@extends('layouts.app')

@section('title', 'Book Your Tanzania Adventure - Lau Paradise Adventures')
@section('description', 'Secure your spot on one of our Tanzania tours. Professional booking experience with secure Stripe payment.')

@section('content')

<!-- Hero Section -->
<section class="booking-hero-section" style="background-image: linear-gradient(135deg, rgba(26, 77, 58, 0.9) 0%, rgba(0, 0, 0, 0.7) 100%), url('{{ isset($selectedTour) && $selectedTour ? $selectedTour['image'] : asset('images/safari_home-1.jpg') }}');">
    <div class="container">
        <div class="booking-hero-content">
            <div class="hero-badge">
                <i class="fas fa-shield-alt me-2"></i>Secure Booking
            </div>
            <h1 class="hero-title">Reserve Your Adventure</h1>
            <p class="hero-subtitle">
                @if(isset($selectedTour) && $selectedTour)
                    Book {{ $selectedTour['name'] }} and create unforgettable memories in Tanzania
                @else
                    Experience the magic of Tanzania with our expertly crafted tours. Book now and secure your spot.
                @endif
            </p>
        </div>
    </div>
</section>

<!-- Advanced Booking Form Section -->
<section class="advanced-booking-section">
    <div class="container">
        <div class="booking-container">
            <!-- Progress Indicator -->
            <div class="booking-progress-indicator">
                <div class="progress-step active">
                    <div class="step-icon">
                        <i class="fas fa-map-marked-alt"></i>
                    </div>
                    <span class="step-label">Select Tour</span>
                </div>
                <div class="progress-line"></div>
                <div class="progress-step">
                    <div class="step-icon">
                        <i class="fas fa-users"></i>
                    </div>
                    <span class="step-label">Traveler Info</span>
                </div>
                <div class="progress-line"></div>
                <div class="progress-step">
                    <div class="step-icon">
                        <i class="fas fa-credit-card"></i>
                    </div>
                    <span class="step-label">Payment</span>
                </div>
            </div>

            <form action="{{ route('booking.submit') }}" method="POST" id="advancedBookingForm" class="booking-form-wrapper">
                @csrf
                <input type="hidden" name="payment_method" value="stripe">
                
                <div class="row g-4">
                    <!-- Left Column: Main Form -->
                    <div class="col-lg-8">
                        <!-- Step 1: Tour Selection -->
                        <div class="form-step active" id="step-tour">
                            <div class="step-header">
                                <h3><i class="fas fa-map-marked-alt me-2"></i>Select Your Tour</h3>
                                <p class="text-muted">Choose the perfect adventure for your journey</p>
                            </div>

                            <div class="tour-selection-grid" id="tourSelectionGrid">
                                @foreach($tours as $tour)
                                <div class="tour-card-select" data-tour-id="{{ $tour['id'] }}" data-tour-price="{{ $tour['price'] }}">
                                    <input type="radio" name="tourId" id="tour_{{ $tour['id'] }}" value="{{ $tour['id'] }}" 
                                           {{ (isset($selectedTourId) && $selectedTourId == $tour['id']) ? 'checked' : '' }} required>
                                    <label for="tour_{{ $tour['id'] }}" class="tour-card-label">
                                        <div class="tour-card-image">
                                            <img src="{{ $tour['image'] }}" alt="{{ $tour['name'] }}" onerror="this.src='{{ asset('images/hero-slider/safari-adventure.jpg') }}'">
                                            <div class="tour-card-overlay">
                                                <span class="tour-price">${{ number_format($tour['price'], 2) }}</span>
                                            </div>
                                            <div class="tour-card-check">
                                                <i class="fas fa-check-circle"></i>
                                            </div>
                                        </div>
                                        <div class="tour-card-body">
                                            <h4>{{ $tour['name'] }}</h4>
                                            <div class="tour-card-meta">
                                                <span><i class="fas fa-clock me-1"></i>{{ $tour['duration_days'] }} Days</span>
                                                <span><i class="fas fa-map-marker-alt me-1"></i>{{ $tour['destination'] }}</span>
                                            </div>
                                            <p class="tour-description">{{ $tour['description'] }}</p>
                                        </div>
                                    </label>
                                </div>
                                @endforeach
                            </div>

                            <!-- Fallback Dropdown (Mobile) -->
                            <div class="tour-select-mobile">
                                <label class="form-label-advanced">
                                    <span>Select Tour <span class="text-danger">*</span></span>
                                </label>
                                <select name="tourId" id="tourIdMobile" class="form-control-advanced">
                                    <option value="">Choose a tour...</option>
                                    @foreach($tours as $tour)
                                        <option value="{{ $tour['id'] }}" 
                                                data-price="{{ $tour['price'] }}"
                                                {{ (isset($selectedTourId) && $selectedTourId == $tour['id']) ? 'selected' : '' }}>
                                            {{ $tour['name'] }} - ${{ number_format($tour['price'], 2) }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="step-actions">
                                <button type="button" class="btn-next-step" onclick="nextStep('step-tour', 'step-traveler')">
                                    Continue <i class="fas fa-arrow-right ms-2"></i>
                                </button>
                            </div>
                        </div>

                        <!-- Step 2: Traveler Information -->
                        <div class="form-step" id="step-traveler">
                            <div class="step-header">
                                <h3><i class="fas fa-users me-2"></i>Traveler Information</h3>
                                <p class="text-muted">Please provide details for all travelers</p>
                            </div>

                            <div class="form-section">
                                <h5 class="section-title"><i class="fas fa-calendar-alt me-2"></i>Travel Details</h5>
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <div class="form-group-advanced">
                                            <label class="form-label-advanced">
                                                <span>Number of Travelers <span class="text-danger">*</span></span>
                                            </label>
                                            <div class="input-group-advanced">
                                                <button type="button" class="btn-quantity" onclick="changeTravelers(-1)">
                                                    <i class="fas fa-minus"></i>
                                                </button>
                                                <input type="number" name="travelers" id="travelers" class="form-control-advanced text-center" 
                                                       min="1" max="20" value="1" required readonly>
                                                <button type="button" class="btn-quantity" onclick="changeTravelers(1)">
                                                    <i class="fas fa-plus"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group-advanced">
                                            <label class="form-label-advanced">
                                                <span>Departure Date <span class="text-danger">*</span></span>
                                            </label>
                                            <div class="input-icon-wrapper">
                                                <i class="fas fa-calendar input-icon"></i>
                                                <input type="date" name="date" id="date" class="form-control-advanced" 
                                                       min="{{ date('Y-m-d', strtotime('+7 days')) }}" required>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="form-section">
                                <h5 class="section-title"><i class="fas fa-user me-2"></i>Primary Contact</h5>
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <div class="form-group-advanced">
                                            <label class="form-label-advanced">
                                                <span>Full Name <span class="text-danger">*</span></span>
                                            </label>
                                            <div class="input-icon-wrapper">
                                                <i class="fas fa-user input-icon"></i>
                                                <input type="text" name="name" id="name" class="form-control-advanced" 
                                                       placeholder="John Doe" required>
                                            </div>
                                            <input type="hidden" name="card-name" value="">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group-advanced">
                                            <label class="form-label-advanced">
                                                <span>Email Address <span class="text-danger">*</span></span>
                                            </label>
                                            <div class="input-icon-wrapper">
                                                <i class="fas fa-envelope input-icon"></i>
                                                <input type="email" name="email" id="email" class="form-control-advanced" 
                                                       placeholder="john@example.com" required>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group-advanced">
                                            <label class="form-label-advanced">
                                                <span>Phone Number <span class="text-danger">*</span></span>
                                            </label>
                                            <div class="input-icon-wrapper">
                                                <i class="fas fa-phone input-icon"></i>
                                                <input type="tel" name="phone" id="phone" class="form-control-advanced" 
                                                       placeholder="+255 123 456 789" required>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group-advanced">
                                            <label class="form-label-advanced">
                                                <span>Country</span>
                                            </label>
                                            <div class="input-icon-wrapper">
                                                <i class="fas fa-globe input-icon"></i>
                                                <input type="text" name="country" id="country" class="form-control-advanced" 
                                                       value="Tanzania" placeholder="Tanzania">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="form-section">
                                <h5 class="section-title"><i class="fas fa-plus-circle me-2"></i>Add-ons (Optional)</h5>
                                <div class="addons-grid-advanced">
                                    <div class="addon-card">
                                        <input type="checkbox" name="addons[]" value="insurance" id="addonInsurance">
                                        <label for="addonInsurance" class="addon-card-label">
                                            <div class="addon-icon">
                                                <i class="fas fa-shield-alt"></i>
                                            </div>
                                            <div class="addon-content">
                                                <h6>Travel Insurance</h6>
                                                <p>Comprehensive coverage for your journey</p>
                                                <span class="addon-price">+$150</span>
                                            </div>
                                        </label>
                                    </div>
                                    <div class="addon-card">
                                        <input type="checkbox" name="addons[]" value="gear" id="addonGear">
                                        <label for="addonGear" class="addon-card-label">
                                            <div class="addon-icon">
                                                <i class="fas fa-campground"></i>
                                            </div>
                                            <div class="addon-content">
                                                <h6>Camping Gear</h6>
                                                <p>Professional camping equipment rental</p>
                                                <span class="addon-price">+$80</span>
                                            </div>
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <div class="form-section">
                                <div class="form-group-advanced">
                                    <label class="form-label-advanced">
                                        <span>Special Requirements</span>
                                    </label>
                                    <textarea name="special_requirements" id="special_requirements" class="form-control-advanced" rows="4" 
                                              placeholder="Any dietary restrictions, accessibility needs, or special requests..."></textarea>
                                </div>
                            </div>

                            <div class="step-actions">
                                <button type="button" class="btn-prev-step" onclick="prevStep('step-traveler', 'step-tour')">
                                    <i class="fas fa-arrow-left me-2"></i>Back
                                </button>
                                <button type="button" class="btn-next-step" onclick="nextStep('step-traveler', 'step-payment')">
                                    Continue <i class="fas fa-arrow-right ms-2"></i>
                                </button>
                            </div>
                        </div>

                        <!-- Step 3: Payment Summary -->
                        <div class="form-step" id="step-payment">
                            <div class="step-header">
                                <h3><i class="fas fa-credit-card me-2"></i>Review & Payment</h3>
                                <p class="text-muted">Review your booking details and proceed to secure payment</p>
                            </div>

                            <div class="review-section">
                                <div class="review-card">
                                    <h5><i class="fas fa-info-circle me-2"></i>Booking Summary</h5>
                                    <div class="review-item">
                                        <span>Tour:</span>
                                        <strong id="reviewTour">-</strong>
                                    </div>
                                    <div class="review-item">
                                        <span>Travelers:</span>
                                        <strong id="reviewTravelers">-</strong>
                                    </div>
                                    <div class="review-item">
                                        <span>Departure Date:</span>
                                        <strong id="reviewDate">-</strong>
                                    </div>
                                    <div class="review-item">
                                        <span>Contact:</span>
                                        <strong id="reviewContact">-</strong>
                                    </div>
                                </div>
                            </div>

                            <div class="payment-section">
                                <div class="payment-card">
                                    <div class="payment-header">
                                        <i class="fas fa-lock"></i>
                                        <div>
                                            <h5>Secure Payment via Stripe</h5>
                                            <p>Complete your secure payment below</p>
                                        </div>
                                    </div>
                                    
                                    <!-- Stripe Payment Link Embed -->
                                    <div class="stripe-payment-embed" id="stripePaymentEmbed">
                                        @if(isset($paymentLinkUrl) && $paymentLinkUrl)
                                            <div class="payment-iframe-wrapper">
                                                <iframe 
                                                    src="{{ $paymentLinkUrl }}" 
                                                    id="stripePaymentIframe"
                                                    frameborder="0" 
                                                    scrolling="auto"
                                                    allow="payment *"
                                                    sandbox="allow-forms allow-scripts allow-same-origin allow-top-navigation allow-popups allow-popups-to-escape-sandbox"
                                                    style="width: 100%; min-height: 600px; border: none;">
                                                </iframe>
                                            </div>
                                            <div class="payment-alternative">
                                                <p>Having trouble with the payment form? <a href="{{ $paymentLinkUrl }}" target="_blank" rel="noopener">Open payment page in a new window</a></p>
                                            </div>
                                        @else
                                            <div class="payment-placeholder">
                                                <i class="fas fa-credit-card"></i>
                                                <p>Payment form will appear here after you complete the booking details</p>
                                            </div>
                                        @endif
                                    </div>
                                    
                                    <div class="payment-security">
                                        <i class="fas fa-shield-alt"></i>
                                        <span>256-bit SSL Encryption • Powered by Stripe</span>
                                    </div>
                                </div>
                            </div>

                            <div class="terms-section">
                                <div class="form-check-advanced">
                                    <input type="checkbox" id="agreeTerms" class="form-check-input-advanced" required>
                                    <label for="agreeTerms">
                                        I agree to the <a href="{{ route('terms') }}" target="_blank">Terms & Conditions</a> 
                                        and <a href="{{ route('privacy') }}" target="_blank">Privacy Policy</a>
                                        <span class="text-danger">*</span>
                                    </label>
                                </div>
                            </div>

                            <div class="step-actions">
                                <button type="button" class="btn-prev-step" onclick="prevStep('step-payment', 'step-traveler')">
                                    <i class="fas fa-arrow-left me-2"></i>Back
                                </button>
                                <button type="submit" class="btn-submit-payment" id="submitPaymentBtn">
                                    <i class="fas fa-lock me-2"></i>Proceed to Secure Payment
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Right Column: Booking Summary Sidebar -->
                    <div class="col-lg-4">
                        <div class="booking-summary-sidebar">
                            <div class="summary-header">
                                <h4><i class="fas fa-receipt me-2"></i>Booking Summary</h4>
                            </div>
                            
                            <div class="summary-content">
                                <div class="summary-item-advanced">
                                    <span>Tour:</span>
                                    <strong id="summaryTour">Select a tour</strong>
                                </div>
                                <div class="summary-item-advanced">
                                    <span>Travelers:</span>
                                    <strong id="summaryTravelers">1</strong>
                                </div>
                                <div class="summary-item-advanced">
                                    <span>Departure:</span>
                                    <strong id="summaryDate">-</strong>
                                </div>
                                <div class="summary-item-advanced">
                                    <span>Base Price:</span>
                                    <strong id="summaryBasePrice">$0.00</strong>
                                </div>
                                <div class="summary-item-advanced" id="summaryAddonsRow" style="display: none;">
                                    <span>Add-ons:</span>
                                    <strong id="summaryAddons">$0.00</strong>
                                </div>
                                <div class="summary-divider"></div>
                                <div class="summary-total-advanced">
                                    <span>Total Amount:</span>
                                    <strong id="summaryTotal">$0.00</strong>
                                </div>
                            </div>

                            <div class="summary-footer">
                                <div class="security-badge">
                                    <i class="fas fa-shield-alt"></i>
                                    <span>Secure Payment</span>
                                </div>
                                <div class="trust-badges">
                                    <i class="fas fa-lock" title="SSL Encrypted"></i>
                                    <i class="fas fa-check-circle" title="Verified"></i>
                                    <i class="fas fa-user-shield" title="Protected"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</section>

@endsection

@push('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<style>
/* Hero Section */
.booking-hero-section {
    position: relative;
    padding: 120px 0 80px;
    background-size: cover;
    background-position: center;
    background-attachment: fixed;
    color: white;
    text-align: center;
}

.booking-hero-content {
    position: relative;
    z-index: 2;
    max-width: 900px;
    margin: 0 auto;
}

.hero-badge {
    display: inline-block;
    padding: 10px 25px;
    background: rgba(255, 255, 255, 0.2);
    backdrop-filter: blur(10px);
    border-radius: 50px;
    font-size: 0.95rem;
    font-weight: 600;
    margin-bottom: 25px;
    border: 1px solid rgba(255, 255, 255, 0.3);
}

.hero-title {
    font-size: 3.5rem;
    font-weight: 800;
    margin-bottom: 20px;
    text-shadow: 2px 2px 10px rgba(0,0,0,0.5);
}

.hero-subtitle {
    font-size: 1.3rem;
    opacity: 0.95;
    line-height: 1.7;
}

/* Main Section */
.advanced-booking-section {
    padding: 60px 0 100px;
    background: linear-gradient(135deg, #f5f7fa 0%, #e9ecef 100%);
    min-height: 100vh;
}

.booking-container {
    max-width: 1400px;
    margin: 0 auto;
}

/* Progress Indicator */
.booking-progress-indicator {
    display: flex;
    align-items: center;
    justify-content: center;
    margin-bottom: 40px;
    padding: 30px;
    background: white;
    border-radius: 20px;
    box-shadow: 0 4px 20px rgba(0,0,0,0.08);
}

.progress-step {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 10px;
    position: relative;
    flex: 1;
    max-width: 200px;
}

.progress-step.active .step-icon {
    background: linear-gradient(135deg, #1a4d3e 0%, #2d7a5f 100%);
    color: white;
    transform: scale(1.1);
    box-shadow: 0 8px 25px rgba(26, 77, 58, 0.3);
}

.progress-step .step-icon {
    width: 60px;
    height: 60px;
    border-radius: 50%;
    background: #e9ecef;
    color: #6c757d;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.5rem;
    transition: all 0.3s;
    border: 3px solid transparent;
}

.progress-step.active .step-label {
    color: #1a4d3e;
    font-weight: 700;
}

.progress-step .step-label {
    font-size: 0.9rem;
    color: #6c757d;
    font-weight: 600;
    text-align: center;
}

.progress-line {
    flex: 1;
    height: 3px;
    background: #e9ecef;
    margin: 0 20px;
    position: relative;
    top: -30px;
    max-width: 150px;
}

.progress-step.active ~ .progress-line {
    background: linear-gradient(90deg, #1a4d3e 0%, #2d7a5f 100%);
}

/* Form Steps */
.form-step {
    display: none;
    background: white;
    border-radius: 20px;
    padding: 40px;
    box-shadow: 0 10px 40px rgba(0,0,0,0.1);
    margin-bottom: 30px;
    animation: fadeIn 0.5s ease-in;
}

.form-step.active {
    display: block;
}

@keyframes fadeIn {
    from { opacity: 0; transform: translateY(20px); }
    to { opacity: 1; transform: translateY(0); }
}

.step-header {
    margin-bottom: 30px;
    padding-bottom: 20px;
    border-bottom: 2px solid #e9ecef;
}

.step-header h3 {
    font-size: 1.8rem;
    color: #1a4d3e;
    font-weight: 700;
    margin-bottom: 8px;
}

.step-header p {
    color: #6c757d;
    margin: 0;
}

/* Tour Selection Grid */
.tour-selection-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
    gap: 25px;
    margin-bottom: 30px;
}

.tour-card-select {
    position: relative;
}

.tour-card-select input[type="radio"] {
    position: absolute;
    opacity: 0;
    pointer-events: none;
}

.tour-card-label {
    display: block;
    cursor: pointer;
    background: white;
    border: 3px solid #e9ecef;
    border-radius: 15px;
    overflow: hidden;
    transition: all 0.3s;
    height: 100%;
}

.tour-card-select input[type="radio"]:checked + .tour-card-label {
    border-color: #1a4d3e;
    box-shadow: 0 10px 30px rgba(26, 77, 58, 0.2);
    transform: translateY(-5px);
}

.tour-card-image {
    position: relative;
    height: 200px;
    overflow: hidden;
}

.tour-card-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.3s;
}

.tour-card-select input[type="radio"]:checked + .tour-card-label .tour-card-image img {
    transform: scale(1.1);
}

.tour-card-overlay {
    position: absolute;
    top: 15px;
    right: 15px;
    background: rgba(26, 77, 58, 0.9);
    color: white;
    padding: 8px 15px;
    border-radius: 20px;
    font-weight: 700;
    font-size: 1.1rem;
}

.tour-card-check {
    position: absolute;
    top: 15px;
    left: 15px;
    width: 40px;
    height: 40px;
    background: #1a4d3e;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    opacity: 0;
    transform: scale(0);
    transition: all 0.3s;
}

.tour-card-select input[type="radio"]:checked + .tour-card-label .tour-card-check {
    opacity: 1;
    transform: scale(1);
}

.tour-card-body {
    padding: 20px;
}

.tour-card-body h4 {
    font-size: 1.3rem;
    color: #1a4d3e;
    font-weight: 700;
    margin-bottom: 10px;
}

.tour-card-meta {
    display: flex;
    gap: 15px;
    margin-bottom: 10px;
    font-size: 0.9rem;
    color: #6c757d;
}

.tour-description {
    color: #495057;
    font-size: 0.95rem;
    line-height: 1.6;
    margin: 0;
}

.tour-select-mobile {
    display: none;
}

/* Form Sections */
.form-section {
    margin-bottom: 35px;
    padding: 25px;
    background: #f8f9fa;
    border-radius: 15px;
}

.section-title {
    font-size: 1.2rem;
    color: #1a4d3e;
    font-weight: 700;
    margin-bottom: 20px;
    padding-bottom: 10px;
    border-bottom: 2px solid #e9ecef;
}

.form-group-advanced {
    margin-bottom: 20px;
}

.form-label-advanced {
    display: block;
    font-weight: 600;
    color: #495057;
    margin-bottom: 8px;
    font-size: 0.95rem;
}

.form-control-advanced {
    width: 100%;
    padding: 14px 18px;
    border: 2px solid #e9ecef;
    border-radius: 10px;
    font-size: 1rem;
    transition: all 0.3s;
    background: white;
}

.form-control-advanced:focus {
    outline: none;
    border-color: #1a4d3e;
    box-shadow: 0 0 0 4px rgba(26, 77, 58, 0.1);
}

.input-icon-wrapper {
    position: relative;
}

.input-icon {
    position: absolute;
    left: 18px;
    top: 50%;
    transform: translateY(-50%);
    color: #6c757d;
    z-index: 1;
}

.input-icon-wrapper .form-control-advanced {
    padding-left: 45px;
}

.input-group-advanced {
    display: flex;
    align-items: center;
    gap: 10px;
}

.btn-quantity {
    width: 45px;
    height: 45px;
    border: 2px solid #e9ecef;
    background: white;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: all 0.3s;
    color: #1a4d3e;
}

.btn-quantity:hover {
    background: #1a4d3e;
    color: white;
    border-color: #1a4d3e;
}

/* Addons */
.addons-grid-advanced {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 20px;
}

.addon-card input[type="checkbox"] {
    display: none;
}

.addon-card-label {
    display: flex;
    align-items: center;
    gap: 15px;
    padding: 20px;
    background: white;
    border: 2px solid #e9ecef;
    border-radius: 12px;
    cursor: pointer;
    transition: all 0.3s;
}

.addon-card input[type="checkbox"]:checked + .addon-card-label {
    border-color: #1a4d3e;
    background: #e6f4ed;
}

.addon-icon {
    width: 50px;
    height: 50px;
    background: linear-gradient(135deg, #1a4d3e 0%, #2d7a5f 100%);
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 1.5rem;
}

.addon-content {
    flex: 1;
}

.addon-content h6 {
    font-weight: 700;
    color: #1a4d3e;
    margin-bottom: 5px;
}

.addon-content p {
    font-size: 0.85rem;
    color: #6c757d;
    margin: 0;
}

.addon-price {
    font-weight: 700;
    color: #1a4d3e;
    font-size: 1.1rem;
}

/* Summary Sidebar */
.booking-summary-sidebar {
    position: sticky;
    top: 20px;
    background: white;
    border-radius: 20px;
    box-shadow: 0 10px 40px rgba(0,0,0,0.1);
    overflow: hidden;
}

.summary-header {
    background: linear-gradient(135deg, #1a4d3e 0%, #2d7a5f 100%);
    color: white;
    padding: 25px;
    text-align: center;
}

.summary-header h4 {
    margin: 0;
    font-weight: 700;
    font-size: 1.3rem;
}

.summary-content {
    padding: 25px;
}

.summary-item-advanced {
    display: flex;
    justify-content: space-between;
    padding: 15px 0;
    border-bottom: 1px solid #e9ecef;
}

.summary-item-advanced:last-of-type {
    border-bottom: none;
}

.summary-item-advanced span {
    color: #6c757d;
    font-weight: 500;
}

.summary-item-advanced strong {
    color: #1a4d3e;
    font-weight: 700;
}

.summary-divider {
    height: 2px;
    background: linear-gradient(90deg, transparent 0%, #e9ecef 50%, transparent 100%);
    margin: 20px 0;
}

.summary-total-advanced {
    display: flex;
    justify-content: space-between;
    padding: 20px;
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    border-radius: 12px;
    font-size: 1.3rem;
    font-weight: 700;
    color: #1a4d3e;
}

.summary-footer {
    padding: 20px;
    background: #f8f9fa;
    border-top: 1px solid #e9ecef;
}

.security-badge {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 10px;
    padding: 12px;
    background: white;
    border-radius: 10px;
    margin-bottom: 15px;
    color: #1a4d3e;
    font-weight: 600;
}

.trust-badges {
    display: flex;
    justify-content: center;
    gap: 15px;
    color: #28a745;
    font-size: 1.2rem;
}

/* Review Section */
.review-section {
    margin-bottom: 30px;
}

.review-card {
    background: #f8f9fa;
    border-radius: 15px;
    padding: 25px;
}

.review-card h5 {
    color: #1a4d3e;
    font-weight: 700;
    margin-bottom: 20px;
}

.review-item {
    display: flex;
    justify-content: space-between;
    padding: 12px 0;
    border-bottom: 1px solid #e9ecef;
}

.review-item:last-child {
    border-bottom: none;
}

.review-item span {
    color: #6c757d;
}

.review-item strong {
    color: #1a4d3e;
    font-weight: 700;
}

/* Payment Section */
.payment-section {
    margin-bottom: 30px;
}

.payment-card {
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    border-radius: 15px;
    padding: 25px;
    border: 2px solid #e9ecef;
}

.payment-header {
    display: flex;
    align-items: center;
    gap: 15px;
    margin-bottom: 20px;
}

.payment-header i {
    font-size: 2rem;
    color: #1a4d3e;
}

.payment-header h5 {
    margin: 0;
    color: #1a4d3e;
    font-weight: 700;
}

.payment-header p {
    margin: 0;
    color: #6c757d;
    font-size: 0.9rem;
}

.payment-methods {
    display: flex;
    gap: 15px;
    margin-bottom: 20px;
    flex-wrap: wrap;
}

.payment-method-item {
    flex: 1;
    min-width: 120px;
    padding: 15px;
    background: white;
    border-radius: 10px;
    text-align: center;
    border: 2px solid #e9ecef;
}

.payment-method-item i {
    font-size: 1.5rem;
    color: #1a4d3e;
    display: block;
    margin-bottom: 8px;
}

.payment-method-item span {
    font-size: 0.85rem;
    color: #495057;
    font-weight: 600;
}

.payment-security {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 10px;
    padding: 12px;
    background: white;
    border-radius: 10px;
    color: #28a745;
    font-weight: 600;
}

/* Stripe Payment Embed */
.stripe-payment-embed {
    margin: 20px 0;
    border-radius: 10px;
    overflow: hidden;
    background: white;
    min-height: 600px;
}

.payment-iframe-wrapper {
    position: relative;
    width: 100%;
    min-height: 600px;
    border: 2px solid #e9ecef;
    border-radius: 10px;
    overflow: hidden;
    background: #f8f9fa;
}

.stripe-payment-embed iframe {
    width: 100%;
    min-height: 600px;
    border: none;
    display: block;
    background: white;
}

.payment-alternative {
    margin-top: 15px;
    padding: 15px;
    background: #f8f9fa;
    border-radius: 8px;
    text-align: center;
    font-size: 0.9rem;
    color: #6c757d;
}

.payment-alternative a {
    color: #1a4d3e;
    text-decoration: none;
    font-weight: 600;
}

.payment-alternative a:hover {
    text-decoration: underline;
}

.payment-loading,
.payment-placeholder {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    padding: 60px 20px;
    text-align: center;
    color: #6c757d;
    min-height: 600px;
}

.payment-loading i,
.payment-placeholder i {
    font-size: 3rem;
    color: #1a4d3e;
    margin-bottom: 20px;
}

.payment-loading p,
.payment-placeholder p {
    font-size: 1.1rem;
    margin: 0;
}

/* Terms Section */
.terms-section {
    margin-bottom: 30px;
}

.form-check-advanced {
    display: flex;
    align-items: flex-start;
    gap: 12px;
    padding: 20px;
    background: #f8f9fa;
    border-radius: 12px;
}

.form-check-input-advanced {
    width: 20px;
    height: 20px;
    margin-top: 2px;
    cursor: pointer;
}

.form-check-advanced label {
    flex: 1;
    cursor: pointer;
    color: #495057;
    line-height: 1.6;
}

/* Buttons */
.step-actions {
    display: flex;
    justify-content: space-between;
    gap: 15px;
    margin-top: 30px;
    padding-top: 30px;
    border-top: 2px solid #e9ecef;
}

.btn-next-step, .btn-prev-step, .btn-submit-payment {
    padding: 15px 30px;
    border: none;
    border-radius: 10px;
    font-weight: 700;
    font-size: 1rem;
    cursor: pointer;
    transition: all 0.3s;
    display: flex;
    align-items: center;
}

.btn-next-step, .btn-submit-payment {
    background: linear-gradient(135deg, #1a4d3e 0%, #2d7a5f 100%);
    color: white;
    margin-left: auto;
}

.btn-next-step:hover, .btn-submit-payment:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(26, 77, 58, 0.3);
}

.btn-prev-step {
    background: #e9ecef;
    color: #495057;
}

.btn-prev-step:hover {
    background: #dee2e6;
}

.btn-submit-payment {
    background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
    font-size: 1.1rem;
    padding: 18px 35px;
}

/* Responsive */
@media (max-width: 992px) {
    .tour-selection-grid {
        grid-template-columns: 1fr;
    }
    
    .tour-select-mobile {
        display: block;
        margin-bottom: 20px;
    }
    
    .booking-summary-sidebar {
        position: relative;
        margin-top: 30px;
    }
}

/* Validation Styles */
.is-invalid {
    border-color: #dc3545 !important;
    box-shadow: 0 0 0 4px rgba(220, 53, 69, 0.1) !important;
}

.has-error {
    border-color: #dc3545 !important;
}

.validation-error-notification {
    position: fixed;
    top: 20px;
    right: 20px;
    background: #dc3545;
    color: white;
    padding: 15px 20px;
    border-radius: 10px;
    box-shadow: 0 5px 25px rgba(220, 53, 69, 0.3);
    z-index: 9999;
    opacity: 0;
    transform: translateX(400px);
    transition: all 0.3s;
    max-width: 400px;
}

.validation-error-notification.show {
    opacity: 1;
    transform: translateX(0);
}

.error-content {
    display: flex;
    align-items: center;
    font-weight: 600;
}

.tour-card-select.has-error .tour-card-label {
    border-color: #dc3545;
    animation: shake 0.5s;
}

@keyframes shake {
    0%, 100% { transform: translateX(0); }
    25% { transform: translateX(-10px); }
    75% { transform: translateX(10px); }
}

@media (max-width: 768px) {
    .hero-title {
        font-size: 2.5rem;
    }
    
    .progress-step {
        max-width: 100px;
    }
    
    .progress-line {
        max-width: 50px;
    }
    
    .form-step {
        padding: 25px;
    }
    
    .addons-grid-advanced {
        grid-template-columns: 1fr;
    }
    
    .validation-error-notification {
        right: 10px;
        left: 10px;
        max-width: none;
    }
}
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const tourSelect = document.getElementById('tourIdMobile');
    const travelersInput = document.getElementById('travelers');
    const dateInput = document.getElementById('date');
    const addonsCheckboxes = document.querySelectorAll('input[name="addons[]"]');
    const tourRadios = document.querySelectorAll('input[name="tourId"]');
    
    // Pricing
    const addonsPricing = {
        insurance: 150,
        gear: 80
    };

    // Tour selection handlers
    tourRadios.forEach(radio => {
        radio.addEventListener('change', function() {
            updateSummary();
            updateProgress();
        });
    });

    if (tourSelect) {
        tourSelect.addEventListener('change', function() {
            // Sync with radio buttons
            const selectedRadio = document.querySelector(`input[name="tourId"][value="${this.value}"]`);
            if (selectedRadio) {
                selectedRadio.checked = true;
            }
            updateSummary();
            updateProgress();
        });
    }

    function updateSummary() {
        let selectedTour = null;
        let tourPrice = 0;
        let tourName = 'Select a tour';
        
        // Get selected tour from radio or dropdown
        const selectedRadio = document.querySelector('input[name="tourId"]:checked');
        if (selectedRadio) {
            const tourCard = selectedRadio.closest('.tour-card-select');
            if (tourCard) {
                tourPrice = parseFloat(tourCard.dataset.tourPrice || 0);
                tourName = tourCard.querySelector('.tour-card-body h4')?.textContent || 'Select a tour';
            }
        } else if (tourSelect && tourSelect.value) {
            const option = tourSelect.options[tourSelect.selectedIndex];
            tourPrice = parseFloat(option.dataset.price || 0);
            tourName = option.text.split(' - ')[0] || 'Select a tour';
        }
        
        const travelers = parseInt(travelersInput.value) || 1;
        const date = dateInput.value || '-';
        
        // Calculate addons
        let addonsTotal = 0;
        addonsCheckboxes.forEach(checkbox => {
            if (checkbox.checked) {
                addonsTotal += addonsPricing[checkbox.value] || 0;
            }
        });
        
        // Calculate totals
        const basePrice = tourPrice * travelers;
        const total = basePrice + addonsTotal;
        
        // Update summary sidebar
        document.getElementById('summaryTour').textContent = tourName;
        document.getElementById('summaryTravelers').textContent = travelers;
        document.getElementById('summaryDate').textContent = date || '-';
        document.getElementById('summaryBasePrice').textContent = '$' + basePrice.toFixed(2);
        
        if (addonsTotal > 0) {
            document.getElementById('summaryAddonsRow').style.display = 'flex';
            document.getElementById('summaryAddons').textContent = '$' + addonsTotal.toFixed(2);
        } else {
            document.getElementById('summaryAddonsRow').style.display = 'none';
        }
        
        document.getElementById('summaryTotal').textContent = '$' + total.toFixed(2);
        
        // Update review section
        document.getElementById('reviewTour').textContent = tourName;
        document.getElementById('reviewTravelers').textContent = travelers + ' traveler' + (travelers > 1 ? 's' : '');
        document.getElementById('reviewDate').textContent = date || '-';
        
        const name = document.getElementById('name').value;
        const email = document.getElementById('email').value;
        document.getElementById('reviewContact').textContent = name && email ? `${name} (${email})` : '-';
    }

    function updateProgress() {
        const steps = document.querySelectorAll('.progress-step');
        const currentStep = document.querySelector('.form-step.active');
        
        if (currentStep) {
            const stepIndex = Array.from(document.querySelectorAll('.form-step')).indexOf(currentStep);
            steps.forEach((step, index) => {
                if (index <= stepIndex) {
                    step.classList.add('active');
                } else {
                    step.classList.remove('active');
                }
            });
        }
    }

    // Event listeners
    travelersInput.addEventListener('input', updateSummary);
    dateInput.addEventListener('change', updateSummary);
    addonsCheckboxes.forEach(checkbox => {
        checkbox.addEventListener('change', updateSummary);
    });
    
    // Update contact info in review
    document.getElementById('name').addEventListener('input', updateSummary);
    document.getElementById('email').addEventListener('input', updateSummary);

    // Initial update
    updateSummary();
    updateProgress();
    
    // If payment link is available, show payment step
    @if(isset($showPayment) && $showPayment && isset($paymentLinkUrl) && $paymentLinkUrl)
        // Hide other steps and show payment step
        document.querySelectorAll('.form-step').forEach(step => {
            step.classList.remove('active');
        });
        const paymentStep = document.getElementById('step-payment');
        if (paymentStep) {
            paymentStep.classList.add('active');
            updateProgress();
            // Scroll to payment section
            setTimeout(() => {
                paymentStep.scrollIntoView({ behavior: 'smooth', block: 'start' });
            }, 100);
        }
        
        // Listen for payment completion in iframe
        const paymentIframe = document.getElementById('stripePaymentIframe');
        if (paymentIframe) {
            // Check if payment is completed by listening to messages from iframe
            window.addEventListener('message', function(event) {
                // Handle messages from Stripe payment page
                if (event.data && event.data.type === 'stripe-payment-complete') {
                // Redirect to confirmation page
                @if(isset($bookingId) && $bookingId)
                    window.location.href = '{{ route("booking.confirmation", $bookingId) }}';
                @else
                    window.location.href = '{{ route("booking") }}';
                @endif
                }
            });
            
            // Monitor iframe load
            paymentIframe.addEventListener('load', function() {
                // Iframe loaded successfully
                const loadingDiv = paymentIframe.parentElement.querySelector('.payment-loading');
                if (loadingDiv) {
                    loadingDiv.style.display = 'none';
                }
            });
        }
    @endif
});

// Step navigation
function nextStep(currentStepId, nextStepId) {
    const currentStep = document.getElementById(currentStepId);
    const nextStep = document.getElementById(nextStepId);
    
    // Validate current step
    if (!validateStep(currentStepId)) {
        return;
    }
    
    // Hide current, show next
    currentStep.classList.remove('active');
    nextStep.classList.add('active');
    
    // Update progress
    updateProgress();
    
    // Scroll to top
    window.scrollTo({ top: 0, behavior: 'smooth' });
}

function prevStep(currentStepId, prevStepId) {
    const currentStep = document.getElementById(currentStepId);
    const prevStep = document.getElementById(prevStepId);
    
    currentStep.classList.remove('active');
    prevStep.classList.add('active');
    
    updateProgress();
    window.scrollTo({ top: 0, behavior: 'smooth' });
}

function validateStep(stepId) {
    const step = document.getElementById(stepId);
    if (!step) return true;
    
    const requiredFields = step.querySelectorAll('[required]');
    let isValid = true;
    const errors = [];
    const validatedGroups = new Set(); // Track validated radio groups
    
    requiredFields.forEach(field => {
        // Skip hidden fields
        if (field.type === 'hidden') {
            return;
        }
        
        let fieldValid = true;
        
        // Handle different input types
        if (field.type === 'radio') {
            // Skip if we've already validated this radio group
            if (validatedGroups.has(field.name)) {
                return;
            }
            validatedGroups.add(field.name);
            
            // Check if at least one radio in the group is checked
            const radioGroup = step.querySelectorAll(`input[name="${field.name}"][type="radio"]`);
            const isChecked = Array.from(radioGroup).some(radio => radio.checked);
            
            // Also check mobile dropdown if it exists (for tourId)
            if (!isChecked && field.name === 'tourId') {
                const mobileSelect = document.getElementById('tourIdMobile');
                if (mobileSelect && mobileSelect.value) {
                    // Mobile select has value, so it's valid
                    fieldValid = true;
                } else {
                    fieldValid = false;
                    errors.push('Please select a tour');
                }
            } else if (!isChecked) {
                fieldValid = false;
                errors.push(`Please select a ${field.name === 'tourId' ? 'tour' : 'option'}`);
            } else {
                fieldValid = true;
            }
        } else if (field.type === 'checkbox') {
            if (!field.checked) {
                fieldValid = false;
            }
        } else if (field.type === 'email') {
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!field.value.trim() || !emailRegex.test(field.value.trim())) {
                fieldValid = false;
                errors.push('Please enter a valid email address');
            }
        } else if (field.tagName === 'SELECT') {
            // Special handling for tourId dropdown - it's a mobile fallback, skip if radios are used
            if (field.name === 'tourId' && field.id === 'tourIdMobile') {
                // Check if any radio button is selected first
                const radioGroup = document.querySelectorAll(`input[name="tourId"][type="radio"]`);
                const isRadioChecked = Array.from(radioGroup).some(radio => radio.checked);
                
                // If radio is checked, skip dropdown validation (it's just a fallback)
                if (isRadioChecked) {
                    return; // Skip this field entirely
                }
                // If no radio checked, validate dropdown (mobile view)
                if (!field.value || field.value === '') {
                    fieldValid = false;
                    errors.push('Please select a tour');
                } else {
                    fieldValid = true;
                }
            } else if (!field.value || field.value === '') {
                fieldValid = false;
                errors.push('Please select an option');
            }
        } else {
            // Text, number, date, tel inputs
            // Skip readonly fields (they're controlled by buttons)
            if (field.hasAttribute('readonly') && field.value) {
                fieldValid = true;
            } else if (!field.value || (typeof field.value === 'string' && !field.value.trim())) {
                fieldValid = false;
                const label = field.closest('.form-group-advanced')?.querySelector('.form-label-advanced span')?.textContent || 'field';
                const cleanLabel = label.replace(/\*/g, '').trim();
                errors.push(`Please fill in ${cleanLabel}`);
            }
        }
        
        if (!fieldValid) {
            isValid = false;
            field.classList.add('is-invalid');
            // Also add to parent for styling
            const parent = field.closest('.form-group-advanced, .tour-card-select, .form-check-advanced');
            if (parent) {
                parent.classList.add('has-error');
            }
        } else {
            field.classList.remove('is-invalid');
            const parent = field.closest('.form-group-advanced, .tour-card-select, .form-check-advanced');
            if (parent) {
                parent.classList.remove('has-error');
            }
        }
    });
    
    if (!isValid) {
        // Show first error or generic message
        const errorMessage = errors.length > 0 ? errors[0] : 'Please fill in all required fields before continuing.';
        
        // Create a better notification
        showValidationError(errorMessage);
        
        // Scroll to first error
        const firstError = step.querySelector('.is-invalid, .has-error');
        if (firstError) {
            firstError.scrollIntoView({ behavior: 'smooth', block: 'center' });
        }
    }
    
    return isValid;
}

function showValidationError(message) {
    // Remove existing error notification
    const existing = document.querySelector('.validation-error-notification');
    if (existing) {
        existing.remove();
    }
    
    // Create error notification
    const errorDiv = document.createElement('div');
    errorDiv.className = 'validation-error-notification';
    errorDiv.innerHTML = `
        <div class="error-content">
            <i class="fas fa-exclamation-circle me-2"></i>
            <span>${message}</span>
        </div>
    `;
    
    document.body.appendChild(errorDiv);
    
    // Show with animation
    setTimeout(() => errorDiv.classList.add('show'), 10);
    
    // Auto remove after 5 seconds
    setTimeout(() => {
        errorDiv.classList.remove('show');
        setTimeout(() => errorDiv.remove(), 300);
    }, 5000);
}

function changeTravelers(delta) {
    const input = document.getElementById('travelers');
    const current = parseInt(input.value) || 1;
    const newValue = Math.max(1, Math.min(20, current + delta));
    input.value = newValue;
    updateSummary();
}

// Form submission - simplified for Payment Link redirect
document.getElementById('advancedBookingForm').addEventListener('submit', function(e) {
    // Validate all steps before submission
    const steps = ['step-tour', 'step-traveler', 'step-payment'];
    let allValid = true;
    
    // Show all steps temporarily to validate
    steps.forEach(stepId => {
        const step = document.getElementById(stepId);
        if (step) {
            step.classList.add('active');
            if (!validateStep(stepId)) {
                allValid = false;
            }
            // Hide steps that aren't the payment step
            if (stepId !== 'step-payment') {
                step.classList.remove('active');
            }
        }
    });
    
    if (!allValid) {
        e.preventDefault();
        // Show payment step
        document.getElementById('step-payment').classList.add('active');
        updateProgress();
        window.scrollTo({ top: 0, behavior: 'smooth' });
        return false;
    }
    
    // Check terms agreement
    const agreeTerms = document.getElementById('agreeTerms');
    if (!agreeTerms || !agreeTerms.checked) {
        e.preventDefault();
        showValidationError('Please agree to the Terms & Conditions to continue.');
        agreeTerms.focus();
        agreeTerms.closest('.form-check-advanced')?.classList.add('has-error');
        return false;
    }
    
    // Show loading state
    const submitBtn = document.getElementById('submitPaymentBtn');
    if (submitBtn) {
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Redirecting to Payment...';
    }
    
    // Form will submit normally and redirect to Stripe Payment Link
});

function updateProgress() {
    const steps = document.querySelectorAll('.progress-step');
    const formSteps = document.querySelectorAll('.form-step');
    const activeStep = document.querySelector('.form-step.active');
    
    if (activeStep) {
        const stepIndex = Array.from(formSteps).indexOf(activeStep);
        steps.forEach((step, index) => {
            if (index <= stepIndex) {
                step.classList.add('active');
            } else {
                step.classList.remove('active');
            }
        });
    }
}
</script>
@endpush
