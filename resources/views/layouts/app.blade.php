<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Alumni Association Portal')</title>
    <meta name="description" content="@yield('description', 'Connect with fellow alumni, attend events, and grow your professional network through our comprehensive alumni association portal.')">
    <meta name="keywords" content="alumni, association, networking, events, university, graduates">
    <meta name="author" content="Alumni Association">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:300,400,500,600,700,800&display=swap" rel="stylesheet" />
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Custom CSS -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    
    <!-- Additional Head Content -->
    @stack('styles')
    @yield('head')

    <style>
        /* Modern Alumni Portal Styles */
        :root {
            --primary-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            --secondary-gradient: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
            --success-gradient: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
            --warning-gradient: linear-gradient(135deg, #fa709a 0%, #fee140 100%);
            --primary-color: #667eea;
            --secondary-color: #764ba2;
            --text-color: #333;
            --text-muted: #666;
            --bg-light: #f8f9fa;
            --border-color: #e0e0e0;
            --shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
            --shadow-hover: 0 15px 35px rgba(0, 0, 0, 0.15);
            --border-radius: 15px;
            --transition: all 0.3s ease;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            line-height: 1.6;
            color: var(--text-color);
            background: var(--primary-gradient);
            min-height: 100vh;
        }

        /* Header Styles */
        .header {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            box-shadow: var(--shadow);
            position: sticky;
            top: 0;
            z-index: 1000;
            transition: var(--transition);
        }

        .header-content {
            max-width: 1200px;
            margin: 0 auto;
            padding: 1rem 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .logo {
            font-size: 1.8rem;
            font-weight: 800;
            color: var(--primary-color);
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            transition: var(--transition);
        }

        .logo:hover {
            color: var(--secondary-color);
            text-decoration: none;
            transform: scale(1.05);
        }

        .nav-menu {
            display: flex;
            list-style: none;
            gap: 2rem;
            align-items: center;
        }

        .nav-menu a {
            text-decoration: none;
            color: var(--text-color);
            font-weight: 500;
            transition: var(--transition);
            padding: 0.5rem 1rem;
            border-radius: 25px;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .nav-menu a:hover {
            color: var(--primary-color);
            background: rgba(102, 126, 234, 0.1);
            text-decoration: none;
            transform: translateY(-2px);
        }

        .nav-menu .dropdown {
            position: relative;
        }

        .dropdown-menu {
            position: absolute;
            top: 100%;
            right: 0;
            background: white;
            border-radius: var(--border-radius);
            box-shadow: var(--shadow-hover);
            padding: 1rem 0;
            min-width: 200px;
            opacity: 0;
            visibility: hidden;
            transform: translateY(-10px);
            transition: var(--transition);
            border: 1px solid var(--border-color);
        }

        .dropdown:hover .dropdown-menu {
            opacity: 1;
            visibility: visible;
            transform: translateY(0);
        }

        .dropdown-menu a {
            padding: 0.75rem 1.5rem;
            border-radius: 0;
            display: block;
            color: var(--text-color);
        }

        .dropdown-menu a:hover {
            background: var(--bg-light);
            color: var(--primary-color);
        }

        /* Mobile Navigation */
        .mobile-menu-toggle {
            display: none;
            background: none;
            border: none;
            font-size: 1.5rem;
            color: var(--primary-color);
            cursor: pointer;
        }

        /* Main Content */
        .main-content {
            flex: 1;
            padding: 2rem 0;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 2rem;
        }

        /* Cards */
        .card {
            background: white;
            border-radius: var(--border-radius);
            box-shadow: var(--shadow);
            margin-bottom: 2rem;
            overflow: hidden;
            transition: var(--transition);
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: var(--shadow-hover);
        }

        .card-header {
            background: var(--primary-gradient);
            color: white;
            padding: 1.5rem 2rem;
            font-weight: 600;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .card-body {
            padding: 2rem;
        }

        /* Buttons */
        .btn {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.75rem 2rem;
            background: var(--primary-gradient);
            color: white;
            text-decoration: none;
            border-radius: 25px;
            border: none;
            cursor: pointer;
            font-weight: 500;
            font-size: 0.95rem;
            transition: var(--transition);
            text-align: center;
        }

        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
            color: white;
            text-decoration: none;
        }

        .btn-secondary {
            background: var(--secondary-gradient);
        }

        .btn-success {
            background: var(--success-gradient);
        }

        .btn-warning {
            background: var(--warning-gradient);
        }

        .btn-outline {
            background: transparent;
            border: 2px solid var(--primary-color);
            color: var(--primary-color);
        }

        .btn-outline:hover {
            background: var(--primary-color);
            color: white;
        }

        .btn-large {
            padding: 1rem 2.5rem;
            font-size: 1.1rem;
        }

        .btn-small {
            padding: 0.5rem 1rem;
            font-size: 0.875rem;
        }

        /* Alerts */
        .alert {
            padding: 1rem 1.5rem;
            border-radius: var(--border-radius);
            margin-bottom: 1.5rem;
            border: 1px solid transparent;
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .alert-success {
            background: #d4edda;
            border-color: #c3e6cb;
            color: #155724;
        }

        .alert-danger {
            background: #f8d7da;
            border-color: #f5c6cb;
            color: #721c24;
        }

        .alert-warning {
            background: #fff3e0;
            border-color: #ffcc02;
            color: #f57c00;
        }

        .alert-info {
            background: #d1ecf1;
            border-color: #bee5eb;
            color: #0c5460;
        }

        /* Footer */
        .footer {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            margin-top: 3rem;
            padding: 3rem 0 2rem;
        }

        .footer-content {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 2rem;
        }

        .footer-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 2rem;
            margin-bottom: 2rem;
        }

        .footer-section h5 {
            color: var(--primary-color);
            margin-bottom: 1rem;
            font-weight: 600;
        }

        .footer-section ul {
            list-style: none;
        }

        .footer-section ul li {
            margin-bottom: 0.5rem;
        }

        .footer-section a {
            color: var(--text-muted);
            text-decoration: none;
            transition: var(--transition);
        }

        .footer-section a:hover {
            color: var(--primary-color);
        }

        .footer-bottom {
            text-align: center;
            padding-top: 2rem;
            border-top: 1px solid var(--border-color);
            color: var(--text-muted);
        }

        /* Animations */
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .fade-in {
            animation: fadeIn 0.6s ease-out;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .header-content {
                padding: 1rem;
            }

            .mobile-menu-toggle {
                display: block;
            }

            .nav-menu {
                display: none;
                position: absolute;
                top: 100%;
                left: 0;
                right: 0;
                background: white;
                box-shadow: var(--shadow);
                flex-direction: column;
                padding: 1rem;
                gap: 0.5rem;
            }

            .nav-menu.active {
                display: flex;
            }

            .container {
                padding: 0 1rem;
            }

            .footer-grid {
                grid-template-columns: 1fr;
                text-align: center;
            }
        }

        /* Loading States */
        .loading {
            opacity: 0.6;
            pointer-events: none;
        }

        .spinner {
            display: inline-block;
            width: 20px;
            height: 20px;
            border: 2px solid #f3f3f3;
            border-top: 2px solid var(--primary-color);
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        /* Custom Scrollbar */
        ::-webkit-scrollbar {
            width: 8px;
        }

        ::-webkit-scrollbar-track {
            background: #f1f1f1;
        }

        ::-webkit-scrollbar-thumb {
            background: var(--primary-gradient);
            border-radius: 4px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: var(--secondary-gradient);
        }
    </style>
</head>
<body>
    <div id="app" class="min-h-screen flex flex-col">
        <!-- Header -->
        <header class="header">
            <div class="header-content">
                <a href="{{ route('home') }}" class="logo">
                    <i class="fas fa-graduation-cap"></i>
                    {{ config('app.name', 'Alumni Portal') }}
                </a>
                
                <nav>
                    <button class="mobile-menu-toggle" onclick="toggleMobileMenu()">
                        <i class="fas fa-bars"></i>
                    </button>
                    
                    <ul class="nav-menu" id="navMenu">
                        <li><a href="{{ route('home') }}"><i class="fas fa-home"></i> Home</a></li>
                        <li><a href="{{ route('alumni.directory') }}"><i class="fas fa-users"></i> Directory</a></li>
                        <li><a href="{{ route('events.index') }}"><i class="fas fa-calendar"></i> Events</a></li>
                        <li><a href="{{ route('news.index') }}"><i class="fas fa-newspaper"></i> News</a></li>
                        <li><a href="{{ route('gallery.index') }}"><i class="fas fa-images"></i> Gallery</a></li>
                        
                        @guest
                            <li><a href="{{ route('login') }}"><i class="fas fa-sign-in-alt"></i> Login</a></li>
                            <li><a href="{{ route('register') }}"><i class="fas fa-user-plus"></i> Register</a></li>
                        @else
                            <li class="dropdown">
                                <a href="#" class="dropdown-toggle">
                                    <img src="{{ auth()->user()->profile_picture_url }}" 
                                         alt="{{ auth()->user()->full_name }}" 
                                         style="width: 32px; height: 32px; border-radius: 50%; margin-right: 0.5rem;">
                                    {{ auth()->user()->first_name }}
                                    <i class="fas fa-chevron-down" style="font-size: 0.8rem;"></i>
                                </a>
                                <div class="dropdown-menu">
                                    <a href="{{ route('profile.show') }}"><i class="fas fa-user"></i> My Profile</a>
                                    <a href="{{ route('messages.index') }}"><i class="fas fa-envelope"></i> Messages</a>
                                    <a href="{{ route('events.my-events') }}"><i class="fas fa-calendar-check"></i> My Events</a>
                                    @if(auth()->user()->isAdmin())
                                        <hr style="margin: 0.5rem 0; border: none; height: 1px; background: var(--border-color);">
                                        <a href="{{ route('admin.dashboard') }}"><i class="fas fa-cog"></i> Admin Panel</a>
                                    @endif
                                    <hr style="margin: 0.5rem 0; border: none; height: 1px; background: var(--border-color);">
                                    <a href="{{ route('logout') }}" 
                                       onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                        <i class="fas fa-sign-out-alt"></i> Logout
                                    </a>
                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                        @csrf
                                    </form>
                                </div>
                            </li>
                        @endguest
                    </ul>
                </nav>
            </div>
        </header>

        <!-- Main Content -->
        <main class="main-content flex-1">
            <!-- Flash Messages -->
            @if(session('success'))
                <div class="container">
                    <div class="alert alert-success">
                        <i class="fas fa-check-circle"></i>
                        {{ session('success') }}
                    </div>
                </div>
            @endif

            @if(session('error'))
                <div class="container">
                    <div class="alert alert-danger">
                        <i class="fas fa-exclamation-circle"></i>
                        {{ session('error') }}
                    </div>
                </div>
            @endif

            @if(session('warning'))
                <div class="container">
                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-triangle"></i>
                        {{ session('warning') }}
                    </div>
                </div>
            @endif

            @if(session('info'))
                <div class="container">
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle"></i>
                        {{ session('info') }}
                    </div>
                </div>
            @endif

            <!-- Page Content -->
            @yield('content')
        </main>

        <!-- Footer -->
        <footer class="footer">
            <div class="footer-content">
                <div class="footer-grid">
                    <div class="footer-section">
                        <h5><i class="fas fa-graduation-cap"></i> Alumni Portal</h5>
                        <p>Connecting graduates, fostering relationships, and building a stronger community for lifelong success.</p>
                        <div style="margin-top: 1rem;">
                            <a href="#" style="margin-right: 1rem; font-size: 1.5rem;"><i class="fab fa-facebook"></i></a>
                            <a href="#" style="margin-right: 1rem; font-size: 1.5rem;"><i class="fab fa-twitter"></i></a>
                            <a href="#" style="margin-right: 1rem; font-size: 1.5rem;"><i class="fab fa-linkedin"></i></a>
                            <a href="#" style="margin-right: 1rem; font-size: 1.5rem;"><i class="fab fa-instagram"></i></a>
                        </div>
                    </div>
                    
                    <div class="footer-section">
                        <h5>Quick Links</h5>
                        <ul>
                            <li><a href="{{ route('about') }}"><i class="fas fa-info-circle"></i> About Us</a></li>
                            <li><a href="{{ route('contact') }}"><i class="fas fa-phone"></i> Contact</a></li>
                            <li><a href="{{ route('careers') }}"><i class="fas fa-briefcase"></i> Career Center</a></li>
                            <li><a href="{{ route('donate') }}"><i class="fas fa-heart"></i> Donate</a></li>
                        </ul>
                    </div>
                    
                    <div class="footer-section">
                        <h5>Resources</h5>
                        <ul>
                            <li><a href="{{ route('help') }}"><i class="fas fa-question-circle"></i> Help Center</a></li>
                            <li><a href="{{ route('privacy') }}"><i class="fas fa-shield-alt"></i> Privacy Policy</a></li>
                            <li><a href="{{ route('terms') }}"><i class="fas fa-file-contract"></i> Terms of Service</a></li>
                            <li><a href="{{ route('api.docs') }}"><i class="fas fa-code"></i> API Documentation</a></li>
                        </ul>
                    </div>
                    
                    <div class="footer-section">
                        <h5>Contact Info</h5>
                        <p>
                            <i class="fas fa-envelope"></i> {{ config('mail.from.address') }}<br>
                            <i class="fas fa-phone"></i> +1 (555) 123-4567<br>
                            <i class="fas fa-map-marker-alt"></i> University Campus<br>
                            123 Alumni Drive, College Town, ST 12345
                        </p>
                    </div>
                </div>
                
                <div class="footer-bottom">
                    <p>&copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved. 
                       | Built with <i class="fas fa-heart" style="color: #ff6b6b;"></i> for our alumni community</p>
                </div>
            </div>
        </footer>
    </div>

    <!-- JavaScript -->
    <script src="{{ asset('js/app.js') }}"></script>
    
    <script>
        // Mobile menu toggle
        function toggleMobileMenu() {
            const navMenu = document.getElementById('navMenu');
            navMenu.classList.toggle('active');
        }

        // Auto-hide alerts after 5 seconds
        document.addEventListener('DOMContentLoaded', function() {
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(alert => {
                setTimeout(() => {
                    alert.style.opacity = '0';
                    alert.style.transform = 'translateY(-20px)';
                    setTimeout(() => {
                        alert.remove();
                    }, 300);
                }, 5000);
            });

            // Add fade-in animation to main content
            const mainContent = document.querySelector('.main-content');
            if (mainContent) {
                mainContent.classList.add('fade-in');
            }
        });

        // Smooth scrolling for anchor links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        });

        // Loading state for forms
        document.querySelectorAll('form').forEach(form => {
            form.addEventListener('submit', function() {
                const submitButton = form.querySelector('button[type="submit"], input[type="submit"]');
                if (submitButton) {
                    submitButton.classList.add('loading');
                    submitButton.innerHTML = '<span class="spinner"></span> Processing...';
                    submitButton.disabled = true;
                }
            });
        });
    </script>

    <!-- Additional Scripts -->
    @stack('scripts')
    @yield('scripts')
</body>
</html>