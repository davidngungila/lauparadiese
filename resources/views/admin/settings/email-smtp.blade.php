@extends('admin.layouts.app')

@section('title', 'Email SMTP Settings - Advanced Management')

@push('styles')
<style>
    .status-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.375rem 0.75rem;
        border-radius: 0.375rem;
        font-size: 0.875rem;
        font-weight: 500;
    }
    .connection-indicator {
        width: 10px;
        height: 10px;
        border-radius: 50%;
        display: inline-block;
        animation: pulse 2s infinite;
    }
    .connection-indicator.active {
        background-color: #71dd37;
    }
    .connection-indicator.inactive {
        background-color: #ff3e1d;
    }
    .connection-indicator.unknown {
        background-color: #a1acb8;
    }
    @keyframes pulse {
        0%, 100% { opacity: 1; }
        50% { opacity: 0.5; }
    }
    .test-result {
        margin-top: 0.5rem;
        padding: 0.75rem;
        border-radius: 0.25rem;
        font-size: 0.875rem;
    }
    .test-result.success {
        background-color: #d4edda;
        color: #155724;
        border: 1px solid #c3e6cb;
    }
    .test-result.error {
        background-color: #f8d7da;
        color: #721c24;
        border: 1px solid #f5c6cb;
    }
    .nav-tabs .nav-link {
        color: #566a7f;
        border: none;
        border-bottom: 2px solid transparent;
        padding: 0.75rem 1.5rem;
    }
    .nav-tabs .nav-link:hover {
        border-color: transparent;
        border-bottom-color: #d9dee3;
    }
    .nav-tabs .nav-link.active {
        color: #3ea572;
        border-bottom-color: #3ea572;
        background: transparent;
    }
    .tab-content {
        padding-top: 1.5rem;
    }
    .setting-group {
        background: #ffffff;
        border: 1px solid #e7e9ec;
        border-radius: 0.5rem;
        padding: 1.5rem;
        margin-bottom: 1.5rem;
    }
    .setting-group-title {
        font-size: 1.125rem;
        font-weight: 600;
        color: #566a7f;
        margin-bottom: 1.25rem;
        padding-bottom: 0.75rem;
        border-bottom: 2px solid #e7e9ec;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    .setting-group-title i {
        color: #3ea572;
    }
    .info-card {
        background: linear-gradient(135deg, #f8f9fa 0%, #ffffff 100%);
        border-left: 4px solid #3ea572;
        padding: 1.25rem;
        margin-bottom: 1.5rem;
        border-radius: 0.5rem;
    }
    .info-card i {
        color: #3ea572;
        font-size: 1.5rem;
        margin-right: 0.75rem;
    }
    .help-text {
        font-size: 0.8125rem;
        color: #6c757d;
        margin-top: 0.5rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    .help-text i {
        color: #3ea572;
    }
    .spin {
        animation: spin 1s linear infinite;
        display: inline-block;
    }
    @keyframes spin {
        from { transform: rotate(0deg); }
        to { transform: rotate(360deg); }
    }
    .mailer-badge {
        display: inline-block;
        padding: 0.25rem 0.75rem;
        border-radius: 0.25rem;
        font-size: 0.75rem;
        font-weight: 600;
        text-transform: uppercase;
    }
    .mailer-badge.smtp { background: #e3f2fd; color: #1976d2; }
    .mailer-badge.mailgun { background: #fff3e0; color: #f57c00; }
    .mailer-badge.ses { background: #f3e5f5; color: #7b1fa2; }
    .mailer-badge.sendmail { background: #e8f5e9; color: #388e3c; }
</style>
@endpush

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <!-- Email Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm" style="background: linear-gradient(135deg, #3ea572 0%, #2d8654 100%);">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="d-flex align-items-center gap-3">
                            <div class="bg-white rounded p-3" style="width: 80px; height: 80px; display: flex; align-items: center; justify-content: center;">
                                <i class="ri-mail-line" style="font-size: 3rem; color: #3ea572;"></i>
                            </div>
                            <div class="text-white">
                                <h3 class="mb-1 fw-bold">Email SMTP Settings</h3>
                                <p class="mb-0 opacity-90">Advanced email configuration and management</p>
                                <small class="opacity-75">Configure SMTP, Mailgun, SES, and other email providers</small>
                            </div>
                        </div>
                        <div class="d-flex align-items-center gap-3">
                            <div class="status-badge bg-white bg-opacity-20 text-white">
                                <span class="connection-indicator unknown" id="connectionStatus"></span>
                                <span id="connectionStatusText">Not Tested</span>
                            </div>
                            <div class="status-badge bg-white bg-opacity-20 text-white">
                                <span class="mailer-badge {{ $settings['mailer'] ?? 'smtp' }}">{{ strtoupper($settings['mailer'] ?? 'smtp') }}</span>
                            </div>
                            <button type="button" class="btn btn-light" id="testConnectionBtn">
                                <i class="ri-flashlight-line me-1"></i>Test Connection
                    </button>
                </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabs Navigation -->
    <div class="card">
        <div class="card-header border-bottom">
            <ul class="nav nav-tabs card-header-tabs" role="tablist">
                <li class="nav-item">
                    <button type="button" class="nav-link active" data-bs-toggle="tab" data-bs-target="#configuration-tab" role="tab" aria-selected="true">
                        <i class="ri-settings-3-line me-1"></i>Configuration
                    </button>
                </li>
                <li class="nav-item">
                    <button type="button" class="nav-link" data-bs-toggle="tab" data-bs-target="#advanced-tab" role="tab" aria-selected="false">
                        <i class="ri-tools-line me-1"></i>Advanced Settings
                    </button>
                </li>
                <li class="nav-item">
                    <button type="button" class="nav-link" data-bs-toggle="tab" data-bs-target="#testing-tab" role="tab" aria-selected="false">
                        <i class="ri-test-tube-line me-1"></i>Testing
                    </button>
                </li>
                <li class="nav-item">
                    <button type="button" class="nav-link" data-bs-toggle="tab" data-bs-target="#logs-tab" role="tab" aria-selected="false">
                        <i class="ri-file-list-3-line me-1"></i>Email Logs
                    </button>
                </li>
                <li class="nav-item">
                    <button type="button" class="nav-link" data-bs-toggle="tab" data-bs-target="#templates-tab" role="tab" aria-selected="false">
                        <i class="ri-file-text-line me-1"></i>Templates
                    </button>
                </li>
                <li class="nav-item">
                    <button type="button" class="nav-link" data-bs-toggle="tab" data-bs-target="#info-tab" role="tab" aria-selected="false">
                        <i class="ri-information-line me-1"></i>Information
                    </button>
                </li>
            </ul>
        </div>

                <div class="card-body">
            <div class="tab-content">
                <!-- Configuration Tab -->
                <div class="tab-pane fade show active" id="configuration-tab" role="tabpanel">
                    <form id="emailConfigForm">
                        @csrf
                        <input type="hidden" name="form_type" value="configuration">
                        
                        <div class="info-card">
                            <div class="d-flex align-items-start">
                                <i class="ri-information-line"></i>
                                <div>
                                    <strong>SMTP Configuration</strong>
                                    <p class="mb-0">Configure your email server settings. These settings will be used for all outgoing emails.</p>
                                </div>
                            </div>
                        </div>

                        <div class="setting-group">
                            <div class="setting-group-title">
                                <i class="ri-mail-settings-line"></i>
                                Mailer Settings
                            </div>
                        <div class="row g-3">
                            <div class="col-md-6">
                                    <label class="form-label">Mailer Driver <span class="text-danger">*</span></label>
                                    <select name="mailer" class="form-select" required onchange="updateMailerSettings()">
                                        <option value="smtp" {{ ($settings['mailer'] ?? 'smtp') == 'smtp' ? 'selected' : '' }}>SMTP</option>
                                        <option value="sendmail" {{ ($settings['mailer'] ?? '') == 'sendmail' ? 'selected' : '' }}>Sendmail</option>
                                        <option value="mailgun" {{ ($settings['mailer'] ?? '') == 'mailgun' ? 'selected' : '' }}>Mailgun</option>
                                        <option value="ses" {{ ($settings['mailer'] ?? '') == 'ses' ? 'selected' : '' }}>Amazon SES</option>
                                        <option value="postmark" {{ ($settings['mailer'] ?? '') == 'postmark' ? 'selected' : '' }}>Postmark</option>
                                        <option value="log" {{ ($settings['mailer'] ?? '') == 'log' ? 'selected' : '' }}>Log (Testing)</option>
                                </select>
                                    <div class="help-text">
                                        <i class="ri-question-line"></i>
                                        Select your email service provider
                                    </div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">SMTP Host <span class="text-danger">*</span></label>
                                <input type="text" name="host" class="form-control" 
                                           value="{{ $settings['host'] ?? '' }}" required 
                                           placeholder="smtp.gmail.com">
                                    <div class="help-text">
                                        <i class="ri-question-line"></i>
                                        SMTP server hostname
                                    </div>
                            </div>
                                <div class="col-md-4">
                                <label class="form-label">SMTP Port <span class="text-danger">*</span></label>
                                <input type="number" name="port" class="form-control" 
                                           value="{{ $settings['port'] ?? 587 }}" required 
                                           min="1" max="65535" placeholder="587">
                                    <div class="help-text">
                                        <i class="ri-question-line"></i>
                                        Common ports: 587 (TLS), 465 (SSL), 25
                                    </div>
                            </div>
                                <div class="col-md-4">
                                <label class="form-label">Encryption <span class="text-danger">*</span></label>
                                <select name="encryption" class="form-select" required>
                                        <option value="tls" {{ ($settings['encryption'] ?? 'tls') == 'tls' ? 'selected' : '' }}>TLS</option>
                                        <option value="ssl" {{ ($settings['encryption'] ?? '') == 'ssl' ? 'selected' : '' }}>SSL</option>
                                        <option value="none" {{ ($settings['encryption'] ?? '') == 'none' ? 'selected' : '' }}>None</option>
                                    </select>
                                    <div class="help-text">
                                        <i class="ri-question-line"></i>
                                        Encryption method for secure connection
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Auth Mode</label>
                                    <select name="auth_mode" class="form-select">
                                        <option value="login" {{ ($settings['auth_mode'] ?? 'login') == 'login' ? 'selected' : '' }}>Login</option>
                                        <option value="plain" {{ ($settings['auth_mode'] ?? '') == 'plain' ? 'selected' : '' }}>Plain</option>
                                        <option value="cram-md5" {{ ($settings['auth_mode'] ?? '') == 'cram-md5' ? 'selected' : '' }}>CRAM-MD5</option>
                                </select>
                                </div>
                            </div>
                            </div>
                            
                        <div class="setting-group">
                            <div class="setting-group-title">
                                <i class="ri-user-settings-line"></i>
                                Authentication
                            </div>
                            <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">Username <span class="text-danger">*</span></label>
                                <input type="text" name="username" class="form-control" 
                                           value="{{ $settings['username'] ?? '' }}" required 
                                           placeholder="your-email@example.com">
                                    <div class="help-text">
                                        <i class="ri-question-line"></i>
                                        Your email address or SMTP username
                                    </div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Password</label>
                                <input type="password" name="password" class="form-control" 
                                       placeholder="Leave blank to keep current password">
                                    <div class="help-text">
                                        <i class="ri-question-line"></i>
                                        Your email password or app-specific password
                                    </div>
                                </div>
                            </div>
                            </div>
                            
                        <div class="setting-group">
                            <div class="setting-group-title">
                                <i class="ri-send-plane-line"></i>
                                From Address
                            </div>
                            <div class="row g-3">
                            <div class="col-md-6">
                                    <label class="form-label">From Email Address <span class="text-danger">*</span></label>
                                <input type="email" name="from_address" class="form-control" 
                                           value="{{ $settings['from_address'] ?? '' }}" required 
                                           placeholder="noreply@example.com">
                                    <div class="help-text">
                                        <i class="ri-question-line"></i>
                                        Default sender email address
                                    </div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">From Name <span class="text-danger">*</span></label>
                                <input type="text" name="from_name" class="form-control" 
                                           value="{{ $settings['from_name'] ?? config('app.name') }}" required 
                                           placeholder="Your Company Name">
                                    <div class="help-text">
                                        <i class="ri-question-line"></i>
                                        Display name for sent emails
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="d-flex justify-content-end gap-2 mt-4">
                            <button type="reset" class="btn btn-label-secondary">Reset</button>
                            <button type="submit" class="btn btn-primary">
                                <i class="ri-save-line me-1"></i>Save Configuration
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Advanced Settings Tab -->
                <div class="tab-pane fade" id="advanced-tab" role="tabpanel">
                    <form id="advancedConfigForm">
                        @csrf
                        <input type="hidden" name="form_type" value="advanced">
                        
                        <div class="info-card">
                            <div class="d-flex align-items-start">
                                <i class="ri-information-line"></i>
                                <div>
                                    <strong>Advanced Email Settings</strong>
                                    <p class="mb-0">Configure advanced options for email delivery, queuing, and rate limiting.</p>
                                </div>
                            </div>
                        </div>

                        <div class="setting-group">
                            <div class="setting-group-title">
                                <i class="ri-time-line"></i>
                                Connection Settings
                            </div>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label">Connection Timeout (seconds)</label>
                                    <input type="number" name="timeout" class="form-control" 
                                           value="{{ $settings['timeout'] ?? 30 }}" 
                                           min="1" max="300" placeholder="30">
                                    <div class="help-text">
                                        <i class="ri-question-line"></i>
                                        Maximum time to wait for SMTP connection (1-300 seconds)
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Max Retry Attempts</label>
                                    <input type="number" name="max_retries" class="form-control" 
                                           value="{{ $settings['max_retries'] ?? 3 }}" 
                                           min="0" max="10" placeholder="3">
                                    <div class="help-text">
                                        <i class="ri-question-line"></i>
                                        Number of retry attempts for failed emails (0-10)
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" name="verify_peer" id="verifyPeer" 
                                               {{ ($settings['verify_peer'] ?? true) ? 'checked' : '' }} value="1">
                                        <label class="form-check-label" for="verifyPeer">
                                            <strong>Verify SSL Certificate</strong>
                                            <small class="d-block text-muted">Verify peer SSL certificate (recommended for production)</small>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="setting-group">
                            <div class="setting-group-title">
                                <i class="ri-stack-line"></i>
                                Queue Settings
                            </div>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <div class="form-check form-switch form-switch-lg">
                                        <input class="form-check-input" type="checkbox" name="queue_enabled" id="queueEnabled" 
                                               {{ ($settings['queue_enabled'] ?? false) ? 'checked' : '' }} value="1" onchange="toggleQueueSettings()">
                                        <label class="form-check-label" for="queueEnabled">
                                            <strong>Enable Email Queue</strong>
                                            <small class="d-block text-muted">Queue emails for background processing</small>
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-6" id="queueConnectionGroup" style="display: {{ ($settings['queue_enabled'] ?? false) ? 'block' : 'none' }};">
                                    <label class="form-label">Queue Connection</label>
                                    <select name="queue_connection" class="form-select">
                                        <option value="database" {{ ($settings['queue_connection'] ?? 'database') == 'database' ? 'selected' : '' }}>Database</option>
                                        <option value="redis" {{ ($settings['queue_connection'] ?? '') == 'redis' ? 'selected' : '' }}>Redis</option>
                                        <option value="beanstalkd" {{ ($settings['queue_connection'] ?? '') == 'beanstalkd' ? 'selected' : '' }}>Beanstalkd</option>
                                        <option value="sqs" {{ ($settings['queue_connection'] ?? '') == 'sqs' ? 'selected' : '' }}>Amazon SQS</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="setting-group">
                            <div class="setting-group-title">
                                <i class="ri-speed-line"></i>
                                Rate Limiting
                            </div>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label">Rate Limit (emails)</label>
                                    <input type="number" name="rate_limit" class="form-control" 
                                           value="{{ $settings['rate_limit'] ?? 100 }}" 
                                           min="1" max="1000" placeholder="100">
                                    <div class="help-text">
                                        <i class="ri-question-line"></i>
                                        Maximum emails per rate limit period
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Rate Limit Period (seconds)</label>
                                    <input type="number" name="rate_limit_period" class="form-control" 
                                           value="{{ $settings['rate_limit_period'] ?? 60 }}" 
                                           min="1" max="3600" placeholder="60">
                                    <div class="help-text">
                                        <i class="ri-question-line"></i>
                                        Time period for rate limiting (1-3600 seconds)
            </div>
        </div>
    </div>
</div>

                        <div class="d-flex justify-content-end gap-2 mt-4">
                            <button type="reset" class="btn btn-label-secondary">Reset</button>
                            <button type="submit" class="btn btn-primary">
                                <i class="ri-save-line me-1"></i>Save Advanced Settings
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Testing Tab -->
                <div class="tab-pane fade" id="testing-tab" role="tabpanel">
                    <div class="row">
                        <div class="col-lg-8">
                            <div class="card mb-4">
                                <div class="card-header">
                                    <h5 class="mb-0">
                                        <i class="ri-flashlight-line me-2"></i>Connection Test
                                    </h5>
                                </div>
                                <div class="card-body">
                                    <p class="text-muted mb-4">Test your SMTP connection settings before sending emails.</p>
                                    <form id="connectionTestForm">
                                        @csrf
                                        <div class="row g-3">
                                            <div class="col-md-6">
                                                <label class="form-label">SMTP Host</label>
                                                <input type="text" class="form-control" name="host" 
                                                       value="{{ $settings['host'] ?? '' }}" placeholder="smtp.gmail.com">
                                            </div>
                                            <div class="col-md-3">
                                                <label class="form-label">Port</label>
                                                <input type="number" class="form-control" name="port" 
                                                       value="{{ $settings['port'] ?? 587 }}" placeholder="587">
                                            </div>
                                            <div class="col-md-3">
                                                <label class="form-label">Encryption</label>
                                                <select class="form-select" name="encryption">
                                                    <option value="tls" {{ ($settings['encryption'] ?? 'tls') == 'tls' ? 'selected' : '' }}>TLS</option>
                                                    <option value="ssl" {{ ($settings['encryption'] ?? '') == 'ssl' ? 'selected' : '' }}>SSL</option>
                                                </select>
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label">Username</label>
                                                <input type="text" class="form-control" name="username" 
                                                       value="{{ $settings['username'] ?? '' }}">
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label">Password</label>
                                                <input type="password" class="form-control" name="password" 
                                                       placeholder="Enter password">
                                            </div>
                                        </div>
                                        <div class="mt-4">
                                            <button type="submit" class="btn btn-primary">
                                                <i class="ri-flashlight-line me-1"></i>Test Connection
                                            </button>
                                        </div>
                                    </form>
                                    <div id="connectionTestResult" class="mt-3" style="display: none;"></div>
                                </div>
                            </div>

                            <div class="card">
                                <div class="card-header">
                                    <h5 class="mb-0">
                                        <i class="ri-send-plane-line me-2"></i>Send Test Email
                                    </h5>
            </div>
                                <div class="card-body">
                                    <p class="text-muted mb-4">Send a test email to verify your configuration is working correctly.</p>
                                    <form id="testEmailForm">
                @csrf
                                        <div class="row g-3">
                                            <div class="col-md-6">
                                                <label class="form-label">Recipient Email <span class="text-danger">*</span></label>
                                                <input type="email" class="form-control" name="test_email" 
                               value="{{ auth()->user()->email }}" required>
                    </div>
                                            <div class="col-md-6">
                                                <label class="form-label">Subject</label>
                                                <input type="text" class="form-control" name="subject" 
                                                       value="Test Email from {{ config('app.name') }}">
                                            </div>
                                            <div class="col-12">
                                                <label class="form-label">Message</label>
                                                <textarea class="form-control" name="message" rows="4">This is a test email to verify your SMTP configuration is working correctly.

If you receive this email, your SMTP settings are properly configured.

Sent from: {{ config('app.name') }}</textarea>
                                            </div>
                </div>
                                        <div class="mt-4">
                                            <button type="submit" class="btn btn-success">
                        <i class="ri-send-plane-line me-1"></i>Send Test Email
                    </button>
                </div>
            </form>
                                    <div id="testEmailResult" class="mt-3" style="display: none;"></div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="mb-0">
                                        <i class="ri-information-line me-2"></i>Quick Reference
                                    </h5>
                                </div>
                                <div class="card-body">
                                    <h6 class="mb-3">Common SMTP Settings</h6>
                                    <div class="mb-3">
                                        <strong>Gmail:</strong>
                                        <ul class="small mb-0">
                                            <li>Host: smtp.gmail.com</li>
                                            <li>Port: 587 (TLS) or 465 (SSL)</li>
                                            <li>Use App Password for authentication</li>
                                        </ul>
                                    </div>
                                    <div class="mb-3">
                                        <strong>Outlook/Hotmail:</strong>
                                        <ul class="small mb-0">
                                            <li>Host: smtp-mail.outlook.com</li>
                                            <li>Port: 587 (TLS)</li>
                                        </ul>
                                    </div>
                                    <div class="mb-3">
                                        <strong>Yahoo:</strong>
                                        <ul class="small mb-0">
                                            <li>Host: smtp.mail.yahoo.com</li>
                                            <li>Port: 587 (TLS) or 465 (SSL)</li>
                                        </ul>
                                    </div>
                                    <hr>
                                    <p class="small mb-0"><strong>Note:</strong> Some providers require app-specific passwords. Check your email provider's documentation.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Email Logs Tab -->
                <div class="tab-pane fade" id="logs-tab" role="tabpanel">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h5 class="mb-0">
                                <i class="ri-file-list-3-line me-2"></i>Email Logs
                            </h5>
                            <button type="button" class="btn btn-sm btn-outline-primary" onclick="refreshEmailLogs()">
                                <i class="ri-refresh-line me-1"></i>Refresh
                            </button>
                        </div>
                        <div class="card-body">
                            <div class="row g-4 mb-3">
                                <div class="col-md-4">
                                    <div class="card border-0 bg-label-primary text-primary h-100">
                                        <div class="card-body py-3">
                                            <h6 class="mb-1">Total Emails</h6>
                                            <h4 class="mb-0">{{ number_format($emailLogStats['total'] ?? 0) }}</h4>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="card border-0 bg-label-success text-success h-100">
                                        <div class="card-body py-3">
                                            <h6 class="mb-1">Last 24 Hours</h6>
                                            <h4 class="mb-0">{{ number_format($emailLogStats['last_24h'] ?? 0) }}</h4>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="card border-0 bg-label-danger text-danger h-100">
                                        <div class="card-body py-3">
                                            <h6 class="mb-1">Failed</h6>
                                            <h4 class="mb-0">{{ number_format($emailLogStats['failed'] ?? 0) }}</h4>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="alert alert-info mb-3">
                                <i class="ri-information-line me-2"></i>
                                Email logs are currently recording **SMTP test emails**. You can extend this to all outgoing emails
                                by logging from your mail sending services into the <code>email_logs</code> table.
                            </div>

                            <div class="table-responsive">
                                <table class="table table-hover align-middle">
                                    <thead>
                                        <tr>
                                            <th>Date</th>
                                            <th>To</th>
                                            <th>Subject</th>
                                            <th>Status</th>
                                            <th>Error</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($emailLogs ?? [] as $log)
                                            @php
                                                $statusClass = match($log->status) {
                                                    'sent' => 'bg-label-success',
                                                    'queued' => 'bg-label-warning',
                                                    'failed' => 'bg-label-danger',
                                                    default => 'bg-label-secondary',
                                                };
                                            @endphp
                                            <tr>
                                                <td class="text-nowrap small">{{ optional($log->sent_at ?? $log->created_at)->format('Y-m-d H:i:s') }}</td>
                                                <td class="small">{{ $log->to }}</td>
                                                <td class="small text-truncate" style="max-width: 240px;" title="{{ $log->subject }}">
                                                    {{ $log->subject ?? 'N/A' }}
                                                </td>
                                                <td>
                                                    <span class="badge {{ $statusClass }}">{{ ucfirst($log->status) }}</span>
                                                </td>
                                                <td class="small text-truncate" style="max-width: 260px;" title="{{ $log->error_message }}">
                                                    {{ $log->error_message ?? '-' }}
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="5" class="text-center text-muted py-4">
                                                    <i class="ri-inbox-line ri-48px mb-2" style="opacity: 0.3;"></i>
                                                    <p class="mt-2 mb-0">No email logs available yet</p>
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Templates Tab -->
                <div class="tab-pane fade" id="templates-tab" role="tabpanel">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h5 class="mb-0">
                                <i class="ri-file-text-line me-2"></i>Email Templates
                            </h5>
                            <div class="d-flex align-items-center gap-2">
                                <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#genericEmailTemplateModal" onclick="prepareGenericTemplateModal()">
                                    <i class="ri-add-line me-1"></i>Create Template
                                </button>
                                <span class="text-body-secondary small d-none d-md-inline">
                                    Use variables like <code>{name}</code>, <code>{app_name}</code>, <code>{reset_link}</code>, <code>{message}</code>
                                </span>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="alert alert-info mb-3">
                                <i class="ri-information-line me-2"></i>
                                Templates are stored in the <code>email_templates</code> table. You can customize subjects and bodies,
                                and reference variables like <code>{name}</code>, <code>{app_name}</code>, <code>{reset_link}</code>, and <code>{message}</code>.
                            </div>

                            <div class="row g-4 mb-4">
                                @php
                                    $welcomeTemplate = $emailTemplates['welcome'] ?? null;
                                    $resetTemplate = $emailTemplates['password_reset'] ?? null;
                                    $notifyTemplate = $emailTemplates['notification'] ?? null;
                                @endphp

                                <!-- Welcome Email -->
                                <div class="col-xl-4 col-md-6">
                                    <div class="card h-100">
                                        <div class="card-body d-flex flex-column">
                                            <div class="d-flex align-items-center mb-3">
                                                <div class="avatar avatar-lg bg-label-success me-3">
                                                    <i class="ri-mail-send-line ri-24px"></i>
                                                </div>
                                                <div>
                                                    <h6 class="mb-1">{{ $welcomeTemplate->name ?? 'Welcome Email' }}</h6>
                                                    <p class="small text-muted mb-0">{{ $welcomeTemplate->description ?? 'Template for new user registration' }}</p>
                                                </div>
                                            </div>
                                            <p class="small text-body-secondary flex-grow-1 mb-2">
                                                <strong>Subject:</strong>
                                                <span class="text-truncate d-inline-block" style="max-width: 100%;" title="{{ $welcomeTemplate->subject ?? '' }}">
                                                    {{ $welcomeTemplate->subject ?? 'Welcome to ' . config('app.name') }}
                                                </span>
                                            </p>
                                            <p class="small text-body-secondary mb-3">
                                                <strong>Last updated:</strong>
                                                {{ optional($welcomeTemplate?->updated_at ?? $welcomeTemplate?->created_at)->format('Y-m-d H:i') ?? 'N/A' }}
                                            </p>
                                            <button type="button" class="btn btn-outline-primary btn-sm mt-auto" data-bs-toggle="modal" data-bs-target="#editWelcomeTemplateModal">
                                                <i class="ri-edit-line me-1"></i>Edit Template
                                            </button>
                                        </div>
                                    </div>
                                </div>

                                <!-- Password Reset -->
                                <div class="col-xl-4 col-md-6">
                                    <div class="card h-100">
                                        <div class="card-body d-flex flex-column">
                                            <div class="d-flex align-items-center mb-3">
                                                <div class="avatar avatar-lg bg-label-warning me-3">
                                                    <i class="ri-lock-password-line ri-24px"></i>
                                                </div>
                                                <div>
                                                    <h6 class="mb-1">{{ $resetTemplate->name ?? 'Password Reset' }}</h6>
                                                    <p class="small text-muted mb-0">{{ $resetTemplate->description ?? 'Template for password reset emails' }}</p>
                                                </div>
                                            </div>
                                            <p class="small text-body-secondary flex-grow-1 mb-2">
                                                <strong>Subject:</strong>
                                                <span class="text-truncate d-inline-block" style="max-width: 100%;" title="{{ $resetTemplate->subject ?? '' }}">
                                                    {{ $resetTemplate->subject ?? 'Reset your password' }}
                                                </span>
                                            </p>
                                            <p class="small text-body-secondary mb-3">
                                                <strong>Last updated:</strong>
                                                {{ optional($resetTemplate?->updated_at ?? $resetTemplate?->created_at)->format('Y-m-d H:i') ?? 'N/A' }}
                                            </p>
                                            <button type="button" class="btn btn-outline-primary btn-sm mt-auto" data-bs-toggle="modal" data-bs-target="#editPasswordResetTemplateModal">
                                                <i class="ri-edit-line me-1"></i>Edit Template
                                            </button>
                                        </div>
                                    </div>
                                </div>

                                <!-- Notifications -->
                                <div class="col-xl-4 col-md-6">
                                    <div class="card h-100">
                                        <div class="card-body d-flex flex-column">
                                            <div class="d-flex align-items-center mb-3">
                                                <div class="avatar avatar-lg bg-label-info me-3">
                                                    <i class="ri-notification-line ri-24px"></i>
                                                </div>
                                                <div>
                                                    <h6 class="mb-1">{{ $notifyTemplate->name ?? 'Notifications' }}</h6>
                                                    <p class="small text-muted mb-0">{{ $notifyTemplate->description ?? 'Template for system notifications' }}</p>
                                                </div>
                                            </div>
                                            <p class="small text-body-secondary flex-grow-1 mb-2">
                                                <strong>Subject:</strong>
                                                <span class="text-truncate d-inline-block" style="max-width: 100%;" title="{{ $notifyTemplate->subject ?? '' }}">
                                                    {{ $notifyTemplate->subject ?? 'New notification from ' . config('app.name') }}
                                                </span>
                                            </p>
                                            <p class="small text-body-secondary mb-3">
                                                <strong>Last updated:</strong>
                                                {{ optional($notifyTemplate?->updated_at ?? $notifyTemplate?->created_at)->format('Y-m-d H:i') ?? 'N/A' }}
                                            </p>
                                            <button type="button" class="btn btn-outline-primary btn-sm mt-auto" data-bs-toggle="modal" data-bs-target="#editNotificationTemplateModal">
                                                <i class="ri-edit-line me-1"></i>Edit Template
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- All templates list -->
                            <div class="card mt-3">
                                <div class="card-header d-flex justify-content-between align-items-center">
                                    <h6 class="mb-0">
                                        <i class="ri-list-check-2 me-1"></i>All Templates
                                    </h6>
                                    <span class="text-body-secondary small">{{ $emailTemplates?->count() ?? 0 }} template(s)</span>
                                </div>
                                <div class="card-body p-0">
                                    <div class="table-responsive">
                                        <table class="table table-hover mb-0 align-middle">
                                            <thead>
                                                <tr>
                                                    <th>Key</th>
                                                    <th>Name</th>
                                                    <th>Subject</th>
                                                    <th>Status</th>
                                                    <th>Last Updated</th>
                                                    <th>Actions</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @forelse(($emailTemplates ?? collect()) as $tpl)
                                                    <tr>
                                                        <td class="small"><code>{{ $tpl->key }}</code></td>
                                                        <td class="small">{{ $tpl->name }}</td>
                                                        <td class="small text-truncate" style="max-width: 260px;" title="{{ $tpl->subject }}">
                                                            {{ $tpl->subject ?? 'N/A' }}
                                                        </td>
                                                        <td>
                                                            <span class="badge {{ $tpl->is_active ? 'bg-label-success' : 'bg-label-secondary' }}">
                                                                {{ $tpl->is_active ? 'Active' : 'Inactive' }}
                                                            </span>
                                                        </td>
                                                        <td class="small text-nowrap">
                                                            {{ optional($tpl->updated_at ?? $tpl->created_at)->format('Y-m-d H:i') }}
                                                        </td>
                                                        <td>
                                                            <button
                                                                type="button"
                                                                class="btn btn-sm btn-outline-primary"
                                                                data-bs-toggle="modal"
                                                                data-bs-target="#genericEmailTemplateModal"
                                                                onclick="prepareGenericTemplateModal({
                                                                    key: '{{ $tpl->key }}',
                                                                    name: @json($tpl->name),
                                                                    subject: @json($tpl->subject),
                                                                    body_html: @json($tpl->body_html),
                                                                    body_text: @json($tpl->body_text),
                                                                    is_active: {{ $tpl->is_active ? 'true' : 'false' }}
                                                                })"
                                                            >
                                                                <i class="ri-edit-line"></i>
                                                            </button>
                                                        </td>
                                                    </tr>
                                                @empty
                                                    <tr>
                                                        <td colspan="6" class="text-center text-muted py-3">
                                                            No templates found.
                                                        </td>
                                                    </tr>
                                                @endforelse
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Information Tab -->
                <div class="tab-pane fade" id="info-tab" role="tabpanel">
                    <div class="row">
                        <div class="col-lg-8">
                            <div class="card mb-4">
                                <div class="card-header">
                                    <h5 class="mb-0">
                                        <i class="ri-information-line me-2"></i>Email Configuration Guide
                                    </h5>
                                </div>
                                <div class="card-body">
                                    <h6>SMTP Configuration</h6>
                                    <p>SMTP (Simple Mail Transfer Protocol) is the standard protocol for sending emails. Configure your SMTP settings to enable email functionality in your application.</p>
                                    
                                    <h6 class="mt-4">Common Mailer Options</h6>
                                    <ul>
                                        <li><strong>SMTP:</strong> Standard SMTP server (Gmail, Outlook, custom servers)</li>
                                        <li><strong>Mailgun:</strong> Professional email service with API</li>
                                        <li><strong>Amazon SES:</strong> Scalable email service from AWS</li>
                                        <li><strong>Sendmail:</strong> Use server's sendmail command</li>
                                        <li><strong>Postmark:</strong> Transactional email service</li>
                                        <li><strong>Log:</strong> Log emails to file (for testing)</li>
                                    </ul>

                                    <h6 class="mt-4">Security Best Practices</h6>
                                    <ul>
                                        <li>Always use TLS or SSL encryption</li>
                                        <li>Use app-specific passwords for Gmail</li>
                                        <li>Enable queue for production environments</li>
                                        <li>Set up rate limiting to prevent abuse</li>
                                        <li>Verify SSL certificates in production</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="mb-0">Quick Links</h5>
                                </div>
                                <div class="card-body">
                                    <ul class="list-unstyled mb-0">
                                        <li class="mb-2">
                                            <a href="https://support.google.com/mail/answer/7126229" target="_blank" class="text-decoration-none">
                                                <i class="ri-external-link-line me-2"></i>Gmail SMTP Setup
                                            </a>
                                        </li>
                                        <li class="mb-2">
                                            <a href="https://docs.microsoft.com/en-us/exchange/mail-flow-best-practices/how-to-set-up-a-multifunction-device-or-application-to-send-email-using-microsoft-365-or-office-365" target="_blank" class="text-decoration-none">
                                                <i class="ri-external-link-line me-2"></i>Outlook SMTP Setup
                                            </a>
                                        </li>
                                        <li class="mb-2">
                                            <a href="https://documentation.mailgun.com/en/latest/" target="_blank" class="text-decoration-none">
                                                <i class="ri-external-link-line me-2"></i>Mailgun Documentation
                                            </a>
                                        </li>
                                        <li class="mb-2">
                                            <a href="https://docs.aws.amazon.com/ses/" target="_blank" class="text-decoration-none">
                                                <i class="ri-external-link-line me-2"></i>Amazon SES Docs
                                            </a>
                                        </li>
                                    </ul>
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
    function prepareGenericTemplateModal(template) {
        const modalEl = document.getElementById('genericEmailTemplateModal');
        if (!modalEl) return;

        const form = modalEl.querySelector('form');
        const keyInput = form.querySelector('input[name="key"]');
        const nameInput = form.querySelector('input[name="name"]');
        const subjectInput = form.querySelector('input[name="subject"]');
        const bodyHtmlInput = form.querySelector('textarea[name="body_html"]');
        const bodyTextInput = form.querySelector('textarea[name="body_text"]');
        const activeInput = form.querySelector('input[name="is_active"]');

        if (template && template.key) {
            keyInput.value = template.key;
            nameInput.value = template.name || '';
            subjectInput.value = template.subject || '';
            bodyHtmlInput.value = template.body_html || '';
            bodyTextInput.value = template.body_text || '';
            activeInput.checked = !!template.is_active;
        } else {
            keyInput.value = '';
            nameInput.value = '';
            subjectInput.value = '';
            bodyHtmlInput.value = '';
            bodyTextInput.value = '';
            activeInput.checked = true;
        }

        const baseUrl = '{{ url('admin/settings/email-templates') }}';
        form.action = baseUrl + '/' + (keyInput.value || 'new-key');

        const modal = new bootstrap.Modal(modalEl);
        modal.show();
    }
</script>
@endpush

<!-- Template Edit Modals -->
@if(isset($emailTemplates))
    <!-- Welcome Template Modal -->
    <div class="modal fade" id="editWelcomeTemplateModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-scrollable" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Welcome Email Template</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form method="POST" action="{{ route('admin.settings.email-templates.update', 'welcome') }}">
                    @csrf
                    <div class="modal-body">
                        @php $tpl = $emailTemplates['welcome'] ?? null; @endphp
                        <div class="mb-3">
                            <label class="form-label">Template Name</label>
                            <input type="text" name="name" class="form-control" value="{{ $tpl->name ?? 'Welcome Email' }}" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Subject</label>
                            <input type="text" name="subject" class="form-control" value="{{ $tpl->subject ?? ('Welcome to ' . config('app.name')) }}" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">HTML Body</label>
                            <textarea name="body_html" class="form-control" rows="6" placeholder="Use variables like {{ '{' }}{{ 'name' }}{{ '}' }}, {{ '{' }}{{ 'app_name' }}{{ '}' }} etc.">{{ $tpl->body_html ?? '' }}</textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Plain Text Body (optional)</label>
                            <textarea name="body_text" class="form-control" rows="4">{{ $tpl->body_text ?? '' }}</textarea>
                        </div>
                        <div class="form-check mb-2">
                            <input class="form-check-input" type="checkbox" name="is_active" id="welcome_active" {{ ($tpl->is_active ?? true) ? 'checked' : '' }}>
                            <label class="form-check-label" for="welcome_active">
                                Template is active
                            </label>
                        </div>
                        <small class="text-body-secondary">
                            Variables available: <code>{{ '{' }}{{ 'name' }}{{ '}' }}</code>, <code>{{ '{' }}{{ 'app_name' }}{{ '}' }}</code>
                        </small>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Save Template</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Password Reset Template Modal -->
    <div class="modal fade" id="editPasswordResetTemplateModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-scrollable" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Password Reset Template</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form method="POST" action="{{ route('admin.settings.email-templates.update', 'password_reset') }}">
                    @csrf
                    <div class="modal-body">
                        @php $tpl = $emailTemplates['password_reset'] ?? null; @endphp
                        <div class="mb-3">
                            <label class="form-label">Template Name</label>
                            <input type="text" name="name" class="form-control" value="{{ $tpl->name ?? 'Password Reset' }}" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Subject</label>
                            <input type="text" name="subject" class="form-control" value="{{ $tpl->subject ?? 'Reset your password' }}" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">HTML Body</label>
                            <textarea name="body_html" class="form-control" rows="6" placeholder="Use {{ '{' }}{{ 'reset_link' }}{{ '}' }} for the password reset URL.">{{ $tpl->body_html ?? '' }}</textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Plain Text Body (optional)</label>
                            <textarea name="body_text" class="form-control" rows="4">{{ $tpl->body_text ?? '' }}</textarea>
                        </div>
                        <div class="form-check mb-2">
                            <input class="form-check-input" type="checkbox" name="is_active" id="reset_active" {{ ($tpl->is_active ?? true) ? 'checked' : '' }}>
                            <label class="form-check-label" for="reset_active">
                                Template is active
                            </label>
                        </div>
                        <small class="text-body-secondary">
                            Variables available: <code>{{ '{' }}{{ 'name' }}{{ '}' }}</code>, <code>{{ '{' }}{{ 'reset_link' }}{{ '}' }}</code>, <code>{{ '{' }}{{ 'app_name' }}{{ '}' }}</code>
                        </small>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Save Template</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Notifications Template Modal -->
    <div class="modal fade" id="editNotificationTemplateModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-scrollable" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Notification Template</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form method="POST" action="{{ route('admin.settings.email-templates.update', 'notification') }}">
                    @csrf
                    <div class="modal-body">
                        @php $tpl = $emailTemplates['notification'] ?? null; @endphp
                        <div class="mb-3">
                            <label class="form-label">Template Name</label>
                            <input type="text" name="name" class="form-control" value="{{ $tpl->name ?? 'Notifications' }}" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Subject</label>
                            <input type="text" name="subject" class="form-control" value="{{ $tpl->subject ?? ('New notification from ' . config('app.name')) }}" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">HTML Body</label>
                            <textarea name="body_html" class="form-control" rows="6" placeholder="Use {{ '{' }}{{ 'message' }}{{ '}' }} for the notification body.">{{ $tpl->body_html ?? '' }}</textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Plain Text Body (optional)</label>
                            <textarea name="body_text" class="form-control" rows="4">{{ $tpl->body_text ?? '' }}</textarea>
                        </div>
                        <div class="form-check mb-2">
                            <input class="form-check-input" type="checkbox" name="is_active" id="notification_active" {{ ($tpl->is_active ?? true) ? 'checked' : '' }}>
                            <label class="form-check-label" for="notification_active">
                                Template is active
                            </label>
                        </div>
                        <small class="text-body-secondary">
                            Variables available: <code>{{ '{' }}{{ 'name' }}{{ '}' }}</code>, <code>{{ '{' }}{{ 'message' }}{{ '}' }}</code>, <code>{{ '{' }}{{ 'app_name' }}{{ '}' }}</code>
                        </small>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Save Template</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Generic Template Modal (create/edit) -->
    <div class="modal fade" id="genericEmailTemplateModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-scrollable" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Email Template</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form method="POST" action="{{ url('admin/settings/email-templates/new-key') }}">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Template Key (unique)</label>
                            <input type="text" name="key" class="form-control" placeholder="e.g. invoice, reminder" required>
                            <small class="text-body-secondary">Use lowercase and underscores only (e.g. <code>invoice_reminder</code>).</small>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Template Name</label>
                            <input type="text" name="name" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Subject</label>
                            <input type="text" name="subject" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">HTML Body</label>
                            <textarea name="body_html" class="form-control" rows="6" placeholder="Use variables like {name}, {app_name}, {reset_link}, {message}."></textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Plain Text Body (optional)</label>
                            <textarea name="body_text" class="form-control" rows="4"></textarea>
                        </div>
                        <div class="form-check mb-2">
                            <input class="form-check-input" type="checkbox" name="is_active" id="generic_active" checked>
                            <label class="form-check-label" for="generic_active">
                                Template is active
                            </label>
                        </div>
                        <small class="text-body-secondary d-block mt-1">
                            Supported variables depend on how you use the template in code, but common ones include
                            <code>{name}</code>, <code>{app_name}</code>, <code>{reset_link}</code>, <code>{message}</code>.
                        </small>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Save Template</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endif

@push('scripts')
<script>
// Form submissions
document.getElementById('emailConfigForm')?.addEventListener('submit', async function(e) {
    e.preventDefault();
    await submitForm(this, 'Configuration');
});

document.getElementById('advancedConfigForm')?.addEventListener('submit', async function(e) {
    e.preventDefault();
    await submitForm(this, 'Advanced Settings');
});

async function submitForm(form, section) {
    const formData = new FormData(form);
    const submitBtn = form.querySelector('button[type="submit"]');
    const originalText = submitBtn.innerHTML;
    submitBtn.disabled = true;
    submitBtn.innerHTML = '<i class="ri-loader-4-line spin me-1"></i>Saving...';

    try {
        const response = await fetch('{{ route('admin.settings.email-smtp.update') }}', {
            method: 'PUT',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json',
            },
            body: formData
        });

        const data = await response.json();

        if (response.ok && data.success) {
            if (typeof showSuccessToast === 'function') {
                showSuccessToast(data.message || section + ' settings saved successfully!', 'Success');
            } else {
                alert('✓ ' + (data.message || section + ' settings saved successfully!'));
            }
        } else {
            const errorMessage = data.message || (data.errors ? 'Please fix the validation errors below' : 'Failed to save settings');
            if (typeof showErrorToast === 'function') {
                showErrorToast(errorMessage, 'Validation Error');
            } else {
                alert('✗ ' + errorMessage);
            }
        }
    } catch (error) {
        console.error('Error:', error);
        if (typeof showErrorToast === 'function') {
            showErrorToast('An error occurred. Please try again.', 'Error');
        } else {
            alert('✗ An error occurred. Please try again.');
        }
    } finally {
        submitBtn.disabled = false;
        submitBtn.innerHTML = originalText;
    }
}

// Connection Test
document.getElementById('connectionTestForm')?.addEventListener('submit', async function(e) {
    e.preventDefault();
    const formData = new FormData(this);
    const submitBtn = this.querySelector('button[type="submit"]');
    const resultDiv = document.getElementById('connectionTestResult');
    const originalText = submitBtn.innerHTML;
    
    submitBtn.disabled = true;
    submitBtn.innerHTML = '<i class="ri-loader-4-line spin me-1"></i>Testing...';
    resultDiv.style.display = 'none';

    try {
        const response = await fetch('{{ route('admin.settings.email-smtp.test-connection') }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json',
            },
            body: formData
        });

        const data = await response.json();
        resultDiv.style.display = 'block';
        
        if (data.success) {
            resultDiv.className = 'test-result success';
            resultDiv.innerHTML = '<i class="ri-checkbox-circle-line me-1"></i><strong>Success!</strong> ' + data.message;
            updateConnectionStatus(true);
        } else {
            resultDiv.className = 'test-result error';
            resultDiv.innerHTML = '<i class="ri-error-warning-line me-1"></i><strong>Failed!</strong> ' + data.message;
            updateConnectionStatus(false);
        }
    } catch (error) {
        resultDiv.style.display = 'block';
        resultDiv.className = 'test-result error';
        resultDiv.innerHTML = '<i class="ri-error-warning-line me-1"></i><strong>Error!</strong> ' + error.message;
        updateConnectionStatus(false);
    } finally {
        submitBtn.disabled = false;
        submitBtn.innerHTML = originalText;
    }
});

// Test Email
document.getElementById('testEmailForm')?.addEventListener('submit', async function(e) {
    e.preventDefault();
    const formData = new FormData(this);
    const submitBtn = this.querySelector('button[type="submit"]');
    const resultDiv = document.getElementById('testEmailResult');
    const originalText = submitBtn.innerHTML;
    
    submitBtn.disabled = true;
    submitBtn.innerHTML = '<i class="ri-loader-4-line spin me-1"></i>Sending...';
    resultDiv.style.display = 'none';

    try {
        const response = await fetch('{{ route('admin.settings.email-smtp.test') }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json',
            },
            body: formData
        });

        const data = await response.json();
        resultDiv.style.display = 'block';
        
        if (data.success) {
            resultDiv.className = 'test-result success';
            resultDiv.innerHTML = '<i class="ri-checkbox-circle-line me-1"></i><strong>Success!</strong> ' + data.message;
        } else {
            resultDiv.className = 'test-result error';
            resultDiv.innerHTML = '<i class="ri-error-warning-line me-1"></i><strong>Failed!</strong> ' + data.message;
        }
    } catch (error) {
        resultDiv.style.display = 'block';
        resultDiv.className = 'test-result error';
        resultDiv.innerHTML = '<i class="ri-error-warning-line me-1"></i><strong>Error!</strong> ' + error.message;
    } finally {
        submitBtn.disabled = false;
        submitBtn.innerHTML = originalText;
    }
});

// Test Connection Button (Header)
document.getElementById('testConnectionBtn')?.addEventListener('click', function() {
    const form = document.getElementById('connectionTestForm');
    if (form) {
        form.dispatchEvent(new Event('submit'));
    }
});

// Update Connection Status
function updateConnectionStatus(success) {
    const indicator = document.getElementById('connectionStatus');
    const text = document.getElementById('connectionStatusText');
    if (indicator && text) {
        indicator.className = 'connection-indicator ' + (success ? 'active' : 'inactive');
        text.textContent = success ? 'Connected' : 'Failed';
    }
}

// Toggle Queue Settings
function toggleQueueSettings() {
    const enabled = document.getElementById('queueEnabled')?.checked;
    const group = document.getElementById('queueConnectionGroup');
    if (group) {
        group.style.display = enabled ? 'block' : 'none';
    }
}

// Update Mailer Settings
function updateMailerSettings() {
    const mailer = document.querySelector('[name="mailer"]')?.value;
    // You can add logic here to show/hide fields based on mailer type
}

// Refresh Email Logs
function refreshEmailLogs() {
    alert('Email logs feature coming soon!');
}
</script>
@endpush


