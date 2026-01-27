<?php

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "Testing import script...\n";
echo "PHP Version: " . PHP_VERSION . "\n";

use App\Models\Tour;
use Illuminate\Support\Facades\Http;

echo "Testing HTTP request...\n";
try {
    $response = Http::timeout(10)->get('https://altezzatravel.com/tanzania-safari/tours/serengeti-great-migration-ngorongoro-tarangire');
    echo "Status: " . $response->status() . "\n";
    echo "Content length: " . strlen($response->body()) . "\n";
    
    if ($response->successful()) {
        $html = $response->body();
        if (preg_match('/<h1[^>]*>(.*?)<\/h1>/is', $html, $matches)) {
            echo "Found title: " . trim(strip_tags($matches[1])) . "\n";
        }
    }
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}

echo "Current tours in database: " . Tour::count() . "\n";
echo "Done!\n";






