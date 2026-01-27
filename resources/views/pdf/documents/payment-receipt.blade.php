@extends('pdf.advanced-layout')

@php
    $documentTitle = 'PAYMENT RECEIPT';
    $documentRef = 'REC-' . str_pad($payment->id, 6, '0', STR_PAD_LEFT);
    $documentDate = $payment->created_at->format('d M Y');
    $mainColor = '#3ea572';
    $darkBlue = '#2d7a5f';
@endphp

@section('content')
<!-- Receipt Header -->
<div style="text-align: center; margin-bottom: 30px; padding: 20px; background: linear-gradient(135deg, #e8f5e9 0%, #c8e6c9 100%); border-radius: 10px;">
    <h1 style="color: #2e7d32; margin: 0 0 10px 0; font-size: 28pt;">PAYMENT RECEIPT</h1>
    <p style="font-size: 12pt; color: #1b5e20; margin: 0; font-weight: bold;">Receipt No: REC-{{ str_pad($payment->id, 6, '0', STR_PAD_LEFT) }}</p>
    <p style="font-size: 10pt; color: #666; margin-top: 5px;">Date: {{ $payment->created_at->format('F d, Y H:i:s') }}</p>
</div>

<!-- Payment Information -->
<div class="billing-section">
    <div class="billing-box">
        <div class="billing-title">Payment From</div>
        <div class="billing-content">
            @if($payment->booking)
            <p><strong>{{ $payment->booking->customer_name }}</strong></p>
            @if($payment->booking->customer_email)
            <p>{{ $payment->booking->customer_email }}</p>
            @endif
            @if($payment->booking->customer_phone)
            <p>{{ $payment->booking->customer_phone }}</p>
            @endif
            @else
            <p><strong>{{ $payment->customer_name ?? 'N/A' }}</strong></p>
            @endif
        </div>
    </div>
    <div class="billing-box">
        <div class="billing-title">Payment Details</div>
        <div class="billing-content">
            <p><strong>Transaction ID:</strong> {{ $payment->transaction_id ?? 'N/A' }}</p>
            <p><strong>Payment Method:</strong> {{ ucfirst(str_replace('_', ' ', $payment->payment_method ?? 'N/A')) }}</p>
            <p><strong>Status:</strong> 
                <span class="status-badge status-{{ str_replace('_', '-', $payment->status ?? 'pending') }}">
                    {{ ucfirst(str_replace('_', ' ', $payment->status ?? 'pending')) }}
                </span>
            </p>
            @if($payment->booking)
            <p><strong>Booking Reference:</strong> {{ $payment->booking->booking_reference }}</p>
            @endif
        </div>
    </div>
</div>

<!-- Payment Summary -->
<div class="section">
    <div class="section-title">Payment Summary</div>
    <table class="data-table">
        <thead>
            <tr>
                <th>Description</th>
                <th class="text-right">Amount</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>
                    @if($payment->booking && $payment->booking->tour)
                        Payment for: {{ $payment->booking->tour->name }}
                    @else
                        Payment for: {{ $payment->description ?? 'Service Payment' }}
                    @endif
                </td>
                <td class="text-right"><strong>{{ $payment->currency ?? 'USD' }} {{ number_format($payment->amount, 2) }}</strong></td>
            </tr>
        </tbody>
        <tfoot>
            <tr style="background-color: #f9fafb;">
                <td style="font-weight: bold; font-size: 12pt;">Total Paid</td>
                <td class="text-right" style="font-weight: bold; font-size: 14pt; color: {{ $mainColor }};">{{ $payment->currency ?? 'USD' }} {{ number_format($payment->amount, 2) }}</td>
            </tr>
        </tfoot>
    </table>
</div>

<!-- Payment Information -->
@if($payment->booking)
<div class="section">
    <div class="section-title">Related Booking Information</div>
    <div class="info-grid">
        <div class="info-row">
            <div class="info-label">Booking Reference:</div>
            <div class="info-value"><strong>{{ $payment->booking->booking_reference }}</strong></div>
        </div>
        @if($payment->booking->tour)
        <div class="info-row">
            <div class="info-label">Tour:</div>
            <div class="info-value">{{ $payment->booking->tour->name }}</div>
        </div>
        @endif
        @if($payment->booking->departure_date)
        <div class="info-row">
            <div class="info-label">Departure Date:</div>
            <div class="info-value">{{ $payment->booking->departure_date->format('F d, Y') }}</div>
        </div>
        @endif
        <div class="info-row">
            <div class="info-label">Total Booking Amount:</div>
            <div class="info-value">{{ $payment->booking->currency ?? 'USD' }} {{ number_format($payment->booking->total_price, 2) }}</div>
        </div>
    </div>
</div>
@endif

<!-- Receipt Footer -->
<div style="background-color: #f9fafb; padding: 15px; margin: 20px 0; border-radius: 5px; text-align: center;">
    <p style="font-size: 10pt; color: #666; margin: 0;"><strong>Thank you for your payment!</strong></p>
    <p style="font-size: 9pt; color: #999; margin-top: 5px;">This is an official receipt. Please keep it for your records.</p>
</div>
@endsection


