<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class TourAvailability extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'tour_id',
        'date',
        'start_date',
        'end_date',
        'available_slots',
        'status',
        'price_override',
        'notes',
        'is_repeating',
        'repeat_pattern',
        'repeat_days',
        'repeat_until',
    ];

    protected $casts = [
        'date' => 'date',
        'start_date' => 'date',
        'end_date' => 'date',
        'repeat_until' => 'date',
        'available_slots' => 'integer',
        'is_repeating' => 'boolean',
        'repeat_days' => 'array',
        'price_override' => 'decimal:2',
    ];

    /**
     * Get the tour that owns this availability.
     */
    public function tour(): BelongsTo
    {
        return $this->belongsTo(Tour::class);
    }

    /**
     * Scope a query to only include available dates.
     */
    public function scopeAvailable($query)
    {
        return $query->where('status', 'Available');
    }

    /**
     * Scope a query to only include dates on or after today.
     */
    public function scopeUpcoming($query)
    {
        return $query->where(function($q) {
            $q->where('date', '>=', now())
              ->orWhere(function($q2) {
                  $q2->whereNotNull('start_date')
                     ->where('start_date', '>=', now());
              });
        });
    }
}
