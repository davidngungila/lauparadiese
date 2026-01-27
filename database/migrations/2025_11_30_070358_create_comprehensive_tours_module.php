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
        Schema::table('tours', function (Blueprint $table) {
            // Tour Code (Auto-code like TR-2025-0012)
            if (!Schema::hasColumn('tours', 'tour_code')) {
                $table->string('tour_code')->unique()->nullable()->after('id');
            }
            
            // Basic Information
            if (!Schema::hasColumn('tours', 'short_description')) {
                $table->text('short_description')->nullable()->after('excerpt');
            }
            if (!Schema::hasColumn('tours', 'long_description')) {
                $table->longText('long_description')->nullable()->after('short_description');
            }
            
            // Tour Details
            if (!Schema::hasColumn('tours', 'duration_nights')) {
                $table->integer('duration_nights')->nullable()->after('duration_days');
            }
            if (!Schema::hasColumn('tours', 'start_location')) {
                $table->string('start_location')->nullable()->after('duration_nights');
            }
            if (!Schema::hasColumn('tours', 'end_location')) {
                $table->string('end_location')->nullable()->after('start_location');
            }
            if (!Schema::hasColumn('tours', 'tour_type')) {
                $table->enum('tour_type', ['Private', 'Group', 'Shared', 'Customizable'])->default('Group')->after('end_location');
            }
            if (!Schema::hasColumn('tours', 'max_group_size')) {
                $table->integer('max_group_size')->nullable()->after('tour_type');
            }
            if (!Schema::hasColumn('tours', 'min_age')) {
                $table->integer('min_age')->nullable()->after('max_group_size');
            }
            if (!Schema::hasColumn('tours', 'difficulty_level')) {
                $table->enum('difficulty_level', ['Easy', 'Medium', 'Hard'])->nullable()->after('min_age');
            }
            if (!Schema::hasColumn('tours', 'highlights')) {
                $table->json('highlights')->nullable()->after('difficulty_level');
            }
            
            // Inclusions & Exclusions
            if (!Schema::hasColumn('tours', 'inclusions')) {
                $table->json('inclusions')->nullable()->after('highlights');
            }
            if (!Schema::hasColumn('tours', 'exclusions')) {
                $table->json('exclusions')->nullable()->after('inclusions');
            }
            
            // Additional Info
            if (!Schema::hasColumn('tours', 'terms_conditions')) {
                $table->longText('terms_conditions')->nullable()->after('exclusions');
            }
            if (!Schema::hasColumn('tours', 'cancellation_policy')) {
                $table->longText('cancellation_policy')->nullable()->after('terms_conditions');
            }
            if (!Schema::hasColumn('tours', 'important_notes')) {
                $table->longText('important_notes')->nullable()->after('cancellation_policy');
            }
            
            // Gallery Images
            if (!Schema::hasColumn('tours', 'gallery_images')) {
                $table->json('gallery_images')->nullable()->after('image_url');
            }
            
            // Visibility & SEO
            if (!Schema::hasColumn('tours', 'publish_status')) {
                $table->enum('publish_status', ['Draft', 'Published', 'Hidden'])->default('Draft')->after('important_notes');
            }
            if (!Schema::hasColumn('tours', 'meta_title')) {
                $table->string('meta_title')->nullable()->after('publish_status');
            }
            if (!Schema::hasColumn('tours', 'meta_description')) {
                $table->text('meta_description')->nullable()->after('meta_title');
            }
            if (!Schema::hasColumn('tours', 'meta_keywords')) {
                $table->text('meta_keywords')->nullable()->after('meta_description');
            }
            if (!Schema::hasColumn('tours', 'og_image')) {
                $table->string('og_image')->nullable()->after('meta_keywords');
            }
            
            // Status & Availability
            if (!Schema::hasColumn('tours', 'status')) {
                $table->enum('status', ['Active', 'Inactive'])->default('Active')->after('og_image');
            }
            if (!Schema::hasColumn('tours', 'availability_status')) {
                $table->enum('availability_status', ['Available', 'Sold Out'])->default('Available')->after('status');
            }
            if (!Schema::hasColumn('tours', 'starting_price')) {
                $table->decimal('starting_price', 10, 2)->nullable()->after('price');
            }
            
            // Soft deletes
            if (!Schema::hasColumn('tours', 'deleted_at')) {
                $table->softDeletes();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tours', function (Blueprint $table) {
            $columns = [
                'tour_code', 'short_description', 'long_description', 'duration_nights',
                'start_location', 'end_location', 'tour_type', 'max_group_size', 'min_age',
                'difficulty_level', 'highlights', 'inclusions', 'exclusions',
                'terms_conditions', 'cancellation_policy', 'important_notes',
                'gallery_images', 'publish_status', 'meta_title', 'meta_description',
                'meta_keywords', 'og_image', 'status', 'availability_status', 'starting_price'
            ];
            
            foreach ($columns as $column) {
                if (Schema::hasColumn('tours', $column)) {
                    $table->dropColumn($column);
                }
            }
            
            if (Schema::hasColumn('tours', 'deleted_at')) {
                $table->dropSoftDeletes();
            }
        });
    }
};
