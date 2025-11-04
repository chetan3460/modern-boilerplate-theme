import Swiper from 'swiper';
import { Navigation, Pagination, Autoplay } from 'swiper/modules';

// Simplified ClientSlider
// - Uses a single set of navigation elements (prefer bottom controls, fallback to top)
// - No manual click bindings (avoids double-triggered navigation)
// - Autoplay pauses after user interaction (prevents perceived skipping)
export default class ClientSlider {
  constructor(element) {
    this.el = element;

    // Use the nearest slider container regardless of where data-component is placed
    this.container = this.el.closest('.client-slider-container')
      || this.el.querySelector('.client-slider-container')
      || this.el;

    // Core elements
    this.slider = this.container.querySelector('.client-slider');

    // Prefer bottom pagination controls; fallback to top controls
    const bottomPrev = this.container.querySelector('.swiper-btn-prev-pagination.swiper-btn-prev');
    const bottomNext = this.container.querySelector('.swiper-btn-next-pagination.swiper-btn-next');
    const topPrev = this.container.querySelector('.swiper-btn-prev');
    const topNext = this.container.querySelector('.swiper-btn-next');

    this.prevEl = bottomPrev || topPrev || null;
    this.nextEl = bottomNext || topNext || null;

    // Pagination display (x/y)
    this.paginationCustom = this.container.querySelector('.swiper-pagination-custom');

    // Data attributes (fallback to deriving from DOM)
    const enabledAttr = this.container.getAttribute('data-slider-enabled')
      || this.el.getAttribute('data-slider-enabled');
    this.sliderEnabled = enabledAttr === 'true';

    const countAttr = this.container.getAttribute('data-client-count')
      || this.el.getAttribute('data-client-count');
    const parsedCount = parseInt(countAttr, 10);
    this.clientCount = Number.isFinite(parsedCount)
      ? parsedCount
      : (this.slider ? this.container.querySelectorAll('.swiper-slide').length : 0);

    if (!this.slider || this.clientCount === 0) return;

    this.init();
  }

  init() {
    const swiperConfig = {
      modules: [Navigation, Pagination, Autoplay],
      loop: false,
      speed: 600,
      autoplay: this.sliderEnabled && this.clientCount > 1
        ? {
          delay: 5000,
          disableOnInteraction: true, // stop autoplay after user clicks/touches
          pauseOnMouseEnter: true,
        }
        : false,
      slidesPerView: 1,
      spaceBetween: 24,
      breakpoints: {
        640: { slidesPerView: 1, spaceBetween: 24 },
        768: { slidesPerView: 2, spaceBetween: 24 },
        1024: { slidesPerView: 3, spaceBetween: 24 },
        1280: { slidesPerView: 3, spaceBetween: 24 },
      },
      navigation: this.prevEl && this.nextEl
        ? { nextEl: this.nextEl, prevEl: this.prevEl }
        : false,
      pagination: this.paginationCustom
        ? {
          el: this.paginationCustom,
          type: 'custom',
          renderCustom: (swiper, current, total) => `${current}/${total}`,
        }
        : false,
    };

    try {
      this.swiper = new Swiper(this.slider, swiperConfig);
      this.handleResize();
    } catch (error) {
    }
  }

  handleResize() {
    let timer;
    window.addEventListener('resize', () => {
      clearTimeout(timer);
      timer = setTimeout(() => {
        if (this.swiper) this.swiper.update();
      }, 200);
    });
  }

  destroy() {
    if (this.swiper) {
      this.swiper.destroy(true, true);
      this.swiper = null;
    }
  }
}
