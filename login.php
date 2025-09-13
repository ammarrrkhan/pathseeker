<?php
// Database connection and processing logic
session_start();

include 'db.php';

// Initialize variables
$message = '';
$message_type = '';

/*
 Make sure db.php defines:
 $host, $dbname, $username, $password
*/

$mysqli = new mysqli($host, $user, $pass, $dbname);

if ($mysqli->connect_errno) {
    $message = 'Database error: Failed to connect to MySQL - ' . $mysqli->connect_error;
    $message_type = 'error';
} else {
    // Ensure proper charset
    $mysqli->set_charset("utf8mb4");

    // Check if form is submitted
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (isset($_POST['action']) && $_POST['action'] === 'signup') {
            // Signup processing
            $uname = trim($_POST['fullname']);
            $email = trim($_POST['email']);
            $password = $_POST['password'];
            $confirm_password = $_POST['confirm_password'];
            $role = $_POST['role'];

            // Validate inputs
            if (empty($uname) || empty($email) || empty($password) || empty($role)) {
                $message = 'All fields are required';
                $message_type = 'error';
            } elseif ($password !== $confirm_password) {
                $message = 'Passwords do not match';
                $message_type = 'error';
            } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $message = 'Invalid email format';
                $message_type = 'error';
            } else {
                // Check if email already exists
                $stmt = $mysqli->prepare("SELECT user_id FROM Users WHERE email = ?");
                if (!$stmt) {
                    $message = 'Database error: ' . $mysqli->error;
                    $message_type = 'error';
                } else {
                    $stmt->bind_param('s', $email);
                    $stmt->execute();
                    $stmt->store_result();

                    if ($stmt->num_rows > 0) {
                        $message = 'Email already registered';
                        $message_type = 'error';
                        $stmt->close();
                    } else {
                        $stmt->close();

                        // Hash password
                        $password_hash = password_hash($password, PASSWORD_DEFAULT);

                        // Insert user into database
                        $insert = $mysqli->prepare("INSERT INTO Users (uname, email, password_hash, role) VALUES (?, ?, ?, ?)");
                        if (!$insert) {
                            $message = 'Database error: ' . $mysqli->error;
                            $message_type = 'error';
                        } else {
                            $insert->bind_param('ssss', $uname, $email, $password_hash, $role);
                            $ok = $insert->execute();

                            if ($ok && $insert->affected_rows > 0) {
                                $user_id = $mysqli->insert_id;
                                $insert->close();

                                // Create empty user profile
                                $profile = $mysqli->prepare("INSERT INTO UserProfiles (user_id, education_level, interests) VALUES (?, ?, ?)");
                                if ($profile) {
                                    $emptyEducation = '';
                                    $emptyInterests = '';
                                    $profile->bind_param('iss', $user_id, $emptyEducation, $emptyInterests);
                                    $profile->execute();
                                    $profile->close();
                                }

                                $message = 'Registration successful! You can now login.';
                                $message_type = 'success';

                                // Clear form
                                $_POST = array();
                            } else {
                                $message = 'Registration failed. Please try again.';
                                $message_type = 'error';
                                if ($insert) $insert->close();
                            }
                        }
                    }
                }
            }
        } elseif (isset($_POST['action']) && $_POST['action'] === 'login') {
            // Login processing
            $email = trim($_POST['email']);
            $password = $_POST['password'];

            // Validate inputs
            if (empty($email) || empty($password)) {
                $message = 'Please enter both email and password';
                $message_type = 'error';
            } else {
                // Check if user exists
                $stmt = $mysqli->prepare("SELECT user_id, uname, email, password_hash, role FROM Users WHERE email = ?");
                if (!$stmt) {
                    $message = 'Database error: ' . $mysqli->error;
                    $message_type = 'error';
                } else {
                    $stmt->bind_param('s', $email);
                    $stmt->execute();
                    $result = $stmt->get_result();
                    $user = $result->fetch_assoc();
                    $stmt->close();

                    if ($user && password_verify($password, $user['password_hash'])) {
                        // Login successful
                        $_SESSION['user_id'] = $user['user_id'];
                        $_SESSION['user_name'] = $user['uname'];
                        $_SESSION['user_email'] = $user['email'];
                        $_SESSION['user_role'] = $user['role'];

                        // Redirect to dashboard
                        header('Location: dashboard.php');
                        exit();
                    } else {
                        $message = 'Invalid email or password';
                        $message_type = 'error';
                    }
                }
            }
        }
    }
    // Close connection at end of request (optional)
    // $mysqli->close();
}
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login/Signup - PathSeeker</title>
    <link rel="stylesheet" href="css/styles.css">
    <link rel="stylesheet" href="css/responsive.css">
    <!-- Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
