<?php

/**
 * =============================================================================
 * PRODUCT LISTING SECTION
 * =============================================================================
 * 
 * This template displays the main product listing with advanced filtering.
 * Features:
 * - Three main filter categories: Chemistry, Brand, Applications
 * - Real-time AJAX filtering with Tailwind CSS
 * - Responsive grid layout
 * - Search functionality
 * - Multiple sort options
 * - URL management for shareable filters
 * 
 * Dependencies:
 * - Custom taxonomies: product_chemistry, product_brand, product_application
 * - Custom post type: products
 * - ProductListing JavaScript component
 * - ACF fields for product specifications
 * =============================================================================
 */

// Get filter parameters from URL
$selected_chemistry = isset($_GET['chemistry']) ? array_filter(explode(',', sanitize_text_field($_GET['chemistry']))) : [];
$selected_brand = isset($_GET['brand']) ? array_filter(explode(',', sanitize_text_field($_GET['brand']))) : [];
$selected_applications = isset($_GET['applications']) ? array_filter(explode(',', sanitize_text_field($_GET['applications']))) : [];
$search_q = isset($_GET['s']) ? sanitize_text_field($_GET['s']) : '';
$sort = isset($_GET['sort']) ? sanitize_text_field($_GET['sort']) : 'name';
$view_mode = isset($_GET['view']) ? sanitize_text_field($_GET['view']) : 'list';
$ppp = 5; // Initial number of products to show

$section_id = 'products-section-' . uniqid();
$nonce = wp_create_nonce('resplast_products_nonce');

// Build initial query for products
$args = [
  'post_type'      => 'product',
  'post_status'    => 'publish',
  'posts_per_page' => $ppp,
  'paged'          => 1,
  'no_found_rows'  => false,
  's'              => $search_q,
];

// Add sorting
switch ($sort) {
  case 'newest':
    $args['orderby'] = 'date';
    $args['order'] = 'DESC';
    break;
  case 'oldest':
    $args['orderby'] = 'date';
    $args['order'] = 'ASC';
    break;
  case 'name':
  default:
    $args['orderby'] = 'title';
    $args['order'] = 'ASC';
    break;
}

// Build tax_query for filters
$tax_query = ['relation' => 'AND'];

if (!empty($selected_chemistry)) {
  $tax_query[] = [
    'taxonomy' => 'product_chemistry',
    'field'    => 'slug',
    'terms'    => $selected_chemistry,
    'operator' => 'IN'
  ];
}

if (!empty($selected_brand)) {
  $tax_query[] = [
    'taxonomy' => 'product_brand',
    'field'    => 'slug',
    'terms'    => $selected_brand,
    'operator' => 'IN'
  ];
}

if (!empty($selected_applications)) {
  $tax_query[] = [
    'taxonomy' => 'product_application',
    'field'    => 'slug',
    'terms'    => $selected_applications,
    'operator' => 'IN'
  ];
}

if (!empty($tax_query) && count($tax_query) > 1) {
  $args['tax_query'] = $tax_query;
}

$products_query = new WP_Query($args);
$max_pages = (int) $products_query->max_num_pages;

// Get filter counts for all taxonomies
function get_taxonomy_terms_with_counts($taxonomy, $selected_terms = [])
{
  $terms = get_terms([
    'taxonomy'   => $taxonomy,
    'hide_empty' => true,
    'orderby'    => 'name',
    'order'      => 'ASC',
  ]);

  if (is_wp_error($terms)) {
    return [];
  }

  return $terms;
}

?>

