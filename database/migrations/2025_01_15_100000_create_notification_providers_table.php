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
        Schema::create('notification_providers', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->enum('type', ['sms', 'email'])->default('sms');
            $table->boolean('is_primary')->default(false);
            $table->boolean('is_active')->default(true);
            
            // SMS Provider Fields
            $table->string('sms_username')->nullable();
            $table->string('sms_password')->nullable();
            $table->string('sms_from')->nullable();
            $table->text('sms_url')->nullable();
            $table->enum('sms_method', ['get', 'post'])->default('post');
            
            // Email Provider Fields
            $table->string('mailer_type')->nullable(); // smtp, sendmail, etc.
            $table->string('mail_host')->nullable();
            $table->integer('mail_port')->nullable();
            $table->string('mail_username')->nullable();
            $table->string('mail_password')->nullable();
            $table->string('mail_encryption')->nullable(); // tls, ssl
            $table->string('mail_from_address')->nullable();
            $table->string('mail_from_name')->nullable();
            
            // Status and Metadata
            $table->enum('connection_status', ['connected', 'disconnected', 'unknown'])->default('unknown');
            $table->timestamp('last_tested_at')->nullable();
            $table->text('last_test_result')->nullable();
            $table->text('notes')->nullable();
            $table->integer('priority')->default(0); // Lower number = higher priority
            $table->json('metadata')->nullable(); // Additional provider-specific settings
            
            $table->timestamps();
            
            // Indexes
            $table->index(['type', 'is_primary']);
            $table->index(['type', 'is_active']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notification_providers');
    }
};






