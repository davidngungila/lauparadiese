@extends('layouts.app')

@section('title', 'Travel Blog - Adventure Tours')
@section('description', 'Get inspired for your next journey with travel stories, expert tips, and destination guides from the team at Adventure Tours.')

@section('body_class', 'blog-index-page')

@section('content')

    <!-- Page Header -->
    <section class="page-header" style="background-image: url('https://images.unsplash.com/photo-1476514525535-07fb3b4ae5f1?auto=format&fit=crop&w=1920&q=80&blend=0f3429&sat=-100&bri=-20&bm=multiply');">
        <div class="container">
            <h1 class="page-title" data-aos="fade-up">Adventure Awaits</h1>
            <p class="page-subtitle" data-aos="fade-up" data-aos-delay="100">Stories, tips, and inspiration from our travels.</p>
        </div>
    </section>

    <!-- Main Blog Section -->
    <section class="content-section">
        <div class="container">
            <div class="blog-layout">
                <!-- Main Content: Posts Grid -->
                <div class="blog-main-content">
                    @php
                        // In a real app, this data would come from your BlogController
                        $posts = [
                            ['title' => '10 Essential Tips for High-Altitude Trekking', 'slug' => '10-essential-tips-for-high-altitude-trekking', 'author' => 'Alex Riley', 'published_at' => '2025-08-15', 'category' => 'Travel Tips', 'category_slug' => 'travel-tips', 'excerpt' => 'Preparing for a high-altitude trek requires more than just physical fitness. Here are our top 10 tips to ensure you stay safe...', 'image' => 'https://images.unsplash.com/photo-1551632811-561732d1e306?auto=format&fit=crop&w=800&q=80'],
                            ['title' => 'A Guide to Ethical Wildlife Photography', 'slug' => 'a-guide-to-ethical-wildlife-photography-on-safari', 'author' => 'Maria Chen', 'published_at' => '2025-07-22', 'category' => 'Safari Guides', 'category_slug' => 'safari-guides', 'excerpt' => 'Capturing the perfect shot shouldn\'t come at the expense of the animals\' well-being. Learn the principles of ethical wildlife photography...', 'image' => 'https://images.unsplash.com/photo-1549462375-4c5b0814f13b?auto=format&fit=crop&w=800&q=80'],
                            ['title' => 'Packing for Patagonia: The Ultimate Gear List', 'slug' => 'packing-for-patagonia-the-ultimate-gear-list', 'author' => 'Alex Riley', 'published_at' => '2025-06-30', 'category' => 'Travel Tips', 'category_slug' => 'travel-tips', 'excerpt' => 'Patagonia\'s weather is notoriously unpredictable. Our comprehensive gear list covers everything you\'ll need to stay comfortable...', 'image' => 'https://images.unsplash.com/photo-1542692244-138c45424912?auto=format&fit=crop&w=800&q=80'],
                        ];
                    @endphp

                    <!-- Featured Post -->
                    <a href="{{ route('blog.show', $posts[0]['slug']) }}" class="featured-post-card" data-aos="fade-up">
                        <div class="featured-post-image">
                            <img src="{{ $posts[0]['image'] }}" alt="{{ $posts[0]['title'] }}">
                        </div>
                        <div class="featured-post-content">
                            <span class="post-category">{{ $posts[0]['category'] }}</span>
                            <h2 class="featured-post-title">{{ $posts[0]['title'] }}</h2>
                            <p class="post-excerpt">{{ $posts[0]['excerpt'] }}</p>
                            <span class="post-meta">By {{ $posts[0]['author'] }} on {{ \Carbon\Carbon::parse($posts[0]['published_at'])->format('F j, Y') }}</span>
                        </div>
                    </a>

                    <div class="latest-posts-grid">
                        @foreach (array_slice($posts, 1) as $post)
                            <a href="{{ route('blog.show', $post['slug']) }}" class="post-card" data-aos="fade-up">
                                <div class="post-card-image"><img src="{{ $post['image'] }}" alt="{{ $post['title'] }}"></div>
                                <div class="post-card-content">
                                    <span class="post-category">{{ $post['category'] }}</span>
                                    <h3 class="post-card-title">{{ $post['title'] }}</h3>
                                    <span class="post-meta">By {{ $post['author'] }}</span>
                                </div>
                            </a>
                        @endforeach
                    </div>
                </div>

                <!-- Sidebar -->
                <aside class="blog-sidebar" data-aos="fade-left">
                    <div class="sidebar-widget">
                        <h4 class="widget-title">Categories</h4>
                        <ul class="category-list">
                            <li><a href="{{ route('blog.category', 'travel-tips') }}">Travel Tips</a></li>
                            <li><a href="{{ route('blog.category', 'safari-guides') }}">Safari Guides</a></li>
                            <li><a href="#">Destination Spotlights</a></li>
                            <li><a href="#">Gear Reviews</a></li>
                        </ul>
                    </div>
                    {{-- Add more widgets like "Recent Posts" or a search bar here --}}
                </aside>
            </div>
        </div>
    </section>

