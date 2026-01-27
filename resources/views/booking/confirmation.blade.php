@extends('layouts.app')

@section('title', 'Booking Confirmation - Lau Paradise Adventures')
@section('description', 'Your booking has been confirmed. Thank you for choosing Lau Paradise Adventures!')

@section('body_class', 'booking-confirmation-page')

@section('content')

    <!-- Page Header -->
    <section class="page-hero" style="background-image: url('https://images.unsplash.com/photo-1519542232379-1641f92e92c2?auto=format&fit=crop&w=1920&q=80');">
        <div class="hero-overlay"></div>
        <div class="container">
            <div class="hero-content" data-aos="fade-up">
                <h1 class="hero-title">Booking Confirmed!</h1>
                <p class="hero-subtitle">Thank you for choosing Lau Paradise Adventures. Your Tanzania adventure awaits!</p>
            </div>
        </div>
    </section>

    <!-- Confirmation Section -->
    <section class="confirmation-section">
        <div class="container">
            <div class="confirmation-wrapper">
                <!-- Success Message -->
                <div class="success-message" data-aos="zoom-in">
                    <div class="success-icon">
                        <i class="fas fa-check-circle"></i>
                    </div>
                    <h2>Your Booking Has Been Confirmed</h2>
                    <p>Booking Reference: <strong>{{ $booking->booking_reference }}</strong></p>
                    <p class="success-text">We've sent a confirmation email to <strong>{{ $booking->customer_email }}</strong> with all the details of your booking.</p>
                </div>

                <!-- Booking Details Card -->
                <div class="booking-details-card" data-aos="fade-up" data-aos-delay="200">
                    <h3 class="card-title">
                        <i class="fas fa-info-circle"></i> Booking Details
                    </h3>
                    <div class="details-grid">
                        <div class="detail-item">
                            <span class="detail-label">Booking Reference</span>
                            <span class="detail-value">{{ $booking->booking_reference }}</span>
                        </div>
                        <div class="detail-item">
                            <span class="detail-label">Tour</span>
                            <span class="detail-value">{{ $booking->tour ? $booking->tour->name : 'Custom Tour' }}</span>
                        </div>
                        <div class="detail-item">
                            <span class="detail-label">Customer Name</span>
                            <span class="detail-value">{{ $booking->customer_name }}</span>
                        </div>
                        <div class="detail-item">
                            <span class="detail-label">Email</span>
                            <span class="detail-value">{{ $booking->customer_email }}</span>
                        </div>
                        <div class="detail-item">
                            <span class="detail-label">Phone</span>
                            <span class="detail-value">{{ $booking->customer_phone ?: 'Not provided' }}</span>
                        </div>
                        @if($booking->customer_country)
                        <div class="detail-item">
                            <span class="detail-label">Country</span>
                            <span class="detail-value">{{ $booking->customer_country }}</span>
                        </div>
                        @endif
                        @if($booking->deposit_amount)
                        <div class="detail-item">
                            <span class="detail-label">Deposit Amount</span>
                            <span class="detail-value">${{ number_format($booking->deposit_amount, 2) }}</span>
                        </div>
                        @endif
                        @if($booking->balance_amount)
                        <div class="detail-item">
                            <span class="detail-label">Balance Due</span>
                            <span class="detail-value">${{ number_format($booking->balance_amount, 2) }}</span>
                        </div>
                        @endif
                        <div class="detail-item">
                            <span class="detail-label">Departure Date</span>
                            <span class="detail-value">{{ $booking->departure_date->format('F j, Y') }}</span>
                        </div>
                        <div class="detail-item">
                            <span class="detail-label">Number of Travelers</span>
                            <span class="detail-value">{{ $booking->travelers }} {{ $booking->travelers == 1 ? 'Traveler' : 'Travelers' }}</span>
                        </div>
                        <div class="detail-item">
                            <span class="detail-label">Status</span>
                            <span class="detail-value status-badge status-{{ $booking->status }}">
                                {{ ucfirst(str_replace('_', ' ', $booking->status)) }}
                            </span>
                        </div>
                        @if($booking->addons && count($booking->addons) > 0)
                        <div class="detail-item full-width">
                            <span class="detail-label">Add-ons</span>
                            <span class="detail-value">
                                @foreach($booking->addons as $addon)
                                    <span class="addon-badge">{{ ucfirst($addon) }}</span>
                                @endforeach
                            </span>
                        </div>
                        @endif
                        <div class="detail-item full-width">
                            <span class="detail-label">Total Price</span>
                            <span class="detail-value total-price">${{ number_format($booking->total_price, 2) }}</span>
                        </div>
                        @if($booking->payment_method)
                        <div class="detail-item">
                            <span class="detail-label">Payment Method</span>
                            <span class="detail-value">{{ ucfirst(str_replace('_', ' ', $booking->payment_method)) }}</span>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Next Steps Card -->
                <div class="next-steps-card" data-aos="fade-up" data-aos-delay="300">
                    <h3 class="card-title">
                        <i class="fas fa-list-check"></i> What Happens Next?
                    </h3>
                    <ul class="steps-list">
                        <li>
                            <i class="fas fa-envelope"></i>
                            <div>
                                <strong>Confirmation Email</strong>
                                <p>You'll receive a detailed confirmation email within the next few minutes with all booking information.</p>
                            </div>
                        </li>
                        <li>
                            <i class="fas fa-calendar-check"></i>
                            <div>
                                <strong>Pre-Trip Information</strong>
                                <p>We'll send you pre-trip information and packing lists 7 days before your departure date.</p>
                            </div>
                        </li>
                        <li>
                            <i class="fas fa-headset"></i>
                            <div>
                                <strong>24/7 Support</strong>
                                <p>Our team is available 24/7 to answer any questions. Contact us via WhatsApp, email, or phone.</p>
                            </div>
                        </li>
                        @if($booking->status === 'pending_payment')
                        <li>
                            <i class="fas fa-credit-card"></i>
                            <div>
                                <strong>Complete Payment</strong>
                                <p>Please complete your payment to confirm your booking. You'll receive payment instructions via email.</p>
                            </div>
                        </li>
                        @endif
                    </ul>
                </div>

                <!-- Action Buttons -->
                <div class="action-buttons" data-aos="fade-up" data-aos-delay="400">
                    <a href="{{ route('booking.invoice.download', $booking->booking_reference) }}" class="btn btn-primary" target="_blank">
                        <i class="fas fa-download"></i> Download Invoice
                    </a>
                    <a href="{{ route('booking.invoice.view', $booking->booking_reference) }}" class="btn btn-secondary" target="_blank">
                        <i class="fas fa-file-invoice"></i> View Invoice
                    </a>
                    <a href="{{ route('home') }}" class="btn btn-primary">
                        <i class="fas fa-home"></i> Back to Home
                    </a>
                    <a href="{{ route('tours.index') }}" class="btn btn-secondary">
                        <i class="fas fa-route"></i> Explore More Tours
                    </a>
                    <a href="https://wa.me/255123456789?text=Hi%20Lau%20Paradise%20Adventures,%20I%20just%20booked%20tour%20{{ $booking->booking_reference }}" target="_blank" class="btn btn-whatsapp">
                        <i class="fab fa-whatsapp"></i> Chat on WhatsApp
                    </a>
                </div>
            </div>
        </div>
    </section>

