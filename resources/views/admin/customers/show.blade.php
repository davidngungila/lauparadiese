@extends('admin.layouts.app')

@section('title', 'Customer Profile - ' . ($customer->full_name ?? $customer->name) . ' - Lau Paradise Adventures')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <!-- Page Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="d-flex align-items-center">
                            <div class="avatar me-3" style="width: 80px; height: 80px;">
                                @if($customer->avatar)
                                    <img src="{{ asset('storage/' . $customer->avatar) }}" alt="{{ $customer->full_name ?? $customer->name }}" class="rounded-circle" style="width: 100%; height: 100%; object-fit: cover;">
                                @else
                                    <span class="avatar-initial rounded-circle bg-label-primary" style="width: 100%; height: 100%; display: flex; align-items: center; justify-content: center; font-size: 32px;">
                                        {{ strtoupper(substr($customer->full_name ?? $customer->name ?? 'U', 0, 1)) }}
                                    </span>
                                @endif
                            </div>
                            <div>
                                <h4 class="mb-1">{{ $customer->full_name ?? $customer->name }}</h4>
                                <p class="mb-1"><i class="ri-mail-line me-1"></i>{{ $customer->email }}</p>
                                <p class="mb-0">
                                    @if($customer->phone)
                                        <i class="ri-phone-line me-1"></i>{{ $customer->phone }}
                                    @endif
                                    @if($customer->whatsapp_number)
                                        <span class="ms-3"><i class="ri-whatsapp-line me-1 text-success"></i>{{ $customer->whatsapp_number }}</span>
                                    @endif
                                </p>
                            </div>
                        </div>
                        <div class="d-flex gap-2">
                            <a href="{{ route('admin.customers.edit', $customer->id) }}" class="btn btn-primary">
                                <i class="ri-pencil-line me-1"></i>Edit Customer
                            </a>
                            <a href="{{ route('admin.customers.index') }}" class="btn btn-outline-secondary">
                                <i class="ri-arrow-left-line me-1"></i>Back
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
                                <i class="ri-calendar-check-line"></i>
                            </span>
                        </div>
                        <div>
                            <h5 class="mb-0">{{ $totalBookings ?? 0 }}</h5>
                            <small class="text-muted">Total Bookings</small>
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
                                <i class="ri-money-dollar-circle-line"></i>
                            </span>
                        </div>
                        <div>
                            <h5 class="mb-0">${{ number_format($totalSpend ?? 0, 2) }}</h5>
                            <small class="text-muted">Total Spend</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 mb-4">
            <div class="card card-border-shadow-info">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="avatar me-3">
                            <span class="avatar-initial rounded bg-label-info">
                                <i class="ri-wallet-line"></i>
                            </span>
                        </div>
                        <div>
                            <h5 class="mb-0">${{ number_format($totalPaid ?? 0, 2) }}</h5>
                            <small class="text-muted">Total Paid</small>
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
                            <h5 class="mb-0">${{ number_format($pendingBalance ?? 0, 2) }}</h5>
                            <small class="text-muted">Pending Balance</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabs Navigation -->
    <div class="card mb-4">
        <div class="card-body">
            <ul class="nav nav-tabs nav-fill" role="tablist">
                <li class="nav-item">
                    <button type="button" class="nav-link active" data-bs-toggle="tab" data-bs-target="#basic-info" role="tab">
                        <i class="ri-user-line me-1"></i>Basic Info
                    </button>
                </li>
                <li class="nav-item">
                    <button type="button" class="nav-link" data-bs-toggle="tab" data-bs-target="#bookings" role="tab">
                        <i class="ri-calendar-check-line me-1"></i>Booking History
                    </button>
                </li>
                <li class="nav-item">
                    <button type="button" class="nav-link" data-bs-toggle="tab" data-bs-target="#payments" role="tab">
                        <i class="ri-wallet-line me-1"></i>Payment History
                    </button>
                </li>
                <li class="nav-item">
                    <button type="button" class="nav-link" data-bs-toggle="tab" data-bs-target="#documents" role="tab">
                        <i class="ri-file-line me-1"></i>Travel Documents
                    </button>
                </li>
                <li class="nav-item">
                    <button type="button" class="nav-link" data-bs-toggle="tab" data-bs-target="#communication" role="tab">
                        <i class="ri-message-line me-1"></i>Communication Log
                    </button>
                </li>
                <li class="nav-item">
                    <button type="button" class="nav-link" data-bs-toggle="tab" data-bs-target="#notes" role="tab">
                        <i class="ri-sticky-note-line me-1"></i>Internal Notes
                    </button>
                </li>
                <li class="nav-item">
                    <button type="button" class="nav-link" data-bs-toggle="tab" data-bs-target="#groups" role="tab">
                        <i class="ri-group-line me-1"></i>Groups
                    </button>
                </li>
            </ul>
        </div>
    </div>

    <!-- Tab Content -->
    <div class="tab-content">
        <!-- Basic Info Tab -->
        <div class="tab-pane fade show active" id="basic-info" role="tabpanel">
            <div class="row">
                <div class="col-md-6 mb-4">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0"><i class="ri-user-line me-2"></i>Personal Information</h5>
                        </div>
                        <div class="card-body">
                            <table class="table table-borderless">
                                <tr>
                                    <th width="150">Full Name:</th>
                                    <td>{{ $customer->full_name ?? $customer->name }}</td>
                                </tr>
                                <tr>
                                    <th>Gender:</th>
                                    <td>{{ ucfirst($customer->gender ?? 'N/A') }}</td>
                                </tr>
                                <tr>
                                    <th>Date of Birth:</th>
                                    <td>{{ $customer->date_of_birth ? $customer->date_of_birth->format('M d, Y') : 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <th>Nationality:</th>
                                    <td>{{ $customer->nationality ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <th>Passport Number:</th>
                                    <td>{{ $customer->passport_number ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <th>Passport Expiry:</th>
                                    <td>
                                        @if($customer->passport_expiry)
                                            {{ $customer->passport_expiry->format('M d, Y') }}
                                            @if($customer->passport_expiry->isPast())
                                                <span class="badge bg-label-danger ms-2">Expired</span>
                                            @elseif($customer->passport_expiry->diffInDays(now()) < 90)
                                                <span class="badge bg-label-warning ms-2">Expiring Soon</span>
                                            @endif
                                        @else
                                            N/A
                                        @endif
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 mb-4">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0"><i class="ri-phone-line me-2"></i>Contact Information</h5>
                        </div>
                        <div class="card-body">
                            <table class="table table-borderless">
                                <tr>
                                    <th width="150">Email:</th>
                                    <td>{{ $customer->email }}</td>
                                </tr>
                                <tr>
                                    <th>Phone:</th>
                                    <td>{{ $customer->phone ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <th>Mobile:</th>
                                    <td>{{ $customer->mobile ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <th>WhatsApp:</th>
                                    <td>{{ $customer->whatsapp_number ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <th>Address:</th>
                                    <td>{{ $customer->address ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <th>City:</th>
                                    <td>{{ $customer->city ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <th>Country:</th>
                                    <td>{{ $customer->country ?? 'N/A' }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 mb-4">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0"><i class="ri-alert-line me-2"></i>Emergency Contact</h5>
                        </div>
                        <div class="card-body">
                            <table class="table table-borderless">
                                <tr>
                                    <th width="150">Name:</th>
                                    <td>{{ $customer->emergency_contact_name ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <th>Phone:</th>
                                    <td>{{ $customer->emergency_contact_phone ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <th>Relationship:</th>
                                    <td>{{ $customer->emergency_contact_relationship ?? 'N/A' }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 mb-4">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0"><i class="ri-map-pin-line me-2"></i>Travel Preferences</h5>
                        </div>
                        <div class="card-body">
                            <table class="table table-borderless">
                                <tr>
                                    <th width="150">Preferred Destination:</th>
                                    <td>{{ $customer->preferred_destination ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <th>Preferred Tour Type:</th>
                                    <td>{{ $customer->preferred_tour_type ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <th>Preferred Budget:</th>
                                    <td>{{ $customer->preferred_budget ? '$' . number_format($customer->preferred_budget, 2) : 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <th>Special Needs:</th>
                                    <td>{{ $customer->special_needs ?? 'None' }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="col-md-12 mb-4">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0"><i class="ri-settings-3-line me-2"></i>System Information</h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-3">
                                    <strong>Status:</strong>
                                    @php
                                        $status = $customer->customer_status ?? ($customer->email_verified_at ? 'active' : 'inactive');
                                        $statusClass = match($status) {
                                            'active' => 'success',
                                            'inactive' => 'warning',
                                            'suspended' => 'danger',
                                            default => 'secondary'
                                        };
                                    @endphp
                                    <span class="badge bg-label-{{ $statusClass }} ms-2">{{ ucfirst($status) }}</span>
                                </div>
                                <div class="col-md-3">
                                    <strong>Assigned Consultant:</strong>
                                    <span class="ms-2">{{ $customer->assignedConsultant->name ?? 'None' }}</span>
                                </div>
                                <div class="col-md-3">
                                    <strong>Member Since:</strong>
                                    <span class="ms-2">{{ $customer->created_at->format('M d, Y') }}</span>
                                </div>
                                <div class="col-md-3">
                                    <strong>Last Updated:</strong>
                                    <span class="ms-2">{{ $customer->updated_at->format('M d, Y') }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Bookings Tab -->
        <div class="tab-pane fade" id="bookings" role="tabpanel">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><i class="ri-calendar-check-line me-2"></i>Booking History</h5>
                    <a href="{{ route('admin.bookings.index', ['customer_id' => $customer->id]) }}" class="btn btn-sm btn-primary">
                        <i class="ri-add-line me-1"></i>View All Bookings
                    </a>
                </div>
                <div class="card-body">
                    @if($customer->bookings && $customer->bookings->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Booking Reference</th>
                                        <th>Tour</th>
                                        <th>Departure Date</th>
                                        <th>Travelers</th>
                                        <th>Total Price</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($customer->bookings->take(10) as $booking)
                                        <tr>
                                            <td><strong>{{ $booking->booking_reference }}</strong></td>
                                            <td>{{ $booking->tour->name ?? 'N/A' }}</td>
                                            <td>{{ $booking->departure_date ? $booking->departure_date->format('M d, Y') : 'N/A' }}</td>
                                            <td>{{ ($booking->number_of_adults ?? 0) + ($booking->number_of_children ?? 0) }}</td>
                                            <td><strong>${{ number_format($booking->total_price ?? 0, 2) }}</strong></td>
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
                                            </td>
                                            <td>
                                                <a href="{{ route('admin.bookings.show', $booking->id) }}" class="btn btn-sm btn-outline-primary">
                                                    <i class="ri-eye-line"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        @if($customer->bookings->count() > 10)
                            <div class="text-center mt-3">
                                <a href="{{ route('admin.bookings.index', ['customer_id' => $customer->id]) }}" class="btn btn-outline-primary">
                                    View All {{ $customer->bookings->count() }} Bookings
                                </a>
                            </div>
                        @endif
                    @else
                        <div class="text-center py-5">
                            <i class="ri-calendar-check-line" style="font-size: 48px; color: #ccc;"></i>
                            <p class="text-muted mt-3">No bookings found for this customer.</p>
                            <a href="{{ route('admin.bookings.create', ['customer_id' => $customer->id]) }}" class="btn btn-primary">
                                <i class="ri-add-line me-1"></i>Create Booking
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Payments Tab -->
        <div class="tab-pane fade" id="payments" role="tabpanel">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="ri-wallet-line me-2"></i>Payment History</h5>
                </div>
                <div class="card-body">
                    @if($customer->payments && $customer->payments->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Date</th>
                                        <th>Amount</th>
                                        <th>Payment Method</th>
                                        <th>Status</th>
                                        <th>Reference</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($customer->payments->take(10) as $payment)
                                        <tr>
                                            <td>{{ $payment->created_at->format('M d, Y') }}</td>
                                            <td><strong>${{ number_format($payment->amount ?? 0, 2) }}</strong></td>
                                            <td>{{ ucfirst(str_replace('_', ' ', $payment->payment_method ?? 'N/A')) }}</td>
                                            <td>
                                                @php
                                                    $statusClass = match($payment->status ?? 'pending') {
                                                        'completed' => 'success',
                                                        'pending' => 'warning',
                                                        'failed' => 'danger',
                                                        default => 'secondary'
                                                    };
                                                @endphp
                                                <span class="badge bg-label-{{ $statusClass }}">{{ ucfirst($payment->status ?? 'Pending') }}</span>
                                            </td>
                                            <td><small>{{ $payment->transaction_reference ?? 'N/A' }}</small></td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="ri-wallet-line" style="font-size: 48px; color: #ccc;"></i>
                            <p class="text-muted mt-3">No payment history found.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Documents Tab -->
        <div class="tab-pane fade" id="documents" role="tabpanel">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="ri-file-line me-2"></i>Travel Documents</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <div class="card border">
                                <div class="card-body">
                                    <h6><i class="ri-passport-line me-2"></i>Passport</h6>
                                    <p class="mb-1"><strong>Number:</strong> {{ $customer->passport_number ?? 'Not provided' }}</p>
                                    <p class="mb-0"><strong>Expiry:</strong> {{ $customer->passport_expiry ? $customer->passport_expiry->format('M d, Y') : 'Not provided' }}</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="card border">
                                <div class="card-body">
                                    <h6><i class="ri-file-upload-line me-2"></i>Upload Documents</h6>
                                    <p class="text-muted">Document upload feature coming soon</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Communication Tab -->
        <div class="tab-pane fade" id="communication" role="tabpanel">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><i class="ri-message-line me-2"></i>Communication Log</h5>
                    <a href="{{ route('admin.customers.messages', ['customer_id' => $customer->id]) }}" class="btn btn-sm btn-primary">
                        <i class="ri-add-line me-1"></i>Send Message
                    </a>
                </div>
                <div class="card-body">
                    @if($customer->messages && $customer->messages->count() > 0)
                        <div class="timeline">
                            @foreach($customer->messages->take(10) as $message)
                                <div class="timeline-item mb-3">
                                    <div class="card">
                                        <div class="card-body">
                                            <div class="d-flex justify-content-between">
                                                <div>
                                                    <h6 class="mb-1">{{ $message->subject ?? 'No Subject' }}</h6>
                                                    <p class="mb-1">{{ $message->message }}</p>
                                                    <small class="text-muted">
                                                        <i class="ri-time-line me-1"></i>{{ $message->created_at->format('M d, Y h:i A') }}
                                                        @if($message->assignedStaff)
                                                            | Assigned to: {{ $message->assignedStaff->name }}
                                                        @endif
                                                    </small>
                                                </div>
                                                <div>
                                                    @php
                                                        $statusClass = match($message->status) {
                                                            'new' => 'primary',
                                                            'open' => 'info',
                                                            'resolved' => 'success',
                                                            'closed' => 'secondary',
                                                            default => 'warning'
                                                        };
                                                    @endphp
                                                    <span class="badge bg-label-{{ $statusClass }}">{{ ucfirst($message->status) }}</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="ri-message-line" style="font-size: 48px; color: #ccc;"></i>
                            <p class="text-muted mt-3">No messages found.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Internal Notes Tab -->
        <div class="tab-pane fade" id="notes" role="tabpanel">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="ri-sticky-note-line me-2"></i>Internal Staff Notes</h5>
                </div>
                <div class="card-body">
                    @if($customer->internal_notes)
                        <div class="alert alert-info">
                            <p class="mb-0">{{ $customer->internal_notes }}</p>
                        </div>
                    @else
                        <p class="text-muted">No internal notes available.</p>
                    @endif
                </div>
            </div>
        </div>

        <!-- Groups Tab -->
        <div class="tab-pane fade" id="groups" role="tabpanel">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><i class="ri-group-line me-2"></i>Customer Groups</h5>
                    <a href="{{ route('admin.customers.edit', $customer->id) }}" class="btn btn-sm btn-primary">
                        <i class="ri-edit-line me-1"></i>Edit Groups
                    </a>
                </div>
                <div class="card-body">
                    @if($customer->customerGroups && $customer->customerGroups->count() > 0)
                        <div class="d-flex flex-wrap gap-2">
                            @foreach($customer->customerGroups as $group)
                                <span class="badge" style="background-color: {{ $group->color ?? '#3ea572' }}; color: white; padding: 8px 16px; font-size: 14px;">
                                    <i class="ri-group-line me-1"></i>{{ $group->name }}
                                </span>
                            @endforeach
                        </div>
                    @else
                        <p class="text-muted">Customer is not assigned to any groups.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
