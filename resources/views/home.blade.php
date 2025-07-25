@extends('layouts.app')

@section('title', 'Home - Alumni Association Portal')
@section('description', 'Join thousands of alumni in our thriving community. Connect, network, attend events, and advance your career through meaningful relationships.')

@push('styles')
<style>
    .hero-section {
        background: linear-gradient(135deg, rgba(102, 126, 234, 0.9) 0%, rgba(118, 75, 162, 0.9) 100%), 
                    url('{{ asset('images/hero-bg.jpg') }}');
        background-size: cover;
        background-position: center;
        color: white;
        padding: 4rem 0;
        text-align: center;
        margin-bottom: 3rem;
        border-radius: var(--border-radius);
        position: relative;
        overflow: hidden;
    }

    .hero-section::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(0, 0, 0, 0.2);
        z-index: 1;
    }

    .hero-content {
        position: relative;
        z-index: 2;
    }

    .hero-title {
        font-size: 3.5rem;
        font-weight: 800;
        margin-bottom: 1rem;
        text-shadow: 2px 2px 4px rgba(0,0,0,0.3);
        background: linear-gradient(45deg, #fff, #f0f8ff);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }

    .hero-subtitle {
        font-size: 1.3rem;
        margin-bottom: 2rem;
        opacity: 0.95;
        line-height: 1.6;
    }

    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 2rem;
        margin: 3rem 0;
    }

    .stat-card {
        background: white;
        border-radius: var(--border-radius);
        padding: 2rem;
        text-align: center;
        box-shadow: var(--shadow);
        transition: var(--transition);
        position: relative;
        overflow: hidden;
    }

    .stat-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 4px;
        background: var(--primary-gradient);
    }

    .stat-card:hover {
        transform: translateY(-10px);
        box-shadow: var(--shadow-hover);
    }

    .stat-icon {
        font-size: 3rem;
        margin-bottom: 1rem;
        background: var(--primary-gradient);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }

    .stat-value {
        font-size: 2.5rem;
        font-weight: 800;
        color: var(--text-color);
        margin-bottom: 0.5rem;
    }

    .stat-label {
        color: var(--text-muted);
        font-weight: 500;
        margin-bottom: 1rem;
    }

    .stat-change {
        font-size: 0.875rem;
        padding: 0.25rem 0.75rem;
        border-radius: 20px;
        font-weight: 500;
        background: #e8f5e8;
        color: #2e7d32;
    }

    .feature-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: 2rem;
        margin: 3rem 0;
    }

    .feature-card {
        background: white;
        border-radius: var(--border-radius);
        overflow: hidden;
        box-shadow: var(--shadow);
        transition: var(--transition);
        text-decoration: none;
        color: inherit;
    }

    .feature-card:hover {
        transform: translateY(-5px);
        box-shadow: var(--shadow-hover);
        text-decoration: none;
        color: inherit;
    }

    .feature-image {
        height: 200px;
        background: var(--primary-gradient);
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 4rem;
        position: relative;
        overflow: hidden;
    }

    .feature-image::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: radial-gradient(circle at 30% 30%, rgba(255,255,255,0.2) 0%, transparent 50%);
    }

    .feature-body {
        padding: 2rem;
    }

    .feature-title {
        font-size: 1.3rem;
        font-weight: 600;
        margin-bottom: 1rem;
        color: var(--text-color);
    }

    .feature-description {
        color: var(--text-muted);
        line-height: 1.6;
        margin-bottom: 1.5rem;
    }

    .events-section {
        margin: 4rem 0;
    }

    .event-card {
        background: white;
        border-radius: var(--border-radius);
        overflow: hidden;
        box-shadow: var(--shadow);
        transition: var(--transition);
        margin-bottom: 1.5rem;
        border-left: 4px solid var(--primary-color);
    }

    .event-card:hover {
        transform: translateX(5px);
        box-shadow: var(--shadow-hover);
    }

    .event-content {
        padding: 1.5rem;
    }

    .event-date {
        background: var(--primary-color);
        color: white;
        padding: 0.5rem 1rem;
        border-radius: 15px;
        font-size: 0.875rem;
        font-weight: 600;
        display: inline-block;
        margin-bottom: 1rem;
    }

    .event-title {
        font-size: 1.2rem;
        font-weight: 600;
        margin-bottom: 0.5rem;
        color: var(--text-color);
    }

    .event-description {
        color: var(--text-muted);
        line-height: 1.5;
        margin-bottom: 1rem;
    }

    .event-meta {
        display: flex;
        gap: 1rem;
        font-size: 0.9rem;
        color: var(--text-muted);
    }

    .testimonial-section {
        background: var(--secondary-gradient);
        color: white;
        padding: 4rem 0;
        margin: 4rem 0;
        border-radius: var(--border-radius);
        position: relative;
        overflow: hidden;
    }

    .testimonial-section::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><circle cx="20" cy="20" r="2" fill="rgba(255,255,255,0.1)"/><circle cx="80" cy="80" r="2" fill="rgba(255,255,255,0.1)"/><circle cx="40" cy="60" r="1" fill="rgba(255,255,255,0.1)"/></svg>');
        opacity: 0.5;
    }

    .testimonial-content {
        position: relative;
        z-index: 2;
    }

    .testimonial-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: 2rem;
        margin-top: 2rem;
    }

    .testimonial-card {
        text-align: center;
        padding: 2rem;
        background: rgba(255, 255, 255, 0.1);
        border-radius: var(--border-radius);
        backdrop-filter: blur(10px);
    }

    .testimonial-avatar {
        width: 80px;
        height: 80px;
        border-radius: 50%;
        margin: 0 auto 1rem;
        font-size: 2rem;
        display: flex;
        align-items: center;
        justify-content: center;
        background: rgba(255, 255, 255, 0.2);
    }

    .cta-section {
        text-align: center;
        margin: 4rem 0;
    }

    .cta-card {
        background: var(--primary-gradient);
        color: white;
        padding: 4rem 2rem;
        border-radius: var(--border-radius);
        position: relative;
        overflow: hidden;
    }

    .cta-card::before {
        content: '';
        position: absolute;
        top: -50%;
        left: -50%;
        width: 200%;
        height: 200%;
        background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 50%);
        animation: pulse 3s ease-in-out infinite;
    }

    @keyframes pulse {
        0%, 100% { transform: scale(1); opacity: 0.5; }
        50% { transform: scale(1.1); opacity: 0.3; }
    }

    .cta-content {
        position: relative;
        z-index: 2;
    }

    @media (max-width: 768px) {
        .hero-title {
            font-size: 2.5rem;
        }
        
        .stats-grid,
        .feature-grid {
            grid-template-columns: 1fr;
        }
        
        .hero-section {
            padding: 3rem 0;
        }
    }
