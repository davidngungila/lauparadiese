@extends('layouts.app')

@section('title', 'Tanzania Destinations - Lau Paradise Adventures')
@section('description', 'Discover Tanzania\'s breathtaking destinations. From Serengeti and Kilimanjaro to Zanzibar beaches, explore the best of Tanzania.')

@section('content')

<!-- Hero Section -->
<section class="page-hero-section" style="background-image: url('{{ asset('images/safari_home-1.jpg') }}');">
    <div class="page-hero-overlay"></div>
        <div class="container">
        <div class="page-hero-content" data-aos="fade-up">
            <span class="hero-badge"><i class="fas fa-compass"></i> Explore Tanzania</span>
            <h1 class="page-hero-title">Discover Tanzania's Wonders</h1>
            <p class="page-hero-subtitle">From world-famous national parks to pristine beaches and majestic mountains - explore the diverse beauty of Tanzania</p>
            </div>
        </div>
    </section>

<!-- Destinations Overview Section -->
<section class="destinations-overview-section">
        <div class="container">
        <div class="section-header" data-aos="fade-up">
            <span class="section-badge">Tanzania's Best</span>
            <h2 class="section-title">Top Tanzania Destinations</h2>
            <p class="section-subtitle">Tanzania offers an incredible diversity of landscapes, wildlife, and experiences. From the endless plains of Serengeti to the snow-capped peak of Kilimanjaro, discover why Tanzania is Africa's premier safari destination.</p>
        </div>
        
        <!-- Featured Destinations Grid -->
        <div class="destinations-grid">
            @forelse($homepageDestinations as $index => $dest)
            <a href="{{ route('destinations.show', $dest['slug']) }}" class="destination-card" data-aos="fade-up" data-aos-delay="{{ $index * 100 }}">
                <img src="{{ $dest['image'] }}" alt="{{ $dest['name'] }}" loading="lazy" onerror="this.src='{{ asset('images/safari_home-1.jpg') }}'">
                <div class="destination-overlay"></div>
                <div class="destination-content">
                    @if($dest['category'])
                    <div class="destination-badge">{{ $dest['category'] }}</div>
                    @endif
                    <h3 class="destination-name">{{ $dest['name'] }}</h3>
                    @if($dest['duration'])
                    <p class="destination-tours">{{ $dest['duration'] }}</p>
                    @elseif($dest['price_display'])
                    <p class="destination-tours">{{ $dest['price_display'] }}</p>
                    @endif
                    @if($dest['short_description'])
                    <p class="destination-highlight">{{ Str::limit($dest['short_description'], 60) }}</p>
                    @endif
                </div>
            </a>
            @empty
            <!-- Fallback to hardcoded destinations if no HomepageDestinations -->
            <a href="{{ route('destinations.show', 'serengeti') }}" class="destination-card" data-aos="fade-up">
                <img src="{{ asset('images/Serengetei-NP-2.jpeg') }}" alt="Serengeti">
                <div class="destination-overlay"></div>
                <div class="destination-content">
                    <div class="destination-badge">Northern Circuit</div>
                    <h3 class="destination-name">Serengeti National Park</h3>
                    <p class="destination-tours">12+ Safari Tours</p>
                    <p class="destination-highlight">Witness the Great Migration</p>
                </div>
            </a>
            <a href="{{ route('destinations.show', 'kilimanjaro') }}" class="destination-card" data-aos="fade-up" data-aos-delay="100">
                <img src="{{ asset('images/hero-slider/kilimanjaro-climbing.jpg') }}" alt="Kilimanjaro">
                <div class="destination-overlay"></div>
                <div class="destination-content">
                    <div class="destination-badge">Mountain</div>
                    <h3 class="destination-name">Mount Kilimanjaro</h3>
                    <p class="destination-tours">6 Climbing Routes</p>
                    <p class="destination-highlight">Africa's Highest Peak</p>
                </div>
            </a>
            <a href="{{ route('destinations.show', 'zanzibar') }}" class="destination-card" data-aos="fade-up" data-aos-delay="200">
                <img src="{{ asset('images/zanzibar_home.jpg') }}" alt="Zanzibar">
                <div class="destination-overlay"></div>
                <div class="destination-content">
                    <div class="destination-badge">Coastal</div>
                    <h3 class="destination-name">Zanzibar</h3>
                    <p class="destination-tours">8 Beach Holidays</p>
                    <p class="destination-highlight">Spice Island Paradise</p>
                </div>
            </a>
            <a href="{{ route('destinations.show', 'ngorongoro') }}" class="destination-card" data-aos="fade-up" data-aos-delay="300">
                <img src="{{ asset('images/safari_home-1.jpg') }}" alt="Ngorongoro">
                <div class="destination-overlay"></div>
                <div class="destination-content">
                    <div class="destination-badge">Northern Circuit</div>
                    <h3 class="destination-name">Ngorongoro Crater</h3>
                    <p class="destination-tours">5 Crater Tours</p>
                    <p class="destination-highlight">World's Largest Caldera</p>
                </div>
            </a>
            <a href="{{ route('destinations.show', 'tarangire') }}" class="destination-card" data-aos="fade-up" data-aos-delay="400">
                <img src="{{ asset('images/Tarangire-NP-1.jpeg') }}" alt="Tarangire">
                <div class="destination-overlay"></div>
                <div class="destination-content">
                    <div class="destination-badge">Northern Circuit</div>
                    <h3 class="destination-name">Tarangire National Park</h3>
                    <p class="destination-tours">4 Safari Tours</p>
                    <p class="destination-highlight">Elephant Paradise</p>
                </div>
            </a>
            <a href="{{ route('destinations.show', 'manyara') }}" class="destination-card" data-aos="fade-up" data-aos-delay="500">
                <img src="{{ asset('images/safari_home-1.jpg') }}" alt="Lake Manyara">
                <div class="destination-overlay"></div>
                <div class="destination-content">
                    <div class="destination-badge">Northern Circuit</div>
                    <h3 class="destination-name">Lake Manyara</h3>
                    <p class="destination-tours">3 Safari Tours</p>
                    <p class="destination-highlight">Tree-Climbing Lions</p>
                </div>
            </a>
            @endforelse
        </div>
    </div>
