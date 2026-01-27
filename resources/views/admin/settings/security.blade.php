@extends('admin.layouts.app')

@section('title', 'Security Settings')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="row mb-4">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h4 class="mb-0">
                        <i class="ri-shield-line me-2"></i>Security Settings
                    </h4>
                </div>
                <div class="card-body">
                    <form id="securitySettingsForm" method="POST" action="{{ route('admin.settings.security.update') }}">
                        @csrf
                        @method('PUT')
                        
                        <h5 class="mb-3">Password Requirements</h5>
                        <div class="row g-3">
                            <div class="col-md-12">
                                <label class="form-label">Minimum Length <span class="text-danger">*</span></label>
                                <input type="number" name="password_min_length" class="form-control" 
                                       value="{{ old('password_min_length', $settings['password_min_length'] ?? 8) }}" 
                                       min="6" max="32" required>
                            </div>
                            
                            <div class="col-12">
                                <div class="form-check form-switch mb-2">
                                    <input class="form-check-input" type="checkbox" name="password_require_uppercase" id="requireUppercase" 
                                           {{ old('password_require_uppercase', $settings['password_require_uppercase'] ?? true) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="requireUppercase">
                                        Require Uppercase Letters
                                    </label>
                                </div>
                                
                                <div class="form-check form-switch mb-2">
                                    <input class="form-check-input" type="checkbox" name="password_require_lowercase" id="requireLowercase" 
                                           {{ old('password_require_lowercase', $settings['password_require_lowercase'] ?? true) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="requireLowercase">
                                        Require Lowercase Letters
                                    </label>
                                </div>
                                
                                <div class="form-check form-switch mb-2">
                                    <input class="form-check-input" type="checkbox" name="password_require_numbers" id="requireNumbers" 
                                           {{ old('password_require_numbers', $settings['password_require_numbers'] ?? true) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="requireNumbers">
                                        Require Numbers
                                    </label>
                                </div>
                                
                                <div class="form-check form-switch mb-2">
                                    <input class="form-check-input" type="checkbox" name="password_require_symbols" id="requireSymbols" 
                                           {{ old('password_require_symbols', $settings['password_require_symbols'] ?? false) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="requireSymbols">
                                        Require Symbols
                                    </label>
                                </div>
                            </div>
                        </div>
                        
                        <h5 class="mb-3 mt-4">Session Settings</h5>
                        <div class="row g-3">
                            <div class="col-md-12">
                                <label class="form-label">Session Timeout (minutes) <span class="text-danger">*</span></label>
                                <input type="number" name="session_timeout" class="form-control" 
                                       value="{{ old('session_timeout', $settings['session_timeout'] ?? 120) }}" 
                                       min="5" max="1440" required>
                            </div>
                        </div>
                        
                        <h5 class="mb-3 mt-4">Login Security</h5>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">Login Attempts Limit <span class="text-danger">*</span></label>
                                <input type="number" name="login_attempts_limit" class="form-control" 
                                       value="{{ old('login_attempts_limit', $settings['login_attempts_limit'] ?? 5) }}" 
                                       min="3" max="10" required>
                            </div>
                            
                            <div class="col-md-6">
                                <label class="form-label">Lockout Duration (minutes) <span class="text-danger">*</span></label>
                                <input type="number" name="lockout_duration" class="form-control" 
                                       value="{{ old('lockout_duration', $settings['lockout_duration'] ?? 15) }}" 
                                       min="1" max="60" required>
                            </div>
                            
                            <div class="col-12">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" name="two_factor_enabled" id="twoFactorEnabled" 
                                           {{ old('two_factor_enabled', $settings['two_factor_enabled'] ?? false) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="twoFactorEnabled">
                                        Enable Two-Factor Authentication
                                    </label>
                                </div>
                            </div>
                        </div>
                        
                        <div class="mt-4">
                            <button type="submit" class="btn btn-primary">
                                <i class="ri-save-line me-1"></i>Save Settings
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h4 class="mb-0">
                        <i class="ri-lock-password-line me-2"></i>Change Password
                    </h4>
                </div>
                <div class="card-body">
                    <form id="changePasswordForm" method="POST" action="{{ route('admin.settings.security.change-password') }}">
                        @csrf
                        
                        <div class="mb-3">
                            <label class="form-label">Current Password <span class="text-danger">*</span></label>
                            <input type="password" name="current_password" class="form-control" required>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">New Password <span class="text-danger">*</span></label>
                            <input type="password" name="new_password" class="form-control" required minlength="8">
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Confirm New Password <span class="text-danger">*</span></label>
                            <input type="password" name="new_password_confirmation" class="form-control" required minlength="8">
                        </div>
                        
                        <div class="mt-4">
                            <button type="submit" class="btn btn-primary">
                                <i class="ri-lock-password-line me-1"></i>Change Password
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection


