@extends('admin.layouts.app')

@section('title', 'Drivers & Guides - Lau Paradise Adventures')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">
                        <i class="ri-user-line me-2"></i>Drivers & Guides Management
                    </h4>
                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addDriverModal">
                        <i class="ri-add-line me-1"></i>Add Driver/Guide
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="row mb-4">
        <div class="col-lg-4 col-md-6 mb-4">
            <div class="card card-border-shadow-primary">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="avatar me-3">
                            <span class="avatar-initial rounded bg-label-primary"><i class="ri-user-line"></i></span>
                        </div>
                        <div>
                            <h5 class="mb-0">{{ number_format(($stats ?? [])['total'] ?? 0) }}</h5>
                            <small class="text-muted">Total Drivers/Guides</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-4 col-md-6 mb-4">
            <div class="card card-border-shadow-success">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="avatar me-3">
                            <span class="avatar-initial rounded bg-label-success"><i class="ri-check-line"></i></span>
                        </div>
                        <div>
                            <h5 class="mb-0">{{ number_format(($stats ?? [])['available'] ?? 0) }}</h5>
                            <small class="text-muted">Available</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-4 col-md-6 mb-4">
            <div class="card card-border-shadow-warning">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="avatar me-3">
                            <span class="avatar-initial rounded bg-label-warning"><i class="ri-car-line"></i></span>
                        </div>
                        <div>
                            <h5 class="mb-0">{{ number_format(($stats ?? [])['assigned'] ?? 0) }}</h5>
                            <small class="text-muted">Currently Assigned</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('admin.vehicles.drivers') }}">
                <div class="row g-3">
                    <div class="col-md-3">
                        <label class="form-label">Status</label>
                        <select name="status" class="form-select">
                            <option value="">All Status</option>
                            <option value="available" {{ request('status') == 'available' ? 'selected' : '' }}>Available</option>
                            <option value="assigned" {{ request('status') == 'assigned' ? 'selected' : '' }}>Assigned</option>
                        </select>
                    </div>
                    <div class="col-md-7">
                        <label class="form-label">Search</label>
                        <input type="text" name="search" class="form-control" placeholder="Search by name, email, or phone..." value="{{ request('search') }}">
                    </div>
                    <div class="col-md-2 d-flex align-items-end gap-2">
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="ri-search-line me-1"></i>Filter
                        </button>
                        <a href="{{ route('admin.vehicles.drivers') }}" class="btn btn-label-secondary">
                            <i class="ri-refresh-line"></i>
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Drivers Table -->
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Driver/Guide</th>
                            <th>Contact</th>
                            <th>Current Assignment</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($drivers as $driver)
                        @php
                            $assignedVehicles = \App\Models\Vehicle::where('driver_id', $driver->id)->where('status', 'in_use')->get();
                        @endphp
                        <tr>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="avatar me-3">
                                        <span class="avatar-initial rounded bg-label-primary">
                                            {{ strtoupper(substr($driver->name, 0, 2)) }}
                                        </span>
                                    </div>
                                    <div>
                                        <strong>{{ $driver->name }}</strong>
                                        <br>
                                        <small class="text-muted">Driver/Guide</small>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div>
                                    <i class="ri-mail-line me-1"></i>{{ $driver->email ?? 'N/A' }}<br>
                                    <i class="ri-phone-line me-1"></i>{{ $driver->phone ?? 'N/A' }}
                                </div>
                            </td>
                            <td>
                                @if($assignedVehicles->count() > 0)
                                    @foreach($assignedVehicles as $vehicle)
                                        <span class="badge bg-label-info mb-1 d-block">
                                            {{ $vehicle->make }} {{ $vehicle->model }} ({{ $vehicle->license_plate }})
                                        </span>
                                    @endforeach
                                @else
                                    <span class="text-muted">No active assignments</span>
                                @endif
                            </td>
                            <td>
                                @if($assignedVehicles->count() > 0)
                                    <span class="badge bg-label-warning">Assigned</span>
                                @else
                                    <span class="badge bg-label-success">Available</span>
                                @endif
                            </td>
                            <td>
                                <div class="d-flex gap-1">
                                    <button class="btn btn-sm btn-icon btn-outline-primary view-driver" 
                                            data-id="{{ $driver->id }}" 
                                            data-name="{{ $driver->name }}"
                                            data-email="{{ $driver->email }}"
                                            data-phone="{{ $driver->phone }}"
                                            data-bs-toggle="modal" 
                                            data-bs-target="#viewDriverModal"
                                            title="View Details">
                                        <i class="ri-eye-line"></i>
                                    </button>
                                    <button class="btn btn-sm btn-icon btn-outline-info edit-driver" 
                                            data-id="{{ $driver->id }}"
                                            data-bs-toggle="modal" 
                                            data-bs-target="#editDriverModal"
                                            title="Edit">
                                        <i class="ri-pencil-line"></i>
                                    </button>
                                    <button class="btn btn-sm btn-icon btn-outline-success assign-vehicle" 
                                            data-driver-id="{{ $driver->id }}"
                                            data-driver-name="{{ $driver->name }}"
                                            data-bs-toggle="modal" 
                                            data-bs-target="#assignVehicleModal"
                                            title="Assign Vehicle">
                                        <i class="ri-car-line"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center py-4">
                                <p class="text-muted mb-0">No drivers/guides found</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-4">
                {{ $drivers->links() }}
            </div>
        </div>
    </div>
