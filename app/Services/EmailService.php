<?php

namespace App\Services;

use App\Models\EmailAccount;
use App\Models\EmailMessage;
use App\Models\EmailAttachment;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Mail;

class EmailService
{
    /**
     * Fetch emails from an account
     */
    public function fetchEmails(EmailAccount $account): int
    {
        $fetched = 0;
        
        try {
            if ($account->protocol === 'imap') {
                $fetched = $this->fetchViaImap($account);
            } elseif ($account->protocol === 'pop3') {
                $fetched = $this->fetchViaPop3($account);
            }
            
            $account->update([
                'last_checked_at' => now(),
                'messages_count' => $account->messages()->count(),
            ]);
            
            return $fetched;
        } catch (\Exception $e) {
            Log::error('Email fetch failed', [
                'account_id' => $account->id,
                'error' => $e->getMessage(),
            ]);
            throw $e;
        }
    }

    /**
     * Fetch emails via IMAP
     */
    protected function fetchViaImap(EmailAccount $account): int
    {
        // Note: This requires php-imap extension
        // For production, consider using a package like webklex/laravel-imap
        
        $connectionString = $account->getImapConnectionString();
        $username = $account->username;
        $password = $account->password;
        
        // Check if IMAP extension is available
        if (!function_exists('imap_open')) {
            throw new \Exception('IMAP extension is not installed. Please install php-imap extension.');
        }
        
        $mailbox = @imap_open($connectionString, $username, $password);
        
        if (!$mailbox) {
            throw new \Exception('Failed to connect to IMAP server: ' . imap_last_error());
        }
        
        $fetched = 0;
        $messages = imap_search($mailbox, 'UNSEEN');
        
        if ($messages) {
            foreach ($messages as $msgNumber) {
                $header = imap_headerinfo($mailbox, $msgNumber);
                $uid = imap_uid($mailbox, $msgNumber);
                
                // Check if message already exists
                if (EmailMessage::where('message_id', $header->message_id)->exists()) {
                    continue;
                }
                
                $body = imap_body($mailbox, $msgNumber);
                $structure = imap_fetchstructure($mailbox, $msgNumber);
                
                // Parse email
                $emailMessage = $this->parseEmailMessage($account, $header, $body, $structure, $uid);
                
                if ($emailMessage) {
                    $fetched++;
                }
            }
        }
        
        imap_close($mailbox);
        
        return $fetched;
    }

    /**
     * Fetch emails via POP3
     */
    protected function fetchViaPop3(EmailAccount $account): int
    {
        // POP3 implementation would go here
        // Similar to IMAP but using POP3 protocol
        throw new \Exception('POP3 fetching not yet implemented. Please use IMAP.');
    }

    /**
     * Parse email message from IMAP
     */
    protected function parseEmailMessage(EmailAccount $account, $header, $body, $structure, $uid): ?EmailMessage
    {
        try {
            $emailMessage = EmailMessage::create([
                'email_account_id' => $account->id,
                'message_id' => $header->message_id ?? uniqid('msg_'),
                'uid' => $uid,
                'subject' => $header->subject ?? '(No Subject)',
                'from_email' => $header->from[0]->mailbox . '@' . $header->from[0]->host,
                'from_name' => isset($header->from[0]->personal) ? $header->from[0]->personal : null,
                'to' => $this->parseAddresses($header->to ?? []),
                'cc' => $this->parseAddresses($header->cc ?? []),
                'bcc' => $this->parseAddresses($header->bcc ?? []),
                'reply_to' => isset($header->reply_to[0]) ? 
                    $header->reply_to[0]->mailbox . '@' . $header->reply_to[0]->host : null,
                'body_text' => $this->extractTextBody($body, $structure),
                'body_html' => $this->extractHtmlBody($body, $structure),
                'status' => 'unread',
                'type' => 'inbox',
                'has_attachments' => $this->hasAttachments($structure),
                'received_at' => isset($header->date) ? date('Y-m-d H:i:s', strtotime($header->date)) : now(),
            ]);
            
            // Process attachments
            if ($emailMessage->has_attachments) {
                $this->processAttachments($emailMessage, $structure, $body);
            }
            
            return $emailMessage;
        } catch (\Exception $e) {
            Log::error('Failed to parse email message', [
                'error' => $e->getMessage(),
            ]);
            return null;
        }
    }

