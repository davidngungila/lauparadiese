@extends('layouts.app')

@section('title', 'Booking Help - Adventure Tours')
@section('description', 'A step-by-step guide to our simple and secure booking process. Find information on payments, confirmations, and how to manage your booking.')

@section('body_class', 'support-page')

@section('content')

    <!-- Page Header -->
    <section class="page-header" style="background-image: url('https://images.unsplash.com/photo-1552664730-d307ca884978?auto=format&fit=crop&w=1920&q=80&blend=0f3429&sat=-100&bri=-20&bm=multiply');">
        <div class="container">
            <h1 class="page-title" data-aos="fade-up">Booking Help</h1>
            <p class="page-subtitle" data-aos="fade-up" data-aos-delay="100">Your adventure is just a few clicks away. Here’s how our simple booking process works.</p>
        </div>
    </section>

    <!-- Step-by-Step Guide Section -->
    <section class="content-section">
        <div class="container">
            <div class="steps-grid">
                <!-- Step 1 -->
                <div class="step-card" data-aos="fade-up">
                    <div class="step-number">01</div>
                    <div class="step-icon"><i class="fas fa-route"></i></div>
                    <h3 class="step-title">Choose Your Tour</h3>
                    <p>Browse our collection of adventures. Use our filters to sort by destination, activity, or date. Once you find the perfect trip, click "View Details" to learn more.</p>
                </div>
                <!-- Step 2 -->
                <div class="step-card" data-aos="fade-up" data-aos-delay="100">
                    <div class="step-number">02</div>
                    <div class="step-icon"><i class="fas fa-calendar-check"></i></div>
                    <h3 class="step-title">Select Your Date</h3>
                    <p>On the tour page, you'll find a list of available departure dates. Select the one that works for you and specify the number of travelers in your group.</p>
                </div>
                <!-- Step 3 -->
                <div class="step-card" data-aos="fade-up" data-aos-delay="200">
                    <div class="step-number">03</div>
                    <div class="step-icon"><i class="fas fa-user-edit"></i></div>
                    <h3 class="step-title">Enter Your Details</h3>
                    <p>Proceed to our secure booking page. Fill in your personal information and any optional add-ons, like gear rental or travel insurance.</p>
                </div>
                <!-- Step 4 -->
                <div class="step-card" data-aos="fade-up" data-aos-delay="300">
                    <div class="step-number">04</div>
                    <div class="step-icon"><i class="fas fa-credit-card"></i></div>
                    <h3 class="step-title">Confirm & Pay</h3>
                    <p>Review your booking summary and complete the payment through our secure gateway. We accept all major credit cards. Once complete, you'll receive an instant confirmation email.</p>
                </div>
            </div>
        </div>
    </section>

@endsection

@push('styles')
<style>
    /* Page Header Styles */
    .page-header { padding: 100px 0; /* ... */ }

    .steps-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 30px;
    }
    .step-card {
        background: var(--card-bg);
        padding: 40px;
        border-radius: 12px;
        box-shadow: 0 5px 25px var(--shadow);
        text-align: center;
        position: relative;
    }
    .step-number {
        position: absolute;
        top: 20px;
        left: 20px;
        font-family: var(--font-primary);
        font-size: 2rem;
        font-weight: 700;
        color: var(--accent-green);
        opacity: 0.1;
    }
    .step-icon {
        font-size: 2.5rem;
        color: var(--accent-green);
        margin-bottom: 20px;
    }
    .step-title {
        font-family: var(--font-primary);
        font-size: 1.5rem;
        color: var(--text-color);
        margin-bottom: 15px;
    }
    .step-card p { color: var(--gray); }
</style>
@endpush
