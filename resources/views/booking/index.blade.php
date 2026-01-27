@extends('layouts.app')

@section('title', 'Book Your Tanzania Adventure - Lau Paradise Adventures')
@section('description', 'Secure your spot on one of our Tanzania tours. Our simple and secure booking process is the first step on your journey of a lifetime.')

@section('content')

<!-- Hero Section -->
<section class="page-hero-section" style="background-image: url('{{ isset($selectedTour) && $selectedTour ? $selectedTour['image'] : asset('images/safari_home-1.jpg') }}');">
    <div class="page-hero-overlay"></div>
        <div class="container">
        <div class="page-hero-content" data-aos="fade-up">
            <span class="hero-badge"><i class="fas fa-calendar-check"></i> Book Now</span>
            <h1 class="page-hero-title">Book Your Adventure</h1>
            <p class="page-hero-subtitle">
                @if(isset($selectedTour) && $selectedTour)
                    Ready to book {{ $selectedTour['name'] }}? Complete the form below to secure your spot.
                @else
                    Your next great story is just a few clicks away. Complete the form below to secure your spot.
                @endif
            </p>
        </div>
        </div>
    </section>

    <!-- Booking Form Section -->
<section class="booking-form-section">
        <div class="container">
        <div class="booking-wrapper" x-data="bookingWizard()">
                    <!-- Progress Bar -->
            <div class="booking-progress" data-aos="fade-up">
                <div class="progress-steps">
                    <div class="progress-step" :class="{ 'active': currentStep >= 1, 'completed': currentStep > 1 }">
                        <div class="step-number">1</div>
                        <div class="step-label">Traveler Info</div>
                                    </div>
                    <div class="progress-line" :class="{ 'completed': currentStep > 1 }"></div>
                    <div class="progress-step" :class="{ 'active': currentStep >= 2, 'completed': currentStep > 2 }">
                        <div class="step-number">2</div>
                        <div class="step-label">Tour Selection</div>
                                </div>
                    <div class="progress-line" :class="{ 'completed': currentStep > 2 }"></div>
                    <div class="progress-step" :class="{ 'active': currentStep >= 3, 'completed': currentStep > 3 }">
                        <div class="step-number">3</div>
                        <div class="step-label">Add-ons</div>
                            </div>
                    <div class="progress-line" :class="{ 'completed': currentStep > 3 }"></div>
                    <div class="progress-step" :class="{ 'active': currentStep >= 4, 'completed': currentStep > 4 }">
                        <div class="step-number">4</div>
                        <div class="step-label">Documents</div>
                    </div>
                    <div class="progress-line" :class="{ 'completed': currentStep > 4 }"></div>
                    <div class="progress-step" :class="{ 'active': currentStep >= 5, 'completed': currentStep > 5 }">
                        <div class="step-number">5</div>
                        <div class="step-label">Payment</div>
                    </div>
                    <div class="progress-line" :class="{ 'completed': currentStep > 5 }"></div>
                    <div class="progress-step" :class="{ 'active': currentStep >= 6 }">
                        <div class="step-number">6</div>
                        <div class="step-label">Confirm</div>
                    </div>
                </div>
                    </div>

            <!-- Booking Form Card -->
            <div class="booking-form-card" data-aos="fade-up">
                <form action="{{ route('booking.submit') }}" method="POST" @submit.prevent="submitBooking" enctype="multipart/form-data">
                        @csrf
                    
                    <!-- Tab 1: Traveler Information -->
                    <div class="booking-tab" x-show="currentStep === 1" x-transition>
                        <div class="tab-header">
                            <h2><i class="fas fa-user"></i> Traveler Information</h2>
                            <p>Please provide your personal details for the booking</p>
                                        </div>
                        <div class="form-grid">
                            <div class="form-group">
                                <label for="full_name">Full Name <span class="required">*</span></label>
                                <input type="text" id="full_name" name="full_name" x-model="formData.full_name" class="form-input" required>
                                    </div>
                            <div class="form-group">
                                <label for="email">Email Address <span class="required">*</span></label>
                                <input type="email" id="email" name="email" x-model="formData.email" class="form-input" required>
                            </div>
                            <div class="form-group">
                                <label for="phone">Phone Number <span class="required">*</span></label>
                                <input type="tel" id="phone" name="phone" x-model="formData.phone" class="form-input" placeholder="+255 123 456 789" required>
                        </div>
                                <div class="form-group">
                                <label for="nationality">Nationality</label>
                                <input type="text" id="nationality" name="nationality" x-model="formData.nationality" class="form-input" placeholder="e.g., Tanzanian, American">
                                </div>
                                <div class="form-group">
                                <label for="date_of_birth">Date of Birth</label>
                                <input type="date" id="date_of_birth" name="date_of_birth" x-model="formData.date_of_birth" class="form-input">
                                </div>
                            <div class="form-group">
                                <label for="gender">Gender</label>
                                <select id="gender" name="gender" x-model="formData.gender" class="form-input">
                                    <option value="">Select Gender</option>
                                    <option value="male">Male</option>
                                    <option value="female">Female</option>
                                    <option value="other">Other</option>
                                </select>
                            </div>
                                <div class="form-group">
                                <label for="passport_number">Passport/ID Number</label>
                                <input type="text" id="passport_number" name="passport_number" x-model="formData.passport_number" class="form-input" placeholder="Optional">
                                </div>
                                <div class="form-group">
                                <label for="address">Address (City, Country)</label>
                                <input type="text" id="address" name="address" x-model="formData.address" class="form-input" placeholder="City, Country">
                            </div>
                        </div>
                        <div class="tab-actions">
                            <button type="button" @click="nextStep()" class="btn-primary">
                                Next: Tour Selection <i class="fas fa-arrow-right"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Tab 2: Tour/Package Selection -->
                    <div class="booking-tab" x-show="currentStep === 2" x-transition>
                        <div class="tab-header">
                            <h2><i class="fas fa-route"></i> Tour/Package Selection</h2>
                            <p>Choose your Tanzania adventure and travel dates</p>
                        </div>
                        
                        @if(isset($selectedTour) && $selectedTour)
                        <!-- Pre-selected Tour Card -->
                        <div class="selected-tour-card" data-aos="fade-up">
                            <div class="selected-tour-badge">
                                <i class="fas fa-check-circle"></i> Pre-selected Tour
                            </div>
                            <div class="selected-tour-content">
                                <div class="selected-tour-image">
                                    <img src="{{ $selectedTour['image'] }}" alt="{{ $selectedTour['name'] }}">
                                </div>
                                <div class="selected-tour-info">
                                    <h3>{{ $selectedTour['name'] }}</h3>
                                    <div class="selected-tour-meta">
                                        <span><i class="fas fa-map-marker-alt"></i> {{ $selectedTour['destination'] }}</span>
                                        <span><i class="fas fa-clock"></i> {{ $selectedTour['duration_days'] }} Days</span>
                                        <span><i class="fas fa-star"></i> {{ number_format($selectedTour['rating'], 1) }}</span>
                                    </div>
                                    <p class="selected-tour-description">{{ $selectedTour['description'] }}</p>
                                    <div class="selected-tour-price">
                                        <span class="price-label">Starting from</span>
                                        <span class="price-amount">${{ number_format($selectedTour['price']) }}</span>
                                        <span class="price-note">per person</span>
                                    </div>
                                    <a href="{{ route('tours.show', $selectedTour['slug']) }}" class="view-tour-link" target="_blank">
                                        <i class="fas fa-external-link-alt"></i> View Full Details
                                    </a>
                                </div>
                            </div>
                        </div>
                        @endif
                        
                        <div class="form-grid">
                            <div class="form-group full-width">
                                <label for="tour_id">Select Tour / Package <span class="required">*</span></label>
                                <select id="tour_id" name="tour_id" x-model="formData.tour_id" @change="updateTourDetails()" class="form-input" required>
                                    <option value="">Choose a tour...</option>
                                    @if(isset($tours) && count($tours) > 0)
                                        @foreach($tours as $tour)
                                            <option value="{{ $tour['id'] }}" data-price="{{ $tour['price'] }}" data-duration="{{ $tour['duration_days'] }}" {{ (isset($selectedTourId) && $tour['id'] == $selectedTourId) ? 'selected' : '' }}>
                                                {{ $tour['name'] }} - ${{ number_format($tour['price']) }} ({{ $tour['duration_days'] }} Days)
                                            </option>
                                        @endforeach
                                    @else
                                        <option value="1" data-price="2800" data-duration="7">Kilimanjaro Machame Route - $2,800 (7 Days)</option>
                                        <option value="2" data-price="3500" data-duration="6">Serengeti Great Migration Safari - $3,500 (6 Days)</option>
                                        <option value="3" data-price="1200" data-duration="5">Zanzibar Beach Paradise - $1,200 (5 Days)</option>
                                        <option value="4" data-price="2400" data-duration="4">Ngorongoro Crater Safari - $2,400 (4 Days)</option>
                                    @endif
                                </select>
                                </div>
                                
                                <!-- Tour Cards Grid (Alternative Selection Method) -->
                                @if(isset($tours) && count($tours) > 0)
                                <div class="form-group full-width">
                                    <label>Or Select from Popular Tours</label>
                                    <div class="tour-cards-grid">
                                        @foreach($tours->take(6) as $tour)
                                        <div class="tour-select-card" :class="{ 'selected': formData.tour_id == '{{ $tour['id'] }}' }" @click="selectTourCard('{{ $tour['id'] }}', {{ $tour['price'] }}, {{ $tour['duration_days'] }}, '{{ addslashes($tour['name']) }}')">
                                            <div class="tour-select-image">
                                                <img src="{{ $tour['image'] }}" alt="{{ $tour['name'] }}">
                                                @if(isset($selectedTourId) && $tour['id'] == $selectedTourId)
                                                <div class="tour-select-badge">
                                                    <i class="fas fa-check-circle"></i> Selected
                                                </div>
                                                @endif
                                            </div>
                                            <div class="tour-select-content">
                                                <h4>{{ $tour['name'] }}</h4>
                                                <div class="tour-select-meta">
                                                    <span><i class="fas fa-clock"></i> {{ $tour['duration_days'] }} Days</span>
                                                    <span><i class="fas fa-dollar-sign"></i> ${{ number_format($tour['price']) }}</span>
                                                </div>
                                            </div>
                                        </div>
                                        @endforeach
                                    </div>
                                </div>
                                @endif
                                <div class="form-group">
                                <label for="tour_start_date">Tour Start Date <span class="required">*</span></label>
                                <input type="date" id="tour_start_date" name="tour_start_date" x-model="formData.tour_start_date" :min="minDate" @change="calculateEndDate()" class="form-input" required>
                                </div>
                            <div class="form-group">
                                <label for="tour_end_date">Tour End Date</label>
                                <input type="date" id="tour_end_date" name="tour_end_date" x-model="formData.tour_end_date" class="form-input" readonly>
                            </div>
                                <div class="form-group">
                                <label for="number_of_adults">Number of Adults <span class="required">*</span></label>
                                <input type="number" id="number_of_adults" name="number_of_adults" x-model="formData.number_of_adults" @change="calculateTotal()" min="1" class="form-input" required>
                                </div>
                                <div class="form-group">
                                <label for="number_of_children">Number of Children</label>
                                <input type="number" id="number_of_children" name="number_of_children" x-model="formData.number_of_children" @change="calculateTotal()" min="0" class="form-input" value="0">
                                </div>
                            <div class="form-group">
                                <label for="pickup_location">Pickup Location</label>
                                <select id="pickup_location" name="pickup_location" x-model="formData.pickup_location" class="form-input">
                                    <option value="">Select Pickup Location</option>
                                    <option value="airport">Airport (Kilimanjaro/Arusha)</option>
                                    <option value="hotel">Hotel in Arusha</option>
                                    <option value="hotel_moshi">Hotel in Moshi</option>
                                    <option value="other">Other Location</option>
                                </select>
                            </div>
                                <div class="form-group">
                                <label for="preferred_language">Preferred Language</label>
                                <select id="preferred_language" name="preferred_language" x-model="formData.preferred_language" class="form-input">
                                    <option value="english">English</option>
                                    <option value="swahili">Swahili</option>
                                    <option value="french">French</option>
                                    <option value="german">German</option>
                                    <option value="spanish">Spanish</option>
                                </select>
                                    </div>
                                    </div>
                        <div class="tab-actions">
                            <button type="button" @click="prevStep()" class="btn-secondary">
                                <i class="fas fa-arrow-left"></i> Previous
                            </button>
                            <button type="button" @click="nextStep()" class="btn-primary">
                                Next: Add-ons <i class="fas fa-arrow-right"></i>
                            </button>
                                    </div>
                                </div>

                    <!-- Tab 3: Add-ons & Special Requests -->
                    <div class="booking-tab" x-show="currentStep === 3" x-transition>
                        <div class="tab-header">
                            <h2><i class="fas fa-plus-circle"></i> Add-ons & Special Requests</h2>
                            <p>Customize your experience with add-ons and special preferences</p>
                        </div>
                        <div class="form-grid">
                                <div class="form-group">
                                <label for="accommodation_type">Accommodation Type</label>
                                <select id="accommodation_type" name="accommodation_type" x-model="formData.accommodation_type" @change="calculateTotal()" class="form-input">
                                    <option value="budget">Budget</option>
                                    <option value="standard" selected>Standard</option>
                                    <option value="luxury">Luxury (+$500)</option>
                                    </select>
                                </div>
                            <div class="form-group">
                                <label for="meal_preferences">Meal Preferences</label>
                                <select id="meal_preferences" name="meal_preferences" x-model="formData.meal_preferences" class="form-input">
                                    <option value="standard">Standard</option>
                                    <option value="vegetarian">Vegetarian</option>
                                    <option value="vegan">Vegan</option>
                                    <option value="halal">Halal</option>
                                    <option value="none">None</option>
                                </select>
                            </div>
                        </div>
                        <div class="addons-section">
                            <h3 class="addons-title">Available Add-ons</h3>
                             <div class="addons-grid">
                                <label class="addon-item">
                                    <input type="checkbox" x-model="formData.addons.airport_pickup" @change="calculateTotal()" value="150">
                                    <div class="addon-content">
                                        <div class="addon-icon"><i class="fas fa-plane"></i></div>
                                        <div class="addon-info">
                                            <h4>Airport Pickup</h4>
                                            <p>Private airport transfer</p>
                                            <span class="addon-price">+$150</span>
                                        </div>
                                    </div>
                                 </label>
                                <label class="addon-item">
                                    <input type="checkbox" x-model="formData.addons.extra_day" @change="calculateTotal()" value="300">
                                    <div class="addon-content">
                                        <div class="addon-icon"><i class="fas fa-calendar-plus"></i></div>
                                        <div class="addon-info">
                                            <h4>Extra Day</h4>
                                            <p>Extend your tour by one day</p>
                                            <span class="addon-price">+$300</span>
                                        </div>
                                    </div>
                                 </label>
                                <label class="addon-item">
                                    <input type="checkbox" x-model="formData.addons.private_guide" @change="calculateTotal()" value="200">
                                    <div class="addon-content">
                                        <div class="addon-icon"><i class="fas fa-user-tie"></i></div>
                                        <div class="addon-info">
                                            <h4>Private Guide</h4>
                                            <p>Dedicated private guide</p>
                                            <span class="addon-price">+$200</span>
                             </div>
                        </div>
                                 </label>
                                <label class="addon-item">
                                    <input type="checkbox" x-model="formData.addons.photography" @change="calculateTotal()" value="250">
                                    <div class="addon-content">
                                        <div class="addon-icon"><i class="fas fa-camera"></i></div>
                                        <div class="addon-info">
                                            <h4>Photography Package</h4>
                                            <p>Professional photography service</p>
                                            <span class="addon-price">+$250</span>
                                        </div>
                                    </div>
                                 </label>
                                <label class="addon-item">
                                    <input type="checkbox" x-model="formData.addons.camping_gear" @change="calculateTotal()" value="100">
                                    <div class="addon-content">
                                        <div class="addon-icon"><i class="fas fa-campground"></i></div>
                                        <div class="addon-info">
                                            <h4>Camping Gear</h4>
                                            <p>Premium camping equipment</p>
                                            <span class="addon-price">+$100</span>
                             </div>
                                </div>
                                </label>
                                <label class="addon-item">
                                    <input type="checkbox" x-model="formData.addons.travel_insurance" @change="calculateTotal()" value="150">
                                    <div class="addon-content">
                                        <div class="addon-icon"><i class="fas fa-shield-alt"></i></div>
                                        <div class="addon-info">
                                            <h4>Travel Insurance</h4>
                                            <p>Comprehensive travel coverage</p>
                                            <span class="addon-price">+$150</span>
                                </div>
                                </div>
                                </label>
                                    </div>
                                    </div>
                        <div class="form-group full-width">
                            <label for="special_requests">Special Requests / Notes</label>
                            <textarea id="special_requests" name="special_requests" x-model="formData.special_requests" class="form-input" rows="5" placeholder="Any special requirements, dietary restrictions, or additional information..."></textarea>
                                </div>
                        <div class="tab-actions">
                            <button type="button" @click="prevStep()" class="btn-secondary">
                                <i class="fas fa-arrow-left"></i> Previous
                            </button>
                            <button type="button" @click="nextStep()" class="btn-primary">
                                Next: Documents <i class="fas fa-arrow-right"></i>
                            </button>
                            </div>
                        </div>

                    <!-- Tab 4: Traveler Documents -->
                    <div class="booking-tab" x-show="currentStep === 4" x-transition>
                        <div class="tab-header">
                            <h2><i class="fas fa-file-upload"></i> Traveler Documents</h2>
                            <p>Upload required documents (Optional but recommended)</p>
                            </div>
                        <div class="documents-grid">
                            <div class="document-upload">
                                <label for="passport_copy" class="upload-label">
                                    <div class="upload-icon"><i class="fas fa-passport"></i></div>
                                    <div class="upload-content">
                                        <h4>Passport Copy</h4>
                                        <p>Upload a clear copy of your passport</p>
                                        <span class="upload-hint">PDF, JPG, PNG (Max 5MB)</span>
                        </div>
                                    <input type="file" id="passport_copy" name="passport_copy" accept=".pdf,.jpg,.jpeg,.png" class="file-input">
                                </label>
                                <div x-show="formData.documents.passport" class="uploaded-file">
                                    <i class="fas fa-check-circle"></i> File selected
                                </div>
                            </div>
                            <div class="document-upload">
                                <label for="id_copy" class="upload-label">
                                    <div class="upload-icon"><i class="fas fa-id-card"></i></div>
                                    <div class="upload-content">
                                        <h4>ID Copy</h4>
                                        <p>Upload a copy of your ID card</p>
                                        <span class="upload-hint">PDF, JPG, PNG (Max 5MB)</span>
                                    </div>
                                    <input type="file" id="id_copy" name="id_copy" accept=".pdf,.jpg,.jpeg,.png" class="file-input">
                                </label>
                                <div x-show="formData.documents.id" class="uploaded-file">
                                    <i class="fas fa-check-circle"></i> File selected
                                </div>
                            </div>
                            <div class="document-upload">
                                <label for="payment_proof" class="upload-label">
                                    <div class="upload-icon"><i class="fas fa-receipt"></i></div>
                                    <div class="upload-content">
                                        <h4>Payment Proof</h4>
                                        <p>Upload payment receipt (for offline payments)</p>
                                        <span class="upload-hint">PDF, JPG, PNG (Max 5MB)</span>
                                    </div>
                                    <input type="file" id="payment_proof" name="payment_proof" accept=".pdf,.jpg,.jpeg,.png" class="file-input">
                                </label>
                                <div x-show="formData.documents.payment" class="uploaded-file">
                                    <i class="fas fa-check-circle"></i> File selected
                                </div>
                            </div>
                            <div class="document-upload">
                                <label for="travel_insurance" class="upload-label">
                                    <div class="upload-icon"><i class="fas fa-shield-alt"></i></div>
                                    <div class="upload-content">
                                        <h4>Travel Insurance</h4>
                                        <p>Upload your travel insurance document</p>
                                        <span class="upload-hint">PDF, JPG, PNG (Max 5MB)</span>
                                    </div>
                                    <input type="file" id="travel_insurance" name="travel_insurance" accept=".pdf,.jpg,.jpeg,.png" class="file-input">
                                </label>
                                <div x-show="formData.documents.insurance" class="uploaded-file">
                                    <i class="fas fa-check-circle"></i> File selected
                                </div>
                            </div>
                        </div>
                        <div class="tab-actions">
                            <button type="button" @click="prevStep()" class="btn-secondary">
                                <i class="fas fa-arrow-left"></i> Previous
                            </button>
                            <button type="button" @click="nextStep()" class="btn-primary">
                                Next: Payment <i class="fas fa-arrow-right"></i>
                            </button>
                        </div>
                </div>

                    <!-- Tab 5: Payment Details -->
                    <div class="booking-tab" x-show="currentStep === 5" x-transition>
                        <div class="tab-header">
                            <h2><i class="fas fa-credit-card"></i> Payment Details</h2>
                            <p>Review your booking summary and choose payment method</p>
                        </div>
                        <div class="payment-wrapper">
                <div class="booking-summary">
                    <h3 class="summary-title">Booking Summary</h3>
                    <div class="summary-item">
                                    <span>Tour:</span>
                                    <strong x-text="selectedTourName || 'Not selected'"></strong>
                    </div>
                    <div class="summary-item">
                                    <span>Start Date:</span>
                                    <strong x-text="formData.tour_start_date || 'Not selected'"></strong>
                    </div>
                    <div class="summary-item">
                                    <span>End Date:</span>
                                    <strong x-text="formData.tour_end_date || 'Not selected'"></strong>
                    </div>
                                <div class="summary-item">
                                    <span>Travelers:</span>
                                    <strong x-text="formData.number_of_adults + ' Adults' + (formData.number_of_children > 0 ? ', ' + formData.number_of_children + ' Children' : '')"></strong>
                        </div>
                                <div class="summary-item" x-show="formData.addons.airport_pickup">
                                    <span>Airport Pickup:</span>
                                    <strong>+$150</strong>
                                </div>
                                <div class="summary-item" x-show="formData.addons.extra_day">
                                    <span>Extra Day:</span>
                                    <strong>+$300</strong>
                                </div>
                                <div class="summary-item" x-show="formData.addons.private_guide">
                                    <span>Private Guide:</span>
                                    <strong>+$200</strong>
                                </div>
                                <div class="summary-item" x-show="formData.addons.photography">
                                    <span>Photography:</span>
                                    <strong>+$250</strong>
                                </div>
                                <div class="summary-item" x-show="formData.addons.camping_gear">
                                    <span>Camping Gear:</span>
                                    <strong>+$100</strong>
                                </div>
                                <div class="summary-item" x-show="formData.addons.travel_insurance">
                                    <span>Travel Insurance:</span>
                                    <strong>+$150</strong>
                                </div>
                                <div class="summary-item" x-show="formData.accommodation_type === 'luxury'">
                                    <span>Luxury Upgrade:</span>
                                    <strong>+$500</strong>
                                </div>
                    <div class="summary-divider"></div>
                    <div class="summary-total">
                                    <span>Total Price:</span>
                                    <strong>$<span x-text="totalPrice.toLocaleString()">0</span></strong>
                    </div>
                </div>
                            <div class="payment-details">
                                <div class="form-group">
                                    <label>Payment Method <span class="required">*</span></label>
                                    <div class="payment-methods">
                                        <label class="payment-method">
                                            <input type="radio" name="payment_method" x-model="formData.payment_method" value="mobile_money" required>
                                            <div class="method-content">
                                                <i class="fas fa-mobile-alt"></i>
                                                <span>Mobile Money</span>
                                                <small>M-Pesa, Tigo Pesa, Airtel Money</small>
            </div>
                                        </label>
                                        <label class="payment-method">
                                            <input type="radio" name="payment_method" x-model="formData.payment_method" value="bank_transfer" required>
                                            <div class="method-content">
                                                <i class="fas fa-university"></i>
                                                <span>Bank Transfer</span>
                                                <small>Direct bank transfer</small>
        </div>
                                        </label>
                                        <label class="payment-method">
                                            <input type="radio" name="payment_method" x-model="formData.payment_method" value="card" required>
                                            <div class="method-content">
                                                <i class="fas fa-credit-card"></i>
                                                <span>Credit/Debit Card</span>
                                                <small>Visa, Mastercard via Pesapal</small>
                                            </div>
                                        </label>
                                        <label class="payment-method">
                                            <input type="radio" name="payment_method" x-model="formData.payment_method" value="pesapal" required>
                                            <div class="method-content">
                                                <i class="fas fa-wallet"></i>
                                                <span>Pesapal</span>
                                                <small>Secure online payment</small>
                                            </div>
                                        </label>
                                        <label class="payment-method">
                                            <input type="radio" name="payment_method" x-model="formData.payment_method" value="paypal" required>
                                            <div class="method-content">
                                                <i class="fab fa-paypal"></i>
                                                <span>PayPal</span>
                                                <small>PayPal account</small>
                                            </div>
                                        </label>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="billing_name">Billing Name</label>
                                    <input type="text" id="billing_name" name="billing_name" x-model="formData.billing_name" class="form-input" :value="formData.full_name">
                                </div>
                                <div class="form-group">
                                    <label for="billing_email">Billing Email</label>
                                    <input type="email" id="billing_email" name="billing_email" x-model="formData.billing_email" class="form-input" :value="formData.email">
                                </div>
                                <div class="form-group">
                                    <label for="billing_address">Billing Address</label>
                                    <textarea id="billing_address" name="billing_address" x-model="formData.billing_address" class="form-input" rows="3"></textarea>
                                </div>
                                <div class="form-group">
                                    <label class="checkbox-label">
                                        <input type="checkbox" x-model="formData.agree_terms" required>
                                        <span>I agree to the <a href="#" target="_blank">Terms & Conditions</a> and <a href="#" target="_blank">Privacy Policy</a> <span class="required">*</span></span>
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="tab-actions">
                            <button type="button" @click="prevStep()" class="btn-secondary">
                                <i class="fas fa-arrow-left"></i> Previous
                            </button>
                            <button type="button" @click="nextStep()" class="btn-primary" :disabled="!formData.agree_terms">
                                Review & Confirm <i class="fas fa-check"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Tab 6: Confirmation -->
                    <div class="booking-tab" x-show="currentStep === 6" x-transition>
                        <div class="confirmation-content">
                            <div class="confirmation-icon">
                                <i class="fas fa-check-circle"></i>
                            </div>
                            <h2 class="confirmation-title">Booking Submitted Successfully!</h2>
                            <p class="confirmation-message">Thank you for booking with Lau Paradise Adventures. We've received your booking request and will confirm it shortly.</p>
                            
                            <div class="booking-reference">
                                <div class="reference-item">
                                    <span>Booking Reference:</span>
                                    <strong x-text="bookingReference">TZ-2024-001234</strong>
                                </div>
                                <div class="reference-item">
                                    <span>Status:</span>
                                    <strong class="status-pending">Pending Confirmation</strong>
                                </div>
                            </div>

                            <div class="confirmation-summary">
                                <h3>Booking Summary</h3>
                                <div class="summary-details">
                                    <div class="detail-row">
                                        <span>Tour:</span>
                                        <strong x-text="selectedTourName"></strong>
                                    </div>
                                    <div class="detail-row">
                                        <span>Travel Dates:</span>
                                        <strong x-text="formData.tour_start_date + ' to ' + formData.tour_end_date"></strong>
                                    </div>
                                    <div class="detail-row">
                                        <span>Travelers:</span>
                                        <strong x-text="formData.number_of_adults + ' Adults' + (formData.number_of_children > 0 ? ', ' + formData.number_of_children + ' Children' : '')"></strong>
                                    </div>
                                    <div class="detail-row">
                                        <span>Total Amount:</span>
                                        <strong>$<span x-text="totalPrice.toLocaleString()">0</span></strong>
                                    </div>
                                </div>
                            </div>

                            <div class="confirmation-actions">
                                <a href="#" class="btn-primary" @click.prevent="downloadInvoice()">
                                    <i class="fas fa-download"></i> Download Invoice
                                </a>
                                <a href="{{ route('home') }}" class="btn-secondary">
                                    <i class="fas fa-home"></i> Back to Home
                                </a>
                            </div>

                            <div class="confirmation-note">
                                <p><i class="fas fa-info-circle"></i> You will receive a confirmation email shortly. Our team will contact you within 24 hours to finalize your booking.</p>
                            </div>
                        </div>
                    </div>

                    <!-- Hidden inputs for form submission -->
                    <input type="hidden" name="tour_id" :value="formData.tour_id">
                    <input type="hidden" name="tour_start_date" :value="formData.tour_start_date">
                    <input type="hidden" name="tour_end_date" :value="formData.tour_end_date">
                    <input type="hidden" name="number_of_adults" :value="formData.number_of_adults">
                    <input type="hidden" name="number_of_children" :value="formData.number_of_children">
                    <input type="hidden" name="total_price" :value="totalPrice">
                </form>
            </div>
        </div>
    </div>
