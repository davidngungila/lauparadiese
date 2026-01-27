@extends('admin.layouts.app')

@section('title', 'Ticket Details - ' . ($ticket->ticket_number ?? 'TKT-001'))

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">
                        <i class="ri-customer-service-2-line me-2"></i>Ticket: {{ $ticket->ticket_number }}
                    </h4>
                    <div>
                        <a href="{{ route('admin.tickets.index') }}" class="btn btn-label-secondary me-2">
                            <i class="ri-arrow-left-line me-1"></i>Back
                        </a>
                        <a href="{{ route('admin.tickets.edit', $ticket->id) }}" class="btn btn-info">
                            <i class="ri-pencil-line me-1"></i>Edit
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8 mb-4">
            <!-- Ticket Details -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">Ticket Information</h5>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label text-muted">Subject</label>
                            <p class="mb-0"><strong>{{ $ticket->subject }}</strong></p>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-muted">Category</label>
                            <p class="mb-0">
                                <span class="badge bg-label-info">{{ ucfirst($ticket->category) }}</span>
                            </p>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label text-muted">Description</label>
                        <div class="border rounded p-3 bg-light">
                            {{ $ticket->description }}
                        </div>
                    </div>
                    @if($ticket->resolution_notes)
                    <div class="mb-3">
                        <label class="form-label text-muted">Resolution Notes</label>
                        <div class="border rounded p-3 bg-light">
                            {{ $ticket->resolution_notes }}
                        </div>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Replies -->
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Replies & Updates</h5>
                    <button class="btn btn-sm btn-primary reply-ticket-btn" 
                            data-id="{{ $ticket->id }}"
                            data-bs-toggle="modal" 
                            data-bs-target="#replyTicketModal">
                        <i class="ri-reply-line me-1"></i>Add Reply
                    </button>
                </div>
                <div class="card-body">
                    @forelse($ticket->replies ?? [] as $reply)
                    <div class="d-flex align-items-start mb-4 pb-4 border-bottom">
                        <div class="avatar me-3">
                            <span class="avatar-initial rounded bg-label-{{ $reply->reply_type == 'staff' ? 'primary' : 'success' }}">
                                {{ strtoupper(substr($reply->createdBy->name ?? 'U', 0, 2)) }}
                            </span>
                        </div>
                        <div class="flex-grow-1">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <div>
                                    <strong>{{ $reply->createdBy->name ?? 'System' }}</strong>
                                    <span class="badge bg-label-{{ $reply->reply_type == 'staff' ? 'primary' : 'success' }} ms-2">
                                        {{ ucfirst($reply->reply_type) }}
                                    </span>
                                    @if($reply->is_internal)
                                        <span class="badge bg-label-secondary ms-1">Internal</span>
                                    @endif
                                </div>
                                <small class="text-muted">{{ $reply->created_at->format('M d, Y H:i') }}</small>
                            </div>
                            <p class="mb-0">{{ $reply->message }}</p>
                        </div>
                    </div>
                    @empty
                    <p class="text-muted text-center py-4">No replies yet</p>
                    @endforelse
                </div>
            </div>
        </div>

        <div class="col-lg-4 mb-4">
            <!-- Status Card -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">Status & Details</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label text-muted">Status</label>
                        <p class="mb-0">
                            <span class="badge bg-label-{{ $ticket->status == 'open' ? 'warning' : ($ticket->status == 'resolved' ? 'success' : ($ticket->status == 'closed' ? 'secondary' : 'info')) }}">
                                {{ ucfirst(str_replace('_', ' ', $ticket->status)) }}
                            </span>
                        </p>
                    </div>
                    <div class="mb-3">
                        <label class="form-label text-muted">Priority</label>
                        <p class="mb-0">
                            <span class="badge bg-label-{{ $ticket->priority == 'urgent' ? 'danger' : ($ticket->priority == 'high' ? 'warning' : 'secondary') }}">
                                {{ ucfirst($ticket->priority) }}
                            </span>
                        </p>
                    </div>
                    @if($ticket->assignedTo)
                    <div class="mb-3">
                        <label class="form-label text-muted">Assigned To</label>
                        <p class="mb-0">{{ $ticket->assignedTo->name }}</p>
                    </div>
                    @endif
                    <div class="mb-3">
                        <label class="form-label text-muted">Created</label>
                        <p class="mb-0">{{ $ticket->created_at->format('F d, Y H:i') }}</p>
                    </div>
                    @if($ticket->resolved_at)
                    <div class="mb-3">
                        <label class="form-label text-muted">Resolved</label>
                        <p class="mb-0">{{ $ticket->resolved_at->format('F d, Y H:i') }}</p>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Customer Info -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">Customer Information</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label text-muted">Name</label>
                        <p class="mb-0"><strong>{{ $ticket->customer_name }}</strong></p>
                    </div>
                    <div class="mb-3">
                        <label class="form-label text-muted">Email</label>
                        <p class="mb-0">{{ $ticket->customer_email }}</p>
                    </div>
                    @if($ticket->customer_phone)
                    <div class="mb-3">
                        <label class="form-label text-muted">Phone</label>
                        <p class="mb-0">{{ $ticket->customer_phone }}</p>
                    </div>
                    @endif
                    @if($ticket->booking)
                    <div class="mb-3">
                        <label class="form-label text-muted">Related Booking</label>
                        <p class="mb-0">
                            <a href="{{ route('admin.bookings.show', $ticket->booking->id) }}" class="text-primary">
                                {{ $ticket->booking->booking_reference }}
                            </a>
                        </p>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Quick Actions</h5>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <button class="btn btn-primary reply-ticket-btn" 
                                data-id="{{ $ticket->id }}"
                                data-bs-toggle="modal" 
                                data-bs-target="#replyTicketModal">
                            <i class="ri-reply-line me-1"></i>Add Reply
                        </button>
                        @if($ticket->status !== 'resolved' && $ticket->status !== 'closed')
                        <button class="btn btn-success resolve-ticket-btn" 
                                data-id="{{ $ticket->id }}"
                                data-bs-toggle="modal" 
                                data-bs-target="#resolveTicketModal">
                            <i class="ri-check-line me-1"></i>Resolve Ticket
                        </button>
                        @endif
                        <a href="{{ route('admin.tickets.edit', $ticket->id) }}" class="btn btn-outline-info">
                            <i class="ri-pencil-line me-1"></i>Edit Ticket
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Reply Ticket Modal -->
<div class="modal fade" id="replyTicketModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add Reply</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="replyTicketForm" method="POST">
                @csrf
                <div class="modal-body">
                    <input type="hidden" name="ticket_id" id="reply-ticket-id">
                    <div class="mb-3">
                        <label class="form-label">Reply Message <span class="text-danger">*</span></label>
                        <textarea name="message" class="form-control" rows="6" required></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Update Status</label>
                        <select name="update_status" class="form-select">
                            <option value="">Keep Current Status</option>
                            <option value="in_progress">In Progress</option>
                            <option value="waiting_customer">Waiting Customer</option>
                        </select>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="isInternalReply" name="is_internal" value="1">
                        <label class="form-check-label" for="isInternalReply">
                            Internal note (not visible to customer)
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

