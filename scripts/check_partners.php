<?php
require_once __DIR__ . '/../database/Database.php';
$db = Database::getInstance();

// Check current partner count
$partners = $db->query("SELECT * FROM partners ORDER BY type, sort_order");
echo "Total partners: " . count($partners) . "\n\n";

// Show unique names
$names = [];
foreach ($partners as $p) {
    $names[] = $p['name'] . ' (' . $p['type'] . ')';
}
print_r(array_count_values($names));
?>
