<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Traits\GeneratesReferenceNumber;

class Invoice extends Model
{
    use HasFactory, GeneratesReferenceNumber;

    protected $fillable = [
        'invoice_number', 'booking_id', 'user_id', 'customer_name',
        'customer_email', 'customer_phone', 'customer_address',
        'subtotal', 'tax_amount', 'discount_amount', 'total_amount',
        'currency', 'invoice_date', 'due_date', 'status', 'notes', 'terms',
    ];

    protected $casts = [
        'invoice_date' => 'date',
        'due_date' => 'date',
        'subtotal' => 'decimal:2',
        'tax_amount' => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'total_amount' => 'decimal:2',
    ];

    public function booking(): BelongsTo
    {
        return $this->belongsTo(Booking::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }

    /**
     * Boot the model.
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($invoice) {
            if (empty($invoice->invoice_number)) {
                $invoice->invoice_number = static::generateInvoiceNumber();
            }
        });
    }

    /**
     * Generate a unique invoice number in format {PREFIX}{YYYYMMDD}-{HHMM}-{NNN}
     * Example: INV20251112-0721-001
     */
    public static function generateInvoiceNumber(): string
    {
        return static::generateReferenceNumber('INV', 'invoice_prefix', 'invoice_number');
    }
}
