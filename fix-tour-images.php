<?php
/**
 * Fix Tour Images - Update all tour image URLs in database
 */

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Tour;

echo "Fixing tour image URLs...\n\n";

$tours = Tour::all();
$fixed = 0;

foreach ($tours as $tour) {
    if ($tour->image_url) {
        // If image_url doesn't start with http or /, ensure it starts with images/
        if (!str_starts_with($tour->image_url, 'http') && !str_starts_with($tour->image_url, '/') && !str_starts_with($tour->image_url, 'images/')) {
            $tour->image_url = 'images/tours/' . basename($tour->image_url);
            $tour->save();
            echo "✓ Fixed: {$tour->name} -> {$tour->image_url}\n";
            $fixed++;
        } elseif (str_starts_with($tour->image_url, 'images/tours/')) {
            // Already correct format
            echo "✓ OK: {$tour->name} -> {$tour->image_url}\n";
        }
    } else {
        // Set default image based on tour type
        $defaultImage = 'images/hero-slider/safari-adventure.jpg';
        if (stripos($tour->name, 'kilimanjaro') !== false || stripos($tour->name, 'climbing') !== false) {
            $defaultImage = 'images/hero-slider/kilimanjaro-climbing.jpg';
        } elseif (stripos($tour->name, 'zanzibar') !== false || stripos($tour->name, 'beach') !== false) {
            $defaultImage = 'images/hero-slider/zanzibar-beach.jpg';
        }
        $tour->image_url = $defaultImage;
        $tour->save();
        echo "✓ Set default: {$tour->name} -> {$defaultImage}\n";
        $fixed++;
    }
}

echo "\n========================================\n";
echo "Fixed $fixed tour image URLs\n";
echo "========================================\n";





