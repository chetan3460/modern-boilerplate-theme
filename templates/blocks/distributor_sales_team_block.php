<?php

/**
 * Block Name: Distributor & Sales Team Block
 * Description: Display distributor network information and sales team directory organized by location
 */

// Get block fields
$hide_block = get_sub_field('hide_block');
if ($hide_block) return;

$main_title = get_sub_field('main_title') ?: 'Connect with our distributors & sales team';
$main_description = get_sub_field('main_description') ?: 'Easily locate our sales team or distributors in your region or seamless access to our products and services.';
$contact_information = get_sub_field('contact_information') ?: [];

// Get distributor section (add this as a new ACF group field)
$distributor_section = get_sub_field('distributor_section') ?: [];
$distributor_data = [
    'title' => isset($distributor_section['title']) ? $distributor_section['title'] : 'Distributor network',
    'description' => isset($distributor_section['description']) ? $distributor_section['description'] : 'RPL distributors are located across India and international markets including Bangladesh, UAE, UK, Africa, and South America. For specific queries, please reach our team.'
];

// Extract nested data from contact_information
$contact_info_data = [
    'title' => isset($contact_information['title']) ? $contact_information['title'] : 'For more information',
    'phone' => isset($contact_information['phone']) ? $contact_information['phone'] : '',
    'email' => isset($contact_information['email_address']) ? $contact_information['email_address'] : ''
];

$sales_team_data = isset($contact_information['sales_team_section']) ? $contact_information['sales_team_section'] : [];

?>

