<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Process;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Config;
use App\Models\SystemSetting;
use App\Models\EmailAccount;

class BackupController extends BaseAdminController
{
    /**
     * Display backup manager
     */
    public function index()
    {
        $backups = [];
        $backupPath = storage_path('app/backups');
        $totalSize = 0;
        $lastBackup = null;

        if (File::exists($backupPath)) {
            $files = File::files($backupPath);
            foreach ($files as $file) {
                $item = [
                    'name' => $file->getFilename(),
                    'size' => $file->getSize(),
                    'created_at' => date('Y-m-d H:i:s', $file->getMTime()),
                ];
                $backups[] = $item;
                $totalSize += $item['size'];

                if (!$lastBackup || strtotime($item['created_at']) > strtotime($lastBackup['created_at'])) {
                    $lastBackup = $item;
                }
            }
        }

        usort($backups, function($a, $b) {
            return strtotime($b['created_at']) - strtotime($a['created_at']);
        });

        // Backup settings (from system_settings backup group)
        $backupEnabled = SystemSetting::getValue('backup_enabled', false);
        $backupFrequency = SystemSetting::getValue('backup_frequency', 'daily');
        $backupRetentionDays = (int) SystemSetting::getValue('backup_retention_days', 30);
        $backupStorageDriver = SystemSetting::getValue('backup_storage_driver', config('filesystems.default', 'local'));
        $backupEmailRecipient = SystemSetting::getValue('backup_email_recipient', 'lauparadiseadventure@gmail.com');
        $backupSendEmail = SystemSetting::getValue('backup_send_email', true);

        // Basic "next scheduled" approximation: tomorrow at 02:00 or based on frequency
        $now = now();
        $defaultTime = '02:00';
        $timeSetting = SystemSetting::getValue('backup_time', $defaultTime);
        [$hour, $minute] = explode(':', $timeSetting . ':00');
        $nextScheduled = $now->copy()->setTime((int)$hour, (int)$minute, 0);
        if ($nextScheduled->lessThanOrEqualTo($now)) {
            $nextScheduled->addDay();
        }

        $status = [
            'enabled' => (bool) $backupEnabled,
            'frequency' => $backupFrequency,
            'retention_days' => $backupRetentionDays,
            'storage_driver' => $backupStorageDriver,
            'email_recipient' => $backupEmailRecipient,
            'send_email' => (bool) $backupSendEmail,
            'last_backup' => $lastBackup,
            'total_backups' => count($backups),
            'total_size' => $totalSize,
            'next_scheduled_at' => $nextScheduled,
        ];

        // Server checks
        $diskTotal = @disk_total_space($backupPath) ?: 0;
        $diskFree = @disk_free_space($backupPath) ?: 0;
        $diskUsed = $diskTotal > 0 ? $diskTotal - $diskFree : 0;

        $serverChecks = [
            'disk_total' => $diskTotal,
            'disk_free' => $diskFree,
            'disk_used' => $diskUsed,
            'backup_path' => $backupPath,
            'backup_path_writable' => is_writable($backupPath) || (!File::exists($backupPath) && is_writable(dirname($backupPath))),
        ];

        return view('admin.settings.backups', compact('backups', 'status', 'serverChecks'));
    }

