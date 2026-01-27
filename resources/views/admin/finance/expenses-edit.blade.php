@extends('admin.layouts.app')

@section('title', 'Edit Expense - Lau Paradise Adventures')
@section('description', 'Edit expense')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">
                        <i class="ri-edit-line me-2"></i>Edit Expense
                    </h4>
                    <a href="{{ route('admin.finance.expenses') }}" class="btn btn-label-secondary">
                        <i class="ri-arrow-left-line me-1"></i>Back to Expenses
                    </a>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.finance.expenses.update', $expense->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">Expense Category <span class="text-danger">*</span></label>
                                <input type="text" name="expense_category" class="form-control @error('expense_category') is-invalid @enderror" value="{{ old('expense_category', $expense->expense_category) }}" required>
                                @error('expense_category')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Expense Date <span class="text-danger">*</span></label>
                                <input type="date" name="expense_date" class="form-control @error('expense_date') is-invalid @enderror" value="{{ old('expense_date', $expense->expense_date?->format('Y-m-d')) }}" required>
                                @error('expense_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-12">
                                <label class="form-label">Description <span class="text-danger">*</span></label>
                                <textarea name="description" class="form-control @error('description') is-invalid @enderror" rows="3" required>{{ old('description', $expense->description) }}</textarea>
                                @error('description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Amount <span class="text-danger">*</span></label>
                                <input type="number" name="amount" class="form-control @error('amount') is-invalid @enderror" value="{{ old('amount', $expense->amount) }}" step="0.01" min="0" required>
                                @error('amount')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Currency <span class="text-danger">*</span></label>
                                <select name="currency" class="form-select @error('currency') is-invalid @enderror" required>
                                    <option value="USD" {{ old('currency', $expense->currency) == 'USD' ? 'selected' : '' }}>USD</option>
                                    <option value="TZS" {{ old('currency', $expense->currency) == 'TZS' ? 'selected' : '' }}>TZS</option>
                                    <option value="EUR" {{ old('currency', $expense->currency) == 'EUR' ? 'selected' : '' }}>EUR</option>
                                </select>
                                @error('currency')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Payment Method</label>
                                <select name="payment_method" class="form-select @error('payment_method') is-invalid @enderror">
                                    <option value="">Select Method</option>
                                    <option value="cash" {{ old('payment_method', $expense->payment_method) == 'cash' ? 'selected' : '' }}>Cash</option>
                                    <option value="card" {{ old('payment_method', $expense->payment_method) == 'card' ? 'selected' : '' }}>Card</option>
                                    <option value="bank_transfer" {{ old('payment_method', $expense->payment_method) == 'bank_transfer' ? 'selected' : '' }}>Bank Transfer</option>
                                    <option value="mobile_money" {{ old('payment_method', $expense->payment_method) == 'mobile_money' ? 'selected' : '' }}>Mobile Money</option>
                                </select>
                                @error('payment_method')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Related Tour</label>
                                <select name="tour_id" class="form-select @error('tour_id') is-invalid @enderror">
                                    <option value="">No Tour</option>
                                    @foreach($tours ?? [] as $tour)
                                        <option value="{{ $tour->id }}" {{ old('tour_id', $expense->tour_id) == $tour->id ? 'selected' : '' }}>
                                            {{ $tour->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('tour_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Related Booking</label>
                                <select name="booking_id" class="form-select @error('booking_id') is-invalid @enderror">
                                    <option value="">No Booking</option>
                                    @foreach($bookings ?? [] as $booking)
                                        <option value="{{ $booking->id }}" {{ old('booking_id', $expense->booking_id) == $booking->id ? 'selected' : '' }}>
                                            Booking #{{ $booking->id }} - {{ $booking->customer_name ?? 'N/A' }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('booking_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Receipt Number</label>
                                <input type="text" name="receipt_number" class="form-control @error('receipt_number') is-invalid @enderror" value="{{ old('receipt_number', $expense->receipt_number) }}">
                                @error('receipt_number')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-12">
                                <label class="form-label">Notes</label>
                                <textarea name="notes" class="form-control @error('notes') is-invalid @enderror" rows="3">{{ old('notes', $expense->notes) }}</textarea>
                                @error('notes')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="mt-4">
                            <button type="submit" class="btn btn-primary">
                                <i class="ri-save-line me-1"></i>Update Expense
                            </button>
                            <a href="{{ route('admin.finance.expenses') }}" class="btn btn-label-secondary">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection



