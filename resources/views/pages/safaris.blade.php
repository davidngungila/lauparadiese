@extends('layouts.app')

@section('title', 'Tanzania Safari Tours - Lau Paradise Adventures')
@section('description', 'Experience the ultimate Tanzania safari adventure. Witness the Big Five, Great Migration, and Africa\'s most iconic wildlife in Serengeti, Ngorongoro, Tarangire, and more.')

@section('content')

<!-- Hero Section -->
<section class="page-hero-section" style="background-image: url('{{ asset('images/safari_home-1.jpg') }}');">
    <div class="page-hero-overlay"></div>
    <div class="container">
        <div class="page-hero-content" data-aos="fade-up">
            <span class="hero-badge"><i class="fas fa-binoculars"></i> Tanzania Safaris</span>
            <h1 class="page-hero-title">Experience the Ultimate Tanzania Safari</h1>
            <p class="page-hero-subtitle">Witness the Big Five, Great Migration, and Africa's most iconic wildlife in the world's greatest national parks</p>
        </div>
    </div>
</section>

<!-- What is a Safari Section -->
<section class="content-section">
    <div class="container">
        <div class="section-header" data-aos="fade-up">
            <span class="section-badge">Discover Tanzania Safaris</span>
            <h2 class="section-title">What is a Tanzania Safari?</h2>
            <p class="section-subtitle">A safari in Tanzania is an unforgettable journey into the heart of Africa's most spectacular wilderness areas, where you'll encounter incredible wildlife, breathtaking landscapes, and authentic cultural experiences.</p>
        </div>
        
        <div class="safari-intro-grid">
            <div class="safari-intro-card" data-aos="fade-up">
                <div class="intro-icon"><i class="fas fa-camera"></i></div>
                <h3>Game Drives</h3>
                <p>Explore national parks in comfortable 4x4 vehicles with pop-up roofs, guided by expert local drivers who know where to find the wildlife.</p>
            </div>
            <div class="safari-intro-card" data-aos="fade-up" data-aos-delay="100">
                <div class="intro-icon"><i class="fas fa-paw"></i></div>
                <h3>Big Five & More</h3>
                <p>Spot lions, elephants, leopards, rhinos, and buffalo, plus cheetahs, giraffes, zebras, wildebeest, and hundreds of bird species.</p>
            </div>
            <div class="safari-intro-card" data-aos="fade-up" data-aos-delay="200">
                <div class="intro-icon"><i class="fas fa-mountain"></i></div>
                <h3>Stunning Landscapes</h3>
                <p>From endless savannah plains to volcanic craters, baobab forests, and acacia woodlands - Tanzania's scenery is breathtaking.</p>
            </div>
            <div class="safari-intro-card" data-aos="fade-up" data-aos-delay="300">
                <div class="intro-icon"><i class="fas fa-users"></i></div>
                <h3>Cultural Experiences</h3>
                <p>Visit Maasai villages, learn about local traditions, and experience authentic Tanzanian culture alongside your wildlife adventure.</p>
            </div>
        </div>
    </div>
</section>

