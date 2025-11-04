<?php

/**
 * Template Name: News Page
 * 
 * News page template with flexible content blocks
 * 
 * @package Resplast
 */

get_header(); ?>

<div id="smooth-wrapper" class="">
    <div id="smooth-content" class="">

        <?php get_template_part('templates/partials/breadcrumbs'); ?>

        <main class="site-main flex flex-col gap-12 lg:gap-y-24 mb-12 lg:mb-24 relative">
            <?php

            render_blocks('news_panels');
            ?>
        </main>
        <?php get_footer(); ?>

    </div>
</div>