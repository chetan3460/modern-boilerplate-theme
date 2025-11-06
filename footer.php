   <?php
   // Footer fields
   $footer_logo = get_field('footer_logo', 'option');
   $contact_title = get_field('contact_title', 'option');
   $phone_no = get_field('phone_no', 'option');
   $email = get_field('email', 'option');
   $page_links = get_field('page_links', 'option');
   $follow_us = get_field('follow_us', 'option');

   $social_links = isset($follow_us['social_links']) ? $follow_us['social_links'] : null;
   $policy = get_field('policy', 'option');
   ?>
   <footer class="footer text-black relative border-t border-[#F1F3F7]">
     <div class="container-fluid xl:px-24 lg:px-14 px-5">
       <div class="grid grid-cols-12">
         <div class="col-span-12">
           <div class="pt-8 pb-6">
             <div class="flex md:flex-row flex-col justify-between gap-5 md:gap-7">
               <!-- Logo Section -->
               <div class="lg:col-span-4 md:col-span-6">
                 <a href="<?= get_home_url() ?>" class="text-[22px] focus:outline-none">
                   <?php if ($footer_logo): ?>
                     <img loading="lazy" src="<?= $footer_logo ?>" alt="<?php bloginfo(
  'name'
); ?>" class="">
                   <?php endif; ?>
                 </a>
               </div><!--end col-->

               <!-- Contact Section -->
               <?php if ($phone_no || $email || $social_links || $contact_title): ?>
               <div class="lg:col-span-3 md:col-span-6">
                 <?php if ($contact_title): ?>
                   <div class="text-base text-black font-semibold"><?php echo esc_html(
                     $contact_title
                   ); ?></div>
                 <?php endif; ?>

                 <ul class="list-none footer-list flex flex-col mt-4 gap-4">
                   <?php if ($phone_no): ?>
                     <li class="flex items-center gap-1">
                       <svg xmlns="http://www.w3.org/2000/svg" width="16" height="15" viewBox="0 0 16 15" fill="none">
                         <path d="M14.6466 11.5258C14.6466 11.7658 14.5933 12.0125 14.4799 12.2525C14.3666 12.4925 14.2199 12.7192 14.0266 12.9325C13.6999 13.2925 13.3399 13.5525 12.9333 13.7192C12.5333 13.8858 12.0999 13.9725 11.6333 13.9725C10.9533 13.9725 10.2266 13.8125 9.45992 13.4858C8.69325 13.1592 7.92658 12.7192 7.16658 12.1658C6.39992 11.6058 5.67325 10.9858 4.97992 10.2992C4.29325 9.60583 3.67325 8.87916 3.11992 8.11916C2.57325 7.35916 2.13325 6.59916 1.81325 5.84583C1.49325 5.08583 1.33325 4.35916 1.33325 3.66583C1.33325 3.21249 1.41325 2.77916 1.57325 2.37916C1.73325 1.97249 1.98659 1.59916 2.33992 1.26583C2.76659 0.845827 3.23325 0.63916 3.72659 0.63916C3.91325 0.63916 4.09992 0.67916 4.26659 0.75916C4.43992 0.83916 4.59325 0.95916 4.71325 1.13249L6.25992 3.31249C6.37992 3.47916 6.46658 3.63249 6.52658 3.77916C6.58658 3.91916 6.61992 4.05916 6.61992 4.18583C6.61992 4.34583 6.57325 4.50583 6.47992 4.65916C6.39325 4.81249 6.26658 4.97249 6.10658 5.13249L5.59992 5.65916C5.52658 5.73249 5.49325 5.81916 5.49325 5.92583C5.49325 5.97916 5.49992 6.02583 5.51325 6.07916C5.53325 6.13249 5.55325 6.17249 5.56659 6.21249C5.68659 6.43249 5.89325 6.71916 6.18658 7.06583C6.48658 7.41249 6.80658 7.76583 7.15325 8.11916C7.51325 8.47249 7.85992 8.79916 8.21325 9.09916C8.55992 9.39249 8.84658 9.59249 9.07325 9.71249C9.10658 9.72583 9.14658 9.74583 9.19325 9.76583C9.24658 9.78583 9.29992 9.79249 9.35992 9.79249C9.47325 9.79249 9.55992 9.75249 9.63325 9.67916L10.1399 9.17916C10.3066 9.01249 10.4666 8.88583 10.6199 8.80583C10.7733 8.71249 10.9266 8.66583 11.0933 8.66583C11.2199 8.66583 11.3533 8.69249 11.4999 8.75249C11.6466 8.81249 11.7999 8.89916 11.9666 9.01249L14.1733 10.5792C14.3466 10.6992 14.4666 10.8392 14.5399 11.0058C14.6066 11.1725 14.6466 11.3392 14.6466 11.5258Z" stroke="#292D32" stroke-miterlimit="10" />
                       </svg>
                       <a href="tel:<?php echo esc_attr(
                         $phone_no
                       ); ?>" class="text-base font-normal text-[#333] hover:text-primary duration-500 ease-in-out"><?php echo esc_html(
  $phone_no
); ?></a>
                     </li>
                   <?php endif; ?>

                   <?php if ($email): ?>
                     <li class="flex items-center gap-1">
                       <svg xmlns="http://www.w3.org/2000/svg" width="16" height="17" viewBox="0 0 16 17" fill="none">
                         <path d="M11.3333 13.9735H4.66659C2.66659 13.9735 1.33325 12.9735 1.33325 10.6401V5.97347C1.33325 3.64014 2.66659 2.64014 4.66659 2.64014H11.3333C13.3333 2.64014 14.6666 3.64014 14.6666 5.97347V10.6401C14.6666 12.9735 13.3333 13.9735 11.3333 13.9735Z" stroke="#292D32" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round" />
                         <path d="M11.3334 6.30664L9.24674 7.97331C8.56008 8.51997 7.43341 8.51997 6.74674 7.97331L4.66675 6.30664" stroke="#292D32" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round" />
                       </svg>
                       <a href="mailto:<?php echo esc_attr(
                         $email
                       ); ?>" class="text-base font-normal text-[#333] hover:text-primary duration-500 ease-in-out"><?php echo esc_html(
  $email
); ?></a>
                     </li>
                   <?php endif; ?>
                 </ul>

                 <!-- Social Media -->
                 <?php if ($social_links): ?>
                   <div class="mt-4 flex space-x-3">
                     <?php if (
                       isset($social_links['instagram']) &&
                       !empty($social_links['instagram'])
                     ): ?>
                       <a href="<?php echo esc_url($social_links['instagram']); ?>"
                         class="inline-flex items-center justify-center w-8 h-8 bg-primary text-white rounded transition-all duration-300"
                         target="_blank" rel="noopener noreferrer" aria-label="Instagram (opens in new tab)">
                         <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                           <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z" />
                         </svg>
                       </a>
                     <?php endif; ?>

                     <?php if (
                       isset($social_links['facebook']) &&
                       !empty($social_links['facebook'])
                     ): ?>
                       <a href="<?php echo esc_url($social_links['facebook']); ?>"
                         class="inline-flex items-center justify-center  w-8 h-8 bg-primary  text-white rounded transition-colors duration-300"
                         target="_blank" rel="noopener noreferrer" aria-label="Facebook (opens in new tab)">
                         <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                           <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z" />
                         </svg>
                       </a>
                     <?php endif; ?>

                     <?php if (
                       isset($social_links['linkedin']) &&
                       !empty($social_links['linkedin'])
                     ): ?>
                       <a href="<?php echo esc_url($social_links['linkedin']); ?>"
                         class="inline-flex items-center justify-center w-8 h-8 bg-primary  text-white rounded transition-colors duration-300"
                         target="_blank" rel="noopener noreferrer" aria-label="LinkedIn (opens in new tab)">
                         <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 14 14" fill="none">
                           <path d="M0 2.18896C0 1.74002 0.157662 1.36964 0.472973 1.07785C0.788284 0.786036 1.1982 0.640137 1.7027 0.640137C2.1982 0.640137 2.59909 0.783787 2.90541 1.07111C3.22072 1.36741 3.37838 1.75348 3.37838 2.22936C3.37838 2.66034 3.22523 3.01948 2.91892 3.3068C2.60361 3.6031 2.18919 3.75125 1.67568 3.75125H1.66216C1.16666 3.75125 0.76577 3.6031 0.45946 3.3068C0.153149 3.01051 0 2.63789 0 2.18896ZM0.175676 13.9735V4.97684H3.17568V13.9735H0.175676ZM4.83784 13.9735H7.83784V8.9499C7.83784 8.63564 7.87388 8.39321 7.94595 8.22263C8.07207 7.91735 8.26351 7.65921 8.52027 7.44822C8.77703 7.23721 9.0991 7.13172 9.48649 7.13172C10.4955 7.13172 11 7.8096 11 9.16539V13.9735H14V8.81522C14 7.48637 13.6847 6.47852 13.0541 5.79165C12.4234 5.10478 11.5901 4.76135 10.5541 4.76135C9.39189 4.76135 8.48649 5.25966 7.83784 6.2563V6.28323H7.82432L7.83784 6.2563V4.97684H4.83784C4.85585 5.26415 4.86487 6.15752 4.86487 7.65697C4.86487 9.1564 4.85585 11.2619 4.83784 13.9735Z" fill="white" />
                         </svg>
                       </a>
                     <?php endif; ?>

                     <?php if (
                       isset($social_links['twitter']) &&
                       !empty($social_links['twitter'])
                     ): ?>
                       <a href="<?php echo esc_url($social_links['twitter']); ?>"
                         class="inline-flex items-center justify-center w-8 h-8 bg-primary text-white rounded transition-colors duration-300"
                         target="_blank" rel="noopener noreferrer" aria-label="Twitter (opens in new tab)">
                         <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                           <path d="M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723c-.951.555-2.005.959-3.127 1.184a4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.06a4.923 4.923 0 003.946 4.827 4.996 4.996 0 01-2.212.085 4.936 4.936 0 004.604 3.417 9.867 9.867 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 007.557 2.209c9.053 0 13.998-7.496 13.998-13.985 0-.21 0-.42-.015-.63A9.935 9.935 0 0024 4.59z" />
                         </svg>
                       </a>
                     <?php endif; ?>
                   </div>
                 <?php endif; ?>
               </div><!--end col-->
               <?php endif; ?>

              <?php
              // Get all page links from ACF
              $all_links = [];
              if ($page_links && have_rows('page_links', 'option')):
                while (have_rows('page_links', 'option')):
                  the_row();
                  $page_link = get_sub_field('page_link');
                  if ($page_link):
                    $all_links[] = $page_link;
                  endif;
                endwhile;
              endif;

              // Only show footer link columns if links exist
              if (!empty($all_links)):
                // Split links into 2 groups for 2 page link columns
                $links_per_column = ceil(count($all_links) / 2);
                $column_1 = array_slice($all_links, 0, $links_per_column);
                $column_2 = array_slice($all_links, $links_per_column);
              endif;
              ?>

               <?php if (!empty($all_links)): ?>
                 <!-- Page Links Column 1 -->
                 <div class="lg:col-span-2 md:col-span-6">
                   <ul class="list-none footer-list pt-4 md:pt-0 border-t md:border-t-0 border-black/10">
                     <?php foreach ($column_1 as $index => $link): ?>
                       <li class="<?php echo $index > 0 ? 'mt-[10px]' : ''; ?>">
                         <a href="<?php echo esc_url($link['url']); ?>"
                           class="text-sm md:text-base !hover:text-primary text-black font-semibold  duration-500 ease-in-out"
                           <?php echo isset($link['target']) && $link['target']
                             ? 'target="' . esc_attr($link['target']) . '"'
                             : ''; ?>>
                           <?php echo esc_html($link['title']); ?>
                         </a>
                       </li>
                     <?php endforeach; ?>
                   </ul>
                 </div><!--end col-->

                 <!-- Page Links Column 2 -->
                 <div class="lg:col-span-3 md:col-span-6">
                   <ul class="list-none footer-list pt-4 md:pt-0 border-t md:border-t-0 border-black/10">
                     <?php foreach ($column_2 as $index => $link): ?>
                       <li class="<?php echo $index > 0 ? 'mt-4' : ''; ?>">
                         <a href="<?php echo esc_url($link['url']); ?>"
                           class="text-sm md:text-base !hover:text-primary text-black font-semibold  duration-500 ease-in-out"
                           <?php echo isset($link['target']) && $link['target']
                             ? 'target="' . esc_attr($link['target']) . '"'
                             : ''; ?>>
                           <?php echo esc_html($link['title']); ?>
                         </a>
                       </li>
                     <?php endforeach; ?>
                   </ul>
                 </div><!--end col-->
               <?php endif; ?>
               <!--end col-->
             </div><!--end grid-->
           </div><!--end col-->
         </div>
       </div>
     </div>
     <div class="py-6 px-0 border-t border-[#F1F3F7]">
       <div class="container-fluid ">
         <div class="flex md:flex-row flex-col items-start md:items-center justify-start md:justify-between gap-4">
           <!-- Policy Link -->
           <div>
             <?php $policy_link =
               $policy && !empty($policy['url'])
                 ? $policy
                 : [
                   'url' => home_url('/privacy-policy'),
                   'title' => 'Legal & Privacy Policy',
                   'target' => '',
                 ]; ?>
             <a href="<?php echo esc_url($policy_link['url']); ?>"
               class="text-sm md:text-base text-grey-3 hover:text-primary duration-500 ease-in-out"
               <?php echo isset($policy_link['target']) && $policy_link['target']
                 ? 'target="' . esc_attr($policy_link['target']) . '"'
                 : ''; ?>>
               <?php echo esc_html($policy_link['title']); ?>
             </a>
           </div>

           <!-- Copyright -->
           <div>
             <p class="mb-0 text-sm md:text-base text-grey-3">
               © <span id="current-year"></span> <?php bloginfo('name'); ?>. All rights reserved
             </p>
           </div>

           <!-- Designed by -->
           <div class="flex  gap-1 items-center">
             <img src="<?php echo get_vite_asset('images/SR.svg'); ?>" alt="Announcement">
             <span class="text-grey-3">Designed by</span>
             <a href="https://screenroot.com" target="_blank" class="text-[#000] hover:text-primary duration-500 ease-in-out">ScreenRoot</a>
           </div>
         </div>
       </div>
     </div>
   </footer>
   <!--end footer-->
   <!-- Footer End -->

   <?php
   // 2025 Performance Optimizations - Prefetch likely next pages
   if (function_exists('add_prefetch_hints')) {
     add_prefetch_hints();
   }

   // Add intersection observer for lazy loading components
   if (function_exists('add_intersection_observer_loader')) {
     add_intersection_observer_loader();
   }

   wp_footer();
   ?>

   <!-- Footer utilities: Vite badge, year updates, accessibility -->
   <script type="module" data-theme-url="<?php echo get_template_directory_uri(); ?>">
     import FooterUtils from '<?php echo get_template_directory_uri(); ?>/assets/js/utils/footer-utils.js';
     
     if (document.readyState === 'loading') {
       document.addEventListener('DOMContentLoaded', () => new FooterUtils());
     } else {
       new FooterUtils();
     }
   </script>

   </body>

   </html>