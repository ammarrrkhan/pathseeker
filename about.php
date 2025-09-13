<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About Us - PathSeeker</title>
    <link rel="stylesheet" href="css/styles.css">
    <link rel="stylesheet" href="css/responsive.css">
    <link rel="stylesheet" href="css/dark-mode.css">
    <!-- Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
</head>
<style>
/* Additional styles for about page */
.about-page-content {
padding: 3rem 0;
}

.about-grid {
display: grid;
grid-template-columns: 1fr 1fr;
gap: 3rem;
align-items: center;
margin-bottom: 4rem;
}

.about-image {
border-radius: var(--border-radius-lg);
overflow: hidden;
height: 400px;
display: flex;
align-items: center;
justify-content: center;
}

.about-image img {
width: 100%;
height: 100%;
object-fit: cover;
transition: transform 0.3s ease;
}

.about-image:hover img {
transform: scale(1.05);
}

.about-text h2 {
font-size: 2.5rem;
margin-bottom: 1.5rem;
color: var(--primary-color);
}

.about-text p {
margin-bottom: 1.5rem;
line-height: 1.7;
font-size: 1.1rem;
}
/* Page Banner */
.page-banner {
background: linear-gradient(rgba(67, 97, 238, 0.8), rgba(58, 12, 163, 0.8)), url('https://images.unsplash.com/photo-1522202176988-66273c2fd55f?ixlib=rb-4.0.3&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=1200&q=80');
background-size: cover;
background-position: center;
color: white;
padding: 4rem 0;
text-align: center;
margin-bottom: 3rem;
padding: 160px;
}

.page-banner h1 {
font-size: 3rem;
margin-bottom: 1rem;
}

.page-banner p {
font-size: 1.2rem;
max-width: 700px;
margin: 0 auto;
}

.section-title {
text-align: center;
font-size: 2.5rem;
margin-bottom: 3rem;
color: var(--primary-color);
}

.values-grid {
display: grid;
grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
gap: 2rem;
margin-bottom: 4rem;
}

.value-card {
padding: 2rem;
text-align: center;
transition: transform 0.3s ease;
}

.value-card:hover {
transform: translateY(-5px);
}

.value-icon {
width: 80px;
height: 80px;
border-radius: 50%;
background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
display: flex;
align-items: center;
justify-content: center;
margin: 0 auto 1.5rem;
color: white;
font-size: 2rem;
}

.value-card h3 {
font-size: 1.5rem;
margin-bottom: 1rem;
color: var(--primary-color);
}

.team-section {
margin-bottom: 4rem;
}

.team-grid {
display: grid;
grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
gap: 2rem;
}

.team-member {
padding: 2rem;
text-align: center;
transition: transform 0.3s ease;
}

.team-member:hover {
transform: translateY(-5px);
}

.member-image {
width: 150px;
height: 150px;
border-radius: 50%;
overflow: hidden;
margin: 0 auto 1.5rem;
border: 4px solid var(--primary-color);
}

.member-image img {
width: 100%;
height: 100%;
object-fit: cover;
transition: transform 0.3s ease;
}

.team-member:hover .member-image img {
transform: scale(1.1);
}

.team-member h3 {
font-size: 1.5rem;
margin-bottom: 0.5rem;
color: var(--primary-color);
}

.member-role {
color: var(--secondary-color);
font-weight: 600;
margin-bottom: 1rem;
}
/* Contact Section */
.contact-section {
padding: 3rem;
text-align: center;
margin: 4rem 0;
}

.contact-section h2 {
font-size: 2.5rem;
margin-bottom: 1.5rem;
color: var(--primary-color);
}

.contact-section > p {
margin-bottom: 2rem;
line-height: 1.7;
font-size: 1.1rem;
}

.contact-info {
display: grid;
grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
gap: 2rem;
margin-bottom: 2rem;
}

.contact-item {
display: flex;
flex-direction: column;
align-items: center;
}

.contact-item i {
font-size: 2rem;
color: var(--primary-color);
margin-bottom: 1rem;
}

.contact-item p {
margin: 0;
}

.social-links {
display: flex;
justify-content: center;
gap: 1.5rem;
}

