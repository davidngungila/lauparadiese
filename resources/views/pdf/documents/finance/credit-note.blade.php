@extends('pdf.advanced-layout')

@php
    $documentTitle = 'CREDIT NOTE';
    $documentRef = 'CN-' . $invoice->invoice_number;
    $documentDate = now()->format('d-M-Y');
    $mainColor = '#3ea572';
    $darkBlue = '#2d7a5f';
@endphp

@section('content')
<!-- Credit Note Header -->
<div style="text-align: center; margin-bottom: 30px; padding: 20px; background: linear-gradient(135deg, #e8f5e9 0%, #c8e6c9 100%); border-radius: 10px;">
    <h1 style="color: #2e7d32; margin: 0 0 10px 0; font-size: 28pt;">CREDIT NOTE</h1>
    <p style="font-size: 12pt; color: #1b5e20; margin: 0; font-weight: bold;">CN #: CN-{{ str_pad($invoice->id, 6, '0', STR_PAD_LEFT) }}</p>
    <p style="font-size: 10pt; color: #666; margin-top: 5px;">Date: {{ now()->format('F d, Y') }}</p>
</div>

<!-- Credit Note Details -->
<div class="billing-section">
    <div class="billing-box">
        <div class="billing-title">Credit To</div>
        <div class="billing-content">
            <p><strong>{{ $invoice->customer_name }}</strong></p>
            @if($invoice->customer_email)
            <p>{{ $invoice->customer_email }}</p>
            @endif
            @if($invoice->customer_phone)
            <p>{{ $invoice->customer_phone }}</p>
            @endif
        </div>
    </div>
    <div class="billing-box">
        <div class="billing-title">Credit Note Details</div>
        <div class="billing-content">
            <p><strong>Original Invoice:</strong> {{ $invoice->invoice_number }}</p>
            <p><strong>Reason:</strong> {{ $reason ?? 'Cancellation/Amendment' }}</p>
            <p><strong>Date:</strong> {{ now()->format('F d, Y') }}</p>
        </div>
    </div>
</div>

<!-- Credit Amount -->
<div class="section">
    <div class="section-title">Credit Amount</div>
    <div style="text-align: center; padding: 30px; background: #f0f8f4; border-radius: 5px;">
        <p style="font-size: 28pt; font-weight: bold; color: #28a745; margin: 0;">
            {{ $invoice->currency ?? 'USD' }} {{ number_format($creditAmount ?? 0, 2) }}
        </p>
        <p style="font-size: 11pt; color: #666; margin-top: 10px;">
            This amount can be used against future bookings or refunded.
        </p>
    </div>
</div>

<!-- Reason Details -->
@if(isset($reasonDetails))
<div class="section">
    <div class="section-title">Reason for Credit</div>
    <div style="padding: 15px; background: #f9fafb; border-radius: 5px;">
        <p style="font-size: 11pt; line-height: 1.8;">{{ $reasonDetails }}</p>
    </div>
</div>
@endif

<!-- Authorization -->
<div style="margin-top: 40px; padding-top: 20px; border-top: 2px solid #ddd;">
    <div style="display: table; width: 100%;">
        <div style="display: table-cell; width: 50%; vertical-align: top;">
            <p style="margin-bottom: 40px;"><strong>Authorized by:</strong></p>
            <p style="border-top: 1px solid #333; padding-top: 5px; margin-top: 40px;">Finance Manager Signature</p>
        </div>
        <div style="display: table-cell; width: 50%; vertical-align: top; text-align: right;">
            <p style="margin-bottom: 40px;"><strong>Date:</strong> {{ now()->format('F d, Y') }}</p>
            <p style="border-top: 1px solid #333; padding-top: 5px; margin-top: 40px;">Date</p>
        </div>
    </div>
</div>
@endsection


