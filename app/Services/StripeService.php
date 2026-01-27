<?php

namespace App\Services;

use App\Models\PaymentGateway;
use Stripe\Stripe;
use Stripe\PaymentIntent;
use Stripe\PaymentLink;
use Stripe\Refund;
use Stripe\Exception\ApiErrorException;
use Illuminate\Support\Facades\Log;

class StripeService extends PaymentGatewayService
{
    public function __construct(PaymentGateway $gateway)
    {
        parent::__construct($gateway);
        
        $credentials = $gateway->credentials ?? [];
        $apiKey = '';
        
        if ($gateway->is_test_mode) {
            $apiKey = $credentials['test_secret_key'] 
                ?? $credentials['secret_key'] 
                ?? '';
        } else {
            $apiKey = $credentials['live_secret_key'] 
                ?? $credentials['secret_key'] 
                ?? '';
        }
            
        if ($apiKey) {
            Stripe::setApiKey($apiKey);
        }
    }

    public function processPayment(array $data): array
    {
        try {
            $amount = (int)($data['amount'] * 100); // Convert to cents
            $currency = strtolower($data['currency'] ?? 'usd');

            // Support multiple payment methods
            $paymentMethodTypes = $data['payment_method_types'] ?? ['card', 'apple_pay', 'google_pay', 'link'];
            
            $paymentIntent = PaymentIntent::create([
                'amount' => $amount,
                'currency' => $currency,
                'payment_method_types' => $paymentMethodTypes,
                'description' => $data['description'] ?? 'Tour Booking Payment',
                'metadata' => [
                    'booking_id' => $data['booking_id'] ?? '',
                    'invoice_id' => $data['invoice_id'] ?? '',
                    'user_id' => $data['user_id'] ?? '',
                ],
            ]);

            $this->log('Stripe payment intent created', [
                'payment_intent_id' => $paymentIntent->id,
                'amount' => $data['amount'],
            ]);

            return [
                'success' => true,
                'payment_intent_id' => $paymentIntent->id,
                'client_secret' => $paymentIntent->client_secret,
                'status' => $paymentIntent->status,
            ];
        } catch (ApiErrorException $e) {
            $this->log('Stripe payment error', ['error' => $e->getMessage()]);
            return [
                'success' => false,
                'error' => $e->getMessage(),
            ];
        }
    }

    public function verifyPayment(string $paymentIntentId): array
    {
        try {
            $paymentIntent = PaymentIntent::retrieve($paymentIntentId);

            return [
                'success' => true,
                'status' => $paymentIntent->status,
                'amount' => $paymentIntent->amount / 100,
                'currency' => strtoupper($paymentIntent->currency),
                'metadata' => $paymentIntent->metadata->toArray(),
            ];
        } catch (ApiErrorException $e) {
            return [
                'success' => false,
                'error' => $e->getMessage(),
            ];
        }
    }

    public function refundPayment(string $paymentIntentId, float $amount = null): array
    {
        try {
            $params = [
                'payment_intent' => $paymentIntentId,
            ];

            if ($amount !== null) {
                $params['amount'] = (int)($amount * 100);
            }

            $refund = Refund::create($params);

            $this->log('Stripe refund processed', [
                'refund_id' => $refund->id,
                'amount' => $refund->amount / 100,
            ]);

            return [
                'success' => true,
                'refund_id' => $refund->id,
                'amount' => $refund->amount / 100,
                'status' => $refund->status,
            ];
        } catch (ApiErrorException $e) {
            $this->log('Stripe refund error', ['error' => $e->getMessage()]);
            return [
                'success' => false,
                'error' => $e->getMessage(),
            ];
        }
    }

    public function getPaymentStatus(string $paymentIntentId): string
    {
        try {
            $paymentIntent = PaymentIntent::retrieve($paymentIntentId);
            return $paymentIntent->status;
        } catch (ApiErrorException $e) {
            return 'unknown';
        }
    }

    public function handleWebhook(array $payload): bool
    {
        try {
            $event = $payload['type'] ?? null;
            $data = $payload['data']['object'] ?? [];

            switch ($event) {
                case 'payment_intent.succeeded':
                    $this->log('Stripe webhook: Payment succeeded', [
                        'payment_intent_id' => $data['id'] ?? '',
                    ]);
                    return true;

                case 'payment_intent.payment_failed':
                    $this->log('Stripe webhook: Payment failed', [
                        'payment_intent_id' => $data['id'] ?? '',
                    ]);
                    return true;

                case 'checkout.session.completed':
                    $this->log('Stripe webhook: Checkout session completed', [
                        'session_id' => $data['id'] ?? '',
                    ]);
                    return true;

                default:
                    return false;
            }
        } catch (\Exception $e) {
            $this->log('Stripe webhook error', ['error' => $e->getMessage()]);
            return false;
        }
    }

    /**
     * Create a Payment Link for booking
     */
    public function createPaymentLink(array $data): array
    {
        try {
            $amount = (int)($data['amount'] * 100); // Convert to cents
            $currency = strtolower($data['currency'] ?? 'usd');
            
            // Check if a static payment link is configured
            $credentials = $this->gateway->credentials ?? [];
            $staticPaymentLink = $credentials['payment_link_url'] ?? 'https://buy.stripe.com/test_6oU7sL7Tq1ny7Kwc3z7IY00';
            
            // Use static payment link (default or configured)
            if ($staticPaymentLink) {
                $paymentLinkUrl = $staticPaymentLink;
                
                // Append metadata as query parameters for tracking
                $metadata = [
                    'booking_id' => $data['booking_id'] ?? '',
                ];
                
                // Append metadata as query parameters if needed
                $separator = strpos($paymentLinkUrl, '?') !== false ? '&' : '?';
                $paymentLinkUrl .= $separator . 'client_reference_id=' . urlencode($metadata['booking_id']);
                
                return [
                    'success' => true,
                    'payment_link_url' => $paymentLinkUrl,
                    'type' => 'static',
                ];
            }
            
            // Create dynamic Payment Link via API
            $paymentLink = PaymentLink::create([
                'line_items' => [[
                    'price_data' => [
                        'currency' => $currency,
                        'product_data' => [
                            'name' => $data['description'] ?? 'Tour Booking Payment',
                        ],
                        'unit_amount' => $amount,
                    ],
                    'quantity' => 1,
                ]],
                'metadata' => [
                    'booking_id' => $data['booking_id'] ?? '',
                    'customer_email' => $data['email'] ?? '',
                    'customer_name' => $data['name'] ?? '',
                ],
                'after_completion' => [
                    'type' => 'redirect',
                    'redirect' => [
                        'url' => $data['success_url'] ?? route('booking.confirmation', ['booking' => $data['booking_id'] ?? '']),
                    ],
                ],
            ]);

            $this->log('Stripe payment link created', [
                'payment_link_id' => $paymentLink->id,
                'amount' => $data['amount'],
            ]);

            return [
                'success' => true,
                'payment_link_url' => $paymentLink->url,
                'payment_link_id' => $paymentLink->id,
                'type' => 'dynamic',
            ];
        } catch (ApiErrorException $e) {
            $this->log('Stripe payment link error', ['error' => $e->getMessage()]);
            return [
                'success' => false,
                'error' => $e->getMessage(),
            ];
        }
    }
}





