<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SmartVillage DePIN Hub - Empowering African Communities</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
    <style>
        :root {
            --primary-bg: #0a0a0f;
            --card-bg: #1d1d2d;
            --primary-accent: #00ff88;
            --secondary-accent: #00b3ff;
            --text-primary: #ffffff;
            --text-secondary: #a0a0b0;
        }
        
        body {
            font-family: 'Inter', sans-serif;
            background: var(--primary-bg);
            color: var(--text-primary);
            line-height: 1.6;
        }
        
        .hero-section {
            background: linear-gradient(rgba(0, 0, 0, 0.7), rgba(0, 0, 0, 0.7)), 
                        url('https://images.unsplash.com/photo-1517486808906-6ca8b3f8e1c1?ixlib=rb-1.2.1&auto=format&fit=crop&w=1949&q=80');
            background-size: cover;
            background-position: center;
            padding: 120px 0;
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
            background: radial-gradient(circle at 50% 50%, rgba(0, 255, 136, 0.1) 0%, transparent 50%);
            pointer-events: none;
        }
        
        .navbar-brand {
            font-size: 1.5rem;
        }
        
        .logo-icon {
            font-size: 1.8rem;
        }
        
        .card {
            background: var(--card-bg);
            border: none;
            border-radius: 12px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.2);
            backdrop-filter: blur(10px);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        
        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 12px 40px rgba(0, 255, 136, 0.15);
        }
        
        .btn-primary {
            background: linear-gradient(135deg, var(--primary-accent), var(--secondary-accent));
            border: none;
            border-radius: 8px;
            font-weight: 500;
            transition: all 0.3s ease;
        }
        
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(0, 255, 136, 0.3);
        }
        
        .btn-outline-light {
            border: 2px solid rgba(255, 255, 255, 0.3);
            color: white;
        }
        
        .btn-outline-light:hover {
            background: white;
            color: black;
        }
        
        .stat-number {
            font-size: 2.5rem;
            font-weight: bold;
            background: linear-gradient(135deg, var(--primary-accent), var(--secondary-accent));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        
        .feature-icon {
            font-size: 2.5rem;
            background: linear-gradient(135deg, var(--primary-accent), var(--secondary-accent));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            margin-bottom: 1rem;
        }
        
        .testimonial-card {
            border-left: 4px solid var(--primary-accent);
            background: var(--card-bg);
        }
        
        .african-pattern {
            background-image: url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 40 40"><path fill="%2300ff88" fill-opacity="0.1" d="M0 0h40v40H0z"/><path fill="%2300b3ff" fill-opacity="0.2" d="M0 0h20v20H0z"/></svg>');
        }
        
        footer {
            background: rgba(0, 0, 0, 0.3);
            backdrop-filter: blur(10px);
        }
        
        .glow-effect {
            position: relative;
        }
        
        .glow-effect::after {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            border-radius: inherit;
            box-shadow: 0 0 15px rgba(0, 255, 136, 0.2);
            opacity: 0;
            transition: opacity 0.3s ease;
        }
        
        .glow-effect:hover::after {
            opacity: 1;
        }

        .text-muted{
            color: var(--text-secondary) !important;
        }

        .card-title{
            color: white !important;
        }
        .card-body{
            color: var(--text-secondary) !important;
        }

        .card-text{
            color: var(--text-secondary) !important;
        }
        #how-it-words{
            
        }
        
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .card {
            animation: fadeInUp 0.5s ease-out;
        }
    </style>
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark border-bottom border-success">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center" href="/">
                <div class="logo-icon me-2">
                    <i class="fas fa-microchip text-success"></i>
                </div>
                <span class="fw-bold">SmartVillage</span>
                <span class="text-success ms-1">DePIN</span>
            </a>
            <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <i class="fas fa-bars"></i>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="#features">Features</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#how-it-works">How It Works</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#impact">Impact</a>
                    </li>
                    @auth
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('dashboard') }}">Dashboard</a>
                    </li>
                    @else
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('login') }}">Login</a>
                    </li>
                    <li class="nav-item ms-2">
                        <a class="btn btn-success" href="{{ route('register') }}">Register</a>
                    </li>
                    @endauth
                </ul>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero-section">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <h1 class="display-4 fw-bold mb-4">Empowering African Communities with Decentralized Infrastructure</h1>
                    <p class="lead mb-5">SmartVillage DePIN Hub leverages blockchain and AI to help communities share resources, optimize allocations, and build sustainable infrastructure for a better future.</p>
                    <div class="d-grid gap-2 d-sm-flex justify-content-sm-center">
                        @auth
                        <a href="{{ route('dashboard') }}" class="btn btn-primary btn-lg px-4 gap-3">Go to Dashboard</a>
                        @else
                        <a href="{{ route('register') }}" class="btn btn-primary btn-lg px-4 gap-3">Get Started</a>
                        <a href="#how-it-works" class="btn btn-outline-light btn-lg px-4">Learn More</a>
                        @endauth
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Stats Section -->
    <section class="py-5 bg-dark">
        <div class="container">
            <div class="row text-center">
                <div class="col-md-3 mb-4">
                    <div class="stat-number">500M+</div>
                    <p class="text-muted">Africans without electricity access</p>
                </div>
                <div class="col-md-3 mb-4">
                    <div class="stat-number">600M+</div>
                    <p class="text-muted">Africans without reliable internet</p>
                </div>
                <div class="col-md-3 mb-4">
                    <div class="stat-number">$3T+</div>
                    <p class="text-muted">Global infrastructure market by 2030</p>
                </div>
                <div class="col-md-3 mb-4">
                    <div class="stat-number">100+</div>
                    <p class="text-muted">Communities using DePIN solutions</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section id="features" class="py-5">
        <div class="container">
            <div class="text-center mb-5">
                <h2 class="display-5 fw-bold">How SmartVillage DePIN Works</h2>
                <p class="lead text-muted">Our platform combines cutting-edge technology with community-driven solutions</p>
            </div>
            
            <div class="row g-4">
                <div class="col-md-4">
                    <div class="card h-100 border-0 glow-effect">
                        <div class="card-body text-center p-4">
                            <div class="feature-icon">
                                <i class="fas fa-bolt"></i>
                            </div>
                            <h4 class="card-title">Resource Sharing</h4>
                            <p class="card-text text-muted">Community members contribute excess resources like solar energy, bandwidth, water, and storage capacity.</p>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-4">
                    <div class="card h-100 border-0 glow-effect">
                        <div class="card-body text-center p-4">
                            <div class="feature-icon">
                                <i class="fas fa-link"></i>
                            </div>
                            <h4 class="card-title">Hedera Blockchain</h4>
                            <p class="card-text text-muted">All transactions are securely recorded on the Hedera blockchain for transparency and verifiability.</p>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-4">
                    <div class="card h-100 border-0 glow-effect">
                        <div class="card-body text-center p-4">
                            <div class="feature-icon">
                                <i class="fas fa-brain"></i>
                            </div>
                            <h4 class="card-title">AI Optimization</h4>
                            <p class="card-text text-muted">Our AI algorithms predict community needs and optimize resource allocation for maximum impact.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- How It Works Section -->
    <section id="how-it-works" class="py-5 bg-dark african-pattern">
        <div class="container">
            <div class="text-center mb-5">
                <h2 class="display-5 fw-bold">Simple Four-Step Process</h2>
                <p class="lead text-muted">Join the decentralized infrastructure revolution in just a few steps</p>
            </div>
            
            <div class="row align-items-center">
                <div class="col-lg-6">
                    <div class="d-flex mb-4">
                        <div class="flex-shrink-0">
                            <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                                <span class="fw-bold">1</span>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-4">
                            <h4>Create an Account</h4>
                            <p class="text-muted">Sign up and connect your Hedera account to start contributing to your community.</p>
                        </div>
                    </div>
                    
                    <div class="d-flex mb-4">
                        <div class="flex-shrink-0">
                            <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                                <span class="fw-bold">2</span>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-4">
                            <h4>Contribute Resources</h4>
                            <p class="text-muted">Share your excess resources - solar energy, bandwidth, water, or storage capacity.</p>
                        </div>
                    </div>
                    
                    <div class="d-flex mb-4">
                        <div class="flex-shrink-0">
                            <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                                <span class="fw-bold">3</span>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-4">
                            <h4>AI Optimization</h4>
                            <p class="text-muted">Our algorithms analyze community needs and optimize resource distribution.</p>
                        </div>
                    </div>
                    
                    <div class="d-flex">
                        <div class="flex-shrink-0">
                            <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                                <span class="fw-bold">4</span>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-4">
                            <h4>Community Impact</h4>
                            <p class="text-muted">Watch your contributions make a real difference in your village and beyond.</p>
                        </div>
                    </div>
                </div>
                
                <div class="col-lg-6">
                    <div class="card border-0 overflow-hidden">
                        <img src="https://images.unsplash.com/photo-1586773860418-d37222d8fce3?ixlib=rb-1.2.1&auto=format&fit=crop&w=1350&q=80" 
                             alt="African community working together" class="img-fluid rounded">
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Impact Section -->
    <section id="impact" class="py-5">
        <div class="container">
            <div class="text-center mb-5">
                <h2 class="display-5 fw-bold">Real World Impact</h2>
                <p class="lead text-muted">SmartVillage DePIN is transforming communities across Africa</p>
            </div>
            
            <div class="row">
                <div class="col-md-6 col-lg-3 mb-4">
                    <div class="card h-100 border-0 glow-effect">
                        <div class="card-body text-center p-4">
                            <div class="feature-icon">
                                <i class="fas fa-sun"></i>
                            </div>
                            <h4>Solar Energy</h4>
                            <p class="text-muted">20+ villages now have reliable electricity through shared solar resources.</p>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-6 col-lg-3 mb-4">
                    <div class="card h-100 border-0 glow-effect">
                        <div class="card-body text-center p-4">
                            <div class="feature-icon">
                                <i class="fas fa-wifi"></i>
                            </div>
                            <h4>Internet Access</h4>
                            <p class="text-muted">15 communities connected through mesh networks and shared bandwidth.</p>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-6 col-lg-3 mb-4">
                    <div class="card h-100 border-0 glow-effect">
                        <div class="card-body text-center p-4">
                            <div class="feature-icon">
                                <i class="fas fa-tint"></i>
                            </div>
                            <h4>Clean Water</h4>
                            <p class="text-muted">Water distribution optimized to reach 5000+ people in drought-prone areas.</p>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-6 col-lg-3 mb-4">
                    <div class="card h-100 border-0 glow-effect">
                        <div class="card-body text-center p-4">
                            <div class="feature-icon">
                                <i class="fas fa-graduation-cap"></i>
                            </div>
                            <h4>Education</h4>
                            <p class="text-muted">Digital educational resources now accessible to 2000+ students.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    

    <!-- CTA Section -->
    <section class="py-5" style="background: linear-gradient(135deg, var(--primary-accent), var(--secondary-accent));">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-8 text-center">
                    <h2 class="display-5 fw-bold mb-4">Ready to Transform Your Community?</h2>
                    <p class="lead mb-5">Join the SmartVillage DePIN Hub today and be part of the decentralized infrastructure revolution</p>
                    @auth
                    <a href="{{ route('dashboard') }}" class="btn btn-light btn-lg px-5">Go to Dashboard</a>
                    @else
                    <a href="{{ route('register') }}" class="btn btn-light btn-lg px-5">Get Started Now</a>
                    @endauth
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="py-4 text-white">
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <h5>SmartVillage DePIN Hub</h5>
                    <p class="text-muted">Empowering African communities through decentralized infrastructure and AI optimization.</p>
                </div>
                <div class="col-md-3">
                    <h5>Quick Links</h5>
                    <ul class="list-unstyled">
                        <li><a href="#features" class="text-muted">Features</a></li>
                        <li><a href="#how-it-works" class="text-muted">How It Works</a></li>
                        <li><a href="#impact" class="text-muted">Impact</a></li>
                    </ul>
                </div>
            </div>
            <hr class="my-4 border-secondary">
            <div class="text-center">
                <p class="mb-0">&copy; 2025 SmartVillage DePIN Hub. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>