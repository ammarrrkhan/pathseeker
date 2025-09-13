<?php
session_start();

// Redirect to login if not authenticated
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

include 'db.php'; // must define $host, $dbname, $username, $password

// Create MySQLi connection
$mysqli = new mysqli($host, $user, $pass, $dbname);

// Check connection
if ($mysqli->connect_errno) {
    die("Database connection failed: " . $mysqli->connect_error);
}

// Ensure charset
$mysqli->set_charset("utf8mb4");

// Initialize variables
$user = [];
$profile = [];
$error = "";

// Get user data
$stmt = $mysqli->prepare("SELECT uname, email, role FROM Users WHERE user_id = ?");
if ($stmt) {
    $stmt->bind_param("i", $_SESSION['user_id']);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();
    $stmt->close();
} else {
    $error = "Database error: " . $mysqli->error;
}

// Get user profile data
$stmt = $mysqli->prepare("SELECT education_level, interests FROM UserProfiles WHERE user_id = ?");
if ($stmt) {
    $stmt->bind_param("i", $_SESSION['user_id']);
    $stmt->execute();
    $result = $stmt->get_result();
    $profile = $result->fetch_assoc();
    $stmt->close();
} else {
    $error = "Database error: " . $mysqli->error;
}

// Handle logout
if (isset($_GET['logout'])) {
    session_destroy();
    header('Location: login.php');
    exit();
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - PathSeeker</title>
    <link rel="stylesheet" href="css/styles.css">
    <link rel="stylesheet" href="css/responsive.css">
    <link rel="stylesheet" href="css/dark-mode.css">
    <!-- Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
.dashboard-container {
min-height: 100vh;
padding-top: 80px;
background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
padding: 100px 2rem 2rem;
}

.dark-mode .dashboard-container {
background: linear-gradient(135deg, #2d3748 0%, #4a5568 100%);
}
.dark-mode .navbar {
background: linear-gradient(135deg, #2b2d42, #1a202c);
border-bottom: 1px solid var(--glass-border-dark);
}

/* Make all nav links white in dark mode */
.dark-mode .nav-menu a {
color: #ffffff !important;
}

.dark-mode .nav-menu a:hover {
color: #ffd166 !important;
}


.dark-mode .nav-btn {
background-color: var(--primary-color);
color: var(--light-color) !important;
}

.dark-mode .nav-btn:hover {
background-color: var(--accent-color-2);
}

.dark-mode .logo a {
color: #ffffff !important;
}

/* Mobile menu toggle (if you have one) */
.dark-mode .menu-toggle .bar {
background-color: #ffffff;
}
.welcome-section {
text-align: center;
margin-bottom: 3rem;
padding: 2rem;
background-color: var(--glass-bg);
backdrop-filter: var(--glass-blur);
-webkit-backdrop-filter: var(--glass-blur);
border-radius: var(--border-radius-lg);
border: 1px solid var(--glass-border);
box-shadow: var(--glass-shadow);
max-width: 800px;
margin: 0 auto 3rem;
}

.dark-mode .welcome-section {
background-color: var(--glass-bg-dark);
border: 1px solid var(--glass-border-dark);
}

.welcome-section h1 {
font-size: 2.5rem;
margin-bottom: 0.5rem;
color: var(--primary-color);
}

.welcome-section p {
font-size: 1.2rem;
color: var(--text-color);
margin-bottom: 1.5rem;
}

.user-role {
display: inline-block;
padding: 0.5rem 1.5rem;
background-color: var(--primary-color);
color: white;
border-radius: 50px;
font-weight: 600;
font-size: 0.9rem;
}

.dashboard-grid {
display: grid;
grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
gap: 2rem;
max-width: 1200px;
margin: 0 auto;
}

.dashboard-card {
background-color: var(--glass-bg);
backdrop-filter: var(--glass-blur);
-webkit-backdrop-filter: var(--glass-blur);
border-radius: var(--border-radius-lg);
border: 1px solid var(--glass-border);
box-shadow: var(--glass-shadow);
padding: 2rem;
transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.dark-mode .dashboard-card {
background-color: var(--glass-bg-dark);
border: 1px solid var(--glass-border-dark);
}

.dashboard-card:hover {
transform: translateY(-5px);
box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
}

.card-header {
display: flex;
align-items: center;
margin-bottom: 1.5rem;
}

.card-icon {
width: 50px;
height: 50px;
border-radius: 50%;
background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
display: flex;
align-items: center;
justify-content: center;
margin-right: 1rem;
color: white;
font-size: 1.5rem;
}

.card-title {
font-size: 1.5rem;
font-weight: 600;
margin: 0;
}

.card-content {
margin-bottom: 1.5rem;
}

.profile-info {
margin-bottom: 1rem;
}

.profile-info p {
margin: 0.5rem 0;
display: flex;
align-items: center;
}

.profile-info i {
margin-right: 0.5rem;
color: var(--primary-color);
width: 20px;
}

.progress-container {
margin: 1.5rem 0;
}

.progress-label {
display: flex;
justify-content: between;
margin-bottom: 0.5rem;
}

.progress-bar {
height: 10px;
background-color: #e9ecef;
border-radius: 5px;
overflow: hidden;
}

.dark-mode .progress-bar {
background-color: #4a5568;
}

.progress-value {
height: 100%;
background: linear-gradient(90deg, var(--primary-color), var(--secondary-color));
border-radius: 5px;
}

.btn {
display: inline-block;
padding: 0.75rem 1.5rem;
background-color: var(--primary-color);
color: white;
border: none;
border-radius: var(--border-radius-md);
font-weight: 600;
cursor: pointer;
transition: all 0.3s ease;
text-decoration: none;
text-align: center;
}

.btn:hover {
background-color: var(--secondary-color);
transform: translateY(-2px);
box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
}

.btn-block {
display: block;
width: 100%;
}

.btn-logout {
background-color: #e53e3e;
}

.btn-logout:hover {
background-color: #c53030;
}

.stats-grid {
display: grid;
grid-template-columns: repeat(2, 1fr);
gap: 1rem;
margin-top: 1.5rem;
}

.stat-item {
text-align: center;
padding: 1rem;
background-color: rgba(74, 107, 255, 0.1);
border-radius: var(--border-radius-md);
}

.dark-mode .stat-item {
background-color: rgba(74, 107, 255, 0.2);
}

.stat-value {
font-size: 1.5rem;
font-weight: 700;
color: var(--primary-color);
margin-bottom: 0.25rem;
}

.stat-label {
font-size: 0.9rem;
color: var(--text-color);
}

.resources-list {
list-style: none;
padding: 0;
margin: 0;
}

.resources-list li {
padding: 0.75rem 0;
border-bottom: 1px solid #eee;
}

.dark-mode .resources-list li {
border-bottom: 1px solid #4a5568;
}

.resources-list li:last-child {
border-bottom: none;
}

.resources-list a {
color: var(--text-color);
text-decoration: none;
display: flex;
align-items: center;
transition: color 0.3s ease;
}

.resources-list a:hover {
color: var(--primary-color);
}

.resources-list i {
margin-right: 0.5rem;
color: var(--primary-color);
}

@media screen and (max-width: 768px) {
.dashboard-grid {
grid-template-columns: 1fr;
}

.welcome-section h1 {
font-size: 2rem;
}

.stats-grid {
grid-template-columns: 1fr;
}
}

/* Notification styles */
.notification {
    position: fixed;
    top: 20px;
    right: 20px;
    padding: 15px 25px;
    background-color: #4CAF50;
    color: white;
    border-radius: 5px;
    box-shadow: 0 4px 8px rgba(0,0,0,0.1);
    z-index: 1000;
    opacity: 0;
    transform: translateY(-20px);
    transition: opacity 0.3s, transform 0.3s;
}

.notification.show {
    opacity: 1;
    transform: translateY(0);
}

.notification.error {
    background-color: #f44336;
}
.center-div {
display: flex;
justify-content: center;  /* center horizontally */
align-items: center;      /* center vertically */
height: 10vh;            /* full screen height */
background-color: var(--light-color);
}

.center-div a {
text-decoration: none;
padding: 12px 24px;
background: #4a6bff;
color: white;
font-size: 18px;
border-radius: 8px;
transition: 0.3s;
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
            <li><a href="about.php">About</a></li>
            <li><a href="careers.php">Careers</a></li>
            <li><a href="quiz.php">Quiz</a></li>
            <li><a href="resources.php">Resources</a></li>
            <li><a href="success-stories.php">Success Stories</a></li>
            <li><a href="login.php" class="active">Login / Signup</a></li>
            <!-- Buttons -->
            <li><a href="admin-login.php" class="nav-btn admin-btn">Admin Portal</a></li>
        </ul>
    </nav>
</header>


    <!-- Dashboard Content -->
    <div class="dashboard-container">
        <!-- Welcome Section -->
        <div class="welcome-section">
            <h1>Welcome, <?php echo isset($user['uname']) ? htmlspecialchars($user['uname']) : 'User'; ?></h1>
            <p>Your career journey continues here. Explore opportunities and track your progress.</p>
            <div class="user-role">
                <?php echo isset($user['role']) ? ucfirst(htmlspecialchars($user['role'])) : 'Member'; ?>
            </div>
        </div>
        
        <!-- Dashboard Grid -->
        <div class="dashboard-grid">
            <!-- Profile Card -->
            <div class="dashboard-card">
    <div class="card-header">
        <div class="card-icon">
            <i class="fas fa-user"></i>
        </div>
        <h2 class="card-title">Your Profile</h2>
    </div>
    <div class="card-content">
        <div class="profile-info">
            <p><i class="fas fa-user"></i> <?php echo isset($user['uname']) ? htmlspecialchars($user['uname']) : 'Not set'; ?></p>
            <p><i class="fas fa-envelope"></i> <?php echo isset($user['email']) ? htmlspecialchars($user['email']) : 'Not set'; ?></p>
            <p><i class="fas fa-graduation-cap"></i> 
                <?php 
                if (isset($profile['education_level']) && !empty($profile['education_level'])) {
                    echo htmlspecialchars($profile['education_level']);
                } else {
                    echo 'Not specified';
                }
                ?>
            </p>
            <p><i class="fas fa-heart"></i> 
                <?php 
                if (isset($profile['interests']) && !empty($profile['interests'])) {
                    echo htmlspecialchars($profile['interests']);
                } else {
                    echo 'Not specified';
                }
                ?>
            </p>
        </div>
        <a href="edit-profile.php" class="btn btn-block" style="margin-bottom: 1rem;">
            <i class="fa-solid fa-user"></i>  Edit Profile
        </a>
        <a href="?logout=true" class="btn btn-block" style="margin-bottom: 1rem;">
            <i class="fa-solid fa-right-from-bracket"></i> Logout
        </a>
    </div>
</div>
            
            <!-- Progress Card -->
            <div class="dashboard-card">
                <div class="card-header">
                    <div class="card-icon">
                        <i class="fas fa-chart-line"></i>
                    </div>
                    <h2 class="card-title">Your Progress</h2>
                </div>
                <div class="card-content">
                    <div class="progress-container">
                        <div class="progress-label">
                            <span>Profile Completion</span>
                            <span>65%</span>
                        </div>
                        <div class="progress-bar">
                            <div class="progress-value" style="width: 65%"></div>
                        </div>
                    </div>
                    
                    <div class="progress-container">
                        <div class="progress-label">
                            <span>Career Quiz</span>
                            <span>40%</span>
                        </div>
                        <div class="progress-bar">
                            <div class="progress-value" style="width: 40%"></div>
                        </div>
                    </div>
                    
                    <div class="progress-container">
                        <div class="progress-label">
                            <span>Resources Explored</span>
                            <span>25%</span>
                        </div>
                        <div class="progress-bar">
                            <div class="progress-value" style="width: 25%"></div>
                        </div>
                    </div>
                    
                    <div class="stats-grid">
                        <div class="stat-item">
                            <div class="stat-value">3</div>
                            <div class="stat-label">Completed Tasks</div>
                        </div>
                        <div class="stat-item">
                            <div class="stat-value">7</div>
                            <div class="stat-label">Career Matches</div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Quick Actions Card -->
            <div class="dashboard-card">
                <div class="card-header">
                    <div class="card-icon">
                        <i class="fas fa-bolt"></i>
                    </div>
                    <h2 class="card-title">Quick Actions</h2>
                </div>
                <div class="card-content">
                    <a href="quiz.php" class="btn btn-block" style="margin-bottom: 1rem;">
                        <i class="fas fa-puzzle-piece"></i> Take Career Quiz
                    </a>
                    <a href="careers.php" class="btn btn-block" style="margin-bottom: 1rem;">
                        <i class="fas fa-briefcase"></i> Explore Careers
                    </a>
                    <a href="resources.php" class="btn btn-block" style="margin-bottom: 1rem;">
                        <i class="fas fa-book"></i> Browse Resources
                    </a>
                    <a href="success-stories.php" class="btn btn-block">
                        <i class="fas fa-star"></i> Read Success Stories
                    </a>
                </div>
            </div>
            
            <!-- Recommended Resources Card -->
            <div class="dashboard-card">
                <div class="card-header">
                    <div class="card-icon">
                        <i class="fas fa-bookmark"></i>
                    </div>
                    <h2 class="card-title">Recommended For You</h2>
                </div>
                <div class="card-content">
                    <ul class="resources-list">
                        <li>
                            <a href="#">
                                <i class="fas fa-file-pdf"></i> 
                                <span>Guide to Resume Building</span>
                            </a>
                        </li>
                        <li>
                            <a href="#">
                                <i class="fas fa-video"></i> 
                                <span>Interview Preparation Tips</span>
                            </a>
                        </li>
                        <li>
                            <a href="#">
                                <i class="fas fa-link"></i> 
                                <span>Networking Strategies</span>
                            </a>
                        </li>
                        <li>
                            <a href="#">
                                <i class="fas fa-chart-bar"></i> 
                                <span>Industry Growth Reports</span>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

        <!-- <div class="center-div">
            <a href="?logout=true" class="btn secondary-btn">Logout</a>
        </div> -->

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
    <div class="accessibility-panel">
        <button class="accessibility-toggle" id="accessibility-toggle">
            <i class="fas fa-universal-access"></i>
        </button>
        <div class="accessibility-options" id="accessibility-options">
            <div class="option">
                <label for="dark-mode-toggle">Dark Mode</label>
                <label class="switch">
                    <input type="checkbox" id="dark-mode-toggle">
                    <span class="slider round"></span>
                </label>
            </div>
            <div class="option">
                <label for="font-size">Font Size</label>
                <div class="font-controls">
                    <button id="decrease-font">A-</button>
                    <button id="reset-font">Reset</button>
                    <button id="increase-font">A+</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Notification -->
    <div class="notification" id="notification"></div>

    <!-- JavaScript Files -->
    <script src="js/main.js"></script>
    <script src="js/accessibility.js"></script>
    <script src="js/update-footers.js"></script>
    
    <script>
    // Store login state in localStorage
    document.addEventListener('DOMContentLoaded', function() {
        // Set the login state in localStorage
        localStorage.setItem('isLoggedIn', 'true');
        
        // Show welcome notification
        showNotification('Welcome back! You are now logged in.', 'success');
        
        // Check if this is a redirect from login
        const urlParams = new URLSearchParams(window.location.search);
        if (urlParams.has('login_success')) {
            showNotification('Login successful! Welcome to your dashboard.', 'success');
        }
    });
    
    // Function to show notification
    function showNotification(message, type = 'success') {
        const notification = document.getElementById('notification');
        notification.textContent = message;
        notification.className = 'notification show';
        if (type === 'error') {
            notification.classList.add('error');
        }
        
        setTimeout(() => {
            notification.className = 'notification';
        }, 3000);
    }
    
    // Check login state on page load and update navigation
    window.addEventListener('load', function() {
        const isLoggedIn = localStorage.getItem('isLoggedIn') === 'true';
        const dashboardNavItem = document.getElementById('dashboard-nav-item');
        const logoutNavItem = document.getElementById('logout-nav-item');
        
        if (!isLoggedIn) {
            if (dashboardNavItem) dashboardNavItem.style.display = 'none';
            if (logoutNavItem) logoutNavItem.style.display = 'none';
        } else {
            if (dashboardNavItem) dashboardNavItem.style.display = 'list-item';
            if (logoutNavItem) logoutNavItem.style.display = 'list-item';
        }
    });
    
    // Handle logout
    document.addEventListener('click', function(e) {
        if (e.target.getAttribute('href') === '?logout') {
            e.preventDefault();
            
            // Clear the login state
            localStorage.setItem('isLoggedIn', 'false');
            
            // Show logout notification
            showNotification('You have been logged out successfully.', 'success');
            
            // Hide dashboard and logout links
            const dashboardNavItem = document.getElementById('dashboard-nav-item');
            const logoutNavItem = document.getElementById('logout-nav-item');
            
            if (dashboardNavItem) dashboardNavItem.style.display = 'none';
            if (logoutNavItem) logoutNavItem.style.display = 'none';
            
            // Redirect to login page after a delay
            setTimeout(() => {
                window.location.href = 'login.php';
            }, 1500);
        }
    });
    </script>
</body>
</html>