@extends('admin.layouts.app')

@section('title', 'Quotations Management')

@push('styles')
<style>
    .stats-card {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-radius: 0.75rem;
        padding: 1.5rem;
        color: white;
        transition: transform 0.2s;
    }
    .stats-card:hover {
        transform: translateY(-2px);
    }
    .stat-box {
        text-align: center;
    }
    .stat-number {
        font-size: 2.5rem;
        font-weight: 700;
        line-height: 1;
    }
    .stat-label {
        font-size: 0.875rem;
        opacity: 0.9;
        margin-top: 0.5rem;
    }
    .filter-toggle {
        cursor: pointer;
        user-select: none;
    }
    .filter-toggle:hover {
        color: #667eea;
    }
    .advanced-filters {
        display: none;
    }
    .advanced-filters.show {
        display: block;
    }
    .quick-actions {
        display: flex;
        gap: 0.5rem;
        flex-wrap: wrap;
    }
    .status-badge {
        font-size: 0.75rem;
        padding: 0.35rem 0.75rem;
        border-radius: 0.375rem;
        font-weight: 500;
    }
    .table-hover tbody tr:hover {
        background-color: #f8f9fa;
    }
    .action-btn {
        padding: 0.25rem 0.5rem;
        border-radius: 0.25rem;
        transition: all 0.2s;
    }
    .action-btn:hover {
        transform: scale(1.1);
    }