    /**
     * Create a new backup
     */
    public function create()
    {
        try {
            $backupPath = storage_path('app/backups');
            
            // Create backup directory if it doesn't exist
            if (!File::exists($backupPath)) {
                File::makeDirectory($backupPath, 0755, true);
            }
            
            // Generate backup filename with timestamp
            $timestamp = date('Y-m-d_H-i-s');
            $filename = "backup_{$timestamp}.sql";
            $filepath = $backupPath . DIRECTORY_SEPARATOR . $filename;
            
            // Get database configuration
            $dbConnection = config('database.default');
            $dbConfig = config("database.connections.{$dbConnection}");
            
            // Create database dump
            if ($dbConfig['driver'] === 'mysql') {
                // Find mysqldump executable
                $mysqldump = $this->findMysqldump();
                
                if (!$mysqldump) {
                    throw new \Exception('mysqldump not found. Please ensure MySQL is installed and mysqldump is in your PATH.');
                }
                
                // Create a temporary MySQL config file for secure credential passing
                $tempConfigFile = storage_path('app/backups/.my.cnf.' . uniqid());
                $configContent = sprintf(
                    "[client]\nuser=%s\npassword=%s\nhost=%s\nport=%s\n",
                    $dbConfig['username'],
                    $dbConfig['password'],
                    $dbConfig['host'],
                    $dbConfig['port'] ?? 3306
                );
                File::put($tempConfigFile, $configContent);
                
                // Set proper permissions (Unix/Linux)
                if (strtoupper(substr(PHP_OS, 0, 3)) !== 'WIN') {
                    chmod($tempConfigFile, 0600);
                }
                
                // Build mysqldump command with all necessary options to capture full database
                $command = sprintf(
                    '"%s" --defaults-file=%s --single-transaction --routines --triggers --events --complete-insert --add-drop-table --add-locks --create-options --disable-keys --extended-insert --quick --lock-tables=false --set-charset --default-character-set=utf8mb4 %s',
                    $mysqldump,
                    escapeshellarg($tempConfigFile),
                    escapeshellarg($dbConfig['database'])
                );
                
                // Execute command and write output to file
                // Use proper file redirection based on OS
                if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
                    // Windows: Use cmd /c for proper redirection
                    $fullCommand = 'cmd /c "' . $command . ' > "' . str_replace('/', '\\', $filepath) . '" 2>&1"';
                } else {
                    // Unix/Linux: Use shell redirection
                    $fullCommand = $command . ' > ' . escapeshellarg($filepath) . ' 2>&1';
                }
                
                // Execute the command
                exec($fullCommand, $execOutput, $execReturn);
                
                // Clean up temp config file
                if (File::exists($tempConfigFile)) {
                    File::delete($tempConfigFile);
                }
                
                // Wait a moment for file to be written
                usleep(500000); // 0.5 seconds
                
                // Check if file was created and has content
                if (!File::exists($filepath)) {
                    $errorMsg = !empty($output) ? implode("\n", $output) : 'Backup file was not created.';
                    throw new \Exception($errorMsg);
                }
                
                $fileSize = File::size($filepath);
                if ($fileSize == 0) {
                    // Read error output if file is empty
                    $errorContent = File::get($filepath);
                    File::delete($filepath);
                    throw new \Exception('Backup file is empty. Error: ' . ($errorContent ?: 'Unknown error. Please check MySQL credentials and mysqldump access.'));
                }
                
                // Verify the backup file contains SQL data
                $fileContent = File::get($filepath);
                if (strpos($fileContent, 'CREATE TABLE') === false && strpos($fileContent, 'INSERT INTO') === false) {
                    // Might be an error message
                    if (stripos($fileContent, 'error') !== false || stripos($fileContent, 'access denied') !== false) {
                        File::delete($filepath);
                        throw new \Exception('Backup failed: ' . substr($fileContent, 0, 500));
                    }
                }
                
                // Send backup via email if enabled
                $sendEmail = SystemSetting::getValue('backup_send_email', true);
                if ($sendEmail) {
                    try {
                        $this->sendBackupEmail($filepath, $filename, $fileSize);
                    } catch (\Exception $e) {
                        // Log error but don't fail the backup
                        \Log::error('Failed to send backup email', ['error' => $e->getMessage()]);
                    }
                }
                
                return $this->successResponse('Backup created successfully! (' . number_format($fileSize / 1024 / 1024, 2) . ' MB)', route('admin.settings.backups'));
            } else {
                // For SQLite or other databases, create a simple export
                throw new \Exception('Backup is currently only supported for MySQL databases.');
            }
        } catch (\Exception $e) {
            return $this->errorResponse('Backup failed: ' . $e->getMessage(), route('admin.settings.backups'));
        }
    }
    
    /**
     * Find mysqldump executable
     */
    private function findMysqldump()
    {
        // Common paths for mysqldump
        $paths = [
            'mysqldump', // In PATH
            'C:\\xampp\\mysql\\bin\\mysqldump.exe',
            'C:\\laragon\\bin\\mysql\\mysql-8.0.30\\bin\\mysqldump.exe',
            'C:\\laragon\\bin\\mysql\\mysql-8.0.31\\bin\\mysqldump.exe',
            'C:\\laragon\\bin\\mysql\\mysql-8.0.32\\bin\\mysqldump.exe',
            'C:\\laragon\\bin\\mysql\\mysql-8.0.33\\bin\\mysqldump.exe',
            'C:\\laragon\\bin\\mysql\\mysql-8.0.34\\bin\\mysqldump.exe',
            'C:\\laragon\\bin\\mysql\\mysql-8.0.35\\bin\\mysqldump.exe',
            'C:\\wamp64\\bin\\mysql\\mysql8.0.27\\bin\\mysqldump.exe',
            'C:\\Program Files\\MySQL\\MySQL Server 8.0\\bin\\mysqldump.exe',
            'C:\\Program Files (x86)\\MySQL\\MySQL Server 8.0\\bin\\mysqldump.exe',
            '/usr/bin/mysqldump',
            '/usr/local/bin/mysqldump',
            '/usr/local/mysql/bin/mysqldump',
        ];
        
        foreach ($paths as $path) {
            if (is_executable($path) || (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN' && file_exists($path))) {
                // Test if it's actually mysqldump
                $testCommand = strtoupper(substr(PHP_OS, 0, 3)) === 'WIN' 
                    ? '"' . $path . '" --version 2>&1'
                    : escapeshellarg($path) . ' --version 2>&1';
                
                exec($testCommand, $testOutput, $testReturn);
                if ($testReturn === 0 || (is_array($testOutput) && !empty($testOutput))) {
                    return $path;
                }
            }
        }
        
        // Try to find in Laragon dynamically
        if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
            $laragonBase = 'C:\\laragon\\bin\\mysql';
            if (is_dir($laragonBase)) {
                $mysqlDirs = glob($laragonBase . '\\mysql-*', GLOB_ONLYDIR);
                if (!empty($mysqlDirs)) {
                    // Get the latest version
                    usort($mysqlDirs, function($a, $b) {
                        return filemtime($b) - filemtime($a);
                    });
                    $mysqldumpPath = $mysqlDirs[0] . '\\bin\\mysqldump.exe';
                    if (file_exists($mysqldumpPath)) {
                        return $mysqldumpPath;
                    }
                }
            }
        }
        
        return null;
    }

    /**
     * Download backup
     */
    public function download($filename)
    {
        $path = storage_path('app/backups/' . $filename);
        
        if (!File::exists($path)) {
            return $this->errorResponse('Backup file not found!', route('admin.settings.backups'));
        }
        
        return response()->download($path);
    }

    /**
     * Delete backup
     */
    public function destroy($filename)
    {
        $path = storage_path('app/backups/' . $filename);
        
        if (File::exists($path)) {
            File::delete($path);
            return $this->successResponse('Backup deleted successfully!', route('admin.settings.backups'));
        }
        
        return $this->errorResponse('Backup file not found!', route('admin.settings.backups'));
    }

    /**
     * Send backup via email
     */
    private function sendBackupEmail($filepath, $filename, $fileSize)
    {
        try {
            // Get email recipient from settings
            $recipientEmail = SystemSetting::getValue('backup_email_recipient', 'lauparadiseadventure@gmail.com');
            
            if (empty($recipientEmail)) {
                return;
            }
            
            // Get email account for sending
            $emailAccount = EmailAccount::where('email', 'lauparadiseadventure@gmail.com')
                ->where('is_active', true)
                ->first();
            
            if (!$emailAccount) {
                return;
            }
            
            // Configure mailer with account settings
            $config = $emailAccount->getSmtpConfig();
            
            Config::set('mail.default', 'smtp');
            Config::set('mail.mailers.smtp.host', $config['host']);
            Config::set('mail.mailers.smtp.port', $config['port']);
            Config::set('mail.mailers.smtp.encryption', $config['encryption']);
            Config::set('mail.mailers.smtp.username', $config['username']);
            Config::set('mail.mailers.smtp.password', $config['password']);
            Config::set('mail.mailers.smtp.timeout', 60);
            Config::set('mail.from.address', $emailAccount->email);
            Config::set('mail.from.name', $emailAccount->name);
            
            // Send email with attachment
            Mail::mailer('smtp')->send('emails.backup-notification', [
                'filename' => $filename,
                'fileSize' => $fileSize,
                'fileSizeFormatted' => number_format($fileSize / 1024 / 1024, 2) . ' MB',
                'backupDate' => now()->format('F j, Y \a\t g:i A'),
                'databaseName' => config('database.connections.' . config('database.default') . '.database'),
            ], function ($message) use ($recipientEmail, $filename, $filepath, $emailAccount) {
                $message->to($recipientEmail)
                         ->from($emailAccount->email, $emailAccount->name)
                         ->subject('Database Backup - ' . date('Y-m-d H:i:s') . ' | Lau Paradise Adventures')
                         ->attach($filepath, [
                             'as' => $filename,
                             'mime' => 'application/sql',
                         ]);
            });
            
        } catch (\Exception $e) {
            \Log::error('Backup email failed', [
                'error' => $e->getMessage(),
            ]);
            throw $e;
        }
    }
}

