<?php

/**
 * Archive Template for News CPT
 * 
 * This template is used when visiting /news-updates/
 * Uses the same listing template as page-news-listing.php
 */

get_header(); ?>
<div id="smooth-wrapper">

  <div id="smooth-content">

    <?php get_template_part('templates/partials/breadcrumbs'); ?>
    <main class="site-main flex flex-col gap-12 lg:gap-y-10 mb-12 lg:mb-24 relative">
      <?php
      // Render the standalone news listing section
      $template = locate_template('templates/news/listing.php', false, false);
      if ($template) {
        include $template;
      } else {
        echo '<div class="container py-16 text-red-600">Template not found: templates/news/listing.php</div>';
      }
      ?>

    </main>
    <?php get_footer(); ?>
  </div>
</div>