@extends('admin.layouts.app')

@section('title', 'Edit Gallery Image - Lau Paradise Adventures')
@section('description', 'Edit gallery image')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">
                        <i class="ri-edit-line me-2"></i>Edit Gallery Image: {{ $gallery->title }}
                    </h4>
                    <a href="{{ route('admin.homepage.gallery') }}" class="btn btn-label-secondary">
                        <i class="ri-arrow-left-line me-1"></i>Back to Gallery
                    </a>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.homepage.gallery.update', $gallery->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">Title <span class="text-danger">*</span></label>
                                <input type="text" name="title" class="form-control @error('title') is-invalid @enderror" value="{{ old('title', $gallery->title) }}" required>
                                @error('title')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Category</label>
                                <input type="text" name="category" class="form-control @error('category') is-invalid @enderror" value="{{ old('category', $gallery->category) }}" placeholder="e.g., Logo, Destination, Tour, Activity">
                                @error('category')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-12">
                                <label class="form-label">Description</label>
                                <textarea name="description" class="form-control @error('description') is-invalid @enderror" rows="3">{{ old('description', $gallery->description) }}</textarea>
                                @error('description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Current Image</label>
                                @php
                                    use Illuminate\Support\Facades\Storage;
                                    $currentImageUrl = str_starts_with($gallery->image_url, 'http') ? $gallery->image_url : Storage::url($gallery->image_url);
                                @endphp
                                <div class="mb-2">
                                    <img src="{{ $currentImageUrl }}" class="image-preview" alt="Current image" id="current_image_preview">
                                </div>
                                <label class="form-label">Upload New Image</label>
                                <input type="file" name="image" class="form-control @error('image') is-invalid @enderror" accept="image/*" id="image_input" onchange="previewImage(this)">
                                <small class="text-muted">Image will be automatically converted to WebP format (max 10MB)</small>
                                <div id="image_preview" class="mt-2"></div>
                                @error('image')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">OR Use Image URL</label>
                                <input type="url" name="image_url" class="form-control @error('image_url') is-invalid @enderror" value="{{ old('image_url', $gallery->image_url) }}" placeholder="https://example.com/image.jpg" id="image_url_input" onchange="previewImageUrl(this)">
                                <small class="text-muted">External image URL</small>
                                <div id="image_url_preview" class="mt-2"></div>
                                @error('image_url')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Display Order</label>
                                <input type="number" name="display_order" class="form-control @error('display_order') is-invalid @enderror" value="{{ old('display_order', $gallery->display_order) }}" min="0">
                                @error('display_order')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-3">
                                <div class="form-check mt-4">
                                    <input class="form-check-input" type="checkbox" name="is_featured" id="is_featured" value="1" {{ old('is_featured', $gallery->is_featured) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="is_featured">
                                        Featured
                                    </label>
                                </div>
                                <div class="form-check mt-2">
                                    <input class="form-check-input" type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', $gallery->is_active) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="is_active">
                                        Active
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="mt-4">
                            <button type="submit" class="btn btn-primary">
                                <i class="ri-save-line me-1"></i>Update Image
                            </button>
                            <a href="{{ route('admin.homepage.gallery') }}" class="btn btn-label-secondary">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .image-preview {
        max-width: 300px;
        max-height: 200px;
        border-radius: 4px;
        border: 1px solid #ddd;
    }
</style>
@endpush

@push('scripts')
<script>
    function previewImage(input) {
        const preview = document.getElementById('image_preview');
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                preview.innerHTML = '<img src="' + e.target.result + '" class="image-preview" alt="Preview">';
            };
            reader.readAsDataURL(input.files[0]);
        } else {
            preview.innerHTML = '';
        }
    }

    function previewImageUrl(input) {
        const preview = document.getElementById('image_url_preview');
        if (input.value) {
            preview.innerHTML = '<img src="' + input.value + '" class="image-preview" alt="Preview" onerror="this.style.display=\'none\'">';
        } else {
            preview.innerHTML = '';
        }
    }
</script>
@endpush



