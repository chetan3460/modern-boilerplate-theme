import { gsap } from 'gsap';

export default class StatsCounter {
  constructor(element) {
    this.el = element;
    this.counters = this.el.querySelectorAll('.stats-counter');
    this.animated = false;

    this.init();
  }

  init() {
    if (this.counters.length === 0) return;

    this.setupObserver();
  }

  setupObserver() {
    const observer = new IntersectionObserver(
      (entries) => {
        entries.forEach((entry) => {
          if (entry.isIntersecting && !this.animated) {
            this.animateCounters();
            this.animated = true;
            observer.unobserve(entry.target);
          }
        });
      },
      {
        threshold: 0.3,
        rootMargin: '0px 0px -100px 0px',
      }
    );

    observer.observe(this.el);
  }

  animateCounters() {
    this.counters.forEach((counter) => {
      const targetText = counter.getAttribute('data-target');
      const target = parseFloat(targetText);
      const duration = parseInt(counter.getAttribute('data-duration'), 10) || 2000;
      const decimals = parseInt(counter.getAttribute('data-decimals'), 10) || 0;

      if (!isNaN(target) && !counter.classList.contains('animated')) {
        counter.classList.add('animated');
        this.animateCounter(counter, target, targetText, duration, decimals);
      }
    });
  }

  animateCounter(element, target, targetText, duration, decimals = 0) {
    // Get the original text to preserve symbols
    const originalText = element.getAttribute('data-original-text') || element.textContent;

    // Extract prefix and suffix symbols
    const numericMatch = originalText.match(/([^0-9.]*)([0-9.]+)([^0-9.]*)/);
    const prefix = numericMatch ? numericMatch[1] : '';
    const suffix = numericMatch ? numericMatch[3] : '';

    // Build an odometer-like structure and animate each digit vertically
    // Use targetText to preserve leading zeros, fallback to formatted number
    const formatted = targetText && /^0\d+$/.test(targetText) ? targetText : (decimals > 0 ? Number(target).toFixed(decimals) : Math.round(target).toString());

    // Ensure tabular numbers for equal-width digits
    element.style.fontVariantNumeric = 'tabular-nums';
    element.style.display = 'flex';
    element.style.alignItems = 'center';       // vertical center
    element.style.justifyContent = 'center';
    // Measure digit height/width
    const probe = document.createElement('span');
    probe.textContent = '0';
    probe.style.visibility = 'hidden';
    probe.style.position = 'absolute';
    probe.style.whiteSpace = 'pre';
    element.appendChild(probe);
    const rect = probe.getBoundingClientRect();
    const digitHeight = Math.ceil(rect.height || parseFloat(window.getComputedStyle(probe).lineHeight) || 24);
    const digitWidth = Math.ceil(rect.width || 12);
    element.removeChild(probe);

    // Clear and rebuild layout
    element.textContent = '';

    // Add prefix if exists
    if (prefix) {
      const prefixSpan = document.createElement('span');
      prefixSpan.className = 'counter-prefix';
      prefixSpan.textContent = prefix;
      element.appendChild(prefixSpan);
    }

    const groups = [];
    [...formatted].forEach((ch) => {
      if (ch === '.' || ch === ',') {
        const staticSpan = document.createElement('span');
        staticSpan.className = 'digit-static';
        staticSpan.textContent = ch;
        element.appendChild(staticSpan);
        return;
      }

      const group = document.createElement('span');
      group.className = 'digit-group';
      group.style.position = 'relative';
      group.style.display = 'inline-block';
      group.style.overflow = 'hidden';
      group.style.height = `${digitHeight}px`;
      group.style.width = `${digitWidth}px`;

      const strip = document.createElement('span');
      strip.className = 'digit-strip';
      strip.style.display = 'flex';
      strip.style.flexDirection = 'column';
      strip.style.lineHeight = '1';
      strip.style.willChange = 'transform';

      for (let i = 0; i < 10; i += 1) {
        const d = document.createElement('span');
        d.textContent = i.toString();
        d.style.display = 'block';
        d.style.height = `${digitHeight}px`;
        d.style.width = `${digitWidth}px`;
        strip.appendChild(d);
      }

      group.appendChild(strip);
      element.appendChild(group);

      const digit = parseInt(ch, 10);
      groups.push({ strip, digit: Number.isNaN(digit) ? 0 : digit });
    });

    // Add suffix if exists
    if (suffix) {
      const suffixSpan = document.createElement('span');
      suffixSpan.className = 'counter-suffix';
      suffixSpan.textContent = suffix;
      element.appendChild(suffixSpan);
    }

    const seconds = Math.max(0.2, duration / 1000);

    // Animate each digit with a slight stagger for a smoother rolling feel
    groups.forEach((g, idx) => {
      gsap.to(g.strip, {
        y: -digitHeight * g.digit,
        duration: seconds,
        ease: 'power2.out',
        delay: idx * 0.05,
      });
    });
  }

  destroy() {
    // Clean up if needed
  }
}
