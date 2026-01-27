{{-- Dynamic Sidebar Menu Based on User Role --}}
@php
    $user = auth()->user();
    $userRole = $user->roles->first() ? $user->roles->first()->name : 'Customer';
@endphp

{{-- 🟦 MAIN DASHBOARD --}}
<li class="menu-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
    <a href="{{ route('admin.dashboard') }}" class="menu-link">
        <i class="menu-icon tf-icons ri-home-smile-line"></i>
        <div data-i18n="Dashboard Overview">Dashboard Overview</div>
    </a>
</li>

{{-- 🟦 STATISTICS --}}
@if($user->hasAnyRole(['System Administrator', 'Finance Officer', 'Travel Consultant']))
<li class="menu-item {{ request()->routeIs('admin.statistics.*') ? 'active open' : '' }}">
    <a href="javascript:void(0);" class="menu-link menu-toggle">
        <i class="menu-icon tf-icons ri-bar-chart-box-line"></i>
        <div data-i18n="Statistics">Statistics</div>
    </a>
    <ul class="menu-sub">
        <li class="menu-item {{ request()->routeIs('admin.statistics.analytics') ? 'active' : '' }}">
            <a href="{{ route('admin.statistics.analytics') }}" class="menu-link">
                <div data-i18n="Analytics">Analytics</div>
            </a>
        </li>
        <li class="menu-item {{ request()->routeIs('admin.statistics.revenue-summary') ? 'active' : '' }}">
            <a href="{{ route('admin.statistics.revenue-summary') }}" class="menu-link">
                <div data-i18n="Revenue Summary">Revenue Summary</div>
            </a>
        </li>
        <li class="menu-item {{ request()->routeIs('admin.statistics.bookings-status') ? 'active' : '' }}">
            <a href="{{ route('admin.statistics.bookings-status') }}" class="menu-link">
                <div data-i18n="Bookings Status">Bookings Status</div>
            </a>
        </li>
        <li class="menu-item {{ request()->routeIs('admin.statistics.upcoming-trips') ? 'active' : '' }}">
            <a href="{{ route('admin.statistics.upcoming-trips') }}" class="menu-link">
                <div data-i18n="Upcoming Trips">Upcoming Trips</div>
            </a>
        </li>
    </ul>
</li>
@endif

{{-- 🟧 BOOKINGS MANAGEMENT --}}
@if($user->hasAnyRole(['System Administrator', 'Travel Consultant', 'Reservations Officer']))
<li class="menu-item {{ request()->routeIs('admin.bookings.*') ? 'active open' : '' }}">
    <a href="javascript:void(0);" class="menu-link menu-toggle">
        <i class="menu-icon tf-icons ri-calendar-check-line"></i>
        <div data-i18n="Bookings">Bookings</div>
    </a>
    <ul class="menu-sub">
        <li class="menu-item {{ request()->routeIs('admin.bookings.index') ? 'active' : '' }}">
            <a href="{{ route('admin.bookings.index') }}" class="menu-link">
                <div data-i18n="All Bookings">All Bookings</div>
            </a>
        </li>
        <li class="menu-item {{ request()->routeIs('admin.bookings.create') ? 'active' : '' }}">
            <a href="{{ route('admin.bookings.create') }}" class="menu-link">
                <div data-i18n="Create New Booking">Create New Booking</div>
            </a>
        </li>
        <li class="menu-item {{ request()->routeIs('admin.bookings.pending') ? 'active' : '' }}">
            <a href="{{ route('admin.bookings.pending') }}" class="menu-link">
                <div data-i18n="Pending Approvals">Pending Approvals</div>
            </a>
        </li>
        <li class="menu-item {{ request()->routeIs('admin.bookings.confirmed') ? 'active' : '' }}">
            <a href="{{ route('admin.bookings.confirmed') }}" class="menu-link">
                <div data-i18n="Confirmed Bookings">Confirmed Bookings</div>
            </a>
        </li>
        <li class="menu-item {{ request()->routeIs('admin.bookings.cancelled') ? 'active' : '' }}">
            <a href="{{ route('admin.bookings.cancelled') }}" class="menu-link">
                <div data-i18n="Cancelled Bookings">Cancelled Bookings</div>
            </a>
        </li>
        <li class="menu-item {{ request()->routeIs('admin.bookings.calendar') ? 'active' : '' }}">
            <a href="{{ route('admin.bookings.calendar') }}" class="menu-link">
                <div data-i18n="Booking Calendar">Booking Calendar</div>
            </a>
        </li>
    </ul>
