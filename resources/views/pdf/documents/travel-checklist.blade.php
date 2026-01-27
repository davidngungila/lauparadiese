@extends('pdf.advanced-layout')

@php
    $documentTitle = 'TRAVEL CHECKLIST';
    $documentRef = 'TC-' . $booking->booking_reference;
    $documentDate = now()->format('d-M-Y');
    $mainColor = '#3ea572';
    $darkBlue = '#2d7a5f';
@endphp

@section('content')
<!-- Checklist Header -->
<div style="text-align: center; margin-bottom: 30px; padding: 20px; background: linear-gradient(135deg, #e0f2fe 0%, #bae6fd 100%); border-radius: 10px;">
    <h1 style="color: #0369a1; margin: 0 0 10px 0; font-size: 24pt;">PRE-DEPARTURE CHECKLIST</h1>
    <p style="font-size: 12pt; color: #075985; margin: 0; font-weight: bold;">Booking Reference: {{ $booking->booking_reference }}</p>
    <p style="font-size: 10pt; color: #666; margin-top: 5px;">Departure Date: {{ $booking->departure_date ? $booking->departure_date->format('F d, Y') : 'N/A' }}</p>
</div>

<!-- Travel Information -->
<div class="billing-section">
    <div class="billing-box">
        <div class="billing-title">Traveler Information</div>
        <div class="billing-content">
            <p><strong>{{ $booking->customer_name }}</strong></p>
            <p>Travelers: {{ $booking->travelers }} {{ Str::plural('Person', $booking->travelers) }}</p>
            @if($booking->tour)
            <p>Tour: {{ $booking->tour->name }}</p>
            @endif
        </div>
    </div>
    <div class="billing-box">
        <div class="billing-title">Travel Details</div>
        <div class="billing-content">
            <p><strong>Departure:</strong> {{ $booking->departure_date ? $booking->departure_date->format('F d, Y') : 'N/A' }}</p>
            @if($booking->travel_end_date)
            <p><strong>Return:</strong> {{ $booking->travel_end_date->format('F d, Y') }}</p>
            @endif
            @if($booking->pickup_location)
            <p><strong>Pickup:</strong> {{ $booking->pickup_location }}</p>
            @endif
        </div>
    </div>
</div>

<!-- Pre-Departure Checklist -->
<div class="section">
    <div class="section-title">Essential Documents Checklist</div>
    <table class="data-table">
        <thead>
            <tr>
                <th style="width: 5%;">✓</th>
                <th style="width: 95%;">Item</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td style="text-align: center;">☐</td>
                <td><strong>Passport</strong> - Valid for 6+ months after arrival date</td>
            </tr>
            <tr>
                <td style="text-align: center;">☐</td>
                <td><strong>Printed eVisa/Visa</strong> - Ensure visa is valid for Tanzania</td>
            </tr>
            <tr>
                <td style="text-align: center;">☐</td>
                <td><strong>Yellow Fever Vaccination Certificate</strong> - Required for entry</td>
            </tr>
            <tr>
                <td style="text-align: center;">☐</td>
                <td><strong>Travel Insurance Policy Copy</strong> - Medical and trip cancellation coverage</td>
            </tr>
            <tr>
                <td style="text-align: center;">☐</td>
                <td><strong>Printed Booking Vouchers & Itinerary</strong> - Keep all documents together</td>
            </tr>
            <tr>
                <td style="text-align: center;">☐</td>
                <td><strong>USD Cash</strong> - Small bills for tips and souvenirs (recommended: $100-200)</td>
            </tr>
            <tr>
                <td style="text-align: center;">☐</td>
                <td><strong>Credit/Debit Cards</strong> - For emergencies and larger purchases</td>
            </tr>
        </tbody>
    </table>
</div>

<!-- Packing Checklist -->
<div class="section">
    <div class="section-title">Packing Essentials</div>
    <div style="display: flex; gap: 20px; flex-wrap: wrap;">
        <div style="flex: 1; min-width: 250px;">
            <div style="font-weight: bold; color: {{ $mainColor }}; margin-bottom: 10px; font-size: 11pt;">Clothing & Accessories:</div>
            <ul style="font-size: 10pt; line-height: 1.8; margin: 0; padding-left: 20px;">
                <li>Neutral-colored clothing (beige, khaki, green)</li>
                <li>Long-sleeved shirts and pants (mosquito protection)</li>
                <li>Warm layers (sweater/jacket for early mornings)</li>
                <li>Comfortable walking shoes</li>
                <li>Hat or cap</li>
                <li>Sunglasses</li>
            </ul>
        </div>
        <div style="flex: 1; min-width: 250px;">
            <div style="font-weight: bold; color: {{ $mainColor }}; margin-bottom: 10px; font-size: 11pt;">Equipment & Supplies:</div>
            <ul style="font-size: 10pt; line-height: 1.8; margin: 0; padding-left: 20px;">
                <li>Binoculars (essential for wildlife viewing)</li>
                <li>Camera with extra batteries/memory cards</li>
                <li>Sunscreen (SPF 30+)</li>
                <li>Insect repellent</li>
                <li>Power adapters (Type G for Tanzania)</li>
                <li>Portable charger/power bank</li>
            </ul>
        </div>
    </div>
</div>

<!-- Health & Safety -->
<div class="section">
    <div class="section-title">Health & Safety Reminders</div>
    <div style="background-color: #fef3c7; border-left: 4px solid #f59e0b; padding: 15px; margin: 15px 0;">
        <ul style="font-size: 10pt; line-height: 1.8; margin: 0; padding-left: 20px;">
            <li>Consult your doctor about malaria prophylaxis</li>
            <li>Pack personal medications and prescriptions</li>
            <li>Stay hydrated - drink plenty of bottled water</li>
            <li>Use insect repellent, especially at dawn and dusk</li>
            <li>Follow guide instructions for wildlife viewing safety</li>
        </ul>
    </div>
</div>

<!-- Important Contacts -->
@php
    $org = \App\Models\OrganizationSetting::getSettings();
@endphp
<div class="section">
    <div class="section-title">Important Contacts</div>
    <div class="info-grid">
        <div class="info-row">
            <div class="info-label">Emergency Contact:</div>
            <div class="info-value">{{ $org->phone ?? 'N/A' }}</div>
        </div>
        <div class="info-row">
            <div class="info-label">Email:</div>
            <div class="info-value">{{ $org->email ?? 'N/A' }}</div>
        </div>
        <div class="info-row">
            <div class="info-label">Booking Reference:</div>
            <div class="info-value"><strong>{{ $booking->booking_reference }}</strong></div>
        </div>
    </div>
</div>

<!-- Final Reminder -->
<div style="background-color: #dbeafe; border-left: 4px solid #2563eb; padding: 15px; margin: 20px 0;">
    <div style="font-weight: bold; color: #1e40af; margin-bottom: 10px;">💡 Final Reminder:</div>
    <div style="font-size: 10pt; line-height: 1.8; color: #1e40af;">
        <p style="margin-bottom: 5px;">Please check off each item as you pack to ensure nothing is forgotten.</p>
        <p style="margin-bottom: 5px;">Keep all important documents in a safe, easily accessible place.</p>
        <p>Have a wonderful and safe journey!</p>
    </div>
</div>
@endsection


