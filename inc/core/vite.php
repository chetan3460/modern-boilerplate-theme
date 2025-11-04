<?php
// functions.php (or inc/vite.php)

/**
 * Environment detection
 */
if (!defined('WP_ENV')) {
  $host = $_SERVER['HTTP_HOST'] ?? 'localhost';
  define(
    'WP_ENV',
    preg_match('/localhost|\.local|\.test|127\.0\.0\.1/', $host) ? 'development' : 'production'
  );
}

/**
 * Is Vite dev server up?
 */
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

/**
 * Read manifest (dist/.vite/manifest.json)
 */
function get_vite_manifest()
{
  $path = get_template_directory() . '/dist/.vite/manifest.json';
  if (!file_exists($path)) {
    return [];
  }
  $json = file_get_contents($path);
  $manifest = json_decode($json, true);
  return is_array($manifest) ? $manifest : [];
}

/**
 * Find the manifest entry key for the main entry
 * This is resilient to variations: searches for keys ending with main.js or keys containing '/main' or returns first entry as fallback.
 */
function vite_find_entry_key($manifest, $needle = 'main.js')
{
  if (!is_array($manifest) || empty($manifest)) {
    return null;
  }

  // 1) exact match ends with needle
  foreach ($manifest as $key => $val) {
    if (substr($key, -strlen($needle)) === $needle) {
      return $key;
    }
  }

  // 2) contains 'main' and '.js'
  foreach ($manifest as $key => $val) {
    if (strpos($key, 'main') !== false && substr($key, -3) === 'js') {
      return $key;
    }
  }

  // 3) fallback: first key
  reset($manifest);
  $first = key($manifest);
  return $first;
}

/**
 * Enqueue CSS from manifest (production only)
 */
add_action(
  'wp_enqueue_scripts',
  function () {
    if (is_admin() || is_dev_mode()) {
      return;
    }

    $manifest = get_vite_manifest();
    if (empty($manifest)) {
      return;
    }

    $is_home = is_front_page() || is_home();

    // Home page: if separate home.css exists in manifest, enqueue ONLY that and bail
    if ($is_home && isset($manifest['css/home.css']) && !empty($manifest['css/home.css']['file'])) {
      $href = get_template_directory_uri() . '/dist/' . ltrim($manifest['css/home.css']['file'], '/');
      $version = filemtime(get_template_directory() . '/dist/' . ltrim($manifest['css/home.css']['file'], '/'));
      wp_enqueue_style('vite-home-css', $href, [], $version, 'all');
      do_action('vite_enqueued_home_css');
      return;
    }

    // Method 1: Check if CSS is bundled with main.js entry
    $entry_key = vite_find_entry_key($manifest, 'main.js');
    if ($entry_key && isset($manifest[$entry_key])) {
      $entry = $manifest[$entry_key];

      // If CSS extracted with main entry, enqueue it
      if (!empty($entry['css']) && is_array($entry['css'])) {
        foreach ($entry['css'] as $css_path) {
          $handle = 'vite-style-' . md5($css_path);
          $href = get_template_directory_uri() . '/dist/' . ltrim($css_path, '/');
          $version = filemtime(get_template_directory() . '/dist/' . ltrim($css_path, '/'));
          wp_enqueue_style($handle, $href, [], $version, 'all');
        }
      }
    }

    // Method 2: Check for separate CSS entries (when cssCodeSplit: false)
    foreach ($manifest as $key => $entry) {
      // Look for CSS files directly in manifest
      if (isset($entry['file']) && pathinfo($entry['file'], PATHINFO_EXTENSION) === 'css') {
        $handle = 'vite-style-' . md5($entry['file']);
        $href = get_template_directory_uri() . '/dist/' . ltrim($entry['file'], '/');
        $version = filemtime(get_template_directory() . '/dist/' . ltrim($entry['file'], '/'));
        wp_enqueue_style($handle, $href, [], $version, 'all');
      }
    }
  },
  20
);

/**
 * Inject dev scripts in head (dev mode only) for early CSS via JS import
 *
 * Backup of previous CSS link injection left commented below for reference.
 */
