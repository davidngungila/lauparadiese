<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;

/**
 * A hypothetical service class to simulate payment gateway interactions.
 * In a real application, this would contain the logic for charging a credit card
 * using a service like Stripe, Braintree, or PayPal.
 */
class PaymentGateway
{
    /**
     * Simulate charging a customer's credit card.
     *
     * @param float $amount The total amount to charge.
     * @param string $paymentToken A secure token representing the credit card (e.g., from Stripe.js).
     * @return object
     */
    public function charge(float $amount, string $paymentToken): object
    {
        Log::info("Attempting to charge {$amount} using token: {$paymentToken}");

        // --- REAL-WORLD LOGIC WOULD GO HERE ---
        // 1. Initialize the payment provider's SDK (e.g., \Stripe\Stripe::setApiKey(config('services.stripe.secret'));)
        // 2. Create a charge or payment intent using the amount and token.
        // 3. Handle potential exceptions (e.g., card declined, invalid CVC).
        // 4. If the charge is successful, return a result object.

        // For now, we'll just simulate a successful transaction.
        $wasSuccessful = true;
        $transactionId = 'txn_' . uniqid();

        if ($wasSuccessful) {
            Log::info("Successfully charged {$amount}. Transaction ID: {$transactionId}");
        } else {
            Log::error("Failed to charge {$amount}.");
        }

        // Return a standard object that the controller can easily interpret.
        return (object) [
            'successful' => $wasSuccessful,
            'transaction_id' => $wasSuccessful ? $transactionId : null,
            'error_message' => $wasSuccessful ? null : 'The credit card was declined.',
        ];
    }
}
