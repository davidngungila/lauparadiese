@extends('admin.layouts.app')

@section('title', 'Audit Trail Details')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">
                        <i class="ri-history-line me-2"></i>Audit Trail Details
                    </h4>
                    <a href="{{ route('admin.settings.audit-trails') }}" class="btn btn-sm btn-outline-secondary">
                        <i class="ri-arrow-left-line me-1"></i>Back
                    </a>
                </div>
                <div class="card-body">
                    <div class="row g-4">
                        <div class="col-md-6">
                            <h6 class="mb-3">Basic Information</h6>
                            <table class="table table-borderless">
                                <tr>
                                    <th width="150">ID:</th>
                                    <td>{{ $audit->id }}</td>
                                </tr>
                                <tr>
                                    <th>Date & Time:</th>
                                    <td>{{ $audit->created_at->format('Y-m-d H:i:s') }}</td>
                                </tr>
                                <tr>
                                    <th>User:</th>
                                    <td>
                                        @if($audit->user)
                                            {{ $audit->user->name }} ({{ $audit->user->email }})
                                        @else
                                            <span class="text-muted">System</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th>Action:</th>
                                    <td><span class="badge bg-label-primary">{{ ucfirst($audit->action) }}</span></td>
                                </tr>
                                <tr>
                                    <th>Module:</th>
                                    <td>
                                        @if($audit->module)
                                            <span class="badge bg-label-info">{{ ucfirst($audit->module) }}</span>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th>Status:</th>
                                    <td>
                                        @php
                                            $statusClass = match($audit->status ?? 'success') {
                                                'success' => 'success',
                                                'failed' => 'danger',
                                                'error' => 'danger',
                                                default => 'secondary'
                                            };
                                        @endphp
                                        <span class="badge bg-label-{{ $statusClass }}">{{ ucfirst($audit->status ?? 'success') }}</span>
                                    </td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <h6 class="mb-3">Request Information</h6>
                            <table class="table table-borderless">
                                <tr>
                                    <th width="150">Model Type:</th>
                                    <td>{{ $audit->model_type ? class_basename($audit->model_type) : '-' }}</td>
                                </tr>
                                <tr>
                                    <th>Model ID:</th>
                                    <td>{{ $audit->model_id ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <th>Model Name:</th>
                                    <td>{{ $audit->model_name ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <th>Route:</th>
                                    <td><code>{{ $audit->route ?? '-' }}</code></td>
                                </tr>
                                <tr>
                                    <th>Method:</th>
                                    <td><span class="badge bg-label-secondary">{{ $audit->method ?? '-' }}</span></td>
                                </tr>
                                <tr>
                                    <th>IP Address:</th>
                                    <td><code>{{ $audit->ip_address ?? '-' }}</code></td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    @if($audit->description)
                    <div class="mt-4">
                        <h6 class="mb-3">Description</h6>
                        <div class="card bg-label-light">
                            <div class="card-body">
                                <p class="mb-0">{{ $audit->description }}</p>
                            </div>
                        </div>
                    </div>
                    @endif

                    @if($audit->old_values || $audit->new_values)
                    <div class="row mt-4">
                        <div class="col-md-6">
                            <h6 class="mb-3">Old Values</h6>
                            @if($audit->old_values)
                                <div class="card bg-label-light">
                                    <div class="card-body">
                                        <pre class="mb-0 small">{{ json_encode($audit->old_values, JSON_PRETTY_PRINT) }}</pre>
                                    </div>
                                </div>
                            @else
                                <p class="text-muted">No old values</p>
                            @endif
                        </div>
                        <div class="col-md-6">
                            <h6 class="mb-3">New Values</h6>
                            @if($audit->new_values)
                                <div class="card bg-label-light">
                                    <div class="card-body">
                                        <pre class="mb-0 small">{{ json_encode($audit->new_values, JSON_PRETTY_PRINT) }}</pre>
                                    </div>
                                </div>
                            @else
                                <p class="text-muted">No new values</p>
                            @endif
                        </div>
                    </div>
                    @endif

                    @if($audit->changed_fields)
                    <div class="mt-4">
                        <h6 class="mb-3">Changed Fields</h6>
                        <div class="card bg-label-light">
                            <div class="card-body">
                                <ul class="mb-0">
                                    @foreach($audit->changed_fields as $field)
                                        <li>{{ $field }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                    @endif

                    @if($audit->request_data)
                    <div class="mt-4">
                        <h6 class="mb-3">Request Data</h6>
                        <div class="card bg-label-light">
                            <div class="card-body">
                                <pre class="mb-0 small">{{ json_encode($audit->request_data, JSON_PRETTY_PRINT) }}</pre>
                            </div>
                        </div>
                    </div>
                    @endif

                    @if($audit->error_message)
                    <div class="mt-4">
                        <h6 class="mb-3 text-danger">Error Message</h6>
                        <div class="card bg-label-danger">
                            <div class="card-body">
                                <p class="mb-0 text-danger">{{ $audit->error_message }}</p>
                            </div>
                        </div>
                    </div>
                    @endif

                    @if($audit->user_agent)
                    <div class="mt-4">
                        <h6 class="mb-3">User Agent</h6>
                        <div class="card bg-label-light">
                            <div class="card-body">
                                <p class="mb-0 small">{{ $audit->user_agent }}</p>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection






