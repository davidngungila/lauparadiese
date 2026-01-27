@extends('layouts.app')

@section('title', 'Lau Paradise Adventures - Premium Tanzania Tours & Safaris')
@section('description', 'Discover the beauty of Tanzania with Lau Paradise Adventures. Expert-guided safaris, Kilimanjaro climbs, Zanzibar beaches, and authentic cultural experiences.')

@section('content')

<br><br>
<!-- Hero Section with Slider -->
<section class="hero-section" id="home">
        <div class="hero-slider" id="heroSlider">
            <div class="slider-wrapper">
            @if(isset($heroSlides) && $heroSlides->count() > 0)
                @foreach($heroSlides as $index => $slide)
                @php
                    $slideNumber = $index + 1;
                    $isActive = $index === 0 ? 'active' : '';
                    $animationClass = 'content-animation-' . $slideNumber;
                    $badgeAnimationClass = 'badge-animation-' . $slideNumber;
                    $titleAnimationClass = 'title-animation-' . $slideNumber;
                    $subtitleAnimationClass = 'subtitle-animation-' . $slideNumber;
                    $actionsAnimationClass = 'actions-animation-' . $slideNumber;
                    $overlayClass = 'overlay-type-' . $slideNumber;
                    $slideTypeClass = 'slide-type-' . $slideNumber;
                @endphp
                <div class="slider-slide {{ $isActive }} {{ $slideTypeClass }}" style="background-image: url('{{ $slide['image_url'] }}');">
                    <div class="hero-overlay {{ $overlayClass }}"></div>
                    <div class="hero-content {{ $animationClass }}">
                        @if($slide['badge_text'])
                        <span class="hero-badge {{ $badgeAnimationClass }}">
                            @if($slide['badge_icon'])
                            <i class="{{ $slide['badge_icon'] }}"></i>
                            @endif
                            {{ $slide['badge_text'] }}
                        </span>
                        @endif
                        <h1 class="hero-title {{ $titleAnimationClass }}">{{ $slide['title'] }}</h1>
                        @if($slide['subtitle'])
                        <p class="hero-subtitle {{ $subtitleAnimationClass }}">{{ $slide['subtitle'] }}</p>
                        @endif
                        <div class="hero-actions {{ $actionsAnimationClass }}">
                            @if($slide['primary_button_text'] && $slide['primary_button_link'])
                            <a href="{{ $slide['primary_button_link'] }}" class="btn-hero-primary">
                                @if($slide['primary_button_icon'])
                                <i class="{{ $slide['primary_button_icon'] }}"></i>
                                @endif
                                {{ $slide['primary_button_text'] }}
                            </a>
                            @endif
                            @if($slide['secondary_button_text'] && $slide['secondary_button_link'])
                            <a href="{{ $slide['secondary_button_link'] }}" class="btn-hero-secondary">
                                @if($slide['secondary_button_icon'])
                                <i class="{{ $slide['secondary_button_icon'] }}"></i>
                                @endif
                                {{ $slide['secondary_button_text'] }}
                            </a>
                            @endif
                        </div>
                    </div>
                </div>
                @endforeach
            @else
                <!-- Fallback slide if no slides in database -->
                <div class="slider-slide active slide-type-1" style="background-image: url('{{ asset('images/safari_home-1.jpg') }}');">
                    <div class="hero-overlay overlay-type-1"></div>
                    <div class="hero-content content-animation-1">
                        <span class="hero-badge badge-animation-1"><i class="fas fa-star"></i> Best Seller</span>
                        <h1 class="hero-title title-animation-1">Tanzania Wildlife Safaris</h1>
                        <p class="hero-subtitle subtitle-animation-1">Witness the Big Five in Serengeti, Ngorongoro, and Tarangire. Experience authentic Tanzania safaris with expert local guides.</p>
                        <div class="hero-actions actions-animation-1">
                            <a href="{{ route('tours.index') }}" class="btn-hero-primary">
                                <i class="fas fa-compass"></i> Explore Safaris
                            </a>
                            <a href="{{ route('booking') }}" class="btn-hero-secondary">
                                <i class="fas fa-calendar-check"></i> Book Now
                            </a>
                        </div>
                    </div>
                </div>
            @endif

        <!-- Single Pagination Container (outside slides) -->
        <div class="slider-pagination-container">
            <div class="slider-pagination" id="sliderPagination"></div>
        </div>

        <button class="slider-btn slider-prev" id="sliderPrev">
                <i class="fas fa-chevron-left"></i>
            </button>
        <button class="slider-btn slider-next" id="sliderNext">
            <i class="fas fa-chevron-right"></i>
        </button>
        </div>

    <!-- Simple Search Modal -->
    <div class="search-modal-overlay" id="searchModal">
        <div class="search-modal-content">
            <button class="search-modal-close" id="closeSearchModal">
                <i class="fas fa-times"></i>
            </button>
            <div class="search-modal-body">
                <div class="search-modal-icon">
                    <i class="fas fa-compass"></i>
                </div>
                <h2>Find Your Perfect Tanzania Adventure</h2>
                <p class="search-modal-message">Search functionality would connect to a backend in a real application. For now, please call us at <a href="tel:+255789456123" class="phone-link">+255 789 456 123</a> to book your Tanzania adventure!</p>
                <div class="search-modal-actions">
                    <a href="tel:+255789456123" class="btn-search-call">
                        <i class="fas fa-phone"></i> Call Us Now
                    </a>
                    <a href="{{ route('contact') }}" class="btn-search-contact">
                        <i class="fas fa-envelope"></i> Contact Us
                    </a>
                </div>
                <div class="search-modal-quick-links">
                    <p>Quick Links:</p>
                    <div class="quick-links-grid">
                        <a href="{{ route('tours.index') }}"><i class="fas fa-route"></i> All Tours</a>
                        <a href="{{ route('safaris') }}"><i class="fas fa-camera"></i> Safaris</a>
                        <a href="{{ route('destinations.index') }}"><i class="fas fa-map-marker-alt"></i> Destinations</a>
                        <a href="{{ route('booking') }}"><i class="fas fa-calendar-check"></i> Book Now</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </section>

