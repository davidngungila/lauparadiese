# Advanced Sidebar & Quotation System Guide

## Overview

This document describes the advanced sidebar with dropdown menus and the quotation generation system implemented in the Tour Booking System.

## Features Implemented

### 1. Advanced Sidebar with Dropdown Menus

All sidebar headers are now clickable dropdown menus that expand/collapse to show more options.

#### Implementation Details

- **CSS Styling**: Added custom CSS in `resources/views/admin/layouts/app.blade.php` for smooth dropdown animations
- **Bootstrap Collapse**: Uses Bootstrap's collapse component for dropdown functionality
- **Role-Based Sidebars**: Each role has its own customized sidebar with relevant dropdown menus

#### Sidebar Structure

Each sidebar section now follows this pattern:
```blade
<li class="sidebar-header">
    <a class="sidebar-link sidebar-header-link" data-bs-toggle="collapse" href="#menuId" role="button">
        <i class="align-middle" data-feather="icon"></i> 
        <span class="align-middle">Section Name</span>
        <i class="align-middle float-end" data-feather="chevron-down"></i>
    </a>
</li>
<li class="collapse" id="menuId">
    <ul class="sidebar-nav">
        <!-- Menu items here -->
    </ul>
</li>
```

#### Updated Sidebars

1. **Admin Sidebar** (`admin.blade.php`)
   - Main (Dashboard, Analytics, Reports, Notifications)
   - User Management (All Users, Add User, Roles & Permissions, Blocked Users, Activity Logs)
   - Content Management (Tours, Destinations, Blog Posts, Media Library, Categories)
   - Bookings & Operations (All Bookings, Status filters, Quotations, Transport, Hotels, Custom Itineraries)
   - Financial (Payments, Revenue Reports, Analytics, Invoices, Receipts, Payment Methods)
   - System (Settings, Backups, Payment Gateways, Email Settings, SMS Settings, SEO Settings, System Logs)

2. **Travel Consultant Sidebar** (`consultant.blade.php`)
   - Main (Dashboard, Performance)
   - Bookings (My Bookings, Create Booking, Quotations, Custom Itineraries, Status filters)
   - Customers (All Customers, Add Customer, Inquiries, Messages)
   - Documents (Invoices, Vouchers, Generate Quotation)

3. **Reservations Officer Sidebar** (`reservations.blade.php`)
   - Main (Dashboard)
   - Bookings (All Bookings, Status filters, Quotations)
   - Availability (Check Availability, Calendar View, Availability List)
   - Accommodation (Hotels & Lodges, Room Types, Room Availability)
   - Transport (Car Rentals, Shuttles, Transport Schedule)
   - Payments (Verify Payments, Payment Status)

4. **Finance Officer Sidebar** (`finance.blade.php`)
   - Main (Dashboard)
   - Payments (All Payments, Approve Payments, Refunds, Pending Payments)
   - Financial Reports (Revenue, Expenses, Profit & Loss, Analytics, Export Reports)
   - Documents (Invoices, Receipts, Quotations)

### 2. Quotation Generation System

A complete quotation system that automatically generates professional quotations from bookings.

#### Database Structure

**Quotations Table** (`quotations`)
- `id` - Primary key
- `booking_id` - Foreign key to bookings
- `quotation_number` - Unique quotation number (format: QT20250001)
- `customer_name`, `customer_email`, `customer_phone`, `customer_address`
- `tour_id`, `tour_name`
- `travelers`, `departure_date`, `duration_days`
- `tour_price`, `addons_total`, `discount`, `tax`, `total_price`
- `included`, `excluded`, `terms_conditions`, `notes`
- `valid_until` - Quotation expiry date
- `status` - draft, sent, accepted, rejected, expired
- `created_by` - User who created the quotation
- `timestamps`

#### Model Features

**Quotation Model** (`app/Models/Quotation.php`)
- Automatic quotation number generation
- Relationships: `booking()`, `tour()`, `creator()`
- Helper methods: `isExpired()`, `getSubtotalAttribute()`, `getFinalTotalAttribute()`
- Automatic calculation of subtotals and totals

#### Controller Features

