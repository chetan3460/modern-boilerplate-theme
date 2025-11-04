<?php

/**
 * =============================================================================
 * PRODUCT CARD - LIST VIEW
 * =============================================================================
 * 
 * This template displays individual product cards in horizontal list layout.
 * Optimized for Tailwind CSS with table-like information display.
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
$product_permalink = get_permalink();
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
  // Get Features & Benefits (repeater field) - try multiple field names
  $features_benefits = get_field('features_benefits') ?:
    get_field('features_and_benefits') ?:
    get_field('feature_benefit') ?:
    get_field('features') ?:
    get_field('benefits') ?: [];

  // Get Typical Uses (repeater field) - try multiple field names
  $typical_uses = get_field('typical_uses') ?:
    get_field('typical_use') ?:
    get_field('uses') ?:
    get_field('applications') ?: [];

  // Get Additional Specifications (repeater field)
  $additional_specifications = get_field('additional_specifications') ?:
    get_field('additional_specs') ?:
    get_field('specifications') ?: [];

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

<article class="product-card bg-white rounded-lg  transition-all duration-200 hover:shadow-md overflow-hidden" data-id="<?php echo esc_attr($product_id); ?>" data-index="<?php global $wp_query;
                                                                                                                                                                          echo esc_attr($wp_query->current_post + 1); ?>">

  <!-- Product Layout: Image + Content -->
  <div class="flex flex-col <?php echo $show_product_image ? 'lg:flex-row' : ''; ?>">

    <!-- Product Image (conditional) -->
    <?php if ($show_product_image): ?>
      <div class="lg:w-1/3 bg-gray-50">
        <div class="aspect-square lg:aspect-[4/3] relative">
          <img src="<?php echo esc_url($product_image_url); ?>"
            alt="<?php echo esc_attr($product_image_alt); ?>"
            class="absolute inset-0 w-full h-full object-cover"
            loading="lazy"
            decoding="async" />
        </div>
      </div>
    <?php endif; ?>

    <!-- Product Content -->
    <div class="<?php echo $show_product_image ? 'lg:w-2/3' : 'w-full'; ?>">

      <!-- Product Header -->
      <div class=" flex sm:flex-row flex-col sm:items-center justify-between lg:mb-6  max-md:p-4 py-4 px-8 border-b border-[#E4E4E4] gap-2">
        <!-- Product Title -->
        <div class="text-base lg:text-[20px] lg:leading-[24px] lg:tracking-[-0.3px] text-black font-bold">
          <?php echo esc_html($product_title); ?>
        </div>

        <!-- Technical Data Sheet -->
        <?php if ($technical_datasheet): ?>
          <a href="<?php echo esc_url($technical_datasheet['url']); ?>"
            target="_blank"
            rel="noopener"
            class="inline-flex items-center gap-1 text-primary text-sm font-semibold tracking-[-0.3px] transition-colors duration-200">
            <img src="<?php echo get_template_directory_uri(); ?>/assets/images/icons/download.svg" alt="">
            <span>Technical Data Sheet</span>
          </a>
        <?php endif; ?>
      </div>

      <!-- Product Description -->
      <?php if ($product_description): ?>
        <div class="px-8  mb-6">
          <div class="prose prose-sm text-gray-700 line-clamp-3">
            <?php echo wp_kses_post($product_description); ?>
          </div>
        </div>
      <?php endif; ?>

      <!-- Product Content Grid -->
      <div class="grid grid-cols-1 lg:grid-cols-2 gap-3 lg:gap-8 max-md:p-4 px-8 pb-8">

        <!-- Custom Attributes -->
        <?php
        $custom_attributes = function_exists('get_sorted_custom_attributes') ? get_sorted_custom_attributes() : [];

        // Check if we have any custom attributes with actual content
        $has_custom_content = false;
        if (!empty($custom_attributes)) {
          foreach ($custom_attributes as $attribute) {
            if (!empty($attribute['attribute_label']) && !empty($attribute['attribute_content'])) {
              $has_custom_content = true;
              break;
            }
          }
        }

        if ($has_custom_content):
        ?>
          <!-- Display custom attributes -->
          <?php foreach ($custom_attributes as $index => $attribute): ?>
            <?php if (!empty($attribute['attribute_label']) && !empty($attribute['attribute_content'])): ?>
              <div class="">
                <div class="product-subtitle text-sm text-grey-7/60 mb-1"><?php echo esc_html($attribute['attribute_label']); ?></div>
                <p class="product-description body-2 text-black ">
                  <?php echo wp_kses_post(nl2br($attribute['attribute_content'])); ?>
                </p>
              </div>
            <?php endif; ?>
          <?php endforeach; ?>
        <?php else: ?>

          <!-- Fallback: Legacy ACF Fields (if no custom attributes with content) -->
          <!-- Chemistry -->
          <div class="space-y-4 max-md:border-b lg:border-r  max-md:pb-3 border-[#E4E4E4]">
            <?php if ($chemistry_type): ?>
              <div>
                <div class="product-subtitle text-sm text-grey-7/60 mb-1">Chemistry</div>
                <p class="product-description body-2 text-black ">
                  <?php echo esc_html($chemistry_type); ?>
                </p>
              </div>
            <?php endif; ?>

            <!-- Applications -->
            <?php if ($applications_list): ?>
              <div>
                <div class="product-subtitle text-sm text-grey-7/60 mb-1">Applications</div>
                <p class="product-description body-2 text-black ">
                  <?php echo esc_html($applications_list); ?>
                </p>
              </div>
            <?php endif; ?>
          </div>
        <?php endif; ?>

        <!-- Specifications (Always show if data exists) -->
        <?php if ($solvent || $non_volatile || $oil_length): ?>
          <div>
            <!-- Desktop version (visible on lg screens and up) -->
            <div class="hidden lg:block">
              <div class="product-subtitle text-sm text-grey-7/60 mb-3">Specifications</div>
              <div class="space-y-3">
                <?php if ($solvent): ?>
                  <div class="flex justify-between items-center">
                    <span class="body-2 text-black font-normal">Solvent</span>
                    <span class="body-2 font-medium text-black"><?php echo esc_html($solvent); ?></span>
                  </div>
                <?php endif; ?>

                <?php if ($non_volatile): ?>
                  <div class="flex justify-between items-center">
                    <span class="body-2 text-black font-normal">Non-Volatile %</span>
                    <span class="body-2 font-medium text-black"><?php echo esc_html($non_volatile); ?></span>
                  </div>
                <?php endif; ?>

                <?php if ($oil_length): ?>
                  <div class="flex justify-between items-center">
                    <span class="body-2 text-black font-normal">Oil Length %</span>
                    <span class="body-2 font-medium text-black"><?php echo esc_html($oil_length); ?></span>
                  </div>
                <?php endif; ?>
              </div>
            </div>

            <!-- Mobile accordion (visible on smaller screens) -->
            <div class="lg:hidden" data-component="ProductAccordions">
              <button type="button"
                class="flex w-full justify-between items-center product-subtitle text-sm text-grey-7/60 "
                aria-expanded="false"
                aria-controls="specs-panel-<?php echo esc_attr($product_id); ?>"
                data-accordion-toggle>
                <span>Specifications</span>
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="9" viewBox="0 0 16 9" fill="none" class="transition-transform duration-200">
                  <path d="M14.75 0.75L8.12692 7.925C8.07858 7.9808 8.02019 8.02528 7.95536 8.05568C7.89053 8.08608 7.82064 8.10177 7.75 8.10177C7.67936 8.10177 7.60946 8.08608 7.54464 8.05568C7.47981 8.02528 7.42142 7.9808 7.37308 7.925L0.749999 0.749999" stroke="#333333" stroke-width="1.5" stroke-linecap="round" />
                </svg>
              </button>

              <div id="specs-panel-<?php echo esc_attr($product_id); ?>" class=" space-y-2 overflow-hidden" aria-hidden="true" data-accordion-panel style="max-height: 0; opacity: 0; margin-bottom: 0; transition: max-height 0.3s ease, opacity 0.3s ease, margin-bottom 0.3s ease; visibility: hidden;">
                <?php if ($solvent): ?>
                  <div class="flex justify-between items-center mt-2">
                    <span class="body-2 text-black font-normal">Solvent</span>
                    <span class="body-2 font-medium text-black"><?php echo esc_html($solvent); ?></span>
                  </div>
                <?php endif; ?>

                <?php if ($non_volatile): ?>
                  <div class="flex justify-between items-center">
                    <span class="body-2 text-black font-normal">Non-Volatile %</span>
                    <span class="body-2 font-medium text-black"><?php echo esc_html($non_volatile); ?></span>
                  </div>
                <?php endif; ?>

                <?php if ($oil_length): ?>
                  <div class="flex justify-between items-center">
                    <span class="body-2 text-black font-normal">Oil Length %</span>
                    <span class="body-2 font-medium text-black"><?php echo esc_html($oil_length); ?></span>
                  </div>
                <?php endif; ?>
              </div>
            </div>
          </div>
        <?php endif; ?>

        <!-- Additional Specifications -->
        <?php if (!empty($additional_specifications)): ?>
          <div>
            <div class="product-subtitle text-sm text-grey-7/60 mb-1 mb-3">Additional Specifications</div>
            <div class="space-y-2">
              <?php foreach ($additional_specifications as $spec): ?>
                <?php
                // Try different possible sub-field names
                $spec_name = $spec['specification_name'] ?? $spec['name'] ?? $spec['spec_name'] ?? '';
                $spec_value = $spec['value'] ?? $spec['specification_value'] ?? $spec['spec_value'] ?? '';
                ?>
                <?php if (!empty($spec_name) && !empty($spec_value)): ?>
                  <div class="flex justify-between items-center">
                    <span class="body-2 text-black font-normal"><?php echo esc_html($spec_name); ?></span>
                    <span class="body-2 font-medium text-black"><?php echo esc_html($spec_value); ?></span>
                  </div>
                <?php endif; ?>
              <?php endforeach; ?>
            </div>
          </div>
        <?php endif; ?>

        <!-- Features & Benefits -->
        <?php
        // Debug output (temporary)
        if (isset($_GET['debug_fields']) && current_user_can('administrator')) {
          echo '<div style="background: yellow; padding: 10px; margin: 10px 0;">';
          echo '<strong>Features & Benefits Debug:</strong><br>';
          echo 'Count: ' . count($features_benefits) . '<br>';
          if (!empty($features_benefits)) {
            echo '<pre>' . print_r($features_benefits, true) . '</pre>';
          }
          echo '</div>';
        }
        ?>
        <?php if (!empty($features_benefits)): ?>
          <div>
            <h4 class="text-sm font-medium text-gray-500 mb-3">Features & Benefits</h4>
            <ul class="space-y-1">
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
        <?php
        // Debug output (temporary)
        if (isset($_GET['debug_fields']) && current_user_can('administrator')) {
          echo '<div style="background: lightblue; padding: 10px; margin: 10px 0;">';
          echo '<strong>Typical Uses Debug:</strong><br>';
          echo 'Count: ' . count($typical_uses) . '<br>';
          if (!empty($typical_uses)) {
            echo '<pre>' . print_r($typical_uses, true) . '</pre>';
          }
          echo '</div>';
        }
        ?>
        <?php if (!empty($typical_uses)): ?>
          <div>
            <div class="product-subtitle text-sm text-grey-7/60 mb-1 mb-3">Typical Uses</div>
            <ul class="space-y-1">
              <?php foreach ($typical_uses as $use): ?>
                <?php
                // Use correct sub-field name from ACF structure
                $use_text = $use['use_text'] ?? '';
                ?>
                <?php if (!empty($use_text)): ?>
                  <li class="text-sm text-gray-700 flex items-start">
                    <svg class="w-3 h-3 text-blue-500 mt-1 mr-2 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                      <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                    </svg>
                    <?php echo esc_html($use_text); ?>
                  </li>
                <?php endif; ?>
              <?php endforeach; ?>
            </ul>
          </div>
        <?php endif; ?>

      </div> <!-- End content grid -->

    </div> <!-- End product content -->

  </div> <!-- End product layout -->

</article>