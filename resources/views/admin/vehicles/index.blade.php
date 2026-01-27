@extends('admin.layouts.app')

@section('title', 'Fleet List - Transport & Fleet Management')
@section('description', 'Manage all vehicles in the fleet')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">
                        <i class="ri-car-line me-2"></i>Fleet List
                    </h4>
                    <div class="d-flex gap-2">
                        <a href="{{ route('admin.vehicles.export-fleet') }}" class="btn btn-outline-primary">
                            <i class="ri-download-line me-1"></i>Export Fleet
                        </a>
                        <a href="{{ route('admin.vehicles.create') }}" class="btn btn-primary">
                            <i class="ri-add-line me-1"></i>Add Vehicle
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
                                <i class="ri-car-line"></i>
                            </span>
                        </div>
                        <div>
                            <h5 class="mb-0">{{ number_format($stats['total'] ?? 0) }}</h5>
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
                            <span class="avatar-initial rounded bg-label-success">
                                <i class="ri-checkbox-circle-line"></i>
                            </span>
                        </div>
                        <div>
                            <h5 class="mb-0">{{ number_format($stats['available'] ?? 0) }}</h5>
                            <small class="text-muted">Active</small>
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
                                <i class="ri-tools-line"></i>
                            </span>
                        </div>
                        <div>
                            <h5 class="mb-0">{{ number_format($stats['in_maintenance'] ?? 0) }}</h5>
                            <small class="text-muted">In Maintenance</small>
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
                            <span class="avatar-initial rounded bg-label-danger">
                                <i class="ri-close-circle-line"></i>
                            </span>
                        </div>
                        <div>
                            <h5 class="mb-0">{{ number_format($stats['not_available'] ?? 0) }}</h5>
                            <small class="text-muted">Not Available</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('admin.vehicles.index') }}">
                <div class="row g-3">
                    <div class="col-md-2">
                        <label class="form-label">Vehicle Type</label>
                        <select name="type" class="form-select">
                            <option value="">All Types</option>
                            <option value="Safari Jeep" {{ request('type') == 'Safari Jeep' ? 'selected' : '' }}>Safari Jeep</option>
                            <option value="Van" {{ request('type') == 'Van' ? 'selected' : '' }}>Van</option>
                            <option value="Minibus" {{ request('type') == 'Minibus' ? 'selected' : '' }}>Minibus</option>
                            <option value="Coaster Bus" {{ request('type') == 'Coaster Bus' ? 'selected' : '' }}>Coaster Bus</option>
                            <option value="Sedan" {{ request('type') == 'Sedan' ? 'selected' : '' }}>Sedan</option>
                            <option value="VIP SUV" {{ request('type') == 'VIP SUV' ? 'selected' : '' }}>VIP SUV</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Capacity</label>
                        <input type="number" name="capacity" class="form-control" placeholder="Min capacity" value="{{ request('capacity') }}" min="1">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Status</label>
                        <select name="status" class="form-select">
                            <option value="">All Status</option>
                            <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                            <option value="in_maintenance" {{ request('status') == 'in_maintenance' ? 'selected' : '' }}>In Maintenance</option>
                            <option value="not_available" {{ request('status') == 'not_available' ? 'selected' : '' }}>Not Available</option>
                            <option value="out_of_service" {{ request('status') == 'out_of_service' ? 'selected' : '' }}>Out of Service</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Assigned Driver</label>
                        <select name="driver_id" class="form-select">
                            <option value="">All Drivers</option>
                            @foreach($drivers ?? [] as $driver)
                                <option value="{{ $driver->id }}" {{ request('driver_id') == $driver->id ? 'selected' : '' }}>
                                    {{ $driver->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Search</label>
                        <input type="text" name="search" class="form-control" placeholder="Search by name, code, plate..." value="{{ request('search') }}">
                    </div>
                    <div class="col-md-1 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="ri-search-line"></i>
                        </button>
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
                            <th>Vehicle ID</th>
                            <th>Image</th>
                            <th>Vehicle Name</th>
                            <th>Type</th>
                            <th>Capacity</th>
                            <th>Registration No.</th>
                            <th>Status</th>
                            <th>Assigned Driver</th>
                            <th>Current Booking</th>
                            <th>Last Service Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($vehicles as $vehicle)
                        <tr>
                            <td>
                                <span class="badge bg-label-info">{{ $vehicle->vehicle_code ?? 'N/A' }}</span>
                            </td>
                            <td>
                                @if($vehicle->cover_image)
                                    <img src="{{ asset('storage/' . $vehicle->cover_image) }}" alt="{{ $vehicle->display_name }}" class="rounded" style="width: 50px; height: 50px; object-fit: cover;">
                                @else
                                    <div class="avatar avatar-sm">
                                        <span class="avatar-initial rounded bg-label-secondary">
                                            <i class="ri-car-line"></i>
                                        </span>
                                    </div>
                                @endif
                            </td>
                            <td>
                                <strong>{{ $vehicle->display_name }}</strong>
                            </td>
                            <td>
                                <span class="badge bg-label-info">{{ $vehicle->vehicle_type ?? 'N/A' }}</span>
                            </td>
                            <td>{{ $vehicle->capacity ?? 0 }} seats</td>
                            <td><strong>{{ $vehicle->license_plate ?? 'N/A' }}</strong></td>
                            <td>
                                @php
                                    $statusColors = [
                                        'active' => 'success',
                                        'in_maintenance' => 'warning',
                                        'not_available' => 'danger',
                                        'out_of_service' => 'secondary'
                                    ];
                                    $color = $statusColors[$vehicle->status] ?? 'secondary';
                                @endphp
                                <span class="badge bg-label-{{ $color }}">
                                    {{ ucfirst(str_replace('_', ' ', $vehicle->status ?? 'N/A')) }}
                                </span>
                            </td>
                            <td>
                                @if($vehicle->driver)
                                    <div class="d-flex align-items-center">
                                        <div class="avatar avatar-xs me-2">
                                            @if($vehicle->driver->avatar)
                                                <img src="{{ asset('storage/' . $vehicle->driver->avatar) }}" alt="{{ $vehicle->driver->name }}" class="rounded-circle">
                                            @else
                                                <span class="avatar-initial rounded-circle bg-label-primary">{{ substr($vehicle->driver->name, 0, 1) }}</span>
                                            @endif
                                        </div>
                                        <span>{{ $vehicle->driver->name }}</span>
                                    </div>
                                @else
                                    <span class="text-muted">Unassigned</span>
                                @endif
                            </td>
                            <td>
                                @if($vehicle->currentBooking)
                                    <a href="{{ route('admin.bookings.show', $vehicle->currentBooking->id) }}" class="text-primary">
                                        {{ $vehicle->currentBooking->booking_reference }}
                                    </a>
                                @else
                                    <span class="text-muted">None</span>
                                @endif
                            </td>
                            <td>
                                @if($vehicle->last_maintenance)
                                    {{ $vehicle->last_maintenance->format('M d, Y') }}
                                @else
                                    <span class="text-muted">N/A</span>
                                @endif
                            </td>
                            <td>
                                <div class="d-flex gap-2">
                                    <a href="{{ route('admin.vehicles.show', $vehicle->id) }}" class="btn btn-sm btn-icon btn-outline-primary" data-bs-toggle="tooltip" title="View">
                                        <i class="ri-eye-line"></i>
                                    </a>
                                    <a href="{{ route('admin.vehicles.edit', $vehicle->id) }}" class="btn btn-sm btn-icon btn-outline-info" data-bs-toggle="tooltip" title="Edit">
                                        <i class="ri-pencil-line"></i>
                                    </a>
                                    <a href="{{ route('admin.vehicles.show', $vehicle->id) }}#maintenance" class="btn btn-sm btn-icon btn-outline-warning" data-bs-toggle="tooltip" title="Maintenance Log">
                                        <i class="ri-tools-line"></i>
                                    </a>
                                    <a href="{{ route('admin.vehicles.show', $vehicle->id) }}#documents" class="btn btn-sm btn-icon btn-outline-secondary" data-bs-toggle="tooltip" title="Documents">
                                        <i class="ri-file-line"></i>
                                    </a>
                                    <button type="button" class="btn btn-sm btn-icon btn-outline-danger delete-vehicle" data-id="{{ $vehicle->id }}" data-name="{{ $vehicle->display_name }}" data-bs-toggle="modal" data-bs-target="#deleteVehicleModal" title="Delete">
                                        <i class="ri-delete-bin-line"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="11" class="text-center py-4">
                                <p class="text-muted mb-0">No vehicles found</p>
                                <a href="{{ route('admin.vehicles.create') }}" class="btn btn-primary btn-sm mt-2">
                                    <i class="ri-add-line me-1"></i>Add First Vehicle
                                </a>
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

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteVehicleModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Confirm Delete</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete vehicle <strong id="deleteVehicleName"></strong>?</p>
                <p class="text-danger mb-0"><small>This action cannot be undone.</small></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <form id="deleteVehicleForm" method="POST" style="display: inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Delete Vehicle</button>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
$(document).ready(function() {
    // Delete vehicle modal
    $('.delete-vehicle').on('click', function() {
        const vehicleId = $(this).data('id');
        const vehicleName = $(this).data('name');
        $('#deleteVehicleName').text(vehicleName);
        $('#deleteVehicleForm').attr('action', '{{ route("admin.vehicles.destroy", ":id") }}'.replace(':id', vehicleId));
    });
});
</script>
@endpush
@endsection
