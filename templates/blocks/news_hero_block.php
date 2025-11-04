<?php

/**
 * News Hero Block Template
 *
 * Displays a full-width hero section for the News page.
 * Supports background image, title, subtitle, and description.
 *
 * @package Resplast
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

// Get ACF fields
$hide_block = get_sub_field('hide_block');
if ($hide_block) return;

$title             = get_sub_field('title') ?: 'Latest News';
$subtitle          = get_sub_field('subtitle');
$description       = get_sub_field('description');
$background_image  = get_sub_field('background_image');

// Generate a unique block ID
$block_id = 'news-hero-' . uniqid();
?>

<section
    id="<?php echo esc_attr($block_id); ?>"
    class="relative overflow-hidden text-white bg-gradient-to-r from-blue-600 to-blue-800">
    <?php if ($background_image): ?>
        <!-- Background Image -->
        <div class="absolute inset-0 z-0">
            <?php
            if (function_exists('resplast_optimized_image')) {
                echo resplast_optimized_image($background_image['ID'], 'full', [
                    'class'    => 'w-full h-full object-cover opacity-30',
                    'alt'      => $background_image['alt'] ?: esc_attr($title),
                    'priority' => true,
                    'lazy'     => false,
                ]);
            } else {
                echo wp_get_attachment_image($background_image['ID'], 'full', false, [
                    'class' => 'w-full h-full object-cover opacity-30',
                    'alt'   => $background_image['alt'] ?: esc_attr($title),
                ]);
            }
            ?>
        </div>

        <!-- Overlay -->
        <div class="absolute inset-0 bg-gradient-to-r from-black/60 to-black/40 z-1"></div>
    <?php endif; ?>

    <!-- Hero Content -->
    <div class="relative z-10 container mx-auto px-4 py-16 lg:py-24">
        <div class="max-w-4xl mx-auto text-center">

            <?php if ($subtitle): ?>
                <p class="mb-4 text-lg lg:text-xl font-medium text-blue-200">
                    <?php echo esc_html($subtitle); ?>
                </p>
            <?php endif; ?>

            <?php if ($title): ?>
                <h1 class="text-4xl lg:text-6xl font-bold leading-tight mb-6">
                    <?php echo esc_html($title); ?>
                </h1>
            <?php endif; ?>

            <?php if ($description): ?>
                <div class="max-w-2xl mx-auto text-lg lg:text-xl text-gray-200 prose prose-lg prose-invert">
                    <?php echo wp_kses_post($description); ?>
                </div>
            <?php endif; ?>

        </div>
    </div>

    <!-- Decorative Glow Elements -->
    <div class="absolute top-10 left-10 w-20 h-20 bg-blue-400/20 rounded-full blur-2xl"></div>
    <div class="absolute bottom-10 right-10 w-32 h-32 bg-blue-300/20 rounded-full blur-3xl"></div>
</section>