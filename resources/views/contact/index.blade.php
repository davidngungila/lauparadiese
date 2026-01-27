@extends('layouts.app')

@section('title', 'Contact Us - Lau Paradise Adventures')
@section('description', 'Get in touch with our Tanzania travel experts. We\'re here 24/7 to help you plan your perfect Tanzania adventure.')

@section('content')

<!-- Hero Section -->
<section class="page-hero-section" style="background-image: url('{{ asset('images/hero-slider/safari-adventure.jpg') }}');">
    <div class="page-hero-overlay"></div>
        <div class="container">
        <div class="page-hero-content" data-aos="fade-up">
            <span class="hero-badge"><i class="fas fa-headset"></i> We're Here to Help</span>
            <h1 class="page-hero-title">Get In Touch</h1>
            <p class="page-hero-subtitle">We're here to help you plan your perfect Tanzania adventure. Reach out to our Tanzania-based team anytime - we're available 24/7.</p>
            </div>
        </div>
    </section>

    <!-- Contact Options Section -->
    <section class="contact-options-section">
        <div class="container">
        <div class="section-header" data-aos="fade-up">
            <span class="section-badge">Multiple Ways to Reach Us</span>
            <h2 class="section-title">Choose Your Preferred Contact Method</h2>
            <p class="section-subtitle">We're available through multiple channels to make it easy for you to get in touch.</p>
        </div>
            <div class="contact-options-grid">
            <div class="contact-option-card" data-aos="fade-up">
                    <div class="option-icon"><i class="fas fa-phone"></i></div>
                    <h3>Call Us</h3>
                    <p>Speak directly with our travel experts</p>
                    <div class="option-details">
                    <a href="tel:+255683163219" class="contact-link">+255 683 163 219</a>
                    <span class="option-hours"><i class="fas fa-clock"></i> Mon-Fri: 8am - 6pm EAT</span>
                    <span class="option-hours"><i class="fas fa-clock"></i> Sat-Sun: 9am - 4pm EAT</span>
                </div>
            </div>
            <div class="contact-option-card" data-aos="fade-up" data-aos-delay="100">
                    <div class="option-icon whatsapp"><i class="fab fa-whatsapp"></i></div>
                    <h3>WhatsApp</h3>
                <p>Chat with us instantly - fastest response!</p>
                    <div class="option-details">
                    <a href="https://wa.me/255683163219?text=Hi%20Lau%20Paradise%20Adventures,%20I%20would%20like%20to%20inquire%20about%20your%20tours" target="_blank" class="contact-link">+255 683 163 219</a>
                    <span class="option-hours"><i class="fas fa-check-circle"></i> 24/7 Available</span>
                    <span class="option-hours"><i class="fas fa-bolt"></i> Instant Response</span>
                </div>
            </div>
            <div class="contact-option-card" data-aos="fade-up" data-aos-delay="200">
                    <div class="option-icon"><i class="fas fa-envelope"></i></div>
                    <h3>Email Us</h3>
                    <p>Send us a detailed message</p>
                    <div class="option-details">
                    <a href="mailto:lauparadiseadventure@gmail.com" class="contact-link">lauparadiseadventure@gmail.com</a>
                    <span class="option-hours"><i class="fas fa-reply"></i> Response within 24hrs</span>
                </div>
            </div>
            <div class="contact-option-card" data-aos="fade-up" data-aos-delay="300">
                <div class="option-icon"><i class="fas fa-map-marker-alt"></i></div>
                <h3>Visit Us</h3>
                <p>Come to our office in Arusha</p>
                    <div class="option-details">
                    <p class="contact-link">Arusha, Tanzania</p>
                    <p class="contact-link">Near Clock Tower</p>
                    <span class="option-hours"><i class="fas fa-clock"></i> Mon-Fri: 8am - 6pm</span>
                    </div>
                </div>
            </div>
        </div>
    </section>

