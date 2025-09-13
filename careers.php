include 'db.php';

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Career Bank - PathSeeker</title>
  <link rel="stylesheet" href="css/styles.css">
  <link rel="stylesheet" href="css/responsive.css">
  <!-- Font Awesome for icons -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <!-- Google Fonts -->
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
   <style>
/* Modal container */
.career-modal {
display: none;
position: fixed;
z-index: 9999;
left: 0;
top: 0;
width: 100%;
height: 100%;
background-color: rgba(0,0,0,0.6);
overflow: auto; /* ✅ Enables scroll if content is too tall */
padding: 40px 0; /* spacing so content is centered with space */
}

/* Modal content */
.career-modal-content {
background: #fff;
margin: auto;
padding: 20px 30px;
border-radius: 12px;
width: 90%;
max-width: 600px;
position: relative;
max-height: 90vh;       /* ✅ Prevents modal from going off-screen */
overflow-y: auto;       /* ✅ Internal scrolling */
box-shadow: 0 10px 25px rgba(0,0,0,0.2);
}

/* Form Layout */
.form-row {
display: flex;
gap: 1rem;
}
.form-row .form-group {
flex: 1;
}

/* Form Inputs */
.form-group input[type="text"],
.form-group input[type="email"],
.form-group input[type="tel"],
.form-group input[type="file"] {
width: 100%;
padding: 0.75rem;
border: 1px solid #ddd;
border-radius: 8px;
font-size: 1rem;
transition: all 0.3s ease;
background: #fafafa;
}

/* Focus & Hover */
.form-group input:focus {
border-color: var(--primary-color);
outline: none;
background: #fff;
box-shadow: 0 0 6px rgba(74, 107, 255, 0.2);
}

/* Labels */
.form-group label {
font-weight: 600;
margin-bottom: 0.4rem;
display: block;
color: #333;
}

/* Submit Button */
#apply-form button {
width: 100%;
padding: 0.9rem;
border: none;
border-radius: 8px;
background: var(--primary-color);
color: white;
font-size: 1.1rem;
font-weight: 600;
cursor: pointer;
transition: background 0.3s ease;
}

#apply-form button:hover {
background: #3549cc;
}