</section>

<!-- Regions Section -->
<section class="regions-section">
    <div class="container">
        <div class="section-header" data-aos="fade-up">
            <span class="section-badge">Explore by Region</span>
            <h2 class="section-title">Tanzania's Diverse Regions</h2>
            <p class="section-subtitle">Tanzania is divided into distinct regions, each offering unique experiences and attractions.</p>
        </div>
        <div class="regions-grid">
            <div class="region-card" data-aos="fade-up">
                <div class="region-icon"><i class="fas fa-paw"></i></div>
                <h3 class="region-title">Northern Circuit</h3>
                <p class="region-description">Home to Serengeti, Ngorongoro, Tarangire, and Lake Manyara. The most popular safari circuit with incredible wildlife viewing.</p>
                <ul class="region-destinations">
                    <li><i class="fas fa-check"></i> Serengeti National Park</li>
                    <li><i class="fas fa-check"></i> Ngorongoro Crater</li>
                    <li><i class="fas fa-check"></i> Tarangire National Park</li>
                    <li><i class="fas fa-check"></i> Lake Manyara</li>
                    <li><i class="fas fa-check"></i> Arusha National Park</li>
                </ul>
                <a href="{{ route('tours.category', 'safari') }}" class="region-link">Explore Northern Circuit Tours <i class="fas fa-arrow-right"></i></a>
            </div>
            <div class="region-card" data-aos="fade-up" data-aos-delay="100">
                <div class="region-icon"><i class="fas fa-mountain"></i></div>
                <h3 class="region-title">Mountain Regions</h3>
                <p class="region-description">Challenge yourself with Africa's highest peaks and stunning mountain landscapes.</p>
                <ul class="region-destinations">
                    <li><i class="fas fa-check"></i> Mount Kilimanjaro</li>
                    <li><i class="fas fa-check"></i> Mount Meru</li>
                    <li><i class="fas fa-check"></i> Usambara Mountains</li>
                    <li><i class="fas fa-check"></i> Pare Mountains</li>
                </ul>
                <a href="{{ route('tours.category', 'hiking') }}" class="region-link">Explore Mountain Tours <i class="fas fa-arrow-right"></i></a>
            </div>
            <div class="region-card" data-aos="fade-up" data-aos-delay="200">
                <div class="region-icon"><i class="fas fa-umbrella-beach"></i></div>
                <h3 class="region-title">Coastal & Islands</h3>
                <p class="region-description">Pristine beaches, turquoise waters, and rich cultural heritage await on Tanzania's coast.</p>
                <ul class="region-destinations">
                    <li><i class="fas fa-check"></i> Zanzibar</li>
                    <li><i class="fas fa-check"></i> Pemba Island</li>
                    <li><i class="fas fa-check"></i> Mafia Island</li>
                    <li><i class="fas fa-check"></i> Dar es Salaam</li>
                </ul>
                <a href="{{ route('tours.category', 'beach') }}" class="region-link">Explore Beach Tours <i class="fas fa-arrow-right"></i></a>
            </div>
            <div class="region-card" data-aos="fade-up" data-aos-delay="300">
                <div class="region-icon"><i class="fas fa-tree"></i></div>
                <h3 class="region-title">Southern Circuit</h3>
                <p class="region-description">Less crowded parks offering authentic wilderness experiences and diverse ecosystems.</p>
                <ul class="region-destinations">
                    <li><i class="fas fa-check"></i> Selous Game Reserve</li>
                    <li><i class="fas fa-check"></i> Ruaha National Park</li>
                    <li><i class="fas fa-check"></i> Mikumi National Park</li>
                    <li><i class="fas fa-check"></i> Udzungwa Mountains</li>
                </ul>
                <a href="{{ route('tours.index') }}" class="region-link">Explore Southern Tours <i class="fas fa-arrow-right"></i></a>
            </div>
                    </div>
                </div>
