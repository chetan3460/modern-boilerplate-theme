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

  bindEvents = () => { };

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
    const nav = document.getElementById('navigation-mobile') || document.getElementById('navigation');
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
    const toggle = dropdown?.querySelector('.language-toggle');
    const menu = dropdown?.querySelector('.language-menu');
    const options = dropdown?.querySelectorAll('.language-option');

    if (!dropdown || !toggle || !menu) return;

    // Toggle dropdown
    toggle.addEventListener('click', (e) => {
      e.preventDefault();
      const isOpen = toggle.getAttribute('aria-expanded') === 'true';
      
      toggle.setAttribute('aria-expanded', !isOpen);
      toggle.querySelector('svg').style.transform = isOpen ? 'rotate(0deg)' : 'rotate(180deg)';
      
      if (isOpen) {
        menu.classList.add('opacity-0', 'invisible');
        menu.classList.remove('opacity-100', 'visible');
      } else {
        menu.classList.remove('opacity-0', 'invisible');
        menu.classList.add('opacity-100', 'visible');
      }
    });

    // Language selection
    options?.forEach(option => {
      option.addEventListener('click', (e) => {
        e.preventDefault();
        const selectedText = option.textContent.trim();
        const langCode = selectedText === 'English' ? 'EN' : 
                        selectedText === 'Español' ? 'ES' :
                        selectedText === 'Français' ? 'FR' :
                        selectedText === 'Deutsch' ? 'DE' : 'EN';
        
        toggle.querySelector('.language-text').textContent = langCode;
        
        // Close dropdown
        toggle.setAttribute('aria-expanded', 'false');
        toggle.querySelector('svg').style.transform = 'rotate(0deg)';
        menu.classList.add('opacity-0', 'invisible');
        menu.classList.remove('opacity-100', 'visible');
      });
    });

    // Close on click outside
    document.addEventListener('click', (e) => {
      if (!dropdown.contains(e.target)) {
        toggle.setAttribute('aria-expanded', 'false');
        toggle.querySelector('svg').style.transform = 'rotate(0deg)';
        menu.classList.add('opacity-0', 'invisible');
        menu.classList.remove('opacity-100', 'visible');
      }
    });

    // Close on Escape key
    document.addEventListener('keydown', (e) => {
      if (e.key === 'Escape' && toggle.getAttribute('aria-expanded') === 'true') {
        toggle.setAttribute('aria-expanded', 'false');
        toggle.querySelector('svg').style.transform = 'rotate(0deg)';
        menu.classList.add('opacity-0', 'invisible');
        menu.classList.remove('opacity-100', 'visible');
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

        const submenu = li.querySelector(':scope > .submenu') || li.querySelector(':scope > .mega-panel');
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

  // Mega menu hover/focus for desktop, click for mobile
  setupMegaMenus = () => {
    const nav = document.getElementById('navigation');
    if (!nav) return;

    // Ensure no lingering 'open' classes affect desktop hover-only behavior
    const clearOpensIfDesktop = () => {
      const isDesktop = window.matchMedia('(min-width: 992px)').matches;
      if (!isDesktop) return;
      nav.querySelectorAll('li.open').forEach((li) => li.classList.remove('open'));
    };
    clearOpensIfDesktop();
    window.addEventListener('resize', () => {
      clearOpensIfDesktop();
    }, { passive: true });

    const parents = nav.querySelectorAll(':scope > ul > li.has-mega');
    parents.forEach((li) => {
      const trigger = li.querySelector(':scope > a');
      const panel = li.querySelector(':scope > .mega-panel');
      if (!trigger || !panel) return;

      // CSS-centered mega panel (old JS centering disabled)
      const positionCard = () => {
        /* no-op: handled via CSS fixed centering */
      };

      // CSS-centered mega panel; previous JS fixed positioning disabled
      const fixPanelToViewport = () => {
        /* no-op: handled via CSS */
      };

      const open = () => {
        li.classList.add('open');
        trigger.setAttribute('aria-expanded', 'true');
        fixPanelToViewport();
        requestAnimationFrame(positionCard);
      };
      const close = () => {
        li.classList.remove('open');
        trigger.setAttribute('aria-expanded', 'false');
        // Reset any fixed positioning applied to the panel
        panel.style.position = '';
        panel.style.left = '';
        panel.style.right = '';
        panel.style.top = '';
        panel.style.width = '';
        panel.style.zIndex = '';
      };

      // Desktop hover/focus (devices that support hover)
      // Use pure CSS (:hover / :focus-within) for show/hide. JS centering is disabled.
      // Keep a lightweight listener to reposition if needed in the future (currently no-op).
      const prefersHover = window.matchMedia('(hover: hover)').matches;
      if (prefersHover) {
        // No show/hide via JS — CSS controls visibility on hover
        // Optional: listen to resize/scroll in case future logic needs it
        const onResize = () => { /* no-op */ };
        const onScroll = () => { /* no-op */ };
        window.addEventListener('resize', onResize, { passive: true });
        window.addEventListener('scroll', onScroll, { passive: true });
      }

      // Mobile/tablet: toggle on click (accordion: only one open at a time)
      trigger.addEventListener('click', (e) => {
        const isDesktop = window.matchMedia('(min-width: 992px)').matches;
        if (!isDesktop) {
          e.preventDefault();

          // Close any other open mega menus first
          const openItems = nav.querySelectorAll(':scope > ul > li.has-mega.open');
          openItems.forEach((item) => {
            if (item === li) return;
            const otherTrigger = item.querySelector(':scope > a');
            if (otherTrigger) otherTrigger.setAttribute('aria-expanded', 'false');
            item.classList.remove('open');
          });

          // Toggle current
          if (li.classList.contains('open')) {
            close();
          } else {
            open();
          }
        }
      });

      // Escape closes when focused inside
      li.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') {
          close();
          trigger.focus();
        }
      });
    });
  };
}
