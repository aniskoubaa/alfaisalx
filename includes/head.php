<?php
/**
 * AlfaisalX - Head Section
 * 
 * Include this file in the <head> of every page.
 * Before including, set $page_title and optionally $page_description.
 */

// Default values if not set
$page_title = isset($page_title) ? $page_title . ' | ' . SITE_NAME : SITE_NAME . ' - ' . SITE_FULL_NAME;
$page_description = isset($page_description) ? $page_description : SITE_TAGLINE;
?>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="description" content="<?php echo htmlspecialchars($page_description); ?>">
<meta name="keywords" content="AlfaisalX, Robotics, UAV, Autonomous Agents, AI, Alfaisal University, Saudi Arabia, Vision 2030">
<meta name="author" content="AlfaisalX - Alfaisal University">

<!-- Open Graph / Social Media -->
<meta property="og:title" content="<?php echo htmlspecialchars($page_title); ?>">
<meta property="og:description" content="<?php echo htmlspecialchars($page_description); ?>">
<meta property="og:type" content="website">
<meta property="og:url" content="<?php echo SITE_URL; ?>">
<meta property="og:image" content="<?php echo ASSETS_URL; ?>/images/logo/og-image.png">

<!-- Twitter Card -->
<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:title" content="<?php echo htmlspecialchars($page_title); ?>">
<meta name="twitter:description" content="<?php echo htmlspecialchars($page_description); ?>">

<title><?php echo htmlspecialchars($page_title); ?></title>

<!-- Favicon -->
<link rel="icon" type="image/png" href="<?php echo ASSETS_URL; ?>/images/logo/favicon.png">
<link rel="apple-touch-icon" href="<?php echo ASSETS_URL; ?>/images/logo/apple-touch-icon.png">

<!-- Google Fonts: Space Grotesk (headings) + Inter (body) -->
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=Space+Grotesk:wght@400;500;600;700&family=JetBrains+Mono:wght@400;500&display=swap" rel="stylesheet">

<!-- Font Awesome Icons -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

<!-- Main Stylesheet -->
<link rel="stylesheet" href="<?php echo ASSETS_URL; ?>/css/main.css">

<!-- Theme Initialization (prevent flash) -->
<script>
    (function() {
        const savedTheme = localStorage.getItem('alfaisalx-theme') || 'dark';
        document.documentElement.setAttribute('data-theme', savedTheme);
    })();
</script>