/* Additional styles specific to careers page */
.search-filters {
    padding: var(--spacing-lg);
    margin-bottom: var(--spacing-lg);
    border-radius: var(--border-radius-lg);
}
.search-bar { display: flex; margin-bottom: var(--spacing-md); }
.search-bar input {
    flex: 1; padding: 0.75rem; border: 1px solid #ddd;
    border-radius: var(--border-radius-md) 0 0 var(--border-radius-md);
    font-size: 1rem;
}
.search-bar button {
    background-color: var(--primary-color); color: white;
    border: none; padding: 0 1.5rem;
    border-radius: 0 var(--border-radius-md) var(--border-radius-md) 0;
    cursor: pointer;
}
.filters-container {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: var(--spacing-md);
}
.filter-group { margin-bottom: var(--spacing-sm); }
.filter-group label { display: block; margin-bottom: var(--spacing-xs); font-weight: 500; }
.filter-group select {
    width: 100%; padding: 0.5rem; border: 1px solid #ddd;
    border-radius: var(--border-radius-sm); background-color: white;
}
.careers-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
    gap: var(--spacing-lg); margin-bottom: var(--spacing-xl);
}
.career-card {
    padding: var(--spacing-lg); border-radius: var(--border-radius-lg);
    transition: all var(--transition-speed) ease;
}
.career-card:hover { transform: translateY(-5px); }
.career-header { display: flex; align-items: center; margin-bottom: var(--spacing-md); }
.career-icon {
    width: 50px; height: 50px; background-color: rgba(74, 107, 255, 0.1);
    border-radius: 50%; display: flex; align-items: center; justify-content: center;
    margin-right: var(--spacing-md); font-size: 1.5rem; color: var(--primary-color);
}
.career-title h3 { margin-bottom: 0; }
.career-title p { color: var(--gray-color); margin-bottom: 0; }
.career-details { margin-bottom: var(--spacing-md); }
.career-meta {
    display: flex; flex-wrap: wrap; gap: var(--spacing-sm);
    margin-bottom: var(--spacing-md);
}
.meta-item {
    background-color: rgba(74, 107, 255, 0.1); color: var(--primary-color);
    padding: 0.25rem 0.75rem; border-radius: 20px; font-size: 0.85rem;
}
.pagination { display: flex; justify-content: center; margin-top: var(--spacing-lg); gap: var(--spacing-xs); }
.pagination-item {
    width: 40px; height: 40px; display: flex; align-items: center; justify-content: center;
    border-radius: 50%; background-color: white; border: 1px solid #ddd; cursor: pointer;
    transition: all var(--transition-speed) ease;
}
.pagination-item.active,
.pagination-item:hover { background-color: var(--primary-color); color: white; border-color: var(--primary-color); }
/* --- Modal Styles --- */
.career-modal {
  display: none; position: fixed; z-index: 9999; padding-top: 100px;
  left: 0; top: 0; width: 100%; height: 100%; background-color: rgba(0,0,0,0.6);
}
.career-modal-content {
  background: #fff; margin: auto; padding: 20px; border-radius: 12px;
  width: 90%; max-width: 600px; position: relative;
}
.career-modal-content h2 { margin-top: 0; }
.close-btn {
  position: absolute; right: 15px; top: 10px; font-size: 24px; cursor: pointer;
}
/* Form styling */
.form-group { margin-bottom: 1rem; }
.form-group label { display: block; margin-bottom: 0.5rem; font-weight: 500; }
.form-group input[type="text"], 
.form-group input[type="file"] {
  width: 100%; padding: 0.6rem; border: 1px solid #ddd; border-radius: 6px;
}
header{
margin-bottom: 1px;
}
.logo a {
font-size: 2rem;
font-weight: 900;
background: linear-gradient(135deg, var(--accent-color), var(--accent-color-2));
-webkit-background-clip: text;
-webkit-text-fill-color: transparent;
letter-spacing: 1.5px;
transition: opacity var(--transition-speed);
}
.logo a:hover {
opacity: 0.8;
}
.admin-btn {
background: linear-gradient(135deg, var(--danger-color), var(--accent-color));
color: #fff !important;
font-weight: 700;
padding: 0.6rem 1.5rem;
border-radius: 50px; /* pill style */
border: none;
box-shadow: 0 4px 15px rgba(239, 71, 111, 0.4);
transition: all var(--transition-speed);
}
.admin-btn:hover {
background: linear-gradient(135deg, var(--accent-color), var(--danger-color));
transform: scale(1.05) translateY(-2px);
box-shadow: 0 6px 20px rgba(239, 71, 111, 0.6);
}
.footer {
margin-top: 20px;
}
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

.dark-mode .menu-toggle .bar {
background-color: #ffffff;
}
.dark-mode .page-banner{
color: #ffffff;
}
    </style>
