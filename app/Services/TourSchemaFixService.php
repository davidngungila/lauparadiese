<?php

namespace App\Services;

use Illuminate\Support\Facades\Schema;

class TourSchemaFixService
{
    /**
     * Quick helper to describe current tour & review schema.
     */
    public static function describe(): array
    {
        return [
            'tours' => Schema::getColumnListing('tours'),
            'reviews' => Schema::getColumnListing('reviews'),
        ];
    }
}





