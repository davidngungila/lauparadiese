@extends('layouts.app')

@section('title', 'Bird Watching in Tanzania - 1000+ Bird Species Guide | Lau Paradise Adventures')
@section('description', 'Discover Tanzania\'s incredible birdlife with over 1,000 bird species. Expert bird watching tours, species guides, and the best birding destinations in East Africa.')

@section('content')

<!-- Hero Section -->
<section class="page-hero" style="background: linear-gradient(135deg, rgba(0,0,0,0.6) 0%, rgba(0,0,0,0.4) 100%), url('{{ asset('images/hero-slider/group-of-animals.jpg') }}') center/cover;">
    <div class="container">
        <div class="row align-items-center min-vh-50">
            <div class="col-lg-8 mx-auto text-center text-white" data-aos="fade-up">
                <h1 class="display-3 fw-bold mb-4">Tanzania's Bird Paradise</h1>
                <p class="lead mb-4">Discover over 1,000 bird species in Tanzania's diverse ecosystems. From flamingos to eagles, experience world-class bird watching in Africa's premier destinations.</p>
                <div class="d-flex gap-3 justify-content-center flex-wrap">
                    <a href="#bird-species" class="btn btn-primary btn-lg">
                        <i class="fas fa-dove me-2"></i>Explore Species
                    </a>
                    <a href="#birding-tours" class="btn btn-outline-light btn-lg">
                        <i class="fas fa-binoculars me-2"></i>Birding Tours
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Bird Statistics Section -->
<section class="py-5 bg-light">
    <div class="container">
        <div class="row g-4">
            <div class="col-md-3 col-sm-6" data-aos="fade-up" data-aos-delay="100">
                <div class="text-center p-4 bg-white rounded shadow-sm h-100">
                    <div class="mb-3">
                        <i class="fas fa-dove fa-3x text-primary"></i>
                    </div>
                    <h2 class="display-4 fw-bold text-primary mb-2">1,000+</h2>
                    <p class="text-muted mb-0">Bird Species</p>
                </div>
            </div>
            <div class="col-md-3 col-sm-6" data-aos="fade-up" data-aos-delay="200">
                <div class="text-center p-4 bg-white rounded shadow-sm h-100">
                    <div class="mb-3">
                        <i class="fas fa-map-marked-alt fa-3x text-success"></i>
                    </div>
                    <h2 class="display-4 fw-bold text-success mb-2">50+</h2>
                    <p class="text-muted mb-0">Birding Hotspots</p>
                </div>
            </div>
            <div class="col-md-3 col-sm-6" data-aos="fade-up" data-aos-delay="300">
                <div class="text-center p-4 bg-white rounded shadow-sm h-100">
                    <div class="mb-3">
                        <i class="fas fa-plane fa-3x text-warning"></i>
                    </div>
                    <h2 class="display-4 fw-bold text-warning mb-2">200+</h2>
                    <p class="text-muted mb-0">Migratory Species</p>
                </div>
            </div>
            <div class="col-md-3 col-sm-6" data-aos="fade-up" data-aos-delay="400">
                <div class="text-center p-4 bg-white rounded shadow-sm h-100">
                    <div class="mb-3">
                        <i class="fas fa-star fa-3x text-info"></i>
                    </div>
                    <h2 class="display-4 fw-bold text-info mb-2">30+</h2>
                    <p class="text-muted mb-0">Endemic Species</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Featured Bird Species -->
<section id="bird-species" class="py-5">
    <div class="container">
        <div class="row mb-5">
            <div class="col-lg-8 mx-auto text-center" data-aos="fade-up">
                <h2 class="display-5 fw-bold mb-3">Featured Bird Species</h2>
                <p class="lead text-muted">Discover some of Tanzania's most iconic and sought-after bird species.</p>
            </div>
        </div>
        <div class="row g-4">
            <div class="col-md-6 col-lg-4" data-aos="fade-up" data-aos-delay="100">
                <div class="card h-100 border-0 shadow-sm">
                    <div class="card-body p-4">
                        <div class="mb-3 text-center">
                            <i class="fas fa-dove fa-4x text-primary"></i>
                        </div>
                        <h4 class="fw-bold mb-3">Flamingos</h4>
                        <p class="text-muted mb-3">Witness thousands of pink flamingos at Lake Manyara and other Rift Valley lakes. A spectacular sight for any birdwatcher.</p>
                        <ul class="list-unstyled mb-0">
                            <li><i class="fas fa-check text-success me-2"></i>Greater & Lesser Flamingo</li>
                            <li><i class="fas fa-check text-success me-2"></i>Best seen: Lake Manyara, Lake Natron</li>
                            <li><i class="fas fa-check text-success me-2"></i>Year-round resident</li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-lg-4" data-aos="fade-up" data-aos-delay="200">
                <div class="card h-100 border-0 shadow-sm">
                    <div class="card-body p-4">
                        <div class="mb-3 text-center">
                            <i class="fas fa-eagle fa-4x text-danger"></i>
                        </div>
                        <h4 class="fw-bold mb-3">African Fish Eagle</h4>
                        <p class="text-muted mb-3">Tanzania's national bird, known for its distinctive call and impressive hunting skills. Commonly seen near water bodies.</p>
                        <ul class="list-unstyled mb-0">
                            <li><i class="fas fa-check text-success me-2"></i>National Bird of Tanzania</li>
                            <li><i class="fas fa-check text-success me-2"></i>Best seen: Lake Victoria, rivers</li>
                            <li><i class="fas fa-check text-success me-2"></i>Year-round resident</li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-lg-4" data-aos="fade-up" data-aos-delay="300">
                <div class="card h-100 border-0 shadow-sm">
                    <div class="card-body p-4">
                        <div class="mb-3 text-center">
                            <i class="fas fa-feather fa-4x text-warning"></i>
                        </div>
                        <h4 class="fw-bold mb-3">Secretary Bird</h4>
                        <p class="text-muted mb-3">A unique ground-dwelling bird of prey with long legs and distinctive crest. Often seen walking through grasslands.</p>
                        <ul class="list-unstyled mb-0">
                            <li><i class="fas fa-check text-success me-2"></i>Distinctive appearance</li>
                            <li><i class="fas fa-check text-success me-2"></i>Best seen: Serengeti, Ngorongoro</li>
                            <li><i class="fas fa-check text-success me-2"></i>Year-round resident</li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-lg-4" data-aos="fade-up" data-aos-delay="400">
                <div class="card h-100 border-0 shadow-sm">
                    <div class="card-body p-4">
                        <div class="mb-3 text-center">
                            <i class="fas fa-crow fa-4x text-dark"></i>
                        </div>
                        <h4 class="fw-bold mb-3">Ostrich</h4>
                        <p class="text-muted mb-3">The world's largest bird, commonly seen in Tanzania's national parks. Impressive size and speed make it a favorite.</p>
                        <ul class="list-unstyled mb-0">
                            <li><i class="fas fa-check text-success me-2"></i>World's largest bird</li>
                            <li><i class="fas fa-check text-success me-2"></i>Best seen: Ngorongoro, Serengeti</li>
                            <li><i class="fas fa-check text-success me-2"></i>Year-round resident</li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-lg-4" data-aos="fade-up" data-aos-delay="500">
                <div class="card h-100 border-0 shadow-sm">
                    <div class="card-body p-4">
                        <div class="mb-3 text-center">
                            <i class="fas fa-kiwi-bird fa-4x text-info"></i>
                        </div>
                        <h4 class="fw-bold mb-3">Pelicans</h4>
                        <p class="text-muted mb-3">Large water birds often seen in groups. Both Pink-backed and Great White Pelicans can be spotted in Tanzania.</p>
                        <ul class="list-unstyled mb-0">
                            <li><i class="fas fa-check text-success me-2"></i>Pink-backed & Great White</li>
                            <li><i class="fas fa-check text-success me-2"></i>Best seen: Lake Manyara, lakes</li>
                            <li><i class="fas fa-check text-success me-2"></i>Year-round resident</li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-lg-4" data-aos="fade-up" data-aos-delay="600">
                <div class="card h-100 border-0 shadow-sm">
                    <div class="card-body p-4">
                        <div class="mb-3 text-center">
                            <i class="fas fa-feather-alt fa-4x text-success"></i>
                        </div>
                        <h4 class="fw-bold mb-3">Storks</h4>
                        <p class="text-muted mb-3">Various stork species including Marabou, Yellow-billed, and Saddle-billed storks. Impressive wading birds.</p>
                        <ul class="list-unstyled mb-0">
                            <li><i class="fas fa-check text-success me-2"></i>Multiple species</li>
                            <li><i class="fas fa-check text-success me-2"></i>Best seen: Wetlands, rivers</li>
                            <li><i class="fas fa-check text-success me-2"></i>Year-round & migratory</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Best Birding Destinations -->
