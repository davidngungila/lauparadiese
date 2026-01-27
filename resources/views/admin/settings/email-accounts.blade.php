@extends('admin.layouts.app')

@section('title', 'Email Accounts Settings')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header border-bottom d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">Email Accounts</h5>
                <a href="{{ route('admin.settings.email-accounts.create') }}" class="btn btn-primary btn-sm">
                    <i class="ri-add-line me-1"></i>Add Email Account
                </a>
            </div>
            
            <div class="card-datatable">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Protocol</th>
                                <th>Status</th>
                                <th>Messages</th>
                                <th>Last Checked</th>
                                <th width="150">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($accounts as $account)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="avatar avatar-sm me-2">
                                            <span class="avatar-initial rounded-circle bg-label-primary">
                                                {{ strtoupper(substr($account->name, 0, 1)) }}
                                            </span>
                                        </div>
                                        <div>
                                            <div class="fw-medium">{{ $account->name }}</div>
                                            @if($account->is_default)
                                                <small class="text-muted">Default</small>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td>{{ $account->email }}</td>
                                <td>
                                    <span class="badge bg-label-info">{{ strtoupper($account->protocol) }}</span>
                                </td>
                                <td>
                                    @if($account->is_active)
                                        <span class="badge bg-label-success">Active</span>
                                    @else
                                        <span class="badge bg-label-secondary">Inactive</span>
                                    @endif
                                </td>
                                <td>{{ $account->messages_count }}</td>
                                <td>
                                    @if($account->last_checked_at)
                                        <small class="text-muted">{{ $account->last_checked_at->diffForHumans() }}</small>
                                    @else
                                        <small class="text-muted">Never</small>
                                    @endif
                                </td>
                                <td>
                                    <div class="d-flex gap-1">
                                        <a href="{{ route('admin.settings.email-accounts.edit', $account) }}" class="btn btn-sm btn-icon" title="Edit">
                                            <i class="ri-edit-line"></i>
                                        </a>
                                        <form action="{{ route('admin.settings.email-accounts.test', $account) }}" method="POST" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-icon" title="Test Connection">
                                                <i class="ri-checkbox-circle-line"></i>
                                            </button>
                                        </form>
                                        <button type="button" class="btn btn-sm btn-icon btn-success" title="Send Test Email" 
                                                onclick="showTestEmailModal({{ $account->id }}, '{{ $account->email }}', '{{ $account->name }}')">
                                            <i class="ri-mail-send-line"></i>
                                        </button>
                                        <form action="{{ route('admin.settings.email-accounts.destroy', $account) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-icon text-danger" title="Delete" onclick="return confirm('Are you sure?')">
                                                <i class="ri-delete-bin-line"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="text-center py-5">
                                    <div class="text-muted">
                                        <i class="ri-mail-settings-line ri-48px mb-3 d-block"></i>
                                        <p>No email accounts configured</p>
                                        <a href="{{ route('admin.settings.email-accounts.create') }}" class="btn btn-primary btn-sm">Add Email Account</a>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

<!-- Test Email Modal -->
<div class="modal fade" id="testEmailModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Send Test Email</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="testEmailForm" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Recipient Email <span class="text-danger">*</span></label>
                        <input type="email" name="test_email" class="form-control" value="davidngungila@gmail.com" required>
                        <small class="text-muted">Enter the email address to send the test email to</small>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Subject</label>
                        <input type="text" name="subject" class="form-control" value="" placeholder="Leave blank for default">
                    </div>
                    <div class="alert alert-info">
                        <i class="ri-information-line me-2"></i>
                        A beautifully formatted HTML test email will be sent to verify your SMTP configuration.
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="ri-send-plane-line me-1"></i>Send Test Email
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
    function showTestEmailModal(accountId, accountEmail, accountName) {
        const modal = new bootstrap.Modal(document.getElementById('testEmailModal'));
        const form = document.getElementById('testEmailForm');
        form.action = '{{ url("admin/settings/email-accounts") }}/' + accountId + '/send-test-email';
        modal.show();
    }

    document.getElementById('testEmailForm')?.addEventListener('submit', function(e) {
        const submitBtn = this.querySelector('button[type="submit"]');
        const originalText = submitBtn.innerHTML;
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span>Sending...';
    });
</script>
@endpush




