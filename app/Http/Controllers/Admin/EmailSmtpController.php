<?php

namespace App\Http\Controllers\Admin;

use App\Models\SystemSetting;
use App\Models\EmailLog;
use App\Models\EmailTemplate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Config;
use Illuminate\Mail\Message;

class EmailSmtpController extends BaseAdminController
{
    /**
     * Display Email SMTP settings
     */
    public function index()
    {
        // Get settings from database
        $settings = [];
        if (Schema::hasTable('system_settings')) {
            $emailSettings = SystemSetting::where('group', 'email_smtp')->get()->keyBy('key');
            foreach ($emailSettings as $key => $setting) {
                $settings[$key] = $setting->value;
            }
        }
        
        // Merge with config defaults
        $settings = array_merge([
            'mailer' => config('mail.default', 'smtp'),
            'host' => config('mail.mailers.smtp.host', ''),
            'port' => config('mail.mailers.smtp.port', 587),
            'username' => config('mail.mailers.smtp.username', ''),
            'password' => config('mail.mailers.smtp.password', ''),
            'encryption' => config('mail.mailers.smtp.encryption', 'tls'),
            'from_address' => config('mail.from.address', ''),
            'from_name' => config('mail.from.name', ''),
            'timeout' => config('mail.mailers.smtp.timeout', 30),
            'auth_mode' => config('mail.mailers.smtp.auth_mode', 'login'),
            'verify_peer' => config('mail.mailers.smtp.verify_peer', true),
            'max_retries' => config('mail.mailers.smtp.max_retries', 3),
            'queue_enabled' => config('mail.queue_enabled', false),
            'queue_connection' => config('mail.queue_connection', 'database'),
            'rate_limit' => config('mail.rate_limit', 100),
            'rate_limit_period' => config('mail.rate_limit_period', 60),
        ], $settings);

        // Email logs (last 50) if table exists
        $emailLogs = collect();
        $emailLogStats = [
            'total' => 0,
            'last_24h' => 0,
            'failed' => 0,
        ];
        if (Schema::hasTable('email_logs')) {
            $emailLogs = EmailLog::orderBy('created_at', 'desc')->limit(50)->get();
            $emailLogStats['total'] = EmailLog::count();
            $emailLogStats['last_24h'] = EmailLog::where('created_at', '>=', now()->subDay())->count();
            $emailLogStats['failed'] = EmailLog::where('status', 'failed')->count();
        }

        // Email templates (ensure defaults exist)
        $defaultTemplates = [
            'welcome' => [
                'name' => 'Welcome Email',
                'description' => 'Template for new user registration',
                'subject' => 'Welcome to ' . config('app.name'),
                'body_html' => "<p>Hello {{name}},</p>\n<p>Welcome to {{app_name}}! We are excited to have you on board.</p>\n<p>Best regards,<br>{{app_name}} Team</p>",
            ],
            'password_reset' => [
                'name' => 'Password Reset',
                'description' => 'Template for password reset emails',
                'subject' => 'Reset your password',
                'body_html' => "<p>Hello {{name}},</p>\n<p>We received a request to reset your password. Click the link below to proceed:</p>\n<p><a href=\"{{reset_link}}\">Reset Password</a></p>\n<p>If you did not request this, please ignore this email.</p>",
            ],
            'notification' => [
                'name' => 'Notifications',
                'description' => 'Template for system notifications',
                'subject' => 'New notification from ' . config('app.name'),
                'body_html' => "<p>Hello {{name}},</p>\n<p>{{message}}</p>\n<p>Best regards,<br>{{app_name}} Team</p>",
            ],
        ];

        $emailTemplates = collect();
        if (Schema::hasTable('email_templates')) {
            foreach ($defaultTemplates as $key => $tpl) {
                EmailTemplate::firstOrCreate(
                    ['key' => $key],
                    [
                        'name' => $tpl['name'],
                        'description' => $tpl['description'],
                        'subject' => $tpl['subject'],
                        'body_html' => $tpl['body_html'],
                        'body_text' => null,
                        'is_active' => true,
                    ]
                );
            }
            $emailTemplates = EmailTemplate::orderBy('name')->get()->keyBy('key');
        }
        
        return view('admin.settings.email-smtp', compact('settings', 'emailLogs', 'emailLogStats', 'emailTemplates'));
    }

