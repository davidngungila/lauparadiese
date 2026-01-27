@extends('admin.layouts.app')

@section('title', 'Backup Manager - Advanced')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h4 class="mb-1">
                        <i class="ri-database-2-line me-2"></i>Backup Manager
                    </h4>
                    <p class="text-muted mb-0">Configure automated backups, destinations, retention and manual tools.</p>
                </div>
                <div class="d-flex gap-2">
                    <button type="button" class="btn btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#createBackupModal">
                        <i class="ri-database-2-line me-1"></i>Create Manual Backup
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- 1. Backup Status Overview -->
    <div class="row g-4 mb-4">
        <div class="col-xl-4 col-md-6">
            <div class="card h-100">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">Last Backup</h5>
                    @if(($status['last_backup'] ?? null))
                        <span class="badge bg-label-success">Available</span>
                    @else
                        <span class="badge bg-label-warning">Not yet created</span>
                    @endif
                </div>
                <div class="card-body">
                    @if(($status['last_backup'] ?? null))
                        <p class="mb-1 small text-body-secondary">
                            Date &amp; time:
                            <strong>{{ $status['last_backup']['created_at'] }}</strong>
                        </p>
                        <p class="mb-1 small text-body-secondary">
                            Size:
                            <strong>{{ number_format($status['last_backup']['size'] / 1024 / 1024, 2) }} MB</strong>
                        </p>
                        <p class="mb-0 small text-body-secondary">
                            Status:
                            <span class="badge bg-label-success">Success</span>
                        </p>
                    @else
                        <p class="mb-0 small text-body-secondary">
                            No backup has been created yet. Use <strong>Create Manual Backup</strong> above.
                        </p>
                    @endif
                </div>
            </div>
        </div>
        <div class="col-xl-4 col-md-6">
            <div class="card h-100">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">Next Scheduled Backup</h5>
                    <span class="badge {{ ($status['enabled'] ?? false) ? 'bg-label-success' : 'bg-label-secondary' }}">
                        {{ ($status['enabled'] ?? false) ? 'Enabled' : 'Disabled' }}
                    </span>
                </div>
                <div class="card-body">
                    <p class="mb-1 small text-body-secondary">
                        Frequency:
                        <strong class="text-capitalize">{{ str_replace('_', ' ', $status['frequency'] ?? 'daily') }}</strong>
                    </p>
                    <p class="mb-1 small text-body-secondary">
                        Next run:
                        <strong>
                            @if($status['enabled'] ?? false)
                                {{ optional($status['next_scheduled_at'] ?? null)->format('Y-m-d H:i') }}
                            @else
                                N/A
                            @endif
                        </strong>
                    </p>
                    <p class="mb-0 small text-body-secondary">
                        Retention:
                        Keep <strong>{{ $status['retention_days'] ?? 30 }}</strong> days
                    </p>
                </div>
            </div>
        </div>
        <div class="col-xl-4 col-md-12">
            <div class="card h-100">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">Backup Storage</h5>
                </div>
                <div class="card-body">
                    @php
                        $totalBackups = $status['total_backups'] ?? 0;
                        $totalSize = $status['total_size'] ?? 0;
                        $diskTotal = $serverChecks['disk_total'] ?? 0;
                        $diskFree = $serverChecks['disk_free'] ?? 0;
                        $diskUsed = $serverChecks['disk_used'] ?? 0;
                        $diskPercent = $diskTotal > 0 ? round($diskUsed / $diskTotal * 100, 1) : 0;
                    @endphp
                    <p class="mb-1 small text-body-secondary">
                        Total backups: <strong>{{ $totalBackups }}</strong>
                    </p>
                    <p class="mb-1 small text-body-secondary">
                        Total size: <strong>{{ number_format($totalSize / 1024 / 1024, 2) }} MB</strong>
                    </p>
                    <p class="mb-1 small text-body-secondary">
                        Storage path: <code>{{ $serverChecks['backup_path'] ?? storage_path('app/backups') }}</code>
                    </p>
                    <p class="mb-1 small text-body-secondary">
                        Path writable:
                        @if($serverChecks['backup_path_writable'] ?? false)
                            <span class="badge bg-label-success">Yes</span>
                        @else
                            <span class="badge bg-label-danger">No</span>
                        @endif
                    </p>
                    <p class="mb-1 small text-body-secondary">
                        Disk usage:
                        <strong>{{ $diskPercent }}%</strong>
                    </p>
                    <div class="progress" style="height: 6px;">
                        <div class="progress-bar {{ $diskPercent > 80 ? 'bg-danger' : ($diskPercent > 70 ? 'bg-warning' : 'bg-success') }}"
                             role="progressbar"
                             style="width: {{ $diskPercent }}%;"
                             aria-valuenow="{{ $diskPercent }}" aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- 2–6. Settings & Destinations (layout similar to system settings) -->
    <div class="row g-4 mb-4">
        <!-- Backup Settings & Types -->
        <div class="col-xl-8">
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">Backup Settings</h5>
                </div>
                <div class="card-body">
                    <div class="row g-4">
                        <div class="col-md-4">
                            <h6 class="mb-2">General</h6>
                            <p class="mb-1 small text-body-secondary">
                                Enable backups:
                                <span class="badge {{ ($status['enabled'] ?? false) ? 'bg-label-success' : 'bg-label-secondary' }}">
                                    {{ ($status['enabled'] ?? false) ? 'Yes' : 'No' }}
                                </span>
                            </p>
                            <p class="mb-1 small text-body-secondary">
                                Frequency:
                                <span class="fw-medium text-capitalize">{{ str_replace('_', ' ', $status['frequency'] ?? 'daily') }}</span>
                            </p>
                            <p class="mb-0 small text-body-secondary">
                                Backup time:
                                <span class="fw-medium">{{ \App\Models\SystemSetting::getValue('backup_time', '02:00') }}</span>
                            </p>
                        </div>
                        <div class="col-md-4">
                            <h6 class="mb-2">Types Included</h6>
                            <p class="mb-1 small text-body-secondary">
                                <i class="ri-checkbox-circle-line text-success me-1"></i> Database Backup
                            </p>
                            <p class="mb-1 small text-body-secondary">
                                <i class="ri-checkbox-circle-line text-success me-1"></i> Storage (uploads)
                            </p>
                            <p class="mb-0 small text-body-secondary">
                                <i class="ri-checkbox-circle-line text-success me-1"></i> Configuration files
                            </p>
                        </div>
                        <div class="col-md-4">
                            <h6 class="mb-2">Retention & Rotation</h6>
                            <p class="mb-1 small text-body-secondary">
                                Retention days: <strong>{{ $status['retention_days'] ?? 30 }}</strong>
                            </p>
                            <p class="mb-0 small text-body-secondary">
                                Auto-delete old backups: <span class="badge bg-label-info">Configured via system settings</span>
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Email Notifications -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">Email Notifications</h5>
                </div>
                <div class="card-body">
                    <div class="row g-4">
                        <div class="col-md-12">
                            <div class="alert alert-info">
                                <i class="ri-information-line me-2"></i>
                                <strong>Automatic Email Backups:</strong> Database backups are automatically sent via email when created.
                            </div>
                        </div>
                        <div class="col-md-6">
                            <h6 class="mb-2">Email Settings</h6>
                            <p class="mb-1 small text-body-secondary">
                                Send backups via email:
                                <span class="badge {{ ($status['send_email'] ?? true) ? 'bg-label-success' : 'bg-label-secondary' }}">
                                    {{ ($status['send_email'] ?? true) ? 'Enabled' : 'Disabled' }}
                                </span>
                            </p>
                            <p class="mb-0 small text-body-secondary">
                                Recipient email:
                                <strong><code>{{ $status['email_recipient'] ?? 'lauparadiseadventure@gmail.com' }}</code></strong>
                            </p>
                        </div>
                        <div class="col-md-6">
                            <h6 class="mb-2">Manual Tools</h6>
                            <p class="mb-2 small text-body-secondary">
                                Use <strong>Create Manual Backup</strong> to trigger full database backups on demand.
                            </p>
                            <p class="mb-0 small text-body-secondary">
                                You can also download or delete individual backups from the table below.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Destinations & Server Checks -->
        <div class="col-xl-4">
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">Backup Destinations</h5>
                </div>
                <div class="card-body">
                    <h6 class="mb-2">Primary Destination</h6>
                    <p class="mb-1 small text-body-secondary">
                        Driver: <strong>{{ $status['storage_driver'] ?? config('filesystems.default', 'local') }}</strong>
                    </p>
                    <p class="mb-3 small text-body-secondary">
                        Path: <code>{{ $serverChecks['backup_path'] ?? storage_path('app/backups') }}</code>
                    </p>
                    <h6 class="mb-2">Cloud / Remote Storage</h6>
                    <p class="mb-0 small text-body-secondary">
                        For enterprise setups, you can extend this panel to configure S3, Google Drive, Dropbox,
                        or SFTP destinations via `system_settings` and a scheduled backup job.
                    </p>
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Server Requirements Check</h5>
                </div>
                <div class="card-body">
                    <p class="mb-1 small text-body-secondary">
                        Free disk space:
                        <strong>
                            {{ number_format(($serverChecks['disk_free'] ?? 0) / 1024 / 1024 / 1024, 2) }} GB
                        </strong>
                    </p>
                    <p class="mb-1 small text-body-secondary">
                        Backup folder permissions:
                        @if($serverChecks['backup_path_writable'] ?? false)
                            <span class="badge bg-label-success">Writable</span>
                        @else
                            <span class="badge bg-label-danger">Not writable</span>
                        @endif
                    </p>
                    <p class="mb-0 small text-body-secondary">
                        Cron & queue status can be monitored from <strong>System Health</strong> and server tools.
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- 7–8. Backup List -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Backup List</h5>
                </div>
                <div class="card-body">
                    @if(count($backups) > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Backup Name</th>
                                        <th>Size</th>
                                        <th>Created At</th>
                                        <th>Type</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($backups as $backup)
                                        <tr>
                                            <td>{{ $backup['name'] }}</td>
                                            <td>{{ number_format($backup['size'] / 1024 / 1024, 2) }} MB</td>
                                            <td>{{ $backup['created_at'] }}</td>
                                            <td>
                                                <span class="badge bg-label-primary">Database</span>
                                            </td>
                                            <td>
                                                <div class="d-flex gap-2">
                                                    <a href="{{ route('admin.settings.backups.download', $backup['name']) }}"
                                                       class="btn btn-sm btn-outline-primary">
                                                        <i class="ri-download-line"></i>
                                                    </a>
                                                    <button type="button" class="btn btn-sm btn-outline-danger"
                                                            onclick="deleteBackup('{{ $backup['name'] }}')">
                                                        <i class="ri-delete-bin-line"></i>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="ri-database-2-line ri-48px text-muted mb-3"></i>
                            <p class="text-muted mt-3">No backups found</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Create Backup Modal -->
<div class="modal fade" id="createBackupModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Create Backup</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="createBackupForm" method="POST" action="{{ route('admin.settings.backups.create') }}">
                @csrf
                <div class="modal-body">
                    <p>This will create a backup of your database and files. This may take a few minutes.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="ri-database-2-line me-1"></i>Create Backup
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Delete Backup Modal -->
<div class="modal fade" id="deleteBackupModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Delete Backup</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="deleteBackupForm" method="POST">
                @csrf
                @method('DELETE')
                <div class="modal-body">
                    <p>Are you sure you want to delete this backup? This action cannot be undone.</p>
                    <p class="text-danger"><strong id="backupNameToDelete"></strong></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger">
                        <i class="ri-delete-bin-line me-1"></i>Delete Backup
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function deleteBackup(filename) {
    document.getElementById('backupNameToDelete').textContent = filename;
    // Build the URL manually to avoid route generation error
    const baseUrl = '{{ url("admin/settings/backups") }}';
    document.getElementById('deleteBackupForm').action = baseUrl + '/' + encodeURIComponent(filename);
    new bootstrap.Modal(document.getElementById('deleteBackupModal')).show();
}
</script>
@endsection


