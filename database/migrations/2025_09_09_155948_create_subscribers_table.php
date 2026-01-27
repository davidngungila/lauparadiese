<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
public function up()
{
    Schema::create('subscribers', function (Blueprint $table) {
        $table->id();
        // This is the line that was likely missing
        $table->string('email')->unique();
        $table->timestamps();
    });
}

    /**p
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('subscribers');
    }
};
