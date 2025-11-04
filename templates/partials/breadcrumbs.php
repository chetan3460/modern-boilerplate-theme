<?php

/**
 * Breadcrumbs partial
 * - Uses Yoast / Rank Math / Breadcrumb NavXT if available
 * - Falls back to a lightweight custom breadcrumb for Pages and Posts
 */

if (is_front_page()) {
  return;
}
?>

<section class="breadcrumbs pt-[83px]  pb-5 lg:pb-7 fade-in">
  <nav class="container-fluid" aria-label="Breadcrumb">
    <?php if (is_singular('news')): ?>
      <?php
      // Custom breadcrumbs for news posts - HIGH PRIORITY
      $breadcrumbs = [];
      $breadcrumbs[] = [
        'url'   => home_url('/'),
        'label' => __('Home'),
        'link'  => true,
      ];

      // Link back to custom News Updates page
      $breadcrumbs[] = [
        'url'   => home_url('/news-updates/'),
        'label' => __('News & Updates'),
        'link'  => true,
      ];

      // Current news post (not linked)
      $breadcrumbs[] = [
        'url'   => '',
        'label' => get_the_title(),
        'link'  => false,
      ];
      ?>
      <ol class="flex flex-wrap items-center gap-2 text-sm font-medium tracking-[0.14px]">
        <?php foreach ($breadcrumbs as $index => $crumb): ?>
          <?php if ($index > 0): ?>
            <li aria-hidden="true" class="">
              <svg xmlns="http://www.w3.org/2000/svg" width="6" height="6" viewBox="0 0 6 6" fill="none" class="inline-block align-middle">
                <circle cx="2.5957" cy="3" r="2.5957" fill="#FFB6B0" />
              </svg>
            </li>
          <?php endif; ?>
          <li>
            <?php if (!empty($crumb['link'])): ?>
              <a href="<?php echo esc_url($crumb['url']); ?>" class="text-grey-1 hover:text-primary transition-colors">
                <?php echo esc_html($crumb['label']); ?>
              </a>
            <?php else: ?>
              <span class="text-primary">
                <?php echo esc_html($crumb['label']); ?>
              </span>
            <?php endif; ?>
          </li>
        <?php endforeach; ?>
      </ol>
    <?php elseif (is_page()): ?>
      <?php
      $breadcrumbs = [];
      $home_label = __('Home');
      $breadcrumbs[] = [
        'url'   => home_url('/'),
        'label' => $home_label,
        'link'  => true,
      ];

      $post_id = get_the_ID();
      $ancestors = array_reverse(get_post_ancestors($post_id));

      // Detect the "Our Company" page by slug if possible, else by title match
      $our_company_page = get_page_by_path('our-company');
      $our_company_id = $our_company_page ? (int) $our_company_page->ID : 0;

      foreach ($ancestors as $ancestor_id) {
        $title = get_the_title($ancestor_id);
        $is_our_company = false;
        if ($our_company_id && $ancestor_id === $our_company_id) {
          $is_our_company = true;
        } else {
          $is_our_company = (strcasecmp(trim($title), 'Our Company') === 0);
        }
        // Skip "Our Company" in breadcrumbs for descendant pages
        if ($is_our_company) {
          continue;
        }
        $breadcrumbs[] = [
          'url'   => get_permalink($ancestor_id),
          'label' => $title,
          'link'  => true,
        ];
      }

      // Current page (not linked)
      $breadcrumbs[] = [
        'url'   => '',
        'label' => get_the_title($post_id),
        'link'  => false,
      ];
      ?>
      <ol class="flex flex-wrap items-center gap-2 text-sm font-medium tracking-[0.14px]">
        <?php foreach ($breadcrumbs as $index => $crumb): ?>
          <?php if ($index > 0): ?>
            <li aria-hidden="true" class="">
              <svg xmlns="http://www.w3.org/2000/svg" width="6" height="6" viewBox="0 0 6 6" fill="none" class="inline-block align-middle">
                <circle cx="2.5957" cy="3" r="2.5957" fill="#FFB6B0" />
              </svg>
            </li>
          <?php endif; ?>
          <li>
            <?php if (!empty($crumb['link'])): ?>
              <a href="<?php echo esc_url($crumb['url']); ?>" class="text-grey-1 hover:text-primary transition-colors">
                <?php echo esc_html($crumb['label']); ?>
              </a>
            <?php else: ?>
              <span class="text-primary">
                <?php echo esc_html($crumb['label']); ?>
              </span>
            <?php endif; ?>
          </li>
        <?php endforeach; ?>
      </ol>
    <?php elseif (function_exists('yoast_breadcrumb')): ?>
      <div class="text-sm font-medium tracking-[0.14px]">&nbsp;<?php yoast_breadcrumb('', ''); ?></div>
    <?php elseif (function_exists('rank_math_the_breadcrumbs')): ?>
      <div class="text-sm"><?php rank_math_the_breadcrumbs(); ?></div>
    <?php elseif (function_exists('bcn_display')): ?>
      <div class="text-sm" typeof="BreadcrumbList" vocab="https://schema.org/">
        <?php bcn_display(); ?>
      </div>
    <?php else: ?>
      <?php
      // Generic fallback for non-Page contexts
      $breadcrumbs = [];
      $breadcrumbs[] = [
        'url'   => home_url('/'),
        'label' => __('Home'),
        'link'  => true,
      ];
      $breadcrumbs[] = [
        'url'   => '',
        'label' => wp_get_document_title(),
        'link'  => false,
      ];
      ?>
      <ol class="flex flex-wrap items-center gap-2 text-sm font-medium tracking-[0.14px]">
        <?php foreach ($breadcrumbs as $index => $crumb): ?>
          <?php if ($index > 0): ?>
            <li aria-hidden="true" class="mx-1">
              <svg xmlns="http://www.w3.org/2000/svg" width="6" height="6" viewBox="0 0 6 6" fill="none" class="inline-block align-middle">
                <circle cx="2.5957" cy="3" r="2.5957" fill="#FFB6B0" />
              </svg>
            </li>
          <?php endif; ?>
          <li>
            <?php if (!empty($crumb['link'])): ?>
              <a href="<?php echo esc_url($crumb['url']); ?>" class="text-grey-1 hover:text-primary transition-colors">
                <?php echo esc_html($crumb['label']); ?>
              </a>
            <?php else: ?>
              <span class="text-gray-900">
                <?php echo esc_html($crumb['label']); ?>
              </span>
            <?php endif; ?>
          </li>
        <?php endforeach; ?>
      </ol>
    <?php endif; ?>
  </nav>
</section>