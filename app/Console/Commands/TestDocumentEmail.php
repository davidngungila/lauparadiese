<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;
use App\Models\EmailAccount;
use Barryvdh\DomPDF\Facade\Pdf;

class TestDocumentEmail extends Command
{
    protected $signature = 'test:document-email {email=davidngungila@gmail.com}';
    protected $description = 'Test sending document email with PDF attachment';

    public function handle()
    {
        $testEmail = $this->argument('email');
        
        $this->info('Testing email sending to: ' . $testEmail);
        $this->newLine();
        
        try {
            // Configure mail settings
            $this->configureMailSettings();
            
            // Try to get a real booking for testing, or create minimal test data
            $booking = \App\Models\Booking::first();
            
            if ($booking) {
                $this->info('Using real booking: ' . $booking->booking_reference);
                $pdf = Pdf::loadView('pdf.documents.booking-confirmation-voucher', compact('booking'));
            } else {
                $this->info('No bookings found, creating simple test email without PDF...');
                $pdf = null;
            }
            
            $pdfContent = $pdf ? $pdf->output() : null;
            
            $subject = 'Test Email - Booking Confirmation';
            $message = "Dear Test User,\n\n";
            $message .= "This is a test email to verify email sending functionality.\n\n";
            $message .= "If you receive this email, the email system is working correctly!\n\n";
            $message .= "Best regards,\nLau Paradise Adventures";
            
            // Set stream context
            stream_context_set_default([
                'ssl' => [
                    'verify_peer' => false,
                    'verify_peer_name' => false,
                    'allow_self_signed' => true,
                ],
            ]);
            
            // Configure SwiftMailer transport
            $this->configureSwiftMailerTransport();
            
            $this->info('Sending email...');
            Mail::raw($message, function($mail) use ($testEmail, $subject, $pdfContent) {
                $mail->to($testEmail)->subject($subject);
                if ($pdfContent) {
                    $mail->attachData($pdfContent, 'test-booking-confirmation.pdf', [
                        'mime' => 'application/pdf',
                    ]);
                }
            });
            
            $this->info('✓ Email sent successfully to ' . $testEmail);
            $this->info('Please check the inbox (and spam folder) for the test email.');
            
            return 0;
        } catch (\Exception $e) {
            $this->error('Failed to send email: ' . $e->getMessage());
            $this->error('Trace: ' . $e->getTraceAsString());
            Log::error('Test email failed: ' . $e->getMessage(), [
                'email' => $testEmail,
                'trace' => $e->getTraceAsString()
            ]);
            return 1;
        }
    }
    
    private function configureMailSettings()
    {
        try {
            // Set default stream context
            stream_context_set_default([
                'ssl' => [
                    'verify_peer' => false,
                    'verify_peer_name' => false,
                    'allow_self_signed' => true,
                ],
            ]);
            
            // Try to get email account
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
                $this->info('Using email account: ' . $emailAccount->email);
            } else {
                // Fallback to default Gmail settings
                Config::set('mail.default', 'smtp');
                Config::set('mail.mailers.smtp.host', env('MAIL_HOST', 'smtp.gmail.com'));
                Config::set('mail.mailers.smtp.port', env('MAIL_PORT', 587));
                Config::set('mail.mailers.smtp.username', env('MAIL_USERNAME', 'lauparadiseadventure@gmail.com'));
                Config::set('mail.mailers.smtp.password', env('MAIL_PASSWORD', 'cykk ionu mmil lusd'));
                Config::set('mail.mailers.smtp.encryption', env('MAIL_ENCRYPTION', 'tls'));
                Config::set('mail.from.address', env('MAIL_FROM_ADDRESS', 'lauparadiseadventure@gmail.com'));
                Config::set('mail.from.name', env('MAIL_FROM_NAME', 'Lau Paradise Adventures'));
                $this->info('Using default Gmail settings');
            }
            
            $this->info('Mail configured: ' . Config::get('mail.mailers.smtp.host') . ':' . Config::get('mail.mailers.smtp.port'));
        } catch (\Exception $e) {
            $this->warn('Failed to configure mail settings: ' . $e->getMessage());
        }
    }
    
    /**
     * Configure mail transport to disable SSL verification
     * Works with Symfony Mailer (Laravel 12 default)
     */
    private function configureSwiftMailerTransport()
    {
        // Set stream context to disable SSL verification
        // This works with Symfony Mailer (Laravel 12 default)
        // Note: stream_context_set_default is already called in configureMailSettings()
        // This method is kept for consistency but the stream context is the key
        stream_context_set_default([
            'ssl' => [
                'verify_peer' => false,
                'verify_peer_name' => false,
                'allow_self_signed' => true,
            ],
        ]);
    }
}

