<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Booking extends Model
{
    use HasFactory;

    protected $fillable = [
        'tour_id',
        'user_id',
        'assigned_staff_id',
        'booking_reference',
        'customer_name',
        'customer_email',
        'customer_phone',
        'customer_country',
        'city',
        'passport_number',
        'travelers',
        'number_of_adults',
        'number_of_children',
        'departure_date',
        'travel_end_date',
        'accommodation_level',
        'pickup_location',
        'dropoff_location',
        'total_price',
        'deposit_amount',
        'balance_amount',
        'discount_amount',
        'discount_percentage',
        'currency',
        'status',
        'approval_status',
        'approved_by',
        'approved_at',
        'rejected_by',
        'rejected_at',
        'rejection_reason',
        'addons',
        'payment_gateway_id',
        'payment_method',
        'payment_status',
        'amount_paid',
        'payment_receipt_path',
        'booking_source',
        'notes',
        'special_requirements',
        'emergency_contact_name',
        'emergency_contact_phone',
        'attachments',
        'confirmed_at',
        'cancelled_at',
        'cancellation_reason',
        'refund_amount',
        'refund_status',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'departure_date' => 'date',
        'travel_end_date' => 'date',
        'addons' => 'array',
        'attachments' => 'array',
        'total_price' => 'decimal:2',
        'deposit_amount' => 'decimal:2',
        'balance_amount' => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'discount_percentage' => 'decimal:2',
        'amount_paid' => 'decimal:2',
        'refund_amount' => 'decimal:2',
        'confirmed_at' => 'datetime',
        'cancelled_at' => 'datetime',
        'approved_at' => 'datetime',
        'rejected_at' => 'datetime',
    ];

    /**
     * Get the tour associated with the booking.
     */
    public function tour(): BelongsTo
    {
        return $this->belongsTo(Tour::class);
    }

    /**
     * Get the user associated with the booking (if logged in).
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the assigned staff/agent for the booking.
     */
    public function assignedStaff(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_staff_id');
    }

    /**
     * Get the user who approved the booking.
     */
    public function approver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    /**
     * Get the user who rejected the booking.
     */
    public function rejector(): BelongsTo
    {
        return $this->belongsTo(User::class, 'rejected_by');
    }

    /**
     * Get the tour operations associated with the booking.
     */
    public function tourOperations(): HasMany
    {
        return $this->hasMany(TourOperation::class);
    }

    /**
     * Get the payments associated with the booking.
     */
    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }

    /**
     * Get the invoice associated with the booking.
     */
    public function invoice(): HasOne
    {
        return $this->hasOne(Invoice::class);
    }

    /**
     * Boot the model.
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($booking) {
            if (empty($booking->booking_reference)) {
                $booking->booking_reference = static::generateBookingReference();
            }
        });
    }

    /**
     * Generate a unique booking reference in format BK{YYYYMMDD}-{HHMM}-{NNN}
     * Example: BK20251112-0721-001
     * Where:
     * - YYYYMMDD: Date (20251112)
     * - HHMM: Time in 24-hour format (0721)
     * - NNN: Unique sequential number for the day (001, 002, 003, ...)
     */
    public static function generateBookingReference(): string
    {
        $now = now();
        $date = $now->format('Ymd'); // YYYYMMDD
        $time = $now->format('Hi');   // HHMM (24-hour format)
        
        // Get the prefix from organization settings, default to 'BK'
        $prefix = 'BK';
        try {
            $orgSettings = OrganizationSetting::getSettings();
            if ($orgSettings && $orgSettings->booking_prefix) {
                $prefix = $orgSettings->booking_prefix;
            }
        } catch (\Exception $e) {
            // Use default prefix if settings not available
        }
        
        // Count bookings created today to get the next sequential number for the day
        $todayStart = $now->copy()->startOfDay();
        $todayEnd = $now->copy()->endOfDay();
        
        // Get all bookings created today with the same date prefix
        // Format: BK{YYYYMMDD}-{HHMM}-{NNN}
        $bookingsToday = static::whereBetween('created_at', [$todayStart, $todayEnd])
            ->where('booking_reference', 'like', $prefix . $date . '-%')
            ->orderBy('id', 'desc')
            ->get();
        
        // Extract the highest counter number from today's bookings
        $maxCounter = 0;
        $pattern = '/^' . preg_quote($prefix, '/') . $date . '-\d{4}-(\d+)$/';
        foreach ($bookingsToday as $booking) {
            // Extract counter from format: BK{YYYYMMDD}-{HHMM}-{NNN}
            if (preg_match($pattern, $booking->booking_reference, $matches)) {
                $counter = (int) $matches[1];
                if ($counter > $maxCounter) {
                    $maxCounter = $counter;
                }
            }
        }
        
        // Start counter from 1, increment if bookings exist
        $counter = $maxCounter + 1;
        
        // Generate reference and ensure uniqueness
        // If multiple bookings are created in the same minute, increment counter
        $attempts = 0;
        $maxAttempts = 1000; // Prevent infinite loop
        do {
            $reference = $prefix . $date . '-' . $time . '-' . str_pad($counter, 3, '0', STR_PAD_LEFT);
            $exists = static::where('booking_reference', $reference)->exists();
            if ($exists) {
                $counter++;
                $attempts++;
                // If we've tried many times, update time and reset attempts
                if ($attempts > 100 && $attempts % 100 == 0) {
                    $now = now();
                    $time = $now->format('Hi');
                }
                // Safety check to prevent infinite loop
                if ($attempts > $maxAttempts) {
                    // Fallback: use microtime to ensure uniqueness
                    $time = $now->format('Hi') . substr((string)microtime(true), -3, 2);
                    $counter = 1;
                    break;
                }
            }
        } while ($exists);

        return $reference;
    }

    /**
     * Get formatted booking reference number
     */
    public function getBookingReferenceAttribute($value): string
    {
        return $value ?: 'BK' . str_pad($this->id, 6, '0', STR_PAD_LEFT);
    }

    /**
     * Scope: Get confirmed bookings
     */
    public function scopeConfirmed($query)
    {
        return $query->where('status', 'confirmed');
    }

    /**
     * Scope: Get pending bookings
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending_payment');
    }

    /**
     * Scope: Get bookings pending approval
     */
    public function scopePendingApproval($query)
    {
        return $query->where('approval_status', 'pending');
    }

    /**
     * Scope: Get approved bookings
     */
    public function scopeApproved($query)
    {
        return $query->where('approval_status', 'approved');
    }

    /**
     * Scope: Get bookings for a specific tour and date
     */
    public function scopeForTourAndDate($query, $tourId, $date)
    {
        return $query->where('tour_id', $tourId)
                    ->where('departure_date', $date)
                    ->whereIn('status', ['confirmed', 'pending_payment']);
    }

    /**
     * Check if booking can be cancelled
     */
    public function canBeCancelled(): bool
    {
        return in_array($this->status, ['pending_payment', 'confirmed']) && 
               !$this->cancelled_at;
    }

    /**
     * Check if booking is confirmed
     */
    public function isConfirmed(): bool
    {
        return $this->status === 'confirmed' && $this->confirmed_at !== null;
    }

    /**
     * Calculate total travelers for a tour on a specific date
     */
    public static function getTotalTravelersForDate($tourId, $date): int
    {
        return static::forTourAndDate($tourId, $date)
                    ->sum('travelers');
    }
}
