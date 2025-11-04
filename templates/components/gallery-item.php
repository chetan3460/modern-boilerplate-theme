<?php

/**
 * Gallery Item Component
 * Reusable component for displaying gallery items
 * 
 * Expected $item array structure:
 * - gallery_image (image field)
 * - title (string)
 * - year (string)
 */

if (!isset($item) || empty($item)) return;
?>

<div class="gallery_items-item relative overflow-hidden custom-rounded duration-300 h-full bottom-right ">
  <?php if (isset($item['gallery_image']) && $item['gallery_image']): ?>
    <div class="image-container overflow-hidden">
      <?php if (function_exists('resplast_optimized_image')): ?>
        <?php echo resplast_optimized_image($item['gallery_image']['ID'], 'large', [
          'class' => 'w-full h-full object-cover transition-transform duration-300 hover:scale-105',
          'alt' => $item['gallery_image']['alt'] ?: $item['title'] ?: '',
          'lazy' => true
        ]); ?>
      <?php else: ?>
        <img src="<?php echo esc_url($item['gallery_image']['url']); ?>"
          alt="<?php echo esc_attr($item['gallery_image']['alt'] ?: $item['title'] ?: ''); ?>"
          class="w-full h-full object-cover transition-transform duration-300 hover:scale-105">
      <?php endif; ?>
    </div>
  <?php endif; ?>

  <!-- Content overlay -->
  <div class="absolute bottom-0 left-0 right-0  pl-6 pb-8 pr-28 bg-gradient-to-t from-black/50 from-30% to-transparent size-full -z-0 flex items-baseline flex-col justify-end
">
    <?php if (isset($item['title']) && $item['title']): ?>
      <div class="title text-white font-semibold text-sm mb-1"><?php echo esc_html($item['title']); ?></div>
    <?php endif; ?>
    <?php if (isset($item['year']) && $item['year']): ?>
      <div class="year text-white text-xs lg:text-sm"><?php echo esc_html($item['year']); ?></div>
    <?php endif; ?>
  </div>
</div>