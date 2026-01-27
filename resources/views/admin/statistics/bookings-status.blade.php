@extends('admin.layouts.app')

@section('title', 'Bookings Status - Statistics')
@section('description', 'Comprehensive Bookings Status Analysis')

@section('content')
@php
    $statusTrendsData = $statusTrends ?? [];
    $cancellationValues = $cancellationReasons['values'] ?? [];
    $cancellationLabels = $cancellationReasons['labels'] ?? [];
@endphp

<!-- Page Header -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <h4 class="mb-1">Bookings Status</h4>
                        <p class="mb-0 text-body-secondary">Comprehensive overview of booking statuses and trends</p>
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

<!-- Status Summary Cards -->
<div class="row g-6 mb-4">
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
                        <h4 class="mb-0">{{ number_format($statusData['pending'] ?? 0) }}</h4>
                        <small class="text-body-secondary">Pending Payment</small>
                    </div>
                </div>
                <p class="mb-0 mt-2">
                    <span class="fw-medium">{{ number_format($statusData['pending_percent'] ?? 0, 1) }}%</span>
                    <small class="text-body-secondary">of total</small>
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
                    <div>
                        <h4 class="mb-0">{{ number_format($statusData['confirmed'] ?? 0) }}</h4>
                        <small class="text-body-secondary">Confirmed</small>
                    </div>
                </div>
                <p class="mb-0 mt-2">
                    <span class="fw-medium">{{ number_format($statusData['confirmed_percent'] ?? 0, 1) }}%</span>
                    <small class="text-body-secondary">of total</small>
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
                            <i class="icon-base ri ri-flag-line icon-24px"></i>
                        </span>
                    </div>
                    <div>
                        <h4 class="mb-0">{{ number_format($statusData['completed'] ?? 0) }}</h4>
                        <small class="text-body-secondary">Completed</small>
                    </div>
                </div>
                <p class="mb-0 mt-2">
                    <span class="fw-medium">{{ number_format($statusData['completed_percent'] ?? 0, 1) }}%</span>
                    <small class="text-body-secondary">of total</small>
                </p>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-lg-3">
        <div class="card card-border-shadow-danger h-100">
            <div class="card-body">
                <div class="d-flex align-items-center mb-2">
                    <div class="avatar me-4">
                        <span class="avatar-initial rounded bg-label-danger">
                            <i class="icon-base ri ri-close-circle-line icon-24px"></i>
                        </span>
                    </div>
                    <div>
                        <h4 class="mb-0">{{ number_format($statusData['cancelled'] ?? 0) }}</h4>
                        <small class="text-body-secondary">Cancelled</small>
                    </div>
                </div>
                <p class="mb-0 mt-2">
                    <span class="fw-medium">{{ number_format($statusData['cancelled_percent'] ?? 0, 1) }}%</span>
                    <small class="text-body-secondary">of total</small>
                </p>
            </div>
        </div>
    </div>
</div>

