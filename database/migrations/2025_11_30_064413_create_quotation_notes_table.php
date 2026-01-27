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
        Schema::create('quotation_notes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('quotation_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');
            $table->enum('type', ['admin_note', 'customer_reply', 'system', 'email_sent', 'whatsapp_sent'])->default('admin_note');
            $table->text('note');
            $table->json('metadata')->nullable(); // For storing additional data like email subject, WhatsApp number, etc.
            $table->timestamps();
            
            $table->index('quotation_id');
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('quotation_notes');
    }
};
