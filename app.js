// =============================================
// VEDAWAYS — Main JavaScript
// =============================================

document.addEventListener('DOMContentLoaded', () => {
  initNavbar();
  initParticles();
  initOnboarding();
  initFilters();
  initScrollReveal();
  initBudgetSlider();
  initForms();
  initHamburger();
});

// ===== NAVBAR =====
function initNavbar() {
  const navbar = document.getElementById('navbar');
  window.addEventListener('scroll', () => {
    if (window.scrollY > 60) navbar.classList.add('scrolled');
    else navbar.classList.remove('scrolled');
  });
}

// ===== HERO PARTICLES =====
function initParticles() {
  const container = document.getElementById('particles');
  if (!container) return;
  const svg = document.createElementNS('http://www.w3.org/2000/svg', 'svg');
  svg.setAttribute('width', '100%');
  svg.setAttribute('height', '100%');
  svg.style.position = 'absolute';
  svg.style.inset = '0';
  svg.style.opacity = '0.4';

  for (let i = 0; i < 40; i++) {
    const circle = document.createElementNS('http://www.w3.org/2000/svg', 'circle');
    const x = Math.random() * 100;
    const y = Math.random() * 100;
    const r = Math.random() * 2 + 0.5;
    const dur = Math.random() * 8 + 6;
    const delay = Math.random() * 5;
    circle.setAttribute('cx', `${x}%`);
    circle.setAttribute('cy', `${y}%`);
    circle.setAttribute('r', r);
    circle.setAttribute('fill', Math.random() > 0.5 ? '#C9A84C' : '#E07A52');
    circle.style.animation = `floatDot ${dur}s ease-in-out ${delay}s infinite alternate`;
    svg.appendChild(circle);
  }

  // Add mandala decoration
  const deco = document.createElementNS('http://www.w3.org/2000/svg', 'g');
  deco.style.opacity = '0.06';
  for (let i = 0; i < 12; i++) {
    const line = document.createElementNS('http://www.w3.org/2000/svg', 'line');
    const angle = (i / 12) * Math.PI * 2;
    const cx = 75, cy = 30, len = 15;
    line.setAttribute('x1', `${cx}%`);
    line.setAttribute('y1', `${cy}%`);
    line.setAttribute('x2', `${cx + Math.cos(angle) * len}%`);
    line.setAttribute('y2', `${cy + Math.sin(angle) * len}%`);
    line.setAttribute('stroke', '#C9A84C');
    line.setAttribute('stroke-width', '1');
    deco.appendChild(line);
  }
  svg.appendChild(deco);
  container.appendChild(svg);

  const style = document.createElement('style');
  style.textContent = `@keyframes floatDot { 0% { transform: translateY(0) scale(1); opacity: 0.3; } 100% { transform: translateY(-30px) scale(1.5); opacity: 0.8; } }`;
  document.head.appendChild(style);
}

// ===== ONBOARDING STEPS =====
let currentStep = 1;
const totalSteps = 4;

function nextStep(step) {
  const currentEl = document.querySelector(`.step[data-step="${step}"]`);
  const nextEl = document.querySelector(`.step[data-step="${step + 1}"]`);
  if (!nextEl) return;

  currentEl.classList.remove('active');
  nextEl.classList.add('active');
  currentStep = step + 1;
  updateProgress();
}

function prevStep(step) {
  const currentEl = document.querySelector(`.step[data-step="${step}"]`);
  const prevEl = document.querySelector(`.step[data-step="${step - 1}"]`);
  if (!prevEl) return;

  currentEl.classList.remove('active');
  prevEl.classList.add('active');
  currentStep = step - 1;
  updateProgress();
}

function updateProgress() {
  const fill = document.getElementById('progressFill');
  if (fill) fill.style.width = `${(currentStep / totalSteps) * 100}%`;
}

function initOnboarding() {
  const form = document.getElementById('planForm');
  if (!form) return;
  form.addEventListener('submit', (e) => {
    e.preventDefault();
    const step4 = form.querySelector('[data-step="4"]');
    const step5 = form.querySelector('[data-step="5"]');
    if (step4 && step5) {
      step4.classList.remove('active');
      step5.classList.add('active');
      const fill = document.getElementById('progressFill');
      if (fill) fill.style.width = '100%';
      // Submit to backend
      submitPlanForm(form);
    }
  });
}

