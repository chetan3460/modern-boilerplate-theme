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
$stats_group   = get_sub_field('stats_group');
$title         = get_sub_field('title');
$description   = get_sub_field('description');
$stats_items   = get_sub_field('stats_items');
$stats_cta     = get_sub_field('stats_cta');

// Hiding and cosmetics
include locate_template('templates/blocks/hide_block.php', false, false);
?>

<?php if (($stats_items) && !$hide_block): ?>
    <section class="stats-block relative fade-in"
        data-component="StatsCounter"
        data-load="lazy">

        <div class="container-fluid relative">

            <!-- Header Section -->
            <?php if ($title || $description): ?>
                <div class="text-center section-heading">

                    <?php if ($title): ?>
                        <h2 class="mb-2 fade-text"><?= esc_html($title); ?></h2>
                    <?php endif; ?>

                    <?php if ($description): ?>
                        <div class="">
                            <?php echo wp_kses_post($description); ?>
                        </div>
                    <?php endif; ?>

                </div>
            <?php endif; ?>

            <!-- Stats Grid -->
            <?php if ($stats_items && count($stats_items) > 0): ?>
                <div class="stats-gird-items w-full lg:w-7/12 mx-auto py-6 md:py-12
                    grid grid-cols-3 lg:grid-cols-<?= min(4, count($stats_items)) ?>
                    gap-2 lg:gap-12">

                    <?php foreach ($stats_items as $index => $item):
                        $number     = $item['stats_number'] ?? '';
                        $sign       = $item['stats_sign'] ?? '';
                        $stat_title = $item['stats_title'] ?? '';

                        if (empty($number) && empty($stat_title)) continue;
                    ?>
                        <div class="stats-item text-center">

                            <!-- Number Display -->
                            <div class="inline-flex items-baseline justify-center">
                                <div class="stats-counter text-[38px] leading-[35px] tracking-[-0.76px] md:text-[80px] md:leading-[76px] font-normal text-primary tabular-nums"
                                    data-target="<?= esc_attr($number); ?>"
                                    data-duration="2000">
                                    <?= esc_html($number); ?>
                                </div>

                                <?php if ($sign): ?>
                                    <span class="text-[38px] leading-[35px] md:text-[80px] md:leading-[75px] text-primary font-normal">
                                        <?= esc_html($sign); ?>
                                    </span>
                                <?php endif; ?>
                            </div>

                            <!-- Stats Title -->
                            <?php if ($stat_title): ?>
                                <div class="text-sm md:text-base text-grey-2">
                                    <?= esc_html($stat_title); ?>
                                </div>
                            <?php endif; ?>

                        </div>
                    <?php endforeach; ?>

                </div>
            <?php endif; ?>

            <!-- CTA Section -->
            <?php if ($stats_cta && $stats_cta['url']): ?>
                <div class="text-center">
                    <a href="<?= esc_url($stats_cta['url']); ?>"
                        class="btn"
                        <?php if (!empty($stats_cta['target'])): ?>
                        target="<?= esc_attr($stats_cta['target']); ?>"
                        <?php endif; ?>>
                        <?= esc_html($stats_cta['title'] ?: 'Learn More'); ?>
                    </a>
                </div>
            <?php endif; ?>

            <!-- Decorative Shapes -->
            <div class="absolute left-0 bottom-0 -z-1 pointer-none w-[87px] lg:w-fit" data-speed="1.25">
                <img src="<?= get_stylesheet_directory_uri(); ?>/assets/images/home/shapes/shape-1.webp" alt="">
            </div>

            <div class="absolute right-[-45px] top-0 -z-1" data-speed="1.25">
                <img class="pointer-none w-[118px] lg:w-fit"
                    src="<?= get_stylesheet_directory_uri(); ?>/assets/images/home/shapes/shape-2.webp"
                    alt="">
            </div>

        </div> <!-- end container-fluid -->
    </section>
<?php endif; ?>