</section>

<!-- Best Time to Visit Section -->
<section class="best-time-section">
    <div class="container">
        <div class="section-header" data-aos="fade-up">
            <span class="section-badge">Travel Planning</span>
            <h2 class="section-title">Best Time to Visit Tanzania</h2>
            <p class="section-subtitle">Tanzania offers incredible experiences year-round. Choose the perfect time for your adventure.</p>
                    </div>
        <div class="seasons-grid">
            <div class="season-card" data-aos="fade-up">
                <div class="season-icon"><i class="fas fa-sun"></i></div>
                <h3 class="season-title">Dry Season</h3>
                <p class="season-period">June - October</p>
                <p class="season-description">Perfect for wildlife viewing. Clear skies, minimal rain, and animals gather around water sources. Best time for safaris and Kilimanjaro climbs.</p>
                <div class="season-highlights">
                    <span><i class="fas fa-check-circle"></i> Excellent Wildlife Viewing</span>
                    <span><i class="fas fa-check-circle"></i> Clear Weather</span>
                    <span><i class="fas fa-check-circle"></i> Great Migration</span>
                </div>
                    </div>
            <div class="season-card" data-aos="fade-up" data-aos-delay="100">
                <div class="season-icon"><i class="fas fa-cloud-rain"></i></div>
                <h3 class="season-title">Green Season</h3>
                <p class="season-period">November - May</p>
                <p class="season-description">Lush landscapes, fewer crowds, and lower prices. Perfect for bird watching and photography. Calving season in Serengeti (January-March).</p>
                <div class="season-highlights">
                    <span><i class="fas fa-check-circle"></i> Lush Landscapes</span>
                    <span><i class="fas fa-check-circle"></i> Fewer Crowds</span>
                    <span><i class="fas fa-check-circle"></i> Better Prices</span>
                </div>
            </div>
            <div class="season-card" data-aos="fade-up" data-aos-delay="200">
                <div class="season-icon"><i class="fas fa-umbrella-beach"></i></div>
                <h3 class="season-title">Beach Season</h3>
                <p class="season-period">Year Round</p>
                <p class="season-description">Zanzibar and coastal areas enjoy warm weather year-round. Best beach weather from June to October. Perfect for diving and water sports.</p>
                <div class="season-highlights">
                    <span><i class="fas fa-check-circle"></i> Warm Weather</span>
                    <span><i class="fas fa-check-circle"></i> Great Diving</span>
                    <span><i class="fas fa-check-circle"></i> Cultural Tours</span>
                    </div>
                </div>
            </div>
        </div>
    </section>

