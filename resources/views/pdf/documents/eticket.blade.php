@extends('pdf.advanced-layout')

@php
    $documentTitle = 'E-TICKET';
    $documentRef = 'ET-' . $booking->booking_reference;
    $documentDate = now()->format('d-M-Y');
    $mainColor = '#3ea572';
    $darkBlue = '#2d7a5f';
@endphp

@section('content')
<!-- E-Ticket Header -->
<div style="text-align: center; margin-bottom: 30px; padding: 20px; background: linear-gradient(135deg, #e8f5e9 0%, #c8e6c9 100%); border-radius: 10px;">
    <h1 style="color: #2e7d32; margin: 0 0 10px 0; font-size: 24pt;">E-TICKET</h1>
    <p style="font-size: 12pt; color: #1b5e20; margin: 0; font-weight: bold;">Booking Reference: {{ $booking->booking_reference }}</p>
</div>

<!-- Passenger Information -->
<div class="billing-section">
    <div class="billing-box">
        <div class="billing-title">Passenger Details</div>
        <div class="billing-content">
            <p><strong>{{ $booking->customer_name }}</strong></p>
            @if($booking->passport_number)
            <p>Passport: {{ $booking->passport_number }}</p>
            @endif
            @if($booking->customer_email)
            <p>Email: {{ $booking->customer_email }}</p>
            @endif
            @if($booking->customer_phone)
            <p>Phone: {{ $booking->customer_phone }}</p>
            @endif
        </div>
    </div>
    <div class="billing-box">
        <div class="billing-title">Travel Details</div>
        <div class="billing-content">
            @if($booking->tour)
            <p><strong>Route:</strong> {{ $booking->tour->start_location ?? 'N/A' }} to {{ $booking->tour->end_location ?? 'N/A' }}</p>
            @endif
            <p><strong>Date:</strong> {{ $booking->departure_date ? $booking->departure_date->format('d-M-Y') : 'N/A' }}</p>
            <p><strong>Travelers:</strong> {{ $booking->travelers }} {{ Str::plural('Person', $booking->travelers) }}</p>
            @if($booking->pickup_location)
            <p><strong>Pickup:</strong> {{ $booking->pickup_location }}</p>
            @endif
        </div>
    </div>
</div>

<!-- Ticket Information Table -->
<div class="section">
    <div class="section-title">Ticket Information</div>
    <table class="data-table">
        <thead>
            <tr>
                <th>Service</th>
                <th>Date</th>
                <th>Time</th>
                <th>Reference</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td><strong>{{ $booking->tour->name ?? 'Tour Service' }}</strong></td>
                <td>{{ $booking->departure_date ? $booking->departure_date->format('d-M-Y') : 'N/A' }}</td>
                <td>{{ $booking->pickup_time ?? '08:00' }}</td>
                <td>{{ $booking->booking_reference }}</td>
                <td>
                    <span class="status-badge status-{{ str_replace('_', '-', $booking->status) }}">
                        {{ ucfirst(str_replace('_', ' ', $booking->status)) }}
                    </span>
                </td>
            </tr>
        </tbody>
    </table>
</div>

<!-- Important Instructions -->
<div style="background-color: #fff3cd; border-left: 4px solid #ffc107; padding: 15px; margin: 20px 0;">
    <div style="font-weight: bold; color: #856404; margin-bottom: 10px;">📋 Check-in Instructions:</div>
    <div style="font-size: 10pt; line-height: 1.8; color: #856404;">
        <p style="margin-bottom: 5px;">1. Present this e-ticket and valid passport/ID at check-in</p>
        <p style="margin-bottom: 5px;">2. Arrive at least 30 minutes before scheduled departure time</p>
        <p style="margin-bottom: 5px;">3. Keep this e-ticket safe - you may need it for reference</p>
        <p>4. For any changes, contact us immediately using booking reference: {{ $booking->booking_reference }}</p>
    </div>
</div>
@endsection


