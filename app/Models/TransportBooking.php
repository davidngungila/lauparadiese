<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TransportBooking extends Model
{
    use HasFactory;

    protected $fillable = [
        'transport_id',
        'customer_name',
        'customer_email',
        'customer_phone',
        'pickup_location',
        'dropoff_location',
        'travel_date',
        'number_of_passengers',
        'luggage_info',
        'preferred_vehicle_type',
        'vehicle_id',
        'driver_id',
        'base_price',
        'addons_price',
        'discount_amount',
        'final_price',
        'status',
        'admin_notes',
    ];

    protected $casts = [
        'travel_date' => 'datetime',
        'base_price' => 'decimal:2',
        'addons_price' => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'final_price' => 'decimal:2',
    ];

    /**
     * Get the vehicle assigned to this transport booking.
     */
    public function vehicle(): BelongsTo
    {
        return $this->belongsTo(Vehicle::class);
    }

    /**
     * Get the driver assigned to this transport booking.
     */
    public function driver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'driver_id');
    }

    /**
     * Boot the model.
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($booking) {
            if (empty($booking->transport_id)) {
                $lastBooking = static::orderBy('id', 'desc')->first();
                $number = $lastBooking ? ((int) substr($lastBooking->transport_id, 3)) + 1 : 1;
                $booking->transport_id = 'TR-' . str_pad($number, 4, '0', STR_PAD_LEFT);
            }
        });
    }
}
