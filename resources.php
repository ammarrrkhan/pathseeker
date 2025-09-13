<?php
// resources.php
include 'db.php';

// Fetch resources
$sql = "SELECT * FROM resources ORDER BY published_at DESC";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Career Resource - PathSeeker</title>
    <link rel="stylesheet" href="css/styles.css">
    <link rel="stylesheet" href="css/responsive.css">
    <link rel="stylesheet" href="css/dark-mode.css">
    <!-- Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

<style>
/* Resources Page Specific Styles */
.resources-section {
padding: var(--spacing-xl) 0;
min-height: 80vh;
}

.page-banner {
padding: 120px 20px 40px; 
text-align: center;
color: white;
background: linear-gradient(135deg, rgba(67, 97, 238, 0.9), rgba(58, 12, 163, 0.85), rgba(114, 9, 183, 0.8)), url('../images/resources-bg.jpg');
background-size: cover;
background-position: center;
position: relative;
overflow: hidden;
}

.page-banner::before {
content: '';
position: absolute;
top: 0;
left: 0;
right: 0;
bottom: 0;
background: url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" width="100" height="100" viewBox="0 0 100 100"><rect fill="none" width="100" height="100"/><path fill="rgba(255,255,255,0.05)" d="M30,10L50,30L70,10L90,30L70,50L90,70L70,90L50,70L30,90L10,70L30,50L10,30L30,10Z"/></svg>');
opacity: 0.5;
z-index: 0;
}

.page-banner h1 {
font-size: 3.5rem;
margin-bottom: var(--spacing-sm);
animation: fadeInDown 1s ease;
position: relative;
z-index: 2;
}

.page-banner p {
font-size: 1.25rem;
animation: fadeInUp 1s ease;
position: relative;
z-index: 2;
}

.resource-filters {
display: flex;
justify-content: center;
flex-wrap: wrap;
gap: var(--spacing-md);
margin: var(--spacing-xl) 0;
padding: 0 var(--spacing-md);
}

.filter-btn {
padding: 0.75rem 1.5rem;
border-radius: var(--border-radius-lg);
font-weight: 600;
cursor: pointer;
transition: all var(--transition-speed) ease;
border: none;
background: var(--glass-gradient), var(--glass-bg);
backdrop-filter: var(--glass-blur);
-webkit-backdrop-filter: var(--glass-blur);
border: 1px solid var(--glass-border);
box-shadow: var(--glass-shadow);
color: var(--dark-color);
}

.filter-btn:hover {
transform: translateY(-3px);
box-shadow: var(--glass-shadow-hover);
}

.filter-btn.active {
background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
color: white;
}

.dark-mode .filter-btn {
background: var(--glass-gradient-dark), var(--glass-bg-dark);
border: 1px solid var(--glass-border-dark);
color: var(--light-text);
}

.resources-grid {
display: grid;
grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
gap: var(--spacing-lg);
padding: 0 var(--spacing-md);
margin-bottom: var(--spacing-xl);
}

.resource-card {
background: var(--glass-gradient), var(--glass-bg);
backdrop-filter: var(--glass-blur);
-webkit-backdrop-filter: var(--glass-blur);
border-radius: var(--border-radius-lg);
border: 1px solid var(--glass-border);
box-shadow: var(--glass-shadow);
overflow: hidden;
transition: all var(--transition-speed) ease;
display: flex;
flex-direction: column;
height: 100%;
}

.resource-card:hover {
transform: translateY(-10px);
box-shadow: var(--glass-shadow-hover);
}

.dark-mode .resource-card {
background: var(--glass-gradient-dark), var(--glass-bg-dark);
border: 1px solid var(--glass-border-dark);
}

.resource-card img {
width: 100%;
height: 180px;
object-fit: cover;
transition: transform var(--transition-speed) ease;
}

.resource-card:hover img {
transform: scale(1.05);
}

.resource-content {
padding: var(--spacing-lg);
display: flex;
flex-direction: column;
flex: 1;
}

.resource-tags {
display: flex;
flex-wrap: wrap;
gap: var(--spacing-xs);
margin-bottom: var(--spacing-sm);
}

.resource-tag {
padding: 0.25rem 0.5rem;
border-radius: var(--border-radius-sm);
font-size: 0.75rem;
font-weight: 500;
}

.video {
background-color: rgba(231, 76, 60, 0.15);
color: #e74c3c;
}

.article {
background-color: rgba(33, 150, 243, 0.15);
color: #2196f3;
}

.tool {
background-color: rgba(76, 175, 80, 0.15);
color: #4caf50;
}

