<?php

/**
 * Team Card Component
 * 
 * @param array $member - Member data array with keys: id, title, has_thumbnail, position
 */

// Ensure we have member data
if (!isset($member) || empty($member)) {
    return;
}
?>

<div class="team-member-card flex flex-col items-stretch group mb-3 lg:mb-6 rounded-2xl bottom-right h-full ">
    <!-- Member Photo with blue accent background -->
    <div class="relative">
        <div class="bg-[linear-gradient(180deg,#BABAB8_0%,#FFF_100%)] rounded-2xl overflow-hidden relative flex items-center justify-center ">
            <?php if ($member['has_thumbnail']): ?>
                <?php if (function_exists('resplast_optimized_image')): ?>
                    <?php echo resplast_optimized_image(get_post_thumbnail_id($member['id']), 'medium_large', [
                        'class' => ' object-cover object-top',
                        'alt' => $member['title'],
                        'lazy' => true
                    ]); ?>
                <?php else: ?>
                    <?php echo get_the_post_thumbnail($member['id'], 'medium_large', [
                        'class' => 'w-full h-full object-cover object-top',
                        'alt' => $member['title'],
                        'loading' => 'lazy'
                    ]); ?>
                <?php endif; ?>
            <?php else: ?>
                <div class="w-full h-full flex items-end justify-center pb-8">
                    <div class="w-32 h-32 rounded-full bg-gray-300 flex items-center justify-center">
                        <svg class="w-16 h-16 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"></path>
                        </svg>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Member Info Card -->
    <div class="bg-sky-50 p-4 md:p-6 rounded-b-2xl h-full flex flex-col items-stretch min-h-[108px] md:min-h-[125px] justify-start">
        <div class="h3 font-semibold text-grey-1 tracking-[-0.48px]">
            <?php echo esc_html($member['title']); ?>
        </div>

        <?php if (!empty($member['position'])): ?>
            <div class="body-1 text-grey-3 font-normal max-w-55 md:max-w-72 ">
                <?php echo esc_html($member['position']); ?>
            </div>
        <?php endif; ?>
    </div>
</div>