<?php
$adminTitle = 'Edit Project';
require_once 'includes/auth.php';
requireLogin();
require_once '../includes/db.php';
$db = getDB();

$id = (int)($_GET['id'] ?? 0);
if (!$id) { header('Location: projects.php'); exit; }

$project = $db->prepare("SELECT * FROM projects WHERE id=?");
$project->execute([$id]);
$project = $project->fetch();
if (!$project) { header('Location: projects.php'); exit; }

$images = $db->prepare("SELECT * FROM project_images WHERE project_id=? ORDER BY is_cover DESC, sort_order ASC");
$images->execute([$id]);
$images = $images->fetchAll();

$success = false;
$errors  = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? 'update';

    if ($action === 'delete_image') {
        $imgId = (int)($_POST['image_id'] ?? 0);
        $row = $db->prepare("SELECT filename FROM project_images WHERE id=? AND project_id=?");
        $row->execute([$imgId, $id]);
        $row = $row->fetch();
        if ($row) {
            $path = '../uploads/projects/' . $row['filename'];
            if (file_exists($path)) unlink($path);
            $db->prepare("DELETE FROM project_images WHERE id=?")->execute([$imgId]);
        }
        header("Location: project-edit.php?id=$id"); exit;
    }

    if ($action === 'set_cover') {
        $imgId = (int)($_POST['image_id'] ?? 0);
        $db->prepare("UPDATE project_images SET is_cover=0 WHERE project_id=?")->execute([$id]);
        $db->prepare("UPDATE project_images SET is_cover=1 WHERE id=? AND project_id=?")->execute([$imgId, $id]);
        header("Location: project-edit.php?id=$id"); exit;
    }

    // Main details update
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
        $db->prepare("UPDATE projects SET name=?,description=?,location=?,year=?,category=?,area_sqm=?,is_featured=?,sort_order=? WHERE id=?")
           ->execute([$name, $desc, $location, $year, $category, $area ?: null, $featured, $sort, $id]);
        $success = true;

        // Refresh
        $stmt = $db->prepare("SELECT * FROM projects WHERE id=?");
        $stmt->execute([$id]);
        $project = $stmt->fetch();
        $stmt = $db->prepare("SELECT * FROM project_images WHERE project_id=? ORDER BY is_cover DESC, sort_order ASC");
        $stmt->execute([$id]);
        $images = $stmt->fetchAll();
    }
}

$isNew = !empty($_GET['new']);
include 'includes/layout_top.php';
?>

<div style="display:flex;align-items:center;gap:16px;margin-bottom:8px">
  <a href="projects.php" style="color:rgba(255,255,255,0.35);font-size:0.75rem;font-family:var(--f-mono);letter-spacing:0.1em">← Projects</a>
</div>

<?php if ($isNew): ?>
<div class="alert success">Project created! Now add your images below.</div>
<?php endif; ?>

<h1 class="page-title"><?= htmlspecialchars($project['name']) ?></h1>
<p class="page-sub">Update project details or manage images.</p>

<?php if ($success): ?><div class="alert success">Project updated successfully.</div><?php endif; ?>
<?php foreach ($errors as $e): ?><div class="alert error"><?= htmlspecialchars($e) ?></div><?php endforeach; ?>

<!-- ── Details form ───────────────────────────────────────── -->
<form method="POST">
  <input type="hidden" name="action" value="update">
  <div class="card">
    <h2 style="font-size:0.8rem;font-family:var(--f-mono);letter-spacing:0.1em;text-transform:uppercase;color:rgba(255,255,255,0.4);margin-bottom:18px">Project Details</h2>
    <div class="form-group">
      <label class="form-label">Project Name *</label>
      <input class="form-input" type="text" name="name" value="<?= htmlspecialchars($project['name']) ?>" required>
    </div>
    <div class="form-group">
      <label class="form-label">Description</label>
      <textarea class="form-textarea" name="description"><?= htmlspecialchars($project['description'] ?? '') ?></textarea>
    </div>
    <div class="form-row">
      <div class="form-group">
        <label class="form-label">Location</label>
        <input class="form-input" type="text" name="location" value="<?= htmlspecialchars($project['location'] ?? '') ?>">
      </div>
      <div class="form-group">
        <label class="form-label">Year</label>
        <input class="form-input" type="text" name="year" value="<?= htmlspecialchars($project['year'] ?? '') ?>">
      </div>
    </div>
    <div class="form-row">
      <div class="form-group">
        <label class="form-label">Category</label>
        <select class="form-select" name="category">
          <option value="">Select…</option>
          <?php foreach (['Residential','Commercial','Institutional','Industrial','Mixed Use','Interior','Infrastructure'] as $cat): ?>
          <option <?= ($project['category'] === $cat) ? 'selected' : '' ?>><?= $cat ?></option>
          <?php endforeach; ?>
        </select>
      </div>
      <div class="form-group">
        <label class="form-label">Area (sqm)</label>
        <input class="form-input" type="text" name="area_sqm" value="<?= htmlspecialchars($project['area_sqm'] ?? '') ?>">
      </div>
    </div>
    <div class="form-row">
      <div class="form-group">
        <label class="form-label">Sort Order</label>
        <input class="form-input" type="number" name="sort_order" value="<?= (int)$project['sort_order'] ?>">
      </div>
      <div class="form-group" style="display:flex;align-items:flex-end;padding-bottom:4px">
        <label class="form-check">
          <input type="checkbox" name="is_featured" <?= $project['is_featured'] ? 'checked' : '' ?>>
          Feature on homepage
        </label>
      </div>
    </div>
    <button type="submit" class="btn-sm primary">Save Changes</button>
  </div>
