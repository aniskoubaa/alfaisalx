<?php
/**
 * AlfaisalX - Governance
 * 
 * Organizational structure and leadership.
 */

require_once '../includes/config.php';

$page_title = 'Governance';
$page_description = 'Learn about the governance structure, leadership, and organizational framework of AlfaisalX Center.';
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
                <span class="section-tag">// Organization</span>
                <h1 class="page-title"><span class="text-gradient">Governance</span> Structure</h1>
                <p class="page-subtitle">
                    Leadership and organizational framework
                </p>
            </div>
        </section>

        <section class="section">
            <div class="container">
                <!-- About Navigation -->
                <nav class="about-nav">
                    <a href="index.php">Overview</a>
                    <a href="vision-mission.php">Vision & Mission</a>
                    <a href="objectives.php">Objectives</a>
                    <a href="governance.php" class="active">Governance</a>
                    <a href="why-alfaisalx.php">Why AlfaisalX</a>
                </nav>
                
                <div class="page-intro">
                    <p>AlfaisalX operates under a clear governance framework that ensures strategic alignment with university goals, research excellence, and industry collaboration.</p>
                </div>
                
                <!-- Organizational Structure -->
                <div class="section-spacing">
                    <h2 class="section-title text-center">Leadership Structure</h2>
                    
                    <div class="org-chart">
                        <!-- Top Level -->
                        <div class="org-level">
                            <div class="org-card org-primary">
                                <span class="org-role">Leadership</span>
                                <h3>Center Director</h3>
                                <p>Overall strategic direction, stakeholder relations, and research vision</p>
                            </div>
                        </div>
                        
                        <div class="org-connector"></div>
                        
                        <!-- Second Level -->
                        <div class="org-level">
                            <div class="org-card">
                                <span class="org-role">Deputy</span>
                                <h3>Deputy Director</h3>
                                <p>Operations, partnerships, and coordination with university units</p>
                            </div>
                        </div>
                        
                        <div class="org-connector"></div>
                        
                        <!-- Third Level - Units -->
                        <div class="org-level">
                            <div class="org-card">
                                <span class="org-role">Unit Head</span>
                                <h3>Robotics & UAVs</h3>
                                <p>Autonomous systems and aerial robotics research</p>
                            </div>
                            <div class="org-card">
                                <span class="org-role">Unit Head</span>
                                <h3>Agentic AI</h3>
                                <p>Intelligent workflows and autonomous agents</p>
                            </div>
                            <div class="org-card">
                                <span class="org-role">Unit Head</span>
                                <h3>Medical Robotics</h3>
                                <p>Healthcare automation and precision medicine</p>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Committees -->
                <div class="section-spacing">
                    <h2 class="section-title text-center">Advisory & Committees</h2>
                    
                    <div class="cards-grid-auto">
                        <div class="committee-card">
                            <div class="committee-icon">
                                <i class="fas fa-users"></i>
                            </div>
                            <h3>Executive Advisory Board</h3>
                            <p>Provides strategic guidance and industry connections to ensure alignment with market needs and Vision 2030 goals.</p>
                            <div class="committee-members">
                                <span class="committee-member">Industry Leaders</span>
                                <span class="committee-member">Government Representatives</span>
                            </div>
                        </div>
                        
                        <div class="committee-card">
                            <div class="committee-icon">
                                <i class="fas fa-flask"></i>
                            </div>
                            <h3>Research Committee</h3>
                            <p>Oversees research priorities, publication strategy, and ensures quality and impact of scholarly output.</p>
                            <div class="committee-members">
                                <span class="committee-member">Faculty Researchers</span>
                                <span class="committee-member">External Experts</span>
                            </div>
                        </div>
                        
                        <div class="committee-card">
                            <div class="committee-icon">
                                <i class="fas fa-handshake"></i>
                            </div>
                            <h3>Industry Partnership Council</h3>
                            <p>Manages relationships with corporate partners, technology transfer, and collaborative projects.</p>
                            <div class="committee-members">
                                <span class="committee-member">Partner Companies</span>
                                <span class="committee-member">Center Leadership</span>
                            </div>
                        </div>
                        
                        <div class="committee-card">
                            <div class="committee-icon">
                                <i class="fas fa-shield-alt"></i>
                            </div>
                            <h3>Ethics & Safety Board</h3>
                            <p>Ensures responsible AI development, safety protocols, and ethical guidelines for autonomous systems.</p>
                            <div class="committee-members">
                                <span class="committee-member">Ethics Experts</span>
                                <span class="committee-member">Safety Engineers</span>
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
                    <h2>Meet Our Leadership</h2>
                    <p>Learn more about the experts guiding AlfaisalX.</p>
                    <div class="cta-buttons">
                        <a href="<?php echo SITE_URL; ?>/team/" class="btn btn-primary">
                            <i class="fas fa-users"></i>
                            View Full Team
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
