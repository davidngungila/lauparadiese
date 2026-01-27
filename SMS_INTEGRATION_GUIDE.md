# SMS Integration Guide

This document describes the SMS integration implemented in the Tour Booking System.

## Overview

SMS notifications have been integrated into the booking system to automatically notify customers and staff about booking events.

## Features Implemented

### 1. NotificationService (`app/Services/NotificationService.php`)
- Handles SMS and email notifications
- Supports multiple SMS providers
- Phone number validation and formatting (Tanzania format: 255XXXXXXXXX)
- Supports both GET and POST API methods
- Graceful fallback to environment variables if database models don't exist

### 2. Booking System Integration

#### Customer Booking (`app/Http/Controllers/BookingController.php`)
- Collects customer phone number during booking
- Sends SMS confirmation to customer upon booking creation
- Sends SMS notifications to travel consultants and reservations officers
- Supports multiple payment methods (card, mpesa, tigopesa, bank_transfer)

#### Admin Booking Management (`app/Http/Controllers/Admin/BookingController.php`)
- Sends SMS when booking status changes:
  - **Confirmed**: Customer receives confirmation message
  - **Cancelled**: Customer receives cancellation notice with reason
  - **Completed**: Customer receives thank you message
  - **Pending Payment**: Customer receives payment reminder

### 3. Database Updates

#### Bookings Table Migration
- Added `customer_phone` field
- Added `user_id` to link bookings to authenticated users
- Added `payment_method` field
- Added `notes` field for admin notes

#### Booking Model
- Added `booking_reference` accessor (format: BK000001)
- Added relationship to User model

### 4. SMS Test Command

Test SMS functionality using:
```bash
php artisan sms:test [phone] [--message="Your message"]
```

Example:
```bash
php artisan sms:test 255712345678 --message="Test message"
```

## Configuration

### Environment Variables

Add these to your `.env` file:

```env
# SMS Configuration
SMS_URL=https://messaging-service.co.tz/api/sms/v1/test/text/single
SMS_USERNAME=your_username
SMS_PASSWORD=your_password
SMS_FROM=TourBooking
```

### SMS Provider Setup

The system supports multiple SMS providers through the `NotificationProvider` model (optional). If the model doesn't exist, it falls back to environment variables.

## SMS Message Templates

### Booking Confirmation
```
Hello [Name], your booking #[Ref] for [Tour] on [Date] has been confirmed. 
Total: $[Amount]. Thank you for choosing us!
```

### Booking Cancellation
```
Hello [Name], we regret to inform you that booking #[Ref] for [Tour] on [Date] 
has been CANCELLED. Reason: [Reason]. Please contact us if you have questions.
```

### Status Updates
- **Pending Payment**: Reminds customer to complete payment
- **Confirmed**: Confirms booking with excitement
- **Cancelled**: Informs with reason
- **Completed**: Thanks customer and requests feedback

## Routes Added

### Admin Routes
- `POST /admin/bookings/{booking}/status` - Update booking status
- `POST /admin/bookings/{booking}/confirm` - Confirm booking
- `POST /admin/bookings/{booking}/cancel` - Cancel booking
- `PUT /admin/bookings/{booking}` - Update booking details

## Phone Number Format

The system automatically formats phone numbers to Tanzania format:
- Input: `0712345678` or `255712345678`
- Formatted: `255712345678` (12 digits)

## Error Handling

- SMS failures are logged but don't break the booking process
- Email notifications continue even if SMS fails
- Detailed error logging in `storage/logs/laravel.log`

## Testing

1. **Test SMS Command**:
   ```bash
   php artisan sms:test 255712345678
   ```

2. **Test Booking Flow**:
   - Create a booking with a phone number
   - Check SMS is sent to customer
   - Check admin notifications are sent

3. **Test Status Changes**:
   - Update booking status in admin panel
   - Verify SMS is sent to customer

## Logging

All SMS activities are logged:
- Success: `storage/logs/laravel.log` with `[SMS]` tag
- Failures: Detailed error logs with phone number and error message

## Next Steps

1. Configure SMS credentials in `.env`
2. Test SMS sending with `php artisan sms:test`
3. Update booking form to include phone number field
4. Test complete booking flow
5. Monitor logs for SMS delivery status

## Support

For SMS provider issues:
- Check credentials in `.env`
- Verify phone number format (255XXXXXXXXX)
- Check network connectivity
- Review logs in `storage/logs/laravel.log`
- Contact messaging-service.co.tz for account issues






