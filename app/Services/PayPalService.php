<?php

namespace App\Services;

use App\Models\PaymentGateway;
use PayPal\Rest\ApiContext;
use PayPal\Auth\OAuthTokenCredential;
use PayPal\Api\Payer;
use PayPal\Api\Amount;
use PayPal\Api\Transaction;
use PayPal\Api\RedirectUrls;
use PayPal\Api\Payment;
use PayPal\Api\PaymentExecution;
use PayPal\Exception\PayPalConnectionException;
use Illuminate\Support\Facades\Log;

class PayPalService extends PaymentGatewayService
{
    protected ApiContext $apiContext;

    public function __construct(PaymentGateway $gateway)
    {
        parent::__construct($gateway);
        
        $credentials = $gateway->credentials ?? [];
        $clientId = $gateway->is_test_mode 
            ? ($credentials['test_client_id'] ?? '')
            : ($credentials['live_client_id'] ?? '');
        $secret = $gateway->is_test_mode 
            ? ($credentials['test_secret'] ?? '')
            : ($credentials['live_secret'] ?? '');

        $this->apiContext = new ApiContext(
            new OAuthTokenCredential($clientId, $secret)
        );

        $this->apiContext->setConfig([
            'mode' => $gateway->is_test_mode ? 'sandbox' : 'live',
            'log.LogEnabled' => true,
            'log.FileName' => storage_path('logs/paypal.log'),
            'log.LogLevel' => 'DEBUG',
        ]);
    }

    public function processPayment(array $data): array
    {
        try {
            $payer = new Payer();
            $payer->setPaymentMethod('paypal');

            $amount = new Amount();
            $amount->setTotal(number_format($data['amount'], 2, '.', ''));
            $amount->setCurrency(strtoupper($data['currency'] ?? 'USD'));

            $transaction = new Transaction();
            $transaction->setAmount($amount);
            $transaction->setDescription($data['description'] ?? 'Tour Booking Payment');

            $redirectUrls = new RedirectUrls();
            $redirectUrls->setReturnUrl($data['return_url'] ?? url('/paypal/success'))
                        ->setCancelUrl($data['cancel_url'] ?? url('/paypal/cancel'));

            $payment = new Payment();
            $payment->setIntent('sale')
                   ->setPayer($payer)
                   ->setTransactions([$transaction])
                   ->setRedirectUrls($redirectUrls);

            $payment->create($this->apiContext);

            $this->log('PayPal payment created', [
                'payment_id' => $payment->getId(),
                'amount' => $data['amount'],
            ]);

            $approvalUrl = $payment->getApprovalLink();

            return [
                'success' => true,
                'payment_id' => $payment->getId(),
                'approval_url' => $approvalUrl,
                'status' => $payment->getState(),
            ];
        } catch (PayPalConnectionException $e) {
            $this->log('PayPal payment error', ['error' => $e->getData()]);
            return [
                'success' => false,
                'error' => $e->getMessage(),
            ];
        }
    }

    public function executePayment(string $paymentId, string $payerId): array
    {
        try {
            $payment = Payment::get($paymentId, $this->apiContext);
            $execution = new PaymentExecution();
            $execution->setPayerId($payerId);

            $result = $payment->execute($execution, $this->apiContext);

            $this->log('PayPal payment executed', [
                'payment_id' => $paymentId,
                'state' => $result->getState(),
            ]);

            return [
                'success' => $result->getState() === 'approved',
                'payment_id' => $paymentId,
                'state' => $result->getState(),
                'transaction_id' => $result->getTransactions()[0]->getRelatedResources()[0]->getSale()->getId(),
            ];
        } catch (PayPalConnectionException $e) {
            $this->log('PayPal execution error', ['error' => $e->getData()]);
            return [
                'success' => false,
                'error' => $e->getMessage(),
            ];
        }
    }

    public function verifyPayment(string $paymentId): array
    {
        try {
            $payment = Payment::get($paymentId, $this->apiContext);

            return [
                'success' => true,
                'status' => $payment->getState(),
                'amount' => $payment->getTransactions()[0]->getAmount()->getTotal(),
                'currency' => $payment->getTransactions()[0]->getAmount()->getCurrency(),
            ];
        } catch (PayPalConnectionException $e) {
            return [
                'success' => false,
                'error' => $e->getMessage(),
            ];
        }
    }

    public function refundPayment(string $transactionId, float $amount = null): array
    {
        try {
            $sale = new \PayPal\Api\Sale();
            $sale->setId($transactionId);

            $refund = new \PayPal\Api\Refund();
            if ($amount !== null) {
                $refundAmount = new Amount();
                $refundAmount->setTotal(number_format($amount, 2, '.', ''));
                $refund->setAmount($refundAmount);
            }

            $refundedSale = $sale->refundSale($refund, $this->apiContext);

            $this->log('PayPal refund processed', [
                'refund_id' => $refundedSale->getId(),
                'amount' => $refundedSale->getAmount()->getTotal(),
            ]);

            return [
                'success' => true,
                'refund_id' => $refundedSale->getId(),
                'amount' => $refundedSale->getAmount()->getTotal(),
                'state' => $refundedSale->getState(),
            ];
        } catch (PayPalConnectionException $e) {
            $this->log('PayPal refund error', ['error' => $e->getData()]);
            return [
                'success' => false,
                'error' => $e->getMessage(),
            ];
        }
    }

    public function getPaymentStatus(string $paymentId): string
    {
        try {
            $payment = Payment::get($paymentId, $this->apiContext);
            return $payment->getState();
        } catch (PayPalConnectionException $e) {
            return 'unknown';
        }
    }

    public function handleWebhook(array $payload): bool
    {
        try {
            $eventType = $payload['event_type'] ?? null;

            switch ($eventType) {
                case 'PAYMENT.SALE.COMPLETED':
                    $this->log('PayPal webhook: Payment completed', $payload);
                    return true;

                case 'PAYMENT.SALE.DENIED':
                    $this->log('PayPal webhook: Payment denied', $payload);
                    return true;

                default:
                    return false;
            }
        } catch (\Exception $e) {
            $this->log('PayPal webhook error', ['error' => $e->getMessage()]);
            return false;
        }
    }
}