<!-- Trust Section -->
    <section class="trust-section">
        <div class="container">
            <div class="trust-grid">
            <div class="trust-item" data-aos="fade-up">
                    <div class="trust-icon"><i class="fas fa-users"></i></div>
                    <div class="trust-content">
                        <h3 class="trust-number" data-count="50000">0</h3>
                        <p class="trust-label">Happy Travelers</p>
                    </div>
                </div>
            <div class="trust-item" data-aos="fade-up" data-aos-delay="100">
                    <div class="trust-icon"><i class="fas fa-globe"></i></div>
                    <div class="trust-content">
                        <h3 class="trust-number" data-count="50">0</h3>
                        <p class="trust-label">Tanzania Destinations</p>
                    </div>
                </div>
            <div class="trust-item" data-aos="fade-up" data-aos-delay="200">
                    <div class="trust-icon"><i class="fas fa-star"></i></div>
                    <div class="trust-content">
                        <h3 class="trust-number" data-count="4.9">0</h3>
                        <p class="trust-label">Average Rating</p>
                    </div>
                </div>
            <div class="trust-item" data-aos="fade-up" data-aos-delay="300">
                    <div class="trust-icon"><i class="fas fa-award"></i></div>
                    <div class="trust-content">
                        <h3 class="trust-number" data-count="15">0</h3>
                        <p class="trust-label">Years in Tanzania</p>
                    </div>
                </div>
            </div>
            <div class="certifications" data-aos="fade-up">
                <p class="cert-label">Licensed & Certified By:</p>
                <div class="cert-badges">
                    <span class="cert-badge"><i class="fas fa-certificate"></i> TATO Member</span>
                    <span class="cert-badge"><i class="fas fa-shield-alt"></i> Fully Insured</span>
                    <span class="cert-badge"><i class="fas fa-check-circle"></i> TALA Certified</span>
                    <span class="cert-badge"><i class="fas fa-leaf"></i> Eco-Friendly</span>
                </div>
            </div>
        </div>
    </section>

<!-- Featured Tours -->
<section class="featured-tours-section" id="tours">
        <div class="container">
            <div class="section-header" data-aos="fade-up">
            <span class="section-badge"><i class="fas fa-star"></i> Tanzania's Best Tours</span>
                    <h2 class="section-title">Our Most Popular Tanzania Adventures</h2>
                <p class="section-subtitle">Carefully curated Tanzania tours that our travelers love. From Serengeti safaris to Kilimanjaro climbs, your perfect adventure awaits.</p>
            </div>
            <div class="tours-grid">
            @if(isset($featuredTours) && count($featuredTours) > 0)
                @foreach($featuredTours as $index => $tour)
                <div class="tour-card" data-aos="fade-up" data-aos-delay="{{ $index * 100 }}">
                    <div class="tour-card-image">
                        <img src="{{ $tour['image'] }}" alt="{{ $tour['name'] }}">
                        <div class="tour-card-badge">{{ $tour['destination'] }}</div>
                    </div>
                    <div class="tour-card-content">
                        <div class="tour-card-meta">
                            <span class="tour-duration"><i class="fas fa-clock"></i> {{ $tour['duration_days'] }} Days</span>
                            <span class="tour-rating"><i class="fas fa-star"></i> {{ $tour['rating'] }}</span>
                        </div>
                        <h3 class="tour-card-title">{{ $tour['name'] }}</h3>
                        <p class="tour-card-location"><i class="fas fa-map-marker-alt"></i> {{ $tour['destination'] }}</p>
                        <p class="tour-card-description">{{ $tour['description'] }}</p>
                        <div class="tour-card-features">
                            <span><i class="fas fa-check"></i> Expert Guides</span>
                            <span><i class="fas fa-check"></i> All Meals</span>
                            <span><i class="fas fa-check"></i> Equipment</span>
                        </div>
                        <div class="tour-card-footer">
                            <div class="tour-price">
                                <span class="price-label">From</span>
                                <span class="price-amount">${{ number_format($tour['starting_price']) }}</span>
                                <span class="price-note">per person</span>
                            </div>
                            <a href="{{ route('tours.show', $tour['slug']) }}" class="btn-secondary">View Details</a>
                        </div>
                    </div>
                </div>
                @endforeach
            @else
                <!-- Fallback tours if database is empty -->
                <div class="tour-card" data-aos="fade-up">
                    <div class="tour-card-image">
                        <img src="{{ asset('images/hero-slider/kilimanjaro-climbing.jpg') }}" alt="Kilimanjaro">
                        <div class="tour-card-badge">Climbing</div>
                    </div>
                    <div class="tour-card-content">
                        <div class="tour-card-meta">
                            <span class="tour-duration"><i class="fas fa-clock"></i> 7 Days</span>
                            <span class="tour-rating"><i class="fas fa-star"></i> 4.9</span>
                        </div>
                        <h3 class="tour-card-title">Kilimanjaro Machame Route</h3>
                        <p class="tour-card-location"><i class="fas fa-map-marker-alt"></i> Mount Kilimanjaro, Tanzania</p>
                        <p class="tour-card-description">Conquer Africa's highest peak via the scenic Machame route. Expert guides and premium equipment included.</p>
                         <div class="tour-card-footer">
                            <div class="tour-price">
                                <span class="price-label">From</span>
                                <span class="price-amount">$2,578</span>
                                <span class="price-note">per person</span>
                            </div>
                            <a href="{{ route('tours.index') }}" class="btn-secondary">View Details</a>
                        </div>
                    </div>
                </div>
            @endif
            </div>
             <div class="section-footer" data-aos="fade-up">
            <a href="{{ route('tours.index') }}" class="btn-primary btn-large">
                <i class="fas fa-compass"></i> View All Tours
                <i class="fas fa-arrow-right"></i>
            </a>
            </div>
        </div>
    </section>