add_action(
  'wp_head',
  function () {
    if (is_admin() || !is_dev_mode()) {
      return;
    }

    // In dev, explicitly include CSS so we can choose home.css on homepage
    $is_home = is_front_page() || is_home();
    $css_path = $is_home ? 'http://localhost:3000/css/home.css' : 'http://localhost:3000/css/style.css';
    echo '<link rel="stylesheet" href="' . esc_url($css_path) . '">' . PHP_EOL;

    // Dev modules for HMR
    echo '<script type="module" src="http://localhost:3000/@vite/client"></script>' . PHP_EOL;
    echo '<script type="module" src="http://localhost:3000/js/main.js"></script>' . PHP_EOL;
  },
  1
);

/* BACKUP (previous approach, not used now):
add_action('wp_head', function () {
    if (is_admin() || !is_dev_mode()) return;
    // Load CSS directly in head to prevent flash
    echo '<link rel="stylesheet" href="http://localhost:3000/css/style.css">' . PHP_EOL;
}, 1);
*/

/**
 * Inject JS module in footer (dev or prod)
 */
add_action(
  'wp_footer',
  function () {
    if (is_admin()) {
      return;
    }

    if (is_dev_mode()) {
      // Dev: include vite client + module (moved to wp_head for early CSS via JS import)
      // @vite/client should be loaded first for HMR to work
      /* BACKUP (previous footer injection, now moved to head):
        echo '<script type="module" src="http://localhost:3000/@vite/client"></script>' . PHP_EOL;
        echo '<script type="module" src="http://localhost:3000/js/main.js"></script>' . PHP_EOL;
        */

      // Dev-only floating badge (will not render in production)
      echo '<style id="vite-dev-badge-style">#vite-dev-badge{position:fixed;right:16px;bottom:16px;z-index:99999;display:inline-flex;align-items:center;justify-content:center;width:46px;height:46px;border-radius:50%;background:linear-gradient(135deg,#646cff,#00d4ff);color:#fff;box-shadow:0 10px 25px rgba(0,0,0,.2);text-decoration:none}#vite-dev-badge svg{width:26px;height:26px;fill:#fff}</style>' .
        PHP_EOL;
      echo '<a id="vite-dev-badge" href="http://localhost:3000" target="_blank" title="Vite Dev Server">' .
        '<svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">' .
        '<path d="M13 2L3 14h7l-1 8 10-12h-7l1-8z"/></svg>' .
        '</a>' .
        PHP_EOL;
    } else {
      // Production: load built file(s) from manifest
      $manifest = get_vite_manifest();
      if (empty($manifest)) {
        return;
      }

      $entry_key = vite_find_entry_key($manifest, 'main.js');
      if (!$entry_key || !isset($manifest[$entry_key])) {
        return;
      }

      $entry = $manifest[$entry_key];

      // Module file
      if (!empty($entry['file'])) {
        $src = get_template_directory_uri() . '/dist/' . ltrim($entry['file'], '/');
        // Add timestamp for cache busting
        $cache_bust = '?v=' . filemtime(get_template_directory() . '/dist/' . ltrim($entry['file'], '/'));
        echo '<script type="module" src="' . esc_url($src . $cache_bust) . '"></script>' . PHP_EOL;
      }

      // Optionally preload imports (modulepreload)
      if (!empty($entry['imports']) && is_array($entry['imports'])) {
        foreach ($entry['imports'] as $importKey) {
          if (!empty($manifest[$importKey]['file'])) {
            $file = $manifest[$importKey]['file'];
            $href = get_template_directory_uri() . '/dist/' . ltrim($file, '/');
            echo '<link rel="modulepreload" href="' . esc_url($href) . '">' . PHP_EOL;
          }
        }
      }
    }

    // Fallback error logging for debugging
    echo '<script>
    document.querySelectorAll("script[type=\'module\']").forEach(tag => {
        tag.onerror = () => console.error("[vite-theme] JS load error:", tag.src);
    });
    document.querySelectorAll("link[rel=\'stylesheet\']").forEach(tag => {
        tag.onerror = () => console.error("[vite-theme] CSS load error:", tag.href);
    });
    </script>' . PHP_EOL;

    echo '<!-- WP_ENV: ' . WP_ENV . ', Dev: ' . (is_dev_mode() ? 'yes' : 'no') . ' -->' . PHP_EOL;
  },
  100
);