</li>
@endif

{{-- QUOTATIONS --}}
@if($user->hasAnyRole(['System Administrator', 'Travel Consultant', 'Reservations Officer']))
<li class="menu-item {{ request()->routeIs('admin.quotations.*') ? 'active open' : '' }}">
    <a href="javascript:void(0);" class="menu-link menu-toggle">
        <i class="menu-icon tf-icons ri-file-text-line"></i>
        <div data-i18n="Quotations">Quotations</div>
    </a>
    <ul class="menu-sub">
        <li class="menu-item {{ request()->routeIs('admin.quotations.index') ? 'active' : '' }}">
            <a href="{{ route('admin.quotations.index') }}" class="menu-link">
                <div data-i18n="All Quotations">All Quotations</div>
            </a>
        </li>
        <li class="menu-item {{ request()->routeIs('admin.quotations.create') ? 'active' : '' }}">
            <a href="{{ route('admin.quotations.create') }}" class="menu-link">
                <div data-i18n="Create Quotation">Create Quotation</div>
            </a>
        </li>
        <li class="menu-item {{ request()->routeIs('admin.quotations.pending') ? 'active' : '' }}">
            <a href="{{ route('admin.quotations.pending') }}" class="menu-link">
                <div data-i18n="Pending Quotations">Pending Quotations</div>
            </a>
        </li>
        <li class="menu-item {{ request()->routeIs('admin.quotations.sent') ? 'active' : '' }}">
            <a href="{{ route('admin.quotations.sent') }}" class="menu-link">
                <div data-i18n="Sent Quotations">Sent Quotations</div>
            </a>
        </li>
        <li class="menu-item {{ request()->routeIs('admin.quotations.accepted') ? 'active' : '' }}">
            <a href="{{ route('admin.quotations.accepted') }}" class="menu-link">
                <div data-i18n="Accepted Quotations">Accepted Quotations</div>
            </a>
        </li>
    </ul>
</li>
@endif

{{-- 🟩 TOURS & PACKAGES --}}
@if($user->hasAnyRole(['System Administrator', 'Content Manager', 'Travel Consultant']))
<li class="menu-item {{ request()->routeIs('admin.tours.*') || request()->routeIs('admin.destinations.*') || request()->routeIs('admin.categories.*') ? 'active open' : '' }}">
    <a href="javascript:void(0);" class="menu-link menu-toggle">
        <i class="menu-icon tf-icons ri-map-2-line"></i>
        <div data-i18n="Tours & Packages">Tours & Packages</div>
    </a>
    <ul class="menu-sub">
        <li class="menu-item {{ request()->routeIs('admin.tours.index') ? 'active' : '' }}">
            <a href="{{ route('admin.tours.index') }}" class="menu-link">
                <div data-i18n="All Tours">All Tours</div>
            </a>
        </li>
        <li class="menu-item {{ request()->routeIs('admin.tours.create') ? 'active' : '' }}">
            <a href="{{ route('admin.tours.create') }}" class="menu-link">
                <div data-i18n="Add New Tour">Add New Tour</div>
            </a>
        </li>
        <li class="menu-item {{ request()->routeIs('admin.tours.itinerary-builder') ? 'active' : '' }}">
            <a href="{{ route('admin.tours.itinerary-builder') }}" class="menu-link">
                <div data-i18n="Itinerary Builder">Itinerary Builder</div>
            </a>
        </li>
        <li class="menu-item {{ request()->routeIs('admin.categories.*') ? 'active' : '' }}">
            <a href="{{ route('admin.categories.index') }}" class="menu-link">
                <div data-i18n="Tour Categories">Tour Categories</div>
            </a>
        </li>
        <li class="menu-item {{ request()->routeIs('admin.tours.availability') ? 'active' : '' }}">
            <a href="{{ route('admin.tours.availability') }}" class="menu-link">
                <div data-i18n="Tour Availability">Tour Availability</div>
            </a>
        </li>
        <li class="menu-item {{ request()->routeIs('admin.tours.pricing') ? 'active' : '' }}">
            <a href="{{ route('admin.tours.pricing') }}" class="menu-link">
                <div data-i18n="Tour Pricing">Tour Pricing</div>
            </a>
        </li>
        <li class="menu-item {{ request()->routeIs('admin.destinations.*') ? 'active' : '' }}">
            <a href="{{ route('admin.destinations.index') }}" class="menu-link">
                <div data-i18n="Destinations">Destinations</div>
            </a>
        </li>
    </ul>
