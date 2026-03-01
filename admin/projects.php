<?php
$adminTitle = 'Projects';
require_once 'includes/auth.php';
requireLogin();
require_once '../includes/db.php';
$db = getDB();

// Handle actions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    $id     = (int)($_POST['id'] ?? 0);
    if ($action === 'toggle_featured' && $id) {
        $db->prepare("UPDATE projects SET is_featured = NOT is_featured WHERE id=?")->execute([$id]);
    } elseif ($action === 'delete' && $id) {
        // Delete images from disk
        $imgs = $db->prepare("SELECT filename FROM project_images WHERE project_id=?");
        $imgs->execute([$id]);
        foreach ($imgs->fetchAll() as $img) {
            $path = '../uploads/projects/' . $img['filename'];
            if (file_exists($path)) unlink($path);
        }
        $db->prepare("DELETE FROM project_images WHERE project_id=?")->execute([$id]);
        $db->prepare("DELETE FROM projects WHERE id=?")->execute([$id]);
    }
    header('Location: projects.php'); exit;
}

$projects = $db->query("
  SELECT p.*, COUNT(pi.id) AS img_count,
    (SELECT filename FROM project_images WHERE project_id=p.id AND is_cover=1 LIMIT 1) AS cover
  FROM projects p
  LEFT JOIN project_images pi ON pi.project_id=p.id
  GROUP BY p.id
  ORDER BY p.sort_order ASC, p.created_at DESC
")->fetchAll();

include 'includes/layout_top.php';
?>
<div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:8px">
  <h1 class="page-title" style="margin-bottom:0">Projects</h1>
  <a href="project-add.php" class="btn-sm primary">+ Add Project</a>
</div>
<p class="page-sub"><?= count($projects) ?> project<?= count($projects)!==1?'s':'' ?> total</p>

<div class="card" style="padding:0">
  <table>
    <thead><tr><th style="width:60px"></th><th>Name</th><th>Category</th><th>Location</th><th>Year</th><th>Images</th><th>Featured</th><th>Actions</th></tr></thead>
    <tbody>
    <?php foreach ($projects as $p): ?>
    <tr>
      <td>
        <?php if ($p['cover']): ?>
        <img src="../uploads/projects/<?= htmlspecialchars($p['cover']) ?>" class="thumb" alt="">
        <?php else: ?>
        <div style="width:48px;height:48px;background:var(--ink-soft)"></div>
        <?php endif; ?>
      </td>
      <td><strong><?= htmlspecialchars($p['name']) ?></strong></td>
      <td><?= htmlspecialchars($p['category'] ?? '—') ?></td>
      <td><?= htmlspecialchars($p['location'] ?? '—') ?></td>
      <td><?= htmlspecialchars($p['year'] ?? '—') ?></td>
      <td><?= $p['img_count'] ?></td>
      <td>
        <form method="POST" style="display:inline">
          <input type="hidden" name="action" value="toggle_featured">
          <input type="hidden" name="id" value="<?= $p['id'] ?>">
          <button type="submit" class="badge <?= $p['is_featured']?'yes':'no' ?>" style="cursor:pointer;border:none">
            <?= $p['is_featured'] ? 'Yes' : 'No' ?>
          </button>
        </form>
      </td>
      <td style="white-space:nowrap">
        <a href="project-edit.php?id=<?= $p['id'] ?>" class="btn-sm ghost" style="margin-right:6px">Edit</a>
        <form method="POST" style="display:inline" onsubmit="return confirm('Delete &quot;<?= htmlspecialchars(addslashes($p['name'])) ?>&quot; and all its images?')">
          <input type="hidden" name="action" value="delete">
          <input type="hidden" name="id" value="<?= $p['id'] ?>">
          <button type="submit" class="btn-sm danger">Delete</button>
        </form>
      </td>
    </tr>
    <?php endforeach; ?>
    <?php if (!$projects): ?>
    <tr><td colspan="8" style="text-align:center;padding:32px;color:rgba(255,255,255,0.35)">No projects yet. <a href="project-add.php" style="color:var(--stone)">Add one →</a></td></tr>
    <?php endif; ?>
    </tbody>
  </table>
</div>
<?php include 'includes/layout_bottom.php'; ?>
