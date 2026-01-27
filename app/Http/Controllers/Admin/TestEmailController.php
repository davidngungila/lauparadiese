<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\BaseAdminController;
use App\Models\EmailAccount;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Booking;

class TestEmailController extends BaseAdminController
{
    /**
     * Test sending email with PDF attachment
     */
    public function testEmail(Request $request)
    {
        $testEmail = $request->get('email', 'davidngungila@gmail.com');
        
        try {
            // Configure mail settings
            $this->configureMailSettings();
            
            // Create a test PDF
            $testData = [
                'booking_reference' => 'TEST-' . time(),
                'customer_name' => 'Test User',
                'departure_date' => now(),
            ];
            
            $pdf = Pdf::loadView('pdf.documents.booking-confirmation-voucher', ['booking' => (object)$testData]);
            $pdfContent = $pdf->output();
            
            $subject = 'Test Email - Booking Confirmation';
            $message = "Dear Test User,\n\n";
            $message .= "This is a test email to verify email sending functionality.\n\n";
            $message .= "If you receive this email, the email system is working correctly!\n\n";
            $message .= "Best regards,\nLau Paradise Adventures";
            
            // Configure SwiftMailer transport with SSL options
            $this->configureSwiftMailerTransport();
            
            Mail::raw($message, function($mail) use ($testEmail, $subject, $pdfContent) {
                $mail->to($testEmail)
                     ->subject($subject)
                     ->attachData($pdfContent, 'test-booking-confirmation.pdf', [
                         'mime' => 'application/pdf',
                     ]);
            });
            
            return response()->json([
                'success' => true,
                'message' => 'Test email sent successfully to ' . $testEmail
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to send test email: ' . $e->getMessage(), [
                'email' => $testEmail,
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Failed to send email: ' . $e->getMessage(),
                'trace' => config('app.debug') ? $e->getTraceAsString() : null
            ], 500);
        }
    }
    
    /**
     * Configure mail settings from system settings or email account
     */
    private function configureMailSettings()
    {
        try {
            // Set default stream context for all SSL connections
            stream_context_set_default([
                'ssl' => [
                    'verify_peer' => false,
                    'verify_peer_name' => false,
                    'allow_self_signed' => true,
                ],
            ]);
            
            // Try to get email account first
            $emailAccount = EmailAccount::where('is_active', true)->first();
            
            if ($emailAccount) {
                $config = $emailAccount->getSmtpConfig();
                Config::set('mail.default', 'smtp');
                Config::set('mail.mailers.smtp.host', $config['host'] ?? 'smtp.gmail.com');
                Config::set('mail.mailers.smtp.port', $config['port'] ?? 587);
                Config::set('mail.mailers.smtp.username', $config['username'] ?? $emailAccount->username ?? '');
                Config::set('mail.mailers.smtp.password', $config['password'] ?? $emailAccount->password ?? '');
                Config::set('mail.mailers.smtp.encryption', $config['encryption'] ?? $emailAccount->smtp_encryption ?? 'tls');
                Config::set('mail.from.address', $emailAccount->email ?? env('MAIL_FROM_ADDRESS', 'noreply@example.com'));
                Config::set('mail.from.name', $emailAccount->name ?? env('MAIL_FROM_NAME', 'Lau Paradise Adventures'));
                return;
            }
            
            // Fallback to environment variables or default Gmail settings
            Config::set('mail.default', env('MAIL_MAILER', 'smtp'));
            Config::set('mail.mailers.smtp.host', env('MAIL_HOST', 'smtp.gmail.com'));
            Config::set('mail.mailers.smtp.port', env('MAIL_PORT', 587));
            Config::set('mail.mailers.smtp.username', env('MAIL_USERNAME', 'lauparadiseadventure@gmail.com'));
            Config::set('mail.mailers.smtp.password', env('MAIL_PASSWORD', 'cykk ionu mmil lusd'));
            Config::set('mail.mailers.smtp.encryption', env('MAIL_ENCRYPTION', 'tls'));
            Config::set('mail.from.address', env('MAIL_FROM_ADDRESS', 'lauparadiseadventure@gmail.com'));
            Config::set('mail.from.name', env('MAIL_FROM_NAME', 'Lau Paradise Adventures'));
        } catch (\Exception $e) {
            Log::warning('Failed to configure mail settings: ' . $e->getMessage());
        }
    }
    
    /**
     * Configure mail transport to disable SSL verification
     */
    private function configureSwiftMailerTransport()
    {
        // Set stream context to disable SSL verification
        // This works for both SwiftMailer and Symfony Mailer
        stream_context_set_default([
            'ssl' => [
                'verify_peer' => false,
                'verify_peer_name' => false,
                'allow_self_signed' => true,
            ],
        ]);
    }
}

