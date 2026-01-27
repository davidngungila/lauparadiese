@extends('pdf.advanced-layout')

@php
    $documentTitle = 'INTERNAL BOOKING SHEET';
    $documentRef = 'BS-' . $booking->booking_reference;
    $documentDate = now()->format('d M Y');
    $mainColor = '#3ea572';
    $darkBlue = '#2d7a5f';
@endphp

@section('content')
<!-- Booking Sheet Header -->
<div style="text-align: center; margin-bottom: 30px; padding: 25px; background: linear-gradient(135deg, #f0f8f4 0%, #e8f5e9 100%); border-radius: 10px; border: 2px solid {{ $mainColor }};">
    <div style="font-size: 28pt; font-weight: bold; color: {{ $mainColor }}; margin-bottom: 10px;">📋 INTERNAL BOOKING SHEET</div>
    <div style="font-size: 14pt; color: {{ $darkBlue }}; font-weight: bold;">Booking ID: {{ $booking->id }} | Ref: {{ $booking->booking_reference }}</div>
    <div style="font-size: 11pt; color: #666; margin-top: 8px;">Generated on {{ now()->format('F d, Y \a\t H:i') }}</div>
</div>

<!-- Booking Overview Boxes -->
<div class="billing-section">
    <div class="billing-box">
        <div class="billing-title">Booking Status</div>
        <div class="billing-content">
            <p><strong>Status:</strong> 
                <span class="status-badge status-{{ str_replace('_', '-', $booking->status) }}">
                    {{ ucfirst(str_replace('_', ' ', $booking->status)) }}
                </span>
            </p>
            <p><strong>Payment Status:</strong> 
                <span class="status-badge status-{{ str_replace('_', '-', $booking->payment_status ?? 'pending') }}">
                    {{ ucfirst(str_replace('_', ' ', $booking->payment_status ?? 'pending')) }}
                </span>
            </p>
            <p><strong>Approval Status:</strong> 
                <span class="status-badge status-{{ str_replace('_', '-', $booking->approval_status ?? 'pending') }}">
                    {{ ucfirst(str_replace('_', ' ', $booking->approval_status ?? 'pending')) }}
                </span>
            </p>
            @if($booking->confirmed_at)
            <p><strong>Confirmed:</strong> {{ $booking->confirmed_at->format('M d, Y H:i') }}</p>
            @endif
        </div>
    </div>
    <div class="billing-box">
        <div class="billing-title">Financial Summary</div>
        <div class="billing-content">
            <p><strong>Total Price:</strong> {{ $booking->currency ?? 'USD' }} {{ number_format($booking->total_price, 2) }}</p>
            <p><strong>Amount Paid:</strong> {{ $booking->currency ?? 'USD' }} {{ number_format($booking->amount_paid ?? 0, 2) }}</p>
            <p><strong>Balance Due:</strong> 
                <strong style="color: {{ ($booking->balance_amount ?? 0) > 0 ? '#dc2626' : '#28a745' }};">
                    {{ $booking->currency ?? 'USD' }} {{ number_format($booking->balance_amount ?? 0, 2) }}
                </strong>
            </p>
            @if($booking->deposit_amount)
            <p><strong>Deposit:</strong> {{ $booking->currency ?? 'USD' }} {{ number_format($booking->deposit_amount, 2) }}</p>
            @endif
        </div>
    </div>
</div>

