@extends('layouts.app')

@section('title', 'Custom Tours - Design Your Perfect Tanzania Adventure | Lau Paradise Adventures')
@section('description', 'Create your dream Tanzania tour with our custom tour builder. Choose destinations, activities, and accommodations to design a personalized adventure tailored to your preferences.')

@section('content')

<!-- Hero Section -->
<section class="custom-tours-hero position-relative overflow-hidden">
    <div class="hero-background" style="background: linear-gradient(135deg, rgba(0,0,0,0.75) 0%, rgba(0,0,0,0.6) 100%), url('{{ asset('images/hero-slider/safari-adventure.jpg') }}') center/cover;"></div>
    <div class="hero-overlay"></div>
    <div class="container position-relative" style="z-index: 2;">
        <div class="row align-items-center min-vh-60 py-5">
            <div class="col-lg-10 mx-auto text-center text-white">
                <div class="hero-badge mb-4" data-aos="fade-up">
                    <span class="badge bg-primary bg-opacity-25 border border-primary border-opacity-50 px-4 py-2 rounded-pill">
                        <i class="ri-magic-line me-2"></i>Personalized Travel Experience
                    </span>
                </div>
                <h1 class="display-2 fw-bold mb-4" data-aos="fade-up" data-aos-delay="100">
                    Design Your Perfect<br>
                    <span class="text-primary">Tanzania Adventure</span>
                </h1>
                <p class="lead fs-4 mb-5" data-aos="fade-up" data-aos-delay="200">
                    Create a personalized tour tailored to your interests, budget, and travel style.<br>
                    Our expert travel consultants will help bring your dream journey to life.
                </p>
                <div class="d-flex gap-3 justify-content-center flex-wrap" data-aos="fade-up" data-aos-delay="300">
                    <a href="#tour-builder" class="btn btn-primary btn-lg px-5 py-3 rounded-pill shadow-lg">
                        <i class="ri-magic-line me-2"></i>Start Building Your Tour
                    </a>
                    <a href="#how-it-works" class="btn btn-outline-light btn-lg px-5 py-3 rounded-pill">
                        <i class="ri-information-line me-2"></i>How It Works
                    </a>
                </div>
                <div class="mt-5 pt-4" data-aos="fade-up" data-aos-delay="400">
                    <div class="row g-4 text-center">
                        <div class="col-md-3 col-6">
                            <div class="stat-item">
                                <h3 class="display-6 fw-bold text-primary mb-1">500+</h3>
                                <p class="text-white-50 mb-0 small">Custom Tours Created</p>
                            </div>
                        </div>
                        <div class="col-md-3 col-6">
                            <div class="stat-item">
                                <h3 class="display-6 fw-bold text-primary mb-1">98%</h3>
                                <p class="text-white-50 mb-0 small">Satisfaction Rate</p>
                            </div>
                        </div>
                        <div class="col-md-3 col-6">
                            <div class="stat-item">
                                <h3 class="display-6 fw-bold text-primary mb-1">24/7</h3>
                                <p class="text-white-50 mb-0 small">Expert Support</p>
                            </div>
                        </div>
                        <div class="col-md-3 col-6">
                            <div class="stat-item">
                                <h3 class="display-6 fw-bold text-primary mb-1">48hr</h3>
                                <p class="text-white-50 mb-0 small">Response Time</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="hero-shape-bottom">
        <svg viewBox="0 0 1200 120" preserveAspectRatio="none">
            <path d="M0,0 C300,120 900,0 1200,120 L1200,120 L0,120 Z" fill="#fff"></path>
        </svg>
    </div>
</section>

