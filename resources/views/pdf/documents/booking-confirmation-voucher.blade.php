@extends('pdf.advanced-layout')

@php
    $documentTitle = 'BOOKING CONFIRMATION VOUCHER';
    $documentRef = $booking->booking_reference;
    $documentDate = $booking->created_at->format('d M Y');
    $mainColor = '#3ea572';
    $darkBlue = '#2d7a5f';
@endphp

@section('content')
<!-- Confirmation Header Banner -->
<div style="text-align: center; margin-bottom: 30px; padding: 25px; background: linear-gradient(135deg, #e6f4ed 0%, #c8e6c9 100%); border-radius: 10px; border: 2px solid {{ $mainColor }};">
    <div style="font-size: 28pt; font-weight: bold; color: {{ $mainColor }}; margin-bottom: 10px;">✓ BOOKING CONFIRMED</div>
    <div style="font-size: 14pt; color: {{ $darkBlue }}; font-weight: bold;">Reference: {{ $booking->booking_reference }}</div>
    <div style="font-size: 11pt; color: #666; margin-top: 8px;">Confirmed on {{ $booking->created_at->format('F d, Y') }}</div>
</div>

<!-- Greeting Letter -->
<div style="margin-bottom: 30px; padding: 20px; background: #f8f9fa; border-radius: 8px; border-left: 4px solid {{ $mainColor }};">
    <p style="font-size: 12pt; line-height: 1.8; margin-bottom: 12px;">
        <strong>Dear {{ $booking->customer_name }},</strong>
    </p>
    <p style="font-size: 11pt; line-height: 1.8; margin-bottom: 12px;">
        Thank you for choosing <strong>{{ \App\Models\OrganizationSetting::getSettings()->organization_name ?? 'Lau Paradise Adventures' }}</strong>. We are delighted to confirm your booking and look forward to providing you with an exceptional travel experience.
    </p>
    <p style="font-size: 11pt; line-height: 1.8; margin-bottom: 0;">
        Please find below the complete details of your confirmed booking. Please review all information carefully and keep this document for your records.
    </p>
</div>

<!-- Customer & Booking Information Boxes -->
<div class="billing-section">
    <div class="billing-box">
        <div class="billing-title">Customer Information</div>
        <div class="billing-content">
            <p><strong>{{ $booking->customer_name }}</strong></p>
            @if($booking->customer_email)
            <p><strong>Email:</strong> {{ $booking->customer_email }}</p>
            @endif
            @if($booking->customer_phone)
            <p><strong>Phone:</strong> {{ $booking->customer_phone }}</p>
            @endif
            @if($booking->customer_country)
            <p><strong>Country:</strong> {{ $booking->customer_country }}</p>
            @endif
            @if($booking->city)
            <p><strong>City:</strong> {{ $booking->city }}</p>
            @endif
            @if($booking->passport_number)
            <p><strong>Passport:</strong> {{ $booking->passport_number }}</p>
            @endif
        </div>
    </div>
    <div class="billing-box">
        <div class="billing-title">Booking Summary</div>
        <div class="billing-content">
            <p><strong>Booking Reference:</strong> {{ $booking->booking_reference }}</p>
            <p><strong>Booking Date:</strong> {{ $booking->created_at->format('F d, Y') }}</p>
            <p><strong>Status:</strong> 
                <span class="status-badge status-{{ str_replace('_', '-', $booking->status) }}">
                    {{ ucfirst(str_replace('_', ' ', $booking->status)) }}
                </span>
            </p>
            <p><strong>Payment Status:</strong> 
                <span class="status-badge status-{{ str_replace('_', '-', $booking->payment_status ?? 'pending') }}">
                    {{ ucfirst(str_replace('_', ' ', $booking->payment_status ?? 'pending')) }}
                </span>
            </p>
            @if($booking->assignedStaff)
            <p><strong>Assigned Agent:</strong> {{ $booking->assignedStaff->name }}</p>
            @endif
        </div>
    </div>
</div>

