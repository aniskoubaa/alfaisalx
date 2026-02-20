<?php
/**
 * AlfaisalX Admin - Stats Management
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
            'value' => trim($_POST['value'] ?? ''),
            'label' => trim($_POST['label'] ?? ''),
            'icon' => trim($_POST['icon'] ?? 'chart-bar'),
            'sort_order' => intval($_POST['sort_order'] ?? 0)
        ];
        
        if (empty($data['value']) || empty($data['label'])) {
            $error = 'Value and Label are required';
        } else {
            if ($postAction === 'create') {
                $db->execute(
                    "INSERT INTO stats (value, label, icon, sort_order) VALUES (?, ?, ?, ?)",
                    array_values($data)
                );
                $message = 'Stat created successfully';
                $action = 'list';
            } else {
                $statId = $_POST['id'];
                $db->execute(
                    "UPDATE stats SET value=?, label=?, icon=?, sort_order=? WHERE id=?",
                    [...array_values($data), $statId]
                );
                $message = 'Stat updated successfully';
                $action = 'list';
            }
        }
    } elseif ($postAction === 'delete') {
        $statId = $_POST['id'];
        $db->execute("DELETE FROM stats WHERE id = ?", [$statId]);
        $message = 'Stat deleted successfully';
    }
}

// Get data
$stats = $db->query("SELECT * FROM stats ORDER BY sort_order");
$editStat = null;
if ($action === 'edit' && $id) {
    $editStat = $db->queryOne("SELECT * FROM stats WHERE id = ?", [$id]);
    if (!$editStat) {
        $error = 'Stat not found';
        $action = 'list';
    }
}

include 'includes/header.php';
?>

<div class="top-bar">
    <h1 class="page-title">Homepage Stats</h1>
    <div>
        <?php if ($action === 'list'): ?>
            <a href="?action=add" class="btn btn-primary"><i class="fas fa-plus"></i> Add Stat</a>
        <?php else: ?>
            <a href="stats.php" class="btn btn-primary"><i class="fas fa-arrow-left"></i> Back to List</a>
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
        <!-- Preview -->
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Preview (as shown on homepage)</h3>
            </div>
            <div class="card-body">
                <div class="stats-grid">
                    <?php foreach ($stats as $index => $stat): ?>
                    <?php 
                    $colors = ['blue', 'purple', 'green', 'orange'];
                    $color = $colors[$index % 4];
                    ?>
                    <div class="stat-card">
                        <div class="stat-icon <?php echo $color; ?>">
                            <i class="fas fa-<?php echo htmlspecialchars($stat['icon']); ?>"></i>
                        </div>
                        <div class="stat-value"><?php echo htmlspecialchars($stat['value']); ?></div>
                        <div class="stat-label"><?php echo htmlspecialchars($stat['label']); ?></div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
        
        <!-- List -->
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">All Stats (<?php echo count($stats); ?>)</h3>
            </div>
            <div class="table-responsive">
                <table>
                    <thead>
                        <tr>
                            <th>Order</th>
                            <th>Value</th>
                            <th>Label</th>
                            <th>Icon</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($stats as $stat): ?>
                        <tr>
                            <td><?php echo $stat['sort_order']; ?></td>
                            <td><strong><?php echo htmlspecialchars($stat['value']); ?></strong></td>
                            <td><?php echo htmlspecialchars($stat['label']); ?></td>
                            <td><i class="fas fa-<?php echo htmlspecialchars($stat['icon']); ?>"></i> <?php echo htmlspecialchars($stat['icon']); ?></td>
                            <td>
                                <div class="actions">
                                    <a href="?action=edit&id=<?php echo $stat['id']; ?>" class="btn btn-sm btn-warning btn-icon" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form method="POST" style="display:inline;" onsubmit="return confirmDelete('Delete this stat?');">
                                        <input type="hidden" name="action" value="delete">
                                        <input type="hidden" name="id" value="<?php echo $stat['id']; ?>">
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
                <h3 class="card-title"><?php echo $action === 'edit' ? 'Edit' : 'Add'; ?> Stat</h3>
            </div>
            <div class="card-body">
                <form method="POST">
                    <input type="hidden" name="action" value="<?php echo $action === 'edit' ? 'update' : 'create'; ?>">
                    <?php if ($editStat): ?>
                        <input type="hidden" name="id" value="<?php echo $editStat['id']; ?>">
                    <?php endif; ?>
                    
                    <div class="row">
                        <div class="col-6">
                            <div class="form-group">
                                <label class="form-label">Value *</label>
                                <input type="text" name="value" class="form-control" required 
                                       value="<?php echo htmlspecialchars($editStat['value'] ?? ''); ?>"
                                       placeholder="e.g., 10+, 4, 2025">
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group">
                                <label class="form-label">Label *</label>
                                <input type="text" name="label" class="form-control" required 
                                       value="<?php echo htmlspecialchars($editStat['label'] ?? ''); ?>"
                                       placeholder="e.g., Research Papers/Year">
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-6">
                            <div class="form-group">
                                <label class="form-label">Icon (FontAwesome name)</label>
                                <input type="text" name="icon" class="form-control" 
                                       value="<?php echo htmlspecialchars($editStat['icon'] ?? 'chart-bar'); ?>"
                                       placeholder="e.g., file-alt, cubes, flask, calendar-alt">
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group">
                                <label class="form-label">Sort Order</label>
                                <input type="number" name="sort_order" class="form-control" 
                                       value="<?php echo $editStat['sort_order'] ?? 0; ?>">
                            </div>
                        </div>
                    </div>
                    
                    <div style="margin-top: 1rem;">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> <?php echo $action === 'edit' ? 'Update' : 'Create'; ?> Stat
                        </button>
                        <a href="stats.php" class="btn" style="background: var(--gray);">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    <?php endif; ?>
</div>

<?php include 'includes/footer.php'; ?>
