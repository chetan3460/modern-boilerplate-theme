<?php
add_action('init', 'create_custom_posts');
function create_custom_posts()
{
  // CPT News
  register_post_type('news', [
    'labels' => [
      'name' => __('News & Updates'),
      'singular_name' => __('News & Updates'),
      'menu_name' => __('News & Updates'),
      'all_items' => __('All News & Updates'),
      'add_new' => __('Add New'),
      'add_new_item' => __('Add New News'),
      'edit_item' => __('Edit News'),
      'new_item' => __('New News'),
      'view_item' => __('View News'),
      'search_items' => __('Search News & Updates'),
      'not_found' => __('No news found'),
      'not_found_in_trash' => __('No news found in trash'),
    ],
    'public' => true,
    'has_archive' => false,
    'rewrite' => ['slug' => 'news-updates'],
    'show_in_rest' => true,
    'capability_type' => 'post',
    'hierarchical' => true,
    'menu_position' => null,
    'publicly_queryable' => true,
    'supports' => ['title', 'editor', 'thumbnail', 'excerpt'],
  ]);

  // Custom taxonomy - Category for News
  $news_cat = [
    'name' => _x('Category', 'news category'),
    'singular_name' => _x('Category', 'news category'),
    'search_items' => __('Search Categories'),
    'all_items' => __('All Categories'),
    'parent_item' => __('Parent Category'),
    'parent_item_colon' => __('Parent Category:'),
    'edit_item' => __('Edit Category'),
    'update_item' => __('Update Category'),
    'add_new_item' => __('Add New Category'),
    'new_item_name' => __('New Category Name'),
    'menu_name' => __('Category'),
  ];

  register_taxonomy(
    'news_category',
    ['news'],
    [
      'hierarchical' => true,
      'labels' => $news_cat,
      'show_ui' => true,
      'show_admin_column' => true,
      'query_var' => true,
      'show_in_rest' => true,
      'rewrite' => ['slug' => 'news-category'],
    ]
  );

  // CPT Team Members
  register_post_type('team_member', [
    'labels' => [
      'name' => __('Team Members'),
      'singular_name' => __('Team Member'),
      'menu_name' => __('Team Members'),
      'add_new' => __('Add New Member'),
      'add_new_item' => __('Add New Team Member'),
      'edit_item' => __('Edit Team Member'),
      'new_item' => __('New Team Member'),
      'view_item' => __('View Team Member'),
      'search_items' => __('Search Team Members'),
      'not_found' => __('No team members found'),
      'not_found_in_trash' => __('No team members found in trash'),
    ],
    'public' => true,
    'publicly_queryable' => true,
    'show_ui' => true,
    'show_in_menu' => true,
    'query_var' => true,
    'rewrite' => ['slug' => 'team'],
    'capability_type' => 'post',
    'has_archive' => true,
    'hierarchical' => false,
    'menu_position' => 25,
    'menu_icon' => 'dashicons-groups',
    'supports' => ['title', 'editor', 'thumbnail', 'excerpt', 'custom-fields'],
    'show_in_rest' => true,
  ]);

  // Custom taxonomy - Team Categories
  $team_cat = [
    'name' => _x('Team Categories', 'team category'),
    'singular_name' => _x('Team Category', 'team category'),
    'search_items' => __('Search Categories'),
    'all_items' => __('All Categories'),
    'parent_item' => __('Parent Category'),
    'parent_item_colon' => __('Parent Category:'),
    'edit_item' => __('Edit Category'),
    'update_item' => __('Update Category'),
    'add_new_item' => __('Add New Category'),
    'new_item_name' => __('New Category Name'),
    'menu_name' => __('Categories'),
  ];

  register_taxonomy(
    'team_category',
    ['team_member'],
    [
      'hierarchical' => true,
      'labels' => $team_cat,
      'show_ui' => true,
      'show_admin_column' => true,
      'query_var' => true,
      'show_in_rest' => true,
      'rewrite' => ['slug' => 'team-category'],
    ]
  );
}

// Create default team categories
add_action('init', 'create_default_team_categories', 999);
function create_default_team_categories()
{
  // Only create if they don't exist
  if (!term_exists('Board Members', 'team_category')) {
    wp_insert_term('Board Members', 'team_category', [
      'description' => 'Board of Directors and key leadership',
      'slug' => 'board-members',
    ]);

    // Flush rewrite rules on theme activation to enable product permalinks
    add_action('after_switch_theme', 'flush_rewrite_rules');
  }

  if (!term_exists('Management Team', 'team_category')) {
    wp_insert_term('Management Team', 'team_category', [
      'description' => 'Executive management and senior staff',
      'slug' => 'management-team',
    ]);
  }

  if (!term_exists('Advisory Board', 'team_category')) {
    wp_insert_term('Advisory Board', 'team_category', [
      'description' => 'Independent advisors and consultants',
      'slug' => 'advisory-board',
    ]);
  }
}

function modify_nav_menu_meta_box_object($object)
{
  if ($object->name === 'news_category') {
    $object->labels->name = __('News Category', T_PREFIX);
  }
  if ($object->name === 'team_category') {
    $object->labels->name = __('Team Category', T_PREFIX);
  }
  return $object;
}

add_filter('nav_menu_meta_box_object', 'modify_nav_menu_meta_box_object');
