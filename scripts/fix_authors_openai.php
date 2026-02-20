<?php
/**
 * AlfaisalX Publication Authors Fixer - OpenAI LLM Edition
 * 
 * This script uses OpenAI to format all publication authors in IEEE format.
 * It processes publications with truncated or poorly formatted authors and
 * updates them in the database.
 * 
 * Usage: php fix_authors_openai.php
 * Or visit: http://localhost/alfaisalx/scripts/fix_authors_openai.php
 */

// Prevent timeout
set_time_limit(0);
ini_set('max_execution_time', 0);

require_once __DIR__ . '/../database/Database.php';
require_once __DIR__ . '/../includes/OpenAIHelper.php';

class OpenAIAuthorFixer {
    private $db;
    private $openai;
    private $processedCount = 0;
    private $fixedCount = 0;
    private $errorCount = 0;
    
    public function __construct() {
        $this->db = Database::getInstance();
        try {
            $this->openai = new OpenAIHelper();
        } catch (Exception $e) {
            die("<pre>Error: " . $e->getMessage() . "\n\nPlease add OPENAI_API_KEY to your .env file.</pre>");
        }
    }
    
    /**
     * Main execution method
     */
    public function run() {
        echo "<pre>";
        echo "=================================================================\n";
        echo "   AlfaisalX Publication Authors Fixer (OpenAI LLM Edition)\n";
        echo "=================================================================\n\n";
        
        // Get all 2025 publications
        $publications = $this->getPublications();
        
        echo "Found " . count($publications) . " publications to process\n\n";
        
        foreach ($publications as $pub) {
            $this->processedCount++;
            $this->processPublication($pub);
            
            // Rate limiting - OpenAI has limits
            usleep(500000); // 0.5 second delay
        }
        
        echo "\n=================================================================\n";
        echo "                        SUMMARY\n";
        echo "=================================================================\n";
        echo "Processed: {$this->processedCount}\n";
        echo "Fixed: {$this->fixedCount}\n";
        echo "Errors: {$this->errorCount}\n";
        echo "</pre>";
    }
    
    /**
     * Get publications to process
     */
    private function getPublications() {
        return $this->db->query(
            "SELECT id, title, authors, venue, year, url 
             FROM publications 
             WHERE year = 2025 
             ORDER BY id ASC"
        );
    }
    
    /**
     * Process a single publication
     */
    private function processPublication($pub) {
        echo str_repeat("-", 70) . "\n";
        echo "[{$this->processedCount}] ID: {$pub['id']}\n";
        echo "Title: " . $this->truncate($pub['title'], 60) . "...\n";
        echo "Current: {$pub['authors']}\n";
        
        // Check if authors need fixing
        $needsFix = $this->needsFormatting($pub['authors']);
        
        if (!$needsFix) {
            echo "Status: Already formatted ✓\n\n";
            return;
        }
        
        try {
            // Try to get full authors from URL first if truncated
            $fullAuthors = null;
            if ($this->isTruncated($pub['authors']) && !empty($pub['url'])) {
                echo "Attempting to fetch full authors from URL...\n";
                $fullAuthors = $this->openai->extractAuthorsFromUrl($pub['url'], $pub['authors']);
            }
            
            // Format authors with OpenAI
            $authorsToFormat = $fullAuthors ?: $pub['authors'];
            $formattedAuthors = $this->openai->formatAuthorsIEEE($authorsToFormat, $pub['title']);
            
            if ($formattedAuthors && $formattedAuthors !== $pub['authors']) {
                // Update database
                $this->updateAuthors($pub['id'], $formattedAuthors);
                echo "Updated: {$formattedAuthors}\n";
                echo "Status: Fixed ✓\n\n";
                $this->fixedCount++;
            } else {
                echo "Status: No changes needed\n\n";
            }
        } catch (Exception $e) {
            echo "Error: " . $e->getMessage() . "\n";
            echo "Status: Error ✗\n\n";
            $this->errorCount++;
        }
    }
    
    /**
     * Check if authors need formatting
     */
    private function needsFormatting($authors) {
        // Needs formatting if:
        // 1. Contains truncation indicators
        if ($this->isTruncated($authors)) {
            return true;
        }
        
        // 2. Contains "Dr." or "Prof." titles
        if (preg_match('/\b(Dr\.|Prof\.|Eng\.)/i', $authors)) {
            return true;
        }
        
        // 3. First names are not abbreviated (e.g., "Anis Koubaa" instead of "A. Koubaa")
        // Check for pattern like "Full Name Lastname"
        if (preg_match('/[A-Z][a-z]{2,}\s+[A-Z][a-z]+/', $authors)) {
            return true;
        }
        
        return false;
    }
    
    /**
     * Check if authors string is truncated
     */
    private function isTruncated($authors) {
        return strpos($authors, '…') !== false || strpos($authors, '...') !== false;
    }
    
    /**
     * Update authors in database
     */
    private function updateAuthors($id, $authors) {
        return $this->db->execute(
            "UPDATE publications SET authors = ? WHERE id = ?",
            [$authors, $id]
        );
    }
    
    /**
     * Truncate string
     */
    private function truncate($str, $len) {
        return strlen($str) > $len ? substr($str, 0, $len) : $str;
    }
}

// Run the fixer
$fixer = new OpenAIAuthorFixer();
$fixer->run();
