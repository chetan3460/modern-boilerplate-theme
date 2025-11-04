<?php

/**
 * Template Name: News Listing
 * Template Post Type: page
 * Description: Displays the News CPT listing with filters, sorting, and AJAX View More.
 */

get_header(); ?>
<div id="smooth-wrapper">

  <div id="smooth-content">
    <?php get_template_part('templates/partials/breadcrumbs'); ?>

    <main class="site-main flex flex-col gap-12 lg:gap-y-24 mb-12 lg:mb-24 relative">
      <?php
      // Render the standalone listing section (no header/footer inside)
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