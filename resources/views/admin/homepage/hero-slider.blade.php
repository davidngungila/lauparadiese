@extends('admin.layouts.app')

@section('title', 'Hero Slider Management - Lau Paradise Adventures')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <div>
                        <h4 class="mb-0"><i class="ri-slideshow-line me-2"></i>Hero Slider Management</h4>
                        <small class="text-muted">Manage homepage hero slider slides</small>
                    </div>
                    <a href="{{ route('admin.homepage.hero-slider.create') }}" class="btn btn-primary">
                        <i class="ri-add-line me-1"></i>Add New Slide
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-lg-3 col-md-6 mb-4">
            <div class="card card-border-shadow-primary">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="avatar me-3">
                            <span class="avatar-initial rounded bg-label-primary">
                                <i class="ri-slideshow-line"></i>
                            </span>
                        </div>
                        <div>
                            <h5 class="mb-0">{{ $slides->count() }}</h5>
                            <small class="text-muted">Total Slides</small>
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
                            <h5 class="mb-0">{{ $slides->where('is_active', true)->count() }}</h5>
                            <small class="text-muted">Active Slides</small>
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
                                <i class="ri-eye-off-line"></i>
                            </span>
                        </div>
                        <div>
                            <h5 class="mb-0">{{ $slides->where('is_active', false)->count() }}</h5>
                            <small class="text-muted">Inactive Slides</small>
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
                                <i class="ri-star-line"></i>
                            </span>
                        </div>
                        <div>
                            <h5 class="mb-0">{{ $slides->whereNotNull('badge_text')->count() }}</h5>
                            <small class="text-muted">With Badges</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Slides List with Drag & Drop -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">All Hero Slides</h5>
                    <div>
                        <button type="button" class="btn btn-sm btn-outline-primary" id="saveOrderBtn" style="display: none;">
                            <i class="ri-save-line me-1"></i>Save Order
                        </button>
                        <a href="{{ route('home') }}" target="_blank" class="btn btn-sm btn-outline-info">
                            <i class="ri-external-link-line me-1"></i>Preview Homepage
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    @if($slides->count() > 0)
                    <div class="alert alert-info mb-4">
                        <i class="ri-information-line me-2"></i>
                        <strong>Tip:</strong> Drag and drop slides to reorder them. The order determines how they appear on the homepage.
                    </div>
                    <div class="table-responsive">
                        <table class="table table-hover" id="slidesTable">
                            <thead>
                                <tr>
                                    <th style="width: 50px;" class="text-center">
                                        <i class="ri-drag-move-2-line"></i>
                                    </th>
                                    <th style="width: 60px;">Order</th>
                                    <th style="width: 180px;">Preview</th>
                                    <th>Content</th>
                                    <th>Badge & Buttons</th>
                                    <th>Settings</th>
                                    <th style="width: 100px;">Status</th>
                                    <th style="width: 180px;">Actions</th>
                                </tr>
                            </thead>
                            <tbody id="slidesTableBody" class="sortable">
                                @foreach($slides as $slide)
                                <tr data-slide-id="{{ $slide->id }}" data-order="{{ $slide->display_order }}" style="cursor: move;">
                                    <td class="text-center drag-handle">
                                        <i class="ri-drag-move-2-line text-muted" style="font-size: 1.5rem;"></i>
                                    </td>
                                    <td>
                                        <span class="badge bg-label-secondary order-badge">{{ $slide->display_order }}</span>
                                    </td>
                                    <td>
                                        @php
                                            // Get image URL with proper handling
                                            if ($slide->image_id && $slide->image) {
                                                $imageUrl = $slide->image->display_url;
                                            } elseif ($slide->getAttributes()['image_url'] ?? null) {
                                                $rawUrl = $slide->getAttributes()['image_url'];
                                                if (str_starts_with($rawUrl, 'http://') || str_starts_with($rawUrl, 'https://')) {
                                                    $imageUrl = $rawUrl;
                                                } elseif (str_starts_with($rawUrl, 'images/')) {
                                                    $imageUrl = asset($rawUrl);
                                                } else {
                                                    // Assume it's in images/hero-slider/
                                                    $imageUrl = asset('images/hero-slider/' . $rawUrl);
                                                }
                                            } else {
                                                $imageUrl = asset('images/safari_home-1.jpg');
                                            }
                                        @endphp
                                        <div class="position-relative">
                                            <img src="{{ $imageUrl }}" alt="{{ $slide->title }}" 
                                                 class="img-thumbnail rounded" 
                                                 style="width: 150px; height: 100px; object-fit: cover; cursor: pointer;"
                                                 onerror="this.onerror=null; this.src='{{ asset('images/safari_home-1.jpg') }}'"
                                                 onclick="previewSlide({{ $slide->id }})"
                                                 title="Click to preview">
                                            <button type="button" class="btn btn-xs btn-icon btn-primary position-absolute top-0 end-0 m-1" 
                                                    onclick="previewSlide({{ $slide->id }})" 
                                                    style="opacity: 0.8;"
                                                    title="Preview slide">
                                                <i class="ri-eye-line"></i>
                                            </button>
                                        </div>
                                    </td>
                                    <td>
                                        <div>
                                            <strong class="d-block mb-1">{{ $slide->title }}</strong>
                                            @if($slide->subtitle)
                                            <small class="text-muted d-block mb-2">{{ Str::limit($slide->subtitle, 80) }}</small>
                                            @endif
                                            <div class="d-flex gap-2 flex-wrap">
                                                <small class="text-muted">
                                                    <i class="ri-magic-line me-1"></i>{{ ucfirst(str_replace('-', ' ', $slide->animation_type ?? 'fade-in-up')) }}
                                                </small>
                                                <small class="text-muted">
                                                    <i class="ri-palette-line me-1"></i>{{ ucfirst($slide->overlay_type ?? 'gradient') }}
                                                </small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="d-flex flex-column gap-2">
                                            @if($slide->badge_text)
                                            <span class="badge bg-label-info w-fit">
                                                @if($slide->badge_icon)
                                                <i class="{{ $slide->badge_icon }} me-1"></i>
                                                @endif
                                                {{ $slide->badge_text }}
                                            </span>
                                            @else
                                            <span class="text-muted small">No badge</span>
                                            @endif
                                            <div class="d-flex flex-column gap-1">
                                                @if($slide->primary_button_text)
                                                <span class="badge bg-label-primary w-fit">
                                                    @if($slide->primary_button_icon)
                                                    <i class="{{ $slide->primary_button_icon }} me-1"></i>
                                                    @endif
                                                    {{ $slide->primary_button_text }}
                                                </span>
                                                @endif
                                                @if($slide->secondary_button_text)
                                                <span class="badge bg-label-secondary w-fit">
                                                    @if($slide->secondary_button_icon)
                                                    <i class="{{ $slide->secondary_button_icon }} me-1"></i>
                                                    @endif
                                                    {{ $slide->secondary_button_text }}
                                                </span>
                                                @endif
                                                @if(!$slide->primary_button_text && !$slide->secondary_button_text)
                                                <span class="text-muted small">No buttons</span>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="d-flex flex-column gap-1">
                                            <small class="text-muted">
                                                <i class="ri-image-line me-1"></i>
                                                {{ $slide->image_id ? 'Gallery' : ($slide->image_url ? 'Direct URL' : 'None') }}
                                            </small>
                                            <small class="text-muted">
                                                <i class="ri-calendar-line me-1"></i>
                                                {{ $slide->created_at->format('M d, Y') }}
                                            </small>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-check form-switch">
                                            <input class="form-check-input toggle-slide-status" 
                                                   type="checkbox" 
                                                   data-slide-id="{{ $slide->id }}"
                                                   {{ $slide->is_active ? 'checked' : '' }}
                                                   title="Toggle active status">
                                        </div>
                                    </td>
                                    <td>
                                        <div class="d-flex gap-2">
                                            <button type="button" class="btn btn-sm btn-icon btn-outline-info" 
                                                    onclick="previewSlide({{ $slide->id }})" 
                                                    title="Preview">
                                                <i class="ri-eye-line"></i>
                                            </button>
                                            <a href="{{ route('admin.homepage.hero-slider.edit', $slide->id) }}" 
                                               class="btn btn-sm btn-icon btn-outline-primary" 
                                               title="Edit">
                                                <i class="ri-edit-line"></i>
                                            </a>
                                            <button type="button" class="btn btn-sm btn-icon btn-outline-danger" 
                                                    onclick="deleteSlide({{ $slide->id }}, '{{ addslashes($slide->title) }}')" 
                                                    title="Delete">
                                                <i class="ri-delete-bin-line"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @else
                    <div class="text-center py-5">
                        <div class="mb-4">
                            <i class="ri-slideshow-line" style="font-size: 4rem; color: #ccc;"></i>
                        </div>
                        <h5 class="mb-2">No Hero Slides Found</h5>
                        <p class="text-muted mb-4">Create your first hero slide to display on the homepage</p>
                        <a href="{{ route('admin.homepage.hero-slider.create') }}" class="btn btn-primary">
                            <i class="ri-add-line me-1"></i>Add First Slide
                        </a>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Preview Modal -->
