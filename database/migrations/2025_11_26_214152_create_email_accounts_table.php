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
        Schema::create('email_accounts', function (Blueprint $table) {
            $table->id();
            $table->string('name')->comment('Account name/label');
            $table->string('email')->unique();
            $table->enum('protocol', ['imap', 'pop3'])->default('imap');
            $table->string('imap_host')->nullable();
            $table->integer('imap_port')->default(993);
            $table->string('imap_encryption')->default('ssl')->comment('ssl, tls, none');
            $table->string('pop3_host')->nullable();
            $table->integer('pop3_port')->default(995);
            $table->string('pop3_encryption')->default('ssl');
            $table->string('smtp_host')->nullable();
            $table->integer('smtp_port')->default(587);
            $table->string('smtp_encryption')->default('tls');
            $table->string('username');
            $table->text('password')->comment('Encrypted password');
            $table->boolean('is_active')->default(true);
            $table->boolean('is_default')->default(false);
            $table->integer('check_interval')->default(5)->comment('Minutes between checks');
            $table->timestamp('last_checked_at')->nullable();
            $table->integer('messages_count')->default(0);
            $table->text('notes')->nullable();
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('email_accounts');
    }
};
