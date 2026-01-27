@extends('admin.layouts.app')

@section('title', 'Analytics - Statistics')
@section('description', 'Comprehensive Analytics Dashboard')

@section('content')
@php
    $monthlyBookingsMonths = $monthlyBookings['months'] ?? [];
    $monthlyBookingsAmounts = $monthlyBookings['amounts'] ?? [];
    $revenueTrendsMonths = $revenueTrends['months'] ?? [];
    $revenueTrendsAmounts = $revenueTrends['amounts'] ?? [];
    $bookingTrendsDates = $bookingTrends['dates'] ?? [];
    $bookingTrendsAmounts = $bookingTrends['amounts'] ?? [];
@endphp

<!-- Page Header -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <h4 class="mb-1">Analytics Dashboard</h4>
                        <p class="mb-0 text-body-secondary">Comprehensive insights into your tour business performance</p>
                    </div>
                    <div class="btn-group">
                        <button type="button" class="btn btn-outline-primary dropdown-toggle" data-bs-toggle="dropdown">
                            <i class="ri-download-2-line me-1"></i>Export
                        </button>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="javascript:void(0);">Export as PDF</a></li>
                            <li><a class="dropdown-item" href="javascript:void(0);">Export as Excel</a></li>
                            <li><a class="dropdown-item" href="javascript:void(0);">Export as CSV</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Key Metrics Cards -->
<div class="row g-6 mb-4">
    <div class="col-sm-6 col-lg-3">
        <div class="card card-border-shadow-primary h-100">
            <div class="card-body">
                <div class="d-flex align-items-center mb-2">
                    <div class="avatar me-4">
                        <span class="avatar-initial rounded bg-label-primary">
                            <i class="icon-base ri ri-calendar-check-line icon-24px"></i>
                        </span>
                    </div>
                    <h4 class="mb-0">{{ number_format($stats['total_bookings'] ?? 0) }}</h4>
                </div>
                <h6 class="mb-0 fw-normal">Total Bookings</h6>
                <p class="mb-0 mt-2">
                    <span class="me-1 fw-medium {{ ($stats['booking_growth'] ?? 0) >= 0 ? 'text-success' : 'text-danger' }}">
                        {{ ($stats['booking_growth'] ?? 0) >= 0 ? '+' : '' }}{{ number_format($stats['booking_growth'] ?? 0, 1) }}%
                    </span>
                    <small class="text-body-secondary">vs last month</small>
                </p>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-lg-3">
        <div class="card card-border-shadow-success h-100">
            <div class="card-body">
                <div class="d-flex align-items-center mb-2">
                    <div class="avatar me-4">
                        <span class="avatar-initial rounded bg-label-success">
                            <i class="icon-base ri ri-checkbox-circle-line icon-24px"></i>
                        </span>
                    </div>
                    <h4 class="mb-0">{{ number_format($stats['confirmed_bookings'] ?? 0) }}</h4>
                </div>
                <h6 class="mb-0 fw-normal">Confirmed Bookings</h6>
                <p class="mb-0 mt-2">
                    <span class="me-1 fw-medium text-success">Active</span>
                    <small class="text-body-secondary">{{ number_format($stats['pending_bookings'] ?? 0) }} pending</small>
                </p>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-lg-3">
        <div class="card card-border-shadow-info h-100">
            <div class="card-body">
                <div class="d-flex align-items-center mb-2">
                    <div class="avatar me-4">
                        <span class="avatar-initial rounded bg-label-info">
                            <i class="icon-base ri ri-money-dollar-circle-line icon-24px"></i>
                        </span>
                    </div>
                    <h4 class="mb-0">${{ number_format(($stats['total_revenue'] ?? 0) / 1000, 1) }}k</h4>
                </div>
                <h6 class="mb-0 fw-normal">Total Revenue</h6>
                <p class="mb-0 mt-2">
                    <span class="me-1 fw-medium {{ ($stats['revenue_growth'] ?? 0) >= 0 ? 'text-success' : 'text-danger' }}">
                        {{ ($stats['revenue_growth'] ?? 0) >= 0 ? '+' : '' }}{{ number_format($stats['revenue_growth'] ?? 0, 1) }}%
                    </span>
                    <small class="text-body-secondary">vs last month</small>
                </p>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-lg-3">
        <div class="card card-border-shadow-warning h-100">
            <div class="card-body">
                <div class="d-flex align-items-center mb-2">
                    <div class="avatar me-4">
                        <span class="avatar-initial rounded bg-label-warning">
                            <i class="icon-base ri ri-group-line icon-24px"></i>
                        </span>
                    </div>
                    <h4 class="mb-0">{{ number_format($stats['total_travelers'] ?? 0) }}</h4>
                </div>
                <h6 class="mb-0 fw-normal">Total Travelers</h6>
                <p class="mb-0 mt-2">
                    <span class="me-1 fw-medium text-body-secondary">Avg: ${{ number_format($stats['avg_booking_value'] ?? 0, 0) }}</span>
                    <small class="text-body-secondary">per booking</small>
                </p>
            </div>
        </div>
    </div>