<!-- Popular Destinations -->
<section class="popular-destinations-section">
        <div class="container">
            <div class="section-header" data-aos="fade-up">
            <span class="section-badge"><i class="fas fa-map-marker-alt"></i> Explore</span>
            <h2 class="section-title">Popular Destinations</h2>
            <p class="section-subtitle">Discover Tanzania's most iconic destinations</p>
                        </div>
        <div class="destinations-simple-grid">
            <a href="{{ route('destinations.show', 'serengeti') }}" class="destination-simple-card" data-aos="fade-up">
                <div class="destination-simple-image">
                    <img src="{{ asset('images/Serengetei-NP-2.jpeg') }}" alt="Serengeti">
                    </div>
                <div class="destination-simple-content">
                    <h3>Serengeti</h3>
                    <p>Great Migration</p>
                        </div>
            </a>
            <a href="{{ route('destinations.show', 'kilimanjaro') }}" class="destination-simple-card" data-aos="fade-up" data-aos-delay="100">
                <div class="destination-simple-image">
                    <img src="{{ asset('images/hero-slider/kilimanjaro-climbing.jpg') }}" alt="Kilimanjaro">
                        </div>
                <div class="destination-simple-content">
                    <h3>Kilimanjaro</h3>
                    <p>Africa's Highest Peak</p>
                            </div>
            </a>
            <a href="{{ route('destinations.show', 'zanzibar') }}" class="destination-simple-card" data-aos="fade-up" data-aos-delay="200">
                <div class="destination-simple-image">
                    <img src="{{ asset('images/zanzibar_home.jpg') }}" alt="Zanzibar">
                        </div>
                <div class="destination-simple-content">
                    <h3>Zanzibar</h3>
                    <p>Beach Paradise</p>
                    </div>
                </a>
            <a href="{{ route('destinations.show', 'ngorongoro') }}" class="destination-simple-card" data-aos="fade-up" data-aos-delay="300">
                <div class="destination-simple-image">
                    <img src="{{ asset('images/safari_home-1.jpg') }}" alt="Ngorongoro">
                </div>
                <div class="destination-simple-content">
                    <h3>Ngorongoro</h3>
                    <p>Crater Safari</p>
                    </div>
                </a>
            </div>
             <div class="section-footer" data-aos="fade-up">
            <a href="{{ route('destinations.index') }}" class="btn-secondary">
                <i class="fas fa-compass"></i> View All Destinations
                </a>
            </div>
        </div>
    </section>

<!-- Why Choose Us Simple -->
<section class="why-choose-simple-section">
        <div class="container">
            <div class="section-header" data-aos="fade-up">
            <span class="section-badge">Why Choose Us</span>
                <h2 class="section-title">Your Trusted Tanzania Travel Partner</h2>
            </div>
        <div class="benefits-grid">
            <div class="benefit-item" data-aos="fade-up">
                <div class="benefit-icon"><i class="fas fa-check-circle"></i></div>
                <h3>Tanzania Specialists</h3>
                <p>100% focused on Tanzania with unmatched local expertise</p>
                </div>
            <div class="benefit-item" data-aos="fade-up" data-aos-delay="100">
                <div class="benefit-icon"><i class="fas fa-car"></i></div>
                <h3>Private Safaris</h3>
                <p>Dedicated vehicles and guides for personalized experiences</p>
                </div>
            <div class="benefit-item" data-aos="fade-up" data-aos-delay="200">
                <div class="benefit-icon"><i class="fas fa-shield-alt"></i></div>
                <h3>Fully Insured</h3>
                <p>Licensed, certified, and fully insured for your peace of mind</p>
                </div>
            <div class="benefit-item" data-aos="fade-up" data-aos-delay="300">
                <div class="benefit-icon"><i class="fas fa-heart"></i></div>
                <h3>Personalized Service</h3>
                <p>Tailored itineraries to match your preferences and budget</p>
                </div>
            </div>
        </div>
    </section>
    
