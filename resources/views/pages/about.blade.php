@extends('layouts.app')

@section('title', 'About Us - Lau Paradise Adventures')
@section('description', 'Learn about Lau Paradise Adventures, a Tanzania-based tour company dedicated to providing authentic, sustainable, and unforgettable Tanzania travel experiences.')

@section('content')

<!-- Hero Section -->
@php
    $heroSection = $sections->get('hero') ?? null;
    $heroImage = $heroSection && $heroSection->image_url ? (str_starts_with($heroSection->image_url, 'http') ? $heroSection->image_url : asset($heroSection->image_url)) : asset('images/safari_home-1.jpg');
    $heroData = $heroSection ? ($heroSection->data ?? []) : [];
@endphp
<section class="page-hero-section" style="background-image: url('{{ $heroImage }}');">
    <div class="page-hero-overlay"></div>
        <div class="container">
        <div class="page-hero-content" data-aos="fade-up">
            <span class="hero-badge"><i class="fas fa-heart"></i> {{ $heroData['badge_text'] ?? 'Our Story' }}</span>
            <h1 class="page-hero-title">{{ $heroData['title'] ?? 'About Lau Paradise Adventures' }}</h1>
            <p class="page-hero-subtitle">{{ $heroData['subtitle'] ?? "Tanzania's premier tour operator offering authentic safaris, Kilimanjaro climbs, and beach holidays since 2025." }}</p>
        </div>
        </div>
    </section>

<!-- Our Story Section -->
@php
    $storySection = $sections->get('story') ?? null;
    $storyData = $storySection ? ($storySection->data ?? []) : [];
    $storyImage = $storySection && $storySection->image_url ? (str_starts_with($storySection->image_url, 'http') ? $storySection->image_url : asset($storySection->image_url)) : asset('images/safari_home-1.jpg');
    $foundedYear = $storyData['founded_year'] ?? '2025';
@endphp
<section class="content-section">
        <div class="container">
        <div class="story-wrapper">
                <div class="story-image" data-aos="fade-right">
                <img src="{{ $storyImage }}" alt="Our Story">
                <div class="story-image-badge">
                    <i class="fas fa-calendar-alt"></i>
                    <span>Since {{ $foundedYear }}</span>
                </div>
            </div>
            <div class="story-content" data-aos="fade-left">
                <span class="section-badge">{{ $storyData['badge'] ?? 'Our Story' }}</span>
                    <h2 class="section-title">{{ $storyData['title'] ?? 'Born in Tanzania, Dedicated to Tanzania' }}</h2>
                    @if($storySection && $storySection->content)
                        {!! nl2br(e($storySection->content)) !!}
                    @else
                        <p>Lau Paradise Adventures was founded with a simple mission: to share the incredible beauty and wonder of Tanzania with the world. Born from a deep love for our homeland, we started as a small local company in Arusha, Tanzania, with a passion for showcasing the best of what Tanzania has to offer—from the iconic Serengeti and Kilimanjaro to the pristine beaches of Zanzibar.</p>
                        <p>As a Tanzania-based company, we understand our country like no one else. Our team consists of local Tanzanians who have grown up exploring these lands, from the vast savannas to the highest peaks. We've grown from organizing small local tours to becoming a trusted name in Tanzania tourism, yet our core philosophy remains unchanged: every journey should be authentic, sustainable, and deeply personal, showcasing the real Tanzania.</p>
                    @endif
                <div class="story-stats">
                    @foreach($statistics->take(3) as $index => $stat)
                    <div class="story-stat">
                        <div class="stat-number">{{ $stat->value }}</div>
                        <div class="stat-label">{{ $stat->label }}</div>
                    </div>
                    @endforeach
                </div>
            </div>
            </div>
        </div>
    </section>

<!-- Mission & Vision Section -->
@php
    $missionSection = $sections->get('mission') ?? null;
    $visionSection = $sections->get('vision') ?? null;
    $missionData = $missionSection ? ($missionSection->data ?? []) : [];
    $visionData = $visionSection ? ($visionSection->data ?? []) : [];
