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
            // Personal Information
            if (!Schema::hasColumn('users', 'first_name')) {
                $table->string('first_name')->nullable()->after('name');
            }
            if (!Schema::hasColumn('users', 'middle_name')) {
                $table->string('middle_name')->nullable()->after('first_name');
            }
            if (!Schema::hasColumn('users', 'last_name')) {
                $table->string('last_name')->nullable()->after('middle_name');
            }
            if (!Schema::hasColumn('users', 'gender')) {
                $table->enum('gender', ['male', 'female', 'other'])->nullable()->after('last_name');
            }
            if (!Schema::hasColumn('users', 'nationality')) {
                $table->string('nationality')->nullable()->after('country');
            }
            
            // Passport Information
            if (!Schema::hasColumn('users', 'passport_number')) {
                $table->string('passport_number')->nullable()->after('nationality');
            }
            if (!Schema::hasColumn('users', 'passport_expiry')) {
                $table->date('passport_expiry')->nullable()->after('passport_number');
            }
            
            // Contact Information
            if (!Schema::hasColumn('users', 'whatsapp_number')) {
                $table->string('whatsapp_number')->nullable()->after('mobile');
            }
            if (!Schema::hasColumn('users', 'emergency_contact_name')) {
                $table->string('emergency_contact_name')->nullable()->after('whatsapp_number');
            }
            if (!Schema::hasColumn('users', 'emergency_contact_phone')) {
                $table->string('emergency_contact_phone')->nullable()->after('emergency_contact_name');
            }
            if (!Schema::hasColumn('users', 'emergency_contact_relationship')) {
                $table->string('emergency_contact_relationship')->nullable()->after('emergency_contact_phone');
            }
            
            // Travel Preferences
            if (!Schema::hasColumn('users', 'preferred_destination')) {
                $table->string('preferred_destination')->nullable()->after('emergency_contact_relationship');
            }
            if (!Schema::hasColumn('users', 'preferred_tour_type')) {
                $table->string('preferred_tour_type')->nullable()->after('preferred_destination');
            }
            if (!Schema::hasColumn('users', 'preferred_budget')) {
                $table->decimal('preferred_budget', 10, 2)->nullable()->after('preferred_tour_type');
            }
            if (!Schema::hasColumn('users', 'special_needs')) {
                $table->text('special_needs')->nullable()->after('preferred_budget');
            }
            
            // Customer Management
            if (!Schema::hasColumn('users', 'customer_status')) {
                $table->enum('customer_status', ['active', 'inactive', 'suspended'])->default('active')->after('special_needs');
            }
            if (!Schema::hasColumn('users', 'assigned_consultant_id')) {
                $table->foreignId('assigned_consultant_id')->nullable()->constrained('users')->onDelete('set null')->after('customer_status');
            }
            if (!Schema::hasColumn('users', 'internal_notes')) {
                $table->text('internal_notes')->nullable()->after('assigned_consultant_id');
            }
            
            // Indexes
            $table->index('customer_status');
            $table->index('assigned_consultant_id');
            $table->index('passport_number');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $columns = [
                'first_name', 'middle_name', 'last_name', 'gender', 'nationality',
                'passport_number', 'passport_expiry', 'whatsapp_number',
                'emergency_contact_name', 'emergency_contact_phone', 'emergency_contact_relationship',
                'preferred_destination', 'preferred_tour_type', 'preferred_budget', 'special_needs',
                'customer_status', 'assigned_consultant_id', 'internal_notes'
            ];
            
            foreach ($columns as $column) {
                if (Schema::hasColumn('users', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
