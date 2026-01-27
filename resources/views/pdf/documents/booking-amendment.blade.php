@extends('pdf.advanced-layout')

@php
    $documentTitle = 'BOOKING AMENDMENT CONFIRMATION';
    $documentRef = $booking->booking_reference . '-AM' . str_pad(($amendmentNumber ?? 1), 2, '0', STR_PAD_LEFT);
    $documentDate = now()->format('d M Y');
    $mainColor = '#3ea572';
    $darkBlue = '#2d7a5f';
    $amendmentType = $amendmentType ?? 'general';
@endphp

@section('content')
<!-- Amendment Header Banner -->
<div style="text-align: center; margin-bottom: 30px; padding: 25px; background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%); border-radius: 10px; border: 2px solid #f59e0b;">
    <div style="font-size: 28pt; font-weight: bold; color: #92400e; margin-bottom: 10px;">📝 BOOKING AMENDMENT</div>
    <div style="font-size: 14pt; color: #78350f; font-weight: bold;">Amendment Reference: {{ $documentRef }}</div>
    <div style="font-size: 11pt; color: #666; margin-top: 8px;">Original Booking: {{ $booking->booking_reference }}</div>
</div>

<!-- Greeting Letter -->
<div style="margin-bottom: 30px; padding: 20px; background: #f8f9fa; border-radius: 8px; border-left: 4px solid #f59e0b;">
    <p style="font-size: 12pt; line-height: 1.8; margin-bottom: 12px;">
        <strong>Dear {{ $booking->customer_name }},</strong>
    </p>
    <p style="font-size: 11pt; line-height: 1.8; margin-bottom: 12px;">
        This document confirms the amendment(s) made to your booking <strong>{{ $booking->booking_reference }}</strong>. Please review the changes carefully and keep this document for your records.
    </p>
    <p style="font-size: 11pt; line-height: 1.8; margin-bottom: 0;">
        All other services remain confirmed as per your original booking unless otherwise stated below.
    </p>
</div>

<!-- Original Booking Summary -->
<div class="section">
    <div class="section-title">Original Booking Information</div>
    <div class="billing-section">
        <div class="billing-box">
            <div class="billing-title">Booking Details</div>
            <div class="billing-content">
                <p><strong>Booking Reference:</strong> {{ $booking->booking_reference }}</p>
                <p><strong>Tour:</strong> {{ $booking->tour->name ?? 'N/A' }}</p>
                <p><strong>Original Date:</strong> {{ $booking->departure_date ? $booking->departure_date->format('F d, Y') : 'N/A' }}</p>
                <p><strong>Travelers:</strong> {{ $booking->travelers }} {{ Str::plural('Person', $booking->travelers) }}</p>
            </div>
        </div>
        <div class="billing-box">
            <div class="billing-title">Customer Information</div>
            <div class="billing-content">
                <p><strong>{{ $booking->customer_name }}</strong></p>
                @if($booking->customer_email)
                <p>{{ $booking->customer_email }}</p>
                @endif
                @if($booking->customer_phone)
                <p>{{ $booking->customer_phone }}</p>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Amendment Details Table -->
<div class="section">
    <div class="section-title">Amendment Details</div>
    <table class="data-table">
        <thead>
            <tr>
                <th style="width: 30%;">Item</th>
                <th style="width: 35%;">Original Value</th>
                <th style="width: 35%;">Amended Value</th>
            </tr>
        </thead>
        <tbody>
            @if($amendmentType == 'pax_change' || $amendmentType == 'general')
            <tr>
                <td><strong>Number of Passengers</strong></td>
                <td>{{ $booking->travelers }} {{ Str::plural('Person', $booking->travelers) }}</td>
                <td>
                    @if(isset($newTravelers))
                        <strong style="color: {{ $mainColor }};">{{ $newTravelers }} {{ Str::plural('Person', $newTravelers) }}</strong>
                    @else
                        <em>See notes below</em>
                    @endif
                </td>
            </tr>
            @endif
            
            @if($amendmentType == 'date_change' || $amendmentType == 'general')
            <tr>
                <td><strong>Departure Date</strong></td>
                <td>{{ $booking->departure_date ? $booking->departure_date->format('F d, Y') : 'N/A' }}</td>
                <td>
                    @if(isset($newDepartureDate))
                        <strong style="color: {{ $mainColor }};">{{ \Carbon\Carbon::parse($newDepartureDate)->format('F d, Y') }}</strong>
                    @else
                        <em>See notes below</em>
                    @endif
                </td>
            </tr>
            @endif
            
            @if($amendmentType == 'tour_change' || $amendmentType == 'general')
            <tr>
                <td><strong>Tour Package</strong></td>
                <td>{{ $booking->tour->name ?? 'N/A' }}</td>
                <td>
                    @if(isset($newTour))
                        <strong style="color: {{ $mainColor }};">{{ is_object($newTour) ? $newTour->name : $newTour }}</strong>
                    @else
                        <em>No change</em>
                    @endif
                </td>
            </tr>
            @endif
            
            @if($amendmentType == 'price_change' || $amendmentType == 'general')
            <tr>
                <td><strong>Total Price</strong></td>
                <td>{{ $booking->currency ?? 'USD' }} {{ number_format($booking->total_price, 2) }}</td>
                <td>
                    @if(isset($newTotalPrice))
                        <strong style="color: {{ $mainColor }};">{{ $booking->currency ?? 'USD' }} {{ number_format($newTotalPrice, 2) }}</strong>
                    @else
                        <em>No change</em>
                    @endif
                </td>
            </tr>
            @endif
            
            @if($booking->notes)
            <tr>
                <td colspan="3" style="padding: 15px; background-color: #f9fafb;">
                    <strong style="color: {{ $mainColor }};">Amendment Notes:</strong><br>
                    <div style="margin-top: 8px; font-size: 10px; line-height: 1.6;">{!! nl2br(e($booking->notes)) !!}</div>
                </td>
            </tr>
            @endif
        </tbody>
    </table>
