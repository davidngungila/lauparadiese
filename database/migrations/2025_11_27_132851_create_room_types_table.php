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
        Schema::create('room_types', function (Blueprint $table) {
            $table->id();
            $table->foreignId('hotel_id')->constrained()->onDelete('cascade');
            $table->string('name');
            $table->string('category')->nullable(); // standard, deluxe, suite, presidential, family
            $table->integer('max_occupancy')->default(2);
            $table->integer('total_rooms')->default(1);
            $table->integer('available_rooms')->default(0);
            $table->decimal('base_price', 10, 2);
            $table->decimal('weekend_price', 10, 2)->nullable();
            $table->decimal('holiday_price', 10, 2)->nullable();
            $table->integer('room_size')->nullable(); // in sq ft
            $table->string('bed_type')->nullable(); // single, double, queen, king, twin, bunk
            $table->json('amenities')->nullable(); // array of amenities
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('room_types');
    }
};
