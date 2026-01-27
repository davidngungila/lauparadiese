# Role-Based Access Control System Documentation

## Overview

A comprehensive role-based access control (RBAC) system has been implemented for the Tourism Company management system. The system includes 8 distinct user roles with specific permissions and custom dashboards.

## User Roles

### 1. System Administrator (Super Admin)
- **Slug**: `system-administrator`
- **Description**: Highest-level role with full control over the system
- **Permissions**: All permissions
- **Access**: Full access to all modules, system settings, user management, backups, and security

### 2. Travel Consultant / Tour Agent
- **Slug**: `travel-consultant`
- **Description**: Handles customer interactions and bookings
- **Permissions**: 
  - View and manage bookings
  - Create custom itineraries
  - Respond to customer inquiries
  - Generate invoices & vouchers
  - Assign drivers/hotels for bookings

### 3. Reservations Officer / Booking Manager
- **Slug**: `reservations-officer`
- **Description**: Responsible for booking operations
- **Permissions**:
  - Confirm/Cancel bookings
  - Check availability
  - Manage hotel or lodge reservations
  - Manage transport assignments
  - Update booking status
  - Verify payments

### 4. Finance Officer / Accountant
- **Slug**: `finance-officer`
- **Description**: Handles financial activities
- **Permissions**:
  - View all payments
  - Approve/refund payments
  - Generate financial reports
  - Manage invoices & receipts
  - Monitor revenue, expenses, profit
  - Export reports (PDF/Excel)

### 5. Content Manager (Web Content Editor)
- **Slug**: `content-manager`
- **Description**: Maintains the website content
- **Permissions**:
  - Add/Update/Delete: Tours, Destinations, Blog posts, FAQ, Galleries
  - Edit homepage sliders
  - SEO settings

### 6. Driver / Guide (Operational Staff)
- **Slug**: `driver-guide`
- **Description**: Operational staff for transport services or safari guides
- **Permissions**:
  - View assigned trips
  - See customer details
  - Update trip status (picked, dropped, in-progress)
  - Upload reports/photos

### 7. Hotel Partner / Supplier
- **Slug**: `hotel-partner`
- **Description**: B2B partner for hotels, drivers, or activity partners
- **Permissions**:
  - Manage their properties/rooms
  - Set availability and prices
  - View bookings assigned to them
  - Upload documents (insurance, licenses)

### 8. Customer / Tourist (Frontend User)
- **Slug**: `customer`
- **Description**: The person booking the trips
- **Permissions**:
  - Create account
  - Search and book tours
  - Pay online
  - View booking history
  - Download invoices & vouchers
  - Chat with travel consultant
  - Manage their profile

## Database Structure

### Tables Created
1. **roles** - Stores role information
   - id, name, slug, description, is_active, timestamps

2. **permissions** - Stores permission information
   - id, name, slug, description, module, timestamps

3. **role_permission** - Pivot table for role-permission relationships
   - id, role_id, permission_id, timestamps

4. **role_user** - Pivot table for user-role relationships (many-to-many)
   - id, user_id, role_id, timestamps

5. **users** table updated
   - Added `role_id` column for primary role assignment

## Models

### User Model
- `role()` - BelongsTo relationship to primary role
- `roles()` - BelongsToMany relationship to additional roles
- `hasRole($roleSlug)` - Check if user has a specific role
- `hasAnyRole($roleSlugs)` - Check if user has any of the given roles
- `hasPermission($permissionSlug)` - Check if user has a specific permission
- `isAdmin()` - Check if user is system administrator

### Role Model
- `users()` - BelongsToMany relationship to users
- `permissions()` - BelongsToMany relationship to permissions
- `hasPermission($permissionSlug)` - Check if role has a specific permission

### Permission Model
- `roles()` - BelongsToMany relationship to roles

## Middleware

### CheckRole Middleware
- **Location**: `app/Http/Middleware/CheckRole.php`
- **Usage**: `->middleware(['role:role1,role2'])`
- **Function**: Checks if authenticated user has any of the specified roles

## Routes Protection

Routes are protected using role-based middleware:

```php
// System Administrator only
Route::middleware(['role:system-administrator'])->group(function () {
    // Routes here
});

// Multiple roles
Route::middleware(['role:system-administrator,content-manager'])->group(function () {
    // Routes here
});
```

## Admin Dashboard

### Layout
- **Location**: `resources/views/admin/layouts/app.blade.php`
- **Template**: AdminKit Bootstrap 5 Admin Template
- **Features**:
  - Responsive sidebar navigation
  - Role-specific menu items
  - Notification dropdown
  - User profile dropdown
  - Alert messages for success/error

### Sidebar Navigation
Role-specific sidebar partials are located in:
- `resources/views/admin/partials/sidebar/`
- Each role has its own sidebar file (admin.blade.php, consultant.blade.php, etc.)

### Dashboards
Role-specific dashboard views are located in:
- `resources/views/admin/dashboards/`
- Each role has its own dashboard with relevant statistics and data

## Setup Instructions

### 1. Run Migrations
```bash
php artisan migrate
```

### 2. Seed Roles and Permissions
```bash
php artisan db:seed --class=RolePermissionSeeder
```

Or run all seeders:
```bash
php artisan db:seed
```

### 3. Assign Roles to Users
You can assign roles to users in several ways:

**Via Migration/Seeder:**
```php
$user = User::find(1);
$role = Role::where('slug', 'system-administrator')->first();
$user->role_id = $role->id;
$user->save();
```

**Via Code:**
```php
$user = User::find(1);
$role = Role::where('slug', 'travel-consultant')->first();
$user->roles()->attach($role->id);
```

### 4. Access Admin Dashboard
- Navigate to `/admin/dashboard`
- Must be authenticated
- Dashboard content changes based on user's role

## Usage Examples

### Check User Role in Controller
```php
if (auth()->user()->hasRole('system-administrator')) {
    // Admin-only code
}
```

### Check User Permission in Controller
```php
if (auth()->user()->hasPermission('manage-tours')) {
    // User can manage tours
}
```

### Check Role in Blade Template
```blade
@if(auth()->user()->hasRole('system-administrator'))
    <a href="{{ route('admin.settings.index') }}">Settings</a>
@endif
```

### Protect Route with Role
```php
Route::middleware(['auth', 'role:system-administrator'])->group(function () {
    Route::get('/admin/settings', [SettingsController::class, 'index']);
});
```

## File Structure

```
app/
├── Http/
│   ├── Controllers/
│   │   └── Admin/
│   │       ├── DashboardController.php
│   │       └── BookingController.php
│   └── Middleware/
│       └── CheckRole.php
├── Models/
│   ├── User.php (updated)
│   ├── Role.php
│   └── Permission.php
database/
├── migrations/
│   ├── 2025_11_25_131505_add_role_id_to_users_table.php
│   ├── 2025_11_25_131508_create_roles_table.php
│   ├── 2025_11_25_131512_create_permissions_table.php
│   ├── 2025_11_25_131517_create_role_permission_table.php
│   └── 2025_11_25_131558_create_role_user_table.php
└── seeders/
    └── RolePermissionSeeder.php
resources/
└── views/
    └── admin/
        ├── layouts/
        │   └── app.blade.php
        ├── dashboards/
        │   ├── admin.blade.php
        │   ├── consultant.blade.php
        │   ├── reservations.blade.php
        │   ├── finance.blade.php
        │   ├── content.blade.php
        │   ├── driver.blade.php
        │   ├── hotel.blade.php
        │   └── customer.blade.php
        └── partials/
            └── sidebar/
                ├── admin.blade.php
                ├── consultant.blade.php
                ├── reservations.blade.php
                ├── finance.blade.php
                ├── content.blade.php
                ├── driver.blade.php
                ├── hotel.blade.php
                └── customer.blade.php
```

## AdminKit Assets

The AdminKit template assets are located in:
- `public/assets/css/app.css`
- `public/assets/js/app.js`
- `public/assets/img/` (avatars, icons, photos)

All assets are properly referenced in the admin layout.

## Next Steps

1. **Implement Controller Methods**: Complete the implementation of controller methods for bookings, tours, users, etc.

2. **Add Authentication Routes**: Ensure login/logout routes are properly set up if not already done.

3. **Customize Dashboards**: Update dashboard views with real data from your database.

4. **Add More Permissions**: Extend the permission system as needed for your specific requirements.

5. **Testing**: Test each role's access to ensure proper restrictions are in place.

## Notes

- The system supports both primary role (via `role_id`) and additional roles (via `role_user` pivot table)
- Permissions are checked through roles, not directly on users
- The middleware redirects unauthorized users to the dashboard with an error message
- All admin routes are prefixed with `/admin` and require authentication






