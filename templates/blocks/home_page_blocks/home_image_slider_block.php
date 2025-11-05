<?php

/**
 * =============================================================================
 * HOME IMAGE SLIDER BLOCK
 * =============================================================================
 *
 * ACF Fields:
 * - slider_group (group)
 *   - title (text)
 *   - description (wysiwyg)
 * - slider_items (repeater)
 *   - slider_title (text)
 *   - slider_description (wysiwyg)
 *   - images (image)
 * - cta (link)
 */

// Get ACF fields
$slider_group = get_sub_field('slider_group');
$title = get_sub_field('title');
$description = get_sub_field('description');
$slider_items = get_sub_field('slider_items');
$cta = get_sub_field('cta');

// Hiding and spacing options
$hide_block = get_sub_field('hide_block');
$padding_top = get_sub_field('padding_top');
$padding_bottom = get_sub_field('padding_bottom');
?>

<?php if (($title || $description || $slider_items) && !$hide_block): ?>
  <section class="image-slider-block relative fade-in"
    data-component="ImageSliderBlock"
    data-load="lazy">

    <div class="container-fluid relative">

      <!-- Header Section -->
      <?php if ($title || $description): ?>
        <div class="section-heading text-center mb-4 md:mb-8">
          <?php if ($title): ?>
            <h2 class="mb-1 fade-text"><?= esc_html($title) ?></h2>
          <?php endif; ?>
          <?php if ($description): ?>
            <div class="anim-uni-in-up">
              <?= wp_kses_post($description) ?>
            </div>
          <?php endif; ?>
        </div>
      <?php endif; ?>

      <!-- Slider Section -->
      <?php if ($slider_items && count($slider_items) > 0): ?>
        <div class="image-slider swiper overflow-hidden">
          <div class="swiper-wrapper">

            <?php foreach ($slider_items as $item):

              $slide_title = $item['slider_title'] ?? '';
              $slide_content = $item['slider_description'] ?? '';
              $slide_image = $item['images'] ?? null;

              if (!$slide_image) {
                continue;
              }
              ?>
              <div class="swiper-slide relative group">
                <div class="relative overflow-hidden aspect-[248/91] ">

                  <!-- Image -->
                  <img src="<?= esc_url($slide_image['url']) ?>"
                    alt="<?= esc_attr($slide_title ?: 'Slider image') ?>"
                    class="w-full h-full object-cover rounded-2xl"
                    loading="lazy">



                  <!-- Overlay -->
                  <div class="absolute inset-0 bg-[linear-gradient(186deg,rgba(0,0,0,0)_17.36%,rgba(0,0,0,0.8)_95.64%)] lg:bg-[linear-gradient(180deg, rgba(0, 0, 0, 0.00) 55.77%, rgba(0, 0, 0, 0.50) 88.45%)] rounded-2xl"></div>

                  <!-- Slide Content -->
                  <?php if ($slide_title || $slide_content): ?>
                    <div class="slide-content absolute bottom-0 left-0 right-0 px-6 lg:px-10 pb-12  opacity-0">
                      <?php if ($slide_title): ?>
                        <div class="slide-title h3 !text-white font-semibold mb-2"><?= esc_html(
                          $slide_title
                        ) ?></div>
                      <?php endif; ?>
                      <?php if ($slide_content): ?>
                        <div class="slide-description prose prose-p:text-sm prose-p:lg:text-base prose-p:!text-white max-w-[559px] prose-p:leading-[19px]">
                          <?= wp_kses_post($slide_content) ?>
                        </div>
                      <?php endif; ?>
                    </div>
                  <?php endif; ?>

                </div>
              </div>
            <?php
            endforeach; ?>

          </div>

          <!-- Slider Navigation -->
          <?php if (count($slider_items) > 1): ?>
            <div class="swiper-navigation flex items-center justify-center mt-3 gap-4">
              <div class="swiper-btn-prev">
                <svg xmlns="http://www.w3.org/2000/svg" width="9" height="7" viewBox="0 0 9 7" fill="none">
                  <path d="M7.92214 3.18291C8.16739 3.18291 8.36621 3.38173 8.36621 3.62699C8.36621 3.87224 8.16739 4.07106 7.92214 4.07106L1.66704 4.07106L3.79543 6.19944C3.96885 6.37286 3.96885 6.65403 3.79543 6.82745C3.62201 7.00087 3.34084 7.00087 3.16742 6.82745L0.594961 4.255C0.24812 3.90816 0.24812 3.34581 0.594961 2.99897L3.16742 0.426516C3.34084 0.253095 3.62201 0.253096 3.79543 0.426516C3.96885 0.599937 3.96885 0.881107 3.79543 1.05453L1.66705 3.18291L7.92214 3.18291Z" fill="#DA000E" />
                </svg>
              </div>

              <div class="swiper-pagination flex gap-2 !w-auto"></div>

              <div class="swiper-btn-next w-12">
                <svg xmlns="http://www.w3.org/2000/svg" width="9" height="7" viewBox="0 0 9 7" fill="none">
                  <path d="M1.15891 3.18291C0.913661 3.18291 0.714844 3.38173 0.714844 3.62699C0.714844 3.87224 0.913661 4.07106 1.15892 4.07106L7.41401 4.07106L5.28562 6.19944C5.1122 6.37286 5.1122 6.65403 5.28562 6.82745C5.45904 7.00087 5.74021 7.00087 5.91364 6.82745L8.48609 4.255C8.83293 3.90816 8.83294 3.34581 8.48609 2.99897L5.91363 0.426516C5.74021 0.253095 5.45904 0.253096 5.28562 0.426516C5.1122 0.599937 5.1122 0.881107 5.28562 1.05453L7.41401 3.18291L1.15891 3.18291Z" fill="#DA000E" />
                </svg>
              </div>
            </div>
          <?php endif; ?>

        </div>
      <?php endif; ?>

      <!-- CTA Section -->
      <?php if ($cta && $cta['url']): ?>
        <div class="text-center anim-uni-in-up">
          <a href="<?= esc_url($cta['url']) ?>"
            class="btn"
            <?php if (!empty($cta['target'])): ?> target="<?= esc_attr(
   $cta['target']
 ) ?>" <?php endif; ?>>
            <?= esc_html($cta['title'] ?: 'Learn More') ?>
          </a>
        </div>
      <?php endif; ?>



    </div> <!-- end container-fluid -->

  </section>
<?php endif; ?>