</div>

<!-- View Driver Modal -->
<div class="modal fade" id="viewDriverModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Driver/Guide Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label text-muted">Full Name</label>
                        <p class="mb-0" id="view-driver-name"></p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label text-muted">Email</label>
                        <p class="mb-0" id="view-driver-email"></p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label text-muted">Phone</label>
                        <p class="mb-0" id="view-driver-phone"></p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label text-muted">Status</label>
                        <p class="mb-0">
                            <span class="badge bg-label-success" id="view-driver-status">Available</span>
                        </p>
                    </div>
                </div>
                <hr>
                <h6 class="mb-3">Current Vehicle Assignments</h6>
                <div id="view-driver-vehicles">
                    <p class="text-muted">Loading...</p>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary edit-driver-from-view">
                    <i class="ri-pencil-line me-1"></i>Edit Driver
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Edit Driver Modal -->
<div class="modal fade" id="editDriverModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Driver/Guide</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="editDriverForm" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <input type="hidden" name="driver_id" id="edit-driver-id">
                    <div class="mb-3">
                        <label class="form-label">Full Name <span class="text-danger">*</span></label>
                        <input type="text" name="name" id="edit-driver-name" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Email <span class="text-danger">*</span></label>
                        <input type="email" name="email" id="edit-driver-email" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Phone</label>
                        <input type="text" name="phone" id="edit-driver-phone" class="form-control">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Update Driver</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Assign Vehicle Modal -->
