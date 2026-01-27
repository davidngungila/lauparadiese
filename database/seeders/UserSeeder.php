<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create users for each role using Spatie
        $users = [
            [
                'name' => 'System Administrator',
                'email' => 'admin@tourism.com',
                'password' => 'password',
                'role' => 'System Administrator',
            ],
            [
                'name' => 'Travel Consultant',
                'email' => 'consultant@tourism.com',
                'password' => 'password',
                'role' => 'Travel Consultant',
            ],
            [
                'name' => 'Reservations Officer',
                'email' => 'reservations@tourism.com',
                'password' => 'password',
                'role' => 'Reservations Officer',
            ],
            [
                'name' => 'Finance Officer',
                'email' => 'finance@tourism.com',
                'password' => 'password',
                'role' => 'Finance Officer',
            ],
            [
                'name' => 'Content Manager',
                'email' => 'content@tourism.com',
                'password' => 'password',
                'role' => 'Content Manager',
            ],
            [
                'name' => 'Driver Guide',
                'email' => 'driver@tourism.com',
                'password' => 'password',
                'role' => 'Driver/Guide',
            ],
            [
                'name' => 'Hotel Partner',
                'email' => 'hotel@tourism.com',
                'password' => 'password',
                'role' => 'Hotel Partner',
            ],
            [
                'name' => 'Customer User',
                'email' => 'customer@tourism.com',
                'password' => 'password',
                'role' => 'Customer',
            ],
        ];

        foreach ($users as $userData) {
            $role = Role::where('name', $userData['role'])->first();
            
            if ($role) {
                $user = User::firstOrCreate(
                    ['email' => $userData['email']],
                    [
                        'name' => $userData['name'],
                        'email' => $userData['email'],
                        'password' => Hash::make($userData['password']),
                    ]
                );

                // Assign role using Spatie
                if (!$user->hasRole($role->name)) {
                    $user->assignRole($role);
                }

                $this->command->info("Created user: {$userData['email']} with role: {$userData['role']}");
            } else {
                $this->command->warn("Role '{$userData['role']}' not found. Skipping user creation.");
            }
        }

        $this->command->info('All users seeded successfully!');
        $this->command->info('Default password for all users: password');
    }
}
