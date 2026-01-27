<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tours', function (Blueprint $table) {
            if (!Schema::hasColumn('tours', 'name')) {
                $table->string('name')->after('id');
            }
            if (!Schema::hasColumn('tours', 'slug')) {
                $table->string('slug')->unique()->nullable()->after('name');
            }
            if (!Schema::hasColumn('tours', 'destination_id')) {
                $table->foreignId('destination_id')->nullable()->after('slug')->constrained()->onDelete('set null');
            }
            if (!Schema::hasColumn('tours', 'description')) {
                $table->text('description')->nullable()->after('destination_id');
            }
            if (!Schema::hasColumn('tours', 'excerpt')) {
                $table->text('excerpt')->nullable()->after('description');
            }
            if (!Schema::hasColumn('tours', 'duration_days')) {
                $table->integer('duration_days')->nullable()->after('excerpt');
            }
            if (!Schema::hasColumn('tours', 'price')) {
                $table->decimal('price', 10, 2)->nullable()->after('duration_days');
            }
            if (!Schema::hasColumn('tours', 'rating')) {
                $table->decimal('rating', 3, 2)->nullable()->after('price');
            }
            if (!Schema::hasColumn('tours', 'fitness_level')) {
                $table->string('fitness_level')->nullable()->after('rating');
            }
            if (!Schema::hasColumn('tours', 'image_url')) {
                $table->string('image_url')->nullable()->after('fitness_level');
            }
        });
    }

    public function down(): void
    {
        Schema::table('tours', function (Blueprint $table) {
            $columns = ['image_url', 'fitness_level', 'rating', 'price', 'duration_days', 
                       'excerpt', 'description', 'destination_id', 'slug', 'name'];
            foreach ($columns as $column) {
                if (Schema::hasColumn('tours', $column)) {
                    if ($column === 'destination_id') {
                        $table->dropForeign(['destination_id']);
                    }
                    $table->dropColumn($column);
                }
            }
        });
    }
};
