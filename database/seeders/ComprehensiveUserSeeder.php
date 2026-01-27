<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class ComprehensiveUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('🌱 Seeding comprehensive user data...');
        $this->command->newLine();

        // System Administrator (Multiple)
        $this->createUser([
            'name' => 'System Administrator',
            'email' => 'admin@tourism.com',
            'password' => 'Admin@2024!',
            'phone' => '+255 754 123 456',
            'role' => 'System Administrator',
            'email_verified_at' => now(),
        ], '🔐 System Administrator');

        $this->createUser([
            'name' => 'System Administrator',
            'email' => 'admin@lauparadiseadventures.com',
            'password' => 'Admin@2024!',
            'phone' => '+255 754 123 457',
            'role' => 'System Administrator',
            'email_verified_at' => now(),
        ], '🔐 System Administrator');

        // Travel Consultants (Multiple)
        $consultants = [
            [
                'name' => 'Travel Consultant',
                'email' => 'consultant@tourism.com',
                'phone' => '+255 754 111 110',
            ],
            [
                'name' => 'Sarah Johnson',
                'email' => 'sarah.johnson@lauparadiseadventures.com',
                'phone' => '+255 754 111 111',
            ],
            [
                'name' => 'Michael Chen',
                'email' => 'michael.chen@lauparadiseadventures.com',
                'phone' => '+255 754 111 112',
            ],
            [
                'name' => 'Amina Hassan',
                'email' => 'amina.hassan@lauparadiseadventures.com',
                'phone' => '+255 754 111 113',
            ],
        ];

        foreach ($consultants as $consultant) {
            $this->createUser([
                'name' => $consultant['name'],
                'email' => $consultant['email'],
                'password' => 'Consultant@2024!',
                'phone' => $consultant['phone'],
                'role' => 'Travel Consultant',
                'email_verified_at' => now(),
            ], '👔 Travel Consultant');
        }

        // Reservations Officers (Multiple)
        $reservations = [
            [
                'name' => 'Reservations Officer',
                'email' => 'reservations@tourism.com',
                'phone' => '+255 754 222 220',
            ],
            [
                'name' => 'David Ngungila',
                'email' => 'david.ngungila@lauparadiseadventures.com',
                'phone' => '+255 754 222 221',
            ],
            [
                'name' => 'Grace Mwangi',
                'email' => 'grace.mwangi@lauparadiseadventures.com',
                'phone' => '+255 754 222 222',
            ],
        ];

        foreach ($reservations as $reservation) {
            $this->createUser([
                'name' => $reservation['name'],
                'email' => $reservation['email'],
                'password' => 'Reservations@2024!',
                'phone' => $reservation['phone'],
                'role' => 'Reservations Officer',
                'email_verified_at' => now(),
            ], '📅 Reservations Officer');
        }

        // Finance Officers (Multiple)
        $finance = [
            [
                'name' => 'Finance Officer',
                'email' => 'finance@tourism.com',
                'phone' => '+255 754 333 330',
            ],
            [
                'name' => 'James Kimathi',
                'email' => 'james.kimathi@lauparadiseadventures.com',
                'phone' => '+255 754 333 331',
            ],
            [
                'name' => 'Patricia Ochieng',
                'email' => 'patricia.ochieng@lauparadiseadventures.com',
                'phone' => '+255 754 333 332',
            ],
        ];

        foreach ($finance as $fin) {
            $this->createUser([
                'name' => $fin['name'],
                'email' => $fin['email'],
                'password' => 'Finance@2024!',
                'phone' => $fin['phone'],
                'role' => 'Finance Officer',
                'email_verified_at' => now(),
            ], '💰 Finance Officer');
        }

        // Content Manager
        $this->createUser([
            'name' => 'Content Manager',
            'email' => 'content@tourism.com',
            'password' => 'Content@2024!',
            'phone' => '+255 754 444 440',
            'role' => 'Content Manager',
            'email_verified_at' => now(),
        ], '📝 Content Manager');

        $this->createUser([
            'name' => 'Emma Wilson',
            'email' => 'emma.wilson@lauparadiseadventures.com',
            'password' => 'Content@2024!',
            'phone' => '+255 754 444 441',
            'role' => 'Content Manager',
            'email_verified_at' => now(),
        ], '📝 Content Manager');

        // Marketing Officer
        $this->createUser([
            'name' => 'Lisa Anderson',
            'email' => 'lisa.anderson@lauparadiseadventures.com',
            'password' => 'Marketing@2024!',
            'phone' => '+255 754 444 442',
            'role' => 'Marketing Officer',
            'email_verified_at' => now(),
        ], '📢 Marketing Officer');

        // ICT Officer
        $this->createUser([
            'name' => 'Tech Support',
            'email' => 'ict@lauparadiseadventures.com',
            'password' => 'ICT@2024!',
            'phone' => '+255 754 444 443',
            'role' => 'ICT Officer',
            'email_verified_at' => now(),
        ], '💻 ICT Officer');

        // Drivers/Guides (Multiple)
        $drivers = [
            [
                'name' => 'Driver Guide',
                'email' => 'driver@tourism.com',
                'phone' => '+255 754 555 550',
            ],
            [
                'name' => 'John Mwangi',
                'email' => 'john.mwangi@lauparadiseadventures.com',
                'phone' => '+255 754 555 551',
            ],
            [
                'name' => 'Peter Otieno',
                'email' => 'peter.otieno@lauparadiseadventures.com',
                'phone' => '+255 754 555 552',
            ],
            [
                'name' => 'Robert Kipchoge',
                'email' => 'robert.kipchoge@lauparadiseadventures.com',
                'phone' => '+255 754 555 553',
            ],
            [
                'name' => 'Daniel Simba',
                'email' => 'daniel.simba@lauparadiseadventures.com',
                'phone' => '+255 754 555 554',
            ],
        ];

        foreach ($drivers as $driver) {
            $this->createUser([
                'name' => $driver['name'],
                'email' => $driver['email'],
                'password' => 'Driver@2024!',
                'phone' => $driver['phone'],
                'role' => 'Driver/Guide',
                'email_verified_at' => now(),
            ], '🚗 Driver/Guide');
        }

        // Hotel Partners (Multiple)
        $hotels = [
            [
                'name' => 'Hotel Partner',
                'email' => 'hotel@tourism.com',
                'phone' => '+255 754 666 660',
            ],
            [
                'name' => 'Serengeti Safari Lodge',
                'email' => 'serengeti.lodge@lauparadiseadventures.com',
                'phone' => '+255 754 666 661',
            ],
            [
                'name' => 'Ngorongoro Crater Hotel',
                'email' => 'ngorongoro.hotel@lauparadiseadventures.com',
                'phone' => '+255 754 666 662',
            ],
            [
                'name' => 'Kilimanjaro View Resort',
                'email' => 'kilimanjaro.resort@lauparadiseadventures.com',
                'phone' => '+255 754 666 663',
            ],
        ];

        foreach ($hotels as $hotel) {
            $this->createUser([
                'name' => $hotel['name'],
                'email' => $hotel['email'],
                'password' => 'Hotel@2024!',
                'phone' => $hotel['phone'],
                'role' => 'Hotel Partner',
                'email_verified_at' => now(),
            ], '🏨 Hotel Partner');
        }

        // Test Customers (Multiple)
        $customers = [
            [
                'name' => 'John Smith',
                'email' => 'john.smith@example.com',
                'phone' => '+1 555 777 0001',
                'nationality' => 'American',
                'country' => 'United States',
            ],
            [
                'name' => 'Maria Garcia',
                'email' => 'maria.garcia@example.com',
                'phone' => '+34 555 777 0002',
                'nationality' => 'Spanish',
                'country' => 'Spain',
            ],
            [
                'name' => 'Hans Mueller',
                'email' => 'hans.mueller@example.com',
                'phone' => '+49 555 777 0003',
                'nationality' => 'German',
                'country' => 'Germany',
            ],
            [
                'name' => 'Yuki Tanaka',
                'email' => 'yuki.tanaka@example.com',
                'phone' => '+81 555 777 0004',
                'nationality' => 'Japanese',
                'country' => 'Japan',
            ],
            [
                'name' => 'Sophie Martin',
                'email' => 'sophie.martin@example.com',
                'phone' => '+33 555 777 0005',
                'nationality' => 'French',
                'country' => 'France',
            ],
            [
                'name' => 'David Thompson',
                'email' => 'david.thompson@example.com',
                'phone' => '+44 555 777 0006',
                'nationality' => 'British',
                'country' => 'United Kingdom',
            ],
        ];

        foreach ($customers as $customer) {
            $this->createUser([
                'name' => $customer['name'],
                'email' => $customer['email'],
                'password' => 'Customer@2024!',
                'phone' => $customer['phone'],
                'nationality' => $customer['nationality'],
                'country' => $customer['country'],
                'role' => 'Customer',
                'email_verified_at' => now(),
            ], '👤 Customer');
        }

        $this->command->newLine();
        $this->command->info('✅ All users seeded successfully!');
        $this->command->newLine();
        $this->displayCredentials();
    }

    /**
     * Create a user with role assignment
     */
    private function createUser(array $data, string $roleLabel): void
    {
        $role = Role::where('name', $data['role'])->first();
        
        if (!$role) {
            $this->command->warn("⚠️  Role '{$data['role']}' not found. Skipping user: {$data['email']}");
            return;
        }

        $user = User::firstOrCreate(
            ['email' => $data['email']],
            [
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => Hash::make($data['password']),
                'phone' => $data['phone'] ?? null,
                'nationality' => $data['nationality'] ?? null,
                'country' => $data['country'] ?? null,
                'email_verified_at' => $data['email_verified_at'] ?? null,
            ]
        );

        // Assign role using Spatie
        if (!$user->hasRole($role->name)) {
            $user->assignRole($role);
        }

        $this->command->line("   ✓ {$roleLabel}: {$data['name']} ({$data['email']})");
    }

    /**
     * Display login credentials
     */
    private function displayCredentials(): void
    {
        $this->command->info('📋 Login Credentials Summary:');
        $this->command->newLine();
        
        $credentials = [
            ['System Administrator', 'admin@tourism.com', 'Admin@2024!'],
            ['System Administrator', 'admin@lauparadiseadventures.com', 'Admin@2024!'],
            ['Travel Consultant', 'consultant@tourism.com', 'Consultant@2024!'],
            ['Travel Consultant', 'sarah.johnson@lauparadiseadventures.com', 'Consultant@2024!'],
            ['Reservations Officer', 'reservations@tourism.com', 'Reservations@2024!'],
            ['Reservations Officer', 'david.ngungila@lauparadiseadventures.com', 'Reservations@2024!'],
            ['Finance Officer', 'finance@tourism.com', 'Finance@2024!'],
            ['Finance Officer', 'james.kimathi@lauparadiseadventures.com', 'Finance@2024!'],
            ['Content Manager', 'content@tourism.com', 'Content@2024!'],
            ['Content Manager', 'emma.wilson@lauparadiseadventures.com', 'Content@2024!'],
            ['Marketing Officer', 'lisa.anderson@lauparadiseadventures.com', 'Marketing@2024!'],
            ['ICT Officer', 'ict@lauparadiseadventures.com', 'ICT@2024!'],
            ['Driver/Guide', 'driver@tourism.com', 'Driver@2024!'],
            ['Driver/Guide', 'john.mwangi@lauparadiseadventures.com', 'Driver@2024!'],
            ['Hotel Partner', 'hotel@tourism.com', 'Hotel@2024!'],
            ['Hotel Partner', 'serengeti.lodge@lauparadiseadventures.com', 'Hotel@2024!'],
            ['Customer', 'john.smith@example.com', 'Customer@2024!'],
        ];

        foreach ($credentials as $cred) {
            $this->command->line("   {$cred[0]}:");
            $this->command->line("      Email: {$cred[1]}");
            $this->command->line("      Password: {$cred[2]}");
            $this->command->newLine();
        }

        $this->command->warn('⚠️  Please change all passwords after first login!');
    }
}