</style>
@endpush

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-bold mb-1">
                <i class="ri-file-text-line me-2"></i>Quotations Management
            </h4>
            <p class="text-muted mb-0">Manage customer quotations and pricing requests</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.quotations.export', request()->all()) }}" class="btn btn-outline-secondary">
                <i class="ri-download-line me-1"></i>Export
            </a>
            <a href="{{ route('admin.quotations.create') }}" class="btn btn-primary">
                <i class="ri-add-line me-1"></i>New Quotation
            </a>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row g-3 mb-4">
        <div class="col-6 col-md-2">
            <div class="stats-card">
                <div class="stat-box">
                    <div class="stat-number">{{ number_format($stats['total'] ?? 0) }}</div>
                    <div class="stat-label">Total</div>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-2">
            <div class="card border-warning">
                <div class="card-body text-center py-3">
                    <div class="stat-number text-warning">{{ number_format($stats['pending'] ?? 0) }}</div>
                    <div class="stat-label text-muted">Pending</div>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-2">
            <div class="card border-info">
                <div class="card-body text-center py-3">
                    <div class="stat-number text-info">{{ number_format($stats['under_review'] ?? 0) }}</div>
                    <div class="stat-label text-muted">Review</div>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-2">
            <div class="card border-primary">
                <div class="card-body text-center py-3">
                    <div class="stat-number text-primary">{{ number_format($stats['sent'] ?? 0) }}</div>
                    <div class="stat-label text-muted">Sent</div>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-2">
            <div class="card border-success">
                <div class="card-body text-center py-3">
                    <div class="stat-number text-success">{{ number_format($stats['approved'] ?? 0) }}</div>
                    <div class="stat-label text-muted">Approved</div>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-2">
            <div class="card border-danger">
                <div class="card-body text-center py-3">
                    <div class="stat-number text-danger">{{ number_format($stats['rejected'] ?? 0) }}</div>
                    <div class="stat-label text-muted">Rejected</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters Card -->
    <div class="card mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">
                <i class="ri-filter-line me-2"></i>Filters
            </h5>
            <span class="filter-toggle text-primary" onclick="toggleAdvancedFilters()">
                <i class="ri-arrow-down-s-line" id="filterIcon"></i> Advanced
            </span>
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('admin.quotations.index') }}" id="filterForm">
                <!-- Basic Filters -->
                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label">Search</label>
                        <input type="text" name="search" class="form-control" 
                               placeholder="Quotation #, Name, Email, Phone..." 
                               value="{{ request('search') }}">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Status</label>
                        <select name="status" class="form-select">
                            <option value="">All Status</option>
                            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="under_review" {{ request('status') == 'under_review' ? 'selected' : '' }}>Under Review</option>
                            <option value="sent" {{ request('status') == 'sent' ? 'selected' : '' }}>Sent</option>
                            <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approved</option>
                            <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
                            <option value="closed" {{ request('status') == 'closed' ? 'selected' : '' }}>Closed</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Tour/Package</label>
                        <select name="tour_id" class="form-select">
                            <option value="">All Tours</option>
                            @foreach($tours as $tour)
                                <option value="{{ $tour->id }}" {{ request('tour_id') == $tour->id ? 'selected' : '' }}>
                                    {{ Str::limit($tour->name, 40) }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Date Range</label>
                        <div class="input-group">
                            <input type="date" name="date_from" class="form-control" value="{{ request('date_from') }}" placeholder="From">
                            <input type="date" name="date_to" class="form-control" value="{{ request('date_to') }}" placeholder="To">
                        </div>
                    </div>
                </div>

                <!-- Advanced Filters (Collapsible) -->
                <div class="advanced-filters mt-3" id="advancedFilters">
                    <hr>
                    <div class="row g-3">
                        <div class="col-md-3">
                            <label class="form-label">Agent</label>
                            <select name="agent_id" class="form-select">
                                <option value="">All Agents</option>
                                @foreach($agents ?? [] as $agent)
                                    <option value="{{ $agent->id }}" {{ request('agent_id') == $agent->id ? 'selected' : '' }}>
                                        {{ $agent->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Price Range</label>
                            <div class="input-group">
                                <input type="number" name="price_min" class="form-control" placeholder="Min" 
                                       value="{{ request('price_min') }}" step="0.01">
                                <input type="number" name="price_max" class="form-control" placeholder="Max" 
                                       value="{{ request('price_max') }}" step="0.01">
                            </div>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Travelers</label>
                            <div class="input-group">
                                <input type="number" name="people_min" class="form-control" placeholder="Min" 
                                       value="{{ request('people_min') }}" min="1">
                                <input type="number" name="people_max" class="form-control" placeholder="Max" 
                                       value="{{ request('people_max') }}" min="1">
                            </div>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Travel Month</label>
                            <select name="travel_month" class="form-select">
                                <option value="">All Months</option>
                                @for($i = 1; $i <= 12; $i++)
                                    <option value="{{ $i }}" {{ request('travel_month') == $i ? 'selected' : '' }}>
                                        {{ date('F', mktime(0, 0, 0, $i, 1)) }}
                                    </option>
                                @endfor
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Travel Date Range</label>
                            <div class="input-group">
                                <input type="date" name="travel_date_from" class="form-control" 
                                       value="{{ request('travel_date_from') }}" placeholder="From">
                                <input type="date" name="travel_date_to" class="form-control" 
                                       value="{{ request('travel_date_to') }}" placeholder="To">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Filter Actions -->
                <div class="row mt-3">
                    <div class="col-12">
                        <button type="submit" class="btn btn-primary">
                            <i class="ri-search-line me-1"></i>Apply Filters
                        </button>
                        <a href="{{ route('admin.quotations.index') }}" class="btn btn-outline-secondary">
                            <i class="ri-refresh-line me-1"></i>Reset
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Quotations Table -->
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">Quotations List</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead>
                        <tr>
                            <th>Quotation #</th>
                            <th>Customer</th>
                            <th>Tour/Package</th>
                            <th>Travel Date</th>
                            <th>Travelers</th>
                            <th>Status</th>
                            <th>Amount</th>
                            <th>Created</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($quotations as $quotation)
                        <tr>
                            <td>
                                <strong class="text-primary">{{ $quotation->quotation_number }}</strong>
                            </td>
                            <td>
                                <div>
                                    <strong>{{ $quotation->customer_name }}</strong><br>
                                    <small class="text-muted">{{ $quotation->customer_email }}</small><br>
                                    <small class="text-muted">{{ $quotation->customer_phone ?? 'N/A' }}</small>
                                </div>
                            </td>
                            <td>
                                <span class="text-truncate d-inline-block" style="max-width: 200px;" title="{{ $quotation->tour ? $quotation->tour->name : $quotation->tour_name }}">
                                    {{ $quotation->tour ? $quotation->tour->name : $quotation->tour_name }}
                                </span>
                            </td>
                            <td>
                                @if($quotation->departure_date)
                                    <div>{{ $quotation->departure_date->format('M d, Y') }}</div>
                                    <small class="text-muted">{{ $quotation->departure_date->diffForHumans() }}</small>
                                @else
                                    <span class="text-muted">N/A</span>
                                @endif
                            </td>
                            <td>
                                <span class="badge bg-label-info">
                                    <i class="ri-group-line me-1"></i>{{ $quotation->travelers }} {{ $quotation->travelers == 1 ? 'Person' : 'People' }}
                                </span>
                            </td>
                            <td>
                                @php
                                    $statusConfig = [
                                        'pending' => ['class' => 'warning', 'icon' => 'ri-time-line', 'text' => 'Pending'],
                                        'under_review' => ['class' => 'info', 'icon' => 'ri-eye-line', 'text' => 'Review'],
                                        'sent' => ['class' => 'primary', 'icon' => 'ri-send-plane-line', 'text' => 'Sent'],
                                        'approved' => ['class' => 'success', 'icon' => 'ri-checkbox-circle-line', 'text' => 'Approved'],
                                        'rejected' => ['class' => 'danger', 'icon' => 'ri-close-circle-line', 'text' => 'Rejected'],
                                        'closed' => ['class' => 'secondary', 'icon' => 'ri-archive-line', 'text' => 'Closed'],
                                    ];
                                    $status = $statusConfig[$quotation->status] ?? ['class' => 'secondary', 'icon' => 'ri-file-line', 'text' => ucfirst($quotation->status)];
                                @endphp
                                <span class="badge bg-label-{{ $status['class'] }} status-badge">
                                    <i class="{{ $status['icon'] }} me-1"></i>{{ $status['text'] }}
                                </span>
                            </td>
                            <td>
                                <strong class="text-success">
                                    {{ ($quotation->currency ?? 'USD') }} {{ number_format($quotation->total_price, 2) }}
                                </strong>
                            </td>
                            <td>
                                <small>{{ $quotation->created_at->format('M d, Y') }}</small><br>
                                <small class="text-muted">{{ $quotation->created_at->format('h:i A') }}</small>
                            </td>
                            <td>
                                <div class="quick-actions">
                                    <a href="{{ route('admin.quotations.show', $quotation->id) }}" 
                                       class="btn btn-sm btn-outline-primary action-btn" title="View">
                                        <i class="ri-eye-line"></i>
                                    </a>
                                    <a href="{{ route('admin.quotations.pdf', $quotation->id) }}" 
                                       class="btn btn-sm btn-outline-success action-btn" title="PDF">
                                        <i class="ri-file-pdf-line"></i>
                                    </a>
                                    <a href="{{ route('admin.quotations.edit', $quotation->id) }}" 
                                       class="btn btn-sm btn-outline-warning action-btn" title="Edit">
                                        <i class="ri-pencil-line"></i>
                                    </a>
                                    @if($quotation->status == 'pending' || $quotation->status == 'under_review')
                                        <button class="btn btn-sm btn-outline-info action-btn send-quotation" 
                                                data-id="{{ $quotation->id }}" title="Send">
                                            <i class="ri-send-plane-line"></i>
                                        </button>
                                    @endif
                                    <button class="btn btn-sm btn-outline-danger action-btn delete-quotation" 
                                            data-id="{{ $quotation->id }}" 
                                            data-name="{{ $quotation->quotation_number }}" 
                                            title="Delete">
                                        <i class="ri-delete-bin-line"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="9" class="text-center py-5">
                                <div class="text-muted">
                                    <i class="ri-file-text-line" style="font-size: 3rem; opacity: 0.3;"></i>
                                    <p class="mt-3 mb-0">No quotations found</p>
                                    <a href="{{ route('admin.quotations.create') }}" class="btn btn-primary btn-sm mt-2">
                                        <i class="ri-add-line me-1"></i>Create First Quotation
                                    </a>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if($quotations->hasPages())
            <div class="mt-4">
                {{ $quotations->appends(request()->query())->links() }}
            </div>
            @endif
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteQuotationModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Delete Quotation</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete quotation <strong id="deleteQuotationName"></strong>?</p>
                <p class="text-danger mb-0"><small>This action cannot be undone.</small></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <form id="deleteQuotationForm" method="POST" action="" style="display: inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Delete</button>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
function toggleAdvancedFilters() {
    const filters = document.getElementById('advancedFilters');
    const icon = document.getElementById('filterIcon');
    filters.classList.toggle('show');
    icon.classList.toggle('ri-arrow-down-s-line');
    icon.classList.toggle('ri-arrow-up-s-line');
}

document.addEventListener('DOMContentLoaded', function() {
    // Send Quotation
    document.querySelectorAll('.send-quotation').forEach(btn => {
        btn.addEventListener('click', function() {
            const id = this.dataset.id;
            if (confirm('Send this quotation to the customer?')) {
                fetch(`/admin/quotations/${id}/send`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    }
                })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        alert('Quotation sent successfully!');
                        location.reload();
                    } else {
                        alert('Error: ' + (data.message || 'Failed to send quotation'));
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('An error occurred while sending the quotation');
                });
            }
        });
    });

    // Delete Quotation Modal
    const deleteModal = new bootstrap.Modal(document.getElementById('deleteQuotationModal'));
    document.querySelectorAll('.delete-quotation').forEach(btn => {
        btn.addEventListener('click', function() {
            const quotationId = this.dataset.id;
            const quotationName = this.dataset.name;
            
            document.getElementById('deleteQuotationName').textContent = quotationName;
            const form = document.getElementById('deleteQuotationForm');
            form.action = `/admin/quotations/${quotationId}`;
            
            deleteModal.show();
        });
    });
});
</script>
@endpush
@endsection
