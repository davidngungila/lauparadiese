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
        Schema::table('posts', function (Blueprint $table) {
            // Add foreign key columns
            if (!Schema::hasColumn('posts', 'category_id')) {
                $table->unsignedBigInteger('category_id')->nullable()->after('id');
                $table->foreign('category_id')->references('id')->on('categories')->onDelete('set null');
            }
            
            if (!Schema::hasColumn('posts', 'user_id')) {
                $table->unsignedBigInteger('user_id')->nullable()->after('category_id');
                $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
            }
            
            // Add post content columns
            if (!Schema::hasColumn('posts', 'title')) {
                $table->string('title')->after('user_id');
            }
            
            if (!Schema::hasColumn('posts', 'slug')) {
                $table->string('slug')->unique()->after('title');
            }
            
            if (!Schema::hasColumn('posts', 'excerpt')) {
                $table->text('excerpt')->nullable()->after('slug');
            }
            
            if (!Schema::hasColumn('posts', 'body')) {
                $table->longText('body')->nullable()->after('excerpt');
            }
            
            if (!Schema::hasColumn('posts', 'image_url')) {
                $table->string('image_url')->nullable()->after('body');
            }
            
            if (!Schema::hasColumn('posts', 'published_at')) {
                $table->timestamp('published_at')->nullable()->after('image_url');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('posts', function (Blueprint $table) {
            // Drop foreign keys first
            if (Schema::hasColumn('posts', 'category_id')) {
                $table->dropForeign(['category_id']);
                $table->dropColumn('category_id');
            }
            
            if (Schema::hasColumn('posts', 'user_id')) {
                $table->dropForeign(['user_id']);
                $table->dropColumn('user_id');
            }
            
            // Drop other columns
            $table->dropColumn([
                'title',
                'slug',
                'excerpt',
                'body',
                'image_url',
                'published_at',
            ]);
        });
    }
};
