<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Football Tournament Management')</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <!-- Bootstrap 5 CSS CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Animate.css -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
    
    <!-- Custom CSS -->
    <style>
        :root {
            --primary-color: #2563eb;       /* New primary (lighter blue from gradient) */
            --secondary-color: #1e40af;    /* New secondary (darker blue from gradient) */
            --accent-color: #e74c3c;       /* Keeping the original accent color (red) */
            --light-color: #f8fafc;        /* Lighter background color */
            --dark-color: #1e293b;         /* Darker text color that complements the blues */
            
            /* Optional: You can add gradient variables if you'll reuse them */
            --blue-gradient: linear-gradient(135deg, #1e40af, #2563eb);
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f8f9fa;
            color: var(--dark-color);
        }
        
        .navbar {
            background-color: var(--secondary-color);
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        
        .navbar-brand {
            font-weight: 700;
            color: white;
        }
        
        .navbar-brand img {
            height: 30px;
            margin-right: 10px;
        }
        
        .hero-section {
            background: linear-gradient(135deg, var(--secondary-color), var(--primary-color));
            color: white;
            padding: 5rem 0;
            text-align: center;
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
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><circle cx="25" cy="25" r="2" fill="white" opacity="0.1"/><circle cx="75" cy="75" r="1" fill="white" opacity="0.1"/><circle cx="50" cy="10" r="1.5" fill="white" opacity="0.1"/></svg>');
            animation: float 20s ease-in-out infinite;
        }
        
        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-20px); }
        }
        
        .page-header {
            background: linear-gradient(135deg, var(--secondary-color), var(--primary-color));
            color: white;
            padding: 3rem 0;
            margin-bottom: 2rem;
        }
        
        .stats-card {
            background: white;
            border-radius: 15px;
            padding: 2rem;
            text-align: center;
            box-shadow: 0 5px 15px rgba(0,0,0,0.05);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            border: none;
        }
        
        .stats-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0,0,0,0.1);
        }
        
        .stats-number {
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
        }
        
        .stats-label {
            color: #666;
            font-size: 1rem;
            font-weight: 500;
        }
        
        .card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.05);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            overflow: hidden;
        }
        
        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0,0,0,0.1);
        }
        
        .bg-accent {
            background-color: var(--accent-color) !important;
        }
        
        .text-primary {
            color: var(--primary-color) !important;
        }
        
        .text-secondary {
            color: var(--secondary-color) !important;
        }
        
        .text-accent {
            color: var(--accent-color) !important;
        }
        
        .btn-primary {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
        }
        
        .btn-primary:hover {
            background-color: #2980b9;
            border-color: #2980b9;
        }
        
        .btn-accent {
            background-color: var(--accent-color);
            border-color: var(--accent-color);
            color: white;
        }
        
        .btn-accent:hover {
            background-color: #c0392b;
            border-color: #c0392b;
            color: white;
        }
        
        .feature-icon {
            font-size: 2.5rem;
            color: var(--primary-color);
            margin-bottom: 1rem;
        }
        
        .team-logo {
            width: 60px;
            height: 60px;
            object-fit: contain;
            transition: transform 0.3s ease;
        }
        
        .team-logo:hover {
            transform: scale(1.1);
        }
        
        .player-card {
            position: relative;
            overflow: hidden;
            border-radius: 10px;
            height: 100%;
        }
        
        .player-img {
            width: 100%;
            height: 250px;
            object-fit: cover;
            transition: transform 0.5s ease;
        }
        
        .player-card:hover .player-img {
            transform: scale(1.1);
        }
        
        .player-overlay {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            background: linear-gradient(135deg, #1e40af, #2563eb);
            padding: 20px;
            color: white;
        }
        
        .player-number {
            position: absolute;
            top: 10px;
            right: 10px;
            background-color: var(--accent-color);
            color: white;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
        }
        
        .match-card {
            background: white;
            border-radius: 15px;
            padding: 1.5rem;
            box-shadow: 0 5px 15px rgba(0,0,0,0.05);
            transition: transform 0.3s ease;
        }
        
        .match-card:hover {
            transform: translateY(-5px);
        }
        
        .match-teams {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 1rem;
        }
        
        .team-info {
            text-align: center;
            flex: 1;
        }
        
        .vs-text {
            margin: 0 2rem;
            font-weight: bold;
            color: var(--secondary-color);
        }
        
        .table-responsive {
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 5px 15px rgba(0,0,0,0.05);
        }
        
        .table thead {
            background-color: var(--primary-color);
            color: white;
        }
        
        .table tbody tr:hover {
            background-color: rgba(37, 99, 235, 0.05);
        }
        
        .slide-up {
            animation: slideUp 0.8s ease-out forwards;
            opacity: 0;
            transform: translateY(30px);
        }
        
        @keyframes slideUp {
            from { 
                opacity: 0;
                transform: translateY(30px);
            }
            to { 
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        /* Responsive adjustments */
        @media (max-width: 768px) {
            .hero-section {
                padding: 3rem 0;
            }
            
            .player-img {
                height: 200px;
            }
        }

        .footer {
            background-color: var(--dark-color);
            color: white;
            padding: 3rem 0 2rem;
            margin-top: 5rem;
        }
        
        .footer h5 {
            color: var(--primary-color);
            margin-bottom: 1rem;
        }
        
        .footer a {
            color: #ccc;
            text-decoration: none;
            transition: color 0.3s ease;
        }
        
        .footer a:hover {
            color: var(--primary-color);
        }

        @stack('styles')
    </style>
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark sticky-top">
        <div class="container">
            <a class="navbar-brand" href="{{ route('fuma.index') }}">
                <i class="fas fa-futbol me-2"></i> FUMA
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('fuma.index') ? 'active' : '' }}" href="{{ route('fuma.index') }}">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('fuma.tournaments*') ? 'active' : '' }}" href="{{ route('fuma.tournaments') }}">Tournaments</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('fuma.teams*') ? 'active' : '' }}" href="{{ route('fuma.teams') }}">Teams</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('fuma.matches*') ? 'active' : '' }}" href="{{ route('fuma.matches') }}">Matches</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('fuma.players*') ? 'active' : '' }}" href="{{ route('fuma.players') }}">Players</a>
                    </li>
                    @auth
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown">
                                <i class="fas fa-user me-1"></i> {{ auth()->user()->name }}
                            </a>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="{{ route('dashboard') }}"><i class="fas fa-tachometer-alt me-2"></i> Dashboard</a></li>
                                <li><a class="dropdown-item" href="{{ route('profile.edit') }}"><i class="fas fa-user-edit me-2"></i> Profile</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <form action="{{ route('logout') }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="dropdown-item"><i class="fas fa-sign-out-alt me-2"></i> Logout</button>
                                    </form>
                                </li>
                            </ul>
                        </li>
                    @else
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('login') }}">
                                <i class="fas fa-sign-in-alt me-1"></i> Login
                            </a>
                        </li>
                    @endauth
                </ul>
            </div>
        </div>
    </nav>

    @yield('content')

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="row">
                <div class="col-lg-4 mb-4">
                    <h5><i class="fas fa-futbol me-2"></i> FUMA</h5>
                    <p>Football Tournament Management System - Managing your football tournaments with ease and precision.</p>
                    <div class="social-links">
                        <a href="#" class="me-3"><i class="fab fa-facebook-f"></i></a>
                        <a href="#" class="me-3"><i class="fab fa-twitter"></i></a>
                        <a href="#" class="me-3"><i class="fab fa-instagram"></i></a>
                        <a href="#"><i class="fab fa-youtube"></i></a>
                    </div>
                </div>
                <div class="col-lg-2 col-md-6 mb-4">
                    <h5>Tournaments</h5>
                    <ul class="list-unstyled">
                        <li><a href="{{ route('fuma.tournaments') }}">All Tournaments</a></li>
                        <li><a href="{{ route('fuma.tournaments', ['status' => 'upcoming']) }}">Upcoming</a></li>
                        <li><a href="{{ route('fuma.tournaments', ['status' => 'ongoing']) }}">Ongoing</a></li>
                        <li><a href="{{ route('fuma.tournaments', ['status' => 'completed']) }}">Completed</a></li>
                    </ul>
                </div>
                <div class="col-lg-2 col-md-6 mb-4">
                    <h5>Teams & Players</h5>
                    <ul class="list-unstyled">
                        <li><a href="{{ route('fuma.teams') }}">All Teams</a></li>
                        <li><a href="{{ route('fuma.players') }}">All Players</a></li>
                        <li><a href="{{ route('fuma.matches') }}">Matches</a></li>
                    </ul>
                </div>
                <div class="col-lg-4 mb-4">
                    <h5>Contact Info</h5>
                    <p><i class="fas fa-map-marker-alt me-2"></i> Football Management Center</p>
                    <p><i class="fas fa-phone me-2"></i> +62 123 456 7890</p>
                    <p><i class="fas fa-envelope me-2"></i> info@fuma.com</p>
                </div>
            </div>
            <hr class="my-4">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <p class="mb-0">&copy; {{ date('Y') }} FUMA. All rights reserved.</p>
                </div>
                <div class="col-md-6 text-md-end">
                    <p class="mb-0">Made with <i class="fas fa-heart text-danger"></i> for Football</p>
                </div>
            </div>
        </div>
    </footer>

    <!-- Bootstrap 5 JS CDN -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Custom JavaScript -->
    <script>
        // Add smooth scrolling for anchor links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth'
                    });
                }
            });
        });

        // Animation on scroll
        const observerOptions = {
            threshold: 0.1,
            rootMargin: '0px 0px -50px 0px'
        };

        const observer = new IntersectionObserver(function(entries) {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.style.animationDelay = Math.random() * 0.5 + 's';
                    entry.target.classList.add('slide-up');
                }
            });
        }, observerOptions);

        // Observe all cards
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.card, .stats-card').forEach(card => {
                observer.observe(card);
            });
        });
    </script>

    @stack('scripts')
</body>
</html>