@extends('admin.layouts.app')

@section('title', 'Customer Feedback - Lau Paradise Adventures')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <!-- Page Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">
                        <i class="ri-feedback-line me-2"></i>Customer Feedback Management
                    </h4>
                    <a href="{{ route('admin.customers.index') }}" class="btn btn-outline-secondary">
                        <i class="ri-arrow-left-line me-1"></i>Back to Customers
                    </a>
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
                                <i class="ri-feedback-line"></i>
                            </span>
                        </div>
                        <div>
                            <h5 class="mb-0">{{ number_format($stats['total'] ?? 0) }}</h5>
                            <small class="text-muted">Total Feedback</small>
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
                                <i class="ri-time-line"></i>
                            </span>
                        </div>
                        <div>
                            <h5 class="mb-0">{{ number_format($stats['pending'] ?? 0) }}</h5>
                            <small class="text-muted">Pending Review</small>
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
                                <i class="ri-check-line"></i>
                            </span>
                        </div>
                        <div>
                            <h5 class="mb-0">{{ number_format($stats['approved'] ?? 0) }}</h5>
                            <small class="text-muted">Approved</small>
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
                                <i class="ri-star-line"></i>
                            </span>
                        </div>
                        <div>
                            <h5 class="mb-0">{{ number_format($stats['average_rating'] ?? 0, 1) }}</h5>
                            <small class="text-muted">Average Rating</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('admin.customers.feedback') }}">
                <div class="row g-3">
                    <div class="col-md-3">
                        <label class="form-label">Search</label>
                        <input type="text" name="search" class="form-control" placeholder="Search feedback..." value="{{ request('search') }}">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Feedback Type</label>
                        <select name="feedback_type" class="form-select">
                            <option value="">All Types</option>
                            <option value="tour_package" {{ request('feedback_type') == 'tour_package' ? 'selected' : '' }}>Tour Package</option>
                            <option value="driver_guide" {{ request('feedback_type') == 'driver_guide' ? 'selected' : '' }}>Driver/Guide</option>
                            <option value="hotel" {{ request('feedback_type') == 'hotel' ? 'selected' : '' }}>Hotel</option>
                            <option value="general_company" {{ request('feedback_type') == 'general_company' ? 'selected' : '' }}>General</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Status</label>
                        <select name="status" class="form-select">
                            <option value="">All Status</option>
                            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approved</option>
                            <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
                            <option value="resolved" {{ request('status') == 'resolved' ? 'selected' : '' }}>Resolved</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Rating</label>
                        <select name="rating" class="form-select">
                            <option value="">All Ratings</option>
                            <option value="5" {{ request('rating') == '5' ? 'selected' : '' }}>5 Stars</option>
                            <option value="4" {{ request('rating') == '4' ? 'selected' : '' }}>4 Stars</option>
                            <option value="3" {{ request('rating') == '3' ? 'selected' : '' }}>3 Stars</option>
                            <option value="2" {{ request('rating') == '2' ? 'selected' : '' }}>2 Stars</option>
                            <option value="1" {{ request('rating') == '1' ? 'selected' : '' }}>1 Star</option>
                        </select>
                    </div>
                    <div class="col-md-3 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="ri-search-line me-1"></i>Filter
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Feedback Table -->
    <div class="card">
        <div class="card-body">
            @if($feedback->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Customer</th>
                                <th>Type</th>
                                <th>Rating</th>
                                <th>Title/Message</th>
                                <th>Date</th>
                                <th>Status</th>
                                <th class="text-end">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($feedback as $item)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="avatar me-2">
                                                <span class="avatar-initial rounded bg-label-primary">
                                                    {{ strtoupper(substr($item->customer->name ?? 'U', 0, 1)) }}
                                                </span>
                                            </div>
                                            <div>
                                                <strong>{{ $item->customer->name ?? 'N/A' }}</strong>
                                                <br><small class="text-muted">{{ $item->customer->email ?? '' }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge bg-label-info">{{ ucfirst(str_replace('_', ' ', $item->feedback_type)) }}</span>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            @for($i = 1; $i <= 5; $i++)
                                                <i class="ri-star{{ $i <= $item->rating ? '-fill' : '' }}-line text-warning"></i>
                                            @endfor
                                            <span class="ms-2">({{ $item->rating }})</span>
                                        </div>
                                    </td>
                                    <td>
                                        <strong>{{ $item->title ?? 'No Title' }}</strong>
                                        <br><small class="text-muted">{{ Str::limit($item->message, 50) }}</small>
                                    </td>
                                    <td>
                                        <small>{{ $item->created_at->format('M d, Y') }}</small>
                                    </td>
                                    <td>
                                        @php
                                            $statusClass = match($item->status) {
                                                'approved' => 'success',
                                                'pending' => 'warning',
                                                'rejected' => 'danger',
                                                'resolved' => 'info',
                                                default => 'secondary'
                                            };
                                        @endphp
                                        <span class="badge bg-label-{{ $statusClass }}">{{ ucfirst($item->status) }}</span>
                                        @if($item->is_public)
                                            <br><small class="text-success"><i class="ri-eye-line"></i> Public</small>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="d-flex justify-content-end gap-1">
                                            <button type="button" class="btn btn-sm btn-icon btn-outline-primary" onclick="viewFeedback({{ $item->id }})" title="View">
                                                <i class="ri-eye-line"></i>
                                            </button>
                                            <button type="button" class="btn btn-sm btn-icon btn-outline-info" onclick="editFeedback({{ $item->id }})" title="Edit">
                                                <i class="ri-pencil-line"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                
                <!-- Pagination -->
                <div class="mt-4">
                    {{ $feedback->links() }}
                </div>
            @else
                <div class="text-center py-5">
                    <i class="ri-feedback-line" style="font-size: 64px; color: #ccc;"></i>
                    <h5 class="mt-3">No Feedback Found</h5>
                    <p class="text-muted">Customer feedback will appear here once submitted.</p>
                </div>
            @endif
        </div>
    </div>
</div>

<!-- View Feedback Modal -->
<div class="modal fade" id="viewFeedbackModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Feedback Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="feedbackDetails">
                <!-- Content loaded via AJAX -->
            </div>
        </div>
    </div>
</div>

<!-- Edit Feedback Modal -->
<div class="modal fade" id="editFeedbackModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form id="editFeedbackForm" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-header">
                    <h5 class="modal-title">Update Feedback</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body" id="editFeedbackContent">
                    <!-- Content loaded via AJAX -->
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Update Feedback</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function viewFeedback(id) {
    // Load feedback details via AJAX
    fetch(`/admin/customers/feedback/${id}`)
        .then(res => res.json())
        .then(data => {
            document.getElementById('feedbackDetails').innerHTML = data.html;
            new bootstrap.Modal(document.getElementById('viewFeedbackModal')).show();
        });
}

function editFeedback(id) {
    document.getElementById('editFeedbackForm').action = `{{ route('admin.customers.feedback.update', ':id') }}`.replace(':id', id);
    // Load edit form via AJAX
    fetch(`/admin/customers/feedback/${id}/edit`)
        .then(res => res.json())
        .then(data => {
            document.getElementById('editFeedbackContent').innerHTML = data.html;
            new bootstrap.Modal(document.getElementById('editFeedbackModal')).show();
        });
}
</script>
@endpush
