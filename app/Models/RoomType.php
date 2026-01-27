<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RoomType extends Model
{
    use HasFactory;

    protected $fillable = [
        'hotel_id',
        'name',
        'category',
        'max_occupancy',
        'total_rooms',
        'available_rooms',
        'base_price',
        'weekend_price',
        'holiday_price',
        'room_size',
        'bed_type',
        'amenities',
        'description',
        'is_active',
    ];

    protected $casts = [
        'amenities' => 'array',
        'base_price' => 'decimal:2',
        'weekend_price' => 'decimal:2',
        'holiday_price' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    /**
     * Get the hotel that owns the room type.
     */
    public function hotel(): BelongsTo
    {
        return $this->belongsTo(Hotel::class);
    }

    /**
     * Scope: Get active room types
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Get occupancy percentage
     */
    public function getOccupancyPercentageAttribute(): float
    {
        if ($this->total_rooms == 0) {
            return 0;
        }
        return (($this->total_rooms - $this->available_rooms) / $this->total_rooms) * 100;
    }
}
