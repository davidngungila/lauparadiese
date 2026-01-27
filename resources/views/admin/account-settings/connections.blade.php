@extends('admin.layouts.app')

@section('title', 'Account Settings - Connections')
@section('description', 'Manage your social media connections')

@section('content')
@php
    $socialLinks = $user->social_links ?? [];
@endphp
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
                    <a class="nav-link" href="{{ route('admin.account-settings.notifications') }}">
                        <i class="icon-base ri ri-notification-4-line icon-sm me-1_5"></i>Notifications
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link active" href="{{ route('admin.account-settings.connections') }}">
                        <i class="icon-base ri ri-link-m icon-sm me-1_5"></i>Connections
                    </a>
                </li>
            </ul>
        </div>
        
        <div class="card mb-6">
            <h5 class="card-header">Social Connections</h5>
            <div class="card-body">
                <form id="formConnectionsSettings" method="POST" action="{{ route('admin.account-settings.connections.update') }}">
                    @csrf
                    <div class="row g-4">
                        <div class="col-md-6">
                            <div class="form-floating form-floating-outline">
                                <input type="url" class="form-control" id="facebook" name="facebook" placeholder="Facebook URL" value="{{ old('facebook', $socialLinks['facebook'] ?? '') }}" />
                                <label for="facebook">
                                    <i class="icon-base ri ri-facebook-fill text-primary me-2"></i>Facebook
                                </label>
                            </div>
                            @error('facebook')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <div class="form-floating form-floating-outline">
                                <input type="url" class="form-control" id="twitter" name="twitter" placeholder="Twitter URL" value="{{ old('twitter', $socialLinks['twitter'] ?? '') }}" />
                                <label for="twitter">
                                    <i class="icon-base ri ri-twitter-fill text-info me-2"></i>Twitter
                                </label>
                            </div>
                            @error('twitter')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <div class="form-floating form-floating-outline">
                                <input type="url" class="form-control" id="linkedin" name="linkedin" placeholder="LinkedIn URL" value="{{ old('linkedin', $socialLinks['linkedin'] ?? '') }}" />
                                <label for="linkedin">
                                    <i class="icon-base ri ri-linkedin-fill text-primary me-2"></i>LinkedIn
                                </label>
                            </div>
                            @error('linkedin')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <div class="form-floating form-floating-outline">
                                <input type="url" class="form-control" id="instagram" name="instagram" placeholder="Instagram URL" value="{{ old('instagram', $socialLinks['instagram'] ?? '') }}" />
                                <label for="instagram">
                                    <i class="icon-base ri ri-instagram-fill text-danger me-2"></i>Instagram
                                </label>
                            </div>
                            @error('instagram')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                        </div>
                    </div>
                    <div class="mt-6">
                        <button type="submit" class="btn btn-primary">Save changes</button>
                    </div>
                </form>
            </div>
        </div>
        
        <!-- Connected Accounts -->
        <div class="card">
            <h5 class="card-header">Connected Accounts</h5>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-borderless">
                        <thead>
                            <tr>
                                <th>Provider</th>
                                <th>Status</th>
                                <th>Connected On</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if(!empty($socialLinks['facebook']))
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <i class="icon-base ri ri-facebook-fill text-primary me-2"></i>
                                        <span>Facebook</span>
                                    </div>
                                </td>
                                <td><span class="badge bg-label-success">Connected</span></td>
                                <td>{{ now()->format('M d, Y') }}</td>
                                <td>
                                    <a href="{{ route('admin.account-settings.connections.disconnect', 'facebook') }}" class="btn btn-sm btn-outline-danger" onclick="return confirm('Are you sure you want to disconnect Facebook?')">
                                        <i class="ri-unlink-line"></i> Disconnect
                                    </a>
                                </td>
                            </tr>
                            @endif
                            @if(!empty($socialLinks['twitter']))
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <i class="icon-base ri ri-twitter-fill text-info me-2"></i>
                                        <span>Twitter</span>
                                    </div>
                                </td>
                                <td><span class="badge bg-label-success">Connected</span></td>
                                <td>{{ now()->format('M d, Y') }}</td>
                                <td>
                                    <a href="{{ route('admin.account-settings.connections.disconnect', 'twitter') }}" class="btn btn-sm btn-outline-danger" onclick="return confirm('Are you sure you want to disconnect Twitter?')">
                                        <i class="ri-unlink-line"></i> Disconnect
                                    </a>
                                </td>
                            </tr>
                            @endif
                            @if(!empty($socialLinks['linkedin']))
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <i class="icon-base ri ri-linkedin-fill text-primary me-2"></i>
                                        <span>LinkedIn</span>
                                    </div>
                                </td>
                                <td><span class="badge bg-label-success">Connected</span></td>
                                <td>{{ now()->format('M d, Y') }}</td>
                                <td>
                                    <a href="{{ route('admin.account-settings.connections.disconnect', 'linkedin') }}" class="btn btn-sm btn-outline-danger" onclick="return confirm('Are you sure you want to disconnect LinkedIn?')">
                                        <i class="ri-unlink-line"></i> Disconnect
                                    </a>
                                </td>
                            </tr>
                            @endif
                            @if(!empty($socialLinks['instagram']))
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <i class="icon-base ri ri-instagram-fill text-danger me-2"></i>
                                        <span>Instagram</span>
                                    </div>
                                </td>
                                <td><span class="badge bg-label-success">Connected</span></td>
                                <td>{{ now()->format('M d, Y') }}</td>
                                <td>
                                    <a href="{{ route('admin.account-settings.connections.disconnect', 'instagram') }}" class="btn btn-sm btn-outline-danger" onclick="return confirm('Are you sure you want to disconnect Instagram?')">
                                        <i class="ri-unlink-line"></i> Disconnect
                                    </a>
                                </td>
                            </tr>
                            @endif
                            @if(empty($socialLinks['facebook']) && empty($socialLinks['twitter']) && empty($socialLinks['linkedin']) && empty($socialLinks['instagram']))
                            <tr>
                                <td colspan="4" class="text-center text-muted py-4">
                                    <i class="icon-base ri ri-link-m icon-48px mb-2 d-block"></i>
                                    <p class="mb-0">No social accounts connected</p>
                                </td>
                            </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const formConnectionsSettings = document.getElementById('formConnectionsSettings');
    if (formConnectionsSettings) {
        formConnectionsSettings.addEventListener('submit', function(e) {
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
                        showToast(data.message || 'Social connections updated successfully!', 'success');
                    } else {
                        alert(data.message || 'Social connections updated successfully!');
                    }
                    setTimeout(() => window.location.reload(), 1000);
                } else {
                    throw new Error(data.message || 'Failed to update social connections');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                if (typeof showToast === 'function') {
                    showToast(error.message || 'Failed to update social connections', 'error');
                } else {
                    alert('Error: ' + (error.message || 'Failed to update social connections'));
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

