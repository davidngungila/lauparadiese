@extends('pdf.advanced-layout')

@php
    $documentTitle = 'TRANSPORT ALLOCATION SHEET';
    $documentRef = 'TA-' . str_replace('-', '', $date);
    $documentDate = Carbon\Carbon::parse($date)->format('d-M-Y');
    $mainColor = '#3ea572';
    $darkBlue = '#2d7a5f';
@endphp

@section('content')
<!-- Transport Allocation Header -->
<div style="text-align: center; margin-bottom: 30px; padding: 20px; background: #f8f9fa; border-radius: 10px;">
    <h1 style="color: {{ $mainColor }}; margin: 0 0 10px 0; font-size: 24pt;">TRANSPORT ALLOCATION SHEET</h1>
    <p style="font-size: 12pt; color: #666; margin: 0; font-weight: bold;">Date: {{ Carbon\Carbon::parse($date)->format('l, F d, Y') }}</p>
</div>

<!-- Transport Allocation Table -->
<div class="section">
    <table class="data-table">
        <thead>
            <tr>
                <th style="width: 10%;">Date</th>
                <th style="width: 15%;">Vehicle</th>
                <th style="width: 15%;">Driver</th>
                <th style="width: 20%;">Tour/Client</th>
                <th style="width: 20%;">Pick-up</th>
                <th style="width: 20%;">Drop-off</th>
            </tr>
        </thead>
        <tbody>
            @forelse($bookings as $booking)
            @php
                $operation = $booking->tourOperations->first();
                $vehicle = $operation->vehicle ?? null;
                $driver = $operation->driver ?? null;
            @endphp
            <tr>
                <td>{{ $date }}</td>
                <td><strong>{{ $vehicle->registration_number ?? 'TBA' }}</strong></td>
                <td>{{ $driver->name ?? 'TBA' }}</td>
                <td>
                    {{ $booking->customer_name }}<br>
                    <small>{{ $booking->tour->name ?? 'N/A' }}</small>
                </td>
                <td>{{ $booking->pickup_location ?? 'N/A' }}</td>
                <td>{{ $booking->dropoff_location ?? 'N/A' }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="6" style="text-align: center; padding: 20px; color: #999;">No transport allocations for this date</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

<!-- Notes Section -->
<div class="section">
    <div class="section-title">Notes</div>
    <div style="padding: 15px; background: #f9fafb; border-radius: 5px;">
        <p style="font-size: 10pt; line-height: 1.8; margin: 0;">
            This allocation sheet is for internal use. Please ensure all drivers have received their trip manifests and are aware of their assignments.
        </p>
    </div>
</div>
@endsection


