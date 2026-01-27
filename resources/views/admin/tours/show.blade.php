@extends('admin.layouts.app')

@section('title', 'Tour Details - ' . $tour->name)
@section('description', 'View comprehensive tour details')

@push('styles')
<style>
    .info-card {
        border: none;
        box-shadow: 0 2px 8px rgba(0,0,0,0.08);
        border-radius: 10px;
        margin-bottom: 1.5rem;
    }
    .info-card .card-header {
        background: linear-gradient(135deg, #3ea572 0%, #2d7a5f 100%);
        color: white;
        border-radius: 10px 10px 0 0;
        padding: 1rem 1.5rem;
        border: none;
    }
    .info-card .card-header h5 {
        margin: 0;
        font-weight: 600;
    }
    .tour-header-card {
        background: linear-gradient(135deg, #3ea572 0%, #2d7a5f 100%);
        color: white;
        border-radius: 10px;
        padding: 2rem;
        margin-bottom: 2rem;
    }
    .stat-card {
        background: white;
        padding: 1.5rem;
        border-radius: 10px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.08);
        border-left: 4px solid #3ea572;
        transition: transform 0.2s;
    }
    .stat-card:hover {
        transform: translateY(-5px);
    }
    .stat-card .stat-label {
        font-size: 0.875rem;
        color: #6c757d;
        margin-bottom: 0.5rem;
    }
    .stat-card .stat-value {
        font-size: 1.5rem;
        font-weight: 700;
        color: #2d7a5f;
    }
    .timeline-item {
        position: relative;
        padding-left: 2rem;
        padding-bottom: 1.5rem;
    }
    .timeline-item:before {
        content: '';
        position: absolute;
        left: 0;
        top: 0;
        bottom: -1.5rem;
        width: 2px;
        background: #e0e0e0;
    }
    .timeline-item:last-child:before {
        display: none;
    }
    .timeline-marker {
        position: absolute;
        left: -5px;
        top: 0;
        width: 12px;
        height: 12px;
        border-radius: 50%;
        background: #3ea572;
        border: 2px solid white;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }
    .gallery-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
        gap: 1rem;
    }
    .gallery-item {
        position: relative;
        aspect-ratio: 1;
        border-radius: 8px;
        overflow: hidden;
        cursor: pointer;
    }
    .gallery-item img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.3s;
    }
    .gallery-item:hover img {
        transform: scale(1.1);
    }
    .highlight-item, .inclusion-item, .exclusion-item {
        padding: 0.75rem;
        margin-bottom: 0.5rem;
        background: #f8f9fa;
        border-left: 3px solid #3ea572;
        border-radius: 4px;
    }
    .price-highlight {
        font-size: 1.5rem;
        font-weight: 700;
        color: #3ea572;
    }
