@extends('layouts.app')

@section('title', 'Book Your Adventure - Advanced Booking Wizard')
@section('description', 'Complete your tour booking with our advanced booking wizard. Secure, easy, and fast.')

@section('body_class', 'booking-wizard-page')

@push('styles')
<link rel="stylesheet" href="{{ asset('assets/assets/vendor/libs/bs-stepper/bs-stepper.css') }}" />
<link rel="stylesheet" href="{{ asset('assets/assets/vendor/libs/select2/select2.css') }}" />
<link rel="stylesheet" href="{{ asset('assets/assets/vendor/libs/tagify/tagify.css') }}" />
<link rel="stylesheet" href="{{ asset('assets/assets/vendor/libs/flatpickr/flatpickr.css') }}" />
<link rel="stylesheet" href="{{ asset('assets/assets/vendor/libs/@form-validation/form-validation.css') }}" />
<style>
    .booking-wizard-page {
        background: #f5f5f9;
        min-height: 100vh;
        padding: 2rem 0;
    }
    .booking-wizard-container {
        max-width: 1200px;
        margin: 0 auto;
    }
    .wizard-card {
        background: #fff;
        border-radius: 0.5rem;
        box-shadow: 0 2px 6px 0 rgba(67, 89, 113, 0.12);
        padding: 2rem;
    }
    .bs-stepper.vertical .bs-stepper-header {
        border-right: 1px solid #d9dee3;
    }
    .bs-stepper.vertical .step.active .step-trigger {
        color: #3ea572;
    }
    .bs-stepper.vertical .step.active .bs-stepper-circle {
        background-color: #3ea572;
        border-color: #3ea572;
    }
    .custom-option {
        border: 1px solid #d9dee3;
        border-radius: 0.375rem;
        padding: 1.5rem;
        cursor: pointer;
        transition: all 0.3s;
        height: 100%;
    }
    .custom-option:hover {
        border-color: #3ea572;
        box-shadow: 0 2px 8px rgba(62, 165, 114, 0.15);
    }
    .custom-option input[type="radio"]:checked + .custom-option-content,
    .custom-option input[type="checkbox"]:checked + .custom-option-content {
        border-color: #3ea572;
        background-color: #e6f4ed;
    }
    .custom-option-icon {
        text-align: center;
    }
    .custom-option-icon i {
        font-size: 2.5rem;
        color: #3ea572;
        margin-bottom: 1rem;
    }
    .custom-option-title {
        font-weight: 600;
        font-size: 1.1rem;
        margin-bottom: 0.5rem;
        color: #566a7f;
    }
    .custom-option-body small {
        color: #a1acb8;
        font-size: 0.875rem;
    }
    .tour-card {
        border: 2px solid #d9dee3;
        border-radius: 0.5rem;
        overflow: hidden;
        cursor: pointer;
        transition: all 0.3s;
        position: relative;
    }
    .tour-card:hover {
        border-color: #3ea572;
        transform: translateY(-4px);
        box-shadow: 0 4px 12px rgba(62, 165, 114, 0.2);
    }
    .tour-card.selected {
        border-color: #3ea572;
        box-shadow: 0 0 0 3px rgba(62, 165, 114, 0.2);
    }
    .tour-card.selected::before {
        content: '\2713';
        position: absolute;
        top: 10px;
        right: 10px;
        background: #3ea572;
        color: white;
        width: 30px;
        height: 30px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: bold;
        z-index: 10;
    }
    .tour-card img {
        width: 100%;
        height: 200px;
        object-fit: cover;
    }
    .tour-card-body {
        padding: 1.5rem;
    }
    .tour-card-title {
        font-size: 1.25rem;
        font-weight: 600;
        margin-bottom: 0.5rem;
        color: #566a7f;
    }
    .tour-card-price {
        font-size: 1.5rem;
        font-weight: 700;
        color: #3ea572;
        margin-top: 0.5rem;
    }
    .traveler-card {
        border: 1px solid #d9dee3;
        border-radius: 0.5rem;
        padding: 1.5rem;
        margin-bottom: 1rem;
        background: #f8f9fa;
    }
    .traveler-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1rem;
    }
    .traveler-number {
        font-weight: 600;
        color: #566a7f;
    }
    .summary-box {
        background: #f8f9fa;
        border-radius: 0.5rem;
        padding: 1.5rem;
        margin-top: 2rem;
        border: 1px solid #d9dee3;
    }
    .summary-item {
        display: flex;
        justify-content: space-between;
        padding: 0.75rem 0;
        border-bottom: 1px solid #e7e9ec;
    }
    .summary-item:last-child {
        border-bottom: none;
    }
    .summary-total {
        font-size: 1.5rem;
        font-weight: 700;
        color: #3ea572;
        margin-top: 1rem;
        padding-top: 1rem;
        border-top: 2px solid #3ea572;
    }
    .form-floating-outline .form-control:focus ~ label,
    .form-floating-outline .form-control:not(:placeholder-shown) ~ label {
        color: #3ea572;
    }
    .form-floating-outline .form-control:focus {
        border-color: #3ea572;
    }
    .invalid-feedback {
        display: block;
        color: #ea5455;
        font-size: 0.875rem;
        margin-top: 0.25rem;
    }
    .is-invalid {
        border-color: #ea5455;
    }
    .payment-method-card {
        border: 2px solid #d9dee3;
        border-radius: 0.5rem;
        padding: 1.5rem;
        text-align: center;
        cursor: pointer;
        transition: all 0.3s;
    }
    .payment-method-card:hover {
        border-color: #3ea572;
    }
    .payment-method-card input[type="radio"]:checked + .payment-method-content,
    .payment-method-card.selected {
        border-color: #3ea572;
        background-color: #e6f4ed;
    }
    .payment-method-card i {
        font-size: 2.5rem;
        color: #3ea572;
        margin-bottom: 0.5rem;
    }
    @media (max-width: 768px) {
        .bs-stepper.vertical .bs-stepper-header {
            border-right: none;
            border-bottom: 1px solid #d9dee3;
            margin-bottom: 2rem;
        }
    }
