<?php

namespace App\Console\Commands;

use App\Models\EmailAccount;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Mail\Message;

class TestEmailSend extends Command
{
    protected $signature = 'email:test-send {email=davidngungila@gmail.com} {--v}';
    protected $description = 'Test sending email with detailed diagnostics';

    public function handle()
    {
        $testEmail = $this->argument('email');
        $verbose = $this->option('v');

        $this->info('=== Email Sending Test ===');
        $this->newLine();

        // Get email account
        $account = EmailAccount::where('email', 'lauparadiseadventure@gmail.com')->first();
        
        if (!$account) {
            $this->error('Email account not found! Please run: php artisan email:configure-account');
            return 1;
        }

        $this->info('Account found: ' . $account->name);
        $this->line('Email: ' . $account->email);
        $this->newLine();

        // Show current mail config
        if ($verbose) {
            $this->info('Current Mail Configuration:');
            $this->line('  Driver: ' . config('mail.default'));
            $this->line('  Host: ' . config('mail.mailers.smtp.host'));
            $this->line('  Port: ' . config('mail.mailers.smtp.port'));
            $this->line('  Encryption: ' . config('mail.mailers.smtp.encryption'));
            $this->line('  Username: ' . config('mail.mailers.smtp.username'));
            $this->line('  From: ' . config('mail.from.address'));
            $this->newLine();
        }

        // Configure mailer with account settings
        $config = $account->getSmtpConfig();
        
        $this->info('Configuring SMTP with account settings...');
        Config::set('mail.default', 'smtp');
        Config::set('mail.mailers.smtp.host', $config['host']);
        Config::set('mail.mailers.smtp.port', $config['port']);
        Config::set('mail.mailers.smtp.encryption', $config['encryption']);
        Config::set('mail.mailers.smtp.username', $config['username']);
        Config::set('mail.mailers.smtp.password', $config['password']);
        Config::set('mail.mailers.smtp.timeout', 30);
        Config::set('mail.from.address', $account->email);
        Config::set('mail.from.name', $account->name);

        if ($verbose) {
            $this->info('Updated Mail Configuration:');
            $this->line('  Host: ' . $config['host']);
            $this->line('  Port: ' . $config['port']);
            $this->line('  Encryption: ' . $config['encryption']);
            $this->line('  Username: ' . $config['username']);
            $this->line('  Password: ' . (strlen($config['password']) > 0 ? '***' . substr($config['password'], -4) : 'NOT SET'));
            $this->newLine();
        }

        // Test SMTP connection first
        $this->info('Testing SMTP connection...');
        $connection = @fsockopen($config['host'], $config['port'], $errno, $errstr, 10);
        
        if (!$connection) {
            $this->error("✗ Cannot connect to {$config['host']}:{$config['port']}");
            $this->error("  Error: {$errstr} (Code: {$errno})");
            return 1;
        }
        
        fclose($connection);
        $this->info("✓ SMTP connection successful to {$config['host']}:{$config['port']}");
        $this->newLine();

        // Try sending email
        $this->info("Sending test email to: {$testEmail}");
        $this->newLine();

        try {
            // Enable verbose logging
            if ($verbose) {
                Log::info('Starting email send test', [
                    'to' => $testEmail,
                    'from' => $account->email,
                    'config' => [
                        'host' => $config['host'],
                        'port' => $config['port'],
                        'encryption' => $config['encryption'],
                    ]
                ]);
            }

            // Send email using view - explicitly use smtp mailer
            Mail::mailer('smtp')->send('emails.test-advanced', [
                'accountName' => $account->name,
                'accountEmail' => $account->email,
                'smtpHost' => $config['host'],
                'smtpPort' => $config['port'],
                'smtpEncryption' => $config['encryption'],
                'testDate' => now()->format('F j, Y \a\t g:i A'),
            ], function (Message $mail) use ($testEmail, $account) {
                $mail->to($testEmail)
                     ->from($account->email, $account->name)
                     ->subject('✓ Test Email - SMTP Configuration Successful | Lau Paradise Adventures');
            });

            $this->info('✓ Email sent successfully!');
            $this->newLine();
            
            // Log to email_logs if table exists
            if (DB::getSchemaBuilder()->hasTable('email_logs')) {
                DB::table('email_logs')->insert([
                    'to' => $testEmail,
                    'subject' => '✓ Test Email - SMTP Configuration Successful | Lau Paradise Adventures',
                    'body' => 'Test email sent via command',
                    'status' => 'sent',
                    'error_message' => null,
                    'meta' => json_encode([
                        'type' => 'command_test',
                        'account_id' => $account->id,
                        'account_email' => $account->email,
                    ]),
                    'sent_at' => now(),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
                $this->info('✓ Email logged to database');
            }

            $this->newLine();
            $this->info('=== Test Complete ===');
            $this->warn('If you don\'t see the email:');
            $this->line('  1. Check spam/junk folder');
            $this->line('  2. Wait a few minutes (Gmail can delay emails)');
            $this->line('  3. Verify the recipient email is correct');
            $this->line('  4. Check Gmail account security settings');
            $this->line('  5. Ensure "Less secure app access" is enabled or use App Password');
            $this->newLine();

            return 0;

        } catch (\Swift_TransportException $e) {
            $this->error('✗ Swift Transport Exception:');
            $this->error('  ' . $e->getMessage());
            $this->newLine();
            $this->warn('Common issues:');
            $this->line('  - Wrong password or username');
            $this->line('  - Gmail requires App Password (not regular password)');
            $this->line('  - 2FA must be enabled to generate App Password');
            $this->line('  - Check firewall/network blocking port 587');
            return 1;

        } catch (\Exception $e) {
            $this->error('✗ Error sending email:');
            $this->error('  ' . $e->getMessage());
            $this->newLine();
            
            if ($verbose) {
                $this->warn('Full error trace:');
                $this->line($e->getTraceAsString());
            }

            // Log error
            Log::error('Email send test failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'to' => $testEmail,
            ]);

            // Log to email_logs if table exists
            if (DB::getSchemaBuilder()->hasTable('email_logs')) {
                DB::table('email_logs')->insert([
                    'to' => $testEmail,
                    'subject' => 'Test Email - FAILED',
                    'body' => null,
                    'status' => 'failed',
                    'error_message' => $e->getMessage(),
                    'meta' => json_encode([
                        'type' => 'command_test',
                        'account_id' => $account->id,
                    ]),
                    'sent_at' => now(),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            return 1;
        }
    }
}

