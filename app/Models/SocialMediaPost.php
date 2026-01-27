<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SocialMediaPost extends Model
{
    use HasFactory;

    protected $table = 'social_media_posts';

    protected $fillable = [
        'platform', // 'facebook', 'twitter', 'instagram', 'linkedin'
        'content',
        'media_url',
        'status', // 'draft', 'scheduled', 'published'
        'scheduled_at',
        'published_at',
        'likes',
        'shares',
        'comments',
        'views',
    ];

    protected $casts = [
        'scheduled_at' => 'datetime',
        'published_at' => 'datetime',
        'likes' => 'integer',
        'shares' => 'integer',
        'comments' => 'integer',
        'views' => 'integer',
    ];

    public function getEngagementRateAttribute()
    {
        if ($this->views == 0) {
            return 0;
        }
        return (($this->likes + $this->shares + $this->comments) / $this->views) * 100;
    }
}






