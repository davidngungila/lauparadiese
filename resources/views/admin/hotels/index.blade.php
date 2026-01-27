@extends('admin.layouts.app')

@section('title', 'All Hotels - Lau Paradise Adventures')
@section('description', 'Manage all hotels')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">
                        <i class="ri-hotel-line me-2"></i>All Hotels
                    </h4>
                    <div class="d-flex gap-2">
                        <a href="{{ route('admin.hotels.create') }}" class="btn btn-primary">
                            <i class="ri-add-line me-1"></i>Add Hotel
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="row mb-4">
        <div class="col-lg-3 col-md-6 mb-4">
            <div class="card card-border-shadow-primary">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="avatar me-3">
                            <span class="avatar-initial rounded bg-label-primary">
                                <i class="ri-hotel-line"></i>
                            </span>
                        </div>
                        <div>
                            <h5 class="mb-0">{{ number_format(($stats ?? [])['total'] ?? 0) }}</h5>
                            <small class="text-muted">Total Hotels</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 mb-4">
            <div class="card card-border-shadow-success">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="avatar me-3">
                            <span class="avatar-initial rounded bg-label-success">
                                <i class="ri-checkbox-circle-line"></i>
                            </span>
                        </div>
                        <div>
                            <h5 class="mb-0">{{ number_format(($stats ?? [])['active'] ?? 0) }}</h5>
                            <small class="text-muted">Active Hotels</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 mb-4">
            <div class="card card-border-shadow-info">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="avatar me-3">
                            <span class="avatar-initial rounded bg-label-info">
                                <i class="ri-handshake-line"></i>
                            </span>
                        </div>
                        <div>
                            <h5 class="mb-0">{{ number_format(($stats ?? [])['partner'] ?? 0) }}</h5>
                            <small class="text-muted">Partner Hotels</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 mb-4">
            <div class="card card-border-shadow-warning">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="avatar me-3">
                            <span class="avatar-initial rounded bg-label-warning">
                                <i class="ri-hotel-bed-line"></i>
                            </span>
                        </div>
                        <div>
                            <h5 class="mb-0">{{ number_format(($stats ?? [])['total_rooms'] ?? 0) }}</h5>
                            <small class="text-muted">Total Rooms</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('admin.hotels.index') }}">
                <div class="row g-3">
                    <div class="col-md-3">
                        <label class="form-label">Status</label>
                        <select name="status" class="form-select">
                            <option value="">All Hotels</option>
                            <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                            <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Type</label>
                        <select name="partner" class="form-select">
                            <option value="">All Hotels</option>
                            <option value="1" {{ request('partner') == '1' ? 'selected' : '' }}>Partner Hotels</option>
                            <option value="0" {{ request('partner') == '0' ? 'selected' : '' }}>Regular Hotels</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Search</label>
                        <input type="text" name="search" class="form-control" placeholder="Search hotels..." value="{{ request('search') }}">
                    </div>
                    <div class="col-md-2 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="ri-search-line me-1"></i>Filter
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Hotels Table -->
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Hotel Name</th>
                            <th>Location</th>
                            <th>Star Rating</th>
                            <th>Total Rooms</th>
                            <th>Partner</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse(($hotels ?? collect()) as $hotel)
                        <tr>
                            <td><strong>{{ $hotel->name }}</strong></td>
                            <td>{{ $hotel->city ?? ($hotel->address ?? 'N/A') }}</td>
                            <td>
                                @for($i = 0; $i < ($hotel->star_rating ?? 0); $i++)
                                    <i class="ri-star-fill text-warning"></i>
                                @endfor
                            </td>
                            <td>{{ $hotel->total_rooms ?? 0 }}</td>
                            <td>
                                @if($hotel->partner_id)
                                    <span class="badge bg-label-primary">Partner</span>
                                @else
                                    <span class="badge bg-label-secondary">Regular</span>
                                @endif
                            </td>
                            <td>
                                <span class="badge bg-label-{{ $hotel->is_active ? 'success' : 'danger' }}">
                                    {{ $hotel->is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </td>
                            <td>
                                <div class="d-flex gap-2">
                                    <a href="{{ route('admin.hotels.show', $hotel->id) }}" class="btn btn-sm btn-icon btn-outline-primary" data-bs-toggle="tooltip" title="View">
                                        <i class="ri-eye-line"></i>
                                    </a>
                                    <a href="{{ route('admin.hotels.edit', $hotel->id) }}" class="btn btn-sm btn-icon btn-outline-info" data-bs-toggle="tooltip" title="Edit">
                                        <i class="ri-pencil-line"></i>
                                    </a>
                                    <button type="button" class="btn btn-sm btn-icon btn-outline-danger delete-hotel" data-id="{{ $hotel->id }}" data-name="{{ $hotel->name }}" data-bs-toggle="modal" data-bs-target="#deleteHotelModal" data-bs-tooltip title="Delete">
                                        <i class="ri-delete-bin-line"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center py-4">
                                <p class="text-muted mb-0">No hotels found</p>
                                <a href="{{ route('admin.hotels.create') }}" class="btn btn-primary btn-sm mt-2">
                                    <i class="ri-add-line me-1"></i>Add First Hotel
                                </a>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-4">
                {{ $hotels->links() }}
            </div>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteHotelModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Confirm Delete</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete hotel <strong id="deleteHotelName"></strong>?</p>
                <p class="text-danger mb-0"><small>This action cannot be undone.</small></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <form id="deleteHotelForm" method="POST" style="display: inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Delete Hotel</button>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
$(document).ready(function() {
    // Delete hotel modal
    $('.delete-hotel').on('click', function() {
        const hotelId = $(this).data('id');
        const hotelName = $(this).data('name');
        $('#deleteHotelName').text(hotelName);
        $('#deleteHotelForm').attr('action', '{{ route("admin.hotels.destroy", ":id") }}'.replace(':id', hotelId));
    });
});
</script>
@endpush
@endsection
