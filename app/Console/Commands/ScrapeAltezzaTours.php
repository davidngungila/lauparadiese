<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Tour;
use App\Models\Destination;
use App\Models\TourCategory;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use DOMDocument;
use DOMXPath;

class ScrapeAltezzaTours extends Command
{
    protected $signature = 'scrape:altezza-tours 
                            {--limit= : Limit number of tours to scrape}
                            {--skip-images : Skip downloading images}';
    
    protected $description = 'Scrape all tour packages from altezzatravel.com and import them into the database';

    protected $baseUrl = 'https://altezzatravel.com';
    protected $scrapedTours = [];
    protected $tourLinks = [];

    public function handle()
    {
        $this->info('Starting to scrape tours from altezzatravel.com...');
        
        // Step 1: Get all tour links from the tours listing page
        $this->info('Step 1: Fetching tour links from listing page...');
        $this->fetchTourLinks();
        
        if (empty($this->tourLinks)) {
            $this->error('No tour links found!');
            return 1;
        }
        
        $this->info('Found ' . count($this->tourLinks) . ' tour links');
        
        // Step 2: Scrape each tour page
        $limit = $this->option('limit') ? (int) $this->option('limit') : null;
        $tourLinks = $limit ? array_slice($this->tourLinks, 0, $limit) : $this->tourLinks;
        
        $this->info('Step 2: Scraping individual tour pages...');
        $bar = $this->output->createProgressBar(count($tourLinks));
        $bar->start();
        
        foreach ($tourLinks as $index => $tourUrl) {
            try {
                $this->scrapeTourPage($tourUrl);
                $bar->advance();
            } catch (\Exception $e) {
                $this->newLine();
                $this->warn("Error scraping {$tourUrl}: " . $e->getMessage());
                $bar->advance();
            }
        }
        
        $bar->finish();
        $this->newLine();
        
        // Step 3: Import tours into database
        $this->info('Step 3: Importing tours into database...');
        $this->importTours();
        
        $this->info('Done! Imported ' . count($this->scrapedTours) . ' tours.');
        
        return 0;
    }

    protected function fetchTourLinks()
    {
        try {
            // Try multiple URLs
            $urls = [
                $this->baseUrl . '/tours',
                $this->baseUrl . '/safari-tours',
                $this->baseUrl . '/packages',
            ];
            
            $allLinks = [];
            
            foreach ($urls as $url) {
                try {
                    $response = Http::timeout(30)
                        ->withHeaders([
                            'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36',
                            'Accept' => 'text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8',
                        ])
                        ->get($url);
                    
                    if ($response->successful()) {
                        $html = $response->body();
                        $links = $this->extractLinksFromHtml($html);
                        $allLinks = array_merge($allLinks, $links);
                    }
                } catch (\Exception $e) {
                    // Continue to next URL
                }
            }
            
            // Also try to extract from HTML using regex as fallback
            if (empty($allLinks)) {
                foreach ($urls as $url) {
                    try {
                        $response = Http::timeout(30)->get($url);
                        if ($response->successful()) {
                            $html = $response->body();
                            // Extract links using regex
                            preg_match_all('/href=["\']([^"\']*(?:tour|safari|package)[^"\']*)["\']/i', $html, $matches);
                            if (!empty($matches[1])) {
                                foreach ($matches[1] as $href) {
                                    if (strpos($href, 'http') !== 0) {
                                        $href = $this->baseUrl . '/' . ltrim($href, '/');
                                    }
                                    if (strpos($href, $this->baseUrl) === 0 && !in_array($href, $allLinks)) {
                                        $allLinks[] = $href;
                                    }
                                }
                            }
                        }
                    } catch (\Exception $e) {
                        // Continue
                    }
                }
            }
            
            $this->tourLinks = array_unique($allLinks);
            
            // If still no links, try common tour URL patterns
            if (empty($this->tourLinks)) {
                $this->info('No links found via scraping, trying common URL patterns...');
                $this->tryCommonTourUrls();
            }
            
        } catch (\Exception $e) {
            $this->error('Error fetching tour links: ' . $e->getMessage());
        }
    }
    
