<?php
require_once __DIR__ . '/../database/Database.php';

$db = Database::getInstance();
$job = $db->queryOne("SELECT * FROM jobs WHERE title LIKE 'Research Engineer%'");

print_r(json_decode($job['requirements'], true));
?>
