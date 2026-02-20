<?php
/**
 * AlfaisalX Admin - Team Members CRUD
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
            'title' => trim($_POST['title'] ?? ''),
            'role' => trim($_POST['role'] ?? ''),
            'category' => $_POST['category'] ?? 'core_faculty',
            'initials' => trim($_POST['initials'] ?? ''),
            'image' => trim($_POST['image'] ?? ''),
            'bio' => trim($_POST['bio'] ?? ''),
            'email' => trim($_POST['email'] ?? ''),
            'expertise' => trim($_POST['expertise'] ?? ''),
            'google_scholar' => trim($_POST['google_scholar'] ?? ''),
            'linkedin' => trim($_POST['linkedin'] ?? ''),
            'is_active' => isset($_POST['is_active']) ? 1 : 0,
            'sort_order' => intval($_POST['sort_order'] ?? 0)
        ];
        
        // Convert expertise to JSON array
        $expertiseArray = array_filter(array_map('trim', explode(',', $data['expertise'])));
        $data['expertise'] = json_encode($expertiseArray);
        
        // Generate initials if not provided
        if (empty($data['initials'])) {
            $words = explode(' ', $data['name']);
            $data['initials'] = '';
            foreach ($words as $word) {
                if (!empty($word)) $data['initials'] .= strtoupper($word[0]);
            }
            $data['initials'] = substr($data['initials'], 0, 2);
        }
        
        if (empty($data['name'])) {
            $error = 'Name is required';
        } else {
            if ($postAction === 'create') {
                $db->execute(
                    "INSERT INTO team_members (name, title, role, category, initials, image, bio, email, expertise, google_scholar, linkedin, is_active, sort_order) 
                     VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)",
                    array_values($data)
                );
                $message = 'Team member created successfully';
                $action = 'list';
            } else {
                $memberId = $_POST['id'];
                $db->execute(
                    "UPDATE team_members SET name=?, title=?, role=?, category=?, initials=?, image=?, bio=?, email=?, expertise=?, google_scholar=?, linkedin=?, is_active=?, sort_order=? WHERE id=?",
                    [...array_values($data), $memberId]
                );
                $message = 'Team member updated successfully';
                $action = 'list';
            }
        }
    } elseif ($postAction === 'delete') {
        $memberId = $_POST['id'];
        $db->execute("DELETE FROM team_members WHERE id = ?", [$memberId]);
        $message = 'Team member deleted successfully';
    }
}

// Get data for list/edit
$members = $db->query("SELECT * FROM team_members ORDER BY category, sort_order, name");
$editMember = null;
if ($action === 'edit' && $id) {
    $editMember = $db->queryOne("SELECT * FROM team_members WHERE id = ?", [$id]);
    if (!$editMember) {
        $error = 'Team member not found';
        $action = 'list';
    }
}

include 'includes/header.php';
?>

<div class="top-bar">
    <h1 class="page-title">Team Members</h1>
    <div>
        <?php if ($action === 'list'): ?>
            <a href="?action=add" class="btn btn-primary"><i class="fas fa-plus"></i> Add Member</a>
        <?php else: ?>
            <a href="team.php" class="btn btn-primary"><i class="fas fa-arrow-left"></i> Back to List</a>
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
        <!-- List View -->
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">All Team Members (<?php echo count($members); ?>)</h3>
            </div>
            <div class="table-responsive">
                <table>
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Title</th>
                            <th>Role</th>
                            <th>Category</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($members as $member): ?>
                        <tr>
                            <td>
                                <strong><?php echo htmlspecialchars($member['name']); ?></strong>
                                <?php if ($member['email']): ?>
                                    <br><small style="color: var(--gray);"><?php echo htmlspecialchars($member['email']); ?></small>
                                <?php endif; ?>
                            </td>
                            <td><?php echo htmlspecialchars($member['title']); ?></td>
                            <td><?php echo htmlspecialchars($member['role']); ?></td>
                            <td><span class="badge badge-success"><?php echo htmlspecialchars($member['category']); ?></span></td>
                            <td>
                                <?php if ($member['is_active']): ?>
                                    <span class="badge badge-success">Active</span>
                                <?php else: ?>
                                    <span class="badge badge-danger">Inactive</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <div class="actions">
                                    <a href="?action=edit&id=<?php echo $member['id']; ?>" class="btn btn-sm btn-warning btn-icon" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form method="POST" style="display:inline;" onsubmit="return confirmDelete('Delete this team member?');">
                                        <input type="hidden" name="action" value="delete">
                                        <input type="hidden" name="id" value="<?php echo $member['id']; ?>">
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
        <!-- Add/Edit Form -->
        <div class="card">
            <div class="card-header">
                <h3 class="card-title"><?php echo $action === 'edit' ? 'Edit' : 'Add'; ?> Team Member</h3>
            </div>
            <div class="card-body">
                <form method="POST">
                    <input type="hidden" name="action" value="<?php echo $action === 'edit' ? 'update' : 'create'; ?>">
                    <?php if ($editMember): ?>
                        <input type="hidden" name="id" value="<?php echo $editMember['id']; ?>">
                    <?php endif; ?>
                    
                    <div class="row">
                        <div class="col-6">
                            <div class="form-group">
                                <label class="form-label">Name *</label>
                                <input type="text" name="name" class="form-control" required 
                                       value="<?php echo htmlspecialchars($editMember['name'] ?? ''); ?>">
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group">
                                <label class="form-label">Title</label>
                                <input type="text" name="title" class="form-control" 
                                       value="<?php echo htmlspecialchars($editMember['title'] ?? ''); ?>"
                                       placeholder="e.g., Professor, Assistant Professor">
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-6">
                            <div class="form-group">
                                <label class="form-label">Role</label>
                                <input type="text" name="role" class="form-control" 
                                       value="<?php echo htmlspecialchars($editMember['role'] ?? ''); ?>"
                                       placeholder="e.g., Director, Core Faculty - UAVs">
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group">
                                <label class="form-label">Category</label>
                                <select name="category" class="form-control">
                                    <option value="leadership" <?php echo ($editMember['category'] ?? '') === 'leadership' ? 'selected' : ''; ?>>Leadership</option>
                                    <option value="core_faculty" <?php echo ($editMember['category'] ?? 'core_faculty') === 'core_faculty' ? 'selected' : ''; ?>>Core Faculty</option>
                                    <option value="adjunct" <?php echo ($editMember['category'] ?? '') === 'adjunct' ? 'selected' : ''; ?>>Adjunct</option>
                                    <option value="researchers" <?php echo ($editMember['category'] ?? '') === 'researchers' ? 'selected' : ''; ?>>Researchers</option>
                                    <option value="staff" <?php echo ($editMember['category'] ?? '') === 'staff' ? 'selected' : ''; ?>>Staff</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-6">
                            <div class="form-group">
                                <label class="form-label">Email</label>
                                <input type="email" name="email" class="form-control" 
                                       value="<?php echo htmlspecialchars($editMember['email'] ?? ''); ?>">
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group">
                                <label class="form-label">Initials (auto-generated if empty)</label>
                                <input type="text" name="initials" class="form-control" maxlength="3"
                                       value="<?php echo htmlspecialchars($editMember['initials'] ?? ''); ?>">
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">Image Filename</label>
                        <input type="text" name="image" class="form-control" 
                               value="<?php echo htmlspecialchars($editMember['image'] ?? ''); ?>"
                               placeholder="e.g., john_doe.jpg (place in assets/images/team/)">
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">Bio</label>
                        <textarea name="bio" class="form-control" rows="4"><?php echo htmlspecialchars($editMember['bio'] ?? ''); ?></textarea>
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">Expertise (comma-separated)</label>
                        <input type="text" name="expertise" class="form-control" 
                               value="<?php echo htmlspecialchars(implode(', ', json_decode($editMember['expertise'] ?? '[]', true) ?: [])); ?>"
                               placeholder="e.g., Robotics, AI, UAVs, Smart Cities">
                    </div>
                    
                    <div class="row">
                        <div class="col-6">
                            <div class="form-group">
                                <label class="form-label">Google Scholar URL</label>
                                <input type="url" name="google_scholar" class="form-control" 
                                       value="<?php echo htmlspecialchars($editMember['google_scholar'] ?? ''); ?>">
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group">
                                <label class="form-label">LinkedIn URL</label>
                                <input type="url" name="linkedin" class="form-control" 
                                       value="<?php echo htmlspecialchars($editMember['linkedin'] ?? ''); ?>">
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-6">
                            <div class="form-group">
                                <label class="form-label">Sort Order</label>
                                <input type="number" name="sort_order" class="form-control" 
                                       value="<?php echo $editMember['sort_order'] ?? 0; ?>">
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group">
                                <label class="form-label">&nbsp;</label>
                                <div style="padding-top: 0.5rem;">
                                    <label>
                                        <input type="checkbox" name="is_active" value="1" 
                                               <?php echo ($editMember['is_active'] ?? 1) ? 'checked' : ''; ?>>
                                        Active (visible on website)
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div style="margin-top: 1rem;">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> <?php echo $action === 'edit' ? 'Update' : 'Create'; ?> Member
                        </button>
                        <a href="team.php" class="btn" style="background: var(--gray);">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    <?php endif; ?>
</div>

<?php include 'includes/footer.php'; ?>
