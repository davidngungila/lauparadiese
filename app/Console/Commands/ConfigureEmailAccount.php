<?php

namespace App\Console\Commands;

use App\Models\EmailAccount;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Schema;
use Illuminate\Mail\Message;

class ConfigureEmailAccount extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'email:configure-account {--test-email=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Configure email account in email-accounts system and test sending';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        if (!Schema::hasTable('email_accounts')) {
            $this->error('Email accounts table does not exist. Please run migrations.');
            return 1;
        }

        $this->info('Configuring Email Account...');
        $this->newLine();

        // Check if account already exists
        $existingAccount = EmailAccount::where('email', 'lauparadiseadventure@gmail.com')->first();
        
        if ($existingAccount) {
            $this->warn('Email account already exists. Updating...');
            $account = $existingAccount;
        } else {
            $this->info('Creating new email account...');
            $account = new EmailAccount();
        }

        // Gmail Configuration
        $account->name = 'Lau Paradise Adventures';
        $account->email = 'lauparadiseadventure@gmail.com';
        $account->protocol = 'imap';
        $account->imap_host = 'imap.gmail.com';
        $account->imap_port = 993;
        $account->imap_encryption = 'ssl';
        $account->smtp_host = 'smtp.gmail.com';
        $account->smtp_port = 587;
        $account->smtp_encryption = 'tls';
        $account->username = 'lauparadiseadventure@gmail.com';
        $account->password = 'cykk ionu mmil lusd';
        $account->is_active = true;
        $account->is_default = true;
        $account->check_interval = 5;
        $account->notes = 'Main email account for Lau Paradise Adventures';

        // If setting as default, unset others
        if ($account->is_default) {
            EmailAccount::where('id', '!=', $account->id ?? 0)
                ->where('is_default', true)
                ->update(['is_default' => false]);
        }

        $account->save();

        $this->info('✓ Email account configured successfully!');
        $this->newLine();

        // Test email if requested
        $testEmail = $this->option('test-email') ?: 'davidngungila@gmail.com';
        
        $this->info("Sending test email to {$testEmail}...");
        $this->newLine();

        $maxAttempts = 3;
        $attempt = 0;
        $success = false;

        while ($attempt < $maxAttempts && !$success) {
            $attempt++;
            $this->line("Attempt {$attempt} of {$maxAttempts}...");

            try {
                // Configure mailer with account settings
                $config = $account->getSmtpConfig();
                
                // CRITICAL: Set default mailer to smtp (not log)
                Config::set('mail.default', 'smtp');
                Config::set('mail.mailers.smtp.host', $config['host']);
                Config::set('mail.mailers.smtp.port', $config['port']);
                Config::set('mail.mailers.smtp.encryption', $config['encryption']);
                Config::set('mail.mailers.smtp.username', $config['username']);
                Config::set('mail.mailers.smtp.password', $config['password']);
                Config::set('mail.mailers.smtp.timeout', 30);
                Config::set('mail.from.address', $account->email);
                Config::set('mail.from.name', $account->name);

                // Send beautiful HTML email - explicitly use smtp mailer
                Mail::mailer('smtp')->send('emails.test-advanced', [
                    'accountName' => $account->name,
                    'accountEmail' => $account->email,
                    'smtpHost' => $config['host'],
                    'smtpPort' => $config['port'],
                    'smtpEncryption' => $config['encryption'],
                    'testDate' => now()->format('F j, Y \a\t g:i A'),
                ], function (Message $mail) use ($testEmail, $account) {
                    $mail->to($testEmail)
                         ->subject('✓ Test Email - SMTP Configuration Successful | Lau Paradise Adventures');
                });

                $this->info("✓ Test email sent successfully to {$testEmail}!");
                $this->info('Please check the inbox (and spam folder) for the test email.');
                $success = true;

            } catch (\Exception $e) {
                $this->error("✗ Attempt {$attempt} failed: " . $e->getMessage());
                
                if ($attempt < $maxAttempts) {
                    $this->warn("Retrying in 2 seconds...");
                    sleep(2);
                } else {
                    $this->newLine();
                    $this->error('Failed to send test email after ' . $maxAttempts . ' attempts.');
                    $this->warn('Error details:');
                    $this->line($e->getTraceAsString());
                    return 1;
                }
            }
        }

        $this->newLine();
        $this->info('Configuration complete!');
        $this->info('You can now access the email accounts at: /admin/settings/email-accounts');
        
        return 0;
    }
}

