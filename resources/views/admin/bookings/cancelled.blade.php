@extends('admin.layouts.app')

@section('title', 'Cancelled Bookings - Lau Paradise Adventures')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">
                        <i class="ri-close-circle-line me-2"></i>Cancelled Bookings
                    </h4>
                    <a href="{{ route('admin.bookings.index') }}" class="btn btn-outline-primary">
                        <i class="ri-arrow-left-line me-1"></i>All Bookings
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Reference</th>
                            <th>Customer</th>
                            <th>Tour</th>
                            <th>Departure</th>
                            <th>Travelers</th>
                            <th>Amount</th>
                            <th>Payment Status</th>
                            <th>Cancelled</th>
                            <th>Cancelled By</th>
                            <th>Reason</th>
                            <th>Refund Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($bookings as $booking)
                        <tr>
                            <td><strong>{{ $booking->booking_reference }}</strong></td>
                            <td>
                                <div>
                                    <strong>{{ $booking->customer_name }}</strong><br>
                                    <small class="text-muted">{{ $booking->customer_email }}</small>
                                </div>
                            </td>
                            <td>{{ $booking->tour ? $booking->tour->name : 'N/A' }}</td>
                            <td>{{ $booking->departure_date ? $booking->departure_date->format('M d, Y') : 'N/A' }}</td>
                            <td>{{ $booking->travelers }}</td>
                            <td>${{ number_format($booking->total_price, 2) }}</td>
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
                            <td>{{ $booking->cancelled_at ? $booking->cancelled_at->format('M d, Y') : 'N/A' }}</td>
                            <td>
                                @if($booking->assignedStaff)
                                    {{ $booking->assignedStaff->name }}
                                @else
                                    <span class="text-muted">System</span>
                                @endif
                            </td>
                            <td>
                                <small class="text-muted">{{ Str::limit($booking->cancellation_reason, 50) }}</small>
                            </td>
                            <td>
                                @if($booking->refund_status)
                                    @php
                                        $refundClass = match($booking->refund_status) {
                                            'processed' => 'success',
                                            'pending' => 'warning',
                                            'rejected' => 'danger',
                                            default => 'secondary'
                                        };
                                    @endphp
                                    <span class="badge bg-label-{{ $refundClass }}">
                                        {{ ucfirst($booking->refund_status) }}
                                    </span>
                                    @if($booking->refund_amount)
                                        <br><small class="text-muted">${{ number_format($booking->refund_amount, 2) }}</small>
                                    @endif
                                @else
                                    <span class="text-muted">N/A</span>
                                @endif
                            </td>
                            <td>
                                <div class="d-flex gap-1">
                                    <a href="{{ route('admin.bookings.show', $booking->id) }}" class="btn btn-sm btn-icon btn-outline-primary" title="View Details">
                                        <i class="ri-eye-line"></i>
                                    </a>
                                    <button class="btn btn-sm btn-icon btn-outline-success restore-booking" data-id="{{ $booking->id }}" title="Restore Booking">
                                        <i class="ri-refresh-line"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="11" class="text-center py-4">
                                <p class="text-muted mb-0">No cancelled bookings</p>
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

@include('admin.bookings.modals.view')

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Restore Booking
    document.querySelectorAll('.restore-booking').forEach(btn => {
        btn.addEventListener('click', function() {
            const id = this.dataset.id;
            if (confirm('Are you sure you want to restore this cancelled booking?')) {
                fetch(`/admin/bookings/${id}/restore`, {
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
                    } else {
                        alert('Error: ' + (data.message || 'Failed to restore booking'));
                    }
                })
                .catch(err => {
                    console.error(err);
                    alert('An error occurred. Please try again.');
                });
            }
        });
    });

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
});
</script>
@endpush
@endsection
