/**
 * ProductAccordions - Initialize product card accordions
 * Simple implementation using only inline styles (no hidden class)
 */
export default class ProductAccordions {
  static eventListenerAttached = false;
  
  constructor(element) {
    if (!ProductAccordions.eventListenerAttached) {
      document.addEventListener('click', (e) => {
        const toggle = e.target.closest('[data-accordion-toggle]');
        if (!toggle) return;
        e.preventDefault();
        e.stopPropagation();
        this.toggle(toggle);
      }, true);
      ProductAccordions.eventListenerAttached = true;
    }
  }
  
  static toggle(toggle) {
    const panelId = toggle.getAttribute('aria-controls');
    const panel = document.getElementById(panelId);
    if (!panel) return;
    
    const isExpanded = toggle.getAttribute('aria-expanded') === 'true';
    const nextState = !isExpanded;
    
    toggle.setAttribute('aria-expanded', nextState);
    
    if (nextState) {
      // OPEN
      const height = panel.scrollHeight;
      panel.style.maxHeight = height + 'px';
      panel.style.opacity = '1';
      panel.style.marginBottom = '';
      panel.style.visibility = 'visible';
      panel.setAttribute('aria-hidden', 'false');
    } else {
      // CLOSE
      panel.style.maxHeight = '0';
      panel.style.opacity = '0';
      panel.style.marginBottom = '0';
      panel.style.visibility = 'hidden';
      panel.setAttribute('aria-hidden', 'true');
    }
    
    // Rotate icon
    const icon = toggle.querySelector('svg');
    if (icon) icon.classList.toggle('rotate-180', nextState);
  }
}

// Bind the toggle method
ProductAccordions.prototype.toggle = ProductAccordions.toggle;
