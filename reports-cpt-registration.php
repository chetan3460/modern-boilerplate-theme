<?php
/**
 * Reports Custom Post Type and Taxonomies
 * Add this code to your functions.php file to register the Reports CPT and taxonomies
 */

/**
 * Register Reports Custom Post Type
 */
function register_reports_post_type() {
    $labels = array(
        'name'                  => _x('Reports', 'Post type general name', 'resplast'),
        'singular_name'         => _x('Report', 'Post type singular name', 'resplast'),
        'menu_name'             => _x('Reports', 'Admin Menu text', 'resplast'),
        'name_admin_bar'        => _x('Report', 'Add New on Toolbar', 'resplast'),
        'add_new'               => __('Add New', 'resplast'),
        'add_new_item'          => __('Add New Report', 'resplast'),
        'new_item'              => __('New Report', 'resplast'),
        'edit_item'             => __('Edit Report', 'resplast'),
        'view_item'             => __('View Report', 'resplast'),
        'all_items'             => __('All Reports', 'resplast'),
        'search_items'          => __('Search Reports', 'resplast'),
        'parent_item_colon'     => __('Parent Reports:', 'resplast'),
        'not_found'             => __('No reports found.', 'resplast'),
        'not_found_in_trash'    => __('No reports found in Trash.', 'resplast'),
        'featured_image'        => _x('Report Featured Image', 'Overrides the "Featured Image" phrase', 'resplast'),
        'set_featured_image'    => _x('Set featured image', 'Overrides the "Set featured image" phrase', 'resplast'),
        'remove_featured_image' => _x('Remove featured image', 'Overrides the "Remove featured image" phrase', 'resplast'),
        'use_featured_image'    => _x('Use as featured image', 'Overrides the "Use as featured image" phrase', 'resplast'),
        'archives'              => _x('Report archives', 'The post type archive label', 'resplast'),
        'insert_into_item'      => _x('Insert into report', 'Overrides the "Insert into post" phrase', 'resplast'),
        'uploaded_to_this_item' => _x('Uploaded to this report', 'Overrides the "Uploaded to this post" phrase', 'resplast'),
        'filter_items_list'     => _x('Filter reports list', 'Screen reader text for the filter links', 'resplast'),
        'items_list_navigation' => _x('Reports list navigation', 'Screen reader text for the pagination', 'resplast'),
        'items_list'            => _x('Reports list', 'Screen reader text for the items list', 'resplast'),
    );
    
    $args = array(
        'labels'             => $labels,
        'public'             => true,
        'publicly_queryable' => true,
        'show_ui'            => true,
        'show_in_menu'       => true,
        'show_in_nav_menus'  => true,
        'show_in_admin_bar'  => true,
        'query_var'          => true,
        'rewrite'            => array('slug' => 'reports'),
        'capability_type'    => 'post',
        'has_archive'        => true,
        'hierarchical'       => false,
        'menu_position'      => 6,
        'menu_icon'          => 'dashicons-media-document',
        'supports'           => array('title', 'editor', 'thumbnail', 'excerpt', 'author', 'revisions', 'custom-fields'),
        'show_in_rest'       => true,
        'rest_base'          => 'reports',
        'rest_controller_class' => 'WP_REST_Posts_Controller',
    );
    
    register_post_type('reports', $args);
}
add_action('init', 'register_reports_post_type');

/**
 * Register Report Categories Taxonomy
 */
function register_report_category_taxonomy() {
    $labels = array(
        'name'                       => _x('Report Categories', 'Taxonomy General Name', 'resplast'),
        'singular_name'              => _x('Report Category', 'Taxonomy Singular Name', 'resplast'),
        'menu_name'                  => __('Categories', 'resplast'),
        'all_items'                  => __('All Categories', 'resplast'),
        'parent_item'                => __('Parent Category', 'resplast'),
        'parent_item_colon'          => __('Parent Category:', 'resplast'),
        'new_item_name'              => __('New Category Name', 'resplast'),
        'add_new_item'               => __('Add New Category', 'resplast'),
        'edit_item'                  => __('Edit Category', 'resplast'),
        'update_item'                => __('Update Category', 'resplast'),
        'view_item'                  => __('View Category', 'resplast'),
        'separate_items_with_commas' => __('Separate categories with commas', 'resplast'),
        'add_or_remove_items'        => __('Add or remove categories', 'resplast'),
        'choose_from_most_used'      => __('Choose from the most used', 'resplast'),
        'popular_items'              => __('Popular Categories', 'resplast'),
        'search_items'               => __('Search Categories', 'resplast'),
        'not_found'                  => __('Not Found', 'resplast'),
        'no_terms'                   => __('No categories', 'resplast'),
        'items_list'                 => __('Categories list', 'resplast'),
        'items_list_navigation'      => __('Categories list navigation', 'resplast'),
    );
    
    $args = array(
        'labels'                     => $labels,
        'hierarchical'               => true,
        'public'                     => true,
        'show_ui'                    => true,
        'show_admin_column'          => true,
        'show_in_nav_menus'          => true,
        'show_tagcloud'              => false,
        'show_in_rest'               => true,
        'rest_base'                  => 'report-categories',
        'rest_controller_class'      => 'WP_REST_Terms_Controller',
    );
    
    register_taxonomy('report_category', array('reports'), $args);
}
add_action('init', 'register_report_category_taxonomy');

