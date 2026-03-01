<?php
$currentPage = 'index';
$pageTitle = 'Architecture · Engineering · Interior · Project Management';
$pageDesc  = 'Globwocs Co. Ltd — A consortium of multi-skilled professionals delivering world-class architecture, engineering, and interior design across Africa and beyond.';
require_once 'includes/db.php';

$db = getDB();

// Featured projects for homepage showcase (up to 3 for the triptych)
$featured = $db->query("
  SELECT p.*, pi.filename AS cover
  FROM projects p
  LEFT JOIN project_images pi ON pi.project_id = p.id AND pi.is_cover = 1
  WHERE p.is_featured = 1
  ORDER BY p.sort_order ASC
  LIMIT 3
")->fetchAll();

// Hero slides — use featured project covers + fallbacks
$heroSlides = [];
foreach ($featured as $f) {
  if ($f['cover']) $heroSlides[] = ['src' => 'uploads/projects/' . $f['cover'], 'name' => $f['name']];
}
// Ensure at least 4 slides from static images
$staticSlides = [
  ['src' => 'assets/img/hero-1.jpg', 'name' => ''],
  ['src' => 'assets/img/hero-2.jpg', 'name' => ''],
  ['src' => 'assets/img/hero-3.jpg', 'name' => ''],
  ['src' => 'assets/img/hero-4.jpg', 'name' => ''],
];
foreach ($staticSlides as $s) {
  if (count($heroSlides) < 4) $heroSlides[] = $s;
}

include 'includes/head.php';
include 'includes/nav.php';
?>

<!-- ═══════════════════════════════════════════════════════
     HERO
     ═══════════════════════════════════════════════════════ -->
<section class="hero">
  <div class="hero__slides-wrap">
    <?php foreach ($heroSlides as $i => $slide): ?>
    <div class="hero__slide <?= $i===0 ? 'active' : '' ?>"
         style="background-image:url('<?= htmlspecialchars($slide['src']) ?>')"></div>
    <?php endforeach; ?>
  </div>
  <div class="hero__grain"></div>
  <div class="hero__vignette"></div>

  <div class="hero__content">
    <div class="container">
      <div class="hero__grid">
        <div>
          <p class="hero__eyebrow">Architecture & Engineering</p>
          <h1 class="hero__title">
            Dream it.<br>
            <em>Design it.</em><br>
            Build it.
          </h1>
          <p class="hero__sub">
            Globwocs Co. Ltd — a consortium of multi-skilled professionals creating coherent, functional and extraordinary spaces across Africa and beyond.
          </p>
          <div class="hero__actions">
            <a href="projects.php" class="btn-fill">
              View Projects
              <svg width="14" height="10" viewBox="0 0 14 10" fill="none"><path d="M9 1L13 5M13 5L9 9M13 5H1" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/></svg>
            </a>
            <a href="contact.php" class="btn-outline">Start a Project</a>
          </div>
        </div>
        <div class="hero__side">
          <div class="hero__slide-counter">
            <span id="slide-counter">01</span> / <?= str_pad(count($heroSlides), 2, '0', STR_PAD_LEFT) ?>
          </div>
          <div class="hero__dots">
            <?php foreach ($heroSlides as $i => $_): ?>
            <div class="hero__dot <?= $i===0 ? 'active' : '' ?>"></div>
            <?php endforeach; ?>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- ═══════════════════════════════════════════════════════
     MARQUEE
     ═══════════════════════════════════════════════════════ -->
<div class="marquee-strip">
  <div class="marquee-track" aria-hidden="true">
    <?php
    $tags = ['Architecture','Structural Engineering','Interior Design','Project Management','Feasibility Studies','Building Surveying','Condition Surveys','QS Consultancy'];
    // Duplicate for seamless loop
    for ($r = 0; $r < 4; $r++) foreach ($tags as $t) {
      echo '<span class="marquee-item">' . $t . '</span>';
    }
    ?>
  </div>
</div>

<!-- ═══════════════════════════════════════════════════════
     ABOUT
     ═══════════════════════════════════════════════════════ -->
<section class="section">
  <div class="container">
    <div class="about-block">
      <div class="about-block__visual reveal-left">
        <div class="about-block__accent"></div>
        <img
          src="assets/img/about.jpg"
          alt="Globwocs design studio"
          class="about-block__img"
          loading="lazy"
        >
        <div class="about-block__badge">
          <span class="about-block__badge-num">15<sup>+</sup></span>
          <span class="about-block__badge-text">Years of<br>Excellence</span>
        </div>
      </div>
      <div class="about-block__text reveal-right">
        <p class="t-label">Who We Are</p>
        <h2 class="about-block__headline">
          Multi-disciplinary<br>design <em>consultants</em><br>to the world
        </h2>
        <p class="about-block__body">
          Globwocs Co. Ltd is a consortium of multi-skilled professionals that provide excellent consulting services by employing best international practices — keeping our clients abreast of global trends and maximally satisfied regarding their projects.
        </p>
        <p class="about-block__body">
          We create coherent, functional and responsive schemes after collating information about our clients' requirements. Each scheme is then coordinated and detailed wholesomely, with quality, uniqueness and optimal value for money at the core of everything we do.
        </p>
        <div class="about-block__values">
          <?php foreach (['Reliability','Integrity','Commitment','Enthusiasm'] as $v): ?>
          <span class="about-block__value"><?= $v ?></span>
          <?php endforeach; ?>
        </div>
        <div class="about-block__link" style="margin-top:2.5rem">
          <a href="about.php" class="btn">
            Our Story
            <svg width="14" height="10" viewBox="0 0 14 10" fill="none"><path d="M9 1L13 5M13 5L9 9M13 5H1" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/></svg>
          </a>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- ═══════════════════════════════════════════════════════
     STATS
     ═══════════════════════════════════════════════════════ -->
<div class="stats-bar">
  <div class="container">
    <div class="stats-grid">
      <div class="stat-item reveal reveal-d1">
        <p class="stat-num"><span data-count="15" data-suffix="+">15+</span></p>
        <p class="stat-label">Years of Practice</p>
      </div>
      <div class="stat-item reveal reveal-d2">
        <p class="stat-num"><span data-count="80" data-suffix="+">80+</span></p>
        <p class="stat-label">Projects Delivered</p>
      </div>
      <div class="stat-item reveal reveal-d3">
        <p class="stat-num"><span data-count="10">10</span></p>
        <p class="stat-label">Professional Bodies</p>
      </div>
      <div class="stat-item reveal reveal-d4">
        <p class="stat-num"><span data-count="5">5</span></p>
        <p class="stat-label">States Served</p>
      </div>
    </div>
  </div>
</div>

<!-- ═══════════════════════════════════════════════════════
     SERVICES PREVIEW
     ═══════════════════════════════════════════════════════ -->
<section class="section services-preview">
  <div class="container">
    <div class="section-head">
      <div class="section-head__left">
        <p class="t-label section-head__label reveal">What We Do</p>
        <h2 class="section-head__title reveal">Our <em>Services</em></h2>
      </div>
      <a href="about.php#services" class="btn reveal" style="flex-shrink:0">
        All Services
        <svg width="14" height="10" viewBox="0 0 14 10" fill="none"><path d="M9 1L13 5M13 5L9 9M13 5H1" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/></svg>
      </a>
    </div>
  </div>
  <div class="services-grid">
    <?php
    $services = [
      ['num'=>'01','name'=>'Feasibility Studies',                         'desc'=>'Site and financial analysis to validate your project before ground breaks.'],
      ['num'=>'02','name'=>'Land & Building Surveying',                   'desc'=>'Precise topographic and measured surveys to international standards.'],
      ['num'=>'03','name'=>'Condition Survey & Defect Analysis',          'desc'=>'Detailed structural health reports with prioritised remediation plans.'],
      ['num'=>'04','name'=>'Architectural & Engineering Consultancy',     'desc'=>'End-to-end design and technical consultancy across all building types.'],
      ['num'=>'05','name'=>'Interior Design',                             'desc'=>'Functional, distinctive interior schemes that elevate every space.'],
      ['num'=>'06','name'=>'Project Management',                          'desc'=>'Rigorous oversight ensuring on-time, on-budget and quality delivery.'],
      ['num'=>'07','name'=>'Building Documentation',                      'desc'=>'Comprehensive drawing packages from planning stage to as-built.'],
      ['num'=>'08','name'=>'Quantity Surveying',                          'desc'=>'Accurate cost planning, bills of quantities and procurement advisory.'],
    ];
    foreach ($services as $i => $s):
    ?>
    <div class="svc-item reveal" style="transition-delay:<?= ($i % 4) * 0.08 ?>s">
      <p class="svc-num"><?= $s['num'] ?></p>
      <h3 class="svc-name"><?= htmlspecialchars($s['name']) ?></h3>
      <p class="svc-desc"><?= htmlspecialchars($s['desc']) ?></p>
      <span class="svc-hover-line"></span>
    </div>
    <?php endforeach; ?>
  </div>
</section>

<!-- ═══════════════════════════════════════════════════════
     FEATURED PROJECTS
     ═══════════════════════════════════════════════════════ -->
<section class="section featured-projects">
  <div class="container">
    <div class="section-head">
      <div class="section-head__left">
        <p class="t-label section-head__label reveal">Selected Work</p>
        <h2 class="section-head__title reveal">Featured <em>Projects</em></h2>
      </div>
      <a href="projects.php" class="btn reveal" style="flex-shrink:0">
        All Projects
        <svg width="14" height="10" viewBox="0 0 14 10" fill="none"><path d="M9 1L13 5M13 5L9 9M13 5H1" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/></svg>
      </a>
    </div>
  </div>

  <?php if (count($featured) >= 2): ?>
  <div class="proj-showcase reveal-scale">
    <div class="proj-showcase__main">
      <div class="proj-card-inner">
        <?php $p = $featured[0]; $img = $p['cover'] ? 'uploads/projects/'.$p['cover'] : 'assets/img/hero-1.jpg'; ?>
        <img src="<?= htmlspecialchars($img) ?>" alt="<?= htmlspecialchars($p['name']) ?>" class="proj-card-img" loading="lazy">
        <a href="projects.php" class="proj-card-overlay" style="text-decoration:none">
          <div class="proj-card-meta">
            <p class="proj-card-name"><?= htmlspecialchars($p['name']) ?></p>
            <p class="proj-card-detail"><?= implode(' · ', array_filter([$p['category'] ?? '', $p['location'] ?? '', $p['year'] ?? ''])) ?></p>
          </div>
        </a>
      </div>
    </div>
    <div class="proj-showcase__grid">
      <?php foreach (array_slice($featured, 1, 2) as $p): ?>
      <div class="proj-showcase__thumb">
        <div class="proj-card-inner">
          <?php $img = $p['cover'] ? 'uploads/projects/'.$p['cover'] : 'assets/img/hero-2.jpg'; ?>
          <img src="<?= htmlspecialchars($img) ?>" alt="<?= htmlspecialchars($p['name']) ?>" class="proj-card-img" loading="lazy">
          <a href="projects.php" class="proj-card-overlay" style="text-decoration:none">
            <div class="proj-card-meta">
              <p class="proj-card-name"><?= htmlspecialchars($p['name']) ?></p>
              <p class="proj-card-detail"><?= implode(' · ', array_filter([$p['category'] ?? '', $p['location'] ?? '', $p['year'] ?? ''])) ?></p>
            </div>
          </a>
        </div>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
  <?php else: ?>
  <!-- Fallback if no projects yet -->
  <div class="container" style="text-align:center;padding:6rem 0">
    <p class="t-label" style="margin-bottom:1rem">Coming Soon</p>
    <!-- <p style="color:rgba(255,255,255,0.45);font-size:0.9rem">Projects will appear here once added via the admin panel.</p> -->
    <a href="projects.php" class="btn-fill" style="margin-top:2rem;display:inline-flex">View Projects</a>
  </div>
  <?php endif; ?>
</section>

<!-- ═══════════════════════════════════════════════════════
     QUOTE / CTA BAND
     ═══════════════════════════════════════════════════════ -->
<section style="background:var(--ink-mid);padding:120px 0;border-top:1px solid rgba(196,168,130,0.12);border-bottom:1px solid rgba(196,168,130,0.12)">
  <div class="container" style="text-align:center;max-width:860px;margin:0 auto">
    <p class="t-label reveal" style="margin-bottom:2rem">Our Philosophy</p>
    <blockquote class="reveal" style="font-family:var(--f-display);font-size:clamp(1.6rem,3.5vw,3rem);font-weight:400;font-style:italic;line-height:1.3;color:rgba(255,255,255,0.9);margin-bottom:3rem;letter-spacing:-0.02em">
      "We are primarily concerned with quality, uniqueness and optimal value for money solutions for our clients."
    </blockquote>
    <div class="reveal" style="display:flex;justify-content:center;gap:1.5rem;flex-wrap:wrap">
      <a href="contact.php" class="btn-fill">
        Start Your Project
        <svg width="14" height="10" viewBox="0 0 14 10" fill="none"><path d="M9 1L13 5M13 5L9 9M13 5H1" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/></svg>
      </a>
      <a href="about.php" class="btn-outline">Learn More</a>
    </div>
  </div>
</section>

<?php include 'includes/footer.php'; ?>
<script src="assets/js/main.js"></script>
</body>
</html>
