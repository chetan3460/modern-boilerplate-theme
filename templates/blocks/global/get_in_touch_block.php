<?php
// ACF fields as specified
$title = get_sub_field('title') ?: '';
$description = get_sub_field('description') ?: '';
$phone = get_sub_field('phone') ?: '';
$email = get_sub_field('email') ?: '';
$country = get_sub_field('country') ?: '';
$address = get_sub_field('address') ?: '';
$form_id = get_sub_field('form_id') ?: '';

include locate_template('templates/blocks/hide_block.php', false, false);

if (
  !$hide_block &&
  ($title || $description || $phone || $email || $country || $address || $form_id)
): ?>
  <section class="get-in-touch-block fade-in">
    <div class="container-fluid relative">
      <div class="section-heading text-center mb-8 relative !max-w-full">
        <?php if ($title): ?>
          <h2 class="mb-2 fade-text"><?= esc_html($title) ?></h2>
        <?php endif; ?>

        <?php if ($description): ?>
          <div class=" mx-auto">
            <?= wp_kses_post($description) ?>
          </div>
        <?php endif; ?>

      </div>
      <div class="flex md:flex-row flex-col gap-7 bg-sky-50 rounded-[36px] px-4 md:px-10  pt-4 md:pt-10 md:pb-16 pb-12 relative justify-between">
        <!-- Left: copy + contact info -->
        <div class="w-full md:w-4/12 space-y-6">
          <?php if ($phone || $email || $country || $address): ?>
            <div class="flex flex-col gap-4">
              <?php if ($phone): ?>
                <div class="flex items-center gap-2 md:gap-3 ">
                  <div class="shrink-0 w-10 h-10 rounded-full bg-white/50 text-black flex items-center justify-center">
                    <img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/contact/contact.svg" class="size-[17px] rotate-[-30deg]" alt="phone resins">
                  </div>
                  <div>
                    <a class="text-grey-1 font-normal hover:text-primary transition-colors" href="tel:<?= preg_replace(
                      '/[^0-9+]/',
                      '',
                      esc_attr($phone)
                    ) ?>"><?= esc_html($phone) ?></a>
                  </div>
                </div>
              <?php endif; ?>

              <?php if ($email): ?>
                <div class="flex items-center gap-2 md:gap-3 ">
                  <div class="shrink-0 w-10 h-10 rounded-full bg-white/50 text-black flex items-center justify-center">
                    <img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/contact/email.svg" class="size-[17px]" alt="email resins">
                  </div>
                  <div>
                    <a class="text-grey-1 font-normal hover:text-primary transition-colors" href="mailto:<?= esc_attr(
                      $email
                    ) ?>"><?= esc_html($email) ?></a>
                  </div>
                </div>
              <?php endif; ?>

              <?php if ($country): ?>
                <div class="flex items-center gap-2 md:gap-3 ">
                  <div class="shrink-0 w-10 h-10 rounded-full bg-white/50 text-black flex items-center justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                      <path d="M21.75 12C21.75 17.3848 17.3848 21.75 12 21.75C6.61522 21.75 2.25 17.3848 2.25 12C2.25 6.61522 6.61522 2.25 12 2.25C17.3848 2.25 21.75 6.61522 21.75 12Z" stroke="currentColor" stroke-width="1.5" />
                      <path d="M12 2.25C14.5 5.25 15.75 8.5 15.75 12C15.75 15.5 14.5 18.75 12 21.75" stroke="currentColor" stroke-width="1.5" />
                      <path d="M12 2.25C9.5 5.25 8.25 8.5 8.25 12C8.25 15.5 9.5 18.75 12 21.75" stroke="currentColor" stroke-width="1.5" />
                      <path d="M2.25 12H21.75" stroke="currentColor" stroke-width="1.5" />
                      <path d="M4.5 8.25H19.5" stroke="currentColor" stroke-width="1.5" />
                      <path d="M4.5 15.75H19.5" stroke="currentColor" stroke-width="1.5" />
                    </svg>
                  </div>
                  <div>
                    <span class="text-grey-1 font-normal"><?php echo esc_html($country); ?></span>
                  </div>
                </div>
              <?php endif; ?>

              <?php if ($address): ?>
                <div class="flex items-start gap-2 md:gap-3">
                  <div class="shrink-0 w-10 h-10 rounded-full bg-white/50 text-black flex items-center justify-center">
                    <img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/contact/location.svg" class="size-[17px]" alt="email resins">
                  </div>
                  <div>
                    <div class="text-grey-1 font-normal hover:text-primary transition-colors md:max-w-[299px]"><?= wp_kses_post(
                      $address
                    ) ?></div>
                  </div>
                </div>
              <?php endif; ?>
            </div>
          <?php endif; ?>
        </div>

        <!-- Right: form -->
        <div class="w-full md:w-8/12 prose placeholder:!text-grey-3">
          <?php if ($form_id): ?>
            <?php
            $form_html = '';
            // If shortcode pasted
            if (strpos($form_id, '[') !== false) {
              $form_html = do_shortcode($form_id);
            } else {
              // Try Formidable Forms first
              if (class_exists('FrmFormsController')) {
                $attr = is_numeric($form_id)
                  ? 'id="' . esc_attr($form_id) . '"'
                  : 'key="' . esc_attr($form_id) . '"';
                $form_html = do_shortcode(
                  '[formidable ' . $attr . ' title=true description=false]'
                );
                // Then Gravity Forms
              } elseif (class_exists('WPCF7_ContactForm')) {
                $form_html = do_shortcode('[contact-form-7 id="' . esc_attr($form_id) . '"]');
                // WPForms
              } else {
                $form_html = do_shortcode('[wpforms id="' . esc_attr($form_id) . '"]');
              }
            }
            ?>
            <div class="">
              <?= $form_html
                ? $form_html
                : '<p class="text-gray-500">Form not available. Please configure a valid form ID or shortcode.</p>' ?>
            </div>
          <?php endif; ?>

        </div>

      </div>


    </div>
  </section>
<?php endif; ?>
