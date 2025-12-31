<?php

/**
 * Modern Boilerplate Theme Functions
 *
 * @package ModernBoilerplate
 * @since 1.0.0
 */

// Theme Constants
if (!defined('T_PREFIX')) {
  define('T_PREFIX', 'wpmodernbp');
}

// ============================================================================
// CORE INCLUDES
// ============================================================================

// Core initialization
require_once __DIR__ . '/inc/core/init.php';

// Helper functions
require_once get_template_directory() . '/inc/reports-sorting-helper.php';

// Feature modules
require_once get_template_directory() . '/inc/cookie-helpers.php';

// AJAX Handlers (moved from functions.php)
require_once get_template_directory() . '/inc/ajax-handlers.php';

// ============================================================================
// ACF CONFIGURATION
// ============================================================================

// ACF JSON save and load paths
add_filter('acf/settings/save_json', function ($path) {
  return get_stylesheet_directory() . '/acf-json';
});

add_filter('acf/settings/load_json', function ($paths) {
  unset($paths[0]);
  $paths[] = get_stylesheet_directory() . '/acf-json';
  return $paths;
});

// ============================================================================
// PERFORMANCE OPTIMIZATIONS
// ============================================================================

/**
 * Hero image function
 */
function resplast_hero_image($image_id, $alt = '', $class = '')
{
  return wp_get_attachment_image($image_id, 'full', false, [
    'class' => $class,
    'alt' => $alt,
    'loading' => 'eager',
  ]);
}

/**
 * Conditional loading for Formidable Forms
 * Ensures jQuery is available
 */
function ensure_jquery_for_formidable()
{
  if (is_admin() || !class_exists('FrmFormsController')) {
    return;
  }
  wp_enqueue_script('jquery');
}
add_action('wp_enqueue_scripts', 'ensure_jquery_for_formidable', 5);

// ============================================================================
// WORDPRESS CORE OPTIMIZATIONS
// ============================================================================

// Disable Gutenberg
add_filter('use_block_editor_for_post', '__return_false');

// Remove unnecessary head tags
remove_action('wp_head', 'wp_generator');
remove_action('wp_head', 'wlwmanifest_link');
remove_action('wp_head', 'rsd_link');
remove_action('wp_head', 'wp_shortlink_wp_head');
remove_action('wp_head', 'adjacent_posts_rel_link_wp_head', 10);
remove_action('wp_head', 'feed_links_extra', 3);
remove_action('wp_head', 'feed_links', 2);
remove_action('wp_head', 'rest_output_link_wp_head', 10);
remove_action('wp_head', 'wp_oembed_add_discovery_links', 10);
remove_action('template_redirect', 'rest_output_link_header', 11);

// Disable emoji scripts
remove_action('wp_head', 'print_emoji_detection_script', 7);
remove_action('wp_print_styles', 'print_emoji_styles');
remove_action('admin_print_scripts', 'print_emoji_detection_script');
remove_action('admin_print_styles', 'print_emoji_styles');

// Limit WordPress heartbeat
function slow_heartbeat($settings)
{
  $settings['interval'] = 60;
  return $settings;
}
add_filter('heartbeat_settings', 'slow_heartbeat');

// Reduce revisions
if (!defined('WP_POST_REVISIONS')) {
  define('WP_POST_REVISIONS', 3);
}

// Increase memory limit
ini_set('memory_limit', '256M');

// Disable auto-updates
add_filter('automatic_updater_disabled', '__return_true');

// Remove query strings from static resources
function remove_query_strings()
{
  if (!is_admin()) {
    add_filter('script_loader_src', 'remove_query_strings_split', 15);
    add_filter('style_loader_src', 'remove_query_strings_split', 15);
  }
}
function remove_query_strings_split($src)
{
  $output = preg_split('/(&ver|\\?ver|&v=|\\?v=)/', $src);
  return $output[0];
}
add_action('init', 'remove_query_strings');

// Enable Gzip compression
function enable_gzip_compression()
{
  if (ob_get_level() == 0) {
    ob_start('ob_gzhandler');
  }
}
if (!is_admin()) {
  add_action('init', 'enable_gzip_compression', 1);
}

// Optimize database queries
function limit_post_revisions($num, $post)
{
  return 3;
}
add_filter('wp_revisions_to_keep', 'limit_post_revisions', 10, 2);

// Remove unnecessary resource hints
remove_action('wp_head', 'wp_resource_hints', 2);

// ============================================================================
// UTILITY FUNCTIONS
// ============================================================================

/**
 * Calculate estimated reading time for a post
 */
