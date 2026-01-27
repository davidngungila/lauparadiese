@extends('admin.layouts.app')

@section('title', 'Invoice Details - ' . ($invoice->invoice_number ?? 'INV-001'))
@section('description', 'View invoice details')

@push('styles')
<style>
    .info-card {
        border: none;
        box-shadow: 0 2px 8px rgba(0,0,0,0.08);
        border-radius: 10px;
        margin-bottom: 1.5rem;
    }
    .info-card .card-header {
        background: linear-gradient(135deg, #3ea572 0%, #2d7a5f 100%);
        color: white;
        border-radius: 10px 10px 0 0;
        padding: 1rem 1.5rem;
        border: none;
    }
    .info-card .card-header h5 {
        margin: 0;
        font-weight: 600;
    }
    .price-highlight {
        font-size: 1.5rem;
        font-weight: 700;
        color: #3ea572;
    }
    .status-badge {
        font-size: 0.875rem;
        padding: 0.5rem 1rem;
        border-radius: 6px;
    }
    .action-buttons .btn {
        margin-bottom: 0.5rem;
    }
    .quick-stats {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1rem;
        margin-bottom: 1.5rem;
    }
    .stat-card {
        background: white;
        padding: 1.5rem;
        border-radius: 10px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.08);
        border-left: 4px solid #3ea572;
    }
    .stat-card .stat-label {
        font-size: 0.875rem;
        color: #6c757d;
        margin-bottom: 0.5rem;
    }
    .stat-card .stat-value {
        font-size: 1.5rem;
        font-weight: 700;
        color: #2d7a5f;
    }
    @media print {
        .no-print {
            display: none !important;
        }
    }