<div class="row g-6">
    <!-- Status Overview Progress -->
    <div class="col-lg-6">
        <div class="card h-100">
            <div class="card-header d-flex align-items-center justify-content-between">
                <div class="card-title mb-0">
                    <h5 class="m-0 me-2">Status Overview</h5>
                    <span class="card-subtitle">Total: {{ number_format($statusData['total'] ?? 0) }} bookings</span>
                </div>
            </div>
            <div class="card-body">
                <div class="d-none d-lg-flex bookings-progress-labels mb-5">
                    <div class="bookings-progress-label confirmed-text" style="width: {{ $statusData['confirmed_percent'] ?? 0 }}%;">Confirmed</div>
                    <div class="bookings-progress-label pending-text" style="width: {{ $statusData['pending_percent'] ?? 0 }}%;">Pending</div>
                    <div class="bookings-progress-label completed-text" style="width: {{ $statusData['completed_percent'] ?? 0 }}%;">Completed</div>
                    <div class="bookings-progress-label cancelled-text" style="width: {{ $statusData['cancelled_percent'] ?? 0 }}%;">Cancelled</div>
                </div>
                <div class="bookings-overview-progress progress rounded bg-transparent mb-2" style="height: 46px;">
                    <div class="progress-bar small fw-medium text-start rounded-start bg-label-success text-heading px-1 px-lg-4" role="progressbar" style="width: {{ $statusData['confirmed_percent'] ?? 0 }}%" aria-valuenow="{{ $statusData['confirmed_percent'] ?? 0 }}" aria-valuemin="0" aria-valuemax="100">{{ $statusData['confirmed_percent'] ?? 0 }}%</div>
                    <div class="progress-bar small fw-medium text-start bg-warning px-1 px-lg-4" role="progressbar" style="width: {{ $statusData['pending_percent'] ?? 0 }}%" aria-valuenow="{{ $statusData['pending_percent'] ?? 0 }}" aria-valuemin="0" aria-valuemax="100">{{ $statusData['pending_percent'] ?? 0 }}%</div>
                    <div class="progress-bar small fw-medium text-start text-bg-info px-1 px-lg-4" role="progressbar" style="width: {{ $statusData['completed_percent'] ?? 0 }}%" aria-valuenow="{{ $statusData['completed_percent'] ?? 0 }}" aria-valuemin="0" aria-valuemax="100">{{ $statusData['completed_percent'] ?? 0 }}%</div>
                    <div class="progress-bar small fw-medium text-start bg-danger rounded-end px-1 px-lg-4" role="progressbar" style="width: {{ $statusData['cancelled_percent'] ?? 0 }}%" aria-valuenow="{{ $statusData['cancelled_percent'] ?? 0 }}" aria-valuemin="0" aria-valuemax="100">{{ $statusData['cancelled_percent'] ?? 0 }}%</div>
                </div>
                <div class="table-responsive">
                    <table class="table card-table">
                        <tbody class="table-border-bottom-0">
                            <tr>
                                <td class="w-75 ps-0">
                                    <div class="d-flex justify-content-start align-items-center">
                                        <div class="me-2">
                                            <i class="text-heading icon-base ri ri-checkbox-circle-line icon-24px text-success"></i>
                                        </div>
                                        <h6 class="mb-0 fw-normal">Confirmed</h6>
                                    </div>
                                </td>
                                <td class="text-end pe-0 text-nowrap">
                                    <h6 class="mb-0">{{ $statusData['confirmed'] ?? 0 }} bookings</h6>
                                </td>
                                <td class="text-end pe-0 ps-6">
                                    <span>{{ $statusData['confirmed_percent'] ?? 0 }}%</span>
                                </td>
                            </tr>
                            <tr>
                                <td class="w-75 ps-0">
                                    <div class="d-flex justify-content-start align-items-center">
                                        <div class="me-2">
                                            <i class="text-heading icon-base ri ri-time-line icon-24px text-warning"></i>
                                        </div>
                                        <h6 class="mb-0 fw-normal">Pending Payment</h6>
                                    </div>
                                </td>
                                <td class="text-end pe-0 text-nowrap">
                                    <h6 class="mb-0">{{ $statusData['pending'] ?? 0 }} bookings</h6>
                                </td>
                                <td class="text-end pe-0 ps-6">
                                    <span>{{ $statusData['pending_percent'] ?? 0 }}%</span>
                                </td>
                            </tr>
                            <tr>
                                <td class="w-75 ps-0">
                                    <div class="d-flex justify-content-start align-items-center">
                                        <div class="me-2">
                                            <i class="text-heading icon-base ri ri-flag-line icon-24px text-info"></i>
                                        </div>
                                        <h6 class="mb-0 fw-normal">Completed</h6>
                                    </div>
                                </td>
                                <td class="text-end pe-0 text-nowrap">
                                    <h6 class="mb-0">{{ $statusData['completed'] ?? 0 }} bookings</h6>
                                </td>
                                <td class="text-end pe-0 ps-6">
                                    <span>{{ $statusData['completed_percent'] ?? 0 }}%</span>
                                </td>
                            </tr>
                            <tr>
                                <td class="w-75 ps-0">
                                    <div class="d-flex justify-content-start align-items-center">
                                        <div class="me-2">
                                            <i class="text-heading icon-base ri ri-close-circle-line icon-24px text-danger"></i>
                                        </div>
                                        <h6 class="mb-0 fw-normal">Cancelled</h6>
                                    </div>
                                </td>
                                <td class="text-end pe-0 text-nowrap">
                                    <h6 class="mb-0">{{ $statusData['cancelled'] ?? 0 }} bookings</h6>
                                </td>
                                <td class="text-end pe-0 ps-6">
                                    <span>{{ $statusData['cancelled_percent'] ?? 0 }}%</span>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Status Trends Chart -->
    <div class="col-lg-6">
        <div class="card h-100">
            <div class="card-header d-flex align-items-center justify-content-between">
                <div class="card-title mb-0">
                    <h5 class="m-0 me-2">Status Trends</h5>
                    <span class="card-subtitle">Last 30 days</span>
                </div>
            </div>
            <div class="card-body">
                <div id="statusTrendsChart"></div>
            </div>
        </div>
    </div>
</div>

