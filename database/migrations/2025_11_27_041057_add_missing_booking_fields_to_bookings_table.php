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
            return;
        }
        
        Schema::table('bookings', function (Blueprint $table) {
            if (!Schema::hasColumn('bookings', 'booking_reference')) {
                $table->string('booking_reference')->unique()->nullable()->after('id');
            }
            if (!Schema::hasColumn('bookings', 'customer_country')) {
                $table->string('customer_country')->nullable()->after('customer_phone');
            }
            if (!Schema::hasColumn('bookings', 'special_requirements')) {
                $table->text('special_requirements')->nullable()->after('notes');
            }
            if (!Schema::hasColumn('bookings', 'emergency_contact_name')) {
                $table->string('emergency_contact_name')->nullable()->after('special_requirements');
            }
            if (!Schema::hasColumn('bookings', 'emergency_contact_phone')) {
                $table->string('emergency_contact_phone')->nullable()->after('emergency_contact_name');
            }
            if (!Schema::hasColumn('bookings', 'confirmed_at')) {
                $table->timestamp('confirmed_at')->nullable()->after('status');
            }
            if (!Schema::hasColumn('bookings', 'cancelled_at')) {
                $table->timestamp('cancelled_at')->nullable()->after('confirmed_at');
            }
            if (!Schema::hasColumn('bookings', 'cancellation_reason')) {
                $table->text('cancellation_reason')->nullable()->after('cancelled_at');
            }
            if (!Schema::hasColumn('bookings', 'deposit_amount')) {
                $table->decimal('deposit_amount', 10, 2)->nullable()->after('total_price');
            }
            if (!Schema::hasColumn('bookings', 'balance_amount')) {
                $table->decimal('balance_amount', 10, 2)->nullable()->after('deposit_amount');
            }
            if (!Schema::hasColumn('bookings', 'currency')) {
                $table->string('currency', 3)->default('USD')->after('balance_amount');
            }
        });
        
        // Add indexes if they don't exist
        try {
            if (!Schema::hasColumn('bookings', 'booking_reference')) {
                return;
            }
            $sm = Schema::getConnection()->getDoctrineSchemaManager();
            $indexesFound = $sm->listTableIndexes('bookings');
            
            if (!isset($indexesFound['bookings_tour_id_departure_date_index'])) {
                Schema::table('bookings', function (Blueprint $table) {
                    $table->index(['tour_id', 'departure_date'], 'bookings_tour_id_departure_date_index');
                });
            }
            
            if (!isset($indexesFound['bookings_status_index'])) {
                Schema::table('bookings', function (Blueprint $table) {
                    $table->index('status', 'bookings_status_index');
                });
            }
            
            if (!isset($indexesFound['bookings_booking_reference_index'])) {
                Schema::table('bookings', function (Blueprint $table) {
                    $table->index('booking_reference', 'bookings_booking_reference_index');
                });
            }
        } catch (\Exception $e) {
            // Indexes might already exist or table structure issue
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $columns = [
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
            ];
            
            foreach ($columns as $column) {
                if (Schema::hasColumn('bookings', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
