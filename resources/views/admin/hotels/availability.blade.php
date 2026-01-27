@extends('admin.layouts.app')

@section('title', 'Hotel Availability - Lau Paradise Adventures')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">
                        <i class="ri-calendar-check-line me-2"></i>Hotel Availability
                    </h4>
                    <a href="{{ route('admin.hotels.index') }}" class="btn btn-outline-primary">
                        <i class="ri-arrow-left-line me-1"></i>All Hotels
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('admin.hotels.availability') }}">
                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label">Hotel</label>
                        <select name="hotel_id" class="form-select">
                            <option value="">All Hotels</option>
                            @foreach($allHotels as $h)
                                <option value="{{ $h->id }}" {{ request('hotel_id') == $h->id ? 'selected' : '' }}>
                                    {{ $h->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Search</label>
                        <input type="text" name="search" class="form-control" value="{{ request('search') }}" placeholder="Search hotels...">
                    </div>
                    <div class="col-md-4 d-flex align-items-end gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="ri-search-line me-1"></i>Filter
                        </button>
                        <a href="{{ route('admin.hotels.availability') }}" class="btn btn-outline-secondary">
                            <i class="ri-refresh-line me-1"></i>Reset
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Hotels Table -->
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">Hotel Availability Overview</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Hotel Name</th>
                            <th>Location</th>
                            <th>Star Rating</th>
                            <th>Room Types</th>
                            <th>Total Rooms</th>
                            <th>Available Rooms</th>
                            <th>Availability</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($hotels as $hotel)
                        <tr>
                            <td>
                                <strong>{{ $hotel->name }}</strong>
                                @if(!$hotel->is_active)
                                    <span class="badge bg-label-secondary ms-2">Inactive</span>
                                @endif
                            </td>
                            <td>
                                <i class="ri-map-pin-line me-1"></i>
                                {{ $hotel->city ?? ($hotel->address ?? 'N/A') }}
                                @if($hotel->country)
                                    , {{ $hotel->country }}
                                @endif
                            </td>
                            <td>
                                @if($hotel->star_rating)
                                    @for($i = 0; $i < $hotel->star_rating; $i++)
                                        <i class="ri-star-fill text-warning"></i>
                                    @endfor
                                @else
                                    <span class="text-muted">N/A</span>
                                @endif
                            </td>
                            <td>
                                <span class="badge bg-label-info">{{ $hotel->total_room_types ?? 0 }} Types</span>
                            </td>
                            <td>
                                <strong>{{ $hotel->total_rooms ?? 0 }}</strong>
                            </td>
                            <td>
                                <span class="badge bg-label-{{ ($hotel->total_available_rooms ?? 0) > 0 ? 'success' : 'danger' }}">
                                    {{ $hotel->total_available_rooms ?? 0 }}
                                </span>
                            </td>
                            <td>
                                @php
                                    $percentage = $hotel->availability_percentage ?? 0;
                                    $statusClass = $percentage >= 50 ? 'success' : ($percentage >= 25 ? 'warning' : 'danger');
                                @endphp
                                <div class="d-flex align-items-center gap-2">
                                    <div class="progress" style="width: 100px; height: 8px;">
                                        <div class="progress-bar bg-{{ $statusClass }}" 
                                             role="progressbar" 
                                             style="width: {{ $percentage }}%"
                                             aria-valuenow="{{ $percentage }}" 
                                             aria-valuemin="0" 
                                             aria-valuemax="100">
                                        </div>
                                    </div>
                                    <small class="text-muted">{{ $percentage }}%</small>
                                </div>
                            </td>
                            <td>
                                <div class="d-flex gap-1">
                                    <a href="{{ route('admin.hotels.room-types', ['hotel_id' => $hotel->id]) }}" 
                                       class="btn btn-sm btn-icon btn-outline-primary" 
                                       title="Manage Room Types">
                                        <i class="ri-door-open-line"></i>
                                    </a>
                                    <a href="{{ route('admin.hotels.show', $hotel->id) }}" 
                                       class="btn btn-sm btn-icon btn-outline-info" 
                                       title="View Details">
                                        <i class="ri-eye-line"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center py-5">
                                <div class="text-muted">
                                    <i class="ri-hotel-line ri-48px mb-3 d-block"></i>
                                    <p>No hotels found</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($hotels->hasPages())
            <div class="mt-4">
                {{ $hotels->links() }}
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
