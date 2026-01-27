@extends('admin.layouts.app')

@section('title', 'Account Settings - Account')
@section('description', 'Manage your account information and preferences')

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="nav-align-top">
            <ul class="nav nav-pills flex-column flex-md-row mb-6 gap-2 gap-lg-0">
                <li class="nav-item">
                    <a class="nav-link active" href="{{ route('admin.account-settings') }}">
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
        
        <div class="card mb-6">
            <!-- Account -->
            <div class="card-body">
                <div class="d-flex align-items-start align-items-sm-center gap-6">
                    @if($user->avatar)
                        <img src="{{ asset('storage/' . $user->avatar) }}" alt="user-avatar" class="d-block w-px-100 h-px-100 rounded" id="uploadedAvatar" />
                    @else
                        <div class="avatar-initials d-block w-px-100 h-px-100 rounded d-flex align-items-center justify-content-center" id="uploadedAvatar" style="width: 100px; height: 100px; font-size: 2.5rem; background-color: var(--bs-primary); color: white;">
                            {{ $user->initials }}
                        </div>
                    @endif
                    <div class="button-wrapper">
                        <form action="{{ route('admin.profile.avatar') }}" method="POST" enctype="multipart/form-data" id="avatarForm">
                            @csrf
                            @method('PUT')
                            <label for="upload" class="btn btn-sm btn-primary me-3 mb-4" tabindex="0">
                                <span class="d-none d-sm-block">Upload new photo</span>
                                <i class="icon-base ri ri-upload-2-line d-block d-sm-none"></i>
                                <input type="file" id="upload" name="avatar" class="account-file-input" hidden accept="image/png, image/jpeg" onchange="document.getElementById('avatarForm').submit();" />
                            </label>
                        </form>
                        <button type="button" class="btn btn-sm btn-outline-danger account-image-reset mb-4" onclick="resetAvatar()">
                            <i class="icon-base ri ri-refresh-line d-block d-sm-none"></i>
                            <span class="d-none d-sm-block">Reset</span>
                        </button>
                        <div>Allowed JPG, GIF or PNG. Max size of 800K</div>
                    </div>
                </div>
            </div>
            
            <div class="card-body pt-0">
                <form id="formAccountSettings" method="POST" action="{{ route('admin.account-settings.update') }}">
                    @csrf
                    @method('PUT')
                    <div class="row mt-1 g-5">
                        <div class="col-md-6 form-control-validation">
                            <div class="form-floating form-floating-outline">
                                <input class="form-control" type="text" id="name" name="name" value="{{ old('name', $user->name) }}" autofocus required />
                                <label for="name">Full Name</label>
                            </div>
                            @error('name')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6 form-control-validation">
                            <div class="form-floating form-floating-outline">
                                <input class="form-control" type="email" id="email" name="email" value="{{ old('email', $user->email) }}" required />
                                <label for="email">E-mail</label>
                            </div>
                            @error('email')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <div class="input-group input-group-merge">
                                <div class="form-floating form-floating-outline">
                                    <input type="text" id="phone" name="phone" class="form-control" placeholder="Phone Number" value="{{ old('phone', $user->phone) }}" />
                                    <label for="phone">Phone Number</label>
                                </div>
                                <span class="input-group-text">+255</span>
                            </div>
                            @error('phone')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <div class="form-floating form-floating-outline">
                                <input type="text" class="form-control" id="mobile" name="mobile" placeholder="Mobile" value="{{ old('mobile', $user->mobile) }}" />
                                <label for="mobile">Mobile</label>
                            </div>
                            @error('mobile')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <div class="form-floating form-floating-outline">
                                <input type="text" class="form-control" id="address" name="address" placeholder="Address" value="{{ old('address', $user->address) }}" />
                                <label for="address">Address</label>
                            </div>
                            @error('address')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <div class="form-floating form-floating-outline">
                                <input class="form-control" type="text" id="city" name="city" placeholder="City" value="{{ old('city', $user->city) }}" />
                                <label for="city">City</label>
                            </div>
                            @error('city')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <div class="form-floating form-floating-outline">
                                <input type="text" class="form-control" id="country" name="country" placeholder="Country" value="{{ old('country', $user->country ?? 'Tanzania') }}" />
                                <label for="country">Country</label>
                            </div>
                            @error('country')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <div class="form-floating form-floating-outline">
                                <input type="date" class="form-control" id="date_of_birth" name="date_of_birth" value="{{ old('date_of_birth', $user->date_of_birth ? $user->date_of_birth->format('Y-m-d') : '') }}" />
                                <label for="date_of_birth">Date of Birth</label>
                            </div>
                            @error('date_of_birth')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <div class="form-floating form-floating-outline">
                                <select id="timezone" name="timezone" class="select2 form-select">
                                    <option value="">Select Timezone</option>
                                    <option value="Africa/Dar_es_Salaam" {{ old('timezone', $user->timezone ?? '') == 'Africa/Dar_es_Salaam' ? 'selected' : '' }}>(GMT+03:00) Dar es Salaam</option>
                                    <option value="UTC" {{ old('timezone', $user->timezone ?? '') == 'UTC' ? 'selected' : '' }}>(GMT+00:00) UTC</option>
                                    <option value="America/New_York" {{ old('timezone', $user->timezone ?? '') == 'America/New_York' ? 'selected' : '' }}>(GMT-05:00) Eastern Time</option>
                                    <option value="Europe/London" {{ old('timezone', $user->timezone ?? '') == 'Europe/London' ? 'selected' : '' }}>(GMT+00:00) London</option>
                                </select>
                                <label for="timezone">Timezone</label>
                            </div>
                            @error('timezone')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <div class="form-floating form-floating-outline">
                                <select id="currency" name="currency" class="select2 form-select">
                                    <option value="">Select Currency</option>
                                    <option value="TZS" {{ old('currency', $user->currency ?? '') == 'TZS' ? 'selected' : '' }}>TZS - Tanzanian Shilling</option>
                                    <option value="USD" {{ old('currency', $user->currency ?? '') == 'USD' ? 'selected' : '' }}>USD - US Dollar</option>
                                    <option value="EUR" {{ old('currency', $user->currency ?? '') == 'EUR' ? 'selected' : '' }}>EUR - Euro</option>
                                    <option value="GBP" {{ old('currency', $user->currency ?? '') == 'GBP' ? 'selected' : '' }}>GBP - British Pound</option>
                                </select>
                                <label for="currency">Currency</label>
                            </div>
                            @error('currency')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <div class="form-floating form-floating-outline">
                                <select id="language" name="language" class="select2 form-select">
                                    <option value="">Select Language</option>
                                    <option value="en" {{ old('language', $user->language ?? 'en') == 'en' ? 'selected' : '' }}>English</option>
                                    <option value="sw" {{ old('language', $user->language ?? '') == 'sw' ? 'selected' : '' }}>Swahili</option>
                                    <option value="fr" {{ old('language', $user->language ?? '') == 'fr' ? 'selected' : '' }}>French</option>
                                </select>
                                <label for="language">Language</label>
                            </div>
                            @error('language')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-12">
                            <div class="form-floating form-floating-outline">
                                <textarea class="form-control" id="bio" name="bio" rows="3" placeholder="Bio">{{ old('bio', $user->bio) }}</textarea>
                                <label for="bio">Bio</label>
                            </div>
                            @error('bio')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                        </div>
                    </div>
                    <div class="mt-6">
                        <button type="submit" class="btn btn-primary me-3">Save changes</button>
                        <button type="reset" class="btn btn-outline-secondary">Reset</button>
                    </div>
                </form>
            </div>
            <!-- /Account -->
        </div>
        
        <div class="card">
            <h5 class="card-header">Delete Account</h5>
            <div class="card-body">
                <form id="formAccountDeactivation" method="POST" action="{{ route('admin.account-settings.deactivate') }}">
                    @csrf
                    <div class="form-check mb-6 ms-3">
                        <input class="form-check-input" type="checkbox" name="confirm_deactivation" id="accountActivation" />
                        <label class="form-check-label" for="accountActivation">I confirm my account deactivation</label>
                    </div>
                    <button type="submit" class="btn btn-danger deactivate-account" disabled="disabled">Deactivate Account</button>
                </form>
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
    // Initialize Select2
    if ($('.select2').length) {
        $('.select2').select2();
    }
    
    // Enable/Disable deactivate button based on checkbox
    const accountActivation = document.getElementById('accountActivation');
    const deactivateBtn = document.querySelector('.deactivate-account');
    
    if (accountActivation && deactivateBtn) {
        accountActivation.addEventListener('change', function() {
            deactivateBtn.disabled = !this.checked;
        });
    }
    
    // Form validation and AJAX submission
    const formAccountSettings = document.getElementById('formAccountSettings');
    if (formAccountSettings) {
        formAccountSettings.addEventListener('submit', function(e) {
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
            .then(response => {
                if (!response.ok) {
                    return response.json().then(data => {
                        throw new Error(data.message || 'Failed to update account settings');
                    });
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    // Show success notification
                    if (typeof showToast === 'function') {
                        showToast(data.message || 'Account settings updated successfully!', 'success');
                    } else {
                        alert(data.message || 'Account settings updated successfully!');
                    }
                    // Reload page to show updated data
                    setTimeout(() => window.location.reload(), 1000);
                } else {
                    throw new Error(data.message || 'Failed to update account settings');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                if (typeof showToast === 'function') {
                    showToast(error.message || 'Failed to update account settings', 'error');
                } else {
                    alert('Error: ' + (error.message || 'Failed to update account settings'));
                }
            })
            .finally(() => {
                submitBtn.disabled = false;
                submitBtn.innerHTML = originalText;
            });
        });
    }
    
    // Deactivate account confirmation
    const formAccountDeactivation = document.getElementById('formAccountDeactivation');
    if (formAccountDeactivation) {
        formAccountDeactivation.addEventListener('submit', function(e) {
            e.preventDefault();
            
            if (!confirm('Are you sure you want to deactivate your account? This action cannot be undone.')) {
                return;
            }
            
            const formData = new FormData(this);
            const submitBtn = this.querySelector('button[type="submit"]');
            const originalText = submitBtn.innerHTML;
            
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Deactivating...';
            
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
                    alert('Your account has been deactivated. You will be logged out.');
                    window.location.href = '{{ route("login") }}';
                } else {
                    throw new Error(data.message || 'Failed to deactivate account');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error: ' + (error.message || 'Failed to deactivate account'));
                submitBtn.disabled = false;
                submitBtn.innerHTML = originalText;
            });
        });
    }
});

function resetAvatar() {
    if (confirm('Are you sure you want to reset your avatar? This will remove your profile picture.')) {
        // Implement avatar reset logic
        alert('Avatar reset functionality will be implemented');
    }
}
</script>
@endpush

