<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;
use App\Services\ImageService;

class HomepageDestination extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'short_description',
        'full_description',
        'featured_image_id',
        'featured_image_url',
        'og_image_id',
        'og_image_url',
        'gallery_image_ids',
        'image_gallery',
        'country',
        'region',
        'city',
        'category',
        'price',
        'price_display',
        'duration',
        'rating',
        'is_active',
        'is_featured',
        'slug',
        'meta_title',
        'meta_description',
        'meta_keywords',
        'display_order',
    ];

    protected $casts = [
        'gallery_image_ids' => 'array',
        'image_gallery' => 'array',
        'price' => 'decimal:2',
        'rating' => 'decimal:2',
        'is_active' => 'boolean',
        'is_featured' => 'boolean',
        'display_order' => 'integer',
    ];

    /**
     * Get the featured image from gallery
     */
    public function featuredImage(): BelongsTo
    {
        return $this->belongsTo(Gallery::class, 'featured_image_id');
    }

    /**
     * Get the OG image from gallery
     */
    public function ogImage(): BelongsTo
    {
        return $this->belongsTo(Gallery::class, 'og_image_id');
    }

    /**
     * Get gallery images
     */
    public function galleryImages()
    {
        if (!$this->gallery_image_ids) {
            return collect([]);
        }
        return Gallery::whereIn('id', $this->gallery_image_ids)->get();
    }

    /**
     * Get featured image URL for display (from gallery or direct URL)
     */
    public function getFeaturedImageDisplayUrlAttribute()
    {
        if ($this->featured_image_id && $this->featuredImage) {
            return $this->featuredImage->display_url;
        }
        $rawUrl = $this->getAttributes()['featured_image_url'] ?? null;
        if ($rawUrl) {
            $imageService = new ImageService();
            return $imageService->getUrl($rawUrl);
        }
        return null;
    }

    /**
     * Get OG image URL for display (from gallery or direct URL)
     */
    public function getOgImageDisplayUrlAttribute()
    {
        if ($this->og_image_id && $this->ogImage) {
            return $this->ogImage->display_url;
        }
        $rawUrl = $this->getAttributes()['og_image_url'] ?? null;
        if ($rawUrl) {
            $imageService = new ImageService();
            return $imageService->getUrl($rawUrl);
        }
        return null;
    }

    /**
     * Boot the model
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($destination) {
            if (empty($destination->slug)) {
                $destination->slug = Str::slug($destination->name);
            }
        });

        static::updating(function ($destination) {
            if ($destination->isDirty('name') && empty($destination->slug)) {
                $destination->slug = Str::slug($destination->name);
            }
        });
    }

    /**
     * Get the location as a formatted string
     */
    public function getLocationAttribute(): string
    {
        $parts = array_filter([$this->city, $this->region, $this->country]);
        return implode(', ', $parts);
    }

    /**
     * Scope for active destinations
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope for featured destinations
     */
    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    /**
     * Scope for homepage display (active and ordered)
     */
    public function scopeForHomepage($query)
    {
        return $query->where('is_active', true)
                    ->orderBy('is_featured', 'desc')
                    ->orderBy('display_order', 'asc')
                    ->orderBy('name', 'asc');
    }
}
