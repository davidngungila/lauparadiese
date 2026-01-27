@extends('pdf.advanced-layout')

@php
    $documentTitle = 'GUIDE BRIEFING NOTES';
    $documentRef = 'GB-' . $booking->booking_reference;
    $documentDate = now()->format('d-M-Y');
    $mainColor = '#3ea572';
    $darkBlue = '#2d7a5f';
@endphp

@section('content')
<!-- Briefing Header -->
<div style="text-align: center; margin-bottom: 30px; padding: 20px; background: #f8f9fa; border-radius: 10px;">
    <h1 style="color: {{ $mainColor }}; margin: 0 0 10px 0; font-size: 24pt;">GUIDE BRIEFING NOTES</h1>
    <p style="font-size: 12pt; color: #666; margin: 0; font-weight: bold;">Booking: {{ $booking->booking_reference }}</p>
</div>

<!-- Client Information -->
<div class="section">
    <div class="section-title">Client Information</div>
    <div class="info-grid">
        <div class="info-row">
            <div class="info-label">Client Name:</div>
            <div class="info-value"><strong>{{ $booking->customer_name }}</strong></div>
        </div>
        <div class="info-row">
            <div class="info-label">Travelers:</div>
            <div class="info-value">{{ $booking->travelers }} {{ Str::plural('pax', $booking->travelers) }}</div>
        </div>
        <div class="info-row">
            <div class="info-label">Tour:</div>
            <div class="info-value">{{ $booking->tour->name ?? 'N/A' }}</div>
        </div>
        <div class="info-row">
            <div class="info-label">Departure Date:</div>
            <div class="info-value">{{ $booking->departure_date ? $booking->departure_date->format('d-M-Y') : 'N/A' }}</div>
        </div>
    </div>
</div>

<!-- Key Notes -->
<div class="section">
    <div class="section-title">Key Notes for Guide</div>
    <div style="padding: 20px; background: #fff3cd; border-left: 4px solid #f59e0b; border-radius: 5px;">
        <ul style="font-size: 11pt; line-height: 1.8; margin: 0; padding-left: 20px; color: #856404;">
            @if($booking->special_requirements)
            <li><strong>Special Requirements:</strong> {{ $booking->special_requirements }}</li>
            @endif
            @if($booking->notes)
            <li><strong>Client Notes:</strong> {{ $booking->notes }}</li>
            @endif
            <li>Collect packed lunch from HQ</li>
            <li>Park fees paid, tickets in envelope</li>
            <li>Vehicle inspection completed</li>
            <li>Emergency contact: {{ $booking->emergency_contact_phone ?? 'N/A' }}</li>
        </ul>
    </div>
</div>

<!-- Handover Checklist -->
<div class="section">
    <div class="section-title">Pre-Departure Checklist</div>
    <table class="data-table">
        <thead>
            <tr>
                <th style="width: 5%;">✓</th>
                <th style="width: 95%;">Item</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td style="text-align: center;">☐</td>
                <td>Park tickets collected and verified</td>
            </tr>
            <tr>
                <td style="text-align: center;">☐</td>
                <td>Packed lunch collected from HQ</td>
            </tr>
            <tr>
                <td style="text-align: center;">☐</td>
                <td>Vehicle inspection completed</td>
            </tr>
            <tr>
                <td style="text-align: center;">☐</td>
                <td>Client contact details confirmed</td>
            </tr>
            <tr>
                <td style="text-align: center;">☐</td>
                <td>Emergency contact numbers noted</td>
            </tr>
            <tr>
                <td style="text-align: center;">☐</td>
                <td>Special requirements reviewed</td>
            </tr>
            <tr>
                <td style="text-align: center;">☐</td>
                <td>Route and itinerary confirmed</td>
            </tr>
        </tbody>
    </table>
</div>
@endsection