<!-- Office Location Section -->
<section class="office-location-section">
        <div class="container">
        <div class="location-wrapper">
            <div class="location-info" data-aos="fade-right">
                <div class="section-header">
                    <span class="section-badge">Our Office</span>
                    <h2 class="section-title">Visit Us in Arusha</h2>
                    <p class="section-subtitle">Our main office is located in the heart of Arusha, Tanzania's safari capital.</p>
                        </div>
                <div class="office-details">
                    <div class="office-detail-item">
                        <div class="detail-icon"><i class="fas fa-map-marker-alt"></i></div>
                        <div class="detail-content">
                            <h4>Address</h4>
                            <p>Arusha, Tanzania<br>Near Clock Tower<br>P.O. Box 1234</p>
                        </div>
                    </div>
                    <div class="office-detail-item">
                        <div class="detail-icon"><i class="fas fa-clock"></i></div>
                        <div class="detail-content">
                            <h4>Office Hours</h4>
                            <p>Monday - Friday: 8:00 AM - 6:00 PM<br>Saturday: 9:00 AM - 4:00 PM<br>Sunday: Closed</p>
                        </div>
                    </div>
                    <div class="office-detail-item">
                        <div class="detail-icon"><i class="fas fa-globe"></i></div>
                        <div class="detail-content">
                            <h4>Timezone</h4>
                            <p>East Africa Time (EAT)<br>UTC+3</p>
                        </div>
                    </div>
                        </div>
                    </div>
            <div class="location-map" data-aos="fade-left">
                <iframe 
                    src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3987.1234567890123!2d36.6829!3d-3.3869!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x183de0c0c5b5b5b5%3A0x5b5b5b5b5b5b5b5b!2sArusha%2C%20Tanzania!5e0!3m2!1sen!2sus!4v1234567890123!5m2!1sen!2sus" 
                    width="100%" 
                    height="100%" 
                    style="border:0; border-radius: 16px;" 
                    allowfullscreen="" 
                    loading="lazy" 
                    referrerpolicy="no-referrer-when-downgrade"
                    title="Arusha City, Tanzania - Lau Paradise Adventures Office Location">
                </iframe>
                <div class="map-overlay">
                    <a href="https://www.google.com/maps/place/Arusha,+Tanzania/@-3.3869,36.6829,13z" target="_blank" class="map-link-btn">
                        <i class="fas fa-external-link-alt"></i> Open in Google Maps
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Why Contact Us Section -->
<section class="why-contact-section">
    <div class="container">
        <div class="section-header" data-aos="fade-up">
            <span class="section-badge">Why Contact Us</span>
            <h2 class="section-title">We're Here to Make Your Dream Trip a Reality</h2>
            <p class="section-subtitle">Our experienced team is ready to help you plan the perfect Tanzania adventure.</p>
        </div>
        <div class="why-contact-grid">
            <div class="why-contact-item" data-aos="fade-up">
                <div class="why-icon"><i class="fas fa-user-tie"></i></div>
                <h4>Expert Travel Consultants</h4>
                <p>Our team has years of experience planning safaris, climbs, and beach holidays in Tanzania. We know the best routes, accommodations, and hidden gems.</p>
            </div>
            <div class="why-contact-item" data-aos="fade-up" data-aos-delay="100">
                <div class="why-icon"><i class="fas fa-handshake"></i></div>
                <h4>Personalized Service</h4>
                <p>Every trip is unique. We work with you to create a customized itinerary that matches your interests, budget, and travel style perfectly.</p>
            </div>
            <div class="why-contact-item" data-aos="fade-up" data-aos-delay="200">
                <div class="why-icon"><i class="fas fa-shield-alt"></i></div>
                <h4>Trusted & Reliable</h4>
                <p>We're a licensed tour operator with excellent reviews. Your safety and satisfaction are our top priorities.</p>
            </div>
            <div class="why-contact-item" data-aos="fade-up" data-aos-delay="300">
                <div class="why-icon"><i class="fas fa-clock"></i></div>
                <h4>Quick Response</h4>
                <p>We respond to all inquiries within 24 hours. For urgent matters, call or WhatsApp us for instant assistance.</p>
            </div>
        </div>
    </div>
