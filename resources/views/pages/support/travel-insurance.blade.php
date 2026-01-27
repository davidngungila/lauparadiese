@extends('layouts.app')

@section('title', 'Travel Insurance - Adventure Tours')
@section('description', 'Protect your adventure with comprehensive travel insurance. Learn about the importance of coverage for medical emergencies, trip cancellations, and more.')

@section('body_class', 'support-page')

@section('content')

    <!-- ============================================ -->
    <!-- 1. Page Header -->
    <!-- ============================================ -->
    <section class="page-header" style="background-image: url('https://images.unsplash.com/photo-1569949381669-ecf31ae8e613?auto=format&fit=crop&w=1920&q=80&blend=0f3429&sat=-100&bri=-20&bm=multiply');">
        <div class="container">
            <h1 class="page-title" data-aos="fade-up">Travel With Confidence</h1>
            <p class="page-subtitle" data-aos="fade-up" data-aos-delay="100">Comprehensive travel insurance for peace of mind on every adventure.</p>
        </div>
    </section>

    <!-- ============================================ -->
    <!-- 2. Introduction Section -->
    <!-- ============================================ -->
    <section class="content-section">
        <div class="container text-center max-w-4xl mx-auto">
            <h2 class="section-title" data-aos="fade-up">Your Adventure, Protected.</h2>
            <p class="section-subtitle" data-aos="fade-up" data-aos-delay="100">
                While we meticulously plan every detail for your safety and enjoyment, the unexpected can happen. From a missed flight to a medical emergency in a remote location, travel insurance is an essential component of any well-planned journey. It's the safety net that allows you to explore boldly, knowing you're covered no matter what the trail brings.
            </p>
        </div>
    </section>

    <!-- ============================================ -->
    <!-- 3. Why You Need It Section -->
    <!-- ============================================ -->
    <section class="content-section why-needed-section">
        <div class="container">
            <div class="features-grid">
                <div class="feature-item" data-aos="fade-up" data-aos-delay="100">
                    <div class="feature-icon"><i class="fas fa-notes-medical"></i></div>
                    <h3 class="feature-title">Emergency Medical Coverage</h3>
                    <p>Cover for hospital bills, doctor visits, and emergency medical evacuation, which can be critically important in remote destinations.</p>
                </div>
                <div class="feature-item" data-aos="fade-up" data-aos-delay="200">
                    <div class="feature-icon"><i class="fas fa-plane-slash"></i></div>
                    <h3 class="feature-title">Trip Cancellation & Interruption</h3>
                    <p>Reimbursement for prepaid, non-refundable trip costs if you have to cancel or cut your adventure short for a covered reason.</p>
                </div>
                <div class="feature-item" data-aos="fade-up" data-aos-delay="300">
                    <div class="feature-icon"><i class="fas fa-suitcase-rolling"></i></div>
                    <h3 class="feature-title">Baggage & Personal Effects</h3>
                    <p>Protection against loss, theft, or damage to your baggage and personal items, so you can replace essential gear and continue your trip.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- ============================================ -->
    <!-- 4. What We Cover Section -->
    <!-- ============================================ -->
    <section class="content-section coverage-section">
        <div class="container">
            <div class="section-header" data-aos="fade-up">
                <h2 class="section-title">Comprehensive Coverage</h2>
                <p class="section-subtitle">We partner with leading insurers to offer policies that cover the specifics of adventure travel.</p>
            </div>
            <div class="coverage-grid">
                <div class="coverage-column included" data-aos="fade-right">
                    <h4>What's Typically Included</h4>
                    <ul>
                        <li><i class="fas fa-check-circle"></i> Emergency Accident & Sickness Medical Expenses</li>
                        <li><i class="fas fa-check-circle"></i> Emergency Evacuation & Repatriation</li>
                        <li><i class="fas fa-check-circle"></i> Trip Cancellation</li>
                        <li><i class="fas fa-check-circle"></i> Trip Interruption</li>
                        <li><i class="fas fa-check-circle"></i> Lost or Delayed Baggage</li>
                        <li><i class="fas fa-check-circle"></i> 24/7 Worldwide Assistance</li>
                        <li><i class="fas fa-check-circle"></i> Coverage for many adventure sports (check policy)</li>
                    </ul>
                </div>
                <div class="coverage-column excluded" data-aos="fade-left">
                    <h4>What's Typically Not Included</h4>
                    <ul>
                        <li><i class="fas fa-times-circle"></i> Pre-existing medical conditions (unless specified)</li>
                        <li><i class="fas fa-times-circle"></i> Extreme sports or professional competitions</li>
                        <li><i class="fas fa-times-circle"></i> Travel to countries with government travel warnings</li>
                        <li><i class="fas fa-times-circle"></i> Non-emergency or elective medical procedures</li>
                        <li><i class="fas fa-times-circle"></i> Loss or damage from reckless behavior</li>
                    </ul>
                </div>
            </div>
            <p class="coverage-disclaimer" data-aos="fade-up">
                This is a general guide. Please read your specific policy details carefully to understand the full terms and conditions of your coverage.
            </p>
        </div>
    </section>

    <!-- ============================================ -->
    <!-- 5. Partners Section -->
    <!-- ============================================ -->
    <section class="content-section partners-section">
        <div class="container text-center">
             <div class="section-header" data-aos="fade-up">
                <h2 class="section-title">Our Trusted Partners</h2>
                <p class="section-subtitle">We recommend these leading providers for their excellent service and comprehensive coverage for adventure travelers.</p>
            </div>
            <div class="partners-logos" data-aos="fade-up" data-aos-delay="100">
                {{-- In a real app, these images would be stored locally --}}
                <img src="https://via.placeholder.com/200x100.png/f8f9fa/6c757d?text=World+Nomads" alt="World Nomads Logo">
                <img src="https://via.placeholder.com/200x100.png/f8f9fa/6c757d?text=Allianz+Travel" alt="Allianz Travel Logo">
                <img src="https://via.placeholder.com/200x100.png/f8f9fa/6c757d?text=SafetyWing" alt="SafetyWing Logo">
            </div>
        </div>
    </section>