</style>
@endpush

@section('content')
<div class="booking-wizard-page">
    <div class="container-xxl booking-wizard-container">
        <div class="wizard-card">
            <!-- Booking Wizard -->
            <div id="wizard-booking" class="bs-stepper vertical mt-2">
                <div class="bs-stepper-header gap-lg-2 border-end">
                    <div class="step" data-target="#tour-selection">
                        <button type="button" class="step-trigger">
                            <span class="bs-stepper-circle"><i class="ri-route-line"></i></span>
                            <span class="bs-stepper-label">
                                <span class="bs-stepper-number">01</span>
                                <span class="d-flex flex-column ms-2">
                                    <span class="bs-stepper-title">Tour Selection</span>
                                    <span class="bs-stepper-subtitle">Choose Your Adventure</span>
                                </span>
                            </span>
                        </button>
                    </div>
                    <div class="line"></div>
                    <div class="step" data-target="#traveler-details">
                        <button type="button" class="step-trigger">
                            <span class="bs-stepper-circle"><i class="ri-user-line"></i></span>
                            <span class="bs-stepper-label">
                                <span class="bs-stepper-number">02</span>
                                <span class="d-flex flex-column ms-2">
                                    <span class="bs-stepper-title">Traveler Details</span>
                                    <span class="bs-stepper-subtitle">Personal Information</span>
                                </span>
                            </span>
                        </button>
                    </div>
                    <div class="line"></div>
                    <div class="step" data-target="#travel-preferences">
                        <button type="button" class="step-trigger">
                            <span class="bs-stepper-circle"><i class="ri-settings-3-line"></i></span>
                            <span class="bs-stepper-label">
                                <span class="bs-stepper-number">03</span>
                                <span class="d-flex flex-column ms-2">
                                    <span class="bs-stepper-title">Travel Preferences</span>
                                    <span class="bs-stepper-subtitle">Dates & Add-ons</span>
                                </span>
                            </span>
                        </button>
                    </div>
                    <div class="line"></div>
                    <div class="step" data-target="#payment-details">
                        <button type="button" class="step-trigger">
                            <span class="bs-stepper-circle"><i class="ri-bank-card-line"></i></span>
                            <span class="bs-stepper-label">
                                <span class="bs-stepper-number">04</span>
                                <span class="d-flex flex-column ms-2">
                                    <span class="bs-stepper-title">Payment</span>
                                    <span class="bs-stepper-subtitle">Payment Method</span>
                                </span>
                            </span>
                        </button>
                    </div>
                    <div class="line"></div>
                    <div class="step" data-target="#review-confirm">
                        <button type="button" class="step-trigger">
                            <span class="bs-stepper-circle"><i class="ri-check-line"></i></span>
                            <span class="bs-stepper-label">
                                <span class="bs-stepper-number">05</span>
                                <span class="d-flex flex-column ms-2">
                                    <span class="bs-stepper-title">Review & Confirm</span>
                                    <span class="bs-stepper-subtitle">Final Check</span>
                                </span>
                            </span>
                        </button>
                    </div>
                </div>

                <div class="bs-stepper-content">
                    <form id="wizard-booking-form" action="{{ route('booking.submit') }}" method="POST">
                        @csrf
                        
                        <!-- Step 1: Tour Selection -->
                        <div id="tour-selection" class="content">
                            <div class="row g-4">
                                <div class="col-12">
                                    <h4 class="mb-4">Select Your Tour</h4>
                                    <div class="row g-4">
                                        @foreach($tours as $tour)
                                        <div class="col-md-6 col-lg-4">
                                            <div class="tour-card" data-tour-id="{{ $tour['id'] }}" data-tour-price="{{ $tour['price'] }}">
                                                <img src="{{ $tour['image'] }}" alt="{{ $tour['name'] }}" onerror="this.src='https://via.placeholder.com/400x200?text=Tour+Image'">
                                                <div class="tour-card-body">
                                                    <h5 class="tour-card-title">{{ $tour['name'] }}</h5>
                                                    <p class="text-muted small mb-2">{{ $tour['destination'] }}</p>
                                                    <p class="text-muted small mb-2">
                                                        <i class="ri-calendar-line"></i> {{ $tour['duration_days'] }} Days
                                                        @if(isset($tour['rating']))
                                                        | <i class="ri-star-fill text-warning"></i> {{ number_format($tour['rating'], 1) }}
                                                        @endif
                                                    </p>
                                                    <div class="tour-card-price">${{ number_format($tour['price'], 2) }}</div>
                                                </div>
                                            </div>
                                        </div>
                                        @endforeach
                                    </div>
                                    <input type="hidden" name="tourId" id="selectedTourId" required>
                                    <div class="invalid-feedback" id="tourError"></div>
                                </div>
                                <div class="col-12 d-flex justify-content-between">
                                    <button class="btn btn-outline-secondary btn-prev" disabled>
                                        <i class="ri-arrow-left-line me-1"></i> Previous
                                    </button>
                                    <button class="btn btn-primary btn-next">
                                        Next <i class="ri-arrow-right-line ms-1"></i>
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Step 2: Traveler Details -->
                        <div id="traveler-details" class="content">
                            <div class="row g-4">
                                <div class="col-12">
                                    <h4 class="mb-4">Primary Contact Information</h4>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-floating form-floating-outline">
                                        <input type="text" id="firstName" name="firstName" class="form-control" placeholder="John" required>
                                        <label for="firstName">First Name <span class="text-danger">*</span></label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-floating form-floating-outline">
                                        <input type="text" id="lastName" name="lastName" class="form-control" placeholder="Doe" required>
                                        <label for="lastName">Last Name <span class="text-danger">*</span></label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-floating form-floating-outline">
                                        <input type="email" id="email" name="email" class="form-control" placeholder="john@example.com" required>
                                        <label for="email">Email Address <span class="text-danger">*</span></label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-floating form-floating-outline">
                                        <input type="tel" id="phone" name="phone" class="form-control" placeholder="+255 123 456 789" required>
                                        <label for="phone">Phone Number <span class="text-danger">*</span></label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-floating form-floating-outline">
                                        <select id="country" name="country" class="form-select" required>
                                            <option value="Tanzania" selected>Tanzania</option>
                                            <option value="Kenya">Kenya</option>
                                            <option value="Uganda">Uganda</option>
                                            <option value="Rwanda">Rwanda</option>
                                            <option value="United States">United States</option>
                                            <option value="United Kingdom">United Kingdom</option>
                                            <option value="Canada">Canada</option>
                                            <option value="Australia">Australia</option>
                                            <option value="Germany">Germany</option>
                                            <option value="France">France</option>
                                            <option value="Other">Other</option>
                                        </select>
                                        <label for="country">Country <span class="text-danger">*</span></label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-floating form-floating-outline">
                                        <input type="text" id="address" name="address" class="form-control" placeholder="Street Address">
                                        <label for="address">Address (Optional)</label>
                                    </div>
                                </div>
                                
                                <div class="col-12">
                                    <hr class="my-4">
                                    <h5 class="mb-3">Number of Travelers</h5>
                                    <div class="row g-3">
                                        <div class="col-md-6">
                                            <div class="form-floating form-floating-outline">
                                                <input type="number" id="numTravelers" name="numTravelers" class="form-control" min="1" max="20" value="1" required>
                                                <label for="numTravelers">Total Travelers <span class="text-danger">*</span></label>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-12" id="travelersContainer">
                                    <!-- Dynamic traveler forms will be added here -->
                                </div>

                                <div class="col-12">
                                    <hr class="my-4">
                                    <h5 class="mb-3">Emergency Contact</h5>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-floating form-floating-outline">
                                        <input type="text" id="emergencyName" name="emergency_contact_name" class="form-control" placeholder="Emergency Contact Name">
                                        <label for="emergencyName">Emergency Contact Name</label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-floating form-floating-outline">
                                        <input type="tel" id="emergencyPhone" name="emergency_contact_phone" class="form-control" placeholder="+255 123 456 789">
                                        <label for="emergencyPhone">Emergency Contact Phone</label>
                                    </div>
                                </div>

                                <div class="col-12 d-flex justify-content-between">
                                    <button class="btn btn-outline-secondary btn-prev">
                                        <i class="ri-arrow-left-line me-1"></i> Previous
                                    </button>
                                    <button class="btn btn-primary btn-next">
                                        Next <i class="ri-arrow-right-line ms-1"></i>
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Step 3: Travel Preferences -->
                        <div id="travel-preferences" class="content">
                            <div class="row g-4">
                                <div class="col-12">
                                    <h4 class="mb-4">Travel Dates & Preferences</h4>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-floating form-floating-outline">
                                        <input type="text" id="departureDate" name="date" class="form-control flatpickr" placeholder="Select Date" required>
                                        <label for="departureDate">Departure Date <span class="text-danger">*</span></label>
                                        <div class="invalid-feedback" id="dateError"></div>
                                    </div>
                                    <small class="text-muted">Please select a date at least 7 days from today</small>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-floating form-floating-outline">
                                        <select id="roomPreference" name="room_preference" class="form-select">
                                            <option value="single">Single Room</option>
                                            <option value="double" selected>Double Room</option>
                                            <option value="triple">Triple Room</option>
                                            <option value="shared">Shared Room</option>
                                        </select>
                                        <label for="roomPreference">Room Preference</label>
                                    </div>
                                </div>

                                <div class="col-12">
                                    <hr class="my-4">
                                    <h5 class="mb-3">Dietary Requirements</h5>
                                    <div class="row g-3">
                                        <div class="col-md-6">
                                            <div class="form-check custom-option custom-option-icon">
                                                <input class="form-check-input" type="radio" name="dietary" id="dietaryNone" value="none" checked>
                                                <label class="form-check-label custom-option-content" for="dietaryNone">
                                                    <span class="custom-option-body">
                                                        <i class="ri-restaurant-line"></i>
                                                        <span class="custom-option-title">No Special Diet</span>
                                                        <small>Standard meals included</small>
                                                    </span>
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-check custom-option custom-option-icon">
                                                <input class="form-check-input" type="radio" name="dietary" id="dietaryVegetarian" value="vegetarian">
                                                <label class="form-check-label custom-option-content" for="dietaryVegetarian">
                                                    <span class="custom-option-body">
                                                        <i class="ri-leaf-line"></i>
                                                        <span class="custom-option-title">Vegetarian</span>
                                                        <small>Vegetarian meals preferred</small>
                                                    </span>
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-check custom-option custom-option-icon">
                                                <input class="form-check-input" type="radio" name="dietary" id="dietaryVegan" value="vegan">
                                                <label class="form-check-label custom-option-content" for="dietaryVegan">
                                                    <span class="custom-option-body">
                                                        <i class="ri-plant-line"></i>
                                                        <span class="custom-option-title">Vegan</span>
                                                        <small>Vegan meals preferred</small>
                                                    </span>
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-check custom-option custom-option-icon">
                                                <input class="form-check-input" type="radio" name="dietary" id="dietaryHalal" value="halal">
                                                <label class="form-check-label custom-option-content" for="dietaryHalal">
                                                    <span class="custom-option-body">
                                                        <i class="ri-restaurant-2-line"></i>
                                                        <span class="custom-option-title">Halal</span>
                                                        <small>Halal meals preferred</small>
                                                    </span>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-12">
                                    <hr class="my-4">
                                    <h5 class="mb-3">Optional Add-ons</h5>
                                    <div class="row g-3">
                                        <div class="col-md-4">
                                            <div class="form-check custom-option">
                                                <input class="form-check-input" type="checkbox" name="addons[]" id="addonInsurance" value="insurance">
                                                <label class="form-check-label custom-option-content" for="addonInsurance">
                                                    <span class="custom-option-body">
                                                        <i class="ri-shield-check-line custom-option-icon"></i>
                                                        <span class="custom-option-title">Travel Insurance</span>
                                                        <small>Comprehensive coverage</small>
                                                        <div class="mt-2"><strong class="text-primary">+$150</strong></div>
                                                    </span>
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-check custom-option">
                                                <input class="form-check-input" type="checkbox" name="addons[]" id="addonGear" value="gear">
                                                <label class="form-check-label custom-option-content" for="addonGear">
                                                    <span class="custom-option-body">
                                                        <i class="ri-hiking-line custom-option-icon"></i>
                                                        <span class="custom-option-title">Gear Rental</span>
                                                        <small>Hiking & camping gear</small>
                                                        <div class="mt-2"><strong class="text-primary">+$80</strong></div>
                                                    </span>
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-check custom-option">
                                                <input class="form-check-input" type="checkbox" name="addons[]" id="addonAirport" value="airport">
                                                <label class="form-check-label custom-option-content" for="addonAirport">
                                                    <span class="custom-option-body">
                                                        <i class="ri-flight-takeoff-line custom-option-icon"></i>
                                                        <span class="custom-option-title">Airport Transfer</span>
                                                        <small>Round trip transfer</small>
                                                        <div class="mt-2"><strong class="text-primary">+$50</strong></div>
                                                    </span>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-12">
                                    <div class="form-floating form-floating-outline">
                                        <textarea id="specialRequirements" name="special_requirements" class="form-control" style="height: 120px;" placeholder="Any special requirements, accessibility needs, or other requests..."></textarea>
                                        <label for="specialRequirements">Special Requirements or Requests</label>
                                    </div>
                                </div>

                                <div class="col-12 d-flex justify-content-between">
                                    <button class="btn btn-outline-secondary btn-prev">
                                        <i class="ri-arrow-left-line me-1"></i> Previous
                                    </button>
                                    <button class="btn btn-primary btn-next">
                                        Next <i class="ri-arrow-right-line ms-1"></i>
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Step 4: Payment Details -->
                        <div id="payment-details" class="content">
                            <div class="row g-4">
                                <div class="col-12">
                                    <h4 class="mb-4">Payment Method</h4>
                                    <div class="row g-3 mb-4">
                                        <div class="col-md-3">
                                            <div class="payment-method-card" data-payment="card">
                                                <input type="radio" name="payment_method" id="paymentCard" value="card" checked>
                                                <div class="payment-method-content">
                                                    <i class="ri-bank-card-line"></i>
                                                    <div class="mt-2"><strong>Credit/Debit Card</strong></div>
                                                    <small class="text-muted">Via Pesapal</small>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="payment-method-card" data-payment="pesapal">
                                                <input type="radio" name="payment_method" id="paymentPesapal" value="pesapal">
                                                <div class="payment-method-content">
                                                    <i class="ri-wallet-line"></i>
                                                    <div class="mt-2"><strong>Pesapal</strong></div>
                                                    <small class="text-muted">Secure Payment</small>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="payment-method-card" data-payment="mpesa">
                                                <input type="radio" name="payment_method" id="paymentMpesa" value="mpesa">
                                                <div class="payment-method-content">
                                                    <i class="ri-smartphone-line"></i>
                                                    <div class="mt-2"><strong>M-Pesa</strong></div>
                                                    <small class="text-muted">Mobile Money</small>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="payment-method-card" data-payment="later">
                                                <input type="radio" name="payment_method" id="paymentLater" value="later">
                                                <div class="payment-method-content">
                                                    <i class="ri-file-invoice-line"></i>
                                                    <div class="mt-2"><strong>Pay Later</strong></div>
                                                    <small class="text-muted">Invoice will be sent</small>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div id="cardPaymentDetails" class="col-12">
                                    <hr class="my-4">
                                    <div class="alert alert-info mb-3">
                                        <i class="ri-information-line me-2"></i>
                                        <strong>Secure Payment:</strong> You will be redirected to Pesapal's secure payment page to complete your transaction. Your card details are processed securely by Pesapal.
                                    </div>
                                    <h5 class="mb-3">Card Details (Optional - Can be entered on Pesapal page)</h5>
                                    <div class="row g-3">
                                        <div class="col-12">
                                            <div class="form-floating form-floating-outline">
                                                <input type="text" id="cardName" name="card-name" class="form-control" placeholder="John M. Doe">
                                                <label for="cardName">Name on Card</label>
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <div class="form-floating form-floating-outline">
                                                <input type="text" id="cardNumber" name="card-number" class="form-control" placeholder="1234 5678 9876 5432" maxlength="19">
                                                <label for="cardNumber">Card Number</label>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-floating form-floating-outline">
                                                <input type="text" id="cardExpiry" name="expiry" class="form-control" placeholder="MM/YY" maxlength="5">
                                                <label for="cardExpiry">Expiry Date (MM/YY)</label>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-floating form-floating-outline">
                                                <input type="text" id="cardCVC" name="cvc" class="form-control" placeholder="123" maxlength="4">
                                                <label for="cardCVC">CVC</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <div id="pesapalPaymentInfo" class="col-12" style="display: none;">
                                    <hr class="my-4">
                                    <div class="alert alert-info">
                                        <i class="ri-wallet-line me-2"></i>
                                        <strong>Pesapal Payment:</strong> You will be redirected to Pesapal's secure payment page where you can pay using your preferred method (Card, Mobile Money, etc.).
                                    </div>
                                </div>

                                <div class="col-12">
                                    <div class="summary-box">
                                        <h5 class="mb-3">Booking Summary</h5>
                                        <div class="summary-item">
                                            <span>Tour:</span>
                                            <span id="summaryTour">-</span>
                                        </div>
                                        <div class="summary-item">
                                            <span>Travelers:</span>
                                            <span id="summaryTravelers">-</span>
                                        </div>
                                        <div class="summary-item">
                                            <span>Departure Date:</span>
                                            <span id="summaryDate">-</span>
                                        </div>
                                        <div class="summary-item">
                                            <span>Base Price:</span>
                                            <span id="summaryBasePrice">-</span>
                                        </div>
                                        <div class="summary-item" id="summaryAddons" style="display: none;">
                                            <span>Add-ons:</span>
                                            <span id="summaryAddonsValue">-</span>
                                        </div>
                                        <div class="summary-total">
                                            <span>Total:</span>
                                            <span id="summaryTotal">-</span>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-12 d-flex justify-content-between">
                                    <button class="btn btn-outline-secondary btn-prev">
                                        <i class="ri-arrow-left-line me-1"></i> Previous
                                    </button>
                                    <button class="btn btn-primary btn-next">
                                        Next <i class="ri-arrow-right-line ms-1"></i>
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Step 5: Review & Confirm -->
                        <div id="review-confirm" class="content">
                            <div class="row g-4">
                                <div class="col-12 text-center mb-4">
                                    <i class="ri-checkbox-circle-line text-success" style="font-size: 4rem;"></i>
                                    <h4 class="mt-3">Review Your Booking</h4>
                                    <p class="text-muted">Please review all details before confirming your booking</p>
                                </div>

                                <div class="col-12">
                                    <div class="card">
                                        <div class="card-body">
                                            <h5 class="card-title mb-3">Booking Details</h5>
                                            <div id="reviewContent">
                                                <!-- Review content will be populated by JavaScript -->
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-12">
                                    <div class="form-check mb-3">
                                        <input class="form-check-input" type="checkbox" id="agreeTerms" required>
                                        <label class="form-check-label" for="agreeTerms">
                                            I agree to the <a href="#" target="_blank">Terms and Conditions</a> and <a href="#" target="_blank">Privacy Policy</a>
                                        </label>
                                    </div>
                                </div>

                                <div class="col-12 d-flex justify-content-between">
                                    <button class="btn btn-outline-secondary btn-prev">
                                        <i class="ri-arrow-left-line me-1"></i> Previous
                                    </button>
                                    <button type="submit" class="btn btn-success btn-submit">
                                        <i class="ri-check-line me-1"></i> Confirm Booking
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="{{ asset('assets/assets/vendor/libs/jquery/jquery.js') }}"></script>
<script src="{{ asset('assets/assets/vendor/libs/bs-stepper/bs-stepper.js') }}"></script>
<script src="{{ asset('assets/assets/vendor/libs/select2/select2.js') }}"></script>
<script src="{{ asset('assets/assets/vendor/libs/tagify/tagify.js') }}"></script>
<script src="{{ asset('assets/assets/vendor/libs/flatpickr/flatpickr.js') }}"></script>
<script src="{{ asset('assets/assets/vendor/libs/@form-validation/popular.js') }}"></script>
<script src="{{ asset('assets/assets/vendor/libs/@form-validation/bootstrap5.js') }}"></script>
<script src="{{ asset('assets/assets/vendor/libs/@form-validation/auto-focus.js') }}"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize BS Stepper
    const wizardBooking = document.querySelector('#wizard-booking');
    const stepper = new Stepper(wizardBooking, {
        linear: true,
        animation: true
    });

    // Initialize Flatpickr
    const minDate = new Date();
    minDate.setDate(minDate.getDate() + 7);
    if (typeof flatpickr !== 'undefined') {
        const datePicker = flatpickr('#departureDate', {
            minDate: minDate,
            dateFormat: 'Y-m-d',
            onChange: function(selectedDates, dateStr, instance) {
                updateSummary();
                checkAvailability();
            }
        });
    }

    // Tour Selection
    let selectedTour = null;
    document.querySelectorAll('.tour-card').forEach(card => {
        card.addEventListener('click', function() {
            document.querySelectorAll('.tour-card').forEach(c => c.classList.remove('selected'));
            this.classList.add('selected');
            selectedTour = {
                id: this.dataset.tourId,
                price: parseFloat(this.dataset.tourPrice),
                name: this.querySelector('.tour-card-title').textContent
            };
            document.getElementById('selectedTourId').value = selectedTour.id;
            document.getElementById('tourError').textContent = '';
            updateSummary();
        });
    });

    // Number of Travelers
    const numTravelersInput = document.getElementById('numTravelers');
    numTravelersInput.addEventListener('change', function() {
        generateTravelerForms(parseInt(this.value));
        updateSummary();
    });

    // Payment Method Selection
    document.querySelectorAll('.payment-method-card').forEach(card => {
        card.addEventListener('click', function() {
            document.querySelectorAll('.payment-method-card').forEach(c => c.classList.remove('selected'));
            this.classList.add('selected');
            const radio = this.querySelector('input[type="radio"]');
            radio.checked = true;
            
            // Show/hide payment details
            const cardDetails = document.getElementById('cardPaymentDetails');
            const pesapalInfo = document.getElementById('pesapalPaymentInfo');
            
            if (radio.value === 'card') {
                cardDetails.style.display = 'block';
                if (pesapalInfo) pesapalInfo.style.display = 'none';
            } else if (radio.value === 'pesapal') {
                cardDetails.style.display = 'none';
                if (pesapalInfo) pesapalInfo.style.display = 'block';
            } else {
                cardDetails.style.display = 'none';
                if (pesapalInfo) pesapalInfo.style.display = 'none';
            }
        });
    });

    // Add-ons pricing
    const addonsPricing = {
        insurance: 150,
        gear: 80,
        airport: 50
    };

    // Update summary
    function updateSummary() {
        if (!selectedTour) return;
        
        const numTravelers = parseInt(numTravelersInput.value) || 1;
        const departureDate = document.getElementById('departureDate').value;
        const selectedAddons = Array.from(document.querySelectorAll('input[name="addons[]"]:checked')).map(cb => cb.value);
        
        let basePrice = selectedTour.price * numTravelers;
        let addonsTotal = selectedAddons.reduce((sum, addon) => sum + (addonsPricing[addon] || 0), 0);
        let total = basePrice + addonsTotal;

        document.getElementById('summaryTour').textContent = selectedTour.name;
        document.getElementById('summaryTravelers').textContent = numTravelers;
        document.getElementById('summaryDate').textContent = departureDate || '-';
        document.getElementById('summaryBasePrice').textContent = '$' + basePrice.toFixed(2);
        
        if (selectedAddons.length > 0) {
            document.getElementById('summaryAddons').style.display = 'flex';
            document.getElementById('summaryAddonsValue').textContent = '$' + addonsTotal.toFixed(2);
        } else {
            document.getElementById('summaryAddons').style.display = 'none';
        }
        
        document.getElementById('summaryTotal').textContent = '$' + total.toFixed(2);
    }

    // Generate traveler forms
    function generateTravelerForms(count) {
        const container = document.getElementById('travelersContainer');
        container.innerHTML = '';
        
        if (count > 1) {
            container.innerHTML = '<h5 class="mb-3">Additional Traveler Information</h5>';
            for (let i = 2; i <= count; i++) {
                const travelerCard = document.createElement('div');
                travelerCard.className = 'traveler-card';
                travelerCard.innerHTML = `
                    <div class="traveler-header">
                        <span class="traveler-number">Traveler ${i}</span>
                    </div>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="form-floating form-floating-outline">
                                <input type="text" name="traveler_${i}_first_name" class="form-control" placeholder="First Name">
                                <label>First Name</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-floating form-floating-outline">
                                <input type="text" name="traveler_${i}_last_name" class="form-control" placeholder="Last Name">
                                <label>Last Name</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-floating form-floating-outline">
                                <input type="date" name="traveler_${i}_dob" class="form-control">
                                <label>Date of Birth</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-floating form-floating-outline">
                                <select name="traveler_${i}_gender" class="form-select">
                                    <option value="">Select</option>
                                    <option value="male">Male</option>
                                    <option value="female">Female</option>
                                    <option value="other">Other</option>
                                </select>
                                <label>Gender</label>
                            </div>
                        </div>
                    </div>
                `;
                container.appendChild(travelerCard);
            }
        }
    }

    // Check availability
    async function checkAvailability() {
        const tourId = document.getElementById('selectedTourId').value;
        const date = document.getElementById('departureDate').value;
        const travelers = parseInt(numTravelersInput.value) || 1;

        if (!tourId || !date) return;

        try {
            const response = await fetch('{{ route("booking.check-availability") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({ tour_id: tourId, date: date, travelers: travelers })
            });

            const data = await response.json();
            const dateError = document.getElementById('dateError');
            
            if (!data.available) {
                dateError.textContent = data.message;
                document.getElementById('departureDate').classList.add('is-invalid');
            } else {
                dateError.textContent = '';
                document.getElementById('departureDate').classList.remove('is-invalid');
            }
        } catch (error) {
            console.error('Availability check failed:', error);
        }
    }

    // Form validation before next step
    wizardBooking.addEventListener('show.bs-stepper', function(event) {
        const currentStep = event.detail.indexStep;
        
        // Validate step 1
        if (currentStep === 1 && !selectedTour) {
            event.preventDefault();
            document.getElementById('tourError').textContent = 'Please select a tour';
            return;
        }
        
        // Validate step 2
        if (currentStep === 2) {
            const firstName = document.getElementById('firstName').value;
            const lastName = document.getElementById('lastName').value;
            const email = document.getElementById('email').value;
            const phone = document.getElementById('phone').value;
            
            if (!firstName || !lastName || !email || !phone) {
                event.preventDefault();
                alert('Please fill in all required fields');
                return;
            }
        }
        
        // Validate step 3
        if (currentStep === 3) {
            const date = document.getElementById('departureDate').value;
            if (!date) {
                event.preventDefault();
                alert('Please select a departure date');
                return;
            }
        }
        
        // Validate step 4
        if (currentStep === 4) {
            updateSummary();
        }
        
        // Generate review content for step 5
        if (currentStep === 4) {
            generateReviewContent();
        }
    });

    // Generate review content
    function generateReviewContent() {
        const reviewContent = document.getElementById('reviewContent');
        const formData = new FormData(document.getElementById('wizard-booking-form'));
        
        let html = `
            <p><strong>Tour:</strong> ${selectedTour ? selectedTour.name : '-'}</p>
            <p><strong>Travelers:</strong> ${numTravelersInput.value}</p>
            <p><strong>Departure Date:</strong> ${document.getElementById('departureDate').value || '-'}</p>
            <p><strong>Primary Contact:</strong> ${document.getElementById('firstName').value} ${document.getElementById('lastName').value}</p>
            <p><strong>Email:</strong> ${document.getElementById('email').value}</p>
            <p><strong>Phone:</strong> ${document.getElementById('phone').value}</p>
            <p><strong>Payment Method:</strong> ${document.querySelector('input[name="payment_method"]:checked')?.value || '-'}</p>
        `;
        
        reviewContent.innerHTML = html;
    }

    // Card number formatting
    document.getElementById('cardNumber')?.addEventListener('input', function(e) {
        let value = e.target.value.replace(/\s/g, '');
        let formattedValue = value.match(/.{1,4}/g)?.join(' ') || value;
        e.target.value = formattedValue;
    });

    // Expiry date formatting
    document.getElementById('cardExpiry')?.addEventListener('input', function(e) {
        let value = e.target.value.replace(/\D/g, '');
        if (value.length >= 2) {
            value = value.substring(0, 2) + '/' + value.substring(2, 4);
        }
        e.target.value = value;
    });

    // Form submission
    document.getElementById('wizard-booking-form').addEventListener('submit', function(e) {
        if (!document.getElementById('agreeTerms').checked) {
            e.preventDefault();
            alert('Please agree to the Terms and Conditions');
            return;
        }
        
        // Combine first and last name for backend
        const firstName = document.getElementById('firstName').value;
        const lastName = document.getElementById('lastName').value;
        const hiddenName = document.createElement('input');
        hiddenName.type = 'hidden';
        hiddenName.name = 'name';
        hiddenName.value = firstName + ' ' + lastName;
        this.appendChild(hiddenName);
        
        // Set travelers count
        const hiddenTravelers = document.createElement('input');
        hiddenTravelers.type = 'hidden';
        hiddenTravelers.name = 'travelers';
        hiddenTravelers.value = numTravelersInput.value;
        this.appendChild(hiddenTravelers);
    });
});
</script>
@endpush

