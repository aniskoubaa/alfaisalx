<?php
/**
 * AlfaisalX - About Overview
 * 
 * Main about page providing an overview of the center.
 */

require_once '../includes/config.php';

$page_title = 'About';
$page_description = 'Learn about AlfaisalX - the Alfaisal Center for Cognitive Robotics and Autonomous Agents, driving digital transformation through robotics, UAVs, and agentic AI.';
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
        <!-- Page Header -->
        <section class="page-header">
            <div class="container">
                <span class="section-tag">// Who We Are</span>
                <h1 class="page-title">About <span class="text-gradient">AlfaisalX</span></h1>
                <p class="page-subtitle">
                    Center for Cognitive Robotics and Autonomous Agents
                </p>
            </div>
        </section>

        <!-- Overview Section -->
        <section class="section">
            <div class="container">
                <!-- About Navigation -->
                <nav class="about-nav">
                    <a href="index.php" class="active">Overview</a>
                    <a href="vision-mission.php">Vision & Mission</a>
                    <a href="objectives.php">Objectives</a>
                    <a href="governance.php">Governance</a>
                    <a href="why-alfaisalx.php">Why AlfaisalX</a>
                </nav>
                
                <div class="about-content-grid">
                    <div class="about-main-text">
                        <h2>Intelligent Systems for a <span class="text-gradient">Better Tomorrow</span></h2>
                        <p class="lead">
                            The Cognitive Robotics and Autonomous Agents Center (AlfaisalX) is established to drive next-generation digital transformation through the convergence of robotics, unmanned aerial vehicles (UAVs), and agentic AI workflows.
                        </p>
                        <p>
                            By integrating autonomous robotic platforms with intelligent, goal-driven AI agents, the center develops adaptive systems that can perceive, decide, and act across complex environments. This synergy enables breakthrough applications in logistics automation, smart manufacturing, precision healthcare, aerial monitoring, and business process transformation.
                        </p>
                        <p>
                            Based at Alfaisal University's College of Engineering, we are committed to ethical innovation and advancing the frontiers of autonomous systems research while contributing to Saudi Arabia's technological leadership.
                        </p>

                        <div class="x-explanation-box">
                            <h3>The "<span class="x-letter">X</span>" in AlfaisalX</h3>
                            <p>Alfaisal is the name of the University, and <strong>X</strong> represents Digital Transformation with Generative AI and Robotics — symbolizing our commitment to pioneering the future of intelligent systems.</p>
                        </div>
                    </div>

                    <aside class="quick-facts-sidebar">
                        <h4><i class="fas fa-bolt"></i> Quick Facts</h4>
                        <ul class="facts-list">
                            <li>
                                <span class="fact-label">Established</span>
                                <span class="fact-value">2025</span>
                            </li>
                            <li>
                                <span class="fact-label">Location</span>
                                <span class="fact-value">Alfaisal University, Riyadh</span>
                            </li>
                            <li>
                                <span class="fact-label">Focus</span>
                                <span class="fact-value">Robotics, UAVs, Agentic AI</span>
                            </li>
                            <li>
                                <span class="fact-label">Alignment</span>
                                <span class="fact-value">Saudi Vision 2030</span>
                            </li>
                        </ul>

                        <div class="explore-links">
                            <h5>Explore More</h5>
                            <ul>
                                <li><a href="vision-mission.php"><i class="fas fa-arrow-right"></i> Vision & Mission</a></li>
                                <li><a href="objectives.php"><i class="fas fa-arrow-right"></i> Our Objectives</a></li>
                                <li><a href="governance.php"><i class="fas fa-arrow-right"></i> Governance</a></li>
                                <li><a href="why-alfaisalx.php"><i class="fas fa-arrow-right"></i> Why AlfaisalX</a></li>
                            </ul>
                        </div>
                    </aside>
                </div>
            </div>
        </section>

        <!-- Strategic Alignment -->
        <section class="section section-alt">
            <div class="container">
                <h2 class="section-title text-center">Strategic Alignment</h2>
                <p class="section-subtitle text-center">Positioning for national and global impact</p>
                
                <div class="cards-grid-3">
                    <div class="alignment-card">
                        <div class="alignment-icon">
                            <i class="fas fa-university"></i>
                        </div>
                        <h3>Alfaisal University</h3>
                        <p>Supporting the university's aim to provide student-centric, world-class education, research, and innovation that contribute to serving society and achieving sustainable development.</p>
                    </div>
                    <div class="alignment-card">
                        <div class="alignment-icon">
                            <i class="fas fa-landmark"></i>
                        </div>
                        <h3>Saudi Vision 2030</h3>
                        <p>Contributing to the Kingdom's technological leadership and economic diversification through robotics, AI, and autonomous systems innovation.</p>
                    </div>
                    <div class="alignment-card">
                        <div class="alignment-icon">
                            <i class="fas fa-globe"></i>
                        </div>
                        <h3>Global Impact</h3>
                        <p>Positioning Saudi Arabia as a global leader in robotics and AI through world-class publications, patents, and international collaborations.</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- CTA Section -->
        <section class="section cta-section">
            <div class="container">
                <div class="cta-content">
                    <h2>Partner With AlfaisalX</h2>
                    <p>Join us in pioneering the future of intelligent systems.</p>
                    <div class="cta-buttons">
                        <a href="<?php echo SITE_URL; ?>/partners/become-partner.php" class="btn btn-primary">
                            <i class="fas fa-handshake"></i>
                            Become a Partner
                        </a>
                        <a href="<?php echo SITE_URL; ?>/contact/" class="btn btn-outline">Contact Us</a>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <?php include '../includes/footer.php'; ?>
</body>
</html>





