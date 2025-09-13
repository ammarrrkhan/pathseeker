<?php
session_start();

include 'db.php'; // should define $host, $dbname, $username, $password

// Initialize variables
$message = '';
$message_type = '';

// Create MySQLi connection
$mysqli = new mysqli($host, $user, $pass, $dbname);

// Check connection
if ($mysqli->connect_errno) {
    die("Database connection failed: " . $mysqli->connect_error);
}

$mysqli->set_charset("utf8mb4");

// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_story'])) {
    // Get form data
    $name = trim($_POST['name']);
    $position = trim($_POST['position']);
    $domain = $_POST['domain'];
    $story_title = trim($_POST['story_title']);
    $story_text = trim($_POST['story_text']);
    
    // Validate inputs
    if (empty($name) || empty($position) || empty($domain) || empty($story_title) || empty($story_text)) {
        $message = 'All required fields must be filled out';
        $message_type = 'error';
    } else {
        // Handle file uploads
        $avatar_path = '';
        $story_image_path = '';
        
        // Upload avatar if provided
        if (isset($_FILES['avatar']) && $_FILES['avatar']['error'] === UPLOAD_ERR_OK) {
            $avatar_dir = 'uploads/avatars/';
            if (!file_exists($avatar_dir)) {
                mkdir($avatar_dir, 0777, true);
            }
            
            $avatar_ext = pathinfo($_FILES['avatar']['name'], PATHINFO_EXTENSION);
            $avatar_filename = 'avatar_' . time() . '_' . uniqid() . '.' . $avatar_ext;
            $avatar_path = $avatar_dir . $avatar_filename;
            
            if (!move_uploaded_file($_FILES['avatar']['tmp_name'], $avatar_path)) {
                $message = 'Error uploading avatar image';
                $message_type = 'error';
            }
        }
        
        // Upload story image if provided
        if (isset($_FILES['story_image']) && $_FILES['story_image']['error'] === UPLOAD_ERR_OK) {
            $story_image_dir = 'uploads/story_images/';
            if (!file_exists($story_image_dir)) {
                mkdir($story_image_dir, 0777, true);
            }
            
            $story_image_ext = pathinfo($_FILES['story_image']['name'], PATHINFO_EXTENSION);
            $story_image_filename = 'story_' . time() . '_' . uniqid() . '.' . $story_image_ext;
            $story_image_path = $story_image_dir . $story_image_filename;
            
            if (!move_uploaded_file($_FILES['story_image']['tmp_name'], $story_image_path)) {
                $message = 'Error uploading story image';
                $message_type = 'error';
            }
        }
        
        // If no errors so far, insert into database
        if (empty($message)) {
            // Get user ID if logged in, otherwise use 0
            $submitted_by = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : 0;
            
            // Insert into SuccessStories table
            $stmt = $mysqli->prepare("INSERT INTO SuccessStories (manne, domain, story_text, image_url, submitted_by, story_title, position) VALUES (?, ?, ?, ?, ?, ?, ?)");
            
            if ($stmt) {
                $stmt->bind_param("ssssiss", $name, $domain, $story_text, $story_image_path, $submitted_by, $story_title, $position);
                $stmt->execute();
                
                if ($stmt->affected_rows > 0) {
                    $message = 'Your success story has been submitted successfully! It will be reviewed before publishing.';
                    $message_type = 'success';
                    
                    // Clear form
                    $_POST = array();
                } else {
                    $message = 'Error submitting your story. Please try again.';
                    $message_type = 'error';
                }
                $stmt->close();
            } else {
                $message = 'Database error: ' . $mysqli->error;
                $message_type = 'error';
            }
        }
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Success Stories - PathSeeker</title>
    <link rel="stylesheet" href="css/styles.css">
    <link rel="stylesheet" href="css/responsive.css">
    <link rel="stylesheet" href="css/dark-mode.css">
    <!-- Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
.dark-mode {
background-color: var(--dark-bg-color);
color: var(--light-text);
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
.container {
    width: 100%;
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 var(--spacing-md);
}

.glass-effect {
    background: rgba(255, 255, 255, 0.8);
    backdrop-filter: blur(10px);
    border-radius: var(--border-radius-md);
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    border: 1px solid rgba(255, 255, 255, 0.2);
}

.dark-mode .glass-effect {
    background: rgba(43, 49, 73, 0.5);
    border: 1px solid rgba(255, 255, 255, 0.1);
}

.btn {
    display: inline-block;
    padding: 10px 20px;
    border-radius: var(--border-radius-sm);
    font-weight: 500;
    text-align: center;
    cursor: pointer;
    transition: all var(--transition-speed) ease;
    border: none;
}

.primary-btn {
    background-color: var(--primary-color);
    color: white;
}

.primary-btn:hover {
    background-color: var(--primary-dark);
}

.secondary-btn {
    background-color: transparent;
    border: 1px solid var(--primary-color);
    color: var(--primary-color);
}

.secondary-btn:hover {
    background-color: var(--primary-color);
    color: white;
}

.btn-link {
    background: none;
    border: none;
    color: var(--primary-color);
    cursor: pointer;
    font-weight: 500;
}

.btn-link:hover {
    text-decoration: underline;
}

.story-filters {
    display: flex;
    flex-wrap: wrap;
    gap: var(--spacing-sm);
    margin-bottom: var(--spacing-lg);
}

.filter-btn {
    padding: 8px 16px;
    border-radius: var(--border-radius-sm);
    background-color: var(--light-bg-color);
    border: 1px solid var(--border-color);
    cursor: pointer;
    transition: all var(--transition-speed) ease;
}

.filter-btn:hover {
    background-color: var(--hover-color);
}

.filter-btn.active {
    background-color: var(--primary-color);
    color: white;
    border-color: var(--primary-color);
}

.dark-mode .filter-btn {
    background-color: var(--dark-card-bg);
    border-color: var(--dark-border-color);
}

.dark-mode .filter-btn:hover {
    background-color: var(--dark-hover-color);
}

.dark-mode .filter-btn.active {
    background-color: var(--primary-color);
}

.stories-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
    gap: var(--spacing-lg);
}

.story-card {
    display: flex;
    flex-direction: column;
    height: 100%;
    overflow: hidden;
}

.story-header {
    position: relative;
    height: 200px;
    overflow: hidden;
    border-radius: var(--border-radius-md) var(--border-radius-md) 0 0;
}

.story-header img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform var(--transition-speed) ease;
}

