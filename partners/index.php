<?php
/**
 * AlfaisalX - Partners Overview
 */

require_once '../includes/config.php';

$page_title = 'Partners';
$page_description = 'Explore our industry, government, and academic partnerships driving innovation in robotics and AI.';
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
                <span class="section-tag">// Collaboration</span>
                <h1 class="page-title">Our Partners</h1>
                <p class="page-subtitle">
                    Strategic collaborations with industry, government, and academia to deliver real-world impact.
                </p>
            </div>
        </section>

        <!-- Industry Partners -->
        <section class="section">
            <div class="container">
                <h2 class="section-title">Industry Partners</h2>
                <div class="partners-grid">
                    <?php foreach ($partners['industry'] as $partner): ?>
                    <div class="partner-card">
                        <div class="partner-logo">
                            <?php if ($partner['logo']): ?>
                                <img src="<?php echo ASSETS_URL; ?>/images/partners/<?php echo $partner['logo']; ?>" alt="<?php echo $partner['name']; ?>">
                            <?php else: ?>
                                <span class="partner-placeholder"><?php echo $partner['name'][0]; ?></span>
                            <?php endif; ?>
                        </div>
                        <div class="partner-info">
                            <h3><?php echo $partner['name']; ?></h3>
                            <span class="partner-type"><?php echo $partner['type']; ?></span>
                            <p><?php echo $partner['description']; ?></p>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </section>

        <!-- Government Partners -->
        <section class="section section-alt">
            <div class="container">
                <h2 class="section-title">Government Partners</h2>
                <div class="partners-grid">
                    <?php foreach ($partners['government'] as $partner): ?>
                    <div class="partner-card">
                        <div class="partner-logo">
                            <?php if ($partner['logo']): ?>
                                <img src="<?php echo ASSETS_URL; ?>/images/partners/<?php echo $partner['logo']; ?>" alt="<?php echo $partner['name']; ?>">
                            <?php else: ?>
                                <span class="partner-placeholder"><?php echo $partner['name']; ?></span>
                            <?php endif; ?>
                        </div>
                        <div class="partner-info">
                            <h3><?php echo $partner['name']; ?></h3>
                            <span class="partner-type"><?php echo $partner['full_name']; ?></span>
                            <p><?php echo $partner['description']; ?></p>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </section>

        <!-- CTA -->
        <section class="section cta-section">
            <div class="container">
                <div class="cta-content">
                    <h2>Become a Partner</h2>
                    <p>Join us in pioneering the future of robotics and AI in Saudi Arabia.</p>
                    <a href="become-partner.php" class="btn btn-primary">Partner With Us</a>
                </div>
            </div>
        </section>
    </main>

    <?php include '../includes/footer.php'; ?>
</body>
</html>





