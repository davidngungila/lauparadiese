@extends('admin.layouts.app')

@section('title', 'Room Types Management - Lau Paradise Adventures')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">
                        <i class="ri-door-open-line me-2"></i>Room Types Management
                    </h4>
                    <div class="d-flex gap-2">
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addRoomTypeModal">
                            <i class="ri-add-line me-1"></i>Add Room Type
                        </button>
                        <a href="{{ route('admin.hotels.index') }}" class="btn btn-outline-secondary">
                            <i class="ri-arrow-left-line me-1"></i>Back to Hotels
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Hotel Filter -->
    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('admin.hotels.room-types') }}" class="row g-3">
                <div class="col-md-10">
                    <label class="form-label">Select Hotel</label>
                    <select name="hotel_id" class="form-select" onchange="this.form.submit()">
                        <option value="">All Hotels</option>
                        @foreach($hotels as $h)
                            <option value="{{ $h->id }}" {{ request('hotel_id') == $h->id ? 'selected' : '' }}>
                                {{ $h->name }} - {{ $h->city ?? 'N/A' }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2 d-flex align-items-end">
                    <a href="{{ route('admin.hotels.room-types') }}" class="btn btn-outline-secondary w-100">
                        <i class="ri-refresh-line me-1"></i>Reset
                    </a>
                </div>
            </form>
        </div>
    </div>

    @if($hotel)
    <!-- Selected Hotel Info -->
    <div class="card mb-4">
        <div class="card-body">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h5 class="mb-1">{{ $hotel->name }}</h5>
                    <p class="text-muted mb-0">
                        <i class="ri-map-pin-line me-1"></i>{{ $hotel->city ?? $hotel->address ?? 'N/A' }}, {{ $hotel->country ?? '' }}
                        @if($hotel->star_rating)
                            | <i class="ri-star-fill text-warning me-1"></i>{{ $hotel->star_rating }} Star
                        @endif
                    </p>
                </div>
                <div class="col-md-4 text-end">
                    <span class="badge bg-label-info">{{ $hotel->total_rooms ?? 0 }} Total Rooms</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Room Types Table -->
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">Room Types for {{ $hotel->name }}</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Room Type</th>
                            <th>Max Occupancy</th>
                            <th>Base Price/Night</th>
                            <th>Weekend Price</th>
                            <th>Total Rooms</th>
                            <th>Available</th>
                            <th>Status</th>
                            <th width="150">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($roomTypes as $roomType)
                        <tr>
                            <td>
                                <div>
                                    <strong>{{ $roomType->name }}</strong>
                                    @if($roomType->category)
                                        <br><small class="text-muted">{{ ucfirst($roomType->category) }}</small>
                                    @endif
                                </div>
                            </td>
                            <td>{{ $roomType->max_occupancy }} {{ Str::plural('Guest', $roomType->max_occupancy) }}</td>
                            <td>${{ number_format($roomType->base_price, 2) }}</td>
                            <td>
                                @if($roomType->weekend_price)
                                    ${{ number_format($roomType->weekend_price, 2) }}
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td>{{ $roomType->total_rooms }}</td>
                            <td>
                                <span class="badge bg-label-{{ $roomType->available_rooms > 0 ? 'success' : 'danger' }}">
                                    {{ $roomType->available_rooms }}
                                </span>
                            </td>
                            <td>
                                @if($roomType->is_active)
                                    <span class="badge bg-label-success">Active</span>
                                @else
                                    <span class="badge bg-label-secondary">Inactive</span>
                                @endif
                            </td>
                            <td>
                                <div class="d-flex gap-1">
                                    <button class="btn btn-sm btn-icon btn-outline-primary edit-room-type" 
                                            data-id="{{ $roomType->id }}"
                                            title="Edit">
                                        <i class="ri-edit-line"></i>
                                    </button>
                                    <button class="btn btn-sm btn-icon btn-outline-danger delete-room-type" 
                                            data-id="{{ $roomType->id }}"
                                            data-name="{{ $roomType->name }}"
                                            title="Delete">
                                        <i class="ri-delete-bin-line"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center py-5">
                                <div class="text-muted">
                                    <i class="ri-door-open-line ri-48px mb-3 d-block"></i>
                                    <p>No room types configured yet</p>
                                    <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addRoomTypeModal">
                                        <i class="ri-add-line me-1"></i>Add Room Type
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    @else
    <!-- No Hotel Selected -->
    <div class="card">
        <div class="card-body text-center py-5">
            <i class="ri-hotel-line ri-48px text-muted mb-3 d-block"></i>
            <p class="text-muted">Please select a hotel to manage room types</p>
        </div>
    </div>
    @endif
</div>

<!-- Add/Edit Room Type Modal -->
<div class="modal fade" id="addRoomTypeModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="roomTypeModalTitle">Add Room Type</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="roomTypeForm" method="POST">
                @csrf
                <input type="hidden" name="_method" id="roomTypeMethod" value="POST">
                <input type="hidden" name="hotel_id" id="roomTypeHotelId" value="{{ $hotel->id ?? '' }}">
                <div class="modal-body">
                    <div class="row g-3">
                        @if(!$hotel)
                        <div class="col-md-12">
                            <label class="form-label">Hotel <span class="text-danger">*</span></label>
                            <select name="hotel_id" id="roomTypeHotelSelect" class="form-select" required>
                                <option value="">Select Hotel</option>
                                @foreach($hotels as $h)
                                    <option value="{{ $h->id }}">{{ $h->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        @endif
                        <div class="col-md-6">
                            <label class="form-label">Room Type Name <span class="text-danger">*</span></label>
                            <input type="text" name="name" id="roomTypeName" class="form-control" required 
                                   placeholder="e.g., Deluxe Suite, Standard Double">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Room Category</label>
                            <select name="category" id="roomTypeCategory" class="form-select">
                                <option value="standard">Standard</option>
                                <option value="deluxe">Deluxe</option>
                                <option value="suite">Suite</option>
                                <option value="presidential">Presidential</option>
                                <option value="family">Family</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Max Occupancy <span class="text-danger">*</span></label>
                            <input type="number" name="max_occupancy" id="roomTypeMaxOccupancy" class="form-control" 
                                   min="1" max="10" value="2" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Total Rooms <span class="text-danger">*</span></label>
                            <input type="number" name="total_rooms" id="roomTypeTotalRooms" class="form-control" 
                                   min="1" value="1" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Available Rooms</label>
                            <input type="number" name="available_rooms" id="roomTypeAvailableRooms" class="form-control" 
                                   min="0" value="0">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Base Price/Night <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text">$</span>
                                <input type="number" name="base_price" id="roomTypeBasePrice" class="form-control" 
                                       step="0.01" min="0" required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Weekend Price</label>
                            <div class="input-group">
                                <span class="input-group-text">$</span>
                                <input type="number" name="weekend_price" id="roomTypeWeekendPrice" class="form-control" 
                                       step="0.01" min="0">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Holiday Price</label>
                            <div class="input-group">
                                <span class="input-group-text">$</span>
                                <input type="number" name="holiday_price" id="roomTypeHolidayPrice" class="form-control" 
                                       step="0.01" min="0">
                            </div>
                        </div>
                        <div class="col-md-12">
                            <label class="form-label">Room Size (sq ft)</label>
                            <input type="number" name="room_size" id="roomTypeSize" class="form-control" min="0">
                        </div>
                        <div class="col-md-12">
                            <label class="form-label">Bed Type</label>
                            <select name="bed_type" id="roomTypeBedType" class="form-select">
                                <option value="">Select Bed Type</option>
                                <option value="single">Single Bed</option>
                                <option value="double">Double Bed</option>
                                <option value="queen">Queen Bed</option>
                                <option value="king">King Bed</option>
                                <option value="twin">Twin Beds</option>
                                <option value="bunk">Bunk Beds</option>
                            </select>
                        </div>
                        <div class="col-md-12">
                            <label class="form-label">Amenities</label>
                            <div class="row g-2">
                                <div class="col-md-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="amenities[]" value="wifi" id="amenity_wifi">
                                        <label class="form-check-label" for="amenity_wifi">WiFi</label>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="amenities[]" value="tv" id="amenity_tv">
                                        <label class="form-check-label" for="amenity_tv">TV</label>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="amenities[]" value="ac" id="amenity_ac">
                                        <label class="form-check-label" for="amenity_ac">AC</label>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="amenities[]" value="balcony" id="amenity_balcony">
                                        <label class="form-check-label" for="amenity_balcony">Balcony</label>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="amenities[]" value="minibar" id="amenity_minibar">
                                        <label class="form-check-label" for="amenity_minibar">Minibar</label>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="amenities[]" value="safe" id="amenity_safe">
                                        <label class="form-check-label" for="amenity_safe">Safe</label>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="amenities[]" value="bathtub" id="amenity_bathtub">
                                        <label class="form-check-label" for="amenity_bathtub">Bathtub</label>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="amenities[]" value="ocean_view" id="amenity_ocean_view">
                                        <label class="form-check-label" for="amenity_ocean_view">Ocean View</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <label class="form-label">Description</label>
                            <textarea name="description" id="roomTypeDescription" class="form-control" rows="3"></textarea>
                        </div>
                        <div class="col-md-12">
                            <label class="form-label">Image URL</label>
                            <input type="url" name="image_url" id="roomTypeImageUrl" class="form-control" placeholder="https://example.com/image.jpg">
                        </div>
                        <div class="col-12">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" name="is_active" id="roomTypeIsActive" checked>
                                <label class="form-check-label" for="roomTypeIsActive">
                                    Active
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="ri-save-line me-1"></i>Save Room Type
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
let editingRoomTypeId = null;

// Form submission
document.getElementById('roomTypeForm').addEventListener('submit', function(e) {
    e.preventDefault();
    const formData = new FormData(this);
    const method = formData.get('_method');
    const url = editingRoomTypeId 
        ? '{{ url("admin/hotels/room-types") }}/' + editingRoomTypeId
        : '{{ route("admin.hotels.room-types.store") }}';
    
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
            alert(data.message || 'Room type saved successfully!');
            bootstrap.Modal.getInstance(document.getElementById('addRoomTypeModal')).hide();
            window.location.reload();
        } else {
            alert('Error: ' + (data.message || 'Failed to save room type'));
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred. Please try again.');
    });
});

// Edit Room Type
document.addEventListener('click', function(e) {
    if (e.target.closest('.edit-room-type')) {
        const button = e.target.closest('.edit-room-type');
        const roomTypeId = button.dataset.id;
        
        fetch('{{ url("admin/hotels/room-types") }}/' + roomTypeId)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const roomType = data.room_type;
                    editingRoomTypeId = roomType.id;
                    
                    // Update modal title
                    document.getElementById('roomTypeModalTitle').textContent = 'Edit Room Type';
                    document.getElementById('roomTypeMethod').value = 'PUT';
                    
                    // Fill form fields
                    document.getElementById('roomTypeName').value = roomType.name || '';
                    document.getElementById('roomTypeCategory').value = roomType.category || 'standard';
                    document.getElementById('roomTypeMaxOccupancy').value = roomType.max_occupancy || 2;
                    document.getElementById('roomTypeTotalRooms').value = roomType.total_rooms || 1;
                    document.getElementById('roomTypeAvailableRooms').value = roomType.available_rooms || 0;
                    document.getElementById('roomTypeBasePrice').value = roomType.base_price || '';
                    document.getElementById('roomTypeWeekendPrice').value = roomType.weekend_price || '';
                    document.getElementById('roomTypeHolidayPrice').value = roomType.holiday_price || '';
                    document.getElementById('roomTypeSize').value = roomType.room_size || '';
                    document.getElementById('roomTypeBedType').value = roomType.bed_type || '';
                    document.getElementById('roomTypeDescription').value = roomType.description || '';
                    
                    // Handle amenities checkboxes
                    const amenities = roomType.amenities || [];
                    document.querySelectorAll('input[name="amenities[]"]').forEach(checkbox => {
                        checkbox.checked = amenities.includes(checkbox.value);
                    });
                    
                    // Handle is_active
                    document.getElementById('roomTypeIsActive').checked = roomType.is_active !== false;
                    
                    // Show modal
                    new bootstrap.Modal(document.getElementById('addRoomTypeModal')).show();
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Failed to load room type details.');
            });
    }
    
    // Delete Room Type
    if (e.target.closest('.delete-room-type')) {
        const button = e.target.closest('.delete-room-type');
        const roomTypeId = button.dataset.id;
        const roomTypeName = button.dataset.name;
        
        if (confirm(`Are you sure you want to delete "${roomTypeName}"? This action cannot be undone.`)) {
            fetch('{{ url("admin/hotels/room-types") }}/' + roomTypeId, {
                method: 'DELETE',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert(data.message || 'Room type deleted successfully!');
                    window.location.reload();
                } else {
                    alert('Error: ' + (data.message || 'Failed to delete room type'));
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred. Please try again.');
            });
        }
    }
});

// Reset form when modal is closed
document.getElementById('addRoomTypeModal').addEventListener('hidden.bs.modal', function() {
    editingRoomTypeId = null;
    document.getElementById('roomTypeForm').reset();
    document.getElementById('roomTypeModalTitle').textContent = 'Add Room Type';
    document.getElementById('roomTypeMethod').value = 'POST';
    document.querySelectorAll('input[name="amenities[]"]').forEach(checkbox => {
        checkbox.checked = false;
    });
});
</script>
@endsection
