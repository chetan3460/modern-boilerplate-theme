/**
 * AccordionManager - Centralized accordion handling
 * Manages product card accordions and filter accordions
 */
export class AccordionManager {
  constructor() {
    this.globalBound = false;
  }

  /**
   * Setup global accordion handler for all accordions (one-time setup)
   */
  setupGlobal() {
    if (this.globalBound) return;
    this.globalBound = true;
    window._globalProductAccordionsBound = true;

    const esc = (s) => window.CSS?.escape ? CSS.escape(s) : String(s).replace(/[^a-zA-Z0-9_-]/g, '\\$&');

    document.addEventListener('click', (e) => {
      const toggle = e.target.closest('[data-accordion-toggle]');
      if (!toggle) return;

      e.preventDefault();
      e.stopPropagation();
      
      // Find appropriate container
      const article = toggle.closest('article');
      const productAccordions = toggle.closest('[data-component="ProductAccordions"]');
      const container = article || productAccordions || document;
      this.handleToggle(toggle, container, esc);
    });
  }

  /**
   * Handle single accordion toggle
   */
  handleToggle(toggle, container, esc) {
    const panelId = toggle.getAttribute('aria-controls');
    if (!panelId) return; // Must have aria-controls
    
    let panel = null;

    // Try immediate next sibling first
    panel = toggle.nextElementSibling;
    if (panel && !(panel instanceof HTMLElement)) {
      panel = null;
    }

    // If next sibling doesn't work, search by ID
    if (!panel) {
      // Try direct global ID lookup first (most reliable)
      panel = document.getElementById(panelId);
    }
    
    // If still not found, try within container
    if (!panel && container) {
      try {
        panel = container.querySelector(`#${esc(panelId)}`);
      } catch (e) {
        // If CSS.escape fails, try without escaping
        panel = container.querySelector(`#${panelId}`);
      }
    }
    
    // Fallback: look for any panel data attribute
    if (!panel && container) {
      panel = container.querySelector('[data-accordion-panel]');
    }

    console.log('🎯 Accordion toggle clicked:', { panelId, panelFound: !!panel, toggle });

    const willExpand = toggle.getAttribute('aria-expanded') !== 'true';

    // Close all other panels in container
    container.querySelectorAll('[data-accordion-toggle]').forEach((other) => {
      if (other === toggle) return;
      const oid = other.getAttribute('aria-controls');
      let op = other.nextElementSibling;

      if (!op || !(op instanceof HTMLElement)) {
        op = container.querySelector(`#${esc(oid)}`);
      }

      other.setAttribute('aria-expanded', 'false');
      this.setPanelHidden(op);
    });

    // Open/close current panel
    toggle.setAttribute('aria-expanded', String(willExpand));
    if (panel) {
      if (willExpand) {
        console.log('📖 Showing panel');
        this.setPanelVisible(panel);
      } else {
        console.log('📕 Hiding panel');
        this.setPanelHidden(panel);
      }

      // Rotate icon
      const icon = toggle.querySelector('svg');
      if (icon) icon.classList.toggle('rotate-180', willExpand);
    } else {
      console.warn('❌ Panel not found for ID:', panelId);
    }
  }

  /**
   * Show panel
   */
  setPanelVisible(panel) {
    if (!panel) return;
    
    console.log('Before show - Classes:', panel.className);
    console.log('Before show - Styles:', panel.style.cssText);
    
    // Remove hidden class if it exists
    panel.classList.remove('hidden');
    
    // Get the scrollHeight to know how tall the content is
    const scrollHeight = panel.scrollHeight;
    console.log('Panel scrollHeight:', scrollHeight);
    
    // Set max-height and opacity for smooth animation
    panel.style.maxHeight = scrollHeight + 'px';
    panel.style.opacity = '1';
    panel.style.visibility = 'visible';
    
    panel.setAttribute('aria-hidden', 'false');
    
    console.log('After show - Classes:', panel.className);
    console.log('After show - Styles:', panel.style.cssText);
  }

