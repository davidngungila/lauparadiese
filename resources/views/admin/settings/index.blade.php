@extends('admin.layouts.app')

@section('title', 'Settings')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="row">
        <div class="col-12">
            <div class="card mb-4">
                <div class="card-header">
                    <h4 class="card-title mb-0">System Settings</h4>
                    <p class="text-muted mb-0">Manage your system configuration and preferences</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Settings Cards -->
    <div class="row g-4">
        <!-- System Settings -->
        <div class="col-xl-3 col-lg-4 col-md-6">
            <div class="card h-100">
                <div class="card-body text-center">
                    <div class="avatar mx-auto mb-3">
                        <div class="avatar-initial bg-label-primary rounded">
                            <i class="ri-settings-3-line ri-24px"></i>
                        </div>
                    </div>
                    <h5 class="card-title">System Settings</h5>
                    <p class="card-text text-muted">Configure system preferences and general settings</p>
                    <a href="{{ route('admin.settings.system') }}" class="btn btn-primary btn-sm">
                        <i class="ri-arrow-right-line me-1"></i>Manage
                    </a>
                </div>
            </div>
        </div>

        <!-- Organization Settings -->
        <div class="col-xl-3 col-lg-4 col-md-6">
            <div class="card h-100">
                <div class="card-body text-center">
                    <div class="avatar mx-auto mb-3">
                        <div class="avatar-initial bg-label-info rounded">
                            <i class="ri-building-line ri-24px"></i>
                        </div>
                    </div>
                    <h5 class="card-title">Organization</h5>
                    <p class="card-text text-muted">Manage organization details and information</p>
                    <a href="{{ route('admin.settings.organization') }}" class="btn btn-info btn-sm">
                        <i class="ri-arrow-right-line me-1"></i>Manage
                    </a>
                </div>
            </div>
        </div>

        <!-- Website Settings -->
        <div class="col-xl-3 col-lg-4 col-md-6">
            <div class="card h-100">
                <div class="card-body text-center">
                    <div class="avatar mx-auto mb-3">
                        <div class="avatar-initial bg-label-success rounded">
                            <i class="ri-global-line ri-24px"></i>
                        </div>
                    </div>
                    <h5 class="card-title">Website Settings</h5>
                    <p class="card-text text-muted">Configure website appearance and content</p>
                    <a href="{{ route('admin.settings.website') }}" class="btn btn-success btn-sm">
                        <i class="ri-arrow-right-line me-1"></i>Manage
                    </a>
                </div>
            </div>
        </div>

        <!-- Security Settings -->
        <div class="col-xl-3 col-lg-4 col-md-6">
            <div class="card h-100">
                <div class="card-body text-center">
                    <div class="avatar mx-auto mb-3">
                        <div class="avatar-initial bg-label-warning rounded">
                            <i class="ri-shield-check-line ri-24px"></i>
                        </div>
                    </div>
                    <h5 class="card-title">Security</h5>
                    <p class="card-text text-muted">Manage security settings and access controls</p>
                    <a href="{{ route('admin.settings.security') }}" class="btn btn-warning btn-sm">
                        <i class="ri-arrow-right-line me-1"></i>Manage
                    </a>
                </div>
            </div>
        </div>

        <!-- API Integrations -->
        <div class="col-xl-3 col-lg-4 col-md-6">
            <div class="card h-100">
                <div class="card-body text-center">
                    <div class="avatar mx-auto mb-3">
                        <div class="avatar-initial bg-label-secondary rounded">
                            <i class="ri-plug-line ri-24px"></i>
                        </div>
                    </div>
                    <h5 class="card-title">API Integrations</h5>
                    <p class="card-text text-muted">Configure third-party API connections</p>
                    <a href="{{ route('admin.settings.api-integrations') }}" class="btn btn-secondary btn-sm">
                        <i class="ri-arrow-right-line me-1"></i>Manage
                    </a>
                </div>
            </div>
        </div>

        <!-- MPESA Daraja -->
        <div class="col-xl-3 col-lg-4 col-md-6">
            <div class="card h-100">
                <div class="card-body text-center">
                    <div class="avatar mx-auto mb-3">
                        <div class="avatar-initial bg-label-primary rounded">
                            <i class="ri-money-dollar-circle-line ri-24px"></i>
                        </div>
                    </div>
                    <h5 class="card-title">MPESA Daraja</h5>
                    <p class="card-text text-muted">Configure MPESA payment gateway</p>
                    <a href="{{ route('admin.settings.mpesa') }}" class="btn btn-primary btn-sm">
                        <i class="ri-arrow-right-line me-1"></i>Manage
                    </a>
                </div>
            </div>
        </div>

        <!-- SMS Gateway -->
        <div class="col-xl-3 col-lg-4 col-md-6">
            <div class="card h-100">
                <div class="card-body text-center">
                    <div class="avatar mx-auto mb-3">
                        <div class="avatar-initial bg-label-info rounded">
                            <i class="ri-message-3-line ri-24px"></i>
                        </div>
                    </div>
                    <h5 class="card-title">SMS Gateway</h5>
                    <p class="card-text text-muted">Configure SMS notification settings</p>
                    <a href="{{ route('admin.settings.sms-gateway') }}" class="btn btn-info btn-sm">
                        <i class="ri-arrow-right-line me-1"></i>Manage
                    </a>
                </div>
            </div>
        </div>

        <!-- Email SMTP -->
        <div class="col-xl-3 col-lg-4 col-md-6">
            <div class="card h-100">
                <div class="card-body text-center">
                    <div class="avatar mx-auto mb-3">
                        <div class="avatar-initial bg-label-success rounded">
                            <i class="ri-mail-line ri-24px"></i>
                        </div>
                    </div>
                    <h5 class="card-title">Email SMTP</h5>
                    <p class="card-text text-muted">Configure email server settings</p>
                    <a href="{{ route('admin.settings.email-smtp') }}" class="btn btn-success btn-sm">
                        <i class="ri-arrow-right-line me-1"></i>Manage
                    </a>
                </div>
            </div>
        </div>

        <!-- Payment Gateways -->
        <div class="col-xl-3 col-lg-4 col-md-6">
            <div class="card h-100">
                <div class="card-body text-center">
                    <div class="avatar mx-auto mb-3">
                        <div class="avatar-initial bg-label-warning rounded">
                            <i class="ri-bank-card-line ri-24px"></i>
                        </div>
                    </div>
                    <h5 class="card-title">Payment Gateways</h5>
                    <p class="card-text text-muted">Configure PayPal, Stripe and other payment methods</p>
                    <a href="{{ route('admin.settings.payment-gateways') }}" class="btn btn-warning btn-sm">
                        <i class="ri-arrow-right-line me-1"></i>Manage
                    </a>
                </div>
            </div>
        </div>

        <!-- Backup Manager -->
        <div class="col-xl-3 col-lg-4 col-md-6">
            <div class="card h-100">
                <div class="card-body text-center">
                    <div class="avatar mx-auto mb-3">
                        <div class="avatar-initial bg-label-secondary rounded">
                            <i class="ri-database-backup-line ri-24px"></i>
                        </div>
                    </div>
                    <h5 class="card-title">Backup Manager</h5>
                    <p class="card-text text-muted">Create and manage system backups</p>
                    <a href="{{ route('admin.settings.backups') }}" class="btn btn-secondary btn-sm">
                        <i class="ri-arrow-right-line me-1"></i>Manage
                    </a>
                </div>
            </div>
        </div>

        <!-- System Logs -->
        <div class="col-xl-3 col-lg-4 col-md-6">
            <div class="card h-100">
                <div class="card-body text-center">
                    <div class="avatar mx-auto mb-3">
                        <div class="avatar-initial bg-label-danger rounded">
                            <i class="ri-file-list-3-line ri-24px"></i>
                        </div>
                    </div>
                    <h5 class="card-title">System Logs</h5>
                    <p class="card-text text-muted">View and manage system logs</p>
                    <a href="{{ route('admin.settings.system-logs') }}" class="btn btn-danger btn-sm">
                        <i class="ri-arrow-right-line me-1"></i>Manage
                    </a>
                </div>
            </div>
        </div>

        <!-- Audit Trails -->
        <div class="col-xl-3 col-lg-4 col-md-6">
            <div class="card h-100">
                <div class="card-body text-center">
                    <div class="avatar mx-auto mb-3">
                        <div class="avatar-initial bg-label-primary rounded">
                            <i class="ri-file-search-line ri-24px"></i>
                        </div>
                    </div>
                    <h5 class="card-title">Audit Trails</h5>
                    <p class="card-text text-muted">View system audit and activity records</p>
                    <a href="{{ route('admin.settings.audit-trails') }}" class="btn btn-primary btn-sm">
                        <i class="ri-arrow-right-line me-1"></i>Manage
                    </a>
                </div>
            </div>
        </div>

        <!-- Activity Logs -->
        <div class="col-xl-3 col-lg-4 col-md-6">
            <div class="card h-100">
                <div class="card-body text-center">
                    <div class="avatar mx-auto mb-3">
                        <div class="avatar-initial bg-label-info rounded">
                            <i class="ri-history-line ri-24px"></i>
                        </div>
                    </div>
                    <h5 class="card-title">Activity Logs</h5>
                    <p class="card-text text-muted">View user activity and system events</p>
                    <a href="{{ route('admin.settings.activity-logs') }}" class="btn btn-info btn-sm">
                        <i class="ri-arrow-right-line me-1"></i>Manage
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection






