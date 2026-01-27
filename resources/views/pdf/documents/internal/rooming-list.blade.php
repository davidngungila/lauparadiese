@extends('pdf.advanced-layout')

@php
    $documentTitle = 'ROOMING LIST';
    $documentRef = 'RL-' . str_replace('-', '', $date);
    $documentDate = Carbon\Carbon::parse($date)->format('d-M-Y');
    $mainColor = '#3ea572';
    $darkBlue = '#2d7a5f';
@endphp

@section('content')
<!-- Rooming List Header -->
<div style="text-align: center; margin-bottom: 30px; padding: 20px; background: #f8f9fa; border-radius: 10px;">
    <h1 style="color: {{ $mainColor }}; margin: 0 0 10px 0; font-size: 24pt;">ROOMING LIST</h1>
    <p style="font-size: 12pt; color: #666; margin: 0; font-weight: bold;">Date: {{ Carbon\Carbon::parse($date)->format('l, F d, Y') }}</p>
</div>

<!-- Rooming Details -->
@forelse($bookings as $booking)
<div class="section" style="page-break-inside: avoid; margin-bottom: 20px;">
    <div style="background: #f0f8f4; padding: 12px; border-left: 4px solid {{ $mainColor }}; margin-bottom: 15px;">
        <div style="font-weight: bold; color: {{ $mainColor }}; font-size: 12pt;">
            Booking: {{ $booking->booking_reference }} - {{ $booking->customer_name }}
        </div>
        <div style="font-size: 9pt; color: #666; margin-top: 5px;">
            Tour: {{ $booking->tour->name ?? 'N/A' }} | Travelers: {{ $booking->travelers }}
        </div>
    </div>
    
    <table class="data-table">
        <thead>
            <tr>
                <th style="width: 15%;">Room</th>
                <th style="width: 35%;">Guest Name</th>
                <th style="width: 20%;">Room Type</th>
                <th style="width: 15%;">Meal Plan</th>
                <th style="width: 15%;">Special Notes</th>
            </tr>
        </thead>
        <tbody>
            @if($booking->travelers == 1)
            <tr>
                <td><strong>Room 1</strong></td>
                <td>{{ $booking->customer_name }}</td>
                <td>Single</td>
                <td>Full Board</td>
                <td>
                    @if($booking->special_requirements && stripos($booking->special_requirements, 'vegetarian') !== false)
                        Vegetarian
                    @else
                        -
                    @endif
                </td>
            </tr>
            @else
            <tr>
                <td><strong>Room 1</strong></td>
                <td>{{ $booking->customer_name }} @if($booking->travelers > 1) + {{ $booking->travelers - 1 }} {{ Str::plural('Guest', $booking->travelers - 1) }}@endif</td>
                <td>Double/Twin</td>
                <td>Full Board</td>
                <td>
                    @if($booking->special_requirements && stripos($booking->special_requirements, 'vegetarian') !== false)
                        Vegetarian for {{ $booking->customer_name }}
                    @else
                        -
                    @endif
                </td>
            </tr>
            @endif
        </tbody>
    </table>
</div>
@empty
<div style="text-align: center; padding: 40px; color: #999;">
    <p>No bookings scheduled for this date</p>
</div>
@endforelse
@endsection


