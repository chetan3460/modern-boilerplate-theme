<?php

/**
 * Template Name: Initiatives Template
 */
get_header(); ?>

<div id="smooth-wrapper" class="">

    <div id="smooth-content" class="">
        <?php get_template_part('templates/partials/breadcrumbs'); ?>

        <main class="site-main flex flex-col gap-12 lg:gap-y-24 mb-12 lg:mb-24 relative">
            <?php render_blocks('initiatives_panels'); ?>

            <!-- Decorative Shape -->
            <div class="absolute right-0 bottom-0 md:bottom-[-131px] z-1 pointer-none w-[63.4px] md:w-auto">
                <img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/home/shapes/shape-7.webp" alt="">
            </div>
        </main>
        <?php get_footer(); ?>
    </div>
</div>