<!-- Resolve Ticket Modal -->
<div class="modal fade" id="resolveTicketModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Resolve Ticket</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="resolveTicketForm" method="POST">
                @csrf
                <div class="modal-body">
                    <input type="hidden" name="ticket_id" id="resolve-ticket-id">
                    <div class="mb-3">
                        <label class="form-label">Resolution Notes <span class="text-danger">*</span></label>
                        <textarea name="resolution_notes" class="form-control" rows="5" required placeholder="Describe how the ticket was resolved..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success">Resolve Ticket</button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
$(document).ready(function() {
    $('.reply-ticket-btn').on('click', function() {
        const ticketId = $(this).data('id');
        $('#reply-ticket-id').val(ticketId);
        $('#replyTicketForm').attr('action', `/admin/tickets/${ticketId}/reply`);
    });

    $('.resolve-ticket-btn').on('click', function() {
        const ticketId = $(this).data('id');
        $('#resolve-ticket-id').val(ticketId);
        $('#resolveTicketForm').attr('action', `/admin/tickets/${ticketId}/resolve`);
    });

    $('#replyTicketForm').on('submit', function(e) {
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

    $('#resolveTicketForm').on('submit', function(e) {
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
                alert('Error resolving ticket');
            }
        });
    });
});
</script>
@endpush
@endsection