</div>

<!-- Cost Impact Section -->
@php
    $additionalCost = isset($additionalCost) ? (float)$additionalCost : 0;
@endphp
@if($additionalCost != 0)
<div class="section">
    <div class="section-title">Cost Impact</div>
    <div style="background: linear-gradient(135deg, #f0f8f4 0%, #e8f5e9 100%); padding: 20px; border-radius: 8px; border: 2px solid {{ $mainColor }};">
        @if($additionalCost > 0)
        <div style="text-align: center; margin-bottom: 15px;">
            <div style="font-size: 16px; color: #666; margin-bottom: 5px;">Additional Amount Required</div>
            <div style="font-size: 32px; font-weight: bold; color: #dc2626;">
                {{ $booking->currency ?? 'USD' }} {{ number_format($additionalCost, 2) }}
            </div>
        </div>
        <p style="font-size: 11px; color: #666; text-align: center; margin: 0;">
            A proforma invoice for this amount will be sent separately. Payment is due before the amended departure date.
        </p>
        @else
        <div style="text-align: center; margin-bottom: 15px;">
            <div style="font-size: 16px; color: #666; margin-bottom: 5px;">Credit Amount</div>
            <div style="font-size: 32px; font-weight: bold; color: #28a745;">
                {{ $booking->currency ?? 'USD' }} {{ number_format(abs($additionalCost), 2) }}
            </div>
        </div>
        <p style="font-size: 11px; color: #666; text-align: center; margin: 0;">
            This amount will be refunded to your original payment method or credited to your account within 7-14 business days.
        </p>
        @endif
    </div>
</div>
@endif

<!-- Confirmation Statement -->
<div style="background: linear-gradient(135deg, #dbeafe 0%, #bfdbfe 100%); border-left: 4px solid #2563eb; padding: 20px; margin: 25px 0; border-radius: 5px;">
    <div style="font-weight: bold; color: #1e40af; margin-bottom: 12px; font-size: 13px;">✓ Amendment Confirmation:</div>
    <div style="font-size: 11px; line-height: 1.8; color: #1e40af;">
        <p style="margin-bottom: 8px;">✓ All changes have been processed and confirmed</p>
        <p style="margin-bottom: 8px;">✓ Your booking reference remains: <strong>{{ $booking->booking_reference }}</strong></p>
        <p style="margin-bottom: 8px;">✓ All other services remain as per your original booking</p>
        <p style="margin-bottom: 0;">✓ Updated documents will be sent to your email</p>
    </div>
</div>

<!-- Acceptance Section -->
<div class="section" style="margin-top: 30px;">
    <div class="section-title">Amendment Acceptance</div>
    <div style="border: 2px solid #dee2e6; padding: 25px; border-radius: 8px; background: #f8f9fa;">
        <p style="font-size: 11pt; line-height: 1.8; margin-bottom: 20px; text-align: center;">
            <strong>I acknowledge that I have reviewed the above amendment(s) and agree to the changes and any associated costs or credits.</strong>
        </p>
        
        <div style="display: table; width: 100%; margin-top: 40px;">
            <div style="display: table-cell; width: 50%; vertical-align: top; padding-right: 20px;">
                <div style="border-top: 2px solid #333; padding-top: 8px; margin-top: 60px;">
                    <p style="font-size: 10px; color: #666; margin: 0;">Client Signature</p>
                </div>
            </div>
            <div style="display: table-cell; width: 50%; vertical-align: top; padding-left: 20px;">
                <div style="border-top: 2px solid #333; padding-top: 8px; margin-top: 60px;">
                    <p style="font-size: 10px; color: #666; margin: 0;">Date</p>
                    <p style="font-size: 10px; color: #666; margin-top: 5px;">{{ now()->format('F d, Y') }}</p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Important Notes -->
<div style="background-color: #fff3cd; border-left: 4px solid #ffc107; padding: 15px; margin: 25px 0; border-radius: 5px;">
    <div style="font-weight: bold; color: #856404; margin-bottom: 10px; font-size: 12px;">⚠️ Important Notes:</div>
    <div style="font-size: 10px; line-height: 1.8; color: #856404;">
        <p style="margin-bottom: 8px;">• Please sign and return this document to confirm acceptance of the amendment</p>
        <p style="margin-bottom: 8px;">• Any further changes may incur additional fees</p>
        <p style="margin-bottom: 8px;">• Cancellation policies apply to the amended booking</p>
        <p style="margin-bottom: 0;">• Contact us immediately if you have any questions or concerns</p>
    </div>
</div>
@endsection
