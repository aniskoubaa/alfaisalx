<?php
/**
 * AlfaisalX Publication Authors Fixer - Semantic Scholar Edition
 * 
 * This script uses the free Semantic Scholar API to fetch full author names
 * by searching for paper titles.
 * 
 * Usage: php fix_authors_semantic_scholar.php
 * Or visit: http://localhost/alfaisalx/scripts/fix_authors_semantic_scholar.php
 */

// Prevent timeout
set_time_limit(0);
ini_set('max_execution_time', 0);

require_once __DIR__ . '/../database/Database.php';

class SemanticScholarAuthorFixer {
    private $db;
    private $apiUrl = 'https://api.semanticscholar.org/graph/v1/paper/search';
    private $userAgent = 'AlfaisalX/1.0 (Academic Research Portal)';
    
    public function __construct() {
        $this->db = Database::getInstance();
    }
    
    /**
     * Main execution method
     */
    public function run() {
        echo "<pre>";
        echo "=== AlfaisalX Authors Fixer (Semantic Scholar) ===\n\n";
        
        // Get publications with truncated authors
        $publications = $this->getPublicationsWithTruncatedAuthors();
        
        echo "Found " . count($publications) . " publications with truncated authors\n\n";
        
        $fixedCount = 0;
        $errorCount = 0;
        
        foreach ($publications as $pub) {
            echo str_repeat("-", 70) . "\n";
            echo "ID: {$pub['id']}\n";
            echo "Title: " . substr($pub['title'], 0, 60) . "...\n";
            echo "Current Authors: {$pub['authors']}\n";
            
            $authors = $this->fetchAuthorsFromSemanticScholar($pub['title']);
            
            if ($authors && $authors !== $pub['authors']) {
                // Only update if the new author string is longer/more complete
                if (strlen($authors) > strlen($pub['authors'])) {
                    $this->updateAuthors($pub['id'], $authors);
                    echo "✓ Updated to: {$authors}\n\n";
                    $fixedCount++;
                } else {
                    echo "- New data not better, skipping\n\n";
                }
            } else if (!$authors) {
                echo "✗ Could not find in Semantic Scholar\n\n";
                $errorCount++;
            } else {
                echo "- Authors already correct\n\n";
            }
            
            // Sleep to respect rate limits (100 requests/5 minutes for free tier)
            sleep(3);
        }
        
        echo str_repeat("=", 70) . "\n";
        echo "=== Fix Complete! ===\n";
        echo "Fixed: {$fixedCount} | Errors: {$errorCount}\n";
        echo "</pre>";
    }
    
    /**
     * Get publications with truncated author lists
     */
    private function getPublicationsWithTruncatedAuthors() {
        return $this->db->query(
            "SELECT id, title, authors, url 
             FROM publications 
             WHERE year = 2025 
             AND (authors LIKE '%…%' OR authors LIKE '%...%')
             ORDER BY id ASC"
        );
    }
    
    /**
     * Fetch authors from Semantic Scholar API
     */
    private function fetchAuthorsFromSemanticScholar($title) {
        // Clean the title for search
        $cleanTitle = $this->cleanTitle($title);
        
        $params = [
            'query' => $cleanTitle,
            'fields' => 'title,authors',
            'limit' => 5
        ];
        
        $url = $this->apiUrl . '?' . http_build_query($params);
        
        $ch = curl_init();
        curl_setopt_array($ch, [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_USERAGENT => $this->userAgent,
            CURLOPT_HTTPHEADER => [
                'Accept: application/json'
            ]
        ]);
        
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        
        if ($httpCode !== 200 || !$response) {
            echo "  API Error: HTTP {$httpCode}\n";
            return null;
        }
        
        $data = json_decode($response, true);
        
        if (!isset($data['data']) || empty($data['data'])) {
            return null;
        }
        
        // Find the best match by title similarity
        $bestMatch = null;
        $bestScore = 0;
        
        foreach ($data['data'] as $paper) {
            $score = $this->titleSimilarity($cleanTitle, $paper['title'] ?? '');
            if ($score > $bestScore && $score > 0.7) {
                $bestScore = $score;
                $bestMatch = $paper;
            }
        }
        
        if (!$bestMatch || !isset($bestMatch['authors'])) {
            return null;
        }
        
        // Extract author names
        $authorNames = [];
        foreach ($bestMatch['authors'] as $author) {
            if (isset($author['name'])) {
                $authorNames[] = $author['name'];
            }
        }
        
        if (empty($authorNames)) {
            return null;
        }
        
        return implode(', ', $authorNames);
    }
    
    /**
     * Clean title for search
     */
    private function cleanTitle($title) {
        // Remove special characters but keep basic punctuation
        $title = preg_replace('/[^\w\s\-:,.]/', ' ', $title);
        // Collapse multiple spaces
        $title = preg_replace('/\s+/', ' ', $title);
        return trim($title);
    }
    
    /**
     * Calculate title similarity (Jaccard similarity)
     */
    private function titleSimilarity($title1, $title2) {
        $words1 = array_filter(array_map('strtolower', explode(' ', $title1)));
        $words2 = array_filter(array_map('strtolower', explode(' ', $title2)));
        
        if (empty($words1) || empty($words2)) {
            return 0;
        }
        
        $intersection = count(array_intersect($words1, $words2));
        $union = count(array_unique(array_merge($words1, $words2)));
        
        return $union > 0 ? $intersection / $union : 0;
    }
    
    /**
     * Update authors in the database
     */
    private function updateAuthors($id, $authors) {
        return $this->db->execute(
            "UPDATE publications SET authors = ? WHERE id = ?",
            [$authors, $id]
        );
    }
}

// Run the fixer
$fixer = new SemanticScholarAuthorFixer();
$fixer->run();