</section>

@endsection

@push('styles')
<style>
.page-hero-section {
    position: relative;
    padding: 150px 0 100px;
    background-size: cover;
    background-position: center;
    background-attachment: fixed;
    color: var(--white);
    text-align: center;
    min-height: 400px;
    display: flex;
    align-items: center;
}
.page-hero-overlay {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: linear-gradient(135deg, rgba(26, 77, 58, 0.85) 0%, rgba(0, 0, 0, 0.7) 100%);
}
.page-hero-content {
    position: relative;
    z-index: 2;
    max-width: 900px;
    margin: 0 auto;
}
.hero-badge {
    display: inline-block;
    padding: 8px 20px;
    background: rgba(255, 255, 255, 0.2);
    backdrop-filter: blur(10px);
    border-radius: 30px;
        font-size: 0.9rem;
    font-weight: 600;
    margin-bottom: 20px;
    border: 1px solid rgba(255, 255, 255, 0.3);
}
.page-hero-title {
    font-size: 3.5rem;
    font-weight: 800;
    margin-bottom: 20px;
    text-shadow: 2px 2px 10px rgba(0,0,0,0.5);
}
.page-hero-subtitle {
    font-size: 1.3rem;
    opacity: 0.95;
    line-height: 1.6;
}
.booking-form-section {
    padding: 80px 0;
    background: var(--gray-light);
}
.booking-wrapper {
    max-width: 1000px;
    margin: 0 auto;
}
.booking-progress {
    background: var(--white);
    padding: 30px;
    border-radius: 16px;
    margin-bottom: 30px;
    box-shadow: 0 5px 25px rgba(0,0,0,0.08);
}
.progress-steps {
    display: flex;
    align-items: center;
    justify-content: space-between;
    position: relative;
}
.progress-step {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 10px;
    position: relative;
    z-index: 2;
}
.step-number {
    width: 50px;
    height: 50px;
    border-radius: 50%;
    background: var(--gray-light);
    color: var(--gray);
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 700;
    font-size: 1.2rem;
    transition: all 0.3s;
    border: 3px solid var(--gray-light);
}
.progress-step.active .step-number {
    background: var(--accent-green);
    color: var(--white);
    border-color: var(--accent-green);
    transform: scale(1.1);
}
.progress-step.completed .step-number {
    background: var(--primary-green);
    color: var(--white);
    border-color: var(--primary-green);
}
.progress-step.completed .step-number::after {
    content: '\f00c';
    font-family: 'Font Awesome 5 Free';
    font-weight: 900;
}
.step-label {
    font-size: 0.85rem;
    color: var(--gray);
    font-weight: 600;
    text-align: center;
}
.progress-step.active .step-label {
    color: var(--accent-green);
    font-weight: 700;
}
.progress-line {
    flex: 1;
    height: 3px;
    background: var(--gray-light);
    margin: 0 10px;
    position: relative;
    top: -25px;
}
.progress-line.completed {
    background: var(--accent-green);
}
.booking-form-card {
    background: var(--white);
    border-radius: 20px;
    padding: 50px;
    box-shadow: 0 10px 40px rgba(0,0,0,0.1);
}
.booking-tab {
    min-height: 500px;
}
.tab-header {
    margin-bottom: 40px;
    padding-bottom: 20px;
    border-bottom: 2px solid var(--gray-light);
}
.tab-header h2 {
    font-size: 2rem;
    color: var(--primary-green);
    margin-bottom: 10px;
    font-weight: 700;
    display: flex;
    align-items: center;
    gap: 12px;
}
.tab-header h2 i {
    color: var(--accent-green);
}
.tab-header p {
    color: var(--gray);
    font-size: 1.05rem;
}
.form-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 25px;
    margin-bottom: 30px;
}
.form-group {
    display: flex;
    flex-direction: column;
}
.form-group.full-width {
    grid-column: 1 / -1;
}
.form-group label {
    font-weight: 600;
    margin-bottom: 10px;
    color: var(--text-color);
    font-size: 0.95rem;
}
.required {
    color: #e74c3c;
}
.form-input {
    padding: 14px 18px;
    border: 2px solid var(--gray-light);
    border-radius: 10px;
    font-size: 1rem;
    font-family: var(--font-primary);
    transition: all 0.3s;
    background: var(--white);
}
.form-input:focus {
    outline: none;
    border-color: var(--accent-green);
    box-shadow: 0 0 0 3px rgba(61, 165, 114, 0.1);
}
.form-input[readonly] {
    background: var(--gray-light);
    cursor: not-allowed;
}
.addons-section {
    margin: 40px 0;
}
.addons-title {
    font-size: 1.5rem;
    color: var(--primary-green);
    margin-bottom: 25px;
    font-weight: 700;
}
.addons-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
    gap: 20px;
}
.addon-item {
    display: block;
    cursor: pointer;
}
.addon-item input[type="checkbox"] {
    display: none;
}
.addon-content {
    background: var(--gray-light);
    padding: 25px;
    border-radius: 12px;
    border: 2px solid transparent;
    transition: all 0.3s;
    display: flex;
    align-items: center;
    gap: 20px;
}
.addon-item input[type="checkbox"]:checked + .addon-content {
    background: var(--light-green);
    border-color: var(--accent-green);
    transform: translateY(-3px);
    box-shadow: 0 5px 15px rgba(61, 165, 114, 0.2);
}
.addon-icon {
    font-size: 2.5rem;
    color: var(--accent-green);
}
.addon-info {
    flex: 1;
}
.addon-info h4 {
    font-size: 1.1rem;
    color: var(--primary-green);
    margin-bottom: 5px;
    font-weight: 700;
}
.addon-info p {
    font-size: 0.9rem;
    color: var(--gray);
    margin-bottom: 8px;
}
.addon-price {
    font-size: 1rem;
    color: var(--accent-green);
    font-weight: 700;
}
.documents-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
    gap: 25px;
    margin-bottom: 30px;
}
.document-upload {
    position: relative;
}
.upload-label {
    display: block;
    background: var(--gray-light);
    padding: 30px;
    border-radius: 12px;
    border: 2px dashed var(--gray-light);
    text-align: center;
    cursor: pointer;
    transition: all 0.3s;
}
.upload-label:hover {
    border-color: var(--accent-green);
    background: var(--light-green);
}
.file-input {
    position: absolute;
    opacity: 0;
    width: 0;
    height: 0;
}
.upload-icon {
    font-size: 3rem;
    color: var(--accent-green);
    margin-bottom: 15px;
}
.upload-content h4 {
    font-size: 1.2rem;
    color: var(--primary-green);
    margin-bottom: 8px;
    font-weight: 700;
}
.upload-content p {
    color: var(--gray);
    font-size: 0.95rem;
    margin-bottom: 5px;
}
.upload-hint {
    font-size: 0.85rem;
    color: var(--gray);
    font-style: italic;
}
.uploaded-file {
    margin-top: 10px;
    color: var(--accent-green);
    font-weight: 600;
        display: flex;
        align-items: center;
        gap: 8px;
    }