</li>
@endif

{{-- 🟪 HOTELS & ACCOMMODATIONS --}}
@if($user->hasAnyRole(['System Administrator', 'Hotel Partner', 'Travel Consultant']))
<li class="menu-item {{ request()->routeIs('admin.hotels.*') ? 'active open' : '' }}">
    <a href="javascript:void(0);" class="menu-link menu-toggle">
        <i class="menu-icon tf-icons ri-hotel-line"></i>
        <div data-i18n="Hotels">Hotels</div>
    </a>
    <ul class="menu-sub">
        <li class="menu-item {{ request()->routeIs('admin.hotels.index') ? 'active' : '' }}">
            <a href="{{ route('admin.hotels.index') }}" class="menu-link">
                <div data-i18n="All Hotels">All Hotels</div>
            </a>
        </li>
        <li class="menu-item {{ request()->routeIs('admin.hotels.create') ? 'active' : '' }}">
            <a href="{{ route('admin.hotels.create') }}" class="menu-link">
                <div data-i18n="Add Hotel">Add Hotel</div>
            </a>
        </li>
        <li class="menu-item {{ request()->routeIs('admin.hotels.room-types') ? 'active' : '' }}">
            <a href="{{ route('admin.hotels.room-types') }}" class="menu-link">
                <div data-i18n="Room Types">Room Types</div>
            </a>
        </li>
        <li class="menu-item {{ request()->routeIs('admin.hotels.room-pricing') ? 'active' : '' }}">
            <a href="{{ route('admin.hotels.room-pricing') }}" class="menu-link">
                <div data-i18n="Room Pricing">Room Pricing</div>
            </a>
        </li>
        <li class="menu-item {{ request()->routeIs('admin.hotels.availability') ? 'active' : '' }}">
            <a href="{{ route('admin.hotels.availability') }}" class="menu-link">
                <div data-i18n="Hotel Availability">Hotel Availability</div>
            </a>
        </li>
        <li class="menu-item {{ request()->routeIs('admin.hotels.partner-portal') ? 'active' : '' }}">
            <a href="{{ route('admin.hotels.partner-portal') }}" class="menu-link">
                <div data-i18n="Partner Hotels Portal">Partner Hotels Portal</div>
            </a>
        </li>
    </ul>
</li>
@endif

{{-- 🟫 TRANSPORT & FLEET --}}
@if($user->hasAnyRole(['System Administrator', 'Driver/Guide', 'Travel Consultant', 'Reservations Officer']))
<li class="menu-item {{ request()->routeIs('admin.vehicles.*') ? 'active open' : '' }}">
    <a href="javascript:void(0);" class="menu-link menu-toggle">
        <i class="menu-icon tf-icons ri-car-line"></i>
        <div data-i18n="Transport & Fleet">Transport & Fleet</div>
    </a>
    <ul class="menu-sub">
        <li class="menu-item {{ request()->routeIs('admin.vehicles.index') ? 'active' : '' }}">
            <a href="{{ route('admin.vehicles.index') }}" class="menu-link">
                <div data-i18n="Vehicles">Vehicles</div>
            </a>
        </li>
        <li class="menu-item {{ request()->routeIs('admin.vehicles.create') ? 'active' : '' }}">
            <a href="{{ route('admin.vehicles.create') }}" class="menu-link">
                <div data-i18n="Add Vehicle">Add Vehicle</div>
            </a>
        </li>
        <li class="menu-item {{ request()->routeIs('admin.vehicles.drivers') ? 'active' : '' }}">
            <a href="{{ route('admin.vehicles.drivers') }}" class="menu-link">
                <div data-i18n="Drivers / Guides">Drivers / Guides</div>
            </a>
        </li>
        <li class="menu-item {{ request()->routeIs('admin.vehicles.assign-driver') ? 'active' : '' }}">
            <a href="{{ route('admin.vehicles.assign-driver') }}" class="menu-link">
                <div data-i18n="Assign Driver to Trip">Assign Driver to Trip</div>
            </a>
        </li>
        <li class="menu-item {{ request()->routeIs('admin.vehicles.availability') ? 'active' : '' }}">
            <a href="{{ route('admin.vehicles.availability') }}" class="menu-link">
                <div data-i18n="Fleet Availability">Fleet Availability</div>
            </a>
        </li>
        <li class="menu-item {{ request()->routeIs('admin.vehicles.bookings') ? 'active' : '' }}">
            <a href="{{ route('admin.vehicles.bookings') }}" class="menu-link">
                <div data-i18n="Transport Bookings">Transport Bookings</div>
            </a>
        </li>
    </ul>
