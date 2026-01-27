@extends('pdf.advanced-layout')

@php
    $documentTitle = 'GUIDE ASSIGNMENT FORM';
    $documentRef = 'GA-' . str_replace('-', '', $date);
    $documentDate = Carbon\Carbon::parse($date)->format('d-M-Y');
    $mainColor = '#3ea572';
    $darkBlue = '#2d7a5f';
@endphp

@section('content')
<!-- Guide Assignment Header -->
<div style="text-align: center; margin-bottom: 30px; padding: 20px; background: #f8f9fa; border-radius: 10px;">
    <h1 style="color: {{ $mainColor }}; margin: 0 0 10px 0; font-size: 24pt;">GUIDE ASSIGNMENT</h1>
    <p style="font-size: 12pt; color: #666; margin: 0; font-weight: bold;">Date: {{ Carbon\Carbon::parse($date)->format('l, F d, Y') }}</p>
</div>

<!-- Guide Assignments -->
@forelse($bookings as $booking)
@php
    $guide = $booking->assignedStaff ?? $booking->tourOperations->first()->guide ?? null;
@endphp
<div class="section" style="page-break-inside: avoid; margin-bottom: 25px; border: 1px solid #ddd; padding: 20px; border-radius: 5px;">
    <div style="background: #f0f8f4; padding: 12px; border-left: 4px solid {{ $mainColor }}; margin-bottom: 15px;">
        <div style="font-weight: bold; color: {{ $mainColor }}; font-size: 14pt;">
            Guide: {{ $guide->name ?? 'TBA' }}
        </div>
    </div>
    
    <div class="info-grid">
        <div class="info-row">
            <div class="info-label">Tour:</div>
            <div class="info-value"><strong>{{ $booking->tour->name ?? 'N/A' }}</strong></div>
        </div>
        <div class="info-row">
            <div class="info-label">Booking Reference:</div>
            <div class="info-value">{{ $booking->booking_reference }}</div>
        </div>
        <div class="info-row">
            <div class="info-label">Client:</div>
            <div class="info-value">{{ $booking->customer_name }} ({{ $booking->travelers }} {{ Str::plural('pax', $booking->travelers) }})</div>
        </div>
        <div class="info-row">
            <div class="info-label">Client Type:</div>
            <div class="info-value">{{ $booking->assignedStaff ? 'Agent Booking' : 'Direct Booking' }}</div>
        </div>
    </div>
    
    <!-- Key Notes -->
    <div style="margin-top: 15px; padding: 15px; background: #fff3cd; border-radius: 5px;">
        <div style="font-weight: bold; color: #856404; margin-bottom: 10px;">Key Notes:</div>
        <ul style="font-size: 10pt; line-height: 1.8; margin: 0; padding-left: 20px; color: #856404;">
            @if($booking->special_requirements)
            <li>{{ $booking->special_requirements }}</li>
            @endif
            @if($booking->notes)
            <li>{{ $booking->notes }}</li>
            @endif
            <li>Collect packed lunch from HQ</li>
            <li>Park fees paid, tickets in envelope</li>
        </ul>
    </div>
    
    <!-- Handover Section -->
    <div style="margin-top: 20px; padding-top: 15px; border-top: 1px solid #ddd;">
        <div style="font-weight: bold; color: {{ $mainColor }}; margin-bottom: 10px;">Handover Checklist:</div>
        <table style="width: 100%; font-size: 10pt;">
            <tr>
                <td style="width: 50%;">☐ Park tickets collected</td>
                <td>☐ Vehicle inspection completed</td>
            </tr>
            <tr>
                <td>☐ Packed lunch collected</td>
                <td>☐ Client contact details confirmed</td>
            </tr>
            <tr>
                <td>☐ Emergency contact numbers noted</td>
                <td>☐ Special requirements reviewed</td>
            </tr>
        </table>
    </div>
</div>
@empty
<div style="text-align: center; padding: 40px; color: #999;">
    <p>No guide assignments for this date</p>
</div>
@endforelse
@endsection


