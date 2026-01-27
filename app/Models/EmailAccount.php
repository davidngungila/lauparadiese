<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Crypt;

class EmailAccount extends Model
{
    protected $fillable = [
        'name',
        'email',
        'protocol',
        'imap_host',
        'imap_port',
        'imap_encryption',
        'pop3_host',
        'pop3_port',
        'pop3_encryption',
        'smtp_host',
        'smtp_port',
        'smtp_encryption',
        'username',
        'password',
        'is_active',
        'is_default',
        'check_interval',
        'last_checked_at',
        'messages_count',
        'notes',
        'user_id',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'is_default' => 'boolean',
        'imap_port' => 'integer',
        'pop3_port' => 'integer',
        'smtp_port' => 'integer',
        'check_interval' => 'integer',
        'messages_count' => 'integer',
        'last_checked_at' => 'datetime',
    ];

    protected $hidden = [
        'password',
    ];

    /**
     * Get the user that owns the email account
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get all messages for this account
     */
    public function messages(): HasMany
    {
        return $this->hasMany(EmailMessage::class);
    }

    /**
     * Get password (decrypted)
     */
    public function getPasswordAttribute($value)
    {
        return $value ? Crypt::decryptString($value) : null;
    }

    /**
     * Set password (encrypted)
     */
    public function setPasswordAttribute($value)
    {
        $this->attributes['password'] = $value ? Crypt::encryptString($value) : null;
    }

    /**
     * Get IMAP connection string
     */
    public function getImapConnectionString(): string
    {
        $encryption = $this->imap_encryption === 'ssl' ? '/ssl' : ($this->imap_encryption === 'tls' ? '/tls' : '');
        return "{{$this->imap_host}:{$this->imap_port}/imap{$encryption}}";
    }

    /**
     * Get SMTP connection array
     */
    public function getSmtpConfig(): array
    {
        return [
            'host' => $this->smtp_host,
            'port' => $this->smtp_port,
            'encryption' => $this->smtp_encryption,
            'username' => $this->username,
            'password' => $this->password,
        ];
    }
}
