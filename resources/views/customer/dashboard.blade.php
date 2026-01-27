@extends('layouts.app')

@section('title', 'My Dashboard - Lau Paradise Adventures')

@push('styles')
<style>
    .customer-dashboard {
        background: linear-gradient(135deg, #f5f7fa 0%, #ffffff 100%);
        min-height: 100vh;
        padding: 2rem 0;
    }
    
    .dashboard-header {
        background: linear-gradient(135deg, #3ea572 0%, #2d8654 100%);
        border-radius: 1rem;
        padding: 2.5rem;
        color: white;
        margin-bottom: 2rem;
        box-shadow: 0 4px 20px rgba(62, 165, 114, 0.3);
    }
    
    .stat-card {
        background: white;
        border-radius: 1rem;
        padding: 1.5rem;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08);
        transition: all 0.3s ease;
        border-left: 4px solid;
        height: 100%;
    }
    
    .stat-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
    }
    
    .stat-card.primary { border-left-color: #3ea572; }
    .stat-card.success { border-left-color: #71dd37; }
    .stat-card.warning { border-left-color: #ffab00; }
    .stat-card.danger { border-left-color: #ff3e1d; }
    .stat-card.info { border-left-color: #03c3ec; }
    
    .stat-icon {
        width: 60px;
        height: 60px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 24px;
        margin-bottom: 1rem;
    }
    
    .stat-icon.primary { background: rgba(62, 165, 114, 0.1); color: #3ea572; }
    .stat-icon.success { background: rgba(113, 221, 55, 0.1); color: #71dd37; }
    .stat-icon.warning { background: rgba(255, 171, 0, 0.1); color: #ffab00; }
    .stat-icon.danger { background: rgba(255, 62, 29, 0.1); color: #ff3e1d; }
    .stat-icon.info { background: rgba(3, 195, 236, 0.1); color: #03c3ec; }
    
    .content-card {
        background: white;
        border-radius: 1rem;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08);
        overflow: hidden;
        margin-bottom: 2rem;
    }
    
    .content-card-header {
        background: #f8f9fa;
        padding: 1.25rem 1.5rem;
        border-bottom: 1px solid #e7e9ec;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }
    
    .content-card-body {
        padding: 1.5rem;
    }
    
    .activity-item {
        display: flex;
        align-items: start;
        padding: 1rem;
        border-bottom: 1px solid #f0f0f0;
        transition: background 0.2s;
    }
    
    .activity-item:last-child {
        border-bottom: none;
    }
    
    .activity-item:hover {
        background: #f8f9fa;
    }
    
    .activity-icon {
        width: 40px;
        height: 40px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-right: 1rem;
        flex-shrink: 0;
    }
    
    .quick-action-card {
        background: white;
        border-radius: 1rem;
        padding: 1.5rem;
        text-align: center;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08);
        transition: all 0.3s ease;
        border: 2px solid transparent;
        height: 100%;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
    }
    
    .quick-action-card:hover {
        transform: translateY(-5px);
        border-color: #3ea572;
        box-shadow: 0 8px 25px rgba(62, 165, 114, 0.2);
    }
    
    .quick-action-icon {
        width: 70px;
        height: 70px;
        border-radius: 16px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 32px;
        margin-bottom: 1rem;
        background: linear-gradient(135deg, #3ea572 0%, #2d8654 100%);
        color: white;
    }
    
    .booking-card {
        background: white;
        border-radius: 12px;
        padding: 1.5rem;
        margin-bottom: 1rem;
        border-left: 4px solid #3ea572;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
        transition: all 0.3s ease;
    }
    
    .booking-card:hover {
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.12);
        transform: translateX(5px);
    }
    
    .status-badge {
        padding: 0.375rem 0.75rem;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 600;
        text-transform: uppercase;
    }
    
    .empty-state {
        text-align: center;
        padding: 3rem 1rem;
        color: #6c757d;
    }
    
    .empty-state-icon {
        font-size: 64px;
        margin-bottom: 1rem;
        opacity: 0.3;
    }
    
    @media (max-width: 768px) {
        .dashboard-header {
            padding: 1.5rem;
        }
        
        .stat-card {
            margin-bottom: 1rem;
        }
    }
</style>
@endpush

@section('content')
<div class="customer-dashboard">
    <div class="container">
        <!-- Dashboard Header -->
        <div class="dashboard-header">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h1 class="mb-2 fw-bold">Welcome back, {{ $user->name }}! 👋</h1>
                    <p class="mb-0 opacity-90">Here's an overview of your bookings, quotations, and account activity</p>
                </div>
                <div class="col-md-4 text-md-end mt-3 mt-md-0">
                    <a href="{{ route('logout') }}" 
                       class="btn btn-light" 
                       onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        <i class="ri-logout-box-line me-1"></i>Logout
                    </a>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                        @csrf
                    </form>
                </div>
            </div>
        </div>

        <!-- Statistics Cards -->
        <div class="row g-4 mb-4">
            <div class="col-sm-6 col-lg-3">
                <div class="stat-card primary">
                    <div class="stat-icon primary">
                        <i class="ri-calendar-check-line"></i>
                    </div>
                    <h3 class="mb-1 fw-bold">{{ $stats['total_bookings'] ?? 0 }}</h3>
                    <p class="text-muted mb-0 small">Total Bookings</p>
                </div>
            </div>

            <div class="col-sm-6 col-lg-3">
                <div class="stat-card success">
                    <div class="stat-icon success">
                        <i class="ri-calendar-event-line"></i>
                    </div>
                    <h3 class="mb-1 fw-bold">{{ $stats['upcoming_bookings'] ?? 0 }}</h3>
                    <p class="text-muted mb-0 small">Upcoming Trips</p>
                </div>
            </div>

            <div class="col-sm-6 col-lg-3">
                <div class="stat-card warning">
                    <div class="stat-icon warning">
                        <i class="ri-file-list-3-line"></i>
                    </div>
                    <h3 class="mb-1 fw-bold">{{ $stats['pending_quotations'] ?? 0 }}</h3>
                    <p class="text-muted mb-0 small">Pending Quotations</p>
                </div>
            </div>

            <div class="col-sm-6 col-lg-3">
                <div class="stat-card danger">
                    <div class="stat-icon danger">
                        <i class="ri-money-dollar-circle-line"></i>
                    </div>
                    <h3 class="mb-1 fw-bold">{{ $stats['unpaid_invoices'] ?? 0 }}</h3>
                    <p class="text-muted mb-0 small">Unpaid Invoices</p>
                </div>
            </div>
        </div>

        <!-- Main Content Grid -->
        <div class="row g-4">
            <!-- Left Column -->
            <div class="col-lg-8">
                <!-- Upcoming Bookings -->
                <div class="content-card">
                    <div class="content-card-header">
                        <h5 class="mb-0 fw-bold">
                            <i class="ri-calendar-event-line me-2 text-success"></i>Upcoming Trips
                        </h5>
                        <a href="#" class="btn btn-sm btn-outline-primary">View All</a>
                    </div>
                    <div class="content-card-body">
                        @if($upcomingBookings && $upcomingBookings->count() > 0)
                            @foreach($upcomingBookings as $booking)
                                <div class="booking-card">
                                    <div class="row align-items-center">
                                        <div class="col-md-6">
                                            <h6 class="mb-1 fw-bold">{{ $booking->tour_name ?? 'Tour Package' }}</h6>
                                            <p class="text-muted mb-1 small">
                                                <i class="ri-calendar-line me-1"></i>
                                                {{ optional($booking->departure_date)->format('F d, Y') ?? 'Date TBD' }}
                                            </p>
                                            <p class="text-muted mb-0 small">
                                                <i class="ri-group-line me-1"></i>
                                                {{ $booking->travelers ?? $booking->number_of_adults ?? 1 }} Traveler(s)
                                            </p>
                                        </div>
                                        <div class="col-md-4 text-md-center">
                                            @php
                                                $status = strtolower($booking->status ?? 'pending');
                                                $badgeClass = match($status) {
                                                    'confirmed' => 'bg-success',
                                                    'pending' => 'bg-warning',
                                                    'cancelled' => 'bg-danger',
                                                    default => 'bg-secondary'
                                                };
                                            @endphp
                                            <span class="status-badge {{ $badgeClass }} text-white">{{ ucfirst($status) }}</span>
                                        </div>
                                        <div class="col-md-2 text-md-end mt-2 mt-md-0">
                                            <a href="#" class="btn btn-sm btn-outline-primary">
                                                <i class="ri-eye-line"></i>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <div class="empty-state">
                                <i class="ri-calendar-event-line empty-state-icon"></i>
                                <p class="mb-2">No upcoming trips scheduled</p>
                                <a href="{{ route('tours.index') ?? '/' }}" class="btn btn-primary">
                                    <i class="ri-search-line me-1"></i>Browse Tours
                                </a>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Recent Bookings -->
                <div class="content-card">
                    <div class="content-card-header">
                        <h5 class="mb-0 fw-bold">
                            <i class="ri-history-line me-2 text-primary"></i>Recent Bookings
                        </h5>
                        <a href="#" class="btn btn-sm btn-outline-primary">View All</a>
                    </div>
                    <div class="content-card-body">
                        @if($bookings && $bookings->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-hover align-middle">
                                    <thead>
                                        <tr>
                                            <th>Booking ID</th>
                                            <th>Tour/Service</th>
                                            <th>Date</th>
                                            <th>Status</th>
                                            <th>Amount</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($bookings as $booking)
                                            <tr>
                                                <td>
                                                    <strong>#{{ $booking->id ?? $booking->booking_reference ?? 'N/A' }}</strong>
                                                </td>
                                                <td>{{ $booking->tour_name ?? 'Tour Package' }}</td>
                                                <td>
                                                    <small>{{ optional($booking->departure_date ?? $booking->created_at)->format('M d, Y') }}</small>
                                                </td>
                                                <td>
                                                    @php
                                                        $status = strtolower($booking->status ?? 'pending');
                                                        $badgeClass = match($status) {
                                                            'confirmed' => 'bg-success',
                                                            'pending' => 'bg-warning',
                                                            'cancelled' => 'bg-danger',
                                                            default => 'bg-secondary'
                                                        };
                                                    @endphp
                                                    <span class="status-badge {{ $badgeClass }} text-white">{{ ucfirst($status) }}</span>
                                                </td>
                                                <td>
                                                    @if(isset($booking->total_price))
                                                        <strong>${{ number_format($booking->total_price, 2) }}</strong>
                                                    @else
                                                        <span class="text-muted">N/A</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <a href="#" class="btn btn-sm btn-outline-primary">
                                                        <i class="ri-eye-line"></i> View
                                                    </a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="empty-state">
                                <i class="ri-calendar-check-line empty-state-icon"></i>
                                <p class="mb-2">No bookings found</p>
                                <a href="{{ route('tours.index') ?? '/' }}" class="btn btn-primary">
                                    <i class="ri-search-line me-1"></i>Browse Tours
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Right Column -->
            <div class="col-lg-4">
                <!-- Quick Actions -->
                <div class="content-card">
                    <div class="content-card-header">
                        <h5 class="mb-0 fw-bold">
                            <i class="ri-menu-line me-2 text-info"></i>Quick Actions
                        </h5>
                    </div>
                    <div class="content-card-body">
                        <div class="row g-3">
                            <div class="col-6">
                                <a href="{{ route('tours.index') ?? '/' }}" class="quick-action-card text-decoration-none">
                                    <div class="quick-action-icon">
                                        <i class="ri-search-line"></i>
                                    </div>
                                    <h6 class="mb-0 fw-bold">Browse Tours</h6>
                                </a>
                            </div>
                            <div class="col-6">
                                <a href="#" class="quick-action-card text-decoration-none">
                                    <div class="quick-action-icon">
                                        <i class="ri-file-list-3-line"></i>
                                    </div>
                                    <h6 class="mb-0 fw-bold">Quotations</h6>
                                </a>
                            </div>
                            <div class="col-6">
                                <a href="#" class="quick-action-card text-decoration-none">
                                    <div class="quick-action-icon">
                                        <i class="ri-invoice-line"></i>
                                    </div>
                                    <h6 class="mb-0 fw-bold">Invoices</h6>
                                </a>
                            </div>
                            <div class="col-6">
                                <a href="#" class="quick-action-card text-decoration-none">
                                    <div class="quick-action-icon">
                                        <i class="ri-user-settings-line"></i>
                                    </div>
                                    <h6 class="mb-0 fw-bold">Profile</h6>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Recent Activity -->
                <div class="content-card">
                    <div class="content-card-header">
                        <h5 class="mb-0 fw-bold">
                            <i class="ri-time-line me-2 text-warning"></i>Recent Activity
                        </h5>
                    </div>
                    <div class="content-card-body p-0">
                        @if($recentActivity && $recentActivity->count() > 0)
                            @foreach($recentActivity as $activity)
                                <div class="activity-item">
                                    <div class="activity-icon bg-label-{{ $activity['color'] }}">
                                        <i class="{{ $activity['icon'] }}"></i>
                                    </div>
                                    <div class="flex-grow-1">
                                        <h6 class="mb-1 small fw-bold">{{ $activity['title'] }}</h6>
                                        <p class="mb-1 small text-muted">{{ $activity['description'] }}</p>
                                        <small class="text-muted">{{ $activity['date']->diffForHumans() }}</small>
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <div class="empty-state">
                                <i class="ri-time-line empty-state-icon"></i>
                                <p class="mb-0 small">No recent activity</p>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Account Summary -->
                <div class="content-card">
                    <div class="content-card-header">
                        <h5 class="mb-0 fw-bold">
                            <i class="ri-wallet-line me-2 text-success"></i>Account Summary
                        </h5>
                    </div>
                    <div class="content-card-body">
                        <div class="mb-3">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span class="text-muted small">Total Spent</span>
                                <strong class="text-success">${{ number_format($stats['total_spent'] ?? 0, 2) }}</strong>
                            </div>
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span class="text-muted small">Total Invoices</span>
                                <strong>{{ $stats['total_invoices'] ?? 0 }}</strong>
                            </div>
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span class="text-muted small">Quotations</span>
                                <strong>{{ $stats['total_quotations'] ?? 0 }}</strong>
                            </div>
                            <hr>
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="text-muted small">Unpaid Amount</span>
                                <strong class="text-danger">${{ number_format(($invoices->where('status', 'unpaid')->sum('total_amount') ?? 0), 2) }}</strong>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Additional Sections -->
        <div class="row g-4 mt-2">
            <!-- Recent Quotations -->
            <div class="col-lg-6">
                <div class="content-card">
                    <div class="content-card-header">
                        <h5 class="mb-0 fw-bold">
                            <i class="ri-file-list-3-line me-2 text-info"></i>Recent Quotations
                        </h5>
                        <a href="#" class="btn btn-sm btn-outline-primary">View All</a>
                    </div>
                    <div class="content-card-body">
                        @if($quotations && $quotations->count() > 0)
                            @foreach($quotations->take(3) as $quotation)
                                <div class="booking-card">
                                    <div class="d-flex justify-content-between align-items-start mb-2">
                                        <div>
                                            <h6 class="mb-1 fw-bold">{{ $quotation->quotation_number }}</h6>
                                            <p class="text-muted mb-1 small">{{ $quotation->tour_name ?? 'Tour Package' }}</p>
                                        </div>
                                        @php
                                            $status = strtolower($quotation->status ?? 'pending');
                                            $badgeClass = match($status) {
                                                'approved', 'accepted' => 'bg-success',
                                                'pending' => 'bg-warning',
                                                'rejected' => 'bg-danger',
                                                default => 'bg-secondary'
                                            };
                                        @endphp
                                        <span class="status-badge {{ $badgeClass }} text-white">{{ ucfirst($status) }}</span>
                                    </div>
                                    <div class="d-flex justify-content-between align-items-center">
                                        <small class="text-muted">
                                            <i class="ri-calendar-line me-1"></i>
                                            {{ optional($quotation->created_at)->format('M d, Y') }}
                                        </small>
                                        @if($quotation->total_price)
                                            <strong class="text-primary">${{ number_format($quotation->total_price, 2) }}</strong>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <div class="empty-state">
                                <i class="ri-file-list-3-line empty-state-icon"></i>
                                <p class="mb-0 small">No quotations found</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Recent Invoices -->
            <div class="col-lg-6">
                <div class="content-card">
                    <div class="content-card-header">
                        <h5 class="mb-0 fw-bold">
                            <i class="ri-invoice-line me-2 text-danger"></i>Recent Invoices
                        </h5>
                        <a href="#" class="btn btn-sm btn-outline-primary">View All</a>
                    </div>
                    <div class="content-card-body">
                        @if($invoices && $invoices->count() > 0)
                            @foreach($invoices->take(3) as $invoice)
                                <div class="booking-card">
                                    <div class="d-flex justify-content-between align-items-start mb-2">
                                        <div>
                                            <h6 class="mb-1 fw-bold">{{ $invoice->invoice_number ?? 'N/A' }}</h6>
                                            <p class="text-muted mb-1 small">
                                                <i class="ri-calendar-line me-1"></i>
                                                {{ optional($invoice->invoice_date ?? $invoice->created_at)->format('M d, Y') }}
                                            </p>
                                        </div>
                                        @php
                                            $status = strtolower($invoice->status ?? 'unpaid');
                                            $badgeClass = match($status) {
                                                'paid' => 'bg-success',
                                                'unpaid' => 'bg-danger',
                                                'partial' => 'bg-warning',
                                                default => 'bg-secondary'
                                            };
                                        @endphp
                                        <span class="status-badge {{ $badgeClass }} text-white">{{ ucfirst($status) }}</span>
                                    </div>
                                    <div class="d-flex justify-content-between align-items-center">
                                        <small class="text-muted">Due: {{ optional($invoice->due_date)->format('M d, Y') ?? 'N/A' }}</small>
                                        <strong class="text-primary">${{ number_format($invoice->total_amount ?? 0, 2) }}</strong>
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <div class="empty-state">
                                <i class="ri-invoice-line empty-state-icon"></i>
                                <p class="mb-0 small">No invoices found</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Add any interactive features here
    document.addEventListener('DOMContentLoaded', function() {
        // Smooth scroll animations
        const cards = document.querySelectorAll('.stat-card, .booking-card, .quick-action-card');
        cards.forEach((card, index) => {
            card.style.animationDelay = `${index * 0.1}s`;
        });
    });
</script>
@endpush
