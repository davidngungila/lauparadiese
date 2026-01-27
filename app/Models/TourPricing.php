<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class TourPricing extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'tour_id',
        'currency',
        'price_type',
        'category_type',
        'price',
        'child_price',
        'min_pax',
        'max_pax',
        'valid_from',
        'valid_to',
        'optional_addons',
        'discount_percentage',
        'final_price',
        'is_active',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'child_price' => 'decimal:2',
        'final_price' => 'decimal:2',
        'min_pax' => 'integer',
        'max_pax' => 'integer',
        'valid_from' => 'date',
        'valid_to' => 'date',
        'optional_addons' => 'array',
        'discount_percentage' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    /**
     * Get the tour that owns this pricing.
     */
    public function tour(): BelongsTo
    {
        return $this->belongsTo(Tour::class);
    }

    /**
     * Scope a query to only include active pricings.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope a query to only include valid pricings for a date.
     */
    public function scopeValidForDate($query, $date)
    {
        return $query->where(function($q) use ($date) {
            $q->whereNull('valid_from')
              ->orWhere('valid_from', '<=', $date);
        })->where(function($q) use ($date) {
            $q->whereNull('valid_to')
              ->orWhere('valid_to', '>=', $date);
        });
    }

    /**
     * Calculate final price with discount.
     */
    public function calculateFinalPrice()
    {
        $price = $this->price;
        
        if ($this->discount_percentage) {
            $discount = $price * ($this->discount_percentage / 100);
            $price = $price - $discount;
        }
        
        $this->final_price = $price;
        return $price;
    }
}
