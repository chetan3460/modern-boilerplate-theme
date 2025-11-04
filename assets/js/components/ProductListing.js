import { AccordionManager } from './managers/AccordionManager.js';
import { FilterManager } from './managers/FilterManager.js';
import { MobileModalManager } from './managers/MobileModalManager.js';

/**
 * ProductListing - Simplified product filtering and display
 * Delegates to specialized manager modules
 */
export default class ProductListing {
  constructor(section) {
    this.section = section;
    if (!this.section) return;

    // Config from data attributes
    this.ajaxUrl = this.section.dataset.ajaxUrl;
    this.nonce = this.section.dataset.nonce;
    this.initialPP = Number(this.section.dataset.initialPpp || '12');
    this.loadPP = Number(this.section.dataset.loadPpp || '12');
    this.isLoadingMore = false;
    this.isLoading = false;

    // Cache grid elements
    this.grid = this.section.querySelector('#' + this.section.id + '-grid');
    this.loadBtn = this.section.querySelector('#' + this.section.id + '-load');
    this.loader = this.section.querySelector('#' + this.section.id + '-loader');
    this.endMsg = this.section.querySelector('#' + this.section.id + '-end');
    this.resultsText = this.section.querySelector('#' + this.section.id + '-results-text');

    // Initialize managers
    this.accordion = new AccordionManager();
    this.filters = new FilterManager(this.section);
    this.modal = new MobileModalManager(this.section, this.filters, this.accordion, this);

    // Initialize
    this.filters.initializeFromState();
    this.init();
  }

  /**
   * Initialize all event listeners
   */
  init() {
    this.setupDropdowns();
    this.bindFilterEvents();
    this.bindSortAndViewEvents();
    this.setupMobileFilters();
    this.setupFilterToggles();
    this.modal.setup();

    // Setup accordions
    const sidebarFilters = document.getElementById('product-filters');
    if (sidebarFilters) this.accordion.setupFilterAccordions(sidebarFilters);

    const mobileModal = document.getElementById('mobile-filter-modal');
    if (mobileModal) this.accordion.setupFilterAccordions(mobileModal);

    // Global product card accordions
    this.accordion.setupGlobal();

    this.filters.updateClearAllButton();
    this.filters.updateGridLayout(this.grid);
  }

  /**
   * Setup dropdown menus
   */
  setupDropdowns() {
    const dropdowns = this.section.querySelectorAll('[data-dd]');

    dropdowns.forEach(dropdown => {
      const btn = dropdown.querySelector('button');
      const menu = dropdown.querySelector('.dd-menu');
      const label = dropdown.querySelector('.dd-label');
      const sortSelect = this.section.querySelector('#' + this.section.id + '-sort');

      if (!btn || !menu) return;

      const close = () => {
        menu.classList.add('hidden');
        btn.setAttribute('aria-expanded', 'false');
      };

      const open = () => {
        dropdowns.forEach(other => {
          if (other !== dropdown) {
            other.querySelector('.dd-menu')?.classList.add('hidden');
            other.querySelector('button')?.setAttribute('aria-expanded', 'false');
          }
        });
        menu.classList.remove('hidden');
        btn.setAttribute('aria-expanded', 'true');
      };

      btn.addEventListener('click', (e) => {
        e.stopPropagation();
        menu.classList.contains('hidden') ? open() : close();
      });

      dropdown.querySelectorAll('.dd-item').forEach(item => {
        item.addEventListener('click', () => {
          if (sortSelect) {
            sortSelect.value = item.dataset.value;
            if (label) label.textContent = item.textContent.trim();
            this.filters.state.sort = item.dataset.value;
            this.resetAndFetch('sort');
          }
          close();
        });
      });

      document.addEventListener('click', (e) => {
        if (!dropdown.contains(e.target)) close();
      });
    });
  }

  /**
   * Setup mobile filter toggle
   */
  setupMobileFilters() {
    const mobileToggle = document.getElementById('mobile-filter-toggle');
    const filterSections = document.getElementById('filter-sections');
    const mobileIcon = document.getElementById('mobile-filter-icon');

    if (mobileToggle && filterSections) {
      mobileToggle.addEventListener('click', () => {
        const isOpen = filterSections.classList.contains('show');

        if (isOpen) {
          filterSections.classList.remove('show');
          mobileToggle.querySelector('span').textContent = 'Show Filters';
          mobileIcon?.classList.remove('rotate-180');
        } else {
          filterSections.classList.add('show');
          mobileToggle.querySelector('span').textContent = 'Hide Filters';
          mobileIcon?.classList.add('rotate-180');
        }
      });
    }
  }

