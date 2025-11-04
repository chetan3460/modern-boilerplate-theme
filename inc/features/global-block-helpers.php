<?php

/**
 * Global Block Helpers
 * Functions to easily use global blocks anywhere in your theme
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Render a global block directly with data
 * 
 * @param string $block_name Name of the block (e.g., 'home_stats_block')
 * @param array $data Data to pass to the block
 * @param bool $echo Whether to echo or return the output
 * @return string|void
 */
function render_global_block($block_name, $data = [], $echo = true) {
    global $_acf_temp_data;
    
    // Store the temporary data
    $_acf_temp_data = $data;
    
    // Override get_sub_field to use our temp data
    if (!function_exists('get_sub_field_temp')) {
        function get_sub_field_temp($field_name) {
            global $_acf_temp_data;
            return $_acf_temp_data[$field_name] ?? '';
        }
    }
    
    // Temporarily replace get_sub_field
    add_filter('pre_option_active_plugins', function($plugins) {
        // Hack to temporarily override get_sub_field
        if (!function_exists('get_sub_field_original')) {
            if (function_exists('get_sub_field')) {
                function get_sub_field_original($field_name) {
                    return get_sub_field_temp($field_name);
                }
            }
        }
        return $plugins;
    });
    
    ob_start();
    
    // First check regular blocks, then global
    $local_template = get_template_directory() . "/templates/blocks/{$block_name}.php";
    $global_template = get_template_directory() . "/templates/blocks/global/{$block_name}.php";
    
    if (file_exists($local_template)) {
        include $local_template;
    } elseif (file_exists($global_template)) {
        include $global_template;
    } else {
        echo "<!-- Block template not found: {$block_name} -->";
    }
    
    $output = ob_get_clean();
    
    // Clean up
    $_acf_temp_data = null;
    
    if ($echo) {
        echo $output;
    } else {
        return $output;
    }
}

/**
 * Quick render functions for common global blocks
 */

/**
 * Render home stats block anywhere
 */
function render_stats_block($title = '', $description = '', $stats = [], $cta = null) {
    render_global_block('home_stats_block', [
        'title' => $title,
        'description' => $description,
        'stats_items' => $stats,
        'stats_cta' => $cta,
        'hide_block' => false
    ]);
}

/**
 * Render get in touch block anywhere
 */
function render_contact_block($title = '', $description = '', $phone = '', $email = '', $address = '', $form_id = '') {
    render_global_block('get_in_touch_block', [
        'title' => $title,
        'description' => $description,
        'phone' => $phone,
        'email' => $email,
        'address' => $address,
        'form_id' => $form_id,
        'hide_block' => false
    ]);
}

/**
 * Render certificate block anywhere
 */
function render_certificate_block($title = '', $description = '', $image = null, $certificates = []) {
    render_global_block('certificate_block', [
        'title' => $title,
        'description' => $description,
        'certificate_image' => $image,
        'certificates_list' => $certificates,
        'hide_block' => false
    ]);
}

/**
 * List all available global blocks
 */
function get_global_blocks() {
    $global_dir = get_template_directory() . '/templates/blocks/global/';
    $blocks = [];
    
    if (is_dir($global_dir)) {
        $files = glob($global_dir . '*.php');
        foreach ($files as $file) {
            $block_name = basename($file, '.php');
            $blocks[] = $block_name;
        }
    }
    
    return $blocks;
}