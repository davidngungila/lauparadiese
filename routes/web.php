<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| This file is where you can register web routes for your application.
| These routes are loaded by the RouteServiceProvider within a group
| which contains the "web" middleware group. Now create something great!
|
*/

// --- PUBLIC-FACING CONTROLLERS ---
use App\Http\Controllers\PageController;
use App\Http\Controllers\TourController;
use App\Http\Controllers\DestinationController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\NewsletterController;
use App\Http\Controllers\LegalController;

// --- AUTH CONTROLLERS ---
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;

// --- ADMIN CONTROLLERS ---
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\BookingController as AdminBookingController;
use App\Http\Controllers\Admin\QuotationController as AdminQuotationController;
use App\Http\Controllers\Admin\TourController as AdminTourController;
use App\Http\Controllers\Admin\HotelController as AdminHotelController;
use App\Http\Controllers\Admin\VehicleController as AdminVehicleController;
use App\Http\Controllers\Admin\CustomerController as AdminCustomerController;
use App\Http\Controllers\Admin\FinanceController as AdminFinanceController;
use App\Http\Controllers\Admin\MarketingController as AdminMarketingController;
use App\Http\Controllers\Admin\DocumentController as AdminDocumentController;
use App\Http\Controllers\Admin\HomepageController as AdminHomepageController;
use App\Http\Controllers\Admin\UserManagementController as AdminUserManagementController;
use App\Http\Controllers\Admin\SystemSettingsController as AdminSystemSettingsController;
use App\Http\Controllers\Admin\ApiIntegrationsController as AdminApiIntegrationsController;
use App\Http\Controllers\Admin\MpesaController as AdminMpesaController;
use App\Http\Controllers\Api\MpesaCallbackController;
use App\Http\Controllers\Admin\StatisticsController as AdminStatisticsController;
use App\Http\Controllers\Admin\SmsGatewayController as AdminSmsGatewayController;
use App\Http\Controllers\Admin\EmailSmtpController as AdminEmailSmtpController;
use App\Http\Controllers\Admin\PaymentGatewayController as AdminPaymentGatewayController;
use App\Http\Controllers\Admin\BackupController as AdminBackupController;
use App\Http\Controllers\Admin\SystemLogsController as AdminSystemLogsController;
use App\Http\Controllers\Admin\AuditTrailController as AdminAuditTrailController;
use App\Http\Controllers\Admin\ActivityLogController as AdminActivityLogController;
use App\Http\Controllers\Admin\WebsiteSettingsController as AdminWebsiteSettingsController;
use App\Http\Controllers\Admin\SecuritySettingsController as AdminSecuritySettingsController;
use App\Http\Controllers\Admin\OrganizationSettingController;
use App\Http\Controllers\Admin\CustomerQueriesController as AdminCustomerQueriesController;
use App\Http\Controllers\Admin\SupportTicketsController as AdminSupportTicketsController;
use App\Http\Controllers\Admin\NotificationController as AdminNotificationController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\PermissionController;


//======================================================================
// SECTION 1: PUBLIC-FACING WEBSITE ROUTES
//======================================================================

// --- 1.1 Core & Static Pages ---
Route::get('/', [PageController::class, 'home'])->name('home');
Route::get('/safaris', [PageController::class, 'safaris'])->name('safaris');
Route::get('/about', [PageController::class, 'about'])->name('about');
Route::get('/our-team', [PageController::class, 'team'])->name('team');
Route::get('/sustainability', [PageController::class, 'sustainability'])->name('sustainability');
Route::get('/partners', [PageController::class, 'partners'])->name('partners');
Route::get('/careers', [PageController::class, 'careers'])->name('careers');
Route::get('/press-media', [PageController::class, 'press'])->name('press');

// --- 1.2 Tours Section ---
Route::controller(TourController::class)->prefix('tours')->name('tours.')->group(function () {
    Route::get('/', 'index')->name('index');
    Route::get('/last-minute-deals', 'lastMinuteDeals')->name('last-minute');
    Route::get('/category/{category_slug}', 'category')->name('category');
    Route::get('/{tour_slug}', 'show')->name('show');
});

// --- 1.3 Destinations Section ---
Route::controller(DestinationController::class)->prefix('destinations')->name('destinations.')->group(function () {
    Route::get('/', 'index')->name('index');
    Route::get('/{destination_slug}', 'show')->name('show');
});

// --- 1.4 Blog Section ---
Route::controller(BlogController::class)->prefix('blog')->name('blog.')->group(function () {
    Route::get('/', 'index')->name('index');
    Route::get('/category/{category_slug}', 'category')->name('category');
    Route::get('/{post_slug}', 'show')->name('show');
});

// --- 1.5 Booking, Contact & Support Pages ---
Route::controller(BookingController::class)->group(function () {
    Route::get('/book-now', 'simple')->name('booking'); // Simplified booking by default
    Route::get('/book-now/wizard', 'wizard')->name('booking.wizard'); // Full wizard (optional)
    Route::post('/book-now', 'submit')->name('booking.submit');
    Route::get('/booking/check-availability', 'checkAvailability')->name('booking.check-availability');
    Route::get('/booking/confirmation/{booking}', 'confirmation')->name('booking.confirmation');
    Route::get('/booking/invoice/{reference}', 'viewInvoice')->name('booking.invoice.view');
    Route::get('/booking/invoice/{reference}/download', 'downloadInvoice')->name('booking.invoice.download');
});

Route::controller(ContactController::class)->group(function () {
    Route::get('/contact', 'index')->name('contact');
    Route::post('/contact', 'submit')->name('contact.submit');
});

Route::controller(PageController::class)->group(function () {
    Route::get('/faq', 'faq')->name('faq');
    Route::get('/booking-help', 'bookingHelp')->name('booking.help');
    Route::get('/customer-reviews', 'reviews')->name('reviews');
    Route::get('/travel-insurance', 'travelInsurance')->name('travel-insurance');
    Route::get('/travel-tips', 'travelTips')->name('travel-tips');
    Route::get('/gift-cards', 'giftCards')->name('gift-cards');
    Route::get('/custom-tours', 'customTours')->name('custom-tours');
    Route::get('/travel-proposal', 'travelProposal')->name('travel-proposal');
    Route::get('/experiences/family', 'familyExperiences')->name('experiences.family');
    Route::get('/wildlife/birds', 'wildlifeBirds')->name('wildlife.birds');
});

// --- 1.6 Legal Pages ---
Route::controller(LegalController::class)->group(function () {
    Route::get('/privacy-policy', 'privacy')->name('privacy');
    Route::get('/terms-of-service', 'terms')->name('terms');
    Route::get('/cookie-policy', 'cookies')->name('cookies');
    Route::get('/accessibility', 'accessibility')->name('accessibility');
});


//======================================================================
// SECTION 2: AUTHENTICATION ROUTES
//======================================================================

Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [RegisterController::class, 'register']);

