@extends('admin.layouts.app')

@section('title', 'Tour Categories - Lau Paradise Adventures')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">
                        <i class="ri-folder-line me-2"></i>Tour Categories
                    </h4>
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addCategoryModal">
                        <i class="ri-add-line me-1"></i>Add Category
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="row mb-4">
        <div class="col-lg-3 col-md-6 mb-4">
            <div class="card card-border-shadow-primary">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="avatar me-3">
                            <span class="avatar-initial rounded bg-label-primary">
                                <i class="ri-folder-line"></i>
                            </span>
                        </div>
                        <div>
                            <h5 class="mb-0">{{ $stats['total'] }}</h5>
                            <small class="text-muted">Total Categories</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 mb-4">
            <div class="card card-border-shadow-success">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="avatar me-3">
                            <span class="avatar-initial rounded bg-label-success">
                                <i class="ri-checkbox-circle-line"></i>
                            </span>
                        </div>
                        <div>
                            <h5 class="mb-0">{{ $stats['active'] }}</h5>
                            <small class="text-muted">Active Categories</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 mb-4">
            <div class="card card-border-shadow-warning">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="avatar me-3">
                            <span class="avatar-initial rounded bg-label-warning">
                                <i class="ri-map-pin-line"></i>
                            </span>
                        </div>
                        <div>
                            <h5 class="mb-0">{{ $stats['tour_category_total'] ?? $stats['tour_categories'] }}</h5>
                            <small class="text-muted">Tour Categories</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 mb-4">
            <div class="card card-border-shadow-info">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="avatar me-3">
                            <span class="avatar-initial rounded bg-label-info">
                                <i class="ri-hotel-line"></i>
                            </span>
                        </div>
                        <div>
                            <h5 class="mb-0">{{ $stats['hotel_categories'] }}</h5>
                            <small class="text-muted">Hotel Categories</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 mb-4">
            <div class="card card-border-shadow-danger">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="avatar me-3">
                            <span class="avatar-initial rounded bg-label-danger">
                                <i class="ri-eye-off-line"></i>
                            </span>
                        </div>
                        <div>
                            <h5 class="mb-0">{{ $stats['inactive'] ?? 0 }}</h5>
                            <small class="text-muted">Inactive Categories</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 mb-4">
            <div class="card card-border-shadow-primary">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="avatar me-3">
                            <span class="avatar-initial rounded bg-label-primary">
                                <i class="ri-star-line"></i>
                            </span>
                        </div>
                        <div>
                            <h5 class="mb-0">{{ $stats['featured'] ?? 0 }}</h5>
                            <small class="text-muted">Featured Categories</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 mb-4">
            <div class="card card-border-shadow-success">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="avatar me-3">
                            <span class="avatar-initial rounded bg-label-success">
                                <i class="ri-road-map-line"></i>
                            </span>
                        </div>
                        <div>
                            <h5 class="mb-0">{{ $stats['categories_with_tours'] ?? 0 }}</h5>
                            <small class="text-muted">Categories with Tours</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 mb-4">
            <div class="card card-border-shadow-info">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="avatar me-3">
                            <span class="avatar-initial rounded bg-label-info">
                                <i class="ri-calendar-check-line"></i>
                            </span>
                        </div>
                        <div>
                            <h5 class="mb-0">{{ $stats['total_tours_in_categories'] ?? 0 }}</h5>
                            <small class="text-muted">Tours in Categories</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('admin.categories.index') }}" class="row g-3">
                <div class="col-md-4">
                    <label class="form-label">Search</label>
                    <input type="text" name="search" class="form-control" placeholder="Search categories..." value="{{ request('search') }}">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Type</label>
                    <select name="type" class="form-select">
                        <option value="">All Types</option>
                        <option value="tour" {{ request('type') == 'tour' ? 'selected' : '' }}>Tour</option>
                        <option value="hotel" {{ request('type') == 'hotel' ? 'selected' : '' }}>Hotel</option>
                        <option value="general" {{ request('type') == 'general' ? 'selected' : '' }}>General</option>
                    </select>
                </div>
                <div class="col-md-2 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="ri-search-line me-1"></i>Filter
                    </button>
                </div>
                <div class="col-md-2 d-flex align-items-end">
                    <a href="{{ route('admin.categories.index') }}" class="btn btn-outline-secondary w-100">
                        <i class="ri-refresh-line me-1"></i>Reset
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Categories Table -->
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">All Categories</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Type</th>
                            <th>Parent</th>
                            <th>Tours</th>
                            <th>Description & Details</th>
                            <th>Status & Settings</th>
                            <th>Sort</th>
                            <th width="150">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($categories as $category)
                        <tr>
                            <td>
                                <div class="d-flex align-items-center">
                                    @if($category->image_url)
                                        <img src="{{ $category->image_url }}" alt="{{ $category->name }}" 
                                             class="rounded me-2" style="width: 40px; height: 40px; object-fit: cover;">
                                    @else
                                        <div class="avatar avatar-sm me-2">
                                            <span class="avatar-initial rounded bg-label-primary">
                                                {{ strtoupper(substr($category->name, 0, 1)) }}
                                            </span>
                                        </div>
                                    @endif
                                    <div>
                                        <strong>{{ $category->name }}</strong>
                                        <br><small class="text-muted">{{ $category->slug }}</small>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span class="badge bg-label-{{ $category->type == 'tour' ? 'primary' : ($category->type == 'hotel' ? 'info' : 'secondary') }}">
                                    {{ ucfirst($category->type) }}
                                </span>
                            </td>
                            <td>
                                @if($category->parent)
                                    <span class="badge bg-label-secondary">{{ $category->parent->name }}</span>
                                @else
                                    <span class="text-muted">Root</span>
                                @endif
                            </td>
                            <td>
                                @php
                                    $tourCount = $category->tours_count ?? 0;
                                    if ($category->type == 'tour') {
                                        // Try to get count from TourCategory if available
                                        $tourCategory = \App\Models\TourCategory::where('slug', $category->slug)->first();
                                        if ($tourCategory) {
                                            $tourCount = $tourCategory->tours()->where('status', 'active')->where('publish_status', 'published')->count();
                                        }
                                    }
                                @endphp
                                <span class="badge bg-label-{{ $tourCount > 0 ? 'success' : 'secondary' }}">
                                    {{ $tourCount }} {{ $tourCount == 1 ? 'tour' : 'tours' }}
                                </span>
                            </td>
                            <td>
                                <div class="d-flex flex-column">
                                    <small class="text-muted mb-1">{{ \Illuminate\Support\Str::limit($category->description ?? 'No description', 50) }}</small>
                                    @if($category->icon)
                                        <small class="text-muted"><i class="{{ $category->icon }}"></i> Icon: {{ $category->icon }}</small>
                                    @endif
                                    @if($category->color)
                                        <small class="text-muted">
                                            <span class="badge" style="background-color: {{ $category->color }}; width: 20px; height: 20px; display: inline-block; border-radius: 4px;"></span>
                                            Color: {{ $category->color }}
                                        </small>
                                    @endif
                                </div>
                            </td>
                            <td>
                                <div class="d-flex flex-column gap-1">
                                    <div>
                                        @if($category->is_active)
                                            <span class="badge bg-success">Active</span>
                                        @else
                                            <span class="badge bg-secondary">Inactive</span>
                                        @endif
                                        @if($category->is_featured)
                                            <span class="badge bg-label-warning ms-1">Featured</span>
                                        @endif
                                    </div>
                                    <div class="small text-body-secondary">
                                        @if($category->show_in_menu)
                                            <span class="badge bg-label-primary me-1">Menu</span>
                                        @endif
                                        @if($category->show_on_homepage)
                                            <span class="badge bg-label-info">Homepage</span>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span class="badge bg-label-secondary">{{ $category->sort_order ?? 0 }}</span>
                            </td>
                            <td>
                                <div class="d-flex gap-1">
                                    <button type="button" class="btn btn-sm btn-icon btn-outline-primary" 
                                            onclick="viewCategory({{ $category->id }})" title="View Details">
                                        <i class="ri-eye-line"></i>
                                    </button>
                                    <button type="button" class="btn btn-sm btn-icon btn-outline-warning" 
                                            onclick="editCategory({{ $category->id }})" title="Edit">
                                        <i class="ri-edit-line"></i>
                                    </button>
                                    <button type="button" class="btn btn-sm btn-icon btn-outline-danger" 
                                            onclick="deleteCategory({{ $category->id }}, '{{ $category->name }}')" title="Delete">
                                        <i class="ri-delete-bin-line"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center py-5">
                                <div class="text-muted">
                                    <i class="ri-folder-line ri-48px mb-3 d-block"></i>
                                    <p>No categories found</p>
                                    <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addCategoryModal">
                                        <i class="ri-add-line me-1"></i>Add Category
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-4">
                {{ $categories->links() }}
            </div>
        </div>
    </div>

    <!-- Tour Categories Section -->
    @if(isset($tourCategories) && $tourCategories->count() > 0)
    <div class="card mt-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">
                <i class="ri-map-pin-line me-2"></i>Tour Categories (TourCategory Model)
            </h5>
            <span class="badge bg-label-primary">{{ $tourCategories->count() }} Categories</span>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Slug</th>
                            <th>Tours</th>
                            <th>Description</th>
                            <th>Status</th>
                            <th>Sort Order</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($tourCategories as $tourCategory)
                        <tr>
                            <td>
                                <div class="d-flex align-items-center">
                                    @if($tourCategory->image_url)
                                        <img src="{{ $tourCategory->image_url }}" alt="{{ $tourCategory->name }}" 
                                             class="rounded me-2" style="width: 40px; height: 40px; object-fit: cover;">
                                    @else
                                        <div class="avatar avatar-sm me-2">
                                            <span class="avatar-initial rounded bg-label-primary">
                                                {{ strtoupper(substr($tourCategory->name, 0, 1)) }}
                                            </span>
                                        </div>
                                    @endif
                                    <strong>{{ $tourCategory->name }}</strong>
                                </div>
                            </td>
                            <td>
                                <small class="text-muted">{{ $tourCategory->slug }}</small>
                            </td>
                            <td>
                                <span class="badge bg-label-{{ $tourCategory->tours_count > 0 ? 'success' : 'secondary' }}">
                                    {{ $tourCategory->tours_count ?? 0 }} {{ ($tourCategory->tours_count ?? 0) == 1 ? 'tour' : 'tours' }}
                                </span>
                            </td>
                            <td>
                                <small class="text-muted">{{ \Illuminate\Support\Str::limit($tourCategory->description ?? 'No description', 60) }}</small>
                            </td>
                            <td>
                                @if($tourCategory->is_active)
                                    <span class="badge bg-success">Active</span>
                                @else
                                    <span class="badge bg-secondary">Inactive</span>
                                @endif
                            </td>
                            <td>
                                <span class="badge bg-label-secondary">{{ $tourCategory->sort_order ?? 0 }}</span>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    @endif
