<?php
/**
 * Performance Helper Functions (2025 Standards)
 * =============================================================================
 * 
 * Functions to optimize Core Web Vitals:
 * - LCP (Largest Contentful Paint) ≤ 2.5s
 * - INP (Interaction to Next Paint) ≤ 200ms  
 * - CLS (Cumulative Layout Shift) ≤ 0.1
 * - TTFB (Time To First Byte) ≤ 0.8s
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Enhanced image output with 2025 performance attributes
 */
function resplast_optimized_image($image_id, $size = 'large', $options = []) {
    if (!$image_id) return '';
    
    $defaults = [
        'class' => '',
        'alt' => '',
        'priority' => false, // fetchpriority="high"
        'lazy' => true,      // loading="lazy" 
        'async_decode' => true, // decoding="async"
        'content_visibility' => false, // content-visibility: auto
        'intrinsic_size' => true, // contain-intrinsic-size for layout stability
        'avif_support' => true,  // AVIF with WebP fallback
    ];
    
    $options = array_merge($defaults, $options);
    
    $image_data = wp_get_attachment_image_src($image_id, $size);
    if (!$image_data) return '';
    
    [$src, $width, $height] = $image_data;
    $alt = $options['alt'] ?: get_post_meta($image_id, '_wp_attachment_image_alt', true);
    
    // Build modern image formats
    $avif_src = '';
    $webp_src = '';
    
    if ($options['avif_support']) {
        // Check if AVIF version exists
        $avif_path = str_replace(['.jpg', '.jpeg', '.png'], '.avif', $src);
        $avif_file = str_replace(wp_upload_dir()['baseurl'], wp_upload_dir()['basedir'], $avif_path);
        if (file_exists($avif_file)) {
            $avif_src = $avif_path;
        }
        
        // Check if WebP version exists
        $webp_path = str_replace(['.jpg', '.jpeg', '.png'], '.webp', $src);
        $webp_file = str_replace(wp_upload_dir()['baseurl'], wp_upload_dir()['basedir'], $webp_path);
        if (file_exists($webp_file)) {
            $webp_src = $webp_path;
        }
    }
    
    // Generate srcset for responsive images
    $srcset = wp_get_attachment_image_srcset($image_id, $size);
    $sizes = wp_get_attachment_image_sizes($image_id, $size);
    
    // Build attributes
    $attrs = [
        'width' => $width,
        'height' => $height,
        'alt' => esc_attr($alt),
        'class' => esc_attr($options['class']),
    ];
    
    // Add any custom attributes (like data-* attributes)
    $reserved_keys = ['class', 'alt', 'priority', 'lazy', 'async_decode', 'content_visibility', 'intrinsic_size', 'avif_support'];
    foreach ($options as $key => $value) {
        if (!in_array($key, $reserved_keys) && $value !== null && $value !== '') {
            $attrs[$key] = esc_attr($value);
        }
    }
    
    if ($options['priority']) {
        $attrs['fetchpriority'] = 'high';
        $attrs['loading'] = 'eager';
    } elseif ($options['lazy']) {
        $attrs['loading'] = 'lazy';
    }
    
    if ($options['async_decode']) {
        $attrs['decoding'] = 'async';
    }
    
    if ($srcset) {
        $attrs['srcset'] = $srcset;
    }
    
    if ($sizes) {
        $attrs['sizes'] = $sizes;
    }
    
    // Content visibility styles for off-screen optimization
    $styles = [];
    if ($options['content_visibility']) {
        $styles[] = 'content-visibility: auto';
    }
    if ($options['intrinsic_size']) {
        $styles[] = "contain-intrinsic-size: {$width}px {$height}px";
    }
    
    if (!empty($styles)) {
        $attrs['style'] = implode('; ', $styles);
    }
    
    // Build final HTML
    if ($avif_src || $webp_src) {
        $html = '<picture>';
        
        if ($avif_src) {
            $html .= sprintf('<source srcset="%s" type="image/avif">', esc_url($avif_src));
        }
        
        if ($webp_src) {
            $html .= sprintf('<source srcset="%s" type="image/webp">', esc_url($webp_src));
        }
        
        $html .= sprintf('<img src="%s" %s>', esc_url($src), build_html_attributes($attrs));
        $html .= '</picture>';
    } else {
        $html = sprintf('<img src="%s" %s>', esc_url($src), build_html_attributes($attrs));
    }
    
    return $html;
}

/**
 * Helper to build HTML attributes string
 */
function build_html_attributes($attrs) {
    $output = [];
    foreach ($attrs as $key => $value) {
        if ($value !== '' && $value !== null) {
            $output[] = sprintf('%s="%s"', $key, $value);
        }
    }
    return implode(' ', $output);
}

