{{-- Image Picker Component --}}
{{-- Usage: @include('admin.partials.image-picker', ['name' => 'featured_image_id', 'label' => 'Featured Image', 'value' => $destination->featured_image_id ?? null]) --}}

@php
    use App\Services\ImageService;
    $imageService = new ImageService();
    $selectedImage = isset($value) && $value ? \App\Models\Gallery::find($value) : null;
    $selectedImageUrl = $selectedImage ? $selectedImage->display_url : null;
@endphp

<div class="image-picker-wrapper">
    <label class="form-label">{{ $label ?? 'Select Image' }} <span class="text-danger">*</span></label>
    
    <div class="d-flex gap-2 mb-2">
        <input type="hidden" name="{{ $name }}" id="{{ $name }}" value="{{ $value ?? '' }}">
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#imagePickerModal_{{ $name }}">
            <i class="ri-image-line me-1"></i>Select from Gallery
        </button>
        @if($selectedImage)
        <button type="button" class="btn btn-outline-danger" onclick="clearImagePicker('{{ $name }}')">
            <i class="ri-close-line me-1"></i>Clear
        </button>
        @endif
    </div>
    
    <div id="selected_image_preview_{{ $name }}" class="mt-2">
        @if($selectedImage && $selectedImageUrl)
        <div class="border rounded p-2" style="max-width: 300px;">
            <img src="{{ $selectedImageUrl }}" alt="{{ $selectedImage->title }}" class="img-fluid rounded" style="max-height: 200px;">
            <p class="mb-0 mt-1 small"><strong>{{ $selectedImage->title }}</strong></p>
            @if($selectedImage->category)
            <p class="mb-0 small text-muted">Category: {{ $selectedImage->category }}</p>
            @endif
        </div>
        @else
        <p class="text-muted small">No image selected. Click "Select from Gallery" to choose an image.</p>
        @endif
    </div>
</div>

