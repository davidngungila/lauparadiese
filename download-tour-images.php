<?php
/**
 * Image Download Helper Script
 * 
 * This script helps download tour images from Tanzania Specialist website
 * and save them locally for Lau Paradise Adventures
 * 
 * Usage: php download-tour-images.php
 */

$imageUrls = [
    // Safari + Zanzibar
    'safari-zanzibar-11.jpg' => 'https://tanzania-specialist.com/images/tours/safari-zanzibar-11.jpg',
    'safari-zanzibar-15.jpg' => 'https://tanzania-specialist.com/images/tours/safari-zanzibar-15.jpg',
    
    // Safari
    'migration-safari-9.jpg' => 'https://tanzania-specialist.com/images/tours/migration-safari-9.jpg',
    'natural-wonders-7.jpg' => 'https://tanzania-specialist.com/images/tours/natural-wonders-7.jpg',
    'southern-safari-10.jpg' => 'https://tanzania-specialist.com/images/tours/southern-safari-10.jpg',
    
    // Kilimanjaro
    'kilimanjaro-marangu.jpg' => 'https://tanzania-specialist.com/images/tours/kilimanjaro-marangu.jpg',
    'kilimanjaro-machame.jpg' => 'https://tanzania-specialist.com/images/tours/kilimanjaro-machame.jpg',
    
    // Zanzibar
    'zanzibar-10.jpg' => 'https://tanzania-specialist.com/images/tours/zanzibar-10.jpg',
];

$baseDir = __DIR__ . '/public/images/tours/';

// Create directory if it doesn't exist
if (!is_dir($baseDir)) {
    mkdir($baseDir, 0755, true);
    echo "Created directory: $baseDir\n";
}

echo "Starting image download...\n\n";

foreach ($imageUrls as $filename => $url) {
    $filepath = $baseDir . $filename;
    
    // Skip if file already exists
    if (file_exists($filepath)) {
        echo "Skipping $filename (already exists)\n";
        continue;
    }
    
    echo "Downloading $filename...\n";
    
    // Try to download the image
    $ch = curl_init($url);
    $fp = fopen($filepath, 'wb');
    curl_setopt($ch, CURLOPT_FILE, $fp);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36');
    
    if (curl_exec($ch)) {
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        if ($httpCode === 200) {
            echo "  ✓ Successfully downloaded $filename\n";
        } else {
            echo "  ✗ Failed: HTTP $httpCode\n";
            unlink($filepath);
        }
    } else {
        echo "  ✗ Failed to download $filename\n";
        if (file_exists($filepath)) {
            unlink($filepath);
        }
    }
    
    curl_close($ch);
    fclose($fp);
    
    // Small delay to avoid overwhelming the server
    usleep(500000); // 0.5 seconds
}

echo "\nDownload complete!\n";
echo "\nNote: If some images failed to download, you can:\n";
echo "1. Use placeholder images from unsplash.com\n";
echo "2. Manually download images and place them in: $baseDir\n";
echo "3. Use existing images from your hero-slider folder\n";





