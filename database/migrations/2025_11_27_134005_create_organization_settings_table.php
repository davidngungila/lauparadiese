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
        Schema::create('organization_settings', function (Blueprint $table) {
            $table->id();
            $table->string('organization_name')->default('Lau Paradise Adventures');
            $table->string('logo_url')->nullable();
            $table->text('address')->nullable();
            $table->string('city')->nullable();
            $table->string('state')->nullable();
            $table->string('country')->nullable();
            $table->string('postal_code')->nullable();
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->string('website')->nullable();
            $table->string('tax_id')->nullable();
            $table->string('registration_number')->nullable();
            $table->text('bank_name')->nullable();
            $table->string('bank_country')->nullable();
            $table->string('iban')->nullable();
            $table->string('swift_code')->nullable();
            $table->text('invoice_footer_note')->nullable();
            $table->text('invoice_terms')->nullable();
            $table->string('currency', 3)->default('USD');
            $table->string('invoice_prefix')->default('INV');
            $table->string('quotation_prefix')->default('QT');
            $table->string('booking_prefix')->default('BK');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('organization_settings');
    }
};
