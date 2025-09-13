// Main JavaScript file for PathSeeker

document.addEventListener('DOMContentLoaded', function() {
    // Mobile Menu Toggle
    const mobileMenu = document.getElementById('mobile-menu');
    const navMenu = document.querySelector('.nav-menu');
    
    if (mobileMenu) {
        mobileMenu.addEventListener('click', function() {
            mobileMenu.classList.toggle('active');
            navMenu.classList.toggle('active');
        });
    }
    
    // Close mobile menu when clicking on a nav link
    const navLinks = document.querySelectorAll('.nav-menu a');
    navLinks.forEach(link => {
        link.addEventListener('click', () => {
            mobileMenu.classList.remove('active');
            navMenu.classList.remove('active');
        });
    });
    
    // Testimonial Slider
    const testimonialSlider = document.querySelector('.testimonials-slider');
    const prevBtn = document.querySelector('.prev-btn');
    const nextBtn = document.querySelector('.next-btn');
    const testimonialCards = document.querySelectorAll('.testimonial-card');
    
    if (testimonialSlider && prevBtn && nextBtn) {
        let currentIndex = 0;
        
        // Set initial position
        updateSliderPosition();
        
        // Previous button click
        prevBtn.addEventListener('click', () => {
            currentIndex = (currentIndex > 0) ? currentIndex - 1 : testimonialCards.length - 1;
            updateSliderPosition();
        });
        
        // Next button click
        nextBtn.addEventListener('click', () => {
            currentIndex = (currentIndex < testimonialCards.length - 1) ? currentIndex + 1 : 0;
            updateSliderPosition();
        });
        
        // Auto slide every 5 seconds
        setInterval(() => {
            currentIndex = (currentIndex < testimonialCards.length - 1) ? currentIndex + 1 : 0;
            updateSliderPosition();
        }, 5000);
        
        function updateSliderPosition() {
            const cardWidth = testimonialCards[0].offsetWidth;
            testimonialSlider.style.transform = `translateX(-${currentIndex * cardWidth}px)`;
        }
        
        // Update slider position on window resize
        window.addEventListener('resize', updateSliderPosition);
    }
    
    // Smooth scrolling for anchor links
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function(e) {
            const targetId = this.getAttribute('href');
            if (targetId === '#') return;
            
            e.preventDefault();
            
            const targetElement = document.querySelector(targetId);
            if (targetElement) {
                window.scrollTo({
                    top: targetElement.offsetTop - 80, // Adjust for fixed header
                    behavior: 'smooth'
                });
            }
        });
    });
    
    // Navbar scroll effect
    const navbar = document.querySelector('.navbar');
    
    if (navbar) {
        window.addEventListener('scroll', () => {
            if (window.scrollY > 50) {
                navbar.style.padding = '0.5rem 2rem';
                navbar.style.boxShadow = '0 2px 10px rgba(0, 0, 0, 0.1)';
            } else {
                navbar.style.padding = '1rem 2rem';
                navbar.style.boxShadow = '0 2px 10px rgba(0, 0, 0, 0.05)';
            }
        });
    }
    
    // Add active class to current page in navigation
    const currentLocation = location.pathname;
    const navLinks2 = document.querySelectorAll('.nav-menu a');
    
    navLinks2.forEach(link => {
        if (link.getAttribute('href') === currentLocation || 
            (currentLocation === '/' && link.getAttribute('href') === 'index.html')) {
            link.classList.add('active');
        }
    });
});