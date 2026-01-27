<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Hotel;
use Illuminate\Support\Str;

class HotelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $hotels = [
            // Arusha Area Hotels
            [
                'name' => 'Arusha Coffee Lodge',
                'slug' => 'arusha-coffee-lodge',
                'description' => 'A luxury boutique hotel set on a working coffee plantation, offering elegant rooms, gourmet dining, and stunning views of Mount Meru. Perfect for pre and post-safari stays.',
                'address' => 'Old Moshi Road',
                'city' => 'Arusha',
                'country' => 'Tanzania',
                'phone' => '+255 27 254 0630',
                'email' => 'info@arushacoffeelodge.com',
                'website' => 'https://www.arushacoffeelodge.com',
                'star_rating' => 5,
                'total_rooms' => 30,
                'amenities' => ['WiFi', 'Swimming Pool', 'Restaurant', 'Bar', 'Spa', 'Coffee Tours', 'Airport Shuttle', 'Parking', 'Gift Shop'],
                'image_url' => 'images/hotels/arusha-coffee-lodge.jpg',
                'is_active' => true,
            ],
            [
                'name' => 'Mount Meru Hotel',
                'slug' => 'mount-meru-hotel',
                'description' => 'A modern 4-star hotel in the heart of Arusha, featuring comfortable rooms, multiple dining options, conference facilities, and easy access to Arusha National Park.',
                'address' => 'Usa River Road',
                'city' => 'Arusha',
                'country' => 'Tanzania',
                'phone' => '+255 27 254 0000',
                'email' => 'reservations@mountmeruhotel.com',
                'website' => 'https://www.mountmeruhotel.com',
                'star_rating' => 4,
                'total_rooms' => 120,
                'amenities' => ['WiFi', 'Swimming Pool', 'Restaurant', 'Bar', 'Fitness Center', 'Conference Rooms', 'Business Center', 'Airport Shuttle', 'Parking'],
                'image_url' => 'images/hotels/mount-meru-hotel.jpg',
                'is_active' => true,
            ],
            [
                'name' => 'Kigongoni Lodge',
                'slug' => 'kigongoni-lodge',
                'description' => 'A charming eco-lodge offering rustic luxury in a peaceful setting, with beautiful gardens, comfortable cottages, and excellent cuisine. Ideal for nature lovers.',
                'address' => 'Kigongoni Village',
                'city' => 'Arusha',
                'country' => 'Tanzania',
                'phone' => '+255 27 250 0630',
                'email' => 'info@kigongoni.com',
                'website' => 'https://www.kigongoni.com',
                'star_rating' => 4,
                'total_rooms' => 18,
                'amenities' => ['WiFi', 'Restaurant', 'Bar', 'Garden', 'Library', 'Bird Watching', 'Hiking Trails', 'Parking'],
                'image_url' => 'images/hotels/kigongoni-lodge.jpg',
                'is_active' => true,
            ],

            // Serengeti Area Hotels
            [
                'name' => 'Serengeti Serena Safari Lodge',
                'slug' => 'serengeti-serena-safari-lodge',
                'description' => 'An award-winning lodge located in the heart of the Serengeti, offering luxury accommodations, exceptional game viewing, and authentic African experiences.',
                'address' => 'Central Serengeti',
                'city' => 'Serengeti',
                'country' => 'Tanzania',
                'phone' => '+255 27 253 9160',
                'email' => 'serengeti@serenahotels.com',
                'website' => 'https://www.serenahotels.com',
                'star_rating' => 5,
                'total_rooms' => 66,
                'amenities' => ['WiFi', 'Swimming Pool', 'Restaurant', 'Bar', 'Game Drives', 'Bush Dinners', 'Cultural Shows', 'Gift Shop', 'Laundry Service'],
                'image_url' => 'images/hotels/serengeti-serena-lodge.jpg',
                'is_active' => true,
            ],
            [
                'name' => 'Four Seasons Safari Lodge Serengeti',
                'slug' => 'four-seasons-safari-lodge-serengeti',
                'description' => 'Ultra-luxury safari lodge with infinity pool overlooking a waterhole, world-class spa, gourmet dining, and exclusive game viewing experiences in the Serengeti.',
                'address' => 'Central Serengeti',
                'city' => 'Serengeti',
                'country' => 'Tanzania',
                'phone' => '+255 27 250 0000',
                'email' => 'serengeti@fourseasons.com',
                'website' => 'https://www.fourseasons.com/serengeti',
                'star_rating' => 5,
                'total_rooms' => 77,
                'amenities' => ['WiFi', 'Infinity Pool', 'Spa', 'Restaurant', 'Bar', 'Game Drives', 'Hot Air Balloon', 'Kids Club', 'Fitness Center', 'Business Center', 'Gift Shop'],
                'image_url' => 'images/hotels/four-seasons-serengeti.jpg',
                'is_active' => true,
            ],
            [
                'name' => 'Serengeti Sopa Lodge',
                'slug' => 'serengeti-sopa-lodge',
                'description' => 'A comfortable mid-range lodge offering excellent value, spacious rooms, good food, and prime location for wildlife viewing in the Serengeti.',
                'address' => 'Nyabogati Hill, Serengeti',
                'city' => 'Serengeti',
                'country' => 'Tanzania',
                'phone' => '+255 27 250 0630',
                'email' => 'sopa@serengetisopa.com',
                'website' => 'https://www.serengetisopa.com',
                'star_rating' => 4,
                'total_rooms' => 75,
                'amenities' => ['WiFi', 'Swimming Pool', 'Restaurant', 'Bar', 'Game Drives', 'Gift Shop', 'Parking'],
                'image_url' => 'images/hotels/serengeti-sopa-lodge.jpg',
                'is_active' => true,
            ],

            // Ngorongoro Area Hotels
            [
                'name' => 'Ngorongoro Crater Lodge',
                'slug' => 'ngorongoro-crater-lodge',
                'description' => 'One of Africa\'s most luxurious lodges, perched on the rim of the Ngorongoro Crater. Features opulent suites, gourmet cuisine, and breathtaking crater views.',
                'address' => 'Ngorongoro Crater Rim',
                'city' => 'Ngorongoro',
                'country' => 'Tanzania',
                'phone' => '+255 27 253 9160',
                'email' => 'ngorongoro@andbeyond.com',
                'website' => 'https://www.andbeyond.com',
                'star_rating' => 5,
                'total_rooms' => 30,
                'amenities' => ['WiFi', 'Restaurant', 'Bar', 'Spa', 'Game Drives', 'Crater Tours', 'Bush Dinners', 'Laundry Service', 'Gift Shop'],
                'image_url' => 'images/hotels/ngorongoro-crater-lodge.jpg',
                'is_active' => true,
            ],
            [
                'name' => 'Ngorongoro Serena Safari Lodge',
                'slug' => 'ngorongoro-serena-safari-lodge',
                'description' => 'Built into the crater rim, this lodge offers stunning views, comfortable accommodations, and easy access to the crater floor for exceptional game viewing.',
                'address' => 'Ngorongoro Crater Rim',
                'city' => 'Ngorongoro',
                'country' => 'Tanzania',
                'phone' => '+255 27 253 9160',
                'email' => 'ngorongoro@serenahotels.com',
                'website' => 'https://www.serenahotels.com',
                'star_rating' => 5,
                'total_rooms' => 75,
                'amenities' => ['WiFi', 'Restaurant', 'Bar', 'Game Drives', 'Crater Tours', 'Cultural Visits', 'Gift Shop', 'Laundry Service'],
                'image_url' => 'images/hotels/ngorongoro-serena-lodge.jpg',
                'is_active' => true,
            ],
            [
                'name' => 'Ngorongoro Sopa Lodge',
                'slug' => 'ngorongoro-sopa-lodge',
                'description' => 'A comfortable lodge with excellent crater views, spacious rooms, good food, and great value for money. Perfect for crater exploration.',
                'address' => 'Ngorongoro Crater Rim',
                'city' => 'Ngorongoro',
                'country' => 'Tanzania',
                'phone' => '+255 27 250 0630',
                'email' => 'sopa@ngorongorosopa.com',
                'website' => 'https://www.ngorongorosopa.com',
                'star_rating' => 4,
                'total_rooms' => 96,
                'amenities' => ['WiFi', 'Swimming Pool', 'Restaurant', 'Bar', 'Game Drives', 'Crater Tours', 'Gift Shop', 'Parking'],
                'image_url' => 'images/hotels/ngorongoro-sopa-lodge.jpg',
                'is_active' => true,
            ],

            // Tarangire Area Hotels
            [
                'name' => 'Tarangire Safari Lodge',
                'slug' => 'tarangire-safari-lodge',
                'description' => 'A classic safari lodge overlooking the Tarangire River, offering comfortable accommodations, excellent elephant viewing, and authentic safari experience.',
                'address' => 'Tarangire National Park',
                'city' => 'Tarangire',
                'country' => 'Tanzania',
                'phone' => '+255 27 250 0630',
                'email' => 'info@tarangiresafarilodge.com',
                'website' => 'https://www.tarangiresafarilodge.com',
                'star_rating' => 4,
                'total_rooms' => 50,
                'amenities' => ['WiFi', 'Restaurant', 'Bar', 'Game Drives', 'Bird Watching', 'Gift Shop', 'Parking'],
                'image_url' => 'images/hotels/tarangire-safari-lodge.jpg',
                'is_active' => true,
            ],
            [
                'name' => 'Tarangire Treetops',
                'slug' => 'tarangire-treetops',
                'description' => 'Unique elevated lodge built around ancient baobab trees, offering treehouse-style rooms, exceptional wildlife viewing, and unforgettable safari experience.',
                'address' => 'Tarangire National Park',
                'city' => 'Tarangire',
                'country' => 'Tanzania',
                'phone' => '+255 27 250 0630',
                'email' => 'info@tarangiretreetops.com',
                'website' => 'https://www.tarangiretreetops.com',
                'star_rating' => 5,
                'total_rooms' => 20,
                'amenities' => ['WiFi', 'Restaurant', 'Bar', 'Game Drives', 'Walking Safaris', 'Bird Watching', 'Gift Shop', 'Laundry Service'],
                'image_url' => 'images/hotels/tarangire-treetops.jpg',
                'is_active' => true,
            ],

            // Lake Manyara Area Hotels
            [
                'name' => 'Lake Manyara Hotel',
                'slug' => 'lake-manyara-hotel',
                'description' => 'A comfortable hotel with stunning lake views, offering modern amenities, good food, and easy access to Lake Manyara National Park.',
                'address' => 'Lake Manyara Road',
                'city' => 'Lake Manyara',
                'country' => 'Tanzania',
                'phone' => '+255 27 253 9160',
                'email' => 'info@lakemanyarahotel.com',
                'website' => 'https://www.lakemanyarahotel.com',
                'star_rating' => 4,
                'total_rooms' => 100,
                'amenities' => ['WiFi', 'Swimming Pool', 'Restaurant', 'Bar', 'Game Drives', 'Lake Tours', 'Gift Shop', 'Parking'],
                'image_url' => 'images/hotels/lake-manyara-hotel.jpg',
                'is_active' => true,
            ],
            [
                'name' => 'Lake Manyara Tree Lodge',
                'slug' => 'lake-manyara-tree-lodge',
                'description' => 'An exclusive lodge set in a mahogany forest, offering luxury treehouse accommodations, exceptional bird watching, and intimate safari experience.',
                'address' => 'Lake Manyara National Park',
                'city' => 'Lake Manyara',
                'country' => 'Tanzania',
                'phone' => '+255 27 253 9160',
                'email' => 'lakemanyara@andbeyond.com',
                'website' => 'https://www.andbeyond.com',
                'star_rating' => 5,
                'total_rooms' => 10,
                'amenities' => ['WiFi', 'Restaurant', 'Bar', 'Game Drives', 'Bird Watching', 'Walking Safaris', 'Gift Shop', 'Laundry Service'],
                'image_url' => 'images/hotels/lake-manyara-tree-lodge.jpg',
                'is_active' => true,
            ],

            // Kilimanjaro Area Hotels
            [
                'name' => 'Kibo Palace Hotel',
                'slug' => 'kibo-palace-hotel',
                'description' => 'A luxury hotel in Arusha, perfect for Kilimanjaro climbers. Features comfortable rooms, excellent facilities, and mountain views.',
                'address' => 'Old Moshi Road',
                'city' => 'Arusha',
                'country' => 'Tanzania',
                'phone' => '+255 27 254 4471',
                'email' => 'info@kibopalace.com',
                'website' => 'https://www.kibopalace.com',
                'star_rating' => 4,
                'total_rooms' => 80,
                'amenities' => ['WiFi', 'Swimming Pool', 'Restaurant', 'Bar', 'Fitness Center', 'Spa', 'Conference Rooms', 'Airport Shuttle', 'Parking'],
                'image_url' => 'images/hotels/kibo-palace-hotel.jpg',
                'is_active' => true,
            ],
            [
                'name' => 'Kilimanjaro Mountain Lodge',
                'slug' => 'kilimanjaro-mountain-lodge',
                'description' => 'A charming lodge at the base of Mount Kilimanjaro, offering comfortable accommodations, mountain views, and perfect base for climbing expeditions.',
                'address' => 'Moshi Road',
                'city' => 'Moshi',
                'country' => 'Tanzania',
                'phone' => '+255 27 275 0000',
                'email' => 'info@kilimanjaromountainlodge.com',
                'website' => 'https://www.kilimanjaromountainlodge.com',
                'star_rating' => 4,
                'total_rooms' => 40,
                'amenities' => ['WiFi', 'Restaurant', 'Bar', 'Mountain Views', 'Climbing Gear Storage', 'Gift Shop', 'Parking'],
                'image_url' => 'images/hotels/kilimanjaro-mountain-lodge.jpg',
                'is_active' => true,
            ],

            // Zanzibar Hotels
            [
                'name' => 'Zanzibar Serena Hotel',
                'slug' => 'zanzibar-serena-hotel',
                'description' => 'A luxury beachfront hotel in Stone Town, combining historic architecture with modern amenities. Perfect for exploring Zanzibar\'s culture and beaches.',
                'address' => 'Stone Town',
                'city' => 'Zanzibar',
                'country' => 'Tanzania',
                'phone' => '+255 24 223 3051',
                'email' => 'zanzibar@serenahotels.com',
                'website' => 'https://www.serenahotels.com',
                'star_rating' => 5,
                'total_rooms' => 51,
                'amenities' => ['WiFi', 'Swimming Pool', 'Restaurant', 'Bar', 'Spa', 'Beach Access', 'Diving', 'Cultural Tours', 'Gift Shop'],
                'image_url' => 'images/hotels/zanzibar-serena-hotel.jpg',
                'is_active' => true,
            ],
            [
                'name' => 'The Residence Zanzibar',
                'slug' => 'the-residence-zanzibar',
                'description' => 'Ultra-luxury beachfront resort with private villas, world-class spa, multiple restaurants, and pristine white sand beaches. Perfect for honeymooners.',
                'address' => 'Kizimkazi Beach',
                'city' => 'Zanzibar',
                'country' => 'Tanzania',
                'phone' => '+255 24 224 0000',
                'email' => 'reservations@theresidence.com',
                'website' => 'https://www.theresidence.com/zanzibar',
                'star_rating' => 5,
                'total_rooms' => 66,
                'amenities' => ['WiFi', 'Private Pool', 'Spa', 'Restaurant', 'Bar', 'Beach Access', 'Diving', 'Snorkeling', 'Water Sports', 'Kids Club', 'Gift Shop'],
                'image_url' => 'images/hotels/the-residence-zanzibar.jpg',
                'is_active' => true,
            ],
            [
                'name' => 'Zanzibar White Sand Luxury Villas & Spa',
                'slug' => 'zanzibar-white-sand-luxury-villas',
                'description' => 'Exclusive beachfront resort with private villas, infinity pools, spa, and direct access to pristine beaches. Ideal for romantic getaways.',
                'address' => 'Paje Beach',
                'city' => 'Zanzibar',
                'country' => 'Tanzania',
                'phone' => '+255 24 224 0000',
                'email' => 'info@zanzibarwhitesand.com',
                'website' => 'https://www.zanzibarwhitesand.com',
                'star_rating' => 5,
                'total_rooms' => 25,
                'amenities' => ['WiFi', 'Private Pool', 'Infinity Pool', 'Spa', 'Restaurant', 'Bar', 'Beach Access', 'Diving', 'Snorkeling', 'Water Sports', 'Gift Shop'],
                'image_url' => 'images/hotels/zanzibar-white-sand.jpg',
                'is_active' => true,
            ],
            [
                'name' => 'Zanzibar Beach Resort',
                'slug' => 'zanzibar-beach-resort',
                'description' => 'A comfortable beachfront resort offering good value, spacious rooms, multiple pools, and easy access to water activities and cultural sites.',
                'address' => 'Nungwi Beach',
                'city' => 'Zanzibar',
                'country' => 'Tanzania',
                'phone' => '+255 24 224 0000',
                'email' => 'info@zanzibarbeachresort.com',
                'website' => 'https://www.zanzibarbeachresort.com',
                'star_rating' => 4,
                'total_rooms' => 120,
                'amenities' => ['WiFi', 'Swimming Pool', 'Restaurant', 'Bar', 'Beach Access', 'Diving', 'Snorkeling', 'Water Sports', 'Gift Shop', 'Parking'],
                'image_url' => 'images/hotels/zanzibar-beach-resort.jpg',
                'is_active' => true,
            ],

            // Dar es Salaam Hotels
            [
                'name' => 'Hyatt Regency Dar es Salaam',
                'slug' => 'hyatt-regency-dar-es-salaam',
                'description' => 'A modern 5-star hotel in the heart of Dar es Salaam, featuring luxury accommodations, multiple dining options, spa, and business facilities.',
                'address' => 'Kivukoni Front',
                'city' => 'Dar es Salaam',
                'country' => 'Tanzania',
                'phone' => '+255 22 213 1234',
                'email' => 'dar.regency@hyatt.com',
                'website' => 'https://www.hyatt.com',
                'star_rating' => 5,
                'total_rooms' => 200,
                'amenities' => ['WiFi', 'Swimming Pool', 'Spa', 'Restaurant', 'Bar', 'Fitness Center', 'Business Center', 'Conference Rooms', 'Airport Shuttle', 'Parking'],
                'image_url' => 'images/hotels/hyatt-regency-dar.jpg',
                'is_active' => true,
            ],
            [
                'name' => 'Southern Sun Dar es Salaam',
                'slug' => 'southern-sun-dar-es-salaam',
                'description' => 'A comfortable 4-star hotel offering modern rooms, good facilities, and convenient location for business and leisure travelers.',
                'address' => 'Garden Avenue',
                'city' => 'Dar es Salaam',
                'country' => 'Tanzania',
                'phone' => '+255 22 213 7575',
                'email' => 'info@southernsun.com',
                'website' => 'https://www.southernsun.com',
                'star_rating' => 4,
                'total_rooms' => 150,
                'amenities' => ['WiFi', 'Swimming Pool', 'Restaurant', 'Bar', 'Fitness Center', 'Business Center', 'Conference Rooms', 'Airport Shuttle', 'Parking'],
                'image_url' => 'images/hotels/southern-sun-dar.jpg',
                'is_active' => true,
            ],
        ];

        foreach ($hotels as $hotelData) {
            // Generate slug if not provided
            if (empty($hotelData['slug'])) {
                $hotelData['slug'] = Str::slug($hotelData['name']);
            }

            // Ensure slug is unique
            $baseSlug = $hotelData['slug'];
            $counter = 1;
            while (Hotel::where('slug', $hotelData['slug'])->exists()) {
                $hotelData['slug'] = $baseSlug . '-' . $counter;
                $counter++;
            }

            Hotel::create($hotelData);
        }

        $this->command->info('Successfully seeded ' . count($hotels) . ' hotels!');
    }
}





