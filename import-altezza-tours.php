<?php

/**
 * Import tours from altezzatravel.com
 * This script will scrape and import tour packages
 */

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Tour;
use App\Models\Destination;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;

error_reporting(E_ALL);
ini_set('display_errors', 1);

$baseUrl = 'https://altezzatravel.com';
$logFile = __DIR__ . '/import-log.txt';

function logMessage($message) {
    global $logFile;
    $timestamp = date('Y-m-d H:i:s');
    $log = "[$timestamp] $message\n";
    echo $log;
    file_put_contents($logFile, $log, FILE_APPEND);
}

logMessage("========================================");
logMessage("Altezza Travel Tours Scraper");
logMessage("========================================");
logMessage("");

// Known tour URLs from altezzatravel.com (from actual website)
$tourUrls = [
    // Safari Tours
    'https://altezzatravel.com/tanzania-safari/tours/serengeti-great-migration-ngorongoro-tarangire',
    'https://altezzatravel.com/tanzania-safari/tours/lake-natron-ol-doinyo-lengai-hike-flamingo-walks',
    'https://altezzatravel.com/tanzania-safari/tours/wild-trails-of-tarangire-3-day-safari',
    'https://altezzatravel.com/tanzania-safari/tours/migration-calving-safari-tarangire-ngorongoro-serengeti',
    'https://altezzatravel.com/tanzania-safari/tours/luxury-migration-safari-tarangire-ngorongoro-serengeti',
    'https://altezzatravel.com/tanzania-safari/tours/serengeti-cheetah-safari-and-tarangire-ngorongoro',
    'https://altezzatravel.com/tanzania-safari/tours/ngorongoro-day-trip',
    'https://altezzatravel.com/tanzania-safari/tours/lake-natron-ngorongoro-safari',
    'https://altezzatravel.com/tanzania-safari/tours/crater-world-ngorongoro-empakaai',
    
    // Additional common tour patterns
    'https://altezzatravel.com/tours/3-days-safari-tarangire-ngorongoro-serengeti',
    'https://altezzatravel.com/tours/4-days-safari-tarangire-serengeti-ngorongoro',
    'https://altezzatravel.com/tours/5-days-safari-tarangire-serengeti-ngorongoro',
    'https://altezzatravel.com/tours/6-days-safari-tarangire-serengeti-ngorongoro',
    'https://altezzatravel.com/tours/7-days-safari-tarangire-serengeti-ngorongoro',
];

// Try to get more URLs from sitemap
logMessage("Step 1: Fetching tour URLs from sitemap...");
try {
    $sitemapResponse = Http::timeout(10)->withOptions(['verify' => false])->get($baseUrl . '/sitemap.xml');
    if ($sitemapResponse->successful()) {
        $xml = $sitemapResponse->body();
        $dom = new DOMDocument();
        @$dom->loadXML($xml);
        $xpath = new DOMXPath($dom);
        $xpath->registerNamespace('sm', 'http://www.sitemaps.org/schemas/sitemap/0.9');
        
        $urls = $xpath->query('//sm:url/sm:loc');
        foreach ($urls as $url) {
            $urlString = trim($url->textContent);
            if ((strpos($urlString, '/tour') !== false || 
                 strpos($urlString, '/safari') !== false ||
                 strpos($urlString, '/kilimanjaro') !== false ||
                 strpos($urlString, '/zanzibar') !== false) &&
                strpos($urlString, $baseUrl) === 0) {
                if (!in_array($urlString, $tourUrls)) {
                    $tourUrls[] = $urlString;
                }
            }
        }
        logMessage("Found " . count($tourUrls) . " tour URLs");
    }
} catch (Exception $e) {
    logMessage("Could not fetch sitemap, using predefined URLs");
}

// Limit if specified
$limit = isset($argv[1]) ? (int) $argv[1] : null;
if ($limit && $limit > 0) {
    $tourUrls = array_slice($tourUrls, 0, $limit);
    logMessage("Limited to {$limit} tours");
}

logMessage("Step 2: Scraping " . count($tourUrls) . " tours...");
logMessage("");

$imported = 0;
$skipped = 0;
$errors = 0;

foreach ($tourUrls as $index => $url) {
    $num = $index + 1;
    $total = count($tourUrls);
    logMessage("[{$num}/{$total}] Processing: " . substr($url, strlen($baseUrl)));
    
    try {
        $response = Http::timeout(30)
            ->withOptions(['verify' => false]) // Disable SSL verification for local development
            ->withHeaders([
                'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36',
                'Accept' => 'text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8',
            ])
            ->get($url);
        
        if (!$response->successful()) {
            logMessage("  ⚠ Failed to fetch (Status: {$response->status()})");
            logMessage("");
            $errors++;
            continue;
        }
        
        $html = $response->body();
        $tour = parseTourPage($html, $url);
        
        if ($tour && !empty($tour['name']) && $tour['name'] !== 'Untitled Tour') {
            if (importTour($tour)) {
                $imported++;
                logMessage("  ✓ Imported: {$tour['name']}");
                if ($tour['price']) {
                    logMessage("    Price: $" . number_format($tour['price']));
                }
                if ($tour['duration_days']) {
                    logMessage("    Duration: {$tour['duration_days']} days");
                }
            } else {
                $skipped++;
                logMessage("  ⊘ Skipped (already exists): {$tour['name']}");
            }
        } else {
            logMessage("  ⚠ Could not extract tour data");
            $errors++;
        }
        
    } catch (Exception $e) {
        logMessage("  ✗ Error: " . $e->getMessage());
        $errors++;
    }
    
    logMessage("");
    
    // Small delay to avoid overwhelming the server
    usleep(500000); // 0.5 seconds
}