<section class="py-5 bg-light">
    <div class="container">
        <div class="row mb-5">
            <div class="col-lg-8 mx-auto text-center" data-aos="fade-up">
                <h2 class="display-5 fw-bold mb-3">Best Birding Destinations</h2>
                <p class="lead text-muted">Tanzania offers exceptional bird watching opportunities across diverse habitats.</p>
            </div>
        </div>
        <div class="row g-4">
            <div class="col-md-6 col-lg-4" data-aos="fade-up" data-aos-delay="100">
                <div class="card h-100 border-0 shadow-sm">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center mb-3">
                            <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 60px; height: 60px;">
                                <i class="fas fa-water fa-2x"></i>
                            </div>
                            <h4 class="fw-bold mb-0">Lake Manyara</h4>
                        </div>
                        <p class="text-muted mb-3">Home to over 400 bird species including flamingos, pelicans, and storks. The alkaline lake attracts thousands of waterbirds.</p>
                        <ul class="list-unstyled mb-0">
                            <li><i class="fas fa-dove text-primary me-2"></i>400+ species</li>
                            <li><i class="fas fa-star text-warning me-2"></i>Flamingo hotspot</li>
                            <li><i class="fas fa-calendar text-info me-2"></i>Year-round birding</li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-lg-4" data-aos="fade-up" data-aos-delay="200">
                <div class="card h-100 border-0 shadow-sm">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center mb-3">
                            <div class="bg-success text-white rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 60px; height: 60px;">
                                <i class="fas fa-tree fa-2x"></i>
                            </div>
                            <h4 class="fw-bold mb-0">Serengeti</h4>
                        </div>
                        <p class="text-muted mb-3">Diverse habitats support over 500 bird species. From grassland birds to raptors, the Serengeti offers incredible diversity.</p>
                        <ul class="list-unstyled mb-0">
                            <li><i class="fas fa-dove text-primary me-2"></i>500+ species</li>
                            <li><i class="fas fa-eagle text-danger me-2"></i>Raptor paradise</li>
                            <li><i class="fas fa-calendar text-info me-2"></i>Year-round birding</li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-lg-4" data-aos="fade-up" data-aos-delay="300">
                <div class="card h-100 border-0 shadow-sm">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center mb-3">
                            <div class="bg-warning text-white rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 60px; height: 60px;">
                                <i class="fas fa-mountain fa-2x"></i>
                            </div>
                            <h4 class="fw-bold mb-0">Ngorongoro Crater</h4>
                        </div>
                        <p class="text-muted mb-3">The crater's unique ecosystem supports diverse birdlife. Excellent for spotting both common and rare species.</p>
                        <ul class="list-unstyled mb-0">
                            <li><i class="fas fa-dove text-primary me-2"></i>300+ species</li>
                            <li><i class="fas fa-star text-warning me-2"></i>Unique habitat</li>
                            <li><i class="fas fa-calendar text-info me-2"></i>Year-round birding</li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-lg-4" data-aos="fade-up" data-aos-delay="400">
                <div class="card h-100 border-0 shadow-sm">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center mb-3">
                            <div class="bg-info text-white rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 60px; height: 60px;">
                                <i class="fas fa-leaf fa-2x"></i>
                            </div>
                            <h4 class="fw-bold mb-0">Tarangire</h4>
                        </div>
                        <p class="text-muted mb-3">Riverine forests and baobab-studded savannas create perfect birding conditions. Over 550 species recorded.</p>
                        <ul class="list-unstyled mb-0">
                            <li><i class="fas fa-dove text-primary me-2"></i>550+ species</li>
                            <li><i class="fas fa-tree text-success me-2"></i>Forest birds</li>
                            <li><i class="fas fa-calendar text-info me-2"></i>Year-round birding</li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-lg-4" data-aos="fade-up" data-aos-delay="500">
                <div class="card h-100 border-0 shadow-sm">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center mb-3">
                            <div class="bg-danger text-white rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 60px; height: 60px;">
                                <i class="fas fa-umbrella-beach fa-2x"></i>
                            </div>
                            <h4 class="fw-bold mb-0">Zanzibar</h4>
                        </div>
                        <p class="text-muted mb-3">Coastal and forest birds thrive on the island. Endemic species and migratory birds make Zanzibar a birding gem.</p>
                        <ul class="list-unstyled mb-0">
                            <li><i class="fas fa-dove text-primary me-2"></i>200+ species</li>
                            <li><i class="fas fa-star text-warning me-2"></i>Endemic species</li>
                            <li><i class="fas fa-calendar text-info me-2"></i>Year-round birding</li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-lg-4" data-aos="fade-up" data-aos-delay="600">
                <div class="card h-100 border-0 shadow-sm">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center mb-3">
                            <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 60px; height: 60px;">
                                <i class="fas fa-water fa-2x"></i>
                            </div>
                            <h4 class="fw-bold mb-0">Selous Game Reserve</h4>
                        </div>
                        <p class="text-muted mb-3">Remote wilderness with excellent bird diversity. River systems and diverse habitats support over 400 bird species.</p>
                        <ul class="list-unstyled mb-0">
                            <li><i class="fas fa-dove text-primary me-2"></i>400+ species</li>
                            <li><i class="fas fa-compass text-info me-2"></i>Remote birding</li>
                            <li><i class="fas fa-calendar text-info me-2"></i>Year-round birding</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Birding Tours -->