<!-- Activities Section -->
<section class="activities-section">
        <div class="container">
             <div class="section-header" data-aos="fade-up">
            <span class="section-badge"><i class="fas fa-mountain"></i> Activities</span>
            <h2 class="section-title">What You Can Experience</h2>
            <p class="section-subtitle">Discover amazing activities and experiences in Tanzania</p>
            </div>
        <div class="activities-grid">
            <div class="activity-card" data-aos="fade-up">
                <div class="activity-icon"><i class="fas fa-binoculars"></i></div>
                <h3>Wildlife Safari</h3>
                <p>Game drives, Big Five sightings, and incredible wildlife encounters</p>
                    </div>
            <div class="activity-card" data-aos="fade-up" data-aos-delay="100">
                <div class="activity-icon"><i class="fas fa-mountain"></i></div>
                <h3>Mountain Climbing</h3>
                <p>Conquer Kilimanjaro and other peaks with expert guides</p>
                    </div>
            <div class="activity-card" data-aos="fade-up" data-aos-delay="200">
                <div class="activity-icon"><i class="fas fa-umbrella-beach"></i></div>
                <h3>Beach Holidays</h3>
                <p>Relax on pristine beaches in Zanzibar and coastal areas</p>
                    </div>
            <div class="activity-card" data-aos="fade-up" data-aos-delay="300">
                <div class="activity-icon"><i class="fas fa-camera"></i></div>
                <h3>Photography Tours</h3>
                <p>Capture stunning wildlife and landscapes with professional guidance</p>
                    </div>
            <div class="activity-card" data-aos="fade-up" data-aos-delay="400">
                <div class="activity-icon"><i class="fas fa-users"></i></div>
                <h3>Cultural Tours</h3>
                <p>Experience local cultures, traditions, and authentic interactions</p>
            </div>
            <div class="activity-card" data-aos="fade-up" data-aos-delay="500">
                <div class="activity-icon"><i class="fas fa-water"></i></div>
                <h3>Water Activities</h3>
                <p>Snorkeling, diving, boat safaris, and water sports</p>
            </div>
        </div>
    </div>
</section>

<!-- Best Time to Visit -->
<section class="best-time-section">
    <div class="container">
        <div class="best-time-content" data-aos="fade-up">
            <div class="best-time-text">
                <span class="section-badge"><i class="fas fa-calendar-alt"></i> Travel Guide</span>
                <h2 class="section-title">Best Time to Visit Tanzania</h2>
                <p>Tanzania offers incredible experiences year-round, but timing can enhance your adventure:</p>
                <div class="season-grid">
                    <div class="season-item">
                        <h4><i class="fas fa-sun"></i> Dry Season</h4>
                        <p><strong>June - October</strong></p>
                        <p>Best for wildlife viewing, clear skies, and Great Migration</p>
                    </div>
                    <div class="season-item">
                        <h4><i class="fas fa-leaf"></i> Green Season</h4>
                        <p><strong>November - May</strong></p>
                        <p>Lush landscapes, bird watching, fewer crowds, lower prices</p>
                    </div>
                </div>
                <a href="{{ route('contact') }}" class="btn-secondary">
                    <i class="fas fa-info-circle"></i> Get Travel Advice
                </a>
            </div>
            </div>
        </div>
    </section>

<!-- Photo Gallery -->
<section class="photo-gallery-section">
    <div class="container">
        <div class="section-header" data-aos="fade-up">
            <span class="section-badge"><i class="fas fa-images"></i> Gallery</span>
            <h2 class="section-title">Tanzania in Pictures</h2>
            <p class="section-subtitle">Glimpse the beauty that awaits you</p>
        </div>
        @if(isset($homepageGallery) && $homepageGallery->count() > 0)
        <div class="gallery-grid" id="homepageGallery">
            @foreach($homepageGallery as $index => $image)
            <div class="gallery-item" data-aos="fade-up" data-aos-delay="{{ $index * 100 }}" 
                 data-gallery-id="{{ $image['id'] }}"
                 data-image-url="{{ $image['image_url'] }}"
                 data-image-title="{{ $image['title'] }}"
                 data-image-caption="{{ $image['caption'] ?? '' }}">
                <img src="{{ $image['thumbnail_url'] }}" 
                     alt="{{ $image['alt_text'] }}" 
                     loading="lazy"
                     onerror="this.src='{{ asset('images/safari_home-1.jpg') }}'">
                <div class="gallery-overlay">
                    <i class="fas fa-search-plus"></i>
                    @if($image['caption'])
                    <p class="gallery-caption">{{ Str::limit($image['caption'], 50) }}</p>
                    @endif
                </div>
            </div>
            @endforeach
        </div>
        @else
        <!-- Fallback gallery if no images in database -->
        <div class="gallery-grid">
            <div class="gallery-item" data-aos="fade-up">
                <img src="{{ asset('images/Serengetei-NP-2.jpeg') }}" alt="Serengeti Wildlife">
                <div class="gallery-overlay">
                    <i class="fas fa-search-plus"></i>
                </div>
            </div>
            <div class="gallery-item" data-aos="fade-up" data-aos-delay="100">
                <img src="{{ asset('images/hero-slider/kilimanjaro-climbing.jpg') }}" alt="Kilimanjaro">
                <div class="gallery-overlay">
                    <i class="fas fa-search-plus"></i>
                </div>
            </div>
            <div class="gallery-item" data-aos="fade-up" data-aos-delay="200">
                <img src="{{ asset('images/zanzibar_home.jpg') }}" alt="Zanzibar Beach">
                <div class="gallery-overlay">
                    <i class="fas fa-search-plus"></i>
                </div>
            </div>
            <div class="gallery-item" data-aos="fade-up" data-aos-delay="300">
                <img src="{{ asset('images/Tarangire-NP-1.jpeg') }}" alt="Tarangire">
                <div class="gallery-overlay">
                    <i class="fas fa-search-plus"></i>
                </div>
            </div>
            <div class="gallery-item" data-aos="fade-up" data-aos-delay="400">
                <img src="{{ asset('images/Mara-River-3-1536x1024.jpg') }}" alt="Mara River">
                <div class="gallery-overlay">
                    <i class="fas fa-search-plus"></i>
                </div>
            </div>
            <div class="gallery-item" data-aos="fade-up" data-aos-delay="500">
                <img src="{{ asset('images/safari_home-1.jpg') }}" alt="Safari">
                <div class="gallery-overlay">
                    <i class="fas fa-search-plus"></i>
                </div>
            </div>
        </div>
        @endif
    </div>
</section>

