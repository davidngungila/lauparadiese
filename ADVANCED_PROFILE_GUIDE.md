# Advanced User Profile System Guide

## Overview

A comprehensive user profile system with image upload, initials fallback, timeline, and profile management features.

## Features Implemented

### 1. Database Structure

**Migration**: `add_avatar_to_users_table`
- `avatar` - Profile image path
- `phone` - Phone number
- `mobile` - Mobile number
- `address` - Full address
- `city` - City
- `country` - Country
- `date_of_birth` - Date of birth
- `bio` - Biography/About section
- `social_links` - JSON field for social media links (Facebook, Twitter, LinkedIn, Instagram)

### 2. User Model Enhancements

**New Methods**:
- `getInitialsAttribute()` - Generates initials from name (e.g., "David Ngungila" → "DN")
- `getAvatarUrlAttribute()` - Returns avatar URL or empty string

**New Fillable Fields**:
All profile fields added to `$fillable` array

**Casts**:
- `date_of_birth` → date
- `social_links` → array

### 3. Profile Controller Methods

**DashboardController**:
- `profile()` - Display profile page with user data and bookings
- `updateProfile()` - Update profile information including avatar upload
- `updatePassword()` - Change user password

### 4. Profile View Features

#### Three Main Tabs:

1. **Timeline Tab**
   - Shows user's booking history
   - Displays booking details with status badges
   - Links to booking details
   - Empty state when no bookings

2. **Profile Tab**
   - User information display
   - Account statistics
   - Progress bars for account metrics
   - About section (if bio exists)

3. **Settings Tab**
   - Full profile edit form
   - Avatar upload with preview
   - All profile fields editable
   - Social media links
   - Password change form

#### Avatar System:

**Initials Fallback**:
- If no avatar uploaded, displays initials in a colored circle
- Example: "David Ngungila" → "DN"
- Single name: First 2 letters
- Multiple names: First letter of first + first letter of last

**Avatar Upload**:
- Click camera icon on avatar to upload
- Automatic form submission on file select
- Old avatar deleted when new one uploaded
- Stored in `storage/app/public/avatars/`

### 5. Routes

```php
GET  /admin/profile              - View profile
PUT  /admin/profile              - Update profile
PUT  /admin/profile/password     - Update password
```

### 6. Color Scheme

Uses the green color scheme from home.blade.php:
- Primary Green: `#1a4d3a`
- Accent Green: `#3ea572`
- Secondary Green: `#2d7a5f`
- Light Green: `#e8f5f0`

### 7. Navbar Integration

- Avatar with initials shown in navbar
- Dropdown menu shows avatar/initials
- Consistent styling across the application

## Usage

### Upload Avatar

1. Go to Profile page
2. Click camera icon on avatar
3. Select image file
4. Image uploads automatically

### Update Profile

1. Go to Profile page
2. Click "Settings" tab
3. Fill in profile information
4. Click "Update Profile"

### Change Password

1. Go to Profile page
2. Click "Settings" tab
3. Scroll to "Change Password" section
4. Enter current and new password
5. Click "Update Password"

## File Structure

```
app/
  Models/
    User.php (updated)
  Http/Controllers/Admin/
    DashboardController.php (updated)

database/migrations/
  2025_11_26_100708_add_avatar_to_users_table.php

resources/views/admin/
  profile/
    index.blade.php (new)
  layouts/
    app.blade.php (updated)
```

## Styling Features

- Gradient backgrounds for initials
- Smooth animations
- Responsive design
- Professional card layouts
- Color-coded status badges
- Interactive hover effects
- Social media button styling

## Next Steps

1. Run migration: `php artisan migrate`
2. Create storage link: `php artisan storage:link`
3. Test avatar upload
4. Update profile information
5. Test password change

## Notes

- Avatar images are stored in `storage/app/public/avatars/`
- Initials are automatically generated from user's name
- Social links are stored as JSON in database
- All form validations are in place
- Password change requires current password verification