<section id="<?php echo esc_attr($section_id); ?>"
  class="products-listing-section  fade-in"
  data-component="ProductListing"
  data-load="eager"
  data-ajax-url="<?php echo esc_url(admin_url('admin-ajax.php')); ?>"
  data-nonce="<?php echo esc_attr($nonce); ?>"
  data-initial-ppp="<?php echo esc_attr($ppp); ?>"
  data-load-ppp="3">

  <div class="container-fluid ">

    <!-- Section Heading -->


    <!-- Main Content Layout -->
    <div class="flex flex-col lg:flex-row gap-4 bg-sky-50/60 rounded-3xl">

      <!-- Filter Sidebar - Hidden on mobile by default, shows as modal popup -->
      <aside class="mobile-filter-modal lg:block lg:w-80 flex-shrink-0 p-6 border-r border-grey-4" id="mobile-filter-modal">
        <?php include locate_template('templates/products/filter-sidebar.php', false, false); ?>
      </aside>

      <!-- Main Content Area -->
      <main class="flex-1 p-4 lg:p-6">

        <!-- Top Controls Bar -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 pb-4">

          <!-- Results Counter -->
          <div id="<?php echo esc_attr($section_id); ?>-results-text"
            class="text-sm text-grey-1 font-semibold w-full"
            data-base-text="Showing all products ">
            <?php
            $total_found = $products_query->found_posts;
            $showing_count = min($ppp, $total_found);
            echo "Showing {$showing_count} of {$total_found} products";
            ?>
          </div>

          <!-- Top Controls -->
          <div class="flex items-center gap-4 flex-wrap justify-end w-full ">

            <!-- Search Box -->
            <div class="relative flex items-center gap-2 w-full lg:w-80">
              <form id="<?php echo esc_attr($section_id); ?>-search-form" class="relative flex items-center h-10 rounded-[12px] p-4 w-full bg-white">
                <input id="<?php echo esc_attr($section_id); ?>-search"
                  type="search"
                  placeholder="Search products"
                  value="<?php echo esc_attr($search_q); ?>"
                  class="w-full text-sm focus:outline-none bg-white" />
                <svg class="" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20" fill="none">
                  <g clip-path="url(#clip0_836_5965)">
                    <path d="M13.9766 13.7676L18.307 18.098" stroke="#DA000E" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                    <path d="M9.55664 15.5977C13.0084 15.5977 15.8066 12.7994 15.8066 9.34766C15.8066 5.89588 13.0084 3.09766 9.55664 3.09766C6.10486 3.09766 3.30664 5.89588 3.30664 9.34766C3.30664 12.7994 6.10486 15.5977 9.55664 15.5977Z" stroke="black" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                  </g>
                  <defs>
                    <clipPath id="clip0_836_5965">
                      <rect width="20" height="20" fill="white" />
                    </clipPath>
                  </defs>
                </svg>
              </form>

              <!-- Filter button outside search input -->

              <button type="button"
                class="w-10 h-10 lg:hidden inline-flex items-center justify-center p-3  rounded-[8px] bg-white"
                id="mobile-filter-btn">
                <img src="<?php echo get_template_directory_uri(); ?>/assets/images/icons/filter.svg" alt="">
              </button>
            </div>

            <!-- Sort Dropdown (hidden) -->
            <div class="relative hidden" data-dd="sort">
              <button type="button"
                class="inline-flex items-center gap-2 px-4 py-2 border border-gray-300 rounded-full text-sm font-medium bg-white text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent"
                aria-haspopup="listbox"
                aria-expanded="false">
                <span class="dd-label">
                  <?php
                  switch ($sort) {
                    case 'newest':
                      echo 'Newest First';
                      break;
                    case 'oldest':
                      echo 'Oldest First';
                      break;
                    case 'name':
                    default:
                      echo 'Sort by Name';
                      break;
                  }
                  ?>
                </span>
                <svg class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                  <path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 10.94l3.71-3.71a.75.75 0 111.06 1.06l-4.24 4.25a.75.75 0 01-1.06 0L5.21 8.29a.75.75 0 01-1.08z" clip-rule="evenodd" />
                </svg>
              </button>
              <ul class="dd-menu absolute right-0 z-20 mt-2 w-48 bg-white border border-gray-200 rounded-lg shadow-lg p-1 max-h-64 overflow-auto hidden">
                <li class="dd-item px-3 py-2 rounded-md text-sm text-gray-700 hover:bg-red-50 hover:text-red-600 cursor-pointer" data-value="name">Sort by Name</li>
                <li class="dd-item px-3 py-2 rounded-md text-sm text-gray-700 hover:bg-red-50 hover:text-red-600 cursor-pointer" data-value="newest">Newest First</li>
                <li class="dd-item px-3 py-2 rounded-md text-sm text-gray-700 hover:bg-red-50 hover:text-red-600 cursor-pointer" data-value="oldest">Oldest First</li>
              </ul>
            </div>
            <select id="<?php echo esc_attr($section_id); ?>-sort" class="hidden" aria-hidden="true" disabled>
              <option value="name" <?php selected('name', $sort); ?>>Sort by Name</option>
              <option value="newest" <?php selected('newest', $sort); ?>>Newest First</option>
              <option value="oldest" <?php selected('oldest', $sort); ?>>Oldest First</option>
            </select>

            <!-- View Mode Toggle (hidden) -->
            <div class="flex border border-gray-300 rounded-lg overflow-hidden hidden">
              <button type="button"
                class="view-mode-btn px-3 py-2 text-sm font-medium <?php echo $view_mode === 'grid' ? 'bg-red-600 text-white' : 'bg-white text-gray-700 hover:bg-gray-50'; ?>"
                data-view="grid">
                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                  <path d="M5 3a2 2 0 00-2 2v2a2 2 0 002 2h2a2 2 0 002-2V5a2 2 0 00-2-2H5zM5 11a2 2 0 00-2 2v2a2 2 0 002 2h2a2 2 0 002-2v-2a2 2 0 00-2-2H5zM11 5a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V5zM11 13a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path>
                </svg>
              </button>
              <button type="button"
                class="view-mode-btn px-3 py-2 text-sm font-medium <?php echo $view_mode === 'list' ? 'bg-red-600 text-white' : 'bg-white text-gray-700 hover:bg-gray-50'; ?>"
                data-view="list">
                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                  <path fill-rule="evenodd" d="M3 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z" clip-rule="evenodd"></path>
                </svg>
              </button>
            </div>

          </div>
        </div>

        <!-- Loading State -->
        <div id="<?php echo esc_attr($section_id); ?>-loader" class="hidden flex justify-center items-center py-12">
          <div class="flex items-center space-x-3">
            <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-red-600"></div>
            <span class="text-gray-600">Loading products...</span>
          </div>
        </div>

        <!-- Products Grid -->
        <div id="<?php echo esc_attr($section_id); ?>-grid"
          class="<?php echo $view_mode === 'list' ? 'space-y-6' : 'grid gap-6 grid-cols-1 lg:grid-cols-2'; ?>"
          data-view="<?php echo esc_attr($view_mode); ?>">
          <?php
          if ($products_query->have_posts()) {
            while ($products_query->have_posts()) {
              $products_query->the_post();
              // Include the appropriate card template based on view mode
              if ($view_mode === 'list') {
                get_template_part('templates/products/card-list');
              } else {
                get_template_part('templates/products/card-grid');
              }
            }
            wp_reset_postdata();
          } else {
            echo '<div class="col-span-full text-center text-gray-500 py-20">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    <h3 class="mt-2 text-lg font-medium text-gray-900">No products found</h3>
                    <p class="mt-1 text-gray-500">Try adjusting your filters or search terms.</p>
                  </div>';
          }
          ?>
        </div>

        <!-- Load More Button -->
        <div class="mt-12 flex justify-center">
          <button id="<?php echo esc_attr($section_id); ?>-load"
            class="flex items-center   text-primary rounded-full font-medium  transition-colors duration-200 <?php echo ($products_query->found_posts > 5) ? '' : 'hidden'; ?>">
            <svg class="hidden animate-spin -ml-1 mr-2 h-4 w-4" fill="none" viewBox="0 0 24 24">
              <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
              <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"></path>
            </svg>
            <span class="btn-text">Load More Products</span>
          </button>
        </div>

        <!-- End of Results Message -->
        <div id="<?php echo esc_attr($section_id); ?>-end" class="hidden mt-8 text-center text-gray-500">
          <p class="font-semibold">You've reached the end of our product catalog</p>
        </div>

      </main>

    </div> <!-- End main layout -->

  </div> <!-- End container -->

</section>

<?php
// Add hidden form elements for JavaScript
?>
<script type="text/javascript">
  window.productFilters = {
    chemistry: <?php echo json_encode($selected_chemistry); ?>,
    brand: <?php echo json_encode($selected_brand); ?>,
    applications: <?php echo json_encode($selected_applications); ?>,
    search: <?php echo json_encode($search_q); ?>,
    sort: <?php echo json_encode($sort); ?>,
    view: <?php echo json_encode($view_mode); ?>
  };
</script>