/* Additional styles specific to login/signup page */
.auth-container {
display: flex;
min-height: 100vh;
padding-top: 80px; /* Account for fixed navbar */
}

.auth-image {
flex: 1;
background: linear-gradient(135deg, rgba(74, 107, 255, 0.9), rgba(108, 99, 255, 0.9)), url('images/auth-bg.jpg');
background-size: cover;
background-position: center;
display: flex;
align-items: center;
justify-content: center;
color: white;
padding: 2rem;
text-align: center;
}

.auth-image-content {
max-width: 500px;
}

.auth-image h2 {
font-size: 2.5rem;
margin-bottom: 1rem;
}

.auth-forms {
flex: 1;
display: flex;
align-items: center;
justify-content: center;
padding: 2rem;
background-color: var(--glass-bg);
backdrop-filter: var(--glass-blur);
-webkit-backdrop-filter: var(--glass-blur);
border: 1px solid var(--glass-border);
box-shadow: var(--glass-shadow);
}

.dark-mode .auth-forms {
background-color: var(--glass-bg-dark);
border: 1px solid var(--glass-border-dark);
}

.forms-container {
width: 100%;
max-width: 450px;
background-color: var(--glass-bg);
backdrop-filter: var(--glass-blur);
-webkit-backdrop-filter: var(--glass-blur);
border-radius: var(--border-radius-lg);
border: 1px solid var(--glass-border);
box-shadow: var(--glass-shadow);
padding: var(--spacing-lg);
margin-top: 0.1px;
}

.dark-mode .forms-container {
background-color: var(--glass-bg-dark);
border: 1px solid var(--glass-border-dark);
}

.form-tabs {
display: flex;
margin-bottom: 2rem;
border-bottom: 1px solid #eee;
}

.dark-mode .form-tabs {
border-bottom: 1px solid #4a5568;
}

.form-tab {
flex: 1;
text-align: center;
padding: 1rem;
cursor: pointer;
font-weight: 600;
position: relative;
transition: all 0.3s ease;
}

.form-tab.active {
color: var(--primary-color);
}

.form-tab.active:after {
content: '';
position: absolute;
bottom: -1px;
left: 0;
width: 100%;
height: 3px;
background-color: var(--primary-color);
border-radius: 3px 3px 0 0;
}
header{
    margin-bottom: 1px;
}
.auth-form {
display: none;
}

.auth-form.active {
display: block;
animation: fadeIn 0.5s ease;
}

.form-group {
margin-bottom: 1.5rem;
}

.form-group label {
display: block;
margin-bottom: 0.5rem;
font-weight: 500;
}

.form-control {
width: 100%;
padding: 0.75rem 1rem;
border: 1px solid #ddd;
border-radius: var(--border-radius-md);
font-family: var(--body-font);
font-size: 1rem;
transition: all 0.3s ease;
}

.dark-mode .form-control {
background-color: #2d3748;
border-color: #4a5568;
color: white;
}

.form-control:focus {
border-color: var(--primary-color);
outline: none;
box-shadow: 0 0 0 3px rgba(74, 107, 255, 0.2);
}

.role-selection {
display: flex;
gap: 1rem;
flex-wrap: wrap;
}
/* Dark mode styles for navbar */
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
.role-option {
flex: 1;
min-width: 120px;
text-align: center;
padding: 1rem;
border: 2px solid #ddd;
border-radius: var(--border-radius-md);
cursor: pointer;
transition: all 0.3s ease;
}

.dark-mode .role-option {
border-color: #4a5568;
}

