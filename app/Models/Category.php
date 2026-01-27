<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'type',
        'description',
        'image_url',
        'is_active',
        'parent_id',
        'icon',
        'color',
        'sort_order',
        'show_in_menu',
        'show_on_homepage',
        'is_featured',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'show_in_menu' => 'boolean',
        'show_on_homepage' => 'boolean',
        'is_featured' => 'boolean',
    ];

    /**
     * Get all of the posts for the category.
     */
    public function posts(): HasMany
    {
        return $this->hasMany(Post::class);
    }

    /**
     * Get all of the tours for the category.
     * Note: This relationship requires a pivot table 'category_tour' with 'category_id' and 'tour_id' columns.
     * Currently disabled as the pivot table doesn't exist.
     * If you need this relationship, create the migration for the pivot table first.
     */
    // public function tours(): BelongsToMany
    // {
    //     return $this->belongsToMany(Tour::class, 'category_tour', 'category_id', 'tour_id');
    // }

    public function parent()
    {
        return $this->belongsTo(self::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(self::class, 'parent_id');
    }
}
