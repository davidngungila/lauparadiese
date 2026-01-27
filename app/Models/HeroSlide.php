<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Services\ImageService;

class HeroSlide extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'hero_slides';

    protected $fillable = [
        'title',
        'subtitle',
        'badge_text',
        'badge_icon',
        'image_id',
        'image_url',
        'primary_button_text',
        'primary_button_link',
        'primary_button_icon',
        'secondary_button_text',
        'secondary_button_link',
        'secondary_button_icon',
        'display_order',
        'animation_type',
        'is_active',
        'overlay_type',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'display_order' => 'integer',
    ];

    /**
     * Get the gallery image
     */
    public function image()
    {
        return $this->belongsTo(Gallery::class, 'image_id');
    }

    /**
     * Get the display image URL (accessor)
     */
    public function getDisplayImageUrlAttribute()
    {
        // If image_id is set, use gallery image
        if ($this->image_id && $this->image) {
            return $this->image->display_url;
        }
        
        // Otherwise use direct image_url
        $rawUrl = $this->getAttributes()['image_url'] ?? null;
        if ($rawUrl) {
            // If it's a full URL, return as is
            if (str_starts_with($rawUrl, 'http://') || str_starts_with($rawUrl, 'https://')) {
                return $rawUrl;
            }
            
            // If it starts with images/, use asset() helper
            if (str_starts_with($rawUrl, 'images/')) {
                return asset($rawUrl);
            }
            
            // Otherwise, assume it's in images/hero-slider/ folder
            return asset('images/hero-slider/' . $rawUrl);
        }
        
        return null;
    }

    /**
     * Scope for active slides
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope for ordered slides
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('display_order', 'asc');
    }
}





