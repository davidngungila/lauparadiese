@extends('layouts.app')

@section('title', ($tour->meta_title ?? $tour->name) . ' - Lau Paradise Adventures')
@section('description', $tour->meta_description ?? $tour->short_description ?? $tour->description)

@section('content')

<!-- Tour Hero Section -->
<section class="tour-hero-section" style="background-image: url('{{ $tour->image_url ? (str_starts_with($tour->image_url, 'http') ? $tour->image_url : asset($tour->image_url)) : asset('images/safari_home-1.jpg') }}');">
    <div class="tour-hero-overlay"></div>
    <div class="container">
        <div class="tour-hero-content" data-aos="fade-up">
            <nav class="tour-breadcrumb">
                <a href="{{ route('home') }}"><i class="fas fa-home"></i> Home</a>
                <span>/</span>
                <a href="{{ route('tours.index') }}">Tours</a>
                @if($tour->destination)
                <span>/</span>
                <a href="{{ route('destinations.show', $tour->destination->slug) }}">{{ $tour->destination->name }}</a>
                @endif
            </nav>
            @if($tour->is_featured)
            <span class="tour-badge-featured"><i class="fas fa-star"></i> Featured Tour</span>
            @endif
            <h1 class="tour-hero-title">{{ $tour->name }}</h1>
            <p class="tour-hero-subtitle">{{ $tour->short_description ?: substr($tour->description ?? '', 0, 200) }}</p>
            <div class="tour-hero-meta">
                @if($tour->destination)
                <span><i class="fas fa-map-marker-alt"></i> {{ $tour->destination->name }}</span>
                @endif
                <span><i class="fas fa-clock"></i> {{ $tour->duration_days }} Days</span>
                @if($tour->rating)
                <span><i class="fas fa-star"></i> {{ number_format($tour->rating, 1) }} Rating</span>
                @endif
            </div>
        </div>
    </div>
</section>

<!-- Quick Stats Bar -->
<section class="tour-stats-bar">
        <div class="container">
        <div class="stats-grid">
            <div class="stat-item" data-aos="fade-up">
                <i class="fas fa-calendar-alt"></i>
                <div>
                    <strong>{{ $tour->duration_days }} Days</strong>
                    <span>{{ $tour->duration_nights ?? $tour->duration_days - 1 }} Nights</span>
                </div>
            </div>
            <div class="stat-item" data-aos="fade-up" data-aos-delay="100">
                <i class="fas fa-users"></i>
                <div>
                    <strong>Max {{ $tour->max_group_size ?? 12 }}</strong>
                    <span>Group Size</span>
                </div>
            </div>
            <div class="stat-item" data-aos="fade-up" data-aos-delay="200">
                <i class="fas fa-signal"></i>
                <div>
                    <strong>{{ ucfirst($tour->difficulty_level ?? 'Moderate') }}</strong>
                    <span>Difficulty</span>
                </div>
            </div>
            @if($tour->fitness_level)
            <div class="stat-item" data-aos="fade-up" data-aos-delay="300">
                <i class="fas fa-running"></i>
                <div>
                    <strong>{{ ucfirst($tour->fitness_level) }}</strong>
                    <span>Fitness Level</span>
                </div>
            </div>
            @endif
            @if($tour->min_age)
            <div class="stat-item" data-aos="fade-up" data-aos-delay="{{ $tour->fitness_level ? '400' : '300' }}">
                <i class="fas fa-user-check"></i>
                <div>
                    <strong>{{ $tour->min_age }}+ Years</strong>
                    <span>Min Age</span>
                </div>
            </div>
            @endif
            <div class="stat-item" data-aos="fade-up" data-aos-delay="{{ $tour->fitness_level && $tour->min_age ? '500' : ($tour->fitness_level || $tour->min_age ? '400' : '300') }}">
                <i class="fas fa-dollar-sign"></i>
                <div>
                    <strong>From ${{ number_format($tour->starting_price ?? $tour->price) }}</strong>
                    <span>Per Person</span>
                </div>
            </div>
            </div>
        </div>
    </section>

