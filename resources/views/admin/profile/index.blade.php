@extends('admin.layouts.app')

@section('title', 'My Profile')
@section('description', 'Manage your profile, account settings, and view your activity')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-12">
            <h4 class="fw-bold py-3 mb-4">
                <span class="text-muted fw-light">Account /</span> Profile
            </h4>
        </div>
    </div>

    <!-- Profile Overview Card -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-start align-items-sm-center gap-4">
                        <div class="avatar-wrapper">
                            @if($user->avatar)
                                <img src="{{ asset('storage/' . $user->avatar) }}" alt="user-avatar" class="d-block rounded" id="uploadedAvatar" style="width: 120px; height: 120px; object-fit: cover;" />
                            @else
                                <div class="avatar-initial bg-label-primary d-flex align-items-center justify-content-center rounded" id="uploadedAvatar" style="width: 120px; height: 120px; font-size: 3rem; font-weight: 600;">
                                    {{ $user->initials }}
                                </div>
                            @endif
                            <form action="{{ route('admin.profile.avatar') }}" method="POST" enctype="multipart/form-data" id="avatarForm" class="d-none">
                                @csrf
                                @method('PUT')
                                <input type="file" id="upload" name="avatar" class="account-file-input" accept="image/png, image/jpeg" />
                            </form>
                        </div>
                        <div class="flex-grow-1">
                            <div class="d-flex align-items-center gap-3 mb-2">
                                <h5 class="mb-0">{{ $user->name }}</h5>
                                @if($user->email_verified_at)
                                    <span class="badge bg-label-success">
                                        <i class="ri-check-line me-1"></i>Verified
                                    </span>
                                @else
                                    <span class="badge bg-label-warning">
                                        <i class="ri-close-line me-1"></i>Unverified
                                    </span>
                                @endif
                            </div>
                            <p class="mb-2 text-muted">{{ $user->email }}</p>
                            @if($user->phone)
                                <p class="mb-2 text-muted">
                                    <i class="ri-phone-line me-1"></i>{{ $user->phone }}
                                </p>
                            @endif
                            <div class="d-flex gap-2 mt-3">
                                <label for="upload" class="btn btn-sm btn-primary" tabindex="0">
                                    <i class="ri-upload-2-line me-1"></i>Upload Photo
                                </label>
                                <button type="button" class="btn btn-sm btn-outline-secondary account-image-reset">
                                    <i class="ri-refresh-line me-1"></i>Reset
                                </button>
                            </div>
                            <p class="text-muted small mt-2 mb-0">Allowed JPG, GIF or PNG. Max size of 800K</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-lg-3 col-md-6 mb-4">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="mb-1">Total Bookings</h6>
                            <h4 class="mb-0">{{ $stats['total_bookings'] }}</h4>
                        </div>
                        <div class="avatar">
                            <div class="avatar-initial bg-label-primary rounded">
                                <i class="ri-calendar-check-line ri-24px"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 mb-4">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="mb-1">Confirmed</h6>
                            <h4 class="mb-0">{{ $stats['confirmed_bookings'] }}</h4>
                        </div>
                        <div class="avatar">
                            <div class="avatar-initial bg-label-success rounded">
                                <i class="ri-checkbox-circle-line ri-24px"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 mb-4">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="mb-1">Total Spent</h6>
                            <h4 class="mb-0">{{ number_format($stats['total_spent'], 2) }}</h4>
                        </div>
                        <div class="avatar">
                            <div class="avatar-initial bg-label-info rounded">
                                <i class="ri-money-dollar-circle-line ri-24px"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 mb-4">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="mb-1">Roles</h6>
                            <h4 class="mb-0">{{ $stats['roles_count'] }}</h4>
                        </div>
                        <div class="avatar">
                            <div class="avatar-initial bg-label-warning rounded">
                                <i class="ri-user-settings-line ri-24px"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabs Navigation -->
    <div class="row">
        <div class="col-12">
            <div class="nav-align-top mb-4">
                <ul class="nav nav-pills flex-column flex-md-row" role="tablist">
                    <li class="nav-item">
                        <button type="button" class="nav-link active" role="tab" data-bs-toggle="tab" data-bs-target="#account" aria-controls="account" aria-selected="true">
                            <i class="ri-user-line me-1"></i>Account
                        </button>
                    </li>
                    <li class="nav-item">
                        <button type="button" class="nav-link" role="tab" data-bs-toggle="tab" data-bs-target="#roles-permissions" aria-controls="roles-permissions" aria-selected="false">
                            <i class="ri-shield-user-line me-1"></i>Roles & Permissions
                        </button>
                    </li>
                    <li class="nav-item">
                        <button type="button" class="nav-link" role="tab" data-bs-toggle="tab" data-bs-target="#bookings" aria-controls="bookings" aria-selected="false">
                            <i class="ri-calendar-check-line me-1"></i>Recent Bookings
                        </button>
                    </li>
                    <li class="nav-item">
                        <button type="button" class="nav-link" role="tab" data-bs-toggle="tab" data-bs-target="#security" aria-controls="security" aria-selected="false">
                            <i class="ri-lock-line me-1"></i>Security
                        </button>
                    </li>
                </ul>
            </div>
        </div>
    </div>

    <!-- Tab Content -->
    <div class="tab-content">
        <!-- Account Tab -->
        <div class="tab-pane fade show active" id="account" role="tabpanel">
            <div class="row">
                <div class="col-12">
                    <div class="card mb-4">
                        <h5 class="card-header">Account Information</h5>
                        <div class="card-body">
                            <form id="formAccountSettings" method="POST" action="{{ route('admin.profile.update') }}">
                                @csrf
                                @method('PUT')
                                <div class="row g-4">
                                    <div class="col-md-6">
                                        <div class="form-floating form-floating-outline">
                                            <input class="form-control" type="text" id="name" name="name" value="{{ old('name', $user->name) }}" autofocus required />
                                            <label for="name">Full Name <span class="text-danger">*</span></label>
                                            @error('name')
                                                <div class="text-danger small mt-1">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-floating form-floating-outline">
                                            <input class="form-control" type="email" id="email" name="email" value="{{ old('email', $user->email) }}" required />
                                            <label for="email">E-mail <span class="text-danger">*</span></label>
                                            @error('email')
                                                <div class="text-danger small mt-1">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-floating form-floating-outline">
                                            <input type="text" id="phone" name="phone" class="form-control" placeholder="Phone Number" value="{{ old('phone', $user->phone) }}" />
                                            <label for="phone">Phone Number</label>
                                            @error('phone')
                                                <div class="text-danger small mt-1">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-floating form-floating-outline">
                                            <input type="text" class="form-control" id="mobile" name="mobile" placeholder="Mobile" value="{{ old('mobile', $user->mobile) }}" />
                                            <label for="mobile">Mobile</label>
                                            @error('mobile')
                                                <div class="text-danger small mt-1">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-floating form-floating-outline">
                                            <input type="text" class="form-control" id="address" name="address" placeholder="Address" value="{{ old('address', $user->address) }}" />
                                            <label for="address">Address</label>
                                            @error('address')
                                                <div class="text-danger small mt-1">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-floating form-floating-outline">
                                            <input class="form-control" type="text" id="city" name="city" placeholder="City" value="{{ old('city', $user->city) }}" />
                                            <label for="city">City</label>
                                            @error('city')
                                                <div class="text-danger small mt-1">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-floating form-floating-outline">
                                            <input type="text" class="form-control" id="country" name="country" placeholder="Country" value="{{ old('country', $user->country) }}" />
                                            <label for="country">Country</label>
                                            @error('country')
                                                <div class="text-danger small mt-1">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-floating form-floating-outline">
                                            <input type="date" class="form-control" id="date_of_birth" name="date_of_birth" value="{{ old('date_of_birth', $user->date_of_birth ? $user->date_of_birth->format('Y-m-d') : '') }}" />
                                            <label for="date_of_birth">Date of Birth</label>
                                            @error('date_of_birth')
                                                <div class="text-danger small mt-1">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-floating form-floating-outline">
                                            <textarea class="form-control" id="bio" name="bio" rows="3" placeholder="Bio">{{ old('bio', $user->bio) }}</textarea>
                                            <label for="bio">Bio</label>
                                            @error('bio')
                                                <div class="text-danger small mt-1">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                <div class="mt-4">
                                    <button type="submit" class="btn btn-primary me-3">
                                        <i class="ri-save-line me-1"></i>Save Changes
                                    </button>
                                    <button type="reset" class="btn btn-outline-secondary">
                                        <i class="ri-refresh-line me-1"></i>Reset
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Roles & Permissions Tab -->
        <div class="tab-pane fade" id="roles-permissions" role="tabpanel">
            <div class="row">
                <div class="col-md-6 mb-4">
                    <div class="card h-100">
                        <h5 class="card-header">
                            <i class="ri-user-settings-line me-2"></i>Assigned Roles
                        </h5>
                        <div class="card-body">
                            @if($user->roles->count() > 0)
                                <div class="d-flex flex-wrap gap-2">
                                    @foreach($user->roles as $role)
                                        <span class="badge bg-label-primary" style="font-size: 0.875rem; padding: 0.5rem 1rem;">
                                            <i class="ri-shield-user-line me-1"></i>{{ $role->name }}
                                        </span>
                                    @endforeach
                                </div>
                            @else
                                <p class="text-muted mb-0">No roles assigned</p>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="col-md-6 mb-4">
                    <div class="card h-100">
                        <h5 class="card-header">
                            <i class="ri-shield-check-line me-2"></i>Permissions Summary
                        </h5>
                        <div class="card-body">
                            <div class="d-flex align-items-center gap-3 mb-3">
                                <div>
                                    <h4 class="mb-0">{{ $stats['permissions_count'] }}</h4>
                                    <small class="text-muted">Total Permissions</small>
                                </div>
                                <div class="flex-grow-1">
                                    <div class="progress" style="height: 8px;">
                                        <div class="progress-bar" role="progressbar" style="width: 100%;" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                </div>
                            </div>
                            @if($allPermissions->count() > 0)
                                <div class="mt-3">
                                    <small class="text-muted d-block mb-2">All Permissions:</small>
                                    <div class="d-flex flex-wrap gap-1" style="max-height: 200px; overflow-y: auto;">
                                        @foreach($allPermissions as $permission)
                                            <span class="badge bg-label-info" style="font-size: 0.75rem;">
                                                {{ $permission->name }}
                                            </span>
                                        @endforeach
                                    </div>
                                </div>
                            @else
                                <p class="text-muted mb-0">No permissions assigned</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Bookings Tab -->
        <div class="tab-pane fade" id="bookings" role="tabpanel">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <h5 class="card-header d-flex justify-content-between align-items-center">
                            <span><i class="ri-calendar-check-line me-2"></i>Recent Bookings</span>
                            <a href="{{ route('admin.bookings.index') }}" class="btn btn-sm btn-primary">
                                <i class="ri-eye-line me-1"></i>View All
                            </a>
                        </h5>
                        <div class="card-body">
                            @if($stats['recent_bookings']->count() > 0)
                                <div class="table-responsive">
                                    <table class="table table-hover">
                                        <thead>
                                            <tr>
                                                <th>Reference</th>
                                                <th>Tour</th>
                                                <th>Date</th>
                                                <th>Travelers</th>
                                                <th>Amount</th>
                                                <th>Status</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($stats['recent_bookings'] as $booking)
                                                <tr>
                                                    <td>
                                                        <strong>{{ $booking->booking_reference }}</strong>
                                                    </td>
                                                    <td>
                                                        {{ $booking->tour ? $booking->tour->name : 'N/A' }}
                                                    </td>
                                                    <td>
                                                        {{ $booking->departure_date ? $booking->departure_date->format('M d, Y') : 'N/A' }}
                                                    </td>
                                                    <td>{{ $booking->travelers }}</td>
                                                    <td>
                                                        <strong>{{ number_format($booking->total_price, 2) }} {{ $booking->currency ?? 'USD' }}</strong>
                                                    </td>
                                                    <td>
                                                        @php
                                                            $statusColors = [
                                                                'pending_payment' => 'warning',
                                                                'confirmed' => 'success',
                                                                'cancelled' => 'danger',
                                                                'completed' => 'info'
                                                            ];
                                                            $color = $statusColors[$booking->status] ?? 'secondary';
                                                        @endphp
                                                        <span class="badge bg-label-{{ $color }}">
                                                            {{ ucfirst(str_replace('_', ' ', $booking->status)) }}
                                                        </span>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                <div class="text-center py-5">
                                    <i class="ri-calendar-line ri-48px text-muted mb-3"></i>
                                    <p class="text-muted">No bookings found</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Security Tab -->
        <div class="tab-pane fade" id="security" role="tabpanel">
            <div class="row">
                <div class="col-12">
                    <div class="card mb-4">
                        <h5 class="card-header">Change Password</h5>
                        <div class="card-body">
                            <form id="formAccountPassword" method="POST" action="{{ route('admin.profile.password') }}">
                                @csrf
                                @method('PUT')
                                <div class="row g-4">
                                    <div class="col-md-12">
                                        <div class="form-floating form-floating-outline">
                                            <input class="form-control" type="password" id="current_password" name="current_password" required />
                                            <label for="current_password">Current Password <span class="text-danger">*</span></label>
                                            @error('current_password')
                                                <div class="text-danger small mt-1">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-floating form-floating-outline">
                                            <input class="form-control" type="password" id="password" name="password" required />
                                            <label for="password">New Password <span class="text-danger">*</span></label>
                                            @error('password')
                                                <div class="text-danger small mt-1">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-floating form-floating-outline">
                                            <input class="form-control" type="password" id="password_confirmation" name="password_confirmation" required />
                                            <label for="password_confirmation">Confirm New Password <span class="text-danger">*</span></label>
                                        </div>
                                    </div>
                                </div>
                                <div class="mt-4">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="ri-lock-password-line me-1"></i>Update Password
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>

                    <div class="card">
                        <h5 class="card-header">Account Security</h5>
                        <div class="card-body">
                            <div class="row g-4">
                                <div class="col-md-6">
                                    <div class="d-flex align-items-center justify-content-between p-3 border rounded">
                                        <div>
                                            <h6 class="mb-1">Email Verification</h6>
                                            <small class="text-muted">
                                                @if($user->email_verified_at)
                                                    Verified on {{ $user->email_verified_at->format('M d, Y') }}
                                                @else
                                                    Email not verified
                                                @endif
                                            </small>
                                        </div>
                                        <div>
                                            @if($user->email_verified_at)
                                                <span class="badge bg-label-success">
                                                    <i class="ri-check-line me-1"></i>Verified
                                                </span>
                                            @else
                                                <span class="badge bg-label-warning">
                                                    <i class="ri-close-line me-1"></i>Unverified
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="d-flex align-items-center justify-content-between p-3 border rounded">
                                        <div>
                                            <h6 class="mb-1">Account Created</h6>
                                            <small class="text-muted">{{ $user->created_at->format('M d, Y') }}</small>
                                        </div>
                                        <div>
                                            <i class="ri-calendar-line ri-24px text-muted"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Avatar upload
    document.getElementById('upload')?.addEventListener('change', function() {
        const form = document.getElementById('avatarForm');
        const formData = new FormData(form);
        
        fetch(form.action, {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Update avatar image
                const avatarImg = document.getElementById('uploadedAvatar');
                if (avatarImg && data.avatar_url) {
                    if (avatarImg.tagName === 'IMG') {
                        avatarImg.src = data.avatar_url;
                    } else {
                        // Replace div with img
                        const newImg = document.createElement('img');
                        newImg.src = data.avatar_url;
                        newImg.alt = 'user-avatar';
                        newImg.className = 'd-block rounded';
                        newImg.id = 'uploadedAvatar';
                        newImg.style.cssText = 'width: 120px; height: 120px; object-fit: cover;';
                        avatarImg.parentNode.replaceChild(newImg, avatarImg);
                    }
                }
                // Show success message
                if (typeof toastr !== 'undefined') {
                    toastr.success(data.message || 'Avatar updated successfully');
                } else {
                    alert(data.message || 'Avatar updated successfully');
                }
            }
        })
        .catch(error => {
            console.error('Error:', error);
            if (typeof toastr !== 'undefined') {
                toastr.error('An error occurred while uploading avatar');
            } else {
                alert('An error occurred while uploading avatar');
            }
        });
    });

    // Account image reset
    document.querySelector('.account-image-reset')?.addEventListener('click', function() {
        document.getElementById('upload').value = '';
        // Optionally reset to default avatar
    });

    // Form validation and AJAX submission
    document.getElementById('formAccountSettings')?.addEventListener('submit', function(e) {
        e.preventDefault();
        const form = this;
        const formData = new FormData(form);
        
        fetch(form.action, {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content')
            }
        })
        .then(response => {
            if (!response.ok) {
                return response.json().then(data => {
                    throw new Error(data.message || 'Failed to update profile');
                });
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                // Show success message
                if (typeof toastr !== 'undefined') {
                    toastr.success(data.message || 'Profile updated successfully');
                } else {
                    alert(data.message || 'Profile updated successfully');
                }
                // Optionally reload page after a short delay to show updated data
                setTimeout(() => {
                    window.location.reload();
                }, 1500);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            if (typeof toastr !== 'undefined') {
                toastr.error(error.message || 'An error occurred while updating your profile');
            } else {
                alert(error.message || 'An error occurred while updating your profile');
            }
        });
    });

    // Password form validation and AJAX submission
    document.getElementById('formAccountPassword')?.addEventListener('submit', function(e) {
        e.preventDefault();
        const form = this;
        const password = document.getElementById('password').value;
        const passwordConfirmation = document.getElementById('password_confirmation').value;
        
        if (password !== passwordConfirmation) {
            if (typeof toastr !== 'undefined') {
                toastr.error('New password and confirmation password do not match');
            } else {
                alert('New password and confirmation password do not match');
            }
            return false;
        }
        
        if (password.length < 8) {
            if (typeof toastr !== 'undefined') {
                toastr.error('Password must be at least 8 characters long');
            } else {
                alert('Password must be at least 8 characters long');
            }
            return false;
        }
        
        const formData = new FormData(form);
        
        fetch(form.action, {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content')
            }
        })
        .then(response => {
            if (!response.ok) {
                return response.json().then(data => {
                    throw new Error(data.message || 'Failed to update password');
                });
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                // Show success message
                if (typeof toastr !== 'undefined') {
                    toastr.success(data.message || 'Password updated successfully');
                } else {
                    alert(data.message || 'Password updated successfully');
                }
                // Reset form
                form.reset();
            }
        })
        .catch(error => {
            console.error('Error:', error);
            if (typeof toastr !== 'undefined') {
                toastr.error(error.message || 'An error occurred while updating password');
            } else {
                alert(error.message || 'An error occurred while updating password');
            }
        });
    });
</script>
@endpush
