<?php
/**
 * AlfaisalX - News Overview
 */

require_once '../includes/config.php';

$page_title = 'News & Events';
$page_description = 'Latest announcements, events, and updates from AlfaisalX.';

// Get news from database
$news_items = get_db()->getNews();
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
                <span class="section-tag">// Latest Updates</span>
                <h1 class="page-title">News & Events</h1>
                <p class="page-subtitle">
                    Stay updated with the latest from AlfaisalX.
                </p>
            </div>
        </section>

        <!-- News Grid -->
        <section class="section">
            <div class="container">
                <div class="news-filter">
                    <button class="filter-btn active" data-filter="all">All</button>
                    <button class="filter-btn" data-filter="announcement">Announcements</button>
                    <button class="filter-btn" data-filter="event">Events</button>
                    <button class="filter-btn" data-filter="research">Research</button>
                </div>

                <div class="news-grid">
                    <?php foreach ($news_items as $news): ?>
                    <article class="news-card" data-type="<?php echo htmlspecialchars($news['type']); ?>">
                        <div class="news-image">
                            <i class="fas fa-<?php echo htmlspecialchars($news['icon'] ?? 'newspaper'); ?>"></i>
                        </div>
                        <div class="news-content">
                            <div class="news-meta">
                                <span class="news-type"><?php echo htmlspecialchars(ucfirst($news['type'])); ?></span>
                                <span class="news-date">
                                    <i class="far fa-calendar"></i>
                                    <?php echo date('M d, Y', strtotime($news['date'])); ?>
                                </span>
                            </div>
                            <h3><?php echo htmlspecialchars($news['title']); ?></h3>
                            <p><?php echo htmlspecialchars($news['excerpt']); ?></p>
                        </div>
                    </article>
                    <?php endforeach; ?>
                </div>
            </div>
        </section>

    </main>

    <?php include '../includes/footer.php'; ?>
</body>
</html>