.story-card:hover .story-header img {
    transform: scale(1.05);
}

.story-industry {
    position: absolute;
    bottom: 0;
    left: 0;
    padding: 6px 12px;
    background-color: var(--primary-color);
    color: white;
    font-size: 0.75rem;
    font-weight: 600;
    text-transform: uppercase;
    border-radius: 0 var(--border-radius-sm) 0 0;
}

.story-content {
    flex: 1;
    padding: var(--spacing-md);
    display: flex;
    flex-direction: column;
}

.story-meta {
    display: flex;
    align-items: center;
    margin-bottom: var(--spacing-sm);
    font-size: 0.875rem;
    color: var(--text-muted);
}

.story-meta .avatar {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    overflow: hidden;
    margin-right: var(--spacing-sm);
}

.story-meta .avatar img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.story-meta .info {
    display: flex;
    flex-direction: column;
}

.story-meta .name {
    font-weight: 600;
    color: var(--text-color);
}

.dark-mode .story-meta .name {
    color: var(--light-text);
}

.story-title {
    margin: var(--spacing-xs) 0;
    font-size: 1.25rem;
}

.story-excerpt {
    margin-bottom: var(--spacing-md);
    flex: 1;
}

.story-footer {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-top: auto;
    font-size: 0.875rem;
}

.story-stats {
    display: flex;
    gap: var(--spacing-sm);
    color: var(--text-muted);
}

.stat-item {
    display: flex;
    align-items: center;
    gap: 4px;
}

/* Featured Story */
.featured-story {
    margin-bottom: var(--spacing-xl);
}

.featured-story-inner {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: var(--spacing-lg);
    align-items: center;
}

.featured-story-image {
    height: 400px;
    border-radius: var(--border-radius-lg);
    overflow: hidden;
}

.featured-story-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.featured-story-content {
    padding: var(--spacing-lg);
}

.featured-label {
    display: inline-block;
    padding: 6px 12px;
    background-color: var(--primary-color);
    color: white;
    font-size: 0.75rem;
    font-weight: 600;
    text-transform: uppercase;
    border-radius: var(--border-radius-sm);
    margin-bottom: var(--spacing-md);
}

.featured-story-title {
    font-size: 2rem;
    margin-bottom: var(--spacing-md);
}

.featured-story-excerpt {
    margin-bottom: var(--spacing-lg);
}

/* Story Modal */
.story-modal {
    display: none;
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.7);
    z-index: 1000;
    overflow-y: auto;
    padding: var(--spacing-lg) 0;
}

.modal-content {
    position: relative;
    max-width: 800px;
    margin: 0 auto;
    border-radius: var(--border-radius-lg);
    padding: var(--spacing-lg);
}

.close-modal {
    position: absolute;
    top: var(--spacing-md);
    right: var(--spacing-md);
    font-size: 1.5rem;
    cursor: pointer;
    z-index: 10;
    width: 36px;
    height: 36px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 50%;
    background-color: rgba(255, 255, 255, 0.2);
    backdrop-filter: blur(5px);
    transition: all var(--transition-speed) ease;
}

.close-modal:hover {
    background-color: rgba(255, 255, 255, 0.3);
}

.modal-header {
    margin-bottom: var(--spacing-lg);
    text-align: center;
}

.modal-body {
    margin-bottom: var(--spacing-lg);
}

