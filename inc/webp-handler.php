<?php
/**
 * =============================================================================
 * WebP Image Handler
 * =============================================================================
 * 
 * Handles WebP conversion and optimization for uploaded images.
 * Features:
 * - Automatic WebP conversion for JPEG/PNG uploads
 * - PNG fallback creation for WebP uploads
 * - Smart quality detection to prevent blur
 * - Admin interface for manual optimization control
 * - Background processing to avoid upload delays
 * =============================================================================
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Initialize WebP handler
 */
class ResplastWebPHandler 
{
    public function __construct() 
    {
        $this->init_hooks();
    }

    /**
     * Initialize WordPress hooks
     */
    private function init_hooks()
    {
        // Allow WebP and AVIF uploads
        add_filter('upload_mimes', array($this, 'allow_webp_uploads'), 10, 1);
        
        // Handle image processing after WordPress upload
        add_filter('wp_generate_attachment_metadata', array($this, 'schedule_image_conversion'), 20, 2);
        
        // Background conversion actions
        add_action('replast_convert_to_webp', array($this, 'background_webp_conversion'));
        add_action('replast_create_fallback_from_webp', array($this, 'create_fallback_from_webp'));
        
        // Optional: Enable direct WebP URL replacement (currently enabled)
        add_filter('wp_get_attachment_image_src', array($this, 'replace_image_urls_with_webp'), 10, 4);
        add_filter('wp_calculate_image_srcset', array($this, 'replace_srcset_with_webp'), 10, 5);
        
        // Admin interface for manual WebP skip
        add_filter('attachment_fields_to_edit', array($this, 'add_attachment_fields'), 10, 2);
        add_filter('attachment_fields_to_save', array($this, 'save_attachment_fields'), 10, 2);
    }

    /**
     * Allow WebP and AVIF uploads in WordPress
     */
    public function allow_webp_uploads($mimes) 
    {
        $mimes['webp'] = 'image/webp';
        $mimes['avif'] = 'image/avif';
        return $mimes;
    }

    /**
     * Schedule appropriate conversion based on uploaded image type
     */
    public function schedule_image_conversion($metadata, $attachment_id)
    {
        // Skip if metadata generation failed
        if (!$metadata || !is_array($metadata)) {
            return $metadata;
        }

        $file = get_attached_file($attachment_id);
        if (!$file || !file_exists($file)) {
            return $metadata;
        }

        // Check if optimization should be skipped
        if ($this->should_skip_optimization($file, $attachment_id)) {
            error_log('WebP optimization skipped for: ' . basename($file) . ' (quality/size threshold)');
            return $metadata;
        }

        $ext = strtolower(pathinfo($file, PATHINFO_EXTENSION));
        
        // Handle different image types
        if (in_array($ext, ['jpg', 'jpeg', 'png'], true)) {
            // PNG/JPEG → Create WebP versions
            wp_schedule_single_event(time() + 10, 'replast_convert_to_webp', array($attachment_id));
        } elseif ($ext === 'webp') {
            // WebP → Create PNG fallback for compatibility
            wp_schedule_single_event(time() + 10, 'replast_create_fallback_from_webp', array($attachment_id));
        } else {
            // Other formats (GIF, SVG, etc.) → Skip processing
            return $metadata;
        }

        return $metadata;
    }

