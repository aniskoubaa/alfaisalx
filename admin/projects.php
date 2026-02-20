<?php
/**
 * AlfaisalX Admin - Projects CRUD
 */
require_once dirname(__DIR__) . '/database/Database.php';
$db = Database::getInstance();

$message = '';
$error = '';
$action = $_GET['action'] ?? 'list';
$id = $_GET['id'] ?? null;

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $postAction = $_POST['action'] ?? '';
    
    if ($postAction === 'create' || $postAction === 'update') {
        $data = [
            'slug' => trim($_POST['slug'] ?? ''),
            'title' => trim($_POST['title'] ?? ''),
            'short_description' => trim($_POST['short_description'] ?? ''),
            'description' => trim($_POST['description'] ?? ''),
            'status' => $_POST['status'] ?? 'proposed',
            'partners' => trim($_POST['partners'] ?? ''),
            'funding_source' => trim($_POST['funding_source'] ?? ''),
            'budget' => trim($_POST['budget'] ?? ''),
            'start_year' => trim($_POST['start_year'] ?? ''),
            'end_year' => trim($_POST['end_year'] ?? ''),
            'is_featured' => isset($_POST['is_featured']) ? 1 : 0,
            'sort_order' => intval($_POST['sort_order'] ?? 0)
        ];
        
        // Generate slug if empty
        if (empty($data['slug'])) {
            $data['slug'] = strtolower(preg_replace('/[^a-zA-Z0-9]+/', '-', $data['title']));
        }
        
        // Convert partners to JSON
        $partnersArray = array_filter(array_map('trim', explode(',', $data['partners'])));
        $data['partners'] = json_encode($partnersArray);
        
        if (empty($data['title'])) {
            $error = 'Title is required';
        } else {
            if ($postAction === 'create') {
                $db->execute(
                    "INSERT INTO projects (slug, title, short_description, description, status, partners, funding_source, budget, start_year, end_year, is_featured, sort_order) 
                     VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)",
                    array_values($data)
                );
                $message = 'Project created successfully';
                $action = 'list';
            } else {
                $projectId = $_POST['id'];
                $db->execute(
                    "UPDATE projects SET slug=?, title=?, short_description=?, description=?, status=?, partners=?, funding_source=?, budget=?, start_year=?, end_year=?, is_featured=?, sort_order=? WHERE id=?",
                    [...array_values($data), $projectId]
                );
                $message = 'Project updated successfully';
                $action = 'list';
            }
        }
    } elseif ($postAction === 'delete') {
        $projectId = $_POST['id'];
        $db->execute("DELETE FROM projects WHERE id = ?", [$projectId]);
        $message = 'Project deleted successfully';
    }
}

// Get data
$projects = $db->query("SELECT * FROM projects ORDER BY is_featured DESC, sort_order, title");
$editProject = null;
if ($action === 'edit' && $id) {
    $editProject = $db->queryOne("SELECT * FROM projects WHERE id = ?", [$id]);
    if (!$editProject) {
        $error = 'Project not found';
        $action = 'list';
    }
}

include 'includes/header.php';
?>

<div class="top-bar">
    <h1 class="page-title">Projects</h1>
    <div>
        <?php if ($action === 'list'): ?>
            <a href="?action=add" class="btn btn-primary"><i class="fas fa-plus"></i> Add Project</a>
        <?php else: ?>
            <a href="projects.php" class="btn btn-primary"><i class="fas fa-arrow-left"></i> Back to List</a>
        <?php endif; ?>
    </div>
</div>

