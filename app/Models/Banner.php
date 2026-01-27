<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Banner extends Model
{
    use HasFactory;

    protected $table = 'banners';

    protected $fillable = [
        'title',
        'description',
        'image_url',
        'link_url',
        'position', // 'header', 'sidebar', 'footer', 'popup'
        'type', // 'banner', 'popup'
        'is_active',
        'start_date',
        'end_date',
        'display_order',
        'target_audience', // 'all', 'logged_in', 'guests'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'start_date' => 'datetime',
        'end_date' => 'datetime',
        'display_order' => 'integer',
    ];
}

