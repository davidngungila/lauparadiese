@extends('admin.layouts.app')

@section('title', 'Financial Management - Lau Paradise Adventures')
@section('description', 'Comprehensive financial management dashboard')

@push('styles')
<style>
    .finance-dashboard {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-radius: 15px;
        padding: 2rem;
        margin-bottom: 2rem;
        color: white;
    }
    
    .stat-card {
        background: white;
        border-radius: 12px;
        padding: 1.5rem;
        box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        transition: transform 0.3s, box-shadow 0.3s;
        height: 100%;
        border-left: 4px solid;
    }
    
    .stat-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 15px rgba(0,0,0,0.2);
    }
    
    .stat-card.primary { border-left-color: #667eea; }
    .stat-card.success { border-left-color: #10b981; }
    .stat-card.warning { border-left-color: #f59e0b; }
    .stat-card.danger { border-left-color: #ef4444; }
    .stat-card.info { border-left-color: #3b82f6; }
    
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
    
    .stat-card.primary .stat-icon { background: rgba(102, 126, 234, 0.1); color: #667eea; }
    .stat-card.success .stat-icon { background: rgba(16, 185, 129, 0.1); color: #10b981; }
    .stat-card.warning .stat-icon { background: rgba(245, 158, 11, 0.1); color: #f59e0b; }
    .stat-card.danger .stat-icon { background: rgba(239, 68, 68, 0.1); color: #ef4444; }
    .stat-card.info .stat-icon { background: rgba(59, 130, 246, 0.1); color: #3b82f6; }
    
    .quick-action-btn {
        border-radius: 10px;
        padding: 0.75rem 1.5rem;
        font-weight: 600;
        transition: all 0.3s;
        border: none;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
    }
    
    .quick-action-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    }
    
    .finance-table {
        background: white;
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    }
    
    .table-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 1.25rem 1.5rem;
        font-weight: 600;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    
    .badge-status {
        padding: 0.4rem 0.8rem;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 600;
        text-transform: uppercase;
    }
    
    .chart-container {
        background: white;
        border-radius: 12px;
        padding: 1.5rem;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        height: 100%;
    }
    
    .filter-tabs {
        display: flex;
        gap: 0.5rem;
        margin-bottom: 1.5rem;
        flex-wrap: wrap;
    }
    
    .filter-tab {
        padding: 0.5rem 1rem;
        border: 2px solid #e5e7eb;
        background: white;
        border-radius: 8px;
        cursor: pointer;
        transition: all 0.3s;
        font-weight: 500;
    }
    
    .filter-tab.active {
        background: #667eea;
        color: white;
        border-color: #667eea;
    }
    
    .filter-tab:hover {
        border-color: #667eea;
    }
    
    .action-dropdown {
        position: relative;
    }
    
    .dropdown-menu {
        border-radius: 8px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        border: none;
        padding: 0.5rem;
    }
    
    .dropdown-item {
        border-radius: 6px;
        padding: 0.5rem 1rem;
        transition: all 0.2s;
    }
    
    .dropdown-item:hover {
        background: #f3f4f6;
    }
    
    .search-box {
        position: relative;
    }
    
    .search-box input {
        border-radius: 10px;
        padding-left: 2.5rem;
        border: 2px solid #e5e7eb;
        transition: all 0.3s;
    }
    
    .search-box input:focus {
        border-color: #667eea;
        box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
    }
    
    .search-box i {
        position: absolute;
        left: 1rem;
        top: 50%;
        transform: translateY(-50%);
        color: #9ca3af;
    }
    
    .recent-activity {
        background: white;
        border-radius: 12px;
        padding: 1.5rem;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    }
    
    .activity-item {
        display: flex;
        align-items: center;
        padding: 1rem;
        border-bottom: 1px solid #f3f4f6;
        transition: background 0.2s;
    }
    
    .activity-item:last-child {
        border-bottom: none;
    }
    
    .activity-item:hover {
        background: #f9fafb;
    }
    
    .activity-icon {
        width: 40px;
        height: 40px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-right: 1rem;
        font-size: 18px;
    }
    
    @media (max-width: 768px) {
        .finance-dashboard {
            padding: 1rem;
        }
        
        .filter-tabs {
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
        }
    }
</style>
@endpush

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <!-- Dashboard Header -->
    <div class="finance-dashboard">
        <div class="row align-items-center">
            <div class="col-md-8">
                <h2 class="mb-2 text-white">
                    <i class="ri-wallet-3-line me-2"></i>Financial Management
                </h2>
                <p class="text-white-50 mb-0">Comprehensive financial overview and management</p>
            </div>
            <div class="col-md-4 text-end">
                <div class="d-flex gap-2 justify-content-end flex-wrap">
                    <button class="btn btn-light quick-action-btn" onclick="window.location.href='{{ route('admin.finance.invoices.create') }}'">
                        <i class="ri-file-add-line"></i> New Invoice
                    </button>
                    <button class="btn btn-light quick-action-btn" onclick="window.location.href='{{ route('admin.finance.expenses.create') }}'">
                        <i class="ri-add-circle-line"></i> Add Expense
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-lg-3 col-md-6 mb-4">
            <div class="stat-card primary">
                <div class="stat-icon">
                    <i class="ri-money-dollar-circle-line"></i>
                </div>
                <h3 class="mb-1">${{ number_format($stats['total_revenue'] ?? 0, 2) }}</h3>
                <p class="text-muted mb-0">Total Revenue</p>
                <small class="text-success">
                    <i class="ri-arrow-up-line"></i> 12.5% from last month
                </small>
            </div>
        </div>
        
        <div class="col-lg-3 col-md-6 mb-4">
            <div class="stat-card success">
                <div class="stat-icon">
                    <i class="ri-checkbox-circle-line"></i>
                </div>
                <h3 class="mb-1">${{ number_format($stats['paid_amount'] ?? 0, 2) }}</h3>
                <p class="text-muted mb-0">Paid Invoices</p>
                <small class="text-success">
                    <i class="ri-arrow-up-line"></i> 8.2% increase
                </small>
            </div>
        </div>
        
        <div class="col-lg-3 col-md-6 mb-4">
            <div class="stat-card warning">
                <div class="stat-icon">
                    <i class="ri-time-line"></i>
                </div>
                <h3 class="mb-1">${{ number_format($stats['pending_amount'] ?? 0, 2) }}</h3>
                <p class="text-muted mb-0">Pending Payments</p>
                <small class="text-warning">
                    <i class="ri-alert-line"></i> {{ $stats['pending_count'] ?? 0 }} pending
                </small>
            </div>
        </div>
        
        <div class="col-lg-3 col-md-6 mb-4">
            <div class="stat-card danger">
                <div class="stat-icon">
                    <i class="ri-arrow-down-circle-line"></i>
                </div>
                <h3 class="mb-1">${{ number_format($stats['total_expenses'] ?? 0, 2) }}</h3>
                <p class="text-muted mb-0">Total Expenses</p>
                <small class="text-danger">
                    <i class="ri-arrow-down-line"></i> 3.1% from last month
                </small>
            </div>
        </div>
    </div>

    <!-- Quick Actions & Filters -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                        <div class="filter-tabs">
                            <div class="filter-tab active" data-filter="all">All</div>
                            <div class="filter-tab" data-filter="payments">Payments</div>
                            <div class="filter-tab" data-filter="invoices">Invoices</div>
                            <div class="filter-tab" data-filter="expenses">Expenses</div>
                            <div class="filter-tab" data-filter="refunds">Refunds</div>
                        </div>
                        
                        <div class="d-flex gap-2 flex-wrap">
                            <div class="search-box">
                                <i class="ri-search-line"></i>
                                <input type="text" class="form-control" id="searchInput" placeholder="Search transactions..." style="width: 250px;">
                            </div>
                            <div class="dropdown">
                                <button class="btn btn-outline-primary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                    <i class="ri-download-2-line"></i> Export
                                </button>
                                <ul class="dropdown-menu">
                                    <li><a class="dropdown-item" href="#"><i class="ri-file-excel-line me-2"></i>Export to Excel</a></li>
                                    <li><a class="dropdown-item" href="#"><i class="ri-file-pdf-line me-2"></i>Export to PDF</a></li>
                                    <li><a class="dropdown-item" href="#"><i class="ri-file-line me-2"></i>Export to CSV</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content Tabs -->
    <div class="row">
        <!-- Payments Section -->
        <div class="col-lg-8 mb-4">
            <div class="finance-table">
                <div class="table-header">
                    <div>
                        <i class="ri-money-dollar-circle-line me-2"></i>
                        <span>Recent Payments</span>
                    </div>
                    <a href="{{ route('admin.finance.payments') }}" class="text-white text-decoration-none">
                        View All <i class="ri-arrow-right-line"></i>
                    </a>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Payment ID</th>
                                <th>Customer</th>
                                <th>Amount</th>
                                <th>Method</th>
                                <th>Status</th>
                                <th>Date</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentPayments ?? [] as $payment)
                            <tr>
                                <td><strong>#{{ $payment->id ?? 'PAY-' . str_pad($loop->index + 1, 6, '0', STR_PAD_LEFT) }}</strong></td>
                                <td>{{ $payment->customer_name ?? 'John Doe' }}</td>
                                <td><strong>${{ number_format($payment->amount ?? 1500, 2) }}</strong></td>
                                <td>
                                    <span class="badge bg-label-info">
                                        {{ $payment->method ?? 'Credit Card' }}
                                    </span>
                                </td>
                                <td>
                                    <span class="badge-status bg-success text-white">
                                        {{ $payment->status ?? 'Completed' }}
                                    </span>
                                </td>
                                <td>{{ $payment->created_at ?? now()->format('M d, Y') }}</td>
                                <td>
                                    <div class="dropdown action-dropdown">
                                        <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                            <i class="ri-more-2-line"></i>
                                        </button>
                                        <ul class="dropdown-menu">
                                            <li><a class="dropdown-item" href="#"><i class="ri-eye-line me-2"></i>View Details</a></li>
                                            <li><a class="dropdown-item" href="#"><i class="ri-file-download-line me-2"></i>Download Receipt</a></li>
                                            <li><a class="dropdown-item" href="#"><i class="ri-refund-line me-2"></i>Process Refund</a></li>
                                        </ul>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="text-center py-4 text-muted">
                                    <i class="ri-inbox-line" style="font-size: 3rem;"></i>
                                    <p class="mt-2">No payments found</p>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Invoices Section -->
        <div class="col-lg-4 mb-4">
            <div class="finance-table">
                <div class="table-header">
                    <div>
                        <i class="ri-file-text-line me-2"></i>
                        <span>Recent Invoices</span>
                    </div>
                    <a href="{{ route('admin.finance.invoices') }}" class="text-white text-decoration-none">
                        View All <i class="ri-arrow-right-line"></i>
                    </a>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Invoice #</th>
                                <th>Amount</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentInvoices ?? [] as $invoice)
                            <tr>
                                <td><strong>#{{ $invoice->invoice_number ?? 'INV-' . str_pad($loop->index + 1, 6, '0', STR_PAD_LEFT) }}</strong></td>
                                <td><strong>${{ number_format($invoice->total ?? 2500, 2) }}</strong></td>
                                <td>
                                    @php
                                        $status = $invoice->status ?? 'paid';
                                        $statusClass = $status === 'paid' ? 'success' : ($status === 'pending' ? 'warning' : 'danger');
                                    @endphp
                                    <span class="badge-status bg-{{ $statusClass }} text-white">
                                        {{ ucfirst($status) }}
                                    </span>
                                </td>
                                <td>
                                    <div class="dropdown action-dropdown">
                                        <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                            <i class="ri-more-2-line"></i>
                                        </button>
                                        <ul class="dropdown-menu">
                                            <li><a class="dropdown-item" href="#"><i class="ri-eye-line me-2"></i>View</a></li>
                                            <li><a class="dropdown-item" href="#"><i class="ri-file-download-line me-2"></i>Download</a></li>
                                            <li><a class="dropdown-item" href="#"><i class="ri-printer-line me-2"></i>Print</a></li>
                                            <li><a class="dropdown-item" href="#"><i class="ri-mail-send-line me-2"></i>Send</a></li>
                                        </ul>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="text-center py-4 text-muted">
                                    <i class="ri-file-text-line" style="font-size: 2rem;"></i>
                                    <p class="mt-2 mb-0">No invoices</p>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Expenses & Refunds Section -->
    <div class="row">
        <div class="col-lg-6 mb-4">
            <div class="finance-table">
                <div class="table-header">
                    <div>
                        <i class="ri-arrow-down-circle-line me-2"></i>
                        <span>Recent Expenses</span>
                    </div>
                    <a href="{{ route('admin.finance.expenses') }}" class="text-white text-decoration-none">
                        View All <i class="ri-arrow-right-line"></i>
                    </a>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Category</th>
                                <th>Description</th>
                                <th>Amount</th>
                                <th>Date</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentExpenses ?? [] as $expense)
                            <tr>
                                <td>
                                    <span class="badge bg-label-warning">
                                        {{ $expense->category ?? 'Travel' }}
                                    </span>
                                </td>
                                <td>{{ $expense->description ?? 'Flight tickets' }}</td>
                                <td><strong class="text-danger">-${{ number_format($expense->amount ?? 450, 2) }}</strong></td>
                                <td>{{ $expense->date ?? now()->format('M d, Y') }}</td>
                                <td>
                                    <div class="dropdown action-dropdown">
                                        <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                            <i class="ri-more-2-line"></i>
                                        </button>
                                        <ul class="dropdown-menu">
                                            <li><a class="dropdown-item" href="#"><i class="ri-eye-line me-2"></i>View</a></li>
                                            <li><a class="dropdown-item" href="#"><i class="ri-pencil-line me-2"></i>Edit</a></li>
                                            <li><a class="dropdown-item text-danger" href="#"><i class="ri-delete-bin-line me-2"></i>Delete</a></li>
                                        </ul>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="text-center py-4 text-muted">
                                    <i class="ri-arrow-down-circle-line" style="font-size: 2rem;"></i>
                                    <p class="mt-2 mb-0">No expenses recorded</p>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-lg-6 mb-4">
            <div class="finance-table">
                <div class="table-header">
                    <div>
                        <i class="ri-refund-line me-2"></i>
                        <span>Refund Requests</span>
                    </div>
                    <a href="{{ route('admin.finance.refunds') }}" class="text-white text-decoration-none">
                        View All <i class="ri-arrow-right-line"></i>
                    </a>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Request ID</th>
                                <th>Customer</th>
                                <th>Amount</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($refundRequests ?? [] as $refund)
                            <tr>
                                <td><strong>#{{ $refund->id ?? 'REF-' . str_pad($loop->index + 1, 6, '0', STR_PAD_LEFT) }}</strong></td>
                                <td>{{ $refund->customer_name ?? 'Jane Smith' }}</td>
                                <td><strong>${{ number_format($refund->amount ?? 800, 2) }}</strong></td>
                                <td>
                                    @php
                                        $refundStatus = $refund->status ?? 'pending';
                                        $refundClass = $refundStatus === 'approved' ? 'success' : ($refundStatus === 'pending' ? 'warning' : 'danger');
                                    @endphp
                                    <span class="badge-status bg-{{ $refundClass }} text-white">
                                        {{ ucfirst($refundStatus) }}
                                    </span>
                                </td>
                                <td>
                                    <div class="dropdown action-dropdown">
                                        <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                            <i class="ri-more-2-line"></i>
                                        </button>
                                        <ul class="dropdown-menu">
                                            <li><a class="dropdown-item" href="#"><i class="ri-eye-line me-2"></i>View Details</a></li>
                                            <li><a class="dropdown-item text-success" href="#"><i class="ri-check-line me-2"></i>Approve</a></li>
                                            <li><a class="dropdown-item text-danger" href="#"><i class="ri-close-line me-2"></i>Reject</a></li>
                                        </ul>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="text-center py-4 text-muted">
                                    <i class="ri-refund-line" style="font-size: 2rem;"></i>
                                    <p class="mt-2 mb-0">No refund requests</p>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Receipt Generation & Recent Activity -->
    <div class="row">
        <div class="col-lg-8 mb-4">
            <div class="chart-container">
                <h5 class="mb-3">
                    <i class="ri-bar-chart-line me-2"></i>Revenue Overview
                </h5>
                <canvas id="revenueChart" height="100"></canvas>
            </div>
        </div>

        <div class="col-lg-4 mb-4">
            <div class="recent-activity">
                <h5 class="mb-3">
                    <i class="ri-history-line me-2"></i>Recent Activity
                </h5>
                <div class="activity-list">
                    @for($i = 0; $i < 5; $i++)
                    <div class="activity-item">
                        <div class="activity-icon bg-label-primary">
                            <i class="ri-file-text-line"></i>
                        </div>
                        <div class="flex-grow-1">
                            <p class="mb-0 fw-semibold">Invoice #{{ str_pad($i + 1, 6, '0', STR_PAD_LEFT) }} created</p>
                            <small class="text-muted">{{ now()->subHours($i + 1)->diffForHumans() }}</small>
                        </div>
                    </div>
                    @endfor
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Filter Tabs
    const filterTabs = document.querySelectorAll('.filter-tab');
    filterTabs.forEach(tab => {
        tab.addEventListener('click', function() {
            filterTabs.forEach(t => t.classList.remove('active'));
            this.classList.add('active');
            const filter = this.dataset.filter;
            console.log('Filter:', filter);
            // Implement filtering logic here
        });
    });

    // Search Functionality
    const searchInput = document.getElementById('searchInput');
    if (searchInput) {
        searchInput.addEventListener('input', function(e) {
            const searchTerm = e.target.value.toLowerCase();
            console.log('Searching for:', searchTerm);
            // Implement search logic here
        });
    }

    // Revenue Chart
    const ctx = document.getElementById('revenueChart');
    if (ctx) {
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
                datasets: [{
                    label: 'Revenue',
                    data: [12000, 19000, 15000, 25000, 22000, 30000],
                    borderColor: '#667eea',
                    backgroundColor: 'rgba(102, 126, 234, 0.1)',
                    tension: 0.4,
                    fill: true
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return '$' + value.toLocaleString();
                            }
                        }
                    }
                }
            }
        });
    }
});
</script>
@endpush