/**
 * Register Financial Year Taxonomy
 */
function register_financial_year_taxonomy() {
    $labels = array(
        'name'                       => _x('Financial Years', 'Taxonomy General Name', 'resplast'),
        'singular_name'              => _x('Financial Year', 'Taxonomy Singular Name', 'resplast'),
        'menu_name'                  => __('Financial Years', 'resplast'),
        'all_items'                  => __('All Financial Years', 'resplast'),
        'parent_item'                => __('Parent Financial Year', 'resplast'),
        'parent_item_colon'          => __('Parent Financial Year:', 'resplast'),
        'new_item_name'              => __('New Financial Year Name', 'resplast'),
        'add_new_item'               => __('Add New Financial Year', 'resplast'),
        'edit_item'                  => __('Edit Financial Year', 'resplast'),
        'update_item'                => __('Update Financial Year', 'resplast'),
        'view_item'                  => __('View Financial Year', 'resplast'),
        'separate_items_with_commas' => __('Separate financial years with commas', 'resplast'),
        'add_or_remove_items'        => __('Add or remove financial years', 'resplast'),
        'choose_from_most_used'      => __('Choose from the most used', 'resplast'),
        'popular_items'              => __('Popular Financial Years', 'resplast'),
        'search_items'               => __('Search Financial Years', 'resplast'),
        'not_found'                  => __('Not Found', 'resplast'),
        'no_terms'                   => __('No financial years', 'resplast'),
        'items_list'                 => __('Financial Years list', 'resplast'),
        'items_list_navigation'      => __('Financial Years list navigation', 'resplast'),
    );
    
    $args = array(
        'labels'                     => $labels,
        'hierarchical'               => false,
        'public'                     => true,
        'show_ui'                    => true,
        'show_admin_column'          => true,
        'show_in_nav_menus'          => false,
        'show_tagcloud'              => false,
        'show_in_rest'               => true,
        'rest_base'                  => 'financial-years',
        'rest_controller_class'      => 'WP_REST_Terms_Controller',
    );
    
    register_taxonomy('financial_year', array('reports'), $args);
}
add_action('init', 'register_financial_year_taxonomy');

/**
 * Register Quarter Taxonomy
 */
function register_quarter_taxonomy() {
    $labels = array(
        'name'                       => _x('Quarters', 'Taxonomy General Name', 'resplast'),
        'singular_name'              => _x('Quarter', 'Taxonomy Singular Name', 'resplast'),
        'menu_name'                  => __('Quarters', 'resplast'),
        'all_items'                  => __('All Quarters', 'resplast'),
        'parent_item'                => __('Parent Quarter', 'resplast'),
        'parent_item_colon'          => __('Parent Quarter:', 'resplast'),
        'new_item_name'              => __('New Quarter Name', 'resplast'),
        'add_new_item'               => __('Add New Quarter', 'resplast'),
        'edit_item'                  => __('Edit Quarter', 'resplast'),
        'update_item'                => __('Update Quarter', 'resplast'),
        'view_item'                  => __('View Quarter', 'resplast'),
        'separate_items_with_commas' => __('Separate quarters with commas', 'resplast'),
        'add_or_remove_items'        => __('Add or remove quarters', 'resplast'),
        'choose_from_most_used'      => __('Choose from the most used', 'resplast'),
        'popular_items'              => __('Popular Quarters', 'resplast'),
        'search_items'               => __('Search Quarters', 'resplast'),
        'not_found'                  => __('Not Found', 'resplast'),
        'no_terms'                   => __('No quarters', 'resplast'),
        'items_list'                 => __('Quarters list', 'resplast'),
        'items_list_navigation'      => __('Quarters list navigation', 'resplast'),
    );
    
    $args = array(
        'labels'                     => $labels,
        'hierarchical'               => false,
        'public'                     => true,
        'show_ui'                    => true,
        'show_admin_column'          => true,
        'show_in_nav_menus'          => false,
        'show_tagcloud'              => false,
        'show_in_rest'               => true,
        'rest_base'                  => 'quarters',
        'rest_controller_class'      => 'WP_REST_Terms_Controller',
    );
    
    register_taxonomy('quarter', array('reports'), $args);
}
add_action('init', 'register_quarter_taxonomy');

/**
 * Insert default taxonomy terms on theme activation
 */
function insert_default_report_terms() {
    // Only run once
    if (get_option('resplast_default_report_terms_inserted')) {
        return;
    }
    
    // Default report categories
    $default_categories = array(
        'Annual Reports',
        'Quarterly Results', 
        'Investor Presentations',
        'Financial Statements',
        'Corporate Governance',
        'Sustainability Reports',
        'Regulatory Filings',
        'Press Releases',
        'Notices & Disclosures',
        'Policies & Guidelines'
    );
    
    foreach ($default_categories as $category) {
        if (!term_exists($category, 'report_category')) {
            wp_insert_term($category, 'report_category');
        }
    }
    
    // Default financial years (last 5 years + current + next)
    $current_year = date('Y');
    $default_years = array();
    
    for ($i = -3; $i <= 2; $i++) {
        $year = $current_year + $i;
        $next_year = $year + 1;
        $fy_label = 'FY' . $year . '-' . substr($next_year, 2);
        $default_years[] = $fy_label;
    }
    
    foreach ($default_years as $year) {
        if (!term_exists($year, 'financial_year')) {
            wp_insert_term($year, 'financial_year');
        }
    }
    
    // Default quarters
    $default_quarters = array('Q1', 'Q2', 'Q3', 'Q4');
    
    foreach ($default_quarters as $quarter) {
        if (!term_exists($quarter, 'quarter')) {
            wp_insert_term($quarter, 'quarter');
        }
    }
    
    // Mark as completed
    update_option('resplast_default_report_terms_inserted', true);
}
add_action('init', 'insert_default_report_terms');

/**
 * Customize Reports admin columns
 */
function add_reports_admin_columns($columns) {
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
function populate_reports_admin_columns($column, $post_id) {
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
                echo '<a href="' . esc_url($document_file['url']) . '" target="_blank" title="Download file">📄 ' . basename($document_file['filename']) . '</a>';
            } elseif ($download_link) {
                echo '<a href="' . esc_url($download_link) . '" target="_blank" title="External download link">🔗 External</a>';
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
function add_reports_admin_filters() {
    global $typenow;
    
    if ($typenow === 'reports') {
        // Category filter
        $categories = get_terms(array(
            'taxonomy' => 'report_category',
            'hide_empty' => false,
        ));
        
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
        $financial_years = get_terms(array(
            'taxonomy' => 'financial_year',
            'hide_empty' => false,
            'orderby' => 'name',
            'order' => 'DESC'
        ));
        
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
        
        echo '<option value="featured"' . selected($featured_filter, 'featured', false) . '>Featured Only</option>';
        echo '<option value="not_featured"' . selected($featured_filter, 'not_featured', false) . '>Not Featured</option>';
        echo '</select>';
    }
}
add_action('restrict_manage_posts', 'add_reports_admin_filters');

/**
 * Handle admin filtering
 */
function handle_reports_admin_filtering($query) {
    global $pagenow, $typenow;
    
    if ($pagenow === 'edit.php' && $typenow === 'reports' && $query->is_admin && $query->is_main_query()) {
        // Category filtering
        if (!empty($_GET['report_category'])) {
            $query->set('tax_query', array(
                array(
                    'taxonomy' => 'report_category',
                    'field'    => 'slug',
                    'terms'    => $_GET['report_category'],
                )
            ));
        }
        
        // Financial year filtering
        if (!empty($_GET['financial_year'])) {
            $existing_tax_query = $query->get('tax_query') ?: array();
            $existing_tax_query[] = array(
                'taxonomy' => 'financial_year',
                'field'    => 'slug',
                'terms'    => $_GET['financial_year'],
            );
            $query->set('tax_query', $existing_tax_query);
        }
        
        // Featured filtering
        if (!empty($_GET['featured_filter'])) {
            $meta_query = array();
            
            if ($_GET['featured_filter'] === 'featured') {
                $meta_query[] = array(
                    'key'     => 'featured_report',
                    'value'   => '1',
                    'compare' => '='
                );
            } elseif ($_GET['featured_filter'] === 'not_featured') {
                $meta_query[] = array(
                    'relation' => 'OR',
                    array(
                        'key'     => 'featured_report',
                        'value'   => '1',
                        'compare' => '!='
                    ),
                    array(
                        'key'     => 'featured_report',
                        'compare' => 'NOT EXISTS'
                    )
                );
            }
            
            $query->set('meta_query', $meta_query);
        }
    }
}
add_action('pre_get_posts', 'handle_reports_admin_filtering');

/**
 * Make custom columns sortable
 */
function make_reports_columns_sortable($columns) {
    $columns['published_date'] = 'published_date';
    $columns['featured'] = 'featured';
    return $columns;
}
add_filter('manage_edit-reports_sortable_columns', 'make_reports_columns_sortable');

/**
 * Handle custom column sorting
 */
function handle_reports_column_sorting($query) {
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