  /**
   * Setup filter toggle buttons
   */
  setupFilterToggles() {
    document.addEventListener('click', function(e) {
      if (e.target.closest('.filter-toggle')) {
        const btn = e.target.closest('.filter-toggle');
        const target = document.getElementById(btn.dataset.target);
        const minusIcon = btn.querySelector('.minus-icon');
        const plusIcon = btn.querySelector('.plus-icon');

        if (target) {
          const isCollapsed = target.classList.contains('collapsed');

          if (isCollapsed) {
            target.classList.remove('collapsed');
            minusIcon?.classList.remove('hidden');
            plusIcon?.classList.add('hidden');
          } else {
            target.classList.add('collapsed');
            minusIcon?.classList.add('hidden');
            plusIcon?.classList.remove('hidden');
          }
        }
      }
    });
  }

  /**
   * Bind filter checkbox events
   */
  bindFilterEvents() {
    this.filters.chemistryFilters.forEach(checkbox => {
      checkbox.addEventListener('change', () => {
        this.filters.handleFilterChange('chemistry', checkbox.value, checkbox.checked);
        this.resetAndFetch('filter');
        this.filters.updateClearAllButton();
      });
    });

    this.filters.brandFilters.forEach(checkbox => {
      checkbox.addEventListener('change', () => {
        this.filters.handleFilterChange('brand', checkbox.value, checkbox.checked);
        this.resetAndFetch('filter');
        this.filters.updateClearAllButton();
      });
    });

    this.filters.applicationFilters.forEach(checkbox => {
      checkbox.addEventListener('change', () => {
        this.filters.handleFilterChange('applications', checkbox.value, checkbox.checked);
        this.resetAndFetch('filter');
        this.filters.updateClearAllButton();
      });
    });

    if (this.filters.searchForm) {
      this.filters.searchForm.addEventListener('submit', (e) => {
        e.preventDefault();
        this.filters.handleSearchChange();
        this.resetAndFetch('search');
        this.filters.updateClearAllButton();
      });
    }

    if (this.filters.searchInput) {
      let searchTimeout;
      this.filters.searchInput.addEventListener('input', () => {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(() => {
          this.filters.handleSearchChange();
          this.resetAndFetch('search');
          this.filters.updateClearAllButton();
        }, 500);
      });
    }

    if (this.filters.clearAllBtn) {
      this.filters.clearAllBtn.addEventListener('click', () => {
        this.filters.clearAllFilters();
        this.resetAndFetch('clear');
        this.filters.updateClearAllButton();
      });
    }

    this.filters.removeFilterBtns.forEach(btn => {
      btn.addEventListener('click', () => {
        const filterType = btn.dataset.removeFilter;
        const filterValue = btn.dataset.filterValue;
        this.filters.removeFilter(filterType, filterValue);
        this.resetAndFetch('remove');
        this.filters.updateClearAllButton();
      });
    });
  }

  /**
   * Bind sort and view mode events
   */
  bindSortAndViewEvents() {
    if (this.filters.sortSelect) {
      this.filters.sortSelect.addEventListener('change', () => {
        this.filters.state.sort = this.filters.sortSelect.value;
        this.resetAndFetch('sort');
      });
    }

    this.filters.viewModeBtns.forEach(btn => {
      btn.addEventListener('click', () => {
        this.filters.state.view = btn.dataset.view;
        this.filters.updateViewModeButtons();
        this.filters.updateGridLayout(this.grid);
        this.resetAndFetch('view');
      });
    });

    if (this.loadBtn) {
      this.loadBtn.addEventListener('click', () => this.loadMoreProducts());
    }
  }

  /**
   * Reset filters and fetch new products
   */
  resetAndFetch(origin = 'filter') {
    if (this.endMsg) this.endMsg.classList.add('hidden');
    if (this.loadBtn) {
      this.loadBtn.disabled = false;
      this.loadBtn.classList.remove('opacity-50', 'cursor-not-allowed');
    }

    this.performRequest({
      perPage: this.initialPP,
      offset: 0,
      replace: true,
      origin
    });
  }

  /**
   * Load more products
   */
  loadMoreProducts() {
    if (this.isLoading || this.isLoadingMore || !this.grid) return;

    this.isLoadingMore = true;

    const uniqueIds = new Set(
      Array.from(this.grid.querySelectorAll('article')).map(article => article.dataset.id)
    );

    this.performRequest({
      perPage: this.loadPP,
      offset: uniqueIds.size,
      replace: false,
      origin: 'loadmore'
    });
  }