.role-option:hover {
border-color: var(--primary-color);
}

.role-option.selected {
border-color: var(--primary-color);
background-color: rgba(74, 107, 255, 0.1);
}

.role-option i {
font-size: 2rem;
color: var(--primary-color);
margin-bottom: 0.5rem;
display: block;
}

.forgot-password {
display: block;
text-align: right;
margin-bottom: 1.5rem;
font-size: 0.9rem;
}

.submit-btn {
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

.submit-btn:hover {
background-color: var(--secondary-color);
transform: translateY(-2px);
box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
}

.social-login {
margin-top: 2rem;
text-align: center;
}

.social-login p {
margin-bottom: 1rem;
position: relative;
}

.social-login p:before,
.social-login p:after {
content: '';
position: absolute;
top: 50%;
width: 40%;
height: 1px;
background-color: #ddd;
}

.dark-mode .social-login p:before,
.dark-mode .social-login p:after {
background-color: #4a5568;
}

.social-login p:before {
left: 0;
}

.social-login p:after {
right: 0;
}

.social-icons {
display: flex;
justify-content: center;
gap: 1rem;
}

.social-icon {
display: flex;
align-items: center;
justify-content: center;
width: 40px;
height: 40px;
border-radius: 50%;
background-color: #f1f1f1;
transition: all 0.3s ease;
}

.dark-mode .social-icon {
background-color: #4a5568;
}

.social-icon:hover {
transform: translateY(-3px);
box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
}

.social-icon.facebook {
color: #3b5998;
}

.social-icon.google {
color: #db4437;
}

.social-icon.linkedin {
color: #0077b5;
}

/* Message styles */
.message {
padding: 12px 15px;
margin-bottom: 20px;
border-radius: var(--border-radius-md);
font-weight: 500;
}

.message.error {
background-color: #ffebee;
color: #d32f2f;
border: 1px solid #ffcdd2;
}

.message.success {
background-color: #e8f5e9;
color: #388e3c;
border: 1px solid #c8e6c9;
}

.dark-mode .message.error {
background-color: #422;
color: #ff6b6b;
border-color: #611;
}

.dark-mode .message.success {
background-color: #242;
color: #6bcb77;
border-color: #161;
}

/* Responsive adjustments */
@media screen and (max-width: 992px) {
.auth-container {
    flex-direction: column;
}

.auth-image,
.auth-forms {
    flex: none;
    width: 100%;
}

.auth-image {
    min-height: 300px;
}
}
.password-toggle {
    position: absolute;
    right: 10px;
    top: 50%;
    transform: translateY(-50%);
    cursor: pointer;
    color: #6c757d;
    z-index: 2;
}

.form-group {
    position: relative;
}

.has-error .password-toggle {
    color: #d32f2f;
}

/* Adjust input padding to make room for the eye icon */
#loginPassword, #signupPassword, #signupConfirmPassword {
    padding-right: 40px;
}

/* Style for error state */
.has-error input {
    border-color: #d32f2f !important;
}

.error-message {
    color: #d32f2f;
    font-size: 0.85rem;
    margin-top: 0.25rem;
}

/* Dark mode adjustments */
.dark-mode .password-toggle {
    color: #a0aec0;
}

