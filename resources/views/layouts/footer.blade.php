<footer class="footer-main">
    <div class="newsletter-section">
        <div class="container">
            <div class="newsletter-content">
                <div class="newsletter-text">
                    <h3>Get Tanzania Travel Tips & Offers</h3>
                    <p>Subscribe to our newsletter for exclusive deals and Tanzania travel inspiration.</p>
                </div>
                <form class="newsletter-form">
                    <div class="form-group">
                        <i class="fas fa-envelope"></i>
                        <input type="email" placeholder="Your email address" required>
                        <button type="submit" class="subscribe-btn">Subscribe</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <div class="footer-content">
        <div class="container">
            <div class="footer-grid">
                <div>
                    <a href="{{ route('home') }}" class="footer-logo">
                        <img src="{{ asset('lau-adventuress.png') }}" alt="Lau Paradise Adventures" class="footer-logo-img">
                    </a>
                    <p class="company-description">Tanzania's premier tour operator offering authentic safaris, Kilimanjaro climbs, and beach holidays since 2008.</p>
                    <div class="social-links">
                        <a href="#"><i class="fab fa-facebook-f"></i></a>
                        <a href="#"><i class="fab fa-instagram"></i></a>
                        <a href="#"><i class="fab fa-twitter"></i></a>
                        <a href="#"><i class="fab fa-youtube"></i></a>
                    </div>
                </div>
                
                <div>
                    <h4 class="footer-title">Quick Links</h4>
                    <ul class="footer-links">
                        <li><a href="{{ route('home') }}">Home</a></li>
                        <li><a href="{{ route('tours.index') }}">Tours</a></li>
                        <li><a href="{{ route('destinations.index') }}">Destinations</a></li>
                        <li><a href="{{ route('about') }}">About Us</a></li>
                        <li><a href="{{ route('contact') }}">Contact</a></li>
                    </ul>
                </div>
                
                <div>
                    <h4 class="footer-title">Tours</h4>
                    <ul class="footer-links">
                        <li><a href="{{ route('tours.category', 'safari') }}">Safari Tours</a></li>
                        <li><a href="{{ route('tours.category', 'hiking') }}">Kilimanjaro Climbing</a></li>
                        <li><a href="{{ route('tours.category', 'beach') }}">Zanzibar Beach Holidays</a></li>
                        <li><a href="{{ route('tours.category', 'cultural') }}">Cultural Tours</a></li>
                        <li><a href="{{ route('tours.last-minute') }}">Family Safaris</a></li>
                    </ul>
                </div>
                
                <div>
                    <h4 class="footer-title">Contact Us</h4>
                    <ul class="footer-contact">
                        <li>
                            <i class="fas fa-map-marker-alt"></i>
                            <div>
                                <strong>Arusha Office</strong>
                                <span>Sekei Area, Arusha, Tanzania</span>
                            </div>
                        </li>
                        <li>
                            <i class="fas fa-phone-alt"></i>
                            <div>
                                <strong>Phone</strong>
                                <a href="tel:+255683163219">+255 683 163 219</a>
                            </div>
                        </li>
                        <li>
                            <i class="fas fa-envelope"></i>
                            <div>
                                <strong>Email</strong>
                                <a href="mailto:lauparadiseadventure@gmail.com">lauparadiseadventure@gmail.com</a>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    
    <div class="footer-bottom">
        <div class="container">
            <div class="footer-bottom-content">
                <p>&copy; {{ date('Y') }} Lau Paradise Adventures. All rights reserved.</p>
                <p class="footer-credit">Designed and developed by Ngungila D</p>
                <div class="footer-legal">
                    <a href="{{ route('privacy') }}">Privacy Policy</a>
                    <a href="{{ route('terms') }}">Terms & Conditions</a>
                </div>
            </div>
        </div>
    </div>
</footer>
