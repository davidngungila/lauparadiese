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
        if (Schema::hasTable('hotels')) {
            Schema::table('hotels', function (Blueprint $table) {
                if (Schema::hasColumn('hotels', 'address')) {
                    $table->string('address')->nullable()->change();
                }
                if (Schema::hasColumn('hotels', 'city')) {
                    $table->string('city')->nullable()->change();
                }
                if (Schema::hasColumn('hotels', 'country')) {
                    $table->string('country')->nullable()->change();
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('hotels')) {
            Schema::table('hotels', function (Blueprint $table) {
                if (Schema::hasColumn('hotels', 'address')) {
                    $table->string('address')->nullable(false)->change();
                }
                if (Schema::hasColumn('hotels', 'city')) {
                    $table->string('city')->nullable(false)->change();
                }
                if (Schema::hasColumn('hotels', 'country')) {
                    $table->string('country')->nullable(false)->change();
                }
            });
        }
    }
};

