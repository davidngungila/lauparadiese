<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AboutPageTeamMember extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'role',
        'bio',
        'image_url',
        'expertise',
        'social_links',
        'display_order',
        'is_active',
    ];

    protected $casts = [
        'expertise' => 'array',
        'social_links' => 'array',
        'is_active' => 'boolean',
        'display_order' => 'integer',
    ];
}
