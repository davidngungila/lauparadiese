@extends('admin.layouts.app')

@section('title', 'Support Tickets - Lau Paradise Adventures')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">
                        <i class="ri-customer-service-2-line me-2"></i>Support Tickets Management
                    </h4>
                    <a href="{{ route('admin.tickets.create') }}" class="btn btn-primary">
                        <i class="ri-add-line me-1"></i>Create Ticket
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
                            <span class="avatar-initial rounded bg-label-primary"><i class="ri-ticket-line"></i></span>
                        </div>
                        <div>
                            <h5 class="mb-0">{{ number_format(($stats ?? [])['total'] ?? 0) }}</h5>
                            <small class="text-muted">Total Tickets</small>
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
                            <h5 class="mb-0">{{ number_format(($stats ?? [])['open'] ?? 0) }}</h5>
                            <small class="text-muted">Open Tickets</small>
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
                            <h5 class="mb-0">{{ number_format(($stats ?? [])['resolved'] ?? 0) }}</h5>
                            <small class="text-muted">Resolved</small>
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
            <form method="GET" action="{{ route('admin.tickets.index') }}">
                <div class="row g-3">
                    <div class="col-md-2">
                        <label class="form-label">Status</label>
                        <select name="status" class="form-select">
                            <option value="">All Status</option>
                            <option value="open" {{ request('status') == 'open' ? 'selected' : '' }}>Open</option>
                            <option value="in_progress" {{ request('status') == 'in_progress' ? 'selected' : '' }}>In Progress</option>
                            <option value="waiting_customer" {{ request('status') == 'waiting_customer' ? 'selected' : '' }}>Waiting Customer</option>
                            <option value="resolved" {{ request('status') == 'resolved' ? 'selected' : '' }}>Resolved</option>
                            <option value="closed" {{ request('status') == 'closed' ? 'selected' : '' }}>Closed</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Category</label>
                        <select name="category" class="form-select">
                            <option value="">All Categories</option>
                            <option value="technical" {{ request('category') == 'technical' ? 'selected' : '' }}>Technical</option>
                            <option value="billing" {{ request('category') == 'billing' ? 'selected' : '' }}>Billing</option>
                            <option value="booking" {{ request('category') == 'booking' ? 'selected' : '' }}>Booking</option>
                            <option value="refund" {{ request('category') == 'refund' ? 'selected' : '' }}>Refund</option>
                            <option value="general" {{ request('category') == 'general' ? 'selected' : '' }}>General</option>
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
                        <input type="text" name="search" class="form-control" placeholder="Ticket #, customer, subject..." value="{{ request('search') }}">
                    </div>
                    <div class="col-md-1 d-flex align-items-end gap-2">
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="ri-search-line"></i>
                        </button>
                        <a href="{{ route('admin.tickets.index') }}" class="btn btn-label-secondary">
                            <i class="ri-refresh-line"></i>
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Tickets Table -->
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Ticket #</th>
                            <th>Customer</th>
                            <th>Subject</th>
                            <th>Category</th>
                            <th>Priority</th>
                            <th>Status</th>
                            <th>Assigned To</th>
                            <th>Created</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($tickets as $ticket)
                        <tr>
                            <td>
                                <strong>{{ $ticket->ticket_number }}</strong>
                            </td>
                            <td>
                                <div>
                                    <strong>{{ $ticket->customer_name }}</strong><br>
                                    <small class="text-muted">{{ $ticket->customer_email }}</small>
                                </div>
                            </td>
                            <td>
                                <div>
                                    <strong>{{ $ticket->subject }}</strong>
                                    @if($ticket->booking)
                                        <br><small class="text-muted">Booking: {{ $ticket->booking->booking_reference }}</small>
                                    @endif
                                </div>
                            </td>
                            <td>
                                <span class="badge bg-label-info">{{ ucfirst($ticket->category) }}</span>
                            </td>
                            <td>
                                <span class="badge bg-label-{{ $ticket->priority == 'urgent' ? 'danger' : ($ticket->priority == 'high' ? 'warning' : 'secondary') }}">
                                    {{ ucfirst($ticket->priority) }}
                                </span>
                            </td>
                            <td>
                                <span class="badge bg-label-{{ $ticket->status == 'open' ? 'warning' : ($ticket->status == 'resolved' ? 'success' : ($ticket->status == 'closed' ? 'secondary' : 'info')) }}">
                                    {{ ucfirst(str_replace('_', ' ', $ticket->status)) }}
                                </span>
                            </td>
                            <td>
                                @if($ticket->assignedTo)
                                    <div class="d-flex align-items-center">
                                        <div class="avatar avatar-xs me-2">
                                            <span class="avatar-initial rounded bg-label-primary">
                                                {{ strtoupper(substr($ticket->assignedTo->name, 0, 2)) }}
                                            </span>
                                        </div>
                                        <span>{{ $ticket->assignedTo->name }}</span>
                                    </div>
                                @else
                                    <span class="text-muted">Unassigned</span>
                                @endif
                            </td>
                            <td>{{ $ticket->created_at->format('M d, Y') }}</td>
                            <td>
                                <div class="d-flex gap-1">
                                    <a href="{{ route('admin.tickets.show', $ticket->id) }}" 
                                       class="btn btn-sm btn-icon btn-outline-primary"
                                       title="View">
                                        <i class="ri-eye-line"></i>
                                    </a>
                                    <button class="btn btn-sm btn-icon btn-outline-success reply-ticket" 
                                            data-id="{{ $ticket->id }}"
                                            data-bs-toggle="modal" 
                                            data-bs-target="#replyTicketModal"
                                            title="Reply">
                                        <i class="ri-reply-line"></i>
                                    </button>
                                    <a href="{{ route('admin.tickets.edit', $ticket->id) }}" 
                                       class="btn btn-sm btn-icon btn-outline-info"
                                       title="Edit">
                                        <i class="ri-pencil-line"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="9" class="text-center py-4">
                                <p class="text-muted mb-0">No support tickets found</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-4">
                {{ $tickets->links() }}
            </div>
        </div>
    </div>
</div>

<!-- Reply Ticket Modal -->
<div class="modal fade" id="replyTicketModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Reply to Ticket</h5>
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

@push('scripts')
<script>
$(document).ready(function() {
    $('.reply-ticket').on('click', function() {
        const ticketId = $(this).data('id');
        $('#reply-ticket-id').val(ticketId);
        $('#replyTicketForm').attr('action', `/admin/tickets/${ticketId}/reply`);
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
});
</script>
@endpush
@endsection

