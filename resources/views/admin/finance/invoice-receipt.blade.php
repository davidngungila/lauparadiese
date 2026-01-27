@extends('pdf.advanced-layout')

@php
    $documentTitle = 'INVOICE';
    $documentRef = $invoice->invoice_number;
    $documentDate = $invoice->invoice_date ? $invoice->invoice_date->format('d M Y') : now()->format('d M Y');
    $mainColor = '#3ea572';
    $darkBlue = '#2d7a5f';
@endphp

@section('content')
<!-- Billing Section -->
<div class="billing-section">
    <div class="billing-box">
        <div class="billing-title">Invoice To:</div>
        <div class="billing-content">
            <p><strong>{{ $invoice->customer_name }}</strong></p>
            @if($invoice->customer_address)
            <p>{{ $invoice->customer_address }}</p>
            @endif
            @if($invoice->customer_phone)
            <p>{{ $invoice->customer_phone }}</p>
            @endif
            @if($invoice->customer_email)
            <p>{{ $invoice->customer_email }}</p>
            @endif
        </div>
    </div>
    <div class="billing-box">
        <div class="billing-title">Bill To:</div>
        <div class="billing-content">
            <p><strong>Total Due:</strong> {{ $invoice->currency ?? 'USD' }} {{ number_format($invoice->total_amount ?? 0, 2) }}</p>
            @php
                $org = \App\Models\OrganizationSetting::getSettings();
            @endphp
            @if($org->bank_name)
            <p><strong>Bank Name:</strong> {{ $org->bank_name }}</p>
            @endif
            @if($org->bank_country)
            <p><strong>Country:</strong> {{ $org->bank_country }}</p>
            @endif
            @if($org->iban)
            <p><strong>IBAN:</strong> {{ $org->iban }}</p>
            @endif
            @if($org->swift_code)
            <p><strong>SWIFT Code:</strong> {{ $org->swift_code }}</p>
            @endif
            @if($invoice->due_date)
            <p><strong>Due Date:</strong> {{ $invoice->due_date->format('d M Y') }}</p>
            @endif
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
                <th style="width: 45%;">Item</th>
                <th style="width: 25%;">Description</th>
                <th style="width: 10%;" class="text-center">Qty</th>
                <th style="width: 15%;" class="text-right">Price</th>
            </tr>
        </thead>
        <tbody>
            @if($invoice->booking && $invoice->booking->tour)
            <tr>
                <td>1</td>
                <td class="text-nowrap text-heading">{{ $invoice->booking->tour->name }}</td>
                <td class="text-nowrap">Tour Package</td>
                <td class="text-center">1</td>
                <td class="text-right">{{ $invoice->currency ?? 'USD' }} {{ number_format($invoice->subtotal ?? 0, 2) }}</td>
            </tr>
            @else
            <tr>
                <td>1</td>
                <td class="text-nowrap text-heading">Service</td>
                <td class="text-nowrap">Tour & Travel Services</td>
                <td class="text-center">1</td>
                <td class="text-right">{{ $invoice->currency ?? 'USD' }} {{ number_format($invoice->subtotal ?? 0, 2) }}</td>
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
        @if(($invoice->discount_amount ?? 0) > 0)
        <div class="summary-row">
            <span class="summary-label">Discount:</span>
            <span class="summary-value" style="color: #dc2626;">-{{ $invoice->currency ?? 'USD' }} {{ number_format($invoice->discount_amount, 2) }}</span>
        </div>
        @endif
        @if(($invoice->tax_amount ?? 0) > 0)
        <div class="summary-row">
            <span class="summary-label">Tax:</span>
            <span class="summary-value">{{ $invoice->currency ?? 'USD' }} {{ number_format($invoice->tax_amount, 2) }}</span>
        </div>
        @endif
        <div class="summary-row">
            <span class="summary-label">Total Amount:</span>
            <span class="summary-value">{{ $invoice->currency ?? 'USD' }} {{ number_format($invoice->total_amount ?? 0, 2) }}</span>
        </div>
    </div>
</div>

<!-- Notes Section -->
<div class="notes-section">
    <div class="notes-title">Notes</div>
    <div class="notes-content">
        <p><strong>Salesperson:</strong> {{ $org->organization_name }}</p>
        <p>{{ $invoice->notes ?? 'Thanks for your business! We appreciate your trust in our services.' }}</p>
    </div>
</div>
@endsection