</section>

<!-- Response Time Section -->
<section class="response-time-section">
    <div class="container">
        <div class="response-time-wrapper" data-aos="fade-up">
            <div class="response-time-content">
                <div class="response-icon">
                    <i class="fas fa-hourglass-half"></i>
                </div>
                <h3>Our Response Times</h3>
                <div class="response-times-grid">
                    <div class="response-time-item">
                        <div class="response-method">
                            <i class="fab fa-whatsapp"></i>
                            <span>WhatsApp</span>
                        </div>
                        <div class="response-duration">Within 1 hour</div>
                    </div>
                    <div class="response-time-item">
                        <div class="response-method">
                            <i class="fas fa-phone"></i>
                            <span>Phone Call</span>
                        </div>
                        <div class="response-duration">Immediate</div>
                    </div>
                    <div class="response-time-item">
                        <div class="response-method">
                            <i class="fas fa-envelope"></i>
                            <span>Email</span>
                        </div>
                        <div class="response-duration">Within 24 hours</div>
                    </div>
                    <div class="response-time-item">
                        <div class="response-method">
                            <i class="fas fa-comment"></i>
                            <span>Contact Form</span>
                        </div>
                        <div class="response-duration">Within 24 hours</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Social Media Section -->
<section class="social-media-section">
    <div class="container">
        <div class="section-header" data-aos="fade-up">
            <span class="section-badge">Follow Us</span>
            <h2 class="section-title">Connect With Us on Social Media</h2>
            <p class="section-subtitle">Stay updated with our latest tours, special offers, and Tanzania travel inspiration.</p>
        </div>
        <div class="social-media-grid">
            <a href="https://facebook.com" target="_blank" class="social-media-card" data-aos="fade-up">
                <div class="social-icon facebook"><i class="fab fa-facebook-f"></i></div>
                <h4>Facebook</h4>
                <p>Follow us for travel tips and updates</p>
                <span class="social-link">Visit Page <i class="fas fa-arrow-right"></i></span>
            </a>
            <a href="https://instagram.com" target="_blank" class="social-media-card" data-aos="fade-up" data-aos-delay="100">
                <div class="social-icon instagram"><i class="fab fa-instagram"></i></div>
                <h4>Instagram</h4>
                <p>See stunning photos from our tours</p>
                <span class="social-link">Follow Us <i class="fas fa-arrow-right"></i></span>
            </a>
            <a href="https://twitter.com" target="_blank" class="social-media-card" data-aos="fade-up" data-aos-delay="200">
                <div class="social-icon twitter"><i class="fab fa-twitter"></i></div>
                <h4>Twitter</h4>
                <p>Get the latest news and updates</p>
                <span class="social-link">Follow Us <i class="fas fa-arrow-right"></i></span>
            </a>
            <a href="https://youtube.com" target="_blank" class="social-media-card" data-aos="fade-up" data-aos-delay="300">
                <div class="social-icon youtube"><i class="fab fa-youtube"></i></div>
                <h4>YouTube</h4>
                <p>Watch our safari videos and tours</p>
                <span class="social-link">Subscribe <i class="fas fa-arrow-right"></i></span>
            </a>
        </div>
    </div>
</section>

