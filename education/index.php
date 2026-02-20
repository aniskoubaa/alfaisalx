<?php
/**
 * AlfaisalX - Education Overview
 */

require_once '../includes/config.php';

$page_title = 'Education & Training';
$page_description = 'Student engagement, graduate studies, training programs, internships, and hackathons at AlfaisalX.';
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
                <span class="section-tag">// Learning & Development</span>
                <h1 class="page-title">Education & Training</h1>
                <p class="page-subtitle">
                    Cultivating the next generation of robotics and AI innovators.
                </p>
            </div>
        </section>

        <!-- Programs Grid -->
        <section class="section">
            <div class="container">
                <div class="programs-grid">
                    <div class="program-card">
                        <div class="program-icon">
                            <i class="fas fa-flask"></i>
                        </div>
                        <h3>Student Research</h3>
                        <p>Mentoring capstone projects and research aligned with real industry challenges and Vision 2030.</p>
                    </div>

                    <div class="program-card">
                        <div class="program-icon">
                            <i class="fas fa-graduation-cap"></i>
                        </div>
                        <h3>Graduate Studies</h3>
                        <p>Support for master's and PhD research in robotics, AI, and autonomous systems.</p>
                    </div>

                    <div class="program-card">
                        <div class="program-icon">
                            <i class="fas fa-chalkboard-teacher"></i>
                        </div>
                        <h3>Training Programs</h3>
                        <p>Professional workshops and certifications in robotics, ROS2, and agentic AI.</p>
                    </div>

                    <div class="program-card">
                        <div class="program-icon">
                            <i class="fas fa-briefcase"></i>
                        </div>
                        <h3>Internships</h3>
                        <p>Industry internships and real-world deployment opportunities with leading companies.</p>
                    </div>

                    <div class="program-card">
                        <div class="program-icon">
                            <i class="fas fa-trophy"></i>
                        </div>
                        <h3>Hackathons</h3>
                        <p>Annual robotics and AI competitions to build national talent pipelines.</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Student Engagement -->
        <section class="section section-alt">
            <div class="container">
                <h2 class="section-title">Student Engagement Initiatives</h2>
                <div class="engagement-grid">
                    <div class="engagement-item">
                        <i class="fas fa-users"></i>
                        <h4>Mentoring & Supervision</h4>
                        <p>Faculty mentoring on robotics, UAVs, and agentic AI projects that address real-world challenges.</p>
                    </div>
                    <div class="engagement-item">
                        <i class="fas fa-file-alt"></i>
                        <h4>Co-Authorship</h4>
                        <p>Opportunities to co-author papers in top-tier robotics, AI, and autonomous systems venues.</p>
                    </div>
                    <div class="engagement-item">
                        <i class="fas fa-tools"></i>
                        <h4>Prototyping Support</h4>
                        <p>Access to drones, mobile robots, embedded systems, and AI cloud resources.</p>
                    </div>
                    <div class="engagement-item">
                        <i class="fas fa-globe"></i>
                        <h4>International Exchange</h4>
                        <p>Hosting international students for research internships (e.g., French Air Force Academy).</p>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <?php include '../includes/footer.php'; ?>
</body>
</html>





