/**
 * Reports Filter Component
 * Handles filtering, searching, sorting, and pagination for the Reports & Investor Updates section
 */

export default class ReportsFilter {
  constructor(el, config = {}) {
    this.element = el;
    this.config = config;
    this.init();
  }

  init() {
    // Use the passed element or find the reports section
    this.reportsSection = this.element || document.querySelector('.reports-investor-section');
    if (!this.reportsSection) {
      return; // Exit if reports section is not found
    }

    // Cache DOM elements (scoped to the component element)
    this.categoryFilters = this.reportsSection.querySelectorAll('.category-filter');
    this.yearFilters = this.reportsSection.querySelectorAll('#year-filter');
    this.quarterFilters = this.reportsSection.querySelectorAll('#quarter-filter');
    this.sortFilter = null;
    this.searchInput = this.reportsSection.querySelector('#search-reports');
    this.reportCards = this.reportsSection.querySelectorAll('.report-card');
    this.resultsCount = this.reportsSection.querySelector('#results-count');
    this.showingCount = this.reportsSection.querySelector('#showing-count');
    this.noResultsMessage = this.reportsSection.querySelector('#no-results-found');
    this.clearFiltersBtn = this.reportsSection.querySelector('#clear-filters');
    this.reportsGrid = this.reportsSection.querySelector('#reports-grid');
    this.loadMoreBtn = this.reportsSection.querySelector('#load-more-btn');
    this.loadMoreSection = this.reportsSection.querySelector('#load-more-section');
    
    // Mobile categories dropdown (we'll listen for events instead of direct element)
    this.mobileCategoriesDropdown = this.reportsSection.querySelector('[data-component="MobileCategoriesDropdown"]');

    // Parse sort configuration for categories
    // this.sortConfig = this.parseSortConfig();
    this.sortConfig = {};

    // State variables
    this.activeCategory = this.getDefaultCategorySlug(); // Set appropriate default category
    this.activeYear = '';
    this.activeQuarter = '';
    this.currentSort = 'newest';
    this.searchTerm = '';
    this.itemsPerPage = 6;
    this.currentlyVisible = 6;
    this.allReports = Array.from(this.reportCards);
    this.isLoading = false;

    // Initialize the system
    this.initCustomDropdowns();
    this.setupEventListeners();
    this.setInitialCategoryState();
    // Apply initial sorting based on active category before displaying
    // this.applyCategorySorting();
    this.updateFilterVisibility();
    this.filterAndDisplayReports();

  }

