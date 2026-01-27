@extends('pdf.advanced-layout')

@php
    $documentTitle = 'CUSTOMERS EXPORT';
    $documentRef = 'EXP-' . date('Ymd');
    $documentDate = now()->format('d M Y');
    $mainColor = '#3ea572';
    $darkBlue = '#2d7a5f';
@endphp

@section('content')
<div class="section">
    <div class="section-title">Customers List ({{ count($customers) }} Total)</div>
    
    <table class="data-table">
        <thead>
            <tr>
                <th style="width: 5%;">#</th>
                <th style="width: 20%;">Name</th>
                <th style="width: 20%;">Email</th>
                <th style="width: 15%;">Phone</th>
                <th style="width: 15%;">Country</th>
                <th style="width: 10%;">Status</th>
                <th style="width: 15%;">Registered</th>
            </tr>
        </thead>
        <tbody>
            @foreach($customers as $index => $customer)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td><strong>{{ $customer->name }}</strong></td>
                <td>{{ $customer->email }}</td>
                <td>{{ $customer->phone ?? 'N/A' }}</td>
                <td>{{ $customer->country ?? 'N/A' }}</td>
                <td>
                    <span class="status-badge status-{{ $customer->email_verified_at ? 'confirmed' : 'pending' }}">
                        {{ $customer->email_verified_at ? 'Verified' : 'Pending' }}
                    </span>
                </td>
                <td>{{ $customer->created_at->format('d M Y') }}</td>
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
            <div class="info-label">Total Customers:</div>
            <div class="info-value"><strong>{{ count($customers) }}</strong></div>
        </div>
        <div class="info-row">
            <div class="info-label">Verified Customers:</div>
            <div class="info-value">{{ $customers->whereNotNull('email_verified_at')->count() }}</div>
        </div>
        <div class="info-row">
            <div class="info-label">Unverified Customers:</div>
            <div class="info-value">{{ $customers->whereNull('email_verified_at')->count() }}</div>
        </div>
        <div class="info-row">
            <div class="info-label">Export Date:</div>
            <div class="info-value">{{ now()->format('d M Y, H:i:s') }}</div>
        </div>
    </div>
</div>
@endsection




