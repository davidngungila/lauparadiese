<?php

namespace App\Http\Controllers\Admin;

use App\Models\SystemSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class SystemSettingsController extends BaseAdminController
{
    /**
     * Display system settings with grouped tabs
     */
    public function index()
    {
        // Get settings by groups
        $settings = [
            'general' => $this->getSettingsByGroup('general'),
            'application' => $this->getSettingsByGroup('application'),
            'email' => $this->getSettingsByGroup('email'),
            'security' => $this->getSettingsByGroup('security'),
            'performance' => $this->getSettingsByGroup('performance'),
            'maintenance' => $this->getSettingsByGroup('maintenance'),
            'backup' => $this->getSettingsByGroup('backup'),
            'logging' => $this->getSettingsByGroup('logging'),
        ];

        // Get current config values as fallback
        $configValues = [
            'app_name' => config('app.name', 'Lau Paradise Adventures'),
            'app_url' => config('app.url', url('/')),
            'app_timezone' => config('app.timezone', 'Africa/Dar_es_Salaam'),
            'app_locale' => config('app.locale', 'en'),
            'app_debug' => config('app.debug', false),
            'mail_mailer' => config('mail.default', 'smtp'),
            'mail_host' => config('mail.mailers.smtp.host', ''),
            'mail_port' => config('mail.mailers.smtp.port', 587),
            'mail_username' => config('mail.mailers.smtp.username', ''),
            'mail_from_address' => config('mail.from.address', ''),
            'mail_from_name' => config('mail.from.name', ''),
        ];

        return view('admin.settings.system', compact('settings', 'configValues'));
    }

    /**
     * Display system health dashboard
     */
    public function health()
    {
        // 1. System overview
        $system = [
            'status' => 'healthy',
            'php_version' => PHP_VERSION,
            'php_recommended' => '8.2+',
            'laravel_version' => app()->version(),
            'server_os' => PHP_OS_FAMILY,
            'app_env' => config('app.env'),
            'app_debug' => config('app.debug'),
        ];

        // 2. Application health
        $logPath = storage_path('logs/laravel.log');
        $logExists = file_exists($logPath);
        $logSize = $logExists ? filesize($logPath) : 0;

        $application = [
            'log_path' => $logPath,
            'log_exists' => $logExists,
            'log_size' => $logSize,
            'queue_connection' => config('queue.default'),
            'cache_driver' => config('cache.default'),
            'session_driver' => config('session.driver'),
        ];

        // 3. Database health
        try {
            $dbStatus = DB::connection()->getPdo() ? 'connected' : 'disconnected';
        } catch (\Throwable $e) {
            $dbStatus = 'disconnected';
        }

        $database = [
            'status' => $dbStatus,
            'connection' => config('database.default'),
            'host' => config('database.connections.' . config('database.default') . '.host'),
            'port' => config('database.connections.' . config('database.default') . '.port'),
            // Detailed migration status is intentionally not executed here to avoid running console commands on each request.
            'pending_migrations' => null,
        ];

        // 4. Server health (basic but real-time snapshot)
        // CPU (approximate using system load if available)
        $cpuPercent = null;
        if (function_exists('sys_getloadavg')) {
            $load = sys_getloadavg();
            $oneMinuteLoad = $load[0] ?? 0;
            // Assume 4 cores if we cannot detect core count
            $cores = (int) (ini_get('cpu_cores') ?: 4);
            $cpuPercent = max(0, min(100, round(($oneMinuteLoad / max($cores, 1)) * 100, 1)));
        }

        // Memory (PHP memory usage snapshot)
        $phpMemoryUsed = memory_get_usage(true);
        $phpMemoryLimit = ini_get('memory_limit');
        $phpMemoryLimitBytes = -1;
        if ($phpMemoryLimit && $phpMemoryLimit !== '-1') {
            $unit = strtolower(substr($phpMemoryLimit, -1));
            $value = (int) $phpMemoryLimit;
            $phpMemoryLimitBytes = match ($unit) {
                'g' => $value * 1024 * 1024 * 1024,
                'm' => $value * 1024 * 1024,
                'k' => $value * 1024,
                default => (int) $phpMemoryLimit,
            };
        }
        $phpMemoryPercent = $phpMemoryLimitBytes > 0
            ? round(($phpMemoryUsed / $phpMemoryLimitBytes) * 100, 1)
            : null;

        // Storage
        $storageTotal = disk_total_space(base_path());
        $storageFree = disk_free_space(base_path());

        // Folder sizes (logs, storage/app)
        $logsSize = 0;
        $storageAppSize = 0;
        try {
            foreach (\Illuminate\Support\Facades\File::allFiles(storage_path('logs')) as $file) {
                $logsSize += $file->getSize();
            }
        } catch (\Throwable $e) {
            $logsSize = 0;
        }
        try {
            foreach (\Illuminate\Support\Facades\File::allFiles(storage_path('app')) as $file) {
                $storageAppSize += $file->getSize();
            }
        } catch (\Throwable $e) {
            $storageAppSize = 0;
        }

        $server = [
            'cpu_percent' => $cpuPercent,
            'php_memory_used' => $phpMemoryUsed,
            'php_memory_limit' => $phpMemoryLimitBytes,
            'php_memory_percent' => $phpMemoryPercent,
            'storage_total' => $storageTotal,
            'storage_free' => $storageFree,
            'logs_size' => $logsSize,
            'storage_app_size' => $storageAppSize,
        ];

        // 5. Security status
        $security = [
            'ssl' => request()->isSecure(),
            'app_debug' => config('app.debug'),
            'app_env' => config('app.env'),
            'storage_writable' => is_writable(storage_path()),
            'cache_writable' => is_writable(base_path('bootstrap/cache')),
            'logs_writable' => is_writable(storage_path('logs')),
        ];

        // 6. Integrations (basic config-based)
        $integrations = [
            'mail' => config('mail.default'),
            'payment_gateways' => [
                'mpesa' => config('services.mpesa.enabled', false),
                'stripe' => config('services.stripe.key') ? true : false,
                'paypal' => config('services.paypal.client_id') ? true : false,
            ],
        ];

        // 7. Storage & backups (basic)
        $storageRoot = Storage::disk(config('filesystems.default', 'local'))->path('/');
        $storage = [
            'disk' => config('filesystems.default', 'local'),
            'root' => $storageRoot,
        ];

        // 8. Issues (placeholder – could be extended with real checks)
        $issues = [];
        if ($dbStatus !== 'connected') {
            $issues[] = [
                'severity' => 'critical',
                'name' => 'Database connection failed',
                'detected_at' => now(),
                'how_to_fix' => 'Check DB_HOST, DB_PORT, DB_DATABASE, DB_USERNAME, DB_PASSWORD in .env and ensure MySQL is running.',
            ];
            $system['status'] = 'critical';
        } elseif ($logSize > 50 * 1024 * 1024) { // 50MB
            $issues[] = [
                'severity' => 'warning',
                'name' => 'Log file too large',
                'detected_at' => now(),
                'how_to_fix' => 'Rotate or clear logs from Settings → System Logs or manually from storage/logs.',
            ];
            $system['status'] = 'warning';
        }

        return view('admin.settings.system-health', compact(
            'system',
            'application',
            'database',
            'server',
            'security',
            'integrations',
            'storage',
            'issues'
        ));
    }

    /**
     * Get settings by group
     */
    private function getSettingsByGroup(string $group)
    {
        try {
            if (!Schema::hasTable('system_settings')) {
                return collect([]);
            }
            return SystemSetting::where('group', $group)->get()->keyBy('key');
        } catch (\Exception $e) {
            Log::warning('Failed to get settings by group: ' . $e->getMessage());
            return collect([]);
        }
    }

    /**
     * Update system settings
     */
    public function update(Request $request)
    {
        $group = $request->input('group', 'general');
        
        try {
            if (!Schema::hasTable('system_settings')) {
                return response()->json([
                    'success' => false,
                    'message' => 'System settings table does not exist. Please run migrations.',
                ], 500);
            }

            $validated = $this->validateByGroup($request, $group);
            
            DB::beginTransaction();

            foreach ($validated as $key => $value) {
                // Skip group field
                if ($key === 'group') continue;
                
                SystemSetting::updateOrCreate(
                    ['key' => $key],
                    [
                        'value' => $value,
                        'type' => $this->getFieldType($key, $value),
                        'group' => $group,
                        'description' => $this->getFieldDescription($key),
                    ]
                );
                
                // Clear cache
                Cache::forget("system_setting_{$key}");
            }

            DB::commit();

            Log::info('System settings updated', [
                'group' => $group,
                'updated_by' => auth()->id(),
            ]);

            if ($request->expectsJson() || $request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => ucfirst($group) . ' settings updated successfully!',
                ]);
            }

            return redirect()->route('admin.settings.system')
                ->with('success', ucfirst($group) . ' settings updated successfully!');
                
        } catch (\Illuminate\Validation\ValidationException $e) {
            if ($request->expectsJson() || $request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $e->errors(),
                ], 422);
            }
            return back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to update system settings', [
                'error' => $e->getMessage(),
                'group' => $group,
            ]);

            if ($request->expectsJson() || $request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to update settings: ' . $e->getMessage(),
                ], 500);
            }

            return $this->errorResponse('Failed to update settings: ' . $e->getMessage());
        }
    }

    /**
     * Validate settings by group
     */
    private function validateByGroup(Request $request, string $group)
    {
        $rules = [];

        switch ($group) {
            case 'general':
                $rules = [
                    'app_name' => 'required|string|max:255',
                    'app_url' => 'required|url|max:500',
                    'app_timezone' => 'required|string|max:100',
                    'app_locale' => 'required|string|max:10',
                    'app_currency' => 'nullable|string|size:3',
                    'app_currency_symbol' => 'nullable|string|max:10',
                ];
                break;
            case 'application':
                $rules = [
                    'app_debug' => 'nullable|boolean',
                    'app_maintenance_mode' => 'nullable|boolean',
                    'app_session_lifetime' => 'nullable|integer|min:1|max:1440',
                    'app_max_upload_size' => 'nullable|integer|min:1',
                    'app_allowed_file_types' => 'nullable|string|max:500',
                ];
                break;
            case 'email':
                $rules = [
                    'mail_mailer' => 'required|string|in:smtp,sendmail,mailgun,ses,postmark,log',
                    'mail_host' => 'nullable|string|max:255',
                    'mail_port' => 'nullable|integer|min:1|max:65535',
                    'mail_username' => 'nullable|string|max:255',
                    'mail_password' => 'nullable|string|max:255',
                    'mail_encryption' => 'nullable|string|in:tls,ssl',
                    'mail_from_address' => 'required|email|max:255',
                    'mail_from_name' => 'required|string|max:255',
                ];
                break;
            case 'security':
                $rules = [
                    'security_password_min_length' => 'nullable|integer|min:6|max:32',
                    'security_password_require_uppercase' => 'nullable|boolean',
                    'security_password_require_lowercase' => 'nullable|boolean',
                    'security_password_require_numbers' => 'nullable|boolean',
                    'security_password_require_symbols' => 'nullable|boolean',
                    'security_session_timeout' => 'nullable|integer|min:5|max:1440',
                    'security_max_login_attempts' => 'nullable|integer|min:3|max:10',
                    'security_enable_2fa' => 'nullable|boolean',
                ];
                break;
            case 'performance':
                $rules = [
                    'cache_driver' => 'nullable|string|in:file,redis,memcached',
                    'cache_ttl' => 'nullable|integer|min:1',
                    'queue_driver' => 'nullable|string|in:sync,database,redis,sqs',
                    'enable_query_cache' => 'nullable|boolean',
                    'enable_page_cache' => 'nullable|boolean',
                ];
                break;
            case 'maintenance':
                $rules = [
                    'maintenance_enabled' => 'nullable|boolean',
                    'maintenance_message' => 'nullable|string|max:1000',
                    'maintenance_allowed_ips' => 'nullable|string|max:500',
                ];
                break;
            case 'backup':
                $rules = [
                    'backup_enabled' => 'nullable|boolean',
                    'backup_frequency' => 'nullable|string|in:daily,weekly,monthly',
                    'backup_retention_days' => 'nullable|integer|min:1|max:365',
                    'backup_storage_driver' => 'nullable|string|in:local,s3',
                ];
                break;
            case 'logging':
                $rules = [
                    'log_level' => 'nullable|string|in:debug,info,warning,error',
                    'log_max_files' => 'nullable|integer|min:1|max:100',
                    'log_enable_daily' => 'nullable|boolean',
                    'log_enable_slack' => 'nullable|boolean',
                    'log_slack_webhook' => 'nullable|url|max:500',
                ];
                break;
        }

        return $request->validate($rules);
    }

    /**
     * Get field type
     */
    private function getFieldType(string $key, $value)
    {
        if (is_bool($value) || in_array($key, ['app_debug', 'app_maintenance_mode', 'maintenance_enabled', 'backup_enabled'])) {
            return 'boolean';
        }
        if (is_int($value) || preg_match('/^(port|timeout|length|days|files|attempts|ttl|size)/', $key)) {
            return 'integer';
        }
        if (filter_var($value, FILTER_VALIDATE_URL)) {
            return 'url';
        }
        if (filter_var($value, FILTER_VALIDATE_EMAIL)) {
            return 'email';
        }
        return 'text';
    }

    /**
     * Get field description
     */
    private function getFieldDescription(string $key)
    {
        $descriptions = [
            'app_name' => 'The name of your application',
            'app_url' => 'The base URL of your application',
            'app_timezone' => 'Default timezone for the application',
            'app_locale' => 'Default locale/language for the application',
            'app_currency' => 'Default currency code (e.g., USD, TZS)',
            'app_currency_symbol' => 'Currency symbol (e.g., $, TSh)',
            'app_debug' => 'Enable or disable debug mode',
            'app_maintenance_mode' => 'Enable or disable maintenance mode',
            'app_session_lifetime' => 'Session lifetime in minutes',
            'app_max_upload_size' => 'Maximum file upload size in MB',
            'app_allowed_file_types' => 'Comma-separated list of allowed file types',
            'mail_mailer' => 'Mail driver to use',
            'mail_host' => 'SMTP server hostname',
            'mail_port' => 'SMTP server port',
            'mail_username' => 'SMTP username',
            'mail_password' => 'SMTP password',
            'mail_encryption' => 'Encryption method (tls or ssl)',
            'mail_from_address' => 'Default "from" email address',
            'mail_from_name' => 'Default "from" name',
            'security_password_min_length' => 'Minimum password length',
            'security_password_require_uppercase' => 'Require uppercase letters in passwords',
            'security_password_require_lowercase' => 'Require lowercase letters in passwords',
            'security_password_require_numbers' => 'Require numbers in passwords',
            'security_password_require_symbols' => 'Require special characters in passwords',
            'security_session_timeout' => 'Session timeout in minutes',
            'security_max_login_attempts' => 'Maximum login attempts before lockout',
            'security_enable_2fa' => 'Enable two-factor authentication',
            'cache_driver' => 'Cache driver to use',
            'cache_ttl' => 'Cache time-to-live in minutes',
            'queue_driver' => 'Queue driver to use',
            'enable_query_cache' => 'Enable database query caching',
            'enable_page_cache' => 'Enable page caching',
            'maintenance_enabled' => 'Enable maintenance mode',
            'maintenance_message' => 'Message to display during maintenance',
            'maintenance_allowed_ips' => 'Comma-separated list of IPs allowed during maintenance',
            'backup_enabled' => 'Enable automatic backups',
            'backup_frequency' => 'How often to run backups',
            'backup_retention_days' => 'Number of days to keep backups',
            'backup_storage_driver' => 'Storage driver for backups',
            'log_level' => 'Minimum log level to record',
            'log_max_files' => 'Maximum number of log files to keep',
            'log_enable_daily' => 'Enable daily log rotation',
            'log_enable_slack' => 'Enable Slack notifications for logs',
            'log_slack_webhook' => 'Slack webhook URL for log notifications',
        ];

        return $descriptions[$key] ?? null;
    }
}
