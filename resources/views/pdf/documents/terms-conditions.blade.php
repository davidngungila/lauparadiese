@extends('pdf.advanced-layout')

@php
    $documentTitle = 'TERMS & CONDITIONS';
    $documentRef = $tour->tour_code ?? 'TC-' . $tour->id;
    $documentDate = now()->format('d-M-Y');
    $mainColor = '#3ea572';
    $darkBlue = '#2d7a5f';
@endphp

@section('content')
<!-- Terms Header -->
<div style="text-align: center; margin-bottom: 30px; padding: 20px; background: #f8f9fa; border-radius: 10px;">
    <h1 style="color: {{ $mainColor }}; margin: 0 0 10px 0; font-size: 24pt;">TERMS & CONDITIONS</h1>
    <p style="font-size: 14pt; color: #666; margin: 0; font-weight: bold;">{{ $tour->name }}</p>
</div>

<!-- Terms Content -->
@if($tour->terms_conditions)
<div class="section">
    <div style="font-size: 11pt; line-height: 1.8; text-align: justify;">
        {!! nl2br(e($tour->terms_conditions)) !!}
    </div>
</div>
@else
<div class="section">
    <div style="font-size: 11pt; line-height: 1.8;">
        <h3 style="color: {{ $mainColor }}; margin-bottom: 15px;">1. Payment Terms</h3>
        <p style="margin-bottom: 15px;">A deposit of 30% is required to confirm your booking. The balance must be paid at least 14 days before departure.</p>
        
        <h3 style="color: {{ $mainColor }}; margin-bottom: 15px;">2. Cancellation Policy</h3>
        <p style="margin-bottom: 15px;">
            <strong>4. Cancellations:</strong> 30+ days before departure: 20% fee. 15-29 days: 50% fee. 0-14 days: 100% fee (no refund).
        </p>
        
        <h3 style="color: {{ $mainColor }}; margin-bottom: 15px;">3. Changes</h3>
        <p style="margin-bottom: 15px;">Changes to bookings are subject to availability and may incur additional charges.</p>
        
        <h3 style="color: {{ $mainColor }}; margin-bottom: 15px;">4. Travel Insurance</h3>
        <p style="margin-bottom: 15px;">We strongly recommend comprehensive travel insurance covering medical expenses, trip cancellation, and personal belongings.</p>
        
        <h3 style="color: {{ $mainColor }}; margin-bottom: 15px;">5. Force Majeure</h3>
        <p style="margin-bottom: 15px;">We are not liable for circumstances beyond our control including natural disasters, political unrest, or pandemics.</p>
        
        <h3 style="color: {{ $mainColor }}; margin-bottom: 15px;">6. Liability</h3>
        <p style="margin-bottom: 15px;">
            Lau Paradise Adventures acts as an agent for suppliers. We are not liable for acts of God, travel delays, or supplier failures.
        </p>
        
        <h3 style="color: {{ $mainColor }}; margin-bottom: 15px;">7. Acceptance</h3>
        <p>By accepting this booking, you agree to our terms and conditions.</p>
    </div>
</div>
@endif

<!-- Cancellation Policy Details -->
@if($tour->cancellation_policy)
<div class="section">
    <div class="section-title">Cancellation Policy</div>
    <div style="font-size: 11pt; line-height: 1.8;">
        {!! nl2br(e($tour->cancellation_policy)) !!}
    </div>
</div>
@endif
@endsection


