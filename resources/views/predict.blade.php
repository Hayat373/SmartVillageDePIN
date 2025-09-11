<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AI Prediction - SmartVillage DePIN Hub</title>
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
        
        .alert {
            border: none;
            border-radius: 8px;
        }
        
        .display-6 {
            font-weight: 700;
            background: linear-gradient(135deg, var(--primary-accent), var(--secondary-accent));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
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
<body class="bg-dark">
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
                        <a class="nav-link d-flex align-items-center" href="/">
                            <i class="fas fa-home me-1"></i> Home
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link d-flex align-items-center" href="/login">
                            <i class="fas fa-sign-in-alt me-1"></i> Login
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="btn btn-success d-flex align-items-center ms-2" href="/register">
                            <i class="fas fa-user-plus me-1"></i> Register
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="py-5">
        <div class="container">
            <div class="card border-0 mb-5">
                <div class="card-body p-5">
                    <h1 class="text-center fw-bold mb-4">Resource Allocation Prediction</h1>
                    
                    <div class="alert alert-primary d-flex align-items-center mb-4">
                        <i class="fas fa-database fa-2x me-3"></i>
                        <div>
                            <h5 class="mb-0">Total Resources Shared</h5>
                            <p class="mb-0 display-6 fw-bold">{{ $totalResources }}</p>
                        </div>
                    </div>
                    
                    <div class="alert alert-success d-flex align-items-center mb-5">
                        <i class="fas fa-brain fa-2x me-3"></i>
                        <div>
                            <h5 class="mb-0">AI Prediction</h5>
                            <p class="mb-0 display-6 fw-bold">{{ $prediction }}</p>
                        </div>
                    </div>
                    
                    <div class="mt-5">
                        <h4 class="mb-3">How Our AI Makes Predictions</h4>
                        <p class="mb-4">This prediction is based on our advanced rule-based AI system:</p>
                        
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <div class="card h-100 border-0 bg-dark">
                                    <div class="card-body">
                                        <div class="d-flex align-items-center mb-3">
                                            <div class="bg-primary rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px;">
                                                <i class="fas fa-code-branch"></i>
                                            </div>
                                            <h5 class="mb-0">Decision Logic</h5>
                                        </div>
                                        <ul class="list-unstyled">
                                            <li class="mb-2"><i class="fas fa-check-circle text-success me-2"></i> If total shared resources > 50: "High demand - allocate more!"</li>
                                            <li><i class="fas fa-check-circle text-success me-2"></i> Otherwise: "Low demand"</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="card h-100 border-0 bg-dark">
                                    <div class="card-body">
                                        <div class="d-flex align-items-center mb-3">
                                            <div class="bg-warning rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px;">
                                                <i class="fas fa-robot"></i>
                                            </div>
                                            <h5 class="mb-0">Future Enhancements</h5>
                                        </div>
                                        <p>In a production system, this would be replaced with more sophisticated machine learning algorithms including:</p>
                                        <ul class="list-unstyled">
                                            <li class="mb-1"><i class="fas fa-angle-right me-2"></i> Time series forecasting</li>
                                            <li class="mb-1"><i class="fas fa-angle-right me-2"></i> Neural networks</li>
                                            <li><i class="fas fa-angle-right me-2"></i> Pattern recognition</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="text-center mt-5">
                        <a href="/" class="btn btn-primary btn-lg px-5 py-3">
                            <i class="fas fa-arrow-left me-2"></i> Back to Home
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <footer class="bg-dark text-white text-center py-4 border-top border-secondary">
        <div class="container">
            <div class="d-flex justify-content-center mb-2">
                <div class="d-flex">
                    <a href="#" class="text-white me-3"><i class="fab fa-twitter fa-lg"></i></a>
                    <a href="#" class="text-white me-3"><i class="fab fa-discord fa-lg"></i></a>
                    <a href="#" class="text-white"><i class="fab fa-github fa-lg"></i></a>
                </div>
            </div>
            <p class="mb-0">SmartVillage DePIN Hub - Empowering Communities with Decentralized Infrastructure</p>
            <small class="text-muted">© 2025 - Building the future of community resources</small>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>