  /**
   * Set loading state UI
   */
  setLoadingState(isLoading, origin = 'filter') {
    this.isLoading = isLoading;
    const isLoadMore = origin === 'loadmore';

    if (this.loader) {
      this.loader.classList.toggle('hidden', !isLoading || isLoadMore);
    }

    if (this.grid) {
      if (isLoading && !isLoadMore) {
        const currentHeight = this.grid.offsetHeight;
        this.grid.style.minHeight = `${currentHeight}px`;
        this.grid.style.opacity = '0.6';
      }
      this.grid.classList.toggle('hidden', isLoading && !isLoadMore);
    }

    if (this.loadBtn) {
      this.loadBtn.disabled = isLoading;
      const spinner = this.loadBtn.querySelector('svg');
      const label = this.loadBtn.querySelector('.btn-text');

      if (spinner) spinner.classList.toggle('hidden', !(isLoading && isLoadMore));
      if (label) label.textContent = (isLoading && isLoadMore) ? 'Loading...' : 'Load More Products';

      this.loadBtn.classList.toggle('opacity-50', isLoading && !isLoadMore);
      this.loadBtn.classList.toggle('cursor-not-allowed', isLoading && !isLoadMore);
    }
  }

  /**
   * Perform AJAX request
   */
  performRequest({ perPage, offset, replace, origin = 'filter' }) {
    if (this.isLoading) return;

    this.setLoadingState(true, origin);

    const params = this.filters.getFilterParams();
    params.append('action', 'resplast_filter_products');
    params.append('nonce', this.nonce);
    params.append('posts_per_page', perPage);
    params.append('offset', offset);

    fetch(this.ajaxUrl, {
      method: 'POST',
      headers: { 'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8' },
      body: params.toString()
    })
    .then(response => response.json())
    .then(data => {
      if (!data || !data.success) {
        throw new Error('Invalid response');
      }
      this.handleResponse(data.data, replace, origin);
    })
    .catch(() => {
      this.showError('Failed to load products. Please try again.');
    })
    .finally(() => {
      this.setLoadingState(false, origin);
    });
  }

  /**
   * Handle AJAX response
   */
  handleResponse(data, replace, origin) {
    const html = (data.html || '').trim();
    const total = Number(data.total || 0);
    const hasMore = Boolean(data.has_more);

    const existingIds = new Set(
      Array.from(this.grid.querySelectorAll('article')).map(article => article.dataset.id)
    );

    if (replace) {
      this.grid.innerHTML = html || this.getNoResultsHTML();
      this.grid.style.minHeight = '';
      this.grid.style.opacity = '';
    } else if (html) {
      const temp = document.createElement('div');
      temp.innerHTML = html;

      const newArticles = Array.from(temp.querySelectorAll('article')).filter(article => {
        const id = article.dataset.id;
        if (!id || existingIds.has(id)) return false;
        existingIds.add(id);
        return true;
      });

      newArticles.forEach(article => this.grid.appendChild(article));
    }

    this.isLoadingMore = false;

    const currentShowing = this.grid ? this.grid.querySelectorAll('article').length : 0;
    this.updateResultsText(total, currentShowing);

    if (!hasMore || !html) {
      this.loadBtn.disabled = true;
      this.loadBtn.classList.add('hidden', 'opacity-50', 'cursor-not-allowed');
      if (!replace && this.endMsg) this.endMsg.classList.remove('hidden');
    } else {
      this.loadBtn.disabled = false;
      this.loadBtn.classList.remove('opacity-50', 'cursor-not-allowed', 'hidden');
      if (this.endMsg) this.endMsg.classList.add('hidden');
    }

    if (data.filter_counts) {
      this.filters.updateFilterCounts(data.filter_counts);
    }

    this.refreshScrollSmoother(replace);
    this.modal.reinitIfOpen();
  }

  /**
   * Update results text
   */
  updateResultsText(total, showing) {
    if (this.resultsText) {
      this.resultsText.textContent = `Showing ${showing} of ${total} products`;
    }
  }

  /**
   * Show error message
   */
  showError(message) {
    const errorSVG = '<svg class="mx-auto h-12 w-12 text-red-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>';
    this.grid.innerHTML = '<div class="col-span-full text-center text-red-500 py-20">' +
      errorSVG +
      '<h3 class="mt-2 text-lg font-medium text-red-900">Error</h3>' +
      '<p class="mt-1 text-red-600">' + message + '</p>' +
      '</div>';
  }

  /**
   * Get no results HTML
   */
  getNoResultsHTML() {
    const noResultsSVG = '<svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>';
    return '<div class="col-span-full text-center text-gray-500 py-20">' +
      noResultsSVG +
      '<h3 class="mt-2 text-lg font-medium text-gray-900">No products found</h3>' +
      '<p class="mt-1 text-gray-500">Try adjusting your filters or search terms.</p>' +
      '</div>';
  }

  /**
   * Refresh ScrollSmoother
   */
  refreshScrollSmoother(isContentReplacement = false) {
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
          if (isContentReplacement) {
            setTimeout(() => {
              try {
                smoother.refresh();
                if (window.gsap?.ScrollTrigger) {
                  window.gsap.ScrollTrigger.refresh();
                }
              } catch (e) {}
            }, 50);
          }
          break;
        }
      } catch (e) {}
    }
  }
}
