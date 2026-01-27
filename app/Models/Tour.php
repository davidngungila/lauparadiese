<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Tour extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'tour_code',
        'name',
        'slug',
        'destination_id',
        'description',
        'excerpt',
        'short_description',
        'long_description',
        'duration_days',
        'duration_nights',
        'start_location',
        'end_location',
        'tour_type',
        'max_group_size',
        'min_age',
        'difficulty_level',
        'fitness_level',
        'highlights',
        'inclusions',
        'exclusions',
        'terms_conditions',
        'cancellation_policy',
        'important_notes',
        'price',
        'starting_price',
        'rating',
        'image_url',
        'gallery_images',
        'publish_status',
        'meta_title',
        'meta_description',
        'meta_keywords',
        'og_image',
        'status',
        'availability_status',
        'is_featured',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'highlights' => 'array',
        'inclusions' => 'array',
        'exclusions' => 'array',
        'gallery_images' => 'array',
        'is_featured' => 'boolean',
        'price' => 'decimal:2',
        'starting_price' => 'decimal:2',
        'rating' => 'decimal:2',
        'duration_days' => 'integer',
        'duration_nights' => 'integer',
        'max_group_size' => 'integer',
        'min_age' => 'integer',
    ];

    /**
     * Boot the model.
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($tour) {
            if (empty($tour->tour_code)) {
                $year = date('Y');
                $lastTour = static::whereYear('created_at', $year)
                    ->orderBy('id', 'desc')
                    ->first();
                $number = $lastTour ? (int) substr($lastTour->tour_code, -4) + 1 : 1;
                $tour->tour_code = 'TR-' . $year . '-' . str_pad($number, 4, '0', STR_PAD_LEFT);
            }
            
            if (empty($tour->slug)) {
                $tour->slug = \Str::slug($tour->name);
            }
        });
    }

    /**
     * Get the destination that this tour belongs to.
     */
    public function destination(): BelongsTo
    {
        return $this->belongsTo(Destination::class);
    }

    /**
     * Get all destinations for this tour (many-to-many).
     */
    public function destinations(): BelongsToMany
    {
        return $this->belongsToMany(Destination::class, 'tour_destinations')
            ->withPivot('sort_order')
            ->orderByPivot('sort_order');
    }

    /**
     * Get the reviews for this tour.
     */
    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }

    /**
     * Get the bookings for this tour.
     */
    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class);
    }

    /**
     * Get the categories for this tour.
     */
    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(TourCategory::class, 'tour_category_tour');
    }

    /**
     * Get the itineraries for this tour.
     */
    public function itineraries(): HasMany
    {
        return $this->hasMany(TourItinerary::class)->orderBy('day_number');
    }

    /**
     * Get the availabilities for this tour.
     */
    public function availabilities(): HasMany
    {
        return $this->hasMany(TourAvailability::class);
    }

    /**
     * Get the pricings for this tour.
     */
    public function pricings(): HasMany
    {
        return $this->hasMany(TourPricing::class)->where('is_active', true);
    }

    /**
     * Get all pricings (including inactive).
     */
    public function allPricings(): HasMany
    {
        return $this->hasMany(TourPricing::class);
    }

    /**
     * Scope a query to only include published tours.
     */
    public function scopePublished($query)
    {
        return $query->where('publish_status', 'Published');
    }

    /**
     * Scope a query to only include active tours.
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'Active');
    }

    /**
     * Scope a query to only include available tours.
     */
    public function scopeAvailable($query)
    {
        return $query->where('availability_status', 'Available');
    }

    /**
     * Get the starting price (from pricing table or tour price).
     */
    public function getStartingPriceAttribute($value)
    {
        if ($value) {
            return $value;
        }
        
        $lowestPricing = $this->pricings()
            ->where('is_active', true)
            ->orderBy('price', 'asc')
            ->first();
            
        return $lowestPricing ? $lowestPricing->price : $this->price;
    }
}
