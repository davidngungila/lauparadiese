@extends('layouts.app')

@php
    // Use HomepageDestination if available, otherwise use regular Destination
    $homepageDest = $homepageDestination ?? null;
    $dest = $destination;
    $isHomepageDest = $homepageDest !== null;
    
    // Get destination data
    $destName = $isHomepageDest ? $homepageDest->name : $dest->name;
    $destDescription = $isHomepageDest ? ($homepageDest->full_description ?? $homepageDest->short_description) : ($dest->description ?? '');
    // Get destination image with proper handling for relative paths
    $destImage = $destinationImage ?? ($isHomepageDest ? ($homepageDest->featured_image_display_url ?? ($homepageDest->featured_image_url ? (str_starts_with($homepageDest->featured_image_url, 'http://') || str_starts_with($homepageDest->featured_image_url, 'https://') ? $homepageDest->featured_image_url : asset($homepageDest->featured_image_url)) : asset('images/safari_home-1.jpg'))) : ($dest->image_url ? (str_starts_with($dest->image_url, 'http://') || str_starts_with($dest->image_url, 'https://') ? $dest->image_url : asset($dest->image_url)) : asset('images/safari_home-1.jpg')));
    $destLocation = $isHomepageDest ? $homepageDest->location : 'Tanzania';
    $destCategory = $isHomepageDest ? $homepageDest->category : null;
    $destRating = $isHomepageDest ? $homepageDest->rating : null;
    $destPrice = $isHomepageDest ? $homepageDest->price_display : null;
    $destDuration = $isHomepageDest ? $homepageDest->duration : null;
    $galleryImages = $galleryImages ?? ($isHomepageDest ? ($homepageDest->image_gallery ?? []) : []);
@endphp

@section('title', $destName . ' - Lau Paradise Adventures')
@section('description', $destDescription ? Str::limit(strip_tags($destDescription), 160) : 'Discover ' . $destName . ' with Lau Paradise Adventures')

@section('content')

<!-- Destination Hero Section -->
<section class="destination-hero-section" style="background-image: url('{{ $destImage }}');">
    <div class="destination-hero-overlay"></div>
        <div class="container">
        <div class="destination-hero-content" data-aos="fade-up">
            <nav class="destination-breadcrumb">
                <a href="{{ route('home') }}"><i class="fas fa-home"></i> Home</a>
                <span>/</span>
                <a href="{{ route('destinations.index') }}">Destinations</a>
            </nav>
            <h1 class="destination-hero-title">{{ $destName }}</h1>
            <p class="destination-hero-subtitle">{{ $destDescription ? Str::limit(strip_tags($destDescription), 200) : 'Discover the wonders of ' . $destName . ' with expertly crafted tours and unforgettable experiences.' }}</p>
        </div>
        </div>
    </section>

<!-- Quick Stats Bar -->
<section class="destination-stats-bar">
        <div class="container">
        <div class="stats-grid">
            @if($destDuration)
            <div class="stat-item" data-aos="fade-up">
                <i class="fas fa-calendar-alt"></i>
                <div>
                    <strong>Duration</strong>
                    <span>{{ $destDuration }}</span>
                </div>
            </div>
            @endif
            <div class="stat-item" data-aos="fade-up" data-aos-delay="100">
                <i class="fas fa-map-marker-alt"></i>
                <div>
                    <strong>Location</strong>
                    <span>{{ $destLocation }}</span>
                </div>
            </div>
            @if($destCategory)
            <div class="stat-item" data-aos="fade-up" data-aos-delay="200">
                <i class="fas fa-compass"></i>
                <div>
                    <strong>Category</strong>
                    <span>{{ $destCategory }}</span>
                </div>
            </div>
            @endif
            @if($destRating)
            <div class="stat-item" data-aos="fade-up" data-aos-delay="200">
                <i class="fas fa-star"></i>
                <div>
                    <strong>Rating</strong>
                    <span>{{ number_format($destRating, 1) }}/5</span>
                </div>
            </div>
            @endif
            <div class="stat-item" data-aos="fade-up" data-aos-delay="300">
                <i class="fas fa-route"></i>
                <div>
                    <strong>{{ $tours->count() }} Tours</strong>
                    <span>Available</span>
                </div>
            </div>
            @if($destPrice)
            <div class="stat-item" data-aos="fade-up" data-aos-delay="400">
                <i class="fas fa-dollar-sign"></i>
                <div>
                    <strong>Price</strong>
                    <span>{{ $destPrice }}</span>
                </div>
            </div>
            @endif
        </div>
        </div>
    </section>

