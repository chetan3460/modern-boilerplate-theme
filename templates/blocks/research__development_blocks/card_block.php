<?php

/**
 * Card Block Template
 *
 * ACF Fields:
 * - hide_block (true_false)
 * - title (text)
 * - description (wysiwyg)
 * - card_items (repeater)
 *   - card_image (image)
 *   - card_title (text)
 *   - card_content (wysiwyg)
 * - cta (link)
 */

$title = get_sub_field('title') ?: '';
$description = get_sub_field('description') ?: '';
$card_items = get_sub_field('card_items') ?: [];
$cta = get_sub_field('cta') ?: '';

// Include hide block functionality
include locate_template('templates/blocks/hide_block.php', false, false);

if (!$hide_block && ($title || $description || $card_items)): ?>
  <section class="card_block fade-in relative" data-component="CardSlider" data-load="lazy">
    <div class="container-fluid  overflow-hidden">

      <!-- Heading -->
      <div class="section-heading text-center !max-w-max">
        <?php if ($title): ?>
          <h2 class="fade-text"><?php echo esc_html($title); ?></h2>
        <?php endif; ?>

        <?php if ($description): ?>
          <div class="description-content prose !max-w-none anim-uni-in-up">
            <?php echo wp_kses_post($description); ?>
          </div>
        <?php endif; ?>
      </div>

      <?php if (!empty($card_items)): ?>
        <?php $card_count = is_array($card_items) ? count($card_items) : 0; ?>

        <!-- Unified Swiper container (mobile + desktop) -->
        <div class="card-slider-container" data-card-count="<?= esc_attr($card_count); ?>">
          <div class="card-slider swiper max-md:!overflow-visible">
            <div class="swiper-wrapper items-stretch">
              <?php foreach ($card_items as $item): ?>
                <div class="swiper-slide">
                  <div class="innovations-card relative rounded-[20px] lg:rounded-[40px] overflow-hidden h-full grid grid-rows-[auto_1fr] animate-card-3">
                    <?php if (!empty($item['card_image'])): ?>
                      <img class="w-full h-[200px] lg:h-[240px] object-cover rounded-t-[20px] lg:rounded-t-[40px]" src="<?php echo esc_url($item['card_image']['url']); ?>" alt="<?php echo esc_attr($item['card_image']['alt'] ?? ($item['card_title'] ?? '')); ?>" loading="lazy">
                    <?php endif; ?>
                    <div class="bg-sky-50 p-5 lg:p-6 rounded-b-[20px] lg:rounded-b-[40px] h-full flex flex-col items-stretch min-h-[145px] sm:min-h-[150px] lg:min-h-[190px]">
                      <?php if (!empty($item['card_title'])): ?>
                        <div class="h3 font-semibold text-grey-1 mb-2 md:mb-4"><?php echo esc_html($item['card_title']); ?></div>
                      <?php endif; ?>
                      <div class="text-sm capitalize text-grey-7/60 mb-1">Applications</div>
                      <?php if (!empty($item['card_content'])): ?>
                        <div class="text-xs md:text-base md:leading-[21px] max-w-[302px]"><?php echo wp_kses_post($item['card_content']); ?></div>
                      <?php endif; ?>
                      <div class="curve-shape absolute end-0 right-[-1px] bottom-0 w-[55px]"></div>
                    </div>
                  </div>
                </div>
              <?php endforeach; ?>
            </div>
          </div>

          <!-- Slider controls (shown only when slider active via JS) -->
          <div class="card-slider-controls lg:hidden mt-3 flex justify-center items-center gap-4">
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

      <?php if ($cta && !empty($cta['url'])): ?>
        <div class="text-center  mt-6 md:mt-7 anim-uni-in-up">
          <a href="<?php echo esc_url($cta['url']); ?>" class="btn" <?php if (!empty($cta['target'])): ?>target="<?php echo esc_attr($cta['target']); ?>" <?php endif; ?>>
            <?php echo esc_html($cta['title'] ?: 'Read More'); ?>
          </a>
        </div>
      <?php endif; ?>


    </div>

  </section>
<?php endif; ?>