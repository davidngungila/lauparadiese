<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tickets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('booking_id')->nullable()->constrained()->onDelete('cascade');
            $table->string('ticket_type'); // flight, bus, train
            $table->string('ticket_number')->unique();
            $table->string('passenger_name');
            $table->string('passenger_email')->nullable();
            $table->string('passenger_phone')->nullable();
            $table->string('departure_location');
            $table->string('arrival_location');
            $table->dateTime('departure_date');
            $table->dateTime('arrival_date')->nullable();
            $table->string('airline_company')->nullable();
            $table->string('seat_number')->nullable();
            $table->string('class')->nullable(); // economy, business, first
            $table->decimal('price', 10, 2);
            $table->string('currency', 3)->default('USD');
            $table->string('status')->default('pending'); // pending, confirmed, cancelled
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tickets');
    }
};
