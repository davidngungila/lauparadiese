@extends('admin.layouts.app')

@section('title', 'Payment Gateway Settings - Advanced Management')

@push('styles')
<style>
    .gateway-card {
        transition: all 0.3s;
        border: 2px solid #e7e9ec;
    }
    .gateway-card:hover {
        box-shadow: 0 4px 12px rgba(62, 165, 114, 0.15);
        border-color: #3ea572;
    }
    .gateway-card.primary {
        border-color: #3ea572;
        background: linear-gradient(135deg, #e6f4ed 0%, #ffffff 100%);
    }
    .gateway-card.inactive {
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
    .connection-indicator.active {
        background-color: #71dd37;
    }
    .connection-indicator.inactive {
        background-color: #ff3e1d;
    }
    .connection-indicator.pending {
        background-color: #ffab00;
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
    .spin {
        animation: spin 1s linear infinite;
        display: inline-block;
    }
    @keyframes spin {
        from { transform: rotate(0deg); }
        to { transform: rotate(360deg); }
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
                        <i class="ri-bank-card-line me-2"></i>Payment Gateway Management
                    </h4>
                    <p class="text-muted mb-0">Manage multiple payment gateways and configure primary settings</p>
                </div>
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addGatewayModal">
                    <i class="ri-add-line me-1"></i>Add New Gateway
                </button>
            </div>
        </div>
    </div>

    <!-- Gateways Grid -->
    <div class="row g-4 mb-4" id="gatewaysContainer">
        @forelse($gateways as $gateway)
        <div class="col-md-6 col-lg-4">
            <div class="card gateway-card {{ $gateway->is_primary ? 'primary' : '' }} {{ !$gateway->is_active ? 'inactive' : '' }}" data-gateway-id="{{ $gateway->id }}">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <div class="d-flex align-items-center gap-2">
                        @if($gateway->is_primary)
                        <span class="badge bg-label-success">
                            <i class="ri-star-fill me-1"></i>Primary
                        </span>
                        @endif
                        @if($gateway->is_test_mode)
                        <span class="badge bg-label-warning">Test Mode</span>
                        @endif
                        @if(!$gateway->is_active)
                        <span class="badge bg-label-secondary">Inactive</span>
                        @endif
                    </div>
                    <div class="dropdown">
                        <button class="btn btn-sm btn-icon" type="button" data-bs-toggle="dropdown">
                            <i class="ri-more-2-line"></i>
                        </button>
                        <ul class="dropdown-menu">
                            <li>
                                <a class="dropdown-item" href="#" onclick="editGateway({{ $gateway->id }})">
                                    <i class="ri-edit-line me-2"></i>Edit
                                </a>
                            </li>
                            @if(!$gateway->is_primary)
                            <li>
                                <a class="dropdown-item" href="#" onclick="setPrimary({{ $gateway->id }})">
                                    <i class="ri-star-line me-2"></i>Set as Primary
                                </a>
                            </li>
                            @endif
                            <li>
                                <a class="dropdown-item" href="#" onclick="toggleActive({{ $gateway->id }})">
                                    <i class="ri-{{ $gateway->is_active ? 'eye-off' : 'eye' }}-line me-2"></i>
                                    {{ $gateway->is_active ? 'Deactivate' : 'Activate' }}
                                </a>
                            </li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <a class="dropdown-item text-danger" href="#" onclick="deleteGateway({{ $gateway->id }}, '{{ $gateway->display_name }}')">
                                    <i class="ri-delete-bin-line me-2"></i>Delete
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="card-body">
                    <h5 class="card-title mb-3">
                        <i class="ri-{{ $gateway->name === 'stripe' ? 'stripe' : 'paypal' }}-line me-2"></i>
                        {{ $gateway->display_name }}
                    </h5>
                    
                    <!-- Connection Status -->
                    <div class="mb-3">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="text-muted small">Status</span>
                            <span class="status-badge {{ $gateway->getStatusBadgeClass() }}">
                                <span class="connection-indicator {{ $gateway->status }}"></span>
                                <span class="text-capitalize">{{ $gateway->status }}</span>
                            </span>
                        </div>
                    </div>

                    <!-- Gateway Details -->
                    <div class="mb-3">
                        <div class="row g-2 small">
                            <div class="col-12">
                                <div class="d-flex justify-content-between">
                                    <span class="text-muted">Currency:</span>
                                    <span class="fw-semibold">{{ $gateway->settings['currency'] ?? 'USD' }}</span>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="d-flex justify-content-between">
                                    <span class="text-muted">Mode:</span>
                                    <span class="badge bg-label-{{ $gateway->is_test_mode ? 'warning' : 'success' }}">
                                        {{ $gateway->is_test_mode ? 'Sandbox' : 'Live' }}
                                    </span>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="d-flex justify-content-between">
                                    <span class="text-muted">Priority:</span>
                                    <span class="fw-semibold">{{ $gateway->priority }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="d-flex gap-2">
                        <button class="btn btn-sm btn-outline-primary flex-fill" onclick="testConnection({{ $gateway->id }})">
                            <i class="ri-wifi-line me-1"></i>Test Connection
                        </button>
                    </div>

                    <!-- Test Result -->
                    <div id="testResult{{ $gateway->id }}" class="test-result" style="display: none;"></div>
                </div>
            </div>
        </div>
        @empty
        <div class="col-12">
            <div class="card">
                <div class="card-body text-center py-5">
                    <i class="ri-inbox-line" style="font-size: 3rem; color: #a1acb8;"></i>
                    <h5 class="mt-3 mb-2">No Payment Gateways</h5>
                    <p class="text-muted mb-4">Get started by adding your first payment gateway</p>
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addGatewayModal">
                        <i class="ri-add-line me-1"></i>Add Gateway
                    </button>
                </div>
            </div>
        </div>
        @endforelse
    </div>
</div>

<!-- Add/Edit Gateway Modal -->
<div class="modal fade" id="addGatewayModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalTitle">Add New Payment Gateway</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="gatewayForm">
                @csrf
                <input type="hidden" id="gatewayId" name="gateway_id">
                <div class="modal-body">
                    <!-- Tabs for Gateway Type -->
                    <ul class="nav nav-tabs mb-3" role="tablist">
                        <li class="nav-item">
                            <button type="button" class="nav-link active" data-bs-toggle="tab" data-bs-target="#general-tab" role="tab">
                                General
                            </button>
                        </li>
                        <li class="nav-item">
                            <button type="button" class="nav-link" data-bs-toggle="tab" data-bs-target="#credentials-tab" role="tab">
                                Credentials
                            </button>
                        </li>
                        <li class="nav-item">
                            <button type="button" class="nav-link" data-bs-toggle="tab" data-bs-target="#advanced-tab" role="tab">
                                Advanced
                            </button>
                        </li>
                    </ul>
                    
                    <div class="tab-content">
                        <!-- General Tab -->
                        <div class="tab-pane fade show active" id="general-tab" role="tabpanel">
                                <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label">Gateway Provider <span class="text-danger">*</span></label>
                                    <select class="form-select" id="gatewayName" name="name" required onchange="updateGatewayFields()">
                                        <option value="">Select Gateway</option>
                                        <option value="stripe">Stripe</option>
                                        <option value="paypal">PayPal</option>
                                        <option value="pesapal">Pesapal</option>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Display Name <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="displayName" name="display_name" required placeholder="e.g., Stripe, PayPal">
                                </div>
                                <div class="col-md-12">
                                    <label class="form-label">Description</label>
                                    <textarea class="form-control" id="description" name="description" rows="2" placeholder="Optional description"></textarea>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Currency <span class="text-danger">*</span></label>
                                    <select class="form-select" id="currency" name="currency" required>
                                        <option value="USD">USD - US Dollar</option>
                                        <option value="EUR">EUR - Euro</option>
                                        <option value="GBP">GBP - British Pound</option>
                                        <option value="TZS">TZS - Tanzanian Shilling</option>
                                        <option value="KES">KES - Kenyan Shilling</option>
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Success URL</label>
                                    <input type="url" class="form-control" id="successUrl" name="success_url" placeholder="https://example.com/success">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Cancel URL</label>
                                    <input type="url" class="form-control" id="cancelUrl" name="cancel_url" placeholder="https://example.com/cancel">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Priority</label>
                                    <input type="number" class="form-control" id="priority" name="priority" value="0" min="0" max="100">
                                    <small class="text-muted">Lower number = higher priority</small>
                                        </div>
                                <div class="col-md-3">
                                    <div class="form-check form-switch mt-4">
                                        <input class="form-check-input" type="checkbox" id="isActive" name="is_active" checked>
                                        <label class="form-check-label" for="isActive">Active</label>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-check form-switch mt-4">
                                        <input class="form-check-input" type="checkbox" id="isTestMode" name="is_test_mode" checked>
                                        <label class="form-check-label" for="isTestMode">Test Mode</label>
                                    </div>
                                </div>
                                    <div class="col-md-6">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" id="isPrimary" name="is_primary">
                                        <label class="form-check-label" for="isPrimary">Set as Primary Gateway</label>
                                    </div>
                                </div>
                            </div>
                                    </div>
                                    
                        <!-- Credentials Tab -->
                        <div class="tab-pane fade" id="credentials-tab" role="tabpanel">
                            <!-- Stripe Fields -->
                            <div id="stripeFields" style="display: none;">
                                <div class="setting-group">
                                    <div class="setting-group-title">Stripe Credentials</div>
                                    <div class="row g-3">
                                        <div class="col-md-6">
                                            <label class="form-label">Publishable Key <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" id="stripePublishableKey" name="publishable_key" placeholder="pk_test_...">
                                            <small class="form-text">Your Stripe publishable key</small>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">Secret Key <span class="text-danger">*</span></label>
                                            <input type="password" class="form-control" id="stripeSecretKey" name="secret_key" placeholder="sk_test_...">
                                            <small class="form-text">Your Stripe secret key</small>
                                        </div>
                                        <div class="col-md-6">
                                        <label class="form-label">Webhook Secret</label>
                                            <input type="text" class="form-control" id="stripeWebhookSecret" name="webhook_secret" placeholder="whsec_...">
                                            <small class="form-text">Webhook signing secret for verification</small>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">Webhook URL</label>
                                            <input type="url" class="form-control" id="stripeWebhookUrl" name="webhook_url" placeholder="https://example.com/webhook/stripe">
                                        </div>
                                    </div>
                                    </div>
                                </div>
                                
                            <!-- PayPal Fields -->
                            <!-- Pesapal Fields -->
                            <div id="pesapalFields" style="display: none;">
                                <div class="setting-group">
                                    <div class="setting-group-title">Pesapal Credentials</div>
                                    <div class="alert alert-info">
                                        <i class="ri-information-line me-2"></i>
                                        <strong>Your Credentials:</strong><br>
                                        Consumer Key: <code>qos3CiU3YjP0k5Jk2AWaCvE5RTW0OslD</code><br>
                                        Consumer Secret: <code>M89Yr4yZ/U6ImiNJNBbQyuNxRCU=</code>
                                    </div>
                                    <div class="row g-3">
                                        <div class="col-md-6">
                                            <label class="form-label">Test Consumer Key</label>
                                            <input type="text" class="form-control" id="pesapalTestConsumerKey" name="test_consumer_key" placeholder="For sandbox testing">
                                            <small class="form-text">Leave empty if using live credentials</small>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">Test Consumer Secret</label>
                                            <input type="password" class="form-control" id="pesapalTestConsumerSecret" name="test_consumer_secret" placeholder="For sandbox testing">
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">Live Consumer Key <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" id="pesapalLiveConsumerKey" name="live_consumer_key" 
                                                   value="qos3CiU3YjP0k5Jk2AWaCvE5RTW0OslD" required>
                                            <small class="form-text">Your production consumer key</small>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">Live Consumer Secret <span class="text-danger">*</span></label>
                                            <input type="password" class="form-control" id="pesapalLiveConsumerSecret" name="live_consumer_secret" 
                                                   value="M89Yr4yZ/U6ImiNJNBbQyuNxRCU=" required>
                                            <small class="form-text">Your production consumer secret</small>
                                        </div>
                                    </div>
                                    <div class="alert alert-warning mt-3">
                                        <i class="ri-alert-line me-2"></i>
                                        <strong>Important:</strong> Uncheck "Is Test Mode" when using live credentials above.
                                    </div>
                                </div>
                            </div>
                            
                            <div id="paypalFields" style="display: none;">
                                <div class="setting-group">
                                    <div class="setting-group-title">PayPal Credentials</div>
                                    <div class="row g-3">
                                        <div class="col-md-6">
                                            <label class="form-label">Client ID <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" id="paypalClientId" name="client_id" placeholder="Your PayPal Client ID">
                                            <small class="form-text">PayPal App Client ID</small>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">Client Secret <span class="text-danger">*</span></label>
                                            <input type="password" class="form-control" id="paypalClientSecret" name="client_secret" placeholder="Your PayPal Client Secret">
                                            <small class="form-text">PayPal App Secret</small>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">Mode <span class="text-danger">*</span></label>
                                            <select class="form-select" id="paypalMode" name="mode" required>
                                                <option value="sandbox">Sandbox</option>
                                                <option value="live">Live</option>
                                            </select>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">Webhook ID</label>
                                            <input type="text" class="form-control" id="paypalWebhookId" name="webhook_id" placeholder="Webhook ID for validation">
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">Webhook URL</label>
                                            <input type="url" class="form-control" id="paypalWebhookUrl" name="webhook_url" placeholder="https://example.com/webhook/paypal">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Advanced Tab -->
                        <div class="tab-pane fade" id="advanced-tab" role="tabpanel">
                            <!-- Stripe Advanced -->
                            <div id="stripeAdvanced" style="display: none;">
                                <div class="setting-group">
                                    <div class="setting-group-title">Stripe Advanced Settings</div>
                                <div class="row g-3">
                                        <div class="col-md-6">
                                            <label class="form-label">API Version</label>
                                            <input type="text" class="form-control" id="stripeApiVersion" name="api_version" value="2024-06-01" placeholder="2024-06-01">
                                            <small class="form-text">Stripe API version to use</small>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">Webhook Tolerance (seconds)</label>
                                            <input type="number" class="form-control" id="stripeWebhookTolerance" name="webhook_tolerance" value="300" min="0" max="300">
                                            <small class="form-text">Time tolerance for webhook signature validation</small>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">Payout Mode</label>
                                            <select class="form-select" id="stripePayoutMode" name="payout_mode">
                                                <option value="automatic">Automatic</option>
                                                <option value="manual">Manual</option>
                                            </select>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">Description Prefix</label>
                                            <input type="text" class="form-control" id="stripeDescriptionPrefix" name="description_prefix" value="OfisiLink Payment" placeholder="OfisiLink Payment #ID">
                                        </div>
                                    </div>
                                        </div>
                                    </div>
                                    
                            <!-- PayPal Advanced -->
                            <div id="paypalAdvanced" style="display: none;">
                                <div class="setting-group">
                                    <div class="setting-group-title">PayPal Advanced Settings</div>
                                    <div class="row g-3">
                                        <div class="col-md-6">
                                            <label class="form-label">API Base URL</label>
                                            <input type="url" class="form-control" id="paypalApiBaseUrl" name="api_base_url" placeholder="https://api.paypal.com">
                                            <small class="form-text">Custom API URL if needed</small>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">Payment Intent Mode</label>
                                            <select class="form-select" id="paypalPaymentIntentMode" name="payment_intent_mode">
                                                <option value="capture">Capture</option>
                                                <option value="authorize">Authorize</option>
                                            </select>
                                        </div>
                                    <div class="col-md-6">
                                            <div class="form-check form-switch">
                                                <input class="form-check-input" type="checkbox" id="paypalWebhookVerification" name="webhook_verification_enabled" checked>
                                                <label class="form-check-label" for="paypalWebhookVerification">Webhook Verification Enabled</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                    </div>
                                    
                            <!-- Common Advanced -->
                            <div class="setting-group">
                                <div class="setting-group-title">Transaction Fees</div>
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label class="form-label">Fee Percentage (%)</label>
                                        <input type="number" class="form-control" id="feePercentage" name="transaction_fee_percentage" value="0" step="0.01" min="0" max="100">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Fixed Fee</label>
                                        <input type="number" class="form-control" id="feeFixed" name="transaction_fee_fixed" value="0" step="0.01" min="0">
                                    </div>
                                    </div>
                                </div>
                                
                            <div class="setting-group">
                                <div class="setting-group-title">Notes</div>
                                <div class="row g-3">
                                    <div class="col-12">
                                        <textarea class="form-control" id="notes" name="notes" rows="3" placeholder="Internal notes about this gateway"></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="ri-save-line me-1"></i>Save Gateway
                    </button>
            </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
const gateways = @json($gateways ?? []);

// Update gateway fields based on selected provider
function updateGatewayFields() {
    const gatewayName = document.getElementById('gatewayName').value;
    
    // Hide all fields
    document.getElementById('stripeFields').style.display = 'none';
    document.getElementById('paypalFields').style.display = 'none';
    document.getElementById('pesapalFields').style.display = 'none';
    document.getElementById('stripeAdvanced').style.display = 'none';
    document.getElementById('paypalAdvanced').style.display = 'none';
    
    // Show relevant fields
    if (gatewayName === 'stripe') {
        document.getElementById('stripeFields').style.display = 'block';
    } else if (gatewayName === 'pesapal') {
        document.getElementById('pesapalFields').style.display = 'block';
        document.getElementById('stripeAdvanced').style.display = 'block';
        document.getElementById('displayName').value = 'Stripe';
    } else if (gatewayName === 'paypal') {
        document.getElementById('paypalFields').style.display = 'block';
        document.getElementById('paypalAdvanced').style.display = 'block';
        document.getElementById('displayName').value = 'PayPal';
    }
}

// Gateway Form Submission
document.getElementById('gatewayForm').addEventListener('submit', async function(e) {
    e.preventDefault();
    const form = this;
    const formData = new FormData(form);
    
    // Handle checkboxes
    formData.set('is_primary', document.getElementById('isPrimary').checked ? '1' : '0');
    formData.set('is_active', document.getElementById('isActive').checked ? '1' : '0');
    formData.set('is_test_mode', document.getElementById('isTestMode').checked ? '1' : '0');
    
    const gatewayId = document.getElementById('gatewayId').value;
    const url = gatewayId 
        ? `{{ route('admin.settings.payment-gateways.update', ':id') }}`.replace(':id', gatewayId)
        : '{{ route('admin.settings.payment-gateways.store') }}';
    const method = gatewayId ? 'PUT' : 'POST';
    
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

        const data = await response.json();

        if (response.ok && data.success) {
            if (typeof showSuccessToast === 'function') {
                showSuccessToast(data.message || 'Gateway saved successfully!', 'Success');
            } else {
                alert(data.message || 'Gateway saved successfully!');
            }
            
            const modal = bootstrap.Modal.getInstance(document.getElementById('addGatewayModal'));
            if (modal) modal.hide();
            setTimeout(() => location.reload(), 1000);
        } else {
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
                showErrorToast(data.message || 'Failed to save gateway', 'Error');
            } else {
                alert(data.message || 'Failed to save gateway');
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

// Test Connection
async function testConnection(gatewayId) {
    const resultDiv = document.getElementById(`testResult${gatewayId}`);
    resultDiv.style.display = 'block';
    resultDiv.className = 'test-result';
    resultDiv.innerHTML = '<i class="ri-loader-4-line spin"></i> Testing connection...';

    try {
        const response = await fetch(`{{ route('admin.settings.payment-gateways.test-connection', ':id') }}`.replace(':id', gatewayId), {
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

// Set Primary
async function setPrimary(gatewayId) {
    if (!confirm('Set this gateway as primary? The current primary will be unset.')) return;

    try {
        const response = await fetch(`{{ route('admin.settings.payment-gateways.set-primary', ':id') }}`.replace(':id', gatewayId), {
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
            alert(data.message || 'Failed to set primary gateway');
        }
    } catch (error) {
        alert('An error occurred: ' + error.message);
    }
}

// Toggle Active
async function toggleActive(gatewayId) {
    try {
        const response = await fetch(`{{ route('admin.settings.payment-gateways.toggle-active', ':id') }}`.replace(':id', gatewayId), {
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
            alert(data.message || 'Failed to update gateway status');
        }
    } catch (error) {
        alert('An error occurred: ' + error.message);
    }
}

// Delete Gateway
async function deleteGateway(gatewayId, gatewayName) {
    if (!confirm(`Are you sure you want to delete "${gatewayName}"? This action cannot be undone.`)) return;

    try {
        const response = await fetch(`{{ route('admin.settings.payment-gateways.destroy', ':id') }}`.replace(':id', gatewayId), {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json',
            }
        });

        if (response.ok) {
            const data = await response.json();
            if (data.success) {
                location.reload();
            } else {
                alert(data.message || 'Failed to delete gateway');
            }
        } else {
            const data = await response.json();
            alert(data.message || 'Failed to delete gateway');
        }
    } catch (error) {
        alert('An error occurred: ' + error.message);
    }
}

// Edit Gateway
function editGateway(gatewayId) {
    const gateway = gateways.find(g => g.id == gatewayId);
    if (!gateway) return;

    document.getElementById('modalTitle').textContent = 'Edit Payment Gateway';
    document.getElementById('gatewayId').value = gateway.id;
    document.getElementById('gatewayName').value = gateway.name;
    document.getElementById('displayName').value = gateway.display_name;
    document.getElementById('description').value = gateway.description || '';
    document.getElementById('currency').value = gateway.settings?.currency || 'USD';
    document.getElementById('successUrl').value = gateway.settings?.success_url || '';
    document.getElementById('cancelUrl').value = gateway.settings?.cancel_url || '';
    document.getElementById('priority').value = gateway.priority;
    document.getElementById('isActive').checked = gateway.is_active;
    document.getElementById('isTestMode').checked = gateway.is_test_mode;
    document.getElementById('isPrimary').checked = gateway.is_primary;
    document.getElementById('feePercentage').value = gateway.transaction_fee_percentage || 0;
    document.getElementById('feeFixed').value = gateway.transaction_fee_fixed || 0;
    document.getElementById('notes').value = gateway.settings?.notes || '';

    const credentials = gateway.credentials || {};
    
    if (gateway.name === 'stripe') {
        document.getElementById('stripePublishableKey').value = credentials.publishable_key || '';
        document.getElementById('stripeSecretKey').value = credentials.secret_key || '';
        document.getElementById('stripeWebhookSecret').value = gateway.webhook_secret || '';
        document.getElementById('stripeWebhookUrl').value = gateway.webhook_url || '';
        document.getElementById('stripeApiVersion').value = credentials.api_version || '2024-06-01';
        document.getElementById('stripeWebhookTolerance').value = credentials.webhook_tolerance || 300;
        document.getElementById('stripePayoutMode').value = credentials.payout_mode || 'automatic';
        document.getElementById('stripeDescriptionPrefix').value = credentials.description_prefix || 'OfisiLink Payment';
    } else if (gateway.name === 'paypal') {
        document.getElementById('paypalClientId').value = credentials.client_id || '';
        document.getElementById('paypalClientSecret').value = credentials.client_secret || '';
        document.getElementById('paypalMode').value = credentials.mode || 'sandbox';
        document.getElementById('paypalWebhookId').value = credentials.webhook_id || '';
        document.getElementById('paypalWebhookUrl').value = gateway.webhook_url || '';
        document.getElementById('paypalApiBaseUrl').value = credentials.api_base_url || '';
        document.getElementById('paypalPaymentIntentMode').value = credentials.payment_intent_mode || 'capture';
        document.getElementById('paypalWebhookVerification').checked = credentials.webhook_verification_enabled !== false;
    }

    updateGatewayFields();

    const modal = new bootstrap.Modal(document.getElementById('addGatewayModal'));
    modal.show();
}

// Reset form when modal is closed
document.getElementById('addGatewayModal').addEventListener('hidden.bs.modal', function() {
    document.getElementById('gatewayForm').reset();
    document.getElementById('gatewayId').value = '';
    document.getElementById('modalTitle').textContent = 'Add New Payment Gateway';
    document.getElementById('stripeFields').style.display = 'none';
    document.getElementById('paypalFields').style.display = 'none';
    document.getElementById('pesapalFields').style.display = 'none';
    document.getElementById('stripeAdvanced').style.display = 'none';
    document.getElementById('paypalAdvanced').style.display = 'none';
});
</script>
@endpush
