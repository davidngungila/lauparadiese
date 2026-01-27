<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;
use App\Models\Destination;
use App\Models\HomepageDestination;
use App\Models\Tour;
use Illuminate\Support\Str;

class DestinationController extends Controller
{
    /**
     * Find matching image from public/images based on destination name
     */
    private function findMatchingImage($destinationName, $slug = null)
    {
        $searchTerms = [];
        
        // Add slug-based search
        if ($slug) {
            $searchTerms[] = strtolower($slug);
            $searchTerms[] = str_replace('-', '_', strtolower($slug));
            $searchTerms[] = str_replace('-', '', strtolower($slug));
        }
        
        // Add name-based search terms
        $nameLower = strtolower($destinationName);
        $nameWords = explode(' ', $nameLower);
        foreach ($nameWords as $word) {
            $word = trim($word);
            if (strlen($word) > 2) {
                $searchTerms[] = $word;
            }
        }
        
        // Add full name (without spaces)
        $searchTerms[] = str_replace(' ', '', $nameLower);
        $searchTerms[] = str_replace(' ', '-', $nameLower);
        $searchTerms[] = str_replace(' ', '_', $nameLower);
        
        // Remove duplicates and empty
        $searchTerms = array_unique(array_filter($searchTerms));
        
        // Search in public/images
        $imageDir = public_path('images');
        $images = [];
        $matchScores = [];
        
        if (is_dir($imageDir)) {
            // Search root images directory
            $files = glob($imageDir . '/*.{jpg,jpeg,png,webp,gif}', GLOB_BRACE);
            foreach ($files as $file) {
                $fileName = strtolower(basename($file));
                $relativePath = 'images/' . basename($file);
                
                // Calculate match score
                $score = 0;
                foreach ($searchTerms as $term) {
                    if (str_contains($fileName, $term)) {
                        $score += strlen($term); // Longer matches score higher
                    }
                }
                
                if ($score > 0) {
                    $images[] = $relativePath;
                    $matchScores[$relativePath] = $score;
                }
            }
            
            // Search in subdirectories
            $subdirs = ['hero-slider', 'tours'];
            foreach ($subdirs as $subdir) {
                $subdirPath = $imageDir . '/' . $subdir;
                if (is_dir($subdirPath)) {
                    $files = glob($subdirPath . '/*.{jpg,jpeg,png,webp,gif}', GLOB_BRACE);
                    foreach ($files as $file) {
                        $fileName = strtolower(basename($file));
                        $relativePath = 'images/' . $subdir . '/' . basename($file);
                        
                        $score = 0;
                        foreach ($searchTerms as $term) {
                            if (str_contains($fileName, $term)) {
                                $score += strlen($term);
                            }
                        }
                        
                        if ($score > 0) {
                            $images[] = $relativePath;
                            $matchScores[$relativePath] = $score;
                        }
                    }
                }
            }
        }
        
        // Sort by match score (highest first) and return best match
        if (!empty($images)) {
            usort($images, function($a, $b) use ($matchScores) {
                return ($matchScores[$b] ?? 0) <=> ($matchScores[$a] ?? 0);
            });
            return $images[0];
        }
        
        return null;
    }
    
    /**
     * Get image URL for destination
     */
    private function getDestinationImage($destination)
    {
        // Priority 1: Featured image from database (via ImageService)
        if ($destination->featured_image_display_url) {
            return $destination->featured_image_display_url;
        }
        
        // Priority 2: Featured image URL from database
        if ($destination->featured_image_url) {
            // Check if it's an HTTP URL or relative path
            if (str_starts_with($destination->featured_image_url, 'http://') || str_starts_with($destination->featured_image_url, 'https://')) {
                return $destination->featured_image_url;
            }
            // Use asset() for relative paths like images/tours/...
            return asset($destination->featured_image_url);
        }
        
        // Priority 3: Try to find matching image from public/images
        $matchingImage = $this->findMatchingImage($destination->name, $destination->slug);
        if ($matchingImage) {
            return asset($matchingImage);
        }
        
        // Priority 4: Check image_gallery array
        if ($destination->image_gallery && is_array($destination->image_gallery) && count($destination->image_gallery) > 0) {
            $firstImage = $destination->image_gallery[0];
            // Check if it's an HTTP URL or relative path
            if (str_starts_with($firstImage, 'http://') || str_starts_with($firstImage, 'https://')) {
                return $firstImage;
            }
            // Use asset() for relative paths like images/tours/...
            return asset($firstImage);
        }
        
        // Fallback
        return asset('images/safari_home-1.jpg');
    }
    
