@extends('admin.layouts.app')

@section('title', 'Testimonials - Lau Paradise Adventures')
@section('description', 'Manage testimonials')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">
                        <i class="ri-star-smile-line me-2"></i>Testimonials
                    </h4>
                    <a href="{{ route('admin.homepage.testimonials.create') }}" class="btn btn-primary">
                        <i class="ri-add-line me-1"></i>Add Testimonial
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('admin.homepage.testimonials') }}">
                <div class="row g-3">
                    <div class="col-md-3">
                        <label class="form-label">Status</label>
                        <select name="status" class="form-select">
                            <option value="">All Status</option>
                            <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approved</option>
                            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Featured</label>
                        <select name="featured" class="form-select">
                            <option value="">All</option>
                            <option value="1" {{ request('featured') == '1' ? 'selected' : '' }}>Featured Only</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Search</label>
                        <input type="text" name="search" class="form-control" placeholder="Search testimonials..." value="{{ request('search') }}">
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

    <!-- Testimonials Table -->
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Author</th>
                            <th>Content</th>
                            <th>Rating</th>
                            <th>Tour</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($testimonials as $testimonial)
                        <tr>
                            <td>
                                <div>
                                    <strong>{{ $testimonial->author_name }}</strong>
                                    @if($testimonial->author_title)
                                        <br><small class="text-muted">{{ $testimonial->author_title }}</small>
                                    @endif
                                </div>
                            </td>
                            <td>
                                <p class="mb-0">{{ Str::limit($testimonial->content, 100) }}</p>
                            </td>
                            <td>
                                @for($i = 0; $i < 5; $i++)
                                    <i class="ri-star-{{ $i < $testimonial->rating ? 'fill' : 'line' }} text-warning"></i>
                                @endfor
                            </td>
                            <td>
                                @if($testimonial->tour)
                                    <span class="badge bg-label-info">{{ $testimonial->tour->name }}</span>
                                @else
                                    <span class="text-muted">General</span>
                                @endif
                            </td>
                            <td>
                                @if($testimonial->is_approved)
                                    <span class="badge bg-label-success">Approved</span>
                                @else
                                    <span class="badge bg-label-warning">Pending</span>
                                @endif
                                @if($testimonial->is_featured)
                                    <span class="badge bg-label-primary ms-1">Featured</span>
                                @endif
                            </td>
                            <td>
                                <div class="d-flex gap-2">
                                    <a href="{{ route('admin.homepage.testimonials.edit', $testimonial->id) }}" class="btn btn-sm btn-icon btn-outline-info" data-bs-toggle="tooltip" title="Edit">
                                        <i class="ri-pencil-line"></i>
                                    </a>
                                    <button type="button" class="btn btn-sm btn-icon btn-outline-danger delete-testimonial" data-id="{{ $testimonial->id }}" data-name="{{ $testimonial->author_name }}" data-bs-toggle="modal" data-bs-target="#deleteTestimonialModal" data-bs-tooltip title="Delete">
                                        <i class="ri-delete-bin-line"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center py-4">
                                <p class="text-muted mb-0">No testimonials found</p>
                                <a href="{{ route('admin.homepage.testimonials.create') }}" class="btn btn-primary btn-sm mt-2">
                                    <i class="ri-add-line me-1"></i>Add First Testimonial
                                </a>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-4">
                {{ $testimonials->links() }}
            </div>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteTestimonialModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Confirm Delete</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete testimonial from <strong id="deleteTestimonialName"></strong>?</p>
                <p class="text-danger mb-0"><small>This action cannot be undone.</small></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <form id="deleteTestimonialForm" method="POST" style="display: inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Delete Testimonial</button>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
$(document).ready(function() {
    // Delete testimonial modal
    $('.delete-testimonial').on('click', function() {
        const testimonialId = $(this).data('id');
        const testimonialName = $(this).data('name');
        $('#deleteTestimonialName').text(testimonialName);
        $('#deleteTestimonialForm').attr('action', '{{ route("admin.homepage.testimonials.destroy", ":id") }}'.replace(':id', testimonialId));
    });
});
</script>
@endpush
@endsection
