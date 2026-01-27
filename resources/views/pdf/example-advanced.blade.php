@extends('pdf.advanced-layout')

@section('content')
@php
    // Example: Set document variables
    $documentTitle = 'QUOTATION';
    $documentRef = 'QT-2024-001';
    $documentDate = now()->format('d M Y');
    $mainColor = '#3ea572';
    $darkBlue = '#2d7a5f';
    
    // Example data
    $customerName = 'John Doe';
    $customerEmail = 'john.doe@example.com';
    $customerPhone = '+255 123 456 789';
    $customerAddress = '123 Main Street, Arusha, Tanzania';
    
    $items = [
        ['description' => '3-Day Safari Tour', 'quantity' => 2, 'unit_price' => 500, 'total' => 1000],
        ['description' => 'Kilimanjaro Climb - 7 Days', 'quantity' => 1, 'unit_price' => 1500, 'total' => 1500],
        ['description' => 'Zanzibar Beach Extension', 'quantity' => 2, 'unit_price' => 300, 'total' => 600],
    ];
    
    $subtotal = array_sum(array_column($items, 'total'));
    $tax = $subtotal * 0.18; // 18% tax
    $total = $subtotal + $tax;
@endphp

<!-- Billing Section -->
<div class="billing-section">
    <div class="billing-box">
        <div class="billing-title">Bill To:</div>
        <div class="billing-content">
            <p><strong>{{ $customerName }}</strong></p>
            <p>{{ $customerEmail }}</p>
            <p>{{ $customerPhone }}</p>
            <p>{{ $customerAddress }}</p>
        </div>
    </div>
    <div class="billing-box">
        <div class="billing-title">Quotation Details:</div>
        <div class="billing-content">
            <p><strong>Valid Until:</strong> {{ now()->addDays(30)->format('d M Y') }}</p>
            <p><strong>Payment Terms:</strong> 50% Deposit, 50% Before Travel</p>
            <p><strong>Currency:</strong> USD</p>
        </div>
    </div>
</div>

<!-- Items Table -->
<div class="section">
    <div class="section-title">Items & Services</div>
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
            @foreach($items as $index => $item)
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
            <span class="summary-value">${{ number_format($subtotal, 2) }}</span>
        </div>
        <div class="summary-row">
            <span class="summary-label">Tax (18%):</span>
            <span class="summary-value">${{ number_format($tax, 2) }}</span>
        </div>
        <div class="summary-row">
            <span class="summary-label">Total Amount:</span>
            <span class="summary-value">${{ number_format($total, 2) }}</span>
        </div>
    </div>
</div>

<!-- Notes Section -->
<div class="notes-section">
    <div class="notes-title">Important Notes:</div>
    <div class="notes-content">
        <p>• This quotation is valid for 30 days from the date of issue.</p>
        <p>• A 50% deposit is required to confirm your booking.</p>
        <p>• The remaining balance must be paid 14 days before the travel date.</p>
        <p>• Prices are subject to change based on availability and season.</p>
    </div>
</div>

<!-- Terms Section -->
<div class="terms-section">
    <div class="terms-title">Terms & Conditions:</div>
    <div class="terms-content">
        <p>1. All bookings are subject to availability at the time of confirmation.</p>
        <p>2. Cancellation policy: 50% refund if cancelled 30 days before travel, no refund within 14 days.</p>
        <p>3. Travel insurance is recommended for all participants.</p>
        <p>4. Prices include accommodation, meals, and transportation as specified in the itinerary.</p>
        <p>5. International flights are not included unless otherwise stated.</p>
    </div>
</div>

<!-- Additional Information -->
<div class="section">
    <div class="section-subtitle">Payment Information</div>
    <div class="info-grid">
        <div class="info-row">
            <div class="info-label">Bank Name:</div>
            <div class="info-value">Tanzania Commercial Bank</div>
        </div>
        <div class="info-row">
            <div class="info-label">Account Number:</div>
            <div class="info-value">1234567890</div>
        </div>
        <div class="info-row">
            <div class="info-label">SWIFT Code:</div>
            <div class="info-value">TCBZTZTZ</div>
        </div>
        <div class="info-row">
            <div class="info-label">Account Name:</div>
            <div class="info-value">Lau Paradise Adventures</div>
        </div>
    </div>
</div>
@endsection




