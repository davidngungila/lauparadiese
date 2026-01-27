@extends('pdf.advanced-layout')

@php
    $documentTitle = 'DAILY DEPARTURE MANIFEST';
    $documentRef = 'DM-' . str_replace('-', '', $date);
    $documentDate = Carbon\Carbon::parse($date)->format('d M Y');
    $mainColor = '#3ea572';
    $darkBlue = '#2d7a5f';
@endphp

@section('content')
<!-- Manifest Header -->
<div style="text-align: center; margin-bottom: 30px; padding: 20px; background: #f8f9fa; border-radius: 10px;">
    <h1 style="color: {{ $mainColor }}; margin: 0 0 10px 0; font-size: 24pt;">DAILY DEPARTURE MANIFEST</h1>
    <p style="font-size: 14pt; color: #2d7a47; margin: 0; font-weight: bold;">{{ Carbon\Carbon::parse($date)->format('l, F d, Y') }}</p>
    <p style="font-size: 10pt; color: #666; margin-top: 5px;">Total Departures: {{ $bookings->count() }}</p>
</div>

<!-- Summary Statistics -->
<div class="billing-section">
    <div class="billing-box">
        <div class="billing-title">Summary</div>
        <div class="billing-content">
            <p><strong>Total Bookings:</strong> {{ $bookings->count() }}</p>
            <p><strong>Total Travelers:</strong> {{ $bookings->sum('travelers') }}</p>
            <p><strong>Total Revenue:</strong> {{ $bookings->sum('total_price') }}</p>
        </div>
    </div>
    <div class="billing-box">
        <div class="billing-title">Status Breakdown</div>
        <div class="billing-content">
            <p><strong>Confirmed:</strong> {{ $bookings->where('status', 'confirmed')->count() }}</p>
            <p><strong>Pending:</strong> {{ $bookings->where('status', 'pending_payment')->count() }}</p>
            <p><strong>Total Tours:</strong> {{ $bookings->pluck('tour_id')->unique()->count() }}</p>
        </div>
    </div>
</div>

<!-- Departure List -->
<div class="section">
    <div class="section-title">Departure List</div>
    <table class="data-table">
        <thead>
            <tr>
                <th style="width: 8%;">#</th>
                <th style="width: 12%;">Booking Ref</th>
                <th style="width: 20%;">Customer Name</th>
                <th style="width: 20%;">Tour</th>
                <th style="width: 10%;">Travelers</th>
                <th style="width: 10%;">Pickup</th>
                <th style="width: 10%;">Status</th>
                <th style="width: 10%;" class="text-right">Amount</th>
            </tr>
        </thead>
        <tbody>
            @forelse($bookings as $index => $booking)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td><strong>{{ $booking->booking_reference }}</strong></td>
                <td>{{ $booking->customer_name }}</td>
                <td>{{ $booking->tour->name ?? 'N/A' }}</td>
                <td class="text-center">{{ $booking->travelers }}</td>
                <td>{{ $booking->pickup_location ?? 'N/A' }}</td>
                <td>
                    <span class="status-badge status-{{ str_replace('_', '-', $booking->status) }}">
                        {{ ucfirst(str_replace('_', ' ', $booking->status)) }}
                    </span>
                </td>
                <td class="text-right">{{ $booking->currency ?? 'USD' }} {{ number_format($booking->total_price, 2) }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="8" style="text-align: center; padding: 20px; color: #999;">No departures scheduled for this date</td>
            </tr>
            @endforelse
        </tbody>
        <tfoot>
            <tr style="background-color: #f9fafb;">
                <td colspan="4" style="font-weight: bold;">TOTAL</td>
                <td class="text-center" style="font-weight: bold;">{{ $bookings->sum('travelers') }}</td>
                <td colspan="2"></td>
                <td class="text-right" style="font-weight: bold; color: {{ $mainColor }};">{{ $bookings->first()->currency ?? 'USD' }} {{ number_format($bookings->sum('total_price'), 2) }}</td>
            </tr>
        </tfoot>
    </table>
</div>

<!-- Notes -->
<div class="notes-section">
    <div class="notes-title">📋 Notes:</div>
    <div class="notes-content">
        <p>This manifest is for internal use only. All bookings listed are scheduled to depart on {{ Carbon\Carbon::parse($date)->format('F d, Y') }}.</p>
        <p>Please ensure all travelers have been contacted and all arrangements are confirmed.</p>
    </div>
</div>
@endsection


