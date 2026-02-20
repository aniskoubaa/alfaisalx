<?php
/**
 * AlfaisalX Google Scholar Publication Fetcher via SerpAPI
 * 
 * Fetches publications from Google Scholar starting from 2025
 * and adds missing ones to the database.
 * 
 * Usage: php fetch_serpapi_publications.php
 * Or visit: http://localhost/alfaisalx/scripts/fetch_serpapi_publications.php
 */

// Prevent timeout
set_time_limit(0);

// Load environment variables
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../database/Database.php';

class SerpApiPublicationFetcher {
    private $db;
    private $apiKey;
    private $baseUrl = 'https://serpapi.com/search.json';
    private $targetYear = 2025; // Starting year
    
    public function __construct() {
        $this->db = Database::getInstance();
        $this->loadEnvFile();
        
        if (empty($this->apiKey)) {
            die("Error: SERPAPI_API_KEY not found in .env file\n");
        }
    }
    
    /**
     * Load environment variables from .env file
     */
    private function loadEnvFile() {
        $envPath = __DIR__ . '/../.env';
        
        if (!file_exists($envPath)) {
            die("Error: .env file not found at {$envPath}\n");
        }
        
        $lines = file($envPath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        foreach ($lines as $line) {
            // Skip comments
            if (strpos(trim($line), '#') === 0) {
                continue;
            }
            
            // Parse KEY=VALUE
            if (strpos($line, '=') !== false) {
                list($key, $value) = explode('=', $line, 2);
                $key = trim($key);
                $value = trim($value);
                
                if ($key === 'SERPAPI_API_KEY') {
                    $this->apiKey = $value;
                }
            }
        }
    }
    
    /**
     * Main execution method
     */
    public function run() {
        echo "<pre>";
        echo "=== AlfaisalX Google Scholar Publication Fetcher ===\n\n";
        echo "Target Year: {$this->targetYear} and onwards\n";
        echo "API Key: " . substr($this->apiKey, 0, 10) . "..." . substr($this->apiKey, -5) . "\n\n";
        
        // Get team members with Google Scholar links
        $members = $this->getTeamMembersWithScholar();
        
        echo "Found " . count($members) . " team members with Google Scholar links\n\n";
        
        foreach ($members as $member) {
            $this->processAuthor($member);
        }
        
        echo "\n=== Fetch Complete! ===\n";
        echo "</pre>";
    }
    
    /**
     * Get team members who have Google Scholar profiles
     */
    private function getTeamMembersWithScholar() {
        return $this->db->query(
            "SELECT id, name, google_scholar 
             FROM team_members 
             WHERE google_scholar IS NOT NULL 
             AND google_scholar != '' 
             AND is_active = 1"
        );
    }
    
    /**
     * Process publications for a single author
     */
    private function processAuthor($member) {
        echo "Processing: {$member['name']}\n";
        echo str_repeat("-", 60) . "\n";
        
        // Extract author name for search
        $cleanName = $this->cleanAuthorName($member['name']);
        echo "  Search query: {$cleanName}\n";
        
        $addedCount = 0;
        $skippedCount = 0;
        $start = 0;
        $numResults = 20; // Number of results per request
        
        // Fetch multiple pages if needed
        while (true) {
            $results = $this->fetchPublications($cleanName, $start, $numResults);
            
            if (!$results || empty($results['organic_results'])) {
                if ($start === 0) {
                    echo "  No publications found\n";
                }
                break;
            }
            
            foreach ($results['organic_results'] as $pub) {
                $result = $this->processPublication($pub, $member);
                
                if ($result === 'added') {
                    $addedCount++;
                } elseif ($result === 'skipped') {
                    $skippedCount++;
                } elseif ($result === 'duplicate') {
                    $skippedCount++;
                }
            }
            
            // Check if we should continue pagination
            // For now, we'll just fetch the first page to avoid too many API calls
            break;
        }
        
        echo "  ✓ Added: {$addedCount} | Skipped: {$skippedCount}\n\n";
    }
    
    /**
     * Clean author name (remove titles)
     */
    private function cleanAuthorName($name) {
        $name = str_replace(['Prof.', 'Dr.', 'Eng.'], '', $name);
        return trim($name);
    }
    
    /**
     * Fetch publications from SerpAPI
     */
    private function fetchPublications($query, $start = 0, $num = 20) {
        $params = [
            'engine' => 'google_scholar',
            'q' => $query,
            'api_key' => $this->apiKey,
            'as_ylo' => $this->targetYear, // Year low (from)
            'num' => $num,
            'start' => $start
        ];
        
        $url = $this->baseUrl . '?' . http_build_query($params);
        
        echo "  Fetching from SerpAPI (start={$start})...\n";
        
        $response = @file_get_contents($url);
        
        if ($response === false) {
            echo "  Error: Failed to fetch data from SerpAPI\n";
            return null;
        }
        
        $data = json_decode($response, true);
        
        if (json_last_error() !== JSON_ERROR_NONE) {
            echo "  Error: Invalid JSON response\n";
            return null;
        }
        
        if (isset($data['error'])) {
            echo "  API Error: {$data['error']}\n";
            return null;
        }
        
        return $data;
    }
    
    /**
     * Process a single publication
     */
    private function processPublication($pub, $member) {
        $title = $pub['title'] ?? 'Unknown Title';
        
        // Extract publication year from publication_info if available
        $year = $this->extractYear($pub);
        
        // Skip if year is before target year
        if ($year && $year < $this->targetYear) {
            return 'old';
        }
        
        // Check if publication already exists by title
        $existing = $this->db->queryOne(
            "SELECT id FROM publications WHERE title = ?",
            [$title]
        );
        
        if ($existing) {
            return 'duplicate';
        }
        
        // Extract other fields
        $authors = $this->extractAuthors($pub, $member['name']);
        $venue = $this->extractVenue($pub);
        $url = $pub['link'] ?? null;
        $abstract = $pub['snippet'] ?? '';
        $doi = null;
        
        // Try to extract DOI if available
        if (isset($pub['resources'])) {
            foreach ($pub['resources'] as $resource) {
                if (isset($resource['link']) && strpos($resource['link'], 'doi.org') !== false) {
                    $doi = str_replace('https://doi.org/', '', $resource['link']);
                    break;
                }
            }
        }
        
        // Determine publication type
        $type = $this->determinePublicationType($pub);
        
        // Insert into database
        try {
            $this->db->execute(
                "INSERT INTO publications (title, authors, venue, year, type, doi, url, abstract, is_featured, created_at) 
                 VALUES (?, ?, ?, ?, ?, ?, ?, ?, 0, datetime('now'))",
                [
                    $title,
                    $authors,
                    $venue,
                    $year,
                    $type,
                    $doi,
                    $url,
                    $abstract
                ]
            );
            
            $shortTitle = strlen($title) > 60 ? substr($title, 0, 60) . '...' : $title;
            echo "    + Added: {$shortTitle} ({$year})\n";
            
            return 'added';
        } catch (Exception $e) {
            echo "    ! Error adding publication: {$e->getMessage()}\n";
            return 'error';
        }
    }
    
    /**
     * Extract year from publication data
     */
    private function extractYear($pub) {
        // Try publication_info summary first
        if (isset($pub['publication_info']['summary'])) {
            $summary = $pub['publication_info']['summary'];
            // Look for 4-digit year
            if (preg_match('/\b(20\d{2})\b/', $summary, $matches)) {
                return (int)$matches[1];
            }
        }
        
        // Try inline_links cited_by or other fields
        if (isset($pub['inline_links']['cited_by']['link'])) {
            if (preg_match('/\b(20\d{2})\b/', $pub['inline_links']['cited_by']['link'], $matches)) {
                return (int)$matches[1];
            }
        }
        
        return null;
    }
    
    /**
     * Extract authors from publication
     */
    private function extractAuthors($pub, $memberName) {
        if (isset($pub['publication_info']['summary'])) {
            $summary = $pub['publication_info']['summary'];
            // Usually format is: "Authors - Title, Year - Source"
            $parts = explode(' - ', $summary);
            if (count($parts) > 0) {
                return trim($parts[0]);
            }
        }
        
        // Fallback to member name
        return $memberName;
    }
    
    /**
     * Extract venue from publication
     */
    private function extractVenue($pub) {
        if (isset($pub['publication_info']['summary'])) {
            $summary = $pub['publication_info']['summary'];
            // Try to extract venue (last part after last dash)
            $parts = explode(' - ', $summary);
            if (count($parts) >= 2) {
                return trim($parts[count($parts) - 1]);
            }
        }
        
        return 'Google Scholar';
    }
    
    /**
     * Determine publication type based on available information
     */
    private function determinePublicationType($pub) {
        $title = strtolower($pub['title'] ?? '');
        $venue = strtolower($this->extractVenue($pub));
        
        // Check for book indicators
        if (strpos($venue, 'springer') !== false || 
            strpos($venue, 'book') !== false ||
            strpos($title, 'volume') !== false) {
            return 'book';
        }
        
        // Check for conference indicators
        if (strpos($venue, 'conference') !== false ||
            strpos($venue, 'proceedings') !== false ||
            strpos($venue, 'workshop') !== false) {
            return 'conference';
        }
        
        // Default to journal
        return 'journal';
    }
}

// Run the fetcher
$fetcher = new SerpApiPublicationFetcher();
$fetcher->run();
