@extends('admin.layouts.app')

@section('title', 'All Tours - Lau Paradise Adventures')
@section('description', 'Manage all tours')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">
                        <i class="ri-map-2-line me-2"></i>All Tours
                    </h4>
                    <div class="d-flex gap-2">
                        <a href="{{ route('admin.tours.create') }}" class="btn btn-primary">
                            <i class="ri-add-line me-1"></i>Add New Tour
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
                                <i class="ri-map-2-line"></i>
                            </span>
                        </div>
                        <div>
                            <h5 class="mb-0">{{ number_format($stats['total'] ?? 0) }}</h5>
                            <small class="text-muted">Total Tours</small>
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
                                <i class="ri-star-line"></i>
                            </span>
                        </div>
                        <div>
                            <h5 class="mb-0">{{ number_format(($stats ?? [])['featured'] ?? 0) }}</h5>
                            <small class="text-muted">Featured Tours</small>
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
                                <i class="ri-calendar-check-line"></i>
                            </span>
                        </div>
                        <div>
                            <h5 class="mb-0">{{ number_format(($stats ?? [])['total_bookings'] ?? 0) }}</h5>
                            <small class="text-muted">Total Bookings</small>
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
                                <i class="ri-money-dollar-circle-line"></i>
                            </span>
                        </div>
                        <div>
                            <h5 class="mb-0">${{ number_format(($stats ?? [])['avg_price'] ?? 0, 2) }}</h5>
                            <small class="text-muted">Avg. Price</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('admin.tours.index') }}">
                <div class="row g-3">
                    <div class="col-md-3">
                        <label class="form-label">Destination</label>
                        <select name="destination_id" class="form-select">
                            <option value="">All Destinations</option>
                            @foreach($destinations ?? [] as $dest)
                                <option value="{{ $dest->id }}" {{ request('destination_id') == $dest->id ? 'selected' : '' }}>
                                    {{ $dest->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Status</label>
                        <select name="featured" class="form-select">
                            <option value="">All Tours</option>
                            <option value="1" {{ request('featured') == '1' ? 'selected' : '' }}>Featured Only</option>
                            <option value="0" {{ request('featured') == '0' ? 'selected' : '' }}>Regular Only</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Search</label>
                        <input type="text" name="search" class="form-control" placeholder="Search tours..." value="{{ request('search') }}">
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

    <!-- Tours Table -->
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Tour Name</th>
                            <th>Destination</th>
                            <th>Duration</th>
                            <th>Price</th>
                            <th>Rating</th>
                            <th>Status</th>
                            <th>Bookings</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($tours as $tour)
                        <tr>
                            <td>
                                <div>
                                    <strong>{{ $tour->name }}</strong>
                                    @if($tour->is_featured)
                                        <span class="badge bg-label-success ms-2">Featured</span>
                                    @endif
                                </div>
                            </td>
                            <td>{{ $tour->destination->name ?? 'N/A' }}</td>
                            <td>{{ $tour->duration_days ?? 0 }} days</td>
                            <td><strong>${{ number_format($tour->price ?? 0, 2) }}</strong></td>
                            <td>
                                @if($tour->rating)
                                    @for($i = 0; $i < 5; $i++)
                                        <i class="ri-star-{{ $i < $tour->rating ? 'fill' : 'line' }} text-warning"></i>
                                    @endfor
                                    <span class="ms-1">({{ $tour->rating }})</span>
                                @else
                                    <span class="text-muted">No rating</span>
                                @endif
                            </td>
                            <td>
                                <span class="badge bg-label-{{ $tour->is_featured ? 'success' : 'secondary' }}">
                                    {{ $tour->is_featured ? 'Active' : 'Regular' }}
                                </span>
                            </td>
                            <td>{{ $tour->bookings_count ?? 0 }}</td>
                            <td>
                                <div class="d-flex gap-2">
                                    <a href="{{ route('admin.tours.show', $tour->id) }}" class="btn btn-sm btn-icon btn-outline-primary" data-bs-toggle="tooltip" title="View">
                                        <i class="ri-eye-line"></i>
                                    </a>
                                    <a href="{{ route('admin.tours.edit', $tour->id) }}" class="btn btn-sm btn-icon btn-outline-info" data-bs-toggle="tooltip" title="Edit">
                                        <i class="ri-pencil-line"></i>
                                    </a>
                                    <button type="button" class="btn btn-sm btn-icon btn-outline-danger delete-tour" data-id="{{ $tour->id }}" data-name="{{ $tour->name }}" data-bs-toggle="modal" data-bs-target="#deleteTourModal" data-bs-tooltip title="Delete">
                                        <i class="ri-delete-bin-line"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center py-4">
                                <p class="text-muted mb-0">No tours found</p>
                                <a href="{{ route('admin.tours.create') }}" class="btn btn-primary btn-sm mt-2">
                                    <i class="ri-add-line me-1"></i>Create First Tour
                                </a>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-4">
                {{ $tours->links() }}
            </div>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteTourModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Confirm Delete</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete tour <strong id="deleteTourName"></strong>?</p>
                <p class="text-danger mb-0"><small>This action cannot be undone. Tours with existing bookings cannot be deleted.</small></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <form id="deleteTourForm" method="POST" action="" style="display: inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Delete Tour</button>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
$(document).ready(function() {
    // Delete tour modal - set form action when modal is shown
    $('#deleteTourModal').on('show.bs.modal', function(event) {
        const button = $(event.relatedTarget); // Button that triggered the modal
        const tourId = button.data('id');
        const tourName = button.data('name');
        
        // Update modal content
        $('#deleteTourName').text(tourName);
        
        // Construct the delete URL - route expects DELETE method to /admin/tours/{id}
        const deleteUrl = '{{ url("admin/tours") }}/' + tourId;
        const form = $('#deleteTourForm');
        
        if (form.length && tourId) {
            // Set the form action
            form.attr('action', deleteUrl);
            form.attr('method', 'POST');
            
            // Ensure _method field exists and is set to DELETE
            let methodField = form.find('input[name="_method"]');
            if (methodField.length === 0) {
                // Create the _method field if it doesn't exist
                $('<input>').attr({
                    type: 'hidden',
                    name: '_method',
                    value: 'DELETE'
                }).appendTo(form);
            } else {
                // Update existing _method field
                methodField.val('DELETE');
            }
        }
    });
    
    // Prevent form submission if action is not properly set
    $('#deleteTourForm').on('submit', function(e) {
        const action = $(this).attr('action');
        const methodField = $(this).find('input[name="_method"]');
        
        // Validate that action is set and includes the tour ID
        if (!action || action === '' || !action.includes('/admin/tours/') || action === '{{ url("admin/tours") }}/') {
            e.preventDefault();
            alert('Error: Unable to delete tour. Please try again.');
            console.error('Form action not properly set:', action);
            return false;
        }
        
        // Validate that _method field exists and is DELETE
        if (methodField.length === 0 || methodField.val() !== 'DELETE') {
            e.preventDefault();
            alert('Error: Form method not properly set. Please try again.');
            console.error('_method field issue:', methodField.length, methodField.val());
            return false;
        }
    });
});
</script>
@endpush
@endsection
