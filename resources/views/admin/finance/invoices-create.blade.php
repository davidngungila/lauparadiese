@extends('admin.layouts.app')

@section('title', 'Create Invoice - Lau Paradise Adventures')
@section('description', 'Create a new invoice')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">
                        <i class="ri-add-line me-2"></i>Create Invoice
                    </h4>
                    <a href="{{ route('admin.finance.invoices') }}" class="btn btn-label-secondary">
                        <i class="ri-arrow-left-line me-1"></i>Back to Invoices
                    </a>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.finance.invoices.store') }}" method="POST">
                        @csrf
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">Invoice Number</label>
                                <input type="text" name="invoice_number" class="form-control @error('invoice_number') is-invalid @enderror" value="{{ old('invoice_number') }}" placeholder="Leave empty for auto-generation">
                                <small class="text-muted">Auto-generated if left empty</small>
                                @error('invoice_number')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Status <span class="text-danger">*</span></label>
                                <select name="status" class="form-select @error('status') is-invalid @enderror" required>
                                    <option value="unpaid" {{ old('status') == 'unpaid' ? 'selected' : '' }}>Unpaid</option>
                                    <option value="paid" {{ old('status') == 'paid' ? 'selected' : '' }}>Paid</option>
                                    <option value="overdue" {{ old('status') == 'overdue' ? 'selected' : '' }}>Overdue</option>
                                    <option value="cancelled" {{ old('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                                </select>
                                @error('status')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Related Booking</label>
                                <select name="booking_id" class="form-select @error('booking_id') is-invalid @enderror" id="bookingSelect">
                                    <option value="">No Booking</option>
                                    @foreach($bookings ?? [] as $booking)
                                        <option value="{{ $booking->id }}" data-customer="{{ $booking->customer_name ?? '' }}" data-email="{{ $booking->customer_email ?? '' }}" data-phone="{{ $booking->customer_phone ?? '' }}" data-amount="{{ $booking->total_price ?? 0 }}" {{ old('booking_id') == $booking->id ? 'selected' : '' }}>
                                            Booking #{{ $booking->id }} - {{ $booking->customer_name ?? 'N/A' }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('booking_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Customer</label>
                                <select name="user_id" class="form-select @error('user_id') is-invalid @enderror">
                                    <option value="">Select Customer</option>
                                    @foreach(\App\Models\User::whereHas('roles', function($q) { $q->where('name', '!=', 'System Administrator'); })->get() as $user)
                                        <option value="{{ $user->id }}" {{ old('user_id') == $user->id ? 'selected' : '' }}>
                                            {{ $user->name }} ({{ $user->email }})
                                        </option>
                                    @endforeach
                                </select>
                                @error('user_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Customer Name <span class="text-danger">*</span></label>
                                <input type="text" name="customer_name" class="form-control @error('customer_name') is-invalid @enderror" value="{{ old('customer_name') }}" id="customerName" required>
                                @error('customer_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Customer Email <span class="text-danger">*</span></label>
                                <input type="email" name="customer_email" class="form-control @error('customer_email') is-invalid @enderror" value="{{ old('customer_email') }}" id="customerEmail" required>
                                @error('customer_email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Customer Phone</label>
                                <input type="text" name="customer_phone" class="form-control @error('customer_phone') is-invalid @enderror" value="{{ old('customer_phone') }}" id="customerPhone">
                                @error('customer_phone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Currency <span class="text-danger">*</span></label>
                                <select name="currency" class="form-select @error('currency') is-invalid @enderror" required>
                                    <option value="USD" {{ old('currency') == 'USD' ? 'selected' : '' }}>USD</option>
                                    <option value="TZS" {{ old('currency') == 'TZS' ? 'selected' : '' }}>TZS</option>
                                    <option value="EUR" {{ old('currency') == 'EUR' ? 'selected' : '' }}>EUR</option>
                                </select>
                                @error('currency')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-12">
                                <label class="form-label">Customer Address</label>
                                <textarea name="customer_address" class="form-control @error('customer_address') is-invalid @enderror" rows="2">{{ old('customer_address') }}</textarea>
                                @error('customer_address')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Subtotal <span class="text-danger">*</span></label>
                                <input type="number" name="subtotal" class="form-control @error('subtotal') is-invalid @enderror" value="{{ old('subtotal') }}" step="0.01" min="0" id="subtotal" required>
                                @error('subtotal')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Tax Amount</label>
                                <input type="number" name="tax_amount" class="form-control @error('tax_amount') is-invalid @enderror" value="{{ old('tax_amount', 0) }}" step="0.01" min="0" id="taxAmount">
                                @error('tax_amount')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Discount Amount</label>
                                <input type="number" name="discount_amount" class="form-control @error('discount_amount') is-invalid @enderror" value="{{ old('discount_amount', 0) }}" step="0.01" min="0" id="discountAmount">
                                @error('discount_amount')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Total Amount <span class="text-danger">*</span></label>
                                <input type="number" name="total_amount" class="form-control @error('total_amount') is-invalid @enderror" value="{{ old('total_amount') }}" step="0.01" min="0" id="totalAmount" required readonly>
                                <small class="text-muted">Calculated automatically</small>
                                @error('total_amount')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Invoice Date <span class="text-danger">*</span></label>
                                <input type="date" name="invoice_date" class="form-control @error('invoice_date') is-invalid @enderror" value="{{ old('invoice_date', date('Y-m-d')) }}" required>
                                @error('invoice_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Due Date <span class="text-danger">*</span></label>
                                <input type="date" name="due_date" class="form-control @error('due_date') is-invalid @enderror" value="{{ old('due_date', date('Y-m-d', strtotime('+30 days'))) }}" required>
                                @error('due_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-12">
                                <label class="form-label">Notes</label>
                                <textarea name="notes" class="form-control @error('notes') is-invalid @enderror" rows="3">{{ old('notes') }}</textarea>
                                @error('notes')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-12">
                                <label class="form-label">Terms & Conditions</label>
                                <textarea name="terms" class="form-control @error('terms') is-invalid @enderror" rows="3">{{ old('terms') }}</textarea>
                                @error('terms')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="mt-4">
                            <button type="submit" class="btn btn-primary">
                                <i class="ri-save-line me-1"></i>Create Invoice
                            </button>
                            <a href="{{ route('admin.finance.invoices') }}" class="btn btn-label-secondary">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
$(document).ready(function() {
    // Auto-fill customer details from booking
    $('#bookingSelect').on('change', function() {
        const selected = $(this).find('option:selected');
        if (selected.val()) {
            $('#customerName').val(selected.data('customer'));
            $('#customerEmail').val(selected.data('email'));
            $('#customerPhone').val(selected.data('phone'));
            $('#subtotal').val(selected.data('amount'));
            calculateTotal();
        }
    });

    // Calculate total amount
    function calculateTotal() {
        const subtotal = parseFloat($('#subtotal').val() || 0);
        const tax = parseFloat($('#taxAmount').val() || 0);
        const discount = parseFloat($('#discountAmount').val() || 0);
        const total = subtotal + tax - discount;
        $('#totalAmount').val(total.toFixed(2));
    }

    $('#subtotal, #taxAmount, #discountAmount').on('input', calculateTotal);
});
</script>
@endpush
@endsection



