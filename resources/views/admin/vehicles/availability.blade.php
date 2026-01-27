@extends('admin.layouts.app')

@section('title', 'Fleet Availability - Lau Paradise Adventures')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">
                        <i class="ri-calendar-check-line me-2"></i>Fleet Availability Management
                    </h4>
                    <a href="{{ route('admin.vehicles.index') }}" class="btn btn-outline-primary">
                        <i class="ri-arrow-left-line me-1"></i>All Vehicles
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
                            <span class="avatar-initial rounded bg-label-primary"><i class="ri-car-line"></i></span>
                        </div>
                        <div>
                            <h5 class="mb-0">{{ number_format(($stats ?? [])['total'] ?? 0) }}</h5>
                            <small class="text-muted">Total Vehicles</small>
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
        <div class="col-lg-3 col-md-6 mb-4">
            <div class="card card-border-shadow-warning">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="avatar me-3">
                            <span class="avatar-initial rounded bg-label-warning"><i class="ri-road-map-line"></i></span>
                        </div>
                        <div>
                            <h5 class="mb-0">{{ number_format(($stats ?? [])['in_use'] ?? 0) }}</h5>
                            <small class="text-muted">In Use</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 mb-4">
            <div class="card card-border-shadow-danger">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="avatar me-3">
                            <span class="avatar-initial rounded bg-label-danger"><i class="ri-tools-line"></i></span>
                        </div>
                        <div>
                            <h5 class="mb-0">{{ number_format(($stats ?? [])['maintenance'] ?? 0) }}</h5>
                            <small class="text-muted">Maintenance</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('admin.vehicles.availability') }}">
                <div class="row g-3">
                    <div class="col-md-2">
                        <label class="form-label">Status</label>
                        <select name="status" class="form-select">
                            <option value="">All Status</option>
                            <option value="available" {{ request('status') == 'available' ? 'selected' : '' }}>Available</option>
                            <option value="in_use" {{ request('status') == 'in_use' ? 'selected' : '' }}>In Use</option>
                            <option value="maintenance" {{ request('status') == 'maintenance' ? 'selected' : '' }}>Maintenance</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Vehicle Type</label>
                        <select name="type" class="form-select">
                            <option value="">All Types</option>
                            <option value="sedan" {{ request('type') == 'sedan' ? 'selected' : '' }}>Sedan</option>
                            <option value="suv" {{ request('type') == 'suv' ? 'selected' : '' }}>SUV</option>
                            <option value="van" {{ request('type') == 'van' ? 'selected' : '' }}>Van</option>
                            <option value="bus" {{ request('type') == 'bus' ? 'selected' : '' }}>Bus</option>
                            <option value="4x4" {{ request('type') == '4x4' ? 'selected' : '' }}>4x4</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Date From</label>
                        <input type="date" name="date_from" class="form-control" value="{{ request('date_from') }}">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Search</label>
                        <input type="text" name="search" class="form-control" placeholder="Make, Model, License Plate..." value="{{ request('search') }}">
                    </div>
                    <div class="col-md-2 d-flex align-items-end gap-2">
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="ri-search-line me-1"></i>Filter
                        </button>
                        <a href="{{ route('admin.vehicles.availability') }}" class="btn btn-label-secondary">
                            <i class="ri-refresh-line"></i>
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Vehicles Table -->
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Vehicle</th>
                            <th>Type</th>
                            <th>License Plate</th>
                            <th>Capacity</th>
                            <th>Assigned Driver</th>
                            <th>Status</th>
                            <th>Maintenance</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($vehicles as $vehicle)
                        <tr>
                            <td>
                                <div>
                                    <strong>{{ ($vehicle->make ?? '') . ' ' . ($vehicle->model ?? 'N/A') }}</strong>
                                    @if($vehicle->year)
                                        <br><small class="text-muted">{{ $vehicle->year }}</small>
                                    @endif
                                </div>
                            </td>
                            <td>
                                <span class="badge bg-label-info">{{ ucfirst($vehicle->vehicle_type ?? 'N/A') }}</span>
                            </td>
                            <td><strong>{{ $vehicle->license_plate ?? 'N/A' }}</strong></td>
                            <td>{{ $vehicle->capacity ?? 0 }} <small class="text-muted">seats</small></td>
                            <td>
                                @if($vehicle->driver)
                                    <div class="d-flex align-items-center">
                                        <div class="avatar avatar-xs me-2">
                                            <span class="avatar-initial rounded bg-label-primary">
                                                {{ strtoupper(substr($vehicle->driver->name, 0, 2)) }}
                                            </span>
                                        </div>
                                        <span>{{ $vehicle->driver->name }}</span>
                                    </div>
                                @else
                                    <span class="text-muted">Unassigned</span>
                                @endif
                            </td>
                            <td>
                                <span class="badge bg-label-{{ $vehicle->status == 'available' ? 'success' : ($vehicle->status == 'in_use' ? 'warning' : 'danger') }}">
                                    {{ ucfirst(str_replace('_', ' ', $vehicle->status ?? 'N/A')) }}
                                </span>
                            </td>
                            <td>
                                @if($vehicle->next_maintenance)
                                    <small class="text-muted">
                                        {{ $vehicle->next_maintenance->format('M d, Y') }}
                                        @if($vehicle->next_maintenance->isPast())
                                            <span class="badge bg-label-danger ms-1">Overdue</span>
                                        @elseif($vehicle->next_maintenance->isToday())
                                            <span class="badge bg-label-warning ms-1">Due Today</span>
                                        @endif
                                    </small>
                                @else
                                    <span class="text-muted">Not scheduled</span>
                                @endif
                            </td>
                            <td>
                                <div class="d-flex gap-1">
                                    <button class="btn btn-sm btn-icon btn-outline-primary view-vehicle" 
                                            data-id="{{ $vehicle->id }}"
                                            data-bs-toggle="modal" 
                                            data-bs-target="#viewVehicleModal"
                                            title="View Details">
                                        <i class="ri-eye-line"></i>
                                    </button>
                                    <button class="btn btn-sm btn-icon btn-outline-warning update-status" 
                                            data-id="{{ $vehicle->id }}"
                                            data-status="{{ $vehicle->status }}"
                                            data-bs-toggle="modal" 
                                            data-bs-target="#updateStatusModal"
                                            title="Update Status">
                                        <i class="ri-edit-line"></i>
                                    </button>
                                    <a href="{{ route('admin.vehicles.edit', $vehicle->id) }}" 
                                       class="btn btn-sm btn-icon btn-outline-info"
                                       title="Edit Vehicle">
                                        <i class="ri-pencil-line"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center py-4">
                                <p class="text-muted mb-0">No vehicles found</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-4">
                {{ $vehicles->links() }}
            </div>
        </div>
    </div>
