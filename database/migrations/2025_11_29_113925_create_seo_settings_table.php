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
        Schema::create('seo_settings', function (Blueprint $table) {
            $table->id();
            $table->string('page_type'); // 'homepage', 'tours', 'destinations', 'blog', 'about', etc.
            $table->string('page_identifier')->nullable(); // Specific page ID or slug
            $table->string('meta_title')->nullable();
            $table->text('meta_description')->nullable();
            $table->text('meta_keywords')->nullable();
            $table->string('og_title')->nullable();
            $table->text('og_description')->nullable();
            $table->string('og_image')->nullable();
            $table->string('og_type')->default('website');
            $table->string('twitter_card')->default('summary_large_image');
            $table->string('twitter_title')->nullable();
            $table->text('twitter_description')->nullable();
            $table->string('twitter_image')->nullable();
            $table->text('canonical_url')->nullable();
            $table->text('robots')->nullable(); // e.g., 'noindex, nofollow'
            $table->text('structured_data')->nullable(); // JSON-LD structured data
            $table->text('custom_head_code')->nullable(); // Custom HTML for <head>
            $table->text('custom_footer_code')->nullable(); // Custom HTML for footer
            $table->boolean('is_active')->default(true);
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->timestamps();
            
            $table->unique(['page_type', 'page_identifier']);
            $table->index('page_type');
            $table->index('is_active');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('seo_settings');
    }
};
