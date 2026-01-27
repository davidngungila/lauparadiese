@extends('admin.layouts.app')

@section('title', 'Room Pricing Management - Lau Paradise Adventures')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">
                        <i class="ri-money-dollar-circle-line me-2"></i>Room Pricing Management
                    </h4>
                    <div class="d-flex gap-2">
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addPricingModal">
                            <i class="ri-add-line me-1"></i>Add Pricing
                        </button>
                        <a href="{{ route('admin.hotels.index') }}" class="btn btn-outline-secondary">
                            <i class="ri-arrow-left-line me-1"></i>Back to Hotels
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
                                <i class="ri-hotel-line"></i>
                            </span>
                        </div>
                        <div>
                            <h5 class="mb-0">{{ $hotels->total() }}</h5>
                            <small class="text-muted">Total Hotels</small>
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
                                <i class="ri-door-open-line"></i>
                            </span>
                        </div>
                        <div>
                            <h5 class="mb-0">{{ $hotels->sum('total_rooms') ?? 0 }}</h5>
                            <small class="text-muted">Total Rooms</small>
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
                            <h5 class="mb-0">${{ number_format(0, 2) }}</h5>
                            <small class="text-muted">Avg. Price/Night</small>
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
                                <i class="ri-calendar-check-line"></i>
                            </span>
                        </div>
                        <div>
                            <h5 class="mb-0">{{ $hotels->where('is_active', true)->count() }}</h5>
                            <small class="text-muted">Active Hotels</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('admin.hotels.room-pricing') }}" class="row g-3">
                <div class="col-md-4">
                    <label class="form-label">Hotel</label>
                    <select name="hotel_id" class="form-select">
                        <option value="">All Hotels</option>
                        @foreach($allHotels as $h)
                            <option value="{{ $h->id }}" {{ request('hotel_id') == $h->id ? 'selected' : '' }}>
                                {{ $h->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Search</label>
                    <input type="text" name="search" class="form-control" placeholder="Search hotels..." value="{{ request('search') }}">
                </div>
                <div class="col-md-2">
                    <label class="form-label">Status</label>
                    <select name="status" class="form-select">
                        <option value="">All</option>
                        <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                        <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
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

    <!-- Hotels Pricing Table -->
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">Hotels & Room Pricing</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th width="40">
                                <input type="checkbox" class="form-check-input" id="selectAll">
                            </th>
                            <th>Hotel Name</th>
                            <th>Location</th>
                            <th>Star Rating</th>
                            <th>Room Types</th>
                            <th>Base Price Range</th>
                            <th>Seasonal Pricing</th>
                            <th>Status</th>
                            <th width="150">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($hotels as $hotel)
                        <tr>
                            <td>
                                <input type="checkbox" class="form-check-input hotel-checkbox" value="{{ $hotel->id }}">
                            </td>
                            <td>
                                <div class="d-flex align-items-center">
                                    @if($hotel->image_url)
                                        <img src="{{ $hotel->image_url }}" alt="{{ $hotel->name }}" 
                                             class="rounded me-2" style="width: 40px; height: 40px; object-fit: cover;">
                                    @else
                                        <div class="avatar avatar-sm me-2">
                                            <span class="avatar-initial rounded bg-label-primary">
                                                {{ strtoupper(substr($hotel->name, 0, 1)) }}
                                            </span>
                                        </div>
                                    @endif
                                    <div>
                                        <strong>{{ $hotel->name }}</strong>
                                        @if($hotel->partner)
                                            <br><small class="text-muted">{{ $hotel->partner->name }}</small>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div>
                                    <i class="ri-map-pin-line me-1 text-muted"></i>
                                    {{ $hotel->city ?? ($hotel->address ?? 'N/A') }}
                                </div>
                                @if($hotel->country)
                                    <small class="text-muted">{{ $hotel->country }}</small>
                                @endif
                            </td>
                            <td>
                                @if($hotel->star_rating)
                                    @for($i = 1; $i <= 5; $i++)
                                        <i class="ri-star-{{ $i <= $hotel->star_rating ? 'fill' : 'line' }} text-warning"></i>
                                    @endfor
                                    <br><small class="text-muted">{{ $hotel->star_rating }} Star</small>
                                @else
                                    <span class="text-muted">N/A</span>
                                @endif
                            </td>
                            <td>
                                <span class="badge bg-label-info">{{ $hotel->total_rooms ?? 0 }} Rooms</span>
                                @if($hotel->amenities)
                                    <br><small class="text-muted">{{ count($hotel->amenities) }} Amenities</small>
                                @endif
                            </td>
                            <td>
                                <div>
                                    <strong class="text-success">${{ number_format(0, 2) }}</strong>
                                    <span class="text-muted">-</span>
                                    <strong class="text-success">${{ number_format(0, 2) }}</strong>
                                </div>
                                <small class="text-muted">per night</small>
                            </td>
                            <td>
                                <span class="badge bg-label-warning">
                                    <i class="ri-calendar-line me-1"></i>Configure
                                </span>
                            </td>
                            <td>
                                @if($hotel->is_active)
                                    <span class="badge bg-success">Active</span>
                                @else
                                    <span class="badge bg-secondary">Inactive</span>
                                @endif
                            </td>
                            <td>
                                <div class="d-flex gap-1">
                                    <button type="button" class="btn btn-sm btn-icon btn-outline-primary" 
                                            onclick="viewHotelDetails({{ $hotel->id }})" title="View Details">
                                        <i class="ri-eye-line"></i>
                                    </button>
                                    <button type="button" class="btn btn-sm btn-icon btn-outline-warning" 
                                            onclick="editPricing({{ $hotel->id }})" title="Edit Pricing">
                                        <i class="ri-edit-line"></i>
                                    </button>
                                    <button type="button" class="btn btn-sm btn-icon btn-outline-info" 
                                            onclick="manageRoomTypes({{ $hotel->id }})" title="Manage Room Types">
                                        <i class="ri-door-open-line"></i>
                                    </button>
                                    <button type="button" class="btn btn-sm btn-icon btn-outline-danger" 
                                            onclick="deletePricing({{ $hotel->id }}, '{{ $hotel->name }}')" title="Delete">
                                        <i class="ri-delete-bin-line"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="9" class="text-center py-5">
                                <div class="text-muted">
                                    <i class="ri-hotel-line ri-48px mb-3 d-block"></i>
                                    <p>No hotels found</p>
                                    <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addPricingModal">
                                        <i class="ri-add-line me-1"></i>Add Pricing
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-4">
                {{ $hotels->links() }}
            </div>
        </div>
    </div>
</div>

<!-- View Hotel Details Modal -->
<div class="modal fade" id="viewHotelModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Hotel Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="hotelDetailsContent">
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

<!-- Add/Edit Pricing Modal -->
<div class="modal fade" id="addPricingModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="pricingModalTitle">Add Room Pricing</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="pricingForm" method="POST">
                @csrf
                <input type="hidden" name="_method" id="pricingMethod" value="POST">
                <input type="hidden" name="hotel_id" id="pricingHotelId">
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Hotel <span class="text-danger">*</span></label>
                            <select name="hotel_id" id="pricingHotelSelect" class="form-select" required>
                                <option value="">Select Hotel</option>
                                @foreach($allHotels as $h)
                                    <option value="{{ $h->id }}">{{ $h->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Room Type <span class="text-danger">*</span></label>
                            <select name="room_type" id="roomTypeSelect" class="form-select" required>
                                <option value="">Select Room Type</option>
                                <option value="single">Single Room</option>
                                <option value="double">Double Room</option>
                                <option value="twin">Twin Room</option>
                                <option value="suite">Suite</option>
                                <option value="deluxe">Deluxe Room</option>
                                <option value="family">Family Room</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Base Price (per night) <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text">$</span>
                                <input type="number" name="base_price" id="basePrice" class="form-control" 
                                       step="0.01" min="0" required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Weekend Price</label>
                            <div class="input-group">
                                <span class="input-group-text">$</span>
                                <input type="number" name="weekend_price" id="weekendPrice" class="form-control" 
                                       step="0.01" min="0">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Holiday Price</label>
                            <div class="input-group">
                                <span class="input-group-text">$</span>
                                <input type="number" name="holiday_price" id="holidayPrice" class="form-control" 
                                       step="0.01" min="0">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Season</label>
                            <select name="season" id="seasonSelect" class="form-select">
                                <option value="all">All Seasons</option>
                                <option value="low">Low Season</option>
                                <option value="peak">Peak Season</option>
                                <option value="high">High Season</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Max Occupancy</label>
                            <input type="number" name="max_occupancy" id="maxOccupancy" class="form-control" 
                                   min="1" max="10" value="2">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Valid From</label>
                            <input type="date" name="valid_from" id="validFrom" class="form-control">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Valid Until</label>
                            <input type="date" name="valid_until" id="validUntil" class="form-control">
                        </div>
                        <div class="col-12">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" name="is_active" id="pricingIsActive" checked>
                                <label class="form-check-label" for="pricingIsActive">
                                    Active
                                </label>
                            </div>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Notes</label>
                            <textarea name="notes" id="pricingNotes" class="form-control" rows="3" 
                                      placeholder="Additional notes about this pricing..."></textarea>
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

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deletePricingModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Delete Pricing</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete pricing for <strong id="deleteHotelName"></strong>?</p>
                <p class="text-danger">This action cannot be undone.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">Cancel</button>
                <form id="deletePricingForm" method="POST" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">
                        <i class="ri-delete-bin-line me-1"></i>Delete
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
// View Hotel Details
function viewHotelDetails(hotelId) {
    fetch(`{{ url('admin/hotels') }}/${hotelId}/details`)
        .then(response => response.json())
        .then(data => {
            let amenitiesHtml = '';
            if (data.amenities) {
                try {
                    const amenities = typeof data.amenities === 'string' ? JSON.parse(data.amenities) : data.amenities;
                    if (Array.isArray(amenities)) {
                        amenitiesHtml = amenities.map(a => `<span class="badge bg-label-primary me-1 mb-1">${a}</span>`).join('');
                    }
                } catch(e) {
                    amenitiesHtml = '';
                }
            }
            
            const starRating = data.star_rating ? '★'.repeat(data.star_rating) + ' (' + data.star_rating + ' Star)' : 'N/A';
            
            const content = `
                <div class="row g-3">
                    ${data.image_url ? `
                        <div class="col-12 text-center mb-3">
                            <img src="${data.image_url}" alt="${data.name}" class="img-fluid rounded" style="max-height: 200px;">
                        </div>
                    ` : ''}
                    <div class="col-md-6">
                        <h6><i class="ri-hotel-line me-1"></i>Hotel Information</h6>
                        <table class="table table-sm table-borderless">
                            <tr><td class="fw-medium" style="width: 40%;">Name:</td><td>${data.name || 'N/A'}</td></tr>
                            <tr><td class="fw-medium">Location:</td><td>${data.city || data.address || 'N/A'}</td></tr>
                            <tr><td class="fw-medium">Country:</td><td>${data.country || 'N/A'}</td></tr>
                            <tr><td class="fw-medium">Address:</td><td>${data.address || 'N/A'}</td></tr>
                            <tr><td class="fw-medium">Star Rating:</td><td>${starRating}</td></tr>
                            <tr><td class="fw-medium">Total Rooms:</td><td>${data.total_rooms || 0}</td></tr>
                            <tr><td class="fw-medium">Partner:</td><td>${data.partner || 'N/A'}</td></tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <h6><i class="ri-phone-line me-1"></i>Contact Information</h6>
                        <table class="table table-sm table-borderless">
                            <tr><td class="fw-medium" style="width: 40%;">Phone:</td><td>${data.phone || 'N/A'}</td></tr>
                            <tr><td class="fw-medium">Email:</td><td>${data.email ? `<a href="mailto:${data.email}">${data.email}</a>` : 'N/A'}</td></tr>
                            <tr><td class="fw-medium">Website:</td><td>${data.website ? `<a href="${data.website}" target="_blank">${data.website}</a>` : 'N/A'}</td></tr>
                            <tr><td class="fw-medium">Status:</td><td>${data.is_active ? '<span class="badge bg-success">Active</span>' : '<span class="badge bg-secondary">Inactive</span>'}</td></tr>
                        </table>
                    </div>
                    <div class="col-12">
                        <h6><i class="ri-file-text-line me-1"></i>Description</h6>
                        <p>${data.description || 'No description available.'}</p>
                    </div>
                    ${amenitiesHtml ? `
                        <div class="col-12">
                            <h6><i class="ri-star-line me-1"></i>Amenities</h6>
                            <div class="d-flex flex-wrap gap-2">
                                ${amenitiesHtml}
                            </div>
                        </div>
                    ` : ''}
                </div>
            `;
            document.getElementById('hotelDetailsContent').innerHTML = content;
            document.getElementById('editFromViewBtn').onclick = () => {
                bootstrap.Modal.getInstance(document.getElementById('viewHotelModal')).hide();
                editPricing(hotelId);
            };
            new bootstrap.Modal(document.getElementById('viewHotelModal')).show();
        })
        .catch(error => {
            console.error('Error:', error);
            document.getElementById('hotelDetailsContent').innerHTML = 
                '<div class="alert alert-danger">Failed to load hotel details. Please try again.</div>';
        });
}

// Edit Pricing
function editPricing(hotelId) {
    // Reset form
    document.getElementById('pricingForm').reset();
    document.getElementById('pricingModalTitle').textContent = 'Edit Room Pricing';
    document.getElementById('pricingMethod').value = 'PUT';
    document.getElementById('pricingHotelId').value = hotelId;
    document.getElementById('pricingHotelSelect').value = hotelId;
    document.getElementById('pricingHotelSelect').disabled = true;
    
    // Load existing pricing data (you'll need to create an API endpoint for this)
    // For now, just show the modal
    new bootstrap.Modal(document.getElementById('addPricingModal')).show();
}

// Manage Room Types
function manageRoomTypes(hotelId) {
    window.location.href = `{{ route('admin.hotels.room-types') }}?hotel_id=${hotelId}`;
}

// Delete Pricing
function deletePricing(hotelId, hotelName) {
    document.getElementById('deleteHotelName').textContent = hotelName;
    document.getElementById('deletePricingForm').action = `{{ url('admin/hotels') }}/${hotelId}`;
    document.getElementById('deletePricingForm').onsubmit = function(e) {
        e.preventDefault();
        if (confirm('Are you sure you want to delete this hotel? This will also delete all associated pricing data.')) {
            this.submit();
        }
    };
    new bootstrap.Modal(document.getElementById('deletePricingModal')).show();
}

// Form submission
document.getElementById('pricingForm').addEventListener('submit', function(e) {
    e.preventDefault();
    const formData = new FormData(this);
    const method = formData.get('_method');
    const url = method === 'PUT' 
        ? `{{ url('admin/hotels') }}/${formData.get('hotel_id')}/room-pricing`
        : '{{ route("admin.hotels.room-pricing.store") }}';
    
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

// Select all checkbox
document.getElementById('selectAll').addEventListener('change', function() {
    document.querySelectorAll('.hotel-checkbox').forEach(cb => {
        cb.checked = this.checked;
    });
});
</script>
@endsection
