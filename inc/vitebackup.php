<?php

// Define WP_ENV
if (!defined('WP_ENV')) {
  $host = $_SERVER['HTTP_HOST'] ?? 'localhost';
  define(
    'WP_ENV',
    preg_match('/localhost|\.local|\.test|127\.0\.0\.1/', $host) ? 'development' : 'production'
  );
}

// Detect if Vite dev server is running
function is_dev_mode()
{
  if (defined('WP_ENV') && WP_ENV === 'production') {
    return false;
  }

  $fp = @fsockopen('localhost', 3000, $errno, $errstr, 1);
  $is_up = $fp !== false;
  if ($fp) {
    fclose($fp);
  }
  return $is_up;
}

// Load Vite manifest
function get_vite_manifest()
{
  $path = get_template_directory() . '/dist/.vite/manifest.json';
  if (!file_exists($path)) {
    return [];
  }
  return json_decode(file_get_contents($path), true);
}

// Enqueue CSS from Vite manifest
add_action('wp_enqueue_scripts', function () {
  if (is_admin() || is_dev_mode()) {
    return;
  }
  $manifest = get_vite_manifest();
  if (!isset($manifest['js/main.js'])) {
    return;
  }
  foreach ($manifest['js/main.js']['css'] ?? [] as $css) {
    wp_enqueue_style(
      'vite-style-' . md5($css),
      get_template_directory_uri() . '/dist/' . ltrim($css, '/'),
      [],
      null // No ?ver
    );
  }
});

// Inject JS in footer
add_action('wp_footer', function () {
  if (is_admin()) {
    return;
  }

  if (is_dev_mode()) {
    echo '<script type="module" src="http://localhost:3000/js/main.js"></script>';
  } else {
    $manifest = get_vite_manifest();
    if (isset($manifest['js/main.js']['file'])) {
      $src = get_template_directory_uri() . '/dist/' . ltrim($manifest['js/main.js']['file'], '/');
      echo '<script type="module" src="' . esc_url($src) . '"></script>';
    }
  }

  // Fallback error logging
  echo '<script>
    document.querySelectorAll("script[type=\'module\']").forEach(tag => {
        tag.onerror = () => console.error("[vite-theme] JS load error:", tag.src);
    });
    document.querySelectorAll("link[rel=\'stylesheet\']").forEach(tag => {
        tag.onerror = () => console.error("[vite-theme] CSS load error:", tag.href);
    });
</script>';

  echo '<!-- WP_ENV: ' . WP_ENV . ', Dev: ' . (is_dev_mode() ? 'yes' : 'no') . ' -->';
});
