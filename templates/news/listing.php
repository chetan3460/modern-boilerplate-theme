<?php

/**
 * News Listing Section (no header/footer)
 * CPT: news
 * - Centered heading + subtitle
 * - Category and Sort filters
 * - 3/2/1 responsive grid
 * - AJAX “View More” pagination (falls back to initial server render)
 */

// Use news_category taxonomy specifically for news posts
$taxonomy = 'news_category';
$selected_cat = isset($_GET['cat']) ? sanitize_text_field($_GET['cat']) : 'all';
$sort = isset($_GET['sort']) ? sanitize_text_field($_GET['sort']) : '';
// Default to newest when no sort is specified
$order = 'DESC';
if ($sort === 'oldest') {
  $order = 'ASC';
}
$search_q = isset($_GET['s']) ? sanitize_text_field($_GET['s']) : '';
$ppp = 6; // posts per page per load

$section_id = 'news-section-' . uniqid();
$archive_link = get_post_type_archive_link('news');

// Labels for dropdowns
$sort_label = 'Sort'; // Default placeholder
if ($sort === 'oldest') {
  $sort_label = 'Oldest';
} elseif ($sort === 'newest') {
  $sort_label = 'Newest';
}
$cat_label  = 'All Categories';
if ($selected_cat !== 'all') {
  $sel_term = is_numeric($selected_cat)
    ? get_term_by('id', (int) $selected_cat, $taxonomy)
    : get_term_by('slug', $selected_cat, $taxonomy);
  if ($sel_term && !is_wp_error($sel_term)) {
    $cat_label = $sel_term->name;
  }
}

// Build initial query
$args = [
  'post_type'      => 'news',
  'post_status'    => 'publish',
  'orderby'        => 'date',
  'order'          => $order,
  'posts_per_page' => $ppp,
  'paged'          => 1,
  'no_found_rows'  => false,
  's'              => $search_q,
];

if (!empty($selected_cat) && $selected_cat !== 'all') {
  $term = is_numeric($selected_cat)
    ? get_term_by('id', (int) $selected_cat, $taxonomy)
    : get_term_by('slug', $selected_cat, $taxonomy);
  if ($term) {
    $args['tax_query'] = [
      [
        'taxonomy' => $taxonomy,
        'field'    => 'term_id',
        'terms'    => (int) $term->term_id,
      ],
    ];
  }
}

$q = new WP_Query($args);
$max_pages = (int) $q->max_num_pages;
$nonce = wp_create_nonce('resplast_news_nonce');