</head>
<body>

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
            <li><a href="careers.php" class="active">Careers</a></li>
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
  <section class="page-banner" style="margin-top: 70px; padding: 40px;">
    <div class="container">
      <h1>Career Bank</h1>
      <p>Explore thousands of career options to find your perfect match</p>
    </div>
  </section>

  <!-- Career Search and Filters -->
  <section class="career-search-section">
    <div class="container">
      <div class="search-filters glass-effect">
        <h2>Find Your Career</h2>
        <div class="search-bar">
          <input type="text" id="search-input" placeholder="Search careers, skills, or keywords...">
          <button id="search-btn"><i class="fas fa-search"></i></button>
        </div>
        <div class="filters-container">
          <div class="filter-group">
            <label for="industry">Industry</label>
            <select id="industry">
              <option value="">All Industries</option>
              <option value="Technology">Technology</option>
              <option value="Healthcare">Healthcare</option>
              <option value="Finance">Finance</option>
              <option value="Education">Education</option>
              <option value="Creative Arts">Creative Arts</option>
              <option value="Engineering">Engineering</option>
            </select>
          </div>
          <div class="filter-group">
            <label for="education">Education Level</label>
            <select id="education">
              <option value="">All Levels</option>
              <option value="High School">High School</option>
              <option value="Associate">Associate's Degree</option>
              <option value="Bachelor">Bachelor's Degree</option>
              <option value="Master">Master's Degree</option>
              <option value="Doctorate">Doctorate</option>
            </select>
          </div>
          <div class="filter-group">
            <label for="salary">Salary Range</label>
            <select id="salary">
              <option value="">All Ranges</option>
              <option value="0-30000">$0 - $30,000</option>
              <option value="30000-50000">$30,000 - $50,000</option>
              <option value="50000-80000">$50,000 - $80,000</option>
              <option value="80000-100000">$80,000 - $100,000</option>
              <option value="100000+">$100,000+</option>
            </select>
          </div>
          <div class="filter-group">
            <label for="experience">Experience Level</label>
            <select id="experience">
              <option value="">All Levels</option>
              <option value="Entry">Entry Level</option>
              <option value="Mid">Mid Level</option>
              <option value="Senior">Senior Level</option>
              <option value="Executive">Executive</option>
            </select>
          </div>
        </div>
      </div>

      <!-- Career Results -->
      <div class="careers-results">
        <div class="results-header">
          <h2 id="results-count">Showing 6 careers</h2>
        </div>
        <div class="careers-grid" id="careers-grid"></div>
        <div class="pagination" id="pagination"></div>
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

  <!-- Careers Filtering & Pagination Script -->
  <script>
     const careers = [
  // --- Technology ---
  {title: "Software Developer", industry: "Technology", education: "Bachelor", salary: [70000,120000], experience: "Mid", icon: "fa-laptop-code", details: "Design, build, and maintain software applications and systems."},
  {title: "IT Support Specialist", industry: "Technology", education: "Associate", salary: [40000,60000], experience: "Entry", icon: "fa-headset", details: "Provide technical assistance and troubleshoot computer issues."},
  {title: "Data Scientist", industry: "Technology", education: "Master", salary: [90000,140000], experience: "Senior", icon: "fa-database", details: "Analyze large datasets to gain insights and build predictive models."},
  {title: "CTO (Chief Technology Officer)", industry: "Technology", education: "Bachelor", salary: [150000,200000], experience: "Executive", icon: "fa-user-tie", details: "Oversee technology strategies and lead IT teams."},

  // --- Healthcare ---
  {title: "Registered Nurse", industry: "Healthcare", education: "Bachelor", salary: [60000,90000], experience: "Mid", icon: "fa-heartbeat", details: "Provide and coordinate patient care, educate patients."},
  {title: "Medical Assistant", industry: "Healthcare", education: "Associate", salary: [35000,50000], experience: "Entry", icon: "fa-stethoscope", details: "Support physicians with patient care and administrative tasks."},
  {title: "Surgeon", industry: "Healthcare", education: "Doctorate", salary: [200000,300000], experience: "Senior", icon: "fa-user-md", details: "Perform surgeries and lead surgical teams."},
  {title: "Healthcare Administrator", industry: "Healthcare", education: "Master", salary: [80000,120000], experience: "Executive", icon: "fa-hospital-user", details: "Manage hospital operations and staff."},

  // --- Finance ---
  {title: "Financial Analyst", industry: "Finance", education: "Bachelor", salary: [65000,100000], experience: "Mid", icon: "fa-chart-line", details: "Analyze financial data, prepare reports, and advise decisions."},
  {title: "Accountant", industry: "Finance", education: "Bachelor", salary: [50000,80000], experience: "Entry", icon: "fa-calculator", details: "Prepare and examine financial records for businesses and individuals."},
  {title: "Investment Banker", industry: "Finance", education: "Master", salary: [120000,180000], experience: "Senior", icon: "fa-coins", details: "Advise on large financial transactions and capital raising."},
  {title: "CFO (Chief Financial Officer)", industry: "Finance", education: "Bachelor", salary: [150000,250000], experience: "Executive", icon: "fa-briefcase", details: "Oversee financial strategy and performance of the company."},

  // --- Education ---
  {title: "Elementary Teacher", industry: "Education", education: "Bachelor", salary: [45000,70000], experience: "Entry", icon: "fa-chalkboard-teacher", details: "Educate young students in basic subjects."},
  {title: "Professor", industry: "Education", education: "Doctorate", salary: [80000,130000], experience: "Senior", icon: "fa-university", details: "Teach at universities and conduct research."},
  {title: "School Principal", industry: "Education", education: "Master", salary: [90000,140000], experience: "Executive", icon: "fa-user-graduate", details: "Lead and manage school operations."},
  {title: "Teaching Assistant", industry: "Education", education: "High School", salary: [30000,45000], experience: "Entry", icon: "fa-book-reader", details: "Assist teachers in classrooms and help students learn."},

  // --- Creative Arts ---
  {title: "Graphic Designer", industry: "Creative Arts", education: "Bachelor", salary: [40000,75000], experience: "Entry", icon: "fa-palette", details: "Create visual concepts and designs."},
  {title: "Animator", industry: "Creative Arts", education: "Bachelor", salary: [50000,85000], experience: "Mid", icon: "fa-film", details: "Create animations and motion graphics for media projects."},
  {title: "Creative Director", industry: "Creative Arts", education: "Bachelor", salary: [100000,150000], experience: "Senior", icon: "fa-lightbulb", details: "Oversee creative projects and manage design teams."},
  {title: "Photographer", industry: "Creative Arts", education: "High School", salary: [30000,60000], experience: "Entry", icon: "fa-camera", details: "Capture professional photographs for events and media."},

  // --- Engineering ---
  {title: "Mechanical Engineer", industry: "Engineering", education: "Bachelor", salary: [70000,110000], experience: "Mid", icon: "fa-cogs", details: "Design, develop, and test mechanical devices."},
  {title: "Civil Engineer", industry: "Engineering", education: "Bachelor", salary: [65000,105000], experience: "Mid", icon: "fa-building", details: "Plan and design infrastructure projects."},
  {title: "Electrical Engineer", industry: "Engineering", education: "Bachelor", salary: [70000,115000], experience: "Senior", icon: "fa-bolt", details: "Design and develop electrical systems and equipment."},
  {title: "Engineering Manager", industry: "Engineering", education: "Master", salary: [120000,170000], experience: "Executive", icon: "fa-industry", details: "Lead engineering teams and manage technical projects."}
];


    const grid = document.getElementById("careers-grid");
    const pagination = document.getElementById("pagination");
    const resultsCount = document.getElementById("results-count");
    const perPage = 6;
    let currentPage = 1;

    function renderCareers(list, page=1) {
      grid.innerHTML = "";
      const start = (page-1)*perPage;
      const end = start + perPage;
      const pageItems = list.slice(start,end);

      pageItems.forEach(career => {
        const card = document.createElement("div");
        card.className = "career-card glass-effect";
        card.innerHTML = `
          <div class="career-header">
            <div class="career-icon"><i class="fas ${career.icon}"></i></div>
            <div class="career-title"><h3>${career.title}</h3><p>${career.industry}</p></div>
          </div>
          <div class="career-details"><p>${career.details}</p></div>
          <div class="career-meta">
            <span class="meta-item">$${career.salary[0].toLocaleString()} - $${career.salary[1].toLocaleString()}</span>
            <span class="meta-item">${career.education}'s Degree</span>
            <span class="meta-item">${career.experience} Level</span>
          </div>
          <a href="#" class="btn primary-btn apply-btn" data-job="${career.title}">Apply For</a>
        `;
        grid.appendChild(card);
      });

      renderPagination(list.length, page);
      resultsCount.textContent = `Showing ${list.length} careers`;
    }

    function renderPagination(total, page) {
      pagination.innerHTML = "";
      const pages = Math.ceil(total/perPage);
      if (pages <= 1) return;

      for (let i=1; i<=pages; i++) {
        const btn = document.createElement("a");
        btn.href="#";
        btn.className="pagination-item" + (i===page ? " active":"");
        btn.textContent=i;
        btn.addEventListener("click", (e)=>{e.preventDefault(); currentPage=i; applyFilters();});
        pagination.appendChild(btn);
      }
    }

    function applyFilters() {
      const keyword = document.getElementById("search-input").value.toLowerCase();
      const industry = document.getElementById("industry").value;
      const education = document.getElementById("education").value;
      const salary = document.getElementById("salary").value;
      const experience = document.getElementById("experience").value;

      let filtered = careers.filter(c => {
        const matchesKeyword = c.title.toLowerCase().includes(keyword) || c.details.toLowerCase().includes(keyword) || c.industry.toLowerCase().includes(keyword);
        const matchesIndustry = !industry || c.industry === industry;
        const matchesEducation = !education || c.education === education;
        const matchesExperience = !experience || c.experience === experience;
        let matchesSalary = true;
        if (salary) {
          if (salary.includes("+")) {
            const min = parseInt(salary);
            matchesSalary = c.salary[1] >= min;
          } else {
            const [min,max] = salary.split("-").map(Number);
            matchesSalary = c.salary[0] >= min && c.salary[1] <= max;
          }
        }
        return matchesKeyword && matchesIndustry && matchesEducation && matchesSalary && matchesExperience;
      });

      renderCareers(filtered,currentPage);
    }

    document.getElementById("search-btn").addEventListener("click", ()=>{currentPage=1;applyFilters();});
    document.getElementById("search-input").addEventListener("keyup", (e)=>{ if(e.key==="Enter"){currentPage=1;applyFilters();}});
    ["industry","education","salary","experience"].forEach(id=>{
      document.getElementById(id).addEventListener("change", ()=>{currentPage=1;applyFilters();});
    });

    renderCareers(careers);
  </script>
  <script src="js/main.js"></script>
    <script src="js/accessibility.js"></script>
    <script src="js/update-footers.js"></script>

