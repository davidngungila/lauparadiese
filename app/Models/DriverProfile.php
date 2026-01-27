<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class DriverProfile extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'driver_code',
        'photo',
        'phone',
        'national_id',
        'passport_number',
        'driving_license_number',
        'license_expiry_date',
        'languages_spoken',
        'experience_level',
        'special_skills',
        'documents',
        'status',
        'rating',
    ];

    protected $casts = [
        'languages_spoken' => 'array',
        'special_skills' => 'array',
        'documents' => 'array',
        'license_expiry_date' => 'date',
        'rating' => 'decimal:2',
    ];

    /**
     * Get the user that owns the driver profile.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get all vehicles assigned to this driver.
     */
    public function vehicles(): HasMany
    {
        return $this->hasMany(Vehicle::class, 'driver_id', 'user_id');
    }
}
