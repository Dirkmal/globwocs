/* ═══════════════════════════════════════════════════════════════
   GLOBWOCS — MAIN.JS
   ═══════════════════════════════════════════════════════════════ */

/* ─── CUSTOM CURSOR ──────────────────────────────────────────── */
(function() {
  const dot  = document.getElementById('cursor');
  const ring = document.getElementById('cursor-ring');
  if (!dot || !ring) return;

  let mx = -100, my = -100, rx = -100, ry = -100;
  let raf;

  document.addEventListener('mousemove', e => {
    mx = e.clientX;
    my = e.clientY;
    dot.style.left  = mx + 'px';
    dot.style.top   = my + 'px';
  });

  function lerp(a, b, t) { return a + (b - a) * t; }

  function followRing() {
    rx = lerp(rx, mx, 0.1);
    ry = lerp(ry, my, 0.1);
    ring.style.left = rx + 'px';
    ring.style.top  = ry + 'px';
    raf = requestAnimationFrame(followRing);
  }
  followRing();
})();

/* ─── NAVIGATION ─────────────────────────────────────────────── */
(function() {
  const nav = document.getElementById('nav');
  if (!nav) return;

  window.addEventListener('scroll', () => {
    nav.classList.toggle('scrolled', window.scrollY > 60);
  }, { passive: true });

  const burger = document.getElementById('nav-burger');
  const menu   = document.getElementById('mobile-menu');
  const closeBtn = document.getElementById('menu-close');

  if (burger && menu) {
    burger.addEventListener('click', () => {
      menu.classList.add('open');
      document.body.style.overflow = 'hidden';
    });
    if (closeBtn) closeBtn.addEventListener('click', () => {
      menu.classList.remove('open');
      document.body.style.overflow = '';
    });
    menu.querySelectorAll('a').forEach(a => a.addEventListener('click', () => {
      menu.classList.remove('open');
      document.body.style.overflow = '';
    }));
  }
})();

/* ─── HERO SLIDESHOW ─────────────────────────────────────────── */
(function() {
  const slides = document.querySelectorAll('.hero__slide');
  const dots   = document.querySelectorAll('.hero__dot');
  const counter = document.getElementById('slide-counter');
  if (!slides.length) return;

  let current = 0;
  let timer;

  function goto(n) {
    slides[current].classList.remove('active');
    dots[current]?.classList.remove('active');
    current = (n + slides.length) % slides.length;
    slides[current].classList.add('active');
    dots[current]?.classList.add('active');
    if (counter) counter.textContent = String(current + 1).padStart(2, '0');
  }

  function autoplay() {
    timer = setInterval(() => goto(current + 1), 5500);
  }

  dots.forEach((d, i) => d.addEventListener('click', () => {
    clearInterval(timer);
    goto(i);
    autoplay();
  }));

  autoplay();
  goto(0);
})();

/* ─── SCROLL REVEALS ─────────────────────────────────────────── */
(function() {
  const els = document.querySelectorAll('.reveal, .reveal-left, .reveal-right, .reveal-scale');
  if (!els.length || !('IntersectionObserver' in window)) {
    els.forEach(el => el.classList.add('in'));
    return;
  }
  const obs = new IntersectionObserver(entries => {
    entries.forEach(e => {
      if (e.isIntersecting) {
        e.target.classList.add('in');
        obs.unobserve(e.target);
      }
    });
  }, { threshold: 0.12 });
  els.forEach(el => obs.observe(el));
})();

/* ─── PAGE TRANSITIONS ───────────────────────────────────────── */
(function() {
  const overlay = document.getElementById('page-transition');
  if (!overlay) return;

  // Fade in on load
  overlay.classList.add('leaving');

  document.querySelectorAll('a[href]').forEach(link => {
    const href = link.getAttribute('href');
    if (!href || href.startsWith('#') || href.startsWith('mailto:') || href.startsWith('tel:') || href.includes('://')) return;
    link.addEventListener('click', e => {
      e.preventDefault();
      overlay.classList.remove('leaving');
      overlay.classList.add('entering');
      setTimeout(() => { window.location.href = href; }, 500);
    });
  });
})();

