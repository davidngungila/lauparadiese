@extends('pdf.advanced-layout')

@php
    $documentTitle = 'TOURS EXPORT';
    $documentRef = 'EXP-' . date('Ymd');
    $documentDate = now()->format('d M Y');
    $mainColor = '#3ea572';
    $darkBlue = '#2d7a5f';
@endphp

@section('content')
<div class="section">
    <div class="section-title">Tours List ({{ count($tours) }} Total)</div>
    
    <table class="data-table">
        <thead>
            <tr>
                <th style="width: 5%;">#</th>
                <th style="width: 25%;">Tour Name</th>
                <th style="width: 15%;">Destination</th>
                <th style="width: 10%;">Duration</th>
                <th style="width: 12%;">Price</th>
                <th style="width: 10%;">Status</th>
                <th style="width: 8%;">Capacity</th>
                <th style="width: 15%;">Categories</th>
            </tr>
        </thead>
        <tbody>
            @foreach($tours as $index => $tour)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td><strong>{{ $tour->name }}</strong></td>
                <td>{{ $tour->destinations->first()->name ?? 'N/A' }}</td>
                <td>{{ $tour->duration_days }} Days</td>
                <td class="text-right">
                    <strong>{{ $tour->currency ?? 'USD' }} {{ number_format($tour->price, 2) }}</strong>
                </td>
                <td>
                    <span class="status-badge status-{{ $tour->status === 'active' ? 'confirmed' : 'cancelled' }}">
                        {{ ucfirst($tour->status) }}
                    </span>
                </td>
                <td class="text-center">{{ $tour->max_capacity ?? 'N/A' }}</td>
                <td>
                    @if($tour->categories->count() > 0)
                        {{ $tour->categories->pluck('name')->implode(', ') }}
                    @else
                        N/A
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

<!-- Summary Section -->
<div class="section">
    <div class="section-title">Summary</div>
    <div class="info-grid">
        <div class="info-row">
            <div class="info-label">Total Tours:</div>
            <div class="info-value"><strong>{{ count($tours) }}</strong></div>
        </div>
        <div class="info-row">
            <div class="info-label">Active Tours:</div>
            <div class="info-value">{{ $tours->where('status', 'active')->count() }}</div>
        </div>
        <div class="info-row">
            <div class="info-label">Inactive Tours:</div>
            <div class="info-value">{{ $tours->where('status', 'inactive')->count() }}</div>
        </div>
        <div class="info-row">
            <div class="info-label">Average Price:</div>
            <div class="info-value">
                <strong>{{ $tours->first()->currency ?? 'USD' }} {{ number_format($tours->avg('price'), 2) }}</strong>
            </div>
        </div>
    </div>
</div>
@endsection




