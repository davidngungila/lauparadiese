@extends('admin.layouts.app')

@section('title', 'Payments - Lau Paradise Adventures')
@section('description', 'Manage all payments')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">
                        <i class="ri-money-dollar-circle-line me-2"></i>Payments
                    </h4>
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
                            <span class="avatar-initial rounded bg-label-primary"><i class="ri-money-dollar-circle-line"></i></span>
                        </div>
                        <div>
                            <h5 class="mb-0">{{ number_format(($stats ?? [])['total'] ?? 0) }}</h5>
                            <small class="text-muted">Total Payments</small>
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
                            <h5 class="mb-0">{{ number_format(($stats ?? [])['completed'] ?? 0) }}</h5>
                            <small class="text-muted">Completed</small>
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
                            <h5 class="mb-0">{{ number_format(($stats ?? [])['pending'] ?? 0) }}</h5>
                            <small class="text-muted">Pending</small>
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
                            <h5 class="mb-0">{{ $payments->first()->currency ?? 'USD' }}{{ number_format(($stats ?? [])['total_amount'] ?? 0, 2) }}</h5>
                            <small class="text-muted">Total Amount</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Payments Table -->
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Payment #</th>
                            <th>Customer</th>
                            <th>Amount</th>
                            <th>Method</th>
                            <th>Status</th>
                            <th>Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse(($payments ?? collect()) as $payment)
                        <tr>
                            <td>
                                <strong>{{ $payment->payment_reference ?? 'PAY-' . str_pad($payment->id, 6, '0', STR_PAD_LEFT) }}</strong>
                            </td>
                            <td>
                                <div>
                                    <strong>{{ $payment->booking->customer_name ?? $payment->user->name ?? 'N/A' }}</strong>
                                    @if($payment->booking && $payment->booking->customer_email)
                                    <br><small class="text-muted">{{ $payment->booking->customer_email }}</small>
                                    @endif
                                </div>
                            </td>
                            <td><strong>{{ $payment->currency ?? 'USD' }} {{ number_format($payment->amount ?? 0, 2) }}</strong></td>
                            <td>{{ ucfirst(str_replace('_', ' ', $payment->payment_method ?? 'N/A')) }}</td>
                            <td>
                                <span class="badge bg-label-{{ $payment->status == 'completed' ? 'success' : ($payment->status == 'pending' ? 'warning' : 'danger') }}">
                                    {{ ucfirst($payment->status ?? 'N/A') }}
                                </span>
                            </td>
                            <td>{{ $payment->paid_at ? $payment->paid_at->format('M d, Y') : ($payment->created_at->format('M d, Y') ?? 'N/A') }}</td>
                            <td>
                                <div class="d-flex gap-2">
                                    <a href="{{ route('admin.finance.payments.show', $payment->id) }}" class="btn btn-sm btn-icon btn-outline-primary view-payment" data-id="{{ $payment->id }}" title="View Details">
                                        <i class="ri-eye-line"></i>
                                    </a>
                                    <a href="{{ route('admin.documents.payment.receipt', $payment->id) }}" class="btn btn-sm btn-icon btn-outline-info" title="Download Receipt" target="_blank">
                                        <i class="ri-file-pdf-line"></i>
                                    </a>
                                    <button type="button" class="btn btn-sm btn-icon btn-outline-success send-payment" data-id="{{ $payment->id }}" title="Send Receipt">
                                        <i class="ri-mail-send-line"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center py-4">
                                <p class="text-muted mb-0">No payments found</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-4">
                {{ ($payments ?? collect())->links() }}
            </div>
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
                        <input type="email" class="form-control" id="recipientEmail" required>
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
let currentPaymentId = null;
let currentPaymentEmail = null;

document.addEventListener('DOMContentLoaded', function() {
    // Send Payment Receipt
    document.querySelectorAll('.send-payment').forEach(btn => {
        btn.addEventListener('click', function() {
            const id = this.dataset.id;
            const row = this.closest('tr');
            const email = row.querySelector('small.text-muted')?.textContent?.trim() || '';
            
            currentPaymentId = id;
            currentPaymentEmail = email;
            document.getElementById('recipientEmail').value = email;
            
            const modal = new bootstrap.Modal(document.getElementById('sendReceiptModal'));
            modal.show();
        });
    });
});

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

    if (!currentPaymentId) {
        alert('Payment ID is missing');
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

    fetch(`/admin/documents/payment/${currentPaymentId}/receipt/send`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json'
        },
        body: formData
    })
    .then(response => {
        return response.json().then(data => {
            if (!response.ok) {
                throw new Error(data.message || 'Failed to send payment receipt');
            }
            return data;
        });
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
