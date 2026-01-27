@extends('admin.layouts.app')

@section('title', 'Assign Driver to Trip - Lau Paradise Adventures')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">
                        <i class="ri-user-settings-line me-2"></i>Assign Driver & Vehicle to Trip
                    </h4>
                    <a href="{{ route('admin.vehicles.index') }}" class="btn btn-outline-primary">
                        <i class="ri-arrow-left-line me-1"></i>All Vehicles
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Booking Selection -->
        <div class="col-lg-4 mb-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="ri-calendar-line me-2"></i>Select Booking</h5>
                </div>
                <div class="card-body">
                    <form method="GET" action="{{ route('admin.vehicles.assign-driver') }}">
                        <div class="mb-3">
                            <label class="form-label">Search Booking</label>
                            <input type="text" name="search" class="form-control" placeholder="Booking reference, tour name..." value="{{ request('search') }}">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Booking <span class="text-danger">*</span></label>
                            <select name="booking_id" class="form-select" onchange="this.form.submit()">
                                <option value="">Select a booking...</option>
                                @foreach($bookings as $b)
                                    <option value="{{ $b->id }}" {{ $booking && $booking->id == $b->id ? 'selected' : '' }}>
                                        {{ $b->booking_reference }} - {{ $b->tour->name ?? 'N/A' }}
                                        ({{ $b->departure_date ? $b->departure_date->format('M d, Y') : 'N/A' }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="ri-search-line me-1"></i>Search
                        </button>
                    </form>
                </div>
            </div>
            
            @if($booking)
            <!-- Booking Summary Card -->
            <div class="card mt-3">
                <div class="card-header">
                    <h5 class="mb-0"><i class="ri-file-text-line me-2"></i>Booking Summary</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label text-muted small">Booking Reference</label>
                        <p class="mb-0"><strong>{{ $booking->booking_reference }}</strong></p>
                    </div>
                    <div class="mb-3">
                        <label class="form-label text-muted small">Tour</label>
                        <p class="mb-0">{{ $booking->tour->name ?? 'N/A' }}</p>
                    </div>
                    <div class="mb-3">
                        <label class="form-label text-muted small">Departure Date</label>
                        <p class="mb-0">{{ $booking->departure_date ? $booking->departure_date->format('F d, Y') : 'N/A' }}</p>
                    </div>
                    <div class="mb-3">
                        <label class="form-label text-muted small">Travelers</label>
                        <p class="mb-0"><strong>{{ $booking->travelers ?? 0 }}</strong> passengers</p>
                    </div>
                    <div class="mb-0">
                        <label class="form-label text-muted small">Customer</label>
                        <p class="mb-0">{{ $booking->customer_name ?? 'N/A' }}</p>
                        <small class="text-muted">{{ $booking->customer_email ?? '' }}</small>
                    </div>
                </div>
            </div>
            @endif
        </div>
        
        <!-- Main Content Area -->
        <div class="col-lg-8">
            @if($booking)
                <!-- Tabs Navigation -->
                <ul class="nav nav-tabs mb-3" role="tablist">
                    <li class="nav-item">
                        <button type="button" class="nav-link active" id="assign-tab" data-bs-toggle="tab" data-bs-target="#assign" role="tab">
                            <i class="ri-user-add-line me-1"></i>New Assignment
                        </button>
                    </li>
                    <li class="nav-item">
                        <button type="button" class="nav-link" id="operations-tab" data-bs-toggle="tab" data-bs-target="#operations" role="tab">
                            <i class="ri-list-check me-1"></i>Operations
                            @if(isset($operations) && $operations->count() > 0)
                                <span class="badge bg-primary ms-1">{{ $operations->count() }}</span>
                            @endif
                        </button>
                    </li>
                </ul>

                <!-- Tab Content -->
                <div class="tab-content">
                    <!-- Assignment Form Tab -->
                    <div class="tab-pane fade show active" id="assign" role="tabpanel">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="mb-0"><i class="ri-user-settings-line me-2"></i>Assignment Details</h5>
                            </div>
                            <div class="card-body">
                                <form id="assignDriverForm" method="POST" action="{{ route('admin.vehicles.assign-driver.store') }}">
                                    @csrf
                                    <input type="hidden" name="booking_id" value="{{ $booking->id }}">
                                    
                                    <div class="row g-3">
                                        <div class="col-md-6">
                                            <label class="form-label">Select Driver <span class="text-danger">*</span></label>
                                            <select name="driver_id" id="driver-select" class="form-select" required>
                                                <option value="">Choose a driver...</option>
                                                @foreach($drivers as $driver)
                                                    @php
                                                        $assignedVehicles = \App\Models\Vehicle::where('driver_id', $driver->id)->where('status', 'in_use')->count();
                                                    @endphp
                                                    <option value="{{ $driver->id }}" 
                                                            data-available="{{ $assignedVehicles == 0 ? 'yes' : 'no' }}"
                                                            data-vehicles="{{ $assignedVehicles }}">
                                                        {{ $driver->name }}
                                                        @if($assignedVehicles > 0)
                                                            (Currently assigned to {{ $assignedVehicles }} vehicle(s))
                                                        @else
                                                            (Available)
                                                        @endif
                                                    </option>
                                                @endforeach
                                            </select>
                                            <small class="text-muted">Select an available driver for this trip</small>
                                        </div>
                                        
                                        <div class="col-md-6">
                                            <label class="form-label">Select Vehicle <span class="text-danger">*</span></label>
                                            <select name="vehicle_id" id="vehicle-select" class="form-select" required>
                                                <option value="">Choose a vehicle...</option>
                                                @foreach($vehicles as $vehicle)
                                                    <option value="{{ $vehicle->id }}" 
                                                            data-capacity="{{ $vehicle->capacity ?? 0 }}"
                                                            data-status="{{ $vehicle->status }}">
                                                        {{ $vehicle->make ?? '' }} {{ $vehicle->model ?? 'N/A' }} 
                                                        ({{ $vehicle->license_plate ?? 'N/A' }})
                                                        - Capacity: {{ $vehicle->capacity ?? 0 }} seats
                                                        - {{ ucfirst(str_replace('_', ' ', $vehicle->status)) }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            <small class="text-muted">Ensure vehicle capacity matches traveler count</small>
                                        </div>
                                        
                                        <div class="col-md-6">
                                            <label class="form-label">Operation Date</label>
                                            <input type="date" name="operation_date" class="form-control" value="{{ $booking->departure_date ? $booking->departure_date->format('Y-m-d') : '' }}">
                                            <small class="text-muted">Default: Booking departure date</small>
                                        </div>
                                        
                                        <div class="col-12">
                                            <div class="alert alert-info d-flex align-items-center" role="alert">
                                                <i class="ri-information-line me-2"></i>
                                                <div>
                                                    <strong>Travelers:</strong> {{ $booking->travelers ?? 0 }} passengers
                                                    <span id="capacity-warning" class="text-danger d-none ms-2">
                                                        <i class="ri-alert-line"></i> Vehicle capacity may be insufficient
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="col-12">
                                            <label class="form-label">Assignment Notes</label>
                                            <textarea name="notes" class="form-control" rows="4" placeholder="Special instructions, pickup location, contact information, etc..."></textarea>
                                        </div>
                                        
                                        <div class="col-12">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" id="notifyDriver" name="notify_driver" value="1" checked>
                                                <label class="form-check-label" for="notifyDriver">
                                                    Notify driver via email about this assignment
                                                </label>
                                            </div>
                                        </div>
                                        
                                        <div class="col-12">
                                            <button type="submit" class="btn btn-primary">
                                                <i class="ri-check-line me-1"></i>Assign Driver & Vehicle
                                            </button>
                                            <a href="{{ route('admin.vehicles.assign-driver') }}" class="btn btn-label-secondary">
                                                <i class="ri-close-line me-1"></i>Cancel
                                            </a>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    <!-- Operations Management Tab -->
                    <div class="tab-pane fade" id="operations" role="tabpanel">
                        <div class="card">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <h5 class="mb-0"><i class="ri-list-check me-2"></i>Tour Operations</h5>
                                <span class="badge bg-label-primary">{{ isset($operations) ? $operations->count() : 0 }} Operation(s)</span>
                            </div>
                            <div class="card-body">
                                @if(isset($operations) && $operations->count() > 0)
                                    <div class="table-responsive">
                                        <table class="table table-hover">
                                            <thead>
                                                <tr>
                                                    <th>Date</th>
                                                    <th>Driver</th>
                                                    <th>Vehicle</th>
                                                    <th>Status</th>
                                                    <th>Actions</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($operations as $operation)
                                                    <tr>
                                                        <td>
                                                            <strong>{{ $operation->operation_date ? $operation->operation_date->format('M d, Y') : 'N/A' }}</strong>
                                                        </td>
                                                        <td>
                                                            @if($operation->driver)
                                                                <div class="d-flex align-items-center">
                                                                    <div class="avatar avatar-sm me-2">
                                                                        <span class="avatar-initial rounded-circle bg-label-primary">
                                                                            {{ substr($operation->driver->name, 0, 1) }}
                                                                        </span>
                                                                    </div>
                                                                    <div>
                                                                        <strong>{{ $operation->driver->name }}</strong>
                                                                        @if($operation->driver->phone)
                                                                            <br><small class="text-muted">{{ $operation->driver->phone }}</small>
                                                                        @endif
                                                                    </div>
                                                                </div>
                                                            @else
                                                                <span class="text-muted">Not assigned</span>
                                                            @endif
                                                        </td>
                                                        <td>
                                                            @if($operation->vehicle)
                                                                <div>
                                                                    <strong>{{ $operation->vehicle->make }} {{ $operation->vehicle->model }}</strong>
                                                                    <br><small class="text-muted">{{ $operation->vehicle->license_plate }}</small>
                                                                    <br><small class="text-info">Capacity: {{ $operation->vehicle->capacity }} seats</small>
                                                                </div>
                                                            @else
                                                                <span class="text-muted">Not assigned</span>
                                                            @endif
                                                        </td>
                                                        <td>
                                                            @php
                                                                $statusColors = [
                                                                    'scheduled' => 'info',
                                                                    'in_progress' => 'warning',
                                                                    'completed' => 'success',
                                                                    'cancelled' => 'danger'
                                                                ];
                                                                $color = $statusColors[$operation->status] ?? 'secondary';
                                                            @endphp
                                                            <span class="badge bg-label-{{ $color }}">{{ ucfirst(str_replace('_', ' ', $operation->status)) }}</span>
                                                        </td>
                                                        <td>
                                                            <div class="d-flex gap-2">
                                                                <button type="button" class="btn btn-sm btn-label-info" onclick="viewOperation({{ $operation->id }})" title="View Details">
                                                                    <i class="ri-eye-line"></i>
                                                                </button>
                                                                <button type="button" class="btn btn-sm btn-label-primary" onclick="editOperation({{ $operation->id }})" title="Edit">
                                                                    <i class="ri-edit-line"></i>
                                                                </button>
                                                                <button type="button" class="btn btn-sm btn-label-danger" onclick="deleteOperation({{ $operation->id }})" title="Delete">
                                                                    <i class="ri-delete-bin-line"></i>
                                                                </button>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                @else
                                    <div class="text-center py-5">
                                        <div class="avatar avatar-xl mx-auto mb-3">
                                            <span class="avatar-initial rounded bg-label-secondary">
                                                <i class="ri-list-check" style="font-size: 48px;"></i>
                                            </span>
                                        </div>
                                        <h5 class="mb-2">No Operations Found</h5>
                                        <p class="text-muted">No operations have been created for this booking yet. Create a new assignment above.</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @else
                <div class="card">
                    <div class="card-body">
                        <div class="text-center py-5">
                            <div class="avatar avatar-xl mx-auto mb-3">
                                <span class="avatar-initial rounded bg-label-secondary">
                                    <i class="ri-user-settings-line" style="font-size: 48px;"></i>
                                </span>
                            </div>
                            <h5 class="mb-2">No Booking Selected</h5>
                            <p class="text-muted">Please select a booking from the list to assign a driver and vehicle</p>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>

<!-- View Operation Details Modal -->
<div class="modal fade" id="viewOperationModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="ri-eye-line me-2"></i>Operation Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="operationDetailsContent">
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

<!-- Edit Operation Modal -->
<div class="modal fade" id="editOperationModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="ri-edit-line me-2"></i>Edit Operation</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="editOperationForm">
                @csrf
                @method('PUT')
                <div class="modal-body" id="editOperationContent">
                    <div class="text-center py-4">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="ri-save-line me-1"></i>Update Operation
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteOperationModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="ri-delete-bin-line me-2"></i>Confirm Delete</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete this operation? This action cannot be undone.</p>
                <div class="alert alert-warning">
                    <i class="ri-alert-line me-2"></i>
                    <strong>Warning:</strong> This will release the assigned vehicle and driver.
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">Cancel</button>
                <form id="deleteOperationForm" method="POST" style="display: inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">
                        <i class="ri-delete-bin-line me-1"></i>Delete Operation
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
$(document).ready(function() {
    // Capacity check
    $('#vehicle-select').on('change', function() {
        const selectedOption = $(this).find('option:selected');
        const capacity = parseInt(selectedOption.data('capacity')) || 0;
        const travelers = {{ $booking->travelers ?? 0 }};
        
        if (capacity > 0 && travelers > capacity) {
            $('#capacity-warning').removeClass('d-none');
        } else {
            $('#capacity-warning').addClass('d-none');
        }
    });
    
    // Form submission
    $('#assignDriverForm').on('submit', function(e) {
        e.preventDefault();
        const form = $(this);
        const submitBtn = form.find('button[type="submit"]');
        
        submitBtn.prop('disabled', true).html('<i class="ri-loader-4-line ri-spin me-1"></i>Assigning...');
        
        $.ajax({
            url: form.attr('action'),
            method: 'POST',
            data: form.serialize(),
            success: function(response) {
                if (response.success) {
                    window.location.href = response.redirect || '{{ route("admin.vehicles.assign-driver", ["booking_id" => $booking->id ?? ""]) }}';
                } else {
                    alert(response.message || 'Assignment completed');
                    location.reload();
                }
            },
            error: function(xhr) {
                let errorMsg = 'Error assigning driver and vehicle. Please try again.';
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    errorMsg = xhr.responseJSON.message;
                } else if (xhr.responseJSON && xhr.responseJSON.errors) {
                    const errors = Object.values(xhr.responseJSON.errors).flat();
                    errorMsg = errors.join('\n');
                }
                alert(errorMsg);
                submitBtn.prop('disabled', false).html('<i class="ri-check-line me-1"></i>Assign Driver & Vehicle');
            }
        });
    });
});

// View Operation Details
function viewOperation(id) {
    const modal = new bootstrap.Modal(document.getElementById('viewOperationModal'));
    const content = $('#operationDetailsContent');
    
    content.html('<div class="text-center py-4"><div class="spinner-border text-primary" role="status"><span class="visually-hidden">Loading...</span></div></div>');
    modal.show();
    
    $.ajax({
        url: '{{ route("admin.vehicles.operations.details", ":id") }}'.replace(':id', id),
        method: 'GET',
        success: function(response) {
            if (response.success && response.operation) {
                const op = response.operation;
                const statusColors = {
                    'scheduled': 'info',
                    'in_progress': 'warning',
                    'completed': 'success',
                    'cancelled': 'danger'
                };
                const statusColor = statusColors[op.status] || 'secondary';
                
                let html = `
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label text-muted small">Operation Date</label>
                            <p class="mb-0"><strong>${op.operation_date ? new Date(op.operation_date).toLocaleDateString() : 'N/A'}</strong></p>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-muted small">Status</label>
                            <p class="mb-0"><span class="badge bg-label-${statusColor}">${op.status.replace('_', ' ').replace(/\b\w/g, l => l.toUpperCase())}</span></p>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-muted small">Driver</label>
                            <p class="mb-0">${op.driver ? op.driver.name : '<span class="text-muted">Not assigned</span>'}</p>
                            ${op.driver && op.driver.phone ? `<small class="text-muted">${op.driver.phone}</small>` : ''}
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-muted small">Vehicle</label>
                            <p class="mb-0">${op.vehicle ? `${op.vehicle.make} ${op.vehicle.model} (${op.vehicle.license_plate})` : '<span class="text-muted">Not assigned</span>'}</p>
                            ${op.vehicle ? `<small class="text-info">Capacity: ${op.vehicle.capacity} seats</small>` : ''}
                        </div>
                        ${op.guide ? `
                        <div class="col-md-6">
                            <label class="form-label text-muted small">Guide</label>
                            <p class="mb-0">${op.guide.name}</p>
                        </div>
                        ` : ''}
                        <div class="col-12">
                            <label class="form-label text-muted small">Notes</label>
                            <p class="mb-0">${op.notes || '<span class="text-muted">No notes</span>'}</p>
                        </div>
                        ${op.daily_log ? `
                        <div class="col-12">
                            <label class="form-label text-muted small">Daily Log</label>
                            <p class="mb-0">${op.daily_log}</p>
                        </div>
                        ` : ''}
                    </div>
                `;
                content.html(html);
            }
        },
        error: function() {
            content.html('<div class="alert alert-danger">Error loading operation details.</div>');
        }
    });
}

// Edit Operation
function editOperation(id) {
    const modal = new bootstrap.Modal(document.getElementById('editOperationModal'));
    const content = $('#editOperationContent');
    const form = $('#editOperationForm');
    
    content.html('<div class="text-center py-4"><div class="spinner-border text-primary" role="status"><span class="visually-hidden">Loading...</span></div></div>');
    modal.show();
    
    $.ajax({
        url: '{{ route("admin.vehicles.operations.details", ":id") }}'.replace(':id', id),
        method: 'GET',
        success: function(response) {
            if (response.success && response.operation) {
                const op = response.operation;
                const drivers = @json($drivers);
                const vehicles = @json($vehicles);
                
                let driversOptions = '<option value="">Select Driver</option>';
                drivers.forEach(driver => {
                    driversOptions += `<option value="${driver.id}" ${op.driver_id == driver.id ? 'selected' : ''}>${driver.name}</option>`;
                });
                
                let vehiclesOptions = '<option value="">Select Vehicle</option>';
                vehicles.forEach(vehicle => {
                    vehiclesOptions += `<option value="${vehicle.id}" ${op.vehicle_id == vehicle.id ? 'selected' : ''}>${vehicle.make} ${vehicle.model} (${vehicle.license_plate})</option>`;
                });
                
                let html = `
                    <input type="hidden" name="operation_id" value="${op.id}">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Operation Date <span class="text-danger">*</span></label>
                            <input type="date" name="operation_date" class="form-control" value="${op.operation_date ? op.operation_date.split('T')[0] : ''}" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Status <span class="text-danger">*</span></label>
                            <select name="status" class="form-select" required>
                                <option value="scheduled" ${op.status == 'scheduled' ? 'selected' : ''}>Scheduled</option>
                                <option value="in_progress" ${op.status == 'in_progress' ? 'selected' : ''}>In Progress</option>
                                <option value="completed" ${op.status == 'completed' ? 'selected' : ''}>Completed</option>
                                <option value="cancelled" ${op.status == 'cancelled' ? 'selected' : ''}>Cancelled</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Driver</label>
                            <select name="driver_id" class="form-select">
                                ${driversOptions}
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Vehicle</label>
                            <select name="vehicle_id" class="form-select">
                                ${vehiclesOptions}
                            </select>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Notes</label>
                            <textarea name="notes" class="form-control" rows="4">${op.notes || ''}</textarea>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Daily Log</label>
                            <textarea name="daily_log" class="form-control" rows="4">${op.daily_log || ''}</textarea>
                        </div>
                    </div>
                `;
                content.html(html);
                
                form.attr('action', '{{ route("admin.vehicles.operations.update", ":id") }}'.replace(':id', id));
            }
        },
        error: function() {
            content.html('<div class="alert alert-danger">Error loading operation details.</div>');
        }
    });
    
    // Handle form submission
    form.off('submit').on('submit', function(e) {
        e.preventDefault();
        const submitBtn = form.find('button[type="submit"]');
        submitBtn.prop('disabled', true).html('<i class="ri-loader-4-line ri-spin me-1"></i>Updating...');
        
        $.ajax({
            url: form.attr('action'),
            method: 'PUT',
            data: form.serialize(),
            success: function(response) {
                if (response.success) {
                    modal.hide();
                    location.reload();
                } else {
                    alert(response.message || 'Operation updated');
                    location.reload();
                }
            },
            error: function(xhr) {
                let errorMsg = 'Error updating operation. Please try again.';
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    errorMsg = xhr.responseJSON.message;
                }
                alert(errorMsg);
                submitBtn.prop('disabled', false).html('<i class="ri-save-line me-1"></i>Update Operation');
            }
        });
    });
}

// Delete Operation
function deleteOperation(id) {
    const modal = new bootstrap.Modal(document.getElementById('deleteOperationModal'));
    const form = $('#deleteOperationForm');
    
    form.attr('action', '{{ route("admin.vehicles.operations.destroy", ":id") }}'.replace(':id', id));
    modal.show();
    
    form.off('submit').on('submit', function(e) {
        e.preventDefault();
        const submitBtn = form.find('button[type="submit"]');
        submitBtn.prop('disabled', true).html('<i class="ri-loader-4-line ri-spin me-1"></i>Deleting...');
        
        $.ajax({
            url: form.attr('action'),
            method: 'DELETE',
            data: form.serialize(),
            success: function(response) {
                if (response.success) {
                    modal.hide();
                    location.reload();
                } else {
                    alert(response.message || 'Operation deleted');
                    location.reload();
                }
            },
            error: function(xhr) {
                let errorMsg = 'Error deleting operation. Please try again.';
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    errorMsg = xhr.responseJSON.message;
                }
                alert(errorMsg);
                submitBtn.prop('disabled', false).html('<i class="ri-delete-bin-line me-1"></i>Delete Operation');
            }
        });
    });
}
</script>
@endpush
@endsection