<!-- Types of Safaris Section -->
<section class="safari-types-section">
    <div class="container">
        <div class="section-header" data-aos="fade-up">
            <span class="section-badge">Choose Your Adventure</span>
            <h2 class="section-title">Types of Tanzania Safaris</h2>
            <p class="section-subtitle">From short 3-day adventures to extended 10+ day journeys, we offer safaris to suit every traveler's needs and budget.</p>
        </div>
        
        <div class="safari-types-grid">
            <div class="safari-type-card" data-aos="fade-up">
                <div class="type-image">
                    <img src="{{ asset('images/safari_home-1.jpg') }}" alt="Northern Circuit Safari">
                    <div class="type-badge">Most Popular</div>
                </div>
                <div class="type-content">
                    <h3>Northern Circuit Safaris</h3>
                    <p>The classic Tanzania safari route featuring Serengeti, Ngorongoro Crater, Tarangire, and Lake Manyara. Perfect for first-time visitors and wildlife enthusiasts.</p>
                    <ul class="type-features">
                        <li><i class="fas fa-check"></i> Serengeti National Park</li>
                        <li><i class="fas fa-check"></i> Ngorongoro Crater</li>
                        <li><i class="fas fa-check"></i> Tarangire National Park</li>
                        <li><i class="fas fa-check"></i> Lake Manyara</li>
                    </ul>
                    <div class="type-duration">
                        <i class="fas fa-calendar"></i> 5-10 Days
                    </div>
                </div>
            </div>
            
            <div class="safari-type-card" data-aos="fade-up" data-aos-delay="100">
                <div class="type-image">
                    <img src="{{ asset('images/Mara-River-3-1536x1024.jpg') }}" alt="Great Migration Safari">
                    <div class="type-badge">Seasonal</div>
                </div>
                <div class="type-content">
                    <h3>Great Migration Safaris</h3>
                    <p>Witness one of nature's greatest spectacles as millions of wildebeest and zebras migrate across the Serengeti in search of fresh grazing.</p>
                    <ul class="type-features">
                        <li><i class="fas fa-check"></i> Wildebeest Migration</li>
                        <li><i class="fas fa-check"></i> River Crossings</li>
                        <li><i class="fas fa-check"></i> Predator Action</li>
                        <li><i class="fas fa-check"></i> Calving Season</li>
                    </ul>
                    <div class="type-duration">
                        <i class="fas fa-calendar"></i> 7-9 Days
                    </div>
                </div>
            </div>
            
            <div class="safari-type-card" data-aos="fade-up" data-aos-delay="200">
                <div class="type-image">
                    <img src="{{ asset('images/DSC05518-1536x1024.jpg') }}" alt="Southern Circuit Safari">
                    <div class="type-badge">Off the Beaten Path</div>
                </div>
                <div class="type-content">
                    <h3>Southern Circuit Safaris</h3>
                    <p>Explore Tanzania's less-visited but equally spectacular southern parks including Selous, Ruaha, and Mikumi for a more exclusive safari experience.</p>
                    <ul class="type-features">
                        <li><i class="fas fa-check"></i> Selous Game Reserve</li>
                        <li><i class="fas fa-check"></i> Ruaha National Park</li>
                        <li><i class="fas fa-check"></i> Boat Safaris</li>
                        <li><i class="fas fa-check"></i> Walking Safaris</li>
                    </ul>
                    <div class="type-duration">
                        <i class="fas fa-calendar"></i> 8-12 Days
                    </div>
                </div>
            </div>
            
            <div class="safari-type-card" data-aos="fade-up" data-aos-delay="300">
                <div class="type-image">
                    <img src="{{ asset('images/11-Days-Safari-trip-Tanzania-Zanzibar-1536x1024.jpg') }}" alt="Safari & Beach">
                    <div class="type-badge">Best Value</div>
                </div>
                <div class="type-content">
                    <h3>Safari & Beach Combinations</h3>
                    <p>Combine your wildlife adventure with a relaxing beach holiday in Zanzibar. Experience the best of both worlds in one unforgettable trip.</p>
                    <ul class="type-features">
                        <li><i class="fas fa-check"></i> Safari Adventure</li>
                        <li><i class="fas fa-check"></i> Zanzibar Beaches</li>
                        <li><i class="fas fa-check"></i> Stone Town</li>
                        <li><i class="fas fa-check"></i> Spice Tours</li>
                    </ul>
                    <div class="type-duration">
                        <i class="fas fa-calendar"></i> 10-15 Days
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- National Parks Section -->
<section class="parks-section">
    <div class="container">
        <div class="section-header" data-aos="fade-up">
            <span class="section-badge">Tanzania's Crown Jewels</span>
            <h2 class="section-title">Iconic National Parks & Reserves</h2>
            <p class="section-subtitle">Tanzania is home to some of Africa's most famous and diverse protected areas, each offering unique wildlife viewing opportunities.</p>
        </div>
        
        <div class="parks-grid">
            <div class="park-card" data-aos="fade-up">
                <div class="park-image">
                    <img src="{{ asset('images/Serengetei-NP-2.jpeg') }}" alt="Serengeti National Park">
                </div>
                <div class="park-content">
                    <h3>Serengeti National Park</h3>
                    <p class="park-location"><i class="fas fa-map-marker-alt"></i> Northern Tanzania</p>
                    <p class="park-description">Home to the Great Migration and the Big Five. Endless plains, dramatic sunsets, and the highest concentration of large mammals on Earth.</p>
                    <div class="park-highlights">
                        <span class="highlight-tag">Great Migration</span>
                        <span class="highlight-tag">Big Five</span>
                        <span class="highlight-tag">14,750 km²</span>
                    </div>
                </div>
            </div>
            
            <div class="park-card" data-aos="fade-up" data-aos-delay="100">
                <div class="park-image">
                    <img src="{{ asset('images/safari_home-1.jpg') }}" alt="Ngorongoro Crater">
                </div>
                <div class="park-content">
                    <h3>Ngorongoro Crater</h3>
                    <p class="park-location"><i class="fas fa-map-marker-alt"></i> Northern Tanzania</p>
                    <p class="park-description">The world's largest intact volcanic caldera, home to 25,000 large animals including the highest density of lions in Africa.</p>
                    <div class="park-highlights">
                        <span class="highlight-tag">World Heritage Site</span>
                        <span class="highlight-tag">Big Five</span>
                        <span class="highlight-tag">260 km²</span>
                    </div>
                </div>
            </div>
            
            <div class="park-card" data-aos="fade-up" data-aos-delay="200">
                <div class="park-image">
                    <img src="{{ asset('images/Tarangire-NP-1.jpeg') }}" alt="Tarangire National Park">
                </div>
                <div class="park-content">
                    <h3>Tarangire National Park</h3>
                    <p class="park-location"><i class="fas fa-map-marker-alt"></i> Northern Tanzania</p>
                    <p class="park-description">Famous for its massive elephant herds, ancient baobab trees, and diverse birdlife. Less crowded than Serengeti.</p>
                    <div class="park-highlights">
                        <span class="highlight-tag">Elephant Paradise</span>
                        <span class="highlight-tag">Baobab Trees</span>
                        <span class="highlight-tag">2,850 km²</span>
                    </div>
                </div>
            </div>
            
            <div class="park-card" data-aos="fade-up" data-aos-delay="300">
                <div class="park-image">
                    <img src="{{ asset('images/safari_home-1.jpg') }}" alt="Lake Manyara">
                </div>
                <div class="park-content">
                    <h3>Lake Manyara National Park</h3>
                    <p class="park-location"><i class="fas fa-map-marker-alt"></i> Northern Tanzania</p>
                    <p class="park-description">Compact park known for tree-climbing lions, large flocks of flamingos, and diverse ecosystems from forest to alkaline lake.</p>
                    <div class="park-highlights">
                        <span class="highlight-tag">Tree-Climbing Lions</span>
                        <span class="highlight-tag">Flamingos</span>
                        <span class="highlight-tag">330 km²</span>
                    </div>
                </div>
            </div>
            
            <div class="park-card" data-aos="fade-up" data-aos-delay="400">
                <div class="park-image">
                    <img src="{{ asset('images/DSC05518-1536x1024.jpg') }}" alt="Selous Game Reserve">
                </div>
                <div class="park-content">
                    <h3>Selous Game Reserve</h3>
                    <p class="park-location"><i class="fas fa-map-marker-alt"></i> Southern Tanzania</p>
                    <p class="park-description">Africa's largest game reserve, offering boat safaris, walking safaris, and excellent wild dog viewing opportunities.</p>
                    <div class="park-highlights">
                        <span class="highlight-tag">Boat Safaris</span>
                        <span class="highlight-tag">Wild Dogs</span>
                        <span class="highlight-tag">50,000 km²</span>
                    </div>
                </div>
            </div>
            
            <div class="park-card" data-aos="fade-up" data-aos-delay="500">
                <div class="park-image">
                    <img src="{{ asset('images/safari_home-1.jpg') }}" alt="Ruaha National Park">
                </div>
                <div class="park-content">
                    <h3>Ruaha National Park</h3>
                    <p class="park-location"><i class="fas fa-map-marker-alt"></i> Southern Tanzania</p>
                    <p class="park-description">Tanzania's largest national park, home to 10% of Africa's lions and excellent elephant viewing in a remote wilderness setting.</p>
                    <div class="park-highlights">
                        <span class="highlight-tag">Lion Capital</span>
                        <span class="highlight-tag">Elephants</span>
                        <span class="highlight-tag">20,226 km²</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Safari Packages Section -->
