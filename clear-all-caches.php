<?php
/**
 * Clear all caches script
 * Visit: http://localhost/resplast/wp-content/themes/resplast-theme/clear-all-caches.php
 */

require_once('../../../wp-load.php');

echo "<h1>Cache Clearing Script</h1>";
echo "<style>
    body { font-family: Arial, sans-serif; margin: 20px; }
    .success { background: #d4edda; color: #155724; padding: 10px; border-radius: 5px; margin: 10px 0; }
    .error { background: #f8d7da; color: #721c24; padding: 10px; border-radius: 5px; margin: 10px 0; }
    .info { background: #d1ecf1; color: #0c5460; padding: 10px; border-radius: 5px; margin: 10px 0; }
</style>";

// Clear WordPress object cache
if (function_exists('wp_cache_flush')) {
    wp_cache_flush();
    echo "<div class='success'>✅ WordPress object cache cleared</div>";
}

// Clear transients
global $wpdb;
$transients_cleared = $wpdb->query("DELETE FROM {$wpdb->options} WHERE option_name LIKE '_transient_%' OR option_name LIKE '_site_transient_%'");
echo "<div class='success'>✅ Cleared $transients_cleared transients</div>";

// Clear rewrite rules
flush_rewrite_rules();
echo "<div class='success'>✅ Rewrite rules flushed</div>";

// Clear Formidable Forms cache specifically
if (class_exists('FrmDb')) {
    // Clear Formidable cache
    FrmDb::cache_delete_group('frm_forms');
    FrmDb::cache_delete_group('frm_fields');
    echo "<div class='success'>✅ Formidable Forms cache cleared</div>";
}

// Clear opcache if available
if (function_exists('opcache_reset')) {
    opcache_reset();
    echo "<div class='success'>✅ PHP OPcache cleared</div>";
}

// Clear common caching plugins
// WP Rocket
if (function_exists('rocket_clean_domain')) {
    rocket_clean_domain();
    echo "<div class='success'>✅ WP Rocket cache cleared</div>";
}

// W3 Total Cache
if (function_exists('w3tc_flush_all')) {
    w3tc_flush_all();
    echo "<div class='success'>✅ W3 Total Cache cleared</div>";
}

// WP Super Cache
if (function_exists('wp_cache_clear_cache')) {
    wp_cache_clear_cache();
    echo "<div class='success'>✅ WP Super Cache cleared</div>";
}

// LiteSpeed Cache
if (class_exists('LiteSpeed_Cache_API')) {
    LiteSpeed_Cache_API::purge_all();
    echo "<div class='success'>✅ LiteSpeed Cache cleared</div>";
}

echo "<h2>Next Steps</h2>";
echo "<div class='info'>
<strong>After clearing caches:</strong><br>
1. Hard refresh your browser (Cmd+Shift+R on Mac, Ctrl+Shift+R on Windows)<br>
2. Try opening both pages in incognito/private mode<br>
3. Check if the phone field now shows the country dropdown<br>
4. If still not working, check the Formidable form settings
</div>";

echo "<div class='success'>
<strong>All available caches have been cleared!</strong><br>
Please refresh your browser and test both forms again.
</div>";

?>