</style>
@endpush

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center flex-wrap">
                <div>
                    <h4 class="fw-bold mb-1">
                        <i class="ri-file-list-3-line me-2" style="color: #3ea572;"></i>Invoice Details
                    </h4>
                    <p class="text-muted mb-0">Invoice #{{ $invoice->invoice_number }}</p>
                </div>
                <div class="d-flex gap-2 flex-wrap">
                    <a href="{{ route('admin.finance.invoices') }}" class="btn btn-label-secondary">
                        <i class="ri-arrow-left-line me-1"></i>Back to Invoices
                    </a>
                    @if(request()->get('edit') == '1')
                    <a href="{{ route('admin.finance.invoices.show', $invoice->id) }}" class="btn btn-label-secondary">
                        <i class="ri-eye-line me-1"></i>View Mode
                    </a>
                    @else
                    <a href="{{ route('admin.finance.invoices.show', $invoice->id) }}?edit=1" class="btn btn-success">
                        <i class="ri-edit-line me-1"></i>Edit Invoice
                    </a>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Stats -->
    <div class="quick-stats no-print">
        <div class="stat-card">
            <div class="stat-label">Total Amount</div>
            <div class="stat-value">{{ $invoice->currency ?? 'USD' }} {{ number_format($invoice->total_amount ?? 0, 2) }}</div>
        </div>
        <div class="stat-card">
            <div class="stat-label">Amount Paid</div>
            <div class="stat-value text-success">{{ $invoice->currency ?? 'USD' }} {{ number_format($totalPaid ?? 0, 2) }}</div>
        </div>
        <div class="stat-card">
            <div class="stat-label">Balance Due</div>
            <div class="stat-value text-danger">{{ $invoice->currency ?? 'USD' }} {{ number_format($remainingBalance ?? $invoice->total_amount, 2) }}</div>
        </div>
        <div class="stat-card">
            <div class="stat-label">Status</div>
            <div class="stat-value">
                @if($invoice->status === 'paid')
                    <span class="badge bg-success status-badge">Paid</span>
                @elseif($invoice->status === 'partial')
                    <span class="badge bg-warning status-badge">Partial</span>
                @elseif($invoice->status === 'overdue')
                    <span class="badge bg-danger status-badge">Overdue</span>
                @else
                    <span class="badge bg-secondary status-badge">Unpaid</span>
                @endif
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Main Content -->
        <div class="col-lg-8">
            <!-- Invoice Information -->
            <div class="card info-card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="ri-file-list-3-line me-2"></i>Invoice Information
                    </h5>
                </div>
                <div class="card-body">
                    @if(request()->get('edit') == '1')
                    <form id="editInvoiceForm">
                        @csrf
                        @method('PUT')
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Invoice Number</label>
                                <input type="text" class="form-control" value="{{ $invoice->invoice_number }}" disabled>
                                <small class="text-muted">Invoice number cannot be changed</small>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Invoice Date <span class="text-danger">*</span></label>
                                <input type="date" name="invoice_date" class="form-control" value="{{ $invoice->invoice_date ? $invoice->invoice_date->format('Y-m-d') : '' }}" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Due Date</label>
                                <input type="date" name="due_date" class="form-control" value="{{ $invoice->due_date ? $invoice->due_date->format('Y-m-d') : '' }}">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Status <span class="text-danger">*</span></label>
                                <select name="status" class="form-select" required>
                                    <option value="unpaid" {{ $invoice->status === 'unpaid' ? 'selected' : '' }}>Unpaid</option>
                                    <option value="partial" {{ $invoice->status === 'partial' ? 'selected' : '' }}>Partial</option>
                                    <option value="paid" {{ $invoice->status === 'paid' ? 'selected' : '' }}>Paid</option>
                                    <option value="overdue" {{ $invoice->status === 'overdue' ? 'selected' : '' }}>Overdue</option>
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Currency <span class="text-danger">*</span></label>
                                <input type="text" name="currency" class="form-control" value="{{ $invoice->currency ?? 'USD' }}" maxlength="3" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Subtotal</label>
                                <input type="number" name="subtotal" class="form-control" step="0.01" value="{{ $invoice->subtotal ?? 0 }}" readonly>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Tax Amount</label>
                                <input type="number" name="tax_amount" class="form-control" step="0.01" value="{{ $invoice->tax_amount ?? 0 }}">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Discount Amount</label>
                                <input type="number" name="discount_amount" class="form-control" step="0.01" value="{{ $invoice->discount_amount ?? 0 }}">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Total Amount</label>
                                <input type="number" name="total_amount" class="form-control" step="0.01" value="{{ $invoice->total_amount ?? 0 }}" readonly>
                            </div>
                            <div class="col-md-12 mb-3">
                                <label class="form-label">Notes</label>
                                <textarea name="notes" class="form-control" rows="3">{{ $invoice->notes }}</textarea>
                            </div>
                            <div class="col-md-12 mb-3">
                                <label class="form-label">Terms & Conditions</label>
                                <textarea name="terms" class="form-control" rows="3">{{ $invoice->terms }}</textarea>
                            </div>
                            <div class="col-12">
                                <button type="submit" class="btn btn-success">
                                    <i class="ri-save-line me-1"></i>Save Changes
                                </button>
                                <a href="{{ route('admin.finance.invoices.show', $invoice->id) }}" class="btn btn-label-secondary">
                                    <i class="ri-close-line me-1"></i>Cancel
                                </a>
                            </div>
                        </div>
                    </form>
                    @else
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-muted small">Invoice Number</label>
                            <p class="mb-0 fw-semibold">{{ $invoice->invoice_number }}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-muted small">Invoice Date</label>
                            <p class="mb-0">
                                <i class="ri-calendar-line me-1"></i>
                                {{ $invoice->invoice_date ? $invoice->invoice_date->format('F d, Y') : 'N/A' }}
                            </p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-muted small">Due Date</label>
                            <p class="mb-0">
                                @if($invoice->due_date)
                                    <i class="ri-calendar-check-line me-1"></i>
                                    {{ $invoice->due_date->format('F d, Y') }}
                                    @if($invoice->due_date->isPast() && $invoice->status !== 'paid')
                                        <span class="badge bg-danger ms-2">Overdue</span>
                                    @endif
                                @else
                                    N/A
                                @endif
                            </p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-muted small">Status</label>
                            <p class="mb-0">
                                @if($invoice->status === 'paid')
                                    <span class="badge bg-success">Paid</span>
                                @elseif($invoice->status === 'partial')
                                    <span class="badge bg-warning">Partial</span>
                                @elseif($invoice->status === 'overdue')
                                    <span class="badge bg-danger">Overdue</span>
                                @else
                                    <span class="badge bg-secondary">Unpaid</span>
                                @endif
                            </p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-muted small">Currency</label>
                            <p class="mb-0">{{ $invoice->currency ?? 'USD' }}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-muted small">Total Amount</label>
                            <p class="mb-0 price-highlight">{{ $invoice->currency ?? 'USD' }} {{ number_format($invoice->total_amount ?? 0, 2) }}</p>
                        </div>
                        @if($invoice->notes)
                        <div class="col-md-12 mb-3">
                            <label class="form-label text-muted small">Notes</label>
                            <p class="mb-0">{{ $invoice->notes }}</p>
                        </div>
                        @endif
                        @if($invoice->terms)
                        <div class="col-md-12 mb-3">
                            <label class="form-label text-muted small">Terms & Conditions</label>
                            <p class="mb-0">{{ $invoice->terms }}</p>
                        </div>
                        @endif
                    </div>
                    @endif
                </div>
            </div>

            <!-- Customer Information -->
            <div class="card info-card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="ri-user-line me-2"></i>Customer Information
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-muted small">Customer Name</label>
                            <p class="mb-0 fw-semibold">{{ $invoice->customer_name }}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-muted small">Email Address</label>
                            <p class="mb-0">
                                <a href="mailto:{{ $invoice->customer_email }}">{{ $invoice->customer_email }}</a>
                            </p>
                        </div>
                        @if($invoice->customer_phone)
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-muted small">Phone Number</label>
                            <p class="mb-0">
                                <a href="tel:{{ $invoice->customer_phone }}">{{ $invoice->customer_phone }}</a>
                            </p>
                        </div>
                        @endif
                        @if($invoice->customer_address)
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-muted small">Address</label>
                            <p class="mb-0">{{ $invoice->customer_address }}</p>
                        </div>
                        @endif
                        @if($invoice->user)
                        <div class="col-md-12">
                            <label class="form-label text-muted small">Registered User</label>
                            <p class="mb-0">
                                <span class="badge bg-label-info">{{ $invoice->user->name }}</span>
                                <span class="text-muted small ms-2">({{ $invoice->user->email }})</span>
                            </p>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Invoice Items -->
            <div class="card info-card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="ri-shopping-cart-line me-2"></i>Invoice Items
                    </h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Item</th>
                                    <th>Description</th>
                                    <th class="text-end">Unit Price</th>
                                    <th class="text-center">Qty</th>
                                    <th class="text-end">Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if($invoice->booking && $invoice->booking->tour)
                                <tr>
                                    <td class="fw-semibold">{{ $invoice->booking->tour->name }}</td>
                                    <td>Tour Package</td>
                                    <td class="text-end">{{ $invoice->currency ?? 'USD' }} {{ number_format($invoice->subtotal ?? 0, 2) }}</td>
                                    <td class="text-center">1</td>
                                    <td class="text-end fw-semibold">{{ $invoice->currency ?? 'USD' }} {{ number_format($invoice->subtotal ?? 0, 2) }}</td>
                                </tr>
                                @else
                                <tr>
                                    <td class="fw-semibold">Service</td>
                                    <td>Tour & Travel Services</td>
                                    <td class="text-end">{{ $invoice->currency ?? 'USD' }} {{ number_format($invoice->subtotal ?? 0, 2) }}</td>
                                    <td class="text-center">1</td>
                                    <td class="text-end fw-semibold">{{ $invoice->currency ?? 'USD' }} {{ number_format($invoice->subtotal ?? 0, 2) }}</td>
                                </tr>
                                @endif
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="4" class="text-end fw-semibold">Subtotal:</td>
                                    <td class="text-end fw-semibold">{{ $invoice->currency ?? 'USD' }} {{ number_format($invoice->subtotal ?? 0, 2) }}</td>
                                </tr>
                                @if(($invoice->discount_amount ?? 0) > 0)
                                <tr>
                                    <td colspan="4" class="text-end">Discount:</td>
                                    <td class="text-end text-danger">-{{ $invoice->currency ?? 'USD' }} {{ number_format($invoice->discount_amount, 2) }}</td>
                                </tr>
                                @endif
                                @if(($invoice->tax_amount ?? 0) > 0)
                                <tr>
                                    <td colspan="4" class="text-end">Tax:</td>
                                    <td class="text-end">{{ $invoice->currency ?? 'USD' }} {{ number_format($invoice->tax_amount, 2) }}</td>
                                </tr>
                                @endif
                                <tr class="table-active">
                                    <td colspan="4" class="text-end fw-bold">Total:</td>
                                    <td class="text-end fw-bold price-highlight">{{ $invoice->currency ?? 'USD' }} {{ number_format($invoice->total_amount ?? 0, 2) }}</td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Payment History -->
            @if($invoice->payments && $invoice->payments->count() > 0)
            <div class="card info-card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="ri-money-dollar-circle-line me-2"></i>Payment History
                    </h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Reference</th>
                                    <th>Method</th>
                                    <th class="text-end">Amount</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($invoice->payments->where('status', 'completed') as $payment)
                                <tr>
                                    <td>{{ $payment->paid_at ? $payment->paid_at->format('M d, Y') : ($payment->created_at ? $payment->created_at->format('M d, Y') : 'N/A') }}</td>
                                    <td><code>{{ $payment->payment_reference ?? 'N/A' }}</code></td>
                                    <td>
                                        <span class="badge bg-label-primary">{{ ucfirst(str_replace('_', ' ', $payment->payment_method ?? 'N/A')) }}</span>
                                    </td>
                                    <td class="text-end fw-semibold">{{ $invoice->currency ?? 'USD' }} {{ number_format($payment->amount, 2) }}</td>
                                    <td>
                                        <span class="badge bg-success">Completed</span>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr class="table-active">
                                    <th colspan="3" class="text-end">Total Paid:</th>
                                    <th class="text-end">{{ $invoice->currency ?? 'USD' }} {{ number_format($totalPaid ?? 0, 2) }}</th>
                                    <th></th>
                                </tr>
                                <tr>
                                    <th colspan="3" class="text-end">Remaining Balance:</th>
                                    <th class="text-end text-danger">{{ $invoice->currency ?? 'USD' }} {{ number_format($remainingBalance ?? $invoice->total_amount, 2) }}</th>
                                    <th></th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
            @endif

            <!-- Related Booking -->
            @if($invoice->booking)
            <div class="card info-card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="ri-calendar-check-line me-2"></i>Related Booking
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-muted small">Booking Reference</label>
                            <p class="mb-0">
                                <a href="{{ route('admin.bookings.show', $invoice->booking->id) }}" class="fw-semibold">
                                    {{ $invoice->booking->booking_reference }}
                                </a>
                            </p>
                        </div>
                        @if($invoice->booking->tour)
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-muted small">Tour</label>
                            <p class="mb-0">{{ $invoice->booking->tour->name }}</p>
                        </div>
                        @endif
                        @if($invoice->booking->departure_date)
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-muted small">Departure Date</label>
                            <p class="mb-0">{{ $invoice->booking->departure_date->format('F d, Y') }}</p>
                        </div>
                        @endif
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-muted small">Booking Status</label>
                            <p class="mb-0">
                                <span class="badge bg-label-{{ $invoice->booking->status === 'confirmed' ? 'success' : ($invoice->booking->status === 'cancelled' ? 'danger' : 'warning') }}">
                                    {{ ucfirst(str_replace('_', ' ', $invoice->booking->status)) }}
                                </span>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            @endif
        </div>

        <!-- Sidebar Actions -->
        <div class="col-lg-4">
            <!-- Quick Actions -->
            <div class="card info-card no-print">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="ri-flashlight-line me-2"></i>Quick Actions
                    </h5>
                </div>
                <div class="card-body action-buttons">
                    <!-- Status Update -->
                    <div class="dropdown mb-3">
                        <button class="btn btn-outline-primary w-100 dropdown-toggle" type="button" data-bs-toggle="dropdown">
                            <i class="ri-arrow-up-down-line me-1"></i>Update Status
                        </button>
                        <ul class="dropdown-menu w-100">
                            <li>
                                <form action="{{ route('admin.finance.invoices.update', $invoice->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('PUT')
                                    <input type="hidden" name="status" value="unpaid">
                                    <button type="submit" class="dropdown-item">Mark as Unpaid</button>
                                </form>
                            </li>
                            <li>
                                <form action="{{ route('admin.finance.invoices.update', $invoice->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('PUT')
                                    <input type="hidden" name="status" value="partial">
                                    <button type="submit" class="dropdown-item">Mark as Partial</button>
                                </form>
                            </li>
                            <li>
                                <form action="{{ route('admin.finance.invoices.update', $invoice->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('PUT')
                                    <input type="hidden" name="status" value="paid">
                                    <button type="submit" class="dropdown-item">Mark as Paid</button>
                                </form>
                            </li>
                        </ul>
                    </div>

                    <!-- Send Email -->
                    <div class="dropdown mb-3">
                        <button class="btn btn-success w-100 dropdown-toggle" type="button" data-bs-toggle="dropdown">
                            <i class="ri-mail-send-line me-1"></i>Send Email
                        </button>
                        <ul class="dropdown-menu w-100">
                            <li>
                                <a href="#" class="dropdown-item" onclick="sendInvoiceEmail('{{ route('admin.documents.invoice.final.send', $invoice->id) }}'); return false;">
                                    <i class="ri-file-pdf-line me-2"></i>Send Final Invoice
                                </a>
                            </li>
                        </ul>
                    </div>

                    <!-- Generate Documents -->
                    <div class="dropdown mb-3">
                        <button class="btn btn-outline-success w-100 dropdown-toggle" type="button" data-bs-toggle="dropdown">
                            <i class="ri-file-download-line me-1"></i>Generate Documents
                        </button>
                        <ul class="dropdown-menu w-100">
                            <li>
                                <a href="{{ route('admin.documents.invoice.final', $invoice->id) }}" class="dropdown-item" target="_blank">
                                    <i class="ri-file-pdf-line me-2"></i>Final Invoice PDF
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('admin.finance.invoices.pdf', $invoice->id) }}" class="dropdown-item" target="_blank">
                                    <i class="ri-download-line me-2"></i>Download Invoice
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('admin.finance.invoices.print', $invoice->id) }}" class="dropdown-item" target="_blank">
                                    <i class="ri-printer-line me-2"></i>Print Invoice
                                </a>
                            </li>
                            @if($invoice->status !== 'paid')
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <a href="{{ route('admin.documents.invoice.credit-note', $invoice->id) }}" class="dropdown-item" target="_blank">
                                    <i class="ri-file-text-line me-2"></i>Credit Note
                                </a>
                            </li>
                            @endif
                        </ul>
                    </div>

                    <!-- Add Payment -->
                    @if($invoice->status !== 'paid' && $remainingBalance > 0)
                    <button class="btn btn-success w-100 mb-3" data-bs-toggle="modal" data-bs-target="#addPaymentModal">
                        <i class="ri-money-dollar-circle-line me-1"></i>Add Payment
                    </button>
                    @endif

                    <!-- Edit Invoice -->
                    <a href="{{ route('admin.finance.invoices.edit', $invoice->id) }}" class="btn btn-outline-primary w-100 mb-3">
                        <i class="ri-edit-line me-1"></i>Edit Invoice
                    </a>

                    <!-- Delete Invoice -->
                    <form action="{{ route('admin.finance.invoices.destroy', $invoice->id) }}" method="POST" class="d-inline w-100" onsubmit="return confirm('Are you sure you want to delete this invoice?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-outline-danger w-100">
                            <i class="ri-delete-bin-line me-1"></i>Delete Invoice
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add Payment Modal -->
<div class="modal fade" id="addPaymentModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header" style="background: linear-gradient(135deg, #3ea572 0%, #2d7a5f 100%); color: white;">
                <h5 class="modal-title">
                    <i class="ri-money-dollar-circle-line me-2"></i>Add Payment
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="addPaymentForm">
                @csrf
                <div class="modal-body">
                    <div class="alert alert-info">
                        <i class="ri-information-line me-2"></i>
                        <strong>Invoice Total:</strong> {{ $invoice->currency ?? 'USD' }} {{ number_format($invoice->total_amount ?? 0, 2) }}<br>
                        @if(isset($totalPaid) && $totalPaid > 0)
                        <strong>Total Paid:</strong> {{ $invoice->currency ?? 'USD' }} {{ number_format($totalPaid, 2) }}<br>
                        @endif
                        <strong>Balance Due:</strong> <span class="text-danger fw-bold">{{ $invoice->currency ?? 'USD' }} {{ number_format($remainingBalance ?? $invoice->total_amount, 2) }}</span>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Payment Amount <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text">{{ $invoice->currency ?? 'USD' }}</span>
                            <input type="number" step="0.01" min="0.01" max="{{ $remainingBalance ?? $invoice->total_amount }}" 
                                   class="form-control" id="paymentAmount" name="amount" 
                                   value="{{ $remainingBalance ?? $invoice->total_amount }}" required>
                        </div>
                        <small class="text-muted">Maximum: {{ $invoice->currency ?? 'USD' }} {{ number_format($remainingBalance ?? $invoice->total_amount, 2) }}</small>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Payment Date <span class="text-danger">*</span></label>
                        <input type="date" class="form-control" id="paymentDate" name="payment_date" value="{{ date('Y-m-d') }}" required>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Payment Method <span class="text-danger">*</span></label>
                        <select class="form-select" id="paymentMethod" name="payment_method" required>
                            <option value="">Select payment method</option>
                            <option value="cash">Cash</option>
                            <option value="bank_transfer">Bank Transfer</option>
                            <option value="credit_card">Credit Card</option>
                            <option value="mobile_money">Mobile Money</option>
                            <option value="paypal">PayPal</option>
                            <option value="manual" selected>Manual Entry</option>
                        </select>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Payment Notes</label>
                        <textarea class="form-control" id="paymentNote" name="note" rows="3" placeholder="Optional notes about this payment..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success">
                        <i class="ri-checkbox-circle-line me-1"></i>Add Payment
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
// Handle invoice edit form
document.getElementById('editInvoiceForm')?.addEventListener('submit', function(e) {
    e.preventDefault();
    
    const form = this;
    const formData = new FormData(form);
    const submitBtn = form.querySelector('button[type="submit"]');
    const originalText = submitBtn.innerHTML;
    
    submitBtn.disabled = true;
    submitBtn.innerHTML = '<i class="ri-loader-4-line ri-spin me-1"></i>Saving...';
    
    // Convert FormData to JSON
    const data = {};
    formData.forEach((value, key) => {
        data[key] = value;
    });
    
    const token = document.querySelector('meta[name="csrf-token"]').content;
    
    fetch(form.action || '{{ route("admin.finance.invoices.update", $invoice->id) }}', {
        method: 'PUT',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': token,
            'Accept': 'application/json'
        },
        body: JSON.stringify(data)
    })
    .then(response => response.json())
    .then(data => {
        if (data.success || !data.errors) {
            alert('Invoice updated successfully!');
            window.location.reload();
        } else {
            let errorMsg = 'Failed to update invoice';
            if (data.message) {
                errorMsg = data.message;
            } else if (data.errors) {
                errorMsg = Object.values(data.errors).flat().join('\n');
            }
            alert('Error: ' + errorMsg);
            submitBtn.disabled = false;
            submitBtn.innerHTML = originalText;
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred while updating invoice. Please try again.');
        submitBtn.disabled = false;
        submitBtn.innerHTML = originalText;
    });
});

