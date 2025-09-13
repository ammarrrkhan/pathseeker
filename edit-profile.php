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

$mysqli->set_charset("utf8mb4");

// Initialize
$user = [];
$profile = [];
$error = "";
$success = "";

// -------------------
// Get user data
// -------------------
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

// -------------------
// Get user profile
// -------------------
$stmt = $mysqli->prepare("SELECT education_level, interests FROM UserProfiles WHERE user_id = ?");
if ($stmt) {
    $stmt->bind_param("i", $_SESSION['user_id']);
    $stmt->execute();
    $result = $stmt->get_result();
    $profile = $result->fetch_assoc();
    $stmt->close();
}

// -------------------
// Handle form submission
// -------------------
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $education_level = $_POST['education_level'];
    $interests = $_POST['interests'];

    // Try update first
    $stmt = $mysqli->prepare("UPDATE UserProfiles SET education_level = ?, interests = ? WHERE user_id = ?");
    if ($stmt) {
        $stmt->bind_param("ssi", $education_level, $interests, $_SESSION['user_id']);
        $stmt->execute();

        if ($stmt->affected_rows === 0) {
            // If no rows updated, insert instead
            $stmt->close();
            $stmt = $mysqli->prepare("INSERT INTO UserProfiles (user_id, education_level, interests) VALUES (?, ?, ?)");
            if ($stmt) {
                $stmt->bind_param("iss", $_SESSION['user_id'], $education_level, $interests);
                $stmt->execute();
            }
        }
        $stmt->close();

        // Refresh profile data
        $stmt = $mysqli->prepare("SELECT education_level, interests FROM UserProfiles WHERE user_id = ?");
        if ($stmt) {
            $stmt->bind_param("i", $_SESSION['user_id']);
            $stmt->execute();
            $result = $stmt->get_result();
            $profile = $result->fetch_assoc();
            $stmt->close();
        }

        $success = "Profile updated successfully!";
    } else {
        $error = "Database error: " . $mysqli->error;
    }
}

