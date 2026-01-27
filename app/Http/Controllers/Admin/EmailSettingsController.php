<?php

namespace App\Http\Controllers\Admin;

use App\Models\EmailAccount;
use App\Services\EmailService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;

class EmailSettingsController extends BaseAdminController
{
    protected $emailService;
    
    public function __construct()
    {
        // EmailService will be resolved when needed
        if (class_exists(\App\Services\EmailService::class)) {
            $this->emailService = app(\App\Services\EmailService::class);
        }
    }

    /**
     * List email accounts
     */
    public function index()
    {
        $accounts = EmailAccount::with('user')->orderBy('is_default', 'desc')->orderBy('created_at', 'desc')->get();
        
        return view('admin.settings.email-accounts', compact('accounts'));
    }

    /**
     * Show create form
     */
    public function create()
    {
        return view('admin.settings.email-account-form');
    }

    /**
     * Store new email account
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:email_accounts,email',
            'protocol' => 'required|in:imap,pop3',
            'imap_host' => 'required_if:protocol,imap|nullable|string',
            'imap_port' => 'required_if:protocol,imap|nullable|integer|min:1|max:65535',
            'imap_encryption' => 'required_if:protocol,imap|nullable|in:ssl,tls,none',
            'pop3_host' => 'required_if:protocol,pop3|nullable|string',
            'pop3_port' => 'required_if:protocol,pop3|nullable|integer|min:1|max:65535',
            'pop3_encryption' => 'required_if:protocol,pop3|nullable|in:ssl,tls,none',
            'smtp_host' => 'required|string',
            'smtp_port' => 'required|integer|min:1|max:65535',
            'smtp_encryption' => 'required|in:ssl,tls,none',
            'username' => 'required|string',
            'password' => 'required|string',
            'is_active' => 'nullable|boolean',
            'is_default' => 'nullable|boolean',
            'check_interval' => 'nullable|integer|min:1',
            'notes' => 'nullable|string',
        ]);
        
        // If this is set as default, unset others
        if ($request->filled('is_default') && $request->is_default) {
            EmailAccount::where('is_default', true)->update(['is_default' => false]);
        }
        
        $account = EmailAccount::create($validated);
        
        // Test connection if service exists
        if ($this->emailService && method_exists($this->emailService, 'testConnection')) {
            try {
                $testResult = $this->emailService->testConnection($account);
                if (!$testResult['imap'] && !$testResult['smtp']) {
                    return $this->errorResponse('Account created but connection test failed. Please check settings.', route('admin.settings.email-accounts.index'));
                }
            } catch (\Exception $e) {
                // Connection test failed, but account was created
            }
        }
        
        return $this->successResponse('Email account created successfully!', route('admin.settings.email-accounts.index'));
    }

    /**
     * Show edit form
     */
    public function edit(EmailAccount $emailAccount)
    {
        return view('admin.settings.email-account-form', ['account' => $emailAccount]);
    }

