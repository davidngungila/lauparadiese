@extends('pdf.advanced-layout')

@php
    $documentTitle = 'BOOKING CONFIRMATION';
    $documentRef = $booking->booking_reference;
    $documentDate = $booking->created_at->format('d M Y');
    $mainColor = '#3ea572';
    $darkBlue = '#2d7a5f';
@endphp

@section('content')
<!-- Billing Section -->
<div class="billing-section">
    <div class="billing-box">
        <div class="billing-title">Booking To:</div>
        <div class="billing-content">
            <p class="mb-1"><strong>{{ $booking->customer_name }}</strong></p>
            @if($booking->customer_email)
            <p class="mb-1">{{ $booking->customer_email }}</p>
            @endif
            @if($booking->customer_phone)
            <p class="mb-1">{{ $booking->customer_phone }}</p>
            @endif
            @if($booking->customer_country)
            <p class="mb-0">{{ $booking->customer_country }}</p>
            @endif
            @if($booking->user)
            <p class="mb-0 mt-2"><small>Registered User: {{ $booking->user->name }}</small></p>
            @endif
        </div>
    </div>
    <div class="billing-box">
        <div class="billing-title">Booking Details:</div>
        <table class="billing-table">
            <tbody>
                <tr>
                    <td>Booking Reference:</td>
                    <td><strong>{{ $booking->booking_reference }}</strong></td>
                </tr>
                <tr>
                    <td>Status:</td>
                    <td>
                        <span class="status-badge status-{{ str_replace('_', '-', $booking->status) }}">
                            {{ ucfirst(str_replace('_', ' ', $booking->status)) }}
                        </span>
                    </td>
                </tr>
                <tr>
                    <td>Total Amount:</td>
                    <td><strong>{{ $booking->currency ?? 'USD' }} {{ number_format($booking->total_price, 2) }}</strong></td>
                </tr>
                @if($booking->deposit_amount)
                <tr>
                    <td>Deposit:</td>
                    <td>{{ $booking->currency ?? 'USD' }} {{ number_format($booking->deposit_amount, 2) }}</td>
                </tr>
                @endif
                @if($booking->balance_amount)
                <tr>
                    <td>Balance:</td>
                    <td>{{ $booking->currency ?? 'USD' }} {{ number_format($booking->balance_amount, 2) }}</td>
                </tr>
                @endif
            </tbody>
        </table>
    </div>
</div>

<!-- Tour Information -->
@if($booking->tour)
<div class="section">
    <div class="section-title">Tour Information</div>
    <div class="info-grid">
        <div class="info-row">
            <div class="info-label">Tour Name:</div>
            <div class="info-value"><strong>{{ $booking->tour->name }}</strong></div>
        </div>
        @if($booking->tour->destination)
        <div class="info-row">
            <div class="info-label">Destination:</div>
            <div class="info-value">{{ $booking->tour->destination->name }}</div>
        </div>
        @endif
        @if($booking->tour->duration_days)
        <div class="info-row">
            <div class="info-label">Duration:</div>
            <div class="info-value">{{ $booking->tour->duration_days }} Days</div>
        </div>
        @endif
    </div>
</div>
@endif

<!-- Travel Details -->
<div class="section">
    <div class="section-title">Travel Details</div>
    <div class="info-grid">
        <div class="info-row">
            <div class="info-label">Departure Date:</div>
            <div class="info-value"><strong>{{ $booking->departure_date->format('F d, Y') }}</strong></div>
        </div>
        <div class="info-row">
            <div class="info-label">Number of Travelers:</div>
            <div class="info-value">{{ $booking->travelers }} {{ Str::plural('Person', $booking->travelers) }}</div>
        </div>
        <div class="info-row">
            <div class="info-label">Days Until Departure:</div>
            <div class="info-value">{{ $booking->departure_date->diffInDays(now()) }} days</div>
        </div>
    </div>
</div>

<!-- Payment Information Table -->
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
            <td class="text-nowrap text-heading">{{ $booking->tour->name ?? 'Tour Package' }}</td>
            <td class="text-nowrap">Tour Package for {{ $booking->travelers }} {{ Str::plural('traveler', $booking->travelers) }}</td>
            <td>{{ $booking->currency ?? 'USD' }} {{ number_format($booking->total_price, 2) }}</td>
            <td>1</td>
            <td>{{ $booking->currency ?? 'USD' }} {{ number_format($booking->total_price, 2) }}</td>
        </tr>
        @if($booking->addons && count($booking->addons) > 0)
        @foreach($booking->addons as $addon)
        <tr>
            <td class="text-nowrap text-heading">{{ $addon['name'] ?? 'Add-on' }}</td>
            <td class="text-nowrap">Additional Service</td>
            <td>{{ $booking->currency ?? 'USD' }} {{ number_format($addon['price'] ?? 0, 2) }}</td>
            <td>1</td>
            <td>{{ $booking->currency ?? 'USD' }} {{ number_format($addon['price'] ?? 0, 2) }}</td>
        </tr>
        @endforeach
        @endif
    </tbody>
</table>

