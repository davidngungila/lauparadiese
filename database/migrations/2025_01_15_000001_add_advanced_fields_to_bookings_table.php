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
        if (!Schema::hasTable('bookings')) {
            return; // Skip if bookings table doesn't exist yet
        }
        
        Schema::table('bookings', function (Blueprint $table) {
            if (!Schema::hasColumn('bookings', 'booking_reference')) {
                $table->string('booking_reference')->unique()->nullable();
            }
            if (!Schema::hasColumn('bookings', 'customer_country')) {
                $table->string('customer_country')->nullable();
            }
            if (!Schema::hasColumn('bookings', 'special_requirements')) {
                $table->text('special_requirements')->nullable();
            }
            if (!Schema::hasColumn('bookings', 'emergency_contact_name')) {
                $table->string('emergency_contact_name')->nullable();
            }
            if (!Schema::hasColumn('bookings', 'emergency_contact_phone')) {
                $table->string('emergency_contact_phone')->nullable();
            }
            if (!Schema::hasColumn('bookings', 'confirmed_at')) {
                $table->timestamp('confirmed_at')->nullable();
            }
            if (!Schema::hasColumn('bookings', 'cancelled_at')) {
                $table->timestamp('cancelled_at')->nullable();
            }
            if (!Schema::hasColumn('bookings', 'cancellation_reason')) {
                $table->text('cancellation_reason')->nullable();
            }
            if (!Schema::hasColumn('bookings', 'deposit_amount')) {
                $table->decimal('deposit_amount', 10, 2)->nullable();
            }
            if (!Schema::hasColumn('bookings', 'balance_amount')) {
                $table->decimal('balance_amount', 10, 2)->nullable();
            }
            if (!Schema::hasColumn('bookings', 'currency')) {
                $table->string('currency', 3)->default('USD');
            }
        });
        
        // Add indexes - Laravel will handle duplicates gracefully
        try {
            Schema::table('bookings', function (Blueprint $table) {
                $table->index(['tour_id', 'departure_date'], 'bookings_tour_id_departure_date_index');
            });
        } catch (\Exception $e) {
            // Index might already exist
        }
        
        try {
            Schema::table('bookings', function (Blueprint $table) {
                $table->index('status', 'bookings_status_index');
            });
        } catch (\Exception $e) {
            // Index might already exist
        }
        
        try {
            Schema::table('bookings', function (Blueprint $table) {
                $table->index('booking_reference', 'bookings_booking_reference_index');
            });
        } catch (\Exception $e) {
            // Index might already exist
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropIndex(['tour_id', 'departure_date']);
            $table->dropIndex(['status']);
            $table->dropIndex(['booking_reference']);
            $table->dropColumn([
                'booking_reference',
                'customer_country',
                'special_requirements',
                'emergency_contact_name',
                'emergency_contact_phone',
                'confirmed_at',
                'cancelled_at',
                'cancellation_reason',
                'deposit_amount',
                'balance_amount',
                'currency'
            ]);
        });
    }
};

