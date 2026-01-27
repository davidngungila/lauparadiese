<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Payment;
use App\Services\PesapalService;
use App\Services\PaymentGatewayFactory;
use App\Mail\BookingConfirmationMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;

class PesapalController extends Controller
{
    /**
     * Handle Pesapal callback (redirect after payment)
     */
    public function callback(Request $request)
    {
        $orderTrackingId = $request->query('OrderTrackingId');
        $orderMerchantReference = $request->query('OrderMerchantReference');

        if (!$orderTrackingId) {
            return redirect()->route('booking')
                ->with('error', 'Invalid payment response.');
        }

        try {
            // Get Pesapal gateway
            $gateway = \App\Models\PaymentGateway::byName('pesapal');
            if (!$gateway || !$gateway->is_active) {
                return redirect()->route('booking')
                    ->with('error', 'Payment gateway is not configured.');
            }

            $pesapalService = PaymentGatewayFactory::create($gateway);
            
            // Get transaction status
            $transactionStatus = $pesapalService->getTransactionStatus($orderTrackingId);

            if (!$transactionStatus['success']) {
                return redirect()->route('booking')
                    ->with('error', 'Failed to verify payment status.');
            }

            $data = $transactionStatus['data'];
            $statusCode = $data['status_code'] ?? null;
            $paymentStatus = $data['payment_status_description'] ?? null;

            // Find booking by order tracking ID or merchant reference
            $booking = Booking::where('payment_gateway_id', $orderTrackingId)
                ->orWhere('booking_reference', $orderMerchantReference)
                ->first();

            if (!$booking) {
                Log::warning('Pesapal callback: Booking not found', [
                    'order_tracking_id' => $orderTrackingId,
                    'merchant_reference' => $orderMerchantReference,
                ]);
                return redirect()->route('booking')
                    ->with('error', 'Booking not found.');
            }

            // Check if payment is completed
            if ($statusCode == 1 && $paymentStatus == 'COMPLETED') {
                // Update booking status
                $booking->update([
                    'status' => 'confirmed',
                    'payment_status' => 'paid',
                    'payment_method' => 'pesapal',
                    'amount_paid' => $booking->total_price,
                    'confirmed_at' => now(),
                ]);

                // Create payment record
                Payment::updateOrCreate(
                    [
                        'booking_id' => $booking->id,
                        'gateway_transaction_id' => $orderTrackingId,
                    ],
                    [
                        'payment_method' => 'pesapal',
                        'amount' => $data['amount'] ?? $booking->total_price,
                        'currency' => $data['currency'] ?? 'KES',
                        'status' => 'completed',
                        'paid_at' => now(),
                        'payment_reference' => $data['confirmation_code'] ?? $orderTrackingId,
                        'gateway_response' => $data,
                    ]
                );

                // Send confirmation email
                try {
                    Mail::to($booking->customer_email)->send(new BookingConfirmationMail($booking));
                    Log::info('Booking confirmation email sent', [
                        'booking_id' => $booking->id,
                        'email' => $booking->customer_email,
                    ]);
                } catch (\Exception $e) {
                    Log::error('Failed to send booking confirmation email', [
                        'booking_id' => $booking->id,
                        'error' => $e->getMessage(),
                    ]);
                }

                return redirect()->route('booking.confirmation', ['booking' => $booking->id])
                    ->with('success', 'Payment completed successfully! Your booking has been confirmed.');
            } elseif ($statusCode == 2 && $paymentStatus == 'FAILED') {
                // Payment failed
                $booking->update([
                    'payment_status' => 'failed',
                ]);

                return redirect()->route('booking.confirmation', ['booking' => $booking->id])
                    ->with('error', 'Payment failed. Please try again or contact support.');
            } else {
                // Payment pending
                return redirect()->route('booking.confirmation', ['booking' => $booking->id])
                    ->with('warning', 'Payment is being processed. We will notify you once it is confirmed.');
            }
        } catch (\Exception $e) {
            Log::error('Pesapal callback error', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return redirect()->route('booking')
                ->with('error', 'An error occurred while processing your payment.');
        }
    }

    /**
     * Handle Pesapal IPN (Instant Payment Notification)
     */
    public function ipn(Request $request)
    {
        $orderTrackingId = $request->query('OrderTrackingId');
        $orderMerchantReference = $request->query('OrderMerchantReference');
        $orderNotificationType = $request->query('OrderNotificationType');

        Log::info('Pesapal IPN received', [
            'order_tracking_id' => $orderTrackingId,
            'merchant_reference' => $orderMerchantReference,
            'notification_type' => $orderNotificationType,
        ]);

        if (!$orderTrackingId) {
            return response()->json([
                'status' => 500,
                'message' => 'Missing OrderTrackingId',
            ], 400);
        }

        try {
            // Get Pesapal gateway
            $gateway = \App\Models\PaymentGateway::byName('pesapal');
            if (!$gateway || !$gateway->is_active) {
                return response()->json([
                    'status' => 500,
                    'message' => 'Payment gateway not configured',
                ], 400);
            }

            $pesapalService = PaymentGatewayFactory::create($gateway);
            
            // Get transaction status
            $transactionStatus = $pesapalService->getTransactionStatus($orderTrackingId);

            if (!$transactionStatus['success']) {
                return response()->json([
                    'status' => 500,
                    'message' => 'Failed to get transaction status',
                ], 400);
            }

            $data = $transactionStatus['data'];
            $statusCode = $data['status_code'] ?? null;
            $paymentStatus = $data['payment_status_description'] ?? null;

            // Find booking
            $booking = Booking::where('payment_gateway_id', $orderTrackingId)
                ->orWhere('booking_reference', $orderMerchantReference)
                ->first();

            if (!$booking) {
                Log::warning('Pesapal IPN: Booking not found', [
                    'order_tracking_id' => $orderTrackingId,
                    'merchant_reference' => $orderMerchantReference,
                ]);

                return response()->json([
                    'orderNotificationType' => $orderNotificationType ?? 'PAYMENT',
                    'orderTrackingId' => $orderTrackingId,
                    'orderMerchantReference' => $orderMerchantReference ?? '',
                    'status' => 500,
                ]);
            }

            // Handle different notification types
            if ($orderNotificationType === 'RECURRING') {
                // Handle recurring payment
                $this->handleRecurringPayment($booking, $data);
            } else {
                // Handle regular payment
                if ($statusCode == 1 && $paymentStatus == 'COMPLETED') {
                    DB::transaction(function () use ($booking, $data, $orderTrackingId) {
                        // Update booking
                        $booking->update([
                            'status' => 'confirmed',
                            'payment_status' => 'paid',
                            'payment_method' => 'pesapal',
                            'amount_paid' => $data['amount'] ?? $booking->total_price,
                            'confirmed_at' => now(),
                        ]);

                        // Create or update payment record
                        Payment::updateOrCreate(
                            [
                                'booking_id' => $booking->id,
                                'gateway_transaction_id' => $orderTrackingId,
                            ],
                            [
                                'payment_method' => 'pesapal',
                                'amount' => $data['amount'] ?? $booking->total_price,
                                'currency' => $data['currency'] ?? 'KES',
                                'status' => 'completed',
                                'paid_at' => now(),
                                'payment_reference' => $data['confirmation_code'] ?? $orderTrackingId,
                                'gateway_response' => $data,
                            ]
                        );

                        // Send confirmation email
                        try {
                            Mail::to($booking->customer_email)->send(new BookingConfirmationMail($booking));
                            Log::info('Booking confirmation email sent via IPN', [
                                'booking_id' => $booking->id,
                            ]);
                        } catch (\Exception $e) {
                            Log::error('Failed to send booking confirmation email via IPN', [
                                'booking_id' => $booking->id,
                                'error' => $e->getMessage(),
                            ]);
                        }
                    });
                }
            }

            // Return success response to Pesapal
            return response()->json([
                'orderNotificationType' => $orderNotificationType ?? 'PAYMENT',
                'orderTrackingId' => $orderTrackingId,
                'orderMerchantReference' => $orderMerchantReference ?? $booking->booking_reference,
                'status' => 200,
            ]);
        } catch (\Exception $e) {
            Log::error('Pesapal IPN error', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return response()->json([
                'orderNotificationType' => $orderNotificationType ?? 'PAYMENT',
                'orderTrackingId' => $orderTrackingId,
                'orderMerchantReference' => $orderMerchantReference ?? '',
                'status' => 500,
            ]);
        }
    }

    /**
     * Handle recurring payment notification
     */
    private function handleRecurringPayment(Booking $booking, array $data)
    {
        $subscriptionInfo = $data['subscription_transaction_info'] ?? null;

        if (!$subscriptionInfo) {
            Log::warning('Pesapal recurring payment: No subscription info', [
                'booking_id' => $booking->id,
            ]);
            return;
        }

        DB::transaction(function () use ($booking, $data, $subscriptionInfo) {
            // Create payment record for recurring payment
            Payment::create([
                'booking_id' => $booking->id,
                'payment_method' => 'pesapal_recurring',
                'amount' => $subscriptionInfo['amount'] ?? $booking->balance_amount,
                'currency' => $data['currency'] ?? 'KES',
                'status' => 'completed',
                'paid_at' => now(),
                'payment_reference' => $data['confirmation_code'] ?? ($subscriptionInfo['correlation_id'] ?? null),
                'gateway_transaction_id' => $subscriptionInfo['correlation_id'] ?? null,
                'gateway_response' => $data,
            ]);

            // Update booking amount paid
            $totalPaid = $booking->amount_paid + ($subscriptionInfo['amount'] ?? 0);
            $booking->update([
                'amount_paid' => $totalPaid,
                'payment_status' => $totalPaid >= $booking->total_price ? 'paid' : 'partial',
            ]);

            // Send notification email
            try {
                Mail::to($booking->customer_email)->send(new BookingConfirmationMail($booking));
            } catch (\Exception $e) {
                Log::error('Failed to send recurring payment email', [
                    'booking_id' => $booking->id,
                    'error' => $e->getMessage(),
                ]);
            }
        });
    }
}

