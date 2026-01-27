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
        Schema::create('email_messages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('email_account_id')->constrained()->onDelete('cascade');
            $table->string('message_id')->unique()->comment('Email Message-ID header');
            $table->string('uid')->nullable()->comment('IMAP/POP3 UID');
            $table->string('subject');
            $table->text('from_email');
            $table->string('from_name')->nullable();
            $table->text('to')->nullable()->comment('JSON array of recipients');
            $table->text('cc')->nullable()->comment('JSON array');
            $table->text('bcc')->nullable()->comment('JSON array');
            $table->text('reply_to')->nullable();
            $table->longText('body_text')->nullable();
            $table->longText('body_html')->nullable();
            $table->enum('status', ['unread', 'read', 'archived', 'deleted', 'spam'])->default('unread');
            $table->enum('type', ['inbox', 'sent', 'draft', 'trash', 'spam'])->default('inbox');
            $table->boolean('is_important')->default(false);
            $table->boolean('is_starred')->default(false);
            $table->boolean('has_attachments')->default(false);
            $table->timestamp('received_at');
            $table->timestamp('read_at')->nullable();
            $table->foreignId('assigned_to')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('replied_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('replied_at')->nullable();
            $table->text('tags')->nullable()->comment('JSON array of tags');
            $table->timestamps();
            
            $table->index(['email_account_id', 'type', 'status']);
            $table->index(['received_at']);
            $table->index(['status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('email_messages');
    }
};
