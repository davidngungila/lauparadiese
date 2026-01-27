<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MpesaTransaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'transaction_type',
        'transaction_id',
        'merchant_request_id',
        'checkout_request_id',
        'conversation_id',
        'originator_conversation_id',
        'amount',
        'phone_number',
        'account_reference',
        'business_short_code',
        'mpesa_receipt_number',
        'transaction_date',
        'balance',
        'result_code',
        'result_description',
        'status',
        'metadata',
        'callback_data',
        'processed_at',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'balance' => 'decimal:2',
        'transaction_date' => 'datetime',
        'processed_at' => 'datetime',
        'metadata' => 'array',
        'callback_data' => 'array',
    ];

    /**
     * Scope for completed transactions
     */
    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    /**
     * Scope for failed transactions
     */
    public function scopeFailed($query)
    {
        return $query->where('status', 'failed');
    }

    /**
     * Scope for pending transactions
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Scope by transaction type
     */
    public function scopeByType($query, $type)
    {
        return $query->where('transaction_type', $type);
    }
}