<div class="modal fade" id="previewModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Slide Preview</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-0" id="previewContent">
                <div class="text-center py-5">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Delete Hero Slide</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete the slide <strong id="deleteSlideTitle"></strong>?</p>
                <p class="text-danger mb-0"><small>This action cannot be undone.</small></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">Cancel</button>
                <form id="deleteSlideForm" method="POST" action="" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">
                        <i class="ri-delete-bin-line me-1"></i>Delete Slide
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

@push('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sortablejs@latest/Sortable.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
<style>
    .sortable tr {
        cursor: move;
    }
    .sortable tr:hover {
        background-color: #f8f9fa;
    }
    .sortable .drag-handle {
        cursor: grab;
    }
    .sortable .drag-handle:active {
        cursor: grabbing;
    }
    .sortable-ghost {
        opacity: 0.4;
        background-color: #e9ecef;
    }
    .w-fit {
        width: fit-content;
    }
    #previewContent .hero-slide-preview {
        min-height: 500px;
        background-size: cover;
        background-position: center;
        position: relative;
        display: flex;
        align-items: center;
        justify-content: center;
        overflow: hidden;
    }
    #previewContent .hero-slide-preview img {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        object-fit: cover;
        z-index: 0;
    }
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sortablejs@latest/Sortable.min.js"></script>
<script>
let sortableInstance;
let orderChanged = false;

