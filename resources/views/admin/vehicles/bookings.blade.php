@extends('admin.layouts.app')

@section('title', 'Transport Bookings - Lau Paradise Adventures')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">
                        <i class="ri-calendar-check-line me-2"></i>Transport Bookings Management
                    </h4>
                    <a href="{{ route('admin.vehicles.index') }}" class="btn btn-outline-primary">
                        <i class="ri-arrow-left-line me-1"></i>All Vehicles
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('admin.vehicles.bookings') }}">
                <div class="row g-3">
                    <div class="col-md-2">
                        <label class="form-label">Status</label>
                        <select name="status" class="form-select">
                            <option value="">All Status</option>
                            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="confirmed" {{ request('status') == 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                            <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                            <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Vehicle</label>
                        <select name="vehicle_id" class="form-select">
                            <option value="">All Vehicles</option>
                            @foreach($vehicles as $v)
                                <option value="{{ $v->id }}" {{ request('vehicle_id') == $v->id ? 'selected' : '' }}>
                                    {{ ($v->make ?? '') . ' ' . ($v->model ?? 'N/A') }} ({{ $v->license_plate ?? 'N/A' }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Date From</label>
                        <input type="date" name="date_from" class="form-control" value="{{ request('date_from') }}">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Date To</label>
                        <input type="date" name="date_to" class="form-control" value="{{ request('date_to') }}">
                    </div>
                    <div class="col-md-3 d-flex align-items-end gap-2">
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="ri-search-line me-1"></i>Filter
                        </button>
                        <a href="{{ route('admin.vehicles.bookings') }}" class="btn btn-label-secondary">
                            <i class="ri-refresh-line"></i>
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Bookings Table -->
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Booking Reference</th>
                            <th>Tour</th>
                            <th>Customer</th>
                            <th>Travelers</th>
                            <th>Departure Date</th>
                            <th>Vehicle Assignment</th>
                            <th>Driver Assignment</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($bookings as $booking)
                        @php
                            $tourOperation = \App\Models\TourOperation::where('booking_id', $booking->id)->first();
                            $assignedVehicle = $tourOperation ? $tourOperation->vehicle : null;
                            $assignedDriver = $tourOperation ? $tourOperation->driver : null;
                        @endphp
                        <tr>
                            <td>
                                <strong>{{ $booking->booking_reference }}</strong>
                            </td>
                            <td>
                                <div>
                                    <strong>{{ $booking->tour->name ?? 'N/A' }}</strong>
                                    @if($booking->tour)
                                        <br><small class="text-muted">{{ $booking->tour->duration ?? 'N/A' }} days</small>
                                    @endif
                                </div>
                            </td>
                            <td>
                                <div>
                                    <strong>{{ $booking->customer_name }}</strong><br>
                                    <small class="text-muted">{{ $booking->customer_email }}</small>
                                    @if($booking->customer_phone)
                                        <br><small class="text-muted"><i class="ri-phone-line"></i> {{ $booking->customer_phone }}</small>
                                    @endif
                                </div>
                            </td>
                            <td>
                                <span class="badge bg-label-info">{{ $booking->travelers ?? 0 }} passengers</span>
                            </td>
                            <td>
                                <div>
                                    {{ $booking->departure_date ? $booking->departure_date->format('M d, Y') : 'N/A' }}
                                    @if($booking->departure_date && $booking->departure_date->isPast() && $booking->status != 'completed')
                                        <br><small class="text-danger">Past due</small>
                                    @endif
                                </div>
                            </td>
                            <td>
                                @if($assignedVehicle)
                                    <div class="d-flex align-items-center">
                                        <i class="ri-car-line me-1"></i>
                                        <div>
                                            <strong>{{ $assignedVehicle->make }} {{ $assignedVehicle->model }}</strong>
                                            <br><small class="text-muted">{{ $assignedVehicle->license_plate }}</small>
                                        </div>
                                    </div>
                                @else
                                    <button class="btn btn-sm btn-outline-primary assign-vehicle-booking" 
                                            data-booking-id="{{ $booking->id }}"
                                            data-booking-ref="{{ $booking->booking_reference }}"
                                            data-travelers="{{ $booking->travelers }}"
                                            data-bs-toggle="modal" 
                                            data-bs-target="#assignVehicleBookingModal">
                                        <i class="ri-add-line me-1"></i>Assign Vehicle
                                    </button>
                                @endif
                            </td>
                            <td>
                                @if($assignedDriver)
                                    <div class="d-flex align-items-center">
                                        <div class="avatar avatar-xs me-2">
                                            <span class="avatar-initial rounded bg-label-primary">
                                                {{ strtoupper(substr($assignedDriver->name, 0, 2)) }}
                                            </span>
                                        </div>
                                        <span>{{ $assignedDriver->name }}</span>
                                    </div>
                                @else
                                    <button class="btn btn-sm btn-outline-success assign-driver-booking" 
                                            data-booking-id="{{ $booking->id }}"
                                            data-booking-ref="{{ $booking->booking_reference }}"
                                            data-bs-toggle="modal" 
                                            data-bs-target="#assignDriverBookingModal">
                                        <i class="ri-user-add-line me-1"></i>Assign Driver
                                    </button>
                                @endif
                            </td>
                            <td>
                                @php
                                    $statusClass = match($booking->status) {
                                        'confirmed' => 'success',
                                        'pending' => 'warning',
                                        'cancelled' => 'danger',
                                        'completed' => 'info',
                                        default => 'secondary'
                                    };
                                @endphp
                                <span class="badge bg-label-{{ $statusClass }}">{{ ucfirst($booking->status) }}</span>
                            </td>
                            <td>
                                <div class="d-flex gap-1">
                                    <button class="btn btn-sm btn-icon btn-outline-primary view-booking" 
                                            data-id="{{ $booking->id }}"
                                            data-bs-toggle="modal" 
                                            data-bs-target="#viewBookingModal"
                                            title="View Details">
                                        <i class="ri-eye-line"></i>
                                    </button>
                                    <a href="{{ route('admin.bookings.show', $booking->id) }}" 
                                       class="btn btn-sm btn-icon btn-outline-info"
                                       title="Full Details">
                                        <i class="ri-file-text-line"></i>
                                    </a>
                                    @if(!$tourOperation)
                                    <a href="{{ route('admin.vehicles.assign-driver', ['booking_id' => $booking->id]) }}" 
                                       class="btn btn-sm btn-icon btn-outline-success"
                                       title="Quick Assign">
                                        <i class="ri-user-settings-line"></i>
                                    </a>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="9" class="text-center py-4">
                                <p class="text-muted mb-0">No transport bookings found</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-4">
                {{ $bookings->links() }}
            </div>
        </div>
    </div>
</div>

<!-- View Booking Modal -->
<div class="modal fade" id="viewBookingModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Booking Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="viewBookingContent">
                <p class="text-center text-muted">Loading...</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">Close</button>
                <a href="#" class="btn btn-primary" id="viewBookingFullLink">
                    <i class="ri-file-text-line me-1"></i>View Full Details
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Assign Vehicle to Booking Modal -->
<div class="modal fade" id="assignVehicleBookingModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Assign Vehicle to Booking</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="assignVehicleBookingForm" method="POST" action="{{ route('admin.vehicles.assign-driver.store') }}">
                @csrf
                <div class="modal-body">
                    <input type="hidden" name="booking_id" id="assign-vehicle-booking-id">
                    <div class="mb-3">
                        <label class="form-label">Booking Reference</label>
                        <input type="text" id="assign-vehicle-booking-ref" class="form-control" readonly>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Travelers</label>
                        <input type="text" id="assign-vehicle-travelers" class="form-control" readonly>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Select Vehicle <span class="text-danger">*</span></label>
                        <select name="vehicle_id" class="form-select" required>
                            <option value="">Choose a vehicle...</option>
                            @foreach($vehicles as $vehicle)
                                <option value="{{ $vehicle->id }}" data-capacity="{{ $vehicle->capacity ?? 0 }}">
                                    {{ $vehicle->make }} {{ $vehicle->model }} ({{ $vehicle->license_plate }})
                                    - Capacity: {{ $vehicle->capacity ?? 0 }} seats
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Select Driver <span class="text-danger">*</span></label>
                        <select name="driver_id" class="form-select" required>
                            <option value="">Choose a driver...</option>
                            @foreach(\App\Models\User::whereHas('roles', function($q) {
                                $q->where('name', 'Driver/Guide');
                            })->orderBy('name')->get() as $driver)
                                <option value="{{ $driver->id }}">{{ $driver->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Notes</label>
                        <textarea name="notes" class="form-control" rows="3"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Assign</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Assign Driver to Booking Modal -->
<div class="modal fade" id="assignDriverBookingModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Assign Driver to Booking</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="assignDriverBookingForm" method="POST" action="{{ route('admin.vehicles.assign-driver.store') }}">
                @csrf
                <div class="modal-body">
                    <input type="hidden" name="booking_id" id="assign-driver-booking-id">
                    <div class="mb-3">
                        <label class="form-label">Booking Reference</label>
                        <input type="text" id="assign-driver-booking-ref" class="form-control" readonly>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Select Driver <span class="text-danger">*</span></label>
                        <select name="driver_id" class="form-select" required>
                            <option value="">Choose a driver...</option>
                            @foreach(\App\Models\User::whereHas('roles', function($q) {
                                $q->where('name', 'Driver/Guide');
                            })->orderBy('name')->get() as $driver)
                                <option value="{{ $driver->id }}">{{ $driver->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Select Vehicle <span class="text-danger">*</span></label>
                        <select name="vehicle_id" class="form-select" required>
                            <option value="">Choose a vehicle...</option>
                            @foreach($vehicles as $vehicle)
                                <option value="{{ $vehicle->id }}">
                                    {{ $vehicle->make }} {{ $vehicle->model }} ({{ $vehicle->license_plate }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Notes</label>
                        <textarea name="notes" class="form-control" rows="3"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Assign</button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
$(document).ready(function() {
    // View Booking
    $('.view-booking').on('click', function() {
        const bookingId = $(this).data('id');
        $('#viewBookingFullLink').attr('href', `/admin/bookings/${bookingId}`);
        $('#viewBookingContent').html('<p class="text-center text-muted">Loading booking details...</p>');
    });
    
    // Assign Vehicle to Booking
    $('.assign-vehicle-booking').on('click', function() {
        const bookingId = $(this).data('booking-id');
        const bookingRef = $(this).data('booking-ref');
        const travelers = $(this).data('travelers');
        
        $('#assign-vehicle-booking-id').val(bookingId);
        $('#assign-vehicle-booking-ref').val(bookingRef);
        $('#assign-vehicle-travelers').val(travelers + ' passengers');
    });
    
    // Assign Driver to Booking
    $('.assign-driver-booking').on('click', function() {
        const bookingId = $(this).data('booking-id');
        const bookingRef = $(this).data('booking-ref');
        
        $('#assign-driver-booking-id').val(bookingId);
        $('#assign-driver-booking-ref').val(bookingRef);
    });
    
    // Form submissions
    $('#assignVehicleBookingForm, #assignDriverBookingForm').on('submit', function(e) {
        e.preventDefault();
        const form = $(this);
        const submitBtn = form.find('button[type="submit"]');
        
        submitBtn.prop('disabled', true).html('<i class="ri-loader-4-line ri-spin me-1"></i>Assigning...');
        
        $.ajax({
            url: form.attr('action'),
            method: 'POST',
            data: form.serialize(),
            success: function(response) {
                location.reload();
            },
            error: function(xhr) {
                alert('Error assigning. Please try again.');
                submitBtn.prop('disabled', false).html('Assign');
            }
        });
    });
});
</script>
@endpush
@endsection
