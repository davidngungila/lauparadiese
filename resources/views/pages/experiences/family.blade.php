@extends('layouts.app')

@section('title', 'Family Experiences - Kid-Friendly Tanzania Tours | Lau Paradise Adventures')
@section('description', 'Discover family-friendly tours and experiences in Tanzania. Safe, educational, and fun adventures designed for families with children of all ages.')

@section('content')

<!-- Hero Section -->
<section class="page-hero" style="background: linear-gradient(135deg, rgba(0,0,0,0.6) 0%, rgba(0,0,0,0.4) 100%), url('{{ asset('images/hero-slider/animal-movement.jpg') }}') center/cover;">
    <div class="container">
        <div class="row align-items-center min-vh-50">
            <div class="col-lg-8 mx-auto text-center text-white" data-aos="fade-up">
                <h1 class="display-3 fw-bold mb-4">Family Adventures in Tanzania</h1>
                <p class="lead mb-4">Create unforgettable memories with your family. Safe, educational, and fun experiences designed for families with children of all ages.</p>
                <div class="d-flex gap-3 justify-content-center flex-wrap">
                    <a href="#family-tours" class="btn btn-primary btn-lg">
                        <i class="fas fa-users me-2"></i>Explore Tours
                    </a>
                    <a href="#why-family" class="btn btn-outline-light btn-lg">
                        <i class="fas fa-heart me-2"></i>Why Family Tours
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Why Family Tours Section -->
<section id="why-family" class="py-5">
    <div class="container">
        <div class="row mb-5">
            <div class="col-lg-8 mx-auto text-center" data-aos="fade-up">
                <h2 class="display-5 fw-bold mb-3">Why Choose Family Tours?</h2>
                <p class="lead text-muted">We understand that traveling with children requires special planning and care.</p>
            </div>
        </div>
        <div class="row g-4">
            <div class="col-md-6 col-lg-4" data-aos="fade-up" data-aos-delay="100">
                <div class="card h-100 border-0 shadow-sm">
                    <div class="card-body p-4">
                        <div class="mb-3">
                            <i class="fas fa-shield-alt fa-3x text-primary"></i>
                        </div>
                        <h4 class="fw-bold mb-3">Safe & Secure</h4>
                        <p class="text-muted">All activities are carefully selected for family safety. Experienced guides ensure a secure environment for children.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-lg-4" data-aos="fade-up" data-aos-delay="200">
                <div class="card h-100 border-0 shadow-sm">
                    <div class="card-body p-4">
                        <div class="mb-3">
                            <i class="fas fa-graduation-cap fa-3x text-success"></i>
                        </div>
                        <h4 class="fw-bold mb-3">Educational</h4>
                        <p class="text-muted">Kids learn about wildlife, nature, and culture through interactive experiences that make learning fun.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-lg-4" data-aos="fade-up" data-aos-delay="300">
                <div class="card h-100 border-0 shadow-sm">
                    <div class="card-body p-4">
                        <div class="mb-3">
                            <i class="fas fa-smile fa-3x text-warning"></i>
                        </div>
                        <h4 class="fw-bold mb-3">Kid-Friendly Activities</h4>
                        <p class="text-muted">Age-appropriate activities designed to keep children engaged and entertained throughout the journey.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-lg-4" data-aos="fade-up" data-aos-delay="400">
                <div class="card h-100 border-0 shadow-sm">
                    <div class="card-body p-4">
                        <div class="mb-3">
                            <i class="fas fa-clock fa-3x text-info"></i>
                        </div>
                        <h4 class="fw-bold mb-3">Flexible Schedule</h4>
                        <p class="text-muted">Pace your tour to suit your family's needs with breaks and rest times built into the itinerary.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-lg-4" data-aos="fade-up" data-aos-delay="500">
                <div class="card h-100 border-0 shadow-sm">
                    <div class="card-body p-4">
                        <div class="mb-3">
                            <i class="fas fa-bed fa-3x text-danger"></i>
                        </div>
                        <h4 class="fw-bold mb-3">Family Accommodations</h4>
                        <p class="text-muted">Comfortable family rooms and child-friendly facilities at all accommodations.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-lg-4" data-aos="fade-up" data-aos-delay="600">
                <div class="card h-100 border-0 shadow-sm">
                    <div class="card-body p-4">
                        <div class="mb-3">
                            <i class="fas fa-heart fa-3x text-primary"></i>
                        </div>
                        <h4 class="fw-bold mb-3">Memorable Moments</h4>
                        <p class="text-muted">Create lasting family memories with experiences that bring your family closer together.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Family Tours Grid -->
<section id="family-tours" class="py-5 bg-light">
    <div class="container">
        <div class="row mb-5">
            <div class="col-lg-8 mx-auto text-center" data-aos="fade-up">
                <h2 class="display-5 fw-bold mb-3">Family-Friendly Tours</h2>
                <p class="lead text-muted">Browse our specially curated tours designed for families.</p>
            </div>
        </div>

        @if($familyTours && $familyTours->count() > 0)
        <div class="row g-4">
            @foreach($familyTours as $tour)
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
                <i class="fas fa-users fa-4x text-muted mb-4"></i>
                <h3 class="fw-bold mb-3">No Family Tours Available</h3>
                <p class="text-muted mb-4">We're working on adding more family-friendly tours. Check back soon!</p>
                <a href="{{ route('tours.index') }}" class="btn btn-primary">
                    <i class="fas fa-arrow-left me-2"></i>Browse All Tours
                </a>
            </div>
        </div>
        @endif
    </div>
