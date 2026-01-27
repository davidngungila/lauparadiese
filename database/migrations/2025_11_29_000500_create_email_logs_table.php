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
        if (!Schema::hasTable('email_logs')) {
            Schema::create('email_logs', function (Blueprint $table) {
                $table->id();
                $table->string('to');
                $table->string('subject')->nullable();
                $table->longText('body')->nullable();
                $table->string('status')->default('sent'); // sent, failed, queued
                $table->text('error_message')->nullable();
                $table->json('meta')->nullable(); // additional context (mailer, driver, etc.)
                $table->timestamp('sent_at')->nullable();
                $table->timestamps();

                $table->index(['to', 'status']);
                $table->index('created_at');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('email_logs');
    }
};






