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
        Schema::create('tour_itineraries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tour_id')->constrained()->onDelete('cascade');
            $table->integer('day_number');
            $table->string('title');
            $table->longText('description');
            $table->json('meals_included')->nullable(); // ["Breakfast", "Lunch", "Dinner"]
            $table->string('accommodation_type')->nullable(); // Hotel, Lodge, Camp
            $table->string('accommodation_name')->nullable();
            $table->string('location')->nullable();
            $table->string('image')->nullable();
            $table->json('activities')->nullable(); // [{"name": "Game Drive", "icon": "car"}]
            $table->integer('sort_order')->default(0);
            $table->timestamps();
            $table->softDeletes();
            
            $table->index(['tour_id', 'day_number']);
            $table->index(['tour_id', 'sort_order']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tour_itineraries');
    }
};