function calculate_post_read_time($post_id = null)
{
  if (!$post_id) {
    $post_id = get_the_ID();
  }

  // Check cache
  $cached_time = get_post_meta($post_id, '_post_read_time', true);
  if (!empty($cached_time)) {
    return (int) $cached_time;
  }

  // Get and process content
  $content = get_post_field('post_content', $post_id);
  $content = wp_strip_all_tags(strip_shortcodes($content));
  $content = preg_replace('/\\s+/', ' ', trim($content));
  $word_count = str_word_count($content);

  // Calculate (200 words per minute)
  $reading_time = ceil($word_count / 200);
  $reading_time = max(1, min(30, $reading_time));

  // Cache result
  update_post_meta($post_id, '_post_read_time', $reading_time);

  return $reading_time;
}

/**
 * Clear read time cache when post is updated
 */
function clear_read_time_cache($post_id)
{
  delete_post_meta($post_id, '_post_read_time');
}
add_action('save_post', 'clear_read_time_cache');
add_action('post_updated', 'clear_read_time_cache');

/**
 * Load homepage-specific CSS
 */
function load_homepage_css()
{
  if (function_exists('did_action') && did_action('vite_enqueued_home_css')) {
    return;
  }
}
add_action('wp_enqueue_scripts', 'load_homepage_css', 15);

// ============================================================================
// NEWS LISTING UTILITIES
// ============================================================================

if (!function_exists('resplast_get_news_card_html')) {
  /**
   * Return HTML for a single news card
   */
  function resplast_get_news_card_html($post_id = null)
  {
    $post = get_post($post_id);
    if (!$post) {
      return '';
    }

    $post_id = $post->ID;

    // Featured image lookup
    $image_id = null;
    if (function_exists('get_field')) {
      $acf_thumb = get_field('post_thumbnail', $post_id);
      if ($acf_thumb) {
        $image_id = is_array($acf_thumb) ? $acf_thumb['ID'] : $acf_thumb;
      }
    }
    if (!$image_id && has_post_thumbnail($post_id)) {
      $image_id = get_post_thumbnail_id($post_id);
    }

    $title = get_the_title($post_id);
    $permalink = get_permalink($post_id);
    $date = get_the_date('j M', $post_id);
    $read_time = function_exists('calculate_post_read_time')
      ? (int) calculate_post_read_time($post_id)
      : 1;

    ob_start();
    ?>
        <article class="bg-white rounded-3xl shadow-sm hover:shadow-md transition overflow-hidden flex flex-col">
            <a href="<?php echo esc_url(
              $permalink
            ); ?>" class="relative block aspect-[16/10] bg-gray-100">
                <?php if ($image_id) {
                  echo wp_get_attachment_image($image_id, 'large', false, [
                    'class' =>
                      'absolute inset-0 w-full h-full object-cover transition-transform duration-500 ease-out hover:scale-105',
                    'alt' => $title,
                    'loading' => 'lazy',
                    'decoding' => 'async',
                  ]);
                } else {
                  $svg =
                    "<svg xmlns='http://www.w3.org/2000/svg' width='800' height='600'><rect width='100%' height='100%' fill='#f3f4f6'/><text x='50%' y='50%' dominant-baseline='middle' text-anchor='middle' fill='#9ca3af' font-size='24'>Image</text></svg>";
                  echo '<img src="data:image/svg+xml;base64,' .
                    base64_encode($svg) .
                    '" alt="' .
                    esc_attr($title) .
                    '" class="absolute inset-0 w-full h-full object-cover" loading="lazy" decoding="async"/>';
                } ?>
            </a>
            <div class="p-6 flex flex-col gap-3">
                <a href="<?php echo esc_url(
                  $permalink
                ); ?>" class="text-lg font-semibold text-black hover:text-red-600 transition">
                    <?php echo esc_html($title); ?>
                </a>
                <div class="flex items-center justify-between text-sm text-gray-500">
                    <time datetime="<?php echo esc_attr(
                      get_the_date('c', $post_id)
                    ); ?>"><?php echo esc_html($date); ?></time>
                    <span><?php echo esc_html($read_time); ?> min read</span>
                </div>
            </div>
        </article>
<?php return trim(ob_get_clean());
  }
}

if (!function_exists('resplast_news_listing_shortcode')) {
  /**
   * Shortcode to embed the News Listing section
   * Usage: [news_listing]
   */
  function resplast_news_listing_shortcode($atts = [])
  {
    $template = locate_template('templates/news/listing.php', false, false);
    if (!$template) {
      return '<div class="text-red-600">News listing template not found.</div>';
    }
    ob_start();
    include $template;
    return ob_get_clean();
  }
  add_shortcode('news_listing', 'resplast_news_listing_shortcode');
}

