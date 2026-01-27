@extends('pdf.advanced-layout')

@php
    $documentTitle = 'INCLUSION / EXCLUSION LIST';
    $documentRef = $tour->tour_code ?? 'INC-' . $tour->id;
    $documentDate = now()->format('d-M-Y');
    $mainColor = '#3ea572';
    $darkBlue = '#2d7a5f';
@endphp

@section('content')
<!-- Header -->
<div style="text-align: center; margin-bottom: 30px; padding: 20px; background: #f8f9fa; border-radius: 10px;">
    <h1 style="color: {{ $mainColor }}; margin: 0 0 10px 0; font-size: 24pt;">INCLUSION / EXCLUSION LIST</h1>
    <p style="font-size: 14pt; color: #666; margin: 0; font-weight: bold;">{{ $tour->name }}</p>
</div>

<!-- Included Section -->
<div class="section">
    <div style="background: linear-gradient(135deg, #d1fae5 0%, #a7f3d0 100%); padding: 15px; border-radius: 5px; margin-bottom: 20px;">
        <h2 style="color: #065f46; margin: 0 0 15px 0; font-size: 16pt;">✓ INCLUDED</h2>
        @if($tour->inclusions && count($tour->inclusions) > 0)
        <ul style="font-size: 11pt; line-height: 1.8; margin: 0; padding-left: 20px; color: #065f46;">
            @foreach($tour->inclusions as $inclusion)
            <li>{{ $inclusion }}</li>
            @endforeach
        </ul>
        @elseif($tour->included)
        <div style="font-size: 11pt; line-height: 1.8; color: #065f46;">
            {!! nl2br(e($tour->included)) !!}
        </div>
        @else
        <ul style="font-size: 11pt; line-height: 1.8; margin: 0; padding-left: 20px; color: #065f46;">
            <li>Park entry fees</li>
            <li>Full-board accommodation</li>
            <li>Private 4x4 vehicle</li>
            <li>Professional guide</li>
            <li>Drinking water</li>
            <li>All meals as specified</li>
        </ul>
        @endif
    </div>
</div>

<!-- Excluded Section -->
<div class="section">
    <div style="background: linear-gradient(135deg, #fee2e2 0%, #fecaca 100%); padding: 15px; border-radius: 5px;">
        <h2 style="color: #991b1b; margin: 0 0 15px 0; font-size: 16pt;">✗ EXCLUDED</h2>
        @if($tour->exclusions && count($tour->exclusions) > 0)
        <ul style="font-size: 11pt; line-height: 1.8; margin: 0; padding-left: 20px; color: #991b1b;">
            @foreach($tour->exclusions as $exclusion)
            <li>{{ $exclusion }}</li>
            @endforeach
        </ul>
        @elseif($tour->excluded)
        <div style="font-size: 11pt; line-height: 1.8; color: #991b1b;">
            {!! nl2br(e($tour->excluded)) !!}
        </div>
        @else
        <ul style="font-size: 11pt; line-height: 1.8; margin: 0; padding-left: 20px; color: #991b1b;">
            <li>International flights</li>
            <li>Visas</li>
            <li>Travel insurance</li>
            <li>Tips and gratuities</li>
            <li>Personal purchases</li>
            <li>Optional activities (balloon safari $599, etc.)</li>
        </ul>
        @endif
    </div>
</div>
@endsection


