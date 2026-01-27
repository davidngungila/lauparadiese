<?php

namespace App\Services;

use App\Models\PaymentGateway;
use App\Models\Payment;
use Illuminate\Support\Facades\Log;

abstract class PaymentGatewayService
{
    protected PaymentGateway $gateway;

    public function __construct(PaymentGateway $gateway)
    {
        $this->gateway = $gateway;
    }

    /**
     * Process a payment
     */
    abstract public function processPayment(array $data): array;

    /**
     * Verify a payment
     */
    abstract public function verifyPayment(string $transactionId): array;

    /**
     * Refund a payment
     */
    abstract public function refundPayment(string $transactionId, float $amount = null): array;

    /**
     * Get payment status
     */
    abstract public function getPaymentStatus(string $transactionId): string;

    /**
     * Handle webhook
     */
    abstract public function handleWebhook(array $payload): bool;

    /**
     * Get supported currencies
     */
    public function getSupportedCurrencies(): array
    {
        return $this->gateway->supported_currencies ?? [];
    }

    /**
     * Get supported payment methods
     */
    public function getSupportedPaymentMethods(): array
    {
        return $this->gateway->supported_payment_methods ?? [];
    }

    /**
     * Calculate total amount with fees
     */
    public function calculateTotalAmount(float $amount): float
    {
        $fee = $this->gateway->calculateFee($amount);
        return $amount + $fee;
    }

    /**
     * Log payment activity
     */
    protected function log(string $message, array $context = []): void
    {
        Log::channel('payment')->info($message, array_merge([
            'gateway' => $this->gateway->name,
        ], $context));
    }
}