@endphp
<section class="mission-vision-section">
        <div class="container">
        <div class="mission-vision-grid">
            <div class="mission-card" data-aos="fade-up">
                <div class="mission-icon"><i class="{{ $missionData['icon'] ?? 'fas fa-bullseye' }}"></i></div>
                <h3 class="mission-title">{{ $missionData['title'] ?? 'Our Mission' }}</h3>
                <p class="mission-text">{{ $missionSection && $missionSection->content ? $missionSection->content : ($missionData['text'] ?? 'To provide authentic, sustainable, and transformative Tanzania travel experiences that connect travelers with the natural beauty, rich culture, and incredible wildlife of our homeland while supporting local communities and preserving Tanzania\'s natural heritage for future generations.') }}</p>
            </div>
            <div class="vision-card" data-aos="fade-up" data-aos-delay="100">
                <div class="vision-icon"><i class="{{ $visionData['icon'] ?? 'fas fa-eye' }}"></i></div>
                <h3 class="vision-title">{{ $visionData['title'] ?? 'Our Vision' }}</h3>
                <p class="vision-text">{{ $visionSection && $visionSection->content ? $visionSection->content : ($visionData['text'] ?? 'To be Tanzania\'s most trusted and respected tour operator, recognized globally for our commitment to excellence, sustainability, and authentic cultural experiences. We envision a future where responsible tourism helps preserve Tanzania\'s natural wonders while empowering local communities.') }}</p>
                </div>
            </div>
        </div>
    </section>

<!-- Our Values Section -->
@php
    $valuesSection = $sections->get('values') ?? null;
    $valuesData = $valuesSection ? ($valuesSection->data ?? []) : [];
@endphp
<section class="values-section">
        <div class="container">
        <div class="section-header" data-aos="fade-up">
            <span class="section-badge">{{ $valuesData['badge'] ?? 'What We Stand For' }}</span>
            <h2 class="section-title">{{ $valuesData['title'] ?? 'Our Core Values' }}</h2>
            <p class="section-subtitle">{{ $valuesData['subtitle'] ?? 'These principles guide everything we do and shape every experience we create.' }}</p>
                </div>
        <div class="values-grid">
            @foreach($values as $index => $value)
            <div class="value-item" data-aos="fade-up" data-aos-delay="{{ $index * 100 }}">
                <div class="value-icon"><i class="{{ $value->icon ?? 'fas fa-star' }}"></i></div>
                <h3 class="value-title">{{ $value->title }}</h3>
                <p class="value-description">{{ $value->description }}</p>
            </div>
            @endforeach
            </div>
        </div>
    </section>

<!-- Our Team Section -->
@php
    $teamSection = $sections->get('team') ?? null;
    $teamData = $teamSection ? ($teamSection->data ?? []) : [];
@endphp
<section class="team-section">
        <div class="container">
            <div class="section-header" data-aos="fade-up">
            <span class="section-badge">{{ $teamData['badge'] ?? 'Meet Our Team' }}</span>
            <h2 class="section-title">{{ $teamData['title'] ?? 'The People Behind Your Adventure' }}</h2>
            <p class="section-subtitle">{{ $teamData['subtitle'] ?? 'Our passionate team of Tanzania experts is dedicated to making your journey unforgettable.' }}</p>
            </div>
            <div class="team-grid">
            @foreach($teamMembers as $index => $member)
            <div class="team-member" data-aos="fade-up" data-aos-delay="{{ $index * 100 }}">
                <div class="member-photo">
                    <img src="{{ $member->image_url ? (str_starts_with($member->image_url, 'http') ? $member->image_url : asset($member->image_url)) : 'https://randomuser.me/api/portraits/men/' . (32 + $index) . '.jpg' }}" alt="{{ $member->name }}">
                    <div class="member-overlay">
                        <div class="member-social">
                            @if($member->social_links)
                                @foreach($member->social_links as $platform => $url)
                                    @if($url)
                                        <a href="{{ $url }}" target="_blank"><i class="fab fa-{{ $platform }}"></i></a>
                                    @endif
                                @endforeach
                            @endif
                    </div>
                        </div>
                    </div>
                <div class="member-info">
                    <h3 class="member-name">{{ $member->name }}</h3>
                    <p class="member-role">{{ $member->role }}</p>
                    <p class="member-bio">{{ $member->bio }}</p>
                    @if($member->expertise && count($member->expertise) > 0)
                    <div class="member-expertise">
                        @foreach($member->expertise as $expertise)
                        <span><i class="fas fa-check"></i> {{ $expertise }}</span>
                        @endforeach
                    </div>
                    @endif
                    </div>
                        </div>
            @endforeach
        </div>
    </section>

