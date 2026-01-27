@extends('layouts.app')

@section('title', 'Tanzania Tours & Safaris - Lau Paradise Adventures')
@section('description', 'Browse our complete collection of Tanzania tours. From Kilimanjaro climbs to Serengeti safaris and Zanzibar beaches, find your perfect Tanzania adventure with Lau Paradise Adventures.')
@section('body_class', 'tours-index-page')
@section('page_identifier', 'tours')

@section('content')

    <!-- ============================================ -->
    <!-- Hero Header Section -->
    <!-- ============================================ -->
    <section class="tours-hero-section" style="background-image: url('{{ asset('images/safari_home-1.jpg') }}');">
        <div class="tours-hero-overlay"></div>
        <div class="container">
            <div class="tours-hero-content" data-aos="fade-up">
                <span class="tours-hero-badge"><i class="fas fa-compass"></i> Explore Tanzania</span>
                <h1 class="tours-hero-title">Discover Your Perfect Tanzania Adventure</h1>
                <p class="tours-hero-subtitle">From Serengeti safaris to Kilimanjaro climbs and Zanzibar beaches - find your dream Tanzania tour with expert local guides</p>
            </div>
        </div>
    </section>

    <!-- ============================================ -->
    <!-- Statistics Section -->
    <!-- ============================================ -->
    <section class="tours-stats-section">
        <div class="container">
            <div class="stats-grid" data-aos="fade-up">
                <div class="stat-item">
                    <div class="stat-icon"><i class="fas fa-mountain"></i></div>
                    <div class="stat-number" data-count="{{ $tours->total() ?? 15 }}">0</div>
                    <div class="stat-label">Tours Available</div>
                </div>
                <div class="stat-item">
                    <div class="stat-icon"><i class="fas fa-users"></i></div>
                    <div class="stat-number" data-count="5000">0</div>
                    <div class="stat-label">Happy Travelers</div>
                </div>
                <div class="stat-item">
                    <div class="stat-icon"><i class="fas fa-star"></i></div>
                    <div class="stat-number" data-count="4.9">0</div>
                    <div class="stat-label">Average Rating</div>
                </div>
                <div class="stat-item">
                    <div class="stat-icon"><i class="fas fa-globe-africa"></i></div>
                    <div class="stat-number" data-count="25">0</div>
                    <div class="stat-label">Destinations</div>
                </div>
            </div>
        </div>
    </section>


    <!-- ============================================ -->
    <!-- Tours Grid Section -->
    <!-- ============================================ -->
    <section class="tours-grid-section">
        <div class="container">
            <div class="section-header" data-aos="fade-up">
                <div class="results-info">
                    <h2 class="section-title">All Tanzania Tours</h2>
                    <span class="results-count">
                        @if($tours->total() > 0)
                            Showing <strong id="tour-count">{{ $tours->firstItem() }}</strong> to <strong>{{ $tours->lastItem() }}</strong> of <strong>{{ $tours->total() }}</strong> {{ $tours->total() == 1 ? 'tour' : 'tours' }}
                        @else
                            <strong>0</strong> tours found
                        @endif
                    </span>
                </div>
                <div class="sort-options">
                    <label><i class="fas fa-sort"></i> Sort by:</label>
                    <select id="sort-tours" class="sort-select">
                        <option value="popular">Most Popular</option>
                        <option value="price-low">Price: Low to High</option>
                        <option value="price-high">Price: High to Low</option>
                        <option value="duration">Duration</option>
                        <option value="rating">Highest Rated</option>
                        <option value="newest">Newest First</option>
                    </select>
                </div>
            </div>

            <div class="tours-grid" id="tours-container">
                @if(isset($tours) && $tours->count() > 0)
                @foreach ($tours as $index => $tour)
                <div class="tour-card-modern" data-tour-id="{{ $tour['id'] }}" data-category="{{ strtolower($tour['destination']) }}" data-price="{{ $tour['price'] }}" data-duration="{{ $tour['duration_days'] }}" data-rating="{{ $tour['rating'] }}" data-aos="fade-up" data-aos-delay="{{ ($index % 3) * 100 }}">
                    <div class="tour-card-image-modern">
                        <a href="{{ route('tours.show', $tour['slug']) }}">
                            <img src="{{ $tour['image'] }}" alt="{{ $tour['name'] }}" loading="lazy">
                        </a>
                        <div class="tour-badges-modern">
                            @if($tour['is_featured'])
                            <span class="tour-badge-modern featured"><i class="fas fa-star"></i> Featured</span>
                        @endif
                            <span class="tour-badge-modern category">{{ $tour['destination'] }}</span>
                        </div>
                        <div class="tour-quick-actions-modern">
                            <button class="quick-action-btn-modern" title="Quick View" onclick="quickView({{ $tour['id'] }})">
                                <i class="fas fa-eye"></i>
                            </button>
                            <button class="quick-action-btn-modern" title="Add to Wishlist" onclick="addToWishlist({{ $tour['id'] }})">
                                <i class="far fa-heart"></i>
                            </button>
                        </div>
                    </div>
                    <div class="tour-card-content-modern">
                        <div class="tour-card-meta-modern">
                            <span class="tour-duration-modern">
                                <i class="fas fa-clock"></i> {{ $tour['duration_days'] }} Days
                            </span>
                            <span class="tour-rating-modern">
                                <i class="fas fa-star"></i> {{ $tour['rating'] }}
                            </span>
                        </div>
                        <h3 class="tour-card-title-modern">
                            <a href="{{ route('tours.show', $tour['slug']) }}">{{ $tour['name'] }}</a>
                        </h3>
                        <p class="tour-card-location-modern">
                            <i class="fas fa-map-marker-alt"></i> {{ $tour['destination'] }}
                        </p>
                        <p class="tour-card-description-modern">{{ $tour['description'] }}</p>
                        <div class="tour-card-footer-modern">
                            <div class="tour-price-modern">
                                <span class="price-label-modern">From</span>
                                <span class="price-amount-modern">${{ number_format($tour['starting_price'] ?? $tour['price']) }}</span>
                                <span class="price-note-modern">per person</span>
                            </div>
                            <div class="tour-actions-modern">
                                <a href="{{ route('tours.show', $tour['slug']) }}" class="btn btn-secondary btn-sm-modern">
                                    <i class="fas fa-info-circle"></i> Details
                                </a>
                                <a href="{{ route('booking') }}?tour={{ $tour['slug'] }}" class="btn btn-primary btn-sm-modern">
                                    <i class="fas fa-calendar-check"></i> Book Now
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
                @else
                <div class="no-tours-message">
                    <i class="fas fa-compass"></i>
                    <h3>No tours available at the moment</h3>
                    <p>Please check back later or contact us for custom tour options.</p>
                    <a href="{{ route('contact') }}" class="btn-primary">Contact Us</a>
                </div>
                @endif
            </div>

            <!-- Pagination -->
            @if($tours->hasPages())
            <div class="pagination-wrapper-modern" data-aos="fade-up">
                <nav class="pagination-modern" aria-label="Tour pagination">
                    {{-- Previous Page Link --}}
                    @if ($tours->onFirstPage())
                        <span class="page-link-modern prev disabled" aria-disabled="true">
                            <i class="fas fa-chevron-left"></i> <span class="pagination-text">Previous</span>
                        </span>
                    @else
                        <a href="{{ $tours->previousPageUrl() }}" class="page-link-modern prev" rel="prev">
                            <i class="fas fa-chevron-left"></i> <span class="pagination-text">Previous</span>
                        </a>
                    @endif

                    {{-- Pagination Elements with Smart Pagination --}}
                    @php
                        $currentPage = $tours->currentPage();
                        $lastPage = $tours->lastPage();
                        $showPages = 7; // Show 7 page numbers max
                        
                        if ($lastPage <= $showPages) {
                            // Show all pages if total pages is less than or equal to showPages
                            $startPage = 1;
                            $endPage = $lastPage;
                        } else {
                            // Calculate start and end pages
                            $half = floor($showPages / 2);
                            
                            if ($currentPage <= $half) {
                                $startPage = 1;
                                $endPage = $showPages;
                            } elseif ($currentPage >= $lastPage - $half) {
                                $startPage = $lastPage - $showPages + 1;
                                $endPage = $lastPage;
                            } else {
                                $startPage = $currentPage - $half;
                                $endPage = $currentPage + $half;
                            }
                        }
                    @endphp

                    {{-- First page and ellipsis --}}
                    @if ($startPage > 1)
                        <a href="{{ $tours->url(1) }}" class="page-link-modern">1</a>
                        @if ($startPage > 2)
                            <span class="page-link-modern ellipsis">...</span>
                        @endif
                    @endif

                    {{-- Page numbers --}}
                    @for ($page = $startPage; $page <= $endPage; $page++)
                        @if ($page == $currentPage)
                            <span class="page-link-modern active" aria-current="page">{{ $page }}</span>
                        @else
                            <a href="{{ $tours->url($page) }}" class="page-link-modern">{{ $page }}</a>
                        @endif
                    @endfor

                    {{-- Last page and ellipsis --}}
                    @if ($endPage < $lastPage)
                        @if ($endPage < $lastPage - 1)
                            <span class="page-link-modern ellipsis">...</span>
                        @endif
                        <a href="{{ $tours->url($lastPage) }}" class="page-link-modern">{{ $lastPage }}</a>
                    @endif

                    {{-- Next Page Link --}}
                    @if ($tours->hasMorePages())
                        <a href="{{ $tours->nextPageUrl() }}" class="page-link-modern next" rel="next">
                            <span class="pagination-text">Next</span> <i class="fas fa-chevron-right"></i>
                        </a>
                    @else
                        <span class="page-link-modern next disabled" aria-disabled="true">
                            <span class="pagination-text">Next</span> <i class="fas fa-chevron-right"></i>
                        </span>
                    @endif
                </nav>
                
                {{-- Page Info --}}
                <div class="pagination-info">
                    <span>Page <strong>{{ $tours->currentPage() }}</strong> of <strong>{{ $tours->lastPage() }}</strong></span>
                </div>
            </div>
            @endif
        </div>
    </section>

    <!-- ============================================ -->
    <!-- CTA Section -->
    <!-- ============================================ -->
    <section class="tours-cta-section">
        <div class="container" data-aos="zoom-in">
            <div class="cta-content-modern">
                <div class="cta-icon"><i class="fas fa-compass"></i></div>
                <h2>Can't Find Your Perfect Tanzania Tour?</h2>
                <p>Our Tanzania travel experts can create a custom itinerary tailored to your dreams and budget.</p>
                <div class="cta-buttons-modern">
                    <a href="{{ route('contact') }}" class="btn btn-primary btn-lg">
                        <i class="fas fa-envelope"></i> Request Custom Tour
                    </a>
                    <a href="https://wa.me/255123456789" target="_blank" class="btn btn-whatsapp-modern">
                        <i class="fab fa-whatsapp"></i> Chat on WhatsApp
                    </a>
                </div>
            </div>
        </div>
    </section>

