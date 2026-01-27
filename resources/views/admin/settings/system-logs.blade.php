@extends('admin.layouts.app')

@section('title', 'System Logs - Advanced')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">
                        <i class="ri-file-list-3-line me-2"></i>System Logs
                    </h4>
                    <div class="d-flex gap-2">
                        <a href="{{ route('admin.settings.system-logs.download', ['file' => $selectedFile ?? 'laravel.log']) }}" class="btn btn-sm btn-outline-primary">
                            <i class="ri-download-line me-1"></i>Download
                        </a>
                        <button type="button" class="btn btn-sm btn-outline-danger" data-bs-toggle="modal" data-bs-target="#clearLogsModal">
                            <i class="ri-delete-bin-line me-1"></i>Clear Logs
                        </button>
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
                            <h6 class="mb-1">Total Entries</h6>
                            <h4 class="mb-0">{{ number_format($stats['total_entries'] ?? 0) }}</h4>
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
                            <h6 class="mb-1">Errors</h6>
                            <h4 class="mb-0 text-danger">{{ number_format($stats['errors'] ?? 0) }}</h4>
                        </div>
                        <div class="avatar">
                            <div class="avatar-initial bg-label-danger rounded">
                                <i class="ri-error-warning-line ri-24px"></i>
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
                            <h6 class="mb-1">Warnings</h6>
                            <h4 class="mb-0 text-warning">{{ number_format($stats['warnings'] ?? 0) }}</h4>
                        </div>
                        <div class="avatar">
                            <div class="avatar-initial bg-label-warning rounded">
                                <i class="ri-alert-line ri-24px"></i>
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
                            <h6 class="mb-1">Info</h6>
                            <h4 class="mb-0 text-info">{{ number_format($stats['info'] ?? 0) }}</h4>
                        </div>
                        <div class="avatar">
                            <div class="avatar-initial bg-label-info rounded">
                                <i class="ri-information-line ri-24px"></i>
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
            <form method="GET" action="{{ route('admin.settings.system-logs') }}" class="row g-3">
                @if(isset($logFiles) && count($logFiles) > 1)
                <div class="col-md-3">
                    <label class="form-label">Log File</label>
                    <select name="file" class="form-select">
                        @foreach($logFiles as $file)
                            <option value="{{ $file }}" {{ ($selectedFile ?? 'laravel.log') == $file ? 'selected' : '' }}>
                                {{ $file }}
                            </option>
                        @endforeach
                    </select>
                </div>
                @endif
                <div class="col-md-2">
                    <label class="form-label">Level</label>
                    <select name="level" class="form-select">
                        <option value="all" {{ ($level ?? 'all') == 'all' ? 'selected' : '' }}>All Levels</option>
                        <option value="error" {{ ($level ?? '') == 'error' ? 'selected' : '' }}>Error</option>
                        <option value="warning" {{ ($level ?? '') == 'warning' ? 'selected' : '' }}>Warning</option>
                        <option value="info" {{ ($level ?? '') == 'info' ? 'selected' : '' }}>Info</option>
                        <option value="debug" {{ ($level ?? '') == 'debug' ? 'selected' : '' }}>Debug</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Date From</label>
                    <input type="date" name="date_from" class="form-control" value="{{ $dateFrom ?? '' }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Date To</label>
                    <input type="date" name="date_to" class="form-control" value="{{ $dateTo ?? '' }}">
                </div>
                <div class="col-md-1 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="ri-search-line"></i>
                    </button>
                </div>
            </form>
            @if(isset($search) && $search)
            <form method="GET" action="{{ route('admin.settings.system-logs') }}" class="mt-3">
                <input type="hidden" name="file" value="{{ $selectedFile ?? 'laravel.log' }}">
                <input type="hidden" name="level" value="{{ $level ?? 'all' }}">
                <div class="input-group">
                    <input type="text" name="search" class="form-control" placeholder="Search in logs..." value="{{ $search }}">
                    <button type="submit" class="btn btn-outline-primary">
                        <i class="ri-search-line"></i>
                    </button>
                    <a href="{{ route('admin.settings.system-logs', ['file' => $selectedFile ?? 'laravel.log', 'level' => $level ?? 'all']) }}" class="btn btn-outline-secondary">
                        <i class="ri-close-line"></i>
                    </a>
                </div>
            </form>
            @endif
        </div>
    </div>

    <!-- Logs Table -->
    <div class="card">
        <div class="card-body">
            @if(isset($logs) && count($logs) > 0)
                <div class="table-responsive" style="max-height: 600px; overflow-y: auto;">
                    <table class="table table-sm table-hover">
                        <thead class="sticky-top bg-body">
                            <tr>
                                <th style="width: 180px;">Timestamp</th>
                                <th style="width: 100px;">Level</th>
                                <th>Message</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($logs as $log)
                                @php
                                    $logLevel = $log['level'] ?? 'info';
                                    $levelClass = match($logLevel) {
                                        'error' => 'danger',
                                        'warning' => 'warning',
                                        'debug' => 'secondary',
                                        default => 'info'
                                    };
                                @endphp
                                <tr>
                                    <td class="text-nowrap small">
                                        {{ isset($log['timestamp']) ? \Carbon\Carbon::parse($log['timestamp'])->format('Y-m-d H:i:s') : 'N/A' }}
                                    </td>
                                    <td>
                                        <span class="badge bg-label-{{ $levelClass }}">
                                            {{ strtoupper($logLevel) }}
                                        </span>
                                    </td>
                                    <td>
                                        <pre class="mb-0 small" style="white-space: pre-wrap; word-wrap: break-word; max-width: 800px;">{{ $log['message'] ?? $log['full_line'] ?? $log }}</pre>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-center py-5">
                    <i class="ri-file-list-3-line ri-48px text-muted mb-3"></i>
                    <p class="text-muted">No logs found</p>
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Clear Logs Modal -->
<div class="modal fade" id="clearLogsModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Clear System Logs</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="POST" action="{{ route('admin.settings.system-logs.clear') }}">
                @csrf
                @method('DELETE')
                <input type="hidden" name="file" value="{{ $selectedFile ?? 'laravel.log' }}">
                <div class="modal-body">
                    <p>Are you sure you want to clear all system logs? This action cannot be undone.</p>
                    <p class="text-danger"><strong>File: {{ $selectedFile ?? 'laravel.log' }}</strong></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger">
                        <i class="ri-delete-bin-line me-1"></i>Clear Logs
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
