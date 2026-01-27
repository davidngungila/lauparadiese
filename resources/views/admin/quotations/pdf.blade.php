@extends('pdf.advanced-layout')

@php
    $documentTitle = 'QUOTATION';
    $documentRef = $quotation->quotation_number;
    $documentDate = $quotation->created_at->format('d M Y');
    $mainColor = '#3ea572';
    $darkBlue = '#2d7a5f';
@endphp

@section('content')
<!-- Billing Section -->
<div class="billing-section">
    <div class="billing-box">
        <div class="billing-title">Quotation To:</div>
        <div class="billing-content">
            <p class="mb-1"><strong>{{ $quotation->customer_name }}</strong></p>
            @if($quotation->customer_email)
            <p class="mb-1">{{ $quotation->customer_email }}</p>
            @endif
            @if($quotation->customer_phone)
            <p class="mb-1">{{ $quotation->customer_phone }}</p>
            @endif
            @if($quotation->customer_address)
            <p class="mb-0">{{ $quotation->customer_address }}</p>
            @endif
        </div>
    </div>
    <div class="billing-box">
        <div class="billing-title">Quotation Details:</div>
        <table class="billing-table">
            <tbody>
                <tr>
                    <td>Total Amount:</td>
                    <td><strong>${{ number_format($quotation->total_price, 2) }}</strong></td>
                </tr>
                @if($quotation->valid_until)
                <tr>
                    <td>Valid Until:</td>
                    <td>{{ $quotation->valid_until->format('F d, Y') }}</td>
                </tr>
                @endif
                @php
                    $org = \App\Models\OrganizationSetting::getSettings();
                @endphp
                @if($org->bank_name)
                <tr>
                    <td>Bank name:</td>
                    <td>{{ $org->bank_name }}</td>
                </tr>
                @endif
                @if($org->bank_country)
                <tr>
                    <td>Country:</td>
                    <td>{{ $org->bank_country }}</td>
                </tr>
                @endif
                @if($org->iban)
                <tr>
                    <td>IBAN:</td>
                    <td>{{ $org->iban }}</td>
                </tr>
                @endif
                @if($org->swift_code)
                <tr>
                    <td>SWIFT code:</td>
                    <td>{{ $org->swift_code }}</td>
                </tr>
                @endif
            </tbody>
        </table>
    </div>
</div>

<!-- Tour Information -->
<div class="section">
    <div class="section-title">Tour Details</div>
    <div class="info-grid">
        <div class="info-row">
            <div class="info-label">Tour Name:</div>
            <div class="info-value"><strong>{{ $quotation->tour_name }}</strong></div>
        </div>
        <div class="info-row">
            <div class="info-label">Departure Date:</div>
            <div class="info-value">{{ $quotation->departure_date ? $quotation->departure_date->format('F d, Y') : 'N/A' }}</div>
        </div>
        <div class="info-row">
            <div class="info-label">Duration:</div>
            <div class="info-value">{{ $quotation->duration_days ? $quotation->duration_days . ' days' : 'N/A' }}</div>
        </div>
        <div class="info-row">
            <div class="info-label">Number of Travelers:</div>
            <div class="info-value">{{ $quotation->travelers }}</div>
        </div>
    </div>
</div>

<!-- Pricing Details Table -->
<table class="data-table">
    <thead>
        <tr>
            <th>Item</th>
            <th>Description</th>
            <th>Cost</th>
            <th>Qty</th>
            <th>Price</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td class="text-nowrap text-heading">{{ $quotation->tour_name }}</td>
            <td class="text-nowrap">Tour package for {{ $quotation->travelers }} {{ $quotation->travelers == 1 ? 'traveler' : 'travelers' }}</td>
            <td>${{ number_format($quotation->tour_price, 2) }}</td>
            <td>1</td>
            <td>${{ number_format($quotation->tour_price, 2) }}</td>
        </tr>
        @if($quotation->addons_total > 0)
        <tr>
            <td class="text-nowrap text-heading">Add-ons & Extras</td>
            <td class="text-nowrap">Additional services and equipment</td>
            <td>${{ number_format($quotation->addons_total, 2) }}</td>
            <td>1</td>
            <td>${{ number_format($quotation->addons_total, 2) }}</td>
        </tr>
        @endif
    </tbody>
