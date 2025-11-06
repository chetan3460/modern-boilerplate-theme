<?php

add_action('after_setup_theme', function () {
  register_nav_menus([
    'primary' => __('Primary Menu', 'vite-base-theme'),
    'mobile' => __('Mobile Menu', 'vite-base-theme'),
  ]);
});

/**
 * Base Nav Walker - Shared logic for desktop and mobile menus
 */
abstract class Base_Nav_Walker extends Walker_Nav_Menu
{
  /**
   * Build HTML attributes for menu links
   */
  protected function build_attributes($atts)
  {
    $attributes = '';
    foreach ($atts as $attr => $value) {
      if (!empty($value)) {
        $attributes .= ' ' . $attr . '="' . esc_attr($value) . '"';
      }
    }
    return $attributes;
  }

  /**
   * Get ACF field value for menu item
   */
  protected function get_menu_item_field($key, $item_id)
  {
    if (function_exists('get_field')) {
      $val = get_field($key, 'menu_item_' . $item_id);
      // ACF image may be array
      if (is_array($val) && isset($val['url'])) {
        return $val['url'];
      }
      return $val;
    }
    return null;
  }

  /**
   * Check if item has children
   */
  protected function has_children($item)
  {
    $classes = empty($item->classes) ? [] : (array) $item->classes;
    return in_array('menu-item-has-children', $classes);
  }
}

/**
 * Desktop Menu Walker with Mega Menu Support
 */
class Custom_Nav_Walker extends Base_Nav_Walker
{
  private $current_parent_item = null;
  private $current_parent_meta = [
    'title' => '',
    'desc' => '',
    'image' => '',
  ];

  private function get_parent_meta($item)
  {
    $title = $this->get_menu_item_field('mega_title', $item->ID) ?: $item->title;
    $desc = $this->get_menu_item_field('mega_description', $item->ID) ?: ($item->description ?: '');
    $image = $this->get_menu_item_field('mega_image', $item->ID) ?: '';

    // Fallbacks from the linked object (e.g., Page) if fields are empty
    $object_id = isset($item->object_id) ? intval($item->object_id) : 0;
    if ($object_id) {
      if (empty($desc)) {
        $maybe_excerpt = get_the_excerpt($object_id);
        if (!empty($maybe_excerpt) && !is_wp_error($maybe_excerpt)) {
          $desc = wp_strip_all_tags($maybe_excerpt);
        }
      }
      if (empty($image)) {
        $thumb = get_the_post_thumbnail_url($object_id, 'large');
        if (!empty($thumb)) {
          $image = $thumb;
        }
      }
    }

    return [
      'title' => $title,
      'desc' => $desc,
      'image' => $image,
    ];
  }

  function start_lvl(&$output, $depth = 0, $args = null)
  {
    // For top-level parents, wrap the submenu in a mega panel with title/desc/image
    if ($depth === 0 && $this->current_parent_item) {
      $label = $this->current_parent_item->title ?: '';
      $panel_id = 'mega-' . $this->current_parent_item->ID;
      $title = $this->current_parent_meta['title'] ?: $label;
      $desc = $this->current_parent_meta['desc'] ?: '';
      $image = $this->current_parent_meta['image'] ?: '';

      $output .=
        "\n<div class=\"mega-panel absolute left-0 right-0 top-full z-50 md:block\" id=\"" .
        esc_attr($panel_id) .
        "\" role=\"region\" aria-label=\"" .
        esc_attr($label) .
        "\">\n";
      $output .= "  <div class=\"container mx-auto\">\n";
      $output .=
        "    <div class=\"mega-card bg-white rounded-2xl shadow-xl ring-1 ring-black/5 p-6 grid md:grid-cols-12 gap-6\">\n";
      // Left column: title/desc/image
      $output .= "      <div class=\"mega-left md:col-span-6 flex flex-col gap-2\">\n";
      $output .=
        "        <h3 class=\"text-2xl font-semibold text-grey-1\">" . esc_html($title) . "</h3>\n";
      if (!empty($desc)) {
        $output .=
          "        <p class=\"text-grey-2 text-sm font-normal leading-relaxed\">" .
          esc_html($desc) .
          "</p>\n";
      }
      if (!empty($image)) {
        $output .=
          "        <img src=\"" .
          esc_url($image) .
          "\" alt=\"" .
          esc_attr($title) .
          "\" class=\"w-full rounded-xl mt-4 object-cover\" loading=\"lazy\" decoding=\"async\" />\n";
      }
      $output .= "      </div>\n";
      // Right column: submenu list starts here
      $output .= "      <div class=\"mega-right md:col-span-6\">\n";
      $output .= "        <ul class=\"submenu grid gap-4\">\n";
    } else {
      $output .= "\n<ul class=\"submenu\">\n";
    }
  }

