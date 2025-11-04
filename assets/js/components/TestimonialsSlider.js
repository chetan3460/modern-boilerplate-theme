import Swiper from 'swiper';
import { Navigation, Pagination, Autoplay } from 'swiper/modules';

export default class TestimonialsSlider {
  constructor(element) {
    this.element = element;
    this.swiper = null;
    this.swiperElement = element.querySelector('.testimonials-swiper');

    // Get settings from data attributes
    this.autoplay = this.swiperElement?.getAttribute('data-autoplay') === 'true';
    this.autoplayDelay = parseInt(this.swiperElement?.getAttribute('data-autoplay-delay')) || 5000;
    this.showNavigation = this.swiperElement?.getAttribute('data-show-navigation') === 'true';

    // Navigation elements (same style as NewsSlider)
    this.prevEl = element.querySelector('.swiper-btn-prev-pagination.swiper-btn-prev');
    this.nextEl = element.querySelector('.swiper-btn-next-pagination.swiper-btn-next');
    this.paginationCustom = element.querySelector('.swiper-pagination-custom');

    this.init();
  }

  init() {
    if (!this.swiperElement) {
      return;
    }

    this.initSwiper();
  }

  initSwiper() {
    // Configure modules
    const modules = [];
    if (this.prevEl && this.nextEl) modules.push(Navigation);
    if (this.paginationCustom) modules.push(Pagination);
    if (this.autoplay) modules.push(Autoplay);

    // Swiper configuration
    const config = {
      modules,
      slidesPerView: 1,
      spaceBetween: 0,
      centeredSlides: true,
      loop: false,
      speed: 800,
      effect: 'slide',

      // Responsive breakpoints
      breakpoints: {
        // 768: {
        //   spaceBetween: 10,
        // },
        // 1024: {
        //   spaceBetween: 10,
        // }
      },

      // Accessibility
      a11y: {
        enabled: true,
        prevSlideMessage: 'Previous testimonial',
        nextSlideMessage: 'Next testimonial',
        paginationBulletMessage: 'Go to testimonial {{index}}',
      },
    };

    // Add navigation if elements exist
    if (this.prevEl && this.nextEl) {
      config.navigation = {
        nextEl: this.nextEl,
        prevEl: this.prevEl,
        disabledClass: 'swiper-button-disabled',
      };
    }

    // Add custom pagination (x/y format)
    if (this.paginationCustom) {
      config.pagination = {
        el: this.paginationCustom,
        type: 'custom',
        renderCustom: (swiper, current, total) => `${current}/${total}`,
      };
    }

    // Add autoplay if enabled
    if (this.autoplay) {
      config.autoplay = {
        delay: this.autoplayDelay,
        disableOnInteraction: true,
        pauseOnMouseEnter: true,
      };
    }

    // Initialize Swiper
    try {
      this.swiper = new Swiper(this.swiperElement, config);
      
      // Add event listeners for navigation visibility
      this.swiper.on('init', () => this.updateNavigationVisibility());
      this.swiper.on('resize', () => this.updateNavigationVisibility());
      this.swiper.on('update', () => this.updateNavigationVisibility());
      
      // Add other event listeners
      this.addEventListeners();
      
    } catch (error) {
    }
  }

  addEventListeners() {
    if (!this.swiper) return;

    // Pause autoplay on hover (if autoplay is enabled)
    if (this.autoplay) {
      this.element.addEventListener('mouseenter', () => {
        if (this.swiper.autoplay) {
          this.swiper.autoplay.stop();
        }
      });

      this.element.addEventListener('mouseleave', () => {
        if (this.swiper.autoplay) {
          this.swiper.autoplay.start();
        }
      });
    }

    // Handle visibility changes
    document.addEventListener('visibilitychange', () => {
      if (!this.swiper) return;

      if (document.hidden) {
        // Pause when page is hidden
        if (this.swiper.autoplay && this.autoplay) {
          this.swiper.autoplay.stop();
        }
      } else {
        // Resume when page becomes visible
        if (this.swiper.autoplay && this.autoplay) {
          this.swiper.autoplay.start();
        }
      }
    });

    // Handle resize
    let resizeTimer;
    window.addEventListener('resize', () => {
      clearTimeout(resizeTimer);
      resizeTimer = setTimeout(() => {
        if (this.swiper) {
          this.swiper.update();
        }
      }, 250);
    });
  }

  updateNavigationVisibility() {
    if (!this.swiper) return;
    
    const slides = this.swiper.slides.length;
    
    // For testimonials, navigation is needed if more than 1 slide
    // Since testimonials show 1 slide at a time
    const needsNavigation = slides > 1;
    
    // Find the navigation container
    const navigationContainer = this.element.querySelector('.mt-3.flex.lg\\:hidden');
    
    if (navigationContainer) {
      navigationContainer.style.display = needsNavigation ? 'flex' : 'none';
    }
  }

  // Public methods
  nextSlide() {
    if (this.swiper) {
      this.swiper.slideNext();
    }
  }

  prevSlide() {
    if (this.swiper) {
      this.swiper.slidePrev();
    }
  }

  goToSlide(index) {
    if (this.swiper) {
      this.swiper.slideTo(index);
    }
  }

  startAutoplay() {
    if (this.swiper && this.swiper.autoplay) {
      this.swiper.autoplay.start();
    }
  }

  stopAutoplay() {
    if (this.swiper && this.swiper.autoplay) {
      this.swiper.autoplay.stop();
    }
  }

  // Cleanup method
  destroy() {
    if (this.swiper) {
      this.swiper.destroy(true, true);
      this.swiper = null;
    }

    // Remove event listeners
    this.element.removeEventListener('mouseenter', this.handleMouseEnter);
    this.element.removeEventListener('mouseleave', this.handleMouseLeave);
    document.removeEventListener('visibilitychange', this.handleVisibilityChange);
    window.removeEventListener('resize', this.handleResize);
  }
}