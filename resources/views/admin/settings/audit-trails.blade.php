@extends('admin.layouts.app')

@section('title', 'Audit Trails - Advanced')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">
                        <i class="ri-history-line me-2"></i>Audit Trails
                    </h4>
                    <div class="d-flex gap-2">
                        <a href="{{ route('admin.settings.audit-trails.export', request()->query()) }}" class="btn btn-sm btn-outline-success">
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
            <form method="GET" action="{{ route('admin.settings.audit-trails') }}" class="row g-3">
                <div class="col-md-3">
                    <label class="form-label">User</label>
                    <select name="user_id" class="form-select">
                        <option value="">All Users</option>
                        @foreach($users ?? [] as $user)
                            <option value="{{ $user->id }}" {{ request('user_id') == $user->id ? 'selected' : '' }}>
                                {{ $user->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label">Action</label>
                    <select name="action" class="form-select">
                        <option value="">All Actions</option>
                        @foreach($actions ?? [] as $action)
                            <option value="{{ $action }}" {{ request('action') == $action ? 'selected' : '' }}>
                                {{ ucfirst($action) }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label">Module</label>
                    <select name="module" class="form-select">
                        <option value="">All Modules</option>
                        @foreach($modules ?? [] as $module)
                            <option value="{{ $module }}" {{ request('module') == $module ? 'selected' : '' }}>
                                {{ ucfirst($module) }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label">Status</label>
                    <select name="status" class="form-select">
                        <option value="">All Status</option>
                        <option value="success" {{ request('status') == 'success' ? 'selected' : '' }}>Success</option>
                        <option value="failed" {{ request('status') == 'failed' ? 'selected' : '' }}>Failed</option>
                        <option value="error" {{ request('status') == 'error' ? 'selected' : '' }}>Error</option>
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
                    <a href="{{ route('admin.settings.audit-trails') }}" class="btn btn-outline-secondary">
                        <i class="ri-refresh-line"></i>
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Top Actions & Modules -->
    @if(isset($stats) && ($stats['by_action']->count() > 0 || $stats['by_module']->count() > 0))
    <div class="row mb-4">
        @if($stats['by_action']->count() > 0)
        <div class="col-md-6 mb-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Top Actions</h5>
                </div>
                <div class="card-body">
                    @foreach($stats['by_action'] as $item)
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span>{{ ucfirst($item->action) }}</span>
                        <span class="badge bg-label-primary">{{ $item->count }}</span>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
        @endif
        @if($stats['by_module']->count() > 0)
        <div class="col-md-6 mb-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Top Modules</h5>
                </div>
                <div class="card-body">
                    @foreach($stats['by_module'] as $item)
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span>{{ ucfirst($item->module) }}</span>
                        <span class="badge bg-label-info">{{ $item->count }}</span>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
        @endif
    </div>
    @endif

    <!-- Audit Trails Table -->
    <div class="card">
        <div class="card-body">
            @if(isset($audits) && $audits->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Date & Time</th>
                                <th>User</th>
                                <th>Action</th>
                                <th>Module</th>
                                <th>Model</th>
                                <th>Description</th>
                                <th>Status</th>
                                <th>IP Address</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($audits as $audit)
                                @php
                                    $statusClass = match($audit->status ?? 'success') {
                                        'success' => 'success',
                                        'failed' => 'danger',
                                        'error' => 'danger',
                                        default => 'secondary'
                                    };
                                @endphp
                                <tr>
                                    <td>{{ $audit->id }}</td>
                                    <td class="text-nowrap small">
                                        {{ \Carbon\Carbon::parse($audit->created_at)->format('Y-m-d H:i:s') }}
                                    </td>
                                    <td>
                                        @if($audit->user)
                                            <div class="d-flex align-items-center">
                                                <div class="avatar avatar-sm me-2">
                                                    <div class="avatar-initial bg-label-primary rounded-circle">
                                                        {{ substr($audit->user->name, 0, 1) }}
                                                    </div>
                                                </div>
                                                <span>{{ $audit->user->name }}</span>
                                            </div>
                                        @else
                                            <span class="text-muted">System</span>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="badge bg-label-primary">{{ ucfirst($audit->action) }}</span>
                                    </td>
                                    <td>
                                        @if($audit->module)
                                            <span class="badge bg-label-info">{{ ucfirst($audit->module) }}</span>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        {{ $audit->model_name ?? ($audit->model_type ? class_basename($audit->model_type) : '-') }}
                                    </td>
                                    <td>
                                        <span class="text-truncate d-inline-block" style="max-width: 200px;" title="{{ $audit->description }}">
                                            {{ Str::limit($audit->description ?? '-', 50) }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge bg-label-{{ $statusClass }}">{{ ucfirst($audit->status ?? 'success') }}</span>
                                    </td>
                                    <td class="small">{{ $audit->ip_address ?? '-' }}</td>
                                    <td>
                                        <a href="{{ route('admin.settings.audit-trails.show', $audit->id) }}" class="btn btn-sm btn-icon">
                                            <i class="ri-eye-line"></i>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                
                <!-- Pagination -->
                <div class="mt-4">
                    {{ $audits->links() }}
                </div>
            @else
                <div class="text-center py-5">
                    <i class="ri-history-line ri-48px text-muted mb-3"></i>
                    <p class="text-muted">No audit trails found</p>
                    @if(isset($error))
                        <p class="text-danger small">{{ $error }}</p>
                    @endif
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
