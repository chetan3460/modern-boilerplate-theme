<?php

/**
 * Hero Block Template
 *
 * ACF Fields:
 * - hero_group (group)
 *   - title (text)
 *   - subtitle (text)
 *   - description (wysiwyg)
 *   - cta (link)
 * - banner_slider (repeater with banner_images field)
 * - select_news (relationship)
 */

// Get ACF field groups
$hero_group = get_sub_field('hero_group');
$title = get_sub_field('title');
$subtitle = get_sub_field('subtitle');
$description = get_sub_field('descritption'); // Note: keeping the typo from ACF field name
$banner_slider = get_sub_field('banner_slider');
$cta = get_sub_field('cta');

// Using per-slide content directly in swiper slides

// Hiding and cosmetics
// $hide_block = get_sub_field('hide_block');
include locate_template('templates/blocks/hide_block.php', false, false);

if ($banner_slider && !$hide_block): ?>
  <!-- Hero Section -->
  <section class="relative overflow-hidden hero-block pt-[83px] gsap-ignore"
    data-component="HeroSlider"
    data-load="eager">
    <div class="container-fluid  relative flex items-center justify-between p-0">

      <?php if (!empty($banner_slider)): ?>
        <div class="hero-slider swiper">
          <div class="swiper-wrapper">
            <?php foreach ($banner_slider as $i => $slide):

              $image = $slide['banner_images'] ?? null;
              if (!$image) {
                continue;
              }

              $alt = !empty($image['alt']) ? esc_attr($image['alt']) : 'Hero slide ' . ($i + 1);
              $image_id = is_array($image) ? $image['ID'] : $image;

              // Get slide-specific content
              $slide_title = $slide['banner_title'] ?? '';
              $slide_subtitle = $slide['banner_subtitle'] ?? '';
              $slide_description = $slide['banner_description'] ?? '';
              ?>
              <div class="swiper-slide">
                <?php // Use optimized image function for hero slides (critical for LCP)
                if (function_exists('resplast_optimized_image')) {
                  echo resplast_optimized_image($image_id, 'full', [
                    'class' =>
                      'object-cover h-full w-full  aspect-[1] sm:aspect-[1.8] md:aspect-auto',
                    'alt' => $alt,
                    'priority' => $i === 0, // First slide gets fetchpriority="high"
                    'lazy' => $i > 0, // Only lazy load subsequent slides
                    'content_visibility' => $i > 2, // Use content-visibility for slides beyond viewport
                    'avif_support' => true,
                  ]);
                } else {
                  echo wp_get_attachment_image($image_id, 'full', false, [
                    'class' =>
                      'object-cover h-full w-full  aspect-[1] sm:aspect-[1.8] md:aspect-auto',
                    'alt' => $alt,
                    'fetchpriority' => $i === 0 ? 'high' : null,
                    'loading' => $i === 0 ? 'eager' : 'lazy',
                    'decoding' => 'async',
                  ]);
                } ?>

                <!-- Content Overlay for This Slide -->
                <div class="absolute inset-0 flex justify-start items-start sl:items-center max-lg:flex-col z-1">
                  <!-- Left Block - Main Content -->
                  <div class="pl-3 pt-7 lg:pt-0 sl:pl-10 lg:pl-14 left-block flex flex-col relative w-full  space-y-6 text-white">
                    <!-- Per-slide Title, Subtitle, Description -->
                    <div class="hero-titles-container">
                      <?php if ($slide_title): ?>
                        <h1 class="hero-title font-bold !text-white tracking-[-1.5px] mb-0 text-[clamp(2rem,8vw,5rem)] leading-[0.95]" data-original-text="<?= esc_attr(
                          $slide_title
                        ) ?>">
                          <?= esc_html($slide_title) ?>
                        </h1>
                      <?php endif; ?>

                      <?php if ($slide_subtitle): ?>
                        <h2 class="hero-subtitle font-medium tracking-[-0.36px] md:tracking-[-0.84px] !text-white !text-[clamp(1.125rem,4vw,2.75rem)] leading-[1.1]" style="opacity: 0;">
                          <?= esc_html($slide_subtitle) ?>
                        </h2>
                      <?php endif; ?>

                      <?php if ($slide_description): ?>
                        <div class="hero-description prose prose-p:text-[14px] sm:prose-p:text-base prose-p:!text-white prose-p:leading-[22px] md:prose-p:text-[18px] max-w-[400px] lg:max-w-[490px] mt-2 md:mt-4" style="opacity: 0;">
                          <?php echo wp_kses_post($slide_description); ?>
                        </div>
                      <?php endif; ?>
                    </div>

                    <!-- Caption -->
                    <?php if ($cta && $cta['url']): ?>
                      <div class="cta-block" style="opacity: 0;">
                        <a
                          href="<?php echo esc_url($cta['url']); ?>"
                          class="btn"
                          <?php if ($cta['target']) {
                            echo 'target="' . esc_attr($cta['target']) . '"';
                          } ?>>
                          <?php echo esc_html($cta['title'] ?: 'Get in Touch'); ?>
                        </a>
                      </div>
                    <?php endif; ?>
                  </div>
                </div>
              </div>
            <?php
            endforeach; ?>
          </div>




        </div>
      <?php endif; ?>

    </div>

  </section>
<?php endif;
?>