<!-- Image Picker Modal -->
<div class="modal fade" id="imagePickerModal_{{ $name }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Select Image from Gallery</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Filters -->
                <div class="row mb-3">
                    <div class="col-md-4">
                        <input type="text" class="form-control" id="gallery_search_{{ $name }}" placeholder="Search images..." onkeyup="filterGalleryImages('{{ $name }}')">
                    </div>
                    <div class="col-md-4">
                        <select class="form-select" id="gallery_category_{{ $name }}" onchange="filterGalleryImages('{{ $name }}')">
                            <option value="">All Categories</option>
                            @php
                                $categories = \App\Models\Gallery::distinct()->whereNotNull('category')->pluck('category');
                            @endphp
                            @foreach($categories as $category)
                            <option value="{{ $category }}">{{ $category }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4">
                        <a href="{{ route('admin.homepage.gallery.create') }}" target="_blank" class="btn btn-outline-primary w-100">
                            <i class="ri-add-line me-1"></i>Upload New Image
                        </a>
                    </div>
                </div>
                
                <!-- Gallery Grid -->
                <div id="gallery_grid_{{ $name }}" class="row g-3" style="max-height: 500px; overflow-y: auto;">
                    <div class="col-12 text-center py-5">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                        <p class="mt-2 text-muted">Loading gallery images...</p>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">Cancel</button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Load gallery images when modal opens
    document.getElementById('imagePickerModal_{{ $name }}')?.addEventListener('show.bs.modal', function() {
        loadGalleryImages('{{ $name }}');
    });

    function loadGalleryImages(pickerName) {
        const grid = document.getElementById('gallery_grid_' + pickerName);
        const search = document.getElementById('gallery_search_' + pickerName)?.value || '';
        const category = document.getElementById('gallery_category_' + pickerName)?.value || '';
        
        grid.innerHTML = '<div class="col-12 text-center py-3"><div class="spinner-border text-primary" role="status"></div></div>';
        
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
                grid.innerHTML = '<div class="col-12 text-center py-5"><p class="text-muted">No images found. <a href="{{ route('admin.homepage.gallery.create') }}" target="_blank">Upload one</a></p></div>';
                return;
            }
            
            data.forEach(image => {
                if (!image || !image.id || !image.url) {
                    return; // Skip invalid images
                }
                
                const col = document.createElement('div');
                col.className = 'col-md-3';
                
                // Properly escape HTML to prevent XSS
                const imageId = image.id;
                const imageUrl = String(image.url || '').replace(/"/g, '&quot;').replace(/'/g, '&#39;');
                const imageTitle = String(image.title || 'Untitled').replace(/"/g, '&quot;').replace(/'/g, '&#39;');
                const imageCategory = image.category ? String(image.category).replace(/"/g, '&quot;').replace(/'/g, '&#39;') : '';
                
                col.innerHTML = `
                    <div class="card h-100 cursor-pointer" onclick="selectGalleryImage('${pickerName}', ${imageId}, '${imageUrl}', '${imageTitle}')" style="cursor: pointer;">
                        <img src="${imageUrl}" class="card-img-top" style="height: 150px; object-fit: cover;" alt="${imageTitle}" onerror="this.src='data:image/svg+xml,%3Csvg xmlns=\'http://www.w3.org/2000/svg\' width=\'150\' height=\'150\'%3E%3Crect fill=\'%23ddd\' width=\'150\' height=\'150\'/%3E%3Ctext x=\'50%25\' y=\'50%25\' text-anchor=\'middle\' dy=\'.3em\' fill=\'%23999\'%3EImage%3C/text%3E%3C/svg%3E'">
                        <div class="card-body p-2">
                            <p class="card-text small mb-0" style="font-size: 0.85rem;">${imageTitle}</p>
                            ${imageCategory ? `<span class="badge bg-label-info" style="font-size: 0.7rem;">${imageCategory}</span>` : ''}
                        </div>
                    </div>
                `;
                grid.appendChild(col);
            });
        })
        .catch(error => {
            grid.innerHTML = '<div class="col-12 text-center py-5"><p class="text-danger">Error loading images. Please try again.</p><p class="text-muted small">' + (error.message || 'Unknown error') + '</p></div>';
            console.error('Error loading gallery images:', error);
        });
    }

    function filterGalleryImages(pickerName) {
        loadGalleryImages(pickerName);
    }

    function selectGalleryImage(pickerName, imageId, imageUrl, imageTitle) {
        if (!imageId || !imageUrl) {
            console.error('Invalid image data');
            return;
        }
        
        document.getElementById(pickerName).value = imageId;
        const preview = document.getElementById('selected_image_preview_' + pickerName);
        
        // Properly escape HTML to prevent XSS
        const escapedUrl = String(imageUrl || '').replace(/"/g, '&quot;').replace(/'/g, '&#39;');
        const escapedTitle = String(imageTitle || 'Untitled').replace(/"/g, '&quot;').replace(/'/g, '&#39;');
        
        preview.innerHTML = `
            <div class="border rounded p-2" style="max-width: 300px;">
                <img src="${escapedUrl}" alt="${escapedTitle}" class="img-fluid rounded" style="max-height: 200px;" onerror="this.src='data:image/svg+xml,%3Csvg xmlns=\'http://www.w3.org/2000/svg\' width=\'300\' height=\'200\'%3E%3Crect fill=\'%23ddd\' width=\'300\' height=\'200\'/%3E%3Ctext x=\'50%25\' y=\'50%25\' text-anchor=\'middle\' dy=\'.3em\' fill=\'%23999\'%3EImage%3C/text%3E%3C/svg%3E'">
                <p class="mb-0 mt-1 small"><strong>${escapedTitle}</strong></p>
            </div>
        `;
        
        // Close modal
        const modalElement = document.getElementById('imagePickerModal_' + pickerName);
        if (modalElement) {
            const modal = bootstrap.Modal.getInstance(modalElement);
            if (modal) {
                modal.hide();
            }
        }
    }

    function clearImagePicker(pickerName) {
        document.getElementById(pickerName).value = '';
        document.getElementById('selected_image_preview_' + pickerName).innerHTML = '<p class="text-muted small">No image selected. Click "Select from Gallery" to choose an image.</p>';
    }
</script>
@endpush

