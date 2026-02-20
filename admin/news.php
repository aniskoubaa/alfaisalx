<?php
/**
 * AlfaisalX Admin - News CRUD
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
            'type' => $_POST['type'] ?? 'announcement',
            'date' => $_POST['date'] ?? date('Y-m-d'),
            'excerpt' => trim($_POST['excerpt'] ?? ''),
            'content' => trim($_POST['content'] ?? ''),
            'icon' => trim($_POST['icon'] ?? 'newspaper'),
            'image' => trim($_POST['image'] ?? ''),
            'is_published' => isset($_POST['is_published']) ? 1 : 0
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
                    "INSERT INTO news (title, slug, type, date, excerpt, content, icon, image, is_published) 
                     VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)",
                    array_values($data)
                );
                $message = 'News item created successfully';
                $action = 'list';
            } else {
                $newsId = $_POST['id'];
                $db->execute(
                    "UPDATE news SET title=?, slug=?, type=?, date=?, excerpt=?, content=?, icon=?, image=?, is_published=? WHERE id=?",
                    [...array_values($data), $newsId]
                );
                $message = 'News item updated successfully';
                $action = 'list';
            }
        }
    } elseif ($postAction === 'delete') {
        $newsId = $_POST['id'];
        $db->execute("DELETE FROM news WHERE id = ?", [$newsId]);
        $message = 'News item deleted successfully';
    }
}

// Get data
$news = $db->query("SELECT * FROM news ORDER BY date DESC, created_at DESC");
$editNews = null;
if ($action === 'edit' && $id) {
    $editNews = $db->queryOne("SELECT * FROM news WHERE id = ?", [$id]);
    if (!$editNews) {
        $error = 'News item not found';
        $action = 'list';
    }
}

include 'includes/header.php';
?>

<div class="top-bar">
    <h1 class="page-title">News & Events</h1>
    <div>
        <?php if ($action === 'list'): ?>
            <a href="?action=add" class="btn btn-primary"><i class="fas fa-plus"></i> Add News</a>
        <?php else: ?>
            <a href="news.php" class="btn btn-primary"><i class="fas fa-arrow-left"></i> Back to List</a>
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
                <h3 class="card-title">All News (<?php echo count($news); ?>)</h3>
            </div>
            <div class="table-responsive">
                <table>
                    <thead>
                        <tr>
                            <th>Title</th>
                            <th>Type</th>
                            <th>Date</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($news as $item): ?>
                        <tr>
                            <td>
                                <strong><?php echo htmlspecialchars($item['title']); ?></strong>
                                <br><small style="color: var(--gray);"><?php echo htmlspecialchars(substr($item['excerpt'] ?? '', 0, 60)); ?>...</small>
                            </td>
                            <td>
                                <span class="badge <?php echo $item['type'] === 'event' ? 'badge-warning' : 'badge-success'; ?>">
                                    <?php echo htmlspecialchars(ucfirst($item['type'])); ?>
                                </span>
                            </td>
                            <td><?php echo date('M d, Y', strtotime($item['date'])); ?></td>
                            <td>
                                <?php if ($item['is_published']): ?>
                                    <span class="badge badge-success">Published</span>
                                <?php else: ?>
                                    <span class="badge badge-warning">Draft</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <div class="actions">
                                    <a href="?action=edit&id=<?php echo $item['id']; ?>" class="btn btn-sm btn-warning btn-icon" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form method="POST" style="display:inline;" onsubmit="return confirmDelete('Delete this news item?');">
                                        <input type="hidden" name="action" value="delete">
                                        <input type="hidden" name="id" value="<?php echo $item['id']; ?>">
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
                <h3 class="card-title"><?php echo $action === 'edit' ? 'Edit' : 'Add'; ?> News</h3>
            </div>
            <div class="card-body">
                <form method="POST">
                    <input type="hidden" name="action" value="<?php echo $action === 'edit' ? 'update' : 'create'; ?>">
                    <?php if ($editNews): ?>
                        <input type="hidden" name="id" value="<?php echo $editNews['id']; ?>">
                    <?php endif; ?>
                    
                    <div class="form-group">
                        <label class="form-label">Title *</label>
                        <input type="text" name="title" class="form-control" required 
                               value="<?php echo htmlspecialchars($editNews['title'] ?? ''); ?>">
                    </div>
                    
                    <div class="row">
                        <div class="col-6">
                            <div class="form-group">
                                <label class="form-label">Slug (auto-generated if empty)</label>
                                <input type="text" name="slug" class="form-control" 
                                       value="<?php echo htmlspecialchars($editNews['slug'] ?? ''); ?>">
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group">
                                <label class="form-label">Type</label>
                                <select name="type" class="form-control">
                                    <option value="announcement" <?php echo ($editNews['type'] ?? '') === 'announcement' ? 'selected' : ''; ?>>Announcement</option>
                                    <option value="event" <?php echo ($editNews['type'] ?? '') === 'event' ? 'selected' : ''; ?>>Event</option>
                                    <option value="research" <?php echo ($editNews['type'] ?? '') === 'research' ? 'selected' : ''; ?>>Research</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-6">
                            <div class="form-group">
                                <label class="form-label">Date</label>
                                <input type="date" name="date" class="form-control" 
                                       value="<?php echo $editNews['date'] ?? date('Y-m-d'); ?>">
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group">
                                <label class="form-label">Icon (FontAwesome name)</label>
                                <input type="text" name="icon" class="form-control" 
                                       value="<?php echo htmlspecialchars($editNews['icon'] ?? 'newspaper'); ?>"
                                       placeholder="e.g., newspaper, calendar, flask">
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">Excerpt (short summary)</label>
                        <textarea name="excerpt" class="form-control" rows="2"><?php echo htmlspecialchars($editNews['excerpt'] ?? ''); ?></textarea>
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">Content (full article)</label>
                        <textarea name="content" class="form-control" rows="8"><?php echo htmlspecialchars($editNews['content'] ?? ''); ?></textarea>
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">Image Filename</label>
                        <input type="text" name="image" class="form-control" 
                               value="<?php echo htmlspecialchars($editNews['image'] ?? ''); ?>"
                               placeholder="e.g., news-image.jpg">
                    </div>
                    
                    <div class="form-group">
                        <label>
                            <input type="checkbox" name="is_published" value="1" 
                                   <?php echo ($editNews['is_published'] ?? 1) ? 'checked' : ''; ?>>
                            Published (visible on website)
                        </label>
                    </div>
                    
                    <div style="margin-top: 1rem;">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> <?php echo $action === 'edit' ? 'Update' : 'Create'; ?> News
                        </button>
                        <a href="news.php" class="btn" style="background: var(--gray);">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    <?php endif; ?>
</div>

<?php include 'includes/footer.php'; ?>
