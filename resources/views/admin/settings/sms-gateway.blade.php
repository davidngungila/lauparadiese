@extends('admin.layouts.app')

@section('title', 'SMS Gateway Settings - Advanced Management')

@push('styles')
<style>
    .provider-card {
        transition: all 0.3s;
        border: 2px solid #e7e9ec;
    }
    .provider-card:hover {
        box-shadow: 0 4px 12px rgba(62, 165, 114, 0.15);
        border-color: #3ea572;
    }
    .provider-card.primary {
        border-color: #3ea572;
        background: linear-gradient(135deg, #e6f4ed 0%, #ffffff 100%);
    }
    .provider-card.inactive {
        opacity: 0.6;
        background: #f8f9fa;
    }
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
    .connection-indicator.connected {
        background-color: #71dd37;
    }
    .connection-indicator.disconnected {
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
        padding: 0.5rem;
        border-radius: 0.25rem;
        font-size: 0.875rem;
    }
    .test-result.success {
        background-color: #d4edda;
        color: #155724;
    }
    .test-result.error {
        background-color: #f8d7da;
        color: #721c24;
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
    .provider-table th {
        background-color: #f8f9fa;
        font-weight: 600;
        color: #566a7f;
    }
    .provider-table td {
        vertical-align: middle;
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
                        <i class="ri-message-3-line me-2"></i>SMS Gateway Management
                    </h4>
                    <p class="text-muted mb-0">Manage multiple SMS providers, test connections, and configure settings</p>
                </div>
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addProviderModal">
                    <i class="ri-add-line me-1"></i>Add New Provider
                </button>
            </div>
        </div>
    </div>

    <!-- Tabs Navigation -->
    <div class="card">
        <div class="card-header border-bottom">
            <ul class="nav nav-tabs card-header-tabs" role="tablist">
                <li class="nav-item">
                    <button type="button" class="nav-link active" data-bs-toggle="tab" data-bs-target="#providers-tab" role="tab" aria-selected="true">
                        <i class="ri-list-check me-1"></i>Providers
                        <span class="badge bg-label-primary ms-2">{{ $providers->count() }}</span>
                    </button>
                </li>
                <li class="nav-item">
                    <button type="button" class="nav-link" data-bs-toggle="tab" data-bs-target="#settings-tab" role="tab" aria-selected="false">
                        <i class="ri-settings-3-line me-1"></i>Fallback Settings
                    </button>
                </li>
                <li class="nav-item">
                    <button type="button" class="nav-link" data-bs-toggle="tab" data-bs-target="#test-tab" role="tab" aria-selected="false">
                        <i class="ri-flask-line me-1"></i>Quick Test
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
                <!-- Providers Tab -->
                <div class="tab-pane fade show active" id="providers-tab" role="tabpanel">
                    <div class="row g-4" id="providersContainer">
                        @forelse($providers as $provider)
                        <div class="col-md-6 col-lg-4">
                            <div class="card provider-card {{ $provider->is_primary ? 'primary' : '' }} {{ !$provider->is_active ? 'inactive' : '' }}" data-provider-id="{{ $provider->id }}">
                                <div class="card-header d-flex justify-content-between align-items-center">
                                    <div class="d-flex align-items-center gap-2">
                                        @if($provider->is_primary)
                                        <span class="badge bg-label-success">
                                            <i class="ri-star-fill me-1"></i>Primary
                                        </span>
                                        @endif
                                        @if(!$provider->is_active)
                                        <span class="badge bg-label-secondary">Inactive</span>
                                        @endif
                                    </div>
                                    <div class="dropdown">
                                        <button class="btn btn-sm btn-icon" type="button" data-bs-toggle="dropdown">
                                            <i class="ri-more-2-line"></i>
                                        </button>
                                        <ul class="dropdown-menu">
                                            <li>
                                                <a class="dropdown-item" href="#" onclick="editProvider({{ $provider->id }})">
                                                    <i class="ri-edit-line me-2"></i>Edit
                                                </a>
                                            </li>
                                            @if(!$provider->is_primary)
                                            <li>
                                                <a class="dropdown-item" href="#" onclick="setPrimary({{ $provider->id }})">
                                                    <i class="ri-star-line me-2"></i>Set as Primary
                                                </a>
                                            </li>
                                            @endif
                                            <li>
                                                <a class="dropdown-item" href="#" onclick="toggleActive({{ $provider->id }})">
                                                    <i class="ri-{{ $provider->is_active ? 'eye-off' : 'eye' }}-line me-2"></i>
                                                    {{ $provider->is_active ? 'Deactivate' : 'Activate' }}
                                                </a>
                                            </li>
                                            <li><hr class="dropdown-divider"></li>
                                            <li>
                                                <a class="dropdown-item text-danger" href="#" onclick="deleteProvider({{ $provider->id }}, '{{ $provider->name }}')">
                                                    <i class="ri-delete-bin-line me-2"></i>Delete
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <h5 class="card-title mb-3">{{ $provider->name }}</h5>
                                    
                                    <!-- Connection Status -->
                                    <div class="mb-3">
                                        <div class="d-flex justify-content-between align-items-center mb-2">
                                            <span class="text-muted small">Connection Status</span>
                                            <span class="status-badge {{ $provider->getStatusBadgeClass() }}">
                                                <span class="connection-indicator {{ $provider->connection_status }}"></span>
                                                <span class="text-capitalize">{{ $provider->connection_status }}</span>
                                            </span>
                                        </div>
                                        @if($provider->last_tested_at)
                                        <small class="text-muted">
                                            Last tested: {{ $provider->last_tested_at->diffForHumans() }}
                                        </small>
                                        @endif
                                    </div>

                                    <!-- Provider Details -->
                                    <div class="mb-3">
                                        <div class="row g-2 small">
                                            <div class="col-12">
                                                <div class="d-flex justify-content-between">
                                                    <span class="text-muted">From:</span>
                                                    <span class="fw-semibold">{{ $provider->sms_from }}</span>
                                                </div>
                                            </div>
                                            <div class="col-12">
                                                <div class="d-flex justify-content-between">
                                                    <span class="text-muted">Method:</span>
                                                    <span class="badge bg-label-info text-uppercase">{{ $provider->sms_method }}</span>
                                                </div>
                                            </div>
                                            <div class="col-12">
                                                <div class="d-flex justify-content-between">
                                                    <span class="text-muted">Priority:</span>
                                                    <span class="fw-semibold">{{ $provider->priority }}</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Actions -->
                                    <div class="d-flex gap-2">
                                        <button class="btn btn-sm btn-outline-primary flex-fill" onclick="testConnection({{ $provider->id }})">
                                            <i class="ri-wifi-line me-1"></i>Test Connection
                                        </button>
                                        <button class="btn btn-sm btn-outline-success flex-fill" onclick="testSms({{ $provider->id }})">
                                            <i class="ri-send-plane-line me-1"></i>Test SMS
                                        </button>
                                    </div>

                                    <!-- Test Result -->
                                    <div id="testResult{{ $provider->id }}" class="test-result" style="display: none;"></div>
                                </div>
                            </div>
                        </div>
                        @empty
                        <div class="col-12">
                            <div class="card">
                                <div class="card-body text-center py-5">
                                    <i class="ri-inbox-line" style="font-size: 3rem; color: #a1acb8;"></i>
                                    <h5 class="mt-3 mb-2">No SMS Providers</h5>
                                    <p class="text-muted mb-4">Get started by adding your first SMS provider</p>
                                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addProviderModal">
                                        <i class="ri-add-line me-1"></i>Add Provider
                                    </button>
                                </div>
                            </div>
                        </div>
                        @endforelse
                    </div>
                </div>

                <!-- Settings Tab -->
                <div class="tab-pane fade" id="settings-tab" role="tabpanel">
                    <div class="row">
                        <div class="col-12">
                            <div class="alert alert-info">
                                <i class="ri-information-line me-2"></i>
                                <strong>Fallback Settings:</strong> These settings are used when no NotificationProvider is configured or as fallback values.
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="mb-0">System Settings (Database)</h5>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-borderless">
                                            <tbody>
                                                <tr>
                                                    <td><strong>Username:</strong></td>
                                                    <td>{{ $fallbackSettings['sms_username'] ?: 'Not set' }}</td>
                                                </tr>
                                                <tr>
                                                    <td><strong>From:</strong></td>
                                                    <td>{{ $fallbackSettings['sms_from'] ?: 'Not set' }}</td>
                                                </tr>
                                                <tr>
                                                    <td><strong>URL:</strong></td>
                                                    <td>
                                                        <small class="text-muted">{{ $fallbackSettings['sms_url'] ?: 'Not set' }}</small>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                    <p class="text-muted small mb-0">
                                        <i class="ri-information-line me-1"></i>
                                        These values come from the <code>system_settings</code> table.
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="mb-0">Environment Variables</h5>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-borderless">
                                            <tbody>
                                                <tr>
                                                    <td><strong>SMS_USERNAME:</strong></td>
                                                    <td>{{ env('SMS_USERNAME') ? '***' . substr(env('SMS_USERNAME'), -4) : 'Not set' }}</td>
                                                </tr>
                                                <tr>
                                                    <td><strong>SMS_FROM:</strong></td>
                                                    <td>{{ env('SMS_FROM') ?: 'Not set' }}</td>
                                                </tr>
                                                <tr>
                                                    <td><strong>SMS_URL:</strong></td>
                                                    <td>
                                                        <small class="text-muted">{{ env('SMS_URL') ?: 'Not set' }}</small>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                    <p class="text-muted small mb-0">
                                        <i class="ri-information-line me-1"></i>
                                        These values come from your <code>.env</code> file.
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 mt-3">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="mb-0">Fallback Priority Order</h5>
                                </div>
                                <div class="card-body">
                                    <ol class="mb-0">
                                        <li><strong>NotificationProvider</strong> (Primary provider from database) - <span class="text-success">Highest Priority</span></li>
                                        <li><strong>SystemSetting</strong> (Database settings table)</li>
                                        <li><strong>Environment Variables</strong> (.env file)</li>
                                        <li><strong>Hardcoded Defaults</strong> - <span class="text-muted">Lowest Priority</span></li>
                                    </ol>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Quick Test Tab -->
                <div class="tab-pane fade" id="test-tab" role="tabpanel">
                    <div class="row">
                        <div class="col-md-8">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="mb-0">
                                        <i class="ri-flask-line me-2"></i>Quick SMS Test
                                    </h5>
                                </div>
                                <div class="card-body">
                                    <form id="quickTestForm">
                                        @csrf
                                        <div class="mb-3">
                                            <label class="form-label">Select Provider</label>
                                            <select class="form-select" id="quickTestProvider" name="provider_id">
                                                <option value="">Use Primary Provider</option>
                                                @foreach($providers->where('is_active', true) as $provider)
                                                <option value="{{ $provider->id }}" {{ $provider->is_primary ? 'selected' : '' }}>
                                                    {{ $provider->name }} {{ $provider->is_primary ? '(Primary)' : '' }}
                                                </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Phone Number <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" id="quickTestPhone" name="phone" required placeholder="255712345678">
                                            <small class="text-muted">Format: 255XXXXXXXXX (Tanzania)</small>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Message <span class="text-danger">*</span></label>
                                            <textarea class="form-control" id="quickTestMessage" name="message" rows="4" maxlength="160" required>Test message from Lau Paradise Adventures SMS Gateway</textarea>
                                            <small class="text-muted"><span id="quickCharCount">0</span>/160 characters</small>
                                        </div>
                                        <button type="submit" class="btn btn-primary">
                                            <i class="ri-send-plane-line me-1"></i>Send Test SMS
                                        </button>
                                    </form>
                                    <div id="quickTestResult" class="mt-3" style="display: none;"></div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="mb-0">Test All Connections</h5>
                                </div>
                                <div class="card-body">
                                    <p class="text-muted small">Test connection status for all active providers.</p>
                                    <button type="button" class="btn btn-outline-primary w-100" onclick="testAllConnections()">
                                        <i class="ri-wifi-line me-1"></i>Test All
                                    </button>
                                    <div id="testAllResults" class="mt-3"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Information Tab -->
                <div class="tab-pane fade" id="info-tab" role="tabpanel">
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="mb-0">
                                        <i class="ri-information-line me-2"></i>How It Works
                                    </h5>
                                </div>
                                <div class="card-body">
                                    <div class="row g-4">
                                        <div class="col-md-6">
                                            <h6 class="mb-3"><i class="ri-star-line me-2 text-warning"></i>Primary Provider</h6>
                                            <p class="text-muted">The primary provider is used by default for all SMS notifications. Only one provider can be primary at a time.</p>
                                        </div>
                                        <div class="col-md-6">
                                            <h6 class="mb-3"><i class="ri-wifi-line me-2 text-primary"></i>Connection Status</h6>
                                            <p class="text-muted">Test the connection to verify your provider credentials are correct. Status is updated automatically after testing.</p>
                                        </div>
                                        <div class="col-md-6">
                                            <h6 class="mb-3"><i class="ri-sort-asc me-2 text-info"></i>Priority System</h6>
                                            <p class="text-muted">Lower numbers have higher priority. If the primary provider fails, the system will try providers in priority order (0, 1, 2, etc.).</p>
                                        </div>
                                        <div class="col-md-6">
                                            <h6 class="mb-3"><i class="ri-send-plane-line me-2 text-success"></i>Test SMS</h6>
                                            <p class="text-muted">Send a test message to verify the provider is working correctly. Use this before making a provider primary.</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 mt-3">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="mb-0">Provider Configuration</h5>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-bordered">
                                            <thead>
                                                <tr>
                                                    <th>Field</th>
                                                    <th>Description</th>
                                                    <th>Required</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td><strong>Provider Name</strong></td>
                                                    <td>A friendly name to identify this provider</td>
                                                    <td><span class="badge bg-label-danger">Yes</span></td>
                                                </tr>
                                                <tr>
                                                    <td><strong>Username/API Key</strong></td>
                                                    <td>Your SMS gateway username or API key</td>
                                                    <td><span class="badge bg-label-danger">Yes</span></td>
                                                </tr>
                                                <tr>
                                                    <td><strong>Password/API Secret</strong></td>
                                                    <td>Your SMS gateway password or API secret</td>
                                                    <td><span class="badge bg-label-danger">Yes</span></td>
                                                </tr>
                                                <tr>
                                                    <td><strong>From/Sender ID</strong></td>
                                                    <td>The sender ID that will appear on SMS messages</td>
                                                    <td><span class="badge bg-label-danger">Yes</span></td>
                                                </tr>
                                                <tr>
                                                    <td><strong>API URL</strong></td>
                                                    <td>The endpoint URL for sending SMS messages</td>
                                                    <td><span class="badge bg-label-danger">Yes</span></td>
                                                </tr>
                                                <tr>
                                                    <td><strong>HTTP Method</strong></td>
                                                    <td>POST (recommended) or GET method for API calls</td>
                                                    <td><span class="badge bg-label-danger">Yes</span></td>
                                                </tr>
                                                <tr>
                                                    <td><strong>Priority</strong></td>
                                                    <td>Fallback priority (0 = highest, 100 = lowest)</td>
                                                    <td><span class="badge bg-label-secondary">Optional</span></td>
                                                </tr>
                                            </tbody>
                                        </table>
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

<!-- Add/Edit Provider Modal -->
<div class="modal fade" id="addProviderModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalTitle">Add New SMS Provider</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="providerForm">
                @csrf
                <input type="hidden" id="providerId" name="provider_id">
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-12">
                            <label class="form-label">Provider Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="providerName" name="name" required placeholder="e.g., Messaging Service, Twilio, etc.">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Username/API Key <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="smsUsername" name="sms_username" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Password/API Secret <span class="text-danger">*</span></label>
                            <input type="password" class="form-control" id="smsPassword" name="sms_password" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">From/Sender ID <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="smsFrom" name="sms_from" required placeholder="e.g., OfisiLink">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">API URL <span class="text-danger">*</span></label>
                            <input type="url" class="form-control" id="smsUrl" name="sms_url" required placeholder="https://api.example.com/sms">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">HTTP Method <span class="text-danger">*</span></label>
                            <select class="form-select" id="smsMethod" name="sms_method" required>
                                <option value="post">POST</option>
                                <option value="get">GET</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Priority</label>
                            <input type="number" class="form-control" id="priority" name="priority" value="0" min="0" max="100">
                            <small class="text-muted">Lower number = higher priority</small>
                        </div>
                        <div class="col-md-12">
                            <label class="form-label">Notes</label>
                            <textarea class="form-control" id="notes" name="notes" rows="2" placeholder="Optional notes about this provider"></textarea>
                        </div>
                        <div class="col-md-6">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="isPrimary" name="is_primary">
                                <label class="form-check-label" for="isPrimary">Set as Primary Provider</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="isActive" name="is_active" checked>
                                <label class="form-check-label" for="isActive">Active</label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="ri-save-line me-1"></i>Save Provider
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Test SMS Modal -->
<div class="modal fade" id="testSmsModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Send Test SMS</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="testSmsForm">
                @csrf
                <input type="hidden" id="testProviderId" name="provider_id">
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Phone Number <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="testPhone" name="phone" required placeholder="255712345678">
                        <small class="text-muted">Format: 255XXXXXXXXX (Tanzania)</small>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Message <span class="text-danger">*</span></label>
                        <textarea class="form-control" id="testMessage" name="message" rows="3" maxlength="160" required>Test message from Lau Paradise Adventures SMS Gateway</textarea>
                        <small class="text-muted"><span id="charCount">0</span>/160 characters</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="ri-send-plane-line me-1"></i>Send Test SMS
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
const providers = @json($providers ?? []);

// Provider Form Submission
document.getElementById('providerForm').addEventListener('submit', async function(e) {
    e.preventDefault();
    const form = this;
    const formData = new FormData(form);
    
    // Handle checkboxes (they don't send values when unchecked)
    formData.set('is_primary', document.getElementById('isPrimary').checked ? '1' : '0');
    formData.set('is_active', document.getElementById('isActive').checked ? '1' : '0');
    
    const providerId = document.getElementById('providerId').value;
    const url = providerId 
        ? `{{ route('admin.settings.sms-gateway.update', ':id') }}`.replace(':id', providerId)
        : '{{ route('admin.settings.sms-gateway.store') }}';
    const method = providerId ? 'PUT' : 'POST';
    
    // Get submit button
    const submitBtn = form.querySelector('button[type="submit"]');
    const originalText = submitBtn.innerHTML;
    submitBtn.disabled = true;
    submitBtn.innerHTML = '<i class="ri-loader-4-line spin me-1"></i>Saving...';

    // Clear previous errors
    form.querySelectorAll('.is-invalid').forEach(el => el.classList.remove('is-invalid'));
    form.querySelectorAll('.invalid-feedback').forEach(el => el.remove());

    try {
        const response = await fetch(url, {
            method: method,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json',
            },
            body: formData
        });

        const contentType = response.headers.get('content-type');
        const isJson = contentType && contentType.includes('application/json');

        if (response.ok) {
            let data;
            if (isJson) {
                data = await response.json();
            } else {
                // Handle redirect response
                const text = await response.text();
                if (text.includes('success') || response.redirected) {
                    // Show success message
                    if (typeof showSuccessToast === 'function') {
                        showSuccessToast(providerId ? 'Provider updated successfully!' : 'Provider created successfully!', 'Success');
                    } else {
                        alert(providerId ? 'Provider updated successfully!' : 'Provider created successfully!');
                    }
                    setTimeout(() => location.reload(), 1000);
                    return;
                }
            }

            if (data && data.success !== false) {
                // Show success message
                if (typeof showSuccessToast === 'function') {
                    showSuccessToast(data.message || (providerId ? 'Provider updated successfully!' : 'Provider created successfully!'), 'Success');
                } else {
                    alert(data.message || (providerId ? 'Provider updated successfully!' : 'Provider created successfully!'));
                }
                
                // Close modal and reload
                const modal = bootstrap.Modal.getInstance(document.getElementById('addProviderModal'));
                if (modal) modal.hide();
                setTimeout(() => location.reload(), 1000);
            } else {
                throw new Error(data.message || 'Failed to save provider');
            }
        } else {
            // Handle validation errors
            let errorMessage = 'Failed to save provider';
            let validationErrors = {};

            if (isJson) {
                const data = await response.json();
                errorMessage = data.message || errorMessage;
                
                if (data.errors) {
                    validationErrors = data.errors;
                    // Display validation errors
                    Object.keys(validationErrors).forEach(field => {
                        const input = form.querySelector(`[name="${field}"]`);
                        if (input) {
                            input.classList.add('is-invalid');
                            const errorDiv = document.createElement('div');
                            errorDiv.className = 'invalid-feedback';
                            errorDiv.textContent = Array.isArray(validationErrors[field]) 
                                ? validationErrors[field][0] 
                                : validationErrors[field];
                            input.parentNode.appendChild(errorDiv);
                        }
                    });
                }
            } else {
                const text = await response.text();
                // Try to extract error from HTML response
                const errorMatch = text.match(/<div[^>]*class="[^"]*error[^"]*"[^>]*>([^<]+)<\/div>/i);
                if (errorMatch) {
                    errorMessage = errorMatch[1];
                }
            }

            // Show error message
            if (typeof showErrorToast === 'function') {
                showErrorToast(errorMessage, 'Error');
            } else {
                alert(errorMessage);
            }
        }
    } catch (error) {
        console.error('Error:', error);
        const errorMessage = error.message || 'An error occurred. Please try again.';
        
        if (typeof showErrorToast === 'function') {
            showErrorToast(errorMessage, 'Error');
        } else {
            alert(errorMessage);
        }
    } finally {
        submitBtn.disabled = false;
        submitBtn.innerHTML = originalText;
    }
});

// Test Connection
async function testConnection(providerId) {
    const resultDiv = document.getElementById(`testResult${providerId}`);
    resultDiv.style.display = 'block';
    resultDiv.className = 'test-result';
    resultDiv.innerHTML = '<i class="ri-loader-4-line spin"></i> Testing connection...';

    try {
        const response = await fetch(`{{ route('admin.settings.sms-gateway.test-connection', ':id') }}`.replace(':id', providerId), {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json',
            }
        });

        const data = await response.json();
        
        resultDiv.className = `test-result ${data.success ? 'success' : 'error'}`;
        resultDiv.innerHTML = `<i class="ri-${data.success ? 'check' : 'close'}-line me-1"></i>${data.message}`;
        
        if (data.success) {
            setTimeout(() => location.reload(), 2000);
        }
    } catch (error) {
        resultDiv.className = 'test-result error';
        resultDiv.innerHTML = `<i class="ri-error-warning-line me-1"></i>Test failed: ${error.message}`;
    }
}

// Test All Connections
async function testAllConnections() {
    const resultsDiv = document.getElementById('testAllResults');
    resultsDiv.innerHTML = '<div class="text-center"><i class="ri-loader-4-line spin"></i> Testing all connections...</div>';
    
    const activeProviders = providers.filter(p => p.is_active);
    let results = [];
    
    for (const provider of activeProviders) {
        try {
            const response = await fetch(`{{ route('admin.settings.sms-gateway.test-connection', ':id') }}`.replace(':id', provider.id), {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json',
                }
            });
            const data = await response.json();
            results.push({
                name: provider.name,
                success: data.success,
                message: data.message
            });
        } catch (error) {
            results.push({
                name: provider.name,
                success: false,
                message: 'Test failed: ' + error.message
            });
        }
    }
    
    let html = '<div class="list-group">';
    results.forEach(result => {
        html += `<div class="list-group-item">
            <div class="d-flex justify-content-between align-items-center">
                <span><strong>${result.name}</strong></span>
                <span class="badge ${result.success ? 'bg-label-success' : 'bg-label-danger'}">
                    ${result.success ? 'Connected' : 'Failed'}
                </span>
            </div>
            <small class="text-muted">${result.message}</small>
        </div>`;
    });
    html += '</div>';
    
    resultsDiv.innerHTML = html;
}

