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
        Schema::create('driver_profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->unique()->constrained('users')->onDelete('cascade');
            $table->string('driver_code')->unique()->nullable(); // Auto-generated DRV-0001
            $table->string('photo')->nullable();
            $table->string('phone')->nullable();
            $table->string('national_id')->nullable();
            $table->string('passport_number')->nullable();
            $table->string('driving_license_number')->nullable();
            $table->date('license_expiry_date')->nullable();
            $table->json('languages_spoken')->nullable(); // Array of languages
            $table->string('experience_level')->nullable(); // Beginner, Intermediate, Advanced, Expert
            $table->json('special_skills')->nullable(); // Mechanic, Guide, Wildlife expert, etc.
            $table->json('documents')->nullable(); // Uploaded documents
            $table->string('status')->default('active'); // active, inactive
            $table->decimal('rating', 3, 2)->nullable()->default(0); // Optional ratings
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('driver_profiles');
    }
};
