<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;
use App\Traits\GeneratesReferenceNumber;

class Payment extends Model
{
    use HasFactory, GeneratesReferenceNumber;

    protected $fillable = [
        'booking_id', 'invoice_id', 'gateway_id', 'payment_reference', 'payment_method',
        'amount', 'currency', 'status', 'gateway_transaction_id',
        'gateway_response', 'paid_at', 'notes',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'paid_at' => 'datetime',
        'gateway_response' => 'array',
    ];

    public function booking(): BelongsTo
    {
        return $this->belongsTo(Booking::class);
    }

    public function invoice(): BelongsTo
    {
        return $this->belongsTo(Invoice::class);
    }

    public function gateway(): BelongsTo
    {
        return $this->belongsTo(PaymentGateway::class);
    }

    /**
     * Boot the model.
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($payment) {
            if (empty($payment->payment_reference)) {
                $payment->payment_reference = static::generatePaymentReference();
            }
        });
    }

    /**
     * Generate a unique payment reference in format {PREFIX}{YYYYMMDD}-{HHMM}-{NNN}
     * Example: PAY20251112-0721-001
     */
    public static function generatePaymentReference(): string
    {
        return static::generateReferenceNumber('PAY', null, 'payment_reference');
    }

    /**
     * Get the user through the booking relationship
     * Payment -> Booking -> User
     * Note: For eager loading, use 'booking.user' instead of 'user'
     */
    public function user()
    {
        return $this->hasOneThrough(
            User::class,        // Final model (User)
            Booking::class,     // Intermediate model (Booking)
            'user_id',          // Foreign key on bookings table (bookings.user_id -> users.id)
            'id',               // Foreign key on users table (users.id)
            'booking_id',       // Local key on payments table (payments.booking_id -> bookings.id)
            'id'                // Local key on bookings table (bookings.id)
        );
    }
}
