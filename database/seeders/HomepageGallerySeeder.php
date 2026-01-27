<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Gallery;

class HomepageGallerySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('🌱 Seeding homepage gallery images...');
        $this->command->newLine();

        $images = [
            [
                'title' => 'Serengeti Wildlife',
                'description' => 'Witness the incredible wildlife of Serengeti National Park',
                'caption' => 'Serengeti National Park - Home to the Great Migration',
                'alt_text' => 'Serengeti Wildlife Safari',
                'image_url' => 'images/Serengetei-NP-2.jpeg',
                'category' => 'Tanzania in Pictures',
                'display_order' => 1,
                'priority' => 'high',
                'is_featured' => true,
                'is_active' => true,
            ],
            [
                'title' => 'Mount Kilimanjaro',
                'description' => 'Africa\'s highest peak - Mount Kilimanjaro',
                'caption' => 'Conquer the Roof of Africa',
                'alt_text' => 'Mount Kilimanjaro Climbing',
                'image_url' => 'images/hero-slider/kilimanjaro-climbing.jpg',
                'category' => 'Tanzania in Pictures',
                'display_order' => 2,
                'priority' => 'high',
                'is_featured' => true,
                'is_active' => true,
            ],
            [
                'title' => 'Zanzibar Beach',
                'description' => 'Pristine beaches of Zanzibar',
                'caption' => 'Paradise beaches await in Zanzibar',
                'alt_text' => 'Zanzibar Beach Paradise',
                'image_url' => 'images/zanzibar_home.jpg',
                'category' => 'Tanzania in Pictures',
                'display_order' => 3,
                'priority' => 'high',
                'is_featured' => true,
                'is_active' => true,
            ],
            [
                'title' => 'Tarangire National Park',
                'description' => 'Elephants and baobab trees in Tarangire',
                'caption' => 'Tarangire - Land of Giants',
                'alt_text' => 'Tarangire National Park',
                'image_url' => 'images/Tarangire-NP-1.jpeg',
                'category' => 'Tanzania in Pictures',
                'display_order' => 4,
                'priority' => 'medium',
                'is_featured' => true,
                'is_active' => true,
            ],
            [
                'title' => 'Mara River Crossing',
                'description' => 'The dramatic wildebeest migration at Mara River',
                'caption' => 'Witness the Great Migration',
                'alt_text' => 'Mara River Wildebeest Crossing',
                'image_url' => 'images/Mara-River-3-1536x1024.jpg',
                'category' => 'Tanzania in Pictures',
                'display_order' => 5,
                'priority' => 'high',
                'is_featured' => true,
                'is_active' => true,
            ],
            [
                'title' => 'Safari Adventure',
                'description' => 'Experience the ultimate safari adventure',
                'caption' => 'Your adventure begins here',
                'alt_text' => 'Tanzania Safari Adventure',
                'image_url' => 'images/safari_home-1.jpg',
                'category' => 'Tanzania in Pictures',
                'display_order' => 6,
                'priority' => 'medium',
                'is_featured' => true,
                'is_active' => true,
            ],
        ];

        $created = 0;
        foreach ($images as $imageData) {
            $gallery = Gallery::firstOrCreate(
                ['title' => $imageData['title'], 'category' => $imageData['category']],
                $imageData
            );
            
            if ($gallery->wasRecentlyCreated) {
                $created++;
                $this->command->line("   ✓ Created: {$imageData['title']}");
            } else {
                $this->command->line("   - Exists: {$imageData['title']}");
            }
        }

        $this->command->newLine();
        $this->command->info("✅ Successfully seeded {$created} new homepage gallery images (Total: " . Gallery::where('category', 'Tanzania in Pictures')->count() . ")");
        $this->command->newLine();
    }
}




