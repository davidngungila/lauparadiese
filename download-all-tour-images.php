<?php
/**
 * Download All Tour Images
 * Downloads high-quality images from Unsplash for all tours
 */

$toursDir = __DIR__ . '/public/images/tours/';

// Create directory if it doesn't exist
if (!is_dir($toursDir)) {
    mkdir($toursDir, 0755, true);
    echo "Created directory: $toursDir\n";
}

// Tour images mapping - using Unsplash URLs for free stock photos
$tourImages = [
    'safari-zanzibar-11.jpg' => 'https://images.unsplash.com/photo-1516426122078-c23e76319893?w=1200&h=800&fit=crop&q=80',
    'safari-zanzibar-15.jpg' => 'https://images.unsplash.com/photo-1507525421304-8d0e1c8e4f8e?w=1200&h=800&fit=crop&q=80',
    'migration-safari-9.jpg' => 'https://images.unsplash.com/photo-1516426122078-c23e76319893?w=1200&h=800&fit=crop&q=80',
    'natural-wonders-7.jpg' => 'https://images.unsplash.com/photo-1506905925346-21bda4d32df4?w=1200&h=800&fit=crop&q=80',
    'kilimanjaro-marangu.jpg' => 'https://images.unsplash.com/photo-1516026672322-bc52d61a55d5?w=1200&h=800&fit=crop&q=80',
    'kilimanjaro-machame.jpg' => 'https://images.unsplash.com/photo-1516026672322-bc52d61a55d5?w=1200&h=800&fit=crop&q=80',
    'zanzibar-10.jpg' => 'https://images.unsplash.com/photo-1507525421304-8d0e1c8e4f8e?w=1200&h=800&fit=crop&q=80',
    'southern-safari-10.jpg' => 'https://images.unsplash.com/photo-1506905925346-21bda4d32df4?w=1200&h=800&fit=crop&q=80',
];

echo "Starting image download...\n\n";

$downloaded = 0;
$failed = 0;

foreach ($tourImages as $filename => $url) {
    $filepath = $toursDir . $filename;
    
    // Skip if file already exists
    if (file_exists($filepath)) {
        echo "✓ Skipping $filename (already exists)\n";
        continue;
    }
    
    echo "Downloading $filename...\n";
    
    // Initialize cURL
    $ch = curl_init($url);
    $fp = fopen($filepath, 'wb');
    
    curl_setopt($ch, CURLOPT_FILE, $fp);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36');
    curl_setopt($ch, CURLOPT_TIMEOUT, 30);
    
    if (curl_exec($ch)) {
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        if ($httpCode === 200) {
            $fileSize = filesize($filepath);
            if ($fileSize > 0) {
                echo "  ✓ Successfully downloaded $filename (" . round($fileSize / 1024, 2) . " KB)\n";
                $downloaded++;
            } else {
                echo "  ✗ Failed: File is empty\n";
                unlink($filepath);
                $failed++;
            }
        } else {
            echo "  ✗ Failed: HTTP $httpCode\n";
            if (file_exists($filepath)) {
                unlink($filepath);
            }
            $failed++;
        }
    } else {
        echo "  ✗ Failed to download $filename\n";
        if (file_exists($filepath)) {
            unlink($filepath);
        }
        $failed++;
    }
    
    curl_close($ch);
    fclose($fp);
    
    // Small delay to avoid overwhelming the server
    usleep(500000); // 0.5 seconds
}

echo "\n========================================\n";
echo "Download Summary:\n";
echo "  Downloaded: $downloaded\n";
echo "  Failed: $failed\n";
echo "========================================\n";

if ($failed > 0) {
    echo "\nNote: Some images failed to download. You can:\n";
    echo "1. Run this script again to retry failed downloads\n";
    echo "2. Manually download images and place them in: $toursDir\n";
    echo "3. Use placeholder images from your hero-slider folder\n";
}





