<?php
$title = get_sub_field('title');
$description = get_sub_field('description');
$tabs_items = get_sub_field('tabs_items');
$button_link = get_sub_field('button_link');
$cta_target = !empty($button_link['target']) ? $button_link['target'] : '_self';

include locate_template('templates/blocks/hide_block.php', false, false);

if ($tabs_items && !$hide_block): ?>
  <section class="tabs-block overflow-hidden fade-in" data-component="HomeTabBlock" data-load="lazy">
    <div class="container-fluid xl:px-24 lg:px-14 px-5">

      <?php if ($title || $description): ?>
        <div class="sec-title-animation animation-style3">
          <div class="heading-block text-center max-w-4xl mx-auto">
            <?php if (!empty($title)): ?>
              <h2 class="mb-2 fade-text"><?= esc_html($title) ?></h2>
            <?php endif; ?>
            <?php if (!empty($description)): ?>
              <div class="section-description mb-8"><?= wp_kses_post($description) ?></div>
            <?php endif; ?>
          </div>
        </div>
      <?php endif; ?>

      <?php if (have_rows('tabs_items')): ?>
        <div class="w-full">
          <div class="products-container tabs-container flex flex-col lg:flex-row lg:flex-nowrap gap-3 lg:gap-5">
            <?php
            $i = 0;
            while (have_rows('tabs_items')):

              the_row();
              $item_title = get_sub_field('title');
              $item_content = get_sub_field('description');
              $tab_image = get_sub_field('tab_image');
              $tab_mobile_image = get_sub_field('tab_mobile_image');
              $tab_inner_image = get_sub_field('tab_inner_image');
              $image = $tab_image;
              $imageMobile = $tab_mobile_image;
              $active_class = $i === 0 ? ' active' : '';
              ?>
              <div class="tab-items tab-item group relative w-full h-[100px] lg:max-h-none lg:w-[221px] lg:h-[400px] rounded-[20px] md:rounded-[24px] lg:rounded-[16px] overflow-hidden cursor-pointer transition-[max-height,transform,opacity] lg:transition-[width,transform,opacity] duration-700 ease-in-out bg-[#eaf3ff] shadow-[inset_0_0_1px_rgba(0,0,0,0.03)] will-change-[transform,opacity] lg:p-0  lg:[&.active]:max-h-none lg:[&.active]:w-[60vw]<?= $active_class ?>" role="button" aria-expanded="<?= $active_class
  ? 'true'
  : 'false' ?>">

                <!-- Background Image -->
                <div class="image pointer-events-none absolute inset-0 lg:relative lg:w-full lg:h-full overflow-hidden z-0">
                  <?php if (!empty($image['url'])): ?>
                    <img loading="lazy" src="<?= esc_url(
                      $image['url']
                    ) ?>" class=" lg:block hidden lazy-image w-full h-full object-cover group-[.active]:opacity-0" alt="<?= esc_attr(
  $image['alt'] ?? $item_title
) ?>" />
                  <?php endif; ?>
                  <?php if (!empty($imageMobile['url'])): ?>
                    <img loading="lazy" src="<?= esc_url(
                      $imageMobile['url']
                    ) ?>" class="lg:hiddne flex lazy-image w-full h-full object-cover group-[.active]:opacity-0" alt="<?= esc_attr(
  $imageMobile['alt'] ?? $item_title
) ?>" />
                  <?php endif; ?>
                  <?php if (!empty($tab_inner_image['url'])): ?>
                    <img src="<?= esc_url($tab_inner_image['url']) ?>" alt="<?= esc_attr(
  $tab_inner_image['alt'] ?? $item_title
) ?>" class="hover-animation tab-overlay absolute bottom-0 left-0 pointer-none opacity-0 scale-[1.03] transition-[clip-path,transform,opacity] duration-1000 ease-in-out [clip-path:inset(0_0_0_100%)] group-[.active]:opacity-60 group-[.active]:scale-100 group-[.active]:[clip-path:inset(0_0_0_0)] will-change-[clip-path,transform,opacity]" />
                  <?php endif; ?>
                </div>

                <!-- Closed State -->
                <div class="closed-state relative lg:absolute lg:inset-0 z-20 flex items-center lg:justify-start lg:items-center p-6 lg:p-4 size-full">

                  <div class="absolute inset-0 z-[5] pointer-events-none bg-[linear-gradient(180deg, rgba(17, 17, 17, 0.00) 0%, rgba(17, 17, 17, 0.59) 75.48%)]"></div>


                  <?php if ($item_title): ?>
                    <div class=" max-lg:flex max-lg:h-full max-lg:items-center title relative z-10 text-black font-medium md:font-semibold text-[18px] max-lg:leading-[20px] lg:text-[22px] tracking-[-0.48px] keep-all">
                      <?= esc_html($item_title) ?>
                    </div>
                  <?php endif; ?>
                </div>

                <!-- Open State -->
                <div class="open-state hidden absolute inset-0 z-30 flex items-center justify-center p-5 lg:p-7">
                  <div class="content-card rounded-xl max-w-md lg:max-w-lg w-full">
                    <div class="content-wrapper space-y-2.5 md:space-y-3.5">
                      <?php if ($item_title): ?>
                        <div class="text-[18px] md:text-2xl font-medium md:font-semibold text-[#222]"><?= esc_html(
                          $item_title
                        ) ?></div>
                      <?php endif; ?>
                      <?php if ($item_content): ?>
                        <div class="description text-sm md:text-base font-normal"><?= wp_kses_post(
                          $item_content
                        ) ?></div>
                      <?php endif; ?>
                    </div>
                  </div>

                </div>

              </div>
            <?php $i++;
            endwhile;
            ?>
          </div>
        </div>
      <?php endif; ?>

      <?php if ($button_link): ?>
        <div class="flex justify-center pt-4">
          <a href="<?= esc_url($button_link['url']) ?>" target="<?= esc_attr(
  $cta_target
) ?>" class="inline-flex items-center px-6 py-3 rounded-full bg-primary text-white font-semibold transition hover:brightness-110"><?= esc_html(
  $button_link['title']
) ?></a>
        </div>
      <?php endif; ?>

    </div>
  </section>
<?php endif; ?>
