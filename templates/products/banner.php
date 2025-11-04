<?php

/**
 * Products Page Banner Template
 * Following Global Banner Block structure
 *
 * ACF Fields:
 * - products_banner_title (text)
 * - products_banner_description (wysiwyg)
 * - products_banner_image (image)
 * - contact_us_link (link)
 * - download_file (file)
 * - download_button_text (text)
 */

$title = get_field('products_banner_title') ?: '';
$description = get_field('products_banner_description') ?: '';
$inner_banner_image = get_field('products_banner_image') ?: '';
$contact_us_link = get_field('contact_us_link') ?: '';
$download_file = get_field('download_file') ?: '';
$download_button_text = get_field('download_button_text') ?: 'Product Brochure';

// Include hide block functionality
include locate_template('templates/blocks/hide_block.php', false, false);

if (!$hide_block && ($title || $description || $inner_banner_image)): ?>

  <!-- Products Page Banner (following global banner structure) -->
  <section class="products_page_banner fade-in">
    <div class="container-fluid flex flex-col gap-6">

      <div class="flex gap-2 flex-col text-left md:text-center">
        <?php if ($title): ?>
          <h1 class="h2 text-grey-1 fade-text"><?php echo esc_html($title); ?></h1>
        <?php endif; ?>

        <?php if ($description): ?>
          <div class="max-w-[902px] mx-auto prose prose-p:text-base prose-p:leading-[22px] lg:prose-p:text-[18px] lg:prose-p:leading-[25px] prose-p:font-normal prose-p:text-grey-2">
            <?php echo wp_kses_post($description); ?>
          </div>
        <?php endif; ?>


      </div>

      <?php if ($inner_banner_image): ?>
        <div class="inner_banner_image-wrapper relative">
          <?php if (function_exists('resplast_optimized_image')): ?>
            <?php echo resplast_optimized_image($inner_banner_image['ID'], 'large', [
              'class' => 'inner_banner_image-image size-full object-cover rounded-[20px] lg:rounded-[40px]',
              'alt' => $inner_banner_image['alt'] ?: '',
              'lazy' => true
            ]); ?>
          <?php else: ?>
            <img src="<?php echo esc_url($inner_banner_image['url']); ?>"
              alt="<?php echo esc_attr($inner_banner_image['alt']); ?>"
              class="inner_banner_image-image">
          <?php endif; ?>
          <!-- Curve Shape (nudged to avoid seam; no transforms) -->
          <div class="curve-shape absolute bottom-[-1px] right-[-1px] w-[135px] sm:w-[185px] sl:w-auto pointer-events-none [backface-visibility:hidden]"></div>
        </div>
      <?php endif; ?>
      <!-- Action Buttons -->
      <?php if ($contact_us_link || $download_file): ?>
        <div class="flex flex-col sm:flex-row gap-4 justify-center">
          <!-- Contact Button -->
          <?php if ($contact_us_link): ?>
            <a href="<?php echo esc_url($contact_us_link['url']); ?>"
              <?php if ($contact_us_link['target']): ?>target="<?php echo esc_attr($contact_us_link['target']); ?>" <?php endif; ?>
              class="btn">
              <?php echo esc_html($contact_us_link['title'] ?: 'Get in Touch'); ?>
            </a>
          <?php endif; ?>

          <!-- Download Button -->
          <?php if ($download_file): ?>
            <?php
            $file_url = is_array($download_file) ? $download_file['url'] : wp_get_attachment_url($download_file);
            $file_name = is_array($download_file) ? $download_file['filename'] : basename(get_attached_file($download_file));
            ?>
            <a href="<?php echo esc_url($file_url); ?>"
              download="<?php echo esc_attr($file_name); ?>"
              class="btn btn-outline flex items-center gap-2 justify-center group">

              <svg xmlns="http://www.w3.org/2000/svg"
                width="16" height="16" viewBox="0 0 16 16"
                fill="none"
                class="transition-all duration-300 stroke-[#DA000E] group-hover:stroke-white">
                <path d="M14 10V12.6667C14 13.0203 13.8595 13.3594 13.6095 13.6095C13.3594 13.8595 13.0203 14 12.6667 14H3.33333C2.97971 14 2.64057 13.8595 2.39052 13.6095C2.14048 13.3594 2 13.0203 2 12.6667V10"
                  stroke-width="1.25" stroke-linecap="round" stroke-linejoin="round" />
                <path d="M4.66626 6.66687L7.99959 10.0002L11.3329 6.66687"
                  stroke-width="1.25" stroke-linecap="round" stroke-linejoin="round" />
                <path d="M8 10V2"
                  stroke-width="1.25" stroke-linecap="round" stroke-linejoin="round" />
              </svg>

              <?php echo esc_html($download_button_text); ?>
            </a>

          <?php endif; ?>
        </div>
      <?php endif; ?>

    </div>
  </section>

<?php endif; ?>