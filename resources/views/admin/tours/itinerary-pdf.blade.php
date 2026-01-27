@extends('pdf.advanced-layout')

@php
    $documentTitle = 'TOUR ITINERARY';
    $documentRef = 'IT-' . $tour->id;
    $documentDate = now()->format('d M Y');
    $mainColor = '#3ea572';
    $darkBlue = '#2d7a5f';
@endphp

@section('content')
<!-- Tour Information Section -->
<div class="section">
    <div class="section-title">Tour Information</div>
    <div class="info-grid">
        <div class="info-row">
            <div class="info-label">Tour Name:</div>
            <div class="info-value"><strong>{{ $tour->name }}</strong></div>
        </div>
        @if($tour->destination)
        <div class="info-row">
            <div class="info-label">Destination:</div>
            <div class="info-value">{{ $tour->destination->name }}</div>
        </div>
        @endif
        @if($tour->duration_days)
        <div class="info-row">
            <div class="info-label">Duration:</div>
            <div class="info-value">{{ $tour->duration_days }} Days / {{ $tour->duration_nights }} Nights</div>
        </div>
        @endif
        @if($tour->start_location)
        <div class="info-row">
            <div class="info-label">Start Location:</div>
            <div class="info-value">{{ $tour->start_location }}</div>
        </div>
        @endif
        @if($tour->end_location)
        <div class="info-row">
            <div class="info-label">End Location:</div>
            <div class="info-value">{{ $tour->end_location }}</div>
        </div>
        @endif
    </div>
</div>

<!-- Itinerary Days -->
@foreach($tour->itineraries as $day)
<div class="section page-break-inside-avoid">
    <div class="section-title">Day {{ $day->day_number }} – {{ $day->title }}</div>
    
    <div class="billing-box" style="margin-top: 15px;">
        @if($day->location)
        <div style="margin-bottom: 8px;">
            <strong>Location:</strong> {{ $day->location }}
        </div>
        @endif

        @if($day->short_summary)
        <div style="margin: 10px 0; font-style: italic; color: #666;">
            {{ $day->short_summary }}
        </div>
        @endif

        @if($day->description)
        <div style="margin: 10px 0; line-height: 1.8;">
            {!! nl2br(e($day->description)) !!}
        </div>
        @endif

        @if($day->meals_included && count($day->meals_included) > 0)
        <div style="margin-top: 10px; margin-bottom: 8px;">
            <strong>Meals:</strong> <span style="color: {{ $mainColor }}; font-weight: bold;">{{ implode(', ', $day->meals_included) }}</span>
        </div>
        @endif

        @if($day->accommodation_name)
        <div style="background: #f9fafb; padding: 12px; border-radius: 5px; margin-top: 15px; border-left: 3px solid {{ $mainColor }};">
            <div class="section-subtitle">Accommodation</div>
            <div class="info-grid">
                <div class="info-row">
                    <div class="info-label">Name:</div>
                    <div class="info-value">{{ $day->accommodation_name }}</div>
                </div>
                @if($day->accommodation_type)
                <div class="info-row">
                    <div class="info-label">Type:</div>
                    <div class="info-value">{{ $day->accommodation_type }}</div>
                </div>
                @endif
                @if($day->accommodation_location)
                <div class="info-row">
                    <div class="info-label">Location:</div>
                    <div class="info-value">{{ $day->accommodation_location }}</div>
                </div>
                @endif
                @if($day->accommodation_rating)
                <div class="info-row">
                    <div class="info-label">Rating:</div>
                    <div class="info-value">{{ $day->accommodation_rating }} Stars</div>
                </div>
                @endif
            </div>
        </div>
        @endif

        @if($day->activities && count($day->activities) > 0)
        <div style="margin-top: 15px;">
            <div class="section-subtitle">Activities</div>
            <div style="display: flex; flex-wrap: wrap; gap: 8px; margin-top: 8px;">
                @foreach($day->activities as $activity)
                <span style="background: #e9ecef; padding: 5px 12px; border-radius: 15px; font-size: 11px; color: #495057;">
                    {{ is_array($activity) ? ($activity['name'] ?? $activity) : $activity }}
                </span>
                @endforeach
            </div>
        </div>
        @endif

        @if($day->vehicle_type)
        <div style="margin-top: 10px;">
            <strong>Transport:</strong> {{ $day->vehicle_type }}
        </div>
        @endif
    </div>
</div>
@endforeach

<!-- Additional Information -->
@if($tour->included || $tour->excluded)
<div class="section">
    <div class="section-title">What's Included & Excluded</div>
    <div style="display: flex; gap: 20px; flex-wrap: wrap;">
        @if($tour->included)
        <div style="flex: 1; min-width: 250px;">
            <div style="font-weight: bold; color: {{ $mainColor }}; margin-bottom: 8px; font-size: 13px;">✓ Included:</div>
            <div style="font-size: 11px; line-height: 1.8; color: #333;">
                {!! nl2br(e($tour->included)) !!}
            </div>
        </div>
        @endif
        @if($tour->excluded)
        <div style="flex: 1; min-width: 250px;">
            <div style="font-weight: bold; color: #dc2626; margin-bottom: 8px; font-size: 13px;">✗ Excluded:</div>
            <div style="font-size: 11px; line-height: 1.8; color: #333;">
                {!! nl2br(e($tour->excluded)) !!}
            </div>
        </div>
        @endif
    </div>
</div>
@endif
@endsection
