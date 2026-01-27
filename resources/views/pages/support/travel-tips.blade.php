@extends('layouts.app')

@section('title', 'Travel Tips - Adventure Tours')
@section('description', 'Expert advice and practical tips to help you prepare for your adventure. From packing lists to photography guides, find everything you need to know.')

@section('body_class', 'support-page')

@section('content')

    <!-- Page Header -->
    <section class="page-header" style="background-image: url('https://images.unsplash.com/photo-1501785888041-af3ef285b470?auto=format&fit=crop&w=1920&q=80&blend=0f3429&sat=-100&bri=-20&bm=multiply');">
        <div class="container">
            <h1 class="page-title" data-aos="fade-up">Expert Travel Tips</h1>
            <p class="page-subtitle" data-aos="fade-up" data-aos-delay="100">Insider advice from our guides to help you travel smarter, safer, and better.</p>
        </div>
    </section>

    <!-- Tips Grid Section -->
    <section class="content-section">
        <div class="container">
            <div class="tips-grid">
                 @php
                    $tips = [
                        ['title' => 'The Ultimate High-Altitude Packing List', 'category' => 'Preparation', 'image' => 'https://images.unsplash.com/photo-1551632811-561732d1e306?auto=format&fit=crop&w=800&q=80', 'slug' => 'packing-list-high-altitude'],
                        ['title' => '5 Tips for Ethical Wildlife Photography', 'category' => 'Photography', 'image' => 'https://images.unsplash.com/photo-1549462375-4c5b0814f13b?auto=format&fit=crop&w=800&q=80', 'slug' => 'ethical-wildlife-photography'],
                        ['title' => 'How to Acclimatize Safely', 'category' => 'Health & Safety', 'image' => 'https://images.unsplash.com/photo-1605289355482-7ab3f738a543?auto=format&fit=crop&w=800&q=80', 'slug' => 'how-to-acclimatize'],
                    ];
                @endphp
                @foreach($tips as $tip)
                <a href="#" class="tip-card" data-aos="fade-up">
                    <div class="tip-card-image">
                        <img src="{{ $tip['image'] }}" alt="{{ $tip['title'] }}">
                        <div class="tip-card-category">{{ $tip['category'] }}</div>
                    </div>
                    <div class="tip-card-content">
                        <h3 class="tip-card-title">{{ $tip['title'] }}</h3>
                        <span class="tip-card-link">Read More <i class="fas fa-arrow-right"></i></span>
                    </div>
                </a>
                @endforeach
            </div>
        </div>
    </section>
@endsection

@push('styles')
<style>
    /* Page Header Styles */
    .page-header { padding: 100px 0; /* ... */ }
    
    .tips-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(340px, 1fr)); gap: 30px; }
    .tip-card { display: block; text-decoration: none; background: var(--card-bg); border-radius: 12px; box-shadow: 0 5px 25px var(--shadow); overflow: hidden; transition: all 0.4s ease; }
    .tip-card:hover { transform: translateY(-10px); box-shadow: 0 15px 40px var(--shadow); }
    .tip-card-image { position: relative; height: 220px; }
    .tip-card-image img { width: 100%; height: 100%; object-fit: cover; }
    .tip-card-category { position: absolute; top: 15px; left: 15px; background: var(--primary-green); color: white; padding: 5px 12px; border-radius: 20px; font-size: 0.8rem; font-weight: 500; }
    .tip-card-content { padding: 25px; }
    .tip-card-title { font-family: var(--font-primary); font-size: 1.4rem; color: var(--text-color); margin-bottom: 15px; }
    .tip-card-link { color: var(--accent-green); font-weight: 600; }
    .tip-card-link i { transition: transform 0.3s; }
    .tip-card:hover .tip-card-link i { transform: translateX(5px); }
</style>
@endpush