<!-- Application Form Modal -->
<div id="apply-modal" class="career-modal">
  <div class="career-modal-content glass-effect">
    <span class="close-btn" id="close-apply-modal">&times;</span>
    <h2>Apply for <span id="apply-job-title"></span></h2>
<form id="apply-form" method="POST" action="apply.php" enctype="multipart/form-data">

  <div class="form-row">
    <div class="form-group">
      <label for="first-name">First Name</label>
      <input type="text" id="first-name" name="firstName" required>
    </div>
    <div class="form-group">
      <label for="last-name">Last Name</label>
      <input type="text" id="last-name" name="lastName" required>
    </div>
  </div>

  <div class="form-group">
    <label for="email">Email Address</label>
    <input type="email" id="email" name="email" required>
  </div>

  <div class="form-group">
    <label for="phone">Phone Number</label>
    <input type="tel" id="phone" name="phone" required>
  </div>

  <div class="form-group">
    <label for="resume">Upload Resume</label>
    <input type="file" id="resume" name="resume" accept=".pdf,.doc,.docx" required>
  </div>

  <div class="form-group">
    <label for="job">Job Applied For</label>
    <input type="text" id="job" name="job" readonly>
  </div>

  <button type="submit" class="btn primary-btn">Submit Application</button>
</form>

  </div>
</div>


  <script>
    // Application Modal Elements
    const applyModal = document.getElementById("apply-modal");
    const closeApplyModal = document.getElementById("close-apply-modal");
    const applyJobTitle = document.getElementById("apply-job-title");
    const jobInput = document.getElementById("job");

    // Open Apply Modal
    document.addEventListener("click", function(e) {
      if (e.target.classList.contains("apply-btn")) {
        e.preventDefault();
        const jobTitle = e.target.getAttribute("data-job");
        applyJobTitle.textContent = jobTitle;
        jobInput.value = jobTitle;
        applyModal.style.display = "block";
      }
    });

    // Close Modal
    closeApplyModal.addEventListener("click", () => { applyModal.style.display = "none"; });
    window.addEventListener("click", (e) => { if (e.target == applyModal) applyModal.style.display = "none"; });

// Handle Form Submit (simple submit)
document.getElementById("apply-form").addEventListener("submit", function() {
  alert("Application submitted successfully for " + jobInput.value + "!");
});


  </script>
</body>
</html>