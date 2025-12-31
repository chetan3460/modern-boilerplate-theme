<?php

/**
 * Admin Customizations
 * Dashboard widgets, admin columns, filters, and ACF diagnostics
 */

/**
 * Add ACF Diagnostics admin page
 */
function add_acf_diagnostics_admin_page()
{
  add_menu_page(
    'ACF Diagnostics',
    'ACF Diagnostics',
    'manage_options',
    'acf-diagnostics',
    'acf_diagnostics_page_content',
    'dashicons-search',
    99
  );
}
add_action('admin_menu', 'add_acf_diagnostics_admin_page');

function acf_diagnostics_page_content()
{
  echo '<div class="wrap">';
  echo '<h1>ACF Diagnostics</h1>';

  // Check if ACF is active
  if (!function_exists('acf_get_field_groups')) {
    echo '<div class="notice notice-error"><p><strong>❌ ACF is not active or not installed!</strong></p></div>';
    echo '</div>';
    return;
  } else {
    echo '<div class="notice notice-success"><p><strong>✅ ACF is active</strong></p></div>';
  }

  // Get all ACF field groups
  $field_groups = acf_get_field_groups();

  echo '<h2>Field Groups (' . count($field_groups) . ' found)</h2>';

  if (empty($field_groups)) {
    echo '<p>No field groups found.</p>';
  } else {
    foreach ($field_groups as $field_group) {
      echo '<div style="border: 1px solid #ddd; margin: 15px 0; padding: 15px; background: #fff; border-radius: 5px;">';
      echo '<h3>' . esc_html($field_group['title']) . '</h3>';
      echo '<p><strong>Key:</strong> ' . esc_html($field_group['key']) . '</p>';

      // Check location rules
      if (!empty($field_group['location'])) {
        echo '<p><strong>Location Rules:</strong></p>';
        echo '<ul>';
        foreach ($field_group['location'] as $location_group) {
          foreach ($location_group as $rule) {
            $operator = $rule['operator'] === '==' ? 'equals' : 'not equals';
            echo '<li style="color: inherit;"><strong>' .
              $rule['param'] .
              '</strong> ' .
              $operator .
              ' <strong>' .
              $rule['value'] .
              '</strong></li>';
          }
        }
        echo '</ul>';
      }

      // Get fields in this group
      $fields = acf_get_fields($field_group);
      if (!empty($fields)) {
        echo '<p><strong>Fields (' . count($fields) . '):</strong></p>';
        echo '<ul>';
        foreach ($fields as $field) {
          echo '<li><strong>' . $field['name'] . '</strong> (' . $field['type'] . ')';
          if ($field['type'] === 'repeater' && !empty($field['sub_fields'])) {
            echo '<ul>';
            foreach ($field['sub_fields'] as $sub_field) {
              echo '<li>└─ <strong>' .
                $sub_field['name'] .
                '</strong> (' .
                $sub_field['type'] .
                ')</li>';
            }
            echo '</ul>';
          }
          echo '</li>';
        }
        echo '</ul>';
      }

      echo '</div>';
    }
  }

  echo '<h2>🔍 Troubleshooting Tips</h2>';
  echo '<div class="notice notice-info"><ul>';
  echo '<li><strong>No ACF fields:</strong> Check that field groups are assigned correctly</li>';
  echo '<li><strong>Wrong field names:</strong> Use the exact field names shown above in your templates</li>';
  echo '<li><strong>Empty data:</strong> Edit the posts and make sure ACF data is saved</li>';
  echo '</ul></div>';

  echo '</div>';
}

/**
 * Customize Reports admin columns
 */
function add_reports_admin_columns($columns)
{
  // Remove date column and add it back at the end
  $date_column = $columns['date'];
  unset($columns['date']);

  // Add custom columns
  $columns['report_category'] = __('Category', 'resplast');
  $columns['financial_year'] = __('Financial Year', 'resplast');
  $columns['quarter'] = __('Quarter', 'resplast');
  $columns['published_date'] = __('Published', 'resplast');
  $columns['featured'] = __('Featured', 'resplast');
  $columns['document_file'] = __('Document', 'resplast');

  // Add date column back at the end
  $columns['date'] = $date_column;

  return $columns;
}
add_filter('manage_reports_posts_columns', 'add_reports_admin_columns');

/**
 * Populate custom admin columns
 */
function populate_reports_admin_columns($column, $post_id)
{
  switch ($column) {
    case 'report_category':
      $terms = get_the_terms($post_id, 'report_category');
      if ($terms && !is_wp_error($terms)) {
        $category_names = wp_list_pluck($terms, 'name');
        echo implode(', ', $category_names);
      } else {
        echo '<span style="color: #999;">—</span>';
      }
      break;

    case 'financial_year':
      $terms = get_the_terms($post_id, 'financial_year');
      if ($terms && !is_wp_error($terms)) {
        $year_names = wp_list_pluck($terms, 'name');
        echo implode(', ', $year_names);
      } else {
        echo '<span style="color: #999;">—</span>';
      }
      break;

    case 'quarter':
      $terms = get_the_terms($post_id, 'quarter');
      if ($terms && !is_wp_error($terms)) {
        $quarter_names = wp_list_pluck($terms, 'name');
        echo implode(', ', $quarter_names);
      } else {
        echo '<span style="color: #999;">—</span>';
      }
      break;

    case 'published_date':
      $published_date = get_post_meta($post_id, 'published_date', true);
      if ($published_date) {
        echo esc_html($published_date);
      } else {
        echo '<span style="color: #999;">—</span>';
      }
      break;

    case 'featured':
      $featured = get_post_meta($post_id, 'featured_report', true);
      if ($featured) {
        echo '<span style="color: #d63638;">⭐ Featured</span>';
      } else {
        echo '<span style="color: #999;">—</span>';
      }
      break;

    case 'document_file':
      $document_file = get_post_meta($post_id, 'document_file', true);
      $download_link = get_post_meta($post_id, 'download_link', true);

      if (!empty($document_file) && is_array($document_file) && isset($document_file['url'])) {
        echo '<a href="' .
          esc_url($document_file['url']) .
          '" target="_blank" title="Download file">📄 ' .
          basename($document_file['filename']) .
          '</a>';
      } elseif ($download_link) {
        echo '<a href="' .
          esc_url($download_link) .
          '" target="_blank" title="External download link">🔗 External</a>';
      } else {
        echo '<span style="color: #999;">No file</span>';
      }
      break;
  }
}
add_action('manage_reports_posts_custom_column', 'populate_reports_admin_columns', 10, 2);

