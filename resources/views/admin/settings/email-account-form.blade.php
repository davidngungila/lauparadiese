@extends('admin.layouts.app')

@section('title', isset($account) ? 'Edit Email Account' : 'Add Email Account')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header border-bottom">
                <h5 class="card-title mb-0">{{ isset($account) ? 'Edit Email Account' : 'Add Email Account' }}</h5>
            </div>
            
            <div class="card-body">
                <form action="{{ isset($account) ? route('admin.settings.email-accounts.update', $account) : route('admin.settings.email-accounts.store') }}" method="POST">
                    @csrf
                    @if(isset($account))
                        @method('PUT')
                    @endif
                    
                    <div class="row">
                        <div class="col-md-6 mb-4">
                            <label class="form-label">Account Name <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" 
                                   value="{{ old('name', $account->name ?? '') }}" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-6 mb-4">
                            <label class="form-label">Email Address <span class="text-danger">*</span></label>
                            <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" 
                                   value="{{ old('email', $account->email ?? '') }}" required>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-4">
                            <label class="form-label">Protocol <span class="text-danger">*</span></label>
                            <select name="protocol" id="protocol" class="form-select @error('protocol') is-invalid @enderror" required>
                                <option value="imap" {{ old('protocol', $account->protocol ?? 'imap') == 'imap' ? 'selected' : '' }}>IMAP</option>
                                <option value="pop3" {{ old('protocol', $account->protocol ?? '') == 'pop3' ? 'selected' : '' }}>POP3</option>
                            </select>
                            @error('protocol')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-6 mb-4">
                            <label class="form-label">Username <span class="text-danger">*</span></label>
                            <input type="text" name="username" class="form-control @error('username') is-invalid @enderror" 
                                   value="{{ old('username', $account->username ?? '') }}" required>
                            @error('username')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-4">
                            <label class="form-label">Password <span class="text-danger">*</span></label>
                            <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" 
                                   {{ isset($account) ? '' : 'required' }} placeholder="{{ isset($account) ? 'Leave blank to keep current password' : '' }}">
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-6 mb-4">
                            <label class="form-label">Check Interval (minutes)</label>
                            <input type="number" name="check_interval" class="form-control @error('check_interval') is-invalid @enderror" 
                                   value="{{ old('check_interval', $account->check_interval ?? 5) }}" min="1">
                            @error('check_interval')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <hr class="my-4">
                    <h6 class="mb-3">IMAP Settings</h6>
                    <div class="row" id="imap-settings">
                        <div class="col-md-4 mb-4">
                            <label class="form-label">IMAP Host</label>
                            <input type="text" name="imap_host" class="form-control @error('imap_host') is-invalid @enderror" 
                                   value="{{ old('imap_host', $account->imap_host ?? 'imap.gmail.com') }}" placeholder="imap.gmail.com">
                            @error('imap_host')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-4 mb-4">
                            <label class="form-label">IMAP Port</label>
                            <input type="number" name="imap_port" class="form-control @error('imap_port') is-invalid @enderror" 
                                   value="{{ old('imap_port', $account->imap_port ?? 993) }}">
                            @error('imap_port')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-4 mb-4">
                            <label class="form-label">IMAP Encryption</label>
                            <select name="imap_encryption" class="form-select @error('imap_encryption') is-invalid @enderror">
                                <option value="ssl" {{ old('imap_encryption', $account->imap_encryption ?? 'ssl') == 'ssl' ? 'selected' : '' }}>SSL</option>
                                <option value="tls" {{ old('imap_encryption', $account->imap_encryption ?? '') == 'tls' ? 'selected' : '' }}>TLS</option>
                                <option value="none" {{ old('imap_encryption', $account->imap_encryption ?? '') == 'none' ? 'selected' : '' }}>None</option>
                            </select>
                            @error('imap_encryption')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <hr class="my-4">
                    <h6 class="mb-3">SMTP Settings</h6>
                    <div class="row">
                        <div class="col-md-4 mb-4">
                            <label class="form-label">SMTP Host <span class="text-danger">*</span></label>
                            <input type="text" name="smtp_host" class="form-control @error('smtp_host') is-invalid @enderror" 
                                   value="{{ old('smtp_host', $account->smtp_host ?? 'smtp.gmail.com') }}" required placeholder="smtp.gmail.com">
                            @error('smtp_host')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-4 mb-4">
                            <label class="form-label">SMTP Port <span class="text-danger">*</span></label>
                            <input type="number" name="smtp_port" class="form-control @error('smtp_port') is-invalid @enderror" 
                                   value="{{ old('smtp_port', $account->smtp_port ?? 587) }}" required>
                            @error('smtp_port')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-4 mb-4">
                            <label class="form-label">SMTP Encryption <span class="text-danger">*</span></label>
                            <select name="smtp_encryption" class="form-select @error('smtp_encryption') is-invalid @enderror" required>
                                <option value="ssl" {{ old('smtp_encryption', $account->smtp_encryption ?? 'tls') == 'ssl' ? 'selected' : '' }}>SSL</option>
                                <option value="tls" {{ old('smtp_encryption', $account->smtp_encryption ?? 'tls') == 'tls' ? 'selected' : '' }}>TLS</option>
                                <option value="none" {{ old('smtp_encryption', $account->smtp_encryption ?? '') == 'none' ? 'selected' : '' }}>None</option>
                            </select>
                            @error('smtp_encryption')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <hr class="my-4">
                    <div class="row">
                        <div class="col-md-6 mb-4">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" name="is_active" id="is_active" 
                                       {{ old('is_active', $account->is_active ?? true) ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_active">Active</label>
                            </div>
                        </div>
                        
                        <div class="col-md-6 mb-4">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" name="is_default" id="is_default" 
                                       {{ old('is_default', $account->is_default ?? false) ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_default">Set as Default</label>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-4">
                        <label class="form-label">Notes</label>
                        <textarea name="notes" class="form-control @error('notes') is-invalid @enderror" rows="3">{{ old('notes', $account->notes ?? '') }}</textarea>
                        @error('notes')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="d-flex justify-content-between">
                        <a href="{{ route('admin.settings.email-accounts.index') }}" class="btn btn-outline-secondary">Cancel</a>
                        <button type="submit" class="btn btn-primary">
                            <i class="ri-save-line me-1"></i>{{ isset($account) ? 'Update' : 'Create' }} Account
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Show/hide IMAP settings based on protocol
    document.getElementById('protocol').addEventListener('change', function() {
        const imapSettings = document.getElementById('imap-settings');
        if (this.value === 'imap') {
            imapSettings.style.display = 'block';
        } else {
            imapSettings.style.display = 'none';
        }
    });
    
    // Trigger on page load
    document.getElementById('protocol').dispatchEvent(new Event('change'));
</script>
@endpush
@endsection




