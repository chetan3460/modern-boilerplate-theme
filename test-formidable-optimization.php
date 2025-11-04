<?php
/**
 * Test Script for Formidable Forms Conditional Loading
 * 
 * Add ?test_formidable=1 to any page URL to see if Formidable scripts are loaded
 * This helps verify the optimization is working correctly
 * 
 * Usage: http://localhost/resplast/your-page/?test_formidable=1
 */

// Only run if test parameter is present
if (isset($_GET['test_formidable']) && $_GET['test_formidable'] == '1') {
    add_action('wp_footer', function() {
        if (is_admin()) return;
        
        echo '<div id="formidable-test" style="position: fixed; top: 10px; right: 10px; background: #333; color: white; padding: 10px; border-radius: 5px; font-size: 12px; z-index: 99999; max-width: 300px;">';
        echo '<strong>🧪 Formidable Forms Test</strong><br>';
        
        // Check if scripts are loaded
        global $wp_scripts;
        $formidable_scripts = [
            'formidable',
            'frm-dom-ready', 
            'frm_form_action_js',
            'frm-validate',
            'frm-show-form',
            'frm_smooth_scroll_js'
        ];
        
        $loaded_count = 0;
        $total_count = count($formidable_scripts);
        
        echo '<div style="margin: 5px 0;">Scripts Status:</div>';
        foreach ($formidable_scripts as $script) {
            $is_loaded = wp_script_is($script, 'enqueued') || wp_script_is($script, 'done');
            if ($is_loaded) $loaded_count++;
            
            $status = $is_loaded ? '✅' : '❌';
            $color = $is_loaded ? '#4CAF50' : '#f44336';
            echo "<div style='color: $color; font-size: 11px;'>$status $script</div>";
        }
        
        // Overall status
        if ($loaded_count === 0) {
            echo '<div style="margin-top: 5px; color: #4CAF50;"><strong>🎉 OPTIMIZED!</strong><br>All Formidable scripts dequeued (172KB saved)</div>';
        } elseif ($loaded_count < $total_count) {
            echo '<div style="margin-top: 5px; color: #FF9800;"><strong>⚠️ PARTIAL</strong><br>Some scripts dequeued</div>';
        } else {
            echo '<div style="margin-top: 5px; color: #2196F3;"><strong>📝 FORM PAGE</strong><br>All scripts loaded (forms detected)</div>';
        }
        
        // Page info
        echo '<div style="margin-top: 5px; font-size: 10px; opacity: 0.8;">';
        echo 'Page: ' . (is_page() ? get_the_title() : get_post_type());
        echo '<br>ID: ' . get_the_ID();
        echo '</div>';
        
        echo '</div>';
        
        // Auto-hide after 10 seconds
        echo '<script>setTimeout(() => { const el = document.getElementById("formidable-test"); if(el) el.style.display = "none"; }, 10000);</script>';
    });
}