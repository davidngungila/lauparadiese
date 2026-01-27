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
        Schema::table('vehicles', function (Blueprint $table) {
            // Vehicle code (VH-0001)
            $table->string('vehicle_code')->unique()->nullable()->after('id');
            
            // Vehicle name
            $table->string('vehicle_name')->nullable()->after('vehicle_code');
            
            // Image fields
            $table->string('cover_image')->nullable()->after('vehicle_name');
            $table->json('gallery_images')->nullable()->after('cover_image');
            
            // Enhanced fields
            $table->string('chassis_number')->nullable()->after('license_plate');
            $table->string('transmission')->nullable()->after('fuel_type'); // Auto / Manual
            $table->json('features')->nullable()->after('transmission'); // Pop-up Roof, AC, Charging Ports, etc.
            
            // Status options
            $table->string('status')->default('active')->change(); // active, in_maintenance, not_available, out_of_service
            
            // Current booking reference
            $table->foreignId('current_booking_id')->nullable()->constrained('bookings')->onDelete('set null')->after('driver_id');
            
            // Odometer reading
            $table->integer('odometer_reading')->nullable()->after('next_maintenance');
            $table->text('service_notes')->nullable()->after('odometer_reading');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('vehicles', function (Blueprint $table) {
            $table->dropForeign(['current_booking_id']);
            $table->dropColumn([
                'vehicle_code',
                'vehicle_name',
                'cover_image',
                'gallery_images',
                'chassis_number',
                'transmission',
                'features',
                'current_booking_id',
                'odometer_reading',
                'service_notes'
            ]);
        });
    }
};
