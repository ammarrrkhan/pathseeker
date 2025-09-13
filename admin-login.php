<?php
session_start();

include 'db.php';

// Initialize variables
$message = '';
$message_type = '';

// Check if already logged in as admin
if (isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true) {
    header('Location: admin-dashboard.php');
    exit();
}

// Process admin login
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'admin_login') {
    $admin_username = trim($_POST['username']);
    $admin_password = $_POST['password'];
    
    // Validate inputs
    if (empty($admin_username) || empty($admin_password)) {
        $message = 'Please enter both username and password';
        $message_type = 'error';
    } else {
        // Check admin credentials (hardcoded for this example)
        if ($admin_username === 'admin' && $admin_password === 'admin123') {
            // Login successful
            $_SESSION['admin_logged_in'] = true;
            $_SESSION['admin_username'] = $admin_username;
            
            // Redirect to admin dashboard
            header('Location: admin-dashboard.php');
            exit();
        } else {
            $message = 'Invalid admin credentials';
            $message_type = 'error';
        }
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - PathSeeker</title>
    <link rel="stylesheet" href="css/styles.css">
    <link rel="stylesheet" href="css/responsive.css">
    <link rel="stylesheet" href="css/dark-mode.css">
    <!-- Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        /* Admin login specific styles */
        .admin-login-container {
            min-height: 100vh;
            padding-top: 80px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            padding: 2rem;
        }

        .dark-mode .admin-login-container {
            background: linear-gradient(135deg, #2d3748 0%, #4a5568 100%);
        }

        .admin-login-box {
            width: 100%;
            max-width: 450px;
            background-color: var(--glass-bg);
            backdrop-filter: var(--glass-blur);
            -webkit-backdrop-filter: var(--glass-blur);
            border-radius: var(--border-radius-lg);
            border: 1px solid var(--glass-border);
            box-shadow: var(--glass-shadow);
            padding: var(--spacing-lg);
            margin: 70px;
        }

        .dark-mode .admin-login-box {
            background-color: var(--glass-bg-dark);
            border: 1px solid var(--glass-border-dark);
        }

        .admin-login-header {
            text-align: center;
            margin-bottom: 2rem;
        }

        .admin-login-header h2 {
            font-size: 2rem;
            color: var(--primary-color);
            margin-bottom: 0.5rem;
        }

        .admin-login-header p {
            color: var(--text-color);
        }

        .admin-form-group {
            margin-bottom: 1.5rem;
        }

        .admin-form-group label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 500;
        }

        .admin-form-control {
            width: 100%;
            padding: 0.75rem 1rem;
            border: 1px solid #ddd;
            border-radius: var(--border-radius-md);
            font-family: var(--body-font);
            font-size: 1rem;
            transition: all 0.3s ease;
        }

        .dark-mode .admin-form-control {
            background-color: #2d3748;
            border-color: #4a5568;
            color: white;
        }

        .admin-form-control:focus {
            border-color: var(--primary-color);
            outline: none;
            box-shadow: 0 0 0 3px rgba(74, 107, 255, 0.2);
        }

        .admin-submit-btn {
            width: 100%;
            padding: 0.75rem;
            background-color: var(--primary-color);
            color: white;
            border: none;
            border-radius: var(--border-radius-md);
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .admin-submit-btn:hover {
            background-color: var(--secondary-color);
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .admin-back-link {
            display: block;
            text-align: center;
            margin-top: 1.5rem;
            color: var(--primary-color);
            text-decoration: none;
        }

        .admin-back-link:hover {
            text-decoration: underline;
        }

        /* Dark mode styles for navbar */
        .dark-mode .navbar {
            background: linear-gradient(135deg, #2b2d42, #1a202c);
            border-bottom: 1px solid var(--glass-border-dark);
        }

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

        /* Mobile menu toggle */
        .dark-mode .menu-toggle .bar {
            background-color: #ffffff;
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
            <li><a href="login.php">Login / Signup</a></li>
            <!-- Buttons -->
            <li><a href="admin-login.php" class="nav-btn admin-btn active">Admin Portal</a></li>
        </ul>
    </nav>
</header>


    <!-- Admin Login Container -->
    <div class="admin-login-container">
        <div class="admin-login-box">
            <div class="admin-login-header">
                <h2>Admin Portal</h2>
                <p>Access the PathSeeker administration panel</p>
            </div>
            
            <!-- Display messages if any -->
            <?php if (!empty($message)): ?>
                <div class="message <?php echo $message_type; ?>">
                    <?php echo $message; ?>
                </div>
            <?php endif; ?>
            
            <!-- Admin Login Form -->
            <form method="POST" action="">
                <input type="hidden" name="action" value="admin_login">
                <div class="admin-form-group">
                    <label for="adminUsername">Username</label>
                    <input type="text" id="adminUsername" name="username" class="admin-form-control" placeholder="Enter admin username" required>
                </div>
                <div class="admin-form-group">
                    <label for="adminPassword">Password</label>
                    <input type="password" id="adminPassword" name="password" class="admin-form-control" placeholder="Enter admin password" required>
                </div>
                <button type="submit" class="admin-submit-btn">Login as Admin</button>
            </form>
            
            <a href="index.php" class="admin-back-link">← Back to Home</a>
        </div>
    </div>

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
</body>
</html>