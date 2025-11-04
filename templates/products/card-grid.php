<?php

/**
 * =============================================================================
 * PRODUCT CARD - GRID VIEW (Compact Design)
 * =============================================================================
 * 
 * This template displays individual product cards in a compact layout
 * matching the design from the provided image.
 * 
 * Expected Context:
 * - Global $post object set to current product
 * - ACF fields for product specifications
 * - Taxonomy terms for chemistry, brand, applications
 * =============================================================================
 */

// Ensure we have a proper WordPress context
if (!function_exists('get_the_ID')) {
    return;
}

// Get product data
$product_id = get_the_ID();
if (!$product_id) {
    return;
}

$product_title = get_the_title();
$product_excerpt = get_the_excerpt();

// Get ACF fields for all product data (with function checks)
$product_description = function_exists('get_field') ? get_field('product_description') : '';
$chemistry_type = function_exists('get_field') ? get_field('chemistry_type') : '';
$applications_list = function_exists('get_field') ? get_field('applications_list') : '';
$solvent = function_exists('get_field') ? get_field('solvent') : '';
$non_volatile = function_exists('get_field') ? get_field('non_volatile_percentage') : '';
$oil_length = function_exists('get_field') ? get_field('oil_length_percentage') : '';
$technical_datasheet = function_exists('get_field') ? get_field('technical_datasheet') : null;

// Get new ACF fields - using correct field names
$features_benefits = [];
$typical_uses = [];
$additional_specifications = [];
$show_product_image = true; // Default to show image

if (function_exists('get_field')) {
    // Get Features & Benefits (repeater field)
    $features_benefits = get_field('features_benefits') ?: [];
    
    // Get Typical Uses (repeater field)
    $typical_uses = get_field('typical_uses') ?: [];
    
    // Get Additional Specifications (repeater field)
    $additional_specifications = get_field('additional_specifications') ?: [];
    
    // Get image visibility setting using helper function
    $show_product_image = function_exists('should_show_product_image') ? should_show_product_image($product_id) : true;
}

// Get product image
$product_image = null;
$default_image = get_stylesheet_directory_uri() . '/assets/images/product-placeholder.svg';

// Try ACF field first
if (function_exists('get_field')) {
    $product_image = get_field('product_image');
}

// If no ACF image, try featured image (with function check)
if (!$product_image && function_exists('get_the_post_thumbnail_id')) {
    $thumbnail_id = get_the_post_thumbnail_id($product_id);
    if ($thumbnail_id) {
        $product_image = $thumbnail_id;
    }
}

// Process the image
if ($product_image) {
    if (is_array($product_image)) {
        // ACF image field
        $product_image_url = $product_image['url'];
        $product_image_alt = $product_image['alt'] ?: $product_title;
    } else {
        // WordPress featured image ID
        $image_src = wp_get_attachment_image_src($product_image, 'large');
        if ($image_src) {
            $product_image_url = $image_src[0];
            $product_image_alt = get_post_meta($product_image, '_wp_attachment_image_alt', true) ?: $product_title;
        } else {
            $product_image_url = $default_image;
            $product_image_alt = $product_title;
        }
    }
} else {
    $product_image_url = $default_image;
    $product_image_alt = $product_title;
}

?>