</li>
@endif

{{-- 🟨 CUSTOMERS --}}
@if($user->hasAnyRole(['System Administrator', 'Travel Consultant', 'Reservations Officer']))
<li class="menu-item {{ request()->routeIs('admin.customers.*') ? 'active open' : '' }}">
    <a href="javascript:void(0);" class="menu-link menu-toggle">
        <i class="menu-icon tf-icons ri-user-line"></i>
        <div data-i18n="Customers">Customers</div>
    </a>
    <ul class="menu-sub">
        <li class="menu-item {{ request()->routeIs('admin.customers.index') ? 'active' : '' }}">
            <a href="{{ route('admin.customers.index') }}" class="menu-link">
                <div data-i18n="All Customers">All Customers</div>
            </a>
        </li>
        <li class="menu-item {{ request()->routeIs('admin.customers.create') ? 'active' : '' }}">
            <a href="{{ route('admin.customers.create') }}" class="menu-link">
                <div data-i18n="Add Customer">Add Customer</div>
            </a>
        </li>
        <li class="menu-item {{ request()->routeIs('admin.customers.groups') ? 'active' : '' }}">
            <a href="{{ route('admin.customers.groups') }}" class="menu-link">
                <div data-i18n="Customer Groups">Customer Groups</div>
            </a>
        </li>
        <li class="menu-item {{ request()->routeIs('admin.customers.feedback') ? 'active' : '' }}">
            <a href="{{ route('admin.customers.feedback') }}" class="menu-link">
                <div data-i18n="Customer Feedback">Customer Feedback</div>
            </a>
        </li>
        <li class="menu-item {{ request()->routeIs('admin.customers.messages') ? 'active' : '' }}">
            <a href="{{ route('admin.customers.messages') }}" class="menu-link">
                <div data-i18n="Customer Messages">Customer Messages</div>
            </a>
        </li>
    </ul>
</li>
@endif

{{-- 🟧 FINANCE & ACCOUNTING --}}
@if($user->hasAnyRole(['System Administrator', 'Finance Officer']))
<li class="menu-item {{ request()->routeIs('admin.finance.*') || request()->routeIs('admin.payments.*') || request()->routeIs('admin.invoices.*') || request()->routeIs('admin.expenses.*') ? 'active open' : '' }}">
    <a href="javascript:void(0);" class="menu-link menu-toggle">
        <i class="menu-icon tf-icons ri-money-dollar-circle-line"></i>
        <div data-i18n="Finance">Finance</div>
    </a>
    <ul class="menu-sub">
        <li class="menu-item {{ request()->routeIs('admin.finance.payments') || request()->routeIs('admin.payments.*') ? 'active' : '' }}">
            <a href="{{ route('admin.finance.payments') }}" class="menu-link">
                <div data-i18n="Payments">Payments</div>
            </a>
        </li>
        <li class="menu-item {{ request()->routeIs('admin.finance.invoices') || request()->routeIs('admin.invoices.*') ? 'active' : '' }}">
            <a href="{{ route('admin.finance.invoices') }}" class="menu-link">
                <div data-i18n="Invoices">Invoices</div>
            </a>
        </li>
        <li class="menu-item {{ request()->routeIs('admin.finance.receipt') ? 'active' : '' }}">
            <a href="{{ route('admin.finance.invoices') }}" class="menu-link">
                <div data-i18n="Generate Receipt">Generate Receipt</div>
            </a>
        </li>
        <li class="menu-item {{ request()->routeIs('admin.finance.refunds') ? 'active' : '' }}">
            <a href="{{ route('admin.finance.refunds') }}" class="menu-link">
                <div data-i18n="Refund Requests">Refund Requests</div>
            </a>
        </li>
        <li class="menu-item {{ request()->routeIs('admin.finance.expenses') || request()->routeIs('admin.expenses.*') ? 'active' : '' }}">
            <a href="{{ route('admin.finance.expenses') }}" class="menu-link">
                <div data-i18n="Expenses">Expenses</div>
            </a>
        </li>
        <li class="menu-item {{ request()->routeIs('admin.finance.revenue-reports') ? 'active' : '' }}">
            <a href="{{ route('admin.finance.revenue-reports') }}" class="menu-link">
                <div data-i18n="Revenue Reports">Revenue Reports</div>
            </a>
        </li>
        <li class="menu-item {{ request()->routeIs('admin.finance.statements') ? 'active' : '' }}">
            <a href="{{ route('admin.finance.statements') }}" class="menu-link">
                <div data-i18n="Financial Statements">Financial Statements</div>
            </a>
        </li>
    </ul>
</li>
@endif

{{-- 🟧 MARKETING --}}
@if($user->hasAnyRole(['System Administrator', 'Marketing Officer', 'Marketing Manager', 'Content Manager']))
<li class="menu-item {{ request()->routeIs('admin.marketing.*') ? 'active open' : '' }}">
    <a href="javascript:void(0);" class="menu-link menu-toggle">
        <i class="menu-icon tf-icons ri-megaphone-line"></i>
        <div data-i18n="Marketing">Marketing</div>
    </a>
    <ul class="menu-sub">
        <li class="menu-item {{ request()->routeIs('admin.marketing.dashboard') ? 'active' : '' }}">
            <a href="{{ route('admin.marketing.dashboard') }}" class="menu-link">
                <div data-i18n="Marketing Dashboard">Marketing Dashboard</div>
            </a>
        </li>
        <li class="menu-item {{ request()->routeIs('admin.marketing.promo-codes*') ? 'active' : '' }}">
            <a href="{{ route('admin.marketing.promo-codes') }}" class="menu-link">
                <div data-i18n="Promo Codes / Discounts">Promo Codes / Discounts</div>
            </a>
        </li>
        <li class="menu-item {{ request()->routeIs('admin.marketing.email-campaigns*') ? 'active' : '' }}">
            <a href="{{ route('admin.marketing.email-campaigns') }}" class="menu-link">
                <div data-i18n="Email Campaigns">Email Campaigns</div>
            </a>
        </li>
        <li class="menu-item {{ request()->routeIs('admin.marketing.sms-campaigns*') ? 'active' : '' }}">
            <a href="{{ route('admin.marketing.sms-campaigns') }}" class="menu-link">
                <div data-i18n="SMS Campaigns">SMS Campaigns</div>
            </a>
        </li>
        <li class="menu-item {{ request()->routeIs('admin.marketing.social-media*') ? 'active' : '' }}">
            <a href="{{ route('admin.marketing.social-media') }}" class="menu-link">
                <div data-i18n="Social Media Scheduler">Social Media Scheduler</div>
            </a>
        </li>
        <li class="menu-item {{ request()->routeIs('admin.marketing.landing-pages*') ? 'active' : '' }}">
            <a href="{{ route('admin.marketing.landing-pages') }}" class="menu-link">
                <div data-i18n="Landing Pages">Landing Pages</div>
            </a>
        </li>
        <li class="menu-item {{ request()->routeIs('admin.marketing.analytics') ? 'active' : '' }}">
            <a href="{{ route('admin.marketing.analytics') }}" class="menu-link">
                <div data-i18n="Marketing Analytics">Marketing Analytics</div>
            </a>
        </li>
        <li class="menu-item {{ request()->routeIs('admin.marketing.banners*') ? 'active' : '' }}">
            <a href="{{ route('admin.marketing.banners') }}" class="menu-link">
                <div data-i18n="Banners & Popups">Banners & Popups</div>
            </a>
        </li>
    </ul>
</li>
@endif

