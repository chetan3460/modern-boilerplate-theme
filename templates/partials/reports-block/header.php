<?php
/**
 * Reports Block - Header Component
 * @param string $main_title
 * @param string $main_description
 */
?>

<!-- Header Section -->
<div class="section-heading text-center !max-w-none">
    <h2 class="fade-text"><?php echo esc_html($main_title); ?></h2>
    <div class="description-content prose !max-w-none">
        <p><?php echo esc_html($main_description); ?></p>
    </div>
</div>