<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Lau Paradise Adventures - Premium Tanzania Tours & Safaris')</title>
    <meta name="description" content="@yield('description', 'Discover the beauty of Tanzania with Lau Paradise Adventures. Expert-guided safaris, Kilimanjaro climbs, Zanzibar beaches, and authentic cultural experiences.')">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@200;300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="{{ asset('css/fonts/google-fonts.css') }}" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{ asset('css/vendor/fontawesome-all.min.css') }}">
    
    <!-- AOS Animation Library -->
    <link href="{{ asset('css/vendor/aos.css') }}" rel="stylesheet">
    
    <!-- Main CSS -->
    <link rel="stylesheet" href="{{ asset('css/main.css') }}">

    @stack('styles')
</head>
<body>
    <!-- Loading Overlay -->
    <div class="loading-overlay" id="loadingOverlay">
        <div class="loader"></div>
    </div>
    
    <!-- Header -->
    @include('layouts.header')

    <!-- Main Content -->
    <main>
        @yield('content')
    </main>

    <!-- Footer -->
    @include('layouts.footer')

    <!-- Back to Top Button -->
    <button class="back-to-top" id="backToTop">
        <i class="fas fa-chevron-up"></i>
    </button>
    
    <!-- Quick Contact Widget - Always Visible Icons -->
    <div class="quick-contact-widget" id="quickContactWidget">
        <a href="https://wa.me/255789456123?text=Hi%20Lau%20Paradise%20Adventures,%20I%20would%20like%20to%20inquire%20about%20your%20tours" target="_blank" class="contact-icon-btn whatsapp" title="WhatsApp">
            <i class="fab fa-whatsapp"></i>
        </a>
        <a href="mailto:info@lauparadise.com" class="contact-icon-btn email" title="Email Us">
            <i class="fas fa-envelope"></i>
        </a>
        <a href="tel:+255789456123" class="contact-icon-btn call" title="Call Us">
            <i class="fas fa-phone"></i>
        </a>
       
    </div>
    
    <!-- JavaScript -->
    <script src="{{ asset('js/vendor/aos.js') }}"></script>
    <script src="{{ asset('js/main.js') }}"></script>
    
    @stack('scripts')
</body>
</html>
