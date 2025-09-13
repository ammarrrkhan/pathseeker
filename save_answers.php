<?php
// Database connection

include 'db.php';


// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_id = 1; // Static for now
    
    // First, clear any previous answers for this user to avoid duplicates
    $conn->query("DELETE FROM quiz_answers WHERE user_id = $user_id");
    $conn->query("DELETE FROM quiz_results WHERE user_id = $user_id");
    
    // Process answers
    foreach ($_POST as $question => $answer) {
        if (strpos($question, 'q') === 0) {
            $qid = (int) str_replace("q", "", $question);
            
            // Get correct answer and weightage from DB
            $sql = "SELECT correct_answer, weightage FROM quiz_questions WHERE id = $qid";
            $result = $conn->query($sql);
            $row = $result->fetch_assoc();
            
            $is_correct = ($row['correct_answer'] == $answer) ? 1 : 0;
            $weightage = $is_correct ? $row['weightage'] : 0;
            
            // Save answer
            $stmt = $conn->prepare("INSERT INTO quiz_answers (user_id, question_id, answer_text, is_correct, weightage) VALUES (?, ?, ?, ?, ?)");
            $stmt->bind_param("iisii", $user_id, $qid, $answer, $is_correct, $weightage);
            $stmt->execute();
        }
    }
    
    // Calculate total score
    $score_sql = "SELECT SUM(weightage) as total_score FROM quiz_answers WHERE user_id = $user_id";
    $score_result = $conn->query($score_sql);
    $score_row = $score_result->fetch_assoc();
    $total_score = $score_row['total_score'] ?? 0;
    
    // Save final result
    $stmt = $conn->prepare("INSERT INTO quiz_results (user_id, score) VALUES (?, ?)");
    $stmt->bind_param("ii", $user_id, $total_score);
    $stmt->execute();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Quiz Results - PathSeeker</title>
  <link rel="stylesheet" href="css/styles.css">
  <link rel="stylesheet" href="css/responsive.css">
  <link rel="stylesheet" href="css/dark-mode.css">
  <!-- Font Awesome for icons -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <!-- Google Fonts -->
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  <style>

    /* Main content wrapper */
    .main-wrapper {
        flex: 1;
        padding: 20px;
        display: flex;
        justify-content: center;
        align-items: flex-start;
        padding-top: 100px; /* Account for fixed header */
        padding-bottom: 40px; /* Space for footer */
    }

    .glass-effect {
        background: var(--glass-gradient), var(--glass-bg);
        backdrop-filter: var(--glass-blur);
        -webkit-backdrop-filter: var(--glass-blur);
        border: 1px solid var(--glass-border);
        box-shadow: var(--glass-shadow);
        border-radius: var(--border-radius-lg);
        transition: all var(--transition-speed) ease;
    }

    .glass-effect:hover {
        box-shadow: var(--glass-shadow-hover);
        transform: translateY(-3px);
    }

    .results-container {
        width: 100%;
        max-width: 800px;
        padding: 30px;
        border-radius: 15px;
        background: var(--glass-gradient), var(--glass-bg);
        backdrop-filter: var(--glass-blur);
        -webkit-backdrop-filter: var(--glass-blur);
        border: 1px solid var(--glass-border);
        box-shadow: var(--glass-shadow);
        margin: 20px 0;
    }

    h1, h2 {
        font-family: var(--heading-font);
        font-weight: 700;
        color: var(--dark-color);
        text-align: center;
        margin-bottom: 20px;
    }

    h1 {
        font-size: 2.5rem;
    }

    h2 {
        font-size: 2rem;
        margin-top: 30px;
    }

    .success-message {
        text-align: center;
        padding: 20px;
        margin-bottom: 30px;
        border-radius: var(--border-radius-md);
        background: rgba(6, 214, 160, 0.1);
        border: 1px solid rgba(6, 214, 160, 0.3);
    }

    .score-display {
        font-size: 2.5rem;
        font-weight: 700;
        color: var(--success-color);
        margin: 20px 0;
        padding: 15px;
        border-radius: var(--border-radius-md);
        background: rgba(255, 255, 255, 0.7);
        display: inline-block;
    }

    .answer {
        margin-bottom: 25px;
        padding: 20px;
        border-radius: 12px;
        background: var(--glass-gradient), rgba(255, 255, 255, 0.7);
        backdrop-filter: var(--glass-blur);
        -webkit-backdrop-filter: var(--glass-blur);
        border: 1px solid var(--glass-border);
        transition: 0.3s;
    }
    
    .answer:hover {
        background: rgba(255, 255, 255, 0.9);
        transform: translateY(-2px);
    }
    
    .answer.correct {
        border-left: 5px solid var(--success-color);
    }
    
    .answer.incorrect {
        border-left: 5px solid var(--danger-color);
    }
    
    .question-text {
        font-weight: 600;
        color: var(--dark-color);
        margin-bottom: 15px;
        font-size: 1.1rem;
    }
    
    .answer-text {
        margin-bottom: 10px;
        padding: 8px 12px;
        border-radius: var(--border-radius-sm);
        background: rgba(255, 255, 255, 0.5);
    }

    /* Action buttons */
    .action-buttons {
        display: flex;
        justify-content: center;
        gap: 20px;
        margin-top: 40px;
    }

    .btn {
        background: linear-gradient(135deg, var(--primary-color), var(--accent-color-3));
        color: white;
        border: none;
        padding: 12px 24px;
        border-radius: var(--border-radius-md);
        font-size: 1.1rem;
        font-weight: 600;
        cursor: pointer;
        transition: all var(--transition-speed) ease;
        text-decoration: none;
        display: inline-block;
        text-align: center;
    }

    .btn:hover {
        background: linear-gradient(135deg, var(--accent-color-3), var(--primary-color));
        transform: translateY(-3px);
        box-shadow: 0 6px 20px rgba(0, 0, 0, 0.2);
        color: white;
    }

    /* Dark Mode Styles */
    body.dark-mode {
        --light-color: #1a202c;
        --dark-color: #f8f9fa;
        --gray-color: #a0aec0;
        background-color: var(--light-color);
        color: var(--dark-color);
    }

    body.dark-mode .results-container,
    body.dark-mode .answer,
    body.dark-mode .score-display,
    body.dark-mode .answer-text {
        background: var(--glass-gradient-dark), var(--glass-bg-dark);
        border: 1px solid var(--glass-border-dark);
        color: var(--dark-color);
    }

    body.dark-mode h1,
    body.dark-mode h2,
    body.dark-mode .question-text {
        color: var(--dark-color);
    }

    body.dark-mode .answer:hover,
    body.dark-mode .score-display {
        background: rgba(26, 32, 44, 0.9);
    }

    /* Accessibility Panel */
    .accessibility-panel {
        position: fixed;
        bottom: 20px;
        right: 20px;
        z-index: 1000;
    }

    .accessibility-toggle {
        width: 50px;
        height: 50px;
        border-radius: 50%;
        background: linear-gradient(135deg, var(--primary-color), var(--accent-color-3));
        color: white;
        border: none;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
        transition: all var(--transition-speed) ease;
        position: relative;
        overflow: hidden;
    }

    .accessibility-toggle::before {
        content: '';
        position: absolute;
        top: -50%;
        left: -50%;
        width: 200%;
        height: 200%;
        background: radial-gradient(circle, rgba(255,255,255,0.3) 0%, rgba(255,255,255,0) 70%);
        opacity: 0;
        transform: scale(0.5);
        transition: transform 0.5s ease, opacity 0.5s ease;
    }

    .accessibility-toggle:hover {
        background: linear-gradient(135deg, var(--accent-color-3), var(--primary-color));
        transform: scale(1.1) rotate(5deg);
        box-shadow: 0 6px 20px rgba(0, 0, 0, 0.3);
    }

    .accessibility-toggle:hover::before {
        opacity: 1;
        transform: scale(1);
    }

    .accessibility-options {
        position: absolute;
        bottom: 60px;
        right: 0;
        background: var(--glass-gradient), var(--glass-bg);
        backdrop-filter: var(--glass-blur);
        -webkit-backdrop-filter: var(--glass-blur);
        border-radius: var(--border-radius-lg);
        border: 1px solid var(--glass-border);
        padding: var(--spacing-lg);
        box-shadow: var(--glass-shadow);
        width: 280px;
        display: none;
        transform: translateY(20px);
        opacity: 0;
        transition: transform 0.3s ease, opacity 0.3s ease;
    }

    body.dark-mode .accessibility-options {
        background: var(--glass-gradient-dark), var(--glass-bg-dark);
        border: 1px solid var(--glass-border-dark);
        box-shadow: var(--glass-shadow);
    }

    .accessibility-options.show {
        display: block;
        transform: translateY(0);
        opacity: 1;
        animation: fadeIn 0.3s ease;
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
        color: var(--dark-color);
    }

    body.dark-mode .option label {
        color: var(--dark-color);
    }

    /* Switch Toggle */
    .switch {
        position: relative;
        display: inline-block;
        width: 60px;
        height: 34px;
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
        height: 26px;
        width: 26px;
        left: 4px;
        bottom: 4px;
        background-color: white;
        transition: .4s;
    }

    input:checked + .slider {
        background-color: var(--primary-color);
    }

    input:focus + .slider {
        box-shadow: 0 0 1px var(--primary-color);
    }

    input:checked + .slider:before {
        transform: translateX(26px);
    }

    .slider.round {
        border-radius: 34px;
    }

    .slider.round:before {
        border-radius: 50%;
    }

    .font-size-controls {
        display: flex;
        gap: var(--spacing-xs);
    }

    .font-size-controls button {
        flex: 1;
        padding: 5px 10px;
        background-color: #f1f1f1;
        border: none;
        border-radius: var(--border-radius-sm);
        cursor: pointer;
        transition: all var(--transition-speed) ease;
        color: var(--dark-color);
    }

    body.dark-mode .font-size-controls button {
        background-color: #4a5568;
        color: white;
    }

    .font-size-controls button:hover {
        background-color: var(--primary-color);
        color: white;
    }

    /* Animations */
    @keyframes fadeIn {
        from { opacity: 0; }
        to { opacity: 1; }
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
            <li><a href="quiz.php" class="active">Quiz</a></li>
            <li><a href="resources.php">Resources</a></li>
            <li><a href="success-stories.php">Success Stories</a></li>
            <li><a href="login.php">Login / Signup</a></li>
            <!-- Buttons -->
            <li><a href="admin-login.php" class="nav-btn admin-btn">Admin Portal</a></li>
        </ul>
    </nav>
</header>


  <!-- Main Content -->
  <div class="main-wrapper">
      <div class="results-container glass-effect">
        <h1>Quiz Results</h1>
        <p class="text-center">See how you performed on the career assessment</p>
        
        <div class="success-message">
          <h2>✅ Assessment Completed Successfully!</h2>
          <div class="score-display">Your Score: <?php echo $total_score; ?> / 40</div>
          <p>Below you can review your answers and see which ones were correct.</p>
        </div>
        
        <h2>Your Answers</h2>
        <?php
        $user_id = 1;
        $sql = "
            SELECT q.question_text, q.correct_answer, a.answer_text, a.is_correct, a.weightage
            FROM quiz_answers a
            JOIN quiz_questions q ON a.question_id = q.id
            WHERE a.user_id = ?
            ORDER BY q.id
        ";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()){
                $status = $row['is_correct'] ? 'correct' : 'incorrect';
                $mark = $row['is_correct'] ? '✅ Correct' : '❌ Incorrect';
                
                echo "<div class='answer $status'>";
                echo "<div class='question-text'>{$row['question_text']}</div>";
                echo "<div class='answer-text'>Your answer: {$row['answer_text']}</div>";
                if (!$row['is_correct']) {
                    echo "<div class='answer-text'>Correct answer: {$row['correct_answer']}</div>";
                }
                echo "<div>$mark • Score: {$row['weightage']}</div>";
                echo "</div>";
            }
        }
        ?>
        
        <div class="action-buttons">
          <a href="quiz.php" class="btn secondary-btn">Take Quiz Again</a>
          <a href="careers.php" class="btn secondary-btn">Explore Careers</a>
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
      // Toggle accessibility options panel
      document.getElementById('accessibilityToggle').addEventListener('click', function() {
          const optionsPanel = document.querySelector('.accessibility-options');
          optionsPanel.classList.toggle('show');
      });

      // Dark mode toggle functionality
      const darkModeToggle = document.getElementById('darkModeToggle');
      const body = document.body;

      // Check for saved dark mode preference
      if (localStorage.getItem('darkMode') === 'enabled') {
          body.classList.add('dark-mode');
          darkModeToggle.checked = true;
      }

      darkModeToggle.addEventListener('change', function() {
          if (this.checked) {
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

      // Set base font size
      let currentFontSize = 16;

      decreaseFontBtn.addEventListener('click', function() {
          if (currentFontSize > 12) {
              currentFontSize--;
              document.body.style.fontSize = currentFontSize + 'px';
          }
      });

      resetFontBtn.addEventListener('click', function() {
          currentFontSize = 16;
          document.body.style.fontSize = currentFontSize + 'px';
      });

      increaseFontBtn.addEventListener('click', function() {
          if (currentFontSize < 24) {
              currentFontSize++;
              document.body.style.fontSize = currentFontSize + 'px';
          }
      });
  </script>
  <!-- JavaScript -->
  <script src="js/main.js"></script>
  <script src="js/update-footers.js"></script>
</body>
</html>