    protected function extractLinksFromHtml($html)
    {
        $links = [];
        $dom = new DOMDocument();
        @$dom->loadHTML('<?xml encoding="UTF-8">' . $html);
        $xpath = new DOMXPath($dom);
        
        // Find all tour links - common patterns
        $patterns = [
            "//a[contains(@href, '/tour/')]",
            "//a[contains(@href, '/tours/')]",
            "//a[contains(@href, '/safari/')]",
            "//a[contains(@href, '/package/')]",
            "//a[contains(@class, 'tour')]",
            "//a[contains(@class, 'package')]",
            "//a[contains(@class, 'safari')]",
            "//article//a",
            "//div[contains(@class, 'tour-card')]//a",
            "//div[contains(@class, 'package-card')]//a",
        ];
        
        foreach ($patterns as $pattern) {
            try {
                $elements = $xpath->query($pattern);
                if ($elements) {
                    foreach ($elements as $element) {
                        $href = $element->getAttribute('href');
                        if ($href && !empty($href) && $href !== '#' && $href !== '/') {
                            // Convert relative URLs to absolute
                            if (strpos($href, 'http') !== 0) {
                                $href = $this->baseUrl . '/' . ltrim($href, '/');
                            }
                            if (strpos($href, $this->baseUrl) === 0 && !in_array($href, $links)) {
                                $links[] = $href;
                            }
                        }
                    }
                }
            } catch (\Exception $e) {
                // Continue with next pattern
            }
        }
        
        return $links;
    }
    
    protected function tryCommonTourUrls()
    {
        // Try common tour URL patterns
        $commonPatterns = [
            '/tours/',
            '/tour/',
            '/safari-tours/',
            '/packages/',
        ];
        
        $tours = [];
        // Try to find tours by checking common slugs
        $commonSlugs = [
            'kilimanjaro', 'serengeti', 'ngorongoro', 'tarangire', 'manyara',
            'zanzibar', 'safari', 'wildlife', 'adventure', 'beach',
        ];
        
        foreach ($commonSlugs as $slug) {
            foreach ($commonPatterns as $pattern) {
                $url = $this->baseUrl . $pattern . $slug;
                try {
                    $response = Http::timeout(10)->head($url);
                    if ($response->successful()) {
                        $tours[] = $url;
                    }
                } catch (\Exception $e) {
                    // Continue
                }
            }
        }
        
        $this->tourLinks = array_unique($tours);
    }

    protected function scrapeTourPage($url)
    {
        try {
            $response = Http::timeout(30)
                ->withHeaders([
                    'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36',
                    'Accept' => 'text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8',
                ])
                ->get($url);
            
            if (!$response->successful()) {
                return;
            }
            
            $html = $response->body();
            $dom = new DOMDocument();
            @$dom->loadHTML('<?xml encoding="UTF-8">' . $html);
            $xpath = new DOMXPath($dom);
            
            // Extract title from multiple sources
            $name = $this->extractText($xpath, "//h1") 
                ?: $this->extractText($xpath, "//h2[contains(@class, 'title')]")
                ?: $this->extractText($xpath, "//title")
                ?: $this->extractMeta($xpath, "og:title")
                ?: 'Untitled Tour';
            
            // Clean up title
            $name = preg_replace('/\s*[-|]\s*Altezza Travel.*$/i', '', $name);
            $name = trim($name);
            
            // Extract description
            $description = $this->extractText($xpath, "//div[contains(@class, 'description')]")
                ?: $this->extractText($xpath, "//div[contains(@class, 'content')]")
                ?: $this->extractText($xpath, "//article//p")
                ?: $this->extractMeta($xpath, "description");
            
            $shortDescription = $this->extractText($xpath, "//p[contains(@class, 'excerpt')]")
                ?: $this->extractText($xpath, "//p[contains(@class, 'summary')]")
                ?: $this->extractMeta($xpath, "description")
                ?: (strlen($description ?? '') > 200 ? substr($description, 0, 200) . '...' : $description);
            
            $tour = [
                'url' => $url,
                'name' => $name,
                'description' => $description,
                'short_description' => $shortDescription,
                'price' => $this->extractPrice($xpath, $html),
                'duration_days' => $this->extractDuration($xpath, $html),
                'image_url' => $this->extractImage($xpath, $html),
                'gallery_images' => $this->extractGalleryImages($xpath, $html),
                'highlights' => $this->extractHighlights($xpath),
                'inclusions' => $this->extractInclusions($xpath),
                'exclusions' => $this->extractExclusions($xpath),
                'itinerary' => $this->extractItinerary($xpath),
            ];
            
            $this->scrapedTours[] = $tour;
            
        } catch (\Exception $e) {
            // Silently continue
        }
    }
    
