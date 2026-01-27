@extends('layouts.app')

@section('title', 'Request Travel Proposal - Customized Private Travel | Lau Paradise Adventures')
@section('description', 'Make your dream trip come true with Lau Paradise Adventures. Customized private travel, best price guarantee, highest service, response within 24 hours.')

@section('content')

<!-- Hero Section -->
<section class="travel-proposal-hero position-relative py-5" style="background: linear-gradient(135deg, rgba(0,0,0,0.75) 0%, rgba(0,0,0,0.6) 100%), url('{{ asset('images/hero-slider/safari-adventure.jpg') }}') center/cover;">
    <div class="container position-relative" style="z-index: 2;">
        <div class="row py-5">
            <div class="col-lg-10 mx-auto text-center text-white">
                <h1 class="display-3 fw-bold mb-4" data-aos="fade-up">
                    Request Travel Proposal
                </h1>
                <p class="lead fs-4 mb-4" data-aos="fade-up" data-aos-delay="100">
                    Make your dream trip come true with Lau Paradise Adventures.
                </p>
                <div class="row g-4 mt-4">
                    <div class="col-md-3 col-6" data-aos="fade-up" data-aos-delay="200">
                        <div class="text-white">
                            <i class="fas fa-user-cog fa-2x mb-3"></i>
                            <h6 class="fw-bold">Customized private travel</h6>
                        </div>
                    </div>
                    <div class="col-md-3 col-6" data-aos="fade-up" data-aos-delay="250">
                        <div class="text-white">
                            <i class="fas fa-question-circle fa-2x mb-3"></i>
                            <h6 class="fw-bold">Inquire without obligations</h6>
                        </div>
                    </div>
                    <div class="col-md-3 col-6" data-aos="fade-up" data-aos-delay="300">
                        <div class="text-white">
                            <i class="fas fa-dollar-sign fa-2x mb-3"></i>
                            <h6 class="fw-bold">Best price guarantee</h6>
                        </div>
                    </div>
                    <div class="col-md-3 col-6" data-aos="fade-up" data-aos-delay="350">
                        <div class="text-white">
                            <i class="fas fa-check-circle fa-2x mb-3"></i>
                            <h6 class="fw-bold">Highest service</h6>
                        </div>
                    </div>
                </div>
                <p class="mt-4" data-aos="fade-up" data-aos-delay="400">
                    <i class="fas fa-clock me-2"></i>Response within 24 hours
                </p>
            </div>
        </div>
    </div>
</section>

