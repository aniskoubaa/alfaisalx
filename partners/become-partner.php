<?php
/**
 * AlfaisalX - Become a Partner
 * 
 * Partnership opportunities and inquiry form.
 */

require_once '../includes/config.php';

$page_title = 'Become a Partner';
$page_description = 'Partner with AlfaisalX to drive innovation in robotics, autonomous systems, and AI. Explore collaboration opportunities with the Alfaisal Center.';

// Check for success/error messages from form submission
$form_success = isset($_GET['success']) && $_GET['success'] == '1';
$form_error = isset($_GET['error']) && $_GET['error'] == '1';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <?php include '../includes/head.php'; ?>
    <style>
        /* Partnership Types Grid */
        .partnership-types {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: var(--space-6);
            margin-bottom: var(--space-12);
        }
        
        .partnership-card {
            position: relative;
            padding: var(--space-8);
            background: var(--bg-card-solid);
            border: 1px solid var(--border-color);
            border-radius: var(--radius-2xl);
            transition: all 0.4s ease;
            overflow: hidden;
        }
        
        .partnership-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: var(--gradient-btn-primary);
            transform: scaleX(0);
            transform-origin: left;
            transition: transform 0.4s ease;
        }
        
        .partnership-card:hover::before {
            transform: scaleX(1);
        }
        
        .partnership-card:hover {
            transform: translateY(-6px);
            border-color: var(--accent-cyan);
            box-shadow: var(--shadow-glow);
        }
        
        .partnership-icon {
            width: 64px;
            height: 64px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: var(--radius-xl);
            font-size: 24px;
            margin-bottom: var(--space-5);
            transition: all 0.3s ease;
        }
        
        .partnership-card:nth-child(1) .partnership-icon {
            background: rgba(56, 189, 248, 0.12);
            color: #38bdf8;
            border: 1px solid rgba(56, 189, 248, 0.25);
        }
        
        .partnership-card:nth-child(2) .partnership-icon {
            background: rgba(167, 139, 250, 0.12);
            color: #a78bfa;
            border: 1px solid rgba(167, 139, 250, 0.25);
        }
        
        .partnership-card:nth-child(3) .partnership-icon {
            background: rgba(52, 211, 153, 0.12);
            color: #34d399;
            border: 1px solid rgba(52, 211, 153, 0.25);
        }
        
        .partnership-card:nth-child(4) .partnership-icon {
            background: rgba(251, 191, 36, 0.12);
            color: #fbbf24;
            border: 1px solid rgba(251, 191, 36, 0.25);
        }
        
        .partnership-card h3 {
            font-size: var(--text-xl);
            font-weight: 700;
            color: var(--text-primary);
            margin-bottom: var(--space-3);
        }
        
        .partnership-card p {
            font-size: var(--text-sm);
            color: var(--text-secondary);
            line-height: 1.7;
            margin-bottom: var(--space-4);
        }
        
        .partnership-card ul {
            list-style: none;
            padding: 0;
            margin: 0;
        }
        
        .partnership-card ul li {
            display: flex;
            align-items: center;
            gap: var(--space-2);
            font-size: var(--text-sm);
            color: var(--text-muted);
            margin-bottom: var(--space-2);
        }
        
        .partnership-card ul li i {
            color: var(--accent-cyan);
            font-size: var(--text-xs);
        }
        
        /* Benefits Section */
        .benefits-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: var(--space-6);
        }
        
        .benefit-item {
            display: flex;
            gap: var(--space-4);
            padding: var(--space-6);
            background: var(--bg-card-solid);
            border: 1px solid var(--border-color);
            border-radius: var(--radius-xl);
            transition: all 0.3s ease;
        }
        
        .benefit-item:hover {
            border-color: var(--accent-purple);
            transform: translateY(-4px);
        }
        
        .benefit-icon {
            width: 48px;
            height: 48px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: var(--bg-subtle);
            border-radius: var(--radius-lg);
            color: var(--accent-cyan);
            font-size: var(--text-lg);
            flex-shrink: 0;
        }
        
        .benefit-content h4 {
            font-size: var(--text-base);
            font-weight: 600;
            color: var(--text-primary);
            margin-bottom: var(--space-2);
        }
        
        .benefit-content p {
            font-size: var(--text-sm);
            color: var(--text-muted);
            line-height: 1.6;
            margin: 0;
        }
        
        /* Contact Form */
        .partner-form-section {
            background: var(--bg-elevated);
            border-radius: var(--radius-2xl);
            padding: var(--space-12);
            border: 1px solid var(--border-color);
        }
        
        .form-header {
            text-align: center;
            margin-bottom: var(--space-10);
        }
        
        .form-header h2 {
            font-size: var(--text-3xl);
            font-weight: 700;
            margin-bottom: var(--space-3);
        }
        
        .form-header p {
            color: var(--text-secondary);
            max-width: 500px;
            margin: 0 auto;
        }
        
        .partner-form {
            max-width: 700px;
            margin: 0 auto;
        }
        
        .form-row {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: var(--space-6);
            margin-bottom: var(--space-6);
        }
        
        .form-group {
            margin-bottom: var(--space-6);
        }
        
        .form-group label {
            display: block;
            font-size: var(--text-sm);
            font-weight: 600;
            color: var(--text-primary);
            margin-bottom: var(--space-2);
        }
        
        .form-group label span {
            color: #ef4444;
        }
        
        .form-group input,
        .form-group select,
        .form-group textarea {
            width: 100%;
            padding: var(--space-4);
            background: var(--bg-card-solid);
            border: 1px solid var(--border-color);
            border-radius: var(--radius-lg);
            color: var(--text-primary);
            font-size: var(--text-base);
            transition: all 0.3s ease;
        }
        
        .form-group input:focus,
        .form-group select:focus,
        .form-group textarea:focus {
            outline: none;
            border-color: var(--accent-cyan);
            box-shadow: 0 0 0 3px rgba(56, 189, 248, 0.15);
        }
        
        .form-group input::placeholder,
        .form-group textarea::placeholder {
            color: var(--text-muted);
        }
        
        .form-group textarea {
            min-height: 150px;
            resize: vertical;
        }
        
        .form-group select {
            cursor: pointer;
            appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke='%2394a3b8'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M19 9l-7 7-7-7'%3E%3C/path%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 12px center;
            background-size: 20px;
            padding-right: 44px;
        }
        
        .form-submit {
            text-align: center;
            margin-top: var(--space-8);
        }
        
        .form-submit .btn {
            min-width: 200px;
        }
        
        /* Current Partners */
        .current-partners {
            text-align: center;
            padding: var(--space-12) 0;
        }
        
        .current-partners h3 {
            font-size: var(--text-lg);
            color: var(--text-muted);
            margin-bottom: var(--space-8);
            text-transform: uppercase;
            letter-spacing: 0.1em;
        }
        
        @media (max-width: 768px) {
            .form-row {
                grid-template-columns: 1fr;
            }
            
            .partner-form-section {
                padding: var(--space-8);
            }
        }
        
        /* Form Alerts */
        .form-alert {
            display: flex;
            align-items: center;
            gap: var(--space-3);
            padding: var(--space-5);
            border-radius: var(--radius-xl);
            margin-bottom: var(--space-8);
            font-weight: 500;
        }
        
        .form-alert i {
            font-size: var(--text-xl);
        }
        
        .form-alert-success {
            background: rgba(52, 211, 153, 0.15);
            border: 2px solid rgba(52, 211, 153, 0.5);
            color: #34d399;
            box-shadow: 0 4px 20px rgba(52, 211, 153, 0.2);
        }
        
        .form-alert-success strong {
            font-size: 18px;
            display: block;
            margin-bottom: 4px;
        }
        
        .form-alert-error {
            background: rgba(239, 68, 68, 0.15);
            border: 2px solid rgba(239, 68, 68, 0.5);
            color: #ef4444;
            box-shadow: 0 4px 20px rgba(239, 68, 68, 0.2);
        }
        
        .form-alert-error strong {
            font-size: 18px;
            display: block;
            margin-bottom: 4px;
        }
        
        .btn-loading {
            pointer-events: none;
            opacity: 0.7;
        }
        
        .btn-loading i {
            animation: spin 1s linear infinite;
        }
        
        @keyframes spin {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
        }
        
        @keyframes pulse {
            0% { transform: scale(1); }
            50% { transform: scale(1.02); box-shadow: 0 4px 30px rgba(52, 211, 153, 0.4); }
            100% { transform: scale(1); }
        }
    </style>
