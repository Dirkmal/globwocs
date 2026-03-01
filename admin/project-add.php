<?php
$adminTitle = 'Add Project';
require_once 'includes/auth.php';
requireLogin();
require_once '../includes/db.php';
$db = getDB();

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name     = trim($_POST['name']        ?? '');
    $desc     = trim($_POST['description'] ?? '');
    $location = trim($_POST['location']    ?? '');
    $year     = trim($_POST['year']        ?? '');
    $category = trim($_POST['category']    ?? '');
    $area     = trim($_POST['area_sqm']    ?? '');
    $featured = isset($_POST['is_featured']) ? 1 : 0;
    $sort     = (int)($_POST['sort_order'] ?? 0);

    if (!$name) $errors[] = 'Project name is required.';

    if (!$errors) {
        $stmt = $db->prepare("
            INSERT INTO projects
              (name,description,location,year,category,area_sqm,is_featured,sort_order,created_at)
            VALUES (?,?,?,?,?,?,?,?,NOW())
        ");
        $stmt->execute([$name, $desc, $location, $year, $category, $area ?: null, $featured, $sort]);
        $projectId = (int)$db->lastInsertId();
        // Redirect to edit page where images are uploaded one-by-one
        header("Location: project-edit.php?id={$projectId}&new=1");
        exit;
    }
}

include 'includes/layout_top.php';
?>

<div style="display:flex;align-items:center;gap:16px;margin-bottom:8px">
  <a href="projects.php" style="color:rgba(255,255,255,0.35);font-size:0.75rem;font-family:var(--f-mono);letter-spacing:0.1em">← Projects</a>
</div>
<h1 class="page-title">Add New Project</h1>
<p class="page-sub">Step 1 of 2 — fill in the details, then add images on the next screen.</p>

<?php foreach ($errors as $e): ?>
<div class="alert error"><?= htmlspecialchars($e) ?></div>
<?php endforeach; ?>

<form method="POST">
  <div class="card">
    <h2 style="font-size:0.8rem;font-family:var(--f-mono);letter-spacing:0.1em;text-transform:uppercase;color:rgba(255,255,255,0.4);margin-bottom:18px">Project Details</h2>
    <div class="form-group">
      <label class="form-label" for="name">Project Name *</label>
      <input class="form-input" type="text" id="name" name="name" value="<?= htmlspecialchars($_POST['name'] ?? '') ?>" required autofocus>
    </div>
    <div class="form-group">
      <label class="form-label" for="description">Description</label>
      <textarea class="form-textarea" id="description" name="description"><?= htmlspecialchars($_POST['description'] ?? '') ?></textarea>
    </div>
    <div class="form-row">
      <div class="form-group">
        <label class="form-label" for="location">Location</label>
        <input class="form-input" type="text" id="location" name="location" value="<?= htmlspecialchars($_POST['location'] ?? '') ?>" placeholder="e.g. Lagos State">
      </div>
      <div class="form-group">
        <label class="form-label" for="year">Year</label>
        <input class="form-input" type="text" id="year" name="year" value="<?= htmlspecialchars($_POST['year'] ?? '') ?>" placeholder="e.g. 2023">
      </div>
    </div>
    <div class="form-row">
      <div class="form-group">
        <label class="form-label" for="category">Category</label>
        <select class="form-select" id="category" name="category">
          <option value="">Select…</option>
          <?php foreach (['Residential','Commercial','Institutional','Industrial','Mixed Use','Interior','Infrastructure'] as $cat): ?>
          <option <?= (($_POST['category'] ?? '') === $cat) ? 'selected' : '' ?>><?= $cat ?></option>
          <?php endforeach; ?>
        </select>
      </div>
      <div class="form-group">
        <label class="form-label" for="area_sqm">Area (sqm)</label>
        <input class="form-input" type="text" id="area_sqm" name="area_sqm" value="<?= htmlspecialchars($_POST['area_sqm'] ?? '') ?>" placeholder="e.g. 215">
      </div>
    </div>
    <div class="form-row">
      <div class="form-group">
        <label class="form-label" for="sort_order">Sort Order</label>
        <input class="form-input" type="number" id="sort_order" name="sort_order" value="<?= (int)($_POST['sort_order'] ?? 0) ?>">
      </div>
      <div class="form-group" style="display:flex;align-items:flex-end;padding-bottom:4px">
        <label class="form-check">
          <input type="checkbox" name="is_featured" <?= isset($_POST['is_featured']) ? 'checked' : '' ?>>
          Feature on homepage
        </label>
      </div>
    </div>
  </div>

  <button type="submit" class="btn-sm primary" style="padding:12px 28px;font-size:0.72rem">
    Continue to Images →
  </button>
</form>

<?php include 'includes/layout_bottom.php'; ?>