<!-- Tour Details Section -->
@if($booking->tour)
<div class="section">
    <div class="section-title">Tour Package Details</div>
    <table class="data-table">
        <thead>
            <tr>
                <th style="width: 30%;">Item</th>
                <th style="width: 70%;">Details</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td><strong>Tour Name</strong></td>
                <td><strong style="color: {{ $mainColor }};">{{ $booking->tour->name }}</strong></td>
            </tr>
            @if($booking->tour->tour_code)
            <tr>
                <td><strong>Tour Code</strong></td>
                <td>{{ $booking->tour->tour_code }}</td>
            </tr>
            @endif
            @if($booking->departure_date)
            <tr>
                <td><strong>Departure Date</strong></td>
                <td><strong>{{ $booking->departure_date->format('l, F d, Y') }}</strong></td>
            </tr>
            @endif
            @if($booking->travel_end_date)
            <tr>
                <td><strong>Return Date</strong></td>
                <td><strong>{{ $booking->travel_end_date->format('l, F d, Y') }}</strong></td>
            </tr>
            @endif
            <tr>
                <td><strong>Duration</strong></td>
                <td>
                    @if($booking->tour->duration_days)
                        {{ $booking->tour->duration_days }} Days / {{ $booking->tour->duration_nights }} Nights
                    @else
                        {{ $booking->departure_date && $booking->travel_end_date ? $booking->departure_date->diffInDays($booking->travel_end_date) + 1 : 'N/A' }} Days
                    @endif
                </td>
            </tr>
            <tr>
                <td><strong>Number of Travelers</strong></td>
                <td>
                    <strong>{{ $booking->travelers }} {{ $booking->travelers == 1 ? 'Adult' : 'Adults' }}</strong>
                    @if($booking->number_of_children && $booking->number_of_children > 0)
                        + {{ $booking->number_of_children }} {{ $booking->number_of_children == 1 ? 'Child' : 'Children' }}
                    @endif
                </td>
            </tr>
            @if($booking->tour->start_location)
            <tr>
                <td><strong>Start Location</strong></td>
                <td>{{ $booking->tour->start_location }}</td>
            </tr>
            @endif
            @if($booking->tour->end_location)
            <tr>
                <td><strong>End Location</strong></td>
                <td>{{ $booking->tour->end_location }}</td>
            </tr>
            @endif
            @if($booking->pickup_location)
            <tr>
                <td><strong>Pickup Location</strong></td>
                <td>{{ $booking->pickup_location }}</td>
            </tr>
            @endif
            @if($booking->dropoff_location)
            <tr>
                <td><strong>Drop-off Location</strong></td>
                <td>{{ $booking->dropoff_location }}</td>
            </tr>
            @endif
            @if($booking->accommodation_level)
            <tr>
                <td><strong>Accommodation Level</strong></td>
                <td>{{ ucfirst(str_replace('_', ' ', $booking->accommodation_level)) }}</td>
            </tr>
            @endif
        </tbody>
    </table>
</div>
@endif

<!-- Payment Breakdown Section -->
<div class="section">
    <div class="section-title">Payment Breakdown</div>
    <div class="summary-section">
        <div class="summary-box">
            <div class="summary-row">
                <span class="summary-label">Total Package Price:</span>
                <span class="summary-value">{{ $booking->currency ?? 'USD' }} {{ number_format($booking->total_price, 2) }}</span>
            </div>
            @if($booking->discount_amount && $booking->discount_amount > 0)
            <div class="summary-row">
                <span class="summary-label">Discount:</span>
                <span class="summary-value" style="color: #28a745;">- {{ $booking->currency ?? 'USD' }} {{ number_format($booking->discount_amount, 2) }}</span>
            </div>
            @endif
            @if($booking->deposit_amount && $booking->deposit_amount > 0)
            <div class="summary-row">
                <span class="summary-label">Deposit Paid:</span>
                <span class="summary-value" style="color: #28a745;">{{ $booking->currency ?? 'USD' }} {{ number_format($booking->deposit_amount, 2) }}</span>
            </div>
            @endif
            @if($booking->amount_paid && $booking->amount_paid > 0)
            <div class="summary-row">
                <span class="summary-label">Amount Paid:</span>
                <span class="summary-value" style="color: #28a745;">{{ $booking->currency ?? 'USD' }} {{ number_format($booking->amount_paid, 2) }}</span>
            </div>
            @endif
            @if($booking->balance_amount && $booking->balance_amount > 0)
            <div class="summary-row">
                <span class="summary-label">Balance Due:</span>
                <span class="summary-value" style="color: #dc2626; font-size: 16px;">
                    {{ $booking->currency ?? 'USD' }} {{ number_format($booking->balance_amount, 2) }}
                </span>
            </div>
            @else
            <div class="summary-row">
                <span class="summary-label">Balance Due:</span>
                <span class="summary-value" style="color: #28a745;">FULLY PAID</span>
            </div>
            @endif
            @if($booking->payment_method)
            <div style="margin-top: 15px; padding-top: 15px; border-top: 1px solid #e5e7eb;">
                <p style="font-size: 10px; color: #6c757d; margin: 5px 0;"><strong>Payment Method:</strong> {{ ucfirst(str_replace('_', ' ', $booking->payment_method)) }}</p>
            </div>
            @endif
        </div>
    </div>
</div>