<!-- Main Booking Details Table -->
<div class="section">
    <div class="section-title">Booking Details</div>
    <table class="data-table">
        <thead>
            <tr>
                <th style="width: 25%;">Field</th>
                <th style="width: 75%;">Details</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td><strong>Booking ID</strong></td>
                <td><strong style="color: {{ $mainColor }};">#{{ $booking->id }}</strong></td>
            </tr>
            <tr>
                <td><strong>Booking Reference</strong></td>
                <td><strong style="color: {{ $mainColor }};">{{ $booking->booking_reference }}</strong></td>
            </tr>
            <tr>
                <td><strong>Booking Date</strong></td>
                <td>{{ $booking->created_at->format('F d, Y H:i') }}</td>
            </tr>
            <tr>
                <td><strong>Booking Source</strong></td>
                <td>{{ ucfirst(str_replace('_', ' ', $booking->booking_source ?? 'manual')) }}</td>
            </tr>
            <tr>
                <td><strong>Client Name</strong></td>
                <td><strong>{{ $booking->customer_name }}</strong></td>
            </tr>
            <tr>
                <td><strong>Tour Package</strong></td>
                <td>
                    <strong style="color: {{ $mainColor }};">{{ $booking->tour->name ?? 'N/A' }}</strong>
                    @if($booking->tour && $booking->tour->tour_code)
                        <span style="color: #666;">({{ $booking->tour->tour_code }})</span>
                    @endif
                </td>
            </tr>
            <tr>
                <td><strong>Departure Date</strong></td>
                <td>
                    <strong>{{ $booking->departure_date ? $booking->departure_date->format('l, F d, Y') : 'N/A' }}</strong>
                </td>
            </tr>
            @if($booking->travel_end_date)
            <tr>
                <td><strong>Return Date</strong></td>
                <td><strong>{{ $booking->travel_end_date->format('l, F d, Y') }}</strong></td>
            </tr>
            @endif
            <tr>
                <td><strong>Number of Travelers</strong></td>
                <td>
                    <strong>{{ $booking->travelers }} {{ $booking->travelers == 1 ? 'Adult' : 'Adults' }}</strong>
                    @if($booking->number_of_children && $booking->number_of_children > 0)
                        + {{ $booking->number_of_children }} {{ $booking->number_of_children == 1 ? 'Child' : 'Children' }}
                    @endif
                </td>
            </tr>
            @if($booking->accommodation_level)
            <tr>
                <td><strong>Accommodation Level</strong></td>
                <td>{{ ucfirst(str_replace('_', ' ', $booking->accommodation_level)) }}</td>
            </tr>
            @endif
            <tr>
                <td><strong>Assigned Agent/Staff</strong></td>
                <td>
                    @if($booking->assignedStaff)
                        <strong>{{ $booking->assignedStaff->name }}</strong>
                        @if($booking->assignedStaff->email)
                            <span style="color: #666;">({{ $booking->assignedStaff->email }})</span>
                        @endif
                    @else
                        <span style="color: #999;">Direct Booking / Not Assigned</span>
                    @endif
                </td>
            </tr>
            @if($booking->pickup_location)
            <tr>
                <td><strong>Pickup Location</strong></td>
                <td>{{ $booking->pickup_location }}</td>
            </tr>
            @endif
            @if($booking->dropoff_location)
            <tr>
                <td><strong>Drop-off Location</strong></td>
                <td>{{ $booking->dropoff_location }}</td>
            </tr>
            @endif
        </tbody>
    </table>
</div>

<!-- Customer Contact Information -->
<div class="section">
    <div class="section-title">Customer Contact Information</div>
    <div class="billing-section">
        <div class="billing-box">
            <div class="billing-title">Contact Details</div>
            <div class="billing-content">
                @if($booking->customer_email)
                <p><strong>Email:</strong> {{ $booking->customer_email }}</p>
                @endif
                @if($booking->customer_phone)
                <p><strong>Phone:</strong> {{ $booking->customer_phone }}</p>
                @endif
                @if($booking->customer_country)
                <p><strong>Country:</strong> {{ $booking->customer_country }}</p>
                @endif
                @if($booking->city)
                <p><strong>City:</strong> {{ $booking->city }}</p>
                @endif
                @if($booking->passport_number)
                <p><strong>Passport Number:</strong> {{ $booking->passport_number }}</p>
                @endif
            </div>
        </div>
        <div class="billing-box">
            <div class="billing-title">Emergency Contact</div>
            <div class="billing-content">
                @if($booking->emergency_contact_name)
                <p><strong>Name:</strong> {{ $booking->emergency_contact_name }}</p>
                @endif
                @if($booking->emergency_contact_phone)
                <p><strong>Phone:</strong> {{ $booking->emergency_contact_phone }}</p>
                @endif
                @if(!$booking->emergency_contact_name && !$booking->emergency_contact_phone)
                <p style="color: #999;">Not provided</p>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Operations Information -->
@if($booking->tourOperations && $booking->tourOperations->count() > 0)
<div class="section">
    <div class="section-title">Operations Assignment</div>
    <table class="data-table">
        <thead>
            <tr>
                <th>Item</th>
                <th>Assigned Resource</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($booking->tourOperations as $operation)
            <tr>
                <td><strong>Guide</strong></td>
                <td>
                    @if($operation->guide)
                        {{ $operation->guide->name }}
                        @if($operation->guide->phone)
                            <span style="color: #666;">({{ $operation->guide->phone }})</span>
                        @endif
                    @else
                        <span style="color: #999;">TBA - To Be Assigned</span>
                    @endif
                </td>
                <td>
                    <span class="status-badge status-{{ $operation->guide ? 'confirmed' : 'pending' }}">
                        {{ $operation->guide ? 'Assigned' : 'Pending' }}
                    </span>
                </td>
            </tr>
            <tr>
                <td><strong>Vehicle</strong></td>
                <td>
                    @if($operation->vehicle)
                        {{ $operation->vehicle->registration_number ?? 'N/A' }}
                        @if($operation->vehicle->make && $operation->vehicle->model)
                            <span style="color: #666;">({{ $operation->vehicle->make }} {{ $operation->vehicle->model }})</span>
                        @endif
                    @else
                        <span style="color: #999;">TBA - To Be Assigned</span>
                    @endif
                </td>
                <td>
                    <span class="status-badge status-{{ $operation->vehicle ? 'confirmed' : 'pending' }}">
                        {{ $operation->vehicle ? 'Assigned' : 'Pending' }}
                    </span>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@else
