<?php

namespace App\Http\Controllers\Admin;

use App\Models\EmailAccount;
use App\Models\EmailMessage;
use App\Services\EmailService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EmailController extends BaseAdminController
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
     * Display inbox
     */
    public function index(Request $request)
    {
        $folder = $request->get('folder', 'inbox'); // inbox, sent, drafts, trash, spam
        
        $query = EmailMessage::with(['account', 'attachments']);
        
        // Filter by folder
        if ($folder === 'inbox') {
            $query->where('type', 'inbox')->where('status', '!=', 'deleted');
        } elseif ($folder === 'sent') {
            $query->where('type', 'sent');
        } elseif ($folder === 'drafts') {
            $query->where('type', 'draft');
        } elseif ($folder === 'trash') {
            $query->where('status', 'deleted')->orWhere('type', 'trash');
        } elseif ($folder === 'spam') {
            $query->where('status', 'spam');
        } else {
            $query->where('type', 'inbox')->where('status', '!=', 'deleted');
        }
        
        // Filter by account
        if ($request->filled('account_id')) {
            $query->where('email_account_id', $request->account_id);
        }
        
        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        
        // Filter by important
        if ($request->filled('important') && $request->important == '1') {
            $query->where('is_important', true);
        }
        
        // Filter by starred
        if ($request->filled('starred') && $request->starred == '1') {
            $query->where('is_starred', true);
        }
        
        // Filter by search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('subject', 'like', "%{$search}%")
                  ->orWhere('from_email', 'like', "%{$search}%")
                  ->orWhere('from_name', 'like', "%{$search}%")
                  ->orWhere('body_text', 'like', "%{$search}%");
            });
        }
        
        $messages = $query->orderBy('received_at', 'desc')->paginate(20);
        $accounts = EmailAccount::where('is_active', true)->get();
        
        $stats = [
            'inbox' => EmailMessage::where('type', 'inbox')->where('status', '!=', 'deleted')->count(),
            'unread' => EmailMessage::where('type', 'inbox')->where('status', 'unread')->count(),
            'sent' => EmailMessage::where('type', 'sent')->count(),
            'drafts' => EmailMessage::where('type', 'draft')->count(),
            'trash' => EmailMessage::where('status', 'deleted')->orWhere('type', 'trash')->count(),
            'important' => EmailMessage::where('is_important', true)->count(),
        ];
        
        return view('admin.emails.index', compact('messages', 'accounts', 'stats', 'folder'));
    }

    /**
     * Show email message
     */
    public function show(EmailMessage $email)
    {
        // Mark as read
        $email->markAsRead();
        
        $email->load(['account', 'attachments', 'assignedUser', 'repliedByUser']);
        
        return view('admin.emails.show', compact('email'));
    }

    /**
     * Show compose form
     */
    public function compose(Request $request)
    {
        $accounts = EmailAccount::where('is_active', true)->get();
        $replyTo = null;
        
        if ($request->filled('reply_to')) {
            $replyTo = EmailMessage::find($request->reply_to);
        }
        
        return view('admin.emails.compose', compact('accounts', 'replyTo'));
    }

    /**
     * Send email
     */
    public function send(Request $request)
    {
        $validated = $request->validate([
            'email_account_id' => 'required|exists:email_accounts,id',
            'to' => 'required|string',
            'subject' => 'required|string|max:255',
            'body' => 'required|string',
            'cc' => 'nullable|string',
            'bcc' => 'nullable|string',
            'save_draft' => 'nullable|boolean',
        ]);
        
        try {
            $account = EmailAccount::findOrFail($validated['email_account_id']);
            
            // Parse multiple recipients
            $toEmails = array_map('trim', explode(',', $validated['to']));
            $ccEmails = !empty($validated['cc']) ? array_map('trim', explode(',', $validated['cc'])) : [];
            $bccEmails = !empty($validated['bcc']) ? array_map('trim', explode(',', $validated['bcc'])) : [];
            
            // Validate all email addresses
            foreach (array_merge($toEmails, $ccEmails, $bccEmails) as $email) {
                if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    throw new \Exception("Invalid email address: {$email}");
                }
            }
            
            // If saving as draft
            if ($request->has('save_draft') && $request->save_draft) {
                EmailMessage::create([
                    'email_account_id' => $account->id,
                    'message_id' => uniqid('draft_'),
                    'subject' => $validated['subject'],
                    'from_email' => $account->email,
                    'from_name' => $account->name,
                    'to' => array_map(fn($e) => ['email' => $e], $toEmails),
                    'body_text' => strip_tags($validated['body']),
                    'body_html' => $validated['body'],
                    'status' => 'read',
                    'type' => 'draft',
                    'received_at' => now(),
                ]);
                
                return $this->successResponse('Draft saved successfully!', route('admin.emails.index', ['folder' => 'drafts']));
            }
            
            // Send email using Laravel Mail
            $config = method_exists($account, 'getSmtpConfig') ? $account->getSmtpConfig() : [];
            if (!empty($config)) {
                config([
                    'mail.mailers.smtp.host' => $config['host'] ?? config('mail.mailers.smtp.host'),
                    'mail.mailers.smtp.port' => $config['port'] ?? config('mail.mailers.smtp.port'),
                    'mail.mailers.smtp.encryption' => $config['encryption'] ?? config('mail.mailers.smtp.encryption'),
                    'mail.mailers.smtp.username' => $config['username'] ?? config('mail.mailers.smtp.username'),
                    'mail.mailers.smtp.password' => $config['password'] ?? config('mail.mailers.smtp.password'),
                ]);
            }
            
            \Mail::html($validated['body'], function ($message) use ($validated, $account, $toEmails, $ccEmails, $bccEmails) {
                $message->from($account->email, $account->name)
                    ->to($toEmails)
                    ->subject($validated['subject']);
                
                if (!empty($ccEmails)) {
                    $message->cc($ccEmails);
                }
                if (!empty($bccEmails)) {
                    $message->bcc($bccEmails);
                }
            });
            
            // Save sent message
            EmailMessage::create([
                'email_account_id' => $account->id,
                'message_id' => uniqid('sent_'),
                'subject' => $validated['subject'],
                'from_email' => $account->email,
                'from_name' => $account->name,
                'to' => array_map(fn($e) => ['email' => $e], $toEmails),
                'body_text' => strip_tags($validated['body']),
                'body_html' => $validated['body'],
                'status' => 'read',
                'type' => 'sent',
                'received_at' => now(),
            ]);
            
            return $this->successResponse('Email sent successfully!', route('admin.emails.index'));
        } catch (\Exception $e) {
            return $this->errorResponse('Failed to send email: ' . $e->getMessage());
        }
    }
    
    /**
     * Bulk actions
     */
    public function bulkAction(Request $request)
    {
        $validated = $request->validate([
            'action' => 'required|in:delete,archive,mark_read,mark_unread,mark_important,mark_spam',
            'message_ids' => 'required|array',
            'message_ids.*' => 'exists:email_messages,id',
        ]);
        
        $messages = EmailMessage::whereIn('id', $validated['message_ids'])->get();
        
        foreach ($messages as $message) {
            switch ($validated['action']) {
                case 'delete':
                    $message->update(['status' => 'deleted', 'type' => 'trash']);
                    break;
                case 'archive':
                    $message->update(['status' => 'archived']);
                    break;
                case 'mark_read':
                    $message->update(['status' => 'read']);
                    break;
                case 'mark_unread':
                    $message->update(['status' => 'unread']);
                    break;
                case 'mark_important':
                    $message->update(['is_important' => true]);
                    break;
                case 'mark_spam':
                    $message->update(['status' => 'spam']);
                    break;
            }
        }
        
        return $this->successResponse('Bulk action completed successfully!', route('admin.emails.index'));
    }

    /**
     * Reply to email
     */
    public function reply(Request $request, EmailMessage $email)
    {
        $validated = $request->validate([
            'body' => 'required|string',
            'subject' => 'nullable|string|max:255',
        ]);
        
        try {
            $success = $this->emailService->sendReply(
                $email,
                $validated['body'],
                $validated['subject'] ?? null
            );
            
            if ($success) {
                $this->notifySuccess('Reply sent successfully!', 'Reply Sent');
                return $this->successResponse('Reply sent successfully!', route('admin.emails.show', $email));
            } else {
                throw new \Exception('Failed to send reply');
            }
        } catch (\Exception $e) {
            $this->notifyError('Failed to send reply: ' . $e->getMessage(), 'Reply Failed');
            return $this->errorResponse('Failed to send reply: ' . $e->getMessage());
        }
    }

    /**
     * Update message status
     */
    public function updateStatus(Request $request, EmailMessage $email)
    {
        $validated = $request->validate([
            'status' => 'nullable|in:read,unread,archived,deleted,spam',
            'is_important' => 'nullable|boolean',
            'is_starred' => 'nullable|boolean',
        ]);
        
        $email->update($validated);
        
        if ($request->expectsJson()) {
            return response()->json(['success' => true, 'message' => 'Email updated successfully!']);
        }
        
        return back()->with('success', 'Email updated successfully!');
    }

    /**
     * Assign email to user
     */
    public function assign(Request $request, EmailMessage $email)
    {
        $validated = $request->validate([
            'assigned_to' => 'required|exists:users,id',
        ]);
        
        $email->update(['assigned_to' => $validated['assigned_to']]);
        
        $this->notifySuccess('Email assigned successfully!', 'Assigned');
        return back();
    }

    /**
     * Fetch emails from account
     */
    public function fetch(EmailAccount $account)
    {
        try {
            $fetched = $this->emailService->fetchEmails($account);
            
            $this->notifySuccess("Fetched {$fetched} new email(s)!", 'Emails Fetched');
            return $this->successResponse("Fetched {$fetched} new email(s)!", route('admin.emails.index'));
        } catch (\Exception $e) {
            $this->notifyError('Failed to fetch emails: ' . $e->getMessage(), 'Fetch Failed');
            return $this->errorResponse('Failed to fetch emails: ' . $e->getMessage());
        }
    }

    /**
     * Delete email
     */
    public function destroy(EmailMessage $email)
    {
        $email->update(['status' => 'deleted', 'type' => 'trash']);
        
        $this->notifySuccess('Email moved to trash!', 'Deleted');
        return back();
    }
}