  initCustomDropdowns() {
    // Initialize custom dropdowns for desktop within the reports section
    const dropdowns = this.reportsSection.querySelectorAll('[data-dd]');
    
    dropdowns.forEach(dropdown => {
      const button = dropdown.querySelector('button');
      const menu = dropdown.querySelector('.dd-menu');
      const items = dropdown.querySelectorAll('.dd-item');
      const label = button.querySelector('.dd-label');
      const filterType = dropdown.getAttribute('data-dd');
      
      
      // Button click handler
      button.addEventListener('click', (e) => {
        e.preventDefault();
        e.stopPropagation();
        
        // Close all other dropdowns
        document.querySelectorAll('.dd-menu').forEach(otherMenu => {
          if (otherMenu !== menu) {
            otherMenu.classList.add('hidden');
            otherMenu.parentElement.querySelector('button').setAttribute('aria-expanded', 'false');
          }
        });
        
        // Toggle this dropdown
        const isOpen = !menu.classList.contains('hidden');
        menu.classList.toggle('hidden');
        button.setAttribute('aria-expanded', !isOpen);
      });
      
      // Fix dropdown scrolling by preventing smooth scroll interference
      menu.addEventListener('wheel', (e) => {
        e.stopPropagation();
        // Allow native scrolling within the dropdown
        const { scrollTop, scrollHeight, clientHeight } = menu;
        
        // Only prevent default if we're not at scroll boundaries
        if ((e.deltaY < 0 && scrollTop > 0) || (e.deltaY > 0 && scrollTop < scrollHeight - clientHeight)) {
          // Let the dropdown scroll naturally without interference
        }
      }, { passive: true });
      
      // Prevent touch scroll issues on mobile
      menu.addEventListener('touchmove', (e) => {
        e.stopPropagation();
      }, { passive: true });
      
      // Item click handlers
      items.forEach(item => {
        item.addEventListener('click', () => {
          const value = item.dataset.value;
          const text = item.textContent;
          
          // Update button text
          label.textContent = text;
          
          // Add selected class for styling (unless it's a default/empty value)
          if (value && value !== '') {
            label.classList.add('selected');
          } else {
            label.classList.remove('selected');
          }
          
          // Update hidden select and trigger change based on type
          if (filterType === 'year') {
            this.activeYear = value;
            // Sync all year filters and their labels
            this.yearFilters.forEach(filter => {
              filter.value = value;
            });
            // Sync labels across all year dropdowns
            document.querySelectorAll('[data-dd="year"] .dd-label').forEach(yearLabel => {
              yearLabel.textContent = text;
              if (value && value !== '') {
                yearLabel.classList.add('selected');
              } else {
                yearLabel.classList.remove('selected');
              }
            });
          } else if (filterType === 'quarter') {
            this.activeQuarter = value;
            // Sync all quarter filters and their labels
            this.quarterFilters.forEach(filter => {
              filter.value = value;
            });
            // Sync labels across all quarter dropdowns
            document.querySelectorAll('[data-dd="quarter"] .dd-label').forEach(quarterLabel => {
              quarterLabel.textContent = text;
              if (value && value !== '') {
                quarterLabel.classList.add('selected');
              } else {
                quarterLabel.classList.remove('selected');
              }
            });
          } else if (false) {
            this.currentSort = value;
    if (false) {
              this.sortFilter.value = value;
            }
            // Sync labels across all sort dropdowns
            document.querySelectorAll('[data-dd="sort"] .dd-label').forEach(sortLabel => {
              sortLabel.textContent = text;
              if (value && value !== 'newest') { // 'newest' is default, so don't mark as selected
                sortLabel.classList.add('selected');
              } else {
                sortLabel.classList.remove('selected');
              }
            });
          }
          
          // Close dropdown
          menu.classList.add('hidden');
          button.setAttribute('aria-expanded', 'false');
          
          // Apply filters
          this.resetPagination();
          this.filterAndDisplayReports();
        });
      });
    });
    
    // Close dropdowns when clicking outside
    document.addEventListener('click', (e) => {
      if (!e.target.closest('[data-dd], [data-dd-mobile]')) {
        document.querySelectorAll('.dd-menu').forEach(menu => {
          menu.classList.add('hidden');
          menu.parentElement.querySelector('button').setAttribute('aria-expanded', 'false');
        });
      }
    });
  }


  setupEventListeners() {
    // Category filter buttons
    this.categoryFilters.forEach(button => {
      button.addEventListener('click', (e) => {
        e.preventDefault();
        this.activeCategory = button.dataset.category;
        this.updateCategoryUI(button);
        
        // Apply category-specific sorting
        // setTimeout(() => this.applyCategorySorting(), 50);
        
        // Update mobile dropdown selection to match desktop selection
        const mobileDropdown = document.querySelector('[data-component="MobileCategoriesDropdown"]');
        if (mobileDropdown) {
          const dropdownItem = mobileDropdown.querySelector(`[data-category="${this.activeCategory}"]`);
          if (dropdownItem) {
            dropdownItem.classList.add('active', 'text-white', 'bg-primary');
            dropdownItem.classList.remove('text-black');
            const selectedText = mobileDropdown.querySelector('#selected-category');
            if (selectedText) {
              selectedText.textContent = dropdownItem.textContent.trim();
              selectedText.classList.remove('text-gray-400');
              selectedText.classList.add('text-primary');
            }
          }
        }
        
        this.resetPagination();
        this.filterAndDisplayReports();
      });
    });

    // Listen for mobile category change events on both reports section and document
    const handleMobileCategoryChange = (e) => {
      
      this.activeCategory = e.detail.category;
      
      // Update desktop category buttons to match mobile selection
      let foundMatchingButton = false;
      this.categoryFilters.forEach(btn => {
        btn.classList.remove('active');
        btn.classList.add('font-normal');
        btn.classList.remove('font-semibold');
        
        if (btn.dataset.category === this.activeCategory) {
          btn.classList.add('active');
          btn.classList.add('font-normal');
          btn.classList.remove('font-semibold');
          foundMatchingButton = true;
        }
      });
      
      if (!foundMatchingButton) {
      }
      
      this.resetPagination();
      this.filterAndDisplayReports();
    };
    
    // Add listeners to both reports section and document for maximum compatibility
    this.reportsSection.addEventListener('mobileCategory:change', handleMobileCategoryChange);
    document.addEventListener('mobileCategory:change', handleMobileCategoryChange);
    

    // Note: Year, Quarter, and Sort filters are now handled by the custom dropdown system in initCustomDropdowns()

    // Search input with debounce
    if (this.searchInput) {
      this.searchInput.addEventListener('input', this.debounce(() => {
        this.searchTerm = this.searchInput.value.toLowerCase().trim();
        this.resetPagination();
        this.filterAndDisplayReports();
      }, 300));
    }

    // Load more button
    if (this.loadMoreBtn) {
      this.loadMoreBtn.addEventListener('click', () => {
        this.loadMoreReports();
      });
    }

    // Clear filters button
    if (this.clearFiltersBtn) {
      this.clearFiltersBtn.addEventListener('click', () => {
        this.resetAllFilters();
      });
    }
  }

