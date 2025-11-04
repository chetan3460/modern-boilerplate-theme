/**
 * Reports Category Sorting Component
 * Integrates with ReportsFilter to provide per-category sorting
 */

export default class ReportsCategorySort {
  constructor(el, config = {}) {
    this.element = el;
    this.config = config;
    this.sortConfig = {};
    console.log('[ReportsCategorySort] Constructor called with element:', el?.className);
    this.init();
  }

  init() {
    // If element is the reports section, use it directly
    // If element is something else, find the reports section
    if (this.element?.classList?.contains('reports-investor-section')) {
      this.reportsSection = this.element;
    } else if (this.element?.closest?.('.reports-investor-section')) {
      this.reportsSection = this.element.closest('.reports-investor-section');
    } else {
      this.reportsSection = document.querySelector('.reports-investor-section');
    }
    
    if (!this.reportsSection) {
      console.log('[ReportsCategorySort] Section not found');
      return;
    }
    
    console.log('[ReportsCategorySort] Found section:', this.reportsSection.className);
  

    // Parse sort configuration from data attribute
    this.parseSortConfig();
    console.log('[ReportsCategorySort] Initialized. Config:', this.sortConfig);

    // Setup event listeners for category changes
    this.setupCategoryListeners();
    
    // Apply initial sort
    this.applyInitialSort();
  }

  /**
   * Parse sort configuration from data attribute
   */
  parseSortConfig() {
    const configStr = this.reportsSection.getAttribute('data-sort-config');
    if (!configStr) {
      console.log('[ReportsCategorySort] No sort config found');
      return;
    }

    try {
      this.sortConfig = JSON.parse(configStr);
    } catch (e) {
      console.error('[ReportsCategorySort] Failed to parse sort config:', e);
    }
  }

  /**
   * Setup event listeners for category filter changes
   * Uses MutationObserver to avoid conflicts with ReportsFilter
   */
  setupCategoryListeners() {
    // Use MutationObserver to watch for changes in active category
    const observer = new MutationObserver(() => {
      const activeFilter = this.reportsSection.querySelector('.category-filter.active');
      if (activeFilter) {
        const categorySlug = activeFilter.getAttribute('data-category');
        console.log('[ReportsCategorySort] Active category detected:', categorySlug);
        setTimeout(() => this.applySortForCategory(categorySlug), 100);
      }
    });
    
    observer.observe(this.reportsSection, {
      attributes: true,
      subtree: true,
      attributeFilter: ['class']
    });
    
    console.log('[ReportsCategorySort] MutationObserver set up');
  }

  /**
   * Apply initial sort based on active category
   */
  applyInitialSort() {
    const activeFilter = this.reportsSection.querySelector('.category-filter.active');
    if (activeFilter) {
      const categorySlug = activeFilter.getAttribute('data-category');
      console.log('[ReportsCategorySort] Applying initial sort for:', categorySlug);
      this.applySortForCategory(categorySlug);
    }
  }

  /**
   * Apply sort configuration for a specific category
   */
  applySortForCategory(categorySlug) {
    const config = this.sortConfig[categorySlug];
    if (!config) {
      console.log('[ReportsCategorySort] No config for category:', categorySlug);
      return;
    }

    const grid = this.reportsSection.querySelector('#reports-grid');
    if (!grid) {
      console.log('[ReportsCategorySort] Reports grid not found');
      return;
    }

    const reports = Array.from(grid.querySelectorAll('[data-report-id]'));
    console.log('[ReportsCategorySort] Found', reports.length, 'reports');
    
    // Filter by category
    const categoryReports = reports.filter(report => {
      const categories = report.getAttribute('data-categories') || '';
      return categories.includes(categorySlug) || categorySlug === 'all';
    });

    console.log('[ReportsCategorySort] Filtering', categoryReports.length, 'reports for category:', categorySlug);

    // Sort based on configuration
    this.sortReports(categoryReports, config);

    // Re-insert sorted reports into DOM
    categoryReports.forEach(report => {
      grid.appendChild(report);
    });

    console.log('[ReportsCategorySort] Applied sort:', config.sort_by);
  }

  /**
   * Sort reports based on configuration
   */
  sortReports(reports, config) {
    const sortBy = config.sort_by || 'date_desc';

    reports.sort((a, b) => {
      switch (sortBy) {
        case 'date_asc':
          return new Date(a.getAttribute('data-date')) - new Date(b.getAttribute('data-date'));
        
        case 'date_desc':
          return new Date(b.getAttribute('data-date')) - new Date(a.getAttribute('data-date'));
        
        case 'title_asc':
          return a.getAttribute('data-title').localeCompare(b.getAttribute('data-title'));
        
        case 'title_desc':
          return b.getAttribute('data-title').localeCompare(a.getAttribute('data-title'));
        
        case 'year_desc':
          // Sort by financial year descending (latest year first)
          const aYears = a.getAttribute('data-years') || '';
          const bYears = b.getAttribute('data-years') || '';
          const aYear = this.extractYear(aYears);
          const bYear = this.extractYear(bYears);
          return bYear - aYear;
        
        case 'year_asc':
          // Sort by financial year ascending (oldest year first)
          const aYearsAsc = a.getAttribute('data-years') || '';
          const bYearsAsc = b.getAttribute('data-years') || '';
          const aYearAsc = this.extractYear(aYearsAsc);
          const bYearAsc = this.extractYear(bYearsAsc);
          return aYearAsc - bYearAsc;
        
        case 'custom':
          const orderA = parseInt(a.getAttribute('data-sort-order')) || Infinity;
          const orderB = parseInt(b.getAttribute('data-sort-order')) || Infinity;
          return orderA - orderB;
        
        default:
          return 0;
      }
    });
  }

  /**
   * Extract year from financial year string (e.g., "FY2023-24" → 2023)
   */
  extractYear(yearsStr) {
    if (!yearsStr) return 0;
    const match = yearsStr.match(/\d{4}/);
    return match ? parseInt(match[0]) : 0;
  }
}
