@extends('admin.layouts.app')

@section('title', 'Dashboard')
@section('description', 'Comprehensive Dashboard Overview')

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
                        <h4 class="mb-1">Dashboard Overview</h4>
                        <p class="mb-0 text-body-secondary">Welcome back, {{ auth()->user()->name }}! Here's what's happening with your tour business today.</p>
                    </div>
                    <div class="btn-group">
                        <button type="button" class="btn btn-outline-primary dropdown-toggle" data-bs-toggle="dropdown">
                            <i class="ri-download-2-line me-1"></i>Export
                        </button>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="javascript:void(0);" onclick="exportDashboard('pdf')">Export as PDF</a></li>
                            <li><a class="dropdown-item" href="javascript:void(0);" onclick="exportDashboard('excel')">Export as Excel</a></li>
                            <li><a class="dropdown-item" href="javascript:void(0);" onclick="exportDashboard('csv')">Export as CSV</a></li>
                        </ul>
                        <button type="button" class="btn btn-primary" onclick="refreshDashboard()">
                            <i class="ri-refresh-line me-1"></i>Refresh
                        </button>
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
                        <a class="dropdown-item" href="{{ route('admin.statistics.analytics') }}">View Details</a>
                        <a class="dropdown-item" href="javascript:void(0);" onclick="exportChart('monthlyBookingsChart', 'Monthly Bookings')">Export Data</a>
                        <a class="dropdown-item" href="javascript:void(0);" onclick="refreshChart('monthlyBookingsChart')">Refresh</a>
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
                <div class="btn-group btn-group-sm">
                    <button type="button" class="btn btn-outline-primary active" onclick="changeTrendPeriod(30)">30 Days</button>
                    <button type="button" class="btn btn-outline-primary" onclick="changeTrendPeriod(7)">7 Days</button>
                </div>
            </div>
            <div class="card-body">
                <div id="bookingTrendsChart"></div>
            </div>
        </div>
    </div>
    
    <!-- Booking Status Breakdown -->
    <div class="col-lg-6">
        <div class="card h-100">
            <div class="card-header d-flex align-items-center justify-content-between">
                <div class="card-title mb-0">
                    <h5 class="m-0 me-2">Booking Status Breakdown</h5>
                    <span class="card-subtitle">Current status distribution</span>
                </div>
            </div>
            <div class="card-body">
                <div id="bookingStatusChart"></div>
            </div>
        </div>
    </div>
</div>

<div class="row g-6 mt-4">
    <!-- Top Performing Tours -->
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
                        <a class="dropdown-item" href="{{ route('admin.tours.index') }}">View All Tours</a>
                        <a class="dropdown-item" href="javascript:void(0);" onclick="exportTable('tourPerformanceTable')">Export</a>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-borderless" id="tourPerformanceTable">
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
                                        <span class="fw-medium">{{ \Illuminate\Support\Str::limit($tour->name ?? 'N/A', 30) }}</span>
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
                                <td colspan="3" class="text-center text-muted">No tour data available</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Recent Bookings -->
    <div class="col-lg-6">
        <div class="card h-100">
            <div class="card-header d-flex align-items-center justify-content-between">
                <div class="card-title mb-0">
                    <h5 class="m-0 me-2">Recent Bookings</h5>
                    <span class="card-subtitle">Latest booking activity</span>
                </div>
                <a href="{{ route('admin.bookings.index') }}" class="btn btn-sm btn-outline-primary">View All</a>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-borderless">
                        <thead>
                            <tr>
                                <th>Reference</th>
                                <th>Customer</th>
                                <th class="text-end">Amount</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentBookings as $booking)
                            <tr>
                                <td>
                                    <span class="fw-medium">{{ $booking->booking_reference }}</span>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="avatar avatar-sm me-2">
                                            <span class="avatar-initial rounded bg-label-info">
                                                {{ strtoupper(substr($booking->customer_name ?? 'N', 0, 1)) }}
                                            </span>
                                        </div>
                                        <span>{{ \Illuminate\Support\Str::limit($booking->customer_name ?? 'N/A', 20) }}</span>
                                    </div>
                                </td>
                                <td class="text-end">
                                    <span class="fw-medium">${{ number_format($booking->total_price ?? 0, 2) }}</span>
                                </td>
                                <td>
                                    @php
                                        $statusColors = [
                                            'confirmed' => 'success',
                                            'pending_payment' => 'warning',
                                            'cancelled' => 'danger',
                                            'completed' => 'info'
                                        ];
                                        $statusColor = $statusColors[$booking->status] ?? 'secondary';
                                    @endphp
                                    <span class="badge bg-label-{{ $statusColor }}">{{ ucfirst(str_replace('_', ' ', $booking->status)) }}</span>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="text-center text-muted">No recent bookings</td>
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
                                        <span class="fw-medium">{{ $destination->name ?? 'N/A' }}</span>
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

