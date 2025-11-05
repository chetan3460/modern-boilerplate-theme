<?php

/**
 * Home Stats Block Template
 *
 * ACF Fields:
 * - stats_group (group)
 *   - title (text)
 *   - description (wysiwyg)
 * - stats_items (repeater)
 *   - stats_number (text)
 *   - stats_sign (text)
 *   - stats_title (text)
 * - stats_cta (link)
 */

// Get ACF field groups
$stats_group = get_sub_field('stats_group');
$title = get_sub_field('title');
$description = get_sub_field('description');
$stats_items = get_sub_field('stats_items');
$stats_cta = get_sub_field('stats_cta');

// Hiding and cosmetics
include locate_template('templates/blocks/hide_block.php', false, false);
?>

<?php if (($title || $stats_items) && !$hide_block): ?>
  <section class="stats-block relative fade-in overflow-hidden"
    data-component="StatsCounter"
    data-load="eager">

    <div class="container-fluid relative">

      <!-- Header Section -->
      <?php if ($title || $description): ?>
        <div class="text-center section-heading">

          <?php if ($title): ?>
            <h2 class="mb-2 fade-text"><?= esc_html($title) ?></h2>
          <?php endif; ?>

          <?php if ($description): ?>
            <div class="anim-uni-in-up">
              <?php echo wp_kses_post($description); ?>
            </div>
          <?php endif; ?>

        </div>
      <?php endif; ?>

      <!-- Stats Grid -->
      <?php if ($stats_items && count($stats_items) > 0): ?>
        <div class="stats-grid-items w-full  mx-auto py-6 md:py-12
            grid grid-cols-2 md:grid-cols-2 lg:grid-cols-<?php echo min(
              5,
              count($stats_items)
            ); ?> gap-4 lg:gap-12">

          <?php foreach ($stats_items as $index => $item):

            $number = $item['stats_number'] ?? '';
            $sign = $item['stats_sign'] ?? '';
            $stat_title = $item['stats_title'] ?? '';

            if (empty($number) && empty($stat_title)) {
              continue;
            }
            ?>
            <div class="stats-item text-center">

              <!-- Number Display -->
              <div class="inline-flex items-center justify-center">
                <div class="stats-counter text-[38px] leading-[35px] tracking-[-0.76px] md:text-[80px] md:leading-[76px] font-normal text-primary tabular-nums"
                  data-target="<?= esc_attr($number) ?>"
                  data-duration="2000">
                  <?= esc_html($number) ?>
                </div>

                <?php if ($sign): ?>
                  <span class="text-[38px] leading-[35px] md:text-[80px] md:leading-[75px] text-primary font-normal">
                    <?= esc_html($sign) ?>
                  </span>
                <?php endif; ?>
              </div>

              <!-- Stats Title -->
              <?php if ($stat_title): ?>
                <div class="text-sm md:text-base text-grey-2">
                  <?= esc_html($stat_title) ?>
                </div>
              <?php endif; ?>

            </div>
          <?php
          endforeach; ?>

        </div>
      <?php endif; ?>

      <!-- CTA Section -->
      <?php if ($stats_cta && $stats_cta['url']): ?>
        <div class="text-center anim-uni-in-up">
          <a href="<?= esc_url($stats_cta['url']) ?>"
            class="btn"
            <?php if (!empty($stats_cta['target'])): ?>
            target="<?= esc_attr($stats_cta['target']) ?>"
            <?php endif; ?>>
            <?= esc_html($stats_cta['title'] ?: 'Learn More') ?>
          </a>
        </div>
      <?php endif; ?>



    </div> <!-- end container-fluid -->
  </section>
<?php endif; ?>
