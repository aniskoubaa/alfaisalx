<?php
/**
 * AlfaisalX - Research Units Overview
 */

require_once '../includes/config.php';

$page_title = 'Research Units';
$page_description = 'Explore our specialized research units: Robotics, UAVs & Autonomous Systems, Agentic AI, MedX (Medical Robotics), and Commercialization & Impact.';
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
                <span class="section-tag">// Research Units</span>
                <h1 class="page-title">Research & Innovation</h1>
                <p class="page-subtitle">
                    Four specialized units advancing robotics, autonomous systems, medical AI, and intelligent workflows.
                </p>
            </div>
        </section>

        <!-- Research Units -->
        <section class="section">
            <div class="container">
                <div class="research-grid">
                    <?php 
                    // Color scheme for each unit
                    $unit_colors = [
                        'robotics-autonomous-systems' => '#0077b6',
                        'agentic-ai-workflows' => '#7c3aed',
                        'medx' => '#ef4444',
                        'commercialization-impact' => '#f59e0b'
                    ];
                    foreach ($research_areas as $area): 
                        $color = $unit_colors[$area['slug']] ?? '#0077b6';
                        $has_page = ($area['slug'] === 'medx'); // MedX has dedicated page
                    ?>
                    <div class="research-card" style="--card-accent: <?php echo $color; ?>">
                        <span class="research-number"><?php echo $area['number']; ?></span>
                        <div class="research-icon" style="color: <?php echo $color; ?>">
                            <i class="fas fa-<?php echo $area['icon']; ?>"></i>
                        </div>
                        <h3><?php echo $area['title']; ?></h3>
                        <p><?php echo $area['description']; ?></p>
                        <div class="research-tags">
                            <?php foreach ($area['tags'] as $tag): ?>
                            <span class="tag"><?php echo $tag; ?></span>
                            <?php endforeach; ?>
                        </div>
                        <?php if ($has_page): ?>
                        <a href="<?php echo htmlspecialchars($area['slug']); ?>.php" class="btn btn-text">
                            Explore Unit <i class="fas fa-arrow-right"></i>
                        </a>
                        <?php else: ?>
                        <a href="#" onclick="showComingSoon('<?php echo addslashes(htmlspecialchars($area['title'])); ?>'); return false;" class="btn btn-text">
                            Coming Soon <i class="fas fa-clock"></i>
                        </a>
                        <?php endif; ?>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </section>

        <!-- Quick Links -->
        <section class="section section-alt">
            <div class="container">
                <div class="quick-links-grid">
                    <a href="medx.php" class="quick-link-card">
                        <i class="fas fa-heartbeat"></i>
                        <h3>MedX Unit</h3>
                        <p>Medical Robotics & AI in Healthcare</p>
                    </a>
                    <a href="../publications/" class="quick-link-card">
                        <i class="fas fa-file-alt"></i>
                        <h3>Publications</h3>
                        <p>Access our research papers and patents</p>
                    </a>
                </div>
            </div>
        </section>
    </main>

    <?php include '../includes/footer.php'; ?>
</body>
</html>





