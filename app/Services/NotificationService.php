<?php

namespace App\Services;

use App\Models\Notification;
use App\Models\User;
use App\Models\SystemSetting;
use App\Models\NotificationProvider;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Auth;
use Illuminate\Mail\Message;

class NotificationService
{
    protected $smsUsername;
    protected $smsPassword;
    protected $smsFrom;
    protected $smsUrl;
    protected $smsProvider;
    protected $emailProvider;

    public function __construct()
    {
        try {
            // Get primary providers from database
            $this->smsProvider = class_exists(NotificationProvider::class) 
                ? NotificationProvider::getPrimary('sms') 
                : null;
            $this->emailProvider = class_exists(NotificationProvider::class) 
                ? NotificationProvider::getPrimary('email') 
                : null;
            
            // Fallback to SystemSetting if no provider found
            if ($this->smsProvider) {
                $this->smsUsername = $this->smsProvider->sms_username;
                $this->smsPassword = $this->smsProvider->sms_password;
                $this->smsFrom = $this->smsProvider->sms_from;
                $this->smsUrl = $this->smsProvider->sms_url;
            } else {
                // Fallback to SystemSetting, then env
                $this->smsUsername = class_exists(SystemSetting::class) 
                    ? (SystemSetting::getValue('sms_username') ?: env('SMS_USERNAME', 'emcatechn'))
                    : env('SMS_USERNAME', 'emcatechn');
                $this->smsPassword = class_exists(SystemSetting::class) 
                    ? (SystemSetting::getValue('sms_password') ?: env('SMS_PASSWORD', 'Emca@#12'))
                    : env('SMS_PASSWORD', 'Emca@#12');
                $this->smsFrom = class_exists(SystemSetting::class) 
                    ? (SystemSetting::getValue('sms_from') ?: env('SMS_FROM', 'OfisiLink'))
                    : env('SMS_FROM', 'OfisiLink');
                $this->smsUrl = class_exists(SystemSetting::class) 
                    ? (SystemSetting::getValue('sms_url') ?: env('SMS_URL', 'https://messaging-service.co.tz/link/sms/v1/text/single'))
                    : env('SMS_URL', 'https://messaging-service.co.tz/link/sms/v1/text/single');
            }
        } catch (\Exception $e) {
            // Table might not exist yet, use fallback
            Log::warning('NotificationProvider table not available, using env fallback: ' . $e->getMessage());
            $this->smsUsername = env('SMS_USERNAME', 'emcatechn');
            $this->smsPassword = env('SMS_PASSWORD', 'Emca@#12');
            $this->smsFrom = env('SMS_FROM', 'OfisiLink');
            $this->smsUrl = env('SMS_URL', 'https://messaging-service.co.tz/link/sms/v1/text/single');
        }
    }