  updateCategoryUI(activeBtn) {
    this.categoryFilters.forEach(btn => {
      btn.classList.remove('active', 'font-semibold');
      btn.classList.add('font-normal');
      const arrow = btn.querySelector('.category-arrow');
      if (arrow) arrow.classList.add('hidden');
    });
    activeBtn.classList.add('active', 'font-semibold');
    activeBtn.classList.remove('font-normal');
    const activeArrow = activeBtn.querySelector('.category-arrow');
    if (activeArrow) activeArrow.classList.remove('hidden');
  }


  resetPagination() {
    this.currentlyVisible = this.itemsPerPage;
  }

  getDefaultCategorySlug() {
    // Check if 'All Reports' option exists (first check for data-category="all")
    const allReportsButton = this.reportsSection ? this.reportsSection.querySelector('.category-filter[data-category="all"]') : null;
    if (allReportsButton) {
      return 'all'; // Default to 'All Reports' if available
    }
    
    // Otherwise, get the first available category
    const firstCategoryButton = this.reportsSection ? this.reportsSection.querySelector('.category-filter') : null;
    return firstCategoryButton ? firstCategoryButton.dataset.category : '';
  }
  
  setInitialCategoryState() {
    // Check if there's already an active category button (from PHP server-side rendering)
    let defaultButton = this.reportsSection.querySelector('.category-filter.active');
    
    if (defaultButton) {
      // If PHP already set an active button, use that
      this.activeCategory = defaultButton.dataset.category;
    } else {
      // Fallback: determine default category programmatically
      // First try to find the 'all' button if that's what we want
      if (this.activeCategory === 'all') {
        defaultButton = this.reportsSection.querySelector('.category-filter[data-category="all"]');
      }
      
      // If no 'all' button found or active category is not 'all', get first available category
      if (!defaultButton && this.categoryFilters.length > 0) {
        defaultButton = this.categoryFilters[0];
        this.activeCategory = defaultButton.dataset.category; // Update active category to match
      }
      
      // Apply styling if we had to determine it programmatically
      if (defaultButton) {
        this.updateCategoryUI(defaultButton);
      }
    }
    
    // Mobile dropdown will be updated via component communication
    // No direct DOM manipulation needed
    
  }

  resetAllFilters() {
    this.activeCategory = this.getDefaultCategorySlug();
    this.activeYear = '';
    this.activeQuarter = '';
    this.currentSort = 'newest';
    this.searchTerm = '';
    
    // Update UI elements
    this.setInitialCategoryState();
    
    // Reset hidden selects
    this.yearFilters.forEach(filter => {
      filter.value = '';
    });
    this.quarterFilters.forEach(filter => {
      filter.value = '';
    });
    if (this.sortFilter) this.sortFilter.value = 'newest';
    if (this.searchInput) this.searchInput.value = '';
    
    // Reset custom dropdown labels
    document.querySelectorAll('[data-dd="year"] .dd-label').forEach(label => {
      label.textContent = 'Financial Year';
      label.classList.remove('selected');
    });
    document.querySelectorAll('[data-dd="quarter"] .dd-label').forEach(label => {
      label.textContent = 'Quarter';
      label.classList.remove('selected');
    });
    document.querySelectorAll('[data-dd="sort"] .dd-label').forEach(label => {
      label.textContent = 'Sort';
      label.classList.remove('selected');
    });
    
    this.resetPagination();
    this.filterAndDisplayReports();
  }

