@extends('admin.layouts.app')

@section('title', 'Confirmed Bookings - Lau Paradise Adventures')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">
                        <i class="ri-checkbox-circle-line me-2"></i>Confirmed Bookings
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
                            <th>Confirmed</th>
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
                            <td>{{ $booking->confirmed_at ? $booking->confirmed_at->format('M d, Y') : 'N/A' }}</td>
                            <td>
                                <div class="d-flex gap-1">
                                    <a href="{{ route('admin.bookings.show', $booking->id) }}" class="btn btn-sm btn-icon btn-outline-primary" title="View">
                                        <i class="ri-eye-line"></i>
                                    </a>
                                    <a href="{{ route('admin.bookings.pdf', $booking->id) }}" class="btn btn-sm btn-icon btn-outline-danger" title="Download PDF Voucher" target="_blank">
                                        <i class="ri-file-pdf-line"></i>
                                    </a>
                                    <a href="{{ route('admin.bookings.pdf.view', $booking->id) }}" class="btn btn-sm btn-icon btn-outline-info" title="Print Ticket" target="_blank">
                                        <i class="ri-printer-line"></i>
                                    </a>
                                    <div class="dropdown">
                                        <button class="btn btn-sm btn-icon btn-outline-secondary" type="button" data-bs-toggle="dropdown">
                                            <i class="ri-more-2-line"></i>
                                        </button>
                                        <ul class="dropdown-menu">
                                            <li>
                                                <form action="{{ route('admin.bookings.convert-to-invoice', $booking->id) }}" method="POST" class="d-inline convert-invoice-form" style="margin: 0;">
                                                    @csrf
                                                    <button type="submit" class="dropdown-item" style="border: none; background: none; width: 100%; text-align: left; padding: 0.5rem 1rem;">
                                                        <i class="ri-file-list-3-line me-2"></i>Generate Invoice
                                                    </button>
                                                </form>
                                            </li>
                                            <li>
                                                <a class="dropdown-item" href="{{ route('admin.tours.show', $booking->tour_id) }}" target="_blank">
                                                    <i class="ri-route-line me-2"></i>View Itinerary
                                                </a>
                                            </li>
                                            <li>
                                                <a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#transportModal{{ $booking->id }}">
                                                    <i class="ri-truck-line me-2"></i>Add Transport Assignment
                                                </a>
                                            </li>
                                            <li>
                                                <a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#guideModal{{ $booking->id }}">
                                                    <i class="ri-user-star-line me-2"></i>Add Guide Assignment
                                                </a>
                                            </li>
                                            <li><hr class="dropdown-divider"></li>
                                            <li>
                                                <form action="{{ route('admin.bookings.send-voucher', $booking->id) }}" method="POST" class="d-inline send-voucher-form" style="margin: 0;">
                                                    @csrf
                                                    <button type="submit" class="dropdown-item" style="border: none; background: none; width: 100%; text-align: left; padding: 0.5rem 1rem;">
                                                        <i class="ri-mail-send-line me-2"></i>Send Travel Voucher
                                                    </button>
                                                </form>
                                            </li>
                                            <li>
                                                <form action="{{ route('admin.bookings.send-whatsapp', $booking->id) }}" method="POST" class="d-inline send-whatsapp-form" style="margin: 0;">
                                                    @csrf
                                                    <button type="submit" class="dropdown-item" style="border: none; background: none; width: 100%; text-align: left; padding: 0.5rem 1rem;">
                                                        <i class="ri-whatsapp-line me-2"></i>Send via WhatsApp
                                                    </button>
                                                </form>
                                            </li>
                                            <li><hr class="dropdown-divider"></li>
                                            <li>
                                                <form action="{{ route('admin.bookings.mark-completed', $booking->id) }}" method="POST" class="d-inline mark-status-form" style="margin: 0;">
                                                    @csrf
                                                    <button type="submit" class="dropdown-item text-success" style="border: none; background: none; width: 100%; text-align: left; padding: 0.5rem 1rem;">
                                                        <i class="ri-checkbox-circle-line me-2"></i>Mark as Completed
                                                    </button>
                                                </form>
                                            </li>
                                            <li>
                                                <form action="{{ route('admin.bookings.mark-in-progress', $booking->id) }}" method="POST" class="d-inline mark-status-form" style="margin: 0;">
                                                    @csrf
                                                    <button type="submit" class="dropdown-item text-info" style="border: none; background: none; width: 100%; text-align: left; padding: 0.5rem 1rem;">
                                                        <i class="ri-time-line me-2"></i>Mark as In-Progress
                                                    </button>
                                                </form>
                                            </li>
                                        </ul>
                                    </div>
                                    
                                    <!-- Transport Assignment Modal -->
                                    <div class="modal fade" id="transportModal{{ $booking->id }}" tabindex="-1">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Add Transport Assignment</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                </div>
                                                <form action="{{ route('admin.bookings.add-transport', $booking->id) }}" method="POST" class="transport-form">
                                                    @csrf
                                                    <div class="modal-body">
                                                        <div class="mb-3">
                                                            <label class="form-label">Vehicle <span class="text-danger">*</span></label>
                                                            <select name="vehicle_id" class="form-select" required>
                                                                <option value="">Select Vehicle</option>
                                                                @foreach(\App\Models\Vehicle::whereIn('status', ['available', 'in_use'])->orderBy('model')->get() as $vehicle)
                                                                    <option value="{{ $vehicle->id }}">
                                                                        {{ $vehicle->make }} {{ $vehicle->model }} ({{ $vehicle->license_plate }})
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label class="form-label">Driver (Optional)</label>
                                                            <select name="driver_id" class="form-select">
                                                                <option value="">Select Driver</option>
                                                                @foreach(\App\Models\User::whereHas('roles', function($q) {
                                                                    $q->where('name', 'Driver/Guide');
                                                                })->orderBy('name')->get() as $driver)
                                                                    <option value="{{ $driver->id }}">{{ $driver->name }}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label class="form-label">Operation Date</label>
                                                            <input type="date" name="operation_date" class="form-control" value="{{ $booking->departure_date ? $booking->departure_date->format('Y-m-d') : '' }}">
                                                        </div>
                                                        <div class="mb-3">
                                                            <label class="form-label">Notes</label>
                                                            <textarea name="notes" class="form-control" rows="3"></textarea>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">Cancel</button>
                                                        <button type="submit" class="btn btn-primary">Assign Transport</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <!-- Guide Assignment Modal -->
                                    <div class="modal fade" id="guideModal{{ $booking->id }}" tabindex="-1">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Add Guide Assignment</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                </div>
                                                <form action="{{ route('admin.bookings.add-guide', $booking->id) }}" method="POST" class="guide-form">
                                                    @csrf
                                                    <div class="modal-body">
                                                        <div class="mb-3">
                                                            <label class="form-label">Guide <span class="text-danger">*</span></label>
                                                            <select name="guide_id" class="form-select" required>
                                                                <option value="">Select Guide</option>
                                                                @foreach(\App\Models\User::whereHas('roles', function($q) {
                                                                    $q->where('name', 'Driver/Guide');
                                                                })->orderBy('name')->get() as $guide)
                                                                    <option value="{{ $guide->id }}">{{ $guide->name }}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label class="form-label">Operation Date</label>
                                                            <input type="date" name="operation_date" class="form-control" value="{{ $booking->departure_date ? $booking->departure_date->format('Y-m-d') : '' }}">
                                                        </div>
                                                        <div class="mb-3">
                                                            <label class="form-label">Notes</label>
                                                            <textarea name="notes" class="form-control" rows="3"></textarea>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">Cancel</button>
                                                        <button type="submit" class="btn btn-primary">Assign Guide</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center py-4">
                                <p class="text-muted mb-0">No confirmed bookings</p>
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
@include('admin.bookings.modals.edit')