{{-- 🟪 HOMEPAGE SECTIONS --}}
@if($user->hasAnyRole(['System Administrator', 'Content Manager']))
<li class="menu-item {{ request()->routeIs('admin.homepage.*') ? 'active open' : '' }}">
    <a href="javascript:void(0);" class="menu-link menu-toggle">
        <i class="menu-icon tf-icons ri-home-line"></i>
        <div data-i18n="Homepage Sections">Homepage Sections</div>
    </a>
    <ul class="menu-sub">
        <li class="menu-item {{ request()->routeIs('admin.homepage.destinations') ? 'active' : '' }}">
            <a href="{{ route('admin.homepage.destinations') }}" class="menu-link">
                <div data-i18n="Destinations">Destinations</div>
            </a>
        </li>
        <li class="menu-item {{ request()->routeIs('admin.homepage.hero-slider*') ? 'active' : '' }}">
            <a href="{{ route('admin.homepage.hero-slider') }}" class="menu-link">
                <div data-i18n="Hero Slider">Hero Slider</div>
            </a>
        </li>
        <li class="menu-item {{ request()->routeIs('admin.homepage.gallery') ? 'active' : '' }}">
            <a href="{{ route('admin.homepage.gallery') }}" class="menu-link">
                <div data-i18n="Gallery">Gallery</div>
            </a>
        </li>
        <li class="menu-item {{ request()->routeIs('admin.homepage.testimonials') ? 'active' : '' }}">
            <a href="{{ route('admin.homepage.testimonials') }}" class="menu-link">
                <div data-i18n="Testimonials">Testimonials</div>
            </a>
        </li>
        <li class="menu-item {{ request()->routeIs('admin.homepage.blog-posts') ? 'active' : '' }}">
            <a href="{{ route('admin.homepage.blog-posts') }}" class="menu-link">
                <div data-i18n="Blog Posts">Blog Posts</div>
            </a>
        </li>
        <li class="menu-item {{ request()->routeIs('admin.homepage.faq') ? 'active' : '' }}">
            <a href="{{ route('admin.homepage.faq') }}" class="menu-link">
                <div data-i18n="FAQ">FAQ</div>
            </a>
        </li>
        <li class="menu-item {{ request()->routeIs('admin.homepage.policies') ? 'active' : '' }}">
            <a href="{{ route('admin.homepage.policies') }}" class="menu-link">
                <div data-i18n="Company Policies">Company Policies</div>
            </a>
        </li>
        <li class="menu-item {{ request()->routeIs('admin.homepage.seo') ? 'active' : '' }}">
            <a href="{{ route('admin.homepage.seo') }}" class="menu-link">
                <div data-i18n="SEO Management">SEO Management</div>
            </a>
        </li>
    </ul>
</li>
@endif

{{-- 🟪 ABOUT PAGE MANAGEMENT --}}
@if($user->hasAnyRole(['System Administrator', 'Content Manager']))
<li class="menu-item {{ request()->routeIs('admin.about-page.*') ? 'active open' : '' }}">
    <a href="javascript:void(0);" class="menu-link menu-toggle">
        <i class="menu-icon tf-icons ri-information-line"></i>
        <div data-i18n="About Page">About Page</div>
    </a>
    <ul class="menu-sub">
        <li class="menu-item {{ request()->routeIs('admin.about-page.index') ? 'active' : '' }}">
            <a href="{{ route('admin.about-page.index') }}" class="menu-link">
                <div data-i18n="Manage About Page">Manage About Page</div>
            </a>
        </li>
    </ul>
</li>
@endif

{{-- 🟪 EMAIL MANAGEMENT --}}
@if($user->hasAnyRole(['System Administrator', 'Travel Consultant', 'Reservations Officer']))
<li class="menu-item {{ request()->routeIs('admin.emails.*') ? 'active open' : '' }}">
    <a href="javascript:void(0);" class="menu-link menu-toggle">
        <i class="menu-icon tf-icons ri-mail-open-line"></i>
        <div data-i18n="Email Management">Email Management</div>
    </a>
    <ul class="menu-sub">
        <li class="menu-item {{ request()->routeIs('admin.emails.index') ? 'active' : '' }}">
            <a href="{{ route('admin.emails.index') }}" class="menu-link">
                <div data-i18n="Inbox">Inbox</div>
            </a>
        </li>
        <li class="menu-item {{ request()->routeIs('admin.emails.compose') ? 'active' : '' }}">
            <a href="{{ route('admin.emails.compose') }}" class="menu-link">
                <div data-i18n="Compose">Compose</div>
            </a>
        </li>
    </ul>
</li>
@endif

