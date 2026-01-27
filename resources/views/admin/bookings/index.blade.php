@extends('admin.layouts.app')

@section('title', 'All Bookings - Lau Paradise Adventures')
@section('description', 'Manage all bookings')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">
                        <i class="ri-calendar-check-line me-2"></i>All Bookings
                    </h4>
                    <div class="d-flex gap-2">
                        <a href="{{ route('admin.bookings.create') }}" class="btn btn-primary">
                            <i class="ri-add-line me-1"></i>Create New Booking
                        </a>
                        <div class="btn-group">
                            <a href="{{ route('admin.bookings.pending-approvals') }}" class="btn btn-outline-warning">
                                <i class="ri-time-line me-1"></i>Pending Approvals
                            </a>
                            <a href="{{ route('admin.bookings.confirmed') }}" class="btn btn-outline-success">
                                <i class="ri-checkbox-circle-line me-1"></i>Confirmed
                            </a>
                            <a href="{{ route('admin.bookings.cancelled') }}" class="btn btn-outline-danger">
                                <i class="ri-close-circle-line me-1"></i>Cancelled
                            </a>
                            <a href="{{ route('admin.bookings.calendar') }}" class="btn btn-outline-primary">
                                <i class="ri-calendar-line me-1"></i>Calendar
                            </a>
                        </div>
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
                                <i class="ri-file-list-3-line"></i>
                            </span>
                        </div>
                        <div>
                            <h5 class="mb-0">{{ number_format($stats['total'] ?? 0) }}</h5>
                            <small class="text-muted">Total Bookings</small>
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
                                <i class="ri-time-line"></i>
                            </span>
                        </div>
                        <div>
                            <h5 class="mb-0">{{ number_format($stats['pending'] ?? 0) }}</h5>
                            <small class="text-muted">Pending</small>
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
                            <h5 class="mb-0">{{ number_format($stats['confirmed'] ?? 0) }}</h5>
                            <small class="text-muted">Confirmed</small>
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
                            <h5 class="mb-0">{{ number_format($stats['cancelled'] ?? 0) }}</h5>
                            <small class="text-muted">Cancelled</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="card mb-4">
        <div class="card-body">
            <form id="filterForm" method="GET" action="{{ route('admin.bookings.index') }}">
                <div class="row g-3">
                    <div class="col-md-2">
                        <label class="form-label">Search</label>
                        <input type="text" name="search" class="form-control" placeholder="Reference, Name, Email..." value="{{ request('search') }}">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Booking Status</label>
                        <select name="status" class="form-select">
                            <option value="">All Status</option>
                            <option value="pending_payment" {{ request('status') == 'pending_payment' ? 'selected' : '' }}>Pending</option>
                            <option value="confirmed" {{ request('status') == 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                            <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                            <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Payment Status</label>
                        <select name="payment_status" class="form-select">
                            <option value="">All Payments</option>
                            <option value="unpaid" {{ request('payment_status') == 'unpaid' ? 'selected' : '' }}>Unpaid</option>
                            <option value="partial" {{ request('payment_status') == 'partial' ? 'selected' : '' }}>Partial</option>
                            <option value="paid" {{ request('payment_status') == 'paid' ? 'selected' : '' }}>Paid</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Tour</label>
                        <select name="tour_id" class="form-select">
                            <option value="">All Tours</option>
                            @foreach($tours as $tour)
                                <option value="{{ $tour->id }}" {{ request('tour_id') == $tour->id ? 'selected' : '' }}>{{ $tour->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Source</label>
                        <select name="booking_source" class="form-select">
                            <option value="">All Sources</option>
                            <option value="website" {{ request('booking_source') == 'website' ? 'selected' : '' }}>Website</option>
                            <option value="manual" {{ request('booking_source') == 'manual' ? 'selected' : '' }}>Manual</option>
                            <option value="whatsapp" {{ request('booking_source') == 'whatsapp' ? 'selected' : '' }}>WhatsApp</option>
                            <option value="referral" {{ request('booking_source') == 'referral' ? 'selected' : '' }}>Referral</option>
                            <option value="agent" {{ request('booking_source') == 'agent' ? 'selected' : '' }}>Agent</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Travel Date From</label>
                        <input type="date" name="date_from" class="form-control" value="{{ request('date_from') }}">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Travel Date To</label>
                        <input type="date" name="date_to" class="form-control" value="{{ request('date_to') }}">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Customer</label>
                        <input type="text" name="customer" class="form-control" placeholder="Name, Email, Phone..." value="{{ request('customer') }}">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">&nbsp;</label>
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary flex-fill">
                                <i class="ri-search-line me-1"></i>Filter
                            </button>
                            <a href="{{ route('admin.bookings.index') }}" class="btn btn-outline-secondary">
                                <i class="ri-refresh-line"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Bulk Actions -->
    <div class="card mb-4" id="bulkActionsCard" style="display: none;">
        <div class="card-body">
            <form id="bulkActionForm">
                @csrf
                <div class="row align-items-center">
                    <div class="col-md-3">
                        <strong id="selectedCount">0</strong> booking(s) selected
                    </div>
                    <div class="col-md-3">
                        <select name="action" class="form-select" id="bulkActionSelect" required>
                            <option value="">Select Action...</option>
                            <option value="approve">Approve Bookings</option>
                            <option value="cancel">Cancel Bookings</option>
                            <option value="assign_staff">Assign Staff</option>
                            <option value="export">Export Selected</option>
                        </select>
                    </div>
                    <div class="col-md-3" id="bulkActionExtra" style="display: none;">
                        <select name="staff_id" class="form-select" id="bulkStaffSelect" style="display: none;">
                            <option value="">Select Staff...</option>
                            @foreach($staff ?? [] as $s)
                                <option value="{{ $s->id }}">{{ $s->name }}</option>
                            @endforeach
                        </select>
                        <input type="text" name="cancellation_reason" class="form-control" id="bulkCancelReason" placeholder="Cancellation reason..." style="display: none;">
                    </div>
                    <div class="col-md-3">
                        <button type="submit" class="btn btn-primary">
                            <i class="ri-check-line me-1"></i>Apply
                        </button>
                        <button type="button" class="btn btn-outline-secondary" onclick="clearSelection()">
                            <i class="ri-close-line me-1"></i>Clear
                        </button>
                    </div>
                </div>
                <input type="hidden" name="booking_ids" id="bulkBookingIds">
            </form>
        </div>
    </div>

    <!-- Bookings Table -->
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">All Bookings</h5>
            <div class="d-flex gap-2">
                <a href="{{ route('admin.bookings.export', array_merge(request()->all(), ['format' => 'excel'])) }}" class="btn btn-sm btn-outline-success">
                    <i class="ri-file-excel-line me-1"></i>Export Excel
                </a>
                <a href="{{ route('admin.bookings.export', array_merge(request()->all(), ['format' => 'pdf'])) }}" class="btn btn-sm btn-outline-danger">
                    <i class="ri-file-pdf-line me-1"></i>Export PDF
                </a>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover" id="bookingsTable">
                    <thead>
                        <tr>
                            <th width="40">
                                <input type="checkbox" id="selectAll" onchange="toggleSelectAll()">
                            </th>
                            <th>Booking ID</th>
                            <th>Customer</th>
                            <th>Contact</th>
                            <th>Package/Destination</th>
                            <th>Travel Dates</th>
                            <th>People</th>
                            <th>Payment Status</th>
                            <th>Booking Status</th>
                            <th>Total Amount</th>
                            <th>Agent/Staff</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($bookings as $booking)
                        <tr>
                            <td>
                                <input type="checkbox" class="booking-checkbox" value="{{ $booking->id }}" onchange="updateBulkActions()">
                            </td>
                            <td>
                                <strong>{{ $booking->booking_reference ?? 'BK-' . str_pad($booking->id, 5, '0', STR_PAD_LEFT) }}</strong>
                            </td>
                            <td>
                                <div>
                                    <strong>{{ $booking->customer_name }}</strong>
                                </div>
                            </td>
                            <td>
                                <div>
                                    <small class="text-muted">{{ $booking->customer_email }}</small><br>
                                    <small class="text-muted">{{ $booking->customer_phone }}</small>
                                </div>
                            </td>
                            <td>{{ $booking->tour ? $booking->tour->name : 'N/A' }}</td>
                            <td>
                                @if($booking->departure_date)
                                    {{ $booking->departure_date->format('M d, Y') }}
                                    @if($booking->travel_end_date)
                                        <br><small class="text-muted">to {{ $booking->travel_end_date->format('M d, Y') }}</small>
                                    @endif
                                @else
                                    N/A
                                @endif
                            </td>
                            <td>
                                {{ ($booking->number_of_adults ?? $booking->travelers) }} Adults
                                @if($booking->number_of_children)
                                    <br><small class="text-muted">{{ $booking->number_of_children }} Children</small>
                                @endif
                            </td>
                            <td>
                                @php
                                    $paymentClass = match($booking->payment_status ?? 'unpaid') {
                                        'paid' => 'success',
                                        'partial' => 'warning',
                                        'unpaid' => 'danger',
                                        default => 'secondary'
                                    };
                                @endphp
                                <span class="badge bg-label-{{ $paymentClass }}">
                                    {{ ucfirst($booking->payment_status ?? 'Unpaid') }}
                                </span>
                                @if($booking->amount_paid)
                                    <br><small class="text-muted">${{ number_format($booking->amount_paid, 2) }} paid</small>
                                @endif
                            </td>
                            <td>
                                @php
                                    $statusClass = match($booking->status) {
                                        'confirmed' => 'success',
                                        'pending_payment' => 'warning',
                                        'cancelled' => 'danger',
                                        'completed' => 'info',
                                        default => 'secondary'
                                    };
                                @endphp
                                <span class="badge bg-label-{{ $statusClass }}">{{ ucfirst(str_replace('_', ' ', $booking->status)) }}</span>
                                @if($booking->approval_status == 'pending')
                                    <br><small class="badge bg-label-warning mt-1">Pending Approval</small>
                                @endif
                            </td>
                            <td>
                                <strong>${{ number_format($booking->total_price, 2) }}</strong>
                                @if($booking->discount_amount)
                                    <br><small class="text-success">Discount: ${{ number_format($booking->discount_amount, 2) }}</small>
                                @endif
                            </td>
                            <td>
                                @if($booking->assignedStaff)
                                    <div class="d-flex align-items-center">
                                        <div class="avatar avatar-xs me-2">
                                            <span class="avatar-initial rounded bg-label-primary">
                                                {{ strtoupper(substr($booking->assignedStaff->name, 0, 2)) }}
                                            </span>
                                        </div>
                                        <span>{{ $booking->assignedStaff->name }}</span>
                                    </div>
                                @else
                                    <span class="text-muted">Unassigned</span>
                                @endif
                            </td>
                            <td>
                                <div class="d-flex gap-1">
                                    <a href="{{ route('admin.bookings.show', $booking->id) }}" class="btn btn-sm btn-icon btn-outline-primary" title="View">
                                        <i class="ri-eye-line"></i>
                                    </a>
                                    <a href="{{ route('admin.bookings.show', $booking->id) }}?edit=1" class="btn btn-sm btn-icon btn-outline-warning" title="Edit">
                                        <i class="ri-pencil-line"></i>
                                    </a>
                                    @if($booking->approval_status == 'pending')
                                        <button class="btn btn-sm btn-icon btn-outline-success approve-booking" data-id="{{ $booking->id }}" title="Approve">
                                            <i class="ri-check-line"></i>
                                        </button>
                                    @endif
                                    @if(in_array($booking->status, ['pending_payment', 'confirmed']))
                                        <button class="btn btn-sm btn-icon btn-outline-danger cancel-booking" data-id="{{ $booking->id }}" data-bs-toggle="modal" data-bs-target="#cancelBookingModal" title="Cancel">
                                            <i class="ri-close-line"></i>
                                        </button>
                                    @endif
                                    <div class="dropdown">
                                        <button class="btn btn-sm btn-icon btn-outline-secondary" type="button" data-bs-toggle="dropdown">
                                            <i class="ri-more-2-line"></i>
                                        </button>
                                        <ul class="dropdown-menu">
                                            <li><a class="dropdown-item" href="{{ route('admin.bookings.pdf', $booking->id) }}" target="_blank"><i class="ri-file-pdf-line me-2"></i>Download PDF</a></li>
                                            <li>
                                                <form action="{{ route('admin.bookings.convert-to-invoice', $booking->id) }}" method="POST" class="d-inline" style="margin: 0;">
                                                    @csrf
                                                    <button type="submit" class="dropdown-item" style="border: none; background: none; width: 100%; text-align: left; padding: 0.5rem 1rem;" onclick="return confirm('Are you sure you want to convert this booking to an invoice?')">
                                                        <i class="ri-file-list-3-line me-2"></i>Convert to Invoice
                                                    </button>
                                                </form>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="12" class="text-center py-4">
                                <p class="text-muted mb-0">No bookings found</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="mt-4">
                {{ $bookings->links() }}
            </div>
        </div>
    </div>
</div>

<!-- View Booking Modal -->
@include('admin.bookings.modals.view')

<!-- Create Booking Modal -->
@include('admin.bookings.modals.create')

<!-- Edit Booking Modal -->
@include('admin.bookings.modals.edit')

<!-- Cancel Booking Modal -->
@include('admin.bookings.modals.cancel')

@push('scripts')
<script>
// Bulk Actions Functions
function toggleSelectAll() {
    const selectAll = document.getElementById('selectAll');
    const checkboxes = document.querySelectorAll('.booking-checkbox');
    checkboxes.forEach(cb => cb.checked = selectAll.checked);
    updateBulkActions();
}

function updateBulkActions() {
    const checkboxes = document.querySelectorAll('.booking-checkbox:checked');
    const count = checkboxes.length;
    const bulkCard = document.getElementById('bulkActionsCard');
    const selectedCount = document.getElementById('selectedCount');
    const bookingIds = Array.from(checkboxes).map(cb => cb.value);
    
    document.getElementById('bulkBookingIds').value = bookingIds.join(',');
    selectedCount.textContent = count;
    bulkCard.style.display = count > 0 ? 'block' : 'none';
}

function clearSelection() {
    document.querySelectorAll('.booking-checkbox').forEach(cb => cb.checked = false);
    document.getElementById('selectAll').checked = false;
    updateBulkActions();
}

// Handle bulk action form
document.getElementById('bulkActionForm')?.addEventListener('submit', function(e) {
    e.preventDefault();
    const action = document.getElementById('bulkActionSelect').value;
    const bookingIds = document.getElementById('bulkBookingIds').value.split(',').filter(id => id);
    
    if (!action || bookingIds.length === 0) {
        alert('Please select an action and at least one booking');
        return;
    }
    
    if (action === 'cancel' && !document.getElementById('bulkCancelReason').value) {
        alert('Please provide a cancellation reason');
        return;
    }
    
    if (action === 'assign_staff' && !document.getElementById('bulkStaffSelect').value) {
        alert('Please select a staff member');
        return;
    }
    
    const formData = new FormData(this);
    formData.set('booking_ids', bookingIds);
    
    fetch('{{ route("admin.bookings.bulk-action") }}', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
        },
        body: formData
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            location.reload();
        } else {
            alert('Error: ' + (data.message || 'Failed to perform action'));
        }
    })
    .catch(err => {
        console.error(err);
        alert('An error occurred. Please try again.');
    });
});

// Show/hide extra fields based on action
document.getElementById('bulkActionSelect')?.addEventListener('change', function() {
    const extraDiv = document.getElementById('bulkActionExtra');
    const staffSelect = document.getElementById('bulkStaffSelect');
    const cancelReason = document.getElementById('bulkCancelReason');
    
    extraDiv.style.display = ['cancel', 'assign_staff'].includes(this.value) ? 'block' : 'none';
    staffSelect.style.display = this.value === 'assign_staff' ? 'block' : 'none';
    cancelReason.style.display = this.value === 'cancel' ? 'block' : 'none';
});

// Pass tours data to JavaScript
const toursData = @json($tours);

document.addEventListener('DOMContentLoaded', function() {
    // View Booking
    document.querySelectorAll('.view-booking').forEach(btn => {
        btn.addEventListener('click', function() {
            const id = this.dataset.id;
            const content = document.getElementById('viewBookingContent');
            
            // Show loading
            content.innerHTML = `
                <div class="text-center py-4">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                </div>
            `;
            
            fetch(`/admin/bookings/${id}`, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                },
                credentials: 'same-origin'
            })
                .then(res => {
                    console.log('Response status:', res.status);
                    if (!res.ok) {
                        return res.json().then(err => {
                            throw new Error(err.error || err.message || 'Failed to load booking details');
                        }).catch(() => {
                            throw new Error(`HTTP error! status: ${res.status}`);
                        });
                    }
                    return res.json();
                })
                .then(data => {
                    console.log('Booking data received:', data);
                    if (typeof populateViewModal === 'function') {
                        populateViewModal(data);
                    } else {
                        console.error('populateViewModal function not found');
                        content.innerHTML = '<div class="alert alert-danger">Error: Modal function not found</div>';
                    }
                })
                .catch(error => {
                    console.error('Error loading booking:', error);
                    content.innerHTML = `
                        <div class="alert alert-danger">
                            <i class="ri-error-warning-line me-2"></i>
                            <strong>Error:</strong> ${error.message || 'Failed to load booking details. Please try again.'}
                        </div>
                    `;
                });
        });
    });

    // Edit Booking
    document.querySelectorAll('.edit-booking').forEach(btn => {
        btn.addEventListener('click', function() {
            const id = this.dataset.id;
            fetch(`/admin/bookings/${id}`)
                .then(res => res.json())
                .then(data => {
                    populateEditModal(data);
                });
        });
    });

    // Confirm Booking
    document.querySelectorAll('.confirm-booking').forEach(btn => {
        btn.addEventListener('click', function() {
            const id = this.dataset.id;
            if (confirm('Are you sure you want to confirm this booking?')) {
                fetch(`/admin/bookings/${id}/confirm`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Content-Type': 'application/json'
                    }
                })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        location.reload();
                    }
                });
            }
        });
    });

    // Cancel Booking
    document.querySelectorAll('.cancel-booking').forEach(btn => {
        btn.addEventListener('click', function() {
            const id = this.dataset.id;
            document.getElementById('cancelBookingId').value = id;
        });
    });

    // Approve Booking
    document.querySelectorAll('.approve-booking').forEach(btn => {
        btn.addEventListener('click', function() {
            const id = this.dataset.id;
            if (confirm('Are you sure you want to approve this booking?')) {
                fetch(`/admin/bookings/${id}/approve`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({})
                })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        location.reload();
                    } else {
                        alert('Error: ' + (data.message || 'Failed to approve booking'));
                    }
                });
            }
        });
    });
});
</script>
@endpush
@endsection