  filterAndDisplayReports() {
    // Get filtered reports
    let filteredReports = this.allReports.filter(card => {
      const cardCategories = (card.dataset.categories || '').split(',').filter(cat => cat.trim());
      const cardYears = (card.dataset.years || '').split(',').filter(year => year.trim());
      const cardQuarters = (card.dataset.quarters || '').split(',').filter(quarter => quarter.trim());
      const cardTitle = card.dataset.title || '';
      
      // Category filter
      if (this.activeCategory !== 'all' && !cardCategories.includes(this.activeCategory)) {
        return false;
      }
      
      // Year filter
      if (this.activeYear && !cardYears.includes(this.activeYear)) {
        return false;
      }
      
      // Quarter filter
      if (this.activeQuarter && !cardQuarters.includes(this.activeQuarter)) {
        return false;
      }
      
      // Search filter
      if (this.searchTerm && !cardTitle.toLowerCase().includes(this.searchTerm.toLowerCase())) {
        return false;
      }
      
      return true;
    });
    
    // Sort the filtered reports
    const sortedReports = filteredReports;
    
    // Hide all reports initially
    this.allReports.forEach(card => {
      card.style.display = 'none';
      card.classList.add('hidden');
    });
    
    const totalFiltered = sortedReports.length;
    const showingCount = Math.min(this.currentlyVisible, totalFiltered);
    
    // Show only the reports that should be visible
    sortedReports.slice(0, showingCount).forEach(card => {
      card.style.display = 'block';
      card.classList.remove('hidden');
    });
    
    // Update UI elements
    this.updateResultsCount(showingCount, totalFiltered);
    this.updateLoadMoreButton(showingCount, totalFiltered);
    this.updateCategoryCounts();
    this.updateFilterVisibility();
    this.showNoResultsMessage(totalFiltered === 0);
  }


  // sortReports function - DISABLED (sorting functionality commented out)
  // sortReports(reports) {
  //   return reports;
  //   return reports.sort((a, b) => {
  //     switch (this.currentSort) {
  //       case 'newest':
  //         const dateA = new Date(a.dataset.date || '1970-01-01');
  //         const dateB = new Date(b.dataset.date || '1970-01-01');
  //         return dateB - dateA;
  //         
  //       case 'oldest':
  //         const dateAOld = new Date(a.dataset.date || '1970-01-01');
  //         const dateBOld = new Date(b.dataset.date || '1970-01-01');
  //         return dateAOld - dateBOld;
  //         
  //       case 'title-asc':
  //         const titleA = (a.dataset.title || '').toLowerCase();
  //         const titleB = (b.dataset.title || '').toLowerCase();
  //         return titleA.localeCompare(titleB);
  //         
  //       case 'title-desc':
  //         const titleADesc = (a.dataset.title || '').toLowerCase();
  //         const titleBDesc = (b.dataset.title || '').toLowerCase();
  //         return titleBDesc.localeCompare(titleADesc);
  //         
  //       case 'featured':
  //         const featuredA = parseInt(a.dataset.featured || '0');
  //         const featuredB = parseInt(b.dataset.featured || '0');
  //         if (featuredA !== featuredB) {
  //           return featuredB - featuredA; // Featured first
  //         }
  //         // If both are same featured status, sort by newest
  //         const dateFeatA = new Date(a.dataset.date || '1970-01-01');
  //         const dateFeatB = new Date(b.dataset.date || '1970-01-01');
  //         return dateFeatB - dateFeatA;
  //         
  //       default:
  //         return 0;
  //     }
  //   });
  // }

  updateResultsCount(showing, total) {
    // Update desktop results count
    if (this.showingCount) {
      this.showingCount.textContent = showing;
    }
    if (this.resultsCount) {
      const totalText = total + ' Report' + (total !== 1 ? 's' : '');
      this.resultsCount.innerHTML = `Showing <span id="showing-count">${showing}</span> of ${totalText}`;
    }
    
    // Update mobile results count
    const mobileShowingCount = this.reportsSection.querySelector('#showing-count-mobile');
    const mobileResultsCount = this.reportsSection.querySelector('#results-count-mobile');
    
    if (mobileShowingCount) {
      mobileShowingCount.textContent = showing;
    }
    if (mobileResultsCount) {
      const totalText = total + ' Report' + (total !== 1 ? 's' : '');
      mobileResultsCount.innerHTML = `Showing <span id="showing-count-mobile">${showing}</span> of ${totalText}`;
    }
  }


  updateLoadMoreButton(showing, total) {
    if (this.loadMoreSection) {
      if (showing < total) {
        this.loadMoreSection.style.display = 'block';
        const remaining = total - showing;
        const nextBatch = Math.min(this.itemsPerPage, remaining);
        const btnText = this.loadMoreBtn.querySelector('span');
        if (btnText) {
          btnText.textContent = `Load ${nextBatch} More Report${nextBatch !== 1 ? 's' : ''}`;
        }
      } else {
        this.loadMoreSection.style.display = 'none';
      }
    }
  }

