/**
 * AlfaisalX - Main JavaScript
 * 
 * Handles interactive functionality for the website.
 */

document.addEventListener('DOMContentLoaded', function() {
    
    // ==========================================================================
    // Theme Toggle (Dark/Light Mode)
    // ==========================================================================
    
    const themeToggle = document.getElementById('theme-toggle');
    const html = document.documentElement;
    
    // Check for saved theme preference or default to dark
    const savedTheme = localStorage.getItem('alfaisalx-theme') || 'dark';
    html.setAttribute('data-theme', savedTheme);
    
    if (themeToggle) {
        themeToggle.addEventListener('click', function() {
            const currentTheme = html.getAttribute('data-theme');
            const newTheme = currentTheme === 'dark' ? 'light' : 'dark';
            
            html.setAttribute('data-theme', newTheme);
            localStorage.setItem('alfaisalx-theme', newTheme);
            
            // Add a smooth transition class
            html.classList.add('theme-transitioning');
            setTimeout(() => {
                html.classList.remove('theme-transitioning');
            }, 300);
        });
    }
    
    // ==========================================================================
    // Mobile Navigation
    // ==========================================================================
    
    const mobileMenuToggle = document.getElementById('mobile-menu-toggle');
    const mobileNavClose = document.getElementById('mobile-nav-close');
    const mobileNav = document.getElementById('mobile-nav');
    const mobileNavOverlay = document.getElementById('mobile-nav-overlay');
    const body = document.body;
    
    function openMobileNav() {
        mobileMenuToggle.classList.add('active');
        mobileNav.classList.add('active');
        if (mobileNavOverlay) mobileNavOverlay.classList.add('active');
        body.classList.add('menu-open');
    }
    
    function closeMobileNav() {
        mobileMenuToggle.classList.remove('active');
        mobileNav.classList.remove('active');
        if (mobileNavOverlay) mobileNavOverlay.classList.remove('active');
        body.classList.remove('menu-open');
    }
    
    if (mobileMenuToggle && mobileNav) {
        mobileMenuToggle.addEventListener('click', function() {
            if (mobileNav.classList.contains('active')) {
                closeMobileNav();
            } else {
                openMobileNav();
            }
        });
        
        // Close button
        if (mobileNavClose) {
            mobileNavClose.addEventListener('click', closeMobileNav);
        }
        
        // Close when clicking overlay
        if (mobileNavOverlay) {
            mobileNavOverlay.addEventListener('click', closeMobileNav);
        }
        
        // Close menu when clicking a link (but not dropdown toggles)
        mobileNav.querySelectorAll('a').forEach(function(link) {
            link.addEventListener('click', function(e) {
                // Don't close if it's a parent link with children
                const parent = this.closest('.has-children');
                if (parent && this.classList.contains('mobile-nav-link')) {
                    // If the parent has children, let the dropdown toggle handle it
                    return;
                }
                closeMobileNav();
            });
        });
        
        // Handle dropdown toggles in mobile menu
        const mobileDropdownToggles = mobileNav.querySelectorAll('.mobile-dropdown-toggle');
        mobileDropdownToggles.forEach(function(toggle) {
            toggle.addEventListener('click', function(e) {
                e.preventDefault();
                const parent = this.closest('li');
                parent.classList.toggle('open');
            });
        });
    }
    
    // ==========================================================================
    // Search Overlay
    // ==========================================================================
    
    const searchToggle = document.getElementById('search-toggle');
    const searchOverlay = document.getElementById('search-overlay');
    const searchClose = document.getElementById('search-close');
    const searchInput = document.getElementById('search-input');
    
    if (searchToggle && searchOverlay) {
        searchToggle.addEventListener('click', function() {
            searchOverlay.classList.add('active');
            if (searchInput) searchInput.focus();
        });
        
        if (searchClose) {
            searchClose.addEventListener('click', function() {
                searchOverlay.classList.remove('active');
            });
        }
        
        // Close on Escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape' && searchOverlay.classList.contains('active')) {
                searchOverlay.classList.remove('active');
            }
        });
        
        // Close when clicking outside
        searchOverlay.addEventListener('click', function(e) {
            if (e.target === searchOverlay) {
                searchOverlay.classList.remove('active');
            }
        });
    }
    
    // ==========================================================================
    // Sticky Header
    // ==========================================================================
    
    const header = document.getElementById('site-header');
    let lastScroll = 0;
    
    if (header) {
        window.addEventListener('scroll', function() {
            const currentScroll = window.pageYOffset;
            
            // Add scrolled class when scrolled past 50px
            if (currentScroll > 50) {
                header.classList.add('scrolled');
            } else {
                header.classList.remove('scrolled');
            }
            
            // Hide header on scroll down, show on scroll up (optional - disabled for now)
            // Uncomment below to enable hide-on-scroll behavior
            /*
            if (currentScroll > lastScroll && currentScroll > 200) {
                header.classList.add('hidden');
            } else {
                header.classList.remove('hidden');
            }
            */
            
            lastScroll = currentScroll;
        });
    }
    
    // ==========================================================================
    // Back to Top Button
    // ==========================================================================
    
    const backToTop = document.getElementById('back-to-top');
    
    if (backToTop) {
        window.addEventListener('scroll', function() {
            if (window.pageYOffset > 300) {
                backToTop.classList.add('visible');
            } else {
                backToTop.classList.remove('visible');
            }
        });
        
        backToTop.addEventListener('click', function() {
            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });
        });
    }
    
    // ==========================================================================
    // Smooth Scroll for Anchor Links
    // ==========================================================================
    
    document.querySelectorAll('a[href^="#"]').forEach(function(anchor) {
        anchor.addEventListener('click', function(e) {
            const href = this.getAttribute('href');
            if (href === '#') return;
            
            e.preventDefault();
            const target = document.querySelector(href);
            
            if (target) {
                const headerHeight = header ? header.offsetHeight : 0;
                const targetPosition = target.getBoundingClientRect().top + window.pageYOffset - headerHeight;
                
                window.scrollTo({
                    top: targetPosition,
                    behavior: 'smooth'
                });
            }
        });
    });
    
    // ==========================================================================
    // Animation on Scroll
    // ==========================================================================
    
    const animateElements = document.querySelectorAll('.research-card, .team-card, .news-card, .partner-card, .lab-card, .benefit-card');
    
    if (animateElements.length > 0 && 'IntersectionObserver' in window) {
        const observerOptions = {
            threshold: 0.1,
            rootMargin: '0px 0px -50px 0px'
        };
        
        const observer = new IntersectionObserver(function(entries) {
            entries.forEach(function(entry) {
                if (entry.isIntersecting) {
                    entry.target.classList.add('animate-slide-up');
                    observer.unobserve(entry.target);
                }
            });
        }, observerOptions);
        
        animateElements.forEach(function(el) {
            el.style.opacity = '0';
            observer.observe(el);
        });
    }
    
    // ==========================================================================
    // Form Validation
    // ==========================================================================
    
    const contactForm = document.getElementById('contact-form');
    
    if (contactForm) {
        contactForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Basic validation
            const requiredFields = this.querySelectorAll('[required]');
            let isValid = true;
            
            requiredFields.forEach(function(field) {
                if (!field.value.trim()) {
                    isValid = false;
                    field.classList.add('error');
                } else {
                    field.classList.remove('error');
                }
            });
            
            // Email validation
            const emailField = this.querySelector('[type="email"]');
            if (emailField && emailField.value) {
                const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                if (!emailPattern.test(emailField.value)) {
                    isValid = false;
                    emailField.classList.add('error');
                }
            }
            
            if (isValid) {
                // In production, this would submit to a backend
                alert('Thank you for your message! We will get back to you soon.');
                this.reset();
            }
        });
        
        // Remove error class on input
        contactForm.querySelectorAll('input, select, textarea').forEach(function(field) {
            field.addEventListener('input', function() {
                this.classList.remove('error');
            });
        });
    }
    
    // ==========================================================================
    // News Filter
    // ==========================================================================
    
    const filterBtns = document.querySelectorAll('.filter-btn');
    const newsCards = document.querySelectorAll('.news-card');
    
    if (filterBtns.length > 0 && newsCards.length > 0) {
        filterBtns.forEach(function(btn) {
            btn.addEventListener('click', function() {
                const filter = this.dataset.filter;
                
                // Update active button
                filterBtns.forEach(function(b) {
                    b.classList.remove('active');
                });
                this.classList.add('active');
                
                // Filter cards
                newsCards.forEach(function(card) {
                    const cardType = card.dataset.type;
                    if (filter === 'all' || cardType === filter) {
                        card.style.display = 'block';
                        card.style.opacity = '1';
                    } else {
                        card.style.display = 'none';
                        card.style.opacity = '0';
                    }
                });
            });
        });
    }
    
    // ==========================================================================
    // Dropdown Hover Enhancement (for touch devices)
    // ==========================================================================
    
    const dropdowns = document.querySelectorAll('.nav-dropdown');
    
    dropdowns.forEach(function(dropdown) {
        const link = dropdown.querySelector('.nav-link');
        
        // Handle click on mobile/touch
        if (link) {
            link.addEventListener('click', function(e) {
                if (window.innerWidth <= 1024) {
                    e.preventDefault();
                    dropdown.classList.toggle('open');
                }
            });
        }
    });
    
    // ==========================================================================
    // Console Welcome Message
    // ==========================================================================
    
    console.log('%cAlfaisalX', 'color: #00d4ff; font-size: 24px; font-weight: bold;');
    console.log('%cCenter for Cognitive Robotics and Autonomous Agents', 'color: #a855f7; font-size: 12px;');
    console.log('%c🤖 Pioneering intelligent systems for tomorrow', 'color: #666; font-size: 11px;');
    
});

// ==========================================================================
// Apply Theme Before Page Load (Prevent Flash)
// ==========================================================================

(function() {
    const savedTheme = localStorage.getItem('alfaisalx-theme') || 'dark';
    document.documentElement.setAttribute('data-theme', savedTheme);
})();





