<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class EmailAttachment extends Model
{
    protected $fillable = [
        'email_message_id',
        'filename',
        'original_filename',
        'mime_type',
        'size',
        'file_path',
        'content_id',
        'is_inline',
    ];

    protected $casts = [
        'size' => 'integer',
        'is_inline' => 'boolean',
    ];

    /**
     * Get the email message
     */
    public function message(): BelongsTo
    {
        return $this->belongsTo(EmailMessage::class);
    }

    /**
     * Get file URL
     */
    public function getUrl(): string
    {
        return Storage::url($this->file_path);
    }

    /**
     * Get file size in human readable format
     */
    public function getSizeHumanAttribute(): string
    {
        $bytes = $this->size;
        $units = ['B', 'KB', 'MB', 'GB'];
        
        for ($i = 0; $bytes > 1024 && $i < count($units) - 1; $i++) {
            $bytes /= 1024;
        }
        
        return round($bytes, 2) . ' ' . $units[$i];
    }

    /**
     * Check if file exists
     */
    public function exists(): bool
    {
        return Storage::exists($this->file_path);
    }
}
