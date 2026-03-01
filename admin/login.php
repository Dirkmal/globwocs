<?php
session_start();
require_once '../includes/db.php';

if (!empty($_SESSION['admin_logged_in'])) {
    header('Location: index.php'); exit;
}

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user = trim($_POST['username'] ?? '');
    $pass = $_POST['password'] ?? '';
    $debug_Steps = "";
    if ($user && $pass) {
        $db = getDB();
        $stmt = $db->prepare("SELECT password_hash FROM admin_users WHERE username = ? LIMIT 1");
        $stmt->execute([$user]);
        $row = $stmt->fetch();
        
        $debug_Steps = password_verify($pass, $row['password_hash']) ? "Password matches hash." : "Password does not match hash.";
        if ($row && password_verify($pass, $row['password_hash'])) {
          $debug_Steps = " Password verified. Logging in.";
            $_SESSION['admin_logged_in'] = true;
            $_SESSION['admin_user']      = $user;
            $db->prepare("UPDATE admin_users SET last_login=NOW() WHERE username=?")->execute([$user]);
            header('Location: index.php'); exit;
        }
    }
    $error = 'Invalid credentials. '. $debug_Steps .' Please try again.';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width,initial-scale=1.0">
  <title>Admin Login — Globwocs</title>
  <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@300;400;500&family=DM+Mono&family=Playfair+Display:ital,wght@1,400&display=swap" rel="stylesheet">
  <style>
    *{box-sizing:border-box;margin:0;padding:0}
    body{background:#0c0b09;color:#fff;font-family:'DM Sans',sans-serif;min-height:100vh;display:flex;align-items:center;justify-content:center;padding:20px}
    .login-box{width:100%;max-width:400px}
    .login-brand{text-align:center;margin-bottom:48px}
    .login-brand__name{font-family:'DM Sans',sans-serif;font-size:1.3rem;font-weight:500;letter-spacing:0.2em;text-transform:uppercase;margin-bottom:4px}
    .login-brand__sub{font-family:'DM Mono',monospace;font-size:0.6rem;letter-spacing:0.2em;text-transform:uppercase;color:#c4a882}
    .login-brand__divider{width:40px;height:1px;background:#c4a882;margin:16px auto 0}
    .form-group{margin-bottom:18px}
    .form-label{display:block;font-family:'DM Mono',monospace;font-size:0.6rem;letter-spacing:0.15em;text-transform:uppercase;color:rgba(255,255,255,0.4);margin-bottom:8px}
    .form-input{width:100%;background:#1c1a16;border:1px solid rgba(255,255,255,0.1);color:#fff;font-family:'DM Sans',sans-serif;font-size:0.9rem;padding:12px 16px;outline:none;transition:border-color 0.2s}
    .form-input:focus{border-color:#c4a882}
    .btn-login{width:100%;background:#c4a882;color:#0c0b09;font-family:'DM Mono',monospace;font-size:0.72rem;letter-spacing:0.15em;text-transform:uppercase;padding:14px;border:none;cursor:pointer;margin-top:8px;transition:background 0.2s}
    .btn-login:hover{background:#d4ba98}
    .error{background:rgba(224,90,90,0.1);border:1px solid rgba(224,90,90,0.3);color:#e05a5a;padding:10px 14px;font-size:0.82rem;margin-bottom:18px}
    a{color:rgba(255,255,255,0.35);font-size:0.75rem;text-decoration:none;font-family:'DM Mono',monospace;letter-spacing:0.1em}
    a:hover{color:#c4a882}
    .login-footer{text-align:center;margin-top:24px}
  </style>
</head>
<body>
<div class="login-box">
  <div class="login-brand">
    <p class="login-brand__name">Globwocs</p>
    <p class="login-brand__sub">Administration</p>
    <div class="login-brand__divider"></div>
  </div>
  <?php if ($error): ?>
  <div class="error"><?= htmlspecialchars($error) ?></div>
  <?php endif; ?>
  <form method="POST">
    <div class="form-group">
      <label class="form-label" for="username">Username</label>
      <input class="form-input" type="text" id="username" name="username" autocomplete="username" autofocus required>
    </div>
    <div class="form-group">
      <label class="form-label" for="password">Password</label>
      <input class="form-input" type="password" id="password" name="password" autocomplete="current-password" required>
    </div>
    <button type="submit" class="btn-login">Sign In →</button>
  </form>
  <div class="login-footer"><a href="../index.php">← Back to site</a></div>
</div>
</body>
</html>
