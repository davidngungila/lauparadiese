# Scrape Altezza Travel Tours - Implementation Guide

## Overview
This implementation includes tools to scrape and import all tour packages from https://altezzatravel.com into your tour management system.

## Files Created

### 1. `import-altezza-tours.php` (Main Import Script)
A standalone PHP script that:
- Fetches tour URLs from altezzatravel.com sitemap
- Scrapes individual tour pages to extract:
  - Tour name
  - Description
  - Price
  - Duration (days)
  - Images
- Downloads and saves images locally
- Imports tours into your database using the Tour model

### 2. `app/Console/Commands/ScrapeAltezzaTours.php` (Artisan Command)
A Laravel artisan command with similar functionality:
```bash
php artisan scrape:altezza-tours --limit=10
```

### 3. `app/Console/Commands/ScrapeAltezzaToursBrowser.php` (Browser-based Command)
Alternative command that uses sitemap parsing:
```bash
php artisan scrape:altezza-browser --limit=10
```

## Usage

### Method 1: Direct PHP Script (Recommended)
```bash
cd c:\laragon\www\tour\Tour
php import-altezza-tours.php [limit]
```

Examples:
```bash
# Import first 10 tours
php import-altezza-tours.php 10

# Import all tours (no limit)
php import-altezza-tours.php
```

### Method 2: Artisan Command
```bash
php artisan scrape:altezza-tours --limit=10
```

## What Gets Imported

For each tour, the script extracts and imports:
- **Name**: Tour title
- **Description**: Full tour description
- **Short Description**: First 500 characters
- **Price**: Extracted from page (if available)
- **Duration**: Number of days
- **Images**: Cover image and gallery images (downloaded locally)
- **Destination**: Automatically set to "Tanzania" (or creates it if doesn't exist)
- **Status**: Set to "Published" and "Active"

## Tour URLs Included

The script includes these known tour URLs from altezzatravel.com:
- Serengeti Great Migration, Ngorongoro & Tarangire Safari
- Lake Natron: Ol Doinyo Lengai Hike & Flamingo Walks
- Wild Trails of Tarangire – 3-Day Immersive Safari
- Migration & Calving Safari
- Luxury Migration Safari
- Serengeti Cheetah Safari
- Ngorongoro 2-Day Trip
- Lake Natron & Ngorongoro Safari
- Crater World: Ngorongoro, Empakaai and Crater Floor Safari

Plus it attempts to discover more tours from the sitemap.

## Features

1. **Automatic Sitemap Discovery**: Tries to find all tours from the sitemap
2. **Image Download**: Automatically downloads and saves tour images to `storage/app/public/tours/`
3. **Duplicate Prevention**: Skips tours that already exist (based on slug)
4. **Error Handling**: Continues processing even if individual tours fail
5. **Progress Logging**: Creates `import-log.txt` with detailed progress

## Output

The script will:
- Display progress in the console
- Create `import-log.txt` with detailed logs
- Show summary at the end:
  - Number of tours imported
  - Number skipped (already exist)
  - Number of errors

## Troubleshooting

### No tours imported?
1. Check `import-log.txt` for error messages
2. Verify database connection
3. Check if tours already exist (they won't be re-imported)
4. Verify network connectivity to altezzatravel.com

### Images not downloading?
1. Check `storage/app/public/tours/` directory exists and is writable
2. Verify disk permissions
3. Check network connectivity

### Script runs but no output?
- Check `import-log.txt` file
- Run with explicit error reporting: `php -d display_errors=1 import-altezza-tours.php`

## Database Structure

Tours are imported into the `tours` table with these fields:
- `name`: Tour name
- `slug`: Auto-generated from name
- `destination_id`: Links to destinations table
- `description`: Full description
- `short_description`: Abbreviated description
- `price`: Tour price
- `duration_days`: Number of days
- `image_url`: Path to cover image
- `gallery_images`: JSON array of gallery image paths
- `publish_status`: Set to "Published"
- `status`: Set to "Active"
- `availability_status`: Set to "Available"

## Next Steps

After importing:
1. Review imported tours in admin panel: `/admin/tours`
2. Edit tours to add:
   - Itineraries (day-by-day plans)
   - Pricing details
   - Availability dates
   - Categories
   - Additional destinations
3. Verify images are displaying correctly
4. Add any missing tour details manually

## Notes

- The script respects rate limits with a 0.5 second delay between requests
- Tours with duplicate slugs are automatically skipped
- Images are stored in `storage/app/public/tours/` directory
- The script creates a "Tanzania" destination if it doesn't exist






