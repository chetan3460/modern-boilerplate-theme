


import { forceHMRReload } from './utils.js';
import GSAPAnimations from './components/GSAPAnimations.js';
import DynamicImports from './components/DynamicImports.js';
import './components/NewsListingDropdowns.js';
import './cookie-consent.js';
// import './sw-register.js'; // Service Worker removed - not necessary for this site

import { gsap } from 'gsap';
import { ScrollTrigger } from 'gsap/ScrollTrigger';

gsap.registerPlugin(ScrollTrigger);

// Lazy load ScrollSmoother for better initial page load
let ScrollSmootherModule = null;
forceHMRReload();
export default new (class App {
  constructor() {
    this.setDomMap();
    // Run domReady after DOM is parsed (also handle already-loaded state)
    if (document.readyState === 'loading') {
      document.addEventListener('DOMContentLoaded', () => this.domReady());
    } else {
      this.domReady();
    }
  }

  domReady = () => {
    this.bindEvents();

    // Ensure body can scroll on mobile by default
    document.body.classList.remove('menu-open');
    document.body.style.overflow = '';

    // Initialize mobile filter bridge
    // new MobileFilterBridge();

    // Initialize dynamic modules
    new DynamicImports();
    new GSAPAnimations();

    // Initialize ScrollSmoother after page is interactive (lazy loaded)
    // This saves ~134KB from initial bundle
    if (document.readyState === 'complete') {
      this.initScrollSmoother();
    } else {
      window.addEventListener('load', () => {
        // Delay slightly to prioritize other tasks
        requestIdleCallback(() => this.initScrollSmoother(), { timeout: 2000 });
      });
    }

    // Ensure scroll remains enabled on viewport changes
    const restoreScroll = () => {
      document.body.classList.remove('menu-open');
      document.body.style.overflow = '';
    };
    window.addEventListener('resize', restoreScroll, { passive: true });
    window.addEventListener('orientationchange', restoreScroll, { passive: true });

    // Initialize back to top
    this.initBackToTop();
  };

  setDomMap = () => {
    this.win = window;
    this.html = document.documentElement;
    this.body = document.body;
    this.wrapper = document.querySelector('#smooth-wrapper');
    this.content = document.querySelector('#smooth-content');
    this.header = document.querySelector('header');
    this.footer = document.querySelector('footer');
  };

  bindEvents = () => {
    let retryCount = 0;
    const maxRetries = 30; // Max 30 frames (~500ms at 60fps)
  };

  initScrollSmoother = async () => {
    // Dynamically import ScrollSmoother only when needed
    if (!ScrollSmootherModule) {
      try {
        const module = await import('gsap/ScrollSmoother');
        ScrollSmootherModule = module.ScrollSmoother;
        gsap.registerPlugin(ScrollSmootherModule);
      } catch (error) {
        console.warn('Failed to load ScrollSmoother:', error);
        return; // Graceful degradation - site works without smooth scrolling
      }
    }

    this.smoother = ScrollSmootherModule.create({
      wrapper: '#smooth-wrapper',
      content: '#smooth-content',
      smooth: 2.5,
      effects: true,
      smoothTouch: 0.1,
      // normalizeScroll causes issues in production build - disabled
      // normalizeScroll: true,
      ignoreMobileResize: true,

      ignore: '.gsap-ignore, #categories-dropdown, .dropdown-menu, [data-component="MobileCategoriesDropdown"], .mobile-dropdown-scrollable, .mobile-filter-overlay-wrapper, .accordion-trigger, .accordion-panel, #cookie-consent-banner, .cookie-consent-banner'
    });

    // Make smoother instance globally accessible
    window.app = window.app || {};
    window.app.smoother = this.smoother;


    // Chromium pixel-snapping to avoid fractional rounding seams during smooth scrolling (accounts for devicePixelRatio)
    const isChromium = /Chrome|Edg/.test(navigator.userAgent) && !/OPR|SamsungBrowser/.test(navigator.userAgent);
    if (isChromium && this.content) {
      const el = this.content;
      const dpr = window.devicePixelRatio || 1;
      const snap = (y) => Math.round(y * dpr) / dpr;
      let frameCounter = 0;
      let lastTransform = '';

      const tick = () => {
        // Throttle to every 3rd frame for better performance
        frameCounter++;
        if (frameCounter % 3 !== 0) return;

        const currentTransform = el.style.transform;

        // Skip if transform hasn't changed since last check
        if (currentTransform === lastTransform) return;
        lastTransform = currentTransform;

        if (!currentTransform || currentTransform === 'none') return;

        let newTransform = null;

        // Only handle translateY for better performance (most common case)
        const translateYMatch = /translateY\(([^)]+)\)/.exec(currentTransform);
        if (translateYMatch) {
          const y = parseFloat(translateYMatch[1]);
          const yr = snap(y);
          if (Number.isFinite(y) && Math.abs(y - yr) > 0.1) { // Increased threshold
            newTransform = currentTransform.replace(translateYMatch[0], `translateY(${yr}px)`);
          }
        } else {
          // Fallback for translate3d (less common)
          const translate3dMatch = /translate3d\(\s*([^,]+),\s*([^,]+),\s*([^)]+)\)/.exec(currentTransform);
          if (translate3dMatch) {
            const x = translate3dMatch[1];
            const y = parseFloat(translate3dMatch[2]);
            const z = translate3dMatch[3];
            const yr = snap(y);
            if (Number.isFinite(y) && Math.abs(y - yr) > 0.1) {
              newTransform = `translate3d(${x}, ${yr}px, ${z})`;
            }
          }
        }

        if (newTransform) {
          el.style.transform = newTransform;
        }
      };

      // Keep a reference in case we need to remove later
      this._snapTickFn = tick;
      gsap.ticker.add(this._snapTickFn);
    }
  };



  initBackToTop = () => {
    // Select the back-to-top box
    const box = document.querySelector(".scrollToTop");

    if (!box) {
      return;
    }

    const water = box.querySelector(".water");

    // Simple scroll handler
    const handleScroll = () => {
      // Get scroll position - use regular scroll first
      const scrollPosition = window.scrollY || document.documentElement.scrollTop;
      const documentHeight = document.documentElement.scrollHeight - window.innerHeight;

      // Show/hide the back to top button
      const shouldShow = scrollPosition >= 200;

      // Calculate percentage for water animation
      const percent = documentHeight > 0
        ? Math.min(Math.floor((scrollPosition / documentHeight) * 100), 100)
        : 0;

      // Move the "water" element
      if (water) {
        water.style.transform = `translate(0, ${100 - percent}%)`;
      }

      // Show or hide the box
      if (shouldShow) {
        box.classList.add('active-progress');
        box.style.opacity = '1';
      } else {
        box.classList.remove('active-progress');
        box.style.opacity = '0';
      }
    };

    // Listen to scroll events
    window.addEventListener('scroll', handleScroll);

    // Initial call
    handleScroll();

    // Click handler
    box.addEventListener('click', (e) => {
      e.preventDefault();

      window.scrollTo({
        top: 0,
        behavior: 'smooth'
      });
    });
  }


})();
