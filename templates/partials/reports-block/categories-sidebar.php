<!-- Mobile Categories Dropdown -->
<div class="lg:hidden relative p-4 pb-0" data-component="MobileCategoriesDropdown" data-load="eager">

    <h3 class="font-semibold text-lg text-black mb-4 ">Categories</h3>

    <div class="relative">
        <button id="categories-toggle" class="text-sm w-full flex items-center justify-between px-4 py-3 text-white bg-primary border  border-primary rounded-2xl text-left focus:outline-none" aria-haspopup="listbox" aria-expanded="false">
            <span id="selected-category" class="text-white font-normal"><?php echo !empty($categories) && is_array($categories) ? esc_html($categories[0]->name) : 'Select Category'; ?></span>
            <!-- <svg class="h-4 w-4 text-white" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                <path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 10.94l3.71-3.71a.75.75 0 111.06 1.06l-4.24 4.25a.75.75 0 01-1.06 0L5.21 8.29a.75.75 0 01-1.08z" clip-rule="evenodd" />
            </svg> -->
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20" fill="none">
                <path d="M3 6L9.62308 13.175C9.67142 13.2308 9.72981 13.2753 9.79464 13.3057C9.85947 13.3361 9.92936 13.3518 10 13.3518C10.0706 13.3518 10.1405 13.3361 10.2054 13.3057C10.2702 13.2753 10.3286 13.2308 10.3769 13.175L17 6" stroke="white" stroke-width="1.5" stroke-linecap="round" />
            </svg>
        </button>

        <ul id="categories-dropdown" class="hidden absolute z-20 mt-2 w-full bg-white border border-gray-200 rounded-2xl shadow-lg p-1 max-h-[300px] overflow-y-auto focus:outline-none" role="listbox">
            <?php if (!empty($categories) && !is_wp_error($categories)): ?>
                <?php foreach ($categories as $index => $category): ?>
                    <li class="dd-item px-5 py-2.5 rounded-xl md:text-base text-sm  cursor-pointer font-normal<?php echo $index === 0 ? ' active [&.active]:font-semibold [&.active]:text-primary [&.active]:bg-[#ccd9ef33] text-white' : ''; ?>"
                        data-category="<?php echo esc_attr($category->slug); ?>" data-value="<?php echo esc_attr($category->slug); ?>" role="option" tabindex="0">
                        <?php echo esc_html($category->name); ?>
                    </li>
                <?php endforeach; ?>
            <?php endif; ?>
        </ul>
    </div>

</div>

<!-- Desktop Categories Sidebar -->
<div class="hidden lg:block lg:col-span-1 relative">
    <div class="p-4 lg:p-6">
        <div class="body-1 text-grey-1 border-b border-[#BDBDBD]  pb-4 mb-4 font-semibold">Categories</div>
        <nav class="space-y-2">
            <?php if (isset($show_all_reports) && $show_all_reports): ?>
                <!-- All Categories -->
                <button class="category-filter w-full text-left px-5 py-2.5 rounded-2xl transition-colors duration-200 focus:outline-none"
                    data-category="all">
                    All Reports
                    <span class="ml-2 text-xs opacity-75" id="count-all"><?php echo count($all_reports); ?></span>
                </button>
            <?php endif; ?>

            <?php if (!empty($categories) && !is_wp_error($categories)): ?>
                <?php
                $is_first_category = true;
                $has_all_reports = (isset($show_all_reports) && $show_all_reports);
                ?>
                <?php foreach ($categories as $index => $category): ?>
                    <?php
                    // Count reports in this category from the existing $all_reports array
                    $category_count = 0;
                    foreach ($all_reports as $report) {
                        $report_categories = get_the_terms($report->ID, 'report_category');
                        if ($report_categories && !is_wp_error($report_categories)) {
                            foreach ($report_categories as $report_cat) {
                                if ($report_cat->term_id === $category->term_id) {
                                    $category_count++;
                                    break; // Don't count the same report multiple times
                                }
                            }
                        }
                    }

                    // Determine if this should be the default active category
                    $is_default_active = false;
                    if (!$has_all_reports && $is_first_category) {
                        $is_default_active = true;
                        $is_first_category = false;
                    }

                    // Set appropriate CSS classes
                    $button_classes = 'category-filter w-full text-left px-4 py-3 rounded-2xl transition-colors duration-200 focus:outline-none font-normal';
                    if ($index === 0) {
                        $button_classes .= ' active';
                    }
                    $button_classes .= ' hover:bg-gray-200 text-black';
                    ?>
                    <button class="<?php echo $button_classes; ?> flex items-center justify-between gap-2"
                        data-category="<?php echo esc_attr($category->slug); ?>">
                        <?php echo esc_html($category->name); ?>
                        <!-- <span class="ml-2 text-xs opacity-75" id="count-<?php echo esc_attr($category->slug); ?>"><?php echo $category_count; ?></span> -->
                        <?php if ($is_default_active): ?>
                            <svg xmlns="http://www.w3.org/2000/svg" width="10" height="17" viewBox="0 0 10 17" fill="none" class="category-arrow">
                                <path d="M0.956055 1.69922L8.13105 8.3223C8.18686 8.37064 8.23133 8.42903 8.26174 8.49386C8.29214 8.55868 8.30783 8.62858 8.30783 8.69922C8.30783 8.76986 8.29214 8.83975 8.26174 8.90458C8.23133 8.96941 8.18686 9.0278 8.13105 9.07614L0.956054 15.6992" stroke="white" stroke-width="1.5" stroke-linecap="round" />
                            </svg>
                        <?php else: ?>
                            <svg xmlns="http://www.w3.org/2000/svg" width="10" height="17" viewBox="0 0 10 17" fill="none" class="category-arrow hidden">
                                <path d="M0.956055 1.69922L8.13105 8.3223C8.18686 8.37064 8.23133 8.42903 8.26174 8.49386C8.29214 8.55868 8.30783 8.62858 8.30783 8.69922C8.30783 8.76986 8.29214 8.83975 8.26174 8.90458C8.23133 8.96941 8.18686 9.0278 8.13105 9.07614L0.956054 15.6992" stroke="white" stroke-width="1.5" stroke-linecap="round" />
                            </svg>
                        <?php endif; ?>
                    </button>
                <?php endforeach; ?>
            <?php endif; ?>
        </nav>
    </div>
    <div class="absolute top-0 right-0 h-full w-px bg-[#BDBDBD]"></div>

</div>