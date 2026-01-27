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
        if (!Schema::hasTable('audit_trails')) {
            Schema::create('audit_trails', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');
                $table->string('action'); // created, updated, deleted, viewed, etc.
                $table->string('model_type')->nullable(); // App\Models\Booking, etc.
                $table->unsignedBigInteger('model_id')->nullable();
                $table->string('model_name')->nullable(); // Human-readable name
                $table->text('description')->nullable();
                $table->json('old_values')->nullable(); // Previous values
                $table->json('new_values')->nullable(); // New values
                $table->json('changed_fields')->nullable(); // Fields that changed
                $table->string('ip_address', 45)->nullable();
                $table->text('user_agent')->nullable();
                $table->string('route')->nullable(); // Route name or URL
                $table->string('method')->nullable(); // GET, POST, PUT, DELETE
                $table->json('request_data')->nullable(); // Request payload (sanitized)
                $table->string('status')->default('success'); // success, failed, error
                $table->text('error_message')->nullable();
                $table->string('module')->nullable(); // bookings, tours, users, etc.
                $table->timestamps();
                
                // Indexes for better performance
                $table->index(['user_id', 'created_at']);
                $table->index(['model_type', 'model_id']);
                $table->index(['action', 'created_at']);
                $table->index('module');
                $table->index('created_at');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('audit_trails');
    }
};






