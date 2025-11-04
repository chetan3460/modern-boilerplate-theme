<?php

/**
 * Global Features Block Template (Enhanced)
 * 
 * Features:
 * - Auto-increment data-delay for animations
 * - Clean ACF logic
 * - Consistent fade-up-stagger usage
 */

$title = get_sub_field('title') ?: '';
$description = get_sub_field('description') ?: '';
$feature_items = get_sub_field('feature_items') ?: [];

// Include hide block functionality
include locate_template('templates/blocks/hide_block.php', false, false);

if (!$hide_block && ($title || $description || $feature_items)): ?>
  <section class="global_features_block relative fade-in">
    <div class="container-fluid">

      <!-- Title + Description -->
      <div class="text-center flex gap-2 flex-col items-center justify-center mb-6 lg:mb-8">
        <?php if ($title): ?>
          <h2 class="fade-text"><?php echo esc_html($title); ?></h2>
        <?php endif; ?>

        <?php if ($description): ?>
          <div class="description-content w-full lg:w-8/12 mx-auto prose max-w-none anim-uni-in-up">
            <?php echo wp_kses_post($description); ?>
          </div>
        <?php endif; ?>
      </div>

      <?php if ($feature_items): ?>
        <div class="feature_items-container">
          <?php
          $total_items = count($feature_items);
          $delay_base = 0.3;
          $delay_step = 0.2;
          $index = 0;

          // Layout for 1-3 items
          if ($total_items <= 3): ?>
            <div class="feature_items-grid grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3 lg:gap-6 justify-center items-center fade-up-stagger-wrap">
              <?php foreach ($feature_items as $item):
                $delay = $delay_base + ($index * $delay_step);
              ?>
                <div class="feature_items-item flex flex-col items-stretch fade-up-stagger rounded-[20px] lg:rounded-[40px] bg-sky-50 pt-7 pl-7 pr-10 pb-15 relative overflow-hidden bottom-right"
                  data-delay="<?php echo number_format($delay, 1); ?>">
                  <?php if (!empty($item['icon'])): ?>
                    <div class="size-14 rounded-full bg-white flex items-center justify-center mb-2 md:mb-4 p-3.5">
                      <img class="w-[33px] h-[33px]" src="<?php echo esc_url($item['icon']['url']); ?>" alt="<?php echo esc_attr($item['icon']['alt']); ?>">
                    </div>
                  <?php endif; ?>
                  <?php if (!empty($item['title'])): ?>
                    <div class="title h3 font-semibold text-grey-1 tracking-[-0.48px] mb-1 md:mb-2"><?php echo esc_html($item['title']); ?></div>
                  <?php endif; ?>
                  <?php if (!empty($item['content'])): ?>
                    <div class="content text-sm lg:text-base"><?php echo esc_html($item['content']); ?></div>
                  <?php endif; ?>
                </div>
              <?php
                $index++;
              endforeach; ?>
            </div>

          <?php else:
            // 4+ items: 3 on top, rest below centered
            $first_three = array_slice($feature_items, 0, 3);
            $remaining_items = array_slice($feature_items, 3);
          ?>
            <!-- First row: 3 items -->
            <div class="feature_items-grid grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3 lg:gap-6 justify-center items-center mb-3 lg:mb-6 fade-up-stagger-wrap">
              <?php foreach ($first_three as $item):
                $delay = $delay_base + ($index * $delay_step);
              ?>
                <div class="feature_items-item flex flex-col items-stretch fade-up-stagger rounded-[20px] lg:rounded-[40px] bg-sky-50 max-md:p-8 pt-7 pl-7 md:pr-10 md:pb-15 relative overflow-hidden bottom-right"
                  data-delay="<?php echo number_format($delay, 1); ?>">
                  <?php if (!empty($item['icon'])): ?>
                    <div class="size-14 rounded-full bg-white flex items-center justify-center mb-2 md:mb-4 p-3.5">
                      <img class="w-[33px] h-[33px]" src="<?php echo esc_url($item['icon']['url']); ?>" alt="<?php echo esc_attr($item['icon']['alt']); ?>">
                    </div>
                  <?php endif; ?>
                  <?php if (!empty($item['title'])): ?>
                    <div class="title h3 font-semibold text-grey-1 tracking-[-0.48px] mb-1 md:mb-2"><?php echo esc_html($item['title']); ?></div>
                  <?php endif; ?>
                  <?php if (!empty($item['content'])): ?>
                    <div class="content text-sm lg:text-base"><?php echo esc_html($item['content']); ?></div>
                  <?php endif; ?>
                </div>
              <?php
                $index++;
              endforeach; ?>
            </div>

            <!-- Second row: remaining items -->
            <?php if (!empty($remaining_items)):
              $remaining_count = count($remaining_items);
              $grid_class = $remaining_count == 1 ? 'grid-cols-1 max-w-md' : 'grid-cols-1 sm:grid-cols-2 lg:grid-cols-2 max-w-[819px]';
            ?>
              <div class="feature_items-grid-bottom grid <?php echo esc_attr($grid_class); ?> gap-3 lg:gap-6 justify-center items-center mx-auto fade-up-stagger-wrap">
                <?php foreach ($remaining_items as $item):
                  $delay = $delay_base + ($index * $delay_step);
                ?>
                  <div class="feature_items-item flex flex-col items-stretch fade-up-stagger rounded-[20px] lg:rounded-[40px] bg-sky-50 max-md:p-8 pt-7 pl-7 md:pr-10 md:pb-15 relative overflow-hidden bottom-right"
                    data-delay="<?php echo number_format($delay, 1); ?>">
                    <?php if (!empty($item['icon'])): ?>
                      <div class="size-14 rounded-full bg-white flex items-center justify-center mb-2 md:mb-4 p-3.5">
                        <img class="w-[33px] h-[33px]" src="<?php echo esc_url($item['icon']['url']); ?>" alt="<?php echo esc_attr($item['icon']['alt']); ?>">
                      </div>
                    <?php endif; ?>
                    <?php if (!empty($item['title'])): ?>
                      <div class="title h3 font-semibold text-grey-1 tracking-[-0.48px] mb-1 md:mb-2"><?php echo esc_html($item['title']); ?></div>
                    <?php endif; ?>
                    <?php if (!empty($item['content'])): ?>
                      <div class="content text-sm lg:text-base"><?php echo esc_html($item['content']); ?></div>
                    <?php endif; ?>
                  </div>
                <?php
                  $index++;
                endforeach; ?>
              </div>
            <?php endif; ?>
          <?php endif; ?>
        </div>
      <?php endif; ?>

      <!-- Shape Decoration -->
      <div class="md:block hidden absolute left-0 top-0 -z-1 pointer-none" data-speed="1.25">
        <img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/values/shape-2.png" alt="resins">
      </div>

    </div>
  </section>
<?php endif; ?>