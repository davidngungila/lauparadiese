<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TourOperation extends Model
{
    use HasFactory;

    protected $fillable = [
        'tour_id', 'booking_id', 'operation_date', 'guide_id',
        'driver_id', 'vehicle_id', 'status', 'checklist',
        'daily_log', 'attendance', 'notes',
    ];

    protected $casts = [
        'operation_date' => 'date',
        'checklist' => 'array',
        'attendance' => 'array',
    ];

    public function tour(): BelongsTo
    {
        return $this->belongsTo(Tour::class);
    }

    public function booking(): BelongsTo
    {
        return $this->belongsTo(Booking::class);
    }

    public function guide(): BelongsTo
    {
        return $this->belongsTo(User::class, 'guide_id');
    }

    public function driver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'driver_id');
    }

    public function vehicle(): BelongsTo
    {
        return $this->belongsTo(Vehicle::class);
    }
}
