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
        Schema::create('customer_groups', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->string('color')->nullable()->default('#3ea572'); // For UI display
            $table->boolean('is_active')->default(true);
            $table->integer('display_order')->default(0);
            $table->json('auto_grouping_rules')->nullable(); // For future auto-grouping
            $table->timestamps();
            $table->softDeletes();
            
            $table->index('is_active');
            $table->index('display_order');
        });
        
        // Pivot table for customer-group relationship
        Schema::create('customer_group_user', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_group_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->timestamp('joined_at')->useCurrent();
            $table->timestamps();
            
            $table->unique(['customer_group_id', 'user_id']);
            $table->index('user_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customer_group_user');
        Schema::dropIfExists('customer_groups');
    }
};