@endsection

@push('styles')
<style>
    /* ============================================ */
    /* Tours Hero Section */
    /* ============================================ */
    .tours-hero-section {
        position: relative;
        padding: 150px 0 100px;
        background-size: cover;
        background-position: center;
        background-attachment: fixed;
        color: var(--white);
        text-align: center;
        min-height: 500px;
        display: flex;
        align-items: center;
    }
    .tours-hero-overlay {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: linear-gradient(135deg, rgba(26, 77, 58, 0.85) 0%, rgba(0, 0, 0, 0.7) 100%);
    }
    .tours-hero-content {
        position: relative;
        z-index: 2;
        max-width: 900px;
        margin: 0 auto;
    }
    .tours-hero-badge {
        display: inline-block;
        padding: 8px 20px;
        background: rgba(255, 255, 255, 0.2);
        backdrop-filter: blur(10px);
        border-radius: 30px;
        font-size: 0.9rem;
        font-weight: 600;
        margin-bottom: 20px;
        border: 1px solid rgba(255, 255, 255, 0.3);
    }
    .tours-hero-title {
        font-size: 3.5rem;
        font-weight: 800;
        margin-bottom: 20px;
        text-shadow: 2px 2px 10px rgba(0,0,0,0.5);
        line-height: 1.2;
    }
    .tours-hero-subtitle {
        font-size: 1.3rem;
        opacity: 0.95;
        line-height: 1.6;
    }

    /* ============================================ */
    /* Statistics Section */
    /* ============================================ */
    .tours-stats-section {
        padding: 60px 0;
        background: var(--white);
        border-bottom: 1px solid var(--gray-light);
    }
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 40px;
        text-align: center;
    }
    .stat-item {
        padding: 30px 20px;
        transition: transform 0.3s;
    }
    .stat-item:hover {
        transform: translateY(-5px);
    }
    .stat-icon {
        font-size: 3rem;
        color: var(--accent-green);
        margin-bottom: 15px;
    }
    .stat-number {
        font-size: 3rem;
        font-weight: 800;
        color: var(--primary-green);
        margin-bottom: 10px;
        line-height: 1;
    }
    .stat-label {
        font-size: 1rem;
        color: var(--gray);
        font-weight: 600;
    }


    /* ============================================ */
    /* Tours Grid Section */
    /* ============================================ */
    .tours-grid-section {
        padding: 80px 0;
        background: var(--gray-light);
    }
    .section-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 50px;
        flex-wrap: wrap;
        gap: 20px;
    }
    .section-title {
        font-size: 2.5rem;
        color: var(--primary-green);
        margin: 0 0 10px 0;
        font-weight: 800;
    }
    .results-count {
        color: var(--gray);
        font-size: 1rem;
    }
    .sort-options {
        display: flex;
        align-items: center;
        gap: 12px;
    }
    .sort-options label {
        font-weight: 600;
        color: var(--text-color);
    }
    .sort-select {
        padding: 12px 20px;
        border: 2px solid var(--gray-light);
        border-radius: 10px;
        background: var(--white);
        color: var(--text-color);
        cursor: pointer;
        font-size: 1rem;
        transition: all 0.3s;
    }
    .sort-select:focus {
        border-color: var(--accent-green);
        outline: none;
    }
    .tours-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(380px, 1fr));
        gap: 35px;
        margin-bottom: 60px;
    }
    .tour-card-modern {
        background: var(--white);
        border-radius: 20px;
        overflow: hidden;
        box-shadow: 0 8px 30px rgba(0,0,0,0.1);
        transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        position: relative;
    }
    .tour-card-modern:hover {
        transform: translateY(-12px);
        box-shadow: 0 20px 50px rgba(0,0,0,0.15);
    }
    .tour-card-image-modern {
        position: relative;
        height: 280px;
        overflow: hidden;
    }
    .tour-card-image-modern img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.6s ease;
    }
    .tour-card-modern:hover .tour-card-image-modern img {
        transform: scale(1.15);
    }
    .tour-badges-modern {
        position: absolute;
        top: 15px;
        left: 15px;
        right: 15px;
        display: flex;
        gap: 10px;
        flex-wrap: wrap;
        z-index: 3;
    }
    .tour-badge-modern {
        padding: 8px 16px;
        border-radius: 25px;
        font-size: 0.85rem;
        font-weight: 700;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        backdrop-filter: blur(10px);
    }
    .tour-badge-modern.featured {
        background: rgba(62, 165, 114, 0.95);
        color: var(--white);
    }
    .tour-badge-modern.deal {
        background: rgba(255, 107, 53, 0.95);
        color: var(--white);
    }
    .tour-badge-modern.category {
        background: rgba(0, 0, 0, 0.7);
        color: var(--white);
        margin-left: auto;
    }
    .tour-quick-actions-modern {
        position: absolute;
        top: 50%;
        right: 20px;
        transform: translateY(-50%);
        display: flex;
        flex-direction: column;
        gap: 12px;
        opacity: 0;
        transition: opacity 0.3s;
        z-index: 3;
    }
    .tour-card-modern:hover .tour-quick-actions-modern {
        opacity: 1;
    }
    .quick-action-btn-modern {
        width: 45px;
        height: 45px;
        border-radius: 50%;
        background: rgba(255,255,255,0.95);
        border: none;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--primary-green);
        transition: all 0.3s;
        box-shadow: 0 4px 15px rgba(0,0,0,0.2);
    }
    .quick-action-btn-modern:hover {
        background: var(--accent-green);
        color: var(--white);
        transform: scale(1.15);
    }
    .tour-card-content-modern {
        padding: 30px;
    }
    .tour-card-meta-modern {
        display: flex;
        justify-content: space-between;
        margin-bottom: 15px;
        font-size: 0.9rem;
    }
    .tour-duration-modern, .tour-rating-modern {
        display: flex;
        align-items: center;
        gap: 6px;
        color: var(--gray);
    }
    .tour-rating-modern {
        color: #FFA500;
        font-weight: 600;
    }
    .review-count-modern {
        color: var(--gray);
        font-size: 0.85rem;
    }
    .tour-card-title-modern {
        font-size: 1.6rem;
        margin: 0 0 10px;
        color: var(--primary-green);
        font-weight: 700;
        line-height: 1.3;
    }
    .tour-card-title-modern a {
        color: inherit;
        text-decoration: none;
        transition: color 0.3s;
    }
    .tour-card-title-modern a:hover {
        color: var(--accent-green);
    }
    .tour-card-location-modern {
        color: var(--gray);
        margin-bottom: 18px;
        font-size: 0.95rem;
        display: flex;
        align-items: center;
        gap: 6px;
    }
    .tour-card-features-modern {
        display: flex;
        flex-wrap: wrap;
        gap: 12px;
        margin-bottom: 20px;
        padding-bottom: 20px;
        border-bottom: 1px solid var(--gray-light);
    }
    .tour-card-features-modern span {
        font-size: 0.9rem;
        color: var(--primary-green);
        display: flex;
        align-items: center;
        gap: 6px;
    }
    .tour-card-features-modern i {
        color: var(--accent-green);
        font-size: 0.8rem;
    }
    .tour-card-footer-modern {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding-top: 15px;
    }
    .tour-price-modern {
        display: flex;
        flex-direction: column;
    }
    .price-label-modern {
        font-size: 0.8rem;
        color: var(--gray);
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    .price-amount-modern {
        font-size: 2rem;
        font-weight: 800;
        color: var(--accent-green);
        line-height: 1;
    }
    .price-note-modern {
        font-size: 0.85rem;
        color: var(--gray);
    }
    .tour-actions-modern {
        display: flex;
        gap: 10px;
    }
    .btn-sm-modern {
        padding: 12px 22px;
        font-size: 0.95rem;
        border-radius: 10px;
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 6px;
        transition: all 0.3s;
    }
    .btn-sm-modern:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(0,0,0,0.2);
    }

    /* ============================================ */
    /* Pagination */
    /* ============================================ */
    .pagination-wrapper-modern {
        margin-top: 60px;
        text-align: center;
    }
    .pagination-modern {
        display: flex;
        justify-content: center;
        align-items: center;
        gap: 8px;
        flex-wrap: wrap;
        margin-bottom: 20px;
    }
    .page-link-modern {
        min-width: 44px;
        height: 44px;
        padding: 12px 16px;
        border: 2px solid var(--gray-light);
        border-radius: 10px;
        text-decoration: none;
        color: var(--text-color);
        transition: all 0.3s;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 6px;
        font-weight: 600;
        font-size: 0.95rem;
        background: var(--white);
    }
    .page-link-modern:hover:not(.disabled):not(.active) {
        background: var(--light-green);
        border-color: var(--accent-green);
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    }
    .page-link-modern.active {
        background: var(--accent-green);
        color: var(--white);
        border-color: var(--accent-green);
        cursor: default;
        pointer-events: none;
    }
    .page-link-modern.disabled {
        opacity: 0.4;
        cursor: not-allowed;
        pointer-events: none;
        background: var(--gray-light);
    }
    .page-link-modern.ellipsis {
        border: none;
        cursor: default;
        pointer-events: none;
        color: var(--gray);
        padding: 12px 10px;
        background: transparent;
    }
    .pagination-text {
        display: inline;
    }
    .pagination-info {
        text-align: center;
        margin-top: 20px;
        color: var(--gray);
        font-size: 0.95rem;
    }
    .pagination-info strong {
        color: var(--primary-green);
        font-weight: 700;
    }
    .page-link-modern.prev,
    .page-link-modern.next {
        min-width: auto;
        padding: 12px 20px;
    }
    .pagination-info {
        margin-top: 15px;
        color: var(--gray);
        font-size: 0.95rem;
        font-weight: 500;
        text-align: center;
    }
    .pagination-info strong {
        color: var(--primary-green);
        font-weight: 700;
    }
    .page-link-modern.ellipsis {
        border: none;
        cursor: default;
        pointer-events: none;
        color: var(--gray);
        padding: 12px 10px;
        background: transparent;
    }
    .pagination-text {
        display: inline;
    }

    /* ============================================ */
    /* CTA Section */
    /* ============================================ */
    .tours-cta-section {
        padding: 100px 0;
        background: linear-gradient(135deg, var(--primary-green) 0%, var(--secondary-green) 100%);
        color: var(--white);
        text-align: center;
        position: relative;
        overflow: hidden;
    }
    .tours-cta-section::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: url('data:image/svg+xml,<svg width="100" height="100" xmlns="http://www.w3.org/2000/svg"><circle cx="50" cy="50" r="2" fill="rgba(255,255,255,0.1)"/></svg>');
        opacity: 0.3;
    }
    .cta-content-modern {
        position: relative;
        z-index: 2;
        max-width: 800px;
        margin: 0 auto;
    }
    .cta-icon {
        font-size: 4rem;
        margin-bottom: 20px;
        opacity: 0.9;
    }
    .cta-content-modern h2 {
        font-size: 2.8rem;
        margin-bottom: 20px;
        font-weight: 800;
    }
    .cta-content-modern p {
        font-size: 1.2rem;
        margin-bottom: 35px;
        opacity: 0.95;
        line-height: 1.6;
    }
    .cta-buttons-modern {
        display: flex;
        justify-content: center;
        gap: 20px;
        flex-wrap: wrap;
    }
    .btn-lg {
        padding: 16px 35px;
        font-size: 1.1rem;
    }
    .btn-whatsapp-modern {
        background: #25D366;
        color: var(--white);
        border: none;
    }
    .btn-whatsapp-modern:hover {
        background: #20BA5A;
        transform: translateY(-2px);
    }

    /* ============================================ */
    /* Responsive Design */
    /* ============================================ */
    @media (max-width: 1200px) {
        .tours-grid {
            grid-template-columns: repeat(auto-fill, minmax(340px, 1fr));
        }
    }
    @media (max-width: 992px) {
        .tours-hero-title {
            font-size: 2.8rem;
        }
        .stats-grid {
            grid-template-columns: repeat(2, 1fr);
        }
        .tours-grid {
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 25px;
        }
        .filters-grid {
            grid-template-columns: 1fr;
        }
        .section-header {
            flex-direction: column;
            align-items: flex-start;
        }
    }
    @media (max-width: 768px) {
        .tours-hero-section {
            padding: 120px 0 80px;
            min-height: 400px;
        }
        .tours-hero-title {
            font-size: 2.2rem;
        }
        .tours-hero-subtitle {
            font-size: 1.1rem;
        }
        .stats-grid {
            grid-template-columns: 1fr;
            gap: 30px;
        }
        .tours-grid {
            grid-template-columns: 1fr;
        }
        .cta-content-modern h2 {
            font-size: 2rem;
        }
        .cta-buttons-modern {
            flex-direction: column;
        }
        .btn-lg {
            width: 100%;
        }
        .pagination-modern {
            gap: 8px;
        }
        .page-link-modern {
            padding: 10px 15px;
            font-size: 0.9rem;
        }
        .pagination-text {
            display: none;
        }
        .page-link-modern.prev,
        .page-link-modern.next {
            padding: 10px 15px;
        }
    }
    @media (max-width: 480px) {
        .pagination-modern {
            gap: 5px;
        }
        .page-link-modern {
            padding: 8px 12px;
            font-size: 0.85rem;
            min-width: 40px;
            justify-content: center;
        }
        .page-link-modern.ellipsis {
            padding: 8px 5px;
            min-width: auto;
        }
        .pagination-info {
            font-size: 0.85rem;
            margin-top: 15px;
        }
    }