  function start_el(&$output, $item, $depth = 0, $args = null, $id = 0)
  {
    $has_children = $this->has_children($item);
    $classes = empty($item->classes) ? [] : (array) $item->classes;

    // Build classes
    if ($has_children) {
      $classes[] = 'has-submenu flex items-center gap-1';
      $classes[] = $depth === 0 ? 'parent-menu-item' : 'child-menu-item';
    }

    // Handle mega menu for top-level parents
    if ($has_children && $depth === 0) {
      $classes[] = 'has-mega group';
      $this->current_parent_item = $item;
      $this->current_parent_meta = $this->get_parent_meta($item);
    }

    $classes[] = 'sub-menu-item';
    $class_names = join(' ', array_unique(array_filter($classes)));
    $output .= '<li class="' . esc_attr($class_names) . '">';

    // Build link attributes with Tailwind classes
    $link_classes = 'block transition-colors duration-200';
    if ($has_children && $depth === 0) {
      $link_classes .= ' hover:text-primary';
    } else {
      $link_classes .= ' hover:text-primary';
    }

    $atts = [
      'title' => $item->attr_title ?: '',
      'target' => $item->target ?: '',
      'rel' => $item->xfn ?: '',
      'href' => $has_children && $depth === 0 ? '#' : ($item->url ?: '#'),
      'class' => $link_classes,
    ];

    if ($has_children && $depth === 0) {
      $atts['aria-haspopup'] = 'true';
      $atts['aria-expanded'] = 'false';
      $atts['aria-controls'] = 'mega-' . $item->ID;
    }

    $output .= '<a' . $this->build_attributes($atts) . '>';
    $output .= apply_filters('the_title', $item->title, $item->ID);
    $output .= '</a>';

    // Add dropdown arrow for top-level items with children
    if ($has_children && $depth === 0) {
      $output .= '<span class="menu-arrow w-4 h-4 flex items-center justify-center" aria-hidden="true"><svg xmlns="http://www.w3.org/2000/svg" width="12" height="7" viewBox="0 0 12 7" fill="none">
  <path d="M11.3538 1.35403L6.35378 6.35403C6.30735 6.40052 6.2522 6.4374 6.1915 6.46256C6.13081 6.48772 6.06574 6.50067 6.00003 6.50067C5.93433 6.50067 5.86926 6.48772 5.80856 6.46256C5.74786 6.4374 5.69272 6.40052 5.64628 6.35403L0.646284 1.35403C0.552464 1.26021 0.499756 1.13296 0.499756 1.00028C0.499756 0.867596 0.552464 0.740348 0.646284 0.646528C0.740104 0.552707 0.867352 0.5 1.00003 0.5C1.13272 0.5 1.25996 0.552707 1.35378 0.646528L6.00003 5.2934L10.6463 0.646528C10.6927 0.600073 10.7479 0.563222 10.8086 0.538081C10.8693 0.51294 10.9343 0.5 11 0.5C11.0657 0.5 11.1308 0.51294 11.1915 0.538081C11.2522 0.563222 11.3073 0.600073 11.3538 0.646528C11.4002 0.692983 11.4371 0.748133 11.4622 0.80883C11.4874 0.869526 11.5003 0.934581 11.5003 1.00028C11.5003 1.06598 11.4874 1.13103 11.4622 1.19173C11.4371 1.25242 11.4002 1.30757 11.3538 1.35403Z" fill="black"/>
</svg></span>';
    }
  }

  function end_el(&$output, $item, $depth = 0, $args = null)
  {
    $output .= "</li>\n";
  }

  function end_lvl(&$output, $depth = 0, $args = null)
  {
    if ($depth === 0 && $this->current_parent_item) {
      // Close mega panel wrappers opened in start_lvl
      $output .= "        </ul>\n      </div>\n    </div>\n  </div>\n</div>\n";
      // Reset context
      $this->current_parent_item = null;
      $this->current_parent_meta = ['title' => '', 'desc' => '', 'image' => ''];
    } else {
      $output .= "</ul>\n";
    }
  }
}

/**
 * Mobile Menu Walker: outputs a plain nested list suitable for accordion behavior on mobile
 */
class Mobile_Nav_Walker extends Base_Nav_Walker
{
  public function start_lvl(&$output, $depth = 0, $args = null)
  {
    $output .= "\n<ul class=\"submenu\">\n";
  }

  public function end_lvl(&$output, $depth = 0, $args = null)
  {
    $output .= "</ul>\n";
  }

  public function start_el(&$output, $item, $depth = 0, $args = null, $id = 0)
  {
    $has_children = $this->has_children($item);
    $classes = empty($item->classes) ? [] : (array) $item->classes;

    if ($has_children) {
      $classes[] = 'has-submenu';
    }
    $classes[] = 'sub-menu-item !mr-0 px-2 py-5 border-b border-neutral-900/10 last:border-0';
    $class_names = join(' ', array_unique(array_filter($classes)));

    $output .= '<li class="' . esc_attr($class_names) . '">';

    // Build link attributes with Tailwind classes
    $link_classes =
      'block text-lg font-medium text-black transition-colors duration-200 hover:text-primary';

    $atts = [
      'title' => $item->attr_title ?: '',
      'target' => $item->target ?: '',
      'rel' => $item->xfn ?: '',
      'href' => $item->url ?: '#',
      'class' => $link_classes,
    ];

    if ($has_children) {
      $atts['class'] .= ' flex items-center justify-between';
    }

    $output .= '<a' . $this->build_attributes($atts) . '>';
    $output .= apply_filters('the_title', $item->title, $item->ID);

    // Add dropdown arrow for items with children
    if ($has_children) {
      $svg =
        '<svg xmlns="http://www.w3.org/2000/svg" width="14" height="9" viewBox="0 0 14 9" fill="none"><path d="M13.25 1.5L7 7.75L0.75 1.5" stroke="currentColor" stroke-width="1.25" stroke-linecap="round" stroke-linejoin="round"/></svg>';
      $output .=
        '<span class="menu-arrow inline-flex items-center justify-center w-5 h-5 transition-transform duration-300 group-hover:rotate-180" aria-hidden="true">' .
        $svg .
        '</span>';
    }

    $output .= '</a>';
  }

  public function end_el(&$output, $item, $depth = 0, $args = null)
  {
    $output .= "</li>\n";
  }
}