.social-link {
display: inline-flex;
align-items: center;
justify-content: center;
width: 40px;
height: 40px;
border-radius: 50%;
background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
color: white;
transition: transform 0.3s ease;
}

.social-link:hover {
transform: translateY(-3px);
}

@media (max-width: 768px) {
.page-banner h1 {
font-size: 2.5rem;
}

.about-text h2, .section-title {
font-size: 2rem;
}

.contact-info {
grid-template-columns: 1fr;
}
}

@media (max-width: 576px) {
.navbar {
padding: 1rem;
}

.page-banner {
padding: 2rem 0;
}

.page-banner h1 {
font-size: 2rem;
}

.glass-effect {
padding: 1.5rem;
}
}
@media (max-width: 992px) {
  .about-grid {
    grid-template-columns: 1fr; /* Stack image & text */
    gap: 2rem;
  }

  .about-image {
    height: 300px; /* smaller height */
  }

  .about-text h2 {
    font-size: 2rem;
  }

  .about-text p {
    font-size: 1rem;
  }

  .page-banner {
    padding: 100px 1rem;
  }

  .page-banner h1 {
    font-size: 2.5rem;
  }
}

/* Mobile screens (max 768px) */
@media (max-width: 768px) {
  .about-grid {
    text-align: center;
  }

  .about-image {
    height: 250px;
  }

  .about-text h2 {
    font-size: 1.8rem;
  }

  .about-text p {
    font-size: 0.95rem;
  }

  .team-grid {
    grid-template-columns: 1fr 1fr; /* 2 per row */
    gap: 1.5rem;
  }

  .contact-section {
    padding: 2rem 1rem;
  }

  .contact-info {
    grid-template-columns: 1fr; /* stack */
    gap: 1.5rem;
  }

  .footer-content {
    flex-direction: column;
    align-items: center;
    text-align: center;
  }

  .footer-links {
    grid-template-columns: 1fr 1fr;
    gap: 2rem;
  }

  .footer-social {
    margin-top: 2rem;
  }
}

