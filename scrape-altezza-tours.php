<?php

/**
 * Direct scraper for altezzatravel.com tours
 * This script extracts tour data and imports it into the database
 */

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Tour;
use App\Models\Destination;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;

$baseUrl = 'https://altezzatravel.com';

echo "Starting to scrape tours from altezzatravel.com...\n";

// Common tour URLs based on typical patterns
$commonTourUrls = [
    // Safari Tours
    'https://altezzatravel.com/tours/3-days-safari-tarangire-ngorongoro-serengeti',
    'https://altezzatravel.com/tours/4-days-safari-tarangire-serengeti-ngorongoro',
    'https://altezzatravel.com/tours/5-days-safari-tarangire-serengeti-ngorongoro',
    'https://altezzatravel.com/tours/6-days-safari-tarangire-serengeti-ngorongoro',
    'https://altezzatravel.com/tours/7-days-safari-tarangire-serengeti-ngorongoro',
    
    // Kilimanjaro Tours
    'https://altezzatravel.com/tours/kilimanjaro-machame-route-6-days',
    'https://altezzatravel.com/tours/kilimanjaro-machame-route-7-days',
    'https://altezzatravel.com/tours/kilimanjaro-marangu-route-5-days',
    'https://altezzatravel.com/tours/kilimanjaro-lemosho-route-8-days',
    
    // Zanzibar Tours
    'https://altezzatravel.com/tours/zanzibar-beach-holiday',
    'https://altezzatravel.com/tours/zanzibar-spice-tour',
    
    // Combined Tours
    'https://altezzatravel.com/tours/safari-zanzibar-combined',
    'https://altezzatravel.com/tours/kilimanjaro-safari-combined',
];

// Try to get tours from sitemap first
echo "Step 1: Checking sitemap...\n";
$sitemapUrls = [
    $baseUrl . '/sitemap.xml',
    $baseUrl . '/sitemap_index.xml',
];

$tourUrls = [];

foreach ($sitemapUrls as $sitemapUrl) {
    try {
        echo "  Trying: $sitemapUrl\n";
        $response = Http::timeout(10)->get($sitemapUrl);
        echo "  Status: " . $response->status() . "\n";
        if ($response->successful()) {
            $xml = $response->body();
            $dom = new DOMDocument();
            @$dom->loadXML($xml);
            $xpath = new DOMXPath($dom);
            $xpath->registerNamespace('sm', 'http://www.sitemaps.org/schemas/sitemap/0.9');
            
            $urls = $xpath->query('//sm:url/sm:loc');
            echo "  Found " . $urls->length . " URLs in sitemap\n";
            foreach ($urls as $url) {
                $urlString = trim($url->textContent);
                if (strpos($urlString, '/tour') !== false || 
                    strpos($urlString, '/safari') !== false ||
                    strpos($urlString, '/package') !== false ||
                    strpos($urlString, '/kilimanjaro') !== false ||
                    strpos($urlString, '/zanzibar') !== false) {
                    $tourUrls[] = $urlString;
                }
            }
            echo "  Found " . count($tourUrls) . " tour URLs in sitemap\n";
            if (count($tourUrls) > 0) {
                break;
            }
        }
    } catch (Exception $e) {
        echo "  Error: " . $e->getMessage() . "\n";
    }
}

// If no sitemap, try common URLs
if (empty($tourUrls)) {
    echo "No sitemap found, trying common tour URLs...\n";
    $tourUrls = $commonTourUrls;
}

// Limit if needed
$limit = isset($argv[1]) ? (int) $argv[1] : null;
if ($limit) {
    $tourUrls = array_slice($tourUrls, 0, $limit);
}

echo "Step 2: Scraping " . count($tourUrls) . " tours...\n";

$imported = 0;
$skipped = 0;

foreach ($tourUrls as $index => $url) {
    echo "\n[" . ($index + 1) . "/" . count($tourUrls) . "] Scraping: $url\n";
    
    try {
        $response = Http::timeout(30)
            ->withHeaders([
                'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36',
            ])
            ->get($url);
        
        if (!$response->successful()) {
            echo "  ⚠ Failed to fetch page (Status: {$response->status()})\n";
            continue;
        }
        
        $html = $response->body();
        $tour = parseTourPage($html, $url);
        
        if ($tour && !empty($tour['name']) && $tour['name'] !== 'Untitled Tour') {
            if (importTour($tour)) {
                $imported++;
                echo "  ✓ Imported: {$tour['name']}\n";
            } else {
                $skipped++;
                echo "  ⊘ Skipped (already exists): {$tour['name']}\n";
            }
        } else {
            echo "  ⚠ Could not extract tour data\n";
        }
        
    } catch (Exception $e) {
        echo "  ✗ Error: " . $e->getMessage() . "\n";
    }
}

echo "\n\nDone! Imported: $imported, Skipped: $skipped\n";

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
    if ($nodes->length > 0) {
        return trim($nodes->item(0)->textContent ?? '');
    }
    return null;
}

function getAttribute($xpath, $query, $attr) {
    $nodes = $xpath->query($query);
    if ($nodes->length > 0) {
        return trim($nodes->item(0)->getAttribute($attr) ?? '');
    }
    return null;
}

function getMeta($xpath, $property) {
    $nodes = $xpath->query("//meta[@property='og:{$property}']/@content | //meta[@name='{$property}']/@content");
    if ($nodes->length > 0) {
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
        
        $response = Http::timeout(30)->get($url);
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






