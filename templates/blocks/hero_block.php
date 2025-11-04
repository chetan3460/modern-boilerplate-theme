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
$select_news = get_sub_field('select_news');

// Using per-slide content directly in swiper slides

// Hiding and cosmetics
// $hide_block = get_sub_field('hide_block');
include locate_template('templates/blocks/hide_block.php', false, false);

if (($banner_slider) && !$hide_block): ?>
    <!-- Hero Section -->
    <section class="relative overflow-hidden hero-block pt-[83px] gsap-ignore"
        data-component="HeroSlider"
        data-load="eager" data-smooth="false">
        <div class="container-fluid xl:px-24 lg:px-14 px-5 relative flex items-center justify-between">

            <?php if (!empty($banner_slider)): ?>
                <div class="hero-slider swiper">
                    <div class="swiper-wrapper">
                        <?php foreach ($banner_slider as $i => $slide):
                            $image = $slide['banner_images'] ?? null;
                            if (!$image) continue;

                            $alt = !empty($image['alt']) ? esc_attr($image['alt']) : 'Hero slide ' . ($i + 1);
                            $image_id = is_array($image) ? $image['ID'] : $image;

                            // Get slide-specific content
                            $slide_title = $slide['banner_title'] ?? '';
                            $slide_subtitle = $slide['banner_subtitle'] ?? '';
                            $slide_description = $slide['banner_description'] ?? '';
                        ?>
                            <div class="swiper-slide">
                                <?php
                                // Use optimized image function for hero slides (critical for LCP)
                                if (function_exists('resplast_optimized_image')) {
                                    echo resplast_optimized_image($image_id, 'full', [
                                        'class' => 'object-cover h-full w-full rounded-[18px] md:rounded-[40px] aspect-[1] sm:aspect-[1.8] md:aspect-auto',
                                        'alt' => $alt,
                                        'priority' => ($i === 0), // First slide gets fetchpriority="high"
                                        'lazy' => ($i > 0), // Only lazy load subsequent slides
                                        'content_visibility' => ($i > 2), // Use content-visibility for slides beyond viewport
                                        'avif_support' => true
                                    ]);
                                } else {
                                    echo wp_get_attachment_image($image_id, 'full', false, [
                                        'class' => 'object-cover h-full w-full rounded-[18px] md:rounded-[40px] aspect-[1] sm:aspect-[1.8] md:aspect-auto',
                                        'alt' => $alt,
                                        'fetchpriority' => ($i === 0) ? 'high' : null,
                                        'loading' => ($i === 0) ? 'eager' : 'lazy',
                                        'decoding' => 'async'
                                    ]);
                                }
                                ?>

                                <!-- Content Overlay for This Slide -->
                                <div class="absolute inset-0 flex justify-start items-start sl:items-center max-lg:flex-col z-1">
                                    <!-- Left Block - Main Content -->
                                    <div class="pl-3 pt-7 lg:pt-0 sl:pl-10 lg:pl-14 left-block flex flex-col relative w-full lg:w-2/3 space-y-6 text-white">
                                        <!-- Per-slide Title, Subtitle, Description -->
                                        <div class="hero-titles-container">
                                            <?php if ($slide_title): ?>
                                                <h1 class="hero-title font-bold !text-white tracking-[-1.5px] mb-0 text-[clamp(2rem,8vw,5rem)] leading-[0.95]" data-original-text="<?= esc_attr($slide_title) ?>">
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
                        <?php endforeach; ?>
                    </div>

                    <?php
                    $hide_news   = get_sub_field('hide_news');
                    // $select_news = get_field('select_news');

                    if (!$hide_news && !empty($select_news)) : ?>
                        <div class="right-block flex justify-end md:absolute end-0 bottom-0 z-1 mt-2 sl:mt-0">

                            <!-- Background Shape (nudged to avoid seam; no transforms to keep same layer as smoother) -->
                            <div class="hidden md:block bg-shape absolute bottom-[-10px] right-[-10px] z-0 pointer-events-none [backface-visibility:hidden]"></div>

                            <!-- Spotlight Block -->
                            <div class="spotlight-block w-full md:w-[292px] rounded-2xl relative z-1">

                                <!-- Header -->
                                <div class="spotlight-header flex items-start gap-3 p-4 min-h-[100px] text-white">
                                    <div class="w-5 h-5 flex items-center justify-center">
                                        <img src="<?php echo get_vite_asset('images/home/Announcement.svg'); ?>" alt="Announcement">
                                    </div>
                                    <div class="text-[18px] font-semibold leading-[22px] tracking-[-0.36px]">Spotlight</div>
                                </div>

                                <!-- News Slider -->
                                <div class="spotlight-slider swiper bg-[#F4F4F4] rounded-[20px] -mt-[50px]">
                                    <div class="swiper-wrapper">
                                        <?php foreach ($select_news as $news_post) : ?>
                                            <div class="swiper-slide">
                                                <article class="p-5">
                                                    <div class="flex gap-3">
                                                        <div class="flex-1 min-w-0">

                                                            <!-- Tags -->
                                                            <?php
                                                            $spotlight_terms = get_the_terms($news_post->ID, 'news_category') ?: get_the_category($news_post->ID);

                                                            if (empty($spotlight_terms) || is_wp_error($spotlight_terms)) {
                                                                $taxonomies = get_object_taxonomies(get_post_type($news_post->ID), 'names');
                                                                foreach ($taxonomies as $tax) {
                                                                    if ($tax === 'post_format') continue;
                                                                    $terms_try = get_the_terms($news_post->ID, $tax);
                                                                    if (!empty($terms_try) && !is_wp_error($terms_try)) {
                                                                        $spotlight_terms = $terms_try;
                                                                        break;
                                                                    }
                                                                }
                                                            }
                                                            ?>

                                                            <?php if (!empty($spotlight_terms) && !is_wp_error($spotlight_terms)) : ?>
                                                                <div class="flex flex-wrap gap-1 mb-3">
                                                                    <?php foreach (array_slice($spotlight_terms, 0, 2) as $t) : ?>
                                                                        <span class="badge"><?php echo esc_html($t->name); ?></span>
                                                                    <?php endforeach; ?>
                                                                </div>
                                                            <?php endif; ?>

                                                            <!-- Title + Arrow -->
                                                            <div class="flex items-start justify-between">
                                                                <div class="max-w-[204px] text-base font-bold tracking-[0.16px] text-[#202020]">
                                                                    <?php echo mb_strimwidth(get_the_title($news_post->ID), 0, 40, '...'); ?>
                                                                </div>
                                                                <a href="<?php echo get_permalink($news_post->ID); ?>" target="_blank" class="flex items-center justify-center">
                                                                    <img class="w-4 h-4" src="<?php echo get_vite_asset('images/home/right-arrow.svg'); ?>" alt="Read More">
                                                                </a>
                                                            </div>

                                                        </div>
                                                    </div>
                                                </article>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>

                                    <?php if (count($select_news) > 1) : ?>
                                        <!-- Navigation -->
                                        <div class="spotlight-navigation flex items-center justify-center gap-2 mt-[10px] relative">
                                            <div class="curve-shape flex items-center justify-center -mb-[1px]"></div>
                                            <div class="absolute z-1">
                                                <div class="spotlight-pagination flex gap-1 items-center justify-between"></div>
                                            </div>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>


                </div>
            <?php endif; ?>

        </div>

    </section>
<?php endif;
?>