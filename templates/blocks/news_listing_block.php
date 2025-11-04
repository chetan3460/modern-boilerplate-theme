<?php

/**
 * News Listing Block Template
 * 
 * @package Resplast
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

// Get block fields
$hide_block = get_sub_field('hide_block');
if ($hide_block) {
    return;
}

$section_title = get_sub_field('section_title') ?: 'All News';
$posts_per_page = get_sub_field('posts_per_page') ?: 6;
$show_filters = get_sub_field('show_filters');
$show_search = get_sub_field('show_search');

// Generate unique ID for this block
$block_id = 'news-listing-' . uniqid();
?>

<section id="<?php echo esc_attr($block_id); ?>" class="news-listing-block py-12 lg:py-20 bg-gray-50">
    <div class="container mx-auto px-4">
        <!-- Section Header -->
        <div class="text-center mb-12">
            <h2 class="fade-text">
                <?php echo esc_html($section_title); ?>
            </h2>
        </div>

        <!-- Filters and Search -->
        <?php if ($show_filters || $show_search): ?>
            <div class="mb-8 bg-white rounded-2xl p-6 shadow-sm">
                <div class="flex flex-col lg:flex-row gap-4 items-center justify-between">

                    <?php if ($show_search): ?>
                        <!-- Search -->
                        <div class="w-full lg:w-auto">
                            <div class="relative">
                                <input type="text"
                                    id="news-search-<?php echo esc_attr($block_id); ?>"
                                    class="w-full lg:w-80 px-4 py-3 pr-12 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                    placeholder="Search news...">
                                <div class="absolute inset-y-0 right-0 flex items-center pr-3">
                                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                    </svg>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>

                    <?php if ($show_filters): ?>
                        <!-- Category Filter -->
                        <div class="w-full lg:w-auto">
                            <?php
                            $news_taxonomy = taxonomy_exists('news_category') ? 'news_category' : 'category';
                            $categories = get_terms([
                                'taxonomy' => $news_taxonomy,
                                'hide_empty' => true
                            ]);

                            if ($categories && !is_wp_error($categories)): ?>
                                <select id="news-category-<?php echo esc_attr($block_id); ?>"
                                    class="w-full lg:w-auto px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-white">
                                    <option value="all">All Categories</option>
                                    <?php foreach ($categories as $category): ?>
                                        <option value="<?php echo esc_attr($category->slug); ?>">
                                            <?php echo esc_html($category->name); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>

                    <!-- Sort Options -->
                    <div class="w-full lg:w-auto">
                        <select id="news-sort-<?php echo esc_attr($block_id); ?>"
                            class="w-full lg:w-auto px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-white">
                            <option value="newest">Newest First</option>
                            <option value="oldest">Oldest First</option>
                        </select>
                    </div>
                </div>
            </div>
        <?php endif; ?>

        <!-- News Grid -->
        <div id="news-grid-<?php echo esc_attr($block_id); ?>"
            class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 mb-8">
            <!-- News cards will be loaded here via AJAX -->
        </div>

        <!-- Loading State -->
        <div id="news-loading-<?php echo esc_attr($block_id); ?>"
            class="hidden text-center py-8">
            <div class="inline-flex items-center px-4 py-2 text-sm font-medium text-gray-900">
                <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-gray-900" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                Loading news...
            </div>
        </div>

        <!-- Load More Button -->
        <div id="news-load-more-<?php echo esc_attr($block_id); ?>"
            class="text-center">
            <button class="load-more-btn px-8 py-3 bg-blue-600 text-white font-semibold rounded-xl hover:bg-blue-700 transition-colors duration-200">
                Load More News
            </button>
        </div>

        <!-- Empty State -->
        <div id="news-empty-<?php echo esc_attr($block_id); ?>"
            class="hidden text-center py-12">
            <div class="max-w-md mx-auto">
                <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9.5a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"></path>
                </svg>
                <h3 class="text-xl font-semibold text-gray-900 mb-2">No news found</h3>
                <p class="text-gray-600">Try adjusting your search or filter criteria.</p>
            </div>
        </div>
    </div>

    <!-- JavaScript for AJAX functionality -->
    <script type="text/javascript">
        document.addEventListener('DOMContentLoaded', function() {
            const blockId = '<?php echo esc_js($block_id); ?>';
            const newsGrid = document.getElementById('news-grid-' + blockId);
            const loadingEl = document.getElementById('news-loading-' + blockId);
            const loadMoreBtn = document.getElementById('news-load-more-' + blockId);
            const emptyEl = document.getElementById('news-empty-' + blockId);
            const searchInput = document.getElementById('news-search-' + blockId);
            const categorySelect = document.getElementById('news-category-' + blockId);
            const sortSelect = document.getElementById('news-sort-' + blockId);

            let currentOffset = 0;
            const postsPerPage = <?php echo absint($posts_per_page); ?>;

            // Initial load
            loadNews(true);

            // Event listeners
            if (searchInput) {
                let searchTimeout;
                searchInput.addEventListener('input', function() {
                    clearTimeout(searchTimeout);
                    searchTimeout = setTimeout(() => loadNews(true), 500);
                });
            }

            if (categorySelect) {
                categorySelect.addEventListener('change', () => loadNews(true));
            }

            if (sortSelect) {
                sortSelect.addEventListener('change', () => loadNews(true));
            }

            if (loadMoreBtn) {
                loadMoreBtn.addEventListener('click', () => loadNews(false));
            }

            function loadNews(reset = false) {
                if (reset) {
                    currentOffset = 0;
                    newsGrid.innerHTML = '';
                }

                showLoading();

                const formData = new FormData();
                formData.append('action', 'resplast_news_query');
                formData.append('nonce', '<?php echo wp_create_nonce('resplast_news_nonce'); ?>');
                formData.append('posts_per_page', postsPerPage);
                formData.append('offset', currentOffset);
                formData.append('search', searchInput ? searchInput.value : '');
                formData.append('category', categorySelect ? categorySelect.value : 'all');
                formData.append('sort', sortSelect ? sortSelect.value : 'newest');

                fetch('<?php echo admin_url('admin-ajax.php'); ?>', {
                        method: 'POST',
                        body: formData
                    })
                    .then(response => response.json())
                    .then(data => {
                        hideLoading();

                        if (data.success) {
                            if (reset && data.data.empty) {
                                showEmpty();
                                return;
                            }

                            hideEmpty();

                            if (data.data.html) {
                                newsGrid.insertAdjacentHTML('beforeend', data.data.html);
                            }

                            currentOffset += data.data.returned;

                            // Show/hide load more button
                            if (data.data.has_more) {
                                loadMoreBtn.parentElement.classList.remove('hidden');
                            } else {
                                loadMoreBtn.parentElement.classList.add('hidden');
                            }
                        } else {
                            console.error('Error loading news:', data.data);
                        }
                    })
                    .catch(error => {
                        hideLoading();
                        console.error('Network error:', error);
                    });
            }

            function showLoading() {
                loadingEl.classList.remove('hidden');
                if (loadMoreBtn) loadMoreBtn.disabled = true;
            }

            function hideLoading() {
                loadingEl.classList.add('hidden');
                if (loadMoreBtn) loadMoreBtn.disabled = false;
            }

            function showEmpty() {
                emptyEl.classList.remove('hidden');
                loadMoreBtn.parentElement.classList.add('hidden');
            }

            function hideEmpty() {
                emptyEl.classList.add('hidden');
            }
        });
    </script>
</section>