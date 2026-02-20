<?php
/**
 * AlfaisalX - Careers
 * Clean, minimal job listing with modal details
 */

require_once '../includes/config.php';
require_once '../database/Database.php';

$db = Database::getInstance();
$jobs = $db->getJobs();

$page_title = 'Careers';
$page_description = 'Join our world-class research team at AlfaisalX.';
$application_email = 'alfaisalx@alfaisal.edu';

$type_config = [
    'research_engineer' => ['label' => 'Research Engineer', 'color' => '#38bdf8'],
    'postdoc' => ['label' => 'Postdoc', 'color' => '#a78bfa'],
    'research_assistant' => ['label' => 'Research Assistant', 'color' => '#34d399'],
    'intern' => ['label' => 'Internship', 'color' => '#fbbf24']
];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <?php include '../includes/head.php'; ?>
    <style>
        /* Clean Job List */
        .jobs-list {
            display: flex;
            flex-direction: column;
            gap: var(--space-4);
            max-width: 900px;
            margin: 0 auto;
        }
        
        .job-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: var(--space-4);
            padding: var(--space-5) var(--space-6);
            background: var(--bg-card);
            border: 1px solid var(--border-color);
            border-radius: var(--radius-lg);
            cursor: pointer;
            transition: all var(--transition-base);
        }
        
        .job-row:hover {
            border-color: var(--accent-cyan);
            background: var(--bg-card-hover);
            transform: translateX(4px);
        }
        
        .job-row.featured {
            border-left: 3px solid var(--accent-purple);
        }
        
        .job-main {
            display: flex;
            align-items: center;
            gap: var(--space-4);
            flex: 1;
            min-width: 0;
        }
        
        .job-color {
            width: 4px;
            height: 40px;
            border-radius: 2px;
            flex-shrink: 0;
        }
        
        .job-info {
            flex: 1;
            min-width: 0;
        }
        
        .job-title {
            font-size: var(--text-lg);
            font-weight: 600;
            color: var(--text-primary);
            margin-bottom: 2px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        
        .job-dept {
            font-size: var(--text-sm);
            color: var(--text-muted);
        }
        
        .job-type-badge {
            padding: 6px 14px;
            border-radius: var(--radius-full);
            font-size: var(--text-xs);
            font-weight: 600;
            white-space: nowrap;
            flex-shrink: 0;
        }
        
        .job-arrow {
            color: var(--text-muted);
            font-size: var(--text-lg);
            transition: all var(--transition-fast);
            flex-shrink: 0;
        }
        
        .job-row:hover .job-arrow {
            color: var(--accent-cyan);
            transform: translateX(4px);
        }
        
        /* Modal - Same as before */
        .modal-overlay {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, 0.85);
            backdrop-filter: blur(10px);
            z-index: 9999;
            justify-content: center;
            align-items: center;
            padding: var(--space-6);
        }
        
        .modal-overlay.active {
            display: flex;
            animation: fadeIn 0.2s ease;
        }
        
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }
        
        .modal {
            background: var(--bg-elevated);
            border: 1px solid var(--border-color);
            border-radius: var(--radius-xl);
            max-width: 700px;
            width: 100%;
            height: 80vh; /* Fixed height */
            overflow-y: auto;
            overflow-x: hidden;
            animation: slideUp 0.3s ease;
            position: relative; /* Context for sticky */
            display: flex; /* Flex column to separate header/body/footer */
            flex-direction: column;
        }
        
        @keyframes slideUp {
            from { opacity: 0; transform: translateY(30px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        .modal-header {
            padding: var(--space-6);
            border-bottom: 1px solid var(--border-color);
            background: var(--bg-elevated); /* Ensure this is solid or opaque enough */
            z-index: 10; /* Ensure it's above scrolling content */
            flex-shrink: 0; /* Don't shrink header */
        }
        
        .modal-header-top {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: var(--space-3);
        }
        
        .modal-header h2 {
            font-size: var(--text-2xl);
            line-height: 1.3;
        }
        
        .modal-close {
            width: 36px;
            height: 36px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: transparent;
            border: 1px solid var(--border-color);
            border-radius: var(--radius-md);
            color: var(--text-muted);
            cursor: pointer;
            transition: all var(--transition-fast);
            flex-shrink: 0;
            margin-left: var(--space-4);
            z-index: 1001; /* Ensure button is clickable */
        }
        
        .modal-close:hover {
            color: white;
            background: rgba(255,255,255,0.1);
        }
        
        .modal-meta {
            display: flex;
            gap: var(--space-4);
            font-size: var(--text-sm);
            color: var(--text-primary); /* Whiter text */
            opacity: 0.8;
        }
        
        .modal-meta i {
            color: var(--accent-cyan);
            margin-right: 6px;
        }
        
        .modal-body {
            padding: var(--space-6);
            flex: 1; /* Take remaining space */
            overflow-y: auto; /* Scroll ONLY body */
        }
        
        .modal-description {
            font-size: var(--text-base);
            color: var(--text-primary); /* Whiter text */
            line-height: 1.8;
            margin-bottom: var(--space-6);
            padding-bottom: var(--space-6);
            border-bottom: 1px solid var(--border-color);
        }
        
        .detail-section {
            margin-bottom: var(--space-5);
        }
        
        .detail-section h4 {
            font-size: var(--text-sm);
            font-weight: 600;
            color: var(--accent-cyan);
            margin-bottom: var(--space-3);
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        .detail-section ul {
            list-style: none;
            padding: 0;
            margin: 0;
        }
        
        .detail-section li {
            position: relative;
            padding-left: 20px;
            margin-bottom: 8px;
            color: var(--text-primary); /* Whiter text */
            font-size: var(--text-sm);
            line-height: 1.6;
            opacity: 0.95;
        }
        
        .detail-section li::before {
            content: '•';
            position: absolute;
            left: 0;
            color: var(--accent-cyan);
        }
        
        .modal-footer {
            padding: var(--space-5) var(--space-6);
            border-top: 1px solid var(--border-color);
            display: flex;
            justify-content: flex-end;
            gap: var(--space-3);
            background: var(--bg-elevated);
        }
        
        .btn-secondary {
            padding: 10px 20px;
            background: transparent;
            border: 1px solid var(--border-color);
            color: var(--text-secondary);
            border-radius: var(--radius-md);
            font-size: var(--text-sm);
            font-weight: 500;
            cursor: pointer;
            transition: all var(--transition-fast);
        }
        
        .btn-secondary:hover {
            border-color: var(--accent-cyan);
            color: var(--accent-cyan);
        }
        
        /* Count badge */
        .positions-count {
            text-align: center;
            margin-bottom: var(--space-8);
        }
        
        .positions-count span {
            display: inline-block;
            padding: 8px 20px;
            background: rgba(56, 189, 248, 0.1);
            border: 1px solid rgba(56, 189, 248, 0.3);
            border-radius: var(--radius-full);
            font-size: var(--text-sm);
            color: var(--accent-cyan);
        }
        
        @media (max-width: 640px) {
            .job-row {
                flex-wrap: wrap;
            }
            
            .job-type-badge {
                order: 3;
                margin-top: var(--space-2);
            }
        }
    </style>
</head>
<body>
    <?php include '../includes/header.php'; ?>

    <main id="main-content">
        <section class="page-header">
            <div class="container">
                <span class="section-tag">// Join Our Team</span>
                <h1 class="page-title">Career Opportunities</h1>
                <p class="page-subtitle">
                    Be part of a world-class research team pushing the boundaries of AI and robotics.
                </p>
            </div>
        </section>

        <section class="section">
            <div class="container">
                <div class="positions-count">
                    <span><strong><?php echo count($jobs); ?></strong> Open Positions</span>
                </div>
                
                <div class="jobs-list">
                    <?php foreach ($jobs as $job): 
                        $type = $job['type'] ?? 'research_engineer';
                        $config = $type_config[$type] ?? $type_config['research_engineer'];
                    ?>
                    <div class="job-row <?php echo $job['is_featured'] ? 'featured' : ''; ?>" 
                         onclick="openModal(<?php echo $job['id']; ?>)">
                        <div class="job-main">
                            <div class="job-color" style="background: <?php echo $config['color']; ?>"></div>
                            <div class="job-info">
                                <div class="job-title"><?php echo htmlspecialchars($job['title']); ?></div>
                                <div class="job-dept"><?php echo htmlspecialchars($job['department'] ?? 'AlfaisalX'); ?></div>
                            </div>
                        </div>
                        <span class="job-type-badge" style="background: <?php echo $config['color']; ?>22; color: <?php echo $config['color']; ?>">
                            <?php echo $config['label']; ?>
                        </span>
                        <span class="job-arrow"><i class="fas fa-chevron-right"></i></span>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </section>

        <section class="section cta-section">
            <div class="container">
                <div class="cta-content">
                    <h2>Don't See a Perfect Fit?</h2>
                    <p>Send us your CV and we'll keep you in mind for future opportunities.</p>
                    <a href="mailto:<?php echo $application_email; ?>" class="btn btn-primary">
                        <i class="fas fa-envelope"></i> Contact Us
                    </a>
                </div>
            </div>
        </section>
    </main>

    <!-- Modal -->
    <div class="modal-overlay" id="jobModal">
        <div class="modal">
            <div class="modal-header">
                <div class="modal-header-top">
                    <h2 id="modalTitle"></h2>
                    <button class="modal-close" onclick="closeModal()"><i class="fas fa-times"></i></button>
                </div>
                <div class="modal-meta">
                    <span><i class="fas fa-building"></i><span id="modalDept"></span></span>
                    <span><i class="fas fa-map-marker-alt"></i><span id="modalLocation"></span></span>
                </div>
            </div>
            <div class="modal-body">
                <p class="modal-description" id="modalDesc"></p>
                
                <div class="detail-section" id="secResp">
                    <h4>Responsibilities</h4>
                    <ul id="listResp"></ul>
                </div>
                
                <div class="detail-section" id="secReq">
                    <h4>Requirements</h4>
                    <ul id="listReq"></ul>
                </div>
                
                <div class="detail-section" id="secPref">
                    <h4>Preferred</h4>
                    <ul id="listPref"></ul>
                </div>
                
                <div class="detail-section" id="secBen">
                    <h4>Benefits</h4>
                    <ul id="listBen"></ul>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn-secondary" onclick="closeModal()">Close</button>
                <a href="#" id="applyBtn" class="btn btn-primary"><i class="fas fa-paper-plane"></i> Apply Now</a>
            </div>
        </div>
    </div>

    <?php include '../includes/footer.php'; ?>

    <script>
        const jobs = <?php echo json_encode(array_map(function($job) {
            return [
                'id' => $job['id'],
                'title' => $job['title'],
                'department' => $job['department'] ?? 'AlfaisalX',
                'location' => $job['location'] ?? 'Riyadh, Saudi Arabia',
                'description' => $job['description'] ?? '',
                'responsibilities' => json_decode($job['responsibilities'] ?? '[]', true),
                'requirements' => json_decode($job['requirements'] ?? '[]', true),
                'preferred' => json_decode($job['preferred'] ?? '[]', true),
                'benefits' => json_decode($job['benefits'] ?? '[]', true)
            ];
        }, $jobs)); ?>;
        
        const email = '<?php echo $application_email; ?>';
        
        function openModal(id) {
            const job = jobs.find(j => j.id == id);
            if (!job) return;
            
            document.getElementById('modalTitle').textContent = job.title;
            document.getElementById('modalDept').textContent = job.department;
            document.getElementById('modalLocation').textContent = job.location;
            document.getElementById('modalDesc').textContent = job.description;
            
            fillList('listResp', 'secResp', job.responsibilities);
            fillList('listReq', 'secReq', job.requirements);
            fillList('listPref', 'secPref', job.preferred);
            fillList('listBen', 'secBen', job.benefits);
            
            document.getElementById('applyBtn').href = `mailto:${email}?subject=${encodeURIComponent('Application: ' + job.title)}`;
            
            document.getElementById('jobModal').classList.add('active');
            document.body.style.overflow = 'hidden';
        }
        
        function fillList(listId, secId, items) {
            const ul = document.getElementById(listId);
            const sec = document.getElementById(secId);
            ul.innerHTML = '';
            if (!items || !items.length) { sec.style.display = 'none'; return; }
            sec.style.display = 'block';
            items.forEach(i => { const li = document.createElement('li'); li.textContent = i; ul.appendChild(li); });
        }
        
        function closeModal() {
            document.getElementById('jobModal').classList.remove('active');
            document.body.style.overflow = '';
        }
        
        document.getElementById('jobModal').addEventListener('click', e => { if (e.target.id === 'jobModal') closeModal(); });
        document.addEventListener('keydown', e => { if (e.key === 'Escape') closeModal(); });
    </script>
</body>
</html>
