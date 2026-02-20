<?php
/**
 * AlfaisalX Admin - Publications CRUD
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
            'authors' => trim($_POST['authors'] ?? ''),
            'venue' => trim($_POST['venue'] ?? ''),
            'year' => intval($_POST['year'] ?? date('Y')),
            'type' => $_POST['type'] ?? 'journal',
            'doi' => trim($_POST['doi'] ?? ''),
            'url' => trim($_POST['url'] ?? ''),
            'abstract' => trim($_POST['abstract'] ?? '')
        ];
        
        if (empty($data['title'])) {
            $error = 'Title is required';
        } else {
            if ($postAction === 'create') {
                $db->execute(
                    "INSERT INTO publications (title, authors, venue, year, type, doi, url, abstract) 
                     VALUES (?, ?, ?, ?, ?, ?, ?, ?)",
                    array_values($data)
                );
                $message = 'Publication created successfully';
                $action = 'list';
            } else {
                $pubId = $_POST['id'];
                $db->execute(
                    "UPDATE publications SET title=?, authors=?, venue=?, year=?, type=?, doi=?, url=?, abstract=? WHERE id=?",
                    [...array_values($data), $pubId]
                );
                $message = 'Publication updated successfully';
                $action = 'list';
            }
        }
    } elseif ($postAction === 'delete') {
        $pubId = $_POST['id'];
        $db->execute("DELETE FROM publications WHERE id = ?", [$pubId]);
        $message = 'Publication deleted successfully';
    }
}

// Get data
$publications = $db->query("SELECT * FROM publications ORDER BY year DESC, title");
$editPub = null;
if ($action === 'edit' && $id) {
    $editPub = $db->queryOne("SELECT * FROM publications WHERE id = ?", [$id]);
    if (!$editPub) {
        $error = 'Publication not found';
        $action = 'list';
    }
}

include 'includes/header.php';
?>

<div class="top-bar">
    <h1 class="page-title">Publications</h1>
    <div>
        <?php if ($action === 'list'): ?>
            <a href="?action=add" class="btn btn-primary"><i class="fas fa-plus"></i> Add Publication</a>
            <a href="fetch_publications.php" class="btn btn-success"><i class="fas fa-download"></i> Fetch from Scholar</a>
        <?php else: ?>
            <a href="publications.php" class="btn btn-primary"><i class="fas fa-arrow-left"></i> Back to List</a>
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
                <h3 class="card-title">All Publications (<?php echo count($publications); ?>)</h3>
            </div>
            <div class="table-responsive">
                <table>
                    <thead>
                        <tr>
                            <th>Title</th>
                            <th>Authors</th>
                            <th>Year</th>
                            <th>Type</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($publications as $pub): ?>
                        <tr>
                            <td>
                                <strong><?php echo htmlspecialchars(substr($pub['title'], 0, 60)); ?><?php echo strlen($pub['title']) > 60 ? '...' : ''; ?></strong>
                                <?php if ($pub['venue']): ?>
                                    <br><small style="color: var(--gray);"><?php echo htmlspecialchars($pub['venue']); ?></small>
                                <?php endif; ?>
                            </td>
                            <td><?php echo htmlspecialchars(substr($pub['authors'] ?? '', 0, 40)); ?>...</td>
                            <td><?php echo $pub['year']; ?></td>
                            <td><span class="badge badge-success"><?php echo htmlspecialchars(ucfirst($pub['type'])); ?></span></td>
                            <td>
                                <div class="actions">
                                    <a href="?action=edit&id=<?php echo $pub['id']; ?>" class="btn btn-sm btn-warning btn-icon" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form method="POST" style="display:inline;" onsubmit="return confirmDelete('Delete this publication?');">
                                        <input type="hidden" name="action" value="delete">
                                        <input type="hidden" name="id" value="<?php echo $pub['id']; ?>">
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
                <h3 class="card-title"><?php echo $action === 'edit' ? 'Edit' : 'Add'; ?> Publication</h3>
            </div>
            <div class="card-body">
                <form method="POST">
                    <input type="hidden" name="action" value="<?php echo $action === 'edit' ? 'update' : 'create'; ?>">
                    <?php if ($editPub): ?>
                        <input type="hidden" name="id" value="<?php echo $editPub['id']; ?>">
                    <?php endif; ?>
                    
                    <div class="form-group">
                        <label class="form-label">Title *</label>
                        <input type="text" name="title" class="form-control" required 
                               value="<?php echo htmlspecialchars($editPub['title'] ?? ''); ?>">
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">Authors</label>
                        <input type="text" name="authors" class="form-control" 
                               value="<?php echo htmlspecialchars($editPub['authors'] ?? ''); ?>"
                               placeholder="e.g., A. Koubaa, M. Bahloul, Y. Bouteraa">
                    </div>
                    
                    <div class="row">
                        <div class="col-6">
                            <div class="form-group">
                                <label class="form-label">Venue (Journal/Conference)</label>
                                <input type="text" name="venue" class="form-control" 
                                       value="<?php echo htmlspecialchars($editPub['venue'] ?? ''); ?>">
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group">
                                <label class="form-label">Year</label>
                                <input type="number" name="year" class="form-control" 
                                       value="<?php echo $editPub['year'] ?? date('Y'); ?>" min="1990" max="2030">
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-6">
                            <div class="form-group">
                                <label class="form-label">Type</label>
                                <select name="type" class="form-control">
                                    <option value="journal" <?php echo ($editPub['type'] ?? '') === 'journal' ? 'selected' : ''; ?>>Journal Article</option>
                                    <option value="conference" <?php echo ($editPub['type'] ?? '') === 'conference' ? 'selected' : ''; ?>>Conference Paper</option>
                                    <option value="book" <?php echo ($editPub['type'] ?? '') === 'book' ? 'selected' : ''; ?>>Book/Chapter</option>
                                    <option value="patent" <?php echo ($editPub['type'] ?? '') === 'patent' ? 'selected' : ''; ?>>Patent</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group">
                                <label class="form-label">DOI</label>
                                <input type="text" name="doi" class="form-control" 
                                       value="<?php echo htmlspecialchars($editPub['doi'] ?? ''); ?>"
                                       placeholder="e.g., 10.1109/ACCESS.2024.1234567">
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">URL</label>
                        <input type="url" name="url" class="form-control" 
                               value="<?php echo htmlspecialchars($editPub['url'] ?? ''); ?>">
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">Abstract</label>
                        <textarea name="abstract" class="form-control" rows="4"><?php echo htmlspecialchars($editPub['abstract'] ?? ''); ?></textarea>
                    </div>
                    
                    <div style="margin-top: 1rem;">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> <?php echo $action === 'edit' ? 'Update' : 'Create'; ?> Publication
                        </button>
                        <a href="publications.php" class="btn" style="background: var(--gray);">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    <?php endif; ?>
</div>

<?php include 'includes/footer.php'; ?>
