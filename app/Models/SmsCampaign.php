<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SmsCampaign extends Model
{
    use HasFactory;

    protected $table = 'sms_campaigns';

    protected $fillable = [
        'name',
        'message',
        'recipient_type', // 'all', 'customers', 'subscribers', 'custom'
        'recipient_ids', // JSON array
        'status', // 'draft', 'scheduled', 'sending', 'sent', 'cancelled'
        'scheduled_at',
        'sent_at',
        'total_recipients',
        'sent_count',
        'delivered_count',
        'failed_count',
    ];

    protected $casts = [
        'recipient_ids' => 'array',
        'scheduled_at' => 'datetime',
        'sent_at' => 'datetime',
        'total_recipients' => 'integer',
        'sent_count' => 'integer',
        'delivered_count' => 'integer',
        'failed_count' => 'integer',
    ];

    public function getDeliveryRateAttribute()
    {
        if ($this->sent_count == 0) {
            return 0;
        }
        return ($this->delivered_count / $this->sent_count) * 100;
    }
}