<!-- System Health & Quick Actions -->
<div class="row g-6 mt-4">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <h5 class="m-0">System Health & Performance</h5>
            </div>
            <div class="card-body">
                <div class="row g-4">
                    <div class="col-md-3">
                        <div class="text-center">
                            <h4 class="mb-1">{{ number_format($systemHealth['total_users'] ?? 0) }}</h4>
                            <small class="text-body-secondary">Total Users</small>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="text-center">
                            <h4 class="mb-1">{{ number_format($systemHealth['active_tours'] ?? 0) }}</h4>
                            <small class="text-body-secondary">Active Tours</small>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="text-center">
                            <h4 class="mb-1">{{ $systemHealth['system_uptime'] ?? 'N/A' }}</h4>
                            <small class="text-body-secondary">System Uptime</small>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="text-center">
                            <h4 class="mb-1">{{ $systemHealth['storage_used'] ?? 'N/A' }}</h4>
                            <small class="text-body-secondary">Storage Used</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="card">
            <div class="card-header">
                <h5 class="m-0">Quick Actions</h5>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <a href="{{ route('admin.bookings.create') }}" class="btn btn-primary">
                        <i class="ri-add-line me-1"></i>New Booking
                    </a>
                    <a href="{{ route('admin.tours.create') }}" class="btn btn-outline-primary">
                        <i class="ri-map-2-line me-1"></i>Create Tour
                    </a>
                    <a href="{{ route('admin.statistics.analytics') }}" class="btn btn-outline-info">
                        <i class="ri-bar-chart-line me-1"></i>View Analytics
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script src="{{ asset('assets/vendor/libs/apex-charts/apexcharts.js') }}" onerror="this.onerror=null;this.src='https://cdn.jsdelivr.net/npm/apexcharts'"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Wait for ApexCharts to be available
    function waitForApexCharts(callback, maxAttempts = 50) {
        if (typeof ApexCharts !== 'undefined') {
            callback();
        } else if (maxAttempts > 0) {
            setTimeout(function() {
                waitForApexCharts(callback, maxAttempts - 1);
            }, 100);
        } else {
            console.error('ApexCharts library failed to load');
        }
    }
    
    waitForApexCharts(function() {
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
        
        try {
            const monthlyBookingsChart = new ApexCharts(document.querySelector("#monthlyBookingsChart"), monthlyBookingsOptions);
            monthlyBookingsChart.render();
            window.monthlyBookingsChart = monthlyBookingsChart;
        } catch (error) {
            console.error('Error rendering monthly bookings chart:', error);
        }
        
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
        
        try {
            const revenueTrendsChart = new ApexCharts(document.querySelector("#revenueTrendsChart"), revenueTrendsOptions);
            revenueTrendsChart.render();
            window.revenueTrendsChart = revenueTrendsChart;
        } catch (error) {
            console.error('Error rendering revenue trends chart:', error);
        }
        
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
        
        try {
            const bookingTrendsChart = new ApexCharts(document.querySelector("#bookingTrendsChart"), bookingTrendsOptions);
            bookingTrendsChart.render();
            window.bookingTrendsChart = bookingTrendsChart;
        } catch (error) {
            console.error('Error rendering booking trends chart:', error);
        }
        
        // Booking Status Chart
        @php
            $statusData = $bookingStatusData ?? [];
            $statusLabels = ['Pending', 'Confirmed', 'Cancelled', 'Completed'];
            $statusValues = [
                $statusData['pending'] ?? 0,
                $statusData['confirmed'] ?? 0,
                $statusData['cancelled'] ?? 0,
                $statusData['completed'] ?? 0
            ];
        @endphp
        const bookingStatusOptions = {
            series: @json($statusValues),
            chart: {
                type: 'donut',
                height: 300
            },
            labels: @json($statusLabels),
            colors: ['#ffc107', '#3ea572', '#dc3545', '#17a2b8'],
            legend: {
                position: 'bottom'
            },
            tooltip: {
                y: {
                            formatter: function(val) {
                        return val + ' bookings'
                    }
                }
            }
        };
        
        try {
            const bookingStatusChart = new ApexCharts(document.querySelector("#bookingStatusChart"), bookingStatusOptions);
            bookingStatusChart.render();
            window.bookingStatusChart = bookingStatusChart;
        } catch (error) {
            console.error('Error rendering booking status chart:', error);
        }
    });
});

// Export functions
function exportDashboard(format) {
    alert('Export as ' + format.toUpperCase() + ' feature coming soon!');
}

function refreshDashboard() {
    window.location.reload();
}

function exportChart(chartId, chartName) {
    if (window[chartId.replace('Chart', '') + 'Chart']) {
        window[chartId.replace('Chart', '') + 'Chart'].dataURI().then((uri) => {
            const link = document.createElement('a');
            link.download = chartName + '.png';
            link.href = uri;
            link.click();
        });
    }
}

function refreshChart(chartId) {
    window.location.reload();
}

function changeTrendPeriod(days) {
    // This would typically make an AJAX call to update the chart
    alert('Changing period to ' + days + ' days - feature coming soon!');
}

function exportTable(tableId) {
    alert('Export table feature coming soon!');
}
</script>
@endpush