logMessage("========================================");
logMessage("Summary:");
logMessage("  Imported: {$imported}");
logMessage("  Skipped: {$skipped}");
logMessage("  Errors: {$errors}");
logMessage("========================================");

ob_end_flush();

function parseTourPage($html, $url) {
    $dom = new DOMDocument();
    @$dom->loadHTML('<?xml encoding="UTF-8">' . $html);
    $xpath = new DOMXPath($dom);
    
    // Extract title
    $name = getText($xpath, "//h1");
    if (!$name) {
        $name = getText($xpath, "//title");
    }
    $name = preg_replace('/\s*[-|]\s*Altezza Travel.*$/i', '', $name ?? '');
    $name = trim($name ?: 'Untitled Tour');
    
    // Extract description
    $description = getText($xpath, "//div[contains(@class, 'description')]")
        ?: getText($xpath, "//div[contains(@class, 'content')]")
        ?: getText($xpath, "//article//p")
        ?: getMeta($xpath, "description");
    
    // Extract price
    $price = extractPrice($html);
    
    // Extract duration
    $duration = extractDuration($html);
    
    // Extract image
    $image = getMeta($xpath, "og:image");
    if (!$image) {
        $image = getAttribute($xpath, "//img[contains(@class, 'hero')]", "src")
            ?: getAttribute($xpath, "//img[contains(@class, 'featured')]", "src")
            ?: getAttribute($xpath, "//img[1]", "src");
    }
    
    if ($image && strpos($image, 'http') !== 0) {
        $image = 'https://altezzatravel.com/' . ltrim($image, '/');
    }
    
    return [
        'url' => $url,
        'name' => $name,
        'description' => $description,
        'short_description' => substr($description ?? '', 0, 500),
        'price' => $price,
        'duration_days' => $duration,
        'image_url' => $image,
    ];
}

function getText($xpath, $query) {
    $nodes = $xpath->query($query);
    if ($nodes && $nodes->length > 0) {
        return trim($nodes->item(0)->textContent ?? '');
    }
    return null;
}

function getAttribute($xpath, $query, $attr) {
    $nodes = $xpath->query($query);
    if ($nodes && $nodes->length > 0) {
        return trim($nodes->item(0)->getAttribute($attr) ?? '');
    }
    return null;
}

function getMeta($xpath, $property) {
    $nodes = $xpath->query("//meta[@property='og:{$property}']/@content | //meta[@name='{$property}']/@content");
    if ($nodes && $nodes->length > 0) {
        return trim($nodes->item(0)->nodeValue ?? '');
    }
    return null;
}

function extractPrice($html) {
    if (preg_match('/\$[\s]*([\d,]+)/', $html, $matches)) {
        return (float) str_replace(',', '', $matches[1]);
    }
    if (preg_match('/USD[\s]*([\d,]+)/i', $html, $matches)) {
        return (float) str_replace(',', '', $matches[1]);
    }
    return null;
}

function extractDuration($html) {
    if (preg_match('/(\d+)\s*(?:day|days|Day|Days)/i', $html, $matches)) {
        return (int) $matches[1];
    }
    return null;
}

function importTour($tourData) {
    $slug = Str::slug($tourData['name']);
    
    // Skip if exists
    if (Tour::where('slug', $slug)->exists()) {
        return false;
    }
    
    // Get or create destination
    $destination = Destination::firstOrCreate(
        ['slug' => 'tanzania'],
        ['name' => 'Tanzania', 'slug' => 'tanzania']
    );
    
    // Download image
    $imagePath = null;
    if ($tourData['image_url']) {
        $imagePath = downloadImage($tourData['image_url']);
    }
    
    // Create tour
    Tour::create([
        'name' => $tourData['name'],
        'slug' => $slug,
        'destination_id' => $destination->id,
        'short_description' => $tourData['short_description'],
        'long_description' => $tourData['description'],
        'description' => $tourData['description'],
        'duration_days' => $tourData['duration_days'] ?? 1,
        'duration_nights' => ($tourData['duration_days'] ?? 1) - 1,
        'price' => $tourData['price'],
        'starting_price' => $tourData['price'],
        'image_url' => $imagePath,
        'tour_type' => 'Private',
        'publish_status' => 'Published',
        'status' => 'Active',
        'availability_status' => 'Available',
    ]);
    
    return true;
}

function downloadImage($url) {
    try {
        if (empty($url) || !filter_var($url, FILTER_VALIDATE_URL)) {
            return null;
        }
        
        $response = Http::timeout(30)->withOptions(['verify' => false])->get($url);
        if (!$response->successful()) {
            return null;
        }
        
        $extension = 'jpg';
        $path = parse_url($url, PHP_URL_PATH);
        if ($path) {
            $ext = pathinfo($path, PATHINFO_EXTENSION);
            if (in_array(strtolower($ext), ['jpg', 'jpeg', 'png', 'gif', 'webp'])) {
                $extension = $ext;
            }
        }
        
        $filename = 'tours/' . Str::random(40) . '.' . $extension;
        Storage::disk('public')->put($filename, $response->body());
        
        return $filename;
    } catch (Exception $e) {
        return null;
    }
}






