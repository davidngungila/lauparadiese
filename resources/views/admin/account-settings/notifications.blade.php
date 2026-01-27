@extends('admin.layouts.app')

@section('title', 'Account Settings - Notifications')
@section('description', 'Manage your notification preferences')

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
                    <a class="nav-link" href="{{ route('admin.account-settings.billing') }}">
                        <i class="icon-base ri ri-bookmark-line icon-sm me-1_5"></i>Billing & Plans
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link active" href="{{ route('admin.account-settings.notifications') }}">
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
        
        <div class="card mb-6">
            <h5 class="card-header">Notification Preferences</h5>
            <div class="card-body">
                <form id="formNotificationSettings" method="POST" action="{{ route('admin.account-settings.notifications.update') }}">
                    @csrf
                    @method('PUT')
                    
                    <!-- Email Notifications -->
                    <div class="d-flex align-items-center justify-content-between mb-6">
                        <div>
                            <h6 class="mb-1">Email Notifications</h6>
                            <small class="text-body-secondary">Receive notifications via email</small>
                        </div>
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="email_notifications" name="email_notifications" value="1" {{ old('email_notifications', $user->email_notifications ?? true) ? 'checked' : '' }} />
                            <label class="form-check-label" for="email_notifications"></label>
                        </div>
                    </div>
                    
                    <!-- SMS Notifications -->
                    <div class="d-flex align-items-center justify-content-between mb-6">
                        <div>
                            <h6 class="mb-1">SMS Notifications</h6>
                            <small class="text-body-secondary">Receive notifications via SMS</small>
                        </div>
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="sms_notifications" name="sms_notifications" value="1" {{ old('sms_notifications', $user->sms_notifications ?? false) ? 'checked' : '' }} />
                            <label class="form-check-label" for="sms_notifications"></label>
                        </div>
                    </div>
                    
                    <!-- Push Notifications -->
                    <div class="d-flex align-items-center justify-content-between mb-6">
                        <div>
                            <h6 class="mb-1">Push Notifications</h6>
                            <small class="text-body-secondary">Receive push notifications in browser</small>
                        </div>
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="push_notifications" name="push_notifications" value="1" {{ old('push_notifications', $user->push_notifications ?? true) ? 'checked' : '' }} />
                            <label class="form-check-label" for="push_notifications"></label>
                        </div>
                    </div>
                    
                    <hr class="my-4">
                    
                    <!-- Booking Notifications -->
                    <div class="d-flex align-items-center justify-content-between mb-6">
                        <div>
                            <h6 class="mb-1">Booking Notifications</h6>
                            <small class="text-body-secondary">Get notified about new bookings and updates</small>
                        </div>
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="booking_notifications" name="booking_notifications" value="1" {{ old('booking_notifications', $user->booking_notifications ?? true) ? 'checked' : '' }} />
                            <label class="form-check-label" for="booking_notifications"></label>
                        </div>
                    </div>
                    
                    <!-- Payment Notifications -->
                    <div class="d-flex align-items-center justify-content-between mb-6">
                        <div>
                            <h6 class="mb-1">Payment Notifications</h6>
                            <small class="text-body-secondary">Get notified about payment updates</small>
                        </div>
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="payment_notifications" name="payment_notifications" value="1" {{ old('payment_notifications', $user->payment_notifications ?? true) ? 'checked' : '' }} />
                            <label class="form-check-label" for="payment_notifications"></label>
                        </div>
                    </div>
                    
                    <!-- Marketing Notifications -->
                    <div class="d-flex align-items-center justify-content-between mb-6">
                        <div>
                            <h6 class="mb-1">Marketing Notifications</h6>
                            <small class="text-body-secondary">Receive marketing and promotional emails</small>
                        </div>
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="marketing_notifications" name="marketing_notifications" value="1" {{ old('marketing_notifications', $user->marketing_notifications ?? false) ? 'checked' : '' }} />
                            <label class="form-check-label" for="marketing_notifications"></label>
                        </div>
                    </div>
                    
                    <div class="mt-6">
                        <button type="submit" class="btn btn-primary">Save changes</button>
                    </div>
                </form>
            </div>
        </div>
        
        <!-- Notification Settings Info -->
        <div class="card">
            <h5 class="card-header">Notification Settings</h5>
            <div class="card-body">
                <div class="alert alert-info">
                    <h6 class="alert-heading">Notification Delivery</h6>
                    <p class="mb-0">You can customize how you receive notifications. Enable or disable specific notification types based on your preferences.</p>
                </div>
                <ul class="list-unstyled mb-0">
                    <li class="d-flex align-items-center mb-3">
                        <i class="icon-base ri ri-mail-line text-primary me-2"></i>
                        <span>Email notifications are sent to: <strong>{{ $user->email }}</strong></span>
                    </li>
                    <li class="d-flex align-items-center mb-3">
                        <i class="icon-base ri ri-phone-line text-primary me-2"></i>
                        <span>SMS notifications are sent to: <strong>{{ $user->phone ?? $user->mobile ?? 'Not set' }}</strong></span>
                    </li>
                    <li class="d-flex align-items-center">
                        <i class="icon-base ri ri-notification-line text-primary me-2"></i>
                        <span>Push notifications require browser permission</span>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const formNotificationSettings = document.getElementById('formNotificationSettings');
    if (formNotificationSettings) {
        formNotificationSettings.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            // Convert checkboxes to boolean values
            const checkboxes = ['email_notifications', 'sms_notifications', 'push_notifications', 'booking_notifications', 'payment_notifications', 'marketing_notifications'];
            checkboxes.forEach(name => {
                formData.set(name, this.querySelector(`#${name}`).checked ? '1' : '0');
            });
            
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
                        showToast(data.message || 'Notification preferences updated successfully!', 'success');
                    } else {
                        alert(data.message || 'Notification preferences updated successfully!');
                    }
                    setTimeout(() => window.location.reload(), 1000);
                } else {
                    throw new Error(data.message || 'Failed to update notification preferences');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                if (typeof showToast === 'function') {
                    showToast(error.message || 'Failed to update notification preferences', 'error');
                } else {
                    alert('Error: ' + (error.message || 'Failed to update notification preferences'));
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

