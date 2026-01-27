// Initialize AOS (Animate On Scroll)
if (typeof AOS !== 'undefined') {
    AOS.init({
        duration: 800,
        once: true,
        offset: 100
    });
}

// DOM Elements
const loadingOverlay = document.getElementById('loadingOverlay');
const mobileToggle = document.getElementById('mobileToggle');
const mobileNav = document.getElementById('mobileNav');
const hamburger = document.querySelector('.hamburger');
const backToTop = document.getElementById('backToTop');
const sliderPrev = document.getElementById('sliderPrev');
const sliderNext = document.getElementById('sliderNext');

// Hero Slider
const slides = document.querySelectorAll('.slider-slide');
const totalSlides = slides.length;
let currentSlide = 0; // Always start with first slide (index 0)
let slideInterval;

// Loading Screen
window.addEventListener('load', () => {
    if (loadingOverlay) {
        setTimeout(() => {
            loadingOverlay.classList.add('hidden');
        }, 800);
    }
});

// Mobile Navigation Toggle
if (mobileToggle) {
    mobileToggle.addEventListener('click', () => {
        if (mobileNav) mobileNav.classList.toggle('active');
        if (hamburger) hamburger.classList.toggle('active');
    });
}

// Close mobile nav when clicking a link
document.querySelectorAll('.mobile-menu a').forEach(link => {
    link.addEventListener('click', () => {
        if (mobileNav) mobileNav.classList.remove('active');
        if (hamburger) hamburger.classList.remove('active');
    });
});

// Quick Contact Widget - Icons are always visible, no modal needed

// Back to Top Button
window.addEventListener('scroll', () => {
    if (backToTop) {
        if (window.pageYOffset > 300) {
            backToTop.classList.add('visible');
        } else {
            backToTop.classList.remove('visible');
        }
    }
});

if (backToTop) {
    backToTop.addEventListener('click', () => {
        window.scrollTo({
            top: 0,
            behavior: 'smooth'
        });
    });
}

// Animated Counter for Trust Section and Stats Section
function animateCounter() {
    const counters = document.querySelectorAll('.trust-number, .stat-number');
    const speed = 200; // Lower = faster
    
    counters.forEach(counter => {
        const target = parseFloat(counter.getAttribute('data-count'));
        const current = parseFloat(counter.innerText) || 0;
        const increment = target / speed;
        
        if (current < target) {
            const next = current + increment;
            if (target % 1 === 0) {
                counter.innerText = Math.ceil(next);
            } else {
                counter.innerText = next.toFixed(1);
            }
            setTimeout(() => animateCounter(), 1);
        } else {
            if (target % 1 === 0) {
                counter.innerText = Math.floor(target);
            } else {
                counter.innerText = target.toFixed(1);
            }
        }
    });
}

// Initialize counter when in viewport
const observer = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
        if (entry.isIntersecting) {
            animateCounter();
            observer.unobserve(entry.target);
        }
    });
}, { threshold: 0.5 });

const trustSection = document.querySelector('.trust-section');
if (trustSection) {
    observer.observe(trustSection);
}

const statsSection = document.querySelector('.stats-section');
if (statsSection) {
    observer.observe(statsSection);
}

// Hero Slider Functions
function initSlider() {
    if (!slides.length) return;
    
    // Ensure first slide is active and currentSlide is 0
    currentSlide = 0;
    slides.forEach((slide, index) => {
        if (index === 0) {
            slide.classList.add('active');
        } else {
            slide.classList.remove('active');
        }
    });
    
    // Get the single pagination container (outside slides)
    const pagination = document.getElementById('sliderPagination');
    
    if (!pagination) return;
    
    // Clear existing bullets
    pagination.innerHTML = '';
    
    // Create bullets for all slides
    for (let i = 0; i < totalSlides; i++) {
        const bullet = document.createElement('span');
        bullet.classList.add('slider-pagination-bullet');
        // Only first bullet (index 0) should be active initially
        if (i === 0) {
            bullet.classList.add('active');
        }
        bullet.addEventListener('click', () => goToSlide(i));
        pagination.appendChild(bullet);
    }
    
    // Start autoplay
    startAutoplay();
}