    /**
     * Background WebP conversion (scheduled after upload)
     */
    public function background_webp_conversion($attachment_id)
    {
        $file = get_attached_file($attachment_id);
        if (!$file || !file_exists($file)) {
            return;
        }

        $metadata = wp_get_attachment_metadata($attachment_id);
        if (!$metadata) {
            return;
        }

        $dir = pathinfo($file, PATHINFO_DIRNAME);
        $basename = pathinfo($file, PATHINFO_FILENAME);

        // Convert original
        $orig_webp = $dir . '/' . $basename . '.webp';
        if (!file_exists($orig_webp)) {
            $this->convert_image_to_webp($file, $orig_webp, 85);
        }

        // Convert thumbnails
        if (!empty($metadata['sizes']) && is_array($metadata['sizes'])) {
            foreach ($metadata['sizes'] as $size => $data) {
                if (empty($data['file'])) continue;
                
                $size_path = $dir . '/' . $data['file'];
                if (!file_exists($size_path)) continue;
                
                $size_ext = strtolower(pathinfo($size_path, PATHINFO_EXTENSION));
                if (!in_array($size_ext, ['jpg', 'jpeg', 'png'], true)) continue;
                
                $base = substr($size_path, 0, -strlen($size_ext) - 1);
                $webp_path = $base . '.webp';
                
                if (!file_exists($webp_path)) {
                    $this->convert_image_to_webp($size_path, $webp_path, 82);
                }
            }
        }
    }

    /**
     * Create PNG fallback from WebP uploads for browser compatibility
     */
    public function create_fallback_from_webp($attachment_id)
    {
        $file = get_attached_file($attachment_id);
        if (!$file || !file_exists($file)) {
            return;
        }

        $metadata = wp_get_attachment_metadata($attachment_id);
        if (!$metadata) {
            return;
        }

        // Only process WebP files
        $ext = strtolower(pathinfo($file, PATHINFO_EXTENSION));
        if ($ext !== 'webp') {
            return;
        }

        $dir = pathinfo($file, PATHINFO_DIRNAME);
        $basename = pathinfo($file, PATHINFO_FILENAME);

        // Create PNG fallback from original WebP
        $png_fallback = $dir . '/' . $basename . '.png';
        if (!file_exists($png_fallback)) {
            $this->convert_webp_to_png($file, $png_fallback);
        }

        // Create PNG fallbacks for thumbnails
        if (!empty($metadata['sizes']) && is_array($metadata['sizes'])) {
            foreach ($metadata['sizes'] as $size => $data) {
                if (empty($data['file'])) continue;
                
                $size_path = $dir . '/' . $data['file'];
                if (!file_exists($size_path)) continue;
                
                $size_ext = strtolower(pathinfo($size_path, PATHINFO_EXTENSION));
                if ($size_ext !== 'webp') continue;
                
                $base = substr($size_path, 0, -5); // Remove '.webp'
                $png_path = $base . '.png';
                
                if (!file_exists($png_path)) {
                    $this->convert_webp_to_png($size_path, $png_path);
                }
            }
        }
    }

    /**
     * Convert JPEG/PNG to WebP using command line tools
     */
    private function convert_image_to_webp($source, $dest, $quality = 82)
    {
        if (!file_exists($source)) {
            return false;
        }

        // Guard against very large files in local dev
        $max_bytes = 10 * 1024 * 1024; // 10MB
        if (@filesize($source) > $max_bytes) {
            return false;
        }

        $info = @getimagesize($source);
        if (!$info || empty($info['mime'])) {
            return false;
        }

        $mime = $info['mime'];
        if (!in_array($mime, ['image/jpeg', 'image/png'], true)) {
            return false;
        }

        // Skip if already up-to-date
        if (file_exists($dest) && filemtime($dest) >= filemtime($source)) {
            return true;
        }

        // Use command-line tools for conversion
        $cwebp_path = '/Applications/XAMPP/xamppfiles/bin/cwebp';
        if (is_executable($cwebp_path)) {
            $cmd = sprintf(
                '%s %s -o %s -q %d 2>&1',
                escapeshellcmd($cwebp_path),
                escapeshellarg($source),
                escapeshellarg($dest),
                (int) $quality
            );
            exec($cmd, $output, $return_code);
            $ok = ($return_code === 0 && file_exists($dest));
            
            // Log errors for debugging
            if (!$ok && defined('WP_DEBUG') && WP_DEBUG) {
                error_log('WebP conversion failed for ' . $source . ': ' . implode(' ', $output));
            }
            
            return $ok;
        } else {
            if (defined('WP_DEBUG') && WP_DEBUG) {
                error_log('cwebp not found or not executable at: ' . $cwebp_path);
            }
        }

        return false;
    }