.modal-footer {
    display: flex;
    justify-content: space-between;
    align-items: center;
    border-top: 1px solid var(--border-color);
    padding-top: var(--spacing-md);
    margin-top: var(--spacing-lg);
}

.dark-mode .modal-footer {
    border-color: var(--dark-border-color);
}

/* Section Header */
.section-header {
    text-align: center;
    margin-bottom: var(--spacing-lg);
}

.section-header h2 {
    font-size: 2rem;
    margin-bottom: var(--spacing-sm);
}

/* Load More Container */
.load-more-container {
    text-align: center;
    margin-top: var(--spacing-xl);
}

.dark-mode .footer-bottom {
    border-color: var(--dark-border-color);
}

/* Accessibility Panel */
.accessibility-panel {
    position: fixed;
    bottom: var(--spacing-md);
    right: var(--spacing-md);
    z-index: 999;
}

.accessibility-toggle {
    width: 50px;
    height: 50px;
    border-radius: 50%;
    background-color: var(--primary-color);
    color: white;
    border: none;
    cursor: pointer;
    font-size: 1.2rem;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.2);
}

.accessibility-options {
    position: absolute;
    bottom: 60px;
    right: 0;
    background-color: white;
    border-radius: var(--border-radius-md);
    padding: var(--spacing-md);
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
    width: 250px;
    display: none;
}

.dark-mode .accessibility-options {
    background-color: var(--dark-card-bg);
}

.accessibility-options.show {
    display: block;
}

.option {
    margin-bottom: var(--spacing-md);
}

.option:last-child {
    margin-bottom: 0;
}

.option label {
    display: block;
    margin-bottom: var(--spacing-xs);
    font-weight: 500;
}

.switch {
    position: relative;
    display: inline-block;
    width: 50px;
    height: 24px;
}

.switch input {
    opacity: 0;
    width: 0;
    height: 0;
}

.slider {
    position: absolute;
    cursor: pointer;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background-color: #ccc;
    transition: .4s;
}

.slider:before {
    position: absolute;
    content: "";
    height: 16px;
    width: 16px;
    left: 4px;
    bottom: 4px;
    background-color: white;
    transition: .4s;
}

input:checked + .slider {
    background-color: var(--primary-color);
}

input:checked + .slider:before {
    transform: translateX(26px);
}

.slider.round {
    border-radius: 24px;
}

.slider.round:before {
    border-radius: 50%;
}

.font-controls {
    display: flex;
    gap: var(--spacing-xs);
}

.font-controls button {
    padding: 4px 8px;
    border: 1px solid var(--border-color);
    background: transparent;
    border-radius: var(--border-radius-sm);
    cursor: pointer;
}

.dark-mode .font-controls button {
    border-color: var(--dark-border-color);
    color: var(--light-text);
}

/* Enhanced Search Area Styles */
.search-filters {
    padding: var(--spacing-lg);
    margin-bottom: var(--spacing-lg);
}

.search-header {
    margin-bottom: var(--spacing-md);
}

.search-header h3 {
    margin-bottom: var(--spacing-xs);
    font-size: 1.5rem;
}

.search-header p {
    color: var(--text-muted);
    font-size: 0.95rem;
}

.search-controls {
    margin-top: var(--spacing-lg);
}

.search-bar.enhanced {
    display: flex;
    align-items: center;
    background: var(--light-bg-color);
    border-radius: var(--border-radius-md);
    padding: 0 var(--spacing-sm);
    border: 1px solid var(--border-color);
    transition: all var(--transition-speed) ease;
}

.dark-mode .search-bar.enhanced {
    background: var(--dark-card-bg);
    border-color: var(--dark-border-color);
}

.search-bar.enhanced:focus-within {
    border-color: var(--primary-color);
    box-shadow: 0 0 0 3px rgba(74, 107, 255, 0.1);
}

.search-bar.enhanced i {
    color: var(--text-muted);
    margin-right: var(--spacing-sm);
}

.search-bar.enhanced input {
    flex: 1;
    border: none;
    padding: var(--spacing-md) var(--spacing-sm);
    background: transparent;
    color: var(--text-color);
}

.dark-mode .search-bar.enhanced input {
    color: var(--light-text);
}

.search-bar.enhanced input:focus {
    outline: none;
}

.search-bar.enhanced .search-btn {
    padding: var(--spacing-sm) var(--spacing-md);
    background: var(--primary-color);
    color: white;
    border: none;
    border-radius: var(--border-radius-sm);
    cursor: pointer;
    transition: background-color var(--transition-speed) ease;
}

.search-bar.enhanced .search-btn:hover {
    background-color: var(--primary-dark);
}

.advanced-filters-toggle {
    display: flex;
    align-items: center;
    margin-top: var(--spacing-md);
    color: var(--primary-color);
    cursor: pointer;
    font-weight: 500;
    user-select: none;
}

.advanced-filters-toggle i {
    margin-left: var(--spacing-xs);
    transition: transform var(--transition-speed) ease;
}

