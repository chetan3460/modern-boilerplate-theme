<?php

/**
 * Investor Support Block Template
 * For use with ACF Flexible Content
 */

// Create id attribute for anchoring
$id = 'investor-support-' . uniqid();

// Create class attribute with default class names
$class_name = 'investor-support-block';
if (function_exists('get_row_index')) {
    $class_name .= ' block-' . get_row_index();
}

// Check if block should be hidden
$hide_block = get_sub_field('hide_block');
if ($hide_block) {
    return;
}

// Get ACF fields
$section_heading = get_sub_field('section_heading') ?: 'Seamless connect for investors';
$section_description = get_sub_field('section_description') ?: 'We offer seamless support for shareholders and investors through our Registrar & Transfer Agent and digital portals, ensuring transparency, efficiency, and timely assistance.';

// RTA Details
$rta_company = get_sub_field('rta_company') ?: 'MUFG Intime India Private Limited';
$rta_phone = get_sub_field('rta_phone') ?: '1800 1020 878';
$rta_phone_label = get_sub_field('rta_phone_label') ?: 'Toll-free';
$rta_address = get_sub_field('rta_address') ?: 'C-101, 247 Park, L.B.S Marg, Vikhroli (West) Mumbai - 400 083';
$rta_email = get_sub_field('rta_email') ?: 'rnt.helpdesk@in.mpms.mufg.com';
$rta_heading = get_sub_field('rta_heading') ?: 'Registrar & Transfer Agent (RTA) details';

// Self-Service Section
$swayam_heading = get_sub_field('swayam_heading') ?: 'Investor Self-Service with SWAYAM';
$swayam_description = get_sub_field('swayam_description') ?: 'Manage your investments and stay updated in one place.';
$swayam_button_text = get_sub_field('swayam_button_text') ?: 'Go to SWAYAM Portal';
$swayam_button_url = get_sub_field('swayam_button_url') ?: '#';

// Support Section
$support_heading = get_sub_field('support_heading') ?: 'Seamless Support for Investors';
$support_description = get_sub_field('support_description') ?: 'Submit and track your service requests with ease and transparency.';
$support_button_text = get_sub_field('support_button_text') ?: 'Raise Service Request';
$support_button_url = get_sub_field('support_button_url') ?: '#';

// Background color options
$bg_color = get_sub_field('background_color') ?: 'light';
$bg_class = $bg_color === 'light' ? 'bg-gray-50' : 'bg-white';
?>

<section id="<?php echo esc_attr($id); ?>" class=" <?php echo esc_attr($class_name); ?>  <?php echo esc_attr($bg_class); ?> fade-in">
    <div class="container-fluid">

        <!-- Section Header -->
        <div class="section-heading text-center">
            <h2 class="fade-text">
                <?php echo wp_kses_post($section_heading); ?>
            </h2>
            <div class="description-content prose !max-w-none">
                <?php echo wp_kses_post($section_description); ?>
            </div>
        </div>

        <!-- Main Content Grid -->
        <div class="bg-sky-50 p-5 lg:p-10 flex flex-col lg:flex-row gap-8 lg:gap-12 rounded-3xl bottom-right">

            <!-- Left Column: RTA Details -->
            <div class="flex-1 space-y-8">
                <div>
                    <h3 class="font-semibold tracking-[-0.48px] text-black mb-5">
                        <?php echo esc_html($rta_heading); ?>
                    </h3>

                    <div class="space-y-4">
                        <!-- Company Name -->
                        <div class="flex items-center gap-3">
                            <div class="flex-shrink-0 w-8 h-8 bg-white rounded-full items-center justify-center flex">
                                <img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/icons/Company.svg" class="size-[17px]" alt="email resins">

                            </div>
                            <div>
                                <div class="body-2 text-black font-medium">
                                    <?php echo esc_html($rta_company); ?>
                                </div>
                            </div>
                        </div>

                        <!-- Phone Number -->
                        <div class="flex items-center gap-3">
                            <div class="flex-shrink-0 w-8 h-8 bg-white rounded-full items-center justify-center flex">
                                <img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/contact/contact.svg" class="size-[17px] rotate-[-30deg]" alt="phone resins">
                            </div>
                            <div>
                                <p class="text-lg text-gray-900">
                                    <a href="tel:<?php echo esc_attr(str_replace(' ', '', $rta_phone)); ?>" class="body-2 text-black font-medium">
                                        <?php echo esc_html($rta_phone); ?>
                                    </a>
                                    <span class="body-2 text-black font-medium ml-2">(<?php echo esc_html($rta_phone_label); ?>)</span>
                                </p>
                            </div>
                        </div>

                        <!-- Address -->
                        <div class="flex items-center gap-3">
                            <div class="flex-shrink-0 w-8 h-8 bg-white rounded-full justify-center items-center flex">
                                <img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/contact/location.svg" class="size-[17px]" alt="email resins">

                            </div>
                            <div>
                                <div class="body-2 text-black font-medium">
                                    <?php echo esc_html($rta_address); ?>
                                </div>
                            </div>
                        </div>

                        <!-- Email -->
                        <div class="flex items-center gap-3">
                            <div class="flex-shrink-0 w-8 h-8 bg-white rounded-full items-center justify-center flex">
                                <img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/contact/email.svg" class="size-[17px]" alt="email resins">

                            </div>
                            <div>
                                <div class="body-2 text-black font-medium">
                                    <a href="mailto:<?php echo esc_attr($rta_email); ?>" class="hover:text-primary-600 transition-colors break-all">
                                        <?php echo esc_html($rta_email); ?>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Separator Line -->
            <div class="block w-full lg:w-px max-lg:h-px  bg-black/10 self-stretch"></div>

            <!-- Right Column: Digital Services -->
            <div class="flex-1 space-y-8">

                <!-- SWAYAM Self-Service -->
                <div class="">
                    <h3 class="font-semibold tracking-[-0.48px] mb-2">
                        <?php echo esc_html($swayam_heading); ?>
                    </h3>
                    <p class="body-2 font-normal">
                        <?php echo esc_html($swayam_description); ?>
                    </p>

                    <?php if ($swayam_button_url && $swayam_button_url !== '#'): ?>
                        <a href="<?php echo esc_url($swayam_button_url); ?>"
                            class="!h-11 text-sm lg:text-base inline-flex items-center px-4 py-2 border-2 border-red-600 text-red-600 font-medium rounded-full hover:bg-red-600 hover:text-white transition-all duration-300 group mt-3 "
                            target="_blank"
                            rel="noopener">
                            <?php echo esc_html($swayam_button_text); ?>
                        </a>
                    <?php endif; ?>
                </div>

                <!-- Support Service -->
                <div class="">
                    <h3 class="font-semibold tracking-[-0.48px] mb-2">
                        <?php echo esc_html($support_heading); ?>
                    </h3>
                    <p class="body-2 font-normal">
                        <?php echo esc_html($support_description); ?>
                    </p>

                    <?php if ($support_button_url && $support_button_url !== '#'): ?>
                        <a href="<?php echo esc_url($support_button_url); ?>"
                            class="!h-11 text-sm lg:text-base inline-flex items-center px-4 py-2 border-2 border-red-600 text-red-600 font-medium rounded-full hover:bg-red-600 hover:text-white transition-all duration-300 group mt-3 ">
                            <?php echo esc_html($support_button_text); ?>

                        </a>
                    <?php endif; ?>
                </div>

            </div>
        </div>
    </div>
</section>