/**
 * Mobile Categories Dropdown Component
 * Handles mobile categories dropdown with proper scrolling
 */

export default class MobileCategoriesDropdown {
  constructor(el, config = {}) {
    this.element = el;
    this.config = config;
    this.init();
  }

  init() {
    this.toggle = this.element.querySelector('#categories-toggle');
    this.dropdown = this.element.querySelector('#categories-dropdown');
    this.selectedCategory = this.element.querySelector('#selected-category');

    if (!this.toggle || !this.dropdown) {
      return;
      return;
    }

    this.categoryItems = this.dropdown.querySelectorAll('.dd-item');
    this.setupEventListeners();
    this.setInitialSelection();
  }

  setupEventListeners() {
    // Toggle dropdown on button click
    this.toggle.addEventListener('click', (e) => {
      e.preventDefault();
      this.toggleDropdown();
    });

    // Improve scrolling within dropdown (prevent scroll chaining)
    this.dropdown.addEventListener('wheel', (e) => {
      e.stopPropagation();
    }, { passive: true });
    this.dropdown.addEventListener('touchmove', (e) => {
      e.stopPropagation();
    }, { passive: true });
    this.dropdown.addEventListener('touchstart', () => { }, { passive: true });
    this.dropdown.addEventListener('touchend', () => { }, { passive: true });

    // Handle category selection
    this.categoryItems.forEach((item) => {
      item.addEventListener('click', (e) => {
        e.preventDefault();
        this.selectCategory(item);
      });

      // Add passive touch events
      item.addEventListener('touchstart', () => { }, { passive: true });
      item.addEventListener('touchmove', () => { }, { passive: true });
      item.addEventListener('touchend', () => { }, { passive: true });
    });

    // Close when clicking outside
    document.addEventListener('click', (e) => {
      if (!this.toggle.contains(e.target) && !this.dropdown.contains(e.target)) {
        this.closeDropdown();
      }
    });
  }

  toggleDropdown() {
    const isOpen = !this.dropdown.classList.contains('hidden');

    if (isOpen) {
      this.closeDropdown();
    } else {
      this.openDropdown();
    }
  }

  openDropdown() {
    this.dropdown.classList.remove('hidden');
    this.toggle.setAttribute('aria-expanded', 'true');
  }

  closeDropdown() {
    this.dropdown.classList.add('hidden');
    this.toggle.setAttribute('aria-expanded', 'false');
  }

  selectCategory(selectedItem) {
    // Get category info
    const categorySlug = selectedItem.getAttribute('data-category');
    const displayName = selectedItem.textContent.trim();

    // Update selected category display
    if (this.selectedCategory) {
      this.selectedCategory.textContent = displayName;
      this.selectedCategory.classList.remove('font-semibold', 'text-gray-400');
      this.selectedCategory.classList.add('text-white');
    }

    // Update visual state
    this.updateCategoryVisualState(selectedItem);

    // Close dropdown
    this.closeDropdown();

    // Trigger category change event for filtering
    const categoryChangeEvent = new CustomEvent('mobileCategory:change', {
      detail: {
        category: categorySlug,
        displayName: displayName,
        element: selectedItem
      }
    });

    // Dispatch on the reports section to be caught by ReportsFilter component
    const reportsSection = document.querySelector('.reports-investor-section');
    if (reportsSection) {
      reportsSection.dispatchEvent(categoryChangeEvent);
    } else {
      // Fallback to document if reports section not found
      document.dispatchEvent(categoryChangeEvent);
    }

  }

  updateCategoryVisualState(selectedItem) {
    // Reset all items
    this.categoryItems.forEach((item) => {
      item.classList.remove('active', 'text-white', 'text-primary', 'bg-primary', 'bg-primary/5');
      item.classList.add('text-black');
    });

    // Activate selected item
    selectedItem.classList.add('active', 'bg-primary', 'text-white');
    selectedItem.classList.remove('text-black');
  }

  // Public API methods
  setSelectedCategory(categorySlug, displayName) {
    const targetItem = this.element.querySelector(`[data-category="${categorySlug}"]`);
    if (targetItem) {
      this.selectCategory(targetItem);
    }
  }

  getSelectedCategory() {
    const activeItem = this.element.querySelector('.dd-item.active');
    if (activeItem) {
      return {
        slug: activeItem.getAttribute('data-category'),
        name: activeItem.textContent.trim()
      };
    }
    return null;
  }

  setInitialSelection() {
    // Find the first category (which should already be marked as active)
    const firstActiveItem = this.dropdown.querySelector('.dd-item.active');
    if (firstActiveItem) {
      const categorySlug = firstActiveItem.getAttribute('data-category');
      const displayName = firstActiveItem.textContent.trim();

      // Ensure proper text color for initial selection
      if (this.selectedCategory) {
        this.selectedCategory.classList.remove('text-gray-400', 'font-semibold');
        this.selectedCategory.classList.add('text-primary', 'font-normal');
      }

      // Dispatch initial selection event to sync with ReportsFilter
      const categoryChangeEvent = new CustomEvent('mobileCategory:change', {
        detail: {
          category: categorySlug,
          displayName: displayName,
          element: firstActiveItem
        }
      });

      // Dispatch on the reports section
      const reportsSection = document.querySelector('.reports-investor-section');
      if (reportsSection) {
        reportsSection.dispatchEvent(categoryChangeEvent);
      } else {
        document.dispatchEvent(categoryChangeEvent);
      }

    }
  }

  destroy() {
  }

}
