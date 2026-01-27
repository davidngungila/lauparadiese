<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\PaymentGateway;
use App\Services\PaymentGatewayFactory;
use Illuminate\Http\Request;

class PaymentProcessingController extends Controller
{
    /**
     * Handle Stripe payment success
     */
    public function stripeSuccess(Request $request)
    {
        $paymentIntentId = $request->input('payment_intent');
        
        if ($paymentIntentId) {
            $gateway = PaymentGateway::byName('stripe');
            if ($gateway && $gateway->is_active) {
                $gatewayService = PaymentGatewayFactory::create($gateway);
                $result = $gatewayService->verifyPayment($paymentIntentId);
                
                if ($result['success'] && $result['status'] === 'succeeded') {
                    $payment = Payment::where('gateway_transaction_id', $paymentIntentId)->first();
                    if ($payment) {
                        $payment->update([
                            'status' => 'completed',
                            'paid_at' => now(),
                        ]);
                    }
                    
                    return redirect()->route('booking.confirmation', $payment->booking_id ?? 0)
                        ->with('success', 'Payment completed successfully!');
                }
            }
        }
        
        return redirect()->route('booking')
            ->with('error', 'Payment verification failed.');
    }

    /**
     * Handle PayPal payment success
     */
    public function paypalSuccess(Request $request)
    {
        $paymentId = $request->input('paymentId');
        $payerId = $request->input('PayerID');
        
        if ($paymentId && $payerId) {
            $gateway = PaymentGateway::byName('paypal');
            if ($gateway && $gateway->is_active) {
                $gatewayService = PaymentGatewayFactory::create($gateway);
                $result = $gatewayService->executePayment($paymentId, $payerId);
                
                if ($result['success']) {
                    $payment = Payment::where('gateway_transaction_id', $paymentId)->first();
                    if ($payment) {
                        $payment->update([
                            'status' => 'completed',
                            'paid_at' => now(),
                            'gateway_transaction_id' => $result['transaction_id'] ?? $paymentId,
                        ]);
                    }
                    
                    return redirect()->route('booking.confirmation', $payment->booking_id ?? 0)
                        ->with('success', 'Payment completed successfully!');
                }
            }
        }
        
        return redirect()->route('booking')
            ->with('error', 'Payment execution failed.');
    }

    /**
     * Handle payment cancellation
     */
    public function cancel()
    {
        return redirect()->route('booking')
            ->with('warning', 'Payment was cancelled.');
    }

    /**
     * Handle webhooks
     */
    public function webhook(Request $request, string $gateway)
    {
        $gatewayModel = PaymentGateway::byName($gateway);
        
        if (!$gatewayModel || !$gatewayModel->is_active) {
            return response()->json(['error' => 'Gateway not found or inactive'], 404);
        }
        
        $gatewayService = PaymentGatewayFactory::create($gatewayModel);
        $payload = $request->all();
        
        if ($gatewayService->handleWebhook($payload)) {
            return response()->json(['success' => true], 200);
        }
        
        return response()->json(['error' => 'Webhook handling failed'], 400);
    }

    /**
     * Get Stripe publishable key
     */
    public function getStripeKey()
    {
        $gateway = PaymentGateway::byName('stripe');
        
        if (!$gateway || !$gateway->is_active) {
            return response()->json(['error' => 'Stripe gateway not configured'], 404);
        }
        
        $credentials = $gateway->credentials ?? [];
        
        // Try different possible key names
        $publishableKey = '';
        if ($gateway->is_test_mode) {
            $publishableKey = $credentials['test_publishable_key'] 
                ?? $credentials['publishable_key'] 
                ?? '';
        } else {
            $publishableKey = $credentials['live_publishable_key'] 
                ?? $credentials['publishable_key'] 
                ?? '';
        }
        
        if (empty($publishableKey)) {
            return response()->json(['error' => 'Stripe publishable key not configured'], 404);
        }
        
        return response()->json([
            'publishable_key' => $publishableKey,
        ]);
    }

    /**
     * Create Stripe payment intent
     */
    public function createStripeIntent(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:0.01',
            'payment_method_id' => 'nullable|string',
        ]);
        
        $gateway = PaymentGateway::byName('stripe');
        
        if (!$gateway || !$gateway->is_active) {
            return response()->json(['error' => 'Stripe gateway not configured'], 404);
        }
        
        $gatewayService = PaymentGatewayFactory::create($gateway);
        
        $paymentData = [
            'amount' => $request->amount,
            'currency' => 'usd',
            'description' => 'Tour Booking Payment',
            'payment_method_types' => ['card', 'apple_pay', 'google_pay', 'link'],
        ];
        
        $result = $gatewayService->processPayment($paymentData);
        
        if ($result['success']) {
            return response()->json([
                'client_secret' => $result['client_secret'],
                'payment_intent_id' => $result['payment_intent_id'],
            ]);
        }
        
        return response()->json([
            'error' => $result['error'] ?? 'Failed to create payment intent',
        ], 400);
    }
}


