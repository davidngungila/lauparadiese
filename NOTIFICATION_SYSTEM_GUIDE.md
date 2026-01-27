# Advanced Toast Notification System - Complete Guide

## ✅ System Implemented

A comprehensive toast notification system has been integrated into the admin panel using Materio Bootstrap design patterns.

---

## 🎨 Features

### Toast Types
- ✅ **Success** - Green toast for successful operations
- ✅ **Error** - Red toast for errors
- ✅ **Warning** - Yellow toast for warnings
- ✅ **Info** - Blue toast for informational messages
- ✅ **Primary** - Primary color toast for general notifications

### Notification Channels
- ✅ **In-App Toasts** - Visual toast notifications (Materio design)
- ✅ **In-App Notifications** - Database-stored notifications
- ✅ **Email Notifications** - Email sent to users
- ✅ **SMS Notifications** - SMS sent to phone numbers (optional)

---

## 📝 Usage Examples

### 1. Controller Methods - Automatic Notifications

All admin controllers extend `BaseAdminController` which provides helper methods:

```php
// Success notification
$this->notifySuccess('Booking created successfully!', 'Success', route('admin.bookings.index'));

// Error notification
$this->notifyError('Failed to create booking. Please try again.', 'Error');

// Warning notification
$this->notifyWarning('Booking is pending approval.', 'Warning');

// Info notification
$this->notifyInfo('New booking received.', 'Information');

// Return with toast
return $this->successResponse('Booking created successfully!', route('admin.bookings.index'));
return $this->errorResponse('Operation failed!');
return $this->warningResponse('Please review the details.');
```

### 2. Session-Based Toasts (Automatic)

Simply return with session flash messages:

```php
// In Controller
return back()->with('success', 'Operation completed successfully!');
return back()->with('error', 'Operation failed!');
return back()->with('warning', 'Please check your input!');
return back()->with('info', 'New information available!');
```

The layout automatically converts these to toasts!

### 3. JavaScript Functions

Available globally in all admin pages:

```javascript
// Success toast
showSuccessToast('Booking created successfully!', 'Success', 5000);

// Error toast
showErrorToast('Failed to create booking!', 'Error', 7000);

// Warning toast
showWarningToast('Please review the details!', 'Warning', 6000);

// Info toast
showInfoToast('New booking received!', 'Information', 5000);

// Generic toast
showToast('success', 'Message here', 'Title', 5000);
```

### 4. Form Integration

Add data attributes to forms for automatic toasts:

```blade
<form method="POST" 
      action="{{ route('admin.bookings.store') }}" 
      data-show-toast="true" 
      data-toast-type="success" 
      data-toast-message="Booking created successfully!" 
      data-toast-title="Success">
    @csrf
    {{-- Form fields --}}
</form>
```

### 5. AJAX Operations

```javascript
fetch('/admin/bookings', {
    method: 'POST',
    headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
    },
    body: JSON.stringify(data)
})
.then(response => response.json())
.then(data => {
    if (data.success) {
        showSuccessToast(data.message, 'Success');
    } else {
        showErrorToast(data.message || 'An error occurred', 'Error');
    }
})
.catch(error => {
    showErrorToast('Network error. Please try again.', 'Error');
});
```

---

## 🔔 Notification Examples by Operation

### Booking Operations

```php
// Create Booking
$booking = Booking::create($data);
$this->notifySuccess("Booking #{$booking->id} created successfully!", 'New Booking', route('admin.bookings.show', $booking));
$this->notificationService->notifyByRole(['Travel Consultant', 'Reservations Officer'], "New booking #{$booking->id} created.", route('admin.bookings.show', $booking), 'New Booking');
return $this->successResponse('Booking created successfully!', route('admin.bookings.show', $booking));

// Update Booking
$booking->update($data);
$this->notifySuccess("Booking #{$booking->id} updated successfully!", 'Booking Updated', route('admin.bookings.show', $booking));
return $this->successResponse('Booking updated successfully!');

// Confirm Booking
$booking->update(['status' => 'confirmed', 'confirmed_at' => now()]);
$this->notifySuccess("Booking #{$booking->id} confirmed!", 'Booking Confirmed', route('admin.bookings.show', $booking));
// Notify customer
if ($booking->customer_phone) {
    $this->notificationService->notifyPhone(
        $booking->customer_phone,
        "Your booking #{$booking->id} has been confirmed!",
        $booking->customer_email,
        "Booking Confirmed - {$booking->tour->name}"
    );
}
return $this->successResponse('Booking confirmed successfully!');

// Cancel Booking
$booking->update(['status' => 'cancelled', 'cancelled_at' => now()]);
$this->notifyWarning("Booking #{$booking->id} cancelled.", 'Booking Cancelled', route('admin.bookings.show', $booking));
// Notify customer
if ($booking->customer_phone) {
    $this->notificationService->notifyPhone(
        $booking->customer_phone,
        "Your booking #{$booking->id} has been cancelled. Reason: {$request->reason}",
        $booking->customer_email,
        "Booking Cancelled - {$booking->tour->name}"
    );
}
return $this->warningResponse('Booking cancelled successfully!');
```

### Quotation Operations

```php
// Generate Quotation
$quotation = Quotation::createFromBooking($booking);
$this->notifySuccess("Quotation #{$quotation->quotation_number} generated successfully!", 'Quotation Generated', route('admin.quotations.show', $quotation));
// Notify customer
if ($booking->customer_phone) {
    $this->notificationService->notifyPhone(
        $booking->customer_phone,
        "Your quotation #{$quotation->quotation_number} is ready!",
        $booking->customer_email,
        "Quotation Ready - {$booking->tour->name}"
    );
}
return $this->successResponse('Quotation generated successfully!', route('admin.quotations.show', $quotation));

// Send Quotation
$quotation->update(['status' => 'sent', 'sent_at' => now()]);
$this->notifySuccess("Quotation #{$quotation->quotation_number} sent to customer!", 'Quotation Sent');
return $this->successResponse('Quotation sent successfully!');
```

### Tour Operations

```php
// Create Tour
$tour = Tour::create($data);
$this->notifySuccess("Tour '{$tour->name}' created successfully!", 'New Tour', route('admin.tours.show', $tour));
$this->notificationService->notifyByRole(['Content Manager'], "New tour '{$tour->name}' has been added.", route('admin.tours.show', $tour), 'New Tour');
return $this->successResponse('Tour created successfully!', route('admin.tours.show', $tour));

// Update Tour
$tour->update($data);
$this->notifySuccess("Tour '{$tour->name}' updated successfully!", 'Tour Updated', route('admin.tours.show', $tour));
return $this->successResponse('Tour updated successfully!');

// Delete Tour
$tourName = $tour->name;
$tour->delete();
$this->notifyWarning("Tour '{$tourName}' deleted.", 'Tour Deleted');
return $this->warningResponse('Tour deleted successfully!');
```

### Client Operations

```php
// Create Client
$client = User::create($data);
$client->assignRole('Customer');
$this->notifySuccess("Client '{$client->name}' added successfully!", 'New Client', route('admin.clients.show', $client));
return $this->successResponse('Client added successfully!', route('admin.clients.show', $client));

// Update Client
$client->update($data);
$this->notifySuccess("Client '{$client->name}' updated successfully!", 'Client Updated', route('admin.clients.show', $client));
return $this->successResponse('Client updated successfully!');
```

### Payment Operations

```php
// Record Payment
$payment = Payment::create($data);
$this->notifySuccess("Payment of {$payment->amount} recorded successfully!", 'Payment Recorded', route('admin.payments.show', $payment));
$this->notificationService->notifyByRole(['Finance Officer'], "New payment of {$payment->amount} recorded.", route('admin.payments.show', $payment), 'New Payment');
return $this->successResponse('Payment recorded successfully!');

// Process Refund
$refund = Refund::create($data);
$this->notifyWarning("Refund of {$refund->amount} processed.", 'Refund Processed', route('admin.refunds.show', $refund));
return $this->warningResponse('Refund processed successfully!');
```

---

## 🎯 Best Practices

### 1. Always Notify on Critical Operations
- ✅ Create/Update/Delete operations
- ✅ Status changes (confirmed, cancelled, etc.)
- ✅ Payment processing
- ✅ User actions (login, password change)

### 2. Notify Relevant Roles
```php
// Notify specific roles
$this->notificationService->notifyByRole(
    ['Travel Consultant', 'Reservations Officer'],
    "New booking #{$booking->id} requires attention.",
    route('admin.bookings.show', $booking),
    'New Booking Alert'
);
```

### 3. Notify Customers
```php
// For customer-facing operations
if ($booking->customer_phone) {
    $this->notificationService->notifyPhone(
        $booking->customer_phone,
        "Your booking #{$booking->id} status has been updated.",
        $booking->customer_email,
        "Booking Update - {$booking->tour->name}"
    );
}
```

### 4. Use Appropriate Toast Types
- **Success** - Completed operations
- **Error** - Failed operations, validation errors
- **Warning** - Important notices, cancellations
- **Info** - General information, updates

### 5. Include Links in Notifications
```php
$this->notifySuccess(
    'Booking created successfully!',
    'New Booking',
    route('admin.bookings.show', $booking) // Link to view details
);
```

---

## 🔧 Configuration

### Toast Duration
Default: 5000ms (5 seconds)
- Success: 5000ms
- Error: 7000ms (longer for errors)
- Warning: 6000ms
- Info: 5000ms

### Toast Position
Default: Top-right corner
- Can be changed in `admin-notifications.js`

### Auto-hide
- Toasts auto-hide after delay
- User can manually close with X button

---

## 📱 Notification Channels

### 1. In-App Toasts (Visual)
- ✅ Always shown
- ✅ Non-intrusive
- ✅ Auto-dismiss

### 2. In-App Notifications (Database)
- ✅ Stored in `notifications` table
- ✅ Can be viewed in notification center
- ✅ Marked as read/unread

### 3. Email Notifications
- ✅ Sent via SMTP
- ✅ Configurable templates
- ✅ HTML formatted

### 4. SMS Notifications
- ✅ Sent via SMS gateway
- ✅ For critical operations
- ✅ Customer communications

---

## 🚀 Quick Reference

### Controller Helper Methods
```php
$this->notifySuccess($message, $title, $link, $userIds);
$this->notifyError($message, $title, $link, $userIds);
$this->notifyWarning($message, $title, $link, $userIds);
$this->notifyInfo($message, $title, $link, $userIds);

$this->successResponse($message, $redirect);
$this->errorResponse($message, $redirect);
$this->warningResponse($message, $redirect);
```

### JavaScript Functions
```javascript
showSuccessToast(message, title, delay);
showErrorToast(message, title, delay);
showWarningToast(message, title, delay);
showInfoToast(message, title, delay);
showToast(type, message, title, delay);
```

### Session Flash Messages
```php
return back()->with('success', 'Message');
return back()->with('error', 'Message');
return back()->with('warning', 'Message');
return back()->with('info', 'Message');
```

---

## ✨ System Ready!

All operations in the admin system now support toast notifications. Simply use the helper methods or session flash messages, and toasts will appear automatically!




