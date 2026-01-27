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
        Schema::table('quotations', function (Blueprint $table) {
            // Enhanced customer information
            $table->string('customer_country')->nullable()->after('customer_address');
            $table->string('customer_city')->nullable()->after('customer_country');
            
            // Enhanced travel information
            $table->date('end_date')->nullable()->after('departure_date');
            $table->integer('adults')->default(0)->after('travelers');
            $table->integer('children')->default(0)->after('adults');
            $table->string('accommodation_type')->nullable()->after('children'); // budget, mid-range, luxury
            $table->boolean('airport_pickup')->default(false)->after('accommodation_type');
            $table->text('special_requests')->nullable()->after('airport_pickup');
            
            // Cost breakdown fields
            $table->string('currency', 3)->default('USD')->after('total_price');
            $table->decimal('accommodation_cost', 10, 2)->default(0)->after('currency');
            $table->decimal('transport_cost', 10, 2)->default(0)->after('accommodation_cost');
            $table->decimal('park_fees', 10, 2)->default(0)->after('transport_cost');
            $table->decimal('guide_fees', 10, 2)->default(0)->after('park_fees');
            $table->decimal('meals_cost', 10, 2)->default(0)->after('guide_fees');
            $table->decimal('activities_cost', 10, 2)->default(0)->after('meals_cost');
            $table->decimal('service_charges', 10, 2)->default(0)->after('activities_cost');
            $table->decimal('discount_percentage', 5, 2)->default(0)->after('discount');
            
            // Enhanced status
            $table->string('status')->default('pending')->change(); // pending, under_review, sent, approved, rejected, closed
            
            // Communication and notes
            $table->text('admin_notes')->nullable()->after('notes');
            $table->timestamp('sent_at')->nullable()->after('status');
            $table->timestamp('approved_at')->nullable()->after('sent_at');
            $table->timestamp('rejected_at')->nullable()->after('approved_at');
            
            // Attachments
            $table->string('itinerary_file')->nullable()->after('rejected_at');
            $table->json('attachment_files')->nullable()->after('itinerary_file');
            
            // Agent information
            $table->foreignId('agent_id')->nullable()->constrained('users')->onDelete('set null')->after('created_by');
            
            // Indexes for performance
            $table->index('status');
            $table->index('created_at');
            $table->index('departure_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('quotations', function (Blueprint $table) {
            $table->dropColumn([
                'customer_country',
                'customer_city',
                'end_date',
                'adults',
                'children',
                'accommodation_type',
                'airport_pickup',
                'special_requests',
                'currency',
                'accommodation_cost',
                'transport_cost',
                'park_fees',
                'guide_fees',
                'meals_cost',
                'activities_cost',
                'service_charges',
                'discount_percentage',
                'admin_notes',
                'sent_at',
                'approved_at',
                'rejected_at',
                'itinerary_file',
                'attachment_files',
                'agent_id',
            ]);
            
            $table->dropIndex(['status']);
            $table->dropIndex(['created_at']);
            $table->dropIndex(['departure_date']);
        });
    }
};
