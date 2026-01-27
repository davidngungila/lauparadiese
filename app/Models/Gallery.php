<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Services\ImageService;

class Gallery extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'galleries';

    protected $fillable = [
        'title',
        'description',
        'caption',
        'alt_text',
        'image_url',
        'original_filename',
        'file_size',
        'mime_type',
        'width',
        'height',
        'thumbnail_150',
        'thumbnail_300',
        'thumbnail_600',
        'thumbnail_hd',
        'webp_url',
        'category',
        'album_id',
        'tags',
        'display_order',
        'priority',
        'visibility',
        'visible_from',
        'visible_until',
        'click_action',
        'click_link',
        'seo_filename',
        'seo_alt_text',
        'auto_optimize',
        'convert_to_webp',
        'resize_large',
        'optimization_quality',
        'uploaded_by',
        'uploaded_at',
        'is_featured',
        'is_active',
    ];

    protected $casts = [
        'tags' => 'array',
        'is_featured' => 'boolean',
        'is_active' => 'boolean',
        'display_order' => 'integer',
        'width' => 'integer',
        'height' => 'integer',
        'file_size' => 'integer',
        'auto_optimize' => 'boolean',
        'convert_to_webp' => 'boolean',
        'resize_large' => 'boolean',
        'optimization_quality' => 'integer',
        'visible_from' => 'datetime',
        'visible_until' => 'datetime',
        'uploaded_at' => 'datetime',
    ];

    /**
     * Get the album that the gallery item belongs to.
     */
    public function album()
    {
        return $this->belongsTo(GalleryAlbum::class, 'album_id');
    }

    /**
     * Get the user who uploaded the image.
     */
    public function uploader()
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }

    /**
     * Get tags relationship (many-to-many)
     */
    public function tagRelations()
    {
        return $this->belongsToMany(GalleryTag::class, 'gallery_gallery_tag', 'gallery_id', 'gallery_tag_id');
    }

    /**
     * Get the image URL for display (handles both storage paths and external URLs)
     */
    public function getDisplayUrlAttribute()
    {
        if (!$this->attributes['image_url'] ?? null) {
            return null;
        }
        
        $imageService = new ImageService();
        return $imageService->getUrl($this->attributes['image_url']);
    }

    /**
     * Get thumbnail URL
     */
    public function getThumbnailUrl($size = '300')
    {
        $thumbField = "thumbnail_{$size}";
        if ($this->$thumbField) {
            $imageService = new ImageService();
            return $imageService->getUrl($this->$thumbField);
        }
        
        // Fallback to main image
        return $this->display_url;
    }

    /**
     * Check if image is currently visible
     */
    public function isVisible(): bool
    {
        if (!$this->is_active) {
            return false;
        }

        $now = now();
        
        if ($this->visible_from && $now->lt($this->visible_from)) {
            return false;
        }

        if ($this->visible_until && $now->gt($this->visible_until)) {
            return false;
        }

        return true;
    }

    /**
     * Get formatted file size
     */
    public function getFormattedFileSizeAttribute()
    {
        if (!$this->file_size) {
            return 'N/A';
        }

        $units = ['B', 'KB', 'MB', 'GB'];
        $size = $this->file_size;
        $unit = 0;

        while ($size >= 1024 && $unit < count($units) - 1) {
            $size /= 1024;
            $unit++;
        }

        return round($size, 2) . ' ' . $units[$unit];
    }
}
