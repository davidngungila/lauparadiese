<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class SystemLogsController extends BaseAdminController
{
    /**
     * Display system logs
     */
    public function index(Request $request)
    {
        $logFiles = $this->getLogFiles();
        $selectedFile = $request->get('file', 'laravel.log');
        $level = $request->get('level', 'all');
        $dateFrom = $request->get('date_from');
        $dateTo = $request->get('date_to');
        $search = $request->get('search');
        
        $logs = [];
        $logFile = storage_path("logs/{$selectedFile}");
        
        if (File::exists($logFile)) {
            $content = File::get($logFile);
            $lines = explode("\n", $content);
            
            // Parse log entries
            $parsedLogs = [];
            $currentEntry = null;
            
            foreach ($lines as $line) {
                if (empty(trim($line))) {
                    if ($currentEntry) {
                        $parsedLogs[] = $currentEntry;
                        $currentEntry = null;
                    }
                    continue;
                }
                
                // Check if line starts a new log entry (contains timestamp)
                if (preg_match('/^\[(\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2})\] /', $line, $matches)) {
                    if ($currentEntry) {
                        $parsedLogs[] = $currentEntry;
                    }
                    
                    $timestamp = $matches[1];
                    $date = Carbon::parse($timestamp);
                    
                    // Filter by date range
                    if ($dateFrom && $date < Carbon::parse($dateFrom)) continue;
                    if ($dateTo && $date > Carbon::parse($dateTo)->endOfDay()) continue;
                    
                    // Extract log level
                    $logLevel = 'info';
                    if (stripos($line, 'ERROR') !== false || stripos($line, 'CRITICAL') !== false) {
                        $logLevel = 'error';
                    } elseif (stripos($line, 'WARNING') !== false) {
                        $logLevel = 'warning';
                    } elseif (stripos($line, 'DEBUG') !== false) {
                        $logLevel = 'debug';
                    }
                    
                    // Filter by level
                    if ($level !== 'all' && $logLevel !== $level) continue;
                    
                    // Filter by search
                    if ($search && stripos($line, $search) === false) continue;
                    
                    $currentEntry = [
                        'timestamp' => $timestamp,
                        'date' => $date,
                        'level' => $logLevel,
                        'message' => substr($line, strlen($matches[0])),
                        'full_line' => $line,
                    ];
                } elseif ($currentEntry) {
                    $currentEntry['message'] .= "\n" . $line;
                    $currentEntry['full_line'] .= "\n" . $line;
                }
            }
            
            if ($currentEntry) {
                $parsedLogs[] = $currentEntry;
            }
            
            // Reverse to show newest first
            $logs = array_reverse($parsedLogs);
            
            // Limit to last 1000 entries
            $logs = array_slice($logs, 0, 1000);
        }
        
        // Statistics
        $stats = [
            'total_entries' => count($logs),
            'errors' => count(array_filter($logs, fn($log) => $log['level'] === 'error')),
            'warnings' => count(array_filter($logs, fn($log) => $log['level'] === 'warning')),
            'info' => count(array_filter($logs, fn($log) => $log['level'] === 'info')),
        ];
        
        return view('admin.settings.system-logs', compact('logs', 'level', 'logFiles', 'selectedFile', 'dateFrom', 'dateTo', 'search', 'stats'));
    }

    /**
     * Get available log files
     */
    private function getLogFiles()
    {
        $logPath = storage_path('logs');
        $files = [];
        
        if (File::exists($logPath)) {
            $allFiles = File::files($logPath);
            foreach ($allFiles as $file) {
                if ($file->getExtension() === 'log') {
                    $files[] = $file->getFilename();
                }
            }
        }
        
        return $files ?: ['laravel.log'];
    }

    /**
     * Clear system logs
     */
    public function clear(Request $request)
    {
        $selectedFile = $request->get('file', 'laravel.log');
        $logFile = storage_path("logs/{$selectedFile}");
        
        if (File::exists($logFile)) {
            File::put($logFile, '');
            
            // Log the action
            \App\Models\AuditTrail::log([
                'action' => 'cleared',
                'description' => "System log file cleared: {$selectedFile}",
                'module' => 'system_logs',
            ]);
            
            return $this->successResponse('System logs cleared successfully!', route('admin.settings.system-logs'));
        }
        
        return $this->errorResponse('Log file not found!', route('admin.settings.system-logs'));
    }

    /**
     * Download log file
     */
    public function download(Request $request)
    {
        $selectedFile = $request->get('file', 'laravel.log');
        $logFile = storage_path("logs/{$selectedFile}");
        
        if (File::exists($logFile)) {
            return response()->download($logFile, $selectedFile);
        }
        
        return $this->errorResponse('Log file not found!', route('admin.settings.system-logs'));
    }
}

