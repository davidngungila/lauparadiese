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
        Schema::table('users', function (Blueprint $table) {
            $table->string('avatar')->nullable()->after('email');
            $table->string('phone')->nullable()->after('avatar');
            $table->string('mobile')->nullable()->after('phone');
            $table->text('address')->nullable()->after('mobile');
            $table->string('city')->nullable()->after('address');
            $table->string('country')->nullable()->after('city');
            $table->date('date_of_birth')->nullable()->after('country');
            $table->text('bio')->nullable()->after('date_of_birth');
            $table->json('social_links')->nullable()->after('bio');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'avatar',
                'phone',
                'mobile',
                'address',
                'city',
                'country',
                'date_of_birth',
                'bio',
                'social_links',
            ]);
        });
    }
};
