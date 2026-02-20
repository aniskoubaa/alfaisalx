<?php
/**
 * AlfaisalX Admin Header
 */
require_once __DIR__ . '/auth.php';
$auth->requireAuth();

$currentPage = basename($_SERVER['PHP_SELF'], '.php');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AlfaisalX Admin - <?php echo ucfirst($currentPage); ?></title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary: #0077b6;
            --primary-dark: #005f8a;
            --secondary: #7c3aed;
            --success: #10b981;
            --warning: #f59e0b;
            --danger: #ef4444;
            --dark: #1e293b;
            --darker: #0f172a;
            --light: #f8fafc;
            --gray: #64748b;
            --border: #334155;
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background: var(--darker);
            color: var(--light);
            min-height: 100vh;
            display: flex;
        }
        
        /* Sidebar */
        .sidebar {
            width: 260px;
            background: var(--dark);
            border-right: 1px solid var(--border);
            height: 100vh;
            position: fixed;
            left: 0;
            top: 0;
            display: flex;
            flex-direction: column;
        }
        
        .sidebar-header {
            padding: 1.5rem;
            border-bottom: 1px solid var(--border);
        }
        
        .sidebar-header h1 {
            font-size: 1.25rem;
            color: var(--primary);
        }
        
        .sidebar-header span {
            font-size: 0.75rem;
            color: var(--gray);
        }
        
        .sidebar-nav {
            flex: 1;
            padding: 1rem 0;
            overflow-y: auto;
        }
        
        .nav-section {
            padding: 0.5rem 1.5rem;
            font-size: 0.7rem;
            text-transform: uppercase;
            color: var(--gray);
            letter-spacing: 0.05em;
            margin-top: 1rem;
        }
        
        .nav-section:first-child {
            margin-top: 0;
        }
        
        .nav-link {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.75rem 1.5rem;
            color: var(--light);
            text-decoration: none;
            transition: all 0.2s;
            border-left: 3px solid transparent;
        }
        
        .nav-link:hover {
            background: rgba(255,255,255,0.05);
        }
        
        .nav-link.active {
            background: rgba(0, 119, 182, 0.1);
            border-left-color: var(--primary);
            color: var(--primary);
        }
        
        .nav-link i {
            width: 20px;
            text-align: center;
        }
        
        .sidebar-footer {
            padding: 1rem 1.5rem;
            border-top: 1px solid var(--border);
        }
        
        .user-info {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            margin-bottom: 0.75rem;
        }
        
        .user-avatar {
            width: 36px;
            height: 36px;
            background: var(--primary);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
        }
        
        .user-name {
            font-size: 0.875rem;
        }
        
        .btn-logout {
            display: block;
            width: 100%;
            padding: 0.5rem;
            background: transparent;
            border: 1px solid var(--border);
            color: var(--gray);
            border-radius: 6px;
            cursor: pointer;
            transition: all 0.2s;
            font-size: 0.875rem;
        }
        
        .btn-logout:hover {
            background: var(--danger);
            border-color: var(--danger);
            color: white;
        }
        
        /* Main Content */
        .main-content {
            margin-left: 260px;
            flex: 1;
            min-height: 100vh;
        }
        
        .top-bar {
            background: var(--dark);
            border-bottom: 1px solid var(--border);
            padding: 1rem 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .page-title {
            font-size: 1.5rem;
            font-weight: 600;
        }
        
        .content {
            padding: 2rem;
        }
        
        /* Cards */
        .card {
            background: var(--dark);
            border: 1px solid var(--border);
            border-radius: 8px;
            margin-bottom: 1.5rem;
        }
        
        .card-header {
            padding: 1rem 1.5rem;
            border-bottom: 1px solid var(--border);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .card-title {
            font-size: 1rem;
            font-weight: 600;
        }
        
        .card-body {
            padding: 1.5rem;
        }
        
        /* Tables */
        .table-responsive {
            overflow-x: auto;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
        }
        
        th, td {
            padding: 0.75rem 1rem;
            text-align: left;
            border-bottom: 1px solid var(--border);
        }
        
        th {
            background: rgba(0,0,0,0.2);
            font-weight: 600;
            font-size: 0.75rem;
            text-transform: uppercase;
            color: var(--gray);
        }
        
        tr:hover {
            background: rgba(255,255,255,0.02);
        }
        
        /* Buttons */
        .btn {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.5rem 1rem;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-size: 0.875rem;
            font-weight: 500;
            transition: all 0.2s;
            text-decoration: none;
        }
        
        .btn-primary {
            background: var(--primary);
            color: white;
        }
        
        .btn-primary:hover {
            background: var(--primary-dark);
        }
        
        .btn-success {
            background: var(--success);
            color: white;
        }
        
        .btn-warning {
            background: var(--warning);
            color: var(--dark);
        }
        
        .btn-danger {
            background: var(--danger);
            color: white;
        }
        
        .btn-sm {
            padding: 0.35rem 0.75rem;
            font-size: 0.8rem;
        }
        
        .btn-icon {
            padding: 0.35rem 0.5rem;
        }
        
        /* Forms */
        .form-group {
            margin-bottom: 1rem;
        }
        
        .form-label {
            display: block;
            margin-bottom: 0.5rem;
            font-size: 0.875rem;
            font-weight: 500;
        }
        
        .form-control {
            width: 100%;
            padding: 0.625rem 0.875rem;
            background: var(--darker);
            border: 1px solid var(--border);
            border-radius: 6px;
            color: var(--light);
            font-size: 0.875rem;
        }
        
        .form-control:focus {
            outline: none;
            border-color: var(--primary);
        }
        
        textarea.form-control {
            min-height: 100px;
            resize: vertical;
        }
        
        select.form-control {
            cursor: pointer;
        }
        
        /* Badges */
        .badge {
            display: inline-block;
            padding: 0.25rem 0.5rem;
            font-size: 0.7rem;
            font-weight: 600;
            border-radius: 4px;
        }
        
        .badge-success {
            background: rgba(16, 185, 129, 0.2);
            color: var(--success);
        }
        
        .badge-warning {
            background: rgba(245, 158, 11, 0.2);
            color: var(--warning);
        }
        
        .badge-danger {
            background: rgba(239, 68, 68, 0.2);
            color: var(--danger);
        }
        
        /* Stats Grid */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1rem;
            margin-bottom: 2rem;
        }
        
        .stat-card {
            background: var(--dark);
            border: 1px solid var(--border);
            border-radius: 8px;
            padding: 1.5rem;
        }
        
        .stat-icon {
            width: 48px;
            height: 48px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.25rem;
            margin-bottom: 1rem;
        }
        
        .stat-icon.blue { background: rgba(0, 119, 182, 0.2); color: var(--primary); }
        .stat-icon.purple { background: rgba(124, 58, 237, 0.2); color: var(--secondary); }
        .stat-icon.green { background: rgba(16, 185, 129, 0.2); color: var(--success); }
        .stat-icon.orange { background: rgba(245, 158, 11, 0.2); color: var(--warning); }
        
        .stat-value {
            font-size: 2rem;
            font-weight: 700;
            margin-bottom: 0.25rem;
        }
        
        .stat-label {
            color: var(--gray);
            font-size: 0.875rem;
        }
        
        /* Alerts */
        .alert {
            padding: 1rem;
            border-radius: 6px;
            margin-bottom: 1rem;
        }
        
        .alert-success {
            background: rgba(16, 185, 129, 0.1);
            border: 1px solid var(--success);
            color: var(--success);
        }
        
        .alert-danger {
            background: rgba(239, 68, 68, 0.1);
            border: 1px solid var(--danger);
            color: var(--danger);
        }
        
        /* Modal */
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
        
        .modal {
            background: var(--dark);
            border: 1px solid var(--border);
            border-radius: 8px;
            width: 100%;
            max-width: 500px;
            max-height: 90vh;
            overflow-y: auto;
        }
        
        .modal-header {
            padding: 1rem 1.5rem;
            border-bottom: 1px solid var(--border);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .modal-title {
            font-size: 1.125rem;
            font-weight: 600;
        }
        
        .modal-close {
            background: none;
            border: none;
            color: var(--gray);
            cursor: pointer;
            font-size: 1.25rem;
        }
        
        .modal-body {
            padding: 1.5rem;
        }
        
        .modal-footer {
            padding: 1rem 1.5rem;
            border-top: 1px solid var(--border);
            display: flex;
            justify-content: flex-end;
            gap: 0.5rem;
        }
        
        /* Actions */
        .actions {
            display: flex;
            gap: 0.5rem;
        }
        
        /* Grid */
        .row {
            display: flex;
            flex-wrap: wrap;
            margin: -0.5rem;
        }
        
        .col-6 {
            width: 50%;
            padding: 0.5rem;
        }
        
        .col-12 {
            width: 100%;
            padding: 0.5rem;
        }
        
        @media (max-width: 768px) {
            .col-6 { width: 100%; }
        }
    </style>
</head>
<body>
    <!-- Sidebar -->
    <aside class="sidebar">
        <div class="sidebar-header">
            <h1><i class="fas fa-robot"></i> AlfaisalX</h1>
            <span>Admin Panel</span>
        </div>
        
        <nav class="sidebar-nav">
            <div class="nav-section">Main</div>
            <a href="index.php" class="nav-link <?php echo $currentPage === 'index' ? 'active' : ''; ?>">
                <i class="fas fa-tachometer-alt"></i> Dashboard
            </a>
            
            <div class="nav-section">Content</div>
            <a href="team.php" class="nav-link <?php echo $currentPage === 'team' ? 'active' : ''; ?>">
                <i class="fas fa-users"></i> Team Members
            </a>
            <a href="research.php" class="nav-link <?php echo $currentPage === 'research' ? 'active' : ''; ?>">
                <i class="fas fa-flask"></i> Research Areas
            </a>
            <a href="partners.php" class="nav-link <?php echo $currentPage === 'partners' ? 'active' : ''; ?>">
                <i class="fas fa-handshake"></i> Partners
            </a>
            <a href="publications.php" class="nav-link <?php echo $currentPage === 'publications' ? 'active' : ''; ?>">
                <i class="fas fa-file-alt"></i> Publications
            </a>
            <a href="news.php" class="nav-link <?php echo $currentPage === 'news' ? 'active' : ''; ?>">
                <i class="fas fa-newspaper"></i> News & Events
            </a>
            <a href="jobs.php" class="nav-link <?php echo $currentPage === 'jobs' ? 'active' : ''; ?>">
                <i class="fas fa-briefcase"></i> Jobs
            </a>
            <a href="projects.php" class="nav-link <?php echo $currentPage === 'projects' ? 'active' : ''; ?>">
                <i class="fas fa-project-diagram"></i> Projects
            </a>
            
            <div class="nav-section">System</div>
            <a href="settings.php" class="nav-link <?php echo $currentPage === 'settings' ? 'active' : ''; ?>">
                <i class="fas fa-cog"></i> Settings
            </a>
            <a href="stats.php" class="nav-link <?php echo $currentPage === 'stats' ? 'active' : ''; ?>">
                <i class="fas fa-chart-bar"></i> Stats
            </a>
        </nav>
        
        <div class="sidebar-footer">
            <div class="user-info">
                <div class="user-avatar">
                    <?php echo strtoupper(substr($_SESSION['admin_username'] ?? 'A', 0, 1)); ?>
                </div>
                <div class="user-name"><?php echo htmlspecialchars($_SESSION['admin_username'] ?? 'Admin'); ?></div>
            </div>
            <form action="logout.php" method="post">
                <button type="submit" class="btn-logout">
                    <i class="fas fa-sign-out-alt"></i> Logout
                </button>
            </form>
        </div>
    </aside>
    
    <!-- Main Content -->
    <main class="main-content">
