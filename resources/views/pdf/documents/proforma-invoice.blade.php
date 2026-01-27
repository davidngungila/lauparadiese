@extends('pdf.advanced-layout')

@php
    $documentTitle = 'PROFORMA INVOICE';
    $documentRef = 'INV-' . $booking->booking_reference . '-P';
    $documentDate = now()->format('d-M-Y');
    $mainColor = '#3ea572';
    $darkBlue = '#2d7a5f';
    
    // Calculate due date (30 days from now or before departure, whichever is earlier)
    $dueDate = $booking->departure_date 
        ? min(now()->addDays(30), $booking->departure_date->copy()->subDays(1))
        : now()->addDays(30);
@endphp

@section('content')
<!-- Bill To Section -->
<div class="billing-section">
    <div class="billing-box">
        <div class="billing-title">Bill To:</div>
        <div class="billing-content">
            <p><strong>{{ $booking->customer_name }}</strong></p>
            @if($booking->customer_email)
            <p>{{ $booking->customer_email }}</p>
            @endif
            @if($booking->customer_phone)
            <p>{{ $booking->customer_phone }}</p>
            @endif
            @if($booking->customer_country)
            <p>{{ $booking->customer_country }}</p>
            @endif
        </div>
    </div>
    <div class="billing-box">
        <div class="billing-title">Invoice Details:</div>
        <div class="billing-content">
            <p><strong>Due Date:</strong> {{ $dueDate->format('d-M-Y') }}</p>
            <p><strong>Booking Ref:</strong> {{ $booking->booking_reference }}</p>
            <p><strong>Payment Status:</strong> 
                <span class="status-badge status-{{ str_replace('_', '-', $booking->payment_status ?? 'pending') }}">
                    {{ ucfirst(str_replace('_', ' ', $booking->payment_status ?? 'pending')) }}
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
            <tr>
                <td>1</td>
                <td>
                    <strong>{{ $booking->tour->name ?? 'Tour Package' }}</strong>
                    @if($booking->travelers)
                    <br><small>({{ $booking->travelers }} {{ Str::plural('pax', $booking->travelers) }})</small>
                    @endif
                </td>
                <td class="text-right">{{ $booking->currency ?? 'USD' }} {{ number_format($booking->total_price / max($booking->travelers, 1), 2) }}</td>
                <td class="text-right"><strong>{{ $booking->currency ?? 'USD' }} {{ number_format($booking->total_price, 2) }}</strong></td>
            </tr>
            @if($booking->addons && count($booking->addons) > 0)
            @foreach($booking->addons as $index => $addon)
            <tr>
                <td>{{ $index + 2 }}</td>
                <td>
                    <strong>{{ is_array($addon) ? ($addon['name'] ?? 'Add-on Service') : $addon }}</strong>
                    @if(is_array($addon) && isset($addon['description']))
                    <br><small>{{ $addon['description'] }}</small>
                    @endif
                </td>
                <td class="text-right">{{ $booking->currency ?? 'USD' }} {{ number_format(is_array($addon) ? ($addon['price'] ?? 0) : 0, 2) }}</td>
                <td class="text-right">{{ $booking->currency ?? 'USD' }} {{ number_format(is_array($addon) ? ($addon['price'] ?? 0) : 0, 2) }}</td>
            </tr>
            @endforeach
            @endif
        </tbody>
    </table>
</div>

<!-- Summary Section -->
<div class="summary-section">
    <div class="summary-box">
        <div class="summary-row">
            <span class="summary-label">Subtotal:</span>
            <span class="summary-value">{{ $booking->currency ?? 'USD' }} {{ number_format($booking->total_price, 2) }}</span>
        </div>
        @if($booking->discount_amount && $booking->discount_amount > 0)
        <div class="summary-row">
            <span class="summary-label">Discount:</span>
            <span class="summary-value" style="color: #dc2626;">-{{ $booking->currency ?? 'USD' }} {{ number_format($booking->discount_amount, 2) }}</span>
        </div>
        @endif
        <div class="summary-row">
            <span class="summary-label">Total:</span>
            <span class="summary-value total-row">{{ $booking->currency ?? 'USD' }} {{ number_format($booking->total_price - ($booking->discount_amount ?? 0), 2) }}</span>
        </div>
        @if($booking->deposit_amount && $booking->deposit_amount > 0)
        <div class="summary-row">
            <span class="summary-label">Less: Deposit Paid:</span>
            <span class="summary-value">-{{ $booking->currency ?? 'USD' }} {{ number_format($booking->deposit_amount, 2) }}</span>
        </div>
        @endif
        <div class="summary-row" style="border-top: 2px solid {{ $mainColor }}; padding-top: 12px; margin-top: 5px;">
            <span class="summary-label"><strong>BALANCE DUE:</strong></span>
            <span class="summary-value" style="font-size: 16pt; color: {{ $mainColor }};">
                <strong>{{ $booking->currency ?? 'USD' }} {{ number_format(($booking->total_price - ($booking->discount_amount ?? 0)) - ($booking->deposit_amount ?? 0), 2) }}</strong>
            </span>
        </div>
    </div>
</div>

<!-- Payment Instructions -->
@php
    $org = \App\Models\OrganizationSetting::getSettings();
@endphp
@if($org->bank_name || $org->iban || $org->swift_code)
<div class="notes-section">
    <div class="notes-title">Payment Instructions:</div>
    <div class="notes-content">
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
        <p style="margin-top: 10px;"><strong>Note:</strong> Please include booking reference ({{ $booking->booking_reference }}) in payment details.</p>
    </div>
</div>
@endif
@endsection