.course {
background-color: rgba(255, 193, 7, 0.15);
color: #ffc107;
}

.resource-title {
font-size: 1.25rem;
font-weight: 600;
margin: var(--spacing-sm) 0;
color: var(--dark-color);
}

.dark-mode .resource-title {
color: var(--light-text);
}

.resource-desc {
flex: 1;
font-size: 0.95rem;
margin-bottom: var(--spacing-md);
color: var(--gray-color);
}

.dark-mode .resource-desc {
color: var(--text-muted-dark);
}

.resource-meta {
display: flex;
justify-content: space-between;
align-items: center;
font-size: 0.85rem;
color: var(--gray-color);
}

.dark-mode .resource-meta {
color: var(--text-muted-dark);
}

.view-btn {
padding: 0.5rem 1rem;
background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
color: white;
border: none;
border-radius: var(--border-radius-md);
cursor: pointer;
font-weight: 500;
transition: all var(--transition-speed) ease;
}

.view-btn:hover {
background: linear-gradient(135deg, var(--secondary-color), var(--primary-color));
transform: translateY(-2px);
}

/* Modal */
.resource-modal {
display: none;
position: fixed;
top: 0;
left: 0;
width: 100%;
height: 100%;
background-color: rgba(0, 0, 0, 0.8);
justify-content: center;
align-items: center;
z-index: 2000;
padding: var(--spacing-md);
}

.modal-content {
background: var(--glass-gradient), var(--glass-bg);
backdrop-filter: var(--glass-blur);
-webkit-backdrop-filter: var(--glass-blur);
border-radius: var(--border-radius-lg);
border: 1px solid var(--glass-border);
max-width: 800px;
width: 100%;
padding: var(--spacing-xl);
position: relative;
box-shadow: var(--glass-shadow);
}

.dark-mode .modal-content {
background: var(--glass-gradient-dark), var(--glass-bg-dark);
border: 1px solid var(--glass-border-dark);
color: var(--light-text);
}

.close-modal {
position: absolute;
top: var(--spacing-md);
right: var(--spacing-md);
cursor: pointer;
font-size: 1.5rem;
color: var(--dark-color);
transition: color var(--transition-speed) ease;
}

.dark-mode .close-modal {
color: var(--light-text);
}

.close-modal:hover {
color: var(--accent-color);
}

#modalTitle {
margin-bottom: var(--spacing-md);
color: var(--dark-color);
}

.dark-mode #modalTitle {
color: var(--light-text);
}

#modalVideo {
width: 100%;
height: 400px;
border: none;
border-radius: var(--border-radius-md);
margin-bottom: var(--spacing-md);
}

#modalDesc {
color: var(--gray-color);
}

.dark-mode #modalDesc {
color: var(--text-muted-dark);
}

/* Responsive Design */
@media (max-width: 768px) {
.resources-grid {
grid-template-columns: 1fr;
}

.resource-filters {
flex-direction: column;
align-items: center;
}

.page-banner h1 {
font-size: 2.5rem;
}

.page-banner p {
font-size: 1rem;
}
}

/* Animations */
.fade-in {
opacity: 0;
animation: fadeIn 0.5s forwards;
}

