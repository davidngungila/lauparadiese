@extends('layouts.app')

{{-- Title and description would be dynamic, e.g., $post->title --}}
@section('title', '10 Essential Tips for High-Altitude Trekking - Adventure Tours')
@section('description', 'Preparing for a high-altitude trek requires more than just physical fitness. Here are our top 10 tips...')

@section('body_class', 'blog-show-page')

@section('content')

    @php
        // This would be the single $post object from your controller
        $post = ['title' => '10 Essential Tips for High-Altitude Trekking', 'slug' => '10-essential-tips-for-high-altitude-trekking', 'author' => 'Alex Riley', 'published_at' => '2025-08-15', 'category' => 'Travel Tips', 'category_slug' => 'travel-tips', 'excerpt' => 'Preparing for a high-altitude trek requires more than just physical fitness. Here are our top 10 tips to ensure you stay safe, healthy, and happy on your journey to the top.', 'image' => 'https://images.unsplash.com/photo-1551632811-561732d1e306?auto=format&fit=crop&w=1920&q=80'];
    @endphp

    <!-- Post Hero -->
    <section class="post-hero" style="background-image: url('{{ $post['image'] }}');">
        <div class="container">
            <div class="post-hero-content">
                <a href="{{ route('blog.category', $post['category_slug']) }}" class="post-category">{{ $post['category'] }}</a>
                <h1 class="post-title" data-aos="fade-up">{{ $post['title'] }}</h1>
                <p class="post-meta" data-aos="fade-up" data-aos-delay="100">
                    By {{ $post['author'] }} | Published on {{ \Carbon\Carbon::parse($post['published_at'])->format('F j, Y') }}
                </p>
            </div>
        </div>
    </section>

    <!-- Post Content Section -->
    <section class="content-section post-content-section">
        <div class="container">
            <div class="post-layout">
                <article class="post-content">
                    <p class="lead">{{ $post['excerpt'] }}</p>
                    <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer posuere erat a ante. Praesent commodo cursus magna, vel scelerisque nisl consectetur et. Nullam id dolor id nibh ultricies vehicula ut id elit. Cras justo odio, dapibus ac facilisis in, egestas eget quam.</p>
                    
                    <h2>1. Acclimatize Properly: Go Slow</h2>
                    <p>The golden rule of high-altitude trekking is "climb high, sleep low." Ascend gradually to allow your body to adapt to the reduced oxygen levels. Pushing yourself too hard, too soon is the quickest way to develop altitude sickness.</p>
                    
                    <figure>
                        <img src="https://images.unsplash.com/photo-1605289355482-7ab3f738a543?auto=format&fit=crop&w=1200&q=80" alt="Trekkers in the Himalayas">
                        <figcaption>Taking a break to acclimatize with Everest in the background.</figcaption>
                    </figure>

                    <h2>2. Hydration is Key</h2>
                    <p>You dehydrate much faster at altitude. Aim to drink at least 3-4 liters of water per day. This not only helps with acclimatization but also keeps your energy levels up. Avoid excessive caffeine and alcohol, as they can contribute to dehydration.</p>

                </article>

                <aside class="post-sidebar">
                    <div class="share-widget">
                        <h4>Share This Post</h4>
                        <div class="share-links">
                            <a href="#" aria-label="Share on Facebook"><i class="fab fa-facebook-f"></i></a>
                            <a href="#" aria-label="Share on Twitter"><i class="fab fa-twitter"></i></a>
                            <a href="#" aria-label="Share on LinkedIn"><i class="fab fa-linkedin-in"></i></a>
                            <a href="#" aria-label="Copy Link"><i class="fas fa-link"></i></a>
                        </div>
                    </div>
                </aside>
            </div>
        </div>
    </section>

@endsection

@push('styles')
<style>
    /* Post Hero */
    .post-hero { height: 60vh; display: flex; align-items: flex-end; position: relative; background-size: cover; background-position: center; color: var(--white); padding-bottom: 60px; }
    .post-hero::after { content: ''; position: absolute; top: 0; left: 0; width: 100%; height: 100%; background: linear-gradient(to top, rgba(0,0,0,0.8) 0%, rgba(0,0,0,0) 100%); }
    .post-hero-content { position: relative; z-index: 2; text-align: center; max-width: 800px; margin: 0 auto; }
    .post-hero .post-category { background: var(--accent-green); color: var(--white); padding: 5px 15px; border-radius: 20px; font-size: 0.9rem; text-decoration: none; }
    .post-title { font-family: var(--font-primary); font-size: 3rem; margin: 15px 0; }
    .post-meta { font-family: var(--font-secondary); opacity: 0.8; }
    
    /* Post Content Section */
    .post-content-section { padding: 80px 0; background: var(--background); }
    .post-layout { display: grid; grid-template-columns: 3fr 1fr; gap: 60px; }
    .post-content { max-width: 750px; }
    .post-content .lead { font-size: 1.2rem; font-weight: 500; color: var(--text-color); }
    .post-content p { color: var(--gray); line-height: 1.8; margin-bottom: 1.5em; }
    .post-content h2 { font-family: var(--font-primary); font-size: 2rem; margin: 2em 0 1em; }
    .post-content figure { margin: 2em 0; }
    .post-content figure img { width: 100%; border-radius: 8px; }
    .post-content figure figcaption { text-align: center; font-size: 0.9rem; color: var(--gray); margin-top: 10px; font-style: italic; }

    /* Post Sidebar */
    .post-sidebar { position: sticky; top: 120px; height: fit-content; }
    .share-widget { background: var(--card-bg); padding: 30px; border-radius: 8px; text-align: center; box-shadow: 0 5px 25px var(--shadow); }
    .share-widget h4 { font-family: var(--font-primary); margin-bottom: 20px; }
    .share-links { display: flex; justify-content: center; gap: 15px; }
    .share-links a { display: flex; align-items: center; justify-content: center; width: 45px; height: 45px; border-radius: 50%; background: var(--light-green); color: var(--gray); text-decoration: none; font-size: 1.1rem; transition: all 0.3s; }
    .share-links a:hover { background: var(--accent-green); color: var(--white); }

    @media(max-width: 992px) { .post-layout { grid-template-columns: 1fr; } .post-sidebar { display: none; } }
</style>
@endpush