    /**
     * Update Email SMTP settings
     */
    public function update(Request $request)
    {
        try {
            if (!Schema::hasTable('system_settings')) {
                return response()->json([
                    'success' => false,
                    'message' => 'System settings table does not exist. Please run migrations.',
                ], 500);
            }

            // Get all submitted data
            $data = $request->all();
            unset($data['_token'], $data['_method'], $data['form_type']);
            
            // Validate based on form type
            $formType = $request->input('form_type', 'configuration');
            $validated = $this->validateEmailSettings($request, $formType);
            
            // Merge validated data with all submitted data
            $updateData = array_merge($data, $validated);

            DB::beginTransaction();

            foreach ($updateData as $key => $value) {
                // Skip non-email fields
                if (in_array($key, ['_token', '_method', 'submit', 'form_type'])) {
                    continue;
                }
                
                // Handle boolean values
                if (in_array($key, ['queue_enabled', 'verify_peer'])) {
                    $value = $request->has($key) && ($value === '1' || $value === 1 || $value === true || $value === 'true') ? '1' : '0';
                }
                
                // Only update if value is not empty or is explicitly set
                if ($value !== null && $value !== '') {
                    SystemSetting::updateOrCreate(
                        ['key' => $key],
                        [
                            'value' => $value,
                            'type' => $this->getFieldType($key, $value),
                            'group' => 'email_smtp',
                            'description' => $this->getFieldDescription($key),
                        ]
                    );
                }
            }

            // Update Laravel config dynamically
            $this->updateMailConfig($updateData);

            DB::commit();

            Log::info('Email SMTP settings updated', [
                'updated_by' => auth()->id(),
                'updated_fields' => array_keys($updateData),
            ]);

            if ($request->expectsJson() || $request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Email SMTP settings updated successfully!',
                ]);
            }

