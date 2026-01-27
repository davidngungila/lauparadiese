@extends('admin.layouts.app')

@section('title', 'Invoices - Lau Paradise Adventures')
@section('description', 'Manage all invoices')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="mb-0"><i class="ri-file-text-line me-2"></i>Invoices</h4>
                    <a href="{{ route('admin.finance.invoices.create') }}" class="btn btn-primary">
                        <i class="ri-add-line me-1"></i>Create Invoice
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
                            <span class="avatar-initial rounded bg-label-primary"><i class="ri-file-text-line"></i></span>
                        </div>
                        <div>
                            <h5 class="mb-0">{{ number_format(($stats ?? [])['total'] ?? 0) }}</h5>
                            <small class="text-muted">Total Invoices</small>
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
                            <span class="avatar-initial rounded bg-label-success"><i class="ri-check-line"></i></span>
                        </div>
                        <div>
                            <h5 class="mb-0">{{ number_format(($stats ?? [])['paid'] ?? 0) }}</h5>
                            <small class="text-muted">Paid</small>
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
                            <span class="avatar-initial rounded bg-label-warning"><i class="ri-time-line"></i></span>
                        </div>
                        <div>
                            <h5 class="mb-0">{{ number_format(($stats ?? [])['unpaid'] ?? 0) }}</h5>
                            <small class="text-muted">Unpaid</small>
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
                            <span class="avatar-initial rounded bg-label-info"><i class="ri-wallet-line"></i></span>
                        </div>
                        <div>
                            <h5 class="mb-0">${{ number_format(($stats ?? [])['total_amount'] ?? 0, 2) }}</h5>
                            <small class="text-muted">Total Amount</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters and Search -->
    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('admin.finance.invoices') }}" class="row g-3">
                <div class="col-md-3">
                    <label class="form-label">Search</label>
                    <input type="text" name="search" class="form-control" placeholder="Invoice #, Customer..." value="{{ request('search') }}">
                </div>
                <div class="col-md-2">
                    <label class="form-label">Status</label>
                    <select name="status" class="form-select">
                        <option value="">All Statuses</option>
                        <option value="unpaid" {{ request('status') == 'unpaid' ? 'selected' : '' }}>Unpaid</option>
                        <option value="paid" {{ request('status') == 'paid' ? 'selected' : '' }}>Paid</option>
                        <option value="overdue" {{ request('status') == 'overdue' ? 'selected' : '' }}>Overdue</option>
                        <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label">Date From</label>
                    <input type="date" name="date_from" class="form-control" value="{{ request('date_from') }}">
                </div>
                <div class="col-md-2">
                    <label class="form-label">Date To</label>
                    <input type="date" name="date_to" class="form-control" value="{{ request('date_to') }}">
                </div>
                <div class="col-md-3 d-flex align-items-end gap-2">
                    <button type="submit" class="btn btn-primary">
                        <i class="ri-search-line me-1"></i>Filter
                    </button>
                    <a href="{{ route('admin.finance.invoices') }}" class="btn btn-label-secondary">
                        <i class="ri-refresh-line me-1"></i>Reset
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Invoices Table -->
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Invoice #</th>
                            <th>Customer</th>
                            <th>Booking</th>
                            <th>Amount</th>
                            <th>Status</th>
                            <th>Invoice Date</th>
                            <th>Due Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse(($invoices ?? collect()) as $invoice)
                        <tr>
                            <td>
                                <strong>#{{ $invoice->invoice_number ?? $invoice->id }}</strong>
                            </td>
                            <td>
                                <div>
                                    <div class="fw-semibold">{{ $invoice->customer_name ?? ($invoice->user->name ?? 'N/A') }}</div>
                                    @if($invoice->customer_email)
                                    <small class="text-muted">{{ $invoice->customer_email }}</small>
                                    @endif
                                </div>
                            </td>
                            <td>
                                @if($invoice->booking)
                                    <a href="{{ route('admin.bookings.show', $invoice->booking->id) }}" class="text-primary">
                                        #{{ $invoice->booking->id }}
                                    </a>
                                @else
                                    <span class="text-muted">N/A</span>
                                @endif
                            </td>
                            <td>
                                <strong class="text-primary">{{ $invoice->currency ?? 'USD' }} {{ number_format($invoice->total_amount ?? 0, 2) }}</strong>
                            </td>
                            <td>
                                <span class="badge bg-label-{{ $invoice->status == 'paid' ? 'success' : ($invoice->status == 'overdue' ? 'danger' : ($invoice->status == 'cancelled' ? 'secondary' : 'warning')) }}">
                                    {{ ucfirst($invoice->status ?? 'N/A') }}
                                </span>
                            </td>
                            <td>{{ $invoice->invoice_date ? $invoice->invoice_date->format('M d, Y') : ($invoice->created_at ? $invoice->created_at->format('M d, Y') : 'N/A') }}</td>
                            <td>
                                @if($invoice->due_date)
                                    <span class="{{ $invoice->due_date->isPast() && $invoice->status != 'paid' ? 'text-danger' : '' }}">
                                        {{ $invoice->due_date->format('M d, Y') }}
                                    </span>
                                @else
                                    <span class="text-muted">N/A</span>
                                @endif
                            </td>
                            <td>
                                <div class="d-flex gap-1">
                                    <a href="{{ route('admin.finance.invoices.show', $invoice->id) }}" class="btn btn-sm btn-icon btn-outline-primary" data-bs-toggle="tooltip" title="View Details">
                                        <i class="ri-eye-line"></i>
                                    </a>
                                    <a href="{{ route('admin.finance.receipt', $invoice->id) }}" class="btn btn-sm btn-icon btn-outline-success" data-bs-toggle="tooltip" title="View Receipt" target="_blank">
                                        <i class="ri-receipt-line"></i>
                                    </a>
                                    <a href="{{ route('admin.finance.invoices.edit', $invoice->id) }}" class="btn btn-sm btn-icon btn-outline-info" data-bs-toggle="tooltip" title="Edit">
                                        <i class="ri-pencil-line"></i>
                                    </a>
                                    @if($invoice->payments()->count() == 0)
                                    <button type="button" class="btn btn-sm btn-icon btn-outline-danger delete-invoice" data-id="{{ $invoice->id }}" data-name="{{ $invoice->invoice_number }}" data-bs-toggle="modal" data-bs-target="#deleteInvoiceModal" data-bs-tooltip title="Delete">
                                        <i class="ri-delete-bin-line"></i>
                                    </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center py-4">
                                <p class="text-muted mb-0">No invoices found</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-4">
                {{ ($invoices ?? collect())->links() }}
            </div>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteInvoiceModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Confirm Delete</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete invoice <strong id="deleteInvoiceName"></strong>?</p>
                <p class="text-danger mb-0"><small>This action cannot be undone. Invoices with existing payments cannot be deleted.</small></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <form id="deleteInvoiceForm" method="POST" style="display: inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Delete Invoice</button>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
$(document).ready(function() {
    // Delete invoice modal
    $('.delete-invoice').on('click', function() {
        const invoiceId = $(this).data('id');
        const invoiceName = $(this).data('name');
        $('#deleteInvoiceName').text(invoiceName);
        $('#deleteInvoiceForm').attr('action', '{{ route("admin.finance.invoices.destroy", ":id") }}'.replace(':id', invoiceId));
    });
});
</script>
@endpush
@endsection

