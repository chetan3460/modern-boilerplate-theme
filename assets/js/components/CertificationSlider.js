import Swiper from 'swiper';
import { Navigation, Pagination } from 'swiper/modules';

export default class CertificationSlider {
  constructor(element) {
    this.el = element;

    // Resolve container
    this.container = this.el.closest('.certification-slider-container')
      || this.el.querySelector('.certification-slider-container')
      || this.el;

    this.slider = this.container.querySelector('.certification-slider');
    this.prevEl = this.container.querySelector('.swiper-btn-prev-pagination.swiper-btn-prev');
    this.nextEl = this.container.querySelector('.swiper-btn-next-pagination.swiper-btn-next');
    this.paginationEl = this.container.querySelector('.swiper-pagination-custom');
    this.controlsWrap = this.container.querySelector('.certification-slider-controls');

    this.slideCount = this.container.querySelectorAll('.swiper-slide').length;

    if (!this.slider || this.slideCount === 0) return;

    this.init();
  }

  init() {
    try {
      this.swiper = new Swiper(this.slider, {
        modules: [Navigation, Pagination],
        loop: false,
        speed: 600,
        slidesPerView: 1,
        spaceBetween: 12,
        watchOverflow: true,
        breakpoints: {
          640: { slidesPerView: 2, spaceBetween: 16 },
          1024: { slidesPerView: 3, spaceBetween: 24 },
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
        on: {
          init: () => this.updateControls(),
          resize: () => this.updateControls(),
          breakpoint: () => this.updateControls(),
        }
      });
    } catch (e) {
    }
  }

  updateControls() {
    if (!this.controlsWrap || !this.swiper) return;

    const isDesktop = window.innerWidth >= 1024;
    if (isDesktop) {
      // Show controls only if there are more than 3 items and swiper isn't locked
      const shouldShow = !this.swiper.isLocked && this.slideCount > 3;
      this.controlsWrap.classList.toggle('lg:hidden', !shouldShow);
      this.controlsWrap.classList.toggle('lg:flex', shouldShow);
    } else {
      // Mobile/tablet: always visible
      this.controlsWrap.classList.remove('hidden');
    }
  }

  destroy() {
    if (this.swiper) {
      this.swiper.destroy(true, true);
      this.swiper = null;
    }
  }
}
