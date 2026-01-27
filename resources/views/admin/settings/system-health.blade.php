@extends('admin.layouts.app')

@section('title', 'System Health')
@section('description', 'Real-time system health overview and diagnostics')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h4 class="mb-1">
                        <i class="ri-heart-pulse-line me-2"></i>System Health
                    </h4>
                    <p class="text-muted mb-0">Live overview of application, database, server, security and integrations health.</p>
                </div>
            </div>
        </div>
    </div>

    <!-- System Summary -->
    <div class="row g-4 mb-4">
        <div class="col-xl-3 col-md-6">
            <div class="card h-100 border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <h6 class="mb-1">System Status</h6>
                            @php
                                $status = $system['status'] ?? 'healthy';
                                $badgeClass = $status === 'critical' ? 'bg-label-danger' : ($status === 'warning' ? 'bg-label-warning' : 'bg-label-success');
                            @endphp
                            <span class="badge {{ $badgeClass }} mt-1 text-uppercase">
                                {{ $status === 'critical' ? 'Critical' : ($status === 'warning' ? 'Warning' : 'Healthy') }}
                            </span>
                        </div>
                        <i class="ri-pulse-line ri-24px text-primary"></i>
                    </div>
                    <p class="mt-3 mb-0 text-body-secondary small">
                        {{ count($issues ?? []) ? count($issues) . ' active issue(s) detected' : 'No major issues detected.' }}
                    </p>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card h-100 border-0 shadow-sm">
                <div class="card-body">
                    <h6 class="mb-1">PHP / Laravel</h6>
                    <p class="mb-1 small text-body-secondary">
                        PHP <strong>{{ $system['php_version'] ?? 'N/A' }}</strong>
                        <span class="text-body-tertiary"> (recommended {{ $system['php_recommended'] ?? '8.2+' }})</span>
                    </p>
                    <p class="mb-0 small text-body-secondary">
                        Laravel <strong>{{ $system['laravel_version'] ?? 'N/A' }}</strong>
                    </p>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card h-100 border-0 shadow-sm">
                <div class="card-body">
                    <h6 class="mb-1">Server</h6>
                    <p class="mb-1 small text-body-secondary">
                        OS: <strong>{{ $system['server_os'] ?? 'N/A' }}</strong>
                    </p>
                    <p class="mb-0 small text-body-secondary">
                        Environment: <strong>{{ $system['app_env'] ?? 'local' }}</strong>
                        @if(($system['app_debug'] ?? false))
                            <span class="badge bg-label-danger ms-1">DEBUG ON</span>
                        @else
                            <span class="badge bg-label-success ms-1">DEBUG OFF</span>
                        @endif
                    </p>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card h-100 border-0 shadow-sm">
                <div class="card-body">
                    <h6 class="mb-1">Storage</h6>
                    @php
                        $total = $server['storage_total'] ?? 0;
                        $free = $server['storage_free'] ?? 0;
                        $used = $total > 0 ? $total - $free : 0;
                        $usedPercent = $total > 0 ? round($used / $total * 100, 1) : 0;
                    @endphp
                    <p class="mb-1 small text-body-secondary">
                        Used: <strong>{{ number_format($used / (1024*1024*1024), 2) }} GB</strong> /
                        {{ number_format($total / (1024*1024*1024), 2) }} GB
                    </p>
                    <div class="progress" style="height: 6px;">
                        <div class="progress-bar {{ $usedPercent > 80 ? 'bg-danger' : ($usedPercent > 70 ? 'bg-warning' : 'bg-success') }}"
                             role="progressbar"
                             style="width: {{ $usedPercent }}%;"
                             aria-valuenow="{{ $usedPercent }}" aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                    <p class="mb-0 mt-1 small text-body-secondary">
                        {{ $usedPercent }}% used
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Server Health Charts -->
    <div class="row g-4 mb-4">
        <div class="col-xl-4 col-md-6">
            <div class="card h-100">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">CPU Usage</h5>
                </div>
                <div class="card-body">
                    <div id="cpuUsageChart" style="min-height: 220px;"></div>
                    <p class="mb-0 mt-2 small text-body-secondary text-center">
                        Current load:
                        <strong>{{ $server['cpu_percent'] !== null ? $server['cpu_percent'] . '%' : 'N/A' }}</strong>
                    </p>
                </div>
            </div>
        </div>
        <div class="col-xl-4 col-md-6">
            <div class="card h-100">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">PHP Memory Usage</h5>
                </div>
                <div class="card-body">
                    <div id="memoryUsageChart" style="min-height: 220px;"></div>
                    @php
                        $phpUsed = $server['php_memory_used'] ?? 0;
                        $phpLimit = $server['php_memory_limit'] ?? 0;
                    @endphp
                    <p class="mb-0 mt-2 small text-body-secondary text-center">
                        Used: <strong>{{ number_format($phpUsed / (1024*1024), 2) }} MB</strong>
                        @if($phpLimit > 0)
                            / {{ number_format($phpLimit / (1024*1024), 0) }} MB
                        @else
                            (no limit)
                        @endif
                    </p>
                </div>
            </div>
        </div>
        <div class="col-xl-4 col-md-12">
            <div class="card h-100">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">Storage & Folders</h5>
                </div>
                <div class="card-body">
                    <div id="storageChart" style="min-height: 220px;"></div>
                    <p class="mb-1 mt-2 small text-body-secondary">
                        <strong>Logs folder:</strong>
                        {{ number_format(($server['logs_size'] ?? 0) / (1024*1024), 2) }} MB
                    </p>
                    <p class="mb-0 small text-body-secondary">
                        <strong>storage/app:</strong>
                        {{ number_format(($server['storage_app_size'] ?? 0) / (1024*1024), 2) }} MB
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Two-column layout: Application/DB/Security on left, Integrations & Issues on right -->
    <div class="row g-4">
        <div class="col-xl-8">
            <!-- Application Status -->
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">Application Status</h5>
                </div>
                <div class="card-body">
                    <div class="row g-4">
                        <div class="col-md-6">
                            <h6 class="mb-2">Logging</h6>
                            <p class="mb-1 small text-body-secondary">
                                Log file:
                                <code class="d-inline-block text-truncate" style="max-width: 100%;">
                                    {{ $application['log_path'] ?? 'storage/logs/laravel.log' }}
                                </code>
                            </p>
                            <p class="mb-1 small text-body-secondary">
                                Status:
                                @if($application['log_exists'] ?? false)
                                    <span class="badge bg-label-success">Available</span>
                                @else
                                    <span class="badge bg-label-danger">Missing</span>
                                @endif
                            </p>
                            <p class="mb-0 small text-body-secondary">
                                Size:
                                <strong>{{ number_format(($application['log_size'] ?? 0) / (1024*1024), 2) }} MB</strong>
                            </p>
                        </div>
                        <div class="col-md-6">
                            <h6 class="mb-2">Runtime</h6>
                            <p class="mb-1 small text-body-secondary">
                                Queue connection:
                                <span class="fw-medium">{{ $application['queue_connection'] ?? 'sync' }}</span>
                            </p>
                            <p class="mb-1 small text-body-secondary">
                                Cache driver:
                                <span class="fw-medium">{{ $application['cache_driver'] ?? 'file' }}</span>
                            </p>
                            <p class="mb-0 small text-body-secondary">
                                Session driver:
                                <span class="fw-medium">{{ $application['session_driver'] ?? 'file' }}</span>
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Database Status -->
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">Database Status</h5>
                </div>
                <div class="card-body">
                    <div class="row g-4">
                        <div class="col-md-6">
                            <h6 class="mb-2">Connection</h6>
                            <p class="mb-1 small text-body-secondary">
                                Status:
                                @if(($database['status'] ?? 'disconnected') === 'connected')
                                    <span class="badge bg-label-success">Connected</span>
                                @else
                                    <span class="badge bg-label-danger">Disconnected</span>
                                @endif
                            </p>
                            <p class="mb-1 small text-body-secondary">
                                Connection: <strong>{{ $database['connection'] ?? 'mysql' }}</strong>
                            </p>
                            <p class="mb-0 small text-body-secondary">
                                Host: <strong>{{ $database['host'] ?? 'N/A' }}</strong>
                                <span class="mx-1">•</span>
                                Port: <strong>{{ $database['port'] ?? 'N/A' }}</strong>
                            </p>
                        </div>
                        <div class="col-md-6">
                            <h6 class="mb-2">Migrations</h6>
                            <p class="mb-0 small text-body-secondary">
                                Pending migrations checks can be run from the command line:
                                <code>php artisan migrate:status</code>
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Security Status -->
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">Security Status</h5>
                </div>
                <div class="card-body">
                    <div class="row g-4">
                        <div class="col-md-4">
                            <h6 class="mb-2">Environment</h6>
                            <p class="mb-1 small text-body-secondary">
                                APP_ENV: <strong>{{ $security['app_env'] ?? 'local' }}</strong>
                            </p>
                            <p class="mb-0 small text-body-secondary">
                                APP_DEBUG:
                                @if($security['app_debug'] ?? false)
                                    <span class="badge bg-label-danger">ON</span>
                                @else
                                    <span class="badge bg-label-success">OFF</span>
                                @endif
                            </p>
                        </div>
                        <div class="col-md-4">
                            <h6 class="mb-2">HTTPS / SSL</h6>
                            <p class="mb-0 small text-body-secondary">
                                HTTPS:
                                @if($security['ssl'] ?? false)
                                    <span class="badge bg-label-success">Enforced</span>
                                @else
                                    <span class="badge bg-label-warning">Not enforced</span>
                                @endif
                            </p>
                        </div>
                        <div class="col-md-4">
                            <h6 class="mb-2">File Permissions</h6>
                            <p class="mb-1 small text-body-secondary">
                                Storage:
                                @if($security['storage_writable'] ?? false)
                                    <span class="badge bg-label-success">Writable</span>
                                @else
                                    <span class="badge bg-label-danger">Not writable</span>
                                @endif
                            </p>
                            <p class="mb-1 small text-body-secondary">
                                Cache:
                                @if($security['cache_writable'] ?? false)
                                    <span class="badge bg-label-success">Writable</span>
                                @else
                                    <span class="badge bg-label-danger">Not writable</span>
                                @endif
                            </p>
                            <p class="mb-0 small text-body-secondary">
                                Logs:
                                @if($security['logs_writable'] ?? false)
                                    <span class="badge bg-label-success">Writable</span>
                                @else
                                    <span class="badge bg-label-danger">Not writable</span>
                                @endif
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-4">
            <!-- Integrations -->
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">Integrations Health</h5>
                </div>
                <div class="card-body">
                    <h6 class="mb-2">Email</h6>
                    <p class="mb-3 small text-body-secondary">
                        Mailer: <strong>{{ $integrations['mail'] ?? 'smtp' }}</strong>
                    </p>

                    <h6 class="mb-2">Payment Gateways</h6>
                    <ul class="list-unstyled mb-0 small">
                        @php $pg = $integrations['payment_gateways'] ?? []; @endphp
                        <li class="d-flex justify-content-between align-items-center mb-1">
                            <span>MPESA Daraja</span>
                            <span class="badge {{ ($pg['mpesa'] ?? false) ? 'bg-label-success' : 'bg-label-secondary' }}">
                                {{ ($pg['mpesa'] ?? false) ? 'Configured' : 'Not configured' }}
                            </span>
                        </li>
                        <li class="d-flex justify-content-between align-items-center mb-1">
                            <span>Stripe</span>
                            <span class="badge {{ ($pg['stripe'] ?? false) ? 'bg-label-success' : 'bg-label-secondary' }}">
                                {{ ($pg['stripe'] ?? false) ? 'Configured' : 'Not configured' }}
                            </span>
                        </li>
                        <li class="d-flex justify-content-between align-items-center">
                            <span>PayPal</span>
                            <span class="badge {{ ($pg['paypal'] ?? false) ? 'bg-label-success' : 'bg-label-secondary' }}">
                                {{ ($pg['paypal'] ?? false) ? 'Configured' : 'Not configured' }}
                            </span>
                        </li>
                    </ul>
                </div>
            </div>

            <!-- Issues -->
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">Detected Issues</h5>
                </div>
                <div class="card-body">
                    @if(empty($issues))
                        <div class="text-center py-4">
                            <i class="ri-checkbox-circle-line ri-32px text-success mb-2"></i>
                            <p class="mb-0 text-body-secondary small">No active issues detected. System appears healthy.</p>
                        </div>
                    @else
                        <ul class="list-unstyled mb-0 small">
                            @foreach($issues as $issue)
                                @php
                                    $sev = $issue['severity'] ?? 'warning';
                                    $sevBadge = $sev === 'critical' ? 'bg-label-danger' : 'bg-label-warning';
                                @endphp
                                <li class="mb-3">
                                    <div class="d-flex justify-content-between align-items-center mb-1">
                                        <span class="fw-medium">{{ $issue['name'] ?? 'Issue' }}</span>
                                        <span class="badge {{ $sevBadge }} text-uppercase">{{ $sev }}</span>
                                    </div>
                                    <p class="mb-1 text-body-secondary">
                                        Detected: {{ isset($issue['detected_at']) ? \Carbon\Carbon::parse($issue['detected_at'])->format('Y-m-d H:i') : 'N/A' }}
                                    </p>
                                    <p class="mb-0 text-body-secondary">
                                        How to fix: {{ $issue['how_to_fix'] ?? 'See server logs for more details.' }}
                                    </p>
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    (function () {
        var cpuValue = {{ $server['cpu_percent'] !== null ? $server['cpu_percent'] : '0' }};
        var memValue = {{ $server['php_memory_percent'] !== null ? $server['php_memory_percent'] : '0' }};
        var total = {{ $server['storage_total'] ?? 0 }};
        var free = {{ $server['storage_free'] ?? 0 }};
        var used = total > 0 ? total - free : 0;

        // CPU radial bar
        var cpuOptions = {
            chart: {
                height: 220,
                type: 'radialBar',
                toolbar: { show: false }
            },
            series: [cpuValue],
            labels: ['CPU %'],
            colors: [cpuValue > 80 ? '#ff3e1d' : (cpuValue > 70 ? '#ffab00' : '#71dd37')],
            plotOptions: {
                radialBar: {
                    hollow: { size: '60%' },
                    dataLabels: {
                        name: { fontSize: '14px' },
                        value: { fontSize: '20px', formatter: function (val) { return val.toFixed(1) + '%'; } }
                    }
                }
            }
        };
        if (document.querySelector('#cpuUsageChart')) {
            new ApexCharts(document.querySelector('#cpuUsageChart'), cpuOptions).render();
        }

        // Memory radial bar
        var memOptions = {
            chart: {
                height: 220,
                type: 'radialBar',
                toolbar: { show: false }
            },
            series: [memValue],
            labels: ['PHP Memory %'],
            colors: [memValue > 80 ? '#ff3e1d' : (memValue > 70 ? '#ffab00' : '#696cff')],
            plotOptions: {
                radialBar: {
                    hollow: { size: '60%' },
                    dataLabels: {
                        name: { fontSize: '14px' },
                        value: { fontSize: '20px', formatter: function (val) { return val.toFixed(1) + '%'; } }
                    }
                }
            }
        };
        if (document.querySelector('#memoryUsageChart')) {
            new ApexCharts(document.querySelector('#memoryUsageChart'), memOptions).render();
        }

        // Storage donut
        var storageOptions = {
            chart: {
                type: 'donut',
                height: 220
            },
            series: [
                Math.round(used / (1024 * 1024 * 1024) * 100) / 100,
                Math.round(free / (1024 * 1024 * 1024) * 100) / 100
            ],
            labels: ['Used GB', 'Free GB'],
            colors: ['#696cff', '#d9dee3'],
            legend: {
                position: 'bottom'
            },
            dataLabels: {
                enabled: true
            }
        };
        if (document.querySelector('#storageChart')) {
            new ApexCharts(document.querySelector('#storageChart'), storageOptions).render();
        }
    })();
</script>
@endpush


