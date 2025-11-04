/**
 * MobileModalManager - Handles mobile filter modal
 */
export class MobileModalManager {
  constructor(section, filterManager, accordionManager, productListing = null) {
    this.section = section;
    this.filterManager = filterManager;
    this.accordionManager = accordionManager;
    this.productListing = productListing;
    this.isOpen = false;
    this.modalOverlay = null;
    this.mobileModal = document.getElementById('mobile-filter-modal');
    this.mobileFilterBtn = document.getElementById('mobile-filter-btn');
  }

  /**
   * Setup mobile filter button and modal
   */
  setup() {
    if (!this.mobileFilterBtn || !this.mobileModal) return;

    this.mobileFilterBtn.addEventListener('click', (e) => {
      e.preventDefault();
      this.openModal();
    });

    // Close on ESC key
    document.addEventListener('keydown', (e) => {
      if (e.key === 'Escape' && this.isOpen) {
        this.closeModal();
      }
    });
  }

  /**
   * Open mobile modal
   */
  openModal() {
    if (this.isOpen) return;

    this.cleanup();
    this.createModalOverlay();
    this.pauseScrollSmoother();
    this.isOpen = true;
  }

  /**
   * Close mobile modal
   */
  closeModal() {
    // Store smoother reference before removing overlay
    const smoother = this.modalOverlay?._scrollSmoother;
    
    if (this.modalOverlay) {
      this.modalOverlay.remove();
      this.modalOverlay = null;
    }

    // Restore overflow FIRST
    document.body.style.overflow = '';
    document.documentElement.style.overflow = '';
    document.body.classList.remove('menu-open', 'modal-open');

    // Then restore ScrollSmoother
    this.restoreScrollSmootherDirect(smoother);
    this.isOpen = false;
  }

  /**
   * Create modal overlay with content
   */
  createModalOverlay() {
    this.modalOverlay = document.createElement('div');
    this.modalOverlay.className = 'mobile-filter-overlay-wrapper';

    const modalContent = document.createElement('div');
    modalContent.className = 'mobile-filter-content';

    const sidebarContent = this.mobileModal.innerHTML;

    if (sidebarContent?.trim()) {
      modalContent.innerHTML = sidebarContent;
      this.accordionManager.normalizeIcons(modalContent);

      // Sync checkboxes
      this.syncModalCheckboxes(modalContent);

      // Setup handlers (before initializing accordions so clear-all visibility is set)
      this.setupModalHandlers(modalContent);

      // Initialize accordions
      this.accordionManager.setInitialModalState(modalContent);
      this.accordionManager.attachFilterHandlers(modalContent);
    } else {
      modalContent.innerHTML = this.getFallbackHTML();
      const closeBtn = modalContent.querySelector('.close-modal-btn');
      if (closeBtn) {
        closeBtn.addEventListener('click', () => this.closeModal());
      }
    }

    this.modalOverlay.appendChild(modalContent);
    document.body.appendChild(this.modalOverlay);

    // Close on backdrop click
    this.modalOverlay.addEventListener('click', (e) => {
      if (e.target === this.modalOverlay) {
        this.closeModal();
      }
    });

    // Prevent body scroll
    document.body.style.overflow = 'hidden';
    document.documentElement.style.overflow = 'hidden';
  }

  /**
   * Sync modal checkboxes with current state
   */
  syncModalCheckboxes(modalContent) {
    const originalCheckboxes = this.section.querySelectorAll('input[type="checkbox"]');
    const modalCheckboxes = modalContent.querySelectorAll('input[type="checkbox"]');

    // Sync by name and value
    modalCheckboxes.forEach(modalCb => {
      const matching = this.section.querySelector(
        `input[name="${modalCb.name}"][value="${modalCb.value}"]`
      );
      if (matching) {
        modalCb.checked = matching.checked;
      }
    });
  }

  /**
   * Setup modal event handlers
   */
  setupModalHandlers(modalContent) {
    // Close button
    const closeBtn = modalContent.querySelector('.mobile-filter-close');
    if (closeBtn) {
      closeBtn.addEventListener('click', () => this.closeModal());
    }

    // Clear All button
    const clearAllBtn = modalContent.querySelector('.clear-all-filters');
    if (clearAllBtn) {
      this.setupClearAllButton(clearAllBtn, modalContent);
    }

    // Checkbox handlers
    const checkboxes = modalContent.querySelectorAll('input[type="checkbox"]');
    checkboxes.forEach(checkbox => {
      checkbox.addEventListener('change', (e) => {
        e.stopPropagation();
        this.handleCheckboxChange(checkbox);
      });
    });
  }

  /**
   * Setup clear all button in modal
   */
  setupClearAllButton(clearAllBtn, modalContent) {
    const updateVisibility = () => {
      const checkedBoxes = modalContent.querySelectorAll('input[type="checkbox"]:checked');
      if (checkedBoxes.length > 0) {
        clearAllBtn.classList.remove('hidden');
        clearAllBtn.classList.add('flex', 'items-center', 'gap-2');
        const labelSpan = clearAllBtn.querySelector('span');
        if (labelSpan) labelSpan.textContent = `Clear All (${checkedBoxes.length})`;
      } else {
        clearAllBtn.classList.add('hidden');
        clearAllBtn.classList.remove('flex', 'items-center', 'gap-2');
      }
    };

    updateVisibility();

    clearAllBtn.addEventListener('click', (e) => {
      e.preventDefault();
      this.filterManager.clearAllFilters();
      this.syncModalCheckboxes(modalContent);
      updateVisibility();
      this.refreshScrollSmoother();
      
      // Trigger product refresh like desktop clear-all does
      if (this.productListing && this.productListing.resetAndFetch) {
        this.productListing.resetAndFetch('clear');
      }
    });

    modalContent._updateClearAllVisibility = updateVisibility;
  }

