@extends('pdf.advanced-layout')

@php
    $documentTitle = 'INVOICE';
    $documentRef = $invoice['invoice_number'];
    $documentDate = $invoice['invoice_date'];
    $mainColor = '#3ea572';
    $darkBlue = '#2d7a5f';
@endphp

@section('content')
<!-- Billing Section -->
<div class="billing-section">
    <div class="billing-box">
        <div class="billing-title">Bill To:</div>
        <div class="billing-content">
            <p><strong>{{ $invoice['customer']['name'] }}</strong></p>
            <p>{{ $invoice['customer']['email'] }}</p>
            <p>{{ $invoice['customer']['phone'] }}</p>
            <p>{{ $invoice['customer']['address'] }}</p>
        </div>
    </div>
    <div class="billing-box">
        <div class="billing-title">Invoice Details:</div>
        <div class="billing-content">
            <p><strong>Due Date:</strong> {{ $invoice['due_date'] }}</p>
            <p><strong>Booking Ref:</strong> {{ $invoice['booking_reference'] }}</p>
            <p><strong>Payment Status:</strong> 
                <span class="status-badge status-{{ strtolower($invoice['payment_status']) }}">
                    {{ ucfirst($invoice['payment_status']) }}
                </span>
            </p>
            <p><strong>Booking Status:</strong> {{ ucfirst(str_replace('_', ' ', $invoice['status'])) }}</p>
        </div>
    </div>
</div>

<!-- Tour Information -->
<div class="section">
    <div class="section-title">Tour Information</div>
    <div class="info-grid">
        <div class="info-row">
            <div class="info-label">Tour Name:</div>
            <div class="info-value"><strong>{{ $invoice['tour']['name'] }}</strong></div>
        </div>
        <div class="info-row">
            <div class="info-label">Destination:</div>
            <div class="info-value">{{ $invoice['tour']['destination'] }}</div>
        </div>
        <div class="info-row">
            <div class="info-label">Start Date:</div>
            <div class="info-value">{{ $invoice['tour']['start_date'] }}</div>
        </div>
        <div class="info-row">
            <div class="info-label">End Date:</div>
            <div class="info-value">{{ $invoice['tour']['end_date'] }}</div>
        </div>
        <div class="info-row">
            <div class="info-label">Duration:</div>
            <div class="info-value">{{ $invoice['tour']['duration'] }} Days</div>
        </div>
    </div>
</div>

<!-- Invoice Items Table -->
<div class="section">
    <div class="section-title">Invoice Items</div>
    <table class="data-table">
        <thead>
            <tr>
                <th style="width: 5%;">#</th>
                <th style="width: 45%;">Description</th>
                <th style="width: 15%;" class="text-center">Quantity</th>
                <th style="width: 20%;" class="text-right">Unit Price</th>
                <th style="width: 15%;" class="text-right">Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach($invoice['line_items'] as $index => $item)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $item['description'] }}</td>
                <td class="text-center">{{ $item['quantity'] }}</td>
                <td class="text-right">${{ number_format($item['unit_price'], 2) }}</td>
                <td class="text-right"><strong>${{ number_format($item['total'], 2) }}</strong></td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

<!-- Summary Section -->
<div class="summary-section">
    <div class="summary-box">
        <div class="summary-row">
            <span class="summary-label">Subtotal:</span>
            <span class="summary-value">${{ number_format($invoice['subtotal'], 2) }}</span>
        </div>
        @if($invoice['discount'] > 0)
        <div class="summary-row">
            <span class="summary-label">Discount:</span>
            <span class="summary-value" style="color: #dc2626;">-${{ number_format($invoice['discount'], 2) }}</span>
        </div>
        @endif
        <div class="summary-row">
            <span class="summary-label">Total Amount:</span>
            <span class="summary-value">${{ number_format($invoice['total'], 2) }}</span>
        </div>
        <div class="summary-row">
            <span class="summary-label">Deposit Paid:</span>
            <span class="summary-value">${{ number_format($invoice['deposit'], 2) }}</span>
        </div>
        <div class="summary-row">
            <span class="summary-label">Balance Due:</span>
            <span class="summary-value" style="color: #dc2626;">${{ number_format($invoice['balance'], 2) }}</span>
        </div>
    </div>
</div>

<!-- Payment Information -->
<div class="notes-section">
    <div class="notes-title">Payment Instructions</div>
    <div class="notes-content">
        <p><strong>Payment Method:</strong> {{ ucfirst(str_replace('_', ' ', $booking->payment_method ?? 'Not specified')) }}</p>
        <p><strong>Deposit Amount:</strong> ${{ number_format($invoice['deposit'], 2) }} (30% of total)</p>
        <p><strong>Balance Due:</strong> ${{ number_format($invoice['balance'], 2) }} - Due before departure date</p>
        <p><strong>Note:</strong> Please complete payment at least 7 days before your departure date to confirm your booking.</p>
    </div>
</div>

<!-- Terms & Conditions -->
@php
    $org = \App\Models\OrganizationSetting::getSettings();
@endphp
@if($org->invoice_terms)
<div class="terms-section">
    <div class="terms-title">Terms & Conditions:</div>
    <div class="terms-content">
        {!! nl2br(e($org->invoice_terms)) !!}
    </div>
</div>
@else
<div class="terms-section">
    <div class="terms-title">Standard Terms & Conditions:</div>
    <div class="terms-content">
        <p style="margin-bottom: 8px;"><strong>1. Payment Terms:</strong> A deposit of 30% is required to confirm your booking. The balance must be paid at least 14 days before departure.</p>
        <p style="margin-bottom: 8px;"><strong>2. Cancellation Policy:</strong> Cancellations made 30+ days before departure receive a full refund minus processing fees. Cancellations 14-30 days before receive 50% refund. No refund for cancellations less than 14 days before departure.</p>
        <p style="margin-bottom: 8px;"><strong>3. Changes:</strong> Changes to bookings are subject to availability and may incur additional charges.</p>
        <p style="margin-bottom: 8px;"><strong>4. Travel Insurance:</strong> We strongly recommend comprehensive travel insurance covering medical expenses, trip cancellation, and personal belongings.</p>
        <p><strong>5. Acceptance:</strong> By accepting this invoice, you agree to our terms and conditions.</p>
    </div>
</div>
@endif
@endsection