    /**
     * Display a listing of all destinations.
     */
    public function index(): View
    {
        // Get HomepageDestinations for display
        $homepageDestinations = HomepageDestination::where('is_active', true)
            ->orderBy('is_featured', 'desc')
            ->orderBy('display_order', 'asc')
            ->orderBy('name', 'asc')
            ->get()
            ->map(function($dest) {
                return [
                    'id' => $dest->id,
                    'name' => $dest->name,
                    'slug' => $dest->slug ?? Str::slug($dest->name),
                    'short_description' => $dest->short_description,
                    'full_description' => $dest->full_description,
                    'category' => $dest->category,
                    'location' => $dest->location,
                    'price' => $dest->price,
                    'price_display' => $dest->price_display,
                    'duration' => $dest->duration,
                    'rating' => $dest->rating,
                    'is_featured' => $dest->is_featured,
                    'image' => $this->getDestinationImage($dest),
                    'image_gallery' => $dest->image_gallery ?? [],
                ];
            });
        
        // Get regular destinations for fallback
        $destinations = Destination::all();
        
        return view('destinations.index', compact('homepageDestinations', 'destinations'));
    }

    /**
     * Display the specified destination and its tours.
     */
    public function show(string $slug): View
    {
        // Try HomepageDestination first
        $homepageDestination = HomepageDestination::where('slug', $slug)
            ->orWhere('slug', Str::slug($slug))
            ->first();
        
        if ($homepageDestination) {
            // Use HomepageDestination
            $destination = $homepageDestination;
            
            // Get tours that match this destination by name or related destinations
            $tours = Tour::with('destination')
                ->where('status', 'active')
                ->where('publish_status', 'published')
                ->where(function($query) use ($destination) {
                    // Match by destination name in tour name or description
                    $query->where('name', 'like', '%' . $destination->name . '%')
                          ->orWhere('short_description', 'like', '%' . $destination->name . '%')
                          ->orWhere('description', 'like', '%' . $destination->name . '%');
                })
                ->orderBy('is_featured', 'desc')
                ->orderBy('created_at', 'desc')
                ->take(12)
                ->get()
                ->map(function($tour) {
                    return [
                        'id' => $tour->id,
                        'name' => $tour->name,
                        'slug' => $tour->slug,
                        'price' => (float) $tour->price,
                        'starting_price' => (float) ($tour->starting_price ?? $tour->price),
                        'duration_days' => $tour->duration_days,
                        'image' => $tour->image_url ? (str_starts_with($tour->image_url, 'http://') || str_starts_with($tour->image_url, 'https://') ? $tour->image_url : asset($tour->image_url)) : asset('images/safari_home-1.jpg'),
                        'description' => $tour->short_description ?: substr($tour->description ?? '', 0, 150) . '...',
                        'rating' => $tour->rating ?? 4.5,
                        'is_featured' => $tour->is_featured ?? false,
                    ];
                });
            
            // Get related destinations
            $relatedDestinations = HomepageDestination::where('id', '!=', $destination->id)
                ->where('is_active', true)
                ->orderBy('is_featured', 'desc')
                ->take(3)
                ->get();
            
            return view('destinations.show', [
                'destination' => $destination,
                'homepageDestination' => $destination,
                'tours' => $tours,
                'relatedDestinations' => $relatedDestinations,
                'destinationImage' => $this->getDestinationImage($destination),
                'galleryImages' => $destination->image_gallery ?? [],
            ]);
        }
        
        // Fallback to regular Destination model
        $destination = Destination::where('slug', $slug)->firstOrFail();
        
        $tours = Tour::with('destination')
            ->where('destination_id', $destination->id)
            ->where('status', 'active')
            ->where('publish_status', 'published')
            ->orderBy('is_featured', 'desc')
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function($tour) {
                return [
                    'id' => $tour->id,
                    'name' => $tour->name,
                    'slug' => $tour->slug,
                    'price' => (float) $tour->price,
                    'starting_price' => (float) ($tour->starting_price ?? $tour->price),
                    'duration_days' => $tour->duration_days,
                    'image' => $tour->image_url ? (str_starts_with($tour->image_url, 'http://') || str_starts_with($tour->image_url, 'https://') ? $tour->image_url : asset($tour->image_url)) : asset('images/safari_home-1.jpg'),
                    'description' => $tour->short_description ?: substr($tour->description ?? '', 0, 150) . '...',
                    'rating' => $tour->rating ?? 4.5,
                    'is_featured' => $tour->is_featured ?? false,
                ];
            });
        
        // Get related destinations
        $relatedDestinations = Destination::where('id', '!=', $destination->id)
            ->take(3)
            ->get();
        
        return view('destinations.show', [
            'destination' => $destination,
            'tours' => $tours,
            'relatedDestinations' => $relatedDestinations,
            'destinationImage' => $destination->image_url ? (str_starts_with($destination->image_url, 'http://') || str_starts_with($destination->image_url, 'https://') ? $destination->image_url : asset($destination->image_url)) : ($this->findMatchingImage($destination->name, $destination->slug) ? asset($this->findMatchingImage($destination->name, $destination->slug)) : asset('images/safari_home-1.jpg')),
        ]);
    }
}