<!-- Why Choose Us Section -->
<section class="why-choose-us-section">
        <div class="container">
        <div class="section-header" data-aos="fade-up">
            <span class="section-badge">Why Travel With Us</span>
            <h2 class="section-title">Tanzania's Premier Tour Operator</h2>
            <p class="section-subtitle">With over 15 years of experience, we provide authentic Tanzanian adventures with unmatched expertise and personalized service.</p>
                </div>
        <div class="features-grid">
            <div class="feature-item" data-aos="fade-up">
                <div class="feature-icon"><i class="fas fa-user-check"></i></div>
                <h3 class="feature-title">Expert Local Guides</h3>
                <p class="feature-description">Our guides are Tanzanian-born experts with extensive knowledge of wildlife, culture, and safety protocols. They speak multiple languages and have years of experience.</p>
                </div>
            <div class="feature-item" data-aos="fade-up" data-aos-delay="100">
                <div class="feature-icon"><i class="fas fa-leaf"></i></div>
                <h3 class="feature-title">Sustainable Tourism</h3>
                <p class="feature-description">We're committed to eco-friendly practices and supporting local communities through responsible tourism. We partner with conservation organizations and local initiatives.</p>
                </div>
            <div class="feature-item" data-aos="fade-up" data-aos-delay="200">
                <div class="feature-icon"><i class="fas fa-shield-alt"></i></div>
                <h3 class="feature-title">Safety First Approach</h3>
                <p class="feature-description">Your safety is our priority. We maintain the highest safety standards, provide comprehensive insurance, and have 24/7 emergency support for all our Tanzania adventures.</p>
            </div>
            <div class="feature-item" data-aos="fade-up" data-aos-delay="300">
                <div class="feature-icon"><i class="fas fa-award"></i></div>
                <h3 class="feature-title">Award Winning Service</h3>
                <p class="feature-description">Recognized as Tanzania's top tour operator with multiple awards for excellence in service and experience. We're TATO certified and fully licensed.</p>
            </div>
            <div class="feature-item" data-aos="fade-up" data-aos-delay="400">
                <div class="feature-icon"><i class="fas fa-handshake"></i></div>
                <h3 class="feature-title">Local Partnerships</h3>
                <p class="feature-description">We work with trusted local partners, lodges, and suppliers to ensure authentic experiences while supporting Tanzania's tourism economy.</p>
            </div>
            <div class="feature-item" data-aos="fade-up" data-aos-delay="500">
                <div class="feature-icon"><i class="fas fa-heart"></i></div>
                <h3 class="feature-title">Personalized Service</h3>
                <p class="feature-description">Every tour is tailored to your interests and preferences. Our team works closely with you to create the perfect Tanzania adventure.</p>
            </div>
            </div>
        </div>
    </section>

<!-- Recognition Section -->
@php
    $recognitionSection = $sections->get('recognition') ?? null;
    $recognitionData = $recognitionSection ? ($recognitionSection->data ?? []) : [];
