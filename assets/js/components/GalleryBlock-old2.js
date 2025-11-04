import Swiper from 'swiper';
import { Grid, Pagination, Navigation } from 'swiper/modules';

export default class GalleryBlock {
  constructor(el) {
    this.el = el || document;
    this.swiper = null;
    this.init();
  }

  init() {
    const container = this.el.querySelector('.gallery_items-grid');
    if (!container) return;

    const slides = container.querySelectorAll('.swiper-slide');
    const total = slides.length;

    const baseConfig = {
      modules: [Grid],
      slidesPerView: 2,
      spaceBetween: 16,
      grid: { rows: Math.ceil(total / 2), fill: 'row' },
      breakpoints: {
        1024: {
          slidesPerView: 3,
          spaceBetween: 24,
          grid: { rows: Math.ceil(total / 3), fill: 'row' },
        },
      },
      observer: true,
      observeParents: true,
    };

    // If more than 6 slides, add pagination & nav with fixed 6-per-page grid
    if (total > 6) {
      baseConfig.modules.push(Pagination, Navigation);
      baseConfig.slidesPerGroup = 6;
      baseConfig.grid = { rows: 3, fill: 'row' };
      baseConfig.breakpoints[1024] = {
        slidesPerView: 3,
        slidesPerGroup: 6,
        spaceBetween: 24,
        grid: { rows: 2, fill: 'row' },
      };
      baseConfig.pagination = { el: '.swiper-pagination', clickable: true };
      baseConfig.navigation = {
        nextEl: '.swiper-btn-next',
        prevEl: '.swiper-btn-prev',
      };
    } else {
      baseConfig.allowTouchMove = false;
    }

    this.swiper = new Swiper(container, baseConfig);
  }
}
