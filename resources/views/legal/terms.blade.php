@extends('layouts.app')

@section('title', 'Terms of Service - Lau Paradise Adventures')
@section('description', 'Terms and conditions for booking tours with Lau Paradise Adventures')

@section('content')

<!-- Hero Section -->
<section class="legal-hero-section">
    <div class="container">
        <div class="legal-hero-content">
            <h1>Terms of Service</h1>
            <p class="legal-subtitle">Last Updated: {{ date('F j, Y') }}</p>
        </div>
    </div>
</section>

<!-- Terms Content -->
<section class="legal-content-section">
    <div class="container">
        <div class="legal-content-wrapper">
            <div class="legal-content">
                <div class="legal-intro">
                    <p class="lead">
                        Welcome to Lau Paradise Adventures. These Terms of Service ("Terms") govern your use of our website and services. 
                        By booking a tour or using our services, you agree to be bound by these Terms.
                    </p>
                </div>

                <div class="legal-section">
                    <h2>1. Acceptance of Terms</h2>
                    <p>
                        By accessing or using our website, booking a tour, or using any of our services, you acknowledge that you have read, 
                        understood, and agree to be bound by these Terms of Service and our Privacy Policy. If you do not agree to these Terms, 
                        please do not use our services.
                    </p>
                </div>

                <div class="legal-section">
                    <h2>2. Booking and Reservations</h2>
                    <h3>2.1 Booking Process</h3>
                    <p>
                        All bookings are subject to availability and confirmation. A booking is considered confirmed once you receive a 
                        confirmation email from us with your booking reference number.
                    </p>
                    
                    <h3>2.2 Payment Terms</h3>
                    <ul>
                        <li>Full payment or deposit (as specified) is required to confirm your booking</li>
                        <li>Payments can be made via Pesapal (cards, mobile money, bank transfer)</li>
                        <li>All prices are in USD unless otherwise stated</li>
                        <li>Prices are subject to change without notice until booking is confirmed</li>
                    </ul>

                    <h3>2.3 Booking Modifications</h3>
                    <p>
                        Changes to confirmed bookings are subject to availability and may incur additional fees. Modification requests must 
                        be made at least 14 days before the departure date.
                    </p>
                </div>

                <div class="legal-section">
                    <h2>3. Cancellation and Refund Policy</h2>
                    <h3>3.1 Cancellation by Customer</h3>
                    <ul>
                        <li><strong>30+ days before departure:</strong> Full refund minus 10% administrative fee</li>
                        <li><strong>15-29 days before departure:</strong> 50% refund</li>
                        <li><strong>8-14 days before departure:</strong> 25% refund</li>
                        <li><strong>Less than 7 days:</strong> No refund</li>
                    </ul>

                    <h3>3.2 Cancellation by Company</h3>
                    <p>
                        In the unlikely event that we must cancel a tour, you will receive a full refund or the option to reschedule to 
                        another date. We are not liable for any additional expenses incurred.
                    </p>

                    <h3>3.3 Refund Processing</h3>
                    <p>
                        Refunds will be processed within 14 business days to the original payment method. Processing times may vary depending 
                        on your payment provider.
                    </p>
                </div>

                <div class="legal-section">
                    <h2>4. Travel Requirements</h2>
                    <h3>4.1 Travel Documents</h3>
                    <p>
                        It is your responsibility to ensure you have valid travel documents, including passports, visas, and any required 
                        vaccinations. We are not responsible for denied entry due to missing or invalid documents.
                    </p>

                    <h3>4.2 Health and Fitness</h3>
                    <p>
                        Some tours require a certain level of physical fitness. Please inform us of any medical conditions or special 
                        requirements at the time of booking. We reserve the right to refuse participation if we determine a tour is 
                        unsuitable for your health condition.
                    </p>

                    <h3>4.3 Travel Insurance</h3>
                    <p>
                        We strongly recommend purchasing comprehensive travel insurance covering medical expenses, trip cancellation, 
                        and personal belongings. Travel insurance can be purchased as an add-on during booking.
                    </p>
                </div>

                <div class="legal-section">
                    <h2>5. Tour Conduct and Responsibilities</h2>
                    <h3>5.1 Participant Behavior</h3>
                    <p>
                        Participants must conduct themselves in a respectful manner. We reserve the right to remove any participant 
                        whose behavior is disruptive, dangerous, or violates local laws, without refund.
                    </p>

                    <h3>5.2 Our Responsibilities</h3>
                    <ul>
                        <li>Provide services as described in the tour itinerary</li>
                        <li>Ensure the safety and well-being of participants</li>
                        <li>Provide qualified guides and appropriate equipment</li>
                        <li>Maintain appropriate insurance coverage</li>
                    </ul>

                    <h3>5.3 Your Responsibilities</h3>
                    <ul>
                        <li>Follow guide instructions and safety protocols</li>
                        <li>Respect local customs and environment</li>
                        <li>Arrive on time for scheduled activities</li>
                        <li>Inform us of any changes to your contact information</li>
                    </ul>
                </div>

                <div class="legal-section">
                    <h2>6. Pricing and Payment</h2>
                    <h3>6.1 Price Inclusions</h3>
                    <p>
                        Tour prices include accommodation, meals (as specified), transportation, guide services, and activities listed 
                        in the itinerary. Prices do not include flights, visas, travel insurance, personal expenses, or optional activities.
                    </p>

                    <h3>6.2 Price Changes</h3>
                    <p>
                        We reserve the right to adjust prices due to changes in exchange rates, fuel costs, or government taxes. 
                        You will be notified of any significant price changes before final payment.
                    </p>

                    <h3>6.3 Payment Security</h3>
                    <p>
                        All payments are processed securely through Pesapal. We do not store your payment card details. Your payment 
                        information is encrypted and processed according to industry standards.
                    </p>
                </div>

                <div class="legal-section">
                    <h2>7. Limitation of Liability</h2>
                    <p>
                        To the maximum extent permitted by law, Lau Paradise Adventures shall not be liable for any indirect, incidental, 
                        special, or consequential damages arising from your use of our services, including but not limited to:
                    </p>
                    <ul>
                        <li>Loss of profits or revenue</li>
                        <li>Loss of data or information</li>
                        <li>Personal injury or property damage</li>
                        <li>Travel delays or cancellations beyond our control</li>
                    </ul>
                    <p>
                        Our total liability shall not exceed the amount you paid for the tour.
                    </p>
                </div>

                <div class="legal-section">
                    <h2>8. Force Majeure</h2>
                    <p>
                        We are not liable for failure to perform our obligations due to circumstances beyond our reasonable control, 
                        including natural disasters, war, terrorism, pandemics, government actions, or other force majeure events. 
                        In such cases, we will work with you to find alternative solutions.
                    </p>
                </div>

                <div class="legal-section">
                    <h2>9. Intellectual Property</h2>
                    <p>
                        All content on our website, including text, images, logos, and tour descriptions, is the property of Lau Paradise 
                        Adventures and protected by copyright laws. You may not reproduce, distribute, or use our content without written 
                        permission.
                    </p>
                </div>

                <div class="legal-section">
                    <h2>10. Privacy and Data Protection</h2>
                    <p>
                        Your personal information is handled in accordance with our Privacy Policy. By using our services, you consent 
                        to the collection and use of your information as described in our Privacy Policy.
                    </p>
                </div>

                <div class="legal-section">
                    <h2>11. Dispute Resolution</h2>
                    <p>
                        Any disputes arising from these Terms or our services shall be resolved through good faith negotiation. 
                        If a resolution cannot be reached, disputes shall be subject to the exclusive jurisdiction of the courts 
                        of Tanzania.
                    </p>
                </div>

                <div class="legal-section">
                    <h2>12. Changes to Terms</h2>
                    <p>
                        We reserve the right to modify these Terms at any time. Changes will be effective immediately upon posting on 
                        our website. Your continued use of our services after changes are posted constitutes acceptance of the modified Terms.
                    </p>
                </div>

                <div class="legal-section">
                    <h2>13. Contact Information</h2>
                    <p>
                        If you have any questions about these Terms of Service, please contact us:
                    </p>
                    <div class="contact-info">
                        <p><strong>Lau Paradise Adventures</strong></p>
                        <p><i class="fas fa-envelope me-2"></i>Email: <a href="mailto:lauparadiseadventure@gmail.com">lauparadiseadventure@gmail.com</a></p>
                        <p><i class="fas fa-phone me-2"></i>Phone: <a href="tel:+255683163219">+255 683 163 219</a></p>
                        <p><i class="fas fa-map-marker-alt me-2"></i>Address: Tanzania</p>
                    </div>
                </div>

                <div class="legal-footer">
                    <p class="text-muted">
                        <small>By booking with Lau Paradise Adventures, you acknowledge that you have read, understood, and agree to be 
                        bound by these Terms of Service.</small>
                    </p>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection

@push('styles')
<style>
.legal-hero-section {
    background: linear-gradient(135deg, #1a4d3e 0%, #2d7a5f 100%);
    color: white;
    padding: 100px 0 60px;
    text-align: center;
}

.legal-hero-content h1 {
    font-size: 3rem;
    font-weight: 800;
    margin-bottom: 15px;
}

.legal-subtitle {
    font-size: 1.1rem;
    opacity: 0.9;
}

.legal-content-section {
    padding: 60px 0;
    background: #f8f9fa;
}

.legal-content-wrapper {
    max-width: 900px;
    margin: 0 auto;
}

.legal-content {
    background: white;
    border-radius: 20px;
    padding: 50px;
    box-shadow: 0 10px 40px rgba(0,0,0,0.1);
}

.legal-intro {
    margin-bottom: 40px;
    padding-bottom: 30px;
    border-bottom: 2px solid #e9ecef;
}

.legal-intro .lead {
    font-size: 1.2rem;
    color: #495057;
    line-height: 1.8;
}

.legal-section {
    margin-bottom: 40px;
    padding-bottom: 30px;
    border-bottom: 1px solid #e9ecef;
}

.legal-section:last-child {
    border-bottom: none;
}

.legal-section h2 {
    color: #1a4d3e;
    font-size: 1.8rem;
    font-weight: 700;
    margin-bottom: 20px;
}

.legal-section h3 {
    color: #2d7a5f;
    font-size: 1.3rem;
    font-weight: 600;
    margin-top: 25px;
    margin-bottom: 15px;
}

.legal-section p {
    color: #495057;
    line-height: 1.8;
    margin-bottom: 15px;
}

.legal-section ul {
    margin: 15px 0;
    padding-left: 25px;
}

.legal-section li {
    color: #495057;
    line-height: 1.8;
    margin-bottom: 10px;
}

.contact-info {
    background: #f8f9fa;
    padding: 25px;
    border-radius: 10px;
    margin-top: 20px;
}

.contact-info p {
    margin-bottom: 10px;
}

.legal-footer {
    margin-top: 40px;
    padding-top: 30px;
    border-top: 2px solid #e9ecef;
    text-align: center;
}

@media (max-width: 768px) {
    .legal-hero-content h1 {
        font-size: 2rem;
    }
    
    .legal-content {
        padding: 30px 20px;
    }
    
    .legal-section h2 {
        font-size: 1.5rem;
    }
}
</style>
@endpush

