<?php
$sub_title = get_sub_field('sub_title');
$heading   = get_sub_field('heading');
$select_news = get_sub_field('select_news');

$cta = get_sub_field('cta');
if (!empty($cta)) {
    $cta_target = $cta['target'] ? $cta['target'] : '_self';
}

// Use manually selected news if available, otherwise get latest news
if (!empty($select_news)) {
    // Use manually selected news posts
    $news_posts = $select_news;
    $news_count = count($news_posts);
    $news_query = null; // No WP_Query needed for manual selection
} else {
    // Fallback to latest news query
    $args = [
        'post_type'      => 'news',
        'posts_per_page' => 3,
        'orderby'        => 'date',
        'order'          => 'DESC',
    ];
    $news_query = new WP_Query($args);
    $news_posts = $news_query->posts;
    $news_count = $news_query->found_posts;
}

// Calculate news count and determine if slider should be used
$use_slider = $news_count >= 4; // Use slider if 4 or more items
$block_id   = 'news-block-' . uniqid();

// Hiding and cosmetics
include locate_template('templates/blocks/hide_block.php', false, false);
?>

<?php if (!$hide_block && $news_count > 0): ?>
    <section class="news-list-block fade-in as" data-component="NewsSlider" data-load="eager">
        <div class="container-fluid relative overflow-hidden">

            <!-- Section Heading -->
            <?php if ($sub_title || $heading): ?>
                <div class="section-heading text-center mb-4 sm:mb-8">
                    <?php if ($heading): ?>
                        <h2 class="mb-1 fade-text"><?= esc_html($heading); ?></h2>
                    <?php endif; ?>
                    <?php if ($sub_title): ?>
                        <div class="anim-uni-in-up">
                            <p><?= esc_html($sub_title); ?></p>

                        </div>
                    <?php endif; ?>
                </div>
            <?php endif; ?>

            <!-- News Slider Container -->
            <div class="news-slider-container"
                data-slider-enabled="<?= $use_slider ? 'true' : 'false'; ?>"
                data-news-count="<?= esc_attr($news_count); ?>">

                <!-- Swiper Slider -->
                <div class="news-slider swiper">
                    <div class="swiper-wrapper fade-up-stagger-wrap gsap-no-scroll">
                        <?php
                        $delay = 0; // Initialize delay counter
                        if ($news_query) {
                            // Using WP_Query - standard loop
                            while ($news_query->have_posts()): $news_query->the_post();
                                $GLOBALS['news_card_delay'] = $delay; // Pass via globals
                        ?>
                                <div class="swiper-slide">
                                    <?php get_template_part('templates/parts/news_card_new'); ?>
                                </div>
                            <?php $delay += 0.2; // Increment delay for each item
                            endwhile;
                            wp_reset_postdata();
                        } else {
                            // Using manually selected posts
                            foreach ($news_posts as $post) {
                                setup_postdata($post);
                                $GLOBALS['news_card_delay'] = $delay; // Pass via globals
                            ?>
                                <div class="swiper-slide">
                                    <?php get_template_part('templates/parts/news_card_new'); ?>
                                </div>
                        <?php $delay += 0.2; // Increment delay for each item
                            }
                            wp_reset_postdata();
                        }
                        ?>
                    </div>
                </div>

                <!-- Slider Navigation & Pagination -->
                <?php if ($news_count >= 4): ?>
                    <div class="mt-3 lg:hidden flex justify-center items-center gap-3 mb-6">
                    <?php else: ?>
                        <div class="mt-3 flex lg:hidden justify-center items-center gap-4">
                        <?php endif; ?>

                        <div class="swiper-btn-prev-pagination swiper-btn-prev">
                            <svg xmlns="http://www.w3.org/2000/svg" width="9" height="7" viewBox="0 0 9 7" fill="none">
                                <path d="M7.92214 3.18291C8.16739 3.18291 8.36621 3.38173 8.36621 3.62699C8.36621 3.87224 8.16739 4.07106 7.92214 4.07106L1.66704 4.07106L3.79543 6.19944C3.96885 6.37286 3.96885 6.65403 3.79543 6.82745C3.62201 7.00087 3.34084 7.00087 3.16742 6.82745L0.594961 4.255C0.24812 3.90816 0.24812 3.34581 0.594961 2.99897L3.16742 0.426516C3.34084 0.253095 3.62201 0.253096 3.79543 0.426516C3.96885 0.599937 3.96885 0.881107 3.79543 1.05453L1.66705 3.18291L7.92214 3.18291Z" fill="#DA000E" />
                            </svg>
                        </div>

                        <div class="swiper-pagination-custom text-primary text-xs font-medium !w-4 !h-4"></div>

                        <div class="swiper-btn-next-pagination swiper-btn-next">
                            <svg xmlns="http://www.w3.org/2000/svg" width="9" height="7" viewBox="0 0 9 7" fill="none">
                                <path d="M1.15891 3.18291C0.913661 3.18291 0.714844 3.38173 0.714844 3.62699C0.714844 3.87224 0.913661 4.07106 1.15892 4.07106L7.41401 4.07106L5.28562 6.19944C5.1122 6.37286 5.1122 6.65403 5.28562 6.82745C5.45904 7.00087 5.74021 7.00087 5.91364 6.82745L8.48609 4.255C8.83293 3.90816 8.83294 3.34581 8.48609 2.99897L5.91363 0.426516C5.74021 0.253095 5.45904 0.253096 5.28562 0.426516C5.1122 0.599937 5.1122 0.881107 5.28562 1.05453L7.41401 3.18291L1.15891 3.18291Z" fill="#DA000E" />
                            </svg>
                        </div>
                        </div>

                        <!-- CTA Button -->
                        <?php if ($cta): ?>
                            <div class="text-center mt-4 anim-uni-in-up">
                                <a href="<?= esc_url($cta['url']); ?>"
                                    target="<?= esc_attr($cta_target); ?>"
                                    class="btn">
                                    <?= esc_html($cta['title']); ?>
                                </a>
                            </div>
                        <?php endif; ?>

                        <!-- Shape Image -->
                        <div class="md:hidden block absolute left-0 bottom-0 -z-1 pointer-none" data-speed="1.25">
                            <img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/home/shapes/shape-6-mobile.webp" alt="">
                        </div>

                    </div> <!-- end news-slider-container -->
            </div> <!-- end container-fluid -->
    </section>
<?php endif; ?>