**QuotationController** (`app/Http/Controllers/Admin/QuotationController.php`)

**Methods:**
1. `index()` - List all quotations
2. `create()` - Show form to create quotation
3. `generateFromBooking()` - Generate quotation from existing booking
4. `show()` - Display quotation details
5. `downloadPDF()` - Download quotation as PDF
6. `viewPDF()` - View quotation PDF in browser
7. `sendToCustomer()` - Send quotation via email and SMS
8. `updateStatus()` - Update quotation status

#### Routes

```php
// Generate quotation from booking
POST /admin/bookings/{booking}/generate-quotation

// Quotation management
GET  /admin/quotations
GET  /admin/quotations/create
GET  /admin/quotations/{quotation}
GET  /admin/quotations/{quotation}/pdf
GET  /admin/quotations/{quotation}/view
POST /admin/quotations/{quotation}/send
POST /admin/quotations/{quotation}/status
```

#### PDF Generation

**PDF Template** (`resources/views/admin/quotations/pdf.blade.php`)
- Professional quotation layout
- Company information header
- Customer details section
- Tour details section
- Itemized pricing table
- Subtotal, discount, tax, and total calculations
- Included/Excluded items
- Terms & conditions
- Notes section
- Footer with validity date

**Features:**
- Automatic PDF generation using DomPDF
- Downloadable PDF files
- Browser view option
- Professional formatting

#### SMS & Email Notifications

When a quotation is generated:
- **SMS**: Sent to customer with quotation number, tour name, total price, and validity date
- **Email**: Sent with quotation details and PDF attachment

#### Usage Examples

**Generate Quotation from Booking:**
```php
// In booking show page
<form action="{{ route('admin.bookings.generate-quotation', $booking) }}" method="POST">
    @csrf
    <input type="number" name="discount" placeholder="Discount amount">
    <input type="number" name="tax" placeholder="Tax amount">
    <input type="date" name="valid_until" value="{{ now()->addDays(7)->format('Y-m-d') }}">
    <textarea name="notes" placeholder="Additional notes"></textarea>
    <button type="submit">Generate Quotation</button>
</form>
```

**View/Download Quotation:**
```php
// View in browser
<a href="{{ route('admin.quotations.view', $quotation) }}">View PDF</a>

// Download PDF
<a href="{{ route('admin.quotations.pdf', $quotation) }}">Download PDF</a>
```

## Installation & Setup

### 1. Run Migrations

```bash
php artisan migrate
```

### 2. Install PDF Package (if not already installed)

```bash
composer require barryvdh/laravel-dompdf
```

### 3. Publish PDF Config (optional)

```bash
php artisan vendor:publish --provider="Barryvdh\DomPDF\ServiceProvider"
```

## Configuration

### Quotation Number Format

The system generates quotation numbers in the format: `QT{YYYY}{NNNN}`

Example: `QT20250001`, `QT20250002`, etc.

### Default Validity Period

Quotations are valid for 7 days by default. This can be customized when generating.

### Default Terms & Conditions

Default terms are set in the controller but can be customized per quotation.

## Integration with Booking System

### Booking Controller Updates

The `AdminBookingController` now:
- Shows related quotations on booking detail page
- Links to quotation generation from booking

### Notification Integration

Quotations automatically trigger:
- SMS notifications to customers
- Email notifications with PDF attachment
- In-app notifications for staff

## Best Practices

1. **Generate Quotations Early**: Generate quotations as soon as a booking inquiry is received
2. **Set Appropriate Validity**: Set validity dates based on tour departure dates
3. **Customize Terms**: Update terms & conditions based on tour type
4. **Track Status**: Regularly update quotation status (sent, accepted, rejected)
5. **Follow Up**: Send reminders before quotation expires

## Future Enhancements

Potential improvements:
- Quotation templates
- Automatic quotation expiry handling
- Quotation acceptance workflow
- Integration with payment system
- Quotation analytics and reporting
- Multi-currency support
- Quotation comparison tool

## Support

For issues or questions:
- Check logs: `storage/logs/laravel.log`
- Review quotation model relationships
- Verify PDF package installation
- Check SMS/Email configuration






