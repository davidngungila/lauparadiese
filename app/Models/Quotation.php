<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Traits\GeneratesReferenceNumber;

class Quotation extends Model
{
    use HasFactory, GeneratesReferenceNumber;

    protected $fillable = [
        'booking_id',
        'quotation_number',
        'customer_name',
        'customer_email',
        'customer_phone',
        'customer_address',
        'customer_country',
        'customer_city',
        'tour_id',
        'tour_name',
        'travelers',
        'adults',
        'children',
        'departure_date',
        'end_date',
        'duration_days',
        'accommodation_type',
        'airport_pickup',
        'special_requests',
        'tour_price',
        'addons_total',
        'accommodation_cost',
        'transport_cost',
        'park_fees',
        'guide_fees',
        'meals_cost',
        'activities_cost',
        'service_charges',
        'discount',
        'discount_percentage',
        'tax',
        'total_price',
        'currency',
        'included',
        'excluded',
        'terms_conditions',
        'notes',
        'admin_notes',
        'valid_until',
        'status',
        'created_by',
        'agent_id',
        'sent_at',
        'approved_at',
        'rejected_at',
        'itinerary_file',
        'attachment_files',
    ];

    protected $casts = [
        'departure_date' => 'date',
        'end_date' => 'date',
        'valid_until' => 'date',
        'sent_at' => 'datetime',
        'approved_at' => 'datetime',
        'rejected_at' => 'datetime',
        'airport_pickup' => 'boolean',
        'tour_price' => 'decimal:2',
        'addons_total' => 'decimal:2',
        'accommodation_cost' => 'decimal:2',
        'transport_cost' => 'decimal:2',
        'park_fees' => 'decimal:2',
        'guide_fees' => 'decimal:2',
        'meals_cost' => 'decimal:2',
        'activities_cost' => 'decimal:2',
        'service_charges' => 'decimal:2',
        'discount' => 'decimal:2',
        'discount_percentage' => 'decimal:2',
        'tax' => 'decimal:2',
        'total_price' => 'decimal:2',
        'attachment_files' => 'array',
    ];

    /**
     * Get the booking associated with the quotation.
     */
    public function booking(): BelongsTo
    {
        return $this->belongsTo(Booking::class);
    }

    /**
     * Get the tour associated with the quotation.
     */
    public function tour(): BelongsTo
    {
        return $this->belongsTo(Tour::class);
    }

    /**
     * Get the user who created the quotation.
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get the agent assigned to the quotation.
     */
    public function agent(): BelongsTo
    {
        return $this->belongsTo(User::class, 'agent_id');
    }

    /**
     * Get all notes for this quotation.
     */
    public function notes(): HasMany
    {
        return $this->hasMany(QuotationNote::class);
    }

    /**
     * Boot the model.
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($quotation) {
            if (empty($quotation->quotation_number)) {
                $quotation->quotation_number = static::generateQuotationNumber();
            }
        });
    }

    /**
     * Generate a unique quotation number in format {PREFIX}{YYYYMMDD}-{HHMM}-{NNN}
     * Example: QTN20251112-0721-001
     */
    public static function generateQuotationNumber(): string
    {
        return static::generateReferenceNumber('QT', 'quotation_prefix', 'quotation_number');
    }

    /**
     * Check if quotation is expired
     */
    public function isExpired(): bool
    {
        if (!$this->valid_until) {
            return false;
        }
        return $this->valid_until->isPast();
    }

    /**
     * Get subtotal (before tax and discount)
     */
    public function getSubtotalAttribute(): float
    {
        return $this->tour_price + $this->addons_total;
    }

    /**
     * Get final total after discount and tax
     */
    public function getFinalTotalAttribute(): float
    {
        $subtotal = $this->subtotal - $this->discount;
        return $subtotal + $this->tax;
    }

    /**
     * Get total cost breakdown
     */
    public function getTotalCostBreakdownAttribute(): float
    {
        return $this->accommodation_cost + 
               $this->transport_cost + 
               $this->park_fees + 
               $this->guide_fees + 
               $this->meals_cost + 
               $this->activities_cost + 
               $this->service_charges;
    }

    /**
     * Scope for pending quotations
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Scope for sent quotations
     */
    public function scopeSent($query)
    {
        return $query->where('status', 'sent');
    }

    /**
     * Scope for accepted quotations
     */
    public function scopeAccepted($query)
    {
        return $query->where('status', 'approved');
    }

    /**
     * Scope for under review quotations
     */
    public function scopeUnderReview($query)
    {
        return $query->where('status', 'under_review');
    }
}
