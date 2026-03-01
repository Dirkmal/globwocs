<?php
$adminTitle = 'Dashboard';
require_once 'includes/auth.php';
requireLogin();
require_once '../includes/db.php';
$db = getDB();

$projectCount  = $db->query("SELECT COUNT(*) FROM projects")->fetchColumn();
$featuredCount = $db->query("SELECT COUNT(*) FROM projects WHERE is_featured=1")->fetchColumn();
$imageCount    = $db->query("SELECT COUNT(*) FROM project_images")->fetchColumn();
$recentProjects = $db->query("SELECT name, category, year, created_at FROM projects ORDER BY created_at DESC LIMIT 5")->fetchAll();

include 'includes/layout_top.php';
?>
<h1 class="page-title">Dashboard</h1>
<p class="page-sub">Welcome back. Here's an overview of the site content.</p>

<div style="display:grid;grid-template-columns:repeat(3,1fr);gap:16px;margin-bottom:28px">
  <div class="card" style="text-align:center">
    <p style="font-family:var(--f-mono);font-size:2rem;color:var(--stone)"><?= $projectCount ?></p>
    <p style="font-size:0.75rem;color:rgba(255,255,255,0.4);margin-top:4px;font-family:var(--f-mono);letter-spacing:0.1em;text-transform:uppercase">Total Projects</p>
  </div>
  <div class="card" style="text-align:center">
    <p style="font-family:var(--f-mono);font-size:2rem;color:var(--stone)"><?= $featuredCount ?></p>
    <p style="font-size:0.75rem;color:rgba(255,255,255,0.4);margin-top:4px;font-family:var(--f-mono);letter-spacing:0.1em;text-transform:uppercase">Featured</p>
  </div>
  <div class="card" style="text-align:center">
    <p style="font-family:var(--f-mono);font-size:2rem;color:var(--stone)"><?= $imageCount ?></p>
    <p style="font-size:0.75rem;color:rgba(255,255,255,0.4);margin-top:4px;font-family:var(--f-mono);letter-spacing:0.1em;text-transform:uppercase">Total Images</p>
  </div>
</div>

<div class="card">
  <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:16px">
    <h2 style="font-size:0.875rem;font-weight:500">Recent Projects</h2>
    <a href="project-add.php" class="btn-sm primary">+ Add Project</a>
  </div>
  <?php if ($recentProjects): ?>
  <table>
    <thead><tr><th>Name</th><th>Category</th><th>Year</th><th>Added</th><th></th></tr></thead>
    <tbody>
    <?php foreach ($recentProjects as $p): ?>
    <tr>
      <td><?= htmlspecialchars($p['name']) ?></td>
      <td><?= htmlspecialchars($p['category'] ?? '—') ?></td>
      <td><?= htmlspecialchars($p['year'] ?? '—') ?></td>
      <td style="color:rgba(255,255,255,0.35);font-size:0.75rem"><?= date('d M Y', strtotime($p['created_at'])) ?></td>
      <td><a href="projects.php" class="btn-sm ghost">Manage</a></td>
    </tr>
    <?php endforeach; ?>
    </tbody>
  </table>
  <?php else: ?>
  <p style="color:rgba(255,255,255,0.35);font-size:0.82rem">No projects yet. <a href="project-add.php" style="color:var(--stone)">Add your first project →</a></p>
  <?php endif; ?>
</div>
<?php include 'includes/layout_bottom.php'; ?>
