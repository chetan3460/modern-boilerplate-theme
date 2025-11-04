<?php
// Preload self-hosted Instrument Sans to improve LCP on first paint.
// Prefer the built (hashed) latin-subset from /dist when available,
// otherwise fall back to the original assets/ files.
add_action(
  'wp_head',
  function () {
    if (is_admin()) {
      return;
    }

    // Skip preload in dev mode if detection is available
    if (function_exists('is_dev_mode') && is_dev_mode()) {
      return;
    }

    $dist_dir = get_template_directory() . '/dist/woff2';
    $dist_uri = get_template_directory_uri() . '/dist/woff2';

    $assets_dir = get_template_directory() . '/assets/fonts/Instrument_Sans';
    $assets_uri = get_template_directory_uri() . '/assets/fonts/Instrument_Sans';

    $href = '';
    $type = 'font/woff2';

    // 1) Try built latin subset woff2 from dist
    if (is_dir($dist_dir)) {
      $matches = glob($dist_dir . '/InstrumentSans-VariableFont_wdth_wght-latin*.woff2');
      if (!$matches) {
        // fallback to full woff2 in dist
        $matches = glob($dist_dir . '/InstrumentSans-VariableFont_wdth_wght*.woff2');
      }
      if ($matches && isset($matches[0])) {
        $href = $dist_uri . '/' . basename($matches[0]);
      }
    }

    // 2) Fallback to assets (subset first, then full)
    if (!$href) {
      if (file_exists($assets_dir . '/InstrumentSans-VariableFont_wdth,wght-latin.woff2')) {
        $href = $assets_uri . '/InstrumentSans-VariableFont_wdth,wght-latin.woff2';
      } elseif (file_exists($assets_dir . '/InstrumentSans-VariableFont_wdth,wght.woff2')) {
        $href = $assets_uri . '/InstrumentSans-VariableFont_wdth,wght.woff2';
      } elseif (file_exists($assets_dir . '/InstrumentSans-VariableFont_wdth,wght.ttf')) {
        $href = $assets_uri . '/InstrumentSans-VariableFont_wdth,wght.ttf';
        $type = 'font/ttf';
      }
    }

    if ($href) {
      echo '<link rel="preload" href="' .
        esc_url($href) .
        '" as="font" type="' .
        esc_attr($type) .
        '" crossorigin>' .
        PHP_EOL;
    }
  },
  1
);
