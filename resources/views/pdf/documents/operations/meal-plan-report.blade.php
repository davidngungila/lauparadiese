@extends('pdf.advanced-layout')

@php
    $documentTitle = 'MEAL PLAN REPORT';
    $documentRef = 'MP-' . str_replace('-', '', $date);
    $documentDate = Carbon\Carbon::parse($date)->format('d-M-Y');
    $mainColor = '#3ea572';
    $darkBlue = '#2d7a5f';
@endphp

@section('content')
<!-- Meal Plan Header -->
<div style="text-align: center; margin-bottom: 30px; padding: 20px; background: #f8f9fa; border-radius: 10px;">
    <h1 style="color: {{ $mainColor }}; margin: 0 0 10px 0; font-size: 24pt;">MEAL PLAN REPORT</h1>
    <p style="font-size: 12pt; color: #666; margin: 0; font-weight: bold;">Date: {{ Carbon\Carbon::parse($date)->format('l, F d, Y') }}</p>
</div>

<!-- Meal Plans -->
@forelse($bookings as $booking)
<div class="section" style="page-break-inside: avoid; margin-bottom: 25px; border: 1px solid #ddd; padding: 20px; border-radius: 5px;">
    <div style="background: #f0f8f4; padding: 12px; border-left: 4px solid {{ $mainColor }}; margin-bottom: 15px;">
        <div style="font-weight: bold; color: {{ $mainColor }}; font-size: 12pt;">
            {{ $booking->customer_name }} - {{ $booking->booking_reference }}
        </div>
    </div>
    
    <div class="info-grid">
        <div class="info-row">
            <div class="info-label">Lodge/Accommodation:</div>
            <div class="info-value">{{ $booking->accommodation_level ?? 'Standard Lodge' }}</div>
        </div>
        <div class="info-row">
            <div class="info-label">Meal Plan:</div>
            <div class="info-value"><strong>Full Board (FB)</strong></div>
        </div>
        <div class="info-row">
            <div class="info-label">Travelers:</div>
            <div class="info-value">{{ $booking->travelers }} {{ Str::plural('Person', $booking->travelers) }}</div>
        </div>
        @if($booking->special_requirements && (stripos($booking->special_requirements, 'vegetarian') !== false || stripos($booking->special_requirements, 'vegan') !== false))
        <div class="info-row">
            <div class="info-label">Special Dietary:</div>
            <div class="info-value" style="color: #dc2626;">
                <strong>
                    @if(stripos($booking->special_requirements, 'vegetarian') !== false)
                        Vegetarian
                    @elseif(stripos($booking->special_requirements, 'vegan') !== false)
                        Vegan
                    @endif
                    for {{ $booking->customer_name }}
                </strong>
            </div>
        </div>
        @endif
    </div>
    
    @if($booking->notes && stripos($booking->notes, 'birthday') !== false)
    <div style="margin-top: 15px; padding: 12px; background: #fff3cd; border-radius: 5px;">
        <p style="margin: 0; font-size: 10pt; color: #856404;">
            <strong>Special Note:</strong> Inform chef of birthday cake for {{ $booking->customer_name }} on {{ $date }}
        </p>
    </div>
    @endif
</div>
@empty
<div style="text-align: center; padding: 40px; color: #999;">
    <p>No meal plans for this date</p>
</div>
@endforelse
@endsection


