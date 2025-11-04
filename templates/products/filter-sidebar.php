<?php

/**
 * =============================================================================
 * PRODUCT FILTER SIDEBAR
 * =============================================================================
 * 
 * This template displays the filter sidebar with three main categories:
 * - Chemistry Types (Taxonomy: product_chemistry)
 * - Brand/Product Family (Taxonomy: product_brand)  
 * - Applications (Taxonomy: product_application)
 * 
 * Features:
 * - Collapsible filter sections with Tailwind CSS
 * - Multi-select checkboxes within each category
 * - Filter counts showing products per term
 * - Clear individual filters and "Clear All" functionality
 * - Mobile-responsive design
 * =============================================================================
 */

// Get current filter values
global $selected_chemistry, $selected_brand, $selected_applications;

// Get all taxonomy terms with counts
$chemistry_terms = get_taxonomy_terms_with_counts('product_chemistry', $selected_chemistry);
$brand_terms = get_taxonomy_terms_with_counts('product_brand', $selected_brand);
$application_terms = get_taxonomy_terms_with_counts('product_application', $selected_applications);

// Helper function to check if term is selected
function is_term_selected($term_slug, $selected_array)
{
  return in_array($term_slug, $selected_array);
}

// Count active filters
$active_filters_count = count($selected_chemistry) + count($selected_brand) + count($selected_applications);

?>

