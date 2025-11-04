// Import only what you need (reduces bundle size significantly)
import Swiper from 'swiper';
import { Autoplay, Pagination } from 'swiper/modules';
export default class SliderBlock {
  constructor(element) {
    this.element = element;
    this.init();
  }

  init() {
    this.setDomMap();

    // Confirm DOM elements exist before using them
    if (!this.dom.slider || !this.dom.pagination) {
      return;
    }

    this.bindEvents();
  }

  setDomMap() {
    // Use the passed element as scope, or fallback to document
    const scope = this.element || document;

    this.dom = {
      slider: scope.querySelector('.swiper'),
      pagination: scope.querySelector('.swiper-pagination'),
    };
  }

  bindEvents() {
    this.swiper = new Swiper(this.dom.slider, {
      modules: [Autoplay, Pagination],
      speed: 1000,
      loop: true,
      autoplay: {
        delay: 5000,
      },
      grabCursor: true,
      slidesPerView: 1,
      spaceBetween: 0,
      pagination: {
        el: this.dom.pagination,
        clickable: true,
      },
    });
  }

  // Clean destroy method
  destroy() {
    if (this.swiper) {
      this.swiper.destroy(true, true);
      this.swiper = null;
    }
  }
}
