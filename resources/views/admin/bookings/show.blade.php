@extends('admin.layouts.app')

@section('title', 'Booking Details - ' . $booking->booking_reference . ' - Lau Paradise Adventures')
@section('description', 'View detailed booking information')

@push('styles')
<style>
    .booking-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 2rem;
        border-radius: 10px;
        margin-bottom: 2rem;
    }
    .booking-status-badge {
        display: inline-block;
        padding: 0.5rem 1.5rem;
        border-radius: 50px;
        font-weight: 600;
        font-size: 0.875rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    .status-pending_payment { background: #fef3c7; color: #92400e; }
    .status-confirmed { background: #d1fae5; color: #065f46; }
    .status-cancelled { background: #fee2e2; color: #991b1b; }
    .status-completed { background: #dbeafe; color: #1e40af; }
    
    .info-card {
        border: none;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        transition: transform 0.2s, box-shadow 0.2s;
    }
    .info-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    }
    .info-icon {
        width: 48px;
        height: 48px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
        margin-bottom: 1rem;
    }
    .price-highlight {
        font-size: 2rem;
        font-weight: 700;
        color: #2563eb;
    }
    .timeline-item {
        position: relative;
        padding-left: 2.5rem;
        padding-bottom: 2rem;
    }
    .timeline-item:not(:last-child)::before {
        content: '';
        position: absolute;
        left: 0.75rem;
        top: 2.5rem;
        bottom: -2rem;
        width: 2px;
        background: #e5e7eb;
    }
    .timeline-dot {
        position: absolute;
        left: 0;
        top: 0.25rem;
        width: 1.5rem;
        height: 1.5rem;
        border-radius: 50%;
        background: #2563eb;
        border: 3px solid white;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }
    .print-only {
        display: none;
    }
    @media print {
        .no-print {
            display: none !important;
        }
        .print-only {
            display: block !important;
        }
        .info-card {
            break-inside: avoid;
        }
    }
</style>
@endpush

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <!-- Booking Header -->
    <div class="booking-header">
        <div class="row align-items-center">
            <div class="col-md-8">
                <h2 class="mb-2 text-white">
                    <i class="ri-file-list-3-line me-2"></i>Booking Details
                </h2>
                <p class="mb-0 text-white-50">Reference: <strong>{{ $booking->booking_reference }}</strong></p>
            </div>
            <div class="col-md-4 text-md-end mt-3 mt-md-0">
                <span class="booking-status-badge status-{{ $booking->status }}">
                    {{ ucfirst(str_replace('_', ' ', $booking->status)) }}
                </span>
            </div>
        </div>
    </div>

    <!-- Action Buttons -->
    <div class="row mb-4 no-print">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex flex-wrap gap-2">
                        <a href="{{ route('admin.bookings.index') }}" class="btn btn-outline-secondary">
                            <i class="ri-arrow-left-line me-1"></i>Back to Bookings
                        </a>
                        <a href="{{ route('admin.bookings.pdf.view', $booking->id) }}" target="_blank" class="btn btn-outline-primary">
                            <i class="ri-file-pdf-line me-1"></i>View PDF
                        </a>
                        <a href="{{ route('admin.bookings.pdf', $booking->id) }}" class="btn btn-primary">
                            <i class="ri-download-line me-1"></i>Download PDF
                        </a>
                        @if(!isset($editMode) || !$editMode)
                        <a href="{{ route('admin.bookings.show', $booking->id) }}?edit=1" class="btn btn-success">
                            <i class="ri-edit-line me-1"></i>Edit Booking
                        </a>
                        @else
                        <a href="{{ route('admin.bookings.show', $booking->id) }}" class="btn btn-outline-secondary">
                            <i class="ri-close-line me-1"></i>Cancel Edit
                        </a>
                        @endif
                        <button onclick="window.print()" class="btn btn-outline-info">
                            <i class="ri-printer-line me-1"></i>Print
                        </button>
                        @if($booking->status !== 'cancelled')
                        <div class="btn-group">
                            <button type="button" class="btn btn-outline-success dropdown-toggle" data-bs-toggle="dropdown">
                                <i class="ri-more-line me-1"></i>Actions
                            </button>
                            <ul class="dropdown-menu">
                                @if($booking->status === 'pending_payment')
                                <li>
                                    <form action="{{ route('admin.bookings.update-status', $booking->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        <input type="hidden" name="status" value="confirmed">
                                        <button type="submit" class="dropdown-item">
                                            <i class="ri-checkbox-circle-line me-2"></i>Confirm Booking
                                        </button>
                                    </form>
                                </li>
                                <li><hr class="dropdown-divider"></li>
                                @endif
                                
                                <!-- Send Documents via Email -->
                                <li class="dropdown-submenu">
                                    <a class="dropdown-item" href="#">
                                        <i class="ri-mail-line me-2"></i>Send Email <i class="ri-arrow-right-s-line float-end"></i>
                                    </a>
                                    <ul class="dropdown-menu">
                                        <li>
                                            <a class="dropdown-item send-email-btn" href="#" data-route="/admin/documents/booking/{{ $booking->id }}/confirmation-voucher/send" data-method="POST">
                                                <i class="ri-file-check-line me-2"></i>Booking Confirmation
                                            </a>
                                        </li>
                                        <li>
                                            <a class="dropdown-item send-email-btn" href="#" data-route="/admin/documents/booking/{{ $booking->id }}/tour-voucher/send" data-method="POST">
                                                <i class="ri-ticket-line me-2"></i>Tour Voucher
                                            </a>
                                        </li>
                                        <li>
                                            <a class="dropdown-item send-email-btn" href="#" data-route="/admin/documents/booking/{{ $booking->id }}/travel-checklist/send" data-method="POST">
                                                <i class="ri-list-check me-2"></i>Travel Checklist
                                            </a>
                                        </li>
                                        <li>
                                            <a class="dropdown-item send-email-btn" href="#" data-route="/admin/documents/booking/{{ $booking->id }}/proforma-invoice/send" data-method="POST">
                                                <i class="ri-file-list-3-line me-2"></i>Proforma Invoice
                                            </a>
                                        </li>
                                        <li>
                                            <a class="dropdown-item" href="{{ route('admin.bookings.send-voucher', $booking->id) }}" onclick="event.preventDefault(); sendVoucher({{ $booking->id }});">
                                                <i class="ri-file-paper-2-line me-2"></i>Travel Voucher
                                            </a>
                                        </li>
                                        @if($booking->status === 'completed' || $booking->status === 'confirmed')
                                        <li><hr class="dropdown-divider"></li>
                                        <li>
                                            <a class="dropdown-item send-email-btn" href="#" data-route="/admin/documents/booking/{{ $booking->id }}/completion-certificate/send" data-method="POST">
                                                <i class="ri-award-line me-2"></i>Completion Certificate 🎉
                                            </a>
                                        </li>
                                        @endif
                                    </ul>
                                </li>
                                
                                <!-- Generate Documents -->
                                <li class="dropdown-submenu">
                                    <a class="dropdown-item" href="#">
                                        <i class="ri-file-download-line me-2"></i>Generate Documents <i class="ri-arrow-right-s-line float-end"></i>
                                    </a>
                                    <ul class="dropdown-menu">
                                        <li><a class="dropdown-item" href="/admin/documents/booking/{{ $booking->id }}/confirmation-voucher" target="_blank"><i class="ri-file-check-line me-2"></i>Booking Confirmation</a></li>
                                        <li><a class="dropdown-item" href="/admin/documents/booking/{{ $booking->id }}/tour-voucher" target="_blank"><i class="ri-ticket-line me-2"></i>Tour Voucher</a></li>
                                        <li><a class="dropdown-item" href="/admin/documents/booking/{{ $booking->id }}/eticket" target="_blank"><i class="ri-flight-takeoff-line me-2"></i>E-Ticket</a></li>
                                        <li><a class="dropdown-item" href="/admin/documents/booking/{{ $booking->id }}/travel-checklist" target="_blank"><i class="ri-list-check me-2"></i>Travel Checklist</a></li>
                                        <li><a class="dropdown-item" href="/admin/documents/booking/{{ $booking->id }}/proforma-invoice" target="_blank"><i class="ri-file-list-3-line me-2"></i>Proforma Invoice</a></li>
                                        @if($booking->status === 'completed' || $booking->status === 'confirmed')
                                        <li><hr class="dropdown-divider"></li>
                                        <li><a class="dropdown-item" href="/admin/documents/booking/{{ $booking->id }}/completion-certificate" target="_blank"><i class="ri-award-line me-2"></i>Completion Certificate 🎉</a></li>
                                        @endif
                                        <li><hr class="dropdown-divider"></li>
                                        <li><a class="dropdown-item" href="/admin/documents/booking/{{ $booking->id }}/booking-sheet" target="_blank"><i class="ri-file-list-line me-2"></i>Booking Sheet</a></li>
                                        <li><a class="dropdown-item" href="/admin/documents/booking/{{ $booking->id }}/passenger-list" target="_blank"><i class="ri-group-line me-2"></i>Passenger List</a></li>
                                        <li><a class="dropdown-item" href="/admin/documents/booking/{{ $booking->id }}/guide-briefing" target="_blank"><i class="ri-user-voice-line me-2"></i>Guide Briefing</a></li>
                                    </ul>
                                </li>
                                
                                <!-- Send SMS -->
                                <li>
                                    <a class="dropdown-item" href="#" onclick="event.preventDefault(); sendSMS({{ $booking->id }});">
                                        <i class="ri-message-3-line me-2"></i>Send SMS
                                    </a>
                                </li>
                                
                                <li><hr class="dropdown-divider"></li>
                                
                                <!-- Cancel Booking -->
                                <li>
                                    <form action="{{ route('admin.bookings.update-status', $booking->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        <input type="hidden" name="status" value="cancelled">
                                        <button type="submit" class="dropdown-item text-danger" onclick="return confirm('Are you sure you want to cancel this booking?')">
                                            <i class="ri-close-circle-line me-2"></i>Cancel Booking
                                        </button>
                                    </form>
                                </li>
                            </ul>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Left Column -->
        <div class="col-lg-8">
            <!-- Customer Information -->
            <div class="card info-card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="ri-user-line me-2"></i>Customer Information
                    </h5>
                </div>
                <div class="card-body">
                    @if(isset($editMode) && $editMode)
                    <form id="editBookingForm" method="POST" action="{{ route('admin.bookings.update', $booking->id) }}">
                        @csrf
                        @method('PUT')
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Full Name <span class="text-danger">*</span></label>
                                <input type="text" name="customer_name" class="form-control" value="{{ $booking->customer_name }}" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Email Address <span class="text-danger">*</span></label>
                                <input type="email" name="customer_email" class="form-control" value="{{ $booking->customer_email }}" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Phone Number</label>
                                <input type="text" name="customer_phone" class="form-control" value="{{ $booking->customer_phone ?? '' }}">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Country</label>
                                <input type="text" name="customer_country" class="form-control" value="{{ $booking->customer_country ?? '' }}">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Tour</label>
                                <select name="tour_id" class="form-select">
                                    <option value="">Select Tour</option>
                                    @foreach($tours ?? [] as $tour)
                                    <option value="{{ $tour->id }}" {{ $booking->tour_id == $tour->id ? 'selected' : '' }}>{{ $tour->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Status</label>
                                <select name="status" class="form-select">
                                    <option value="pending_payment" {{ $booking->status == 'pending_payment' ? 'selected' : '' }}>Pending Payment</option>
                                    <option value="confirmed" {{ $booking->status == 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                                    <option value="cancelled" {{ $booking->status == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                                    <option value="completed" {{ $booking->status == 'completed' ? 'selected' : '' }}>Completed</option>
                                    <option value="in_progress" {{ $booking->status == 'in_progress' ? 'selected' : '' }}>In Progress</option>
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Departure Date</label>
                                <input type="date" name="departure_date" class="form-control" value="{{ $booking->departure_date ? $booking->departure_date->format('Y-m-d') : '' }}">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Number of Travelers</label>
                                <input type="number" name="travelers" class="form-control" value="{{ $booking->travelers }}" min="1" max="50">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Total Price</label>
                                <input type="number" name="total_price" class="form-control" value="{{ $booking->total_price }}" step="0.01" min="0">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Deposit Amount</label>
                                <input type="number" name="deposit_amount" class="form-control" value="{{ $booking->deposit_amount }}" step="0.01" min="0">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Payment Method</label>
                                <select name="payment_method" class="form-select">
                                    <option value="">Select Payment Method</option>
                                    <option value="cash" {{ $booking->payment_method == 'cash' ? 'selected' : '' }}>Cash</option>
                                    <option value="bank_transfer" {{ $booking->payment_method == 'bank_transfer' ? 'selected' : '' }}>Bank Transfer</option>
                                    <option value="credit_card" {{ $booking->payment_method == 'credit_card' ? 'selected' : '' }}>Credit Card</option>
                                    <option value="mobile_money" {{ $booking->payment_method == 'mobile_money' ? 'selected' : '' }}>Mobile Money</option>
                                    <option value="paypal" {{ $booking->payment_method == 'paypal' ? 'selected' : '' }}>PayPal</option>
                                </select>
                            </div>
                            <div class="col-md-12 mb-3">
                                <label class="form-label">Special Requirements</label>
                                <textarea name="special_requirements" class="form-control" rows="3">{{ $booking->special_requirements ?? '' }}</textarea>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Emergency Contact Name</label>
                                <input type="text" name="emergency_contact_name" class="form-control" value="{{ $booking->emergency_contact_name ?? '' }}">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Emergency Contact Phone</label>
                                <input type="text" name="emergency_contact_phone" class="form-control" value="{{ $booking->emergency_contact_phone ?? '' }}">
                            </div>
                            <div class="col-md-12 mb-3">
                                <label class="form-label">Notes</label>
                                <textarea name="notes" class="form-control" rows="3">{{ $booking->notes ?? '' }}</textarea>
                            </div>
                            <div class="col-12">
                                <button type="submit" class="btn btn-primary">
                                    <i class="ri-save-line me-1"></i>Save Changes
                                </button>
                                <a href="{{ route('admin.bookings.show', $booking->id) }}" class="btn btn-outline-secondary">
                                    <i class="ri-close-line me-1"></i>Cancel
                                </a>
                            </div>
                        </div>
                    </form>
                    @else
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-muted small">Full Name</label>
                            <p class="mb-0 fw-semibold">{{ $booking->customer_name }}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-muted small">Email Address</label>
                            <p class="mb-0">
                                <a href="mailto:{{ $booking->customer_email }}">{{ $booking->customer_email }}</a>
                            </p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-muted small">Phone Number</label>
                            <p class="mb-0">
                                <a href="tel:{{ $booking->customer_phone }}">{{ $booking->customer_phone ?? 'N/A' }}</a>
                            </p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-muted small">Country</label>
                            <p class="mb-0">{{ $booking->customer_country ?? 'N/A' }}</p>
                        </div>
                        @if($booking->user)
                        <div class="col-md-12">
                            <label class="form-label text-muted small">Registered User</label>
                            <p class="mb-0">
                                <span class="badge bg-label-info">{{ $booking->user->name }}</span>
                                <span class="text-muted small ms-2">({{ $booking->user->email }})</span>
                            </p>
                        </div>
                        @endif
                    </div>
                    @endif
                </div>
            </div>

            <!-- Tour Information -->
            <div class="card info-card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="ri-map-pin-line me-2"></i>Tour Information
                    </h5>
                </div>
                <div class="card-body">
                    @if($booking->tour)
                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <label class="form-label text-muted small">Tour Name</label>
                            <h5 class="mb-0">{{ $booking->tour->name }}</h5>
                        </div>
                        @if($booking->tour->destination)
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-muted small">Destination</label>
                            <p class="mb-0">{{ $booking->tour->destination->name ?? 'N/A' }}</p>
                        </div>
                        @endif
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-muted small">Duration</label>
                            <p class="mb-0">{{ $booking->tour->duration_days ?? 'N/A' }} Days</p>
                        </div>
                        @if($booking->tour->description)
                        <div class="col-md-12 mb-3">
                            <label class="form-label text-muted small">Description</label>
                            <p class="mb-0">{{ Str::limit($booking->tour->description, 200) }}</p>
                        </div>
                        @endif
                    </div>
                    @else
                    <p class="text-muted mb-0">Tour information not available</p>
                    @endif
                </div>
            </div>

            <!-- Travel Details -->
            <div class="card info-card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="ri-calendar-event-line me-2"></i>Travel Details
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-muted small">Departure Date</label>
                            <p class="mb-0 fw-semibold">
                                <i class="ri-calendar-line me-1"></i>
                                {{ $booking->departure_date->format('F d, Y') }}
                            </p>
                            <small class="text-muted">{{ $booking->departure_date->diffForHumans() }}</small>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-muted small">Number of Travelers</label>
                            <p class="mb-0 fw-semibold">
                                <i class="ri-group-line me-1"></i>
                                {{ $booking->travelers }} {{ Str::plural('Person', $booking->travelers) }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Payment Information -->
            <div class="card info-card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="ri-money-dollar-circle-line me-2"></i>Payment Information
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-muted small">Total Price</label>
                            <p class="price-highlight mb-0">
                                {{ $booking->currency ?? 'USD' }} {{ number_format($booking->total_price, 2) }}
                            </p>
                        </div>
                        @if($booking->deposit_amount)
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-muted small">Deposit Amount</label>
                            <p class="mb-0 fw-semibold">
                                {{ $booking->currency ?? 'USD' }} {{ number_format($booking->deposit_amount, 2) }}
                            </p>
                        </div>
                        @endif
                        @if($booking->balance_amount)
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-muted small">Balance Amount</label>
                            <p class="mb-0 fw-semibold">
                                {{ $booking->currency ?? 'USD' }} {{ number_format($booking->balance_amount, 2) }}
                            </p>
                        </div>
                        @endif
                        @if($booking->payment_method)
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-muted small">Payment Method</label>
                            <p class="mb-0">
                                <span class="badge bg-label-primary">{{ ucfirst(str_replace('_', ' ', $booking->payment_method)) }}</span>
                            </p>
                        </div>
                        @endif
                        @if($booking->payment_gateway_id)
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-muted small">Payment Gateway ID</label>
                            <p class="mb-0 text-muted small">{{ $booking->payment_gateway_id }}</p>
                        </div>
                        @endif
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-muted small">Payment Status</label>
                            <p class="mb-0">
                                @if($booking->payment_status === 'paid')
                                    <span class="badge bg-success">Paid</span>
                                @elseif($booking->payment_status === 'partial')
                                    <span class="badge bg-warning">Partial</span>
                                @else
                                    <span class="badge bg-danger">Unpaid</span>
                                @endif
                            </p>
                        </div>
                        @if($booking->amount_paid)
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-muted small">Amount Paid</label>
                            <p class="mb-0 fw-semibold text-success">
                                {{ $booking->currency ?? 'USD' }} {{ number_format($booking->amount_paid, 2) }}
                            </p>
                        </div>
                        @endif
                    </div>
                    
                    @if($booking->balance_amount > 0)
                    <div class="mt-3 pt-3 border-top">
                        <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#confirmPaymentModal">
                            <i class="ri-checkbox-circle-line me-1"></i>Confirm Payment
                        </button>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Addons -->
            @if($booking->addons && count($booking->addons) > 0)
            <div class="card info-card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="ri-add-box-line me-2"></i>Add-ons & Extras
                    </h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Item</th>
                                    <th class="text-end">Price</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($booking->addons as $addon)
                                <tr>
                                    <td>{{ $addon['name'] ?? 'Add-on' }}</td>
                                    <td class="text-end">{{ $booking->currency ?? 'USD' }} {{ number_format($addon['price'] ?? 0, 2) }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            @endif

            <!-- Special Requirements & Notes -->
            @if($booking->special_requirements || $booking->notes)
            <div class="card info-card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="ri-file-text-line me-2"></i>Additional Information
                    </h5>
                </div>
                <div class="card-body">
                    @if($booking->special_requirements)
                    <div class="mb-3">
                        <label class="form-label text-muted small">Special Requirements</label>
                        <p class="mb-0">{{ $booking->special_requirements }}</p>
                    </div>
                    @endif
                    @if($booking->notes)
                    <div>
                        <label class="form-label text-muted small">Notes</label>
                        <p class="mb-0">{{ $booking->notes }}</p>
                    </div>
                    @endif
                </div>
            </div>
            @endif

            <!-- Emergency Contact -->
            @if($booking->emergency_contact_name || $booking->emergency_contact_phone)
            <div class="card info-card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="ri-phone-line me-2"></i>Emergency Contact
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        @if($booking->emergency_contact_name)
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-muted small">Contact Name</label>
                            <p class="mb-0">{{ $booking->emergency_contact_name }}</p>
                        </div>
                        @endif
                        @if($booking->emergency_contact_phone)
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-muted small">Contact Phone</label>
                            <p class="mb-0">
                                <a href="tel:{{ $booking->emergency_contact_phone }}">{{ $booking->emergency_contact_phone }}</a>
                            </p>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
            @endif
        </div>

        <!-- Right Column -->
        <div class="col-lg-4">
            <!-- Booking Timeline -->
            <div class="card info-card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="ri-time-line me-2"></i>Booking Timeline
                    </h5>
                </div>
                <div class="card-body">
                    <div class="timeline-item">
                        <div class="timeline-dot"></div>
                        <div>
                            <h6 class="mb-1">Booking Created</h6>
                            <p class="text-muted small mb-0">
                                {{ $booking->created_at->format('M d, Y h:i A') }}
                            </p>
                            <small class="text-muted">{{ $booking->created_at->diffForHumans() }}</small>
                        </div>
                    </div>
                    @if($booking->confirmed_at)
                    <div class="timeline-item">
                        <div class="timeline-dot bg-success"></div>
                        <div>
                            <h6 class="mb-1">Booking Confirmed</h6>
                            <p class="text-muted small mb-0">
                                {{ $booking->confirmed_at->format('M d, Y h:i A') }}
                            </p>
                            <small class="text-muted">{{ $booking->confirmed_at->diffForHumans() }}</small>
                        </div>
                    </div>
                    @endif
                    @if($booking->cancelled_at)
                    <div class="timeline-item">
                        <div class="timeline-dot bg-danger"></div>
                        <div>
                            <h6 class="mb-1">Booking Cancelled</h6>
                            <p class="text-muted small mb-0">
                                {{ $booking->cancelled_at->format('M d, Y h:i A') }}
                            </p>
                            @if($booking->cancellation_reason)
                            <p class="text-danger small mt-1">{{ $booking->cancellation_reason }}</p>
                            @endif
                        </div>
                    </div>
                    @endif
                    <div class="timeline-item">
                        <div class="timeline-dot bg-info"></div>
                        <div>
                            <h6 class="mb-1">Last Updated</h6>
                            <p class="text-muted small mb-0">
                                {{ $booking->updated_at->format('M d, Y h:i A') }}
                            </p>
                            <small class="text-muted">{{ $booking->updated_at->diffForHumans() }}</small>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Stats -->
            <div class="card info-card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="ri-bar-chart-line me-2"></i>Quick Stats
                    </h5>
                </div>
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <span class="text-muted">Days Until Departure</span>
                        <strong class="text-primary">
                            {{ $booking->departure_date->diffInDays(now()) }} days
                        </strong>
                    </div>
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <span class="text-muted">Booking Age</span>
                        <strong>{{ $booking->created_at->diffInDays(now()) }} days</strong>
                    </div>
                    @if($booking->deposit_amount && $booking->balance_amount)
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="text-muted">Payment Progress</span>
                        <strong>
                            {{ number_format((($booking->total_price - $booking->balance_amount) / $booking->total_price) * 100, 0) }}%
                        </strong>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Related Information -->
            <div class="card info-card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="ri-links-line me-2"></i>Quick Links
                    </h5>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        @if($booking->tour)
                        <a href="{{ route('admin.tours.show', $booking->tour->id) }}" class="btn btn-outline-primary btn-sm">
                            <i class="ri-eye-line me-1"></i>View Tour Details
                        </a>
                        @endif
                        @if($booking->user)
                        <a href="{{ route('admin.users.show', $booking->user->id) }}" class="btn btn-outline-info btn-sm">
                            <i class="ri-user-line me-1"></i>View Customer Profile
                        </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Confirm Payment Modal -->
<div class="modal fade" id="confirmPaymentModal" tabindex="-1" aria-labelledby="confirmPaymentModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header" style="background: linear-gradient(135deg, #3ea572 0%, #2d7a5f 100%); color: white;">
                <h5 class="modal-title" id="confirmPaymentModalLabel">
                    <i class="ri-money-dollar-circle-line me-2"></i>Confirm Payment
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="confirmPaymentForm">
                @csrf
                <div class="modal-body">
                    <div class="alert alert-info">
                        <i class="ri-information-line me-2"></i>
                        <strong>Total Amount:</strong> {{ $booking->currency ?? 'USD' }} {{ number_format($booking->total_price, 2) }}<br>
                        <strong>Amount Paid:</strong> {{ $booking->currency ?? 'USD' }} {{ number_format($booking->amount_paid ?? 0, 2) }}<br>
                        <strong>Balance Due:</strong> <span class="text-danger fw-bold">{{ $booking->currency ?? 'USD' }} {{ number_format($booking->balance_amount ?? $booking->total_price, 2) }}</span>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Payment Amount <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text">{{ $booking->currency ?? 'USD' }}</span>
                            <input type="number" class="form-control" name="payment_amount" id="paymentAmount" 
                                   value="{{ $booking->balance_amount ?? $booking->total_price }}" 
                                   min="0" max="{{ $booking->balance_amount ?? $booking->total_price }}" 
                                   step="0.01" required>
                        </div>
                        <small class="text-muted">Maximum: {{ $booking->currency ?? 'USD' }} {{ number_format($booking->balance_amount ?? $booking->total_price, 2) }}</small>
                    </div>
                    
                    <div class="mb-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="mark_as_paid" id="markAsPaid" 
                                   {{ ($booking->balance_amount ?? $booking->total_price) <= 0 ? 'checked' : '' }}>
                            <label class="form-check-label" for="markAsPaid">
                                Mark full balance as paid ({{ $booking->currency ?? 'USD' }} {{ number_format($booking->balance_amount ?? $booking->total_price, 2) }})
                            </label>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Payment Method <span class="text-danger">*</span></label>
                        <select class="form-select" name="payment_method" required>
                            <option value="">Select Payment Method</option>
                            <option value="cash" {{ $booking->payment_method === 'cash' ? 'selected' : '' }}>Cash</option>
                            <option value="bank_transfer" {{ $booking->payment_method === 'bank_transfer' ? 'selected' : '' }}>Bank Transfer</option>
                            <option value="credit_card" {{ $booking->payment_method === 'credit_card' ? 'selected' : '' }}>Credit Card</option>
                            <option value="mobile_money" {{ $booking->payment_method === 'mobile_money' ? 'selected' : '' }}>Mobile Money</option>
                            <option value="paypal" {{ $booking->payment_method === 'paypal' ? 'selected' : '' }}>PayPal</option>
                            <option value="manual" selected>Manual Entry</option>
                        </select>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Payment Notes</label>
                        <textarea class="form-control" name="payment_notes" rows="3" 
                                  placeholder="Optional notes about this payment..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success">
                        <i class="ri-checkbox-circle-line me-1"></i>Confirm Payment
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<style>
    .dropdown-submenu {
        position: relative;
    }
    .dropdown-submenu > .dropdown-menu {
        top: 0;
        left: 100%;
        margin-top: -1px;
    }
    .dropdown-submenu:hover > .dropdown-menu {
        display: block;
    }
</style>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Handle email sending buttons
    document.querySelectorAll('.send-email-btn').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            const route = this.getAttribute('data-route');
            const method = this.getAttribute('data-method') || 'POST';
            const btnText = this.textContent.trim();
            const customerEmail = '{{ $booking->customer_email }}';
            
            if (!confirm(`Send ${btnText} via email to ${customerEmail}?`)) {
                return;
            }
            
            // Show loading state
            const originalText = this.innerHTML;
            this.innerHTML = '<i class="ri-loader-4-line ri-spin me-2"></i>Sending...';
            this.disabled = true;
            
            // Get CSRF token
            const token = document.querySelector('meta[name="csrf-token"]').content;
            
            fetch(route, {
                method: method,
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': token,
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Email sent successfully!');
                } else {
                    alert('Error: ' + (data.message || 'Failed to send email'));
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred while sending the email.');
            })
            .finally(() => {
                this.innerHTML = originalText;
                this.disabled = false;
            });
        });
    });
});

// Send voucher function
function sendVoucher(bookingId) {
    if (!confirm('Send travel voucher via email to {{ $booking->customer_email }}?')) {
        return;
    }
    
    const token = document.querySelector('meta[name="csrf-token"]').content;
    
    fetch(`/admin/bookings/${bookingId}/send-voucher`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': token,
            'Accept': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Voucher sent successfully!');
        } else {
            alert('Error: ' + (data.message || 'Failed to send voucher'));
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred while sending the voucher.');
    });
}

// Send SMS function
function sendSMS(bookingId) {
    if (!confirm('Send SMS to {{ $booking->customer_phone ?? "customer" }}?')) {
        return;
    }
    
    const token = document.querySelector('meta[name="csrf-token"]').content;
    
    fetch(`/admin/bookings/${bookingId}/send-whatsapp`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': token,
            'Accept': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('SMS sent successfully!');
        } else {
            alert('Error: ' + (data.message || 'Failed to send SMS'));
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred while sending the SMS.');
    });
}

@if(isset($editMode) && $editMode)
// Handle edit form submission
document.getElementById('editBookingForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const form = this;
    const formData = new FormData(form);
    const submitBtn = form.querySelector('button[type="submit"]');
    const originalText = submitBtn.innerHTML;
    
    // Show loading state
    submitBtn.disabled = true;
    submitBtn.innerHTML = '<i class="ri-loader-4-line ri-spin me-1"></i>Saving...';
    
    // Convert FormData to JSON
    const data = {};
    formData.forEach((value, key) => {
        data[key] = value;
    });
    
    // Get CSRF token
    const token = document.querySelector('meta[name="csrf-token"]').content;
    
    fetch(form.action, {
        method: 'PUT',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': token,
            'Accept': 'application/json'
        },
        body: JSON.stringify(data)
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Show success message
            alert('Booking updated successfully!');
            // Redirect to view mode
            window.location.href = '{{ route("admin.bookings.show", $booking->id) }}';
        } else {
            // Show error message
            let errorMsg = 'Failed to update booking';
            if (data.message) {
                errorMsg = data.message;
            } else if (data.errors) {
                errorMsg = Object.values(data.errors).flat().join('\n');
            }
            alert('Error: ' + errorMsg);
            submitBtn.disabled = false;
            submitBtn.innerHTML = originalText;
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred while saving. Please try again.');
        submitBtn.disabled = false;
        submitBtn.innerHTML = originalText;
    });
});
@endif

// Handle payment confirmation form
document.getElementById('confirmPaymentForm')?.addEventListener('submit', function(e) {
    e.preventDefault();
    
    const form = this;
    const formData = new FormData(form);
    const submitBtn = form.querySelector('button[type="submit"]');
    const originalText = submitBtn.innerHTML;
    
    // Show loading state
    submitBtn.disabled = true;
    submitBtn.innerHTML = '<i class="ri-loader-4-line ri-spin me-1"></i>Processing...';
    
    // Convert FormData to JSON
    const data = {};
    formData.forEach((value, key) => {
        if (key === 'mark_as_paid') {
            data[key] = form.querySelector('#markAsPaid').checked;
        } else {
            data[key] = value;
        }
    });
    
    // Get CSRF token
    const token = document.querySelector('meta[name="csrf-token"]').content;
    
    fetch('{{ route("admin.bookings.confirm-payment", $booking->id) }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': token,
            'Accept': 'application/json'
        },
        body: JSON.stringify(data)
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Show success message
            alert('Payment confirmed successfully!');
            // Close modal
            const modal = bootstrap.Modal.getInstance(document.getElementById('confirmPaymentModal'));
            modal.hide();
            // Reload page to show updated payment status
            window.location.reload();
        } else {
            // Show error message
            let errorMsg = 'Failed to confirm payment';
            if (data.message) {
                errorMsg = data.message;
            } else if (data.errors) {
                errorMsg = Object.values(data.errors).flat().join('\n');
            }
            alert('Error: ' + errorMsg);
            submitBtn.disabled = false;
            submitBtn.innerHTML = originalText;
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred while confirming payment. Please try again.');
        submitBtn.disabled = false;
        submitBtn.innerHTML = originalText;
    });
});

// Handle mark as paid checkbox
document.getElementById('markAsPaid')?.addEventListener('change', function() {
    const paymentAmountInput = document.getElementById('paymentAmount');
    if (this.checked) {
        paymentAmountInput.value = '{{ $booking->balance_amount ?? $booking->total_price }}';
        paymentAmountInput.disabled = true;
    } else {
        paymentAmountInput.disabled = false;
    }
});
</script>
@endpush

