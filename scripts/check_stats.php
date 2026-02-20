<?php
require_once __DIR__ . '/../database/Database.php';
$db = Database::getInstance();
$stats = $db->getStats();
echo "Total stats: " . count($stats) . "\n";
print_r($stats);
?>
