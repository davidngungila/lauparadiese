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
        Schema::create('customer_feedback', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('booking_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('tour_id')->nullable()->constrained()->onDelete('set null');
            
            // Feedback Type
            $table->enum('feedback_type', [
                'tour_package',
                'driver_guide',
                'hotel',
                'general_company',
                'transport',
                'other'
            ])->default('tour_package');
            
            // Rating
            $table->integer('rating')->default(5); // 1-5 stars
            $table->string('title')->nullable();
            $table->text('message');
            
            // Additional Details
            $table->string('tour_name')->nullable();
            $table->string('driver_name')->nullable();
            $table->string('guide_name')->nullable();
            $table->string('hotel_name')->nullable();
            
            // Attachments
            $table->json('attachments')->nullable(); // Array of file paths
            
            // Staff Response
            $table->text('staff_response')->nullable();
            $table->foreignId('responded_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('responded_at')->nullable();
            
            // Status
            $table->enum('status', ['pending', 'approved', 'rejected', 'resolved'])->default('pending');
            $table->boolean('is_public')->default(false); // Show on website
            $table->boolean('is_featured')->default(false);
            
            // Flags
            $table->boolean('is_serious_complaint')->default(false);
            $table->text('admin_notes')->nullable();
            
            $table->timestamps();
            $table->softDeletes();
            
            $table->index(['customer_id', 'status']);
            $table->index(['feedback_type', 'status']);
            $table->index('rating');
            $table->index('is_public');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customer_feedback');
    }
};
