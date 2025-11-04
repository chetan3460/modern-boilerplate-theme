<?php

/**
 * Template Name: Vision Template
 */
get_header(); ?>

<div id="smooth-wrapper" class="">

    <div id="smooth-content" class="">
        <?php get_template_part('templates/partials/breadcrumbs'); ?>

        <main class="site-main flex flex-col gap-12 lg:gap-y-24 mb-12 lg:mb-24 relative">
            <?php render_blocks('vision_panels'); ?>

            <!-- Decorative Shape -->
            <!-- Decorative Shape -->
            <div class="absolute bottom-0 md:bottom-[-120px]  right-0 lg:right-[77px] z-1 pointer-none w-[63.4px] md:w-[176px] rotate-[-4deg] content-visibility-auto" data-speed="1.25">
                <img class="" src="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/home/shapes/shape-7.webp"
                    alt="Decorative shape"
                    loading="lazy"
                    decoding="async"
                    style="content-visibility: auto; contain-intrinsic-size: 100px 100px;">
            </div>
        </main>
        <?php get_footer(); ?>
    </div>
</div>