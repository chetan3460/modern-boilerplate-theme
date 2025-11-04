<?php

// Admin: hide Mega Menu ACF fields on submenu items in Appearance > Menus
// Only keep these fields for top-level items (depth 0)
add_action('admin_head-nav-menus.php', function () {
  echo '<style id="hide-mega-fields-on-submenus">' .
    '#menu-to-edit li[class*="menu-item-depth-"]:not(.menu-item-depth-0) ' .
    '.acf-field[data-name="mega_title"],' .
    '#menu-to-edit li[class*="menu-item-depth-"]:not(.menu-item-depth-0) ' .
    '.acf-field[data-name="mega_description"],' .
    '#menu-to-edit li[class*="menu-item-depth-"]:not(.menu-item-depth-0) ' .
    '.acf-field[data-name="mega_image"]{display:none!important;}' .
    '</style>';
});