@endsection

@push('styles')
<style>
    .max-w-4xl { max-width: 896px; }
    .mx-auto { margin-left: auto; margin-right: auto; }
    .text-center { text-align: center; }

    /* Page Header */
    .page-header { padding: 100px 0; position: relative; background-size: cover; background-position: center; color: var(--white); }
    .page-header::after { content: ''; position: absolute; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.6); }
    .page-header .container { position: relative; z-index: 2; }
    .page-title { font-family: var(--font-primary); font-size: 3.5rem; text-transform: uppercase; margin-bottom: 10px; }
    .page-subtitle { font-family: var(--font-secondary); font-size: 1.2rem; color: rgba(255,255,255,0.8); max-width: 600px; margin: 0 auto; }

    /* General Section Styles */
    .content-section { padding: 100px 0; }
    .section-title { font-family: var(--font-primary); font-size: 2.8rem; letter-spacing: 1px; color: var(--text-color); margin-bottom: 15px; }
    .section-subtitle { font-family: var(--font-secondary); font-size: 1.1rem; color: var(--gray); line-height: 1.7; }

    /* Why Needed Section */
    .why-needed-section { background: var(--light-green); }
    html.dark .why-needed-section { background: var(--card-bg); }
    .features-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 40px; text-align: center; }
    .feature-icon { font-size: 2.5rem; color: var(--accent-green); margin-bottom: 20px; }
    .feature-title { font-family: var(--font-primary); font-size: 1.5rem; color: var(--text-color); margin-bottom: 15px; }
    .feature-item p { font-family: var(--font-secondary); color: var(--gray); }
    
    /* Coverage Section */
    .coverage-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 40px; margin-top: 40px; }
    .coverage-column { background: var(--card-bg); padding: 30px; border-radius: 8px; box-shadow: 0 5px 25px var(--shadow); }
    .coverage-column h4 { font-family: var(--font-primary); font-size: 1.5rem; margin-bottom: 20px; }
    .coverage-column ul { list-style: none; padding: 0; }
    .coverage-column li { display: flex; align-items: flex-start; gap: 15px; margin-bottom: 15px; color: var(--gray); }
    .coverage-column li i { margin-top: 5px; }
    .coverage-column.included h4 { color: var(--accent-green); }
    .coverage-column.included li i { color: var(--accent-green); }
    .coverage-column.excluded h4 { color: #dc3545; }
    .coverage-column.excluded li i { color: #dc3545; }
    .coverage-disclaimer { text-align: center; margin-top: 40px; font-size: 0.9rem; color: var(--gray); font-style: italic; }

    /* Partners Section */
    .partners-section { background: var(--light-green); }
    html.dark .partners-section { background: var(--card-bg); }
    .partners-logos { display: flex; justify-content: center; align-items: center; flex-wrap: wrap; gap: 40px; margin-top: 40px; }
    .partners-logos img { max-height: 60px; filter: grayscale(100%); opacity: 0.6; transition: all 0.3s; }
    .partners-logos img:hover { filter: grayscale(0%); opacity: 1; }
    html.dark .partners-logos img { filter: grayscale(100%) invert(100%); opacity: 0.6; }
    html.dark .partners-logos img:hover { filter: grayscale(0%) invert(0%); opacity: 1; }

    /* Responsive */
    @media (max-width: 768px) {
        .coverage-grid { grid-template-columns: 1fr; }
    }

</style>
@endpush
