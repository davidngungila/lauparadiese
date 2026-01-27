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
        Schema::create('gallery_tags', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->string('slug')->unique();
            $table->string('color')->nullable(); // For UI display
            $table->integer('usage_count')->default(0);
            $table->timestamps();
            
            $table->index('slug');
        });
        
        // Pivot table for gallery-tag many-to-many relationship
        Schema::create('gallery_gallery_tag', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('gallery_id');
            $table->unsignedBigInteger('gallery_tag_id');
            $table->timestamps();
            
            $table->unique(['gallery_id', 'gallery_tag_id']);
            $table->index('gallery_id');
            $table->index('gallery_tag_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('gallery_gallery_tag');
        Schema::dropIfExists('gallery_tags');
    }
};
