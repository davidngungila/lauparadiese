@extends('admin.layouts.app')

@section('title', 'Pending Bookings - Lau Paradise Adventures')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">
                        <i class="ri-time-line me-2"></i>Pending Approvals
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
                            <th>Created</th>
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
                            <td>{{ $booking->created_at->format('M d, Y') }}</td>
                            <td>
                                <div class="d-flex gap-2">
                                    <button class="btn btn-sm btn-icon btn-outline-primary view-booking" data-id="{{ $booking->id }}" data-bs-toggle="modal" data-bs-target="#viewBookingModal">
                                        <i class="ri-eye-line"></i>
                                    </button>
                                    <button class="btn btn-sm btn-icon btn-outline-success confirm-booking" data-id="{{ $booking->id }}">
                                        <i class="ri-check-line"></i>
                                    </button>
                                    <button class="btn btn-sm btn-icon btn-outline-danger cancel-booking" data-id="{{ $booking->id }}" data-bs-toggle="modal" data-bs-target="#cancelBookingModal">
                                        <i class="ri-close-line"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center py-4">
                                <p class="text-muted mb-0">No pending bookings</p>
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
@include('admin.bookings.modals.cancel')

@php
    $users = \App\Models\User::whereHas('roles', function($q) {
        $q->where('name', 'Customer');
    })->orWhereDoesntHave('roles')->orderBy('name')->get();
@endphp

@push('scripts')
<script>
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
            document.getElementById('cancelBookingId').value = this.dataset.id;
        });
    });
});
</script>
@endpush
@endsection
