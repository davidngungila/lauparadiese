@extends('pdf.layout')

@section('title', 'Invoice ' . ($invoice->invoice_number ?? 'INV-001'))
@section('document-type', 'INVOICE')
@section('document-number', $invoice->invoice_number ?? 'INV-001')
@section('document-date', $invoice->created_at->format('F d, Y') ?? date('F d, Y'))
@section('document-status', $invoice->status ?? 'paid')

@section('content')
<!-- Customer Information -->
<div class="section">
    <div class="section-title">Bill To</div>
    <div class="info-grid">
        <div class="info-row">
            <div class="info-label">Customer Name:</div>
            <div class="info-value">{{ $invoice->customer_name ?? 'Customer Name' }}</div>
        </div>
        <div class="info-row">
            <div class="info-label">Email:</div>
            <div class="info-value">{{ $invoice->customer_email ?? 'customer@example.com' }}</div>
        </div>
        <div class="info-row">
            <div class="info-label">Phone:</div>
            <div class="info-value">{{ $invoice->customer_phone ?? '+255 123 456 789' }}</div>
        </div>
    </div>
</div>

<!-- Invoice Items -->
<div class="section">
    <div class="section-title">Invoice Items</div>
    <table class="data-table">
        <thead>
            <tr>
                <th>Description</th>
                <th class="text-center">Quantity</th>
                <th class="text-right">Unit Price</th>
                <th class="text-right">Total</th>
            </tr>
        </thead>
        <tbody>
            @if(isset($invoice->items) && count($invoice->items) > 0)
                @foreach($invoice->items as $item)
                <tr>
                    <td>{{ $item->description }}</td>
                    <td class="text-center">{{ $item->quantity }}</td>
                    <td class="text-right">${{ number_format($item->unit_price, 2) }}</td>
                    <td class="text-right">${{ number_format($item->quantity * $item->unit_price, 2) }}</td>
                </tr>
                @endforeach
            @else
                <tr>
                    <td colspan="4" class="text-center">No items</td>
                </tr>
            @endif
        </tbody>
    </table>
    
    <div class="summary-box">
        <div class="summary-row">
            <span class="summary-label">Subtotal:</span>
            <span class="summary-value">${{ number_format($invoice->subtotal ?? 0, 2) }}</span>
        </div>
        @if(($invoice->tax ?? 0) > 0)
        <div class="summary-row">
            <span class="summary-label">Tax:</span>
            <span class="summary-value">${{ number_format($invoice->tax, 2) }}</span>
        </div>
        @endif
        @if(($invoice->discount ?? 0) > 0)
        <div class="summary-row">
            <span class="summary-label">Discount:</span>
            <span class="summary-value">-${{ number_format($invoice->discount, 2) }}</span>
        </div>
        @endif
        <div class="summary-row">
            <span class="summary-label">Total Amount:</span>
            <span class="summary-value total-row">${{ number_format($invoice->total ?? 0, 2) }}</span>
        </div>
    </div>
</div>

<!-- Payment Information -->
@if(isset($invoice->payment_method))
<div class="section">
    <div class="section-title">Payment Information</div>
    <div class="info-grid">
        <div class="info-row">
            <div class="info-label">Payment Method:</div>
            <div class="info-value">{{ ucfirst(str_replace('_', ' ', $invoice->payment_method)) }}</div>
        </div>
        <div class="info-row">
            <div class="info-label">Payment Date:</div>
            <div class="info-value">{{ $invoice->paid_at ? \Carbon\Carbon::parse($invoice->paid_at)->format('F d, Y') : 'Pending' }}</div>
        </div>
    </div>
</div>
@endif

<!-- Notes -->
@if(isset($invoice->notes) && $invoice->notes)
<div class="notes-section">
    <div class="notes-title">📝 Notes:</div>
    <div>{!! nl2br(e($invoice->notes)) !!}</div>
</div>
@endif
@endsection

@section('footer-extra')
Thank you for your business! For payment inquiries, please contact our finance department.
@endsection