    /**
     * Convert WebP to PNG using command line tools
     */
    private function convert_webp_to_png($webp_path, $png_path)
    {
        if (!file_exists($webp_path)) {
            return false;
        }

        // Guard against very large files
        $max_bytes = 10 * 1024 * 1024; // 10MB
        if (@filesize($webp_path) > $max_bytes) {
            return false;
        }

        // Use dwebp command to convert WebP to PNG
        $dwebp_path = '/Applications/XAMPP/xamppfiles/bin/dwebp';
        if (is_executable($dwebp_path)) {
            $cmd = sprintf(
                '%s %s -o %s 2>&1',
                escapeshellcmd($dwebp_path),
                escapeshellarg($webp_path),
                escapeshellarg($png_path)
            );
            exec($cmd, $output, $return_code);
            $ok = ($return_code === 0 && file_exists($png_path));
            
            // Log errors for debugging
            if (!$ok && defined('WP_DEBUG') && WP_DEBUG) {
                error_log('WebP to PNG conversion failed for ' . $webp_path . ': ' . implode(' ', $output));
            }
            
            return $ok;
        } else {
            if (defined('WP_DEBUG') && WP_DEBUG) {
                error_log('dwebp not found or not executable at: ' . $dwebp_path);
            }
        }

        return false;
    }

    /**
     * Replace image URLs with WebP URLs for supported browsers
     */
    public function replace_image_urls_with_webp($image, $attachment_id, $size, $icon) 
    {
        // Only replace if not in admin and browser supports WebP
        if (is_admin() || !$this->browser_supports_webp()) {
            return $image;
        }
        
        if (is_array($image) && isset($image[0])) {
            $original_url = $image[0];
            
            // Check if this is a PNG or JPEG
            if (preg_match('/\.(png|jpe?g)(\?.*)?$/i', $original_url, $matches)) {
                // Replace extension with .webp
                $webp_url = preg_replace('/\.(png|jpe?g)(\?.*)?$/i', '.webp$2', $original_url);
                
                // Check if WebP file actually exists
                $upload_dir = wp_upload_dir();
                $webp_path = str_replace($upload_dir['baseurl'], $upload_dir['basedir'], $webp_url);
                
                if (file_exists($webp_path)) {
                    $image[0] = $webp_url;
                }
            }
        }
        
        return $image;
    }

    /**
     * Replace URLs in srcset attributes as well
     */
    public function replace_srcset_with_webp($sources, $size_array, $image_src, $image_meta, $attachment_id) 
    {
        // Only replace if not in admin and browser supports WebP
        if (is_admin() || !$this->browser_supports_webp() || !is_array($sources)) {
            return $sources;
        }
        
        $upload_dir = wp_upload_dir();
        
        foreach ($sources as $width => $source) {
            if (isset($source['url']) && preg_match('/\.(png|jpe?g)(\?.*)?$/i', $source['url'])) {
                $webp_url = preg_replace('/\.(png|jpe?g)(\?.*)?$/i', '.webp$2', $source['url']);
                $webp_path = str_replace($upload_dir['baseurl'], $upload_dir['basedir'], $webp_url);
                
                if (file_exists($webp_path)) {
                    $sources[$width]['url'] = $webp_url;
                }
            }
        }
        
        return $sources;
    }

    /**
     * Detect WebP support from browser headers
     */
    private function browser_supports_webp() 
    {
        return isset($_SERVER['HTTP_ACCEPT']) && strpos($_SERVER['HTTP_ACCEPT'], 'image/webp') !== false;
    }