.payment-wrapper {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 40px;
    margin-bottom: 30px;
}
.booking-summary {
    background: var(--gray-light);
    padding: 30px;
    border-radius: 16px;
    height: fit-content;
    position: sticky;
    top: 100px;
}
.summary-title {
    font-size: 1.5rem;
    color: var(--primary-green);
    margin-bottom: 25px;
    font-weight: 700;
    padding-bottom: 15px;
    border-bottom: 2px solid var(--accent-green);
}
.summary-item {
    display: flex;
    justify-content: space-between;
    padding: 12px 0;
    border-bottom: 1px solid var(--gray-light);
}
.summary-item span {
    color: var(--gray);
}
.summary-item strong {
    color: var(--primary-green);
    font-weight: 600;
}
.summary-divider {
    height: 2px;
    background: var(--accent-green);
    margin: 20px 0;
}
.summary-total {
    display: flex;
    justify-content: space-between;
    padding: 15px 0;
    font-size: 1.3rem;
}
.summary-total span {
    color: var(--text-color);
    font-weight: 600;
}
.summary-total strong {
    color: var(--accent-green);
    font-size: 1.8rem;
    font-weight: 800;
}
.payment-methods {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 15px;
    margin-bottom: 25px;
}
.payment-method {
    display: block;
    cursor: pointer;
}
.payment-method input[type="radio"] {
    display: none;
}
.method-content {
    background: var(--gray-light);
    padding: 20px;
    border-radius: 12px;
    border: 2px solid transparent;
    text-align: center;
    transition: all 0.3s;
}
.payment-method input[type="radio"]:checked + .method-content {
        background: var(--light-green);
    border-color: var(--accent-green);
    transform: translateY(-3px);
    box-shadow: 0 5px 15px rgba(61, 165, 114, 0.2);
}
.method-content i {
    font-size: 2rem;
    color: var(--accent-green);
    margin-bottom: 10px;
    display: block;
}
.method-content span {
    display: block;
    font-weight: 700;
        color: var(--primary-green);
    margin-bottom: 5px;
}
.method-content small {
    display: block;
    color: var(--gray);
    font-size: 0.85rem;
}
.checkbox-label {
    display: flex;
    align-items: flex-start;
    gap: 10px;
    cursor: pointer;
    font-size: 0.95rem;
}
.checkbox-label input[type="checkbox"] {
    margin-top: 3px;
    cursor: pointer;
}
.checkbox-label a {
    color: var(--accent-green);
    text-decoration: underline;
}
.tab-actions {
    display: flex;
    justify-content: space-between;
    gap: 15px;
    margin-top: 40px;
    padding-top: 30px;
    border-top: 2px solid var(--gray-light);
}
.btn-primary,
.btn-secondary {
    padding: 14px 30px;
    border-radius: 10px;
    font-weight: 600;
    font-size: 1rem;
    display: inline-flex;
    align-items: center;
    gap: 10px;
    text-decoration: none;
    border: none;
    cursor: pointer;
    transition: all 0.3s;
}
.btn-primary {
    background: var(--accent-green);
    color: var(--white);
}
.btn-primary:hover:not(:disabled) {
    background: var(--secondary-green);
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(61, 165, 114, 0.3);
}
.btn-primary:disabled {
    opacity: 0.5;
    cursor: not-allowed;
}
.btn-secondary {
    background: transparent;
    color: var(--primary-green);
    border: 2px solid var(--primary-green);
}
.btn-secondary:hover {
    background: var(--primary-green);
    color: var(--white);
}
.confirmation-content {
    text-align: center;
    padding: 40px 0;
}
.confirmation-icon {
    font-size: 5rem;
    color: var(--accent-green);
    margin-bottom: 30px;
}
.confirmation-title {
    font-size: 2.5rem;
    color: var(--primary-green);
    margin-bottom: 15px;
    font-weight: 700;
}
.confirmation-message {
    font-size: 1.2rem;
    color: var(--gray);
    margin-bottom: 40px;
    line-height: 1.7;
}
.booking-reference {
    background: var(--gray-light);
    padding: 30px;
    border-radius: 16px;
    margin-bottom: 40px;
    max-width: 600px;
    margin-left: auto;
    margin-right: auto;
}
.reference-item {
    display: flex;
    justify-content: space-between;
    padding: 15px 0;
    border-bottom: 1px solid var(--gray-light);
}
.reference-item:last-child {
    border-bottom: none;
}
.reference-item span {
    color: var(--gray);
    font-weight: 600;
}
.reference-item strong {
    color: var(--primary-green);
    font-weight: 700;
}
.status-pending {
    color: #FFA500;
}
.confirmation-summary {
    background: var(--gray-light);
    padding: 30px;
    border-radius: 16px;
    margin-bottom: 40px;
    text-align: left;
    max-width: 600px;
    margin-left: auto;
    margin-right: auto;
}
.confirmation-summary h3 {
    font-size: 1.5rem;
    color: var(--primary-green);
    margin-bottom: 20px;
    font-weight: 700;
    text-align: center;
}
.summary-details {
    display: flex;
    flex-direction: column;
    gap: 15px;
}
.detail-row {
    display: flex;
    justify-content: space-between;
    padding: 12px 0;
    border-bottom: 1px solid var(--gray-light);
}
.detail-row:last-child {
    border-bottom: none;
    font-size: 1.2rem;
    padding-top: 15px;
    border-top: 2px solid var(--accent-green);
}
.detail-row span {
    color: var(--gray);
    font-weight: 600;
}
.detail-row strong {
    color: var(--primary-green);
    font-weight: 700;
}
.confirmation-actions {
    display: flex;
    justify-content: center;
    gap: 20px;
    margin-bottom: 30px;
    flex-wrap: wrap;
}
.confirmation-note {
    background: var(--light-green);
    padding: 20px;
    border-radius: 12px;
    max-width: 600px;
    margin: 0 auto;
}
.confirmation-note p {
    color: var(--primary-green);
    margin: 0;
    display: flex;
    align-items: center;
    gap: 10px;
    justify-content: center;
}
    .confirmation-note i {
        color: var(--accent-green);
    }
    
    /* Selected Tour Card */
    .selected-tour-card {
        background: linear-gradient(135deg, var(--light-green) 0%, var(--white) 100%);
        border: 2px solid var(--accent-green);
        border-radius: 20px;
        padding: 30px;
        margin-bottom: 30px;
        position: relative;
        overflow: hidden;
    }
    
    .selected-tour-badge {
        position: absolute;
        top: 20px;
        right: 20px;
        background: var(--accent-green);
        color: var(--white);
        padding: 8px 16px;
        border-radius: 20px;
        font-size: 0.85rem;
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    
    .selected-tour-content {
        display: flex;
        gap: 25px;
        align-items: flex-start;
    }
    
    .selected-tour-image {
        width: 200px;
        height: 150px;
        border-radius: 15px;
        overflow: hidden;
        flex-shrink: 0;
        box-shadow: 0 5px 20px rgba(0,0,0,0.15);
    }
    
    .selected-tour-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    
    .selected-tour-info {
        flex: 1;
    }
    
    .selected-tour-info h3 {
        font-size: 1.8rem;
        color: var(--primary-green);
        margin-bottom: 15px;
        font-weight: 700;
    }
    
    .selected-tour-meta {
        display: flex;
        gap: 20px;
        margin-bottom: 15px;
        flex-wrap: wrap;
    }
    
    .selected-tour-meta span {
        display: flex;
        align-items: center;
        gap: 8px;
        color: var(--gray);
        font-size: 0.95rem;
    }
    
    .selected-tour-meta i {
        color: var(--accent-green);
    }
    
    .selected-tour-description {
        color: var(--gray);
        line-height: 1.7;
        margin-bottom: 20px;
    }
    
    .selected-tour-price {
        display: flex;
        align-items: baseline;
        gap: 10px;
        margin-bottom: 15px;
    }
    
    .price-label {
        font-size: 0.9rem;
        color: var(--gray);
    }
    
    .price-amount {
        font-size: 2rem;
        font-weight: 800;
        color: var(--accent-green);
    }
    
    .price-note {
        font-size: 0.9rem;
        color: var(--gray);
    }
    
    .view-tour-link {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        color: var(--accent-green);
        text-decoration: none;
        font-weight: 600;
        transition: all 0.3s;
    }
    
    .view-tour-link:hover {
        gap: 12px;
        color: var(--primary-green);
    }
    
    /* Tour Cards Grid */
    .tour-cards-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
        gap: 20px;
        margin-top: 20px;
    }
    
    .tour-select-card {
        background: var(--gray-light);
        border: 2px solid transparent;
        border-radius: 15px;
        overflow: hidden;
        cursor: pointer;
        transition: all 0.3s;
    }
    
    .tour-select-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        border-color: var(--accent-green);
    }
    
    .tour-select-card.selected {
        background: var(--light-green);
        border-color: var(--accent-green);
        box-shadow: 0 5px 20px rgba(61, 165, 114, 0.3);
    }
    
    .tour-select-image {
        position: relative;
        height: 120px;
        overflow: hidden;
    }
    
    .tour-select-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.3s;
    }
    
    .tour-select-card:hover .tour-select-image img {
        transform: scale(1.1);
    }
    
    .tour-select-badge {
        position: absolute;
        top: 10px;
        right: 10px;
        background: var(--accent-green);
        color: var(--white);
        padding: 5px 10px;
        border-radius: 15px;
        font-size: 0.75rem;
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 5px;
    }
    
    .tour-select-content {
        padding: 15px;
    }
    
    .tour-select-content h4 {
        font-size: 1rem;
        color: var(--primary-green);
        margin-bottom: 10px;
        font-weight: 700;
        line-height: 1.3;
    }
    
    .tour-select-meta {
        display: flex;
        flex-direction: column;
        gap: 5px;
        font-size: 0.85rem;
        color: var(--gray);
    }
    
    .tour-select-meta span {
        display: flex;
        align-items: center;
        gap: 5px;
    }
    
    .tour-select-meta i {
        color: var(--accent-green);
        font-size: 0.8rem;
    }
    
    @media (max-width: 992px) {
    .payment-wrapper {
        grid-template-columns: 1fr;
    }
    .booking-summary {
        position: static;
    }
    .progress-steps {
        flex-wrap: wrap;
        gap: 10px;
    }
    .progress-line {
        display: none;
    }
    }
    @media (max-width: 768px) {
    .page-hero-title {
        font-size: 2.5rem;
    }
    .booking-form-card {
        padding: 30px 20px;
    }
    .form-grid {
        grid-template-columns: 1fr;
    }
    .addons-grid {
        grid-template-columns: 1fr;
    }
    .documents-grid {
        grid-template-columns: 1fr;
    }
    .payment-methods {
        grid-template-columns: 1fr;
    }
    .tab-actions {
        flex-direction: column;
    }
    .step-label {
        display: none;
    }
    
    .selected-tour-content {
        flex-direction: column;
    }
    
    .selected-tour-image {
        width: 100%;
        height: 200px;
    }
    
    .tour-cards-grid {
        grid-template-columns: repeat(2, 1fr);
    }
    }
    
    @media (max-width: 768px) {
    .tour-cards-grid {
        grid-template-columns: 1fr;
    }
    }
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
<script>
function bookingWizard() {
        return {
        currentStep: 1,
        minDate: new Date().toISOString().split('T')[0],
        bookingReference: 'TZ-' + new Date().getFullYear() + '-' + Math.floor(Math.random() * 1000000),
        selectedTourName: '',
        tourDuration: 0,
        tourBasePrice: 0,
        totalPrice: 0,
            formData: { 
            full_name: '',
                email: '', 
                phone: '',
            nationality: '',
            date_of_birth: '',
            gender: '',
            passport_number: '',
            address: '',
            tour_id: '',
            tour_start_date: '',
            tour_end_date: '',
            number_of_adults: 2,
            number_of_children: 0,
            pickup_location: '',
            preferred_language: 'english',
            accommodation_type: 'standard',
            meal_preferences: 'standard',
            addons: {
                airport_pickup: false,
                extra_day: false,
                private_guide: false,
                photography: false,
                camping_gear: false,
                travel_insurance: false
            },
            special_requests: '',
            documents: {
                passport: false,
                id: false,
                payment: false,
                insurance: false
            },
            payment_method: '',
            billing_name: '',
            billing_email: '',
            billing_address: '',
            agree_terms: false
        },
            init() { 
            // Pre-fill billing info from traveler info
            this.$watch('formData.full_name', (value) => {
                if (!this.formData.billing_name) {
                    this.formData.billing_name = value;
                }
            });
            this.$watch('formData.email', (value) => {
                if (!this.formData.billing_email) {
                    this.formData.billing_email = value;
                }
            });
            
            // Pre-select tour from URL parameter or selectedTourId
            @if(isset($selectedTourId) && $selectedTourId)
            this.formData.tour_id = '{{ $selectedTourId }}';
            this.$nextTick(() => {
                this.updateTourDetails();
            });
            @else
            // Check for tour in URL
            const urlParams = new URLSearchParams(window.location.search);
            const tourParam = urlParams.get('tour');
            if (tourParam) {
                // Try to find and select the tour
                const tourSelect = document.getElementById('tour_id');
                if (tourSelect) {
                    for (let option of tourSelect.options) {
                        if (option.value && (option.text.toLowerCase().includes(tourParam.toLowerCase()) || option.value === tourParam)) {
                            tourSelect.value = option.value;
                            this.formData.tour_id = option.value;
                            this.updateTourDetails();
                            break;
                        }
                    }
                }
            }
            @endif
        },
        nextStep() {
            if (this.validateStep()) {
                if (this.currentStep < 6) {
                    this.currentStep++;
                    window.scrollTo({ top: 0, behavior: 'smooth' });
                }
            }
        },
        prevStep() {
            if (this.currentStep > 1) {
                this.currentStep--;
                window.scrollTo({ top: 0, behavior: 'smooth' });
            }
        },
        validateStep() {
            const step = this.currentStep;
            if (step === 1) {
                if (!this.formData.full_name || !this.formData.email || !this.formData.phone) {
                    alert('Please fill in all required fields (Full Name, Email, Phone)');
                    return false;
                }
            } else if (step === 2) {
                if (!this.formData.tour_id || !this.formData.tour_start_date || !this.formData.number_of_adults) {
                    alert('Please select a tour, start date, and number of adults');
                    return false;
                }
            } else if (step === 5) {
                if (!this.formData.payment_method || !this.formData.agree_terms) {
                    alert('Please select a payment method and agree to terms & conditions');
                    return false;
                }
            }
            return true;
        },
        updateTourDetails() {
            const tourSelect = document.getElementById('tour_id');
            if (tourSelect && tourSelect.selectedIndex > 0) {
                const selectedOption = tourSelect.options[tourSelect.selectedIndex];
                this.selectedTourName = selectedOption.text.split(' - ')[0];
                this.tourBasePrice = parseFloat(selectedOption.dataset.price || 0);
                this.tourDuration = parseInt(selectedOption.dataset.duration || 0);
                this.calculateTotal();
                if (this.formData.tour_start_date) {
                    this.calculateEndDate();
                }
            }
        },
        calculateEndDate() {
            if (this.formData.tour_start_date && this.tourDuration > 0) {
                const startDate = new Date(this.formData.tour_start_date);
                startDate.setDate(startDate.getDate() + this.tourDuration);
                this.formData.tour_end_date = startDate.toISOString().split('T')[0];
            }
        },
        selectTourCard(tourId, price, duration, name) {
            this.formData.tour_id = tourId;
            this.tourBasePrice = price;
            this.tourDuration = duration;
            this.selectedTourName = name;
            
            // Update select dropdown
            const tourSelect = document.getElementById('tour_id');
            if (tourSelect) {
                tourSelect.value = tourId;
            }
            
            this.calculateTotal();
            if (this.formData.tour_start_date) {
                this.calculateEndDate();
            }
        },
        calculateTotal() {
            let total = 0;
            
            // Base price per adult
            if (this.tourBasePrice > 0 && this.formData.number_of_adults > 0) {
                total = this.tourBasePrice * this.formData.number_of_adults;
            }
            
            // Children (50% discount)
            if (this.formData.number_of_children > 0 && this.tourBasePrice > 0) {
                total += (this.tourBasePrice * 0.5) * this.formData.number_of_children;
            }
            
            // Add-ons
            if (this.formData.addons.airport_pickup) total += 150;
            if (this.formData.addons.extra_day) total += 300;
            if (this.formData.addons.private_guide) total += 200;
            if (this.formData.addons.photography) total += 250;
            if (this.formData.addons.camping_gear) total += 100;
            if (this.formData.addons.travel_insurance) total += 150;
            
            // Accommodation upgrade
            if (this.formData.accommodation_type === 'luxury') {
                total += 500 * this.formData.number_of_adults;
            }
            
            this.totalPrice = total;
        },
        submitBooking() {
            if (this.validateStep() && this.currentStep === 6) {
                // Submit the form
                document.querySelector('form').submit();
            }
        },
        downloadInvoice() {
            // Generate invoice download URL
            const baseUrl = '{{ url("/booking/invoice") }}';
            const invoiceUrl = baseUrl + '/' + this.bookingReference + '/download';
            window.open(invoiceUrl, '_blank');
        }
    }
    }
</script>
@endpush
