<?php
$currentPage = 'about';
$pageTitle   = 'About Us';
$pageDesc    = 'Learn about Globwocs Co. Ltd — our story, mission, values and professional memberships.';
include 'includes/head.php';
include 'includes/nav.php';
?>

<!-- ═══════════════════════════════════════════════════════
     PAGE HERO
     ═══════════════════════════════════════════════════════ -->
<section class="page-hero">
  <img src="assets/img/about-hero.jpg" alt="Globwocs design ethos" class="page-hero__img">
  <div class="page-hero__overlay"></div>
  <div class="page-hero__content">
    <div class="container">
      <p class="page-hero__tag">About Globwocs</p>
      <h1 class="page-hero__title">We build what<br>others only <em>imagine</em></h1>
    </div>
  </div>
</section>

<!-- ═══════════════════════════════════════════════════════
     STORY
     ═══════════════════════════════════════════════════════ -->
<section class="section">
  <div class="container">
    <div class="two-col-text">
      <div class="two-col-text__sticky">
        <p class="t-label reveal" style="margin-bottom:1rem">Our Story</p>
        <h2 class="two-col-text__heading reveal">
          A consortium built<br>on <em>excellence</em>
        </h2>
        <span class="svc-hover-line reveal" style="width:40px;display:block;margin-top:1.5rem"></span>
      </div>
      <div class="two-col-text__body reveal">
        <p>Globwocs Co. Ltd is a consortium of multi-skilled professionals that provide excellent consulting services by employing best international practices — keeping our clients abreast of global trends and maximally satisfied regarding their projects.</p>
        <p>We create coherent, functional and responsive schemes after collating information about our clients' requirements. Each scheme is then coordinated and detailed wholesomely, with no element left to chance.</p>
        <p>We are primarily concerned with <strong style="color:var(--stone)">quality</strong>, <strong style="color:var(--stone)">uniqueness</strong> and <strong style="color:var(--stone)">optimal value for money solutions</strong> for our clients. Our multi-disciplinary team brings together architects, engineers, interior designers and project managers under one roof — ensuring seamless delivery from brief to handover.</p>
        <p>From private residences and luxury estates to government institutions and commercial developments, Globwocs has established a track record of delivering projects that exceed expectations, on time and within budget.</p>
      </div>
    </div>
  </div>
</section>

<!-- ═══════════════════════════════════════════════════════
     DIVIDER IMAGE
     ═══════════════════════════════════════════════════════ -->
<div style="height:50vh;min-height:360px;overflow:hidden;position:relative">
  <img src="assets/img/about-mid.jpg" alt="Globwocs project" style="width:100%;height:100%;object-fit:cover;filter:brightness(0.55)">
  <div style="position:absolute;inset:0;background:linear-gradient(to right,rgba(12,11,9,0.7) 0%,transparent 60%)"></div>
  <div style="position:absolute;inset:0;display:flex;align-items:center;padding:0 40px">
    <div class="container">
      <p class="t-label reveal" style="margin-bottom:1rem">Our Approach</p>
      <p class="reveal" style="font-family:var(--f-display);font-size:clamp(1.4rem,3vw,2.2rem);font-weight:400;font-style:italic;max-width:600px;line-height:1.4;color:rgba(255,255,255,0.9)">
        "Breaking grounds, transforming spaces — globally."
      </p>
    </div>
  </div>
</div>

<!-- ═══════════════════════════════════════════════════════
     CORE VALUES
     ═══════════════════════════════════════════════════════ -->
<section class="section" style="background:var(--ink-mid)">
  <div class="container">
    <div style="text-align:center;margin-bottom:4rem">
      <p class="t-label reveal" style="margin-bottom:1rem">What Drives Us</p>
      <h2 class="section-head__title reveal">Core <em>Values</em></h2>
    </div>
    <div class="values-grid">
      <?php
      $values = [
        ['R','Reliability',   'We show up consistently — meeting deadlines, honouring commitments and maintaining the highest professional standards on every engagement, large or small.'],
        ['I','Integrity',     'Transparency and honesty guide every recommendation we make. We tell clients what they need to hear, not just what they want to hear, protecting their interests above all else.'],
        ['C','Commitment',    'From initial brief through to project handover, our dedication never wavers. We pursue management efficiency while effectively managing business risk at every stage.'],
        ['E','Enthusiasm',    'We are genuinely passionate about design and construction. This enthusiasm translates into creative problem-solving and an energy that elevates every project we touch.'],
      ];
      foreach ($values as $i => $v):
      ?>
      <div class="value-card reveal" style="transition-delay:<?= $i * 0.1 ?>s">
        <div class="value-card__letter"><?= $v[0] ?></div>
        <h3 class="value-card__name"><?= $v[1] ?></h3>
        <p class="value-card__desc"><?= $v[2] ?></p>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- ═══════════════════════════════════════════════════════
     SERVICES DETAIL
     ═══════════════════════════════════════════════════════ -->
