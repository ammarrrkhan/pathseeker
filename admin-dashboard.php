<?php
session_start();

// Redirect to admin login if not authenticated
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: admin-login.php');
    exit();
}

include 'db.php'; // Make sure db.php defines $conn = new mysqli(...);

try {
    // Get user statistics
    $user_count = $conn->query("SELECT COUNT(*) AS count FROM Users")->fetch_assoc()['count'];
    $student_count = $conn->query("SELECT COUNT(*) AS count FROM Users WHERE role = 'student'")->fetch_assoc()['count'];
    $graduate_count = $conn->query("SELECT COUNT(*) AS count FROM Users WHERE role = 'graduate'")->fetch_assoc()['count'];
    $professional_count = $conn->query("SELECT COUNT(*) AS count FROM Users WHERE role = 'professional'")->fetch_assoc()['count'];

    // Get recent users
    $recent_users = [];
    $recent_users_stmt = $conn->query("SELECT user_id, uname, email, role, created_at FROM Users ORDER BY created_at DESC LIMIT 5");
    if ($recent_users_stmt) {
        while ($row = $recent_users_stmt->fetch_assoc()) {
            $recent_users[] = $row;
        }
    }

} catch (Exception $e) {
    $error = "Database error: " . $e->getMessage();
}

// Handle logout
if (isset($_GET['logout'])) {
    session_destroy();
    header('Location: admin-login.php');
    exit();
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - PathSeeker</title>
    <link rel="stylesheet" href="css/styles.css">
    <link rel="stylesheet" href="css/responsive.css">
    <link rel="stylesheet" href="css/update-footers.css">
    <!-- Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        .admin-dashboard-container {
            min-height: 100vh;
            padding-top: 80px;
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            padding: 100px 2rem 2rem;
        }

        .dark-mode .admin-dashboard-container {
            background: linear-gradient(135deg, #2d3748 0%, #4a5568 100%);
        }

        .admin-welcome-section {
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

        .dark-mode .admin-welcome-section {
            background-color: var(--glass-bg-dark);
            border: 1px solid var(--glass-border-dark);
        }

        .admin-welcome-section h1 {
            font-size: 2.5rem;
            margin-bottom: 0.5rem;
            color: var(--primary-color);
        }

        .admin-welcome-section p {
            font-size: 1.2rem;
            color: var(--text-color);
            margin-bottom: 1.5rem;
        }

        .admin-dashboard-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 2rem;
            max-width: 1200px;
            margin: 0 auto;
        }

        .admin-dashboard-card {
            background-color: var(--glass-bg);
            backdrop-filter: var(--glass-blur);
            -webkit-backdrop-filter: var(--glass-blur);
            border-radius: var(--border-radius-lg);
            border: 1px solid var(--glass-border);
            box-shadow: var(--glass-shadow);
            padding: 2rem;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .dark-mode .admin-dashboard-card {
            background-color: var(--glass-bg-dark);
            border: 1px solid var(--glass-border-dark);
        }

        .admin-dashboard-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
        }

        .admin-card-header {
            display: flex;
            align-items: center;
            margin-bottom: 1.5rem;
        }

        .admin-card-icon {
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

        .admin-card-title {
            font-size: 1.5rem;
            font-weight: 600;
            color: var(--primary-color);
        }

        .admin-card-value {
            font-size: 2.5rem;
            font-weight: 700;
            color: var(--text-color);
            margin-bottom: 1rem;
        }

        .admin-card-description {
            color: var(--text-color-light);
            margin-bottom: 1.5rem;
        }

        .admin-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 1rem;
        }

        .admin-table th,
        .admin-table td {
            padding: 0.75rem;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        .dark-mode .admin-table th,
        .dark-mode .admin-table td {
            border-color: #4a5568;
        }

        .admin-table th {
            background-color: rgba(74, 107, 255, 0.1);
            font-weight: 600;
        }

        .dark-mode .admin-table th {
            background-color: rgba(74, 107, 255, 0.2);
        }

        .admin-table tr:hover {
            background-color: rgba(74, 107, 255, 0.05);
        }

        .dark-mode .admin-table tr:hover {
            background-color: rgba(74, 107, 255, 0.1);
        }

        .admin-actions {
            display: flex;
            justify-content: center;
            margin-top: 3rem;
        }

        .admin-logout-btn {
            padding: 0.75rem 1.5rem;
            background-color: #e74c3c;
            color: white;
            border: none;
            border-radius: var(--border-radius-md);
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-block;
        }

        .admin-logout-btn:hover {
            background-color: #c0392b;
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .admin-back-link {
            display: block;
            text-align: center;
            margin-top: 2rem;
            color: var(--primary-color);
            text-decoration: none;
        }

        .admin-back-link:hover {
            text-decoration: underline;
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


    <!-- Admin Dashboard Container -->
    <div class="admin-dashboard-container">
        <div class="admin-welcome-section">
            <h1>Welcome, Admin!</h1>
            <p>Manage PathSeeker users and content from this dashboard</p>
        </div>

        <div class="admin-dashboard-grid">
            <!-- User Statistics Card -->
            <div class="admin-dashboard-card">
                <div class="admin-card-header">
                    <div class="admin-card-icon">
                        <i class="fas fa-users"></i>
                    </div>
                    <h3 class="admin-card-title">Total Users</h3>
                </div>
                <div class="admin-card-value"><?php echo $user_count; ?></div>
                <p class="admin-card-description">Total registered users on PathSeeker</p>
            </div>

            <!-- Students Card -->
            <div class="admin-dashboard-card">
                <div class="admin-card-header">
                    <div class="admin-card-icon">
                        <i class="fas fa-graduation-cap"></i>
                    </div>
                    <h3 class="admin-card-title">Students</h3>
                </div>
                <div class="admin-card-value"><?php echo $student_count; ?></div>
                <p class="admin-card-description">Users with student role</p>
            </div>

            <!-- Graduates Card -->
            <div class="admin-dashboard-card">
                <div class="admin-card-header">
                    <div class="admin-card-icon">
                        <i class="fas fa-user-graduate"></i>
                    </div>
                    <h3 class="admin-card-title">Graduates</h3>
                </div>
                <div class="admin-card-value"><?php echo $graduate_count; ?></div>
                <p class="admin-card-description">Users with graduate role</p>
            </div>

            <!-- Professionals Card -->
            <div class="admin-dashboard-card">
                <div class="admin-card-header">
                    <div class="admin-card-icon">
                        <i class="fas fa-briefcase"></i>
                    </div>
                    <h3 class="admin-card-title">Professionals</h3>
                </div>
                <div class="admin-card-value"><?php echo $professional_count; ?></div>
                <p class="admin-card-description">Users with professional role</p>
            </div>
        </div>

        <!-- Recent Users Table -->
        <div class="admin-dashboard-card" style="grid-column: 1 / -1; margin-top: 2rem;">
            <div class="admin-card-header">
                <div class="admin-card-icon">
                    <i class="fas fa-clock"></i>
                </div>
                <h3 class="admin-card-title">Recent Users</h3>
            </div>
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Username</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Joined</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($recent_users as $user): ?>
                    <tr>
                        <td><?php echo $user['user_id']; ?></td>
                        <td><?php echo htmlspecialchars($user['uname']); ?></td>
                        <td><?php echo htmlspecialchars($user['email']); ?></td>
                        <td><?php echo ucfirst($user['role']); ?></td>
                        <td><?php echo date('M j, Y', strtotime($user['created_at'])); ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <div class="admin-actions">
            <a href="?logout=true" class="admin-logout-btn">Logout</a>
        </div>

        <a href="index.php" class="admin-back-link">← Back to Home</a>
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