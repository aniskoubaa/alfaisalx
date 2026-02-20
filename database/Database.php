<?php
/**
 * AlfaisalX Database Connection Class
 * 
 * Simple SQLite database wrapper for the website.
 */

class Database {
    private static $instance = null;
    private $pdo;
    private $dbPath;

    private function __construct() {
        $this->dbPath = __DIR__ . '/alfaisalx.db';
        
        try {
            $this->pdo = new PDO('sqlite:' . $this->dbPath);
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            die("Database connection failed: " . $e->getMessage());
        }
    }

    /**
     * Get singleton instance
     */
    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Get PDO connection
     */
    public function getConnection() {
        return $this->pdo;
    }

    /**
     * Execute a query and return results
     */
    public function query($sql, $params = []) {
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    /**
     * Execute a query and return single row
     */
    public function queryOne($sql, $params = []) {
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetch();
    }

    /**
     * Execute an insert/update/delete
     */
    public function execute($sql, $params = []) {
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute($params);
    }

    /**
     * Get last insert ID
     */
    public function lastInsertId() {
        return $this->pdo->lastInsertId();
    }

    // ============================================================
    // HELPER METHODS FOR COMMON QUERIES
    // ============================================================

    /**
     * Get site settings
     */
    public function getSetting($key) {
        $result = $this->queryOne("SELECT value FROM settings WHERE key = ?", [$key]);
        return $result ? $result['value'] : null;
    }

    /**
     * Get all settings as associative array
     */
    public function getAllSettings() {
        $results = $this->query("SELECT key, value FROM settings");
        $settings = [];
        foreach ($results as $row) {
            $settings[$row['key']] = $row['value'];
        }
        return $settings;
    }

    /**
     * Get team members by category
     */
    public function getTeamByCategory($category = null) {
        if ($category) {
            return $this->query(
                "SELECT * FROM team_members WHERE category = ? AND is_active = 1 ORDER BY sort_order",
                [$category]
            );
        }
        return $this->query("SELECT * FROM team_members WHERE is_active = 1 ORDER BY category, sort_order");
    }

    /**
     * Get research areas
     */
    public function getResearchAreas() {
        return $this->query("SELECT * FROM research_areas WHERE is_active = 1 ORDER BY sort_order");
    }

    /**
     * Get research area by slug
     */
    public function getResearchAreaBySlug($slug) {
        return $this->queryOne("SELECT * FROM research_areas WHERE slug = ?", [$slug]);
    }

    /**
     * Get objectives
     */
    public function getObjectives() {
        return $this->query("SELECT * FROM objectives ORDER BY sort_order");
    }

    /**
     * Get partners by type
     */
    public function getPartnersByType($type = null) {
        if ($type) {
            return $this->query(
                "SELECT * FROM partners WHERE type = ? AND is_active = 1 ORDER BY sort_order",
                [$type]
            );
        }
        return $this->query("SELECT * FROM partners WHERE is_active = 1 ORDER BY type, sort_order");
    }

    /**
     * Get labs/units
     */
    public function getLabs() {
        return $this->query("SELECT * FROM labs WHERE is_active = 1 ORDER BY sort_order");
    }

    /**
     * Get lab by slug
     */
    public function getLabBySlug($slug) {
        return $this->queryOne("SELECT * FROM labs WHERE slug = ?", [$slug]);
    }

    /**
     * Get projects
     */
    public function getProjects($featured = null) {
        if ($featured !== null) {
            return $this->query(
                "SELECT * FROM projects WHERE is_featured = ? ORDER BY sort_order",
                [$featured ? 1 : 0]
            );
        }
        return $this->query("SELECT * FROM projects ORDER BY sort_order");
    }

    /**
     * Get publications
     */
    public function getPublications($limit = null, $year = 2025) {
        $sql = "SELECT * FROM publications";
        $params = [];
        
        if ($year) {
            $sql .= " WHERE year = ?";
            $params[] = $year;
        }
        
        $sql .= " ORDER BY year DESC, title ASC";
        
        if ($limit) {
            $sql .= " LIMIT " . intval($limit);
        }
        
        return $this->query($sql, $params);
    }

    /**
     * Get news/events
     */
    public function getNews($limit = null, $type = null) {
        $sql = "SELECT * FROM news WHERE is_published = 1";
        $params = [];
        
        if ($type) {
            $sql .= " AND type = ?";
            $params[] = $type;
        }
        
        $sql .= " ORDER BY date DESC";
        
        if ($limit) {
            $sql .= " LIMIT " . intval($limit);
        }
        
        return $this->query($sql, $params);
    }

    /**
     * Get strategic initiatives
     */
    public function getStrategicInitiatives() {
        return $this->query("SELECT * FROM strategic_initiatives ORDER BY sort_order");
    }

    /**
     * Get application sectors
     */
    public function getApplicationSectors() {
        return $this->query("SELECT * FROM application_sectors ORDER BY sort_order");
    }

    /**
     * Get KPIs
     */
    public function getKPIs() {
        return $this->query("SELECT * FROM kpis ORDER BY category, sort_order");
    }

    /**
     * Get infrastructure items
     */
    public function getInfrastructure() {
        return $this->query("SELECT * FROM infrastructure ORDER BY sort_order");
    }

    /**
     * Get navigation items
     */
    public function getNavigation() {
        return $this->query("SELECT * FROM navigation WHERE is_active = 1 ORDER BY sort_order");
    }

    /**
     * Get stats for homepage
     */
    public function getStats() {
        return $this->query("SELECT * FROM stats ORDER BY sort_order");
    }

    /**
     * Get job listings
     */
    public function getJobs($active_only = true) {
        if ($active_only) {
            return $this->query("SELECT * FROM jobs WHERE is_active = 1 ORDER BY is_featured DESC, sort_order");
        }
        return $this->query("SELECT * FROM jobs ORDER BY is_featured DESC, sort_order");
    }

    /**
     * Get single job by slug
     */
    public function getJob($slug) {
        return $this->queryOne("SELECT * FROM jobs WHERE slug = ?", [$slug]);
    }
}





