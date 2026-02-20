<?php
/**
 * AlfaisalX Admin API - Fetch Publications from SerpAPI
 * 
 * API endpoint for the admin publication fetcher interface.
 * Supports two modes:
 *   - preview: Fetch and return publications without saving
 *   - save: Save selected publications to database
 */

// Increase timeout for save operations
set_time_limit(60);

header('Content-Type: application/json');
session_start();

// Check authentication
if (!isset($_SESSION['admin_email']) || $_SESSION['admin_email'] !== 'akoubaa@alfaisal.edu') {
    http_response_code(401);
    echo json_encode(['error' => 'Unauthorized']);
    exit;
}

require_once '../database/Database.php';

// Get request data
$input = json_decode(file_get_contents('php://input'), true);

if (!$input) {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid request']);
    exit;
}

$db = Database::getInstance();

// Handle different actions
$action = $input['action'] ?? 'preview';

// ============================================
// ACTION: Save selected publications
// ============================================
if ($action === 'save') {
    $publications = $input['publications'] ?? [];
    
    if (empty($publications)) {
        echo json_encode(['error' => 'No publications to save']);
        exit;
    }
    
    $saved = 0;
    $errors = 0;
    $results = [];
    
    foreach ($publications as $pub) {
        // Check for duplicate
        $existing = $db->queryOne("SELECT id FROM publications WHERE title = ?", [$pub['title'] ?? '']);
        
        if ($existing) {
            $results[] = ['title' => $pub['title'], 'status' => 'duplicate'];
            continue;
        }
        
        try {
            // Use basic cleaning for fast save (OpenAI formatting can be run later via batch script)
            $authors = cleanAuthorsBasic($pub['authors'] ?? '');
            
            $db->execute(
                "INSERT INTO publications (title, authors, venue, year, type, doi, url, abstract, is_featured, created_at) 
                 VALUES (?, ?, ?, ?, ?, ?, ?, ?, 0, datetime('now'))",
                [
                    $pub['title'] ?? 'Unknown Title',
                    $authors,
                    $pub['venue'] ?? 'Google Scholar',
                    $pub['year'] ?? null,
                    $pub['type'] ?? 'journal',
                    $pub['doi'] ?? null,
                    $pub['url'] ?? null,
                    $pub['abstract'] ?? ''
                ]
            );
            
            $results[] = ['title' => $pub['title'], 'status' => 'saved', 'authors' => $authors];
            $saved++;
        } catch (Exception $e) {
            $results[] = ['title' => $pub['title'], 'status' => 'error', 'error' => $e->getMessage()];
            $errors++;
        }
    }
    
    echo json_encode([
        'success' => true,
        'action' => 'save',
        'saved' => $saved,
        'errors' => $errors,
        'results' => $results
    ]);
    exit;
}

/**
 * Basic author cleaning without OpenAI
 */
function cleanAuthorsBasic($authors) {
    // Remove ellipsis
    $authors = str_replace(['…', '...'], '', $authors);
    // Remove titles
    $authors = preg_replace('/\b(Dr\.|Prof\.|Eng\.)\s*/i', '', $authors);
    // Clean up
    $authors = preg_replace('/,\s*,/', ',', $authors);
    $authors = preg_replace('/\s+/', ' ', $authors);
    return trim($authors, ', ');
}

// ============================================
// ACTION: Preview (fetch without saving)
// ============================================
if (!isset($input['query'])) {
    http_response_code(400);
    echo json_encode(['error' => 'Missing query parameter']);
    exit;
}

// Load API key
function getApiKey() {
    $envPath = __DIR__ . '/../.env';
    if (!file_exists($envPath)) return null;
    
    $lines = file($envPath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        if (strpos(trim($line), '#') === 0) continue;
        if (strpos($line, '=') !== false) {
            list($key, $value) = explode('=', $line, 2);
            if (trim($key) === 'SERPAPI_API_KEY') {
                return trim($value);
            }
        }
    }
    return null;
}

$apiKey = getApiKey();
if (!$apiKey) {
    echo json_encode(['error' => 'API key not configured']);
    exit;
}

