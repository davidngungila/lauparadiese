<?php

namespace App\Console\Commands;

use App\Models\SystemSetting;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Schema;
use Illuminate\Mail\Message;

class ConfigureEmailSmtp extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'email:configure-smtp {--test-email=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Configure email SMTP settings and optionally test sending an email';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        if (!Schema::hasTable('system_settings')) {
            $this->error('System settings table does not exist. Please run migrations.');
            return 1;
        }

        $this->info('Configuring Email SMTP Settings...');
        $this->newLine();

        // Gmail SMTP Configuration
        $settings = [
            'mailer' => 'smtp',
            'host' => 'smtp.gmail.com',
            'port' => '587',
            'username' => 'lauparadiseadventure@gmail.com',
            'password' => 'cykk ionu mmil lusd',
            'encryption' => 'tls',
            'from_address' => 'lauparadiseadventure@gmail.com',
            'from_name' => 'Lau Paradise Adventures',
            'timeout' => '30',
            'auth_mode' => 'login',
            'verify_peer' => '1',
            'max_retries' => '3',
        ];

        $bar = $this->output->createProgressBar(count($settings));
        $bar->start();

        foreach ($settings as $key => $value) {
            SystemSetting::updateOrCreate(
                ['key' => $key],
                [
                    'value' => $value,
                    'type' => $this->getFieldType($key, $value),
                    'group' => 'email_smtp',
                    'description' => $this->getFieldDescription($key),
                ]
            );
            $bar->advance();
        }

        $bar->finish();
        $this->newLine(2);
        $this->info('✓ Email SMTP settings configured successfully!');
        $this->newLine();

        // Update Laravel config dynamically
        $this->updateMailConfig($settings);
        $this->info('✓ Laravel mail configuration updated!');
        $this->newLine();

        // Test email if requested
        $testEmail = $this->option('test-email') ?: 'davidngungila@gmail.com';
        
        $this->info("Sending test email to {$testEmail}...");
        $this->newLine();

        try {
            Mail::raw('This is a test email from Lau Paradise Adventures to verify SMTP configuration is working correctly.

Email Settings:
- SMTP Host: smtp.gmail.com
- Port: 587
- Encryption: TLS
- From: lauparadiseadventure@gmail.com

If you receive this email, your SMTP settings are properly configured!', function (Message $mail) use ($testEmail) {
                $mail->to($testEmail)
                     ->subject('Test Email - SMTP Configuration');
            });

            $this->info("✓ Test email sent successfully to {$testEmail}!");
            $this->info('Please check the inbox (and spam folder) for the test email.');
        } catch (\Exception $e) {
            $this->error("✗ Failed to send test email: " . $e->getMessage());
            $this->newLine();
            $this->warn('Error details:');
            $this->line($e->getTraceAsString());
            return 1;
        }

        $this->newLine();
        $this->info('Configuration complete!');
        $this->info('You can now access the email settings at: /admin/settings/email-smtp');
        
        return 0;
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
}

