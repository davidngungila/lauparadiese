<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;
use App\Models\Tour;
use App\Models\Destination;

class TourController extends Controller
{
    /**
     * Display a listing of all tours.
     */
    public function index(Request $request): View
    {
        $perPage = $request->get('per_page', 12); // 12 tours per page
        
        $tours = Tour::with('destination')
            ->where('status', 'active')
            ->where('publish_status', 'published')
            ->orderBy('is_featured', 'desc')
            ->orderBy('created_at', 'desc')
            ->paginate($perPage)
            ->through(function($tour) {
                return [
                    'id' => $tour->id,
                    'name' => $tour->name,
                    'slug' => $tour->slug,
                    'price' => (float) $tour->price,
                    'starting_price' => (float) ($tour->starting_price ?? $tour->price),
                    'duration_days' => $tour->duration_days,
                    'image' => $tour->image_url ? (str_starts_with($tour->image_url, 'http') ? $tour->image_url : asset($tour->image_url)) : asset('images/safari_home-1.jpg'),
                    'description' => $tour->short_description ?: substr($tour->description ?? '', 0, 150) . '...',
                    'destination' => $tour->destination ? $tour->destination->name : 'Tanzania',
                    'rating' => $tour->rating ?? 4.5,
                    'fitness_level' => $tour->fitness_level ?? 'moderate',
                    'max_capacity' => $tour->max_group_size ?? 12,
                    'is_featured' => $tour->is_featured ?? false,
                ];
            });

        return view('tours.index', compact('tours'));
    }

    /**
     * Display last minute tour deals.
     */
    public function lastMinuteDeals(): View
    {
        $deals = Tour::with('destination')
            ->where('status', 'active')
            ->where('publish_status', 'published')
            ->orderBy('price', 'asc')
            ->take(6)
            ->get()
            ->map(function($tour) {
                return [
                    'id' => $tour->id,
                    'name' => $tour->name,
                    'slug' => $tour->slug,
                    'price' => (float) $tour->price,
                    'discount_price' => (float) ($tour->price * 0.85), // 15% discount
                    'duration_days' => $tour->duration_days,
                    'image' => $tour->image_url ? (str_starts_with($tour->image_url, 'http') ? $tour->image_url : asset($tour->image_url)) : asset('images/safari_home-1.jpg'),
                    'destination' => $tour->destination ? $tour->destination->name : 'Tanzania',
                    'rating' => $tour->rating ?? 4.5,
                ];
            });

        return view('tours.last-minute', compact('deals'));
    }

    /**
     * Display tours by a specific category.
     */
    public function category(string $slug): View
    {
        $tours = Tour::with('destination')
            ->where('status', 'active')
            ->where('publish_status', 'published')
            ->whereHas('destination', function($query) use ($slug) {
                $query->where('slug', $slug);
            })
            ->orderBy('is_featured', 'desc')
            ->get();

        $destination = Destination::where('slug', $slug)->first();

        return view('tours.category', compact('tours', 'destination'));
    }

    /**
     * Display the specified tour.
     */
    public function show(string $slug): View
    {
        $tour = Tour::with(['destination', 'itineraries', 'reviews', 'destinations'])
            ->where('slug', $slug)
            ->where('status', 'active')
            ->where('publish_status', 'published')
            ->firstOrFail();

        // Get related tours (from same destination or similar tours)
        $relatedTours = Tour::with('destination')
            ->where('status', 'active')
            ->where('publish_status', 'published')
            ->where('id', '!=', $tour->id)
            ->where(function($query) use ($tour) {
                $query->where('destination_id', $tour->destination_id)
                      ->orWhere('tour_type', $tour->tour_type)
                      ->orWhere('difficulty_level', $tour->difficulty_level);
            })
            ->orderBy('is_featured', 'desc')
            ->orderBy('rating', 'desc')
            ->take(3)
            ->get();

        return view('tours.show', compact('tour', 'relatedTours'));
    }
}
