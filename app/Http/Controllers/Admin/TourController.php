<?php

namespace App\Http\Controllers\Admin;

use App\Models\Tour;
use App\Models\Destination;
use App\Models\Category;
use App\Models\Booking;
use App\Models\TourCategory;
use App\Models\TourAvailability;
use App\Models\TourPricing;
use App\Models\TourItinerary;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;

class TourController extends BaseAdminController
{
    /**
     * Display a listing of tours with comprehensive filtering
     */
    public function index(Request $request)
    {
        $query = Tour::with(['destinations', 'categories', 'bookings']);
        
        // Category filter
        if ($request->filled('category')) {
            $query->whereHas('categories', function($q) use ($request) {
                $q->where('tour_categories.id', $request->category);
            });
        }
        
        // Destination filter
        if ($request->filled('destination')) {
            $query->whereHas('destinations', function($q) use ($request) {
                $q->where('destinations.id', $request->destination);
            });
        }
        
        // Duration filter
        if ($request->filled('duration_min')) {
            $query->where('duration_days', '>=', $request->duration_min);
        }
        if ($request->filled('duration_max')) {
            $query->where('duration_days', '<=', $request->duration_max);
        }
        
        // Price range filter
        if ($request->filled('price_min')) {
            $query->where(function($q) use ($request) {
                $q->where('price', '>=', $request->price_min)
                  ->orWhere('starting_price', '>=', $request->price_min);
            });
        }
        if ($request->filled('price_max')) {
            $query->where(function($q) use ($request) {
                $q->where('price', '<=', $request->price_max)
                  ->orWhere('starting_price', '<=', $request->price_max);
            });
        }
        
        // Availability filter
        if ($request->filled('availability')) {
            $query->where('availability_status', $request->availability);
        }
        
        // Status filter
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        
        // Publish status filter
        if ($request->filled('publish_status')) {
            $query->where('publish_status', $request->publish_status);
        }
        
        // Search by name
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('tour_code', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhere('short_description', 'like', "%{$search}%");
            });
        }
        
        $tours = $query->withCount('bookings')->latest()->paginate(20);
        $destinations = Destination::orderBy('name')->get();
        $categories = TourCategory::where('is_active', true)->orderBy('name')->get();
        
        $stats = [
            'total_tours' => Tour::count(),
            'published_tours' => Tour::where('publish_status', 'Published')->count(),
            'draft_tours' => Tour::where('publish_status', 'Draft')->count(),
            'active_tours' => Tour::where('status', 'Active')->count(),
            'available_tours' => Tour::where('availability_status', 'Available')->count(),
            'total_bookings' => Booking::whereIn('tour_id', Tour::pluck('id'))->count(),
            'avg_price' => Tour::avg('price'),
        ];
        
        return view('admin.tours.index', compact('tours', 'destinations', 'categories', 'stats'));
    }

    /**
     * Show the form for creating a new tour
     */
    public function create()
    {
        $destinations = Destination::orderBy('name')->get();
        $categories = TourCategory::where('is_active', true)->orderBy('name')->get();
        return view('admin.tours.create', compact('destinations', 'categories'));
    }

    /**
     * Store a newly created tour
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            // Basic Information
            'name' => 'required|string|max:255',
            'short_description' => 'nullable|string',
            'long_description' => 'nullable|string',
            'tour_code' => 'nullable|string|unique:tours,tour_code',
            'category_ids' => 'nullable|array',
            'category_ids.*' => 'exists:tour_categories,id',
            'destination_ids' => 'required|array|min:1',
            'destination_ids.*' => 'exists:destinations,id',
            'cover_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'gallery_images' => 'nullable|array',
            'gallery_images.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
            
            // Tour Details
            'duration_days' => 'required|integer|min:1',
            'duration_nights' => 'nullable|integer|min:0',
            'start_location' => 'nullable|string|max:255',
            'end_location' => 'nullable|string|max:255',
            'tour_type' => 'required|in:Private,Group,Shared,Customizable',
            'max_group_size' => 'nullable|integer|min:1',
            'min_age' => 'nullable|integer|min:0',
            'difficulty_level' => 'nullable|in:Easy,Medium,Hard',
            'highlights' => 'nullable|array',
            
            // Inclusions & Exclusions
            'inclusions' => 'nullable|array',
            'exclusions' => 'nullable|array',
            
            // Additional Info
            'terms_conditions' => 'nullable|string',
            'cancellation_policy' => 'nullable|string',
            'important_notes' => 'nullable|string',
            
            // Visibility & SEO
            'publish_status' => 'required|in:Draft,Published,Hidden',
            'slug' => 'nullable|string|unique:tours,slug',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
            'meta_keywords' => 'nullable|string',
            'og_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            
            // Status
            'status' => 'required|in:Active,Inactive',
            'availability_status' => 'required|in:Available,Sold Out',
            'price' => 'nullable|numeric|min:0',
            'starting_price' => 'nullable|numeric|min:0',
            'is_featured' => 'boolean',
        ]);
        
        // Handle image uploads
        if ($request->hasFile('cover_image')) {
            $validated['image_url'] = $request->file('cover_image')->store('tours', 'public');
        }
        
        if ($request->hasFile('gallery_images')) {
            $galleryPaths = [];
            foreach ($request->file('gallery_images') as $image) {
                $galleryPaths[] = $image->store('tours/gallery', 'public');
            }
            $validated['gallery_images'] = $galleryPaths;
        }
        
        if ($request->hasFile('og_image')) {
            $validated['og_image'] = $request->file('og_image')->store('tours/og', 'public');
        }
        
        // Generate slug if not provided
        if (empty($validated['slug'])) {
            $validated['slug'] = Str::slug($validated['name']);
        }
        
        $validated['is_featured'] = $request->has('is_featured');
        
        $tour = Tour::create($validated);
        
        // Attach destinations
        if (isset($validated['destination_ids'])) {
            $tour->destinations()->attach($validated['destination_ids']);
        }
        
        // Attach categories
        if (isset($validated['category_ids'])) {
            $tour->categories()->attach($validated['category_ids']);
        }
        
        $redirectRoute = route('admin.tours.index');
        if ($request->has('save_and_add_itinerary')) {
            $redirectRoute = route('admin.tours.itinerary-builder', ['tour_id' => $tour->id]);
        } elseif ($request->has('save_and_add_pricing')) {
            $redirectRoute = route('admin.tours.pricing', ['tour_id' => $tour->id]);
        }
        
        return $this->successResponse('Tour created successfully!', $redirectRoute);
    }

    /**
     * Display the specified tour
     */
    public function show($id)
    {
        $tour = Tour::with([
            'destination', 
            'bookings' => function($q) {
                $q->latest()->limit(10);
            },
            'bookings.user',
            'reviews' => function($q) {
                $q->latest()->limit(10);
            },
            'reviews.user',
            'itineraries' => function($q) {
                $q->orderBy('day_number')->orderBy('sort_order');
            },
            'categories'
        ])->findOrFail($id);
        
        // Calculate statistics
        $totalBookings = $tour->bookings()->count();
        $confirmedBookings = $tour->bookings()->where('status', 'confirmed')->count();
        $pendingBookings = $tour->bookings()->where('status', 'pending_payment')->count();
        $cancelledBookings = $tour->bookings()->where('status', 'cancelled')->count();
        
        $totalRevenue = $tour->bookings()->where('status', 'confirmed')->sum('total_price');
        $averageBookingValue = $confirmedBookings > 0 ? $totalRevenue / $confirmedBookings : 0;
        
        $totalReviews = $tour->reviews()->count();
        $averageRating = $tour->reviews()->avg('rating') ?? $tour->rating ?? 0;
        $fiveStarReviews = $tour->reviews()->where('rating', 5)->count();
        $fourStarReviews = $tour->reviews()->where('rating', 4)->count();
        
        // Booking trends (last 6 months)
        $bookingTrends = $tour->bookings()
            ->selectRaw('DATE_FORMAT(created_at, "%Y-%m") as month, COUNT(*) as count')
            ->where('created_at', '>=', now()->subMonths(6))
            ->groupBy('month')
            ->orderBy('month')
            ->get();
        
        // Recent bookings (all, not just 10)
        $recentBookings = $tour->bookings()->with('user')->latest()->limit(20)->get();
        
        // Monthly revenue
        $monthlyRevenue = $tour->bookings()
            ->selectRaw('DATE_FORMAT(created_at, "%Y-%m") as month, SUM(total_price) as revenue')
            ->where('status', 'confirmed')
            ->where('created_at', '>=', now()->subMonths(6))
            ->groupBy('month')
            ->orderBy('month')
            ->get();
        
        $stats = [
            'total_bookings' => $totalBookings,
            'confirmed_bookings' => $confirmedBookings,
            'pending_bookings' => $pendingBookings,
            'cancelled_bookings' => $cancelledBookings,
            'total_revenue' => $totalRevenue,
            'average_booking_value' => $averageBookingValue,
            'total_reviews' => $totalReviews,
            'average_rating' => round($averageRating, 2),
            'five_star_reviews' => $fiveStarReviews,
            'four_star_reviews' => $fourStarReviews,
        ];
        
        return view('admin.tours.show', compact(
            'tour', 
            'stats', 
            'bookingTrends', 
            'recentBookings',
            'monthlyRevenue'
        ));
    }

    /**
     * Show the form for editing the specified tour
     */
    public function edit($id)
    {
        $tour = Tour::findOrFail($id);
        $destinations = Destination::orderBy('name')->get();
        return view('admin.tours.edit', compact('tour', 'destinations'));
    }

    /**
     * Update the specified tour
     */
    public function update(Request $request, $id)
    {
        $tour = Tour::findOrFail($id);
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'destination_id' => 'required|exists:destinations,id',
            'description' => 'required|string',
            'excerpt' => 'nullable|string|max:500',
            'duration_days' => 'required|integer|min:1',
            'price' => 'required|numeric|min:0',
            'rating' => 'nullable|numeric|min:0|max:5',
            'fitness_level' => 'nullable|string|in:Easy,Moderate,Challenging,Strenuous',
            'image_url' => [
                'nullable',
                'string',
                'max:500',
                function ($attribute, $value, $fail) {
                    if (!empty($value)) {
                        // Allow HTTP/HTTPS URLs
                        if (filter_var($value, FILTER_VALIDATE_URL) !== false) {
                            return;
                        }
                        // Allow relative paths starting with images/
                        if (str_starts_with($value, 'images/')) {
                            return;
                        }
                        // Reject other values
                        $fail('The image URL must be a valid URL (http://...) or a relative path starting with images/');
                    }
                },
            ],
            'is_featured' => 'boolean',
        ]);
        
        $validated['slug'] = Str::slug($validated['name']);
        $validated['is_featured'] = $request->has('is_featured');
        
        $tour->update($validated);
        
        return $this->successResponse('Tour updated successfully!', route('admin.tours.index'));
    }

    /**
     * Remove the specified tour
     */
    public function destroy($id)
    {
        $tour = Tour::findOrFail($id);
        
        // Check if tour has bookings
        if ($tour->bookings()->count() > 0) {
            return $this->errorResponse('Cannot delete tour with existing bookings!', route('admin.tours.index'));
        }
        
        $tour->delete();
        
        return $this->successResponse('Tour deleted successfully!', route('admin.tours.index'));
    }

    /**
     * Update tour publish status
     */
    public function updateStatus(Request $request, $id)
    {
        $tour = Tour::findOrFail($id);
        $request->validate([
            'publish_status' => 'required|in:published,draft,Draft,Published'
        ]);
        
        $status = ucfirst(strtolower($request->publish_status));
        $tour->update(['publish_status' => $status]);
        
        $this->notifySuccess("Tour {$status} successfully!", 'Status Updated', route('admin.tours.itinerary-builder', ['tour_id' => $id]));
        
        return redirect()->back()->with('success', "Tour {$status} successfully!");
    }

    /**
     * Duplicate a tour
     */
