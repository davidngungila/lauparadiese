@extends('admin.layouts.app')

@section('title', 'Expenses - Lau Paradise Adventures')
@section('description', 'Manage expenses')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">
                        <i class="ri-money-dollar-circle-line me-2"></i>Expenses Management
                    </h4>
                    <a href="{{ route('admin.finance.expenses.create') }}" class="btn btn-primary">
                        <i class="ri-add-line me-1"></i>Add Expense
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="row mb-4">
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
                            <h5 class="mb-0">{{ number_format($stats['total'] ?? 0) }}</h5>
                            <small class="text-muted">Total Expenses</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 mb-4">
            <div class="card card-border-shadow-danger">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="avatar me-3">
                            <span class="avatar-initial rounded bg-label-danger">
                                <i class="ri-money-dollar-circle-line"></i>
                            </span>
                        </div>
                        <div>
                            <h5 class="mb-0">${{ number_format($stats['total_amount'] ?? 0, 2) }}</h5>
                            <small class="text-muted">Total Amount</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 mb-4">
            <div class="card card-border-shadow-warning">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="avatar me-3">
                            <span class="avatar-initial rounded bg-label-warning">
                                <i class="ri-calendar-line"></i>
                            </span>
                        </div>
                        <div>
                            <h5 class="mb-0">${{ number_format($stats['this_month'] ?? 0, 2) }}</h5>
                            <small class="text-muted">This Month</small>
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
                                <i class="ri-bar-chart-line"></i>
                            </span>
                        </div>
                        <div>
                            <h5 class="mb-0">${{ number_format(($stats['total_amount'] ?? 0) / max($stats['total'] ?? 1, 1), 2) }}</h5>
                            <small class="text-muted">Avg. per Expense</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('admin.finance.expenses') }}">
                <div class="row g-3">
                    <div class="col-md-3">
                        <label class="form-label">Category</label>
                        <input type="text" name="category" class="form-control" placeholder="Category..." value="{{ request('category') }}">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Date From</label>
                        <input type="date" name="date_from" class="form-control" value="{{ request('date_from') }}">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Date To</label>
                        <input type="date" name="date_to" class="form-control" value="{{ request('date_to') }}">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Search</label>
                        <input type="text" name="search" class="form-control" placeholder="Search..." value="{{ request('search') }}">
                    </div>
                    <div class="col-md-1 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="ri-search-line"></i>
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Expenses Table -->
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Category</th>
                            <th>Description</th>
                            <th>Amount</th>
                            <th>Tour/Booking</th>
                            <th>Payment Method</th>
                            <th>Receipt #</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($expenses as $expense)
                        <tr>
                            <td>{{ $expense->expense_date ? $expense->expense_date->format('M d, Y') : 'N/A' }}</td>
                            <td><span class="badge bg-label-info">{{ $expense->expense_category }}</span></td>
                            <td>{{ Str::limit($expense->description, 50) }}</td>
                            <td><strong class="text-danger">${{ number_format($expense->amount ?? 0, 2) }}</strong></td>
                            <td>
                                @if($expense->tour)
                                    <span class="badge bg-label-primary">{{ $expense->tour->name }}</span>
                                @elseif($expense->booking)
                                    <span class="badge bg-label-success">Booking #{{ $expense->booking->id }}</span>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td>{{ $expense->payment_method ?? 'N/A' }}</td>
                            <td>{{ $expense->receipt_number ?? '-' }}</td>
                            <td>
                                <div class="d-flex gap-2">
                                    <a href="{{ route('admin.finance.expenses.edit', $expense->id) }}" class="btn btn-sm btn-icon btn-outline-info" data-bs-toggle="tooltip" title="Edit">
                                        <i class="ri-pencil-line"></i>
                                    </a>
                                    <button type="button" class="btn btn-sm btn-icon btn-outline-danger delete-expense" data-id="{{ $expense->id }}" data-name="{{ Str::limit($expense->description, 30) }}" data-bs-toggle="modal" data-bs-target="#deleteExpenseModal" data-bs-tooltip title="Delete">
                                        <i class="ri-delete-bin-line"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center py-4">
                                <p class="text-muted mb-0">No expenses found</p>
                                <a href="{{ route('admin.finance.expenses.create') }}" class="btn btn-primary btn-sm mt-2">
                                    <i class="ri-add-line me-1"></i>Add First Expense
                                </a>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-4">
                {{ $expenses->links() }}
            </div>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteExpenseModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Confirm Delete</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete expense <strong id="deleteExpenseName"></strong>?</p>
                <p class="text-danger mb-0"><small>This action cannot be undone.</small></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <form id="deleteExpenseForm" method="POST" style="display: inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Delete Expense</button>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
$(document).ready(function() {
    // Delete expense modal
    $('.delete-expense').on('click', function() {
        const expenseId = $(this).data('id');
        const expenseName = $(this).data('name');
        $('#deleteExpenseName').text(expenseName);
        $('#deleteExpenseForm').attr('action', '{{ route("admin.finance.expenses.destroy", ":id") }}'.replace(':id', expenseId));
    });
});
</script>
@endpush
@endsection
