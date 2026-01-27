<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SeasonalOffer extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'description',
        'tour_id',
        'discount_percentage',
        'discount_amount',
        'start_date',
        'end_date',
        'image_url',
        'is_active',
        'terms_conditions',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'discount_percentage' => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    /**
     * Get the tour associated with this offer
     */
    public function tour(): BelongsTo
    {
        return $this->belongsTo(Tour::class);
    }

    /**
     * Check if offer is currently active
     */
    public function isCurrentlyActive(): bool
    {
        return $this->is_active 
            && now()->between($this->start_date, $this->end_date);
    }
}
