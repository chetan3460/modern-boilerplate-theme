<?php
/*
/*
======================================
1.1 Dequeue
======================================
*/

function prefix_remove_core_block_styles()
{
  wp_dequeue_style('wp-block-columns');
  wp_dequeue_style('wp-block-column');
  wp_dequeue_style('classic-theme-styles');
}
add_action('wp_enqueue_scripts', 'prefix_remove_core_block_styles');

function prefix_remove_global_styles()
{
  wp_dequeue_style('global-styles');
}
add_action('wp_enqueue_scripts', 'prefix_remove_global_styles', 100);

function smartwp_remove_wp_block_library_css()
{
  wp_dequeue_style('wp-block-library');
  wp_dequeue_style('wp-block-library-theme');
  //wp_dequeue_style('wc-blocks-style');
}
add_action('wp_enqueue_scripts', 'smartwp_remove_wp_block_library_css', 100);
