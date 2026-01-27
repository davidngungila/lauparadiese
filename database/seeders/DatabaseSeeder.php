<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Seed roles and permissions first
        $this->call([
            TourismRolePermissionSeeder::class,
            ComprehensiveUserSeeder::class, // Use comprehensive seeder instead
            HeroSlideSeeder::class, // Hero Slider Slides
            SmsGatewaySeeder::class,
            NotificationProviderSeeder::class,
            PaymentGatewaySeeder::class, // Payment gateways (Pesapal, Stripe, PayPal)
            TourCategorySeeder::class, // Tour Categories
            HomepageDestinationSeeder::class, // Homepage Destinations
            TanzaniaSpecialistToursSeeder::class, // Tour Packages
            TourItinerarySeeder::class, // Tour Itineraries
            HotelSeeder::class, // Hotels
        ]);
    }
}
