@extends('pdf.advanced-layout')

@php
    $documentTitle = 'DAILY OPERATION PLAN';
    $documentRef = 'OP-' . str_replace('-', '', $date);
    $documentDate = Carbon\Carbon::parse($date)->format('d-M-Y');
    $mainColor = '#3ea572';
    $darkBlue = '#2d7a5f';
@endphp

@section('content')
<!-- Operation Plan Header -->
<div style="text-align: center; margin-bottom: 30px; padding: 20px; background: #f8f9fa; border-radius: 10px;">
    <h1 style="color: {{ $mainColor }}; margin: 0 0 10px 0; font-size: 24pt;">DAILY OPERATION PLAN</h1>
    <p style="font-size: 12pt; color: #666; margin: 0; font-weight: bold;">{{ Carbon\Carbon::parse($date)->format('l, F d, Y') }}</p>
    <p style="font-size: 10pt; color: #999; margin-top: 5px;">Weather: {{ $weather ?? 'Sunny, 28°C' }}</p>
</div>

<!-- Teams in Field -->
<div class="section">
    <div class="section-title">Teams in Field</div>
    @forelse($operations as $operation)
    <div style="margin-bottom: 20px; padding: 15px; border: 1px solid #ddd; border-radius: 5px; page-break-inside: avoid;">
        <div style="display: flex; justify-content: space-between; margin-bottom: 10px;">
            <div>
                <strong style="color: {{ $mainColor }}; font-size: 12pt;">
                    {{ $operation->guide->name ?? 'Guide TBA' }} 
                    ({{ $operation->vehicle->registration_number ?? 'Vehicle TBA' }})
                </strong>
            </div>
            <div style="font-size: 10pt; color: #666;">
                {{ $operation->booking->booking_reference ?? 'N/A' }}
            </div>
        </div>
        <div style="font-size: 10pt; line-height: 1.8;">
            <p style="margin: 5px 0;"><strong>Client:</strong> {{ $operation->booking->customer_name ?? 'N/A' }}</p>
            <p style="margin: 5px 0;"><strong>Location:</strong> {{ $operation->location ?? 'In Field' }}</p>
            <p style="margin: 5px 0;"><strong>Status:</strong> {{ $operation->status ?? 'Active' }}</p>
            @if($operation->notes)
            <p style="margin: 5px 0;"><strong>Notes:</strong> {{ $operation->notes }}</p>
            @endif
        </div>
    </div>
    @empty
    <div style="text-align: center; padding: 40px; color: #999;">
        <p>No operations scheduled for this date</p>
    </div>
    @endforelse
</div>

<!-- HQ Tasks -->
<div class="section">
    <div class="section-title">HQ Tasks</div>
    <div style="padding: 15px; background: #f9fafb; border-radius: 5px;">
        <ul style="font-size: 11pt; line-height: 1.8; margin: 0; padding-left: 20px;">
            <li>Reconcile yesterday's park tickets</li>
            <li>Prepare vouchers for upcoming bookings</li>
            <li>Coordinate with suppliers</li>
            <li>Update booking statuses</li>
        </ul>
    </div>
</div>
@endsection


