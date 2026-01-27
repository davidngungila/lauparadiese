<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class VehicleMaintenance extends Model
{
    use HasFactory;

    protected $fillable = [
        'vehicle_id',
        'service_type',
        'service_date',
        'next_service_date',
        'odometer_reading',
        'cost',
        'service_notes',
        'parts_replaced',
        'service_provider',
        'performed_by',
        'attachments',
    ];

    protected $casts = [
        'service_date' => 'date',
        'next_service_date' => 'date',
        'parts_replaced' => 'array',
        'attachments' => 'array',
        'cost' => 'decimal:2',
    ];

    /**
     * Get the vehicle that this maintenance belongs to.
     */
    public function vehicle(): BelongsTo
    {
        return $this->belongsTo(Vehicle::class);
    }

    /**
     * Get the user who performed the maintenance.
     */
    public function performedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'performed_by');
    }
}
