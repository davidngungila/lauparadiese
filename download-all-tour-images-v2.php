<?php
/**
 * Download All Tour Images - Version 2
 * Uses better image sources and copies existing images as fallbacks
 */

$toursDir = __DIR__ . '/public/images/tours/';
$heroDir = __DIR__ . '/public/images/hero-slider/';

// Create directory if it doesn't exist
if (!is_dir($toursDir)) {
    mkdir($toursDir, 0755, true);
    echo "Created directory: $toursDir\n";
}

// Tour images mapping - using direct Unsplash image IDs
$tourImages = [
    'safari-zanzibar-11.jpg' => [
        'url' => 'https://images.unsplash.com/photo-1507525421304-8d0e1c8e4f8e?w=1200&h=800&fit=crop',
        'fallback' => 'zanzibar-beach.jpg'
    ],
    'safari-zanzibar-15.jpg' => [
        'url' => 'https://images.unsplash.com/photo-1516426122078-c23e76319893?w=1200&h=800&fit=crop',
        'fallback' => 'safari-adventure.jpg'
    ],
    'migration-safari-9.jpg' => [
        'url' => 'https://images.unsplash.com/photo-1516426122078-c23e76319893?w=1200&h=800&fit=crop',
        'fallback' => 'safari-adventure.jpg'
    ],
    'natural-wonders-7.jpg' => [
        'url' => 'https://images.unsplash.com/photo-1506905925346-21bda4d32df4?w=1200&h=800&fit=crop',
        'fallback' => 'safari-adventure.jpg'
    ],
    'kilimanjaro-marangu.jpg' => [
        'url' => 'https://images.unsplash.com/photo-1516026672322-bc52d61a55d5?w=1200&h=800&fit=crop',
        'fallback' => 'kilimanjaro-climbing.jpg'
    ],
    'kilimanjaro-machame.jpg' => [
        'url' => 'https://images.unsplash.com/photo-1516026672322-bc52d61a55d5?w=1200&h=800&fit=crop',
        'fallback' => 'kilimanjaro-climbing.jpg'
    ],
    'zanzibar-10.jpg' => [
        'url' => 'https://images.unsplash.com/photo-1507525421304-8d0e1c8e4f8e?w=1200&h=800&fit=crop',
        'fallback' => 'zanzibar-beach.jpg'
    ],
    'southern-safari-10.jpg' => [
        'url' => 'https://images.unsplash.com/photo-1506905925346-21bda4d32df4?w=1200&h=800&fit=crop',
        'fallback' => 'safari-adventure.jpg'
    ],
];

echo "Starting image download...\n\n";

$downloaded = 0;
$copied = 0;
$failed = 0;

foreach ($tourImages as $filename => $config) {
    $filepath = $toursDir . $filename;
    
    // Skip if file already exists
    if (file_exists($filepath) && filesize($filepath) > 0) {
        echo "✓ Skipping $filename (already exists)\n";
        continue;
    }
    
    echo "Downloading $filename...\n";
    
    // Try to download from URL
    $ch = curl_init($config['url']);
    $fp = fopen($filepath, 'wb');
    
    curl_setopt($ch, CURLOPT_FILE, $fp);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36');
    curl_setopt($ch, CURLOPT_TIMEOUT, 30);
    curl_setopt($ch, CURLOPT_REFERER, 'https://unsplash.com/');
    
    $success = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    fclose($fp);
    
    if ($success && $httpCode === 200 && file_exists($filepath) && filesize($filepath) > 1000) {
        $fileSize = filesize($filepath);
        echo "  ✓ Successfully downloaded $filename (" . round($fileSize / 1024, 2) . " KB)\n";
        $downloaded++;
    } else {
        // Try fallback - copy from hero-slider
        if (file_exists($filepath)) {
            unlink($filepath);
        }
        
        $fallbackPath = $heroDir . $config['fallback'];
        if (file_exists($fallbackPath)) {
            if (copy($fallbackPath, $filepath)) {
                echo "  ✓ Copied fallback image: {$config['fallback']}\n";
                $copied++;
            } else {
                echo "  ✗ Failed to copy fallback\n";
                $failed++;
            }
        } else {
            echo "  ✗ Failed to download and fallback not found\n";
            $failed++;
        }
    }
    
    // Small delay
    usleep(300000); // 0.3 seconds
}

echo "\n========================================\n";
echo "Download Summary:\n";
echo "  Downloaded: $downloaded\n";
echo "  Copied (fallback): $copied\n";
echo "  Failed: $failed\n";
echo "========================================\n";

if ($failed === 0) {
    echo "\n✓ All images are now available!\n";
} else {
    echo "\nNote: Some images failed. Check the files manually.\n";
}