async function submitPlanForm(form) {
  const data = {
    interest: form.querySelector('[name="interest"]:checked')?.value,
    duration: form.querySelector('[name="duration"]:checked')?.value,
    budget: document.getElementById('budget')?.value,
    name: document.getElementById('fname')?.value,
    email: document.getElementById('femail')?.value,
    phone: document.getElementById('fphone')?.value,
    nationality: document.getElementById('fnationality')?.value,
  };
  try {
    await fetch('/api/inquiry', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify(data),
    });
  } catch (err) {
    console.log('Form submitted (backend unavailable in dev)');
  }
}

// ===== BUDGET SLIDER =====
function initBudgetSlider() {
  const slider = document.getElementById('budget');
  const display = document.getElementById('budgetVal');
  if (!slider || !display) return;

  slider.addEventListener('input', () => {
    const val = parseInt(slider.value);
    display.textContent = val.toLocaleString('en-IN');
    const pct = ((val - slider.min) / (slider.max - slider.min)) * 100;
    slider.style.background = `linear-gradient(to right, var(--terra) 0%, var(--terra) ${pct}%, var(--cream-dark) ${pct}%)`;
  });
}

// ===== JOURNEY FILTERS =====
function initFilters() {
  const btns = document.querySelectorAll('.filter-btn');
  const cards = document.querySelectorAll('.journey-card');

  btns.forEach(btn => {
    btn.addEventListener('click', () => {
      btns.forEach(b => b.classList.remove('active'));
      btn.classList.add('active');
      const filter = btn.dataset.filter;

      cards.forEach(card => {
        if (filter === 'all') {
          card.classList.remove('hidden');
        } else {
          const cats = (card.dataset.category || '').split(' ');
          card.classList.toggle('hidden', !cats.includes(filter));
        }
      });
    });
  });
}

// ===== SCROLL REVEAL =====
function initScrollReveal() {
  const elements = document.querySelectorAll(
    '.section-header, .plan-text, .plan-form-wrap, .journey-card, .why-feat, .dest-card, .testi-card, .about-image-wrap, .about-text, .contact-text, .contact-form, .why-text, .why-features'
  );

  elements.forEach(el => el.classList.add('reveal'));

  const observer = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
      if (entry.isIntersecting) {
        entry.target.classList.add('visible');
        observer.unobserve(entry.target);
      }
    });
  }, { threshold: 0.1, rootMargin: '0px 0px -50px 0px' });

  elements.forEach(el => observer.observe(el));
}

// ===== FORMS =====
function initForms() {
  const contactForm = document.getElementById('contactForm');
  if (contactForm) {
    contactForm.addEventListener('submit', async (e) => {
      e.preventDefault();
      const btn = contactForm.querySelector('button[type="submit"]');
      btn.textContent = 'Sending…';
      btn.disabled = true;
      try {
        const formData = new FormData(contactForm);
        await fetch('/api/contact', {
          method: 'POST',
          headers: { 'Content-Type': 'application/json' },
          body: JSON.stringify(Object.fromEntries(formData)),
        });
      } catch (err) {}
      setTimeout(() => {
        btn.textContent = 'Message Sent ✦';
        btn.style.background = 'var(--sage)';
      }, 800);
    });
  }
}

// ===== HAMBURGER =====
function initHamburger() {
  const hamburger = document.getElementById('hamburger');
  if (!hamburger) return;
  let open = false;

  hamburger.addEventListener('click', () => {
    open = !open;
    const navLinks = document.querySelector('.nav-links');
    const btnPlan = document.querySelector('.btn-plan-nav');
    if (navLinks) {
      navLinks.style.display = open ? 'flex' : '';
      navLinks.style.flexDirection = open ? 'column' : '';
      navLinks.style.position = open ? 'fixed' : '';
      navLinks.style.top = open ? '70px' : '';
      navLinks.style.left = open ? '0' : '';
      navLinks.style.right = open ? '0' : '';
      navLinks.style.background = open ? 'var(--charcoal)' : '';
      navLinks.style.padding = open ? '30px 40px' : '';
      navLinks.style.zIndex = open ? '999' : '';
      navLinks.style.gap = open ? '20px' : '';
    }
    if (btnPlan) {
      btnPlan.style.display = open ? 'block' : '';
    }
  });
}

// ===== SMOOTH SCROLL for all anchor links =====
document.querySelectorAll('a[href^="#"]').forEach(anchor => {
  anchor.addEventListener('click', function (e) {
    const target = document.querySelector(this.getAttribute('href'));
    if (target) {
      e.preventDefault();
      target.scrollIntoView({ behavior: 'smooth', block: 'start' });
    }
  });
});

// Make step functions globally accessible
window.nextStep = nextStep;
window.prevStep = prevStep;
