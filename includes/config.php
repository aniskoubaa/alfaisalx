<?php
/**
 * AlfaisalX Website Configuration
 * 
 * Loads all site data from SQLite database.
 */

// ============================================================
// DATABASE CONNECTION
// ============================================================

require_once dirname(__DIR__) . '/database/Database.php';

$db = Database::getInstance();

// ============================================================
// SITE URLS
// ============================================================

define('SITE_URL', '/alfaisalx');
define('ASSETS_URL', SITE_URL . '/assets');

// File paths
define('ROOT_PATH', dirname(__DIR__));
define('INCLUDES_PATH', ROOT_PATH . '/includes');
define('ASSETS_PATH', ROOT_PATH . '/assets');

// ============================================================
// LOAD SETTINGS FROM DATABASE
// ============================================================

$settings = $db->getAllSettings();

// Define constants from settings
define('SITE_NAME', $settings['site_name'] ?? 'AlfaisalX');
define('SITE_FULL_NAME', $settings['site_full_name'] ?? 'Alfaisal Center for Cognitive Robotics and Autonomous Agents');
define('SITE_TAGLINE', $settings['site_tagline'] ?? 'Pioneering Cognitive Robotics and Autonomous Agents for Tomorrow');
define('VISION', $settings['vision'] ?? 'Global leadership in Robotics, Autonomous Systems, and Agents.');
define('MISSION', $settings['mission'] ?? 'Innovating robotics and Agentic workflows for smarter industries and societies.');
define('CONTACT_EMAIL', $settings['contact_email'] ?? 'alfaisalx@alfaisal.edu');
define('CONTACT_PHONE', $settings['contact_phone'] ?? '+966 11 215 7777');
define('CONTACT_ADDRESS', $settings['contact_address'] ?? 'Alfaisal University, College of Engineering, Riyadh, Saudi Arabia');

// Social links
$social_links = [
    'linkedin' => $settings['social_linkedin'] ?? '#',
    'github' => $settings['social_github'] ?? '#',
    'twitter' => $settings['social_twitter'] ?? '#',
    'scholar' => $settings['social_scholar'] ?? '#'
];

// ============================================================
// LOAD DATA FROM DATABASE
// ============================================================

// Stats for homepage
$stats = $db->getStats();

// Team members - load and parse JSON fields
$team_categories = ['leadership', 'core_faculty', 'researchers', 'adjunct', 'staff'];
$team_members = [];
foreach ($team_categories as $category) {
    $members = $db->getTeamByCategory($category);
    foreach ($members as &$member) {
        $member['expertise'] = json_decode($member['expertise'] ?? '[]', true) ?: [];
    }
    unset($member);
    $team_members[$category] = $members;
}

// Research areas
$research_areas = $db->getResearchAreas();

// Parse JSON fields in research areas
foreach ($research_areas as &$area) {
    $area['tags'] = json_decode($area['tags'] ?? '[]', true) ?: [];
    $area['objectives'] = json_decode($area['objectives'] ?? '[]', true) ?: [];
}
unset($area);

// Objectives
$objectives = $db->getObjectives();
foreach ($objectives as &$obj) {
    $obj['bullet_points'] = json_decode($obj['bullet_points'] ?? '[]', true) ?: [];
}
unset($obj);

// Partners
$partners = [
    'industry' => $db->getPartnersByType('industry'),
    'government' => $db->getPartnersByType('government'),
    'academic' => $db->getPartnersByType('academic')
];

// Labs
$labs = $db->getLabs();
foreach ($labs as &$lab) {
    $lab['equipment'] = json_decode($lab['equipment'] ?? '[]', true) ?: [];
    $lab['team_roles'] = json_decode($lab['team_roles'] ?? '[]', true) ?: [];
}
unset($lab);

// Projects
$projects = $db->getProjects();
foreach ($projects as &$project) {
    $project['partners'] = json_decode($project['partners'] ?? '[]', true) ?: [];
    $project['timeline'] = json_decode($project['timeline'] ?? '[]', true) ?: [];
    $project['objectives'] = json_decode($project['objectives'] ?? '[]', true) ?: [];
}
unset($project);

// Strategic initiatives
$strategic_initiatives = $db->getStrategicInitiatives();

// Application sectors
$application_sectors = $db->getApplicationSectors();

// KPIs
$kpis = $db->getKPIs();

// Infrastructure
$infrastructure = $db->getInfrastructure();

// ============================================================
// NAVIGATION - Simplified & Organized
// ============================================================