<!-- How It Works Section -->
<section id="how-it-works" class="py-5 bg-white">
    <div class="container">
        <div class="row mb-5">
            <div class="col-lg-8 mx-auto text-center">
                <span class="badge bg-primary bg-opacity-10 text-primary px-3 py-2 rounded-pill mb-3">Simple Process</span>
                <h2 class="display-4 fw-bold mb-3">How It Works</h2>
                <p class="lead text-muted">Creating your custom tour is simple and fun. Follow these easy steps:</p>
            </div>
        </div>
        <div class="row g-4 mb-5">
            <div class="col-lg-3 col-md-6" data-aos="fade-up" data-aos-delay="100">
                <div class="step-card h-100 text-center p-4 position-relative">
                    <div class="step-number">01</div>
                    <div class="step-icon mb-4">
                        <div class="icon-wrapper bg-primary bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center" style="width: 100px; height: 100px;">
                            <i class="ri-map-pin-line text-primary" style="font-size: 2.5rem;"></i>
                        </div>
                    </div>
                    <h4 class="fw-bold mb-3">Choose Destinations</h4>
                    <p class="text-muted">Select the destinations you want to visit in Tanzania. Mix and match to create your ideal route.</p>
                </div>
            </div>
            <div class="col-lg-3 col-md-6" data-aos="fade-up" data-aos-delay="200">
                <div class="step-card h-100 text-center p-4 position-relative">
                    <div class="step-number">02</div>
                    <div class="step-icon mb-4">
                        <div class="icon-wrapper bg-success bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center" style="width: 100px; height: 100px;">
                            <i class="ri-hotel-line text-success" style="font-size: 2.5rem;"></i>
                        </div>
                    </div>
                    <h4 class="fw-bold mb-3">Select Activities</h4>
                    <p class="text-muted">Pick activities that interest you - from wildlife safaris to cultural experiences and adventure activities.</p>
                </div>
            </div>
            <div class="col-lg-3 col-md-6" data-aos="fade-up" data-aos-delay="300">
                <div class="step-card h-100 text-center p-4 position-relative">
                    <div class="step-number">03</div>
                    <div class="step-icon mb-4">
                        <div class="icon-wrapper bg-warning bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center" style="width: 100px; height: 100px;">
                            <i class="ri-settings-3-line text-warning" style="font-size: 2.5rem;"></i>
                        </div>
                    </div>
                    <h4 class="fw-bold mb-3">Set Preferences</h4>
                    <p class="text-muted">Choose your accommodation level, travel dates, group size, and budget. We'll customize everything for you.</p>
                </div>
            </div>
            <div class="col-lg-3 col-md-6" data-aos="fade-up" data-aos-delay="400">
                <div class="step-card h-100 text-center p-4 position-relative">
                    <div class="step-number">04</div>
                    <div class="step-icon mb-4">
                        <div class="icon-wrapper bg-info bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center" style="width: 100px; height: 100px;">
                            <i class="ri-checkbox-circle-line text-info" style="font-size: 2.5rem;"></i>
                        </div>
                    </div>
                    <h4 class="fw-bold mb-3">Get Your Proposal</h4>
                    <p class="text-muted">Receive a detailed, personalized itinerary and quote within 48 hours. Review, adjust, and confirm!</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Custom Tour Builder Form -->
