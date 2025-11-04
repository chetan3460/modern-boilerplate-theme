<?php

/**
 * Reports Block - Report Card Component
 * @param object $report - WordPress post object
 * @param int $index - Card index for pagination
 */

// Get report taxonomy terms
$report_categories = get_the_terms($report->ID, 'report_category');
$report_years = get_the_terms($report->ID, 'financial_year');
$report_quarters = get_the_terms($report->ID, 'quarter');

$category_slugs = $report_categories ? array_map(function ($term) {
    return $term->slug;
}, $report_categories) : array();
$year_slugs = $report_years ? array_map(function ($term) {
    return $term->slug;
}, $report_years) : array();
$quarter_slugs = $report_quarters ? array_map(function ($term) {
    return $term->slug;
}, $report_quarters) : array();

// Get ACF fields (try both ACF function and direct meta)
$published_date = get_field('published_date', $report->ID) ?: get_post_meta($report->ID, 'published_date', true);
$document_file = get_field('document_file', $report->ID) ?: get_post_meta($report->ID, 'document_file', true);
$download_link = get_field('download_link', $report->ID) ?: get_post_meta($report->ID, 'download_link', true);
$file_size = get_field('file_size', $report->ID) ?: get_post_meta($report->ID, 'file_size', true);
$featured = get_field('featured_report', $report->ID) ?: get_post_meta($report->ID, 'featured_report', true);
?>

<?php
$download_url = '';
$file_name = 'Download';

// Handle different ACF file field formats
if (!empty($document_file)) {
    if (is_array($document_file)) {
        // ACF returns array format
        if (isset($document_file['url'])) {
            $download_url = $document_file['url'];
            $file_name = isset($document_file['filename']) ? $document_file['filename'] : 'Download';
        }
    } elseif (is_string($document_file)) {
        // ACF returns URL string or attachment ID
        if (filter_var($document_file, FILTER_VALIDATE_URL)) {
            // It's a URL
            $download_url = $document_file;
        } elseif (is_numeric($document_file)) {
            // It's an attachment ID
            $download_url = wp_get_attachment_url($document_file);
            $file_name = basename(get_attached_file($document_file));
        }
    }
}

// Fallback to external download link
if (empty($download_url) && !empty($download_link)) {
    $download_url = $download_link;
    $file_name = 'External Download';
}
?>

<!-- Single Responsive Report Card -->
<?php $custom_order = get_field('custom_order', $report->ID) ?: get_post_meta($report->ID, 'custom_order', true); ?>
<div class="report-card bg-white rounded-2xl p-4 lg:p-6 duration-200<?php echo $report->visibility_class ?? ''; ?> <?php echo $featured ? ' ring-2 ring-red-200 bg-red-50' : ''; ?>"
    data-report-id="<?php echo esc_attr($report->ID); ?>"
    data-categories="<?php echo esc_attr(implode(',', $category_slugs)); ?>"
    data-years="<?php echo esc_attr(implode(',', $year_slugs)); ?>"
    data-quarters="<?php echo esc_attr(implode(',', $quarter_slugs)); ?>"
    data-title="<?php echo esc_attr(strtolower($report->post_title)); ?>"
    data-content="<?php echo esc_attr(strtolower(wp_strip_all_tags($report->post_content))); ?>"
    data-published="<?php echo esc_attr($published_date); ?>"
    data-featured="<?php echo $featured ? '1' : '0'; ?>"
    data-date="<?php echo esc_attr($report->post_date); ?>"
    data-custom-order="<?php echo esc_attr($custom_order); ?>"
    data-index="<?php echo $index; ?>" <?php
                                        // Add sort order attributes for each category if applicable
                                        foreach ($report_categories as $cat) {
                                            echo ' data-sort-order-' . esc_attr($cat->slug) . '="' . esc_attr(resplast_get_report_sort_order_attr($report->ID, $cat->term_id)) . '"';
                                        }
                                        ?>>

    <?php if ($featured): ?>
        <div class="mb-3">
            <span class="inline-flex items-center px-2 py-1 text-xs font-medium bg-red-100 text-red-800 rounded-full">
                ⭐ Featured
            </span>
        </div>
    <?php endif; ?>

    <!-- Main Content Area -->
    <div class="flex items-start gap-3 lg:gap-4">
        <!-- Document Icon -->
        <div class="flex-shrink-0 size-9 lg:size-12 rounded-full flex items-center justify-center border border-[#F4F4F4]">
            <img src="<?= get_stylesheet_directory_uri(); ?>/assets/images/report/report.svg" alt="">
        </div>

        <!-- Content -->
        <div class="flex-1 min-w-0">
            <div class="body-2 font-semibold text-grey-1 mb-2">
                <?php echo esc_html($report->post_title); ?>
            </div>

            <?php if ($published_date): ?>
                <p class="body-2 text-grey-3 font-medium mb-3">
                    <?php echo esc_html($published_date); ?>
                </p>
            <?php endif; ?>
            <!-- Download Button -->
            <div class="flex-shrink-0">
                <?php if ($download_url): ?>
                    <a href="<?php echo esc_url($download_url); ?>"
                        class="inline-flex items-center gap-2 text-primary  font-bold transition-colors duration-200 text-sm lg:text-base !tracking-[-0.48px]"
                        download
                        target="_blank"
                        title="<?php echo esc_attr($file_name); ?>">
                        <img src="<?= get_stylesheet_directory_uri(); ?>/assets/images/report/download.svg" alt="">

                        <span class="">Download</span>
                    </a>
                <?php else: ?>
                    <div class="text-gray-400 text-sm">
                        <span class="hidden lg:inline">Download not available</span>
                        <span class="lg:hidden">N/A</span>
                        <?php if (current_user_can('manage_options')): ?>
                            <br><small class="text-xs text-red-500">(Admin: Check ACF field configuration)</small>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
            </div>
            <?php if ($file_size): ?>
                <p class="text-xs text-gray-500 mb-3 hidden lg:block">
                    File size: <?php echo esc_html($file_size); ?>
                </p>
            <?php endif; ?>
        </div>


    </div>

    <!-- Categories (Desktop Only) -->
    <?php if ($report_categories): ?>
        <div class="hidden  flex-wrap gap-2">
            <?php foreach ($report_categories as $category): ?>
                <span class="inline-flex items-center px-2 py-1 text-xs font-medium bg-blue-100 text-blue-800 rounded">
                    <?php echo esc_html($category->name); ?>
                </span>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>