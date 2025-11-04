<?php

/**
 * =============================================================================
 * HOME PRODUCT LISTING BLOCK
 * =============================================================================
 * 
 * This block displays a product listing section with animated marquee effect.
 * Features:
 * - Supports unlimited product items (no restrictions)
 * - Smart distribution across two animated rows
 * - Dynamic content from ACF fields
 * - Smooth animations with gradient masks
 * - CTA button support
 * 
 * ACF Fields Structure:
 * - hide_block (true/false) - Toggle block visibility
 * - title (text) - Section heading
 * - description (wysiwyg) - Section description
 * - cta (link) - Call-to-action button
 * - product_items (repeater) - Product items to display
 *    - icon (image) - Product icon/image
 *    - title (text) - Product name/title
 * 
 * Dependencies:
 * - templates/blocks/hide_block.php
 * - templates/partials/product-card.php
 * - SwiperMarquee JavaScript component
 * =============================================================================
 */

/* START ACF FIELD INITIALIZATION */
include locate_template('templates/blocks/hide_block.php', false, false);

$title         = get_sub_field('title');
$description   = get_sub_field('description');
$cta           = get_sub_field('cta');
$product_items = get_sub_field('product_items');
/* END ACF FIELD INITIALIZATION */

/* START MAIN BLOCK RENDERING */
if (!$hide_block && ($title || $description || !empty($product_items))): ?>
  <section class="home-product-listing-block gsap-ignore" data-smooth="false">
    <!-- ANIMATIONS COMMENTED OUT: fade-in class and SwiperMarquee component -->
    <div class="container-fluid items-center gap-24 relative">

      <!-- Section Heading -->
      <?php if ($title || $description): ?>
        <div class="section-heading text-center max-w-[852px] mx-auto">
          <?php if ($title): ?>
            <h2 class="mb-1"><?= esc_html($title); ?></h2>
            <!-- ANIMATION COMMENTED OUT: fade-text class -->
          <?php endif; ?>
          <?php if ($description): ?>
            <div class="anim-uni-in-up">
              <?= wp_kses_post($description); ?>
            </div>
          <?php endif; ?>
        </div>
      <?php endif; ?>

      <!-- Product Items Marquee -->
      <div class="max-w-7xl mx-auto">
        <?php if (!empty($product_items) && is_array($product_items)):
          // Display first 8 items in a responsive grid
          $display_items = array_slice($product_items, 0, 8);
          $delay = 0; // Initialize delay counter
        ?>

          <div class="grid grid-cols-2 md:grid-cols-4 gap-4 lg:gap-6 w-full my-6 md:my-12 justify-items-center fade-up-stagger-wrap">
            <?php
            foreach ($display_items as $item):
              $icon       = $item['icon'] ?? null;
              $item_title = $item['title'] ?? '';
              $delay += 0.2; // increase delay by 0.2 each time

            ?>
              <?php include locate_template('templates/partials/product-card.php', false, false); ?>
            <?php endforeach; ?>
          </div>

        <?php else: ?>
          <!-- No products found -->
          <div class="text-center text-gray-500">No product items found.</div>
        <?php endif; ?>
      </div>

      <!-- CTA Button -->
      <?php if ($cta && $cta['url']): ?>
        <div class="text-center anim-uni-in-up">
          <a href="<?= esc_url($cta['url']); ?>"
            class="btn"
            <?php if (!empty($cta['target'])): ?> target="<?= esc_attr($cta['target']); ?>" <?php endif; ?>>
            <?= esc_html($cta['title'] ?: 'Learn More'); ?>
          </a>
        </div>
      <?php endif; ?>

      <!-- Decorative Shape -->
      <div class="absolute left-0 lg:-left-10 md:-bottom-10 bottom-0 -z-1 pointer-none w-[73px] lg:w-auto" data-speed="1.25">
        <!-- ANIMATION COMMENTED OUT: data-speed="1.25" -->
        <img src="<?= get_stylesheet_directory_uri(); ?>/assets/images/home/shapes/shape-5.webp" alt="resins">
      </div>

    </div> <!-- end container-fluid -->
  </section>
<?php endif; ?>