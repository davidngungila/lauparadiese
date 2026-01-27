<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\HeroSlide;
use App\Models\Gallery;

class HeroSlideSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('🌱 Seeding hero slides...');
        $this->command->newLine();

        // Try to find images from gallery first
        $safariImage = Gallery::where('category', 'Homepage Slider')
            ->orWhere('title', 'like', '%safari%')
            ->orWhere('title', 'like', '%serengeti%')
            ->orWhere('title', 'like', '%wildlife%')
            ->first();
        
        $kilimanjaroImage = Gallery::where('category', 'Homepage Slider')
            ->orWhere('title', 'like', '%kilimanjaro%')
            ->orWhere('title', 'like', '%mountain%')
            ->orWhere('title', 'like', '%climb%')
            ->first();
        
        $zanzibarImage = Gallery::where('category', 'Homepage Slider')
            ->orWhere('title', 'like', '%zanzibar%')
            ->orWhere('title', 'like', '%beach%')
            ->orWhere('title', 'like', '%island%')
            ->first();

        $ngorongoroImage = Gallery::where('category', 'Homepage Slider')
            ->orWhere('title', 'like', '%ngorongoro%')
            ->orWhere('title', 'like', '%crater%')
            ->first();

        $slides = [
            [
                'title' => 'Discover Tanzania\'s Wildlife Wonders',
                'subtitle' => 'Experience the magic of Serengeti, Ngorongoro, and Tarangire. Witness the Great Migration and the Big Five in their natural habitat with expert local guides.',
                'badge_text' => 'Best Seller',
                'badge_icon' => 'ri-star-fill',
                'image_id' => $safariImage->id ?? null,
                'image_url' => $safariImage ? null : 'images/hero-slider/serengeti-safari.jpg',
                'primary_button_text' => 'Explore Safaris',
                'primary_button_link' => '/tours?category=safari',
                'primary_button_icon' => 'ri-compass-3-line',
                'secondary_button_text' => 'Book Now',
                'secondary_button_link' => '/book-now',
                'secondary_button_icon' => 'ri-calendar-check-line',
                'display_order' => 1,
                'animation_type' => 'fade-in-up',
                'overlay_type' => 'gradient',
                'is_active' => true,
            ],
            [
                'title' => 'Conquer Mount Kilimanjaro',
                'subtitle' => 'Reach the Roof of Africa with Lau Paradise Adventures. Expert guides, premium equipment, and unforgettable summit experiences await you.',
                'badge_text' => 'Adventure',
                'badge_icon' => 'ri-mountain-line',
                'image_id' => $kilimanjaroImage->id ?? null,
                'image_url' => $kilimanjaroImage ? null : 'images/hero-slider/kilimanjaro-climbing.jpg',
                'primary_button_text' => 'View Expeditions',
                'primary_button_link' => '/tours?category=kilimanjaro',
                'primary_button_icon' => 'ri-mountain-line',
                'secondary_button_text' => 'Get Quote',
                'secondary_button_link' => '/contact',
                'secondary_button_icon' => 'ri-phone-line',
                'display_order' => 2,
                'animation_type' => 'slide-left',
                'overlay_type' => 'gradient',
                'is_active' => true,
            ],
            [
                'title' => 'Zanzibar Paradise Awaits',
                'subtitle' => 'Relax on pristine beaches, explore Stone Town, and dive into turquoise waters. Your perfect tropical escape in Tanzania\'s spice island.',
                'badge_text' => 'Relaxation',
                'badge_icon' => 'ri-sun-line',
                'image_id' => $zanzibarImage->id ?? null,
                'image_url' => $zanzibarImage ? null : 'images/hero-slider/zanzibar-beach.jpg',
                'primary_button_text' => 'Beach Tours',
                'primary_button_link' => '/tours?destination=zanzibar',
                'primary_button_icon' => 'ri-sun-line',
                'secondary_button_text' => 'Book Holiday',
                'secondary_button_link' => '/book-now',
                'secondary_button_icon' => 'ri-calendar-line',
                'display_order' => 3,
                'animation_type' => 'fade-in-up',
                'overlay_type' => 'gradient',
                'is_active' => true,
            ],
            [
                'title' => 'Ngorongoro Crater Experience',
                'subtitle' => 'Descend into the world\'s largest inactive volcanic crater. Home to over 25,000 animals including the rare black rhino and the Big Five.',
                'badge_text' => 'Premium',
                'badge_icon' => 'ri-award-line',
                'image_id' => $ngorongoroImage->id ?? null,
                'image_url' => $ngorongoroImage ? null : 'images/hero-slider/ngorongoro-crater.jpg',
                'primary_button_text' => 'View Packages',
                'primary_button_link' => '/tours?destination=ngorongoro',
                'primary_button_icon' => 'ri-map-pin-line',
                'secondary_button_text' => 'Learn More',
                'secondary_button_link' => '/destinations/ngorongoro',
                'secondary_button_icon' => 'ri-information-line',
                'display_order' => 4,
                'animation_type' => 'zoom-in',
                'overlay_type' => 'dark',
                'is_active' => true,
            ],
            [
                'title' => 'Cultural Tours & Authentic Experiences',
                'subtitle' => 'Immerse yourself in Tanzania\'s rich culture. Visit Maasai villages, learn traditional crafts, and experience authentic local life.',
                'badge_text' => 'Cultural',
                'badge_icon' => 'ri-community-line',
                'image_id' => null,
                'image_url' => 'images/hero-slider/cultural-tour.jpg',
                'primary_button_text' => 'Cultural Tours',
                'primary_button_link' => '/tours?type=cultural',
                'primary_button_icon' => 'ri-group-line',
                'secondary_button_text' => 'Contact Us',
                'secondary_button_link' => '/contact',
                'secondary_button_icon' => 'ri-mail-line',
                'display_order' => 5,
                'animation_type' => 'slide-right',
                'overlay_type' => 'light',
                'is_active' => true,
            ],
            [
                'title' => 'Witness the Great Migration',
                'subtitle' => 'Experience one of nature\'s greatest spectacles as millions of wildebeest and zebras cross the Serengeti plains in search of fresh grazing.',
                'badge_text' => 'Seasonal',
                'badge_icon' => 'ri-calendar-event-line',
                'image_id' => null,
                'image_url' => 'images/hero-slider/animal-movement.jpg',
                'primary_button_text' => 'Migration Tours',
                'primary_button_link' => '/tours?feature=migration',
                'primary_button_icon' => 'ri-roadmap-line',
                'secondary_button_text' => 'Best Time to Visit',
                'secondary_button_link' => '/about/migration',
                'secondary_button_icon' => 'ri-calendar-line',
                'display_order' => 6,
                'animation_type' => 'fade-in-up',
                'overlay_type' => 'gradient',
                'is_active' => true,
            ],
            [
                'title' => 'Tarangire National Park Safari',
                'subtitle' => 'Home to Africa\'s largest elephant herds and ancient baobab trees. Experience incredible wildlife viewing in this hidden gem of Tanzania.',
                'badge_text' => 'Wildlife',
                'badge_icon' => 'ri-lion-line',
                'image_id' => null,
                'image_url' => 'images/hero-slider/safari-adventure.jpg',
                'primary_button_text' => 'Tarangire Tours',
                'primary_button_link' => '/tours?destination=tarangire',
                'primary_button_icon' => 'ri-map-pin-line',
                'secondary_button_text' => 'View Packages',
                'secondary_button_link' => '/tours',
                'secondary_button_icon' => 'ri-price-tag-3-line',
                'display_order' => 7,
                'animation_type' => 'slide-left',
                'overlay_type' => 'gradient',
                'is_active' => true,
            ],
            [
                'title' => 'Hot Air Balloon Safari',
                'subtitle' => 'Soar above the Serengeti at sunrise for a breathtaking aerial view of the wildlife below. An unforgettable experience you\'ll treasure forever.',
                'badge_text' => 'Premium',
                'badge_icon' => 'ri-flight-takeoff-line',
                'image_id' => null,
                'image_url' => 'images/hero-slider/morning.jpg',
                'primary_button_text' => 'Book Balloon Safari',
                'primary_button_link' => '/tours?activity=balloon',
                'primary_button_icon' => 'ri-flight-takeoff-line',
                'secondary_button_text' => 'Learn More',
                'secondary_button_link' => '/experiences/balloon-safari',
                'secondary_button_icon' => 'ri-information-line',
                'display_order' => 8,
                'animation_type' => 'zoom-in',
                'overlay_type' => 'dark',
                'is_active' => true,
            ],
            [
                'title' => 'Lake Manyara Bird Paradise',
                'subtitle' => 'Discover over 400 bird species including flamingos, pelicans, and storks. A birdwatcher\'s paradise set against the stunning Rift Valley escarpment.',
                'badge_text' => 'Bird Watching',
                'badge_icon' => 'ri-bird-line',
                'image_id' => null,
                'image_url' => 'images/hero-slider/group-of-animals.jpg',
                'primary_button_text' => 'Birding Tours',
                'primary_button_link' => '/tours?activity=birding',
                'primary_button_icon' => 'ri-eye-line',
                'secondary_button_text' => 'Species Guide',
                'secondary_button_link' => '/wildlife/birds',
                'secondary_button_icon' => 'ri-book-open-line',
                'display_order' => 9,
                'animation_type' => 'fade-in-up',
                'overlay_type' => 'gradient',
                'is_active' => true,
            ],
            [
                'title' => 'Luxury Safari Experience',
                'subtitle' => 'Indulge in the ultimate safari adventure with premium lodges, private guides, and exclusive wildlife encounters. Luxury meets adventure.',
                'badge_text' => 'Luxury',
                'badge_icon' => 'ri-hotel-line',
                'image_id' => null,
                'image_url' => 'images/hero-slider/serengeti-safari.jpg',
                'primary_button_text' => 'Luxury Packages',
                'primary_button_link' => '/tours?category=luxury',
                'primary_button_icon' => 'ri-star-line',
                'secondary_button_text' => 'View Lodges',
                'secondary_button_link' => '/accommodation',
                'secondary_button_icon' => 'ri-building-line',
                'display_order' => 10,
                'animation_type' => 'slide-right',
                'overlay_type' => 'dark',
                'is_active' => true,
            ],
            [
                'title' => 'Honeymoon in Paradise',
                'subtitle' => 'Celebrate your love in Tanzania\'s most romantic settings. From beachfront resorts in Zanzibar to intimate safari camps under the stars.',
                'badge_text' => 'Romance',
                'badge_icon' => 'ri-heart-line',
                'image_id' => null,
                'image_url' => 'images/hero-slider/zanzibar-beach.jpg',
                'primary_button_text' => 'Honeymoon Packages',
                'primary_button_link' => '/tours?type=honeymoon',
                'primary_button_icon' => 'ri-heart-3-line',
                'secondary_button_text' => 'Romantic Getaways',
                'secondary_button_link' => '/experiences/honeymoon',
                'secondary_button_icon' => 'ri-moon-line',
                'display_order' => 11,
                'animation_type' => 'fade-in-up',
                'overlay_type' => 'gradient',
                'is_active' => true,
            ],
            [
                'title' => 'Family Safari Adventure',
                'subtitle' => 'Create lasting memories with your family. Kid-friendly safaris, educational experiences, and safe wildlife encounters for all ages.',
                'badge_text' => 'Family Friendly',
                'badge_icon' => 'ri-group-line',
                'image_id' => null,
                'image_url' => 'images/hero-slider/animal-movement.jpg',
                'primary_button_text' => 'Family Tours',
                'primary_button_link' => '/tours?type=family',
                'primary_button_icon' => 'ri-parent-line',
                'secondary_button_text' => 'Kids Activities',
                'secondary_button_link' => '/experiences/family',
                'secondary_button_icon' => 'ri-gamepad-line',
                'display_order' => 12,
                'animation_type' => 'slide-left',
                'overlay_type' => 'light',
                'is_active' => true,
            ],
            [
                'title' => 'Photography Safari',
                'subtitle' => 'Capture stunning wildlife moments with expert photography guides. Perfect lighting, prime locations, and unforgettable shots await.',
                'badge_text' => 'Photography',
                'badge_icon' => 'ri-camera-line',
                'image_id' => null,
                'image_url' => 'images/hero-slider/safari-adventure.jpg',
                'primary_button_text' => 'Photo Tours',
                'primary_button_link' => '/tours?activity=photography',
                'primary_button_icon' => 'ri-camera-3-line',
                'secondary_button_text' => 'Gallery',
                'secondary_button_link' => '/gallery',
                'secondary_button_icon' => 'ri-image-line',
                'display_order' => 13,
                'animation_type' => 'zoom-in',
                'overlay_type' => 'gradient',
                'is_active' => true,
            ],
            [
                'title' => 'Walking Safari Experience',
                'subtitle' => 'Get up close with nature on foot. Guided walking safaris offer an intimate connection with the African wilderness and its inhabitants.',
                'badge_text' => 'Adventure',
                'badge_icon' => 'ri-walk-line',
                'image_id' => null,
                'image_url' => 'images/hero-slider/kilimanjaro-climbing.jpg',
                'primary_button_text' => 'Walking Safaris',
                'primary_button_link' => '/tours?activity=walking',
                'primary_button_icon' => 'ri-footprint-line',
                'secondary_button_text' => 'Safety Info',
                'secondary_button_link' => '/about/walking-safaris',
                'secondary_button_icon' => 'ri-shield-check-line',
                'display_order' => 14,
                'animation_type' => 'fade-in-up',
                'overlay_type' => 'dark',
                'is_active' => true,
            ],
            [
                'title' => 'Selous Game Reserve',
                'subtitle' => 'Explore Africa\'s largest game reserve. Boat safaris, walking tours, and incredible wildlife diversity in an untouched wilderness.',
                'badge_text' => 'Remote',
                'badge_icon' => 'ri-map-pin-range-line',
                'image_id' => null,
                'image_url' => 'images/hero-slider/group-of-animals.jpg',
                'primary_button_text' => 'Selous Tours',
                'primary_button_link' => '/tours?destination=selous',
                'primary_button_icon' => 'ri-compass-line',
                'secondary_button_text' => 'Reserve Info',
                'secondary_button_link' => '/destinations/selous',
                'secondary_button_icon' => 'ri-map-line',
                'display_order' => 15,
                'animation_type' => 'slide-right',
                'overlay_type' => 'gradient',
                'is_active' => true,
            ],
            [
                'title' => 'Ruaha National Park',
                'subtitle' => 'Tanzania\'s largest national park offers remote wilderness, diverse landscapes, and exceptional predator sightings away from the crowds.',
                'badge_text' => 'Wilderness',
                'badge_icon' => 'ri-landscape-line',
                'image_id' => null,
                'image_url' => 'images/hero-slider/animal-movement.jpg',
                'primary_button_text' => 'Ruaha Tours',
                'primary_button_link' => '/tours?destination=ruaha',
                'primary_button_icon' => 'ri-map-pin-line',
                'secondary_button_text' => 'Park Guide',
                'secondary_button_link' => '/destinations/ruaha',
                'secondary_button_icon' => 'ri-book-line',
                'display_order' => 16,
                'animation_type' => 'fade-in-up',
                'overlay_type' => 'gradient',
                'is_active' => true,
            ],
            [
                'title' => 'Mafia Island Diving',
                'subtitle' => 'Dive into pristine coral reefs and swim with whale sharks. Mafia Island offers world-class diving in crystal-clear waters.',
                'badge_text' => 'Diving',
                'badge_icon' => 'ri-water-percent-line',
                'image_id' => null,
                'image_url' => 'images/hero-slider/zanzibar-beach.jpg',
                'primary_button_text' => 'Diving Packages',
                'primary_button_link' => '/tours?activity=diving',
                'primary_button_icon' => 'ri-swimming-pool-line',
                'secondary_button_text' => 'Dive Sites',
                'secondary_button_link' => '/experiences/diving',
                'secondary_button_icon' => 'ri-map-pin-line',
                'display_order' => 17,
                'animation_type' => 'zoom-in',
                'overlay_type' => 'light',
                'is_active' => true,
            ],
            [
                'title' => 'Arusha National Park',
                'subtitle' => 'A perfect introduction to Tanzania\'s wildlife. Easy access, diverse habitats, and Mount Meru views make this park ideal for day trips.',
                'badge_text' => 'Day Trip',
                'badge_icon' => 'ri-sun-line',
                'image_id' => null,
                'image_url' => 'images/hero-slider/morning.jpg',
                'primary_button_text' => 'Day Tours',
                'primary_button_link' => '/tours?destination=arusha',
                'primary_button_icon' => 'ri-calendar-todo-line',
                'secondary_button_text' => 'Park Info',
                'secondary_button_link' => '/destinations/arusha',
                'secondary_button_icon' => 'ri-information-line',
                'display_order' => 18,
                'animation_type' => 'slide-left',
                'overlay_type' => 'gradient',
                'is_active' => true,
            ],
            [
                'title' => 'Dar es Salaam City Tours',
                'subtitle' => 'Explore Tanzania\'s vibrant commercial capital. Rich history, bustling markets, beautiful beaches, and Swahili culture await.',
                'badge_text' => 'City',
                'badge_icon' => 'ri-building-2-line',
                'image_id' => null,
                'image_url' => 'images/hero-slider/cultural-tour.jpg',
                'primary_button_text' => 'City Tours',
                'primary_button_link' => '/tours?destination=dar-es-salaam',
                'primary_button_icon' => 'ri-map-2-line',
                'secondary_button_text' => 'City Guide',
                'secondary_button_link' => '/destinations/dar-es-salaam',
                'secondary_button_icon' => 'ri-guide-line',
                'display_order' => 19,
                'animation_type' => 'fade-in-up',
                'overlay_type' => 'dark',
                'is_active' => true,
            ],
            [
                'title' => 'Custom Safari Packages',
                'subtitle' => 'Design your perfect Tanzania adventure. Our experts will create a personalized itinerary tailored to your interests, budget, and timeline.',
                'badge_text' => 'Custom',
                'badge_icon' => 'ri-settings-3-line',
                'image_id' => null,
                'image_url' => 'images/hero-slider/ngorongoro-crater.jpg',
                'primary_button_text' => 'Create Custom Tour',
                'primary_button_link' => '/custom-tours',
                'primary_button_icon' => 'ri-tools-line',
                'secondary_button_text' => 'Get Quote',
                'secondary_button_link' => '/contact',
                'secondary_button_icon' => 'ri-price-tag-3-line',
                'display_order' => 20,
                'animation_type' => 'slide-right',
                'overlay_type' => 'gradient',
                'is_active' => true,
            ],
        ];

        $created = 0;
        foreach ($slides as $slideData) {
            $slide = HeroSlide::firstOrCreate(
                ['title' => $slideData['title']],
                $slideData
            );
            
            if ($slide->wasRecentlyCreated) {
                $created++;
                $this->command->line("   ✓ Created: {$slideData['title']}");
            } else {
                $this->command->line("   - Exists: {$slideData['title']}");
            }
        }

        $this->command->newLine();
        $this->command->info("✅ Successfully seeded {$created} new hero slides (Total: " . HeroSlide::count() . ")");
        $this->command->newLine();
    }
}