    protected function extractMeta($xpath, $property)
    {
        $nodes = $xpath->query("//meta[@property='og:{$property}']/@content | //meta[@name='{$property}']/@content");
        if ($nodes->length > 0) {
            return trim($nodes->item(0)->nodeValue ?? '');
        }
        return null;
    }

    protected function extractText($xpath, $query)
    {
        $nodes = $xpath->query($query);
        if ($nodes->length > 0) {
            $text = trim($nodes->item(0)->textContent ?? $nodes->item(0)->nodeValue ?? '');
            return $text ?: null;
        }
        return null;
    }

    protected function extractPrice($xpath, $html)
    {
        // Try multiple patterns for price
        $patterns = [
            "//span[contains(@class, 'price')]",
            "//div[contains(@class, 'price')]",
            "//*[contains(text(), '$')]",
            "//*[contains(text(), 'USD')]",
        ];
        
        foreach ($patterns as $pattern) {
            $nodes = $xpath->query($pattern);
            foreach ($nodes as $node) {
                $text = $node->textContent ?? $node->nodeValue ?? '';
                // Extract numbers from price text
                if (preg_match('/[\d,]+/', $text, $matches)) {
                    $price = str_replace(',', '', $matches[0]);
                    if (is_numeric($price) && $price > 100) { // Reasonable price threshold
                        return (float) $price;
                    }
                }
            }
        }
        
        // Try regex on HTML
        if (preg_match('/\$[\s]*([\d,]+)/', $html, $matches)) {
            $price = str_replace(',', '', $matches[1]);
            if (is_numeric($price) && $price > 100) {
                return (float) $price;
            }
        }
        
        return null;
    }

    protected function extractDuration($xpath, $html)
    {
        // Look for duration patterns like "5 days", "7 Days", etc.
        $patterns = [
            "//*[contains(text(), 'day')]",
            "//*[contains(text(), 'Day')]",
        ];
        
        foreach ($patterns as $pattern) {
            $nodes = $xpath->query($pattern);
            foreach ($nodes as $node) {
                $text = $node->textContent ?? $node->nodeValue ?? '';
                if (preg_match('/(\d+)\s*(?:day|days|Day|Days)/i', $text, $matches)) {
                    return (int) $matches[1];
                }
            }
        }
        
        // Try regex on HTML
        if (preg_match('/(\d+)\s*(?:day|days|Day|Days)/i', $html, $matches)) {
            return (int) $matches[1];
        }
        
        return null;
    }

    protected function extractImage($xpath, $html)
    {
        // Try multiple patterns
        $queries = [
            "//meta[@property='og:image']/@content",
            "//img[contains(@class, 'hero')]/@src",
            "//img[contains(@class, 'featured')]/@src",
            "//img[contains(@class, 'cover')]/@src",
            "//img[contains(@class, 'banner')]/@src",
            "//div[contains(@class, 'hero')]//img/@src",
            "//div[contains(@class, 'banner')]//img/@src",
            "//img[1]/@src", // First image as fallback
        ];
        
        foreach ($queries as $query) {
            $nodes = $xpath->query($query);
            if ($nodes->length > 0) {
                $src = trim($nodes->item(0)->nodeValue ?? '');
                if ($src && strpos($src, 'data:') !== 0) {
                    if (strpos($src, 'http') !== 0) {
                        $src = $this->baseUrl . '/' . ltrim($src, '/');
                    }
                    return $src;
                }
            }
        }
        
        // Try regex as fallback
        if (preg_match('/<meta\s+property=["\']og:image["\']\s+content=["\']([^"\']+)["\']/i', $html, $matches)) {
            $src = $matches[1];
            if (strpos($src, 'http') !== 0) {
                $src = $this->baseUrl . '/' . ltrim($src, '/');
            }
            return $src;
        }
        
        return null;
    }

