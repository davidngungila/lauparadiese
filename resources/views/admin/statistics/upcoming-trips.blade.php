@extends('admin.layouts.app')

@section('title', 'Upcoming Trips - Statistics')
@section('description', 'Upcoming Trips Calendar and Details')

@section('content')
@php
    $monthlyTripsMonths = $monthlyTrips['months'] ?? [];
    $monthlyTripsCounts = $monthlyTrips['counts'] ?? [];
@endphp

<!-- Page Header -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <h4 class="mb-1">Upcoming Trips</h4>
                        <p class="mb-0 text-body-secondary">Comprehensive overview of upcoming trips and departures</p>
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

<!-- Trip Statistics Cards -->
<div class="row g-6 mb-4">
    <div class="col-sm-6 col-lg-3">
        <div class="card card-border-shadow-primary h-100">
            <div class="card-body">
                <div class="d-flex align-items-center mb-2">
                    <div class="avatar me-4">
                        <span class="avatar-initial rounded bg-label-primary">
                            <i class="icon-base ri ri-calendar-event-line icon-24px"></i>
                        </span>
                    </div>
                    <div>
                        <h4 class="mb-0">{{ number_format($tripStats['upcoming'] ?? 0) }}</h4>
                        <small class="text-body-secondary">Upcoming Trips</small>
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
                            <i class="icon-base ri ri-calendar-check-line icon-24px"></i>
                        </span>
                    </div>
                    <div>
                        <h4 class="mb-0">{{ number_format($tripStats['this_month'] ?? 0) }}</h4>
                        <small class="text-body-secondary">This Month</small>
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
                            <i class="icon-base ri ri-calendar-2-line icon-24px"></i>
                        </span>
                    </div>
                    <div>
                        <h4 class="mb-0">{{ number_format($tripStats['next_month'] ?? 0) }}</h4>
                        <small class="text-body-secondary">Next Month</small>
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
                            <i class="icon-base ri ri-group-line icon-24px"></i>
                        </span>
                    </div>
                    <div>
                        <h4 class="mb-0">{{ number_format($tripStats['total_travelers'] ?? 0) }}</h4>
                        <small class="text-body-secondary">Total Travelers</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row g-6">
    <!-- Monthly Trips Chart -->
    <div class="col-lg-8">
        <div class="card h-100">
            <div class="card-header d-flex align-items-center justify-content-between">
                <div class="card-title mb-0">
                    <h5 class="m-0 me-2">Upcoming Trips Schedule</h5>
                    <span class="card-subtitle">Next 6 months</span>
                </div>
                <div class="dropdown">
                    <button class="btn p-0" type="button" id="monthlyTrips" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="icon-base ri ri-more-2-line"></i>
                    </button>
                    <div class="dropdown-menu dropdown-menu-end" aria-labelledby="monthlyTrips">
                        <a class="dropdown-item" href="javascript:void(0);">View Details</a>
                        <a class="dropdown-item" href="javascript:void(0);">Export Data</a>
                        <a class="dropdown-item" href="javascript:void(0);">Refresh</a>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div id="monthlyTripsChart"></div>
            </div>
        </div>
    </div>
    
    <!-- Top Destinations -->
    <div class="col-lg-4">
        <div class="card h-100">
            <div class="card-header d-flex align-items-center justify-content-between">
                <div class="card-title mb-0">
                    <h5 class="m-0 me-2">Top Destinations</h5>
                    <span class="card-subtitle">Upcoming trips</span>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-borderless">
                        <thead>
                            <tr>
                                <th>Destination</th>
                                <th class="text-end">Trips</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($destinationTrips as $destination)
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
</div>

