<?php
/**
 * AlfaisalX - Team Overview
 */

require_once '../includes/config.php';

$page_title = 'Our Team';
$page_description = 'Meet the leadership and research team of AlfaisalX - experts in robotics, AI, and autonomous systems.';
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
                <span class="section-tag">// Our People</span>
                <h1 class="page-title">Meet Our Team</h1>
                <p class="page-subtitle">
                    World-class researchers and engineers driving innovation in robotics and AI.
                </p>
            </div>
        </section>

        <!-- Leadership Section -->
        <section class="section">
            <div class="container">
                <h2 class="section-title">Leadership</h2>
                <div class="team-grid leadership-grid">
                    <?php foreach ($team_members['leadership'] as $member): ?>
                    <div class="team-card team-card-featured">
                        <div class="team-avatar">
                            <?php if ($member['image']): ?>
                                <img src="<?php echo ASSETS_URL; ?>/images/team/<?php echo $member['image']; ?>" alt="<?php echo $member['name']; ?>">
                            <?php else: ?>
                                <span class="avatar-initials"><?php echo $member['initials']; ?></span>
                            <?php endif; ?>
                        </div>
                        <div class="team-info">
                            <h3><?php echo $member['name']; ?></h3>
                            <span class="team-role"><?php echo $member['role']; ?></span>
                            <span class="team-title"><?php echo $member['title']; ?></span>
                            <p class="team-bio"><?php echo $member['bio']; ?></p>
                            <div class="team-expertise">
                                <?php foreach ($member['expertise'] as $exp): ?>
                                <span class="expertise-tag"><?php echo $exp; ?></span>
                                <?php endforeach; ?>
                            </div>
                            <div class="team-links">
                                <?php if (!empty($member['email'])): ?>
                                <a href="mailto:<?php echo $member['email']; ?>" aria-label="Email">
                                    <i class="fas fa-envelope"></i>
                                </a>
                                <?php endif; ?>
                                <?php if (!empty($member['google_scholar'])): ?>
                                <a href="<?php echo $member['google_scholar']; ?>" target="_blank" aria-label="Google Scholar">
                                    <i class="fas fa-graduation-cap"></i>
                                </a>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </section>

        <!-- Core Faculty Section -->
        <section class="section section-alt">
            <div class="container">
                <h2 class="section-title">Core Faculty</h2>
                <div class="team-grid">
                    <?php foreach ($team_members['core_faculty'] as $member): ?>
                    <div class="team-card">
                        <div class="team-avatar">
                            <?php if ($member['image']): ?>
                                <img src="<?php echo ASSETS_URL; ?>/images/team/<?php echo $member['image']; ?>" alt="<?php echo $member['name']; ?>">
                            <?php else: ?>
                                <span class="avatar-initials"><?php echo $member['initials']; ?></span>
                            <?php endif; ?>
                        </div>
                        <div class="team-info">
                            <h3><?php echo $member['name']; ?></h3>
                            <span class="team-role"><?php echo $member['role']; ?></span>
                            <p class="team-bio"><?php echo $member['bio']; ?></p>
                            <div class="team-expertise">
                                <?php foreach ($member['expertise'] as $exp): ?>
                                <span class="expertise-tag"><?php echo $exp; ?></span>
                                <?php endforeach; ?>
                            </div>
                            <div class="team-links">
                                <?php if (!empty($member['email'])): ?>
                                <a href="mailto:<?php echo $member['email']; ?>" aria-label="Email">
                                    <i class="fas fa-envelope"></i>
                                </a>
                                <?php endif; ?>
                                <?php if (!empty($member['google_scholar'])): ?>
                                <a href="<?php echo $member['google_scholar']; ?>" target="_blank" aria-label="Google Scholar">
                                    <i class="fas fa-graduation-cap"></i>
                                </a>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </section>

        <?php if (!empty($team_members['adjunct'])): ?>
        <!-- External Adjunct Members Section -->
        <section class="section">
            <div class="container">
                <h2 class="section-title">External Adjunct Members</h2>
                <div class="team-grid">
                    <?php foreach ($team_members['adjunct'] as $member): ?>
                    <div class="team-card">
                        <div class="team-avatar">
                            <?php if ($member['image']): ?>
                                <img src="<?php echo ASSETS_URL; ?>/images/team/<?php echo $member['image']; ?>" alt="<?php echo $member['name']; ?>">
                            <?php else: ?>
                                <span class="avatar-initials"><?php echo $member['initials']; ?></span>
                            <?php endif; ?>
                        </div>
                        <div class="team-info">
                            <h3><?php echo $member['name']; ?></h3>
                            <span class="team-role"><?php echo $member['title']; ?></span>
                            <p class="team-bio"><?php echo $member['bio']; ?></p>
                            <div class="team-expertise">
                                <?php foreach ($member['expertise'] as $exp): ?>
                                <span class="expertise-tag"><?php echo $exp; ?></span>
                                <?php endforeach; ?>
                            </div>
                            <div class="team-links">
                                <?php if (!empty($member['email'])): ?>
                                <a href="mailto:<?php echo $member['email']; ?>" aria-label="Email">
                                    <i class="fas fa-envelope"></i>
                                </a>
                                <?php endif; ?>
                                <?php if (!empty($member['google_scholar'])): ?>
                                <a href="<?php echo $member['google_scholar']; ?>" target="_blank" aria-label="Google Scholar">
                                    <i class="fas fa-graduation-cap"></i>
                                </a>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </section>
        <?php endif; ?>

        <!-- Join Us CTA -->
        <section class="section cta-section">
            <div class="container">
                <div class="cta-content">
                    <h2>Join Our Team</h2>
                    <p>We're looking for talented researchers and engineers to join AlfaisalX.</p>
                    <a href="<?php echo SITE_URL; ?>/careers/" class="btn btn-primary">View Open Positions</a>
                </div>
            </div>
        </section>
    </main>

    <?php include '../includes/footer.php'; ?>
</body>
</html>





