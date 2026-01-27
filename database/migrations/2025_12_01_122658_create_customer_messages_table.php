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
        Schema::create('customer_messages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('assigned_staff_id')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('booking_id')->nullable()->constrained()->onDelete('set null');
            
            // Message Details
            $table->string('subject')->nullable();
            $table->text('message');
            $table->enum('message_type', ['inquiry', 'support', 'booking', 'complaint', 'general'])->default('general');
            $table->enum('priority', ['low', 'normal', 'high', 'urgent'])->default('normal');
            
            // Status
            $table->enum('status', ['new', 'open', 'in_progress', 'waiting_customer', 'resolved', 'closed'])->default('new');
            $table->boolean('is_important')->default(false);
            $table->boolean('is_read')->default(false);
            $table->timestamp('read_at')->nullable();
            
            // Channel
            $table->enum('channel', ['email', 'sms', 'whatsapp', 'website', 'phone', 'in_person'])->default('website');
            $table->string('external_id')->nullable(); // For WhatsApp/Email sync
            
            // Attachments
            $table->json('attachments')->nullable(); // Array of file paths
            
            // Threading (for conversation)
            $table->foreignId('parent_message_id')->nullable()->constrained('customer_messages')->onDelete('cascade');
            $table->integer('thread_depth')->default(0);
            
            // Metadata
            $table->string('ip_address')->nullable();
            $table->text('user_agent')->nullable();
            
            $table->timestamps();
            $table->softDeletes();
            
            $table->index(['customer_id', 'status']);
            $table->index(['assigned_staff_id', 'status']);
            $table->index(['status', 'priority']);
            $table->index('is_read');
            $table->index('parent_message_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customer_messages');
    }
};
