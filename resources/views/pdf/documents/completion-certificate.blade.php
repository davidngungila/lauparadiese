@extends('pdf.advanced-layout')

@php
    $documentTitle = 'CERTIFICATE OF CONGRATULATIONS';
    $documentRef = 'CERT-' . $booking->booking_reference;
    $documentDate = ($issueDate ?? now())->format('d M Y');
    $mainColor = '#3ea572';
    $darkBlue = '#2d7a5f';
@endphp

@section('content')
<!-- Corner Decorations -->
<div style="position: absolute; top: -15px; left: -15px; width: 60px; height: 60px; border-top: 8px solid #d4af37; border-left: 8px solid #d4af37; z-index: 0;"></div>
<div style="position: absolute; top: -15px; right: -15px; width: 60px; height: 60px; border-top: 8px solid #d4af37; border-right: 8px solid #d4af37; z-index: 0;"></div>
<div style="position: absolute; bottom: -15px; left: -15px; width: 60px; height: 60px; border-bottom: 8px solid #d4af37; border-left: 8px solid #d4af37; z-index: 0;"></div>
<div style="position: absolute; bottom: -15px; right: -15px; width: 60px; height: 60px; border-bottom: 8px solid #d4af37; border-right: 8px solid #d4af37; z-index: 0;"></div>

<!-- Certificate Content -->
<div style="position: relative; z-index: 1;">
<!-- Certificate Header Banner -->
<div style="text-align: center; margin-bottom: 20px; padding: 15px; background: linear-gradient(135deg, #e6f4ed 0%, #c8e6c9 100%); border-radius: 8px; border: 2px solid {{ $mainColor }};">
    <div style="font-size: 24px; font-weight: bold; color: {{ $mainColor }}; margin-bottom: 8px; text-transform: uppercase; letter-spacing: 1px; white-space: nowrap;">* * * CONGRATULATIONS! * * *</div>
    <div style="font-size: 14px; color: {{ $darkBlue }}; font-weight: bold; margin-top: 5px;">Certificate of Completion</div>
</div>

<!-- Presented To Section -->
<div class="section" style="text-align: center; margin-bottom: 10px;">
    <div style="font-size: 11px; color: #666; margin-bottom: 5px; font-style: italic;">
        This certificate is proudly presented to
    </div>
    <div style="font-size: 22px; font-weight: bold; color: {{ $mainColor }}; margin: 5px 0; padding: 8px 15px; border-top: 2px solid #d4af37; border-bottom: 2px solid #d4af37; display: inline-block; text-transform: uppercase; letter-spacing: 0.5px;">
        {{ $booking->customer_name ?? 'Tourist Name' }}
    </div>
</div>

<!-- Achievement Section -->
<div class="section" style="text-align: center; margin-bottom: 18px;">
    <div style="font-size: 11px; color: #333; line-height: 1.5; margin-bottom: 0;">
        <strong>Congratulations!</strong><br>
        You have successfully completed and enjoyed the tour organized by<br>
        <strong style="color: {{ $mainColor }}; font-size: 12px;">{{ \App\Models\OrganizationSetting::getSettings()->organization_name ?? 'Lau Paradise Adventures' }}</strong>
    </div>
</div>

<!-- Tour Information Box -->
<div class="section" style="margin-bottom: 18px;">
    <div class="billing-box" style="background: linear-gradient(135deg, #f0f8f4 0%, #e8f5e9 100%); border: 2px solid {{ $mainColor }}; text-align: center; padding: 15px;">
        <div class="billing-title" style="font-size: 11px; text-align: center; border-bottom: 2px solid {{ $mainColor }}; margin-bottom: 10px; padding-bottom: 5px;">
            Tour Destination / Location
        </div>
        <div style="font-size: 16px; font-weight: bold; color: {{ $darkBlue }}; text-transform: uppercase; letter-spacing: 0.5px;">
            @if($booking->tour)
                {{ $booking->tour->name }}
                @if($booking->tour->destination)
                    - {{ $booking->tour->destination->name }}
                @endif
            @else
                {{ $booking->tour_destination ?? 'Tanzania' }}
            @endif
        </div>
        @if($booking->departure_date && $booking->travel_end_date)
        <div style="font-size: 10px; color: #666; margin-top: 8px;">
            {{ $booking->departure_date->format('F d, Y') }} - {{ $booking->travel_end_date->format('F d, Y') }}
        </div>
        @endif
    </div>
</div>

<!-- Appreciation Message -->
<div class="section" style="text-align: center; margin-bottom: 18px;">
    <div style="font-size: 10px; color: #555; line-height: 1.6; font-style: italic; margin-bottom: 10px;">
        Your enthusiasm, positive spirit, and participation made this journey truly memorable.
    </div>
    <div style="font-size: 11px; color: #333; line-height: 1.6; font-weight: bold;">
        Thank you for choosing {{ \App\Models\OrganizationSetting::getSettings()->organization_name ?? 'Lau Paradise Adventures' }} —<br>
        we are honored to be part of your adventure.
    </div>
</div>

<!-- Date Section -->
<div style="text-align: center; margin-top: 12px; padding-top: 10px; border-top: 1px solid #dee2e6;">
    <div style="font-size: 10px; color: #666;">
        Date: <strong style="color: {{ $darkBlue }}; font-size: 11px;">{{ ($issueDate ?? now())->format('F d, Y') }}</strong>
    </div>
</div>
</div>
@endsection

@push('styles')
<style>
    /* Hide the auto-generated disclaimer - target div with specific inline styles */
    div[style*="margin-top: 30px"][style*="padding-top: 15px"][style*="font-size: 9px"],
    div[style*="margin-top: 30px"]:last-child {
        display: none !important;
        visibility: hidden !important;
        height: 0 !important;
        overflow: hidden !important;
        margin: 0 !important;
        padding: 0 !important;
        line-height: 0 !important;
        font-size: 0 !important;
    }
    
    /* Override pdf-container for full page border */
    .pdf-container {
        padding: 20mm 25mm !important;
        margin: 0 !important;
        max-width: 100% !important;
        position: relative;
        min-height: 250mm !important;
        box-sizing: border-box;
    }
    
    /* Full page outer border */
    body {
        border: 15px solid {{ $mainColor }} !important;
        margin: 0 !important;
        padding: 0 !important;
        box-sizing: border-box;
    }
    
    /* Inner double border */
    .pdf-container {
        border: 5px double #d4af37 !important;
    }
    
    .pdf-content {
        min-height: auto !important;
        position: relative;
        z-index: 1;
    }
    
    @page {
        margin: 0;
        size: A4 portrait;
    }
    
    body {
        font-size: 11px;
    }
    
    /* Ensure everything fits on one page */
    .section {
        page-break-inside: avoid;
        margin-bottom: 15px;
        position: relative;
        z-index: 1;
    }
    
    /* Reduce padding in billing boxes */
    .billing-box {
        padding: 12px !important;
        position: relative;
        z-index: 1;
    }
</style>
@endpush
