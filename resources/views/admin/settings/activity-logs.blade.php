@extends('admin.layouts.app')

@section('title', 'Activity Logs - Advanced')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">
                        <i class="ri-file-list-line me-2"></i>Activity Logs
                    </h4>
                    <div class="d-flex gap-2">
                        <a href="{{ route('admin.settings.activity-logs.export', request()->query()) }}" class="btn btn-sm btn-outline-success">
                            <i class="ri-download-line me-1"></i>Export CSV
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    @if(isset($stats))
    <div class="row mb-4">
        <div class="col-lg-3 col-md-6 mb-4">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="mb-1">Total Records</h6>
                            <h4 class="mb-0">{{ number_format($stats['total'] ?? 0) }}</h4>
                        </div>
                        <div class="avatar">
                            <div class="avatar-initial bg-label-primary rounded">
                                <i class="ri-file-list-line ri-24px"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 mb-4">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="mb-1">Today</h6>
                            <h4 class="mb-0">{{ number_format($stats['today'] ?? 0) }}</h4>
                        </div>
                        <div class="avatar">
                            <div class="avatar-initial bg-label-success rounded">
                                <i class="ri-calendar-check-line ri-24px"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 mb-4">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="mb-1">This Week</h6>
                            <h4 class="mb-0">{{ number_format($stats['this_week'] ?? 0) }}</h4>
                        </div>
                        <div class="avatar">
                            <div class="avatar-initial bg-label-info rounded">
                                <i class="ri-calendar-line ri-24px"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 mb-4">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="mb-1">This Month</h6>
                            <h4 class="mb-0">{{ number_format($stats['this_month'] ?? 0) }}</h4>
                        </div>
                        <div class="avatar">
                            <div class="avatar-initial bg-label-warning rounded">
                                <i class="ri-calendar-2-line ri-24px"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Filters -->
    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('admin.settings.activity-logs') }}" class="row g-3">
                <div class="col-md-3">
                    <label class="form-label">User</label>
                    <select name="causer_id" class="form-select">
                        <option value="">All Users</option>
                        @foreach($users ?? [] as $user)
                            <option value="{{ $user->id }}" {{ request('causer_id') == $user->id ? 'selected' : '' }}>
                                {{ $user->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label">Log Name</label>
                    <select name="log_name" class="form-select">
                        <option value="">All Logs</option>
                        @foreach($logNames ?? [] as $logName)
                            <option value="{{ $logName }}" {{ request('log_name') == $logName ? 'selected' : '' }}>
                                {{ $logName }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label">Event</label>
                    <select name="event" class="form-select">
                        <option value="">All Events</option>
                        @foreach($events ?? [] as $event)
                            <option value="{{ $event }}" {{ request('event') == $event ? 'selected' : '' }}>
                                {{ ucfirst($event) }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label">Subject Type</label>
                    <select name="subject_type" class="form-select">
                        <option value="">All Types</option>
                        @foreach($subjectTypes ?? [] as $type)
                            <option value="{{ $type }}" {{ request('subject_type') == $type ? 'selected' : '' }}>
                                {{ class_basename($type) }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Date From</label>
                    <input type="date" name="date_from" class="form-control" value="{{ request('date_from') }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Date To</label>
                    <input type="date" name="date_to" class="form-control" value="{{ request('date_to') }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Search</label>
                    <input type="text" name="search" class="form-control" placeholder="Search..." value="{{ request('search') }}">
                </div>
                <div class="col-md-3 d-flex align-items-end gap-2">
                    <button type="submit" class="btn btn-primary">
                        <i class="ri-search-line me-1"></i>Filter
                    </button>
                    <a href="{{ route('admin.settings.activity-logs') }}" class="btn btn-outline-secondary">
                        <i class="ri-refresh-line"></i>
                    </a>
                </div>
            </form>
        </div>
    </div>

    {{-- Advanced Insights --}}
    @if(isset($stats))
    <div class="row mb-4">
        <div class="col-lg-4 mb-4">
            <div class="card h-100">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">Top Log Names</h5>
                    <span class="badge bg-label-primary">{{ ($stats['by_log_name'] ?? collect([]))->count() }}</span>
                </div>
                <div class="card-body">
                    @forelse($stats['by_log_name'] ?? collect([]) as $item)
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <div class="d-flex align-items-center">
                                <span class="badge bg-label-info me-2">{{ $item->log_name ?? 'N/A' }}</span>
                            </div>
                            <span class="fw-medium">{{ number_format($item->count ?? 0) }}</span>
                        </div>
                    @empty
                        <p class="text-muted mb-0">No log name statistics available yet.</p>
                    @endforelse
                </div>
            </div>
        </div>
        <div class="col-lg-4 mb-4">
            <div class="card h-100">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">Top Events</h5>
                    <span class="badge bg-label-success">{{ ($stats['by_event'] ?? collect([]))->count() }}</span>
                </div>
                <div class="card-body">
                    @forelse($stats['by_event'] ?? collect([]) as $item)
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <div class="d-flex align-items-center">
                                <span class="badge bg-label-success me-2">{{ ucfirst($item->event ?? 'N/A') }}</span>
                            </div>
                            <span class="fw-medium">{{ number_format($item->count ?? 0) }}</span>
                        </div>
                    @empty
                        <p class="text-muted mb-0">No event statistics available yet.</p>
                    @endforelse
                </div>
            </div>
        </div>
        <div class="col-lg-4 mb-4">
            <div class="card h-100">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">Top Active Users</h5>
                    <span class="badge bg-label-warning">{{ ($stats['top_users'] ?? collect([]))->count() }}</span>
                </div>
                <div class="card-body">
                    @forelse($stats['top_users'] ?? collect([]) as $item)
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <div class="d-flex align-items-center">
                                <div class="avatar avatar-sm me-2">
                                    <span class="avatar-initial rounded-circle bg-label-primary">
                                        {{ isset($item['user_name']) ? substr($item['user_name'], 0, 1) : 'U' }}
                                    </span>
                                </div>
                                <div class="d-flex flex-column">
                                    <span class="fw-medium">{{ $item['user_name'] ?? 'Unknown User' }}</span>
                                    <small class="text-body-secondary">User ID: {{ $item['user_id'] ?? '-' }}</small>
                                </div>
                            </div>
                            <span class="fw-medium">{{ number_format($item['count'] ?? 0) }}</span>
                        </div>
                    @empty
                        <p class="text-muted mb-0">No user activity statistics available yet.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Activity Logs Table -->
    <div class="card">
        <div class="card-body">
            @if(isset($activities) && $activities->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Date & Time</th>
                                <th>User</th>
                                <th>Log Name</th>
                                <th>Event</th>
                                <th>Subject Type</th>
                                <th>Description</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($activities as $activity)
                                <tr>
                                    <td>{{ $activity->id }}</td>
                                    <td class="text-nowrap small">
                                        {{ \Carbon\Carbon::parse($activity->created_at)->format('Y-m-d H:i:s') }}
                                    </td>
                                    <td>
                                        @if($activity->causer_id && isset($usersMap[$activity->causer_id]))
                                            <div class="d-flex align-items-center">
                                                <div class="avatar avatar-sm me-2">
                                                    <div class="avatar-initial bg-label-primary rounded-circle">
                                                        {{ substr($usersMap[$activity->causer_id], 0, 1) }}
                                                    </div>
                                                </div>
                                                <span>{{ $usersMap[$activity->causer_id] }}</span>
                                            </div>
                                        @else
                                            <span class="text-muted">System</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($activity->log_name)
                                            <span class="badge bg-label-info">{{ $activity->log_name }}</span>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($activity->event)
                                            <span class="badge bg-label-primary">{{ ucfirst($activity->event) }}</span>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        {{ $activity->subject_type ? class_basename($activity->subject_type) : '-' }}
                                    </td>
                                    <td>
                                        <span class="text-truncate d-inline-block" style="max-width: 200px;" title="{{ $activity->description }}">
                                            {{ Str::limit($activity->description ?? '-', 50) }}
                                        </span>
                                    </td>
                                    <td>
                                        <a href="{{ route('admin.settings.activity-logs.show', $activity->id) }}" class="btn btn-sm btn-icon">
                                            <i class="ri-eye-line"></i>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                
                <!-- Pagination -->
                <div class="mt-4 d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-2">
                    <div>
                        {{ $activities->links() }}
                    </div>
                    {{-- Advanced footer --}}
                    <div class="text-md-end text-body-secondary small">
                        <div>Showing <strong>{{ $activities->firstItem() ?? 0 }}</strong> to <strong>{{ $activities->lastItem() ?? 0 }}</strong> of <strong>{{ $activities->total() }}</strong> activity records.</div>
                        <div>Source: <code>activity_log</code> table (Spatie Activitylog). Use filters above to narrow results or export the full dataset as CSV.</div>
                        <div>Audit level: <span class="badge bg-label-secondary">Application-wide</span></div>
                    </div>
                </div>
            @else
                <div class="text-center py-5">
                    <i class="ri-file-list-line ri-48px text-muted mb-3"></i>
                    <p class="text-muted">No activity logs found</p>
                    @if(isset($error))
                        <p class="text-danger small">{{ $error }}</p>
                    @endif
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
