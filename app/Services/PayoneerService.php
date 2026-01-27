<?php

namespace App\Services;

use App\Models\PaymentGateway;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class PayoneerService extends PaymentGatewayService
{
    protected string $apiUrl;
    protected string $apiKey;

    public function __construct(PaymentGateway $gateway)
    {
        parent::__construct($gateway);
        
        $credentials = $gateway->credentials ?? [];
        $this->apiUrl = $gateway->is_test_mode 
            ? ($credentials['test_api_url'] ?? 'https://api.sandbox.payoneer.com')
            : ($credentials['live_api_url'] ?? 'https://api.payoneer.com');
        $this->apiKey = $gateway->is_test_mode 
            ? ($credentials['test_api_key'] ?? '')
            : ($credentials['live_api_key'] ?? '');
    }

    public function processPayment(array $data): array
    {
        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->apiKey,
                'Content-Type' => 'application/json',
            ])->post($this->apiUrl . '/v4/payments', [
                'amount' => $data['amount'],
                'currency' => strtoupper($data['currency'] ?? 'USD'),
                'description' => $data['description'] ?? 'Tour Booking Payment',
                'return_url' => $data['return_url'] ?? url('/payoneer/success'),
                'cancel_url' => $data['cancel_url'] ?? url('/payoneer/cancel'),
                'metadata' => [
                    'booking_id' => $data['booking_id'] ?? '',
                    'invoice_id' => $data['invoice_id'] ?? '',
                ],
            ]);

            if ($response->successful()) {
                $result = $response->json();
                
                $this->log('Payoneer payment created', [
                    'payment_id' => $result['payment_id'] ?? '',
                    'amount' => $data['amount'],
                ]);

                return [
                    'success' => true,
                    'payment_id' => $result['payment_id'] ?? '',
                    'redirect_url' => $result['redirect_url'] ?? '',
                    'status' => $result['status'] ?? 'pending',
                ];
            }

            return [
                'success' => false,
                'error' => $response->json()['message'] ?? 'Payment processing failed',
            ];
        } catch (\Exception $e) {
            $this->log('Payoneer payment error', ['error' => $e->getMessage()]);
            return [
                'success' => false,
                'error' => $e->getMessage(),
            ];
        }
    }

    public function verifyPayment(string $paymentId): array
    {
        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->apiKey,
            ])->get($this->apiUrl . '/v4/payments/' . $paymentId);

            if ($response->successful()) {
                $result = $response->json();
                return [
                    'success' => true,
                    'status' => $result['status'] ?? 'unknown',
                    'amount' => $result['amount'] ?? 0,
                    'currency' => $result['currency'] ?? 'USD',
                ];
            }

            return [
                'success' => false,
                'error' => $response->json()['message'] ?? 'Verification failed',
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'error' => $e->getMessage(),
            ];
        }
    }

    public function refundPayment(string $paymentId, float $amount = null): array
    {
        try {
            $payload = [];
            if ($amount !== null) {
                $payload['amount'] = $amount;
            }

            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->apiKey,
                'Content-Type' => 'application/json',
            ])->post($this->apiUrl . '/v4/payments/' . $paymentId . '/refund', $payload);

            if ($response->successful()) {
                $result = $response->json();
                
                $this->log('Payoneer refund processed', [
                    'refund_id' => $result['refund_id'] ?? '',
                    'amount' => $result['amount'] ?? 0,
                ]);

                return [
                    'success' => true,
                    'refund_id' => $result['refund_id'] ?? '',
                    'amount' => $result['amount'] ?? 0,
                    'status' => $result['status'] ?? 'pending',
                ];
            }

            return [
                'success' => false,
                'error' => $response->json()['message'] ?? 'Refund failed',
            ];
        } catch (\Exception $e) {
            $this->log('Payoneer refund error', ['error' => $e->getMessage()]);
            return [
                'success' => false,
                'error' => $e->getMessage(),
            ];
        }
    }

    public function getPaymentStatus(string $paymentId): string
    {
        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->apiKey,
            ])->get($this->apiUrl . '/v4/payments/' . $paymentId);

            if ($response->successful()) {
                $result = $response->json();
                return $result['status'] ?? 'unknown';
            }

            return 'unknown';
        } catch (\Exception $e) {
            return 'unknown';
        }
    }

    public function handleWebhook(array $payload): bool
    {
        try {
            $eventType = $payload['event_type'] ?? null;

            switch ($eventType) {
                case 'payment.completed':
                    $this->log('Payoneer webhook: Payment completed', $payload);
                    return true;

                case 'payment.failed':
                    $this->log('Payoneer webhook: Payment failed', $payload);
                    return true;

                default:
                    return false;
            }
        } catch (\Exception $e) {
            $this->log('Payoneer webhook error', ['error' => $e->getMessage()]);
            return false;
        }
    }
}





