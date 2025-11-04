<?php

/**
 * =============================================================================
 * HOME CLIENT BLOCK
 * =============================================================================
 *
 * ACF Fields:
 * - hide_block (true/false)
 * - title (text)
 * - description (wysiwyg)
 * - client_items (repeater)
 *    - image (image)
 *    - title (text)
 *    - description (wysiwyg)
 */

include locate_template('templates/blocks/hide_block.php', false, false);

$title        = get_sub_field('title');
$description  = get_sub_field('description');
$client_items = get_sub_field('client_items');
?>

<?php if (($title || $client_items) && !$hide_block):

    $client_count = is_array($client_items) ? count($client_items) : 0;
    $use_slider   = $client_count >= 4; // Slider enabled for 4+ items
    $block_id     = 'client-block-' . uniqid();

?>
    <section class="home-client-block fade-in" data-component="ClientSlider" data-load="lazy">
        <div class="container-fluid">

            <!-- Header Section -->
            <?php if ($title || $description): ?>
                <div class="section-heading text-center mb-8">
                    <?php if ($title): ?>
                        <h2 class="mb-1 fade-text"><?= esc_html($title); ?></h2>
                    <?php endif; ?>
                    <?php if ($description): ?>
                        <div class="anim-uni-in-up"><?= wp_kses_post($description); ?></div>
                    <?php endif; ?>
                </div>
            <?php endif; ?>

            <!-- Client Items -->
            <?php if ($client_items && $client_count > 0): ?>
                <div class="client-slider-container"
                    data-slider-enabled="<?= $use_slider ? 'true' : 'false'; ?>"
                    data-client-count="<?= $client_count; ?>">

                    <div class="client-slider swiper">
                        <div class="swiper-wrapper items-stretch">

                            <?php foreach ($client_items as $item):
                                $image           = $item['image'] ?? null;
                                $item_title      = $item['title'] ?? '';
                                $item_description = $item['description'] ?? '';
                            ?>
                                <div class="swiper-slide client-item group h-full">
                                    <div class="client-item bg-light-blue px-5 sm:px-6 pt-6 pb-8 sm:pb-12 rounded-[20px] md:rounded-[40px] relative flex flex-col h-full animate-card-3">

                                        <!-- Client Image -->
                                        <?php if ($image && is_array($image) && !empty($image['url'])): ?>
                                            <div class="client-image text-center mb-6">
                                                <img
                                                    src="<?= esc_url($image['url']); ?>"
                                                    alt="<?= esc_attr($image['alt'] ?: 'Client Logo'); ?>"
                                                    class="mx-auto"
                                                    loading="lazy">
                                            </div>
                                        <?php endif; ?>

                                        <!-- Client Content -->
                                        <div class="client-content flex flex-col flex-grow">
                                            <?php if ($item_description): ?>
                                                <div class="flex items-start gap-6 flex-grow">
                                                    <img
                                                        src="<?= get_vite_asset('images/home/quotes.svg'); ?>"
                                                        alt="Quote Icon"
                                                        class="shrink-0 mt-1 w-6 h-6 sm:w-8 sm:h-8">

                                                    <div class="testimonial-content flex flex-col gap-4 flex-grow">
                                                        <div class="prose prose-p:text-charcoal text-base leading-[22px]">
                                                            <?= wp_kses_post($item_description); ?>
                                                        </div>

                                                        <?php if ($item_title): ?>
                                                            <div class="body-1 font-semibold mt-auto">
                                                                <?= esc_html($item_title); ?>
                                                            </div>
                                                        <?php endif; ?>
                                                    </div>
                                                </div>
                                            <?php endif; ?>
                                        </div>

                                        <!-- Decorative Shape -->
                                        <div class="curve-shape absolute end-0 right-[-1px] bottom-0 w-[60px]"></div>
                                    </div>
                                </div>


                            <?php endforeach; ?>

                        </div>
                    </div>

                    <!-- Slider Navigation -->
                    <?php if ($client_count > 3): ?>
                        <div class="mt-3 flex justify-center items-center gap-4">
                        <?php else: ?>
                            <div class="mt-3 flex lg:hidden justify-center items-center gap-4">
                            <?php endif; ?>

                            <div class="swiper-btn-prev-pagination swiper-btn-prev">
                                <!-- SVG prev arrow -->
                                <svg xmlns="http://www.w3.org/2000/svg" width="9" height="7" viewBox="0 0 9 7" fill="none">
                                    <path d="M7.92214 3.18291C8.16739 3.18291 8.36621 3.38173 8.36621 3.62699C8.36621 3.87224 8.16739 4.07106 7.92214 4.07106L1.66704 4.07106L3.79543 6.19944C3.96885 6.37286 3.96885 6.65403 3.79543 6.82745C3.62201 7.00087 3.34084 7.00087 3.16742 6.82745L0.594961 4.255C0.24812 3.90816 0.24812 3.34581 0.594961 2.99897L3.16742 0.426516C3.34084 0.253095 3.62201 0.253096 3.79543 0.426516C3.96885 0.599937 3.96885 0.881107 3.79543 1.05453L1.66705 3.18291L7.92214 3.18291Z" fill="#DA000E" />
                                </svg>
                            </div>

                            <div class="swiper-pagination-custom text-primary text-xs font-medium !w-4 !h-4"></div>

                            <div class="swiper-btn-next-pagination swiper-btn-next">
                                <!-- SVG next arrow -->
                                <svg xmlns="http://www.w3.org/2000/svg" width="9" height="7" viewBox="0 0 9 7" fill="none">
                                    <path d="M1.15891 3.18291C0.913661 3.18291 0.714844 3.38173 0.714844 3.62699C0.714844 3.87224 0.913661 4.07106 1.15892 4.07106L7.41401 4.07106L5.28562 6.19944C5.1122 6.37286 5.1122 6.65403 5.28562 6.82745C5.45904 7.00087 5.74021 7.00087 5.91364 6.82745L8.48609 4.255C8.83293 3.90816 8.83294 3.34581 8.48609 2.99897L5.91363 0.426516C5.74021 0.253095 5.45904 0.253096 5.28562 0.426516C5.1122 0.599937 5.1122 0.881107 5.28562 1.05453L7.41401 3.18291L1.15891 3.18291Z" fill="#DA000E" />
                                </svg>
                            </div>

                            </div>
                        </div>

                    <?php else: ?>
                        <div class="text-center py-12">
                            <p class="text-gray-500 text-lg">No client items to display.</p>
                        </div>
                    <?php endif; ?>

                </div>
    </section>
<?php endif; ?>