<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class DownloadDataTables extends Command
{
    protected $signature = 'datatables:download';
    protected $description = 'Download DataTables CSS and JS files to local assets';

    public function handle()
    {
        $this->info('Downloading DataTables files...');

        $basePath = public_path('assets/assets/vendor/libs');
        
        // Create directories
        $dirs = [
            $basePath . '/datatables-bs5',
            $basePath . '/datatables-responsive-bs5',
            $basePath . '/datatables-buttons-bs5',
        ];

        foreach ($dirs as $dir) {
            if (!is_dir($dir)) {
                mkdir($dir, 0755, true);
                $this->info("Created directory: $dir");
            }
        }

        // Files to download
        $files = [
            [
                'url' => 'https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css',
                'path' => $basePath . '/datatables-bs5/datatables.bootstrap5.css'
            ],
            [
                'url' => 'https://cdn.datatables.net/responsive/2.5.0/css/responsive.bootstrap5.min.css',
                'path' => $basePath . '/datatables-responsive-bs5/responsive.bootstrap5.css'
            ],
            [
                'url' => 'https://cdn.datatables.net/buttons/2.4.1/css/buttons.bootstrap5.min.css',
                'path' => $basePath . '/datatables-buttons-bs5/buttons.bootstrap5.css'
            ],
            [
                'url' => 'https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js',
                'path' => $basePath . '/datatables-bs5/jquery.dataTables.min.js'
            ],
            [
                'url' => 'https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js',
                'path' => $basePath . '/datatables-bs5/datatables-bootstrap5.js'
            ],
            [
                'url' => 'https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js',
                'path' => $basePath . '/datatables-responsive-bs5/dataTables.responsive.min.js'
            ],
            [
                'url' => 'https://cdn.datatables.net/responsive/2.5.0/js/responsive.bootstrap5.min.js',
                'path' => $basePath . '/datatables-responsive-bs5/responsive.bootstrap5.min.js'
            ],
            [
                'url' => 'https://cdn.datatables.net/buttons/2.4.1/js/dataTables.buttons.min.js',
                'path' => $basePath . '/datatables-buttons-bs5/dataTables.buttons.min.js'
            ],
            [
                'url' => 'https://cdn.datatables.net/buttons/2.4.1/js/buttons.bootstrap5.min.js',
                'path' => $basePath . '/datatables-buttons-bs5/buttons.bootstrap5.js'
            ],
        ];

        $success = 0;
        $failed = 0;

        foreach ($files as $file) {
            $filename = basename($file['path']);
            $this->info("Downloading $filename...");
            
            try {
                $response = \Illuminate\Support\Facades\Http::timeout(30)->get($file['url']);
                
                if ($response->successful()) {
                    $content = $response->body();
                    if (file_put_contents($file['path'], $content) !== false) {
                        $size = filesize($file['path']);
                        $this->info("  ✓ Saved ($size bytes)");
                        $success++;
                    } else {
                        $this->error("  ✗ Failed to write file to: " . $file['path']);
                        $failed++;
                    }
                } else {
                    $this->error("  ✗ HTTP Error: " . $response->status());
                    $failed++;
                }
            } catch (\Exception $e) {
                $this->error("  ✗ Exception: " . $e->getMessage());
                $failed++;
            }
        }

        $this->newLine();
        $this->info("Download complete!");
        $this->info("Success: $success | Failed: $failed");

        return Command::SUCCESS;
    }
}

