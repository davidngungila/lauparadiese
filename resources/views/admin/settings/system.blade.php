@extends('admin.layouts.app')

@section('title', 'System Settings - Advanced Configuration')

@push('styles')
<style>
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
        margin-bottom: 2rem;
    }
    .setting-group-title {
        font-size: 1.1rem;
        font-weight: 600;
        color: #566a7f;
        margin-bottom: 1rem;
        padding-bottom: 0.5rem;
        border-bottom: 1px solid #e7e9ec;
    }
    .form-label {
        font-weight: 500;
        color: #566a7f;
        margin-bottom: 0.5rem;
    }
    .form-text {
        font-size: 0.875rem;
        color: #6c757d;
    }
    .info-card {
        background: #f8f9fa;
        border-left: 3px solid #3ea572;
        padding: 1rem;
        border-radius: 0.25rem;
        margin-bottom: 1.5rem;
    }
    .info-card i {
        color: #3ea572;
    }
</style>
@endpush

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h4 class="mb-1">
                        <i class="ri-settings-3-line me-2"></i>System Settings
                    </h4>
                    <p class="text-muted mb-0">Configure system-wide settings and preferences</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabs Navigation -->
    <div class="card">
        <div class="card-header border-bottom">
            <ul class="nav nav-tabs card-header-tabs" role="tablist">
                <li class="nav-item">
                    <button type="button" class="nav-link active" data-bs-toggle="tab" data-bs-target="#general-tab" role="tab" aria-selected="true">
                        <i class="ri-global-line me-1"></i>General
                    </button>
                </li>
                <li class="nav-item">
                    <button type="button" class="nav-link" data-bs-toggle="tab" data-bs-target="#application-tab" role="tab" aria-selected="false">
                        <i class="ri-apps-line me-1"></i>Application
                    </button>
                </li>
                <li class="nav-item">
                    <button type="button" class="nav-link" data-bs-toggle="tab" data-bs-target="#email-tab" role="tab" aria-selected="false">
                        <i class="ri-mail-line me-1"></i>Email
                    </button>
                </li>
                <li class="nav-item">
                    <button type="button" class="nav-link" data-bs-toggle="tab" data-bs-target="#security-tab" role="tab" aria-selected="false">
                        <i class="ri-shield-check-line me-1"></i>Security
                    </button>
                </li>
                <li class="nav-item">
                    <button type="button" class="nav-link" data-bs-toggle="tab" data-bs-target="#performance-tab" role="tab" aria-selected="false">
                        <i class="ri-speed-line me-1"></i>Performance
                    </button>
                </li>
                <li class="nav-item">
                    <button type="button" class="nav-link" data-bs-toggle="tab" data-bs-target="#maintenance-tab" role="tab" aria-selected="false">
                        <i class="ri-tools-line me-1"></i>Maintenance
                    </button>
                </li>
                <li class="nav-item">
                    <button type="button" class="nav-link" data-bs-toggle="tab" data-bs-target="#backup-tab" role="tab" aria-selected="false">
                        <i class="ri-database-2-line me-1"></i>Backup
                    </button>
                </li>
                <li class="nav-item">
                    <button type="button" class="nav-link" data-bs-toggle="tab" data-bs-target="#logging-tab" role="tab" aria-selected="false">
                        <i class="ri-file-list-3-line me-1"></i>Logging
                    </button>
                </li>
            </ul>
        </div>

        <div class="card-body">
            <div class="tab-content">
                <!-- General Settings Tab -->
                <div class="tab-pane fade show active" id="general-tab" role="tabpanel">
                    <form id="generalForm" class="settings-form">
                        @csrf
                        <input type="hidden" name="group" value="general">
                        
                        <div class="info-card">
                            <i class="ri-information-line me-2"></i>
                            <strong>General Settings:</strong> Configure basic application information, timezone, locale, and currency settings.
                        </div>

                        <div class="setting-group">
                            <div class="setting-group-title">Application Information</div>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label">Application Name <span class="text-danger">*</span></label>
                                    <input type="text" name="app_name" class="form-control" 
                                           value="{{ isset($settings['general']['app_name']) ? $settings['general']['app_name']->value : ($configValues['app_name'] ?? '') }}" required>
                                    <small class="form-text">The name of your application displayed throughout the system</small>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Application URL <span class="text-danger">*</span></label>
                                    <input type="url" name="app_url" class="form-control" 
                                           value="{{ isset($settings['general']['app_url']) ? $settings['general']['app_url']->value : ($configValues['app_url'] ?? '') }}" required>
                                    <small class="form-text">The base URL of your application</small>
                                </div>
                            </div>
                        </div>

                        <div class="setting-group">
                            <div class="setting-group-title">Regional Settings</div>
                            <div class="row g-3">
                                <div class="col-md-4">
                                    <label class="form-label">Timezone <span class="text-danger">*</span></label>
                                    <select name="app_timezone" class="form-select" required>
                                        <option value="Africa/Dar_es_Salaam" {{ (isset($settings['general']['app_timezone']) ? $settings['general']['app_timezone']->value : ($configValues['app_timezone'] ?? 'Africa/Dar_es_Salaam')) == 'Africa/Dar_es_Salaam' ? 'selected' : '' }}>Africa/Dar_es_Salaam</option>
                                        <option value="UTC" {{ ($settings['general']['app_timezone']->value ?? $configValues['app_timezone'] ?? '') == 'UTC' ? 'selected' : '' }}>UTC</option>
                                        <option value="America/New_York" {{ ($settings['general']['app_timezone']->value ?? $configValues['app_timezone'] ?? '') == 'America/New_York' ? 'selected' : '' }}>America/New_York</option>
                                        <option value="Europe/London" {{ ($settings['general']['app_timezone']->value ?? $configValues['app_timezone'] ?? '') == 'Europe/London' ? 'selected' : '' }}>Europe/London</option>
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Locale <span class="text-danger">*</span></label>
                                    <select name="app_locale" class="form-select" required>
                                        <option value="en" {{ ($settings['general']['app_locale']->value ?? $configValues['app_locale'] ?? 'en') == 'en' ? 'selected' : '' }}>English</option>
                                        <option value="sw" {{ ($settings['general']['app_locale']->value ?? $configValues['app_locale'] ?? '') == 'sw' ? 'selected' : '' }}>Swahili</option>
                                        <option value="fr" {{ ($settings['general']['app_locale']->value ?? $configValues['app_locale'] ?? '') == 'fr' ? 'selected' : '' }}>French</option>
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Currency Code</label>
                                    <input type="text" name="app_currency" class="form-control" 
                                           value="{{ isset($settings['general']['app_currency']) ? $settings['general']['app_currency']->value : 'TZS' }}" maxlength="3" placeholder="TZS">
                                    <small class="form-text">ISO 4217 currency code (e.g., TZS, USD, EUR)</small>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Currency Symbol</label>
                                    <input type="text" name="app_currency_symbol" class="form-control" 
                                           value="{{ $settings['general']['app_currency_symbol']->value ?? 'TSh' }}" maxlength="10" placeholder="TSh">
                                    <small class="form-text">Currency symbol to display (e.g., $, €, TSh)</small>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-end gap-2 mt-4">
                            <button type="reset" class="btn btn-label-secondary">Reset</button>
                            <button type="submit" class="btn btn-primary">
                                <i class="ri-save-line me-1"></i>Save General Settings
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Application Settings Tab -->
                <div class="tab-pane fade" id="application-tab" role="tabpanel">
                    <form id="applicationForm" class="settings-form">
                        @csrf
                        <input type="hidden" name="group" value="application">
                        
                        <div class="info-card">
                            <i class="ri-information-line me-2"></i>
                            <strong>Application Settings:</strong> Configure debug mode, maintenance mode, session settings, and file upload limits.
                        </div>

                        <div class="setting-group">
                            <div class="setting-group-title">Debug & Maintenance</div>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" name="app_debug" id="app_debug" 
                                               value="1" {{ (isset($settings['application']['app_debug']) ? $settings['application']['app_debug']->value : ($configValues['app_debug'] ?? false)) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="app_debug">Enable Debug Mode</label>
                                    </div>
                                    <small class="form-text">Enable detailed error messages (disable in production)</small>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" name="app_maintenance_mode" id="app_maintenance_mode" 
                                               value="1" {{ (isset($settings['application']['app_maintenance_mode']) ? $settings['application']['app_maintenance_mode']->value : false) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="app_maintenance_mode">Enable Maintenance Mode</label>
                                    </div>
                                    <small class="form-text">Put the application in maintenance mode</small>
                                </div>
                            </div>
                        </div>

                        <div class="setting-group">
                            <div class="setting-group-title">Session & Upload Settings</div>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label">Session Lifetime (minutes)</label>
                                    <input type="number" name="app_session_lifetime" class="form-control" 
                                           value="{{ isset($settings['application']['app_session_lifetime']) ? $settings['application']['app_session_lifetime']->value : 120 }}" min="1" max="1440">
                                    <small class="form-text">How long sessions remain active (default: 120 minutes)</small>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Max Upload Size (MB)</label>
                                    <input type="number" name="app_max_upload_size" class="form-control" 
                                           value="{{ $settings['application']['app_max_upload_size']->value ?? 10 }}" min="1">
                                    <small class="form-text">Maximum file upload size in megabytes</small>
                                </div>
                                <div class="col-md-12">
                                    <label class="form-label">Allowed File Types</label>
                                    <input type="text" name="app_allowed_file_types" class="form-control" 
                                           value="{{ $settings['application']['app_allowed_file_types']->value ?? 'jpg,jpeg,png,gif,pdf,doc,docx' }}" 
                                           placeholder="jpg,jpeg,png,gif,pdf">
                                    <small class="form-text">Comma-separated list of allowed file extensions</small>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-end gap-2 mt-4">
                            <button type="reset" class="btn btn-label-secondary">Reset</button>
                            <button type="submit" class="btn btn-primary">
                                <i class="ri-save-line me-1"></i>Save Application Settings
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Email Settings Tab -->
                <div class="tab-pane fade" id="email-tab" role="tabpanel">
                    <form id="emailForm" class="settings-form">
                        @csrf
                        <input type="hidden" name="group" value="email">
                        
                        <div class="info-card">
                            <i class="ri-information-line me-2"></i>
                            <strong>Email Settings:</strong> Configure SMTP settings for sending emails from the system.
                        </div>

                        <div class="setting-group">
                            <div class="setting-group-title">Mail Configuration</div>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label">Mail Driver <span class="text-danger">*</span></label>
                                    <select name="mail_mailer" class="form-select" required>
                                        <option value="smtp" {{ ($settings['email']['mail_mailer']->value ?? $configValues['mail_mailer'] ?? 'smtp') == 'smtp' ? 'selected' : '' }}>SMTP</option>
                                        <option value="sendmail" {{ ($settings['email']['mail_mailer']->value ?? '') == 'sendmail' ? 'selected' : '' }}>Sendmail</option>
                                        <option value="mailgun" {{ ($settings['email']['mail_mailer']->value ?? '') == 'mailgun' ? 'selected' : '' }}>Mailgun</option>
                                        <option value="ses" {{ ($settings['email']['mail_mailer']->value ?? '') == 'ses' ? 'selected' : '' }}>Amazon SES</option>
                                        <option value="postmark" {{ ($settings['email']['mail_mailer']->value ?? '') == 'postmark' ? 'selected' : '' }}>Postmark</option>
                                        <option value="log" {{ ($settings['email']['mail_mailer']->value ?? '') == 'log' ? 'selected' : '' }}>Log (Testing)</option>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">SMTP Host</label>
                                    <input type="text" name="mail_host" class="form-control" 
                                           value="{{ $settings['email']['mail_host']->value ?? $configValues['mail_host'] ?? '' }}" 
                                           placeholder="smtp.gmail.com">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">SMTP Port</label>
                                    <input type="number" name="mail_port" class="form-control" 
                                           value="{{ $settings['email']['mail_port']->value ?? $configValues['mail_port'] ?? 587 }}" 
                                           min="1" max="65535" placeholder="587">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Encryption</label>
                                    <select name="mail_encryption" class="form-select">
                                        <option value="tls" {{ ($settings['email']['mail_encryption']->value ?? 'tls') == 'tls' ? 'selected' : '' }}>TLS</option>
                                        <option value="ssl" {{ ($settings['email']['mail_encryption']->value ?? '') == 'ssl' ? 'selected' : '' }}>SSL</option>
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">SMTP Username</label>
                                    <input type="text" name="mail_username" class="form-control" 
                                           value="{{ $settings['email']['mail_username']->value ?? $configValues['mail_username'] ?? '' }}" 
                                           placeholder="your-email@gmail.com">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">SMTP Password</label>
                                    <input type="password" name="mail_password" class="form-control" 
                                           value="{{ $settings['email']['mail_password']->value ?? '' }}" 
                                           placeholder="••••••••">
                                    <small class="form-text">Leave blank to keep current password</small>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">From Address <span class="text-danger">*</span></label>
                                    <input type="email" name="mail_from_address" class="form-control" 
                                           value="{{ $settings['email']['mail_from_address']->value ?? $configValues['mail_from_address'] ?? '' }}" 
                                           required placeholder="noreply@example.com">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">From Name <span class="text-danger">*</span></label>
                                    <input type="text" name="mail_from_name" class="form-control" 
                                           value="{{ $settings['email']['mail_from_name']->value ?? $configValues['mail_from_name'] ?? 'Lau Paradise Adventures' }}" 
                                           required placeholder="Lau Paradise Adventures">
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-end gap-2 mt-4">
                            <button type="reset" class="btn btn-label-secondary">Reset</button>
                            <button type="submit" class="btn btn-primary">
                                <i class="ri-save-line me-1"></i>Save Email Settings
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Security Settings Tab -->
                <div class="tab-pane fade" id="security-tab" role="tabpanel">
                    <form id="securityForm" class="settings-form">
                        @csrf
                        <input type="hidden" name="group" value="security">
                        
                        <div class="info-card">
                            <i class="ri-information-line me-2"></i>
                            <strong>Security Settings:</strong> Configure password requirements, session security, and authentication settings.
                        </div>

                        <div class="setting-group">
                            <div class="setting-group-title">Password Requirements</div>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label">Minimum Password Length</label>
                                    <input type="number" name="security_password_min_length" class="form-control" 
                                           value="{{ $settings['security']['security_password_min_length']->value ?? 8 }}" 
                                           min="6" max="32">
                                </div>
                                <div class="col-md-6">
                                    <div class="form-check form-switch mt-4">
                                        <input class="form-check-input" type="checkbox" name="security_password_require_uppercase" id="require_uppercase" 
                                               value="1" {{ ($settings['security']['security_password_require_uppercase']->value ?? false) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="require_uppercase">Require Uppercase Letters</label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" name="security_password_require_lowercase" id="require_lowercase" 
                                               value="1" {{ ($settings['security']['security_password_require_lowercase']->value ?? false) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="require_lowercase">Require Lowercase Letters</label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" name="security_password_require_numbers" id="require_numbers" 
                                               value="1" {{ ($settings['security']['security_password_require_numbers']->value ?? true) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="require_numbers">Require Numbers</label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" name="security_password_require_symbols" id="require_symbols" 
                                               value="1" {{ ($settings['security']['security_password_require_symbols']->value ?? false) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="require_symbols">Require Special Characters</label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="setting-group">
                            <div class="setting-group-title">Session & Authentication</div>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label">Session Timeout (minutes)</label>
                                    <input type="number" name="security_session_timeout" class="form-control" 
                                           value="{{ $settings['security']['security_session_timeout']->value ?? 30 }}" 
                                           min="5" max="1440">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Max Login Attempts</label>
                                    <input type="number" name="security_max_login_attempts" class="form-control" 
                                           value="{{ $settings['security']['security_max_login_attempts']->value ?? 5 }}" 
                                           min="3" max="10">
                                </div>
                                <div class="col-md-6">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" name="security_enable_2fa" id="enable_2fa" 
                                               value="1" {{ ($settings['security']['security_enable_2fa']->value ?? false) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="enable_2fa">Enable Two-Factor Authentication</label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-end gap-2 mt-4">
                            <button type="reset" class="btn btn-label-secondary">Reset</button>
                            <button type="submit" class="btn btn-primary">
                                <i class="ri-save-line me-1"></i>Save Security Settings
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Performance Settings Tab -->
                <div class="tab-pane fade" id="performance-tab" role="tabpanel">
                    <form id="performanceForm" class="settings-form">
                        @csrf
                        <input type="hidden" name="group" value="performance">
                        
                        <div class="info-card">
                            <i class="ri-information-line me-2"></i>
                            <strong>Performance Settings:</strong> Configure caching, queues, and performance optimization options.
                        </div>

                        <div class="setting-group">
                            <div class="setting-group-title">Cache Configuration</div>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label">Cache Driver</label>
                                    <select name="cache_driver" class="form-select">
                                        <option value="file" {{ ($settings['performance']['cache_driver']->value ?? 'file') == 'file' ? 'selected' : '' }}>File</option>
                                        <option value="redis" {{ ($settings['performance']['cache_driver']->value ?? '') == 'redis' ? 'selected' : '' }}>Redis</option>
                                        <option value="memcached" {{ ($settings['performance']['cache_driver']->value ?? '') == 'memcached' ? 'selected' : '' }}>Memcached</option>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Cache TTL (minutes)</label>
                                    <input type="number" name="cache_ttl" class="form-control" 
                                           value="{{ $settings['performance']['cache_ttl']->value ?? 60 }}" min="1">
                                </div>
                                <div class="col-md-6">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" name="enable_query_cache" id="enable_query_cache" 
                                               value="1" {{ ($settings['performance']['enable_query_cache']->value ?? true) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="enable_query_cache">Enable Query Cache</label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" name="enable_page_cache" id="enable_page_cache" 
                                               value="1" {{ ($settings['performance']['enable_page_cache']->value ?? false) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="enable_page_cache">Enable Page Cache</label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="setting-group">
                            <div class="setting-group-title">Queue Configuration</div>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label">Queue Driver</label>
                                    <select name="queue_driver" class="form-select">
                                        <option value="sync" {{ ($settings['performance']['queue_driver']->value ?? 'sync') == 'sync' ? 'selected' : '' }}>Synchronous</option>
                                        <option value="database" {{ ($settings['performance']['queue_driver']->value ?? '') == 'database' ? 'selected' : '' }}>Database</option>
                                        <option value="redis" {{ ($settings['performance']['queue_driver']->value ?? '') == 'redis' ? 'selected' : '' }}>Redis</option>
                                        <option value="sqs" {{ ($settings['performance']['queue_driver']->value ?? '') == 'sqs' ? 'selected' : '' }}>Amazon SQS</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-end gap-2 mt-4">
                            <button type="reset" class="btn btn-label-secondary">Reset</button>
                            <button type="submit" class="btn btn-primary">
                                <i class="ri-save-line me-1"></i>Save Performance Settings
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Maintenance Settings Tab -->
                <div class="tab-pane fade" id="maintenance-tab" role="tabpanel">
                    <form id="maintenanceForm" class="settings-form">
                        @csrf
                        <input type="hidden" name="group" value="maintenance">
                        
                        <div class="info-card">
                            <i class="ri-information-line me-2"></i>
                            <strong>Maintenance Settings:</strong> Configure maintenance mode and allowed IP addresses.
                        </div>

                        <div class="setting-group">
                            <div class="setting-group-title">Maintenance Mode</div>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" name="maintenance_enabled" id="maintenance_enabled" 
                                               value="1" {{ ($settings['maintenance']['maintenance_enabled']->value ?? false) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="maintenance_enabled">Enable Maintenance Mode</label>
                                    </div>
                                    <small class="form-text">When enabled, only allowed IPs can access the site</small>
                                </div>
                                <div class="col-md-12">
                                    <label class="form-label">Maintenance Message</label>
                                    <textarea name="maintenance_message" class="form-control" rows="3" 
                                              placeholder="We are currently performing scheduled maintenance. Please check back soon.">{{ $settings['maintenance']['maintenance_message']->value ?? '' }}</textarea>
                                </div>
                                <div class="col-md-12">
                                    <label class="form-label">Allowed IPs (comma-separated)</label>
                                    <input type="text" name="maintenance_allowed_ips" class="form-control" 
                                           value="{{ $settings['maintenance']['maintenance_allowed_ips']->value ?? '' }}" 
                                           placeholder="127.0.0.1, 192.168.1.1">
                                    <small class="form-text">IP addresses that can access the site during maintenance</small>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-end gap-2 mt-4">
                            <button type="reset" class="btn btn-label-secondary">Reset</button>
                            <button type="submit" class="btn btn-primary">
                                <i class="ri-save-line me-1"></i>Save Maintenance Settings
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Backup Settings Tab -->
                <div class="tab-pane fade" id="backup-tab" role="tabpanel">
                    <form id="backupForm" class="settings-form">
                        @csrf
                        <input type="hidden" name="group" value="backup">
                        
                        <div class="info-card">
                            <i class="ri-information-line me-2"></i>
                            <strong>Backup Settings:</strong> Configure automatic backup frequency and retention policies.
                        </div>

                        <div class="setting-group">
                            <div class="setting-group-title">Backup Configuration</div>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" name="backup_enabled" id="backup_enabled" 
                                               value="1" {{ ($settings['backup']['backup_enabled']->value ?? false) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="backup_enabled">Enable Automatic Backups</label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Backup Frequency</label>
                                    <select name="backup_frequency" class="form-select">
                                        <option value="daily" {{ ($settings['backup']['backup_frequency']->value ?? 'daily') == 'daily' ? 'selected' : '' }}>Daily</option>
                                        <option value="weekly" {{ ($settings['backup']['backup_frequency']->value ?? '') == 'weekly' ? 'selected' : '' }}>Weekly</option>
                                        <option value="monthly" {{ ($settings['backup']['backup_frequency']->value ?? '') == 'monthly' ? 'selected' : '' }}>Monthly</option>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Retention Period (days)</label>
                                    <input type="number" name="backup_retention_days" class="form-control" 
                                           value="{{ $settings['backup']['backup_retention_days']->value ?? 30 }}" 
                                           min="1" max="365">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Storage Driver</label>
                                    <select name="backup_storage_driver" class="form-select">
                                        <option value="local" {{ ($settings['backup']['backup_storage_driver']->value ?? 'local') == 'local' ? 'selected' : '' }}>Local Storage</option>
                                        <option value="s3" {{ ($settings['backup']['backup_storage_driver']->value ?? '') == 's3' ? 'selected' : '' }}>Amazon S3</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-end gap-2 mt-4">
                            <button type="reset" class="btn btn-label-secondary">Reset</button>
                            <button type="submit" class="btn btn-primary">
                                <i class="ri-save-line me-1"></i>Save Backup Settings
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Logging Settings Tab -->
                <div class="tab-pane fade" id="logging-tab" role="tabpanel">
                    <form id="loggingForm" class="settings-form">
                        @csrf
                        <input type="hidden" name="group" value="logging">
                        
                        <div class="info-card">
                            <i class="ri-information-line me-2"></i>
                            <strong>Logging Settings:</strong> Configure log levels, rotation, and notification settings.
                        </div>

                        <div class="setting-group">
                            <div class="setting-group-title">Log Configuration</div>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label">Log Level</label>
                                    <select name="log_level" class="form-select">
                                        <option value="debug" {{ ($settings['logging']['log_level']->value ?? 'info') == 'debug' ? 'selected' : '' }}>Debug</option>
                                        <option value="info" {{ ($settings['logging']['log_level']->value ?? 'info') == 'info' ? 'selected' : '' }}>Info</option>
                                        <option value="warning" {{ ($settings['logging']['log_level']->value ?? '') == 'warning' ? 'selected' : '' }}>Warning</option>
                                        <option value="error" {{ ($settings['logging']['log_level']->value ?? '') == 'error' ? 'selected' : '' }}>Error</option>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Max Log Files</label>
                                    <input type="number" name="log_max_files" class="form-control" 
                                           value="{{ $settings['logging']['log_max_files']->value ?? 5 }}" 
                                           min="1" max="100">
                                </div>
                                <div class="col-md-6">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" name="log_enable_daily" id="log_enable_daily" 
                                               value="1" {{ ($settings['logging']['log_enable_daily']->value ?? true) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="log_enable_daily">Enable Daily Log Rotation</label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" name="log_enable_slack" id="log_enable_slack" 
                                               value="1" {{ ($settings['logging']['log_enable_slack']->value ?? false) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="log_enable_slack">Enable Slack Notifications</label>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <label class="form-label">Slack Webhook URL</label>
                                    <input type="url" name="log_slack_webhook" class="form-control" 
                                           value="{{ $settings['logging']['log_slack_webhook']->value ?? '' }}" 
                                           placeholder="https://hooks.slack.com/services/...">
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-end gap-2 mt-4">
                            <button type="reset" class="btn btn-label-secondary">Reset</button>
                            <button type="submit" class="btn btn-primary">
                                <i class="ri-save-line me-1"></i>Save Logging Settings
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
// Handle form submissions for all tabs
document.querySelectorAll('.settings-form').forEach(form => {
    form.addEventListener('submit', async function(e) {
        e.preventDefault();
        const form = this;
        const formData = new FormData(form);
        const submitBtn = form.querySelector('button[type="submit"]');
        const originalText = submitBtn.innerHTML;
        
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="ri-loader-4-line spin me-1"></i>Saving...';

        // Clear previous errors
        form.querySelectorAll('.is-invalid').forEach(el => el.classList.remove('is-invalid'));
        form.querySelectorAll('.invalid-feedback').forEach(el => el.remove());

        try {
            const response = await fetch('{{ route('admin.settings.system.update') }}', {
                method: 'PUT',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json',
                },
                body: formData
            });

            const data = await response.json();

            if (response.ok && data.success) {
                // Show success message
                if (typeof showSuccessToast === 'function') {
                    showSuccessToast(data.message || 'Settings saved successfully!', 'Success');
                } else {
                    alert(data.message || 'Settings saved successfully!');
                }
            } else {
                // Handle validation errors
                if (data.errors) {
                    Object.keys(data.errors).forEach(field => {
                        const input = form.querySelector(`[name="${field}"]`);
                        if (input) {
                            input.classList.add('is-invalid');
                            const errorDiv = document.createElement('div');
                            errorDiv.className = 'invalid-feedback';
                            errorDiv.textContent = Array.isArray(data.errors[field]) 
                                ? data.errors[field][0] 
                                : data.errors[field];
                            input.parentNode.appendChild(errorDiv);
                        }
                    });
                }

                if (typeof showErrorToast === 'function') {
                    showErrorToast(data.message || 'Failed to save settings', 'Error');
                } else {
                    alert(data.message || 'Failed to save settings');
                }
            }
        } catch (error) {
            console.error('Error:', error);
            if (typeof showErrorToast === 'function') {
                showErrorToast('An error occurred. Please try again.', 'Error');
            } else {
                alert('An error occurred. Please try again.');
            }
        } finally {
            submitBtn.disabled = false;
            submitBtn.innerHTML = originalText;
        }
    });
});

// Add spin animation
const style = document.createElement('style');
style.textContent = `
    .spin {
        animation: spin 1s linear infinite;
        display: inline-block;
    }
    @keyframes spin {
        from { transform: rotate(0deg); }
        to { transform: rotate(360deg); }
    }
`;
document.head.appendChild(style);
</script>
@endpush