function goToSlide(n) {
    if (!slides.length) return;
    
    // Remove active class from current slide
    slides[currentSlide].classList.remove('active');
    
    // Remove active class from all bullets
    const allBullets = document.querySelectorAll('.slider-pagination-bullet');
    allBullets.forEach(bullet => bullet.classList.remove('active'));
    
    // Update current slide index
    currentSlide = (n + totalSlides) % totalSlides;
    
    // Add active class to new slide
    slides[currentSlide].classList.add('active');
    
    // Add active class to corresponding bullet
    allBullets.forEach((bullet, index) => {
        if (index === currentSlide) {
            bullet.classList.add('active');
        }
    });
    
    // Trigger animations for the new slide
    const activeSlide = slides[currentSlide];
    const content = activeSlide.querySelector('.hero-content');
    const badge = activeSlide.querySelector('.hero-badge');
    const title = activeSlide.querySelector('.hero-title');
    const subtitle = activeSlide.querySelector('.hero-subtitle');
    const actions = activeSlide.querySelector('.hero-actions');
    
    // Remove and re-add classes to trigger animations
    if (content) {
        const animClass = content.className.match(/content-animation-\d+/);
        if (animClass) {
            content.classList.remove(animClass[0]);
            void content.offsetWidth; // Trigger reflow
            content.classList.add(animClass[0]);
        }
    }
    
    if (badge) {
        const animClass = badge.className.match(/badge-animation-\d+/);
        if (animClass) {
            badge.classList.remove(animClass[0]);
            void badge.offsetWidth;
            badge.classList.add(animClass[0]);
        }
    }
    
    if (title) {
        const animClass = title.className.match(/title-animation-\d+/);
        if (animClass) {
            title.classList.remove(animClass[0]);
            void title.offsetWidth;
            title.classList.add(animClass[0]);
        }
    }
    
    if (subtitle) {
        const animClass = subtitle.className.match(/subtitle-animation-\d+/);
        if (animClass) {
            subtitle.classList.remove(animClass[0]);
            void subtitle.offsetWidth;
            subtitle.classList.add(animClass[0]);
        }
    }
    
    if (actions) {
        const animClass = actions.className.match(/actions-animation-\d+/);
        if (animClass) {
            actions.classList.remove(animClass[0]);
            void actions.offsetWidth;
            actions.classList.add(animClass[0]);
        }
    }
    
    // Reset autoplay
    clearInterval(slideInterval);
    startAutoplay();
}

function nextSlide() {
    goToSlide(currentSlide + 1);
}

function prevSlide() {
    goToSlide(currentSlide - 1);
}

function startAutoplay() {
    if (slides.length > 1) {
        slideInterval = setInterval(nextSlide, 5000);
    }
}

// Slider Event Listeners
if (sliderNext) {
    sliderNext.addEventListener('click', nextSlide);
}
if (sliderPrev) {
    sliderPrev.addEventListener('click', prevSlide);
}

// Pause autoplay on hover
const heroSlider = document.getElementById('heroSlider');
if (heroSlider) {
    heroSlider.addEventListener('mouseenter', () => clearInterval(slideInterval));
    heroSlider.addEventListener('mouseleave', startAutoplay);
}

// Search Modal
const openSearchModal = document.getElementById('openSearchModal');
const closeSearchModal = document.getElementById('closeSearchModal');
const searchModal = document.getElementById('searchModal');

if (openSearchModal && searchModal) {
    openSearchModal.addEventListener('click', () => {
        searchModal.classList.add('active');
        document.body.style.overflow = 'hidden';
    });
}

if (closeSearchModal && searchModal) {
    closeSearchModal.addEventListener('click', () => {
        searchModal.classList.remove('active');
        document.body.style.overflow = '';
    });
}

// Close modal when clicking outside
if (searchModal) {
    searchModal.addEventListener('click', (e) => {
        if (e.target === searchModal) {
            searchModal.classList.remove('active');
            document.body.style.overflow = '';
        }
    });
}

// Handle search form submission (prevent default and show message)
const searchForm = document.querySelector('.search-modal-form');
if (searchForm) {
    searchForm.addEventListener('submit', (e) => {
        e.preventDefault();
        // Form submission is handled by the message in the modal
    });
}

// Close modal on Escape key
document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape' && searchModal && searchModal.classList.contains('active')) {
        searchModal.classList.remove('active');
        document.body.style.overflow = '';
    }
});

// Search modal is now simplified - no form submission needed
// The modal displays a message with call-to-action buttons

