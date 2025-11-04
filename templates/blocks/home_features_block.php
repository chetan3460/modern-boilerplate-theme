<?php

/**
 * =============================================================================
 * HOME FEATURES BLOCK
 * =============================================================================
 *
 * ACF Fields:
 * - hide_block (true/false)
 * - title (text)
 * - description (wysiwyg)
 * - feature_items (repeater)
 *    - icon (image)
 *    - title (text)
 *    - description (wysiwyg)
 * - cta (link)
 */

include locate_template('templates/blocks/hide_block.php', false, false);

$title         = get_sub_field('title');
$description   = get_sub_field('description');
$feature_items = get_sub_field('feature_items');
$cta           = get_sub_field('cta');
?>

<?php if (($title || $feature_items) && !$hide_block): ?>
    <section class="home-features-block fade-in">
        <div class="container-fluid relative">

            <div class="flex lg:flex-row flex-col gap-3 lg:gap-24">

                <!-- Left Column: Heading + CTA -->
                <div class="w-full lg:w-5/12 relative">

                    <?php if ($title || $description): ?>
                        <div class="section-heading text-center md:text-left">
                            <?php if ($title): ?>
                                <h2 class="mb-1 fade-text"><?= esc_html($title); ?></h2>
                            <?php endif; ?>
                            <?php if ($description): ?>
                                <div class="anim-uni-in-up"><?= wp_kses_post($description); ?></div>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>

                    <!-- CTA Button (Desktop) -->
                    <?php if ($cta && is_array($cta) && !empty($cta['url'])): ?>
                        <div class="hidden lg:block mt-6 anim-uni-in-up">
                            <a href="<?= esc_url($cta['url']); ?>"
                                <?php if (!empty($cta['target'])): ?> target="<?= esc_attr($cta['target']); ?>" <?php endif; ?>
                                class="btn">
                                <?= esc_html($cta['title'] ?: 'Learn More'); ?>
                            </a>
                        </div>
                    <?php endif; ?>

                    <!-- Decorative Shape -->
                    <div class="hidden lg:block absolute right-0 bottom-0 -z-1 pointer-none w-[87px] lg:w-fit" data-speed="1.25">
                        <img src="<?= get_stylesheet_directory_uri(); ?>/assets/images/home/shapes/shape-3.webp" alt="">
                    </div>

                </div>
                <!-- End Left Column -->

                <!-- Right Column: Feature Items -->
                <?php if ($feature_items && is_array($feature_items) && count($feature_items) > 0): ?>
                    <?php $delay = 0; // initialize delay 
                    ?>
                    <div class="mt-4 md:mt-0 w-full lg:w-7/12 fade-up-stagger-wrap">
                        <div class="grid grid-cols-2 gap-4 sm:gap-8 md:gap-10">

                            <?php foreach ($feature_items as $item):
                                $delay += 0.2; // increase delay by 0.2 each time
                                $icon = $item['icon'] ?? null;
                                $item_title = $item['title'] ?? '';
                                $item_description = $item['description'] ?? '';
                            ?>
                                <div class="feature-item fade-up-stagger" data-delay="<?php echo number_format($delay, 1); ?>">

                                    <!-- Icon -->
                                    <?php if ($icon && is_array($icon) && !empty($icon['url'])): ?>
                                        <div class="feature-icon">
                                            <img class="w-[56px] h-[56px]"
                                                src="<?= esc_url($icon['url']); ?>"
                                                alt="<?= esc_attr($icon['alt'] ?: $item_title); ?>"
                                                loading="lazy">
                                        </div>
                                    <?php endif; ?>

                                    <!-- Content -->
                                    <div class="feature-content">
                                        <?php if ($item_title): ?>
                                            <div class="h3 my-1 md:my-2 !font-semibold !text-grey-1">
                                                <?= esc_html($item_title); ?>
                                            </div>
                                        <?php endif; ?>
                                        <?php if ($item_description): ?>
                                            <div class="text-sm md:text-base tracking-[0.32px]">
                                                <?= wp_kses_post($item_description); ?>
                                            </div>
                                        <?php endif; ?>
                                    </div>

                                </div>
                            <?php endforeach; ?>

                        </div>
                    </div>
                <?php endif; ?>
                <!-- End Right Column -->


                <!-- CTA Button (Mobile) -->
                <?php if ($cta && is_array($cta) && !empty($cta['url'])): ?>
                    <div class="block lg:hidden mt-7 mx-auto anim-uni-in-up">
                        <a href="<?= esc_url($cta['url']); ?>"
                            <?php if (!empty($cta['target'])): ?> target="<?= esc_attr($cta['target']); ?>" <?php endif; ?>
                            class="btn">
                            <?= esc_html($cta['title'] ?: 'Learn More'); ?>
                        </a>
                    </div>
                <?php endif; ?>

            </div>

            <!-- Decorative Shape (Mobile) -->
            <div class="md:hidden block absolute right-0 bottom-0 -z-1 pointer-none w-[85px]" data-speed="1.25">
                <img src="<?= get_stylesheet_directory_uri(); ?>/assets/images/home/shapes/shape-4.webp" alt="">
            </div>

        </div>
    </section>
<?php endif; ?>