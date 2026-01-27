@extends('layouts.app')

@section('title', 'Explore All Tours - Adventure Tours')
@section('description', 'Browse our complete collection of expertly guided tours. From high-altitude treks to immersive wildlife safaris, your next great adventure starts here.')

@section('body_class', 'tours-index-page')

@section('content')

    <!-- Page Header -->
    <section class="page-header" style="background-image: url('https://images.unsplash.com/photo-1458442310124-05424337123b?auto=format&fit=crop&w=1920&q=80&blend=0f3429&sat=-100&bri=-20&bm=multiply');">
        <div class="container">
            <h1 class="page-title" data-aos="fade-up">All Adventures</h1>
            <p class="page-subtitle" data-aos="fade-up" data-aos-delay="100">The world is waiting. Choose your journey.</p>
        </div>
    </section>

    <!-- Main Tours Grid Section -->
    <section class="content-section">
        <div class="container">
            <!-- Filter Bar -->
            <div class="filter-bar" data-aos="fade-up">
                <button class="filter-btn active">All</button>
                <button class="filter-btn">Hiking</button>
                <button class="filter-btn">Safari</button>
                <button class="filter-btn">Diving</button>
                <button class="filter-btn">Climbing</button>
            </div>

            {{-- Re-uses the tour card component style from home.blade.php --}}
            <div class="tours-grid">
                @php
                    $all_tours = [
                        ['title' => 'Kilimanjaro Expedition', 'slug' => 'kilimanjaro-expedition', 'location' => 'Tanzania, Africa', 'price' => 2800, 'image' => 'https://images.unsplash.com/photo-1589834390005-5d4fb9bf3d32?auto=format&fit=crop&w=800&q=80', 'category' => 'Hiking', 'duration' => 8, 'rating' => 4.9],
                        ['title' => 'Serengeti Great Migration', 'slug' => 'serengeti-great-migration', 'location' => 'Tanzania, Africa', 'price' => 3500, 'image' => 'https://images.unsplash.com/photo-1534437431430-c4d9c7a53d8e?auto=format&fit=crop&w=800&q=80', 'category' => 'Safari', 'duration' => 7, 'rating' => 5.0],
                        ['title' => 'Everest Base Camp Trek', 'slug' => 'everest-base-camp-trek', 'location' => 'Nepal, Asia', 'price' => 1990, 'image' => 'https://images.unsplash.com/photo-1605289355482-7ab3f738a543?auto=format&fit=crop&w=800&q=80', 'category' => 'Hiking', 'duration' => 14, 'rating' => 4.8],
                        ['title' => 'Patagonia W Trek', 'slug' => 'patagonia-w-trek', 'location' => 'Chile, Americas', 'price' => 2400, 'image' => 'https://images.unsplash.com/photo-1528271537-64676384a754?auto=format&fit=crop&w=800&q=80', 'category' => 'Hiking', 'duration' => 9, 'rating' => 4.9],
                        ['title' => 'Great Barrier Reef Dive', 'slug' => 'great-barrier-reef-dive', 'location' => 'Australia, Oceania', 'price' => 1500, 'image' => 'https://images.unsplash.com/photo-1617208192237-9d7a97a3a1f8?auto=format&fit=crop&w=800&q=80', 'category' => 'Diving', 'duration' => 5, 'rating' => 4.7],
                        ['title' => 'Yosemite Rock Climbing', 'slug' => 'yosemite-rock-climbing', 'location' => 'USA, Americas', 'price' => 950, 'image' => 'https://images.unsplash.com/photo-1594994895912-1f21a1460a37?auto=format&fit=crop&w=800&q=80', 'category' => 'Climbing', 'duration' => 3, 'rating' => 4.9],
                    ];
                @endphp

                @foreach ($all_tours as $index => $tour)
                <div class="tour-card" data-aos="fade-up" data-aos-delay="{{ ($index % 3) * 100 }}">
                    <div class="tour-card-image">
                        <a href="{{ route('tours.show', $tour['slug']) }}">
                            <img src="{{ $tour['image'] }}" alt="{{ $tour['title'] }}">
                        </a>
                        <div class="tour-card-badge">{{ $tour['category'] }}</div>
                        <div class="tour-card-price">From <span>${{ number_format($tour['price']) }}</span></div>
                    </div>
                    <div class="tour-card-content">
                        <div class="tour-card-meta">
                            <span><i class="fas fa-clock"></i> {{ $tour['duration'] }} Days</span>
                            <span><i class="fas fa-star"></i> {{ $tour['rating'] }}/5</span>
                        </div>
                        <h3 class="tour-card-title"><a href="{{ route('tours.show', $tour['slug']) }}">{{ $tour['title'] }}</a></h3>
                        <p class="tour-card-location"><i class="fas fa-map-marker-alt"></i> {{ $tour['location'] }}</p>
                        <a href="{{ route('tours.show', $tour['slug']) }}" class="btn-details">View Details <i class="fas fa-arrow-right"></i></a>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </section>