<section id="tour-builder" class="py-5 bg-light">
    <div class="container">
        <div class="row mb-5">
            <div class="col-lg-8 mx-auto text-center">
                <span class="badge bg-primary bg-opacity-10 text-primary px-3 py-2 rounded-pill mb-3">Tour Builder</span>
                <h2 class="display-4 fw-bold mb-3">Build Your Custom Tour</h2>
                <p class="lead text-muted">Fill out the form below and our travel experts will create a personalized itinerary just for you.</p>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-10 mx-auto">
                <!-- Progress Bar -->
                <div class="card border-0 shadow-sm mb-4" id="progressCard">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center justify-content-between mb-3">
                            <span class="text-muted small">Progress</span>
                            <span class="text-muted small" id="progressText">Step 1 of 5</span>
                        </div>
                        <div class="progress" style="height: 8px;">
                            <div class="progress-bar bg-primary progress-bar-striped progress-bar-animated" 
                                 role="progressbar" 
                                 id="progressBar"
                                 style="width: 20%"
                                 aria-valuenow="20" 
                                 aria-valuemin="0" 
                                 aria-valuemax="100"></div>
                        </div>
                    </div>
                </div>

                <!-- Multi-Step Form -->
                <div class="card border-0 shadow-lg" data-aos="fade-up">
                    <div class="card-body p-4 p-md-5">
                        <form id="customTourForm" action="{{ route('contact.submit') }}" method="POST">
                            @csrf
                            <input type="hidden" name="subject" value="Custom Tour Request">
                            
                            <!-- Step 1: Personal Information -->
                            <div class="form-step active" data-step="1">
                                <div class="step-header mb-4">
                                    <div class="d-flex align-items-center mb-3">
                                        <div class="step-indicator bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 50px; height: 50px;">
                                            <i class="ri-user-line fs-5"></i>
                                        </div>
                                        <div>
                                            <h3 class="h4 fw-bold mb-1">Your Information</h3>
                                            <p class="text-muted mb-0 small">Tell us about yourself</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="row g-4">
                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold">
                                            Full Name <span class="text-danger">*</span>
                                        </label>
                                        <div class="input-group input-group-lg">
                                            <span class="input-group-text bg-light border-end-0">
                                                <i class="ri-user-line text-primary"></i>
                                            </span>
                                            <input type="text" name="name" class="form-control border-start-0" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold">
                                            Email Address <span class="text-danger">*</span>
                                        </label>
                                        <div class="input-group input-group-lg">
                                            <span class="input-group-text bg-light border-end-0">
                                                <i class="ri-mail-line text-primary"></i>
                                            </span>
                                            <input type="email" name="email" class="form-control border-start-0" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold">
                                            Phone Number <span class="text-danger">*</span>
                                        </label>
                                        <div class="input-group input-group-lg">
                                            <span class="input-group-text bg-light border-end-0">
                                                <i class="ri-phone-line text-primary"></i>
                                            </span>
                                            <input type="tel" name="phone" class="form-control border-start-0" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold">Country</label>
                                        <div class="input-group input-group-lg">
                                            <span class="input-group-text bg-light border-end-0">
                                                <i class="ri-global-line text-primary"></i>
                                            </span>
                                            <input type="text" name="country" class="form-control border-start-0" placeholder="e.g., United States">
                                        </div>
                                    </div>
                                </div>
                                <div class="d-flex justify-content-end mt-4">
                                    <button type="button" class="btn btn-primary btn-lg px-5" onclick="nextStep()">
                                        Next Step <i class="ri-arrow-right-line ms-2"></i>
                                    </button>
                                </div>
                            </div>

                            <!-- Step 2: Travel Preferences -->
                            <div class="form-step" data-step="2">
                                <div class="step-header mb-4">
                                    <div class="d-flex align-items-center mb-3">
                                        <div class="step-indicator bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 50px; height: 50px;">
                                            <i class="ri-calendar-line fs-5"></i>
                                        </div>
                                        <div>
                                            <h3 class="h4 fw-bold mb-1">Travel Preferences</h3>
                                            <p class="text-muted mb-0 small">When and how do you want to travel?</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="row g-4">
                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold">
                                            Preferred Travel Dates <span class="text-danger">*</span>
                                        </label>
                                        <div class="input-group input-group-lg">
                                            <span class="input-group-text bg-light border-end-0">
                                                <i class="ri-calendar-2-line text-primary"></i>
                                            </span>
                                            <input type="text" name="travel_dates" class="form-control border-start-0" 
                                                   placeholder="e.g., March 15-25, 2025" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold">
                                            Duration (Days) <span class="text-danger">*</span>
                                        </label>
                                        <select name="duration" class="form-select form-select-lg" required>
                                            <option value="">Select Duration</option>
                                            <option value="3-5">3-5 Days</option>
                                            <option value="6-8">6-8 Days</option>
                                            <option value="9-12">9-12 Days</option>
                                            <option value="13-15">13-15 Days</option>
                                            <option value="16+">16+ Days</option>
                                        </select>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold">
                                            Number of Travelers <span class="text-danger">*</span>
                                        </label>
                                        <div class="input-group input-group-lg">
                                            <span class="input-group-text bg-light border-end-0">
                                                <i class="ri-group-line text-primary"></i>
                                            </span>
                                            <input type="number" name="travelers" class="form-control border-start-0" 
                                                   min="1" max="50" value="2" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold">Budget Range (USD)</label>
                                        <select name="budget" class="form-select form-select-lg">
                                            <option value="">Select Budget Range</option>
                                            <option value="under-2000">Under $2,000 per person</option>
                                            <option value="2000-5000">$2,000 - $5,000 per person</option>
                                            <option value="5000-10000">$5,000 - $10,000 per person</option>
                                            <option value="10000-20000">$10,000 - $20,000 per person</option>
                                            <option value="20000+">$20,000+ per person</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="d-flex justify-content-between mt-4">
                                    <button type="button" class="btn btn-outline-secondary btn-lg px-4" onclick="prevStep()">
                                        <i class="ri-arrow-left-line me-2"></i>Previous
                                    </button>
                                    <button type="button" class="btn btn-primary btn-lg px-5" onclick="nextStep()">
                                        Next Step <i class="ri-arrow-right-line ms-2"></i>
                                    </button>
                                </div>
                            </div>

                            <!-- Step 3: Destinations -->
                            <div class="form-step" data-step="3">
                                <div class="step-header mb-4">
                                    <div class="d-flex align-items-center mb-3">
                                        <div class="step-indicator bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 50px; height: 50px;">
                                            <i class="ri-map-pin-line fs-5"></i>
                                        </div>
                                        <div>
                                            <h3 class="h4 fw-bold mb-1">Destinations of Interest</h3>
                                            <p class="text-muted mb-0 small">Select the places you want to visit</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="row g-3" id="destinationsGrid">
                                    @foreach($destinations as $destination)
                                    <div class="col-lg-3 col-md-4 col-sm-6">
                                        <div class="destination-card">
                                            <input type="checkbox" name="destinations[]" value="{{ $destination->id }}" 
                                                   id="dest_{{ $destination->id }}" class="destination-checkbox d-none">
                                            <label for="dest_{{ $destination->id }}" class="destination-label">
                                                <div class="card h-100 border-2 cursor-pointer transition-all">
                                                    <div class="card-body text-center p-4">
                                                        <div class="mb-3">
                                                            <i class="ri-map-pin-3-line text-primary" style="font-size: 2rem;"></i>
                                                        </div>
                                                        <h6 class="fw-bold mb-0">{{ $destination->name }}</h6>
                                                    </div>
                                                </div>
                                            </label>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                                <div class="d-flex justify-content-between mt-4">
                                    <button type="button" class="btn btn-outline-secondary btn-lg px-4" onclick="prevStep()">
                                        <i class="ri-arrow-left-line me-2"></i>Previous
                                    </button>
                                    <button type="button" class="btn btn-primary btn-lg px-5" onclick="nextStep()">
                                        Next Step <i class="ri-arrow-right-line ms-2"></i>
                                    </button>
                                </div>
                            </div>

                            <!-- Step 4: Activities & Categories -->
                            <div class="form-step" data-step="4">
                                <div class="step-header mb-4">
                                    <div class="d-flex align-items-center mb-3">
                                        <div class="step-indicator bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 50px; height: 50px;">
                                            <i class="ri-hotel-line fs-5"></i>
                                        </div>
                                        <div>
                                            <h3 class="h4 fw-bold mb-1">Activities & Interests</h3>
                                            <p class="text-muted mb-0 small">What experiences are you looking for?</p>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Tour Categories -->
                                @if($categories->count() > 0)
                                <div class="mb-4">
                                    <h5 class="fw-bold mb-3">Tour Categories</h5>
                                    <div class="row g-3">
                                        @foreach($categories as $category)
                                        <div class="col-lg-3 col-md-4 col-sm-6">
                                            <div class="activity-card">
                                                <input type="checkbox" name="categories[]" value="{{ $category->id }}" 
                                                       id="cat_{{ $category->id }}" class="activity-checkbox d-none">
                                                <label for="cat_{{ $category->id }}" class="activity-label">
                                                    <div class="card h-100 border-2 cursor-pointer transition-all">
                                                        <div class="card-body text-center p-3">
                                                            <h6 class="fw-bold mb-0 small">{{ $category->name }}</h6>
                                                        </div>
                                                    </div>
                                            </label>
                                            </div>
                                        </div>
                                        @endforeach
                                    </div>
                                </div>
                                @endif

                                <!-- Activities -->
                                <div>
                                    <h5 class="fw-bold mb-3">Specific Activities</h5>
                                    <div class="row g-3">
                                        @php
                                        $activities = [
                                            ['value' => 'wildlife-safari', 'icon' => 'ri-camera-line', 'label' => 'Wildlife Safari'],
                                            ['value' => 'mountain-climbing', 'icon' => 'ri-mountain-line', 'label' => 'Mountain Climbing'],
                                            ['value' => 'beach-relaxation', 'icon' => 'ri-sun-line', 'label' => 'Beach & Relaxation'],
                                            ['value' => 'cultural-tours', 'icon' => 'ri-group-line', 'label' => 'Cultural Tours'],
                                            ['value' => 'photography', 'icon' => 'ri-camera-3-line', 'label' => 'Photography'],
                                            ['value' => 'bird-watching', 'icon' => 'ri-bird-line', 'label' => 'Bird Watching'],
                                            ['value' => 'hot-air-balloon', 'icon' => 'ri-flight-takeoff-line', 'label' => 'Hot Air Balloon'],
                                            ['value' => 'walking-safari', 'icon' => 'ri-walk-line', 'label' => 'Walking Safari'],
                                            ['value' => 'night-safari', 'icon' => 'ri-moon-line', 'label' => 'Night Safari'],
                                            ['value' => 'diving-snorkeling', 'icon' => 'ri-water-percent-line', 'label' => 'Diving & Snorkeling'],
                                        ];
                                        @endphp
                                        @foreach($activities as $activity)
                                        <div class="col-lg-3 col-md-4 col-sm-6">
                                            <div class="activity-card">
                                                <input type="checkbox" name="activities[]" value="{{ $activity['value'] }}" 
                                                       id="act_{{ $activity['value'] }}" class="activity-checkbox d-none">
                                                <label for="act_{{ $activity['value'] }}" class="activity-label">
                                                    <div class="card h-100 border-2 cursor-pointer transition-all">
                                                        <div class="card-body text-center p-3">
                                                            <i class="{{ $activity['icon'] }} text-primary mb-2 d-block" style="font-size: 1.5rem;"></i>
                                                            <h6 class="fw-bold mb-0 small">{{ $activity['label'] }}</h6>
                                        </div>
                                    </div>
                                            </label>
                                            </div>
                                        </div>
                                        @endforeach
                                    </div>
                                </div>
                                <div class="d-flex justify-content-between mt-4">
                                    <button type="button" class="btn btn-outline-secondary btn-lg px-4" onclick="prevStep()">
                                        <i class="ri-arrow-left-line me-2"></i>Previous
                                    </button>
                                    <button type="button" class="btn btn-primary btn-lg px-5" onclick="nextStep()">
                                        Next Step <i class="ri-arrow-right-line ms-2"></i>
                                    </button>
                                        </div>
                                    </div>

                            <!-- Step 5: Accommodation & Additional Info -->
                            <div class="form-step" data-step="5">
                                <div class="step-header mb-4">
                                    <div class="d-flex align-items-center mb-3">
                                        <div class="step-indicator bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 50px; height: 50px;">
                                            <i class="ri-hotel-bed-line fs-5"></i>
                                        </div>
                                        <div>
                                            <h3 class="h4 fw-bold mb-1">Accommodation & Preferences</h3>
                                            <p class="text-muted mb-0 small">Finalize your tour preferences</p>
                                    </div>
                                </div>
                            </div>

                                <div class="mb-4">
                                    <label class="form-label fw-semibold mb-3">Accommodation Preference</label>
                                <div class="row g-3">
                                    <div class="col-md-4">
                                            <div class="accommodation-option">
                                                <input type="radio" name="accommodation" value="budget" id="acc_budget" class="d-none">
                                                <label for="acc_budget" class="accommodation-label">
                                                    <div class="card h-100 border-2 cursor-pointer transition-all text-center p-4">
                                                        <i class="ri-camping-line text-primary mb-3" style="font-size: 2.5rem;"></i>
                                                        <h5 class="fw-bold mb-2">Budget</h5>
                                                        <p class="text-muted small mb-0">Camping & Basic Lodges</p>
                                                        <p class="text-primary small fw-semibold mt-2 mb-0">$50-150/night</p>
                                                    </div>
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                            <div class="accommodation-option">
                                                <input type="radio" name="accommodation" value="mid-range" id="acc_mid" class="d-none">
                                                <label for="acc_mid" class="accommodation-label">
                                                    <div class="card h-100 border-2 cursor-pointer transition-all text-center p-4">
                                                        <i class="ri-hotel-line text-success mb-3" style="font-size: 2.5rem;"></i>
                                                        <h5 class="fw-bold mb-2">Mid-Range</h5>
                                                        <p class="text-muted small mb-0">Comfortable Lodges & Hotels</p>
                                                        <p class="text-success small fw-semibold mt-2 mb-0">$150-400/night</p>
                                                    </div>
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                            <div class="accommodation-option">
                                                <input type="radio" name="accommodation" value="luxury" id="acc_luxury" class="d-none">
                                                <label for="acc_luxury" class="accommodation-label">
                                                    <div class="card h-100 border-2 cursor-pointer transition-all text-center p-4">
                                                        <i class="ri-hotel-bed-line text-warning mb-3" style="font-size: 2.5rem;"></i>
                                                        <h5 class="fw-bold mb-2">Luxury</h5>
                                                        <p class="text-muted small mb-0">Premium Lodges & Resorts</p>
                                                        <p class="text-warning small fw-semibold mt-2 mb-0">$400+/night</p>
                                                    </div>
                                            </label>
                                            </div>
                                    </div>
                                </div>
                            </div>

                                <div class="mb-4">
                                    <label class="form-label fw-semibold mb-3">Special Requirements or Requests</label>
                                    <textarea name="special_requirements" class="form-control" rows="6" 
                                              placeholder="Tell us about any special requirements, dietary restrictions, accessibility needs, or specific experiences you'd like to include..."></textarea>
                                </div>
                                
                                <!-- Hidden field to compile all custom tour details -->
                                <input type="hidden" name="message" id="compiledMessage">

                                <div class="d-flex justify-content-between mt-4">
                                    <button type="button" class="btn btn-outline-secondary btn-lg px-4" onclick="prevStep()">
                                        <i class="ri-arrow-left-line me-2"></i>Previous
                                    </button>
                                <button type="submit" class="btn btn-primary btn-lg px-5">
                                        <i class="ri-send-plane-line me-2"></i>Submit Request
                                </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Why Choose Custom Tours -->
