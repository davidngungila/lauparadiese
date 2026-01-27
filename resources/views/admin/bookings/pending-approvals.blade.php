@extends('admin.layouts.app')

@section('title', 'Pending Approvals - Lau Paradise Adventures')
@section('description', 'Review and approve pending bookings')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">
                        <i class="ri-time-line me-2"></i>Pending Approvals
                    </h4>
                    <div class="d-flex gap-2">
                        <a href="{{ route('admin.bookings.index') }}" class="btn btn-outline-primary">
                            <i class="ri-arrow-left-line me-1"></i>All Bookings
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bookings Table -->
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Booking ID</th>
                            <th>Customer</th>
                            <th>Package/Destination</th>
                            <th>Travel Dates</th>
                            <th>People</th>
                            <th>Amount</th>
                            <th>Payment Status</th>
                            <th>Assigned Staff</th>
                            <th>Created</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($bookings as $booking)
                        <tr>
                            <td>
                                <strong>{{ $booking->booking_reference ?? 'BK-' . str_pad($booking->id, 5, '0', STR_PAD_LEFT) }}</strong>
                            </td>
                            <td>
                                <div>
                                    <strong>{{ $booking->customer_name }}</strong><br>
                                    <small class="text-muted">{{ $booking->customer_email }}</small>
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
                                <strong>${{ number_format($booking->total_price, 2) }}</strong>
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
                            </td>
                            <td>
                                @if($booking->assignedStaff)
                                    {{ $booking->assignedStaff->name }}
                                @else
                                    <span class="text-muted">Unassigned</span>
                                @endif
                            </td>
                            <td>
                                {{ $booking->created_at->format('M d, Y') }}<br>
                                <small class="text-muted">{{ $booking->created_at->format('h:i A') }}</small>
                            </td>
                            <td>
                                <div class="d-flex gap-1">
                                    <a href="{{ route('admin.bookings.show', $booking->id) }}" class="btn btn-sm btn-icon btn-outline-primary" title="View Details">
                                        <i class="ri-eye-line"></i>
                                    </a>
                                    <button class="btn btn-sm btn-icon btn-outline-success approve-booking" data-id="{{ $booking->id }}" title="Approve">
                                        <i class="ri-check-line"></i>
                                    </button>
                                    <button class="btn btn-sm btn-icon btn-outline-danger reject-booking" data-id="{{ $booking->id }}" data-bs-toggle="modal" data-bs-target="#rejectBookingModal" title="Reject">
                                        <i class="ri-close-line"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="10" class="text-center py-4">
                                <p class="text-muted mb-0">No pending approvals</p>
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

<!-- Reject Booking Modal -->
<div class="modal fade" id="rejectBookingModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Reject Booking</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="rejectBookingForm">
                @csrf
                <div class="modal-body">
                    <input type="hidden" name="booking_id" id="rejectBookingId">
                    <div class="mb-3">
                        <label class="form-label">Rejection Reason <span class="text-danger">*</span></label>
                        <textarea name="rejection_reason" class="form-control" rows="4" required placeholder="Please provide a reason for rejecting this booking..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger">Reject Booking</button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
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
                })
                .catch(err => {
                    console.error(err);
                    alert('An error occurred. Please try again.');
                });
            }
        });
    });

    // Reject Booking
    document.querySelectorAll('.reject-booking').forEach(btn => {
        btn.addEventListener('click', function() {
            const id = this.dataset.id;
            document.getElementById('rejectBookingId').value = id;
        });
    });

    // Reject Form Submit
    document.getElementById('rejectBookingForm')?.addEventListener('submit', function(e) {
        e.preventDefault();
        const bookingId = document.getElementById('rejectBookingId').value;
        const formData = new FormData(this);
        
        fetch(`/admin/bookings/${bookingId}/reject`, {
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
                alert('Error: ' + (data.message || 'Failed to reject booking'));
            }
        })
        .catch(err => {
            console.error(err);
            alert('An error occurred. Please try again.');
        });
    });
});
</script>
@endpush
@endsection




