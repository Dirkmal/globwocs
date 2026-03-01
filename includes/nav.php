<?php
// includes/nav.php — expects $currentPage to be set by each page
$currentPage = $currentPage ?? '';
$links = [
  'index'    => ['href' => 'index.php',    'label' => 'Home'],
  'projects' => ['href' => 'projects.php', 'label' => 'Projects'],
  'about'    => ['href' => 'about.php',    'label' => 'About'],
  'contact'  => ['href' => 'contact.php',  'label' => 'Contact'],
];
?>
<nav id="nav">
  <div class="nav-inner">
    <a href="index.php" class="nav-logo" aria-label="Globwocs Home" style="flex-direction:row;align-items:center;gap:12px">
      <img src="assets/img/logo.png" alt="Globwocs logo" style="height:38px;width:auto;display:block;object-fit:contain">
      <div>
        <span class="nav-logo__name">GLOBWOCS</span>
        <span class="nav-logo__tag">Co. Ltd</span>
      </div>
    </a>

    <ul class="nav-links" role="list">
      <?php foreach ($links as $key => $link): ?>
      <li><a href="<?= $link['href'] ?>" class="<?= $currentPage === $key ? 'active' : '' ?>"><?= $link['label'] ?></a></li>
      <?php endforeach; ?>
    </ul>

    <div style="display:flex;align-items:center;gap:1.5rem">
      <a href="contact.php" class="nav-cta" style="display:none" id="nav-contact-btn">Enquire</a>
      <button class="nav-burger" id="nav-burger" aria-label="Open menu" aria-expanded="false">
        <span></span><span></span><span></span>
      </button>
    </div>
  </div>
</nav>

<!-- Mobile menu -->
<div id="mobile-menu" role="dialog" aria-modal="true" aria-label="Site navigation">
  <button id="menu-close" style="position:absolute;top:30px;right:30px;font-family:var(--f-mono);font-size:0.7rem;letter-spacing:0.2em;text-transform:uppercase;color:rgba(255,255,255,0.5);padding:8px" aria-label="Close menu">Close ×</button>
  <nav class="mobile-menu-links">
    <?php foreach ($links as $key => $link): ?>
    <a href="<?= $link['href'] ?>"><?= $link['label'] ?></a>
    <?php endforeach; ?>
    <a href="contact.php" style="font-size:clamp(1rem,4vw,1.5rem);font-family:var(--f-mono);letter-spacing:0.15em;text-transform:uppercase;color:var(--stone)">Enquire →</a>
  </nav>
</div>

<script>
// Show contact button in nav after scroll
window.addEventListener('scroll', function() {
  const btn = document.getElementById('nav-contact-btn');
  if (btn) btn.style.display = window.scrollY > 300 ? 'inline-flex' : 'none';
}, { passive: true });
</script>