</head>
<body>
    <?php include '../includes/header.php'; ?>

    <main id="main-content">
        <!-- Page Header -->
        <section class="page-header">
            <div class="container">
                <span class="section-tag">// Collaborate</span>
                <h1 class="page-title">Become a <span class="text-gradient">Partner</span></h1>
                <p class="page-subtitle">
                    Join us in pioneering the future of robotics, autonomous systems, and agentic AI. Together, we can drive innovation and create lasting impact.
                </p>
            </div>
        </section>

        <!-- Partnership Types -->
        <section class="section">
            <div class="container">
                <div class="section-header text-center">
                    <h2 class="section-title">Partnership Opportunities</h2>
                    <p class="section-subtitle">Explore different ways to collaborate with AlfaisalX</p>
                </div>
                
                <div class="partnership-types">
                    <!-- Industry Partner -->
                    <div class="partnership-card">
                        <div class="partnership-icon">
                            <i class="fas fa-industry"></i>
                        </div>
                        <h3>Industry Partner</h3>
                        <p>Collaborate on applied research projects and access cutting-edge robotics and AI solutions.</p>
                        <ul>
                            <li><i class="fas fa-check"></i> Sponsored research projects</li>
                            <li><i class="fas fa-check"></i> Technology transfer opportunities</li>
                            <li><i class="fas fa-check"></i> Access to talent pipeline</li>
                            <li><i class="fas fa-check"></i> Joint product development</li>
                        </ul>
                    </div>
                    
                    <!-- Government Partner -->
                    <div class="partnership-card">
                        <div class="partnership-icon">
                            <i class="fas fa-landmark"></i>
                        </div>
                        <h3>Government Partner</h3>
                        <p>Strategic collaborations aligned with Vision 2030 and national development goals.</p>
                        <ul>
                            <li><i class="fas fa-check"></i> Policy research and advisory</li>
                            <li><i class="fas fa-check"></i> Pilot program implementation</li>
                            <li><i class="fas fa-check"></i> Capacity building programs</li>
                            <li><i class="fas fa-check"></i> National initiative support</li>
                        </ul>
                    </div>
                    
                    <!-- Academic Partner -->
                    <div class="partnership-card">
                        <div class="partnership-icon">
                            <i class="fas fa-graduation-cap"></i>
                        </div>
                        <h3>Academic Partner</h3>
                        <p>Joint research programs, student exchanges, and collaborative publications.</p>
                        <ul>
                            <li><i class="fas fa-check"></i> Joint research initiatives</li>
                            <li><i class="fas fa-check"></i> Faculty & student exchange</li>
                            <li><i class="fas fa-check"></i> Co-authored publications</li>
                            <li><i class="fas fa-check"></i> Shared infrastructure access</li>
                        </ul>
                    </div>
                    
                    <!-- Technology Partner -->
                    <div class="partnership-card">
                        <div class="partnership-icon">
                            <i class="fas fa-microchip"></i>
                        </div>
                        <h3>Technology Partner</h3>
                        <p>Equipment partnerships and joint development of next-generation platforms.</p>
                        <ul>
                            <li><i class="fas fa-check"></i> Hardware/software integration</li>
                            <li><i class="fas fa-check"></i> Platform co-development</li>
                            <li><i class="fas fa-check"></i> Beta testing programs</li>
                            <li><i class="fas fa-check"></i> Technical training support</li>
                        </ul>
                    </div>
                </div>
            </div>
        </section>

        <!-- Benefits Section -->
        <section class="section section-alt">
            <div class="container">
                <div class="section-header text-center">
                    <h2 class="section-title">Why Partner With Us</h2>
                    <p class="section-subtitle">Unlock the potential of strategic collaboration</p>
                </div>
                
                <div class="benefits-grid">
                    <div class="benefit-item">
                        <div class="benefit-icon">
                            <i class="fas fa-flask"></i>
                        </div>
                        <div class="benefit-content">
                            <h4>Cutting-Edge Research</h4>
                            <p>Access world-class research in robotics, UAVs, and AI systems.</p>
                        </div>
                    </div>
                    
                    <div class="benefit-item">
                        <div class="benefit-icon">
                            <i class="fas fa-users"></i>
                        </div>
                        <div class="benefit-content">
                            <h4>Expert Talent</h4>
                            <p>Connect with leading researchers and engineers in the field.</p>
                        </div>
                    </div>
                    
                    <div class="benefit-item">
                        <div class="benefit-icon">
                            <i class="fas fa-rocket"></i>
                        </div>
                        <div class="benefit-content">
                            <h4>Innovation Pipeline</h4>
                            <p>Early access to emerging technologies and innovations.</p>
                        </div>
                    </div>
                    
                    <div class="benefit-item">
                        <div class="benefit-icon">
                            <i class="fas fa-handshake"></i>
                        </div>
                        <div class="benefit-content">
                            <h4>Strategic Network</h4>
                            <p>Join a network of industry leaders and innovators.</p>
                        </div>
                    </div>
                    
                    <div class="benefit-item">
                        <div class="benefit-icon">
                            <i class="fas fa-certificate"></i>
                        </div>
                        <div class="benefit-content">
                            <h4>IP & Commercialization</h4>
                            <p>Pathways for intellectual property and technology licensing.</p>
                        </div>
                    </div>
                    
                    <div class="benefit-item">
                        <div class="benefit-icon">
                            <i class="fas fa-globe-americas"></i>
                        </div>
                        <div class="benefit-content">
                            <h4>Regional Impact</h4>
                            <p>Contribute to Vision 2030 and regional technological advancement.</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Partnership Inquiry Form -->
        <section class="section">
            <div class="container">
                <div class="partner-form-section">
                    <div class="form-header">
                        <h2>Start the <span class="text-gradient">Conversation</span></h2>
                        <p>Tell us about your organization and how we can collaborate. Our partnerships team will respond within 2 business days.</p>
                    </div>
                    
                    <?php if ($form_success): ?>
                    <div class="form-alert form-alert-success" id="formAlert">
                        <i class="fas fa-check-circle"></i>
                        <div>
                            <strong>🎉 Inquiry Submitted Successfully!</strong><br>
                            <span>Thank you for your interest in partnering with AlfaisalX. We have received your message and will respond within 2 business days.</span>
                        </div>
                    </div>
                    <?php endif; ?>
                    
                    <?php if ($form_error): ?>
                    <div class="form-alert form-alert-error" id="formAlert">
                        <i class="fas fa-exclamation-circle"></i>
                        <div>
                            <strong>Submission Error</strong><br>
                            <span>There was an error submitting your inquiry. Please try again or contact us directly at <?php echo CONTACT_EMAIL; ?>.</span>
                        </div>
                    </div>
                    <?php endif; ?>
                    
                    <form class="partner-form" id="partnerForm" action="<?php echo SITE_URL; ?>/api/partner-inquiry.php" method="POST">
                        <div class="form-row">
                            <div class="form-group">
                                <label for="name">Full Name <span>*</span></label>
                                <input type="text" id="name" name="name" placeholder="Your full name" required>
                            </div>
                            <div class="form-group">
                                <label for="email">Email Address <span>*</span></label>
                                <input type="email" id="email" name="email" placeholder="your.email@company.com" required>
                            </div>
                        </div>
                        
                        <div class="form-row">
                            <div class="form-group">
                                <label for="organization">Organization <span>*</span></label>
                                <input type="text" id="organization" name="organization" placeholder="Company or institution name" required>
                            </div>
                            <div class="form-group">
                                <label for="role">Your Role</label>
                                <input type="text" id="role" name="role" placeholder="e.g., CTO, Research Director">
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label for="partnership_type">Partnership Type <span>*</span></label>
                            <select id="partnership_type" name="partnership_type" required>
                                <option value="" disabled selected>Select partnership type</option>
                                <option value="industry">Industry Partner</option>
                                <option value="government">Government Partner</option>
                                <option value="academic">Academic Partner</option>
                                <option value="technology">Technology Partner</option>
                                <option value="other">Other</option>
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label for="areas_of_interest">Areas of Interest</label>
                            <select id="areas_of_interest" name="areas_of_interest">
                                <option value="" disabled selected>Select area of interest</option>
                                <option value="robotics">Robotics & Autonomous Systems</option>
                                <option value="uavs">UAVs & Aerial Autonomy</option>
                                <option value="ai">Agentic AI & Intelligent Workflows</option>
                                <option value="health">Health & Medical Robotics</option>
                                <option value="multiple">Multiple Areas</option>
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label for="message">Tell us about your partnership goals <span>*</span></label>
                            <textarea id="message" name="message" placeholder="Describe your organization's interests, potential collaboration areas, and what you hope to achieve through this partnership..." required></textarea>
                        </div>
                        
                        <div class="form-submit">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="fas fa-paper-plane"></i>
                                Submit Inquiry
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </section>

        <!-- Current Partners (COMMENTED OUT - will enable later)
        <section class="section section-dark">
            <div class="container current-partners">
                <h3>Trusted By Leading Organizations</h3>
                <div class="partners-logos-row">
                    <a href="#" class="partner-logo" title="Bako Motors">
                        <img src="<?php echo ASSETS_URL; ?>/images/partners/bakomotors_logo_rect.png" alt="Bako Motors">
                    </a>
                    <a href="#" class="partner-logo" title="Humain">
                        <img src="<?php echo ASSETS_URL; ?>/images/partners/humain.png" alt="Humain">
                    </a>
                    <a href="#" class="partner-logo" title="PSDSARC">
                        <img src="<?php echo ASSETS_URL; ?>/images/partners/psdsarc-transparent.jpg" alt="PSDSARC">
                    </a>
                    <a href="#" class="partner-logo" title="Tawuniya">
                        <img src="<?php echo ASSETS_URL; ?>/images/partners/tawuniya.jpg" alt="Tawuniya">
                    </a>
                </div>
            </div>
        </section>
        -->

        <!-- CTA Section -->
        <section class="section cta-section">
            <div class="container">
                <div class="cta-content">
                    <h2>Have Questions?</h2>
                    <p>Our partnerships team is ready to discuss how we can work together.</p>
                    <div class="cta-buttons">
                        <a href="mailto:<?php echo CONTACT_EMAIL; ?>" class="btn btn-primary">
                            <i class="fas fa-envelope"></i>
                            Email Us
                        </a>
                        <a href="<?php echo SITE_URL; ?>/contact/" class="btn btn-outline">
                            <i class="fas fa-phone"></i>
                            Contact Page
                        </a>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <?php include '../includes/footer.php'; ?>
    
    <?php if ($form_success || $form_error): ?>
    <script>
        // Scroll to the alert message so user sees it immediately
        document.addEventListener('DOMContentLoaded', function() {
            const alertEl = document.getElementById('formAlert');
            if (alertEl) {
                // Scroll to the alert with some offset for the header
                const yOffset = -120;
                const y = alertEl.getBoundingClientRect().top + window.pageYOffset + yOffset;
                window.scrollTo({top: y, behavior: 'smooth'});
                
                // Add a pulse animation to draw attention
                alertEl.style.animation = 'pulse 0.5s ease-out';
            }
        });
    </script>
    <?php endif; ?>
</body>
</html>