    /**
     * Determine if image optimization should be skipped to prevent blur
     */
    private function should_skip_optimization($file_path, $attachment_id)
    {
        // Check manual override first
        $skip_webp = get_post_meta($attachment_id, '_skip_webp_optimization', true);
        if ($skip_webp === '1') {
            return true;
        }

        // Get image info
        $info = @getimagesize($file_path);
        if (!$info) {
            return false;
        }

        $width = $info[0];
        $height = $info[1];
        $file_size = filesize($file_path);
        $thresholds = $this->get_optimization_thresholds();
        
        // Skip very small images (likely icons or tiny graphics)
        if ($width < $thresholds['min_width'] || $height < $thresholds['min_height']) {
            return true;
        }

        // Calculate compression ratio to detect already optimized images
        $pixels = $width * $height;
        $bytes_per_pixel = $file_size / $pixels;
        
        // Skip if image is already highly compressed (likely to blur more)
        if ($bytes_per_pixel < $thresholds['min_bytes_per_pixel']) {
            return true;
        }

        // Skip very large images that might lose important detail
        $megapixels = ($width * $height) / (1024 * 1024);
        if ($megapixels > $thresholds['max_megapixels']) {
            return true;
        }

        // Additional checks for specific image characteristics
        return $this->detect_image_characteristics($file_path, $info);
    }

    /**
     * Analyze image characteristics to detect potential blur issues
     */
    private function detect_image_characteristics($file_path, $info)
    {
        $mime = $info['mime'];
        
        // PNG with transparency - might be logos/graphics
        if ($mime === 'image/png') {
            // Check if PNG has transparency (these are often logos/graphics)
            $img = @imagecreatefrompng($file_path);
            if ($img) {
                $has_alpha = false;
                if (function_exists('imagecolorsforindex') && function_exists('imagecolorat')) {
                    // Sample a few pixels to check for transparency
                    $width = imagesx($img);
                    $height = imagesy($img);
                    for ($i = 0; $i < min(10, $width); $i += 2) {
                        for ($j = 0; $j < min(10, $height); $j += 2) {
                            $color = imagecolorat($img, $i, $j);
                            $alpha = ($color >> 24) & 0xFF;
                            if ($alpha > 0) {
                                $has_alpha = true;
                                break 2;
                            }
                        }
                    }
                }
                imagedestroy($img);
                
                // Skip PNGs with transparency (likely logos/graphics)
                if ($has_alpha) {
                    return true;
                }
            }
        }

        return false;
    }

    /**
     * Add admin interface to manually skip WebP optimization
     */
    public function add_attachment_fields($form_fields, $post)
    {
        if (strpos($post->post_mime_type, 'image/') === 0) {
            $skip_webp = get_post_meta($post->ID, '_skip_webp_optimization', true);
            
            $form_fields['skip_webp'] = array(
                'label' => 'WebP Optimization',
                'input' => 'html',
                'html' => '<label for="skip_webp_' . $post->ID . '">' .
                         '<input type="checkbox" id="skip_webp_' . $post->ID . '" name="attachments[' . $post->ID . '][skip_webp]" value="1" ' . checked($skip_webp, '1', false) . ' /> ' .
                         'Skip WebP optimization (prevents potential blur)</label>' .
                         '<p class="description">Check this if the WebP version appears blurry or loses important detail.</p>',
                'helps' => 'Prevent WebP conversion for images that might become blurry'
            );
        }
        return $form_fields;
    }

    /**
     * Save attachment field data
     */
    public function save_attachment_fields($post, $attachment)
    {
        if (isset($attachment['skip_webp'])) {
            update_post_meta($post['ID'], '_skip_webp_optimization', '1');
        } else {
            delete_post_meta($post['ID'], '_skip_webp_optimization');
        }
        return $post;
    }

    /**
     * Get optimization thresholds (can be customized)
     */
    private function get_optimization_thresholds()
    {
        return array(
            'min_width' => 100,           // Skip images smaller than 100px
            'min_height' => 100,          // Skip images smaller than 100px  
            'max_megapixels' => 50,       // Skip images over 50MP
            'min_bytes_per_pixel' => 0.5, // Skip highly compressed images
            'webp_quality' => 85,         // WebP quality for originals
            'webp_quality_thumbs' => 82   // WebP quality for thumbnails
        );
    }
}

// Initialize the WebP handler
new ResplastWebPHandler();