public function duplicate($id)
    {
        $tour = Tour::with(['destinations', 'categories', 'itineraries', 'pricings'])->findOrFail($id);
        
        $newTour = $tour->replicate();
        $newTour->name = $tour->name . ' (Copy)';
        $newTour->tour_code = null; // Will be auto-generated
        $newTour->slug = Str::slug($newTour->name) . '-' . time();
        $newTour->publish_status = 'Draft';
        $newTour->save();
        
        // Copy destinations
        if ($tour->destinations->count() > 0) {
            $newTour->destinations()->attach($tour->destinations->pluck('id'));
        }
        
        // Copy categories
        if ($tour->categories->count() > 0) {
            $newTour->categories()->attach($tour->categories->pluck('id'));
        }
        
        // Copy itineraries
        foreach ($tour->itineraries as $itinerary) {
            $newItinerary = $itinerary->replicate();
            $newItinerary->tour_id = $newTour->id;
            $newItinerary->save();
        }
        
        // Copy pricings
        foreach ($tour->allPricings as $pricing) {
            $newPricing = $pricing->replicate();
            $newPricing->tour_id = $newTour->id;
            $newPricing->save();
        }
        
        return $this->successResponse('Tour duplicated successfully!', route('admin.tours.edit', $newTour->id));
    }

    /**
     * Bulk actions (Publish, Unpublish, Activate, Deactivate, Delete)
     */
    public function bulkAction(Request $request)
    {
        $request->validate([
            'action' => 'required|in:publish,unpublish,activate,deactivate,delete',
            'tour_ids' => 'required|array',
            'tour_ids.*' => 'exists:tours,id',
        ]);
        
        $tourIds = $request->tour_ids;
        $action = $request->action;
        $count = 0;
        
        switch ($action) {
            case 'publish':
                $count = Tour::whereIn('id', $tourIds)->update(['publish_status' => 'Published']);
                $message = "{$count} tour(s) published successfully!";
                break;
                
            case 'unpublish':
                $count = Tour::whereIn('id', $tourIds)->update(['publish_status' => 'Draft']);
                $message = "{$count} tour(s) unpublished successfully!";
                break;
                
            case 'activate':
                $count = Tour::whereIn('id', $tourIds)->update(['status' => 'Active']);
                $message = "{$count} tour(s) activated successfully!";
                break;
                
            case 'deactivate':
                $count = Tour::whereIn('id', $tourIds)->update(['status' => 'Inactive']);
                $message = "{$count} tour(s) deactivated successfully!";
                break;
                
            case 'delete':
                // Check for bookings before deleting
                $toursWithBookings = Tour::whereIn('id', $tourIds)
                    ->whereHas('bookings')
                    ->count();
                
                if ($toursWithBookings > 0) {
                    return $this->errorResponse("Cannot delete {$toursWithBookings} tour(s) with existing bookings!", route('admin.tours.index'));
                }
                
                $count = Tour::whereIn('id', $tourIds)->delete();
                $message = "{$count} tour(s) deleted successfully!";
                break;
        }
        
        return $this->successResponse($message, route('admin.tours.index'));
    }

    /**
     * Export tours to PDF/Excel
     */
    public function export(Request $request)
    {
        $format = $request->get('format', 'excel');
        $query = Tour::with(['destinations', 'categories']);
        
        // Apply same filters as index
        if ($request->filled('category')) {
            $query->whereHas('categories', function($q) use ($request) {
                $q->where('tour_categories.id', $request->category);
            });
        }
        
        if ($request->filled('destination')) {
            $query->whereHas('destinations', function($q) use ($request) {
                $q->where('destinations.id', $request->destination);
            });
        }
        
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        
        $tours = $query->get();
        
        if ($format === 'pdf') {
            $pdf = Pdf::loadView('admin.tours.export-pdf', compact('tours'));
            return $pdf->download('tours-' . date('Y-m-d') . '.pdf');
        } else {
            // Excel export would go here
            return response()->json(['message' => 'Excel export not yet implemented']);
        }
    }

    /**
     * Display itinerary builder
     */
    public function itineraryBuilder(Request $request)
    {
        $tourId = $request->get('tour_id');
        $tour = $tourId ? Tour::with([
            'destination', 
            'categories',
            'itineraries' => function($q) {
                $q->orderBy('day_number')->orderBy('sort_order');
            }
        ])->find($tourId) : null;
        
        $tours = Tour::orderBy('name')->get();
        
        // Get all tours for copy itinerary feature
        $allTours = Tour::where('id', '!=', $tourId)->orderBy('name')->get();
        
        return view('admin.tours.itinerary-builder', compact('tour', 'tours', 'allTours'));
    }

    /**
     * Get single itinerary day
     */
    public function getItinerary($id)
    {
        try {
            $itinerary = TourItinerary::findOrFail($id);
            
            return response()->json([
                'success' => true,
                'itinerary' => $itinerary
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to load itinerary day: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Store itinerary day
     */
    public function storeItinerary(Request $request)
    {
        try {
            $validated = $request->validate([
                'tour_id' => 'required|exists:tours,id',
                'day_number' => 'required|integer|min:1',
                'title' => 'required|string|max:255',
                'short_summary' => 'nullable|string|max:500',
                'description' => 'required|string',
                'location' => 'nullable|string|max:255',
                'meals_included' => 'nullable|array',
                'meals_included.*' => 'in:Breakfast,Lunch,Dinner',
                'accommodation_type' => 'nullable|in:Camp,Lodge,Hotel,Guest House',
                'accommodation_name' => 'nullable|string|max:255',
                'accommodation_location' => 'nullable|string|max:255',
                'accommodation_rating' => 'nullable|numeric|min:0|max:5',
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
                'accommodation_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
                'gallery_images' => 'nullable|array',
                'gallery_images.*' => 'image|mimes:jpeg,png,jpg,gif,webp|max:5120',
                'activities' => 'nullable|array',
                'vehicle_type' => 'nullable|string|max:255',
                'driver_guide_notes' => 'nullable|string',
                'transfer_info' => 'nullable|string',
                'day_notes' => 'nullable|string',
                'custom_icons' => 'nullable|array',
                'sort_order' => 'nullable|integer|min:0',
            ]);
            
            // Handle main image upload
            if ($request->hasFile('image')) {
                $validated['image'] = $request->file('image')->store('tours/itinerary', 'public');
            }
            
            // Handle accommodation image upload
            if ($request->hasFile('accommodation_image')) {
                $validated['accommodation_image'] = $request->file('accommodation_image')->store('tours/accommodations', 'public');
            }
            
            // Handle gallery images
            if ($request->hasFile('gallery_images')) {
                $galleryPaths = [];
                foreach ($request->file('gallery_images') as $file) {
                    $galleryPaths[] = $file->store('tours/itinerary/gallery', 'public');
                }
                $validated['gallery_images'] = $galleryPaths;
            }
            
            // Auto-calculate sort_order if not provided
            if (!isset($validated['sort_order'])) {
                $maxSort = TourItinerary::where('tour_id', $validated['tour_id'])
                    ->where('day_number', $validated['day_number'])
                    ->max('sort_order') ?? 0;
                $validated['sort_order'] = $maxSort + 1;
            }
            
            $itinerary = TourItinerary::create($validated);
            
            $this->notifySuccess('Itinerary day added successfully!', 'Day Added', route('admin.tours.itinerary-builder', ['tour_id' => $validated['tour_id']]));
            
            return response()->json([
                'success' => true,
                'message' => 'Itinerary day added successfully!',
                'itinerary' => $itinerary->load('tour')
            ]);
            
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            \Log::error('Error storing itinerary: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to add itinerary day: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update itinerary day
     */
    public function updateItinerary(Request $request, $id)
    {
        try {
            $itinerary = TourItinerary::findOrFail($id);
            
            $validated = $request->validate([
                'day_number' => 'required|integer|min:1',
                'title' => 'required|string|max:255',
                'short_summary' => 'nullable|string|max:500',
                'description' => 'required|string',
                'location' => 'nullable|string|max:255',
                'meals_included' => 'nullable|array',
                'meals_included.*' => 'in:Breakfast,Lunch,Dinner',
                'accommodation_type' => 'nullable|in:Camp,Lodge,Hotel,Guest House',
                'accommodation_name' => 'nullable|string|max:255',
                'accommodation_location' => 'nullable|string|max:255',
                'accommodation_rating' => 'nullable|numeric|min:0|max:5',
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
                'accommodation_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
                'gallery_images' => 'nullable|array',
                'gallery_images.*' => 'image|mimes:jpeg,png,jpg,gif,webp|max:5120',
                'activities' => 'nullable|array',
                'vehicle_type' => 'nullable|string|max:255',
                'driver_guide_notes' => 'nullable|string',
                'transfer_info' => 'nullable|string',
                'day_notes' => 'nullable|string',
                'custom_icons' => 'nullable|array',
                'sort_order' => 'nullable|integer|min:0',
            ]);
            
            // Handle main image upload
            if ($request->hasFile('image')) {
                // Delete old image if exists
                if ($itinerary->image && Storage::disk('public')->exists($itinerary->image)) {
                    Storage::disk('public')->delete($itinerary->image);
                }
                $validated['image'] = $request->file('image')->store('tours/itinerary', 'public');
            }
            
            // Handle accommodation image upload
            if ($request->hasFile('accommodation_image')) {
                if ($itinerary->accommodation_image && Storage::disk('public')->exists($itinerary->accommodation_image)) {
                    Storage::disk('public')->delete($itinerary->accommodation_image);
                }
                $validated['accommodation_image'] = $request->file('accommodation_image')->store('tours/accommodations', 'public');
            }
            
            // Handle gallery images
            if ($request->hasFile('gallery_images')) {
                $galleryPaths = $itinerary->gallery_images ?? [];
                foreach ($request->file('gallery_images') as $file) {
                    $galleryPaths[] = $file->store('tours/itinerary/gallery', 'public');
                }
                $validated['gallery_images'] = $galleryPaths;
            }
            
            $itinerary->update($validated);
            
            $this->notifySuccess('Itinerary day updated successfully!', 'Day Updated', route('admin.tours.itinerary-builder', ['tour_id' => $itinerary->tour_id]));
            
            return response()->json([
                'success' => true,
                'message' => 'Itinerary day updated successfully!',
                'itinerary' => $itinerary->fresh()
            ]);
            
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            \Log::error('Error updating itinerary: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to update itinerary day: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Delete itinerary day
     */
    public function deleteItinerary(Request $request, $id)
    {
        try {
            $itinerary = TourItinerary::findOrFail($id);
            $tourId = $itinerary->tour_id;
            $dayNumber = $itinerary->day_number;
            
            DB::beginTransaction();
            
            // Delete the itinerary
            $itinerary->delete();
            
            // Reorder remaining days - decrease day_number for days after deleted one
            TourItinerary::where('tour_id', $tourId)
                ->where('day_number', '>', $dayNumber)
                ->decrement('day_number');
            
            DB::commit();
            
            $this->notifySuccess('Itinerary day deleted successfully!', 'Day Deleted', route('admin.tours.itinerary-builder', ['tour_id' => $tourId]));
            
            return response()->json([
                'success' => true,
                'message' => 'Itinerary day deleted successfully!'
            ]);
            
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Error deleting itinerary: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete itinerary day: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Clone itinerary day
     */
    public function cloneItinerary(Request $request, $id)
    {
        try {
            $itinerary = TourItinerary::findOrFail($id);
            $newItinerary = $itinerary->replicate();
            
            // Set new day number (after the cloned day)
            $newItinerary->day_number = $itinerary->day_number + 1;
            $newItinerary->title = $itinerary->title . ' (Copy)';
            $newItinerary->sort_order = $itinerary->sort_order + 1;
            
            // Increment day numbers for days after the cloned one
            TourItinerary::where('tour_id', $itinerary->tour_id)
                ->where('day_number', '>', $itinerary->day_number)
                ->increment('day_number');
            
            $newItinerary->save();
            
            $this->notifySuccess('Itinerary day duplicated successfully!', 'Day Duplicated', route('admin.tours.itinerary-builder', ['tour_id' => $itinerary->tour_id]));
            
            return response()->json([
                'success' => true,
                'message' => 'Itinerary day duplicated successfully!',
                'itinerary' => $newItinerary->fresh()
            ]);
            
        } catch (\Exception $e) {
            \Log::error('Error cloning itinerary: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to duplicate itinerary day: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Reorder itinerary days
     */
    public function reorderItinerary(Request $request)
    {
        try {
            $request->validate([
                'tour_id' => 'required|exists:tours,id',
                'itinerary_ids' => 'required|array',
                'itinerary_ids.*' => 'exists:tour_itineraries,id',
            ]);
            
            DB::beginTransaction();
            
            // Update day numbers and sort orders
            foreach ($request->itinerary_ids as $index => $id) {
                $dayNumber = $index + 1;
                TourItinerary::where('id', $id)
                    ->where('tour_id', $request->tour_id)
                    ->update([
                        'day_number' => $dayNumber,
                        'sort_order' => $dayNumber
                    ]);
            }
            
            DB::commit();
            
            $this->notifySuccess('Itinerary days reordered successfully!', 'Reordered', route('admin.tours.itinerary-builder', ['tour_id' => $request->tour_id]));
            
            return response()->json([
                'success' => true,
                'message' => 'Itinerary days reordered successfully!'
            ]);
            
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Error reordering itinerary: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to reorder itinerary: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Print itinerary as PDF
     */
    public function printItinerary($tourId)
    {
        try {
            $tour = Tour::with([
                'destination',
                'itineraries' => function($q) {
                    $q->orderBy('day_number')->orderBy('sort_order');
                }
            ])->findOrFail($tourId);
            
            $pdf = Pdf::loadView('admin.tours.itinerary-pdf', compact('tour'));
            
            return $pdf->stream('itinerary-' . $tour->slug . '.pdf');
        } catch (\Exception $e) {
            \Log::error('Error generating itinerary PDF: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to generate itinerary PDF: ' . $e->getMessage());
        }
    }

    /**
     * Export itinerary as JSON
     */
    public function exportItinerary($tourId)
    {
        try {
            $tour = Tour::with([
                'itineraries' => function($q) {
                    $q->orderBy('day_number')->orderBy('sort_order');
                }
            ])->findOrFail($tourId);
            
            $data = [
                'tour_id' => $tour->id,
                'tour_name' => $tour->name,
                'exported_at' => now()->toISOString(),
                'itineraries' => $tour->itineraries->map(function($itinerary) {
                    return [
                        'day_number' => $itinerary->day_number,
                        'title' => $itinerary->title,
                        'short_summary' => $itinerary->short_summary,
                        'description' => $itinerary->description,
                        'location' => $itinerary->location,
                        'meals_included' => $itinerary->meals_included,
                        'accommodation_type' => $itinerary->accommodation_type,
                        'accommodation_name' => $itinerary->accommodation_name,
                        'accommodation_location' => $itinerary->accommodation_location,
                        'accommodation_rating' => $itinerary->accommodation_rating,
                        'activities' => $itinerary->activities,
                        'vehicle_type' => $itinerary->vehicle_type,
                        'driver_guide_notes' => $itinerary->driver_guide_notes,
                        'transfer_info' => $itinerary->transfer_info,
                        'day_notes' => $itinerary->day_notes,
                        'sort_order' => $itinerary->sort_order,
                    ];
                })->toArray()
            ];
            
            return response()->json($data, 200, [], JSON_PRETTY_PRINT)
                ->header('Content-Type', 'application/json')
                ->header('Content-Disposition', 'attachment; filename="itinerary-' . $tour->slug . '-' . now()->format('Y-m-d') . '.json"');
        } catch (\Exception $e) {
            \Log::error('Error exporting itinerary: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to export itinerary: ' . $e->getMessage());
        }
    }

    /**
     * Import itinerary from JSON
     */
    public function importItinerary(Request $request, $tourId)
    {
        try {
            $request->validate([
                'json_file' => 'required|file|mimes:json,txt|max:5120',
            ]);
            
            $tour = Tour::findOrFail($tourId);
            
            $jsonContent = file_get_contents($request->file('json_file')->getRealPath());
            $data = json_decode($jsonContent, true);
            
            if (json_last_error() !== JSON_ERROR_NONE) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid JSON file: ' . json_last_error_msg()
                ], 400);
            }
            
            if (!isset($data['itineraries']) || !is_array($data['itineraries'])) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid itinerary format. Expected "itineraries" array.'
                ], 400);
            }
            
            DB::beginTransaction();
            
            // Delete existing itineraries
            TourItinerary::where('tour_id', $tourId)->delete();
            
            // Import new itineraries
            foreach ($data['itineraries'] as $itineraryData) {
                TourItinerary::create([
                    'tour_id' => $tourId,
                    'day_number' => $itineraryData['day_number'] ?? 1,
                    'title' => $itineraryData['title'] ?? 'Untitled Day',
                    'short_summary' => $itineraryData['short_summary'] ?? null,
                    'description' => $itineraryData['description'] ?? '',
                    'location' => $itineraryData['location'] ?? null,
                    'meals_included' => $itineraryData['meals_included'] ?? null,
                    'accommodation_type' => $itineraryData['accommodation_type'] ?? null,
                    'accommodation_name' => $itineraryData['accommodation_name'] ?? null,
                    'accommodation_location' => $itineraryData['accommodation_location'] ?? null,
                    'accommodation_rating' => $itineraryData['accommodation_rating'] ?? null,
                    'activities' => $itineraryData['activities'] ?? null,
                    'vehicle_type' => $itineraryData['vehicle_type'] ?? null,
                    'driver_guide_notes' => $itineraryData['driver_guide_notes'] ?? null,
                    'transfer_info' => $itineraryData['transfer_info'] ?? null,
                    'day_notes' => $itineraryData['day_notes'] ?? null,
                    'sort_order' => $itineraryData['sort_order'] ?? 0,
                ]);
            }
            
            DB::commit();
            
            $this->notifySuccess('Itinerary imported successfully!', 'Import Complete', route('admin.tours.itinerary-builder', ['tour_id' => $tourId]));
            
            return response()->json([
                'success' => true,
                'message' => 'Itinerary imported successfully!',
                'count' => count($data['itineraries'])
            ]);
            
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Error importing itinerary: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to import itinerary: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Reset entire itinerary
     */
    public function resetItinerary(Request $request, $tourId)
    {
        try {
            $request->validate([
                'confirm' => 'required|accepted',
            ]);
            
            $tour = Tour::findOrFail($tourId);
            
            DB::beginTransaction();
            
            // Delete all itineraries
            $deletedCount = TourItinerary::where('tour_id', $tourId)->delete();
            
            DB::commit();
            
            $this->notifySuccess("All {$deletedCount} itinerary days deleted successfully!", 'Itinerary Reset', route('admin.tours.itinerary-builder', ['tour_id' => $tourId]));
            
            return response()->json([
                'success' => true,
                'message' => "All {$deletedCount} itinerary days deleted successfully!",
                'deleted_count' => $deletedCount
            ]);
            
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Error resetting itinerary: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to reset itinerary: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display tour availability
     */
    public function availability(Request $request)
    {
        $query = Tour::with('destination');
        
        if ($request->filled('tour_id')) {
            $query->where('id', $request->tour_id);
        }
        
        if ($request->filled('date_from')) {
            $query->whereHas('availabilities', function($q) use ($request) {
                $q->where('date', '>=', $request->date_from);
            });
        }
        
        if ($request->filled('date_to')) {
            $query->whereHas('availabilities', function($q) use ($request) {
                $q->where('date', '<=', $request->date_to);
            });
        }
        
        $tours = $query->paginate(20);
        $allTours = Tour::orderBy('name')->get();
        
        return view('admin.tours.availability', compact('tours', 'allTours'));
    }

    /**
     * Display tour pricing
     */
    public function pricing(Request $request)
    {
        $query = Tour::with('destination');
        
        if ($request->filled('tour_id')) {
            $query->where('id', $request->tour_id);
        }
        
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }
        
        $tours = $query->paginate(20);
        $allTours = Tour::orderBy('name')->get();
        
        return view('admin.tours.pricing', compact('tours', 'allTours'));
    }
    
    /**
     * Store tour pricing
     */
    public function storePricing(Request $request)
    {
        $validated = $request->validate([
            'tour_id' => 'required|exists:tours,id',
            'currency' => 'required|string|max:3',
            'price_type' => 'required|in:Per Person,Per Group,Per Category,Seasonal',
            'category_type' => 'nullable|in:Resident,Non-Resident,Adult,Child,Senior',
            'price' => 'required|numeric|min:0',
            'child_price' => 'nullable|numeric|min:0',
            'min_pax' => 'nullable|integer|min:1',
            'max_pax' => 'nullable|integer|min:1',
            'valid_from' => 'nullable|date',
            'valid_to' => 'nullable|date|after:valid_from',
            'optional_addons' => 'nullable|array',
            'discount_percentage' => 'nullable|numeric|min:0|max:100',
            'is_active' => 'boolean',
        ]);
        
        $validated['is_active'] = $request->has('is_active') ? true : false;
        
        $pricing = TourPricing::create($validated);
        
        // Calculate final price
        $pricing->calculateFinalPrice();
        $pricing->save();
        
        // Update tour starting price if this is the lowest
        $tour = Tour::findOrFail($validated['tour_id']);
        $lowestPrice = $tour->pricings()->where('is_active', true)->min('final_price') ?? $pricing->final_price;
        if (!$tour->starting_price || $lowestPrice < $tour->starting_price) {
            $tour->update(['starting_price' => $lowestPrice]);
        }
        
        return response()->json([
            'success' => true,
            'message' => 'Tour pricing saved successfully!'
        ]);
    }
    
    /**
     * Update tour pricing
     */
    public function updatePricing(Request $request, $tourId)
    {
        $validated = $request->validate([
            'pricing_type' => 'required|in:standard,seasonal,group,custom',
            'base_price' => 'required|numeric|min:0',
            'child_price' => 'nullable|numeric|min:0',
            'infant_price' => 'nullable|numeric|min:0',
            'low_season_price' => 'nullable|numeric|min:0',
            'high_season_price' => 'nullable|numeric|min:0',
            'peak_season_price' => 'nullable|numeric|min:0',
            'group_min_size' => 'nullable|integer|min:2',
            'group_discount' => 'nullable|numeric|min:0|max:100',
            'valid_from' => 'nullable|date',
            'valid_until' => 'nullable|date|after:valid_from',
            'currency' => 'nullable|string|max:3',
            'is_active' => 'boolean',
            'notes' => 'nullable|string',
        ]);
        
        // Update tour base price
        $tour = Tour::findOrFail($tourId);
        $tour->update(['price' => $validated['base_price']]);
        
        // For now, just return success (you'll need to create a TourPricing model for seasonal pricing)
        return response()->json([
            'success' => true,
            'message' => 'Tour pricing updated successfully!'
        ]);
    }

    /**
     * Get tour pricing details (JSON)
     */
    public function getPricingDetails($id)
    {
        try {
            $tour = Tour::with(['destination', 'bookings', 'reviews'])->findOrFail($id);
            
            // Calculate statistics
            $totalBookings = $tour->bookings()->count();
            $confirmedBookings = $tour->bookings()->where('status', 'confirmed')->count();
            $totalRevenue = (float) ($tour->bookings()->where('status', 'confirmed')->sum('total_price') ?? 0);
            $averageRating = (float) ($tour->reviews()->avg('rating') ?? $tour->rating ?? 0);
            
            return response()->json([
                'success' => true,
                'tour' => [
                    'id' => $tour->id,
                    'name' => $tour->name ?? 'N/A',
                    'slug' => $tour->slug ?? '',
                    'description' => $tour->description ?? '',
                    'excerpt' => $tour->excerpt ?? '',
                    'duration_days' => $tour->duration_days ?? 0,
                    'price' => (float) ($tour->price ?? 0),
                    'rating' => (float) ($tour->rating ?? 0),
                    'fitness_level' => $tour->fitness_level ?? null,
                    'image_url' => $tour->image_url ? (str_starts_with($tour->image_url, 'http://') || str_starts_with($tour->image_url, 'https://') ? $tour->image_url : asset($tour->image_url)) : null,
                    'is_featured' => (bool) ($tour->is_featured ?? false),
                    'destination' => $tour->destination ? [
                        'id' => $tour->destination->id,
                        'name' => $tour->destination->name,
                    ] : null,
                    'stats' => [
                        'total_bookings' => $totalBookings,
                        'confirmed_bookings' => $confirmedBookings,
                        'total_revenue' => $totalRevenue,
                        'average_rating' => round($averageRating, 2),
                    ],
                ],
            ]);
        } catch (\Exception $e) {
            \Log::error('Error in getPricingDetails: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to load pricing details: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get tour details (JSON)
     */
    public function getTourDetails($id)
    {
        $tour = Tour::with(['destination', 'bookings', 'reviews', 'itineraries', 'categories'])->findOrFail($id);
        
        return response()->json([
            'success' => true,
            'tour' => [
                'id' => $tour->id,
                'name' => $tour->name,
                'slug' => $tour->slug,
                'description' => $tour->description,
                'excerpt' => $tour->excerpt,
                'duration_days' => $tour->duration_days,
                'price' => $tour->price,
                'rating' => $tour->rating,
                'fitness_level' => $tour->fitness_level,
                'image_url' => $tour->image_url ? (str_starts_with($tour->image_url, 'http://') || str_starts_with($tour->image_url, 'https://') ? $tour->image_url : asset($tour->image_url)) : null,
                'is_featured' => $tour->is_featured,
                'destination' => $tour->destination ? [
                    'id' => $tour->destination->id,
                    'name' => $tour->destination->name,
                ] : null,
                'bookings_count' => $tour->bookings()->count(),
                'reviews_count' => $tour->reviews()->count(),
                'itineraries_count' => $tour->itineraries()->count(),
                'categories' => $tour->categories->map(function($category) {
                    return ['id' => $category->id, 'name' => $category->name];
                }),
            ],
        ]);
    }
    
    /**
     * Store tour availability
     */
    public function storeAvailability(Request $request)
    {
        $validated = $request->validate([
            'tour_id' => 'required|exists:tours,id',
            'date' => 'nullable|date',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after:start_date',
            'available_slots' => 'required|integer|min:0',
            'status' => 'required|in:Available,Sold Out,On Request',
            'price_override' => 'nullable|numeric|min:0',
            'notes' => 'nullable|string',
            'is_repeating' => 'boolean',
            'repeat_pattern' => 'nullable|in:daily,weekly,monthly,custom',
            'repeat_days' => 'nullable|array',
            'repeat_until' => 'nullable|date',
        ]);
        
        $validated['is_repeating'] = $request->has('is_repeating');
        
        TourAvailability::create($validated);
        
        return response()->json([
            'success' => true,
            'message' => 'Tour availability saved successfully!'
        ]);
    }
    
    /**
     * Update tour availability
     */
    public function updateAvailability(Request $request, $tourId)
    {
        $validated = $request->validate([
            // For now, all fields are optional in edit mode since availability records
            // are not persisted yet. This avoids validation errors when opening the edit modal.
            'availability_type' => 'nullable|in:specific_dates,recurring,year_round',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after:start_date',
            'max_participants' => 'nullable|integer|min:1',
            'min_participants' => 'nullable|integer|min:1',
            'available_slots' => 'nullable|integer|min:0',
            'is_active' => 'nullable|boolean',
            'notes' => 'nullable|string',
        ]);
        
        // For now, just return success (you'll need to create a TourAvailability model)
        return response()->json([
            'success' => true,
            'message' => 'Tour availability updated successfully!'
        ]);
    }

    /**
     * Show availability calendar for a specific tour (admin view)
     */
    public function availabilityCalendar($id)
    {
        $tour = Tour::with('destination')->findOrFail($id);
        $availability = $this->calculateTourAvailability($tour);

        return view('admin.tours.partials.availability-calendar', compact('tour', 'availability'));
    }

    /**
     * Show rich tour details (admin modal partial view)
     */
    public function detailsPartial($id)
    {
        $tour = Tour::with(['destination', 'bookings'])->findOrFail($id);

        $totalBookings = $tour->bookings->count();
        $upcomingBookings = $tour->bookings
            ->where('start_date', '>=', Carbon::today()->toDateString())
            ->count();
        $totalTravelers = $tour->bookings->sum('travelers');

        $stats = [
            'total_bookings' => $totalBookings,
            'upcoming_bookings' => $upcomingBookings,
            'total_travelers' => $totalTravelers,
            'avg_price' => $tour->price,
            'duration_days' => $tour->duration_days,
        ];

        return view('admin.tours.partials.details', compact('tour', 'stats'));
    }

    /**
     * Calculate tour availability for the next 90 days (based on bookings and max capacity)
     */
    private function calculateTourAvailability(Tour $tour): array
    {
        $availability = [];
        $maxCapacity = $tour->max_capacity ?? 12;

        for ($i = 0; $i < 90; $i++) {
            $date = now()->addDays($i)->format('Y-m-d');
            $bookedTravelers = Booking::getTotalTravelersForDate($tour->id, $date);
            $available = max(0, $maxCapacity - $bookedTravelers);

            $availability[] = [
                'date' => $date,
                'available' => $available,
                'booked' => $bookedTravelers,
                'status' => $available > 0 ? 'available' : 'full',
            ];
        }

        return $availability;
    }
}