<section class="py-5 bg-white">
    <div class="container">
        <div class="row mb-5">
            <div class="col-lg-8 mx-auto text-center">
                <span class="badge bg-primary bg-opacity-10 text-primary px-3 py-2 rounded-pill mb-3">Benefits</span>
                <h2 class="display-4 fw-bold mb-3">Why Choose Custom Tours?</h2>
                <p class="lead text-muted">Experience Tanzania your way with a personalized itinerary designed just for you.</p>
            </div>
        </div>
        <div class="row g-4">
            <div class="col-md-6 col-lg-3" data-aos="fade-up" data-aos-delay="100">
                <div class="feature-card text-center p-4 h-100">
                    <div class="feature-icon mb-4">
                        <div class="bg-primary bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center" style="width: 80px; height: 80px;">
                            <i class="ri-user-settings-line text-primary" style="font-size: 2rem;"></i>
                        </div>
                    </div>
                    <h5 class="fw-bold mb-3">100% Personalized</h5>
                    <p class="text-muted small">Every detail tailored to your preferences, interests, and travel style.</p>
                </div>
            </div>
            <div class="col-md-6 col-lg-3" data-aos="fade-up" data-aos-delay="200">
                <div class="feature-card text-center p-4 h-100">
                    <div class="feature-icon mb-4">
                        <div class="bg-success bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center" style="width: 80px; height: 80px;">
                            <i class="ri-time-line text-success" style="font-size: 2rem;"></i>
                        </div>
                    </div>
                    <h5 class="fw-bold mb-3">Flexible Schedule</h5>
                    <p class="text-muted small">Choose your own dates, pace, and travel style. No fixed departures.</p>
                </div>
            </div>
            <div class="col-md-6 col-lg-3" data-aos="fade-up" data-aos-delay="300">
                <div class="feature-card text-center p-4 h-100">
                    <div class="feature-icon mb-4">
                        <div class="bg-warning bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center" style="width: 80px; height: 80px;">
                            <i class="ri-customer-service-2-line text-warning" style="font-size: 2rem;"></i>
                        </div>
                    </div>
                    <h5 class="fw-bold mb-3">Expert Support</h5>
                    <p class="text-muted small">Dedicated travel consultant to guide you every step of the way.</p>
                </div>
            </div>
            <div class="col-md-6 col-lg-3" data-aos="fade-up" data-aos-delay="400">
                <div class="feature-card text-center p-4 h-100">
                    <div class="feature-icon mb-4">
                        <div class="bg-info bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center" style="width: 80px; height: 80px;">
                            <i class="ri-money-dollar-circle-line text-info" style="font-size: 2rem;"></i>
                        </div>
                    </div>
                    <h5 class="fw-bold mb-3">Best Value</h5>
                    <p class="text-muted small">Get the most out of your budget with optimized planning and local expertise.</p>
                </div>
            </div>
        </div>
    </div>
