<?php

/**
 * Template Name: Products Listing
 * Template Post Type: page
 * Description: Displays the Products with advanced filtering system for Chemistry, Brand, and Applications.
 */

get_header(); ?>
<div id="smooth-wrapper">

  <div id="smooth-content">
    <?php get_template_part('templates/partials/breadcrumbs'); ?>

    <main class="site-main flex flex-col gap-12  mb-12 lg:mb-24 relative">
      <!-- Products Page Banner -->
      <?php
      $banner_template = locate_template('templates/products/banner.php', false, false);
      if ($banner_template) {
        include $banner_template;
      }
      ?>
      <?php
      // Render the standalone product listing section
      $template = locate_template('templates/products/listing.php', false, false);
      if ($template) {
        include $template;
      } else {
        echo '<div class="container py-16 text-red-600">Template not found: templates/products/listing.php</div>';
      }
      ?>
    </main>

    <?php get_footer(); ?>
  </div>
</div>