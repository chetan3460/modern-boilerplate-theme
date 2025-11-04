<a href="<?php the_permalink(); ?>" class="fade-in overflow-hidden relative flex flex-col cursor-pointer group w-full bg-gray-100 rounded-[40px] transition-all delay-200 hover:shadow-lg">

    <?php if (get_field('post_thumbnail')) {
      $post_featured_img = wp_get_attachment_image_url(get_field('post_thumbnail'), 'full');
    } else {
      if (has_post_thumbnail()) {
        $post_featured_img = wp_get_attachment_image_url(get_post_thumbnail_id(), 'full');
      } else {
        $post_featured_img = get_stylesheet_directory_uri() . '/assets/images/placeholder.jpg';
      }
    } ?>
    <img src="<?php echo $post_featured_img; ?>" alt="<?php the_title(); ?>" class="lazy-image object-cover absolute inset-0 size-full scale-100 duration-700 transition-all group-hover:scale-110" />

    <!-- Tags Section -->
    <?php
    // Option 1: Use WordPress tags
    $post_tags = get_the_tags();

    // Option 2: Use categories instead (uncomment the line below and comment the line above)
    // $post_tags = get_the_category();

    if ($post_tags && !is_wp_error($post_tags)): ?>
        <div class="absolute top-0 left-0 z-10 mt-8 ml-8 max-lg:ml-4 flex flex-wrap gap-2">
            <?php foreach ($post_tags as $index => $tag):
              // Limit to first 2-3 tags to avoid overcrowding
              if ($index >= 2) {
                break;
              } ?>
                <span class="inline-block px-3 py-1 text-xs font-medium text-white bg-black bg-opacity-60 backdrop-blur-sm rounded-full border border-white border-opacity-30 transition-all group-hover:bg-opacity-80 group-hover:border-opacity-50">
                    <?php echo esc_html($tag->name); ?>
                </span>
            <?php
            endforeach; ?>
        </div>
    <?php endif;
    ?>

    <!-- Dynamic Category Labels -->
    <?php
    $post_categories = [];

    // Method 1: Try standard WordPress categories
    $post_categories = get_the_category(get_the_ID());

    // Method 2: If no categories, try getting all taxonomies and their terms
    if (empty($post_categories) || is_wp_error($post_categories)) {
      $taxonomies = get_object_taxonomies(get_post_type(get_the_ID()), 'names');

      foreach ($taxonomies as $taxonomy) {
        $terms = get_the_terms(get_the_ID(), $taxonomy);
        if (!empty($terms) && !is_wp_error($terms)) {
          $post_categories = $terms;
          break; // Use the first taxonomy that has terms
        }
      }
    }

    // Method 3: Force check specific taxonomy if still empty
    if (empty($post_categories) || is_wp_error($post_categories)) {
      // Try various possible taxonomy names
      $possible_taxonomies = ['category', 'news_category', 'news-category', 'post_tag'];

      foreach ($possible_taxonomies as $tax) {
        $terms = get_the_terms(get_the_ID(), $tax);
        if (!empty($terms) && !is_wp_error($terms)) {
          $post_categories = $terms;
          break;
        }
      }
    }
    ?>

    <div class="desc relative z-10">
        
        <!-- Categories Pills like in the design -->
        <?php if (!empty($post_categories) && !is_wp_error($post_categories)):
          $categories_to_show = array_slice($post_categories, 0, 3);
          // Show up to 3 categories
          ?>
            <div class="flex flex-wrap gap-2 mb-4">
                <?php foreach ($categories_to_show as $category): ?>
                    <span class="inline-block px-3 py-1 text-xs font-medium text-red-600 bg-transparent border border-red-600 rounded-full hover:bg-red-600 hover:text-white transition-all duration-300">
                        <?php echo esc_html($category->name); ?>
                    </span>
                <?php endforeach; ?>
            </div>
        <?php
        endif; ?>
        
        <p class="text-base mt-3 line-clamp-4 pb-3"> <time datetime="<?php echo get_the_date(
          'c'
        ); ?>" class="relative opacity-50"><?php echo get_the_date('j F Y'); ?></time></p>
        <h3 class="capitalize text-[1.625rem] font-medium leading-7 relative transition-all duration-700 bottom-0 left-0 lg:group-hover:bottom-14"><?php the_title(); ?></h3>

        <div class="absolute bg-white text-black  bottom-0 p-5 transition-all duration-700 delay-100 [clip-path:polygon(0%_100%,100%_100%,100%_100%,0%_100%)] group-hover:[clip-path:polygon(0%_0%,100%_0%,100%_100%,0%_100%)] max-lg:hidden">
            <!-- Tags in hover overlay -->
            <?php if ($post_tags && !is_wp_error($post_tags)): ?>
                <div class="flex flex-wrap gap-2 mb-3">
                    <?php foreach ($post_tags as $index => $tag):
                      if ($index >= 3) {
                        break;
                      }
                      // Show up to 3 tags in overlay
                      ?>
                        <span class="inline-block px-2 py-1 text-xs font-medium text-gray-600 bg-gray-100 rounded-full">
                            <?php echo esc_html($tag->name); ?>
                        </span>
                    <?php
                    endforeach; ?>
                </div>
            <?php endif; ?>

            <p class="text-base mt-3 line-clamp-4 pb-3"> <time datetime="<?php echo get_the_date(
              'c'
            ); ?>" class="relative opacity-50"><?php echo get_the_date('j F Y'); ?></time></p>
            <h3 class="capitalize text-[1.625rem] font-medium leading-7 "><?php the_title(); ?></h3>
        </div>
    </div>
    <div class="overlay"></div>
</a>