@endsection

@push('styles')
<style>
    /* Page Header */
    .page-header { padding: 100px 0; text-align: center; position: relative; background-size: cover; background-position: center; color: var(--white); }
    .page-header::after { content: ''; position: absolute; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.6); }
    .page-header .container { position: relative; z-index: 2; }
    .page-title { font-family: var(--font-primary); font-size: 3.5rem; text-transform: uppercase; margin-bottom: 10px; }
    .page-subtitle { font-family: var(--font-secondary); font-size: 1.2rem; color: rgba(255,255,255,0.8); max-width: 600px; margin: 0 auto; }

    /* Filter Bar */
    .filter-bar { display: flex; justify-content: center; flex-wrap: wrap; gap: 15px; margin-bottom: 50px; }
    .filter-btn { font-family: var(--font-secondary); font-weight: 500; padding: 10px 25px; background: var(--card-bg); color: var(--text-color); border: 1px solid var(--shadow); border-radius: 50px; cursor: pointer; transition: all 0.3s; }
    .filter-btn:hover { background: var(--light-green); border-color: var(--accent-green); }
    .filter-btn.active { background: var(--accent-green); color: var(--white); border-color: var(--accent-green); }
    html.dark .filter-btn { background: var(--card-bg); }
    html.dark .filter-btn:hover { background: rgba(255,255,255,0.1); border-color: var(--accent-green); }

    /* Tour Card Styles (re-used from home for consistency) */
    .tours-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(340px, 1fr)); gap: 30px; }
    .tour-card { background: var(--card-bg); border-radius: 12px; box-shadow: 0 5px 25px var(--shadow); overflow: hidden; transition: all 0.4s ease; }
    .tour-card:hover { transform: translateY(-10px); box-shadow: 0 15px 40px var(--shadow); }
    .tour-card-image { position: relative; height: 250px; }
    .tour-card-image img { width: 100%; height: 100%; object-fit: cover; }
    .tour-card-badge { position: absolute; top: 15px; left: 15px; background: rgba(0,0,0,0.5); color: white; padding: 5px 12px; border-radius: 20px; font-size: 0.8rem; font-weight: 500; backdrop-filter: blur(5px); }
    .tour-card-price { position: absolute; bottom: 15px; right: 15px; background: rgba(255,255,255,0.9); color: var(--primary-green); padding: 8px 15px; border-radius: 20px; font-size: 1rem; font-weight: 600; backdrop-filter: blur(5px); }
    html.dark .tour-card-price { background: rgba(0,0,0,0.8); color: var(--accent-green); }
    .tour-card-price span { font-size: 1.2rem; }
    .tour-card-content { padding: 25px; }
    .tour-card-meta { display: flex; gap: 20px; color: var(--gray); margin-bottom: 15px; font-size: 0.9rem; }
    .tour-card-title a { font-family: var(--font-primary); font-size: 1.5rem; color: var(--text-color); text-decoration: none; transition: color 0.3s; }
    .tour-card-title a:hover { color: var(--accent-green); }
    .tour-card-location { color: var(--gray); font-size: 0.9rem; margin-bottom: 20px; }
    .btn-details { color: var(--accent-green); text-decoration: none; font-weight: 600; font-family: var(--font-secondary); }
    .btn-details i { transition: transform 0.3s; }
    .btn-details:hover i { transform: translateX(5px); }
</style>
@endpush