.advanced-filters-toggle.active i {
    transform: rotate(180deg);
}

.advanced-filters {
    max-height: 0;
    overflow: hidden;
    transition: max-height var(--transition-speed) ease;
}

.advanced-filters.expanded {
    max-height: 500px;
    margin-top: var(--spacing-md);
}

.filter-group {
    margin-bottom: var(--spacing-md);
}

.filter-group label {
    display: block;
    margin-bottom: var(--spacing-xs);
    font-weight: 500;
}

.filter-select {
    width: 100%;
    padding: var(--spacing-sm);
    border-radius: var(--border-radius-sm);
    border: 1px solid var(--border-color);
    background-color: var(--light-bg-color);
    color: var(--text-color);
}

.dark-mode .filter-select {
    background-color: var(--dark-card-bg);
    border-color: var(--dark-border-color);
    color: var(--light-text);
}

.filter-actions {
    display: flex;
    gap: var(--spacing-sm);
    justify-content: flex-end;
}

.submit-story-section {
    padding: var(--spacing-xl) 0;
    background-color: var(--light-bg-color);
}

.dark-mode .submit-story-section {
    background-color: var(--dark-bg-color);
}

.submit-form {
    padding: var(--spacing-lg);
    max-width: 800px;
    margin: 0 auto;
}

.form-group {
    margin-bottom: var(--spacing-md);
}

.form-group label {
    display: block;
    margin-bottom: var(--spacing-xs);
    font-weight: 500;
}

.form-group input,
.form-group select,
.form-group textarea {
    width: 100%;
    padding: var(--spacing-sm);
    border: 1px solid black;
    border-radius: var(--border-radius-sm);
    background-color: var(--light-bg-color);
    color: var(--text-color);
}

.dark-mode .form-group input,
.dark-mode .form-group select,
.dark-mode .form-group textarea {
    background-color: var(--dark-card-bg);
    border-color: var(--dark-border-color);
    color: var(--light-text);
}

.form-group input:focus,
.form-group select:focus,
.form-group textarea:focus {
    outline: none;
    border-color: var(--primary-color);
    box-shadow: 0 0 0 3px rgba(74, 107, 255, 0.1);
}

/* Responsive adjustments */
@media (max-width: 768px) {
    .featured-story-inner {
        grid-template-columns: 1fr;
    }
    .featured-story-image {
        height: 250px;
    }
    .footer-content {
        grid-template-columns: 1fr;
    }
    .footer-links {
        grid-template-columns: 1fr;
        gap: var(--spacing-md);
    }
    .stories-grid {
        grid-template-columns: 1fr;
    }
    .search-bar.enhanced {
        flex-direction: column;
        align-items: stretch;
    }
    .search-bar.enhanced input {
        margin: var(--spacing-sm) 0;
    }
    .filter-actions {
        flex-direction: column;
    }
}