{{-- 🟪 MESSAGES & SUPPORT --}}
@if($user->hasAnyRole(['System Administrator', 'Travel Consultant', 'Reservations Officer']))
<li class="menu-item {{ request()->routeIs('admin.queries.*') || request()->routeIs('admin.tickets.*') || request()->routeIs('admin.notifications.*') ? 'active open' : '' }}">
    <a href="javascript:void(0);" class="menu-link menu-toggle">
        <i class="menu-icon tf-icons ri-message-3-line"></i>
        <div data-i18n="Messages & Support">Messages & Support</div>
    </a>
    <ul class="menu-sub">
        <li class="menu-item {{ request()->routeIs('admin.queries.*') ? 'active' : '' }}">
            <a href="{{ route('admin.queries.index') }}" class="menu-link">
                <div data-i18n="Customer Queries">Customer Queries</div>
            </a>
        </li>
        <li class="menu-item {{ request()->routeIs('admin.tickets.*') ? 'active' : '' }}">
            <a href="{{ route('admin.tickets.index') }}" class="menu-link">
                <div data-i18n="Support Tickets">Support Tickets</div>
            </a>
        </li>
        <li class="menu-item {{ request()->routeIs('admin.notifications.*') ? 'active' : '' }}">
            <a href="{{ route('admin.notifications.index') }}" class="menu-link">
                <div data-i18n="Notifications">Notifications</div>
            </a>
        </li>
    </ul>
</li>
@endif

{{-- 🟫 REPORTS --}}
@if($user->hasAnyRole(['System Administrator', 'Finance Officer', 'Travel Consultant']))
<li class="menu-item {{ request()->routeIs('admin.reports.*') ? 'active open' : '' }}">
    <a href="javascript:void(0);" class="menu-link menu-toggle">
        <i class="menu-icon tf-icons ri-file-chart-line"></i>
        <div data-i18n="Reports">Reports</div>
    </a>
    <ul class="menu-sub">
        <li class="menu-item {{ request()->routeIs('admin.reports.bookings') ? 'active' : '' }}">
            <a href="{{ route('admin.reports.bookings') }}" class="menu-link">
                <div data-i18n="Bookings Report">Bookings Report</div>
            </a>
        </li>
        <li class="menu-item {{ request()->routeIs('admin.reports.revenue') ? 'active' : '' }}">
            <a href="{{ route('admin.reports.revenue') }}" class="menu-link">
                <div data-i18n="Finance Reports">Finance Reports</div>
            </a>
        </li>
    </ul>
</li>
@endif

{{-- 🟩 USERS & ROLES MANAGEMENT --}}
@if($user->hasAnyRole(['System Administrator', 'ICT Officer']))
<li class="menu-item {{ request()->routeIs('admin.users.*') || request()->routeIs('admin.roles.*') || request()->routeIs('admin.permissions.*') ? 'active open' : '' }}">
    <a href="javascript:void(0);" class="menu-link menu-toggle">
        <i class="menu-icon tf-icons ri-user-settings-line"></i>
        <div data-i18n="Users & Roles">Users & Roles</div>
    </a>
    <ul class="menu-sub">
        <li class="menu-item {{ request()->routeIs('admin.users.index') ? 'active' : '' }}">
            <a href="{{ route('admin.users.index') }}" class="menu-link">
                <div data-i18n="All Users">All Users</div>
            </a>
        </li>
        <li class="menu-item {{ request()->routeIs('admin.users.create') ? 'active' : '' }}">
            <a href="{{ route('admin.users.create') }}" class="menu-link">
                <div data-i18n="Add User">Add User</div>
            </a>
        </li>
        <li class="menu-item {{ request()->routeIs('admin.users.roles') || request()->routeIs('admin.roles.*') ? 'active' : '' }}">
            <a href="{{ route('admin.users.roles') }}" class="menu-link">
                <div data-i18n="Roles">Roles</div>
            </a>
        </li>
        <li class="menu-item {{ request()->routeIs('admin.users.permissions') || request()->routeIs('admin.permissions.*') ? 'active' : '' }}">
            <a href="{{ route('admin.users.permissions') }}" class="menu-link">
                <div data-i18n="Permissions">Permissions</div>
            </a>
        </li>
    </ul>
</li>
@endif