</div>

<div class="row g-6">
    <!-- Monthly Bookings Chart -->
    <div class="col-lg-8">
        <div class="card h-100">
            <div class="card-header d-flex align-items-center justify-content-between">
                <div class="card-title mb-0">
                    <h5 class="m-0 me-2">Monthly Bookings Overview</h5>
                    <span class="card-subtitle">Bookings trend for {{ date('Y') }}</span>
                </div>
                <div class="dropdown">
                    <button class="btn p-0" type="button" id="monthlyBookings" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="icon-base ri ri-more-2-line"></i>
                    </button>
                    <div class="dropdown-menu dropdown-menu-end" aria-labelledby="monthlyBookings">
                        <a class="dropdown-item" href="javascript:void(0);">View Details</a>
                        <a class="dropdown-item" href="javascript:void(0);">Export Data</a>
                        <a class="dropdown-item" href="javascript:void(0);">Refresh</a>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div id="monthlyBookingsChart"></div>
            </div>
        </div>
    </div>
    
    <!-- Revenue Trends Chart -->
    <div class="col-lg-4">
        <div class="card h-100">
            <div class="card-header d-flex align-items-center justify-content-between">
                <div class="card-title mb-0">
                    <h5 class="m-0 me-2">Revenue Trends</h5>
                    <span class="card-subtitle">Monthly revenue</span>
                </div>
            </div>
            <div class="card-body">
                <div id="revenueTrendsChart"></div>
            </div>
        </div>
    </div>
</div>