    /**
     * Send notification to user(s) via all channels
     * 
     * @param array|int $userIds User ID(s) to notify
     * @param string $message Message to send
     * @param string|null $link Optional link for in-app notification
     * @param string|null $subject Optional email subject
     * @param array $data Additional data for email template
     */
    public function notify($userIds, string $message, ?string $link = null, ?string $subject = null, array $data = [])
    {
        if (!is_array($userIds)) {
            $userIds = [$userIds];
        }

        // Check if SMS should be skipped (from $data array)
        $skipSMS = isset($data['skip_sms']) && $data['skip_sms'] === true;
        $users = User::whereIn('id', $userIds)->get();

        foreach ($users as $user) {
            // 1. In-App Notification
            $this->sendInAppNotification($user->id, $message, $link);

            // 2. SMS Notification - check both mobile and phone fields (skip if requested)
            if (!$skipSMS) {
                $phone = $user->mobile ?? $user->phone ?? null;
                if ($phone) {
                    try {
                        $smsResult = $this->sendSMS($phone, $message);
                        if ($smsResult && class_exists(\App\Services\ActivityLogService::class)) {
                            // Log SMS sent activity
                            \App\Services\ActivityLogService::logSMSSent($phone, $message, Auth::id(), $user->id, [
                                'notification_type' => 'multi_channel',
                                'link' => $link,
                            ]);
                        }
                    } catch (\Exception $e) {
                        Log::warning('SMS sending failed in notify method', [
                            'user_id' => $user->id,
                            'phone' => $phone,
                            'error' => $e->getMessage()
                        ]);
                        // Continue with other notifications even if SMS fails
                    }
                }
            }

            // 3. Email Notification
            if ($user->email) {
                $emailSubject = $subject ?? 'Tour Booking Notification';
                $emailResult = $this->sendEmail($user->email, $emailSubject, $message, $data);
                if ($emailResult && class_exists(\App\Services\ActivityLogService::class)) {
                    // Log email sent activity
                    \App\Services\ActivityLogService::logEmailSent($user->email, $emailSubject, Auth::id(), $user->id, [
                        'notification_type' => 'multi_channel',
                        'link' => $link,
                    ]);
                }
            }
        }

        // Log notification sent activity
        if (class_exists(\App\Services\ActivityLogService::class)) {
            \App\Services\ActivityLogService::logNotificationSent($userIds, $message, $link, Auth::id(), [
                'subject' => $subject,
                'notification_count' => count($users),
            ]);
        }

        // Broadcast for real-time toast notifications (if using Laravel Echo/WebSockets)
        $this->broadcastNotification($userIds, $message, $link);
    }

    /**
     * Send notification to phone number directly (for non-user bookings)
     * 
     * @param string $phoneNumber Phone number to send SMS to
     * @param string $message Message to send
     * @param string|null $email Optional email to send notification to
     * @param string|null $subject Optional email subject
     * @param array $data Additional data
     */
    public function notifyPhone(string $phoneNumber, string $message, ?string $email = null, ?string $subject = null, array $data = [])
    {
        // Send SMS
        $skipSMS = isset($data['skip_sms']) && $data['skip_sms'] === true;
        if (!$skipSMS && $phoneNumber) {
            try {
                $this->sendSMS($phoneNumber, $message);
            } catch (\Exception $e) {
                Log::warning('SMS sending failed in notifyPhone method', [
                    'phone' => $phoneNumber,
                    'error' => $e->getMessage()
                ]);
            }
        }

        // Send Email if provided
        if ($email) {
            $emailSubject = $subject ?? 'Tour Booking Notification';
            $this->sendEmail($email, $emailSubject, $message, $data);
        }
    }

    /**
     * Send in-app notification
     */
    protected function sendInAppNotification(int $userId, string $message, ?string $link = null)
    {
        try {
            if (class_exists(Notification::class)) {
                Notification::create([
                    'user_id' => $userId,
                    'message' => $message,
                    'link' => $link,
                    'is_read' => false,
                ]);
            }
        } catch (\Exception $e) {
            Log::error('Failed to create in-app notification: ' . $e->getMessage());
        }
    }

