<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Payment;
use App\Http\Controllers\Admin\DocumentController;
use Illuminate\Http\Request;

class TestPaymentReceiptEmail extends Command
{
    protected $signature = 'test:payment-receipt-email {payment_id?} {email=davidngungila@gmail.com}';
    protected $description = 'Test sending payment receipt email';

    public function handle()
    {
        $paymentId = $this->argument('payment_id');
        $email = $this->argument('email');
        
        if (!$paymentId) {
            $payment = Payment::first();
            if (!$payment) {
                $this->error('No payments found in database');
                return 1;
            }
            $paymentId = $payment->id;
        } else {
            $payment = Payment::find($paymentId);
            if (!$payment) {
                $this->error("Payment with ID {$paymentId} not found");
                return 1;
            }
        }
        
        $this->info("Testing payment receipt email for Payment #{$paymentId}");
        $this->info("Sending to: {$email}");
        
        $controller = new DocumentController();
        $request = new Request(['email' => $email]);
        
        try {
            $response = $controller->sendPaymentReceipt($paymentId, $request);
            $data = json_decode($response->getContent(), true);
            
            if ($data['success'] ?? false) {
                $this->info("✓ Success: " . ($data['message'] ?? 'Email sent successfully'));
                return 0;
            } else {
                $this->error("✗ Failed: " . ($data['message'] ?? 'Unknown error'));
                return 1;
            }
        } catch (\Exception $e) {
            $this->error("✗ Exception: " . $e->getMessage());
            $this->error("Trace: " . $e->getTraceAsString());
            return 1;
        }
    }
}

