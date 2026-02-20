<?php
/**
 * AlfaisalX - Homepage
 * 
 * Center for Cognitive Robotics and Autonomous Agents
 */

require_once 'includes/config.php';

$page_title = null;
$page_description = get_setting('intro_text');

// Get featured news
$news_items = get_db()->getNews(3);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <?php include 'includes/head.php'; ?>
</head>
<body>
    <?php include 'includes/header.php'; ?>

    <main id="main-content">
    <!-- Hero Section -->
    <section class="hero">
            <div class="container">
                <div class="hero-container">
        <div class="hero-content">
                        <div class="hero-badge">
                            <span class="pulse"></span>
                Center • Alfaisal University
            </div>
            <h1>
                Pioneering <span class="highlight">Cognitive Robotics</span> & <span class="highlight">Autonomous Agents</span>
            </h1>
            <p class="hero-subtitle">
                            Driving global innovation through the convergence of robotics, UAVs, and agentic AI workflows. We bridge academic research and industrial transformation aligned with Saudi Vision 2030.
            </p>
            <div class="hero-buttons">
                            <a href="<?php echo SITE_URL; ?>/research/" class="btn btn-primary">
                                <i class="fas fa-flask"></i>
                    Explore Research
                </a>
                            <a href="<?php echo SITE_URL; ?>/partners/become-partner.php" class="btn btn-outline">
                                <i class="fas fa-handshake"></i>
                    Partner With Us
                </a>
            </div>
        </div>
                    <div class="hero-visual">
                        <div class="hero-logo-container">
                            <img src="<?php echo ASSETS_URL; ?>/images/logo/alfaisal_logo.png" alt="AlfaisalX Logo" class="hero-logo">
            </div>
            </div>
            </div>
            </div>
        </section>

        <!-- Stats Section -->
        <section class="stats-section">
            <div class="container">
                <div class="stats-grid">
                    <?php foreach ($stats as $stat): ?>
            <div class="stat-item">
                        <div class="stat-value"><?php echo htmlspecialchars($stat['value']); ?></div>
                        <div class="stat-label"><?php echo htmlspecialchars($stat['label']); ?></div>
                    </div>
                    <?php endforeach; ?>
            </div>
        </div>
    </section>

        <!-- About Preview -->
        <section class="section section-dark about-preview" id="about">
            <div class="container">
                <!-- Decorative background elements -->
                <div class="about-bg-decor">
                    <div class="about-glow about-glow-1"></div>
                    <div class="about-glow about-glow-2"></div>
                </div>
                
                <div class="section-header text-center">
                    <span class="section-tag">// Who We Are</span>
                    <h2 class="section-title">Intelligent Systems for <span class="text-gradient">Tomorrow</span></h2>
                    <p class="section-subtitle">Pioneering the future of intelligent automation in the Kingdom</p>
                </div>
                
                <div class="about-content-wrapper">
                    <!-- Main Content Column -->
                    <div class="about-main-content">
                        <div class="about-text-block">
                            <p class="lead">
                                <?php echo get_setting('intro_text'); ?>
                            </p>
                            <p class="about-body-text">
                                <?php echo get_setting('intro_text_2'); ?>
                            </p>
                    </div>
                        
                        <!-- Vision & Mission Cards -->
                        <div class="vision-mission-grid">
                            <div class="vm-card vm-card-vision">
                                <div class="vm-card-glow"></div>
                                <div class="vm-icon">
                                    <i class="fas fa-eye"></i>
                                </div>
                                <div class="vm-content">
                        <h4>Our Vision</h4>
                                    <p><?php echo VISION; ?></p>
                    </div>
                                <div class="vm-accent"></div>
                </div>
                            
                            <div class="vm-card vm-card-mission">
                                <div class="vm-card-glow"></div>
                                <div class="vm-icon">
                                    <i class="fas fa-rocket"></i>
            </div>
                                <div class="vm-content">
                                    <h4>Our Mission</h4>
                                    <p><?php echo MISSION; ?></p>
                    </div>
                                <div class="vm-accent"></div>
                    </div>
                    </div>
                        
                        <div class="about-cta">
                            <a href="<?php echo SITE_URL; ?>/about/" class="btn btn-primary btn-glow">
                                <i class="fas fa-arrow-right"></i>
                                Learn More About Us
                            </a>
                            <a href="<?php echo SITE_URL; ?>/team/" class="btn btn-outline">
                                <i class="fas fa-users"></i>
                                Meet Our Team
                            </a>
                    </div>
                    </div>
                    
                    <!-- Sidebar Quick Facts -->
                    <aside class="about-sidebar">
                        <div class="quick-facts-card">
                            <div class="qf-header">
                                <div class="qf-icon">
                                    <i class="fas fa-bolt"></i>
                    </div>
                                <h4>Quick Facts</h4>
                    </div>
                            <ul class="qf-list">
                                <li>
                                    <div class="qf-item-icon">
                                        <i class="fas fa-calendar-alt"></i>
                </div>
                                    <div class="qf-item-content">
                                        <span class="qf-label">Established</span>
                                        <span class="qf-value">2025</span>
                                    </div>
                                </li>
                                <li>
                                    <div class="qf-item-icon">
                                        <i class="fas fa-map-marker-alt"></i>
                                    </div>
                                    <div class="qf-item-content">
                                        <span class="qf-label">Location</span>
                                        <span class="qf-value">Alfaisal University, Riyadh</span>
                                    </div>
                                </li>
                                <li>
                                    <div class="qf-item-icon">
                                        <i class="fas fa-crosshairs"></i>
                                    </div>
                                    <div class="qf-item-content">
                                        <span class="qf-label">Focus Areas</span>
                                        <span class="qf-value">Robotics, UAVs, Agentic AI</span>
                                    </div>
                                </li>
                                <li>
                                    <div class="qf-item-icon">
                                        <i class="fas fa-flag"></i>
                                    </div>
                                    <div class="qf-item-content">
                                        <span class="qf-label">Alignment</span>
                                        <span class="qf-value">Saudi Vision 2030</span>
                                    </div>
                                </li>
                            </ul>
                            <div class="qf-footer">
                                <div class="qf-badge">
                                    <span class="qf-badge-dot"></span>
                                    Center
                                </div>
                            </div>
                        </div>
                    </aside>
            </div>
        </div>
    </section>

        <!-- Research Units - Clean Landing Page Design -->
        <section class="section section-gradient research-section" id="research">
            <div class="container">
                <div class="section-header text-center">
                    <span class="section-tag">// Research Units</span>
                    <h2 class="section-title">Research <span class="text-gradient">&</span> Innovation</h2>
                    <p class="section-subtitle">Four specialized units driving the future of intelligent systems</p>
        </div>
                
                <!-- Bento Grid Layout - 4 Units -->
                <div class="research-bento">
                    <?php 
                    // Color scheme for each unit
                    $unit_colors = [
                        'robotics-autonomous-systems' => 'cyan',
                        'agentic-ai-workflows' => 'purple',
                        'medx' => 'red',
                        'commercialization-impact' => 'amber'
                    ];
                    $i = 0;
                    foreach ($research_areas as $area): 
                        $color = $unit_colors[$area['slug']] ?? 'cyan';
                        // MedX has its own page, others show coming soon for now
                        $has_page = ($area['slug'] === 'medx');
                        $link_url = $has_page ? SITE_URL . '/research/' . $area['slug'] . '.php' : '#';
                        $link_click = $has_page ? '' : "onclick=\"showComingSoon('" . addslashes(htmlspecialchars($area['title'])) . "'); return false;\"";
                    ?>
                    <a href="<?php echo $link_url; ?>" <?php echo $link_click; ?>
                       class="bento-card bento-card-<?php echo $color; ?> <?php echo $i === 0 ? 'bento-featured' : ''; ?>">
                        <div class="bento-glow"></div>
                        <div class="bento-number"><?php echo htmlspecialchars($area['number']); ?></div>
                        <div class="bento-icon">
                            <i class="fas fa-<?php echo htmlspecialchars($area['icon']); ?>"></i>
                </div>
                        <div class="bento-content">
                            <h3><?php echo htmlspecialchars($area['title']); ?></h3>
                            <p><?php echo htmlspecialchars(substr($area['description'], 0, 100)); ?>...</p>
            </div>
                        <div class="bento-arrow">
                            <i class="fas fa-arrow-right"></i>
                        </div>
                        <?php if ($area['slug'] === 'medx'): ?>
                        <span class="bento-badge">NEW</span>
                        <?php endif; ?>
                    </a>
                    <?php $i++; endforeach; ?>
            </div>
                
                <!-- View All Research Units CTA -->
                <div class="research-cta text-center">
                    <a href="<?php echo SITE_URL; ?>/research/" class="btn btn-outline btn-lg">
                        <i class="fas fa-flask"></i>
                        Explore All Research Units
                    </a>
            </div>
        </div>
    </section>

        <!-- Team Preview -->
        <section class="section section-dark" id="team">
            <div class="container">
                <div class="section-header text-center">
                    <span class="section-tag">// Our People</span>
                    <h2 class="section-title">Meet the Team</h2>
                    <p class="section-subtitle">World-class researchers and engineers driving innovation in robotics and AI.</p>
        </div>
                <div class="team-grid leadership-grid">
                    <?php foreach ($team_members['leadership'] as $member): ?>
                    <div class="team-card team-card-featured">
                        <div class="team-avatar">
                            <?php if (!empty($member['image'])): ?>
                                <img src="<?php echo ASSETS_URL; ?>/images/team/<?php echo $member['image']; ?>" alt="<?php echo htmlspecialchars($member['name']); ?>">
                            <?php else: ?>
                                <span class="avatar-initials"><?php echo htmlspecialchars($member['initials']); ?></span>
                            <?php endif; ?>
            </div>
                        <div class="team-info">
                            <h3><?php echo htmlspecialchars($member['name']); ?></h3>
                            <span class="team-role"><?php echo htmlspecialchars($member['role']); ?></span>
                            <span class="team-title"><?php echo htmlspecialchars($member['title']); ?></span>
                            <p class="team-bio"><?php echo htmlspecialchars(substr($member['bio'], 0, 180)); ?>...</p>
                            <div class="team-expertise">
                                <?php foreach (array_slice($member['expertise'], 0, 4) as $exp): ?>
                                <span class="expertise-tag"><?php echo htmlspecialchars($exp); ?></span>
                                <?php endforeach; ?>
            </div>
                            <div class="team-social">
                                <?php if (!empty($member['email'])): ?>
                                <a href="mailto:<?php echo htmlspecialchars($member['email']); ?>" class="social-link" title="Email">
                                    <i class="fas fa-envelope"></i>
                                </a>
                                <?php endif; ?>
                                <?php if (!empty($member['linkedin'])): ?>
                                <a href="<?php echo htmlspecialchars($member['linkedin']); ?>" target="_blank" class="social-link" title="LinkedIn">
                                    <i class="fab fa-linkedin"></i>
                                </a>
                                <?php endif; ?>
                                <?php if (!empty($member['google_scholar'])): ?>
                                <a href="<?php echo htmlspecialchars($member['google_scholar']); ?>" target="_blank" class="social-link" title="Google Scholar">
                                    <i class="fas fa-graduation-cap"></i>
                                </a>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
                <div class="team-grid mt-8">
                    <?php foreach ($team_members['core_faculty'] as $member): ?>
            <div class="team-card">
                        <div class="team-avatar">
                            <?php if (!empty($member['image'])): ?>
                                <img src="<?php echo ASSETS_URL; ?>/images/team/<?php echo $member['image']; ?>" alt="<?php echo htmlspecialchars($member['name']); ?>">
                            <?php else: ?>
                                <span class="avatar-initials"><?php echo htmlspecialchars($member['initials']); ?></span>
                            <?php endif; ?>
            </div>
                        <div class="team-info">
                            <h3><?php echo htmlspecialchars($member['name']); ?></h3>
                            <span class="team-role"><?php echo htmlspecialchars($member['role']); ?></span>
                            <p class="team-bio"><?php echo htmlspecialchars($member['bio']); ?></p>
                            <div class="team-expertise">
                                <?php foreach (array_slice($member['expertise'], 0, 3) as $exp): ?>
                                <span class="expertise-tag"><?php echo htmlspecialchars($exp); ?></span>
                                <?php endforeach; ?>
                            </div>
                            <div class="team-social">
                                <?php if (!empty($member['email'])): ?>
                                <a href="mailto:<?php echo htmlspecialchars($member['email']); ?>" class="social-link" title="Email">
                                    <i class="fas fa-envelope"></i>
                                </a>
                                <?php endif; ?>
                                <?php if (!empty($member['linkedin'])): ?>
                                <a href="<?php echo htmlspecialchars($member['linkedin']); ?>" target="_blank" class="social-link" title="LinkedIn">
                                    <i class="fab fa-linkedin"></i>
                                </a>
                                <?php endif; ?>
                                <?php if (!empty($member['google_scholar'])): ?>
                                <a href="<?php echo htmlspecialchars($member['google_scholar']); ?>" target="_blank" class="social-link" title="Google Scholar">
                                    <i class="fas fa-graduation-cap"></i>
                                </a>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
                
                <?php if (!empty($team_members['adjunct'])): ?>
                <!-- External Adjunct Members -->
                <h3 class="subsection-title mt-12">External Adjunct Members</h3>
                <div class="team-grid mt-8">
                    <?php foreach ($team_members['adjunct'] as $member): ?>
            <div class="team-card">
                        <div class="team-avatar">
                            <?php if (!empty($member['image'])): ?>
                                <img src="<?php echo ASSETS_URL; ?>/images/team/<?php echo $member['image']; ?>" alt="<?php echo htmlspecialchars($member['name']); ?>">
                            <?php else: ?>
                                <span class="avatar-initials"><?php echo htmlspecialchars($member['initials']); ?></span>
                            <?php endif; ?>
                        </div>
                        <div class="team-info">
                            <h3><?php echo htmlspecialchars($member['name']); ?></h3>
                            <span class="team-role"><?php echo htmlspecialchars($member['title']); ?></span>
                            <p class="team-bio"><?php echo htmlspecialchars($member['bio']); ?></p>
                            <div class="team-expertise">
                                <?php foreach (array_slice($member['expertise'], 0, 3) as $exp): ?>
                                <span class="expertise-tag"><?php echo htmlspecialchars($exp); ?></span>
                                <?php endforeach; ?>
                            </div>
                            <div class="team-social">
                                <?php if (!empty($member['email'])): ?>
                                <a href="mailto:<?php echo htmlspecialchars($member['email']); ?>" class="social-link" title="Email">
                                    <i class="fas fa-envelope"></i>
                                </a>
                                <?php endif; ?>
                                <?php if (!empty($member['google_scholar'])): ?>
                                <a href="<?php echo htmlspecialchars($member['google_scholar']); ?>" target="_blank" class="social-link" title="Google Scholar">
                                    <i class="fas fa-graduation-cap"></i>
                                </a>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
                <?php endif; ?>
                
                <div class="text-center mt-8">
                    <a href="<?php echo SITE_URL; ?>/team/" class="btn btn-outline">View Full Team</a>
            </div>
        </div>
    </section>

        <!-- Partners Section - Clean Logo Strip (COMMENTED OUT - will enable later)
        <section class="partners-strip">
            <div class="container">
                <div class="partners-strip-header">
                    <span>Trusted by Industry Leaders</span>
        </div>
                <div class="partners-logos-row">
                    <a href="https://bakomotors.com" target="_blank" class="partner-logo" title="Bako Motors - Electric Vehicle Partner">
                        <img src="<?php echo ASSETS_URL; ?>/images/partners/bakomotors_logo_rect.png" alt="Bako Motors">
                    </a>
                    <a href="https://humain.com" target="_blank" class="partner-logo" title="Humain - AI & Technology Partner">
                        <img src="<?php echo ASSETS_URL; ?>/images/partners/humain.png" alt="Humain">
                    </a>
                    <a href="#" class="partner-logo" title="Prince Sultan Defense Studies & Research Center">
                        <img src="<?php echo ASSETS_URL; ?>/images/partners/psdsarc-transparent.jpg" alt="PSDSARC">
                    </a>
                    <a href="https://tawuniya.com" target="_blank" class="partner-logo" title="Tawuniya - Insurance Industry Partner">
                        <img src="<?php echo ASSETS_URL; ?>/images/partners/tawuniya.jpg" alt="Tawuniya">
                    </a>
                    </div>
                </div>
        </section>
        -->

        <!-- News & Events Section -->
        <section class="section section-dark news-section" id="news">
            <div class="container">
                <div class="section-header text-center">
                    <span class="section-tag">// Latest Updates</span>
                    <h2 class="section-title">News <span class="text-gradient">&</span> Events</h2>
                    <p class="section-subtitle">Stay informed about our latest developments and upcoming events</p>
            </div>
                
                <div class="news-cards-grid">
                    <!-- Featured Event: SmartTech 2025 -->
                    <article class="news-card news-card-featured">
                        <div class="news-card-badge news-badge-event">
                            <i class="fas fa-calendar-star"></i>
                            Featured Event
                    </div>
                        <div class="news-card-header">
                            <div class="news-date-block">
                                <span class="news-day">25-27</span>
                                <span class="news-month">Dec</span>
                                <span class="news-year">2025</span>
                </div>
                            <div class="news-icon news-icon-event">
                                <i class="fas fa-globe"></i>
            </div>
                    </div>
                        <div class="news-card-body">
                            <h3>SmartTech 2025 International Conference</h3>
                            <p>A global platform advancing AI, Quantum Computing, Cybersecurity, Autonomous Systems, and Smart Cities. Co-organized by MUST University and ALECSO.</p>
                            <div class="news-location">
                                <i class="fas fa-map-marker-alt"></i>
                                <span>Tunis, Tunisia</span>
                </div>
            </div>
                        <div class="news-card-footer">
                            <a href="https://smarttech-conference.org/" target="_blank" class="btn btn-primary btn-sm">
                                Learn More <i class="fas fa-external-link-alt"></i>
                            </a>
        </div>
                    </article>

                    <!-- Recruitment -->
                    <article class="news-card news-card-recruitment">
                        <div class="news-card-badge news-badge-recruitment">
                            <i class="fas fa-user-plus"></i>
                            Recruitment
                </div>
                        <div class="news-card-header">
                            <div class="news-date-block">
                                <span class="news-day">17</span>
                                <span class="news-month">Dec</span>
                                <span class="news-year">2025</span>
                    </div>
                            <div class="news-icon news-icon-recruitment">
                                <i class="fas fa-users"></i>
                </div>
                    </div>
                        <div class="news-card-body">
                            <h3>Global Faculty Recruitment Drive</h3>
                            <p>Seeking world-class researchers in robotics, UAV systems, and agentic AI to join our growing team at AlfaisalX.</p>
                </div>
                        <div class="news-card-footer">
                            <a href="<?php echo SITE_URL; ?>/careers/" class="btn btn-outline btn-sm">
                                Apply Now <i class="fas fa-arrow-right"></i>
                            </a>
                    </div>
                    </article>

                    <!-- Announcement -->
                    <article class="news-card news-card-announcement">
                        <div class="news-card-badge news-badge-announcement">
                            <i class="fas fa-bullhorn"></i>
                            Announcement
                </div>
                        <div class="news-card-header">
                            <div class="news-date-block">
                                <span class="news-day">16</span>
                                <span class="news-month">Dec</span>
                                <span class="news-year">2025</span>
            </div>
                            <div class="news-icon news-icon-announcement">
                                <i class="fas fa-building"></i>
                    </div>
                    </div>
                        <div class="news-card-body">
                            <h3>AlfaisalX Center Official Launch</h3>
                            <p>Marking a new era in AI and robotics research with the opening of our state-of-the-art facility at Alfaisal University.</p>
                </div>
                        <div class="news-card-footer">
                            <a href="#" onclick="showComingSoon('AlfaisalX Center Launch Details'); return false;" class="btn btn-outline btn-sm">
                                Read More <i class="fas fa-arrow-right"></i>
                            </a>
                </div>
                    </article>
                </div>
                
                <div class="text-center mt-12">
                    <a href="<?php echo SITE_URL; ?>/news/" class="btn btn-outline btn-lg">
                        <i class="fas fa-newspaper"></i>
                        View All News & Events
                    </a>
            </div>
        </div>
    </section>

        <!-- CTA Section -->
        <section class="cta-section" id="contact">
            <div class="container">
                <div class="cta-content">
                    <h2>Ready to <span class="text-gradient">Innovate</span> Together?</h2>
                    <p>Partner with AlfaisalX to shape the future of robotics and autonomous systems in Saudi Arabia.</p>
                    <div class="cta-buttons">
                        <a href="<?php echo SITE_URL; ?>/partners/become-partner.php" class="btn btn-primary">
                            <i class="fas fa-handshake"></i>
                            Become a Partner
                        </a>
                        <a href="<?php echo SITE_URL; ?>/contact/" class="btn btn-outline">
                            <i class="fas fa-envelope"></i>
                            Contact Us
                        </a>
                </div>
            </div>
            </div>
        </section>
    </main>

    <!-- Coming Soon Modal -->
    <div id="comingSoonModal" class="modal-overlay" onclick="closeComingSoon(event)">
        <div class="modal-content">
            <button class="modal-close" onclick="closeComingSoon(event)">&times;</button>
            <div class="modal-icon">
                <i class="fas fa-rocket"></i>
            </div>
            <h3>Coming Soon!</h3>
            <p id="comingSoonText">This feature is currently under development.</p>
            <p class="modal-subtext">We're working hard to bring you this content. Check back soon!</p>
            <button class="btn btn-primary" onclick="closeComingSoon(event)">Got it!</button>
            </div>
        </div>

    <style>
        /* Coming Soon Modal */
        .modal-overlay {
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
            animation: fadeIn 0.3s ease;
        }
        
        .modal-overlay.show {
            display: flex;
        }
        
        .modal-content {
            background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%);
            border: 1px solid #334155;
            border-radius: 24px;
            padding: 48px;
            max-width: 420px;
            width: 100%;
            text-align: center;
            position: relative;
            animation: slideUp 0.4s ease;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5), 0 0 60px rgba(14, 165, 233, 0.15);
        }
        
        .modal-close {
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
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .modal-close:hover {
            background: rgba(239, 68, 68, 0.2);
            color: #ef4444;
        }
        
        .modal-icon {
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
            animation: pulse 2s infinite;
        }
        
        .modal-content h3 {
            font-size: 28px;
            font-weight: 700;
            color: #ffffff;
            margin-bottom: 12px;
        }
        
        .modal-content p {
            color: #94a3b8;
            margin-bottom: 8px;
            font-size: 16px;
        }
        
        .modal-subtext {
            font-size: 14px !important;
            color: #64748b !important;
            margin-bottom: 24px !important;
        }
        
        .modal-content .btn {
            margin-top: 16px;
            min-width: 140px;
        }
        
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }
        
        @keyframes slideUp {
            from { 
                opacity: 0;
                transform: translateY(30px);
            }
            to { 
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        @keyframes pulse {
            0%, 100% { box-shadow: 0 0 0 0 rgba(14, 165, 233, 0.4); }
            50% { box-shadow: 0 0 0 15px rgba(14, 165, 233, 0); }
        }
    </style>

    <script>
        function showComingSoon(title) {
            const modal = document.getElementById('comingSoonModal');
            const text = document.getElementById('comingSoonText');
            text.innerHTML = '<strong>' + title + '</strong> is currently under development.';
            modal.classList.add('show');
            document.body.style.overflow = 'hidden';
        }
        
        function closeComingSoon(event) {
            if (event) {
                event.stopPropagation();
                if (event.target.id === 'comingSoonModal' || 
                    event.target.classList.contains('modal-close') ||
                    event.target.classList.contains('btn')) {
                    const modal = document.getElementById('comingSoonModal');
                    modal.classList.remove('show');
                    document.body.style.overflow = '';
                }
            }
        }
        
        // Close on Escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                const modal = document.getElementById('comingSoonModal');
                modal.classList.remove('show');
                document.body.style.overflow = '';
            }
        });
    </script>

    <?php include 'includes/footer.php'; ?>
</body>
</html>
