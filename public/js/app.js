// Enhanced JavaScript for SmartVillage DePIN Hub
document.addEventListener('DOMContentLoaded', function() {
    // Enable Bootstrap tooltips
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl)
    });
    
    // Auto-dismiss alerts after 5 seconds
    setTimeout(function() {
        var alerts = document.querySelectorAll('.alert');
        alerts.forEach(function(alert) {
            var bsAlert = new bootstrap.Alert(alert);
            bsAlert.close();
        });
    }, 5000);
    
    // Add smooth scrolling for anchor links
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
    
    // Add parallax effect to hero section
    const heroSection = document.querySelector('.hero-section');
    if (heroSection) {
        window.addEventListener('scroll', function() {
            const scrolled = window.pageYOffset;
            const parallaxSpeed = 0.5;
            heroSection.style.backgroundPositionY = -(scrolled * parallaxSpeed) + 'px';
        });
    }
    
    // Animate stats counting
    const animateCounter = (element, target, duration = 2000) => {
        const start = 0;
        const increment = target / (duration / 16);
        let current = start;
        
        const timer = setInterval(() => {
            current += increment;
            if (current >= target) {
                element.textContent = target + '+';
                clearInterval(timer);
            } else {
                element.textContent = Math.floor(current) + '+';
            }
        }, 16);
    };
    
    // Initialize counters when they come into view
    const observerOptions = {
        threshold: 0.5,
        rootMargin: '0px 0px -100px 0px'
    };
    
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                const statNumber = entry.target.querySelector('.stat-number');
                if (statNumber) {
                    const target = parseInt(statNumber.textContent);
                    animateCounter(statNumber, target);
                }
                observer.unobserve(entry.target);
            }
        });
    }, observerOptions);
    
    // Observe all stat elements
    document.querySelectorAll('.col-md-3.mb-4').forEach(el => {
        observer.observe(el);
    });
    
    // Add typing animation effect to hero text
    const heroText = document.querySelector('.hero-section h1');
    if (heroText) {
        const originalText = heroText.textContent;
        heroText.textContent = '';
        let i = 0;
        
        const typeWriter = () => {
            if (i < originalText.length) {
                heroText.textContent += originalText.charAt(i);
                i++;
                setTimeout(typeWriter, 50);
            }
        };
        
        // Start typing animation when hero section is in view
        const heroObserver = new IntersectionObserver((entries) => {
            if (entries[0].isIntersecting) {
                typeWriter();
                heroObserver.disconnect();
            }
        });
        
        heroObserver.observe(heroSection);
    }
    
    // Add interactive resource cards animation
    const resourceCards = document.querySelectorAll('.card.glow-effect');
    resourceCards.forEach(card => {
        card.addEventListener('mouseenter', () => {
            card.style.transform = 'translateY(-10px) scale(1.02)';
        });
        
        card.addEventListener('mouseleave', () => {
            card.style.transform = 'translateY(0) scale(1)';
        });
    });
    
    // Add scroll to top button
    const scrollToTopBtn = document.createElement('button');
    scrollToTopBtn.innerHTML = '<i class="fas fa-arrow-up"></i>';
    scrollToTopBtn.classList.add('btn', 'btn-success', 'scroll-to-top');
    scrollToTopBtn.style.position = 'fixed';
    scrollToTopBtn.style.bottom = '20px';
    scrollToTopBtn.style.right = '20px';
    scrollToTopBtn.style.zIndex = '1000';
    scrollToTopBtn.style.borderRadius = '50%';
    scrollToTopBtn.style.width = '50px';
    scrollToTopBtn.style.height = '50px';
    scrollToTopBtn.style.display = 'none';
    scrollToTopBtn.style.alignItems = 'center';
    scrollToTopBtn.style.justifyContent = 'center';
    document.body.appendChild(scrollToTopBtn);
    
    scrollToTopBtn.addEventListener('click', () => {
        window.scrollTo({
            top: 0,
            behavior: 'smooth'
        });
    });
    
    window.addEventListener('scroll', () => {
        if (window.pageYOffset > 300) {
            scrollToTopBtn.style.display = 'flex';
        } else {
            scrollToTopBtn.style.display = 'none';
        }
    });
    
    // Add loading animation for forms
    const forms = document.querySelectorAll('form');
    forms.forEach(form => {
        form.addEventListener('submit', function() {
            const submitBtn = this.querySelector('button[type="submit"]');
            if (submitBtn) {
                submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Processing...';
                submitBtn.disabled = true;
            }
        });
    });
    
    // Add real-time validation to forms
    const inputs = document.querySelectorAll('input, select, textarea');
    inputs.forEach(input => {
        input.addEventListener('blur', function() {
            if (this.value.trim() === '' && this.hasAttribute('required')) {
                this.classList.add('is-invalid');
            } else {
                this.classList.remove('is-invalid');
            }
        });
    });
    
    // Add theme toggle functionality (optional dark/light mode)
    const themeToggleBtn = document.createElement('button');
    themeToggleBtn.innerHTML = '<i class="fas fa-moon"></i>';
    themeToggleBtn.classList.add('btn', 'btn-outline-secondary', 'theme-toggle');
    themeToggleBtn.style.position = 'fixed';
    themeToggleBtn.style.bottom = '80px';
    themeToggleBtn.style.right = '20px';
    themeToggleBtn.style.zIndex = '1000';
    themeToggleBtn.style.borderRadius = '50%';
    themeToggleBtn.style.width = '50px';
    themeToggleBtn.style.height = '50px';
    themeToggleBtn.style.display = 'flex';
    themeToggleBtn.style.alignItems = 'center';
    themeToggleBtn.style.justifyContent = 'center';
    document.body.appendChild(themeToggleBtn);
    
    themeToggleBtn.addEventListener('click', () => {
        document.body.classList.toggle('light-mode');
        if (document.body.classList.contains('light-mode')) {
            themeToggleBtn.innerHTML = '<i class="fas fa-sun"></i>';
            themeToggleBtn.classList.replace('btn-outline-secondary', 'btn-warning');
        } else {
            themeToggleBtn.innerHTML = '<i class="fas fa-moon"></i>';
            themeToggleBtn.classList.replace('btn-warning', 'btn-outline-secondary');
        }
    });
    
    // Add network status indicator
    const networkStatus = document.createElement('div');
    networkStatus.classList.add('network-status');
    networkStatus.style.position = 'fixed';
    networkStatus.style.top = '70px';
    networkStatus.style.right = '20px';
    networkStatus.style.zIndex = '1000';
    networkStatus.style.padding = '5px 10px';
    networkStatus.style.borderRadius = '4px';
    networkStatus.style.fontSize = '12px';
    networkStatus.style.display = 'none';
    
    window.addEventListener('online', () => {
        networkStatus.textContent = 'Online';
        networkStatus.style.background = 'var(--success)';
        networkStatus.style.display = 'block';
        setTimeout(() => {
            networkStatus.style.display = 'none';
        }, 3000);
    });
    
    window.addEventListener('offline', () => {
        networkStatus.textContent = 'Offline';
        networkStatus.style.background = 'var(--danger)';
        networkStatus.style.display = 'block';
    });
    
    document.body.appendChild(networkStatus);
    
    // Initialize any charts if Chart.js is available
    if (typeof Chart !== 'undefined') {
        initializeCharts();
    }
    
    console.log('SmartVillage DePIN Hub initialized successfully');
});

// Function to initialize charts (if needed)
function initializeCharts() {
    // This would be where you initialize any Chart.js charts
    // Example:
    /*
    const ctx = document.getElementById('resourceUsageChart');
    if (ctx) {
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
                datasets: [{
                    label: 'Energy Usage',
                    data: [12, 19, 3, 5, 2, 3],
                    borderWidth: 2,
                    borderColor: '#00ff88',
                    tension: 0.3
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'top',
                    }
                }
            }
        });
    }
    */
}

// Utility function for formatting numbers
function formatNumber(number) {
    return new Intl.NumberFormat().format(number);
}

// Utility function for formatting dates
function formatDate(date) {
    return new Intl.DateTimeFormat('en-US', {
        year: 'numeric',
        month: 'short',
        day: 'numeric'
    }).format(new Date(date));
}

// Export functions for use in other modules (if needed)
if (typeof module !== 'undefined' && module.exports) {
    module.exports = {
        formatNumber,
        formatDate
    };
}