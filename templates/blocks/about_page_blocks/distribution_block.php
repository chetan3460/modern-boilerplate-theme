<?php

/**
 * Distribution Block Template
 * Interactive map showing global distribution network
 *
 * ACF Fields:
 * - hide_block (true_false)
 * - title (text)
 * - description (wysiwyg)
 * - cta (link)
 * - international_locations (repeater)
 *   - location_name (text)
 *   - position_x (number)
 *   - position_y (number)
 * - india_locations (repeater)
 *   - location_name (text)
 *   - position_x (number)
 *   - position_y (number)
 */

$title = get_sub_field('title') ?: 'Strategic reach, seamless supply';
$description = get_sub_field('description') ?: "RPL\'s distribution network spans India and South Asia, with offices in key states and reach into Bangladesh, Nepal, and Sri Lanka. This ensures quick delivery, seamless service, and trusted access to advanced resin solutions across industries.";
$cta = get_sub_field('cta');

// Get location data
$international_locations = get_sub_field('international_locations') ?: [];
$india_locations = get_sub_field('india_locations') ?: [];

// Default locations if none are set
if (empty($international_locations)) {
  $international_locations = [
    ['location_name' => 'South America', 'position_x' => 17, 'position_y' => 70],
    ['location_name' => 'UK', 'position_x' => 40, 'position_y' => 19],
    ['location_name' => 'Bangladesh', 'position_x' => 69, 'position_y' => 40],
    ['location_name' => 'Mumbai', 'position_x' => 63, 'position_y' => 44],
    ['location_name' => 'UAE', 'position_x' => 58, 'position_y' => 44],
    ['location_name' => 'Africa', 'position_x' => 46, 'position_y' => 57],
  ];
}

if (empty($india_locations)) {
  $india_locations = [
    ['location_name' => 'Chandigarh', 'position_x' => 34, 'position_y' => 24],
    ['location_name' => 'Delhi', 'position_x' => 34.5, 'position_y' => 29],
    ['location_name' => 'Pune', 'position_x' => 23, 'position_y' => 68],
    ['location_name' => 'Kolkata', 'position_x' => 70, 'position_y' => 52],
    ['location_name' => 'Mumbai', 'position_x' => 17, 'position_y' => 65],
    ['location_name' => 'Chennai', 'position_x' => 43, 'position_y' => 84],
    ['location_name' => 'Bangalore', 'position_x' => 34, 'position_y' => 84],
  ];
}

// Include hide block functionality
include locate_template('templates/blocks/hide_block.php', false, false);

