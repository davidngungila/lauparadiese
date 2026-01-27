@extends('layouts.app')

@section('title', 'Privacy Policy - Lau Paradise Adventures')
@section('description', 'Privacy policy and data protection information for Lau Paradise Adventures')

@section('content')

<!-- Hero Section -->
<section class="legal-hero-section">
    <div class="container">
        <div class="legal-hero-content">
            <h1>Privacy Policy</h1>
            <p class="legal-subtitle">Last Updated: {{ date('F j, Y') }}</p>
        </div>
    </div>
</section>

<!-- Privacy Content -->
<section class="legal-content-section">
    <div class="container">
        <div class="legal-content-wrapper">
            <div class="legal-content">
                <div class="legal-intro">
                    <p class="lead">
                        At Lau Paradise Adventures, we are committed to protecting your privacy and personal information. 
                        This Privacy Policy explains how we collect, use, disclose, and safeguard your information when you 
                        use our website and services.
                    </p>
                </div>

                <div class="legal-section">
                    <h2>1. Information We Collect</h2>
                    
                    <h3>1.1 Personal Information</h3>
                    <p>We collect information that you provide directly to us, including:</p>
                    <ul>
                        <li><strong>Booking Information:</strong> Name, email address, phone number, passport details, travel dates</li>
                        <li><strong>Payment Information:</strong> Processed securely through Pesapal (we do not store card details)</li>
                        <li><strong>Contact Information:</strong> When you contact us via forms, email, or phone</li>
                        <li><strong>Account Information:</strong> If you create an account with us</li>
                    </ul>

                    <h3>1.2 Automatically Collected Information</h3>
                    <p>When you visit our website, we automatically collect certain information:</p>
                    <ul>
                        <li>IP address and browser type</li>
                        <li>Device information and operating system</li>
                        <li>Pages visited and time spent on pages</li>
                        <li>Referring website addresses</li>
                        <li>Cookies and similar tracking technologies</li>
                    </ul>
                </div>

                <div class="legal-section">
                    <h2>2. How We Use Your Information</h2>
                    <p>We use the information we collect for the following purposes:</p>
                    <ul>
                        <li><strong>Booking Processing:</strong> To process and manage your tour bookings</li>
                        <li><strong>Communication:</strong> To send booking confirmations, updates, and respond to inquiries</li>
                        <li><strong>Payment Processing:</strong> To process payments securely through Pesapal</li>
                        <li><strong>Service Improvement:</strong> To improve our website, services, and customer experience</li>
                        <li><strong>Marketing:</strong> To send promotional materials (with your consent, which you can opt-out of)</li>
                        <li><strong>Legal Compliance:</strong> To comply with legal obligations and protect our rights</li>
                        <li><strong>Safety and Security:</strong> To ensure the safety of our tours and participants</li>
                    </ul>
                </div>

                <div class="legal-section">
                    <h2>3. Information Sharing and Disclosure</h2>
                    <p>We do not sell your personal information. We may share your information only in the following circumstances:</p>
                    
                    <h3>3.1 Service Providers</h3>
                    <p>We may share information with trusted third-party service providers who assist us in:</p>
                    <ul>
                        <li>Payment processing (Pesapal)</li>
                        <li>Email delivery services</li>
                        <li>Website hosting and analytics</li>
                        <li>Customer support services</li>
                    </ul>
                    <p>These providers are contractually obligated to protect your information and use it only for specified purposes.</p>

                    <h3>3.2 Legal Requirements</h3>
                    <p>We may disclose your information if required by law, court order, or government regulation, or to:</p>
                    <ul>
                        <li>Comply with legal processes</li>
                        <li>Protect our rights and property</li>
                        <li>Prevent fraud or security issues</li>
                        <li>Protect the safety of our customers and the public</li>
                    </ul>

                    <h3>3.3 Business Transfers</h3>
                    <p>
                        In the event of a merger, acquisition, or sale of assets, your information may be transferred to the 
                        acquiring entity, subject to the same privacy protections.
                    </p>
                </div>

                <div class="legal-section">
                    <h2>4. Payment Information</h2>
                    <p>
                        All payments are processed securely through Pesapal, a PCI-DSS compliant payment processor. We do not 
                        store your credit card details, CVV codes, or full payment card numbers on our servers. Pesapal handles 
                        all payment data according to industry security standards.
                    </p>
                    <p>
                        When you make a payment, you are redirected to Pesapal's secure payment page. Your payment information 
                        is encrypted and transmitted securely. We only receive confirmation of payment status, not your actual 
                        payment details.
                    </p>
                </div>

                <div class="legal-section">
                    <h2>5. Data Security</h2>
                    <p>We implement appropriate technical and organizational measures to protect your personal information:</p>
                    <ul>
                        <li><strong>Encryption:</strong> Data transmitted over the internet is encrypted using SSL/TLS</li>
                        <li><strong>Secure Storage:</strong> Personal data is stored on secure servers with restricted access</li>
                        <li><strong>Access Controls:</strong> Only authorized personnel have access to personal information</li>
                        <li><strong>Regular Updates:</strong> We regularly update our security measures and systems</li>
                        <li><strong>Monitoring:</strong> We monitor for security breaches and unauthorized access</li>
                    </ul>
                    <p>
                        However, no method of transmission over the internet or electronic storage is 100% secure. While we 
                        strive to protect your information, we cannot guarantee absolute security.
                    </p>
                </div>

                <div class="legal-section">
                    <h2>6. Cookies and Tracking Technologies</h2>
                    <p>We use cookies and similar technologies to:</p>
                    <ul>
                        <li>Remember your preferences and settings</li>
                        <li>Analyze website traffic and usage patterns</li>
                        <li>Improve website functionality and user experience</li>
                        <li>Provide personalized content and advertisements</li>
                    </ul>
                    <p>
                        You can control cookies through your browser settings. However, disabling cookies may affect website 
                        functionality. For more information, see our <a href="{{ route('cookies') }}">Cookie Policy</a>.
                    </p>
                </div>

                <div class="legal-section">
                    <h2>7. Your Rights and Choices</h2>
                    <p>You have the following rights regarding your personal information:</p>
                    
                    <h3>7.1 Access and Correction</h3>
                    <p>You can access and update your personal information by contacting us or logging into your account.</p>

                    <h3>7.2 Data Deletion</h3>
                    <p>
                        You can request deletion of your personal information, subject to legal and contractual obligations. 
                        We may retain certain information as required by law or for legitimate business purposes.
                    </p>

                    <h3>7.3 Marketing Communications</h3>
                    <p>
                        You can opt-out of marketing emails by clicking the unsubscribe link in any email or contacting us directly. 
                        You will still receive transactional emails related to your bookings.
                    </p>

                    <h3>7.4 Data Portability</h3>
                    <p>You can request a copy of your personal data in a structured, machine-readable format.</p>

                    <h3>7.5 Objection to Processing</h3>
                    <p>You can object to certain types of data processing, such as direct marketing.</p>
                </div>

                <div class="legal-section">
                    <h2>8. Data Retention</h2>
                    <p>We retain your personal information for as long as necessary to:</p>
                    <ul>
                        <li>Fulfill the purposes for which it was collected</li>
                        <li>Comply with legal obligations</li>
                        <li>Resolve disputes and enforce agreements</li>
                        <li>Maintain business records as required by law</li>
                    </ul>
                    <p>
                        Booking information is typically retained for 7 years after the completion of your tour for accounting 
                        and legal purposes. Marketing data is retained until you opt-out or request deletion.
                    </p>
                </div>

                <div class="legal-section">
                    <h2>9. International Data Transfers</h2>
                    <p>
                        Your information may be transferred to and processed in countries other than your country of residence. 
                        These countries may have different data protection laws. We ensure appropriate safeguards are in place to 
                        protect your information in accordance with this Privacy Policy.
                    </p>
                </div>

                <div class="legal-section">
                    <h2>10. Children's Privacy</h2>
                    <p>
                        Our services are not directed to individuals under 18 years of age. We do not knowingly collect personal 
                        information from children. If you are a parent or guardian and believe your child has provided us with 
                        personal information, please contact us to have it removed.
                    </p>
                </div>

                <div class="legal-section">
                    <h2>11. Third-Party Links</h2>
                    <p>
                        Our website may contain links to third-party websites. We are not responsible for the privacy practices 
                        or content of these external sites. We encourage you to review the privacy policies of any third-party 
                        sites you visit.
                    </p>
                </div>

                <div class="legal-section">
                    <h2>12. Changes to This Privacy Policy</h2>
                    <p>
                        We may update this Privacy Policy from time to time. We will notify you of any material changes by posting 
                        the new Privacy Policy on this page and updating the "Last Updated" date. We encourage you to review this 
                        Privacy Policy periodically.
                    </p>
                </div>

                <div class="legal-section">
                    <h2>13. Contact Us</h2>
                    <p>If you have questions, concerns, or requests regarding this Privacy Policy or your personal information, please contact us:</p>
                    <div class="contact-info">
                        <p><strong>Lau Paradise Adventures</strong></p>
                        <p><i class="fas fa-envelope me-2"></i>Email: <a href="mailto:lauparadiseadventure@gmail.com">lauparadiseadventure@gmail.com</a></p>
                        <p><i class="fas fa-phone me-2"></i>Phone: <a href="tel:+255683163219">+255 683 163 219</a></p>
                        <p><i class="fas fa-map-marker-alt me-2"></i>Address: Tanzania</p>
                    </div>
                    <p class="mt-3">
                        <strong>Data Protection Officer:</strong> For data protection inquiries, please contact us at 
                        <a href="mailto:lauparadiseadventure@gmail.com">lauparadiseadventure@gmail.com</a>
                    </p>
                </div>

                <div class="legal-footer">
                    <p class="text-muted">
                        <small>By using our website and services, you acknowledge that you have read and understood this Privacy Policy 
                        and consent to the collection and use of your information as described herein.</small>
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

.legal-section a {
    color: #1a4d3e;
    text-decoration: underline;
}

.legal-section a:hover {
    color: #2d7a5f;
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

