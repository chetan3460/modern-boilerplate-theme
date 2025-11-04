<?php
/**
 * Critical CSS Management (2025 Standards)
 * =============================================================================
 * 
 * Manages above-the-fold CSS inlining and async loading of non-critical CSS
 * to optimize LCP and prevent render blocking.
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Get critical CSS for current page type
 */
function get_critical_css() {
    $css = '';
    
    // Base critical CSS (always included)
    $css .= get_base_critical_css();
    
    // Page-specific critical CSS
    if (is_front_page()) {
        $css .= get_homepage_critical_css();
    } elseif (is_page()) {
        $css .= get_page_critical_css();
    } elseif (is_singular('news')) {
        $css .= get_news_critical_css();
    } elseif (is_archive()) {
        $css .= get_archive_critical_css();
    }
    
    // Minify CSS
    return minify_css($css);
}

/**
 * Base critical CSS - always above the fold
 */
function get_base_critical_css() {
    return '
    /* Minimal critical CSS - only performance optimizations */
    .content-visibility-auto{content-visibility:auto;contain-intrinsic-size:0 500px}
    
    /* Image optimization - non-breaking */
    picture{display:block}
    
    /* Vite dev badge positioning fix - force floating */
    body #vite-dev-badge, html #vite-dev-badge, #vite-dev-badge{
        position:fixed!important;
        right:16px!important;
        bottom:16px!important;
        z-index:999999!important;
        display:inline-flex!important;
        align-items:center!important;
        justify-content:center!important;
        width:46px!important;
        height:46px!important;
        border-radius:50%!important;
        background:linear-gradient(135deg,#646cff,#00d4ff)!important;
        color:#fff!important;
        box-shadow:0 10px 25px rgba(0,0,0,.2)!important;
        text-decoration:none!important;
        pointer-events:auto!important;
        visibility:visible!important;
        opacity:1!important;
        transform:none!important;
        margin:0!important;
        padding:0!important
    }
    ';
}

/**
 * Homepage-specific critical CSS
 */
function get_homepage_critical_css() {
    return '
    /* Minimal homepage optimizations - non-breaking */
    ';
}

/**
 * Page-specific critical CSS
 */
function get_page_critical_css() {
    return '
    /* Minimal page optimizations - non-breaking */
    ';
}

/**
 * News-specific critical CSS
 */
function get_news_critical_css() {
    return '
    /* Minimal news optimizations - non-breaking */
    ';
}

/**
 * Archive-specific critical CSS
 */
function get_archive_critical_css() {
    return '
    /* Minimal archive optimizations - non-breaking */
    ';
}

/**
 * Output critical CSS inline in head
 */
function output_critical_css() {
    $critical_css = get_critical_css();
    
    if (!empty($critical_css)) {
        echo '<style id="critical-css">' . $critical_css . '</style>' . "\n";
    }
}

/**
 * Load non-critical CSS asynchronously
 */
function load_non_critical_css() {
    // Temporarily disabled to prevent layout breaking
    // CSS will load normally through wp_enqueue_style
    return;
    
    /*
    // Get theme CSS files that should load async
    $css_files = get_non_critical_css_files();
    
    foreach ($css_files as $handle => $file) {
        printf(
            '<link rel="preload" href="%s" as="style" onload="this.onload=null;this.rel=\'stylesheet\'" data-handle="%s">',
            esc_url($file['url']),
            esc_attr($handle)
        );
        echo "\n";
        
        // Noscript fallback
        printf('<noscript><link rel="stylesheet" href="%s"></noscript>', esc_url($file['url']));
        echo "\n";
    }
    */
}

/**
 * Get non-critical CSS files for async loading
 */
function get_non_critical_css_files() {
    $files = [];
    
    // Main theme CSS (non-critical parts)
    $files['theme-main'] = [
        'url' => get_template_directory_uri() . '/style.css',
        'version' => wp_get_theme()->get('Version'),
    ];
    
    // Page-specific CSS
    if (is_front_page()) {
        $files['homepage'] = [
            'url' => get_template_directory_uri() . '/assets/css/home.css',
            'version' => wp_get_theme()->get('Version'),
        ];
    }
    
    // Filter for customization
    return apply_filters('resplast_non_critical_css_files', $files);
}

/**
 * Simple CSS minification
 */
function minify_css($css) {
    // Remove comments
    $css = preg_replace('!/\*[^*]*\*+([^/][^*]*\*+)*/!', '', $css);
    
    // Remove unnecessary whitespace
    $css = preg_replace('/\s+/', ' ', $css);
    $css = preg_replace('/;\s*}/', '}', $css);
    $css = preg_replace('/\s*{\s*/', '{', $css);
    $css = preg_replace('/;\s*/', ';', $css);
    $css = preg_replace('/\s*>\s*/', '>', $css);
    $css = preg_replace('/\s*~\s*/', '~', $css);
    $css = preg_replace('/\s*\+\s*/', '+', $css);
    $css = preg_replace('/\s*,\s*/', ',', $css);
    
    return trim($css);
}

/**
 * Add font preloading with proper fallback metrics
 */
function preload_critical_fonts() {
    // Define critical fonts to preload
    $fonts = get_critical_fonts();
    
    foreach ($fonts as $font) {
        add_resource_preload(
            $font['url'],
            'font',
            $font['type'],
            true // crossorigin
        );
    }
}

/**
 * Get critical fonts that should be preloaded
 */
function get_critical_fonts() {
    $fonts = [];
    
    // Example: Add your critical fonts here
    // $fonts[] = [
    //     'url' => get_template_directory_uri() . '/assets/fonts/Inter-Regular.woff2',
    //     'type' => 'font/woff2',
    // ];
    // 
    // $fonts[] = [
    //     'url' => get_template_directory_uri() . '/assets/fonts/Inter-Bold.woff2',
    //     'type' => 'font/woff2',
    // ];
    
    return apply_filters('resplast_critical_fonts', $fonts);
}

/**
 * Add font-display CSS for better performance
 */
function output_font_display_css() {
    // Disabled to prevent font conflicts with existing theme fonts
    // Only add minimal font-display optimization if needed
    ?>
    <style id="font-display">
    /* Minimal font optimization - non-breaking */
    </style>
    <?php
}
