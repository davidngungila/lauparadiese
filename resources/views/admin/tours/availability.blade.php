@extends('admin.layouts.app')

@section('title', 'Tour Availability - Lau Paradise Adventures')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">
                        <i class="ri-calendar-check-line me-2"></i>Tour Availability Management
                    </h4>
                    <div class="d-flex gap-2">
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addAvailabilityModal">
                            <i class="ri-add-line me-1"></i>Set Availability
                        </button>
                        <a href="{{ route('admin.tours.index') }}" class="btn btn-outline-secondary">
                            <i class="ri-arrow-left-line me-1"></i>Back to Tours
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('admin.tours.availability') }}" class="row g-3">
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
                    <label class="form-label">Date From</label>
                    <input type="date" name="date_from" class="form-control" value="{{ request('date_from') }}">
                </div>
                <div class="col-md-2 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="ri-search-line me-1"></i>Filter
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Tours Availability Table -->
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">Tour Availability Calendar</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Tour Name</th>
                            <th>Destination</th>
                            <th>Duration</th>
                            <th>Next Available</th>
                            <th>Availability Status</th>
                            <th>Booked Slots</th>
                            <th>Available Slots</th>
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
                                <span class="text-muted">Not Set</span>
                            </td>
                            <td>
                                <span class="badge bg-label-success">Available</span>
                            </td>
                            <td>
                                <span class="badge bg-label-warning">0</span>
                            </td>
                            <td>
                                <span class="badge bg-label-primary">Unlimited</span>
                            </td>
                            <td>
                                <div class="d-flex gap-1">
                                    <button type="button" class="btn btn-sm btn-icon btn-outline-primary" 
                                            onclick="viewAvailability({{ $tour->id }})" title="View Calendar">
                                        <i class="ri-calendar-line"></i>
                                    </button>
                                    <button type="button" class="btn btn-sm btn-icon btn-outline-warning" 
                                            onclick="editAvailability({{ $tour->id }})" title="Edit Availability">
                                        <i class="ri-edit-line"></i>
                                    </button>
                                    <button type="button" class="btn btn-sm btn-icon btn-outline-info" 
                                            onclick="viewDetails({{ $tour->id }})" title="View Details">
                                        <i class="ri-eye-line"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center py-5">
                                <div class="text-muted">
                                    <i class="ri-calendar-check-line ri-48px mb-3 d-block"></i>
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

<!-- Add/Edit Availability Modal -->
<div class="modal fade" id="addAvailabilityModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="availabilityModalTitle">Set Tour Availability</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="availabilityForm" method="POST">
                @csrf
                <input type="hidden" name="_method" id="availabilityMethod" value="POST">
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Tour <span class="text-danger">*</span></label>
                            <select name="tour_id" id="availabilityTourId" class="form-select" required>
                                <option value="">Select Tour</option>
                                @foreach($allTours as $tour)
                                    <option value="{{ $tour->id }}">{{ $tour->name }} - {{ $tour->duration_days ?? 0 }} Days</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Availability Type <span class="text-danger">*</span></label>
                            <select name="availability_type" id="availabilityType" class="form-select" required>
                                <option value="specific_dates">Specific Dates</option>
                                <option value="recurring">Recurring (Weekly/Monthly)</option>
                                <option value="year_round">Year Round</option>
                            </select>
                        </div>
                        <div class="col-md-6" id="startDateGroup">
                            <label class="form-label">Start Date <span class="text-danger">*</span></label>
                            <input type="date" name="start_date" id="availabilityStartDate" class="form-control" required>
                        </div>
                        <div class="col-md-6" id="endDateGroup">
                            <label class="form-label">End Date</label>
                            <input type="date" name="end_date" id="availabilityEndDate" class="form-control">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Max Participants</label>
                            <input type="number" name="max_participants" id="availabilityMaxParticipants" class="form-control" 
                                   min="1" value="20">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Min Participants</label>
                            <input type="number" name="min_participants" id="availabilityMinParticipants" class="form-control" 
                                   min="1" value="2">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Available Slots</label>
                            <input type="number" name="available_slots" id="availabilitySlots" class="form-control" 
                                   min="0" value="20">
                        </div>
                        <div class="col-12">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" name="is_active" id="availabilityIsActive" checked>
                                <label class="form-check-label" for="availabilityIsActive">
                                    Active Availability
                                </label>
                            </div>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Notes</label>
                            <textarea name="notes" id="availabilityNotes" class="form-control" rows="3" 
                                      placeholder="Additional notes about availability..."></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="ri-save-line me-1"></i>Save Availability
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- View Availability Calendar Modal -->
<div class="modal fade" id="viewAvailabilityModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tour Availability Calendar</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="availabilityCalendarContent">
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

<!-- View Tour Details Modal -->
<div class="modal fade" id="viewDetailsModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tour Details</h5>
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
function viewAvailability(tourId) {
    const content = document.getElementById('availabilityCalendarContent');
    content.innerHTML = `
        <div class="text-center py-4">
            <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
        </div>
    `;
    const url = `{{ route('admin.tours.availability.calendar', ':id') }}`.replace(':id', tourId);
    fetch(url)
        .then(response => response.text())
        .then(html => {
            content.innerHTML = html;
            new bootstrap.Modal(document.getElementById('viewAvailabilityModal')).show();
        })
        .catch(error => {
            console.error(error);
            content.innerHTML = `
                <div class="text-center py-4 text-danger">
                    <p>Failed to load availability calendar.</p>
                </div>
            `;
            new bootstrap.Modal(document.getElementById('viewAvailabilityModal')).show();
        });
}

function editAvailability(tourId) {
    document.getElementById('availabilityModalTitle').textContent = 'Edit Tour Availability';
    document.getElementById('availabilityMethod').value = 'PUT';
    document.getElementById('availabilityTourId').value = tourId;
    document.getElementById('availabilityTourId').disabled = true;
    document.getElementById('availabilityForm').action = `{{ route('admin.tours.availability.update', ':tourId') }}`.replace(':tourId', tourId);
    new bootstrap.Modal(document.getElementById('addAvailabilityModal')).show();
}

function viewDetails(tourId) {
    const content = document.getElementById('tourDetailsContent');
    content.innerHTML = `
        <div class="text-center py-4">
            <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
        </div>
    `;
    const url = `{{ route('admin.tours.details.partial', ':id') }}`.replace(':id', tourId);
    fetch(url)
        .then(response => response.text())
        .then(html => {
            content.innerHTML = html;
            new bootstrap.Modal(document.getElementById('viewDetailsModal')).show();
        })
        .catch(error => {
            console.error(error);
            content.innerHTML = `
                <div class="text-center py-4 text-danger">
                    <p>Failed to load tour details.</p>
                </div>
            `;
            new bootstrap.Modal(document.getElementById('viewDetailsModal')).show();
        });
}

// Form submission
document.getElementById('availabilityForm').addEventListener('submit', function(e) {
    e.preventDefault();
    const formData = new FormData(this);
    const method = formData.get('_method');
    const tourId = formData.get('tour_id');
    const url = this.action || (method === 'PUT' 
        ? `{{ route('admin.tours.availability.update', ':tourId') }}`.replace(':tourId', tourId)
        : '{{ route("admin.tours.availability.store") }}');
    
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
            alert('Error: ' + (data.message || 'Failed to save availability'));
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred. Please try again.');
    });
});
</script>
@endsection
