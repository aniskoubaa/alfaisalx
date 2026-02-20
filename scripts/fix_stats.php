<?php
require_once __DIR__ . '/../database/Database.php';
$db = Database::getInstance();

// Delete all stats
$db->execute("DELETE FROM stats");

// Re-insert the correct 4 stats
$stats = [
    ['10+', 'Research Papers/Year', 'file-alt', 1],
    ['4', 'Core Research Domains', 'cubes', 2],
    ['3', 'Research Labs', 'flask', 3],
    ['2025', 'Established', 'calendar-alt', 4],
];

foreach ($stats as $s) {
    $db->execute(
        "INSERT INTO stats (value, label, icon, sort_order) VALUES (?, ?, ?, ?)",
        $s
    );
}

echo "Stats table cleaned! Now contains " . count($db->getStats()) . " rows.\n";
print_r($db->getStats());
?>