// Password Reset Routes
use App\Http\Controllers\Auth\ForgotPasswordController;
Route::get('/password/reset', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
Route::post('/password/email', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');

// Payment Gateway Webhooks (Public routes for webhook callbacks)
Route::prefix('webhooks')->name('webhooks.')->group(function () {
    Route::post('/stripe', [\App\Http\Controllers\PaymentProcessingController::class, 'webhook'])->name('stripe');
    Route::post('/paypal', [\App\Http\Controllers\PaymentProcessingController::class, 'webhook'])->name('paypal');
    Route::post('/payoneer', [\App\Http\Controllers\PaymentProcessingController::class, 'webhook'])->name('payoneer');
});

// Stripe Payment Routes
Route::prefix('stripe')->name('stripe.')->group(function () {
    Route::get('/get-key', [\App\Http\Controllers\PaymentProcessingController::class, 'getStripeKey'])->name('get-key');
    Route::post('/create-intent', [\App\Http\Controllers\PaymentProcessingController::class, 'createStripeIntent'])->name('create-intent');
});

// Pesapal Payment Routes (Public routes for callbacks)
Route::prefix('pesapal')->name('pesapal.')->group(function () {
    Route::get('/callback', [\App\Http\Controllers\PesapalController::class, 'callback'])->name('callback');
    Route::get('/ipn', [\App\Http\Controllers\PesapalController::class, 'ipn'])->name('ipn');
});

// M-PESA API Callbacks (Public routes - Called by Safaricom servers)
Route::prefix('api/mpesa')->name('api.mpesa.')->group(function () {
    // STK Push Callbacks
    Route::post('/stk/callback', [MpesaCallbackController::class, 'stkCallback'])->name('stk.callback');
    Route::post('/stk/timeout', [MpesaCallbackController::class, 'stkTimeout'])->name('stk.timeout');
    
    // C2B Callbacks
    Route::post('/c2b/validate', [MpesaCallbackController::class, 'c2bValidate'])->name('c2b.validate');
    Route::post('/c2b/confirm', [MpesaCallbackController::class, 'c2bConfirm'])->name('c2b.confirm');
    
    // B2C Callbacks
    Route::post('/b2c/result', [MpesaCallbackController::class, 'b2cResult'])->name('b2c.result');
    Route::post('/b2c/timeout', [MpesaCallbackController::class, 'b2cTimeout'])->name('b2c.timeout');
});

// PayPal Success/Cancel Routes (Public)
Route::get('/paypal/success', [\App\Http\Controllers\PaymentProcessingController::class, 'paypalSuccess'])->name('paypal.success');
Route::get('/paypal/cancel', [\App\Http\Controllers\PaymentProcessingController::class, 'cancel'])->name('paypal.cancel');
Route::get('/payoneer/success', [\App\Http\Controllers\PaymentProcessingController::class, 'cancel'])->name('payoneer.success');
Route::get('/payoneer/cancel', [\App\Http\Controllers\PaymentProcessingController::class, 'cancel'])->name('payoneer.cancel');


//======================================================================
// SECTION 3: CUSTOMER AREA ROUTES (Authenticated Customers)
//======================================================================

Route::middleware(['auth', 'role:Customer'])->prefix('customer')->name('customer.')->group(function () {
    Route::get('/dashboard', [\App\Http\Controllers\Customer\DashboardController::class, 'index'])->name('dashboard');
    // Add more customer routes here as needed
});

//======================================================================
// SECTION 4: FUNCTIONAL ROUTES (Forms, Actions, etc.)
//======================================================================

Route::post('/newsletter/subscribe', [NewsletterController::class, 'subscribe'])->name('newsletter.subscribe');
Route::get('/newsletter/verify/{token}', [NewsletterController::class, 'verify'])->name('newsletter.verify');


//======================================================================
// SECTION 5: ADMIN PANEL ROUTES
//======================================================================

Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {

    // Dashboard - accessible to all authenticated users
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Statistics - System Administrator, Finance Officer, Travel Consultant
    Route::middleware(['role:System Administrator|Finance Officer|Travel Consultant'])->group(function () {
        Route::get('/statistics/analytics', [AdminStatisticsController::class, 'analytics'])->name('statistics.analytics');
        Route::get('/statistics/revenue-summary', [AdminStatisticsController::class, 'revenueSummary'])->name('statistics.revenue-summary');
        Route::get('/statistics/bookings-status', [AdminStatisticsController::class, 'bookingsStatus'])->name('statistics.bookings-status');
        Route::get('/statistics/upcoming-trips', [AdminStatisticsController::class, 'upcomingTrips'])->name('statistics.upcoming-trips');
    });
    
    // Profile & Account Settings - accessible to all authenticated users
    Route::get('/profile', [DashboardController::class, 'profile'])->name('profile');
    Route::get('/account-settings', [DashboardController::class, 'accountSettings'])->name('account-settings');
    Route::get('/account-settings/security', [DashboardController::class, 'accountSecurity'])->name('account-settings.security');
    Route::get('/account-settings/billing', [DashboardController::class, 'accountBilling'])->name('account-settings.billing');
    Route::get('/account-settings/notifications', [DashboardController::class, 'accountNotifications'])->name('account-settings.notifications');
    Route::get('/account-settings/connections', [DashboardController::class, 'accountConnections'])->name('account-settings.connections');
    
    // Profile Update Routes
    Route::put('/profile', [DashboardController::class, 'updateProfile'])->name('profile.update');
    Route::put('/profile/password', [DashboardController::class, 'updatePassword'])->name('profile.password');
    Route::put('/profile/avatar', [DashboardController::class, 'updateAvatar'])->name('profile.avatar');
    
    // Account Settings Update Routes
    Route::put('/account-settings', [DashboardController::class, 'updateAccountSettings'])->name('account-settings.update');
    Route::put('/account-settings/security', [DashboardController::class, 'updateSecurity'])->name('account-settings.security.update');
    Route::put('/account-settings/billing', [DashboardController::class, 'updateBilling'])->name('account-settings.billing.update');
    Route::put('/account-settings/notifications', [DashboardController::class, 'updateNotifications'])->name('account-settings.notifications.update');
    Route::post('/account-settings/connections', [DashboardController::class, 'updateConnections'])->name('account-settings.connections.update');
    Route::delete('/account-settings/connections/{provider}', [DashboardController::class, 'disconnectProvider'])->name('account-settings.connections.disconnect');
    Route::post('/account-settings/deactivate', [DashboardController::class, 'deactivateAccount'])->name('account-settings.deactivate');
    
    // Marketing - System Administrator, Marketing Manager, Content Manager
    Route::middleware(['role:System Administrator|Marketing Manager|Content Manager'])->prefix('marketing')->name('marketing.')->group(function () {
        Route::get('/dashboard', [App\Http\Controllers\Admin\MarketingController::class, 'dashboard'])->name('dashboard');
        
        // Promo Codes / Discounts
        Route::get('/promo-codes', [App\Http\Controllers\Admin\MarketingController::class, 'promoCodes'])->name('promo-codes');
        Route::get('/promo-codes/create', [App\Http\Controllers\Admin\MarketingController::class, 'createPromoCode'])->name('promo-codes.create');
        Route::post('/promo-codes', [App\Http\Controllers\Admin\MarketingController::class, 'storePromoCode'])->name('promo-codes.store');
        Route::get('/promo-codes/{id}/edit', [App\Http\Controllers\Admin\MarketingController::class, 'editPromoCode'])->name('promo-codes.edit');
        Route::put('/promo-codes/{id}', [App\Http\Controllers\Admin\MarketingController::class, 'updatePromoCode'])->name('promo-codes.update');
        Route::delete('/promo-codes/{id}', [App\Http\Controllers\Admin\MarketingController::class, 'destroyPromoCode'])->name('promo-codes.destroy');
        
        // Email Campaigns
        Route::get('/email-campaigns', [App\Http\Controllers\Admin\MarketingController::class, 'emailCampaigns'])->name('email-campaigns');
        Route::get('/email-campaigns/create', [App\Http\Controllers\Admin\MarketingController::class, 'createEmailCampaign'])->name('email-campaigns.create');
        Route::post('/email-campaigns', [App\Http\Controllers\Admin\MarketingController::class, 'storeEmailCampaign'])->name('email-campaigns.store');
        Route::get('/email-campaigns/{id}/edit', [App\Http\Controllers\Admin\MarketingController::class, 'editEmailCampaign'])->name('email-campaigns.edit');
        Route::put('/email-campaigns/{id}', [App\Http\Controllers\Admin\MarketingController::class, 'updateEmailCampaign'])->name('email-campaigns.update');
        Route::post('/email-campaigns/{id}/send', [App\Http\Controllers\Admin\MarketingController::class, 'sendEmailCampaign'])->name('email-campaigns.send');
        Route::delete('/email-campaigns/{id}', [App\Http\Controllers\Admin\MarketingController::class, 'destroyEmailCampaign'])->name('email-campaigns.destroy');
        
        // SMS Campaigns
        Route::get('/sms-campaigns', [App\Http\Controllers\Admin\MarketingController::class, 'smsCampaigns'])->name('sms-campaigns');
        Route::get('/sms-campaigns/create', [App\Http\Controllers\Admin\MarketingController::class, 'createSmsCampaign'])->name('sms-campaigns.create');
        Route::post('/sms-campaigns', [App\Http\Controllers\Admin\MarketingController::class, 'storeSmsCampaign'])->name('sms-campaigns.store');
        Route::get('/sms-campaigns/{id}/edit', [App\Http\Controllers\Admin\MarketingController::class, 'editSmsCampaign'])->name('sms-campaigns.edit');
        Route::put('/sms-campaigns/{id}', [App\Http\Controllers\Admin\MarketingController::class, 'updateSmsCampaign'])->name('sms-campaigns.update');
        Route::post('/sms-campaigns/{id}/send', [App\Http\Controllers\Admin\MarketingController::class, 'sendSmsCampaign'])->name('sms-campaigns.send');
        Route::delete('/sms-campaigns/{id}', [App\Http\Controllers\Admin\MarketingController::class, 'destroySmsCampaign'])->name('sms-campaigns.destroy');
        
        // Social Media Scheduler
        Route::get('/social-media', [App\Http\Controllers\Admin\MarketingController::class, 'socialMedia'])->name('social-media');
        Route::get('/social-media/create', [App\Http\Controllers\Admin\MarketingController::class, 'createSocialMedia'])->name('social-media.create');
        Route::post('/social-media', [App\Http\Controllers\Admin\MarketingController::class, 'storeSocialMedia'])->name('social-media.store');
        Route::get('/social-media/{id}/edit', [App\Http\Controllers\Admin\MarketingController::class, 'editSocialMedia'])->name('social-media.edit');
        Route::put('/social-media/{id}', [App\Http\Controllers\Admin\MarketingController::class, 'updateSocialMedia'])->name('social-media.update');
        Route::post('/social-media/{id}/publish', [App\Http\Controllers\Admin\MarketingController::class, 'publishSocialMedia'])->name('social-media.publish');
        Route::delete('/social-media/{id}', [App\Http\Controllers\Admin\MarketingController::class, 'destroySocialMedia'])->name('social-media.destroy');
        
        // Landing Pages
        Route::get('/landing-pages', [App\Http\Controllers\Admin\MarketingController::class, 'landingPages'])->name('landing-pages');
        Route::get('/landing-pages/create', [App\Http\Controllers\Admin\MarketingController::class, 'createLandingPage'])->name('landing-pages.create');
        Route::post('/landing-pages', [App\Http\Controllers\Admin\MarketingController::class, 'storeLandingPage'])->name('landing-pages.store');
        Route::get('/landing-pages/{id}/edit', [App\Http\Controllers\Admin\MarketingController::class, 'editLandingPage'])->name('landing-pages.edit');
        Route::put('/landing-pages/{id}', [App\Http\Controllers\Admin\MarketingController::class, 'updateLandingPage'])->name('landing-pages.update');
        Route::delete('/landing-pages/{id}', [App\Http\Controllers\Admin\MarketingController::class, 'destroyLandingPage'])->name('landing-pages.destroy');
        
        // Marketing Analytics
        Route::get('/analytics', [App\Http\Controllers\Admin\MarketingController::class, 'analytics'])->name('analytics');
        
        // Banners & Popups
        Route::get('/banners', [App\Http\Controllers\Admin\MarketingController::class, 'banners'])->name('banners');
        Route::get('/banners/create', [App\Http\Controllers\Admin\MarketingController::class, 'createBanner'])->name('banners.create');
        Route::post('/banners', [App\Http\Controllers\Admin\MarketingController::class, 'storeBanner'])->name('banners.store');
        Route::get('/banners/{id}/edit', [App\Http\Controllers\Admin\MarketingController::class, 'editBanner'])->name('banners.edit');
        Route::put('/banners/{id}', [App\Http\Controllers\Admin\MarketingController::class, 'updateBanner'])->name('banners.update');
        Route::delete('/banners/{id}', [App\Http\Controllers\Admin\MarketingController::class, 'destroyBanner'])->name('banners.destroy');
        Route::post('/banners/{id}/toggle', [App\Http\Controllers\Admin\MarketingController::class, 'toggleBanner'])->name('banners.toggle');
    });
    
    // Settings - System Administrator and ICT Officer only
    Route::middleware(['role:System Administrator|ICT Officer'])->group(function () {
        Route::get('/settings', [DashboardController::class, 'settings'])->name('settings');
        
        // System Settings
        Route::get('/settings/system', [AdminSystemSettingsController::class, 'index'])->name('settings.system');
        Route::put('/settings/system', [AdminSystemSettingsController::class, 'update'])->name('settings.system.update');
        
        // API Integrations
        Route::get('/settings/api-integrations', [AdminApiIntegrationsController::class, 'index'])->name('settings.api-integrations');
        
        // MPESA Daraja
        Route::get('/settings/mpesa', [AdminMpesaController::class, 'index'])->name('settings.mpesa');
        Route::put('/settings/mpesa', [AdminMpesaController::class, 'update'])->name('settings.mpesa.update');
        Route::post('/settings/mpesa/test', [AdminMpesaController::class, 'test'])->name('settings.mpesa.test');
        Route::post('/settings/mpesa/simulate-stk', [AdminMpesaController::class, 'simulateStkPush'])->name('settings.mpesa.simulate-stk');
        Route::post('/settings/mpesa/query-stk-status', [AdminMpesaController::class, 'queryStkStatus'])->name('settings.mpesa.query-stk-status');
        Route::post('/settings/mpesa/register-c2b', [AdminMpesaController::class, 'registerC2b'])->name('settings.mpesa.register-c2b');
        Route::post('/settings/mpesa/initiate-b2c', [AdminMpesaController::class, 'initiateB2c'])->name('settings.mpesa.initiate-b2c');
        Route::post('/settings/mpesa/query-balance', [AdminMpesaController::class, 'queryBalance'])->name('settings.mpesa.query-balance');
        Route::post('/settings/mpesa/reverse-transaction', [AdminMpesaController::class, 'reverseTransaction'])->name('settings.mpesa.reverse-transaction');
        Route::get('/settings/mpesa/transaction-history', [AdminMpesaController::class, 'transactionHistory'])->name('settings.mpesa.transaction-history');
        
        // SMS Gateway
        Route::get('/settings/sms-gateway', [AdminSmsGatewayController::class, 'index'])->name('settings.sms-gateway');
        Route::post('/settings/sms-gateway', [AdminSmsGatewayController::class, 'store'])->name('settings.sms-gateway.store');
        Route::put('/settings/sms-gateway/{id}', [AdminSmsGatewayController::class, 'update'])->name('settings.sms-gateway.update');
        Route::delete('/settings/sms-gateway/{id}', [AdminSmsGatewayController::class, 'destroy'])->name('settings.sms-gateway.destroy');
        Route::post('/settings/sms-gateway/{id}/set-primary', [AdminSmsGatewayController::class, 'setPrimary'])->name('settings.sms-gateway.set-primary');
        Route::post('/settings/sms-gateway/{id}/test-connection', [AdminSmsGatewayController::class, 'testConnection'])->name('settings.sms-gateway.test-connection');
        Route::post('/settings/sms-gateway/{id}/toggle-active', [AdminSmsGatewayController::class, 'toggleActive'])->name('settings.sms-gateway.toggle-active');
        Route::post('/settings/sms-gateway/test', [AdminSmsGatewayController::class, 'test'])->name('settings.sms-gateway.test');
        
        // Email SMTP & Templates
        Route::get('/settings/email-smtp', [AdminEmailSmtpController::class, 'index'])->name('settings.email-smtp');
        Route::put('/settings/email-smtp', [AdminEmailSmtpController::class, 'update'])->name('settings.email-smtp.update');
        Route::post('/settings/email-smtp/test', [AdminEmailSmtpController::class, 'test'])->name('settings.email-smtp.test');
        Route::post('/settings/email-smtp/test-connection', [AdminEmailSmtpController::class, 'testConnection'])->name('settings.email-smtp.test-connection');
        Route::post('/settings/email-templates/{key}', [AdminEmailSmtpController::class, 'updateTemplate'])->name('settings.email-templates.update');
        
        // Payment Gateways (PayPal/Stripe)
        Route::get('/settings/payment-gateways', [AdminPaymentGatewayController::class, 'index'])->name('settings.payment-gateways');
        Route::post('/settings/payment-gateways', [AdminPaymentGatewayController::class, 'store'])->name('settings.payment-gateways.store');
        Route::put('/settings/payment-gateways/{id}', [AdminPaymentGatewayController::class, 'update'])->name('settings.payment-gateways.update');
        Route::delete('/settings/payment-gateways/{id}', [AdminPaymentGatewayController::class, 'destroy'])->name('settings.payment-gateways.destroy');
        Route::post('/settings/payment-gateways/{id}/set-primary', [AdminPaymentGatewayController::class, 'setPrimary'])->name('settings.payment-gateways.set-primary');
        Route::post('/settings/payment-gateways/{id}/test-connection', [AdminPaymentGatewayController::class, 'testConnection'])->name('settings.payment-gateways.test-connection');
        Route::post('/settings/payment-gateways/{id}/toggle-active', [AdminPaymentGatewayController::class, 'toggleActive'])->name('settings.payment-gateways.toggle-active');
        
        // Backup Manager
        Route::get('/settings/backups', [AdminBackupController::class, 'index'])->name('settings.backups');
        Route::post('/settings/backups', [AdminBackupController::class, 'create'])->name('settings.backups.create');
        Route::get('/settings/backups/{filename}/download', [AdminBackupController::class, 'download'])->name('settings.backups.download');
        Route::delete('/settings/backups/{filename}', [AdminBackupController::class, 'destroy'])->name('settings.backups.destroy');
        
        // System Logs & Health
        Route::get('/settings/system-logs', [AdminSystemLogsController::class, 'index'])->name('settings.system-logs');
        Route::delete('/settings/system-logs', [AdminSystemLogsController::class, 'clear'])->name('settings.system-logs.clear');
        Route::get('/settings/system-logs/download', [AdminSystemLogsController::class, 'download'])->name('settings.system-logs.download');
        Route::get('/settings/system-health', [AdminSystemSettingsController::class, 'health'])->name('settings.system-health');
        
        // Audit Trails
        Route::get('/settings/audit-trails', [AdminAuditTrailController::class, 'index'])->name('settings.audit-trails');
        Route::get('/settings/audit-trails/{id}', [AdminAuditTrailController::class, 'show'])->name('settings.audit-trails.show');
        Route::get('/settings/audit-trails/export', [AdminAuditTrailController::class, 'export'])->name('settings.audit-trails.export');
        
        // Activity Logs
        Route::get('/settings/activity-logs', [AdminActivityLogController::class, 'index'])->name('settings.activity-logs');
        Route::get('/settings/activity-logs/{id}', [AdminActivityLogController::class, 'show'])->name('settings.activity-logs.show');
        Route::get('/settings/activity-logs/export', [AdminActivityLogController::class, 'export'])->name('settings.activity-logs.export');
        
        // Website Settings
        Route::get('/settings/website', [AdminWebsiteSettingsController::class, 'index'])->name('settings.website');
        Route::put('/settings/website', [AdminWebsiteSettingsController::class, 'update'])->name('settings.website.update');
        
        // Security Settings
        Route::get('/settings/security', [AdminSecuritySettingsController::class, 'index'])->name('settings.security');
        Route::put('/settings/security', [AdminSecuritySettingsController::class, 'update'])->name('settings.security.update');
        Route::post('/settings/security/change-password', [AdminSecuritySettingsController::class, 'changePassword'])->name('settings.security.change-password');
        
        // Organization Settings
        Route::get('/settings/organization', [OrganizationSettingController::class, 'index'])->name('settings.organization');
        Route::put('/settings/organization', [OrganizationSettingController::class, 'update'])->name('settings.organization.update');
        
        // Redirect old routes
        Route::get('/settings/users', [DashboardController::class, 'users'])->name('settings.users');
        Route::get('/settings/roles', [DashboardController::class, 'roles'])->name('settings.roles');
        
        // Roles & Permissions Management
        Route::prefix('roles')->name('roles.')->group(function () {
            Route::get('/', [RoleController::class, 'index'])->name('index');
            Route::get('/datatable', [RoleController::class, 'datatable'])->name('datatable');
            Route::get('/users/datatable', [RoleController::class, 'usersDatatable'])->name('users.datatable');
            Route::get('/permissions', [RoleController::class, 'getPermissions'])->name('permissions');
            Route::get('/export', [RoleController::class, 'export'])->name('export');
            Route::post('/', [RoleController::class, 'store'])->name('store');
            Route::get('/{role}', [RoleController::class, 'show'])->name('show');
            Route::put('/{role}', [RoleController::class, 'update'])->name('update');
            Route::delete('/{role}', [RoleController::class, 'destroy'])->name('destroy');
        });
        
        Route::prefix('permissions')->name('permissions.')->group(function () {
            Route::get('/', [PermissionController::class, 'index'])->name('index');
            Route::get('/datatable', [PermissionController::class, 'datatable'])->name('datatable');
            Route::get('/export', [PermissionController::class, 'export'])->name('export');
            Route::post('/', [PermissionController::class, 'store'])->name('store');
            Route::get('/{permission}', [PermissionController::class, 'show'])->name('show');
            Route::put('/{permission}', [PermissionController::class, 'update'])->name('update');
            Route::delete('/{permission}', [PermissionController::class, 'destroy'])->name('destroy');
        });
    });
    
    // Bookings - System Administrator, Travel Consultant, Reservations Officer
    Route::middleware(['role:System Administrator|Travel Consultant|Reservations Officer'])->group(function () {
        Route::get('/bookings', [AdminBookingController::class, 'index'])->name('bookings.index');
        Route::get('/bookings/create', [AdminBookingController::class, 'create'])->name('bookings.create');
        Route::post('/bookings', [AdminBookingController::class, 'store'])->name('bookings.store');
        // Specific routes must come before parameterized routes
        Route::get('/bookings/pending', [AdminBookingController::class, 'pending'])->name('bookings.pending');
        Route::get('/bookings/pending-approvals', [AdminBookingController::class, 'pendingApprovals'])->name('bookings.pending-approvals');
        Route::get('/bookings/confirmed', [AdminBookingController::class, 'confirmed'])->name('bookings.confirmed');
        Route::get('/bookings/cancelled', [AdminBookingController::class, 'cancelled'])->name('bookings.cancelled');
        Route::get('/bookings/calendar', [AdminBookingController::class, 'calendarView'])->name('bookings.calendar');
        Route::get('/bookings/calendar/data', [AdminBookingController::class, 'calendarData'])->name('bookings.calendar.data');
        Route::get('/bookings/stats', [AdminBookingController::class, 'stats'])->name('bookings.stats');
        Route::post('/bookings/bulk-action', [AdminBookingController::class, 'bulkAction'])->name('bookings.bulk-action');
        Route::get('/bookings/export', [AdminBookingController::class, 'export'])->name('bookings.export');
        // PDF routes must come before parameterized routes
        Route::get('/bookings/{id}/pdf', [AdminBookingController::class, 'downloadPDF'])->name('bookings.pdf');
        Route::get('/bookings/{id}/pdf/view', [AdminBookingController::class, 'viewPDF'])->name('bookings.pdf.view');
        // Parameterized routes come after specific routes
        Route::get('/bookings/{id}', [AdminBookingController::class, 'show'])->name('bookings.show');
        Route::put('/bookings/{id}', [AdminBookingController::class, 'update'])->name('bookings.update');
        Route::delete('/bookings/{id}', [AdminBookingController::class, 'destroy'])->name('bookings.destroy');
        Route::match(['get', 'post'], '/bookings/{id}/status', [AdminBookingController::class, 'updateStatus'])->name('bookings.update-status');
        Route::post('/bookings/{id}/confirm', [AdminBookingController::class, 'confirm'])->name('bookings.confirm');
        Route::post('/bookings/{id}/confirm-payment', [AdminBookingController::class, 'confirmPayment'])->name('bookings.confirm-payment');
        Route::post('/bookings/{id}/cancel', [AdminBookingController::class, 'cancel'])->name('bookings.cancel');
        Route::post('/bookings/{id}/approve', [AdminBookingController::class, 'approve'])->name('bookings.approve');
        Route::post('/bookings/{id}/reject', [AdminBookingController::class, 'reject'])->name('bookings.reject');
        Route::post('/bookings/{id}/restore', [AdminBookingController::class, 'restore'])->name('bookings.restore');
        Route::post('/bookings/{id}/record-payment', [AdminBookingController::class, 'recordPayment'])->name('bookings.record-payment');
        Route::post('/bookings/{id}/convert-to-invoice', [AdminBookingController::class, 'convertToInvoice'])->name('bookings.convert-to-invoice');
        Route::post('/bookings/{id}/add-itinerary', [AdminBookingController::class, 'addItinerary'])->name('bookings.add-itinerary');
        Route::post('/bookings/{id}/add-transport', [AdminBookingController::class, 'addTransport'])->name('bookings.add-transport');
        Route::post('/bookings/{id}/add-guide', [AdminBookingController::class, 'addGuide'])->name('bookings.add-guide');
        Route::post('/bookings/{id}/send-voucher', [AdminBookingController::class, 'sendVoucher'])->name('bookings.send-voucher');
        Route::post('/bookings/{id}/send-whatsapp', [AdminBookingController::class, 'sendWhatsApp'])->name('bookings.send-whatsapp');
        Route::post('/bookings/{id}/mark-completed', [AdminBookingController::class, 'markCompleted'])->name('bookings.mark-completed');
        Route::post('/bookings/{id}/mark-in-progress', [AdminBookingController::class, 'markInProgress'])->name('bookings.mark-in-progress');
    });
    
    // Quotations - System Administrator, Travel Consultant, Reservations Officer
    Route::middleware(['role:System Administrator|Travel Consultant|Reservations Officer'])->group(function () {
        Route::get('/quotations', [AdminQuotationController::class, 'index'])->name('quotations.index');
        Route::get('/quotations/create', [AdminQuotationController::class, 'create'])->name('quotations.create');
        Route::post('/quotations', [AdminQuotationController::class, 'store'])->name('quotations.store');
        // Specific routes must come before parameterized routes
        Route::get('/quotations/pending', [AdminQuotationController::class, 'pending'])->name('quotations.pending');
        Route::get('/quotations/sent', [AdminQuotationController::class, 'sent'])->name('quotations.sent');
        Route::get('/quotations/accepted', [AdminQuotationController::class, 'accepted'])->name('quotations.accepted');
        Route::get('/quotations/tour/{id}/details', [AdminQuotationController::class, 'getTourDetails'])->name('quotations.tour-details');
        // PDF routes must come before parameterized {id} route
        Route::get('/quotations/export', [AdminQuotationController::class, 'export'])->name('quotations.export');
        Route::get('/quotations/{id}/pdf', [AdminQuotationController::class, 'downloadPDF'])->name('quotations.pdf');
        Route::get('/quotations/{id}/pdf/view', [AdminQuotationController::class, 'viewPDF'])->name('quotations.pdf.view');
        Route::get('/quotations/{id}/print', [AdminQuotationController::class, 'print'])->name('quotations.print');
        Route::post('/quotations/{id}/duplicate', [AdminQuotationController::class, 'duplicate'])->name('quotations.duplicate');
        Route::post('/quotations/{id}/convert-to-booking', [AdminQuotationController::class, 'convertToBooking'])->name('quotations.convert-to-booking');
        Route::post('/quotations/{id}/send-whatsapp', [AdminQuotationController::class, 'sendWhatsApp'])->name('quotations.send-whatsapp');
        Route::post('/quotations/{id}/notes', [AdminQuotationController::class, 'addNote'])->name('quotations.add-note');
        // Parameterized routes come after all specific routes
        Route::get('/quotations/{id}', [AdminQuotationController::class, 'show'])->name('quotations.show');
        Route::get('/quotations/{id}/edit', [AdminQuotationController::class, 'edit'])->name('quotations.edit');
        Route::put('/quotations/{id}', [AdminQuotationController::class, 'update'])->name('quotations.update');
        Route::delete('/quotations/{id}', [AdminQuotationController::class, 'destroy'])->name('quotations.destroy');
        Route::post('/quotations/{id}/status', [AdminQuotationController::class, 'updateStatus'])->name('quotations.update-status');
        Route::post('/quotations/{id}/send', [AdminQuotationController::class, 'send'])->name('quotations.send');
        Route::post('/quotations/{id}/accept', [AdminQuotationController::class, 'accept'])->name('quotations.accept');
    });
    
    // Tours - System Administrator, Content Manager, Travel Consultant
    Route::middleware(['role:System Administrator|Content Manager|Travel Consultant'])->group(function () {
        Route::get('/tours', [AdminTourController::class, 'index'])->name('tours.index');
        Route::get('/tours/create', [AdminTourController::class, 'create'])->name('tours.create');
        Route::post('/tours', [AdminTourController::class, 'store'])->name('tours.store');
        Route::put('/tours/{id}/status', [AdminTourController::class, 'updateStatus'])->name('tours.update-status');
        
        // Bulk actions
        Route::post('/tours/bulk-action', [AdminTourController::class, 'bulkAction'])->name('tours.bulk-action');
        Route::get('/tours/export', [AdminTourController::class, 'export'])->name('tours.export');
        
        // Itinerary Builder
        Route::get('/tours/itinerary-builder', [AdminTourController::class, 'itineraryBuilder'])->name('tours.itinerary-builder');
        Route::get('/tours/itinerary/{id}', [AdminTourController::class, 'getItinerary'])->name('tours.itinerary.show');
        Route::post('/tours/itinerary', [AdminTourController::class, 'storeItinerary'])->name('tours.itinerary.store');
        Route::put('/tours/itinerary/{id}', [AdminTourController::class, 'updateItinerary'])->name('tours.itinerary.update');
        Route::delete('/tours/itinerary/{id}', [AdminTourController::class, 'deleteItinerary'])->name('tours.itinerary.delete');
        Route::post('/tours/itinerary/{id}/clone', [AdminTourController::class, 'cloneItinerary'])->name('tours.itinerary.clone');
        Route::post('/tours/itinerary/reorder', [AdminTourController::class, 'reorderItinerary'])->name('tours.itinerary.reorder');
        Route::post('/tours/itinerary/copy', [AdminTourController::class, 'copyItinerary'])->name('tours.itinerary.copy');
        Route::get('/tours/{tourId}/itinerary/export', [AdminTourController::class, 'exportItinerary'])->name('tours.itinerary.export');
        Route::post('/tours/{tourId}/itinerary/import', [AdminTourController::class, 'importItinerary'])->name('tours.itinerary.import');
        Route::post('/tours/{tourId}/itinerary/reset', [AdminTourController::class, 'resetItinerary'])->name('tours.itinerary.reset');
        Route::get('/tours/{tourId}/itinerary/print', [AdminTourController::class, 'printItinerary'])->name('tours.itinerary.print');
        
        // Availability
        Route::get('/tours/availability', [AdminTourController::class, 'availability'])->name('tours.availability');
        Route::post('/tours/availability', [AdminTourController::class, 'storeAvailability'])->name('tours.availability.store');
        Route::put('/tours/{tourId}/availability', [AdminTourController::class, 'updateAvailability'])->name('tours.availability.update');
        Route::get('/tours/{id}/availability-calendar', [AdminTourController::class, 'availabilityCalendar'])->name('tours.availability.calendar');
        
        // Pricing
        Route::get('/tours/pricing', [AdminTourController::class, 'pricing'])->name('tours.pricing');
        Route::post('/tours/pricing', [AdminTourController::class, 'storePricing'])->name('tours.pricing.store');
        Route::put('/tours/{id}/pricing', [AdminTourController::class, 'updatePricing'])->name('tours.pricing.update');
        Route::get('/tours/{id}/pricing-details', [AdminTourController::class, 'getPricingDetails'])->name('tours.pricing-details');
        
        // Tour details
        Route::get('/tours/{id}/details', [AdminTourController::class, 'getTourDetails'])->name('tours.details');
        Route::get('/tours/{id}/details-partial', [AdminTourController::class, 'detailsPartial'])->name('tours.details.partial');
        
        // Parameterized routes come after specific routes
        Route::get('/tours/{id}', [AdminTourController::class, 'show'])->name('tours.show');
        Route::get('/tours/{id}/edit', [AdminTourController::class, 'edit'])->name('tours.edit');
        Route::put('/tours/{id}', [AdminTourController::class, 'update'])->name('tours.update');
        Route::post('/tours/{id}/duplicate', [AdminTourController::class, 'duplicate'])->name('tours.duplicate');
        Route::delete('/tours/{id}', [AdminTourController::class, 'destroy'])->name('tours.destroy');
        
        Route::get('/destinations', [DashboardController::class, 'destinations'])->name('destinations.index');
        Route::prefix('categories')->name('categories.')->group(function () {
            Route::get('/', [\App\Http\Controllers\Admin\CategoryController::class, 'index'])->name('index');
            Route::get('/create', [\App\Http\Controllers\Admin\CategoryController::class, 'create'])->name('create');
            Route::post('/', [\App\Http\Controllers\Admin\CategoryController::class, 'store'])->name('store');
            Route::get('/{id}', [\App\Http\Controllers\Admin\CategoryController::class, 'show'])->name('show');
            Route::get('/{id}/edit', [\App\Http\Controllers\Admin\CategoryController::class, 'edit'])->name('edit');
            Route::put('/{id}', [\App\Http\Controllers\Admin\CategoryController::class, 'update'])->name('update');
            Route::delete('/{id}', [\App\Http\Controllers\Admin\CategoryController::class, 'destroy'])->name('destroy');
        });
    });
    
    // Clients - System Administrator, Travel Consultant, Reservations Officer
    Route::middleware(['role:System Administrator|Travel Consultant|Reservations Officer'])->group(function () {
        Route::get('/clients', [DashboardController::class, 'clients'])->name('clients.index');
        Route::get('/clients/create', [DashboardController::class, 'createClient'])->name('clients.create');
    });
    
    // Finance - System Administrator, Finance Officer
    Route::middleware(['role:System Administrator|Finance Officer'])->group(function () {
        // Redirect old routes to new finance routes (for backward compatibility)
        Route::get('/payments', fn() => redirect()->route('admin.finance.payments'))->name('payments.index');
        Route::get('/invoices', fn() => redirect()->route('admin.finance.invoices'))->name('invoices.index');
        Route::get('/expenses', fn() => redirect()->route('admin.finance.expenses'))->name('expenses.index');
    });
    
    // Marketing - System Administrator, Marketing Officer
    Route::middleware(['role:System Administrator|Marketing Officer'])->group(function () {
        Route::get('/promotions', [DashboardController::class, 'promotions'])->name('promotions.index');
        Route::get('/campaigns', [DashboardController::class, 'campaigns'])->name('campaigns.index');
    });
    
    // Hotels - System Administrator, Hotel Partner, Travel Consultant
    Route::middleware(['role:System Administrator|Hotel Partner|Travel Consultant'])->group(function () {
        Route::get('/hotels', [AdminHotelController::class, 'index'])->name('hotels.index');
        Route::get('/hotels/create', [AdminHotelController::class, 'create'])->name('hotels.create');
        Route::post('/hotels', [AdminHotelController::class, 'store'])->name('hotels.store');
        // Specific routes must come before parameterized routes
        Route::get('/hotels/room-types', [AdminHotelController::class, 'roomTypes'])->name('hotels.room-types');
        Route::post('/hotels/room-types', [AdminHotelController::class, 'storeRoomType'])->name('hotels.room-types.store');
        Route::get('/hotels/room-types/{id}', [AdminHotelController::class, 'getRoomType'])->name('hotels.room-types.show');
        Route::put('/hotels/room-types/{id}', [AdminHotelController::class, 'updateRoomType'])->name('hotels.room-types.update');
        Route::delete('/hotels/room-types/{id}', [AdminHotelController::class, 'deleteRoomType'])->name('hotels.room-types.delete');
        Route::get('/hotels/room-pricing', [AdminHotelController::class, 'roomPricing'])->name('hotels.room-pricing');
        Route::post('/hotels/room-pricing', [AdminHotelController::class, 'storeRoomPricing'])->name('hotels.room-pricing.store');
        Route::get('/hotels/availability', [AdminHotelController::class, 'availability'])->name('hotels.availability');
        Route::get('/hotels/partner-portal', [AdminHotelController::class, 'partnerPortal'])->name('hotels.partner-portal');
        // Parameterized routes come after specific routes
        Route::get('/hotels/{id}/details', [AdminHotelController::class, 'getHotelDetails'])->name('hotels.details');
        Route::get('/hotels/{id}', [AdminHotelController::class, 'show'])->name('hotels.show');
        Route::get('/hotels/{id}/edit', [AdminHotelController::class, 'edit'])->name('hotels.edit');
        Route::put('/hotels/{id}', [AdminHotelController::class, 'update'])->name('hotels.update');
        Route::delete('/hotels/{id}', [AdminHotelController::class, 'destroy'])->name('hotels.destroy');
    });
    
    // Vehicles - System Administrator, Driver/Guide, Travel Consultant, Reservations Officer
    Route::middleware(['role:System Administrator|Driver/Guide|Travel Consultant|Reservations Officer'])->group(function () {
        // Specific routes must come before parameterized routes
        Route::get('/vehicles', [AdminVehicleController::class, 'index'])->name('vehicles.index');
        Route::get('/vehicles/create', [AdminVehicleController::class, 'create'])->name('vehicles.create');
        Route::post('/vehicles', [AdminVehicleController::class, 'store'])->name('vehicles.store');
        Route::get('/vehicles/export-fleet', [AdminVehicleController::class, 'exportFleet'])->name('vehicles.export-fleet');
        Route::get('/vehicles/calendar-data', [AdminVehicleController::class, 'getCalendarData'])->name('vehicles.calendar-data');
        Route::get('/vehicles/{id}/maintenance-log', [AdminVehicleController::class, 'getMaintenanceLog'])->name('vehicles.maintenance-log');
        Route::post('/vehicles/maintenance', [AdminVehicleController::class, 'storeMaintenance'])->name('vehicles.store-maintenance');
        Route::post('/vehicles/documents', [AdminVehicleController::class, 'storeDocument'])->name('vehicles.store-document');
        Route::delete('/vehicles/documents/{id}', [AdminVehicleController::class, 'deleteDocument'])->name('vehicles.delete-document');
        Route::get('/vehicles/drivers', [AdminVehicleController::class, 'drivers'])->name('vehicles.drivers');
        Route::get('/vehicles/drivers/{id}/vehicles', [AdminVehicleController::class, 'getDriverVehicles'])->name('vehicles.drivers.vehicles');
        Route::get('/vehicles/assign-driver', [AdminVehicleController::class, 'assignDriver'])->name('vehicles.assign-driver');
        Route::post('/vehicles/assign-driver', [AdminVehicleController::class, 'storeAssignDriver'])->name('vehicles.assign-driver.store');
        Route::post('/vehicles/assign-vehicle', [AdminVehicleController::class, 'assignVehicle'])->name('vehicles.assign-vehicle');
        Route::get('/vehicles/operations/{id}/details', [AdminVehicleController::class, 'getOperationDetails'])->name('vehicles.operations.details');
        Route::put('/vehicles/operations/{id}', [AdminVehicleController::class, 'updateOperation'])->name('vehicles.operations.update');
        Route::delete('/vehicles/operations/{id}', [AdminVehicleController::class, 'destroyOperation'])->name('vehicles.operations.destroy');
        Route::get('/vehicles/availability', [AdminVehicleController::class, 'availability'])->name('vehicles.availability');
        Route::get('/vehicles/bookings', [AdminVehicleController::class, 'bookings'])->name('vehicles.bookings');
        Route::get('/vehicles/transport-bookings', [AdminVehicleController::class, 'transportBookings'])->name('vehicles.transport-bookings');
        Route::get('/vehicles/transport-bookings/create', [AdminVehicleController::class, 'createTransportBooking'])->name('vehicles.transport-bookings.create');
        Route::post('/vehicles/transport-bookings', [AdminVehicleController::class, 'storeTransportBooking'])->name('vehicles.transport-bookings.store');
        Route::put('/vehicles/transport-bookings/{id}', [AdminVehicleController::class, 'updateTransportBooking'])->name('vehicles.transport-bookings.update');
        // Parameterized routes come after specific routes
        Route::get('/vehicles/{id}/edit', [AdminVehicleController::class, 'edit'])->name('vehicles.edit');
        Route::put('/vehicles/{id}/status', [AdminVehicleController::class, 'updateStatus'])->name('vehicles.update-status');
        Route::put('/vehicles/{id}', [AdminVehicleController::class, 'update'])->name('vehicles.update');
        Route::delete('/vehicles/{id}', [AdminVehicleController::class, 'destroy'])->name('vehicles.destroy');
        Route::get('/vehicles/{id}', [AdminVehicleController::class, 'show'])->name('vehicles.show');
    });
    
    // Reports - System Administrator, Finance Officer, Travel Consultant
    Route::middleware(['role:System Administrator|Finance Officer|Travel Consultant'])->group(function () {
        Route::get('/reports/bookings', [DashboardController::class, 'bookingReports'])->name('reports.bookings');
        Route::get('/reports/revenue', [DashboardController::class, 'revenueReports'])->name('reports.revenue');
    });
    
    // Customers - System Administrator, Travel Consultant, Reservations Officer
    Route::middleware(['role:System Administrator|Travel Consultant|Reservations Officer'])->group(function () {
        Route::get('/customers', [AdminCustomerController::class, 'index'])->name('customers.index');
        Route::get('/customers/create', [AdminCustomerController::class, 'create'])->name('customers.create');
        Route::post('/customers', [AdminCustomerController::class, 'store'])->name('customers.store');
        Route::get('/customers/{id}', [AdminCustomerController::class, 'show'])->name('customers.show');
        Route::get('/customers/{id}/edit', [AdminCustomerController::class, 'edit'])->name('customers.edit');
        Route::put('/customers/{id}', [AdminCustomerController::class, 'update'])->name('customers.update');
        Route::delete('/customers/{id}', [AdminCustomerController::class, 'destroy'])->name('customers.destroy');
        Route::post('/customers/bulk-action', [AdminCustomerController::class, 'bulkAction'])->name('customers.bulk-action');
        Route::get('/customers/export', [AdminCustomerController::class, 'export'])->name('customers.export');
        
        // Customer Groups
        Route::get('/customers/groups', [AdminCustomerController::class, 'groups'])->name('customers.groups');
        Route::post('/customers/groups', [AdminCustomerController::class, 'storeGroup'])->name('customers.groups.store');
        Route::put('/customers/groups/{id}', [AdminCustomerController::class, 'updateGroup'])->name('customers.groups.update');
        Route::delete('/customers/groups/{id}', [AdminCustomerController::class, 'destroyGroup'])->name('customers.groups.destroy');
        
        // Customer Feedback
        Route::get('/customers/feedback', [AdminCustomerController::class, 'feedback'])->name('customers.feedback');
        Route::put('/customers/feedback/{id}', [AdminCustomerController::class, 'updateFeedback'])->name('customers.feedback.update');
        
        // Customer Messages
        Route::get('/customers/messages', [AdminCustomerController::class, 'messages'])->name('customers.messages');
        Route::post('/customers/messages/{id}/reply', [AdminCustomerController::class, 'replyMessage'])->name('customers.messages.reply');
    });
    
    // Finance - System Administrator, Finance Officer
    Route::middleware(['role:System Administrator|Finance Officer'])->group(function () {
        Route::get('/finance/payments', [AdminFinanceController::class, 'payments'])->name('finance.payments');
        Route::get('/finance/payments/{id}', [AdminFinanceController::class, 'showPayment'])->name('finance.payments.show');
        
        // Invoices
        Route::get('/finance/invoices', [AdminFinanceController::class, 'invoices'])->name('finance.invoices');
        Route::get('/finance/invoices/create', [AdminFinanceController::class, 'createInvoice'])->name('finance.invoices.create');
        Route::post('/finance/invoices', [AdminFinanceController::class, 'storeInvoice'])->name('finance.invoices.store');
        Route::get('/finance/invoices/{id}/edit', [AdminFinanceController::class, 'editInvoice'])->name('finance.invoices.edit');
        Route::get('/finance/invoices/{id}/receipt', [AdminFinanceController::class, 'generateReceipt'])->name('finance.receipt');
        Route::get('/finance/invoices/{id}/pdf', [AdminFinanceController::class, 'downloadInvoicePDF'])->name('finance.invoices.pdf');
        Route::get('/finance/invoices/{id}/print', [AdminFinanceController::class, 'printInvoice'])->name('finance.invoices.print');
        Route::get('/finance/invoices/{id}', [AdminFinanceController::class, 'showInvoice'])->name('finance.invoices.show');
        Route::put('/finance/invoices/{id}', [AdminFinanceController::class, 'updateInvoice'])->name('finance.invoices.update');
        Route::delete('/finance/invoices/{id}', [AdminFinanceController::class, 'destroyInvoice'])->name('finance.invoices.destroy');
        Route::post('/finance/invoices/{id}/payments', [AdminFinanceController::class, 'storePayment'])->name('finance.invoices.payments.store');
        
        Route::get('/finance/refunds', [AdminFinanceController::class, 'refunds'])->name('finance.refunds');
        
        // Expenses
        Route::get('/finance/expenses', [AdminFinanceController::class, 'expenses'])->name('finance.expenses');
        Route::get('/finance/expenses/create', [AdminFinanceController::class, 'createExpense'])->name('finance.expenses.create');
        Route::post('/finance/expenses', [AdminFinanceController::class, 'storeExpense'])->name('finance.expenses.store');
        Route::get('/finance/expenses/{id}/edit', [AdminFinanceController::class, 'editExpense'])->name('finance.expenses.edit');
        Route::put('/finance/expenses/{id}', [AdminFinanceController::class, 'updateExpense'])->name('finance.expenses.update');
        Route::delete('/finance/expenses/{id}', [AdminFinanceController::class, 'destroyExpense'])->name('finance.expenses.destroy');
        
        Route::get('/finance/revenue-reports', [AdminFinanceController::class, 'revenueReports'])->name('finance.revenue-reports');
        Route::get('/finance/statements', [AdminFinanceController::class, 'statements'])->name('finance.statements');
    });
    
    // Test Email Route
    Route::middleware(['auth'])->group(function () {
        Route::post('/test-email', [\App\Http\Controllers\Admin\TestEmailController::class, 'testEmail'])->name('test.email');
    });
    
    // Documents - All authenticated admin users can generate documents
    Route::middleware(['auth'])->group(function () {
        // Customer-Facing Booking Documents
        Route::get('/documents/booking/{id}/confirmation-voucher', [AdminDocumentController::class, 'bookingConfirmationVoucher'])->name('documents.booking.confirmation-voucher');
        Route::post('/documents/booking/{id}/confirmation-voucher/send', [AdminDocumentController::class, 'sendBookingConfirmationVoucher'])->name('documents.booking.confirmation-voucher.send');
        Route::get('/documents/booking/{id}/tour-voucher', [AdminDocumentController::class, 'tourVoucher'])->name('documents.booking.tour-voucher');
        Route::post('/documents/booking/{id}/tour-voucher/send', [AdminDocumentController::class, 'sendTourVoucher'])->name('documents.booking.tour-voucher.send');
        Route::get('/documents/payment/{id}/receipt', [AdminDocumentController::class, 'paymentReceipt'])->name('documents.payment.receipt');
        Route::post('/documents/payment/{id}/receipt/send', [AdminDocumentController::class, 'sendPaymentReceipt'])->name('documents.payment.receipt.send');
        Route::get('/documents/booking/{id}/proforma-invoice', [AdminDocumentController::class, 'proformaInvoice'])->name('documents.booking.proforma-invoice');
        Route::post('/documents/booking/{id}/proforma-invoice/send', [AdminDocumentController::class, 'sendProformaInvoice'])->name('documents.booking.proforma-invoice.send');
        Route::get('/documents/invoice/{id}/final', [AdminDocumentController::class, 'finalInvoice'])->name('documents.invoice.final');
        Route::post('/documents/invoice/{id}/final/send', [AdminDocumentController::class, 'sendFinalInvoice'])->name('documents.invoice.final.send');
        Route::get('/documents/booking/{id}/eticket', [AdminDocumentController::class, 'eticket'])->name('documents.booking.eticket');
        Route::get('/documents/booking/{id}/cancellation-notice', [AdminDocumentController::class, 'cancellationNotice'])->name('documents.booking.cancellation-notice');
        Route::get('/documents/booking/{id}/refund-receipt', [AdminDocumentController::class, 'refundReceipt'])->name('documents.booking.refund-receipt');
        Route::get('/documents/booking/{id}/travel-checklist', [AdminDocumentController::class, 'travelChecklist'])->name('documents.booking.travel-checklist');
        Route::post('/documents/booking/{id}/travel-checklist/send', [AdminDocumentController::class, 'sendTravelChecklist'])->name('documents.booking.travel-checklist.send');
        Route::get('/documents/booking/{id}/completion-certificate', [AdminDocumentController::class, 'completionCertificate'])->name('documents.booking.completion-certificate');
        Route::post('/documents/booking/{id}/completion-certificate/send', [AdminDocumentController::class, 'sendCompletionCertificate'])->name('documents.booking.completion-certificate.send');
        Route::get('/documents/booking/{id}/amendment', [AdminDocumentController::class, 'bookingAmendment'])->name('documents.booking.amendment');
        
        // Internal Booking Documents
        Route::get('/documents/booking/{id}/booking-sheet', [AdminDocumentController::class, 'bookingSheet'])->name('documents.booking.sheet');
        Route::get('/documents/departure/manifest', [AdminDocumentController::class, 'dailyDepartureManifest'])->name('documents.departure.manifest');
        Route::get('/documents/booking/{id}/passenger-list', [AdminDocumentController::class, 'passengerList'])->name('documents.booking.passenger-list');
        Route::get('/documents/rooming/list', [AdminDocumentController::class, 'roomingList'])->name('documents.rooming.list');
        Route::get('/documents/transport/allocation', [AdminDocumentController::class, 'transportAllocationSheet'])->name('documents.transport.allocation');
        Route::get('/documents/guide/assignment', [AdminDocumentController::class, 'guideAssignmentForm'])->name('documents.guide.assignment');
        
        // Tour Package Documents
        Route::get('/documents/tour/{id}/overview', [AdminDocumentController::class, 'tourOverview'])->name('documents.tour.overview');
        Route::get('/documents/tour/{id}/detailed-itinerary', [AdminDocumentController::class, 'detailedItinerary'])->name('documents.tour.detailed-itinerary');
        Route::get('/documents/tour/{id}/pricing-sheet', [AdminDocumentController::class, 'tourPricingSheet'])->name('documents.tour.pricing-sheet');
        Route::get('/documents/tour/{id}/availability-calendar', [AdminDocumentController::class, 'tourAvailabilityCalendar'])->name('documents.tour.availability-calendar');
        Route::get('/documents/tour/{id}/inclusion-exclusion', [AdminDocumentController::class, 'inclusionExclusionList'])->name('documents.tour.inclusion-exclusion');
        Route::get('/documents/tour/{id}/terms-conditions', [AdminDocumentController::class, 'termsConditions'])->name('documents.tour.terms-conditions');
        
        // Operations Documents
        Route::get('/documents/operations/daily-plan', [AdminDocumentController::class, 'dailyOperationPlan'])->name('documents.operations.daily-plan');
        Route::get('/documents/booking/{id}/guide-briefing', [AdminDocumentController::class, 'guideBriefingNotes'])->name('documents.booking.guide-briefing');
        Route::get('/documents/driver/movement-sheet', [AdminDocumentController::class, 'driverMovementSheet'])->name('documents.driver.movement-sheet');
        Route::get('/documents/meal-plan/report', [AdminDocumentController::class, 'mealPlanReport'])->name('documents.meal-plan.report');
        Route::get('/documents/park-fees/summary', [AdminDocumentController::class, 'parkFeesSummary'])->name('documents.park-fees.summary');
        
        // Finance Documents
        Route::get('/documents/invoice/{id}/credit-note', [AdminDocumentController::class, 'creditNote'])->name('documents.invoice.credit-note');
        Route::get('/documents/expense/{id}/supplier-payment-voucher', [AdminDocumentController::class, 'supplierPaymentVoucher'])->name('documents.expense.supplier-payment-voucher');
        Route::get('/documents/commission/statement', [AdminDocumentController::class, 'commissionStatement'])->name('documents.commission.statement');
        Route::get('/documents/revenue/report', [AdminDocumentController::class, 'revenueReport'])->name('documents.revenue.report');
        Route::get('/documents/cash-collection/daily', [AdminDocumentController::class, 'dailyCashCollectionReport'])->name('documents.cash-collection.daily');
        Route::get('/documents/tour/{id}/profit-loss', [AdminDocumentController::class, 'profitLossPerTour'])->name('documents.tour.profit-loss');
        Route::get('/documents/profit-loss/month', [AdminDocumentController::class, 'profitLossPerMonth'])->name('documents.profit-loss.month');
        Route::get('/documents/expense/breakdown', [AdminDocumentController::class, 'expenseBreakdown'])->name('documents.expense.breakdown');
        Route::get('/documents/outstanding/payments', [AdminDocumentController::class, 'outstandingPaymentsList'])->name('documents.outstanding.payments');
        Route::get('/documents/aging/report', [AdminDocumentController::class, 'agingReport'])->name('documents.aging.report');
        
        // Fleet & Transport Documents
        Route::get('/documents/booking/{id}/transport-booking-sheet', [AdminDocumentController::class, 'transportBookingSheet'])->name('documents.booking.transport-booking-sheet');
        Route::get('/documents/driver/assignment', [AdminDocumentController::class, 'driverAssignmentDocument'])->name('documents.driver.assignment');
        Route::get('/documents/vehicle/{id}/logbook', [AdminDocumentController::class, 'vehicleLogbook'])->name('documents.vehicle.logbook');
        Route::get('/documents/fuel/request-voucher', [AdminDocumentController::class, 'fuelRequestVoucher'])->name('documents.fuel.request-voucher');
        Route::get('/documents/vehicle/{id}/maintenance-report', [AdminDocumentController::class, 'maintenanceReport'])->name('documents.vehicle.maintenance-report');
        Route::get('/documents/vehicle/{id}/condition-checklist', [AdminDocumentController::class, 'vehicleConditionChecklist'])->name('documents.vehicle.condition-checklist');
        Route::get('/documents/booking/{id}/trip-manifest', [AdminDocumentController::class, 'tripManifest'])->name('documents.booking.trip-manifest');
        Route::get('/documents/transport/cost-report', [AdminDocumentController::class, 'transportCostReport'])->name('documents.transport.cost-report');
    });
    
    // Marketing - System Administrator, Marketing Officer
    Route::middleware(['role:System Administrator|Marketing Officer'])->group(function () {
        Route::get('/marketing/dashboard', [AdminMarketingController::class, 'dashboard'])->name('marketing.dashboard');
        
        // Promo Codes
        Route::get('/marketing/promo-codes', [AdminMarketingController::class, 'promoCodes'])->name('marketing.promo-codes');
        Route::get('/marketing/promo-codes/create', [AdminMarketingController::class, 'createPromoCode'])->name('marketing.promo-codes.create');
        Route::post('/marketing/promo-codes', [AdminMarketingController::class, 'storePromoCode'])->name('marketing.promo-codes.store');
        Route::get('/marketing/promo-codes/{id}/edit', [AdminMarketingController::class, 'editPromoCode'])->name('marketing.promo-codes.edit');
        Route::put('/marketing/promo-codes/{id}', [AdminMarketingController::class, 'updatePromoCode'])->name('marketing.promo-codes.update');
        Route::delete('/marketing/promo-codes/{id}', [AdminMarketingController::class, 'destroyPromoCode'])->name('marketing.promo-codes.destroy');
        
        // Email Campaigns
        Route::get('/marketing/email-campaigns', [AdminMarketingController::class, 'emailCampaigns'])->name('marketing.email-campaigns');
        Route::get('/marketing/email-campaigns/create', [AdminMarketingController::class, 'createEmailCampaign'])->name('marketing.email-campaigns.create');
        Route::post('/marketing/email-campaigns', [AdminMarketingController::class, 'storeEmailCampaign'])->name('marketing.email-campaigns.store');
        Route::get('/marketing/email-campaigns/{id}/edit', [AdminMarketingController::class, 'editEmailCampaign'])->name('marketing.email-campaigns.edit');
        Route::put('/marketing/email-campaigns/{id}', [AdminMarketingController::class, 'updateEmailCampaign'])->name('marketing.email-campaigns.update');
        Route::delete('/marketing/email-campaigns/{id}', [AdminMarketingController::class, 'destroyEmailCampaign'])->name('marketing.email-campaigns.destroy');
        
        // SMS Campaigns
        Route::get('/marketing/sms-campaigns', [AdminMarketingController::class, 'smsCampaigns'])->name('marketing.sms-campaigns');
        Route::get('/marketing/sms-campaigns/create', [AdminMarketingController::class, 'createSmsCampaign'])->name('marketing.sms-campaigns.create');
        Route::post('/marketing/sms-campaigns', [AdminMarketingController::class, 'storeSmsCampaign'])->name('marketing.sms-campaigns.store');
        Route::get('/marketing/sms-campaigns/{id}/edit', [AdminMarketingController::class, 'editSmsCampaign'])->name('marketing.sms-campaigns.edit');
        Route::put('/marketing/sms-campaigns/{id}', [AdminMarketingController::class, 'updateSmsCampaign'])->name('marketing.sms-campaigns.update');
        Route::post('/marketing/sms-campaigns/{id}/send', [AdminMarketingController::class, 'sendSmsCampaign'])->name('marketing.sms-campaigns.send');
        Route::delete('/marketing/sms-campaigns/{id}', [AdminMarketingController::class, 'destroySmsCampaign'])->name('marketing.sms-campaigns.destroy');
        
        // Social Media Scheduler
        Route::get('/marketing/social-media', [AdminMarketingController::class, 'socialMedia'])->name('marketing.social-media');
        Route::get('/marketing/social-media/create', [AdminMarketingController::class, 'createSocialMedia'])->name('marketing.social-media.create');
        Route::post('/marketing/social-media', [AdminMarketingController::class, 'storeSocialMedia'])->name('marketing.social-media.store');
        Route::get('/marketing/social-media/{id}/edit', [AdminMarketingController::class, 'editSocialMedia'])->name('marketing.social-media.edit');
        Route::put('/marketing/social-media/{id}', [AdminMarketingController::class, 'updateSocialMedia'])->name('marketing.social-media.update');
        Route::post('/marketing/social-media/{id}/publish', [AdminMarketingController::class, 'publishSocialMedia'])->name('marketing.social-media.publish');
        Route::delete('/marketing/social-media/{id}', [AdminMarketingController::class, 'destroySocialMedia'])->name('marketing.social-media.destroy');
        
        // Landing Pages
        Route::get('/marketing/landing-pages', [AdminMarketingController::class, 'landingPages'])->name('marketing.landing-pages');
        Route::get('/marketing/landing-pages/create', [AdminMarketingController::class, 'createLandingPage'])->name('marketing.landing-pages.create');
        Route::post('/marketing/landing-pages', [AdminMarketingController::class, 'storeLandingPage'])->name('marketing.landing-pages.store');
        Route::get('/marketing/landing-pages/{id}/edit', [AdminMarketingController::class, 'editLandingPage'])->name('marketing.landing-pages.edit');
        Route::put('/marketing/landing-pages/{id}', [AdminMarketingController::class, 'updateLandingPage'])->name('marketing.landing-pages.update');
        Route::delete('/marketing/landing-pages/{id}', [AdminMarketingController::class, 'destroyLandingPage'])->name('marketing.landing-pages.destroy');
        
        // Marketing Analytics
        Route::get('/marketing/analytics', [AdminMarketingController::class, 'analytics'])->name('marketing.analytics');
        
        // Banners & Popups
        Route::get('/marketing/banners', [AdminMarketingController::class, 'banners'])->name('marketing.banners');
        Route::get('/marketing/banners/create', [AdminMarketingController::class, 'createBanner'])->name('marketing.banners.create');
        Route::post('/marketing/banners', [AdminMarketingController::class, 'storeBanner'])->name('marketing.banners.store');
        Route::get('/marketing/banners/{id}/edit', [AdminMarketingController::class, 'editBanner'])->name('marketing.banners.edit');
        Route::put('/marketing/banners/{id}', [AdminMarketingController::class, 'updateBanner'])->name('marketing.banners.update');
        Route::post('/marketing/banners/{id}/toggle', [AdminMarketingController::class, 'toggleBanner'])->name('marketing.banners.toggle');
        Route::delete('/marketing/banners/{id}', [AdminMarketingController::class, 'destroyBanner'])->name('marketing.banners.destroy');
    });
    
    // Homepage Management - System Administrator, Content Manager
    Route::middleware(['role:System Administrator|Content Manager'])->group(function () {
        // Homepage Destinations
        Route::get('/homepage/destinations', [AdminHomepageController::class, 'destinations'])->name('homepage.destinations');
        Route::get('/homepage/destinations/get-images', [AdminHomepageController::class, 'getImages'])->name('homepage.destinations.get-images');
        Route::get('/homepage/destinations/create', [AdminHomepageController::class, 'createDestination'])->name('homepage.destinations.create');
        Route::post('/homepage/destinations', [AdminHomepageController::class, 'storeDestination'])->name('homepage.destinations.store');
        Route::get('/homepage/destinations/{id}', [AdminHomepageController::class, 'showDestination'])->name('homepage.destinations.show');
        Route::get('/homepage/destinations/{id}/edit', [AdminHomepageController::class, 'editDestination'])->name('homepage.destinations.edit');
        Route::put('/homepage/destinations/{id}', [AdminHomepageController::class, 'updateDestination'])->name('homepage.destinations.update');
        Route::delete('/homepage/destinations/{id}', [AdminHomepageController::class, 'destroyDestination'])->name('homepage.destinations.destroy');
        
        // About Page Management
        Route::get('/about-page', [App\Http\Controllers\Admin\AboutPageController::class, 'index'])->name('about-page.index');
        Route::put('/about-page/sections/{id}', [App\Http\Controllers\Admin\AboutPageController::class, 'updateSection'])->name('about-page.sections.update');
        
        // Team Members
        Route::post('/about-page/team-members', [App\Http\Controllers\Admin\AboutPageController::class, 'storeTeamMember'])->name('about-page.team-members.store');
        Route::put('/about-page/team-members/{id}', [App\Http\Controllers\Admin\AboutPageController::class, 'updateTeamMember'])->name('about-page.team-members.update');
        Route::delete('/about-page/team-members/{id}', [App\Http\Controllers\Admin\AboutPageController::class, 'deleteTeamMember'])->name('about-page.team-members.delete');
        
        // Values
        Route::post('/about-page/values', [App\Http\Controllers\Admin\AboutPageController::class, 'storeValue'])->name('about-page.values.store');
        Route::put('/about-page/values/{id}', [App\Http\Controllers\Admin\AboutPageController::class, 'updateValue'])->name('about-page.values.update');
        Route::delete('/about-page/values/{id}', [App\Http\Controllers\Admin\AboutPageController::class, 'deleteValue'])->name('about-page.values.delete');
        
        // Recognitions
        Route::post('/about-page/recognitions', [App\Http\Controllers\Admin\AboutPageController::class, 'storeRecognition'])->name('about-page.recognitions.store');
        Route::put('/about-page/recognitions/{id}', [App\Http\Controllers\Admin\AboutPageController::class, 'updateRecognition'])->name('about-page.recognitions.update');
        Route::delete('/about-page/recognitions/{id}', [App\Http\Controllers\Admin\AboutPageController::class, 'deleteRecognition'])->name('about-page.recognitions.delete');
        
        // Timeline Items
        Route::post('/about-page/timeline-items', [App\Http\Controllers\Admin\AboutPageController::class, 'storeTimelineItem'])->name('about-page.timeline-items.store');
        Route::put('/about-page/timeline-items/{id}', [App\Http\Controllers\Admin\AboutPageController::class, 'updateTimelineItem'])->name('about-page.timeline-items.update');
        Route::delete('/about-page/timeline-items/{id}', [App\Http\Controllers\Admin\AboutPageController::class, 'deleteTimelineItem'])->name('about-page.timeline-items.delete');
        
        // Statistics
        Route::post('/about-page/statistics', [App\Http\Controllers\Admin\AboutPageController::class, 'storeStatistic'])->name('about-page.statistics.store');
        Route::put('/about-page/statistics/{id}', [App\Http\Controllers\Admin\AboutPageController::class, 'updateStatistic'])->name('about-page.statistics.update');
        Route::delete('/about-page/statistics/{id}', [App\Http\Controllers\Admin\AboutPageController::class, 'deleteStatistic'])->name('about-page.statistics.delete');
        
        // Hero Slider
        Route::get('/homepage/hero-slider', [AdminHomepageController::class, 'heroSlider'])->name('homepage.hero-slider');
        Route::get('/homepage/hero-slider/create', [AdminHomepageController::class, 'createHeroSlide'])->name('homepage.hero-slider.create');
        Route::post('/homepage/hero-slider', [AdminHomepageController::class, 'storeHeroSlide'])->name('homepage.hero-slider.store');
        Route::get('/homepage/hero-slider/{id}', [AdminHomepageController::class, 'showHeroSlide'])->name('homepage.hero-slider.show');
        Route::get('/homepage/hero-slider/{id}/edit', [AdminHomepageController::class, 'editHeroSlide'])->name('homepage.hero-slider.edit');
        Route::put('/homepage/hero-slider/{id}', [AdminHomepageController::class, 'updateHeroSlide'])->name('homepage.hero-slider.update');
        Route::put('/homepage/hero-slider/{id}/toggle-status', [AdminHomepageController::class, 'toggleHeroSlideStatus'])->name('homepage.hero-slider.toggle-status');
        Route::delete('/homepage/hero-slider/{id}', [AdminHomepageController::class, 'destroyHeroSlide'])->name('homepage.hero-slider.destroy');
        Route::post('/homepage/hero-slider/update-order', [AdminHomepageController::class, 'updateHeroSlideOrder'])->name('homepage.hero-slider.update-order');
        
        // Gallery
        Route::get('/homepage/gallery', [AdminHomepageController::class, 'gallery'])->name('homepage.gallery');
        Route::get('/homepage/gallery/create', [AdminHomepageController::class, 'createGallery'])->name('homepage.gallery.create');
        Route::post('/homepage/gallery', [AdminHomepageController::class, 'storeGallery'])->name('homepage.gallery.store');
        Route::post('/homepage/gallery/bulk-action', [AdminHomepageController::class, 'bulkGalleryAction'])->name('homepage.gallery.bulk-action');
        Route::post('/homepage/gallery/update-order', [AdminHomepageController::class, 'updateGalleryOrder'])->name('homepage.gallery.update-order');
        // Specific routes must come before parameterized routes
        Route::get('/homepage/gallery/images', [AdminHomepageController::class, 'getGalleryImages'])->name('homepage.gallery.images');
        Route::get('/homepage/gallery/{id}/edit', [AdminHomepageController::class, 'editGallery'])->name('homepage.gallery.edit');
        // GET route for viewing gallery item (redirects to edit)
        Route::get('/homepage/gallery/{id}', [AdminHomepageController::class, 'showGallery'])->name('homepage.gallery.show');
        Route::put('/homepage/gallery/{id}', [AdminHomepageController::class, 'updateGallery'])->name('homepage.gallery.update');
        Route::delete('/homepage/gallery/{id}', [AdminHomepageController::class, 'destroyGallery'])->name('homepage.gallery.destroy');
        
        // Testimonials
        Route::get('/homepage/testimonials', [AdminHomepageController::class, 'testimonials'])->name('homepage.testimonials');
        Route::get('/homepage/testimonials/create', [AdminHomepageController::class, 'createTestimonial'])->name('homepage.testimonials.create');
        Route::post('/homepage/testimonials', [AdminHomepageController::class, 'storeTestimonial'])->name('homepage.testimonials.store');
        Route::get('/homepage/testimonials/{id}/edit', [AdminHomepageController::class, 'editTestimonial'])->name('homepage.testimonials.edit');
        Route::put('/homepage/testimonials/{id}', [AdminHomepageController::class, 'updateTestimonial'])->name('homepage.testimonials.update');
        Route::delete('/homepage/testimonials/{id}', [AdminHomepageController::class, 'destroyTestimonial'])->name('homepage.testimonials.destroy');
        
        // Blog Posts
        Route::get('/homepage/blog-posts', [AdminHomepageController::class, 'blogPosts'])->name('homepage.blog-posts');
        Route::get('/homepage/blog-posts/create', [AdminHomepageController::class, 'createBlogPost'])->name('homepage.blog-posts.create');
        Route::post('/homepage/blog-posts', [AdminHomepageController::class, 'storeBlogPost'])->name('homepage.blog-posts.store');
        Route::get('/homepage/blog-posts/{id}/edit', [AdminHomepageController::class, 'editBlogPost'])->name('homepage.blog-posts.edit');
        Route::put('/homepage/blog-posts/{id}', [AdminHomepageController::class, 'updateBlogPost'])->name('homepage.blog-posts.update');
        Route::delete('/homepage/blog-posts/{id}', [AdminHomepageController::class, 'destroyBlogPost'])->name('homepage.blog-posts.destroy');
        
        // FAQ
        Route::get('/homepage/faq', [AdminHomepageController::class, 'faq'])->name('homepage.faq');
        Route::get('/homepage/faq/create', [AdminHomepageController::class, 'createFaq'])->name('homepage.faq.create');
        Route::post('/homepage/faq', [AdminHomepageController::class, 'storeFaq'])->name('homepage.faq.store');
        Route::get('/homepage/faq/{id}/edit', [AdminHomepageController::class, 'editFaq'])->name('homepage.faq.edit');
        Route::put('/homepage/faq/{id}', [AdminHomepageController::class, 'updateFaq'])->name('homepage.faq.update');
        Route::delete('/homepage/faq/{id}', [AdminHomepageController::class, 'destroyFaq'])->name('homepage.faq.destroy');
        
        // Company Policies
        Route::get('/homepage/policies', [AdminHomepageController::class, 'policies'])->name('homepage.policies');
        Route::get('/homepage/policies/create', [AdminHomepageController::class, 'createPolicy'])->name('homepage.policies.create');
        Route::post('/homepage/policies', [AdminHomepageController::class, 'storePolicy'])->name('homepage.policies.store');
        Route::get('/homepage/policies/{id}/edit', [AdminHomepageController::class, 'editPolicy'])->name('homepage.policies.edit');
        Route::put('/homepage/policies/{id}', [AdminHomepageController::class, 'updatePolicy'])->name('homepage.policies.update');
        Route::delete('/homepage/policies/{id}', [AdminHomepageController::class, 'destroyPolicy'])->name('homepage.policies.destroy');
        
        // SEO Management
        Route::get('/homepage/seo', [AdminHomepageController::class, 'seo'])->name('homepage.seo');
        Route::get('/homepage/seo/create', [AdminHomepageController::class, 'createSeo'])->name('homepage.seo.create');
        Route::post('/homepage/seo', [AdminHomepageController::class, 'storeSeo'])->name('homepage.seo.store');
        Route::get('/homepage/seo/{id}/edit', [AdminHomepageController::class, 'editSeo'])->name('homepage.seo.edit');
        Route::put('/homepage/seo/{id}', [AdminHomepageController::class, 'storeSeo'])->name('homepage.seo.update');
        Route::delete('/homepage/seo/{id}', [AdminHomepageController::class, 'destroySeo'])->name('homepage.seo.destroy');
    });
    
    // User Management - System Administrator, ICT Officer
    Route::middleware(['role:System Administrator|ICT Officer'])->group(function () {
        // Specific routes must come BEFORE parameterized routes
        Route::get('/users/roles', [RoleController::class, 'index'])->name('users.roles');
        Route::get('/users/permissions', [PermissionController::class, 'index'])->name('users.permissions');
        Route::get('/users/export', [AdminUserManagementController::class, 'export'])->name('users.export');
        Route::post('/users/bulk-action', [AdminUserManagementController::class, 'bulkAction'])->name('users.bulk-action');
        Route::get('/users/create', [AdminUserManagementController::class, 'create'])->name('users.create');
        Route::get('/users/{id}/edit', [AdminUserManagementController::class, 'edit'])->name('users.edit');
        
        // General routes
        Route::get('/users', [AdminUserManagementController::class, 'index'])->name('users.index');
        Route::post('/users', [AdminUserManagementController::class, 'store'])->name('users.store');
        Route::get('/users/{id}', [AdminUserManagementController::class, 'show'])->name('users.show');
        Route::put('/users/{id}', [AdminUserManagementController::class, 'update'])->name('users.update');
        Route::delete('/users/{id}', [AdminUserManagementController::class, 'destroy'])->name('users.destroy');
    });
    
    // Customer Queries - System Administrator, Travel Consultant, Reservations Officer
    Route::middleware(['role:System Administrator|Travel Consultant|Reservations Officer'])->group(function () {
        Route::get('/queries', [AdminCustomerQueriesController::class, 'index'])->name('queries.index');
        Route::get('/queries/create', [AdminCustomerQueriesController::class, 'create'])->name('queries.create');
        Route::post('/queries', [AdminCustomerQueriesController::class, 'store'])->name('queries.store');
        Route::get('/queries/{id}', [AdminCustomerQueriesController::class, 'show'])->name('queries.show');
        Route::get('/queries/{id}/edit', [AdminCustomerQueriesController::class, 'edit'])->name('queries.edit');
        Route::put('/queries/{id}', [AdminCustomerQueriesController::class, 'update'])->name('queries.update');
        Route::delete('/queries/{id}', [AdminCustomerQueriesController::class, 'destroy'])->name('queries.destroy');
        Route::post('/queries/{id}/reply', [AdminCustomerQueriesController::class, 'reply'])->name('queries.reply');
        Route::post('/queries/bulk-update', [AdminCustomerQueriesController::class, 'bulkUpdate'])->name('queries.bulk-update');
    });
    
    // Support Tickets - System Administrator, Travel Consultant, Reservations Officer
    Route::middleware(['role:System Administrator|Travel Consultant|Reservations Officer'])->group(function () {
        Route::get('/tickets', [AdminSupportTicketsController::class, 'index'])->name('tickets.index');
        Route::get('/tickets/create', [AdminSupportTicketsController::class, 'create'])->name('tickets.create');
        Route::post('/tickets', [AdminSupportTicketsController::class, 'store'])->name('tickets.store');
        Route::get('/tickets/{id}', [AdminSupportTicketsController::class, 'show'])->name('tickets.show');
        Route::get('/tickets/{id}/edit', [AdminSupportTicketsController::class, 'edit'])->name('tickets.edit');
        Route::put('/tickets/{id}', [AdminSupportTicketsController::class, 'update'])->name('tickets.update');
        Route::delete('/tickets/{id}', [AdminSupportTicketsController::class, 'destroy'])->name('tickets.destroy');
        Route::post('/tickets/{id}/reply', [AdminSupportTicketsController::class, 'reply'])->name('tickets.reply');
        Route::post('/tickets/{id}/resolve', [AdminSupportTicketsController::class, 'resolve'])->name('tickets.resolve');
        Route::post('/tickets/bulk-update', [AdminSupportTicketsController::class, 'bulkUpdate'])->name('tickets.bulk-update');
    });
    
    // Notifications - System Administrator, Travel Consultant, Reservations Officer, Marketing Officer
    Route::middleware(['role:System Administrator|Travel Consultant|Reservations Officer|Marketing Officer'])->group(function () {
        Route::get('/notifications', [AdminNotificationController::class, 'index'])->name('notifications.index');
        Route::post('/notifications/send', [AdminNotificationController::class, 'send'])->name('notifications.send');
        Route::post('/notifications/bulk-send', [AdminNotificationController::class, 'bulkSend'])->name('notifications.bulk-send');
        Route::get('/notifications/stats', [AdminNotificationController::class, 'stats'])->name('notifications.stats');
    });
    
    // Email Management - System Administrator, Travel Consultant, Reservations Officer
    Route::middleware(['role:System Administrator|Travel Consultant|Reservations Officer'])->group(function () {
        Route::prefix('emails')->name('emails.')->group(function () {
            Route::get('/', [\App\Http\Controllers\Admin\EmailController::class, 'index'])->name('index');
            Route::get('/compose', [\App\Http\Controllers\Admin\EmailController::class, 'compose'])->name('compose');
            Route::post('/send', [\App\Http\Controllers\Admin\EmailController::class, 'send'])->name('send');
            Route::post('/bulk-action', [\App\Http\Controllers\Admin\EmailController::class, 'bulkAction'])->name('bulk-action');
            Route::get('/{email}', [\App\Http\Controllers\Admin\EmailController::class, 'show'])->name('show');
            Route::post('/{email}/reply', [\App\Http\Controllers\Admin\EmailController::class, 'reply'])->name('reply');
            Route::put('/{email}/status', [\App\Http\Controllers\Admin\EmailController::class, 'updateStatus'])->name('update-status');
            Route::put('/{email}/assign', [\App\Http\Controllers\Admin\EmailController::class, 'assign'])->name('assign');
            Route::post('/{email}/fetch', [\App\Http\Controllers\Admin\EmailController::class, 'fetch'])->name('fetch');
            Route::delete('/{email}', [\App\Http\Controllers\Admin\EmailController::class, 'destroy'])->name('destroy');
        });
    });
    
    // Email Settings - System Administrator, ICT Officer
    Route::middleware(['role:System Administrator|ICT Officer'])->group(function () {
        Route::prefix('settings/email-accounts')->name('settings.email-accounts.')->group(function () {
            Route::get('/', [\App\Http\Controllers\Admin\EmailSettingsController::class, 'index'])->name('index');
            Route::get('/create', [\App\Http\Controllers\Admin\EmailSettingsController::class, 'create'])->name('create');
            Route::post('/', [\App\Http\Controllers\Admin\EmailSettingsController::class, 'store'])->name('store');
            Route::get('/{emailAccount}/edit', [\App\Http\Controllers\Admin\EmailSettingsController::class, 'edit'])->name('edit');
            Route::put('/{emailAccount}', [\App\Http\Controllers\Admin\EmailSettingsController::class, 'update'])->name('update');
            Route::post('/{emailAccount}/test', [\App\Http\Controllers\Admin\EmailSettingsController::class, 'testConnection'])->name('test');
            Route::post('/{emailAccount}/send-test-email', [\App\Http\Controllers\Admin\EmailSettingsController::class, 'sendTestEmail'])->name('send-test-email');
            Route::delete('/{emailAccount}', [\App\Http\Controllers\Admin\EmailSettingsController::class, 'destroy'])->name('destroy');
        });
    });

});
