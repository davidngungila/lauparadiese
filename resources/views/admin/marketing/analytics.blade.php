@extends('admin.layouts.app')

@section('title', 'Marketing Analytics')
@section('description', 'Marketing performance analytics and insights')

@section('content')
<div class="row g-4 mb-4">
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
                            <h5 class="mb-0">{{ number_format($analytics['email_open_rate'], 1) }}%</h5>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Email Click Rate -->
    <div class="col-xl-3 col-lg-4 col-sm-6">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div class="d-flex align-items-center">
                        <div class="avatar">
                            <div class="avatar-initial bg-label-success rounded">
                                <i class="icon-base ri ri-cursor-line icon-24px"></i>
                            </div>
                        </div>
                        <div class="ms-3">
                            <div class="small mb-1">Email Click Rate</div>
                            <h5 class="mb-0">{{ number_format($analytics['email_click_rate'], 1) }}%</h5>
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
                            <div class="avatar-initial bg-label-info rounded">
                                <i class="icon-base ri ri-checkbox-circle-line icon-24px"></i>
                            </div>
                        </div>
                        <div class="ms-3">
                            <div class="small mb-1">SMS Delivery Rate</div>
                            <h5 class="mb-0">{{ number_format($analytics['sms_delivery_rate'], 1) }}%</h5>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Social Engagement -->
    <div class="col-xl-3 col-lg-4 col-sm-6">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div class="d-flex align-items-center">
                        <div class="avatar">
                            <div class="avatar-initial bg-label-warning rounded">
                                <i class="icon-base ri ri-heart-line icon-24px"></i>
                            </div>
                        </div>
                        <div class="ms-3">
                            <div class="small mb-1">Social Engagement</div>
                            <h5 class="mb-0">{{ number_format($analytics['social_engagement'], 1) }}%</h5>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Marketing Analytics</h5>
            </div>
            <div class="card-body">
                <form method="GET" action="{{ route('admin.marketing.analytics') }}" class="mb-4">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label">Date From</label>
                            <input type="date" name="date_from" class="form-control" value="{{ $dateFrom }}">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Date To</label>
                            <input type="date" name="date_to" class="form-control" value="{{ $dateTo }}">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">&nbsp;</label>
                            <button type="submit" class="btn btn-primary w-100">Filter</button>
                        </div>
                    </div>
                </form>
                
                <div class="alert alert-info">
                    <h6 class="alert-heading">Analytics Overview</h6>
                    <p class="mb-0">View detailed analytics for your marketing campaigns including email open rates, click rates, SMS delivery rates, and social media engagement metrics.</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
