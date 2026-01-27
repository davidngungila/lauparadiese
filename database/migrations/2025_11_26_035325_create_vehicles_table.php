<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('vehicles', function (Blueprint $table) {
            $table->id();
            $table->string('vehicle_type'); // car, bus, van, 4x4
            $table->string('make');
            $table->string('model');
            $table->string('year');
            $table->string('license_plate')->unique();
            $table->string('color')->nullable();
            $table->integer('capacity');
            $table->string('fuel_type')->nullable(); // petrol, diesel, electric
            $table->foreignId('driver_id')->nullable()->constrained('users')->onDelete('set null');
            $table->string('status')->default('available'); // available, in_use, maintenance, retired
            $table->date('last_maintenance')->nullable();
            $table->date('next_maintenance')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('vehicles');
    }
};
