<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class CustomerGroup extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'color',
        'is_active',
        'display_order',
        'auto_grouping_rules',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'display_order' => 'integer',
        'auto_grouping_rules' => 'array',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($group) {
            if (empty($group->slug)) {
                $group->slug = Str::slug($group->name);
                $baseSlug = $group->slug;
                $counter = 1;
                while (static::where('slug', $group->slug)->exists()) {
                    $group->slug = $baseSlug . '-' . $counter;
                    $counter++;
                }
            }
        });
    }

    /**
     * Get all customers in this group
     */
    public function customers(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'customer_group_user')
            ->withPivot('joined_at')
            ->withTimestamps();
    }

    /**
     * Get total customers count
     */
    public function getCustomersCountAttribute(): int
    {
        return $this->customers()->count();
    }

    /**
     * Get total revenue from group
     */
    public function getTotalRevenueAttribute(): float
    {
        return $this->customers()
            ->withSum('bookings', 'total_price')
            ->get()
            ->sum('bookings_sum_total_price') ?? 0;
    }
}
