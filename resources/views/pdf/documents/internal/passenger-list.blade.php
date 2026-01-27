@extends('pdf.advanced-layout')

@php
    $documentTitle = 'PASSENGER / GUEST LIST';
    $documentRef = 'PL-' . $booking->booking_reference;
    $documentDate = $booking->departure_date ? $booking->departure_date->format('d-M-Y') : now()->format('d-M-Y');
    $mainColor = '#3ea572';
    $darkBlue = '#2d7a5f';
@endphp

@section('content')
<!-- Guest List Header -->
<div style="text-align: center; margin-bottom: 30px; padding: 20px; background: #f8f9fa; border-radius: 10px;">
    <h1 style="color: {{ $mainColor }}; margin: 0 0 10px 0; font-size: 24pt;">GUEST LIST</h1>
    <p style="font-size: 12pt; color: #666; margin: 0; font-weight: bold;">
        {{ $booking->tour->name ?? 'Tour Package' }} - Departure: {{ $documentDate }}
    </p>
</div>

<!-- Guest List Table -->
<div class="section">
    <table class="data-table">
        <thead>
            <tr>
                <th style="width: 5%;">No.</th>
                <th style="width: 25%;">Guest Name</th>
                <th style="width: 15%;">Nationality</th>
                <th style="width: 20%;">Passport No.</th>
                <th style="width: 15%;">Dietary</th>
                <th style="width: 20%;">Rooming</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td><strong>1</strong></td>
                <td><strong>{{ $booking->customer_name }}</strong></td>
                <td>{{ $booking->customer_country ?? 'N/A' }}</td>
                <td>{{ $booking->passport_number ?? 'N/A' }}</td>
                <td>
                    @if($booking->special_requirements && stripos($booking->special_requirements, 'vegetarian') !== false)
                        Vegetarian
                    @elseif($booking->special_requirements && stripos($booking->special_requirements, 'vegan') !== false)
                        Vegan
                    @else
                        None
                    @endif
                </td>
                <td>
                    @if($booking->travelers == 1)
                        Single
                    @else
                        Double/Twin
                    @endif
                </td>
            </tr>
            @if($booking->travelers > 1)
            @for($i = 2; $i <= $booking->travelers; $i++)
            <tr>
                <td><strong>{{ $i }}</strong></td>
                <td><em>Additional Passenger {{ $i - 1 }}</em></td>
                <td>-</td>
                <td>-</td>
                <td>-</td>
                <td>Shared</td>
            </tr>
            @endfor
            @endif
        </tbody>
        <tfoot>
            <tr style="background-color: #f9fafb;">
                <td colspan="2" style="font-weight: bold;">TOTAL</td>
                <td colspan="4" style="text-align: right; font-weight: bold;">{{ $booking->travelers }} {{ Str::plural('Guest', $booking->travelers) }}</td>
            </tr>
        </tfoot>
    </table>
</div>

<!-- Additional Information -->
<div class="section">
    <div class="section-title">Additional Information</div>
    <div class="info-grid">
        <div class="info-row">
            <div class="info-label">Booking Reference:</div>
            <div class="info-value"><strong>{{ $booking->booking_reference }}</strong></div>
        </div>
        <div class="info-row">
            <div class="info-label">Tour Duration:</div>
            <div class="info-value">
                @if($booking->tour)
                    {{ $booking->tour->duration_days }} Days / {{ $booking->tour->duration_nights }} Nights
                @else
                    N/A
                @endif
            </div>
        </div>
        @if($booking->special_requirements)
        <div class="info-row">
            <div class="info-label">Special Requirements:</div>
            <div class="info-value">{{ $booking->special_requirements }}</div>
        </div>
        @endif
    </div>
</div>
@endsection