document.addEventListener('DOMContentLoaded', function() {
    const tbody = document.getElementById('slidesTableBody');
    if (tbody) {
        sortableInstance = Sortable.create(tbody, {
            handle: '.drag-handle',
            animation: 150,
            ghostClass: 'sortable-ghost',
            onEnd: function(evt) {
                orderChanged = true;
                updateOrderNumbers();
                document.getElementById('saveOrderBtn').style.display = 'inline-block';
            }
        });
    }

    // Toggle slide status
    document.querySelectorAll('.toggle-slide-status').forEach(toggle => {
        toggle.addEventListener('change', function() {
            const slideId = this.dataset.slideId;
            const isActive = this.checked;
            
            fetch(`{{ url('admin/homepage/hero-slider') }}/${slideId}/toggle-status`, {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify({
                    is_active: isActive,
                    _method: 'PUT'
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Show success message
                    const alert = document.createElement('div');
                    alert.className = 'alert alert-success alert-dismissible fade show position-fixed top-0 start-50 translate-middle-x mt-3';
                    alert.style.zIndex = '9999';
                    alert.innerHTML = `
                        <i class="ri-checkbox-circle-line me-2"></i>Slide status updated successfully!
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    `;
                    document.body.appendChild(alert);
                    setTimeout(() => alert.remove(), 3000);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                this.checked = !isActive; // Revert toggle
                alert('Failed to update slide status. Please try again.');
            });
        });
    });
});

function updateOrderNumbers() {
    const rows = document.querySelectorAll('#slidesTableBody tr');
    rows.forEach((row, index) => {
        const orderBadge = row.querySelector('.order-badge');
        if (orderBadge) {
            orderBadge.textContent = index + 1;
        }
        row.dataset.order = index + 1;
    });
}

document.getElementById('saveOrderBtn')?.addEventListener('click', function() {
    const rows = document.querySelectorAll('#slidesTableBody tr');
    const slides = Array.from(rows).map((row, index) => ({
        id: row.dataset.slideId,
        order: index + 1
    }));

    const btn = this;
    const originalText = btn.innerHTML;
    btn.disabled = true;
    btn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span>Saving...';

    fetch('{{ route("admin.homepage.hero-slider.update-order") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: JSON.stringify({ slides })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            orderChanged = false;
            btn.style.display = 'none';
            
            // Show success message
            const alert = document.createElement('div');
            alert.className = 'alert alert-success alert-dismissible fade show position-fixed top-0 start-50 translate-middle-x mt-3';
            alert.style.zIndex = '9999';
            alert.innerHTML = `
                <i class="ri-checkbox-circle-line me-2"></i>${data.message}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            `;
            document.body.appendChild(alert);
            setTimeout(() => alert.remove(), 3000);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Failed to save order. Please try again.');
    })
    .finally(() => {
        btn.disabled = false;
        btn.innerHTML = originalText;
    });
});

function previewSlide(slideId) {
    fetch(`{{ url('admin/homepage/hero-slider') }}/${slideId}`, {
        headers: {
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.slide) {
            const slide = data.slide;
            
            // Get image URL with proper handling
            let imageUrl = '';
            if (slide.image && slide.image.display_url) {
                imageUrl = slide.image.display_url;
            } else if (slide.image_url) {
                const rawUrl = slide.image_url;
                if (rawUrl.startsWith('http://') || rawUrl.startsWith('https://')) {
                    imageUrl = rawUrl;
                } else if (rawUrl.startsWith('images/')) {
                    imageUrl = '{{ asset("") }}' + rawUrl;
                } else {
                    // Assume it's in images/hero-slider/
                    imageUrl = '{{ asset("images/hero-slider/") }}/' + rawUrl;
                }
            } else {
                imageUrl = '{{ asset("images/safari_home-1.jpg") }}';
            }
            
            // Get animation class
            const animationClass = getAnimationClass(slide.animation_type || 'fade-in-up');
            
            document.getElementById('previewContent').innerHTML = `
                <div class="hero-slide-preview position-relative" style="background-image: url('${imageUrl}'); background-size: cover; background-position: center; min-height: 500px;">
                    <div class="overlay-${slide.overlay_type || 'gradient'}" style="position: absolute; top: 0; left: 0; right: 0; bottom: 0; background: ${getOverlayStyle(slide.overlay_type)};"></div>
                    <div class="container position-relative" style="z-index: 2; color: white; text-align: center; padding: 4rem 2rem; display: flex; flex-direction: column; justify-content: center; min-height: 500px;">
                        <div class="${animationClass}">
                            ${slide.badge_text ? `
                                <span class="badge bg-primary mb-3" style="font-size: 0.875rem; padding: 0.5rem 1rem; display: inline-block;">
                                    ${slide.badge_icon ? `<i class="${slide.badge_icon} me-1"></i>` : ''}
                                    ${slide.badge_text}
                                </span>
                            ` : ''}
                            <h1 class="display-4 fw-bold mb-3">${escapeHtml(slide.title)}</h1>
                            ${slide.subtitle ? `<p class="lead mb-4">${escapeHtml(slide.subtitle)}</p>` : ''}
                            <div class="d-flex gap-3 justify-content-center flex-wrap">
                                ${slide.primary_button_text ? `
                                    <a href="${slide.primary_button_link || '#'}" class="btn btn-primary btn-lg">
                                        ${slide.primary_button_icon ? `<i class="${slide.primary_button_icon} me-2"></i>` : ''}
                                        ${escapeHtml(slide.primary_button_text)}
                                    </a>
                                ` : ''}
                                ${slide.secondary_button_text ? `
                                    <a href="${slide.secondary_button_link || '#'}" class="btn btn-outline-light btn-lg">
                                        ${slide.secondary_button_icon ? `<i class="${slide.secondary_button_icon} me-2"></i>` : ''}
                                        ${escapeHtml(slide.secondary_button_text)}
                                    </a>
                                ` : ''}
                            </div>
                        </div>
                    </div>
                </div>
            `;
            new bootstrap.Modal(document.getElementById('previewModal')).show();
        }
    })
    .catch(error => {
        console.error('Error:', error);
        document.getElementById('previewContent').innerHTML = `
            <div class="alert alert-danger m-4">
                Failed to load slide preview. Please try again.
            </div>
        `;
        new bootstrap.Modal(document.getElementById('previewModal')).show();
    });
}

function escapeHtml(text) {
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
}

function getAnimationClass(type) {
    const animations = {
        'fade-in-up': 'animate__animated animate__fadeInUp',
        'slide-left': 'animate__animated animate__slideInLeft',
        'slide-right': 'animate__animated animate__slideInRight',
        'zoom-in': 'animate__animated animate__zoomIn'
    };
    return animations[type] || animations['fade-in-up'];
}

function getOverlayStyle(type) {
    switch(type) {
        case 'gradient':
            return 'linear-gradient(135deg, rgba(0,0,0,0.5) 0%, rgba(0,0,0,0.3) 100%)';
        case 'dark':
            return 'rgba(0,0,0,0.6)';
        case 'light':
            return 'rgba(255,255,255,0.3)';
        default:
            return 'transparent';
    }
}

function deleteSlide(slideId, slideTitle) {
    document.getElementById('deleteSlideTitle').textContent = slideTitle;
    // Construct the delete URL - route expects DELETE method to /admin/homepage/hero-slider/{id}
    const deleteUrl = '{{ url("admin/homepage/hero-slider") }}/' + slideId;
    const form = document.getElementById('deleteSlideForm');
    if (form) {
        form.action = deleteUrl;
        // Ensure the form has the correct method
        form.method = 'POST';
        // Make sure the _method field exists
        let methodField = form.querySelector('input[name="_method"]');
        if (!methodField) {
            methodField = document.createElement('input');
            methodField.type = 'hidden';
            methodField.name = '_method';
            methodField.value = 'DELETE';
            form.appendChild(methodField);
        } else {
            methodField.value = 'DELETE';
        }
    }
    new bootstrap.Modal(document.getElementById('deleteModal')).show();
}
</script>
@endpush
@endsection

