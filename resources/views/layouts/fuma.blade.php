<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Football Tournament Management')</title>
    <!-- Bootstrap 5 CSS CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Animate.css -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
    
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
            margin-bottom: 3rem;
        }
        
        .page-header {
            background: linear-gradient(135deg, var(--secondary-color), var(--primary-color));
            color: white;
            padding: 3rem 0;
            margin-bottom: 2rem;
        }
        
        .card {
            border: none;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.05);
            transition: all 0.3s ease;
            margin-bottom: 20px;
            overflow: hidden;
        }
        
        .card:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 30px rgba(0,0,0,0.1);
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
        
        .btn-outline-accent {
            color: var(--accent-color);
            border-color: var(--accent-color);
        }
        
        .btn-outline-accent:hover {
            background-color: var(--accent-color);
            border-color: var(--accent-color);
            color: white;
        }
        
        .text-accent {
            color: var(--accent-color) !important;
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
        
        .table th {
            font-weight: 600;
        }
        
        .status-badge {
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 500;
        }
        
        .badge-active {
            background-color: rgba(52, 152, 219, 0.2);
            color: var(--primary-color);
        }
        
        .badge-upcoming {
            background-color: rgba(241, 196, 15, 0.2);
            color: #f39c12;
        }
        
        .badge-completed {
            background-color: rgba(149, 165, 166, 0.2);
            color: #7f8c8d;
        }
        
        .filter-card {
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.05);
            margin-bottom: 20px;
        }
        
        .action-btn {
            padding: 5px 10px;
            font-size: 0.8rem;
        }
        
        .pagination .page-item.active .page-link {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
        }
        
        .pagination .page-link {
            color: var(--primary-color);
        }
        
        .team-logo-sm {
            width: 30px;
            height: 30px;
            object-fit: contain;
        }
        
        .badge-pill {
            border-radius: 10px;
            padding: 5px 10px;
            font-size: 0.8rem;
            font-weight: 500;
        }
        
        .match-score {
            font-weight: bold;
            font-size: 1.1rem;
            background-color: #f8f9fa;
            padding: 2px 8px;
            border-radius: 5px;
        }
        
        .live-badge {
            animation: pulse 1.5s infinite;
        }
        
        @keyframes pulse {
            0% { opacity: 1; }
            50% { opacity: 0.5; }
            100% { opacity: 1; }
        }
        
        .match-card {
            border-left: 5px solid var(--primary-color);
            transition: all 0.3s ease;
        }
        
        .match-card:hover {
            border-left-color: var(--accent-color);
            transform: translateY(-3px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
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
            box-shadow: 0 10px 20px rgba(0,0,0,0.1);
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
            from { opacity: 0; }
            to { opacity: 1; }
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
    
    @stack('styles')
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
                        <a class="nav-link {{ request()->routeIs('fuma.tournaments*') ? 'active' : '' }}" href="{{ route('fuma.tournaments.index') }}">Tournaments</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('fuma.teams*') ? 'active' : '' }}" href="{{ route('fuma.teams.index') }}">Teams</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('fuma.matches*') ? 'active' : '' }}" href="{{ route('fuma.matches.index') }}">Matches</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('fuma.players*') ? 'active' : '' }}" href="{{ route('fuma.players.index') }}">Players</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('login') }}">
                            <i class="fas fa-sign-in-alt me-1"></i> Login
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    @yield('content')

    <!-- Footer -->
    <footer>
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <h5 class="fw-bold mb-3">FUMA</h5>
                    <p>The ultimate football tournament management system. Organize, manage, and track your tournaments with ease.</p>
                    <div class="d-flex">
                        <a href="#" class="social-icon"><i class="fab fa-facebook"></i></a>
                        <a href="#" class="social-icon"><i class="fab fa-twitter"></i></a>
                        <a href="#" class="social-icon"><i class="fab fa-instagram"></i></a>
                        <a href="#" class="social-icon"><i class="fab fa-youtube"></i></a>
                    </div>
                </div>
                <div class="col-md-3">
                    <h6 class="fw-bold mb-3">Quick Links</h6>
                    <ul class="list-unstyled">
                        <li><a href="{{ route('fuma.tournaments.index') }}" class="text-light text-decoration-none">Tournaments</a></li>
                        <li><a href="{{ route('fuma.teams.index') }}" class="text-light text-decoration-none">Teams</a></li>
                        <li><a href="{{ route('fuma.matches.index') }}" class="text-light text-decoration-none">Matches</a></li>
                        <li><a href="{{ route('fuma.players.index') }}" class="text-light text-decoration-none">Players</a></li>
                    </ul>
                </div>
                <div class="col-md-3">
                    <h6 class="fw-bold mb-3">Contact</h6>
                    <p class="mb-1"><i class="fas fa-envelope me-2"></i> info@fuma.com</p>
                    <p class="mb-1"><i class="fas fa-phone me-2"></i> +1 234 567 8900</p>
                    <p><i class="fas fa-map-marker-alt me-2"></i> Jakarta, Indonesia</p>
                </div>
            </div>
            <hr class="my-4">
            <div class="text-center">
                <p>&copy; 2023 FUMA. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <!-- Bootstrap 5 JS CDN -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    
    @stack('scripts')
</body>
</html>