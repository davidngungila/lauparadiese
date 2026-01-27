@extends('admin.layouts.app')

@section('title', 'Booking Reports')
@section('description', 'Comprehensive Booking Analysis and Reports')

@section('content')

<!-- Page Header -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <h4 class="mb-1">Booking Reports</h4>
                        <p class="mb-0 text-body-secondary">Comprehensive booking analysis and statistics</p>
                    </div>
                    <div class="btn-group">
                        <button type="button" class="btn btn-outline-primary dropdown-toggle" data-bs-toggle="dropdown">
                            <i class="ri-download-2-line me-1"></i>Export Report
                        </button>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="javascript:void(0);" onclick="exportReport('pdf')">Export as PDF</a></li>
                            <li><a class="dropdown-item" href="javascript:void(0);" onclick="exportReport('excel')">Export as Excel</a></li>
                            <li><a class="dropdown-item" href="javascript:void(0);" onclick="exportReport('csv')">Export as CSV</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Filters -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <form method="GET" action="{{ route('admin.reports.bookings') }}" id="filterForm">
                    <div class="row g-3">
                        <div class="col-md-3">
                            <label class="form-label">Date From</label>
                            <input type="date" name="date_from" class="form-control" value="{{ request('date_from', \Carbon\Carbon::now()->startOfMonth()->toDateString()) }}">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Date To</label>
                            <input type="date" name="date_to" class="form-control" value="{{ request('date_to', \Carbon\Carbon::now()->endOfMonth()->toDateString()) }}">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Status</label>
                            <select name="status" class="form-select">
                                <option value="">All Statuses</option>
                                <option value="pending_payment" {{ request('status') == 'pending_payment' ? 'selected' : '' }}>Pending Payment</option>
                                <option value="confirmed" {{ request('status') == 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                                <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                                <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Tour</label>
                            <select name="tour_id" class="form-select">
                                <option value="">All Tours</option>
                                @foreach($tours ?? [] as $tour)
                                    <option value="{{ $tour->id }}" {{ request('tour_id') == $tour->id ? 'selected' : '' }}>{{ $tour->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-12">
                            <button type="submit" class="btn btn-primary">
                                <i class="ri-search-line me-1"></i>Filter
                            </button>
                            <a href="{{ route('admin.reports.bookings') }}" class="btn btn-outline-secondary">
                                <i class="ri-refresh-line me-1"></i>Reset
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Statistics Cards -->
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
                    <div>
                        <h4 class="mb-0">{{ number_format($stats['total'] ?? 0) }}</h4>
                        <small class="text-body-secondary">Total Bookings</small>
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
                            <i class="icon-base ri ri-checkbox-circle-line icon-24px"></i>
                        </span>
                    </div>
                    <div>
                        <h4 class="mb-0">{{ number_format($stats['confirmed'] ?? 0) }}</h4>
                        <small class="text-body-secondary">Confirmed</small>
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
                            <i class="icon-base ri ri-time-line icon-24px"></i>
                        </span>
                    </div>
                    <div>
                        <h4 class="mb-0">{{ number_format($stats['pending'] ?? 0) }}</h4>
                        <small class="text-body-secondary">Pending</small>
                    </div>
                </div>
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
                    <div>
                        <h4 class="mb-0">${{ number_format($stats['total_revenue'] ?? 0, 2) }}</h4>
                        <small class="text-body-secondary">Total Revenue</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Charts Row -->
<div class="row g-6 mb-4">
    <!-- Booking Trends Chart -->
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header d-flex align-items-center justify-content-between">
                <h5 class="card-title mb-0">Booking Trends</h5>
            </div>
            <div class="card-body">
                <div id="bookingTrendsChart" style="min-height: 350px;"></div>
            </div>
        </div>
    </div>
    
    <!-- Status Breakdown Chart -->
    <div class="col-lg-4">
        <div class="card">
            <div class="card-header d-flex align-items-center justify-content-between">
                <h5 class="card-title mb-0">Status Breakdown</h5>
            </div>
            <div class="card-body">
                <div id="statusBreakdownChart" style="min-height: 350px;"></div>
            </div>
        </div>
    </div>
</div>

<!-- Top Tours -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Top Tours by Bookings</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Tour</th>
                                <th>Bookings</th>
                                <th>Revenue</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($topTours ?? [] as $item)
                                <tr>
                                    <td>{{ $item->tour->name ?? 'N/A' }}</td>
                                    <td>{{ $item->booking_count ?? 0 }}</td>
                                    <td>${{ number_format($item->tour->bookings()->where('status', 'confirmed')->sum('total_price') ?? 0, 2) }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="text-center">No data available</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Bookings Table -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex align-items-center justify-content-between">
                <h5 class="card-title mb-0">Bookings List</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover" id="bookingsTable">
                        <thead>
                            <tr>
                                <th>Booking ID</th>
                                <th>Customer</th>
                                <th>Tour</th>
                                <th>Date</th>
                                <th>Travelers</th>
                                <th>Amount</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($bookings ?? [] as $booking)
                                <tr>
                                    <td>#{{ $booking->id }}</td>
                                    <td>{{ $booking->user->name ?? 'N/A' }}</td>
                                    <td>{{ $booking->tour->name ?? 'N/A' }}</td>
                                    <td>{{ $booking->created_at->format('M d, Y') }}</td>
                                    <td>{{ $booking->travelers ?? 0 }}</td>
                                    <td>${{ number_format($booking->total_price ?? 0, 2) }}</td>
                                    <td>
                                        <span class="badge bg-label-{{ $booking->status == 'confirmed' ? 'success' : ($booking->status == 'pending_payment' ? 'warning' : ($booking->status == 'cancelled' ? 'danger' : 'secondary')) }}">
                                            {{ ucfirst(str_replace('_', ' ', $booking->status)) }}
                                        </span>
                                    </td>
                                    <td>
                                        <a href="{{ route('admin.bookings.show', $booking->id) }}" class="btn btn-sm btn-outline-primary">
                                            <i class="ri-eye-line"></i>
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center">No bookings found</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script src="{{ asset('assets/vendor/libs/apexcharts/apexcharts.js') }}"></script>
<script>
    // Booking Trends Chart
    @php
        $trendsDates = $bookingTrends->pluck('date')->toArray() ?? [];
        $trendsCounts = $bookingTrends->pluck('count')->toArray() ?? [];
        $trendsRevenue = $bookingTrends->pluck('revenue')->toArray() ?? [];
    @endphp
    
    var bookingTrendsOptions = {
        series: [{
            name: 'Bookings',
            type: 'column',
            data: @json($trendsCounts)
        }, {
            name: 'Revenue',
            type: 'line',
            data: @json($trendsRevenue)
        }],
        chart: {
            height: 350,
            type: 'line',
            toolbar: { show: false }
        },
        stroke: {
            width: [0, 4]
        },
        dataLabels: {
            enabled: true,
            enabledOnSeries: [1]
        },
        labels: @json($trendsDates),
        xaxis: {
            type: 'category'
        },
        yaxis: [{
            title: {
                text: 'Bookings'
            }
        }, {
            opposite: true,
            title: {
                text: 'Revenue'
            }
        }],
        colors: ['#696cff', '#71dd37']
    };
    
    var bookingTrendsChart = new ApexCharts(document.querySelector("#bookingTrendsChart"), bookingTrendsOptions);
    bookingTrendsChart.render();
    
    // Status Breakdown Chart
    @php
        $statusLabels = $statusBreakdown->pluck('status')->toArray() ?? [];
        $statusCounts = $statusBreakdown->pluck('count')->toArray() ?? [];
    @endphp
    
    var statusBreakdownOptions = {
        series: @json($statusCounts),
        chart: {
            type: 'donut',
            height: 350
        },
        labels: @json($statusLabels),
        colors: ['#696cff', '#71dd37', '#ffab00', '#ff3e1d', '#03c3ec'],
        legend: {
            position: 'bottom'
        },
        dataLabels: {
            enabled: true
        }
    };
    
    var statusBreakdownChart = new ApexCharts(document.querySelector("#statusBreakdownChart"), statusBreakdownOptions);
    statusBreakdownChart.render();
    
    // DataTable initialization
    $(document).ready(function() {
        $('#bookingsTable').DataTable({
            order: [[0, 'desc']],
            pageLength: 25
        });
    });
    
    function exportReport(format) {
        // Implement export functionality
        alert('Export functionality will be implemented');
    }
</script>
@endpush