</style>
@endpush

@section('content')
<!-- Hero Section -->
<section class="hero-section">
    <div class="container">
        <div class="hero-content">
            <h1 class="hero-title">Welcome to Alumni Portal</h1>
            <p class="hero-subtitle">
                Connecting Graduates • Building Networks • Creating Opportunities<br>
                Join thousands of alumni worldwide in our thriving community
            </p>
            <div style="margin-top: 2rem;">
                @guest
                    <a href="{{ route('register') }}" class="btn btn-large" style="margin-right: 1rem; background: white; color: var(--primary-color);">
                        <i class="fas fa-user-plus"></i> Join Our Community
                    </a>
                    <a href="{{ route('login') }}" class="btn btn-large btn-outline" style="border-color: white; color: white;">
                        <i class="fas fa-sign-in-alt"></i> Member Login
                    </a>
                @else
                    <a href="{{ route('profile.show') }}" class="btn btn-large" style="margin-right: 1rem; background: white; color: var(--primary-color);">
                        <i class="fas fa-user"></i> My Profile
                    </a>
                    <a href="{{ route('events.index') }}" class="btn btn-large btn-outline" style="border-color: white; color: white;">
                        <i class="fas fa-calendar"></i> Browse Events
                    </a>
                @endguest
            </div>
        </div>
    </div>
</section>