<section id="birding-tours" class="py-5">
    <div class="container">
        <div class="row mb-5">
            <div class="col-lg-8 mx-auto text-center" data-aos="fade-up">
                <h2 class="display-5 fw-bold mb-3">Bird Watching Tours</h2>
                <p class="lead text-muted">Join our expert-guided birding tours designed for bird enthusiasts of all levels.</p>
            </div>
        </div>

        @if($birdingTours && $birdingTours->count() > 0)
        <div class="row g-4">
            @foreach($birdingTours as $tour)
            <div class="col-md-6 col-lg-4" data-aos="fade-up" data-aos-delay="{{ $loop->index * 100 }}">
                <div class="card h-100 border-0 shadow-sm tour-card">
                    <div class="position-relative overflow-hidden" style="height: 250px;">
                        <img src="{{ $tour['image'] }}" class="card-img-top w-100 h-100" style="object-fit: cover;" alt="{{ $tour['name'] }}">
                        @if($tour['is_featured'])
                        <span class="badge bg-warning position-absolute top-0 end-0 m-2">
                            <i class="fas fa-star me-1"></i>Featured
                        </span>
                        @endif
                        <div class="position-absolute bottom-0 start-0 end-0 p-3 text-white" style="background: linear-gradient(to top, rgba(0,0,0,0.8), transparent);">
                            <div class="d-flex align-items-center gap-2">
                                <i class="fas fa-clock"></i>
                                <span>{{ $tour['duration_days'] }} Days</span>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <h5 class="card-title fw-bold mb-2">{{ $tour['name'] }}</h5>
                        <p class="card-text text-muted small mb-3">{{ $tour['description'] }}</p>
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <div>
                                <span class="text-primary fw-bold fs-5">${{ number_format($tour['starting_price']) }}</span>
                                <span class="text-muted small">/person</span>
                            </div>
                            <div class="text-warning">
                                @for($i = 0; $i < 5; $i++)
                                    <i class="fas fa-star{{ $i < floor($tour['rating']) ? '' : '-o' }}"></i>
                                @endfor
                                <span class="text-muted small ms-1">({{ $tour['rating'] }})</span>
                            </div>
                        </div>
                        <div class="d-flex gap-2">
                            <a href="{{ route('tours.show', $tour['slug']) }}" class="btn btn-primary flex-fill">
                                <i class="fas fa-eye me-1"></i>View Details
                            </a>
                            <a href="{{ route('booking') }}?tour={{ $tour['id'] }}" class="btn btn-outline-primary">
                                <i class="fas fa-calendar-check"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        @else
        <div class="row">
            <div class="col-12 text-center py-5" data-aos="fade-up">
                <i class="fas fa-dove fa-4x text-muted mb-4"></i>
                <h3 class="fw-bold mb-3">Bird Watching Tours Coming Soon</h3>
                <p class="text-muted mb-4">We're adding specialized birding tours. In the meantime, check out our general tours which include excellent bird watching opportunities.</p>
                <a href="{{ route('tours.index') }}" class="btn btn-primary">
                    <i class="fas fa-arrow-left me-2"></i>Browse All Tours
                </a>
            </div>
        </div>
        @endif
    </div>
