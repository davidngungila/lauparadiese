@extends('admin.layouts.app')

@section('title', 'Marketing Dashboard')
@section('description', 'Marketing overview and statistics')

@section('content')
<div class="row g-4 mb-4">
    <!-- Total Campaigns -->
    <div class="col-xl-3 col-lg-4 col-sm-6">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div class="d-flex align-items-center">
                        <div class="avatar">
                            <div class="avatar-initial bg-label-primary rounded">
                                <i class="icon-base ri ri-mail-send-line icon-24px"></i>
                            </div>
                        </div>
                        <div class="ms-3">
                            <div class="small mb-1">Total Campaigns</div>
                            <h5 class="mb-0">{{ number_format($stats['total_campaigns']) }}</h5>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Active Campaigns -->
    <div class="col-xl-3 col-lg-4 col-sm-6">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div class="d-flex align-items-center">
                        <div class="avatar">
                            <div class="avatar-initial bg-label-success rounded">
                                <i class="icon-base ri ri-play-circle-line icon-24px"></i>
                            </div>
                        </div>
                        <div class="ms-3">
                            <div class="small mb-1">Active Campaigns</div>
                            <h5 class="mb-0">{{ number_format($stats['active_campaigns']) }}</h5>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Total Emails Sent -->
    <div class="col-xl-3 col-lg-4 col-sm-6">
            <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div class="d-flex align-items-center">
                        <div class="avatar">
                            <div class="avatar-initial bg-label-info rounded">
                                <i class="icon-base ri ri-mail-line icon-24px"></i>
                            </div>
                        </div>
                        <div class="ms-3">
                            <div class="small mb-1">Emails Sent</div>
                            <h5 class="mb-0">{{ number_format($stats['total_emails_sent']) }}</h5>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Total SMS Sent -->
    <div class="col-xl-3 col-lg-4 col-sm-6">
        <div class="card">
                <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div class="d-flex align-items-center">
                        <div class="avatar">
                            <div class="avatar-initial bg-label-warning rounded">
                                <i class="icon-base ri ri-message-3-line icon-24px"></i>
                            </div>
                        </div>
                        <div class="ms-3">
                            <div class="small mb-1">SMS Sent</div>
                            <h5 class="mb-0">{{ number_format($stats['total_sms_sent']) }}</h5>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Email Open Rate -->
    <div class="col-xl-3 col-lg-4 col-sm-6">
        <div class="card">
                <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div class="d-flex align-items-center">
                        <div class="avatar">
                            <div class="avatar-initial bg-label-primary rounded">
                                <i class="icon-base ri ri-eye-line icon-24px"></i>
                            </div>
                        </div>
                        <div class="ms-3">
                            <div class="small mb-1">Email Open Rate</div>
                            <h5 class="mb-0">{{ number_format($stats['email_open_rate'], 1) }}%</h5>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- SMS Delivery Rate -->
    <div class="col-xl-3 col-lg-4 col-sm-6">
        <div class="card">
                <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div class="d-flex align-items-center">
                        <div class="avatar">
                            <div class="avatar-initial bg-label-success rounded">
                                <i class="icon-base ri ri-checkbox-circle-line icon-24px"></i>
                            </div>
                        </div>
                        <div class="ms-3">
                            <div class="small mb-1">SMS Delivery Rate</div>
                            <h5 class="mb-0">{{ number_format($stats['sms_delivery_rate'], 1) }}%</h5>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Active Promo Codes -->
    <div class="col-xl-3 col-lg-4 col-sm-6">
        <div class="card">
                <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div class="d-flex align-items-center">
                        <div class="avatar">
                            <div class="avatar-initial bg-label-danger rounded">
                                <i class="icon-base ri ri-coupon-line icon-24px"></i>
                            </div>
                        </div>
                        <div class="ms-3">
                            <div class="small mb-1">Active Promo Codes</div>
                            <h5 class="mb-0">{{ number_format($stats['active_promo_codes']) }}</h5>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Active Banners -->
    <div class="col-xl-3 col-lg-4 col-sm-6">
    <div class="card">
        <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div class="d-flex align-items-center">
                        <div class="avatar">
                            <div class="avatar-initial bg-label-secondary rounded">
                                <i class="icon-base ri ri-image-line icon-24px"></i>
                            </div>
                        </div>
                        <div class="ms-3">
                            <div class="small mb-1">Active Banners</div>
                            <h5 class="mb-0">{{ number_format($stats['active_banners']) }}</h5>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Quick Actions -->
