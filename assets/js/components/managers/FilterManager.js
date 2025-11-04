/**
 * FilterManager - Manages filter state and updates
 */
export class FilterManager {
  constructor(section) {
    this.section = section;
    this.state = {
      chemistry: [],
      brand: [],
      applications: [],
      search: '',
      sort: 'name',
      view: 'grid'
    };

    this.cacheElements();
  }

  /**
   * Cache DOM elements
   */
  cacheElements() {
    this.chemistryFilters = this.section.querySelectorAll('input[name="chemistry[]"]');
    this.brandFilters = this.section.querySelectorAll('input[name="brand[]"]');
    this.applicationFilters = this.section.querySelectorAll('input[name="applications[]"]');
    this.searchInput = this.section.querySelector('#' + this.section.id + '-search');
    this.sortSelect = this.section.querySelector('#' + this.section.id + '-sort');
    this.searchForm = this.section.querySelector('#' + this.section.id + '-search-form');
    this.clearAllBtn = this.section.querySelector('.clear-all-filters');
    this.removeFilterBtns = this.section.querySelectorAll('[data-remove-filter]');
    this.viewModeBtns = this.section.querySelectorAll('.view-mode-btn');
  }

  /**
   * Initialize filters from URL or global state
   */
  initializeFromState() {
    if (window.productFilters) {
      this.state = { ...this.state, ...window.productFilters };
    } else {
      this.initializeFromURL();
    }
    this.syncCheckboxesToState();
  }

  /**
   * Parse URL parameters into state
   */
  initializeFromURL() {
    const params = new URLSearchParams(window.location.search);

    if (params.get('chemistry')) {
      this.state.chemistry = params.get('chemistry').split(',');
    }
    if (params.get('brand')) {
      this.state.brand = params.get('brand').split(',');
    }
    if (params.get('applications')) {
      this.state.applications = params.get('applications').split(',');
    }
    if (params.get('s')) {
      this.state.search = params.get('s');
    }
    if (params.get('sort')) {
      this.state.sort = params.get('sort');
    }
    if (params.get('view')) {
      this.state.view = params.get('view');
    }
  }

  /**
   * Sync checkboxes to current state
   */
  syncCheckboxesToState() {
    this.chemistryFilters.forEach(cb => {
      cb.checked = this.state.chemistry.includes(cb.value);
    });

    this.brandFilters.forEach(cb => {
      cb.checked = this.state.brand.includes(cb.value);
    });

    this.applicationFilters.forEach(cb => {
      cb.checked = this.state.applications.includes(cb.value);
    });

    if (this.searchInput) {
      this.searchInput.value = this.state.search;
    }

    if (this.sortSelect) {
      this.sortSelect.value = this.state.sort;
    }

    this.updateViewModeButtons();
  }

  /**
   * Update filter state when checkbox changes
   */
  handleFilterChange(filterType, value, isChecked) {
    if (isChecked) {
      if (!this.state[filterType].includes(value)) {
        this.state[filterType].push(value);
      }
    } else {
      this.state[filterType] = this.state[filterType].filter(v => v !== value);
    }
  }

  /**
   * Update search state
   */
  handleSearchChange() {
    this.state.search = this.searchInput ? this.searchInput.value.trim() : '';
  }

  /**
   * Clear all filters
   */
  clearAllFilters() {
    this.state.chemistry = [];
    this.state.brand = [];
    this.state.applications = [];
    this.state.search = '';
    this.syncCheckboxesToState();

    // Also clear modal checkboxes
    const modalCheckboxes = document.querySelectorAll('.mobile-filter-content input[type="checkbox"]');
    modalCheckboxes.forEach(checkbox => checkbox.checked = false);
  }

  /**
   * Remove single filter
   */
  removeFilter(filterType, value) {
    if (this.state[filterType]) {
      this.state[filterType] = this.state[filterType].filter(v => v !== value);
      this.syncCheckboxesToState();
    }
  }

  /**
   * Update view mode buttons UI
   */
  updateViewModeButtons() {
    this.viewModeBtns.forEach(btn => {
      const isActive = btn.dataset.view === this.state.view;
      btn.classList.toggle('bg-red-600', isActive);
      btn.classList.toggle('text-white', isActive);
      btn.classList.toggle('bg-white', !isActive);
      btn.classList.toggle('text-gray-700', !isActive);
    });
  }

  /**
   * Update grid layout based on view mode
   */
  updateGridLayout(grid) {
    if (!grid) return;

    if (this.state.view === 'list') {
      grid.className = 'space-y-6';
      grid.setAttribute('data-view', 'list');
    } else {
      grid.className = 'grid gap-6 grid-cols-1 lg:grid-cols-2';
      grid.setAttribute('data-view', 'grid');
    }
  }

  /**
   * Update clear all button visibility
   */
  updateClearAllButton() {
    const totalActive = this.state.chemistry.length + 
                       this.state.brand.length + 
                       this.state.applications.length;

    if (!this.clearAllBtn) return;

    const labelEl = this.clearAllBtn.querySelector('span');
    if (totalActive > 0) {
      this.clearAllBtn.classList.remove('hidden');
      this.clearAllBtn.classList.add('flex', 'items-center', 'gap-2');
      if (labelEl) labelEl.textContent = `Clear All (${totalActive})`;
    } else {
      this.clearAllBtn.classList.add('hidden');
      this.clearAllBtn.classList.remove('flex', 'items-center', 'gap-2');
      if (labelEl) labelEl.textContent = 'Clear All';
    }
  }

  /**
   * Update filter counts after AJAX response
   */
  updateFilterCounts(counts) {
    const updateCount = (checkbox, countMap) => {
      const count = countMap[checkbox.value] || 0;
      const label = checkbox.closest('label');
      const countSpan = label?.querySelector('.text-xs');
      if (countSpan) {
        countSpan.textContent = `(${count})`;
      }
    };

    if (counts.chemistry) {
      this.chemistryFilters.forEach(cb => updateCount(cb, counts.chemistry));
    }
    if (counts.brand) {
      this.brandFilters.forEach(cb => updateCount(cb, counts.brand));
    }
    if (counts.applications) {
      this.applicationFilters.forEach(cb => updateCount(cb, counts.applications));
    }
  }

  /**
   * Get current filter state as URLSearchParams
   */
  getFilterParams() {
    const params = new URLSearchParams();
    params.append('chemistry', this.state.chemistry.length > 0 ? this.state.chemistry.join(',') : '');
    params.append('brand', this.state.brand.length > 0 ? this.state.brand.join(',') : '');
    params.append('applications', this.state.applications.length > 0 ? this.state.applications.join(',') : '');
    params.append('search', this.state.search || '');
    params.append('sort', this.state.sort);
    params.append('view', this.state.view);
    return params;
  }
}
