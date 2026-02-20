<?php
/**
 * OpenAI API Helper Class for AlfaisalX
 * 
 * Provides methods to call OpenAI API for various tasks including
 * formatting publication authors in IEEE format.
 */

class OpenAIHelper {
    private $apiKey;
    private $baseUrl = 'https://api.openai.com/v1/chat/completions';
    private $model = 'gpt-4o-mini'; // Cost-effective model for simple tasks
    
    public function __construct($apiKey = null) {
        if ($apiKey) {
            $this->apiKey = $apiKey;
        } else {
            $this->loadApiKey();
        }
    }
    
    /**
     * Load API key from .env file
     */
    private function loadApiKey() {
        $envPath = __DIR__ . '/../.env';
        
        if (!file_exists($envPath)) {
            throw new Exception('.env file not found');
        }
        
        $lines = file($envPath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        foreach ($lines as $line) {
            if (strpos(trim($line), '#') === 0) continue;
            if (strpos($line, '=') !== false) {
                list($key, $value) = explode('=', $line, 2);
                if (trim($key) === 'OPENAI_API_KEY') {
                    $this->apiKey = trim($value);
                    return;
                }
            }
        }
        
        throw new Exception('OPENAI_API_KEY not found in .env file');
    }
    
    /**
     * Make a chat completion request
     */
    public function chat($messages, $options = []) {
        $payload = [
            'model' => $options['model'] ?? $this->model,
            'messages' => $messages,
            'temperature' => $options['temperature'] ?? 0.1,
            'max_tokens' => $options['max_tokens'] ?? 500
        ];
        
        $ch = curl_init();
        curl_setopt_array($ch, [
            CURLOPT_URL => $this->baseUrl,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_HTTPHEADER => [
                'Content-Type: application/json',
                'Authorization: Bearer ' . $this->apiKey
            ],
            CURLOPT_POSTFIELDS => json_encode($payload),
            CURLOPT_TIMEOUT => 60
        ]);
        
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        
        if ($httpCode !== 200) {
            $error = json_decode($response, true);
            throw new Exception('OpenAI API Error: ' . ($error['error']['message'] ?? 'Unknown error'));
        }
        
        $data = json_decode($response, true);
        return $data['choices'][0]['message']['content'] ?? '';
    }
    
    /**
     * Format authors in IEEE format
     * 
     * @param string $rawAuthors The raw author string from Google Scholar
     * @param string $title The publication title (for context)
     * @return string Authors formatted in IEEE style
     */
    public function formatAuthorsIEEE($rawAuthors, $title = '') {
        // Skip if authors look already formatted or are simple
        if (strpos($rawAuthors, '…') === false && strpos($rawAuthors, '...') === false) {
            // Check if it's a simple clean name
            $parts = preg_split('/[,\s]+and\s+/', $rawAuthors);
            if (count($parts) <= 5 && !preg_match('/[A-Z]\s+[A-Z][a-z]/', $rawAuthors)) {
                // Already looks like full names format
                return $this->cleanAndFormatIEEE($rawAuthors);
            }
        }
        
        $prompt = "Format these author names in IEEE citation style. IEEE format uses: First name initial(s). Last name (e.g., 'A. Koubaa', 'J. Smith'). 

Rules:
1. If authors are truncated (contain '…' or '...'), extract all visible names and format them properly
2. Use initials for first/middle names, full last name
3. Separate authors with commas, use 'and' before the last author
4. Remove titles like 'Dr.', 'Prof.', etc.
5. If only initials are given, keep them as is but add periods
6. Output ONLY the formatted author string, nothing else

Input authors: $rawAuthors

Output:";

        try {
            $result = $this->chat([
                ['role' => 'system', 'content' => 'You are an academic citation formatter. Output only the formatted author string, no explanations.'],
                ['role' => 'user', 'content' => $prompt]
            ], ['temperature' => 0.0, 'max_tokens' => 200]);
            
            return trim($result);
        } catch (Exception $e) {
            // Fallback to basic cleaning if API fails
            return $this->cleanAndFormatIEEE($rawAuthors);
        }
    }
    
    /**
     * Format a complete publication in IEEE citation format
     * 
     * @param array $pub Publication data with title, authors, venue, year
     * @return string Complete IEEE formatted citation
     */
    public function formatPublicationIEEE($pub) {
        $authors = $pub['authors'] ?? 'Unknown';
        $title = $pub['title'] ?? 'Untitled';
        $venue = $pub['venue'] ?? '';
        $year = $pub['year'] ?? '';
        $doi = $pub['doi'] ?? '';
        
        // Format authors
        $formattedAuthors = $this->formatAuthorsIEEE($authors, $title);
        
        // Build IEEE format: Authors, "Title," Venue, Year.
        $citation = $formattedAuthors . ', "' . $title . ',"';
        
        if ($venue) {
            $citation .= ' ' . $venue . ',';
        }
        
        if ($year) {
            $citation .= ' ' . $year;
        }
        
        $citation .= '.';
        
        if ($doi) {
            $citation .= ' DOI: ' . $doi;
        }
        
        return $citation;
    }
    
    /**
     * Basic cleaning and IEEE formatting without API
     */
    private function cleanAndFormatIEEE($authors) {
        // Remove ellipsis
        $authors = str_replace(['…', '...'], '', $authors);
        
        // Remove leading/trailing commas and spaces
        $authors = trim($authors, ', ');
        
        // Remove titles
        $authors = preg_replace('/\b(Dr\.|Prof\.|Eng\.)\s*/i', '', $authors);
        
        // Clean up multiple spaces
        $authors = preg_replace('/\s+/', ' ', $authors);
        
        // Clean up multiple commas
        $authors = preg_replace('/,\s*,/', ',', $authors);
        
        return trim($authors);
    }
    
    /**
     * Extract full author list from a publication URL with LLM help
     * 
     * @param string $url The publication URL
     * @param string $currentAuthors Current truncated authors
     * @return string|null Full author list or null if failed
     */
    public function extractAuthorsFromUrl($url, $currentAuthors) {
        // Fetch the page content
        $html = $this->fetchUrl($url);
        if (!$html) {
            return null;
        }
        
        // Extract potential author sections from HTML
        $authorSection = $this->extractAuthorSection($html);
        if (!$authorSection) {
            return null;
        }
        
        // Use LLM to extract and format authors
        $prompt = "Extract and format all author names from this text in IEEE citation format.

Text from publication page:
$authorSection

Current (truncated) author list: $currentAuthors

Rules:
1. Find ALL author names in the text
2. Format as: First initial(s). Last name (e.g., 'A. Koubaa')
3. Separate with commas, 'and' before last author
4. Remove affiliations and other metadata
5. Output ONLY the formatted author string

Authors:";

        try {
            $result = $this->chat([
                ['role' => 'system', 'content' => 'You are an academic citation formatter. Extract author names and output only the IEEE formatted author string.'],
                ['role' => 'user', 'content' => $prompt]
            ], ['temperature' => 0.0, 'max_tokens' => 300]);
            
            return trim($result);
        } catch (Exception $e) {
            return null;
        }
    }
    
    /**
     * Fetch URL content
     */
    private function fetchUrl($url) {
        $ch = curl_init();
        curl_setopt_array($ch, [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_USERAGENT => 'Mozilla/5.0 (compatible; Academic Research Bot)',
            CURLOPT_SSL_VERIFYPEER => false
        ]);
        
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        
        return $httpCode === 200 ? $response : null;
    }
    
    /**
     * Extract author section from HTML
     */
    private function extractAuthorSection($html) {
        // Try common meta tags first
        $patterns = [
            '/<meta name="citation_author" content="([^"]+)"/i',
            '/<meta name="author" content="([^"]+)"/i',
            '/<meta name="dc\.creator" content="([^"]+)"/i',
        ];
        
        foreach ($patterns as $pattern) {
            if (preg_match_all($pattern, $html, $matches)) {
                if (!empty($matches[1])) {
                    return implode(', ', $matches[1]);
                }
            }
        }
        
        // Try to find author divs (limit to 2000 chars)
        if (preg_match('/<div[^>]*class="[^"]*author[^"]*"[^>]*>(.*?)<\/div>/is', $html, $matches)) {
            return substr(strip_tags($matches[1]), 0, 2000);
        }
        
        return null;
    }
}