<div class="row g-6 mt-4">
    <!-- Booking Trends (Last 30 Days) -->
    <div class="col-lg-6">
        <div class="card h-100">
            <div class="card-header d-flex align-items-center justify-content-between">
                <div class="card-title mb-0">
                    <h5 class="m-0 me-2">Booking Trends</h5>
                    <span class="card-subtitle">Last 30 days</span>
                </div>
            </div>
            <div class="card-body">
                <div id="bookingTrendsChart"></div>
            </div>
        </div>
    </div>
    
    <!-- Tour Performance -->
    <div class="col-lg-6">
        <div class="card h-100">
            <div class="card-header d-flex align-items-center justify-content-between">
                <div class="card-title mb-0">
                    <h5 class="m-0 me-2">Top Performing Tours</h5>
                    <span class="card-subtitle">By bookings count</span>
                </div>
                <div class="dropdown">
                    <button class="btn p-0" type="button" id="tourPerformance" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="icon-base ri ri-more-2-line"></i>
                    </button>
                    <div class="dropdown-menu dropdown-menu-end" aria-labelledby="tourPerformance">
                        <a class="dropdown-item" href="javascript:void(0);">View All Tours</a>
                        <a class="dropdown-item" href="javascript:void(0);">Export</a>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-borderless">
                        <thead>
                            <tr>
                                <th>Tour Name</th>
                                <th class="text-end">Bookings</th>
                                <th class="text-end">Revenue</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($tourPerformance as $tour)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="avatar avatar-sm me-2">
                                            <span class="avatar-initial rounded bg-label-primary">
                                                <i class="ri-map-2-line"></i>
                                            </span>
                                        </div>
                                        <span class="fw-medium">{{ \Illuminate\Support\Str::limit($tour['name'], 30) }}</span>
                                    </div>
                                </td>
                                <td class="text-end">
                                    <span class="badge bg-label-primary">{{ $tour['bookings'] }}</span>
                                </td>
                                <td class="text-end">
                                    <span class="fw-medium">${{ number_format($tour['revenue'], 0) }}</span>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="3" class="text-center text-muted">No tour data available</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row g-6 mt-4">
    <!-- Destination Statistics -->
    <div class="col-lg-6">
        <div class="card h-100">
            <div class="card-header d-flex align-items-center justify-content-between">
                <div class="card-title mb-0">
                    <h5 class="m-0 me-2">Top Destinations</h5>
                    <span class="card-subtitle">By active tours</span>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-borderless">
                        <thead>
                            <tr>
                                <th>Destination</th>
                                <th class="text-end">Active Tours</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($destinationStats as $destination)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="avatar avatar-sm me-2">
                                            <span class="avatar-initial rounded bg-label-info">
                                                <i class="ri-map-pin-line"></i>
                                            </span>
                                        </div>
                                        <span class="fw-medium">{{ $destination->name }}</span>
                                    </div>
                                </td>
                                <td class="text-end">
                                    <span class="badge bg-label-info">{{ $destination->tours_count ?? 0 }}</span>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="2" class="text-center text-muted">No destination data available</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Customer Statistics -->
    <div class="col-lg-6">
        <div class="card h-100">
            <div class="card-header d-flex align-items-center justify-content-between">
                <div class="card-title mb-0">
                    <h5 class="m-0 me-2">Customer Statistics</h5>
                    <span class="card-subtitle">Customer insights</span>
                </div>
            </div>
            <div class="card-body">
                <div class="d-flex flex-column gap-4">
                    <div class="d-flex align-items-center justify-content-between">
                        <div class="d-flex align-items-center">
                            <div class="avatar me-3">
                                <span class="avatar-initial rounded bg-label-primary">
                                    <i class="ri-user-line"></i>
                                </span>
                            </div>
                            <div>
                                <h6 class="mb-0">Total Customers</h6>
                                <small class="text-body-secondary">All registered customers</small>
                            </div>
                        </div>
                        <h4 class="mb-0">{{ number_format($customerStats['total'] ?? 0) }}</h4>
                    </div>
                    <div class="d-flex align-items-center justify-content-between">
                        <div class="d-flex align-items-center">
                            <div class="avatar me-3">
                                <span class="avatar-initial rounded bg-label-success">
                                    <i class="ri-user-add-line"></i>
                                </span>
                            </div>
                            <div>
                                <h6 class="mb-0">New This Month</h6>
                                <small class="text-body-secondary">New customers registered</small>
                            </div>
                        </div>
                        <h4 class="mb-0">{{ number_format($customerStats['new_this_month'] ?? 0) }}</h4>
                    </div>
                    <div class="d-flex align-items-center justify-content-between">
                        <div class="d-flex align-items-center">
                            <div class="avatar me-3">
                                <span class="avatar-initial rounded bg-label-info">
                                    <i class="ri-user-star-line"></i>
                                </span>
                            </div>
                            <div>
                                <h6 class="mb-0">Active Customers</h6>
                                <small class="text-body-secondary">Customers with bookings</small>
                            </div>
                        </div>
                        <h4 class="mb-0">{{ number_format($customerStats['active'] ?? 0) }}</h4>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script src="{{ asset('assets/vendor/libs/apex-charts/apexcharts.js') }}"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Monthly Bookings Chart
    const monthlyBookingsOptions = {
        series: [{
            name: 'Bookings',
            data: @json($monthlyBookingsAmounts)
        }],
        chart: {
            type: 'bar',
            height: 350,
            toolbar: { show: false }
        },
        colors: ['#3ea572'],
        plotOptions: {
            bar: {
                borderRadius: 6,
                horizontal: false,
            }
        },
        dataLabels: {
            enabled: false
        },
        xaxis: {
            categories: @json($monthlyBookingsMonths)
        },
        tooltip: {
            y: {
                formatter: function(val) {
                    return val + ' bookings'
                }
            }
        }
    };
    const monthlyBookingsChart = new ApexCharts(document.querySelector("#monthlyBookingsChart"), monthlyBookingsOptions);
    monthlyBookingsChart.render();
    
    // Revenue Trends Chart
    const revenueTrendsOptions = {
        series: [{
            name: 'Revenue',
            data: @json($revenueTrendsAmounts)
        }],
        chart: {
            type: 'area',
            height: 350,
            toolbar: { show: false }
        },
        colors: ['#3ea572'],
        fill: {
            type: 'gradient',
            gradient: {
                shadeIntensity: 1,
                opacityFrom: 0.7,
                opacityTo: 0.3,
                stops: [0, 90, 100]
            }
        },
        dataLabels: {
            enabled: false
        },
        xaxis: {
            categories: @json($revenueTrendsMonths)
        },
        tooltip: {
            y: {
                formatter: function(val) {
                    return '$' + val.toLocaleString()
                }
            }
        }
    };
    const revenueTrendsChart = new ApexCharts(document.querySelector("#revenueTrendsChart"), revenueTrendsOptions);
    revenueTrendsChart.render();
    
    // Booking Trends Chart (Last 30 Days)
    const bookingTrendsOptions = {
        series: [{
            name: 'Bookings',
            data: @json($bookingTrendsAmounts)
        }],
        chart: {
            type: 'line',
            height: 300,
            toolbar: { show: false }
        },
        colors: ['#3ea572'],
        stroke: {
            curve: 'smooth',
            width: 3
        },
        dataLabels: {
            enabled: false
        },
        xaxis: {
            categories: @json($bookingTrendsDates)
        },
        tooltip: {
            y: {
                formatter: function(val) {
                    return val + ' bookings'
                }
            }
        }
    };
    const bookingTrendsChart = new ApexCharts(document.querySelector("#bookingTrendsChart"), bookingTrendsOptions);
    bookingTrendsChart.render();
});
</script>
@endpush

