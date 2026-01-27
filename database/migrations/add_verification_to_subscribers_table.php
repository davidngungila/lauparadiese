<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('subscribers', function (Blueprint $table) {
            $table->timestamp('verified_at')->nullable()->after('email');
            $table->string('verification_token', 60)->nullable()->unique()->after('verified_at');
        });
    }

    public function down()
    {
        Schema::table('subscribers', function (Blueprint $table) {
            $table->dropColumn(['verified_at', 'verification_token']);
        });
    }
};
