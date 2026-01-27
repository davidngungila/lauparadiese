@extends('admin.layouts.app')

@section('title', 'Partner Hotels Portal - Lau Paradise Adventures')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">
                        <i class="ri-hotel-line me-2"></i>Partner Hotels Portal
                    </h4>
                    <a href="{{ route('admin.hotels.index') }}" class="btn btn-outline-primary">
                        <i class="ri-arrow-left-line me-1"></i>All Hotels
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('admin.hotels.partner-portal') }}">
                <div class="row g-3">
                    <div class="col-md-10">
                        <label class="form-label">Search</label>
                        <input type="text" name="search" class="form-control" placeholder="Search partner hotels..." value="{{ request('search') }}">
                    </div>
                    <div class="col-md-2 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="ri-search-line me-1"></i>Search
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Partner Hotels Grid -->
    <div class="row g-4">
        @forelse($hotels as $hotel)
        <div class="col-md-6 col-lg-4">
            <div class="card h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div>
                            <h5 class="card-title mb-1">{{ $hotel->name }}</h5>
                            <p class="text-muted small mb-0">{{ $hotel->city ?? ($hotel->address ?? 'N/A') }}</p>
                        </div>
                        <span class="badge bg-label-primary">Partner</span>
                    </div>
                    <div class="mb-3">
                        @for($i = 0; $i < ($hotel->star_rating ?? 0); $i++)
                            <i class="ri-star-fill text-warning"></i>
                        @endfor
                    </div>
                    <p class="card-text text-muted small">{{ \Illuminate\Support\Str::limit($hotel->description ?? 'No description available', 100) }}</p>
                    <div class="d-flex gap-2 mt-3">
                        <button class="btn btn-sm btn-outline-primary flex-fill" title="View Details">
                            <i class="ri-eye-line me-1"></i>View
                        </button>
                        <button class="btn btn-sm btn-outline-warning flex-fill" title="Manage">
                            <i class="ri-settings-3-line me-1"></i>Manage
                        </button>
                    </div>
                </div>
            </div>
        </div>
        @empty
        <div class="col-12">
            <div class="card">
                <div class="card-body text-center py-5">
                    <i class="ri-hotel-line" style="font-size: 48px; color: #ccc;"></i>
                    <p class="text-muted mt-3">No partner hotels found</p>
                </div>
            </div>
        </div>
        @endforelse
    </div>

    <div class="mt-4">
        {{ $hotels->links() }}
    </div>
</div>
@endsection

