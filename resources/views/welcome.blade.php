<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SmartVillage DePIN Hub - Empowering African Communities</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        :root {
            --primary-color: #198754;
            --secondary-color: #0f6848;
            --accent-color: #ffc107;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            color: #333;
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
        }
        
        .hero-section {
            background: linear-gradient(rgba(0, 0, 0, 0.6), rgba(0, 0, 0, 0.6)), 
                        url('https://images.unsplash.com/photo-1517486808906-6ca8b3f8e1c1?ixlib=rb-1.2.1&auto=format&fit=crop&w=1949&q=80');
            background-size: cover;
            background-position: center;
            color: white;
            padding: 100px 0;
            text-align: center;
        }
        
        .feature-icon {
            font-size: 2.5rem;
            color: var(--primary-color);
            margin-bottom: 1rem;
        }
        
        .btn-primary {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
        }
        
        .btn-primary:hover {
            background-color: var(--secondary-color);
            border-color: var(--secondary-color);
        }
        
        .btn-outline-primary {
            color: var(--primary-color);
            border-color: var(--primary-color);
        }
        
        .btn-outline-primary:hover {
            background-color: var(--primary-color);
            color: white;
        }
        
        .stat-number {
            font-size: 2.5rem;
            font-weight: bold;
            color: var(--primary-color);
        }
        
        .testimonial-card {
            border-left: 4px solid var(--primary-color);
        }
        
        .african-pattern {
            background-image: url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 40 40"><path fill="%23198754" fill-opacity="0.1" d="M0 0h40v40H0z"/><path fill="%230f6848" fill-opacity="0.2" d="M0 0h20v20H0z"/></svg>');
        }
        
        footer {
            background-color: #343a40;
        }
    </style>
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-success">
        <div class="container">
            <a class="navbar-brand" href="/">
                <i class="fas fa-solar-panel me-2"></i>SmartVillage DePIN Hub
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
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
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('register') }}">Register</a>
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
    <section class="py-5 bg-light">
        <div class="container">
            <div class="row text-center">
                <div class="col-md-3">
                    <div class="stat-number">500M+</div>
                    <p>Africans without electricity access</p>
                </div>
                <div class="col-md-3">
                    <div class="stat-number">600M+</div>
                    <p>Africans without reliable internet</p>
                </div>
                <div class="col-md-3">
                    <div class="stat-number">$3T+</div>
                    <p>Global infrastructure market by 2030</p>
                </div>
                <div class="col-md-3">
                    <div class="stat-number">100+</div>
                    <p>Communities using DePIN solutions</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section id="features" class="py-5">
        <div class="container">
            <div class="text-center mb-5">
                <h2 class="display-5 fw-bold">How SmartVillage DePIN Works</h2>
                <p class="lead">Our platform combines cutting-edge technology with community-driven solutions</p>
            </div>
            
            <div class="row g-4">
                <div class="col-md-4">
                    <div class="card h-100 border-0 shadow-sm">
                        <div class="card-body text-center p-4">
                            <div class="feature-icon">
                                <i class="fas fa-bolt"></i>
                            </div>
                            <h4 class="card-title">Resource Sharing</h4>
                            <p class="card-text">Community members contribute excess resources like solar energy, bandwidth, water, and storage capacity.</p>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-4">
                    <div class="card h-100 border-0 shadow-sm">
                        <div class="card-body text-center p-4">
                            <div class="feature-icon">
                                <i class="fas fa-link"></i>
                            </div>
                            <h4 class="card-title">Hedera Blockchain</h4>
                            <p class="card-text">All transactions are securely recorded on the Hedera blockchain for transparency and verifiability.</p>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-4">
                    <div class="card h-100 border-0 shadow-sm">
                        <div class="card-body text-center p-4">
                            <div class="feature-icon">
                                <i class="fas fa-brain"></i>
                            </div>
                            <h4 class="card-title">AI Optimization</h4>
                            <p class="card-text">Our AI algorithms predict community needs and optimize resource allocation for maximum impact.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- How It Works Section -->
    <section id="how-it-works" class="py-5 bg-light african-pattern">
        <div class="container">
            <div class="text-center mb-5">
                <h2 class="display-5 fw-bold">Simple Four-Step Process</h2>
                <p class="lead">Join the decentralized infrastructure revolution in just a few steps</p>
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
                            <p>Sign up and connect your Hedera account to start contributing to your community.</p>
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
                            <p>Share your excess resources - solar energy, bandwidth, water, or storage capacity.</p>
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
                            <p>Our algorithms analyze community needs and optimize resource distribution.</p>
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
                            <p>Watch your contributions make a real difference in your village and beyond.</p>
                        </div>
                    </div>
                </div>
                
                <div class="col-lg-6">
                    <img src="https://images.unsplash.com/photo-1586773860418-d37222d8fce3?ixlib=rb-1.2.1&auto=format&fit=crop&w=1350&q=80" 
                         alt African community working together" class="img-fluid rounded shadow">
                </div>
            </div>
        </div>
    </section>

    <!-- Impact Section -->
    <section id="impact" class="py-5">
        <div class="container">
            <div class="text-center mb-5">
                <h2 class="display-5 fw-bold">Real World Impact</h2>
                <p class="lead">SmartVillage DePIN is transforming communities across Africa</p>
            </div>
            
            <div class="row">
                <div class="col-md-6 col-lg-3 mb-4">
                    <div class="card h-100 border-0 shadow-sm">
                        <div class="card-body text-center p-4">
                            <i class="fas fa-sun feature-icon"></i>
                            <h4>Solar Energy</h4>
                            <p>20+ villages now have reliable electricity through shared solar resources.</p>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-6 col-lg-3 mb-4">
                    <div class="card h-100 border-0 shadow-sm">
                        <div class="card-body text-center p-4">
                            <i class="fas fa-wifi feature-icon"></i>
                            <h4>Internet Access</h4>
                            <p>15 communities connected through mesh networks and shared bandwidth.</p>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-6 col-lg-3 mb-4">
                    <div class="card h-100 border-0 shadow-sm">
                        <div class="card-body text-center p-4">
                            <i class="fas fa-tint feature-icon"></i>
                            <h4>Clean Water</h4>
                            <p>Water distribution optimized to reach 5000+ people in drought-prone areas.</p>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-6 col-lg-3 mb-4">
                    <div class="card h-100 border-0 shadow-sm">
                        <div class="card-body text-center p-4">
                            <i class="fas fa-graduation-cap feature-icon"></i>
                            <h4>Education</h4>
                            <p>Digital educational resources now accessible to 2000+ students.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Testimonials Section -->
    <section class="py-5 bg-light">
        <div class="container">
            <div class="text-center mb-5">
                <h2 class="display-5 fw-bold">What Communities Are Saying</h2>
            </div>
            
            <div class="row">
                <div class="col-md-4 mb-4">
                    <div class="card h-100 testiominal-card">
                        <div class="card-body">
                            <p class="card-text">"SmartVillage has transformed our community. We now have reliable electricity for our school and clinic thanks to shared solar resources."</p>
                            <div class="d-flex align-items-center">
                                <div class="flex-shrink-0">
                                    <img src="https://ui-avatars.com/api/?name=John+Doe&background=random" alt="Profile" class="rounded-circle" width="50">
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <h6 class="mb-0">John Doe</h6>
                                    <small class="text-muted">Community Leader, Kenya</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-4 mb-4">
                    <div class="card h-100 testiominal-card">
                        <div class="card-body">
                            <p class="card-text">"The AI predictions help us optimize our water distribution, especially during dry seasons. This technology is a game-changer for rural areas."</p>
                            <div class="d-flex align-items-center">
                                <div class="flex-shrink-0">
                                    <img src="https://ui-avatars.com/api/?name=Mary+Williams&background=random" alt="Profile" class="rounded-circle" width="50">
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <h6 class="mb-0">Mary Williams</h6>
                                    <small class="text-muted">Water Committee, Nigeria</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-4 mb-4">
                    <div class="card h-100 testiominal-card">
                        <div class="card-body">
                            <p class="card-text">"Thanks to the bandwidth sharing program, our students can now access online educational resources. This has dramatically improved learning outcomes."</p>
                            <div class="d-flex align-items-center">
                                <div class="flex-shrink-0">
                                    <img src="https://ui-avatars.com/api/?name=David+Chen&background=random" alt="Profile" class="rounded-circle" width="50">
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <h6 class="mb-0">David Chen</h6>
                                    <small class="text-muted">Teacher, Ghana</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="py-5 bg-primary text-white">
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
                    <p>Empowering African communities through decentralized infrastructure and AI optimization.</p>
                </div>
                <div class="col-md-3">
                    <h5>Quick Links</h5>
                    <ul class="list-unstyled">
                        <li><a href="#features" class="text-white">Features</a></li>
                        <li><a href="#how-it-works" class="text-white">How It Works</a></li>
                        <li><a href="#impact" class="text-white">Impact</a></li>
                    </ul>
                </div>
                <div class="col-md-3">
                    <h5>Connect</h5>
                    <ul class="list-unstyled">
                        <li><a href="#" class="text-white"><i class="fab fa-twitter me-2"></i>Twitter</a></li>
                        <li><a href="#" class="text-white"><i class="fab fa-linkedin me-2"></i>LinkedIn</a></li>
                        <li><a href="#" class="text-white"><i class="fab fa-github me-2"></i>GitHub</a></li>
                    </ul>
                </div>
            </div>
            <hr class="my-4">
            <div class="text-center">
                <p class="mb-0">&copy; 2025 SmartVillage DePIN Hub. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>