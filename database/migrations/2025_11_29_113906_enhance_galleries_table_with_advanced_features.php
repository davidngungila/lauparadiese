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
        Schema::table('galleries', function (Blueprint $table) {
            // Image metadata
            $table->string('caption')->nullable()->after('description');
            $table->text('alt_text')->nullable()->after('caption');
            $table->string('original_filename')->nullable()->after('image_url');
            $table->string('file_size')->nullable()->after('original_filename');
            $table->string('mime_type')->nullable()->after('file_size');
            $table->integer('width')->nullable()->after('mime_type');
            $table->integer('height')->nullable()->after('width');
            $table->string('thumbnail_150')->nullable()->after('height');
            $table->string('thumbnail_300')->nullable()->after('thumbnail_150');
            $table->string('thumbnail_600')->nullable()->after('thumbnail_300');
            $table->string('thumbnail_hd')->nullable()->after('thumbnail_600');
            $table->string('webp_url')->nullable()->after('thumbnail_hd');
            
            // Organization
            $table->unsignedBigInteger('album_id')->nullable()->after('category');
            $table->json('tags')->nullable()->after('album_id');
            
            // Display controls
            $table->enum('priority', ['high', 'medium', 'low'])->default('medium')->after('display_order');
            $table->enum('visibility', ['all', 'mobile', 'desktop'])->default('all')->after('priority');
            $table->dateTime('visible_from')->nullable()->after('visibility');
            $table->dateTime('visible_until')->nullable()->after('visible_from');
            
            // Click action
            $table->enum('click_action', ['lightbox', 'link', 'none'])->default('lightbox')->after('visible_until');
            $table->string('click_link')->nullable()->after('click_action');
            
            // SEO
            $table->string('seo_filename')->nullable()->after('click_link');
            $table->text('seo_alt_text')->nullable()->after('seo_filename');
            
            // Quality settings
            $table->boolean('auto_optimize')->default(true)->after('seo_alt_text');
            $table->boolean('convert_to_webp')->default(true)->after('auto_optimize');
            $table->boolean('resize_large')->default(true)->after('convert_to_webp');
            $table->integer('optimization_quality')->default(80)->after('resize_large');
            
            // Upload info
            $table->unsignedBigInteger('uploaded_by')->nullable()->after('optimization_quality');
            $table->timestamp('uploaded_at')->nullable()->after('uploaded_by');
            
            // Soft deletes for recycle bin
            $table->softDeletes()->after('updated_at');
            
            // Indexes
            $table->index('album_id');
            $table->index('uploaded_by');
            $table->index('priority');
            $table->index('visibility');
            $table->index('deleted_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('galleries', function (Blueprint $table) {
            $table->dropIndex(['album_id']);
            $table->dropIndex(['uploaded_by']);
            $table->dropIndex(['priority']);
            $table->dropIndex(['visibility']);
            $table->dropIndex(['deleted_at']);
            
            $table->dropColumn([
                'caption', 'alt_text', 'original_filename', 'file_size', 'mime_type',
                'width', 'height', 'thumbnail_150', 'thumbnail_300', 'thumbnail_600',
                'thumbnail_hd', 'webp_url', 'album_id', 'tags', 'priority', 'visibility',
                'visible_from', 'visible_until', 'click_action', 'click_link',
                'seo_filename', 'seo_alt_text', 'auto_optimize', 'convert_to_webp',
                'resize_large', 'optimization_quality', 'uploaded_by', 'uploaded_at',
                'deleted_at'
            ]);
        });
    }
};
