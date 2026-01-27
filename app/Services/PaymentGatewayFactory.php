<?php

namespace App\Services;

use App\Models\PaymentGateway;

class PaymentGatewayFactory
{
    /**
     * Create a payment gateway service instance
     */
    public static function create(PaymentGateway $gateway): PaymentGatewayService
    {
        return match($gateway->name) {
            'stripe' => new StripeService($gateway),
            'paypal' => new PayPalService($gateway),
            'payoneer' => new PayoneerService($gateway),
            'pesapal' => new PesapalService($gateway),
            default => throw new \InvalidArgumentException("Unsupported payment gateway: {$gateway->name}"),
        };
    }

    /**
     * Get active gateway by name
     */
    public static function getActiveGateway(string $name): ?PaymentGatewayService
    {
        $gateway = PaymentGateway::where('name', $name)
            ->where('is_active', true)
            ->first();

        if (!$gateway) {
            return null;
        }

        return self::create($gateway);
    }
}





