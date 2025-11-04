<?php

/**
 * Gallery Block Template
 * Enhanced with tabbed category functionality
 *
 * ACF Fields:
 * - hide_block (true_false)
 * - title (text)
 * - description (wysiwyg)
 * - show_tabs (true_false)
 * - gallery_categories (repeater)
 *   - category_name (text)
 *   - category_slug (text)
 *   - gallery_items (repeater)
 *     - gallery_image (image)
 *     - title (text)
 *     - year (text)
 *
 * Legacy support:
 * - gallery_items (repeater) - for backward compatibility
 */

$title = get_sub_field('title') ?: '';
$description = get_sub_field('description') ?: '';
$show_tabs = get_sub_field('show_tabs') ?: false;
$gallery_categories = get_sub_field('gallery_categories') ?: [];
$legacy_gallery_items = get_sub_field('gallery_items') ?: []; // Legacy support

// Include hide block functionality
include locate_template('templates/blocks/hide_block.php', false, false);

if (!$hide_block && ($title || $description || $gallery_categories || $legacy_gallery_items)): ?>
  <section class="gallery_block fade-in" data-component="<?php echo $show_tabs && !empty($gallery_categories) ? 'SimpleTeamTabs' : 'GalleryBlock'; ?>" data-load="eager">
    <div class="container-fluid">

      <!-- Header Section -->
      <div class="section-heading text-center mb-6 md:mb-8">
        <?php if ($title): ?>
          <h2 class="fade-text"><?php echo esc_html($title); ?></h2>
        <?php endif; ?>

        <?php if ($description): ?>
          <div class="description-content prose max-w-none">
            <?php echo wp_kses_post($description); ?>
          </div>
        <?php endif; ?>
      </div>

      <?php if ($show_tabs && !empty($gallery_categories)): ?>
        <!-- TABBED GALLERY MODE -->

        <!-- Category Tab Buttons - Sliding Toggle Style -->
        <?php if (count($gallery_categories) > 1): ?>
          <div class="gallery-tabs flex justify-center mb-6 lg:mb-8">
            <div class="relative inline-flex border-primary border rounded-full w-full max-w-[200px] mx-auto">
              <!-- Active state background that slides -->
              <div class="gallery-tab-slider absolute top-1 bottom-1 bg-primary rounded-full transition-all duration-300 ease-out"
                style="width: calc(<?php echo 100 / count($gallery_categories); ?>% - 10px); left: 4px;"></div>

              <?php foreach ($gallery_categories as $index => $category): ?>
                <?php
                $category_name = $category['category_name'] ?: 'Category ' . ($index + 1);
                $category_slug = $category['category_slug'] ?: sanitize_title($category_name);
                $category_items = $category['gallery_items'] ?: [];
                $items_count = count($category_items);
                ?>
                <div
                  class="gallery-slide-tab relative z-10 text-sm md:text-base font-normal md:px-4 py-3 px-2 rounded-full transition-all duration-300 cursor-pointer flex-1 text-center <?php echo $index === 0 ? 'text-white active' : 'text-primary'; ?>"
                  data-filter="<?php echo esc_attr($category_slug); ?>"
                  data-index="<?php echo $index; ?>">
                  <?php echo esc_html($category_name); ?>
                </div>
              <?php endforeach; ?>
            </div>
          </div>
        <?php endif; ?>

        <!-- Gallery Categories Content -->
        <div class="gallery-categories-wrapper">
          <?php foreach ($gallery_categories as $index => $category): ?>
            <?php
            $category_name = $category['category_name'] ?: 'Category ' . ($index + 1);
            $category_slug = $category['category_slug'] ?: sanitize_title($category_name);
            $category_items = $category['gallery_items'] ?: [];
            ?>

            <div class="gallery-category-wrapper <?php echo $index === 0 ? 'active' : ''; ?>"
              data-category="<?php echo esc_attr($category_slug); ?>"
              style="display: <?php echo $index === 0 ? 'block' : 'none'; ?>;">

              <?php if (!empty($category_items)): ?>
                <div class="gallery-swiper-container relative">
                  <!-- Swiper -->
                  <div class="swiper gallery_items-grid" data-category="<?php echo esc_attr($category_slug); ?>">
                    <div class="swiper-wrapper">
                      <?php foreach ($category_items as $item): ?>
                        <div class="swiper-slide">
                          <?php include locate_template('templates/components/gallery-item.php'); ?>
                        </div>
                      <?php endforeach; ?>
                    </div>

                    <!-- Navigation -->
                    <div class="mt-3 flex justify-center items-center gap-3 mb-6">
                      <div class="swiper-btn-prev-pagination swiper-btn-prev">
                        <svg xmlns="http://www.w3.org/2000/svg" width="9" height="7" viewBox="0 0 9 7" fill="none">
                          <path d="M7.92214 3.18291C8.16739 3.18291 8.36621 3.38173 8.36621 3.62699C8.36621 3.87224 8.16739 4.07106 7.92214 4.07106L1.66704 4.07106L3.79543 6.19944C3.96885 6.37286 3.96885 6.65403 3.79543 6.82745C3.62201 7.00087 3.34084 7.00087 3.16742 6.82745L0.594961 4.255C0.24812 3.90816 0.24812 3.34581 0.594961 2.99897L3.16742 0.426516C3.34084 0.253095 3.62201 0.253096 3.79543 0.426516C3.96885 0.599937 3.96885 0.881107 3.79543 1.05453L1.66705 3.18291L7.92214 3.18291Z" fill="#DA000E" />
                        </svg>
                      </div>

                      <div class="swiper-pagination-custom text-primary text-xs font-medium !w-4 !h-4"></div>

                      <div class="swiper-btn-next-pagination swiper-btn-next">
                        <svg xmlns="http://www.w3.org/2000/svg" width="9" height="7" viewBox="0 0 9 7" fill="none">
                          <path d="M1.15891 3.18291C0.913661 3.18291 0.714844 3.38173 0.714844 3.62699C0.714844 3.87224 0.913661 4.07106 1.15892 4.07106L7.41401 4.07106L5.28562 6.19944C5.1122 6.37286 5.1122 6.65403 5.28562 6.82745C5.45904 7.00087 5.74021 7.00087 5.91364 6.82745L8.48609 4.255C8.83293 3.90816 8.83294 3.34581 8.48609 2.99897L5.91363 0.426516C5.74021 0.253095 5.45904 0.253096 5.28562 0.426516C5.1122 0.599937 5.1122 0.881107 5.28562 1.05453L7.41401 3.18291L1.15891 3.18291Z" fill="#DA000E" />
                        </svg>
                      </div>
                    </div>
                  </div>
                </div>
              <?php else: ?>
                <div class="text-center py-12">
                  <p class="text-gray-500 text-lg">No gallery items found in this category.</p>
                </div>
              <?php endif; ?>
            </div>
          <?php endforeach; ?>
        </div>

      <?php elseif (!empty($legacy_gallery_items)): ?>
        <!-- LEGACY SINGLE GALLERY MODE -->
        <div class="gallery-swiper-container relative">
          <!-- Swiper -->
          <div class="swiper gallery_items-grid">
            <div class="swiper-wrapper">
              <?php foreach ($legacy_gallery_items as $item): ?>
                <div class="swiper-slide">
                  <?php include locate_template('templates/components/gallery-item.php'); ?>
                </div>
              <?php endforeach; ?>
            </div>

            <!-- Navigation - Latest News Style -->
            <div class="mt-3 flex justify-center items-center gap-3">
              <div class="swiper-btn-prev-pagination swiper-btn-prev">
                <svg xmlns="http://www.w3.org/2000/svg" width="9" height="7" viewBox="0 0 9 7" fill="none">
                  <path d="M7.92214 3.18291C8.16739 3.18291 8.36621 3.38173 8.36621 3.62699C8.36621 3.87224 8.16739 4.07106 7.92214 4.07106L1.66704 4.07106L3.79543 6.19944C3.96885 6.37286 3.96885 6.65403 3.79543 6.82745C3.62201 7.00087 3.34084 7.00087 3.16742 6.82745L0.594961 4.255C0.24812 3.90816 0.24812 3.34581 0.594961 2.99897L3.16742 0.426516C3.34084 0.253095 3.62201 0.253096 3.79543 0.426516C3.96885 0.599937 3.96885 0.881107 3.79543 1.05453L1.66705 3.18291L7.92214 3.18291Z" fill="#DA000E" />
                </svg>
              </div>

              <div class="swiper-pagination-custom text-primary text-xs font-medium !w-4 !h-4"></div>

              <div class="swiper-btn-next-pagination swiper-btn-next">
                <svg xmlns="http://www.w3.org/2000/svg" width="9" height="7" viewBox="0 0 9 7" fill="none">
                  <path d="M1.15891 3.18291C0.913661 3.18291 0.714844 3.38173 0.714844 3.62699C0.714844 3.87224 0.913661 4.07106 1.15892 4.07106L7.41401 4.07106L5.28562 6.19944C5.1122 6.37286 5.1122 6.65403 5.28562 6.82745C5.45904 7.00087 5.74021 7.00087 5.91364 6.82745L8.48609 4.255C8.83293 3.90816 8.83294 3.34581 8.48609 2.99897L5.91363 0.426516C5.74021 0.253095 5.45904 0.253096 5.28562 0.426516C5.1122 0.599937 5.1122 0.881107 5.28562 1.05453L7.41401 3.18291L1.15891 3.18291Z" fill="#DA000E" />
                </svg>
              </div>
            </div>
          </div>
        </div>
      <?php endif; ?>

    </div>
  </section>
<?php endif; ?>