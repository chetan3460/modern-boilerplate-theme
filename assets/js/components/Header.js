export default class Header {
  constructor(element) {
    this.header = element;
    this.htmlBody = document.body;

    // Ensure DOM is ready before initializing
    if (document.readyState === 'loading') {
      document.addEventListener('DOMContentLoaded', () => this.init());
    } else {
      this.init();
    }
  }

  init() {
    this.bindEvents();
    this.stickyMenu();
    this.toggleMenu();
    this.markActiveMenu();
    this.languageDropdown();

    this.submenuToggle();
    this.setupMegaMenus();
  }

  bindEvents = () => {};

  //Sticky Menu
  stickyMenu = () => {
    let ticking = false;

    function windowScroll() {
      const navbar = document.getElementById('topnav');
      if (navbar) {
        navbar.classList.toggle('nav-sticky', window.scrollY >= 50);
      }
    }

    function onScroll() {
      if (!ticking) {
        requestAnimationFrame(() => {
          windowScroll();
          ticking = false;
        });
        ticking = true;
      }
    }

    window.addEventListener('scroll', onScroll, { passive: true });
  };

  /* Toggle Menu */
  toggleMenu = () => {
    const toggleBtn = document.getElementById('isToggle');
    const mobileCloseBtn = document.getElementById('mobileClose');
    const nav =
      document.getElementById('navigation-mobile') || document.getElementById('navigation');
    const overlay = document.getElementById('nav-overlay');

    // Add defensive check for required elements
    if (!toggleBtn) {
      return;
    }
    if (!nav) {
      return;
    }

    // Track if menu has been opened before (for first-time animations)
    let hasBeenOpened = false;

    const setOpen = (open) => {
      nav.classList.toggle('open', open);
      document.body.classList.toggle('menu-open', open);
      if (toggleBtn) {
        toggleBtn.classList.toggle('open', open);
        toggleBtn.setAttribute('aria-expanded', String(open));
      }
      if (mobileCloseBtn) {
        mobileCloseBtn.classList.toggle('open', open);
        mobileCloseBtn.setAttribute('aria-expanded', String(open));
      }
      if (overlay) overlay.classList.toggle('active', open);
      // Ensure no inline display interferes with transform animation
      nav.style.removeProperty('display');

      // Add first-time class only on first open
      if (open && !hasBeenOpened) {
        nav.classList.add('first-open');
        hasBeenOpened = true;
      }
    };

    // Ensure page is scrollable by default on load
    setOpen(false);

    // Auto-close mobile nav on desktop breakpoint to restore body scroll
    const closeOnDesktop = () => {
      const isDesktop = window.matchMedia('(min-width: 992px)').matches;
      if (isDesktop) setOpen(false);
    };
    window.addEventListener('resize', closeOnDesktop, { passive: true });
    window.addEventListener('orientationchange', closeOnDesktop, { passive: true });
    closeOnDesktop();

    if (toggleBtn && nav) {
      toggleBtn.addEventListener('click', (e) => {
        e.preventDefault();
        setOpen(!nav.classList.contains('open'));
      });

      // Also handle mobile close button
      if (mobileCloseBtn) {
        mobileCloseBtn.addEventListener('click', (e) => {
          e.preventDefault();
          setOpen(false);
        });
      }

      // Click outside (overlay) to close
      if (overlay) {
        overlay.addEventListener('click', () => setOpen(false));
      }

      // Close on Escape
      document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape' && nav.classList.contains('open')) {
          setOpen(false);
        }
      });
    }
  };

  // Language dropdown functionality
  languageDropdown = () => {
    const dropdown = document.querySelector('.language-dropdown');
    if (!dropdown) return;

    const toggle = dropdown.querySelector('.language-toggle');
    const menu = dropdown.querySelector('.language-menu');
    const options = dropdown.querySelectorAll('.language-option');

    if (!toggle || !menu) return;

    const langMap = {
      English: 'EN',
      Español: 'ES',
      Français: 'FR',
      Deutsch: 'DE',
    };

    const toggleDropdown = (isOpen) => {
      toggle.setAttribute('aria-expanded', String(isOpen));
      const svg = toggle.querySelector('svg');
      if (svg) svg.style.transform = isOpen ? 'rotate(180deg)' : 'rotate(0deg)';
      menu.classList.toggle('opacity-100', isOpen);
      menu.classList.toggle('visible', isOpen);
      menu.classList.toggle('opacity-0', !isOpen);
      menu.classList.toggle('invisible', !isOpen);
    };

    const closeDropdown = () => toggleDropdown(false);

    // Toggle on click
    toggle.addEventListener('click', (e) => {
      e.preventDefault();
      const isOpen = toggle.getAttribute('aria-expanded') === 'true';
      toggleDropdown(!isOpen);
    });

    // Language selection
    options.forEach((option) => {
      option.addEventListener('click', (e) => {
        e.preventDefault();
        const selectedText = option.textContent.trim();
        const langCode = langMap[selectedText] || 'EN';

        const langText = toggle.querySelector('.language-text');
        if (langText) langText.textContent = langCode;

        closeDropdown();
      });
    });

    // Close on click outside
    document.addEventListener('click', (e) => {
      if (!dropdown.contains(e.target)) closeDropdown();
    });

    // Close on Escape key
    document.addEventListener('keydown', (e) => {
      if (e.key === 'Escape' && toggle.getAttribute('aria-expanded') === 'true') {
        closeDropdown();
      }
    });
  };

  // Add 'active' class to the current menu item based on the URL (desktop + mobile)
  markActiveMenu() {
    const normalize = (p) => (p || '').replace(/\/$/, '');
    const currentPath = normalize(window.location.pathname);

    const menuLinks = document.querySelectorAll(
      '#navigation a.sub-menu-item, #navigation-mobile a.sub-menu-item'
    );

    menuLinks.forEach((link) => {
      const href = normalize(link.getAttribute('href'));
      if (!href) return;

      // Match URL
      const isActive = href === currentPath || href.endsWith(currentPath);
      if (!isActive) return;

      // Mark the link
      link.classList.add('active');

      // Mark ancestor <li> elements
      let li = link.closest('li');
      while (li) {
        li.classList.add('active');
        li = li.parentElement?.closest('li');
      }

      // Also activate direct parent UL if it's submenu
      const submenu = link.closest('ul.submenu');
      if (submenu) submenu.classList.add('active');

      // Note: Do not auto-open mobile submenus based on active page.
      // Opening is controlled solely by user click.
    });
  }

  // Submenu Toggle (mobile + desktop safety)
  // Toggle submenus on click for <=991px and for the dedicated mobile nav
  submenuToggle = () => {
    const navCandidates = [
      document.getElementById('navigation-mobile'),
      document.getElementById('navigation'),
    ].filter(Boolean);

    navCandidates.forEach((nav) => {
      nav.addEventListener('click', (e) => {
        // Support clicks on anchor OR arrow
        const li = e.target.closest('li.has-submenu');
        if (!li || !nav.contains(li)) return;
        const anchor = li.querySelector(':scope > a');
        const clickedArrow = e.target.closest('.menu-arrow');
        const clickedAnchor = anchor && anchor.contains(e.target);
        if (!clickedArrow && !clickedAnchor) return;

        // Only intercept on mobile/tablet, or when clicking inside the dedicated mobile nav
        const isMobileViewport = window.matchMedia('(max-width: 991px)').matches;
        const isMobileNav = nav.id === 'navigation-mobile';
        if (!isMobileViewport && !isMobileNav) return;

        const submenu =
          li.querySelector(':scope > .submenu') || li.querySelector(':scope > .mega-panel');
        if (!submenu) return;

        e.preventDefault();
        // Close siblings
        nav.querySelectorAll('li.has-submenu.open').forEach((item) => {
          if (item !== li) {
            item.classList.remove('open');
            const s = item.querySelector(':scope > .submenu, :scope > .mega-panel');
            if (s) s.classList.remove('open');
          }
        });
        li.classList.toggle('open');
        submenu.classList.toggle('open');
      });
    });
  };

  // Mega menu: CSS-powered hover on desktop, click toggle on mobile
  setupMegaMenus = () => {
    const nav = document.getElementById('navigation');
    if (!nav) return;

    const isDesktop = () => window.matchMedia('(min-width: 992px)').matches;

    // Clear open classes on desktop resize
    const clearOpensIfDesktop = () => {
      if (isDesktop()) {
        nav.querySelectorAll('li.open').forEach((li) => li.classList.remove('open'));
      }
    };

    clearOpensIfDesktop();
    window.addEventListener('resize', clearOpensIfDesktop, { passive: true });

    const megaItems = nav.querySelectorAll(':scope > ul > li.has-mega');

    megaItems.forEach((li) => {
      const trigger = li.querySelector(':scope > a');
      if (!trigger) return;

      const toggleMega = (open) => {
        li.classList.toggle('open', open);
        trigger.setAttribute('aria-expanded', String(open));
      };

      // Mobile/tablet: click to toggle (accordion behavior)
      trigger.addEventListener('click', (e) => {
        if (isDesktop()) return; // Let CSS handle desktop hover

        e.preventDefault();
        const isOpen = li.classList.contains('open');

        // Close other mega menus
        megaItems.forEach((item) => {
          if (item !== li && item.classList.contains('open')) {
            item.classList.remove('open');
            const otherTrigger = item.querySelector(':scope > a');
            if (otherTrigger) otherTrigger.setAttribute('aria-expanded', 'false');
          }
        });

        toggleMega(!isOpen);
      });

      // Escape key closes mega menu
      li.addEventListener('keydown', (e) => {
        if (e.key === 'Escape' && li.classList.contains('open')) {
          toggleMega(false);
          trigger.focus();
        }
      });
    });
  };
}
