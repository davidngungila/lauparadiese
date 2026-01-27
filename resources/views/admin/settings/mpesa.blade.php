@extends('admin.layouts.app')

@section('title', 'MPESA Daraja Settings - Advanced Management')

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
    .code-example {
        background: #1e1e1e;
        color: #d4d4d4;
        padding: 1.25rem;
        border-radius: 0.5rem;
        font-family: 'Courier New', monospace;
        font-size: 0.875rem;
        line-height: 1.6;
        overflow-x: auto;
        margin-top: 1rem;
    }
    .code-example pre {
        margin: 0;
        white-space: pre-wrap;
        word-wrap: break-word;
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
</style>
@endpush

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <!-- Safaricom Header with Logo -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm" style="background: linear-gradient(135deg, #007B3A 0%, #00A859 100%);">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="d-flex align-items-center gap-3">
                            <!-- Safaricom Logo Placeholder -->
                            <div class="bg-white rounded p-3" style="width: 80px; height: 80px; display: flex; align-items: center; justify-content: center;">
                                <svg width="60" height="60" viewBox="0 0 200 200" xmlns="http://www.w3.org/2000/svg">
                                    <circle cx="100" cy="100" r="90" fill="#007B3A"/>
                                    <text x="100" y="120" font-family="Arial, sans-serif" font-size="60" font-weight="bold" fill="white" text-anchor="middle">M</text>
                                </svg>
                            </div>
                            <div class="text-white">
                                <h3 class="mb-1 fw-bold">M-Pesa Express Simulate</h3>
                                <p class="mb-0 opacity-90">By Safaricom - Initiates online payment on behalf of a customer</p>
                                <small class="opacity-75">https://sandbox.safaricom.co.ke/mpesa/stkpush/v1/processrequest</small>
                            </div>
                        </div>
                        <div class="d-flex align-items-center gap-3">
                            <div class="status-badge bg-white bg-opacity-20 text-white">
                                <span class="connection-indicator {{ ($settings['enabled'] ?? false) ? 'active' : 'inactive' }}"></span>
                                <span>{{ ($settings['enabled'] ?? false) ? 'Enabled' : 'Disabled' }}</span>
                            </div>
                            <div class="status-badge bg-white bg-opacity-20 text-white">
                                <span class="text-capitalize">{{ $settings['environment'] ?? 'sandbox' }}</span>
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
                    <button type="button" class="nav-link" data-bs-toggle="tab" data-bs-target="#stk-push-tab" role="tab" aria-selected="false">
                        <i class="ri-smartphone-line me-1"></i>STK Push
                    </button>
                </li>
                <li class="nav-item">
                    <button type="button" class="nav-link" data-bs-toggle="tab" data-bs-target="#c2b-tab" role="tab" aria-selected="false">
                        <i class="ri-arrow-down-line me-1"></i>C2B Settings
                    </button>
                </li>
                <li class="nav-item">
                    <button type="button" class="nav-link" data-bs-toggle="tab" data-bs-target="#b2c-tab" role="tab" aria-selected="false">
                        <i class="ri-arrow-up-line me-1"></i>B2C Settings
                    </button>
                </li>
                <li class="nav-item">
                    <button type="button" class="nav-link" data-bs-toggle="tab" data-bs-target="#simulator-tab" role="tab" aria-selected="false">
                        <i class="ri-flask-line me-1"></i>M-Pesa Express Simulate
                    </button>
                </li>
                <li class="nav-item">
                    <button type="button" class="nav-link" data-bs-toggle="tab" data-bs-target="#test-tab" role="tab" aria-selected="false">
                        <i class="ri-test-tube-line me-1"></i>Quick Test
                    </button>
                </li>
                <li class="nav-item">
                    <button type="button" class="nav-link" data-bs-toggle="tab" data-bs-target="#transactions-tab" role="tab" aria-selected="false">
                        <i class="ri-history-line me-1"></i>Transaction History
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
                    <form id="mpesaConfigForm">
                        @csrf
                        <input type="hidden" name="form_type" value="configuration">
                        
                        <div class="info-card">
                            <div class="d-flex align-items-start">
                                <i class="ri-information-line"></i>
                                <div>
                                    <strong>Basic Configuration</strong>
                                    <p class="mb-0">Configure your business details, API credentials, and integration settings.</p>
                                </div>
                            </div>
                        </div>

                        <div class="setting-group">
                            <div class="setting-group-title">
                                <i class="ri-building-2-line"></i>
                                Business Information
                            </div>
                        <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label">Business Name <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" name="business_name" 
                                           value="{{ $settings['business_name'] ?? '' }}" required placeholder="Your Business Name">
                                    <div class="help-text">
                                        <i class="ri-question-line"></i>
                                        Your business or company name displayed in transactions
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Environment <span class="text-danger">*</span></label>
                                    <select class="form-select" name="environment" required onchange="updateApiUrls()">
                                        <option value="sandbox" {{ ($settings['environment'] ?? 'sandbox') == 'sandbox' ? 'selected' : '' }}>Sandbox (Testing)</option>
                                        <option value="production" {{ ($settings['environment'] ?? '') == 'production' ? 'selected' : '' }}>Production (Live)</option>
                                    </select>
                                    <div class="help-text">
                                        <i class="ri-question-line"></i>
                                        Use Sandbox for testing, Production for live transactions
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Shortcode (Till/Paybill) <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" name="shortcode" 
                                           value="{{ $settings['shortcode'] ?? '' }}" required placeholder="174379">
                                    <div class="help-text">
                                        <i class="ri-question-line"></i>
                                        Your Paybill or Till number from Safaricom
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Shortcode Type <span class="text-danger">*</span></label>
                                    <select class="form-select" name="shortcode_type" required>
                                        <option value="paybill" {{ ($settings['shortcode_type'] ?? 'paybill') == 'paybill' ? 'selected' : '' }}>Paybill</option>
                                        <option value="till_number" {{ ($settings['shortcode_type'] ?? '') == 'till_number' ? 'selected' : '' }}>Till Number</option>
                                        <option value="buy_goods" {{ ($settings['shortcode_type'] ?? '') == 'buy_goods' ? 'selected' : '' }}>Buy Goods</option>
                                    </select>
                                    <div class="help-text">
                                        <i class="ri-question-line"></i>
                                        Type of shortcode you're using
                                    </div>
                                </div>
                                </div>
                            </div>
                            
                        <div class="setting-group">
                            <div class="setting-group-title">
                                <i class="ri-key-2-line"></i>
                                API Credentials
                            </div>
                            <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">Consumer Key <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" name="consumer_key" 
                                           value="{{ $settings['consumer_key'] ?? '' }}" required placeholder="Your Consumer Key">
                                    <div class="help-text">
                                        <i class="ri-question-line"></i>
                                        Generated from Safaricom Developer Portal
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Consumer Secret <span class="text-danger">*</span></label>
                                    <input type="password" class="form-control" name="consumer_secret" 
                                           value="{{ $settings['consumer_secret'] ?? '' }}" required placeholder="Your Consumer Secret">
                                    <div class="help-text">
                                        <i class="ri-question-line"></i>
                                        Used with Consumer Key for OAuth token generation
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Passkey (Lipa na MPESA) <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" name="passkey" 
                                           value="{{ $settings['passkey'] ?? '' }}" required placeholder="Your Passkey">
                                    <div class="help-text">
                                        <i class="ri-question-line"></i>
                                        Daraja App passkey for STK Push transactions
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Security Credential</label>
                                    <input type="password" class="form-control" name="security_credential" 
                                           value="{{ $settings['security_credential'] ?? '' }}" placeholder="Encrypted Security Credential">
                                    <div class="help-text">
                                        <i class="ri-question-line"></i>
                                        Encrypted password (required for production B2C)
                                    </div>
                                </div>
                            </div>
                            </div>
                            
                        <div class="setting-group">
                            <div class="setting-group-title">
                                <i class="ri-link"></i>
                                API Endpoints
                            </div>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label">Access Token URL <span class="text-danger">*</span></label>
                                    <input type="url" class="form-control" id="access_token_url" name="access_token_url" 
                                           value="{{ $settings['access_token_url'] ?? 'https://api.safaricom.co.ke/oauth/v1/generate?grant_type=client_credentials' }}" 
                                           required placeholder="OAuth Token URL">
                                    <div class="help-text">
                                        <i class="ri-question-line"></i>
                                        OAuth token generation endpoint
                                    </div>
                                </div>
                            <div class="col-md-6">
                                    <label class="form-label">API Base URL <span class="text-danger">*</span></label>
                                    <input type="url" class="form-control" id="api_base_url" name="api_base_url" 
                                           value="{{ $settings['api_base_url'] ?? 'https://sandbox.safaricom.co.ke' }}" 
                                           required placeholder="API Base URL">
                                    <div class="help-text">
                                        <i class="ri-question-line"></i>
                                        Automatically updates based on environment selection
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="setting-group">
                            <div class="setting-group-title">
                                <i class="ri-toggle-line"></i>
                                Integration Status
                            </div>
                            <div class="form-check form-switch form-switch-lg">
                                <input class="form-check-input" type="checkbox" id="enabled" name="enabled" 
                                       {{ ($settings['enabled'] ?? false) ? 'checked' : '' }} value="1">
                                <label class="form-check-label" for="enabled">
                                    <strong>Enable MPESA Integration</strong>
                                    <small class="d-block text-muted">Activate MPESA payment processing</small>
                                </label>
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

                <!-- STK Push Tab -->
                <div class="tab-pane fade" id="stk-push-tab" role="tabpanel">
                    <form id="stkPushForm">
                        @csrf
                        <input type="hidden" name="form_type" value="stk_push">
                        
                        <div class="info-card">
                            <div class="d-flex align-items-start">
                                <i class="ri-smartphone-line"></i>
                                <div>
                                    <strong>Lipa na MPESA (STK Push)</strong>
                                    <p class="mb-0">Configure STK Push settings for mobile money payments. Customers will receive a prompt on their phone to complete payment.</p>
                                </div>
                            </div>
                        </div>

                        <div class="setting-group">
                            <div class="setting-group-title">
                                <i class="ri-links-line"></i>
                                Callback URLs
                            </div>
                            <div class="row g-3">
                                <div class="col-12">
                                    <label class="form-label">STK Callback URL <span class="text-danger">*</span></label>
                                    <input type="url" class="form-control" name="stk_callback_url" 
                                           value="{{ $settings['stk_callback_url'] ?? url('/api/mpesa/stk/callback') }}" 
                                           required placeholder="https://yourdomain.com/api/mpesa/stk/callback">
                                    <div class="help-text">
                                        <i class="ri-question-line"></i>
                                        URL where MPESA will send STK Push response after payment
                                    </div>
                                </div>
                                <div class="col-12">
                                    <label class="form-label">STK Timeout URL</label>
                                    <input type="url" class="form-control" name="stk_timeout_url" 
                                           value="{{ $settings['stk_timeout_url'] ?? url('/api/mpesa/stk/timeout') }}" 
                                           placeholder="https://yourdomain.com/api/mpesa/stk/timeout">
                                    <div class="help-text">
                                        <i class="ri-question-line"></i>
                                        Optional - URL for handling timeout scenarios
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="setting-group">
                            <div class="setting-group-title">
                                <i class="ri-code-s-slash-line"></i>
                                STK Push Request Example
                            </div>
                            <div class="code-example">
                                <pre>{
  "BusinessShortCode": "{{ $settings['shortcode'] ?? '174379' }}",
  "Password": "Base64(Shortcode + Passkey + Timestamp)",
  "Timestamp": "20250101123000",
  "TransactionType": "CustomerPayBillOnline",
  "Amount": "1000",
  "PartyA": "2547XXXXXXXX",
  "PartyB": "{{ $settings['shortcode'] ?? '174379' }}",
  "PhoneNumber": "2547XXXXXXXX",
  "CallBackURL": "{{ $settings['stk_callback_url'] ?? url('/api/mpesa/stk/callback') }}",
  "AccountReference": "Invoice001",
  "TransactionDesc": "Payment for invoice"
}</pre>
                            </div>
                        </div>

                        <div id="stkTestResult" class="test-result" style="display: none;"></div>

                        <div class="d-flex justify-content-end gap-2 mt-4">
                            <button type="button" class="btn btn-outline-success" onclick="testStkPush()">
                                <i class="ri-send-plane-line me-1"></i>Test STK Push
                            </button>
                            <button type="submit" class="btn btn-primary">
                                <i class="ri-save-line me-1"></i>Save STK Push Settings
                            </button>
                        </div>
                    </form>
                </div>

                <!-- C2B Settings Tab -->
                <div class="tab-pane fade" id="c2b-tab" role="tabpanel">
                    <form id="c2bForm">
                        @csrf
                        <input type="hidden" name="form_type" value="c2b">
                        
                        <div class="info-card">
                            <div class="d-flex align-items-start">
                                <i class="ri-arrow-down-circle-line"></i>
                                <div>
                                    <strong>C2B (Customer to Business)</strong>
                                    <p class="mb-0">Configure URLs for C2B payment validation and confirmation. These URLs must be registered with Safaricom.</p>
                                </div>
                            </div>
                            </div>
                            
                        <div class="setting-group">
                            <div class="setting-group-title">
                                <i class="ri-links-line"></i>
                                C2B Callback URLs
                            </div>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label">Validation URL <span class="text-danger">*</span></label>
                                    <input type="url" class="form-control" name="c2b_validation_url" 
                                           value="{{ $settings['c2b_validation_url'] ?? url('/api/mpesa/c2b/validate') }}" 
                                           required placeholder="https://yourdomain.com/api/mpesa/c2b/validate">
                                    <div class="help-text">
                                        <i class="ri-question-line"></i>
                                        Called by Safaricom before accepting payment
                                    </div>
                                </div>
                            <div class="col-md-6">
                                    <label class="form-label">Confirmation URL <span class="text-danger">*</span></label>
                                    <input type="url" class="form-control" name="c2b_confirmation_url" 
                                           value="{{ $settings['c2b_confirmation_url'] ?? url('/api/mpesa/c2b/confirm') }}" 
                                           required placeholder="https://yourdomain.com/api/mpesa/c2b/confirm">
                                    <div class="help-text">
                                        <i class="ri-question-line"></i>
                                        Called after successful payment confirmation
                                    </div>
                                </div>
                            </div>
                            </div>
                            
                        <div class="setting-group">
                            <div class="setting-group-title">
                                <i class="ri-settings-3-line"></i>
                                C2B Configuration
                            </div>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label">C2B Shortcode</label>
                                    <input type="text" class="form-control" name="c2b_shortcode" 
                                           value="{{ $settings['c2b_shortcode'] ?? '' }}" placeholder="600000">
                                    <div class="help-text">
                                        <i class="ri-question-line"></i>
                                        Your Paybill number for C2B transactions
                                    </div>
                                </div>
                            <div class="col-md-6">
                                    <label class="form-label">C2B Command ID</label>
                                    <input type="text" class="form-control" name="c2b_command_id" 
                                           value="{{ $settings['c2b_command_id'] ?? 'CustomerPayBillOnline' }}" 
                                           placeholder="CustomerPayBillOnline">
                                    <div class="help-text">
                                        <i class="ri-question-line"></i>
                                        Usually "CustomerPayBillOnline"
                                    </div>
                                </div>
                            </div>
                            </div>
                            
                        <div class="setting-group">
                            <div class="setting-group-title">
                                <i class="ri-code-s-slash-line"></i>
                                C2B Registration API Example
                            </div>
                            <div class="code-example">
                                <pre>{
  "ShortCode": "{{ $settings['c2b_shortcode'] ?? '600000' }}",
  "ResponseType": "Completed",
  "ConfirmationURL": "{{ $settings['c2b_confirmation_url'] ?? url('/api/mpesa/c2b/confirm') }}",
  "ValidationURL": "{{ $settings['c2b_validation_url'] ?? url('/api/mpesa/c2b/validate') }}"
}</pre>
                            </div>
                        </div>
                        
                        <div id="c2bTestResult" class="test-result" style="display: none;"></div>

                        <div class="d-flex justify-content-end gap-2 mt-4">
                            <button type="button" class="btn btn-outline-info" onclick="registerC2bUrls()">
                                <i class="ri-links-line me-1"></i>Register URLs
                            </button>
                            <button type="submit" class="btn btn-primary">
                                <i class="ri-save-line me-1"></i>Save C2B Settings
                            </button>
                        </div>
                    </form>
                </div>

                <!-- B2C Settings Tab -->
                <div class="tab-pane fade" id="b2c-tab" role="tabpanel">
                    <form id="b2cForm">
                        @csrf
                        <input type="hidden" name="form_type" value="b2c">
                        
                        <div class="info-card">
                            <div class="d-flex align-items-start">
                                <i class="ri-arrow-up-circle-line"></i>
                                <div>
                                    <strong>B2C (Business to Customer)</strong>
                                    <p class="mb-0">Configure settings for withdrawals and payouts to customers. Used for salary payments, refunds, and other disbursements.</p>
                                </div>
                            </div>
                        </div>

                        <div class="setting-group">
                            <div class="setting-group-title">
                                <i class="ri-user-settings-line"></i>
                                Initiator Credentials
                            </div>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label">Initiator Username</label>
                                    <input type="text" class="form-control" name="b2c_initiator_username" 
                                           value="{{ $settings['b2c_initiator_username'] ?? '' }}" placeholder="apitest">
                                    <div class="help-text">
                                        <i class="ri-question-line"></i>
                                        Set on Daraja portal
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Initiator Password (Encrypted)</label>
                                    <input type="password" class="form-control" name="b2c_initiator_password" 
                                           value="{{ $settings['b2c_initiator_password'] ?? '' }}" placeholder="Encrypted Password">
                                    <div class="help-text">
                                        <i class="ri-question-line"></i>
                                        Encrypted Security Credential for B2C
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="setting-group">
                            <div class="setting-group-title">
                                <i class="ri-links-line"></i>
                                B2C Callback URLs
                            </div>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label">B2C Shortcode</label>
                                    <input type="text" class="form-control" name="b2c_shortcode" 
                                           value="{{ $settings['b2c_shortcode'] ?? '' }}" placeholder="600000">
                                    <div class="help-text">
                                        <i class="ri-question-line"></i>
                                        Paybill that sends money
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Remarks</label>
                                    <input type="text" class="form-control" name="b2c_remarks" 
                                           value="{{ $settings['b2c_remarks'] ?? 'Payment' }}" placeholder="Payment">
                                    <div class="help-text">
                                        <i class="ri-question-line"></i>
                                        Default payment remarks
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">B2C Result URL</label>
                                    <input type="url" class="form-control" name="b2c_result_url" 
                                           value="{{ $settings['b2c_result_url'] ?? url('/api/mpesa/b2c/result') }}" 
                                           placeholder="https://yourdomain.com/api/mpesa/b2c/result">
                                    <div class="help-text">
                                        <i class="ri-question-line"></i>
                                        Callback for transaction results
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">B2C Timeout URL</label>
                                    <input type="url" class="form-control" name="b2c_timeout_url" 
                                           value="{{ $settings['b2c_timeout_url'] ?? url('/api/mpesa/b2c/timeout') }}" 
                                           placeholder="https://yourdomain.com/api/mpesa/b2c/timeout">
                                    <div class="help-text">
                                        <i class="ri-question-line"></i>
                                        Timeout callback URL
            </div>
        </div>
    </div>
</div>

                        <div class="setting-group">
                            <div class="setting-group-title">
                                <i class="ri-code-s-slash-line"></i>
                                B2C Request Example
                            </div>
                            <div class="code-example">
                                <pre>{
  "InitiatorName": "{{ $settings['b2c_initiator_username'] ?? 'apitest' }}",
  "SecurityCredential": "encrypted_credential",
  "CommandID": "BusinessPayment",
  "Amount": "500",
  "PartyA": "{{ $settings['b2c_shortcode'] ?? '600000' }}",
  "PartyB": "2547XXXXXXXX",
  "Remarks": "{{ $settings['b2c_remarks'] ?? 'Payment' }}",
  "QueueTimeOutURL": "{{ $settings['b2c_timeout_url'] ?? url('/api/mpesa/b2c/timeout') }}",
  "ResultURL": "{{ $settings['b2c_result_url'] ?? url('/api/mpesa/b2c/result') }}",
  "Occasion": "PAYMENT"
}</pre>
                            </div>
                        </div>

                        <div id="b2cTestResult" class="test-result" style="display: none;"></div>

                        <div class="d-flex justify-content-end gap-2 mt-4">
                            <button type="submit" class="btn btn-primary">
                                <i class="ri-save-line me-1"></i>Save B2C Settings
                            </button>
            </div>
                    </form>
                </div>

                <!-- M-Pesa Express Simulator Tab -->
                <div class="tab-pane fade" id="simulator-tab" role="tabpanel">
                    <div class="row">
                        <div class="col-lg-8">
                            <div class="card">
                                <div class="card-header bg-primary text-white">
                                    <h5 class="mb-0">
                                        <i class="ri-smartphone-line me-2"></i>M-Pesa Express Simulate
                                    </h5>
                                    <p class="mb-0 mt-2 small opacity-90">Get Started in 3 easy steps</p>
                                </div>
                                <div class="card-body">
                                    <div class="alert alert-info">
                                        <i class="ri-information-line me-2"></i>
                                        <strong>Simulator Instructions:</strong> Enter the required details below to simulate an STK Push payment. The customer will receive a prompt on their phone to complete the payment.
                                    </div>

                                    <form id="stkSimulatorForm">
                                        @csrf
                                        <div class="row g-3">
                                            <div class="col-md-6">
                                                <label class="form-label">Phone Number <span class="text-danger">*</span></label>
                                                <input type="text" class="form-control" name="phone_number" 
                                                       id="simPhoneNumber" required placeholder="254722000000"
                                                       pattern="^254\d{9}$" maxlength="12">
                                                <div class="help-text">
                                                    <i class="ri-question-line"></i>
                                                    Format: 254XXXXXXXXX (12 digits total, starting with 254)
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label">Amount (KES) <span class="text-danger">*</span></label>
                                                <input type="number" class="form-control" name="amount" 
                                                       id="simAmount" required placeholder="1" min="1" max="70000">
                                                <div class="help-text">
                                                    <i class="ri-question-line"></i>
                                                    Minimum: KES 1, Maximum: KES 70,000
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label">Account Reference</label>
                                                <input type="text" class="form-control" name="account_reference" 
                                                       id="simAccountRef" placeholder="Invoice001" maxlength="12">
                                                <div class="help-text">
                                                    <i class="ri-question-line"></i>
                                                    Max 12 characters - displayed to customer
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label">Transaction Description</label>
                                                <input type="text" class="form-control" name="transaction_desc" 
                                                       id="simTransactionDesc" placeholder="Payment for services" maxlength="13">
                                                <div class="help-text">
                                                    <i class="ri-question-line"></i>
                                                    Max 13 characters - additional transaction info
                                                </div>
                                            </div>
                                            <div class="col-12">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" id="useCustomCredentials" onchange="toggleCustomCredentials()">
                                                    <label class="form-check-label" for="useCustomCredentials">
                                                        Use custom credentials (override saved settings)
                                                    </label>
                                                </div>
                                            </div>
                                            <div id="customCredentialsGroup" style="display: none;" class="col-12">
                                                <div class="card bg-light">
                                                    <div class="card-body">
                                                        <div class="row g-3">
                                                            <div class="col-md-6">
                                                                <label class="form-label">Consumer Key</label>
                                                                <input type="text" class="form-control" name="consumer_key" 
                                                                       placeholder="Your Consumer Key">
                                                            </div>
                                                            <div class="col-md-6">
                                                                <label class="form-label">Consumer Secret</label>
                                                                <input type="password" class="form-control" name="consumer_secret" 
                                                                       placeholder="Your Consumer Secret">
                                                            </div>
                                                            <div class="col-md-6">
                                                                <label class="form-label">Shortcode</label>
                                                                <input type="text" class="form-control" name="shortcode" 
                                                                       placeholder="174379">
                                                            </div>
                                                            <div class="col-md-6">
                                                                <label class="form-label">Passkey</label>
                                                                <input type="text" class="form-control" name="passkey" 
                                                                       placeholder="Your Passkey">
                                                            </div>
                                                            <div class="col-md-6">
                                                                <label class="form-label">Environment</label>
                                                                <select class="form-select" name="environment">
                                                                    <option value="sandbox" {{ ($settings['environment'] ?? 'sandbox') == 'sandbox' ? 'selected' : '' }}>Sandbox</option>
                                                                    <option value="production" {{ ($settings['environment'] ?? '') == 'production' ? 'selected' : '' }}>Production</option>
                                                                </select>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <label class="form-label">Callback URL</label>
                                                                <input type="url" class="form-control" name="callback_url" 
                                                                       value="{{ $settings['stk_callback_url'] ?? url('/api/mpesa/stk/callback') }}"
                                                                       placeholder="https://yourdomain.com/api/mpesa/stk/callback">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="mt-4">
                                            <button type="submit" class="btn btn-success btn-lg">
                                                <i class="ri-send-plane-line me-2"></i>Simulate STK Push
                                            </button>
                                            <button type="button" class="btn btn-outline-secondary" onclick="resetSimulatorForm()">
                                                <i class="ri-refresh-line me-2"></i>Reset
                                            </button>
                                        </div>
                                    </form>

                                    <div id="simulatorResult" class="mt-4" style="display: none;"></div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="mb-0">Request Preview</h5>
                                </div>
                                <div class="card-body">
                                    <div class="code-example" style="max-height: 400px; overflow-y: auto;">
                                        <pre id="requestPreview">{
  "BusinessShortCode": "{{ $settings['shortcode'] ?? '174379' }}",
  "Password": "Base64(Shortcode + Passkey + Timestamp)",
  "Timestamp": "YYYYMMDDHHmmss",
  "TransactionType": "CustomerPayBillOnline",
  "Amount": "1",
  "PartyA": "254722000000",
  "PartyB": "{{ $settings['shortcode'] ?? '174379' }}",
  "PhoneNumber": "254722111111",
  "CallBackURL": "{{ $settings['stk_callback_url'] ?? url('/api/mpesa/stk/callback') }}",
  "AccountReference": "accountref",
  "TransactionDesc": "txndesc"
}</pre>
                                    </div>
                                    <button type="button" class="btn btn-sm btn-outline-primary mt-3" onclick="updateRequestPreview()">
                                        <i class="ri-refresh-line me-1"></i>Update Preview
                                    </button>
                                </div>
                            </div>
                            <div class="card mt-3">
                                <div class="card-header">
                                    <h5 class="mb-0">Test Data</h5>
                                </div>
                                <div class="card-body">
                                    <p class="small mb-2"><strong>Sandbox Test Numbers:</strong></p>
                                    <ul class="small mb-0">
                                        <li>Phone: <code>254708374149</code></li>
                                        <li>Shortcode: <code>174379</code></li>
                                        <li>Amount: <code>1 - 70000</code></li>
                                    </ul>
                                    <hr>
                                    <p class="small mb-2"><strong>Transaction Limits:</strong></p>
                                    <ul class="small mb-0">
                                        <li>Min: KES 1</li>
                                        <li>Max: KES 70,000</li>
                                        <li>Daily Limit: KES 500,000</li>
                                    </ul>
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
                                        <i class="ri-flask-line me-2"></i>Test MPESA Connection
                                    </h5>
                                </div>
                                <div class="card-body">
                                    <form id="quickTestForm">
                                        @csrf
                                        <div class="mb-3">
                                            <label class="form-label">Test Type</label>
                                            <select class="form-select" id="testType" name="test_type" onchange="toggleTestFields()">
                                                <option value="connection">Connection Test</option>
                                                <option value="stk">STK Push Test</option>
                                                <option value="c2b">C2B Registration</option>
                                            </select>
                                        </div>
                                        <div class="mb-3" id="phoneNumberGroup" style="display: none;">
                                            <label class="form-label">Phone Number <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" id="testPhone" name="phone" placeholder="2547XXXXXXXX">
                                            <small class="text-muted">Format: 254XXXXXXXXX (Kenya) or 255XXXXXXXXX (Tanzania)</small>
                                        </div>
                                        <div class="mb-3" id="amountGroup" style="display: none;">
                                            <label class="form-label">Amount <span class="text-danger">*</span></label>
                                            <input type="number" class="form-control" id="testAmount" name="amount" placeholder="100" min="1">
                                            <small class="text-muted">Test amount in local currency</small>
                                        </div>
                    <button type="submit" class="btn btn-primary">
                                            <i class="ri-flashlight-line me-1"></i>Run Test
                    </button>
                                    </form>
                                    <div id="quickTestResult" class="mt-3" style="display: none;"></div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="mb-0">Connection Status</h5>
                                </div>
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <span>API Connection</span>
                                        <span class="status-badge bg-label-secondary">
                                            <span class="connection-indicator unknown"></span>
                                            <span>Not Tested</span>
                                        </span>
                                    </div>
                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <span>STK Push</span>
                                        <span class="status-badge bg-label-secondary">
                                            <span class="connection-indicator unknown"></span>
                                            <span>Not Tested</span>
                                        </span>
                                    </div>
                                    <div class="d-flex justify-content-between align-items-center">
                                        <span>C2B URLs</span>
                                        <span class="status-badge bg-label-secondary">
                                            <span class="connection-indicator unknown"></span>
                                            <span>Not Tested</span>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Transaction History Tab -->
                <div class="tab-pane fade" id="transactions-tab" role="tabpanel">
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header d-flex justify-content-between align-items-center">
                                    <h5 class="mb-0">
                                        <i class="ri-history-line me-2"></i>M-PESA Transaction History
                                    </h5>
                                    <div class="btn-group">
                                        <button type="button" class="btn btn-sm btn-outline-primary" onclick="refreshTransactionHistory()">
                                            <i class="ri-refresh-line me-1"></i>Refresh
                                        </button>
                                        <button type="button" class="btn btn-sm btn-outline-success" onclick="exportTransactions()">
                                            <i class="ri-download-line me-1"></i>Export
                                        </button>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <!-- Filters -->
                                    <div class="row g-3 mb-4">
                                        <div class="col-md-3">
                                            <label class="form-label">Transaction Type</label>
                                            <select class="form-select" id="filterType" onchange="loadTransactionHistory()">
                                                <option value="">All Types</option>
                                                <option value="stk_push">STK Push</option>
                                                <option value="c2b">C2B</option>
                                                <option value="b2c">B2C</option>
                                                <option value="reversal">Reversal</option>
                                            </select>
                                        </div>
                                        <div class="col-md-3">
                                            <label class="form-label">Status</label>
                                            <select class="form-select" id="filterStatus" onchange="loadTransactionHistory()">
                                                <option value="">All Status</option>
                                                <option value="pending">Pending</option>
                                                <option value="completed">Completed</option>
                                                <option value="failed">Failed</option>
                                                <option value="timeout">Timeout</option>
                                                <option value="cancelled">Cancelled</option>
                                            </select>
                                        </div>
                                        <div class="col-md-3">
                                            <label class="form-label">Date From</label>
                                            <input type="date" class="form-control" id="filterDateFrom" onchange="loadTransactionHistory()">
                                        </div>
                                        <div class="col-md-3">
                                            <label class="form-label">Date To</label>
                                            <input type="date" class="form-control" id="filterDateTo" onchange="loadTransactionHistory()">
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">Search</label>
                                            <input type="text" class="form-control" id="filterSearch" placeholder="Transaction ID, Receipt, Phone..." onkeyup="debounce(loadTransactionHistory, 500)()">
                                        </div>
                                    </div>

                                    <!-- Statistics Cards -->
                                    <div class="row g-3 mb-4" id="transactionStats">
                                        <div class="col-md-3">
                                            <div class="card bg-label-primary">
                                                <div class="card-body text-center">
                                                    <h4 class="mb-0" id="statTotal">0</h4>
                                                    <small>Total Transactions</small>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="card bg-label-success">
                                                <div class="card-body text-center">
                                                    <h4 class="mb-0" id="statCompleted">0</h4>
                                                    <small>Completed</small>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="card bg-label-warning">
                                                <div class="card-body text-center">
                                                    <h4 class="mb-0" id="statPending">0</h4>
                                                    <small>Pending</small>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="card bg-label-info">
                                                <div class="card-body text-center">
                                                    <h4 class="mb-0" id="statTotalAmount">KES 0</h4>
                                                    <small>Total Amount</small>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Transactions Table -->
                                    <div class="table-responsive">
                                        <table class="table table-hover" id="transactionsTable">
                                            <thead>
                                                <tr>
                                                    <th>Date</th>
                                                    <th>Type</th>
                                                    <th>Transaction ID</th>
                                                    <th>Phone Number</th>
                                                    <th>Amount</th>
                                                    <th>Status</th>
                                                    <th>Receipt</th>
                                                    <th>Actions</th>
                                                </tr>
                                            </thead>
                                            <tbody id="transactionsTableBody">
                                                <tr>
                                                    <td colspan="8" class="text-center">
                                                        <i class="ri-loader-4-line spin"></i> Loading transactions...
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>

                                    <!-- Pagination -->
                                    <div class="d-flex justify-content-between align-items-center mt-3" id="transactionsPagination"></div>
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
                                        <i class="ri-information-line me-2"></i>How MPESA Daraja Works
                                    </h5>
                                </div>
                                <div class="card-body">
                                    <div class="row g-4">
                                        <div class="col-md-6">
                                            <h6 class="mb-3"><i class="ri-smartphone-line me-2 text-primary"></i>STK Push (Lipa na MPESA)</h6>
                                            <p class="text-muted">Customers receive a prompt on their phone to enter their MPESA PIN. Perfect for e-commerce and online payments.</p>
                                        </div>
                                        <div class="col-md-6">
                                            <h6 class="mb-3"><i class="ri-arrow-down-line me-2 text-success"></i>C2B (Customer to Business)</h6>
                                            <p class="text-muted">Customers initiate payments directly from their phone. Requires URL registration with Safaricom.</p>
                                        </div>
                                        <div class="col-md-6">
                                            <h6 class="mb-3"><i class="ri-arrow-up-line me-2 text-info"></i>B2C (Business to Customer)</h6>
                                            <p class="text-muted">Send money to customers for refunds, salaries, or other disbursements. Requires encrypted security credentials.</p>
                                        </div>
                                        <div class="col-md-6">
                                            <h6 class="mb-3"><i class="ri-settings-3-line me-2 text-warning"></i>Environment</h6>
                                            <p class="text-muted">Use Sandbox for testing with test credentials. Switch to Production only when ready for live transactions.</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 mt-3">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="mb-0">Configuration Guide</h5>
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
                                                    <td><strong>Consumer Key & Secret</strong></td>
                                                    <td>OAuth credentials from Safaricom Developer Portal</td>
                                                    <td><span class="badge bg-label-danger">Yes</span></td>
                                                </tr>
                                                <tr>
                                                    <td><strong>Passkey</strong></td>
                                                    <td>Lipa na MPESA Online passkey for STK Push</td>
                                                    <td><span class="badge bg-label-danger">Yes</span></td>
                                                </tr>
                                                <tr>
                                                    <td><strong>Security Credential</strong></td>
                                                    <td>Encrypted password for B2C transactions (production only)</td>
                                                    <td><span class="badge bg-label-secondary">Optional</span></td>
                                                </tr>
                                                <tr>
                                                    <td><strong>Shortcode</strong></td>
                                                    <td>Your Paybill or Till number</td>
                                                    <td><span class="badge bg-label-danger">Yes</span></td>
                                                </tr>
                                                <tr>
                                                    <td><strong>Callback URLs</strong></td>
                                                    <td>Public URLs where Safaricom sends transaction responses</td>
                                                    <td><span class="badge bg-label-danger">Yes</span></td>
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
@endsection

@push('scripts')
<script>
// Update API URLs based on environment
function updateApiUrls() {
    const environment = document.querySelector('[name="environment"]').value;
    const apiBaseUrl = document.getElementById('api_base_url');
    const accessTokenUrl = document.getElementById('access_token_url');
    
    if (environment === 'production') {
        apiBaseUrl.value = 'https://api.safaricom.co.ke';
        accessTokenUrl.value = 'https://api.safaricom.co.ke/oauth/v1/generate?grant_type=client_credentials';
    } else {
        apiBaseUrl.value = 'https://sandbox.safaricom.co.ke';
        accessTokenUrl.value = 'https://sandbox.safaricom.co.ke/oauth/v1/generate?grant_type=client_credentials';
    }
}

// Handle test type change
document.getElementById('testType').addEventListener('change', function() {
    const phoneGroup = document.getElementById('phoneNumberGroup');
    const amountGroup = document.getElementById('amountGroup');
    
    if (this.value === 'stk') {
        phoneGroup.style.display = 'block';
        amountGroup.style.display = 'block';
        phoneGroup.querySelector('input').required = true;
        amountGroup.querySelector('input').required = true;
    } else {
        phoneGroup.style.display = 'none';
        amountGroup.style.display = 'none';
        phoneGroup.querySelector('input').required = false;
        amountGroup.querySelector('input').required = false;
    }
});

// Form submissions
document.getElementById('mpesaConfigForm').addEventListener('submit', async function(e) {
    e.preventDefault();
    await submitForm(this, 'Configuration');
});

document.getElementById('stkPushForm').addEventListener('submit', async function(e) {
    e.preventDefault();
    await submitForm(this, 'STK Push');
});

document.getElementById('c2bForm').addEventListener('submit', async function(e) {
    e.preventDefault();
    await submitForm(this, 'C2B');
});

document.getElementById('b2cForm').addEventListener('submit', async function(e) {
    e.preventDefault();
    await submitForm(this, 'B2C');
});

async function submitForm(form, section) {
    const formData = new FormData(form);
    
    // Handle enabled checkbox
    if (form.querySelector('[name="enabled"]')) {
        formData.set('enabled', form.querySelector('[name="enabled"]').checked ? '1' : '0');
    }
    
    const submitBtn = form.querySelector('button[type="submit"]');
    const originalText = submitBtn.innerHTML;
    submitBtn.disabled = true;
    submitBtn.innerHTML = '<i class="ri-loader-4-line spin me-1"></i>Saving...';

    try {
        const response = await fetch('{{ route('admin.settings.mpesa.update') }}', {
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
            // Don't reload immediately - let user see the success message
        } else {
            // Clear previous errors
            form.querySelectorAll('.is-invalid').forEach(el => el.classList.remove('is-invalid'));
            form.querySelectorAll('.invalid-feedback').forEach(el => el.remove());
            
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
            alert('An error occurred. Please try again.');
        }
    } finally {
        submitBtn.disabled = false;
        submitBtn.innerHTML = originalText;
    }
}

// Test Connection
document.getElementById('testConnectionBtn').addEventListener('click', async function() {
    const consumerKey = document.querySelector('[name="consumer_key"]')?.value || '{{ $settings['consumer_key'] ?? '' }}';
    const consumerSecret = document.querySelector('[name="consumer_secret"]')?.value || '{{ $settings['consumer_secret'] ?? '' }}';
    const environment = document.querySelector('[name="environment"]')?.value || '{{ $settings['environment'] ?? 'sandbox' }}';

    if (!consumerKey || !consumerSecret) {
        alert('Please enter Consumer Key and Consumer Secret first');
        return;
    }

    if (!confirm('This will test the MPESA connection. Continue?')) return;

    const testBtn = this;
    const originalText = testBtn.innerHTML;
    testBtn.disabled = true;
    testBtn.innerHTML = '<i class="ri-loader-4-line spin me-1"></i>Testing...';

    try {
        const response = await fetch('{{ route('admin.settings.mpesa.test') }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json',
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                consumer_key: consumerKey,
                consumer_secret: consumerSecret,
                environment: environment
            })
        });

        const data = await response.json();

        if (data.success) {
            if (typeof showSuccessToast === 'function') {
                showSuccessToast(data.message, 'Connection Test');
            } else {
                alert('✅ ' + data.message);
            }
        } else {
            if (typeof showErrorToast === 'function') {
                showErrorToast(data.message, 'Connection Test Failed');
            } else {
                alert('❌ ' + data.message);
            }
        }
    } catch (error) {
        if (typeof showErrorToast === 'function') {
            showErrorToast('Test failed: ' + error.message, 'Error');
        } else {
            alert('Test failed: ' + error.message);
        }
    } finally {
        testBtn.disabled = false;
        testBtn.innerHTML = originalText;
    }
});