// Test SMS
function testSms(providerId) {
    document.getElementById('testProviderId').value = providerId;
    const modal = new bootstrap.Modal(document.getElementById('testSmsModal'));
    modal.show();
}

// Test SMS Form
document.getElementById('testSmsForm').addEventListener('submit', async function(e) {
    e.preventDefault();
    const formData = new FormData(this);
    const submitBtn = this.querySelector('button[type="submit"]');
    const originalText = submitBtn.innerHTML;
    submitBtn.disabled = true;
    submitBtn.innerHTML = '<i class="ri-loader-4-line spin me-1"></i>Sending...';

    try {
        const response = await fetch('{{ route('admin.settings.sms-gateway.test') }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json',
            },
            body: formData
        });

        const data = await response.json();
        
        if (data.success) {
            alert('Test SMS sent successfully!');
            bootstrap.Modal.getInstance(document.getElementById('testSmsModal')).hide();
            this.reset();
        } else {
            alert('Failed to send test SMS: ' + data.message);
        }
    } catch (error) {
        alert('An error occurred: ' + error.message);
    } finally {
        submitBtn.disabled = false;
        submitBtn.innerHTML = originalText;
    }
});

// Quick Test Form
document.getElementById('quickTestForm').addEventListener('submit', async function(e) {
    e.preventDefault();
    const formData = new FormData(this);
    const submitBtn = this.querySelector('button[type="submit"]');
    const originalText = submitBtn.innerHTML;
    submitBtn.disabled = true;
    submitBtn.innerHTML = '<i class="ri-loader-4-line spin me-1"></i>Sending...';
    const resultDiv = document.getElementById('quickTestResult');
    resultDiv.style.display = 'none';

    try {
        const response = await fetch('{{ route('admin.settings.sms-gateway.test') }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json',
            },
            body: formData
        });

        const data = await response.json();
        
        resultDiv.style.display = 'block';
        resultDiv.className = `alert ${data.success ? 'alert-success' : 'alert-danger'}`;
        resultDiv.innerHTML = `<i class="ri-${data.success ? 'check' : 'close'}-line me-1"></i>${data.message}`;
        
        if (data.success) {
            this.reset();
            document.getElementById('quickCharCount').textContent = '0';
        }
    } catch (error) {
        resultDiv.style.display = 'block';
        resultDiv.className = 'alert alert-danger';
        resultDiv.innerHTML = `<i class="ri-error-warning-line me-1"></i>An error occurred: ${error.message}`;
    } finally {
        submitBtn.disabled = false;
        submitBtn.innerHTML = originalText;
    }
});

