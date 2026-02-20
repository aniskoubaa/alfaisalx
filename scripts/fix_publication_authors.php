<?php
/**
 * AlfaisalX Publication Authors Fixer
 * 
 * This script fetches full author names from publication URLs
 * and updates the database with complete author lists.
 * 
 * Usage: php fix_publication_authors.php
 * Or visit: http://localhost/alfaisalx/scripts/fix_publication_authors.php
 */

// Prevent timeout
set_time_limit(0);
ini_set('max_execution_time', 0);

require_once __DIR__ . '/../database/Database.php';

class PublicationAuthorFixer {
    private $db;
    private $userAgent = 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36';
    
    public function __construct() {
        $this->db = Database::getInstance();
    }
    
    /**
     * Main execution method
     */
    public function run() {
        echo "<pre>";
        echo "=== AlfaisalX Publication Authors Fixer ===\n\n";
        
        // Get publications with truncated authors (containing ...)
        $publications = $this->getPublicationsWithTruncatedAuthors();
        
        echo "Found " . count($publications) . " publications with truncated authors\n\n";
        
        $fixedCount = 0;
        $errorCount = 0;
        
        foreach ($publications as $pub) {
            echo str_repeat("-", 70) . "\n";
            echo "ID: {$pub['id']}\n";
            echo "Title: " . substr($pub['title'], 0, 60) . "...\n";
            echo "Current Authors: {$pub['authors']}\n";
            echo "URL: {$pub['url']}\n";
            
            if (empty($pub['url'])) {
                echo "⚠ No URL available, skipping\n\n";
                continue;
            }
            
            $authors = $this->fetchAuthorsFromUrl($pub['url']);
            
            if ($authors && $authors !== $pub['authors']) {
                // Update the database
                $this->updateAuthors($pub['id'], $authors);
                echo "✓ Updated to: {$authors}\n\n";
                $fixedCount++;
            } else if ($authors === $pub['authors']) {
                echo "- Authors already correct\n\n";
            } else {
                echo "✗ Could not fetch authors\n\n";
                $errorCount++;
            }
            
            // Sleep to avoid rate limiting
            sleep(2);
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
     * Fetch authors from the publication URL
     */
    private function fetchAuthorsFromUrl($url) {
        $html = $this->fetchUrl($url);
        
        if (!$html) {
            return null;
        }
        
        // Detect source and extract authors
        if (strpos($url, 'ieeexplore.ieee.org') !== false) {
            return $this->extractIEEEAuthors($html);
        } elseif (strpos($url, 'springer') !== false || strpos($url, 'link.springer') !== false) {
            return $this->extractSpringerAuthors($html);
        } elseif (strpos($url, 'aclanthology') !== false) {
            return $this->extractACLAuthors($html);
        } elseif (strpos($url, 'sciencedirect') !== false || strpos($url, 'elsevier') !== false) {
            return $this->extractElsevierAuthors($html);
        } elseif (strpos($url, 'mdpi.com') !== false) {
            return $this->extractMDPIAuthors($html);
        } elseif (strpos($url, 'arxiv.org') !== false) {
            return $this->extractArxivAuthors($html);
        } elseif (strpos($url, 'researchgate.net') !== false) {
            return $this->extractResearchGateAuthors($html);
        } elseif (strpos($url, 'scholar.google') !== false) {
            // Can't easily extract from Google Scholar, skip
            return null;
        } else {
            // Try generic meta tag extraction
            return $this->extractGenericAuthors($html);
        }
    }
    
    /**
     * Fetch URL content using cURL
     */
    private function fetchUrl($url) {
        $ch = curl_init();
        
        curl_setopt_array($ch, [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_USERAGENT => $this->userAgent,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_HTTPHEADER => [
                'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8',
                'Accept-Language: en-US,en;q=0.5',
            ]
        ]);
        
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        
        if ($httpCode !== 200 || !$response) {
            echo "  HTTP Error: {$httpCode}\n";
            return null;
        }
        
        return $response;
    }
    
    /**
     * Extract authors from IEEE Xplore
     */
    private function extractIEEEAuthors($html) {
        // IEEE uses JavaScript to load authors, so we look for JSON data
        // Look for authors in the meta tags
        if (preg_match_all('/<meta property="article:author" content="([^"]+)"/i', $html, $matches)) {
            if (!empty($matches[1])) {
                return implode(', ', $matches[1]);
            }
        }
        
        // Try to find authors in JSON-LD
        if (preg_match('/"author"\s*:\s*\[(.*?)\]/s', $html, $matches)) {
            if (preg_match_all('/"name"\s*:\s*"([^"]+)"/i', $matches[1], $authorMatches)) {
                return implode(', ', $authorMatches[1]);
            }
        }
        
        // Try citation_author meta tags
        if (preg_match_all('/<meta name="citation_author" content="([^"]+)"/i', $html, $matches)) {
            if (!empty($matches[1])) {
                return implode(', ', $matches[1]);
            }
        }
        
        return null;
    }
    
    /**
     * Extract authors from Springer
     */
    private function extractSpringerAuthors($html) {
        // Springer uses citation_author meta tags
        if (preg_match_all('/<meta name="citation_author" content="([^"]+)"/i', $html, $matches)) {
            if (!empty($matches[1])) {
                return implode(', ', $matches[1]);
            }
        }
        
        // Try dc.creator
        if (preg_match_all('/<meta name="dc\.creator" content="([^"]+)"/i', $html, $matches)) {
            if (!empty($matches[1])) {
                return implode(', ', $matches[1]);
            }
        }
        
        return null;
    }
    
    /**
     * Extract authors from ACL Anthology
     */
    private function extractACLAuthors($html) {
        // ACL uses citation_author meta tags
        if (preg_match_all('/<meta name="citation_author" content="([^"]+)"/i', $html, $matches)) {
            if (!empty($matches[1])) {
                return implode(', ', $matches[1]);
            }
        }
        
        return null;
    }
    
    /**
     * Extract authors from Elsevier/ScienceDirect
     */
    private function extractElsevierAuthors($html) {
        if (preg_match_all('/<meta name="citation_author" content="([^"]+)"/i', $html, $matches)) {
            if (!empty($matches[1])) {
                return implode(', ', $matches[1]);
            }
        }
        
        return null;
    }
    
    /**
     * Extract authors from MDPI
     */
    private function extractMDPIAuthors($html) {
        if (preg_match_all('/<meta name="citation_author" content="([^"]+)"/i', $html, $matches)) {
            if (!empty($matches[1])) {
                return implode(', ', $matches[1]);
            }
        }
        
        return null;
    }
    
    /**
     * Extract authors from arXiv
     */
    private function extractArxivAuthors($html) {
        if (preg_match_all('/<meta name="citation_author" content="([^"]+)"/i', $html, $matches)) {
            if (!empty($matches[1])) {
                return implode(', ', $matches[1]);
            }
        }
        
        return null;
    }
    
    /**
     * Extract authors from ResearchGate
     */
    private function extractResearchGateAuthors($html) {
        // ResearchGate uses citation_author meta tags
        if (preg_match_all('/<meta name="citation_author" content="([^"]+)"/i', $html, $matches)) {
            if (!empty($matches[1])) {
                return implode(', ', $matches[1]);
            }
        }
        
        return null;
    }
    
    /**
     * Generic author extraction using common meta tags
     */
    private function extractGenericAuthors($html) {
        // Try citation_author first (most common)
        if (preg_match_all('/<meta name="citation_author" content="([^"]+)"/i', $html, $matches)) {
            if (!empty($matches[1])) {
                return implode(', ', $matches[1]);
            }
        }
        
        // Try DC.creator
        if (preg_match_all('/<meta name="DC\.creator" content="([^"]+)"/i', $html, $matches)) {
            if (!empty($matches[1])) {
                return implode(', ', $matches[1]);
            }
        }
        
        // Try author meta tag
        if (preg_match('/<meta name="author" content="([^"]+)"/i', $html, $matches)) {
            if (!empty($matches[1])) {
                return $matches[1];
            }
        }
        
        // Try og:article:author
        if (preg_match_all('/<meta property="og:article:author" content="([^"]+)"/i', $html, $matches)) {
            if (!empty($matches[1])) {
                return implode(', ', $matches[1]);
            }
        }
        
        return null;
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
$fixer = new PublicationAuthorFixer();
$fixer->run();
