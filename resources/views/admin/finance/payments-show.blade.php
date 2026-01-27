@extends('admin.layouts.app')

@section('title', 'Payment Details - ' . ($payment->payment_reference ?? 'PAY-' . $payment->id))
@section('description', 'View payment details')

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
                        <i class="ri-money-dollar-circle-line me-2" style="color: #3ea572;"></i>Payment Details
                    </h4>
                    <p class="text-muted mb-0">Payment Reference: {{ $payment->payment_reference ?? 'PAY-' . str_pad($payment->id, 6, '0', STR_PAD_LEFT) }}</p>
                </div>
                <div class="d-flex gap-2 flex-wrap no-print">
                    <a href="{{ route('admin.finance.payments') }}" class="btn btn-label-secondary">
                        <i class="ri-arrow-left-line me-1"></i>Back to Payments
                    </a>
                    <a href="{{ route('admin.documents.payment.receipt', $payment->id) }}" class="btn btn-outline-primary" target="_blank">
                        <i class="ri-file-pdf-line me-1"></i>Download Receipt
                    </a>
                    <button type="button" class="btn btn-success" onclick="sendPaymentReceipt()">
                        <i class="ri-mail-send-line me-1"></i>Send Receipt
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Stats -->
    <div class="quick-stats no-print">
        <div class="stat-card">
            <div class="stat-label">Payment Amount</div>
            <div class="stat-value">{{ $payment->currency ?? 'USD' }} {{ number_format($payment->amount, 2) }}</div>
        </div>
        <div class="stat-card">
            <div class="stat-label">Payment Status</div>
            <div class="stat-value">
                <span class="badge bg-label-{{ $payment->status == 'completed' ? 'success' : ($payment->status == 'pending' ? 'warning' : 'danger') }}">
                    {{ ucfirst($payment->status ?? 'pending') }}
                </span>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-label">Payment Method</div>
            <div class="stat-value" style="font-size: 1.1rem;">
                {{ ucfirst(str_replace('_', ' ', $payment->payment_method ?? 'N/A')) }}
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-label">Payment Date</div>
            <div class="stat-value" style="font-size: 1.1rem;">
                {{ $payment->paid_at ? $payment->paid_at->format('M d, Y') : ($payment->created_at->format('M d, Y')) }}
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Main Information -->
        <div class="col-lg-8">
            <!-- Payment Information -->
            <div class="card info-card">
                <div class="card-header">
                    <h5><i class="ri-information-line me-2"></i>Payment Information</h5>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label text-muted">Payment Reference</label>
                            <div class="fw-bold">{{ $payment->payment_reference ?? 'PAY-' . str_pad($payment->id, 6, '0', STR_PAD_LEFT) }}</div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-muted">Payment ID</label>
                            <div class="fw-bold">#{{ $payment->id }}</div>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label text-muted">Amount</label>
                            <div class="price-highlight">{{ $payment->currency ?? 'USD' }} {{ number_format($payment->amount, 2) }}</div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-muted">Status</label>
                            <div>
                                <span class="badge bg-label-{{ $payment->status == 'completed' ? 'success' : ($payment->status == 'pending' ? 'warning' : 'danger') }} status-badge">
                                    {{ ucfirst($payment->status ?? 'pending') }}
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label text-muted">Payment Method</label>
                            <div class="fw-bold">{{ ucfirst(str_replace('_', ' ', $payment->payment_method ?? 'N/A')) }}</div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-muted">Payment Date</label>
                            <div class="fw-bold">
                                {{ $payment->paid_at ? $payment->paid_at->format('F d, Y H:i') : ($payment->created_at->format('F d, Y H:i')) }}
                            </div>
                        </div>
                    </div>
                    @if($payment->gateway_transaction_id)
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label text-muted">Gateway Transaction ID</label>
                            <div class="fw-bold">{{ $payment->gateway_transaction_id }}</div>
                        </div>
                    </div>
                    @endif
                    @if($payment->notes)
                    <div class="row">
                        <div class="col-12">
                            <label class="form-label text-muted">Notes</label>
                            <div class="p-3 bg-light rounded">{!! nl2br(e($payment->notes)) !!}</div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Related Booking -->
            @if($payment->booking)
            <div class="card info-card">
                <div class="card-header">
                    <h5><i class="ri-calendar-check-line me-2"></i>Related Booking</h5>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label text-muted">Booking Reference</label>
                            <div>
                                <a href="{{ route('admin.bookings.show', $payment->booking->id) }}" class="fw-bold text-decoration-none">
                                    {{ $payment->booking->booking_reference }}
                                    <i class="ri-external-link-line ms-1"></i>
                                </a>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-muted">Customer Name</label>
                            <div class="fw-bold">{{ $payment->booking->customer_name }}</div>
                        </div>
                    </div>
                    @if($payment->booking->tour)
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label text-muted">Tour Package</label>
                            <div class="fw-bold">{{ $payment->booking->tour->name }}</div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-muted">Departure Date</label>
                            <div class="fw-bold">
                                {{ $payment->booking->departure_date ? $payment->booking->departure_date->format('F d, Y') : 'N/A' }}
                            </div>
                        </div>
                    </div>
                    @endif
                    <div class="row">
                        <div class="col-12">
                            <a href="{{ route('admin.bookings.show', $payment->booking->id) }}" class="btn btn-outline-primary">
                                <i class="ri-eye-line me-1"></i>View Full Booking Details
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            @endif

            <!-- Related Invoice -->
            @if($payment->invoice)
            <div class="card info-card">
                <div class="card-header">
                    <h5><i class="ri-file-list-3-line me-2"></i>Related Invoice</h5>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label text-muted">Invoice Number</label>
                            <div>
                                <a href="{{ route('admin.finance.invoices.show', $payment->invoice->id) }}" class="fw-bold text-decoration-none">
                                    {{ $payment->invoice->invoice_number }}
                                    <i class="ri-external-link-line ms-1"></i>
                                </a>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-muted">Invoice Amount</label>
                            <div class="fw-bold">{{ $payment->invoice->currency ?? 'USD' }} {{ number_format($payment->invoice->total_amount, 2) }}</div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <a href="{{ route('admin.finance.invoices.show', $payment->invoice->id) }}" class="btn btn-outline-primary">
                                <i class="ri-eye-line me-1"></i>View Full Invoice Details
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            @endif
        </div>

        <!-- Sidebar -->
        <div class="col-lg-4">
            <!-- Quick Actions -->
            <div class="card info-card no-print">
                <div class="card-header">
                    <h5><i class="ri-flashlight-line me-2"></i>Quick Actions</h5>
                </div>
                <div class="card-body action-buttons">
                    <a href="{{ route('admin.documents.payment.receipt', $payment->id) }}" class="btn btn-outline-primary w-100" target="_blank">
                        <i class="ri-file-pdf-line me-1"></i>Download Receipt PDF
                    </a>
                    <button type="button" class="btn btn-success w-100" onclick="sendPaymentReceipt()">
                        <i class="ri-mail-send-line me-1"></i>Send Receipt via Email
                    </button>
                    @if($payment->booking)
                    <a href="{{ route('admin.bookings.show', $payment->booking->id) }}" class="btn btn-outline-info w-100">
                        <i class="ri-calendar-check-line me-1"></i>View Booking
                    </a>
                    @endif
                    @if($payment->invoice)
                    <a href="{{ route('admin.finance.invoices.show', $payment->invoice->id) }}" class="btn btn-outline-info w-100">
                        <i class="ri-file-list-3-line me-1"></i>View Invoice
                    </a>
                    @endif
                </div>
            </div>

            <!-- Payment Timeline -->
            <div class="card info-card">
                <div class="card-header">
                    <h5><i class="ri-time-line me-2"></i>Payment Timeline</h5>
                </div>
                <div class="card-body">
                    <div class="timeline">
                        <div class="timeline-item mb-3">
                            <div class="d-flex">
                                <div class="flex-shrink-0">
                                    <div class="avatar avatar-sm rounded-circle bg-label-primary">
                                        <i class="ri-add-circle-line"></i>
                                    </div>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <h6 class="mb-1">Payment Created</h6>
                                    <p class="text-muted mb-0 small">{{ $payment->created_at->format('F d, Y H:i') }}</p>
                                </div>
                            </div>
                        </div>
                        @if($payment->paid_at && $payment->paid_at != $payment->created_at)
                        <div class="timeline-item mb-3">
                            <div class="d-flex">
                                <div class="flex-shrink-0">
                                    <div class="avatar avatar-sm rounded-circle bg-label-success">
                                        <i class="ri-check-line"></i>
                                    </div>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <h6 class="mb-1">Payment Completed</h6>
                                    <p class="text-muted mb-0 small">{{ $payment->paid_at->format('F d, Y H:i') }}</p>
                                </div>
                            </div>
                        </div>
                        @endif
                        @if($payment->status == 'completed')
                        <div class="timeline-item">
                            <div class="d-flex">
                                <div class="flex-shrink-0">
                                    <div class="avatar avatar-sm rounded-circle bg-label-success">
                                        <i class="ri-check-double-line"></i>
                                    </div>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <h6 class="mb-1">Status: Completed</h6>
                                    <p class="text-muted mb-0 small">Payment successfully processed</p>
                                </div>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Customer Information -->
            @if($payment->booking && $payment->booking->customer_email)
            <div class="card info-card">
                <div class="card-header">
                    <h5><i class="ri-user-line me-2"></i>Customer Information</h5>
                </div>
                <div class="card-body">
                    <div class="mb-2">
                        <label class="form-label text-muted small">Name</label>
                        <div class="fw-bold">{{ $payment->booking->customer_name }}</div>
                    </div>
                    @if($payment->booking->customer_email)
                    <div class="mb-2">
                        <label class="form-label text-muted small">Email</label>
                        <div class="fw-bold">{{ $payment->booking->customer_email }}</div>
                    </div>
                    @endif
                    @if($payment->booking->customer_phone)
                    <div>
                        <label class="form-label text-muted small">Phone</label>
                        <div class="fw-bold">{{ $payment->booking->customer_phone }}</div>
                    </div>
                    @endif
                </div>
            </div>
            @endif
        </div>
    </div>