// Set Primary
async function setPrimary(providerId) {
    if (!confirm('Set this provider as primary? The current primary will be unset.')) return;

    try {
        const response = await fetch(`{{ route('admin.settings.sms-gateway.set-primary', ':id') }}`.replace(':id', providerId), {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json',
            }
        });

        const data = await response.json();
        
        if (data.success) {
            location.reload();
        } else {
            alert(data.message || 'Failed to set primary provider');
        }
    } catch (error) {
        alert('An error occurred: ' + error.message);
    }
}

// Toggle Active
async function toggleActive(providerId) {
    try {
        const response = await fetch(`{{ route('admin.settings.sms-gateway.toggle-active', ':id') }}`.replace(':id', providerId), {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json',
            }
        });

        const data = await response.json();
        
        if (data.success) {
            location.reload();
        } else {
            alert(data.message || 'Failed to update provider status');
        }
    } catch (error) {
        alert('An error occurred: ' + error.message);
    }
}

// Delete Provider
async function deleteProvider(providerId, providerName) {
    if (!confirm(`Are you sure you want to delete "${providerName}"? This action cannot be undone.`)) return;

    try {
        const response = await fetch(`{{ route('admin.settings.sms-gateway.destroy', ':id') }}`.replace(':id', providerId), {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json',
            }
        });

        if (response.ok || response.redirected) {
            location.reload();
        } else {
            const data = await response.json();
            alert(data.message || 'Failed to delete provider');
        }
    } catch (error) {
        alert('An error occurred: ' + error.message);
    }
}

