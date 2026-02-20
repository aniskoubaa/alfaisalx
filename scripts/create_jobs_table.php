<?php
/**
 * Create Jobs Table in AlfaisalX Database
 */
require_once dirname(__DIR__) . '/database/Database.php';

$db = Database::getInstance();

echo "<pre>";
echo "=== Creating Jobs Table ===\n\n";

$sql = "CREATE TABLE IF NOT EXISTS jobs (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    title TEXT NOT NULL,
    slug TEXT UNIQUE,
    department TEXT,
    type TEXT DEFAULT 'full-time',
    location TEXT DEFAULT 'Riyadh, Saudi Arabia',
    description TEXT,
    responsibilities TEXT,
    requirements TEXT,
    benefits TEXT,
    apply_email TEXT,
    is_featured INTEGER DEFAULT 0,
    is_active INTEGER DEFAULT 1,
    sort_order INTEGER DEFAULT 0,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP
)";

try {
    $db->execute($sql);
    echo "✓ Jobs table created successfully\n";
    
    // Check if table is empty and add sample job
    $count = $db->queryOne("SELECT COUNT(*) as count FROM jobs")['count'];
    
    if ($count == 0) {
        echo "\nAdding sample job postings...\n";
        
        $jobs = [
            [
                'Postdoctoral Researcher - Robotics & AI',
                'postdoctoral-researcher-robotics-ai',
                'Robotics Lab',
                'postdoc',
                'Riyadh, Saudi Arabia',
                'Join our team as a Postdoctoral Researcher focusing on autonomous robotics and AI systems.',
                "Conduct cutting-edge research in robotics and AI\nPublish in top-tier venues\nMentor graduate students\nCollaborate with industry partners",
                "PhD in Robotics, Computer Science, or related field\nStrong publication record\nExperience with ROS2\nExcellent communication skills",
                "Competitive salary\nResearch funding\nConference travel support\nCollaborative environment",
                'careers@alfaisal.edu',
                1, 1, 1
            ],
            [
                'Research Engineer - UAV Systems',
                'research-engineer-uav-systems',
                'UAV Lab',
                'full-time',
                'Riyadh, Saudi Arabia',
                'We are seeking a Research Engineer to develop and test UAV systems for various applications.',
                "Design and implement UAV control systems\nConduct flight tests\nDevelop perception algorithms\nDocument technical specifications",
                "MS in Aerospace, Electrical, or Computer Engineering\nExperience with drone systems\nProgramming skills (Python, C++)\nKnowledge of computer vision",
                "Competitive salary\nHealth insurance\nProfessional development\nModern lab facilities",
                'careers@alfaisal.edu',
                0, 1, 2
            ],
        ];
        
        foreach ($jobs as $job) {
            $db->execute(
                "INSERT INTO jobs (title, slug, department, type, location, description, responsibilities, requirements, benefits, apply_email, is_featured, is_active, sort_order) 
                 VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)",
                $job
            );
            echo "  ✓ Added: {$job[0]}\n";
        }
    }
    
    echo "\n=== Complete! ===\n";
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}

echo "</pre>";
