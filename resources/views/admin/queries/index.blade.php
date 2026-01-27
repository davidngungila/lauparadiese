@extends('admin.layouts.app')

@section('title', 'Customer Queries - Lau Paradise Adventures')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">
                        <i class="ri-question-answer-line me-2"></i>Customer Queries Management
                    </h4>
                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createQueryModal">
                        <i class="ri-add-line me-1"></i>Create Query
                    </button>
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
                            <span class="avatar-initial rounded bg-label-primary"><i class="ri-mail-line"></i></span>
                        </div>
                        <div>
                            <h5 class="mb-0">{{ number_format(($stats ?? [])['total'] ?? 0) }}</h5>
                            <small class="text-muted">Total Queries</small>
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
                            <span class="avatar-initial rounded bg-label-warning"><i class="ri-time-line"></i></span>
                        </div>
                        <div>
                            <h5 class="mb-0">{{ number_format(($stats ?? [])['new'] ?? 0) }}</h5>
                            <small class="text-muted">New Queries</small>
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
                            <span class="avatar-initial rounded bg-label-success"><i class="ri-check-line"></i></span>
                        </div>
                        <div>
                            <h5 class="mb-0">{{ number_format(($stats ?? [])['replied'] ?? 0) }}</h5>
                            <small class="text-muted">Replied</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 mb-4">
            <div class="card card-border-shadow-danger">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="avatar me-3">
                            <span class="avatar-initial rounded bg-label-danger"><i class="ri-alert-line"></i></span>
                        </div>
                        <div>
                            <h5 class="mb-0">{{ number_format(($stats ?? [])['urgent'] ?? 0) }}</h5>
                            <small class="text-muted">Urgent</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('admin.queries.index') }}">
                <div class="row g-3">
                    <div class="col-md-2">
                        <label class="form-label">Status</label>
                        <select name="status" class="form-select">
                            <option value="">All Status</option>
                            <option value="new" {{ request('status') == 'new' ? 'selected' : '' }}>New</option>
                            <option value="read" {{ request('status') == 'read' ? 'selected' : '' }}>Read</option>
                            <option value="replied" {{ request('status') == 'replied' ? 'selected' : '' }}>Replied</option>
                            <option value="resolved" {{ request('status') == 'resolved' ? 'selected' : '' }}>Resolved</option>
                            <option value="archived" {{ request('status') == 'archived' ? 'selected' : '' }}>Archived</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Category</label>
                        <select name="category" class="form-select">
                            <option value="">All Categories</option>
                            <option value="booking" {{ request('category') == 'booking' ? 'selected' : '' }}>Booking</option>
                            <option value="tour" {{ request('category') == 'tour' ? 'selected' : '' }}>Tour</option>
                            <option value="support" {{ request('category') == 'support' ? 'selected' : '' }}>Support</option>
                            <option value="partnership" {{ request('category') == 'partnership' ? 'selected' : '' }}>Partnership</option>
                            <option value="other" {{ request('category') == 'other' ? 'selected' : '' }}>Other</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Priority</label>
                        <select name="priority" class="form-select">
                            <option value="">All Priorities</option>
                            <option value="urgent" {{ request('priority') == 'urgent' ? 'selected' : '' }}>Urgent</option>
                            <option value="high" {{ request('priority') == 'high' ? 'selected' : '' }}>High</option>
                            <option value="normal" {{ request('priority') == 'normal' ? 'selected' : '' }}>Normal</option>
                            <option value="low" {{ request('priority') == 'low' ? 'selected' : '' }}>Low</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Assigned To</label>
                        <select name="assigned_to" class="form-select">
                            <option value="">All Users</option>
                            @foreach($users ?? [] as $user)
                                <option value="{{ $user->id }}" {{ request('assigned_to') == $user->id ? 'selected' : '' }}>
                                    {{ $user->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Search</label>
                        <input type="text" name="search" class="form-control" placeholder="Name, email, subject..." value="{{ request('search') }}">
                    </div>
                    <div class="col-md-1 d-flex align-items-end gap-2">
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="ri-search-line"></i>
                        </button>
                        <a href="{{ route('admin.queries.index') }}" class="btn btn-label-secondary">
                            <i class="ri-refresh-line"></i>
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Queries Table -->
    <div class="card">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <div>
                    <button class="btn btn-sm btn-outline-primary" id="bulkActionsBtn" disabled>
                        <i class="ri-settings-3-line me-1"></i>Bulk Actions
                    </button>
                </div>
            </div>
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th width="30">
                                <input type="checkbox" id="selectAll" class="form-check-input">
                            </th>
                            <th>Customer</th>
                            <th>Subject</th>
                            <th>Category</th>
                            <th>Priority</th>
                            <th>Status</th>
                            <th>Assigned To</th>
                            <th>Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($queries as $query)
                        <tr>
                            <td>
                                <input type="checkbox" class="form-check-input query-checkbox" value="{{ $query->id }}">
                            </td>
                            <td>
                                <div>
                                    <strong>{{ $query->name }}</strong><br>
                                    <small class="text-muted">{{ $query->email }}</small>
                                </div>
                            </td>
                            <td>
                                <div>
                                    <strong>{{ $query->subject ?? 'No Subject' }}</strong>
                                    @if($query->status === 'new')
                                        <span class="badge bg-label-primary ms-1">New</span>
                                    @endif
                                </div>
                            </td>
                            <td>
                                <span class="badge bg-label-info">{{ ucfirst($query->category) }}</span>
                            </td>
                            <td>
                                <span class="badge bg-label-{{ $query->priority == 'urgent' ? 'danger' : ($query->priority == 'high' ? 'warning' : 'secondary') }}">
                                    {{ ucfirst($query->priority) }}
                                </span>
                            </td>
                            <td>
                                <span class="badge bg-label-{{ $query->status == 'new' ? 'warning' : ($query->status == 'replied' ? 'success' : ($query->status == 'resolved' ? 'info' : 'secondary')) }}">
                                    {{ ucfirst($query->status) }}
                                </span>
                            </td>
                            <td>
                                @if($query->assignedTo)
                                    <div class="d-flex align-items-center">
                                        <div class="avatar avatar-xs me-2">
                                            <span class="avatar-initial rounded bg-label-primary">
                                                {{ strtoupper(substr($query->assignedTo->name, 0, 2)) }}
                                            </span>
                                        </div>
                                        <span>{{ $query->assignedTo->name }}</span>
                                    </div>
                                @else
                                    <span class="text-muted">Unassigned</span>
                                @endif
                            </td>
                            <td>{{ $query->created_at->format('M d, Y') }}</td>
                            <td>
                                <div class="d-flex gap-1">
                                    <button class="btn btn-sm btn-icon btn-outline-primary view-query" 
                                            data-id="{{ $query->id }}"
                                            data-bs-toggle="modal" 
                                            data-bs-target="#viewQueryModal"
                                            title="View">
                                        <i class="ri-eye-line"></i>
                                    </button>
                                    <button class="btn btn-sm btn-icon btn-outline-info reply-query" 
                                            data-id="{{ $query->id }}"
                                            data-email="{{ $query->email }}"
                                            data-subject="{{ $query->subject }}"
                                            data-bs-toggle="modal" 
                                            data-bs-target="#replyQueryModal"
                                            title="Reply">
                                        <i class="ri-reply-line"></i>
                                    </button>
                                    <button class="btn btn-sm btn-icon btn-outline-warning edit-query" 
                                            data-id="{{ $query->id }}"
                                            data-bs-toggle="modal" 
                                            data-bs-target="#editQueryModal"
                                            title="Edit">
                                        <i class="ri-pencil-line"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="9" class="text-center py-4">
                                <p class="text-muted mb-0">No customer queries found</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-4">
                {{ $queries->links() }}
            </div>
        </div>
    </div>
</div>

<!-- View Query Modal -->
<div class="modal fade" id="viewQueryModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Query Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="viewQueryContent">
                <p class="text-center text-muted">Loading...</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary reply-query-from-view">
                    <i class="ri-reply-line me-1"></i>Reply
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Reply Query Modal -->
<div class="modal fade" id="replyQueryModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Reply to Query</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="replyQueryForm" method="POST">
                @csrf
                <div class="modal-body">
                    <input type="hidden" name="query_id" id="reply-query-id">
                    <div class="mb-3">
                        <label class="form-label">To</label>
                        <input type="text" id="reply-query-email" class="form-control" readonly>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Subject</label>
                        <input type="text" id="reply-query-subject" class="form-control" readonly>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Reply Message <span class="text-danger">*</span></label>
                        <textarea name="reply_message" class="form-control" rows="6" required></textarea>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="sendEmailReply" name="send_email" value="1" checked>
                        <label class="form-check-label" for="sendEmailReply">
                            Send email notification to customer
                        </label>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Send Reply</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Query Modal -->
<div class="modal fade" id="editQueryModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Query</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="editQueryForm" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <input type="hidden" name="query_id" id="edit-query-id">
                    <div class="mb-3">
                        <label class="form-label">Status <span class="text-danger">*</span></label>
                        <select name="status" id="edit-query-status" class="form-select" required>
                            <option value="new">New</option>
                            <option value="read">Read</option>
                            <option value="replied">Replied</option>
                            <option value="resolved">Resolved</option>
                            <option value="archived">Archived</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Priority <span class="text-danger">*</span></label>
                        <select name="priority" id="edit-query-priority" class="form-select" required>
                            <option value="low">Low</option>
                            <option value="normal">Normal</option>
                            <option value="high">High</option>
                            <option value="urgent">Urgent</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Assign To</label>
                        <select name="assigned_to" id="edit-query-assigned" class="form-select">
                            <option value="">Unassigned</option>
                            @foreach($users ?? [] as $user)
                                <option value="{{ $user->id }}">{{ $user->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Admin Notes</label>
                        <textarea name="admin_notes" id="edit-query-notes" class="form-control" rows="3"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Update Query</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Create Query Modal -->
<div class="modal fade" id="createQueryModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Create Customer Query</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="createQueryForm" method="POST" action="{{ route('admin.queries.store') }}">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Customer Name <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Email <span class="text-danger">*</span></label>
                        <input type="email" name="email" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Phone</label>
                        <input type="text" name="phone" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Subject</label>
                        <input type="text" name="subject" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Category <span class="text-danger">*</span></label>
                        <select name="category" class="form-select" required>
                            <option value="booking">Booking</option>
                            <option value="tour">Tour</option>
                            <option value="support">Support</option>
                            <option value="partnership">Partnership</option>
                            <option value="other">Other</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Priority</label>
                        <select name="priority" class="form-select">
                            <option value="normal">Normal</option>
                            <option value="low">Low</option>
                            <option value="high">High</option>
                            <option value="urgent">Urgent</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Message <span class="text-danger">*</span></label>
                        <textarea name="message" class="form-control" rows="5" required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Create Query</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Bulk Actions Modal -->
<div class="modal fade" id="bulkActionsModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Bulk Actions</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="bulkActionsForm" method="POST" action="{{ route('admin.queries.bulk-update') }}">
                @csrf
                <div class="modal-body">
                    <input type="hidden" name="query_ids" id="bulk-query-ids">
                    <div class="mb-3">
                        <label class="form-label">Action <span class="text-danger">*</span></label>
                        <select name="action" id="bulk-action" class="form-select" required>
                            <option value="">Select action...</option>
                            <option value="assign">Assign To User</option>
                            <option value="status">Change Status</option>
                            <option value="priority">Change Priority</option>
                            <option value="delete">Delete</option>
                        </select>
                    </div>
                    <div class="mb-3" id="bulk-value-container" style="display: none;">
                        <label class="form-label" id="bulk-value-label">Value</label>
                        <select name="value" id="bulk-value-select" class="form-select" style="display: none;">
                            <option value="">Select...</option>
                        </select>
                        <select name="value" id="bulk-assign-select" class="form-select" style="display: none;">
                            <option value="">Select user...</option>
                            @foreach($users ?? [] as $user)
                                <option value="{{ $user->id }}">{{ $user->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Apply</button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
$(document).ready(function() {
    // Checkbox selection
    $('#selectAll').on('change', function() {
        $('.query-checkbox').prop('checked', this.checked);
        updateBulkActionsBtn();
    });

    $('.query-checkbox').on('change', function() {
        updateBulkActionsBtn();
    });

    function updateBulkActionsBtn() {
        const checked = $('.query-checkbox:checked').length;
        $('#bulkActionsBtn').prop('disabled', checked === 0);
    }

    // Bulk actions
    $('#bulkActionsBtn').on('click', function() {
        const checked = $('.query-checkbox:checked').map(function() {
            return $(this).val();
        }).get();
        
        if (checked.length === 0) return;
        
        $('#bulk-query-ids').val(JSON.stringify(checked));
        $('#bulkActionsModal').modal('show');
    });

    $('#bulk-action').on('change', function() {
        const action = $(this).val();
        $('#bulk-value-container').toggle(action !== '');
        
        if (action === 'assign') {
            $('#bulk-assign-select').show();
            $('#bulk-value-select').hide();
            $('#bulk-value-label').text('Assign To');
        } else if (action === 'status') {
            $('#bulk-value-select').html(`
                <option value="new">New</option>
                <option value="read">Read</option>
                <option value="replied">Replied</option>
                <option value="resolved">Resolved</option>
                <option value="archived">Archived</option>
            `).show();
            $('#bulk-assign-select').hide();
            $('#bulk-value-label').text('Status');
        } else if (action === 'priority') {
            $('#bulk-value-select').html(`
                <option value="low">Low</option>
                <option value="normal">Normal</option>
                <option value="high">High</option>
                <option value="urgent">Urgent</option>
            `).show();
            $('#bulk-assign-select').hide();
            $('#bulk-value-label').text('Priority');
        } else {
            $('#bulk-value-select').hide();
            $('#bulk-assign-select').hide();
        }
    });

    // View Query
    $('.view-query').on('click', function() {
        const queryId = $(this).data('id');
        $.get(`/admin/queries/${queryId}`, function(data) {
            $('#viewQueryContent').html(data);
        }).fail(function() {
            $('#viewQueryContent').html('<p class="text-danger">Error loading query details</p>');
        });
    });

    // Reply Query
    $('.reply-query, .reply-query-from-view').on('click', function() {
        const queryId = $(this).data('id') || $('#viewQueryModal').data('query-id');
        const email = $(this).data('email') || '';
        const subject = $(this).data('subject') || '';
        
        $('#reply-query-id').val(queryId);
        $('#reply-query-email').val(email);
        $('#reply-query-subject').val('Re: ' + subject);
        $('#replyQueryForm').attr('action', `/admin/queries/${queryId}/reply`);
    });

    // Edit Query
    $('.edit-query').on('click', function() {
        const queryId = $(this).data('id');
        $.get(`/admin/queries/${queryId}/edit`, function(data) {
            // Load edit form data
        }).fail(function() {
            alert('Error loading query');
        });
    });

    // Form submissions
    $('#replyQueryForm').on('submit', function(e) {
        e.preventDefault();
        const form = $(this);
        $.ajax({
            url: form.attr('action'),
            method: 'POST',
            data: form.serialize(),
            success: function() {
                location.reload();
            },
            error: function() {
                alert('Error sending reply');
            }
        });
    });

    $('#editQueryForm').on('submit', function(e) {
        e.preventDefault();
        const form = $(this);
        const queryId = $('#edit-query-id').val();
        form.attr('action', `/admin/queries/${queryId}`);
        
        $.ajax({
            url: form.attr('action'),
            method: 'POST',
            data: form.serialize(),
            success: function() {
                location.reload();
            },
            error: function() {
                alert('Error updating query');
            }
        });
    });
});
</script>
@endpush
@endsection

