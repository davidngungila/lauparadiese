<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create Permissions
        $permissions = [
            // User Management
            ['name' => 'Manage Users', 'slug' => 'manage-users', 'module' => 'users'],
            ['name' => 'Manage Roles', 'slug' => 'manage-roles', 'module' => 'users'],
            ['name' => 'View Users', 'slug' => 'view-users', 'module' => 'users'],
            
            // Bookings
            ['name' => 'View All Bookings', 'slug' => 'view-all-bookings', 'module' => 'bookings'],
            ['name' => 'Manage Bookings', 'slug' => 'manage-bookings', 'module' => 'bookings'],
            ['name' => 'Create Bookings', 'slug' => 'create-bookings', 'module' => 'bookings'],
            ['name' => 'Confirm Bookings', 'slug' => 'confirm-bookings', 'module' => 'bookings'],
            ['name' => 'Cancel Bookings', 'slug' => 'cancel-bookings', 'module' => 'bookings'],
            ['name' => 'View Own Bookings', 'slug' => 'view-own-bookings', 'module' => 'bookings'],
            
            // Tours & Content
            ['name' => 'Manage Tours', 'slug' => 'manage-tours', 'module' => 'tours'],
            ['name' => 'Manage Destinations', 'slug' => 'manage-destinations', 'module' => 'tours'],
            ['name' => 'Manage Blog Posts', 'slug' => 'manage-posts', 'module' => 'content'],
            ['name' => 'Manage FAQ', 'slug' => 'manage-faq', 'module' => 'content'],
            ['name' => 'Manage Galleries', 'slug' => 'manage-galleries', 'module' => 'content'],
            ['name' => 'Manage Homepage', 'slug' => 'manage-homepage', 'module' => 'content'],
            ['name' => 'Manage SEO', 'slug' => 'manage-seo', 'module' => 'content'],
            
            // Financial
            ['name' => 'View All Payments', 'slug' => 'view-all-payments', 'module' => 'finance'],
            ['name' => 'Approve Payments', 'slug' => 'approve-payments', 'module' => 'finance'],
            ['name' => 'Refund Payments', 'slug' => 'refund-payments', 'module' => 'finance'],
            ['name' => 'Generate Reports', 'slug' => 'generate-reports', 'module' => 'finance'],
            ['name' => 'Manage Invoices', 'slug' => 'manage-invoices', 'module' => 'finance'],
            
            // Operations
            ['name' => 'Manage Transport', 'slug' => 'manage-transport', 'module' => 'operations'],
            ['name' => 'Manage Hotels', 'slug' => 'manage-hotels', 'module' => 'operations'],
            ['name' => 'Assign Drivers', 'slug' => 'assign-drivers', 'module' => 'operations'],
            ['name' => 'View Assigned Trips', 'slug' => 'view-assigned-trips', 'module' => 'operations'],
            ['name' => 'Update Trip Status', 'slug' => 'update-trip-status', 'module' => 'operations'],
            
            // System
            ['name' => 'System Settings', 'slug' => 'system-settings', 'module' => 'system'],
            ['name' => 'Payment Settings', 'slug' => 'payment-settings', 'module' => 'system'],
            ['name' => 'Manage Backups', 'slug' => 'manage-backups', 'module' => 'system'],
            ['name' => 'Delete Data', 'slug' => 'delete-data', 'module' => 'system'],
            
            // Hotel Partner
            ['name' => 'Manage Properties', 'slug' => 'manage-properties', 'module' => 'hotel'],
            ['name' => 'Set Availability', 'slug' => 'set-availability', 'module' => 'hotel'],
            ['name' => 'Manage Prices', 'slug' => 'manage-prices', 'module' => 'hotel'],
            ['name' => 'View Hotel Bookings', 'slug' => 'view-hotel-bookings', 'module' => 'hotel'],
            
            // Customer
            ['name' => 'Book Tours', 'slug' => 'book-tours', 'module' => 'customer'],
            ['name' => 'View Own Profile', 'slug' => 'view-own-profile', 'module' => 'customer'],
            ['name' => 'Chat Support', 'slug' => 'chat-support', 'module' => 'customer'],
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(
                ['slug' => $permission['slug']],
                $permission
            );
        }

        // Create Roles
        $roles = [
            [
                'name' => 'System Administrator',
                'slug' => 'system-administrator',
                'description' => 'Highest-level role with full control over the system',
                'permissions' => ['*'], // All permissions
            ],
            [
                'name' => 'Travel Consultant',
                'slug' => 'travel-consultant',
                'description' => 'Handles customer interactions and bookings',
                'permissions' => [
                    'view-all-bookings', 'manage-bookings', 'create-bookings',
                    'view-users', 'manage-invoices', 'book-tours',
                ],
            ],
            [
                'name' => 'Reservations Officer',
                'slug' => 'reservations-officer',
                'description' => 'Responsible for booking operations',
                'permissions' => [
                    'view-all-bookings', 'confirm-bookings', 'cancel-bookings',
                    'manage-transport', 'manage-hotels', 'assign-drivers',
                ],
            ],
            [
                'name' => 'Finance Officer',
                'slug' => 'finance-officer',
                'description' => 'Handles financial activities',
                'permissions' => [
                    'view-all-payments', 'approve-payments', 'refund-payments',
                    'generate-reports', 'manage-invoices',
                ],
            ],
            [
                'name' => 'Content Manager',
                'slug' => 'content-manager',
                'description' => 'Maintains website content',
                'permissions' => [
                    'manage-tours', 'manage-destinations', 'manage-posts',
                    'manage-faq', 'manage-galleries', 'manage-homepage', 'manage-seo',
                ],
            ],
            [
                'name' => 'Driver / Guide',
                'slug' => 'driver-guide',
                'description' => 'Operational staff for transport and guides',
                'permissions' => [
                    'view-assigned-trips', 'update-trip-status', 'view-own-profile',
                ],
            ],
            [
                'name' => 'Hotel Partner',
                'slug' => 'hotel-partner',
                'description' => 'B2B partner for hotels and lodges',
                'permissions' => [
                    'manage-properties', 'set-availability', 'manage-prices',
                    'view-hotel-bookings', 'view-own-profile',
                ],
            ],
            [
                'name' => 'Customer / Tourist',
                'slug' => 'customer',
                'description' => 'Frontend user booking trips',
                'permissions' => [
                    'book-tours', 'view-own-bookings', 'view-own-profile', 'chat-support',
                ],
            ],
        ];

        foreach ($roles as $roleData) {
            $permissions = $roleData['permissions'];
            unset($roleData['permissions']);

            $role = Role::firstOrCreate(
                ['slug' => $roleData['slug']],
                $roleData
            );

            // Attach permissions
            if ($permissions[0] === '*') {
                // Attach all permissions for admin
                $role->permissions()->sync(Permission::pluck('id'));
            } else {
                $permissionIds = Permission::whereIn('slug', $permissions)->pluck('id');
                $role->permissions()->sync($permissionIds);
            }
        }

        $this->command->info('Roles and permissions seeded successfully!');
    }
}
