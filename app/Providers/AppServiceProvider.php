<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Models\Tour;
use App\Models\Destination;
use App\Models\HomepageDestination;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Share navigation data with header
        View::composer('layouts.header', function ($view) {
            // Get featured tours for dropdown
            $navTours = Tour::with('destination')
                ->where('status', 'active')
                ->where('publish_status', 'published')
                ->orderBy('is_featured', 'desc')
                ->orderBy('created_at', 'desc')
                ->take(6)
                ->get()
                ->map(function($tour) {
                    return [
                        'name' => $tour->name,
                        'slug' => $tour->slug,
                        'image' => $tour->image_url ? (str_starts_with($tour->image_url, 'http') ? $tour->image_url : asset($tour->image_url)) : asset('images/safari_home-1.jpg'),
                        'price' => number_format($tour->starting_price ?? $tour->price),
                        'duration_days' => $tour->duration_days,
                    ];
                });

            // Get safari tours (tours in safari destinations)
            $navSafaris = Tour::with('destination')
                ->where('status', 'active')
                ->where('publish_status', 'published')
                ->whereHas('destination', function ($query) {
                    $query->whereIn('slug', ['serengeti', 'ngorongoro', 'tarangire', 'lake-manyara', 'selous', 'ruaha']);
                })
                ->orderBy('is_featured', 'desc')
                ->orderBy('created_at', 'desc')
                ->take(6)
                ->get()
                ->map(function($tour) {
                    return [
                        'name' => $tour->name,
                        'slug' => $tour->slug,
                        'image' => $tour->image_url ? (str_starts_with($tour->image_url, 'http') ? $tour->image_url : asset($tour->image_url)) : asset('images/safari_home-1.jpg'),
                        'price' => number_format($tour->starting_price ?? $tour->price),
                        'duration_days' => $tour->duration_days,
                    ];
                });

            // Get destinations for dropdown - use HomepageDestination first, fallback to Destination
            $homepageDestinations = HomepageDestination::where('is_active', true)
                ->orderBy('is_featured', 'desc')
                ->orderBy('display_order', 'asc')
                ->orderBy('name', 'asc')
                ->take(6)
                ->get();
            
            $navDestinations = $homepageDestinations->map(function($destination) {
                // Use featured_image_display_url if available (handles both URLs and relative paths)
                $image = $destination->featured_image_display_url;
                
                // If not available, check featured_image_url
                if (!$image && $destination->featured_image_url) {
                    if (str_starts_with($destination->featured_image_url, 'http://') || str_starts_with($destination->featured_image_url, 'https://')) {
                        $image = $destination->featured_image_url;
                    } else {
                        $image = asset($destination->featured_image_url);
                    }
                }
                
                // Fallback to default if no image
                if (!$image) {
                    $image = asset('images/safari_home-1.jpg');
                }
                
                return [
                    'name' => $destination->name,
                    'slug' => $destination->slug ?? \Illuminate\Support\Str::slug($destination->name),
                    'image' => $image,
                ];
            });
            
            // If we don't have enough HomepageDestinations, fill with regular Destinations
            if ($navDestinations->count() < 6) {
                $remaining = 6 - $navDestinations->count();
                $regularDestinations = Destination::whereNotIn('slug', $navDestinations->pluck('slug')->toArray())
                    ->take($remaining)
                    ->get()
                    ->map(function($destination) {
                        $image = $destination->image_url 
                            ? (str_starts_with($destination->image_url, 'http://') || str_starts_with($destination->image_url, 'https://') 
                                ? $destination->image_url 
                                : asset($destination->image_url)) 
                            : asset('images/safari_home-1.jpg');
                        
                        return [
                            'name' => $destination->name,
                            'slug' => $destination->slug,
                            'image' => $image,
                        ];
                    });
                
                $navDestinations = $navDestinations->merge($regularDestinations);
            }

            $view->with([
                'navTours' => $navTours,
                'navSafaris' => $navSafaris,
                'navDestinations' => $navDestinations,
            ]);
        });
    }
}