  /**
   * Hide panel
   */
  setPanelHidden(panel) {
    if (!panel) return;
    
    console.log('Before hide - Classes:', panel.className);
    
    // Add hidden class
    panel.classList.add('hidden');
    
    // Collapse with animation
    panel.style.maxHeight = '0';
    panel.style.opacity = '0';
    panel.style.visibility = 'hidden';
    
    panel.setAttribute('aria-hidden', 'true');
    
    console.log('After hide - Classes:', panel.className);
    console.log('After hide - Styles:', panel.style.cssText);
  }

  /**
   * Setup filter section accordions (desktop sidebar + mobile)
   */
  setupFilterAccordions(container) {
    this.setDefaultState(container);
    this.attachFilterHandlers(container);
  }

  /**
   * Set initial state for filter sections (all open on desktop, first open on mobile)
   */
  setDefaultState(container) {

    const sections = container.querySelectorAll('.filter-section');
    let hasStateSet = false;

    // Check if any section already has visible state
    sections.forEach(section => {
      const panel = section.querySelector('.filter-options');
      if (panel && (panel.style.display === 'block' || !panel.classList.contains('hidden'))) {
        hasStateSet = true;
      }
    });

    // Only set if no state already configured
    if (!hasStateSet) {
      sections.forEach((section) => {
        const panel = section.querySelector('.filter-options');
        const header = section.querySelector('.filter-toggle');
        if (!panel || !header) return;

        panel.style.display = 'block';
        panel.classList.remove('hidden');
        section.classList.add('expanded');

        const icons = header.querySelectorAll('svg');
        icons.forEach(icon => icon.style.display = '');
      });
    }
  }

  /**
   * Set initial state for modal (first section open, others closed)
   */
  setInitialModalState(container) {

    const sections = container.querySelectorAll('.filter-section');
    sections.forEach((section, index) => {
      const panel = section.querySelector('.filter-options');
      const header = section.querySelector('.filter-toggle');
      if (!panel || !header) return;

      const icons = header.querySelectorAll('svg');
      const [minusIcon, plusIcon] = icons;

      if (index === 0) {
        panel.style.display = 'block';
        panel.classList.remove('hidden');
        section.classList.add('expanded');
        if (minusIcon) minusIcon.style.display = '';
        if (plusIcon) plusIcon.style.display = 'none';
      } else {
        panel.style.display = 'none';
        panel.classList.add('hidden');
        section.classList.remove('expanded');
        if (minusIcon) minusIcon.style.display = 'none';
        if (plusIcon) plusIcon.style.display = '';
      }
    });
  }

  /**
   * Attach click handlers to filter accordion headers
   */
  attachFilterHandlers(container) {
    const sections = container.querySelectorAll('.filter-section');
    sections.forEach(section => {
      const header = section.querySelector('.filter-toggle');
      const panel = section.querySelector('.filter-options');
      if (!header || !panel || header._accordionBound) return;

      header._accordionBound = true;

      header.addEventListener('click', (e) => {
        e.preventDefault();
        const isExpanded = section.classList.contains('expanded');

        // Close all panels
        sections.forEach(other => {
          const op = other.querySelector('.filter-options');
          if (!op) return;
          op.style.display = 'none';
          op.classList.add('hidden');
          other.classList.remove('expanded');
        });

        // Open if wasn't expanded
        if (!isExpanded) {
          panel.style.display = 'block';
          panel.classList.remove('hidden');
          section.classList.add('expanded');
        }
      });
    });
  }


  /**
   * Normalize toggle icons (ensure first svg is minus, second is plus)
   */
  normalizeIcons(container) {
    container.querySelectorAll('.filter-toggle').forEach(tg => {
      const svgs = tg.querySelectorAll('svg');
      if (svgs.length >= 2) {
        svgs[0].classList.add('minus-icon');
        svgs[1].classList.add('plus-icon');
      }
    });
  }
}
