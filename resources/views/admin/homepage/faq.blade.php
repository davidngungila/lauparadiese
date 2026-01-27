@extends('admin.layouts.app')

@section('title', 'FAQ - Lau Paradise Adventures')
@section('description', 'Manage FAQ')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">
                        <i class="ri-question-line me-2"></i>FAQ Management
                    </h4>
                    <a href="{{ route('admin.homepage.faq.create') }}" class="btn btn-primary">
                        <i class="ri-add-line me-1"></i>Add FAQ
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('admin.homepage.faq') }}">
                <div class="row g-3">
                    <div class="col-md-3">
                        <label class="form-label">Category</label>
                        <input type="text" name="category" class="form-control" placeholder="Category..." value="{{ request('category') }}">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Status</label>
                        <select name="status" class="form-select">
                            <option value="">All Status</option>
                            <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                            <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Search</label>
                        <input type="text" name="search" class="form-control" placeholder="Search FAQ..." value="{{ request('search') }}">
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

    <!-- FAQ Table -->
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Question</th>
                            <th>Answer</th>
                            <th>Category</th>
                            <th>Order</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($faqs as $faq)
                        <tr>
                            <td><strong>{{ Str::limit($faq->question, 60) }}</strong></td>
                            <td>{{ Str::limit($faq->answer, 80) }}</td>
                            <td>{{ $faq->category ?? 'Uncategorized' }}</td>
                            <td>{{ $faq->display_order ?? 0 }}</td>
                            <td>
                                <span class="badge bg-label-{{ $faq->is_active ? 'success' : 'secondary' }}">
                                    {{ $faq->is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </td>
                            <td>
                                <div class="d-flex gap-2">
                                    <a href="{{ route('admin.homepage.faq.edit', $faq->id) }}" class="btn btn-sm btn-icon btn-outline-info" data-bs-toggle="tooltip" title="Edit">
                                        <i class="ri-pencil-line"></i>
                                    </a>
                                    <button type="button" class="btn btn-sm btn-icon btn-outline-danger delete-faq" data-id="{{ $faq->id }}" data-name="{{ Str::limit($faq->question, 30) }}" data-bs-toggle="modal" data-bs-target="#deleteFaqModal" data-bs-tooltip title="Delete">
                                        <i class="ri-delete-bin-line"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center py-4">
                                <p class="text-muted mb-0">No FAQ items found</p>
                                <a href="{{ route('admin.homepage.faq.create') }}" class="btn btn-primary btn-sm mt-2">
                                    <i class="ri-add-line me-1"></i>Add First FAQ
                                </a>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-4">
                {{ $faqs->links() }}
            </div>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteFaqModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Confirm Delete</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete FAQ <strong id="deleteFaqName"></strong>?</p>
                <p class="text-danger mb-0"><small>This action cannot be undone.</small></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <form id="deleteFaqForm" method="POST" style="display: inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Delete FAQ</button>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
$(document).ready(function() {
    // Delete FAQ modal
    $('.delete-faq').on('click', function() {
        const faqId = $(this).data('id');
        const faqName = $(this).data('name');
        $('#deleteFaqName').text(faqName);
        $('#deleteFaqForm').attr('action', '{{ route("admin.homepage.faq.destroy", ":id") }}'.replace(':id', faqId));
    });
});
</script>
@endpush
@endsection