@endphp
<section class="certifications-section">
        <div class="container">
            <div class="section-header" data-aos="fade-up">
            <span class="section-badge">{{ $recognitionData['badge'] ?? 'Recognition' }}</span>
                <h2 class="section-title">{{ $recognitionData['title'] ?? 'Recognition' }}</h2>
            <p class="section-subtitle">{{ $recognitionData['subtitle'] ?? 'We\'re proud to be recognized for our commitment to excellence and responsible tourism.' }}</p>
            </div>
            <div class="certifications-grid">
            @foreach($recognitions as $index => $recognition)
            <div class="cert-item" data-aos="fade-up" data-aos-delay="{{ $index * 100 }}">
                    <div class="cert-icon"><i class="{{ $recognition->icon ?? 'fas fa-certificate' }}"></i></div>
                    <h4>{{ $recognition->title }}</h4>
                <p>{{ $recognition->description }}</p>
                @if($recognition->year)
                <span class="achievement-year">{{ $recognition->year }}</span>
                @endif
                </div>
            @endforeach
            </div>
        </div>
    </section>

<!-- Timeline Section -->
@php
    $timelineSection = $sections->get('timeline') ?? null;
    $timelineData = $timelineSection ? ($timelineSection->data ?? []) : [];
@endphp
<section class="timeline-section">
        <div class="container">
            <div class="section-header" data-aos="fade-up">
            <span class="section-badge">{{ $timelineData['badge'] ?? 'Our Journey' }}</span>
            <h2 class="section-title">{{ $timelineData['title'] ?? 'Our Story Through the Years' }}</h2>
            <p class="section-subtitle">{{ $timelineData['subtitle'] ?? 'From humble beginnings to becoming Tanzania\'s trusted tour operator' }}</p>
            </div>
        <div class="timeline-container">
            @foreach($timelineItems as $index => $item)
            <div class="timeline-item" data-aos="fade-up" data-aos-delay="{{ $index * 100 }}">
                <div class="timeline-year">{{ $item->year }}</div>
                <div class="timeline-content">
                    <h3>{{ $item->title }}</h3>
                    <p>{{ $item->description }}</p>
                </div>
                </div>
            @endforeach
            </div>
        </div>
    </section>

<!-- Statistics Section -->
<section class="about-stats-section">
    <div class="container">
        <div class="stats-container">
            @foreach($statistics as $index => $stat)
            <div class="stat-card" data-aos="fade-up" data-aos-delay="{{ $index * 100 }}">
                <div class="stat-icon-wrapper">
                    <i class="{{ $stat->icon ?? 'fas fa-star' }}"></i>
                </div>
                <div class="stat-number" data-count="{{ $stat->value }}">0</div>
                <div class="stat-label">{{ $stat->label }}</div>
                @if($stat->description)
                <p class="stat-description">{{ $stat->description }}</p>
                @endif
            </div>
            @endforeach
        </div>
    </div>
</section>

<!-- Achievements Section -->
@php
    $achievementsSection = $sections->get('achievements') ?? null;
    $achievementsData = $achievementsSection ? ($achievementsSection->data ?? []) : [];
@endphp
<section class="achievements-section">
    <div class="container">
        <div class="section-header" data-aos="fade-up">
            <span class="section-badge">{{ $achievementsData['badge'] ?? 'Our Achievements' }}</span>
            <h2 class="section-title">{{ $achievementsData['title'] ?? 'Milestones & Recognition' }}</h2>
            <p class="section-subtitle">{{ $achievementsData['subtitle'] ?? 'Proud moments and recognition for our commitment to excellence' }}</p>
        </div>
        <div class="achievements-grid">
            @foreach($recognitions->where('year', '!=', null) as $index => $recognition)
            <div class="achievement-card" data-aos="fade-up" data-aos-delay="{{ $index * 100 }}">
                <div class="achievement-icon"><i class="{{ $recognition->icon ?? 'fas fa-trophy' }}"></i></div>
                <h3>{{ $recognition->title }}</h3>
                <p>{{ $recognition->description }}</p>
                @if($recognition->year)
                <span class="achievement-year">{{ $recognition->year }}</span>
                @endif
            </div>
            @endforeach
        </div>
    </div>
</section>

<!-- CTA Section -->
<section class="cta-section">
    <div class="container">
        <div class="cta-content" data-aos="fade-up">
            <h2 class="cta-title">Ready for Your Tanzania Adventure?</h2>
            <p class="cta-text">Contact our Tanzania experts today to plan your perfect safari, Kilimanjaro climb, or beach holiday. We'll create a customized itinerary just for you.</p>
            <div class="cta-buttons">
                <a href="{{ route('contact') }}" class="cta-btn btn-primary">
                    <i class="fas fa-envelope"></i> Contact Us
                </a>
                <a href="{{ route('tours.index') }}" class="cta-btn btn-secondary">
                    <i class="fas fa-compass"></i> View Tours
                </a>
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
.content-section {
        padding: 100px 0;
        background: var(--white);
    }
.story-wrapper {
        display: grid; 
    grid-template-columns: 1fr 1fr;
        gap: 60px; 
    align-items: center;
    }
    .story-image { 
    border-radius: 20px;
        overflow: hidden; 
    box-shadow: 0 10px 40px rgba(0,0,0,0.15);
}
.story-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    display: block;
}
.story-content {
    max-width: 100%;
}
    .story-content p { 
    font-size: 1.1rem;
        line-height: 1.8; 
    color: var(--gray);
    margin-bottom: 20px;
}
.story-stats {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 30px;
    margin-top: 40px;
    padding-top: 40px;
    border-top: 2px solid var(--gray-light);
}
.story-stat {
    text-align: center;
}
.story-stat .stat-number {
    font-size: 2.5rem;
    font-weight: 800;
    color: var(--accent-green);
    margin-bottom: 8px;
}
.story-stat .stat-label {
    font-size: 0.95rem;
    color: var(--gray);
    font-weight: 600;
}
.mission-vision-section {
        padding: 100px 0;
    background: var(--gray-light);
    }
.mission-vision-grid {
        display: grid; 
    grid-template-columns: 1fr 1fr;
        gap: 40px; 
    }
.mission-card,
.vision-card {
    background: var(--white);
    padding: 50px 40px;
    border-radius: 20px;
    box-shadow: 0 5px 25px rgba(0,0,0,0.08);
        text-align: center; 
}
.mission-icon,
.vision-icon {
    font-size: 4rem;
    color: var(--accent-green);
    margin-bottom: 25px;
}
.mission-title,
.vision-title {
    font-size: 2rem;
    color: var(--primary-green);
    margin-bottom: 20px;
    font-weight: 700;
}
.mission-text,
.vision-text {
    font-size: 1.05rem;
    line-height: 1.8;
    color: var(--gray);
}
.values-section {
    padding: 100px 0;
        background: var(--white);
}
.values-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 30px;
    margin-top: 50px;
}
.value-item {
    background: var(--gray-light);
    padding: 35px 30px;
        border-radius: 16px;
    text-align: center;
        transition: all 0.3s;
    border: 2px solid transparent;
}
.value-item:hover {
    background: var(--white);
    border-color: var(--accent-green);
        transform: translateY(-5px);
    box-shadow: 0 10px 30px rgba(0,0,0,0.1);
    }
.value-icon {
        font-size: 3rem; 
        color: var(--accent-green); 
        margin-bottom: 20px; 
    }
.value-title {
        font-size: 1.5rem; 
        color: var(--primary-green); 
        margin-bottom: 15px; 
        font-weight: 700;
    }
.value-description {
        color: var(--gray); 
        line-height: 1.7; 
}
.team-section {
        padding: 100px 0;
    background: var(--gray-light);
}
.team-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 40px;
    margin-top: 50px;
}
.team-member {
    background: var(--white);
    border-radius: 20px;
    overflow: hidden;
    box-shadow: 0 5px 25px rgba(0,0,0,0.08);
    transition: all 0.3s;
}
.team-member:hover {
    transform: translateY(-10px);
    box-shadow: 0 15px 40px rgba(0,0,0,0.15);
}
.member-photo {
        position: relative;
    height: 300px;
        overflow: hidden;
    }
.member-photo img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.5s;
}
.team-member:hover .member-photo img {
    transform: scale(1.1);
}
.member-overlay {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
    background: linear-gradient(to top, rgba(26, 77, 58, 0.9) 0%, transparent 100%);
    display: flex;
    align-items: flex-end;
    justify-content: center;
    padding: 20px;
    opacity: 0;
    transition: opacity 0.3s;
}
.team-member:hover .member-overlay {
    opacity: 1;
}
.member-social {
    display: flex;
    gap: 15px;
}
.member-social a {
    width: 40px;
    height: 40px;
    background: rgba(255, 255, 255, 0.2);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: var(--white);
    text-decoration: none;
        transition: all 0.3s;
    }
.member-social a:hover {
    background: var(--accent-green);
    transform: scale(1.1);
}
.member-info {
    padding: 30px;
}
.member-name {
    font-size: 1.5rem;
    color: var(--primary-green);
    margin-bottom: 5px;
        font-weight: 700; 
}
.member-role {
    color: var(--accent-green);
    font-weight: 600;
    margin-bottom: 15px;
    font-size: 1rem;
}
.member-bio {
    color: var(--gray);
    line-height: 1.7;
    margin-bottom: 20px;
    font-size: 0.95rem;
}
.member-expertise {
    display: flex;
    flex-wrap: wrap;
    gap: 10px;
    padding-top: 15px;
    border-top: 1px solid var(--gray-light);
}
.member-expertise span {
    font-size: 0.85rem;
    color: var(--primary-green);
    display: flex;
    align-items: center;
    gap: 5px;
}
.member-expertise i {
    color: var(--accent-green);
    font-size: 0.75rem;
}
.why-choose-us-section {
        padding: 100px 0;
        background: var(--white);
    }
.features-grid {
        display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: 40px;
    margin-top: 50px;
}
.feature-item {
    padding: 30px 20px;
        border-radius: 12px;
        transition: all 0.3s;
    text-align: center;
}
.feature-item:hover {
    background: var(--gray-light);
        transform: translateY(-5px);
    }
.feature-icon {
        font-size: 3rem;
        color: var(--accent-green);
        margin-bottom: 20px;
    }
.feature-title {
    font-size: 1.4rem;
        color: var(--primary-green);
        margin-bottom: 15px;
        font-weight: 700;
    }
.feature-description {
        color: var(--gray);
        line-height: 1.7;
        font-size: 0.95rem;
    }
    .certifications-section {
        padding: 100px 0;
        background: var(--gray-light);
    }
    .certifications-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 30px;
    margin-top: 50px;
    }
.cert-item {
        background: var(--white);
    padding: 35px 25px;
    border-radius: 16px;
        text-align: center;
        transition: all 0.3s;
    box-shadow: 0 3px 15px rgba(0,0,0,0.05);
    }
.cert-item:hover {
        transform: translateY(-5px);
    box-shadow: 0 8px 25px rgba(0,0,0,0.1);
    }
    .cert-icon {
    font-size: 3rem;
        color: var(--accent-green);
    margin-bottom: 20px;
    }
.cert-item h4 {
        font-size: 1.2rem;
        color: var(--primary-green);
        margin-bottom: 10px;
        font-weight: 700;
    }
.cert-item p {
        color: var(--gray);
    font-size: 0.95rem;
        line-height: 1.6;
}
.cta-section {
    padding: 100px 0;
    background: linear-gradient(135deg, var(--light-green) 0%, var(--white) 100%);
    text-align: center;
}
.cta-content {
    max-width: 800px;
    margin: 0 auto;
}
.cta-title {
    font-size: 3rem;
    color: var(--primary-green);
    margin-bottom: 20px;
    font-weight: 700;
}
.cta-text {
    font-size: 1.2rem;
    color: var(--gray);
    margin-bottom: 40px;
    line-height: 1.7;
}
.cta-buttons {
    display: flex;
    justify-content: center;
    gap: 20px;
    flex-wrap: wrap;
}
.cta-btn {
    padding: 15px 35px;
    font-size: 1rem;
    display: inline-flex;
    align-items: center;
    gap: 10px;
    border-radius: 50px;
    text-decoration: none;
    font-weight: 600;
    transition: all 0.3s;
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
.timeline-section {
        padding: 100px 0;
        background: var(--white);
    position: relative;
}
.timeline-container {
    position: relative;
    max-width: 900px;
    margin: 50px auto 0;
    padding: 0 20px;
}
.timeline-container::before {
    content: '';
    position: absolute;
    left: 50%;
    top: 0;
    bottom: 0;
    width: 4px;
    background: linear-gradient(to bottom, var(--accent-green), var(--secondary-green));
    transform: translateX(-50%);
}
.timeline-item {
    position: relative;
    margin-bottom: 60px;
    display: flex;
    align-items: center;
    gap: 40px;
}
.timeline-item:nth-child(odd) {
    flex-direction: row;
}
.timeline-item:nth-child(even) {
    flex-direction: row-reverse;
}
.timeline-year {
    min-width: 120px;
    height: 120px;
    background: linear-gradient(135deg, var(--accent-green), var(--secondary-green));
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: var(--white);
    font-size: 1.8rem;
    font-weight: 800;
    box-shadow: 0 10px 30px rgba(61, 165, 114, 0.3);
    position: relative;
    z-index: 2;
}
.timeline-content {
    flex: 1;
    background: var(--gray-light);
    padding: 30px;
    border-radius: 15px;
    box-shadow: 0 5px 20px rgba(0,0,0,0.08);
    transition: all 0.3s;
}
.timeline-item:hover .timeline-content {
    background: var(--white);
    box-shadow: 0 10px 30px rgba(0,0,0,0.15);
    transform: translateY(-5px);
}
.timeline-content h3 {
        color: var(--primary-green);
    font-size: 1.5rem;
    margin-bottom: 10px;
        font-weight: 700;
    }
.timeline-content p {
        color: var(--gray);
        line-height: 1.7;
    margin: 0;
}
.story-image-badge {
    position: absolute;
    bottom: 20px;
    left: 20px;
    background: rgba(26, 77, 58, 0.95);
    color: var(--white);
    padding: 12px 20px;
    border-radius: 30px;
    display: flex;
    align-items: center;
    gap: 10px;
    font-weight: 600;
    backdrop-filter: blur(10px);
}
.story-image {
    position: relative;
    border-radius: 20px;
    overflow: hidden;
    box-shadow: 0 10px 40px rgba(0,0,0,0.15);
}
.about-stats-section {
        padding: 100px 0;
    background: linear-gradient(135deg, var(--primary-green) 0%, var(--accent-green) 100%);
        color: var(--white);
        position: relative;
        overflow: hidden;
    }
.about-stats-section::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: url('data:image/svg+xml,<svg width="100" height="100" xmlns="http://www.w3.org/2000/svg"><circle cx="50" cy="50" r="2" fill="rgba(255,255,255,0.1)"/></svg>');
        opacity: 0.3;
    }
.stats-container {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 40px;
        position: relative;
        z-index: 2;
    }
.stat-card {
    background: rgba(255, 255, 255, 0.1);
    backdrop-filter: blur(10px);
    padding: 40px 30px;
    border-radius: 20px;
    text-align: center;
    border: 2px solid rgba(255, 255, 255, 0.2);
    transition: all 0.3s;
}
.stat-card:hover {
    background: rgba(255, 255, 255, 0.15);
    transform: translateY(-10px);
    box-shadow: 0 15px 40px rgba(0,0,0,0.2);
}
.stat-icon-wrapper {
    width: 80px;
    height: 80px;
    margin: 0 auto 20px;
    background: rgba(255, 255, 255, 0.2);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 2.5rem;
        color: var(--white);
}
.stat-number {
    font-size: 3.5rem;
    font-weight: 800;
    margin-bottom: 10px;
    line-height: 1;
}
.stat-label {
    font-size: 1.3rem;
    font-weight: 700;
    margin-bottom: 10px;
        opacity: 0.95;
}
.stat-description {
    font-size: 0.95rem;
    opacity: 0.85;
    line-height: 1.6;
    margin: 0;
}
.achievements-section {
    padding: 100px 0;
    background: var(--gray-light);
}
.achievements-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
    gap: 30px;
    margin-top: 50px;
}
.achievement-card {
    background: var(--white);
    padding: 40px 30px;
    border-radius: 20px;
    text-align: center;
    box-shadow: 0 5px 25px rgba(0,0,0,0.08);
    transition: all 0.3s;
    position: relative;
    overflow: hidden;
}
.achievement-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 4px;
    background: linear-gradient(90deg, var(--accent-green), var(--secondary-green));
    transform: scaleX(0);
    transition: transform 0.3s;
}
.achievement-card:hover::before {
    transform: scaleX(1);
}
.achievement-card:hover {
    transform: translateY(-10px);
    box-shadow: 0 15px 40px rgba(0,0,0,0.15);
}
.achievement-icon {
    width: 100px;
    height: 100px;
    margin: 0 auto 25px;
    background: var(--light-green);
    border-radius: 50%;
        display: flex;
    align-items: center;
        justify-content: center;
    font-size: 3rem;
    color: var(--accent-green);
    transition: all 0.3s;
}
.achievement-card:hover .achievement-icon {
        background: var(--accent-green);
        color: var(--white);
    transform: scale(1.1) rotate(5deg);
    }
.achievement-card h3 {
        color: var(--primary-green);
    font-size: 1.4rem;
    margin-bottom: 15px;
    font-weight: 700;
}
.achievement-card p {
    color: var(--gray);
    line-height: 1.7;
    margin-bottom: 20px;
}
.achievement-year {
    display: inline-block;
    background: var(--light-green);
    color: var(--primary-green);
    padding: 6px 15px;
    border-radius: 20px;
    font-size: 0.9rem;
    font-weight: 600;
}
    @media (max-width: 992px) {
    .story-wrapper {
        grid-template-columns: 1fr;
    }
    .mission-vision-grid {
        grid-template-columns: 1fr;
    }
    .story-stats {
        grid-template-columns: 1fr;
    }
    .timeline-container::before {
        left: 30px;
    }
    .timeline-item {
        flex-direction: row !important;
        padding-left: 60px;
    }
    .timeline-year {
        position: absolute;
        left: 0;
        min-width: 60px;
        height: 60px;
        font-size: 1.2rem;
    }
    }
    @media (max-width: 768px) {
    .page-hero-title {
        font-size: 2.5rem;
    }
    .values-grid,
    .team-grid,
    .features-grid {
        grid-template-columns: 1fr;
    }
    .certifications-grid {
        grid-template-columns: repeat(2, 1fr);
    }
    .stats-container {
        grid-template-columns: repeat(2, 1fr);
    }
    .story-stats {
        grid-template-columns: 1fr;
    }
    .achievements-grid {
        grid-template-columns: 1fr;
    }
    }
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Animate statistics
    function animateStats() {
        const stats = document.querySelectorAll('.about-stats-section .stat-number');
        stats.forEach(stat => {
            const target = parseFloat(stat.getAttribute('data-count'));
            const duration = 2000;
            const increment = target / (duration / 16);
            let current = 0;
            
            const timer = setInterval(() => {
                current += increment;
                if (current >= target) {
                    stat.textContent = target % 1 === 0 ? Math.floor(target) : target.toFixed(1);
                    clearInterval(timer);
                } else {
                    stat.textContent = current % 1 === 0 ? Math.floor(current) : current.toFixed(1);
                }
            }, 16);
        });
    }

    // Intersection Observer for stats
    const statsObserver = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting && !entry.target.classList.contains('animated')) {
                entry.target.classList.add('animated');
                animateStats();
            }
        });
    }, { threshold: 0.5 });

    const statsSection = document.querySelector('.about-stats-section');
    if (statsSection) {
        statsObserver.observe(statsSection);
    }
    });
</script>
@endpush
