<?php
/**
 * AlfaisalX Admin - Settings Management
 */
require_once dirname(__DIR__) . '/database/Database.php';
$db = Database::getInstance();

$message = '';
$error = '';

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $postAction = $_POST['action'] ?? '';
    
    if ($postAction === 'update_settings') {
        $settings = $_POST['settings'] ?? [];
        
        foreach ($settings as $key => $value) {
            $db->execute(
                "UPDATE settings SET value = ? WHERE key = ?",
                [trim($value), $key]
            );
        }
        
        $message = 'Settings updated successfully';
    } elseif ($postAction === 'change_password') {
        require_once __DIR__ . '/includes/auth.php';
        
        $currentPassword = $_POST['current_password'] ?? '';
        $newPassword = $_POST['new_password'] ?? '';
        $confirmPassword = $_POST['confirm_password'] ?? '';
        
        if ($newPassword !== $confirmPassword) {
            $error = 'New passwords do not match';
        } else {
            $result = $auth->changePassword($currentPassword, $newPassword);
            if ($result['success']) {
                $message = $result['message'];
            } else {
                $error = $result['message'];
            }
        }
    } elseif ($postAction === 'add_setting') {
        $key = trim($_POST['new_key'] ?? '');
        $value = trim($_POST['new_value'] ?? '');
        $description = trim($_POST['new_description'] ?? '');
        
        if (empty($key)) {
            $error = 'Setting key is required';
        } else {
            $existing = $db->queryOne("SELECT id FROM settings WHERE key = ?", [$key]);
            if ($existing) {
                $error = 'Setting key already exists';
            } else {
                $db->execute(
                    "INSERT INTO settings (key, value, description) VALUES (?, ?, ?)",
                    [$key, $value, $description]
                );
                $message = 'Setting added successfully';
            }
        }
    } elseif ($postAction === 'delete_setting') {
        $key = $_POST['key'] ?? '';
        // Protect critical settings
        $protected = ['admin_username', 'admin_password', 'site_name'];
        if (in_array($key, $protected)) {
            $error = 'Cannot delete protected setting';
        } else {
            $db->execute("DELETE FROM settings WHERE key = ?", [$key]);
            $message = 'Setting deleted successfully';
        }
    }
}

// Get all settings grouped
$allSettings = $db->query("SELECT * FROM settings ORDER BY key");

// Group settings
$settingsGroups = [
    'Site Info' => ['site_name', 'site_full_name', 'site_tagline', 'acronym_explanation', 'established_year'],
    'Vision & Mission' => ['vision', 'vision_extended', 'mission', 'mission_extended'],
    'Contact' => ['contact_email', 'contact_phone', 'contact_address'],
    'Social Media' => ['social_linkedin', 'social_github', 'social_twitter', 'social_scholar'],
    'Introduction' => ['intro_text', 'intro_text_2'],
    'Budget' => ['budget_initial_setup', 'budget_annual_operational', 'lab_space_size'],
];

// Build grouped array
$groupedSettings = [];
$otherSettings = [];
foreach ($allSettings as $setting) {
    $found = false;
    foreach ($settingsGroups as $group => $keys) {
        if (in_array($setting['key'], $keys)) {
            $groupedSettings[$group][] = $setting;
            $found = true;
            break;
        }
    }
    if (!$found && !in_array($setting['key'], ['admin_username', 'admin_password'])) {
        $otherSettings[] = $setting;
    }
}

include 'includes/header.php';
?>

<div class="top-bar">
    <h1 class="page-title">Settings</h1>
</div>