@endsection

@push('styles')
<style>
    /* Page Hero */
    .page-hero {
        position: relative;
        padding: 120px 0 80px;
        background-size: cover;
        background-position: center;
        color: var(--white);
        text-align: center;
        margin-top: -120px;
    }
    .hero-overlay {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: linear-gradient(135deg, rgba(26, 77, 58, 0.8) 0%, rgba(0, 0, 0, 0.6) 100%);
    }
    .hero-content {
        position: relative;
        z-index: 2;
    }
    .hero-title {
        font-size: 3.5rem;
        font-weight: 800;
        margin-bottom: 15px;
        text-shadow: 2px 2px 10px rgba(0,0,0,0.5);
        line-height: 1.2;
    }
    .hero-subtitle {
        font-size: 1.2rem;
        opacity: 0.95;
        max-width: 700px;
        margin: 0 auto;
        line-height: 1.7;
    }

    /* Confirmation Section */
    .confirmation-section {
        padding: 100px 0;
        background: var(--gray-light);
    }
    .confirmation-wrapper {
        max-width: 900px;
        margin: 0 auto;
    }

    /* Success Message */
    .success-message {
        text-align: center;
        padding: 50px 30px;
        background: var(--white);
        border-radius: 16px;
        box-shadow: 0 10px 40px rgba(0,0,0,0.1);
        margin-bottom: 40px;
    }
    html.dark .success-message {
        background: var(--card-bg);
    }
    .success-icon {
        font-size: 5rem;
        color: var(--accent-green);
        margin-bottom: 20px;
    }
    .success-message h2 {
        font-size: 2.5rem;
        color: var(--primary-green);
        margin-bottom: 15px;
        font-weight: 700;
    }
    .success-message p {
        font-size: 1.1rem;
        color: var(--gray);
        margin-bottom: 10px;
    }
    .success-text {
        margin-top: 20px;
        padding-top: 20px;
        border-top: 1px solid var(--gray-light);
    }

    /* Booking Details Card */
    .booking-details-card, .next-steps-card {
        background: var(--white);
        border-radius: 16px;
        box-shadow: 0 10px 40px rgba(0,0,0,0.1);
        padding: 40px;
        margin-bottom: 30px;
    }
    html.dark .booking-details-card, html.dark .next-steps-card {
        background: var(--card-bg);
    }
    .card-title {
        font-size: 1.8rem;
        color: var(--primary-green);
        margin-bottom: 30px;
        font-weight: 700;
        display: flex;
        align-items: center;
        gap: 10px;
    }
    .card-title i {
        color: var(--accent-green);
    }
    .details-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 20px;
    }
    .detail-item {
        display: flex;
        flex-direction: column;
        gap: 8px;
    }
    .detail-item.full-width {
        grid-column: 1 / -1;
    }
    .detail-label {
        font-size: 0.9rem;
        color: var(--gray);
        font-weight: 500;
    }
    .detail-value {
        font-size: 1.1rem;
        color: var(--text-color);
        font-weight: 600;
    }
    .detail-value.total-price {
        font-size: 2rem;
        color: var(--accent-green);
        font-weight: 700;
    }
    .status-badge {
        display: inline-block;
        padding: 6px 14px;
        border-radius: 20px;
        font-size: 0.85rem;
        font-weight: 600;
    }
    .status-confirmed {
        background: var(--light-green);
        color: var(--primary-green);
    }
    .status-pending_payment {
        background: #fff3cd;
        color: #856404;
    }
    .status-cancelled {
        background: #f8d7da;
        color: #721c24;
    }
    .addon-badge {
        display: inline-block;
        padding: 4px 12px;
        background: var(--light-green);
        color: var(--primary-green);
        border-radius: 12px;
        font-size: 0.85rem;
        margin-right: 8px;
        margin-bottom: 5px;
    }

    /* Next Steps */
    .steps-list {
        list-style: none;
        padding: 0;
    }
    .steps-list li {
        display: flex;
        gap: 20px;
        padding: 20px 0;
        border-bottom: 1px solid var(--gray-light);
    }
    .steps-list li:last-child {
        border-bottom: none;
    }
    .steps-list li i {
        font-size: 2rem;
        color: var(--accent-green);
        flex-shrink: 0;
    }
    .steps-list li strong {
        display: block;
        color: var(--primary-green);
        margin-bottom: 5px;
        font-size: 1.1rem;
    }
    .steps-list li p {
        color: var(--gray);
        margin: 0;
        line-height: 1.6;
    }

    /* Action Buttons */
    .action-buttons {
        display: flex;
        justify-content: center;
        gap: 15px;
        flex-wrap: wrap;
        margin-top: 40px;
    }
    .btn {
        padding: 15px 35px;
        border-radius: 50px;
        text-decoration: none;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 10px;
        transition: all 0.3s ease;
        font-size: 1rem;
    }
    .btn-primary {
        background: var(--accent-green);
        color: var(--white);
        box-shadow: 0 4px 15px rgba(61, 165, 114, 0.3);
    }
    .btn-primary:hover {
        background: var(--secondary-green);
        transform: translateY(-3px);
        box-shadow: 0 8px 25px rgba(61, 165, 114, 0.4);
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
    .btn-whatsapp {
        background: #25D366;
        color: var(--white);
        box-shadow: 0 4px 15px rgba(37, 211, 102, 0.3);
    }
    .btn-whatsapp:hover {
        background: #20BA5A;
        transform: translateY(-3px);
        box-shadow: 0 8px 25px rgba(37, 211, 102, 0.4);
    }

    /* Responsive */
    @media (max-width: 768px) {
        .hero-title {
            font-size: 2.5rem;
        }
        .details-grid {
            grid-template-columns: 1fr;
        }
        .action-buttons {
            flex-direction: column;
        }
        .btn {
            width: 100%;
            justify-content: center;
        }
    }
</style>
@endpush

