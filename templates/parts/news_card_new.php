<?php
// Get post thumbnail
if (get_field('post_thumbnail')) {
  $post_featured_img = wp_get_attachment_image_url(get_field('post_thumbnail'), 'full');
} else {
  if (has_post_thumbnail()) {
    $post_featured_img = wp_get_attachment_image_url(get_post_thumbnail_id(), 'full');
  } else {
    $post_featured_img = get_stylesheet_directory_uri() . '/assets/images/placeholder.jpg';
  }
}

// Get categories
$post_categories = [];
$post_categories = get_the_category(get_the_ID());

// If no categories, try getting all taxonomies and their terms
if (empty($post_categories) || is_wp_error($post_categories)) {
  $taxonomies = get_object_taxonomies(get_post_type(get_the_ID()), 'names');

  foreach ($taxonomies as $taxonomy) {
    $terms = get_the_terms(get_the_ID(), $taxonomy);
    if (!empty($terms) && !is_wp_error($terms)) {
      $post_categories = $terms;
      break;
    }
  }
}

// Get calculated read time
$read_time = calculate_post_read_time();

// Get delay from globals (passed by parent template)
$item_delay = isset($GLOBALS['news_card_delay']) ? $GLOBALS['news_card_delay'] : 0;
?>

<div class="news-item rounded-2xl flex flex-col flex-shrink-0 transition-all duration-300  group bottom-right animate-card-3" data-delay="<?php echo number_format((float)$item_delay, 1); ?>">
  <!-- Image Section -->
  <div class="relative overflow-hidden aspect-[2] rounded-t-2xl rounded-tr-2xl">
    <a href="<?php the_permalink(); ?>">
      <img src="<?php echo $post_featured_img; ?>" alt="<?php the_title(); ?>" class=" rounded-t-2xl  lazy-image object-cover w-full h-full scale-100 duration-700 transition-all group-hover:scale-110 overflow-hidden" />
    </a>
  </div>

  <!-- Content Section -->
  <div class="flex flex-col gap-4 items-start justify-between bg-mid-gray px-5 pt-5 pb-7 rounded-bl-2xl relative min-h-[170px]">
    <!-- Categories Pills -->
    <?php if (!empty($post_categories) && !is_wp_error($post_categories)):
      $categories_to_show = array_slice($post_categories, 0, 3); ?>
      <div class="flex flex-wrap gap-2">
        <?php foreach ($categories_to_show as $category): ?>
          <span class="badge">
            <?php echo esc_html($category->name); ?>
          </span>
        <?php endforeach; ?>
      </div>
    <?php
    endif; ?>

    <!-- Title -->
    <a href="<?php the_permalink(); ?>" class="block">
      <div class="text-base font-semibold leading-[19px] text-dark-100">
        <?php echo wp_trim_words(get_the_title(), 10, '...'); ?>
      </div>
    </a>
    <!-- Date and Read Time -->
    <div class="flex justify-between items-center  font-medium text-base text-grey-3 gap-1.5">
      <time datetime="<?php echo get_the_date('c'); ?>">
        <?php echo get_the_date('j M'); ?>
      </time>

      <div class="w-1.5 h-1.5 bg-grey-3 rounded-full"></div>
      <span><?php echo $read_time; ?> min read</span>
    </div>
    <!-- <div class="curve-shape absolute end-0 right-[-1px] bottom-0 w-[55px]"></div> -->
  </div>

</div>