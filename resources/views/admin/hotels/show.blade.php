@extends('admin.layouts.app')

@section('title', 'Hotel Details - Lau Paradise Adventures')
@section('description', 'View hotel details')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">
                        <i class="ri-eye-line me-2"></i>Hotel Details: {{ $hotel->name }}
                    </h4>
                    <div class="d-flex gap-2">
                        <a href="{{ route('admin.hotels.edit', $hotel->id) }}" class="btn btn-info">
                            <i class="ri-edit-line me-1"></i>Edit
                        </a>
                        <a href="{{ route('admin.hotels.index') }}" class="btn btn-label-secondary">
                            <i class="ri-arrow-left-line me-1"></i>Back to Hotels
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row g-4">
                        <div class="col-md-8">
                            <h5 class="mb-3">Hotel Information</h5>
                            <table class="table table-borderless">
                                <tr>
                                    <th width="200">Hotel Name:</th>
                                    <td><strong>{{ $hotel->name }}</strong></td>
                                </tr>
                                <tr>
                                    <th>Location:</th>
                                    <td>{{ $hotel->location ?? $hotel->city ?? $hotel->address ?? 'N/A' }}</td>
                                </tr>
                                @if($hotel->partner)
                                <tr>
                                    <th>Partner:</th>
                                    <td><span class="badge bg-label-primary">{{ $hotel->partner->name }}</span></td>
                                </tr>
                                @endif
                                @if($hotel->star_rating || $hotel->rating)
                                <tr>
                                    <th>Rating:</th>
                                    <td>
                                        @for($i = 0; $i < ($hotel->star_rating ?? $hotel->rating ?? 0); $i++)
                                            <i class="ri-star-fill text-warning"></i>
                                        @endfor
                                    </td>
                                </tr>
                                @endif
                                <tr>
                                    <th>Status:</th>
                                    <td>
                                        @if($hotel->is_active)
                                            <span class="badge bg-label-success">Active</span>
                                        @else
                                            <span class="badge bg-label-danger">Inactive</span>
                                        @endif
                                    </td>
                                </tr>
                            </table>
                            
                            @if($hotel->description)
                            <div class="mt-4">
                                <h6>Description</h6>
                                <p class="text-muted">{{ $hotel->description }}</p>
                            </div>
                            @endif
                        </div>
                        <div class="col-md-4">
                            <div class="card">
                                <div class="card-header">
                                    <h6 class="mb-0">Quick Actions</h6>
                                </div>
                                <div class="card-body">
                                    <div class="d-grid gap-2">
                                        <a href="{{ route('admin.hotels.edit', $hotel->id) }}" class="btn btn-info">
                                            <i class="ri-edit-line me-1"></i>Edit Hotel
                                        </a>
                                        <a href="{{ route('admin.hotels.room-types', ['hotel_id' => $hotel->id]) }}" class="btn btn-outline-primary">
                                            <i class="ri-hotel-bed-line me-1"></i>Manage Room Types
                                        </a>
                                        <a href="{{ route('admin.hotels.room-pricing', ['hotel_id' => $hotel->id]) }}" class="btn btn-outline-warning">
                                            <i class="ri-money-dollar-circle-line me-1"></i>Manage Pricing
                                        </a>
                                        <a href="{{ route('admin.hotels.availability', ['hotel_id' => $hotel->id]) }}" class="btn btn-outline-info">
                                            <i class="ri-calendar-line me-1"></i>View Availability
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
</div>
@endsection