?>
<section id="<?php echo esc_attr($section_id); ?>" class="news-list-block fade-in" data-component="NewsListing" data-load="eager" data-ajax-url="<?php echo esc_url(admin_url('admin-ajax.php')); ?>" data-nonce="<?php echo esc_attr($nonce); ?>" data-taxonomy="<?php echo esc_attr($taxonomy); ?>" data-initial-ppp="<?php echo esc_attr($ppp); ?>" data-load-ppp="3">
  <div class="container-fluid relative overflow-hidden">

    <!-- Section Heading -->
    <div class="section-heading text-center mb-4 sm:mb-8">
      <?php
      // Get ACF fields from the current page (since archive is disabled)
      $queried_object = get_queried_object();
      $current_page_id = is_object($queried_object) && isset($queried_object->ID) ? $queried_object->ID : get_the_ID();

      $section_title = get_field('news_listing_title', $current_page_id) ?: 'Driving what\'s next';
      $section_description = get_field('news_listing_description', $current_page_id) ?: 'A look at our innovations, research milestones, and events that keep us ahead in a changing world.';
      ?>
      <?php if ($section_title): ?>
        <h2 class="mb-1 fade-text"><?php echo esc_html($section_title); ?></h2>
      <?php endif; ?>
      <?php if (!empty($section_description)): ?>
        <p><?php echo esc_html($section_description); ?></p>
      <?php endif; ?>
    </div>

    <!-- Filters row -->
    <div class="flex items-center justify-between gap-4 flex-wrap mb-8">
      <span id="<?php echo esc_attr($section_id); ?>-results-text" class="text-sm text-black font-semibold" data-base-text="Showing all results">
        <?php
        if ($selected_cat !== 'all' && !empty($cat_label) && $cat_label !== 'All Categories') {
          echo 'Showing all results ' . esc_html($cat_label);
        } else {
          echo 'Showing all results';
        }
        ?>
      </span>

      <div class="flex items-center gap-3 flex-wrap">
        <!-- Search -->
        <div class="hidden">
          <label class="sr-only" for="<?php echo esc_attr($section_id); ?>-search">Search news</label>
          <form id="<?php echo esc_attr($section_id); ?>-search-form" class="relative">
            <input id="<?php echo esc_attr($section_id); ?>-search" type="search" placeholder="Search news..." value="<?php echo isset($_GET['s']) ? esc_attr($_GET['s']) : ''; ?>" class="border border-gray-200 rounded-full px-4 py-2 text-sm bg-white focus:outline-none focus:ring-2 focus:ring-red-200 w-64 max-w-full" />
          </form>
        </div>

        <!-- Categories (Custom dropdown) -->
        <div class="relative" data-dd="cat">
          <button type="button" class="inline-flex items-center gap-2 border border-[#D6D6D6] !font-semibold rounded-[12px] px-4 py-3 text-xs !bg-white !text-grey-7 focus:outline-none hover:!bg-transparent" aria-haspopup="listbox" aria-expanded="false">
            <span class="dd-label"><?php echo esc_html($cat_label); ?></span>
            <svg class="h-4 w-4 text-black" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
              <path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 10.94l3.71-3.71a.75.75 0 111.06 1.06l-4.24 4.25a.75.75 0 01-1.06 0L5.21 8.29a.75.75 0 01-1.08z" clip-rule="evenodd" />
            </svg>
          </button>
          <ul class="dd-menu absolute z-20 mt-2 w-44 bg-white border border-gray-200 rounded-lg shadow-lg p-1 max-h-64 overflow-auto hidden" role="listbox">
            <li class="dd-item px-3 py-2 rounded-md md:text-base text-sm text-black hover:!text-primary hover:bg-[#CCD9EF]/20 cursor-pointer hover:font-semibold" data-value="all">All Categories</li>
            <?php
            $terms = get_terms([
              'taxonomy'   => $taxonomy,
              'hide_empty' => true,
            ]);
            if (!is_wp_error($terms)) {
              foreach ($terms as $t) {
                printf('<li class="dd-item px-3 py-2 rounded-md md:text-base text-sm text-black hover:!text-primary hover:bg-[#CCD9EF]/20 cursor-pointer hover:font-semibold" data-value="%s">%s</li>', esc_attr($t->slug), esc_html($t->name));
              }
            }
            ?>
          </ul>
        </div>
        <select id="<?php echo esc_attr($section_id); ?>-cat" class="hidden" aria-hidden="true">
          <option value="all" <?php selected('all', $selected_cat); ?>>All Categories</option>
          <?php if (!is_wp_error($terms)) {
            foreach ($terms as $t) {
              printf('<option value="%s" %s>%s</option>', esc_attr($t->slug), selected($selected_cat, $t->slug, false), esc_html($t->name));
            }
          } ?>
        </select>

        <!-- Sort (Custom dropdown) -->
        <div class="relative" data-dd="sort">
          <button type="button" class="inline-flex items-center gap-2 border border-[#D6D6D6] !font-semibold rounded-[12px] px-4 py-3 text-xs !bg-white !text-grey-7 focus:outline-none hover:!bg-transparent" aria-haspopup="listbox" aria-expanded="false">
            <span class="dd-label"><?php echo esc_html($sort_label); ?></span>
            <svg class="h-4 w-4 text-black" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
              <path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 10.94l3.71-3.71a.75.75 0 111.06 1.06l-4.24 4.25a.75.75 0 01-1.06 0L5.21 8.29a.75.75 0 01-1.08z" clip-rule="evenodd" />
            </svg>

          </button>
          <ul class="dd-menu absolute z-20 mt-2 w-40 bg-white border border-gray-200 rounded-lg shadow-lg p-1 max-h-64 overflow-auto hidden" role="listbox">
            <!-- <li class="dd-item px-3 py-2 rounded-md md:text-base text-sm text-gray-500 hover:!text-primary hover:bg-[#CCD9EF]/20 cursor-pointer" data-value="">Sort</li> -->
            <li class="dd-item px-3 py-2 rounded-md md:text-base text-sm text-black hover:!text-primary hover:bg-[#CCD9EF]/20 cursor-pointer hover:font-semibold" data-value="newest">Newest</li>
            <li class="dd-item px-3 py-2 rounded-md md:text-base text-sm text-black hover:!text-primary hover:bg-[#CCD9EF]/20 cursor-pointer hover:font-semibold" data-value="oldest">Oldest</li>
          </ul>
        </div>
        <select id="<?php echo esc_attr($section_id); ?>-sort" class="hidden" aria-hidden="true">
          <option value="" <?php selected('', $sort); ?>>Sort</option>
          <option value="newest" <?php selected('newest', $sort); ?>>Newest</option>
          <option value="oldest" <?php selected('oldest', $sort); ?>>Oldest</option>
        </select>
      </div>
    </div>

    <!-- Loader -->
    <div id="<?php echo esc_attr($section_id); ?>-loader" class="hidden flex justify-center items-center py-6">
      <svg class="animate-spin h-10 w-10 text-red-600" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"></path>
      </svg>
    </div>

    <!-- Grid -->
    <div id="<?php echo esc_attr($section_id); ?>-grid" class="grid gap-6 sm:gap-8 grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 fade-up-stagger-wrap">
      <?php
      if ($q->have_posts()) {
        while ($q->have_posts()) {
          $q->the_post();
          // Use the same card template as latest_news.php for consistent styling
          get_template_part('templates/parts/news_card_new');
        }
        wp_reset_postdata();
      } else {
        echo '<div class="col-span-full text-center text-gray-500 py-10">No news found. Try adjusting your filters or search.</div>';
      }
      ?>
    </div>

    <!-- Pagination: View More -->
    <div id="<?php echo esc_attr($section_id); ?>-load-container" class="mt-10 flex justify-center">
      <button id="<?php echo esc_attr($section_id); ?>-load" class="inline-flex items-center justify-center gap-2 rounded-full border-2 border-primary text-red-600 px-6 py-2 text-sm font-medium hover:bg-red-600 hover:text-white transition <?php echo $max_pages > 1 ? '' : 'hidden'; ?>">
        <svg class="hidden animate-spin h-4 w-4" viewBox="0 0 24 24" fill="none" aria-hidden="true">
          <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
          <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"></path>
        </svg>
        <span class="btn-text">View More</span>
      </button>
    </div>

    <!-- End-of-list message (shown after loading the last batch) -->
    <div id="<?php echo esc_attr($section_id); ?>-end" class="hidden mt-6 text-center text-gray-500">You're all caught up</div>
  </div>


</section>