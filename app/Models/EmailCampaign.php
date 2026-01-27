<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EmailCampaign extends Model
{
    use HasFactory;

    protected $table = 'email_campaigns';

    protected $fillable = [
        'name',
        'subject',
        'content',
        'recipient_type', // 'all', 'customers', 'subscribers', 'custom'
        'recipient_ids', // JSON array
        'status', // 'draft', 'scheduled', 'sending', 'sent', 'cancelled'
        'scheduled_at',
        'sent_at',
        'total_recipients',
        'sent_count',
        'opened_count',
        'clicked_count',
        'bounced_count',
    ];

    protected $casts = [
        'recipient_ids' => 'array',
        'scheduled_at' => 'datetime',
        'sent_at' => 'datetime',
        'total_recipients' => 'integer',
        'sent_count' => 'integer',
        'opened_count' => 'integer',
        'clicked_count' => 'integer',
        'bounced_count' => 'integer',
    ];

    public function getOpenRateAttribute()
    {
        if ($this->sent_count == 0) {
            return 0;
        }
        return ($this->opened_count / $this->sent_count) * 100;
    }

    public function getClickRateAttribute()
    {
        if ($this->sent_count == 0) {
            return 0;
        }
        return ($this->clicked_count / $this->sent_count) * 100;
    }
}