// Edit Provider
function editProvider(providerId) {
    const provider = providers.find(p => p.id == providerId);
    if (!provider) return;

    document.getElementById('modalTitle').textContent = 'Edit SMS Provider';
    document.getElementById('providerId').value = provider.id;
    document.getElementById('providerName').value = provider.name;
    document.getElementById('smsUsername').value = provider.sms_username;
    document.getElementById('smsPassword').value = provider.sms_password;
    document.getElementById('smsFrom').value = provider.sms_from;
    document.getElementById('smsUrl').value = provider.sms_url;
    document.getElementById('smsMethod').value = provider.sms_method;
    document.getElementById('priority').value = provider.priority;
    document.getElementById('notes').value = provider.notes || '';
    document.getElementById('isPrimary').checked = provider.is_primary;
    document.getElementById('isActive').checked = provider.is_active;

    const modal = new bootstrap.Modal(document.getElementById('addProviderModal'));
    modal.show();
}

// Reset form when modal is closed
document.getElementById('addProviderModal').addEventListener('hidden.bs.modal', function() {
    document.getElementById('providerForm').reset();
    document.getElementById('providerId').value = '';
    document.getElementById('modalTitle').textContent = 'Add New SMS Provider';
});

// Character count for test messages
document.getElementById('testMessage').addEventListener('input', function() {
    document.getElementById('charCount').textContent = this.value.length;
});

document.getElementById('quickTestMessage').addEventListener('input', function() {
    document.getElementById('quickCharCount').textContent = this.value.length;
});
</script>
@endpush