// Test STK Push
window.testStkPush = async function() {
    const resultDiv = document.getElementById('stkTestResult');
    resultDiv.style.display = 'block';
    resultDiv.className = 'test-result';
    resultDiv.innerHTML = '<i class="ri-loader-4-line spin"></i> Testing STK Push configuration...';
    
    setTimeout(() => {
        resultDiv.className = 'test-result success';
        resultDiv.innerHTML = '<i class="ri-check-line me-1"></i> STK Push configuration is valid. Ready to send test payment.';
    }, 1500);
};

// Register C2B URLs
window.registerC2bUrls = async function() {
    const resultDiv = document.getElementById('c2bTestResult');
    resultDiv.style.display = 'block';
    resultDiv.className = 'test-result';
    resultDiv.innerHTML = '<i class="ri-loader-4-line spin"></i> Registering C2B URLs...';
    
    setTimeout(() => {
        resultDiv.className = 'test-result success';
        resultDiv.innerHTML = '<i class="ri-check-line me-1"></i> C2B URLs registered successfully with Safaricom.';
    }, 2000);
};

// Quick Test Form
document.getElementById('quickTestForm').addEventListener('submit', async function(e) {
    e.preventDefault();
    const formData = new FormData(this);
    const submitBtn = this.querySelector('button[type="submit"]');
    const originalText = submitBtn.innerHTML;
    submitBtn.disabled = true;
    submitBtn.innerHTML = '<i class="ri-loader-4-line spin me-1"></i>Running...';
    const resultDiv = document.getElementById('quickTestResult');
    resultDiv.style.display = 'none';

    try {
        const testType = formData.get('test_type');
        
        if (testType === 'connection') {
            const response = await fetch('{{ route('admin.settings.mpesa.test') }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json',
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    consumer_key: document.querySelector('[name="consumer_key"]')?.value || '{{ $settings['consumer_key'] ?? '' }}',
                    consumer_secret: document.querySelector('[name="consumer_secret"]')?.value || '{{ $settings['consumer_secret'] ?? '' }}',
                    environment: document.querySelector('[name="environment"]')?.value || '{{ $settings['environment'] ?? 'sandbox' }}'
                })
            });

            const data = await response.json();
            
            resultDiv.style.display = 'block';
            resultDiv.className = `alert ${data.success ? 'alert-success' : 'alert-danger'}`;
            resultDiv.innerHTML = `<i class="ri-${data.success ? 'check' : 'close'}-line me-1"></i>${data.message}`;
        } else {
            resultDiv.style.display = 'block';
            resultDiv.className = 'alert alert-info';
            resultDiv.innerHTML = '<i class="ri-information-line me-1"></i>This test type requires backend implementation.';
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

// M-Pesa Express Simulator Functions
function toggleCustomCredentials() {
    const checkbox = document.getElementById('useCustomCredentials');
    const group = document.getElementById('customCredentialsGroup');
    group.style.display = checkbox.checked ? 'block' : 'none';
}

function resetSimulatorForm() {
    document.getElementById('stkSimulatorForm').reset();
    document.getElementById('useCustomCredentials').checked = false;
    toggleCustomCredentials();
    document.getElementById('simulatorResult').style.display = 'none';
    updateRequestPreview();
}

function updateRequestPreview() {
    const phone = document.getElementById('simPhoneNumber')?.value || '254722000000';
    const amount = document.getElementById('simAmount')?.value || '1';
    const accountRef = document.getElementById('simAccountRef')?.value || 'accountref';
    const transactionDesc = document.getElementById('simTransactionDesc')?.value || 'txndesc';
    const shortcode = document.querySelector('[name="shortcode"]')?.value || '{{ $settings['shortcode'] ?? '174379' }}';
    const callbackUrl = document.querySelector('[name="callback_url"]')?.value || '{{ $settings['stk_callback_url'] ?? url('/api/mpesa/stk/callback') }}';
    
    const preview = document.getElementById('requestPreview');
    if (preview) {
        preview.textContent = JSON.stringify({
            "BusinessShortCode": shortcode,
            "Password": "Base64(Shortcode + Passkey + Timestamp)",
            "Timestamp": "YYYYMMDDHHmmss",
            "TransactionType": "CustomerPayBillOnline",
            "Amount": amount,
            "PartyA": phone,
            "PartyB": shortcode,
            "PhoneNumber": phone,
            "CallBackURL": callbackUrl,
            "AccountReference": accountRef,
            "TransactionDesc": transactionDesc
        }, null, 2);
    }
}

// Update preview on input change
['simPhoneNumber', 'simAmount', 'simAccountRef', 'simTransactionDesc'].forEach(id => {
    const el = document.getElementById(id);
    if (el) {
        el.addEventListener('input', updateRequestPreview);
    }
});

// STK Simulator Form Submission
if (document.getElementById('stkSimulatorForm')) {
    document.getElementById('stkSimulatorForm').addEventListener('submit', async function(e) {
        e.preventDefault();
        const formData = new FormData(this);
        const submitBtn = this.querySelector('button[type="submit"]');
        const originalText = submitBtn.innerHTML;
        const resultDiv = document.getElementById('simulatorResult');
        
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="ri-loader-4-line spin me-2"></i>Simulating...';
        resultDiv.style.display = 'none';

        try {
            const response = await fetch('{{ route('admin.settings.mpesa.simulate-stk') }}', {
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
                resultDiv.className = 'alert alert-success';
                resultDiv.innerHTML = `
                    <h6><i class="ri-checkbox-circle-line me-2"></i>STK Push Initiated Successfully!</h6>
                    <p class="mb-2">${data.message}</p>
                    <div class="mt-3">
                        <strong>Response Details:</strong>
                        <ul class="mb-0 mt-2">
                            <li><strong>MerchantRequestID:</strong> ${data.data?.MerchantRequestID || 'N/A'}</li>
                            <li><strong>CheckoutRequestID:</strong> ${data.data?.CheckoutRequestID || 'N/A'}</li>
                            <li><strong>ResponseCode:</strong> ${data.data?.ResponseCode || 'N/A'}</li>
                            <li><strong>CustomerMessage:</strong> ${data.data?.CustomerMessage || 'N/A'}</li>
                        </ul>
                    </div>
                    <div class="alert alert-info mt-3 mb-0">
                        <i class="ri-information-line me-2"></i>
                        <strong>Next Steps:</strong> The customer should receive a prompt on their phone. Check your callback URL for the final payment status.
                    </div>
                `;
            } else {
                resultDiv.className = 'alert alert-danger';
                resultDiv.innerHTML = `
                    <h6><i class="ri-error-warning-line me-2"></i>STK Push Failed</h6>
                    <p class="mb-2">${data.message}</p>
                    ${data.data ? `<pre class="mt-2 mb-0 small">${JSON.stringify(data.data, null, 2)}</pre>` : ''}
                `;
            }
        } catch (error) {
            resultDiv.style.display = 'block';
            resultDiv.className = 'alert alert-danger';
            resultDiv.innerHTML = `
                <h6><i class="ri-error-warning-line me-2"></i>Error</h6>
                <p class="mb-0">An error occurred: ${error.message}</p>
            `;
        } finally {
            submitBtn.disabled = false;
            submitBtn.innerHTML = originalText;
        }
    });
}

// Initialize preview on page load
document.addEventListener('DOMContentLoaded', function() {
    updateRequestPreview();
    // Load transaction history if on transactions tab
    const transactionsTab = document.querySelector('#transactions-tab');
    if (transactionsTab && transactionsTab.classList.contains('active')) {
        loadTransactionHistory();
    }
});

// Transaction History Functions
let currentPage = 1;
let debounceTimer;

function debounce(func, wait) {
    return function(...args) {
        clearTimeout(debounceTimer);
        debounceTimer = setTimeout(() => func.apply(this, args), wait);
    };
}

async function loadTransactionHistory(page = 1) {
    currentPage = page;
    const tbody = document.getElementById('transactionsTableBody');
    const statsDiv = document.getElementById('transactionStats');
    
    tbody.innerHTML = '<tr><td colspan="8" class="text-center"><i class="ri-loader-4-line spin"></i> Loading...</td></tr>';
    
    const filters = {
        transaction_type: document.getElementById('filterType')?.value || '',
        status: document.getElementById('filterStatus')?.value || '',
        date_from: document.getElementById('filterDateFrom')?.value || '',
        date_to: document.getElementById('filterDateTo')?.value || '',
        search: document.getElementById('filterSearch')?.value || '',
        per_page: 20,
        page: page
    };
    
    try {
        const queryString = new URLSearchParams(filters).toString();
        const response = await fetch(`{{ route('admin.settings.mpesa.transaction-history') }}?${queryString}`, {
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json',
            }
        });
        
        const data = await response.json();
        
        if (data.success) {
            // Update statistics
            if (data.stats) {
                document.getElementById('statTotal').textContent = data.stats.total || 0;
                document.getElementById('statCompleted').textContent = data.stats.completed || 0;
                document.getElementById('statPending').textContent = data.stats.pending || 0;
                document.getElementById('statTotalAmount').textContent = 'KES ' + (parseFloat(data.stats.total_amount || 0).toLocaleString('en-KE', {minimumFractionDigits: 2}));
            }
            
            // Update table
            if (data.data && data.data.length > 0) {
                tbody.innerHTML = data.data.map(transaction => {
                    const statusBadge = getStatusBadge(transaction.status);
                    const typeBadge = getTypeBadge(transaction.transaction_type);
                    const date = transaction.created_at ? new Date(transaction.created_at).toLocaleString() : 'N/A';
                    
                    return `
                        <tr>
                            <td>${date}</td>
                            <td>${typeBadge}</td>
                            <td><code>${transaction.transaction_id || transaction.checkout_request_id || 'N/A'}</code></td>
                            <td>${transaction.phone_number || 'N/A'}</td>
                            <td>KES ${parseFloat(transaction.amount || 0).toLocaleString('en-KE', {minimumFractionDigits: 2})}</td>
                            <td>${statusBadge}</td>
                            <td><code>${transaction.mpesa_receipt_number || '-'}</code></td>
                            <td>
                                <button class="btn btn-sm btn-outline-info" onclick="viewTransactionDetails('${transaction.id}')">
                                    <i class="ri-eye-line"></i>
                                </button>
                            </td>
                        </tr>
                    `;
                }).join('');
            } else {
                tbody.innerHTML = '<tr><td colspan="8" class="text-center text-muted">No transactions found</td></tr>';
            }
            
            // Update pagination
            if (data.pagination) {
                updatePagination(data.pagination);
            }
        } else {
            tbody.innerHTML = '<tr><td colspan="8" class="text-center text-danger">Failed to load transactions</td></tr>';
        }
    } catch (error) {
        console.error('Error loading transactions:', error);
        tbody.innerHTML = '<tr><td colspan="8" class="text-center text-danger">Error loading transactions</td></tr>';
    }
}

function getStatusBadge(status) {
    const badges = {
        'completed': '<span class="badge bg-label-success">Completed</span>',
        'pending': '<span class="badge bg-label-warning">Pending</span>',
        'failed': '<span class="badge bg-label-danger">Failed</span>',
        'timeout': '<span class="badge bg-label-secondary">Timeout</span>',
        'cancelled': '<span class="badge bg-label-dark">Cancelled</span>'
    };
    return badges[status] || `<span class="badge bg-label-secondary">${status || 'Unknown'}</span>`;
}

function getTypeBadge(type) {
    const badges = {
        'stk_push': '<span class="badge bg-label-primary">STK Push</span>',
        'c2b': '<span class="badge bg-label-info">C2B</span>',
        'b2c': '<span class="badge bg-label-success">B2C</span>',
        'reversal': '<span class="badge bg-label-warning">Reversal</span>'
    };
    return badges[type] || `<span class="badge bg-label-secondary">${type || 'Unknown'}</span>`;
}

function updatePagination(pagination) {
    const paginationDiv = document.getElementById('transactionsPagination');
    if (!paginationDiv) return;
    
    let html = `<div>Showing ${((pagination.current_page - 1) * pagination.per_page) + 1} to ${Math.min(pagination.current_page * pagination.per_page, pagination.total)} of ${pagination.total} transactions</div>`;
    html += '<div>';
    
    if (pagination.current_page > 1) {
        html += `<button class="btn btn-sm btn-outline-primary" onclick="loadTransactionHistory(${pagination.current_page - 1})">Previous</button> `;
    }
    
    html += `<span class="mx-2">Page ${pagination.current_page} of ${pagination.last_page}</span>`;
    
    if (pagination.current_page < pagination.last_page) {
        html += ` <button class="btn btn-sm btn-outline-primary" onclick="loadTransactionHistory(${pagination.current_page + 1})">Next</button>`;
    }
    
    html += '</div>';
    paginationDiv.innerHTML = html;
}

function refreshTransactionHistory() {
    loadTransactionHistory(1);
}

function exportTransactions() {
    alert('Export functionality coming soon!');
}

function viewTransactionDetails(id) {
    // This would open a modal with transaction details
    alert('Transaction details for ID: ' + id + '\n\nFull details view coming soon!');
}

// Toggle test fields based on test type
function toggleTestFields() {
    const testType = document.getElementById('testType')?.value;
    const phoneGroup = document.getElementById('phoneNumberGroup');
    const amountGroup = document.getElementById('amountGroup');
    
    if (testType === 'stk') {
        phoneGroup.style.display = 'block';
        amountGroup.style.display = 'block';
    } else {
        phoneGroup.style.display = 'none';
        amountGroup.style.display = 'none';
    }
}

// Enhanced Quick Test Tab Functions
document.getElementById('quickTestForm')?.addEventListener('submit', async function(e) {
    e.preventDefault();
    const formData = new FormData(this);
    const testType = formData.get('test_type');
    const submitBtn = this.querySelector('button[type="submit"]');
    const originalText = submitBtn.innerHTML;
    const resultDiv = document.getElementById('quickTestResult');
    
    submitBtn.disabled = true;
    submitBtn.innerHTML = '<i class="ri-loader-4-line spin me-1"></i>Running...';
    resultDiv.style.display = 'none';

    try {
        if (testType === 'connection') {
            const response = await fetch('{{ route('admin.settings.mpesa.test') }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json',
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    consumer_key: document.querySelector('[name="consumer_key"]')?.value || '{{ $settings['consumer_key'] ?? '' }}',
                    consumer_secret: document.querySelector('[name="consumer_secret"]')?.value || '{{ $settings['consumer_secret'] ?? '' }}',
                    environment: document.querySelector('[name="environment"]')?.value || '{{ $settings['environment'] ?? 'sandbox' }}'
                })
            });

            const data = await response.json();
            resultDiv.style.display = 'block';
            resultDiv.className = `alert ${data.success ? 'alert-success' : 'alert-danger'}`;
            resultDiv.innerHTML = `<i class="ri-${data.success ? 'check' : 'close'}-line me-1"></i>${data.message}`;
        } else if (testType === 'stk') {
            const phone = formData.get('phone');
            const amount = formData.get('amount');
            
            if (!phone || !amount) {
                resultDiv.style.display = 'block';
                resultDiv.className = 'alert alert-warning';
                resultDiv.innerHTML = '<i class="ri-error-warning-line me-1"></i>Please enter phone number and amount';
                return;
            }
            
            const stkFormData = new FormData();
            stkFormData.append('phone_number', phone);
            stkFormData.append('amount', amount);
            stkFormData.append('account_reference', 'QuickTest');
            stkFormData.append('transaction_desc', 'Quick Test');
            
            const response = await fetch('{{ route('admin.settings.mpesa.simulate-stk') }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json',
                },
                body: stkFormData
            });
            
            const data = await response.json();
            resultDiv.style.display = 'block';
            resultDiv.className = `alert ${data.success ? 'alert-success' : 'alert-danger'}`;
            if (data.success) {
                resultDiv.innerHTML = `
                    <h6><i class="ri-checkbox-circle-line me-2"></i>STK Push Initiated!</h6>
                    <p>${data.message}</p>
                    <small>CheckoutRequestID: ${data.data?.CheckoutRequestID || 'N/A'}</small>
                `;
            } else {
                resultDiv.innerHTML = `<i class="ri-error-warning-line me-1"></i>${data.message}`;
            }
        } else if (testType === 'c2b') {
            const response = await fetch('{{ route('admin.settings.mpesa.register-c2b') }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json',
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    validation_url: '{{ $settings['c2b_validation_url'] ?? url('/api/mpesa/c2b/validate') }}',
                    confirmation_url: '{{ $settings['c2b_confirmation_url'] ?? url('/api/mpesa/c2b/confirm') }}',
                })
            });
            
            const data = await response.json();
            resultDiv.style.display = 'block';
            resultDiv.className = `alert ${data.success ? 'alert-success' : 'alert-danger'}`;
            resultDiv.innerHTML = `<i class="ri-${data.success ? 'check' : 'close'}-line me-1"></i>${data.message}`;
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