.message {
    padding: 12px 15px;
    margin: 20px 0;
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
            <li><a href="success-stories.php" class="active">Success Stories</a></li>
            <li><a href="login.php">Login / Signup</a></li>
            <!-- Buttons -->
            <li><a href="admin-login.php" class="nav-btn admin-btn">Admin Portal</a></li>
        </ul>
    </nav>
</header>


    <!-- Page Banner -->
    <section class="page-banner">
        <div class="container">
            <h1>Success Stories</h1>
            <p>Real stories from real people who found their career path with PathSeeker</p>
        </div>
    </section>

    <!-- Display messages if any -->
    <?php if (!empty($message)): ?>
        <div class="container">
            <div class="message <?php echo $message_type; ?>">
                <?php echo $message; ?>
            </div>
        </div>
    <?php endif; ?>

    <!-- Featured Story Section -->
    <section class="featured-story">
        <div class="container">
            <div class="featured-story-inner glass-effect">
                <div class="featured-story-image">
                    <img src="images/retail.png" alt="Featured Success Story">
                </div>
                <div class="featured-story-content">
                    <span class="featured-label">Featured Story</span>
                    <h2 class="featured-story-title">From Retail to Tech: My Career Transformation</h2>
                    <div class="story-meta">
                        <div class="avatar">
                            <img src="images/avatar7.jpeg" alt="John Doe">
                        </div>
                        <div class="info">
                            <span class="name">John Doe</span>
                            <span class="position">Software Developer at TechCorp</span>
                        </div>
                    </div>
                    <div class="featured-story-excerpt">
                        <p>After 5 years in retail management, I felt stuck and unfulfilled. Using PathSeeker's career assessment tools and resources, I discovered my passion for coding and mapped out a transition plan...</p>
                    </div>
                    <button class="btn primary-btn" data-modal="featured-story">Read Full Story</button>
                </div>
            </div>
        </div>
    </section>

<!-- Success Stories Section -->
<section class="success-stories-section">
    <div class="container">
        <div class="section-header">
            <h2>Real Stories, Real Success</h2>
            <p>Discover how people like you found their dream careers with PathSeeker's guidance</p>
        </div>

        <!-- Enhanced Search and Filters -->
        <div class="search-filters glass-effect">
            <div class="search-header">
                <h3>Find Your Inspiration</h3>
                <p>Filter stories by industry, career level, or transition time to find journeys that resonate with yours</p>
            </div>
            
            <div class="search-controls">
                <div class="search-bar enhanced">
                    <i class="fas fa-search"></i>
                    <input type="text" placeholder="Search by job title, skill, university, or challenge..." id="story-search">
                    <button class="search-btn">Find Stories</button>
                </div>
                
                <div class="advanced-filters-toggle">
                    <span>Refine Your Search</span>
                    <i class="fas fa-chevron-down"></i>
                </div>
                
                <div class="advanced-filters">
                    <div class="filter-group">
                        <label>Industry Focus</label>
                        <div class="story-filters">
                            <button class="filter-btn active" data-filter="all">All Industries</button>
                            <button class="filter-btn" data-filter="tech">Tech & IT</button>
                            <button class="filter-btn" data-filter="healthcare">Healthcare</button>
                            <button class="filter-btn" data-filter="finance">Business & Finance</button>
                            <button class="filter-btn" data-filter="creative">Creative Arts</button>
                            <button class="filter-btn" data-filter="education">Education</button>
                            <button class="filter-btn" data-filter="engineering">Engineering</button>
                        </div>
                    </div>
                    
                    <div class="filter-group">
                        <label>Career Transition</label>
                        <select class="filter-select">
                            <option value="">Any Career Change</option>
                            <option value="student">Student to Professional</option>
                            <option value="lateral">Lateral Industry Move</option>
                            <option value="advancement">Career Advancement</option>
                            <option value="entrepreneur">To Entrepreneurship</option>
                            <option value="return">Returning to Workforce</option>
                        </select>
                    </div>
                    
                    <div class="filter-group">
                        <label>Transition Time</label>
                        <select class="filter-select">
                            <option value="">Any Duration</option>
                            <option value="3">Under 3 months</option>
                            <option value="6">3-6 months</option>
                            <option value="12">6-12 months</option>
                            <option value="24">1-2 years</option>
                            <option value="24+">2+ years</option>
                        </select>
                    </div>
                    
                    <div class="filter-actions">
                        <button class="btn secondary-btn" id="reset-filters">Clear All</button>
                        <button class="btn primary-btn" id="apply-filters">Show Stories</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Stories Grid -->
        <div class="stories-grid">
            
            <!-- Story Card 1 -->
            <div class="story-card glass-effect" data-industry="tech">
                <div class="story-header">
                    <img src="images/ui-ux.png" alt="Sarah's transition to UX Design">
                    <div class="story-industry">Technology</div>
                    <div class="success-badge">Career Changer</div>
                </div>
                <div class="story-content">
                    <div class="story-meta">
                        <div class="avatar">
                            <img src="images/avatar2.jpg" alt="Sarah Miller">
                        </div>
                        <div class="info">
                            <span class="name">Sarah Miller</span>
                            <span class="position">Senior UX Designer • Google</span>
                            <span class="education">Former Marketing Coordinator</span>
                        </div>
                    </div>
                    <h3 class="story-title">From Marketing to UX: Finding Where Creativity Meets Technology</h3>
                    <p class="story-excerpt">After feeling unfulfilled in marketing, I discovered my passion for user experience design through PathSeeker's career assessment. Within 14 months, I transitioned to a new career with a 45% salary increase...</p>
                    <div class="story-stats">
                        <div class="stat">
                            <span class="number">14</span>
                            <span class="label">Months to transition</span>
                        </div>
                        <div class="stat">
                            <span class="number">45%</span>
                            <span class="label">Salary increase</span>
                        </div>
                    </div>
                    <div class="story-footer">
                        <div class="engagement">
                            <div class="stat-item">
                                <i class="far fa-eye"></i>
                                <span>1.2k views</span>
                            </div>
                            <div class="stat-item">
                                <i class="far fa-heart"></i>
                                <span>245</span>
                            </div>
                        </div>
                        <button class="btn-link" data-modal="story1">Read Full Journey</button>
                    </div>
                </div>
            </div>

            <!-- Story Card 2 -->
            <div class="story-card glass-effect" data-industry="healthcare">
                <div class="story-header">
                    <img src="images/physical theripast.png" alt="Michael's physical therapy career">
                    <div class="story-industry">Healthcare</div>
                    <div class="success-badge">Second Career</div>
                </div>
                <div class="story-content">
                    <div class="story-meta">
                        <div class="avatar">
                            <img src="images/avatar1.jpeg" alt="Michael Johnson">
                        </div>
                        <div class="info">
                            <span class="name">Michael Johnson</span>
                            <span class="position">Physical Therapist • Mayo Clinic</span>
                            <span class="education">Former Professional Athlete</span>
                        </div>
                    </div>
                    <h3 class="story-title">When Sports Ended: Building a New Purpose in Healthcare</h3>
                    <p class="story-excerpt">A career-ending injury left me uncertain about my future. PathSeeker helped me channel my knowledge of sports medicine into a rewarding healthcare career where I now help others recover...</p>
                    <div class="story-stats">
                        <div class="stat">
                            <span class="number">2</span>
                            <span class="label">Years to transition</span>
                        </div>
                        <div class="stat">
                            <span class="number">32</span>
                            <span class="label">Age when started</span>
                        </div>
                    </div>
                    <div class="story-footer">
                        <div class="engagement">
                            <div class="stat-item">
                                <i class="far fa-eye"></i>
                                <span>987 views</span>
                            </div>
                            <div class="stat-item">
                                <i class="far fa-heart"></i>
                                <span>189</span>
                            </div>
                        </div>
                        <button class="btn-link" data-modal="story2">Read Full Journey</button>
                    </div>
                </div>
            </div>

            <!-- Story Card 3 -->
            <div class="story-card glass-effect" data-industry="finance">
                <div class="story-header">
                    <img src="images/image.png" alt="Emily's finance career">
                    <div class="story-industry">Finance</div>
                    <div class="success-badge">Non-Traditional Path</div>
                </div>
                <div class="story-content">
                    <div class="story-meta">
                        <div class="avatar">
                            <img src="images/avatar3.jpeg" alt="Emily Wong">
                        </div>
                        <div class="info">
                            <span class="name">Emily Wong</span>
                            <span class="position">Financial Analyst • JPMorgan Chase</span>
                            <span class="education">English Literature Graduate</span>
                        </div>
                    </div>
                    <h3 class="story-title">From Shakespeare to Spreadsheets: An English Major's Finance Success</h3>
                    <p class="story-excerpt">Everyone told me my English degree was impractical. PathSeeker showed me how my analytical reading skills could transfer to financial analysis. I'm now thriving on Wall Street...</p>
                    <div class="story-stats">
                        <div class="stat">
                            <span class="number">9</span>
                            <span class="label">Months to transition</span>
                        </div>
                        <div class="stat">
                            <span class="number">$85K</span>
                            <span class="label">Starting salary</span>
                        </div>
                    </div>
                    <div class="story-footer">
                        <div class="engagement">
                            <div class="stat-item">
                                <i class="far fa-eye"></i>
                                <span>1.4k views</span>
                            </div>
                            <div class="stat-item">
                                <i class="far fa-heart"></i>
                                <span>312</span>
                            </div>
                        </div>
                        <button class="btn-link" data-modal="story3">Read Full Journey</button>
                    </div>
                </div>
            </div>

            <!-- Story Card 4 -->
            <div class="story-card glass-effect" data-industry="creative">
                <div class="story-header">
                    <img src="images/video creater.png" alt="David's video production career">
                    <div class="story-industry">Creative</div>
                    <div class="success-badge">Self-Taught Success</div>
                </div>
                <div class="story-content">
                    <div class="story-meta">
                        <div class="avatar">
                            <img src="images/avatar5.jpeg" alt="David Rodriguez">
                        </div>
                        <div class="info">
                            <span class="name">David Rodriguez</span>
                            <span class="position">Video Producer • Netflix</span>
                            <span class="education">Former Retail Manager</span>
                        </div>
                    </div>
                    <h3 class="story-title">From Retail Floors to Film Sets: Building a Creative Career</h3>
                    <p class="story-excerpt">I spent years managing a retail store while creating videos as a hobby. PathSeeker helped me develop a portfolio and business plan that turned my side hustle into a thriving career...</p>
                    <div class="story-stats">
                        <div class="stat">
                            <span class="number">18</span>
                            <span class="label">Months to transition</span>
                        </div>
                        <div class="stat">
                            <span class="number">3X</span>
                            <span class="label">Income multiplier</span>
                        </div>
                    </div>
                    <div class="story-footer">
                        <div class="engagement">
                            <div class="stat-item">
                                <i class="far fa-eye"></i>
                                <span>2.3k views</span>
                            </div>
                            <div class="stat-item">
                                <i class="far fa-heart"></i>
                                <span>467</span>
                            </div>
                        </div>
                        <button class="btn-link" data-modal="story4">Read Full Journey</button>
                    </div>
                </div>
            </div>

            <!-- Story Card 5 -->
            <div class="story-card glass-effect" data-industry="education">
                <div class="story-header">
                    <img src="images/education specialist.png" alt="James's edtech career">
                    <div class="story-industry">Education</div>
                    <div class="success-badge">Career Evolution</div>
                </div>
                <div class="story-content">
                    <div class="story-meta">
                        <div class="avatar">
                            <img src="images/avatar6.jpeg" alt="James Taylor">
                        </div>
                        <div class="info">
                            <span class="name">James Taylor</span>
                            <span class="position">EdTech Specialist • Coursera</span>
                            <span class="education">Former Classroom Teacher</span>
                        </div>
                    </div>
                    <h3 class="story-title">Beyond the Classroom: Leveraging Teaching Skills in EdTech</h3>
                    <p class="story-excerpt">After 12 years in the classroom, I was ready for a new challenge. PathSeeker helped me identify how my teaching experience could translate to the growing educational technology sector...</p>
                    <div class="story-stats">
                        <div class="stat">
                            <span class="number">7</span>
                            <span class="label">Months to transition</span>
                        </div>
                        <div class="stat">
                            <span class="number">60%</span>
                            <span class="label">Remote work flexibility</span>
                        </div>
                    </div>
                    <div class="story-footer">
                        <div class="engagement">
                            <div class="stat-item">
                                <i class="far fa-eye"></i>
                                <span>1.7k views</span>
                            </div>
                            <div class="stat-item">
                                <i class="far fa-heart"></i>
                                <span>289</span>
                            </div>
                        </div>
                        <button class="btn-link" data-modal="story5">Read Full Journey</button>
                    </div>
                </div>
            </div>

            <!-- Story Card 6 -->
            <div class="story-card glass-effect" data-industry="tech">
                <div class="story-header">
                    <img src="images/cybersecurity.png" alt="Aisha's cybersecurity career">
                    <div class="story-industry">Technology</div>
                    <div class="success-badge">Skills Transfer</div>
                </div>
                <div class="story-content">
                    <div class="story-meta">
                        <div class="avatar">
                            <img src="images/avatar4.jpeg" alt="Aisha Patel">
                        </div>
                        <div class="info">
                            <span class="name">Aisha Patel</span>
                            <span class="position">Cybersecurity Analyst • Microsoft</span>
                            <span class="education">Former Law Enforcement</span>
                        </div>
                    </div>
                    <h3 class="story-title">From Crime Scenes to Cyber Security: Protecting Digital Frontiers</h3>
                    <p class="story-excerpt">My investigative skills from law enforcement found a new purpose in cybersecurity. PathSeeker's career matching showed me how to rebrand my experience for the tech industry...</p>
                    <div class="story-stats">
                        <div class="stat">
                            <span class="number">11</span>
                            <span class="label">Months to transition</span>
                        </div>
                        <div class="stat">
                            <span class="number">$125K</span>
                            <span class="label">Current salary</span>
                        </div>
                    </div>
                    <div class="story-footer">
                        <div class="engagement">
                            <div class="stat-item">
                                <i class="far fa-eye"></i>
                                <span>2.1k views</span>
                            </div>
                            <div class="stat-item">
                                <i class="far fa-heart"></i>
                                <span>398</span>
                            </div>
                        </div>
                        <button class="btn-link" data-modal="story6">Read Full Journey</button>
                    </div>
                </div>
            </div>
        </div>
</section><br><br>

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
                          <a href="https://www.facebook.com/"><i class="fab fa-facebook-f"></i></a>
                        <a href="https://x.com/"><i class="fab fa-twitter"></i></a>
                        <a href="https://pk.linkedin.com/"><i class="fab fa-linkedin-in"></i></a>
                        <a href="https://www.instagram.com/"><i class="fab fa-instagram"></i></a>
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
    <script>
        // Story filtering functionality
        document.addEventListener('DOMContentLoaded', function() {
            const filterBtns = document.querySelectorAll('.filter-btn');
            const storyCards = document.querySelectorAll('.story-card');
            const searchInput = document.getElementById('story-search');
            const modalBtns = document.querySelectorAll('[data-modal]');
            const closeBtns = document.querySelectorAll('.close-modal');
            const loadMoreBtn = document.getElementById('load-more');
            const advancedFiltersToggle = document.querySelector('.advanced-filters-toggle');
            const advancedFilters = document.querySelector('.advanced-filters');
            const resetFiltersBtn = document.getElementById('reset-filters');
            const applyFiltersBtn = document.getElementById('apply-filters');
            const darkModeToggle = document.getElementById('dark-mode-toggle');
            const accessibilityToggle = document.getElementById('accessibility-toggle');
            const accessibilityOptions = document.getElementById('accessibility-options');
            
            // Toggle advanced filters
            advancedFiltersToggle.addEventListener('click', function() {
                this.classList.toggle('active');
                advancedFilters.classList.toggle('expanded');
            });
            
            // Reset filters
            resetFiltersBtn.addEventListener('click', function() {
                // Reset industry filters
                filterBtns.forEach(btn => {
                    if (btn.getAttribute('data-filter') === 'all') {
                        btn.classList.add('active');
                    } else {
                        btn.classList.remove('active');
                    }
                });
                
                // Reset select elements
                document.querySelectorAll('.filter-select').forEach(select => {
                    select.value = '';
                });
                
                // Show all stories
                storyCards.forEach(card => {
                    card.style.display = 'block';
                });
            });
            
            // Apply advanced filters
            applyFiltersBtn.addEventListener('click', function() {
                const industryFilter = document.querySelector('.filter-btn.active').getAttribute('data-filter');
                const careerLevelFilter = document.querySelectorAll('.filter-select')[0].value;
                const transitionTimeFilter = document.querySelectorAll('.filter-select')[1].value;
                
                // In a real application, this would filter based on all criteria
                // For this demo, we'll just use the industry filter
                storyCards.forEach(card => {
                    if (industryFilter === 'all' || card.getAttribute('data-industry') === industryFilter) {
                        card.style.display = 'block';
                    } else {
                        card.style.display = 'none';
                    }
                });
                
                // Close advanced filters
                advancedFiltersToggle.classList.remove('active');
                advancedFilters.classList.remove('expanded');
            });
            
            // Filter stories by industry
            filterBtns.forEach(btn => {
                btn.addEventListener('click', function() {
                    // Remove active class from all buttons
                    filterBtns.forEach(btn => btn.classList.remove('active'));
                    
                    // Add active class to clicked button
                    this.classList.add('active');
                    
                    const filterValue = this.getAttribute('data-filter');
                    
                    // Show/hide stories based on filter
                    storyCards.forEach(card => {
                        if (filterValue === 'all' || card.getAttribute('data-industry') === filterValue) {
                            card.style.display = 'block';
                        } else {
                            card.style.display = 'none';
                        }
                    });
                });
            });
            
            // Search functionality
            searchInput.addEventListener('input', function() {
                const searchTerm = this.value.toLowerCase();
                
                storyCards.forEach(card => {
                    const title = card.querySelector('.story-title').textContent.toLowerCase();
                    const excerpt = card.querySelector('.story-excerpt').textContent.toLowerCase();
                    const name = card.querySelector('.name').textContent.toLowerCase();
                    const position = card.querySelector('.position').textContent.toLowerCase();
                    
                    if (title.includes(searchTerm) || excerpt.includes(searchTerm) || name.includes(searchTerm) || position.includes(searchTerm)) {
                        card.style.display = 'block';
                    } else {
                        card.style.display = 'none';
                    }
                });
            });
            
            // Modal functionality
            modalBtns.forEach(btn => {
                btn.addEventListener('click', function() {
                    const modalId = this.getAttribute('data-modal') + '-modal';
                    document.getElementById(modalId).style.display = 'block';
                    document.body.style.overflow = 'hidden'; // Prevent scrolling
                });
            });
            
            closeBtns.forEach(btn => {
                btn.addEventListener('click', function() {
                    const modalId = this.getAttribute('data-close');
                    document.getElementById(modalId).style.display = 'none';
                    document.body.style.overflow = 'auto'; // Enable scrolling
                });
            });
            
            // Close modal when clicking outside
            window.addEventListener('click', function(event) {
                if (event.target.classList.contains('story-modal')) {
                    event.target.style.display = 'none';
                    document.body.style.overflow = 'auto';
                }
            });
            
            // Load more functionality (simulated)
            loadMoreBtn.addEventListener('click', function() {
                // In a real application, this would load more stories from the server
                this.textContent = 'Loading...';
                
                // Simulate loading delay
                setTimeout(() => {
                    this.textContent = 'No More Stories';
                    this.disabled = true;
                }, 1500);
            });
            
            // Dark mode toggle
            if (localStorage.getItem('dark-mode') === 'enabled') {
                document.body.classList.add('dark-mode');
                darkModeToggle.checked = true;
            }
            
            darkModeToggle.addEventListener('change', function() {
                if (this.checked) {
                    document.body.classList.add('dark-mode');
                    localStorage.setItem('dark-mode', 'enabled');
                } else {
                    document.body.classList.remove('dark-mode');
                    localStorage.setItem('dark-mode', 'disabled');
                }
            });
            
            // Accessibility panel toggle
            accessibilityToggle.addEventListener('click', function() {
                accessibilityOptions.classList.toggle('show');
            });
            
            // Font size controls
            const decreaseFontBtn = document.getElementById('decrease-font');
            const resetFontBtn = document.getElementById('reset-font');
            const increaseFontBtn = document.getElementById('increase-font');
            
            decreaseFontBtn.addEventListener('click', function() {
                const currentSize = parseFloat(getComputedStyle(document.body).fontSize);
                document.body.style.fontSize = (currentSize - 1) + 'px';
            });
            
            resetFontBtn.addEventListener('click', function() {
                document.body.style.fontSize = '16px';
            });
            
            increaseFontBtn.addEventListener('click', function() {
                const currentSize = parseFloat(getComputedStyle(document.body).fontSize);
                document.body.style.fontSize = (currentSize + 1) + 'px';
            });
        });
    </script>
    <script src="js/main.js"></script>
    <script src="js/accessibility.js"></script>
    <script src="js/update-footers.js"></script>
</body>
</html>