<!-- Lightbox Modal for Gallery -->
<div class="gallery-lightbox" id="galleryLightbox">
    <div class="lightbox-overlay"></div>
    <div class="lightbox-content">
        <button class="lightbox-close" id="lightboxClose">
            <i class="fas fa-times"></i>
        </button>
        <button class="lightbox-prev" id="lightboxPrev">
            <i class="fas fa-chevron-left"></i>
        </button>
        <button class="lightbox-next" id="lightboxNext">
            <i class="fas fa-chevron-right"></i>
        </button>
        <div class="lightbox-image-container">
            <img id="lightboxImage" src="" alt="">
            <div class="lightbox-caption">
                <h4 id="lightboxTitle"></h4>
                <p id="lightboxDescription"></p>
            </div>
        </div>
    </div>
</div>

<!-- Travel Tips Section -->
<section class="travel-tips-section">
    <div class="container">
        <div class="section-header" data-aos="fade-up">
            <span class="section-badge"><i class="fas fa-lightbulb"></i> Travel Tips</span>
            <h2 class="section-title">Essential Travel Information</h2>
                    </div>
        <div class="tips-grid">
            <div class="tip-card" data-aos="fade-up">
                <div class="tip-icon"><i class="fas fa-passport"></i></div>
                <h3>Visa Requirements</h3>
                <p>Most visitors need a visa. Apply online or get on arrival at the airport.</p>
                </div>
            <div class="tip-card" data-aos="fade-up" data-aos-delay="100">
                <div class="tip-icon"><i class="fas fa-syringe"></i></div>
                <h3>Health & Vaccinations</h3>
                <p>Yellow fever vaccination recommended. Consult your doctor before travel.</p>
            </div>
            <div class="tip-card" data-aos="fade-up" data-aos-delay="200">
                <div class="tip-icon"><i class="fas fa-money-bill-wave"></i></div>
                <h3>Currency</h3>
                <p>Tanzanian Shilling (TZS). USD widely accepted. Credit cards in major areas.</p>
            </div>
            <div class="tip-card" data-aos="fade-up" data-aos-delay="300">
                <div class="tip-icon"><i class="fas fa-language"></i></div>
                <h3>Language</h3>
                <p>Swahili and English are official languages. English widely spoken.</p>
            </div>
             </div>
        </div>
    </section>

<!-- Google & TripAdvisor Reviews Carousel -->
<section class="reviews-carousel-section">
    <div class="container">
        <div class="section-header" data-aos="fade-up">
            <span class="section-badge"><i class="fas fa-star"></i> Reviews</span>
            <h2 class="section-title">What Our Guests Say</h2>
            <p class="section-subtitle">Real reviews from Google and TripAdvisor</p>
        </div>
        <div class="reviews-carousel-wrapper">
            <div class="reviews-carousel-track" id="reviewsCarousel">
                <!-- Google Reviews -->
                <div class="review-card">
                    <div class="review-platform">
                        <i class="fab fa-google"></i>
                        <span>Google</span>
                    </div>
                    <div class="review-rating">
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                    </div>
                    <p class="review-text">"Amazing safari experience! The guides were knowledgeable and we saw the Big Five. Highly recommend Lau Paradise Adventures!"</p>
                    <div class="review-author">
                        <strong>Sarah Johnson</strong>
                        <span>United States</span>
                    </div>
                </div>
                <div class="review-card">
                    <div class="review-platform">
                        <i class="fab fa-google"></i>
                        <span>Google</span>
                    </div>
                    <div class="review-rating">
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                    </div>
                    <p class="review-text">"Best travel company in Tanzania! They organized everything perfectly. The Kilimanjaro climb was unforgettable."</p>
                    <div class="review-author">
                        <strong>Michael Chen</strong>
                        <span>Australia</span>
                    </div>
                </div>
                <div class="review-card">
                    <div class="review-platform">
                        <i class="fab fa-tripadvisor"></i>
                        <span>TripAdvisor</span>
                    </div>
                    <div class="review-rating">
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                    </div>
                    <p class="review-text">"Outstanding service from start to finish. The Serengeti safari exceeded all expectations. Professional team and great value!"</p>
                    <div class="review-author">
                        <strong>Emma Williams</strong>
                        <span>United Kingdom</span>
                    </div>
                </div>
                <div class="review-card">
                    <div class="review-platform">
                        <i class="fab fa-tripadvisor"></i>
                        <span>TripAdvisor</span>
                    </div>
                    <div class="review-rating">
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                    </div>
                    <p class="review-text">"Zanzibar beach holiday was perfect! Great communication, excellent accommodations, and beautiful locations."</p>
                    <div class="review-author">
                        <strong>David Martinez</strong>
                        <span>Spain</span>
                    </div>
                </div>
                <div class="review-card">
                    <div class="review-platform">
                        <i class="fab fa-google"></i>
                        <span>Google</span>
                    </div>
                    <div class="review-rating">
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                    </div>
                    <p class="review-text">"The Great Migration tour was incredible! We witnessed thousands of wildebeest crossing. A once-in-a-lifetime experience!"</p>
                    <div class="review-author">
                        <strong>Lisa Anderson</strong>
                        <span>Canada</span>
                    </div>
                </div>
                <div class="review-card">
                    <div class="review-platform">
                        <i class="fab fa-tripadvisor"></i>
                        <span>TripAdvisor</span>
                    </div>
                    <div class="review-rating">
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                    </div>
                    <p class="review-text">"Professional, reliable, and friendly team. Our custom safari itinerary was perfectly tailored to our interests."</p>
                    <div class="review-author">
                        <strong>James Brown</strong>
                        <span>South Africa</span>
                    </div>
                </div>
                <!-- Duplicate cards for seamless loop -->
                <div class="review-card">
                    <div class="review-platform">
                        <i class="fab fa-google"></i>
                        <span>Google</span>
                    </div>
                    <div class="review-rating">
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                    </div>
                    <p class="review-text">"Amazing safari experience! The guides were knowledgeable and we saw the Big Five. Highly recommend Lau Paradise Adventures!"</p>
                    <div class="review-author">
                        <strong>Sarah Johnson</strong>
                        <span>United States</span>
                    </div>
                </div>
                <div class="review-card">
                    <div class="review-platform">
                        <i class="fab fa-google"></i>
                        <span>Google</span>
                    </div>
                    <div class="review-rating">
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                    </div>
                    <p class="review-text">"Best travel company in Tanzania! They organized everything perfectly. The Kilimanjaro climb was unforgettable."</p>
                    <div class="review-author">
                        <strong>Michael Chen</strong>
                        <span>Australia</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="reviews-platform-badges" data-aos="fade-up">
            <div class="platform-badge">
                <i class="fab fa-google"></i>
                <div class="platform-info">
                    <strong>4.9/5</strong>
                    <span>Google Reviews</span>
                </div>
            </div>
            <div class="platform-badge">
                <i class="fab fa-tripadvisor"></i>
                <div class="platform-info">
                    <strong>4.8/5</strong>
                    <span>TripAdvisor</span>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Simple CTA Section -->
