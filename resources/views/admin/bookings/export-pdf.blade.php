@extends('pdf.advanced-layout')

@php
    $documentTitle = 'BOOKINGS EXPORT';
    $documentRef = 'EXP-' . date('Ymd');
    $documentDate = now()->format('d M Y');
    $mainColor = '#3ea572';
    $darkBlue = '#2d7a5f';
@endphp

@section('content')
<div class="section">
    <div class="section-title">Bookings List ({{ count($bookings) }} Total)</div>
    
    <table class="data-table">
        <thead>
            <tr>
                <th style="width: 8%;">#</th>
                <th style="width: 12%;">Reference</th>
                <th style="width: 15%;">Customer</th>
                <th style="width: 20%;">Tour</th>
                <th style="width: 10%;">Date</th>
                <th style="width: 8%;">Travelers</th>
                <th style="width: 12%;">Amount</th>
                <th style="width: 8%;">Status</th>
                <th style="width: 5%;">Payment</th>
            </tr>
        </thead>
        <tbody>
            @foreach($bookings as $index => $booking)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td><strong>{{ $booking->booking_reference }}</strong></td>
                <td>
                    {{ $booking->customer_name }}<br>
                    <small style="color: #666;">{{ $booking->customer_email }}</small>
                </td>
                <td>{{ $booking->tour->name ?? 'N/A' }}</td>
                <td>{{ $booking->departure_date ? $booking->departure_date->format('d M Y') : 'N/A' }}</td>
                <td class="text-center">{{ $booking->travelers }}</td>
                <td class="text-right">
                    <strong>{{ $booking->currency ?? 'USD' }} {{ number_format($booking->total_price, 2) }}</strong>
                </td>
                <td>
                    <span class="status-badge status-{{ str_replace('_', '-', $booking->status) }}">
                        {{ ucfirst(str_replace('_', ' ', $booking->status)) }}
                    </span>
                </td>
                <td>
                    <span class="status-badge status-{{ str_replace('_', '-', $booking->payment_status ?? 'pending') }}">
                        {{ ucfirst(str_replace('_', ' ', $booking->payment_status ?? 'pending')) }}
                    </span>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

<!-- Summary Section -->
<div class="section">
    <div class="section-title">Summary</div>
    <div class="info-grid">
        <div class="info-row">
            <div class="info-label">Total Bookings:</div>
            <div class="info-value"><strong>{{ count($bookings) }}</strong></div>
        </div>
        <div class="info-row">
            <div class="info-label">Total Revenue:</div>
            <div class="info-value"><strong>{{ $bookings->first()->currency ?? 'USD' }} {{ number_format($bookings->sum('total_price'), 2) }}</strong></div>
        </div>
        <div class="info-row">
            <div class="info-label">Confirmed Bookings:</div>
            <div class="info-value">{{ $bookings->where('status', 'confirmed')->count() }}</div>
        </div>
        <div class="info-row">
            <div class="info-label">Pending Bookings:</div>
            <div class="info-value">{{ $bookings->where('status', 'pending')->count() }}</div>
        </div>
    </div>
</div>
@endsection




