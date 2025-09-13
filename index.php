<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PathSeeker - Career Passport</title>
    <link rel="stylesheet" href="css/styles.css">
    <link rel="stylesheet" href="css/responsive.css">
    <!-- Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
</head>
<style>
.dark-mode .navbar {
  background: linear-gradient(135deg, #0f2027, #203a43, #2c5364);
  border-bottom: 1px solid var(--glass-border-dark);
}

.dark-mode .nav-menu a {
  color: #e0f7fa !important;
}

.dark-mode .nav-menu a:hover {
  color: #4dd0e1 !important;
}

.dark-mode .nav-btn {
  background-color: #00bcd4;
  color: #ffffff !important;
}

.dark-mode .nav-btn:hover {
  background-color: #0097a7;
}

.dark-mode .logo a {
  color: #e0f7fa !important;
}

.dark-mode .menu-toggle .bar {
  background-color: #e0f7fa;
}

.logo a {
  font-size: 2rem;
  font-weight: 900;
  background: linear-gradient(135deg, #26c6da, #00acc1);
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;
  letter-spacing: 1.5px;
  transition: opacity var(--transition-speed);
}
.logo a:hover {
  opacity: 0.8;
}

.admin-btn {
  background: linear-gradient(135deg, #00bfa5, #26c6da);
  color: #fff !important;
  font-weight: 700;
  padding: 0.6rem 1.5rem;
  border-radius: 50px; /* pill style */
  border: none;
  box-shadow: 0 4px 15px rgba(38, 198, 218, 0.4);
  transition: all var(--transition-speed);
}
.admin-btn:hover {
  background: linear-gradient(135deg, #26c6da, #00bfa5);
  transform: scale(1.05) translateY(-2px);
  box-shadow: 0 6px 20px rgba(38, 198, 218, 0.6);
}
</style>
<body>
    <!-- Navigation Bar -->
    <header>
    <nav class="navbar">
        <!-- Logo -->
        <div class="logo">
            <a href="index.php">PathSeeker</a>
        </div>

        <!-- Mobile Menu Toggle -->
        <div class="menu-toggle" id="mobile-menu">
            <span class="bar"></span>
            <span class="bar"></span>
            <span class="bar"></span>
        </div>

        <!-- Navigation Menu -->
        <ul class="nav-menu">
            <li><a href="index.php" class="active">Home</a></li>
            <li><a href="about.php">About</a></li>
            <li><a href="careers.php">Careers</a></li>
            <li><a href="quiz.php">Quiz</a></li>
            <li><a href="resources.php">Resources</a></li>
            <li><a href="success-stories.php">Success Stories</a></li>
            <li><a href="login.php">Login / Signup</a></li>
            <!-- Buttons -->
            <li><a href="admin-login.php" class="nav-btn admin-btn">Admin Portal</a></li>
        </ul>
    </nav>
</header>

    <!-- Hero Section -->
    <section class="hero">
        <div class="hero-content">
            <h1>PathSeeker</h1>
            <p class="tagline">Discover What Fits You Best</p>
            <div class="cta-buttons">
                <a href="quiz.php" class="btn primary-btn">Start Quiz</a>
                <a href="careers.php" class="btn primary-btn">Explore Careers</a>
            </div>
        </div>
        <!-- <div class="hero-image">
            <img src="images/hero-image.svg" alt="Career Path Journey" class="responsive-img">
        </div> -->
    </section>

<!-- About Section -->
<section class="about-section" id="about">
    <div class="container">
        <h2 class="section-title">Your Journey Starts Here</h2>
        <div class="about-content">
            <div class="about-text">
                <p>PathSeeker transforms career discovery through personalized guidance that connects your unique talents with fulfilling professional pathways. We believe everyone deserves a career that brings purpose and passion.</p>
                <p>Our intelligent platform combines cutting-edge assessment technology with comprehensive market insights to illuminate your optimal career direction and the steps to get there.</p>
            </div>
            <div class="about-image">
                <img src="images/about-illustration.svg" alt="Career Pathway Illustration" class="responsive-img">
            </div>
        </div>
    </div>
</section>

<!-- Features Section -->
<section class="features-section" id="features">
    <div class="container">
        <h2 class="section-title">Discover Your Potential</h2>
        <div class="features-grid">
            <div class="feature-card">
                <div class="feature-icon">
                    <i class="fas fa-clipboard-check"></i>
                </div>
                <h3>Personalized Discovery</h3>
                <p>Uncover careers perfectly aligned with your personality through our scientifically-validated assessment.</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon">
                    <i class="fas fa-briefcase"></i>
                </div>
                <h3>Career Explorer</h3>
                <p>Navigate hundreds of professional pathways with insights into growth potential, salaries, and education requirements.</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon">
                    <i class="fas fa-book"></i>
                </div>
                <h3>Learning Hub</h3>
                <p>Access exclusive resources, skill-building exercises, and roadmap templates to accelerate your career journey.</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon">
                    <i class="fas fa-users"></i>
                </div>
                <h3>Mentor Connections</h3>
                <p>Learn from professionals who've walked the path before you and gain valuable insider perspectives.</p>
            </div>
        </div>
    </div>
</section>

    <!-- Testimonials Section -->
    <section class="testimonials-section" id="testimonials">
        <div class="container">
            <h2 class="section-title">What Our Users Say</h2>
            <div class="testimonials-slider">
                <div class="testimonial-card">
                    <div class="testimonial-content">
                        <p>"PathSeeker helped me discover my passion for UX design. The career assessment was spot on, and the resources provided were invaluable for my transition."</p>
                    </div>
                    <div class="testimonial-author">
                        <img src="images/girl1.jpg" alt="User Avatar">
                        <div class="author-info">
                            <h4>Emily Rodriguez</h4>
                            <p>UX Designer</p>
                        </div>
                    </div>
                </div>
                <div class="testimonial-card">
                    <div class="testimonial-content">
                        <p>"As a college student unsure about my future, PathSeeker's quiz and career bank gave me clarity and direction. I'm now confidently pursuing a career in data science."</p>
                    </div>
                    <div class="testimonial-author">
                        <img src="images/men2.jpeg" alt="User Avatar">
                        <div class="author-info">
                            <h4>Michael Chen</h4>
                            <p>Data Science Student</p>
                        </div>
                    </div>
                </div>
                <div class="testimonial-card">
                    <div class="testimonial-content">
                        <p>"The success stories on PathSeeker inspired me to make a career change at 35. The resources and guidance made the transition smoother than I expected."</p>
                    </div>
                    <div class="testimonial-author">
                        <img src="images/girl2.jpg" alt="User Avatar">
                        <div class="author-info">
                            <h4>Sarah Malik</h4>
                            <p>Marketing Specialist</p>
                        </div>
                    </div>
                </div>
                <div class="testimonial-card">
                    <div class="testimonial-content">
                        <p>"PathSeeker's personalized approach helped me identify my strengths and find a career that truly aligns with my values. I've never been happier in my professional life!"</p>
                    </div>
                    <div class="testimonial-author">
                        <img src="images/men.jpg" alt="User Avatar">
                        <div class="author-info">
                            <h4>David Wilson</h4>
                            <p>Environmental Scientist</p>
                        </div>
                    </div>
                </div>
                <div class="testimonial-card">
                    <div class="testimonial-content">
                        <p>"After feeling stuck in my career for years, PathSeeker helped me discover new opportunities I never considered before. The career resources were exactly what I needed to make a successful transition."</p>
                    </div>
                    <div class="testimonial-author">
                        <img src="images/girl4.jpeg" alt="User Avatar">
                        <div class="author-info">
                            <h4>Jennifer Lopez</h4>
                            <p>Project Manager</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="slider-controls">
                <button class="prev-btn"><i class="fas fa-chevron-left"></i></button>
                <button class="next-btn"><i class="fas fa-chevron-right"></i></button>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="footer-content">
                <div class="footer-logo">
                    <h3>PathSeeker</h3>
                    <p>Your Career Passport</p>
                </div>
                <div class="footer-links">
                    <div class="footer-column">
                        <h4>Quick Links</h4>
                        <ul>
                            <li><a href="index.php">Home</a></li>
                            <li><a href="about.php">About</a></li>
                            <li><a href="careers.php">Careers</a></li>
                            <li><a href="quiz.php">Quiz</a></li>
                        </ul>
                    </div>
                    <div class="footer-column">
                        <h4>Resources</h4>
                        <ul>
                            <li><a href="resources.php">Resources</a></li>
                            <li><a href="success-stories.php">Success Stories</a></li>
                            <li><a href="#">Blog</a></li>
                            <li><a href="#">FAQ</a></li>
                        </ul>
                    </div>
                    <div class="footer-column">
                        <h4>Contact Us</h4>
                        <ul class="contact-info">
                            <li><i class="fas fa-envelope"></i> info@pathseeker.com</li>
                            <li><i class="fas fa-phone"></i> +1 (555) 123-4567</li>
                            <li><i class="fas fa-map-marker-alt"></i> 123 Career Ave, Success City</li>
                        </ul>
                    </div>
                </div>
                <div class="footer-social">
                    <h4>Follow Us</h4>
                    <div class="social-icons">
                        <a href="https://www.facebook.com/"><i class="fab fa-facebook-f"></i></a>
                        <a href="https://x.com/"><i class="fab fa-twitter"></i></a>
                        <a href="https://pk.linkedin.com/"><i class="fab fa-linkedin-in"></i></a>
                        <a href="https://www.instagram.com/"><i class="fab fa-instagram"></i></a>
                    </div>
                </div>
            </div>
            <div class="footer-bottom">
                <p>&copy; 2025 PathSeeker. All rights reserved.</p>
                <div class="footer-bottom-links">
                    <a href="#">Privacy Policy</a>
                    <a href="#">Terms of Service</a>
                </div>
            </div>
        </div>
    </footer>

    <!-- Accessibility Panel -->
    <div class="accessibility-panel" id="accessibilityPanel">
        <button class="accessibility-toggle" id="accessibilityToggle">
            <i class="fas fa-universal-access"></i>
        </button>
        <div class="accessibility-options">
            <div class="option">
                <label for="darkModeToggle">Dark Mode</label>
                <label class="switch">
                    <input type="checkbox" id="darkModeToggle">
                    <span class="slider round"></span>
                </label>
            </div>
            <div class="option">
                <label>Font Size</label>
                <div class="font-size-controls">
                    <button id="decreaseFontBtn">A-</button>
                    <button id="resetFontBtn">Reset</button>
                    <button id="increaseFontBtn">A+</button>
                </div>
            </div>
        </div>
    </div>

    <!-- JavaScript Files -->
    <script src="js/main.js"></script>
    <script src="js/accessibility.js"></script>
    <script src="js/update-footers.js"></script>

    <script>
    const testimonials = document.querySelectorAll(".testimonial-card");
    const prevBtn = document.querySelector(".prev-btn");
    const nextBtn = document.querySelector(".next-btn");
    let currentIndex = 0;

    function showTestimonial(index) {
        testimonials.forEach((testimonial, i) => {
            testimonial.style.display = (i === index) ? "block" : "none";
        });
    }

    // Show first testimonial
    showTestimonial(currentIndex);

    // Next button
    nextBtn.addEventListener("click", () => {
        currentIndex = (currentIndex + 1) % testimonials.length;
        showTestimonial(currentIndex);
    });

    // Prev button
    prevBtn.addEventListener("click", () => {
        currentIndex = (currentIndex - 1 + testimonials.length) % testimonials.length;
        showTestimonial(currentIndex);
    });

    // Optional: Auto-slide every 5s
    setInterval(() => {
        currentIndex = (currentIndex + 1) % testimonials.length;
        showTestimonial(currentIndex);
    }, 5000);
</script>

</body>
</html>