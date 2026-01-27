<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class CustomerMessage extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'customer_id',
        'assigned_staff_id',
        'booking_id',
        'subject',
        'message',
        'message_type',
        'priority',
        'status',
        'is_important',
        'is_read',
        'read_at',
        'channel',
        'external_id',
        'attachments',
        'parent_message_id',
        'thread_depth',
        'ip_address',
        'user_agent',
    ];

    protected $casts = [
        'attachments' => 'array',
        'is_important' => 'boolean',
        'is_read' => 'boolean',
        'read_at' => 'datetime',
        'thread_depth' => 'integer',
    ];

    /**
     * Get the customer who sent the message
     */
    public function customer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'customer_id');
    }

    /**
     * Get the assigned staff member
     */
    public function assignedStaff(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_staff_id');
    }

    /**
     * Get the booking related to message
     */
    public function booking(): BelongsTo
    {
        return $this->belongsTo(Booking::class);
    }

    /**
     * Get parent message (for threading)
     */
    public function parentMessage(): BelongsTo
    {
        return $this->belongsTo(CustomerMessage::class, 'parent_message_id');
    }

    /**
     * Get reply messages
     */
    public function replies(): HasMany
    {
        return $this->hasMany(CustomerMessage::class, 'parent_message_id')
            ->orderBy('created_at');
    }

    /**
     * Scope for unread messages
     */
    public function scopeUnread($query)
    {
        return $query->where('is_read', false);
    }

    /**
     * Scope for important messages
     */
    public function scopeImportant($query)
    {
        return $query->where('is_important', true);
    }

    /**
     * Scope for new messages
     */
    public function scopeNew($query)
    {
        return $query->where('status', 'new');
    }
}