<div class="product-filters popup-inner" id="product-filters">

  <!-- Filter Header -->
  <div class="flex flex-col ">
    <!-- Close button for mobile modal -->
    <button type="button"
      class="mobile-filter-close lg:hidden p-1 hover:bg-gray-100 rounded text-right inline-flex self-end mb-3"
      aria-label="Close filters">
      <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 12 12" fill="none">
        <path d="M10.473 10.4902L0.666504 0.666504" stroke="#333333" stroke-width="1.33295" stroke-linecap="round" />
        <path d="M0.66665 10.4902L10.4731 0.666504" stroke="#333333" stroke-width="1.33295" stroke-linecap="round" />
      </svg>
    </button>
    <div class="flex items-center justify-between lg:mb-6 lg:pb-6 mb-3 pb-3 border-b border-grey-4">
      <div class="body-1 font-semibold text-grey-1">Filters</div>
      <div class="flex items-center gap-1">

        <button type="button"
          class="clear-all-filters hidden text-sm text-primary  font-bold "
          data-filters="all">
          <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16" fill="none">
            <path d="M12 4L4 12" stroke="currentColor" stroke-width="1.58333" stroke-linecap="round" stroke-linejoin="round" />
            <path d="M4 4L12 12" stroke="currentColor" stroke-width="1.58333" stroke-linecap="round" stroke-linejoin="round" />
          </svg>
          <span>Clear All</span>

        </button>

      </div>
    </div>

  </div>


  <!-- Filter Sections Container -->
  <div class="filter-sections space-y-3 lg:space-y-6  lg:block" id="filter-sections">

    <!-- Chemistry Filter -->
    <div class="filter-section expanded border-b border-grey-4 pb-4 lg:pb-6" data-filter="chemistry">
      <div class="flex items-center justify-between">
        <h4 class="font-semibold text-black flex items-center lg:text-base text-sm">
          Chemistry
          <?php if (!empty($selected_chemistry)): ?>
            <span class="ml-2 inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800 hidden">
              <?php echo count($selected_chemistry); ?>
            </span>
          <?php endif; ?>
        </h4>
        <div type="button"
          class="filter-toggle p-1 bg-primary rounded-full w-5 h-5 flex items-center justify-center">
          <svg class="w-4 h-4 text-white minus-icon" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M3 10a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z" clip-rule="evenodd" />
          </svg>
          <svg class="w-4 h-4 text-white plus-icon" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" />
          </svg>
        </div>
      </div>

      <div class="filter-options space-y-3 mt-3" id="chemistry-options">
        <?php if (!empty($chemistry_terms)): ?>
          <?php foreach ($chemistry_terms as $term): ?>
            <label class="flex items-center group cursor-pointer">
              <input type="checkbox"
                name="chemistry[]"
                value="<?php echo esc_attr($term->slug); ?>"
                <?php checked(is_term_selected($term->slug, $selected_chemistry)); ?>
                class="custom-checkbox">
              <span class="ml-2 text-sm text-gray-700 group-hover:text-gray-900 flex-1">
                <?php echo esc_html($term->name); ?>
              </span>
              <span class="text-xs text-gray-500 ml-2 hidden">
                (<?php echo $term->count; ?>)
              </span>
            </label>
          <?php endforeach; ?>
        <?php else: ?>
          <p class="text-sm text-gray-500">No chemistry types found.</p>
        <?php endif; ?>
      </div>
    </div>

    <!-- Brand/Product Family Filter -->
    <div class="filter-section expanded border-b border-grey-4 pb-4 lg:pb-6" data-filter="brand">
      <div class="flex items-center justify-between">
        <h4 class="font-semibold text-black flex items-center lg:text-base text-sm">
          Brand / Product Family
          <?php if (!empty($selected_brand)): ?>
            <span class="ml-2 inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800 hidden">
              <?php echo count($selected_brand); ?>
            </span>
          <?php endif; ?>
        </h4>
        <div type="button"
          class="filter-toggle p-1 bg-primary rounded-full w-5 h-5 flex items-center justify-center">
          <svg class="w-4 h-4 text-white minus-icon" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M3 10a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z" clip-rule="evenodd" />
          </svg>
          <svg class="w-4 h-4 text-white plus-icon" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" />
          </svg>
        </div>
      </div>

      <div class="filter-options space-y-3 mt-3" id="brand-options">
        <?php if (!empty($brand_terms)): ?>
          <?php foreach ($brand_terms as $term): ?>
            <label class="flex items-center group cursor-pointer">
              <input type="checkbox"
                name="brand[]"
                value="<?php echo esc_attr($term->slug); ?>"
                <?php checked(is_term_selected($term->slug, $selected_brand)); ?>
                class="custom-checkbox">
              <span class="ml-2 text-grey-2 lg:text-base text-sm flex-1">
                <?php echo esc_html($term->name); ?>
              </span>
              <span class="text-xs text-gray-500 ml-2 hidden">
                (<?php echo $term->count; ?>)
              </span>
            </label>
          <?php endforeach; ?>
        <?php else: ?>
          <p class="text-sm text-gray-500">No brands found.</p>
        <?php endif; ?>
      </div>
    </div>

    <!-- Applications Filter -->
    <?php if (!empty($application_terms)): ?>
    <div class="filter-section expanded" data-filter="applications">
      <div class="flex items-center justify-between">
        <h4 class="font-semibold text-black flex items-center lg:text-base text-sm">
          Applications
          <?php if (!empty($selected_applications)): ?>
            <span class="ml-2 inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800 hidden">
              <?php echo count($selected_applications); ?>
            </span>
          <?php endif; ?>
        </h4>
        <div type="button"
          class="filter-toggle p-1 bg-primary rounded-full w-5 h-5 flex items-center justify-center">
          <svg class="w-4 h-4 text-white minus-icon" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M3 10a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z" clip-rule="evenodd" />
          </svg>
          <svg class="w-4 h-4 text-white plus-icon" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" />
          </svg>
        </div>
      </div>

      <div class="filter-options space-y-3 mt-3" id="application-options">
        <?php if (!empty($application_terms)): ?>
          <?php foreach ($application_terms as $term): ?>
            <label class="flex items-center group cursor-pointer">
              <input type="checkbox"
                name="applications[]"
                value="<?php echo esc_attr($term->slug); ?>"
                <?php checked(is_term_selected($term->slug, $selected_applications)); ?>
                class="custom-checkbox">
              <span class="ml-2 text-grey-2 lg:text-base text-sm flex-1">
                <?php echo esc_html($term->name); ?>
              </span>
              <span class="text-xs text-gray-500 ml-2 hidden">
                (<?php echo $term->count; ?>)
              </span>
            </label>
          <?php endforeach; ?>
        <?php else: ?>
          <p class="text-sm text-gray-500">No applications found.</p>
        <?php endif; ?>
      </div>
    </div>
    <?php endif; ?>

  </div>

</div>