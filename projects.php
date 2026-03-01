<?php
$currentPage = 'projects';
$pageTitle   = 'Projects';
$pageDesc    = 'Explore the portfolio of Globwocs Co. Ltd — residential, commercial and institutional projects across Nigeria and Africa.';
require_once 'includes/db.php';

$db = getDB();

// All projects with cover image
$projects = $db->query("
  SELECT p.*, pi.filename AS cover
  FROM projects p
  LEFT JOIN project_images pi ON pi.project_id = p.id AND pi.is_cover = 1
  ORDER BY p.sort_order ASC, p.created_at DESC
")->fetchAll();

// For each project, get all images for lightbox
$allImages = [];
if ($projects) {
  $ids = implode(',', array_map(fn($r) => (int)$r['id'], $projects));
  $imgRows = $db->query("
    SELECT project_id, filename, caption
    FROM project_images
    WHERE project_id IN ($ids)
    ORDER BY is_cover DESC, sort_order ASC
  ")->fetchAll();
  foreach ($imgRows as $r) {
    $allImages[$r['project_id']][] = $r;
  }
}

// Unique categories
$categories = [];
foreach ($projects as $p) {
  if ($p['category'] && !in_array($p['category'], $categories)) {
    $categories[] = $p['category'];
  }
}

include 'includes/head.php';
include 'includes/nav.php';
?>

<!-- ═══════════════════════════════════════════════════════
     PAGE HERO
     ═══════════════════════════════════════════════════════ -->
<section class="page-hero">
  <img src="assets/img/projects-hero.jpg" alt="Globwocs projects" class="page-hero__img">
  <div class="page-hero__overlay"></div>
  <div class="page-hero__content">
    <div class="container">
      <p class="page-hero__tag">Our Portfolio</p>
      <h1 class="page-hero__title">Selected<br><em>Projects</em></h1>
    </div>
  </div>
</section>

<!-- ═══════════════════════════════════════════════════════
     FILTER + GRID
     ═══════════════════════════════════════════════════════ -->
<section class="section">
  <div class="container">

    <?php if ($categories): ?>
    <nav class="proj-filter" aria-label="Filter projects by category">
      <button class="filter-btn active" data-cat="all">All Work</button>
      <?php foreach ($categories as $cat): ?>
      <button class="filter-btn" data-cat="<?= htmlspecialchars($cat) ?>"><?= htmlspecialchars($cat) ?></button>
      <?php endforeach; ?>
    </nav>
    <?php endif; ?>

    <?php if ($projects): ?>
    <div class="projects-masonry" id="projects-grid">
      <?php foreach ($projects as $i => $p):
        $cover = $p['cover'] ? 'uploads/projects/' . $p['cover'] : 'assets/img/proj-placeholder.jpg';
        $imgs  = $allImages[$p['id']] ?? [];
        $lightboxData = array_map(fn($r) => [
          'src'    => 'uploads/projects/' . $r['filename'],
          'name'   => $p['name'],
          'detail' => implode(' · ', array_filter([$p['area_sqm'] ? $p['area_sqm'].'sqm' : '', $p['location'] ?? '', $p['year'] ?? ''])) . ($r['caption'] ? ' — ' . $r['caption'] : ''),
        ], $imgs ?: [['filename' => $p['cover'] ?: '', 'caption' => '']]);
      ?>
      <div class="masonry-item <?= $i < 3 ? 'always-show' : '' ?> reveal"
           data-cat="<?= htmlspecialchars($p['category'] ?? '') ?>"
           data-images='<?= htmlspecialchars(json_encode($lightboxData), ENT_QUOTES) ?>'
           style="transition-delay:<?= ($i % 6) * 0.07 ?>s"
           role="button"
           tabindex="0"
           aria-label="View <?= htmlspecialchars($p['name']) ?>">
        <img
          src="<?= htmlspecialchars($cover) ?>"
          alt="<?= htmlspecialchars($p['name']) ?>"
          class="masonry-img"
          loading="lazy"
        >
        <div class="masonry-overlay">
          <div>
            <p class="masonry-name"><?= htmlspecialchars($p['name']) ?></p>
            <p class="masonry-detail"><?= htmlspecialchars(implode(' · ', array_filter([$p['category'] ?? '', $p['location'] ?? '', $p['year'] ?? '']))) ?></p>
          </div>
        </div>
      </div>
      <?php endforeach; ?>
    </div>
    <?php else: ?>
    <div style="text-align:center;padding:8rem 0">
      <p class="t-label" style="margin-bottom:1rem">Portfolio</p>
      <p style="color:rgba(255,255,255,0.4);font-size:0.95rem">Projects will appear here once added via the admin panel.</p>
    </div>
    <?php endif; ?>

  </div>
</section>

<!-- ═══════════════════════════════════════════════════════
     CTA
     ═══════════════════════════════════════════════════════ -->
<section style="background:var(--ink-mid);padding:100px 0;border-top:1px solid rgba(196,168,130,0.12)">
  <div class="container" style="display:flex;align-items:center;justify-content:space-between;gap:2rem;flex-wrap:wrap">
    <div class="reveal">
      <p class="t-label" style="margin-bottom:0.75rem">Have a Project in Mind?</p>
      <h2 style="font-family:var(--f-display);font-size:clamp(1.5rem,3vw,2.5rem);font-weight:400;letter-spacing:-0.02em">
        Let's build something <em>extraordinary</em>
      </h2>
    </div>
    <a href="contact.php" class="btn-fill reveal reveal-d2">
      Start a Conversation
      <svg width="14" height="10" viewBox="0 0 14 10" fill="none"><path d="M9 1L13 5M13 5L9 9M13 5H1" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/></svg>
    </a>
  </div>
</section>

<!-- ═══════════════════════════════════════════════════════
     LIGHTBOX
     ═══════════════════════════════════════════════════════ -->
<div id="lightbox" role="dialog" aria-modal="true" aria-label="Project images">
  <p class="lb-counter" id="lb-counter">1 / 1</p>
  <button class="lb-close" aria-label="Close lightbox">&#215;</button>
  <div class="lb-main">
    <img id="lb-img" src="" alt="" style="transition:opacity 0.3s">
    <div class="lb-meta">
      <p id="lb-title"></p>
      <p id="lb-detail"></p>
    </div>
    <div class="lb-nav">
      <button class="lb-prev" aria-label="Previous image">&#8592;</button>
      <button class="lb-next" aria-label="Next image">&#8594;</button>
    </div>
  </div>
</div>

<?php include 'includes/footer.php'; ?>
<script src="assets/js/main.js"></script>
<script>
// Keyboard accessibility for masonry items
document.querySelectorAll('.masonry-item[data-images]').forEach(item => {
  item.addEventListener('keydown', e => {
    if (e.key === 'Enter' || e.key === ' ') {
      e.preventDefault();
      const data = JSON.parse(item.dataset.images || '[]');
      window.globwocs?.openLightbox({ images: data });
    }
  });
});
</script>
</body>
</html>