    protected function extractGalleryImages($xpath, $html)
    {
        $images = [];
        
        // Try multiple patterns
        $queries = [
            "//div[contains(@class, 'gallery')]//img/@src",
            "//div[contains(@class, 'slider')]//img/@src",
            "//div[contains(@class, 'carousel')]//img/@src",
            "//img[contains(@class, 'gallery')]/@src",
        ];
        
        foreach ($queries as $query) {
            $nodes = $xpath->query($query);
            foreach ($nodes as $node) {
                $src = trim($node->nodeValue ?? '');
                if ($src && strpos($src, 'data:') !== 0 && !in_array($src, $images)) {
                    if (strpos($src, 'http') !== 0) {
                        $src = $this->baseUrl . '/' . ltrim($src, '/');
                    }
                    $images[] = $src;
                }
            }
        }
        
        return array_slice(array_unique($images), 0, 10); // Limit to 10 images
    }

    protected function extractHighlights($xpath)
    {
        $highlights = [];
        $patterns = [
            "//div[contains(@class, 'highlights')]//li",
            "//ul[contains(@class, 'highlights')]//li",
            "//div[contains(@class, 'features')]//li",
        ];
        
        foreach ($patterns as $pattern) {
            $nodes = $xpath->query($pattern);
            foreach ($nodes as $node) {
                $text = trim($node->textContent ?? $node->nodeValue ?? '');
                if ($text && strlen($text) > 10 && !in_array($text, $highlights)) {
                    $highlights[] = $text;
                }
            }
        }
        
        return array_slice($highlights, 0, 10); // Limit to 10 highlights
    }

    protected function extractInclusions($xpath)
    {
        $inclusions = [];
        $patterns = [
            "//div[contains(@class, 'inclusions')]//li",
            "//ul[contains(@class, 'inclusions')]//li",
            "//div[contains(@class, 'includes')]//li",
        ];
        
        foreach ($patterns as $pattern) {
            $nodes = $xpath->query($pattern);
            foreach ($nodes as $node) {
                $text = trim($node->textContent ?? $node->nodeValue ?? '');
                if ($text && !in_array($text, $inclusions)) {
                    $inclusions[] = $text;
                }
            }
        }
        
        return $inclusions;
    }

    protected function extractExclusions($xpath)
    {
        $exclusions = [];
        $patterns = [
            "//div[contains(@class, 'exclusions')]//li",
            "//ul[contains(@class, 'exclusions')]//li",
            "//div[contains(@class, 'excludes')]//li",
        ];
        
        foreach ($patterns as $pattern) {
            $nodes = $xpath->query($pattern);
            foreach ($nodes as $node) {
                $text = trim($node->textContent ?? $node->nodeValue ?? '');
                if ($text && !in_array($text, $exclusions)) {
                    $exclusions[] = $text;
                }
            }
        }
        
        return $exclusions;
    }

    protected function extractItinerary($xpath)
    {
        $itinerary = [];
        $nodes = $xpath->query("//div[contains(@class, 'itinerary')]//div[contains(@class, 'day')] | //div[contains(@class, 'day')]");
        
        foreach ($nodes as $node) {
            $day = [
                'title' => $this->extractText($xpath, ".//h3 | .//h4 | .//strong"),
                'description' => trim($node->textContent ?? $node->nodeValue ?? ''),
            ];
            if ($day['title'] || $day['description']) {
                $itinerary[] = $day;
            }
        }
        
        return $itinerary;
    }

