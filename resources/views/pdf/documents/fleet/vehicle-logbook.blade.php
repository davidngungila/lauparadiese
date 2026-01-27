@extends('pdf.advanced-layout')

@php
    $documentTitle = 'VEHICLE LOGBOOK';
    $documentRef = 'VL-' . ($vehicle->registration_number ?? $vehicle->id);
    $documentDate = now()->format('d-M-Y');
    $mainColor = '#3ea572';
    $darkBlue = '#2d7a5f';
@endphp

@section('content')
<!-- Logbook Header -->
<div style="text-align: center; margin-bottom: 30px; padding: 20px; background: #f8f9fa; border-radius: 10px;">
    <h1 style="color: {{ $mainColor }}; margin: 0 0 10px 0; font-size: 24pt;">VEHICLE LOGBOOK</h1>
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
        <div class="info-row">
            <div class="info-label">Current Odometer:</div>
            <div class="info-value">{{ $vehicle->current_odometer ?? 'N/A' }} km</div>
        </div>
    </div>
</div>

<!-- Daily Log Entries -->
<div class="section">
    <div class="section-title">Daily Log Entry</div>
    <table class="data-table">
        <thead>
            <tr>
                <th style="width: 15%;">Date</th>
                <th style="width: 15%;">Driver</th>
                <th style="width: 10%;">Start Km</th>
                <th style="width: 10%;">End Km</th>
                <th style="width: 10%;">Distance</th>
                <th style="width: 20%;">Route</th>
                <th style="width: 20%;">Issues</th>
            </tr>
        </thead>
        <tbody>
            @forelse($operations as $operation)
            <tr>
                <td>{{ $operation->operation_date ? $operation->operation_date->format('d-M-Y') : 'N/A' }}</td>
                <td>{{ $operation->driver->name ?? 'N/A' }}</td>
                <td>{{ $operation->start_km ?? '-' }}</td>
                <td>{{ $operation->end_km ?? '-' }}</td>
                <td>
                    @if($operation->start_km && $operation->end_km)
                        {{ $operation->end_km - $operation->start_km }} km
                    @else
                        -
                    @endif
                </td>
                <td>{{ $operation->route ?? ($operation->location ?? 'N/A') }}</td>
                <td>{{ $operation->notes ?? 'None reported' }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="7" style="text-align: center; padding: 20px; color: #999;">No log entries for this period</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

<!-- Fuel Information -->
@if(isset($fuelInfo))
<div class="section">
    <div class="section-title">Fuel Information</div>
    <div class="info-grid">
        <div class="info-row">
            <div class="info-label">Fuel In:</div>
            <div class="info-value">{{ $fuelInfo['fuel_in'] ?? 'N/A' }}L</div>
        </div>
        <div class="info-row">
            <div class="info-label">Fuel Used:</div>
            <div class="info-value">{{ $fuelInfo['fuel_used'] ?? 'N/A' }}L</div>
        </div>
        <div class="info-row">
            <div class="info-label">Next Service Due:</div>
            <div class="info-value">{{ $fuelInfo['next_service'] ?? 'N/A' }}km</div>
        </div>
    </div>
</div>
@endif

<!-- Driver Signature -->
<div style="margin-top: 30px; padding-top: 20px; border-top: 2px solid #ddd;">
    <div style="display: table; width: 100%;">
        <div style="display: table-cell; width: 50%; vertical-align: top;">
            <p style="border-top: 1px solid #333; padding-top: 5px; margin-top: 40px;">Driver Signature</p>
        </div>
        <div style="display: table-cell; width: 50%; vertical-align: top; text-align: right;">
            <p style="border-top: 1px solid #333; padding-top: 5px; margin-top: 40px;">Date</p>
        </div>
    </div>
</div>
@endsection


