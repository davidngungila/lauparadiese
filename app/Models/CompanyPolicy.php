<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class CompanyPolicy extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'title',
        'slug',
        'policy_type',
        'short_description',
        'content',
        'display_order',
        'is_active',
        'is_featured',
        'show_in_footer',
        'effective_date',
        'expiry_date',
        'version',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'is_featured' => 'boolean',
        'show_in_footer' => 'boolean',
        'display_order' => 'integer',
        'effective_date' => 'datetime',
        'expiry_date' => 'datetime',
    ];

    /**
     * Boot the model
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($policy) {
            if (empty($policy->slug)) {
                $policy->slug = Str::slug($policy->title);
                $baseSlug = $policy->slug;
                $counter = 1;
                while (static::where('slug', $policy->slug)->exists()) {
                    $policy->slug = $baseSlug . '-' . $counter;
                    $counter++;
                }
            }
        });
    }

    /**
     * Get the creator
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get the updater
     */
    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    /**
     * Check if policy is currently effective
     */
    public function isEffective(): bool
    {
        if (!$this->is_active) {
            return false;
        }

        $now = now();
        
        if ($this->effective_date && $now->lt($this->effective_date)) {
            return false;
        }

        if ($this->expiry_date && $now->gt($this->expiry_date)) {
            return false;
        }

        return true;
    }
}
