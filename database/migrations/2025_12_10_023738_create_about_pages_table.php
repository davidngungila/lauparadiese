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
        Schema::create('about_pages', function (Blueprint $table) {
            $table->id();
            $table->string('section_key')->unique(); // e.g., 'hero', 'story', 'mission', 'values', 'team', etc.
            $table->string('section_name'); // Human-readable name
            $table->text('content')->nullable(); // JSON or text content
            $table->json('data')->nullable(); // Structured data for complex sections
            $table->string('image_url')->nullable();
            $table->integer('display_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
        
        // Create separate tables for dynamic content
        Schema::create('about_page_team_members', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('role');
            $table->text('bio')->nullable();
            $table->string('image_url')->nullable();
            $table->json('expertise')->nullable(); // Array of expertise items
            $table->json('social_links')->nullable(); // Array of social links
            $table->integer('display_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
        
        Schema::create('about_page_values', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description');
            $table->string('icon')->nullable(); // FontAwesome icon class
            $table->integer('display_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
        
        Schema::create('about_page_recognitions', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('icon')->nullable(); // FontAwesome icon class
            $table->string('year')->nullable();
            $table->integer('display_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
        
        Schema::create('about_page_timeline_items', function (Blueprint $table) {
            $table->id();
            $table->string('year');
            $table->string('title');
            $table->text('description');
            $table->integer('display_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
        
        Schema::create('about_page_statistics', function (Blueprint $table) {
            $table->id();
            $table->string('label');
            $table->string('value'); // Can be number or text
            $table->text('description')->nullable();
            $table->string('icon')->nullable();
            $table->integer('display_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('about_page_statistics');
        Schema::dropIfExists('about_page_timeline_items');
        Schema::dropIfExists('about_page_recognitions');
        Schema::dropIfExists('about_page_values');
        Schema::dropIfExists('about_page_team_members');
        Schema::dropIfExists('about_pages');
    }
};
