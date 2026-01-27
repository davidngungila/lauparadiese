@extends('admin.layouts.app')

@section('title', 'Homepage Destinations - Lau Paradise Adventures')
@section('description', 'Manage homepage destinations')

@php
use Illuminate\Support\Facades\Storage;
@endphp

@push('styles')
<style>
    .destination-image {
        width: 80px;
        height: 60px;
        object-fit: cover;
        border-radius: 4px;
    }
    .rating-stars {
        color: #ffc107;
    }
</style>
@endpush

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="mb-0"><i class="ri-map-pin-line me-2"></i>Homepage Destinations</h4>
                    <a href="{{ route('admin.homepage.destinations.create') }}" class="btn btn-primary">
                        <i class="ri-add-line me-1"></i>Add Destination
                    </a>
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
                                <i class="ri-map-pin-line"></i>
                            </span>
                        </div>
                        <div>
                            <h5 class="mb-0">{{ $stats['total'] ?? 0 }}</h5>
                            <small class="text-muted">Total Destinations</small>
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
                            <h5 class="mb-0">{{ $stats['active'] ?? 0 }}</h5>
                            <small class="text-muted">Active Destinations</small>
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
                                <i class="ri-star-line"></i>
                            </span>
                        </div>
                        <div>
                            <h5 class="mb-0">{{ $stats['featured'] ?? 0 }}</h5>
                            <small class="text-muted">Featured Destinations</small>
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
                                <i class="ri-folder-line"></i>
                            </span>
                        </div>
                        <div>
                            <h5 class="mb-0">{{ count($categories ?? []) }}</h5>
                            <small class="text-muted">Categories</small>
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
                                <i class="ri-landscape-line"></i>
                            </span>
                        </div>
                        <div>
                            <h5 class="mb-0">{{ $stats['national_parks'] ?? 0 }}</h5>
                            <small class="text-muted">National Parks</small>
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
                                <i class="ri-mountain-line"></i>
                            </span>
                        </div>
                        <div>
                            <h5 class="mb-0">{{ $stats['mountain_trekking'] ?? 0 }}</h5>
                            <small class="text-muted">Mountain Trekking</small>
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
                                <i class="ri-water-percent-line"></i>
                            </span>
                        </div>
                        <div>
                            <h5 class="mb-0">{{ $stats['beaches'] ?? 0 }}</h5>
                            <small class="text-muted">Beaches</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 mb-4">
            <div class="card card-border-shadow-secondary">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="avatar me-3">
                            <span class="avatar-initial rounded bg-label-secondary">
                                <i class="ri-star-line"></i>
                            </span>
                        </div>
                        <div>
                            <h5 class="mb-0">{{ $stats['with_rating'] ?? 0 }}</h5>
                            <small class="text-muted">With Ratings</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('admin.homepage.destinations') }}" class="row g-3">
                <div class="col-md-3">
                    <label class="form-label">Search</label>
                    <input type="text" name="search" class="form-control" placeholder="Search destinations..." value="{{ request('search') }}">
                </div>
                <div class="col-md-2">
                    <label class="form-label">Status</label>
                    <select name="status" class="form-select">
                        <option value="">All</option>
                        <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                        <option value="hidden" {{ request('status') == 'hidden' ? 'selected' : '' }}>Hidden</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label">Featured</label>
                    <select name="featured" class="form-select">
                        <option value="">All</option>
                        <option value="1" {{ request('featured') == '1' ? 'selected' : '' }}>Featured</option>
                        <option value="0" {{ request('featured') == '0' ? 'selected' : '' }}>Not Featured</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label">Category</label>
                    <select name="category" class="form-select">
                        <option value="">All Categories</option>
                        @foreach($categories ?? [] as $category)
                        <option value="{{ $category }}" {{ request('category') == $category ? 'selected' : '' }}>{{ $category }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3 d-flex align-items-end gap-2">
                    <button type="submit" class="btn btn-primary">
                        <i class="ri-search-line me-1"></i>Filter
                    </button>
                    <a href="{{ route('admin.homepage.destinations') }}" class="btn btn-outline-secondary">
                        <i class="ri-refresh-line me-1"></i>Reset
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Destinations Table -->
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th style="width: 100px;">Image</th>
                            <th>Name & Description</th>
                            <th>Location</th>
                            <th>Category</th>
                            <th>Price & Duration</th>
                            <th>Rating</th>
                            <th>Status & Featured</th>
                            <th>Display Order</th>
                            <th style="width: 120px;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($destinations as $destination)
                        <tr>
                            <td>
                                @if($destination->featured_image_display_url)
                                <img src="{{ $destination->featured_image_display_url }}" alt="{{ $destination->name }}" class="destination-image" style="cursor: pointer;" onclick="window.open('{{ $destination->featured_image_display_url }}', '_blank')">
                                @elseif($destination->featured_image_url)
                                <img src="{{ asset($destination->featured_image_url) }}" alt="{{ $destination->name }}" class="destination-image" style="cursor: pointer;" onclick="window.open('{{ asset($destination->featured_image_url) }}', '_blank')">
                                @else
                                <div class="destination-image bg-light d-flex align-items-center justify-content-center">
                                    <i class="ri-image-line text-muted"></i>
                                </div>
                                @endif
                            </td>
                            <td>
                                <div class="d-flex flex-column">
                                    <strong>{{ $destination->name }}</strong>
                                    @if($destination->short_description)
                                    <small class="text-muted mt-1">{{ Str::limit($destination->short_description, 80) }}</small>
                                    @endif
                                    @if($destination->slug)
                                    <small class="text-muted mt-1">
                                        <i class="ri-link me-1"></i>{{ $destination->slug }}
                                    </small>
                                    @endif
                                    @if($destination->image_gallery && is_array($destination->image_gallery) && count($destination->image_gallery) > 0)
                                    <small class="text-muted mt-1">
                                        <i class="ri-image-line me-1"></i>{{ count($destination->image_gallery) }} gallery images
                                    </small>
                                    @endif
                                </div>
                            </td>
                            <td>
                                @if($destination->location)
                                <i class="ri-map-pin-line me-1"></i>{{ $destination->location }}
                                @else
                                <span class="text-muted">N/A</span>
                                @endif
                            </td>
                            <td>
                                @if($destination->category)
                                <span class="badge bg-label-info">{{ $destination->category }}</span>
                                @else
                                <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td>
                                <div class="d-flex flex-column">
                                    @if($destination->price_display)
                                    <strong class="text-primary">{{ $destination->price_display }}</strong>
                                    @elseif($destination->price)
                                    <strong class="text-primary">{{ config('app.currency', '$') }}{{ number_format($destination->price, 2) }}</strong>
                                    @else
                                    <span class="text-muted">Contact for price</span>
                                    @endif
                                    @if($destination->duration)
                                    <small class="text-muted mt-1">
                                        <i class="ri-time-line me-1"></i>{{ $destination->duration }}
                                    </small>
                                    @endif
                                </div>
                            </td>
                            <td>
                                @if($destination->rating)
                                <div class="rating-stars">
                                    @for($i = 1; $i <= 5; $i++)
                                    <i class="ri-star{{ $i <= round($destination->rating) ? '-fill' : '-line' }}"></i>
                                    @endfor
                                </div>
                                <small class="text-muted">{{ number_format($destination->rating, 1) }}/5</small>
                                @else
                                <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td>
                                <div class="d-flex flex-column gap-1">
                                    <div>
                                        @if($destination->is_active)
                                        <span class="badge bg-label-success">Active</span>
                                        @else
                                        <span class="badge bg-label-secondary">Hidden</span>
                                        @endif
                                        @if($destination->is_featured)
                                        <span class="badge bg-label-warning ms-1">
                                            <i class="ri-star-fill me-1"></i>Featured
                                        </span>
                                        @endif
                                    </div>
                                    @if($destination->meta_title || $destination->meta_description)
                                    <small class="text-muted">
                                        <i class="ri-seo-line me-1"></i>SEO Configured
                                    </small>
                                    @endif
                                </div>
                            </td>
                            <td>
                                <span class="badge bg-label-secondary">{{ $destination->display_order ?? 0 }}</span>
                            </td>
                            <td>
                                <div class="d-flex gap-2">
                                    <a href="{{ route('admin.homepage.destinations.show', $destination->id) }}" class="btn btn-sm btn-icon btn-outline-primary" title="View Details">
                                        <i class="ri-eye-line"></i>
                                    </a>
                                    <a href="{{ route('admin.homepage.destinations.edit', $destination->id) }}" class="btn btn-sm btn-icon btn-outline-info" title="Edit">
                                        <i class="ri-pencil-line"></i>
                                    </a>
                                    <form action="{{ route('admin.homepage.destinations.destroy', $destination->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this destination?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-icon btn-outline-danger" title="Delete">
                                            <i class="ri-delete-bin-line"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="9" class="text-center py-4">
                                <div class="text-muted">
                                    <i class="ri-inbox-line" style="font-size: 48px;"></i>
                                    <p class="mt-2 mb-0">No destinations found</p>
                                    <a href="{{ route('admin.homepage.destinations.create') }}" class="btn btn-primary btn-sm mt-2">
                                        <i class="ri-add-line me-1"></i>Add First Destination
                                    </a>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-4">
                {{ $destinations->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