</div>

<!-- Send Receipt Modal -->
<div class="modal fade" id="sendReceiptModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Send Payment Receipt</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="sendReceiptForm">
                    <div class="mb-3">
                        <label class="form-label">Recipient Email</label>
                        <input type="email" class="form-control" id="recipientEmail" 
                               value="{{ $payment->booking->customer_email ?? $payment->user->email ?? '' }}" required>
                        <small class="text-muted">The payment receipt will be sent to this email address.</small>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-success" onclick="confirmSendReceipt()">
                    <i class="ri-mail-send-line me-1"></i>Send Receipt
                </button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
function sendPaymentReceipt() {
    const modal = new bootstrap.Modal(document.getElementById('sendReceiptModal'));
    modal.show();
}

function confirmSendReceipt() {
    const emailInput = document.getElementById('recipientEmail');
    const email = emailInput.value.trim();
    
    if (!email) {
        alert('Please enter a valid email address');
        emailInput.focus();
        return;
    }

    // Basic email validation
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!emailRegex.test(email)) {
        alert('Please enter a valid email address format');
        emailInput.focus();
        return;
    }

    // Show loading state
    const btn = event.target;
    const originalText = btn.innerHTML;
    btn.disabled = true;
    btn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span>Sending...';

    // Create form data
    const formData = new FormData();
    formData.append('email', email);
    formData.append('_token', '{{ csrf_token() }}');

    fetch('{{ route("admin.documents.payment.receipt.send", $payment->id) }}', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json'
        },
        body: formData
    })
    .then(response => {
        if (!response.ok) {
            return response.json().then(data => {
                throw new Error(data.message || 'Failed to send payment receipt');
            });
        }
        return response.json();
    })
    .then(data => {
        if (data.success) {
            alert('Payment receipt sent successfully!');
            bootstrap.Modal.getInstance(document.getElementById('sendReceiptModal')).hide();
            setTimeout(() => location.reload(), 500);
        } else {
            alert('Error: ' + (data.message || 'Failed to send payment receipt'));
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Error: ' + (error.message || 'An error occurred while sending the receipt'));
    })
    .finally(() => {
        btn.disabled = false;
        btn.innerHTML = originalText;
    });
}
</script>
@endpush
@endsection

