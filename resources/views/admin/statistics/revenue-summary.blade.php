@extends('admin.layouts.app')

@section('title', 'Revenue Summary - Statistics')
@section('description', 'Comprehensive Revenue Analysis')

@section('content')
@php
    $monthlyRevenueMonths = $monthlyRevenue['months'] ?? [];
    $monthlyRevenueAmounts = $monthlyRevenue['amounts'] ?? [];
@endphp

<!-- Page Header -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <h4 class="mb-1">Revenue Summary</h4>
                        <p class="mb-0 text-body-secondary">Comprehensive financial overview and revenue analysis</p>
                    </div>
                    <div class="btn-group">
                        <button type="button" class="btn btn-outline-primary dropdown-toggle" data-bs-toggle="dropdown">
                            <i class="ri-download-2-line me-1"></i>Export Report
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

<!-- Revenue Summary Cards -->
<div class="row g-6 mb-4">
    <div class="col-sm-6 col-lg-3">
        <div class="card card-border-shadow-primary h-100">
            <div class="card-body">
                <div class="d-flex align-items-center mb-2">
                    <div class="avatar me-4">
                        <span class="avatar-initial rounded bg-label-primary">
                            <i class="icon-base ri ri-money-dollar-circle-line icon-24px"></i>
                        </span>
                    </div>
                    <div>
                        <h4 class="mb-0">${{ number_format(($revenueData['total'] ?? 0) / 1000, 1) }}k</h4>
                        <small class="text-body-secondary">Total Revenue</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-lg-3">
        <div class="card card-border-shadow-success h-100">
            <div class="card-body">
                <div class="d-flex align-items-center mb-2">
                    <div class="avatar me-4">
                        <span class="avatar-initial rounded bg-label-success">
                            <i class="icon-base ri ri-calendar-line icon-24px"></i>
                        </span>
                    </div>
                    <div>
                        <h4 class="mb-0">${{ number_format(($revenueData['this_month'] ?? 0) / 1000, 1) }}k</h4>
                        <small class="text-body-secondary">This Month</small>
                    </div>
                </div>
                <p class="mb-0 mt-2">
                    <span class="me-1 fw-medium {{ ($revenueData['growth'] ?? 0) >= 0 ? 'text-success' : 'text-danger' }}">
                        {{ ($revenueData['growth'] ?? 0) >= 0 ? '+' : '' }}{{ number_format($revenueData['growth'] ?? 0, 1) }}%
                    </span>
                    <small class="text-body-secondary">vs last month</small>
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
                            <i class="icon-base ri ri-calendar-check-line icon-24px"></i>
                        </span>
                    </div>
                    <div>
                        <h4 class="mb-0">${{ number_format(($revenueData['last_month'] ?? 0) / 1000, 1) }}k</h4>
                        <small class="text-body-secondary">Last Month</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-lg-3">
        <div class="card card-border-shadow-warning h-100">
            <div class="card-body">
                <div class="d-flex align-items-center mb-2">
                    <div class="avatar me-4">
                        <span class="avatar-initial rounded bg-label-warning">
                            <i class="icon-base ri ri-calendar-2-line icon-24px"></i>
                        </span>
                    </div>
                    <div>
                        <h4 class="mb-0">${{ number_format(($revenueData['this_year'] ?? 0) / 1000, 1) }}k</h4>
                        <small class="text-body-secondary">This Year</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row g-6">
    <!-- Monthly Revenue Chart -->
    <div class="col-lg-8">
        <div class="card h-100">
            <div class="card-header d-flex align-items-center justify-content-between">
                <div class="card-title mb-0">
                    <h5 class="m-0 me-2">Monthly Revenue Trend</h5>
                    <span class="card-subtitle">Revenue for {{ date('Y') }}</span>
                </div>
                <div class="dropdown">
                    <button class="btn p-0" type="button" id="monthlyRevenue" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="icon-base ri ri-more-2-line"></i>
                    </button>
                    <div class="dropdown-menu dropdown-menu-end" aria-labelledby="monthlyRevenue">
                        <a class="dropdown-item" href="javascript:void(0);">View Details</a>
                        <a class="dropdown-item" href="javascript:void(0);">Export Data</a>
                        <a class="dropdown-item" href="javascript:void(0);">Refresh</a>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div id="monthlyRevenueChart"></div>
            </div>
        </div>
    </div>
    
    <!-- Payment Methods -->
    <div class="col-lg-4">
        <div class="card h-100">
            <div class="card-header d-flex align-items-center justify-content-between">
                <div class="card-title mb-0">
                    <h5 class="m-0 me-2">Payment Methods</h5>
                    <span class="card-subtitle">Revenue by method</span>
                </div>
            </div>
            <div class="card-body">
                <div id="paymentMethodsChart"></div>
                <div class="mt-4">
                    <div class="table-responsive">
                        <table class="table table-sm table-borderless">
                            <tbody>
                                @forelse($paymentMethods as $method)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <span class="badge bg-label-primary me-2">{{ $method['count'] }}</span>
                                            <span class="text-body">{{ $method['method'] }}</span>
                                        </div>
                                    </td>
                                    <td class="text-end">
                                        <span class="fw-medium">${{ number_format($method['total'], 0) }}</span>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="2" class="text-center text-muted">No payment data available</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row g-6 mt-4">
    <!-- Top Tours by Revenue -->
    <div class="col-lg-6">
        <div class="card h-100">
            <div class="card-header d-flex align-items-center justify-content-between">
                <div class="card-title mb-0">
                    <h5 class="m-0 me-2">Top Tours by Revenue</h5>
                    <span class="card-subtitle">Highest revenue generating tours</span>
                </div>
                <div class="dropdown">
                    <button class="btn p-0" type="button" id="tourRevenue" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="icon-base ri ri-more-2-line"></i>
                    </button>
                    <div class="dropdown-menu dropdown-menu-end" aria-labelledby="tourRevenue">
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
                            @forelse($tourRevenue as $tour)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="avatar avatar-sm me-2">
                                            <span class="avatar-initial rounded bg-label-primary">
                                                <i class="ri-map-2-line"></i>
                                            </span>
                                        </div>
                                        <span class="fw-medium">{{ \Illuminate\Support\Str::limit($tour->name, 30) }}</span>
                                    </div>
                                </td>
                                <td class="text-end">
                                    <span class="badge bg-label-primary">{{ $tour->bookings_count ?? 0 }}</span>
                                </td>
                                <td class="text-end">
                                    <span class="fw-medium">${{ number_format($tour->bookings_sum_total_price ?? 0, 0) }}</span>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="3" class="text-center text-muted">No tour revenue data available</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Top Destinations by Revenue -->
    <div class="col-lg-6">
        <div class="card h-100">
            <div class="card-header d-flex align-items-center justify-content-between">
                <div class="card-title mb-0">
                    <h5 class="m-0 me-2">Top Destinations by Revenue</h5>
                    <span class="card-subtitle">Revenue by destination</span>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-borderless">
                        <thead>
                            <tr>
                                <th>Destination</th>
                                <th class="text-end">Revenue</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($destinationRevenue as $destination)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="avatar avatar-sm me-2">
                                            <span class="avatar-initial rounded bg-label-info">
                                                <i class="ri-map-pin-line"></i>
                                            </span>
                                        </div>
                                        <span class="fw-medium">{{ $destination['name'] }}</span>
                                    </div>
                                </td>
                                <td class="text-end">
                                    <span class="fw-medium">${{ number_format($destination['revenue'], 0) }}</span>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="2" class="text-center text-muted">No destination revenue data available</td>
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
    <!-- Yearly Comparison -->
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header d-flex align-items-center justify-content-between">
                <div class="card-title mb-0">
                    <h5 class="m-0 me-2">Yearly Revenue Comparison</h5>
                    <span class="card-subtitle">Current year vs previous year</span>
                </div>
            </div>
            <div class="card-body">
                <div class="row g-4">
                    <div class="col-md-6">
                        <div class="card card-border-shadow-primary">
                            <div class="card-body text-center">
                                <h6 class="text-body-secondary mb-2">Current Year ({{ date('Y') }})</h6>
                                <h3 class="mb-0">${{ number_format(($yearlyComparison['current_year'] ?? 0) / 1000, 1) }}k</h3>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card card-border-shadow-info">
                            <div class="card-body text-center">
                                <h6 class="text-body-secondary mb-2">Previous Year ({{ date('Y') - 1 }})</h6>
                                <h3 class="mb-0">${{ number_format(($yearlyComparison['last_year'] ?? 0) / 1000, 1) }}k</h3>
                            </div>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="text-center">
                            <h6 class="mb-2">Growth Rate</h6>
                            <h4 class="mb-0 {{ ($yearlyComparison['growth'] ?? 0) >= 0 ? 'text-success' : 'text-danger' }}">
                                {{ ($yearlyComparison['growth'] ?? 0) >= 0 ? '+' : '' }}{{ number_format($yearlyComparison['growth'] ?? 0, 1) }}%
                            </h4>
                        </div>
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
    // Monthly Revenue Chart
    const monthlyRevenueOptions = {
        series: [{
            name: 'Revenue',
            data: @json($monthlyRevenueAmounts)
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
            categories: @json($monthlyRevenueMonths)
        },
        tooltip: {
            y: {
                formatter: function(val) {
                    return '$' + val.toLocaleString()
                }
            }
        }
    };
    const monthlyRevenueChart = new ApexCharts(document.querySelector("#monthlyRevenueChart"), monthlyRevenueOptions);
    monthlyRevenueChart.render();
    
    // Payment Methods Chart
    const paymentMethodsData = @json($paymentMethods);
    const paymentMethodsOptions = {
        series: paymentMethodsData.map(item => item.total),
        chart: {
            type: 'donut',
            height: 300
        },
        labels: paymentMethodsData.map(item => item.method),
        colors: ['#3ea572', '#2d7a5f', '#6cbe8f', '#1a4d3a', '#e6f4ed'],
        legend: {
            position: 'bottom'
        },
        plotOptions: {
            pie: {
                donut: {
                    size: '70%'
                }
            }
        },
        tooltip: {
            y: {
                formatter: function(val) {
                    return '$' + val.toLocaleString()
                }
            }
        }
    };
    const paymentMethodsChart = new ApexCharts(document.querySelector("#paymentMethodsChart"), paymentMethodsOptions);
    paymentMethodsChart.render();
});
</script>
@endpush

