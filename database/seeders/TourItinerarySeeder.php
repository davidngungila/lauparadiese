<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Tour;
use App\Models\TourItinerary;

class TourItinerarySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tours = Tour::all();
        
        foreach ($tours as $tour) {
            $this->createItineraryForTour($tour);
        }
        
        $this->command->info('Successfully seeded itineraries for ' . $tours->count() . ' tours!');
    }
    
    /**
     * Create itinerary for a specific tour
     */
    private function createItineraryForTour(Tour $tour)
    {
        $slug = $tour->slug;
        $duration = $tour->duration_days ?? 1;
        
        // Skip if itinerary already exists
        if (TourItinerary::where('tour_id', $tour->id)->count() > 0) {
            $this->command->info("Skipping {$tour->name} - itinerary already exists");
            return;
        }
        
        $itineraries = [];
        
        // Generate itineraries based on tour slug/type
        if (str_contains($slug, 'safari-zanzibar') || str_contains($slug, 'honeymoon')) {
            $itineraries = $this->getSafariZanzibarItinerary($tour, $duration);
        } elseif (str_contains($slug, 'kilimanjaro') || str_contains($slug, 'marangu') || str_contains($slug, 'machame') || str_contains($slug, 'lemosho')) {
            $itineraries = $this->getKilimanjaroItinerary($tour, $duration);
        } elseif (str_contains($slug, 'zanzibar') && !str_contains($slug, 'safari')) {
            $itineraries = $this->getZanzibarItinerary($tour, $duration);
        } elseif (str_contains($slug, 'migration')) {
            $itineraries = $this->getMigrationSafariItinerary($tour, $duration);
        } elseif (str_contains($slug, 'luxury')) {
            $itineraries = $this->getLuxurySafariItinerary($tour, $duration);
        } elseif (str_contains($slug, 'photography')) {
            $itineraries = $this->getPhotographySafariItinerary($tour, $duration);
        } elseif (str_contains($slug, 'family')) {
            $itineraries = $this->getFamilySafariItinerary($tour, $duration);
        } elseif (str_contains($slug, 'southern')) {
            $itineraries = $this->getSouthernCircuitItinerary($tour, $duration);
        } elseif (str_contains($slug, 'northern-circuit')) {
            $itineraries = $this->getNorthernCircuitItinerary($tour, $duration);
        } else {
            $itineraries = $this->getStandardSafariItinerary($tour, $duration);
        }
        
        foreach ($itineraries as $itineraryData) {
            TourItinerary::create($itineraryData);
        }
        
        $this->command->info("Created {$duration}-day itinerary for: {$tour->name}");
    }
    
    /**
     * Safari + Zanzibar Itinerary
     */
    private function getSafariZanzibarItinerary(Tour $tour, int $duration): array
    {
        $safariDays = $duration - 4; // Reserve 4 days for Zanzibar
        $itineraries = [];
        
        // Day 1: Arrival
        $itineraries[] = [
            'tour_id' => $tour->id,
            'day_number' => 1,
            'title' => 'Arrival in Arusha',
            'short_summary' => 'Welcome to Tanzania! Arrive at Kilimanjaro International Airport and transfer to your hotel in Arusha.',
            'description' => 'Upon arrival at Kilimanjaro International Airport, you\'ll be warmly welcomed by our representative and transferred to your hotel in Arusha. After check-in, you\'ll have time to relax and freshen up. In the evening, enjoy a welcome briefing with your guide who will provide an overview of your upcoming safari adventure. Dinner will be served at the hotel.',
            'meals_included' => ['Dinner'],
            'accommodation_type' => 'Hotel',
            'accommodation_name' => 'Arusha Hotel or Similar',
            'accommodation_location' => 'Arusha',
            'accommodation_rating' => 4.0,
            'location' => 'Arusha',
            'activities' => [
                ['name' => 'Airport Transfer', 'icon' => 'plane'],
                ['name' => 'Welcome Briefing', 'icon' => 'users'],
                ['name' => 'Hotel Check-in', 'icon' => 'bed']
            ],
            'vehicle_type' => 'Airport Transfer Vehicle',
            'transfer_info' => 'Airport to hotel transfer included',
            'day_notes' => 'Please ensure you arrive with valid passport and yellow fever vaccination certificate.',
            'sort_order' => 1,
        ];
        
        // Safari Days
        $safariLocations = [
            ['name' => 'Tarangire National Park', 'accommodation' => 'Tarangire Safari Lodge', 'type' => 'Lodge'],
            ['name' => 'Lake Manyara National Park', 'accommodation' => 'Lake Manyara Serena Lodge', 'type' => 'Lodge'],
            ['name' => 'Serengeti National Park', 'accommodation' => 'Serengeti Serena Safari Lodge', 'type' => 'Lodge'],
            ['name' => 'Ngorongoro Crater', 'accommodation' => 'Ngorongoro Serena Safari Lodge', 'type' => 'Lodge'],
        ];
        
        $day = 2;
        foreach ($safariLocations as $index => $location) {
            if ($day > $safariDays) break;
            
            $itineraries[] = [
                'tour_id' => $tour->id,
                'day_number' => $day,
                'title' => $location['name'],
                'short_summary' => "Early morning departure to {$location['name']}. Full day game drives with incredible wildlife viewing opportunities.",
                'description' => "After an early breakfast, depart for {$location['name']}. The journey takes you through beautiful landscapes and local communities. Upon arrival, begin your game drive experience. {$location['name']} is renowned for its diverse wildlife and stunning scenery. You'll have opportunities to see the Big Five, various antelope species, and incredible birdlife. Your expert guide will share knowledge about the animals, their behaviors, and the ecosystem. Enjoy a picnic lunch in the park before continuing with afternoon game drives. Return to your lodge in the evening for dinner and relaxation.",
                'meals_included' => ['Breakfast', 'Lunch', 'Dinner'],
                'accommodation_type' => $location['type'],
                'accommodation_name' => $location['accommodation'],
                'accommodation_location' => $location['name'],
                'accommodation_rating' => 4.5,
                'location' => $location['name'],
                'activities' => [
                    ['name' => 'Game Drive', 'icon' => 'car'],
                    ['name' => 'Wildlife Viewing', 'icon' => 'camera'],
                    ['name' => 'Photography', 'icon' => 'image']
                ],
                'vehicle_type' => '4x4 Safari Vehicle',
                'driver_guide_notes' => 'Professional English-speaking guide with extensive wildlife knowledge',
                'transfer_info' => "Transfer from previous location to {$location['name']}",
                'day_notes' => 'Game drives are weather-dependent. Best wildlife viewing is early morning and late afternoon.',
                'sort_order' => $day,
            ];
            $day++;
            
            // Add second day in Serengeti if time permits
            if ($location['name'] === 'Serengeti National Park' && $day <= $safariDays) {
                $itineraries[] = [
                    'tour_id' => $tour->id,
                    'day_number' => $day,
                    'title' => 'Serengeti - Full Day Game Drives',
                    'short_summary' => 'Full day exploring the endless plains of Serengeti with morning and afternoon game drives.',
                    'description' => 'Today is dedicated to exploring the vast Serengeti plains. Start with an early morning game drive to catch the animals at their most active. Witness the incredible diversity of wildlife including lions, elephants, cheetahs, and if you\'re lucky, witness the Great Migration (seasonal). Return to the lodge for lunch and relaxation. In the afternoon, embark on another game drive to different areas of the park. The Serengeti offers some of the best wildlife photography opportunities in Africa. Enjoy a sundowner before returning to the lodge for dinner.',
                    'meals_included' => ['Breakfast', 'Lunch', 'Dinner'],
                    'accommodation_type' => 'Lodge',
                    'accommodation_name' => 'Serengeti Serena Safari Lodge',
                    'accommodation_location' => 'Serengeti National Park',
                    'accommodation_rating' => 4.5,
                    'location' => 'Serengeti National Park',
                    'activities' => [
                        ['name' => 'Morning Game Drive', 'icon' => 'sun'],
                        ['name' => 'Afternoon Game Drive', 'icon' => 'sunset'],
                        ['name' => 'Sundowner', 'icon' => 'cocktail']
                    ],
                    'vehicle_type' => '4x4 Safari Vehicle',
                    'day_notes' => 'Great Migration viewing is seasonal (typically July-October in northern Serengeti).',
                    'sort_order' => $day,
                ];
                $day++;
            }
        }
        
        // Ngorongoro Crater Descent
        if ($day <= $safariDays) {
            $itineraries[] = [
                'tour_id' => $tour->id,
                'day_number' => $day,
                'title' => 'Ngorongoro Crater - Full Day Descent',
                'short_summary' => 'Descend into the world\'s largest intact caldera for incredible wildlife viewing.',
                'description' => 'After an early breakfast, descend into the Ngorongoro Crater, one of Africa\'s Seven Natural Wonders. The crater floor is home to an incredible concentration of wildlife, including the Big Five. The unique ecosystem creates a natural enclosure, making wildlife viewing exceptional. You\'ll have a full day to explore the crater, with a picnic lunch at a scenic spot. The crater is also home to a large population of flamingos at Lake Magadi. In the afternoon, ascend the crater and transfer to your lodge on the crater rim for dinner and overnight.',
                'meals_included' => ['Breakfast', 'Lunch', 'Dinner'],
                'accommodation_type' => 'Lodge',
                'accommodation_name' => 'Ngorongoro Serena Safari Lodge',
                'accommodation_location' => 'Ngorongoro Crater Rim',
                'accommodation_rating' => 4.6,
                'location' => 'Ngorongoro Crater',
                'activities' => [
                    ['name' => 'Crater Descent', 'icon' => 'mountain'],
                    ['name' => 'Full Day Game Drive', 'icon' => 'car'],
                    ['name' => 'Big Five Viewing', 'icon' => 'camera']
                ],
                'vehicle_type' => '4x4 Safari Vehicle',
                'day_notes' => 'Crater descent requires early start. Maximum 6 hours allowed in crater per day.',
                'sort_order' => $day,
            ];
            $day++;
        }
        
        // Transfer to Zanzibar
        $itineraries[] = [
            'tour_id' => $tour->id,
            'day_number' => $day,
            'title' => 'Transfer to Zanzibar',
            'short_summary' => 'Fly from Arusha to Zanzibar and transfer to your beach resort.',
            'description' => 'After breakfast, transfer to Arusha Airport for your flight to Zanzibar. Upon arrival at Zanzibar Airport, you\'ll be met and transferred to your beach resort. Check in and enjoy the rest of the day at leisure. Relax on the pristine white sand beaches, take a dip in the crystal-clear waters of the Indian Ocean, or explore the resort facilities. Dinner will be served at the resort.',
            'meals_included' => ['Breakfast', 'Dinner'],
            'accommodation_type' => 'Beach Resort',
            'accommodation_name' => 'Zanzibar Beach Resort',
            'accommodation_location' => 'Zanzibar',
            'accommodation_rating' => 4.7,
            'location' => 'Zanzibar',
            'activities' => [
                ['name' => 'Flight to Zanzibar', 'icon' => 'plane'],
                ['name' => 'Beach Relaxation', 'icon' => 'umbrella'],
                ['name' => 'Resort Activities', 'icon' => 'swimming-pool']
            ],
            'vehicle_type' => 'Airport Transfer & Flight',
            'transfer_info' => 'Domestic flight from Arusha to Zanzibar included',
            'day_notes' => 'Flight duration approximately 1.5 hours. Baggage allowance: 15kg checked, 5kg hand luggage.',
            'sort_order' => $day,
        ];
        $day++;
        
        // Zanzibar Days
        $zanzibarActivities = [
            ['title' => 'Stone Town & Spice Tour', 'activity' => 'Cultural Tour'],
            ['title' => 'Beach Day & Water Activities', 'activity' => 'Beach & Snorkeling'],
            ['title' => 'Dolphin Watching & Jozani Forest', 'activity' => 'Wildlife & Nature'],
            ['title' => 'Beach Relaxation & Dhow Cruise', 'activity' => 'Relaxation'],
        ];
        
        foreach ($zanzibarActivities as $index => $activity) {
            if ($day > $duration) break;
            
            $itineraries[] = [
                'tour_id' => $tour->id,
                'day_number' => $day,
                'title' => $activity['title'],
                'short_summary' => "Experience Zanzibar's {$activity['activity']} with guided tours and activities.",
                'description' => $this->getZanzibarDayDescription($activity['title']),
                'meals_included' => ['Breakfast'],
                'accommodation_type' => 'Beach Resort',
                'accommodation_name' => 'Zanzibar Beach Resort',
                'accommodation_location' => 'Zanzibar',
                'accommodation_rating' => 4.7,
                'location' => 'Zanzibar',
                'activities' => $this->getZanzibarActivities($activity['title']),
                'vehicle_type' => 'Tour Vehicle',
                'day_notes' => 'Beach activities are weather-dependent. Sun protection recommended.',
                'sort_order' => $day,
            ];
            $day++;
        }
        
        // Departure Day
        $itineraries[] = [
            'tour_id' => $tour->id,
            'day_number' => $duration,
            'title' => 'Departure from Zanzibar',
            'short_summary' => 'Transfer to Zanzibar Airport for your departure flight.',
            'description' => 'Enjoy a final breakfast at the resort. Depending on your flight time, you may have some free time to relax on the beach or do last-minute shopping. Transfer to Zanzibar Airport for your departure flight. We hope you\'ve had an incredible journey combining the best of Tanzania\'s wildlife and beaches!',
            'meals_included' => ['Breakfast'],
            'accommodation_type' => null,
            'accommodation_name' => null,
            'location' => 'Zanzibar Airport',
            'activities' => [
                ['name' => 'Airport Transfer', 'icon' => 'plane'],
                ['name' => 'Departure', 'icon' => 'plane']
            ],
            'vehicle_type' => 'Airport Transfer Vehicle',
            'transfer_info' => 'Transfer to Zanzibar Airport for departure',
            'day_notes' => 'Please check out by 11:00 AM. Flight times will determine transfer schedule.',
            'sort_order' => $duration,
        ];
        
        return $itineraries;
    }
    
    /**
     * Kilimanjaro Climbing Itinerary
     */
    private function getKilimanjaroItinerary(Tour $tour, int $duration): array
    {
        $itineraries = [];
        $route = str_contains($tour->slug, 'marangu') ? 'Marangu' : (str_contains($tour->slug, 'machame') ? 'Machame' : 'Lemosho');
        
        // Day 1: Arrival
        $itineraries[] = [
            'tour_id' => $tour->id,
            'day_number' => 1,
            'title' => 'Arrival in Moshi',
            'short_summary' => 'Arrive in Moshi, meet your guide, and prepare for the climb.',
            'description' => 'Upon arrival at Kilimanjaro International Airport, you\'ll be transferred to your hotel in Moshi. After check-in, you\'ll meet your mountain guide for a comprehensive briefing about the climb. Your guide will check your equipment and provide any necessary rentals. Enjoy a welcome dinner and get a good night\'s rest before beginning your adventure tomorrow.',
            'meals_included' => ['Dinner'],
            'accommodation_type' => 'Hotel',
            'accommodation_name' => 'Moshi Hotel',
            'accommodation_location' => 'Moshi',
            'location' => 'Moshi',
            'activities' => [
                ['name' => 'Airport Transfer', 'icon' => 'plane'],
                ['name' => 'Pre-climb Briefing', 'icon' => 'users'],
                ['name' => 'Equipment Check', 'icon' => 'backpack']
            ],
            'vehicle_type' => 'Airport Transfer Vehicle',
            'day_notes' => 'Ensure all personal gear is ready. Rental equipment available if needed.',
            'sort_order' => 1,
        ];
        
        // Climbing Days
        $climbingDays = $duration - 2; // Minus arrival and departure
        
        if ($route === 'Marangu') {
            $itineraries = array_merge($itineraries, $this->getMaranguRouteDays($tour, $climbingDays));
        } elseif ($route === 'Machame') {
            $itineraries = array_merge($itineraries, $this->getMachameRouteDays($tour, $climbingDays));
        } else {
            $itineraries = array_merge($itineraries, $this->getLemoshoRouteDays($tour, $climbingDays));
        }
        
        // Departure Day
        $itineraries[] = [
            'tour_id' => $tour->id,
            'day_number' => $duration,
            'title' => 'Departure',
            'short_summary' => 'Transfer to airport for departure.',
            'description' => 'After breakfast, transfer to Kilimanjaro International Airport for your departure flight. If you have time, you can explore Moshi town or do some souvenir shopping. We hope you\'ve had an incredible experience conquering Africa\'s highest peak!',
            'meals_included' => ['Breakfast'],
            'location' => 'Moshi / Airport',
            'activities' => [
                ['name' => 'Airport Transfer', 'icon' => 'plane']
            ],
            'vehicle_type' => 'Airport Transfer Vehicle',
            'sort_order' => $duration,
        ];
        
        return $itineraries;
    }
    
    /**
     * Marangu Route Days
     */
    private function getMaranguRouteDays(Tour $tour, int $days): array
    {
        $itineraries = [];
        $day = 2;
        
        $stages = [
            ['name' => 'Marangu Gate to Mandara Hut', 'altitude' => '2,700m', 'time' => '4-5 hours', 'hut' => 'Mandara Hut'],
            ['name' => 'Mandara Hut to Horombo Hut', 'altitude' => '3,720m', 'time' => '6-7 hours', 'hut' => 'Horombo Hut'],
            ['name' => 'Horombo Hut to Kibo Hut', 'altitude' => '4,703m', 'time' => '6-7 hours', 'hut' => 'Kibo Hut'],
            ['name' => 'Summit Day - Kibo Hut to Uhuru Peak to Horombo Hut', 'altitude' => '5,895m', 'time' => '12-14 hours', 'hut' => 'Horombo Hut'],
            ['name' => 'Horombo Hut to Marangu Gate to Moshi', 'altitude' => '1,860m', 'time' => '5-6 hours', 'hut' => null],
        ];
        
        foreach ($stages as $index => $stage) {
            if ($day > $days + 1) break;
            
            $itineraries[] = [
                'tour_id' => $tour->id,
                'day_number' => $day,
                'title' => $stage['name'],
                'short_summary' => "Climb to {$stage['altitude']} - {$stage['time']} of trekking.",
                'description' => $this->getKilimanjaroDayDescription($stage, $index === 3),
                'meals_included' => ['Breakfast', 'Lunch', 'Dinner'],
                'accommodation_type' => $stage['hut'] ? 'Mountain Hut' : 'Hotel',
                'accommodation_name' => $stage['hut'] ?? 'Moshi Hotel',
                'accommodation_location' => $stage['hut'] ? 'Kilimanjaro' : 'Moshi',
                'location' => $stage['hut'] ? $stage['hut'] : 'Moshi',
                'activities' => $this->getKilimanjaroActivities($index, $stage),
                'vehicle_type' => $index === 0 ? 'Transfer Vehicle' : null,
                'transfer_info' => $index === 0 ? 'Transfer from Moshi to Marangu Gate' : null,
                'day_notes' => $this->getKilimanjaroDayNotes($index),
                'sort_order' => $day,
            ];
            $day++;
        }
        
        return $itineraries;
    }
    
    /**
     * Machame Route Days
     */
    private function getMachameRouteDays(Tour $tour, int $days): array
    {
        $itineraries = [];
        $day = 2;
        
        $stages = [
            ['name' => 'Machame Gate to Machame Camp', 'altitude' => '3,000m', 'time' => '5-7 hours', 'camp' => 'Machame Camp'],
            ['name' => 'Machame Camp to Shira Camp', 'altitude' => '3,840m', 'time' => '4-6 hours', 'camp' => 'Shira Camp'],
            ['name' => 'Shira Camp to Barranco Camp', 'altitude' => '3,950m', 'time' => '6-8 hours', 'camp' => 'Barranco Camp'],
            ['name' => 'Barranco Camp to Karanga Camp', 'altitude' => '4,035m', 'time' => '4-5 hours', 'camp' => 'Karanga Camp'],
            ['name' => 'Karanga Camp to Barafu Camp', 'altitude' => '4,673m', 'time' => '4-5 hours', 'camp' => 'Barafu Camp'],
            ['name' => 'Summit Day - Barafu Camp to Uhuru Peak to Mweka Camp', 'altitude' => '5,895m', 'time' => '12-14 hours', 'camp' => 'Mweka Camp'],
            ['name' => 'Mweka Camp to Mweka Gate to Moshi', 'altitude' => '1,640m', 'time' => '3-4 hours', 'camp' => null],
        ];
        
        foreach ($stages as $index => $stage) {
            if ($day > $days + 1) break;
            
            $itineraries[] = [
                'tour_id' => $tour->id,
                'day_number' => $day,
                'title' => $stage['name'],
                'short_summary' => "Trek to {$stage['altitude']} - {$stage['time']} of climbing.",
                'description' => $this->getMachameDayDescription($stage, $index === 5),
                'meals_included' => ['Breakfast', 'Lunch', 'Dinner'],
                'accommodation_type' => $stage['camp'] ? 'Mountain Camp' : 'Hotel',
                'accommodation_name' => $stage['camp'] ?? 'Moshi Hotel',
                'accommodation_location' => $stage['camp'] ? 'Kilimanjaro' : 'Moshi',
                'location' => $stage['camp'] ? $stage['camp'] : 'Moshi',
                'activities' => $this->getKilimanjaroActivities($index, $stage, true),
                'vehicle_type' => $index === 0 ? 'Transfer Vehicle' : null,
                'transfer_info' => $index === 0 ? 'Transfer from Moshi to Machame Gate' : null,
                'day_notes' => $this->getKilimanjaroDayNotes($index),
                'sort_order' => $day,
            ];
            $day++;
        }
        
        return $itineraries;
    }
    
    /**
     * Lemosho Route Days
     */
    private function getLemoshoRouteDays(Tour $tour, int $days): array
    {
        $itineraries = [];
        $day = 2;
        
        $stages = [
            ['name' => 'Londorossi Gate to Mti Mkubwa Camp', 'altitude' => '2,650m', 'time' => '3-4 hours', 'camp' => 'Mti Mkubwa Camp'],
            ['name' => 'Mti Mkubwa Camp to Shira 1 Camp', 'altitude' => '3,500m', 'time' => '5-6 hours', 'camp' => 'Shira 1 Camp'],
            ['name' => 'Shira 1 Camp to Shira 2 Camp', 'altitude' => '3,900m', 'time' => '3-4 hours', 'camp' => 'Shira 2 Camp'],
            ['name' => 'Shira 2 Camp to Barranco Camp', 'altitude' => '3,950m', 'time' => '6-8 hours', 'camp' => 'Barranco Camp'],
            ['name' => 'Barranco Camp to Karanga Camp', 'altitude' => '4,035m', 'time' => '4-5 hours', 'camp' => 'Karanga Camp'],
            ['name' => 'Karanga Camp to Barafu Camp', 'altitude' => '4,673m', 'time' => '4-5 hours', 'camp' => 'Barafu Camp'],
            ['name' => 'Summit Day - Barafu Camp to Uhuru Peak to Mweka Camp', 'altitude' => '5,895m', 'time' => '12-14 hours', 'camp' => 'Mweka Camp'],
            ['name' => 'Mweka Camp to Mweka Gate to Moshi', 'altitude' => '1,640m', 'time' => '3-4 hours', 'camp' => null],
        ];
        
        foreach ($stages as $index => $stage) {
            if ($day > $days + 1) break;
            
            $itineraries[] = [
                'tour_id' => $tour->id,
                'day_number' => $day,
                'title' => $stage['name'],
                'short_summary' => "Trek to {$stage['altitude']} - {$stage['time']} of climbing through diverse ecosystems.",
                'description' => $this->getLemoshoDayDescription($stage, $index === 6),
                'meals_included' => ['Breakfast', 'Lunch', 'Dinner'],
                'accommodation_type' => $stage['camp'] ? 'Mountain Camp' : 'Hotel',
                'accommodation_name' => $stage['camp'] ?? 'Moshi Hotel',
                'accommodation_location' => $stage['camp'] ? 'Kilimanjaro' : 'Moshi',
                'location' => $stage['camp'] ? $stage['camp'] : 'Moshi',
                'activities' => $this->getKilimanjaroActivities($index, $stage, true),
                'vehicle_type' => $index === 0 ? 'Transfer Vehicle' : null,
                'transfer_info' => $index === 0 ? 'Transfer from Moshi to Londorossi Gate' : null,
                'day_notes' => $this->getKilimanjaroDayNotes($index),
                'sort_order' => $day,
            ];
            $day++;
        }
        
        return $itineraries;
    }
    
    /**
     * Zanzibar Only Itinerary
     */
    private function getZanzibarItinerary(Tour $tour, int $duration): array
    {
        $itineraries = [];
        
        // Day 1: Arrival
        $itineraries[] = [
            'tour_id' => $tour->id,
            'day_number' => 1,
            'title' => 'Arrival in Zanzibar',
            'short_summary' => 'Arrive at Zanzibar Airport and transfer to your beach resort.',
            'description' => 'Upon arrival at Zanzibar Airport, you\'ll be met and transferred to your beach resort. After check-in, you\'ll have time to relax and settle in. Enjoy the pristine white sand beaches and crystal-clear waters of the Indian Ocean. The resort offers various facilities including swimming pools, restaurants, and water sports. Dinner will be served at the resort.',
            'meals_included' => ['Dinner'],
            'accommodation_type' => 'Beach Resort',
            'accommodation_name' => 'Zanzibar Beach Resort',
            'accommodation_location' => 'Zanzibar',
            'accommodation_rating' => 4.7,
            'location' => 'Zanzibar',
            'activities' => [
                ['name' => 'Airport Transfer', 'icon' => 'plane'],
                ['name' => 'Beach Relaxation', 'icon' => 'umbrella'],
                ['name' => 'Resort Check-in', 'icon' => 'bed']
            ],
            'vehicle_type' => 'Airport Transfer Vehicle',
            'day_notes' => 'Resort check-in typically 2:00 PM. Early check-in subject to availability.',
            'sort_order' => 1,
        ];
        
        // Zanzibar Activity Days
        $activities = [
            'Stone Town Cultural Tour',
            'Spice Plantation Tour',
            'Beach Day & Water Sports',
            'Dolphin Watching & Snorkeling',
            'Jozani Forest & Red Colobus Monkeys',
            'Dhow Sailing & Sunset Cruise',
            'Beach Relaxation',
            'Beach Activities & Local Markets',
        ];
        
        for ($day = 2; $day < $duration; $day++) {
            $activity = $activities[($day - 2) % count($activities)];
            $itineraries[] = [
                'tour_id' => $tour->id,
                'day_number' => $day,
                'title' => $activity,
                'short_summary' => "Experience Zanzibar's {$activity} with guided tours.",
                'description' => $this->getZanzibarDayDescription($activity),
                'meals_included' => ['Breakfast'],
                'accommodation_type' => 'Beach Resort',
                'accommodation_name' => 'Zanzibar Beach Resort',
                'accommodation_location' => 'Zanzibar',
                'accommodation_rating' => 4.7,
                'location' => 'Zanzibar',
                'activities' => $this->getZanzibarActivities($activity),
                'vehicle_type' => 'Tour Vehicle',
                'day_notes' => 'Activities are weather-dependent. Flexible scheduling available.',
                'sort_order' => $day,
            ];
        }
        
        // Departure Day
        $itineraries[] = [
            'tour_id' => $tour->id,
            'day_number' => $duration,
            'title' => 'Departure from Zanzibar',
            'short_summary' => 'Transfer to Zanzibar Airport for departure.',
            'description' => 'Enjoy a final breakfast at the resort. Depending on your flight time, you may have some free time. Transfer to Zanzibar Airport for your departure flight. We hope you\'ve enjoyed your time in paradise!',
            'meals_included' => ['Breakfast'],
            'location' => 'Zanzibar Airport',
            'activities' => [
                ['name' => 'Airport Transfer', 'icon' => 'plane']
            ],
            'vehicle_type' => 'Airport Transfer Vehicle',
            'sort_order' => $duration,
        ];
        
        return $itineraries;
    }
    
    /**
     * Migration Safari Itinerary
     */
    private function getMigrationSafariItinerary(Tour $tour, int $duration): array
    {
        $itineraries = [];
        
        // Day 1: Arrival
        $itineraries[] = [
            'tour_id' => $tour->id,
            'day_number' => 1,
            'title' => 'Arrival in Arusha',
            'short_summary' => 'Welcome to Tanzania! Arrive and transfer to your hotel.',
            'description' => 'Upon arrival at Kilimanjaro International Airport, you\'ll be transferred to your hotel in Arusha. After check-in, enjoy a welcome briefing with your guide about the Great Migration safari. Dinner at the hotel.',
            'meals_included' => ['Dinner'],
            'accommodation_type' => 'Hotel',
            'accommodation_name' => 'Arusha Hotel',
            'accommodation_location' => 'Arusha',
            'location' => 'Arusha',
            'activities' => [
                ['name' => 'Airport Transfer', 'icon' => 'plane'],
                ['name' => 'Welcome Briefing', 'icon' => 'users']
            ],
            'sort_order' => 1,
        ];
        
        // Safari Days focused on Migration
        $safariDays = [
            ['park' => 'Tarangire National Park', 'focus' => 'Elephant herds and baobab trees'],
            ['park' => 'Lake Manyara National Park', 'focus' => 'Bird watching and tree-climbing lions'],
            ['park' => 'Serengeti National Park - Central', 'focus' => 'Great Migration tracking'],
            ['park' => 'Serengeti National Park - Northern', 'focus' => 'Mara River crossing area'],
            ['park' => 'Serengeti National Park - Northern', 'focus' => 'River crossing viewing'],
            ['park' => 'Serengeti National Park - Northern', 'focus' => 'Extended migration viewing'],
            ['park' => 'Ngorongoro Crater', 'focus' => 'Big Five in the crater'],
        ];
        
        for ($day = 2; $day < $duration; $day++) {
            $safari = $safariDays[($day - 2) % count($safariDays)];
            
            $itineraries[] = [
                'tour_id' => $tour->id,
                'day_number' => $day,
                'title' => $safari['park'] . ' - ' . $safari['focus'],
                'short_summary' => "Full day game drives in {$safari['park']} focusing on {$safari['focus']}.",
                'description' => $this->getMigrationDayDescription($safari, $day),
                'meals_included' => ['Breakfast', 'Lunch', 'Dinner'],
                'accommodation_type' => 'Lodge',
                'accommodation_name' => $this->getAccommodationForPark($safari['park']),
                'accommodation_location' => $safari['park'],
                'accommodation_rating' => 4.5,
                'location' => $safari['park'],
                'activities' => $this->getSafariActivities($safari['park']),
                'vehicle_type' => '4x4 Safari Vehicle',
                'day_notes' => 'Migration viewing is nature-dependent. Best time: July-October for northern Serengeti.',
                'sort_order' => $day,
            ];
        }
        
        // Departure
        $itineraries[] = [
            'tour_id' => $tour->id,
            'day_number' => $duration,
            'title' => 'Departure',
            'short_summary' => 'Transfer to airport for departure.',
            'description' => 'After breakfast, transfer to Kilimanjaro International Airport for your departure flight.',
            'meals_included' => ['Breakfast'],
            'location' => 'Arusha / Airport',
            'activities' => [
                ['name' => 'Airport Transfer', 'icon' => 'plane']
            ],
            'sort_order' => $duration,
        ];
        
        return $itineraries;
    }
    
    /**
     * Standard Safari Itinerary
     */
    private function getStandardSafariItinerary(Tour $tour, int $duration): array
    {
        $itineraries = [];
        
        // Day 1: Arrival
        $itineraries[] = [
            'tour_id' => $tour->id,
            'day_number' => 1,
            'title' => 'Arrival in Arusha',
            'short_summary' => 'Welcome to Tanzania! Arrive and transfer to hotel.',
            'description' => 'Upon arrival at Kilimanjaro International Airport, transfer to your hotel in Arusha. Welcome briefing and dinner.',
            'meals_included' => ['Dinner'],
            'accommodation_type' => 'Hotel',
            'accommodation_name' => 'Arusha Hotel',
            'accommodation_location' => 'Arusha',
            'location' => 'Arusha',
            'activities' => [
                ['name' => 'Airport Transfer', 'icon' => 'plane'],
                ['name' => 'Welcome Briefing', 'icon' => 'users']
            ],
            'sort_order' => 1,
        ];
        
        // Safari Days
        $parks = ['Tarangire National Park', 'Lake Manyara National Park', 'Serengeti National Park', 'Ngorongoro Crater'];
        
        for ($day = 2; $day < $duration; $day++) {
            $park = $parks[($day - 2) % count($parks)];
            
            $itineraries[] = [
                'tour_id' => $tour->id,
                'day_number' => $day,
                'title' => $park,
                'short_summary' => "Full day game drives in {$park}.",
                'description' => "After breakfast, depart for {$park}. Full day game drives with opportunities to see the Big Five and diverse wildlife. Picnic lunch in the park. Return to lodge for dinner.",
                'meals_included' => ['Breakfast', 'Lunch', 'Dinner'],
                'accommodation_type' => 'Lodge',
                'accommodation_name' => $this->getAccommodationForPark($park),
                'accommodation_location' => $park,
                'accommodation_rating' => 4.5,
                'location' => $park,
                'activities' => $this->getSafariActivities($park),
                'vehicle_type' => '4x4 Safari Vehicle',
                'sort_order' => $day,
            ];
        }
        
        // Departure
        $itineraries[] = [
            'tour_id' => $tour->id,
            'day_number' => $duration,
            'title' => 'Departure',
            'short_summary' => 'Transfer to airport.',
            'description' => 'After breakfast, transfer to airport for departure.',
            'meals_included' => ['Breakfast'],
            'location' => 'Arusha / Airport',
            'activities' => [
                ['name' => 'Airport Transfer', 'icon' => 'plane']
            ],
            'sort_order' => $duration,
        ];
        
        return $itineraries;
    }
    
    // Helper methods for descriptions and activities
    private function getZanzibarDayDescription(string $activity): string
    {
        $descriptions = [
            'Stone Town & Spice Tour' => 'Explore the historic Stone Town, a UNESCO World Heritage Site. Walk through narrow alleys, visit historic buildings, and learn about Zanzibar\'s rich history. Then visit a spice plantation to see how cloves, vanilla, cinnamon, and other spices are grown. Enjoy a traditional Swahili lunch and learn about the spice trade that made Zanzibar famous.',
            'Beach Day & Water Activities' => 'Spend the day relaxing on pristine white sand beaches. Enjoy various water activities including snorkeling, swimming, and beach games. The crystal-clear waters of the Indian Ocean are perfect for water sports. Optional activities include kayaking, stand-up paddleboarding, and beach volleyball.',
            'Dolphin Watching & Jozani Forest' => 'Early morning departure for dolphin watching in the waters off Zanzibar. After dolphin watching, visit Jozani Forest, home to the endemic red colobus monkeys. Take a guided walk through the forest to see these unique primates and learn about the forest ecosystem.',
            'Beach Relaxation & Dhow Cruise' => 'Enjoy a leisurely day at the beach. In the afternoon, embark on a traditional dhow sailing trip. Watch the sunset from the dhow while enjoying refreshments. This is a perfect way to experience Zanzibar\'s maritime culture.',
        ];
        
        return $descriptions[$activity] ?? "Experience {$activity} in Zanzibar with guided activities and cultural immersion.";
    }
    
    private function getZanzibarActivities(string $activity): array
    {
        $activities = [
            'Stone Town & Spice Tour' => [
                ['name' => 'Stone Town Tour', 'icon' => 'map'],
                ['name' => 'Spice Plantation', 'icon' => 'leaf'],
                ['name' => 'Cultural Experience', 'icon' => 'users']
            ],
            'Beach Day & Water Activities' => [
                ['name' => 'Beach Relaxation', 'icon' => 'umbrella'],
                ['name' => 'Snorkeling', 'icon' => 'water'],
                ['name' => 'Water Sports', 'icon' => 'swimming-pool']
            ],
            'Dolphin Watching & Jozani Forest' => [
                ['name' => 'Dolphin Watching', 'icon' => 'water'],
                ['name' => 'Jozani Forest', 'icon' => 'tree'],
                ['name' => 'Wildlife Viewing', 'icon' => 'camera']
            ],
            'Beach Relaxation & Dhow Cruise' => [
                ['name' => 'Beach Time', 'icon' => 'umbrella'],
                ['name' => 'Dhow Sailing', 'icon' => 'ship'],
                ['name' => 'Sunset Viewing', 'icon' => 'sunset']
            ],
        ];
        
        return $activities[$activity] ?? [['name' => 'Beach Activities', 'icon' => 'umbrella']];
    }
    
    private function getKilimanjaroDayDescription(array $stage, bool $isSummit): string
    {
        if ($isSummit) {
            return "Today is summit day! Start very early (around midnight) for the final ascent to Uhuru Peak (5,895m). The climb is challenging but incredibly rewarding. Reach the summit at sunrise for breathtaking views. After celebrating at the summit, descend back to camp. This is the most physically demanding day but also the most rewarding.";
        }
        
        return "Today you'll trek from the previous camp to {$stage['name']} at {$stage['altitude']}. The journey takes approximately {$stage['time']} and takes you through diverse ecosystems. Your guide will set a steady pace to ensure proper acclimatization. Upon arrival at camp, you'll have time to rest, enjoy meals prepared by the cook team, and prepare for the next day's climb.";
    }
    
    private function getMachameDayDescription(array $stage, bool $isSummit): string
    {
        if ($isSummit) {
            return "Summit day begins very early (around 11 PM) with a challenging climb to Uhuru Peak. The route is steep and requires determination. Reach the summit at sunrise for incredible views across Africa. After summit photos and celebration, descend to Mweka Camp. This is the most challenging but rewarding day of the climb.";
        }
        
        return "Trek from previous camp to {$stage['camp']} at {$stage['altitude']}. The journey takes {$stage['time']} through stunning mountain scenery. Today's route includes the famous Barranco Wall if applicable. Your guide ensures proper pacing for acclimatization. Arrive at camp for rest and meals.";
    }
    
    private function getLemoshoDayDescription(array $stage, bool $isSummit): string
    {
        if ($isSummit) {
            return "The ultimate challenge - summit day! Begin the final ascent around midnight. The climb to Uhuru Peak is steep and demanding but offers the highest success rate. Reach the summit at sunrise for panoramic views. After celebrating, descend to Mweka Camp. This day tests your physical and mental strength but rewards you with an incredible achievement.";
        }
        
        return "Continue your climb from previous camp to {$stage['camp']} at {$stage['altitude']}. The {$stage['time']} trek takes you through diverse ecosystems. The Lemosho Route offers excellent acclimatization and stunning scenery. Your guide monitors your progress and ensures safety throughout.";
    }
    
    private function getKilimanjaroActivities(int $index, array $stage, bool $camping = false): array
    {
        if ($index === 3 || $index === 5 || $index === 6) { // Summit day
            return [
                ['name' => 'Summit Climb', 'icon' => 'mountain'],
                ['name' => 'Reach Uhuru Peak', 'icon' => 'flag'],
                ['name' => 'Sunrise Views', 'icon' => 'sun'],
                ['name' => 'Descent', 'icon' => 'arrow-down']
            ];
        }
        
        return [
            ['name' => 'Mountain Trekking', 'icon' => 'hiking'],
            ['name' => 'Acclimatization', 'icon' => 'activity'],
            ['name' => 'Scenic Views', 'icon' => 'camera']
        ];
    }
    
    private function getKilimanjaroDayNotes(int $index): string
    {
        $notes = [
            'First day of climbing. Pace yourself and stay hydrated.',
            'Acclimatization day. Listen to your guide\'s advice.',
            'Important acclimatization day. Rest well.',
            'Summit day - most challenging. Start very early. Stay positive and follow guide instructions.',
            'Descent day. Take care on steep sections.',
        ];
        
        return $notes[$index] ?? 'Follow your guide\'s instructions. Stay hydrated and pace yourself.';
    }
    
    private function getMigrationDayDescription(array $safari, int $day): string
    {
        if (str_contains($safari['park'], 'Serengeti') && str_contains($safari['focus'], 'Migration')) {
            return "Today is dedicated to tracking and viewing the Great Migration. Your expert guide will use knowledge of migration patterns to position you at the best viewing locations. Witness thousands of wildebeest and zebra as they move across the plains. If conditions are right, you may witness river crossings - one of nature's most dramatic spectacles. The migration is constantly moving, so your guide will track the herds to ensure the best viewing opportunities.";
        }
        
        return "Full day game drives in {$safari['park']} focusing on {$safari['focus']}. Your guide will take you to the best wildlife viewing areas. Enjoy picnic lunch in the park and continue with afternoon game drives. Return to lodge for dinner and overnight.";
    }
    
    private function getSafariActivities(string $park): array
    {
        return [
            ['name' => 'Game Drive', 'icon' => 'car'],
            ['name' => 'Wildlife Viewing', 'icon' => 'camera'],
            ['name' => 'Photography', 'icon' => 'image'],
            ['name' => 'Bird Watching', 'icon' => 'eye']
        ];
    }
    
    private function getAccommodationForPark(string $park): string
    {
        $accommodations = [
            'Tarangire National Park' => 'Tarangire Safari Lodge',
            'Lake Manyara National Park' => 'Lake Manyara Serena Lodge',
            'Serengeti National Park' => 'Serengeti Serena Safari Lodge',
            'Serengeti National Park - Central' => 'Serengeti Serena Safari Lodge',
            'Serengeti National Park - Northern' => 'Serengeti Migration Camp',
            'Ngorongoro Crater' => 'Ngorongoro Serena Safari Lodge',
        ];
        
        return $accommodations[$park] ?? 'Safari Lodge';
    }
    
    // Additional itinerary methods for other tour types
    private function getLuxurySafariItinerary(Tour $tour, int $duration): array
    {
        // Similar to standard safari but with luxury accommodations
        $itineraries = $this->getStandardSafariItinerary($tour, $duration);
        
        // Upgrade accommodations to luxury
        foreach ($itineraries as &$itinerary) {
            if (isset($itinerary['accommodation_type']) && $itinerary['accommodation_type'] === 'Lodge') {
                $itinerary['accommodation_type'] = 'Luxury Lodge';
                $itinerary['accommodation_rating'] = 5.0;
                $itinerary['activities'][] = ['name' => 'Luxury Experience', 'icon' => 'star'];
            }
        }
        
        return $itineraries;
    }
    
    private function getPhotographySafariItinerary(Tour $tour, int $duration): array
    {
        $itineraries = $this->getStandardSafariItinerary($tour, $duration);
        
        // Add photography-specific activities
        foreach ($itineraries as &$itinerary) {
            if (isset($itinerary['activities'])) {
                $itinerary['activities'][] = ['name' => 'Photography Workshop', 'icon' => 'camera'];
                $itinerary['activities'][] = ['name' => 'Golden Hour Shooting', 'icon' => 'sun'];
            }
            $itinerary['day_notes'] = ($itinerary['day_notes'] ?? '') . ' Extended time for photography. Bean bags and camera mounts available.';
        }
        
        return $itineraries;
    }
    
    private function getFamilySafariItinerary(Tour $tour, int $duration): array
    {
        $itineraries = $this->getStandardSafariItinerary($tour, $duration);
        
        // Add family-friendly elements
        foreach ($itineraries as &$itinerary) {
            if (isset($itinerary['activities'])) {
                $itinerary['activities'][] = ['name' => 'Educational Activities', 'icon' => 'book'];
                $itinerary['activities'][] = ['name' => 'Family Games', 'icon' => 'users'];
            }
            $itinerary['day_notes'] = ($itinerary['day_notes'] ?? '') . ' Shorter game drives suitable for children. Family-friendly accommodations with pools.';
        }
        
        return $itineraries;
    }
    
    private function getSouthernCircuitItinerary(Tour $tour, int $duration): array
    {
        $itineraries = [];
        
        // Day 1: Arrival in Dar es Salaam
        $itineraries[] = [
            'tour_id' => $tour->id,
            'day_number' => 1,
            'title' => 'Arrival in Dar es Salaam',
            'short_summary' => 'Arrive in Dar es Salaam and transfer to hotel.',
            'description' => 'Upon arrival, transfer to your hotel in Dar es Salaam. Welcome briefing and dinner.',
            'meals_included' => ['Dinner'],
            'accommodation_type' => 'Hotel',
            'accommodation_name' => 'Dar es Salaam Hotel',
            'accommodation_location' => 'Dar es Salaam',
            'location' => 'Dar es Salaam',
            'activities' => [
                ['name' => 'Airport Transfer', 'icon' => 'plane']
            ],
            'sort_order' => 1,
        ];
        
        // Southern Circuit Parks
        $parks = [
            ['name' => 'Selous Game Reserve', 'activities' => ['Boat Safari', 'Walking Safari', 'Game Drive']],
            ['name' => 'Ruaha National Park', 'activities' => ['Game Drive', 'Wildlife Viewing']],
            ['name' => 'Mikumi National Park', 'activities' => ['Game Drive', 'Bird Watching']],
        ];
        
        for ($day = 2; $day < $duration; $day++) {
            $park = $parks[($day - 2) % count($parks)];
            
            $itineraries[] = [
                'tour_id' => $tour->id,
                'day_number' => $day,
                'title' => $park['name'],
                'short_summary' => "Explore {$park['name']} with various safari activities.",
                'description' => "Full day in {$park['name']} with activities including " . implode(', ', $park['activities']) . ". Experience the authentic wilderness of Tanzania's southern circuit.",
                'meals_included' => ['Breakfast', 'Lunch', 'Dinner'],
                'accommodation_type' => 'Lodge',
                'accommodation_name' => $park['name'] . ' Lodge',
                'accommodation_location' => $park['name'],
                'location' => $park['name'],
                'activities' => array_map(fn($a) => ['name' => $a, 'icon' => 'car'], $park['activities']),
                'sort_order' => $day,
            ];
        }
        
        // Departure
        $itineraries[] = [
            'tour_id' => $tour->id,
            'day_number' => $duration,
            'title' => 'Departure',
            'short_summary' => 'Transfer to airport.',
            'description' => 'After breakfast, transfer to Dar es Salaam Airport for departure.',
            'meals_included' => ['Breakfast'],
            'location' => 'Dar es Salaam / Airport',
            'activities' => [
                ['name' => 'Airport Transfer', 'icon' => 'plane']
            ],
            'sort_order' => $duration,
        ];
        
        return $itineraries;
    }
    
    private function getNorthernCircuitItinerary(Tour $tour, int $duration): array
    {
        // Similar to standard but covers all northern parks
        $itineraries = $this->getStandardSafariItinerary($tour, $duration);
        
        // Add Arusha National Park
        $arushaDay = [
            'tour_id' => $tour->id,
            'day_number' => 2,
            'title' => 'Arusha National Park',
            'short_summary' => 'Day trip to Arusha National Park near Arusha town.',
            'description' => 'Visit Arusha National Park, located just 25km from Arusha. The park features Mount Meru, Momela Lakes, and diverse wildlife. Perfect for a day trip with excellent bird watching and colobus monkey viewing.',
            'meals_included' => ['Breakfast', 'Lunch', 'Dinner'],
            'accommodation_type' => 'Hotel',
            'accommodation_name' => 'Arusha Hotel',
            'accommodation_location' => 'Arusha',
            'location' => 'Arusha National Park',
            'activities' => [
                ['name' => 'Game Drive', 'icon' => 'car'],
                ['name' => 'Bird Watching', 'icon' => 'eye']
            ],
            'sort_order' => 2,
        ];
        
        // Insert Arusha day and adjust other days
        array_splice($itineraries, 1, 0, [$arushaDay]);
        for ($i = 2; $i < count($itineraries) - 1; $i++) {
            $itineraries[$i]['day_number'] = $i + 1;
            $itineraries[$i]['sort_order'] = $i + 1;
        }
        $itineraries[count($itineraries) - 1]['day_number'] = $duration;
        $itineraries[count($itineraries) - 1]['sort_order'] = $duration;
        
        return $itineraries;
    }
}