<div class="row g-6 mt-4">
    <!-- Cancellation Reasons -->
    <div class="col-lg-6">
        <div class="card h-100">
            <div class="card-header d-flex align-items-center justify-content-between">
                <div class="card-title mb-0">
                    <h5 class="m-0 me-2">Cancellation Reasons</h5>
                    <span class="card-subtitle">Top reasons for cancellations</span>
                </div>
            </div>
            <div class="card-body">
                <div id="cancellationReasonsChart"></div>
            </div>
        </div>
    </div>
    
    <!-- Booking Sources -->
    <div class="col-lg-6">
        <div class="card h-100">
            <div class="card-header d-flex align-items-center justify-content-between">
                <div class="card-title mb-0">
                    <h5 class="m-0 me-2">Booking Sources</h5>
                    <span class="card-subtitle">Where bookings come from</span>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-borderless">
                        <thead>
                            <tr>
                                <th>Source</th>
                                <th class="text-end">Count</th>
                                <th class="text-end">Percentage</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $totalSources = collect($bookingSources)->sum('count');
                            @endphp
                            @forelse($bookingSources as $source)
                            @php
                                $percentage = $totalSources > 0 ? ($source['count'] / $totalSources) * 100 : 0;
                            @endphp
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="avatar avatar-sm me-2">
                                            <span class="avatar-initial rounded bg-label-primary">
                                                <i class="ri-global-line"></i>
                                            </span>
                                        </div>
                                        <span class="fw-medium">{{ $source['source'] }}</span>
                                    </div>
                                </td>
                                <td class="text-end">
                                    <span class="badge bg-label-primary">{{ $source['count'] }}</span>
                                </td>
                                <td class="text-end">
                                    <span class="fw-medium">{{ number_format($percentage, 1) }}%</span>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="3" class="text-center text-muted">No booking source data available</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Recent Bookings Table -->
<div class="row g-6 mt-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex align-items-center justify-content-between">
                <div class="card-title mb-0">
                    <h5 class="m-0 me-2">Recent Bookings</h5>
                    <span class="card-subtitle">Latest booking activity</span>
                </div>
                <a href="{{ route('admin.bookings.index') }}" class="btn btn-sm btn-outline-primary">View All</a>
            </div>
            <div class="card-datatable table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Booking Reference</th>
                            <th>Customer</th>
                            <th>Tour</th>
                            <th>Departure Date</th>
                            <th>Travelers</th>
                            <th>Amount</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recentBookings as $booking)
                        @php
                            $statusClass = $booking->status === 'confirmed' ? 'success' : ($booking->status === 'pending_payment' ? 'warning' : ($booking->status === 'completed' ? 'info' : 'danger'));
                        @endphp
                        <tr>
                            <td>
                                <span class="fw-medium">{{ $booking->booking_reference ?? '#' . $booking->id }}</span>
                            </td>
                            <td>{{ $booking->customer_name ?? 'N/A' }}</td>
                            <td>{{ $booking->tour->name ?? 'N/A' }}</td>
                            <td>{{ $booking->departure_date ? \Carbon\Carbon::parse($booking->departure_date)->format('M d, Y') : 'N/A' }}</td>
                            <td>{{ $booking->travelers ?? 0 }}</td>
                            <td>${{ number_format($booking->total_price ?? 0, 2) }}</td>
                            <td>
                                <span class="badge bg-label-{{ $statusClass }} rounded-pill">
                                    {{ ucfirst(str_replace('_', ' ', $booking->status ?? 'pending')) }}
                                </span>
                            </td>
                            <td>
                                <a href="{{ route('admin.bookings.show', $booking->id) }}" class="btn btn-sm btn-icon">
                                    <i class="ri-eye-line"></i>
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center text-muted">No recent bookings found</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script src="{{ asset('assets/vendor/libs/apex-charts/apexcharts.js') }}"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Status Trends Chart
    const statusTrendsData = @json($statusTrendsData);
    const dates = statusTrendsData.map(item => item.date);
    
    const statusTrendsOptions = {
        series: [
            {
                name: 'Pending',
                data: statusTrendsData.map(item => item.pending_payment || 0)
            },
            {
                name: 'Confirmed',
                data: statusTrendsData.map(item => item.confirmed || 0)
            },
            {
                name: 'Completed',
                data: statusTrendsData.map(item => item.completed || 0)
            },
            {
                name: 'Cancelled',
                data: statusTrendsData.map(item => item.cancelled || 0)
            }
        ],
        chart: {
            type: 'line',
            height: 350,
            toolbar: { show: false },
            stacked: false
        },
        colors: ['#ffc107', '#3ea572', '#20c997', '#dc3545'],
        stroke: {
            curve: 'smooth',
            width: 3
        },
        dataLabels: {
            enabled: false
        },
        xaxis: {
            categories: dates
        },
        tooltip: {
            y: {
                formatter: function(val) {
                    return val + ' bookings'
                }
            }
        },
        legend: {
            position: 'top'
        }
    };
    const statusTrendsChart = new ApexCharts(document.querySelector("#statusTrendsChart"), statusTrendsOptions);
    statusTrendsChart.render();
    
    // Cancellation Reasons Chart
    const cancellationReasonsOptions = {
        series: @json($cancellationValues),
        chart: {
            type: 'donut',
            height: 300
        },
        labels: @json($cancellationLabels),
        colors: ['#dc3545', '#fd7e14', '#ffc107', '#20c997', '#6c757d'],
        legend: {
            position: 'bottom'
        },
        plotOptions: {
            pie: {
                donut: {
                    size: '75%'
                }
            }
        }
    };
    const cancellationReasonsChart = new ApexCharts(document.querySelector("#cancellationReasonsChart"), cancellationReasonsOptions);
    cancellationReasonsChart.render();
});
</script>
@endpush






