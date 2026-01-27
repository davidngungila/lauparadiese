@extends('admin.layouts.app')

@section('title', 'Create Hero Slide - Lau Paradise Adventures')

@push('styles')
<style>
    .image-preview {
        max-width: 100%;
        max-height: 300px;
        border-radius: 0.5rem;
        margin-top: 1rem;
    }
    .icon-picker {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(80px, 1fr));
        gap: 0.5rem;
        max-height: 200px;
        overflow-y: auto;
        padding: 1rem;
        border: 1px solid #e7e9ec;
        border-radius: 0.5rem;
        background: #f8f9fa;
    }
    .icon-option {
        padding: 0.5rem;
        text-align: center;
        border: 1px solid #e7e9ec;
        border-radius: 0.25rem;
        cursor: pointer;
        transition: all 0.2s;
    }
    .icon-option:hover {
        background: #3ea572;
        color: white;
        border-color: #3ea572;
    }
    .icon-option.selected {
        background: #3ea572;
        color: white;
        border-color: #3ea572;
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
                        <i class="ri-add-line me-2"></i>Create Hero Slide
                    </h4>
                    <a href="{{ route('admin.homepage.hero-slider') }}" class="btn btn-label-secondary">
                        <i class="ri-arrow-left-line me-1"></i>Back
                    </a>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.homepage.hero-slider.store') }}" method="POST">
                        @csrf
                        
                        <!-- Basic Information -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h5 class="mb-3"><i class="ri-file-text-line me-2"></i>Content</h5>
                            </div>
                            <div class="col-md-12 mb-3">
                                <label class="form-label">Title <span class="text-danger">*</span></label>
                                <input type="text" name="title" class="form-control @error('title') is-invalid @enderror" value="{{ old('title') }}" placeholder="e.g., Tanzania Wildlife Safaris" required>
                                @error('title')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-12 mb-3">
                                <label class="form-label">Subtitle</label>
                                <textarea name="subtitle" class="form-control @error('subtitle') is-invalid @enderror" rows="2" placeholder="Brief description that appears below the title">{{ old('subtitle') }}</textarea>
                                @error('subtitle')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Badge Text</label>
                                <input type="text" name="badge_text" class="form-control" value="{{ old('badge_text') }}" placeholder="e.g., Best Seller">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Badge Icon</label>
                                <input type="text" name="badge_icon" class="form-control" value="{{ old('badge_icon') }}" placeholder="e.g., fas fa-star">
                                <small class="text-muted">Font Awesome icon class</small>
                            </div>
                        </div>

                        <!-- Image Selection -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h5 class="mb-3"><i class="ri-image-line me-2"></i>Image</h5>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Select from Gallery</label>
                                <select name="image_id" id="image_id" class="form-select" onchange="updateImagePreview()">
                                    <option value="">Select Image from Gallery</option>
                                    @foreach($galleryImages as $galleryImage)
                                        <option value="{{ $galleryImage->id }}" data-image-url="{{ $galleryImage->display_url }}">
                                            {{ $galleryImage->title }}
                                        </option>
                                    @endforeach
                                </select>
                                <small class="text-muted">Choose an image from your gallery</small>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">OR Enter Image URL</label>
                                <input type="text" name="image_url" id="image_url" class="form-control" value="{{ old('image_url') }}" placeholder="images/hero-slider/image.jpg or animal-movement.jpg" onchange="updateImagePreview()" onkeyup="updateImagePreview()">
                                <small class="text-muted">Relative path (images/hero-slider/...) or filename (will use images/hero-slider/)</small>
                            </div>
                            <div class="col-12">
                                <div id="imagePreview" class="mt-2"></div>
                                @error('image_id')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                                @error('image_url')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Buttons/Actions -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h5 class="mb-3"><i class="ri-links-line me-2"></i>Action Buttons</h5>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Primary Button Text</label>
                                <input type="text" name="primary_button_text" class="form-control" value="{{ old('primary_button_text') }}" placeholder="e.g., Explore Safaris">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Primary Button Link</label>
                                <input type="text" name="primary_button_link" class="form-control" value="{{ old('primary_button_link') }}" placeholder="e.g., /tours or https://...">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Primary Button Icon</label>
                                <input type="text" name="primary_button_icon" class="form-control" value="{{ old('primary_button_icon') }}" placeholder="e.g., fas fa-compass">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Secondary Button Text</label>
                                <input type="text" name="secondary_button_text" class="form-control" value="{{ old('secondary_button_text') }}" placeholder="e.g., Book Now">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Secondary Button Link</label>
                                <input type="text" name="secondary_button_link" class="form-control" value="{{ old('secondary_button_link') }}" placeholder="e.g., /booking">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Secondary Button Icon</label>
                                <input type="text" name="secondary_button_icon" class="form-control" value="{{ old('secondary_button_icon') }}" placeholder="e.g., fas fa-calendar-check">
                            </div>
                        </div>

                        <!-- Display Settings -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h5 class="mb-3"><i class="ri-settings-3-line me-2"></i>Display Settings</h5>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Display Order</label>
                                <input type="number" name="display_order" class="form-control" value="{{ old('display_order') }}" min="0">
                                <small class="text-muted">Lower numbers appear first</small>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Animation Type</label>
                                <select name="animation_type" class="form-select">
                                    <option value="fade-in-up" {{ old('animation_type', 'fade-in-up') == 'fade-in-up' ? 'selected' : '' }}>Fade In Up</option>
                                    <option value="slide-left" {{ old('animation_type') == 'slide-left' ? 'selected' : '' }}>Slide Left</option>
                                    <option value="slide-right" {{ old('animation_type') == 'slide-right' ? 'selected' : '' }}>Slide Right</option>
                                    <option value="zoom-in" {{ old('animation_type') == 'zoom-in' ? 'selected' : '' }}>Zoom In</option>
                                </select>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Overlay Type</label>
                                <select name="overlay_type" class="form-select">
                                    <option value="gradient" {{ old('overlay_type', 'gradient') == 'gradient' ? 'selected' : '' }}>Gradient</option>
                                    <option value="dark" {{ old('overlay_type') == 'dark' ? 'selected' : '' }}>Dark</option>
                                    <option value="light" {{ old('overlay_type') == 'light' ? 'selected' : '' }}>Light</option>
                                    <option value="none" {{ old('overlay_type') == 'none' ? 'selected' : '' }}>None</option>
                                </select>
                            </div>
                            <div class="col-md-12 mb-3">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="is_active">
                                        Active (Slide will be displayed)
                                    </label>
                                </div>
                            </div>
                        </div>

                        <!-- Form Actions -->
                        <div class="d-flex justify-content-between align-items-center mt-4 pt-3 border-top">
                            <a href="{{ route('admin.homepage.hero-slider') }}" class="btn btn-label-secondary">
                                <i class="ri-close-line me-1"></i>Cancel
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="ri-save-line me-1"></i>Create Slide
                            </button>
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
function updateImagePreview() {
    const preview = document.getElementById('imagePreview');
    const imageIdSelect = document.getElementById('image_id');
    const imageUrlInput = document.getElementById('image_url');
    
    let imageUrl = null;
    
    // Check if gallery image is selected
    if (imageIdSelect.value) {
        const selectedOption = imageIdSelect.options[imageIdSelect.selectedIndex];
        imageUrl = selectedOption.getAttribute('data-image-url');
        // Clear URL input when gallery image is selected
        imageUrlInput.value = '';
    } else if (imageUrlInput.value) {
        // Use URL input - handle images/hero-slider/ paths correctly
        const inputValue = imageUrlInput.value.trim();
        if (inputValue.startsWith('http://') || inputValue.startsWith('https://')) {
            // Full URL
            imageUrl = inputValue;
        } else if (inputValue.startsWith('images/')) {
            // Relative path starting with images/ - use asset() helper
            imageUrl = '{{ asset("") }}' + inputValue;
        } else if (inputValue.startsWith('/')) {
            // Absolute path
            imageUrl = inputValue;
        } else {
            // Assume it's in images/hero-slider/ folder
            imageUrl = '{{ asset("images/hero-slider/") }}/' + inputValue;
        }
        // Clear gallery selection when URL is entered
        imageIdSelect.value = '';
    }
    
    if (imageUrl) {
        preview.innerHTML = `
            <div class="position-relative">
                <img src="${imageUrl}" alt="Preview" class="image-preview" onerror="this.onerror=null; this.src='{{ asset('images/safari_home-1.jpg') }}';">
                <div class="mt-2">
                    <small class="text-muted">Image URL: <code>${imageUrl}</code></small>
                </div>
            </div>
        `;
    } else {
        preview.innerHTML = '<p class="text-muted small">No image selected. Select from gallery or enter image URL.</p>';
    }
}

// Initialize preview on page load
document.addEventListener('DOMContentLoaded', function() {
    updateImagePreview();
});
</script>
@endpush

