<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('destinations', function (Blueprint $table) {
            if (!Schema::hasColumn('destinations', 'name')) {
                $table->string('name')->after('id');
            }
            if (!Schema::hasColumn('destinations', 'slug')) {
                $table->string('slug')->unique()->nullable()->after('name');
            }
            if (!Schema::hasColumn('destinations', 'description')) {
                $table->text('description')->nullable()->after('slug');
            }
            if (!Schema::hasColumn('destinations', 'image_url')) {
                $table->string('image_url')->nullable()->after('description');
            }
        });
    }

    public function down(): void
    {
        Schema::table('destinations', function (Blueprint $table) {
            if (Schema::hasColumn('destinations', 'image_url')) {
                $table->dropColumn('image_url');
            }
            if (Schema::hasColumn('destinations', 'description')) {
                $table->dropColumn('description');
            }
            if (Schema::hasColumn('destinations', 'slug')) {
                $table->dropColumn('slug');
            }
            if (Schema::hasColumn('destinations', 'name')) {
                $table->dropColumn('name');
            }
        });
    }
};