    /**
     * Send SMS using GET method with URL parameters - as per provided example
     */
    public function sendSMS(string $phoneNumber, string $message, ?NotificationProvider $provider = null)
    {
        try {
            // Use provided provider or fallback to default
            $provider = $provider ?? $this->smsProvider;
            
            if ($provider) {
                $smsUsername = $provider->sms_username;
                $smsPassword = $provider->sms_password;
                $smsFrom = $provider->sms_from;
                $smsUrl = $provider->sms_url;
            } else {
                $smsUsername = $this->smsUsername;
                $smsPassword = $this->smsPassword;
                $smsFrom = $this->smsFrom;
                $smsUrl = $this->smsUrl;
            }

            // Validate phone number format
            $phoneNumber = preg_replace('/[^0-9]/', '', $phoneNumber);
            
            if (empty($phoneNumber) || !preg_match('/^255[0-9]{9}$/', $phoneNumber)) {
                // Try to fix format if not already in correct format
                if (!str_starts_with($phoneNumber, '255')) {
                    $phoneNumber = '255' . ltrim($phoneNumber, '0');
                }
                
                // Validate again after formatting
                if (!preg_match('/^255[0-9]{9}$/', $phoneNumber)) {
                    Log::error('SMS sending failed: Invalid phone number format', [
                        'phone' => $phoneNumber,
                        'expected_format' => '255XXXXXXXXX'
                    ]);
                    return false;
                }
            }

            // Debug log
            Log::info('Attempting to send SMS', [
                'phone' => $phoneNumber,
                'message' => substr($message, 0, 50) . (strlen($message) > 50 ? '...' : ''),
                'url' => $smsUrl,
                'from' => $smsFrom
            ]);

            // Check if URL contains '/api/sms/v1/test/text/single' - use POST with JSON
            $usePostMethod = strpos($smsUrl, '/api/sms/v1') !== false || strpos($smsUrl, '/api/') !== false;
            
            $curl = curl_init();
            
            if ($usePostMethod) {
                // Use POST method with JSON body and Basic Auth (as per test_sms_direct.php)
                $auth = base64_encode($smsUsername . ':' . $smsPassword);
                
                $body = json_encode([
                    'from' => $smsFrom,
                    'to' => $phoneNumber,
                    'text' => $message,
                    'reference' => 'tour_' . time()
                ]);
                
                Log::debug('SMS API Request (POST)', [
                    'url' => $smsUrl,
                    'method' => 'POST',
                    'from' => $smsFrom,
                    'to' => $phoneNumber,
                    'body' => $body
                ]);
                
                curl_setopt_array($curl, array(
                    CURLOPT_URL => $smsUrl,
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_POST => true,
                    CURLOPT_POSTFIELDS => $body,
                    CURLOPT_HTTPHEADER => [
                        'Authorization: Basic ' . $auth,
                        'Content-Type: application/json',
                        'Accept: application/json'
                    ],
                    CURLOPT_SSL_VERIFYPEER => false,
                    CURLOPT_SSL_VERIFYHOST => 0,
                    CURLOPT_TIMEOUT => 30,
                    CURLOPT_USERAGENT => 'Tour-Booking-SMS-Client/1.0'
                ));
            } else {
                // Use GET method with URL parameters (legacy support)
                $text = urlencode($message);
                $password = urlencode($smsPassword);
                
                $url = $smsUrl . 
                       '?username=' . urlencode($smsUsername) . 
                       '&password=' . $password . 
                       '&from=' . urlencode($smsFrom) . 
                       '&to=' . $phoneNumber . 
                       '&text=' . $text;
                Log::debug('SMS API Request (GET)', [
                    'url' => $url,
                    'method' => 'GET',
                    'from' => $smsFrom,
                    'to' => $phoneNumber
                ]);

                curl_setopt_array($curl, array(
                    CURLOPT_URL => $url,
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_ENCODING => '',
                    CURLOPT_MAXREDIRS => 10,
                    CURLOPT_TIMEOUT => 30,
                    CURLOPT_FOLLOWLOCATION => true,
                    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                    CURLOPT_CUSTOMREQUEST => 'GET',
                    CURLOPT_SSL_VERIFYPEER => false,
                    CURLOPT_USERAGENT => 'Tour-Booking-SMS-Client/1.0'
                ));
            }

            $response = curl_exec($curl);
            $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
            $curlError = curl_error($curl);
            $curlErrno = curl_errno($curl);

            // Log detailed response
            Log::info('SMS Response', [
                'http_code' => $httpCode,
                'response' => $response
            ]);

            if ($curlErrno) {
                $errorMsg = "cURL Error ({$curlErrno}): {$curlError}";
                Log::error('SMS cURL Error', [
                    'error_code' => $curlErrno,
                    'error_message' => $curlError,
                    'phone' => $phoneNumber,
                    'error' => $errorMsg
                ]);
                curl_close($curl);
                throw new \Exception($errorMsg);
            } else {
                curl_close($curl);
                // Check if SMS was sent successfully based on response
                if ($httpCode == 200) {
                    // Check response content for success indicators
                    $responseLower = strtolower($response ?? '');
                    $responseData = json_decode($response, true);
                    
                    if (strpos($responseLower, 'success') !== false || 
                        strpos($responseLower, '200') !== false ||
                        strpos($responseLower, 'accepted') !== false ||
                        strpos($responseLower, 'sent') !== false ||
                        ($responseData !== null && isset($responseData['success']) && $responseData['success']) ||
                        ($responseData !== null && !isset($responseData['error']))) {
                        
                        Log::info('SMS sent successfully', [
                            'phone' => $phoneNumber,
                            'response' => $response
                        ]);
                        
                        // Log SMS activity if not already logged (for direct SMS calls)
                        try {
                            $userId = Auth::id();
                            $user = User::where('mobile', $phoneNumber)
                                ->orWhere('phone', $phoneNumber)
                                ->first();
                            if (class_exists(\App\Services\ActivityLogService::class)) {
                                \App\Services\ActivityLogService::logSMSSent($phoneNumber, $message, $userId, $user?->id, [
                                    'provider' => $provider ? $provider->name : 'default',
                                    'sms_from' => $smsFrom,
                                    'response_code' => $httpCode,
                                ]);
                            }
                        } catch (\Exception $e) {
                            // Don't fail SMS sending if activity log fails
                            Log::warning('Failed to log SMS activity', ['error' => $e->getMessage()]);
                        }
                        
                        return true;
                    } else {
                        $errorMsg = 'SMS API returned 200 but response indicates failure';
                        if ($responseData && isset($responseData['error'])) {
                            $errorMsg .= ': ' . $responseData['error'];
                        } elseif ($responseData && isset($responseData['message'])) {
                            $errorMsg .= ': ' . $responseData['message'];
                        }
                        
                        Log::warning('SMS API returned 200 but content indicates failure', [
                            'phone' => $phoneNumber,
                            'response' => $response,
                            'error' => $errorMsg
                        ]);
                        throw new \Exception($errorMsg);
                    }
                } else {
                    $errorMsg = "SMS failed with HTTP code {$httpCode}";
                    if ($response) {
                        $errorMsg .= ': ' . substr($response, 0, 200);
                    }
                    
                    Log::error('SMS failed with HTTP code', [
                        'http_code' => $httpCode,
                        'response' => $response,
                        'phone' => $phoneNumber,
                        'error' => $errorMsg
                    ]);
                    throw new \Exception($errorMsg);
                }
            }
        } catch (\Exception $e) {
            Log::error('SMS sending exception', [
                'exception' => get_class($e),
                'message' => $e->getMessage(),
                'phone' => $phoneNumber ?? 'unknown',
                'message_text' => $message ?? 'unknown',
                'trace' => $e->getTraceAsString(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);
            return false;
        }
    }

    /**
     * Send email notification
     */
    protected function sendEmail(string $email, string $subject, string $message, array $data = [], ?NotificationProvider $provider = null)
    {
        try {
            // Use provided provider or fallback to default
            $provider = $provider ?? $this->emailProvider;
            
            if ($provider) {
                // Update mail config from provider
                config([
                    'mail.default' => $provider->mailer_type ?? 'smtp',
                    'mail.mailers.smtp.host' => $provider->mail_host ?? '',
                    'mail.mailers.smtp.port' => $provider->mail_port ?? 587,
                    'mail.mailers.smtp.username' => $provider->mail_username ?? '',
                    'mail.mailers.smtp.password' => $provider->mail_password ?? '',
                    'mail.mailers.smtp.encryption' => $provider->mail_encryption ?? 'tls',
                    'mail.from.address' => $provider->mail_from_address ?? '',
                    'mail.from.name' => $provider->mail_from_name ?? 'Tour Booking',
                ]);
            } else {
                // Fallback to SystemSetting
                $this->updateMailConfigFromSettings();
            }
            
            // Set stream context to disable SSL verification (for self-signed certificates)
            stream_context_set_default([
                'ssl' => [
                    'verify_peer' => false,
                    'verify_peer_name' => false,
                    'allow_self_signed' => true,
                ],
            ]);
            
            Mail::send('emails.notification', [
                'emailMessage' => $message,
                'data' => $data,
            ], function (Message $mail) use ($email, $subject) {
                $mail->to($email)
                     ->subject($subject);
            });
            
            // Log email activity if not already logged (for direct email calls)
            try {
                $userId = Auth::id();
                $user = User::where('email', $email)->first();
                if (class_exists(\App\Services\ActivityLogService::class)) {
                    \App\Services\ActivityLogService::logEmailSent($email, $subject, $userId, $user?->id, [
                        'provider' => $provider ? $provider->name : 'default',
                    ]);
                }
            } catch (\Exception $e) {
                // Don't fail email sending if activity log fails
                Log::warning('Failed to log email activity', ['error' => $e->getMessage()]);
            }
            
            return true;
        } catch (\Exception $e) {
            Log::error('Email sending error: ' . $e->getMessage(), [
                'email' => $email,
                'provider_id' => $provider ? $provider->id : null
            ]);
            return false;
        }
    }
    
    /**
     * Update mail configuration from SystemSetting (fallback)
     */
    protected function updateMailConfigFromSettings()
    {
        if (!class_exists(SystemSetting::class)) {
            return;
        }

        $mailer = SystemSetting::getValue('mail_mailer', config('mail.default', 'smtp'));
        $host = SystemSetting::getValue('mail_host', config('mail.mailers.smtp.host', ''));
        $port = SystemSetting::getValue('mail_port', config('mail.mailers.smtp.port', 587));
        $username = SystemSetting::getValue('mail_username', config('mail.mailers.smtp.username', ''));
        $password = SystemSetting::getValue('mail_password', config('mail.mailers.smtp.password', ''));
        $encryption = SystemSetting::getValue('mail_encryption', config('mail.mailers.smtp.encryption', 'tls'));
        $fromAddress = SystemSetting::getValue('mail_from_address', config('mail.from.address', ''));
        $fromName = SystemSetting::getValue('mail_from_name', config('mail.from.name', 'Tour Booking'));

        config([
            'mail.default' => $mailer,
            'mail.mailers.smtp.host' => $host,
            'mail.mailers.smtp.port' => $port,
            'mail.mailers.smtp.username' => $username,
            'mail.mailers.smtp.password' => $password,
            'mail.mailers.smtp.encryption' => $encryption,
            'mail.from.address' => $fromAddress,
            'mail.from.name' => $fromName,
        ]);
    }

    /**
     * Broadcast notification for real-time updates (toast notifications)
     */
    protected function broadcastNotification(array $userIds, string $message, ?string $link = null)
    {
        // This will be handled by Laravel Broadcasting/WebSockets if configured
        // For now, we'll log it. Frontend can poll for new notifications or use Server-Sent Events
        try {
            if (class_exists(\App\Events\NotificationSent::class)) {
                event(new \App\Events\NotificationSent($userIds, $message, $link));
            }
        } catch (\Exception $e) {
            // If event broadcasting is not set up, silently fail
            Log::debug('Broadcasting not configured: ' . $e->getMessage());
        }
    }

    /**
     * Notify users by role
     */
    public function notifyByRole(array $roleNames, string $message, ?string $link = null, ?string $subject = null, array $data = [])
    {
        $userIds = User::whereHas('roles', function($query) use ($roleNames) {
            $query->whereIn('slug', $roleNames);
        })->pluck('id')->toArray();

        if (!empty($userIds)) {
            $this->notify($userIds, $message, $link, $subject, $data);
        }
    }

    /**
     * Notify travel consultants about new booking
     */
    public function notifyTravelConsultants(string $message, ?string $link = null, ?string $subject = null, array $data = [])
    {
        $this->notifyByRole(['travel-consultant'], $message, $link, $subject, $data);
    }

    /**
     * Notify reservations officers about new booking
     */
    public function notifyReservationsOfficers(string $message, ?string $link = null, ?string $subject = null, array $data = [])
    {
        $this->notifyByRole(['reservations-officer'], $message, $link, $subject, $data);
    }
}






