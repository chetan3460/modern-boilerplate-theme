<?php

/**
 * Single News Post Template
 * 
 * Template for displaying individual news articles
 */

get_header(); ?>
<div id="smooth-wrapper">

  <div id="smooth-content">

    <?php get_template_part('templates/partials/breadcrumbs'); ?>
    <main class="site-main flex flex-col gap-12 lg:gap-y-10 mb-12 lg:mb-24 relative">

      <?php while (have_posts()): the_post(); ?>

        <!-- Hero Section with Featured Image -->
        <section class="news-hero relative fade-in">
          <?php
          $featured_image = '';
          if (get_field('post_thumbnail')) {
            $featured_image = wp_get_attachment_image_url(get_field('post_thumbnail'), 'full');
          } else if (has_post_thumbnail()) {
            $featured_image = wp_get_attachment_image_url(get_post_thumbnail_id(), 'full');
          } else {
            $featured_image = get_stylesheet_directory_uri() . '/assets/images/placeholder.jpg';
          }
          ?>




          <div class="container-fluid  blog-hero-content">
            <!-- Blog Hero Banner -->
            <div class="inner_banner_image-wrapper  relative">
              <div class="max-sm:aspect-[1.9] max-xs:aspect-[1.6]">
                <img src="<?php echo esc_url($featured_image); ?>"
                  alt="<?php the_title(); ?>"
                  class="lazy-image w-full h-full object-cover custom-rounded">
              </div>
              <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/20 to-transparent custom-rounded"></div>
              <div class="curve-shape absolute bottom-[-1px] right-[-1px] w-[135px] sm:w-[185px] sl:w-auto pointer-events-none [backface-visibility:hidden]"></div>
            </div>

            <div class="blog-banner-content absolute top-0 p-5  lg:p-10 flex flex-col gap-3">
              <!-- Date -->
              <time datetime="<?php echo get_the_date('c'); ?>" class="block body-2 text-white">
                <?php echo get_the_date('j F Y'); ?>
              </time>

              <!-- Title -->
              <h1 class="fade-text w-9/12">
                <?php the_title(); ?>
              </h1>

              <!-- Categories -->
              <?php
              $categories = get_the_terms(get_the_ID(), 'news_category');
              if ($categories && !is_wp_error($categories)): ?>
                <div class="flex flex-wrap gap-2">
                  <?php foreach (array_slice($categories, 0, 3) as $category): ?>
                    <span class="inline-block px-3 py-2 text-xs font-semibold text-white bg-white/20 backdrop-blur-sm rounded-full border border-white/30 tracking-normal">
                      <?php echo esc_html($category->name); ?>
                    </span>
                  <?php endforeach; ?>
                </div>
              <?php endif; ?>
            </div>

          </div>
        </section>

        <section>
          <div class="container-fluid">
            <div class="flex lg:flex-row flex-col gap-6">
              <div class="w-full lg:w-8/12 flex flex-col gap-5 md:gap-10">
                <!-- News Blocks Section -->
                <?php if (have_rows('news_panels')): ?>
                  <?php render_blocks('news_panels'); ?>
                <?php endif; ?>
                <!-- Share Section -->
                <div class="flex items-center gap-2">
                  <div class="body-2 text-grey-3">Share</div>
                  <div class="flex gap-1">
                    <a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo urlencode(get_permalink()); ?>"
                      target="_blank" rel="noopener"
                      class="w-10 h-10 bg-[#FFECED] text-white rounded-full flex items-center justify-center hover:opacity-80 transition-opacity">
                      <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16" fill="none">
                        <path d="M12.0013 1.33203H10.0013C9.11725 1.33203 8.2694 1.68322 7.64428 2.30834C7.01916 2.93346 6.66797 3.78131 6.66797 4.66536V6.66536H4.66797V9.33203H6.66797V14.6654H9.33464V9.33203H11.3346L12.0013 6.66536H9.33464V4.66536C9.33464 4.48855 9.40487 4.31898 9.5299 4.19396C9.65492 4.06894 9.82449 3.9987 10.0013 3.9987H12.0013V1.33203Z" fill="#DA000E" />
                      </svg>
                    </a>

                    <a href="https://www.linkedin.com/sharing/share-offsite/?url=<?php echo urlencode(get_permalink()); ?>"
                      target="_blank" rel="noopener"
                      class="w-10 h-10 bg-[#FFECED] text-white rounded-full flex items-center justify-center hover:opacity-80 transition-opacity">
                      <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16" fill="none">
                        <path d="M10.666 5.33203C11.7269 5.33203 12.7443 5.75346 13.4944 6.5036C14.2446 7.25375 14.666 8.27117 14.666 9.33203V13.9987H11.9993V9.33203C11.9993 8.97841 11.8589 8.63927 11.6088 8.38922C11.3588 8.13917 11.0196 7.9987 10.666 7.9987C10.3124 7.9987 9.97326 8.13917 9.72321 8.38922C9.47316 8.63927 9.33268 8.97841 9.33268 9.33203V13.9987H6.66602V9.33203C6.66602 8.27117 7.08744 7.25375 7.83759 6.5036C8.58773 5.75346 9.60515 5.33203 10.666 5.33203Z" fill="#DA000E" />
                        <path d="M4.00065 6H1.33398V14H4.00065V6Z" fill="#DA000E" />
                        <path d="M2.66732 3.9987C3.4037 3.9987 4.00065 3.40174 4.00065 2.66536C4.00065 1.92898 3.4037 1.33203 2.66732 1.33203C1.93094 1.33203 1.33398 1.92898 1.33398 2.66536C1.33398 3.40174 1.93094 3.9987 2.66732 3.9987Z" fill="#DA000E" stroke="#DA000E" stroke-linecap="round" stroke-linejoin="round" />
                      </svg>
                    </a>

                    <a href="https://www.youtube.com/sharing/share-offsite/?url=<?php echo urlencode(get_permalink()); ?>"
                      target="_blank" rel="noopener"
                      class="w-10 h-10 bg-[#FFECED] text-white rounded-full flex items-center justify-center hover:opacity-80 transition-opacity">
                      <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16" fill="none">
                        <path d="M15.0264 4.2813C14.9472 3.96491 14.7859 3.67502 14.5588 3.44091C14.3317 3.2068 14.0469 3.03676 13.7331 2.94797C12.5864 2.66797 7.99973 2.66797 7.99973 2.66797C7.99973 2.66797 3.41306 2.66797 2.2664 2.97464C1.95256 3.06343 1.66771 3.23346 1.44063 3.46757C1.21354 3.70168 1.05226 3.99158 0.973063 4.30797C0.763206 5.47167 0.660554 6.65217 0.666397 7.83464C0.658916 9.026 0.761575 10.2155 0.973063 11.388C1.06037 11.6945 1.22527 11.9734 1.45183 12.1976C1.67838 12.4218 1.95894 12.5838 2.2664 12.668C3.41306 12.9746 7.99973 12.9746 7.99973 12.9746C7.99973 12.9746 12.5864 12.9746 13.7331 12.668C14.0469 12.5792 14.3317 12.4091 14.5588 12.175C14.7859 11.9409 14.9472 11.651 15.0264 11.3346C15.2346 10.1797 15.3373 9.00819 15.3331 7.83464C15.3405 6.64327 15.2379 5.45377 15.0264 4.2813Z" fill="#DA000E" />
                        <path d="M6.5 10.0123L10.3333 7.83234L6.5 5.65234V10.0123Z" fill="#FFECED" />
                      </svg>
                    </a>

                    <div onclick="navigator.share ? navigator.share({title: '<?php echo esc_js(get_the_title()); ?>', url: '<?php echo esc_js(get_permalink()); ?>'}) : navigator.clipboard.writeText('<?php echo esc_js(get_permalink()); ?>').then(() => alert('Link copied!'))"
                      class="w-10 h-10 bg-[#FFECED] text-white rounded-full flex items-center justify-center hover:opacity-80 transition-opacity">
                      <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16" fill="none">
                        <g clip-path="url(#clip0_370_2065)">
                          <path d="M6.79439 12.3268L6.47502 12.6462C6.20412 12.9171 5.88247 13.132 5.52845 13.2785C5.17443 13.425 4.795 13.5003 4.41187 13.5C4.02874 13.4997 3.64942 13.4239 3.29563 13.2768C2.94183 13.1298 2.6205 12.9144 2.35002 12.6431C1.80501 12.0962 1.4993 11.3553 1.5 10.5832C1.5007 9.81114 1.80776 9.07087 2.35377 8.52496L4.52502 6.35371C4.79559 6.08306 5.11684 5.86836 5.47041 5.72188C5.82397 5.57539 6.20293 5.5 6.58564 5.5C6.96835 5.5 7.34731 5.57539 7.70088 5.72188C8.05444 5.86836 8.37569 6.08306 8.64627 6.35371C9.03125 6.737 9.30068 7.22084 9.42377 7.74996" stroke="#DA000E" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                          <path d="M9.20555 3.67309L9.52492 3.35371C9.7955 3.08306 10.1167 2.86836 10.4703 2.72188C10.8239 2.57539 11.2028 2.5 11.5855 2.5C11.9683 2.5 12.3472 2.57539 12.7008 2.72188C13.0543 2.86836 13.3756 3.08306 13.6462 3.35371C13.9168 3.62429 14.1315 3.94553 14.278 4.2991C14.4245 4.65267 14.4999 5.03163 14.4999 5.41434C14.4999 5.79704 14.4245 6.176 14.278 6.52957C14.1315 6.88314 13.9168 7.20438 13.6462 7.47496L12.1212 8.99996L11.4749 9.64621C11.204 9.91715 10.8824 10.132 10.5284 10.2785C10.1743 10.425 9.79491 10.5003 9.41177 10.5C9.02864 10.4997 8.64933 10.4239 8.29553 10.2768C7.94174 10.1298 7.6204 9.91443 7.34992 9.64308C6.96713 9.25995 6.69912 8.77741 6.57617 8.24996" stroke="#DA000E" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                        </g>
                        <defs>
                          <clipPath id="clip0_370_2065">
                            <rect width="16" height="16" fill="white" />
                          </clipPath>
                        </defs>
                      </svg>
                    </div>


                    <a href="https://instagram.com/intent/tweet?url=<?php echo urlencode(get_permalink()); ?>&text=<?php echo urlencode(get_the_title()); ?>"
                      target="_blank" rel="noopener"
                      class="w-10 h-10 bg-[#FFECED] text-white rounded-full flex items-center justify-center hover:opacity-80 transition-opacity">
                      <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16" fill="none">
                        <path d="M11.332 1.33594H4.66536C2.82442 1.33594 1.33203 2.82832 1.33203 4.66927V11.3359C1.33203 13.1769 2.82442 14.6693 4.66536 14.6693H11.332C13.173 14.6693 14.6654 13.1769 14.6654 11.3359V4.66927C14.6654 2.82832 13.173 1.33594 11.332 1.33594Z" fill="#DA000E" />
                        <path d="M10.6658 7.5802C10.7481 8.13503 10.6533 8.70168 10.395 9.19954C10.1367 9.69741 9.72792 10.1011 9.2269 10.3533C8.72589 10.6055 8.15812 10.6933 7.60434 10.6042C7.05057 10.515 6.53899 10.2536 6.14238 9.85697C5.74577 9.46036 5.48431 8.94878 5.3952 8.39501C5.30609 7.84124 5.39386 7.27346 5.64604 6.77245C5.89821 6.27144 6.30194 5.86269 6.79981 5.60436C7.29768 5.34603 7.86432 5.25126 8.41915 5.33353C8.9851 5.41746 9.50905 5.68118 9.91362 6.08574C10.3182 6.4903 10.5819 7.01425 10.6658 7.5802Z" fill="#FFECED" />
                        <path d="M11.668 4.33594H11.6746" stroke="#FFECED" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" />
                      </svg>
                    </a>


                    <a href="https://twitter.com/intent/tweet?url=<?php echo urlencode(get_permalink()); ?>&text=<?php echo urlencode(get_the_title()); ?>"
                      target="_blank" rel="noopener"
                      class="w-10 h-10 bg-[#FFECED] text-white rounded-full flex items-center justify-center hover:opacity-80 transition-opacity">
                      <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16" fill="none">
                        <g clip-path="url(#clip0_370_2077)">
                          <path d="M3 2.5H6L13 13.5H10L3 2.5Z" stroke="#DA000E" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                          <path d="M7.1175 8.9707L3 13.5001" stroke="#DA000E" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                          <path d="M12.9993 2.5L8.88184 7.02938" stroke="#DA000E" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                        </g>
                        <defs>
                          <clipPath id="clip0_370_2077">
                            <rect width="16" height="16" fill="white" />
                          </clipPath>
                        </defs>
                      </svg>
                    </a>




                  </div>
                </div>
              </div>
              <div class="w-full lg:w-4/12">
                <!-- Sidebar -->
                <div class="sticky top-8">
                  <div class="h3 font-semibold mb-3">Related Blogs</div>

                  <?php
                  // Get related news posts
                  $current_categories = get_the_terms(get_the_ID(), 'news_category');
                  $category_ids = array();
                  if ($current_categories && !is_wp_error($current_categories)) {
                    foreach ($current_categories as $cat) {
                      $category_ids[] = $cat->term_id;
                    }
                  }

                  $related_posts = new WP_Query(array(
                    'post_type' => 'news',
                    'posts_per_page' => 6, // Increased for mobile slider
                    'post__not_in' => array(get_the_ID()),
                    'tax_query' => array(
                      array(
                        'taxonomy' => 'news_category',
                        'field' => 'term_id',
                        'terms' => $category_ids,
                        'operator' => 'IN'
                      )
                    ),
                    'meta_key' => 'post_date',
                    'orderby' => 'date',
                    'order' => 'DESC'
                  ));

                  // Fallback query if no related posts
                  if (!$related_posts->have_posts()) {
                    $related_posts = new WP_Query(array(
                      'post_type' => 'news',
                      'posts_per_page' => 6,
                      'post__not_in' => array(get_the_ID()),
                      'orderby' => 'date',
                      'order' => 'DESC'
                    ));
                  }

                  if ($related_posts->have_posts()): ?>

                    <!-- Mobile Swiper Slider -->
                    <div class="related-blogs-slider lg:hidden">
                      <div class="swiper" data-component="RelatedBlogsSlider">
                        <div class="swiper-wrapper">
                          <?php while ($related_posts->have_posts()): $related_posts->the_post(); ?>
                            <div class="swiper-slide">
                              <article class="group">
                                <a href="<?php the_permalink(); ?>" class="flex bg-neutral-1 rounded-2xl h-full">
                                  <?php if (has_post_thumbnail()): ?>
                                    <div class="w-4/12 overflow-hidden rounded-tl-2xl rounded-bl-2xl  min-w-[120px] aspect-[1.5]">
                                      <img src="<?php echo get_the_post_thumbnail_url(null, 'medium'); ?>"
                                        alt="<?php the_title(); ?>"
                                        class="size-full object-cover transition-transform duration-300 group-hover:scale-105">
                                    </div>
                                  <?php endif; ?>

                                  <div class="w-8/12 p-3 pb-4 flex flex-col justify-between">
                                    <div>
                                      <time datetime="<?php echo get_the_date('c'); ?>" class="text-xs text-[#555] tracking-normal">
                                        <?php echo get_the_date('F Y'); ?>
                                      </time>

                                      <div class="text-sm font-medium text-black line-clamp-2 mt-1 group-hover:text-primary transition-colors">
                                        <?php the_title(); ?>
                                      </div>
                                    </div>

                                    <span class="inline-flex items-center text-sm text-primary font-semibold mt-2">
                                      Read More
                                    </span>
                                  </div>
                                </a>
                              </article>
                            </div>
                          <?php endwhile; ?>
                        </div>
                      </div>

                      <!-- Mobile Navigation -->
                      <div class="mt-4 flex justify-center items-center gap-3">
                        <div class="related-blogs-prev swiper-btn-prev">
                          <svg xmlns="http://www.w3.org/2000/svg" width="9" height="7" viewBox="0 0 9 7" fill="none">
                            <path d="M7.92214 3.18291C8.16739 3.18291 8.36621 3.38173 8.36621 3.62699C8.36621 3.87224 8.16739 4.07106 7.92214 4.07106L1.66704 4.07106L3.79543 6.19944C3.96885 6.37286 3.96885 6.65403 3.79543 6.82745C3.62201 7.00087 3.34084 7.00087 3.16742 6.82745L0.594961 4.255C0.24812 3.90816 0.24812 3.34581 0.594961 2.99897L3.16742 0.426516C3.34084 0.253095 3.62201 0.253096 3.79543 0.426516C3.96885 0.599937 3.96885 0.881107 3.79543 1.05453L1.66705 3.18291L7.92214 3.18291Z" fill="#DA000E" />
                          </svg>
                        </div>

                        <div class="related-blogs-pagination swiper-pagination-custom text-primary text-xs font-medium !w-4 !h-4"></div>

                        <div class="related-blogs-next swiper-btn-next">
                          <svg xmlns="http://www.w3.org/2000/svg" width="9" height="7" viewBox="0 0 9 7" fill="none">
                            <path d="M1.15891 3.18291C0.913661 3.18291 0.714844 3.38173 0.714844 3.62699C0.714844 3.87224 0.913661 4.07106 1.15892 4.07106L7.41401 4.07106L5.28562 6.19944C5.1122 6.37286 5.1122 6.65403 5.28562 6.82745C5.45904 7.00087 5.74021 7.00087 5.91364 6.82745L8.48609 4.255C8.83293 3.90816 8.83294 3.34581 8.48609 2.99897L5.91363 0.426516C5.74021 0.253095 5.45904 0.253096 5.28562 0.426516C5.1122 0.599937 5.1122 0.881107 5.28562 1.05453L7.41401 3.18291L1.15891 3.18291Z" fill="#DA000E" />
                          </svg>
                        </div>
                      </div>
                    </div>

                    <!-- Desktop Layout -->
                    <div class="hidden lg:block space-y-6">
                      <?php
                      $related_posts->rewind_posts();
                      $count = 0;
                      while ($related_posts->have_posts() && $count < 3):
                        $related_posts->the_post();
                        $count++;
                      ?>
                        <article class="group">
                          <a href="<?php the_permalink(); ?>" class="flex bg-neutral-1 rounded-2xl h-full">
                            <?php if (has_post_thumbnail()): ?>
                              <div class="w-4/12 overflow-hidden rounded-tl-2xl rounded-bl-2xl aspect-[1.5] min-w-[120px]">
                                <img src="<?php echo get_the_post_thumbnail_url(null, 'medium'); ?>"
                                  alt="<?php the_title(); ?>"
                                  class="w-full h-full object-cover transition-transform duration-300 group-hover:scale-105">
                              </div>
                            <?php endif; ?>

                            <div class="w-8/12 p-3 pb-4 flex flex-col">
                              <div>
                                <time datetime="<?php echo get_the_date('c'); ?>" class="text-xs text-[#555] tracking-normal">
                                  <?php echo get_the_date('F Y'); ?>
                                </time>

                                <div class="text-sm font-medium text-black line-clamp-2 mt-1 group-hover:text-primary transition-colors">
                                  <?php the_title(); ?>
                                </div>
                              </div>

                              <span class="inline-flex items-center text-sm text-primary font-semibold mt-2">
                                Read More
                              </span>
                            </div>
                          </a>
                        </article>
                      <?php endwhile; ?>
                    </div>

                  <?php else: ?>
                    <div class="text-center text-gray-500">
                      <p>No related posts found.</p>
                    </div>
                  <?php endif; ?>
                  <?php wp_reset_postdata(); ?>
                </div>
              </div>
            </div>

          </div>
        </section>


      <?php endwhile; ?>
    </main>
    <?php get_footer(); ?>
  </div>
</div>