<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Tour;
use App\Models\Destination;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Http;

class ScrapeAltezzaToursBrowser extends Command
{
    protected $signature = 'scrape:altezza-browser 
                            {--limit= : Limit number of tours to scrape}
                            {--url= : Specific tour URL to scrape}';
    
    protected $description = 'Scrape tours from altezzatravel.com using browser automation (requires manual data extraction)';

    protected $baseUrl = 'https://altezzatravel.com';

    public function handle()
    {
        $this->info('Browser-based scraper for altezzatravel.com');
        $this->info('This command will help you extract tour data.');
        $this->info('');
        $this->info('Please visit: ' . $this->baseUrl . '/tours');
        $this->info('Then provide the tour data manually or use the web scraper.');
        $this->info('');
        
        // For now, let's try to get tour data from a common API endpoint or sitemap
        $this->trySitemap();
        
        return 0;
    }
    
    protected function trySitemap()
    {
        $sitemapUrls = [
            $this->baseUrl . '/sitemap.xml',
            $this->baseUrl . '/sitemap_index.xml',
            $this->baseUrl . '/sitemap-tours.xml',
        ];
        
        foreach ($sitemapUrls as $sitemapUrl) {
            try {
                $response = Http::timeout(10)->get($sitemapUrl);
                if ($response->successful()) {
                    $xml = $response->body();
                    $this->info("Found sitemap at: {$sitemapUrl}");
                    $this->parseSitemap($xml);
                    return;
                }
            } catch (\Exception $e) {
                // Continue
            }
        }
        
        $this->warn('No sitemap found. You may need to manually extract tour URLs.');
    }
    
    protected function parseSitemap($xml)
    {
        $dom = new \DOMDocument();
        @$dom->loadXML($xml);
        $xpath = new \DOMXPath($dom);
        $xpath->registerNamespace('sm', 'http://www.sitemaps.org/schemas/sitemap/0.9');
        
        $urls = $xpath->query('//sm:url/sm:loc');
        $tourUrls = [];
        
        foreach ($urls as $url) {
            $urlString = trim($url->textContent);
            if (strpos($urlString, '/tour') !== false || 
                strpos($urlString, '/safari') !== false ||
                strpos($urlString, '/package') !== false) {
                $tourUrls[] = $urlString;
            }
        }
        
        if (!empty($tourUrls)) {
            $this->info('Found ' . count($tourUrls) . ' tour URLs in sitemap');
            $limit = $this->option('limit') ? (int) $this->option('limit') : count($tourUrls);
            $tourUrls = array_slice($tourUrls, 0, $limit);
            
            $this->info('Scraping ' . count($tourUrls) . ' tours...');
            $this->scrapeToursFromUrls($tourUrls);
        }
    }
    
    protected function scrapeToursFromUrls($urls)
    {
        $bar = $this->output->createProgressBar(count($urls));
        $bar->start();
        
        foreach ($urls as $url) {
            try {
                $this->scrapeTour($url);
                $bar->advance();
            } catch (\Exception $e) {
                $this->newLine();
                $this->warn("Error scraping {$url}: " . $e->getMessage());
                $bar->advance();
            }
        }
        
        $bar->finish();
        $this->newLine();
    }
    
    protected function scrapeTour($url)
    {
        $response = Http::timeout(30)
            ->withHeaders([
                'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36',
            ])
            ->get($url);
        
        if (!$response->successful()) {
            return;
        }
        
        $html = $response->body();
        $tour = $this->parseTourHtml($html, $url);
        
        if ($tour && !empty($tour['name'])) {
            $this->importTour($tour);
        }
    }
    
    protected function parseTourHtml($html, $url)
    {
        $dom = new \DOMDocument();
        @$dom->loadHTML('<?xml encoding="UTF-8">' . $html);
        $xpath = new \DOMXPath($dom);
        
        // Extract title
        $name = $this->getText($xpath, "//h1") 
            ?: $this->getText($xpath, "//title");
        $name = preg_replace('/\s*[-|]\s*Altezza Travel.*$/i', '', $name ?? '');
        $name = trim($name ?: 'Untitled Tour');
        
        // Extract description
        $description = $this->getText($xpath, "//div[contains(@class, 'description')]")
            ?: $this->getText($xpath, "//div[contains(@class, 'content')]")
            ?: $this->getMeta($xpath, "description");
        
        // Extract price
        $price = $this->extractPriceFromHtml($html);
        
        // Extract duration
        $duration = $this->extractDurationFromHtml($html);
        
        // Extract image
        $image = $this->getMeta($xpath, "og:image")
            ?: $this->getAttribute($xpath, "//img[1]", "src");
        
        if ($image && strpos($image, 'http') !== 0) {
            $image = $this->baseUrl . '/' . ltrim($image, '/');
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
    
    protected function getText($xpath, $query)
    {
        $nodes = $xpath->query($query);
        if ($nodes->length > 0) {
            return trim($nodes->item(0)->textContent ?? '');
        }
        return null;
    }
    
    protected function getAttribute($xpath, $query, $attr)
    {
        $nodes = $xpath->query($query);
        if ($nodes->length > 0) {
            return trim($nodes->item(0)->getAttribute($attr) ?? '');
        }
        return null;
    }
    
    protected function getMeta($xpath, $property)
    {
        $nodes = $xpath->query("//meta[@property='og:{$property}']/@content | //meta[@name='{$property}']/@content");
        if ($nodes->length > 0) {
            return trim($nodes->item(0)->nodeValue ?? '');
        }
        return null;
    }
    
    protected function extractPriceFromHtml($html)
    {
        if (preg_match('/\$[\s]*([\d,]+)/', $html, $matches)) {
            return (float) str_replace(',', '', $matches[1]);
        }
        return null;
    }
    
    protected function extractDurationFromHtml($html)
    {
        if (preg_match('/(\d+)\s*(?:day|days|Day|Days)/i', $html, $matches)) {
            return (int) $matches[1];
        }
        return null;
    }
    
    protected function importTour($tourData)
    {
        $slug = Str::slug($tourData['name']);
        
        // Skip if exists
        if (Tour::where('slug', $slug)->exists()) {
            return;
        }
        
        // Get or create destination
        $destination = Destination::firstOrCreate(
            ['slug' => 'tanzania'],
            ['name' => 'Tanzania', 'slug' => 'tanzania']
        );
        
        // Download image
        $imagePath = null;
        if ($tourData['image_url']) {
            $imagePath = $this->downloadImage($tourData['image_url']);
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
        
        $this->info("Imported: {$tourData['name']}");
    }
    
    protected function downloadImage($url)
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
            $filename = 'tours/' . Str::random(40) . '.' . $extension;
            
            Storage::disk('public')->put($filename, $response->body());
            
            return $filename;
        } catch (\Exception $e) {
            return null;
        }
    }
    
    protected function getImageExtension($url, $contentType = null)
    {
        $path = parse_url($url, PHP_URL_PATH);
        if ($path) {
            $ext = pathinfo($path, PATHINFO_EXTENSION);
            if (in_array(strtolower($ext), ['jpg', 'jpeg', 'png', 'gif', 'webp'])) {
                return $ext;
            }
        }
        
        if ($contentType) {
            if (strpos($contentType, 'jpeg') !== false) return 'jpg';
            if (strpos($contentType, 'png') !== false) return 'png';
            if (strpos($contentType, 'gif') !== false) return 'gif';
            if (strpos($contentType, 'webp') !== false) return 'webp';
        }
        
        return 'jpg';
    }
}






