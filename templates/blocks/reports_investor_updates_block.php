<?php
/**
 * Block Name: Reports & Investor Updates Block
 * Description: Display filterable reports and documents with category sidebar and download functionality
 */

// Get block fields
$hide_block = get_sub_field('hide_block');
if ($hide_block) return;

$main_title = get_sub_field('main_title') ?: 'Reports & investor updates';
$main_description = get_sub_field('main_description') ?: 'Access annual reports, notices, policies, and disclosures that reflect RPL\'s commitment to transparency and accountability.';

$categories = get_sub_field('categories') ?: [];
$filter_options = get_sub_field('filter_options') ?: [];
$reports = get_sub_field('reports') ?: [];
?>

<section class="reports-investor-section gsap-ignore fade-in" data-smooth-ignore="true">
    <div class="container-fluid">
        <!-- Header Section -->
        <div class="section-heading text-center !max-w-none">
            <h2 class="fade-text"><?php echo esc_html($main_title); ?></h2>
            <div class="description-content prose !max-w-none">
                <p><?php echo esc_html($main_description); ?></p>
            </div>
        </div>

        <!-- Main Content Layout -->
        <div class="grid lg:grid-cols-4 gap-8">
            <!-- Sidebar Categories -->
            <div class="lg:col-span-1">
                <div class="bg-gray-50 rounded-2xl p-6">
                    <h3 class="font-semibold text-lg text-black mb-4">Categories</h3>
                    
                    <?php if (!empty($categories)): ?>
                    <nav class="space-y-2">
                        <!-- All Categories (default active) -->
                        <button class="category-filter w-full text-left px-4 py-3 rounded-lg transition-colors duration-200 bg-red-600 text-white font-medium" 
                                data-category="all">
                            All Reports
                        </button>
                        
                        <?php foreach ($categories as $category): ?>
                        <button class="category-filter w-full text-left px-4 py-3 rounded-lg transition-colors duration-200 hover:bg-gray-200 text-gray-700 <?php echo $category['is_active'] ? 'bg-red-600 text-white font-medium' : ''; ?>" 
                                data-category="<?php echo esc_attr(strtolower(str_replace(' ', '-', $category['category_name']))); ?>">
                            <?php echo esc_html($category['category_name']); ?>
                            <?php if ($category['is_active']): ?>
                            <svg class="inline-block w-4 h-4 ml-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                            </svg>
                            <?php endif; ?>
                        </button>
                        <?php endforeach; ?>
                    </nav>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Main Content Area -->
            <div class="lg:col-span-3">
                <!-- Search Bar -->
                <div class="mb-6">
                    <div class="relative max-w-md">
                        <input type="text" 
                               id="search-reports" 
                               placeholder="Search reports by title or description..."
                               class="w-full px-4 py-3 pl-10 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <div class="absolute inset-y-0 left-0 flex items-center pl-3">
                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Filters and Results Count -->
                <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-6">
                    <div class="mb-4 md:mb-0">
                        <span class="text-gray-600" id="results-count">
                            Showing <span id="showing-count"><?php echo min(12, count($reports)); ?></span> of <?php echo count($reports); ?> Reports
                        </span>
                    </div>
                    
                    <!-- Filter Dropdowns -->
                    <div class="flex flex-col sm:flex-row gap-4">
                        <div class="flex items-center gap-2">
                            <span class="text-sm font-medium text-gray-700">Filters</span>
                        </div>
                        
                        <!-- Sort By Dropdown -->
                        <div class="relative">
                            <select id="sort-filter" class="appearance-none bg-white border border-gray-300 rounded-lg px-4 py-2 pr-8 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <option value="newest">Newest First</option>
                                <option value="oldest">Oldest First</option>
                                <option value="title-asc">Title A-Z</option>
                                <option value="title-desc">Title Z-A</option>
                            </select>
                            <svg class="absolute right-2 top-1/2 transform -translate-y-1/2 w-4 h-4 text-gray-400 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </div>
                        
                        <!-- Financial Year Filter -->
                        <div class="relative" id="year-filter-container" style="display: none;">
                            <select id="year-filter" class="appearance-none bg-white border border-gray-300 rounded-lg px-4 py-2 pr-8 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <option value="">Financial Year</option>
                                <?php if (isset($filter_options['financial_years']) && !empty($filter_options['financial_years'])): ?>
                                    <?php foreach ($filter_options['financial_years'] as $year): ?>
                                    <option value="<?php echo esc_attr($year['year']); ?>">
                                        <?php echo esc_html($year['year']); ?>
                                    </option>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </select>
                            <svg class="absolute right-2 top-1/2 transform -translate-y-1/2 w-4 h-4 text-gray-400 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </div>

                        <!-- Quarter Filter -->
                        <div class="relative" id="quarter-filter-container" style="display: none;">
                            <select id="quarter-filter" class="appearance-none bg-white border border-gray-300 rounded-lg px-4 py-2 pr-8 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <option value="">Quarter</option>
                                <?php if (isset($filter_options['quarters']) && !empty($filter_options['quarters'])): ?>
                                    <?php foreach ($filter_options['quarters'] as $quarter): ?>
                                    <option value="<?php echo esc_attr($quarter['quarter']); ?>">
                                        <?php echo esc_html($quarter['quarter']); ?>
                                    </option>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </select>
                            <svg class="absolute right-2 top-1/2 transform -translate-y-1/2 w-4 h-4 text-gray-400 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Reports Grid -->
                <?php if (!empty($reports)): ?>
                <div class="grid md:grid-cols-2 gap-6" id="reports-grid">
                    <?php foreach ($reports as $index => $report): ?>
                    <div class="report-card <?php echo $index >= 12 ? 'hidden' : ''; ?> bg-white rounded-2xl p-6 border hover:shadow-lg transition-shadow duration-200" 
                         data-category="<?php echo esc_attr(strtolower(str_replace(' ', '-', $report['category']))); ?>"
                         data-year="<?php echo esc_attr($report['financial_year']); ?>"
                         data-quarter="<?php echo esc_attr($report['quarter']); ?>"
                         data-title="<?php echo esc_attr(strtolower($report['title'])); ?>"
                         data-published="<?php echo esc_attr($report['published_date']); ?>"
                         data-index="<?php echo $index; ?>">
                        
                        <!-- Document Icon -->
                        <div class="flex items-start gap-4 mb-4">
                            <div class="flex-shrink-0 w-10 h-10 bg-red-50 rounded-lg flex items-center justify-center">
                                <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                            </div>
                            <div class="flex-1">
                                <h4 class="font-semibold text-gray-900 mb-2 leading-snug">
                                    <?php echo esc_html($report['title']); ?>
                                </h4>
                                <p class="text-sm text-gray-600 mb-4">
                                    Published on <?php echo esc_html($report['published_date']); ?>
                                </p>
                            </div>
                        </div>

                        <!-- Download Button -->
                        <?php
                        $download_url = '';
                        if (!empty($report['document_file']) && is_array($report['document_file'])) {
                            $download_url = $report['document_file']['url'];
                        } elseif (!empty($report['download_link'])) {
                            $download_url = $report['download_link'];
                        }
                        ?>
                        
                        <?php if ($download_url): ?>
                        <a href="<?php echo esc_url($download_url); ?>" 
                           class="inline-flex items-center text-red-600 hover:text-red-700 font-medium transition-colors duration-200"
                           download
                           target="_blank">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            Download
                        </a>
                        <?php else: ?>
                        <span class="text-gray-400 text-sm">Download not available</span>
                        <?php endif; ?>
                    </div>
                    <?php endforeach; ?>
                </div>
                
                <!-- Load More Button -->
                <?php if (count($reports) > 12): ?>
                <div class="text-center mt-8" id="load-more-section">
                    <button id="load-more-btn" class="inline-flex items-center px-6 py-3 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 transition-colors duration-200">
                        <span>Load More Reports</span>
                        <svg class="w-4 h-4 ml-2 transition-transform duration-200" id="load-more-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </button>
                    <p class="text-sm text-gray-500 mt-2">Showing 12 of <?php echo count($reports); ?> reports</p>
                </div>
                <?php endif; ?>
                <?php else: ?>
                <!-- No Reports State -->
                <div class="text-center py-16">
                    <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    <h3 class="text-xl font-semibold text-gray-900 mb-2">No Reports Available</h3>
                    <p class="text-gray-600">Reports and updates will be published here when available.</p>
                </div>
                <?php endif; ?>

                <!-- No Results Found Message -->
                <div id="no-results-found" class="hidden text-center py-16">
                    <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                    <h3 class="text-xl font-semibold text-gray-900 mb-2">No Results Found</h3>
                    <p class="text-gray-600 mb-4">Try adjusting your filters or category selection.</p>
                    <button id="clear-filters" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                        Clear All Filters
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Enhanced Filtering & Pagination JavaScript -->
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const categoryFilters = document.querySelectorAll('.category-filter');
        const yearFilter = document.getElementById('year-filter');
        const quarterFilter = document.getElementById('quarter-filter');
        const sortFilter = document.getElementById('sort-filter');
        const searchInput = document.getElementById('search-reports');
        const reportCards = document.querySelectorAll('.report-card');
        const resultsCount = document.getElementById('results-count');
        const showingCount = document.getElementById('showing-count');
        const noResultsMessage = document.getElementById('no-results-found');
        const clearFiltersBtn = document.getElementById('clear-filters');
        const reportsGrid = document.getElementById('reports-grid');
        const loadMoreBtn = document.getElementById('load-more-btn');
        const loadMoreSection = document.getElementById('load-more-section');

        let activeCategory = 'all';
        let activeYear = '';
        let activeQuarter = '';
        let currentSort = 'newest';
        let searchTerm = '';
        let itemsPerPage = 12;
        let currentlyVisible = 12;
        let allReports = Array.from(reportCards);
        
        const yearFilterContainer = document.getElementById('year-filter-container');
        const quarterFilterContainer = document.getElementById('quarter-filter-container');

        // Event handlers
        categoryFilters.forEach(button => {
            button.addEventListener('click', function() {
                activeCategory = this.dataset.category;
                updateCategoryUI(this);
                resetPagination();
                filterAndDisplayReports();
            });
        });

        if (yearFilter) {
            yearFilter.addEventListener('change', function() {
                activeYear = this.value;
                resetPagination();
                filterAndDisplayReports();
            });
        }

        if (quarterFilter) {
            quarterFilter.addEventListener('change', function() {
                activeQuarter = this.value;
                resetPagination();
                filterAndDisplayReports();
            });
        }

        if (sortFilter) {
            sortFilter.addEventListener('change', function() {
                currentSort = this.value;
                resetPagination();
                filterAndDisplayReports();
            });
        }

        if (searchInput) {
            searchInput.addEventListener('input', debounce(function() {
                searchTerm = this.value.toLowerCase().trim();
                resetPagination();
                filterAndDisplayReports();
            }, 300));
        }

        if (loadMoreBtn) {
            loadMoreBtn.addEventListener('click', function() {
                loadMoreReports();
            });
        }

        if (clearFiltersBtn) {
            clearFiltersBtn.addEventListener('click', function() {
                resetAllFilters();
            });
        }
        
        // Helper functions
        function updateCategoryUI(activeBtn) {
            categoryFilters.forEach(btn => {
                btn.classList.remove('bg-red-600', 'text-white', 'font-medium');
                btn.classList.add('text-gray-700', 'hover:bg-gray-200');
            });
            activeBtn.classList.add('bg-red-600', 'text-white', 'font-medium');
            activeBtn.classList.remove('text-gray-700', 'hover:bg-gray-200');
        }
        
        function resetPagination() {
            currentlyVisible = itemsPerPage;
        }
        
        function resetAllFilters() {
            activeCategory = 'all';
            activeYear = '';
            activeQuarter = '';
            currentSort = 'newest';
            searchTerm = '';
            
            // Reset UI
            if (categoryFilters[0]) updateCategoryUI(categoryFilters[0]);
            if (yearFilter) yearFilter.value = '';
            if (quarterFilter) quarterFilter.value = '';
            if (sortFilter) sortFilter.value = 'newest';
            if (searchInput) searchInput.value = '';
            
            resetPagination();
            filterAndDisplayReports();
        }

        function filterAndDisplayReports() {
            // Get filtered reports
            let filteredReports = getFilteredReports();
            
            // Sort reports
            filteredReports = sortReports(filteredReports);
            
            // Hide all cards first
            allReports.forEach(card => {
                card.style.display = 'none';
                card.classList.add('hidden');
            });
            
            // Show filtered and paginated results
            const totalFiltered = filteredReports.length;
            const showingCount = Math.min(currentlyVisible, totalFiltered);
            
            filteredReports.slice(0, currentlyVisible).forEach(card => {
                card.style.display = 'block';
                card.classList.remove('hidden');
            });
            
            // Update UI
            updateResultsCount(showingCount, totalFiltered);
            updateLoadMoreButton(showingCount, totalFiltered);
            showNoResultsMessage(totalFiltered === 0);
            updateFilterVisibility();
        }
        
        function updateFilterVisibility() {
            // Get reports for current category (excluding search/year/quarter filters)
            let categoryReports = allReports.filter(card => {
                const cardCategory = card.dataset.category;
                const cardTitle = card.dataset.title;
                
                // Category filter
                if (activeCategory !== 'all' && cardCategory !== activeCategory) {
                    return false;
                }
                
                // Search filter
                if (searchTerm && !cardTitle.includes(searchTerm)) {
                    return false;
                }
                
                return true;
            });
            
            // Check if any category report has a year
            const hasYears = categoryReports.some(card => !empty(card.dataset.year));
            if (yearFilterContainer) {
                yearFilterContainer.style.display = hasYears ? 'block' : 'none';
            }
            
            // Check if any category report has a quarter
            const hasQuarters = categoryReports.some(card => !empty(card.dataset.quarter));
            if (quarterFilterContainer) {
                quarterFilterContainer.style.display = hasQuarters ? 'block' : 'none';
            }
        }
        
        function empty(value) {
            return value === '' || value === null || value === undefined;
        }
        
        function getFilteredReports() {
            return allReports.filter(card => {
                const cardCategory = card.dataset.category;
                const cardYear = card.dataset.year;
                const cardQuarter = card.dataset.quarter;
                const cardTitle = card.dataset.title;
                
                // Category filter
                if (activeCategory !== 'all' && cardCategory !== activeCategory) {
                    return false;
                }
                
                // Year filter
                if (activeYear && cardYear !== activeYear) {
                    return false;
                }
                
                // Quarter filter
                if (activeQuarter && cardQuarter !== activeQuarter) {
                    return false;
                }
                
                // Search filter
                if (searchTerm && !cardTitle.includes(searchTerm)) {
                    return false;
                }
                
                return true;
            });
        }
        
        function sortReports(reports) {
            return reports.sort((a, b) => {
                switch (currentSort) {
                    case 'newest':
                        return parseInt(b.dataset.index) - parseInt(a.dataset.index);
                    case 'oldest':
                        return parseInt(a.dataset.index) - parseInt(b.dataset.index);
                    case 'title-asc':
                        return a.dataset.title.localeCompare(b.dataset.title);
                    case 'title-desc':
                        return b.dataset.title.localeCompare(a.dataset.title);
                    default:
                        return 0;
                }
            });
        }
        
        function updateResultsCount(showing, total) {
            if (showingCount) {
                showingCount.textContent = showing;
            }
            if (resultsCount) {
                const totalText = total + ' Report' + (total !== 1 ? 's' : '');
                resultsCount.innerHTML = `Showing <span id="showing-count">${showing}</span> of ${totalText}`;
            }
        }
        
        function updateLoadMoreButton(showing, total) {
            if (loadMoreSection) {
                if (showing < total) {
                    loadMoreSection.style.display = 'block';
                    const remaining = total - showing;
                    const nextBatch = Math.min(itemsPerPage, remaining);
                    loadMoreBtn.querySelector('span').textContent = `Load ${nextBatch} More Report${nextBatch !== 1 ? 's' : ''}`;
                } else {
                    loadMoreSection.style.display = 'none';
                }
            }
        }
        
        function showNoResultsMessage(show) {
            if (noResultsMessage && reportsGrid) {
                if (show) {
                    reportsGrid.style.display = 'none';
                    noResultsMessage.classList.remove('hidden');
                    if (loadMoreSection) loadMoreSection.style.display = 'none';
                } else {
                    reportsGrid.style.display = 'grid';
                    noResultsMessage.classList.add('hidden');
                }
            }
        }
        
        function loadMoreReports() {
            currentlyVisible += itemsPerPage;
            filterAndDisplayReports();
        }
        
        function debounce(func, wait) {
            let timeout;
            return function executedFunction(...args) {
                const later = () => {
                    clearTimeout(timeout);
                    func(...args);
                };
                clearTimeout(timeout);
                timeout = setTimeout(later, wait);
            };
        }
        
        // Initialize
        updateFilterVisibility();
        filterAndDisplayReports();
    });
    </script>
</section>