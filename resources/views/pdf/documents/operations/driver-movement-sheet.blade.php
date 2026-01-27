@extends('pdf.advanced-layout')

@php
    $documentTitle = 'DRIVER MOVEMENT SHEET';
    $documentRef = 'DM-' . str_replace('-', '', $date);
    $documentDate = Carbon\Carbon::parse($date)->format('d-M-Y');
    $mainColor = '#3ea572';
    $darkBlue = '#2d7a5f';
@endphp

@section('content')
<!-- Movement Sheet Header -->
<div style="text-align: center; margin-bottom: 30px; padding: 20px; background: #f8f9fa; border-radius: 10px;">
    <h1 style="color: {{ $mainColor }}; margin: 0 0 10px 0; font-size: 24pt;">DRIVER MOVEMENT SHEET</h1>
    <p style="font-size: 12pt; color: #666; margin: 0; font-weight: bold;">Date: {{ Carbon\Carbon::parse($date)->format('l, F d, Y') }}</p>
</div>

<!-- Movement Details -->
@forelse($operations as $operation)
<div class="section" style="page-break-inside: avoid; margin-bottom: 25px; border: 1px solid #ddd; padding: 20px; border-radius: 5px;">
    <div style="background: #f0f8f4; padding: 12px; border-left: 4px solid {{ $mainColor }}; margin-bottom: 15px;">
        <div style="font-weight: bold; color: {{ $mainColor }}; font-size: 12pt;">
            Vehicle: {{ $operation->vehicle->registration_number ?? 'TBA' }} | 
            Driver: {{ $operation->driver->name ?? 'TBA' }}
        </div>
    </div>
    
    <table class="data-table">
        <thead>
            <tr>
                <th style="width: 20%;">Item</th>
                <th style="width: 80%;">Details</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td><strong>Start Km</strong></td>
                <td>{{ $operation->start_km ?? 'TBA' }}</td>
            </tr>
            <tr>
                <td><strong>End Km</strong></td>
                <td>{{ $operation->end_km ?? 'TBA' }}</td>
            </tr>
            <tr>
                <td><strong>Total Km</strong></td>
                <td>
                    @if($operation->start_km && $operation->end_km)
                        {{ $operation->end_km - $operation->start_km }} km
                    @else
                        TBA
                    @endif
                </td>
            </tr>
            <tr>
                <td><strong>Route</strong></td>
                <td>{{ $operation->route ?? ($operation->booking->tour->start_location ?? 'N/A') . ' → ' . ($operation->booking->tour->end_location ?? 'N/A') }}</td>
            </tr>
            <tr>
                <td><strong>Checkpoints</strong></td>
                <td>
                    @if($operation->checkpoints)
                        {{ $operation->checkpoints }}
                    @else
                        Left: {{ $operation->location ?? 'N/A' }} | 
                        Cleared: {{ $operation->checkpoint_time ?? 'N/A' }} | 
                        Arrived: {{ $operation->arrival_time ?? 'N/A' }}
                    @endif
                </td>
            </tr>
            <tr>
                <td><strong>Client</strong></td>
                <td>{{ $operation->booking->customer_name ?? 'N/A' }} ({{ $operation->booking->booking_reference ?? 'N/A' }})</td>
            </tr>
        </tbody>
    </table>
    
    <!-- Driver Signature -->
    <div style="margin-top: 20px; padding-top: 15px; border-top: 1px solid #ddd;">
        <div style="display: table; width: 100%;">
            <div style="display: table-cell; width: 50%;">
                <p style="border-top: 1px solid #333; padding-top: 5px; margin-top: 40px;">Driver Signature</p>
            </div>
            <div style="display: table-cell; width: 50%; text-align: right;">
                <p style="border-top: 1px solid #333; padding-top: 5px; margin-top: 40px;">Date</p>
            </div>
        </div>
    </div>
</div>
@empty
<div style="text-align: center; padding: 40px; color: #999;">
    <p>No driver movements for this date</p>
</div>
@endforelse
@endsection


