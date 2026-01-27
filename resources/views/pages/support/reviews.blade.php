@extends('layouts.app')

@section('title', 'Customer Reviews - Adventure Tours')
@section('description', 'Read stories and testimonials from our community of travelers. See why adventurers from around the world choose us for their journey of a lifetime.')

@section('body_class', 'support-page')

@section('content')

    <!-- Page Header -->
    <section class="page-header" style="background-image: url('https://images.unsplash.com/photo-1517732306149-e8f829eb588a?auto=format&fit=crop&w=1920&q=80&blend=0f3429&sat=-100&bri=-20&bm=multiply');">
        <div class="container">
            <h1 class="page-title" data-aos="fade-up">Stories from the Trail</h1>
            <p class="page-subtitle" data-aos="fade-up" data-aos-delay="100">Don't just take our word for it. Here's what our travelers have to say.</p>
        </div>
    </section>

    <!-- Reviews Section -->
    <section class="content-section">
        <div class="container">
            <div class="reviews-grid">
                @php
                    $reviews = [
                        ['name' => 'Sarah L.', 'tour' => 'Kilimanjaro Expedition', 'rating' => 5, 'text' => 'The most challenging and rewarding experience of my life. The guides were phenomenal—their expertise and encouragement were the only reason I reached the summit. I felt safe and supported every step of the way.'],
                        ['name' => 'David Chen', 'tour' => 'Serengeti Great Migration', 'rating' => 5, 'text' => 'Unbelievable. Seeing the great migration in person is something I\'ll never forget. Our guide, Joseph, was a walking encyclopedia of the Serengeti. The lodges were pure luxury in the middle of the wild. 10/10!'],
                        ['name' => 'Emily Rose', 'tour' => 'Patagonia W Trek', 'rating' => 4, 'text' => 'Patagonia\'s beauty is otherworldly. The logistics were handled flawlessly by Adventure Tours. The trek was tough but the views were worth every ounce of effort. My only suggestion is to pack more warm socks!'],
                        ['name' => 'Mark Jenkins', 'tour' => 'Everest Base Camp Trek', 'rating' => 5, 'text' => 'A lifelong dream fulfilled. The entire crew, from the guides to the porters, were absolute heroes. The cultural immersion in the Sherpa villages was just as impactful as seeing Everest itself. Highly recommend.'],
                    ];
                @endphp
                @foreach($reviews as $review)
                <div class="review-card" data-aos="fade-up">
                    <div class="review-stars">
                        @for ($i = 0; $i < $review['rating']; $i++) <i class="fas fa-star"></i> @endfor
                    </div>
                    <p class="review-text">"{{ $review['text'] }}"</p>
                    <div class="review-author">
                        <span class="author-name">- {{ $review['name'] }}</span>
                        <span class="author-tour">{{ $review['tour'] }}</span>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </section>
@endsection

@push('styles')
<style>
    /* Page Header Styles */
    .page-header { padding: 100px 0; /* ... */ }

    .reviews-grid {
        column-count: 3;
        column-gap: 30px;
    }
    .review-card {
        background: var(--card-bg);
        padding: 30px;
        border-radius: 8px;
        box-shadow: 0 5px 25px var(--shadow);
        margin-bottom: 30px;
        display: inline-block;
        width: 100%;
    }
    .review-stars {
        color: #ffc107;
        margin-bottom: 15px;
    }
    .review-text {
        color: var(--gray);
        font-style: italic;
        line-height: 1.7;
        margin-bottom: 20px;
    }
    .review-author {
        text-align: right;
    }
    .author-name {
        font-weight: 600;
        color: var(--text-color);
        display: block;
    }
    .author-tour {
        font-size: 0.9rem;
        color: var(--accent-green);
    }
    @media (max-width: 992px) { .reviews-grid { column-count: 2; } }
    @media (max-width: 768px) { .reviews-grid { column-count: 1; } }
</style>
@endpush
