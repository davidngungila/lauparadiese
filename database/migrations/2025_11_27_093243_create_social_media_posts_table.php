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
        Schema::create('social_media_posts', function (Blueprint $table) {
            $table->id();
            $table->enum('platform', ['facebook', 'twitter', 'instagram', 'linkedin']);
            $table->text('content');
            $table->string('media_url')->nullable();
            $table->enum('status', ['draft', 'scheduled', 'published'])->default('draft');
            $table->datetime('scheduled_at')->nullable();
            $table->datetime('published_at')->nullable();
            $table->integer('likes')->default(0);
            $table->integer('shares')->default(0);
            $table->integer('comments')->default(0);
            $table->integer('views')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('social_media_posts');
    }
};






