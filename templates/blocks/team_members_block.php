<?php

/**
 * Team Members Block Template - Leadership Page Style
 * Matches "Expertise born from legacy" design
 * 
 * Updated: 2025-09-28 15:37 - SimpleTeamTabs implementation
 *
 * ACF Fields:
 * - hide_block (true_false)
 * - title (text)
 * - description (wysiwyg)
 * - show_categories (true_false)
 * - selected_categories (taxonomy)
 */

$title = get_sub_field('title') ?: 'Expertise born from legacy';
$description = get_sub_field('description') ?: '';
$show_categories = get_sub_field('show_categories') ?: true;
$selected_categories = get_sub_field('selected_categories') ?: [];

// Include hide block functionality
include locate_template('templates/blocks/hide_block.php', false, false);

if (!$hide_block): ?>
  <section class="team_members_block fade-in" data-component="SimpleTeamTabs" data-load="eager">
    <div class="container-fluid ">

      <!-- Header Section -->
      <div class="section-heading text-center mb-6 md:mb-8">
        <?php if ($title): ?>
          <h2 class="fade-text">
            <?php echo esc_html($title); ?>
          </h2>
        <?php endif; ?>

        <?php if ($description): ?>
          <div class="anim-uni-in-up">
            <?php echo wp_kses_post($description); ?>
          </div>
        <?php endif; ?>
      </div>

      <?php
      // Get all team categories
      $categories_to_show = [];
      if (!empty($selected_categories)) {
        $categories_to_show = $selected_categories;
      } else {
        // Get all categories if none selected
        $categories_to_show = get_terms([
          'taxonomy' => 'team_category',
          'hide_empty' => true,
        ]);
      }


      if (!empty($categories_to_show)):
      ?>

        <!-- Category Tab Buttons - Sliding Toggle Style -->
        <?php if ($show_categories && count($categories_to_show) > 1): ?>
          <div class="team-tabs flex justify-center mb-6 lg:mb-8 ">
            <div class="relative inline-flex border-primary border rounded-full w-full max-w-[410px] mx-auto">
              <!-- Active state background that slides -->
              <div class="team-tab-slider absolute top-1 bottom-1 bg-primary rounded-full transition-all duration-300 ease-out"
                style="width: calc(50% - 10px); left: 4px;"></div>

              <?php $first = true; ?>
              <?php foreach ($categories_to_show as $index => $category): ?>
                <?php $cat_obj = is_object($category) ? $category : get_term($category); ?>
                <?php
                // Get member count for this category
                $count_query = new WP_Query([
                  'post_type' => 'team_member',
                  'posts_per_page' => -1,
                  'post_status' => 'publish',
                  'tax_query' => [[
                    'taxonomy' => 'team_category',
                    'field' => 'term_id',
                    'terms' => $cat_obj->term_id,
                  ]],
                  'fields' => 'ids'
                ]);
                $actual_count = $count_query->found_posts;
                wp_reset_postdata();
                ?>
                <div
                  class="team-slide-tab relative z-10 text-sm md:text-base font-normal md:px-4 py-3 px-2 rounded-full transition-all duration-300 cursor-pointer flex-1 text-center <?php echo $first ? 'text-white active ' : 'text-primary'; ?>"
                  data-filter="<?php echo esc_attr($cat_obj->slug); ?>"
                  data-index="<?php echo $index; ?>">
                  <?php echo esc_html($cat_obj->name); ?>
                </div>
                <?php $first = false; ?>
              <?php endforeach; ?>
            </div>
          </div>
        <?php endif; ?>

        <!-- Team Members Layout -->
        <div class="team-members-wrapper animate-card-3">
          <?php if ($categories_to_show): ?>
            <?php foreach ($categories_to_show as $index => $category): ?>
              <?php $cat_obj = is_object($category) ? $category : get_term($category); ?>
              <?php
              // Get members for this category
              $team_query = new WP_Query(array(
                'post_type'      => 'team_member',
                'posts_per_page' => -1,
                'tax_query'      => array(
                  array(
                    'taxonomy' => 'team_category',
                    'field'    => 'slug',
                    'terms'    => $cat_obj->slug,
                  ),
                ),
                'orderby' => 'menu_order',
                'order' => 'ASC'
              ));
              $category_members = [];
              if ($team_query->have_posts()) {
                while ($team_query->have_posts()) {
                  $team_query->the_post();
                  $category_members[] = [
                    'id' => get_the_ID(),
                    'title' => get_the_title(),
                    'thumbnail' => get_the_post_thumbnail(get_the_ID(), 'medium_large'),
                    'has_thumbnail' => has_post_thumbnail(),
                    'position' => get_field('position') ?: get_field('job_title') ?: '',
                    'categories' => [$cat_obj->slug]
                  ];
                }
                wp_reset_postdata();
              }
              ?>

              <div class="team-category-wrapper <?php echo $index === 0 ? 'active' : ''; ?>"
                data-category="<?php echo esc_attr($cat_obj->slug); ?>"
                style="display: <?php echo $index === 0 ? 'block' : 'none'; ?>;">

                <!-- SWIPER LAYOUT: Works for both Desktop and Mobile -->
                <div class="team-mobile-layout max-sm:max-w-[380px]">
                  <div class="swiper max-md:!overflow-visible" data-category="<?php echo esc_attr($cat_obj->slug); ?>">
                    <div class="swiper-wrapper">
                      <?php if (!empty($category_members)): ?>
                        <?php foreach ($category_members as $member): ?>
                          <div class="swiper-slide ">
                            <?php include locate_template('templates/components/team-card.php'); ?>
                          </div>
                        <?php endforeach; ?>
                      <?php else: ?>
                        <div class="swiper-slide">
                          <div class="text-center py-12">
                            <p class="text-gray-500 text-lg">No team members found in this category.</p>
                          </div>
                        </div>
                      <?php endif; ?>
                    </div>






                  </div>
                </div>

                <!-- Slider Navigation & Pagination -->
                <div class="mt-3 flex lg:hidden justify-center items-center gap-4 mb-6">
                  <div class="swiper-btn-prev-pagination swiper-btn-prev">
                    <svg xmlns="http://www.w3.org/2000/svg" width="9" height="7" viewBox="0 0 9 7" fill="none">
                      <path d="M7.92 3.18c.24 0 .44.2.44.45s-.2.44-.44.44H1.67l2.13 2.13a.44.44 0 01-.63.63L.59 4.26a.9.9 0 010-1.26l2.58-2.57a.44.44 0 01.63.63L1.67 3.18h6.25z" fill="#DA000E" />
                    </svg>
                  </div>

                  <div class="swiper-pagination-custom text-primary text-xs font-medium !w-4 !h-4"></div>

                  <div class="swiper-btn-next-pagination swiper-btn-next">
                    <svg xmlns="http://www.w3.org/2000/svg" width="9" height="7" viewBox="0 0 9 7" fill="none">
                      <path d="M1.16 3.18a.44.44 0 000 .89h6.25L5.29 6.2a.44.44 0 10.63.63l2.58-2.58a.9.9 0 000-1.26L5.92.43a.44.44 0 10-.63.63l2.13 2.13H1.16z" fill="#DA000E" />
                    </svg>
                  </div>
                </div>
              </div>
            <?php endforeach; ?>
          <?php endif; ?>
        </div>
      <?php endif; ?>

      <?php
      // CSS is imported in main stylesheet (assets/css/style.css)
      // JavaScript is loaded dynamically via data-component="TeamMembersBlock"
      ?>

    </div>
  </section>
<?php endif; ?>