</form>

<!-- ── Current images ────────────────────────────────────── -->
<div class="card">
  <h2 style="font-size:0.8rem;font-family:var(--f-mono);letter-spacing:0.1em;text-transform:uppercase;color:rgba(255,255,255,0.4);margin-bottom:18px">
    Images (<span id="img-count"><?= count($images) ?></span>)
  </h2>

  <div class="img-grid" id="current-images">
    <?php foreach ($images as $img): ?>
    <div id="imgwrap-<?= $img['id'] ?>" style="position:relative">
      <img src="../uploads/projects/<?= htmlspecialchars($img['filename']) ?>"
           style="width:90px;height:90px;object-fit:cover;display:block;border:2px solid <?= $img['is_cover'] ? '#c4a882' : 'transparent' ?>">
      <?php if ($img['is_cover']): ?>
      <div style="position:absolute;bottom:2px;left:2px;background:#c4a882;color:#0c0b09;font-size:8px;padding:1px 5px;font-family:monospace">COVER</div>
      <?php endif; ?>
      <div style="margin-top:4px;display:flex;gap:4px">
        <?php if (!$img['is_cover']): ?>
        <form method="POST" style="display:inline">
          <input type="hidden" name="action"   value="set_cover">
          <input type="hidden" name="image_id" value="<?= $img['id'] ?>">
          <button type="submit" class="btn-sm ghost" style="font-size:0.55rem;padding:4px 8px">Cover</button>
        </form>
        <?php endif; ?>
        <form method="POST" style="display:inline" onsubmit="return confirm('Delete this image?')">
          <input type="hidden" name="action"   value="delete_image">
          <input type="hidden" name="image_id" value="<?= $img['id'] ?>">
          <button type="submit" class="btn-sm danger" style="font-size:0.55rem;padding:4px 8px">×</button>
        </form>
      </div>
    </div>
    <?php endforeach; ?>
    <?php if (!$images): ?>
    <p id="no-images-msg" style="color:rgba(255,255,255,0.35);font-size:0.82rem">No images yet — add some below.</p>
    <?php endif; ?>
  </div>

  <!-- ── Upload new images ─────────────────────────────────── -->
  <hr style="border:none;border-top:1px solid rgba(255,255,255,0.07);margin:24px 0">
  <h3 style="font-size:0.75rem;font-family:var(--f-mono);letter-spacing:0.1em;text-transform:uppercase;color:rgba(255,255,255,0.35);margin-bottom:4px">Add Images</h3>
  <p style="font-size:0.75rem;color:rgba(255,255,255,0.3);margin-bottom:14px">Each image is compressed then uploaded one at a time — no timeouts.</p>

  <div class="drop-zone" id="drop-zone" style="margin-bottom:0">
    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" style="color:rgba(255,255,255,0.2);margin:0 auto 6px;display:block"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4M17 8l-5-5-5 5M12 3v12"/></svg>
    <p>Drop images here or <label for="file-input" style="color:var(--stone);cursor:pointer;text-decoration:underline">browse</label></p>
    <input type="file" id="file-input" multiple accept="image/*" style="display:none">
  </div>

  <!-- Queue -->
  <div id="queue" style="margin-top:14px"></div>

  <!-- Overall progress -->
  <div id="overall-wrap" style="display:none;margin-top:12px">
    <div style="display:flex;justify-content:space-between;margin-bottom:6px">
      <span id="overall-label" style="font-family:var(--f-mono);font-size:0.62rem;letter-spacing:0.1em;color:rgba(255,255,255,0.4)">Uploading…</span>
      <span id="overall-pct"   style="font-family:var(--f-mono);font-size:0.62rem;color:var(--stone)"></span>
    </div>
    <div style="height:2px;background:rgba(255,255,255,0.08)">
      <div id="overall-bar" style="height:2px;background:var(--stone);width:0%;transition:width 0.3s"></div>
    </div>
  </div>

  <button id="upload-btn" class="btn-sm primary" style="margin-top:14px;display:none">
    Upload All Images
  </button>
