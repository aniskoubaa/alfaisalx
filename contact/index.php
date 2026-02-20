<?php
/**
 * AlfaisalX - Contact Page
 */

require_once '../includes/config.php';

$page_title = 'Contact Us';
$page_description = 'Get in touch with AlfaisalX for research collaboration, partnership inquiries, or general information.';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <?php include '../includes/head.php'; ?>
</head>
<body>
    <?php include '../includes/header.php'; ?>

    <main id="main-content">
        <section class="page-header">
            <div class="container">
                <span class="section-tag">// Get In Touch</span>
                <h1 class="page-title">Contact Us</h1>
                <p class="page-subtitle">
                    We'd love to hear from you. Reach out for collaborations, inquiries, or partnership opportunities.
                </p>
            </div>
        </section>

        <section class="section">
            <div class="container">
                <div class="contact-grid">
                    <!-- Contact Info -->
                    <div class="contact-info">
                        <h2>Get In Touch</h2>
                        
                        <div class="contact-item">
                            <div class="contact-icon">
                                <i class="fas fa-envelope"></i>
                            </div>
                            <div class="contact-details">
                                <h4>Email</h4>
                                <a href="mailto:<?php echo CONTACT_EMAIL; ?>"><?php echo CONTACT_EMAIL; ?></a>
                            </div>
                        </div>

                        <div class="contact-item">
                            <div class="contact-icon">
                                <i class="fas fa-phone"></i>
                            </div>
                            <div class="contact-details">
                                <h4>Phone</h4>
                                <a href="tel:<?php echo CONTACT_PHONE; ?>"><?php echo CONTACT_PHONE; ?></a>
                            </div>
                        </div>

                        <div class="contact-item">
                            <div class="contact-icon">
                                <i class="fas fa-map-marker-alt"></i>
                            </div>
                            <div class="contact-details">
                                <h4>Location</h4>
                                <p>Alfaisal University<br>College of Engineering<br>Riyadh, Saudi Arabia</p>
                            </div>
                        </div>

                        <div class="contact-item">
                            <div class="contact-icon">
                                <i class="fas fa-globe"></i>
                            </div>
                            <div class="contact-details">
                                <h4>University Website</h4>
                                <a href="https://www.alfaisal.edu" target="_blank">www.alfaisal.edu</a>
                            </div>
                        </div>

                        <!-- Social Links -->
                        <div class="contact-social">
                            <h4>Follow Us</h4>
                            <div class="social-links">
                                <a href="<?php echo $social_links['linkedin']; ?>" target="_blank" class="social-link">
                                    <i class="fab fa-linkedin-in"></i>
                                </a>
                                <a href="<?php echo $social_links['github']; ?>" target="_blank" class="social-link">
                                    <i class="fab fa-github"></i>
                                </a>
                                <a href="<?php echo $social_links['twitter']; ?>" target="_blank" class="social-link">
                                    <i class="fab fa-twitter"></i>
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Contact Form -->
                    <div class="contact-form-container">
                        <form class="contact-form" action="" method="POST" id="contact-form">
                            <div class="form-row">
                                <div class="form-group">
                                    <label for="name">Full Name *</label>
                                    <input type="text" id="name" name="name" required placeholder="Your name">
                                </div>
                                <div class="form-group">
                                    <label for="email">Email Address *</label>
                                    <input type="email" id="email" name="email" required placeholder="your@email.com">
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="form-group">
                                    <label for="organization">Organization</label>
                                    <input type="text" id="organization" name="organization" placeholder="Your organization">
                                </div>
                                <div class="form-group">
                                    <label for="inquiry-type">Inquiry Type *</label>
                                    <select id="inquiry-type" name="inquiry_type" required>
                                        <option value="">Select inquiry type</option>
                                        <option value="research">Research Collaboration</option>
                                        <option value="partnership">Industry Partnership</option>
                                        <option value="student">Student Opportunities</option>
                                        <option value="careers">Career Opportunities</option>
                                        <option value="media">Media Inquiry</option>
                                        <option value="general">General Information</option>
                                    </select>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="subject">Subject *</label>
                                <input type="text" id="subject" name="subject" required placeholder="Brief subject line">
                            </div>

                            <div class="form-group">
                                <label for="message">Message *</label>
                                <textarea id="message" name="message" required rows="6" placeholder="Tell us about your interest in AlfaisalX..."></textarea>
                            </div>

                            <button type="submit" class="btn btn-primary btn-full">
                                <i class="fas fa-paper-plane"></i>
                                Send Message
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </section>

        <!-- Map Section -->
        <section class="section section-map">
            <div class="map-container">
                <iframe 
                    src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3622.943988876827!2d46.67789!3d24.774!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0x0!2zMjTCsDQ2JzI2LjQiTiA0NsKwNDAnNDAuNCJF!5e0!3m2!1sen!2ssa!4v1234567890"
                    width="100%" 
                    height="400" 
                    style="border:0;" 
                    allowfullscreen="" 
                    loading="lazy" 
                    referrerpolicy="no-referrer-when-downgrade">
                </iframe>
            </div>
        </section>
    </main>

    <?php include '../includes/footer.php'; ?>
</body>
</html>

