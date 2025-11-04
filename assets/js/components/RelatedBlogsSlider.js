import Swiper from 'swiper';
import { Navigation, Pagination } from 'swiper/modules';

/**
 * RelatedBlogsSlider - Mobile-specific slider for related blogs in sidebar
 * Only initializes on mobile devices (< 768px)
 */
export default class RelatedBlogsSlider {
  constructor(element) {
    this.el = element;
    this.container = this.el.closest('.related-blogs-slider') || this.el;
    this.slider = this.container.querySelector('.swiper') || this.el;

    // Navigation elements
    this.prevEl = this.container.querySelector('.related-blogs-prev');
    this.nextEl = this.container.querySelector('.related-blogs-next');
    this.paginationEl = this.container.querySelector('.related-blogs-pagination');

    // Check if we should initialize (mobile only)
    this.shouldInit = this.isMobile();

    if (!this.slider || !this.shouldInit) return;

    this.init();
    this.bindEvents();
  }

  isMobile() {
    return window.innerWidth < 991;
  }

  init() {
    const slides = this.slider.querySelectorAll('.swiper-slide');
    if (slides.length === 0) return;

    const swiperConfig = {
      modules: [Navigation, Pagination],
      loop: false,
      speed: 300,
      slidesPerView: 1,
      spaceBetween: 16,

      // Mobile-specific settings
      breakpoints: {
        480: {
          slidesPerView: 1.2,
          spaceBetween: 16
        },
        640: {
          slidesPerView: 1.5,
          spaceBetween: 20
        },
      },

      navigation: this.prevEl && this.nextEl ? {
        nextEl: this.nextEl,
        prevEl: this.prevEl,
      } : false,

      pagination: this.paginationEl ? {
        el: this.paginationEl,
        type: 'custom',
        renderCustom: (swiper, current, total) => `${current}/${total}`,
      } : false,
    };

    try {
      this.swiper = new Swiper(this.slider, swiperConfig);
    } catch (error) {
      return;
    }
  }

  bindEvents() {
    // Handle resize events
    let resizeTimer;
    window.addEventListener('resize', () => {
      clearTimeout(resizeTimer);
      resizeTimer = setTimeout(() => {
        const shouldBeActive = this.isMobile();

        if (shouldBeActive && !this.swiper) {
          // Initialize if now mobile and not initialized
          this.shouldInit = true;
          this.init();
        } else if (!shouldBeActive && this.swiper) {
          // Destroy if no longer mobile
          this.destroy();
          this.shouldInit = false;
        } else if (this.swiper) {
          // Update existing swiper
          this.swiper.update();
        }
      }, 250);
    });
  }

  destroy() {
    if (this.swiper) {
      this.swiper.destroy(true, true);
      this.swiper = null;
    }
  }
}