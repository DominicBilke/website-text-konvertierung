// Modern JavaScript for Text-Konvertierung
document.addEventListener('DOMContentLoaded', function() {
    
    // Initialize all components
    initSmoothScrolling();
    initFileUpload();
    initAnimations();
    initFormEnhancements();
    initMobileMenu();
    initLanguageSwitcher();
    
    // Smooth scrolling for navigation links
    function initSmoothScrolling() {
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        });
    }
    
    // Enhanced file upload functionality
    function initFileUpload() {
        const fileUpload = document.querySelector('.file-upload');
        const fileInput = document.querySelector('#pdffile');
        
        if (fileUpload && fileInput) {
            // Drag and drop events
            ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
                fileUpload.addEventListener(eventName, preventDefaults, false);
            });
            
            function preventDefaults(e) {
                e.preventDefault();
                e.stopPropagation();
            }
            
            // Highlight on drag
            ['dragenter', 'dragover'].forEach(eventName => {
                fileUpload.addEventListener(eventName, highlight, false);
            });
            
            ['dragleave', 'drop'].forEach(eventName => {
                fileUpload.addEventListener(eventName, unhighlight, false);
            });
            
            function highlight(e) {
                fileUpload.classList.add('highlight');
            }
            
            function unhighlight(e) {
                fileUpload.classList.remove('highlight');
            }
            
            // Handle file drop
            fileUpload.addEventListener('drop', handleDrop, false);
            
            function handleDrop(e) {
                const dt = e.dataTransfer;
                const files = dt.files;
                fileInput.files = files;
                
                // Update label to show selected file
                updateFileLabel(files[0]);
            }
            
            // Handle file selection via click
            fileInput.addEventListener('change', function(e) {
                if (this.files.length > 0) {
                    updateFileLabel(this.files[0]);
                }
            });
            
            function updateFileLabel(file) {
                const label = fileUpload.querySelector('.file-upload-label');
                if (file) {
                    const fileSize = (file.size / 1024 / 1024).toFixed(2);
                    label.innerHTML = `
                        <i class="fas fa-check-circle fa-2x" style="color: var(--secondary-color);"></i><br>
                        <strong>${file.name}</strong><br>
                        <small>Size: ${fileSize} MB</small>
                    `;
                    
                    // Add success animation
                    fileUpload.style.borderColor = 'var(--secondary-color)';
                    setTimeout(() => {
                        fileUpload.style.borderColor = '';
                    }, 2000);
                }
            }
        }
    }
    
    // Intersection Observer for animations
    function initAnimations() {
        const observerOptions = {
            threshold: 0.1,
            rootMargin: '0px 0px -50px 0px'
        };
        
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('fade-in-up');
                    // Add staggered animation for grid items
                    if (entry.target.parentElement && entry.target.parentElement.classList.contains('features-grid')) {
                        const index = Array.from(entry.target.parentElement.children).indexOf(entry.target);
                        entry.target.style.animationDelay = `${index * 0.1}s`;
                    }
                }
            });
        }, observerOptions);
        
        // Observe all elements with animation classes
        document.querySelectorAll('.fade-in-up, .slide-in-left, .slide-in-right').forEach(el => {
            observer.observe(el);
        });
    }
    
    // Form enhancements
    function initFormEnhancements() {
        document.querySelectorAll('form').forEach(form => {
            form.addEventListener('submit', function(e) {
                const submitBtn = this.querySelector('button[type="submit"]');
                if (submitBtn) {
                    // Show loading state
                    const originalText = submitBtn.innerHTML;
                    submitBtn.innerHTML = '<span class="loading"></span> Processing...';
                    submitBtn.disabled = true;
                    
                    // Re-enable button after a delay (in case of errors)
                    setTimeout(() => {
                        submitBtn.innerHTML = originalText;
                        submitBtn.disabled = false;
                    }, 10000);
                }
            });
            
            // Real-time form validation
            const inputs = form.querySelectorAll('input, select, textarea');
            inputs.forEach(input => {
                input.addEventListener('blur', validateField);
                input.addEventListener('input', clearValidation);
            });
        });
        
        function validateField(e) {
            const field = e.target;
            const value = field.value.trim();
            
            if (field.hasAttribute('required') && !value) {
                showFieldError(field, 'This field is required');
            } else if (field.type === 'email' && value && !isValidEmail(value)) {
                showFieldError(field, 'Please enter a valid email address');
            } else {
                clearFieldError(field);
            }
        }
        
        function clearValidation(e) {
            clearFieldError(e.target);
        }
        
        function showFieldError(field, message) {
            clearFieldError(field);
            field.classList.add('error');
            const errorDiv = document.createElement('div');
            errorDiv.className = 'field-error';
            errorDiv.textContent = message;
            errorDiv.style.color = '#ef4444';
            errorDiv.style.fontSize = '0.875rem';
            errorDiv.style.marginTop = '0.25rem';
            field.parentNode.appendChild(errorDiv);
        }
        
        function clearFieldError(field) {
            field.classList.remove('error');
            const errorDiv = field.parentNode.querySelector('.field-error');
            if (errorDiv) {
                errorDiv.remove();
            }
        }
        
        function isValidEmail(email) {
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            return emailRegex.test(email);
        }
    }
    
    // Mobile menu functionality
    function initMobileMenu() {
        const mobileMenuToggle = document.createElement('button');
        mobileMenuToggle.className = 'mobile-menu-toggle';
        mobileMenuToggle.innerHTML = '<i class="fas fa-bars"></i>';
        mobileMenuToggle.style.display = 'none';
        
        const header = document.querySelector('.header-container');
        const nav = document.querySelector('.nav-menu');
        
        if (header && nav) {
            header.appendChild(mobileMenuToggle);
            
            // Show mobile menu toggle on small screens
            function checkScreenSize() {
                if (window.innerWidth <= 768) {
                    mobileMenuToggle.style.display = 'block';
                    nav.style.display = 'none';
                } else {
                    mobileMenuToggle.style.display = 'none';
                    nav.style.display = 'flex';
                }
            }
            
            // Check on load and resize
            checkScreenSize();
            window.addEventListener('resize', checkScreenSize);
            
            // Toggle mobile menu
            mobileMenuToggle.addEventListener('click', function() {
                const isVisible = nav.style.display === 'flex';
                nav.style.display = isVisible ? 'none' : 'flex';
                this.innerHTML = isVisible ? '<i class="fas fa-bars"></i>' : '<i class="fas fa-times"></i>';
            });
        }
    }
    
    // Enhanced scroll effects
    function initScrollEffects() {
        let ticking = false;
        
        function updateHeader() {
            const header = document.querySelector('.modern-header');
            if (window.scrollY > 100) {
                header.classList.add('scrolled');
            } else {
                header.classList.remove('scrolled');
            }
            ticking = false;
        }
        
        function requestTick() {
            if (!ticking) {
                requestAnimationFrame(updateHeader);
                ticking = true;
            }
        }
        
        window.addEventListener('scroll', requestTick);
    }
    
    // Initialize scroll effects
    initScrollEffects();
    
    // Add CSS for mobile menu toggle
    const style = document.createElement('style');
    style.textContent = `
        .mobile-menu-toggle {
            background: none;
            border: none;
            color: var(--text-secondary);
            font-size: 1.5rem;
            cursor: pointer;
            padding: 0.5rem;
            border-radius: 0.25rem;
            transition: all 0.2s ease;
        }
        
        .mobile-menu-toggle:hover {
            color: var(--primary-color);
            background: var(--bg-secondary);
        }
        
        .modern-header.scrolled {
            background: rgba(255, 255, 255, 0.98);
            box-shadow: var(--shadow-md);
        }
        
        .field-error {
            color: #ef4444;
            font-size: 0.875rem;
            margin-top: 0.25rem;
        }
        
        .form-input.error,
        .form-select.error,
        .form-textarea.error {
            border-color: #ef4444;
        }
        
        @media (max-width: 768px) {
            .nav-menu {
                position: absolute;
                top: 100%;
                left: 0;
                right: 0;
                background: var(--bg-primary);
                flex-direction: column;
                padding: 1rem;
                box-shadow: var(--shadow-md);
                border-top: 1px solid var(--border-color);
                gap: 1rem;
            }
            
            .nav-menu li {
                width: 100%;
            }
            
            .nav-link {
                display: block;
                padding: 0.75rem;
                border-radius: 0.5rem;
                transition: all 0.2s ease;
            }
            
            .nav-link:hover {
                background: var(--bg-secondary);
            }
        }
    `;
    document.head.appendChild(style);
    
    // Add success/error message handling
    window.showMessage = function(message, type = 'success') {
        const messageDiv = document.createElement('div');
        messageDiv.className = `message ${type}-message`;
        messageDiv.textContent = message;
        
        // Remove existing messages
        document.querySelectorAll('.message').forEach(msg => msg.remove());
        
        // Add new message
        document.body.appendChild(messageDiv);
        
        // Auto-remove after 5 seconds
        setTimeout(() => {
            messageDiv.remove();
        }, 5000);
        
        // Add CSS for messages
        if (!document.querySelector('#message-styles')) {
            const messageStyle = document.createElement('style');
            messageStyle.id = 'message-styles';
            messageStyle.textContent = `
                .message {
                    position: fixed;
                    top: 20px;
                    right: 20px;
                    padding: 1rem 1.5rem;
                    border-radius: 0.5rem;
                    color: white;
                    font-weight: 500;
                    z-index: 10000;
                    animation: slideInRight 0.3s ease-out;
                    max-width: 400px;
                    box-shadow: var(--shadow-lg);
                }
                
                .success-message {
                    background: var(--secondary-color);
                }
                
                .error-message {
                    background: #ef4444;
                }
                
                @keyframes slideInRight {
                    from {
                        transform: translateX(100%);
                        opacity: 0;
                    }
                    to {
                        transform: translateX(0);
                        opacity: 1;
                    }
                }
            `;
            document.head.appendChild(messageStyle);
        }
    };
    
    // Add loading states to buttons
    document.querySelectorAll('.btn').forEach(btn => {
        btn.addEventListener('click', function() {
            if (!this.disabled && !this.classList.contains('btn-outline')) {
                this.style.transform = 'scale(0.95)';
                setTimeout(() => {
                    this.style.transform = '';
                }, 150);
            }
        });
    });
    
    // Add keyboard navigation support
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            // Close mobile menu if open
            const nav = document.querySelector('.nav-menu');
            const mobileToggle = document.querySelector('.mobile-menu-toggle');
            if (nav && mobileToggle && window.innerWidth <= 768) {
                nav.style.display = 'none';
                mobileToggle.innerHTML = '<i class="fas fa-bars"></i>';
            }
        }
    });
    
    // Add progress indicator for file uploads
    function addProgressIndicator() {
        const fileInput = document.querySelector('#pdffile');
        if (fileInput) {
            fileInput.addEventListener('change', function() {
                if (this.files.length > 0) {
                    const file = this.files[0];
                    const progressBar = document.createElement('div');
                    progressBar.className = 'upload-progress';
                    progressBar.innerHTML = `
                        <div class="progress-bar">
                            <div class="progress-fill"></div>
                        </div>
                        <span class="progress-text">Uploading ${file.name}...</span>
                    `;
                    
                    const form = this.closest('form');
                    form.insertBefore(progressBar, form.querySelector('button'));
                    
                    // Simulate upload progress
                    let progress = 0;
                    const interval = setInterval(() => {
                        progress += Math.random() * 30;
                        if (progress >= 100) {
                            progress = 100;
                            clearInterval(interval);
                            setTimeout(() => {
                                progressBar.remove();
                            }, 1000);
                        }
                        progressBar.querySelector('.progress-fill').style.width = progress + '%';
                        progressBar.querySelector('.progress-text').textContent = 
                            `Uploading ${file.name}... ${Math.round(progress)}%`;
                    }, 200);
                }
            });
        }
    }
    
    // Initialize progress indicator
    addProgressIndicator();
    
    // Language switching functionality
    function initLanguageSwitcher() {
        const langButtons = document.querySelectorAll('.lang-btn');
        let currentLang = 'de'; // Default language is German
        
        // Check if user has a saved language preference
        const savedLang = localStorage.getItem('preferred-language');
        if (savedLang) {
            currentLang = savedLang;
            updateLanguageDisplay(currentLang);
        }
        
        langButtons.forEach(btn => {
            btn.addEventListener('click', function() {
                const lang = this.getAttribute('data-lang');
                if (lang !== currentLang) {
                    currentLang = lang;
                    updateLanguageDisplay(lang);
                    localStorage.setItem('preferred-language', lang);
                    
                    // Update active button state
                    langButtons.forEach(b => b.classList.remove('active'));
                    this.classList.add('active');
                }
            });
        });
        
        function updateLanguageDisplay(lang) {
            // Update all elements with language data attributes
            document.querySelectorAll('[data-' + lang + ']').forEach(element => {
                const text = element.getAttribute('data-' + lang);
                if (text) {
                    element.textContent = text;
                }
            });
            
            // Update placeholders
            document.querySelectorAll('[data-placeholder-' + lang + ']').forEach(element => {
                const placeholder = element.getAttribute('data-placeholder-' + lang);
                if (placeholder) {
                    element.placeholder = placeholder;
                }
            });
            
            // Update page title
            const titleElement = document.querySelector('title');
            if (titleElement) {
                if (lang === 'de') {
                    titleElement.textContent = 'Text-Konvertierung - Moderne OCR & Sprachkonvertierung';
                } else {
                    titleElement.textContent = 'Text-Konvertierung - Modern OCR & Speech Conversion';
                }
            }
            
            // Update meta description
            const metaDesc = document.querySelector('meta[name="description"]');
            if (metaDesc) {
                if (lang === 'de') {
                    metaDesc.setAttribute('content', 'Konvertieren Sie Ihre Texte mit Leichtigkeit mit unseren modernen OCR-, Übersetzungs- und Text-zu-Sprache-Tools.');
                } else {
                    metaDesc.setAttribute('content', 'Convert your texts with ease using our modern OCR, translation, and text-to-speech tools.');
                }
            }
        }
        
        // Initialize with current language
        updateLanguageDisplay(currentLang);
        
        // Set initial active button
        langButtons.forEach(btn => {
            if (btn.getAttribute('data-lang') === currentLang) {
                btn.classList.add('active');
            } else {
                btn.classList.remove('active');
            }
        });
    }
    
    // Add CSS for progress bar
    const progressStyle = document.createElement('style');
    progressStyle.textContent = `
        .upload-progress {
            margin: 1rem 0;
            padding: 1rem;
            background: var(--bg-accent);
            border-radius: 0.5rem;
            border: 1px solid var(--border-color);
        }
        
        .progress-bar {
            width: 100%;
            height: 8px;
            background: var(--border-color);
            border-radius: 4px;
            overflow: hidden;
            margin-bottom: 0.5rem;
        }
        
        .progress-fill {
            height: 100%;
            background: linear-gradient(90deg, var(--primary-color), var(--secondary-color));
            width: 0%;
            transition: width 0.3s ease;
        }
        
        .progress-text {
            font-size: 0.875rem;
            color: var(--text-secondary);
        }
    `;
    document.head.appendChild(progressStyle);
    
    console.log('Text-Konvertierung modern JavaScript initialized successfully!');
});
