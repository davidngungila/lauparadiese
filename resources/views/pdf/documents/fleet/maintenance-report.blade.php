@extends('pdf.advanced-layout')

@php
    $documentTitle = 'VEHICLE MAINTENANCE REPORT';
    $documentRef = 'MR-' . ($vehicle->registration_number ?? $vehicle->id) . '-' . now()->format('Ymd');
    $documentDate = now()->format('d-M-Y');
    $mainColor = '#3ea572';
    $darkBlue = '#2d7a5f';
@endphp

@section('content')
<!-- Maintenance Report Header -->
<div style="text-align: center; margin-bottom: 30px; padding: 20px; background: #f8f9fa; border-radius: 10px;">
    <h1 style="color: {{ $mainColor }}; margin: 0 0 10px 0; font-size: 24pt;">VEHICLE MAINTENANCE REPORT</h1>
    <p style="font-size: 12pt; color: #666; margin: 0; font-weight: bold;">Vehicle: {{ $vehicle->registration_number ?? 'N/A' }}</p>
    <p style="font-size: 10pt; color: #999; margin-top: 5px;">Date: {{ now()->format('F d, Y') }}</p>
</div>

<!-- Vehicle Information -->
<div class="section">
    <div class="section-title">Vehicle Information</div>
    <div class="info-grid">
        <div class="info-row">
            <div class="info-label">Registration Number:</div>
            <div class="info-value"><strong>{{ $vehicle->registration_number ?? 'N/A' }}</strong></div>
        </div>
        <div class="info-row">
            <div class="info-label">Make & Model:</div>
            <div class="info-value">{{ $vehicle->make ?? 'N/A' }} {{ $vehicle->model ?? '' }}</div>
        </div>
        <div class="info-row">
            <div class="info-label">Year:</div>
            <div class="info-value">{{ $vehicle->year ?? 'N/A' }}</div>
        </div>
    </div>
</div>

<!-- Maintenance Details -->
<div class="section">
    <div class="section-title">Maintenance Details</div>
    <table class="data-table">
        <thead>
            <tr>
                <th style="width: 30%;">Item</th>
                <th style="width: 70%;">Details</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td><strong>Reported Issue</strong></td>
                <td>{{ $maintenance->issue ?? 'Routine maintenance' }}</td>
            </tr>
            <tr>
                <td><strong>Action Taken</strong></td>
                <td>{{ $maintenance->action ?? 'Service completed' }}</td>
            </tr>
            <tr>
                <td><strong>Parts Used</strong></td>
                <td>{{ $maintenance->parts ?? 'Standard parts' }}</td>
            </tr>
            <tr>
                <td><strong>Cost</strong></td>
                <td><strong>{{ $maintenance->currency ?? 'USD' }} {{ number_format($maintenance->cost ?? 0, 2) }}</strong></td>
            </tr>
            <tr>
                <td><strong>Mechanic</strong></td>
                <td>{{ $maintenance->mechanic_name ?? 'N/A' }}</td>
            </tr>
        </tbody>
    </table>
</div>

<!-- Authorization -->
<div style="margin-top: 40px; padding-top: 20px; border-top: 2px solid #ddd;">
    <div style="display: table; width: 100%;">
        <div style="display: table-cell; width: 50%; vertical-align: top;">
            <p style="margin-bottom: 40px;"><strong>Mechanic:</strong></p>
            <p style="border-top: 1px solid #333; padding-top: 5px; margin-top: 40px;">Mechanic Signature</p>
        </div>
        <div style="display: table-cell; width: 50%; vertical-align: top; text-align: right;">
            <p style="margin-bottom: 40px;"><strong>Date:</strong> {{ now()->format('F d, Y') }}</p>
            <p style="border-top: 1px solid #333; padding-top: 5px; margin-top: 40px;">Date</p>
        </div>
    </div>
</div>
@endsection


