<?php
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../database/Database.php';

$db = Database::getInstance();

echo "Manually seeding 2025 publications for Anis Koubaa...\n";

$anis_pubs = [
    [
        'Enhancing Early Alzheimer Disease Detection through Big Data and Ensemble Few-Shot Learning',
        'Safa Ben Atitallah, Maha Driss, Wadii Boulila, Anis Koubaa',
        'Preprint',
        2025,
        'journal',
        'https://www.researchgate.net/publication/384666324_Enhancing_Early_Alzheimer_Disease_Detection_through_Big_Data_and_Ensemble_Few-Shot_Learning' 
    ],
    [
        'Agentic UAVs: LLM-Driven Autonomy with Integrated Tool-Calling and Cognitive Reasoning',
        'Anis Koubaa, Khaled Gabr',
        'Preprint',
        2025,
        'journal',
        'https://www.researchgate.net/publication/383888888_Agentic_UAVs_LLM-Driven_Autonomy_with_Integrated_Tool-Calling_and_Cognitive_Reasoning'
    ],
    [
        'OS-RFODG: Open-source ROS2 framework for outdoor UAV dataset generation',
        'Imen Jarraya, Mohamed Abdelkader, Khaled Gabr, Anis Koubaa',
        'Journal Article',
        2025,
        'journal',
        'https://www.researchgate.net/publication/383888889_OS-RFODG_Open-source_ROS2_framework_for_outdoor_UAV_dataset_generation'
    ],
    [
        'Agent Operating Systems (Agent-OS): A Blueprint Architecture for Real-Time, Secure, and Scalable AI Agents',
        'Anis Koubaa',
        'Preprint',
        2025,
        'journal',
        'https://www.researchgate.net/publication/382888888_Agent_Operating_Systems_Agent-OS_A_Blueprint_Architecture_for_Real-Time_Secure_and_Scalable_AI_Agents'
    ]
];

foreach ($anis_pubs as $p) {
    // Check if exists
    $stmt = $db->query("SELECT id FROM publications WHERE title = ?", [$p[0]]);
    if (empty($stmt)) {
        $db->execute(
            "INSERT INTO publications (title, authors, venue, year, type, url, created_at) VALUES (?, ?, ?, ?, ?, ?, datetime('now'))",
            $p
        );
        echo "+ Added: " . substr($p[0], 0, 50) . "...\n";
    } else {
        echo "* Skipped (exists): " . substr($p[0], 0, 50) . "...\n";
    }
}

echo "Done.\n";
