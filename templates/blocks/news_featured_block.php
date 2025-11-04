<?php
/**
 * Featured News Block Template
 * 
 * @package Resplast
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

// Get block fields
$hide_block = get_sub_field('hide_block');
if ($hide_block) {
    return;
}

$section_title = get_sub_field('section_title') ?: 'Featured News';
$featured_posts = get_sub_field('featured_posts');

// If no posts selected, get latest 3
if (!$featured_posts) {
    $featured_posts = get_posts([
        'post_type' => 'news',
        'posts_per_page' => 3,
        'post_status' => 'publish'
    ]);
}

// Generate unique ID for this block
$block_id = 'news-featured-' . uniqid();

// Exit if no posts
if (!$featured_posts || empty($featured_posts)) {
    return;
}
?>

<section id="<?php echo esc_attr($block_id); ?>" class="news-featured-block py-12 lg:py-20 bg-white">
    <div class="container mx-auto px-4">
        <!-- Section Header -->
        <div class="text-center mb-12">
            <h2 class="text-3xl lg:text-4xl font-bold text-gray-900 mb-4">
                <?php echo esc_html($section_title); ?>
            </h2>
            <div class="w-24 h-1 bg-gradient-to-r from-blue-500 to-red-500 mx-auto rounded-full"></div>
        </div>

        <!-- Featured Posts Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <?php foreach ($featured_posts as $index => $post): 
                setup_postdata($post);
                
                // Get post data
                $post_id = $post->ID;
                $title = get_the_title($post_id);
                $excerpt = get_the_excerpt($post_id) ?: wp_trim_words(get_the_content('', false, $post_id), 25);
                $permalink = get_permalink($post_id);
                $date = get_the_date('M j, Y', $post_id);
                $read_time = function_exists('calculate_post_read_time') ? calculate_post_read_time($post_id) : 1;
                
                // Get featured image
                $image_id = null;
                if (function_exists('get_field')) {
                    $acf_image = get_field('post_thumbnail', $post_id);
                    if ($acf_image) {
                        $image_id = is_array($acf_image) ? $acf_image['ID'] : $acf_image;
                    }
                }
                if (!$image_id && has_post_thumbnail($post_id)) {
                    $image_id = get_post_thumbnail_id($post_id);
                }
                
                // First post gets special treatment (larger)
                $is_main_post = ($index === 0);
                $card_classes = $is_main_post ? 'lg:col-span-2 lg:row-span-2' : '';
                $image_classes = $is_main_post ? 'h-80 lg:h-96' : 'h-64';
                $title_classes = $is_main_post ? 'text-2xl lg:text-3xl' : 'text-xl';
                ?>
                
                <article class="group bg-white rounded-2xl overflow-hidden shadow-sm hover:shadow-xl transition-all duration-300 <?php echo esc_attr($card_classes); ?>">
                    <!-- Featured Image -->
                    <a href="<?php echo esc_url($permalink); ?>" class="block relative <?php echo esc_attr($image_classes); ?> bg-gray-100 overflow-hidden">
                        <?php if ($image_id): ?>
                            <?php 
                            if (function_exists('resplast_optimized_image')) {
                                echo resplast_optimized_image($image_id, $is_main_post ? 'full' : 'large', [
                                    'class' => 'w-full h-full object-cover group-hover:scale-105 transition-transform duration-500',
                                    'alt' => $title,
                                    'lazy' => true,
                                    'avif_support' => true
                                ]);
                            } else {
                                echo wp_get_attachment_image($image_id, $is_main_post ? 'full' : 'large', false, [
                                    'class' => 'w-full h-full object-cover group-hover:scale-105 transition-transform duration-500',
                                    'alt' => $title,
                                    'loading' => 'lazy'
                                ]);
                            }
                            ?>
                        <?php else: ?>
                            <!-- Placeholder -->
                            <div class="w-full h-full bg-gradient-to-br from-gray-200 to-gray-300 flex items-center justify-center">
                                <svg class="w-16 h-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                            </div>
                        <?php endif; ?>
                        
                        <!-- Overlay -->
                        <div class="absolute inset-0 bg-gradient-to-t from-black/50 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                    </a>

                    <!-- Content -->
                    <div class="p-6 <?php echo $is_main_post ? 'lg:p-8' : ''; ?>">
                        <!-- Meta -->
                        <div class="flex items-center gap-4 text-sm text-gray-500 mb-3">
                            <time datetime="<?php echo esc_attr(get_the_date('c', $post_id)); ?>">
                                <?php echo esc_html($date); ?>
                            </time>
                            <span class="w-1 h-1 bg-gray-400 rounded-full"></span>
                            <span><?php echo esc_html($read_time); ?> min read</span>
                        </div>

                        <!-- Title -->
                        <h3 class="<?php echo esc_attr($title_classes); ?> font-bold text-gray-900 mb-3 group-hover:text-blue-600 transition-colors">
                            <a href="<?php echo esc_url($permalink); ?>" class="block">
                                <?php echo esc_html($title); ?>
                            </a>
                        </h3>

                        <!-- Excerpt -->
                        <?php if ($excerpt): ?>
                            <p class="text-gray-600 <?php echo $is_main_post ? 'text-base lg:text-lg' : 'text-sm'; ?> mb-4 line-clamp-3">
                                <?php echo esc_html(wp_strip_all_tags($excerpt)); ?>
                            </p>
                        <?php endif; ?>

                        <!-- Read More -->
                        <a href="<?php echo esc_url($permalink); ?>" 
                           class="inline-flex items-center text-blue-600 hover:text-blue-700 font-semibold text-sm group-hover:gap-2 transition-all">
                            Read More
                            <svg class="w-4 h-4 ml-1 group-hover:ml-0 transition-all" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                            </svg>
                        </a>
                    </div>
                </article>
                
            <?php endforeach; 
            wp_reset_postdata(); ?>
        </div>

        <!-- View All Link -->
        <div class="text-center mt-12">
            <a href="<?php echo home_url('/news-updates/'); ?>"
               class="inline-flex items-center gap-2 px-8 py-4 bg-gray-100 hover:bg-gray-200 text-gray-900 font-semibold rounded-xl transition-all hover:scale-105">
                View All News
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                </svg>
            </a>
        </div>
    </div>
</section>