<section class="simple-cta-section">
        <div class="container">
        <div class="simple-cta-content" data-aos="fade-up">
            <h2 class="simple-cta-title">Ready to Start Your Tanzania Adventure?</h2>
            <p class="simple-cta-text">Get a free, personalized quote for your dream trip</p>
            <div class="simple-cta-buttons">
                <a href="{{ route('booking') }}" class="btn-primary btn-large">
                    <i class="fas fa-calendar-check"></i> Book Your Trip
                </a>
                <a href="{{ route('contact') }}" class="btn-secondary btn-large">
                    <i class="fas fa-envelope"></i> Contact Us
                </a>
                </div>
            </div>
        </div>
    </section>

<style>
/* Popular Destinations Simple */
.popular-destinations-section {
    padding: 80px 0;
    background: var(--white);
}
.destinations-simple-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 30px;
    margin-top: 50px;
}
.destination-simple-card {
    display: block;
    background: var(--white);
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        transition: all 0.3s ease;
    text-decoration: none;
}
.destination-simple-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
}
.destination-simple-image {
    width: 100%;
    height: 200px;
    overflow: hidden;
}
.destination-simple-image img {
        width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.3s ease;
}
.destination-simple-card:hover .destination-simple-image img {
        transform: scale(1.1);
    }
.destination-simple-content {
    padding: 20px;
        text-align: center; 
}
.destination-simple-content h3 {
    font-size: 1.4rem;
    color: var(--primary-green);
    margin-bottom: 5px;
    font-weight: 700;
}
.destination-simple-content p {
    color: var(--gray);
    font-size: 0.95rem;
}

/* Why Choose Us Simple */
.why-choose-simple-section {
    padding: 80px 0;
    background: var(--gray-light);
}
.benefits-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 40px;
    margin-top: 50px;
}
.benefit-item {
        text-align: center;
    padding: 30px 20px;
    background: var(--white);
    border-radius: 12px;
        transition: all 0.3s ease;
}
.benefit-item:hover {
        transform: translateY(-5px);
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
    }
.benefit-icon {
    font-size: 3rem;
    color: var(--accent-green);
        margin-bottom: 20px;
    }
.benefit-item h3 {
    font-size: 1.3rem;
    color: var(--primary-green);
    margin-bottom: 12px;
    font-weight: 700;
}
.benefit-item p {
    color: var(--gray);
    line-height: 1.6;
        font-size: 0.95rem;
    }

/* Simple CTA */
.simple-cta-section {
    padding: 80px 0;
    background: linear-gradient(135deg, var(--primary-green) 0%, var(--accent-green) 100%);
        color: var(--white); 
        text-align: center; 
    }
.simple-cta-content {
    max-width: 700px;
    margin: 0 auto;
}
.simple-cta-title {
    font-size: 2.5rem;
    font-weight: 700;
    margin-bottom: 15px;
        color: var(--white);
}
.simple-cta-text {
    font-size: 1.2rem;
    margin-bottom: 35px;
    opacity: 0.95;
}
.simple-cta-buttons {
        display: flex;
        justify-content: center;
    gap: 20px;
    flex-wrap: wrap;
}
.btn-large {
    padding: 15px 35px;
    font-size: 1.1rem;
}

/* Activities Section */
.activities-section {
    padding: 80px 0;
    background: var(--white);
}
.activities-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
    gap: 30px;
    margin-top: 50px;
}
.activity-card {
    text-align: center;
    padding: 40px 25px;
    background: var(--gray-light);
    border-radius: 12px;
    transition: all 0.3s ease;
}
.activity-card:hover {
    transform: translateY(-5px);
    background: var(--white);
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
}
.activity-icon {
    font-size: 3.5rem;
    color: var(--accent-green);
    margin-bottom: 20px;
}
.activity-card h3 {
    font-size: 1.3rem;
    color: var(--primary-green);
    margin-bottom: 12px;
    font-weight: 700;
}
.activity-card p {
    color: var(--gray);
    line-height: 1.6;
    font-size: 0.95rem;
}

