@extends('pdf.advanced-layout')

@php
    $documentTitle = 'SUPPLIER PAYMENT VOUCHER';
    $documentRef = 'PV-' . str_pad($expense->id ?? 1, 6, '0', STR_PAD_LEFT);
    $documentDate = now()->format('d-M-Y');
    $mainColor = '#3ea572';
    $darkBlue = '#2d7a5f';
@endphp

@section('content')
<!-- Payment Voucher Header -->
<div style="text-align: center; margin-bottom: 30px; padding: 20px; background: #f8f9fa; border-radius: 10px;">
    <h1 style="color: {{ $mainColor }}; margin: 0 0 10px 0; font-size: 24pt;">SUPPLIER PAYMENT VOUCHER</h1>
    <p style="font-size: 12pt; color: #666; margin: 0; font-weight: bold;">PV #: PV-{{ str_pad($expense->id ?? 1, 6, '0', STR_PAD_LEFT) }}</p>
    <p style="font-size: 10pt; color: #999; margin-top: 5px;">Date: {{ now()->format('F d, Y') }}</p>
</div>

<!-- Payment Details -->
<div class="billing-section">
    <div class="billing-box">
        <div class="billing-title">Pay To</div>
        <div class="billing-content">
            <p><strong>{{ $expense->supplier_name ?? 'Supplier Name' }}</strong></p>
            @if($expense->supplier_email)
            <p>{{ $expense->supplier_email }}</p>
            @endif
            @if($expense->supplier_phone)
            <p>{{ $expense->supplier_phone }}</p>
            @endif
        </div>
    </div>
    <div class="billing-box">
        <div class="billing-title">Payment Details</div>
        <div class="billing-content">
            <p><strong>Amount:</strong> {{ $expense->currency ?? 'USD' }} {{ number_format($expense->amount ?? 0, 2) }}</p>
            <p><strong>For:</strong> {{ $expense->description ?? 'Service/Product' }}</p>
            @if($expense->booking)
            <p><strong>Booking Ref:</strong> {{ $expense->booking->booking_reference }}</p>
            @endif
            @if($expense->purchase_order_number)
            <p><strong>P.O. Number:</strong> {{ $expense->purchase_order_number }}</p>
            @endif
        </div>
    </div>
</div>

<!-- Payment Method -->
<div class="section">
    <div class="section-title">Payment Method</div>
    <div class="info-grid">
        <div class="info-row">
            <div class="info-label">Payment Method:</div>
            <div class="info-value">{{ ucfirst($expense->payment_method ?? 'Bank Transfer') }}</div>
        </div>
        <div class="info-row">
            <div class="info-label">Status:</div>
            <div class="info-value">
                <span class="status-badge status-{{ str_replace('_', '-', $expense->status ?? 'pending') }}">
                    {{ ucfirst(str_replace('_', ' ', $expense->status ?? 'pending')) }}
                </span>
            </div>
        </div>
    </div>
</div>

<!-- Authorization -->
<div style="margin-top: 40px; padding-top: 20px; border-top: 2px solid #ddd;">
    <div style="display: table; width: 100%;">
        <div style="display: table-cell; width: 50%; vertical-align: top;">
            <p style="margin-bottom: 40px;"><strong>Approved by:</strong></p>
            <p style="border-top: 1px solid #333; padding-top: 5px; margin-top: 40px;">Finance Manager Signature</p>
        </div>
        <div style="display: table-cell; width: 50%; vertical-align: top; text-align: right;">
            <p style="margin-bottom: 40px;"><strong>Paid by:</strong></p>
            <p style="border-top: 1px solid #333; padding-top: 5px; margin-top: 40px;">Accountant Signature</p>
        </div>
    </div>
</div>
@endsection


