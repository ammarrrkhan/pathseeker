// accessibility.js - Updated version
document.addEventListener('DOMContentLoaded', function() {
    // Accessibility Panel Toggle
    const accessibilityToggle = document.getElementById('accessibilityToggle');
    const accessibilityOptions = document.querySelector('.accessibility-options');
    
    if (accessibilityToggle && accessibilityOptions) {
        accessibilityToggle.addEventListener('click', function() {
            accessibilityOptions.classList.toggle('show');
        });
        
        // Close panel when clicking outside
        document.addEventListener('click', function(event) {
            const isClickInside = accessibilityToggle.contains(event.target) || 
                                 accessibilityOptions.contains(event.target);
            
            if (!isClickInside && accessibilityOptions.classList.contains('show')) {
                accessibilityOptions.classList.remove('show');
            }
        });
    }
    
    // Dark Mode Toggle - Updated for consistency
    const darkModeToggle = document.getElementById('darkModeToggle');
    const body = document.body;
    
    // Check for saved theme preference
    const savedTheme = localStorage.getItem('theme');
    if (savedTheme === 'dark') {
        body.classList.add('dark-mode');
        if (darkModeToggle) darkModeToggle.checked = true;
    }
    
    if (darkModeToggle) {
        darkModeToggle.addEventListener('change', function() {
            if (this.checked) {
                body.classList.add('dark-mode');
                localStorage.setItem('theme', 'dark');
            } else {
                body.classList.remove('dark-mode');
                localStorage.setItem('theme', 'light');
            }
        });
    }
    
    // Font Size Adjustment
    const increaseFontBtn = document.getElementById('increaseFontBtn');
    const decreaseFontBtn = document.getElementById('decreaseFontBtn');
    const resetFontBtn = document.getElementById('resetFontBtn');
    
    // Get saved font size or set default
    let currentFontSize = parseFloat(localStorage.getItem('fontSize')) || 1.0;
    applyFontSize();
    
    if (increaseFontBtn) {
        increaseFontBtn.addEventListener('click', function() {
            if (currentFontSize < 1.5) { // Max 150%
                currentFontSize += 0.1;
                applyFontSize();
            }
        });
    }
    
    if (decreaseFontBtn) {
        decreaseFontBtn.addEventListener('click', function() {
            if (currentFontSize > 0.8) { // Min 80%
                currentFontSize -= 0.1;
                applyFontSize();
            }
        });
    }
    
    if (resetFontBtn) {
        resetFontBtn.addEventListener('click', function() {
            currentFontSize = 1.0;
            applyFontSize();
        });
    }
    
    function applyFontSize() {
        document.documentElement.style.fontSize = `${currentFontSize}rem`;
        localStorage.setItem('fontSize', currentFontSize);
    }
    
    // Initialize dark mode on page load
    function initializeDarkMode() {
        const savedTheme = localStorage.getItem('theme');
        if (savedTheme === 'dark') {
            document.body.classList.add('dark-mode');
            const darkModeToggle = document.getElementById('darkModeToggle');
            if (darkModeToggle) darkModeToggle.checked = true;
        }
    }
    
    initializeDarkMode();
});