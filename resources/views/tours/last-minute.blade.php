@extends('layouts.app')

@section('title', 'Last-Minute Deals - Lau Paradise Adventures')
@section('description', 'Find incredible last-minute deals on our most popular tours. Spontaneous adventures at unbeatable prices.')

@section('content')

<!-- Hero Section -->
<section class="page-hero-section" style="background-image: url('{{ asset('images/safari_home-1.jpg') }}');">
    <div class="page-hero-overlay"></div>
        <div class="container">
        <div class="page-hero-content" data-aos="fade-up">
            <span class="hero-badge"><i class="fas fa-fire"></i> Special Offers</span>
            <h1 class="page-hero-title">Last-Minute Deals</h1>
            <p class="page-hero-subtitle">Unmissable prices on unforgettable adventures. Book now before they're gone!</p>
        </div>
        </div>
    </section>

    <!-- Why Book Now Section -->
<section class="content-section">
        <div class="container">
            <div class="features-grid">
            <div class="feature-item" data-aos="fade-up">
                    <div class="feature-icon"><i class="fas fa-dollar-sign"></i></div>
                    <h3 class="feature-title">Unbeatable Prices</h3>
                <p class="feature-description">Get the absolute best value on our award-winning tours.</p>
                </div>
            <div class="feature-item" data-aos="fade-up" data-aos-delay="100">
                    <div class="feature-icon"><i class="fas fa-bolt"></i></div>
                    <h3 class="feature-title">Spontaneous Adventure</h3>
                <p class="feature-description">Ready when you are. Book today and travel within the next few weeks.</p>
                </div>
            <div class="feature-item" data-aos="fade-up" data-aos-delay="200">
                    <div class="feature-icon"><i class="fas fa-check-circle"></i></div>
                    <h3 class="feature-title">Guaranteed Quality</h3>
                <p class="feature-description">Last-minute doesn't mean less. Expect the same expert guides and service.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Deals Grid Section -->
<section class="featured-tours-section">
        <div class="container">
        <div class="section-header" data-aos="fade-up">
            <span class="section-badge">Limited Time Offers</span>
            <h2 class="section-title">Last-Minute Tour Deals</h2>
            <p class="section-subtitle">Don't miss these incredible savings on our most popular Tanzania tours.</p>
        </div>
            <div class="tours-grid">
            @if(isset($deals) && count($deals) > 0)
            @foreach ($deals as $index => $tour)
            <div class="tour-card" data-aos="fade-up" data-aos-delay="{{ $index * 100 }}">
                    <div class="tour-card-image">
                    <img src="{{ $tour['image'] }}" alt="{{ $tour['name'] }}">
                    <div class="tour-card-badge">Special Deal</div>
                    <div class="discount-badge">Save {{ round((($tour['price'] - $tour['discount_price']) / $tour['price']) * 100) }}%</div>
                    </div>
                    <div class="tour-card-content">
                        <div class="tour-card-meta">
                        <span class="tour-duration"><i class="fas fa-clock"></i> {{ $tour['duration_days'] ?? $tour['duration'] ?? 0 }} Days</span>
                        <span class="tour-rating"><i class="fas fa-star"></i> {{ $tour['rating'] }}</span>
                        </div>
                    <h3 class="tour-card-title">{{ $tour['name'] }}</h3>
                    <p class="tour-card-location"><i class="fas fa-map-marker-alt"></i> {{ $tour['destination'] }}</p>
                    <div class="tour-card-footer">
                        <div class="tour-price">
                            <span class="price-old">${{ number_format($tour['price']) }}</span>
                            <span class="price-amount">${{ number_format($tour['discount_price']) }}</span>
                            <span class="price-note">per person</span>
                        </div>
                        <a href="{{ route('booking') }}?tour={{ $tour['slug'] }}" class="btn-secondary">Book Now</a>
                    </div>
                    </div>
                </div>
                @endforeach
            @else
            <div class="no-tours-message">
                <i class="fas fa-fire"></i>
                <h3>No last-minute deals available</h3>
                <p>Check back soon for special offers!</p>
                <a href="{{ route('tours.index') }}" class="btn-primary">View All Tours</a>
            </div>
            @endif
            </div>
        </div>
    </section>

@endsection

@push('styles')
<style>
.page-hero-section {
    position: relative;
    padding: 150px 0 100px;
    background-size: cover;
    background-position: center;
    background-attachment: fixed;
    color: var(--white);
    text-align: center;
    min-height: 400px;
    display: flex;
    align-items: center;
}
.page-hero-overlay {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: linear-gradient(135deg, rgba(26, 77, 58, 0.85) 0%, rgba(0, 0, 0, 0.7) 100%);
}
.page-hero-content {
    position: relative;
    z-index: 2;
    max-width: 900px;
    margin: 0 auto;
}
.hero-badge {
    display: inline-block;
    padding: 8px 20px;
    background: rgba(255, 107, 53, 0.95);
    backdrop-filter: blur(10px);
    border-radius: 30px;
    font-size: 14px;
    font-weight: 600;
    margin-bottom: 20px;
    border: 1px solid rgba(255, 255, 255, 0.3);
}
.page-hero-title {
    font-size: 3.5rem;
    font-weight: 800;
    margin-bottom: 20px;
    text-shadow: 2px 2px 10px rgba(0,0,0,0.5);
}
.page-hero-subtitle {
    font-size: 1.3rem;
    opacity: 0.95;
    line-height: 1.6;
}
.content-section {
    padding: 80px 0;
    background: var(--white);
}
.discount-badge {
    position: absolute;
    top: 15px;
    right: 15px;
    background: #FF6B35;
    color: var(--white);
    padding: 8px 16px;
    border-radius: 20px;
    font-size: 0.9rem;
    font-weight: 700;
    z-index: 3;
}
.price-old {
    font-size: 1rem;
    color: var(--gray);
    text-decoration: line-through;
    display: block;
}
@media (max-width: 768px) {
    .page-hero-title {
        font-size: 2.5rem;
    }
}
</style>
@endpush
