@extends('admin.layouts.app')

@section('title', 'Account Settings - Security')
@section('description', 'Manage your security settings and password')

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
                    <a class="nav-link active" href="{{ route('admin.account-settings.security') }}">
                        <i class="icon-base ri ri-lock-line icon-sm me-1_5"></i>Security
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('admin.account-settings.billing') }}">
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
        
        <!-- Change Password -->
        <div class="card mb-6">
            <h5 class="card-header">Change Password</h5>
            <div class="card-body">
                <form id="formAccountPassword" method="POST" action="{{ route('admin.account-settings.security.update') }}">
                    @csrf
                    @method('PUT')
                    <div class="row g-4">
                        <div class="col-md-12">
                            <div class="form-floating form-floating-outline">
                                <input class="form-control" type="password" id="current_password" name="current_password" required />
                                <label for="current_password">Current Password</label>
                            </div>
                            @error('current_password')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <div class="form-floating form-floating-outline">
                                <input class="form-control" type="password" id="password" name="password" required />
                                <label for="password">New Password</label>
                            </div>
                            @error('password')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                            <small class="text-body-secondary">Must be at least 8 characters</small>
                        </div>
                        <div class="col-md-6">
                            <div class="form-floating form-floating-outline">
                                <input class="form-control" type="password" id="password_confirmation" name="password_confirmation" required />
                                <label for="password_confirmation">Confirm New Password</label>
                            </div>
                        </div>
                    </div>
                    <div class="mt-6">
                        <button type="submit" class="btn btn-primary">Update Password</button>
                    </div>
                </form>
            </div>
        </div>
        
        <!-- Two-Factor Authentication -->
        <div class="card mb-6">
            <h5 class="card-header">Two-Factor Authentication</h5>
            <div class="card-body">
                <p class="card-text">Add an extra layer of security to your account by enabling two-factor authentication.</p>
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <h6 class="mb-1">SMS Authentication</h6>
                        <small class="text-body-secondary">Use your mobile phone to receive verification codes</small>
                    </div>
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" id="smsAuth" />
                        <label class="form-check-label" for="smsAuth"></label>
                    </div>
                </div>
                <hr class="my-4">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <h6 class="mb-1">Email Authentication</h6>
                        <small class="text-body-secondary">Use your email to receive verification codes</small>
                    </div>
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" id="emailAuth" />
                        <label class="form-check-label" for="emailAuth"></label>
                    </div>
                </div>
                <div class="mt-4">
                    <button type="button" class="btn btn-outline-primary">Configure 2FA</button>
                </div>
            </div>
        </div>
        
        <!-- Recent Login Activity -->
        <div class="card">
            <h5 class="card-header">Recent Login Activity</h5>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-borderless">
                        <thead>
                            <tr>
                                <th>Device</th>
                                <th>Location</th>
                                <th>IP Address</th>
                                <th>Date & Time</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <i class="icon-base ri ri-computer-line me-2"></i>
                                        <span>Windows - Chrome</span>
                                    </div>
                                </td>
                                <td>Dar es Salaam, Tanzania</td>
                                <td>192.168.1.1</td>
                                <td>{{ now()->format('M d, Y H:i') }}</td>
                                <td><span class="badge bg-label-success">Active</span></td>
                            </tr>
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <i class="icon-base ri ri-smartphone-line me-2"></i>
                                        <span>iOS - Safari</span>
                                    </div>
                                </td>
                                <td>Arusha, Tanzania</td>
                                <td>192.168.1.2</td>
                                <td>{{ now()->subHours(2)->format('M d, Y H:i') }}</td>
                                <td><span class="badge bg-label-secondary">Inactive</span></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="mt-4">
                    <button type="button" class="btn btn-outline-danger">Logout from all devices</button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const formAccountPassword = document.getElementById('formAccountPassword');
    if (formAccountPassword) {
        formAccountPassword.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            const submitBtn = this.querySelector('button[type="submit"]');
            const originalText = submitBtn.innerHTML;
            
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Updating...';
            
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
                        showToast(data.message || 'Password updated successfully!', 'success');
                    } else {
                        alert(data.message || 'Password updated successfully!');
                    }
                    this.reset();
                    setTimeout(() => window.location.reload(), 1000);
                } else {
                    throw new Error(data.message || 'Failed to update password');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                if (typeof showToast === 'function') {
                    showToast(error.message || 'Failed to update password', 'error');
                } else {
                    alert('Error: ' + (error.message || 'Failed to update password'));
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

