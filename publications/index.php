<?php
/**
 * AlfaisalX - Publications (Academic Style)
 * 
 * Compact academic-style visualization with BibTeX/IEEE export
 */

require_once '../includes/config.php';
require_once '../database/Database.php';

$db = Database::getInstance();

// Get selected year (default to 2025)
$selected_year = isset($_GET['year']) ? $_GET['year'] : 2025;
if ($selected_year === 'all') $selected_year = null;

$publications = $db->getPublications(null, $selected_year);

$page_title = 'Publications';
$page_description = 'Research publications and scholarly output from AlfaisalX center members.';

// Count by type
$type_counts = ['journal' => 0, 'conference' => 0, 'book' => 0, 'other' => 0];
foreach ($publications as $pub) {
    $type = strtolower($pub['type'] ?? 'other');
    if (strpos($type, 'journal') !== false) $type_counts['journal']++;
    elseif (strpos($type, 'conference') !== false) $type_counts['conference']++;
    elseif (strpos($type, 'book') !== false) $type_counts['book']++;
    else $type_counts['other']++;
}

// Group publications by year
$grouped_publications = [];
foreach ($publications as $pub) {
    $year = $pub['year'] ? $pub['year'] : 'Forthcoming';
    if (!isset($grouped_publications[$year])) {
        $grouped_publications[$year] = [];
    }
    $grouped_publications[$year][] = $pub;
}
krsort($grouped_publications);

// Generate BibTeX for a publication
function generateBibTeX($pub) {
    $authors = str_replace(',', ' and', $pub['authors'] ?? 'Unknown');
    $title = $pub['title'] ?? 'Untitled';
    $year = $pub['year'] ?? date('Y');
    $venue = $pub['venue'] ?? '';
    $doi = $pub['doi'] ?? '';
    
    // Generate key from first author and year
    $key = preg_replace('/[^a-zA-Z]/', '', explode(' ', $authors)[0]) . $year;
    
    $type = strtolower($pub['type'] ?? 'article');
    $bibType = 'article';
    if (strpos($type, 'book') !== false) $bibType = 'book';
    elseif (strpos($type, 'conference') !== false) $bibType = 'inproceedings';
    
    $bib = "@{$bibType}{{$key},\n";
    $bib .= "  author = {{$authors}},\n";
    $bib .= "  title = {{{$title}}},\n";
    $bib .= "  year = {{$year}},\n";
    if ($venue) {
        if ($bibType === 'inproceedings') {
            $bib .= "  booktitle = {{$venue}},\n";
        } else {
            $bib .= "  journal = {{$venue}},\n";
        }
    }
    if ($doi) $bib .= "  doi = {{$doi}},\n";
    if ($pub['url']) $bib .= "  url = {{$pub['url']}},\n";
    $bib .= "}\n";
    
    return $bib;
}

