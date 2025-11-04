<?php

/**
 * The template for displaying 404 pages (not found)
 */
get_header(); ?>
<div id="smooth-wrapper">

    <div id="smooth-content">

        <main class="site-main     relative ">
            <section class="relative py-30 lg:py-[220px]">
                <div class="absolute top-[123px] left-[75px] lg:block hidden">
                    <img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/home/shapes/shape-5.webp" class="w-[108.915px] h-[94.187px] " alt="">
                </div>
                <div class="container-fluid relative">

                    <div class="text-center  relative">
                        <div class="w-[341px] mx-auto">
                            <h1 class="text-[80px] md:text-[250px] lg:text-[128px] font-bold !text-primary leading-none inline-block max-md:mb-2">
                                404
                            </h1>
                            <div class="absolute top-0 right-[280px] lg:block hidden">
                                <img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/home/shapes/shape-2.webp" class="w-[134px] h-[136px]" alt="resins">
                            </div>
                        </div>


                        <h2 class="max-md:text-[28px] text-grey-1 tracking-[-0.84px] font-medium mb-2">
                            Lost in transition
                        </h2>

                        <p class="text-[18px] font-normal text-grey-2 leading-[25px]">
                            This link couldn't be formulated. Navigate to the homepage or try again after some time.
                        </p>

                        <a href="<?php echo esc_url(home_url('/')); ?>"
                            class="btn mt-5">
                            Go To Home
                        </a>
                    </div>


                    <div class="absolute right-[78px] bottom-0 lg:block hidden">
                        <img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/home/shapes/shape-7.webp" class="w-[143px] h-[110px]" alt="resins">
                    </div>
                </div>
            </section>


        </main>
        <?php get_footer(); ?>

    </div>
</div>