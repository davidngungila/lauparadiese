<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;
use App\Models\EmailAccount;
use App\Models\SystemSetting;

class CreateBackup extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'backup:create {--no-email}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a database backup and optionally send via email';

    /**
     * Execute the console command.
     */
    public function handle()
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
            $filepath = $backupPath . '/' . $filename;
            
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
                
                // Build mysqldump command
                $command = sprintf(
                    '"%s" --defaults-file=%s --single-transaction --routines --triggers --events --complete-insert --add-drop-table --add-locks --create-options --disable-keys --extended-insert --quick --lock-tables=false --set-charset --default-character-set=utf8mb4 %s',
                    $mysqldump,
                    escapeshellarg($tempConfigFile),
                    escapeshellarg($dbConfig['database'])
                );
                
                // Execute command and write output to file
                if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
                    $fullCommand = 'cmd /c "' . $command . ' > "' . str_replace('/', '\\', $filepath) . '" 2>&1"';
                } else {
                    $fullCommand = $command . ' > ' . escapeshellarg($filepath) . ' 2>&1';
                }
                
                exec($fullCommand, $execOutput, $execReturn);
                
                // Clean up temp config file
                if (File::exists($tempConfigFile)) {
                    File::delete($tempConfigFile);
                }
                
                // Wait a moment for file to be written
                usleep(500000);
                
                // Check if file was created and has content
                if (!File::exists($filepath)) {
                    throw new \Exception('Backup file was not created.');
                }
                
                $fileSize = File::size($filepath);
                if ($fileSize == 0) {
                    $errorContent = File::get($filepath);
                    File::delete($filepath);
                    throw new \Exception('Backup file is empty. Error: ' . ($errorContent ?: 'Unknown error.'));
                }
                
                $this->info("✓ Backup created successfully: {$filename} (" . number_format($fileSize / 1024 / 1024, 2) . " MB)");
                
                // Send email if enabled and not disabled via flag
                if (!$this->option('no-email')) {
                    $this->sendBackupEmail($filepath, $filename, $fileSize);
                }
                
                // Clean up old backups based on retention
                $this->cleanupOldBackups();
                
                return 0;
            } else {
                $this->error('Backup is currently only supported for MySQL databases.');
                return 1;
            }
        } catch (\Exception $e) {
            $this->error('Backup failed: ' . $e->getMessage());
            Log::error('Backup creation failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            return 1;
        }
    }

    /**
     * Send backup via email
     */
    private function sendBackupEmail($filepath, $filename, $fileSize)
    {
        try {
            // Get email recipient from settings or use default
            $recipientEmail = SystemSetting::getValue('backup_email_recipient', 'lauparadiseadventure@gmail.com');
            
            if (empty($recipientEmail)) {
                $this->warn('No backup email recipient configured. Skipping email send.');
                return;
            }
            
            $this->info("Sending backup to: {$recipientEmail}...");
            
            // Get email account for sending
            $emailAccount = EmailAccount::where('email', 'lauparadiseadventure@gmail.com')
                ->where('is_active', true)
                ->first();
            
            if (!$emailAccount) {
                $this->warn('Email account not found. Skipping email send.');
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
            Config::set('mail.mailers.smtp.timeout', 60); // Longer timeout for large files
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
            
            $this->info("✓ Backup email sent successfully to {$recipientEmail}!");
            
        } catch (\Exception $e) {
            $this->warn("⚠ Failed to send backup email: " . $e->getMessage());
            Log::error('Backup email failed', [
                'error' => $e->getMessage(),
                'recipient' => $recipientEmail ?? 'not set',
            ]);
            // Don't fail the backup if email fails
        }
    }

    /**
     * Clean up old backups based on retention policy
     */
    private function cleanupOldBackups()
    {
        try {
            $retentionDays = (int) SystemSetting::getValue('backup_retention_days', 30);
            $backupPath = storage_path('app/backups');
            
            if (!File::exists($backupPath)) {
                return;
            }
            
            $files = File::files($backupPath);
            $cutoffDate = now()->subDays($retentionDays);
            $deletedCount = 0;
            
            foreach ($files as $file) {
                if ($file->getMTime() < $cutoffDate->timestamp) {
                    File::delete($file->getPathname());
                    $deletedCount++;
                }
            }
            
            if ($deletedCount > 0) {
                $this->info("✓ Cleaned up {$deletedCount} old backup(s) (retention: {$retentionDays} days)");
            }
        } catch (\Exception $e) {
            $this->warn("⚠ Failed to cleanup old backups: " . $e->getMessage());
        }
    }

    /**
     * Find mysqldump executable
     */
    private function findMysqldump()
    {
        $paths = [
            'mysqldump',
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
}