{{-- 🟦 SYSTEM SETTINGS (AT THE END) --}}
@if($user->hasAnyRole(['System Administrator', 'ICT Officer']))
<li class="menu-item {{ request()->routeIs('admin.settings.*') ? 'active open' : '' }}">
    <a href="javascript:void(0);" class="menu-link menu-toggle">
        <i class="menu-icon tf-icons ri-settings-3-line"></i>
        <div data-i18n="System Settings">System Settings</div>
    </a>
    <ul class="menu-sub">
        <li class="menu-item {{ request()->routeIs('admin.settings.system') ? 'active' : '' }}">
            <a href="{{ route('admin.settings.system') }}" class="menu-link">
                <div data-i18n="System Settings">System Settings</div>
            </a>
        </li>
        <li class="menu-item {{ request()->routeIs('admin.settings.organization*') ? 'active' : '' }}">
            <a href="{{ route('admin.settings.organization') }}" class="menu-link">
                <div data-i18n="Organization">Organization</div>
            </a>
        </li>
        <li class="menu-item {{ request()->routeIs('admin.settings.website*') ? 'active' : '' }}">
            <a href="{{ route('admin.settings.website') }}" class="menu-link">
                <div data-i18n="Website Settings">Website Settings</div>
            </a>
        </li>
        <li class="menu-item {{ request()->routeIs('admin.settings.security*') ? 'active' : '' }}">
            <a href="{{ route('admin.settings.security') }}" class="menu-link">
                <div data-i18n="Security">Security</div>
            </a>
        </li>
        <li class="menu-item {{ request()->routeIs('admin.settings.api-integrations') ? 'active' : '' }}">
            <a href="{{ route('admin.settings.api-integrations') }}" class="menu-link">
                <div data-i18n="API Integrations">API Integrations</div>
            </a>
        </li>
        <li class="menu-item {{ request()->routeIs('admin.settings.mpesa*') ? 'active' : '' }}">
            <a href="{{ route('admin.settings.mpesa') }}" class="menu-link">
                <div data-i18n="MPESA Daraja">MPESA Daraja</div>
            </a>
        </li>
        <li class="menu-item {{ request()->routeIs('admin.settings.sms-gateway*') ? 'active' : '' }}">
            <a href="{{ route('admin.settings.sms-gateway') }}" class="menu-link">
                <div data-i18n="SMS Gateway">SMS Gateway</div>
            </a>
        </li>
        <li class="menu-item {{ request()->routeIs('admin.settings.email-smtp*') ? 'active' : '' }}">
            <a href="{{ route('admin.settings.email-smtp') }}" class="menu-link">
                <div data-i18n="Email SMTP">Email SMTP</div>
            </a>
        </li>
        <li class="menu-item {{ request()->routeIs('admin.settings.email-accounts.*') ? 'active' : '' }}">
            <a href="{{ route('admin.settings.email-accounts.index') }}" class="menu-link">
                <div data-i18n="Email Accounts">Email Accounts</div>
            </a>
        </li>
        <li class="menu-item {{ request()->routeIs('admin.settings.payment-gateways*') ? 'active' : '' }}">
            <a href="{{ route('admin.settings.payment-gateways') }}" class="menu-link">
                <div data-i18n="Payment Gateways">Payment Gateways</div>
            </a>
        </li>
        <li class="menu-item {{ request()->routeIs('admin.settings.backups*') ? 'active' : '' }}">
            <a href="{{ route('admin.settings.backups') }}" class="menu-link">
                <div data-i18n="Backup Manager">Backup Manager</div>
            </a>
        </li>
        <li class="menu-item {{ request()->routeIs('admin.settings.system-health') ? 'active' : '' }}">
            <a href="{{ route('admin.settings.system-health') }}" class="menu-link">
                <div data-i18n="System Health">System Health</div>
            </a>
        </li>
        <li class="menu-item {{ request()->routeIs('admin.settings.system-logs*') ? 'active' : '' }}">
            <a href="{{ route('admin.settings.system-logs') }}" class="menu-link">
                <div data-i18n="System Logs">System Logs</div>
            </a>
        </li>
        <li class="menu-item {{ request()->routeIs('admin.settings.audit-trails*') ? 'active' : '' }}">
            <a href="{{ route('admin.settings.audit-trails') }}" class="menu-link">
                <div data-i18n="Audit Trails">Audit Trails</div>
            </a>
        </li>
        <li class="menu-item {{ request()->routeIs('admin.settings.activity-logs*') ? 'active' : '' }}">
            <a href="{{ route('admin.settings.activity-logs') }}" class="menu-link">
                <div data-i18n="Activity Logs">Activity Logs</div>
            </a>
        </li>
    </ul>
</li>
@endif

{{-- 🟦 PROFILE --}}
<li class="menu-item {{ request()->routeIs('admin.profile') ? 'active' : '' }}">
    <a href="{{ route('admin.profile') }}" class="menu-link">
        <i class="menu-icon tf-icons ri-user-3-line"></i>
        <div data-i18n="My Profile">My Profile</div>
    </a>
</li>
