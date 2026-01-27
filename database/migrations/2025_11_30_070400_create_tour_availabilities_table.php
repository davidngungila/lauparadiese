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
        Schema::create('tour_availabilities', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tour_id')->constrained()->onDelete('cascade');
            $table->date('date')->nullable();
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->integer('available_slots')->default(0);
            $table->enum('status', ['Available', 'Sold Out', 'On Request'])->default('Available');
            $table->decimal('price_override', 10, 2)->nullable();
            $table->text('notes')->nullable();
            $table->boolean('is_repeating')->default(false);
            $table->string('repeat_pattern')->nullable(); // daily, weekly, monthly, custom
            $table->json('repeat_days')->nullable(); // [1,3,5] for specific days
            $table->date('repeat_until')->nullable();
            $table->timestamps();
            $table->softDeletes();
            
            $table->index(['tour_id', 'date']);
            $table->index(['tour_id', 'start_date', 'end_date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tour_availabilities');
    }
};
