<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Partner extends Model
{
    use HasFactory;

    protected $fillable = [
        'partner_type', 'name', 'contact_person', 'email', 'phone',
        'address', 'city', 'country', 'website', 'description',
        'commission_rate', 'status', 'documents',
    ];

    protected $casts = [
        'documents' => 'array',
        'commission_rate' => 'decimal:2',
    ];

    public function hotels(): HasMany
    {
        return $this->hasMany(Hotel::class);
    }
}