/* ─── LIGHTBOX ───────────────────────────────────────────────── */
(function() {
  const lb       = document.getElementById('lightbox');
  if (!lb) return;
  const lbImg    = document.getElementById('lb-img');
  const lbTitle  = document.getElementById('lb-title');
  const lbDetail = document.getElementById('lb-detail');
  const lbCounter= document.getElementById('lb-counter');
  const lbClose  = lb.querySelector('.lb-close');
  const lbPrev   = lb.querySelector('.lb-prev');
  const lbNext   = lb.querySelector('.lb-next');

  let images = [], current = 0;

  function open(project) {
    images = project.images || [];
    current = 0;
    show();
    lb.classList.add('open');
    document.body.style.overflow = 'hidden';
  }

  function close() {
    lb.classList.remove('open');
    document.body.style.overflow = '';
  }

  function show() {
    if (!images.length) return;
    const img = images[current];
    lbImg.style.opacity = '0';
    setTimeout(() => {
      lbImg.src = img.src;
      lbTitle.textContent  = img.name  || '';
      lbDetail.textContent = img.detail|| '';
      if (lbCounter) lbCounter.textContent = (current+1) + ' / ' + images.length;
      lbImg.style.opacity = '1';
    }, 150);
  }

  function prev() { current = (current - 1 + images.length) % images.length; show(); }
  function next() { current = (current + 1) % images.length; show(); }

  if (lbClose) lbClose.addEventListener('click', close);
  if (lbPrev)  lbPrev.addEventListener('click', prev);
  if (lbNext)  lbNext.addEventListener('click', next);
  lb.addEventListener('click', e => { if (e.target === lb) close(); });
  document.addEventListener('keydown', e => {
    if (!lb.classList.contains('open')) return;
    if (e.key === 'Escape') close();
    if (e.key === 'ArrowLeft') prev();
    if (e.key === 'ArrowRight') next();
  });

  // Attach to masonry items
  document.querySelectorAll('.masonry-item[data-images]').forEach(item => {
    item.addEventListener('click', () => {
      const data = JSON.parse(item.dataset.images || '[]');
      open({ images: data });
    });
  });

  // Expose for PHP-injected inline handlers
  window.globwocs = window.globwocs || {};
  window.globwocs.openLightbox = open;
})();

/* ─── PROJECT FILTER ─────────────────────────────────────────── */
(function() {
  const btns  = document.querySelectorAll('.filter-btn');
  const items = document.querySelectorAll('.masonry-item[data-cat]');
  if (!btns.length) return;

  btns.forEach(btn => {
    btn.addEventListener('click', () => {
      btns.forEach(b => b.classList.remove('active'));
      btn.classList.add('active');
      const cat = btn.dataset.cat;
      items.forEach(item => {
        if (cat === 'all' || item.dataset.cat === cat) {
          item.classList.remove('hidden');
        } else {
          item.classList.add('hidden');
        }
      });
    });
  });
})();

/* ─── COUNTER ANIMATION ──────────────────────────────────────── */
(function() {
  const els = document.querySelectorAll('[data-count]');
  if (!els.length || !('IntersectionObserver' in window)) return;

  const obs = new IntersectionObserver(entries => {
    entries.forEach(e => {
      if (!e.isIntersecting) return;
      const el = e.target;
      const target = parseInt(el.dataset.count, 10);
      const suffix = el.dataset.suffix || '';
      const dur = 1800;
      const start = performance.now();
      function tick(now) {
        const p = Math.min((now - start) / dur, 1);
        const ease = 1 - Math.pow(1 - p, 3);
        el.textContent = Math.round(ease * target) + suffix;
        if (p < 1) requestAnimationFrame(tick);
      }
      requestAnimationFrame(tick);
      obs.unobserve(el);
    });
  }, { threshold: 0.5 });
  els.forEach(el => obs.observe(el));
})();

/* ─── CONTACT FORM ───────────────────────────────────────────── */
(function() {
  const form = document.getElementById('contact-form');
  if (!form) return;
  form.addEventListener('submit', async e => {
    e.preventDefault();
    const btn = form.querySelector('[type="submit"]');
    const orig = btn.textContent;
    btn.textContent = 'Sending…';
    btn.disabled = true;
    try {
      const res = await fetch('contact-handler.php', {
        method: 'POST',
        body: new FormData(form)
      });
      const json = await res.json();
      if (json.ok) {
        showToast('Message sent successfully');
        form.reset();
      } else {
        showToast(json.error || 'Failed to send. Please try again.');
      }
    } catch {
      showToast('Network error. Please try again.');
    }
    btn.textContent = orig;
    btn.disabled = false;
  });
})();

function showToast(msg) {
  let t = document.getElementById('toast');
  if (!t) {
    t = document.createElement('div');
    t.id = 'toast';
    t.className = 'toast';
    document.body.appendChild(t);
  }
  t.textContent = msg;
  t.classList.add('show');
  setTimeout(() => t.classList.remove('show'), 4000);
}

/* ─── PARALLAX HERO BG ───────────────────────────────────────── */
(function() {
  const hero = document.querySelector('.hero');
  if (!hero) return;
  window.addEventListener('scroll', () => {
    const y = window.scrollY;
    if (y < window.innerHeight) {
      const slides = hero.querySelectorAll('.hero__slide.active');
      slides.forEach(s => { s.style.transform = `translateY(${y * 0.25}px)`; });
    }
  }, { passive: true });
})();