<!-- Main Content Section -->
<section class="destination-main-section">
        <div class="container">
        <div class="destination-layout">
            <!-- Main Content -->
            <div class="destination-content-main">
                <!-- Tabs Navigation -->
                <div class="destination-tabs-wrapper" x-data="{ activeTab: 'overview' }">
                    <div class="destination-tabs-nav">
                        <button @click="activeTab = 'overview'" :class="{ 'active': activeTab === 'overview' }" class="tab-btn">
                            <i class="fas fa-info-circle"></i> Overview
                        </button>
                        <button @click="activeTab = 'highlights'" :class="{ 'active': activeTab === 'highlights' }" class="tab-btn">
                            <i class="fas fa-star"></i> Highlights
                        </button>
                        <button @click="activeTab = 'wildlife'" :class="{ 'active': activeTab === 'wildlife' }" class="tab-btn">
                            <i class="fas fa-paw"></i> Wildlife
                        </button>
                        <button @click="activeTab = 'best-time'" :class="{ 'active': activeTab === 'best-time' }" class="tab-btn">
                            <i class="fas fa-calendar-check"></i> Best Time to Visit
                        </button>
                        <button @click="activeTab = 'gallery'" :class="{ 'active': activeTab === 'gallery' }" class="tab-btn">
                            <i class="fas fa-images"></i> Gallery
                        </button>
                    </div>

                    <!-- Tab Content -->
                    <div class="destination-tabs-content">
                        <!-- Overview Tab -->
                        <div x-show="activeTab === 'overview'" x-transition class="tab-content">
                            <div class="content-block">
                                <h2 class="content-title">About {{ $destName }}</h2>
                                <div class="content-text">
                                    @if($isHomepageDest && $homepageDest->full_description)
                                        {!! nl2br(e($homepageDest->full_description)) !!}
                                    @elseif($isHomepageDest && $homepageDest->short_description)
                                        <p>{{ $homepageDest->short_description }}</p>
                                    @else
                                        {!! nl2br(e($destDescription ?: 'Experience the breathtaking beauty and natural wonders of ' . $destName . '. This incredible destination offers a unique blend of wildlife, landscapes, and cultural experiences that will create memories to last a lifetime.')) !!}
                                    @endif
                                </div>
                                
                                @if($isHomepageDest && $homepageDest->short_description && $homepageDest->full_description)
                                <div class="mt-4">
                                    <h3 class="content-subtitle">Quick Overview</h3>
                                    <p class="content-text">{{ $homepageDest->short_description }}</p>
                                </div>
                                @endif
                                
                                <h3 class="content-subtitle">Why Visit {{ $destName }}?</h3>
                                <div class="features-list">
                                    <div class="feature-item">
                                        <i class="fas fa-check-circle"></i>
                                        <div>
                                            <h4>Unforgettable Experiences</h4>
                                            <p>Discover unique adventures and experiences that can only be found in {{ $destName }}.</p>
                                        </div>
                                    </div>
                                    <div class="feature-item">
                                        <i class="fas fa-check-circle"></i>
                                        <div>
                                            <h4>Expert Guides</h4>
                                            <p>Our local guides have extensive knowledge and passion for {{ $destName }}.</p>
                                        </div>
                                    </div>
                                    <div class="feature-item">
                                        <i class="fas fa-check-circle"></i>
                                        <div>
                                            <h4>Diverse Activities</h4>
                                            <p>From wildlife viewing to cultural experiences, there's something for everyone.</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Highlights Tab -->
                        <div x-show="activeTab === 'highlights'" x-transition class="tab-content">
                            <div class="content-block">
                                <h2 class="content-title">Destination Highlights</h2>
                                <div class="highlights-grid">
                                    <div class="highlight-card" data-aos="fade-up">
                                        <div class="highlight-icon"><i class="fas fa-mountain"></i></div>
                                        <h3>Stunning Landscapes</h3>
                                        <p>Experience breathtaking natural beauty and diverse terrain that makes {{ $destName }} truly special.</p>
                                    </div>
                                    <div class="highlight-card" data-aos="fade-up" data-aos-delay="100">
                                        <div class="highlight-icon"><i class="fas fa-camera"></i></div>
                                        <h3>Photography Paradise</h3>
                                        <p>Capture incredible moments with endless opportunities for stunning wildlife and landscape photography.</p>
                                    </div>
                                    <div class="highlight-card" data-aos="fade-up" data-aos-delay="200">
                                        <div class="highlight-icon"><i class="fas fa-users"></i></div>
                                        <h3>Cultural Richness</h3>
                                        <p>Immerse yourself in local cultures and traditions that have been preserved for generations.</p>
                                    </div>
                                    <div class="highlight-card" data-aos="fade-up" data-aos-delay="300">
                                        <div class="highlight-icon"><i class="fas fa-leaf"></i></div>
                                        <h3>Conservation Focus</h3>
                                        <p>Support conservation efforts while experiencing one of the world's most important natural areas.</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Wildlife Tab -->
                        <div x-show="activeTab === 'wildlife'" x-transition class="tab-content">
                            <div class="content-block">
                                <h2 class="content-title">Wildlife & Nature</h2>
                                <div class="wildlife-content">
                                    <div class="wildlife-intro">
                                        <p>{{ $destName }} is home to an incredible diversity of wildlife and natural wonders. From the Big Five to rare bird species, this destination offers unparalleled opportunities for wildlife viewing and nature appreciation.</p>
                                    </div>
                                    <div class="wildlife-grid">
                                        <div class="wildlife-item">
                                            <i class="fas fa-lion"></i>
                                            <h4>Big Five</h4>
                                            <p>Lion, Leopard, Elephant, Buffalo, Rhino</p>
                                        </div>
                                        <div class="wildlife-item">
                                            <i class="fas fa-dove"></i>
                                            <h4>Bird Species</h4>
                                            <p>500+ species including migratory birds</p>
                                        </div>
                                        <div class="wildlife-item">
                                            <i class="fas fa-tree"></i>
                                            <h4>Ecosystems</h4>
                                            <p>Diverse habitats supporting unique wildlife</p>
                                        </div>
                                        <div class="wildlife-item">
                                            <i class="fas fa-binoculars"></i>
                                            <h4>Viewing</h4>
                                            <p>Excellent game viewing opportunities</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Best Time to Visit Tab -->
                        <div x-show="activeTab === 'best-time'" x-transition class="tab-content">
                            <div class="content-block">
                                <h2 class="content-title">Best Time to Visit</h2>
                                <div class="season-grid">
                                    <div class="season-card" data-aos="fade-up">
                                        <div class="season-icon"><i class="fas fa-sun"></i></div>
                                        <h3>Dry Season</h3>
                                        <p class="season-period">June - October</p>
                                        <p class="season-description">Ideal weather conditions with clear skies and excellent wildlife viewing. The dry season offers the best game viewing opportunities as animals gather around water sources.</p>
                                        <ul class="season-features">
                                            <li><i class="fas fa-check"></i> Clear skies</li>
                                            <li><i class="fas fa-check"></i> Best wildlife viewing</li>
                                            <li><i class="fas fa-check"></i> Comfortable temperatures</li>
                                        </ul>
                                    </div>
                                    <div class="season-card" data-aos="fade-up" data-aos-delay="100">
                                        <div class="season-icon"><i class="fas fa-cloud-rain"></i></div>
                                        <h3>Green Season</h3>
                                        <p class="season-period">November - May</p>
                                        <p class="season-description">Lush landscapes and fewer crowds characterize the green season. While there may be occasional rain, this period offers beautiful scenery and great value.</p>
                                        <ul class="season-features">
                                            <li><i class="fas fa-check"></i> Lush landscapes</li>
                                            <li><i class="fas fa-check"></i> Fewer crowds</li>
                                            <li><i class="fas fa-check"></i> Great value</li>
                    </ul>
                </div>
                                </div>
                            </div>
                        </div>

                        <!-- Gallery Tab -->
                        <div x-show="activeTab === 'gallery'" x-transition class="tab-content">
                            <div class="content-block">
                                <h2 class="content-title">Photo Gallery</h2>
                                <div class="gallery-grid">
                                    @if(!empty($galleryImages) && is_array($galleryImages) && count($galleryImages) > 0)
                                        @foreach($galleryImages as $index => $galleryImage)
                                        @php
                                            $imageUrl = (str_starts_with($galleryImage, 'http://') || str_starts_with($galleryImage, 'https://')) ? $galleryImage : asset($galleryImage);
                                        @endphp
                                        <div class="gallery-item" data-aos="fade-up" data-aos-delay="{{ $index * 100 }}">
                                            <img src="{{ $imageUrl }}" alt="{{ $destName }}" loading="lazy" onerror="this.src='{{ asset('images/safari_home-1.jpg') }}'">
                                            <div class="gallery-overlay" onclick="openImageModal('{{ $imageUrl }}', '{{ $destName }}')">
                                                <i class="fas fa-search-plus"></i>
                                            </div>
                                        </div>
                                        @endforeach
                                    @else
                                        <div class="col-12 text-center py-5">
                                            <i class="fas fa-images" style="font-size: 3rem; color: #ccc; margin-bottom: 1rem;"></i>
                                            <p class="text-muted">No gallery images available for this destination.</p>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="destination-sidebar">
                <!-- Quick Book Card -->
                <div class="sidebar-card" data-aos="fade-left">
                    <h3 class="sidebar-card-title">Plan Your Visit</h3>
                    <p class="sidebar-card-text">Ready to explore {{ $destName }}? Let us help you plan the perfect adventure.</p>
                    @if($destPrice)
                    <div class="mb-3">
                        <strong class="text-primary" style="font-size: 1.5rem;">{{ $destPrice }}</strong>
                    </div>
                    @endif
                    <a href="{{ route('contact') }}" class="sidebar-btn btn-primary">
                        <i class="fas fa-envelope"></i> Contact Us
                    </a>
                    <a href="{{ route('booking') }}" class="sidebar-btn btn-secondary">
                        <i class="fas fa-calendar-check"></i> Book Now
                    </a>
                </div>

                <!-- Quick Facts -->
                <div class="sidebar-card" data-aos="fade-left" data-aos-delay="100">
                    <h3 class="sidebar-card-title">Quick Facts</h3>
                    <div class="facts-list">
                        @if($destLocation)
                        <div class="fact-item">
                            <i class="fas fa-map"></i>
                            <div>
                                <strong>Location</strong>
                                <span>{{ $destLocation }}</span>
                            </div>
                        </div>
                        @endif
                        @if($destDuration)
                        <div class="fact-item">
                            <i class="fas fa-clock"></i>
                            <div>
                                <strong>Duration</strong>
                                <span>{{ $destDuration }}</span>
                            </div>
                        </div>
                        @endif
                        @if($destCategory)
                        <div class="fact-item">
                            <i class="fas fa-compass"></i>
                            <div>
                                <strong>Category</strong>
                                <span>{{ $destCategory }}</span>
                            </div>
                        </div>
                        @endif
                        @if($destRating)
                        <div class="fact-item">
                            <i class="fas fa-star"></i>
                            <div>
                                <strong>Rating</strong>
                                <span>{{ number_format($destRating, 1) }}/5.0</span>
                            </div>
                        </div>
                        @endif
                        <div class="fact-item">
                            <i class="fas fa-route"></i>
                            <div>
                                <strong>Tours Available</strong>
                                <span>{{ $tours->count() }} tours</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Related Destinations -->
                @if(isset($relatedDestinations) && $relatedDestinations->count() > 0)
                <div class="sidebar-card" data-aos="fade-left" data-aos-delay="200">
                    <h3 class="sidebar-card-title">Explore More</h3>
                    <div class="related-destinations">
                        @foreach($relatedDestinations as $related)
                        @php
                            $relatedImage = $isHomepageDest && method_exists($related, 'featured_image_display_url') 
                                ? ($related->featured_image_display_url ?? ($related->featured_image_url ? (str_starts_with($related->featured_image_url, 'http://') || str_starts_with($related->featured_image_url, 'https://') ? $related->featured_image_url : asset($related->featured_image_url)) : asset('images/safari_home-1.jpg')))
                                : ($related->image_url ? (str_starts_with($related->image_url, 'http://') || str_starts_with($related->image_url, 'https://') ? $related->image_url : asset($related->image_url)) : asset('images/safari_home-1.jpg'));
                            $relatedSlug = $isHomepageDest ? ($related->slug ?? Str::slug($related->name)) : $related->slug;
                        @endphp
                        <a href="{{ route('destinations.show', $relatedSlug) }}" class="related-destination-item">
                            <img src="{{ $relatedImage }}" alt="{{ $related->name }}" loading="lazy" onerror="this.src='{{ asset('images/safari_home-1.jpg') }}'">
                            <div class="related-destination-info">
                                <h4>{{ $related->name }}</h4>
                                <span>Explore <i class="fas fa-arrow-right"></i></span>
                            </div>
                        </a>
                        @endforeach
                    </div>
                </div>
                @endif
                </div>
            </div>
        </div>
    </section>

<!-- Available Tours Section -->
@if($tours->count() > 0)
<section class="destination-tours-section">
        <div class="container">
            <div class="section-header" data-aos="fade-up">
            <span class="section-badge">Tours in {{ $destName }}</span>
            <h2 class="section-title">Available Tours</h2>
            <p class="section-subtitle">Choose from our expertly crafted tours to experience the very best of {{ $destName }}.</p>
            </div>
            <div class="tours-grid">
            @foreach($tours as $index => $tour)
            <div class="tour-card" data-aos="fade-up" data-aos-delay="{{ ($index % 3) * 100 }}">
                    <div class="tour-card-image">
                        <a href="{{ route('tours.show', $tour['slug']) }}">
                        <img src="{{ $tour['image'] }}" alt="{{ $tour['name'] }}">
                    </a>
                    @if($tour['is_featured'])
                    <div class="tour-card-badge featured"><i class="fas fa-star"></i> Featured</div>
                    @endif
                    <div class="tour-card-price">From <span>${{ number_format($tour['starting_price']) }}</span></div>
                    </div>
                    <div class="tour-card-content">
                        <div class="tour-card-meta">
                        <span><i class="fas fa-clock"></i> {{ $tour['duration_days'] }} Days</span>
                        <span><i class="fas fa-star"></i> {{ number_format($tour['rating'], 1) }}</span>
                        </div>
                    <h3 class="tour-card-title"><a href="{{ route('tours.show', $tour['slug']) }}">{{ $tour['name'] }}</a></h3>
                    <p class="tour-card-description">{{ $tour['description'] }}</p>
                    <a href="{{ route('tours.show', $tour['slug']) }}" class="tour-card-btn">
                        View Details <i class="fas fa-arrow-right"></i>
                    </a>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </section>
@endif

<!-- CTA Section -->
<section class="destination-cta-section">
    <div class="container">
        <div class="cta-content" data-aos="fade-up">
            <h2 class="cta-title">Ready to Explore {{ $destName }}?</h2>
            <p class="cta-text">Contact our Tanzania experts today to plan your perfect adventure. We'll create a customized itinerary just for you.</p>
            <div class="cta-buttons">
                <a href="{{ route('contact') }}" class="cta-btn btn-primary">
                    <i class="fas fa-envelope"></i> Contact Us
                </a>
                <a href="{{ route('tours.index') }}" class="cta-btn btn-secondary">
                    <i class="fas fa-compass"></i> View All Tours
                </a>
            </div>
            </div>
        </div>
    </section>

@push('styles')
<link rel="stylesheet" href="{{ asset('css/destination-show.css') }}">
<style>
/* Image Modal Styles */
.image-modal {
    display: none;
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    z-index: 9999;
    align-items: center;
    justify-content: center;
}
.image-modal-overlay {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.9);
    cursor: pointer;
}
.image-modal-content {
    position: relative;
    max-width: 90%;
    max-height: 90%;
    z-index: 10000;
}
.image-modal-content img {
    max-width: 100%;
    max-height: 90vh;
    object-fit: contain;
    border-radius: 8px;
}
.image-modal-close {
    position: absolute;
    top: -40px;
    right: 0;
    background: white;
    border: none;
    font-size: 2rem;
    width: 40px;
    height: 40px;
    border-radius: 50%;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    line-height: 1;
}
</style>
@endpush

@push('scripts')
<script>
    function openImageModal(imageUrl, imageAlt) {
        let modal = document.getElementById('imageModal');
        if (!modal) {
            modal = document.createElement('div');
            modal.id = 'imageModal';
            modal.className = 'image-modal';
            modal.innerHTML = `
                <div class="image-modal-overlay" onclick="closeImageModal()"></div>
                <div class="image-modal-content">
                    <button class="image-modal-close" onclick="closeImageModal()">&times;</button>
                    <img id="modalImage" src="" alt="">
                </div>
            `;
            document.body.appendChild(modal);
        }
        document.getElementById('modalImage').src = imageUrl;
        document.getElementById('modalImage').alt = imageAlt;
        modal.style.display = 'flex';
        document.body.style.overflow = 'hidden';
    }
    
    function closeImageModal() {
        const modal = document.getElementById('imageModal');
        if (modal) {
            modal.style.display = 'none';
            document.body.style.overflow = '';
        }
    }
    
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeImageModal();
        }
    });
</script>
@endpush

@endsection