</section>

<!-- Family Activities Section -->
<section class="py-5">
    <div class="container">
        <div class="row mb-5">
            <div class="col-lg-8 mx-auto text-center" data-aos="fade-up">
                <h2 class="display-5 fw-bold mb-3">Family Activities</h2>
                <p class="lead text-muted">Engaging activities that the whole family will enjoy.</p>
            </div>
        </div>
        <div class="row g-4">
            <div class="col-md-6 col-lg-3" data-aos="fade-up" data-aos-delay="100">
                <div class="text-center p-4 bg-light rounded">
                    <i class="fas fa-binoculars fa-3x text-primary mb-3"></i>
                    <h5 class="fw-bold">Wildlife Viewing</h5>
                    <p class="text-muted small mb-0">Safe game drives with expert guides who make learning fun for kids.</p>
                </div>
            </div>
            <div class="col-md-6 col-lg-3" data-aos="fade-up" data-aos-delay="200">
                <div class="text-center p-4 bg-light rounded">
                    <i class="fas fa-campground fa-3x text-success mb-3"></i>
                    <h5 class="fw-bold">Bush Walks</h5>
                    <p class="text-muted small mb-0">Guided nature walks designed for all ages with educational stops.</p>
                </div>
            </div>
            <div class="col-md-6 col-lg-3" data-aos="fade-up" data-aos-delay="300">
                <div class="text-center p-4 bg-light rounded">
                    <i class="fas fa-users fa-3x text-warning mb-3"></i>
                    <h5 class="fw-bold">Cultural Visits</h5>
                    <p class="text-muted small mb-0">Interactive visits to local communities and cultural centers.</p>
                </div>
            </div>
            <div class="col-md-6 col-lg-3" data-aos="fade-up" data-aos-delay="400">
                <div class="text-center p-4 bg-light rounded">
                    <i class="fas fa-swimming-pool fa-3x text-info mb-3"></i>
                    <h5 class="fw-bold">Beach Activities</h5>
                    <p class="text-muted small mb-0">Safe swimming areas and beach games for children.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Tips for Family Travel -->
<section class="py-5 bg-light">
    <div class="container">
        <div class="row">
            <div class="col-lg-10 mx-auto">
                <div class="card border-0 shadow-sm" data-aos="fade-up">
                    <div class="card-body p-4 p-md-5">
                        <h2 class="display-6 fw-bold mb-4 text-center">Tips for Family Travel in Tanzania</h2>
                        <div class="row g-4">
                            <div class="col-md-6">
                                <div class="d-flex gap-3">
                                    <div class="flex-shrink-0">
                                        <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                            <i class="fas fa-check"></i>
                                        </div>
                                    </div>
                                    <div>
                                        <h5 class="fw-bold mb-2">Health & Safety</h5>
                                        <p class="text-muted small mb-0">Ensure all family members have required vaccinations. Pack a first-aid kit and any necessary medications.</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="d-flex gap-3">
                                    <div class="flex-shrink-0">
                                        <div class="bg-success text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                            <i class="fas fa-check"></i>
                                        </div>
                                    </div>
                                    <div>
                                        <h5 class="fw-bold mb-2">Age-Appropriate Activities</h5>
                                        <p class="text-muted small mb-0">Choose activities suitable for your children's ages. Our consultants can help plan the perfect itinerary.</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="d-flex gap-3">
                                    <div class="flex-shrink-0">
                                        <div class="bg-warning text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                            <i class="fas fa-check"></i>
                                        </div>
                                    </div>
                                    <div>
                                        <h5 class="fw-bold mb-2">Packing Essentials</h5>
                                        <p class="text-muted small mb-0">Pack comfortable clothing, sun protection, insect repellent, and entertainment for travel times.</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="d-flex gap-3">
                                    <div class="flex-shrink-0">
                                        <div class="bg-info text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                            <i class="fas fa-check"></i>
                                        </div>
                                    </div>
                                    <div>
                                        <h5 class="fw-bold mb-2">Flexible Itinerary</h5>
                                        <p class="text-muted small mb-0">Allow for rest days and flexible scheduling to accommodate children's needs and energy levels.</p>
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

<!-- CTA Section -->
<section class="py-5 bg-primary text-white">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-8" data-aos="fade-right">
                <h2 class="display-6 fw-bold mb-3">Ready to Plan Your Family Adventure?</h2>
                <p class="lead mb-0">Contact our family travel specialists to create the perfect itinerary for your family.</p>
            </div>
            <div class="col-lg-4 text-lg-end" data-aos="fade-left">
                <a href="{{ route('contact') }}" class="btn btn-light btn-lg">
                    <i class="fas fa-envelope me-2"></i>Get in Touch
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
</style>
@endpush

@endsection





