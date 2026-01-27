<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('payment_gateways')) {
            Schema::table('payment_gateways', function (Blueprint $table) {
                if (!Schema::hasColumn('payment_gateways', 'is_primary')) {
                    $table->boolean('is_primary')->default(false)->after('is_test_mode');
                }
                if (!Schema::hasColumn('payment_gateways', 'priority')) {
                    $table->integer('priority')->default(0)->after('is_primary');
                }
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('payment_gateways')) {
            Schema::table('payment_gateways', function (Blueprint $table) {
                if (Schema::hasColumn('payment_gateways', 'is_primary')) {
                    $table->dropColumn('is_primary');
                }
                if (Schema::hasColumn('payment_gateways', 'priority')) {
                    $table->dropColumn('priority');
                }
            });
        }
    }
};






