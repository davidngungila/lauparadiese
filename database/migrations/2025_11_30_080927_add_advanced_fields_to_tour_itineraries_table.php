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
        if (!Schema::hasTable('tour_itineraries')) {
            return;
        }
        
        Schema::table('tour_itineraries', function (Blueprint $table) {
            // Short summary
            if (!Schema::hasColumn('tour_itineraries', 'short_summary')) {
                $table->text('short_summary')->nullable()->after('title');
            }
            
            // Accommodation details
            if (!Schema::hasColumn('tour_itineraries', 'accommodation_location')) {
                $table->string('accommodation_location')->nullable()->after('accommodation_name');
            }
            if (!Schema::hasColumn('tour_itineraries', 'accommodation_image')) {
                $table->string('accommodation_image')->nullable()->after('accommodation_location');
            }
            if (!Schema::hasColumn('tour_itineraries', 'accommodation_rating')) {
                $table->decimal('accommodation_rating', 2, 1)->nullable()->after('accommodation_image');
            }
            
            // Gallery images
            if (!Schema::hasColumn('tour_itineraries', 'gallery_images')) {
                $table->json('gallery_images')->nullable()->after('image');
            }
            
            // Transport details
            if (!Schema::hasColumn('tour_itineraries', 'vehicle_type')) {
                $table->string('vehicle_type')->nullable()->after('activities');
            }
            if (!Schema::hasColumn('tour_itineraries', 'driver_guide_notes')) {
                $table->text('driver_guide_notes')->nullable()->after('vehicle_type');
            }
            if (!Schema::hasColumn('tour_itineraries', 'transfer_info')) {
                $table->text('transfer_info')->nullable()->after('driver_guide_notes');
            }
            
            // Advanced fields
            if (!Schema::hasColumn('tour_itineraries', 'day_notes')) {
                $table->text('day_notes')->nullable()->after('transfer_info');
            }
            if (!Schema::hasColumn('tour_itineraries', 'custom_icons')) {
                $table->json('custom_icons')->nullable()->after('day_notes');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tour_itineraries', function (Blueprint $table) {
            $columns = [
                'short_summary', 'accommodation_location', 'accommodation_image',
                'accommodation_rating', 'gallery_images', 'vehicle_type',
                'driver_guide_notes', 'transfer_info', 'day_notes', 'custom_icons'
            ];
            
            foreach ($columns as $column) {
                if (Schema::hasColumn('tour_itineraries', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