            return redirect()->route('admin.settings.email-smtp')
                ->with('success', 'Email SMTP settings updated successfully!');
                
        } catch (\Illuminate\Validation\ValidationException $e) {
            DB::rollBack();
            if ($request->expectsJson() || $request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $e->errors(),
                ], 422);
            }
            return back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to update email SMTP settings', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            if ($request->expectsJson() || $request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to update settings: ' . $e->getMessage(),
                ], 500);
            }

            return $this->errorResponse('Failed to update settings: ' . $e->getMessage());
        }
    }

    /**
     * Test email connection
     */
    public function testConnection(Request $request)
    {
        try {
            // Get settings
            $settings = [];
            if (Schema::hasTable('system_settings')) {
                $emailSettings = SystemSetting::where('group', 'email_smtp')->get()->keyBy('key');
                foreach ($emailSettings as $key => $setting) {
                    $settings[$key] = $setting->value;
                }
            }

            // Override with request values if provided
            $host = $request->input('host') ?? $settings['host'] ?? config('mail.mailers.smtp.host');
            $port = $request->input('port') ?? $settings['port'] ?? config('mail.mailers.smtp.port', 587);
            $username = $request->input('username') ?? $settings['username'] ?? config('mail.mailers.smtp.username');
            $password = $request->input('password') ?? $settings['password'] ?? config('mail.mailers.smtp.password');
            $encryption = $request->input('encryption') ?? $settings['encryption'] ?? config('mail.mailers.smtp.encryption', 'tls');

            if (empty($host) || empty($port) || empty($username) || empty($password)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Host, Port, Username, and Password are required',
                ], 400);
            }

            // Test SMTP connection
            $connection = @fsockopen($host, $port, $errno, $errstr, 10);
            
            if (!$connection) {
                return response()->json([
                    'success' => false,
                    'message' => "Connection failed: {$errstr} (Error {$errno})",
                ], 400);
            }
            
            fclose($connection);

            return response()->json([
                'success' => true,
                'message' => 'SMTP connection successful!',
                'details' => [
                    'host' => $host,
                    'port' => $port,
                    'encryption' => $encryption,
                ],
            ]);

        } catch (\Exception $e) {
            Log::error('Email connection test failed', [
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Connection test failed: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Test email sending
     */
    public function test(Request $request)
    {
        try {
            $validated = $request->validate([
                'test_email' => 'required|email',
                'subject' => 'nullable|string|max:255',
                'message' => 'nullable|string',
            ]);

            // Temporarily update mail config with current settings
            $this->updateMailConfigFromDatabase();
            
            // CRITICAL: Ensure default mailer is smtp, not log
            Config::set('mail.default', 'smtp');
            
            $testEmail = $validated['test_email'];
            $subject = $validated['subject'] ?? 'Test Email from ' . config('app.name');
            $message = $validated['message'] ?? 'This is a test email to verify your SMTP configuration is working correctly.';
            
            // Send test email and log
            Mail::mailer('smtp')->raw($message, function (Message $mail) use ($testEmail, $subject) {
                $mail->to($testEmail)
                     ->subject($subject);
            });
            
            // Log to application log
            Log::info('Test email sent', [
                'to' => $testEmail,
                'sent_by' => auth()->id(),
            ]);

            // Persist to email_logs table
            if (Schema::hasTable('email_logs')) {
                EmailLog::create([
                    'to' => $testEmail,
                    'subject' => $subject,
                    'body' => $message,
                    'status' => 'sent',
                    'error_message' => null,
                    'meta' => [
                        'type' => 'smtp_test',
                        'sent_by' => auth()->id(),
                        'mailer' => config('mail.default'),
                    ],
                    'sent_at' => now(),
                ]);
            }

            if ($request->expectsJson() || $request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Test email sent successfully to ' . $testEmail,
                ]);
            }

            return back()->with('success', 'Test email sent successfully!');

        } catch (\Exception $e) {
            Log::error('Test email failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            // Persist failure to email_logs
            if (Schema::hasTable('email_logs')) {
                EmailLog::create([
                    'to' => $request->input('test_email'),
                    'subject' => $request->input('subject') ?? 'Test Email from ' . config('app.name'),
                    'body' => $request->input('message') ?? null,
                    'status' => 'failed',
                    'error_message' => $e->getMessage(),
                    'meta' => [
                        'type' => 'smtp_test',
                        'sent_by' => auth()->id(),
                        'mailer' => config('mail.default'),
                    ],
                    'sent_at' => now(),
                ]);
            }

            if ($request->expectsJson() || $request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Email test failed: ' . $e->getMessage(),
                ], 500);
            }

            return back()->with('error', 'Email test failed: ' . $e->getMessage());
        }
    }

    /**
     * Update an email template (Welcome, Password Reset, Notifications, etc.)
     */
    public function updateTemplate(Request $request, string $key)
    {
        if (!Schema::hasTable('email_templates')) {
            return back()->with('error', 'Email templates table does not exist. Please run migrations.');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'subject' => 'required|string|max:255',
            'body_html' => 'nullable|string',
            'body_text' => 'nullable|string',
            'is_active' => 'nullable|boolean',
        ]);

        $template = EmailTemplate::updateOrCreate(
            ['key' => $key],
            [
                'name' => $validated['name'],
                'subject' => $validated['subject'],
                'body_html' => $validated['body_html'] ?? null,
                'body_text' => $validated['body_text'] ?? null,
                'description' => $request->input('description'),
                'is_active' => $request->has('is_active'),
                'updated_by' => auth()->id(),
            ]
        );

        Log::info('Email template updated', [
            'key' => $template->key,
            'updated_by' => auth()->id(),
        ]);

        if ($request->expectsJson() || $request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Template updated successfully.',
            ]);
        }

        return redirect()->route('admin.settings.email-smtp')->with('success', 'Email template updated successfully.');
    }

    /**
     * Validate email settings based on form type
     */
    private function validateEmailSettings(Request $request, $formType)
    {
        $rules = [];
        
        if ($formType === 'configuration') {
            if ($request->has('mailer')) {
                $rules['mailer'] = 'required|in:smtp,sendmail,mailgun,ses,postmark,log';
            }
            if ($request->has('host')) {
                $rules['host'] = 'required|string|max:255';
            }
            if ($request->has('port')) {
                $rules['port'] = 'required|integer|min:1|max:65535';
            }
            if ($request->has('username')) {
                $rules['username'] = 'required|string|max:255';
            }
            if ($request->has('password')) {
                $rules['password'] = 'nullable|string|max:500';
            }
            if ($request->has('encryption')) {
                $rules['encryption'] = 'required|in:tls,ssl,none';
            }
            if ($request->has('from_address')) {
                $rules['from_address'] = 'required|email|max:255';
            }
            if ($request->has('from_name')) {
                $rules['from_name'] = 'required|string|max:255';
            }
        }
        
        if ($formType === 'advanced') {
            if ($request->has('timeout')) {
                $rules['timeout'] = 'nullable|integer|min:1|max:300';
            }
            if ($request->has('auth_mode')) {
                $rules['auth_mode'] = 'nullable|in:login,plain,cram-md5';
            }
            if ($request->has('verify_peer')) {
                $rules['verify_peer'] = 'nullable|boolean';
            }
            if ($request->has('max_retries')) {
                $rules['max_retries'] = 'nullable|integer|min:0|max:10';
            }
            if ($request->has('queue_enabled')) {
                $rules['queue_enabled'] = 'nullable|boolean';
            }
            if ($request->has('queue_connection')) {
                $rules['queue_connection'] = 'nullable|string|max:50';
            }
            if ($request->has('rate_limit')) {
                $rules['rate_limit'] = 'nullable|integer|min:1|max:1000';
            }
            if ($request->has('rate_limit_period')) {
                $rules['rate_limit_period'] = 'nullable|integer|min:1|max:3600';
            }
        }
        
        if (empty($rules)) {
            return [];
        }
        
        return $request->validate($rules);
    }

    /**
     * Get field type
     */
    private function getFieldType(string $key, $value)
    {
        if (in_array($key, ['queue_enabled', 'verify_peer'])) {
            return 'boolean';
        }
        if (in_array($key, ['password'])) {
            return 'password';
        }
        if (in_array($key, ['port', 'timeout', 'max_retries', 'rate_limit', 'rate_limit_period'])) {
            return 'number';
        }
        if (filter_var($value, FILTER_VALIDATE_EMAIL)) {
            return 'email';
        }
        return 'text';
    }

    /**
     * Get field description
     */
    private function getFieldDescription(string $key)
    {
        $descriptions = [
            'mailer' => 'Email mailer driver',
            'host' => 'SMTP server hostname',
            'port' => 'SMTP server port',
            'username' => 'SMTP username',
            'password' => 'SMTP password',
            'encryption' => 'Encryption method (TLS/SSL)',
            'from_address' => 'Default sender email address',
            'from_name' => 'Default sender name',
            'timeout' => 'Connection timeout in seconds',
            'auth_mode' => 'Authentication mode',
            'verify_peer' => 'Verify SSL certificate',
            'max_retries' => 'Maximum retry attempts',
            'queue_enabled' => 'Enable email queue',
            'queue_connection' => 'Queue connection name',
            'rate_limit' => 'Emails per rate limit period',
            'rate_limit_period' => 'Rate limit period in seconds',
        ];
        
        return $descriptions[$key] ?? null;
    }

    /**
     * Update mail config dynamically
     */
    private function updateMailConfig(array $settings)
    {
        if (isset($settings['mailer'])) {
            Config::set('mail.default', $settings['mailer']);
        }
        
        if (isset($settings['host'])) {
            Config::set('mail.mailers.smtp.host', $settings['host']);
        }
        
        if (isset($settings['port'])) {
            Config::set('mail.mailers.smtp.port', $settings['port']);
        }
        
        if (isset($settings['username'])) {
            Config::set('mail.mailers.smtp.username', $settings['username']);
        }
        
        if (isset($settings['password'])) {
            Config::set('mail.mailers.smtp.password', $settings['password']);
        }
        
        if (isset($settings['encryption'])) {
            Config::set('mail.mailers.smtp.encryption', $settings['encryption']);
        }
        
        if (isset($settings['from_address'])) {
            Config::set('mail.from.address', $settings['from_address']);
        }
        
        if (isset($settings['from_name'])) {
            Config::set('mail.from.name', $settings['from_name']);
        }
        
        if (isset($settings['timeout'])) {
            Config::set('mail.mailers.smtp.timeout', $settings['timeout']);
        }
    }

    /**
     * Update mail config from database
     */
    private function updateMailConfigFromDatabase()
    {
        if (!Schema::hasTable('system_settings')) {
            return;
        }
        
        $settings = SystemSetting::where('group', 'email_smtp')->get()->keyBy('key');
        
        foreach ($settings as $key => $setting) {
            switch ($key) {
                case 'mailer':
                    Config::set('mail.default', $setting->value);
                    break;
                case 'host':
                    Config::set('mail.mailers.smtp.host', $setting->value);
                    break;
                case 'port':
                    Config::set('mail.mailers.smtp.port', $setting->value);
                    break;
                case 'username':
                    Config::set('mail.mailers.smtp.username', $setting->value);
                    break;
                case 'password':
                    Config::set('mail.mailers.smtp.password', $setting->value);
                    break;
                case 'encryption':
                    Config::set('mail.mailers.smtp.encryption', $setting->value);
                    break;
                case 'from_address':
                    Config::set('mail.from.address', $setting->value);
                    break;
                case 'from_name':
                    Config::set('mail.from.name', $setting->value);
                    break;
                case 'timeout':
                    Config::set('mail.mailers.smtp.timeout', $setting->value);
                    break;
            }
        }
    }
}



