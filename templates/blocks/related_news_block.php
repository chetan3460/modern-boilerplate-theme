<?php
/**
 * Related News Block Template
 * 
 * Shows related news posts based on categories or tags
 * Perfect for single news post pages
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

$section_title = get_sub_field('section_title') ?: 'Related Articles';
$posts_to_show = get_sub_field('posts_to_show') ?: 3;
$show_category = get_sub_field('show_category');
$show_date = get_sub_field('show_date');

// Get current post categories for related posts
global $post;
$current_post_id = $post->ID;
$current_categories = get_the_terms($current_post_id, 'news_category');
$category_ids = array();

if ($current_categories && !is_wp_error($current_categories)) {
    foreach ($current_categories as $cat) {
        $category_ids[] = $cat->term_id;
    }
}

// Query for related posts
$related_posts = new WP_Query([
    'post_type' => 'news',
    'posts_per_page' => $posts_to_show,
    'post__not_in' => [$current_post_id],
    'tax_query' => [
        [
            'taxonomy' => 'news_category',
            'field' => 'term_id',
            'terms' => $category_ids,
            'operator' => 'IN'
        ]
    ],
    'orderby' => 'date',
    'order' => 'DESC'
]);

// Fallback: if no related posts, get latest news
if (!$related_posts->have_posts()) {
    wp_reset_postdata();
    $related_posts = new WP_Query([
        'post_type' => 'news',
        'posts_per_page' => $posts_to_show,
        'post__not_in' => [$current_post_id],
        'orderby' => 'date',
        'order' => 'DESC'
    ]);
}

// Exit if still no posts
if (!$related_posts->have_posts()) {
    return;
}

// Generate unique ID for this block
$block_id = 'related-news-' . uniqid();
?>

<section id="<?php echo esc_attr($block_id); ?>" class="related-news-block py-12 lg:py-20 bg-gray-50">
    <div class="container mx-auto px-4">
        <!-- Section Header -->
        <div class="text-center mb-12">
            <h2 class="text-3xl lg:text-4xl font-bold text-gray-900 mb-4">
                <?php echo esc_html($section_title); ?>
            </h2>
            <div class="w-24 h-1 bg-gradient-to-r from-blue-500 to-red-500 mx-auto rounded-full"></div>
        </div>

        <!-- Related Posts Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-<?php echo min(3, $posts_to_show); ?> gap-8">
            <?php while ($related_posts->have_posts()): 
                $related_posts->the_post();
                
                $post_id = get_the_ID();
                $title = get_the_title();
                $excerpt = get_the_excerpt() ?: wp_trim_words(get_the_content('', false, $post_id), 20);
                $permalink = get_permalink();
                $date = get_the_date('M j, Y');
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
                
                // Get categories if enabled
                $post_categories = [];
                if ($show_category) {
                    $post_categories = get_the_terms($post_id, 'news_category');
                }
                ?>
                
                <article class="group bg-white rounded-2xl overflow-hidden shadow-sm hover:shadow-xl transition-all duration-300 hover:-translate-y-1">
                    <!-- Featured Image -->
                    <a href="<?php echo esc_url($permalink); ?>" class="block relative h-48 bg-gray-100 overflow-hidden">
                        <?php if ($image_id): ?>
                            <?php 
                            if (function_exists('resplast_optimized_image')) {
                                echo resplast_optimized_image($image_id, 'medium', [
                                    'class' => 'w-full h-full object-cover group-hover:scale-105 transition-transform duration-500',
                                    'alt' => $title,
                                    'lazy' => true,
                                    'avif_support' => true
                                ]);
                            } else {
                                echo wp_get_attachment_image($image_id, 'medium', false, [
                                    'class' => 'w-full h-full object-cover group-hover:scale-105 transition-transform duration-500',
                                    'alt' => $title,
                                    'loading' => 'lazy'
                                ]);
                            }
                            ?>
                        <?php else: ?>
                            <!-- Placeholder -->
                            <div class="w-full h-full bg-gradient-to-br from-gray-200 to-gray-300 flex items-center justify-center">
                                <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9.5a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"></path>
                                </svg>
                            </div>
                        <?php endif; ?>
                        
                        <!-- Category Badge -->
                        <?php if ($show_category && $post_categories && !is_wp_error($post_categories)): ?>
                            <div class="absolute top-4 left-4">
                                <span class="px-3 py-1 bg-blue-600 text-white text-xs font-semibold rounded-full">
                                    <?php echo esc_html($post_categories[0]->name); ?>
                                </span>
                            </div>
                        <?php endif; ?>
                    </a>

                    <!-- Content -->
                    <div class="p-6">
                        <!-- Meta -->
                        <?php if ($show_date): ?>
                            <div class="flex items-center gap-4 text-sm text-gray-500 mb-3">
                                <time datetime="<?php echo esc_attr(get_the_date('c', $post_id)); ?>">
                                    <?php echo esc_html($date); ?>
                                </time>
                                <span class="w-1 h-1 bg-gray-400 rounded-full"></span>
                                <span><?php echo esc_html($read_time); ?> min read</span>
                            </div>
                        <?php endif; ?>

                        <!-- Title -->
                        <h3 class="text-xl font-bold text-gray-900 mb-3 group-hover:text-blue-600 transition-colors">
                            <a href="<?php echo esc_url($permalink); ?>" class="block">
                                <?php echo esc_html($title); ?>
                            </a>
                        </h3>

                        <!-- Excerpt -->
                        <?php if ($excerpt): ?>
                            <p class="text-gray-600 text-sm mb-4 line-clamp-3">
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
                
            <?php endwhile; 
            wp_reset_postdata(); ?>
        </div>
    </div>
</section>