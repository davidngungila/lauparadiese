@extends('pdf.advanced-layout')

@php
    $documentTitle = 'PARK FEES SUMMARY';
    $documentRef = 'PF-' . str_replace('-', '', $date);
    $documentDate = Carbon\Carbon::parse($date)->format('d-M-Y');
    $mainColor = '#3ea572';
    $darkBlue = '#2d7a5f';
@endphp

@section('content')
<!-- Park Fees Header -->
<div style="text-align: center; margin-bottom: 30px; padding: 20px; background: #f8f9fa; border-radius: 10px;">
    <h1 style="color: {{ $mainColor }}; margin: 0 0 10px 0; font-size: 24pt;">PARK FEES SUMMARY</h1>
    <p style="font-size: 12pt; color: #666; margin: 0; font-weight: bold;">Date: {{ Carbon\Carbon::parse($date)->format('l, F d, Y') }}</p>
</div>

<!-- Park Fees by Booking -->
@forelse($bookings as $booking)
<div class="section" style="page-break-inside: avoid; margin-bottom: 25px; border: 1px solid #ddd; padding: 20px; border-radius: 5px;">
    <div style="background: #f0f8f4; padding: 12px; border-left: 4px solid {{ $mainColor }}; margin-bottom: 15px;">
        <div style="font-weight: bold; color: {{ $mainColor }}; font-size: 12pt;">
            {{ $booking->customer_name }} - {{ $booking->booking_reference }}
        </div>
    </div>
    
    <table class="data-table">
        <thead>
            <tr>
                <th style="width: 40%;">Park</th>
                <th style="width: 20%;" class="text-center">Pax</th>
                <th style="width: 20%;" class="text-right">Rate</th>
                <th style="width: 20%;" class="text-right">Amount</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>Tarangire National Park</td>
                <td class="text-center">{{ $booking->travelers }}</td>
                <td class="text-right">$53</td>
                <td class="text-right"><strong>${{ number_format($booking->travelers * 53, 2) }}</strong></td>
            </tr>
            <tr>
                <td>Serengeti National Park (2 days)</td>
                <td class="text-center">{{ $booking->travelers }}</td>
                <td class="text-right">$74</td>
                <td class="text-right"><strong>${{ number_format($booking->travelers * 74 * 2, 2) }}</strong></td>
            </tr>
            <tr>
                <td>Ngorongoro Conservation Area</td>
                <td class="text-center">{{ $booking->travelers }}</td>
                <td class="text-right">$74 + Vehicle $297</td>
                <td class="text-right"><strong>${{ number_format(($booking->travelers * 74) + 297, 2) }}</strong></td>
            </tr>
        </tbody>
        <tfoot>
            <tr style="background-color: #f9fafb;">
                <td colspan="3" style="font-weight: bold;">TOTAL PAID</td>
                <td class="text-right" style="font-weight: bold; font-size: 12pt; color: {{ $mainColor }};">
                    ${{ number_format(($booking->travelers * 53) + ($booking->travelers * 74 * 2) + (($booking->travelers * 74) + 297), 2) }}
                </td>
            </tr>
        </tfoot>
    </table>
    
    <div style="margin-top: 10px; font-size: 9pt; color: #666; font-style: italic;">
        Receipts in finance file
    </div>
</div>
@empty
<div style="text-align: center; padding: 40px; color: #999;">
    <p>No park fees for this date</p>
</div>
@endforelse
@endsection