$query = $input['query'];
$yearFrom = intval($input['year_from'] ?? 2025);
$yearTo = isset($input['year_to']) && $input['year_to'] ? intval($input['year_to']) : null;
$num = min(50, max(1, intval($input['num'] ?? 20)));

// Build SerpAPI request
$params = [
    'engine' => 'google_scholar',
    'q' => $query,
    'api_key' => $apiKey,
    'as_ylo' => $yearFrom,
    'num' => $num,
    'hl' => 'en',
    'no_cache' => 'true'
];

if ($yearTo) {
    $params['as_yhi'] = $yearTo;
}

$url = 'https://serpapi.com/search.json?' . http_build_query($params);

// Fetch from SerpAPI
$response = @file_get_contents($url);

if ($response === false) {
    echo json_encode(['error' => 'Failed to connect to SerpAPI']);
    exit;
}

$data = json_decode($response, true);

if (json_last_error() !== JSON_ERROR_NONE) {
    echo json_encode(['error' => 'Invalid API response']);
    exit;
}

if (isset($data['error'])) {
    echo json_encode(['error' => 'API Error: ' . $data['error']]);
    exit;
}

// Process results - PREVIEW ONLY (no database insertion)
$publications = [];
$newCount = 0;
$duplicateCount = 0;
$filteredCount = 0;

$organicResults = $data['organic_results'] ?? [];

foreach ($organicResults as $pub) {
    $title = $pub['title'] ?? 'Unknown Title';
    
    // Extract year
    $year = null;
    if (isset($pub['publication_info']['summary'])) {
        if (preg_match('/\b(19|20)\d{2}\b/', $pub['publication_info']['summary'], $matches)) {
            $year = intval($matches[0]);
        }
    }
    
    // Check year range
    if ($year && $year < $yearFrom) {
        $filteredCount++;
        continue;
    }
    
    if ($yearTo && $year && $year > $yearTo) {
        $filteredCount++;
        continue;
    }
    
    // Check for existing duplicate in database
    $existing = $db->queryOne("SELECT id FROM publications WHERE title = ?", [$title]);
    $isDuplicate = !empty($existing);
    
    if ($isDuplicate) {
        $duplicateCount++;
    } else {
        $newCount++;
    }
    
    // Extract authors
    $authors = $query;
    if (isset($pub['publication_info']['authors']) && is_array($pub['publication_info']['authors'])) {
        $authorNames = array_map(function($a) { return $a['name']; }, $pub['publication_info']['authors']);
        if (!empty($authorNames)) {
            $authors = implode(', ', $authorNames);
        }
    } elseif (isset($pub['publication_info']['summary'])) {
        $parts = explode(' - ', $pub['publication_info']['summary']);
        if (count($parts) > 0) {
            $authors = trim($parts[0]);
        }
    }
    
    // Extract venue
    $venue = 'Google Scholar';
    if (isset($pub['publication_info']['summary'])) {
        $parts = explode(' - ', $pub['publication_info']['summary']);
        if (count($parts) >= 2) {
            $venue = trim($parts[count($parts) - 1]);
        }
    }
    
    $pubUrl = $pub['link'] ?? null;
    $abstract = $pub['snippet'] ?? '';
    
    // Determine type
    $type = 'journal';
    $lowerVenue = strtolower($venue);
    if (strpos($lowerVenue, 'springer') !== false || strpos($lowerVenue, 'book') !== false) {
        $type = 'book';
    } elseif (strpos($lowerVenue, 'conference') !== false || strpos($lowerVenue, 'proceedings') !== false) {
        $type = 'conference';
    }
    
    // Add to preview list (NOT saving to database)
    $publications[] = [
        'title' => $title,
        'authors' => $authors,
        'venue' => $venue,
        'year' => $year,
        'type' => $type,
        'url' => $pubUrl,
        'abstract' => $abstract,
        'doi' => null,
        'is_duplicate' => $isDuplicate
    ];
}

echo json_encode([
    'success' => true,
    'action' => 'preview',
    'query' => $query,
    'total_fetched' => count($organicResults),
    'new_count' => $newCount,
    'duplicate_count' => $duplicateCount,
    'filtered_count' => $filteredCount,
    'publications' => $publications
]);
