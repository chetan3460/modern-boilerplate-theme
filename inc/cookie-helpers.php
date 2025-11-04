<?php
/**
 * Cookie Consent Helper Functions
 * 
 * Provides PHP utilities for checking cookie consent status
 * and conditionally loading tracking scripts
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

/**
 * Check if user has accepted cookies
 * 
 * @return bool True if cookies are accepted
 */
function resplast_has_cookie_consent() {
    return isset($_COOKIE['resplast_cookie_consent']) 
           && $_COOKIE['resplast_cookie_consent'] === 'accepted';
}

/**
 * Check if user has rejected cookies
 * 
 * @return bool True if cookies are rejected
 */
function resplast_has_rejected_cookies() {
    return isset($_COOKIE['resplast_cookie_consent']) 
           && $_COOKIE['resplast_cookie_consent'] === 'rejected';
}

/**
 * Conditionally enqueue tracking scripts based on cookie consent
 * 
 * Usage: Uncomment and configure the tracking scripts you need
 */
function resplast_enqueue_tracking_scripts() {
    if (!resplast_has_cookie_consent()) {
        return;
    }

    // ========================================
    // OPTION 1: Google Analytics (GA4)
    // ========================================
    // Replace G-XXXXXXXXXX with your GA4 Measurement ID
    /*
    ?>
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-XXXXXXXXXX"></script>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());
        gtag('config', 'G-XXXXXXXXXX');
    </script>
    <?php
    */

    // ========================================
    // OPTION 2: Google Tag Manager
    // ========================================
    // Replace GTM-XXXXXX with your GTM Container ID
    /*
    ?>
    <script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
    new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
    j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
    'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
    })(window,document,'script','dataLayer','GTM-XXXXXX');</script>
    <?php
    */

    // ========================================
    // OPTION 3: Facebook Pixel
    // ========================================
    // Replace YOUR_PIXEL_ID with your Facebook Pixel ID
    /*
    ?>
    <script>
        !function(f,b,e,v,n,t,s)
        {if(f.fbq)return;n=f.fbq=function(){n.callMethod?
        n.callMethod.apply(n,arguments):n.queue.push(arguments)};
        if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';
        n.queue=[];t=b.createElement(e);t.async=!0;
        t.src=v;s=b.getElementsByTagName(e)[0];
        s.parentNode.insertBefore(t,s)}(window, document,'script',
        'https://connect.facebook.net/en_US/fbevents.js');
        fbq('init', 'YOUR_PIXEL_ID');
        fbq('track', 'PageView');
    </script>
    <noscript>
        <img height="1" width="1" style="display:none" 
             src="https://www.facebook.com/tr?id=YOUR_PIXEL_ID&ev=PageView&noscript=1"/>
    </noscript>
    <?php
    */

    // ========================================
    // OPTION 4: LinkedIn Insight Tag
    // ========================================
    // Replace YOUR_PARTNER_ID with your LinkedIn Partner ID
    /*
    ?>
    <script type="text/javascript">
    _linkedin_partner_id = "YOUR_PARTNER_ID";
    window._linkedin_data_partner_ids = window._linkedin_data_partner_ids || [];
    window._linkedin_data_partner_ids.push(_linkedin_partner_id);
    </script><script type="text/javascript">
    (function(l) {
    if (!l){window.lintrk = function(a,b){window.lintrk.q.push([a,b])};
    window.lintrk.q=[]}
    var s = document.getElementsByTagName("script")[0];
    var b = document.createElement("script");
    b.type = "text/javascript";b.async = true;
    b.src = "https://snap.licdn.com/li.lms-analytics/insight.min.js";
    s.parentNode.insertBefore(b, s);})(window.lintrk);
    </script>
    <noscript>
        <img height="1" width="1" style="display:none;" alt="" 
             src="https://px.ads.linkedin.com/collect/?pid=YOUR_PARTNER_ID&fmt=gif" />
    </noscript>
    <?php
    */

    // ========================================
    // Add more tracking scripts below as needed
    // ========================================
}
add_action('wp_head', 'resplast_enqueue_tracking_scripts');

/**
 * Add consent mode for Google Analytics (optional)
 * This sets default consent to denied until user accepts
 */
function resplast_add_gtag_consent_mode() {
    ?>
    <script>
        // Google Consent Mode V2
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        
        // Default consent to denied
        gtag('consent', 'default', {
            'analytics_storage': 'denied',
            'ad_storage': 'denied',
            'ad_user_data': 'denied',
            'ad_personalization': 'denied'
        });

        // Listen for consent changes from cookie banner
        document.addEventListener('cookieConsentGranted', function() {
            gtag('consent', 'update', {
                'analytics_storage': 'granted',
                'ad_storage': 'granted',
                'ad_user_data': 'granted',
                'ad_personalization': 'granted'
            });
        });
    </script>
    <?php
}
// Uncomment to enable Google Consent Mode
// add_action('wp_head', 'resplast_add_gtag_consent_mode', 1);

/**
 * Add cookie policy link to footer (optional)
 */
function resplast_add_cookie_policy_link() {
    if (!is_page('cookie-policy')) {
        echo '<a href="/cookie-policy" class="text-sm text-gray-500 hover:text-red-600">Cookie Policy</a>';
    }
}

/**
 * Localize cookie consent text for JavaScript
 * Makes text editable from PHP and translatable
 */
function resplast_localize_cookie_consent() {
    wp_localize_script('main', 'cookieConsentText', array(
        'title' => __('We use cookies to give you the best possible experience on our website.', 'resplast'),
        'description' => __('We use cookies and similar technologies to enhance site performance, personalize your experience, and gather anonymous data about how you use our site. We also work with trusted partners to understand general trends like interests and online behavior. You can manage your preferences anytime.', 'resplast'),
        'policyText' => __('Learn more in our', 'resplast'),
        'policyLink' => __('Cookies Policy', 'resplast'),
        'policyUrl' => home_url('/resplast/legal-privacy-policy/'),
        'acceptButton' => __('Accept All', 'resplast'),
        'rejectButton' => __('Reject All', 'resplast'),
        'acceptLabel' => __('Accept all cookies', 'resplast'),
        'rejectLabel' => __('Reject all cookies', 'resplast'),
    ));
}
add_action('wp_enqueue_scripts', 'resplast_localize_cookie_consent');

/**
 * Alternative: Filter to customize cookie consent text
 * Usage: add_filter('resplast_cookie_consent_text', 'your_custom_function');
 */
function resplast_get_cookie_consent_config() {
    $config = array(
        'title' => 'We use cookies to give you the best possible experience on our website.',
        'description' => 'We use cookies and similar technologies to enhance site performance, personalize your experience, and gather anonymous data about how you use our site. We also work with trusted partners to understand general trends like interests and online behavior. You can manage your preferences anytime.',
        'policyText' => 'Learn more in our',
        'policyLink' => 'Cookies Policy',
        'policyUrl' => home_url('/resplast/legal-privacy-policy/'),
        'acceptButton' => 'Accept All',
        'rejectButton' => 'Reject All',
    );
    
    return apply_filters('resplast_cookie_consent_config', $config);
}