@endsection

@push('styles')
<style>
    /* Page Header */
    .page-header { padding: 100px 0; /* ... */ }

    /* Blog Layout */
    .blog-layout { display: grid; grid-template-columns: 3fr 1fr; gap: 50px; }
    
    /* Post Cards */
    .featured-post-card { display: block; text-decoration: none; background: var(--card-bg); border-radius: 12px; box-shadow: 0 5px 25px var(--shadow); margin-bottom: 50px; transition: all 0.3s; }
    .featured-post-card:hover { transform: translateY(-5px); box-shadow: 0 15px 40px var(--shadow); }
    .featured-post-image { height: 400px; border-radius: 12px 12px 0 0; overflow: hidden; }
    .featured-post-image img { width: 100%; height: 100%; object-fit: cover; }
    .featured-post-content { padding: 30px; }
    .featured-post-title { font-family: var(--font-primary); font-size: 2rem; color: var(--text-color); margin-bottom: 15px; }
    
    .latest-posts-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 30px; }
    .post-card { display: block; text-decoration: none; background: var(--card-bg); border-radius: 12px; box-shadow: 0 5px 25px var(--shadow); transition: all 0.3s; }
    .post-card:hover { transform: translateY(-5px); box-shadow: 0 15px 40px var(--shadow); }
    .post-card-image { height: 200px; border-radius: 12px 12px 0 0; overflow: hidden; }
    .post-card-image img { width: 100%; height: 100%; object-fit: cover; }
    .post-card-content { padding: 20px; }
    .post-card-title { font-family: var(--font-primary); font-size: 1.3rem; color: var(--text-color); margin-bottom: 10px; }

    .post-category { font-family: var(--font-secondary); font-weight: 600; color: var(--accent-green); font-size: 0.9rem; margin-bottom: 10px; display: block; }
    .post-excerpt { font-family: var(--font-secondary); color: var(--gray); margin-bottom: 20px; }
    .post-meta { font-family: var(--font-secondary); font-size: 0.9rem; color: var(--gray); }

    /* Sidebar */
    .sidebar-widget { background: var(--card-bg); padding: 30px; border-radius: 12px; box-shadow: 0 5px 25px var(--shadow); }
    .widget-title { font-family: var(--font-primary); font-size: 1.5rem; margin-bottom: 20px; }
    .category-list { list-style: none; padding: 0; }
    .category-list li { margin-bottom: 10px; }
    .category-list a { color: var(--gray); text-decoration: none; transition: color 0.3s; }
    .category-list a:hover { color: var(--accent-green); }

    @media(max-width: 992px) { .blog-layout { grid-template-columns: 1fr; } .blog-sidebar { order: -1; margin-bottom: 40px; } }
    @media(max-width: 768px) { .latest-posts-grid { grid-template-columns: 1fr; } }
</style>
@endpush