<div class="row g-4 mb-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex align-items-center justify-content-between">
                <h5 class="card-title mb-0">Quick Actions</h5>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-3 col-sm-6">
                        <a href="{{ route('admin.marketing.promo-codes.create') }}" class="btn btn-outline-primary w-100">
                            <i class="icon-base ri ri-add-line me-2"></i>Create Promo Code
                        </a>
                    </div>
                    <div class="col-md-3 col-sm-6">
                        <a href="{{ route('admin.marketing.email-campaigns.create') }}" class="btn btn-outline-primary w-100">
                            <i class="icon-base ri ri-mail-add-line me-2"></i>Create Email Campaign
                        </a>
                    </div>
                    <div class="col-md-3 col-sm-6">
                        <a href="{{ route('admin.marketing.sms-campaigns.create') }}" class="btn btn-outline-primary w-100">
                            <i class="icon-base ri ri-message-add-line me-2"></i>Create SMS Campaign
                        </a>
                    </div>
                    <div class="col-md-3 col-sm-6">
                        <a href="{{ route('admin.marketing.banners.create') }}" class="btn btn-outline-primary w-100">
                            <i class="icon-base ri ri-image-add-line me-2"></i>Create Banner
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Marketing Links -->
<div class="row g-4">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Campaigns</h5>
            </div>
            <div class="card-body">
                <ul class="list-unstyled mb-0">
                    <li class="mb-3">
                        <a href="{{ route('admin.marketing.email-campaigns') }}" class="d-flex align-items-center text-body">
                            <i class="icon-base ri ri-mail-line me-2"></i>
                            <span>Email Campaigns</span>
                            <i class="icon-base ri ri-arrow-right-s-line ms-auto"></i>
                        </a>
                    </li>
                    <li class="mb-3">
                        <a href="{{ route('admin.marketing.sms-campaigns') }}" class="d-flex align-items-center text-body">
                            <i class="icon-base ri ri-message-3-line me-2"></i>
                            <span>SMS Campaigns</span>
                            <i class="icon-base ri ri-arrow-right-s-line ms-auto"></i>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.marketing.social-media') }}" class="d-flex align-items-center text-body">
                            <i class="icon-base ri ri-share-line me-2"></i>
                            <span>Social Media Scheduler</span>
                            <i class="icon-base ri ri-arrow-right-s-line ms-auto"></i>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
    
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Content & Analytics</h5>
            </div>
            <div class="card-body">
                <ul class="list-unstyled mb-0">
                    <li class="mb-3">
                        <a href="{{ route('admin.marketing.promo-codes') }}" class="d-flex align-items-center text-body">
                            <i class="icon-base ri ri-coupon-line me-2"></i>
                            <span>Promo Codes / Discounts</span>
                            <i class="icon-base ri ri-arrow-right-s-line ms-auto"></i>
                        </a>
                    </li>
                    <li class="mb-3">
                        <a href="{{ route('admin.marketing.landing-pages') }}" class="d-flex align-items-center text-body">
                            <i class="icon-base ri ri-file-text-line me-2"></i>
                            <span>Landing Pages</span>
                            <i class="icon-base ri ri-arrow-right-s-line ms-auto"></i>
                        </a>
                    </li>
                    <li class="mb-3">
                        <a href="{{ route('admin.marketing.banners') }}" class="d-flex align-items-center text-body">
                            <i class="icon-base ri ri-image-line me-2"></i>
                            <span>Banners & Popups</span>
                            <i class="icon-base ri ri-arrow-right-s-line ms-auto"></i>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.marketing.analytics') }}" class="d-flex align-items-center text-body">
                            <i class="icon-base ri ri-bar-chart-line me-2"></i>
                            <span>Marketing Analytics</span>
                            <i class="icon-base ri ri-arrow-right-s-line ms-auto"></i>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection
