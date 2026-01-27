@extends('admin.layouts.app')

@section('title', 'Upload Gallery Images')

@push('styles')
<style>
    .upload-zone {
        border: 2px dashed #d9dee3;
        border-radius: 0.5rem;
        padding: 3rem;
        text-align: center;
        background: #f8f9fa;
        transition: all 0.3s;
        cursor: pointer;
        min-height: 200px;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
    }
    .upload-zone:hover,
    .upload-zone.dragover {
        border-color: #3ea572;
        background: #f0f9f4;
    }
    .upload-zone.dragover {
        border-width: 3px;
    }
    .image-preview-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
        gap: 1rem;
        margin-top: 1.5rem;
    }
    .preview-item {
        position: relative;
        border: 1px solid #e7e9ec;
        border-radius: 0.5rem;
        overflow: hidden;
        background: #fff;
    }
    .preview-item img {
        width: 100%;
        height: 150px;
        object-fit: cover;
        display: block;
    }
    .preview-item .remove-btn {
        position: absolute;
        top: 5px;
        right: 5px;
        background: rgba(255, 0, 0, 0.8);
        color: white;
        border: none;
        border-radius: 50%;
        width: 30px;
        height: 30px;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        font-size: 1.2rem;
        line-height: 1;
    }
    .preview-item .remove-btn:hover {
        background: rgba(255, 0, 0, 1);
    }
    .preview-item .file-name {
        padding: 0.5rem;
        font-size: 0.75rem;
        color: #566a7f;
        text-overflow: ellipsis;
        overflow: hidden;
        white-space: nowrap;
    }
    .upload-progress {
        margin-top: 1rem;
        display: none;
    }
    .upload-progress.active {
        display: block;
    }
</style>
@endpush

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header" style="background: linear-gradient(135deg, #3ea572 0%, #2d8a5a 100%); color: white;">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h4 class="mb-0 fw-bold">
                                <i class="ri-upload-cloud-2-line me-2"></i>Upload Gallery Images
                            </h4>
                            <p class="mb-0 opacity-90 mt-1">Upload single or multiple images at once</p>
                        </div>
                        <a href="{{ route('admin.homepage.gallery') }}" class="btn btn-light">
                            <i class="ri-arrow-left-line me-1"></i>Back
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.homepage.gallery.store') }}" method="POST" enctype="multipart/form-data" id="galleryForm">
                        @csrf

                        <!-- Bulk Image Upload Section -->
                        <div class="mb-4">
                            <label class="form-label fw-bold mb-3">Select Images <span class="text-danger">*</span></label>
                            <div class="upload-zone" id="uploadZone" onclick="document.getElementById('imagesInput').click()">
                                <i class="ri-upload-cloud-2-line" style="font-size: 3rem; color: #3ea572;"></i>
                                <h5 class="mt-3 mb-2">Click or Drag & Drop Images</h5>
                                <p class="text-muted mb-1">JPG, PNG, WebP, SVG (Max 5MB each)</p>
                                <p class="text-muted small">You can select multiple images at once</p>
                            </div>
                            <input type="file" name="images[]" id="imagesInput" class="d-none" accept="image/jpeg,image/jpg,image/png,image/webp,image/svg+xml" multiple onchange="handleFiles(this.files)">
                            @error('images')
                                <div class="text-danger small mt-2">{{ $message }}</div>
                            @enderror
                            @error('images.*')
                                <div class="text-danger small mt-2">{{ $message }}</div>
                            @enderror
                            
                            <!-- Image Previews -->
                            <div id="imagePreviews" class="image-preview-grid"></div>
                            
                            <!-- Upload Progress -->
                            <div class="upload-progress" id="uploadProgress">
                                <div class="alert alert-info">
                                    <i class="ri-loader-4-line ri-spin me-2"></i>
                                    <span id="progressText">Preparing upload...</span>
                                </div>
                            </div>
                        </div>

                        <!-- Simple Settings -->
                        <div class="row g-3 mb-4">
                            <div class="col-md-6">
                                <label class="form-label">Category</label>
                                <select name="category" class="form-select">
                                    <option value="">Select Category (Optional)</option>
                                    <option value="Homepage Slider" {{ old('category') == 'Homepage Slider' ? 'selected' : '' }}>Homepage Slider</option>
                                    <option value="Homepage Gallery" {{ old('category') == 'Homepage Gallery' ? 'selected' : '' }}>Homepage Gallery</option>
                                    <option value="Destination Images" {{ old('category') == 'Destination Images' ? 'selected' : '' }}>Destination Images</option>
                                    <option value="Blog Images" {{ old('category') == 'Blog Images' ? 'selected' : '' }}>Blog Images</option>
                                    <option value="System Icons / UI Images" {{ old('category') == 'System Icons / UI Images' ? 'selected' : '' }}>System Icons / UI Images</option>
                                    <option value="Team Photos" {{ old('category') == 'Team Photos' ? 'selected' : '' }}>Team Photos</option>
                                    <option value="Testimonials Images" {{ old('category') == 'Testimonials Images' ? 'selected' : '' }}>Testimonials Images</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Status</label>
                                <div class="form-check form-switch mt-2">
                                    <input class="form-check-input" type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="is_active">
                                        Active (Images will be visible)
                                    </label>
                                </div>
                            </div>
                        </div>

                        <!-- Form Actions -->
                        <div class="d-flex justify-content-between align-items-center mt-4 pt-3 border-top">
                            <a href="{{ route('admin.homepage.gallery') }}" class="btn btn-label-secondary">
                                <i class="ri-close-line me-1"></i>Cancel
                            </a>
                            <button type="submit" class="btn btn-primary" id="submitBtn">
                                <i class="ri-upload-cloud-2-line me-1"></i>Upload Images
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
let selectedFiles = [];

// Drag & Drop
const uploadZone = document.getElementById('uploadZone');
const imagesInput = document.getElementById('imagesInput');

uploadZone.addEventListener('dragover', (e) => {
    e.preventDefault();
    uploadZone.classList.add('dragover');
});

uploadZone.addEventListener('dragleave', () => {
    uploadZone.classList.remove('dragover');
});

uploadZone.addEventListener('drop', (e) => {
    e.preventDefault();
    uploadZone.classList.remove('dragover');
    const files = Array.from(e.dataTransfer.files).filter(file => file.type.startsWith('image/'));
    if (files.length > 0) {
        handleFiles(files);
    }
});

function handleFiles(files) {
    const fileArray = Array.from(files);
    
    // Validate file sizes and types
    const validFiles = fileArray.filter(file => {
        if (!file.type.startsWith('image/')) {
            alert(`${file.name} is not an image file. Skipping...`);
            return false;
        }
        if (file.size > 5 * 1024 * 1024) { // 5MB
            alert(`${file.name} is larger than 5MB. Skipping...`);
            return false;
        }
        return true;
    });
    
    // Add to selected files
    validFiles.forEach(file => {
        if (!selectedFiles.find(f => f.name === file.name && f.size === file.size)) {
            selectedFiles.push(file);
        }
    });
    
    // Update file input
    updateFileInput();
    
    // Update previews
    updatePreviews();
}

function removeFile(index) {
    selectedFiles.splice(index, 1);
    updateFileInput();
    updatePreviews();
}

function updateFileInput() {
    const dt = new DataTransfer();
    selectedFiles.forEach(file => dt.items.add(file));
    imagesInput.files = dt.files;
}

function updatePreviews() {
    const previewsContainer = document.getElementById('imagePreviews');
    previewsContainer.innerHTML = '';
    
    if (selectedFiles.length === 0) {
        return;
    }
    
    selectedFiles.forEach((file, index) => {
        const reader = new FileReader();
        reader.onload = function(e) {
            const previewItem = document.createElement('div');
            previewItem.className = 'preview-item';
            previewItem.innerHTML = `
                <button type="button" class="remove-btn" onclick="removeFile(${index})" title="Remove">
                    <i class="ri-close-line"></i>
                </button>
                <img src="${e.target.result}" alt="${file.name}">
                <div class="file-name" title="${file.name}">${file.name}</div>
            `;
            previewsContainer.appendChild(previewItem);
        };
        reader.readAsDataURL(file);
    });
    
    // Update submit button text
    const submitBtn = document.getElementById('submitBtn');
    const count = selectedFiles.length;
    submitBtn.innerHTML = `<i class="ri-upload-cloud-2-line me-1"></i>Upload ${count} Image${count !== 1 ? 's' : ''}`;
}

// Form submission
document.getElementById('galleryForm').addEventListener('submit', function(e) {
    if (selectedFiles.length === 0) {
        e.preventDefault();
        alert('Please select at least one image to upload.');
        return false;
    }
    
    // Show progress
    document.getElementById('uploadProgress').classList.add('active');
    document.getElementById('progressText').textContent = `Uploading ${selectedFiles.length} image${selectedFiles.length !== 1 ? 's' : ''}...`;
    document.getElementById('submitBtn').disabled = true;
});
</script>
@endpush
@endsection
