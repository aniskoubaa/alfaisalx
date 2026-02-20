<?php
$envPath = __DIR__ . '/.env';
$lines = file($envPath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
$apiKey = '';
foreach ($lines as $line) {
    if (strpos(trim($line), '#') === 0) continue;
    if (strpos($line, '=') !== false) {
        list($key, $value) = explode('=', $line, 2);
        if (trim($key) === 'SERPAPI_API_KEY') $apiKey = trim($value);
    }
}

$query = 'author:"Anis Koubaa"';
$params = [
    'engine' => 'google_scholar',
    'q' => $query,
    'api_key' => $apiKey,
    'as_ylo' => 2026,
    'num' => 20,
    'hl' => 'en',
    'no_cache' => 'true'
];

$url = 'https://serpapi.com/search.json?' . http_build_query($params);
$response = file_get_contents($url);
$data = json_decode($response, true);

echo "Query: $query\n";
foreach ($data['organic_results'] ?? [] as $pub) {
    if (isset($pub['title'])) echo $pub['title'] . "\n";
}
