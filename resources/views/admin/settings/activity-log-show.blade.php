@extends('admin.layouts.app')

@section('title', 'Activity Log Details')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">
                        <i class="ri-file-list-line me-2"></i>Activity Log Details
                    </h4>
                    <a href="{{ route('admin.settings.activity-logs') }}" class="btn btn-sm btn-outline-secondary">
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
                                    <td>{{ $activity->id }}</td>
                                </tr>
                                <tr>
                                    <th>Date & Time:</th>
                                    <td>{{ \Carbon\Carbon::parse($activity->created_at)->format('Y-m-d H:i:s') }}</td>
                                </tr>
                                <tr>
                                    <th>User:</th>
                                    <td>
                                        @if($user)
                                            {{ $user->name }} ({{ $user->email }})
                                        @else
                                            <span class="text-muted">System</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th>Log Name:</th>
                                    <td>
                                        @if($activity->log_name)
                                            <span class="badge bg-label-info">{{ $activity->log_name }}</span>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th>Event:</th>
                                    <td>
                                        @if($activity->event)
                                            <span class="badge bg-label-primary">{{ ucfirst($activity->event) }}</span>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <h6 class="mb-3">Subject Information</h6>
                            <table class="table table-borderless">
                                <tr>
                                    <th width="150">Subject Type:</th>
                                    <td>{{ $activity->subject_type ? class_basename($activity->subject_type) : '-' }}</td>
                                </tr>
                                <tr>
                                    <th>Subject ID:</th>
                                    <td>{{ $activity->subject_id ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <th>Causer Type:</th>
                                    <td>{{ $activity->causer_type ? class_basename($activity->causer_type) : '-' }}</td>
                                </tr>
                                <tr>
                                    <th>Causer ID:</th>
                                    <td>{{ $activity->causer_id ?? '-' }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    @if($activity->description)
                    <div class="mt-4">
                        <h6 class="mb-3">Description</h6>
                        <div class="card bg-label-light">
                            <div class="card-body">
                                <p class="mb-0">{{ $activity->description }}</p>
                            </div>
                        </div>
                    </div>
                    @endif

                    @if($activity->properties)
                    <div class="mt-4">
                        <h6 class="mb-3">Properties</h6>
                        <div class="card bg-label-light">
                            <div class="card-body">
                                <pre class="mb-0 small">{{ json_encode(json_decode($activity->properties), JSON_PRETTY_PRINT) }}</pre>
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






