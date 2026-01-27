@extends('admin.layouts.app')

@section('title', 'Email Campaigns')
@section('description', 'Manage email marketing campaigns')

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex align-items-center justify-content-between">
                <h5 class="card-title mb-0">Email Campaigns</h5>
                <a href="{{ route('admin.marketing.email-campaigns.create') }}" class="btn btn-primary">
                    <i class="icon-base ri ri-add-line me-2"></i>Create Email Campaign
                </a>
            </div>
            <div class="card-body">
                <!-- Filters -->
                <form method="GET" action="{{ route('admin.marketing.email-campaigns') }}" class="mb-4">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <input type="text" name="search" class="form-control" placeholder="Search campaigns..." value="{{ request('search') }}">
                        </div>
                        <div class="col-md-3">
                            <select name="status" class="form-select">
                                <option value="">All Status</option>
                                <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                                <option value="scheduled" {{ request('status') == 'scheduled' ? 'selected' : '' }}>Scheduled</option>
                                <option value="sending" {{ request('status') == 'sending' ? 'selected' : '' }}>Sending</option>
                                <option value="sent" {{ request('status') == 'sent' ? 'selected' : '' }}>Sent</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <button type="submit" class="btn btn-primary w-100">Filter</button>
                        </div>
                        <div class="col-md-2">
                            <a href="{{ route('admin.marketing.email-campaigns') }}" class="btn btn-outline-secondary w-100">Reset</a>
                        </div>
                    </div>
                </form>

                <!-- Table -->
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Subject</th>
                                <th>Recipients</th>
                                <th>Status</th>
                                <th>Scheduled At</th>
                                <th>Sent At</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($campaigns as $campaign)
                            <tr>
                                <td><strong>{{ $campaign->name }}</strong></td>
                                <td>{{ $campaign->subject }}</td>
                                <td>{{ ucfirst($campaign->recipient_type) }}</td>
                                <td>
                                    @if($campaign->status == 'draft')
                                        <span class="badge bg-label-secondary">Draft</span>
                                    @elseif($campaign->status == 'scheduled')
                                        <span class="badge bg-label-info">Scheduled</span>
                                    @elseif($campaign->status == 'sending')
                                        <span class="badge bg-label-warning">Sending</span>
                                    @elseif($campaign->status == 'sent')
                                        <span class="badge bg-label-success">Sent</span>
                                    @else
                                        <span class="badge bg-label-danger">Cancelled</span>
                                    @endif
                                </td>
                                <td>{{ $campaign->scheduled_at ? $campaign->scheduled_at->format('M d, Y H:i') : '-' }}</td>
                                <td>{{ $campaign->sent_at ? $campaign->sent_at->format('M d, Y H:i') : '-' }}</td>
                                <td>
                                    <div class="d-flex gap-2">
                                        <a href="{{ route('admin.marketing.email-campaigns.edit', $campaign->id) }}" class="btn btn-sm btn-icon">
                                            <i class="icon-base ri ri-edit-line"></i>
                                        </a>
                                        @if($campaign->status == 'draft' || $campaign->status == 'scheduled')
                                        <form action="{{ route('admin.marketing.email-campaigns.send', $campaign->id) }}" method="POST" class="send-form">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-icon text-success" onclick="return confirm('Send this campaign now?')">
                                                <i class="icon-base ri ri-send-plane-line"></i>
                                            </button>
                                        </form>
                                        @endif
                                        @if($campaign->status != 'sending' && $campaign->status != 'sent')
                                        <form action="{{ route('admin.marketing.email-campaigns.destroy', $campaign->id) }}" method="POST" class="delete-form">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-icon text-danger" onclick="return confirm('Are you sure?')">
                                                <i class="icon-base ri ri-delete-bin-line"></i>
                                            </button>
                                        </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="text-center py-4">
                                    <div class="text-muted">
                                        <i class="icon-base ri ri-mail-line icon-48px mb-2 d-block"></i>
                                        <p>No email campaigns found</p>
                                        <a href="{{ route('admin.marketing.email-campaigns.create') }}" class="btn btn-primary btn-sm">Create Your First Email Campaign</a>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                @if($campaigns->hasPages())
                <div class="mt-4">
                    {{ $campaigns->links() }}
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