</style>
@endpush

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Animate statistics
        function animateStats() {
            const stats = document.querySelectorAll('.stat-number');
            stats.forEach(stat => {
                const target = parseFloat(stat.getAttribute('data-count'));
                const duration = 2000;
                const increment = target / (duration / 16);
                let current = 0;
                
                const timer = setInterval(() => {
                    current += increment;
                    if (current >= target) {
                        stat.textContent = target % 1 === 0 ? Math.floor(target) : target.toFixed(1);
                        clearInterval(timer);
                    } else {
                        stat.textContent = current % 1 === 0 ? Math.floor(current) : current.toFixed(1);
                    }
                }, 16);
            });
        }

        // Intersection Observer for stats
        const statsObserver = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting && !entry.target.classList.contains('animated')) {
                    entry.target.classList.add('animated');
                    animateStats();
                }
            });
        }, { threshold: 0.5 });

        const statsSection = document.querySelector('.tours-stats-section');
        if (statsSection) {
            statsObserver.observe(statsSection);
        }

        // Sort functionality (client-side sorting for current page)
        document.getElementById('sort-tours')?.addEventListener('change', function() {
            const sortValue = this.value;
            const container = document.getElementById('tours-container');
            const cards = Array.from(container.querySelectorAll('.tour-card-modern'));

            // Only sort if we have cards on the page
            if (cards.length === 0) return;

            cards.sort((a, b) => {
                switch(sortValue) {
                    case 'price-low':
                        return parseFloat(a.dataset.price) - parseFloat(b.dataset.price);
                    case 'price-high':
                        return parseFloat(b.dataset.price) - parseFloat(a.dataset.price);
                    case 'duration':
                        return parseInt(a.dataset.duration) - parseInt(b.dataset.duration);
                    case 'rating':
                        return parseFloat(b.dataset.rating) - parseFloat(a.dataset.rating);
                    case 'newest':
                        // For newest, we rely on server-side ordering
                        return 0;
                    default:
                        return 0;
                }
            });

            // Re-append sorted cards
            cards.forEach(card => container.appendChild(card));
            
            // Add visual feedback
            container.style.opacity = '0.7';
            setTimeout(() => {
                container.style.opacity = '1';
            }, 200);
        });

        // Quick view and wishlist functions
        window.quickView = function(tourId) {
            console.log('Quick view tour:', tourId);
            // Implement quick view modal
        };

        window.addToWishlist = function(tourId) {
            console.log('Add to wishlist:', tourId);
            // Implement wishlist functionality
        };
    });
</script>
@endpush
