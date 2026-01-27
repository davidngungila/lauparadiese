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
        Schema::create('homepage_destinations', function (Blueprint $table) {
            $table->id();
            
            // Required Fields
            $table->string('name'); // Destination Name
            $table->text('short_description')->nullable(); // Short Description (1-2 lines)
            $table->text('full_description')->nullable(); // Full Description (Optional)
            $table->string('featured_image_url')->nullable(); // Destination Image (main)
            $table->json('image_gallery')->nullable(); // Image gallery (3-5 images)
            
            // Location
            $table->string('country')->nullable();
            $table->string('region')->nullable();
            $table->string('city')->nullable();
            
            // Category
            $table->string('category')->nullable(); // Mountain Trekking, National Parks, Beaches, etc.
            
            // Pricing & Duration
            $table->decimal('price', 10, 2)->nullable(); // Starting price
            $table->string('price_display')->nullable(); // "Starting from $350" or "Contact for price"
            $table->string('duration')->nullable(); // "3 days / 2 nights"
            
            // Rating
            $table->decimal('rating', 3, 2)->nullable(); // 4.8/5
            
            // Status & Featured
            $table->boolean('is_active')->default(true); // Active / Hidden
            $table->boolean('is_featured')->default(false); // Featured on homepage top section
            
            // Slug / URL
            $table->string('slug')->unique()->nullable(); // Auto-generated or manual
            
            // SEO Details
            $table->string('meta_title')->nullable();
            $table->text('meta_description')->nullable();
            $table->text('meta_keywords')->nullable();
            $table->string('og_image_url')->nullable(); // Social media preview image
            
            // Display Order
            $table->integer('display_order')->default(0);
            
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('homepage_destinations');
    }
};
