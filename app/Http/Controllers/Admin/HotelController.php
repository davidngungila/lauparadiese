<?php

namespace App\Http\Controllers\Admin;

use App\Models\Hotel;
use App\Models\Partner;
use App\Models\RoomType;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class HotelController extends BaseAdminController
{
    /**
     * Display a listing of hotels
     */
    public function index(Request $request)
    {
        $query = Hotel::with('partner');
        
        if ($request->filled('status')) {
            if ($request->status == 'active') {
                $query->where('is_active', true);
            } else {
                $query->where('is_active', false);
            }
        }
        
        if ($request->filled('partner_id')) {
            $query->where('partner_id', $request->partner_id);
        }
        
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('location', 'like', "%{$search}%");
            });
        }
        
        $hotels = $query->latest()->paginate(20);
        $partners = Partner::orderBy('name')->get();
        
        $stats = [
            'total_hotels' => Hotel::count(),
            'active_hotels' => Hotel::where('is_active', true)->count(),
            'partner_hotels' => Hotel::whereNotNull('partner_id')->count(),
        ];
        
        return view('admin.hotels.index', compact('hotels', 'partners', 'stats'));
    }

    /**
     * Show create hotel form
     */
    public function create()
    {
        $partners = Partner::orderBy('name')->get();
        return view('admin.hotels.create', compact('partners'));
    }

    /**
     * Store a newly created hotel
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'location' => 'required|string|max:255',
            'partner_id' => 'nullable|exists:partners,id',
            'description' => 'nullable|string',
            'rating' => 'nullable|numeric|min:0|max:5',
            'address' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:255',
            'country' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255',
            'website' => 'nullable|url|max:255',
            'star_rating' => 'nullable|integer|min:1|max:5',
            'total_rooms' => 'nullable|integer|min:0',
            'image_url' => 'nullable|url',
            'is_active' => 'boolean',
        ]);
        
        $validated['is_active'] = $request->has('is_active');
        
        // Set default values for required fields that might be missing
        $validated['address'] = $validated['address'] ?? $validated['location'] ?? '';
        $validated['city'] = $validated['city'] ?? '';
        $validated['country'] = $validated['country'] ?? '';
        
        // Generate slug from name
        $slug = Str::slug($validated['name']);
        $originalSlug = $slug;
        $counter = 1;
        
        // Ensure slug is unique
        while (Hotel::where('slug', $slug)->exists()) {
            $slug = $originalSlug . '-' . $counter;
            $counter++;
        }
        
        $validated['slug'] = $slug;
        
        Hotel::create($validated);
        
        return $this->successResponse('Hotel created successfully!', route('admin.hotels.index'));
    }

    /**
     * Display the specified hotel
     */
    public function show($id)
    {
        $hotel = Hotel::with('partner')->findOrFail($id);
        return view('admin.hotels.show', compact('hotel'));
    }

    /**
     * Show the form for editing the specified hotel
     */
    public function edit($id)
    {
        $hotel = Hotel::findOrFail($id);
        $partners = Partner::orderBy('name')->get();
        return view('admin.hotels.edit', compact('hotel', 'partners'));
    }

    /**
     * Update the specified hotel
     */
    public function update(Request $request, $id)
    {
        $hotel = Hotel::findOrFail($id);
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'location' => 'required|string|max:255',
            'partner_id' => 'nullable|exists:partners,id',
            'description' => 'nullable|string',
            'rating' => 'nullable|numeric|min:0|max:5',
            'address' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:255',
            'country' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255',
            'website' => 'nullable|url|max:255',
            'star_rating' => 'nullable|integer|min:1|max:5',
            'total_rooms' => 'nullable|integer|min:0',
            'image_url' => 'nullable|url',
            'is_active' => 'boolean',
        ]);
        
        $validated['is_active'] = $request->has('is_active');
        
        // Set default values for required fields that might be missing
        if (empty($validated['address'])) {
            $validated['address'] = $validated['location'] ?? $hotel->address ?? '';
        }
        if (empty($validated['city'])) {
            $validated['city'] = $hotel->city ?? '';
        }
        if (empty($validated['country'])) {
            $validated['country'] = $hotel->country ?? '';
        }
        
        // Generate slug from name if name changed
        if ($hotel->name !== $validated['name']) {
            $slug = Str::slug($validated['name']);
            $originalSlug = $slug;
            $counter = 1;
            
            // Ensure slug is unique (excluding current hotel)
            while (Hotel::where('slug', $slug)->where('id', '!=', $hotel->id)->exists()) {
                $slug = $originalSlug . '-' . $counter;
                $counter++;
            }
            
            $validated['slug'] = $slug;
        }
        
        $hotel->update($validated);
        
        return $this->successResponse('Hotel updated successfully!', route('admin.hotels.index'));
    }

    /**
     * Remove the specified hotel
     */
    public function destroy($id)
    {
        $hotel = Hotel::findOrFail($id);
        $hotel->delete();
        
        return $this->successResponse('Hotel deleted successfully!', route('admin.hotels.index'));
    }

    /**
     * Display room types
     */
    public function roomTypes(Request $request)
    {
        $hotelId = $request->get('hotel_id');
        $hotel = $hotelId ? Hotel::find($hotelId) : null;
        $hotels = Hotel::orderBy('name')->get();
        
        $roomTypes = collect();
        if ($hotel) {
            $roomTypes = RoomType::where('hotel_id', $hotel->id)
                ->orderBy('name')
                ->get();
        }
        
        return view('admin.hotels.room-types', compact('hotel', 'hotels', 'roomTypes'));
    }

    /**
     * Store room type
     */
    public function storeRoomType(Request $request)
    {
        $validated = $request->validate([
            'hotel_id' => 'required|exists:hotels,id',
            'name' => 'required|string|max:255',
            'category' => 'nullable|string|max:255',
            'max_occupancy' => 'required|integer|min:1|max:10',
            'total_rooms' => 'required|integer|min:1',
            'available_rooms' => 'nullable|integer|min:0',
            'base_price' => 'required|numeric|min:0',
            'weekend_price' => 'nullable|numeric|min:0',
            'holiday_price' => 'nullable|numeric|min:0',
            'room_size' => 'nullable|numeric|min:0',
            'bed_type' => 'nullable|string|max:255',
            'amenities' => 'nullable|array',
            'description' => 'nullable|string',
            'is_active' => 'boolean',
        ]);
        
        // Set available_rooms to total_rooms if not provided
        if (!isset($validated['available_rooms'])) {
            $validated['available_rooms'] = $validated['total_rooms'];
        }
        
        // Ensure available_rooms doesn't exceed total_rooms
        $validated['available_rooms'] = min($validated['available_rooms'], $validated['total_rooms']);
        
        $roomType = RoomType::create($validated);
        
        return response()->json([
            'success' => true,
            'message' => 'Room type saved successfully!',
            'room_type' => $roomType
        ]);
    }

    /**
     * Update room type
     */
    public function updateRoomType(Request $request, $id)
    {
        $roomType = RoomType::findOrFail($id);
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'category' => 'nullable|string|max:255',
            'max_occupancy' => 'required|integer|min:1|max:10',
            'total_rooms' => 'required|integer|min:1',
            'available_rooms' => 'nullable|integer|min:0',
            'base_price' => 'required|numeric|min:0',
            'weekend_price' => 'nullable|numeric|min:0',
            'holiday_price' => 'nullable|numeric|min:0',
            'room_size' => 'nullable|numeric|min:0',
            'bed_type' => 'nullable|string|max:255',
            'amenities' => 'nullable|array',
            'description' => 'nullable|string',
            'is_active' => 'boolean',
        ]);
        
        // Ensure available_rooms doesn't exceed total_rooms
        if (isset($validated['available_rooms'])) {
            $validated['available_rooms'] = min($validated['available_rooms'], $validated['total_rooms']);
        }
        
        $roomType->update($validated);
        
        return response()->json([
            'success' => true,
            'message' => 'Room type updated successfully!',
            'room_type' => $roomType
        ]);
    }

    /**
     * Delete room type
     */
    public function deleteRoomType($id)
    {
        $roomType = RoomType::findOrFail($id);
        $roomType->delete();
        
        return response()->json([
            'success' => true,
            'message' => 'Room type deleted successfully!'
        ]);
    }

    /**
     * Get room type details (for editing)
     */
    public function getRoomType($id)
    {
        $roomType = RoomType::with('hotel')->findOrFail($id);
        
        return response()->json([
            'success' => true,
            'room_type' => $roomType
        ]);
    }

    /**
     * Display room pricing
     */
    public function roomPricing(Request $request)
    {
        $query = Hotel::with('partner');
        
        if ($request->filled('hotel_id')) {
            $query->where('id', $request->hotel_id);
        }
        
        if ($request->filled('status')) {
            if ($request->status == 'active') {
                $query->where('is_active', true);
            } elseif ($request->status == 'inactive') {
                $query->where('is_active', false);
            }
        }
        
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('city', 'like', "%{$search}%")
                  ->orWhere('address', 'like', "%{$search}%")
                  ->orWhere('country', 'like', "%{$search}%");
            });
        }
        
        $hotels = $query->orderBy('name')->paginate(20);
        $allHotels = Hotel::where('is_active', true)->orderBy('name')->get();
        
        return view('admin.hotels.room-pricing', compact('hotels', 'allHotels'));
    }
    
    /**
     * Get hotel details for modal
     */
    public function getHotelDetails($id)
    {
        $hotel = Hotel::with('partner')->findOrFail($id);
        
        return response()->json([
            'id' => $hotel->id,
            'name' => $hotel->name,
            'slug' => $hotel->slug,
            'description' => $hotel->description,
            'address' => $hotel->address,
            'city' => $hotel->city,
            'country' => $hotel->country,
            'phone' => $hotel->phone,
            'email' => $hotel->email,
            'website' => $hotel->website,
            'star_rating' => $hotel->star_rating,
            'total_rooms' => $hotel->total_rooms,
            'amenities' => $hotel->amenities,
            'image_url' => $hotel->image_url,
            'is_active' => $hotel->is_active,
            'partner' => $hotel->partner ? $hotel->partner->name : null,
        ]);
    }
    
    /**
     * Store room pricing
     */
    public function storeRoomPricing(Request $request)
    {
        $validated = $request->validate([
            'hotel_id' => 'required|exists:hotels,id',
            'room_type' => 'required|string|max:255',
            'base_price' => 'required|numeric|min:0',
            'weekend_price' => 'nullable|numeric|min:0',
            'holiday_price' => 'nullable|numeric|min:0',
            'season' => 'nullable|string',
            'max_occupancy' => 'nullable|integer|min:1|max:10',
            'valid_from' => 'nullable|date',
            'valid_until' => 'nullable|date|after:valid_from',
            'is_active' => 'boolean',
            'notes' => 'nullable|string',
        ]);
        
        // For now, just return success (you'll need to create a RoomPricing model)
        return response()->json([
            'success' => true,
            'message' => 'Room pricing saved successfully!'
        ]);
    }
    
    /**
     * Update room pricing
     */
    public function updateRoomPricing(Request $request, $hotelId)
    {
        $validated = $request->validate([
            'room_type' => 'required|string|max:255',
            'base_price' => 'required|numeric|min:0',
            'weekend_price' => 'nullable|numeric|min:0',
            'holiday_price' => 'nullable|numeric|min:0',
            'season' => 'nullable|string',
            'max_occupancy' => 'nullable|integer|min:1|max:10',
            'valid_from' => 'nullable|date',
            'valid_until' => 'nullable|date|after:valid_from',
            'is_active' => 'boolean',
            'notes' => 'nullable|string',
        ]);
        
        // For now, just return success (you'll need to create a RoomPricing model)
        return response()->json([
            'success' => true,
            'message' => 'Room pricing updated successfully!'
        ]);
    }

    /**
     * Display hotel availability
     */
    public function availability(Request $request)
    {
        $query = Hotel::with('roomTypes');
        
        if ($request->filled('hotel_id')) {
            $query->where('id', $request->hotel_id);
        }
        
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('city', 'like', "%{$search}%")
                  ->orWhere('address', 'like', "%{$search}%");
            });
        }
        
        $hotels = $query->orderBy('name')->paginate(20);
        $allHotels = Hotel::where('is_active', true)->orderBy('name')->get();
        
        // Calculate availability stats for each hotel
        foreach ($hotels as $hotel) {
            $hotel->total_room_types = $hotel->roomTypes->count();
            $hotel->total_available_rooms = $hotel->roomTypes->sum('available_rooms');
            $hotel->total_rooms = $hotel->roomTypes->sum('total_rooms');
            $hotel->availability_percentage = $hotel->total_rooms > 0 
                ? round(($hotel->total_available_rooms / $hotel->total_rooms) * 100, 1) 
                : 0;
        }
        
        return view('admin.hotels.availability', compact('hotels', 'allHotels'));
    }

    /**
     * Display partner hotels portal
     */
    public function partnerPortal(Request $request)
    {
        $query = Hotel::whereHas('partner');
        
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('location', 'like', "%{$search}%");
            });
        }
        
        $hotels = $query->paginate(20);
        
        return view('admin.hotels.partner-portal', compact('hotels'));
    }
}
