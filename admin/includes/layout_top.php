<?php
// admin/includes/layout_top.php
$adminTitle = $adminTitle ?? 'Admin';
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width,initial-scale=1.0">
  <title><?= htmlspecialchars($adminTitle) ?> — Globwocs Admin</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@300;400;500&family=DM+Mono:wght@400&display=swap" rel="stylesheet">
  <style>
    :root{--ink:#0c0b09;--ink-mid:#1c1a16;--ink-soft:#2e2c28;--stone:#c4a882;--white:#fff;--danger:#e05a5a;--success:#5ac87a;--f-body:'DM Sans',sans-serif;--f-mono:'DM Mono',monospace}
    *{box-sizing:border-box;margin:0;padding:0}
    body{background:var(--ink);color:var(--white);font-family:var(--f-body);font-size:14px;min-height:100vh;display:grid;grid-template-columns:220px 1fr;grid-template-rows:auto 1fr}
    a{color:inherit;text-decoration:none}
    .admin-header{grid-column:1/-1;background:var(--ink-mid);border-bottom:1px solid rgba(196,168,130,0.12);padding:16px 24px;display:flex;align-items:center;justify-content:space-between}
    .admin-logo{font-family:var(--f-mono);font-size:0.75rem;letter-spacing:0.2em;text-transform:uppercase;color:var(--stone)}
    .admin-user{font-family:var(--f-mono);font-size:0.65rem;letter-spacing:0.1em;color:rgba(255,255,255,0.4);display:flex;align-items:center;gap:1rem}
    .admin-user a:hover{color:var(--stone)}
    .admin-sidebar{background:var(--ink-mid);border-right:1px solid rgba(255,255,255,0.06);padding:24px 0}
    .sidebar-section{font-family:var(--f-mono);font-size:0.55rem;letter-spacing:0.2em;text-transform:uppercase;color:rgba(255,255,255,0.25);padding:0 20px;margin:20px 0 8px}
    .sidebar-link{display:block;padding:10px 20px;font-size:0.82rem;color:rgba(255,255,255,0.5);transition:color 0.2s,background 0.2s;position:relative}
    .sidebar-link:hover,.sidebar-link.active{color:var(--white);background:rgba(255,255,255,0.04)}
    .sidebar-link.active::before{content:'';position:absolute;left:0;top:0;bottom:0;width:2px;background:var(--stone)}
    .admin-content{padding:32px;overflow-y:auto}
    .page-title{font-size:1.4rem;font-weight:400;margin-bottom:8px}
    .page-sub{font-size:0.82rem;color:rgba(255,255,255,0.4);margin-bottom:28px}
    .card{background:var(--ink-mid);border:1px solid rgba(255,255,255,0.06);padding:24px;margin-bottom:20px}
    .btn-sm{display:inline-flex;align-items:center;gap:6px;padding:8px 16px;font-family:var(--f-mono);font-size:0.65rem;letter-spacing:0.1em;text-transform:uppercase;cursor:pointer;border-radius:0;transition:background 0.2s,color 0.2s}
    .btn-sm.primary{background:var(--stone);color:var(--ink);border:none}
    .btn-sm.primary:hover{background:#d4ba98}
    .btn-sm.ghost{background:transparent;border:1px solid rgba(255,255,255,0.15);color:rgba(255,255,255,0.6)}
    .btn-sm.ghost:hover{border-color:rgba(255,255,255,0.4);color:var(--white)}
    .btn-sm.danger{background:transparent;border:1px solid rgba(224,90,90,0.3);color:var(--danger)}
    .btn-sm.danger:hover{background:rgba(224,90,90,0.1)}
    table{width:100%;border-collapse:collapse}
    th{text-align:left;font-family:var(--f-mono);font-size:0.6rem;letter-spacing:0.12em;text-transform:uppercase;color:rgba(255,255,255,0.35);padding:10px 12px;border-bottom:1px solid rgba(255,255,255,0.08)}
    td{padding:14px 12px;border-bottom:1px solid rgba(255,255,255,0.05);font-size:0.85rem;color:rgba(255,255,255,0.7);vertical-align:middle}
    tr:hover td{background:rgba(255,255,255,0.02)}
    .badge{display:inline-block;padding:3px 10px;font-family:var(--f-mono);font-size:0.58rem;letter-spacing:0.1em;text-transform:uppercase}
    .badge.yes{background:rgba(90,200,122,0.12);color:var(--success);border:1px solid rgba(90,200,122,0.25)}
    .badge.no{background:rgba(255,255,255,0.05);color:rgba(255,255,255,0.3);border:1px solid rgba(255,255,255,0.08)}
    .form-row{display:grid;grid-template-columns:1fr 1fr;gap:16px}
    .form-group{margin-bottom:18px}
    .form-label{display:block;font-family:var(--f-mono);font-size:0.6rem;letter-spacing:0.15em;text-transform:uppercase;color:rgba(255,255,255,0.4);margin-bottom:8px}
    .form-input,.form-textarea,.form-select{width:100%;background:var(--ink-soft);border:1px solid rgba(255,255,255,0.1);color:var(--white);font-family:var(--f-body);font-size:0.875rem;padding:10px 14px;outline:none;transition:border-color 0.2s}
    .form-input:focus,.form-textarea:focus,.form-select:focus{border-color:var(--stone)}
    .form-textarea{min-height:120px;resize:vertical}
    .form-select option{background:var(--ink-soft)}
    .form-check{display:flex;align-items:center;gap:10px;cursor:pointer;font-size:0.85rem;color:rgba(255,255,255,0.6)}
    .form-check input{accent-color:var(--stone);width:14px;height:14px}
    .alert{padding:12px 16px;margin-bottom:16px;font-size:0.82rem;border-left:3px solid}
    .alert.success{background:rgba(90,200,122,0.08);border-color:var(--success);color:var(--success)}
    .alert.error{background:rgba(224,90,90,0.08);border-color:var(--danger);color:var(--danger)}
    .thumb{width:48px;height:48px;object-fit:cover;display:inline-block}
    .img-grid{display:flex;gap:8px;flex-wrap:wrap;margin-top:8px}
    .img-grid img{width:80px;height:80px;object-fit:cover}
    .drop-zone{border:1px dashed rgba(196,168,130,0.3);padding:32px;text-align:center;cursor:pointer;transition:border-color 0.2s}
    .drop-zone:hover,.drop-zone.dragover{border-color:var(--stone)}
    .drop-zone p{font-size:0.82rem;color:rgba(255,255,255,0.4);margin-top:8px}
    #preview-grid{display:flex;flex-wrap:wrap;gap:8px;margin-top:12px}
    .preview-thumb{position:relative}
    .preview-thumb img{width:90px;height:90px;object-fit:cover}
    .preview-thumb .rm{position:absolute;top:2px;right:2px;background:var(--danger);color:#fff;border:none;width:18px;height:18px;font-size:10px;cursor:pointer;display:flex;align-items:center;justify-content:center}
    @media(max-width:768px){body{grid-template-columns:1fr}  .admin-sidebar{display:none}}
  </style>
</head>
<body>
<header class="admin-header">
  <span class="admin-logo">Globwocs Admin</span>
  <div class="admin-user">
    <span>Administrator</span>
    <a href="<?= str_contains($_SERVER['PHP_SELF'], '/admin/') ? '' : 'admin/' ?>logout.php">Sign Out</a>
  </div>
</header>
<aside class="admin-sidebar">
  <p class="sidebar-section">Content</p>
  <?php
  $base = basename($_SERVER['PHP_SELF']);
  $links = [
    ['index.php',        'Dashboard'],
    ['projects.php',     'Projects'],
    ['project-add.php',  '+ Add Project'],
  ];
  foreach ($links as [$file, $label]) {
    $active = $base === $file ? 'active' : '';
    echo "<a href='{$file}' class='sidebar-link {$active}'>{$label}</a>";
  }
  ?>
  <p class="sidebar-section">Account</p>
  <a href="password.php" class="sidebar-link <?= $base==='password.php'?'active':'' ?>">Change Password</a>
  <a href="logout.php" class="sidebar-link">Sign Out</a>
  <p class="sidebar-section">Site</p>
  <a href="../index.php" class="sidebar-link" target="_blank">View Site ↗</a>
</aside>
<main class="admin-content">