<!-- Wildlife Highlights Section -->
<section class="wildlife-section">
        <div class="container">
            <div class="section-header" data-aos="fade-up">
            <span class="section-badge">Wildlife</span>
            <h2 class="section-title">Tanzania's Incredible Wildlife</h2>
            <p class="section-subtitle">Home to the Big Five and countless other species, Tanzania is a wildlife enthusiast's paradise.</p>
        </div>
        <div class="wildlife-grid">
            <div class="wildlife-item" data-aos="fade-up">
                <div class="wildlife-icon"><i class="fas fa-paw"></i></div>
                <h4>Big Five</h4>
                <p>Lion, Leopard, Elephant, Buffalo, Rhino</p>
            </div>
            <div class="wildlife-item" data-aos="fade-up" data-aos-delay="50">
                <div class="wildlife-icon"><i class="fas fa-horse"></i></div>
                <h4>Great Migration</h4>
                <p>2+ Million Wildebeest & Zebra</p>
                    </div>
            <div class="wildlife-item" data-aos="fade-up" data-aos-delay="100">
                <div class="wildlife-icon"><i class="fas fa-dove"></i></div>
                <h4>Bird Species</h4>
                <p>1,000+ Bird Species</p>
                </div>
            <div class="wildlife-item" data-aos="fade-up" data-aos-delay="150">
                <div class="wildlife-icon"><i class="fas fa-fish"></i></div>
                <h4>Marine Life</h4>
                <p>Dolphins, Turtles, Whale Sharks</p>
                    </div>
            <div class="wildlife-item" data-aos="fade-up" data-aos-delay="200">
                <div class="wildlife-icon"><i class="fas fa-spider"></i></div>
                <h4>Endemic Species</h4>
                <p>Unique to Tanzania</p>
            </div>
            <div class="wildlife-item" data-aos="fade-up" data-aos-delay="250">
                <div class="wildlife-icon"><i class="fas fa-tree"></i></div>
                <h4>Ecosystems</h4>
                <p>Savanna, Forest, Mountain, Coastal</p>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
<section class="cta-section">
    <div class="container">
        <div class="cta-content" data-aos="fade-up">
            <h2 class="cta-title">Ready to Explore Tanzania?</h2>
            <p class="cta-text">Let our experts help you plan the perfect Tanzania adventure based on your interests and travel dates.</p>
                <div class="cta-buttons">
                <a href="{{ route('tours.index') }}" class="cta-btn btn-primary">
                    <i class="fas fa-compass"></i> View All Tours
                </a>
                <a href="{{ route('contact') }}" class="cta-btn btn-secondary">
                    <i class="fas fa-envelope"></i> Get Expert Advice
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
.destinations-overview-section {
        padding: 100px 0;
        background: var(--white);
}
    .destinations-grid {
        display: grid;
    grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
        gap: 30px;
    margin-top: 50px;
    }
    .destination-card {
    position: relative;
    height: 450px;
    border-radius: 20px;
        overflow: hidden;
    display: block;
    text-decoration: none;
    transition: transform 0.3s;
    box-shadow: 0 8px 30px rgba(0,0,0,0.1);
    }
    .destination-card:hover {
        transform: translateY(-10px);
    box-shadow: 0 15px 50px rgba(0,0,0,0.2);
}
.destination-card img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.5s;
    }
.destination-card:hover img {
    transform: scale(1.15);
    }
.destination-overlay {
        position: absolute;
        top: 0;
        left: 0;
    right: 0;
    bottom: 0;
    background: linear-gradient(to top, rgba(0, 0, 0, 0.85) 0%, rgba(0, 0, 0, 0) 50%);
        z-index: 1;
    }
