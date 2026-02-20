<?php
/**
 * AlfaisalX Admin - Jobs CRUD
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
            'title' => trim($_POST['title'] ?? ''),
            'slug' => trim($_POST['slug'] ?? ''),
            'department' => trim($_POST['department'] ?? ''),
            'type' => $_POST['type'] ?? 'full-time',
            'location' => trim($_POST['location'] ?? 'Riyadh, Saudi Arabia'),
            'description' => trim($_POST['description'] ?? ''),
            'responsibilities' => trim($_POST['responsibilities'] ?? ''),
            'requirements' => trim($_POST['requirements'] ?? ''),
            'benefits' => trim($_POST['benefits'] ?? ''),
            'apply_email' => trim($_POST['apply_email'] ?? ''),
            'is_featured' => isset($_POST['is_featured']) ? 1 : 0,
            'is_active' => isset($_POST['is_active']) ? 1 : 0,
            'sort_order' => intval($_POST['sort_order'] ?? 0)
        ];
        
        // Generate slug if empty
        if (empty($data['slug'])) {
            $data['slug'] = strtolower(preg_replace('/[^a-zA-Z0-9]+/', '-', $data['title']));
        }
        
        if (empty($data['title'])) {
            $error = 'Title is required';
        } else {
            if ($postAction === 'create') {
                $db->execute(
                    "INSERT INTO jobs (title, slug, department, type, location, description, responsibilities, requirements, benefits, apply_email, is_featured, is_active, sort_order) 
                     VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)",
                    array_values($data)
                );
                $message = 'Job created successfully';
                $action = 'list';
            } else {
                $jobId = $_POST['id'];
                $db->execute(
                    "UPDATE jobs SET title=?, slug=?, department=?, type=?, location=?, description=?, responsibilities=?, requirements=?, benefits=?, apply_email=?, is_featured=?, is_active=?, sort_order=? WHERE id=?",
                    [...array_values($data), $jobId]
                );
                $message = 'Job updated successfully';
                $action = 'list';
            }
        }
    } elseif ($postAction === 'delete') {
        $jobId = $_POST['id'];
        $db->execute("DELETE FROM jobs WHERE id = ?", [$jobId]);
        $message = 'Job deleted successfully';
    }
}

// Get data
$jobs = $db->query("SELECT * FROM jobs ORDER BY is_featured DESC, sort_order, title");
$editJob = null;
if ($action === 'edit' && $id) {
    $editJob = $db->queryOne("SELECT * FROM jobs WHERE id = ?", [$id]);
    if (!$editJob) {
        $error = 'Job not found';
        $action = 'list';
    }
}

include 'includes/header.php';
?>

<div class="top-bar">
    <h1 class="page-title">Jobs</h1>
    <div>
        <?php if ($action === 'list'): ?>
            <a href="?action=add" class="btn btn-primary"><i class="fas fa-plus"></i> Add Job</a>
        <?php else: ?>
            <a href="jobs.php" class="btn btn-primary"><i class="fas fa-arrow-left"></i> Back to List</a>
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
                <h3 class="card-title">All Jobs (<?php echo count($jobs); ?>)</h3>
            </div>
            <div class="table-responsive">
                <table>
                    <thead>
                        <tr>
                            <th>Title</th>
                            <th>Department</th>
                            <th>Type</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($jobs)): ?>
                        <tr>
                            <td colspan="5" style="text-align: center; color: var(--gray);">No jobs found. Add your first job posting.</td>
                        </tr>
                        <?php endif; ?>
                        <?php foreach ($jobs as $job): ?>
                        <tr>
                            <td>
                                <strong><?php echo htmlspecialchars($job['title']); ?></strong>
                                <?php if ($job['is_featured']): ?>
                                    <span class="badge badge-warning" style="margin-left: 0.5rem;">Featured</span>
                                <?php endif; ?>
                                <br><small style="color: var(--gray);"><?php echo htmlspecialchars($job['location']); ?></small>
                            </td>
                            <td><?php echo htmlspecialchars($job['department']); ?></td>
                            <td><span class="badge badge-success"><?php echo htmlspecialchars(ucfirst($job['type'])); ?></span></td>
                            <td>
                                <?php if ($job['is_active']): ?>
                                    <span class="badge badge-success">Active</span>
                                <?php else: ?>
                                    <span class="badge badge-danger">Closed</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <div class="actions">
                                    <a href="?action=edit&id=<?php echo $job['id']; ?>" class="btn btn-sm btn-warning btn-icon" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form method="POST" style="display:inline;" onsubmit="return confirmDelete('Delete this job?');">
                                        <input type="hidden" name="action" value="delete">
                                        <input type="hidden" name="id" value="<?php echo $job['id']; ?>">
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
                <h3 class="card-title"><?php echo $action === 'edit' ? 'Edit' : 'Add'; ?> Job</h3>
            </div>
            <div class="card-body">
                <form method="POST">
                    <input type="hidden" name="action" value="<?php echo $action === 'edit' ? 'update' : 'create'; ?>">
                    <?php if ($editJob): ?>
                        <input type="hidden" name="id" value="<?php echo $editJob['id']; ?>">
                    <?php endif; ?>
                    
                    <div class="row">
                        <div class="col-6">
                            <div class="form-group">
                                <label class="form-label">Job Title *</label>
                                <input type="text" name="title" class="form-control" required 
                                       value="<?php echo htmlspecialchars($editJob['title'] ?? ''); ?>">
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group">
                                <label class="form-label">Slug (auto-generated if empty)</label>
                                <input type="text" name="slug" class="form-control" 
                                       value="<?php echo htmlspecialchars($editJob['slug'] ?? ''); ?>">
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-6">
                            <div class="form-group">
                                <label class="form-label">Department</label>
                                <input type="text" name="department" class="form-control" 
                                       value="<?php echo htmlspecialchars($editJob['department'] ?? ''); ?>"
                                       placeholder="e.g., Robotics Lab, MedX Unit">
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group">
                                <label class="form-label">Type</label>
                                <select name="type" class="form-control">
                                    <option value="full-time" <?php echo ($editJob['type'] ?? '') === 'full-time' ? 'selected' : ''; ?>>Full-time</option>
                                    <option value="part-time" <?php echo ($editJob['type'] ?? '') === 'part-time' ? 'selected' : ''; ?>>Part-time</option>
                                    <option value="contract" <?php echo ($editJob['type'] ?? '') === 'contract' ? 'selected' : ''; ?>>Contract</option>
                                    <option value="internship" <?php echo ($editJob['type'] ?? '') === 'internship' ? 'selected' : ''; ?>>Internship</option>
                                    <option value="postdoc" <?php echo ($editJob['type'] ?? '') === 'postdoc' ? 'selected' : ''; ?>>Postdoctoral</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-6">
                            <div class="form-group">
                                <label class="form-label">Location</label>
                                <input type="text" name="location" class="form-control" 
                                       value="<?php echo htmlspecialchars($editJob['location'] ?? 'Riyadh, Saudi Arabia'); ?>">
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group">
                                <label class="form-label">Apply Email</label>
                                <input type="email" name="apply_email" class="form-control" 
                                       value="<?php echo htmlspecialchars($editJob['apply_email'] ?? ''); ?>"
                                       placeholder="e.g., careers@alfaisal.edu">
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">Description</label>
                        <textarea name="description" class="form-control" rows="3"><?php echo htmlspecialchars($editJob['description'] ?? ''); ?></textarea>
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">Responsibilities (one per line)</label>
                        <textarea name="responsibilities" class="form-control" rows="4"><?php echo htmlspecialchars($editJob['responsibilities'] ?? ''); ?></textarea>
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">Requirements (one per line)</label>
                        <textarea name="requirements" class="form-control" rows="4"><?php echo htmlspecialchars($editJob['requirements'] ?? ''); ?></textarea>
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">Benefits (one per line)</label>
                        <textarea name="benefits" class="form-control" rows="3"><?php echo htmlspecialchars($editJob['benefits'] ?? ''); ?></textarea>
                    </div>
                    
                    <div class="row">
                        <div class="col-6">
                            <div class="form-group">
                                <label class="form-label">Sort Order</label>
                                <input type="number" name="sort_order" class="form-control" 
                                       value="<?php echo $editJob['sort_order'] ?? 0; ?>">
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group">
                                <label class="form-label">&nbsp;</label>
                                <div style="padding-top: 0.5rem;">
                                    <label style="margin-right: 1rem;">
                                        <input type="checkbox" name="is_active" value="1" 
                                               <?php echo ($editJob['is_active'] ?? 1) ? 'checked' : ''; ?>>
                                        Active
                                    </label>
                                    <label>
                                        <input type="checkbox" name="is_featured" value="1" 
                                               <?php echo ($editJob['is_featured'] ?? 0) ? 'checked' : ''; ?>>
                                        Featured
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div style="margin-top: 1rem;">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> <?php echo $action === 'edit' ? 'Update' : 'Create'; ?> Job
                        </button>
                        <a href="jobs.php" class="btn" style="background: var(--gray);">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    <?php endif; ?>
</div>

<?php include 'includes/footer.php'; ?>
