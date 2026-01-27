<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LandingPage extends Model
{
    use HasFactory;

    protected $table = 'landing_pages';

    protected $fillable = [
        'title',
        'slug',
        'content',
        'meta_title',
        'meta_description',
        'status', // 'draft', 'published'
        'views',
        'conversions',
    ];

    protected $casts = [
        'views' => 'integer',
        'conversions' => 'integer',
    ];
}