  updateCategoryCounts() {
    // Update category counts based on current filters (excluding the category filter itself)
    this.categoryFilters.forEach(button => {
      const category = button.dataset.category;
      const countSpan = button.querySelector('span[id*="count-"]');
      
      if (countSpan) {
        let count;
        
        if (category === 'all') {
          // For "All Reports", count all reports that match non-category filters
          count = this.allReports.filter(card => {
            const cardYears = (card.dataset.years || '').split(',').filter(year => year.trim());
            const cardQuarters = (card.dataset.quarters || '').split(',').filter(quarter => quarter.trim());
            const cardTitle = (card.dataset.title || '').toLowerCase();
            const cardContent = (card.dataset.content || '').toLowerCase();
            
            // Apply non-category filters
            if (this.activeYear && !cardYears.includes(this.activeYear)) return false;
            if (this.activeQuarter && !cardQuarters.includes(this.activeQuarter)) return false;
            if (this.searchTerm && !cardTitle.includes(this.searchTerm) && !cardContent.includes(this.searchTerm)) return false;
            
            return true;
          }).length;
        } else {
          // For specific categories, count reports that match this category + other filters
          count = this.allReports.filter(card => {
            const cardCategories = (card.dataset.categories || '').split(',').filter(cat => cat.trim());
            const cardYears = (card.dataset.years || '').split(',').filter(year => year.trim());
            const cardQuarters = (card.dataset.quarters || '').split(',').filter(quarter => quarter.trim());
            const cardTitle = (card.dataset.title || '').toLowerCase();
            const cardContent = (card.dataset.content || '').toLowerCase();
            
            // Must match this category
            if (!cardCategories.includes(category)) return false;
            
            // Apply non-category filters
            if (this.activeYear && !cardYears.includes(this.activeYear)) return false;
            if (this.activeQuarter && !cardQuarters.includes(this.activeQuarter)) return false;
            if (this.searchTerm && !cardTitle.includes(this.searchTerm) && !cardContent.includes(this.searchTerm)) return false;
            
            return true;
          }).length;
        }
        
        countSpan.textContent = count;
      }
    });
  }

  updateFilterVisibility() {
    // Get reports for current category (excluding year/quarter filters)
    let categoryReports = this.allReports.filter(card => {
      const cardCategories = (card.dataset.categories || '').split(',').filter(cat => cat.trim());
      const cardTitle = card.dataset.title || '';
      
      // Category filter
      if (this.activeCategory !== 'all' && !cardCategories.includes(this.activeCategory)) {
        return false;
      }
      
      // Search filter
      if (this.searchTerm && !cardTitle.toLowerCase().includes(this.searchTerm.toLowerCase())) {
        return false;
      }
      
      return true;
    });
    
    // Check if any category report has a year
    const yearFilterContainer = this.reportsSection.querySelector('#year-filter-container');
    const hasYears = categoryReports.some(card => {
      const years = (card.dataset.years || '').split(',').filter(y => y.trim());
      return years.length > 0;
    });
    if (yearFilterContainer) {
      yearFilterContainer.style.display = hasYears ? 'block' : 'none';
    }
    
    // Check if any category report has a quarter
    const quarterFilterContainer = this.reportsSection.querySelector('#quarter-filter-container');
    const hasQuarters = categoryReports.some(card => {
      const quarters = (card.dataset.quarters || '').split(',').filter(q => q.trim());
      return quarters.length > 0;
    });
    if (quarterFilterContainer) {
      quarterFilterContainer.style.display = hasQuarters ? 'block' : 'none';
    }
  }

  showNoResultsMessage(show) {
    if (this.noResultsMessage && this.reportsGrid) {
      if (show) {
        this.reportsGrid.style.display = 'none';
        this.noResultsMessage.classList.remove('hidden');
        if (this.loadMoreSection) this.loadMoreSection.style.display = 'none';
      } else {
        this.reportsGrid.style.display = 'grid';
        this.noResultsMessage.classList.add('hidden');
      }
    }
  }

