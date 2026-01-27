@extends('admin.layouts.app')

@section('title', 'Query Details - Lau Paradise Adventures')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">
                        <i class="ri-question-answer-line me-2"></i>Query Details
                    </h4>
                    <a href="{{ route('admin.queries.index') }}" class="btn btn-label-secondary">
                        <i class="ri-arrow-left-line me-1"></i>Back to Queries
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8 mb-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Query Information</h5>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label text-muted">Customer Name</label>
                            <p class="mb-0"><strong>{{ $query->name }}</strong></p>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-muted">Email</label>
                            <p class="mb-0">{{ $query->email }}</p>
                        </div>
                    </div>
                    @if($query->phone)
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label text-muted">Phone</label>
                            <p class="mb-0">{{ $query->phone }}</p>
                        </div>
                    </div>
                    @endif
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label text-muted">Subject</label>
                            <p class="mb-0">{{ $query->subject ?? 'No Subject' }}</p>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-muted">Category</label>
                            <p class="mb-0">
                                <span class="badge bg-label-info">{{ ucfirst($query->category) }}</span>
                            </p>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label text-muted">Message</label>
                        <div class="border rounded p-3 bg-light">
                            {{ $query->message }}
                        </div>
                    </div>
                    @if($query->admin_notes)
                    <div class="mb-3">
                        <label class="form-label text-muted">Admin Notes</label>
                        <div class="border rounded p-3 bg-light">
                            {{ $query->admin_notes }}
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-lg-4 mb-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Status & Actions</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label text-muted">Status</label>
                        <p class="mb-0">
                            <span class="badge bg-label-{{ $query->status == 'new' ? 'warning' : ($query->status == 'replied' ? 'success' : ($query->status == 'resolved' ? 'info' : 'secondary')) }}">
                                {{ ucfirst($query->status) }}
                            </span>
                        </p>
                    </div>
                    <div class="mb-3">
                        <label class="form-label text-muted">Priority</label>
                        <p class="mb-0">
                            <span class="badge bg-label-{{ $query->priority == 'urgent' ? 'danger' : ($query->priority == 'high' ? 'warning' : 'secondary') }}">
                                {{ ucfirst($query->priority) }}
                            </span>
                        </p>
                    </div>
                    @if($query->assignedTo)
                    <div class="mb-3">
                        <label class="form-label text-muted">Assigned To</label>
                        <p class="mb-0">{{ $query->assignedTo->name }}</p>
                    </div>
                    @endif
                    @if($query->repliedBy)
                    <div class="mb-3">
                        <label class="form-label text-muted">Replied By</label>
                        <p class="mb-0">{{ $query->repliedBy->name }}</p>
                        <small class="text-muted">{{ $query->replied_at ? $query->replied_at->format('M d, Y H:i') : '' }}</small>
                    </div>
                    @endif
                    <div class="mb-3">
                        <label class="form-label text-muted">Date</label>
                        <p class="mb-0">{{ $query->created_at->format('F d, Y H:i') }}</p>
                    </div>
                    <hr>
                    <div class="d-grid gap-2">
                        <button class="btn btn-primary reply-query-btn" 
                                data-id="{{ $query->id }}"
                                data-email="{{ $query->email }}"
                                data-subject="{{ $query->subject }}"
                                data-bs-toggle="modal" 
                                data-bs-target="#replyQueryModal">
                            <i class="ri-reply-line me-1"></i>Reply to Query
                        </button>
                        <a href="{{ route('admin.queries.edit', $query->id) }}" class="btn btn-outline-info">
                            <i class="ri-pencil-line me-1"></i>Edit Query
                        </a>
                    </div>
                </div>
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

@push('scripts')
<script>
$(document).ready(function() {
    $('.reply-query-btn').on('click', function() {
        const queryId = $(this).data('id');
        const email = $(this).data('email');
        const subject = $(this).data('subject');
        
        $('#reply-query-id').val(queryId);
        $('#reply-query-email').val(email);
        $('#reply-query-subject').val('Re: ' + subject);
        $('#replyQueryForm').attr('action', `/admin/queries/${queryId}/reply`);
    });

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
});
</script>
@endpush
@endsection