  /**
   * Handle checkbox change in modal
   */
  handleCheckboxChange(checkbox) {
    // Find matching checkbox in main page
    const mainCheckbox = this.section.querySelector(
      `input[name="${checkbox.name}"][value="${checkbox.value}"]`
    );

    if (mainCheckbox) {
      mainCheckbox.checked = checkbox.checked;
      mainCheckbox.dispatchEvent(new Event('change', { bubbles: true }));
    } else {
      // Directly trigger filter change
      let filterType = '';
      if (checkbox.name === 'chemistry[]') filterType = 'chemistry';
      else if (checkbox.name === 'brand[]') filterType = 'brand';
      else if (checkbox.name === 'applications[]') filterType = 'applications';

      if (filterType) {
        this.filterManager.handleFilterChange(filterType, checkbox.value, checkbox.checked);
        // Trigger product refresh
        if (this.productListing && this.productListing.resetAndFetch) {
          this.productListing.resetAndFetch('filter');
        }
      }
    }

    this.refreshScrollSmoother();
  }

  /**
   * Reinitialize modal if open (after product updates)
   */
  reinitIfOpen() {
    const modalContent = document.querySelector('.mobile-filter-overlay-wrapper .mobile-filter-content');
    if (!modalContent) return;

    this.accordionManager.normalizeIcons(modalContent);
    this.accordionManager.attachFilterHandlers(modalContent);

    // Update Clear All visibility
    const clearAllBtn = modalContent.querySelector('.clear-all-filters');
    if (clearAllBtn && modalContent._updateClearAllVisibility) {
      modalContent._updateClearAllVisibility();
    }
  }

  /**
   * Cleanup existing modals
   */
  cleanup() {
    const existing = document.querySelectorAll('.mobile-filter-overlay-wrapper');
    existing.forEach(el => el.remove());
    this.modalOverlay = null;
  }

  /**
   * Pause ScrollSmoother
   */
  pauseScrollSmoother() {
    const detectionMethods = [
      () => window.ScrollSmoother?.get(),
      () => window.gsap?.ScrollSmoother?.get(),
      () => window.app?.smoother
    ];

    for (const method of detectionMethods) {
      try {
        const smoother = method();
        if (smoother?.paused) {
          smoother.paused(true);
          this.modalOverlay._scrollSmoother = smoother;
          break;
        }
      } catch (e) {}
    }
  }

  /**
   * Restore ScrollSmoother (with stored reference)
   */
  restoreScrollSmootherDirect(smoother) {
    if (!smoother) return;
    
    try {
      // Unpause the smoother
      smoother.paused(false);
      
      // Immediate refresh
      smoother.refresh();
      
      // Refresh ScrollTrigger
      if (window.gsap?.ScrollTrigger) {
        window.gsap.ScrollTrigger.refresh();
      }
      
      // Delayed refresh for layout recalculation
      setTimeout(() => {
        try {
          smoother.refresh();
          if (window.gsap?.ScrollTrigger) {
            window.gsap.ScrollTrigger.refresh();
          }
        } catch (e) {}
      }, 50);
      
      // Final refresh after all animations settle
      setTimeout(() => {
        try {
          smoother.refresh();
        } catch (e) {}
      }, 150);
    } catch (e) {}
  }

  /**
   * Restore ScrollSmoother (legacy - kept for compatibility)
   */
  restoreScrollSmoother() {
    const smoother = this.modalOverlay?._scrollSmoother;
    this.restoreScrollSmootherDirect(smoother);
  }

  /**
   * Refresh ScrollSmoother after content change
   */
  refreshScrollSmoother() {
    const methods = [
      () => window.ScrollSmoother?.get(),
      () => window.gsap?.ScrollSmoother?.get(),
      () => window.app?.smoother
    ];

    for (const method of methods) {
      try {
        const smoother = method();
        if (smoother?.refresh) {
          smoother.refresh();
          break;
        }
      } catch (e) {}
    }
  }

  /**
   * Fallback HTML if modal content is empty
   */
  getFallbackHTML() {
    return `
      <div class="flex items-center justify-between mb-6">
        <h3 class="text-lg font-semibold text-gray-900">Filters</h3>
        <div class="flex items-center gap-3">
          <button type="button" class="clear-all-filters text-sm text-red-600 hover:text-red-700 font-medium flex items-center gap-2">
            <span>Clear All</span>
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="17" viewBox="0 0 16 17" fill="none">
              <path d="M12 4.19922L4 12.1992" stroke="currentColor" stroke-width="1.58333" stroke-linecap="round" stroke-linejoin="round" />
              <path d="M4 4.19922L12 12.1992" stroke="currentColor" stroke-width="1.58333" stroke-linecap="round" stroke-linejoin="round" />
            </svg>
          </button>
          <button type="button" class="close-modal-btn p-1 hover:bg-gray-100 rounded">
            <svg class="w-5 h-5 text-gray-500" fill="currentColor" viewBox="0 0 20 20">
              <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
            </svg>
          </button>
        </div>
      </div>
      <p class="text-gray-600">Filter functionality will be added here.</p>
    `;
  }
}