$main_nav = [
    'home' => [
        'label' => 'Home',
        'url' => SITE_URL,
        'icon' => 'home'
    ],
    'about' => [
        'label' => 'About',
        'url' => SITE_URL . '/about/',
        'icon' => 'info-circle',
        'children' => [
            ['label' => 'Overview', 'url' => SITE_URL . '/about/', 'desc' => 'Learn about AlfaisalX'],
            ['label' => 'Vision & Mission', 'url' => SITE_URL . '/about/vision-mission.php', 'desc' => 'Our goals and direction'],
            ['label' => 'Objectives', 'url' => SITE_URL . '/about/objectives.php', 'desc' => 'Strategic objectives'],
            ['label' => 'Why AlfaisalX', 'url' => SITE_URL . '/about/why-alfaisalx.php', 'desc' => 'What makes us unique']
        ]
    ],
    'research' => [
        'label' => 'Research',
        'url' => SITE_URL . '/research/',
        'icon' => 'flask',
        'children' => [
            ['label' => 'Research Units', 'url' => SITE_URL . '/research/', 'desc' => 'Our 4 research units'],
            ['label' => 'MedX Unit', 'url' => SITE_URL . '/research/medx.php', 'desc' => 'Medical Robotics & AI'],
            ['label' => 'Publications', 'url' => SITE_URL . '/publications/', 'desc' => 'Research papers']
        ]
    ],
    'team' => [
        'label' => 'Team',
        'url' => SITE_URL . '/team/',
        'icon' => 'users',
        'children' => [
            ['label' => 'Our Team', 'url' => SITE_URL . '/team/', 'desc' => 'Meet our people'],
            ['label' => 'Careers', 'url' => SITE_URL . '/careers/', 'desc' => 'Join our team']
        ]
    ],
    // 'collaborate' => [
    //     'label' => 'Collaborate',
    //     'url' => SITE_URL . '/partners/',
    //     'icon' => 'handshake',
    //     'children' => [
    //         ['label' => 'Partners', 'url' => SITE_URL . '/partners/', 'desc' => 'Our collaborators'],
    //         ['label' => 'Become a Partner', 'url' => SITE_URL . '/partners/become-partner.php', 'desc' => 'Work with us'],
    //         ['label' => 'Education & Training', 'url' => SITE_URL . '/education/', 'desc' => 'Programs & courses']
    //     ]
    // ],
    'news' => [
        'label' => 'News',
        'url' => SITE_URL . '/news/',
        'icon' => 'newspaper'
    ],
    'contact' => [
        'label' => 'Contact',
        'url' => SITE_URL . '/contact/',
        'icon' => 'envelope'
    ]
];

// ============================================================
// HELPER FUNCTIONS
// ============================================================

/**
 * Get the current page path relative to site root
 */
function get_current_page() {
    $path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
    return str_replace('/alfaisalx', '', $path);
}

/**
 * Check if a nav item is active
 */
function is_nav_active($nav_url) {
    $current = get_current_page();
    $nav_path = str_replace(SITE_URL, '', $nav_url);
    
    if ($nav_path === '/' || $nav_path === '') {
        return $current === '/' || $current === '/index.php';
    }
    
    return strpos($current, $nav_path) === 0;
}

/**
 * Generate breadcrumb based on current path
 */
function generate_breadcrumbs() {
    $path = get_current_page();
    $parts = array_filter(explode('/', $path));
    
    $breadcrumbs = [['label' => 'Home', 'url' => SITE_URL]];
    $url = SITE_URL;
    
    foreach ($parts as $part) {
        $part = str_replace('.php', '', $part);
        $label = ucwords(str_replace('-', ' ', $part));
        $url .= '/' . $part;
        $breadcrumbs[] = ['label' => $label, 'url' => $url];
    }
    
    return $breadcrumbs;
}

/**
 * Get setting value
 */
function get_setting($key) {
    global $settings;
    return $settings[$key] ?? null;
}

/**
 * Get database instance
 */
function get_db() {
    return Database::getInstance();
}

/**
 * Parse expertise JSON from team member
 */
function parse_expertise($member) {
    if (isset($member['expertise'])) {
        return json_decode($member['expertise'], true) ?: [];
    }
    return [];
}

/**
 * Check if a page exists for given URL
 * Returns the file path if exists, false otherwise
 */
function page_exists($url) {
    // Convert URL to file path
    $path = str_replace(SITE_URL, '', $url);
    
    // Remove trailing slash and check for index.php
    if (substr($path, -1) === '/') {
        $file = ROOT_PATH . $path . 'index.php';
    } else {
        $file = ROOT_PATH . $path;
    }
    
    return file_exists($file);
}

/**
 * Get nav link attributes - returns coming soon onclick if page doesn't exist
 */
function get_nav_link($url, $label) {
    if (page_exists($url)) {
        return 'href="' . htmlspecialchars($url) . '"';
    } else {
        return 'href="#" onclick="showComingSoon(\'' . addslashes(htmlspecialchars($label)) . '\'); return false;"';
    }
}





