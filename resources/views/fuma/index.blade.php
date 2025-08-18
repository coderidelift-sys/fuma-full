<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Football Tournament Management</title>
    <!-- Bootstrap 5 CSS CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Animate.css -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
    <!-- Custom CSS -->

    <!-- tah section -->
    <style>
        :root {
            --primary-color: #2563eb;
            /* New primary (lighter blue from gradient) */
            --secondary-color: #1e40af;
            /* New secondary (darker blue from gradient) */
            --accent-color: #e74c3c;
            /* Keeping the original accent color (red) */
            --light-color: #f8fafc;
            /* Lighter background color */
            --dark-color: #1e293b;
            /* Darker text color that complements the blues */

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
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
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
            margin-bottom: 3rem;
        }

        .card {
            border: none;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
            transition: all 0.3s ease;
            margin-bottom: 20px;
            overflow: hidden;
        }

        .card:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.1);
        }

        .card-img-top {
            height: 180px;
            object-fit: cover;
            transition: transform 0.5s ease;
        }

        .card:hover .card-img-top {
            transform: scale(1.05);
        }

        .card-body {
            padding: 1.5rem;
        }

        .card-title {
            font-weight: 700;
            margin-bottom: 0.75rem;
        }

        .badge {
            font-weight: 500;
            padding: 5px 10px;
            border-radius: 20px;
        }

        .bg-primary {
            background-color: var(--primary-color) !important;
        }

        .bg-secondary {
            background-color: var(--secondary-color) !important;
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
            border-left: 5px solid var(--primary-color);
            transition: all 0.3s ease;
        }

        .match-card:hover {
            border-left-color: var(--accent-color);
        }

        .stadium-card {
            position: relative;
            overflow: hidden;
            height: 250px;
            border-radius: 10px;
        }

        .stadium-img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.5s ease;
        }

        .stadium-card:hover .stadium-img {
            transform: scale(1.05);
        }

        .stadium-overlay {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            background: linear-gradient(transparent, rgba(0, 0, 0, 0.8));
            padding: 20px;
            color: white;
        }

        .stats-card {
            border-radius: 10px;
            padding: 20px;
            text-align: center;
            height: 100%;
            display: flex;
            flex-direction: column;
            justify-content: center;
            transition: all 0.3s ease;
        }

        .stats-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
        }

        .stats-number {
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
        }

        .stats-label {
            font-size: 1rem;
            color: #6c757d;
        }

        footer {
            background-color: var(--secondary-color);
            color: white;
            padding: 3rem 0;
            margin-top: 3rem;
        }

        .social-icon {
            color: white;
            font-size: 1.5rem;
            margin-right: 15px;
            transition: color 0.3s ease;
        }

        .social-icon:hover {
            color: var(--primary-color);
        }

        /* Animation classes */
        .fade-in {
            animation: fadeIn 1s ease-in;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
            }

            to {
                opacity: 1;
            }
        }

        .slide-up {
            animation: slideUp 0.8s ease-out;
        }

        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(50px);
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
    </style>

    <style>
        /* Custom CSS for compact team cards */
        .team-logo-sm {
            width: 60px;
            height: 60px;
            object-fit: contain;
            transition: transform 0.3s ease;
        }

        .team-card {
            transition: all 0.3s ease;
            border: none;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        }

        .team-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }

        .team-card:hover .team-logo-sm {
            transform: scale(1.1);
        }

        .team-card .btn {
            transition: all 0.2s ease;
        }
    </style>

    <style>
        /* Custom CSS for the compact match cards */
        .team-logo-sm {
            width: 30px;
            height: 30px;
            object-fit: contain;
        }

        .match-card {
            transition: all 0.3s ease;
            border-left: 3px solid var(--primary-color);
        }

        .match-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }

        .vs-badge {
            min-width: 40px;
        }

        .text-truncate {
            max-width: 100px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
    </style>
</head>

<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark sticky-top">
        <div class="container">
            <a class="navbar-brand" href="#hero">
                <i class="fas fa-futbol me-2"></i> FUMA
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link active" href="#hero">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#tournaments">Tournaments</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#teams">Teams</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#matches">Matches</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#players">Players</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="login.html">
                            <i class="fas fa-sign-in-alt me-1"></i> Login
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero-section animate__animated animate__fadeIn" id="hero">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6">
                    <h1 class="display-4 fw-bold mb-4">Football Tournament Management System</h1>
                    <p class="lead mb-4">Manage your football tournaments with ease. Track teams, players, matches, and
                        statistics all in one place.</p>
                    <div class="d-flex gap-3">
                        <a href="#tournaments" class="btn btn-accent btn-lg px-4">
                            <i class="fas fa-trophy me-2"></i> View Tournaments
                        </a>
                        <a href="login.html" class="btn btn-outline-light btn-lg px-4">
                            <i class="fas fa-lock me-2"></i> Admin Login
                        </a>
                    </div>
                </div>
                <div class="col-lg-6 d-none d-lg-block">
                    <img
                        src="https://media.istockphoto.com/id/974754900/id/vektor/ilustrasi-vektor-banner-turnamen-sepak-bola-bola-di-latar-belakang-lapangan-sepak-bola.jpg?s=170667a&w=0&k=20&c=-a5zDG5lJGTK0r_N8LotOEZOuDurWzwN0fY4LVl09hQ=">
                </div>
            </div>
        </div>
    </section>

    <!-- Quick Stats -->
    <section class="py-5 bg-light">
        <div class="container">
            <div class="row g-4" id="quick-stats-container"></div>
        </div>
    </section>

    <!-- Compact Featured Tournaments -->
    <section id="tournaments" class="py-4">
        <div class="container">
            <div class="section-header mb-4 text-center">
                <h2 class="fw-bold h4">Featured Tournaments</h2>
                <p class="text-muted small">Browse active competitions</p>
            </div>

            <div class="row g-3" id="featured-tournaments-container"></div>

            <div class="text-center mt-4">
                <a href="{{ route('tournaments.index') }}" class="btn btn-sm btn-primary px-3">
                    <i class="fas fa-list me-1"></i> All Tournaments
                </a>
            </div>
        </div>
    </section>

    <!-- Top Teams -->
    <section id="teams" class="py-5 bg-light">
        <div class="container">
            <div class="section-header mb-4 text-center"> <!-- Reduced margin -->
                <h2 class="fw-bold">Top Teams</h2>
                <p class="text-muted mb-0">The most successful teams in our tournaments</p>
                <!-- Removed bottom margin -->
            </div>

            <div class="row g-3" id="top-teams-container"></div>

            <div class="text-center mt-4"> <!-- Reduced top margin -->
                <a href="{{ route('teams.index') }}" class="btn btn-primary px-4">
                    <i class="fas fa-users me-2"></i> View All Teams
                </a>
            </div>
        </div>
    </section>

    <!-- Upcoming Matches -->
    <section id="matches" class="py-5">
        <div class="container">
            <div class="section-header mb-5 text-center">
                <h2 class="fw-bold">Upcoming Matches</h2>
                <p class="text-muted">Don't miss these exciting matches coming soon</p>
            </div>

            <div class="row g-3" id="upcoming-matches-container"></div>

            <div class="text-center mt-4"> <!-- Smaller top margin -->
                <a href="{{ route('matches.index') }}" class="btn btn-primary px-4">
                    <i class="fas fa-list me-2"></i> View All Matches
                </a>
            </div>
        </div>
    </section>

    <!-- Top Players -->
    <section id="players" class="py-5 bg-light">
        <div class="container">
            <div class="section-header mb-5 text-center">
                <h2 class="fw-bold">Top Players</h2>
                <p class="text-muted">The best performing players in current tournaments</p>
            </div>

            <div class="row g-4" id="top-players-container"></div>

            <div class="text-center mt-5">
                <a href="{{ route('players.index') }}" class="btn btn-primary px-4">
                    <i class="fas fa-users me-2"></i> View All Players
                </a>
            </div>
        </div>
    </section>

    <!-- Featured Stadiums -->
    <section class="py-5">
        <div class="container">
            <div class="section-header mb-5 text-center">
                <h2 class="fw-bold">Featured Stadiums</h2>
                <p class="text-muted">Iconic venues hosting our tournaments</p>
            </div>

            <div class="row g-4" id="venues-container">

            </div>
        </div>
    </section>

    <!-- Features -->
    <section class="py-5 bg-light">
        <div class="container">
            <div class="section-header mb-5 text-center">
                <h2 class="fw-bold">Why Choose Our System</h2>
                <p class="text-muted">Powerful features for tournament management</p>
            </div>

            <div class="row g-4">
                <div class="col-lg-3 col-md-6">
                    <div class="card h-100 animate__animated animate__fadeInUp">
                        <div class="card-body text-center">
                            <div class="feature-icon">
                                <i class="fas fa-trophy"></i>
                            </div>
                            <h5 class="card-title">Tournament Management</h5>
                            <p class="card-text">Create and manage tournaments with multiple stages, groups, and
                                knockout rounds.</p>
                        </div>
                    </div>
                </div>

                <div class="col-lg-3 col-md-6">
                    <div class="card h-100 animate__animated animate__fadeInUp" style="animation-delay: 0.2s;">
                        <div class="card-body text-center">
                            <div class="feature-icon">
                                <i class="fas fa-users"></i>
                            </div>
                            <h5 class="card-title">Team & Player Profiles</h5>
                            <p class="card-text">Comprehensive profiles for teams and players with detailed statistics.
                            </p>
                        </div>
                    </div>
                </div>

                <div class="col-lg-3 col-md-6">
                    <div class="card h-100 animate__animated animate__fadeInUp" style="animation-delay: 0.4s;">
                        <div class="card-body text-center">
                            <div class="feature-icon">
                                <i class="fas fa-futbol"></i>
                            </div>
                            <h5 class="card-title">Live Match Updates</h5>
                            <p class="card-text">Real-time match tracking with goal alerts, cards, and substitutions.
                            </p>
                        </div>
                    </div>
                </div>

                <div class="col-lg-3 col-md-6">
                    <div class="card h-100 animate__animated animate__fadeInUp" style="animation-delay: 0.6s;">
                        <div class="card-body text-center">
                            <div class="feature-icon">
                                <i class="fas fa-chart-bar"></i>
                            </div>
                            <h5 class="card-title">Advanced Statistics</h5>
                            <p class="card-text">Detailed analytics and reports for teams, players, and tournaments.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Call to Action -->
    <section class="py-5 bg-primary text-white">
        <div class="container text-center">
            <h2 class="fw-bold mb-4">Ready to Manage Your Tournament?</h2>
            <p class="lead mb-5">Join thousands of organizers who trust our system for their football tournaments</p>
            <a href="#" class="btn btn-light btn-lg px-5 me-3">
                <i class="fas fa-play me-2"></i> Get Started
            </a>
            <a href="#" class="btn btn-outline-light btn-lg px-5">
                <i class="fas fa-question-circle me-2"></i> Learn More
            </a>
        </div>
    </section>

    <!-- Footer -->
    <footer class="animate__animated animate__fadeIn">
        <div class="container">
            <div class="row">
                <div class="col-lg-4 mb-4">
                    <h5 class="text-white mb-4">Football Tournament Management</h5>
                    <p>The most comprehensive solution for organizing and managing football tournaments of any size.</p>
                    <div class="mt-4">
                        <a href="#" class="social-icon"><i class="fab fa-facebook-f"></i></a>
                        <a href="#" class="social-icon"><i class="fab fa-twitter"></i></a>
                        <a href="#" class="social-icon"><i class="fab fa-instagram"></i></a>
                        <a href="#" class="social-icon"><i class="fab fa-linkedin-in"></i></a>
                    </div>
                </div>

                <div class="col-lg-2 col-md-6 mb-4">
                    <h5 class="text-white mb-4">Quick Links</h5>
                    <ul class="list-unstyled">
                        <li class="mb-2"><a style="text-decoration: none" href="#hero" class="text-white">Home</a></li>
                        <li class="mb-2"><a style="text-decoration: none" href="#tournaments" class="text-white">Tournaments</a></li>
                        <li class="mb-2"><a style="text-decoration: none" href="#teams" class="text-white">Teams</a></li>
                        <li class="mb-2"><a style="text-decoration: none" href="#matches" class="text-white">Matches</a></li>
                        <li class="mb-2"><a style="text-decoration: none" href="#players" class="text-white">Players</a></li>
                    </ul>
                </div>

                <div class="col-lg-3 col-md-6 mb-4">
                    <h5 class="text-white mb-4">Contact Us</h5>
                    <ul class="list-unstyled text-white">
                        <li class="mb-2"><i class="fas fa-map-marker-alt me-2"></i> 123 Football Street, Sports City
                        </li>
                        <li class="mb-2"><i class="fas fa-phone me-2"></i> +1 (234) 567-8900</li>
                        <li class="mb-2"><i class="fas fa-envelope me-2"></i> info@FUMA.com</li>
                    </ul>
                </div>

                <div class="col-lg-3 mb-4">
                    <h5 class="text-white mb-4">Newsletter</h5>
                    <p>Subscribe to our newsletter for the latest updates.</p>
                    <div class="input-group mb-3">
                        <input type="email" class="form-control" placeholder="Your Email">
                        <button class="btn btn-accent" type="button">Subscribe</button>
                    </div>
                </div>
            </div>

            <hr class="my-4 bg-light">

            <div class="row">
                <div class="col-md-6 text-center text-md-start">
                    <p class="mb-0">&copy; 2023 Football Tournament Management System. All rights reserved.</p>
                </div>
                <div class="col-md-6 text-center text-md-end">
                    <a href="#" class="text-white me-3">Privacy Policy</a>
                    <a href="#" class="text-white me-3">Terms of Service</a>
                    <a href="#" class="text-white">FAQ</a>
                </div>
            </div>
        </div>
    </footer>

    <!-- Bootstrap 5 JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Custom JS -->
    <script>
        // Animation on scroll
        document.addEventListener('DOMContentLoaded', function() {
            const animateElements = document.querySelectorAll('.animate__animated');

            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        const animation = entry.target.getAttribute('data-animation');
                        entry.target.classList.add('animate__fadeInUp');
                        observer.unobserve(entry.target);
                    }
                });
            }, {
                threshold: 0.1
            });

            animateElements.forEach(element => {
                observer.observe(element);
            });

            // Smooth scrolling for anchor links with active class toggle
            const offset = 70; // offset dari atas
            const links = document.querySelectorAll('a[href^="#"]');

            function setActiveLink(link) {
                links.forEach(l => l.classList.remove('active'));
                link.classList.add('active');
            }

            // Set link pertama sebagai default active jika belum ada
            if (!document.querySelector('a[href^="#"].active') && links.length > 0) {
                setActiveLink(links[0]);
            }

            links.forEach(link => {
                link.addEventListener('click', function(e) {
                    e.preventDefault();

                    const targetId = this.getAttribute('href');
                    if (targetId === '#') return;

                    // Scroll ke target
                    const targetElement = document.querySelector(targetId);
                    if (targetElement) {
                        window.scrollTo({
                            top: targetElement.offsetTop - offset,
                            behavior: 'smooth'
                        });
                    }

                    // Set active link
                    setActiveLink(this);
                });
            });
        });
    </script>

    <script>
        const fallbackLogos = [
            'https://logos-world.net/wp-content/uploads/2020/05/Chelsea-Emblem.png',
            'https://logos-world.net/wp-content/uploads/2020/06/Real-Madrid-Logo.png',
            'https://logos-world.net/wp-content/uploads/2020/04/Barcelona-Logo.png',
            'https://logos-world.net/wp-content/uploads/2020/06/Liverpool-Logo.png'
        ];

        // Function to get random fallback logo
        const getRandomLogo = () => {
            return fallbackLogos[Math.floor(Math.random() * fallbackLogos.length)];
        };
    </script>
    <script>
        // public/js/app.js
        document.addEventListener('DOMContentLoaded', () => {
            fetch('/homepage-data') // Satu panggilan AJAX ke endpoint gabungan
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
                .then(data => {
                    // Panggil fungsi render untuk setiap bagian data
                    renderQuickStats(data.quickStats);
                    renderFeaturedTournaments(data.featuredTournaments);
                    renderTopTeams(data.topTeams);
                    renderTopPlayers(data.topPlayers);
                    renderUpcomingMatches(data.upcomingMatches);
                    renderVenues(data.venues);
                })
                .catch(error => {
                    console.error('There was a problem fetching the homepage data:', error);
                });
        });

        function renderQuickStats(stats) {
            const container = document.getElementById('quick-stats-container');
            container.innerHTML = `
                <div class="col-md-3">
                    <div class="stats-card bg-white slide-up" style="animation-delay: 0.1s;">
                        <div class="stats-number text-primary">${stats.activeTournaments}</div>
                        <div class="stats-label">Active Tournaments</div>
                        <i class="fas fa-trophy mt-3 text-primary"></i>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stats-card bg-white slide-up" style="animation-delay: 0.2s;">
                        <div class="stats-number text-primary">${stats.registeredTeams}</div>
                        <div class="stats-label">Registered Teams</div>
                        <i class="fas fa-users mt-3 text-primary"></i>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stats-card bg-white slide-up" style="animation-delay: 0.3s;">
                        <div class="stats-number text-primary">${stats.players}</div>
                        <div class="stats-label">Players</div>
                        <i class="fas fa-user mt-3 text-primary"></i>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stats-card bg-white slide-up" style="animation-delay: 0.4s;">
                        <div class="stats-number text-primary">${stats.matchesPlayed}</div>
                        <div class="stats-label">Matches Played</div>
                        <i class="fas fa-futbol mt-3 text-primary"></i>
                    </div>
                </div>
            `;
        }

        function renderFeaturedTournaments(tournaments) {
            const container = document.getElementById('featured-tournaments-container');
            const colClass = tournaments.length < 3 ? 'col-lg-6 col-md-6' : 'col-lg-4 col-md-6';
            const formatDate = (dateString) => {
                const date = new Date(dateString);
                return date.toLocaleDateString('en-US', {
                    month: 'short',
                    day: 'numeric'
                });
            };
            const toTitleCase = (str) => str.charAt(0).toUpperCase() + str.slice(1).toLowerCase();
            const routeDetail = (tournamentId) => `{{ route('tournaments.show', ':id') }}`.replace(':id', tournamentId);

            container.innerHTML = tournaments.map(tournament => `
                <div class="${colClass}">
                    <div class="card h-100 border-0 shadow-sm animate__animated animate__fadeInUp">
                        <div class="card-body p-3">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span class="badge bg-primary small">${toTitleCase(tournament.status)}</span>
                                <span class="text-muted small"><i class="far fa-calendar-alt me-1"></i>${formatDate(tournament.start_date)} - ${formatDate(tournament.end_date)}</span>
                            </div>
                            <h5 class="card-title mb-2">${tournament.name}</h5>
                            <p class="card-text small text-muted mb-3">${tournament.description}</p>
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="text-muted small"><i class="fas fa-users me-1"></i>${tournament.max_teams} Teams</span>
                                <a href="${routeDetail(tournament.id)}" class="btn btn-sm btn-outline-primary">View</a>
                            </div>
                        </div>
                    </div>
                </div>
            `).join('');
        }

        function renderTopTeams(teams) {
            const container = document.getElementById('top-teams-container');
            const routeDetail = (teamId) => `{{ route('teams.show', ':id') }}`.replace(':id', teamId);

            container.innerHTML = teams.map(team => `
                <div class="col-xl-3 col-lg-4 col-md-6">
                    <div class="card team-card h-100">
                        <div class="card-body p-3 text-center">
                            <img src="${team.logo ? team.logo : getRandomLogo()}"
                                alt="Team Logo" class="team-logo-sm mb-2">
                            <h6 class="card-title mb-1">${team.name}</h6>
                            <div class="d-flex justify-content-center small mb-2">
                                <span class="text-muted me-2">
                                    <i class="fas fa-trophy text-warning me-1"></i>${team.trophies_count}
                                </span>
                                <span class="text-muted">
                                    <i class="fas fa-map-marker-alt me-1"></i>${team.city}, ${team.country}
                                </span>
                            </div>
                            <div class="d-flex justify-content-center small mb-2">
                                <span class="badge bg-light text-dark me-1">
                                    <i class="fas fa-user me-1"></i>${team.players_count}
                                </span>
                                <span class="badge bg-light text-dark">
                                    <i class="fas fa-star text-warning me-1"></i>${team.rating}
                                </span>
                            </div>
                            <a href="${routeDetail(team.id)}" class="btn btn-sm btn-outline-primary w-100">
                                View Team
                            </a>
                        </div>
                    </div>
                </div>
            `).join('');
        }

        function renderTopPlayers(players) {
            const container = document.getElementById('top-players-container');
            const truncate = (text, maxLength) => {
                return text.length > maxLength ?
                    text.slice(0, maxLength) + '…' :
                    text;
            };
            const routeDetail = (playerId) => `{{ route('players.show', ':id') }}`.replace(':id', playerId);

            container.innerHTML = players.map(player => `
                <div class="col-lg-3 col-md-6">
                    <div class="player-card animate__animated animate__fadeInUp">
                        <img src="https://images.hdqwalls.com/wallpapers/cristiano-ronaldo-fifa-world-cup-qatar-4k-dx.jpg"
                            alt="Player" class="player-img">
                        <a class="player-overlay" href="${routeDetail(player.id)}" style="text-decoration: none; color: white;">
                            <h5 class="mb-1">${truncate(player.name, 20)}</h5>
                            <p class="mb-2">${player.position} | ${player.team.name}</p>
                            <div class="d-flex justify-content-between">
                                <span><i class="fas fa-futbol me-1"></i> ${player.goals_scored} Goals</span>
                                <span><i class="fas fa-star me-1 text-warning"></i> ${player.rating}</span>
                            </div>
                        </a>
                        <div class="player-number">${player.jersey_number}</div>
                    </div>
                </div>
            `).join('');
        }

        function renderUpcomingMatches(matches) {
            const container = document.getElementById('upcoming-matches-container');
            const toTitleCase = (str) =>
                str
                .replace(/_/g, ' ')
                .toLowerCase()
                .replace(/\b\w/g, (c) => c.toUpperCase());

            const formatDate = (dateString) => {
                const date = new Date(dateString);
                const formattedDate = date.toLocaleDateString('en-US', {
                    month: 'short',
                    day: 'numeric'
                });
                const formattedTime = date.toLocaleTimeString('en-US', {
                    hour: '2-digit',
                    minute: '2-digit',
                    hour12: false
                });

                return `${formattedDate}, ${formattedTime}`;
            };
            const stageBadgeClass = (stage) => {
                switch (stage) {
                    case 'group':
                        return 'bg-primary';
                    case 'round_of_16':
                        return 'bg-info';
                    case 'quarter_final':
                        return 'bg-warning';
                    case 'semi_final':
                        return 'bg-danger';
                    case 'final':
                        return 'bg-success';
                    default:
                        return 'bg-secondary';
                }
            };
            const routeDetail = (matchId) => `{{ route('matches.show', ':id') }}`.replace(':id', matchId);

            container.innerHTML = matches.map(match => `
                <div class="col-md-6">
                    <div class="card match-card h-100">
                        <div class="card-body p-3"> <!-- Reduced padding -->
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <!-- Smaller margin -->
                                <span class="badge ${stageBadgeClass(match.stage)}">${toTitleCase(match.stage)}</span>
                                <small class="text-muted"><i class="fas fa-calendar-alt me-1"></i> ${formatDate(match.scheduled_at)}</small>
                            </div>

                            <div class="d-flex align-items-center mb-2"> <!-- Horizontal layout -->
                                <div class="d-flex align-items-center flex-grow-1">
                                    <img src="${match.home_team.logo ? match.home_team.logo : getRandomLogo()}"
                                        alt="Team Logo" class="team-logo-sm me-2"> <!-- Smaller logo -->
                                    <span class="text-truncate">${match.home_team.name}</span> <!-- Truncate long names -->
                                </div>

                                <div class="px-2 text-center flex-shrink-0">
                                    <div class="vs-badge bg-light rounded-pill px-2 py-0 d-inline-block">
                                        <small class="fw-bold">VS</small> <!-- Smaller VS text -->
                                    </div>
                                </div>

                                <div class="d-flex align-items-center flex-grow-1 justify-content-end">
                                    <span class="text-truncate text-end">${match.away_team.name}</span> <!-- Right-aligned -->
                                    <img src="${match.away_team.logo ? match.away_team.logo : getRandomLogo()}"
                                        alt="Team Logo" class="team-logo-sm ms-2"> <!-- Smaller logo -->
                                </div>
                            </div>

                            <div class="d-flex justify-content-between align-items-center">
                                <span class="badge bg-light text-dark small">
                                    <i class="fas fa-map-marker-alt me-1"></i> ${match.venue}
                                </span>
                                <div>
                                    <a href="${routeDetail(match.id)}" class="btn btn-sm btn-outline-primary">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="#notification${match.id}" class="btn btn-sm btn-accent ms-1">
                                        <i class="fas fa-bell"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            `).join('');
        }

        function renderVenues(venues) {
            const container = document.getElementById('venues-container');

            container.innerHTML = venues.map(venue => `
                <div class="col-lg-4 col-md-6">
                    <div class="stadium-card animate__animated animate__fadeIn">
                        <img src="https://images.squarespace-cdn.com/content/v1/6541b7a7b9fd1140888a8017/20e885ad-786d-499b-8316-531d56d1741e/stadion+terbesar+di+eropa+-+Signal+Iduna+Park+Stadium-Spun+Global+-+Arne+Museler+Wikimedia.jpg"
                            alt="Stadium" class="stadium-img">
                        <div class="stadium-overlay">
                            <h4>${venue.name}</h4>
                            <p class="mb-1"><i class="fas fa-map-marker-alt me-1"></i> ${venue.full_address}</p>
                            <p class="mb-0"><i class="fas fa-people-arrows me-1"></i> Capacity: ${venue.capacity_formatted}</p>
                        </div>
                    </div>
                </div>
            `).join('');
        }
    </script>
</body>

</html>