<div class="content">
    <?php if ($message): ?>
        <div class="alert alert-success"><i class="fas fa-check-circle"></i> <?php echo htmlspecialchars($message); ?></div>
    <?php endif; ?>
    <?php if ($error): ?>
        <div class="alert alert-danger"><i class="fas fa-exclamation-circle"></i> <?php echo htmlspecialchars($error); ?></div>
    <?php endif; ?>
    
    <?php if ($action === 'list'): ?>
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">All Projects (<?php echo count($projects); ?>)</h3>
            </div>
            <div class="table-responsive">
                <table>
                    <thead>
                        <tr>
                            <th>Title</th>
                            <th>Status</th>
                            <th>Funding</th>
                            <th>Years</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($projects as $project): ?>
                        <tr>
                            <td>
                                <strong><?php echo htmlspecialchars($project['title']); ?></strong>
                                <?php if ($project['is_featured']): ?>
                                    <span class="badge badge-warning" style="margin-left: 0.5rem;">Featured</span>
                                <?php endif; ?>
                                <br><small style="color: var(--gray);"><?php echo htmlspecialchars(substr($project['short_description'] ?? '', 0, 60)); ?>...</small>
                            </td>
                            <td>
                                <?php 
                                $statusClass = match($project['status']) {
                                    'ongoing' => 'badge-success',
                                    'completed' => 'badge-success',
                                    'proposed' => 'badge-warning',
                                    default => 'badge-success'
                                };
                                ?>
                                <span class="badge <?php echo $statusClass; ?>"><?php echo htmlspecialchars(ucfirst($project['status'])); ?></span>
                            </td>
                            <td><?php echo htmlspecialchars($project['funding_source']); ?></td>
                            <td><?php echo $project['start_year']; ?><?php echo $project['end_year'] ? ' - ' . $project['end_year'] : ''; ?></td>
                            <td>
                                <div class="actions">
                                    <a href="?action=edit&id=<?php echo $project['id']; ?>" class="btn btn-sm btn-warning btn-icon" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form method="POST" style="display:inline;" onsubmit="return confirmDelete('Delete this project?');">
                                        <input type="hidden" name="action" value="delete">
                                        <input type="hidden" name="id" value="<?php echo $project['id']; ?>">
                                        <button type="submit" class="btn btn-sm btn-danger btn-icon" title="Delete">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
        
    <?php else: ?>
        <div class="card">
            <div class="card-header">
                <h3 class="card-title"><?php echo $action === 'edit' ? 'Edit' : 'Add'; ?> Project</h3>
            </div>
            <div class="card-body">
                <form method="POST">
                    <input type="hidden" name="action" value="<?php echo $action === 'edit' ? 'update' : 'create'; ?>">
                    <?php if ($editProject): ?>
                        <input type="hidden" name="id" value="<?php echo $editProject['id']; ?>">
                    <?php endif; ?>
                    
                    <div class="row">
                        <div class="col-6">
                            <div class="form-group">
                                <label class="form-label">Title *</label>
                                <input type="text" name="title" class="form-control" required 
                                       value="<?php echo htmlspecialchars($editProject['title'] ?? ''); ?>">
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group">
                                <label class="form-label">Slug (auto-generated if empty)</label>
                                <input type="text" name="slug" class="form-control" 
                                       value="<?php echo htmlspecialchars($editProject['slug'] ?? ''); ?>">
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">Short Description</label>
                        <textarea name="short_description" class="form-control" rows="2"><?php echo htmlspecialchars($editProject['short_description'] ?? ''); ?></textarea>
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">Full Description</label>
                        <textarea name="description" class="form-control" rows="5"><?php echo htmlspecialchars($editProject['description'] ?? ''); ?></textarea>
                    </div>
                    
                    <div class="row">
                        <div class="col-6">
                            <div class="form-group">
                                <label class="form-label">Status</label>
                                <select name="status" class="form-control">
                                    <option value="proposed" <?php echo ($editProject['status'] ?? '') === 'proposed' ? 'selected' : ''; ?>>Proposed</option>
                                    <option value="ongoing" <?php echo ($editProject['status'] ?? '') === 'ongoing' ? 'selected' : ''; ?>>Ongoing</option>
                                    <option value="completed" <?php echo ($editProject['status'] ?? '') === 'completed' ? 'selected' : ''; ?>>Completed</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group">
                                <label class="form-label">Funding Source</label>
                                <input type="text" name="funding_source" class="form-control" 
                                       value="<?php echo htmlspecialchars($editProject['funding_source'] ?? ''); ?>"
                                       placeholder="e.g., Industry Grant, RDIA">
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-6">
                            <div class="form-group">
                                <label class="form-label">Budget</label>
                                <input type="text" name="budget" class="form-control" 
                                       value="<?php echo htmlspecialchars($editProject['budget'] ?? ''); ?>"
                                       placeholder="e.g., 500,000 SAR">
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group">
                                <label class="form-label">Partners (comma-separated)</label>
                                <input type="text" name="partners" class="form-control" 
                                       value="<?php echo htmlspecialchars(implode(', ', json_decode($editProject['partners'] ?? '[]', true) ?: [])); ?>"
                                       placeholder="e.g., HUMAIN, Bako Motors">
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-6">
                            <div class="form-group">
                                <label class="form-label">Start Year</label>
                                <input type="text" name="start_year" class="form-control" 
                                       value="<?php echo htmlspecialchars($editProject['start_year'] ?? ''); ?>"
                                       placeholder="e.g., 2025">
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group">
                                <label class="form-label">End Year</label>
                                <input type="text" name="end_year" class="form-control" 
                                       value="<?php echo htmlspecialchars($editProject['end_year'] ?? ''); ?>"
                                       placeholder="e.g., 2028">
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-6">
                            <div class="form-group">
                                <label class="form-label">Sort Order</label>
                                <input type="number" name="sort_order" class="form-control" 
                                       value="<?php echo $editProject['sort_order'] ?? 0; ?>">
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group">
                                <label class="form-label">&nbsp;</label>
                                <div style="padding-top: 0.5rem;">
                                    <label>
                                        <input type="checkbox" name="is_featured" value="1" 
                                               <?php echo ($editProject['is_featured'] ?? 0) ? 'checked' : ''; ?>>
                                        Featured Project
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div style="margin-top: 1rem;">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> <?php echo $action === 'edit' ? 'Update' : 'Create'; ?> Project
                        </button>
                        <a href="projects.php" class="btn" style="background: var(--gray);">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    <?php endif; ?>
</div>

<?php include 'includes/footer.php'; ?>
