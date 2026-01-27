<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (!Schema::hasTable('mpesa_transactions')) {
            Schema::create('mpesa_transactions', function (Blueprint $table) {
                $table->id();
                $table->string('transaction_type')->index(); // stk_push, c2b, b2c, reversal, balance_query
                $table->string('transaction_id')->nullable()->index(); // M-PESA transaction ID
                $table->string('merchant_request_id')->nullable()->index();
                $table->string('checkout_request_id')->nullable()->index();
                $table->string('conversation_id')->nullable()->index();
                $table->string('originator_conversation_id')->nullable();
                $table->decimal('amount', 15, 2)->nullable();
                $table->string('phone_number', 20)->nullable()->index();
                $table->string('account_reference')->nullable()->index();
                $table->string('business_short_code', 20)->nullable();
                $table->string('mpesa_receipt_number')->nullable()->index();
                $table->datetime('transaction_date')->nullable();
                $table->decimal('balance', 15, 2)->nullable();
                $table->integer('result_code')->nullable()->index();
                $table->text('result_description')->nullable();
                $table->string('status')->default('pending')->index(); // pending, completed, failed, timeout, cancelled
                $table->json('metadata')->nullable(); // Additional transaction data
                $table->json('callback_data')->nullable(); // Full callback payload
                $table->datetime('processed_at')->nullable();
                $table->timestamps();

                // Indexes for common queries
                $table->index(['status', 'transaction_type']);
                $table->index(['created_at', 'status']);
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mpesa_transactions');
    }
};






