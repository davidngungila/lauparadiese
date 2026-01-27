<header class="header-main">
    <div class="top-bar">
        <div class="container">
            <p>
                <i class="fas fa-bolt"></i>
                <span>Special Offer! Up to <strong>30% OFF</strong> on Tanzania Safari Tours.</span>
                <a href="{{ route('tours.last-minute') }}" class="promo-link">Book Now &rarr;</a>
            </p>
        </div>
    </div>
    
    <nav class="main-nav">
        <div class="container">
            <div class="nav-content">
                <a href="{{ route('home') }}" class="logo">
                    <img src="{{ asset('lau-adventuress.png') }}" alt="Lau Paradise Adventures" class="logo-img">
                </a>
                
                <div class="desktop-nav">
                    <ul class="nav-menu">
                        <li><a href="{{ route('home') }}" class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}">Home</a></li>
                        
                        <!-- Safaris Dropdown -->
                        <li class="nav-item-dropdown">
                            <a href="{{ route('safaris') }}" class="nav-link {{ request()->routeIs('safaris') ? 'active' : '' }}">
                                Safaris <i class="fas fa-chevron-down"></i>
                            </a>
                            <div class="dropdown-menu">
                                <div class="dropdown-scroll-wrapper">
                                    <button class="dropdown-scroll-btn scroll-up" onclick="scrollDropdown(this, 'up')" aria-label="Scroll up">
                                        <i class="fas fa-chevron-up"></i>
                                    </button>
                                    <div class="dropdown-content" id="safaris-dropdown-content">
                                        <div class="dropdown-header">
                                            <h3>Popular Safaris</h3>
                                            <a href="{{ route('safaris') }}" class="view-all">View All <i class="fas fa-arrow-right"></i></a>
                                        </div>
                                        <div class="dropdown-grid">
                                            @forelse($navSafaris as $safari)
                                            <a href="{{ route('tours.show', $safari['slug']) }}" class="dropdown-item">
                                                <div class="dropdown-item-image">
                                                    <img src="{{ $safari['image'] }}" alt="{{ $safari['name'] }}">
                                                </div>
                                                <div class="dropdown-item-content">
                                                    <h4>{{ $safari['name'] }}</h4>
                                                    <div class="dropdown-item-meta">
                                                        <span><i class="fas fa-clock"></i> {{ $safari['duration_days'] }} Days</span>
                                                        <span><i class="fas fa-dollar-sign"></i> ${{ $safari['price'] }}</span>
                                                    </div>
                                                </div>
                                            </a>
                                            @empty
                                            <div class="dropdown-empty">
                                                <p>No safaris available at the moment.</p>
                                            </div>
                                            @endforelse
                                        </div>
                                    </div>
                                    <button class="dropdown-scroll-btn scroll-down" onclick="scrollDropdown(this, 'down')" aria-label="Scroll down">
                                        <i class="fas fa-chevron-down"></i>
                                    </button>
                                </div>
                            </div>
                        </li>
                        
                        <!-- Tours Dropdown -->
                        <li class="nav-item-dropdown">
                            <a href="{{ route('tours.index') }}" class="nav-link {{ request()->routeIs('tours.*') ? 'active' : '' }}">
                                Tours <i class="fas fa-chevron-down"></i>
                            </a>
                            <div class="dropdown-menu">
                                <div class="dropdown-scroll-wrapper">
                                    <button class="dropdown-scroll-btn scroll-up" onclick="scrollDropdown(this, 'up')" aria-label="Scroll up">
                                        <i class="fas fa-chevron-up"></i>
                                    </button>
                                    <div class="dropdown-content" id="tours-dropdown-content">
                                        <div class="dropdown-header">
                                            <h3>Featured Tours</h3>
                                            <a href="{{ route('tours.index') }}" class="view-all">View All <i class="fas fa-arrow-right"></i></a>
                                        </div>
                                        <div class="dropdown-grid">
                                            @forelse($navTours as $tour)
                                            <a href="{{ route('tours.show', $tour['slug']) }}" class="dropdown-item">
                                                <div class="dropdown-item-image">
                                                    <img src="{{ $tour['image'] }}" alt="{{ $tour['name'] }}">
                                                </div>
                                                <div class="dropdown-item-content">
                                                    <h4>{{ $tour['name'] }}</h4>
                                                    <div class="dropdown-item-meta">
                                                        <span><i class="fas fa-clock"></i> {{ $tour['duration_days'] }} Days</span>
                                                        <span><i class="fas fa-dollar-sign"></i> ${{ $tour['price'] }}</span>
                                                    </div>
                                                </div>
                                            </a>
                                            @empty
                                            <div class="dropdown-empty">
                                                <p>No tours available at the moment.</p>
                                            </div>
                                            @endforelse
                                        </div>
                                    </div>
                                    <button class="dropdown-scroll-btn scroll-down" onclick="scrollDropdown(this, 'down')" aria-label="Scroll down">
                                        <i class="fas fa-chevron-down"></i>
                                    </button>
                                </div>
                            </div>
                        </li>
                        
                        <!-- Destinations Dropdown -->
                        <li class="nav-item-dropdown">
                            <a href="{{ route('destinations.index') }}" class="nav-link {{ request()->routeIs('destinations.*') ? 'active' : '' }}">
                                Destinations <i class="fas fa-chevron-down"></i>
                            </a>
                            <div class="dropdown-menu">
                                <div class="dropdown-scroll-wrapper">
                                    <button class="dropdown-scroll-btn scroll-up" onclick="scrollDropdown(this, 'up')" aria-label="Scroll up">
                                        <i class="fas fa-chevron-up"></i>
                                    </button>
                                    <div class="dropdown-content" id="destinations-dropdown-content">
                                        <div class="dropdown-header">
                                            <h3>Popular Destinations</h3>
                                            <a href="{{ route('destinations.index') }}" class="view-all">View All <i class="fas fa-arrow-right"></i></a>
                                        </div>
                                        <div class="dropdown-grid">
                                            @forelse($navDestinations as $destination)
                                            <a href="{{ route('destinations.show', $destination['slug']) }}" class="dropdown-item">
                                                <div class="dropdown-item-image">
                                                    <img src="{{ $destination['image'] }}" alt="{{ $destination['name'] }}">
                                                </div>
                                                <div class="dropdown-item-content">
                                                    <h4>{{ $destination['name'] }}</h4>
                                                    <span class="dropdown-item-link">Explore <i class="fas fa-arrow-right"></i></span>
                                                </div>
                                            </a>
                                            @empty
                                            <div class="dropdown-empty">
                                                <p>No destinations available at the moment.</p>
                                            </div>
                                            @endforelse
                                        </div>
                                    </div>
                                    <button class="dropdown-scroll-btn scroll-down" onclick="scrollDropdown(this, 'down')" aria-label="Scroll down">
                                        <i class="fas fa-chevron-down"></i>
                                    </button>
                                </div>
                            </div>
                        </li>
                        
                        <li><a href="{{ route('about') }}" class="nav-link {{ request()->routeIs('about') ? 'active' : '' }}">About</a></li>
                        <li><a href="{{ route('contact') }}" class="nav-link {{ request()->routeIs('contact') ? 'active' : '' }}">Contact</a></li>
                    </ul>
                </div>
                
                <div class="header-actions">
                    <a href="{{ route('booking') }}" class="btn-cta">
                        <span>Book Now</span>
                        <i class="fas fa-calendar-alt"></i>
                    </a>
                    
                    <button class="mobile-toggle" id="mobileToggle">
                        <div class="hamburger">
                            <span></span>
                        </div>
                    </button>
                </div>
            </div>
            
            <div class="mobile-nav" id="mobileNav">
                <ul class="mobile-menu">
                    <li><a href="{{ route('home') }}">Home</a></li>
                    <li><a href="{{ route('safaris') }}">Safaris</a></li>
                    <li><a href="{{ route('tours.index') }}">Tours</a></li>
                    <li><a href="{{ route('destinations.index') }}">Destinations</a></li>
                    <li><a href="{{ route('about') }}">About</a></li>
                    <li><a href="{{ route('contact') }}">Contact</a></li>
                </ul>
            </div>
        </div>
    </nav>
</header>