<div class="section">
    <div class="section-title">Operations Assignment</div>
    <div style="padding: 15px; background: #f9fafb; border-radius: 5px; border-left: 4px solid #f59e0b;">
        <p style="margin: 0; color: #666; font-size: 11px;">
            <strong>⚠️ Operations not yet assigned:</strong> Guide and vehicle assignments are pending. Please assign resources before departure.
        </p>
    </div>
</div>
@endif

<!-- Payment History -->
@if($booking->payments && $booking->payments->count() > 0)
<div class="section">
    <div class="section-title">Payment History</div>
    <table class="data-table">
        <thead>
            <tr>
                <th>Date</th>
                <th>Reference</th>
                <th>Amount</th>
                <th>Method</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($booking->payments as $payment)
            <tr>
                <td>{{ $payment->payment_date ? \Carbon\Carbon::parse($payment->payment_date)->format('M d, Y') : 'N/A' }}</td>
                <td>{{ $payment->payment_reference ?? 'N/A' }}</td>
                <td><strong>{{ $payment->currency ?? $booking->currency ?? 'USD' }} {{ number_format($payment->amount, 2) }}</strong></td>
                <td>{{ ucfirst(str_replace('_', ' ', $payment->payment_method ?? 'N/A')) }}</td>
                <td>
                    <span class="status-badge status-{{ str_replace('_', '-', $payment->status ?? 'completed') }}">
                        {{ ucfirst(str_replace('_', ' ', $payment->status ?? 'completed')) }}
                    </span>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endif

<!-- Special Notes & Requirements -->
@if($booking->special_requirements || $booking->notes)
<div class="section">
    <div class="section-title">Special Notes & Requirements</div>
    <div style="padding: 20px; background: #f9fafb; border-radius: 5px; border-left: 4px solid {{ $mainColor }};">
        @if($booking->special_requirements)
        <div style="margin-bottom: 20px;">
            <div style="font-weight: bold; color: {{ $mainColor }}; margin-bottom: 8px; font-size: 12px;">📝 Special Requirements:</div>
            <div style="font-size: 11px; line-height: 1.8; color: #333;">{!! nl2br(e($booking->special_requirements)) !!}</div>
        </div>
        @endif
        @if($booking->notes)
        <div>
            <div style="font-weight: bold; color: {{ $darkBlue }}; margin-bottom: 8px; font-size: 12px;">📋 Internal Notes:</div>
            <div style="font-size: 11px; line-height: 1.8; color: #333;">{!! nl2br(e($booking->notes)) !!}</div>
        </div>
        @endif
    </div>
</div>
@endif

<!-- Action Items Box -->
<div style="background-color: #dbeafe; border-left: 4px solid #2563eb; padding: 20px; margin: 25px 0; border-radius: 5px;">
    <div style="font-weight: bold; color: #1e40af; margin-bottom: 12px; font-size: 13px;">📋 Action Items & Reminders:</div>
    <div style="font-size: 11px; line-height: 1.8; color: #1e40af;">
        @if(($booking->balance_amount ?? 0) > 0)
        <p style="margin-bottom: 8px;">⚠️ <strong>Balance Due:</strong> {{ $booking->currency ?? 'USD' }} {{ number_format($booking->balance_amount, 2) }} - Follow up on payment</p>
        @endif
        @if(!$booking->tourOperations || $booking->tourOperations->count() == 0)
        <p style="margin-bottom: 8px;">⚠️ <strong>Operations:</strong> Assign guide and vehicle before departure</p>
        @endif
        @if($booking->approval_status == 'pending')
        <p style="margin-bottom: 8px;">⚠️ <strong>Approval:</strong> Booking requires approval</p>
        @endif
        @if($booking->departure_date && $booking->departure_date->isFuture())
        <p style="margin-bottom: 0;">✓ <strong>Departure:</strong> {{ $booking->departure_date->diffForHumans() }} ({{ $booking->departure_date->format('F d, Y') }})</p>
        @endif
    </div>
</div>
@endsection
