<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\TourCategory;
use Illuminate\Support\Str;

class TourCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Safari Tours',
                'slug' => 'safari-tours',
                'description' => 'Experience the thrill of wildlife safaris in Tanzania\'s most iconic national parks. Witness the Big Five, Great Migration, and incredible wildlife encounters.',
                'image_url' => 'images/safari_home-1.jpg',
                'is_active' => true,
                'sort_order' => 1,
            ],
            [
                'name' => 'Kilimanjaro Climbing',
                'slug' => 'kilimanjaro-climbing',
                'description' => 'Conquer Africa\'s highest peak. Choose from various routes including Marangu, Machame, Lemosho, and more. Professional guides and comprehensive support.',
                'image_url' => 'images/hero-slider/kilimanjaro-climbing.jpg',
                'is_active' => true,
                'sort_order' => 2,
            ],
            [
                'name' => 'Beach Holidays',
                'slug' => 'beach-holidays',
                'description' => 'Relax on pristine Zanzibar beaches. Enjoy crystal-clear waters, white sand beaches, water sports, and tropical paradise experiences.',
                'image_url' => 'images/categories/beach-holidays.jpg',
                'is_active' => true,
                'sort_order' => 3,
            ],
            [
                'name' => 'Safari & Beach',
                'slug' => 'safari-beach',
                'description' => 'Combine the best of both worlds: incredible wildlife safaris and relaxing beach holidays. Perfect for travelers who want adventure and relaxation.',
                'image_url' => 'images/11-Days-Safari-trip-Tanzania-Zanzibar-1536x1024.jpg',
                'is_active' => true,
                'sort_order' => 4,
            ],
            [
                'name' => 'Luxury Safaris',
                'slug' => 'luxury-safaris',
                'description' => 'Premium safari experiences with luxury accommodations, private guides, exclusive experiences, and world-class service in Tanzania\'s finest locations.',
                'image_url' => 'images/safari_home-1.jpg',
                'is_active' => true,
                'sort_order' => 5,
            ],
            [
                'name' => 'Family Tours',
                'slug' => 'family-tours',
                'description' => 'Family-friendly tours designed for travelers with children. Educational activities, shorter game drives, and accommodations perfect for families.',
                'image_url' => 'images/safari_home-1.jpg',
                'is_active' => true,
                'sort_order' => 6,
            ],
            [
                'name' => 'Honeymoon Packages',
                'slug' => 'honeymoon-packages',
                'description' => 'Romantic honeymoon experiences combining intimate wildlife encounters with luxury beach escapes. Perfect for couples celebrating their love.',
                'image_url' => 'images/zanzibar_home.jpg',
                'is_active' => true,
                'sort_order' => 7,
            ],
            [
                'name' => 'Photography Safaris',
                'slug' => 'photography-safaris',
                'description' => 'Specialized safaris for photography enthusiasts. Expert guides, optimal lighting times, and prime locations for capturing stunning wildlife images.',
                'image_url' => 'images/Serengetei-NP-2.jpeg',
                'is_active' => true,
                'sort_order' => 8,
            ],
            [
                'name' => 'Great Migration',
                'slug' => 'great-migration',
                'description' => 'Witness one of nature\'s greatest spectacles: the annual Great Migration. Watch thousands of wildebeest and zebra cross rivers and plains.',
                'image_url' => 'images/Mara-River-3-1536x1024.jpg',
                'is_active' => true,
                'sort_order' => 9,
            ],
            [
                'name' => 'Northern Circuit',
                'slug' => 'northern-circuit',
                'description' => 'Explore Tanzania\'s famous northern circuit: Serengeti, Ngorongoro, Tarangire, and Lake Manyara. The ultimate safari experience.',
                'image_url' => 'images/10-Days-Tanzania-safari-all-northern-parks-in-1-trip-wildebeest-migration-1536x1026.webp',
                'is_active' => true,
                'sort_order' => 10,
            ],
            [
                'name' => 'Southern Circuit',
                'slug' => 'southern-circuit',
                'description' => 'Discover Tanzania\'s less-crowded southern circuit. Selous, Ruaha, and Mikumi offer authentic wilderness experiences away from the crowds.',
                'image_url' => 'images/categories/southern-circuit.jpg',
                'is_active' => true,
                'sort_order' => 11,
            ],
            [
                'name' => 'Adventure Tours',
                'slug' => 'adventure-tours',
                'description' => 'Thrilling adventure experiences including hiking, trekking, cultural tours, and active exploration of Tanzania\'s diverse landscapes.',
                'image_url' => 'images/hero-slider/safari-adventure.jpg',
                'is_active' => true,
                'sort_order' => 12,
            ],
            [
                'name' => 'Cultural Tours',
                'slug' => 'cultural-tours',
                'description' => 'Immerse yourself in Tanzania\'s rich culture. Visit local communities, learn about traditions, and experience authentic Swahili culture.',
                'image_url' => 'images/zanzibar_home.jpg',
                'is_active' => true,
                'sort_order' => 13,
            ],
            [
                'name' => 'Bird Watching',
                'slug' => 'bird-watching',
                'description' => 'Tanzania is a birdwatcher\'s paradise with over 1,000 species. Specialized tours for bird enthusiasts in prime birding locations.',
                'image_url' => 'images/hero-slider/animal-movement.jpg',
                'is_active' => true,
                'sort_order' => 14,
            ],
            [
                'name' => 'Short Safaris',
                'slug' => 'short-safaris',
                'description' => 'Perfect for travelers with limited time. 3-5 day safaris covering key destinations and wildlife viewing opportunities.',
                'image_url' => 'images/categories/short-safaris.jpg',
                'is_active' => true,
                'sort_order' => 15,
            ],
            [
                'name' => 'Extended Safaris',
                'slug' => 'extended-safaris',
                'description' => 'Comprehensive safaris of 10+ days allowing you to fully explore multiple parks and destinations at a relaxed pace.',
                'image_url' => 'images/categories/extended-safaris.jpg',
                'is_active' => true,
                'sort_order' => 16,
            ],
            [
                'name' => 'Private Tours',
                'slug' => 'private-tours',
                'description' => 'Exclusive private tours with dedicated guides and vehicles. Customize your itinerary and travel at your own pace.',
                'image_url' => 'images/categories/private-tours.jpg',
                'is_active' => true,
                'sort_order' => 17,
            ],
            [
                'name' => 'Group Tours',
                'slug' => 'group-tours',
                'description' => 'Join small group tours and meet like-minded travelers. Cost-effective options with shared guides and vehicles.',
                'image_url' => 'images/categories/group-tours.jpg',
                'is_active' => true,
                'sort_order' => 18,
            ],
            [
                'name' => 'Budget Safaris',
                'slug' => 'budget-safaris',
                'description' => 'Affordable safari options without compromising on wildlife viewing. Comfortable camping and mid-range accommodations.',
                'image_url' => 'images/categories/budget-safaris.jpg',
                'is_active' => true,
                'sort_order' => 19,
            ],
            [
                'name' => 'Zanzibar Tours',
                'slug' => 'zanzibar-tours',
                'description' => 'Explore the Spice Island. Stone Town tours, spice plantations, pristine beaches, water activities, and Swahili culture.',
                'image_url' => 'images/categories/zanzibar-tours.jpg',
                'is_active' => true,
                'sort_order' => 20,
            ],
        ];

        foreach ($categories as $categoryData) {
            TourCategory::updateOrCreate(
                ['slug' => $categoryData['slug']],
                $categoryData
            );
        }

        $this->command->info('Successfully seeded ' . count($categories) . ' tour categories!');
    }
}






