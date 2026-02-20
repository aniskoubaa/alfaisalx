<?php
/**
 * AlfaisalX Admin Dashboard
 */
require_once dirname(__DIR__) . '/database/Database.php';
$db = Database::getInstance();

// Get counts for dashboard
$teamCount = $db->queryOne("SELECT COUNT(*) as count FROM team_members WHERE is_active = 1")['count'];
$researchCount = $db->queryOne("SELECT COUNT(*) as count FROM research_areas WHERE is_active = 1")['count'];
$partnersCount = $db->queryOne("SELECT COUNT(*) as count FROM partners WHERE is_active = 1")['count'];
$publicationsCount = $db->queryOne("SELECT COUNT(*) as count FROM publications")['count'];
$newsCount = $db->queryOne("SELECT COUNT(*) as count FROM news WHERE is_published = 1")['count'];
$jobsCount = $db->queryOne("SELECT COUNT(*) as count FROM jobs WHERE is_active = 1")['count'] ?? 0;

// Recent items
$recentNews = $db->query("SELECT * FROM news ORDER BY created_at DESC LIMIT 5");
$recentTeam = $db->query("SELECT * FROM team_members ORDER BY created_at DESC LIMIT 5");

include 'includes/header.php';
?>

<div class="top-bar">
    <h1 class="page-title">Dashboard</h1>
    <div>
        <a href="../" target="_blank" class="btn btn-primary">
            <i class="fas fa-external-link-alt"></i> View Website
        </a>
    </div>
</div>

<div class="content">
    <!-- Stats Grid -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-icon blue"><i class="fas fa-users"></i></div>
            <div class="stat-value"><?php echo $teamCount; ?></div>
            <div class="stat-label">Team Members</div>
        </div>
        <div class="stat-card">
            <div class="stat-icon purple"><i class="fas fa-flask"></i></div>
            <div class="stat-value"><?php echo $researchCount; ?></div>
            <div class="stat-label">Research Areas</div>
        </div>
        <div class="stat-card">
            <div class="stat-icon green"><i class="fas fa-handshake"></i></div>
            <div class="stat-value"><?php echo $partnersCount; ?></div>
            <div class="stat-label">Partners</div>
        </div>
        <div class="stat-card">
            <div class="stat-icon orange"><i class="fas fa-file-alt"></i></div>
            <div class="stat-value"><?php echo $publicationsCount; ?></div>
            <div class="stat-label">Publications</div>
        </div>
    </div>
    
    <div class="row">
        <!-- Recent News -->
        <div class="col-6">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Recent News</h3>
                    <a href="news.php" class="btn btn-sm btn-primary">View All</a>
                </div>
                <div class="card-body">
                    <?php if (empty($recentNews)): ?>
                        <p style="color: var(--gray);">No news items yet.</p>
                    <?php else: ?>
                        <table>
                            <thead>
                                <tr>
                                    <th>Title</th>
                                    <th>Type</th>
                                    <th>Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($recentNews as $news): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($news['title']); ?></td>
                                    <td><span class="badge badge-success"><?php echo htmlspecialchars($news['type']); ?></span></td>
                                    <td><?php echo date('M d, Y', strtotime($news['date'])); ?></td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        
        <!-- Recent Team -->
        <div class="col-6">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Team Members</h3>
                    <a href="team.php" class="btn btn-sm btn-primary">View All</a>
                </div>
                <div class="card-body">
                    <?php if (empty($recentTeam)): ?>
                        <p style="color: var(--gray);">No team members yet.</p>
                    <?php else: ?>
                        <table>
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Role</th>
                                    <th>Category</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($recentTeam as $member): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($member['name']); ?></td>
                                    <td><?php echo htmlspecialchars($member['role']); ?></td>
                                    <td><span class="badge badge-success"><?php echo htmlspecialchars($member['category']); ?></span></td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Quick Actions -->
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Quick Actions</h3>
        </div>
        <div class="card-body">
            <div style="display: flex; gap: 1rem; flex-wrap: wrap;">
                <a href="team.php?action=add" class="btn btn-primary"><i class="fas fa-user-plus"></i> Add Team Member</a>
                <a href="news.php?action=add" class="btn btn-success"><i class="fas fa-plus"></i> Add News</a>
                <a href="publications.php?action=add" class="btn btn-warning"><i class="fas fa-file-plus"></i> Add Publication</a>
                <a href="partners.php?action=add" class="btn btn-primary"><i class="fas fa-handshake"></i> Add Partner</a>
                <a href="settings.php" class="btn btn-primary" style="background: var(--secondary);"><i class="fas fa-cog"></i> Settings</a>
            </div>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
