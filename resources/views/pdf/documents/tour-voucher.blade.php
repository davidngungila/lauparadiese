@extends('pdf.advanced-layout')

@php
    $documentTitle = 'TOUR VOUCHER';
    $documentRef = $booking->booking_reference;
    $documentDate = $booking->created_at->format('d M Y');
    $mainColor = '#3ea572';
    $darkBlue = '#2d7a5f';
@endphp

@section('content')
<!-- Voucher Header -->
<div style="text-align: center; margin-bottom: 30px; padding: 20px; background: linear-gradient(135deg, #f0f8f4 0%, #e8f5e9 100%); border-radius: 10px;">
    <h1 style="color: {{ $mainColor }}; margin: 0 0 10px 0; font-size: 24pt;">TOUR VOUCHER</h1>
    <p style="font-size: 14pt; color: #2d7a47; margin: 0; font-weight: bold;">{{ $booking->booking_reference }}</p>
    <p style="font-size: 10pt; color: #666; margin-top: 5px;">Valid for: {{ $booking->departure_date ? $booking->departure_date->format('F d, Y') : 'N/A' }}</p>
</div>

<!-- Guest Information -->
<div class="billing-section">
    <div class="billing-box">
        <div class="billing-title">Guest Details</div>
        <div class="billing-content">
            <p><strong>{{ $booking->customer_name }}</strong></p>
            @if($booking->customer_email)
            <p>{{ $booking->customer_email }}</p>
            @endif
            @if($booking->customer_phone)
            <p>{{ $booking->customer_phone }}</p>
            @endif
            <p>Travelers: {{ $booking->travelers }} {{ Str::plural('Person', $booking->travelers) }}</p>
        </div>
    </div>
    <div class="billing-box">
        <div class="billing-title">Service Details</div>
        <div class="billing-content">
            @if($booking->tour)
            <p><strong>{{ $booking->tour->name }}</strong></p>
            @endif
            <p>Departure: {{ $booking->departure_date ? $booking->departure_date->format('F d, Y') : 'N/A' }}</p>
            @if($booking->travel_end_date)
            <p>Return: {{ $booking->travel_end_date->format('F d, Y') }}</p>
            @endif
            @if($booking->pickup_location)
            <p>Pickup: {{ $booking->pickup_location }}</p>
            @endif
        </div>
    </div>
</div>

<!-- Service Information Table -->
@if($booking->tour)
<div class="section">
    <div class="section-title">Service Information</div>
    <table class="data-table">
        <thead>
            <tr>
                <th>Service</th>
                <th>Details</th>
                <th>Date</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td><strong>Tour Package</strong></td>
                <td>{{ $booking->tour->name }}</td>
                <td>{{ $booking->departure_date ? $booking->departure_date->format('M d, Y') : 'N/A' }}</td>
            </tr>
            @if($booking->tour->duration_days)
            <tr>
                <td><strong>Duration</strong></td>
                <td>{{ $booking->tour->duration_days }} Days / {{ $booking->tour->duration_nights }} Nights</td>
                <td>-</td>
            </tr>
            @endif
            @if($booking->addons && count($booking->addons) > 0)
            @foreach($booking->addons as $addon)
            <tr>
                <td><strong>Add-on Service</strong></td>
                <td>{{ is_array($addon) ? ($addon['name'] ?? 'Add-on') : $addon }}</td>
                <td>-</td>
            </tr>
            @endforeach
            @endif
        </tbody>
    </table>
</div>
@endif

<!-- Instructions -->
<div style="background-color: #fff3cd; border-left: 4px solid #ffc107; padding: 15px; margin: 20px 0;">
    <div style="font-weight: bold; color: #856404; margin-bottom: 10px;">📋 Voucher Instructions:</div>
    <div style="font-size: 10px; line-height: 1.8; color: #856404;">
        <p style="margin-bottom: 5px;">1. Present this voucher upon arrival at the service location</p>
        <p style="margin-bottom: 5px;">2. Keep this voucher safe - you may need it for reference</p>
        <p style="margin-bottom: 5px;">3. Contact us immediately if you need to make any changes</p>
        <p>4. This voucher is non-transferable and valid only for the dates specified</p>
    </div>
</div>

<!-- Terms -->
<div class="terms-section">
    <div class="terms-title">Voucher Terms & Conditions:</div>
    <div class="terms-content">
        <p style="margin-bottom: 8px;">This voucher is valid only for the dates and services specified above.</p>
        <p style="margin-bottom: 8px;">Any changes must be requested at least 48 hours before the service date.</p>
        <p style="margin-bottom: 8px;">This voucher is non-refundable and non-transferable.</p>
        <p>Please contact us with your booking reference ({{ $booking->booking_reference }}) for any inquiries.</p>
    </div>
</div>
@endsection

