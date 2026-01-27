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
        Schema::create('hero_slides', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('subtitle')->nullable();
            $table->string('badge_text')->nullable();
            $table->string('badge_icon')->nullable();
            
            // Image - reference to gallery or direct path
            $table->unsignedBigInteger('image_id')->nullable(); // Reference to galleries table
            $table->string('image_url')->nullable(); // Direct image path (images/hero-slider/...)
            
            // Buttons/Actions
            $table->string('primary_button_text')->nullable();
            $table->string('primary_button_link')->nullable();
            $table->string('primary_button_icon')->nullable();
            $table->string('secondary_button_text')->nullable();
            $table->string('secondary_button_link')->nullable();
            $table->string('secondary_button_icon')->nullable();
            
            // Display settings
            $table->integer('display_order')->default(0);
            $table->string('animation_type')->default('fade-in-up'); // fade-in-up, slide-left, etc.
            $table->boolean('is_active')->default(true);
            
            // Overlay settings
            $table->string('overlay_type')->default('gradient'); // gradient, dark, light, none
            
            $table->timestamps();
            $table->softDeletes();
            
            // Foreign key
            $table->foreign('image_id')->references('id')->on('galleries')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hero_slides');
    }
};