<article class="product-card bg-white rounded-lg border border-gray-200 hover:border-gray-300 transition-all duration-200 hover:shadow-md overflow-hidden" data-id="<?php echo esc_attr($product_id); ?>" data-index="<?php global $wp_query; echo esc_attr($wp_query->current_post + 1); ?>">
  
  <!-- Product Image (conditional) -->
  <?php if ($show_product_image): ?>
    <div class="bg-gray-50">
      <div class="aspect-[4/3] relative">
        <img src="<?php echo esc_url($product_image_url); ?>" 
             alt="<?php echo esc_attr($product_image_alt); ?>" 
             class="absolute inset-0 w-full h-full object-cover" 
             loading="lazy" 
             decoding="async" />
      </div>
    </div>
  <?php endif; ?>
  
  <!-- Product Content -->
  <div class="p-6">
    
    <!-- Product Header -->
    <div class="flex items-start justify-between mb-6">
      <!-- Product Title -->
      <h3 class="text-xl font-semibold text-gray-900">
        <?php echo esc_html($product_title); ?>
      </h3>
      
      <!-- Technical Data Sheet -->
      <?php if ($technical_datasheet): ?>
        <a href="<?php echo esc_url($technical_datasheet['url']); ?>" 
           target="_blank" 
           rel="noopener"
           class="inline-flex items-center gap-1 text-red-600 hover:text-red-700 text-sm font-medium transition-colors duration-200">
          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
          </svg>
          <span>Technical Data Sheet</span>
        </a>
      <?php endif; ?>
    </div>

    <!-- Product Description -->
    <?php if ($product_description): ?>
      <div class="mb-4">
        <div class="prose prose-sm text-gray-700 line-clamp-2">
          <?php echo wp_kses_post($product_description); ?>
        </div>
      </div>
    <?php endif; ?>

  <!-- Product Content Grid -->
  <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
    
    <!-- Custom Attributes -->
    <?php 
    $custom_attributes = function_exists('get_sorted_custom_attributes') ? get_sorted_custom_attributes() : [];
    
    // Debug custom attributes
    if (isset($_GET['debug'])) {
        echo '<div style="background: #f0f0f0; padding: 10px; margin: 10px;">';
        echo '<h3>Custom Attributes Debug:</h3>';
        echo '<pre>';
        var_dump($custom_attributes);
        echo '</pre>';
        echo '</div>';
    }

    // Check if we have any custom attributes with actual content
    $has_custom_content = false;
    if (!empty($custom_attributes)) {
        foreach ($custom_attributes as $attribute) {
            // Debug output
            if (isset($_GET['debug'])) {
                echo '<pre>';
                echo 'Attribute data:';
                var_dump($attribute);
                echo '</pre>';
            }
            // Check both possible key names
            $has_label = !empty($attribute['label']) || !empty($attribute['attribute_label']);
            $has_content = !empty($attribute['content']) || !empty($attribute['attribute_content']);
            if ($has_label && $has_content) {
                $has_custom_content = true;
                break;
            }
        }
    }
    
    if ($has_custom_content): 
    ?>
      <!-- Display custom attributes in grid -->
      <?php 
      // Filter out empty attributes and split into two columns for better layout
      $valid_attributes = array_filter($custom_attributes, function($attr) {
          return !empty($attr['attribute_label']) && !empty($attr['attribute_content']);
      });
      
      $mid_point = ceil(count($valid_attributes) / 2);
      $left_attributes = array_slice($valid_attributes, 0, $mid_point);
      $right_attributes = array_slice($valid_attributes, $mid_point);
      ?>
      
      <!-- Left Column -->
      <?php if (!empty($left_attributes)): ?>
        <div class="space-y-4">
          <?php foreach ($left_attributes as $attribute): ?>
            <div>
                <h4 class="text-sm font-medium text-gray-500 mb-1"><?php 
                    // Get label from either field name
                    $label = !empty($attribute['label']) ? $attribute['label'] : $attribute['attribute_label'];
                    echo esc_html($label); 
                ?></h4>
              <p class="text-base text-gray-900">
                  <?php 
                    // Get content from either field name
                    $content = !empty($attribute['content']) ? $attribute['content'] : $attribute['attribute_content'];
                    echo wp_kses_post(nl2br($content)); 
                  ?>
              </p>
            </div>
          <?php endforeach; ?>
        </div>
      <?php endif; ?>
      
      <!-- Right Column -->
      <?php if (!empty($right_attributes)): ?>
        <div class="space-y-4">
          <?php foreach ($right_attributes as $attribute): ?>
            <div>
              <h4 class="text-sm font-medium text-gray-500 mb-1"><?php echo esc_html($attribute['attribute_label']); ?></h4>
              <p class="text-base text-gray-900">
                <?php echo wp_kses_post(nl2br($attribute['attribute_content'])); ?>
              </p>
            </div>
          <?php endforeach; ?>
        </div>
      <?php endif; ?>
      
    <?php else: ?>
      
      <!-- Fallback: Legacy ACF Fields (if no custom attributes with content) -->
      <!-- Left Column - Chemistry & Applications -->
      <div class="space-y-4">
        
        <!-- Chemistry -->
        <?php if ($chemistry_type): ?>
          <div>
            <h4 class="text-sm font-medium text-gray-500 mb-1">Chemistry</h4>
            <p class="text-base text-gray-900">
              <?php echo esc_html($chemistry_type); ?>
            </p>
          </div>
        <?php endif; ?>

        <!-- Applications -->
        <?php if ($applications_list): ?>
          <div>
            <h4 class="text-sm font-medium text-gray-500 mb-1">Applications</h4>
            <p class="text-base text-gray-900">
              <?php echo esc_html($applications_list); ?>
            </p>
          </div>
        <?php endif; ?>
        
      </div>

      
    <?php endif; ?>
    
    <!-- Specifications (Always show if data exists, as separate section) -->
    <?php if ($solvent || $non_volatile || $oil_length): ?>
      <div class="md:col-span-2">
        <h4 class="text-sm font-medium text-gray-500 mb-3">Specifications</h4>
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
          
          <?php if ($solvent): ?>
            <div class="flex justify-between items-center sm:flex-col sm:items-start sm:text-center p-3 bg-gray-50 rounded-lg">
              <span class="text-sm text-gray-600 sm:mb-1">Solvent</span>
              <span class="text-sm font-medium text-gray-900"><?php echo esc_html($solvent); ?></span>
            </div>
          <?php endif; ?>

          <?php if ($non_volatile): ?>
            <div class="flex justify-between items-center sm:flex-col sm:items-start sm:text-center p-3 bg-gray-50 rounded-lg">
              <span class="text-sm text-gray-600 sm:mb-1">Non-Volatile %</span>
              <span class="text-sm font-medium text-gray-900"><?php echo esc_html($non_volatile); ?></span>
            </div>
          <?php endif; ?>

          <?php if ($oil_length): ?>
            <div class="flex justify-between items-center sm:flex-col sm:items-start sm:text-center p-3 bg-gray-50 rounded-lg">
              <span class="text-sm text-gray-600 sm:mb-1">Oil Length %</span>
              <span class="text-sm font-medium text-gray-900"><?php echo esc_html($oil_length); ?></span>
            </div>
          <?php endif; ?>

        </div>
      </div>
    <?php endif; ?>

    <!-- Additional Specifications -->
    <?php if (!empty($additional_specifications)): ?>
      <div class="mt-6">
        <h4 class="text-sm font-medium text-gray-500 mb-3">Additional Specifications</h4>
        <div class="grid grid-cols-2 gap-2">
          <?php foreach ($additional_specifications as $spec): ?>
            <?php 
            // Try different possible sub-field names
            $spec_name = $spec['specification_name'] ?? $spec['name'] ?? $spec['spec_name'] ?? '';
            $spec_value = $spec['value'] ?? $spec['specification_value'] ?? $spec['spec_value'] ?? '';
            ?>
            <?php if (!empty($spec_name) && !empty($spec_value)): ?>
              <div class="p-2 bg-gray-50 rounded text-center">
                <div class="text-xs text-gray-600"><?php echo esc_html($spec_name); ?></div>
                <div class="text-sm font-medium text-gray-900"><?php echo esc_html($spec_value); ?></div>
              </div>
            <?php endif; ?>
          <?php endforeach; ?>
        </div>
      </div>
    <?php endif; ?>

    <!-- Features & Benefits -->
    <?php if (!empty($features_benefits)): ?>
      <div class="mt-6">
        <h4 class="text-sm font-medium text-gray-500 mb-3">Features & Benefits</h4>
        <ul class="space-y-1 max-h-32 overflow-y-auto">
          <?php foreach ($features_benefits as $feature): ?>
            <?php 
            // Use correct sub-field name from ACF structure
            $feature_text = $feature['feature_text'] ?? '';
            ?>
            <?php if (!empty($feature_text)): ?>
              <li class="text-sm text-gray-700 flex items-start">
                <svg class="w-3 h-3 text-green-500 mt-1 mr-2 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                  <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                </svg>
                <?php echo esc_html($feature_text); ?>
              </li>
            <?php endif; ?>
          <?php endforeach; ?>
        </ul>
      </div>
    <?php endif; ?>

    <!-- Typical Uses -->
    <?php if (!empty($typical_uses)): ?>
      <div class="mt-6">
        <h4 class="text-sm font-medium text-gray-500 mb-3">Typical Uses</h4>
        <ul class="space-y-1 max-h-32 overflow-y-auto">
          <?php foreach ($typical_uses as $use): ?>
            <?php 
            // Use correct sub-field name from ACF structure
            $use_text = $use['use_text'] ?? '';
            ?>
            <?php if (!empty($use_text)): ?>
              <li class="text-sm text-gray-700 flex items-start">
                <svg class="w-3 h-3 text-blue-500 mt-1 mr-2 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                  <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010 1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                </svg>
                <?php echo esc_html($use_text); ?>
              </li>
            <?php endif; ?>
          <?php endforeach; ?>
        </ul>
      </div>
    <?php endif; ?>
    
    </div> <!-- End grid content -->
    
  </div> <!-- End product content -->

</article>

<!-- Additional CSS for line-clamp if not available in your Tailwind build -->
<style>
.line-clamp-2 {
  display: -webkit-box;
  -webkit-line-clamp: 2;
  -webkit-box-orient: vertical;
  overflow: hidden;
}

.stretched-link::after {
  position: absolute;
  top: 0;
  right: 0;
  bottom: 0;
  left: 0;
  z-index: 1;
  content: "";
}

/* Ensure buttons are above stretched link */
.product-card a:not(.stretched-link),
.product-card button {
  position: relative;
  z-index: 2;
}
</style>