</section>

@push('styles')
<style>
.custom-tours-hero {
    position: relative;
    min-height: 90vh;
    display: flex;
    align-items: center;
}

.hero-background {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    z-index: 0;
}

.hero-overlay {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: linear-gradient(180deg, rgba(0,0,0,0.3) 0%, rgba(0,0,0,0.5) 100%);
    z-index: 1;
}

.hero-shape-bottom {
    position: absolute;
    bottom: 0;
    left: 0;
    width: 100%;
    height: 120px;
    z-index: 1;
}

.hero-shape-bottom svg {
    width: 100%;
    height: 100%;
    display: block;
}

.min-vh-60 {
    min-height: 60vh;
}

.step-card {
    transition: transform 0.3s ease;
}

.step-card:hover {
    transform: translateY(-10px);
}

.step-number {
    position: absolute;
    top: -10px;
    right: -10px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    width: 40px;
    height: 40px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: bold;
    font-size: 0.875rem;
    box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4);
}

.form-step {
    display: none;
}

.form-step.active {
    display: block;
    animation: fadeIn 0.5s ease;
}

@keyframes fadeIn {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.destination-label, .activity-label, .accommodation-label {
    cursor: pointer;
    display: block;
    margin: 0;
}

.destination-checkbox:checked + .destination-label .card,
.activity-checkbox:checked + .activity-label .card,
.accommodation-option input:checked + .accommodation-label .card {
    border-color: var(--bs-primary) !important;
    background: linear-gradient(135deg, rgba(102, 126, 234, 0.1) 0%, rgba(118, 75, 162, 0.1) 100%);
    transform: scale(1.05);
    box-shadow: 0 8px 25px rgba(102, 126, 234, 0.2);
}

.destination-label .card:hover,
.activity-label .card:hover,
.accommodation-label .card:hover {
    border-color: var(--bs-primary) !important;
    transform: translateY(-5px);
    box-shadow: 0 10px 30px rgba(0,0,0,0.1);
}

.transition-all {
    transition: all 0.3s ease;
}

.cursor-pointer {
    cursor: pointer;
}

.feature-card {
    transition: transform 0.3s ease;
}

.feature-card:hover {
    transform: translateY(-10px);
}

.input-group-text {
    border-color: #dee2e6;
}

.form-control:focus,
.form-select:focus {
    border-color: var(--bs-primary);
    box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
}

@media (max-width: 768px) {
    .custom-tours-hero {
        min-height: 70vh;
    }
    
    .display-2 {
        font-size: 2.5rem;
    }
    
    .step-number {
        top: 5px;
        right: 5px;
        width: 35px;
        height: 35px;
        font-size: 0.75rem;
    }
}
</style>
@endpush

@push('scripts')
<script>
let currentStep = 1;
const totalSteps = 5;

function updateProgress() {
    const progress = (currentStep / totalSteps) * 100;
    document.getElementById('progressBar').style.width = progress + '%';
    document.getElementById('progressBar').setAttribute('aria-valuenow', progress);
    document.getElementById('progressText').textContent = `Step ${currentStep} of ${totalSteps}`;
}

function showStep(step) {
    // Hide all steps
    document.querySelectorAll('.form-step').forEach(s => {
        s.classList.remove('active');
    });
    
    // Show current step
    const stepElement = document.querySelector(`.form-step[data-step="${step}"]`);
    if (stepElement) {
        stepElement.classList.add('active');
    }
    
    // Scroll to top of form
    document.getElementById('tour-builder').scrollIntoView({ behavior: 'smooth', block: 'start' });
    
    updateProgress();
}

function nextStep() {
    if (currentStep < totalSteps) {
        // Validate current step
        const currentStepElement = document.querySelector(`.form-step[data-step="${currentStep}"]`);
        const requiredFields = currentStepElement.querySelectorAll('[required]');
        let isValid = true;
        
        requiredFields.forEach(field => {
            if (!field.value.trim()) {
                isValid = false;
                field.classList.add('is-invalid');
            } else {
                field.classList.remove('is-invalid');
            }
        });
        
        if (isValid) {
            currentStep++;
            showStep(currentStep);
        } else {
            // Show error message
            const alert = document.createElement('div');
            alert.className = 'alert alert-danger alert-dismissible fade show';
            alert.innerHTML = `
                <i class="ri-error-warning-line me-2"></i>Please fill in all required fields.
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            `;
            currentStepElement.insertBefore(alert, currentStepElement.firstChild);
            setTimeout(() => alert.remove(), 5000);
        }
    }
}

function prevStep() {
    if (currentStep > 1) {
        currentStep--;
        showStep(currentStep);
    }
}

// Initialize
document.addEventListener('DOMContentLoaded', function() {
    updateProgress();
    
    // Add visual feedback for checkboxes and radio buttons
    document.querySelectorAll('.destination-checkbox, .activity-checkbox').forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            // Visual feedback is handled by CSS
        });
    });
    
    // Compile custom tour details into message before submission
    function compileCustomTourMessage() {
        const form = document.getElementById('customTourForm');
        const formData = new FormData(form);
        
        let message = "CUSTOM TOUR REQUEST DETAILS\n";
        message += "================================\n\n";
        
        // Travel Preferences
        message += "TRAVEL PREFERENCES:\n";
        message += `Travel Dates: ${formData.get('travel_dates') || 'Not specified'}\n`;
        message += `Duration: ${formData.get('duration') || 'Not specified'}\n`;
        message += `Number of Travelers: ${formData.get('travelers') || 'Not specified'}\n`;
        message += `Budget Range: ${formData.get('budget') || 'Not specified'}\n\n`;
        
        // Destinations
        const destinations = formData.getAll('destinations[]');
        if (destinations.length > 0) {
            message += "DESTINATIONS OF INTEREST:\n";
            destinations.forEach(destId => {
                const destLabel = document.querySelector(`label[for="dest_${destId}"]`);
                if (destLabel) {
                    message += `- ${destLabel.textContent.trim()}\n`;
                }
            });
            message += "\n";
        }
        
        // Categories
        const categories = formData.getAll('categories[]');
        if (categories.length > 0) {
            message += "TOUR CATEGORIES:\n";
            categories.forEach(catId => {
                const catLabel = document.querySelector(`label[for="cat_${catId}"]`);
                if (catLabel) {
                    message += `- ${catLabel.textContent.trim()}\n`;
                }
            });
            message += "\n";
        }
        
        // Activities
        const activities = formData.getAll('activities[]');
        if (activities.length > 0) {
            message += "ACTIVITIES & INTERESTS:\n";
            activities.forEach(activity => {
                const actLabel = document.querySelector(`label[for="act_${activity}"]`);
                if (actLabel) {
                    message += `- ${actLabel.textContent.trim()}\n`;
                }
            });
            message += "\n";
        }
        
        // Accommodation
        const accommodation = formData.get('accommodation');
        if (accommodation) {
            message += `ACCOMMODATION PREFERENCE: ${accommodation.charAt(0).toUpperCase() + accommodation.slice(1)}\n\n`;
        }
        
        // Special Requirements
        const specialRequirements = formData.get('special_requirements');
        if (specialRequirements) {
            message += "SPECIAL REQUIREMENTS:\n";
            message += `${specialRequirements}\n\n`;
        }
        
        message += "---\n";
        message += "Please create a personalized itinerary based on the above preferences.";
        
        document.getElementById('compiledMessage').value = message;
    }
    
    // Form submission
    document.getElementById('customTourForm').addEventListener('submit', function(e) {
        // Compile message first
        compileCustomTourMessage();
        
        // Add loading state
        const submitBtn = this.querySelector('button[type="submit"]');
        const originalText = submitBtn.innerHTML;
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Submitting...';
    });
});
</script>
@endpush

@endsection
