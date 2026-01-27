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
            // Customer Information
            if (!Schema::hasColumn('bookings', 'passport_number')) {
                $table->string('passport_number')->nullable()->after('customer_country');
            }
            if (!Schema::hasColumn('bookings', 'city')) {
                $table->string('city')->nullable()->after('customer_country');
            }
            
            // Booking Details
            if (!Schema::hasColumn('bookings', 'travel_end_date')) {
                $table->date('travel_end_date')->nullable()->after('departure_date');
            }
            if (!Schema::hasColumn('bookings', 'number_of_adults')) {
                $table->integer('number_of_adults')->default(1)->after('travelers');
            }
            if (!Schema::hasColumn('bookings', 'number_of_children')) {
                $table->integer('number_of_children')->default(0)->after('number_of_adults');
            }
            if (!Schema::hasColumn('bookings', 'accommodation_level')) {
                $table->enum('accommodation_level', ['budget', 'midrange', 'luxury'])->nullable()->after('number_of_children');
            }
            if (!Schema::hasColumn('bookings', 'pickup_location')) {
                $table->string('pickup_location')->nullable()->after('accommodation_level');
            }
            if (!Schema::hasColumn('bookings', 'dropoff_location')) {
                $table->string('dropoff_location')->nullable()->after('pickup_location');
            }
            
            // Payment Details
            if (!Schema::hasColumn('bookings', 'payment_status')) {
                $table->enum('payment_status', ['unpaid', 'partial', 'paid'])->default('unpaid')->after('payment_method');
            }
            if (!Schema::hasColumn('bookings', 'amount_paid')) {
                $table->decimal('amount_paid', 10, 2)->default(0)->after('payment_status');
            }
            if (!Schema::hasColumn('bookings', 'discount_amount')) {
                $table->decimal('discount_amount', 10, 2)->nullable()->after('total_price');
            }
            if (!Schema::hasColumn('bookings', 'discount_percentage')) {
                $table->decimal('discount_percentage', 5, 2)->nullable()->after('discount_amount');
            }
            if (!Schema::hasColumn('bookings', 'payment_receipt_path')) {
                $table->string('payment_receipt_path')->nullable()->after('amount_paid');
            }
            
            // Administration
            if (!Schema::hasColumn('bookings', 'assigned_staff_id')) {
                $table->foreignId('assigned_staff_id')->nullable()->constrained('users')->onDelete('set null')->after('user_id');
            }
            if (!Schema::hasColumn('bookings', 'booking_source')) {
                $table->enum('booking_source', ['website', 'manual', 'whatsapp', 'referral', 'agent'])->default('manual')->after('assigned_staff_id');
            }
            
            // Cancellation & Refunds
            if (!Schema::hasColumn('bookings', 'refund_amount')) {
                $table->decimal('refund_amount', 10, 2)->nullable()->after('cancellation_reason');
            }
            if (!Schema::hasColumn('bookings', 'refund_status')) {
                $table->enum('refund_status', ['pending', 'processed', 'rejected'])->nullable()->after('refund_amount');
            }
            
            // Attachments
            if (!Schema::hasColumn('bookings', 'attachments')) {
                $table->json('attachments')->nullable()->after('payment_receipt_path');
            }
            
            // Approval workflow
            if (!Schema::hasColumn('bookings', 'approval_status')) {
                $table->enum('approval_status', ['pending', 'approved', 'rejected'])->default('pending')->after('status');
            }
            if (!Schema::hasColumn('bookings', 'approved_by')) {
                $table->foreignId('approved_by')->nullable()->constrained('users')->onDelete('set null')->after('approval_status');
            }
            if (!Schema::hasColumn('bookings', 'approved_at')) {
                $table->timestamp('approved_at')->nullable()->after('approved_by');
            }
            if (!Schema::hasColumn('bookings', 'rejected_by')) {
                $table->foreignId('rejected_by')->nullable()->constrained('users')->onDelete('set null')->after('approved_at');
            }
            if (!Schema::hasColumn('bookings', 'rejected_at')) {
                $table->timestamp('rejected_at')->nullable()->after('rejected_by');
            }
            if (!Schema::hasColumn('bookings', 'rejection_reason')) {
                $table->text('rejection_reason')->nullable()->after('rejected_at');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $columns = [
                'passport_number', 'city', 'travel_end_date', 'number_of_adults', 
                'number_of_children', 'accommodation_level', 'pickup_location', 
                'dropoff_location', 'payment_status', 'amount_paid', 'discount_amount',
                'discount_percentage', 'payment_receipt_path', 'assigned_staff_id',
                'booking_source', 'refund_amount', 'refund_status', 'attachments',
                'approval_status', 'approved_by', 'approved_at', 'rejected_by',
                'rejected_at', 'rejection_reason'
            ];
            
            foreach ($columns as $column) {
                if (Schema::hasColumn('bookings', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