</style>
@endpush

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <!-- Tour Header -->
    <div class="tour-header-card">
        <div class="row align-items-center">
            <div class="col-md-8">
                <div class="d-flex align-items-center mb-3">
                    @if($tour->image_url)
                        @php
                            $imageUrl = str_starts_with($tour->image_url, 'http://') || str_starts_with($tour->image_url, 'https://') 
                                ? $tour->image_url 
                                : asset($tour->image_url);
                        @endphp
                        <img src="{{ $imageUrl }}" alt="{{ $tour->name }}" 
                             class="rounded me-3" style="width: 100px; height: 100px; object-fit: cover; border: 3px solid white;" onerror="this.src='{{ asset('images/safari_home-1.jpg') }}'">
                    @else
                        <div class="avatar me-3" style="width: 100px; height: 100px;">
                            <span class="avatar-initial rounded bg-white" style="font-size: 3rem; display: flex; align-items: center; justify-content: center; color: #3ea572;">
                                {{ strtoupper(substr($tour->name, 0, 1)) }}
                            </span>
                        </div>
                    @endif
                    <div>
                        <h2 class="mb-1 text-white">{{ $tour->name }}</h2>
                        @if($tour->tour_code)
                            <p class="mb-1 text-white-50 small">
                                <code style="background: rgba(255,255,255,0.2); padding: 0.25rem 0.5rem; border-radius: 4px;">{{ $tour->tour_code }}</code>
                            </p>
                        @endif
                        @if($tour->destination)
                            <p class="mb-0 text-white-50">
                                <i class="ri-map-pin-line me-1"></i>{{ $tour->destination->name }}
                            </p>
                        @endif
                    </div>
                </div>
                <div class="d-flex gap-3 flex-wrap">
                    @if($tour->is_featured)
                        <span class="badge bg-warning">
                            <i class="ri-star-fill me-1"></i>Featured Tour
                        </span>
                    @endif
                    @if($tour->fitness_level)
                        <span class="badge bg-white text-dark">{{ ucfirst($tour->fitness_level) }}</span>
                    @endif
                    @if($tour->difficulty_level)
                        <span class="badge bg-white text-dark">{{ ucfirst($tour->difficulty_level) }}</span>
                    @endif
                    @if($tour->rating)
                        <span class="badge bg-white text-dark">
                            @for($i = 0; $i < 5; $i++)
                                <i class="ri-star-{{ $i < $tour->rating ? 'fill' : 'line' }}" style="color: #ffc107;"></i>
                            @endfor
                            {{ $tour->rating }}
                        </span>
                    @endif
                    <span class="badge bg-white text-dark">
                        <i class="ri-time-line me-1"></i>{{ $tour->duration_days }} Days
                        @if($tour->duration_nights)
                            / {{ $tour->duration_nights }} Nights
                        @endif
                    </span>
                    <span class="badge bg-white text-dark">
                        <i class="ri-money-dollar-circle-line me-1"></i>${{ number_format($tour->price, 2) }}
                    </span>
                    @if($tour->status)
                        <span class="badge bg-{{ $tour->status === 'Active' ? 'success' : 'secondary' }}">
                            {{ $tour->status }}
                        </span>
                    @endif
                </div>
            </div>
            <div class="col-md-4 text-end mt-3 mt-md-0">
                <div class="d-flex gap-2 justify-content-end flex-wrap">
                    <a href="{{ route('admin.tours.edit', $tour->id) }}" class="btn btn-light">
                        <i class="ri-edit-line me-1"></i>Edit Tour
                    </a>
                    <a href="{{ route('admin.tours.index') }}" class="btn btn-outline-light">
                        <i class="ri-arrow-left-line me-1"></i>Back
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Stats -->
    <div class="row mb-4">
        <div class="col-lg-3 col-md-6 mb-4">
            <div class="stat-card">
                <div class="stat-label">Total Bookings</div>
                <div class="stat-value">{{ $stats['total_bookings'] }}</div>
                <small class="text-success">
                    <i class="ri-arrow-up-line"></i> {{ $stats['confirmed_bookings'] }} confirmed
                </small>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 mb-4">
            <div class="stat-card">
                <div class="stat-label">Total Revenue</div>
                <div class="stat-value">${{ number_format($stats['total_revenue'], 2) }}</div>
                <small class="text-muted">
                    Avg: ${{ number_format($stats['average_booking_value'], 2) }}
                </small>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 mb-4">
            <div class="stat-card">
                <div class="stat-label">Average Rating</div>
                <div class="stat-value">{{ $stats['average_rating'] }}</div>
                <small class="text-muted">
                    {{ $stats['total_reviews'] }} reviews
                </small>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 mb-4">
            <div class="stat-card">
                <div class="stat-label">Pending Bookings</div>
                <div class="stat-value">{{ $stats['pending_bookings'] }}</div>
                <small class="text-danger">
                    {{ $stats['cancelled_bookings'] }} cancelled
                </small>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Main Content -->
        <div class="col-lg-8">
            <!-- Tour Information -->
            <div class="card info-card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="ri-information-line me-2"></i>Tour Information
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label text-muted small">Tour Code</label>
                            <p class="mb-0"><code>{{ $tour->tour_code ?? 'N/A' }}</code></p>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-muted small">Slug</label>
                            <p class="mb-0"><code>{{ $tour->slug }}</code></p>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-muted small">Destination</label>
                            <p class="mb-0">
                                @if($tour->destination)
                                    <span class="badge bg-label-info">{{ $tour->destination->name }}</span>
                                @else
                                    <span class="text-muted">Not set</span>
                                @endif
                            </p>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-muted small">Duration</label>
                            <p class="mb-0">
                                <strong>{{ $tour->duration_days }} days</strong>
                                @if($tour->duration_nights)
                                    / <strong>{{ $tour->duration_nights }} nights</strong>
                                @endif
                            </p>
                        </div>
                        @if($tour->start_location || $tour->end_location)
                        <div class="col-md-6">
                            <label class="form-label text-muted small">Start Location</label>
                            <p class="mb-0">{{ $tour->start_location ?? 'N/A' }}</p>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-muted small">End Location</label>
                            <p class="mb-0">{{ $tour->end_location ?? 'N/A' }}</p>
                        </div>
                        @endif
                        <div class="col-md-6">
                            <label class="form-label text-muted small">Base Price</label>
                            <p class="mb-0 price-highlight">${{ number_format($tour->price, 2) }} per person</p>
                            @if($tour->starting_price && $tour->starting_price != $tour->price)
                                <small class="text-muted">Starting from: ${{ number_format($tour->starting_price, 2) }}</small>
                            @endif
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-muted small">Tour Type</label>
                            <p class="mb-0">
                                @if($tour->tour_type)
                                    <span class="badge bg-label-primary">{{ $tour->tour_type }}</span>
                                @else
                                    <span class="text-muted">Not specified</span>
                                @endif
                            </p>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-muted small">Fitness Level</label>
                            <p class="mb-0">
                                @if($tour->fitness_level)
                                    <span class="badge bg-label-info">{{ ucfirst($tour->fitness_level) }}</span>
                                @else
                                    <span class="text-muted">Not specified</span>
                                @endif
                            </p>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-muted small">Difficulty Level</label>
                            <p class="mb-0">
                                @if($tour->difficulty_level)
                                    <span class="badge bg-label-info">{{ ucfirst($tour->difficulty_level) }}</span>
                                @else
                                    <span class="text-muted">Not specified</span>
                                @endif
                            </p>
                        </div>
                        @if($tour->max_group_size)
                        <div class="col-md-6">
                            <label class="form-label text-muted small">Max Group Size</label>
                            <p class="mb-0"><strong>{{ $tour->max_group_size }}</strong> people</p>
                        </div>
                        @endif
                        @if($tour->min_age)
                        <div class="col-md-6">
                            <label class="form-label text-muted small">Minimum Age</label>
                            <p class="mb-0"><strong>{{ $tour->min_age }}</strong> years</p>
                        </div>
                        @endif
                        <div class="col-md-6">
                            <label class="form-label text-muted small">Rating</label>
                            <p class="mb-0">
                                @if($tour->rating)
                                    @for($i = 0; $i < 5; $i++)
                                        <i class="ri-star-{{ $i < $tour->rating ? 'fill' : 'line' }} text-warning"></i>
                                    @endfor
                                    <span class="ms-1">({{ $tour->rating }})</span>
                                @else
                                    <span class="text-muted">No rating</span>
                                @endif
                            </p>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-muted small">Status</label>
                            <p class="mb-0">
                                @if($tour->status === 'Active')
                                    <span class="badge bg-success">Active</span>
                                @else
                                    <span class="badge bg-secondary">{{ $tour->status ?? 'Inactive' }}</span>
                                @endif
                                @if($tour->is_featured)
                                    <span class="badge bg-warning ms-1">Featured</span>
                                @endif
                            </p>
                        </div>
                        @if($tour->publish_status)
                        <div class="col-md-6">
                            <label class="form-label text-muted small">Publish Status</label>
                            <p class="mb-0">
                                <span class="badge bg-{{ $tour->publish_status === 'Published' ? 'success' : 'secondary' }}">
                                    {{ $tour->publish_status }}
                                </span>
                            </p>
                        </div>
                        @endif
                        @if($tour->availability_status)
                        <div class="col-md-6">
                            <label class="form-label text-muted small">Availability</label>
                            <p class="mb-0">
                                <span class="badge bg-{{ $tour->availability_status === 'Available' ? 'success' : 'warning' }}">
                                    {{ $tour->availability_status }}
                                </span>
                            </p>
                        </div>
                        @endif
                        @if($tour->categories->count() > 0)
                        <div class="col-md-12">
                            <label class="form-label text-muted small">Categories</label>
                            <p class="mb-0">
                                @foreach($tour->categories as $category)
                                    <span class="badge bg-label-primary me-1">{{ $category->name }}</span>
                                @endforeach
                            </p>
                        </div>
                        @endif
                        @if($tour->excerpt)
                        <div class="col-md-12">
                            <label class="form-label text-muted small">Excerpt</label>
                            <p class="mb-0">{{ $tour->excerpt }}</p>
                        </div>
                        @endif
                        @if($tour->short_description)
                        <div class="col-md-12">
                            <label class="form-label text-muted small">Short Description</label>
                            <p class="mb-0">{{ $tour->short_description }}</p>
                        </div>
                        @endif
                        <div class="col-md-12">
                            <label class="form-label text-muted small">Description</label>
                            <div class="text-muted" style="max-height: 300px; overflow-y: auto;">
                                {!! nl2br(e($tour->description ?? 'No description available.')) !!}
                            </div>
                        </div>
                        @if($tour->long_description)
                        <div class="col-md-12">
                            <label class="form-label text-muted small">Long Description</label>
                            <div class="text-muted" style="max-height: 300px; overflow-y: auto;">
                                {!! nl2br(e($tour->long_description)) !!}
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Highlights -->
            @if($tour->highlights && count($tour->highlights) > 0)
            <div class="card info-card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="ri-star-line me-2"></i>Tour Highlights
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        @foreach($tour->highlights as $highlight)
                        <div class="col-md-6 mb-2">
                            <div class="highlight-item">
                                <i class="ri-checkbox-circle-fill me-2" style="color: #3ea572;"></i>{{ $highlight }}
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
            @endif

            <!-- Inclusions & Exclusions -->
            <div class="row">
                @if($tour->inclusions && count($tour->inclusions) > 0)
                <div class="col-md-6">
                    <div class="card info-card">
                        <div class="card-header">
                            <h5 class="mb-0">
                                <i class="ri-checkbox-circle-line me-2"></i>Inclusions
                            </h5>
                        </div>
                        <div class="card-body">
                            @foreach($tour->inclusions as $inclusion)
                            <div class="inclusion-item">
                                <i class="ri-check-line me-2" style="color: #3ea572;"></i>{{ $inclusion }}
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                @endif
                @if($tour->exclusions && count($tour->exclusions) > 0)
                <div class="col-md-6">
                    <div class="card info-card">
                        <div class="card-header">
                            <h5 class="mb-0">
                                <i class="ri-close-circle-line me-2"></i>Exclusions
                            </h5>
                        </div>
                        <div class="card-body">
                            @foreach($tour->exclusions as $exclusion)
                            <div class="exclusion-item">
                                <i class="ri-close-line me-2" style="color: #dc3545;"></i>{{ $exclusion }}
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                @endif
            </div>

            <!-- Terms & Conditions -->
            @if($tour->terms_conditions || $tour->cancellation_policy || $tour->important_notes)
            <div class="card info-card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="ri-file-text-line me-2"></i>Terms & Policies
                    </h5>
                </div>
                <div class="card-body">
                    @if($tour->terms_conditions)
                    <div class="mb-4">
                        <h6 class="fw-semibold mb-2">Terms & Conditions</h6>
                        <div class="text-muted" style="max-height: 200px; overflow-y: auto;">
                            {!! nl2br(e($tour->terms_conditions)) !!}
                        </div>
                    </div>
                    @endif
                    @if($tour->cancellation_policy)
                    <div class="mb-4">
                        <h6 class="fw-semibold mb-2">Cancellation Policy</h6>
                        <div class="text-muted" style="max-height: 200px; overflow-y: auto;">
                            {!! nl2br(e($tour->cancellation_policy)) !!}
                        </div>
                    </div>
                    @endif
                    @if($tour->important_notes)
                    <div>
                        <h6 class="fw-semibold mb-2">Important Notes</h6>
                        <div class="text-muted" style="max-height: 200px; overflow-y: auto;">
                            {!! nl2br(e($tour->important_notes)) !!}
                        </div>
                    </div>
                    @endif
                </div>
            </div>
            @endif

            <!-- Gallery Images -->
            @if($tour->gallery_images && count($tour->gallery_images) > 0)
            <div class="card info-card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="ri-image-line me-2"></i>Gallery Images ({{ count($tour->gallery_images) }})
                    </h5>
                </div>
                <div class="card-body">
                    <div class="gallery-grid">
                        @foreach($tour->gallery_images as $image)
                            @php
                                $imageUrl = str_starts_with($image, 'http://') || str_starts_with($image, 'https://') 
                                    ? $image 
                                    : asset($image);
                            @endphp
                            <div class="gallery-item" onclick="openImageModal('{{ $imageUrl }}')">
                                <img src="{{ $imageUrl }}" alt="Gallery Image" onerror="this.style.display='none'">
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
            @endif

            <!-- Itinerary -->
            @if($tour->itineraries->count() > 0)
            <div class="card info-card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="ri-route-line me-2"></i>Itinerary ({{ $tour->itineraries->count() }} days)
                    </h5>
                    <a href="{{ route('admin.tours.itinerary-builder', ['tour_id' => $tour->id]) }}" class="btn btn-sm btn-light">
                        <i class="ri-edit-line me-1"></i>Manage
                    </a>
                </div>
                <div class="card-body">
                    <div class="timeline">
                        @foreach($tour->itineraries as $itinerary)
                        <div class="timeline-item">
                            <div class="timeline-marker"></div>
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <div>
                                    <h6 class="mb-1">Day {{ $itinerary->day_number }}: {{ $itinerary->title }}</h6>
                                    @if($itinerary->location)
                                        <p class="text-muted small mb-1">
                                            <i class="ri-map-pin-line me-1"></i>{{ $itinerary->location }}
                                        </p>
                                    @endif
                                </div>
                                @if($itinerary->start_time && $itinerary->end_time)
                                    <span class="badge bg-label-info">
                                        {{ $itinerary->start_time->format('H:i') }} - {{ $itinerary->end_time->format('H:i') }}
                                    </span>
                                @endif
                            </div>
                            <p class="text-muted small mb-2">{{ Str::limit($itinerary->description, 200) }}</p>
                            <div class="d-flex gap-2 flex-wrap">
                                @if($itinerary->accommodation)
                                    <span class="badge bg-label-primary">
                                        <i class="ri-hotel-line me-1"></i>{{ $itinerary->accommodation }}
                                    </span>
                                @endif
                                @if($itinerary->meals)
                                    <span class="badge bg-label-success">
                                        <i class="ri-restaurant-line me-1"></i>{{ $itinerary->meals }}
                                    </span>
                                @endif
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
            @else
            <div class="card info-card">
                <div class="card-body text-center py-5">
                    <i class="ri-route-line ri-48px text-muted mb-3 d-block"></i>
                    <p class="text-muted">No itinerary has been created yet.</p>
                    <a href="{{ route('admin.tours.itinerary-builder', ['tour_id' => $tour->id]) }}" class="btn btn-success">
                        <i class="ri-add-line me-1"></i>Create Itinerary
                    </a>
                </div>
            </div>
            @endif

            <!-- Recent Bookings -->
            <div class="card info-card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="ri-calendar-check-line me-2"></i>Recent Bookings
                    </h5>
                    <a href="{{ route('admin.bookings.index', ['tour_id' => $tour->id]) }}" class="btn btn-sm btn-light">
                        <i class="ri-eye-line me-1"></i>View All
                    </a>
                </div>
                <div class="card-body">
                    @if($recentBookings->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Booking Ref</th>
                                    <th>Customer</th>
                                    <th>Travelers</th>
                                    <th>Departure</th>
                                    <th>Amount</th>
                                    <th>Status</th>
                                    <th>Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($recentBookings as $booking)
                                <tr>
                                    <td>
                                        <a href="{{ route('admin.bookings.show', $booking->id) }}" class="text-primary">
                                            {{ $booking->booking_reference ?? '#' . $booking->id }}
                                        </a>
                                    </td>
                                    <td>
                                        <div>
                                            <strong>{{ $booking->customer_name }}</strong>
                                            @if($booking->user)
                                                <br><small class="text-muted">{{ $booking->user->email }}</small>
                                            @else
                                                <br><small class="text-muted">{{ $booking->customer_email }}</small>
                                            @endif
                                        </div>
                                    </td>
                                    <td>{{ $booking->travelers }}</td>
                                    <td>{{ $booking->departure_date ? $booking->departure_date->format('M d, Y') : 'N/A' }}</td>
                                    <td><strong>${{ number_format($booking->total_price, 2) }}</strong></td>
                                    <td>
                                        <span class="badge bg-label-{{ $booking->status == 'confirmed' ? 'success' : ($booking->status == 'pending_payment' ? 'warning' : ($booking->status == 'cancelled' ? 'danger' : 'secondary')) }}">
                                            {{ ucfirst(str_replace('_', ' ', $booking->status)) }}
                                        </span>
                                    </td>
                                    <td>{{ $booking->created_at->format('M d, Y') }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @else
                    <div class="text-center py-4">
                        <i class="ri-calendar-check-line ri-48px text-muted mb-3 d-block"></i>
                        <p class="text-muted">No bookings yet for this tour.</p>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Reviews -->
            @if($tour->reviews->count() > 0)
            <div class="card info-card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="ri-star-line me-2"></i>Reviews ({{ $stats['total_reviews'] }})
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row mb-4">
                        <div class="col-md-4 text-center">
                            <h2 class="mb-0">{{ $stats['average_rating'] }}</h2>
                            <div class="mb-2">
                                @for($i = 0; $i < 5; $i++)
                                    <i class="ri-star-{{ $i < $stats['average_rating'] ? 'fill' : 'line' }} text-warning"></i>
                                @endfor
                            </div>
                            <small class="text-muted">Based on {{ $stats['total_reviews'] }} reviews</small>
                        </div>
                        <div class="col-md-8">
                            <div class="mb-2">
                                <div class="d-flex align-items-center mb-1">
                                    <span class="me-2" style="width: 60px;">5 <i class="ri-star-fill text-warning"></i></span>
                                    <div class="progress flex-grow-1" style="height: 8px;">
                                        <div class="progress-bar bg-warning" style="width: {{ $stats['total_reviews'] > 0 ? ($stats['five_star_reviews'] / $stats['total_reviews'] * 100) : 0 }}%"></div>
                                    </div>
                                    <span class="ms-2 small text-muted">{{ $stats['five_star_reviews'] }}</span>
                                </div>
                            </div>
                            <div class="mb-2">
                                <div class="d-flex align-items-center mb-1">
                                    <span class="me-2" style="width: 60px;">4 <i class="ri-star-fill text-warning"></i></span>
                                    <div class="progress flex-grow-1" style="height: 8px;">
                                        <div class="progress-bar bg-warning" style="width: {{ $stats['total_reviews'] > 0 ? ($stats['four_star_reviews'] / $stats['total_reviews'] * 100) : 0 }}%"></div>
                                    </div>
                                    <span class="ms-2 small text-muted">{{ $stats['four_star_reviews'] }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <hr>
                    <div class="reviews-list">
                        @foreach($tour->reviews->take(5) as $review)
                        <div class="mb-3 pb-3 border-bottom">
                            <div class="d-flex justify-content-between mb-2">
                                <div>
                                    <strong>{{ $review->user->name ?? ($review->customer_name ?? 'Anonymous') }}</strong>
                                    <div class="mt-1">
                                        @for($i = 0; $i < 5; $i++)
                                            <i class="ri-star-{{ $i < $review->rating ? 'fill' : 'line' }} text-warning"></i>
                                        @endfor
                                    </div>
                                </div>
                                <small class="text-muted">{{ $review->created_at->format('M d, Y') }}</small>
                            </div>
                            @if($review->comment ?? $review->review_text ?? null)
                                <p class="mb-0 text-muted">{{ $review->comment ?? $review->review_text }}</p>
                            @endif
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
            @endif
        </div>

        <!-- Sidebar -->
        <div class="col-lg-4">
            <!-- Quick Actions -->
            <div class="card info-card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="ri-settings-3-line me-2"></i>Quick Actions
                    </h5>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="{{ route('admin.tours.edit', $tour->id) }}" class="btn btn-success">
                            <i class="ri-edit-line me-1"></i>Edit Tour
                        </a>
                        <a href="{{ route('admin.tours.itinerary-builder', ['tour_id' => $tour->id]) }}" class="btn btn-outline-success">
                            <i class="ri-route-line me-1"></i>Manage Itinerary
                        </a>
                        <a href="{{ route('admin.tours.availability', ['tour_id' => $tour->id]) }}" class="btn btn-outline-success">
                            <i class="ri-calendar-line me-1"></i>View Availability
                        </a>
                        <a href="{{ route('admin.tours.pricing', ['tour_id' => $tour->id]) }}" class="btn btn-outline-success">
                            <i class="ri-money-dollar-circle-line me-1"></i>Manage Pricing
                        </a>
                        <a href="{{ route('admin.tours.duplicate', $tour->id) }}" class="btn btn-outline-primary" onclick="return confirm('Duplicate this tour?');">
                            <i class="ri-file-copy-line me-1"></i>Duplicate Tour
                        </a>
                    </div>
                </div>
            </div>

            <!-- Booking Statistics -->
            <div class="card info-card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="ri-bar-chart-line me-2"></i>Booking Statistics
                    </h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <div class="d-flex justify-content-between mb-1">
                            <span class="small">Confirmed</span>
                            <span class="small"><strong>{{ $stats['confirmed_bookings'] }}</strong></span>
                        </div>
                        <div class="progress" style="height: 8px;">
                            <div class="progress-bar bg-success" style="width: {{ $stats['total_bookings'] > 0 ? ($stats['confirmed_bookings'] / $stats['total_bookings'] * 100) : 0 }}%"></div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <div class="d-flex justify-content-between mb-1">
                            <span class="small">Pending</span>
                            <span class="small"><strong>{{ $stats['pending_bookings'] }}</strong></span>
                        </div>
                        <div class="progress" style="height: 8px;">
                            <div class="progress-bar bg-warning" style="width: {{ $stats['total_bookings'] > 0 ? ($stats['pending_bookings'] / $stats['total_bookings'] * 100) : 0 }}%"></div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <div class="d-flex justify-content-between mb-1">
                            <span class="small">Cancelled</span>
                            <span class="small"><strong>{{ $stats['cancelled_bookings'] }}</strong></span>
                        </div>
                        <div class="progress" style="height: 8px;">
                            <div class="progress-bar bg-danger" style="width: {{ $stats['total_bookings'] > 0 ? ($stats['cancelled_bookings'] / $stats['total_bookings'] * 100) : 0 }}%"></div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tour Image -->
            @if($tour->image_url)
            <div class="card info-card">
                <div class="card-body p-0">
                    @php
                        $imageUrl = str_starts_with($tour->image_url, 'http://') || str_starts_with($tour->image_url, 'https://') 
                            ? $tour->image_url 
                            : asset($tour->image_url);
                    @endphp
                    <img src="{{ $imageUrl }}" alt="{{ $tour->name }}" class="img-fluid rounded" onerror="this.src='{{ asset('images/safari_home-1.jpg') }}'">
                </div>
            </div>
            @endif

            <!-- Additional Info -->
            <div class="card info-card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="ri-information-line me-2"></i>Additional Information
                    </h5>
                </div>
                <div class="card-body">
                    <table class="table table-sm table-borderless mb-0">
                        <tr>
                            <td class="text-muted small">Created:</td>
                            <td class="small">{{ $tour->created_at->format('M d, Y') }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted small">Last Updated:</td>
                            <td class="small">{{ $tour->updated_at->format('M d, Y') }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted small">Total Itineraries:</td>
                            <td class="small"><strong>{{ $tour->itineraries->count() }}</strong></td>
                        </tr>
                        <tr>
                            <td class="text-muted small">Total Reviews:</td>
                            <td class="small"><strong>{{ $stats['total_reviews'] }}</strong></td>
                        </tr>
                        <tr>
                            <td class="text-muted small">Total Bookings:</td>
                            <td class="small"><strong>{{ $stats['total_bookings'] }}</strong></td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Image Modal -->
<div class="modal fade" id="imageModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-0">
                <img id="modalImage" src="" alt="Gallery Image" class="img-fluid">
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
function openImageModal(imageUrl) {
    document.getElementById('modalImage').src = imageUrl;
    const modal = new bootstrap.Modal(document.getElementById('imageModal'));
    modal.show();
}
</script>
@endpush
@endsection
