<?php

namespace App\Services;

use App\Models\PaymentGateway;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class PesapalService extends PaymentGatewayService
{
    private $baseUrl;
    private $consumerKey;
    private $consumerSecret;
    private $accessToken;
    private $isTestMode;

    public function __construct(PaymentGateway $gateway)
    {
        parent::__construct($gateway);
        
        $credentials = $gateway->credentials ?? [];
        $this->isTestMode = $gateway->is_test_mode ?? true;
        
        // Set base URL based on environment
        $this->baseUrl = $this->isTestMode 
            ? 'https://cybqa.pesapal.com/pesapalv3'
            : 'https://pay.pesapal.com/v3';
        
        $this->consumerKey = $this->isTestMode
            ? ($credentials['test_consumer_key'] ?? '')
            : ($credentials['live_consumer_key'] ?? '');
            
        $this->consumerSecret = $this->isTestMode
            ? ($credentials['test_consumer_secret'] ?? '')
            : ($credentials['live_consumer_secret'] ?? '');
        
        // Authenticate and get access token
        $this->authenticate();
    }

    /**
     * Authenticate with Pesapal API and get access token
     */
    private function authenticate(): void
    {
        try {
            $response = Http::asForm()->post($this->baseUrl . '/api/Auth/RequestToken', [
                'consumer_key' => $this->consumerKey,
                'consumer_secret' => $this->consumerSecret,
            ]);

            if ($response->successful()) {
                $data = $response->json();
                $this->accessToken = $data['token'] ?? null;
                
                if (!$this->accessToken) {
                    Log::error('Pesapal authentication failed: No token received', [
                        'response' => $data
                    ]);
                }
            } else {
                Log::error('Pesapal authentication failed', [
                    'status' => $response->status(),
                    'response' => $response->body()
                ]);
            }
        } catch (\Exception $e) {
            Log::error('Pesapal authentication exception', [
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * Register IPN (Instant Payment Notification) URL
     */
    public function registerIPN(string $ipnUrl): ?string
    {
        try {
            $response = Http::withHeaders([
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
                'Authorization' => 'Bearer ' . $this->accessToken,
            ])->post($this->baseUrl . '/api/URLSetup/RegisterIPN', [
                'url' => $ipnUrl,
                'ipn_notification_type' => 'GET',
            ]);

            if ($response->successful()) {
                $data = $response->json();
                return $data['ipn_id'] ?? null;
            }

            Log::error('Pesapal IPN registration failed', [
                'status' => $response->status(),
                'response' => $response->body()
            ]);

            return null;
        } catch (\Exception $e) {
            Log::error('Pesapal IPN registration exception', [
                'error' => $e->getMessage()
            ]);
            return null;
        }
    }

    /**
     * Submit order request to Pesapal
     */
    public function submitOrderRequest(array $orderData): array
    {
        try {
            if (!$this->accessToken) {
                $this->authenticate();
            }

            $response = Http::withHeaders([
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
                'Authorization' => 'Bearer ' . $this->accessToken,
            ])->post($this->baseUrl . '/api/Transactions/SubmitOrderRequest', $orderData);

            if ($response->successful()) {
                $data = $response->json();
                
                $this->log('Pesapal order submitted successfully', [
                    'order_tracking_id' => $data['order_tracking_id'] ?? null,
                ]);

                return [
                    'success' => true,
                    'order_tracking_id' => $data['order_tracking_id'] ?? null,
                    'redirect_url' => $data['redirect_url'] ?? null,
                    'data' => $data,
                ];
            }

            $errorData = $response->json();
            $errorMessage = $errorData['error']['message'] ?? 'Unknown error occurred';

            $this->log('Pesapal order submission failed', [
                'status' => $response->status(),
                'error' => $errorMessage,
            ]);

            return [
                'success' => false,
                'error' => $errorMessage,
                'error_code' => $errorData['error']['code'] ?? null,
            ];
        } catch (\Exception $e) {
            $this->log('Pesapal order submission exception', [
                'error' => $e->getMessage()
            ]);

            return [
                'success' => false,
                'error' => $e->getMessage(),
            ];
        }
    }

    /**
     * Get transaction status
     */
    public function getTransactionStatus(string $orderTrackingId): array
    {
        try {
            if (!$this->accessToken) {
                $this->authenticate();
            }

            $response = Http::withHeaders([
                'Accept' => 'application/json',
                'Authorization' => 'Bearer ' . $this->accessToken,
            ])->get($this->baseUrl . '/api/Transactions/GetTransactionStatus', [
                'orderTrackingId' => $orderTrackingId,
            ]);

            if ($response->successful()) {
                $data = $response->json();
                
                return [
                    'success' => true,
                    'data' => $data,
                    'payment_status' => $data['payment_status_description'] ?? null,
                    'status_code' => $data['status_code'] ?? null,
                    'confirmation_code' => $data['confirmation_code'] ?? null,
                ];
            }

            return [
                'success' => false,
                'error' => 'Failed to get transaction status',
            ];
        } catch (\Exception $e) {
            $this->log('Pesapal get transaction status exception', [
                'error' => $e->getMessage()
            ]);

            return [
                'success' => false,
                'error' => $e->getMessage(),
            ];
        }
    }

    /**
     * Request refund
     */
    public function requestRefund(string $confirmationCode, float $amount, string $username, string $remarks): array
    {
        try {
            if (!$this->accessToken) {
                $this->authenticate();
            }

            $response = Http::withHeaders([
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
                'Authorization' => 'Bearer ' . $this->accessToken,
            ])->post($this->baseUrl . '/api/Transactions/RefundRequest', [
                'confirmation_code' => $confirmationCode,
                'amount' => number_format($amount, 2, '.', ''),
                'username' => $username,
                'remarks' => $remarks,
            ]);

            if ($response->successful()) {
                $data = $response->json();
                
                $this->log('Pesapal refund requested', [
                    'confirmation_code' => $confirmationCode,
                    'amount' => $amount,
                    'status' => $data['status'] ?? null,
                ]);

                return [
                    'success' => $data['status'] == '200',
                    'message' => $data['message'] ?? null,
                    'status' => $data['status'] ?? null,
                ];
            }

            return [
                'success' => false,
                'error' => 'Refund request failed',
            ];
        } catch (\Exception $e) {
            $this->log('Pesapal refund request exception', [
                'error' => $e->getMessage()
            ]);

            return [
                'success' => false,
                'error' => $e->getMessage(),
            ];
        }
    }

    /**
     * Cancel order
     */
    public function cancelOrder(string $orderTrackingId): array
    {
        try {
            if (!$this->accessToken) {
                $this->authenticate();
            }

            $response = Http::withHeaders([
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
                'Authorization' => 'Bearer ' . $this->accessToken,
            ])->post($this->baseUrl . '/api/Transactions/CancelOrder', [
                'order_tracking_id' => $orderTrackingId,
            ]);

            if ($response->successful()) {
                $data = $response->json();
                
                $this->log('Pesapal order cancelled', [
                    'order_tracking_id' => $orderTrackingId,
                    'status' => $data['status'] ?? null,
                ]);

                return [
                    'success' => $data['status'] == '200',
                    'message' => $data['message'] ?? null,
                    'status' => $data['status'] ?? null,
                ];
            }

            return [
                'success' => false,
                'error' => 'Order cancellation failed',
            ];
        } catch (\Exception $e) {
            $this->log('Pesapal cancel order exception', [
                'error' => $e->getMessage()
            ]);

            return [
                'success' => false,
                'error' => $e->getMessage(),
            ];
        }
    }

    /**
     * Process payment - creates order and returns redirect URL
     */
    public function processPayment(array $data): array
    {
        // Register IPN URL first
        $ipnUrl = route('pesapal.ipn');
        $ipnId = $this->registerIPN($ipnUrl);

        if (!$ipnId) {
            // Try to use existing IPN or continue without it
            Log::warning('Pesapal IPN registration failed, continuing without IPN');
        }

        // Prepare order data
        $orderData = [
            'id' => $data['order_id'] ?? uniqid('ORD-'),
            'currency' => strtoupper($data['currency'] ?? 'KES'),
            'amount' => number_format($data['amount'], 2, '.', ''),
            'description' => $data['description'] ?? 'Tour Booking Payment',
            'callback_url' => $data['callback_url'] ?? route('pesapal.callback'),
            'notification_id' => $ipnId,
            'billing_address' => [
                'email_address' => $data['email'] ?? '',
                'phone_number' => $data['phone'] ?? '',
                'country_code' => $data['country_code'] ?? 'KE',
                'first_name' => $data['first_name'] ?? '',
                'middle_name' => $data['middle_name'] ?? '',
                'last_name' => $data['last_name'] ?? '',
                'line_1' => $data['address_line_1'] ?? '',
                'line_2' => $data['address_line_2'] ?? '',
                'city' => $data['city'] ?? '',
                'state' => $data['state'] ?? '',
                'postal_code' => $data['postal_code'] ?? '',
                'zip_code' => $data['zip_code'] ?? '',
            ],
        ];

        // Add account number for recurring payments if provided
        if (isset($data['account_number'])) {
            $orderData['account_number'] = $data['account_number'];
        }

        // Add subscription details if provided
        if (isset($data['subscription_details'])) {
            $orderData['subscription_details'] = $data['subscription_details'];
        }

        return $this->submitOrderRequest($orderData);
    }

    /**
     * Verify payment status
     */
    public function verifyPayment(string $orderTrackingId): array
    {
        return $this->getTransactionStatus($orderTrackingId);
    }

    /**
     * Refund payment
     */
    public function refundPayment(string $transactionId, float $amount = null): array
    {
        // For Pesapal, we need confirmation code, not tracking ID
        // This would need to be stored when payment is completed
        // For now, return error as we need confirmation code
        return [
            'success' => false,
            'error' => 'Refund requires confirmation code. Use requestRefund method with confirmation code.',
        ];
    }

    /**
     * Get payment status
     */
    public function getPaymentStatus(string $transactionId): string
    {
        $status = $this->getTransactionStatus($transactionId);
        
        if (!$status['success']) {
            return 'unknown';
        }

        $statusCode = $status['data']['status_code'] ?? null;
        $paymentStatus = $status['data']['payment_status_description'] ?? null;

        if ($statusCode == 1 && $paymentStatus == 'COMPLETED') {
            return 'completed';
        } elseif ($statusCode == 2 && $paymentStatus == 'FAILED') {
            return 'failed';
        } elseif ($statusCode == 0) {
            return 'pending';
        }

        return 'unknown';
    }

    /**
     * Handle webhook (IPN)
     */
    public function handleWebhook(array $payload): bool
    {
        // Pesapal uses GET requests for IPN, not POST webhooks
        // This is handled in PesapalController::ipn
        return true;
    }
}