<!-- Main Content Section -->
<section class="tour-main-section">
        <div class="container">
            <div class="tour-layout">
                <!-- Main Content -->
                <div class="tour-content-main">
                <!-- Tabs Navigation -->
                <div class="tour-tabs-wrapper" x-data="{ activeTab: 'overview' }">
                    <div class="tour-tabs-nav">
                        <button @click="activeTab = 'overview'" :class="{ 'active': activeTab === 'overview' }" class="tab-btn">
                            <i class="fas fa-info-circle"></i> Overview
                        </button>
                        <button @click="activeTab = 'itinerary'" :class="{ 'active': activeTab === 'itinerary' }" class="tab-btn">
                            <i class="fas fa-route"></i> Itinerary
                        </button>
                        <button @click="activeTab = 'included'" :class="{ 'active': activeTab === 'included' }" class="tab-btn">
                            <i class="fas fa-check-circle"></i> What's Included
                        </button>
                        <button @click="activeTab = 'highlights'" :class="{ 'active': activeTab === 'highlights' }" class="tab-btn">
                            <i class="fas fa-star"></i> Highlights
                        </button>
                        <button @click="activeTab = 'gallery'" :class="{ 'active': activeTab === 'gallery' }" class="tab-btn">
                            <i class="fas fa-images"></i> Gallery
                        </button>
                        <button @click="activeTab = 'reviews'" :class="{ 'active': activeTab === 'reviews' }" class="tab-btn">
                            <i class="fas fa-comments"></i> Reviews
                        </button>
                        <button @click="activeTab = 'faq'" :class="{ 'active': activeTab === 'faq' }" class="tab-btn">
                            <i class="fas fa-question-circle"></i> FAQ
                        </button>
                    </div>

                    <!-- Tab Contents -->
                    <div class="tour-tabs-content">
                        <!-- Overview Tab -->
                        <div x-show="activeTab === 'overview'" x-transition class="tab-content-panel">
                            <div class="content-section">
                                <h2 class="section-title">About This Tour</h2>
                                <div class="tour-description">
                                    {!! nl2br(e($tour->long_description ?: $tour->description)) !!}
                                </div>

                                <!-- Tour Details Grid -->
                                <div class="tour-details-grid">
                                    @if($tour->start_location || $tour->end_location)
                                    <div class="detail-card">
                                        <div class="detail-icon"><i class="fas fa-map-marked-alt"></i></div>
                                        <h3>Tour Locations</h3>
                                        @if($tour->start_location)
                                        <p><strong>Start:</strong> {{ $tour->start_location }}</p>
                                        @endif
                                        @if($tour->end_location)
                                        <p><strong>End:</strong> {{ $tour->end_location }}</p>
                                        @endif
                                    </div>
                                    @endif

                                    @if($tour->tour_type)
                                    <div class="detail-card">
                                        <div class="detail-icon"><i class="fas fa-users"></i></div>
                                        <h3>Tour Type</h3>
                                        <p><strong>{{ $tour->tour_type }}</strong></p>
                                        <p class="detail-note">Maximum group size: {{ $tour->max_group_size ?? 12 }} travelers</p>
                                    </div>
                                    @endif

                                    @if($tour->min_age)
                                    <div class="detail-card">
                                        <div class="detail-icon"><i class="fas fa-user-check"></i></div>
                                        <h3>Age Requirements</h3>
                                        <p>Minimum age: <strong>{{ $tour->min_age }} years</strong></p>
                                    </div>
                                    @endif

                                    @if($tour->fitness_level)
                                    <div class="detail-card">
                                        <div class="detail-icon"><i class="fas fa-running"></i></div>
                                        <h3>Fitness Level</h3>
                                        <p>Recommended: <strong>{{ ucfirst($tour->fitness_level) }}</strong></p>
                                    </div>
                                    @endif

                                    @if($tour->difficulty_level)
                                    <div class="detail-card">
                                        <div class="detail-icon"><i class="fas fa-signal"></i></div>
                                        <h3>Difficulty Level</h3>
                                        <p><strong>{{ ucfirst($tour->difficulty_level) }}</strong></p>
                                    </div>
                                    @endif

                                    @if($tour->duration_days)
                                    <div class="detail-card">
                                        <div class="detail-icon"><i class="fas fa-calendar-alt"></i></div>
                                        <h3>Duration</h3>
                                        <p><strong>{{ $tour->duration_days }} Days</strong></p>
                                        <p class="detail-note">{{ $tour->duration_nights ?? ($tour->duration_days - 1) }} Nights</p>
                                    </div>
                                    @endif
                                </div>

                                @if($tour->important_notes)
                                <div class="important-notes">
                                    <h3><i class="fas fa-exclamation-triangle"></i> Important Notes</h3>
                                    <p>{{ $tour->important_notes }}</p>
                                </div>
                                @endif

                                @if($tour->terms_conditions)
                                <div class="terms-conditions">
                                    <h3><i class="fas fa-file-contract"></i> Terms & Conditions</h3>
                                    <p>{{ $tour->terms_conditions }}</p>
                                </div>
                                @endif

                                @if($tour->cancellation_policy)
                                <div class="cancellation-policy">
                                    <h3><i class="fas fa-undo"></i> Cancellation Policy</h3>
                                    <p>{{ $tour->cancellation_policy }}</p>
                                </div>
                                @endif
                            </div>
                        </div>

                            <!-- Itinerary Tab -->
                        <div x-show="activeTab === 'itinerary'" x-transition class="tab-content-panel">
                            <div class="content-section">
                                <h2 class="section-title">Day-by-Day Itinerary</h2>
                                <p class="section-subtitle">A detailed breakdown of your {{ $tour->duration_days }}-day adventure</p>
                                
                                @if($tour->itineraries && $tour->itineraries->count() > 0)
                                    <div class="itinerary-timeline">
                                        @foreach($tour->itineraries->sortBy('day_number') as $itinerary)
                                        <div class="itinerary-day" data-aos="fade-up">
                                            <div class="day-number">Day {{ $itinerary->day_number }}</div>
                                            <div class="day-content">
                                                <h3>{{ $itinerary->title }}</h3>
                                                @if($itinerary->description)
                                                <p>{{ $itinerary->description }}</p>
                                                @endif
                                                @if($itinerary->location)
                                                <p class="day-location"><i class="fas fa-map-marker-alt"></i> {{ $itinerary->location }}</p>
                                                @endif
                                                @if($itinerary->meals_included && is_array($itinerary->meals_included))
                                                <div class="day-meals">
                                                    <strong>Meals:</strong>
                                                    @foreach($itinerary->meals_included as $meal)
                                                    <span class="meal-badge">{{ $meal }}</span>
                                                    @endforeach
                                                </div>
                                                @endif
                                                @if($itinerary->accommodation_name)
                                                <div class="day-accommodation">
                                                    <strong><i class="fas fa-bed"></i> Accommodation:</strong> {{ $itinerary->accommodation_name }}
                                                </div>
                                                @endif
                                            </div>
                                        </div>
                                        @endforeach
                                    </div>
                                    
                                    <!-- What to Expect Section -->
                                    <div class="what-to-expect-section">
                                        <h3><i class="fas fa-lightbulb"></i> What to Expect</h3>
                                        <div class="expect-grid">
                                            <div class="expect-item">
                                                <i class="fas fa-clock"></i>
                                                <h4>Daily Schedule</h4>
                                                <p>Early morning game drives (6:00 AM - 10:00 AM), afternoon activities, and evening relaxation. Times may vary based on wildlife activity and weather conditions.</p>
                                            </div>
                                            <div class="expect-item">
                                                <i class="fas fa-utensils"></i>
                                                <h4>Meals</h4>
                                                <p>Enjoy delicious meals prepared with fresh, local ingredients. Special dietary requirements can be accommodated with advance notice.</p>
                                            </div>
                                            <div class="expect-item">
                                                <i class="fas fa-bed"></i>
                                                <h4>Accommodation</h4>
                                                <p>Comfortable accommodations ranging from luxury lodges to authentic tented camps, depending on your tour package.</p>
                                            </div>
                                            <div class="expect-item">
                                                <i class="fas fa-car"></i>
                                                <h4>Transportation</h4>
                                                <p>Travel in comfortable 4x4 safari vehicles with pop-up roofs for optimal wildlife viewing and photography.</p>
                                            </div>
                                        </div>
                                    </div>
                                @else
                                    <div class="no-itinerary">
                                        <i class="fas fa-route"></i>
                                        <p>Detailed itinerary coming soon. Please contact us for more information.</p>
                                        <a href="{{ route('contact') }}" class="btn btn-primary">Contact Us for Details</a>
                                    </div>
                                @endif
                            </div>
                        </div>

                            <!-- Included Tab -->
                        <div x-show="activeTab === 'included'" x-transition class="tab-content-panel">
                            <div class="content-section">
                                <h2 class="section-title">What's Included & Excluded</h2>
                                <p class="section-subtitle">Everything you need to know about what's covered in your tour package</p>
                                
                                <div class="included-excluded-grid">
                                    <div class="included-box">
                                        <div class="box-header">
                                            <i class="fas fa-check-circle"></i>
                                            <h3>What's Included</h3>
                                        </div>
                                        @if($tour->inclusions && is_array($tour->inclusions) && count($tour->inclusions) > 0)
                                        <ul class="included-list">
                                            @foreach($tour->inclusions as $inclusion)
                                            <li>
                                                <i class="fas fa-check"></i>
                                                <span>{{ $inclusion }}</span>
                                            </li>
                                            @endforeach
                                        </ul>
                                        @else
                                        <ul class="included-list">
                                            <li><i class="fas fa-check"></i> <span>Professional English-speaking guide</span></li>
                                            <li><i class="fas fa-check"></i> <span>All park entry fees</span></li>
                                            <li><i class="fas fa-check"></i> <span>Accommodation as specified</span></li>
                                            <li><i class="fas fa-check"></i> <span>All meals during tour</span></li>
                                            <li><i class="fas fa-check"></i> <span>Airport transfers</span></li>
                                            <li><i class="fas fa-check"></i> <span>Drinking water</span></li>
                                        </ul>
                                        @endif
                                    </div>
                                    <div class="excluded-box">
                                        <div class="box-header">
                                            <i class="fas fa-times-circle"></i>
                                            <h3>What's Not Included</h3>
                                        </div>
                                        @if($tour->exclusions && is_array($tour->exclusions) && count($tour->exclusions) > 0)
                                        <ul class="excluded-list">
                                            @foreach($tour->exclusions as $exclusion)
                                            <li>
                                                <i class="fas fa-times"></i>
                                                <span>{{ $exclusion }}</span>
                                            </li>
                                            @endforeach
                                        </ul>
                                        @else
                                        <ul class="excluded-list">
                                            <li><i class="fas fa-times"></i> <span>International flights</span></li>
                                            <li><i class="fas fa-times"></i> <span>Visa fees</span></li>
                                            <li><i class="fas fa-times"></i> <span>Travel insurance</span></li>
                                            <li><i class="fas fa-times"></i> <span>Tips and gratuities</span></li>
                                            <li><i class="fas fa-times"></i> <span>Alcoholic beverages</span></li>
                                            <li><i class="fas fa-times"></i> <span>Personal expenses</span></li>
                                        </ul>
                                        @endif
                                    </div>
                                </div>

                                <!-- Additional Info Section -->
                                <div class="additional-info-section">
                                    <h3><i class="fas fa-info-circle"></i> Additional Information</h3>
                                    <div class="info-grid">
                                        <div class="info-item">
                                            <strong><i class="fas fa-plane"></i> Flights:</strong>
                                            <span>International flights are not included. We can assist with flight bookings upon request.</span>
                                        </div>
                                        <div class="info-item">
                                            <strong><i class="fas fa-passport"></i> Visas:</strong>
                                            <span>Visa requirements vary by nationality. Please check with your local embassy.</span>
                                        </div>
                                        <div class="info-item">
                                            <strong><i class="fas fa-shield-alt"></i> Insurance:</strong>
                                            <span>Travel insurance is highly recommended and can be arranged through us.</span>
                                        </div>
                                        <div class="info-item">
                                            <strong><i class="fas fa-money-bill-wave"></i> Currency:</strong>
                                            <span>US Dollars and Tanzanian Shillings are accepted. Credit cards accepted at most locations.</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Highlights Tab -->
                        <div x-show="activeTab === 'highlights'" x-transition class="tab-content-panel">
                            <div class="content-section">
                                <h2 class="section-title">Tour Highlights</h2>
                                <p class="section-subtitle">Discover the amazing experiences that await you on this incredible journey</p>
                                
                                @if($tour->highlights && is_array($tour->highlights) && count($tour->highlights) > 0)
                                <div class="highlights-grid">
                                    @foreach($tour->highlights as $index => $highlight)
                                    <div class="highlight-item" data-aos="fade-up" data-aos-delay="{{ ($index % 3) * 100 }}">
                                        <div class="highlight-icon-wrapper">
                                            <i class="fas fa-star"></i>
                                        </div>
                                        <div class="highlight-content">
                                            <h4>{{ $highlight }}</h4>
                                            <p>Experience this incredible highlight during your {{ $tour->duration_days }}-day adventure.</p>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                                
                                <!-- Why Choose This Tour Section -->
                                <div class="why-choose-section">
                                    <h3><i class="fas fa-heart"></i> Why Choose This Tour?</h3>
                                    <div class="why-choose-grid">
                                        <div class="why-item">
                                            <i class="fas fa-certificate"></i>
                                            <h4>Expert Guides</h4>
                                            <p>Our professional guides have years of experience and deep knowledge of the area.</p>
                                        </div>
                                        <div class="why-item">
                                            <i class="fas fa-shield-alt"></i>
                                            <h4>Safety First</h4>
                                            <p>Your safety is our top priority with comprehensive safety measures in place.</p>
                                        </div>
                                        <div class="why-item">
                                            <i class="fas fa-leaf"></i>
                                            <h4>Sustainable Tourism</h4>
                                            <p>We support local communities and conservation efforts.</p>
                                        </div>
                                        <div class="why-item">
                                            <i class="fas fa-star"></i>
                                            <h4>Premium Experience</h4>
                                            <p>Carefully selected accommodations and experiences for maximum enjoyment.</p>
                                        </div>
                                    </div>
                                </div>
                                @else
                                <div class="highlights-grid">
                                    <div class="highlight-item" data-aos="fade-up">
                                        <div class="highlight-icon-wrapper">
                                            <i class="fas fa-camera"></i>
                                        </div>
                                        <div class="highlight-content">
                                            <h4>Stunning Photography Opportunities</h4>
                                            <p>Capture breathtaking moments throughout your journey.</p>
                                        </div>
                                    </div>
                                    <div class="highlight-item" data-aos="fade-up" data-aos-delay="100">
                                        <div class="highlight-icon-wrapper">
                                            <i class="fas fa-users"></i>
                                        </div>
                                        <div class="highlight-content">
                                            <h4>Expert Local Guides</h4>
                                            <p>Learn from experienced guides who know the area intimately.</p>
                                        </div>
                                    </div>
                                    <div class="highlight-item" data-aos="fade-up" data-aos-delay="200">
                                        <div class="highlight-icon-wrapper">
                                            <i class="fas fa-leaf"></i>
                                        </div>
                                        <div class="highlight-content">
                                            <h4>Eco-Friendly Experience</h4>
                                            <p>Travel responsibly while supporting local conservation efforts.</p>
                                        </div>
                                    </div>
                                </div>
                                @endif
                            </div>
                        </div>

                        <!-- Gallery Tab -->
                        <div x-show="activeTab === 'gallery'" x-transition class="tab-content-panel">
                            <div class="content-section">
                                <h2 class="section-title">Photo Gallery</h2>
                                <p class="section-subtitle">Get a glimpse of what awaits you on this incredible journey</p>
                                @if($tour->gallery_images && is_array($tour->gallery_images) && count($tour->gallery_images) > 0)
                                <div class="tour-gallery-grid">
                                    @foreach($tour->gallery_images as $index => $image)
                                    <div class="gallery-item" data-aos="fade-up" data-aos-delay="{{ ($index % 4) * 100 }}">
                                        <img src="{{ str_starts_with($image, 'http') ? $image : asset($image) }}" alt="{{ $tour->name }} - Image {{ $index + 1 }}">
                                        <div class="gallery-overlay">
                                            <i class="fas fa-search-plus"></i>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                                @else
                                <div class="tour-gallery-grid">
                                    <div class="gallery-item" data-aos="fade-up">
                                        <img src="{{ asset('images/safari_home-1.jpg') }}" alt="{{ $tour->name }}">
                                        <div class="gallery-overlay">
                                            <i class="fas fa-search-plus"></i>
                                        </div>
                                    </div>
                                    <div class="gallery-item" data-aos="fade-up" data-aos-delay="100">
                                        <img src="{{ asset('images/Serengetei-NP-2.jpeg') }}" alt="{{ $tour->name }}">
                                        <div class="gallery-overlay">
                                            <i class="fas fa-search-plus"></i>
                                        </div>
                                    </div>
                                    <div class="gallery-item" data-aos="fade-up" data-aos-delay="200">
                                        <img src="{{ asset('images/Tarangire-NP-1.jpeg') }}" alt="{{ $tour->name }}">
                                        <div class="gallery-overlay">
                                            <i class="fas fa-search-plus"></i>
                                        </div>
                                    </div>
                                    <div class="gallery-item" data-aos="fade-up" data-aos-delay="300">
                                        <img src="{{ asset('images/safari_home-1.jpg') }}" alt="{{ $tour->name }}">
                                        <div class="gallery-overlay">
                                            <i class="fas fa-search-plus"></i>
                                        </div>
                                    </div>
                                    <div class="gallery-item" data-aos="fade-up" data-aos-delay="400">
                                        <img src="{{ asset('images/Mara-River-3-1536x1024.jpg') }}" alt="{{ $tour->name }}">
                                        <div class="gallery-overlay">
                                            <i class="fas fa-search-plus"></i>
                                        </div>
                                    </div>
                                    <div class="gallery-item" data-aos="fade-up" data-aos-delay="500">
                                        <img src="{{ asset('images/hero-slider/kilimanjaro-climbing.jpg') }}" alt="{{ $tour->name }}">
                                        <div class="gallery-overlay">
                                            <i class="fas fa-search-plus"></i>
                                        </div>
                                    </div>
                                </div>
                                @endif
                                </div>
                            </div>

                             <!-- Reviews Tab -->
                        <div x-show="activeTab === 'reviews'" x-transition class="tab-content-panel">
                            <div class="content-section">
                                <h2 class="section-title">Traveler Reviews</h2>
                                @if($tour->rating)
                                <div class="tour-rating-summary">
                                    <div class="rating-score">
                                        <span class="score">{{ number_format($tour->rating, 1) }}</span>
                                        <div class="rating-stars">
                                            @for($i = 1; $i <= 5; $i++)
                                            <i class="fas fa-star {{ $i <= round($tour->rating) ? 'active' : '' }}"></i>
                                            @endfor
                                        </div>
                                        <p>Based on traveler reviews</p>
                                    </div>
                                </div>
                                @endif
                                
                                @if($tour->reviews && $tour->reviews->count() > 0)
                                    <div class="reviews-list">
                                        @foreach($tour->reviews->take(5) as $review)
                                        <div class="review-card" data-aos="fade-up">
                                            <div class="review-header">
                                                <div class="reviewer-info">
                                                    <strong>{{ $review->reviewer_name ?? 'Anonymous' }}</strong>
                                     <div class="review-stars">
                                                        @for($i = 1; $i <= 5; $i++)
                                                        <i class="fas fa-star {{ $i <= $review->rating ? 'active' : '' }}"></i>
                                                        @endfor
                                                    </div>
                                                </div>
                                                <span class="review-date">{{ $review->created_at->format('M Y') }}</span>
                                            </div>
                                            <p class="review-text">{{ $review->comment }}</p>
                                        </div>
                                        @endforeach
                                    </div>
                                @else
                                    <div class="no-reviews">
                                        <i class="fas fa-comments"></i>
                                        <p>No reviews yet. Be the first to review this tour!</p>
                                    </div>
                                @endif
                                 </div>
                             </div>
                        </div>

                        <!-- FAQ Tab -->
                        <div x-show="activeTab === 'faq'" x-transition class="tab-content-panel">
                            <div class="content-section">
                                <h2 class="section-title">Frequently Asked Questions</h2>
                                <div class="faq-list" x-data="{ openIndex: null }">
                                    <div class="faq-item" data-aos="fade-up">
                                        <button @click="openIndex = openIndex === 0 ? null : 0" class="faq-question">
                                            <span>What is included in the tour price?</span>
                                            <i class="fas fa-chevron-down" :class="{ 'rotate': openIndex === 0 }"></i>
                                        </button>
                                        <div x-show="openIndex === 0" x-transition class="faq-answer">
                                            <p>The tour price includes accommodation, meals as specified, professional guide, park entry fees, airport transfers, and all activities mentioned in the itinerary. Please check the "What's Included" tab for complete details.</p>
                                        </div>
                                    </div>
                                    <div class="faq-item" data-aos="fade-up" data-aos-delay="100">
                                        <button @click="openIndex = openIndex === 1 ? null : 1" class="faq-question">
                                            <span>What should I pack for this tour?</span>
                                            <i class="fas fa-chevron-down" :class="{ 'rotate': openIndex === 1 }"></i>
                                        </button>
                                        <div x-show="openIndex === 1" x-transition class="faq-answer">
                                            <p>We recommend packing comfortable clothing, sturdy walking shoes, a hat, sunscreen, insect repellent, a camera, and any personal medications. A detailed packing list will be provided upon booking confirmation.</p>
                                        </div>
                                    </div>
                                    <div class="faq-item" data-aos="fade-up" data-aos-delay="200">
                                        <button @click="openIndex = openIndex === 2 ? null : 2" class="faq-question">
                                            <span>What is the cancellation policy?</span>
                                            <i class="fas fa-chevron-down" :class="{ 'rotate': openIndex === 2 }"></i>
                                        </button>
                                        <div x-show="openIndex === 2" x-transition class="faq-answer">
                                            @if($tour->cancellation_policy)
                                            <p>{{ $tour->cancellation_policy }}</p>
                                            @else
                                            <p>Free cancellation is available up to 30 days before the tour start date. Cancellations made 15-30 days before will receive a 50% refund. Cancellations made less than 15 days before are non-refundable. Please contact us for more details.</p>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="faq-item" data-aos="fade-up" data-aos-delay="300">
                                        <button @click="openIndex = openIndex === 3 ? null : 3" class="faq-question">
                                            <span>Is travel insurance required?</span>
                                            <i class="fas fa-chevron-down" :class="{ 'rotate': openIndex === 3 }"></i>
                                        </button>
                                        <div x-show="openIndex === 3" x-transition class="faq-answer">
                                            <p>While not mandatory, we strongly recommend comprehensive travel insurance that covers medical emergencies, trip cancellation, and personal belongings. This ensures peace of mind throughout your journey.</p>
                                        </div>
                                    </div>
                                    <div class="faq-item" data-aos="fade-up" data-aos-delay="400">
                                        <button @click="openIndex = openIndex === 4 ? null : 4" class="faq-question">
                                            <span>What is the group size for this tour?</span>
                                            <i class="fas fa-chevron-down" :class="{ 'rotate': openIndex === 4 }"></i>
                                        </button>
                                        <div x-show="openIndex === 4" x-transition class="faq-answer">
                                            <p>The maximum group size is {{ $tour->max_group_size ?? 12 }} travelers. This ensures personalized attention from our guides and a more intimate experience. Private tours can also be arranged upon request.</p>
                                        </div>
                                    </div>
                                    <div class="faq-item" data-aos="fade-up" data-aos-delay="500">
                                        <button @click="openIndex = openIndex === 5 ? null : 5" class="faq-question">
                                            <span>Can I customize this tour?</span>
                                            <i class="fas fa-chevron-down" :class="{ 'rotate': openIndex === 5 }"></i>
                                        </button>
                                        <div x-show="openIndex === 5" x-transition class="faq-answer">
                                            <p>Yes! We offer customizable itineraries to suit your preferences, interests, and schedule. Contact us to discuss your requirements, and we'll create a personalized tour just for you.</p>
                                        </div>
                                     </div>
                                 </div>
                             </div>
                        </div>
                    </div>
                </div>

            <!-- Booking Sidebar -->
            <aside class="tour-sidebar">
                <div class="booking-card" data-aos="fade-left">
                    <div class="booking-header">
                        <div class="price-display">
                            <span class="price-label">Starting from</span>
                            <span class="price-amount">${{ number_format($tour->starting_price ?? $tour->price) }}</span>
                            <span class="price-note">per person</span>
                        </div>
                        @if($tour->rating)
                        <div class="rating-display">
                            <div class="rating-stars-small">
                                @for($i = 1; $i <= 5; $i++)
                                <i class="fas fa-star {{ $i <= round($tour->rating) ? 'active' : '' }}"></i>
                                @endfor
                            </div>
                            <span>{{ number_format($tour->rating, 1) }} ({{ $tour->reviews->count() ?? 0 }} reviews)</span>
                        </div>
                        @endif
                        <div class="tour-badges">
                            @if($tour->is_featured)
                            <span class="badge-featured"><i class="fas fa-star"></i> Featured</span>
                            @endif
                            <span class="badge-popular"><i class="fas fa-fire"></i> Popular</span>
                        </div>
                    </div>
                    
                    <form action="{{ route('booking') }}" method="GET" class="booking-form">
                        <input type="hidden" name="tour" value="{{ $tour->slug }}" id="tour-slug-input">
                        
                        <div class="form-group">
                            <label><i class="fas fa-calendar-alt"></i> Select Date</label>
                            <input type="date" name="date" class="form-control" min="{{ date('Y-m-d') }}" required>
                        </div>
                        
                        <div class="form-group">
                            <label><i class="fas fa-users"></i> Number of Travelers</label>
                            <div class="travelers-selector">
                                <button type="button" class="btn-counter" onclick="decreaseTravelers()">-</button>
                                <input type="number" name="travelers" id="travelersCount" value="2" min="1" max="{{ $tour->max_group_size ?? 12 }}" class="form-control" readonly>
                                <button type="button" class="btn-counter" onclick="increaseTravelers()">+</button>
                            </div>
                        </div>
                        
                        <button type="submit" class="btn-booking-primary">
                            <i class="fas fa-calendar-check"></i> Book Now
                        </button>
                        
                        <div class="booking-features">
                            <div class="feature-item">
                                <i class="fas fa-shield-alt"></i>
                                <span>Free Cancellation</span>
                            </div>
                            <div class="feature-item">
                                <i class="fas fa-clock"></i>
                                <span>Instant Confirmation</span>
                            </div>
                            <div class="feature-item">
                                <i class="fas fa-headset"></i>
                                <span>24/7 Support</span>
                            </div>
                        </div>
                    </form>
                    
                    <div class="booking-footer">
                        <a href="{{ route('contact') }}" class="link-contact">
                            <i class="fas fa-question-circle"></i> Have Questions? Contact Us
                        </a>
                        <a href="https://wa.me/255789456123?text=Hi,%20I'm%20interested%20in%20{{ urlencode($tour->name) }}" target="_blank" class="link-whatsapp">
                            <i class="fab fa-whatsapp"></i> Chat on WhatsApp
                        </a>
                    </div>
                </div>
            </aside>
            </div>
        </div>
    </section>

<!-- Map & Location Section -->
@if($tour->start_location || $tour->end_location)
<section class="tour-location-section">
    <div class="container">
        <div class="section-header" data-aos="fade-up">
            <h2 class="section-title">Tour Location</h2>
            <p class="section-subtitle">Start and end points for your adventure</p>
        </div>
        <div class="location-info-grid">
            @if($tour->start_location)
            <div class="location-card" data-aos="fade-up">
                <div class="location-icon">
                    <i class="fas fa-map-marker-alt"></i>
                </div>
                <h3>Start Location</h3>
                <p>{{ $tour->start_location }}</p>
            </div>
            @endif
            @if($tour->end_location)
            <div class="location-card" data-aos="fade-up" data-aos-delay="100">
                <div class="location-icon">
                    <i class="fas fa-flag-checkered"></i>
                </div>
                <h3>End Location</h3>
                <p>{{ $tour->end_location }}</p>
            </div>
            @endif
            @if($tour->destination)
            <div class="location-card" data-aos="fade-up" data-aos-delay="200">
                <div class="location-icon">
                    <i class="fas fa-compass"></i>
                </div>
                <h3>Destination</h3>
                <p>{{ $tour->destination->name }}</p>
                <a href="{{ route('destinations.show', $tour->destination->slug) }}" class="location-link">Explore Destination <i class="fas fa-arrow-right"></i></a>
            </div>
            @endif
        </div>
    </div>
</section>
@endif

<!-- Related Tours Section -->
@if($relatedTours && $relatedTours->count() > 0)
<section class="related-tours-section">
    <div class="container">
        <div class="section-header" data-aos="fade-up">
            <span class="section-badge">More Adventures</span>
            <h2 class="section-title">You Might Also Like</h2>
            <p class="section-subtitle">Explore more amazing tours in Tanzania</p>
        </div>
        <div class="related-tours-grid">
            @foreach($relatedTours as $index => $relatedTour)
            <div class="related-tour-card" data-aos="fade-up" data-aos-delay="{{ ($index % 3) * 100 }}">
                <a href="{{ route('tours.show', $relatedTour->slug) }}">
                    <div class="tour-image">
                        <img src="{{ $relatedTour->image_url ? (str_starts_with($relatedTour->image_url, 'http') ? $relatedTour->image_url : asset($relatedTour->image_url)) : asset('images/safari_home-1.jpg') }}" alt="{{ $relatedTour->name }}">
                        <div class="tour-overlay">
                            <span class="tour-badge">View Tour</span>
                        </div>
                    </div>
                    <div class="tour-info">
                        <h3>{{ $relatedTour->name }}</h3>
                        <div class="tour-meta">
                            <span><i class="fas fa-clock"></i> {{ $relatedTour->duration_days }} Days</span>
                            <span><i class="fas fa-dollar-sign"></i> From ${{ number_format($relatedTour->starting_price ?? $relatedTour->price) }}</span>
                        </div>
                        @if($relatedTour->rating)
                        <div class="tour-rating">
                            <i class="fas fa-star"></i> {{ number_format($relatedTour->rating, 1) }}
                        </div>
                        @endif
                    </div>
                </a>
            </div>
            @endforeach
            </div>
        </div>
    </section>
@endif

@endsection

@push('scripts')
<script>
function increaseTravelers() {
    const input = document.getElementById('travelersCount');
    const max = parseInt(input.getAttribute('max'));
    const current = parseInt(input.value);
    if (current < max) {
        input.value = current + 1;
    }
}

function decreaseTravelers() {
    const input = document.getElementById('travelersCount');
    const current = parseInt(input.value);
    if (current > 1) {
        input.value = current - 1;
    }
}
</script>
@endpush

@push('styles')
<link rel="stylesheet" href="{{ asset('css/tour-show.css') }}">
@endpush