/* Best Time Section */
.best-time-section {
    padding: 80px 0;
    background: linear-gradient(135deg, var(--primary-green) 0%, var(--accent-green) 100%);
    color: var(--white);
}
.best-time-content {
    max-width: 900px;
    margin: 0 auto;
    text-align: center;
}
.best-time-text .section-badge {
    background: rgba(255, 255, 255, 0.2);
    color: var(--white);
    border-color: rgba(255, 255, 255, 0.3);
}
.best-time-text .section-title {
    color: var(--white);
    margin: 20px 0 15px;
}
.best-time-text > p {
    font-size: 1.1rem;
    margin-bottom: 40px;
    opacity: 0.95;
}
.season-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 30px;
    margin: 40px 0;
}
.season-item {
    background: rgba(255, 255, 255, 0.1);
    padding: 30px;
    border-radius: 12px;
    backdrop-filter: blur(10px);
}
.season-item h4 {
    font-size: 1.4rem;
    margin-bottom: 15px;
        display: flex;
        align-items: center;
    justify-content: center;
    gap: 10px;
}
.season-item p {
    margin-bottom: 10px;
    line-height: 1.6;
}

/* Photo Gallery */
.photo-gallery-section {
    padding: 80px 0;
    background: var(--gray-light);
}
.gallery-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 20px;
    margin-top: 50px;
}
.gallery-item {
        position: relative;
    overflow: hidden;
    border-radius: 12px;
    aspect-ratio: 4/3;
    cursor: pointer;
}
.gallery-item img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.3s ease;
}
.gallery-overlay {
        position: absolute; 
    top: 0;
        left: 0; 
        right: 0;
    bottom: 0;
    background: rgba(26, 77, 58, 0.7);
    display: flex;
    align-items: center;
    justify-content: center;
    opacity: 0;
    transition: opacity 0.3s ease;
}
.gallery-item:hover .gallery-overlay {
    opacity: 1;
}
.gallery-item:hover img {
        transform: scale(1.1);
    }
.gallery-overlay i {
    font-size: 2.5rem;
    color: var(--white);
}
.gallery-caption {
    position: absolute;
    bottom: 15px;
    left: 15px;
    right: 15px;
    color: var(--white);
    font-size: 0.9rem;
    text-align: center;
    margin-top: 10px;
}

/* Gallery Lightbox */
.gallery-lightbox {
    display: none;
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    z-index: 9999;
}
.gallery-lightbox.active {
    display: flex;
    align-items: center;
    justify-content: center;
}
.lightbox-overlay {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.95);
    backdrop-filter: blur(5px);
}
.lightbox-content {
    position: relative;
    max-width: 90%;
    max-height: 90%;
    z-index: 10000;
    display: flex;
    align-items: center;
    justify-content: center;
}
.lightbox-image-container {
    position: relative;
    max-width: 100%;
    max-height: 90vh;
}
.lightbox-image-container img {
    max-width: 100%;
    max-height: 90vh;
    object-fit: contain;
    border-radius: 8px;
    box-shadow: 0 10px 50px rgba(0, 0, 0, 0.5);
}
.lightbox-caption {
    position: absolute;
    bottom: -60px;
    left: 50%;
    transform: translateX(-50%);
    text-align: center;
    color: var(--white);
    width: 100%;
}
.lightbox-caption h4 {
    color: var(--white);
    margin-bottom: 5px;
    font-size: 1.2rem;
}
.lightbox-caption p {
    color: rgba(255, 255, 255, 0.8);
    font-size: 0.9rem;
}
.lightbox-close,
.lightbox-prev,
.lightbox-next {
    position: absolute;
    background: rgba(255, 255, 255, 0.1);
    border: 2px solid rgba(255, 255, 255, 0.3);
    color: var(--white);
    width: 50px;
    height: 50px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: all 0.3s ease;
    z-index: 10001;
    font-size: 1.2rem;
}
.lightbox-close:hover,
.lightbox-prev:hover,
.lightbox-next:hover {
    background: rgba(255, 255, 255, 0.2);
    border-color: rgba(255, 255, 255, 0.5);
    transform: scale(1.1);
}
.lightbox-close {
    top: 20px;
    right: 20px;
}
.lightbox-prev {
    left: 20px;
    top: 50%;
    transform: translateY(-50%);
}
.lightbox-next {
    right: 20px;
    top: 50%;
    transform: translateY(-50%);
}

/* Travel Tips */
.travel-tips-section {
    padding: 80px 0;
    background: var(--white);
}
.tips-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 30px;
    margin-top: 50px;
}
.tip-card {
    text-align: center;
    padding: 35px 25px;
        background: var(--gray-light);
    border-radius: 12px;
    transition: all 0.3s ease;
}
.tip-card:hover {
        transform: translateY(-5px);
    background: var(--white);
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
    }
.tip-icon {
    font-size: 3rem;
    color: var(--accent-green);
        margin-bottom: 20px;
    }
.tip-card h3 {
    font-size: 1.2rem;
    color: var(--primary-green);
    margin-bottom: 12px;
    font-weight: 700;
}
.tip-card p {
    color: var(--gray);
    line-height: 1.6;
        font-size: 0.95rem;
    }