/**
 * Add admin filters for reports
 */
function add_reports_admin_filters()
{
  global $typenow;

  if ($typenow === 'reports') {
    // Category filter
    $categories = get_terms([
      'taxonomy' => 'report_category',
      'hide_empty' => false,
    ]);

    if ($categories && !is_wp_error($categories)) {
      echo '<select name="report_category" id="report_category">';
      echo '<option value="">All Categories</option>';

      $selected_category = isset($_GET['report_category']) ? $_GET['report_category'] : '';

      foreach ($categories as $category) {
        printf(
          '<option value="%s"%s>%s</option>',
          $category->slug,
          selected($selected_category, $category->slug, false),
          $category->name
        );
      }

      echo '</select>';
    }

    // Financial Year filter
    $financial_years = get_terms([
      'taxonomy' => 'financial_year',
      'hide_empty' => false,
      'orderby' => 'name',
      'order' => 'DESC',
    ]);

    if ($financial_years && !is_wp_error($financial_years)) {
      echo '<select name="financial_year" id="financial_year">';
      echo '<option value="">All Financial Years</option>';

      $selected_year = isset($_GET['financial_year']) ? $_GET['financial_year'] : '';

      foreach ($financial_years as $year) {
        printf(
          '<option value="%s"%s>%s</option>',
          $year->slug,
          selected($selected_year, $year->slug, false),
          $year->name
        );
      }

      echo '</select>';
    }

    // Featured filter
    echo '<select name="featured_filter" id="featured_filter">';
    echo '<option value="">All Reports</option>';

    $featured_filter = isset($_GET['featured_filter']) ? $_GET['featured_filter'] : '';

    echo '<option value="featured"' .
      selected($featured_filter, 'featured', false) .
      '>Featured Only</option>';
    echo '<option value="not_featured"' .
      selected($featured_filter, 'not_featured', false) .
      '>Not Featured</option>';
    echo '</select>';
  }
}
add_action('restrict_manage_posts', 'add_reports_admin_filters');

/**
 * Handle admin filtering
 */
function handle_reports_admin_filtering($query)
{
  global $pagenow, $typenow;

  if (
    $pagenow === 'edit.php' &&
    $typenow === 'reports' &&
    $query->is_admin &&
    $query->is_main_query()
  ) {
    // Category filtering
    if (!empty($_GET['report_category'])) {
      $query->set('tax_query', [
        [
          'taxonomy' => 'report_category',
          'field' => 'slug',
          'terms' => $_GET['report_category'],
        ],
      ]);
    }

    // Financial year filtering
    if (!empty($_GET['financial_year'])) {
      $existing_tax_query = $query->get('tax_query') ?: [];
      $existing_tax_query[] = [
        'taxonomy' => 'financial_year',
        'field' => 'slug',
        'terms' => $_GET['financial_year'],
      ];
      $query->set('tax_query', $existing_tax_query);
    }

    // Featured filtering
    if (!empty($_GET['featured_filter'])) {
      $meta_query = [];

      if ($_GET['featured_filter'] === 'featured') {
        $meta_query[] = [
          'key' => 'featured_report',
          'value' => '1',
          'compare' => '=',
        ];
      } elseif ($_GET['featured_filter'] === 'not_featured') {
        $meta_query[] = [
          'relation' => 'OR',
          [
            'key' => 'featured_report',
            'value' => '1',
            'compare' => '!=',
          ],
          [
            'key' => 'featured_report',
            'compare' => 'NOT EXISTS',
          ],
        ];
      }

      $query->set('meta_query', $meta_query);
    }
  }
}
add_action('pre_get_posts', 'handle_reports_admin_filtering');

/**
 * Make custom columns sortable
 */
function make_reports_columns_sortable($columns)
{
  $columns['published_date'] = 'published_date';
  $columns['featured'] = 'featured';
  return $columns;
}
add_filter('manage_edit-reports_sortable_columns', 'make_reports_columns_sortable');

/**
 * Handle custom column sorting
 */
function handle_reports_column_sorting($query)
{
  if (!is_admin() || !$query->is_main_query()) {
    return;
  }

  $orderby = $query->get('orderby');

  if ($orderby === 'published_date') {
    $query->set('meta_key', 'published_date');
    $query->set('orderby', 'meta_value');
  } elseif ($orderby === 'featured') {
    $query->set('meta_key', 'featured_report');
    $query->set('orderby', 'meta_value_num');
  }
}
add_action('pre_get_posts', 'handle_reports_column_sorting');
