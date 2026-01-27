@extends('admin.layouts.app')

@section('title', 'Customer Messages - Lau Paradise Adventures')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <!-- Page Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">
                        <i class="ri-message-3-line me-2"></i>Customer Messages (Inbox)
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
                                <i class="ri-message-3-line"></i>
                            </span>
                        </div>
                        <div>
                            <h5 class="mb-0">{{ number_format($stats['total'] ?? 0) }}</h5>
                            <small class="text-muted">Total Messages</small>
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
                                <i class="ri-mail-unread-line"></i>
                            </span>
                        </div>
                        <div>
                            <h5 class="mb-0">{{ number_format($stats['new'] ?? 0) }}</h5>
                            <small class="text-muted">New Messages</small>
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
                            <span class="avatar-initial rounded bg-label-danger">
                                <i class="ri-mail-line"></i>
                            </span>
                        </div>
                        <div>
                            <h5 class="mb-0">{{ number_format($stats['unread'] ?? 0) }}</h5>
                            <small class="text-muted">Unread</small>
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
                            <h5 class="mb-0">{{ number_format($stats['important'] ?? 0) }}</h5>
                            <small class="text-muted">Important</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('admin.customers.messages') }}">
                <div class="row g-3">
                    <div class="col-md-3">
                        <label class="form-label">Status</label>
                        <select name="status" class="form-select">
                            <option value="">All Status</option>
                            <option value="new" {{ request('status') == 'new' ? 'selected' : '' }}>New</option>
                            <option value="open" {{ request('status') == 'open' ? 'selected' : '' }}>Open</option>
                            <option value="in_progress" {{ request('status') == 'in_progress' ? 'selected' : '' }}>In Progress</option>
                            <option value="resolved" {{ request('status') == 'resolved' ? 'selected' : '' }}>Resolved</option>
                            <option value="closed" {{ request('status') == 'closed' ? 'selected' : '' }}>Closed</option>
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
                    <div class="col-md-3">
                        <label class="form-label">Assigned To</label>
                        <select name="assigned_to" class="form-select">
                            <option value="">All Staff</option>
                            @foreach($staff ?? [] as $member)
                                <option value="{{ $member->id }}" {{ request('assigned_to') == $member->id ? 'selected' : '' }}>{{ $member->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <div class="form-check mt-4">
                            <input class="form-check-input" type="checkbox" name="unread_only" id="unread_only" value="1" {{ request('unread_only') ? 'checked' : '' }}>
                            <label class="form-check-label" for="unread_only">
                                Unread Only
                            </label>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-check mt-4">
                            <input class="form-check-input" type="checkbox" name="important_only" id="important_only" value="1" {{ request('important_only') ? 'checked' : '' }}>
                            <label class="form-check-label" for="important_only">
                                Important Only
                            </label>
                        </div>
                    </div>
                </div>
                <div class="row g-3 mt-2">
                    <div class="col-md-12">
                        <button type="submit" class="btn btn-primary">
                            <i class="ri-search-line me-1"></i>Filter
                        </button>
                        <a href="{{ route('admin.customers.messages') }}" class="btn btn-outline-secondary">
                            <i class="ri-refresh-line me-1"></i>Reset
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Messages List -->
    <div class="card">
        <div class="card-body">
            @if($messages->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th width="40">
                                    <input type="checkbox" id="selectAllMessages">
                                </th>
                                <th>Customer</th>
                                <th>Subject</th>
                                <th>Message</th>
                                <th>Assigned To</th>
                                <th>Priority</th>
                                <th>Status</th>
                                <th>Date</th>
                                <th class="text-end">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($messages as $message)
                                <tr class="{{ !$message->is_read ? 'table-active' : '' }}">
                                    <td>
                                        <input type="checkbox" class="message-checkbox" value="{{ $message->id }}">
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="avatar me-2">
                                                <span class="avatar-initial rounded bg-label-primary">
                                                    {{ strtoupper(substr($message->customer->name ?? 'U', 0, 1)) }}
                                                </span>
                                            </div>
                                            <div>
                                                <strong>{{ $message->customer->name ?? 'N/A' }}</strong>
                                                <br><small class="text-muted">{{ $message->customer->email ?? '' }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <strong>{{ $message->subject ?? 'No Subject' }}</strong>
                                        @if($message->is_important)
                                            <i class="ri-star-fill text-warning ms-1"></i>
                                        @endif
                                    </td>
                                    <td>
                                        <small>{{ Str::limit($message->message, 60) }}</small>
                                    </td>
                                    <td>
                                        {{ $message->assignedStaff->name ?? 'Unassigned' }}
                                    </td>
                                    <td>
                                        @php
                                            $priorityClass = match($message->priority) {
                                                'urgent' => 'danger',
                                                'high' => 'warning',
                                                'normal' => 'info',
                                                'low' => 'secondary',
                                                default => 'secondary'
                                            };
                                        @endphp
                                        <span class="badge bg-label-{{ $priorityClass }}">{{ ucfirst($message->priority) }}</span>
                                    </td>
                                    <td>
                                        @php
                                            $statusClass = match($message->status) {
                                                'new' => 'primary',
                                                'open' => 'info',
                                                'in_progress' => 'warning',
                                                'resolved' => 'success',
                                                'closed' => 'secondary',
                                                default => 'secondary'
                                            };
                                        @endphp
                                        <span class="badge bg-label-{{ $statusClass }}">{{ ucfirst(str_replace('_', ' ', $message->status)) }}</span>
                                    </td>
                                    <td>
                                        <small>{{ $message->created_at->format('M d, Y') }}</small>
                                        <br><small class="text-muted">{{ $message->created_at->format('h:i A') }}</small>
                                    </td>
                                    <td>
                                        <div class="d-flex justify-content-end gap-1">
                                            <button type="button" class="btn btn-sm btn-icon btn-outline-primary" onclick="viewMessage({{ $message->id }})" title="View & Reply">
                                                <i class="ri-eye-line"></i>
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
                    {{ $messages->links() }}
                </div>
            @else
                <div class="text-center py-5">
                    <i class="ri-message-3-line" style="font-size: 64px; color: #ccc;"></i>
                    <h5 class="mt-3">No Messages Found</h5>
                    <p class="text-muted">Customer messages will appear here.</p>
                </div>
            @endif
        </div>
    </div>
</div>

<!-- View Message Modal -->
<div class="modal fade" id="viewMessageModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Message Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="messageDetails">
                <!-- Content loaded via AJAX -->
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function viewMessage(id) {
    // Load message details and reply form via AJAX
    fetch(`/admin/customers/messages/${id}`)
        .then(res => res.json())
        .then(data => {
            document.getElementById('messageDetails').innerHTML = data.html;
            new bootstrap.Modal(document.getElementById('viewMessageModal')).show();
        });
}

document.getElementById('selectAllMessages')?.addEventListener('change', function() {
    document.querySelectorAll('.message-checkbox').forEach(cb => {
        cb.checked = this.checked;
    });
});
</script>
@endpush