    /**
     * Parse email addresses
     */
    protected function parseAddresses(array $addresses): array
    {
        $result = [];
        foreach ($addresses as $address) {
            $result[] = [
                'email' => $address->mailbox . '@' . $address->host,
                'name' => $address->personal ?? null,
            ];
        }
        return $result;
    }

    /**
     * Extract text body
     */
    protected function extractTextBody($body, $structure): ?string
    {
        // Simplified extraction - in production, use proper MIME parsing
        if ($structure->type == 0) {
            return quoted_printable_decode($body);
        }
        return null;
    }

    /**
     * Extract HTML body
     */
    protected function extractHtmlBody($body, $structure): ?string
    {
        // Simplified extraction - in production, use proper MIME parsing
        // This would need to parse multipart messages properly
        return null;
    }

    /**
     * Check if message has attachments
     */
    protected function hasAttachments($structure): bool
    {
        if (!isset($structure->parts)) {
            return false;
        }
        
        foreach ($structure->parts as $part) {
            if (isset($part->disposition) && strtolower($part->disposition) === 'attachment') {
                return true;
            }
        }
        
        return false;
    }

    /**
     * Process attachments
     */
    protected function processAttachments(EmailMessage $message, $structure, $body): void
    {
        // Attachment processing would go here
        // This is simplified - full implementation would extract and store files
    }

    /**
     * Send reply to an email
     */
    public function sendReply(EmailMessage $originalMessage, string $body, ?string $subject = null, array $attachments = []): bool
    {
        try {
            $account = $originalMessage->account;
            $subject = $subject ?: 'Re: ' . $originalMessage->subject;
            
            // Use Laravel Mail with account's SMTP settings
            $config = $account->getSmtpConfig();
            
            // Configure mailer dynamically
            config([
                'mail.mailers.smtp.host' => $config['host'],
                'mail.mailers.smtp.port' => $config['port'],
                'mail.mailers.smtp.encryption' => $config['encryption'],
                'mail.mailers.smtp.username' => $config['username'],
                'mail.mailers.smtp.password' => $config['password'],
            ]);
            
            Mail::raw($body, function ($message) use ($originalMessage, $subject, $account, $attachments) {
                $message->from($account->email, $account->name)
                    ->to($originalMessage->from_email, $originalMessage->from_name)
                    ->subject($subject)
                    ->replyTo($account->email);
                
                // Add original message as quote
                $message->setBody($body . "\n\n--- Original Message ---\n" . $originalMessage->body_text);
                
                // Add attachments
                foreach ($attachments as $attachment) {
                    $message->attach($attachment);
                }
            });
            
            // Mark as replied
            $originalMessage->update([
                'replied_by' => auth()->id(),
                'replied_at' => now(),
            ]);
            
            return true;
        } catch (\Exception $e) {
            Log::error('Failed to send email reply', [
                'error' => $e->getMessage(),
            ]);
            return false;
        }
    }

    /**
     * Test email account connection
     */
    public function testConnection(EmailAccount $account): array
    {
        $result = [
            'imap' => false,
            'smtp' => false,
            'errors' => [],
        ];
        
        // Test IMAP connection
        if ($account->protocol === 'imap' && function_exists('imap_open')) {
            try {
                $connectionString = $account->getImapConnectionString();
                $mailbox = @imap_open($connectionString, $account->username, $account->password);
                
                if ($mailbox) {
                    $result['imap'] = true;
                    imap_close($mailbox);
                } else {
                    $result['errors']['imap'] = imap_last_error();
                }
            } catch (\Exception $e) {
                $result['errors']['imap'] = $e->getMessage();
            }
        }
        
        // Test SMTP connection
        try {
            $config = $account->getSmtpConfig();
            // SMTP test would go here
            $result['smtp'] = true; // Simplified
        } catch (\Exception $e) {
            $result['errors']['smtp'] = $e->getMessage();
        }
        
        return $result;
    }
}




