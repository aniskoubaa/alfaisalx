<?php
/**
 * AlfaisalX Admin - Google Scholar Publication Fetcher
 * Clean, simple admin interface for fetching publications.
 */

session_start();
require_once '../includes/config.php';
require_once '../database/Database.php';

$ADMIN_EMAIL = 'akoubaa@alfaisal.edu';
$authenticated = isset($_SESSION['admin_email']) && $_SESSION['admin_email'] === $ADMIN_EMAIL;

// Handle login
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'login') {
    if (trim($_POST['email'] ?? '') === $ADMIN_EMAIL) {
        $_SESSION['admin_email'] = $ADMIN_EMAIL;
        $authenticated = true;
    }
}

// Handle logout
if (isset($_GET['logout'])) {
    session_destroy();
    header('Location: fetch_publications.php');
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
            if (trim($key) === 'SERPAPI_API_KEY') return trim($value);
        }
    }
    return null;
}

$apiKey = getApiKey();
$db = Database::getInstance();
$teamMembers = $db->query("SELECT id, name, google_scholar, is_active FROM team_members ORDER BY sort_order, name");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Publication Fetcher - AlfaisalX Admin</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        /* Reset and Base */
        *, *::before, *::after {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }
        
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background: #0f172a;
            color: #e2e8f0;
            line-height: 1.6;
            min-height: 100vh;
        }
        
        /* Simple Header */
        .header {
            background: #1e293b;
            padding: 16px 24px;
            border-bottom: 1px solid #334155;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .header-title {
            font-size: 20px;
            font-weight: 700;
            color: #38bdf8;
        }
        
        .header-title i {
            margin-right: 8px;
        }
        
        .logout-btn {
            color: #94a3b8;
            text-decoration: none;
            font-size: 14px;
        }
        
        /* Container */
        .container {
            max-width: 1000px;
            margin: 0 auto;
            padding: 32px 24px;
        }
        
        /* Cards */
        .card {
            background: #1e293b;
            border: 1px solid #334155;
            border-radius: 12px;
            padding: 24px;
            margin-bottom: 24px;
        }
        
        .card-title {
            font-size: 18px;
            font-weight: 600;
            margin-bottom: 20px;
            color: #f1f5f9;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        
        .card-title i {
            color: #38bdf8;
        }
        
        /* Form Elements */
        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 16px;
            margin-bottom: 20px;
        }
        
        @media (max-width: 600px) {
            .form-row { grid-template-columns: 1fr; }
        }
        
        .form-group {
            margin-bottom: 20px;
        }
        
        .form-label {
            display: block;
            font-size: 14px;
            font-weight: 500;
            color: #cbd5e1;
            margin-bottom: 8px;
        }
        
        .form-hint {
            font-size: 12px;
            color: #64748b;
            margin-top: 4px;
        }
        
        .form-input, .form-select {
            width: 100%;
            padding: 12px 16px;
            background: #0f172a;
            border: 1px solid #475569;
            border-radius: 8px;
            color: #f1f5f9;
            font-size: 15px;
        }
        
        .form-input:focus, .form-select:focus {
            outline: 2px solid #38bdf8;
            outline-offset: 1px;
        }
        
        /* Researcher Grid */
        .researcher-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: 12px;
        }
        
        .researcher-item {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 14px 16px;
            background: #0f172a;
            border: 2px solid #475569;
            border-radius: 8px;
            cursor: pointer;
        }
        
        .researcher-item.selected {
            border-color: #38bdf8;
            background: #0c4a6e;
        }
        
        .researcher-item input[type="checkbox"] {
            width: 20px;
            height: 20px;
            cursor: pointer;
            accent-color: #38bdf8;
        }
        
        .researcher-name {
            font-size: 14px;
            font-weight: 500;
            color: #f1f5f9;
        }
        
        .researcher-note {
            font-size: 11px;
            color: #64748b;
            display: block;
        }
        
        .select-all-btn {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 10px 16px;
            background: #0ea5e9;
            color: white;
            border: none;
            border-radius: 6px;
            font-size: 14px;
            font-weight: 500;
            cursor: pointer;
            margin-bottom: 16px;
        }
        
        /* Buttons */
        .btn-row {
            display: flex;
            gap: 12px;
            flex-wrap: wrap;
        }
        
        .btn {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 12px 24px;
            border-radius: 8px;
            font-size: 15px;
            font-weight: 600;
            cursor: pointer;
            text-decoration: none;
            border: none;
        }
        
        .btn-primary {
            background: linear-gradient(135deg, #0ea5e9, #8b5cf6);
            color: white;
        }
        
        .btn-primary:disabled {
            opacity: 0.5;
            cursor: not-allowed;
        }
        
        .btn-secondary {
            background: #334155;
            color: #f1f5f9;
            border: 1px solid #475569;
        }
        
        .btn-success {
            background: #10b981;
            color: white;
        }
        
        .btn-success:disabled {
            opacity: 0.5;
            cursor: not-allowed;
        }
        
        /* Alerts */
        .alert {
            padding: 14px 18px;
            border-radius: 8px;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 14px;
        }
        
        .alert-error {
            background: rgba(239, 68, 68, 0.15);
            border: 1px solid #ef4444;
            color: #fca5a5;
        }
        
        .alert-success {
            background: rgba(16, 185, 129, 0.15);
            border: 1px solid #10b981;
            color: #6ee7b7;
        }
        
        .alert-warning {
            background: rgba(245, 158, 11, 0.15);
            border: 1px solid #f59e0b;
            color: #fcd34d;
        }
        
        .alert-info {
            background: rgba(14, 165, 233, 0.15);
            border: 1px solid #0ea5e9;
            color: #7dd3fc;
        }
        
        /* Progress */
        .progress-container {
            margin-bottom: 20px;
        }
        
        .progress-bar {
            height: 6px;
            background: #334155;
            border-radius: 3px;
            overflow: hidden;
            margin-bottom: 8px;
        }
        
        .progress-fill {
            height: 100%;
            background: linear-gradient(90deg, #0ea5e9, #8b5cf6);
            width: 0%;
        }
        
        .progress-text {
            font-size: 13px;
            color: #94a3b8;
            text-align: center;
        }
        
        /* Results */
        .results-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 12px;
            margin-bottom: 16px;
            padding-bottom: 16px;
            border-bottom: 1px solid #334155;
        }
        
        .stats {
            display: flex;
            gap: 16px;
        }
        
        .stat {
            font-size: 13px;
            padding: 4px 12px;
            border-radius: 20px;
        }
        
        .stat-new { background: rgba(16, 185, 129, 0.2); color: #6ee7b7; }
        .stat-dup { background: rgba(245, 158, 11, 0.2); color: #fcd34d; }
        .stat-sel { background: rgba(14, 165, 233, 0.2); color: #7dd3fc; }
        
        /* Publication List */
        .pub-list {
            max-height: 500px;
            overflow-y: auto;
        }
        
        .pub-item {
            display: flex;
            gap: 16px;
            padding: 16px;
            border-bottom: 1px solid #334155;
        }
        
        .pub-item.is-dup {
            opacity: 0.5;
            background: rgba(245, 158, 11, 0.05);
        }
        
        .pub-item.is-selected {
            background: rgba(14, 165, 233, 0.1);
        }
        
        .pub-checkbox {
            flex-shrink: 0;
        }
        
        .pub-checkbox input {
            width: 22px;
            height: 22px;
            cursor: pointer;
            accent-color: #38bdf8;
        }
        
        .pub-content {
            flex: 1;
            min-width: 0;
        }
        
        .pub-title {
            font-size: 15px;
            font-weight: 600;
            color: #f1f5f9;
            margin-bottom: 6px;
            line-height: 1.4;
        }
        
        .pub-meta {
            font-size: 13px;
            color: #94a3b8;
            display: flex;
            flex-wrap: wrap;
            gap: 12px;
        }
        
        .pub-meta span {
            display: flex;
            align-items: center;
            gap: 4px;
        }
        
        .pub-abstract {
            font-size: 13px;
            color: #64748b;
            margin-top: 8px;
            line-height: 1.5;
        }
        
        .badge {
            display: inline-block;
            padding: 2px 8px;
            font-size: 11px;
            font-weight: 600;
            border-radius: 4px;
            margin-left: 8px;
        }
        
        .badge-dup { background: rgba(245, 158, 11, 0.3); color: #fcd34d; }
        .badge-type { background: rgba(14, 165, 233, 0.2); color: #7dd3fc; }
        
        /* Toolbar */
        .toolbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 12px;
            padding: 16px;
            background: #0f172a;
            border-top: 1px solid #334155;
            border-radius: 0 0 12px 12px;
        }
        
        .toolbar-left {
            display: flex;
            align-items: center;
            gap: 8px;
        }
        
        .toolbar-left input {
            width: 18px;
            height: 18px;
            accent-color: #38bdf8;
        }
        
        .toolbar-left label {
            font-size: 14px;
            color: #94a3b8;
            cursor: pointer;
        }
        
        .toolbar-right {
            display: flex;
            gap: 12px;
        }
        
        /* Login */
        .login-container {
            max-width: 400px;
            margin: 100px auto;
            padding: 0 24px;
        }
        
        .login-card {
            background: #1e293b;
            border: 1px solid #334155;
            border-radius: 16px;
            padding: 40px 32px;
            text-align: center;
        }
        
        .login-icon {
            font-size: 48px;
            color: #38bdf8;
            margin-bottom: 16px;
        }
        
        .login-title {
            font-size: 22px;
            font-weight: 700;
            margin-bottom: 8px;
        }
        
        .login-subtitle {
            color: #94a3b8;
            margin-bottom: 24px;
        }
        
        .login-card .form-input {
            margin-bottom: 16px;
        }
        
        .login-card .btn {
            width: 100%;
            justify-content: center;
        }
        
        /* Hidden */
        .hidden { display: none !important; }
        
        /* Empty */
        .empty-state {
            padding: 48px;
            text-align: center;
            color: #64748b;
        }
        
        .empty-state i {
            font-size: 48px;
            margin-bottom: 16px;
            opacity: 0.5;
        }
        
        /* Spinner */
        .spinner {
            display: inline-block;
            width: 16px;
            height: 16px;
            border: 2px solid rgba(255,255,255,0.3);
            border-radius: 50%;
            border-top-color: white;
            animation: spin 1s linear infinite;
        }
        
        @keyframes spin {
            to { transform: rotate(360deg); }
        }
    </style>
</head>
<body>
    <?php if (!$authenticated): ?>
        <!-- Login -->
        <div class="login-container">
            <div class="login-card">
                <div class="login-icon"><i class="fas fa-lock"></i></div>
                <h1 class="login-title">Admin Access</h1>
                <p class="login-subtitle">Enter admin email to continue</p>
                <form method="POST">
                    <input type="hidden" name="action" value="login">
                    <input type="email" name="email" class="form-input" placeholder="admin@email.com" required>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-sign-in-alt"></i> Login
                    </button>
                </form>
            </div>
        </div>
    <?php else: ?>
        <!-- Header -->
        <header class="header">
            <div class="header-title">
                <i class="fas fa-book-reader"></i> Publication Fetcher
            </div>
            <a href="?logout=1" class="logout-btn">
                <i class="fas fa-sign-out-alt"></i> Logout
            </a>
        </header>
        
        <!-- Main Content -->
        <div class="container">
            <?php if (!$apiKey): ?>
                <div class="alert alert-error">
                    <i class="fas fa-exclamation-triangle"></i>
                    <strong>API Key Missing:</strong> Add SERPAPI_API_KEY to .env file
                </div>
            <?php endif; ?>
            
            <!-- Search Form -->
            <div class="card">
                <h2 class="card-title"><i class="fas fa-search"></i> Search Parameters</h2>
                
                <form id="fetchForm">
                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">Year From</label>
                            <input type="number" name="year_from" class="form-input" value="2026" min="1990" max="2030">
                            <p class="form-hint">Fetch publications from this year onwards</p>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Year To (Optional)</label>
                            <input type="number" name="year_to" class="form-input" placeholder="Any" min="1990" max="2030">
                            <p class="form-hint">Leave empty for no upper limit</p>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">Search Mode</label>
                        <select name="search_mode" id="searchMode" class="form-select">
                            <option value="team">Search by Team Members</option>
                            <option value="custom">Custom Search Query</option>
                        </select>
                    </div>
                    
                    <!-- Team Selection -->
                    <div id="teamSection" class="form-group">
                        <label class="form-label">Select Researchers (<?php echo count($teamMembers); ?> in database)</label>
                        
                        <button type="button" id="selectAllBtn" class="select-all-btn">
                            <i class="fas fa-check-double"></i> Select All
                        </button>
                        
                        <div class="researcher-grid">
                            <?php foreach ($teamMembers as $member): ?>
                                <label class="researcher-item">
                                    <input type="checkbox" name="researchers[]" value="<?php echo htmlspecialchars($member['name']); ?>">
                                    <span>
                                        <span class="researcher-name"><?php echo htmlspecialchars($member['name']); ?></span>
                                        <?php if (!$member['google_scholar']): ?>
                                            <span class="researcher-note">No Google Scholar</span>
                                        <?php endif; ?>
                                    </span>
                                </label>
                            <?php endforeach; ?>
                        </div>
                        
                        <?php if (empty($teamMembers)): ?>
                            <div class="alert alert-warning">
                                <i class="fas fa-exclamation-triangle"></i>
                                No team members in database
                            </div>
                        <?php endif; ?>
                    </div>
                    
                    <!-- Custom Query -->
                    <div id="customSection" class="form-group hidden">
                        <label class="form-label">Search Query</label>
                        <input type="text" name="query" class="form-input" placeholder="e.g., robotics autonomous systems">
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">Results per Query</label>
                        <select name="num_results" class="form-select">
                            <option value="10">10 results</option>
                            <option value="20" selected>20 results</option>
                            <option value="50">50 results</option>
                        </select>
                    </div>
                </form>
                
                <div class="btn-row">
                    <button type="button" id="fetchBtn" class="btn btn-primary" <?php echo !$apiKey ? 'disabled' : ''; ?>>
                        <i class="fas fa-cloud-download-alt"></i> Fetch & Preview
                    </button>
                    <a href="../publications/" class="btn btn-secondary">
                        <i class="fas fa-eye"></i> View Publications
                    </a>
                </div>
            </div>
            
            <!-- Progress -->
            <div id="progressSection" class="progress-container hidden">
                <div class="progress-bar">
                    <div class="progress-fill" id="progressFill"></div>
                </div>
                <p class="progress-text" id="progressText">Fetching...</p>
            </div>
            
            <!-- Status -->
            <div id="statusAlert" class="hidden"></div>
            
            <!-- Results -->
            <div id="resultsSection" class="card hidden" style="padding: 0;">
                <div style="padding: 20px 24px;">
                    <div class="results-header">
                        <h3 style="font-size: 16px; font-weight: 600;">
                            <i class="fas fa-list"></i> Fetched Publications
                        </h3>
                        <div class="stats">
                            <span class="stat stat-new" id="statNew">New: 0</span>
                            <span class="stat stat-dup" id="statDup">Duplicates: 0</span>
                            <span class="stat stat-sel" id="statSel">Selected: 0</span>
                        </div>
                    </div>
                </div>
                
                <div class="pub-list" id="pubList"></div>
                
                <div class="toolbar">
                    <div class="toolbar-left">
                        <input type="checkbox" id="selectAllPubs">
                        <label for="selectAllPubs">Select all new</label>
                    </div>
                    <div class="toolbar-right">
                        <button type="button" id="clearBtn" class="btn btn-secondary" style="padding: 8px 16px;">
                            Clear
                        </button>
                        <button type="button" id="saveBtn" class="btn btn-success" disabled>
                            <i class="fas fa-save"></i> Save (<span id="saveCount">0</span>)
                        </button>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>
    
    <script>
    (function() {
        const form = document.getElementById('fetchForm');
        if (!form) return;
        
        const fetchBtn = document.getElementById('fetchBtn');
        const searchMode = document.getElementById('searchMode');
        const teamSection = document.getElementById('teamSection');
        const customSection = document.getElementById('customSection');
        const selectAllBtn = document.getElementById('selectAllBtn');
        const progressSection = document.getElementById('progressSection');
        const progressFill = document.getElementById('progressFill');
        const progressText = document.getElementById('progressText');
        const statusAlert = document.getElementById('statusAlert');
        const resultsSection = document.getElementById('resultsSection');
        const pubList = document.getElementById('pubList');
        const selectAllPubs = document.getElementById('selectAllPubs');
        const clearBtn = document.getElementById('clearBtn');
        const saveBtn = document.getElementById('saveBtn');
        
        let publications = [];
        let isBusy = false;
        
        // Toggle search mode
        searchMode.onchange = function() {
            teamSection.classList.toggle('hidden', this.value !== 'team');
            customSection.classList.toggle('hidden', this.value === 'team');
        };
        
        // Select all researchers
        selectAllBtn.onclick = function() {
            const cbs = document.querySelectorAll('input[name="researchers[]"]');
            const allChecked = Array.from(cbs).every(c => c.checked);
            cbs.forEach(c => {
                c.checked = !allChecked;
                c.closest('.researcher-item').classList.toggle('selected', !allChecked);
            });
        };
        
        // Highlight selected researchers
        document.querySelectorAll('.researcher-item input').forEach(cb => {
            cb.onchange = function() {
                this.closest('.researcher-item').classList.toggle('selected', this.checked);
            };
        });
        
        // Fetch button
        fetchBtn.onclick = async function() {
            if (isBusy) return;
            isBusy = true;
            
            const formData = new FormData(form);
            const mode = formData.get('search_mode');
            const yearFrom = formData.get('year_from') || 2026;
            const yearTo = formData.get('year_to') || null;
            const num = formData.get('num_results') || 20;
            
            let queries = [];
            if (mode === 'team') {
                const researchers = formData.getAll('researchers[]');
                if (researchers.length === 0) {
                    showAlert('Please select at least one researcher!', 'error');
                    isBusy = false;
                    return;
                }
                queries = researchers.map(n => {
                    const cleanName = n.replace(/^(Prof\.|Dr\.|Eng\.)\s*/g, '');
                    return { query: 'author:"' + cleanName + '"', label: n };
                });
            } else {
                const q = formData.get('query')?.trim();
                if (!q) {
                    showAlert('Please enter a search query!', 'error');
                    isBusy = false;
                    return;
                }
                queries = [{ query: q, label: q }];
            }
            
            // Reset UI
            publications = [];
            pubList.innerHTML = '';
            resultsSection.classList.add('hidden');
            statusAlert.classList.add('hidden');
            progressSection.classList.remove('hidden');
            progressFill.style.width = '0%';
            fetchBtn.disabled = true;
            fetchBtn.innerHTML = '<span class="spinner"></span> Fetching...';
            
            // Fetch
            for (let i = 0; i < queries.length; i++) {
                progressFill.style.width = ((i / queries.length) * 100) + '%';
                progressText.textContent = 'Fetching: ' + queries[i].label + ' (' + (i+1) + '/' + queries.length + ')';
                
                try {
                    const res = await fetch('fetch_publications_api.php', {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json' },
                        body: JSON.stringify({
                            action: 'preview',
                            query: queries[i].query,
                            year_from: yearFrom,
                            year_to: yearTo,
                            num: num
                        })
                    });
                    const data = await res.json();
                    if (data.publications) {
                        data.publications.forEach(p => {
                            p.source = queries[i].label;
                            publications.push(p);
                        });
                    }
                } catch (e) {
                    console.error(e);
                }
            }
            
            progressFill.style.width = '100%';
            progressSection.classList.add('hidden');
            
            // Deduplicate
            const seen = new Set();
            publications = publications.filter(p => {
                const k = p.title.toLowerCase().trim();
                if (seen.has(k)) return false;
                seen.add(k);
                return true;
            });
            
            renderPubs();
            fetchBtn.disabled = false;
            fetchBtn.innerHTML = '<i class="fas fa-cloud-download-alt"></i> Fetch & Preview';
            isBusy = false;
        };
        
        function renderPubs() {
            if (publications.length === 0) {
                pubList.innerHTML = '<div class="empty-state"><i class="fas fa-search"></i><p>No publications found</p></div>';
                resultsSection.classList.remove('hidden');
                updateStats();
                return;
            }
            
            pubList.innerHTML = '';
            publications.forEach((p, i) => {
                const div = document.createElement('div');
                div.className = 'pub-item' + (p.is_duplicate ? ' is-dup' : '');
                div.dataset.idx = i;
                div.innerHTML = `
                    <div class="pub-checkbox">
                        <input type="checkbox" data-idx="${i}" ${p.is_duplicate ? 'disabled' : ''}>
                    </div>
                    <div class="pub-content">
                        <div class="pub-title">
                            ${esc(p.title)}
                            ${p.is_duplicate ? '<span class="badge badge-dup">Already in DB</span>' : ''}
                        </div>
                        <div class="pub-meta">
                            <span><i class="fas fa-user"></i> ${esc(p.authors || 'Unknown')}</span>
                            <span><i class="fas fa-calendar"></i> ${p.year || 'N/A'}</span>
                            <span class="badge badge-type">${p.type || 'journal'}</span>
                        </div>
                        ${p.abstract ? '<div class="pub-abstract">' + esc(trunc(p.abstract, 180)) + '</div>' : ''}
                    </div>
                `;
                pubList.appendChild(div);
            });
            
            resultsSection.classList.remove('hidden');
            updateStats();
            
            // Checkbox handlers
            pubList.querySelectorAll('input[type="checkbox"]').forEach(cb => {
                cb.onchange = function() {
                    this.closest('.pub-item').classList.toggle('is-selected', this.checked);
                    updateStats();
                };
            });
        }
        
        function updateStats() {
            const newC = publications.filter(p => !p.is_duplicate).length;
            const dupC = publications.filter(p => p.is_duplicate).length;
            const selC = pubList.querySelectorAll('input:checked').length;
            document.getElementById('statNew').textContent = 'New: ' + newC;
            document.getElementById('statDup').textContent = 'Duplicates: ' + dupC;
            document.getElementById('statSel').textContent = 'Selected: ' + selC;
            const saveCountEl = document.getElementById('saveCount');
            if (saveCountEl) saveCountEl.textContent = selC;
            saveBtn.disabled = selC === 0;
        }
        
        // Select all new pubs
        selectAllPubs.onchange = function() {
            pubList.querySelectorAll('input:not(:disabled)').forEach(cb => {
                cb.checked = this.checked;
                cb.closest('.pub-item').classList.toggle('is-selected', this.checked);
            });
            updateStats();
        };
        
        // Clear selection
        clearBtn.onclick = function() {
            pubList.querySelectorAll('input:checked').forEach(cb => {
                cb.checked = false;
                cb.closest('.pub-item').classList.remove('is-selected');
            });
            selectAllPubs.checked = false;
            updateStats();
        };
        
        // Save
        saveBtn.onclick = async function() {
            if (isBusy) return;
            isBusy = true;
            
            const indices = [];
            pubList.querySelectorAll('input:checked').forEach(cb => indices.push(parseInt(cb.dataset.idx)));
            
            if (indices.length === 0) {
                showAlert('No publications selected!', 'warning');
                isBusy = false;
                return;
            }
            
            const toSave = indices.map(i => publications[i]);
            saveBtn.disabled = true;
            saveBtn.innerHTML = '<span class="spinner"></span> Saving...';
            
            try {
                const res = await fetch('fetch_publications_api.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ action: 'save', publications: toSave })
                });
                
                // Check if response is OK
                if (!res.ok) {
                    const text = await res.text();
                    showAlert('Server error (' + res.status + '): ' + text.substring(0, 100), 'error');
                    saveBtn.disabled = false;
                    saveBtn.innerHTML = '<i class="fas fa-save"></i> Save (<span id="saveCount">' + 
                        pubList.querySelectorAll('input:checked').length + '</span>)';
                    isBusy = false;
                    return;
                }
                
                const text = await res.text();
                let data;
                try {
                    data = JSON.parse(text);
                } catch (parseError) {
                    showAlert('Invalid response: ' + text.substring(0, 100), 'error');
                    saveBtn.disabled = false;
                    saveBtn.innerHTML = '<i class="fas fa-save"></i> Save (<span id="saveCount">' + 
                        pubList.querySelectorAll('input:checked').length + '</span>)';
                    isBusy = false;
                    return;
                }
                
                if (data.error) {
                    showAlert('Error: ' + data.error, 'error');
                } else {
                    showAlert('Saved ' + data.saved + ' publication(s)!', 'success');
                    
                    // Mark as saved
                    indices.forEach(i => {
                        publications[i].is_duplicate = true;
                        const item = pubList.querySelector(`[data-idx="${i}"]`);
                        if (item) {
                            item.classList.add('is-dup');
                            item.classList.remove('is-selected');
                            const cb = item.querySelector('input');
                            if (cb) { cb.checked = false; cb.disabled = true; }
                            const title = item.querySelector('.pub-title');
                            if (title && !title.querySelector('.badge-dup')) {
                                title.innerHTML += '<span class="badge badge-dup">Saved</span>';
                            }
                        }
                    });
                    selectAllPubs.checked = false;
                    updateStats();
                }
            } catch (e) {
                console.error('Save error:', e);
                showAlert('Network error: ' + e.message, 'error');
            }
            
            saveBtn.disabled = false;
            saveBtn.innerHTML = '<i class="fas fa-save"></i> Save (<span id="saveCount">' + 
                pubList.querySelectorAll('input:checked').length + '</span>)';
            isBusy = false;
        };
        
        function showAlert(msg, type) {
            statusAlert.className = 'alert alert-' + type;
            statusAlert.innerHTML = '<i class="fas fa-' + 
                (type === 'error' ? 'exclamation-circle' : type === 'success' ? 'check-circle' : 'info-circle') + 
                '"></i> ' + msg;
            statusAlert.classList.remove('hidden');
            setTimeout(() => statusAlert.classList.add('hidden'), 4000);
        }
        
        function esc(s) {
            const d = document.createElement('div');
            d.textContent = s || '';
            return d.innerHTML;
        }
        
        function trunc(s, n) {
            return s && s.length > n ? s.slice(0, n) + '...' : s;
        }
    })();
    </script>
</body>
</html>