// Reviews Carousel - Pause on hover
document.addEventListener('DOMContentLoaded', () => {
    const reviewsCarousel = document.getElementById('reviewsCarousel');
    if (reviewsCarousel) {
        // The CSS animation handles the scrolling
        // JavaScript can be used for additional interactions if needed
    }
});

const newsletterForm = document.querySelector('.newsletter-form');
if (newsletterForm) {
    newsletterForm.addEventListener('submit', function(e) {
        e.preventDefault();
        const email = this.querySelector('input[type="email"]').value;
        alert(`Thank you for subscribing with ${email}! You'll receive Tanzania travel tips and exclusive offers soon.`);
        this.reset();
    });
}

// Initialize everything when DOM is loaded
document.addEventListener('DOMContentLoaded', () => {
    initSlider();
    // Initialize counter observer
    if (trustSection) {
        observer.observe(trustSection);
    }
});

// Smooth scrolling for anchor links
document.querySelectorAll('a[href^="#"]').forEach(anchor => {
    anchor.addEventListener('click', function(e) {
        e.preventDefault();
        
        const targetId = this.getAttribute('href');
        if (targetId === '#') return;
        
        const targetElement = document.querySelector(targetId);
        if (targetElement) {
            window.scrollTo({
                top: targetElement.offsetTop - 80,
                behavior: 'smooth'
            });
        }
    });
});

// Dropdown Scroll Functionality
function scrollDropdown(button, direction) {
    const wrapper = button.closest('.dropdown-scroll-wrapper');
    if (!wrapper) return;
    
    const content = wrapper.querySelector('.dropdown-content');
    if (!content) return;
    
    const scrollAmount = 200; // pixels to scroll
    const currentScroll = content.scrollTop;
    const maxScroll = content.scrollHeight - content.clientHeight;
    
    if (direction === 'up') {
        content.scrollTo({
            top: Math.max(0, currentScroll - scrollAmount),
            behavior: 'smooth'
        });
    } else if (direction === 'down') {
        content.scrollTo({
            top: Math.min(maxScroll, currentScroll + scrollAmount),
            behavior: 'smooth'
        });
    }
    
    // Update button states after scroll
    setTimeout(() => {
        updateScrollButtons(wrapper);
    }, 300);
}

// Update scroll button states based on scroll position
function updateScrollButtons(wrapper) {
    const content = wrapper.querySelector('.dropdown-content');
    if (!content) return;
    
    const scrollUp = wrapper.querySelector('.scroll-up');
    const scrollDown = wrapper.querySelector('.scroll-down');
    
    if (scrollUp) {
        scrollUp.disabled = content.scrollTop === 0;
    }
    
    if (scrollDown) {
        const maxScroll = content.scrollHeight - content.clientHeight;
        scrollDown.disabled = content.scrollTop >= maxScroll - 5; // 5px tolerance
    }
}

// Initialize scroll buttons on dropdown hover
document.addEventListener('DOMContentLoaded', () => {
    const dropdowns = document.querySelectorAll('.nav-item-dropdown');
    
    dropdowns.forEach(dropdown => {
        const wrapper = dropdown.querySelector('.dropdown-scroll-wrapper');
        if (!wrapper) return;
        
        const content = wrapper.querySelector('.dropdown-content');
        if (!content) return;
        
        // Show/hide scroll buttons based on content overflow
        const checkScrollButtons = () => {
            const hasOverflow = content.scrollHeight > content.clientHeight;
            const scrollButtons = wrapper.querySelectorAll('.dropdown-scroll-btn');
            
            // Add/remove class for CSS styling
            if (hasOverflow) {
                wrapper.classList.add('has-overflow');
            } else {
                wrapper.classList.remove('has-overflow');
            }
            
            scrollButtons.forEach(btn => {
                if (hasOverflow) {
                    btn.style.opacity = '0.8';
                    btn.style.visibility = 'visible';
                } else {
                    btn.style.opacity = '0';
                    btn.style.visibility = 'hidden';
                }
            });
            
            if (hasOverflow) {
                updateScrollButtons(wrapper);
            }
        };
        
        // Check on load
        checkScrollButtons();
        
        // Check on resize
        window.addEventListener('resize', checkScrollButtons);
        
        // Update buttons on scroll
        content.addEventListener('scroll', () => {
            updateScrollButtons(wrapper);
        });
        
        // Check when dropdown is hovered
        dropdown.addEventListener('mouseenter', () => {
            setTimeout(checkScrollButtons, 100);
        });
    });
});