/**
 * Detect if browser supports modern image formats
 */
function browser_supports_avif() {
    return isset($_SERVER['HTTP_ACCEPT']) && strpos($_SERVER['HTTP_ACCEPT'], 'image/avif') !== false;
}

function browser_supports_webp() {
    return isset($_SERVER['HTTP_ACCEPT']) && strpos($_SERVER['HTTP_ACCEPT'], 'image/webp') !== false;
}

/**
 * Generate AVIF versions of images (extends existing WebP handler)
 */
function generate_avif_version($image_path, $quality = 80) {
    if (!file_exists($image_path)) return false;
    
    $avif_path = preg_replace('/\.(jpe?g|png)$/i', '.avif', $image_path);
    
    // Skip if AVIF already exists and is newer
    if (file_exists($avif_path) && filemtime($avif_path) >= filemtime($image_path)) {
        return $avif_path;
    }
    
    // Try to convert using ImageMagick if available
    if (extension_loaded('imagick')) {
        try {
            $imagick = new Imagick($image_path);
            $imagick->setImageFormat('avif');
            $imagick->setImageCompressionQuality($quality);
            $result = $imagick->writeImage($avif_path);
            $imagick->destroy();
            
            if ($result && file_exists($avif_path)) {
                return $avif_path;
            }
        } catch (Exception $e) {
            error_log('AVIF conversion failed: ' . $e->getMessage());
        }
    }
    
    // Try command line avifenc if available
    $avifenc_path = '/usr/local/bin/avifenc'; // Common path, adjust as needed
    if (is_executable($avifenc_path)) {
        $cmd = sprintf(
            '%s --min 0 --max 63 -a end-usage=q -a cq-level=%d -a tune=ssim %s %s 2>&1',
            escapeshellcmd($avifenc_path),
            $quality,
            escapeshellarg($image_path),
            escapeshellarg($avif_path)
        );
        
        exec($cmd, $output, $return_code);
        
        if ($return_code === 0 && file_exists($avif_path)) {
            return $avif_path;
        }
    }
    
    return false;
}

/**
 * Preload critical resources
 */
function add_resource_preload($href, $as, $type = null, $crossorigin = false) {
    $attrs = [
        'rel' => 'preload',
        'href' => $href,
        'as' => $as,
    ];
    
    if ($type) {
        $attrs['type'] = $type;
    }
    
    if ($crossorigin) {
        $attrs['crossorigin'] = '';
    }
    
    printf('<link %s>', build_html_attributes($attrs));
    echo "\n";
}

/**
 * Add prefetch for likely next pages
 */
function add_prefetch_hints() {
    // Common next pages based on current page
    $prefetch_urls = [];
    
    if (is_front_page()) {
        $prefetch_urls = [
            get_permalink(get_page_by_path('about')),
            get_permalink(get_page_by_path('contact')),
            get_post_type_archive_link('news'),
        ];
    } elseif (is_page('about')) {
        $prefetch_urls = [
            get_permalink(get_page_by_path('contact')),
            home_url('/'),
        ];
    } elseif (is_singular('news')) {
        $prefetch_urls = [
            get_post_type_archive_link('news'),
            home_url('/'),
        ];
    }
    
    // Filter out empty URLs and current page
    $prefetch_urls = array_filter($prefetch_urls);
    $current_url = home_url($_SERVER['REQUEST_URI']);
    $prefetch_urls = array_diff($prefetch_urls, [$current_url]);
    
    foreach ($prefetch_urls as $url) {
        printf('<link rel="prefetch" href="%s" as="document">', esc_url($url));
        echo "\n";
    }
}

/**
 * Break up long tasks for better INP
 */
function schedule_background_task($callback, $priority = 'background') {
    ?>
    <script>
    (function() {
        const task = <?php echo json_encode($callback); ?>;
        
        if ('scheduler' in window && window.scheduler.postTask) {
            scheduler.postTask(task, { priority: '<?php echo esc_js($priority); ?>' });
        } else {
            setTimeout(task, 0);
        }
    })();
    </script>
    <?php
}

/**
 * Add intersection observer for lazy component loading
 */
function add_intersection_observer_loader() {
    ?>
    <script>
    // Lazy load components when they enter viewport
    const lazyComponents = document.querySelectorAll('[data-lazy-component]');
    
    if (lazyComponents.length > 0) {
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const element = entry.target;
                    const component = element.dataset.lazyComponent;
                    
                    // Load component
                    if (window[component + '_init']) {
                        window[component + '_init'](element);
                    }
                    
                    observer.unobserve(element);
                }
            });
        }, {
            rootMargin: '50px 0px',
            threshold: 0.1
        });
        
        lazyComponents.forEach(el => observer.observe(el));
    }
    </script>
    <?php
}