<!-- Ethical Climbing Certification Section -->
<section class="py-4 bg-light border-bottom">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-12 text-center">
                <div class="d-flex align-items-center justify-content-center gap-3 flex-wrap">
                    <i class="fas fa-certificate text-primary" style="font-size: 2rem;"></i>
                    <div class="text-start">
                        <h5 class="fw-bold mb-1">Certified for Ethical Climbing</h5>
                        <p class="text-muted mb-0 small">We are committed to responsible and ethical climbing practices</p>
                    </div>
                    <a href="{{ asset('documents/certified-ethical-climbing.pdf') }}" 
                       target="_blank" 
                       class="btn btn-outline-primary btn-sm">
                        <i class="fas fa-file-pdf me-2"></i>View Certificate PDF
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Travel Proposal Form Section -->
<section class="py-5 bg-white">
    <div class="container">
        <div class="row">
            <div class="col-lg-10 mx-auto">
                <div class="card border-0 shadow-lg">
                    <div class="card-body p-4 p-md-5">
                        <form action="{{ route('contact.submit') }}" method="POST" id="travelProposalForm">
                            @csrf
                            <input type="hidden" name="subject" value="Travel Proposal Request">
                            
                            <!-- Currency Selection -->
                            <div class="mb-4 pb-3 border-bottom">
                                <label class="form-label fw-bold mb-3">
                                    <i class="fas fa-dollar-sign me-2 text-primary"></i>Currency
                                </label>
                                <select name="currency" class="form-select form-select-lg" required>
                                    <option value="USD" selected>U.S. Dollar ($)</option>
                                    <option value="EUR">Euro (€)</option>
                                    <option value="GBP">British Pound (£)</option>
                                    <option value="TZS">Tanzanian Shilling (TSh)</option>
                                </select>
                                <small class="text-muted">* indicates a required field.</small>
                            </div>

                            <!-- Travel Information -->
                            <div class="mb-5">
                                <h3 class="h4 fw-bold mb-4">
                                    <i class="fas fa-map-marked-alt me-2 text-primary"></i>Travel Information
                                </h3>

                                <!-- What do you want to do? -->
                                <div class="mb-4">
                                    <label class="form-label fw-semibold">
                                        What do you want to do? <span class="text-danger">*</span>
                                    </label>
                                    <p class="text-muted small mb-3">Pick one, two,... or even all of the following options.</p>
                                    <div class="row g-3">
                                        <div class="col-md-3 col-sm-6">
                                            <div class="form-check custom-option">
                                                <input class="form-check-input" type="checkbox" name="activities[]" id="activity_safari" value="Safari">
                                                <label class="form-check-label custom-option-content w-100" for="activity_safari">
                                                    <div class="card h-100 text-center p-3">
                                                        <i class="fas fa-camera fa-2x text-primary mb-2"></i>
                                                        <span>Safari</span>
                                                    </div>
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-md-3 col-sm-6">
                                            <div class="form-check custom-option">
                                                <input class="form-check-input" type="checkbox" name="activities[]" id="activity_beach" value="Beach holiday">
                                                <label class="form-check-label custom-option-content w-100" for="activity_beach">
                                                    <div class="card h-100 text-center p-3">
                                                        <i class="fas fa-umbrella-beach fa-2x text-primary mb-2"></i>
                                                        <span>Beach holiday</span>
                                                    </div>
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-md-3 col-sm-6">
                                            <div class="form-check custom-option">
                                                <input class="form-check-input" type="checkbox" name="activities[]" id="activity_climbing" value="Climbing Kilimanjaro">
                                                <label class="form-check-label custom-option-content w-100" for="activity_climbing">
                                                    <div class="card h-100 text-center p-3">
                                                        <i class="fas fa-mountain fa-2x text-primary mb-2"></i>
                                                        <span>Climbing Kilimanjaro</span>
                                                    </div>
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-md-3 col-sm-6">
                                            <div class="form-check custom-option">
                                                <input class="form-check-input" type="checkbox" name="activities[]" id="activity_migration" value="See the great migration">
                                                <label class="form-check-label custom-option-content w-100" for="activity_migration">
                                                    <div class="card h-100 text-center p-3">
                                                        <i class="fas fa-route fa-2x text-primary mb-2"></i>
                                                        <span>See the great migration</span>
                                                    </div>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- How many days? -->
                                <div class="mb-4">
                                    <label class="form-label fw-semibold">
                                        How many days do you want to travel? <span class="text-danger">*</span>
                                    </label>
                                    <select name="duration" class="form-select form-select-lg" required>
                                        <option value="">Choose number of days</option>
                                        <option value="4">4</option>
                                        <option value="5">5</option>
                                        <option value="6">6</option>
                                        <option value="7">7</option>
                                        <option value="8">8</option>
                                        <option value="9">9</option>
                                        <option value="10">10</option>
                                        <option value="11">11</option>
                                        <option value="12" selected>12 days</option>
                                        <option value="13">13</option>
                                        <option value="14">14</option>
                                        <option value="15">15</option>
                                        <option value="16">16</option>
                                        <option value="17">17</option>
                                        <option value="18">18</option>
                                        <option value="19">19</option>
                                        <option value="20">20</option>
                                        <option value="21+">21+</option>
                                    </select>
                                </div>

                                <!-- Who are you travelling with? -->
                                <div class="mb-4">
                                    <label class="form-label fw-semibold">
                                        Who are you travelling with? <span class="text-danger">*</span>
                                    </label>
                                    <div class="row g-3">
                                        <div class="col-md-3 col-sm-6">
                                            <div class="form-check custom-option">
                                                <input class="form-check-input" type="radio" name="travel_group" id="group_honeymoon" value="Honeymoon" required>
                                                <label class="form-check-label custom-option-content w-100" for="group_honeymoon">
                                                    <div class="card h-100 text-center p-3">
                                                        <i class="fas fa-heart fa-2x text-primary mb-2"></i>
                                                        <span>Honeymoon</span>
                                                    </div>
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-md-3 col-sm-6">
                                            <div class="form-check custom-option">
                                                <input class="form-check-input" type="radio" name="travel_group" id="group_family" value="Family" required>
                                                <label class="form-check-label custom-option-content w-100" for="group_family">
                                                    <div class="card h-100 text-center p-3">
                                                        <i class="fas fa-users fa-2x text-primary mb-2"></i>
                                                        <span>Family</span>
                                                    </div>
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-md-3 col-sm-6">
                                            <div class="form-check custom-option">
                                                <input class="form-check-input" type="radio" name="travel_group" id="group_solo" value="Solo (no group tours)" required>
                                                <label class="form-check-label custom-option-content w-100" for="group_solo">
                                                    <div class="card h-100 text-center p-3">
                                                        <i class="fas fa-user fa-2x text-primary mb-2"></i>
                                                        <span>Solo<br><small>(no group tours)</small></span>
                                                    </div>
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-md-3 col-sm-6">
                                            <div class="form-check custom-option">
                                                <input class="form-check-input" type="radio" name="travel_group" id="group_couple" value="Couple" required>
                                                <label class="form-check-label custom-option-content w-100" for="group_couple">
                                                    <div class="card h-100 text-center p-3">
                                                        <i class="fas fa-user-friends fa-2x text-primary mb-2"></i>
                                                        <span>Couple</span>
                                                    </div>
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-md-3 col-sm-6">
                                            <div class="form-check custom-option">
                                                <input class="form-check-input" type="radio" name="travel_group" id="group_friends" value="Group of friends" required>
                                                <label class="form-check-label custom-option-content w-100" for="group_friends">
                                                    <div class="card h-100 text-center p-3">
                                                        <i class="fas fa-user-friends fa-2x text-primary mb-2"></i>
                                                        <span>Group of friends</span>
                                                    </div>
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-md-3 col-sm-6">
                                            <div class="form-check custom-option">
                                                <input class="form-check-input" type="radio" name="travel_group" id="group_other" value="Other" required>
                                                <label class="form-check-label custom-option-content w-100" for="group_other">
                                                    <div class="card h-100 text-center p-3">
                                                        <i class="fas fa-ellipsis-h fa-2x text-primary mb-2"></i>
                                                        <span>Other</span>
                                                    </div>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- When do you want to travel? -->
                                <div class="mb-4">
                                    <label class="form-label fw-semibold">
                                        When do you want to travel? <span class="text-danger">*</span>
                                    </label>
                                    <p class="text-muted small mb-2">Select a date. You can always change it later on, if you are not sure.</p>
                                    <input type="date" name="travel_date" class="form-control form-control-lg" value="2025-12-26" min="{{ date('Y-m-d') }}" required>
                                    <small class="text-muted">MM slash DD slash YYYY</small>
                                </div>

                                <!-- Budget -->
                                <div class="mb-4">
                                    <label class="form-label fw-semibold">
                                        Do you have a budget per person in mind? <span class="text-danger">*</span>
                                    </label>
                                    <p class="text-muted small mb-2">Budget EXCLUDING INTERNATIONAL FLIGHTS. Of course, we can book the flights for you as well, if you want!</p>
                                    <div class="input-group input-group-lg">
                                        <span class="input-group-text">$</span>
                                        <input type="number" name="budget" class="form-control" placeholder="Budget per person" min="0" step="100" value="6000" required>
                                    </div>
                                    <div class="mt-2">
                                        <input type="range" class="form-range" min="3500" max="8500" step="100" value="6000" id="budgetRange" oninput="document.querySelector('input[name=budget]').value=this.value">
                                        <div class="d-flex justify-content-between">
                                            <small class="text-muted">$3500</small>
                                            <small class="text-muted">$8500+</small>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Travellers & Age -->
                            <div class="mb-5">
                                <h3 class="h4 fw-bold mb-4">
                                    <i class="fas fa-users me-2 text-primary"></i>Travellers & age
                                </h3>

                                <div class="mb-4">
                                    <label class="form-label fw-semibold">
                                        Choose the number of adults <span class="text-danger">*</span>
                                    </label>
                                    <input type="number" name="num_adults" class="form-control form-control-lg" min="1" max="20" value="2" required>
                                </div>

                                <div id="adultAgesContainer" class="mb-4">
                                    <div class="mb-3">
                                        <label class="form-label fw-semibold">
                                            Adult's age <span class="text-danger">*</span>
                                        </label>
                                        <input type="number" name="adult_ages[]" class="form-control" min="1" max="120" value="19" required>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label fw-semibold">
                                            Adult's age <span class="text-danger">*</span>
                                        </label>
                                        <input type="number" name="adult_ages[]" class="form-control" min="1" max="120" value="26" required>
                                    </div>
                                </div>

                                <div class="mb-4">
                                    <label class="form-label fw-semibold">
                                        Choose the number of children
                                    </label>
                                    <input type="number" name="num_children" class="form-control form-control-lg" min="0" max="20" value="0" id="numChildren" onchange="updateChildrenAges()">
                                </div>

                                <div id="childrenAgesContainer" class="mb-4"></div>
                            </div>

                            <!-- Additional Information -->
                            <div class="mb-5">
                                <label class="form-label fw-semibold">
                                    Anything else you'd like to share with us?
                                </label>
                                <p class="text-muted small mb-3">
                                    For example: If you want to combine safari and beach, would you prefer to do a long safari and a short beach holiday, or the contrary? Do you want a specific room type? Which national parks or animals would really want to see? Please share with us anything we should know to make this trip unforgettable!
                                </p>
                                <textarea name="additional_info" class="form-control" rows="6" placeholder="Tell us more about your dream trip..."></textarea>
                            </div>

                            <!-- Contact Details -->
                            <div class="mb-5">
                                <h3 class="h4 fw-bold mb-4">
                                    <i class="fas fa-address-card me-2 text-primary"></i>Your contact details
                                </h3>

                                <div class="row g-4">
                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold">First name</label>
                                        <input type="text" name="first_name" class="form-control form-control-lg" placeholder="Your first name">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold">Last name</label>
                                        <input type="text" name="last_name" class="form-control form-control-lg" placeholder="Your last name">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold">
                                            E-mail <span class="text-danger">*</span>
                                        </label>
                                        <input type="email" name="email" class="form-control form-control-lg" placeholder="Your e-mail address" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold">
                                            Phone number <span class="text-danger">*</span>
                                        </label>
                                        <div class="input-group input-group-lg">
                                            <select name="phone_country_code" class="form-select" style="max-width: 150px;">
                                                <option value="+1" selected>United States +1</option>
                                                <option value="+44">United Kingdom +44</option>
                                                <option value="+255">Tanzania +255</option>
                                                <option value="+254">Kenya +254</option>
                                                <option value="+27">South Africa +27</option>
                                            </select>
                                            <input type="tel" name="phone" class="form-control" placeholder="(201) 555-0123" required>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <label class="form-label fw-semibold">
                                            Country <span class="text-danger">*</span>
                                        </label>
                                        <select name="country" class="form-select form-select-lg" required>
                                            <option value="United States" selected>United States</option>
                                            <option value="United Kingdom">United Kingdom</option>
                                            <option value="Canada">Canada</option>
                                            <option value="Australia">Australia</option>
                                            <option value="Germany">Germany</option>
                                            <option value="France">France</option>
                                            <option value="Netherlands">Netherlands</option>
                                            <option value="Tanzania">Tanzania</option>
                                            <option value="Other">Other</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <!-- Permission & Discount Code -->
                            <div class="mb-4">
                                <div class="form-check mb-3">
                                    <input class="form-check-input" type="checkbox" name="permission" id="permission" value="1" required>
                                    <label class="form-check-label" for="permission">
                                        I hereby give permission to receive a travel proposal for a safari and/or beach vacation, as well as any other relevant news regarding my holiday. <span class="text-danger">*</span>
                                    </label>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label fw-semibold">Have you received a discount code?</label>
                                    <input type="text" name="discount_code" class="form-control form-control-lg" placeholder="Enter discount code (optional)">
                                </div>
                            </div>

                            <!-- Submit Button -->
                            <div class="text-center">
                                <button type="submit" class="btn btn-primary btn-lg px-5 py-3">
                                    <i class="fas fa-paper-plane me-2"></i>Request travel proposal
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

