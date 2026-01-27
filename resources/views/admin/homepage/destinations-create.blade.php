@extends('admin.layouts.app')

@section('title', 'Add Homepage Destination - Lau Paradise Adventures')
@section('description', 'Add a new homepage destination')

@push('styles')
<style>
    .image-preview {
        max-width: 200px;
        max-height: 150px;
        margin-top: 10px;
        border-radius: 4px;
    }
</style>
@endpush

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">
                        <i class="ri-add-line me-2"></i>Add Homepage Destination
                    </h4>
                    <a href="{{ route('admin.homepage.destinations') }}" class="btn btn-label-secondary">
                        <i class="ri-arrow-left-line me-1"></i>Back to Destinations
                    </a>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.homepage.destinations.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        
                        <!-- Basic Information -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h5 class="mb-3"><i class="ri-information-line me-2"></i>Basic Information</h5>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Destination Name <span class="text-danger">*</span></label>
                                <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" placeholder="e.g., Serengeti National Park" required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Slug / URL</label>
                                <input type="text" name="slug" class="form-control @error('slug') is-invalid @enderror" value="{{ old('slug') }}" placeholder="Auto-generated from name">
                                <small class="text-muted">Leave empty to auto-generate</small>
                                @error('slug')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-12">
                                <label class="form-label">Short Description (1-2 lines) <span class="text-danger">*</span></label>
                                <textarea name="short_description" class="form-control @error('short_description') is-invalid @enderror" rows="2" placeholder="Experience the Great Migration and Tanzania's breathtaking wildlife." required>{{ old('short_description') }}</textarea>
                                <small class="text-muted">This appears on homepage cards</small>
                                @error('short_description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-12">
                                <label class="form-label">Full Description (Optional)</label>
                                <textarea name="full_description" class="form-control @error('full_description') is-invalid @enderror" rows="5" placeholder="Detailed information about the destination...">{{ old('full_description') }}</textarea>
                                <small class="text-muted">Displayed on destination detail page</small>
                                @error('full_description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Location -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h5 class="mb-3"><i class="ri-map-pin-line me-2"></i>Location</h5>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Country</label>
                                <input type="text" name="country" class="form-control @error('country') is-invalid @enderror" value="{{ old('country') }}" placeholder="e.g., Tanzania">
                                @error('country')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Region</label>
                                <input type="text" name="region" class="form-control @error('region') is-invalid @enderror" value="{{ old('region') }}" placeholder="e.g., Arusha Region">
                                @error('region')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">City</label>
                                <input type="text" name="city" class="form-control @error('city') is-invalid @enderror" value="{{ old('city') }}" placeholder="e.g., Arusha">
                                @error('city')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Images -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h5 class="mb-3"><i class="ri-image-line me-2"></i>Images</h5>
                                <div class="alert alert-info">
                                    <i class="ri-information-line me-2"></i>
                                    <strong>Note:</strong> You can select images from <code>public/images</code> directory or upload to Gallery first.
                                </div>
                            </div>
                            
                            <!-- Featured Image -->
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Featured Image URL</label>
                                <div class="input-group">
                                    <input type="text" name="featured_image_url" id="featured_image_url" class="form-control" value="{{ old('featured_image_url') }}" placeholder="images/example.jpg">
                                    <button type="button" class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#imageBrowserModal" onclick="setImageTarget('featured_image_url')">
                                        <i class="ri-folder-open-line me-1"></i>Browse
                                    </button>
                                </div>
                                <small class="text-muted">Path from public/ directory (e.g., images/serengeti.jpg)</small>
                                <div id="featured_image_preview" class="mt-2">
                                    @if(old('featured_image_url'))
                                    <img src="{{ asset(old('featured_image_url')) }}" alt="Preview" class="img-thumbnail" style="max-width: 200px; max-height: 150px;">
                                    @endif
                                </div>
                            </div>
                            
                            <!-- OG Image -->
                            <div class="col-md-6 mb-3">
                                <label class="form-label">OG Image URL (Social Media)</label>
                                <div class="input-group">
                                    <input type="text" name="og_image_url" id="og_image_url" class="form-control" value="{{ old('og_image_url') }}" placeholder="images/og-image.jpg">
                                    <button type="button" class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#imageBrowserModal" onclick="setImageTarget('og_image_url')">
                                        <i class="ri-folder-open-line me-1"></i>Browse
                                    </button>
                                </div>
                                <small class="text-muted">Path from public/ directory</small>
                                <div id="og_image_preview" class="mt-2">
                                    @if(old('og_image_url'))
                                    <img src="{{ asset(old('og_image_url')) }}" alt="Preview" class="img-thumbnail" style="max-width: 200px; max-height: 150px;">
                                    @endif
                                </div>
                            </div>
                            
                            <!-- Gallery Images -->
                            <div class="col-12">
                                <label class="form-label">Gallery Images (Optional)</label>
                                <div class="input-group mb-2">
                                    <input type="text" id="gallery_images_input" class="form-control" placeholder="Comma-separated image paths (e.g., images/img1.jpg, images/img2.jpg)">
                                    <button type="button" class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#imageBrowserModal" onclick="setImageTarget('gallery_images_input', true)">
                                        <i class="ri-folder-open-line me-1"></i>Browse Multiple
                                    </button>
                                </div>
                                <input type="hidden" name="image_gallery" id="image_gallery" value="{{ old('image_gallery', '[]') }}">
                                <div id="gallery_images_preview" class="row g-2 mt-2"></div>
                                <small class="text-muted">Select multiple images from public/images directory</small>
                            </div>
                        </div>
                        
                        <!-- Image Browser Modal -->
                        <div class="modal fade" id="imageBrowserModal" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog modal-xl">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Browse Images from public/images</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="row mb-3">
                                            <div class="col-md-6">
                                                <input type="text" class="form-control" id="image_search" placeholder="Search images..." onkeyup="filterImages()">
                                            </div>
                                            <div class="col-md-6">
                                                <select class="form-select" id="image_folder" onchange="loadImages()">
                                                    <option value="images">All Images</option>
                                                    <option value="images/hero-slider">Hero Slider</option>
                                                    <option value="images/tours">Tours</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div id="images_grid" class="row g-3" style="max-height: 500px; overflow-y: auto;">
                                            <!-- Images will be loaded here -->
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-primary" onclick="selectImage()">Select</button>
                                        <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">Cancel</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Gallery Multi-Select Modal -->
                        <div class="modal fade" id="galleryPickerModal" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog modal-xl">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Select Gallery Images</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="row mb-3">
                                            <div class="col-md-6">
                                                <input type="text" class="form-control" id="gallery_multi_search" placeholder="Search..." onkeyup="loadMultiGalleryImages()">
                                            </div>
                                            <div class="col-md-6">
                                                <select class="form-select" id="gallery_multi_category" onchange="loadMultiGalleryImages()">
                                                    <option value="">All Categories</option>
                                                    @php
                                                        $categories = \App\Models\Gallery::distinct()->whereNotNull('category')->pluck('category');
                                                    @endphp
                                                    @foreach($categories as $category)
                                                    <option value="{{ $category }}">{{ $category }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div id="gallery_multi_grid" class="row g-3" style="max-height: 400px; overflow-y: auto;"></div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-primary" onclick="confirmGallerySelection()">Confirm Selection</button>
                                        <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">Cancel</button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Category & Pricing -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h5 class="mb-3"><i class="ri-price-tag-3-line me-2"></i>Category & Pricing</h5>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Category</label>
                                <select name="category" class="form-select @error('category') is-invalid @enderror">
                                    <option value="">Select Category</option>
                                    <option value="Mountain Trekking" {{ old('category') == 'Mountain Trekking' ? 'selected' : '' }}>Mountain Trekking</option>
                                    <option value="National Parks" {{ old('category') == 'National Parks' ? 'selected' : '' }}>National Parks</option>
                                    <option value="Beaches" {{ old('category') == 'Beaches' ? 'selected' : '' }}>Beaches</option>
                                    <option value="City Tours" {{ old('category') == 'City Tours' ? 'selected' : '' }}>City Tours</option>
                                    <option value="Cultural Experiences" {{ old('category') == 'Cultural Experiences' ? 'selected' : '' }}>Cultural Experiences</option>
                                </select>
                                @error('category')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Price (Starting From)</label>
                                <input type="number" name="price" class="form-control @error('price') is-invalid @enderror" value="{{ old('price') }}" step="0.01" min="0" placeholder="350.00">
                                @error('price')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Price Display</label>
                                <input type="text" name="price_display" class="form-control @error('price_display') is-invalid @enderror" value="{{ old('price_display') }}" placeholder="Starting from $350 per person">
                                <small class="text-muted">Or "Contact for price"</small>
                                @error('price_display')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Duration</label>
                                <input type="text" name="duration" class="form-control @error('duration') is-invalid @enderror" value="{{ old('duration') }}" placeholder="e.g., 3 days / 2 nights">
                                @error('duration')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Rating (0-5)</label>
                                <input type="number" name="rating" class="form-control @error('rating') is-invalid @enderror" value="{{ old('rating') }}" step="0.1" min="0" max="5" placeholder="4.8">
                                <small class="text-muted">For homepage stars display</small>
                                @error('rating')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- SEO Details -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h5 class="mb-3"><i class="ri-search-line me-2"></i>SEO Details</h5>
                            </div>
                            <div class="col-12">
                                <label class="form-label">Meta Title</label>
                                <input type="text" name="meta_title" class="form-control @error('meta_title') is-invalid @enderror" value="{{ old('meta_title') }}" placeholder="SEO title for search engines">
                                @error('meta_title')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-12">
                                <label class="form-label">Meta Description</label>
                                <textarea name="meta_description" class="form-control @error('meta_description') is-invalid @enderror" rows="2" placeholder="SEO description for search engines">{{ old('meta_description') }}</textarea>
                                @error('meta_description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-12">
                                <label class="form-label">Meta Keywords</label>
                                <input type="text" name="meta_keywords" class="form-control @error('meta_keywords') is-invalid @enderror" value="{{ old('meta_keywords') }}" placeholder="keyword1, keyword2, keyword3">
                                <small class="text-muted">Comma-separated keywords</small>
                                @error('meta_keywords')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Settings -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h5 class="mb-3"><i class="ri-settings-3-line me-2"></i>Settings</h5>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Display Order</label>
                                <input type="number" name="display_order" class="form-control @error('display_order') is-invalid @enderror" value="{{ old('display_order') }}" min="0">
                                <small class="text-muted">Lower numbers appear first</small>
                                @error('display_order')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-8">
                                <div class="form-check mt-4">
                                    <input class="form-check-input" type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="is_active">
                                        Active (Visible on homepage)
                                    </label>
                                </div>
                                <div class="form-check mt-2">
                                    <input class="form-check-input" type="checkbox" name="is_featured" id="is_featured" value="1" {{ old('is_featured') ? 'checked' : '' }}>
                                    <label class="form-check-label" for="is_featured">
                                        Featured (Appears in homepage top section)
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="mt-4">
                            <button type="submit" class="btn btn-primary">
                                <i class="ri-save-line me-1"></i>Create Destination
                            </button>
                            <a href="{{ route('admin.homepage.destinations') }}" class="btn btn-label-secondary">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    let selectedGalleryImages = [];
    let currentImageTarget = null;
    let isMultipleSelection = false;
    let selectedPublicImages = [];
    
    // Image browser for public/images
    document.getElementById('imageBrowserModal')?.addEventListener('show.bs.modal', function() {
        loadImages();
    });
    
    function setImageTarget(targetId, multiple = false) {
        currentImageTarget = targetId;
        isMultipleSelection = multiple;
        if (!multiple) {
            selectedPublicImages = [];
        }
    }
    
    function loadImages() {
        const grid = document.getElementById('images_grid');
        const folder = document.getElementById('image_folder')?.value || 'images';
        
        grid.innerHTML = '<div class="col-12 text-center py-5"><div class="spinner-border text-primary"></div><p class="mt-2">Loading images...</p></div>';
        
        // Fetch available images from server
        fetch(`/admin/homepage/destinations/get-images?folder=${encodeURIComponent(folder)}`, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            grid.innerHTML = '';
            if (data.images && data.images.length > 0) {
                data.images.forEach(image => {
                    const isSelected = selectedPublicImages.includes(image.path);
                    const col = document.createElement('div');
                    col.className = 'col-md-3 col-sm-4 col-6';
                    col.innerHTML = `
                        <div class="card h-100 ${isSelected ? 'border-primary border-2' : ''}" style="cursor: pointer;" onclick="togglePublicImage('${image.path.replace(/'/g, "\\'")}')">
                            <div class="position-relative">
                                <img src="${image.url}" class="card-img-top" style="height: 150px; object-fit: cover;" loading="lazy">
                                ${isSelected ? '<span class="badge bg-primary position-absolute top-0 end-0 m-1"><i class="ri-check-line"></i></span>' : ''}
                            </div>
                            <div class="card-body p-2">
                                <p class="card-text small mb-0 text-truncate" title="${image.name}">${image.name}</p>
                            </div>
                        </div>
                    `;
                    grid.appendChild(col);
                });
            } else {
                grid.innerHTML = '<div class="col-12 text-center py-4"><p class="text-muted">No images found in this folder</p></div>';
            }
        })
        .catch(error => {
            console.error('Error loading images:', error);
            grid.innerHTML = '<div class="col-12 text-center py-4"><p class="text-danger">Error loading images</p></div>';
        });
    }
    
    function filterImages() {
        const search = document.getElementById('image_search')?.value.toLowerCase() || '';
        const cards = document.querySelectorAll('#images_grid .card');
        cards.forEach(card => {
            const text = card.textContent.toLowerCase();
            card.closest('.col-md-3').style.display = text.includes(search) ? '' : 'none';
        });
    }
    
    function togglePublicImage(imagePath) {
        if (isMultipleSelection) {
            const index = selectedPublicImages.indexOf(imagePath);
            if (index > -1) {
                selectedPublicImages.splice(index, 1);
            } else {
                selectedPublicImages.push(imagePath);
            }
            loadImages();
            updateGalleryPreview();
        } else {
            selectedPublicImages = [imagePath];
            if (currentImageTarget) {
                document.getElementById(currentImageTarget).value = imagePath;
                updateImagePreview(currentImageTarget, imagePath);
            }
        }
    }
    
    function selectImage() {
        if (isMultipleSelection && selectedPublicImages.length > 0) {
            const galleryInput = document.getElementById('gallery_images_input');
            galleryInput.value = selectedPublicImages.join(', ');
            updateGalleryPreview();
        }
        bootstrap.Modal.getInstance(document.getElementById('imageBrowserModal')).hide();
    }
    
    function updateImagePreview(targetId, imagePath) {
        const previewId = targetId.replace('_url', '_preview');
        const preview = document.getElementById(previewId);
        if (preview && imagePath) {
            preview.innerHTML = `<img src="${imagePath.startsWith('http') ? imagePath : '/' + imagePath}" alt="Preview" class="img-thumbnail" style="max-width: 200px; max-height: 150px;">`;
        }
    }
    
    function updateGalleryPreview() {
        const preview = document.getElementById('gallery_images_preview');
        const hiddenInput = document.getElementById('image_gallery');
        
        if (isMultipleSelection && selectedPublicImages.length > 0) {
            const imageArray = selectedPublicImages.map(path => path.startsWith('http') ? path : '/' + path);
            hiddenInput.value = JSON.stringify(imageArray);
            
            preview.innerHTML = '';
            selectedPublicImages.forEach((path, index) => {
                const imageUrl = path.startsWith('http') ? path : '/' + path;
                const col = document.createElement('div');
                col.className = 'col-md-2 col-sm-3 col-4';
                col.innerHTML = `
                    <div class="position-relative">
                        <img src="${imageUrl}" class="img-fluid rounded" style="height: 100px; width: 100%; object-fit: cover;">
                        <button type="button" class="btn btn-sm btn-danger position-absolute top-0 end-0 m-1" onclick="removeGalleryImage('${path.replace(/'/g, "\\'")}')">
                            <i class="ri-close-line"></i>
                        </button>
                    </div>
                `;
                preview.appendChild(col);
            });
        }
    }
    
    function removeGalleryImage(path) {
        selectedPublicImages = selectedPublicImages.filter(p => p !== path);
        updateGalleryPreview();
        loadImages();
    }
    
    // Preview on input change
    document.getElementById('featured_image_url')?.addEventListener('input', function() {
        if (this.value) {
            updateImagePreview('featured_image_url', this.value);
        }
    });
    
    document.getElementById('og_image_url')?.addEventListener('input', function() {
        if (this.value) {
            updateImagePreview('og_image_url', this.value);
        }
    });
    
    // Load gallery images for multi-select (existing functionality)
    document.getElementById('galleryPickerModal')?.addEventListener('show.bs.modal', function() {
        loadMultiGalleryImages();
    });
    
    function loadMultiGalleryImages() {
        const grid = document.getElementById('gallery_multi_grid');
        const search = document.getElementById('gallery_multi_search')?.value || '';
        const category = document.getElementById('gallery_multi_category')?.value || '';
        
        grid.innerHTML = '<div class="col-12 text-center py-3"><div class="spinner-border text-primary"></div></div>';
        
        fetch(`{{ route('admin.homepage.gallery.images') }}?search=${encodeURIComponent(search)}&category=${encodeURIComponent(category)}`, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(data => {
            grid.innerHTML = '';
            if (!data || data.length === 0) {
                grid.innerHTML = '<div class="col-12 text-center py-5"><p class="text-muted">No images found.</p></div>';
                return;
            }
            
            data.forEach(image => {
                if (!image || !image.id || !image.url) {
                    return; // Skip invalid images
                }
                
                const isSelected = selectedGalleryImages.some(img => img.id === image.id);
                const col = document.createElement('div');
                col.className = 'col-md-3';
                
                // Properly escape HTML to prevent XSS
                const imageId = image.id;
                const imageUrl = String(image.url || '').replace(/"/g, '&quot;').replace(/'/g, '&#39;');
                const imageTitle = String(image.title || 'Untitled').replace(/"/g, '&quot;').replace(/'/g, '&#39;');
                
                col.innerHTML = `
                    <div class="card h-100 ${isSelected ? 'border-primary' : ''}" style="cursor: pointer;" onclick="toggleGalleryImage(${imageId}, '${imageUrl}', '${imageTitle}')">
                        <div class="position-relative">
                            <img src="${imageUrl}" class="card-img-top" style="height: 150px; object-fit: cover;" onerror="this.src='data:image/svg+xml,%3Csvg xmlns=\'http://www.w3.org/2000/svg\' width=\'150\' height=\'150\'%3E%3Crect fill=\'%23ddd\' width=\'150\' height=\'150\'/%3E%3Ctext x=\'50%25\' y=\'50%25\' text-anchor=\'middle\' dy=\'.3em\' fill=\'%23999\'%3EImage%3C/text%3E%3C/svg%3E'">
                            ${isSelected ? '<span class="badge bg-primary position-absolute top-0 end-0 m-1"><i class="ri-check-line"></i></span>' : ''}
                        </div>
                        <div class="card-body p-2">
                            <p class="card-text small mb-0">${imageTitle}</p>
                        </div>
                    </div>
                `;
                grid.appendChild(col);
            });
        })
        .catch(error => {
            grid.innerHTML = '<div class="col-12 text-center py-5"><p class="text-danger">Error loading images. Please try again.</p></div>';
            console.error('Error loading gallery images:', error);
        });
    }
    
    function toggleGalleryImage(id, url, title) {
        const index = selectedGalleryImages.findIndex(img => img.id === id);
        if (index > -1) {
            selectedGalleryImages.splice(index, 1);
        } else {
            selectedGalleryImages.push({id, url, title});
        }
        loadMultiGalleryImages();
        updateGalleryPreview();
    }
    
    function updateGalleryPreview() {
        const preview = document.getElementById('gallery_images_selected');
        const idsInput = document.getElementById('gallery_image_ids');
        
        idsInput.value = selectedGalleryImages.map(img => img.id).join(',');
        
        preview.innerHTML = '';
        selectedGalleryImages.forEach((image, index) => {
            const col = document.createElement('div');
            col.className = 'col-md-2';
            col.innerHTML = `
                <div class="position-relative">
                    <img src="${image.url}" class="img-fluid rounded" style="height: 100px; width: 100%; object-fit: cover;">
                    <button type="button" class="btn btn-sm btn-danger position-absolute top-0 end-0 m-1" onclick="removeGalleryImage(${image.id})">
                        <i class="ri-close-line"></i>
                    </button>
                    <span class="badge bg-primary position-absolute bottom-0 start-0 m-1">${index + 1}</span>
                </div>
            `;
            preview.appendChild(col);
        });
    }
    
    function removeGalleryImage(id) {
        selectedGalleryImages = selectedGalleryImages.filter(img => img.id !== id);
        updateGalleryPreview();
        loadMultiGalleryImages();
    }
    
    function confirmGallerySelection() {
        const modal = bootstrap.Modal.getInstance(document.getElementById('galleryPickerModal'));
        modal.hide();
    }
</script>
@endpush

