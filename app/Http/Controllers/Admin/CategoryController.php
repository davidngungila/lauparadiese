<?php

namespace App\Http\Controllers\Admin;

use App\Models\Category;
use App\Models\TourCategory;
use App\Models\Tour;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CategoryController extends BaseAdminController
{
    /**
     * Display a listing of categories
     */
    public function index(Request $request)
    {
        $query = Category::with('parent');
        
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }
        
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }
        
        // Get tour counts for each category
        $categories = $query->withCount('children')->latest()->paginate(20);
        
        // Enhanced statistics - include both Category and TourCategory
        $tourCategories = TourCategory::withCount(['tours' => function($query) {
            $query->where('status', 'active')->where('publish_status', 'published');
        }])->orderBy('sort_order')->orderBy('name')->get();
        
        $stats = [
            'total' => Category::count(),
            'active' => Category::where('is_active', true)->count(),
            'inactive' => Category::where('is_active', false)->count(),
            'tour_categories' => Category::where('type', 'tour')->count(),
            'hotel_categories' => Category::where('type', 'hotel')->count(),
            'general_categories' => Category::where('type', 'general')->count(),
            'featured' => Category::where('is_featured', true)->count(),
            'tour_category_total' => TourCategory::count(),
            'tour_category_active' => TourCategory::where('is_active', true)->count(),
            'total_tours_in_categories' => Tour::where('status', 'active')
                ->where('publish_status', 'published')
                ->whereHas('categories')
                ->count(),
            'categories_with_tours' => TourCategory::has('tours')->count(),
        ];

        $allCategories = Category::orderBy('name')->get();
        $allTourCategories = TourCategory::orderBy('name')->get();
        
        return view('admin.categories.index', compact('categories', 'stats', 'allCategories', 'allTourCategories', 'tourCategories'));
    }

    /**
     * Show the form for creating a new category
     */
    public function create()
    {
        return view('admin.categories.create');
    }

    /**
     * Store a newly created category
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:tour,hotel,general',
            'description' => 'nullable|string',
            'image_url' => 'nullable|url',
            'parent_id' => 'nullable|exists:categories,id',
            'icon' => 'nullable|string|max:100',
            'color' => 'nullable|string|max:20',
            'sort_order' => 'nullable|integer',
        ]);
        
        $validated['slug'] = Str::slug($validated['name']);
        $validated['is_active'] = $request->has('is_active');
        $validated['show_in_menu'] = $request->has('show_in_menu');
        $validated['show_on_homepage'] = $request->has('show_on_homepage');
        $validated['is_featured'] = $request->has('is_featured');
        
        // Ensure slug is unique
        $originalSlug = $validated['slug'];
        $counter = 1;
        while (Category::where('slug', $validated['slug'])->exists()) {
            $validated['slug'] = $originalSlug . '-' . $counter;
            $counter++;
        }
        
        Category::create($validated);
        
        return $this->successResponse('Category created successfully!', route('admin.categories.index'));
    }

    /**
     * Display the specified category
     */
    public function show($id)
    {
        $category = Category::with(['parent', 'children'])->findOrFail($id);
        
        // Return JSON for AJAX requests
        if (request()->ajax() || request()->wantsJson()) {
            return response()->json([
                'success' => true,
                'category' => $category
            ]);
        }
        
        return view('admin.categories.show', compact('category'));
    }

    /**
     * Show the form for editing the specified category
     */
    public function edit($id)
    {
        $category = Category::with('parent')->findOrFail($id);
        $allCategories = Category::where('id', '!=', $id)->orderBy('name')->get();
        
        // Return JSON for AJAX requests
        if (request()->ajax() || request()->wantsJson()) {
            return response()->json([
                'success' => true,
                'category' => $category,
                'allCategories' => $allCategories
            ]);
        }
        
        return view('admin.categories.edit', compact('category', 'allCategories'));
    }

    /**
     * Update the specified category
     */
    public function update(Request $request, $id)
    {
        $category = Category::findOrFail($id);
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:tour,hotel,general',
            'description' => 'nullable|string',
            'image_url' => 'nullable|url',
            'parent_id' => 'nullable|exists:categories,id',
            'icon' => 'nullable|string|max:100',
            'color' => 'nullable|string|max:20',
            'sort_order' => 'nullable|integer',
        ]);
        
        $validated['is_active'] = $request->has('is_active');
        $validated['show_in_menu'] = $request->has('show_in_menu');
        $validated['show_on_homepage'] = $request->has('show_on_homepage');
        $validated['is_featured'] = $request->has('is_featured');
        
        // Update slug if name changed
        if ($category->name !== $validated['name']) {
            $slug = Str::slug($validated['name']);
            $originalSlug = $slug;
            $counter = 1;
            
            while (Category::where('slug', $slug)->where('id', '!=', $category->id)->exists()) {
                $slug = $originalSlug . '-' . $counter;
                $counter++;
            }
            
            $validated['slug'] = $slug;
        }
        
        $category->update($validated);
        
        return $this->successResponse('Category updated successfully!', route('admin.categories.index'));
    }

    /**
     * Remove the specified category
     */
    public function destroy($id)
    {
        $category = Category::findOrFail($id);
        
        // Check if category has children
        if ($category->children()->count() > 0) {
            return $this->errorResponse('Cannot delete category with subcategories! Please delete or move subcategories first.', route('admin.categories.index'));
        }
        
        // Check if category has posts
        if ($category->posts()->count() > 0) {
            return $this->errorResponse('Cannot delete category with associated posts!', route('admin.categories.index'));
        }
        
        $category->delete();
        
        return $this->successResponse('Category deleted successfully!', route('admin.categories.index'));
    }
}

