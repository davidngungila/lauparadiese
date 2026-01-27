<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class CustomerFeedback extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'customer_feedback';

    protected $fillable = [
        'customer_id',
        'booking_id',
        'tour_id',
        'feedback_type',
        'rating',
        'title',
        'message',
        'tour_name',
        'driver_name',
        'guide_name',
        'hotel_name',
        'attachments',
        'staff_response',
        'responded_by',
        'responded_at',
        'status',
        'is_public',
        'is_featured',
        'is_serious_complaint',
        'admin_notes',
    ];

    protected $casts = [
        'rating' => 'integer',
        'attachments' => 'array',
        'responded_at' => 'datetime',
        'is_public' => 'boolean',
        'is_featured' => 'boolean',
        'is_serious_complaint' => 'boolean',
    ];

    /**
     * Get the customer who gave feedback
     */
    public function customer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'customer_id');
    }

    /**
     * Get the booking related to feedback
     */
    public function booking(): BelongsTo
    {
        return $this->belongsTo(Booking::class);
    }

    /**
     * Get the tour related to feedback
     */
    public function tour(): BelongsTo
    {
        return $this->belongsTo(Tour::class);
    }

    /**
     * Get the staff member who responded
     */
    public function responder(): BelongsTo
    {
        return $this->belongsTo(User::class, 'responded_by');
    }

    /**
     * Scope for approved feedback
     */
    public function scopeApproved($query)
    {
        return $query->where('status', 'approved')->where('is_public', true);
    }

    /**
     * Scope for pending feedback
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }
}
