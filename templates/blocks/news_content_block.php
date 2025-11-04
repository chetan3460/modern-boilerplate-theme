<?php

/**
 * News Content Block Template
 * 
 * Flexible ACF-based content block for News posts.
 * Supports: text, image + text, quote, and gallery layouts.
 *
 * @package Resplast
 */

if (!defined('ABSPATH')) exit;

// === Block Fields ===
if (get_sub_field('hide_block')) return;

$content_type     = get_sub_field('content_type') ?: 'text';
$title            = get_sub_field('title');
$subtitle         = get_sub_field('subtitle');
$content          = get_sub_field('content');
$background_color = get_sub_field('background_color') ?: 'white';
$text_alignment   = get_sub_field('text_alignment') ?: 'left';

// Optional extras
$image            = get_sub_field('image');
$cta_text         = get_sub_field('cta_text');
$cta_link         = get_sub_field('cta_link');
$quote_author     = get_sub_field('quote_author');
$quote_position   = get_sub_field('quote_position');
$gallery_images   = get_sub_field('gallery_images');

// === Helper Maps ===
$bg_classes = [
    'white'    => 'bg-white',
    'gray'     => 'bg-gray-50',
    'blue'     => 'bg-blue-50',
    'gradient' => 'bg-gradient-to-r from-blue-50 to-gray-50'
];

$text_align_classes = [
    'left'   => 'text-left',
    'center' => 'text-center',
    'right'  => 'text-right'
];

$bg_class   = $bg_classes[$background_color] ?? 'bg-white';
$text_align = $text_align_classes[$text_alignment] ?? 'text-left';
$block_id   = 'news-content-' . uniqid();
?>

<section id="<?php echo esc_attr($block_id); ?>" class="news-content-block  <?php echo esc_attr($bg_class); ?>">
    <div class="">

        <?php if ($content_type === 'text'): ?>
            <!-- Text Content -->
            <div class="<?php echo esc_attr($text_align); ?>">

                <?php if ($title): ?>
                    <h2 class="mb-1">
                        <?php echo esc_html($title); ?>
                    </h2>
                <?php endif; ?>

                <?php if ($subtitle): ?>
                    <p class="">
                        <?php echo esc_html($subtitle); ?>
                    </p>
                <?php endif; ?>

                <?php if ($content): ?>
                    <div class="prose prose-p:text-grey-1 prose-strong:font-semibold prose-sm md:prose-lg max-w-none <?php echo $text_alignment === 'center' ? 'prose-center' : ''; ?>">
                        <?php echo wp_kses_post($content); ?>
                    </div>
                <?php endif; ?>

                <?php if ($cta_text && $cta_link): ?>
                    <div class="mt-8">
                        <a href="<?php echo esc_url($cta_link); ?>"
                            class="inline-flex items-center gap-2 px-8 py-4 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-xl transition-all hover:scale-105">
                            <?php echo esc_html($cta_text); ?>
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                            </svg>
                        </a>
                    </div>
                <?php endif; ?>

            </div>

        <?php elseif ($content_type === 'image_text'): ?>
            <!-- Image + Text -->
            <?php if ($image): ?>
                <div class="bottom-right">
                    <?php
                    if (function_exists('resplast_optimized_image')) {
                        echo resplast_optimized_image($image['ID'], 'large', [
                            'class' => 'w-full h-auto custom-rounded',
                            'alt'   => $image['alt'] ?: $title,
                            'lazy'  => true,
                            'avif_support' => true
                        ]);
                    } else {
                        echo wp_get_attachment_image($image['ID'], 'large', false, [
                            'class' => 'w-full h-auto custom-rounded',
                            'alt'   => $image['alt'] ?: $title
                        ]);
                    }
                    ?>
                </div>
            <?php endif; ?>
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">



                <div class="order-1 lg:order-2 <?php echo esc_attr($text_align); ?>">
                    <?php if ($title): ?>
                        <h2 class="text-3xl lg:text-4xl font-bold text-gray-900 mb-6">
                            <?php echo esc_html($title); ?>
                        </h2>
                    <?php endif; ?>

                    <?php if ($subtitle): ?>
                        <p class="text-xl text-gray-600 font-medium mb-6">
                            <?php echo esc_html($subtitle); ?>
                        </p>
                    <?php endif; ?>

                    <?php if ($content): ?>
                        <div class="prose prose-lg text-gray-700 mb-6">
                            <?php echo wp_kses_post($content); ?>
                        </div>
                    <?php endif; ?>

                    <?php if ($cta_text && $cta_link): ?>
                        <a href="<?php echo esc_url($cta_link); ?>"
                            class="inline-flex items-center gap-2 px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg transition-colors">
                            <?php echo esc_html($cta_text); ?>
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                            </svg>
                        </a>
                    <?php endif; ?>
                </div>
            </div>

        <?php elseif ($content_type === 'quote'): ?>
            <!-- Quote -->
            <div class="max-w-4xl mx-auto text-center">
                <?php if ($content): ?>
                    <blockquote class="text-2xl lg:text-3xl font-medium text-gray-900 italic mb-8 leading-relaxed">
                        “<?php echo esc_html(wp_strip_all_tags($content)); ?>”
                    </blockquote>
                <?php endif; ?>

                <?php if ($quote_author): ?>
                    <footer class="text-lg">
                        <cite class="font-semibold text-gray-900 not-italic">
                            <?php echo esc_html($quote_author); ?>
                        </cite>
                        <?php if ($quote_position): ?>
                            <p class="text-gray-600 mt-1">
                                <?php echo esc_html($quote_position); ?>
                            </p>
                        <?php endif; ?>
                    </footer>
                <?php endif; ?>
            </div>

        <?php elseif ($content_type === 'gallery' && $gallery_images): ?>
            <!-- Gallery -->
            <div class="max-w-6xl mx-auto text-center">
                <?php if ($title): ?>
                    <h2 class="text-3xl lg:text-4xl font-bold text-gray-900 mb-8">
                        <?php echo esc_html($title); ?>
                    </h2>
                <?php endif; ?>

                <div class="grid grid-cols-2 md:grid-cols-3 xl:grid-cols-5 gap-4">
                    <?php foreach ($gallery_images as $img): ?>
                        <div class="group cursor-pointer">
                            <?php
                            if (function_exists('resplast_optimized_image')) {
                                echo resplast_optimized_image($img['ID'], 'medium', [
                                    'class' => 'w-full aspect-[216/133.903] object-cover rounded-lg group-hover:opacity-90 transition-opacity',
                                    'alt'   => $img['alt'] ?: '',
                                    'lazy'  => true,
                                    'avif_support' => true
                                ]);
                            } else {
                                echo wp_get_attachment_image($img['ID'], 'medium', false, [
                                    'class' => 'w-full aspect-[216/133.903] object-cover rounded-lg group-hover:opacity-90 transition-opacity',
                                    'alt'   => $img['alt'] ?: ''
                                ]);
                            }
                            ?>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endif; ?>

    </div>
</section>