@push('styles')
<style>
.travel-proposal-hero {
    min-height: 60vh;
    display: flex;
    align-items: center;
}

.custom-option input[type="checkbox"]:checked + .custom-option-content .card,
.custom-option input[type="radio"]:checked + .custom-option-content .card {
    border-color: var(--bs-primary) !important;
    background-color: rgba(102, 126, 234, 0.1) !important;
    box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
}

.custom-option-content .card {
    transition: all 0.3s ease;
    cursor: pointer;
}

.custom-option-content .card:hover {
    border-color: var(--bs-primary) !important;
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(0,0,0,0.1);
}

.form-control:focus,
.form-select:focus {
    border-color: var(--bs-primary);
    box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
}

@media (max-width: 768px) {
    .travel-proposal-hero {
        min-height: 50vh;
    }
    
    .display-3 {
        font-size: 2rem;
    }
}
</style>
@endpush

@push('scripts')
<script>
function updateChildrenAges() {
    const numChildren = parseInt(document.getElementById('numChildren').value) || 0;
    const container = document.getElementById('childrenAgesContainer');
    container.innerHTML = '';
    
    for (let i = 0; i < numChildren; i++) {
        const div = document.createElement('div');
        div.className = 'mb-3';
        div.innerHTML = `
            <label class="form-label fw-semibold">
                Child's age <span class="text-danger">*</span>
            </label>
            <input type="number" name="children_ages[]" class="form-control" min="0" max="17" required>
        `;
        container.appendChild(div);
    }
}