</div>

<!-- Add/Edit Category Modal -->
<div class="modal fade" id="addCategoryModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="categoryModalTitle">Add Category</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="categoryForm" method="POST" action="{{ route('admin.categories.store') }}">
                @csrf
                <input type="hidden" name="_method" id="categoryMethod" value="POST">
                <input type="hidden" name="category_id" id="categoryId">
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Category Name <span class="text-danger">*</span></label>
                            <input type="text" name="name" id="categoryName" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Type <span class="text-danger">*</span></label>
                            <select name="type" id="categoryType" class="form-select" required>
                                <option value="">Select Type</option>
                                <option value="tour">Tour</option>
                                <option value="hotel">Hotel</option>
                                <option value="general">General</option>
                            </select>
                        </div>
                        <div class="col-md-12">
                            <label class="form-label">Description</label>
                            <textarea name="description" id="categoryDescription" class="form-control" rows="3"></textarea>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Parent Category</label>
                            <select name="parent_id" id="categoryParentId" class="form-select">
                                <option value="">None (Root category)</option>
                                @foreach($allCategories as $parent)
                                    <option value="{{ $parent->id }}">{{ $parent->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Image URL</label>
                            <input type="url" name="image_url" id="categoryImageUrl" class="form-control" placeholder="https://example.com/image.jpg">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Icon (optional)</label>
                            <input type="text" name="icon" id="categoryIcon" class="form-control" placeholder="e.g. ri-map-pin-line">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Color</label>
                            <input type="color" name="color" id="categoryColor" class="form-control form-control-color" value="#3ea572">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Sort Order</label>
                            <input type="number" name="sort_order" id="categorySortOrder" class="form-control" value="0">
                        </div>
                        <div class="col-12">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" name="is_active" id="categoryIsActive" checked>
                                <label class="form-check-label" for="categoryIsActive">
                                    Active
                                </label>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="d-flex flex-wrap gap-3">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" name="show_in_menu" id="categoryShowInMenu" checked>
                                    <label class="form-check-label" for="categoryShowInMenu">
                                        Show in main menu
                                    </label>
                                </div>
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" name="show_on_homepage" id="categoryShowOnHomepage">
                                    <label class="form-check-label" for="categoryShowOnHomepage">
                                        Show on homepage
                                    </label>
                                </div>
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" name="is_featured" id="categoryIsFeatured">
                                    <label class="form-check-label" for="categoryIsFeatured">
                                        Featured category
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="ri-save-line me-1"></i>Save Category
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- View Category Modal -->
<div class="modal fade" id="viewCategoryModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Category Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="categoryDetailsContent">
                <div class="text-center py-4">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="editFromViewBtn">Edit Category</button>
            </div>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteCategoryModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Delete Category</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete category <strong id="deleteCategoryName"></strong>?</p>
                <p class="text-danger">This action cannot be undone. If this category has associated tours, it cannot be deleted.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">Cancel</button>
                <form id="deleteCategoryForm" method="POST" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">
                        <i class="ri-delete-bin-line me-1"></i>Delete
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
function viewCategory(categoryId) {
    fetch(`{{ url('admin/categories') }}/${categoryId}`, {
        headers: {
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
        .then(response => response.json())
        .then(data => {
            if (data.success && data.category) {
                const cat = data.category;
                const parentName = cat.parent ? cat.parent.name : 'None (Root category)';
                const childrenCount = cat.children ? cat.children.length : 0;
                
                document.getElementById('categoryDetailsContent').innerHTML = `
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Category Name</label>
                            <p class="mb-0">${cat.name}</p>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Slug</label>
                            <p class="mb-0"><code>${cat.slug}</code></p>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Type</label>
                            <p class="mb-0">
                                <span class="badge bg-label-${cat.type == 'tour' ? 'primary' : (cat.type == 'hotel' ? 'info' : 'secondary')}">
                                    ${cat.type.charAt(0).toUpperCase() + cat.type.slice(1)}
                                </span>
                            </p>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Parent Category</label>
                            <p class="mb-0">${parentName}</p>
                        </div>
                        <div class="col-md-12">
                            <label class="form-label fw-semibold">Description</label>
                            <p class="mb-0">${cat.description || '<em class="text-muted">No description</em>'}</p>
                        </div>
                        ${cat.image_url ? `
                        <div class="col-md-12">
                            <label class="form-label fw-semibold">Image</label>
                            <p class="mb-0">
                                <img src="${cat.image_url}" alt="${cat.name}" class="img-thumbnail" style="max-width: 200px;">
                                <br><small class="text-muted">${cat.image_url}</small>
                            </p>
                        </div>
                        ` : ''}
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Icon</label>
                            <p class="mb-0">${cat.icon ? `<i class="${cat.icon}"></i> ${cat.icon}` : '<em class="text-muted">None</em>'}</p>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Color</label>
                            <p class="mb-0">
                                ${cat.color ? `<span class="badge" style="background-color: ${cat.color}; width: 30px; height: 30px; display: inline-block; border-radius: 4px;"></span> ${cat.color}` : '<em class="text-muted">None</em>'}
                            </p>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Sort Order</label>
                            <p class="mb-0">${cat.sort_order || 0}</p>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Subcategories</label>
                            <p class="mb-0">${childrenCount} ${childrenCount == 1 ? 'subcategory' : 'subcategories'}</p>
                        </div>
                        <div class="col-md-12">
                            <label class="form-label fw-semibold">Status & Settings</label>
                            <div class="d-flex flex-wrap gap-2">
                                ${cat.is_active ? '<span class="badge bg-success">Active</span>' : '<span class="badge bg-secondary">Inactive</span>'}
                                ${cat.is_featured ? '<span class="badge bg-label-warning">Featured</span>' : ''}
                                ${cat.show_in_menu ? '<span class="badge bg-label-primary">Show in Menu</span>' : ''}
                                ${cat.show_on_homepage ? '<span class="badge bg-label-info">Show on Homepage</span>' : ''}
                            </div>
                        </div>
                        <div class="col-md-12">
                            <label class="form-label fw-semibold">Created</label>
                            <p class="mb-0">${new Date(cat.created_at).toLocaleString()}</p>
                        </div>
                        <div class="col-md-12">
                            <label class="form-label fw-semibold">Last Updated</label>
                            <p class="mb-0">${new Date(cat.updated_at).toLocaleString()}</p>
                        </div>
                    </div>
                `;
            } else {
                document.getElementById('categoryDetailsContent').innerHTML = `
                    <div class="alert alert-danger">
                        Failed to load category details.
                    </div>
                `;
            }
            document.getElementById('editFromViewBtn').onclick = () => {
                bootstrap.Modal.getInstance(document.getElementById('viewCategoryModal')).hide();
                editCategory(categoryId);
            };
            new bootstrap.Modal(document.getElementById('viewCategoryModal')).show();
        })
        .catch(error => {
            console.error('Error:', error);
            document.getElementById('categoryDetailsContent').innerHTML = `
                <div class="alert alert-danger">
                    Error loading category details. Please try again.
                </div>
            `;
            new bootstrap.Modal(document.getElementById('viewCategoryModal')).show();
        });
}

function editCategory(categoryId) {
    fetch(`{{ url('admin/categories') }}/${categoryId}/edit`, {
        headers: {
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
        .then(response => response.json())
        .then(data => {
            if (data.success && data.category) {
                const cat = data.category;
                
                // Populate form fields
                document.getElementById('categoryModalTitle').textContent = 'Edit Category';
                document.getElementById('categoryMethod').value = 'PUT';
                document.getElementById('categoryId').value = cat.id;
                document.getElementById('categoryForm').action = `{{ url('admin/categories') }}/${cat.id}`;
                
                document.getElementById('categoryName').value = cat.name || '';
                document.getElementById('categoryType').value = cat.type || '';
                document.getElementById('categoryDescription').value = cat.description || '';
                document.getElementById('categoryParentId').value = cat.parent_id || '';
                document.getElementById('categoryImageUrl').value = cat.image_url || '';
                document.getElementById('categoryIcon').value = cat.icon || '';
                document.getElementById('categoryColor').value = cat.color || '#3ea572';
                document.getElementById('categorySortOrder').value = cat.sort_order || 0;
                document.getElementById('categoryIsActive').checked = cat.is_active || false;
                document.getElementById('categoryShowInMenu').checked = cat.show_in_menu || false;
                document.getElementById('categoryShowOnHomepage').checked = cat.show_on_homepage || false;
                document.getElementById('categoryIsFeatured').checked = cat.is_featured || false;
                
                // Update parent dropdown if needed
                if (data.allCategories) {
                    const parentSelect = document.getElementById('categoryParentId');
                    const currentValue = parentSelect.value;
                    parentSelect.innerHTML = '<option value="">None (Root category)</option>';
                    data.allCategories.forEach(parent => {
                        const option = document.createElement('option');
                        option.value = parent.id;
                        option.textContent = parent.name;
                        if (parent.id == cat.parent_id) {
                            option.selected = true;
                        }
                        parentSelect.appendChild(option);
                    });
                }
                
                new bootstrap.Modal(document.getElementById('addCategoryModal')).show();
            } else {
                alert('Failed to load category data');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error loading category. Please try again.');
        });
}

function deleteCategory(categoryId, categoryName) {
    document.getElementById('deleteCategoryName').textContent = categoryName;
    document.getElementById('deleteCategoryForm').action = `{{ url('admin/categories') }}/${categoryId}`;
    new bootstrap.Modal(document.getElementById('deleteCategoryModal')).show();
}

// Reset form when modal is closed
document.getElementById('addCategoryModal').addEventListener('hidden.bs.modal', function() {
    document.getElementById('categoryForm').reset();
    document.getElementById('categoryModalTitle').textContent = 'Add Category';
    document.getElementById('categoryMethod').value = 'POST';
    document.getElementById('categoryId').value = '';
    document.getElementById('categoryForm').action = '{{ route("admin.categories.store") }}';
    document.getElementById('categoryIsActive').checked = true;
    document.getElementById('categoryShowInMenu').checked = true;
    document.getElementById('categoryShowOnHomepage').checked = false;
    document.getElementById('categoryIsFeatured').checked = false;
    document.getElementById('categoryColor').value = '#3ea572';
    document.getElementById('categorySortOrder').value = 0;
});

// Form submission
document.getElementById('categoryForm').addEventListener('submit', function(e) {
    e.preventDefault();
    const formData = new FormData(this);
    const method = formData.get('_method');
    const categoryId = formData.get('category_id');
    const url = method === 'PUT' 
        ? `{{ url('admin/categories') }}/${categoryId}`
        : '{{ route("admin.categories.store") }}';
    
    // Show loading state
    const submitBtn = this.querySelector('button[type="submit"]');
    const originalText = submitBtn.innerHTML;
    submitBtn.disabled = true;
    submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span>Saving...';
    
    // For PUT requests, Laravel requires POST with _method=PUT
    if (method === 'PUT') {
        formData.append('_method', 'PUT');
    }
    
    fetch(url, {
        method: 'POST',
        body: formData,
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        }
    })
    .then(response => {
        if (response.ok || response.redirected) {
            // Success - reload page
            window.location.reload();
        } else {
            return response.json().then(data => {
                throw new Error(data.message || 'Failed to save category');
            }).catch(() => {
                return response.text().then(html => {
                    // If response is HTML (validation errors), try to extract errors
                    throw new Error('Validation failed. Please check your input.');
                });
            });
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Error: ' + error.message);
        submitBtn.disabled = false;
        submitBtn.innerHTML = originalText;
    });
});
</script>
@endsection

