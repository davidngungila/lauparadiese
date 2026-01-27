@extends('pdf.advanced-layout')

@php
    $documentTitle = 'REFUND RECEIPT';
    $documentRef = 'RFD-' . str_pad($booking->id, 6, '0', STR_PAD_LEFT);
    $documentDate = now()->format('d-M-Y');
    $mainColor = '#3ea572';
    $darkBlue = '#2d7a5f';
@endphp

@section('content')
<!-- Refund Receipt Header -->
<div style="text-align: center; margin-bottom: 30px; padding: 20px; background: linear-gradient(135deg, #e8f5e9 0%, #c8e6c9 100%); border-radius: 10px;">
    <h1 style="color: #2e7d32; margin: 0 0 10px 0; font-size: 28pt;">REFUND RECEIPT</h1>
    <p style="font-size: 12pt; color: #1b5e20; margin: 0; font-weight: bold;">Refund Voucher No: RFD-{{ str_pad($booking->id, 6, '0', STR_PAD_LEFT) }}</p>
    <p style="font-size: 10pt; color: #666; margin-top: 5px;">Date: {{ now()->format('F d, Y H:i:s') }}</p>
</div>

<!-- Refund Information -->
<div class="billing-section">
    <div class="billing-box">
        <div class="billing-title">Refund To</div>
        <div class="billing-content">
            <p><strong>{{ $booking->customer_name }}</strong></p>
            @if($booking->customer_email)
            <p>{{ $booking->customer_email }}</p>
            @endif
            @if($booking->customer_phone)
            <p>{{ $booking->customer_phone }}</p>
            @endif
        </div>
    </div>
    <div class="billing-box">
        <div class="billing-title">Refund Details</div>
        <div class="billing-content">
            <p><strong>Original Invoice:</strong> {{ $booking->booking_reference }}</p>
            <p><strong>Refund Status:</strong> 
                <span class="status-badge status-{{ str_replace('_', '-', $booking->refund_status ?? 'pending') }}">
                    {{ ucfirst(str_replace('_', ' ', $booking->refund_status ?? 'pending')) }}
                </span>
            </p>
            <p><strong>Refund Date:</strong> {{ now()->format('F d, Y') }}</p>
        </div>
    </div>
</div>

<!-- Refund Summary -->
<div class="section">
    <div class="section-title">Refund Summary</div>
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
                    Refund for cancelled booking: {{ $booking->booking_reference }}
                    @if($booking->tour)
                    <br><small>{{ $booking->tour->name }}</small>
                    @endif
                </td>
                <td class="text-right"><strong>{{ $booking->currency ?? 'USD' }} {{ number_format($booking->refund_amount ?? 0, 2) }}</strong></td>
            </tr>
        </tbody>
        <tfoot>
            <tr style="background-color: #f9fafb;">
                <td style="font-weight: bold; font-size: 12pt;">Total Refunded</td>
                <td class="text-right" style="font-weight: bold; font-size: 14pt; color: #28a745;">{{ $booking->currency ?? 'USD' }} {{ number_format($booking->refund_amount ?? 0, 2) }}</td>
            </tr>
        </tfoot>
    </table>
</div>

<!-- Refund Method -->
<div class="section">
    <div class="section-title">Refund Method</div>
    <div class="info-grid">
        <div class="info-row">
            <div class="info-label">Refund Method:</div>
            <div class="info-value">{{ $booking->refund_method ?? 'Bank Transfer' }}</div>
        </div>
        @if($booking->refund_account_details)
        <div class="info-row">
            <div class="info-label">Account Details:</div>
            <div class="info-value">{{ $booking->refund_account_details }}</div>
        </div>
        @endif
        <div class="info-row">
            <div class="info-label">Processing Time:</div>
            <div class="info-value">14 working days</div>
        </div>
    </div>
</div>

<!-- Authorization -->
<div style="margin-top: 40px; padding-top: 20px; border-top: 2px solid #ddd;">
    <div style="display: table; width: 100%;">
        <div style="display: table-cell; width: 50%; vertical-align: top;">
            <p style="margin-bottom: 40px;"><strong>Refund Authorized by:</strong></p>
            <p style="border-top: 1px solid #333; padding-top: 5px; margin-top: 40px;">Manager Signature</p>
        </div>
        <div style="display: table-cell; width: 50%; vertical-align: top; text-align: right;">
            <p style="margin-bottom: 40px;"><strong>Date:</strong> {{ now()->format('F d, Y') }}</p>
            <p style="border-top: 1px solid #333; padding-top: 5px; margin-top: 40px;">Date</p>
        </div>
    </div>
</div>

<!-- Footer Note -->
<div style="background-color: #f9fafb; padding: 15px; margin: 20px 0; border-radius: 5px; text-align: center;">
    <p style="font-size: 10pt; color: #666; margin: 0;"><strong>Thank you for your understanding!</strong></p>
    <p style="font-size: 9pt; color: #999; margin-top: 5px;">This is an official refund receipt. Please keep it for your records.</p>
</div>
@endsection