// Generate IEEE citation
function generateIEEE($pub, $index) {
    $authors = $pub['authors'] ?? 'Unknown';
    $title = $pub['title'] ?? 'Untitled';
    $venue = $pub['venue'] ?? '';
    $year = $pub['year'] ?? '';
    $doi = $pub['doi'] ?? '';
    
    $citation = "[$index] $authors, \"$title\"";
    if ($venue) $citation .= ", <i>$venue</i>";
    if ($year) $citation .= ", $year";
    if ($doi) $citation .= ". DOI: $doi";
    $citation .= ".";
    
    return $citation;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <?php include '../includes/head.php'; ?>
    <style>
        /* Compact Academic Style */
        .pub-controls {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: var(--space-4);
            margin-bottom: var(--space-6);
            padding: var(--space-4);
            background: var(--bg-elevated);
            border-radius: var(--radius-lg);
            border: 1px solid var(--border-color);
        }
        
        .pub-filters {
            display: flex;
            gap: var(--space-2);
            flex-wrap: wrap;
            align-items: center;
        }
        
        .filter-divider {
            width: 1px;
            height: 24px;
            background: var(--border-color);
            margin: 0 8px;
        }

        .filter-btn {
            padding: 6px 14px;
            border: 1px solid var(--border-color);
            background: transparent;
            color: var(--text-secondary);
            border-radius: var(--radius-full);
            cursor: pointer;
            font-size: 13px;
            transition: all 0.2s;
            text-decoration: none;
            display: inline-block;
        }
        
        .filter-btn:hover, .filter-btn.active {
            background: var(--accent-cyan);
            border-color: var(--accent-cyan);
            color: white;
        }
        
        .filter-btn .count {
            opacity: 0.7;
            font-size: 11px;
            margin-left: 4px;
        }
        
        .export-btns {
            display: flex;
            gap: var(--space-2);
        }
        
        .export-btn {
            padding: 6px 12px;
            border: 1px solid var(--border-color);
            background: var(--bg-card-solid);
            color: var(--text-primary);
            border-radius: var(--radius-md);
            cursor: pointer;
            font-size: 12px;
            display: flex;
            align-items: center;
            gap: 6px;
            transition: all 0.2s;
        }
        
        .export-btn:hover {
            background: var(--accent-purple);
            border-color: var(--accent-purple);
            color: white;
        }
        
        /* Stats bar */
        .pub-stats-bar {
            display: flex;
            gap: var(--space-6);
            margin-bottom: var(--space-6);
            font-size: 14px;
        }
        
        .stat-item {
            display: flex;
            align-items: center;
            gap: var(--space-2);
        }
        
        .stat-item strong {
            color: var(--accent-cyan);
            font-size: 18px;
        }
        
        /* Year section */
        .year-section {
            margin-bottom: var(--space-8);
        }
        
        .year-header {
            font-size: 16px;
            font-weight: 700;
            color: var(--accent-cyan);
            padding: var(--space-2) 0;
            margin-bottom: var(--space-3);
            border-bottom: 2px solid var(--accent-cyan);
            display: inline-block;
        }
        
        .year-count {
            font-weight: 400;
            color: var(--text-muted);
            font-size: 13px;
            margin-left: var(--space-2);
        }
        
        /* Compact publication item */
        .pub-list {
            counter-reset: pub-counter;
        }
        
        .pub-entry {
            display: flex;
            gap: var(--space-3);
            padding: var(--space-3) 0;
            border-bottom: 1px solid var(--border-subtle);
            font-size: 14px;
            line-height: 1.5;
            transition: background 0.2s;
        }
        
        .pub-entry:hover {
            background: var(--bg-subtle);
            margin: 0 calc(-1 * var(--space-3));
            padding-left: var(--space-3);
            padding-right: var(--space-3);
        }
        
        .pub-number {
            flex-shrink: 0;
            min-width: 36px;
            height: 24px;
            display: flex;
            align-items: center;
            justify-content: flex-start;
            color: var(--accent-cyan);
            font-size: 13px;
            font-weight: 600;
        }
        
        .pub-body {
            flex: 1;
        }
        
        .pub-authors-compact {
            color: var(--text-primary);
        }
        
        .pub-title-compact {
            font-weight: 600;
            color: var(--accent-cyan);
        }
        
        .pub-title-compact a {
            color: inherit;
            text-decoration: none;
        }
        
        .pub-title-compact a:hover {
            color: var(--accent-cyan);
            text-decoration: underline;
        }
        
        .pub-venue-compact {
            font-style: normal;
            color: var(--text-secondary);
        }
        
        .pub-venue-compact em {
            font-style: italic;
        }
        
        .pub-year-compact {
            color: var(--text-secondary);
        }
        
        .pub-type-tag {
            display: inline-block;
            padding: 1px 6px;
            font-size: 10px;
            font-weight: 600;
            text-transform: uppercase;
            border-radius: 3px;
            margin-left: 6px;
            vertical-align: middle;
        }
        
        .tag-journal { background: rgba(14, 165, 233, 0.15); color: #0ea5e9; }
        .tag-conference { background: rgba(139, 92, 246, 0.15); color: #8b5cf6; }
        .tag-book { background: rgba(16, 185, 129, 0.15); color: #10b981; }
        
        .pub-links {
            margin-top: 4px;
            display: flex;
            gap: var(--space-3);
            font-size: 12px;
        }
        
        .pub-link {
            color: var(--accent-cyan);
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 4px;
        }
        
        .pub-link:hover {
            text-decoration: underline;
        }
        
        /* Export modal */
        .modal-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0,0,0,0.7);
            z-index: 1000;
            align-items: center;
            justify-content: center;
        }
        
        .modal-overlay.active {
            display: flex;
        }
        
        .modal-content {
            background: var(--bg-card-solid);
            border: 1px solid var(--border-color);
            border-radius: var(--radius-xl);
            max-width: 800px;
            width: 90%;
            max-height: 80vh;
            overflow: hidden;
            display: flex;
            flex-direction: column;
        }
        
        .modal-header {
            padding: var(--space-4) var(--space-6);
            border-bottom: 1px solid var(--border-color);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .modal-title {
            font-size: 18px;
            font-weight: 600;
        }
        
        .modal-close {
            background: none;
            border: none;
            color: var(--text-muted);
            cursor: pointer;
            font-size: 20px;
        }
        
        .modal-body {
            padding: var(--space-4) var(--space-6);
            overflow-y: auto;
            flex: 1;
        }
        
        .modal-body pre {
            background: #0d1117;
            color: #c9d1d9;
            padding: var(--space-4);
            border-radius: var(--radius-md);
            font-size: 12px;
            overflow-x: auto;
            white-space: pre-wrap;
            word-wrap: break-word;
        }
        
        .modal-footer {
            padding: var(--space-4) var(--space-6);
            border-top: 1px solid var(--border-color);
            display: flex;
            justify-content: flex-end;
            gap: var(--space-3);
        }
        
        .btn-copy {
            background: var(--accent-cyan);
            color: white;
            border: none;
            padding: 8px 16px;
            border-radius: var(--radius-md);
            cursor: pointer;
            font-weight: 500;
        }
        
        .btn-download {
            background: var(--accent-purple);
            color: white;
            border: none;
            padding: 8px 16px;
            border-radius: var(--radius-md);
            cursor: pointer;
            font-weight: 500;
        }
        
        /* Search */
        .search-box {
            position: relative;
        }
        
        .search-box input {
            padding: 8px 12px 8px 36px;
            border: 1px solid var(--border-color);
            background: var(--bg-elevated);
            color: var(--text-primary);
            border-radius: var(--radius-md);
            font-size: 14px;
            width: 200px;
        }
        
        .search-box i {
            position: absolute;
            left: 12px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--text-muted);
            font-size: 14px;
        }
        
        /* Hidden for filter */
        .pub-entry.hidden { display: none; }
        .year-section.hidden { display: none; }
        
        @media (max-width: 768px) {
            .pub-controls {
                flex-direction: column;
                align-items: stretch;
            }
            .search-box input { width: 100%; }
        }
    </style>
</head>
<body>
    <?php include '../includes/header.php'; ?>
    
    <main id="main-content">
        <section class="page-header">
            <div class="container">
                <span class="section-tag">// Research Output</span>
                <h1 class="page-title">Publications</h1>
                <p class="page-subtitle">
                    Scholarly contributions in cognitive robotics, AI, and autonomous systems
                </p>
            </div>
        </section>

        <section class="section">
            <div class="container">
                
                <!-- Stats Bar -->
                <div class="pub-stats-bar">
                    <div class="stat-item">
                        <strong><?php echo count($publications); ?></strong>
                        <span>Total</span>
                    </div>
                    <div class="stat-item">
                        <strong><?php echo $type_counts['journal']; ?></strong>
                        <span>Journals</span>
                    </div>
                    <div class="stat-item">
                        <strong><?php echo $type_counts['conference']; ?></strong>
                        <span>Conferences</span>
                    </div>
                    <div class="stat-item">
                        <strong><?php echo $type_counts['book']; ?></strong>
                        <span>Books</span>
                    </div>
                </div>
                
                <!-- Controls -->
                <div class="pub-controls">
                    <div class="pub-filters">
                        <button class="filter-btn active" data-filter="all">
                            All Types <span class="count">(<?php echo count($publications); ?>)</span>
                        </button>
                        <button class="filter-btn" data-filter="journal">
                            Journals <span class="count">(<?php echo $type_counts['journal']; ?>)</span>
                        </button>
                        <button class="filter-btn" data-filter="conference">
                            Conferences <span class="count">(<?php echo $type_counts['conference']; ?>)</span>
                        </button>
                        <button class="filter-btn" data-filter="book">
                            Books <span class="count">(<?php echo $type_counts['book']; ?>)</span>
                        </button>

                        <div class="filter-divider"></div>

                        <a href="?year=2025" class="filter-btn <?php echo ($selected_year == 2025) ? 'active' : ''; ?>">
                            2025
                        </a>
                        <a href="?year=all" class="filter-btn <?php echo ($selected_year === null) ? 'active' : ''; ?>">
                            All Years
                        </a>
                    </div>
                    
                    <div style="display: flex; gap: var(--space-3); align-items: center;">
                        <div class="search-box">
                            <i class="fas fa-search"></i>
                            <input type="text" id="searchInput" placeholder="Search...">
                        </div>
                        
                        <div class="export-btns">
                            <button class="export-btn" onclick="showExport('bibtex')">
                                <i class="fas fa-code"></i> BibTeX
                            </button>
                            <button class="export-btn" onclick="showExport('ieee')">
                                <i class="fas fa-list-ol"></i> IEEE
                            </button>
                        </div>
                    </div>
                </div>
                
                <?php if (empty($publications)): ?>
                    <div class="text-center py-12">
                        <div class="text-4xl text-muted mb-4"><i class="fas fa-book-open"></i></div>
                        <h3>No publications found</h3>
                    </div>
                <?php else: ?>
                    
                    <?php 
                    $global_index = 1;
                    foreach ($grouped_publications as $year => $year_pubs): 
                    ?>
                        <div class="year-section" data-year="<?php echo $year; ?>">
                            <div class="year-header">
                                <?php echo htmlspecialchars($year); ?>
                                <span class="year-count">(<?php echo count($year_pubs); ?> publications)</span>
                            </div>
                            
                            <div class="pub-list">
                                <?php foreach ($year_pubs as $pub): 
                                    $type = strtolower($pub['type'] ?? 'other');
                                    $type_class = '';
                                    $type_label = '';
                                    if (strpos($type, 'journal') !== false) {
                                        $type_class = 'tag-journal';
                                        $type_label = 'J';
                                        $filter_type = 'journal';
                                    } elseif (strpos($type, 'conference') !== false) {
                                        $type_class = 'tag-conference';
                                        $type_label = 'C';
                                        $filter_type = 'conference';
                                    } elseif (strpos($type, 'book') !== false) {
                                        $type_class = 'tag-book';
                                        $type_label = 'B';
                                        $filter_type = 'book';
                                    } else {
                                        $filter_type = 'other';
                                    }
                                ?>
                                    <div class="pub-entry" 
                                         data-type="<?php echo $filter_type; ?>"
                                         data-id="<?php echo $pub['id']; ?>">
                                        <div class="pub-number">[<?php echo $global_index; ?>]</div>
                                        <div class="pub-body">
                                            <?php 
                                            // Clean author names - remove ellipsis and clean up formatting
                                            $authors = $pub['authors'];
                                            $authors = str_replace(['…', '...'], '', $authors);
                                            $authors = preg_replace('/^,\s*/', '', $authors); // Remove leading comma
                                            $authors = preg_replace('/,\s*,/', ',', $authors); // Remove double commas
                                            $authors = trim($authors, ', ');
                                            
                                            // IEEE format: Authors, "Title," Venue, Year.
                                            ?>
                                            <span class="pub-authors-compact"><?php echo htmlspecialchars($authors); ?>,</span>
                                            <?php if ($pub['url']): ?>
                                                <a href="<?php echo htmlspecialchars($pub['url']); ?>" target="_blank" class="pub-title-compact">
                                                    "<?php echo htmlspecialchars($pub['title']); ?>,"
                                                </a>
                                            <?php else: ?>
                                                <span class="pub-title-compact">"<?php echo htmlspecialchars($pub['title']); ?>,"</span>
                                            <?php endif; ?>
                                            <?php if ($type_label): ?>
                                                <span class="pub-type-tag <?php echo $type_class; ?>"><?php echo $type_label; ?></span>
                                            <?php endif; ?>
                                            <?php if ($pub['venue']): ?>
                                                <span class="pub-venue-compact"><em><?php echo htmlspecialchars($pub['venue']); ?></em>,</span>
                                            <?php endif; ?>
                                            <?php if ($pub['year']): ?>
                                                <span class="pub-year-compact"><?php echo $pub['year']; ?>.</span>
                                            <?php endif; ?>
                                            
                                            <?php if ($pub['doi'] || $pub['url']): ?>
                                            <div class="pub-links">
                                                <?php if ($pub['url']): ?>
                                                    <a href="<?php echo htmlspecialchars($pub['url']); ?>" target="_blank" class="pub-link">
                                                        <i class="fas fa-external-link-alt"></i> Paper
                                                    </a>
                                                <?php endif; ?>
                                                <?php if ($pub['doi']): ?>
                                                    <a href="https://doi.org/<?php echo htmlspecialchars($pub['doi']); ?>" target="_blank" class="pub-link">
                                                        <i class="fas fa-link"></i> DOI
                                                    </a>
                                                <?php endif; ?>
                                                <a href="#" class="pub-link" onclick="copySingleBibtex(<?php echo $pub['id']; ?>); return false;">
                                                    <i class="fas fa-quote-right"></i> Cite
                                                </a>
                                            </div>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                <?php 
                                $global_index++;
                                endforeach; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                    
                <?php endif; ?>
                
            </div>
        </section>
    </main>
    
    <!-- Export Modal -->
    <div class="modal-overlay" id="exportModal">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title" id="modalTitle">Export</h3>
                <button class="modal-close" onclick="closeModal()">&times;</button>
            </div>
            <div class="modal-body">
                <pre id="exportContent"></pre>
            </div>
            <div class="modal-footer">
                <button class="btn-copy" onclick="copyExport()">
                    <i class="fas fa-copy"></i> Copy to Clipboard
                </button>
                <button class="btn-download" onclick="downloadExport()">
                    <i class="fas fa-download"></i> Download
                </button>
            </div>
        </div>
    </div>
    
    <?php include '../includes/footer.php'; ?>
    
    <script>
        // Publication data for export
        const publications = <?php echo json_encode($publications); ?>;
        let currentExportFormat = 'bibtex';
        let currentExportContent = '';
        
        // Filter functionality
        document.querySelectorAll('.filter-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                document.querySelectorAll('.filter-btn').forEach(b => b.classList.remove('active'));
                this.classList.add('active');
                
                const filter = this.dataset.filter;
                filterPublications(filter);
            });
        });
        
        function filterPublications(filter) {
            document.querySelectorAll('.pub-entry').forEach(entry => {
                if (filter === 'all' || entry.dataset.type === filter) {
                    entry.classList.remove('hidden');
                } else {
                    entry.classList.add('hidden');
                }
            });
            
            // Hide empty year sections
            document.querySelectorAll('.year-section').forEach(section => {
                const visible = section.querySelectorAll('.pub-entry:not(.hidden)').length;
                section.classList.toggle('hidden', visible === 0);
            });
        }
        
        // Search functionality
        document.getElementById('searchInput').addEventListener('input', function() {
            const query = this.value.toLowerCase();
            
            document.querySelectorAll('.pub-entry').forEach(entry => {
                const text = entry.textContent.toLowerCase();
                entry.classList.toggle('hidden', !text.includes(query));
            });
            
            // Hide empty year sections
            document.querySelectorAll('.year-section').forEach(section => {
                const visible = section.querySelectorAll('.pub-entry:not(.hidden)').length;
                section.classList.toggle('hidden', visible === 0);
            });
        });
        
        // Export functions
        function generateBibTeX(pub) {
            const authors = (pub.authors || 'Unknown').replace(/,/g, ' and');
            const title = pub.title || 'Untitled';
            const year = pub.year || new Date().getFullYear();
            const venue = pub.venue || '';
            const doi = pub.doi || '';
            const url = pub.url || '';
            
            const key = authors.split(' ')[0].replace(/[^a-zA-Z]/g, '') + year;
            
            let type = (pub.type || 'article').toLowerCase();
            let bibType = 'article';
            if (type.includes('book')) bibType = 'book';
            else if (type.includes('conference')) bibType = 'inproceedings';
            
            let bib = `@${bibType}{${key},\n`;
            bib += `  author = {${authors}},\n`;
            bib += `  title = {${title}},\n`;
            bib += `  year = {${year}},\n`;
            if (venue) {
                if (bibType === 'inproceedings') {
                    bib += `  booktitle = {${venue}},\n`;
                } else {
                    bib += `  journal = {${venue}},\n`;
                }
            }
            if (doi) bib += `  doi = {${doi}},\n`;
            if (url) bib += `  url = {${url}},\n`;
            bib += `}\n`;
            
            return bib;
        }
        
        function generateIEEE(pub, index) {
            const authors = pub.authors || 'Unknown';
            const title = pub.title || 'Untitled';
            const venue = pub.venue || '';
            const year = pub.year || '';
            const doi = pub.doi || '';
            
            let citation = `[${index}] ${authors}, "${title}"`;
            if (venue) citation += `, ${venue}`;
            if (year) citation += `, ${year}`;
            if (doi) citation += `. DOI: ${doi}`;
            citation += '.';
            
            return citation;
        }
        
        function showExport(format) {
            currentExportFormat = format;
            const modal = document.getElementById('exportModal');
            const content = document.getElementById('exportContent');
            const title = document.getElementById('modalTitle');
            
            let output = '';
            
            if (format === 'bibtex') {
                title.textContent = 'BibTeX Export';
                publications.forEach(pub => {
                    output += generateBibTeX(pub) + '\n';
                });
            } else {
                title.textContent = 'IEEE Format Export';
                publications.forEach((pub, i) => {
                    output += generateIEEE(pub, i + 1) + '\n\n';
                });
            }
            
            currentExportContent = output;
            content.textContent = output;
            modal.classList.add('active');
        }
        
        function closeModal() {
            document.getElementById('exportModal').classList.remove('active');
        }
        
        function copyExport() {
            navigator.clipboard.writeText(currentExportContent).then(() => {
                alert('Copied to clipboard!');
            });
        }
        
        function downloadExport() {
            const ext = currentExportFormat === 'bibtex' ? 'bib' : 'txt';
            const blob = new Blob([currentExportContent], { type: 'text/plain' });
            const url = URL.createObjectURL(blob);
            const a = document.createElement('a');
            a.href = url;
            a.download = `alfaisalx_publications.${ext}`;
            a.click();
            URL.revokeObjectURL(url);
        }
        
        function copySingleBibtex(pubId) {
            const pub = publications.find(p => p.id == pubId);
            if (pub) {
                const bibtex = generateBibTeX(pub);
                navigator.clipboard.writeText(bibtex).then(() => {
                    alert('BibTeX copied to clipboard!');
                });
            }
        }
        
        // Close modal on outside click
        document.getElementById('exportModal').addEventListener('click', function(e) {
            if (e.target === this) closeModal();
        });
    </script>
</body>
</html>