if (!$hide_block): ?>
  <script>
    window.resplastTheme = window.resplastTheme || {};
    window.resplastTheme.themeUri = '<?php echo get_template_directory_uri(); ?>';
  </script>
  <section class="distribution_block fade-in" data-component="DistributionMap">
    <div class="container-fluid">

      <!-- Header Section -->
      <div class="section-heading text-center">
        <?php if ($title): ?>
          <h2 class="fade-text">
            <?php echo esc_html($title); ?>
          </h2>
        <?php endif; ?>

        <?php if ($description): ?>
          <div class="max-w-5xl mx-auto text-lg text-gray-600 leading-relaxed mb-8 anim-uni-in-up">
            <?php echo wp_kses_post($description); ?>
          </div>
        <?php endif; ?>

        <!-- Tab Navigation -->
        <div class="distribution-tabs flex justify-center mb-8">
          <div class="bg-gray-100 rounded-full">
            <button
              class="distribution-tab active px-8 py-3 rounded-full font-semibold transition-all duration-300"
              data-tab="international">
              International
            </button>
            <button
              class="distribution-tab px-8 py-3 rounded-full font-semibold transition-all duration-300"
              data-tab="india">
              India
            </button>
          </div>
        </div>

        <!-- Legend -->
        <div class="distribution-legend flex justify-center items-center gap-8 mb-8 flex-wrap">
          <div class="legend-item flex items-center gap-3">
            <div class="legend-dot w-4 h-4 rounded-full bg-red-600 shadow-sm"></div>
            <span class="legend-label text-gray-700 font-medium">Distributors</span>
          </div>
          <div class="legend-item sales-team-legend hidden flex items-center gap-3 opacity-100 transition-opacity duration-300">
            <div class="legend-dot w-4 h-4 rounded-full bg-[#5FBFF6] shadow-sm"></div>
            <span class="legend-label text-gray-700 font-medium">Sales Team</span>
          </div>
        </div>

        <style>
          .distribution-legend .legend-item:only-child {
            margin: 0 auto;
          }

          .sales-team-legend.hidden {
            display: none;
          }
        </style>


      </div>

      <!-- Map Container -->
      <div class="distribution-map-wrapper">
        <div class="distribution-map-container">
          <!-- World Map Background -->
          <div class="map-background relative bg-contain bg-center bg-no-repeat"
            style="background-image: url('<?php echo get_template_directory_uri(); ?>/assets/images/about/International.png');">

            <!-- International Locations -->
            <div class="locations-container international-locations active" data-locations="international">
              <?php foreach ($international_locations as $location): ?>
                <div class="location-pin international-pin"
                  style="left: <?php echo esc_attr($location['position_x']); ?>%; top: <?php echo esc_attr($location['position_y']); ?>%;"
                  data-location="<?php echo esc_attr($location['location_name']); ?>"
                  data-marker="red">
                  <div class="pin-marker">
                    <svg xmlns="http://www.w3.org/2000/svg" width="25" height="33" viewBox="0 0 25 33" fill="none">
                      <g clip-path="url(#clip0_609_13303)">
                        <path d="M13.6475 32.0696C16.8935 27.9452 24.2967 17.9492 24.2967 12.3344C24.2967 5.5248 18.855 0 12.1479 0C5.44069 0 -0.000976562 5.5248 -0.000976562 12.3344C-0.000976562 17.9492 7.40222 27.9452 10.6482 32.0696C11.4265 33.0525 12.8692 33.0525 13.6475 32.0696ZM12.1479 16.4459C9.91425 16.4459 8.09825 14.6022 8.09825 12.3344C8.09825 10.0667 9.91425 8.22296 12.1479 8.22296C14.3815 8.22296 16.1975 10.0667 16.1975 12.3344C16.1975 14.6022 14.3815 16.4459 12.1479 16.4459Z" fill="#DA000E" />
                      </g>
                      <defs>
                        <clipPath id="clip0_609_13303">
                          <rect width="24.2977" height="32.8919" fill="white" />
                        </clipPath>
                      </defs>
                    </svg>
                    <defs>
                      <clipPath id="clip0_718_5607">
                        <rect width="24.2977" height="32.8919" fill="white" />
                      </clipPath>
                    </defs>
                    </svg>
                  </div>
                  <div class="pin-label">
                    <span class="pin-type">Distributors</span>
                    <span class="pin-location"><?php echo esc_html($location['location_name']); ?></span>
                  </div>
                </div>
              <?php endforeach; ?>
            </div>

            <!-- India Locations -->
            <div class="locations-container india-locations" data-locations="india">
              <!-- India Distributors (Red Dots) -->
              <?php
              // India distributor locations
              $india_distributors = [
                ['location_name' => 'Hyderabad', 'position_x' => 40, 'position_y' => 73],
                ['location_name' => 'Ahmedabad', 'position_x' => 16, 'position_y' => 53],
                ['location_name' => 'Sivakasi', 'position_x' => 36, 'position_y' => 94],
                ['location_name' => 'Nagpur', 'position_x' => 43, 'position_y' => 56],
              ];

              foreach ($india_distributors as $location):
              ?>
                <div class="location-pin international-pin"
                  style="left: <?php echo esc_attr($location['position_x']); ?>%; top: <?php echo esc_attr($location['position_y']); ?>%"
                  data-location="<?php echo esc_attr($location['location_name']); ?>"
                  data-marker="red">
                  <div class="pin-marker">
                    <svg xmlns="http://www.w3.org/2000/svg" width="25" height="33" viewBox="0 0 25 33" fill="none">
                      <g clip-path="url(#clip0_609_13303)">
                        <path d="M13.6475 32.0696C16.8935 27.9452 24.2967 17.9492 24.2967 12.3344C24.2967 5.5248 18.855 0 12.1479 0C5.44069 0 -0.000976562 5.5248 -0.000976562 12.3344C-0.000976562 17.9492 7.40222 27.9452 10.6482 32.0696C11.4265 33.0525 12.8692 33.0525 13.6475 32.0696ZM12.1479 16.4459C9.91425 16.4459 8.09825 14.6022 8.09825 12.3344C8.09825 10.0667 9.91425 8.22296 12.1479 8.22296C14.3815 8.22296 16.1975 10.0667 16.1975 12.3344C16.1975 14.6022 14.3815 16.4459 12.1479 16.4459Z" fill="#DA000E" />
                      </g>
                      <defs>
                        <clipPath id="clip0_609_13303">
                          <rect width="24.2977" height="32.8919" fill="white" />
                        </clipPath>
                      </defs>
                    </svg>
                  </div>
                  <div class="pin-label">
                    <span class="pin-type">Distributors</span>
                    <span class="pin-location"><?php echo esc_html($location['location_name']); ?></span>
                  </div>
                </div>
              <?php endforeach; ?>

              <!-- India Sales Team (Blue Dots) -->
              <?php foreach ($india_locations as $location): ?>
                <div class="location-pin india-pin"
                  style="left: <?php echo esc_attr($location['position_x']); ?>%; top: <?php echo esc_attr($location['position_y']); ?>%;"
                  data-location="<?php echo esc_attr($location['location_name']); ?>"
                  data-marker="blue">
                  <div class="pin-marker">
                    <svg xmlns="http://www.w3.org/2000/svg" width="25" height="33" viewBox="0 0 25 33" fill="none">
                      <g clip-path="url(#clip0_609_13306)">
                        <path d="M13.6475 32.0696C16.8935 27.9452 24.2967 17.9492 24.2967 12.3344C24.2967 5.5248 18.855 0 12.1479 0C5.44069 0 -0.000976562 5.5248 -0.000976562 12.3344C-0.000976562 17.9492 7.40222 27.9452 10.6482 32.0696C11.4265 33.0525 12.8692 33.0525 13.6475 32.0696ZM12.1479 16.4459C9.91425 16.4459 8.09825 14.6022 8.09825 12.3344C8.09825 10.0667 9.91425 8.22296 12.1479 8.22296C14.3815 8.22296 16.1975 10.0667 16.1975 12.3344C16.1975 14.6022 14.3815 16.4459 12.1479 16.4459Z" fill="#5FBFF6" />
                      </g>
                      <defs>
                        <clipPath id="clip0_609_13306">
                          <rect width="24.2977" height="32.8919" fill="white" />
                        </clipPath>
                      </defs>
                    </svg>
                  </div>
                  <div class="pin-label">
                    <span class="pin-type">Sales Team</span>
                    <span class="pin-location"><?php echo esc_html($location['location_name']); ?></span>
                  </div>
                </div>
              <?php endforeach; ?>
            </div>

            <!-- Mixed view (India tab selected) -->
            <div class="locations-container mixed-locations" data-locations="mixed">
              <!-- International pins (red) -->
              <?php foreach ($international_locations as $location): ?>
                <div class="location-pin international-pin"
                  style="left: <?php echo esc_attr($location['position_x']); ?>%; top: <?php echo esc_attr($location['position_y']); ?>%;"
                  data-location="<?php echo esc_attr($location['location_name']); ?>"
                  data-marker="red">
                  <div class="pin-marker">
                    <svg xmlns="http://www.w3.org/2000/svg" width="25" height="33" viewBox="0 0 25 33" fill="none">
                      <g clip-path="url(#clip0_718_5607)">
                        <path d="M13.6475 32.0696C16.8935 27.9452 24.2967 17.9492 24.2967 12.3344C24.2967 5.5248 18.855 0 12.1479 0C5.44069 0 -0.000976562 5.5248 -0.000976562 12.3344C-0.000976562 17.9492 7.40222 27.9452 10.6482 32.0696C11.4265 33.0525 12.8692 33.0525 13.6475 32.0696ZM12.1479 16.4459C9.91425 16.4459 8.09825 14.6022 8.09825 12.3344C8.09825 10.0667 9.91425 8.22296 12.1479 8.22296C14.3815 8.22296 16.1975 10.0667 16.1975 12.3344C16.1975 14.6022 14.3815 16.4459 12.1479 16.4459Z" fill="#DA000E" />
                      </g>
                      <defs>
                        <clipPath id="clip0_718_5607">
                          <rect width="24.2977" height="32.8919" fill="white" />
                        </clipPath>
                      </defs>
                    </svg>
                  </div>
                  <div class="pin-label">
                    <span class="pin-type">Distributors</span>
                    <span class="pin-location"><?php echo esc_html($location['location_name']); ?></span>
                  </div>
                </div>
              <?php endforeach; ?>

              <!-- India pins (blue) -->
              <?php foreach ($india_locations as $location): ?>
                <div class="location-pin india-pin"
                  style="left: <?php echo esc_attr($location['position_x']); ?>%; top: <?php echo esc_attr($location['position_y']); ?>%"
                  data-location="<?php echo esc_attr($location['location_name']); ?>"
                  data-marker="blue">
                  <div class="pin-marker">
                    <svg xmlns="http://www.w3.org/2000/svg" width="25" height="33" viewBox="0 0 25 33" fill="none">
                      <g clip-path="url(#clip0_609_13306)">
                        <path d="M13.6475 32.0696C16.8935 27.9452 24.2967 17.9492 24.2967 12.3344C24.2967 5.5248 18.855 0 12.1479 0C5.44069 0 -0.000976562 5.5248 -0.000976562 12.3344C-0.000976562 17.9492 7.40222 27.9452 10.6482 32.0696C11.4265 33.0525 12.8692 33.0525 13.6475 32.0696ZM12.1479 16.4459C9.91425 16.4459 8.09825 14.6022 8.09825 12.3344C8.09825 10.0667 9.91425 8.22296 12.1479 8.22296C14.3815 8.22296 16.1975 10.0667 16.1975 12.3344C16.1975 14.6022 14.3815 16.4459 12.1479 16.4459Z" fill="#5FBFF6" />
                      </g>
                      <defs>
                        <clipPath id="clip0_609_13306">
                          <rect width="24.2977" height="32.8919" fill="white" />
                        </clipPath>
                      </defs>
                    </svg>
                  </div>
                  <div class="pin-label">
                    <span class="pin-type">Sales Team</span>
                    <span class="pin-location"><?php echo esc_html($location['location_name']); ?></span>
                  </div>
                </div>
              <?php endforeach; ?>
            </div>

          </div>
        </div>
      </div>

      <!-- CTA Button -->
      <?php if ($cta): ?>
        <div class="distribution-cta text-center mt-6">
          <a href="<?php echo esc_url($cta['url']); ?>"
            class="btn btn-primary "
            <?php if ($cta['target']): ?>target="<?php echo esc_attr($cta['target']); ?>" <?php endif; ?>>
            <?php echo esc_html($cta['title']); ?>
          </a>
        </div>
      <?php endif; ?>

    </div>
  </section>
<?php endif; ?>