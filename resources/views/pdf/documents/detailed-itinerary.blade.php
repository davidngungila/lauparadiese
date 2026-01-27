@extends('pdf.advanced-layout')

@php
    $documentTitle = 'DETAILED ITINERARY';
    $documentRef = $tour->tour_code ?? 'IT-' . $tour->id;
    $documentDate = now()->format('d-M-Y');
    $mainColor = '#3ea572';
    $darkBlue = '#2d7a5f';
@endphp

@section('content')
<!-- Itinerary Header -->
<div style="text-align: center; margin-bottom: 30px; padding: 20px; background: linear-gradient(135deg, #e0f2fe 0%, #bae6fd 100%); border-radius: 10px;">
    <h1 style="color: #0369a1; margin: 0 0 10px 0; font-size: 24pt;">DETAILED ITINERARY</h1>
    <p style="font-size: 14pt; color: #075985; margin: 0; font-weight: bold;">{{ $tour->name }}</p>
    <p style="font-size: 10pt; color: #666; margin-top: 5px;">{{ $tour->duration_days }} Days / {{ $tour->duration_nights }} Nights</p>
</div>

<!-- Day-by-Day Itinerary -->
@if($tour->itineraries && $tour->itineraries->count() > 0)
@foreach($tour->itineraries->sortBy('day_number') as $day)
<div class="section" style="page-break-inside: avoid; margin-bottom: 25px; border: 1px solid #ddd; padding: 20px; border-radius: 5px;">
    <div style="background: linear-gradient(135deg, {{ $mainColor }} 0%, {{ $darkBlue }} 100%); color: white; padding: 12px; border-radius: 5px; margin-bottom: 15px;">
        <h2 style="color: white; margin: 0; font-size: 16pt;">Day {{ $day->day_number }} – {{ $day->title }}</h2>
    </div>
    
    @if($day->location)
    <div style="margin-bottom: 10px;">
        <strong>Location:</strong> {{ $day->location }}
    </div>
    @endif
    
    @if($day->short_summary)
    <div style="margin: 10px 0; font-style: italic; color: #666; font-size: 11pt;">
        {{ $day->short_summary }}
    </div>
    @endif
    
    @if($day->description)
    <div style="margin: 15px 0; line-height: 1.8; font-size: 11pt; text-align: justify;">
        {!! nl2br(e($day->description)) !!}
    </div>
    @endif
    
    <!-- Schedule -->
    <div style="margin: 15px 0; padding: 15px; background: #f9fafb; border-radius: 5px;">
        <div style="font-weight: bold; color: {{ $mainColor }}; margin-bottom: 10px;">Schedule:</div>
        <table style="width: 100%; font-size: 10pt;">
            <tr>
                <td style="width: 20%;"><strong>08:00</strong></td>
                <td>Hotel pick-up and briefing</td>
            </tr>
            @if($day->location)
            <tr>
                <td><strong>10:30</strong></td>
                <td>Drive to {{ $day->location }}</td>
            </tr>
            @endif
            <tr>
                <td><strong>12:30</strong></td>
                <td>Lunch at picnic site</td>
            </tr>
            <tr>
                <td><strong>13:30-17:00</strong></td>
                <td>Game drive / Activities</td>
            </tr>
            <tr>
                <td><strong>18:00</strong></td>
                <td>Check-in at accommodation. Dinner & overnight</td>
            </tr>
        </table>
    </div>
    
    <!-- Meals -->
    @if($day->meals_included && count($day->meals_included) > 0)
    <div style="margin: 15px 0;">
        <strong>Meals Included:</strong> 
        <span style="color: #28a745; font-weight: bold;">{{ implode(', ', $day->meals_included) }}</span>
    </div>
    @endif
    
    <!-- Accommodation -->
    @if($day->accommodation_name)
    <div style="margin: 15px 0; padding: 15px; background: #f0f8f4; border-left: 4px solid {{ $mainColor }}; border-radius: 5px;">
        <div style="font-weight: bold; color: {{ $mainColor }}; margin-bottom: 8px;">Accommodation:</div>
        <div style="font-size: 11pt;">
            <p style="margin: 5px 0;"><strong>{{ $day->accommodation_name }}</strong></p>
            @if($day->accommodation_type)
            <p style="margin: 5px 0;">Type: {{ $day->accommodation_type }}</p>
            @endif
            @if($day->accommodation_location)
            <p style="margin: 5px 0;">Location: {{ $day->accommodation_location }}</p>
            @endif
        </div>
    </div>
    @endif
    
    <!-- Activities -->
    @if($day->activities && count($day->activities) > 0)
    <div style="margin: 15px 0;">
        <div style="font-weight: bold; color: {{ $mainColor }}; margin-bottom: 8px;">Activities:</div>
        <div style="display: flex; flex-wrap: wrap; gap: 8px;">
            @foreach($day->activities as $activity)
            <span style="background: #e9ecef; padding: 5px 12px; border-radius: 15px; font-size: 10pt;">
                {{ is_array($activity) ? ($activity['name'] ?? $activity) : $activity }}
            </span>
            @endforeach
        </div>
    </div>
    @endif
</div>
@endforeach
@else
<div style="text-align: center; padding: 40px; color: #999;">
    <p>No detailed itinerary available for this tour</p>
</div>
@endif
@endsection