<section class="section" id="services">
  <div class="container">
    <div class="section-head">
      <div class="section-head__left">
        <p class="t-label section-head__label reveal">What We Offer</p>
        <h2 class="section-head__title reveal">Our <em>Services</em></h2>
      </div>
    </div>
    <div style="display:grid;grid-template-columns:repeat(2,1fr);gap:2px" class="reveal-scale">
      <?php
      $services = [
        ['01','Feasibility Studies',            'We conduct comprehensive site, financial and regulatory analysis to evaluate project viability before commitment. Our studies cover market conditions, statutory requirements, infrastructure capacity and projected returns — giving decision-makers the clarity to proceed with confidence.'],
        ['02','Land & Building Surveying',      'Precise topographic, boundary and measured building surveys using current best practice and international standards. Our survey data underpins accurate design, procurement and dispute resolution.'],
        ['03','Condition Survey & Defect Analysis','Detailed assessment of existing structures to identify defects, material failure, structural risk and recommended remediation. Our reports provide prioritised action plans with cost estimates, helping asset owners plan maintenance and refurbishment strategically.'],
        ['04','Architectural & Engineering Consultancy','Comprehensive design and technical services from concept through to completion — covering architecture, structural, mechanical, electrical and plumbing engineering. We integrate all disciplines under one coordinated package.'],
        ['05','Interior Design',                'Thoughtful, functional interior schemes that express personality while maximising utility and comfort. We handle space planning, material and finish specification, furniture, lighting design and fit-out supervision.'],
        ['06','Project Management',             'Rigorous project management ensuring on-time, on-budget delivery with full quality and risk controls. We act as your representative throughout the project lifecycle — from procurement strategy and contract administration to snagging and handover.'],
        ['07','Building Documentation',         'Comprehensive drawing and specification packages that communicate design intent clearly to all stakeholders. From planning and building regulation submissions through to as-built record drawings.'],
        ['08','Quantity Surveying',             'Accurate cost planning, bills of quantities, tender evaluation and contract administration. Our QS services give clients control over project expenditure at every stage, minimising cost overruns and procurement risk.'],
      ];
      foreach ($services as $i => $s):
      ?>
      <div style="background:var(--ink-mid);padding:40px;border-bottom:1px solid rgba(255,255,255,0.05);border-right:1px solid rgba(255,255,255,0.05)">
        <p style="font-family:var(--f-mono);font-size:0.6rem;letter-spacing:0.15em;color:var(--stone);margin-bottom:1rem"><?= $s[0] ?></p>
        <h3 style="font-family:var(--f-display);font-size:1.15rem;font-weight:400;margin-bottom:0.875rem;line-height:1.3"><?= htmlspecialchars($s[1]) ?></h3>
        <p style="font-size:0.85rem;color:rgba(255,255,255,0.5);line-height:1.85"><?= htmlspecialchars($s[2]) ?></p>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- ═══════════════════════════════════════════════════════
     PROFESSIONAL MEMBERSHIPS
     ═══════════════════════════════════════════════════════ -->
<div class="memberships">
  <div class="container">
    <div style="text-align:center;margin-bottom:3rem">
      <p class="t-label reveal" style="margin-bottom:1rem">Credentials</p>
      <h2 class="section-head__title reveal">Professional <em>Memberships</em></h2>
      <p class="reveal" style="font-size:0.875rem;color:rgba(255,255,255,0.45);margin-top:1rem;max-width:560px;margin-left:auto;margin-right:auto">Our team are proud full members of the following leading professional and regulatory bodies:</p>
    </div>
    <ul class="membership-list">
      <?php
      $bodies = [
        ['NIA',   'Nigerian Institute of Architects'],
        ['NSE',   'Nigerian Society of Engineers'],
        ['COREN', 'Council for the Regulation of Engineering in Nigeria'],
        ['NIOB',  'Nigerian Institute of Building'],
        ['NIQS',  'Nigerian Institute of Quantity Surveyors'],
        ['ASCE',  'American Society of Civil Engineers'],
        ['NIStructE','Nigerian Institution of Structural Engineers'],
        ['IAENG', 'International Association of Engineers'],
        ['CIBSE', 'Chartered Institution of Building Services Engineers'],
        ['ASHRAE','American Society of Heating, Refrigerating & Air-Conditioning Engineers'],
      ];
      foreach ($bodies as $i => $b):
      ?>
      <li class="membership-item reveal" style="transition-delay:<?= ($i % 5) * 0.06 ?>s">
        <p class="membership-acronym"><?= $b[0] ?></p>
        <p class="membership-full"><?= $b[1] ?></p>
      </li>
      <?php endforeach; ?>
    </ul>
  </div>
</div>

<!-- ═══════════════════════════════════════════════════════
     CTA
     ═══════════════════════════════════════════════════════ -->
<section style="padding:120px 0;text-align:center;background:var(--ink)">
  <div class="container" style="max-width:720px;margin:0 auto">
    <p class="t-label reveal" style="margin-bottom:2rem">Ready to Begin?</p>
    <h2 class="reveal" style="font-family:var(--f-display);font-size:clamp(2rem,4vw,3.5rem);font-weight:400;letter-spacing:-0.02em;margin-bottom:3rem">
      Let's create something <em>extraordinary</em> together
    </h2>
    <div class="reveal reveal-d2" style="display:flex;justify-content:center;gap:1.5rem;flex-wrap:wrap">
      <a href="contact.php" class="btn-fill">
        Get in Touch
        <svg width="14" height="10" viewBox="0 0 14 10" fill="none"><path d="M9 1L13 5M13 5L9 9M13 5H1" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/></svg>
      </a>
      <a href="projects.php" class="btn-outline">View Our Work</a>
    </div>
  </div>
</section>

<?php include 'includes/footer.php'; ?>
<script src="assets/js/main.js"></script>
</body>
</html>