<div class="content">
    <?php if ($message): ?>
        <div class="alert alert-success"><i class="fas fa-check-circle"></i> <?php echo htmlspecialchars($message); ?></div>
    <?php endif; ?>
    <?php if ($error): ?>
        <div class="alert alert-danger"><i class="fas fa-exclamation-circle"></i> <?php echo htmlspecialchars($error); ?></div>
    <?php endif; ?>
    
    <div class="row">
        <div class="col-6">
            <!-- Site Settings -->
            <form method="POST">
                <input type="hidden" name="action" value="update_settings">
                
                <?php foreach ($groupedSettings as $group => $settings): ?>
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title"><?php echo $group; ?></h3>
                    </div>
                    <div class="card-body">
                        <?php foreach ($settings as $setting): ?>
                        <div class="form-group">
                            <label class="form-label"><?php echo htmlspecialchars(ucwords(str_replace('_', ' ', $setting['key']))); ?></label>
                            <?php if (strlen($setting['value'] ?? '') > 100): ?>
                                <textarea name="settings[<?php echo htmlspecialchars($setting['key']); ?>]" class="form-control" rows="3"><?php echo htmlspecialchars($setting['value']); ?></textarea>
                            <?php else: ?>
                                <input type="text" name="settings[<?php echo htmlspecialchars($setting['key']); ?>]" class="form-control" 
                                       value="<?php echo htmlspecialchars($setting['value']); ?>">
                            <?php endif; ?>
                            <?php if ($setting['description']): ?>
                                <small style="color: var(--gray);"><?php echo htmlspecialchars($setting['description']); ?></small>
                            <?php endif; ?>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
                <?php endforeach; ?>
                
                <?php if (!empty($otherSettings)): ?>
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Other Settings</h3>
                    </div>
                    <div class="card-body">
                        <?php foreach ($otherSettings as $setting): ?>
                        <div class="form-group" style="display: flex; gap: 0.5rem; align-items: flex-start;">
                            <div style="flex: 1;">
                                <label class="form-label"><?php echo htmlspecialchars($setting['key']); ?></label>
                                <input type="text" name="settings[<?php echo htmlspecialchars($setting['key']); ?>]" class="form-control" 
                                       value="<?php echo htmlspecialchars($setting['value']); ?>">
                            </div>
                            <form method="POST" style="margin-top: 1.75rem;" onsubmit="return confirmDelete('Delete this setting?');">
                                <input type="hidden" name="action" value="delete_setting">
                                <input type="hidden" name="key" value="<?php echo htmlspecialchars($setting['key']); ?>">
                                <button type="submit" class="btn btn-sm btn-danger btn-icon"><i class="fas fa-trash"></i></button>
                            </form>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
                <?php endif; ?>
                
                <button type="submit" class="btn btn-primary" style="margin-bottom: 2rem;">
                    <i class="fas fa-save"></i> Save All Settings
                </button>
            </form>
        </div>
        
        <div class="col-6">
            <!-- Change Password -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title"><i class="fas fa-lock"></i> Change Password</h3>
                </div>
                <div class="card-body">
                    <form method="POST">
                        <input type="hidden" name="action" value="change_password">
                        
                        <div class="form-group">
                            <label class="form-label">Current Password</label>
                            <input type="password" name="current_password" class="form-control" required>
                        </div>
                        
                        <div class="form-group">
                            <label class="form-label">New Password</label>
                            <input type="password" name="new_password" class="form-control" required minlength="8">
                        </div>
                        
                        <div class="form-group">
                            <label class="form-label">Confirm New Password</label>
                            <input type="password" name="confirm_password" class="form-control" required>
                        </div>
                        
                        <button type="submit" class="btn btn-warning">
                            <i class="fas fa-key"></i> Change Password
                        </button>
                    </form>
                </div>
            </div>
            
            <!-- Add New Setting -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title"><i class="fas fa-plus"></i> Add New Setting</h3>
                </div>
                <div class="card-body">
                    <form method="POST">
                        <input type="hidden" name="action" value="add_setting">
                        
                        <div class="form-group">
                            <label class="form-label">Key</label>
                            <input type="text" name="new_key" class="form-control" required 
                                   placeholder="e.g., custom_setting_name">
                        </div>
                        
                        <div class="form-group">
                            <label class="form-label">Value</label>
                            <input type="text" name="new_value" class="form-control">
                        </div>
                        
                        <div class="form-group">
                            <label class="form-label">Description</label>
                            <input type="text" name="new_description" class="form-control">
                        </div>
                        
                        <button type="submit" class="btn btn-success">
                            <i class="fas fa-plus"></i> Add Setting
                        </button>
                    </form>
                </div>
            </div>
            
            <!-- Database Info -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title"><i class="fas fa-database"></i> Database Info</h3>
                </div>
                <div class="card-body">
                    <table style="width: 100%;">
                        <tr>
                            <td style="color: var(--gray);">Database Type</td>
                            <td>SQLite</td>
                        </tr>
                        <tr>
                            <td style="color: var(--gray);">Database File</td>
                            <td><code>database/alfaisalx.db</code></td>
                        </tr>
                        <tr>
                            <td style="color: var(--gray);">Total Settings</td>
                            <td><?php echo count($allSettings); ?></td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