@php
    $tours = \App\Models\Tour::orderBy('name')->get();
@endphp

@push('scripts')
<script>
const toursData = @json($tours);
</script>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Handle form submissions via AJAX
    document.querySelectorAll('.convert-invoice-form').forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            const formData = new FormData(this);
            const submitBtn = this.querySelector('button[type="submit"]');
            const originalText = submitBtn.innerHTML;
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="ri-loader-4-line ri-spin me-2"></i>Generating...';
            
            fetch(this.action, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                },
                body: formData
            })
            .then(res => res.json())
            .then(res => {
                if (!res.ok) {
                    return res.json().then(err => {
                        throw new Error(err.message || err.error || 'Failed to generate invoice');
                    });
                }
                return res.json();
            })
            .then(data => {
                if (data.success) {
                    if (data.redirect) {
                        window.location.href = data.redirect;
                    } else {
                        location.reload();
                    }
                } else {
                    alert('Error: ' + (data.message || 'Failed to generate invoice'));
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = originalText;
                }
            })
            .catch(err => {
                console.error('Invoice generation error:', err);
                alert('Error: ' + (err.message || 'An error occurred. Please check the console for details.'));
                submitBtn.disabled = false;
                submitBtn.innerHTML = originalText;
            });
        });
    });

    // Send Voucher
    document.querySelectorAll('.send-voucher-form').forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            if (!confirm('Send travel voucher to ' + this.closest('tr').querySelector('td:nth-child(2) small').textContent + '?')) {
                return;
            }
            const formData = new FormData(this);
            const submitBtn = this.querySelector('button[type="submit"]');
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="ri-loader-4-line ri-spin me-2"></i>Sending...';
            
            fetch(this.action, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                },
                body: formData
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    alert(data.message);
                    location.reload();
                } else {
                    alert('Error: ' + (data.message || 'Failed to send voucher'));
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = '<i class="ri-mail-send-line me-2"></i>Send Travel Voucher';
                }
            })
            .catch(err => {
                console.error(err);
                alert('An error occurred. Please try again.');
                submitBtn.disabled = false;
                submitBtn.innerHTML = '<i class="ri-mail-send-line me-2"></i>Send Travel Voucher';
            });
        });
    });

    // Send WhatsApp
    document.querySelectorAll('.send-whatsapp-form').forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            const formData = new FormData(this);
            const submitBtn = this.querySelector('button[type="submit"]');
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="ri-loader-4-line ri-spin me-2"></i>Sending...';
            
            fetch(this.action, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                },
                body: formData
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    alert(data.message);
                    location.reload();
                } else {
                    alert('Error: ' + (data.message || 'Failed to send WhatsApp message'));
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = '<i class="ri-whatsapp-line me-2"></i>Send via WhatsApp';
                }
            })
            .catch(err => {
                console.error(err);
                alert('An error occurred. Please try again.');
                submitBtn.disabled = false;
                submitBtn.innerHTML = '<i class="ri-whatsapp-line me-2"></i>Send via WhatsApp';
            });
        });
    });

    // Mark Status
    document.querySelectorAll('.mark-status-form').forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            const formData = new FormData(this);
            const submitBtn = this.querySelector('button[type="submit"]');
            submitBtn.disabled = true;
            
            fetch(this.action, {
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
                    alert('Error: ' + (data.message || 'Failed to update status'));
                    submitBtn.disabled = false;
                }
            })
            .catch(err => {
                console.error(err);
                alert('An error occurred. Please try again.');
                submitBtn.disabled = false;
            });
        });
    });

    // Transport and Guide forms
    document.querySelectorAll('.transport-form, .guide-form').forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            const formData = new FormData(this);
            const submitBtn = this.querySelector('button[type="submit"]');
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="ri-loader-4-line ri-spin me-2"></i>Assigning...';
            
            fetch(this.action, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                },
                body: formData
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    alert(data.message);
                    location.reload();
                } else {
                    alert('Error: ' + (data.message || 'Failed to assign'));
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = submitBtn.textContent.includes('Transport') ? 'Assign Transport' : 'Assign Guide';
                }
            })
            .catch(err => {
                console.error(err);
                alert('An error occurred. Please try again.');
                submitBtn.disabled = false;
                submitBtn.innerHTML = submitBtn.textContent.includes('Transport') ? 'Assign Transport' : 'Assign Guide';
            });
        });
    });

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
                .then(data => populateEditModal(data));
        });
    });
});
</script>
@endpush
@endsection