<!-- Upcoming Trips Table -->
<div class="row g-6 mt-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex align-items-center justify-content-between">
                <div class="card-title mb-0">
                    <h5 class="m-0 me-2">Upcoming Trips</h5>
                    <span class="card-subtitle">All confirmed upcoming departures</span>
                </div>
                <div class="btn-group">
                    <button type="button" class="btn btn-sm btn-outline-primary" data-bs-toggle="dropdown">
                        <i class="ri-filter-line me-1"></i>Filter
                    </button>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="javascript:void(0);">This Week</a></li>
                        <li><a class="dropdown-item" href="javascript:void(0);">This Month</a></li>
                        <li><a class="dropdown-item" href="javascript:void(0);">Next 3 Months</a></li>
                        <li><a class="dropdown-item" href="javascript:void(0);">All Upcoming</a></li>
                    </ul>
                </div>
            </div>
            <div class="card-datatable table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Booking Reference</th>
                            <th>Customer</th>
                            <th>Tour</th>
                            <th>Destination</th>
                            <th>Departure Date</th>
                            <th>Travelers</th>
                            <th>Amount</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($upcomingTrips as $trip)
                        @php
                            $daysUntil = \Carbon\Carbon::parse($trip->departure_date)->diffInDays(\Carbon\Carbon::now());
                            $isSoon = $daysUntil <= 7;
                        @endphp
                        <tr class="{{ $isSoon ? 'table-warning' : '' }}">
                            <td>
                                <span class="fw-medium">{{ $trip->booking_reference ?? '#' . $trip->id }}</span>
                            </td>
                            <td>{{ $trip->customer_name ?? 'N/A' }}</td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="avatar avatar-sm me-2">
                                        <span class="avatar-initial rounded bg-label-primary">
                                            <i class="ri-map-2-line"></i>
                                        </span>
                                    </div>
                                    <span>{{ \Illuminate\Support\Str::limit($trip->tour->name ?? 'N/A', 30) }}</span>
                                </div>
                            </td>
                            <td>
                                <span class="text-body">{{ $trip->tour->destination->name ?? 'N/A' }}</span>
                            </td>
                            <td>
                                <div>
                                    <span class="fw-medium">{{ \Carbon\Carbon::parse($trip->departure_date)->format('M d, Y') }}</span>
                                    @if($isSoon)
                                    <br><small class="text-warning"><i class="ri-time-line"></i> {{ $daysUntil }} days</small>
                                    @endif
                                </div>
                            </td>
                            <td>
                                <span class="badge bg-label-info">{{ $trip->travelers ?? 0 }}</span>
                            </td>
                            <td>
                                <span class="fw-medium">${{ number_format($trip->total_price ?? 0, 2) }}</span>
                            </td>
                            <td>
                                <span class="badge bg-label-success rounded-pill">Confirmed</span>
                            </td>
                            <td>
                                <div class="d-flex gap-2">
                                    <a href="{{ route('admin.bookings.show', $trip->id) }}" class="btn btn-sm btn-icon" title="View Details">
                                        <i class="ri-eye-line"></i>
                                    </a>
                                    <a href="javascript:void(0);" class="btn btn-sm btn-icon" title="Send Reminder">
                                        <i class="ri-mail-send-line"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="9" class="text-center text-muted py-5">
                                <div class="d-flex flex-column align-items-center">
                                    <i class="ri-calendar-line icon-48px text-body-secondary mb-2"></i>
                                    <p class="mb-0">No upcoming trips found</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Trip Calendar View -->
<div class="row g-6 mt-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex align-items-center justify-content-between">
                <div class="card-title mb-0">
                    <h5 class="m-0 me-2">Trip Calendar</h5>
                    <span class="card-subtitle">Upcoming trips by date</span>
                </div>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    @php
                        $calendarTrips = $tripCalendar->groupBy('date')->take(20);
                    @endphp
                    @forelse($calendarTrips as $date => $trips)
                    <div class="col-md-6 col-lg-4">
                        <div class="card card-border-shadow-primary">
                            <div class="card-body">
                                <div class="d-flex align-items-center justify-content-between">
                                    <div>
                                        <h6 class="mb-1">{{ \Carbon\Carbon::parse($date)->format('M d, Y') }}</h6>
                                        <small class="text-body-secondary">{{ \Carbon\Carbon::parse($date)->format('l') }}</small>
                                    </div>
                                    <div class="avatar">
                                        <span class="avatar-initial rounded bg-label-primary">
                                            {{ $trips->sum('count') }}
                                        </span>
                                    </div>
                                </div>
                                <p class="mb-0 mt-2">
                                    <span class="fw-medium">{{ $trips->sum('count') }}</span>
                                    <small class="text-body-secondary">trip(s) scheduled</small>
                                </p>
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="col-12">
                        <div class="text-center text-muted py-4">
                            <i class="ri-calendar-line icon-48px mb-2"></i>
                            <p class="mb-0">No trips scheduled in the next 3 months</p>
                        </div>
                    </div>
                    @endforelse
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
    // Monthly Trips Chart
    const monthlyTripsOptions = {
        series: [{
            name: 'Upcoming Trips',
            data: @json($monthlyTripsCounts)
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
            categories: @json($monthlyTripsMonths)
        },
        tooltip: {
            y: {
                formatter: function(val) {
                    return val + ' trips'
                }
            }
        }
    };
    const monthlyTripsChart = new ApexCharts(document.querySelector("#monthlyTripsChart"), monthlyTripsOptions);
    monthlyTripsChart.render();
});
</script>
@endpush

