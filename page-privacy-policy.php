<?php

/**
 * Template Name: Privacy Policy
 */
get_header(); ?>
<div id="smooth-wrapper">

    <div id="smooth-content">
        <?php get_template_part('templates/partials/breadcrumbs'); ?>

        <main class="site-main flex flex-col gap-12 lg:gap-y-24 mb-12 lg:mb-24 relative">
            <?php render_blocks('privacy_panels'); ?>
        </main>
        <?php get_footer(); ?>

    </div>
</div>