<!-- Additional Info Section -->
<section class="additional-info-section">
    <div class="container">
        <div class="info-grid">
            <div class="info-card" data-aos="fade-right">
                <div class="info-icon"><i class="fas fa-info-circle"></i></div>
                <h3>Need Help Planning?</h3>
                <p>Not sure where to start? Our travel experts can help you choose the perfect tour, answer questions about destinations, and provide recommendations based on your interests and budget.</p>
                <ul class="info-list">
                    <li><i class="fas fa-check"></i> Free consultation</li>
                    <li><i class="fas fa-check"></i> Custom itinerary planning</li>
                    <li><i class="fas fa-check"></i> Budget-friendly options</li>
                    <li><i class="fas fa-check"></i> Group booking assistance</li>
                </ul>
            </div>
            <div class="info-card" data-aos="fade-left">
                <div class="info-icon"><i class="fas fa-question-circle"></i></div>
                <h3>Have Questions?</h3>
                <p>We're here to answer all your questions about traveling to Tanzania. From visa requirements to what to pack, we've got you covered.</p>
                <ul class="info-list">
                    <li><i class="fas fa-check"></i> Visa and documentation</li>
                    <li><i class="fas fa-check"></i> Health and safety</li>
                    <li><i class="fas fa-check"></i> Best time to visit</li>
                    <li><i class="fas fa-check"></i> What to expect</li>
                </ul>
            </div>
        </div>
    </div>
</section>

<!-- Contact Form Section -->
<section class="content-section">
    <div class="container">
        <div class="section-header" data-aos="fade-up">
            <span class="section-badge">Send Us a Message</span>
            <h2 class="section-title">Let's Plan Your Adventure</h2>
            <p class="section-subtitle">Fill out the form below and our team will get back to you within 24 hours. All fields marked with * are required.</p>
        </div>
        <div class="contact-form-wrapper" data-aos="fade-up">
            @if(session('success'))
                <div class="alert alert-success">
                    <i class="fas fa-check-circle"></i> {{ session('success') }}
                </div>
            @endif
            @if($errors->any())
                <div class="alert alert-error">
                    <i class="fas fa-exclamation-circle"></i> Please correct the errors below and try again.
                </div>
            @endif
            <form action="{{ route('contact.submit') }}" method="POST" class="contact-form">
                        @csrf
                        <div class="form-row">
                            <div class="form-group">
                                <label for="name">Full Name <span class="required">*</span></label>
                        <input type="text" id="name" name="name" placeholder="John Doe" value="{{ old('name') }}" required>
                        @error('name')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                            </div>
                            <div class="form-group">
                                <label for="email">Email Address <span class="required">*</span></label>
                        <input type="email" id="email" name="email" placeholder="you@example.com" value="{{ old('email') }}" required>
                        @error('email')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group">
                                <label for="phone">Phone Number</label>
                        <input type="tel" id="phone" name="phone" placeholder="+255 123 456 789" value="{{ old('phone') }}">
                        @error('phone')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                            </div>
                            <div class="form-group">
                        <label for="subject">Subject</label>
                        <select id="subject" name="subject" class="form-input">
                            <option value="">Select a subject...</option>
                            <option value="tour-inquiry" {{ old('subject') == 'tour-inquiry' ? 'selected' : '' }}>Tour Inquiry</option>
                            <option value="booking-help" {{ old('subject') == 'booking-help' ? 'selected' : '' }}>Booking Help</option>
                            <option value="custom-tour" {{ old('subject') == 'custom-tour' ? 'selected' : '' }}>Custom Tour Request</option>
                            <option value="group-booking" {{ old('subject') == 'group-booking' ? 'selected' : '' }}>Group Booking</option>
                            <option value="partnership" {{ old('subject') == 'partnership' ? 'selected' : '' }}>Partnership Inquiry</option>
                            <option value="other" {{ old('subject') == 'other' ? 'selected' : '' }}>Other</option>
                                </select>
                        @error('subject')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                            </div>
                        </div>
                        <div class="form-group">
                    <label for="message">Message <span class="required">*</span></label>
                    <textarea id="message" name="message" rows="6" placeholder="Tell us about your dream Tanzania adventure, ask questions, or share any special requirements..." required>{{ old('message') }}</textarea>
                    @error('message')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                        </div>
                        <div class="form-group">
                    <label class="checkbox-label">
                        <input type="checkbox" name="newsletter" value="1" {{ old('newsletter') ? 'checked' : '' }}>
                                <span>Subscribe to our newsletter for travel tips and special offers</span>
                            </label>
                        </div>
                <button type="submit" class="btn-primary">
                            <i class="fas fa-paper-plane"></i> Send Message
                        </button>
                    </form>
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
    .contact-options-section {
        padding: 100px 0;
    background: var(--white);
    }
    .contact-options-grid {
        display: grid;
    grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
    gap: 30px;
    margin-top: 50px;
    }
    .contact-option-card {
    background: var(--gray-light);
        padding: 40px 30px;
        border-radius: 16px;
        text-align: center;
        box-shadow: 0 5px 25px rgba(0,0,0,0.08);
    transition: all 0.3s;
    border: 2px solid transparent;
    }
    .contact-option-card:hover {
        transform: translateY(-10px);
        box-shadow: 0 15px 40px rgba(0,0,0,0.15);
    border-color: var(--accent-green);
    }
    .option-icon {
    width: 80px;
    height: 80px;
        margin: 0 auto 20px;
        background: var(--light-green);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
    font-size: 2.5rem;
        color: var(--accent-green);
    transition: all 0.3s;
}
.contact-option-card:hover .option-icon {
    transform: scale(1.1) rotate(5deg);
    }
    .option-icon.whatsapp {
        background: #25D366;
        color: var(--white);
    }
    .contact-option-card h3 {
    font-size: 1.5rem;
        color: var(--primary-green);
        margin-bottom: 10px;
    font-weight: 700;
    }
    .contact-option-card p {
        color: var(--gray);
    margin-bottom: 20px;
        font-size: 0.95rem;
    }
    .option-details {
        display: flex;
        flex-direction: column;
    gap: 10px;
    }
    .contact-link {
        color: var(--accent-green);
    text-decoration: none;
        font-weight: 600;
        font-size: 1.1rem;
    display: block;
    transition: all 0.3s;
    }
    .contact-link:hover {
        color: var(--secondary-green);
    transform: translateX(5px);
    }
    .option-hours {
    font-size: 0.9rem;
        color: var(--gray);
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    margin-top: 5px;
    }
