<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentGateway extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'display_name',
        'description',
        'is_active',
        'is_test_mode',
        'is_primary',
        'priority',
        'credentials',
        'supported_currencies',
        'supported_payment_methods',
        'transaction_fee_percentage',
        'transaction_fee_fixed',
        'settings',
        'status',
        'webhook_url',
        'webhook_secret',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'is_test_mode' => 'boolean',
        'is_primary' => 'boolean',
        'priority' => 'integer',
        'credentials' => 'array',
        'supported_currencies' => 'array',
        'supported_payment_methods' => 'array',
        'transaction_fee_percentage' => 'decimal:2',
        'transaction_fee_fixed' => 'decimal:2',
        'settings' => 'array',
    ];

    /**
     * Get primary payment gateway
     */
    public static function getPrimary()
    {
        return static::where('is_primary', true)->first();
    }

    /**
     * Get status badge class
     */
    public function getStatusBadgeClass()
    {
        return match($this->status) {
            'active' => 'bg-label-success',
            'inactive' => 'bg-label-secondary',
            'pending' => 'bg-label-warning',
            default => 'bg-label-secondary',
        };
    }

    /**
     * Get active payment gateways
     */
    public static function active()
    {
        return static::where('is_active', true)->get();
    }

    /**
     * Get gateway by name
     */
    public static function byName(string $name)
    {
        return static::where('name', $name)->first();
    }

    /**
     * Check if gateway supports currency
     */
    public function supportsCurrency(string $currency): bool
    {
        return in_array(strtoupper($currency), $this->supported_currencies ?? []);
    }

    /**
     * Check if gateway supports payment method
     */
    public function supportsPaymentMethod(string $method): bool
    {
        return in_array($method, $this->supported_payment_methods ?? []);
    }

    /**
     * Calculate transaction fee
     */
    public function calculateFee(float $amount): float
    {
        $percentageFee = ($amount * $this->transaction_fee_percentage) / 100;
        return $percentageFee + $this->transaction_fee_fixed;
    }
}