// -------------------
// Handle logout
// -------------------
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
    <title>Edit Profile - PathSeeker</title>
    <link rel="stylesheet" href="css/styles.css">
    <link rel="stylesheet" href="css/responsive.css">
    <!-- Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        .edit-profile-container {
            min-height: 100vh;
            padding-top: 80px;
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            padding: 100px 2rem 2rem;
        }

        .dark-mode .edit-profile-container {
            background: linear-gradient(135deg, #2d3748 0%, #4a5568 100%);
        }

        .edit-profile-card {
            max-width: 600px;
            margin: 0 auto;
            background-color: var(--glass-bg);
            backdrop-filter: var(--glass-blur);
            -webkit-backdrop-filter: var(--glass-blur);
            border-radius: var(--border-radius-lg);
            border: 1px solid var(--glass-border);
            box-shadow: var(--glass-shadow);
            padding: 2rem;
        }

        .dark-mode .edit-profile-card {
            background-color: var(--glass-bg-dark);
            border: 1px solid var(--glass-border-dark);
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
            font-size: 1.8rem;
            font-weight: 600;
            margin: 0;
            color: var(--primary-color);
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 500;
            color: var(--text-color);
        }

        .form-control {
            width: 100%;
            padding: 0.75rem 1rem;
            border: 1px solid #ddd;
            border-radius: var(--border-radius-md);
            font-family: 'Poppins', sans-serif;
            font-size: 1rem;
            transition: border-color 0.3s ease;
        }

        .dark-mode .form-control {
            background-color: #2d3748;
            border-color: #4a5568;
            color: #fff;
        }

        .form-control:focus {
            outline: none;
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(74, 107, 255, 0.3);
        }

        textarea.form-control {
            min-height: 100px;
            resize: vertical;
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

        .alert {
            padding: 1rem;
            border-radius: var(--border-radius-md);
            margin-bottom: 1.5rem;
        }

        .alert-success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        .alert-error {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }

        .dark-mode .alert-success {
            background-color: #22543d;
            color: #9ae6b4;
            border-color: #2d6a4f;
        }

        .dark-mode .alert-error {
            background-color: #742a2a;
            color: #feb2b2;
            border-color: #823434;
        }

        @media screen and (max-width: 768px) {
            .edit-profile-container {
                padding: 80px 1rem 1rem;
            }
            
            .edit-profile-card {
                padding: 1.5rem;
            }
            
            .card-title {
                font-size: 1.5rem;
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


    <!-- Edit Profile Content -->
    <div class="edit-profile-container">
        <div class="edit-profile-card">
            <div class="card-header">
                <div class="card-icon">
                    <i class="fas fa-user-edit"></i>
                </div>
                <h2 class="card-title">Edit Your Profile</h2>
            </div>
            
            <?php if (isset($success)): ?>
                <div class="alert alert-success">
                    <i class="fas fa-check-circle"></i> <?php echo $success; ?>
                </div>
            <?php endif; ?>
            
            <?php if (isset($error)): ?>
                <div class="alert alert-error">
                    <i class="fas fa-exclamation-circle"></i> <?php echo $error; ?>
                </div>
            <?php endif; ?>
            
            <form method="POST" action="">
                <div class="form-group">
                    <label for="username"><i class="fas fa-user"></i> Username</label>
                    <input type="text" class="form-control" id="username" value="<?php echo isset($user['uname']) ? htmlspecialchars($user['uname']) : ''; ?>" disabled>
                    <small>Username cannot be changed</small>
                </div>
                
                <div class="form-group">
                    <label for="email"><i class="fas fa-envelope"></i> Email</label>
                    <input type="email" class="form-control" id="email" value="<?php echo isset($user['email']) ? htmlspecialchars($user['email']) : ''; ?>" disabled>
                    <small>Email cannot be changed</small>
                </div>
                
                <div class="form-group">
                    <label for="education_level"><i class="fas fa-graduation-cap"></i> Education Level</label>
                    <select class="form-control" id="education_level" name="education_level" required>
                        <option value="">Select your education level</option>
                        <option value="High School" <?php echo (isset($profile['education_level']) && $profile['education_level'] == 'High School') ? 'selected' : ''; ?>>High School</option>
                        <option value="Some College" <?php echo (isset($profile['education_level']) && $profile['education_level'] == 'Some College') ? 'selected' : ''; ?>>Some College</option>
                        <option value="Associate's Degree" <?php echo (isset($profile['education_level']) && $profile['education_level'] == 'Associate\'s Degree') ? 'selected' : ''; ?>>Associate's Degree</option>
                        <option value="Bachelor's Degree" <?php echo (isset($profile['education_level']) && $profile['education_level'] == 'Bachelor\'s Degree') ? 'selected' : ''; ?>>Bachelor's Degree</option>
                        <option value="Master's Degree" <?php echo (isset($profile['education_level']) && $profile['education_level'] == 'Master\'s Degree') ? 'selected' : ''; ?>>Master's Degree</option>
                        <option value="Doctorate" <?php echo (isset($profile['education_level']) && $profile['education_level'] == 'Doctorate') ? 'selected' : ''; ?>>Doctorate</option>
                        <option value="Other" <?php echo (isset($profile['education_level']) && $profile['education_level'] == 'Other') ? 'selected' : ''; ?>>Other</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label for="interests"><i class="fas fa-heart"></i> Interests</label>
                    <textarea class="form-control" id="interests" name="interests" placeholder="Enter your career interests, separated by commas"><?php echo isset($profile['interests']) ? htmlspecialchars($profile['interests']) : ''; ?></textarea>
                    <small>Example: Web Development, Data Analysis, Graphic Design, Marketing</small>
                </div>
                
                <button type="submit" class="btn btn-block">Update Profile</button>
            </form>
            
            <div style="margin-top: 2rem; text-align: center;">
                <a href="dashboard.php" class="btn" style="background-color: #6c757d;">
                    <i class="fas fa-arrow-left"></i> Back to Dashboard
                </a>
            </div>
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

    <!-- JavaScript Files -->
    <script src="js/main.js"></script>
    <script src="js/accessibility.js"></script>
    <script src="js/update-footers.js"></script>
</body>
</html>