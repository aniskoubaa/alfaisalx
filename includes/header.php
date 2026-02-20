<!-- Skip Link for Accessibility -->
<a href="#main-content" class="skip-link">Skip to main content</a>

<!-- Site Header -->
<header class="site-header" id="site-header">
    <div class="container">
        <div class="header-inner">
            <!-- Logo -->
            <a href="<?php echo SITE_URL; ?>" class="logo">
                <img src="<?php echo ASSETS_URL; ?>/images/logo/alfaisal_logo.png" alt="AlfaisalX" class="logo-image">
                <div class="logo-text">
                    <span class="logo-name">ALFAISALX</span>
                    <span class="logo-tagline">Center</span>
                </div>
            </a>

            <!-- Main Navigation -->
            <nav class="main-nav" id="main-nav">
                <?php foreach ($main_nav as $key => $item): ?>
                    <?php if (isset($item['children'])): ?>
                        <?php if (isset($item['mega']) && $item['mega']): ?>
                        <!-- Mega Menu -->
                        <div class="nav-dropdown nav-mega">
                            <a href="<?php echo $item['url']; ?>" class="nav-link <?php echo is_nav_active($item['url']) ? 'active' : ''; ?>">
                                <?php echo $item['label']; ?>
                                <i class="fas fa-chevron-down nav-arrow"></i>
                            </a>
                            <div class="mega-menu">
                                <div class="mega-menu-inner">
                                    <?php foreach ($item['children'] as $group): ?>
                                    <div class="mega-column">
                                        <h4><?php echo $group['group']; ?></h4>
                                        <ul>
                                            <?php foreach ($group['items'] as $child): ?>
                                            <li><a <?php echo get_nav_link($child['url'], $child['label']); ?>><?php echo $child['label']; ?></a></li>
                                            <?php endforeach; ?>
                                        </ul>
                                    </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        </div>
                        <?php else: ?>
                        <!-- Regular Dropdown -->
                        <div class="nav-dropdown">
                            <a href="<?php echo $item['url']; ?>" class="nav-link <?php echo is_nav_active($item['url']) ? 'active' : ''; ?>">
                                <?php echo $item['label']; ?>
                                <i class="fas fa-chevron-down nav-arrow"></i>
                            </a>
                            <div class="dropdown-menu">
                                <?php foreach ($item['children'] as $child): ?>
                                <a <?php echo get_nav_link($child['url'], $child['label']); ?> class="dropdown-link">
                                    <span class="dropdown-label"><?php echo $child['label']; ?></span>
                                    <?php if (isset($child['desc'])): ?>
                                    <span class="dropdown-desc"><?php echo $child['desc']; ?></span>
                                    <?php endif; ?>
                                </a>
                                <?php endforeach; ?>
                            </div>
                        </div>
                        <?php endif; ?>
                    <?php else: ?>
                    <!-- Simple Link -->
                    <a href="<?php echo $item['url']; ?>" class="nav-link <?php echo is_nav_active($item['url']) ? 'active' : ''; ?>">
                        <?php echo $item['label']; ?>
                    </a>
                    <?php endif; ?>
                <?php endforeach; ?>
            </nav>

            <!-- Header Actions -->
            <div class="header-actions">
                <button class="theme-toggle" id="theme-toggle" aria-label="Toggle theme" title="Toggle light/dark mode">
                    <i class="fas fa-sun icon-sun"></i>
                    <i class="fas fa-moon icon-moon"></i>
                </button>
                <a href="<?php echo SITE_URL; ?>/partners/become-partner.php" class="btn-cta-header">
                    <span>Partner With Us</span>
                </a>
                <button class="mobile-menu-toggle" id="mobile-menu-toggle" aria-label="Menu">
                    <span class="hamburger-line"></span>
                    <span class="hamburger-line"></span>
                    <span class="hamburger-line"></span>
                </button>
            </div>
        </div>
    </div>
</header>

<!-- Mobile Navigation -->
<nav class="mobile-nav" id="mobile-nav">
    <div class="mobile-nav-header">
        <a href="<?php echo SITE_URL; ?>" class="logo">
            <img src="<?php echo ASSETS_URL; ?>/images/logo/alfaisal_logo.png" alt="AlfaisalX" class="logo-image">
            <span class="logo-name">ALFAISALX</span>
        </a>
        <button class="mobile-nav-close" id="mobile-nav-close" aria-label="Close menu">
            <i class="fas fa-times"></i>
        </button>
    </div>
    <ul class="mobile-nav-links">
        <?php foreach ($main_nav as $key => $item): ?>
        <li class="<?php echo isset($item['children']) ? 'has-children' : ''; ?>">
            <a href="<?php echo $item['url']; ?>" class="mobile-nav-link">
                <i class="fas fa-<?php echo $item['icon']; ?>"></i>
                <?php echo $item['label']; ?>
            </a>
            <?php if (isset($item['children'])): ?>
            <button class="mobile-dropdown-toggle" aria-label="Toggle submenu">
                <i class="fas fa-chevron-down"></i>
            </button>
            <ul class="mobile-submenu">
                <?php if (isset($item['mega']) && $item['mega']): ?>
                    <?php foreach ($item['children'] as $group): ?>
                        <?php foreach ($group['items'] as $child): ?>
                        <li><a <?php echo get_nav_link($child['url'], $child['label']); ?>><?php echo $child['label']; ?></a></li>
                        <?php endforeach; ?>
                    <?php endforeach; ?>
                <?php else: ?>
                    <?php foreach ($item['children'] as $child): ?>
                    <li><a <?php echo get_nav_link($child['url'], $child['label']); ?>><?php echo $child['label']; ?></a></li>
                    <?php endforeach; ?>
                <?php endif; ?>
            </ul>
            <?php endif; ?>
        </li>
        <?php endforeach; ?>
    </ul>
    <div class="mobile-nav-footer">
        <a href="<?php echo SITE_URL; ?>/partners/become-partner.php" class="btn btn-primary" style="width: 100%;">
            Partner With Us
        </a>
    </div>
</nav>

<!-- Mobile Nav Overlay -->
<div class="mobile-nav-overlay" id="mobile-nav-overlay"></div>

<!-- Search Overlay -->
<div class="search-overlay" id="search-overlay">
    <div class="search-container">
        <button class="search-close" id="search-close" aria-label="Close search">
            <i class="fas fa-times"></i>
        </button>
        <div class="search-input-wrapper">
            <i class="fas fa-search"></i>
            <input type="text" id="search-input" placeholder="Search AlfaisalX..." autocomplete="off">
        </div>
        <p class="search-hint">Press ESC to close</p>
    </div>
</div>





