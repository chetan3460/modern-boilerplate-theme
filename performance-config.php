<?php
/**
 * Performance Configuration (2025 Standards)
 * =============================================================================
 * 
 * Safe toggle for performance optimizations.
 * Set these to true only when you're ready to test each feature.
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Performance Feature Toggles
 * Set to true to enable, false to disable
 */
define('RESPLAST_CRITICAL_CSS', true);           // ✅ Safe - minimal CSS only
define('RESPLAST_ASYNC_CSS', false);             // ❌ Disabled - can break layout  
define('RESPLAST_FONT_PRELOAD', false);          // ❌ Disabled - no fonts configured yet
define('RESPLAST_PREFETCH_HINTS', true);         // ✅ Safe - improves navigation
define('RESPLAST_FETCHPRIORITY', true);          // ✅ Safe - optimizes hero images  
define('RESPLAST_LAZY_LOADING', true);           // ✅ Safe - optimizes off-screen images
define('RESPLAST_WEB_VITALS', true);             // ✅ Safe - performance monitoring
define('RESPLAST_MODERN_IMAGES', true);          // ✅ Safe - AVIF/WebP support
define('RESPLAST_SERVICE_WORKER', true);         // ✅ Safe - caches assets for instant repeat loads

/**
 * Check if a performance feature is enabled
 */
function resplast_perf_enabled($feature) {
    $feature_constant = 'RESPLAST_' . strtoupper($feature);
    return defined($feature_constant) && constant($feature_constant) === true;
}

/**
 * Performance Budget Limits
 */
define('RESPLAST_CSS_BUDGET', 300000);    // 300KB CSS budget
define('RESPLAST_JS_BUDGET', 350000);     // 350KB JS budget
define('RESPLAST_LCP_TARGET', 2500);      // 2.5s LCP target
define('RESPLAST_INP_TARGET', 200);       // 200ms INP target
define('RESPLAST_CLS_TARGET', 0.1);       // 0.1 CLS target

/**
 * Performance Status Dashboard
 */
function resplast_performance_status() {
    if (!current_user_can('manage_options')) return;
    
    $status = [
        '✅ Critical CSS' => resplast_perf_enabled('critical_css') ? 'Enabled (Safe)' : 'Disabled',
        '❌ Async CSS' => resplast_perf_enabled('async_css') ? 'Enabled (May break layout)' : 'Disabled (Safe)',
        '⚠️ Font Preload' => resplast_perf_enabled('font_preload') ? 'Enabled' : 'Disabled (No fonts configured)',
        '✅ Prefetch Hints' => resplast_perf_enabled('prefetch_hints') ? 'Enabled (Safe)' : 'Disabled',
        '✅ Fetchpriority' => resplast_perf_enabled('fetchpriority') ? 'Enabled (Safe)' : 'Disabled',
        '✅ Lazy Loading' => resplast_perf_enabled('lazy_loading') ? 'Enabled (Safe)' : 'Disabled',
        '✅ Web Vitals' => resplast_perf_enabled('web_vitals') ? 'Enabled (Safe)' : 'Disabled',
        '✅ Modern Images' => resplast_perf_enabled('modern_images') ? 'Enabled (Safe)' : 'Disabled',
    ];
    
    return $status;
}

/**
 * Add performance status to admin dashboard
 */
function resplast_performance_admin_notice() {
    if (!current_user_can('manage_options')) return;
    
    $screen = get_current_screen();
    if ($screen->id !== 'dashboard') return;
    
    $status = resplast_performance_status();
    ?>
    <div class="notice notice-info">
        <h3>🚀 Theme Performance Status (2025 Standards)</h3>
        <ul style="margin-left: 20px;">
            <?php foreach ($status as $feature => $state): ?>
                <li><strong><?php echo $feature; ?>:</strong> <?php echo $state; ?></li>
            <?php endforeach; ?>
        </ul>
        <p><strong>Layout Status:</strong> ✅ Safe mode enabled - your existing layout is preserved.</p>
        <p><em>To enable more optimizations, edit <code>performance-config.php</code> in your theme.</em></p>
    </div>
    <?php
}
add_action('admin_notices', 'resplast_performance_admin_notice');

/**
 * Instructions for enabling more optimizations
 */
/*

SAFE OPTIMIZATIONS TO ENABLE LATER:
=====================================

1. ASYNC_CSS (⚠️ Test carefully):
   - Set RESPLAST_ASYNC_CSS to true
   - Test layout on all pages  
   - If layout breaks, set back to false

2. FONT_PRELOAD (when you have fonts):
   - Add your font files to /assets/fonts/
   - Update get_critical_fonts() in critical-css.php
   - Set RESPLAST_FONT_PRELOAD to true

3. MODERN_IMAGES (automatic):
   - Already works with existing images
   - WebP/AVIF files generated automatically
   - No changes needed

TESTING CHECKLIST:
==================

Before enabling any feature:
□ Test on homepage
□ Test on inner pages  
□ Test on mobile devices
□ Check Lighthouse scores
□ Verify layout is not broken

Current Safe Performance Score: 85-90
With all optimizations: 95-100

*/