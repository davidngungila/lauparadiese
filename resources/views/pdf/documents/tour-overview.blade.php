@extends('pdf.advanced-layout')

@php
    $documentTitle = 'TOUR OVERVIEW';
    $documentRef = $tour->tour_code ?? 'TOUR-' . $tour->id;
    $documentDate = now()->format('d-M-Y');
    $mainColor = '#3ea572';
    $darkBlue = '#2d7a5f';
@endphp

@section('content')
<!-- Tour Overview Header -->
<div style="text-align: center; margin-bottom: 30px; padding: 20px; background: linear-gradient(135deg, #e0f2fe 0%, #bae6fd 100%); border-radius: 10px;">
    <h1 style="color: #0369a1; margin: 0 0 10px 0; font-size: 24pt;">TOUR OVERVIEW</h1>
    <p style="font-size: 14pt; color: #075985; margin: 0; font-weight: bold;">{{ $tour->name }}</p>
    <p style="font-size: 10pt; color: #666; margin-top: 5px;">Code: {{ $tour->tour_code ?? 'TOUR-' . $tour->id }}</p>
</div>

<!-- Tour Basic Information -->
<div class="section">
    <div class="section-title">Tour Information</div>
    <div class="info-grid">
        <div class="info-row">
            <div class="info-label">Tour Code:</div>
            <div class="info-value"><strong>{{ $tour->tour_code ?? 'TOUR-' . $tour->id }}</strong></div>
        </div>
        <div class="info-row">
            <div class="info-label">Duration:</div>
            <div class="info-value">{{ $tour->duration_days }} Days / {{ $tour->duration_nights }} Nights</div>
        </div>
        @if($tour->destination)
        <div class="info-row">
            <div class="info-label">Destination:</div>
            <div class="info-value">{{ $tour->destination->name }}</div>
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
        @if($tour->tour_type)
        <div class="info-row">
            <div class="info-label">Tour Type:</div>
            <div class="info-value">{{ ucfirst($tour->tour_type) }}</div>
        </div>
        @endif
        @if($tour->max_group_size)
        <div class="info-row">
            <div class="info-label">Max Group Size:</div>
            <div class="info-value">{{ $tour->max_group_size }} {{ Str::plural('Person', $tour->max_group_size) }}</div>
        </div>
        @endif
        @if($tour->difficulty_level)
        <div class="info-row">
            <div class="info-label">Difficulty Level:</div>
            <div class="info-value">{{ ucfirst($tour->difficulty_level) }}</div>
        </div>
        @endif
    </div>
</div>

<!-- Highlights -->
@if($tour->highlights && count($tour->highlights) > 0)
<div class="section">
    <div class="section-title">Highlights</div>
    <ul style="font-size: 11pt; line-height: 1.8; margin: 0; padding-left: 20px;">
        @foreach($tour->highlights as $highlight)
        <li>{{ $highlight }}</li>
        @endforeach
    </ul>
</div>
@endif

<!-- Destinations -->
@if($tour->destinations && $tour->destinations->count() > 0)
<div class="section">
    <div class="section-title">Destinations</div>
    <div style="display: flex; flex-wrap: wrap; gap: 10px;">
        @foreach($tour->destinations as $destination)
        <span style="background: #e0f2fe; padding: 8px 15px; border-radius: 20px; font-size: 10pt; color: #0369a1;">
            {{ $destination->name }}
        </span>
        @endforeach
    </div>
</div>
@endif

<!-- Description -->
@if($tour->description || $tour->long_description)
<div class="section">
    <div class="section-title">Description</div>
    <div style="font-size: 11pt; line-height: 1.8; text-align: justify;">
        {!! nl2br(e($tour->long_description ?? $tour->description ?? '')) !!}
    </div>
</div>
@endif

<!-- Ideal For -->
<div class="section">
    <div class="section-title">Ideal For</div>
    <div style="font-size: 11pt; line-height: 1.8;">
        @if($tour->max_group_size && $tour->max_group_size >= 4)
            <p>✓ Families and groups</p>
        @endif
        @if($tour->difficulty_level == 'easy' || $tour->difficulty_level == 'moderate')
            <p>✓ First-time safari-goers</p>
        @endif
        @if($tour->tour_type == 'photography' || stripos($tour->name ?? '', 'photo') !== false)
            <p>✓ Photographers</p>
        @endif
        <p>✓ Wildlife enthusiasts</p>
        <p>✓ Nature lovers</p>
    </div>
</div>

<!-- Pricing -->
@if($tour->starting_price || $tour->price)
<div class="section">
    <div class="section-title">Starting Price</div>
    <div style="text-align: center; padding: 20px; background: #f0f8f4; border-radius: 5px;">
        <p style="font-size: 24pt; font-weight: bold; color: {{ $mainColor }}; margin: 0;">
            {{ $tour->currency ?? 'USD' }} {{ number_format($tour->starting_price ?? $tour->price ?? 0, 2) }}
        </p>
        <p style="font-size: 10pt; color: #666; margin-top: 5px;">per person</p>
    </div>
</div>
@endif
@endsection


