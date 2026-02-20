<?php
require_once __DIR__ . '/../database/Database.php';
$db = Database::getInstance();

// Delete all partners
$db->execute("DELETE FROM partners");

// Re-insert only confirmed partners (matching index.php homepage strip)
$partners = [
    // Industry Partners - confirmed
    ['HUMAIN', 'HUMAIN - AI & Technology Solutions', 'industry', 'humain.png',
        'Confirmed readiness to collaborate on multiple initiatives in AI and robotics, accelerating knowledge transfer and co-development of industrial solutions.',
        'HUMAIN has expressed commitment to engage with AlfaisalX on multiple initiatives, demonstrating the industrial trust in our vision.',
        'https://humain.com', 1, 1],
    ['Bako Motors', 'Bako Motors - Electric Vehicle Manufacturer (Tunisia)', 'industry', 'bakomotors_logo_rect.png',
        'Formally pledged industrial support by providing a dedicated electric vehicle platform for research, development, and testing of autonomous driving technologies.',
        'Bako Motors will collaborate with the AlfaisalX team to integrate AI-driven autonomy modules and support real-world validation. They have committed to providing a full electric vehicle to Alfaisal University.',
        'https://bakomotors.com', 1, 2],
    ['Tawuniya', 'Tawuniya Insurance', 'industry', 'tawuniya.jpg',
        'Industry-funded grant for Generative AI-Powered Multilingual Chatbot for Patient Engagement and Claims Processing.',
        'Won with 1.6% acceptance rate across all Saudi universities. Project addresses strategic national healthcare priorities by automating medical record generation, fraud detection, and billing compliance.',
        'https://www.tawuniya.com', 1, 3],
    
    // Government Partners - only confirmed
    ['PSDSARC', 'Prince Sultan Defense Studies and Research Center', 'government', 'psdsarc-transparent.jpg',
        'Defense research grant secured for U-SCAR UAV Surveillance System project.',
        'Project develops AI-powered multimodal detection (vision and sound) for real-time drone monitoring, contributing to defense, security, and emergency response capabilities.',
        null, 1, 1],
];

foreach ($partners as $p) {
    $db->execute(
        "INSERT INTO partners (name, full_name, type, logo, description, collaboration_details, website, is_active, sort_order) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)",
        $p
    );
}

echo "Partners table updated! Now contains " . count($db->query("SELECT * FROM partners")) . " rows.\n";

// Verify
$partners = $db->query("SELECT name, type, logo FROM partners ORDER BY type, sort_order");
foreach ($partners as $p) {
    echo $p['name'] . ' (' . $p['type'] . ') - Logo: ' . ($p['logo'] ?: 'none') . "\n";
}
?>
