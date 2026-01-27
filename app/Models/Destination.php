<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Destination extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'image_url',
    ];

    /**
     * Get all of the tours for the destination.
     */
    public function tours(): HasMany
    {
        return $this->hasMany(Tour::class);
    }
}
