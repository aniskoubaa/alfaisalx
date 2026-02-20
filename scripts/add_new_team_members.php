<?php
/**
 * Add New Team Members to AlfaisalX Database
 * 
 * Adds: Prof. Abd-Elhamid Taha, Dr. Ahmad Sawalmeh, Dr. Omar Al-Ahmad
 * 
 * Usage: php add_new_team_members.php
 * Or visit: http://localhost/alfaisalx/scripts/add_new_team_members.php
 */

require_once dirname(__DIR__) . '/database/Database.php';

$db = Database::getInstance();

echo "<pre>";
echo "=== Adding New Team Members ===\n\n";

$newMembers = [
    // Prof. Abd-Elhamid Taha - Smart Cities
    [
        'name' => 'Prof. Abd-Elhamid Taha',
        'title' => 'Professor',
        'role' => 'Core Faculty - Smart Cities',
        'category' => 'core_faculty',
        'initials' => 'AT',
        'image' => null,
        'bio' => 'Expert in Smart Cities research, focusing on intelligent urban systems, IoT infrastructure, and sustainable city technologies.',
        'bio_extended' => null,
        'email' => null,
        'phone' => null,
        'expertise' => json_encode(['Smart Cities', 'IoT', 'Urban Systems', 'Sustainable Technologies']),
        'google_scholar' => null,
        'linkedin' => null,
        'researchgate' => null,
        'is_active' => 1,
        'sort_order' => 3
    ],
    // Dr. Ahmad Sawalmeh - UAVs, Smart Cities
    [
        'name' => 'Dr. Ahmad Sawalmeh',
        'title' => 'Assistant Professor',
        'role' => 'Core Faculty - UAVs & Smart Cities',
        'category' => 'core_faculty',
        'initials' => 'AS',
        'image' => null,
        'bio' => 'Specializing in UAV systems and Smart Cities applications. Research focus on drone technologies, aerial autonomy, and intelligent urban infrastructure.',
        'bio_extended' => null,
        'email' => null,
        'phone' => null,
        'expertise' => json_encode(['UAVs', 'Smart Cities', 'Drone Systems', 'Aerial Autonomy']),
        'google_scholar' => null,
        'linkedin' => null,
        'researchgate' => null,
        'is_active' => 1,
        'sort_order' => 4
    ],
    // Dr. Omar Al-Ahmad - AI in Healthcare (Under MedX)
    [
        'name' => 'Dr. Omar Al-Ahmad',
        'title' => 'Assistant Professor',
        'role' => 'MedX Unit - AI in Healthcare',
        'category' => 'core_faculty',
        'initials' => 'OA',
        'image' => null,
        'bio' => 'Expert in AI applications for healthcare under the MedX unit. Research focus on medical AI, clinical decision support, and healthcare automation.',
        'bio_extended' => null,
        'email' => null,
        'phone' => null,
        'expertise' => json_encode(['AI in Healthcare', 'Medical AI', 'Clinical Decision Support', 'Healthcare Automation']),
        'google_scholar' => null,
        'linkedin' => null,
        'researchgate' => null,
        'is_active' => 1,
        'sort_order' => 5
    ],
];

$added = 0;
foreach ($newMembers as $member) {
    // Check if member already exists
    $existing = $db->queryOne("SELECT id FROM team_members WHERE name = ?", [$member['name']]);
    
    if ($existing) {
        echo "⚠ {$member['name']} already exists (ID: {$existing['id']}), skipping...\n";
        continue;
    }
    
    $db->execute(
        "INSERT INTO team_members (name, title, role, category, initials, image, bio, bio_extended, email, phone, expertise, google_scholar, linkedin, researchgate, is_active, sort_order) 
         VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)",
        [
            $member['name'],
            $member['title'],
            $member['role'],
            $member['category'],
            $member['initials'],
            $member['image'],
            $member['bio'],
            $member['bio_extended'],
            $member['email'],
            $member['phone'],
            $member['expertise'],
            $member['google_scholar'],
            $member['linkedin'],
            $member['researchgate'],
            $member['is_active'],
            $member['sort_order']
        ]
    );
    
    echo "✓ Added: {$member['name']} ({$member['role']})\n";
    $added++;
}

echo "\n=== Complete! Added {$added} new team members ===\n";

// Show all current team members
echo "\n--- Current Team Members ---\n";
$allMembers = $db->query("SELECT name, role, category FROM team_members WHERE is_active = 1 ORDER BY category, sort_order");
foreach ($allMembers as $m) {
    echo "  • {$m['name']} - {$m['role']} [{$m['category']}]\n";
}

echo "</pre>";
