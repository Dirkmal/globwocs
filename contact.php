<?php
$currentPage = 'contact';
$pageTitle   = 'Contact';
$pageDesc    = 'Get in touch with Globwocs Co. Ltd to discuss your architecture, engineering or interior design project.';
include 'includes/head.php';
include 'includes/nav.php';
?>

<!-- ═══════════════════════════════════════════════════════
     PAGE HERO
     ═══════════════════════════════════════════════════════ -->
<section class="page-hero" style="height:50vh;min-height:400px">
  <img src="assets/img/contact-hero.jpg" alt="Get in touch" class="page-hero__img">
  <div class="page-hero__overlay"></div>
  <div class="page-hero__content">
    <div class="container">
      <p class="page-hero__tag">Get in Touch</p>
      <h1 class="page-hero__title">Let's build<br>something <em>great</em></h1>
    </div>
  </div>
</section>

<!-- ═══════════════════════════════════════════════════════
     CONTACT SPLIT
     ═══════════════════════════════════════════════════════ -->
<section class="section">
  <div class="container">
    <div class="contact-split">

      <!-- LEFT: Info -->
      <div class="contact-info reveal-left">
        <p class="t-label" style="margin-bottom:2rem">Contact Information</p>

        <div class="contact-info__item">
          <p class="contact-info__label">Phone</p>
          <p class="contact-info__value">
            <a href="tel:+2348186009389">+234 818 600 9389</a>
          </p>
        </div>

        <div class="contact-info__item">
          <p class="contact-info__label">Email</p>
          <p class="contact-info__value">
            <a href="mailto:contactus@globwocs.com">contactus@globwocs.com</a><br>
            <a href="mailto:globwocs@yahoo.com">globwocs@yahoo.com</a>
          </p>
        </div>

        <div class="contact-info__item">
          <p class="contact-info__label">Services</p>
          <p class="contact-info__value" style="color:rgba(255,255,255,0.55)">
            Architecture · Engineering<br>
            Interior Design · Project Management<br>
            Feasibility · Surveying
          </p>
        </div>

        <div class="contact-info__item">
          <p class="contact-info__label">Working Hours</p>
          <p class="contact-info__value" style="color:rgba(255,255,255,0.55)">
            Monday – Friday: 8:00am – 6:00pm<br>
            Saturday: 9:00am – 2:00pm
          </p>
        </div>

        <div style="margin-top:3rem;padding:32px;background:var(--ink-mid);border:1px solid rgba(196,168,130,0.15)">
          <p style="font-family:var(--f-display);font-size:1rem;font-weight:400;font-style:italic;color:rgba(255,255,255,0.75);line-height:1.6">
            "Every great project begins with a conversation. We would love to hear about yours."
          </p>
        </div>
      </div>

      <!-- RIGHT: Form -->
      <div class="contact-form-wrap reveal-right">
        <p class="t-label" style="margin-bottom:2rem">Send an Enquiry</p>

        <form id="contact-form" novalidate>
          <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px">
            <div class="form-group">
              <label class="form-label" for="name">Full Name *</label>
              <input class="form-input" type="text" id="name" name="name" placeholder="Your name" required>
            </div>
            <div class="form-group">
              <label class="form-label" for="email">Email Address *</label>
              <input class="form-input" type="email" id="email" name="email" placeholder="your@email.com" required>
            </div>
          </div>
          <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px">
            <div class="form-group">
              <label class="form-label" for="phone">Phone Number</label>
              <input class="form-input" type="tel" id="phone" name="phone" placeholder="+234 …">
            </div>
            <div class="form-group">
              <label class="form-label" for="org">Organisation</label>
              <input class="form-input" type="text" id="org" name="organisation" placeholder="Company / Institution">
            </div>
          </div>
          <div class="form-group">
            <label class="form-label" for="service">Service of Interest</label>
            <select class="form-select" id="service" name="service">
              <option value="">Select a service…</option>
              <option>Architecture & Engineering Consultancy</option>
              <option>Interior Design</option>
              <option>Project Management</option>
              <option>Feasibility Studies</option>
              <option>Land & Building Surveying</option>
              <option>Condition Survey & Defect Analysis</option>
              <option>Quantity Surveying</option>
              <option>Building Documentation</option>
              <option>Multiple Services</option>
            </select>
          </div>
          <div class="form-group">
            <label class="form-label" for="budget">Estimated Project Budget</label>
            <select class="form-select" id="budget" name="budget">
              <option value="">Prefer not to say</option>
              <option>Below ₦10 million</option>
              <option>₦10m – ₦50m</option>
              <option>₦50m – ₦200m</option>
              <option>₦200m – ₦1 billion</option>
              <option>Above ₦1 billion</option>
            </select>
          </div>
          <div class="form-group">
            <label class="form-label" for="message">Project Brief *</label>
            <textarea class="form-textarea" id="message" name="message" placeholder="Tell us about your project — location, scope, timeline and any specific requirements…" required></textarea>
          </div>
          <div class="form-submit">
            <button type="submit" class="btn-fill" style="width:100%;justify-content:center">
              Send Enquiry
              <svg width="14" height="10" viewBox="0 0 14 10" fill="none"><path d="M9 1L13 5M13 5L9 9M13 5H1" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/></svg>
            </button>
          </div>
        </form>
      </div>

    </div>
  </div>
</section>

<?php include 'includes/footer.php'; ?>
<script src="assets/js/main.js"></script>
</body>
</html>
