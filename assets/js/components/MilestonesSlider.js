/**
 * Milestones Timeline Slider Component
 * Creates an interactive timeline slider using Swiper.js
 */
import Swiper from "swiper";
import { Navigation, Pagination, Autoplay } from "swiper/modules";
import gsap from "gsap";

export default class MilestonesSlider {
  constructor() {
    this.init();
  }

  init() {
    this.setDomMap();
    this.bindEvents();
  }

  setDomMap() {
    this.dom = {
      slider: document.querySelector(".milestones-swiper"),
    };
  }

  bindEvents() {
    if (!this.dom.slider) return; // Exit if no slider found

    // Get slide count to determine if navigation is needed
    const slides = this.dom.slider.querySelectorAll('.swiper-slide');
    const slideCount = slides.length;

    // Ensure navigation elements exist
    const nextEl = this.dom.slider.closest('.milestones_block')?.querySelector('.swiper-btn-next-pagination') || document.querySelector('.swiper-btn-next');
    const prevEl = this.dom.slider.closest('.milestones_block')?.querySelector('.swiper-btn-prev-pagination') || document.querySelector('.swiper-btn-prev');
    const paginationEl = this.dom.slider.closest('.milestones_block')?.querySelector('.swiper-pagination-custom') || document.querySelector('.swiper-pagination-custom');

    const swiper2 = new Swiper(this.dom.slider, {
      modules: [Autoplay, Navigation, Pagination],
      loop: false, // Loop disabled
      loopAdditionalSlides: 0,
      slidesPerView: 1, // Default: 1 full slide
      spaceBetween: 10, // No space between slides
      centeredSlides: false,
      slideToClickedSlide: true, // Enable click-to-center feature
      speed: 1000,
      autoplay: false, // Autoplay disabled
      pagination: (paginationEl && (slideCount > 5 || window.innerWidth < 1280)) ? {
        el: paginationEl,
        type: 'custom',
        renderCustom: (swiper, current, total) => `${current}/${total}`,
      } : false,
      navigation: (nextEl && prevEl && (slideCount > 5 || window.innerWidth < 1280)) ? {
        nextEl: nextEl,
        prevEl: prevEl,
      } : false,
      breakpoints: {
        320: { slidesPerView: 1 }, // Mobile: 1 full slide
        640: { slidesPerView: 2 }, // Tablet: 2 full slides
        768: { slidesPerView: 2 }, // Large tablet: 2 full slides
        1024: { slidesPerView: 3 }, // Desktop: 3 full slides
        1280: { slidesPerView: 5, spaceBetween: 0 }, // Large desktop: 3 full slides
      },
      on: {
        init: function () {
          MilestonesSlider.updateSlideScale();
        },
        slideChangeTransitionStart: function () {
          MilestonesSlider.updateSlideScale();
        },
        slideChangeTransitionEnd: function () {
          // Make sure active slide is properly scaled
          const activeSlide = document.querySelector(".swiper-slide-active .milestone-card .image-wrapper");
          if (activeSlide) {
            gsap.to(activeSlide, {
              scale: 1,
              opacity: 1,
              duration: 0.5,
              ease: "power3.out",
            });
          }
        },
      },
    });
  }





  static updateSlideScale() {
    const isMobile = window.matchMedia("(max-width: 767px)").matches;
    const scaleValue = isMobile ? 0.8 : 0.7;

    // Reset all slides except active one
    document.querySelectorAll(".swiper-slide .milestone-card .image-wrapper").forEach((el) => {
      gsap.to(el, {
        // scale: scaleValue,
        opacity: 0.8,
        duration: 0.4,
        ease: "power3.out",
      });
    });

    // Scale the active one separately
    const activeSlide = document.querySelector(".swiper-slide-active .milestone-card .image-wrapper");
    if (activeSlide) {
      gsap.to(activeSlide, {
        scale: 1,
        opacity: 1,
        duration: 0.5,
        ease: "power3.out",
      });
    }
  }
}