    /**
     * Update email account
     */
    public function update(Request $request, EmailAccount $emailAccount)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:email_accounts,email,' . $emailAccount->id,
            'protocol' => 'required|in:imap,pop3',
            'imap_host' => 'required_if:protocol,imap|nullable|string',
            'imap_port' => 'required_if:protocol,imap|nullable|integer|min:1|max:65535',
            'imap_encryption' => 'required_if:protocol,imap|nullable|in:ssl,tls,none',
            'pop3_host' => 'required_if:protocol,pop3|nullable|string',
            'pop3_port' => 'required_if:protocol,pop3|nullable|integer|min:1|max:65535',
            'pop3_encryption' => 'required_if:protocol,pop3|nullable|in:ssl,tls,none',
            'smtp_host' => 'required|string',
            'smtp_port' => 'required|integer|min:1|max:65535',
            'smtp_encryption' => 'required|in:ssl,tls,none',
            'username' => 'required|string',
            'password' => 'nullable|string',
            'is_active' => 'nullable|boolean',
            'is_default' => 'nullable|boolean',
            'check_interval' => 'nullable|integer|min:1',
            'notes' => 'nullable|string',
        ]);
        
        // If password is not provided, don't update it
        if (empty($validated['password'])) {
            unset($validated['password']);
        }
        
        // If this is set as default, unset others
        if ($request->filled('is_default') && $request->is_default) {
            EmailAccount::where('id', '!=', $emailAccount->id)
                ->where('is_default', true)
                ->update(['is_default' => false]);
        }
        
        $emailAccount->update($validated);
        
        return $this->successResponse('Email account updated successfully!', route('admin.settings.email-accounts.index'));
    }

    /**
     * Test email account connection
     */
    public function testConnection(EmailAccount $emailAccount)
    {
        try {
            if (!$this->emailService || !method_exists($this->emailService, 'testConnection')) {
                return $this->errorResponse('Email service not available for testing.', route('admin.settings.email-accounts.index'));
            }
            
            $result = $this->emailService->testConnection($emailAccount);
            
            if ($result['imap'] && $result['smtp']) {
                return back()->with('success', 'Connection test successful!')->with('test_result', $result);
            } else {
                $errors = implode(', ', $result['errors'] ?? []);
                return back()->with('error', 'Connection test failed: ' . $errors)->with('test_result', $result);
            }
        } catch (\Exception $e) {
            return back()->with('error', 'Connection test failed: ' . $e->getMessage());
        }
    }

    /**
     * Send test email from email account
     */
    public function sendTestEmail(Request $request, EmailAccount $emailAccount)
    {
        try {
            $validated = $request->validate([
                'test_email' => 'required|email',
                'subject' => 'nullable|string|max:255',
            ]);

            // Configure mailer with account settings
            $config = $emailAccount->getSmtpConfig();
            
            // CRITICAL: Set default mailer to smtp (not log)
            Config::set('mail.default', 'smtp');
            Config::set('mail.mailers.smtp.host', $config['host']);
            Config::set('mail.mailers.smtp.port', $config['port']);
            Config::set('mail.mailers.smtp.encryption', $config['encryption']);
            Config::set('mail.mailers.smtp.username', $config['username']);
            Config::set('mail.mailers.smtp.password', $config['password']);
            Config::set('mail.mailers.smtp.timeout', 30);
            Config::set('mail.from.address', $emailAccount->email);
            Config::set('mail.from.name', $emailAccount->name);

            $testEmail = $validated['test_email'];
            $subject = $validated['subject'] ?? '✓ Test Email - SMTP Configuration Successful | ' . $emailAccount->name;

            // Send beautiful HTML email - explicitly use smtp mailer
            Mail::mailer('smtp')->send('emails.test-advanced', [
                'accountName' => $emailAccount->name,
                'accountEmail' => $emailAccount->email,
                'smtpHost' => $config['host'],
                'smtpPort' => $config['port'],
                'smtpEncryption' => $config['encryption'],
                'testDate' => now()->format('F j, Y \a\t g:i A'),
            ], function ($message) use ($testEmail, $subject, $emailAccount) {
                $message->to($testEmail)
                         ->subject($subject)
                         ->from($emailAccount->email, $emailAccount->name);
            });

            return back()->with('success', 'Test email sent successfully to ' . $testEmail . '!');
        } catch (\Exception $e) {
            Log::error('Test email failed', [
                'account_id' => $emailAccount->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return back()->with('error', 'Failed to send test email: ' . $e->getMessage());
        }
    }

    /**
     * Delete email account
     */
    public function destroy(EmailAccount $emailAccount)
    {
        // Don't allow deletion if it's the only account
        if (EmailAccount::count() <= 1) {
            return $this->errorResponse('Cannot delete the only email account!', route('admin.settings.email-accounts.index'));
        }
        
        $emailAccount->delete();
        
        return $this->successResponse('Email account deleted successfully!', route('admin.settings.email-accounts.index'));
    }
}