.office-location-section {
        padding: 100px 0;
    background: var(--gray-light);
    }
.location-wrapper {
        display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 50px;
    align-items: center;
}
.location-info {
    max-width: 100%;
}
.office-details {
    margin-top: 30px;
}
.office-detail-item {
    display: flex;
    gap: 20px;
    margin-bottom: 30px;
    padding: 20px;
    background: var(--white);
    border-radius: 12px;
    box-shadow: 0 3px 15px rgba(0,0,0,0.05);
}
.detail-icon {
        font-size: 2rem;
    color: var(--accent-green);
    min-width: 50px;
}
.detail-content h4 {
        color: var(--primary-green);
        margin-bottom: 8px;
    font-weight: 700;
    }
.detail-content p {
        color: var(--gray);
        line-height: 1.7;
        margin: 0;
    }
.location-map {
    height: 400px;
    background: var(--white);
    border-radius: 16px;
    overflow: hidden;
    box-shadow: 0 10px 30px rgba(0,0,0,0.1);
    position: relative;
}
.location-map iframe {
    width: 100%;
    height: 100%;
    border: none;
    border-radius: 16px;
}
.map-overlay {
    position: absolute;
    bottom: 20px;
    right: 20px;
    z-index: 10;
}
.map-link-btn {
        background: var(--accent-green);
        color: var(--white);
    padding: 12px 20px;
    border-radius: 8px;
    text-decoration: none;
    font-weight: 600;
    display: inline-flex;
    align-items: center;
    gap: 8px;
    transition: all 0.3s;
    box-shadow: 0 4px 15px rgba(0,0,0,0.2);
}
.map-link-btn:hover {
    background: var(--secondary-green);
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(0,0,0,0.3);
}
.content-section {
    padding: 100px 0;
    background: var(--white);
}
.contact-form-wrapper {
    max-width: 900px;
    margin: 0 auto;
}
.contact-form {
    background: var(--gray-light);
    padding: 50px;
    border-radius: 20px;
    box-shadow: 0 10px 40px rgba(0,0,0,0.08);
}
.alert {
    padding: 15px 20px;
    border-radius: 10px;
    margin-bottom: 25px;
        display: flex;
        align-items: center;
    gap: 12px;
    font-weight: 600;
}
.alert-success {
    background: #d4edda;
    color: #155724;
    border: 1px solid #c3e6cb;
}
.alert-error {
    background: #f8d7da;
    color: #721c24;
    border: 1px solid #f5c6cb;
    }
    .form-row {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 20px;
    margin-bottom: 20px;
    }
    .form-group {
    display: flex;
    flex-direction: column;
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
    .form-group input,
.form-group textarea,
.form-group select {
    padding: 14px 18px;
        border: 2px solid var(--gray-light);
    border-radius: 10px;
        font-size: 1rem;
    font-family: var(--font-primary);
        transition: all 0.3s;
    background: var(--white);
    }
    .form-group input:focus,
.form-group textarea:focus,
.form-group select:focus {
    outline: none;
        border-color: var(--accent-green);
    box-shadow: 0 0 0 3px rgba(61, 165, 114, 0.1);
}
.form-group textarea {
    resize: vertical;
}
.error-message {
    color: #e74c3c;
    font-size: 0.85rem;
    margin-top: 5px;
    display: block;
}
.checkbox-label {
        display: flex;
    align-items: center;
        gap: 10px;
        cursor: pointer;
    font-size: 0.95rem;
    color: var(--gray);
    }
.checkbox-label input[type="checkbox"] {
        width: auto;
    cursor: pointer;
}
.btn-primary {
    padding: 16px 40px;
    background: var(--accent-green);
    color: var(--white);
    border: none;
    border-radius: 50px;
        font-size: 1.1rem;
        font-weight: 600;
    cursor: pointer;
    display: inline-flex;
        align-items: center;
        gap: 10px;
    transition: all 0.3s;
        margin-top: 10px;
    }
.btn-primary:hover {
    background: var(--secondary-green);
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(61, 165, 114, 0.3);
}

/* Why Contact Us Section */
.why-contact-section {
        padding: 100px 0;
        background: var(--white);
    }
.why-contact-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 30px;
    margin-top: 50px;
}
.why-contact-item {
        text-align: center;
    padding: 30px;
    background: var(--gray-light);
    border-radius: 16px;
    transition: all 0.3s;
}
.why-contact-item:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 30px rgba(0,0,0,0.1);
}
.why-icon {
    width: 80px;
    height: 80px;
    margin: 0 auto 20px;
        background: var(--light-green);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 2.5rem;
    color: var(--accent-green);
    transition: all 0.3s;
}
.why-contact-item:hover .why-icon {
    transform: scale(1.1) rotate(5deg);
}
.why-contact-item h4 {
    font-size: 1.3rem;
        color: var(--primary-green);
        margin-bottom: 15px;
    font-weight: 700;
    }
.why-contact-item p {
        color: var(--gray);
        line-height: 1.7;
    font-size: 0.95rem;
}

/* Response Time Section */
.response-time-section {
    padding: 80px 0;
    background: linear-gradient(135deg, var(--primary-green) 0%, var(--accent-green) 100%);
    color: var(--white);
}
.response-time-wrapper {
        max-width: 900px;
        margin: 0 auto;
    }
.response-time-content {
    text-align: center;
}
.response-icon {
    width: 100px;
    height: 100px;
    margin: 0 auto 30px;
    background: rgba(255, 255, 255, 0.15);
    backdrop-filter: blur(10px);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 3rem;
    border: 3px solid rgba(255, 255, 255, 0.2);
}
.response-time-content h3 {
    font-size: 2.5rem;
    font-weight: 700;
    margin-bottom: 40px;
}
.response-times-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 25px;
    margin-top: 30px;
}
.response-time-item {
    background: rgba(255, 255, 255, 0.1);
    backdrop-filter: blur(10px);
    padding: 25px;
        border-radius: 12px;
    border: 2px solid rgba(255, 255, 255, 0.2);
}
.response-method {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 10px;
        margin-bottom: 15px;
    font-size: 1.1rem;
    font-weight: 600;
}
.response-method i {
    font-size: 1.5rem;
}
.response-duration {
    font-size: 1.3rem;
    font-weight: 700;
    color: var(--light-green);
}

/* Social Media Section */
.social-media-section {
    padding: 100px 0;
    background: var(--gray-light);
}
.social-media-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 30px;
    margin-top: 50px;
}
.social-media-card {
    background: var(--white);
    padding: 40px 30px;
    border-radius: 16px;
    text-align: center;
    text-decoration: none;
    transition: all 0.3s;
    box-shadow: 0 5px 25px rgba(0,0,0,0.08);
    display: block;
}
.social-media-card:hover {
    transform: translateY(-10px);
    box-shadow: 0 15px 40px rgba(0,0,0,0.15);
}
.social-icon {
    width: 80px;
    height: 80px;
    margin: 0 auto 20px;
    border-radius: 50%;
        display: flex;
        align-items: center;
    justify-content: center;
    font-size: 2.5rem;
    color: var(--white);
    transition: all 0.3s;
}
.social-icon.facebook {
    background: #1877F2;
}
.social-icon.instagram {
    background: linear-gradient(45deg, #f09433 0%, #e6683c 25%, #dc2743 50%, #cc2366 75%, #bc1888 100%);
}
.social-icon.twitter {
    background: #1DA1F2;
}
.social-icon.youtube {
    background: #FF0000;
}
.social-media-card:hover .social-icon {
    transform: scale(1.1) rotate(5deg);
}
.social-media-card h4 {
    font-size: 1.5rem;
    color: var(--primary-green);
    margin-bottom: 10px;
    font-weight: 700;
}
.social-media-card p {
    color: var(--gray);
    margin-bottom: 20px;
    font-size: 0.95rem;
}
.social-link {
    color: var(--accent-green);
        font-weight: 600;
    display: inline-flex;
    align-items: center;
    gap: 8px;
    transition: all 0.3s;
}
.social-media-card:hover .social-link {
    gap: 12px;
    color: var(--secondary-green);
}

/* Additional Info Section */
.additional-info-section {
    padding: 100px 0;
    background: var(--white);
}
.info-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(400px, 1fr));
    gap: 40px;
    margin-top: 50px;
}
.info-card {
    background: var(--gray-light);
    padding: 40px;
    border-radius: 20px;
    box-shadow: 0 5px 25px rgba(0,0,0,0.08);
    transition: all 0.3s;
}
.info-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 30px rgba(0,0,0,0.12);
}
.info-icon {
    width: 70px;
    height: 70px;
    background: var(--light-green);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 2rem;
        color: var(--accent-green);
    margin-bottom: 25px;
}
.info-card h3 {
    font-size: 1.8rem;
    color: var(--primary-green);
    margin-bottom: 15px;
    font-weight: 700;
}
.info-card p {
    color: var(--gray);
    line-height: 1.7;
    margin-bottom: 25px;
    font-size: 1rem;
}
.info-list {
    list-style: none;
    padding: 0;
    margin: 0;
}
.info-list li {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 12px 0;
        color: var(--gray);
    font-size: 0.95rem;
    }
.info-list li i {
    color: var(--accent-green);
    font-size: 1rem;
    }

    @media (max-width: 992px) {
    .location-wrapper {
            grid-template-columns: 1fr;
        }
}
@media (max-width: 768px) {
    .page-hero-title {
        font-size: 2.5rem;
        }
        .form-row {
            grid-template-columns: 1fr;
        }
    .contact-form {
        padding: 30px 20px;
        }
        .contact-options-grid,
    .why-contact-grid,
    .social-media-grid {
            grid-template-columns: 1fr;
        }
    .response-times-grid {
        grid-template-columns: repeat(2, 1fr);
    }
    .info-grid {
        grid-template-columns: 1fr;
    }
    .response-time-content h3 {
        font-size: 2rem;
        }
    }
</style>
@endpush