// Update adult ages when number of adults changes
document.querySelector('input[name="num_adults"]')?.addEventListener('change', function() {
    const numAdults = parseInt(this.value) || 0;
    const container = document.getElementById('adultAgesContainer');
    container.innerHTML = '';
    
    for (let i = 0; i < numAdults; i++) {
        const div = document.createElement('div');
        div.className = 'mb-3';
        div.innerHTML = `
            <label class="form-label fw-semibold">
                Adult's age <span class="text-danger">*</span>
            </label>
            <input type="number" name="adult_ages[]" class="form-control" min="1" max="120" required>
        `;
        container.appendChild(div);
    }
});

// Compile form data into message before submission
document.getElementById('travelProposalForm')?.addEventListener('submit', function(e) {
    const formData = new FormData(this);
    
    let message = "TRAVEL PROPOSAL REQUEST\n";
    message += "========================\n\n";
    
    message += "TRAVEL INFORMATION:\n";
    message += `Activities: ${formData.getAll('activities[]').join(', ') || 'Not specified'}\n`;
    message += `Duration: ${formData.get('duration') || 'Not specified'} days\n`;
    message += `Travel Group: ${formData.get('travel_group') || 'Not specified'}\n`;
    message += `Travel Date: ${formData.get('travel_date') || 'Not specified'}\n`;
    message += `Budget: $${formData.get('budget') || 'Not specified'} per person\n\n`;
    
    message += "TRAVELLERS:\n";
    message += `Number of Adults: ${formData.get('num_adults') || '0'}\n`;
    const adultAges = formData.getAll('adult_ages[]');
    adultAges.forEach((age, index) => {
        message += `Adult ${index + 1} age: ${age}\n`;
    });
    message += `Number of Children: ${formData.get('num_children') || '0'}\n`;
    const childrenAges = formData.getAll('children_ages[]');
    childrenAges.forEach((age, index) => {
        message += `Child ${index + 1} age: ${age}\n`;
    });
    message += "\n";
    
    message += "CONTACT DETAILS:\n";
    message += `Name: ${formData.get('first_name') || ''} ${formData.get('last_name') || ''}\n`;
    message += `Email: ${formData.get('email') || 'Not specified'}\n`;
    message += `Phone: ${formData.get('phone_country_code') || ''} ${formData.get('phone') || 'Not specified'}\n`;
    message += `Country: ${formData.get('country') || 'Not specified'}\n\n`;
    
    if (formData.get('additional_info')) {
        message += `ADDITIONAL INFORMATION:\n${formData.get('additional_info')}\n\n`;
    }
    
    if (formData.get('discount_code')) {
        message += `DISCOUNT CODE: ${formData.get('discount_code')}\n\n`;
    }
    
    message += "---\nPlease prepare a customized travel proposal based on the above information.";
    
    // Create hidden field with compiled message
    const hiddenField = document.createElement('input');
    hiddenField.type = 'hidden';
    hiddenField.name = 'message';
    hiddenField.value = message;
    this.appendChild(hiddenField);
});
</script>
@endpush

@endsection