</section>

<!-- Bird Watching Tips -->
<section class="py-5 bg-light">
    <div class="container">
        <div class="row">
            <div class="col-lg-10 mx-auto">
                <div class="card border-0 shadow-sm" data-aos="fade-up">
                    <div class="card-body p-4 p-md-5">
                        <h2 class="display-6 fw-bold mb-4 text-center">Bird Watching Tips</h2>
                        <div class="row g-4">
                            <div class="col-md-6">
                                <div class="d-flex gap-3 mb-4">
                                    <div class="flex-shrink-0">
                                        <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                                            <i class="fas fa-binoculars"></i>
                                        </div>
                                    </div>
                                    <div>
                                        <h5 class="fw-bold mb-2">Bring Quality Binoculars</h5>
                                        <p class="text-muted small mb-0">Invest in good binoculars (8x42 or 10x42) for clear, detailed views of birds at various distances.</p>
                                    </div>
                                </div>
                                <div class="d-flex gap-3 mb-4">
                                    <div class="flex-shrink-0">
                                        <div class="bg-success text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                                            <i class="fas fa-clock"></i>
                                        </div>
                                    </div>
                                    <div>
                                        <h5 class="fw-bold mb-2">Early Morning & Late Afternoon</h5>
                                        <p class="text-muted small mb-0">Birds are most active during these times. Plan your birding activities accordingly for the best sightings.</p>
                                    </div>
                                </div>
                                <div class="d-flex gap-3">
                                    <div class="flex-shrink-0">
                                        <div class="bg-warning text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                                            <i class="fas fa-book"></i>
                                        </div>
                                    </div>
                                    <div>
                                        <h5 class="fw-bold mb-2">Bird Guide Book</h5>
                                        <p class="text-muted small mb-0">Carry a field guide to help identify species. Our guides also provide expert identification assistance.</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="d-flex gap-3 mb-4">
                                    <div class="flex-shrink-0">
                                        <div class="bg-info text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                                            <i class="fas fa-camera"></i>
                                        </div>
                                    </div>
                                    <div>
                                        <h5 class="fw-bold mb-2">Photography Equipment</h5>
                                        <p class="text-muted small mb-0">A telephoto lens (300mm+) is ideal for bird photography. Many birds are shy, so distance is important.</p>
                                    </div>
                                </div>
                                <div class="d-flex gap-3 mb-4">
                                    <div class="flex-shrink-0">
                                        <div class="bg-danger text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                                            <i class="fas fa-tshirt"></i>
                                        </div>
                                    </div>
                                    <div>
                                        <h5 class="fw-bold mb-2">Wear Neutral Colors</h5>
                                        <p class="text-muted small mb-0">Avoid bright colors that might startle birds. Earth tones and greens blend better with nature.</p>
                                    </div>
                                </div>
                                <div class="d-flex gap-3">
                                    <div class="flex-shrink-0">
                                        <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                                            <i class="fas fa-volume-down"></i>
                                        </div>
                                    </div>
                                    <div>
                                        <h5 class="fw-bold mb-2">Stay Quiet & Patient</h5>
                                        <p class="text-muted small mb-0">Move slowly and quietly. Patience is key - wait and observe, and birds will reveal themselves.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Seasonal Birding -->