<section class=" fade-in">
    <div class="container-fluid">
        <!-- Header Section -->
        <div class="section-heading text-center !max-w-none">
            <?php if ($main_title): ?>
                <h2 class="fade-text"><?php echo esc_html($main_title); ?></h2>
            <?php endif; ?>

            <?php if ($main_description): ?>
                <div class="description-content prose !max-w-none ">
                    <?php echo wp_kses_post($main_description); ?>
                </div>
            <?php endif; ?>
        </div>




        <!-- Main Content Grid -->
        <div class="rounded-2xl">
            <div class="rounded-t-2xl p-10 bg-[#CBEAFC] grid lg:grid-cols-4 lg:gap-15 ">
                <!-- Distributor Network Section -->
                <div class="lg:col-span-2">
                    <h3 class="font-semibold tracking-[-0.48px] text-black mb-2">
                        <?php echo wp_kses_post($distributor_data['title']); ?>
                    </h3>
                    <div class="text-sm md:text-base prose-p:text-black mb-3 lg:mb-0">
                        <?php echo wp_kses_post($distributor_data['description']); ?>
                    </div>
                </div>

                <!-- Contact Information Section -->
                <?php if ($contact_info_data['title'] || $contact_info_data['phone'] || $contact_info_data['email']): ?>
                    <div class="lg:col-span-2  bg-white rounded-2xl ">
                        <div class="py-4 px-6 text-base font-medium tracking-[0.16px] leading-[22.4px] text-black border-b border-[#E4E4E4]">
                            <?php echo esc_html($contact_info_data['title'] ?: 'For more information'); ?>
                        </div>

                        <div class="py-4 px-6 flex md:flex-row flex-col justify-between">
                            <?php if ($contact_info_data['phone']): ?>
                                <div class="flex items-center gap-3 ">
                                    <div class="flex-shrink-0 w-10 h-10 bg-white border border-[#E0E0E0] rounded-full flex items-center justify-center">
                                        <img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/contact/contact.svg" class="size-[17px] rotate-[-30deg]" alt="contact resins">
                                    </div>
                                    <a href="tel:<?php echo esc_attr(preg_replace('/[^0-9+]/', '', $contact_info_data['phone'])); ?>"
                                        class="text-gray-900 hover:text-primary transition-colors">
                                        <?php echo esc_html($contact_info_data['phone']); ?>
                                    </a>
                                </div>
                            <?php endif; ?>

                            <?php if ($contact_info_data['email']): ?>
                                <div class="flex items-center gap-3">
                                    <div class="flex-shrink-0 w-10 h-10 bg-white border border-[#E0E0E0] rounded-full flex items-center justify-center">
                                        <img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/contact/email.svg" class="size-[17px]" alt="email resins">

                                    </div>
                                    <a href="mailto:<?php echo esc_attr($contact_info_data['email']); ?>"
                                        class="text-gray-900 hover:text-primary transition-colors">
                                        <?php echo esc_html($contact_info_data['email']); ?>
                                    </a>
                                </div>
                            <?php else: ?>
                                <!-- Fallback when no contact info is available -->
                                <div class="text-center py-4">
                                    <svg class="w-8 h-8 text-gray-400 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                    </svg>
                                    <p class="text-sm text-gray-500">Contact information<br>coming soon</p>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php else: ?>
                    <!-- Default contact info when not configured -->
                    <div class="lg:col-span-2 bg-white rounded-2xl p-6">
                        <h4 class="font-semibold text-gray-900 mb-4">For more information</h4>
                        <div class="text-center py-4">
                            <svg class="w-8 h-8 text-gray-400 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <p class="text-sm text-gray-500">Contact details<br>will be updated soon</p>
                        </div>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Sales Team Section -->
            <?php if ($sales_team_data && isset($sales_team_data['locations']) && !empty($sales_team_data['locations'])): ?>
                <div class="bg-sky-50  p-4 lg:p-10 rounded-b-2xl">
                    <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-6">
                        <div class="h3 font-semibold tracking-[-0.48px] mb-3 lg:mb-0">
                            <?php echo esc_html(isset($sales_team_data['title']) ? $sales_team_data['title'] : 'Sales team'); ?>
                        </div>

                        <!-- Search Box -->
                        <div class="relative w-full lg:w-96">
                            <input type="text"
                                id="location-search"
                                placeholder="<?php echo esc_attr(isset($sales_team_data['search_placeholder']) ? $sales_team_data['search_placeholder'] : 'Search Location'); ?>"
                                class="w-full px-4 py-3  rounded-[12px] bg-white text-sm text-grey-3 font-normal. leading-[18.2px] outline-0">
                            <div class="absolute inset-y-0 right-0 flex items-center pr-3">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20" fill="none">
                                    <g clip-path="url(#clip0_836_4540)">
                                        <path d="M13.9766 13.7686L18.307 18.099" stroke="#DA000E" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                        <path d="M9.55664 15.5986C13.0084 15.5986 15.8066 12.8004 15.8066 9.34863C15.8066 5.89685 13.0084 3.09863 9.55664 3.09863C6.10486 3.09863 3.30664 5.89685 3.30664 9.34863C3.30664 12.8004 6.10486 15.5986 9.55664 15.5986Z" stroke="black" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                    </g>
                                    <defs>
                                        <clipPath id="clip0_836_4540">
                                            <rect width="20" height="20" fill="white" transform="translate(0 0.000976562)" />
                                        </clipPath>
                                    </defs>
                                </svg>
                            </div>
                        </div>
                    </div>

                    <!-- Sales Team Grid -->
                    <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6" id="sales-team-grid">
                        <?php
                        $card_index = 0;
                        foreach ($sales_team_data['locations'] as $location):
                        ?>
                            <?php if (isset($location['team_members']) && !empty($location['team_members'])): ?>
                                <div class="location-card bg-white rounded-2xl <?php echo $card_index >= 3 ? 'hidden md:block mobile-hidden' : ''; ?>" data-location="<?php echo esc_attr(strtolower($location['location_name'] ?? '')); ?>" data-card-index="<?php echo $card_index; ?>">

                                    <div class="py-4 px-6 text-base font-medium tracking-[0.16px] leading-[22.4px] text-black border-b border-[#E4E4E4]">
                                        <?php echo esc_html($location['location_name'] ?? ''); ?>
                                    </div>

                                    <?php foreach ($location['team_members'] as $member): ?>
                                        <div class="member-info mb-6 last:mb-0 py-4 px-6">
                                            <?php if (!empty($member['name'])): ?>
                                                <div class="flex items-center gap-3 mb-4">
                                                    <div class="flex-shrink-0 w-10 h-10 bg-white border border-[#E0E0E0] rounded-full flex items-center justify-center">
                                                        <img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/contact/User.svg" class="size-[17px]" alt="email resins">
                                                    </div>
                                                    <span class="text-black text-sm font-medium">
                                                        <?php echo esc_html($member['name']); ?>
                                                    </span>
                                                </div>
                                            <?php endif; ?>

                                            <?php if (!empty($member['phone'])): ?>
                                                <div class="flex items-center gap-3 mb-4">
                                                    <div class="flex-shrink-0 w-10 h-10 bg-white border border-[#E0E0E0] rounded-full flex items-center justify-center">
                                                        <img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/contact/contact.svg" class="size-[17px] rotate-[-30deg]" alt="phone resins">
                                                    </div>
                                                    <a href="tel:<?php echo esc_attr(preg_replace('/[^0-9+]/', '', $member['phone'])); ?>"
                                                        class="text-black text-sm font-medium hover:text-primary">
                                                        <?php echo esc_html($member['phone']); ?>
                                                    </a>
                                                </div>
                                            <?php endif; ?>

                                            <?php if (!empty($member['email'])): ?>
                                                <div class="flex items-center gap-3">
                                                    <div class="flex-shrink-0 w-10 h-10 bg-white border border-[#E0E0E0] rounded-full flex items-center justify-center">
                                                        <img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/contact/email.svg" class="size-[17px]" alt="email resins">
                                                    </div>
                                                    <a href="mailto:<?php echo esc_attr($member['email']); ?>"
                                                        class="text-black text-sm font-medium hover:text-primary underline transition-colors">
                                                        <?php echo esc_html($member['email']); ?>
                                                    </a>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                                <?php $card_index++; ?>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </div>

                    <!-- Loading Spinner (Mobile Only) -->
                    <div class="md:hidden text-center py-8" id="loading-spinner" style="display: none;">
                        <div class="inline-flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" class="animate-spin">
                                <path d="M12 2V6" stroke="#626262" stroke-width="1.06667" stroke-linecap="round" stroke-linejoin="round" />
                                <path d="M12 18V22" stroke="#626262" stroke-width="1.06667" stroke-linecap="round" stroke-linejoin="round" />
                                <path d="M4.93066 4.92969L7.76066 7.75969" stroke="#626262" stroke-width="1.06667" stroke-linecap="round" stroke-linejoin="round" />
                                <path d="M16.2393 16.2402L19.0693 19.0702" stroke="#626262" stroke-width="1.06667" stroke-linecap="round" stroke-linejoin="round" />
                                <path d="M2 12H6" stroke="#626262" stroke-width="1.06667" stroke-linecap="round" stroke-linejoin="round" />
                                <path d="M18 12H22" stroke="#626262" stroke-width="1.06667" stroke-linecap="round" stroke-linejoin="round" />
                                <path d="M4.93066 19.0702L7.76066 16.2402" stroke="#626262" stroke-width="1.06667" stroke-linecap="round" stroke-linejoin="round" />
                                <path d="M16.2393 7.75969L19.0693 4.92969" stroke="#626262" stroke-width="1.06667" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                            <span class="ml-2 text-gray-600">Loading more locations...</span>
                        </div>
                    </div>

                    <!-- No Results Found Message -->
                    <div id="no-results-message" class="hidden bg-white rounded-2xl p-12 text-center">
                        <div class="mb-6">
                            <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                            <h3 class="text-xl font-semibold text-gray-900 mb-2">No locations found</h3>
                            <p class="text-gray-600 mb-4">We couldn't find any sales team locations matching your search.</p>
                            <button onclick="clearSearch()" class="inline-flex items-center px-4 py-2 text-primary font-semibold transition-colors">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                                Clear Search
                            </button>
                        </div>
                    </div>
                </div>
            <?php else: ?>
                <!-- No Sales Team Data Configured -->
                <div class="bg-white rounded-2xl p-12 text-center">
                    <div class="mb-6">
                        <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                        <h3 class="text-xl font-semibold text-gray-900 mb-2">Sales Team Information Coming Soon</h3>
                        <p class="text-gray-600">Our sales team directory is currently being updated. Please check back soon or contact us directly for assistance.</p>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Search Functionality & Infinite Scroll Script -->
    <script>
        let searchInput, salesGrid, locationCards, noResultsMessage, loadingSpinner;
        let currentlyVisible = 3; // Show first 3 cards on mobile
        let isLoading = false;
        let isMobile = window.innerWidth < 768;

        document.addEventListener('DOMContentLoaded', function() {
            searchInput = document.getElementById('location-search');
            salesGrid = document.getElementById('sales-team-grid');
            noResultsMessage = document.getElementById('no-results-message');
            loadingSpinner = document.getElementById('loading-spinner');
            locationCards = salesGrid ? salesGrid.querySelectorAll('.location-card') : [];

            if (searchInput && salesGrid) {
                searchInput.addEventListener('input', function() {
                    performSearch();
                });
            }

            // Add infinite scroll for mobile only
            if (isMobile && locationCards.length > 3) {
                window.addEventListener('scroll', handleInfiniteScroll);
                window.addEventListener('resize', handleResize);
            }
        });

        function handleResize() {
            const newIsMobile = window.innerWidth < 768;
            if (newIsMobile !== isMobile) {
                isMobile = newIsMobile;
                if (!isMobile) {
                    // Show all cards on desktop
                    locationCards.forEach(card => {
                        card.classList.remove('mobile-hidden');
                        card.style.display = 'block';
                    });
                    window.removeEventListener('scroll', handleInfiniteScroll);
                } else if (locationCards.length > 3) {
                    // Re-hide cards beyond first 3 on mobile
                    locationCards.forEach((card, index) => {
                        if (index >= currentlyVisible) {
                            card.classList.add('mobile-hidden');
                            card.style.display = 'none';
                        }
                    });
                    window.addEventListener('scroll', handleInfiniteScroll);
                }
            }
        }

        function handleInfiniteScroll() {
            if (isLoading || !isMobile) return;

            const scrollTop = window.pageYOffset || document.documentElement.scrollTop;
            const windowHeight = window.innerHeight;
            const documentHeight = document.documentElement.scrollHeight;

            // Load more when user scrolls to 80% of the page
            if (scrollTop + windowHeight >= documentHeight * 0.8) {
                loadMoreCards();
            }
        }

        function loadMoreCards() {
            if (isLoading || currentlyVisible >= locationCards.length) return;

            isLoading = true;
            if (loadingSpinner) loadingSpinner.style.display = 'block';

            // Simulate loading delay
            setTimeout(() => {
                const cardsToShow = Math.min(3, locationCards.length - currentlyVisible);

                for (let i = 0; i < cardsToShow; i++) {
                    const cardIndex = currentlyVisible + i;
                    if (locationCards[cardIndex]) {
                        locationCards[cardIndex].classList.remove('mobile-hidden');
                        locationCards[cardIndex].style.display = 'block';
                    }
                }

                currentlyVisible += cardsToShow;
                isLoading = false;
                if (loadingSpinner) loadingSpinner.style.display = 'none';

                // Remove scroll listener if all cards are visible
                if (currentlyVisible >= locationCards.length) {
                    window.removeEventListener('scroll', handleInfiniteScroll);
                }
            }, 800); // 800ms loading delay
        }

        function performSearch() {
            const searchTerm = searchInput.value.toLowerCase().trim();
            let visibleCount = 0;

            locationCards.forEach((card, index) => {
                const location = card.dataset.location || '';
                const memberNames = Array.from(card.querySelectorAll('.member-info')).map(member =>
                    member.textContent.toLowerCase()
                ).join(' ');

                // Search in location name and team member names
                const matchesLocation = location.includes(searchTerm);
                const matchesMembers = memberNames.includes(searchTerm);

                if (matchesLocation || matchesMembers || searchTerm === '') {
                    // When searching, show matching cards regardless of mobile pagination
                    if (searchTerm !== '' || !isMobile || index < currentlyVisible) {
                        card.style.display = 'block';
                        card.classList.remove('mobile-hidden');
                        visibleCount++;
                    } else {
                        // On mobile without search, respect the pagination
                        card.style.display = 'none';
                        card.classList.add('mobile-hidden');
                    }
                } else {
                    card.style.display = 'none';
                }
            });

            // Show/hide no results message
            if (noResultsMessage) {
                if (visibleCount === 0 && searchTerm !== '') {
                    noResultsMessage.classList.remove('hidden');
                } else {
                    noResultsMessage.classList.add('hidden');
                }
            }

            // Reset infinite scroll when clearing search
            if (searchTerm === '' && isMobile) {
                currentlyVisible = Math.min(3, locationCards.length);
                if (locationCards.length > 3) {
                    window.addEventListener('scroll', handleInfiniteScroll);
                }
            }
        }

        function clearSearch() {
            if (searchInput) {
                searchInput.value = '';
                performSearch();
                searchInput.focus();
            }
        }
    </script>
</section>