<div class="modal fade" id="assignVehicleModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Assign Vehicle to Driver</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="assignVehicleForm" method="POST" action="{{ route('admin.vehicles.assign-vehicle') }}">
                @csrf
                <div class="modal-body">
                    <input type="hidden" name="driver_id" id="assign-driver-id">
                    <div class="mb-3">
                        <label class="form-label">Driver/Guide</label>
                        <input type="text" id="assign-driver-name" class="form-control" readonly>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Select Vehicle <span class="text-danger">*</span></label>
                        <select name="vehicle_id" id="assign-vehicle-id" class="form-select" required>
                            <option value="">Choose a vehicle...</option>
                            @foreach(\App\Models\Vehicle::where('status', 'available')->orWhere('status', 'in_use')->orderBy('model')->get() as $vehicle)
                                <option value="{{ $vehicle->id }}" data-status="{{ $vehicle->status }}">
                                    {{ $vehicle->make }} {{ $vehicle->model }} ({{ $vehicle->license_plate }}) 
                                    - {{ ucfirst(str_replace('_', ' ', $vehicle->status)) }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Notes</label>
                        <textarea name="notes" class="form-control" rows="3" placeholder="Optional notes about this assignment..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Assign Vehicle</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Add Driver Modal -->
<div class="modal fade" id="addDriverModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add New Driver/Guide</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="addDriverForm" method="POST" action="{{ route('admin.users.store') }}">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Full Name <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Email <span class="text-danger">*</span></label>
                        <input type="email" name="email" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Phone</label>
                        <input type="text" name="phone" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Password <span class="text-danger">*</span></label>
                        <input type="password" name="password" class="form-control" required>
                    </div>
                    <input type="hidden" name="role" value="Driver/Guide">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Add Driver/Guide</button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
$(document).ready(function() {
    // View Driver
    $('.view-driver').on('click', function() {
        const driverId = $(this).data('id');
        const driverName = $(this).data('name');
        const driverEmail = $(this).data('email');
        const driverPhone = $(this).data('phone');
        
        $('#view-driver-name').text(driverName);
        $('#view-driver-email').text(driverEmail || 'N/A');
        $('#view-driver-phone').text(driverPhone || 'N/A');
        
        // Load vehicles
        $.get(`/admin/vehicles/drivers/${driverId}/vehicles`, function(data) {
            if (data.vehicles && data.vehicles.length > 0) {
                let html = '<ul class="list-unstyled mb-0">';
                data.vehicles.forEach(function(vehicle) {
                    html += `<li class="mb-2">
                        <span class="badge bg-label-info">${vehicle.make} ${vehicle.model} (${vehicle.license_plate})</span>
                        <small class="text-muted d-block">Status: ${vehicle.status}</small>
                    </li>`;
                });
                html += '</ul>';
                $('#view-driver-vehicles').html(html);
            } else {
                $('#view-driver-vehicles').html('<p class="text-muted mb-0">No vehicle assignments</p>');
            }
        }).fail(function() {
            $('#view-driver-vehicles').html('<p class="text-muted mb-0">Unable to load vehicle assignments</p>');
        });
        
        // Store driver ID for edit
        $('#viewDriverModal').data('driver-id', driverId);
    });
    
    // Edit Driver
    $('.edit-driver, .edit-driver-from-view').on('click', function() {
        const driverId = $(this).closest('.modal').data('driver-id') || $(this).data('id');
        const driver = $('.view-driver[data-id="' + driverId + '"]');
        
        $('#edit-driver-id').val(driverId);
        $('#edit-driver-name').val(driver.data('name'));
        $('#edit-driver-email').val(driver.data('email'));
        $('#edit-driver-phone').val(driver.data('phone'));
        $('#editDriverForm').attr('action', `/admin/users/${driverId}`);
        
        $('#viewDriverModal').modal('hide');
        $('#editDriverModal').modal('show');
    });
    
    // Assign Vehicle
    $('.assign-vehicle').on('click', function() {
        const driverId = $(this).data('driver-id');
        const driverName = $(this).data('driver-name');
        
        $('#assign-driver-id').val(driverId);
        $('#assign-driver-name').val(driverName);
    });
    
    // Form submissions
    $('#editDriverForm').on('submit', function(e) {
        e.preventDefault();
        const form = $(this);
        $.ajax({
            url: form.attr('action'),
            method: 'POST',
            data: form.serialize(),
            success: function(response) {
                location.reload();
            },
            error: function(xhr) {
                alert('Error updating driver. Please try again.');
            }
        });
    });
    
    $('#assignVehicleForm').on('submit', function(e) {
        e.preventDefault();
        const form = $(this);
        $.ajax({
            url: form.attr('action'),
            method: 'POST',
            data: form.serialize(),
            success: function(response) {
                location.reload();
            },
            error: function(xhr) {
                alert('Error assigning vehicle. Please try again.');
            }
        });
    });
});
</script>
@endpush
@endsection