</table>

<!-- Summary Section -->
<div class="summary-section">
    <div class="summary-box">
        <div class="summary-row">
            <span class="summary-label">Subtotal:</span>
            <span class="summary-value">${{ number_format($quotation->subtotal ?? ($quotation->tour_price + ($quotation->addons_total ?? 0)), 2) }}</span>
        </div>
        @if($quotation->discount > 0)
        <div class="summary-row">
            <span class="summary-label">Discount:</span>
            <span class="summary-value">- ${{ number_format($quotation->discount, 2) }}</span>
        </div>
        @endif
        @if($quotation->tax > 0)
        <div class="summary-row">
            <span class="summary-label">Tax:</span>
            <span class="summary-value">${{ number_format($quotation->tax, 2) }}</span>
        </div>
        @endif
        <div class="summary-row">
            <span class="summary-label">Total Amount:</span>
            <span class="summary-value total-row">${{ number_format($quotation->total_price, 2) }}</span>
        </div>
    </div>
</div>

<!-- Included & Excluded -->
@if($quotation->included || $quotation->excluded)
<div class="section">
    <div class="section-title">What's Included & Excluded</div>
    <div style="display: flex; gap: 20px;">
        @if($quotation->included)
        <div style="flex: 1;">
            <div style="font-weight: bold; color: #059669; margin-bottom: 8px;">✓ Included:</div>
            <div style="font-size: 10px; line-height: 1.8;">
                {!! nl2br(e($quotation->included)) !!}
            </div>
        </div>
        @endif
        @if($quotation->excluded)
        <div style="flex: 1;">
            <div style="font-weight: bold; color: #dc2626; margin-bottom: 8px;">✗ Excluded:</div>
            <div style="font-size: 10px; line-height: 1.8;">
                {!! nl2br(e($quotation->excluded)) !!}
            </div>
        </div>
        @endif
    </div>
</div>
@endif

<!-- Validity Information -->
@if($quotation->valid_until)
<div style="background-color: #dbeafe; border: 1px solid #93c5fd; padding: 10px; margin: 20px 0; text-align: center; border-radius: 5px;">
    <div style="font-weight: bold; color: #1e40af;">
        This quotation is valid until {{ $quotation->valid_until->format('F d, Y') }}
        @if($quotation->isExpired())
            <span style="color: #dc2626;">(EXPIRED)</span>
        @endif
    </div>
</div>
@endif

<!-- Notes -->
@if($quotation->notes)
<div class="notes-section">
    <div class="notes-title">📝 Additional Notes:</div>
    <div>{!! nl2br(e($quotation->notes)) !!}</div>
</div>
@endif

<!-- Terms & Conditions -->
@if($quotation->terms_conditions)
<div class="terms-section">
    <div class="terms-title">Terms & Conditions:</div>
    <div>{!! nl2br(e($quotation->terms_conditions)) !!}</div>
</div>
@else
<div class="terms-section">
    <div class="terms-title">Standard Terms & Conditions:</div>
    <div>
        <p style="margin-bottom: 8px;"><strong>1. Payment Terms:</strong> A deposit of 30% is required to confirm your booking. The balance must be paid at least 14 days before departure.</p>
        <p style="margin-bottom: 8px;"><strong>2. Cancellation Policy:</strong> Cancellations made 30+ days before departure receive a full refund minus processing fees. Cancellations 14-30 days before receive 50% refund. No refund for cancellations less than 14 days before departure.</p>
        <p style="margin-bottom: 8px;"><strong>3. Changes:</strong> Changes to bookings are subject to availability and may incur additional charges.</p>
        <p style="margin-bottom: 8px;"><strong>4. Travel Insurance:</strong> We strongly recommend comprehensive travel insurance covering medical expenses, trip cancellation, and personal belongings.</p>
        <p style="margin-bottom: 8px;"><strong>5. Force Majeure:</strong> We are not liable for circumstances beyond our control including natural disasters, political unrest, or pandemics.</p>
        <p><strong>6. Acceptance:</strong> By accepting this quotation, you agree to our terms and conditions.</p>
    </div>
</div>
@endif
@endsection