/* Small Mobile screens (max 576px) */
@media (max-width: 576px) {
  .page-banner {
    padding: 60px 1rem;
  }

  .page-banner h1 {
    font-size: 1.8rem;
  }

  .page-banner p {
    font-size: 1rem;
  }

  .about-text h2,
  .section-title {
    font-size: 1.5rem;
  }

  .team-grid {
    grid-template-columns: 1fr; /* stack single column */
  }

  .footer-links {
    grid-template-columns: 1fr; /* stack footer links */
  }

  .social-links {
    gap: 1rem;
  }

  .value-card {
    padding: 1.5rem;
  }
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
            <li><a href="index.php">Home</a></li>
            <li><a href="about.php" class="active">About</a></li>
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


    <!-- Page Banner -->
    <section class="page-banner">
        <div class="container">
            <h1>About PathSeeker</h1>
            <p>Learn about our mission to help you find your perfect career path</p>
        </div>
    </section>

<!-- About Content -->
<section class="about-page-content">
    <div class="container">
        <div class="about-grid">
            <div class="about-image glass-effect">
                <img src="images/team.png" alt="PathSeeker Team Collaboration">
            </div>
            <div class="about-text">
                <h2>Our Journey</h2>
                <p>PathSeeker emerged in 2023 from a shared frustration with traditional career guidance systems that often provide generic, one-size-fits-all advice. We recognized that meaningful career decisions require personalized insights that consider the whole person—their unique strengths, values, and aspirations.</p>
                <p>Our interdisciplinary team of career psychologists, data scientists, and industry experts has built an intelligent platform that combines cutting-edge assessment technology with human-centered design. We've helped over 50,000 users discover career paths that align not just with their skills, but with their purpose and vision for their lives.</p>
                <p>What sets us apart is our commitment to continuous support throughout your career journey—from initial exploration to professional advancement and transitions.</p>
            </div>
        </div>

        <!-- Mission & Values -->
        <div class="mission-values">
            <h2 class="section-title">Our Mission & Values</h2>
            <div class="values-grid">
                <div class="value-card glass-effect">
                    <div class="value-icon">
                        <i class="fas fa-compass"></i>
                    </div>
                    <h3>Personalized Guidance</h3>
                    <p>We create customized roadmaps that honor your unique combination of talents, interests, and goals, helping you navigate career decisions with confidence.</p>
                </div>
                <div class="value-card glass-effect">
                    <div class="value-icon">
                        <i class="fas fa-lightbulb"></i>
                    </div>
                    <h3>Evidence-Based Innovation</h3>
                    <p>Our platform integrates the latest research in career psychology with advanced analytics to deliver insights that are both scientifically valid and practically applicable.</p>
                </div>
                <div class="value-card glass-effect">
                    <div class="value-icon">
                        <i class="fas fa-users"></i>
                    </div>
                    <h3>Inclusive Access</h3>
                    <p>We're committed to breaking down barriers to quality career guidance by creating affordable, accessible tools that serve diverse populations across all socioeconomic backgrounds.</p>
                </div>
                <div class="value-card glass-effect">
                    <div class="value-icon">
                        <i class="fas fa-chart-line"></i>
                    </div>
                    <h3>Lifetime Growth</h3>
                    <p>We believe career development is a lifelong process, and we've designed our resources to support you through every stage of your professional evolution.</p>
                </div>
            </div>
        </div>

        <!-- Team Section -->
        <div class="team-section">
            <h2 class="section-title">The Minds Behind PathSeeker</h2>
                <div class="team-member glass-effect">
                    <div class="member-image">
                        <img src="images/3d avatar-4.png" alt="M. Ayan - Education Partnerships Director">
                    </div>
                    <h3>Ammar Ahmed Khan</h3>
                    <p class="member-role">Founder & CEO</p>
                    <p>Ammar builds relationships with educational institutions and workforce development programs, expanding our reach to students and career-changers who need guidance most.</p>
                </div>
            </div>
        </div>

        <div class="team-member glass-effect">
    <div class="member-image">
        <img src="images/3d avatar-1.png" alt="Pariwash Khan - Founder & CEO">
    </div>
    <h3>Pariwash Khan</h3>
    <p class="member-role">Founder & CEO</p>
    <p>As our visionary leader, Pariwash founded PathSeeker with a mission to democratize career guidance. With over 10 years of experience in education technology and career development, she drives our strategic direction and ensures we stay true to our mission of helping people discover fulfilling career paths.</p>
</div>





        <!-- Contact Section -->
        <div class="contact-section glass-effect">
            <h2>Connect With Us</h2>
            <p>Have questions about your career journey? Our team is here to provide guidance and support.</p>
            <div class="contact-info">
                <!-- Email -->
                <div class="contact-item">
                    <i class="fas fa-envelope"></i>
                    <a href="https://mail.google.com/mail/?view=cm&fs=1&to=info@pathseeker.com" target="_blank">
                        info@pathseeker.com
                    </a>
                </div>
                <!-- Phone -->
                <div class="contact-item">
                    <i class="fas fa-phone"></i>
                    <a href="tel:+15551234567">+1 (555) 123-4567</a>
                </div>
                <!-- Address -->
                <div class="contact-item">
                    <i class="fas fa-map-marker-alt"></i>
                    <a href="https://www.google.com/maps/search/?api=1&query=123+Career+Avenue,+San+Francisco,+CA+94105" target="_blank">
                        123 Career Avenue, San Francisco, CA 94105
                    </a>
                </div>
            </div>

            <!-- Social Links -->
            <div class="social-links">
                <a href="https://www.facebook.com/" class="social-link" aria-label="Visit our Facebook page"><i class="fab fa-facebook-f"></i></a>
                <a href="https://x.com/" class="social-link" aria-label="Follow us on Twitter"><i class="fab fa-twitter"></i></a>
                <a href="https://pk.linkedin.com/" class="social-link" aria-label="Connect on LinkedIn"><i class="fab fa-linkedin-in"></i></a>
                <a href="https://www.instagram.com/" class="social-link" aria-label="Follow us on Instagram"><i class="fab fa-instagram"></i></a>
            </div>
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
                            <li><a href="resources.php">Resource Library</a></li>
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

    <!-- JavaScript -->
    <script src="js/main.js"></script>
    <script src="js/accessibility.js"></script>
    <script src="js/update-footers.js"></script>
</body>
</html>