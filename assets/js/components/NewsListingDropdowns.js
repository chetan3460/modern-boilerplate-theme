/**
 * NewsListingDropdowns Component
 * 
 * Handles dropdown arrow rotation and results text updates for News Listing page
 * Features:
 * - Smooth arrow rotation on dropdown open/close
 * - Dynamic "Showing all results" text based on category selection
 * - MutationObserver for watching dropdown state changes
 */

class NewsListingDropdowns {
  constructor() {
    this.dropdowns = document.querySelectorAll('[data-dd]');
    this.resultsTextElement = null;
    this.baseText = 'Showing all results';
    this.init();
  }

  init() {
    // Find the results text element (could be in any News Listing section)
    const newsListingSections = document.querySelectorAll('[data-component="NewsListing"]');
    
    if (newsListingSections.length > 0) {
      // Get the first section's results text element
      const firstSection = newsListingSections[0];
      const sectionId = firstSection.id;
      this.resultsTextElement = document.getElementById(`${sectionId}-results-text`);
      this.baseText = this.resultsTextElement ? 
        (this.resultsTextElement.dataset.baseText || 'Showing all results') : 
        'Showing all results';
    }

    this.setupDropdowns();
  }

  setupDropdowns() {
    this.dropdowns.forEach(dropdown => {
      const button = dropdown.querySelector('button');
      const menu = dropdown.querySelector('.dd-menu');
      const arrow = button?.querySelector('svg');

      if (!button || !menu || !arrow) return;

      // Add smooth transition for arrow rotation
      arrow.style.transition = 'transform 0.2s ease-in-out';

      // Setup dropdown observer
      this.setupDropdownObserver(menu, arrow);

      // Setup category text updates
      if (dropdown.dataset.dd === 'cat') {
        this.setupCategoryTextUpdates(dropdown);
      }
    });
  }

  setupDropdownObserver(menu, arrow) {
    // Create a MutationObserver to watch for class changes on the menu
    const observer = new MutationObserver((mutations) => {
      mutations.forEach((mutation) => {
        if (mutation.type === 'attributes' && mutation.attributeName === 'class') {
          const isHidden = menu.classList.contains('hidden');
          arrow.style.transform = isHidden ? 'rotate(0deg)' : 'rotate(180deg)';
        }
      });
    });

    // Start observing
    observer.observe(menu, {
      attributes: true,
      attributeFilter: ['class']
    });
  }

  setupCategoryTextUpdates(dropdown) {
    const items = dropdown.querySelectorAll('.dd-item');
    
    items.forEach(item => {
      item.addEventListener('click', () => {
        const categoryName = item.textContent.trim();
        this.updateResultsText(categoryName);
      });
    });
  }

  updateResultsText(categoryName) {
    if (this.resultsTextElement) {
      if (categoryName && categoryName !== 'All Categories') {
        this.resultsTextElement.textContent = `${this.baseText} ${categoryName}`;
      } else {
        this.resultsTextElement.textContent = this.baseText;
      }
    }
  }
}

// Auto-initialize when DOM is loaded
document.addEventListener('DOMContentLoaded', () => {
  // Only initialize if we have dropdowns with data-dd attributes
  if (document.querySelectorAll('[data-dd]').length > 0) {
    new NewsListingDropdowns();
  }
});

// Export for manual initialization if needed
if (typeof module !== 'undefined' && module.exports) {
  module.exports = NewsListingDropdowns;
}