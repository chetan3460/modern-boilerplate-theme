/**
 * Mobile filter enhancement
 */
export default class MobileFilterBridge {
  constructor() {
    this.init();
  }

  init() {
    // Wait for DOM to be ready
    if (document.readyState === 'loading') {
      document.addEventListener('DOMContentLoaded', () => this.setupEnhancements());
    } else {
      this.setupEnhancements();
    }
  }

  setupEnhancements() {
    // Find the existing mobile filter button
    const existingButton = document.getElementById('mobile-filter-btn');
    
    console.log('MobileFilterBridge: Looking for mobile-filter-btn...', existingButton);
    
    if (existingButton) {
      // Add active filter count badge to existing button
      this.addFilterCountBadge(existingButton);
      
      // Update badge when filters change
      this.watchForFilterChanges(existingButton);
      
      console.log('Mobile filter enhancements applied successfully');
    } else {
      console.warn('MobileFilterBridge: mobile-filter-btn not found. Available elements:', document.querySelectorAll('[id*="filter"]'));
    }
  }

  addFilterCountBadge(button) {
    // Count active filters from URL or form inputs
    const activeCount = this.countActiveFilters();
    console.log('MobileFilterBridge: Active filter count:', activeCount);

    // Add or update badge
    let badge = button.querySelector('.filter-count-badge');
    
    if (activeCount > 0) {
      if (!badge) {
        badge = document.createElement('span');
        badge.className = 'filter-count-badge absolute -top-1 -right-1 bg-red-600 text-white text-xs rounded-full w-5 h-5 flex items-center justify-center';
        button.style.position = 'relative';
        button.appendChild(badge);
      }
      badge.textContent = activeCount;
      badge.style.display = 'flex';
    } else if (badge) {
      badge.style.display = 'none';
    }
  }

  countActiveFilters() {
    // Count only from checked checkboxes in the sidebar to avoid double counting
    const checkedBoxes = document.querySelectorAll('#product-filters input[type="checkbox"]:checked');
    return checkedBoxes.length;
  }

  watchForFilterChanges(button) {
    // Watch for filter checkbox changes immediately
    document.addEventListener('change', (e) => {
      if (e.target.type === 'checkbox' && e.target.closest('#product-filters')) {
        // Small delay to ensure DOM is updated
        setTimeout(() => this.addFilterCountBadge(button), 50);
      }
    });

    // Watch for product listing updates (AJAX responses)
    let lastCount = this.countActiveFilters();
    const checkInterval = setInterval(() => {
      const currentCount = this.countActiveFilters();
      if (currentCount !== lastCount) {
        this.addFilterCountBadge(button);
        lastCount = currentCount;
      }
    }, 500); // Reduced interval for better responsiveness

    // Clean up interval when button is removed
    const observer = new MutationObserver((mutations) => {
      mutations.forEach((mutation) => {
        mutation.removedNodes.forEach((node) => {
          if (node === button) {
            clearInterval(checkInterval);
            observer.disconnect();
          }
        });
      });
    });
    observer.observe(document.body, { childList: true, subtree: true });
  }
}