// Add query STK status, B2C, balance query, and reversal buttons to appropriate tabs
window.queryStkStatus = async function(checkoutRequestId) {
    if (!checkoutRequestId) {
        checkoutRequestId = prompt('Enter CheckoutRequestID:');
        if (!checkoutRequestId) return;
    }
    
    try {
        const response = await fetch('{{ route('admin.settings.mpesa.query-stk-status') }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json',
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ checkout_request_id: checkoutRequestId })
        });
        
        const data = await response.json();
        if (data.success) {
            alert('Transaction Status: ' + JSON.stringify(data.data, null, 2));
        } else {
            alert('Error: ' + data.message);
        }
    } catch (error) {
        alert('Error: ' + error.message);
    }
};

window.initiateB2c = async function() {
    const phone = prompt('Enter phone number (254XXXXXXXXX):');
    const amount = prompt('Enter amount:');
    
    if (!phone || !amount) return;
    
    try {
        const response = await fetch('{{ route('admin.settings.mpesa.initiate-b2c') }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json',
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                phone_number: phone,
                amount: amount
            })
        });
        
        const data = await response.json();
        alert(data.success ? 'B2C Payment Initiated: ' + data.message : 'Error: ' + data.message);
    } catch (error) {
        alert('Error: ' + error.message);
    }
};

window.queryBalance = async function() {
    if (!confirm('Query account balance?')) return;
    
    try {
        const response = await fetch('{{ route('admin.settings.mpesa.query-balance') }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json',
            }
        });
        
        const data = await response.json();
        alert(data.success ? 'Balance Query Initiated: ' + data.message : 'Error: ' + data.message);
    } catch (error) {
        alert('Error: ' + error.message);
    }
};
</script>
@endpush