</div>

<script>
(function () {
  var PROJECT_ID = <?= $id ?>;
  var queue      = [];   // { file, dataUrl, status: 'pending'|'uploading'|'done'|'error' }
  var isUploading = false;

  var dropZone    = document.getElementById('drop-zone');
  var fileInput   = document.getElementById('file-input');
  var queueEl     = document.getElementById('queue');
  var uploadBtn   = document.getElementById('upload-btn');
  var overallWrap = document.getElementById('overall-wrap');
  var overallBar  = document.getElementById('overall-bar');
  var overallLbl  = document.getElementById('overall-label');
  var overallPct  = document.getElementById('overall-pct');
  var imgCount    = document.getElementById('img-count');
  var currentImgs = document.getElementById('current-images');
  var noImgMsg    = document.getElementById('no-images-msg');

  // ── Drop / select ──────────────────────────────────────────
  dropZone.addEventListener('dragover',  function(e) { e.preventDefault(); dropZone.classList.add('dragover'); });
  dropZone.addEventListener('dragleave', function()  { dropZone.classList.remove('dragover'); });
  dropZone.addEventListener('drop', function(e) {
    e.preventDefault(); dropZone.classList.remove('dragover');
    addFiles(Array.prototype.filter.call(e.dataTransfer.files, function(f) { return f.type.indexOf('image/') === 0; }));
  });
  fileInput.addEventListener('change', function() {
    addFiles(Array.prototype.slice.call(fileInput.files));
    fileInput.value = '';
  });

  function addFiles(files) {
    files.forEach(function(f) {
      if (queue.length >= 20) return;
      queue.push({ file: f, dataUrl: null, status: 'pending' });
    });
    renderQueue();
    if (queue.length) uploadBtn.style.display = 'inline-flex';
    // Auto-compress previews
    queue.forEach(function(item, i) {
      if (!item.dataUrl) {
        compress(item.file, 1800, 0.82).then(function(url) {
          queue[i].dataUrl = url;
          renderQueue();
        });
      }
    });
  }

  function renderQueue() {
    queueEl.innerHTML = '';
    queue.forEach(function(item, i) {
      var row = document.createElement('div');
      row.style.cssText = 'display:flex;align-items:center;gap:10px;padding:8px 0;border-bottom:1px solid rgba(255,255,255,0.05)';
      var thumb = item.dataUrl
        ? '<img src="' + item.dataUrl + '" style="width:44px;height:44px;object-fit:cover;flex-shrink:0">'
        : '<div style="width:44px;height:44px;background:rgba(255,255,255,0.06);flex-shrink:0;display:flex;align-items:center;justify-content:center;font-size:10px;color:rgba(255,255,255,0.3)">…</div>';
      var statusColor = { pending:'rgba(255,255,255,0.3)', uploading:'#c4a882', done:'#5ac87a', error:'#e05a5a' }[item.status];
      var statusText  = { pending:'Waiting', uploading:'Uploading…', done:'✓ Done', error:'✗ Failed' }[item.status];
      row.innerHTML = thumb +
        '<span style="flex:1;font-size:0.78rem;color:rgba(255,255,255,0.6);white-space:nowrap;overflow:hidden;text-overflow:ellipsis">' + item.file.name + '</span>' +
        '<span style="font-family:var(--f-mono);font-size:0.6rem;color:' + statusColor + ';flex-shrink:0">' + statusText + '</span>' +
        (item.status === 'pending' ? '<button type="button" data-rm="' + i + '" style="background:none;border:none;color:rgba(255,255,255,0.3);cursor:pointer;font-size:14px;flex-shrink:0">&times;</button>' : '');
      var rmBtn = row.querySelector('[data-rm]');
      if (rmBtn) rmBtn.addEventListener('click', function() {
        queue.splice(parseInt(this.dataset.rm), 1);
        renderQueue();
        if (!queue.length) uploadBtn.style.display = 'none';
      });
      queueEl.appendChild(row);
    });
  }

  // ── Upload button ──────────────────────────────────────────
  uploadBtn.addEventListener('click', function() {
    if (isUploading) return;
    startUpload();
  });

  async function startUpload() {
    isUploading = true;
    uploadBtn.disabled = true;
    overallWrap.style.display = 'block';

    var pending = queue.filter(function(i) { return i.status === 'pending'; });
    var total   = pending.length;
    var done    = 0;
    var currentCount = parseInt(imgCount.textContent) || 0;
    var isFirstUpload = (currentCount === 0);

    for (var i = 0; i < queue.length; i++) {
      var item = queue[i];
      if (item.status !== 'pending') continue;

      // Ensure compressed
      if (!item.dataUrl) {
        item.dataUrl = await compress(item.file, 1800, 0.82);
      }

      item.status = 'uploading';
      renderQueue();
      overallLbl.textContent = 'Uploading ' + (done + 1) + ' of ' + total + '…';
      overallPct.textContent = Math.round((done / total) * 100) + '%';
      overallBar.style.width = Math.round((done / total) * 100) + '%';

      var isCover = (isFirstUpload && done === 0) ? 1 : 0;

      try {
        var result = await uploadOne(item.dataUrl, isCover, currentCount + done);
        if (result.ok) {
          item.status = 'done';
          done++;
          addThumbToGrid(result, isCover);
          currentCount++;
          imgCount.textContent = currentCount;
          if (noImgMsg) { noImgMsg.remove(); noImgMsg = null; }
        } else {
          item.status = 'error';
          console.error('Upload error:', result.error);
        }
      } catch (e) {
        item.status = 'error';
        console.error('Network error:', e);
      }

      renderQueue();
    }

    overallBar.style.width = '100%';
    overallLbl.textContent = done + ' of ' + total + ' uploaded';
    overallPct.textContent = '100%';

    isUploading   = false;
    uploadBtn.disabled = false;

    // Clear done items after 2s
    setTimeout(function() {
      queue.splice(0, queue.length, ...queue.filter(function(i) { return i.status !== 'done'; }));
      renderQueue();
      if (!queue.length) {
        uploadBtn.style.display  = 'none';
        overallWrap.style.display = 'none';
      }
    }, 2000);
  }

  function uploadOne(dataUrl, isCover, sortOrder) {
    return new Promise(function(resolve, reject) {
      var fd = new FormData();
      fd.append('project_id', PROJECT_ID);
      fd.append('image_data',  dataUrl);
      fd.append('is_cover',    isCover);
      fd.append('sort_order',  sortOrder);
      var xhr = new XMLHttpRequest();
      xhr.open('POST', 'upload-image.php');
      xhr.onload = function() {
        try { resolve(JSON.parse(xhr.responseText)); }
        catch(e) { reject(new Error('Bad response')); }
      };
      xhr.onerror = function() { reject(new Error('Network error')); };
      xhr.send(fd);
    });
  }

  function addThumbToGrid(result, isCover) {
    var wrap = document.createElement('div');
    wrap.id = 'imgwrap-' + result.id;
    wrap.style.position = 'relative';
    wrap.innerHTML =
      '<img src="../uploads/projects/' + result.filename + '" style="width:90px;height:90px;object-fit:cover;display:block;border:2px solid ' + (isCover ? '#c4a882' : 'transparent') + '">' +
      (isCover ? '<div style="position:absolute;bottom:2px;left:2px;background:#c4a882;color:#0c0b09;font-size:8px;padding:1px 5px;font-family:monospace">COVER</div>' : '') +
      '<div style="margin-top:4px;display:flex;gap:4px">' +
        (!isCover ? '<form method="POST" style="display:inline"><input type="hidden" name="action" value="set_cover"><input type="hidden" name="image_id" value="' + result.id + '"><button type="submit" class="btn-sm ghost" style="font-size:0.55rem;padding:4px 8px">Cover</button></form>' : '') +
        '<form method="POST" style="display:inline" onsubmit="return confirm(\'Delete this image?\')"><input type="hidden" name="action" value="delete_image"><input type="hidden" name="image_id" value="' + result.id + '"><button type="submit" class="btn-sm danger" style="font-size:0.55rem;padding:4px 8px">&times;</button></form>' +
      '</div>';
    currentImgs.appendChild(wrap);
  }

  // ── Canvas compress ────────────────────────────────────────
  function compress(file, maxPx, quality) {
    return new Promise(function(resolve) {
      var img = new Image(), url = URL.createObjectURL(file);
      img.onload = function() {
        URL.revokeObjectURL(url);
        var w = img.width, h = img.height;
        if (w > maxPx || h > maxPx) {
          if (w > h) { h = Math.round(h * maxPx / w); w = maxPx; }
          else       { w = Math.round(w * maxPx / h); h = maxPx; }
        }
        var c = document.createElement('canvas');
        c.width = w; c.height = h;
        c.getContext('2d').drawImage(img, 0, 0, w, h);
        resolve(c.toDataURL('image/jpeg', quality));
      };
      img.src = url;
    });
  }
})();
</script>

<?php include 'includes/layout_bottom.php'; ?>