<!-- Summary Section -->
<div class="summary-section">
    <div class="summary-box">
        <div class="summary-row">
            <span class="summary-label">Subtotal:</span>
            <span class="summary-value">{{ $booking->currency ?? 'USD' }} {{ number_format($booking->total_price, 2) }}</span>
        </div>
        @if($booking->deposit_amount)
        <div class="summary-row">
            <span class="summary-label">Deposit Amount:</span>
            <span class="summary-value">{{ $booking->currency ?? 'USD' }} {{ number_format($booking->deposit_amount, 2) }}</span>
        </div>
        @endif
        @if($booking->balance_amount)
        <div class="summary-row">
            <span class="summary-label">Balance Amount:</span>
            <span class="summary-value">{{ $booking->currency ?? 'USD' }} {{ number_format($booking->balance_amount, 2) }}</span>
        </div>
        @endif
        @if($booking->payment_method)
        <div class="summary-row">
            <span class="summary-label">Payment Method:</span>
            <span class="summary-value">{{ ucfirst(str_replace('_', ' ', $booking->payment_method)) }}</span>
        </div>
        @endif
        <div class="summary-row">
            <span class="summary-label">Total:</span>
            <span class="summary-value total-row">{{ $booking->currency ?? 'USD' }} {{ number_format($booking->total_price, 2) }}</span>
        </div>
    </div>
</div>

<!-- Emergency Contact -->
@if($booking->emergency_contact_name || $booking->emergency_contact_phone)
<div class="section">
    <div class="section-title">Emergency Contact</div>
    <div class="info-grid">
        @if($booking->emergency_contact_name)
        <div class="info-row">
            <div class="info-label">Contact Name:</div>
            <div class="info-value">{{ $booking->emergency_contact_name }}</div>
        </div>
        @endif
        @if($booking->emergency_contact_phone)
        <div class="info-row">
            <div class="info-label">Contact Phone:</div>
            <div class="info-value">{{ $booking->emergency_contact_phone }}</div>
        </div>
        @endif
    </div>
</div>
@endif

<!-- Special Requirements -->
@if($booking->special_requirements)
<div class="notes-section">
    <div class="notes-title">📝 Special Requirements:</div>
    <div>{!! nl2br(e($booking->special_requirements)) !!}</div>
</div>
@endif

<!-- Additional Notes -->
@if($booking->notes)
<div class="notes-section">
    <div class="notes-title">📝 Additional Notes:</div>
    <div>{!! nl2br(e($booking->notes)) !!}</div>
</div>
@endif

<!-- Important Information -->
<div style="background-color: #dbeafe; border-left: 4px solid #2563eb; padding: 15px; margin: 20px 0;">
    <div style="font-weight: bold; color: #1e40af; margin-bottom: 10px;">⚠️ Important Information:</div>
    <div style="font-size: 10px; line-height: 1.8;">
        <p style="margin-bottom: 8px;"><strong>1. Confirmation:</strong> Please keep this booking confirmation for your records. You will need your booking reference ({{ $booking->booking_reference }}) for any inquiries.</p>
        <p style="margin-bottom: 8px;"><strong>2. Payment:</strong> 
            @if($booking->status === 'pending_payment')
                Your booking is pending payment. Please complete payment to confirm your reservation.
            @elseif($booking->status === 'confirmed')
                Your booking has been confirmed. 
                @if($booking->balance_amount > 0)
                    Please ensure the remaining balance of {{ $booking->currency ?? 'USD' }} {{ number_format($booking->balance_amount, 2) }} is paid at least 14 days before departure.
                @endif
            @endif
        </p>
        <p style="margin-bottom: 8px;"><strong>3. Changes & Cancellations:</strong> Any changes or cancellations must be made in accordance with our terms and conditions. Please contact us as soon as possible if you need to make changes.</p>
        <p style="margin-bottom: 8px;"><strong>4. Travel Documents:</strong> Please ensure you have all necessary travel documents including valid passports, visas, and travel insurance.</p>
        <p><strong>5. Contact:</strong> If you have any questions or concerns, please contact us immediately using the contact information provided.</p>
    </div>
</div>

<!-- Terms & Conditions -->
@php
    $org = \App\Models\OrganizationSetting::getSettings();
@endphp
@if($org->invoice_terms)
<div class="terms-section">
    <div class="terms-title">Terms & Conditions:</div>
    <div>{!! nl2br(e($org->invoice_terms)) !!}</div>
</div>
@else
<div class="terms-section">
    <div class="terms-title">Standard Terms & Conditions:</div>
    <div>
        <p style="margin-bottom: 8px;"><strong>1. Payment Terms:</strong> 
            @if($booking->deposit_amount)
                A deposit of {{ $booking->currency ?? 'USD' }} {{ number_format($booking->deposit_amount, 2) }} has been received.
            @endif
            @if($booking->balance_amount)
                The balance of {{ $booking->currency ?? 'USD' }} {{ number_format($booking->balance_amount, 2) }} must be paid at least 14 days before departure.
            @endif
        </p>
        <p style="margin-bottom: 8px;"><strong>2. Cancellation Policy:</strong> Cancellations made 30+ days before departure receive a full refund minus processing fees. Cancellations 14-30 days before receive 50% refund. No refund for cancellations less than 14 days before departure.</p>
        <p style="margin-bottom: 8px;"><strong>3. Changes:</strong> Changes to bookings are subject to availability and may incur additional charges. Please contact us as soon as possible to make any changes.</p>
        <p style="margin-bottom: 8px;"><strong>4. Travel Insurance:</strong> We strongly recommend comprehensive travel insurance covering medical expenses, trip cancellation, and personal belongings.</p>
        <p style="margin-bottom: 8px;"><strong>5. Force Majeure:</strong> We are not liable for circumstances beyond our control including natural disasters, political unrest, or pandemics.</p>
        <p style="margin-bottom: 8px;"><strong>6. Responsibility:</strong> {{ $org->organization_name }} acts as an agent for tour operators and is not responsible for the acts or omissions of third-party service providers.</p>
        <p><strong>7. Acceptance:</strong> By making this booking, you acknowledge that you have read, understood, and agree to our terms and conditions.</p>
    </div>
</div>
@endif
@endsection
