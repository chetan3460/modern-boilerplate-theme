<?php

/**
 * Template Name: Home
 */
get_header(); ?>

<div id="smooth-wrapper" class="h-full overflow-hidden">

    <div id="smooth-content" class="will-change-transform">

        <main class="site-main flex flex-col gap-12 lg:gap-y-24 mb-12 lg:mb-24 relative">
            <?php render_blocks('home_panels'); ?>

            <!-- Decorative Shape -->
            <div class="absolute right-0 bottom-0 md:bottom-[-131px] z-1 pointer-none w-[63.4px] md:w-auto">
                <img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/home/shapes/shape-7.webp" alt="">
            </div>
        </main>
        <?php get_footer(); ?>
    </div>
</div>