<!-- Add-ons Section -->
@if($booking->addons && count($booking->addons) > 0)
<div class="section">
    <div class="section-title">Additional Services / Add-ons</div>
    <table class="data-table">
        <thead>
            <tr>
                <th>Service</th>
                <th>Details</th>
            </tr>
        </thead>
        <tbody>
            @foreach($booking->addons as $addon)
            <tr>
                <td><strong>{{ is_array($addon) ? ($addon['name'] ?? 'Add-on Service') : $addon }}</strong></td>
                <td>
                    @if(is_array($addon))
                        {{ $addon['description'] ?? 'Included in package' }}
                        @if(isset($addon['price']))
                            - {{ $booking->currency ?? 'USD' }} {{ number_format($addon['price'], 2) }}
                        @endif
                    @else
                        Included in package
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endif

<!-- Important Information Boxes -->
<div style="display: flex; gap: 15px; margin: 25px 0; flex-wrap: wrap;">
    <!-- Next Steps Box -->
    <div style="flex: 1; min-width: 300px; background-color: #fff3cd; border-left: 4px solid #ffc107; padding: 15px; border-radius: 5px;">
        <div style="font-weight: bold; color: #856404; margin-bottom: 10px; font-size: 12px;">📋 Next Steps:</div>
        <div style="font-size: 10px; line-height: 1.8; color: #856404;">
            <p style="margin-bottom: 8px;">✓ Review all attached documents carefully</p>
            @if($booking->balance_amount && $booking->balance_amount > 0)
            <p style="margin-bottom: 8px;">
                <strong>✓ Make balance payment of {{ $booking->currency ?? 'USD' }} {{ number_format($booking->balance_amount, 2) }}</strong><br>
                <span style="font-size: 9px;">Due by: {{ $booking->departure_date ? $booking->departure_date->copy()->subDays(14)->format('F d, Y') : 'Before departure' }}</span>
            </p>
            @else
            <p style="margin-bottom: 8px;">✓ Your booking is fully paid</p>
            @endif
            <p style="margin-bottom: 0;">✓ Prepare travel documents (passport, visa, insurance)</p>
        </div>
    </div>
    
    <!-- Important Information Box -->
    <div style="flex: 1; min-width: 300px; background-color: #dbeafe; border-left: 4px solid #2563eb; padding: 15px; border-radius: 5px;">
        <div style="font-weight: bold; color: #1e40af; margin-bottom: 10px; font-size: 12px;">⚠️ Important Information:</div>
        <div style="font-size: 10px; line-height: 1.8; color: #1e40af;">
            <p style="margin-bottom: 8px;"><strong>1. Booking Reference:</strong> Keep {{ $booking->booking_reference }} for all inquiries</p>
            <p style="margin-bottom: 8px;"><strong>2. Changes:</strong> Must be made at least 48 hours before departure</p>
            <p style="margin-bottom: 8px;"><strong>3. Travel Documents:</strong> Valid passport, visa, and travel insurance required</p>
            <p style="margin-bottom: 0;"><strong>4. Contact:</strong> Use header information for any questions</p>
        </div>
    </div>
</div>

<!-- Special Requirements -->
@if($booking->special_requirements)
<div class="notes-section">
    <div class="notes-title">📝 Special Requirements & Notes:</div>
    <div class="notes-content">{!! nl2br(e($booking->special_requirements)) !!}</div>
</div>
@endif

<!-- Emergency Contact -->
@if($booking->emergency_contact_name || $booking->emergency_contact_phone)
<div class="section">
    <div class="section-title">Emergency Contact Information</div>
    <div class="info-grid">
        @if($booking->emergency_contact_name)
        <div class="info-row">
            <div class="info-label">Contact Name:</div>
            <div class="info-value"><strong>{{ $booking->emergency_contact_name }}</strong></div>
        </div>
        @endif
        @if($booking->emergency_contact_phone)
        <div class="info-row">
            <div class="info-label">Contact Phone:</div>
            <div class="info-value"><strong>{{ $booking->emergency_contact_phone }}</strong></div>
        </div>
        @endif
    </div>
</div>
@endif

<!-- Terms & Conditions -->
@if($booking->tour && $booking->tour->terms_conditions)
<div class="terms-section">
    <div class="terms-title">Terms & Conditions:</div>
    <div class="terms-content">{!! nl2br(e($booking->tour->terms_conditions)) !!}</div>
</div>
@endif

<!-- Closing Statement -->
<div style="margin-top: 30px; padding: 20px; background: linear-gradient(135deg, #f0f8f4 0%, #e8f5e9 100%); border-radius: 8px; border: 1px solid {{ $mainColor }};">
    <p style="font-size: 11pt; line-height: 1.8; margin-bottom: 10px; text-align: center;">
        <strong>We look forward to welcoming you and ensuring you have an unforgettable experience!</strong>
    </p>
    <p style="font-size: 10pt; line-height: 1.8; margin: 0; text-align: center; color: #666;">
        If you have any questions or concerns, please do not hesitate to contact us using the information provided in the header.
    </p>
</div>
@endsection
