<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class TourItinerary extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'tour_id',
        'day_number',
        'title',
        'short_summary',
        'description',
        'meals_included',
        'accommodation_type',
        'accommodation_name',
        'accommodation_location',
        'accommodation_image',
        'accommodation_rating',
        'location',
        'image',
        'gallery_images',
        'activities',
        'vehicle_type',
        'driver_guide_notes',
        'transfer_info',
        'day_notes',
        'custom_icons',
        'sort_order',
    ];

    protected $casts = [
        'day_number' => 'integer',
        'sort_order' => 'integer',
        'accommodation_rating' => 'decimal:1',
        'meals_included' => 'array',
        'activities' => 'array',
        'gallery_images' => 'array',
        'custom_icons' => 'array',
    ];

    /**
     * Get the tour that owns this itinerary.
     */
    public function tour(): BelongsTo
    {
        return $this->belongsTo(Tour::class);
    }

    /**
     * Scope a query to order by day number.
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('day_number')->orderBy('sort_order');
    }
}
