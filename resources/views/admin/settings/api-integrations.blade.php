@extends('admin.layouts.app')

@section('title', 'API Integrations')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="mb-0">
                        <i class="ri-plug-line me-2"></i>API Integrations
                    </h4>
                </div>
                <div class="card-body">
                    <div class="row g-4">
                        <!-- MPESA Integration -->
                        <div class="col-md-6">
                            <div class="card border">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <h5 class="mb-0">
                                            <i class="ri-money-dollar-circle-line me-2"></i>MPESA Daraja
                                        </h5>
                                        <span class="badge {{ $integrations['mpesa']['enabled'] ? 'bg-success' : 'bg-secondary' }}">
                                            {{ $integrations['mpesa']['enabled'] ? 'Enabled' : 'Disabled' }}
                                        </span>
                                    </div>
                                    <p class="text-muted mb-3">Configure MPESA payment gateway integration</p>
                                    <a href="{{ route('admin.settings.mpesa') }}" class="btn btn-sm btn-primary">
                                        Configure <i class="ri-arrow-right-line ms-1"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                        
                        <!-- SMS Gateway -->
                        <div class="col-md-6">
                            <div class="card border">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <h5 class="mb-0">
                                            <i class="ri-message-3-line me-2"></i>SMS Gateway
                                        </h5>
                                        <span class="badge {{ $integrations['sms']['enabled'] ? 'bg-success' : 'bg-secondary' }}">
                                            {{ $integrations['sms']['enabled'] ? 'Enabled' : 'Disabled' }}
                                        </span>
                                    </div>
                                    <p class="text-muted mb-3">Configure SMS gateway for notifications</p>
                                    <a href="{{ route('admin.settings.sms-gateway') }}" class="btn btn-sm btn-primary">
                                        Configure <i class="ri-arrow-right-line ms-1"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Email SMTP -->
                        <div class="col-md-6">
                            <div class="card border">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <h5 class="mb-0">
                                            <i class="ri-mail-line me-2"></i>Email SMTP
                                        </h5>
                                        <span class="badge {{ $integrations['email']['enabled'] ? 'bg-success' : 'bg-secondary' }}">
                                            {{ $integrations['email']['enabled'] ? 'Enabled' : 'Disabled' }}
                                        </span>
                                    </div>
                                    <p class="text-muted mb-3">Configure email SMTP settings</p>
                                    <a href="{{ route('admin.settings.email-smtp') }}" class="btn btn-sm btn-primary">
                                        Configure <i class="ri-arrow-right-line ms-1"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Payment Gateways -->
                        <div class="col-md-6">
                            <div class="card border">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <h5 class="mb-0">
                                            <i class="ri-bank-card-line me-2"></i>Payment Gateways
                                        </h5>
                                        <span class="badge {{ ($integrations['payment']['stripe_enabled'] || $integrations['payment']['paypal_enabled']) ? 'bg-success' : 'bg-secondary' }}">
                                            {{ ($integrations['payment']['stripe_enabled'] || $integrations['payment']['paypal_enabled']) ? 'Enabled' : 'Disabled' }}
                                        </span>
                                    </div>
                                    <p class="text-muted mb-3">Configure PayPal and Stripe payment gateways</p>
                                    <a href="{{ route('admin.settings.payment-gateways') }}" class="btn btn-sm btn-primary">
                                        Configure <i class="ri-arrow-right-line ms-1"></i>
                                    </a>
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


