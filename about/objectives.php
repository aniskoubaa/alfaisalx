<?php
/**
 * AlfaisalX - Objectives
 * 
 * Strategic objectives and goals of the center.
 */

require_once '../includes/config.php';

$page_title = 'Objectives';
$page_description = 'Explore the strategic objectives of AlfaisalX - driving innovation in robotics, UAVs, and autonomous systems aligned with Saudi Vision 2030.';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <?php include '../includes/head.php'; ?>
    <link rel="stylesheet" href="<?php echo ASSETS_URL; ?>/css/about-pages.css">
</head>
<body>
    <?php include '../includes/header.php'; ?>

    <main id="main-content">
        <section class="page-header">
            <div class="container">
                <span class="section-tag">// Strategic Goals</span>
                <h1 class="page-title">Our <span class="text-gradient">Objectives</span></h1>
                <p class="page-subtitle">
                    Measurable goals aligned with Saudi Vision 2030
                </p>
            </div>
        </section>

        <section class="section">
            <div class="container">
                <!-- About Navigation -->
                <nav class="about-nav">
                    <a href="index.php">Overview</a>
                    <a href="vision-mission.php">Vision & Mission</a>
                    <a href="objectives.php" class="active">Objectives</a>
                    <a href="governance.php">Governance</a>
                    <a href="why-alfaisalx.php">Why AlfaisalX</a>
                </nav>
                
                <div class="page-intro">
                    <p>AlfaisalX is committed to achieving measurable impact across research, education, industry collaboration, and societal benefit aligned with Saudi Vision 2030.</p>
                </div>
                
                <div class="cards-grid-auto">
                    <div class="content-card content-card-cyan">
                        <span class="card-number">01</span>
                        <div class="card-icon card-icon-cyan">
                            <i class="fas fa-flask"></i>
                        </div>
                        <h3>Research Excellence</h3>
                        <p>To conduct and publish impactful research in Robotics, UAV Systems, and Agentic AI workflows in high-impact journals and conferences worldwide.</p>
                    </div>
                    
                    <div class="content-card content-card-purple">
                        <span class="card-number">02</span>
                        <div class="card-icon card-icon-purple">
                            <i class="fas fa-graduation-cap"></i>
                        </div>
                        <h3>Talent Development</h3>
                        <p>To attract, develop, and retain world-class faculty and researchers in the Kingdom, building the next generation of robotics and AI experts.</p>
                    </div>
                    
                    <div class="content-card content-card-green">
                        <span class="card-number">03</span>
                        <div class="card-icon card-icon-green">
                            <i class="fas fa-industry"></i>
                        </div>
                        <h3>Industrial Transformation</h3>
                        <p>To partner with government entities and private sector to apply research to real-world industrial challenges and drive digital transformation.</p>
                    </div>
                    
                    <div class="content-card content-card-amber">
                        <span class="card-number">04</span>
                        <div class="card-icon card-icon-amber">
                            <i class="fas fa-lightbulb"></i>
                        </div>
                        <h3>Innovation & IP</h3>
                        <p>To develop and commercialize novel technologies including patents, prototypes, and industry-ready solutions in autonomous systems.</p>
                    </div>
                    
                    <div class="content-card content-card-pink">
                        <span class="card-number">05</span>
                        <div class="card-icon card-icon-pink">
                            <i class="fas fa-globe"></i>
                        </div>
                        <h3>Global Partnerships</h3>
                        <p>To collaborate with leading international laboratories, universities, and technology companies to advance joint innovations.</p>
                    </div>
                    
                    <div class="content-card content-card-indigo">
                        <span class="card-number">06</span>
                        <div class="card-icon card-icon-indigo">
                            <i class="fas fa-flag"></i>
                        </div>
                        <h3>Vision 2030 Alignment</h3>
                        <p>To contribute to Saudi Arabia's national initiatives by enabling automation, AI-driven services, and strategic technology development.</p>
                    </div>
                </div>
                
                <!-- Impact Stats -->
                <div class="section-spacing">
                    <h2 class="section-title text-center">Target Impact</h2>
                    <div class="impact-grid">
                        <div class="impact-item">
                            <div class="impact-value">50+</div>
                            <div class="impact-label">Research Publications (5 Years)</div>
                        </div>
                        <div class="impact-item">
                            <div class="impact-value">10+</div>
                            <div class="impact-label">Industry Partnerships</div>
                        </div>
                        <div class="impact-item">
                            <div class="impact-value">5+</div>
                            <div class="impact-label">Patents Filed</div>
                        </div>
                        <div class="impact-item">
                            <div class="impact-value">20+</div>
                            <div class="impact-label">Student Researchers</div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- CTA Section -->
        <section class="section cta-section">
            <div class="container">
                <div class="cta-content">
                    <h2>Join Our Mission</h2>
                    <p>Partner with us to achieve these objectives and shape the future of intelligent systems.</p>
                    <div class="cta-buttons">
                        <a href="<?php echo SITE_URL; ?>/partners/become-partner.php" class="btn btn-primary">Become a Partner</a>
                        <a href="<?php echo SITE_URL; ?>/contact/" class="btn btn-outline">Contact Us</a>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <?php include '../includes/footer.php'; ?>
</body>
</html>
