<?php
$adminTitle = 'Change Password';
require_once 'includes/auth.php';
requireLogin();
require_once '../includes/db.php';
$db = getDB();

$success = false;
$error   = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $current = $_POST['current_password'] ?? '';
    $new     = $_POST['new_password']     ?? '';
    $confirm = $_POST['confirm_password'] ?? '';

    if (strlen($new) < 8) {
        $error = 'New password must be at least 8 characters.';
    } elseif ($new !== $confirm) {
        $error = 'New passwords do not match.';
    } else {
        $row = $db->prepare("SELECT password_hash FROM admin_users WHERE username=?");
        $row->execute([$_SESSION['admin_user']]);
        $row = $row->fetch();
        if (!$row || !password_verify($current, $row['password_hash'])) {
            $error = 'Current password is incorrect.';
        } else {
            $hash = password_hash($new, PASSWORD_BCRYPT);
            $db->prepare("UPDATE admin_users SET password_hash=? WHERE username=?")
               ->execute([$hash, $_SESSION['admin_user']]);
            $success = true;
        }
    }
}

include 'includes/layout_top.php';
?>
<h1 class="page-title">Change Password</h1>
<p class="page-sub">Update your admin login password.</p>

<?php if ($success): ?><div class="alert success">Password updated successfully.</div><?php endif; ?>
<?php if ($error):   ?><div class="alert error"><?= htmlspecialchars($error) ?></div><?php endif; ?>

<div class="card" style="max-width:420px">
  <form method="POST">
    <div class="form-group">
      <label class="form-label">Current Password</label>
      <input class="form-input" type="password" name="current_password" required>
    </div>
    <div class="form-group">
      <label class="form-label">New Password</label>
      <input class="form-input" type="password" name="new_password" minlength="8" required>
    </div>
    <div class="form-group">
      <label class="form-label">Confirm New Password</label>
      <input class="form-input" type="password" name="confirm_password" required>
    </div>
    <button type="submit" class="btn-sm primary">Update Password</button>
  </form>
</div>
<?php include 'includes/layout_bottom.php'; ?>