    protected function importTours()
    {
        $bar = $this->output->createProgressBar(count($this->scrapedTours));
        $bar->start();
        
        foreach ($this->scrapedTours as $tourData) {
            try {
                // Skip if tour already exists
                $slug = Str::slug($tourData['name']);
                if (Tour::where('slug', $slug)->exists()) {
                    $bar->advance();
                    continue;
                }
                
                // Get or create a default destination
                $destination = Destination::firstOrCreate(
                    ['slug' => 'tanzania'],
                    ['name' => 'Tanzania', 'slug' => 'tanzania']
                );
                
                // Download images if not skipped
                $imageUrl = null;
                $galleryImages = [];
                
                if (!$this->option('skip-images')) {
                    if ($tourData['image_url']) {
                        $imageUrl = $this->downloadImage($tourData['image_url'], 'tours');
                    }
                    
                    if (!empty($tourData['gallery_images'])) {
                        foreach (array_slice($tourData['gallery_images'], 0, 5) as $galleryUrl) {
                            $downloaded = $this->downloadImage($galleryUrl, 'tours/gallery');
                            if ($downloaded) {
                                $galleryImages[] = $downloaded;
                            }
                        }
                    }
                } else {
                    // Just store URLs
                    $imageUrl = $tourData['image_url'];
                    $galleryImages = $tourData['gallery_images'] ?? [];
                }
                
                // Create tour
                $tour = Tour::create([
                    'name' => $tourData['name'],
                    'slug' => $slug,
                    'destination_id' => $destination->id,
                    'short_description' => $tourData['short_description'] ?? substr($tourData['description'] ?? '', 0, 500),
                    'long_description' => $tourData['description'],
                    'description' => $tourData['description'],
                    'duration_days' => $tourData['duration_days'] ?? 1,
                    'duration_nights' => ($tourData['duration_days'] ?? 1) - 1,
                    'price' => $tourData['price'],
                    'starting_price' => $tourData['price'],
                    'image_url' => $imageUrl,
                    'gallery_images' => $galleryImages,
                    'highlights' => $tourData['highlights'] ?? [],
                    'inclusions' => $tourData['inclusions'] ?? [],
                    'exclusions' => $tourData['exclusions'] ?? [],
                    'tour_type' => 'Private',
                    'publish_status' => 'Published',
                    'status' => 'Active',
                    'availability_status' => 'Available',
                ]);
                
                // Attach destination
                $tour->destinations()->attach($destination->id);
                
                $bar->advance();
                
            } catch (\Exception $e) {
                $this->newLine();
                $this->warn("Error importing tour '{$tourData['name']}': " . $e->getMessage());
                $bar->advance();
            }
        }
        
        $bar->finish();
        $this->newLine();
    }

    protected function downloadImage($url, $folder = 'tours')
    {
        try {
            if (empty($url) || !filter_var($url, FILTER_VALIDATE_URL)) {
                return null;
            }
            
            $response = Http::timeout(30)->get($url);
            
            if (!$response->successful()) {
                return null;
            }
            
            $extension = $this->getImageExtension($url, $response->header('Content-Type'));
            $filename = $folder . '/' . Str::random(40) . '.' . $extension;
            
            Storage::disk('public')->put($filename, $response->body());
            
            return $filename;
            
        } catch (\Exception $e) {
            return null;
        }
    }

    protected function getImageExtension($url, $contentType = null)
    {
        // Try to get extension from URL
        $path = parse_url($url, PHP_URL_PATH);
        if ($path) {
            $ext = pathinfo($path, PATHINFO_EXTENSION);
            if (in_array(strtolower($ext), ['jpg', 'jpeg', 'png', 'gif', 'webp'])) {
                return $ext;
            }
        }
        
        // Try to get from content type
        if ($contentType) {
            if (strpos($contentType, 'jpeg') !== false || strpos($contentType, 'jpg') !== false) {
                return 'jpg';
            }
            if (strpos($contentType, 'png') !== false) {
                return 'png';
            }
            if (strpos($contentType, 'gif') !== false) {
                return 'gif';
            }
            if (strpos($contentType, 'webp') !== false) {
                return 'webp';
            }
        }
        
        return 'jpg'; // Default
    }
}






