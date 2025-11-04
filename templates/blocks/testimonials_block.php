<?php

/**
 * Testimonials Block Template
 * 
 * ACF Fields:
 * - hide_block (true_false)
 * - title (text)
 * - description (textarea)
 * - testimonials (repeater)
 *   - quote (textarea)
 *   - name (text)
 *   - position (text)
 *   - image (image)
 * - slider_settings (group)
 *   - autoplay (true_false)
 *   - autoplay_delay (number)
 *   - show_navigation (true_false)
 */

// Get ACF fields
$title = get_sub_field('title') ?: 'Hear from our people';
$description = get_sub_field('description') ?: 'The experiences of our people reflect the passion, purpose, and pride that make RPL a great place to grow.';
$testimonials = get_sub_field('testimonials') ?: [];
$slider_settings = get_sub_field('slider_settings') ?: [];

// Slider settings
$autoplay = $slider_settings['autoplay'] ?? true;
$autoplay_delay = $slider_settings['autoplay_delay'] ?? 5;
$show_navigation = $slider_settings['show_navigation'] ?? true;

// Hiding and cosmetics
include locate_template('templates/blocks/hide_block.php', false, false);

if (!$hide_block && !empty($testimonials)): ?>

  <section class="testimonials-block  fade-in"
    data-component="TestimonialsSlider"
    data-load="lazy">
    <div class="container-fluid">

      <!-- Section Header -->
      <div class="section-heading text-center">
        <div class="text-center mb-12 lg:mb-16">
          <?php if ($title): ?>
            <h2 class=" fade-text">
              <?php echo esc_html($title); ?>
            </h2>
          <?php endif; ?>

          <?php if ($description): ?>
            <div class="">
              <?php echo esc_html($description); ?>
            </div>
          <?php endif; ?>
        </div>
      </div>

      <!-- Testimonials Slider -->
      <div class="testimonials-slider-wrapper relative">
        <div class="swiper testimonials-swiper"
          data-autoplay="<?php echo $autoplay ? 'true' : 'false'; ?>"
          data-autoplay-delay="<?php echo esc_attr($autoplay_delay * 1000); ?>"
          data-show-navigation="<?php echo $show_navigation ? 'true' : 'false'; ?>">

          <div class="swiper-wrapper">
            <?php foreach ($testimonials as $index => $testimonial):
              $quote = $testimonial['quote'] ?? '';
              $name = $testimonial['name'] ?? '';
              $position = $testimonial['position'] ?? '';
              $image = $testimonial['image'] ?? null;

              if (empty($quote) || empty($name)) continue;
            ?>
              <div class="swiper-slide">
                <div class="testimonial-card bg-light-blue rounded-[36px]  max-w-[470px] lg:max-w-[1026px] mx-auto bottom-right">
                  <div class="flex flex-col lg:flex-row  gap-4 lg:gap-12 lg:p-0 p-6 pb-0">


                    <!-- Quote Section -->
                    <div class="  w-full md:w-7/12 flex gap-3 md:gap-7 relative  p-0 lg:p-14">
                      <!-- Large Red Quote Mark -->
                      <div class=" select-none" aria-hidden="true">
                        <img class="min-w-[30px] md:min-w-[60px] lg:min-w-[75px]" src="<?php echo get_vite_asset('images/icons/quote.svg'); ?>" alt="">

                      </div>

                      <div class="flex flex-col gap-5 md:gap-10 lg:max-w-[416px] max-w-none">
                        <!-- Quote Text -->

                        <?php if ($quote): ?>
                          <div class="quote_content-content prose max-w-none text-[#111] prose-p:text-sm md:prose-p:text-2xl md:prose-p:leading-[36px] prose-p:font-normal">
                            <?php echo wp_kses_post(nl2br($quote)); ?>
                          </div>
                        <?php endif; ?>

                        <!-- Author Information -->
                        <div>
                          <div class="author-info">
                            <?php if ($name): ?>
                              <div class="quote_name-text body-1 text-grey-7 font-semibold leading-[26px] tracking-[0.36px] md:mb-1"><?php echo esc_html($name); ?></div>
                            <?php endif; ?>

                            <?php if ($position): ?>
                              <div class="quote_designation-text body-1 text-grey-7 font-normal leading-[26px] tracking-[0.36px]"><?php echo esc_html($position); ?></div>
                            <?php endif; ?>
                          </div>
                        </div>
                      </div>

                    </div>

                    <!-- Certification Badge -->
                    <?php if ($image): ?>
                      <div class=" w-full md:w-5/12 quote_image-wrapper flex justify-center items-end text-center">
                        <div class="">
                          <?php
                          echo resplast_optimized_image(
                            $image['ID'],
                            'medium_large',
                            [
                              'class' => '',
                              'alt' => esc_attr($name . ' - ' . $position),
                              'lazy' => true,
                              'avif_support' => true
                            ]
                          );
                          ?>
                        </div>
                      </div>
                    <?php endif; ?>




                  </div>
                </div>
              </div>
            <?php endforeach; ?>
          </div>


        </div>

        <!-- Slider Navigation & Pagination -->
        <?php if (count($testimonials) > 1): ?>
          <div class="mt-3 flex justify-center items-center gap-4">
            <div class="swiper-btn-prev-pagination swiper-btn-prev">
              <svg xmlns="http://www.w3.org/2000/svg" width="9" height="7" viewBox="0 0 9 7" fill="none">
                <path d="M7.92 3.18c.24 0 .44.2.44.45s-.2.44-.44.44H1.67l2.13 2.13a.44.44 0 01-.63.63L.59 4.26a.9.9 0 010-1.26l2.58-2.57a.44.44 0 01.63.63L1.67 3.18h6.25z" fill="#DA000E" />
              </svg>
            </div>

            <div class="swiper-pagination-custom text-primary text-xs font-medium !w-4 !h-4"></div>

            <div class="swiper-btn-next-pagination swiper-btn-next">
              <svg xmlns="http://www.w3.org/2000/svg" width="9" height="7" viewBox="0 0 9 7" fill="none">
                <path d="M1.16 3.18a.44.44 0 000 .89h6.25L5.29 6.2a.44.44 0 10.63.63l2.58-2.58a.9.9 0 000-1.26L5.92.43a.44.44 0 10-.63.63l2.13 2.13H1.16z" fill="#DA000E" />
              </svg>
            </div>
          </div>
        <?php endif; ?>

      </div>

    </div>
  </section>

<?php endif; ?>