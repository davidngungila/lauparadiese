<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payment_gateways', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // stripe, paypal, payoneer
            $table->string('display_name'); // Stripe, PayPal, Payoneer
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(false);
            $table->boolean('is_test_mode')->default(true);
            
            // API Credentials (stored as JSON for flexibility)
            $table->json('credentials')->nullable();
            
            // Configuration options
            $table->json('supported_currencies')->nullable(); // ['USD', 'EUR', 'GBP']
            $table->json('supported_payment_methods')->nullable(); // ['card', 'paypal', 'bank_transfer']
            $table->decimal('transaction_fee_percentage', 5, 2)->default(0.00);
            $table->decimal('transaction_fee_fixed', 10, 2)->default(0.00);
            
            // Gateway-specific settings
            $table->json('settings')->nullable(); // Additional gateway-specific settings
            
            // Status and metadata
            $table->string('status')->default('inactive'); // active, inactive, pending
            $table->text('webhook_url')->nullable();
            $table->text('webhook_secret')->nullable();
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payment_gateways');
    }
};
