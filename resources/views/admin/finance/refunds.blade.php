@extends('admin.layouts.app')

@section('title', 'Refund Requests - Lau Paradise Adventures')
@section('description', 'Manage refund requests')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">
                        <i class="ri-refund-line me-2"></i>Refund Requests
                    </h4>
                </div>
            </div>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="row mb-4">
        <div class="col-lg-3 col-md-6 mb-4">
            <div class="card card-border-shadow-warning">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="avatar me-3">
                            <span class="avatar-initial rounded bg-label-warning">
                                <i class="ri-time-line"></i>
                            </span>
                        </div>
                        <div>
                            <h5 class="mb-0">{{ $refunds->where('status', 'refund_requested')->count() }}</h5>
                            <small class="text-muted">Pending Requests</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 mb-4">
            <div class="card card-border-shadow-success">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="avatar me-3">
                            <span class="avatar-initial rounded bg-label-success">
                                <i class="ri-check-line"></i>
                            </span>
                        </div>
                        <div>
                            <h5 class="mb-0">{{ $refunds->where('status', 'refunded')->count() }}</h5>
                            <small class="text-muted">Refunded</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 mb-4">
            <div class="card card-border-shadow-info">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="avatar me-3">
                            <span class="avatar-initial rounded bg-label-info">
                                <i class="ri-money-dollar-circle-line"></i>
                            </span>
                        </div>
                        <div>
                            <h5 class="mb-0">${{ number_format($refunds->where('status', 'refunded')->sum('amount') ?? 0, 2) }}</h5>
                            <small class="text-muted">Total Refunded</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 mb-4">
            <div class="card card-border-shadow-primary">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="avatar me-3">
                            <span class="avatar-initial rounded bg-label-primary">
                                <i class="ri-file-list-line"></i>
                            </span>
                        </div>
                        <div>
                            <h5 class="mb-0">{{ $refunds->count() }}</h5>
                            <small class="text-muted">Total Requests</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('admin.finance.refunds') }}">
                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label">Status</label>
                        <select name="status" class="form-select">
                            <option value="">All Status</option>
                            <option value="refund_requested" {{ request('status') == 'refund_requested' ? 'selected' : '' }}>Pending</option>
                            <option value="refunded" {{ request('status') == 'refunded' ? 'selected' : '' }}>Refunded</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Search</label>
                        <input type="text" name="search" class="form-control" placeholder="Search by customer, booking..." value="{{ request('search') }}">
                    </div>
                    <div class="col-md-2 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="ri-search-line me-1"></i>Filter
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Refunds Table -->
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Payment ID</th>
                            <th>Customer</th>
                            <th>Booking</th>
                            <th>Amount</th>
                            <th>Reason</th>
                            <th>Requested Date</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($refunds as $refund)
                        <tr>
                            <td>#{{ $refund->id }}</td>
                            <td>
                                @if($refund->user)
                                    <div>
                                        <strong>{{ $refund->user->name }}</strong>
                                        <br><small class="text-muted">{{ $refund->user->email }}</small>
                                    </div>
                                @else
                                    <span class="text-muted">N/A</span>
                                @endif
                            </td>
                            <td>
                                @if($refund->booking)
                                    <span class="badge bg-label-info">Booking #{{ $refund->booking->id }}</span>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td><strong>${{ number_format($refund->amount ?? 0, 2) }}</strong></td>
                            <td>{{ Str::limit($refund->notes ?? 'No reason provided', 50) }}</td>
                            <td>{{ $refund->created_at ? $refund->created_at->format('M d, Y') : 'N/A' }}</td>
                            <td>
                                <span class="badge bg-label-{{ $refund->status == 'refunded' ? 'success' : 'warning' }}">
                                    {{ ucfirst(str_replace('_', ' ', $refund->status ?? 'N/A')) }}
                                </span>
                            </td>
                            <td>
                                @if($refund->status == 'refund_requested')
                                    <button class="btn btn-sm btn-success" onclick="processRefund({{ $refund->id }})">
                                        <i class="ri-check-line me-1"></i>Process
                                    </button>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center py-4">
                                <p class="text-muted mb-0">No refund requests found</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-4">
                {{ $refunds->links() }}
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
function processRefund(paymentId) {
    if (confirm('Are you sure you want to process this refund?')) {
        // TODO: Implement refund processing
        alert('Refund processing functionality will be implemented here');
    }
}
</script>
@endpush
@endsection
