/**
 * Footer Utilities
 * Handles Vite dev badge positioning, year updates, and accessibility enhancements
 */

export default class FooterUtils {
  constructor() {
    this.init();
  }

  init() {
    this.updateCurrentYear();
    this.fixViteBadgePosition();
    this.ensureSelectLabels();
    this.observeNewSelects();
  }

  /**
   * Update copyright year dynamically
   */
  updateCurrentYear() {
    const yearSpan = document.getElementById('current-year');
    if (yearSpan) {
      yearSpan.textContent = new Date().getFullYear();
    }
  }

  /**
   * Fix Vite dev badge floating position
   */
  fixViteBadgePosition() {
    const viteBadge = document.getElementById('vite-dev-badge');
    if (!viteBadge) return;

    // Move badge to end of body to avoid container positioning issues
    document.body.appendChild(viteBadge);

    // Force floating styles with maximum specificity
    const styles = {
      position: 'fixed',
      right: '16px',
      bottom: '16px',
      zIndex: '999999',
      display: 'inline-flex',
      alignItems: 'center',
      justifyContent: 'center',
      width: '46px',
      height: '46px',
      borderRadius: '50%',
      background: 'linear-gradient(135deg, #646cff, #00d4ff)',
      color: '#fff',
      boxShadow: '0 10px 25px rgba(0,0,0,.2)',
      textDecoration: 'none',
      pointerEvents: 'auto',
      visibility: 'visible',
      opacity: '1',
      transform: 'none',
      margin: '0',
      padding: '0'
    };

    // Apply all styles
    Object.assign(viteBadge.style, styles);

    // Replace SVG icon with update.gif
    this.replaceViteBadgeIcon(viteBadge);

    // Double-check positioning after a brief delay
    setTimeout(() => {
      Object.assign(viteBadge.style, styles);
    }, 100);
  }

  /**
   * Replace Vite SVG with custom GIF
   */
  replaceViteBadgeIcon(viteBadge) {
    const existingSvg = viteBadge.querySelector('svg');
    if (!existingSvg) return;

    // Hide the existing SVG
    existingSvg.style.display = 'none';

    // Create new img element with update.gif
    const updateImg = document.createElement('img');
    // Get theme URL from script data attribute
    const scriptEl = document.querySelector('script[data-theme-url]');
    const themeUrl = scriptEl?.dataset.themeUrl || '/wp-content/themes/resplast-theme';
    updateImg.src = `${themeUrl}/assets/images/update.gif`;
    updateImg.style.borderRadius = '50%';
    updateImg.alt = 'Vite Dev Server';

    // Add the GIF to the badge
    viteBadge.appendChild(updateImg);
  }

  /**
   * Ensure all select elements have accessible labels
   */
  ensureSelectLabels(root = document) {
    const selects = root.querySelectorAll('select');
    selects.forEach(sel => {
      if (sel.dataset.a11yProcessed === 'true') return;
      sel.dataset.a11yProcessed = 'true';

      const id = sel.id;
      let hasAssociatedLabel = false;

      // Check for associated label
      if (id) {
        try {
          const lbl = root.querySelector(`label[for="${CSS.escape(id)}"]`);
          hasAssociatedLabel = !!(lbl && lbl.textContent.trim().length > 0);
        } catch (e) {}
      }

      const hasAriaLabel = !!(sel.getAttribute('aria-label') && sel.getAttribute('aria-label').trim().length > 0);

      // Add label if missing
      if (!hasAssociatedLabel && !hasAriaLabel) {
        const text = sel.getAttribute('placeholder') || sel.getAttribute('data-placeholder') || sel.dataset.placeholder || 'Select an option';
        sel.setAttribute('aria-label', text);

        if (id && sel.parentNode) {
          const hiddenLabel = document.createElement('label');
          hiddenLabel.setAttribute('for', id);
          hiddenLabel.textContent = text;

          // SR-only styles
          Object.assign(hiddenLabel.style, {
            position: 'absolute',
            width: '1px',
            height: '1px',
            padding: '0',
            margin: '-1px',
            overflow: 'hidden',
            clip: 'rect(0, 0, 0, 0)',
            whiteSpace: 'nowrap',
            border: '0'
          });

          sel.parentNode.insertBefore(hiddenLabel, sel);
        }
      }
    });
  }

  /**
   * Observe for dynamically added select elements
   */
  observeNewSelects() {
    const observer = new MutationObserver(mutations => {
      mutations.forEach(m => {
        m.addedNodes.forEach(node => {
          if (node.nodeType === 1) { // ELEMENT_NODE
            if (node.tagName === 'SELECT') {
              this.ensureSelectLabels(node.parentNode || document);
            } else {
              const innerSelects = node.querySelectorAll ? node.querySelectorAll('select') : [];
              if (innerSelects.length) this.ensureSelectLabels(node);
            }
          }
        });
      });
    });

    observer.observe(document.body, {
      childList: true,
      subtree: true
    });
  }
}
