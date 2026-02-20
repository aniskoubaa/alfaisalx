<?php
require_once __DIR__ . '/../database/Database.php';

$db = Database::getInstance();

// New requirement string
$newreq = "Bachelor's or Master's degree in Electrical Engineering, Robotics, Mechatronics, Computer Engineering, or Computer Science";

// Fetch current job
$job = $db->queryOne("SELECT * FROM jobs WHERE title LIKE 'Research Engineer%'");
if (!$job) {
    die("Job not found\n");
}

$requirements = json_decode($job['requirements'], true);

// Replace the first requirement (index 0) which is about the degree
// Or find the one containing "M.Sc." just to be safe
$updated = false;
foreach ($requirements as $k => $v) {
    if (strpos($v, 'M.Sc.') !== false || strpos($v, 'Ph.D.') !== false) {
        $requirements[$k] = $newreq;
        $updated = true;
        break;
    }
}

if (!$updated) {
    // If not found, prepend it
    array_unshift($requirements, $newreq);
}

// Update database
$db->execute(
    "UPDATE jobs SET requirements = ? WHERE id = ?",
    [json_encode($requirements), $job['id']]
);

echo "Updated requirements for " . $job['title'] . "\n";
print_r($requirements);
?>
