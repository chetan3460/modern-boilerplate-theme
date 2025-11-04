<?php

/**
 * Reports Block - Filters Component
 * @param array $all_reports
 * @param array $financial_years
 * @param array $quarters
 */
?>



<!-- Desktop Filters and Results Count -->
<div class="flex flex-col md:flex-row md:items-center md:justify-between mb-6">
    <div class="mb-4 md:mb-0">
        <span class="text-black" id="results-count">
            Showing <span id="showing-count"><?php echo min(12, count($all_reports)); ?></span> of <?php echo count($all_reports); ?> Reports
        </span>
    </div>

    <div class="lg:hidden text-sm font-semibold text-black mb-2">
        Filters
    </div>
    <!-- Desktop Filter Dropdowns -->
    <div class="flex items-center gap-3 lg:flex-wrap">

        <!-- Financial Year Filter -->
        <?php if (!empty($financial_years) && !is_wp_error($financial_years)): ?>
            <div class="relative max-md:w-full" data-dd="year" id="year-filter-container" style="display: none;">
                <button type="button" class="max-md:w-full inline-flex items-center justify-between gap-2 border border-[#D6D6D6] !font-semibold rounded-[12px] px-4 py-3 text-xs !bg-white !text-grey-7 focus:outline-none" aria-haspopup="listbox" aria-expanded="false">
                    <span class="dd-label">Financial Year</span>
                    <svg class="h-4 w-4 text-black" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                        <path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 10.94l3.71-3.71a.75.75 0 111.06 1.06l-4.24 4.25a.75.75 0 01-1.06 0L5.21 8.29a.75.75 0 01-1.08z" clip-rule="evenodd" />
                    </svg>
                </button>
                <ul class="dd-menu absolute z-20 mt-2 w-44 bg-white border border-gray-200 rounded-lg shadow-lg p-1 max-h-64 overflow-auto hidden max-md:w-full" role="listbox">
                    <li class="dd-item px-3 py-2 rounded-md md:text-base text-sm text-gray-500 hover:!text-primary hover:bg-[#CCD9EF]/20 cursor-pointer" data-value="">Financial Year</li>
                    <?php foreach ($financial_years as $year): ?>
                        <li class="dd-item px-3 py-2 rounded-md md:text-base text-sm text-black hover:!text-primary hover:bg-[#CCD9EF]/20 cursor-pointer hover:font-semibold" data-value="<?php echo esc_attr($year->slug); ?>"><?php echo esc_html($year->name); ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
            <select id="year-filter" class="hidden max-md:w-full" aria-hidden="true">
                <option value="">Financial Year</option>
                <?php foreach ($financial_years as $year): ?>
                    <option value="<?php echo esc_attr($year->slug); ?>"><?php echo esc_html($year->name); ?></option>
                <?php endforeach; ?>
            </select>
        <?php endif; ?>

        <!-- Quarter Filter -->
        <?php if (!empty($quarters) && !is_wp_error($quarters)): ?>
            <div class="relative max-md:w-full" data-dd="quarter" id="quarter-filter-container" style="display: none;">
                <button type="button" class="max-md:w-full inline-flex justify-between items-center gap-2 border border-[#D6D6D6] !font-semibold rounded-[12px] px-4 py-3 text-xs !bg-white !text-grey-7 focus:outline-none " aria-haspopup="listbox" aria-expanded="false">
                    <span class="dd-label">Quarter</span>
                    <svg class="h-4 w-4 text-black" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                        <path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 10.94l3.71-3.71a.75.75 0 111.06 1.06l-4.24 4.25a.75.75 0 01-1.06 0L5.21 8.29a.75.75 0 01-1.08z" clip-rule="evenodd" />
                    </svg>
                </button>
                <ul class="dd-menu absolute z-20 mt-2 w-32 bg-white border border-gray-200 rounded-lg shadow-lg p-1 max-h-64 overflow-auto hidden max-md:w-full" role="listbox">
                    <li class="dd-item px-3 py-2 rounded-md md:text-base text-sm text-gray-500 hover:!text-primary hover:bg-[#CCD9EF]/20 cursor-pointer" data-value="">Quarter</li>
                    <?php foreach ($quarters as $quarter): ?>
                        <li class="dd-item px-3 py-2 rounded-md md:text-base text-sm text-black hover:!text-primary hover:bg-[#CCD9EF]/20 cursor-pointer hover:font-semibold" data-value="<?php echo esc_attr($quarter->slug); ?>"><?php echo esc_html($quarter->name); ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
            <select id="quarter-filter" class="hidden " aria-hidden="true">
                <option value="">Quarter</option>
                <?php foreach ($quarters as $quarter): ?>
                    <option value="<?php echo esc_attr($quarter->slug); ?>"><?php echo esc_html($quarter->name); ?></option>
                <?php endforeach; ?>
            </select>
        <?php endif; ?>
    </div>
    <!-- <div class="lg:hidden mb-0 mt-4">
        <span class="text-black" id="results-count">
            Showing <span id="showing-count"><?php echo min(12, count($all_reports)); ?></span> of <?php echo count($all_reports); ?> Reports
        </span>
    </div> -->
</div>

<!-- Mobile filters section removed - was causing conflicts with desktop filtering system -->