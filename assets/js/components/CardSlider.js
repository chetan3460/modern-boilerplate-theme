import Swiper from 'swiper';
import { Navigation, Pagination } from 'swiper/modules';

// CardSlider: always uses Swiper markup. Desktop shows 3 cards; slider interaction only when there are 4+ cards (watchOverflow handles this).
export default class CardSlider {
  constructor(element) {
    this.el = element;

    this.container = this.el.closest('.card-slider-container')
      || this.el.querySelector('.card-slider-container')
      || this.el;

    this.slider = this.container.querySelector('.card-slider');
    this.wrapper = this.slider?.querySelector('.swiper-wrapper');

    this.prevEl = this.container.querySelector('.swiper-btn-prev-pagination.swiper-btn-prev');
    this.nextEl = this.container.querySelector('.swiper-btn-next-pagination.swiper-btn-next');
    this.paginationEl = this.container.querySelector('.swiper-pagination-custom');
    this.controlsWrap = this.container.querySelector('.card-slider-controls');

    this.cardCount = this.wrapper ? this.wrapper.children.length : 0;
    if (!this.slider || this.cardCount === 0) return;

    this.init();
  }

  init() {
    try {
      this.swiper = new Swiper(this.slider, {
        modules: [Navigation, Pagination],
        loop: false,
        speed: 500,
        slidesPerView: 1.1,
        spaceBetween: 10,
        watchOverflow: true, // disables swiping when slides <= slidesPerView for current breakpoint
        navigation: this.prevEl && this.nextEl ? {
          nextEl: this.nextEl,
          prevEl: this.prevEl,
        } : false,
        pagination: this.paginationEl ? {
          el: this.paginationEl,
          type: 'custom',
          renderCustom: (swiper, current, total) => `${current}/${total}`,
        } : false,
        breakpoints: {
          640: { slidesPerView: 1.2, spaceBetween: 22 },
          1024: { slidesPerView: 3, spaceBetween: 24 },
        },
        on: {
          init: () => { this.updateControls(); },
          resize: () => { this.updateControls(); },
          breakpoint: () => { this.updateControls(); },
        }
      });
    } catch (e) {
    }
  }

  updateControls() {
    if (!this.controlsWrap || !this.swiper) return;
    // Mobile (<= 991px): always show controls
    const isMobileUpto991 = window.innerWidth <= 991;
    if (isMobileUpto991) {
      this.controlsWrap.classList.remove('hidden');
      return;
    }
    // Desktop: show only when there are enough slides for current slidesPerView
    const isLocked = this.swiper.isLocked; // true => not enough slides for current slidesPerView
    this.controlsWrap.classList.toggle('hidden', isLocked);
  }


  destroy() {
    if (this.swiper) {
      this.swiper.destroy(true, true);
      this.swiper = null;
    }
  }
}
