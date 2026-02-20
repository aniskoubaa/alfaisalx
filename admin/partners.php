<?php
/**
 * AlfaisalX Admin - Partners CRUD
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
            'name' => trim($_POST['name'] ?? ''),
            'full_name' => trim($_POST['full_name'] ?? ''),
            'type' => $_POST['type'] ?? 'industry',
            'logo' => trim($_POST['logo'] ?? ''),
            'description' => trim($_POST['description'] ?? ''),
            'collaboration_details' => trim($_POST['collaboration_details'] ?? ''),
            'website' => trim($_POST['website'] ?? ''),
            'is_active' => isset($_POST['is_active']) ? 1 : 0,
            'sort_order' => intval($_POST['sort_order'] ?? 0)
        ];
        
        if (empty($data['name'])) {
            $error = 'Name is required';
        } else {
            if ($postAction === 'create') {
                $db->execute(
                    "INSERT INTO partners (name, full_name, type, logo, description, collaboration_details, website, is_active, sort_order) 
                     VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)",
                    array_values($data)
                );
                $message = 'Partner created successfully';
                $action = 'list';
            } else {
                $partnerId = $_POST['id'];
                $db->execute(
                    "UPDATE partners SET name=?, full_name=?, type=?, logo=?, description=?, collaboration_details=?, website=?, is_active=?, sort_order=? WHERE id=?",
                    [...array_values($data), $partnerId]
                );
                $message = 'Partner updated successfully';
                $action = 'list';
            }
        }
    } elseif ($postAction === 'delete') {
        $partnerId = $_POST['id'];
        $db->execute("DELETE FROM partners WHERE id = ?", [$partnerId]);
        $message = 'Partner deleted successfully';
    }
}

// Get data
$partners = $db->query("SELECT * FROM partners ORDER BY type, sort_order, name");
$editPartner = null;
if ($action === 'edit' && $id) {
    $editPartner = $db->queryOne("SELECT * FROM partners WHERE id = ?", [$id]);
    if (!$editPartner) {
        $error = 'Partner not found';
        $action = 'list';
    }
}

include 'includes/header.php';
?>

<div class="top-bar">
    <h1 class="page-title">Partners</h1>
    <div>
        <?php if ($action === 'list'): ?>
            <a href="?action=add" class="btn btn-primary"><i class="fas fa-plus"></i> Add Partner</a>
        <?php else: ?>
            <a href="partners.php" class="btn btn-primary"><i class="fas fa-arrow-left"></i> Back to List</a>
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
                <h3 class="card-title">All Partners (<?php echo count($partners); ?>)</h3>
            </div>
            <div class="table-responsive">
                <table>
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Type</th>
                            <th>Description</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($partners as $partner): ?>
                        <tr>
                            <td>
                                <strong><?php echo htmlspecialchars($partner['name']); ?></strong>
                                <?php if ($partner['full_name']): ?>
                                    <br><small style="color: var(--gray);"><?php echo htmlspecialchars($partner['full_name']); ?></small>
                                <?php endif; ?>
                            </td>
                            <td>
                                <span class="badge <?php echo $partner['type'] === 'government' ? 'badge-warning' : 'badge-success'; ?>">
                                    <?php echo htmlspecialchars(ucfirst($partner['type'])); ?>
                                </span>
                            </td>
                            <td><?php echo htmlspecialchars(substr($partner['description'] ?? '', 0, 80)); ?>...</td>
                            <td>
                                <?php if ($partner['is_active']): ?>
                                    <span class="badge badge-success">Active</span>
                                <?php else: ?>
                                    <span class="badge badge-danger">Inactive</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <div class="actions">
                                    <a href="?action=edit&id=<?php echo $partner['id']; ?>" class="btn btn-sm btn-warning btn-icon" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form method="POST" style="display:inline;" onsubmit="return confirmDelete('Delete this partner?');">
                                        <input type="hidden" name="action" value="delete">
                                        <input type="hidden" name="id" value="<?php echo $partner['id']; ?>">
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
                <h3 class="card-title"><?php echo $action === 'edit' ? 'Edit' : 'Add'; ?> Partner</h3>
            </div>
            <div class="card-body">
                <form method="POST">
                    <input type="hidden" name="action" value="<?php echo $action === 'edit' ? 'update' : 'create'; ?>">
                    <?php if ($editPartner): ?>
                        <input type="hidden" name="id" value="<?php echo $editPartner['id']; ?>">
                    <?php endif; ?>
                    
                    <div class="row">
                        <div class="col-6">
                            <div class="form-group">
                                <label class="form-label">Short Name *</label>
                                <input type="text" name="name" class="form-control" required 
                                       value="<?php echo htmlspecialchars($editPartner['name'] ?? ''); ?>"
                                       placeholder="e.g., HUMAIN">
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group">
                                <label class="form-label">Full Name</label>
                                <input type="text" name="full_name" class="form-control" 
                                       value="<?php echo htmlspecialchars($editPartner['full_name'] ?? ''); ?>"
                                       placeholder="e.g., HUMAIN - AI & Technology Solutions">
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-6">
                            <div class="form-group">
                                <label class="form-label">Type</label>
                                <select name="type" class="form-control">
                                    <option value="industry" <?php echo ($editPartner['type'] ?? '') === 'industry' ? 'selected' : ''; ?>>Industry</option>
                                    <option value="government" <?php echo ($editPartner['type'] ?? '') === 'government' ? 'selected' : ''; ?>>Government</option>
                                    <option value="academic" <?php echo ($editPartner['type'] ?? '') === 'academic' ? 'selected' : ''; ?>>Academic</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group">
                                <label class="form-label">Website</label>
                                <input type="url" name="website" class="form-control" 
                                       value="<?php echo htmlspecialchars($editPartner['website'] ?? ''); ?>">
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">Logo Filename</label>
                        <input type="text" name="logo" class="form-control" 
                               value="<?php echo htmlspecialchars($editPartner['logo'] ?? ''); ?>"
                               placeholder="e.g., humain.png (place in assets/images/partners/)">
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">Description</label>
                        <textarea name="description" class="form-control" rows="3"><?php echo htmlspecialchars($editPartner['description'] ?? ''); ?></textarea>
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">Collaboration Details</label>
                        <textarea name="collaboration_details" class="form-control" rows="3"><?php echo htmlspecialchars($editPartner['collaboration_details'] ?? ''); ?></textarea>
                    </div>
                    
                    <div class="row">
                        <div class="col-6">
                            <div class="form-group">
                                <label class="form-label">Sort Order</label>
                                <input type="number" name="sort_order" class="form-control" 
                                       value="<?php echo $editPartner['sort_order'] ?? 0; ?>">
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group">
                                <label class="form-label">&nbsp;</label>
                                <div style="padding-top: 0.5rem;">
                                    <label>
                                        <input type="checkbox" name="is_active" value="1" 
                                               <?php echo ($editPartner['is_active'] ?? 1) ? 'checked' : ''; ?>>
                                        Active (visible on website)
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div style="margin-top: 1rem;">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> <?php echo $action === 'edit' ? 'Update' : 'Create'; ?> Partner
                        </button>
                        <a href="partners.php" class="btn" style="background: var(--gray);">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    <?php endif; ?>
</div>

<?php include 'includes/footer.php'; ?>