<div class="container">
    <!-- Statistics Section -->
    <section class="stats-section">
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-users"></i>
                </div>
                <div class="stat-value" data-count="{{ $stats['total_alumni'] ?? 1250 }}">0</div>
                <div class="stat-label">Alumni Members</div>
                <div class="stat-change">
                    <i class="fas fa-arrow-up"></i> +12% this month
                </div>
            </div>
            
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-calendar-alt"></i>
                </div>
                <div class="stat-value" data-count="{{ $stats['upcoming_events'] ?? 15 }}">0</div>
                <div class="stat-label">Upcoming Events</div>
                <div class="stat-change">
                    <i class="fas fa-arrow-up"></i> +5 new events
                </div>
            </div>
            
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-graduation-cap"></i>
                </div>
                <div class="stat-value">25+</div>
                <div class="stat-label">Years of Legacy</div>
                <div class="stat-change">
                    Since 1999
                </div>
            </div>
            
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-network-wired"></i>
                </div>
                <div class="stat-value">500+</div>
                <div class="stat-label">Success Stories</div>
                <div class="stat-change">
                    <i class="fas fa-arrow-up"></i> Career advances
                </div>
            </div>
        </div>
    </section>

    <!-- Featured Events Section -->
    <section class="events-section">
        <div class="card-header" style="border-radius: var(--border-radius); margin-bottom: 2rem;">
            <h2><i class="fas fa-star"></i> Upcoming Events</h2>
            <a href="{{ route('events.index') }}" class="btn" style="background: white; color: var(--primary-color);">
                View All Events
            </a>
        </div>
        
        <div class="grid" style="grid-template-columns: repeat(auto-fit, minmax(400px, 1fr)); gap: 1.5rem;">
            @forelse($upcomingEvents ?? [] as $event)
                <div class="event-card">
                    <div class="event-content">
                        <div class="event-date">
                            {{ $event->event_date->format('M d, Y') }}
                        </div>
                        <h3 class="event-title">{{ $event->title }}</h3>
                        <p class="event-description">{{ Str::limit($event->description, 120) }}</p>
                        <div class="event-meta">
                            <span><i class="fas fa-clock"></i> {{ $event->event_date->format('g:i A') }}</span>
                            <span><i class="fas fa-map-marker-alt"></i> {{ $event->location }}</span>
                            <span><i class="fas fa-users"></i> {{ $event->rsvp_count }} attending</span>
                        </div>
                        <div style="margin-top: 1rem;">
                            <a href="{{ route('events.show', $event) }}" class="btn btn-small">
                                <i class="fas fa-info-circle"></i> Learn More
                            </a>
                        </div>
                    </div>
                </div>
            @empty
                <div class="event-card" style="grid-column: 1 / -1; text-align: center; padding: 3rem;">
                    <i class="fas fa-calendar-times" style="font-size: 4rem; color: #ccc; margin-bottom: 1rem;"></i>
                    <h3>No Upcoming Events</h3>
                    <p>Check back soon for exciting events and networking opportunities!</p>
                </div>
            @endforelse
        </div>
    </section>

    <!-- Features Section -->
    <section class="features-section">
        <div class="card-header" style="border-radius: var(--border-radius); margin-bottom: 2rem;">
            <h2><i class="fas fa-rocket"></i> Platform Features</h2>
            <p style="opacity: 0.9; margin: 0;">Discover what makes our alumni community special</p>
        </div>
        
        <div class="feature-grid">
            <a href="{{ route('alumni.directory') }}" class="feature-card">
                <div class="feature-image">
                    <i class="fas fa-users"></i>
                </div>
                <div class="feature-body">
                    <h3 class="feature-title">Alumni Directory</h3>
                    <p class="feature-description">
                        Connect with fellow graduates, search by graduation year, department, or location. 
                        Build your professional network with {{ $stats['total_alumni'] ?? 1250 }}+ alumni.
                    </p>
                    <div class="btn btn-outline btn-small">Explore Directory</div>
                </div>
            </a>
            
            <a href="{{ route('events.index') }}" class="feature-card">
                <div class="feature-image" style="background: var(--secondary-gradient);">
                    <i class="fas fa-calendar-check"></i>
                </div>
                <div class="feature-body">
                    <h3 class="feature-title">Event Management</h3>
                    <p class="feature-description">
                        Stay updated with alumni gatherings, workshops, and networking events. 
                        RSVP and never miss an opportunity to connect.
                    </p>
                    <div class="btn btn-outline btn-small">View Events</div>
                </div>
            </a>
            
            <a href="{{ route('messages.index') }}" class="feature-card">
                <div class="feature-image" style="background: var(--success-gradient);">
                    <i class="fas fa-envelope"></i>
                </div>
                <div class="feature-body">
                    <h3 class="feature-title">Messaging System</h3>
                    <p class="feature-description">
                        Send private messages to other alumni, create groups, and maintain 
                        your professional relationships effortlessly.
                    </p>
                    <div class="btn btn-outline btn-small">Start Messaging</div>
                </div>
            </a>
            
            <a href="{{ route('news.index') }}" class="feature-card">
                <div class="feature-image" style="background: var(--warning-gradient);">
                    <i class="fas fa-newspaper"></i>
                </div>
                <div class="feature-body">
                    <h3 class="feature-title">News & Updates</h3>
                    <p class="feature-description">
                        Stay informed about university news, alumni achievements, 
                        and community updates from around the world.
                    </p>
                    <div class="btn btn-outline btn-small">Read News</div>
                </div>
            </a>
            
            <a href="{{ route('careers.index') }}" class="feature-card">
                <div class="feature-image" style="background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);">
                    <i class="fas fa-briefcase"></i>
                </div>
                <div class="feature-body">
                    <h3 class="feature-title">Career Center</h3>
                    <p class="feature-description">
                        Access job postings from fellow alumni, mentorship programs, 
                        and career development resources to advance your career.
                    </p>
                    <div class="btn btn-outline btn-small">Explore Careers</div>
                </div>
            </a>
            
            <a href="{{ route('gallery.index') }}" class="feature-card">
                <div class="feature-image" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                    <i class="fas fa-images"></i>
                </div>
                <div class="feature-body">
                    <h3 class="feature-title">Photo Gallery</h3>
                    <p class="feature-description">
                        Browse photos from past events, graduation ceremonies, 
                        and community gatherings. Share your own memories!
                    </p>
                    <div class="btn btn-outline btn-small">View Gallery</div>
                </div>
            </a>
        </div>
    </section>

    <!-- Testimonials Section -->
    <section class="testimonial-section">
        <div class="container">
            <div class="testimonial-content">
                <h2 style="text-align: center; margin-bottom: 1rem; font-size: 2.5rem;">
                    <i class="fas fa-quote-left"></i> What Our Alumni Say
                </h2>
                <p style="text-align: center; font-size: 1.1rem; opacity: 0.9;">
                    Hear from successful graduates who've built meaningful connections through our platform
                </p>
                
                <div class="testimonial-grid">
                    <div class="testimonial-card">
                        <div class="testimonial-avatar">👨‍💼</div>
                        <h4 style="margin-bottom: 0.5rem;">John Smith, Class of 2015</h4>
                        <p style="font-style: italic; opacity: 0.9;">
                            "The alumni network helped me land my dream job at Google. The connections 
                            I made here are invaluable for my career growth!"
                        </p>
                    </div>
                    
                    <div class="testimonial-card">
                        <div class="testimonial-avatar">👩‍🔬</div>
                        <h4 style="margin-bottom: 0.5rem;">Sarah Johnson, Class of 2018</h4>
                        <p style="font-style: italic; opacity: 0.9;">
                            "From student to PhD researcher - the mentorship I received through 
                            this platform changed my life completely."
                        </p>
                    </div>
                    
                    <div class="testimonial-card">
                        <div class="testimonial-avatar">👨‍💻</div>
                        <h4 style="margin-bottom: 0.5rem;">Mike Davis, Class of 2012</h4>
                        <p style="font-style: italic; opacity: 0.9;">
                            "Starting my own tech company was possible because of the support 
                            and guidance from fellow alumni in this community."
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Call to Action -->
    <section class="cta-section">
        <div class="cta-card">
            <div class="cta-content">
                <h2 style="font-size: 2.5rem; margin-bottom: 1rem;">Ready to Join Our Community?</h2>
                <p style="font-size: 1.2rem; margin-bottom: 2rem; opacity: 0.9;">
                    Connect with thousands of alumni worldwide and unlock endless opportunities for growth, 
                    networking, and career advancement.
                </p>
                @guest
                    <a href="{{ route('register') }}" class="btn btn-large" style="background: white; color: var(--primary-color); margin-right: 1rem;">
                        <i class="fas fa-rocket"></i> Get Started Today
                    </a>
                    <a href="{{ route('about') }}" class="btn btn-large btn-outline" style="border-color: white; color: white;">
                        <i class="fas fa-info-circle"></i> Learn More
                    </a>
                @else
                    <a href="{{ route('profile.edit') }}" class="btn btn-large" style="background: white; color: var(--primary-color); margin-right: 1rem;">
                        <i class="fas fa-user-edit"></i> Complete Your Profile
                    </a>
                    <a href="{{ route('alumni.directory') }}" class="btn btn-large btn-outline" style="border-color: white; color: white;">
                        <i class="fas fa-users"></i> Find Alumni
                    </a>
                @endguest
            </div>
        </div>
    </section>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Animate statistics counters
    const observerOptions = {
        threshold: 0.5,
        rootMargin: '0px 0px -50px 0px'
    };

    const observer = new IntersectionObserver(function(entries) {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                const statValue = entry.target;
                const finalValue = parseInt(statValue.getAttribute('data-count'));
                animateCounter(statValue, finalValue);
                observer.unobserve(statValue);
            }
        });
    }, observerOptions);

    // Observe all stat values
    document.querySelectorAll('.stat-value[data-count]').forEach(stat => {
        observer.observe(stat);
    });

    function animateCounter(element, target) {
        let current = 0;
        const increment = target / 100;
        const timer = setInterval(() => {
            current += increment;
            if (current >= target) {
                current = target;
                clearInterval(timer);
            }
            element.textContent = Math.floor(current).toLocaleString();
        }, 20);
    }

    // Add entrance animations to cards
    const cards = document.querySelectorAll('.stat-card, .feature-card, .event-card');
    cards.forEach((card, index) => {
        card.style.opacity = '0';
        card.style.transform = 'translateY(30px)';
        
        setTimeout(() => {
            card.style.transition = 'all 0.6s ease';
            card.style.opacity = '1';
            card.style.transform = 'translateY(0)';
        }, index * 100);
    });

    // Parallax effect for hero section
    window.addEventListener('scroll', () => {
        const scrolled = window.pageYOffset;
        const heroSection = document.querySelector('.hero-section');
        if (heroSection) {
            heroSection.style.transform = `translateY(${scrolled * 0.5}px)`;
        }
    });
});
</script>
@endpush