// ============================================================================
// REPORTS CUSTOM POST TYPE
// ============================================================================

/**
 * Register Reports Custom Post Type
 */
function register_reports_post_type()
{
  $labels = [
    'name' => _x('Reports', 'Post type general name', 'resplast'),
    'singular_name' => _x('Report', 'Post type singular name', 'resplast'),
    'menu_name' => _x('Reports', 'Admin Menu text', 'resplast'),
    'name_admin_bar' => _x('Report', 'Add New on Toolbar', 'resplast'),
    'add_new' => __('Add New', 'resplast'),
    'add_new_item' => __('Add New Report', 'resplast'),
    'new_item' => __('New Report', 'resplast'),
    'edit_item' => __('Edit Report', 'resplast'),
    'view_item' => __('View Report', 'resplast'),
    'all_items' => __('All Reports', 'resplast'),
    'search_items' => __('Search Reports', 'resplast'),
    'not_found' => __('No reports found.', 'resplast'),
    'not_found_in_trash' => __('No reports found in Trash.', 'resplast'),
  ];

  $args = [
    'labels' => $labels,
    'public' => true,
    'publicly_queryable' => true,
    'show_ui' => true,
    'show_in_menu' => true,
    'query_var' => true,
    'rewrite' => ['slug' => 'reports'],
    'capability_type' => 'post',
    'has_archive' => true,
    'hierarchical' => false,
    'menu_position' => 6,
    'menu_icon' => 'dashicons-media-document',
    'supports' => [
      'title',
      'editor',
      'thumbnail',
      'excerpt',
      'author',
      'revisions',
      'custom-fields',
    ],
    'show_in_rest' => true,
  ];

  register_post_type('reports', $args);
}
add_action('init', 'register_reports_post_type');

/**
 * Register Report Taxonomies
 */
function register_report_taxonomies()
{
  // Report Categories
  register_taxonomy(
    'report_category',
    ['reports'],
    [
      'hierarchical' => true,
      'labels' => [
        'name' => _x('Report Categories', 'Taxonomy General Name', 'resplast'),
        'singular_name' => _x('Report Category', 'Taxonomy Singular Name', 'resplast'),
      ],
      'show_ui' => true,
      'show_admin_column' => true,
      'show_in_rest' => true,
    ]
  );

  // Financial Years
  register_taxonomy(
    'financial_year',
    ['reports'],
    [
      'hierarchical' => false,
      'labels' => [
        'name' => _x('Financial Years', 'Taxonomy General Name', 'resplast'),
        'singular_name' => _x('Financial Year', 'Taxonomy Singular Name', 'resplast'),
      ],
      'show_ui' => true,
      'show_admin_column' => true,
      'show_in_rest' => true,
    ]
  );

  // Quarters
  register_taxonomy(
    'quarter',
    ['reports'],
    [
      'hierarchical' => false,
      'labels' => [
        'name' => _x('Quarters', 'Taxonomy General Name', 'resplast'),
        'singular_name' => _x('Quarter', 'Taxonomy Singular Name', 'resplast'),
      ],
      'show_ui' => true,
      'show_admin_column' => true,
      'show_in_rest' => true,
    ]
  );
}
add_action('init', 'register_report_taxonomies');

/**
 * Insert default taxonomy terms on theme activation
 */
function insert_default_report_terms()
{
  if (get_option('resplast_default_report_terms_inserted')) {
    return;
  }

  // Default categories
  $default_categories = [
    'Annual Reports',
    'Quarterly Results',
    'Investor Presentations',
    'Financial Statements',
    'Corporate Governance',
    'Sustainability Reports',
    'Regulatory Filings',
    'Press Releases',
    'Notices & Disclosures',
    'Policies & Guidelines',
  ];

  foreach ($default_categories as $category) {
    if (!term_exists($category, 'report_category')) {
      wp_insert_term($category, 'report_category');
    }
  }

  // Default financial years
  $current_year = date('Y');
  for ($i = -3; $i <= 2; $i++) {
    $year = $current_year + $i;
    $next_year = $year + 1;
    $fy_label = 'FY' . $year . '-' . substr($next_year, 2);
    if (!term_exists($fy_label, 'financial_year')) {
      wp_insert_term($fy_label, 'financial_year');
    }
  }

  // Default quarters
  foreach (['Q1', 'Q2', 'Q3', 'Q4'] as $quarter) {
    if (!term_exists($quarter, 'quarter')) {
      wp_insert_term($quarter, 'quarter');
    }
  }

  update_option('resplast_default_report_terms_inserted', true);
}
add_action('init', 'insert_default_report_terms');
