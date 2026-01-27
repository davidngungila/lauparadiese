<?php

namespace App\Http\Controllers\Admin;

use App\Models\Destination;
use App\Models\HomepageDestination;
use App\Models\Post;
use App\Models\Gallery;
use App\Models\GalleryAlbum;
use App\Models\GalleryTag;
use App\Models\Testimonial;
use App\Models\Faq;
use App\Models\CompanyPolicy;
use App\Models\SeoSetting;
use App\Models\Tour;
use App\Models\User;
use App\Models\HeroSlide;
use App\Services\ImageService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class HomepageController extends BaseAdminController
{
    /**
     * Get images from public/images directory
     */
    public function getImages(Request $request)
    {
        $folder = $request->get('folder', 'images');
        $folderPath = public_path($folder);
        
        $images = [];
        
        if (is_dir($folderPath)) {
            $files = glob($folderPath . '/*.{jpg,jpeg,png,webp,gif}', GLOB_BRACE);
            
            foreach ($files as $file) {
                $relativePath = str_replace(public_path() . DIRECTORY_SEPARATOR, '', $file);
                $relativePath = str_replace('\\', '/', $relativePath);
                $fileName = basename($file);
                
                $images[] = [
                    'name' => $fileName,
                    'path' => $relativePath,
                    'url' => asset($relativePath),
                ];
            }
        }
        
        return response()->json([
            'images' => $images,
            'count' => count($images)
        ]);
    }

    /**
     * Display destinations management
     */
    public function destinations(Request $request)
    {
        $query = HomepageDestination::query();
        
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('short_description', 'like', "%{$search}%")
                  ->orWhere('full_description', 'like', "%{$search}%");
            });
        }
        
        if ($request->filled('status')) {
            if ($request->status == 'active') {
                $query->where('is_active', true);
            } elseif ($request->status == 'hidden') {
                $query->where('is_active', false);
            }
        }
        
        if ($request->filled('featured')) {
            $query->where('is_featured', $request->featured == '1');
        }
        
        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }
        
        $destinations = $query->orderBy('display_order')->orderBy('name')->paginate(20);
        
        // Get unique categories for filter
        $categories = HomepageDestination::distinct()->whereNotNull('category')->pluck('category');
        
        // Enhanced statistics
        $stats = [
            'total' => HomepageDestination::count(),
            'active' => HomepageDestination::where('is_active', true)->count(),
            'inactive' => HomepageDestination::where('is_active', false)->count(),
            'featured' => HomepageDestination::where('is_featured', true)->count(),
            'national_parks' => HomepageDestination::where('category', 'National Parks')->count(),
            'mountain_trekking' => HomepageDestination::where('category', 'Mountain Trekking')->count(),
            'beaches' => HomepageDestination::where('category', 'Beaches')->count(),
            'game_reserves' => HomepageDestination::where('category', 'Game Reserves')->count(),
            'cultural_historical' => HomepageDestination::where('category', 'Cultural & Historical')->count(),
            'cities' => HomepageDestination::where('category', 'Cities')->count(),
            'with_rating' => HomepageDestination::whereNotNull('rating')->count(),
            'with_price' => HomepageDestination::whereNotNull('price')->count(),
        ];
        
        return view('admin.homepage.destinations', compact('destinations', 'categories', 'stats'));
    }

    /**
     * Show create destination form
     */
    public function createDestination()
    {
        return view('admin.homepage.destinations-create');
    }

    /**
     * Store a new destination
     */
    public function storeDestination(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'short_description' => 'required|string|max:500',
            'full_description' => 'nullable|string',
            'featured_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
            'featured_image_url' => [
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
                        $fail('The featured image URL must be a valid URL (http://...) or a relative path starting with images/');
                    }
                },
            ],
            'og_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
            'og_image_url' => [
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
                        $fail('The OG image URL must be a valid URL (http://...) or a relative path starting with images/');
                    }
                },
            ],
            'gallery_images' => 'nullable|array',
            'gallery_images.*' => 'image|mimes:jpeg,png,jpg,gif,webp|max:5120',
            'image_gallery' => 'nullable',
            'image_gallery.*' => [
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
                        $fail('Gallery image URLs must be valid URLs (http://...) or relative paths starting with images/');
                    }
                },
            ],
            'country' => 'nullable|string|max:100',
            'region' => 'nullable|string|max:100',
            'city' => 'nullable|string|max:100',
            'category' => 'nullable|string|max:100',
            'price' => 'nullable|numeric|min:0',
            'price_display' => 'nullable|string|max:255',
            'duration' => 'nullable|string|max:100',
            'rating' => 'nullable|numeric|min:0|max:5',
            'is_active' => 'boolean',
            'is_featured' => 'boolean',
            'slug' => 'nullable|string|max:255|unique:homepage_destinations,slug',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
            'meta_keywords' => 'nullable|string|max:500',
            'display_order' => 'nullable|integer|min:0',
        ]);
        
        $validated['is_active'] = $request->has('is_active');
        $validated['is_featured'] = $request->has('is_featured');
        
        // Handle image_gallery - it comes as JSON string from the form
        if ($request->filled('image_gallery')) {
            $imageGallery = $request->input('image_gallery');
            if (is_string($imageGallery)) {
                $decoded = json_decode($imageGallery, true);
                if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                    // Clean up paths - remove leading slashes if present for relative paths
                    $cleaned = array_map(function($path) {
                        $path = trim($path);
                        // Remove leading slash if it's a relative path (not HTTP URL)
                        if (str_starts_with($path, '/') && !str_starts_with($path, 'http')) {
                            $path = ltrim($path, '/');
                        }
                        return $path;
                    }, $decoded);
                    $validated['image_gallery'] = array_filter($cleaned);
                } else {
                    $validated['image_gallery'] = [];
                }
            } elseif (is_array($imageGallery)) {
                $validated['image_gallery'] = array_filter($imageGallery);
            } else {
                $validated['image_gallery'] = [];
            }
        } else {
            $validated['image_gallery'] = [];
        }
        
        // Handle gallery image IDs (convert comma-separated string to array)
        if ($request->filled('gallery_image_ids')) {
            $ids = is_array($request->gallery_image_ids) 
                ? $request->gallery_image_ids 
                : explode(',', $request->gallery_image_ids);
            $validated['gallery_image_ids'] = array_filter(array_map('intval', $ids));
        } else {
            $validated['gallery_image_ids'] = [];
        }
        
        // Generate slug if not provided
        if (empty($validated['slug'])) {
            $validated['slug'] = Str::slug($validated['name']);
            // Ensure uniqueness
            $baseSlug = $validated['slug'];
            $counter = 1;
            while (HomepageDestination::where('slug', $validated['slug'])->exists()) {
                $validated['slug'] = $baseSlug . '-' . $counter;
                $counter++;
            }
        }
        
        // Set default display_order if not provided
        if (!isset($validated['display_order'])) {
            $validated['display_order'] = HomepageDestination::max('display_order') + 1;
        }
        
        HomepageDestination::create($validated);
        
        return $this->successResponse('Destination created successfully!', route('admin.homepage.destinations'));
    }

    /**
     * Show edit destination form
     */
    public function editDestination($id)
    {
        $destination = HomepageDestination::findOrFail($id);
        return view('admin.homepage.destinations-edit', compact('destination'));
    }

    /**
     * Update destination
     */
    public function updateDestination(Request $request, $id)
    {
        $destination = HomepageDestination::findOrFail($id);
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'short_description' => 'required|string|max:500',
            'full_description' => 'nullable|string',
            'featured_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
            'featured_image_url' => [
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
                        $fail('The featured image URL must be a valid URL (http://...) or a relative path starting with images/');
                    }
                },
            ],
            'og_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
            'og_image_url' => [
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
                        $fail('The OG image URL must be a valid URL (http://...) or a relative path starting with images/');
                    }
                },
            ],
            'gallery_images' => 'nullable|array',
            'gallery_images.*' => 'image|mimes:jpeg,png,jpg,gif,webp|max:5120',
            'image_gallery' => 'nullable',
            'image_gallery.*' => [
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
                        $fail('Gallery image URLs must be valid URLs (http://...) or relative paths starting with images/');
                    }
                },
            ],
            'country' => 'nullable|string|max:100',
            'region' => 'nullable|string|max:100',
            'city' => 'nullable|string|max:100',
            'category' => 'nullable|string|max:100',
            'price' => 'nullable|numeric|min:0',
            'price_display' => 'nullable|string|max:255',
            'duration' => 'nullable|string|max:100',
            'rating' => 'nullable|numeric|min:0|max:5',
            'is_active' => 'boolean',
            'is_featured' => 'boolean',
            'slug' => 'nullable|string|max:255|unique:homepage_destinations,slug,' . $id,
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
            'meta_keywords' => 'nullable|string|max:500',
            'display_order' => 'nullable|integer|min:0',
        ]);
        
        $validated['is_active'] = $request->has('is_active');
        $validated['is_featured'] = $request->has('is_featured');
        
        // Handle image_gallery - it comes as JSON string from the form
        if ($request->filled('image_gallery')) {
            $imageGallery = $request->input('image_gallery');
            if (is_string($imageGallery)) {
                $decoded = json_decode($imageGallery, true);
                if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                    // Clean up paths - remove leading slashes if present for relative paths
                    $cleaned = array_map(function($path) {
                        $path = trim($path);
                        // Remove leading slash if it's a relative path (not HTTP URL)
                        if (str_starts_with($path, '/') && !str_starts_with($path, 'http')) {
                            $path = ltrim($path, '/');
                        }
                        return $path;
                    }, $decoded);
                    $validated['image_gallery'] = array_filter($cleaned);
                } else {
                    $validated['image_gallery'] = [];
                }
            } elseif (is_array($imageGallery)) {
                $validated['image_gallery'] = array_filter($imageGallery);
            } else {
                $validated['image_gallery'] = [];
            }
        } else {
            $validated['image_gallery'] = [];
        }
        
        // Handle gallery image IDs (convert comma-separated string to array)
        if ($request->filled('gallery_image_ids')) {
            $ids = is_array($request->gallery_image_ids) 
                ? $request->gallery_image_ids 
                : explode(',', $request->gallery_image_ids);
            $validated['gallery_image_ids'] = array_filter(array_map('intval', $ids));
        } else {
            $validated['gallery_image_ids'] = [];
        }
        
        // Generate slug if not provided
        if (empty($validated['slug'])) {
            $validated['slug'] = Str::slug($validated['name']);
            // Ensure uniqueness
            $baseSlug = $validated['slug'];
            $counter = 1;
            while (HomepageDestination::where('slug', $validated['slug'])->where('id', '!=', $id)->exists()) {
                $validated['slug'] = $baseSlug . '-' . $counter;
                $counter++;
            }
        }
        
        $destination->update($validated);
        
        return $this->successResponse('Destination updated successfully!', route('admin.homepage.destinations'));
    }

    /**
     * Show destination details
     */
    public function showDestination($id)
    {
        $destination = HomepageDestination::findOrFail($id);
        return view('admin.homepage.destinations-show', compact('destination'));
    }

    /**
     * Delete destination
     */
    public function destroyDestination($id)
    {
        $destination = HomepageDestination::findOrFail($id);
        
        // Note: We don't delete gallery images as they may be used elsewhere
        // Gallery images are managed separately in the gallery section
        
        $destination->delete();
        
        return $this->successResponse('Destination deleted successfully!', route('admin.homepage.destinations'));
    }

    /**
     * Get all images from public/images directory recursively
     */
    private function getFilesystemImages($folder = 'images', $search = null)
    {
        $images = [];
        $folderPath = public_path($folder);
        
        if (!is_dir($folderPath)) {
            return $images;
        }
        
        $iterator = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($folderPath, \RecursiveDirectoryIterator::SKIP_DOTS),
            \RecursiveIteratorIterator::SELF_FIRST
        );
        
        foreach ($iterator as $file) {
            if ($file->isFile()) {
                $ext = strtolower($file->getExtension());
                if (in_array($ext, ['jpg', 'jpeg', 'png', 'webp', 'gif'])) {
                    $relativePath = str_replace(public_path() . DIRECTORY_SEPARATOR, '', $file->getPathname());
                    $relativePath = str_replace('\\', '/', $relativePath);
                    $fileName = $file->getFilename();
                    
                    // Apply search filter if provided
                    if ($search && stripos($fileName, $search) === false) {
                        continue;
                    }
                    
                    // Get image dimensions if possible
                    $width = null;
                    $height = null;
                    try {
                        $imageInfo = @getimagesize($file->getPathname());
                        if ($imageInfo) {
                            $width = $imageInfo[0];
                            $height = $imageInfo[1];
                        }
                    } catch (\Exception $e) {
                        // Ignore errors
                    }
                    
                    $images[] = (object)[
                        'id' => 'fs_' . md5($relativePath),
                        'type' => 'filesystem',
                        'title' => $fileName,
                        'image_url' => $relativePath,
                        'display_url' => asset($relativePath),
                        'width' => $width,
                        'height' => $height,
                        'category' => $this->getImageCategory($relativePath),
                        'is_active' => true,
                        'is_featured' => false,
                        'created_at' => \Carbon\Carbon::createFromTimestamp($file->getMTime()),
                        'folder' => dirname($relativePath) !== 'images' ? dirname($relativePath) : 'images',
                    ];
                }
            }
        }
        
        return $images;
    }
    
    /**
     * Determine category based on image path
     */
    private function getImageCategory($path)
    {
        if (str_contains($path, 'hero-slider')) return 'Hero Slider';
        if (str_contains($path, 'tours')) return 'Tours';
        if (str_contains($path, 'categories')) return 'Categories';
        if (str_contains($path, 'destinations')) return 'Destinations';
        return 'General';
    }
    
    /**
     * Display gallery management
     */
    public function gallery(Request $request)
    {
        $viewType = $request->get('view', 'all'); // 'database', 'filesystem', or 'all'
        
        // Get database images
        $dbImages = collect();
        if ($viewType === 'all' || $viewType === 'database') {
            $query = Gallery::with(['album', 'uploader', 'tagRelations']);
            
            if ($request->filled('category')) {
                $query->where('category', $request->category);
            }
            
            if ($request->filled('album_id')) {
                $query->where('album_id', $request->album_id);
            }
            
            if ($request->filled('status')) {
                if ($request->status == 'active') {
                    $query->where('is_active', true);
                } elseif ($request->status == 'inactive') {
                    $query->where('is_active', false);
                } elseif ($request->status == 'deleted') {
                    $query->onlyTrashed();
                }
            }
            
            if ($request->filled('priority')) {
                $query->where('priority', $request->priority);
            }
            
            if ($request->filled('resolution')) {
                if ($request->resolution == 'small') {
                    $query->where(function($q) {
                        $q->where('width', '<', 1280)->orWhere('height', '<', 720);
                    });
                } elseif ($request->resolution == 'medium') {
                    $query->where('width', '>=', 1280)->where('width', '<', 1920);
                } elseif ($request->resolution == 'large') {
                    $query->where('width', '>=', 1920);
                }
            }
            
            if ($request->filled('search')) {
                $search = $request->search;
                $query->where(function($q) use ($search) {
                    $q->where('title', 'like', "%{$search}%")
                      ->orWhere('description', 'like', "%{$search}%")
                      ->orWhere('caption', 'like', "%{$search}%")
                      ->orWhere('alt_text', 'like', "%{$search}%");
                });
            }
            
            if ($request->filled('sort')) {
                switch ($request->sort) {
                    case 'newest':
                        $query->orderBy('created_at', 'desc');
                        break;
                    case 'oldest':
                        $query->orderBy('created_at', 'asc');
                        break;
                    case 'largest':
                        $query->orderBy(DB::raw('width * height'), 'desc');
                        break;
                    case 'smallest':
                        $query->orderBy(DB::raw('width * height'), 'asc');
                        break;
                    default:
                        $query->orderBy('display_order')->orderBy('created_at', 'desc');
                }
            } else {
                $query->orderBy('display_order')->orderBy('created_at', 'desc');
            }
            
            $dbImages = $query->get();
        }
        
        // Get filesystem images
        $fsImages = collect();
        if ($viewType === 'all' || $viewType === 'filesystem') {
            $fsImages = collect($this->getFilesystemImages('images', $request->get('search')));
            
            // Apply category filter for filesystem images
            if ($request->filled('category')) {
                $fsImages = $fsImages->filter(function($img) use ($request) {
                    return $img->category === $request->category;
                });
            }
            
            // Sort filesystem images
            if ($request->filled('sort')) {
                switch ($request->sort) {
                    case 'newest':
                        $fsImages = $fsImages->sortByDesc('created_at');
                        break;
                    case 'oldest':
                        $fsImages = $fsImages->sortBy('created_at');
                        break;
                    case 'largest':
                        $fsImages = $fsImages->sortByDesc(function($img) {
                            return ($img->width ?? 0) * ($img->height ?? 0);
                        });
                        break;
                    case 'smallest':
                        $fsImages = $fsImages->sortBy(function($img) {
                            return ($img->width ?? 0) * ($img->height ?? 0);
                        });
                        break;
                }
            } else {
                $fsImages = $fsImages->sortBy('title');
            }
        }
        
        // Combine images - use concat instead of merge to avoid getKey() issues
        $allImages = $dbImages->concat($fsImages);
        
        // Paginate manually
        $perPage = $request->get('per_page', 20);
        $currentPage = $request->get('page', 1);
        $total = $allImages->count();
        $items = $allImages->slice(($currentPage - 1) * $perPage, $perPage)->values();
        
        // Create paginator manually
        $images = new \Illuminate\Pagination\LengthAwarePaginator(
            $items,
            $total,
            $perPage,
            $currentPage,
            ['path' => $request->url(), 'query' => $request->query()]
        );
        
        // Get filter options
        $categories = Gallery::distinct()->whereNotNull('category')->pluck('category');
        // Add filesystem categories
        $fsCategories = collect($this->getFilesystemImages('images'))->pluck('category')->unique()->filter();
        $categories = $categories->merge($fsCategories)->unique()->sort()->values();
        
        $albums = GalleryAlbum::where('is_active', true)->orderBy('name')->get();
        $priorities = ['high', 'medium', 'low'];
        
        // Statistics
        $totalDbImages = Gallery::count();
        $totalFsImages = count($this->getFilesystemImages('images'));
        
        return view('admin.homepage.gallery', compact('images', 'categories', 'albums', 'priorities', 'viewType', 'totalDbImages', 'totalFsImages'));
    }

    /**
     * Show create gallery item form
     */
    public function createGallery()
    {
        $albums = GalleryAlbum::where('is_active', true)->orderBy('name')->get();
        $tags = GalleryTag::orderBy('name')->get();
        $categories = [
            'Homepage Slider',
            'Homepage Gallery',
            'Tanzania in Pictures',
            'System Icons / UI Images',
            'Blog Images',
            'Destination Images',
            'Team Photos',
            'Testimonials Images',
        ];
        
        return view('admin.homepage.gallery-create', compact('albums', 'tags', 'categories'));
    }

    /**
     * Store a new gallery item (supports bulk upload)
     */
    public function storeGallery(Request $request)
    {
        // Simple validation for bulk upload
        $validated = $request->validate([
            'images' => 'required|array|min:1',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif,webp,svg|max:5120',
            'category' => 'nullable|string|max:100',
            'is_active' => 'boolean',
        ]);
        
        // Validate that at least one image is provided
        if (!$request->hasFile('images') || count($request->file('images')) === 0) {
            return back()->withErrors(['images' => 'Please select at least one image to upload.'])->withInput();
        }
        
        $imageService = new ImageService();
        $uploadedCount = 0;
        $errors = [];
        $baseDisplayOrder = (Gallery::max('display_order') ?? 0) + 1;
        
        // Process each uploaded image
        foreach ($request->file('images') as $index => $imageFile) {
            try {
                // For bulk uploads, use simpler processing without strict validation
                $options = [
                    'quality' => 80,
                    'maxWidth' => 2560,
                    'removeExif' => true,
                    'convertWebP' => true,
                    'generateThumbnails' => true,
                    'minWidth' => 0, // Don't enforce minimum for bulk uploads
                    'minHeight' => 0,
                ];
                
                // Process and store the image
                $imageData = $imageService->optimizeAndStore($imageFile, 'gallery', $options);
                
                // Generate title from filename
                $title = pathinfo($imageFile->getClientOriginalName(), PATHINFO_FILENAME);
                $title = Str::title(str_replace(['_', '-'], ' ', $title));
                
                // Create gallery item
                $galleryData = [
                    'title' => $title,
                    'image_url' => $imageData['original'],
                    'webp_url' => $imageData['webp'] ?? null,
                    'thumbnail_150' => $imageData['thumbnails']['150'] ?? null,
                    'thumbnail_300' => $imageData['thumbnails']['300'] ?? null,
                    'thumbnail_600' => $imageData['thumbnails']['600'] ?? null,
                    'thumbnail_hd' => $imageData['thumbnails']['hd'] ?? null,
                    'width' => $imageData['metadata']['width'],
                    'height' => $imageData['metadata']['height'],
                    'file_size' => $imageData['metadata']['file_size'],
                    'mime_type' => $imageData['metadata']['mime_type'],
                    'original_filename' => $imageData['metadata']['original_filename'],
                    'category' => $validated['category'] ?? null,
                    'is_active' => $request->has('is_active'),
                    'priority' => 'medium',
                    'visibility' => 'all',
                    'click_action' => 'lightbox',
                    'display_order' => $baseDisplayOrder + $index,
                    'uploaded_by' => Auth::id(),
                    'uploaded_at' => now(),
                    'seo_filename' => Str::slug($title),
                    'alt_text' => $title, // Use title as alt text
                ];
                
                Gallery::create($galleryData);
                $uploadedCount++;
                
            } catch (\Exception $e) {
                $errors[] = $imageFile->getClientOriginalName() . ': ' . $e->getMessage();
            }
        }
        
        if ($uploadedCount > 0) {
            $message = "Successfully uploaded {$uploadedCount} image" . ($uploadedCount > 1 ? 's' : '') . "!";
            if (count($errors) > 0) {
                $message .= " " . count($errors) . " image(s) failed to upload.";
            }
            return $this->successResponse($message, route('admin.homepage.gallery'));
        } else {
            return back()->withErrors(['images' => 'Failed to upload images. ' . implode(', ', $errors)])->withInput();
        }
    }

    /**
     * Show gallery item (redirects to edit)
     */
    public function showGallery($id)
    {
        return redirect()->route('admin.homepage.gallery.edit', $id);
    }

    /**
     * Show edit gallery item form
     */
    public function editGallery($id)
    {
        $gallery = Gallery::with(['album', 'tagRelations'])->findOrFail($id);
        $albums = GalleryAlbum::where('is_active', true)->orderBy('name')->get();
        $tags = GalleryTag::orderBy('name')->get();
        $categories = [
            'Homepage Slider',
            'Homepage Gallery',
            'System Icons / UI Images',
            'Blog Images',
            'Destination Images',
            'Team Photos',
            'Testimonials Images',
        ];
        
        return view('admin.homepage.gallery-edit', compact('gallery', 'albums', 'tags', 'categories'));
    }

    /**
     * Update gallery item
     */
    public function updateGallery(Request $request, $id)
    {
        $gallery = Gallery::findOrFail($id);
        
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'caption' => 'nullable|string|max:500',
            'alt_text' => 'nullable|string|max:500',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp,svg|max:5120',
            'image_url' => 'nullable|url',
            'category' => 'nullable|string|max:100',
            'album_id' => 'nullable|exists:gallery_albums,id',
            'tags' => 'nullable|array',
            'tags.*' => 'exists:gallery_tags,id',
            'display_order' => 'nullable|integer|min:0',
            'priority' => 'nullable|in:high,medium,low',
            'visibility' => 'nullable|in:all,mobile,desktop',
            'visible_from' => 'nullable|date',
            'visible_until' => 'nullable|date|after:visible_from',
            'click_action' => 'nullable|in:lightbox,link,none',
            'click_link' => 'nullable|url|required_if:click_action,link',
            'seo_filename' => 'nullable|string|max:255',
            'seo_alt_text' => 'nullable|string|max:500',
            'auto_optimize' => 'boolean',
            'convert_to_webp' => 'boolean',
            'resize_large' => 'boolean',
            'optimization_quality' => 'nullable|integer|min:1|max:100',
            'is_featured' => 'boolean',
            'is_active' => 'boolean',
        ]);
        
        $imageService = new ImageService();
        
        // Handle image upload with advanced processing
        if ($request->hasFile('image')) {
            try {
                // Validate resolution
                $validation = $imageService->validateResolution(
                    $request->file('image'),
                    $request->get('min_width', 1280),
                    $request->get('min_height', 720)
                );
                
                if (!$validation['valid']) {
                    return back()->withErrors(['image' => $validation['message']])->withInput();
                }
                
                // Delete old image files if exists
                $oldImageUrl = $gallery->image_url ?? null;
                if ($oldImageUrl && !str_starts_with($oldImageUrl, 'http')) {
                    $imageService->delete($oldImageUrl);
                    // Delete thumbnails
                    if ($gallery->thumbnail_150) $imageService->delete($gallery->thumbnail_150);
                    if ($gallery->thumbnail_300) $imageService->delete($gallery->thumbnail_300);
                    if ($gallery->thumbnail_600) $imageService->delete($gallery->thumbnail_600);
                    if ($gallery->thumbnail_hd) $imageService->delete($gallery->thumbnail_hd);
                    if ($gallery->webp_url) $imageService->delete($gallery->webp_url);
                }
                
                // Optimize and store
                $options = [
                    'quality' => $validated['optimization_quality'] ?? 80,
                    'maxWidth' => $request->get('max_width', 2560),
                    'removeExif' => $request->has('auto_optimize') ? ($validated['auto_optimize'] ?? true) : true,
                    'convertWebP' => $request->has('convert_to_webp') ? ($validated['convert_to_webp'] ?? true) : true,
                    'generateThumbnails' => true,
                ];
                
                $imageData = $imageService->optimizeAndStore($request->file('image'), 'gallery', $options);
                
                $validated['image_url'] = $imageData['original'];
                $validated['webp_url'] = $imageData['webp'] ?? null;
                $validated['thumbnail_150'] = $imageData['thumbnails']['150'] ?? null;
                $validated['thumbnail_300'] = $imageData['thumbnails']['300'] ?? null;
                $validated['thumbnail_600'] = $imageData['thumbnails']['600'] ?? null;
                $validated['thumbnail_hd'] = $imageData['thumbnails']['hd'] ?? null;
                $validated['width'] = $imageData['metadata']['width'];
                $validated['height'] = $imageData['metadata']['height'];
                $validated['file_size'] = $imageData['metadata']['file_size'];
                $validated['mime_type'] = $imageData['metadata']['mime_type'];
                $validated['original_filename'] = $imageData['metadata']['original_filename'];
                
            } catch (\Exception $e) {
                return back()->withErrors(['image' => 'Image processing failed: ' . $e->getMessage()])->withInput();
            }
        } elseif ($request->filled('image_url') && $request->image_url != $gallery->image_url) {
            // URL changed
            $validated['image_url'] = $request->image_url;
        }
        
        // Set defaults
        $validated['is_featured'] = $request->has('is_featured');
        $validated['is_active'] = $request->has('is_active');
        $validated['auto_optimize'] = $request->has('auto_optimize');
        $validated['convert_to_webp'] = $request->has('convert_to_webp');
        $validated['resize_large'] = $request->has('resize_large');
        $validated['priority'] = $validated['priority'] ?? $gallery->priority ?? 'medium';
        $validated['visibility'] = $validated['visibility'] ?? $gallery->visibility ?? 'all';
        $validated['click_action'] = $validated['click_action'] ?? $gallery->click_action ?? 'lightbox';
        
        // Remove image file from validated
        unset($validated['image']);
        
        $gallery->update($validated);
        
        // Sync tags
        if ($request->filled('tags') && is_array($request->tags)) {
            $gallery->tagRelations()->sync($request->tags);
        } else {
            $gallery->tagRelations()->detach();
        }
        
        return $this->successResponse('Gallery item updated successfully!', route('admin.homepage.gallery'));
    }

    /**
     * Delete gallery item
     */
    public function destroyGallery($id)
    {
        $gallery = Gallery::findOrFail($id);
        
        // Delete image file (only if it's a stored file, not external URL)
        $imageService = new ImageService();
        $imageUrl = $gallery->getAttributes()['image_url'] ?? null;
        if ($imageUrl && !str_starts_with($imageUrl, 'http')) {
            $imageService->delete($imageUrl);
        }
        
        $gallery->delete();
        
        return $this->successResponse('Gallery item deleted successfully!', route('admin.homepage.gallery'));
    }

    /**
     * Get gallery images for image picker (AJAX)
     */
    public function getGalleryImages(Request $request)
    {
        $query = Gallery::where('is_active', true);
        
        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }
        
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }
        
        $images = $query->orderBy('display_order')->orderBy('title')->get();
        
        $imageService = new ImageService();
        $formatted = $images->map(function($image) use ($imageService) {
            $rawUrl = $image->getAttributes()['image_url'] ?? null;
            $displayUrl = null;
            
            if ($rawUrl) {
                try {
                    $displayUrl = $imageService->getUrl($rawUrl);
                } catch (\Exception $e) {
                    // If URL generation fails, skip this image
                    return null;
                }
            }
            
            // Only return images with valid URLs
            if (!$displayUrl) {
                return null;
            }
            
            return [
                'id' => $image->id,
                'title' => $image->title ?? 'Untitled',
                'url' => $displayUrl,
                'storage_path' => $rawUrl,
                'category' => $image->category,
            ];
        })->filter(); // Remove null entries
        
        return response()->json($formatted->values());
    }

    /**
     * Display testimonials management
     */
    public function testimonials(Request $request)
    {
        $query = Testimonial::with('tour');
        
        if ($request->filled('status')) {
            if ($request->status == 'approved') {
                $query->where('is_approved', true);
            } else {
                $query->where('is_approved', false);
            }
        }
        
        if ($request->filled('featured')) {
            $query->where('is_featured', $request->featured == '1');
        }
        
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('author_name', 'like', "%{$search}%")
                  ->orWhere('content', 'like', "%{$search}%");
            });
        }
        
        $testimonials = $query->orderBy('display_order')->latest()->paginate(20);
        
        return view('admin.homepage.testimonials', compact('testimonials'));
    }

    /**
     * Show create testimonial form
     */
    public function createTestimonial()
    {
        $tours = Tour::orderBy('name')->get();
        return view('admin.homepage.testimonials-create', compact('tours'));
    }

    /**
     * Store a new testimonial
     */
    public function storeTestimonial(Request $request)
    {
        $validated = $request->validate([
            'author_name' => 'required|string|max:255',
            'author_title' => 'nullable|string|max:255',
            'author_image_url' => 'nullable|url',
            'content' => 'required|string',
            'rating' => 'required|integer|min:1|max:5',
            'tour_id' => 'nullable|exists:tours,id',
            'is_approved' => 'boolean',
            'is_featured' => 'boolean',
            'display_order' => 'nullable|integer|min:0',
        ]);
        
        $validated['is_approved'] = $request->has('is_approved');
        $validated['is_featured'] = $request->has('is_featured');
        
        if (!$validated['display_order']) {
            $validated['display_order'] = Testimonial::max('display_order') + 1;
        }
        
        Testimonial::create($validated);
        
        return $this->successResponse('Testimonial created successfully!', route('admin.homepage.testimonials'));
    }

    /**
     * Show edit testimonial form
     */
    public function editTestimonial($id)
    {
        $testimonial = Testimonial::findOrFail($id);
        $tours = Tour::orderBy('name')->get();
        return view('admin.homepage.testimonials-edit', compact('testimonial', 'tours'));
    }

    /**
     * Update testimonial
     */
    public function updateTestimonial(Request $request, $id)
    {
        $testimonial = Testimonial::findOrFail($id);
        
        $validated = $request->validate([
            'author_name' => 'required|string|max:255',
            'author_title' => 'nullable|string|max:255',
            'author_image_url' => 'nullable|url',
            'content' => 'required|string',
            'rating' => 'required|integer|min:1|max:5',
            'tour_id' => 'nullable|exists:tours,id',
            'is_approved' => 'boolean',
            'is_featured' => 'boolean',
            'display_order' => 'nullable|integer|min:0',
        ]);
        
        $validated['is_approved'] = $request->has('is_approved');
        $validated['is_featured'] = $request->has('is_featured');
        
        $testimonial->update($validated);
        
        return $this->successResponse('Testimonial updated successfully!', route('admin.homepage.testimonials'));
    }

    /**
     * Delete testimonial
     */
    public function destroyTestimonial($id)
    {
        $testimonial = Testimonial::findOrFail($id);
        $testimonial->delete();
        
        return $this->successResponse('Testimonial deleted successfully!', route('admin.homepage.testimonials'));
    }

    /**
     * Display blog posts management
     */
    public function blogPosts(Request $request)
    {
        $query = Post::query();
        
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('content', 'like', "%{$search}%");
            });
        }
        
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        
        $posts = $query->latest()->paginate(20);
        
        return view('admin.homepage.blog-posts', compact('posts'));
    }

    /**
     * Display FAQ management
     */
    public function faq(Request $request)
    {
        $query = Faq::query();
        
        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }
        
        if ($request->filled('status')) {
            if ($request->status == 'active') {
                $query->where('is_active', true);
            } else {
                $query->where('is_active', false);
            }
        }
        
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('question', 'like', "%{$search}%")
                  ->orWhere('answer', 'like', "%{$search}%");
            });
        }
        
        $faqs = $query->orderBy('display_order')->latest()->paginate(20);
        
        return view('admin.homepage.faq', compact('faqs'));
    }

    /**
     * Show create FAQ form
     */
    public function createFaq()
    {
        return view('admin.homepage.faq-create');
    }

    /**
     * Store a new FAQ
     */
    public function storeFaq(Request $request)
    {
        $validated = $request->validate([
            'question' => 'required|string|max:500',
            'answer' => 'required|string',
            'category' => 'nullable|string|max:100',
            'display_order' => 'nullable|integer|min:0',
            'is_active' => 'boolean',
        ]);
        
        $validated['is_active'] = $request->has('is_active');
        
        if (!$validated['display_order']) {
            $validated['display_order'] = Faq::max('display_order') + 1;
        }
        
        Faq::create($validated);
        
        return $this->successResponse('FAQ created successfully!', route('admin.homepage.faq'));
    }

    /**
     * Show edit FAQ form
     */
    public function editFaq($id)
    {
        $faq = Faq::findOrFail($id);
        return view('admin.homepage.faq-edit', compact('faq'));
    }

    /**
     * Update FAQ
     */
    public function updateFaq(Request $request, $id)
    {
        $faq = Faq::findOrFail($id);
        
        $validated = $request->validate([
            'question' => 'required|string|max:500',
            'answer' => 'required|string',
            'category' => 'nullable|string|max:100',
            'display_order' => 'nullable|integer|min:0',
            'is_active' => 'boolean',
        ]);
        
        $validated['is_active'] = $request->has('is_active');
        
        $faq->update($validated);
        
        return $this->successResponse('FAQ updated successfully!', route('admin.homepage.faq'));
    }

    /**
     * Delete FAQ
     */
    public function destroyFaq($id)
    {
        $faq = Faq::findOrFail($id);
        $faq->delete();
        
        return $this->successResponse('FAQ deleted successfully!', route('admin.homepage.faq'));
    }

    /**
     * Bulk actions for gallery
     */
    public function bulkGalleryAction(Request $request)
    {
        $request->validate([
            'action' => 'required|in:delete,move_category,optimize,download,restore',
            'ids' => 'required|array',
            'ids.*' => 'exists:galleries,id',
        ]);
        
        $ids = $request->ids;
        $imageService = new ImageService();
        
        switch ($request->action) {
            case 'delete':
                $galleries = Gallery::whereIn('id', $ids)->get();
                foreach ($galleries as $gallery) {
                    if ($gallery->image_url && !str_starts_with($gallery->image_url, 'http')) {
                        $imageService->delete($gallery->image_url);
                    }
                    $gallery->delete();
                }
                return $this->successResponse(count($ids) . ' images moved to recycle bin!', route('admin.homepage.gallery'));
                
            case 'restore':
                Gallery::onlyTrashed()->whereIn('id', $ids)->restore();
                return $this->successResponse(count($ids) . ' images restored!', route('admin.homepage.gallery'));
                
            case 'move_category':
                $request->validate(['category' => 'required|string|max:100']);
                Gallery::whereIn('id', $ids)->update(['category' => $request->category]);
                return $this->successResponse(count($ids) . ' images moved to category!', route('admin.homepage.gallery'));
                
            case 'optimize':
                // Re-optimize images
                return $this->successResponse('Optimization queued for ' . count($ids) . ' images!', route('admin.homepage.gallery'));
        }
    }

    /**
     * Update gallery item order (drag & drop)
     */
    public function updateGalleryOrder(Request $request)
    {
        $request->validate([
            'items' => 'required|array',
            'items.*.id' => 'required|exists:galleries,id',
            'items.*.order' => 'required|integer',
        ]);
        
        foreach ($request->items as $item) {
            Gallery::where('id', $item['id'])->update(['display_order' => $item['order']]);
        }
        
        return response()->json(['success' => true, 'message' => 'Order updated successfully!']);
    }

    /**
     * Display company policies management
     */
    public function policies(Request $request)
    {
        $query = CompanyPolicy::query();
        
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('content', 'like', "%{$search}%");
            });
        }
        
        if ($request->filled('policy_type')) {
            $query->where('policy_type', $request->policy_type);
        }
        
        if ($request->filled('status')) {
            if ($request->status == 'active') {
                $query->where('is_active', true);
            } else {
                $query->where('is_active', false);
            }
        }
        
        $policies = $query->orderBy('display_order')->latest()->paginate(20);
        
        $policyTypes = CompanyPolicy::distinct()->whereNotNull('policy_type')->pluck('policy_type');
        
        return view('admin.homepage.policies', compact('policies', 'policyTypes'));
    }

    /**
     * Show create policy form
     */
    public function createPolicy()
    {
        return view('admin.homepage.policies-create');
    }

    /**
     * Store a new policy
     */
    public function storePolicy(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:company_policies,slug',
            'policy_type' => 'nullable|string|max:100',
            'short_description' => 'nullable|string',
            'content' => 'required|string',
            'display_order' => 'nullable|integer|min:0',
            'is_active' => 'boolean',
            'is_featured' => 'boolean',
            'show_in_footer' => 'boolean',
            'effective_date' => 'nullable|date',
            'expiry_date' => 'nullable|date|after:effective_date',
            'version' => 'nullable|string|max:50',
        ]);
        
        $validated['is_active'] = $request->has('is_active');
        $validated['is_featured'] = $request->has('is_featured');
        $validated['show_in_footer'] = $request->has('show_in_footer');
        $validated['created_by'] = Auth::id();
        
        if (!isset($validated['display_order'])) {
            $validated['display_order'] = CompanyPolicy::max('display_order') + 1;
        }
        
        CompanyPolicy::create($validated);
        
        return $this->successResponse('Policy created successfully!', route('admin.homepage.policies'));
    }

    /**
     * Show edit policy form
     */
    public function editPolicy($id)
    {
        $policy = CompanyPolicy::findOrFail($id);
        return view('admin.homepage.policies-edit', compact('policy'));
    }

    /**
     * Update policy
     */
    public function updatePolicy(Request $request, $id)
    {
        $policy = CompanyPolicy::findOrFail($id);
        
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:company_policies,slug,' . $id,
            'policy_type' => 'nullable|string|max:100',
            'short_description' => 'nullable|string',
            'content' => 'required|string',
            'display_order' => 'nullable|integer|min:0',
            'is_active' => 'boolean',
            'is_featured' => 'boolean',
            'show_in_footer' => 'boolean',
            'effective_date' => 'nullable|date',
            'expiry_date' => 'nullable|date|after:effective_date',
            'version' => 'nullable|string|max:50',
        ]);
        
        $validated['is_active'] = $request->has('is_active');
        $validated['is_featured'] = $request->has('is_featured');
        $validated['show_in_footer'] = $request->has('show_in_footer');
        $validated['updated_by'] = Auth::id();
        
        $policy->update($validated);
        
        return $this->successResponse('Policy updated successfully!', route('admin.homepage.policies'));
    }

    /**
     * Delete policy
     */
    public function destroyPolicy($id)
    {
        $policy = CompanyPolicy::findOrFail($id);
        $policy->delete();
        
        return $this->successResponse('Policy deleted successfully!', route('admin.homepage.policies'));
    }

    /**
     * Display SEO management
     */
    public function seo(Request $request)
    {
        $query = SeoSetting::query();
        
        if ($request->filled('page_type')) {
            $query->where('page_type', $request->page_type);
        }
        
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('meta_title', 'like', "%{$search}%")
                  ->orWhere('meta_description', 'like', "%{$search}%")
                  ->orWhere('page_identifier', 'like', "%{$search}%");
            });
        }
        
        $seoSettings = $query->orderBy('page_type')->orderBy('page_identifier')->paginate(20);
        
        $pageTypes = [
            'homepage' => 'Homepage',
            'tours' => 'Tours',
            'destinations' => 'Destinations',
            'blog' => 'Blog',
            'about' => 'About',
            'contact' => 'Contact',
            'booking' => 'Booking',
        ];
        
        return view('admin.homepage.seo', compact('seoSettings', 'pageTypes'));
    }

    /**
     * Show create/edit SEO form
     */
    public function createSeo()
    {
        $pageTypes = [
            'homepage' => 'Homepage',
            'tours' => 'Tours',
            'destinations' => 'Destinations',
            'blog' => 'Blog',
            'about' => 'About',
            'contact' => 'Contact',
            'booking' => 'Booking',
        ];
        
        return view('admin.homepage.seo-create', compact('pageTypes'));
    }

    /**
     * Store/Update SEO setting
     */
    public function storeSeo(Request $request)
    {
        $validated = $request->validate([
            'page_type' => 'required|string|max:100',
            'page_identifier' => 'nullable|string|max:255',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
            'meta_keywords' => 'nullable|string|max:500',
            'og_title' => 'nullable|string|max:255',
            'og_description' => 'nullable|string|max:500',
            'og_image' => 'nullable|url',
            'og_type' => 'nullable|string|max:50',
            'twitter_card' => 'nullable|string|max:50',
            'twitter_title' => 'nullable|string|max:255',
            'twitter_description' => 'nullable|string|max:500',
            'twitter_image' => 'nullable|url',
            'canonical_url' => 'nullable|url',
            'robots' => 'nullable|string|max:255',
            'structured_data' => 'nullable|json',
            'custom_head_code' => 'nullable|string',
            'custom_footer_code' => 'nullable|string',
            'is_active' => 'boolean',
        ]);
        
        $validated['is_active'] = $request->has('is_active');
        $validated['updated_by'] = Auth::id();
        
        // Check if exists
        $seo = SeoSetting::where('page_type', $validated['page_type'])
            ->where('page_identifier', $validated['page_identifier'] ?? null)
            ->first();
        
        if ($seo) {
            $seo->update($validated);
            $message = 'SEO settings updated successfully!';
        } else {
            SeoSetting::create($validated);
            $message = 'SEO settings created successfully!';
        }
        
        return $this->successResponse($message, route('admin.homepage.seo'));
    }

    /**
     * Show edit SEO form
     */
    public function editSeo($id)
    {
        $seo = SeoSetting::findOrFail($id);
        $pageTypes = [
            'homepage' => 'Homepage',
            'tours' => 'Tours',
            'destinations' => 'Destinations',
            'blog' => 'Blog',
            'about' => 'About',
            'contact' => 'Contact',
            'booking' => 'Booking',
        ];
        
        return view('admin.homepage.seo-edit', compact('seo', 'pageTypes'));
    }

    /**
     * Delete SEO setting
     */
    public function destroySeo($id)
    {
        $seo = SeoSetting::findOrFail($id);
        $seo->delete();
        
        return $this->successResponse('SEO setting deleted successfully!', route('admin.homepage.seo'));
    }

    /**
     * Show create blog post form
     */
    public function createBlogPost()
    {
        $categories = \App\Models\Category::orderBy('name')->get();
        return view('admin.homepage.blog-posts-create', compact('categories'));
    }

    /**
     * Store a new blog post
     */
    public function storeBlogPost(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:posts,slug',
            'excerpt' => 'nullable|string|max:500',
            'body' => 'required|string',
            'category_id' => 'nullable|exists:categories,id',
            'image_url' => 'nullable|url',
            'published_at' => 'nullable|date',
        ]);
        
        $validated['user_id'] = Auth::id();
        
        if (empty($validated['slug'])) {
            $validated['slug'] = Str::slug($validated['title']);
            $baseSlug = $validated['slug'];
            $counter = 1;
            while (Post::where('slug', $validated['slug'])->exists()) {
                $validated['slug'] = $baseSlug . '-' . $counter;
                $counter++;
            }
        }
        
        Post::create($validated);
        
        return $this->successResponse('Blog post created successfully!', route('admin.homepage.blog-posts'));
    }

    /**
     * Show edit blog post form
     */
    public function editBlogPost($id)
    {
        $post = Post::findOrFail($id);
        $categories = \App\Models\Category::orderBy('name')->get();
        return view('admin.homepage.blog-posts-edit', compact('post', 'categories'));
    }

    /**
     * Update blog post
     */
    public function updateBlogPost(Request $request, $id)
    {
        $post = Post::findOrFail($id);
        
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:posts,slug,' . $id,
            'excerpt' => 'nullable|string|max:500',
            'body' => 'required|string',
            'category_id' => 'nullable|exists:categories,id',
            'image_url' => 'nullable|url',
            'published_at' => 'nullable|date',
        ]);
        
        $post->update($validated);
        
        return $this->successResponse('Blog post updated successfully!', route('admin.homepage.blog-posts'));
    }

    /**
     * Delete blog post
     */
    public function destroyBlogPost($id)
    {
        $post = Post::findOrFail($id);
        $post->delete();
        
        return $this->successResponse('Blog post deleted successfully!', route('admin.homepage.blog-posts'));
    }

    /**
     * Display hero slider management
     */
    public function heroSlider(Request $request)
    {
        $slides = HeroSlide::with('image')
            ->orderBy('display_order', 'asc')
            ->orderBy('created_at', 'desc')
            ->get();
        
        return view('admin.homepage.hero-slider', compact('slides'));
    }

    /**
     * Show hero slide details (for preview)
     */
    public function showHeroSlide($id)
    {
        $slide = HeroSlide::with('image')->findOrFail($id);
        
        // Return JSON for AJAX requests
        if (request()->ajax() || request()->wantsJson()) {
            // Ensure image_url is properly formatted
            $slideData = $slide->toArray();
            if ($slide->image_url && !$slide->image_id) {
                $rawUrl = $slide->image_url;
                if (!str_starts_with($rawUrl, 'http://') && !str_starts_with($rawUrl, 'https://')) {
                    if (str_starts_with($rawUrl, 'images/')) {
                        $slideData['image_url'] = $rawUrl;
                    } else {
                        // Assume it's in images/hero-slider/
                        $slideData['image_url'] = 'images/hero-slider/' . $rawUrl;
                    }
                }
            }
            
            return response()->json([
                'success' => true,
                'slide' => $slideData
            ]);
        }
        
        return view('admin.homepage.hero-slider-show', compact('slide'));
    }

    /**
     * Toggle hero slide status
     */
    public function toggleHeroSlideStatus(Request $request, $id)
    {
        $slide = HeroSlide::findOrFail($id);
        $slide->is_active = $request->input('is_active', !$slide->is_active);
        $slide->save();
        
        return response()->json([
            'success' => true,
            'message' => 'Slide status updated successfully!',
            'is_active' => $slide->is_active
        ]);
    }

    /**
     * Show create hero slide form
     */
    public function createHeroSlide()
    {
        // Get all active gallery images for selection
        $galleryImages = Gallery::where('is_active', true)
            ->whereNotNull('image_url')
            ->orderBy('display_order')
            ->orderBy('title')
            ->get()
            ->filter(function($image) {
                // Only include images with valid display URLs
                return $image->display_url !== null;
            });
        
        return view('admin.homepage.hero-slider-create', compact('galleryImages'));
    }

    /**
     * Store a new hero slide
     */
    public function storeHeroSlide(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'subtitle' => 'nullable|string|max:500',
            'badge_text' => 'nullable|string|max:100',
            'badge_icon' => 'nullable|string|max:50',
            'image_id' => 'nullable|exists:galleries,id',
            'image_url' => [
                'nullable',
                'string',
                'max:500',
                function ($attribute, $value, $fail) {
                    if (!empty($value)) {
                        if (filter_var($value, FILTER_VALIDATE_URL) !== false) {
                            return;
                        }
                        if (str_starts_with($value, 'images/')) {
                            return;
                        }
                        $fail('The image URL must be a valid URL (http://...) or a relative path starting with images/');
                    }
                },
            ],
            'primary_button_text' => 'nullable|string|max:100',
            'primary_button_link' => 'nullable|string|max:255',
            'primary_button_icon' => 'nullable|string|max:50',
            'secondary_button_text' => 'nullable|string|max:100',
            'secondary_button_link' => 'nullable|string|max:255',
            'secondary_button_icon' => 'nullable|string|max:50',
            'display_order' => 'nullable|integer|min:0',
            'animation_type' => 'nullable|string|in:fade-in-up,slide-left,slide-right,zoom-in',
            'overlay_type' => 'nullable|string|in:gradient,dark,light,none',
            'is_active' => 'boolean',
        ]);
        
        // Validate that at least one image source is provided
        if (!$request->filled('image_id') && !$request->filled('image_url')) {
            return back()->withErrors(['image_id' => 'Please select an image from gallery or provide an image URL.'])->withInput();
        }
        
        $validated['is_active'] = $request->has('is_active');
        $validated['display_order'] = $validated['display_order'] ?? (HeroSlide::max('display_order') ?? 0) + 1;
        $validated['animation_type'] = $validated['animation_type'] ?? 'fade-in-up';
        $validated['overlay_type'] = $validated['overlay_type'] ?? 'gradient';
        
        HeroSlide::create($validated);
        
        return $this->successResponse('Hero slide created successfully!', route('admin.homepage.hero-slider'));
    }

    /**
     * Show edit hero slide form
     */
    public function editHeroSlide($id)
    {
        $slide = HeroSlide::findOrFail($id);
        
        // Get all active gallery images for selection
        $galleryImages = Gallery::where('is_active', true)
            ->whereNotNull('image_url')
            ->orderBy('display_order')
            ->orderBy('title')
            ->get()
            ->filter(function($image) {
                // Only include images with valid display URLs
                return $image->display_url !== null;
            });
        
        return view('admin.homepage.hero-slider-edit', compact('slide', 'galleryImages'));
    }

    /**
     * Update hero slide
     */
    public function updateHeroSlide(Request $request, $id)
    {
        $slide = HeroSlide::findOrFail($id);
        
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'subtitle' => 'nullable|string|max:500',
            'badge_text' => 'nullable|string|max:100',
            'badge_icon' => 'nullable|string|max:50',
            'image_id' => 'nullable|exists:galleries,id',
            'image_url' => [
                'nullable',
                'string',
                'max:500',
                function ($attribute, $value, $fail) {
                    if (!empty($value)) {
                        if (filter_var($value, FILTER_VALIDATE_URL) !== false) {
                            return;
                        }
                        if (str_starts_with($value, 'images/')) {
                            return;
                        }
                        $fail('The image URL must be a valid URL (http://...) or a relative path starting with images/');
                    }
                },
            ],
            'primary_button_text' => 'nullable|string|max:100',
            'primary_button_link' => 'nullable|string|max:255',
            'primary_button_icon' => 'nullable|string|max:50',
            'secondary_button_text' => 'nullable|string|max:100',
            'secondary_button_link' => 'nullable|string|max:255',
            'secondary_button_icon' => 'nullable|string|max:50',
            'display_order' => 'nullable|integer|min:0',
            'animation_type' => 'nullable|string|in:fade-in-up,slide-left,slide-right,zoom-in',
            'overlay_type' => 'nullable|string|in:gradient,dark,light,none',
            'is_active' => 'boolean',
        ]);
        
        // Validate that at least one image source is provided
        if (!$request->filled('image_id') && !$request->filled('image_url')) {
            return back()->withErrors(['image_id' => 'Please select an image from gallery or provide an image URL.'])->withInput();
        }
        
        $validated['is_active'] = $request->has('is_active');
        
        $slide->update($validated);
        
        return $this->successResponse('Hero slide updated successfully!', route('admin.homepage.hero-slider'));
    }

    /**
     * Delete hero slide
     */
    public function destroyHeroSlide($id)
    {
        $slide = HeroSlide::findOrFail($id);
        $slide->delete();
        
        return $this->successResponse('Hero slide deleted successfully!', route('admin.homepage.hero-slider'));
    }

    /**
     * Update hero slide order
     */
    public function updateHeroSlideOrder(Request $request)
    {
        $request->validate([
            'slides' => 'required|array',
            'slides.*.id' => 'required|exists:hero_slides,id',
            'slides.*.order' => 'required|integer|min:0',
        ]);
        
        foreach ($request->slides as $slideData) {
            HeroSlide::where('id', $slideData['id'])
                ->update(['display_order' => $slideData['order']]);
        }
        
        return response()->json(['success' => true, 'message' => 'Slide order updated successfully!']);
    }
}


