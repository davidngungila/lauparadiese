@extends('pdf.advanced-layout')

@php
    $documentTitle = 'FINAL INVOICE';
    $documentRef = $invoice->invoice_number;
    $documentDate = $invoice->invoice_date ? $invoice->invoice_date->format('d-M-Y') : now()->format('d-M-Y');
    $mainColor = '#3ea572';
    $darkBlue = '#2d7a5f';
@endphp

@section('content')
<!-- Bill To Section -->
<div class="billing-section">
    <div class="billing-box">
        <div class="billing-title">Bill To:</div>
        <div class="billing-content">
            <p><strong>{{ $invoice->customer_name }}</strong></p>
            @if($invoice->customer_email)
            <p>{{ $invoice->customer_email }}</p>
            @endif
            @if($invoice->customer_phone)
            <p>{{ $invoice->customer_phone }}</p>
            @endif
            @if($invoice->customer_address)
            <p>{{ $invoice->customer_address }}</p>
            @endif
        </div>
    </div>
    <div class="billing-box">
        <div class="billing-title">Invoice Details:</div>
        <div class="billing-content">
            <p><strong>Due Date:</strong> {{ $invoice->due_date ? $invoice->due_date->format('d-M-Y') : 'N/A' }}</p>
            @if($invoice->booking)
            <p><strong>Booking Ref:</strong> {{ $invoice->booking->booking_reference }}</p>
            @endif
            <p><strong>Status:</strong> 
                <span class="status-badge status-{{ str_replace('_', '-', $invoice->status ?? 'draft') }}">
                    {{ ucfirst(str_replace('_', ' ', $invoice->status ?? 'draft')) }}
                </span>
            </p>
        </div>
    </div>
</div>

<!-- Invoice Items -->
<div class="section">
    <div class="section-title">Description</div>
    <table class="data-table">
        <thead>
            <tr>
                <th style="width: 5%;">#</th>
                <th style="width: 60%;">Description</th>
                <th style="width: 15%;" class="text-right">Unit Price</th>
                <th style="width: 20%;" class="text-right">Amount</th>
            </tr>
        </thead>
        <tbody>
            @if($invoice->booking && $invoice->booking->tour)
            <tr>
                <td>1</td>
                <td>
                    <strong>{{ $invoice->booking->tour->name }}</strong>
                    @if($invoice->booking->travelers)
                    <br><small>({{ $invoice->booking->travelers }} {{ Str::plural('pax', $invoice->booking->travelers) }})</small>
                    @endif
                </td>
                <td class="text-right">{{ $invoice->currency ?? 'USD' }} {{ number_format($invoice->subtotal ?? 0, 2) }}</td>
                <td class="text-right"><strong>{{ $invoice->currency ?? 'USD' }} {{ number_format($invoice->subtotal ?? 0, 2) }}</strong></td>
            </tr>
            @else
            <tr>
                <td>1</td>
                <td><strong>Tour & Travel Services</strong></td>
                <td class="text-right">{{ $invoice->currency ?? 'USD' }} {{ number_format($invoice->subtotal ?? 0, 2) }}</td>
                <td class="text-right"><strong>{{ $invoice->currency ?? 'USD' }} {{ number_format($invoice->subtotal ?? 0, 2) }}</strong></td>
            </tr>
            @endif
        </tbody>
    </table>
</div>

<!-- Summary Section -->
<div class="summary-section">
    <div class="summary-box">
        <div class="summary-row">
            <span class="summary-label">Subtotal:</span>
            <span class="summary-value">{{ $invoice->currency ?? 'USD' }} {{ number_format($invoice->subtotal ?? 0, 2) }}</span>
        </div>
        @if($invoice->discount_amount && $invoice->discount_amount > 0)
        <div class="summary-row">
            <span class="summary-label">Discount:</span>
            <span class="summary-value" style="color: #dc2626;">-{{ $invoice->currency ?? 'USD' }} {{ number_format($invoice->discount_amount, 2) }}</span>
        </div>
        @endif
        @if($invoice->tax_amount && $invoice->tax_amount > 0)
        <div class="summary-row">
            <span class="summary-label">Tax:</span>
            <span class="summary-value">{{ $invoice->currency ?? 'USD' }} {{ number_format($invoice->tax_amount, 2) }}</span>
        </div>
        @endif
        <div class="summary-row">
            <span class="summary-label">Total Amount:</span>
            <span class="summary-value total-row">{{ $invoice->currency ?? 'USD' }} {{ number_format($invoice->total_amount ?? 0, 2) }}</span>
        </div>
        @php
            $paidAmount = $invoice->payments()->where('status', 'completed')->sum('amount') ?? 0;
            $balanceDue = ($invoice->total_amount ?? 0) - $paidAmount;
        @endphp
        @if($paidAmount > 0)
        <div class="summary-row">
            <span class="summary-label">Amount Paid:</span>
            <span class="summary-value">{{ $invoice->currency ?? 'USD' }} {{ number_format($paidAmount, 2) }}</span>
        </div>
        @endif
        <div class="summary-row" style="border-top: 2px solid {{ $mainColor }}; padding-top: 12px; margin-top: 5px;">
            <span class="summary-label"><strong>BALANCE DUE:</strong></span>
            <span class="summary-value" style="font-size: 16pt; color: {{ $balanceDue > 0 ? $mainColor : '#28a745' }};">
                <strong>{{ $invoice->currency ?? 'USD' }} {{ number_format($balanceDue, 2) }}</strong>
            </span>
        </div>
    </div>
</div>

@if($balanceDue == 0)
<div style="background-color: #d1fae5; border-left: 4px solid #28a745; padding: 15px; margin: 20px 0; text-align: center;">
    <p style="font-size: 14pt; font-weight: bold; color: #065f46; margin: 0;">✓ INVOICE FULLY PAID</p>
</div>
@endif
@endsection


