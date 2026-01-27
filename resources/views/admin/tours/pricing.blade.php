@extends('admin.layouts.app')

@section('title', 'Tour Pricing - Lau Paradise Adventures')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">
                        <i class="ri-price-tag-3-line me-2"></i>Tour Pricing Management
                    </h4>
                    <div class="d-flex gap-2">
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addPricingModal">
                            <i class="ri-add-line me-1"></i>Add Pricing
                        </button>
                        <a href="{{ route('admin.tours.index') }}" class="btn btn-outline-secondary">
                            <i class="ri-arrow-left-line me-1"></i>Back to Tours
                        </a>
                    </div>
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
                            <h5 class="mb-0">{{ $tours->total() }}</h5>
                            <small class="text-muted">Total Tours</small>
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
                                <i class="ri-money-dollar-circle-line"></i>
                            </span>
                        </div>
                        <div>
                            <h5 class="mb-0">${{ number_format($tours->avg('price') ?? 0, 2) }}</h5>
                            <small class="text-muted">Avg. Price</small>
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
                                <i class="ri-price-tag-3-line"></i>
                            </span>
                        </div>
                        <div>
                            <h5 class="mb-0">${{ number_format($tours->min('price') ?? 0, 2) }}</h5>
                            <small class="text-muted">Min. Price</small>
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
                                <i class="ri-price-tag-3-line"></i>
                            </span>
                        </div>
                        <div>
                            <h5 class="mb-0">${{ number_format($tours->max('price') ?? 0, 2) }}</h5>
                            <small class="text-muted">Max. Price</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('admin.tours.pricing') }}" class="row g-3">
                <div class="col-md-4">
                    <label class="form-label">Tour</label>
                    <select name="tour_id" class="form-select">
                        <option value="">All Tours</option>
                        @foreach($allTours as $tour)
                            <option value="{{ $tour->id }}" {{ request('tour_id') == $tour->id ? 'selected' : '' }}>
                                {{ $tour->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Search</label>
                    <input type="text" name="search" class="form-control" placeholder="Search tours..." value="{{ request('search') }}">
                </div>
                <div class="col-md-2">
                    <label class="form-label">Price Range</label>
                    <select name="price_range" class="form-select">
                        <option value="">All Prices</option>
                        <option value="0-500" {{ request('price_range') == '0-500' ? 'selected' : '' }}>$0 - $500</option>
                        <option value="500-1000" {{ request('price_range') == '500-1000' ? 'selected' : '' }}>$500 - $1,000</option>
                        <option value="1000-2000" {{ request('price_range') == '1000-2000' ? 'selected' : '' }}>$1,000 - $2,000</option>
                        <option value="2000+" {{ request('price_range') == '2000+' ? 'selected' : '' }}>$2,000+</option>
                    </select>
                </div>
                <div class="col-md-2 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="ri-search-line me-1"></i>Filter
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Tours Pricing Table -->
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">Tour Pricing</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Tour Name</th>
                            <th>Destination</th>
                            <th>Duration</th>
                            <th>Base Price</th>
                            <th>Seasonal Pricing</th>
                            <th>Group Discount</th>
                            <th>Status</th>
                            <th width="150">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($tours as $tour)
                        <tr>
                            <td>
                                <div class="d-flex align-items-center">
                                    @if($tour->image_url)
                                        @php
                                            $imageUrl = str_starts_with($tour->image_url, 'http://') || str_starts_with($tour->image_url, 'https://') 
                                                ? $tour->image_url 
                                                : asset($tour->image_url);
                                        @endphp
                                        <img src="{{ $imageUrl }}" alt="{{ $tour->name }}" 
                                             class="rounded me-2" style="width: 40px; height: 40px; object-fit: cover;" onerror="this.src='{{ asset('images/safari_home-1.jpg') }}'">
                                    @else
                                        <div class="avatar avatar-sm me-2">
                                            <span class="avatar-initial rounded bg-label-primary">
                                                {{ strtoupper(substr($tour->name, 0, 1)) }}
                                            </span>
                                        </div>
                                    @endif
                                    <div>
                                        <strong>{{ $tour->name }}</strong>
                                        @if($tour->is_featured)
                                            <i class="ri-star-fill text-warning ms-1" title="Featured"></i>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td>
                                @if($tour->destination)
                                    <span class="badge bg-label-info">{{ $tour->destination->name }}</span>
                                @else
                                    <span class="text-muted">N/A</span>
                                @endif
                            </td>
                            <td>
                                <i class="ri-time-line me-1"></i>{{ $tour->duration_days ?? 0 }} Days
                            </td>
                            <td>
                                <strong class="text-success">${{ number_format($tour->price ?? 0, 2) }}</strong>
                                <br><small class="text-muted">per person</small>
                            </td>
                            <td>
                                <span class="badge bg-label-warning">
                                    <i class="ri-calendar-line me-1"></i>Configure
                                </span>
                            </td>
                            <td>
                                <span class="badge bg-label-info">Not Set</span>
                            </td>
                            <td>
                                @if($tour->is_featured)
                                    <span class="badge bg-success">Active</span>
                                @else
                                    <span class="badge bg-secondary">Inactive</span>
                                @endif
                            </td>
                            <td>
                                <div class="d-flex gap-1">
                                    <button type="button" class="btn btn-sm btn-icon btn-outline-primary" 
                                            onclick="viewPricing({{ $tour->id }})" title="View Pricing Details">
                                        <i class="ri-eye-line"></i>
                                    </button>
                                    <button type="button" class="btn btn-sm btn-icon btn-outline-warning" 
                                            onclick="editPricing({{ $tour->id }})" title="Edit Pricing">
                                        <i class="ri-edit-line"></i>
                                    </button>
                                    <button type="button" class="btn btn-sm btn-icon btn-outline-info" 
                                            onclick="viewTourDetails({{ $tour->id }})" title="View Tour">
                                        <i class="ri-information-line"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center py-5">
                                <div class="text-muted">
                                    <i class="ri-price-tag-3-line ri-48px mb-3 d-block"></i>
                                    <p>No tours found</p>
                                    <a href="{{ route('admin.tours.create') }}" class="btn btn-primary btn-sm">
                                        <i class="ri-add-line me-1"></i>Create Tour
                                    </a>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-4">
                {{ $tours->links() }}
            </div>
        </div>
    </div>
</div>

<!-- Add/Edit Pricing Modal -->
<div class="modal fade" id="addPricingModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="pricingModalTitle">Add Tour Pricing</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="pricingForm" method="POST" action="{{ route('admin.tours.pricing.store') }}">
                @csrf
                <input type="hidden" name="_method" id="pricingMethod" value="POST">
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Tour <span class="text-danger">*</span></label>
                            <select name="tour_id" id="pricingTourId" class="form-select" required>
                                <option value="">Select Tour</option>
                                @foreach($allTours as $tour)
                                    <option value="{{ $tour->id }}" data-price="{{ $tour->price }}">
                                        {{ $tour->name }} - ${{ number_format($tour->price, 2) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Pricing Type <span class="text-danger">*</span></label>
                            <select name="pricing_type" id="pricingType" class="form-select" required>
                                <option value="standard">Standard Pricing</option>
                                <option value="seasonal">Seasonal Pricing</option>
                                <option value="group">Group Pricing</option>
                                <option value="custom">Custom Pricing</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Base Price (per person) <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text">$</span>
                                <input type="number" name="base_price" id="pricingBasePrice" class="form-control" 
                                       step="0.01" min="0" required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Child Price (per person)</label>
                            <div class="input-group">
                                <span class="input-group-text">$</span>
                                <input type="number" name="child_price" id="pricingChildPrice" class="form-control" 
                                       step="0.01" min="0">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Infant Price (per person)</label>
                            <div class="input-group">
                                <span class="input-group-text">$</span>
                                <input type="number" name="infant_price" id="pricingInfantPrice" class="form-control" 
                                       step="0.01" min="0">
                            </div>
                        </div>
                        <div class="col-12">
                            <h6 class="mb-3">Seasonal Pricing</h6>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Low Season Price</label>
                            <div class="input-group">
                                <span class="input-group-text">$</span>
                                <input type="number" name="low_season_price" id="pricingLowSeason" class="form-control" 
                                       step="0.01" min="0">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">High Season Price</label>
                            <div class="input-group">
                                <span class="input-group-text">$</span>
                                <input type="number" name="high_season_price" id="pricingHighSeason" class="form-control" 
                                       step="0.01" min="0">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Peak Season Price</label>
                            <div class="input-group">
                                <span class="input-group-text">$</span>
                                <input type="number" name="peak_season_price" id="pricingPeakSeason" class="form-control" 
                                       step="0.01" min="0">
                            </div>
                        </div>
                        <div class="col-12">
                            <h6 class="mb-3">Group Discounts</h6>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Group Size (min)</label>
                            <input type="number" name="group_min_size" id="pricingGroupMin" class="form-control" min="2" value="5">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Discount (%)</label>
                            <div class="input-group">
                                <input type="number" name="group_discount" id="pricingGroupDiscount" class="form-control" 
                                       min="0" max="100" value="10">
                                <span class="input-group-text">%</span>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Valid From</label>
                            <input type="date" name="valid_from" id="pricingValidFrom" class="form-control">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Valid Until</label>
                            <input type="date" name="valid_until" id="pricingValidUntil" class="form-control">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Currency</label>
                            <select name="currency" id="pricingCurrency" class="form-select">
                                <option value="USD" selected>USD ($)</option>
                                <option value="EUR">EUR (€)</option>
                                <option value="GBP">GBP (£)</option>
                                <option value="TZS">TZS (TSh)</option>
                            </select>
                        </div>
                        <div class="col-12">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" name="is_active" id="pricingIsActive" checked>
                                <label class="form-check-label" for="pricingIsActive">
                                    Active Pricing
                                </label>
                            </div>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Notes</label>
                            <textarea name="notes" id="pricingNotes" class="form-control" rows="3" 
                                      placeholder="Additional notes about pricing..."></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="ri-save-line me-1"></i>Save Pricing
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- View Pricing Details Modal -->
<div class="modal fade" id="viewPricingModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="viewPricingModalTitle">Pricing Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="pricingDetailsContent">
                <div class="text-center py-4">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="editFromViewBtn">Edit Pricing</button>
            </div>
        </div>
    </div>
</div>

<!-- View Tour Details Modal -->
<div class="modal fade" id="viewTourDetailsModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="viewTourDetailsModalTitle">Tour Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="tourDetailsContent">
                <div class="text-center py-4">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<script>
// Auto-fill base price when tour is selected
document.getElementById('pricingTourId').addEventListener('change', function() {
    const selectedOption = this.options[this.selectedIndex];
    const price = selectedOption.dataset.price;
    if (price) {
        document.getElementById('pricingBasePrice').value = price;
    }
});

function viewPricing(tourId) {
    // Show loading state
    document.getElementById('pricingDetailsContent').innerHTML = `
        <div class="text-center py-4">
            <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
            <p class="mt-2 text-muted">Loading pricing details...</p>
        </div>
    `;
    
    const modal = new bootstrap.Modal(document.getElementById('viewPricingModal'));
    modal.show();
    
    // Fetch pricing details
    fetch(`{{ url('admin/tours') }}/${tourId}/pricing-details`)
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.json();
        })
        .then(data => {
            if (data.success && data.tour) {
                const tour = data.tour;
                const stats = tour.stats || {};
                
                // Update modal title
                document.getElementById('viewPricingModalTitle').textContent = `Pricing Details - ${tour.name}`;
                
                document.getElementById('pricingDetailsContent').innerHTML = `
                    <div class="pricing-details">
                        <!-- Tour Header -->
                        <div class="d-flex align-items-center mb-4 pb-3 border-bottom">
                            ${tour.image_url ? `
                                <img src="${tour.image_url}" alt="${tour.name}" 
                                     class="rounded me-3" style="width: 80px; height: 80px; object-fit: cover;" onerror="this.src='{{ asset('images/safari_home-1.jpg') }}'">
                            ` : `
                                <div class="avatar me-3" style="width: 80px; height: 80px;">
                                    <span class="avatar-initial rounded bg-label-primary" style="font-size: 2rem;">
                                        ${tour.name.charAt(0).toUpperCase()}
                                    </span>
                                </div>
                            `}
                            <div class="flex-grow-1">
                                <h5 class="mb-1">${tour.name}</h5>
                                ${tour.destination ? `<p class="text-muted mb-0"><i class="ri-map-pin-line me-1"></i>${tour.destination.name}</p>` : ''}
                                ${tour.is_featured ? `<span class="badge bg-warning mt-1"><i class="ri-star-fill me-1"></i>Featured</span>` : ''}
                            </div>
                        </div>
                        
                        <!-- Pricing Overview -->
                        <div class="row mb-4">
                            <div class="col-md-6 mb-3">
                                <div class="card card-border-shadow-primary h-100">
                                    <div class="card-body">
                                        <div class="d-flex align-items-center">
                                            <div class="avatar me-3">
                                                <span class="avatar-initial rounded bg-label-primary">
                                                    <i class="ri-money-dollar-circle-line"></i>
                                                </span>
                                            </div>
                                            <div>
                                                <h4 class="mb-0 text-primary">$${parseFloat(tour.price || 0).toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2})}</h4>
                                                <small class="text-muted">Base Price (per person)</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="card card-border-shadow-success h-100">
                                    <div class="card-body">
                                        <div class="d-flex align-items-center">
                                            <div class="avatar me-3">
                                                <span class="avatar-initial rounded bg-label-success">
                                                    <i class="ri-calendar-check-line"></i>
                                                </span>
                                            </div>
                                            <div>
                                                <h4 class="mb-0 text-success">${tour.duration_days || 0}</h4>
                                                <small class="text-muted">Duration (days)</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Statistics -->
                        <div class="row mb-4">
                            <div class="col-md-3 mb-3">
                                <div class="card h-100">
                                    <div class="card-body text-center">
                                        <h5 class="mb-1">${stats.total_bookings || 0}</h5>
                                        <small class="text-muted">Total Bookings</small>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3 mb-3">
                                <div class="card h-100">
                                    <div class="card-body text-center">
                                        <h5 class="mb-1 text-success">${stats.confirmed_bookings || 0}</h5>
                                        <small class="text-muted">Confirmed</small>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3 mb-3">
                                <div class="card h-100">
                                    <div class="card-body text-center">
                                        <h5 class="mb-1 text-primary">$${parseFloat(stats.total_revenue || 0).toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2})}</h5>
                                        <small class="text-muted">Total Revenue</small>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3 mb-3">
                                <div class="card h-100">
                                    <div class="card-body text-center">
                                        <h5 class="mb-1 text-warning">
                                            ${stats.average_rating ? '★'.repeat(Math.round(stats.average_rating)) : 'N/A'}
                                        </h5>
                                        <small class="text-muted">Avg Rating (${stats.average_rating || 0})</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Detailed Information -->
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <h6 class="mb-3"><i class="ri-information-line me-2"></i>Tour Information</h6>
                                <table class="table table-sm">
                                    <tbody>
                                        <tr>
                                            <td class="text-muted" style="width: 40%;">Tour ID:</td>
                                            <td><strong>#${tour.id}</strong></td>
                                        </tr>
                                        <tr>
                                            <td class="text-muted">Fitness Level:</td>
                                            <td><span class="badge bg-label-info">${tour.fitness_level || 'N/A'}</span></td>
                                        </tr>
                                        <tr>
                                            <td class="text-muted">Rating:</td>
                                            <td>${tour.rating ? '★'.repeat(Math.round(tour.rating)) + ` (${tour.rating})` : 'N/A'}</td>
                                        </tr>
                                        <tr>
                                            <td class="text-muted">Status:</td>
                                            <td>${tour.is_featured ? '<span class="badge bg-success">Active & Featured</span>' : '<span class="badge bg-secondary">Active</span>'}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div class="col-md-6 mb-3">
                                <h6 class="mb-3"><i class="ri-price-tag-3-line me-2"></i>Pricing Details</h6>
                                <table class="table table-sm">
                                    <tbody>
                                        <tr>
                                            <td class="text-muted" style="width: 40%;">Base Price:</td>
                                            <td><strong class="text-success">$${parseFloat(tour.price || 0).toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2})}</strong></td>
                                        </tr>
                                        <tr>
                                            <td class="text-muted">Currency:</td>
                                            <td><strong>USD</strong></td>
                                        </tr>
                                        <tr>
                                            <td class="text-muted">Per Person:</td>
                                            <td>Adult pricing</td>
                                        </tr>
                                        <tr>
                                            <td class="text-muted">Seasonal Pricing:</td>
                                            <td><span class="badge bg-label-warning">Not Configured</span></td>
                                        </tr>
                                        <tr>
                                            <td class="text-muted">Group Discount:</td>
                                            <td><span class="badge bg-label-info">Not Configured</span></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        
                        ${tour.excerpt ? `
                        <div class="mt-3">
                            <h6 class="mb-2"><i class="ri-file-text-line me-2"></i>Description</h6>
                            <p class="text-muted">${tour.excerpt}</p>
                        </div>
                        ` : ''}
                    </div>
                `;
            } else {
                document.getElementById('pricingDetailsContent').innerHTML = `
                    <div class="alert alert-danger">
                        <i class="ri-error-warning-line me-2"></i>Failed to load pricing details.
                    </div>
                `;
            }
        })
        .catch(error => {
            console.error('Error loading pricing details:', error);
            document.getElementById('pricingDetailsContent').innerHTML = `
                <div class="alert alert-danger">
                    <i class="ri-error-warning-line me-2"></i>Error loading pricing details: ${error.message || 'Please try again.'}
                    <br><small class="text-muted">If this problem persists, please check the console for more details.</small>
                </div>
            `;
        });
    
    // Set edit button handler
    document.getElementById('editFromViewBtn').onclick = () => {
        modal.hide();
        editPricing(tourId);
    };
}

function editPricing(tourId) {
    // Set form for editing
    document.getElementById('pricingModalTitle').textContent = 'Edit Tour Pricing';
    document.getElementById('pricingMethod').value = 'PUT';
    document.getElementById('pricingTourId').value = tourId;
    document.getElementById('pricingTourId').disabled = true;
    document.getElementById('pricingForm').action = `{{ url('admin/tours') }}/${tourId}/pricing`;
    
    // Load tour data and populate form
    fetch(`{{ url('admin/tours') }}/${tourId}/details`)
        .then(response => response.json())
        .then(data => {
            if (data.success && data.tour) {
                if (data.tour.price) {
                    document.getElementById('pricingBasePrice').value = data.tour.price;
                }
            }
        })
        .catch(error => {
            console.error('Error loading tour details:', error);
        });
    
    new bootstrap.Modal(document.getElementById('addPricingModal')).show();
}

function viewTourDetails(tourId) {
    // Show loading state
    document.getElementById('tourDetailsContent').innerHTML = `
        <div class="text-center py-4">
            <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
            <p class="mt-2 text-muted">Loading tour details...</p>
        </div>
    `;
    
    const modal = new bootstrap.Modal(document.getElementById('viewTourDetailsModal'));
    modal.show();
    
    // Fetch tour details
    fetch(`{{ url('admin/tours') }}/${tourId}/details`)
        .then(response => response.json())
        .then(data => {
            if (data.success && data.tour) {
                const tour = data.tour;
                
                // Update modal title
                document.getElementById('viewTourDetailsModalTitle').textContent = `Tour Details - ${tour.name}`;
                
                document.getElementById('tourDetailsContent').innerHTML = `
                    <div class="tour-details">
                        <!-- Tour Header -->
                        <div class="d-flex align-items-center mb-4 pb-3 border-bottom">
                            ${tour.image_url ? `
                                <img src="${tour.image_url}" alt="${tour.name}" 
                                     class="rounded me-3" style="width: 100px; height: 100px; object-fit: cover;" onerror="this.src='{{ asset('images/safari_home-1.jpg') }}'">
                            ` : `
                                <div class="avatar me-3" style="width: 100px; height: 100px;">
                                    <span class="avatar-initial rounded bg-label-primary" style="font-size: 2.5rem;">
                                        ${tour.name.charAt(0).toUpperCase()}
                                    </span>
                                </div>
                            `}
                            <div class="flex-grow-1">
                                <h4 class="mb-1">${tour.name}</h4>
                                ${tour.destination ? `<p class="text-muted mb-1"><i class="ri-map-pin-line me-1"></i>${tour.destination.name}</p>` : ''}
                                <div class="d-flex gap-2 mt-2">
                                    ${tour.is_featured ? '<span class="badge bg-warning"><i class="ri-star-fill me-1"></i>Featured</span>' : ''}
                                    ${tour.fitness_level ? `<span class="badge bg-info">${tour.fitness_level}</span>` : ''}
                                    ${tour.rating ? `<span class="badge bg-success">★ ${tour.rating}</span>` : ''}
                                </div>
                            </div>
                        </div>
                        
                        <!-- Key Information -->
                        <div class="row mb-4">
                            <div class="col-md-4 mb-3">
                                <div class="card h-100">
                                    <div class="card-body text-center">
                                        <i class="ri-time-line ri-24px text-primary mb-2"></i>
                                        <h5 class="mb-1">${tour.duration_days || 0}</h5>
                                        <small class="text-muted">Days</small>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4 mb-3">
                                <div class="card h-100">
                                    <div class="card-body text-center">
                                        <i class="ri-money-dollar-circle-line ri-24px text-success mb-2"></i>
                                        <h5 class="mb-1">$${parseFloat(tour.price || 0).toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2})}</h5>
                                        <small class="text-muted">Price</small>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4 mb-3">
                                <div class="card h-100">
                                    <div class="card-body text-center">
                                        <i class="ri-calendar-check-line ri-24px text-info mb-2"></i>
                                        <h5 class="mb-1">${tour.bookings_count || 0}</h5>
                                        <small class="text-muted">Bookings</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Details Table -->
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <h6 class="mb-3"><i class="ri-information-line me-2"></i>Tour Details</h6>
                                <table class="table table-sm">
                                    <tbody>
                                        <tr>
                                            <td class="text-muted" style="width: 40%;">Tour ID:</td>
                                            <td><strong>#${tour.id}</strong></td>
                                        </tr>
                                        <tr>
                                            <td class="text-muted">Slug:</td>
                                            <td><code>${tour.slug || 'N/A'}</code></td>
                                        </tr>
                                        <tr>
                                            <td class="text-muted">Duration:</td>
                                            <td><strong>${tour.duration_days || 0} days</strong></td>
                                        </tr>
                                        <tr>
                                            <td class="text-muted">Price:</td>
                                            <td><strong class="text-success">$${parseFloat(tour.price || 0).toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2})}</strong></td>
                                        </tr>
                                        <tr>
                                            <td class="text-muted">Fitness Level:</td>
                                            <td><span class="badge bg-label-info">${tour.fitness_level || 'N/A'}</span></td>
                                        </tr>
                                        <tr>
                                            <td class="text-muted">Rating:</td>
                                            <td>${tour.rating ? '★'.repeat(Math.round(tour.rating)) + ` (${tour.rating})` : 'N/A'}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div class="col-md-6 mb-3">
                                <h6 class="mb-3"><i class="ri-bar-chart-line me-2"></i>Statistics</h6>
                                <table class="table table-sm">
                                    <tbody>
                                        <tr>
                                            <td class="text-muted" style="width: 40%;">Total Bookings:</td>
                                            <td><strong>${tour.bookings_count || 0}</strong></td>
                                        </tr>
                                        <tr>
                                            <td class="text-muted">Reviews:</td>
                                            <td><strong>${tour.reviews_count || 0}</strong></td>
                                        </tr>
                                        <tr>
                                            <td class="text-muted">Itineraries:</td>
                                            <td><strong>${tour.itineraries_count || 0}</strong></td>
                                        </tr>
                                        <tr>
                                            <td class="text-muted">Categories:</td>
                                            <td>
                                                ${tour.categories && tour.categories.length > 0 
                                                    ? tour.categories.map(cat => `<span class="badge bg-label-primary me-1">${cat.name}</span>`).join('')
                                                    : '<span class="text-muted">None</span>'}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="text-muted">Status:</td>
                                            <td>${tour.is_featured ? '<span class="badge bg-success">Active & Featured</span>' : '<span class="badge bg-secondary">Active</span>'}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        
                        ${tour.excerpt ? `
                        <div class="mt-3">
                            <h6 class="mb-2"><i class="ri-file-text-line me-2"></i>Description</h6>
                            <p class="text-muted">${tour.excerpt}</p>
                        </div>
                        ` : ''}
                        
                        ${tour.description ? `
                        <div class="mt-3">
                            <h6 class="mb-2"><i class="ri-file-text-line me-2"></i>Full Description</h6>
                            <div class="text-muted" style="max-height: 200px; overflow-y: auto;">
                                ${tour.description.substring(0, 500)}${tour.description.length > 500 ? '...' : ''}
                            </div>
                        </div>
                        ` : ''}
                        
                        <div class="mt-4 pt-3 border-top">
                            <a href="{{ url('admin/tours') }}/${tour.id}" class="btn btn-primary me-2">
                                <i class="ri-eye-line me-1"></i>View Full Details
                            </a>
                            <a href="{{ url('admin/tours') }}/${tour.id}/edit" class="btn btn-outline-warning">
                                <i class="ri-edit-line me-1"></i>Edit Tour
                            </a>
                        </div>
                    </div>
                `;
            } else {
                document.getElementById('tourDetailsContent').innerHTML = `
                    <div class="alert alert-danger">
                        <i class="ri-error-warning-line me-2"></i>Failed to load tour details.
                    </div>
                `;
            }
        })
        .catch(error => {
            console.error('Error:', error);
            document.getElementById('tourDetailsContent').innerHTML = `
                <div class="alert alert-danger">
                    <i class="ri-error-warning-line me-2"></i>Error loading tour details. Please try again.
                </div>
            `;
        });
}

// Form submission
document.getElementById('pricingForm').addEventListener('submit', function(e) {
    e.preventDefault();
    const formData = new FormData(this);
    const method = formData.get('_method');
    const url = this.action || (method === 'PUT' 
        ? `{{ url('admin/tours') }}/${formData.get('tour_id')}/pricing`
        : '{{ route("admin.tours.pricing.store") }}');
    
    fetch(url, {
        method: method === 'PUT' ? 'PUT' : 'POST',
        body: formData,
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            window.location.reload();
        } else {
            alert('Error: ' + (data.message || 'Failed to save pricing'));
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred. Please try again.');
    });
});
</script>
@endsection
