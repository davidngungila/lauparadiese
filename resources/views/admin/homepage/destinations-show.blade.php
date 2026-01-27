@extends('admin.layouts.app')

@section('title', 'Destination Details - ' . $destination->name)

@php
use Illuminate\Support\Facades\Storage;
@endphp

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">
                        <i class="ri-map-pin-line me-2"></i>{{ $destination->name }}
                    </h4>
                    <div class="d-flex gap-2">
                        <a href="{{ route('admin.homepage.destinations.edit', $destination->id) }}" class="btn btn-primary">
                            <i class="ri-edit-line me-1"></i>Edit
                        </a>
                        <a href="{{ route('admin.homepage.destinations') }}" class="btn btn-label-secondary">
                            <i class="ri-arrow-left-line me-1"></i>Back to List
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Main Content -->
        <div class="col-lg-8">
            <!-- Featured Image -->
            @php
                $imageService = new \App\Services\ImageService();
                $featuredImageUrl = $destination->featured_image_id && $destination->featuredImage 
                    ? $imageService->getUrl($destination->featuredImage->image_url)
                    : ($destination->featured_image_url ? $imageService->getUrl($destination->featured_image_url) : null);
            @endphp
            @if($featuredImageUrl)
            <div class="card mb-4">
                <div class="card-body p-0">
                    <img src="{{ $featuredImageUrl }}" alt="{{ $destination->name }}" class="img-fluid w-100" style="max-height: 400px; object-fit: cover;">
                </div>
            </div>
            @endif

            <!-- Basic Information -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0"><i class="ri-information-line me-2"></i>Basic Information</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Short Description</label>
                        <p class="mb-0">{{ $destination->short_description ?? 'N/A' }}</p>
                    </div>
                    @if($destination->full_description)
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Full Description</label>
                        <p class="mb-0">{!! nl2br(e($destination->full_description)) !!}</p>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Image Gallery -->
            @php
                $galleryImages = $destination->galleryImages();
            @endphp
            @if($galleryImages && $galleryImages->count() > 0)
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0"><i class="ri-image-line me-2"></i>Image Gallery</h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        @foreach($galleryImages as $galleryImage)
                        <div class="col-md-4">
                            <img src="{{ $imageService->getUrl($galleryImage->image_url) }}" alt="{{ $galleryImage->title }}" class="img-fluid rounded" style="height: 200px; width: 100%; object-fit: cover;">
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
            @endif
        </div>

        <!-- Sidebar -->
        <div class="col-lg-4">
            <!-- Status & Settings -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0"><i class="ri-settings-3-line me-2"></i>Status & Settings</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Status</label>
                        <div>
                            @if($destination->is_active)
                            <span class="badge bg-label-success">Active</span>
                            @else
                            <span class="badge bg-label-secondary">Hidden</span>
                            @endif
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Featured</label>
                        <div>
                            @if($destination->is_featured)
                            <span class="badge bg-label-warning">
                                <i class="ri-star-fill me-1"></i>Featured
                            </span>
                            @else
                            <span class="text-muted">Not Featured</span>
                            @endif
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Display Order</label>
                        <p class="mb-0">{{ $destination->display_order }}</p>
                    </div>
                    <div class="mb-0">
                        <label class="form-label fw-semibold">Slug / URL</label>
                        <p class="mb-0"><code>{{ $destination->slug ?? 'N/A' }}</code></p>
                    </div>
                </div>
            </div>

            <!-- Location -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0"><i class="ri-map-pin-line me-2"></i>Location</h5>
                </div>
                <div class="card-body">
                    @if($destination->location)
                    <p class="mb-0">{{ $destination->location }}</p>
                    @else
                    <p class="text-muted mb-0">No location specified</p>
                    @endif
                </div>
            </div>

            <!-- Category & Pricing -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0"><i class="ri-price-tag-3-line me-2"></i>Category & Pricing</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Category</label>
                        <div>
                            @if($destination->category)
                            <span class="badge bg-label-info">{{ $destination->category }}</span>
                            @else
                            <span class="text-muted">N/A</span>
                            @endif
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Price</label>
                        <p class="mb-0">
                            @if($destination->price_display)
                            {{ $destination->price_display }}
                            @elseif($destination->price)
                            {{ config('app.currency', '$') }}{{ number_format($destination->price, 2) }}
                            @else
                            <span class="text-muted">Contact for price</span>
                            @endif
                        </p>
                    </div>
                    @if($destination->duration)
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Duration</label>
                        <p class="mb-0">{{ $destination->duration }}</p>
                    </div>
                    @endif
                    @if($destination->rating)
                    <div class="mb-0">
                        <label class="form-label fw-semibold">Rating</label>
                        <div>
                            <div class="rating-stars mb-1">
                                @for($i = 1; $i <= 5; $i++)
                                <i class="ri-star{{ $i <= round($destination->rating) ? '-fill' : '-line' }}" style="color: #ffc107;"></i>
                                @endfor
                            </div>
                            <span class="text-muted">{{ number_format($destination->rating, 1) }}/5</span>
                        </div>
                    </div>
                    @endif
                </div>
            </div>

            <!-- SEO Details -->
            @if($destination->meta_title || $destination->meta_description || $destination->meta_keywords)
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0"><i class="ri-search-line me-2"></i>SEO Details</h5>
                </div>
                <div class="card-body">
                    @if($destination->meta_title)
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Meta Title</label>
                        <p class="mb-0">{{ $destination->meta_title }}</p>
                    </div>
                    @endif
                    @if($destination->meta_description)
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Meta Description</label>
                        <p class="mb-0">{{ $destination->meta_description }}</p>
                    </div>
                    @endif
                    @if($destination->meta_keywords)
                    <div class="mb-0">
                        <label class="form-label fw-semibold">Meta Keywords</label>
                        <p class="mb-0">{{ $destination->meta_keywords }}</p>
                    </div>
                    @endif
                </div>
            </div>
            @endif

            <!-- Timestamps -->
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="ri-time-line me-2"></i>Timestamps</h5>
                </div>
                <div class="card-body">
                    <div class="mb-2">
                        <small class="text-muted">Created:</small>
                        <p class="mb-0">{{ $destination->created_at->format('M d, Y h:i A') }}</p>
                    </div>
                    <div class="mb-0">
                        <small class="text-muted">Last Updated:</small>
                        <p class="mb-0">{{ $destination->updated_at->format('M d, Y h:i A') }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

