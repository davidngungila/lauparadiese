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
        Schema::table('reviews', function (Blueprint $table) {
            if (!Schema::hasColumn('reviews', 'tour_id')) {
                $table->foreignId('tour_id')
                    ->after('id')
                    ->constrained('tours')
                    ->onDelete('cascade');
            }

            if (!Schema::hasColumn('reviews', 'author_name')) {
                $table->string('author_name')->nullable()->after('tour_id');
            }

            if (!Schema::hasColumn('reviews', 'rating')) {
                $table->unsignedTinyInteger('rating')->nullable()->after('author_name');
            }

            if (!Schema::hasColumn('reviews', 'comment')) {
                $table->text('comment')->nullable()->after('rating');
            }

            if (!Schema::hasColumn('reviews', 'is_approved')) {
                $table->boolean('is_approved')->default(false)->after('comment');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('reviews', function (Blueprint $table) {
            if (Schema::hasColumn('reviews', 'tour_id')) {
                $table->dropForeign(['tour_id']);
                $table->dropColumn('tour_id');
            }

            foreach (['author_name', 'rating', 'comment', 'is_approved'] as $column) {
                if (Schema::hasColumn('reviews', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