// Handle payment form
document.getElementById('addPaymentForm')?.addEventListener('submit', function(e) {
    e.preventDefault();
    
    const form = this;
    const formData = new FormData(form);
    const submitBtn = form.querySelector('button[type="submit"]');
    const originalText = submitBtn.innerHTML;
    
    submitBtn.disabled = true;
    submitBtn.innerHTML = '<i class="ri-loader-4-line ri-spin me-1"></i>Processing...';
    
    fetch('{{ route("admin.finance.invoices.payments.store", $invoice->id) }}', {
        method: 'POST',
        body: formData,
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Payment added successfully!');
            const modal = bootstrap.Modal.getInstance(document.getElementById('addPaymentModal'));
            if (modal) modal.hide();
            setTimeout(() => window.location.reload(), 1000);
        } else {
            let errorMsg = 'Failed to add payment';
            if (data.message) errorMsg = data.message;
            else if (data.errors) errorMsg = Object.values(data.errors).flat().join('\n');
            alert('Error: ' + errorMsg);
            submitBtn.disabled = false;
            submitBtn.innerHTML = originalText;
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred while adding payment. Please try again.');
        submitBtn.disabled = false;
        submitBtn.innerHTML = originalText;
    });
});

// Send invoice email
function sendInvoiceEmail(url) {
    if (!confirm('Send invoice email to {{ $invoice->customer_email }}?')) {
        return;
    }
    
    const token = document.querySelector('meta[name="csrf-token"]').content;
    
    fetch(url, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': token,
            'Accept': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Invoice email sent successfully!');
        } else {
            alert('Error: ' + (data.message || 'Failed to send invoice email'));
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred while sending email.');
    });
}
</script>
@endpush
@endsection
