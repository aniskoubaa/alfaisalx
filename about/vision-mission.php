<?php
/**
 * AlfaisalX - Vision & Mission
 */

require_once '../includes/config.php';

$page_title = 'Vision & Mission';
$page_description = 'Discover the vision and mission of AlfaisalX - global leadership in Robotics, Autonomous Systems, and Agents.';
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
                <span class="section-tag">// Our Purpose</span>
                <h1 class="page-title">Vision & <span class="text-gradient">Mission</span></h1>
                <p class="page-subtitle">
                    Guiding our pursuit of excellence in robotics and AI
                </p>
            </div>
        </section>

        <section class="section">
            <div class="container">
                <!-- About Navigation -->
                <nav class="about-nav">
                    <a href="index.php">Overview</a>
                    <a href="vision-mission.php" class="active">Vision & Mission</a>
                    <a href="objectives.php">Objectives</a>
                    <a href="governance.php">Governance</a>
                    <a href="why-alfaisalx.php">Why AlfaisalX</a>
                </nav>
                
                <div class="page-intro">
                    <p>Our vision and mission define who we are and what we strive to achieve as a leading center for robotics and autonomous systems research.</p>
                </div>
                
                <!-- Vision & Mission Cards -->
                <div class="cards-grid-2">
                    <div class="vm-card vm-card-vision">
                        <div class="vm-icon-wrap">
                            <i class="fas fa-eye"></i>
                        </div>
                        <h2>Our Vision</h2>
                        <blockquote class="vm-quote">
                            "<?php echo VISION; ?>"
                        </blockquote>
                        <p>
                            To be an internationally recognized center in Robotics, Autonomous Systems, and Agentic AI, driving innovation, industrial transformation, and societal impact across the Kingdom and beyond.
                        </p>
                    </div>

                    <div class="vm-card vm-card-mission">
                        <div class="vm-icon-wrap">
                            <i class="fas fa-rocket"></i>
                        </div>
                        <h2>Our Mission</h2>
                        <blockquote class="vm-quote">
                            "<?php echo MISSION; ?>"
                        </blockquote>
                        <p>
                            To advance Cognitive Robotics and Autonomous Agents through cutting-edge research, UAV innovation, and agentic AI workflows, delivering intelligent systems that transform industries, enable automation, and contribute to Saudi Vision 2030 and global progress.
                        </p>
                    </div>
                </div>
                
                <!-- Core Values -->
                <div class="values-section section-spacing">
                    <h2 class="section-title text-center">Our Core Values</h2>
                    <p class="section-subtitle text-center">The principles that guide everything we do</p>
                    
                    <div class="cards-grid-4">
                        <div class="value-card">
                            <i class="fas fa-lightbulb"></i>
                            <h3>Innovation</h3>
                            <p>Pioneering breakthrough technologies in robotics and AI</p>
                        </div>
                        <div class="value-card">
                            <i class="fas fa-shield-alt"></i>
                            <h3>Ethical AI</h3>
                            <p>Responsible development of autonomous systems</p>
                        </div>
                        <div class="value-card">
                            <i class="fas fa-handshake"></i>
                            <h3>Collaboration</h3>
                            <p>Bridging academia, industry, and government</p>
                        </div>
                        <div class="value-card">
                            <i class="fas fa-graduation-cap"></i>
                            <h3>Excellence</h3>
                            <p>World-class research and talent development</p>
                        </div>
                    </div>
                </div>
                
                <!-- Guiding Principles -->
                <div class="section-spacing">
                    <h2 class="section-title text-center">Guiding Principles</h2>
                    
                    <div class="cards-grid-3">
                        <div class="principle-item">
                            <span class="principle-number">1</span>
                            <div class="principle-content">
                                <h4>Research Impact</h4>
                                <p>Focus on high-impact research that addresses real-world challenges and advances the field.</p>
                            </div>
                        </div>
                        <div class="principle-item">
                            <span class="principle-number">2</span>
                            <div class="principle-content">
                                <h4>Industry Relevance</h4>
                                <p>Ensure our research translates to practical applications that benefit industry partners.</p>
                            </div>
                        </div>
                        <div class="principle-item">
                            <span class="principle-number">3</span>
                            <div class="principle-content">
                                <h4>Talent Development</h4>
                                <p>Nurture the next generation of robotics and AI researchers and engineers.</p>
                            </div>
                        </div>
                        <div class="principle-item">
                            <span class="principle-number">4</span>
                            <div class="principle-content">
                                <h4>Global Collaboration</h4>
                                <p>Partner with leading institutions worldwide to advance shared goals.</p>
                            </div>
                        </div>
                        <div class="principle-item">
                            <span class="principle-number">5</span>
                            <div class="principle-content">
                                <h4>Societal Benefit</h4>
                                <p>Develop technologies that improve lives and contribute to national progress.</p>
                            </div>
                        </div>
                        <div class="principle-item">
                            <span class="principle-number">6</span>
                            <div class="principle-content">
                                <h4>Continuous Learning</h4>
                                <p>Stay at the forefront of rapidly evolving technologies and methodologies.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- CTA Section -->
        <section class="section cta-section">
            <div class="container">
                <div class="cta-content">
                    <h2>Share Our Vision?</h2>
                    <p>Partner with us to turn this vision into reality.</p>
                    <div class="cta-buttons">
                        <a href="<?php echo SITE_URL; ?>/partners/become-partner.php" class="btn btn-primary">
                            <i class="fas fa-handshake"></i>
                            Become a Partner
                        </a>
                        <a href="objectives.php" class="btn btn-outline">View Our Objectives</a>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <?php include '../includes/footer.php'; ?>
</body>
</html>





