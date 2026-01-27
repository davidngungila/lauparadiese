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
        Schema::table('homepage_destinations', function (Blueprint $table) {
            $table->unsignedBigInteger('featured_image_id')->nullable()->after('featured_image_url');
            $table->unsignedBigInteger('og_image_id')->nullable()->after('og_image_url');
            $table->json('gallery_image_ids')->nullable()->after('image_gallery');
            
            $table->foreign('featured_image_id')->references('id')->on('galleries')->onDelete('set null');
            $table->foreign('og_image_id')->references('id')->on('galleries')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('homepage_destinations', function (Blueprint $table) {
            $table->dropForeign(['featured_image_id']);
            $table->dropForeign(['og_image_id']);
            $table->dropColumn(['featured_image_id', 'og_image_id', 'gallery_image_ids']);
        });
    }
};
