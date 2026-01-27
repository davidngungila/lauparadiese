<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Vehicle extends Model
{
    use HasFactory;

    protected $fillable = [
        'vehicle_code',
        'vehicle_name',
        'vehicle_type',
        'make',
        'model',
        'year',
        'license_plate',
        'registration_no',
        'chassis_number',
        'color',
        'capacity',
        'fuel_type',
        'transmission',
        'features',
        'cover_image',
        'gallery_images',
        'driver_id',
        'current_booking_id',
        'status',
        'last_maintenance',
        'next_maintenance',
        'odometer_reading',
        'service_notes',
        'notes',
    ];

    protected $casts = [
        'last_maintenance' => 'date',
        'next_maintenance' => 'date',
        'features' => 'array',
        'gallery_images' => 'array',
    ];

    /**
     * Boot the model.
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($vehicle) {
            if (empty($vehicle->vehicle_code)) {
                $lastVehicle = static::orderBy('id', 'desc')->first();
                $number = $lastVehicle ? ((int) substr($lastVehicle->vehicle_code ?? 'VH-0000', 3)) + 1 : 1;
                $vehicle->vehicle_code = 'VH-' . str_pad($number, 4, '0', STR_PAD_LEFT);
            }
        });
    }

    /**
     * Get the driver assigned to this vehicle.
     */
    public function driver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'driver_id');
    }

    /**
     * Get the current booking for this vehicle.
     */
    public function currentBooking(): BelongsTo
    {
        return $this->belongsTo(Booking::class, 'current_booking_id');
    }

    /**
     * Get all maintenance records for this vehicle.
     */
    public function maintenances(): HasMany
    {
        return $this->hasMany(VehicleMaintenance::class);
    }

    /**
     * Get all documents for this vehicle.
     */
    public function documents(): HasMany
    {
        return $this->hasMany(VehicleDocument::class);
    }

    /**
     * Get all tour operations for this vehicle.
     */
    public function tourOperations(): HasMany
    {
        return $this->hasMany(TourOperation::class);
    }

    /**
     * Get all transport bookings for this vehicle.
     */
    public function transportBookings(): HasMany
    {
        return $this->hasMany(TransportBooking::class);
    }

    /**
     * Get vehicle display name.
     */
    public function getDisplayNameAttribute(): string
    {
        return $this->vehicle_name ?: ($this->make . ' ' . $this->model);
    }
}
