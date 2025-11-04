<?php

/**
 * Certification Block Template
 *
 * ACF Fields:
 * - hide_block (true_false)
 * - title (text)
 * - description (wysiwyg)
 * - certification_items (repeater)
 *   - title (text)
 *   - content (wysiwyg)
 */

$title = get_sub_field('title') ?: '';
$description = get_sub_field('description') ?: '';
$items = get_sub_field('certification_items') ?: [];

// Include hide block functionality
include locate_template('templates/blocks/hide_block.php', false, false);

if (!$hide_block && ($title || $description || $items)): ?>
  <section class="certification_block fade-in relative" data-component="CertificationSlider" data-load="eager">
    <!-- Decorative Shape (Mobile) -->
    <div class="lg:block hidden absolute left-[-34px] top-[-130px] -z-1 pointer-none " data-speed="1.25">
      <img src="<?= get_stylesheet_directory_uri(); ?>/assets/images/research/shape-lg:block hidden absolute left-[-34px] top-[-130px] -z-1 pointer-none 4.png" alt="resin">
    </div>
    <div class="container-fluid relative overflow-hidden">

      <div class="section-heading text-center">
        <?php if ($title): ?>
          <h2 class="fade-text"><?php echo esc_html($title); ?></h2>
        <?php endif; ?>

        <?php if ($description): ?>
          <div class="description-content prose max-w-none anim-uni-in-up">
            <?php echo wp_kses_post($description); ?>
          </div>
        <?php endif; ?>
      </div>

      <?php if (!empty($items)): ?>
        <?php $count = count($items); ?>
        <div class="certification-slider-container" data-slider-enabled="true" data-cert-count="<?= esc_attr($count); ?>">
          <div class="certification-slider swiper">
            <div class="swiper-wrapper ">
              <?php foreach ($items as $item): ?>
                <div class="swiper-slide">
                  <div class="certification-card text-center flex flex-col items-center gap-4 animate-card-3">
                    <div class="certification_img relative flex justify-center items-center ">
                      <img class="size-full" src="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/research/laurel_wreath.svg" alt="Decorative shape" loading="lazy" decoding="async">
                      <?php if (!empty($item['title'])): ?>
                        <div class="absolute    text-grey-1 h3 font-semibold max-w-[155px] mx-auto tracking-[-0.48px]"><?php echo esc_html($item['title']); ?></div>
                      <?php endif; ?>
                    </div>
                    <?php if (!empty($item['content'])): ?>
                      <div class="content text-sm max-w-[280px] mx-auto text-grey-2"><?php echo wp_kses_post($item['content']); ?></div>
                    <?php endif; ?>
                  </div>
                </div>
              <?php endforeach; ?>
            </div>
          </div>

          <!-- Slider controls: always visible on mobile; on desktop show only if more than 3 items -->
          <div class=" certification-slider-controls mt-3 flex justify-center items-center gap-4 <?php echo ($count > 3) ? 'lg:flex' : 'lg:hidden'; ?>">
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
      <?php endif; ?>

    </div>
  </section>
<?php endif; ?>