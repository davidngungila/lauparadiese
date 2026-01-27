@extends('admin.layouts.app')

@section('title', 'Account Settings - Billing & Plans')
@section('description', 'Manage your billing information and subscription')

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="nav-align-top">
            <ul class="nav nav-pills flex-column flex-md-row mb-6 gap-2 gap-lg-0">
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('admin.account-settings') }}">
                        <i class="icon-base ri ri-group-line icon-sm me-1_5"></i>Account
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('admin.account-settings.security') }}">
                        <i class="icon-base ri ri-lock-line icon-sm me-1_5"></i>Security
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link active" href="{{ route('admin.account-settings.billing') }}">
                        <i class="icon-base ri ri-bookmark-line icon-sm me-1_5"></i>Billing & Plans
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('admin.account-settings.notifications') }}">
                        <i class="icon-base ri ri-notification-4-line icon-sm me-1_5"></i>Notifications
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('admin.account-settings.connections') }}">
                        <i class="icon-base ri ri-link-m icon-sm me-1_5"></i>Connections
                    </a>
                </li>
            </ul>
        </div>
        
        <!-- Current Plan -->
        <div class="card mb-6">
            <h5 class="card-header">Current Plan</h5>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="d-flex align-items-center mb-4">
                            <div class="avatar avatar-lg me-4">
                                <span class="avatar-initial rounded bg-label-primary">
                                    <i class="icon-base ri ri-vip-crown-line icon-32px"></i>
                                </span>
                            </div>
                            <div>
                                <h5 class="mb-0">Professional Plan</h5>
                                <small class="text-body-secondary">Active until {{ now()->addYear()->format('M d, Y') }}</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 text-md-end">
                        <h4 class="mb-0">$99.00</h4>
                        <small class="text-body-secondary">per month</small>
                        <div class="mt-3">
                            <button type="button" class="btn btn-outline-primary">Upgrade Plan</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Billing Address -->
        <div class="card mb-6">
            <h5 class="card-header">Billing Address</h5>
            <div class="card-body">
                <form id="formBillingSettings" method="POST" action="{{ route('admin.account-settings.billing.update') }}">
                    @csrf
                    @method('PUT')
                    <div class="row g-4">
                        <div class="col-md-12">
                            <div class="form-floating form-floating-outline">
                                <input type="text" class="form-control" id="billing_address" name="billing_address" placeholder="Billing Address" value="{{ old('billing_address', $user->billing_address ?? $user->address) }}" />
                                <label for="billing_address">Billing Address</label>
                            </div>
                            @error('billing_address')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <div class="form-floating form-floating-outline">
                                <input type="text" class="form-control" id="billing_city" name="billing_city" placeholder="City" value="{{ old('billing_city', $user->billing_city ?? $user->city) }}" />
                                <label for="billing_city">City</label>
                            </div>
                            @error('billing_city')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <div class="form-floating form-floating-outline">
                                <input type="text" class="form-control" id="billing_country" name="billing_country" placeholder="Country" value="{{ old('billing_country', $user->billing_country ?? $user->country ?? 'Tanzania') }}" />
                                <label for="billing_country">Country</label>
                            </div>
                            @error('billing_country')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <div class="form-floating form-floating-outline">
                                <input type="text" class="form-control" id="billing_postal_code" name="billing_postal_code" placeholder="Postal Code" value="{{ old('billing_postal_code', $user->billing_postal_code ?? '') }}" />
                                <label for="billing_postal_code">Postal Code</label>
                            </div>
                            @error('billing_postal_code')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <div class="form-floating form-floating-outline">
                                <select id="payment_method" name="payment_method" class="select2 form-select">
                                    <option value="">Select Payment Method</option>
                                    <option value="credit_card" {{ old('payment_method', $user->payment_method ?? '') == 'credit_card' ? 'selected' : '' }}>Credit Card</option>
                                    <option value="bank_transfer" {{ old('payment_method', $user->payment_method ?? '') == 'bank_transfer' ? 'selected' : '' }}>Bank Transfer</option>
                                    <option value="mpesa" {{ old('payment_method', $user->payment_method ?? '') == 'mpesa' ? 'selected' : '' }}>MPESA</option>
                                    <option value="paypal" {{ old('payment_method', $user->payment_method ?? '') == 'paypal' ? 'selected' : '' }}>PayPal</option>
                                </select>
                                <label for="payment_method">Preferred Payment Method</label>
                            </div>
                            @error('payment_method')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                        </div>
                    </div>
                    <div class="mt-6">
                        <button type="submit" class="btn btn-primary">Save changes</button>
                    </div>
                </form>
            </div>
        </div>
        
        <!-- Payment History -->
        <div class="card">
            <h5 class="card-header">Payment History</h5>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-borderless">
                        <thead>
                            <tr>
                                <th>Invoice</th>
                                <th>Amount</th>
                                <th>Date</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>INV-2024-001</td>
                                <td>$99.00</td>
                                <td>{{ now()->subMonth()->format('M d, Y') }}</td>
                                <td><span class="badge bg-label-success">Paid</span></td>
                                <td>
                                    <a href="javascript:void(0);" class="btn btn-sm btn-icon">
                                        <i class="ri-download-line"></i>
                                    </a>
                                </td>
                            </tr>
                            <tr>
                                <td>INV-2024-002</td>
                                <td>$99.00</td>
                                <td>{{ now()->subMonths(2)->format('M d, Y') }}</td>
                                <td><span class="badge bg-label-success">Paid</span></td>
                                <td>
                                    <a href="javascript:void(0);" class="btn btn-sm btn-icon">
                                        <i class="ri-download-line"></i>
                                    </a>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<link rel="stylesheet" href="{{ asset('assets/vendor/libs/select2/select2.css') }}">
<script src="{{ asset('assets/vendor/libs/select2/select2.js') }}"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    if ($('.select2').length) {
        $('.select2').select2();
    }
    
    const formBillingSettings = document.getElementById('formBillingSettings');
    if (formBillingSettings) {
        formBillingSettings.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            const submitBtn = this.querySelector('button[type="submit"]');
            const originalText = submitBtn.innerHTML;
            
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Saving...';
            
            fetch(this.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    if (typeof showToast === 'function') {
                        showToast(data.message || 'Billing information updated successfully!', 'success');
                    } else {
                        alert(data.message || 'Billing information updated successfully!');
                    }
                    setTimeout(() => window.location.reload(), 1000);
                } else {
                    throw new Error(data.message || 'Failed to update billing information');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                if (typeof showToast === 'function') {
                    showToast(error.message || 'Failed to update billing information', 'error');
                } else {
                    alert('Error: ' + (error.message || 'Failed to update billing information'));
                }
            })
            .finally(() => {
                submitBtn.disabled = false;
                submitBtn.innerHTML = originalText;
            });
        });
    }
});
</script>
@endpush