.destination-content {
        position: absolute;
    bottom: 0;
    left: 0;
    right: 0;
    padding: 30px;
    z-index: 2;
    color: white;
}
.destination-badge {
    display: inline-block;
        padding: 6px 14px;
    background: rgba(62, 165, 114, 0.9);
        border-radius: 20px;
        font-size: 0.8rem;
        font-weight: 600;
    margin-bottom: 10px;
}
.destination-name {
    font-size: 2rem;
    font-weight: 700;
    margin-bottom: 8px;
    text-shadow: 2px 2px 8px rgba(0, 0, 0, 0.7);
}
.destination-tours {
    font-size: 1rem;
    opacity: 0.9;
    margin-bottom: 5px;
}
.destination-highlight {
    font-size: 0.95rem;
    opacity: 0.95;
        font-weight: 600;
    color: var(--accent-green);
}
.regions-section {
    padding: 100px 0;
    background: var(--gray-light);
}
.regions-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
    gap: 30px;
    margin-top: 50px;
}
.region-card {
    background: var(--white);
    padding: 40px 30px;
    border-radius: 16px;
    box-shadow: 0 5px 25px rgba(0,0,0,0.08);
    transition: all 0.3s;
}
.region-card:hover {
    transform: translateY(-10px);
    box-shadow: 0 15px 40px rgba(0,0,0,0.15);
}
.region-icon {
    font-size: 3rem;
        color: var(--accent-green);
    margin-bottom: 20px;
}
.region-title {
    font-size: 1.6rem;
    color: var(--primary-green);
    margin-bottom: 15px;
    font-weight: 700;
}
.region-description {
        color: var(--gray);
    line-height: 1.7;
    margin-bottom: 20px;
}
.region-destinations {
    list-style: none;
    margin-bottom: 25px;
}
.region-destinations li {
    padding: 8px 0;
    color: var(--text-color);
        display: flex;
        align-items: center;
    gap: 10px;
}
.region-destinations i {
    color: var(--accent-green);
    font-size: 0.85rem;
}
.region-link {
        color: var(--accent-green);
    text-decoration: none;
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 8px;
    transition: all 0.3s;
    }
.region-link:hover {
        gap: 12px;
    color: var(--secondary-green);
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
    padding: 40px 30px;
    border-radius: 16px;
        text-align: center;
    transition: all 0.3s;
    border: 2px solid transparent;
}
.season-card:hover {
    background: var(--white);
    border-color: var(--accent-green);
    transform: translateY(-5px);
    box-shadow: 0 10px 30px rgba(0,0,0,0.1);
}
.season-icon {
    font-size: 3.5rem;
    color: var(--accent-green);
    margin-bottom: 20px;
}
.season-title {
    font-size: 1.8rem;
        color: var(--primary-green);
    margin-bottom: 10px;
    font-weight: 700;
}
.season-period {
    font-size: 1.1rem;
    color: var(--accent-green);
        font-weight: 600;
        margin-bottom: 15px;
}
.season-description {
        color: var(--gray);
        line-height: 1.7;
    margin-bottom: 20px;
}
.season-highlights {
    display: flex;
    flex-direction: column;
    gap: 10px;
    text-align: left;
}
.season-highlights span {
    display: flex;
    align-items: center;
    gap: 10px;
    color: var(--text-color);
    font-size: 0.95rem;
}
.season-highlights i {
    color: var(--accent-green);
}
.wildlife-section {
    padding: 100px 0;
    background: var(--gray-light);
}
.wildlife-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 25px;
    margin-top: 50px;
}
.wildlife-item {
    background: var(--white);
    padding: 30px 20px;
    border-radius: 12px;
    text-align: center;
    transition: all 0.3s;
    box-shadow: 0 3px 15px rgba(0,0,0,0.05);
}
.wildlife-item:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 25px rgba(0,0,0,0.1);
}
.wildlife-icon {
    font-size: 2.5rem;
    color: var(--accent-green);
        margin-bottom: 15px;
    }
.wildlife-item h4 {
    font-size: 1.2rem;
    color: var(--primary-green);
    margin-bottom: 8px;
    font-weight: 700;
}
.wildlife-item p {
    color: var(--gray);
    font-size: 0.95rem;
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
    @media (max-width: 768px) {
    .page-hero-title {
            font-size: 2.5rem;
        }
        .destinations-grid {
            grid-template-columns: 1fr;
        }
    .regions-grid,
    .seasons-grid {
            grid-template-columns: 1fr;
        }
    .wildlife-grid {
        grid-template-columns: repeat(2, 1fr);
        }
    }
</style>
@endpush
