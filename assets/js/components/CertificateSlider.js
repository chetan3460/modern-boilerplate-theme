import Swiper from 'swiper';
import { Navigation, Pagination, Autoplay } from 'swiper/modules';

// Simplified CertificateSlider - 3 slides per view on desktop
export default class CertificateSlider {
  constructor(element) {
    this.container = element;
    this.slider = this.container.querySelector('.certificate-slider');

    // Navigation elements
    this.prevEl = this.container.querySelector('.swiper-btn-prev');
    this.nextEl = this.container.querySelector('.swiper-btn-next');

    // Pagination element
    this.paginationEl = this.container.querySelector('.swiper-pagination-custom');

    // Count slides
    this.slideCount = this.slider ? this.slider.querySelectorAll('.swiper-slide').length : 0;

    if (!this.slider || this.slideCount === 0) return;

    this.init();
  }

  init() {
    // Calculate if loop is safe (need at least 6 slides for safe looping with 3-per-view on desktop)
    const canLoop = this.slideCount >= 6;
    
    const config = {
      modules: [Navigation, Pagination, Autoplay],
      loop: canLoop,
      speed: 600,
      slidesPerView: 1,
      spaceBetween: 16,
      initialSlide: 0, // Start from first slide
      watchOverflow: true, // Disable slider if not enough slides

      // Responsive breakpoints
      breakpoints: {
        640: {
          slidesPerView: 1.8,
          spaceBetween: 10,
          initialSlide: 0
        },
        1024: {
          slidesPerView: 3, // 3 slides per view on desktop
          spaceBetween: 30,
          initialSlide: 0
        }
      },

      // Navigation
      navigation: this.prevEl && this.nextEl ? {
        nextEl: this.nextEl,
        prevEl: this.prevEl
      } : false,

      // Pagination
      pagination: this.paginationEl ? {
        el: this.paginationEl,
        type: 'custom',
        renderCustom: (swiper, current, total) => `${current}/${total}`
      } : false,

      // Autoplay - only enable with enough slides for looping
      autoplay: canLoop ? {
        delay: 4000,
        disableOnInteraction: true,
        pauseOnMouseEnter: true
      } : false
    };

    // Add data attribute for CSS styling
    this.slider.setAttribute('data-slides', this.slideCount);

    this.swiper = new Swiper(this.slider, config);
  }

  destroy() {
    if (this.swiper) {
      this.swiper.destroy();
      this.swiper = null;
    }
  }
}