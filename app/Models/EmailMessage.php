<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Carbon\Carbon;

class EmailMessage extends Model
{
    protected $fillable = [
        'email_account_id',
        'message_id',
        'uid',
        'subject',
        'from_email',
        'from_name',
        'to',
        'cc',
        'bcc',
        'reply_to',
        'body_text',
        'body_html',
        'status',
        'type',
        'is_important',
        'is_starred',
        'has_attachments',
        'received_at',
        'read_at',
        'assigned_to',
        'replied_by',
        'replied_at',
        'tags',
    ];

    protected $casts = [
        'to' => 'array',
        'cc' => 'array',
        'bcc' => 'array',
        'tags' => 'array',
        'is_important' => 'boolean',
        'is_starred' => 'boolean',
        'has_attachments' => 'boolean',
        'received_at' => 'datetime',
        'read_at' => 'datetime',
        'replied_at' => 'datetime',
    ];

    /**
     * Get the email account
     */
    public function account(): BelongsTo
    {
        return $this->belongsTo(EmailAccount::class, 'email_account_id');
    }

    /**
     * Get attachments
     */
    public function attachments(): HasMany
    {
        return $this->hasMany(EmailAttachment::class);
    }

    /**
     * Get assigned user
     */
    public function assignedUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    /**
     * Get user who replied
     */
    public function repliedByUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'replied_by');
    }

    /**
     * Mark as read
     */
    public function markAsRead(): void
    {
        if ($this->status === 'unread') {
            $this->update([
                'status' => 'read',
                'read_at' => now(),
            ]);
        }
    }

    /**
     * Mark as unread
     */
    public function markAsUnread(): void
    {
        $this->update([
            'status' => 'unread',
            'read_at' => null,
        ]);
    }

    /**
     * Get formatted from name
     */
    public function getFromNameAttribute($value): string
    {
        return $value ?: $this->from_email;
    }

    /**
     * Get body (prefer HTML, fallback to text)
     */
    public function getBody(): string
    {
        return $this->body_html ?: $this->body_text ?: '';
    }

    /**
     * Check if message is unread
     */
    public function isUnread(): bool
    {
        return $this->status === 'unread';
    }

    /**
     * Check if message has been replied to
     */
    public function isReplied(): bool
    {
        return !is_null($this->replied_at);
    }
}
