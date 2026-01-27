<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class TourismRolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Create Permissions
        $permissions = [
            // Dashboard
            'view dashboard',
            
            // Tours & Packages
            'view tours', 'create tours', 'edit tours', 'delete tours',
            'view itineraries', 'create itineraries', 'edit itineraries', 'delete itineraries',
            'view destinations', 'create destinations', 'edit destinations', 'delete destinations',
            'view tour categories', 'create tour categories', 'edit tour categories', 'delete tour categories',
            'manage promotions',
            
            // Bookings
            'view bookings', 'create bookings', 'edit bookings', 'delete bookings',
            'confirm bookings', 'cancel bookings', 'view group bookings', 'view agent bookings',
            
            // Clients
            'view clients', 'create clients', 'edit clients', 'delete clients',
            'view client history', 'view client feedback',
            
            // Tickets & Documents
            'view tickets', 'create tickets', 'edit tickets', 'delete tickets',
            'view travel documents', 'upload documents', 'track documents',
            'manage visas', 'manage travel insurance',
            
            // Accommodation
            'view hotels', 'create hotels', 'edit hotels', 'delete hotels',
            'view hotel contracts', 'manage hotel rates', 'view hotel bookings',
            
            // Transport & Fleet
            'view vehicles', 'create vehicles', 'edit vehicles', 'delete vehicles',
            'view drivers', 'create drivers', 'edit drivers', 'delete drivers',
            'manage vehicle scheduling', 'view maintenance logs', 'track fuel',
            'plan routes',
            
            // Partners & Suppliers
            'view partners', 'create partners', 'edit partners', 'delete partners',
            'manage travel agents', 'manage suppliers',
            
            // Finance
            'view payments', 'receive payments', 'view invoices', 'create invoices',
            'edit invoices', 'delete invoices', 'view receipts', 'process refunds',
            'view expenses', 'create expenses', 'edit expenses', 'delete expenses',
            'view financial reports',
            
            // Quotations
            'view quotations', 'create quotations', 'edit quotations', 'delete quotations',
            'approve quotations', 'convert quotations',
            
            // Tour Operations
            'view tour operations', 'assign guides', 'assign drivers',
            'view tour checklists', 'create tour logs', 'view attendance',
            
            // HR
            'view staff', 'create staff', 'edit staff', 'delete staff',
            'view departments', 'manage attendance', 'manage leave',
            'view payroll', 'manage roles',
            
            // Reports
            'view booking reports', 'view revenue reports', 'view customer reports',
            'view tour performance', 'view expense reports', 'view agent reports',
            'view visa reports', 'view ticket reports',
            
            // Communication
            'send sms', 'send emails', 'view notifications', 'manage campaigns',
            'view support tickets', 'manage chat',
            
            // Content & Blog
            'view posts', 'create posts', 'edit posts', 'delete posts',
            'view galleries', 'manage galleries', 'view customer stories',
            'manage banners',
            
            // Settings
            'manage company profile', 'manage taxes', 'manage payment methods',
            'manage system settings', 'manage users', 'backup system',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission, 'guard_name' => 'web']);
        }

        // Create Roles and assign permissions
        $roles = [
            'System Administrator' => Permission::all(),
            
            'Travel Consultant' => [
                'view dashboard', 'view tours', 'view bookings', 'create bookings', 'edit bookings',
                'view clients', 'create clients', 'edit clients', 'view client history',
                'view quotations', 'create quotations', 'edit quotations', 'approve quotations',
                'convert quotations', 'view tickets', 'create tickets', 'view travel documents',
                'view invoices', 'create invoices', 'send emails', 'send sms',
            ],
            
            'Reservations Officer' => [
                'view dashboard', 'view bookings', 'create bookings', 'edit bookings',
                'confirm bookings', 'cancel bookings', 'view clients', 'view client history',
                'view tickets', 'create tickets', 'edit tickets', 'view travel documents',
                'upload documents', 'view hotel bookings', 'view invoices', 'view receipts',
            ],
            
            'Finance Officer' => [
                'view dashboard', 'view payments', 'receive payments', 'view invoices',
                'create invoices', 'edit invoices', 'view receipts', 'process refunds',
                'view expenses', 'create expenses', 'edit expenses', 'view financial reports',
                'view booking reports', 'view revenue reports', 'view expense reports',
            ],
            
            'Content Manager' => [
                'view dashboard', 'view posts', 'create posts', 'edit posts', 'delete posts',
                'view galleries', 'manage galleries', 'view customer stories', 'manage banners',
                'view tours', 'edit tours', 'view destinations', 'edit destinations',
            ],
            
            'Driver/Guide' => [
                'view dashboard', 'view tour operations',
                'create tour logs', 'view attendance', 'view vehicles',
            ],
            
            'Hotel Partner' => [
                'view dashboard', 'view hotels', 'edit hotels', 'view hotel bookings',
                'manage hotel rates',
            ],
            
            'Customer' => [
                'view dashboard', 'view tours', 'create bookings', 'view bookings',
                'view invoices', 'view receipts',
            ],
        ];

        foreach ($roles as $roleName => $rolePermissions) {
            $role = Role::firstOrCreate(['name' => $roleName, 'guard_name' => 'web']);
            if (is_array($rolePermissions)) {
                $role->syncPermissions($rolePermissions);
            } else {
                $role->syncPermissions($rolePermissions);
            }
        }
    }
}