/* Reviews Carousel Section */
.reviews-carousel-section {
    padding: 80px 0;
    background: var(--gray-light);
    overflow: hidden;
}
.reviews-carousel-wrapper {
    position: relative;
    margin-top: 50px;
    overflow: hidden;
    padding: 20px 0;
}
.reviews-carousel-track {
    display: flex;
    gap: 30px;
    animation: scrollReviews 30s linear infinite;
    width: fit-content;
}
.review-card {
    min-width: 350px;
    background: var(--white);
    padding: 30px;
    border-radius: 15px;
    box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
    transition: all 0.3s ease;
}
.review-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
}
.review-platform {
    display: flex;
    align-items: center;
    gap: 10px;
    margin-bottom: 15px;
    font-size: 0.9rem;
    color: var(--gray);
    font-weight: 600;
}
.review-platform i {
    font-size: 1.2rem;
}
.review-platform i.fa-google {
    color: #4285F4;
}
.review-platform i.fa-tripadvisor {
    color: #00AF87;
}
.review-rating {
    color: #FFA500;
    font-size: 1rem;
    margin-bottom: 15px;
}
.review-text {
    color: var(--gray);
    line-height: 1.7;
    margin-bottom: 20px;
    font-size: 0.95rem;
    font-style: italic;
}
.review-author {
    display: flex;
    flex-direction: column;
    gap: 3px;
    padding-top: 15px;
    border-top: 1px solid var(--gray-light);
}
.review-author strong {
    color: var(--primary-green);
    font-size: 0.95rem;
}
.review-author span {
    color: var(--gray);
    font-size: 0.85rem;
}
.reviews-platform-badges {
    display: flex;
    justify-content: center;
    gap: 40px;
    margin-top: 50px;
    flex-wrap: wrap;
}
.platform-badge {
    display: flex;
    align-items: center;
    gap: 15px;
    background: var(--white);
    padding: 20px 30px;
    border-radius: 12px;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
}
.platform-badge i {
    font-size: 2.5rem;
}
.platform-badge i.fa-google {
    color: #4285F4;
}
.platform-badge i.fa-tripadvisor {
    color: #00AF87;
}
.platform-info {
    display: flex;
    flex-direction: column;
}
.platform-info strong {
    font-size: 1.5rem;
    color: var(--primary-green);
    font-weight: 700;
}
.platform-info span {
    font-size: 0.9rem;
    color: var(--gray);
}

@keyframes scrollReviews {
    0% {
        transform: translateX(0);
    }
    100% {
        transform: translateX(-50%);
    }
}

.reviews-carousel-track:hover {
    animation-play-state: paused;
}

@media (max-width: 768px) {
    .review-card {
        min-width: 280px;
    }
    .reviews-platform-badges {
        flex-direction: column;
        align-items: center;
        gap: 20px;
    }
    .destinations-simple-grid {
        grid-template-columns: repeat(2, 1fr);
        gap: 20px;
    }
    .benefits-grid {
        grid-template-columns: 1fr;
        gap: 25px;
    }
    .activities-grid {
        grid-template-columns: repeat(2, 1fr);
        gap: 20px;
    }
    .season-grid {
        grid-template-columns: 1fr;
    }
    .gallery-grid {
        grid-template-columns: repeat(2, 1fr);
        gap: 15px;
    }
    .tips-grid {
        grid-template-columns: repeat(2, 1fr);
        gap: 20px;
    }
    .simple-cta-title {
            font-size: 2rem; 
    }
    .simple-cta-text {
        font-size: 1rem;
    }
    .simple-cta-buttons {
            flex-direction: column;
        align-items: center;
        }
    .btn-large {
            width: 100%;
        max-width: 300px;
    }
    }
</style>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const galleryItems = document.querySelectorAll('.gallery-item[data-image-url]');
    const lightbox = document.getElementById('galleryLightbox');
    const lightboxImage = document.getElementById('lightboxImage');
    const lightboxTitle = document.getElementById('lightboxTitle');
    const lightboxDescription = document.getElementById('lightboxDescription');
    const lightboxClose = document.getElementById('lightboxClose');
    const lightboxPrev = document.getElementById('lightboxPrev');
    const lightboxNext = document.getElementById('lightboxNext');
    const lightboxOverlay = lightbox.querySelector('.lightbox-overlay');
    
    let currentIndex = 0;
    const images = Array.from(galleryItems).map(item => ({
        url: item.dataset.imageUrl,
        title: item.dataset.imageTitle || '',
        caption: item.dataset.imageCaption || ''
    }));

    // Open lightbox
    function openLightbox(index) {
        if (index < 0 || index >= images.length) return;
        currentIndex = index;
        const image = images[currentIndex];
        lightboxImage.src = image.url;
        lightboxTitle.textContent = image.title;
        lightboxDescription.textContent = image.caption;
        lightbox.classList.add('active');
        document.body.style.overflow = 'hidden';
    }

    // Close lightbox
    function closeLightbox() {
        lightbox.classList.remove('active');
        document.body.style.overflow = '';
    }

    // Navigate to previous image
    function prevImage() {
        currentIndex = (currentIndex - 1 + images.length) % images.length;
        openLightbox(currentIndex);
    }

    // Navigate to next image
    function nextImage() {
        currentIndex = (currentIndex + 1) % images.length;
        openLightbox(currentIndex);
    }

    // Event listeners
    galleryItems.forEach((item, index) => {
        item.addEventListener('click', () => openLightbox(index));
    });

    lightboxClose.addEventListener('click', closeLightbox);
    lightboxOverlay.addEventListener('click', closeLightbox);
    lightboxPrev.addEventListener('click', prevImage);
    lightboxNext.addEventListener('click', nextImage);

    // Keyboard navigation
    document.addEventListener('keydown', function(e) {
        if (!lightbox.classList.contains('active')) return;
        
        if (e.key === 'Escape') {
            closeLightbox();
        } else if (e.key === 'ArrowLeft') {
            prevImage();
        } else if (e.key === 'ArrowRight') {
            nextImage();
        }
    });

    // Prevent lightbox content from closing when clicking on image
    lightboxImage.addEventListener('click', function(e) {
        e.stopPropagation();
    });
});
</script>
@endpush

@endsection
