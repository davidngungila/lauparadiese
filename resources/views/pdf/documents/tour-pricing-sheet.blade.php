@extends('pdf.advanced-layout')

@php
    $documentTitle = 'TOUR PRICING SHEET';
    $documentRef = $tour->tour_code ?? 'PRICE-' . $tour->id;
    $documentDate = now()->format('d-M-Y');
    $mainColor = '#3ea572';
    $darkBlue = '#2d7a5f';
@endphp

@section('content')
<!-- Pricing Sheet Header -->
<div style="text-align: center; margin-bottom: 30px; padding: 20px; background: #f8f9fa; border-radius: 10px;">
    <h1 style="color: {{ $mainColor }}; margin: 0 0 10px 0; font-size: 24pt;">TOUR PRICING SHEET</h1>
    <p style="font-size: 14pt; color: #666; margin: 0; font-weight: bold;">{{ $tour->name }}</p>
    <p style="font-size: 10pt; color: #999; margin-top: 5px;">Valid: {{ now()->format('M Y') }} - {{ now()->addYear()->format('M Y') }}</p>
</div>

<!-- Tour Information -->
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
        <div class="info-row">
            <div class="info-label">Currency:</div>
            <div class="info-value">{{ $tour->currency ?? 'USD' }}</div>
        </div>
    </div>
</div>

<!-- Cost Breakdown -->
<div class="section">
    <div class="section-title">Cost Components (Per Person)</div>
    <table class="data-table">
        <thead>
            <tr>
                <th style="width: 60%;">Component</th>
                <th style="width: 40%;" class="text-right">Amount</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>Park Fees</td>
                <td class="text-right">{{ $tour->currency ?? 'USD' }} {{ number_format(($tour->starting_price ?? $tour->price ?? 0) * 0.15, 2) }}</td>
            </tr>
            <tr>
                <td>Accommodation (Lodge/Camp)</td>
                <td class="text-right">{{ $tour->currency ?? 'USD' }} {{ number_format(($tour->starting_price ?? $tour->price ?? 0) * 0.40, 2) }}</td>
            </tr>
            <tr>
                <td>Transport (4x4 Vehicle)</td>
                <td class="text-right">{{ $tour->currency ?? 'USD' }} {{ number_format(($tour->starting_price ?? $tour->price ?? 0) * 0.20, 2) }}</td>
            </tr>
            <tr>
                <td>Meals (Full Board)</td>
                <td class="text-right">{{ $tour->currency ?? 'USD' }} {{ number_format(($tour->starting_price ?? $tour->price ?? 0) * 0.10, 2) }}</td>
            </tr>
            <tr>
                <td>Guide Fees</td>
                <td class="text-right">{{ $tour->currency ?? 'USD' }} {{ number_format(($tour->starting_price ?? $tour->price ?? 0) * 0.08, 2) }}</td>
            </tr>
            <tr>
                <td>Admin & Profit</td>
                <td class="text-right">{{ $tour->currency ?? 'USD' }} {{ number_format(($tour->starting_price ?? $tour->price ?? 0) * 0.07, 2) }}</td>
            </tr>
        </tbody>
        <tfoot>
            <tr style="background-color: #f9fafb;">
                <td style="font-weight: bold; font-size: 12pt;">TOTAL SELLING PRICE</td>
                <td class="text-right" style="font-weight: bold; font-size: 14pt; color: {{ $mainColor }};">
                    {{ $tour->currency ?? 'USD' }} {{ number_format($tour->starting_price ?? $tour->price ?? 0, 2) }}
                </td>
            </tr>
        </tfoot>
    </table>
</div>

<!-- Agent Commission -->
<div class="section">
    <div class="section-title">Agent Commission</div>
    <div style="padding: 15px; background: #f0f8f4; border-radius: 5px;">
        <div class="info-grid">
            <div class="info-row">
                <div class="info-label">Commission Rate:</div>
                <div class="info-value">10%</div>
            </div>
            <div class="info-row">
                <div class="info-label">Commission Amount (per person):</div>
                <div class="info-value">
                    <strong>{{ $tour->currency ?? 'USD' }} {{ number_format(($tour->starting_price ?? $tour->price ?? 0) * 0.10, 2) }}</strong>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Pricing Notes -->
<div class="notes-section">
    <div class="notes-title">📋 Pricing Notes:</div>
    <div class="notes-content">
        <p>Prices are per person based on double/twin occupancy.</p>
        <p>Single room supplement applies for solo travelers.</p>
        <p>Child rates available (contact for details).</p>
        <p>Prices subject to change without notice. Valid until {{ now()->addYear()->format('M Y') }}.</p>
    </div>
</div>
@endsection


