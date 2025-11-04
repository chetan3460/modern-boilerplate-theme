<?php

/**
 * Block Name: Reports & Investor Updates (CPT Version)
 * Description: Display reports from Reports Custom Post Type with advanced filtering and pagination
 */

// Load sorting helper
if (!function_exists('resplast_sort_reports_by_category')) {
  require_once get_template_directory() . '/inc/reports-sorting-helper.php';
}

// Get block fields
$hide_block = get_sub_field('hide_block');
if ($hide_block) return;

$main_title = get_sub_field('main_title') ?: 'Reports & investor updates';
$main_description = get_sub_field('main_description') ?: 'Access annual reports, notices, policies, and disclosures that reflect RPL\'s commitment to transparency and accountability.';

// Get block options
$show_all_reports = get_sub_field('show_all_reports');
$show_search = get_sub_field('show_search');

// Set defaults if fields don't exist yet
if ($show_all_reports === null) $show_all_reports = true; // Default to true
if ($show_search === null) $show_search = true; // Default to true

// Get all report categories, financial years, and quarters for filters
$categories = get_terms(array(
  'taxonomy' => 'report_category',
  'hide_empty' => false,
));

$financial_years = get_terms(array(
  'taxonomy' => 'financial_year',
  'hide_empty' => false,
  'orderby' => 'name',
  'order' => 'DESC'
));

$quarters = get_terms(array(
  'taxonomy' => 'quarter',
  'hide_empty' => false,
));

// Get all reports (we'll handle filtering via JavaScript)
$reports_query = new WP_Query(array(
  'post_type' => 'reports',
  'posts_per_page' => -1,
  'post_status' => 'publish',
  'orderby' => 'menu_order',
  'order' => 'ASC'
));

$all_reports = $reports_query->posts;

// Get sort configuration as JSON for frontend
$sort_config_json = resplast_get_sort_config_json($categories);
?>

<section class="reports-investor-section fade-in" data-component="ReportsFilter" data-load="eager">
  <div class="container-fluid">
    <?php
    // Include header partial
    include get_template_directory() . '/templates/partials/reports-block/header.php';
    ?>

    <!-- Main Layout -->
    <div class="grid lg:grid-cols-4 gap-1 lg:gap-8 bg-sky-50  rounded-2xl">
      <?php
      // Include categories sidebar partial
      include get_template_directory() . '/templates/partials/reports-block/categories-sidebar.php';
      ?>
      <!-- Main Content Area -->
      <div class="lg:col-span-3 p-4 lg:p-6">
        <?php
        // Include search bar partial
        include get_template_directory() . '/templates/partials/reports-block/search-bar.php';
        ?>

        <?php
        // Include filters partial
        include get_template_directory() . '/templates/partials/reports-block/filters.php';
        ?>

        <!-- Reports Grid -->
        <?php if (!empty($all_reports)): ?>
          <div class="grid grid-cols-1 md:grid-cols-2 gap-4 lg:gap-6" id="reports-grid">
            <?php foreach ($all_reports as $index => $report): ?>
              <?php
              // Add initial visibility class
              $visibility_class = $index >= 6 ? ' hidden' : '';
              $report->visibility_class = $visibility_class;

              // Include report card partial
              include get_template_directory() . '/templates/partials/reports-block/report-card.php';
              ?>
            <?php endforeach; ?>
          </div>

          <!-- Infinite Scroll Trigger (hidden by JavaScript) -->
          <?php if (count($all_reports) > 6): ?>
            <div class="text-center mt-8" id="load-more-section">
              <button id="load-more-btn" class="inline-flex items-center px-6 py-3 bg-transparent text-primary font-medium rounded-full  transition-colors duration-200">
                <span>View more</span>
                <svg class="w-4 h-4 ml-2 transition-transform duration-200" id="load-more-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                </svg>
              </button>
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
          <h3 class="text-xl font-semibold text-black mb-2">No Results Found</h3>
          <p class="text-black mb-4">Try adjusting your filters or search terms.</p>
          <button id="clear-filters" class="inline-flex items-center px-4 py-2 bg-transparent text-primary rounded-lg  transition-colors">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
            Clear All Filters
          </button>
        </div>
      </div>
    </div>
  </div>
</section>