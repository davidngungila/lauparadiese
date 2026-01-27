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
        Schema::create('vehicle_maintenances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vehicle_id')->constrained('vehicles')->onDelete('cascade');
            $table->string('service_type'); // Regular Service, Repair, Inspection, etc.
            $table->date('service_date');
            $table->date('next_service_date')->nullable();
            $table->integer('odometer_reading')->nullable();
            $table->decimal('cost', 10, 2)->default(0);
            $table->text('service_notes')->nullable();
            $table->json('parts_replaced')->nullable(); // Array of parts
            $table->string('service_provider')->nullable();
            $table->foreignId('performed_by')->nullable()->constrained('users')->onDelete('set null');
            $table->json('attachments')->nullable(); // Receipts, invoices, etc.
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vehicle_maintenances');
    }
};
