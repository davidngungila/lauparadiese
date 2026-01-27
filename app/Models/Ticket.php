<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Ticket extends Model
{
    use HasFactory;

    protected $fillable = [
        'booking_id', 'ticket_type', 'ticket_number', 'passenger_name',
        'passenger_email', 'passenger_phone', 'departure_location',
        'arrival_location', 'departure_date', 'arrival_date',
        'airline_company', 'seat_number', 'class', 'price',
        'currency', 'status', 'notes',
    ];

    protected $casts = [
        'departure_date' => 'datetime',
        'arrival_date' => 'datetime',
        'price' => 'decimal:2',
    ];

    public function booking(): BelongsTo
    {
        return $this->belongsTo(Booking::class);
    }
}
