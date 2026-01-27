<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Testimonial extends Model
{
    use HasFactory;

    protected $table = 'testimonials';

    protected $fillable = [
        'author_name',
        'author_title',
        'author_image_url',
        'content',
        'rating',
        'tour_id',
        'is_approved',
        'is_featured',
        'display_order',
    ];

    protected $casts = [
        'rating' => 'integer',
        'is_approved' => 'boolean',
        'is_featured' => 'boolean',
        'display_order' => 'integer',
    ];

    public function tour()
    {
        return $this->belongsTo(Tour::class);
    }
}