.dark-mode .has-error .password-toggle {
    color: #fc8181;
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


    <!-- Authentication Container -->
    <div class="auth-container">
        <!-- Left Side - Image and Text -->
        <div class="auth-image">
            <div class="auth-image-content">
                <h2>Welcome to PathSeeker</h2>
                <p>Your journey to the perfect career starts here. Join our community of career seekers and professionals to discover opportunities that match your skills and interests.</p>
                <img src="images/login.png" alt="Career Guidance Illustration" style="max-width: 80%; margin-top: 2rem;">
            </div>
        </div>
        
        <!-- Right Side - Forms -->
        <div class="auth-forms">
            <div class="forms-container">
                <div class="form-tabs">
                    <div class="form-tab active" data-tab="login">Login</div>
                    <div class="form-tab" data-tab="signup">Sign Up</div>
                </div>
                
                <!-- Display messages if any -->
                <?php if (!empty($message)): ?>
                    <div class="message <?php echo $message_type; ?>">
                        <?php echo $message; ?>
                    </div>
                <?php endif; ?>
                
                <!-- Login Form -->
                <form class="auth-form login-form active" id="loginForm" method="POST" action="">
                    <input type="hidden" name="action" value="login">
                    <div class="form-group">
                        <label for="loginEmail">Email Address</label>
                        <input type="email" id="loginEmail" name="email" class="form-control" placeholder="Enter your email" required value="<?php echo isset($_POST['email']) && $_POST['action'] === 'login' ? htmlspecialchars($_POST['email']) : ''; ?>">
                    </div>
                    <div class="form-group">
                        <label for="loginPassword">Password</label>
                        <input type="password" id="loginPassword" name="password" class="form-control" placeholder="Enter your password" required>
                    </div>
                    <button type="submit" class="submit-btn">Login</button>
                    
                </form>
                
                <!-- Signup Form -->
                <form class="auth-form signup-form" id="signupForm" method="POST" action="">
                    <input type="hidden" name="action" value="signup">
                    <div class="form-group">
                        <label>I am a</label>
                        <div class="role-selection">
                            <div class="role-option" data-role="student">
                                <i class="fas fa-user-graduate"></i>
                                <span>Student</span>
                            </div>
                            <div class="role-option" data-role="graduate">
                                <i class="fas fa-graduation-cap"></i>
                                <span>Graduate</span>
                            </div>
                            <div class="role-option" data-role="professional">
                                <i class="fas fa-briefcase"></i>
                                <span>Professional</span>
                            </div>
                        </div>
                        <input type="hidden" id="selectedRole" name="role" value="<?php echo isset($_POST['role']) ? htmlspecialchars($_POST['role']) : ''; ?>">
                    </div>
                    <div class="form-group">
                        <label for="signupName">Full Name</label>
                        <input type="text" id="signupName" name="fullname" class="form-control" placeholder="Enter your full name" required value="<?php echo isset($_POST['fullname']) ? htmlspecialchars($_POST['fullname']) : ''; ?>">
                    </div>
                    <div class="form-group">
                        <label for="signupEmail">Email Address</label>
                        <input type="email" id="signupEmail" name="email" class="form-control" placeholder="Enter your email" required value="<?php echo isset($_POST['email']) && $_POST['action'] === 'signup' ? htmlspecialchars($_POST['email']) : ''; ?>">
                    </div>
                    <div class="form-group">
                        <label for="signupPassword">Password</label>
                        <input type="password" id="signupPassword" name="password" class="form-control" placeholder="Create a password" required>
                    </div>
                    <div class="form-group">
                        <label for="signupConfirmPassword">Confirm Password</label>
                        <input type="password" id="signupConfirmPassword" name="confirm_password" class="form-control" placeholder="Confirm your password" required>
                    </div>
                    <button type="submit" class="submit-btn">Create Account</button>
                </form>
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
                        <a href="#"><i class="fab fa-facebook-f"></i></a>
                        <a href="#"><i class="fab fa-twitter"></i></a>
                        <a href="#"><i class="fab fa-linkedin-in"></i></a>
                        <a href="#"><i class="fab fa-instagram"></i></a>
                    </div>
                </div>
            </div>
            <div class="footer-bottom">
                <p>&copy; 2023 PathSeeker. All rights reserved.</p>
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
document.addEventListener('DOMContentLoaded', function() {
    // Tab switching
    const formTabs = document.querySelectorAll('.form-tab');
    const authForms = document.querySelectorAll('.auth-form');
    
    formTabs.forEach(tab => {
        tab.addEventListener('click', () => {
            // Remove active class from all tabs and forms
            formTabs.forEach(t => t.classList.remove('active'));
            authForms.forEach(f => f.classList.remove('active'));
            
            // Add active class to clicked tab and corresponding form
            tab.classList.add('active');
            const formId = tab.getAttribute('data-tab');
            document.querySelector(`.${formId}-form`).classList.add('active');
        });
    });
    
    // Role selection in signup form
    const roleOptions = document.querySelectorAll('.role-option');
    const selectedRoleInput = document.getElementById('selectedRole');
    
    // Set initial selection if there's a previously selected value
    if (selectedRoleInput && selectedRoleInput.value) {
        roleOptions.forEach(option => {
            if (option.getAttribute('data-role') === selectedRoleInput.value) {
                option.classList.add('selected');
            }
        });
    }
    
    roleOptions.forEach(option => {
        option.addEventListener('click', () => {
            // Remove selected class from all options
            roleOptions.forEach(o => o.classList.remove('selected'));
            
            // Add selected class to clicked option
            option.classList.add('selected');
            
            // Update hidden input value
            selectedRoleInput.value = option.getAttribute('data-role');
            
            // Remove error styling if any
            option.closest('.form-group').classList.remove('has-error');
        });
    });
    
    // Password visibility toggle function
    function setupPasswordToggle(passwordInputId) {
        const passwordInput = document.getElementById(passwordInputId);
        if (!passwordInput) return;
        
        const formGroup = passwordInput.closest('.form-group');
        const eyeIcon = document.createElement('span');
        eyeIcon.className = 'password-toggle';
        eyeIcon.innerHTML = '<i class="fas fa-eye"></i>';
        eyeIcon.style.position = 'absolute';
        eyeIcon.style.right = '10px';
        eyeIcon.style.top = '50%';
        eyeIcon.style.transform = 'translateY(-50%)';
        eyeIcon.style.cursor = 'pointer';
        eyeIcon.style.color = '#6c757d';
        
        // Add wrapper to position the eye icon
        const inputWrapper = document.createElement('div');
        inputWrapper.style.position = 'relative';
        passwordInput.parentNode.insertBefore(inputWrapper, passwordInput);
        inputWrapper.appendChild(passwordInput);
        inputWrapper.appendChild(eyeIcon);
        
        // Toggle password visibility
        eyeIcon.addEventListener('click', function() {
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                eyeIcon.innerHTML = '<i class="fas fa-eye-slash"></i>';
            } else {
                passwordInput.type = 'password';
                eyeIcon.innerHTML = '<i class="fas fa-eye"></i>';
            }
        });
    }
    
    // Set up password toggles for all password fields
    setupPasswordToggle('loginPassword');
    setupPasswordToggle('signupPassword');
    setupPasswordToggle('signupConfirmPassword');
    
    // Form validation functions
    function showError(input, message) {
        const formGroup = input.closest('.form-group');
        formGroup.classList.add('has-error');
        
        // Remove any existing error message
        const existingError = formGroup.querySelector('.error-message');
        if (existingError) {
            existingError.remove();
        }
        
        // Add error message
        const error = document.createElement('div');
        error.className = 'error-message';
        error.style.color = '#d32f2f';
        error.style.fontSize = '0.85rem';
        error.style.marginTop = '0.25rem';
        error.innerHTML = `<i class="fas fa-exclamation-circle"></i> ${message}`;
        formGroup.appendChild(error);
        
        // Add error styling to input
        input.style.borderColor = '#d32f2f';
    }
    
    function clearError(input) {
        const formGroup = input.closest('.form-group');
        formGroup.classList.remove('has-error');
        
        // Remove error message
        const error = formGroup.querySelector('.error-message');
        if (error) {
            error.remove();
        }
        
        // Reset input styling
        input.style.borderColor = '';
    }
    
    function validateEmail(email) {
        const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return re.test(email);
    }
    
    function validatePassword(password) {
        // At least 8 characters, one uppercase, one lowercase, one number
        const re = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).{8,}$/;
        return re.test(password);
    }
    
    // Real-time validation for signup form
    const signupForm = document.getElementById('signupForm');
    if (signupForm) {
        const signupName = document.getElementById('signupName');
        const signupEmail = document.getElementById('signupEmail');
        const signupPassword = document.getElementById('signupPassword');
        const signupConfirmPassword = document.getElementById('signupConfirmPassword');
        
        // Name validation
        signupName.addEventListener('blur', function() {
            if (!this.value.trim()) {
                showError(this, 'Full name is required');
            } else {
                clearError(this);
            }
        });
        
        // Email validation
        signupEmail.addEventListener('blur', function() {
            if (!this.value.trim()) {
                showError(this, 'Email is required');
            } else if (!validateEmail(this.value)) {
                showError(this, 'Please enter a valid email address');
            } else {
                clearError(this);
            }
        });
        
        // Password validation
        signupPassword.addEventListener('blur', function() {
            if (!this.value) {
                showError(this, 'Password is required');
            } else if (!validatePassword(this.value)) {
                showError(this, 'Password must be at least 8 characters with uppercase, lowercase, and number');
            } else {
                clearError(this);
            }
        });
        
        // Confirm password validation
        signupConfirmPassword.addEventListener('blur', function() {
            if (!this.value) {
                showError(this, 'Please confirm your password');
            } else if (this.value !== signupPassword.value) {
                showError(this, 'Passwords do not match');
            } else {
                clearError(this);
            }
        });
        
        // Signup form submission validation
        signupForm.addEventListener('submit', function(e) {
            let isValid = true;
            
            // Validate role selection
            if (!selectedRoleInput.value) {
                isValid = false;
                const roleGroup = document.querySelector('.role-selection').closest('.form-group');
                roleGroup.classList.add('has-error');
                
                // Add error message if not already present
                if (!roleGroup.querySelector('.error-message')) {
                    const error = document.createElement('div');
                    error.className = 'error-message';
                    error.style.color = '#d32f2f';
                    error.style.fontSize = '0.85rem';
                    error.style.marginTop = '0.25rem';
                    error.innerHTML = '<i class="fas fa-exclamation-circle"></i> Please select a role';
                    roleGroup.appendChild(error);
                }
            }
            
            // Validate name
            if (!signupName.value.trim()) {
                isValid = false;
                showError(signupName, 'Full name is required');
            }
            
            // Validate email
            if (!signupEmail.value.trim()) {
                isValid = false;
                showError(signupEmail, 'Email is required');
            } else if (!validateEmail(signupEmail.value)) {
                isValid = false;
                showError(signupEmail, 'Please enter a valid email address');
            }
            
            // Validate password
            if (!signupPassword.value) {
                isValid = false;
                showError(signupPassword, 'Password is required');
            } else if (!validatePassword(signupPassword.value)) {
                isValid = false;
                showError(signupPassword, 'Password must be at least 8 characters with uppercase, lowercase, and number');
            }
            
            // Validate confirm password
            if (!signupConfirmPassword.value) {
                isValid = false;
                showError(signupConfirmPassword, 'Please confirm your password');
            } else if (signupConfirmPassword.value !== signupPassword.value) {
                isValid = false;
                showError(signupConfirmPassword, 'Passwords do not match');
            }
            
            if (!isValid) {
                e.preventDefault();
            }
        });
    }
    
    // Login form validation
    const loginForm = document.getElementById('loginForm');
    if (loginForm) {
        const loginEmail = document.getElementById('loginEmail');
        const loginPassword = document.getElementById('loginPassword');
        
        // Email validation
        loginEmail.addEventListener('blur', function() {
            if (!this.value.trim()) {
                showError(this, 'Email is required');
            } else if (!validateEmail(this.value)) {
                showError(this, 'Please enter a valid email address');
            } else {
                clearError(this);
            }
        });
        
        // Password validation
        loginPassword.addEventListener('blur', function() {
            if (!this.value) {
                showError(this, 'Password is required');
            } else {
                clearError(this);
            }
        });
        
        // Login form submission validation
        loginForm.addEventListener('submit', function(e) {
            let isValid = true;
            
            // Validate email
            if (!loginEmail.value.trim()) {
                isValid = false;
                showError(loginEmail, 'Email is required');
            } else if (!validateEmail(loginEmail.value)) {
                isValid = false;
                showError(loginEmail, 'Please enter a valid email address');
            }
            
            // Validate password
            if (!loginPassword.value) {
                isValid = false;
                showError(loginPassword, 'Password is required');
            }
            
            if (!isValid) {
                e.preventDefault();
            }
        });
    }
});
</script>

</body>
</html>