</div>

<!-- View Vehicle Modal -->
<div class="modal fade" id="viewVehicleModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Vehicle Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="viewVehicleContent">
                <p class="text-center text-muted">Loading...</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary edit-vehicle-from-view">
                    <i class="ri-pencil-line me-1"></i>Edit Vehicle
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Update Status Modal -->
<div class="modal fade" id="updateStatusModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Update Vehicle Status</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="updateStatusForm" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <input type="hidden" name="vehicle_id" id="status-vehicle-id">
                    <div class="mb-3">
                        <label class="form-label">Status <span class="text-danger">*</span></label>
                        <select name="status" id="status-select" class="form-select" required>
                            <option value="available">Available</option>
                            <option value="in_use">In Use</option>
                            <option value="maintenance">Maintenance</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Notes</label>
                        <textarea name="notes" class="form-control" rows="3" placeholder="Optional notes about status change..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Update Status</button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
$(document).ready(function() {
    // View Vehicle
    $('.view-vehicle').on('click', function() {
        const vehicleId = $(this).data('id');
        $.get(`/admin/vehicles/${vehicleId}`, function(data) {
            // This would load the vehicle show page content
            $('#viewVehicleContent').html('<p>Loading vehicle details...</p>');
        }).fail(function() {
            $('#viewVehicleContent').html('<p class="text-danger">Error loading vehicle details</p>');
        });
    });
    
    // Update Status
    $('.update-status').on('click', function() {
        const vehicleId = $(this).data('id');
        const currentStatus = $(this).data('status');
        
        $('#status-vehicle-id').val(vehicleId);
        $('#status-select').val(currentStatus);
        $('#updateStatusForm').attr('action', `/admin/vehicles/${vehicleId}/status`);
    });
    
    // Edit from view
    $('.edit-vehicle-from-view').on('click', function() {
        const vehicleId = $('#viewVehicleModal').data('vehicle-id');
        if (vehicleId) {
            window.location.href = `/admin/vehicles/${vehicleId}/edit`;
        }
    });
    
    // Form submission
    $('#updateStatusForm').on('submit', function(e) {
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
                alert('Error updating status. Please try again.');
            }
        });
    });
});
</script>
@endpush
@endsection