  loadMoreReports() {
    if (this.isLoading) {
      return; // Prevent multiple simultaneous loads
    }
    
    this.isLoading = true;
    
    // Add a small delay to show loading state if needed
    setTimeout(() => {
      const lastVisible = this.currentlyVisible;
      this.currentlyVisible += this.itemsPerPage;
      
      // Show next batch of cards with animation
      const cards = Array.from(this.reportCards);
      cards.slice(lastVisible, this.currentlyVisible).forEach(card => {
        card.style.opacity = '0';
        card.classList.remove('hidden');
        
        // Fade in with requestAnimationFrame for smooth transition
        requestAnimationFrame(() => {
          card.style.transition = 'opacity 0.3s ease-in-out';
          card.style.opacity = '1';
        });
      });
      
      this.filterAndDisplayReports();
      this.isLoading = false;
      
    }, 150); // Small delay to prevent rapid loading
  }

  debounce(func, wait) {
    let timeout;
    return function executedFunction(...args) {
      const later = () => {
        clearTimeout(timeout);
        func.apply(this, args);
      };
      clearTimeout(timeout);
      timeout = setTimeout(later, wait);
    };
  }

  // Public API for debugging
  getState() {
    return {
      activeCategory: this.activeCategory,
      activeYear: this.activeYear,
      activeQuarter: this.activeQuarter,
      currentSort: this.currentSort,
      searchTerm: this.searchTerm,
      totalReports: this.allReports.length,
      currentlyVisible: this.currentlyVisible
    };
  }

  resetAllFiltersPublic() {
    this.resetAllFilters();
  }

  filterAndDisplayReportsPublic() {
    this.filterAndDisplayReports();
  }

  /**
   * Parse sort configuration from data attribute
   * DISABLED FOR NOW - will enable when implementing expandable categories
   */
  // parseSortConfig() {
  //   const configStr = this.reportsSection?.getAttribute('data-sort-config');
  //   if (!configStr) return {};
  //   try {
  //     return JSON.parse(configStr);
  //   } catch (e) {
  //     console.error('[ReportsFilter] Failed to parse sort config:', e);
  //     return {};
  //   }
  // }

  /**
   * Apply category-specific sorting to all reports
   * DISABLED FOR NOW - will enable when implementing expandable categories
   */
  // applyCategorySorting() {
  //   const config = this.sortConfig[this.activeCategory];
  //   if (!config) return;
  //   const sortBy = config.sort_by || 'date_desc';
  //   const categoryReports = this.allReports.filter(report => {
  //     const categories = report.getAttribute('data-categories') || '';
  //     return this.activeCategory === 'all' || categories.includes(this.activeCategory);
  //   });
  //   categoryReports.sort((a, b) => {
  //     const aOrder = parseInt(a.getAttribute('data-custom-order')) || null;
  //     const bOrder = parseInt(b.getAttribute('data-custom-order')) || null;
  //     if (aOrder !== null && bOrder !== null) return aOrder - bOrder;
  //     if (aOrder !== null) return -1;
  //     if (bOrder !== null) return 1;
  //     switch (sortBy) {
  //       case 'date_asc':
  //         return new Date(a.getAttribute('data-date')) - new Date(b.getAttribute('data-date'));
  //       case 'date_desc':
  //         return new Date(b.getAttribute('data-date')) - new Date(a.getAttribute('data-date'));
  //       case 'title_asc':
  //         return a.getAttribute('data-title').localeCompare(b.getAttribute('data-title'));
  //       case 'title_desc':
  //         return b.getAttribute('data-title').localeCompare(a.getAttribute('data-title'));
  //       case 'year_desc': {
  //         const aYear = this.extractYear(a.getAttribute('data-years') || '');
  //         const bYear = this.extractYear(b.getAttribute('data-years') || '');
  //         return bYear - aYear;
  //       }
  //       case 'year_asc': {
  //         const aYear = this.extractYear(a.getAttribute('data-years') || '');
  //         const bYear = this.extractYear(b.getAttribute('data-years') || '');
  //         return aYear - bYear;
  //       }
  //       default: return 0;
  //     }
  //   });
  //   const nonCategoryReports = this.allReports.filter(report => {
  //     const categories = report.getAttribute('data-categories') || '';
  //     return this.activeCategory !== 'all' && !categories.includes(this.activeCategory);
  //   });
  //   this.allReports = [...categoryReports, ...nonCategoryReports];
  //   if (this.reportsGrid) {
  //     this.resetPagination();
  //     this.filterAndDisplayReports();
  //   }
  // }

  /**
   * Extract year from financial year string
   * DISABLED FOR NOW - will enable when implementing expandable categories
   */
  // extractYear(yearsStr) {
  //   if (!yearsStr) return 0;
  //   const match = yearsStr.match(/\d{4}/);
  //   return match ? parseInt(match[0]) : 0;
  // }
}
