<!DOCTYPE html>
<html <?php language_attributes(); ?>>

<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="format-detection" content="telephone=no">
    <meta name="theme-url" content="<?php echo esc_url(get_template_directory_uri()); ?>">

    <link rel="profile" href="https://gmpg.org/xfn/11">
    <link rel="pingback" href="<?php bloginfo('pingback_url'); ?>">

    <?php
    // 2025 Performance Optimizations - SAFE MODE (non-breaking)

    // 1. Preconnect to external domains (safe optimization)
    echo '<link rel="preconnect" href="https://fonts.googleapis.com" crossorigin>' . "\n";
    echo '<link rel="preconnect" href="https://unpkg.com" crossorigin>' . "\n";

    // 2. Minimal critical CSS (only performance optimizations)
    if (function_exists('output_critical_css')) {
      output_critical_css();
    }

// Note: Font loading and async CSS disabled to preserve existing layout
// Your existing CSS and fonts will load normally
?>

    <?php if (get_field('header_code', 'option')) {
      echo the_field('header_code', 'option');
    } ?>
    <?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>

    <script type="text/javascript">
        var ajaxurl = "<?php echo admin_url('admin-ajax.php'); ?>";
    </script>
    <div class="scrollToTop scrollToTop-3 active-progress">
        <div class="arrowUp">
            <svg class="w-[20px] h-[20px]" data-name="1-Arrow Up" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 32 32">
                <path d="m26.71 10.29-10-10a1 1 0 0 0-1.41 0l-10 10 1.41 1.41L15 3.41V32h2V3.41l8.29 8.29z" />
            </svg>
        </div>
        <div class="water" style="transform: translate(0px, 80%);">
            <svg viewBox="0 0 560 20" class="water_wave water_wave_back">
                <use xlink:href="#wave"></use>
            </svg>
            <svg viewBox="0 0 560 20" class="water_wave water_wave_front">
                <use xlink:href="#wave"></use>
            </svg>
            <svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 560 20" style="display: none;">
                <symbol id="wave">
                    <path d="M420,20c21.5-0.4,38.8-2.5,51.1-4.5c13.4-2.2,26.5-5.2,27.3-5.4C514,6.5,518,4.7,528.5,2.7c7.1-1.3,17.9-2.8,31.5-2.7c0,0,0,0,0,0v20H420z" fill="#" style="transition: stroke-dashoffset 10ms linear; stroke-dasharray: 301.839, 301.839; stroke-dashoffset: 236.203px;"></path>
                    <path d="M420,20c-21.5-0.4-38.8-2.5-51.1-4.5c-13.4-2.2-26.5-5.2-27.3-5.4C326,6.5,322,4.7,311.5,2.7C304.3,1.4,293.6-0.1,280,0c0,0,0,0,0,0v20H420z" fill="#" style="transition: stroke-dashoffset 10ms linear; stroke-dasharray: 301.839, 301.839; stroke-dashoffset: 236.203px;"></path>
                    <path d="M140,20c21.5-0.4,38.8-2.5,51.1-4.5c13.4-2.2,26.5-5.2,27.3-5.4C234,6.5,238,4.7,248.5,2.7c7.1-1.3,17.9-2.8,31.5-2.7c0,0,0,0,0,0v20H140z" fill="#" style="transition: stroke-dashoffset 10ms linear; stroke-dasharray: 301.839, 301.839; stroke-dashoffset: 236.203px;"></path>
                    <path d="M140,20c-21.5-0.4-38.8-2.5-51.1-4.5c-13.4-2.2-26.5-5.2-27.3-5.4C46,6.5,42,4.7,31.5,2.7C24.3,1.4,13.6-0.1,0,0c0,0,0,0,0,0l0,20H140z" fill="#" style="transition: stroke-dashoffset 10ms linear; stroke-dasharray: 301.839, 301.839; stroke-dashoffset: 236.203px;"></path>
                </symbol>
            </svg>
        </div>
    </div>
    <header id="header" data-component="Header" data-load="eager">
        <nav id="topnav" class="defaultscroll is-sticky">
            <div class="container-fluid relative flex items-center lg:justify-between gap-2">

                <!-- Logo -->
                <div class="site-logo flex items-center gap-2">
                    <?php if (function_exists('the_custom_logo')) {
                      the_custom_logo();
                    } ?>

                </div>

                <!-- Mobile Menu Toggle -->
                <div class="menu-extras">
                    <div class="menu-item">
                        <a class="navbar-toggle" id="isToggle">
                            <div class="lines"><span></span><span></span><span></span></div>
                        </a>
                    </div>
                </div>

                <!-- Navigation (Desktop) -->
                <div id="navigation" class="flex items-center justify-end">
                    <?php wp_nav_menu([
                      'theme_location' => 'primary',
                      'menu_class' => 'navigation-menu',
                      'container' => false,
                      'walker' => new Custom_Nav_Walker(),
                      'fallback_cb' => false,
                    ]); ?>
                </div>


                <?php
                $contact_us = get_field('contact_us', 'option');
                if ($contact_us):
                  $enquiry_target = !empty($contact_us['target'])
                    ? $contact_us['target']
                    : '_self'; ?>
                    <a aria-label="Contact us"
                        href="<?= esc_url($contact_us['url']) ?>"
                        target="<?= esc_attr($enquiry_target) ?>"
                        aria-label="<?= esc_attr($contact_us['title']) ?>"
                        class="max-sl:!hidden btn">
                        <span class="z-10"><?= esc_html($contact_us['title']) ?></span>
                    </a>
                <?php
                endif;
                ?>
                <!-- Navigation (Mobile) -->
                <div id="navigation-mobile" aria-hidden="false">
                    <div class="mobile-header">
                        <div class="site-logo">
                            <?php if (function_exists('the_custom_logo')) {
                              the_custom_logo();
                            } ?>
                        </div>
                        <a class="navbar-toggle mobile-close" id="mobileClose">
                            <div class="lines"><span></span><span></span><span></span></div>
                        </a>
                    </div>

                    <?php wp_nav_menu([
                      'theme_location' => 'mobile',
                      'menu_class' => 'navigation-menu',
                      'container' => false,
                      'walker' => new Mobile_Nav_Walker(),
                      'fallback_cb' => false,
                    ]); ?>
                    <?php
                    $contact_us = get_field('contact_us', 'option');
                    if ($contact_us):

                      $enquiry_target = !empty($contact_us['target'])
                        ? $contact_us['target']
                        : '_self';
                      $contact_url = esc_url($contact_us['url']);
                      $contact_title = esc_html($contact_us['title']);
                      ?>
                        <button type="button"
                            aria-label="Contact us"
                            class="my-6 ml-5 btn"
                            onclick="window.location.href='<?php echo $contact_url; ?>'">
                            <span class="z-10"><?php echo $contact_title; ?></span>
                        </button>
                    <?php
                    endif;
                    ?>
                </div>

                <!-- Mobile overlay -->
                <div id="nav-overlay" aria-hidden="true"></div>

            </div>
        </nav>
    </header>