<section class="py-5">
    <div class="container">
        <div class="row mb-5">
            <div class="col-lg-8 mx-auto text-center" data-aos="fade-up">
                <h2 class="display-5 fw-bold mb-3">Best Time for Bird Watching</h2>
                <p class="lead text-muted">Tanzania offers excellent birding year-round, with seasonal variations in species diversity.</p>
            </div>
        </div>
        <div class="row g-4">
            <div class="col-md-6 col-lg-3" data-aos="fade-up" data-aos-delay="100">
                <div class="card h-100 border-0 shadow-sm text-center p-4">
                    <div class="mb-3">
                        <i class="fas fa-sun fa-3x text-warning"></i>
                    </div>
                    <h5 class="fw-bold mb-3">Dry Season<br><small class="text-muted">(June - October)</small></h5>
                    <p class="text-muted small mb-0">Excellent visibility, birds gather at water sources. Best for photography and clear sightings.</p>
                </div>
            </div>
            <div class="col-md-6 col-lg-3" data-aos="fade-up" data-aos-delay="200">
                <div class="card h-100 border-0 shadow-sm text-center p-4">
                    <div class="mb-3">
                        <i class="fas fa-cloud-rain fa-3x text-info"></i>
                    </div>
                    <h5 class="fw-bold mb-3">Wet Season<br><small class="text-muted">(November - May)</small></h5>
                    <p class="text-muted small mb-0">Migratory birds arrive, breeding season begins. Highest species diversity and nesting activity.</p>
                </div>
            </div>
            <div class="col-md-6 col-lg-3" data-aos="fade-up" data-aos-delay="300">
                <div class="card h-100 border-0 shadow-sm text-center p-4">
                    <div class="mb-3">
                        <i class="fas fa-plane fa-3x text-primary"></i>
                    </div>
                    <h5 class="fw-bold mb-3">Migration Period<br><small class="text-muted">(October - April)</small></h5>
                    <p class="text-muted small mb-0">European and Asian migrants arrive. See species not present during other times of year.</p>
                </div>
            </div>
            <div class="col-md-6 col-lg-3" data-aos="fade-up" data-aos-delay="400">
                <div class="card h-100 border-0 shadow-sm text-center p-4">
                    <div class="mb-3">
                        <i class="fas fa-calendar-check fa-3x text-success"></i>
                    </div>
                    <h5 class="fw-bold mb-3">Year-Round<br><small class="text-muted">Any Time</small></h5>
                    <p class="text-muted small mb-0">Resident species are always present. Tanzania offers great birding opportunities throughout the year.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- CTA Section -->
<section class="py-5 bg-primary text-white">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-8" data-aos="fade-right">
                <h2 class="display-6 fw-bold mb-3">Ready to Start Bird Watching?</h2>
                <p class="lead mb-0">Contact our birding specialists to plan your perfect bird watching adventure in Tanzania.</p>
            </div>
            <div class="col-lg-4 text-lg-end" data-aos="fade-left">
                <a href="{{ route('contact') }}" class="btn btn-light btn-lg me-2">
                    <i class="fas fa-envelope me-2"></i>Get in Touch
                </a>
                <a href="{{ route('tours.index') }}?activity=birding" class="btn btn-outline-light btn-lg">
                    <i class="fas fa-binoculars me-2"></i>View Tours
                </a>
            </div>
        </div>
    </div>
</section>

@push('styles')
<style>
.page-hero {
    padding: 120px 0 80px;
    position: relative;
}

.min-vh-50 {
    min-height: 50vh;
}

.tour-card {
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.tour-card:hover {
    transform: translateY(-10px);
    box-shadow: 0 15px 40px rgba(0,0,0,0.15) !important;
}

.tour-card img {
    transition: transform 0.5s ease;
}

.tour-card:hover img {
    transform: scale(1.1);
}

.card {
    transition: transform 0.3s ease;
}

.card:hover {
    transform: translateY(-5px);
}
</style>
@endpush

@endsection





