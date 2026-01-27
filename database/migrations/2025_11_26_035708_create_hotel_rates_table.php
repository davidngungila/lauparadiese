<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('hotel_rates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('hotel_id')->constrained()->onDelete('cascade');
            $table->string('room_type');
            $table->decimal('rate_per_night', 10, 2);
            $table->string('currency', 3)->default('USD');
            $table->date('valid_from');
            $table->date('valid_to')->nullable();
            $table->integer('min_nights')->default(1);
            $table->integer('max_guests')->default(2);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('hotel_rates');
    }
};
