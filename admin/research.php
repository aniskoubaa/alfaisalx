<?php
/**
 * AlfaisalX Admin - Research Areas CRUD
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
            'number' => trim($_POST['number'] ?? ''),
            'slug' => trim($_POST['slug'] ?? ''),
            'title' => trim($_POST['title'] ?? ''),
            'short_title' => trim($_POST['short_title'] ?? ''),
            'icon' => trim($_POST['icon'] ?? 'flask'),
            'color' => trim($_POST['color'] ?? '#0077b6'),
            'description' => trim($_POST['description'] ?? ''),
            'description_extended' => trim($_POST['description_extended'] ?? ''),
            'tags' => trim($_POST['tags'] ?? ''),
            'is_active' => isset($_POST['is_active']) ? 1 : 0,
            'sort_order' => intval($_POST['sort_order'] ?? 0)
        ];
        
        // Generate slug if empty
        if (empty($data['slug'])) {
            $data['slug'] = strtolower(preg_replace('/[^a-zA-Z0-9]+/', '-', $data['title']));
        }
        
        // Convert tags to JSON
        $tagsArray = array_filter(array_map('trim', explode(',', $data['tags'])));
        $data['tags'] = json_encode($tagsArray);
        
        if (empty($data['title'])) {
            $error = 'Title is required';
        } else {
            if ($postAction === 'create') {
                $db->execute(
                    "INSERT INTO research_areas (number, slug, title, short_title, icon, color, description, description_extended, tags, is_active, sort_order) 
                     VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)",
                    array_values($data)
                );
                $message = 'Research area created successfully';
                $action = 'list';
            } else {
                $areaId = $_POST['id'];
                $db->execute(
                    "UPDATE research_areas SET number=?, slug=?, title=?, short_title=?, icon=?, color=?, description=?, description_extended=?, tags=?, is_active=?, sort_order=? WHERE id=?",
                    [...array_values($data), $areaId]
                );
                $message = 'Research area updated successfully';
                $action = 'list';
            }
        }
    } elseif ($postAction === 'delete') {
        $areaId = $_POST['id'];
        $db->execute("DELETE FROM research_areas WHERE id = ?", [$areaId]);
        $message = 'Research area deleted successfully';
    }
}

// Get data
$areas = $db->query("SELECT * FROM research_areas ORDER BY sort_order, title");
$editArea = null;
if ($action === 'edit' && $id) {
    $editArea = $db->queryOne("SELECT * FROM research_areas WHERE id = ?", [$id]);
    if (!$editArea) {
        $error = 'Research area not found';
        $action = 'list';
    }
}

include 'includes/header.php';
?>

<div class="top-bar">
    <h1 class="page-title">Research Areas</h1>
    <div>
        <?php if ($action === 'list'): ?>
            <a href="?action=add" class="btn btn-primary"><i class="fas fa-plus"></i> Add Research Area</a>
        <?php else: ?>
            <a href="research.php" class="btn btn-primary"><i class="fas fa-arrow-left"></i> Back to List</a>
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
                <h3 class="card-title">All Research Areas (<?php echo count($areas); ?>)</h3>
            </div>
            <div class="table-responsive">
                <table>
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Title</th>
                            <th>Short Title</th>
                            <th>Icon</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($areas as $area): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($area['number']); ?></td>
                            <td>
                                <strong><?php echo htmlspecialchars($area['title']); ?></strong>
                                <br><small style="color: var(--gray);"><?php echo htmlspecialchars($area['slug']); ?></small>
                            </td>
                            <td><?php echo htmlspecialchars($area['short_title']); ?></td>
                            <td>
                                <i class="fas fa-<?php echo htmlspecialchars($area['icon']); ?>" style="color: <?php echo htmlspecialchars($area['color']); ?>;"></i>
                            </td>
                            <td>
                                <?php if ($area['is_active']): ?>
                                    <span class="badge badge-success">Active</span>
                                <?php else: ?>
                                    <span class="badge badge-danger">Inactive</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <div class="actions">
                                    <a href="?action=edit&id=<?php echo $area['id']; ?>" class="btn btn-sm btn-warning btn-icon" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form method="POST" style="display:inline;" onsubmit="return confirmDelete('Delete this research area?');">
                                        <input type="hidden" name="action" value="delete">
                                        <input type="hidden" name="id" value="<?php echo $area['id']; ?>">
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
                <h3 class="card-title"><?php echo $action === 'edit' ? 'Edit' : 'Add'; ?> Research Area</h3>
            </div>
            <div class="card-body">
                <form method="POST">
                    <input type="hidden" name="action" value="<?php echo $action === 'edit' ? 'update' : 'create'; ?>">
                    <?php if ($editArea): ?>
                        <input type="hidden" name="id" value="<?php echo $editArea['id']; ?>">
                    <?php endif; ?>
                    
                    <div class="row">
                        <div class="col-6">
                            <div class="form-group">
                                <label class="form-label">Title *</label>
                                <input type="text" name="title" class="form-control" required 
                                       value="<?php echo htmlspecialchars($editArea['title'] ?? ''); ?>">
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group">
                                <label class="form-label">Short Title</label>
                                <input type="text" name="short_title" class="form-control" 
                                       value="<?php echo htmlspecialchars($editArea['short_title'] ?? ''); ?>"
                                       placeholder="e.g., MedX, Robotics">
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-6">
                            <div class="form-group">
                                <label class="form-label">Number</label>
                                <input type="text" name="number" class="form-control" 
                                       value="<?php echo htmlspecialchars($editArea['number'] ?? ''); ?>"
                                       placeholder="e.g., 01, 02">
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group">
                                <label class="form-label">Slug (auto-generated if empty)</label>
                                <input type="text" name="slug" class="form-control" 
                                       value="<?php echo htmlspecialchars($editArea['slug'] ?? ''); ?>">
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-6">
                            <div class="form-group">
                                <label class="form-label">Icon (FontAwesome name)</label>
                                <input type="text" name="icon" class="form-control" 
                                       value="<?php echo htmlspecialchars($editArea['icon'] ?? 'flask'); ?>"
                                       placeholder="e.g., flask, robot, brain, heartbeat">
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group">
                                <label class="form-label">Color</label>
                                <input type="color" name="color" class="form-control" style="height: 42px;"
                                       value="<?php echo htmlspecialchars($editArea['color'] ?? '#0077b6'); ?>">
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">Description (short)</label>
                        <textarea name="description" class="form-control" rows="3"><?php echo htmlspecialchars($editArea['description'] ?? ''); ?></textarea>
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">Extended Description</label>
                        <textarea name="description_extended" class="form-control" rows="5"><?php echo htmlspecialchars($editArea['description_extended'] ?? ''); ?></textarea>
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">Tags (comma-separated)</label>
                        <input type="text" name="tags" class="form-control" 
                               value="<?php echo htmlspecialchars(implode(', ', json_decode($editArea['tags'] ?? '[]', true) ?: [])); ?>"
                               placeholder="e.g., Robotics, AI, UAVs, Healthcare">
                    </div>
                    
                    <div class="row">
                        <div class="col-6">
                            <div class="form-group">
                                <label class="form-label">Sort Order</label>
                                <input type="number" name="sort_order" class="form-control" 
                                       value="<?php echo $editArea['sort_order'] ?? 0; ?>">
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group">
                                <label class="form-label">&nbsp;</label>
                                <div style="padding-top: 0.5rem;">
                                    <label>
                                        <input type="checkbox" name="is_active" value="1" 
                                               <?php echo ($editArea['is_active'] ?? 1) ? 'checked' : ''; ?>>
                                        Active (visible on website)
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div style="margin-top: 1rem;">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> <?php echo $action === 'edit' ? 'Update' : 'Create'; ?> Research Area
                        </button>
                        <a href="research.php" class="btn" style="background: var(--gray);">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    <?php endif; ?>
</div>

<?php include 'includes/footer.php'; ?>
