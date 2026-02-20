<!-- Site Footer -->
<footer class="site-footer">
    <div class="container">
        <div class="footer-grid">
            <!-- Brand Column -->
            <div class="footer-brand">
                <a href="<?php echo SITE_URL; ?>" class="footer-logo">
                    <img src="<?php echo ASSETS_URL; ?>/images/logo/alfaisal_logo.png" alt="AlfaisalX" class="footer-logo-image">
                    <span class="logo-text">ALFAISALX</span>
                </a>
                <p>
                    <?php echo SITE_FULL_NAME; ?> - Pioneering intelligent systems through the convergence of robotics, UAVs, and agentic AI workflows.
                </p>
                <div class="footer-social">
                    <a href="<?php echo $social_links['linkedin']; ?>" target="_blank" aria-label="LinkedIn">
                        <i class="fab fa-linkedin-in"></i>
                    </a>
                    <a href="<?php echo $social_links['github']; ?>" target="_blank" aria-label="GitHub">
                        <i class="fab fa-github"></i>
                    </a>
                    <a href="<?php echo $social_links['twitter']; ?>" target="_blank" aria-label="Twitter">
                        <i class="fab fa-twitter"></i>
                    </a>
                    <a href="<?php echo $social_links['scholar']; ?>" target="_blank" aria-label="Google Scholar">
                        <i class="fas fa-graduation-cap"></i>
                    </a>
                </div>
            </div>

            <!-- Research Links -->
            <div class="footer-column">
                <h4>Research</h4>
                <ul class="footer-links">
                    <li><a href="<?php echo SITE_URL; ?>/research/">Research Units</a></li>
                    <li><a href="<?php echo SITE_URL; ?>/research/medx.php">MedX Unit</a></li>
                    <li><a href="<?php echo SITE_URL; ?>/publications/">Publications</a></li>
                </ul>
            </div>

            <!-- Center Links -->
            <div class="footer-column">
                <h4>Center</h4>
                <ul class="footer-links">
                    <li><a href="<?php echo SITE_URL; ?>/about/">About Us</a></li>
                    <li><a href="<?php echo SITE_URL; ?>/team/">Team</a></li>
                    <li><a href="<?php echo SITE_URL; ?>/partners/">Partners</a></li>
                    <li><a href="<?php echo SITE_URL; ?>/news/">News & Events</a></li>
                </ul>
            </div>

            <!-- Connect Links -->
            <div class="footer-column">
                <h4>Connect</h4>
                <ul class="footer-links">
                    <li><a href="<?php echo SITE_URL; ?>/contact/">Contact Us</a></li>
                    <li><a href="<?php echo SITE_URL; ?>/careers/">Careers</a></li>
                    <li><a href="<?php echo SITE_URL; ?>/partners/become-partner.php">Become a Partner</a></li>
                    <li><a href="mailto:<?php echo CONTACT_EMAIL; ?>"><?php echo CONTACT_EMAIL; ?></a></li>
                </ul>
            </div>
        </div>

        <!-- Footer Bottom -->
        <div class="footer-bottom">
            <div class="footer-bottom-content">
                <p>&copy; <?php echo date('Y'); ?> AlfaisalX - Alfaisal University. All rights reserved.</p>
                <div class="footer-bottom-links">
                    <a href="https://www.alfaisal.edu" target="_blank">Alfaisal University</a>
                </div>
            </div>
        </div>
    </div>
</footer>

<!-- Back to Top Button -->
<button id="back-to-top" class="btn-header" aria-label="Back to top" style="position: fixed; bottom: var(--space-6); right: var(--space-6); opacity: 0; visibility: hidden; transition: all var(--transition-base); z-index: 100;">
    <i class="fas fa-arrow-up"></i>
</button>

<style>
#back-to-top.visible {
    opacity: 1;
    visibility: visible;
}
</style>

<!-- Main JavaScript -->
<script src="<?php echo ASSETS_URL; ?>/js/main.js"></script>

<!-- Coming Soon Modal (Global) -->
<div id="comingSoonModal" class="coming-soon-overlay" onclick="closeComingSoon(event)">
    <div class="coming-soon-modal">
        <button class="coming-soon-close" onclick="closeComingSoon(event)">&times;</button>
        <div class="coming-soon-icon">
            <i class="fas fa-rocket"></i>
        </div>
        <h3>Coming Soon!</h3>
        <p id="comingSoonText">This feature is currently under development.</p>
        <p class="coming-soon-subtext">We're working hard to bring you this content. Check back soon!</p>
        <button class="btn btn-primary" onclick="closeComingSoon(event)">Got it!</button>
    </div>
</div>

<style>
/* Coming Soon Modal */
.coming-soon-overlay {
    display: none;
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(0, 0, 0, 0.8);
    backdrop-filter: blur(8px);
    z-index: 10000;
    align-items: center;
    justify-content: center;
    padding: 20px;
}

.coming-soon-overlay.show {
    display: flex;
}

.coming-soon-modal {
    background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%);
    border: 1px solid #334155;
    border-radius: 24px;
    padding: 48px;
    max-width: 420px;
    width: 100%;
    text-align: center;
    position: relative;
    box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
}

.coming-soon-close {
    position: absolute;
    top: 16px;
    right: 16px;
    width: 36px;
    height: 36px;
    border: none;
    background: rgba(255, 255, 255, 0.1);
    color: #94a3b8;
    font-size: 24px;
    border-radius: 50%;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
}

.coming-soon-close:hover {
    background: rgba(239, 68, 68, 0.2);
    color: #ef4444;
}

.coming-soon-icon {
    width: 80px;
    height: 80px;
    background: linear-gradient(135deg, #0ea5e9, #8b5cf6);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 24px;
    font-size: 32px;
    color: white;
}

.coming-soon-modal h3 {
    font-size: 28px;
    font-weight: 700;
    color: #ffffff;
    margin-bottom: 12px;
}

.coming-soon-modal p {
    color: #94a3b8;
    margin-bottom: 8px;
    font-size: 16px;
}

.coming-soon-subtext {
    font-size: 14px !important;
    color: #64748b !important;
    margin-bottom: 24px !important;
}

.coming-soon-modal .btn {
    margin-top: 16px;
    min-width: 140px;
}
</style>

<script>
function showComingSoon(title) {
    const modal = document.getElementById('comingSoonModal');
    const text = document.getElementById('comingSoonText');
    if (modal && text) {
        text.innerHTML = '<strong>' + title + '</strong> is currently under development.';
        modal.classList.add('show');
        document.body.style.overflow = 'hidden';
    }
}

function closeComingSoon(event) {
    if (event) {
        event.stopPropagation();
        if (event.target.id === 'comingSoonModal' || 
            event.target.classList.contains('coming-soon-close') ||
            event.target.classList.contains('btn')) {
            const modal = document.getElementById('comingSoonModal');
            if (modal) {
                modal.classList.remove('show');
                document.body.style.overflow = '';
            }
        }
    }
}

document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        const modal = document.getElementById('comingSoonModal');
        if (modal) {
            modal.classList.remove('show');
            document.body.style.overflow = '';
        }
    }
});
</script>





