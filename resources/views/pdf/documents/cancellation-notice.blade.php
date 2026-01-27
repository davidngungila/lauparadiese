@extends('pdf.advanced-layout')

@php
    $documentTitle = 'CANCELLATION NOTICE & ACKNOWLEDGEMENT';
    $documentRef = 'CN-' . $booking->booking_reference;
    $documentDate = now()->format('d-M-Y');
    $mainColor = '#3ea572';
    $darkBlue = '#2d7a5f';
@endphp

@section('content')
<!-- Cancellation Header -->
<div style="text-align: center; margin-bottom: 30px; padding: 20px; background: #fee2e2; border-radius: 10px;">
    <h1 style="color: #991b1b; margin: 0 0 10px 0; font-size: 24pt;">CANCELLATION NOTICE</h1>
    <p style="font-size: 12pt; color: #7f1d1d; margin: 0; font-weight: bold;">Booking Reference: {{ $booking->booking_reference }}</p>
</div>

<!-- Client Information -->
<div class="billing-section">
    <div class="billing-box">
        <div class="billing-title">Client Information</div>
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
        <div class="billing-title">Cancellation Details</div>
        <div class="billing-content">
            <p><strong>Original Booking Ref:</strong> {{ $booking->booking_reference }}</p>
            <p><strong>Cancellation Request Date:</strong> {{ $booking->cancelled_at ? $booking->cancelled_at->format('d-M-Y') : now()->format('d-M-Y') }}</p>
            <p><strong>Original Departure Date:</strong> {{ $booking->departure_date ? $booking->departure_date->format('d-M-Y') : 'N/A' }}</p>
        </div>
    </div>
</div>

<!-- Cancellation Reason -->
<div class="section">
    <div class="section-title">Reason for Cancellation</div>
    <div style="padding: 15px; background: #f9fafb; border-radius: 5px;">
        <p style="font-size: 11pt; line-height: 1.8;">
            {{ $booking->cancellation_reason ?? 'Cancellation requested by client.' }}
        </p>
    </div>
</div>

<!-- Cancellation Policy -->
@php
    $daysUntilDeparture = $booking->departure_date ? now()->diffInDays($booking->departure_date, false) : 0;
    $cancellationFee = 0;
    $refundAmount = 0;
    
    if ($daysUntilDeparture > 30) {
        $cancellationFee = $booking->total_price * 0.20; // 20% fee
        $refundAmount = $booking->total_price - $cancellationFee;
    } elseif ($daysUntilDeparture >= 15 && $daysUntilDeparture <= 30) {
        $cancellationFee = $booking->total_price * 0.50; // 50% fee
        $refundAmount = $booking->total_price - $cancellationFee;
    } else {
        $cancellationFee = $booking->total_price; // 100% fee (no refund)
        $refundAmount = 0;
    }
    
    // Use actual refund amount if set
    if ($booking->refund_amount) {
        $refundAmount = $booking->refund_amount;
        $cancellationFee = $booking->total_price - $refundAmount;
    }
@endphp

<div class="section">
    <div class="section-title">Cancellation Fees & Refund</div>
    <div style="background-color: #fef3c7; border-left: 4px solid #f59e0b; padding: 15px; margin: 15px 0;">
        <p style="font-size: 11pt; line-height: 1.8; margin-bottom: 15px;">
            <strong>Based on our Terms & Conditions (clause 4.2), the following fees apply:</strong>
        </p>
        <div style="margin-left: 20px;">
            @if($daysUntilDeparture > 30)
            <p style="margin-bottom: 8px;">✓ Cancellation more than 30 days before departure: <strong>20% forfeit</strong></p>
            @elseif($daysUntilDeparture >= 15 && $daysUntilDeparture <= 30)
            <p style="margin-bottom: 8px;">✓ Cancellation 15-30 days before departure: <strong>50% forfeit</strong></p>
            @else
            <p style="margin-bottom: 8px;">✗ Cancellation less than 15 days before departure: <strong>100% forfeit (no refund)</strong></p>
            @endif
        </div>
    </div>
    
    <table class="data-table" style="margin-top: 20px;">
        <tbody>
            <tr>
                <td style="width: 50%;"><strong>Original Booking Amount:</strong></td>
                <td style="width: 50%; text-align: right;"><strong>{{ $booking->currency ?? 'USD' }} {{ number_format($booking->total_price, 2) }}</strong></td>
            </tr>
            <tr>
                <td><strong>Cancellation Fee:</strong></td>
                <td style="text-align: right; color: #dc2626;">-{{ $booking->currency ?? 'USD' }} {{ number_format($cancellationFee, 2) }}</td>
            </tr>
            <tr style="background-color: #f0f8f4;">
                <td><strong>Amount to be Refunded:</strong></td>
                <td style="text-align: right; font-size: 14pt; color: #28a745;"><strong>{{ $booking->currency ?? 'USD' }} {{ number_format($refundAmount, 2) }}</strong></td>
            </tr>
        </tbody>
    </table>
</div>

<!-- Refund Processing -->
@if($refundAmount > 0)
<div style="background-color: #dbeafe; border-left: 4px solid #2563eb; padding: 15px; margin: 20px 0;">
    <div style="font-weight: bold; color: #1e40af; margin-bottom: 10px;">💰 Refund Processing:</div>
    <div style="font-size: 10pt; line-height: 1.8; color: #1e40af;">
        <p style="margin-bottom: 5px;">Please allow <strong>14 working days</strong> for processing.</p>
        <p style="margin-bottom: 5px;">Refund will be processed to the original payment method.</p>
        <p>You will receive a confirmation email once the refund has been processed.</p>
    </div>
</div>
@endif

<!-- Acknowledgement -->
<div class="section" style="margin-top: 30px;">
    <div style="border: 2px solid #ddd; padding: 20px; border-radius: 5px;">
        <p style="font-size: 11pt; line-height: 1.8; margin-bottom: 20px;">
            I acknowledge that I have read and understood the cancellation policy and agree to the fees and refund amount stated above.
        </p>
        <div style="display: table; width: 100%; margin-top: 30px;">
            <div style="display: table-cell; width: 50%; vertical-align: top;">
                <p style="border-top: 1px solid #333; padding-top: 5px; margin-top: 40px;">Client Signature</p>
            </div>
            <div style="display: table-cell; width: 50%; vertical-align: top; text-align: right;">
                <p style="border-top: 1px solid #333; padding-top: 5px; margin-top: 40px;">Date</p>
            </div>
        </div>
    </div>
</div>
@endsection