@keyframes fadeIn {
to { opacity: 1; }
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
            <li><a href="resources.php" class="active">Resources</a></li>
            <li><a href="success-stories.php">Success Stories</a></li>
            <li><a href="login.php">Login / Signup</a></li>
            <!-- Buttons -->
            <li><a href="admin-login.php" class="nav-btn admin-btn">Admin Portal</a></li>
        </ul>
    </nav>
</header>


<section class="page-banner">
    <h1>Career Resources</h1>
    <p>Videos, articles, tools, and courses to boost your career</p>
</section>

<div class="resource-filters">
    <button class="filter-btn active" data-filter="all">All</button>
    <button class="filter-btn" data-filter="video">Videos</button>
    <button class="filter-btn" data-filter="article">Articles</button>
    <button class="filter-btn" data-filter="tool">Tools</button>
    <button class="filter-btn" data-filter="course">Courses</button>
</div>

<div class="resources-grid" id="resourcesGrid">
<?php while($row = $result->fetch_assoc()): ?>
    <div class="resource-card fade-in" data-type="<?php echo $row['type']; ?>">
        <img src="<?php echo $row['image_url']; ?>" alt="<?php echo $row['title']; ?>">
        <div class="resource-content">
            <div class="resource-tags">
                <span class="resource-tag <?php echo $row['type']; ?>"><?php echo ucfirst($row['type']); ?></span>
                <?php 
                $tags = explode(',', $row['tags']);
                foreach($tags as $tag){
                    echo '<span class="resource-tag">'.trim($tag).'</span>';
                }
                ?>
            </div>
            <h3 class="resource-title"><?php echo $row['title']; ?></h3>
            <p class="resource-desc"><?php echo $row['description']; ?></p>
            <div class="resource-meta">
                <span><?php echo $row['duration']; ?></span>
                <button class="view-modal" data-url="<?php echo $row['content_url']; ?>">View</button>
            </div>
        </div>
    </div>
<?php endwhile; ?>
</div>

<!-- Modal -->
<div class="resource-modal" id="resourceModal">
    <div class="modal-content">
        <span class="close-modal">&times;</span>
        <h2 id="modalTitle"></h2>
        <iframe id="modalVideo" width="100%" height="400" src="" frameborder="0" allowfullscreen></iframe>
        <p id="modalDesc"></p>
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


<script>
// Filter functionality
const filterBtns = document.querySelectorAll('.filter-btn');
const cards = document.querySelectorAll('.resource-card');
filterBtns.forEach(btn => {
    btn.addEventListener('click', () => {
        filterBtns.forEach(b => b.classList.remove('active'));
        btn.classList.add('active');
        const filter = btn.dataset.filter;
        cards.forEach(card => {
            if(filter === 'all' || card.dataset.type === filter) {
                card.style.display = 'flex';
                card.classList.add('fade-in');
            } else {
                card.style.display = 'none';
            }
        });
    });
});

// Modal functionality
const viewBtns = document.querySelectorAll('.view-modal');
const modal = document.getElementById('resourceModal');
const modalFrame = document.getElementById('modalVideo');
const modalTitle = document.getElementById('modalTitle');
const modalDesc = document.getElementById('modalDesc');
const closeModal = modal.querySelector('.close-modal');

viewBtns.forEach(btn => {
    btn.addEventListener('click', () => {
        modal.style.display = 'flex';
        modalFrame.src = btn.dataset.url;
        const card = btn.closest('.resource-card');
        modalTitle.textContent = card.querySelector('.resource-title').textContent;
        modalDesc.textContent = card.querySelector('.resource-desc').textContent;
    });
});

closeModal.addEventListener('click', () => {
    modal.style.display = 'none';
    modalFrame.src = '';
});

window.addEventListener('click', e => {
    if(e.target === modal) { 
        modal.style.display = 'none'; 
        modalFrame.src = ''; 
    }
});

// Accessibility panel functionality
const accessibilityToggle = document.getElementById('accessibilityToggle');
const accessibilityOptions = document.querySelector('.accessibility-options');

accessibilityToggle.addEventListener('click', () => {
    accessibilityOptions.classList.toggle('show');
});

// Dark mode toggle
const darkModeToggle = document.getElementById('darkModeToggle');
const body = document.body;

// Check for saved dark mode preference
if (localStorage.getItem('darkMode') === 'enabled') {
    body.classList.add('dark-mode');
    darkModeToggle.checked = true;
}

darkModeToggle.addEventListener('change', () => {
    if (darkModeToggle.checked) {
        body.classList.add('dark-mode');
        localStorage.setItem('darkMode', 'enabled');
    } else {
        body.classList.remove('dark-mode');
        localStorage.setItem('darkMode', null);
    }
});

// Font size controls
const decreaseFontBtn = document.getElementById('decreaseFontBtn');
const resetFontBtn = document.getElementById('resetFontBtn');
const increaseFontBtn = document.getElementById('increaseFontBtn');

decreaseFontBtn.addEventListener('click', () => {
    changeFontSize(-1);
});

resetFontBtn.addEventListener('click', () => {
    document.documentElement.style.fontSize = '16px';
    localStorage.setItem('fontSize', '16px');
});

increaseFontBtn.addEventListener('click', () => {
    changeFontSize(1);
});

function changeFontSize(direction) {
    const currentSize = parseFloat(getComputedStyle(document.documentElement).fontSize);
    const newSize = currentSize + (direction * 2);
    document.documentElement.style.fontSize = `${newSize}px`;
    localStorage.setItem('fontSize', `${newSize}px`);
}

// Check for saved font size preference
if (localStorage.getItem('fontSize')) {
    document.documentElement.style.fontSize = localStorage.getItem('fontSize');
}

// Mobile menu toggle
const mobileMenu = document.getElementById('mobile-menu');
const navMenu = document.querySelector('.nav-menu');

mobileMenu.addEventListener('click', () => {
    navMenu.classList.toggle('active');
    mobileMenu.classList.toggle('active');
});
</script>

</body>
</html>
