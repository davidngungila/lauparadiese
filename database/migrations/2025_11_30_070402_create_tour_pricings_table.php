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
        Schema::create('tour_pricings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tour_id')->constrained()->onDelete('cascade');
            $table->string('currency', 3)->default('USD');
            $table->enum('price_type', ['Per Person', 'Per Group', 'Per Category', 'Seasonal'])->default('Per Person');
            $table->enum('category_type', ['Resident', 'Non-Resident', 'Adult', 'Child', 'Senior'])->nullable();
            $table->decimal('price', 10, 2);
            $table->decimal('child_price', 10, 2)->nullable();
            $table->integer('min_pax')->default(1);
            $table->integer('max_pax')->nullable();
            $table->date('valid_from')->nullable();
            $table->date('valid_to')->nullable();
            $table->json('optional_addons')->nullable(); // [{"name": "Balloon Safari", "price": 500}]
            $table->decimal('discount_percentage', 5, 2)->nullable();
            $table->decimal('final_price', 10, 2)->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();
            
            $table->index(['tour_id', 'price_type', 'is_active']);
            $table->index(['tour_id', 'valid_from', 'valid_to']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tour_pricings');
    }
};