@if($safariTours && $safariTours->count() > 0)
<section class="safari-packages-section">
    <div class="container">
        <div class="section-header" data-aos="fade-up">
            <span class="section-badge">Our Safari Packages</span>
            <h2 class="section-title">Featured Safari Tours</h2>
            <p class="section-subtitle">Choose from our carefully crafted safari packages, designed to showcase the best of Tanzania's wildlife and landscapes.</p>
        </div>
        
        <div class="tours-grid">
            @foreach($safariTours as $tour)
            <div class="tour-card" data-aos="fade-up">
                <div class="tour-card-image">
                    <img src="{{ $tour['image'] }}" alt="{{ $tour['name'] }}">
                    @if($tour['is_featured'])
                    <div class="tour-badge">Featured</div>
                    @endif
                    <div class="tour-rating">
                        <i class="fas fa-star"></i> {{ number_format($tour['rating'], 1) }}
                    </div>
                </div>
                <div class="tour-card-content">
                    <div class="tour-meta">
                        <span><i class="fas fa-clock"></i> {{ $tour['duration_days'] }} Days</span>
                        <span><i class="fas fa-map-marker-alt"></i> {{ $tour['destination'] }}</span>
                    </div>
                    <h3 class="tour-name">{{ $tour['name'] }}</h3>
                    <p class="tour-description">{{ $tour['description'] }}</p>
                    <div class="tour-footer">
                        <div class="tour-price">
                            <span class="price-from">From</span>
                            <span class="price-amount">${{ number_format($tour['starting_price'], 0) }}</span>
                            <span class="price-person">per person</span>
                        </div>
                        <a href="{{ route('tours.show', $tour['slug']) }}" class="tour-btn">
                            View Details <i class="fas fa-arrow-right"></i>
                        </a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        
        <div class="section-footer" data-aos="fade-up">
            <a href="{{ route('tours.index') }}" class="btn-primary">
                View All Safari Tours <i class="fas fa-arrow-right"></i>
            </a>
        </div>
    </div>
</section>
@endif

<!-- Best Time to Visit Section -->
<section class="best-time-section">
    <div class="container">
        <div class="section-header" data-aos="fade-up">
            <span class="section-badge">Planning Your Safari</span>
            <h2 class="section-title">Best Time to Visit Tanzania for Safaris</h2>
            <p class="section-subtitle">Tanzania offers excellent wildlife viewing year-round, but different seasons offer different experiences.</p>
        </div>
        
        <div class="seasons-grid">
            <div class="season-card" data-aos="fade-up">
                <div class="season-header">
                    <h3>Dry Season</h3>
                    <span class="season-months">June - October</span>
                </div>
                <div class="season-content">
                    <p><strong>Best for:</strong> General wildlife viewing, clear skies, comfortable temperatures</p>
                    <ul>
                        <li><i class="fas fa-check"></i> Animals gather around water sources</li>
                        <li><i class="fas fa-check"></i> Excellent visibility</li>
                        <li><i class="fas fa-check"></i> Great Migration in northern Serengeti</li>
                        <li><i class="fas fa-check"></i> Peak season - book early</li>
                    </ul>
                </div>
            </div>
            
            <div class="season-card" data-aos="fade-up" data-aos-delay="100">
                <div class="season-header">
                    <h3>Calving Season</h3>
                    <span class="season-months">January - March</span>
                </div>
                <div class="season-content">
                    <p><strong>Best for:</strong> Great Migration calving, predator action, fewer crowds</p>
                    <ul>
                        <li><i class="fas fa-check"></i> Witness thousands of wildebeest births</li>
                        <li><i class="fas fa-check"></i> Intense predator activity</li>
                        <li><i class="fas fa-check"></i> Lower prices</li>
                        <li><i class="fas fa-check"></i> Some afternoon rains</li>
                    </ul>
                </div>
            </div>
            
            <div class="season-card" data-aos="fade-up" data-aos-delay="200">
                <div class="season-header">
                    <h3>Green Season</h3>
                    <span class="season-months">November - May</span>
                </div>
                <div class="season-content">
                    <p><strong>Best for:</strong> Bird watching, lush landscapes, lower prices</p>
                    <ul>
                        <li><i class="fas fa-check"></i> Migratory birds arrive</li>
                        <li><i class="fas fa-check"></i> Beautiful green scenery</li>
                        <li><i class="fas fa-check"></i> Best value for money</li>
                        <li><i class="fas fa-check"></i> Afternoon showers possible</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Why Choose Us Section -->
<section class="why-choose-section">
    <div class="container">
        <div class="section-header" data-aos="fade-up">
            <span class="section-badge">Why Choose Lau Paradise</span>
            <h2 class="section-title">Why Choose Us for Your Tanzania Safari?</h2>
            <p class="section-subtitle">We're Tanzania-based safari experts with years of experience creating unforgettable wildlife adventures.</p>
        </div>
        
        <div class="features-grid">
            <div class="feature-card" data-aos="fade-up">
                <div class="feature-icon"><i class="fas fa-user-tie"></i></div>
                <h3>Expert Local Guides</h3>
                <p>Our guides are born and raised in Tanzania with extensive knowledge of wildlife behavior, migration patterns, and the best viewing spots.</p>
            </div>
            
            <div class="feature-card" data-aos="fade-up" data-aos-delay="100">
                <div class="feature-icon"><i class="fas fa-car"></i></div>
                <h3>Quality Safari Vehicles</h3>
                <p>Modern 4x4 Land Cruisers with pop-up roofs, comfortable seating, and all safety equipment for the best game viewing experience.</p>
            </div>
            
            <div class="feature-card" data-aos="fade-up" data-aos-delay="200">
                <div class="feature-icon"><i class="fas fa-hotel"></i></div>
                <h3>Carefully Selected Accommodations</h3>
                <p>From luxury lodges to authentic tented camps, we choose accommodations that enhance your safari experience while respecting the environment.</p>
            </div>
            
            <div class="feature-card" data-aos="fade-up" data-aos-delay="300">
                <div class="feature-icon"><i class="fas fa-shield-alt"></i></div>
                <h3>Safety First</h3>
                <p>Your safety is our priority. All our vehicles are regularly maintained, guides are trained in first aid, and we follow all park regulations.</p>
            </div>
            
            <div class="feature-card" data-aos="fade-up" data-aos-delay="400">
                <div class="feature-icon"><i class="fas fa-dollar-sign"></i></div>
                <h3>Best Value</h3>
                <p>We offer competitive prices without compromising on quality. Direct relationships with lodges and parks mean better value for you.</p>
            </div>
            
            <div class="feature-card" data-aos="fade-up" data-aos-delay="500">
                <div class="feature-icon"><i class="fas fa-heart"></i></div>
                <h3>24/7 Support</h3>
                <p>Our team is available around the clock to assist you before, during, and after your safari. We're here to make your trip perfect.</p>
            </div>
        </div>
    </div>
</section>

<!-- CTA Section -->
<section class="cta-section">
    <div class="container">
        <div class="cta-content" data-aos="fade-up">
            <h2>Ready to Start Your Tanzania Safari Adventure?</h2>
            <p>Let our experts help you plan the perfect safari experience. Contact us today to discuss your dream Tanzania safari.</p>
            <div class="cta-buttons">
                <a href="{{ route('booking') }}" class="btn-primary">
                    <i class="fas fa-calendar-alt"></i> Book Your Safari
                </a>
                <a href="{{ route('contact') }}" class="btn-secondary">
                    <i class="fas fa-envelope"></i> Get a Quote
                </a>
            </div>
        </div>
    </div>
</section>

@endsection

@push('styles')
<style>
.safari-intro-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 30px;
    margin-top: 50px;
}

.safari-intro-card {
    text-align: center;
    padding: 30px;
    background: var(--gray-light);
    border-radius: 15px;
    transition: all 0.3s;
}

.safari-intro-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 30px rgba(0,0,0,0.1);
}

.intro-icon {
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
}

.safari-intro-card h3 {
    color: var(--primary-green);
    margin-bottom: 15px;
    font-size: 1.3rem;
}

.safari-types-section {
    padding: 100px 0;
    background: var(--gray-light);
}

.safari-types-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 30px;
    margin-top: 50px;
}

.safari-type-card {
    background: var(--white);
    border-radius: 20px;
    overflow: hidden;
    box-shadow: 0 5px 25px rgba(0,0,0,0.08);
    transition: all 0.3s;
}

.safari-type-card:hover {
    transform: translateY(-10px);
    box-shadow: 0 15px 40px rgba(0,0,0,0.15);
}

.type-image {
    position: relative;
    height: 200px;
    overflow: hidden;
}

.type-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.5s;
}

.safari-type-card:hover .type-image img {
    transform: scale(1.1);
}

.type-badge {
    position: absolute;
    top: 15px;
    right: 15px;
    background: var(--accent-green);
    color: var(--white);
    padding: 5px 15px;
    border-radius: 20px;
    font-size: 0.85rem;
    font-weight: 600;
}

.type-content {
    padding: 25px;
}

.type-content h3 {
    color: var(--primary-green);
    margin-bottom: 15px;
    font-size: 1.4rem;
}

.type-features {
    list-style: none;
    padding: 0;
    margin: 20px 0;
}

.type-features li {
    padding: 8px 0;
    color: var(--gray);
    display: flex;
    align-items: center;
    gap: 10px;
}

.type-features li i {
    color: var(--accent-green);
}

.type-duration {
    margin-top: 20px;
    padding-top: 20px;
    border-top: 2px solid var(--gray-light);
    color: var(--primary-green);
    font-weight: 600;
    display: flex;
    align-items: center;
    gap: 10px;
}

.parks-section {
    padding: 100px 0;
    background: var(--white);
}

.parks-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
    gap: 30px;
    margin-top: 50px;
}

.park-card {
    background: var(--white);
    border-radius: 15px;
    overflow: hidden;
    box-shadow: 0 5px 25px rgba(0,0,0,0.08);
    transition: all 0.3s;
}

.park-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 15px 40px rgba(0,0,0,0.15);
}

.park-image {
    height: 200px;
    overflow: hidden;
}

.park-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.5s;
}

.park-card:hover .park-image img {
    transform: scale(1.1);
}

.park-content {
    padding: 25px;
}

.park-content h3 {
    color: var(--primary-green);
    margin-bottom: 10px;
    font-size: 1.4rem;
}

.park-location {
    color: var(--accent-green);
    margin-bottom: 15px;
    display: flex;
    align-items: center;
    gap: 8px;
    font-weight: 600;
}

.park-description {
    color: var(--gray);
    line-height: 1.7;
    margin-bottom: 20px;
}

.park-highlights {
    display: flex;
    flex-wrap: wrap;
    gap: 10px;
}

.highlight-tag {
    background: var(--light-green);
    color: var(--primary-green);
    padding: 5px 15px;
    border-radius: 20px;
    font-size: 0.85rem;
    font-weight: 600;
}

.safari-packages-section {
    padding: 100px 0;
    background: var(--gray-light);
}

.tours-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
    gap: 30px;
    margin-top: 50px;
}

.tour-card {
    background: var(--white);
    border-radius: 20px;
    overflow: hidden;
    box-shadow: 0 5px 25px rgba(0,0,0,0.08);
    transition: all 0.3s;
}

.tour-card:hover {
    transform: translateY(-10px);
    box-shadow: 0 15px 40px rgba(0,0,0,0.15);
}

.tour-card-image {
    position: relative;
    height: 220px;
    overflow: hidden;
}

.tour-card-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.5s;
}

.tour-card:hover .tour-card-image img {
    transform: scale(1.1);
}

.tour-badge {
    position: absolute;
    top: 15px;
    left: 15px;
    background: var(--accent-green);
    color: var(--white);
    padding: 5px 15px;
    border-radius: 20px;
    font-size: 0.85rem;
    font-weight: 600;
}

.tour-rating {
    position: absolute;
    bottom: 15px;
    right: 15px;
    background: rgba(255,255,255,0.95);
    padding: 5px 12px;
    border-radius: 20px;
    font-weight: 600;
    color: var(--primary-green);
    display: flex;
    align-items: center;
    gap: 5px;
}

.tour-rating i {
    color: #ffc107;
}

.tour-card-content {
    padding: 25px;
}

.tour-meta {
    display: flex;
    gap: 15px;
    margin-bottom: 15px;
    font-size: 0.9rem;
    color: var(--gray);
}

.tour-name {
    color: var(--primary-green);
    margin-bottom: 15px;
    font-size: 1.3rem;
    font-weight: 700;
}

.tour-description {
    color: var(--gray);
    line-height: 1.7;
    margin-bottom: 20px;
}

.tour-footer {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding-top: 20px;
    border-top: 2px solid var(--gray-light);
}

.tour-price {
    display: flex;
    flex-direction: column;
}

.price-from {
    font-size: 0.85rem;
    color: var(--gray);
}

.price-amount {
    font-size: 1.5rem;
    font-weight: 700;
    color: var(--accent-green);
}

.price-person {
    font-size: 0.85rem;
    color: var(--gray);
}

.tour-btn {
    background: var(--accent-green);
    color: var(--white);
    padding: 12px 25px;
    border-radius: 50px;
    text-decoration: none;
    font-weight: 600;
    display: flex;
    align-items: center;
    gap: 8px;
    transition: all 0.3s;
}

.tour-btn:hover {
    background: var(--secondary-green);
    transform: translateX(5px);
}

.best-time-section {
    padding: 100px 0;
    background: var(--white);
}

.seasons-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 30px;
    margin-top: 50px;
}

.season-card {
    background: var(--gray-light);
    border-radius: 15px;
    padding: 30px;
    border-left: 4px solid var(--accent-green);
}

.season-header {
    margin-bottom: 20px;
    padding-bottom: 15px;
    border-bottom: 2px solid var(--white);
}

.season-header h3 {
    color: var(--primary-green);
    margin-bottom: 5px;
    font-size: 1.3rem;
}

.season-months {
    color: var(--accent-green);
    font-weight: 600;
}

.season-content p {
    margin-bottom: 15px;
    color: var(--gray);
    line-height: 1.7;
}

.season-content ul {
    list-style: none;
    padding: 0;
}

.season-content ul li {
    padding: 8px 0;
    color: var(--gray);
    display: flex;
    align-items: center;
    gap: 10px;
}

.season-content ul li i {
    color: var(--accent-green);
}

.why-choose-section {
    padding: 100px 0;
    background: var(--gray-light);
}

.features-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 30px;
    margin-top: 50px;
}

.feature-card {
    background: var(--white);
    padding: 30px;
    border-radius: 15px;
    text-align: center;
    transition: all 0.3s;
}

.feature-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 30px rgba(0,0,0,0.1);
}

.feature-icon {
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
}

.feature-card h3 {
    color: var(--primary-green);
    margin-bottom: 15px;
    font-size: 1.2rem;
}

.feature-card p {
    color: var(--gray);
    line-height: 1.7;
}

.cta-section {
    padding: 100px 0;
    background: linear-gradient(135deg, var(--primary-green) 0%, var(--accent-green) 100%);
    color: var(--white);
    text-align: center;
}

.cta-content h2 {
    font-size: 2.5rem;
    margin-bottom: 20px;
    font-weight: 800;
}

.cta-content p {
    font-size: 1.2rem;
    margin-bottom: 40px;
    opacity: 0.95;
}

.cta-buttons {
    display: flex;
    gap: 20px;
    justify-content: center;
    flex-wrap: wrap;
}

.btn-primary {
    background: var(--white);
    color: var(--primary-green);
    padding: 18px 40px;
    border-radius: 50px;
    text-decoration: none;
    font-weight: 700;
    display: inline-flex;
    align-items: center;
    gap: 10px;
    transition: all 0.3s;
}

.btn-primary:hover {
    background: var(--light-green);
    transform: translateY(-2px);
    box-shadow: 0 10px 30px rgba(0,0,0,0.3);
}

.btn-secondary {
    background: transparent;
    color: var(--white);
    padding: 18px 40px;
    border-radius: 50px;
    text-decoration: none;
    font-weight: 700;
    display: inline-flex;
    align-items: center;
    gap: 10px;
    border: 2px solid var(--white);
    transition: all 0.3s;
}

.btn-secondary:hover {
    background: var(--white);
    color: var(--primary-green);
    transform: translateY(-2px);
}

@media (max-width: 768px) {
    .safari-types-grid,
    .parks-grid,
    .tours-grid,
    .seasons-grid,
    .features-grid {
        grid-template-columns: 1fr;
    }
    
    .cta-content h2 {
        font-size: 2rem;
    }
    
    .cta-buttons {
        flex-direction: column;
        align-items: stretch;
    }
    
    .btn-primary,
    .btn-secondary {
        width: 100%;
        justify-content: center;
    }
}
</style>
@endpush

