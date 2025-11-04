import Swiper from 'swiper';
import { Navigation, Pagination, Grid } from 'swiper/modules';

export default class GalleryBlock {
  constructor(element) {
    this.element = element || document;
    this.swiper = null;
    this.init();
  }

  init() {
    this.initSwiper();
  }

  initSwiper() {
    const galleryContainer = this.element.querySelector('.gallery_items-grid');
    const navigationContainer = this.element.querySelector('.swiper-navigation');

    if (!galleryContainer) {
      return;
    }

    // Count slides
    const slides = galleryContainer.querySelectorAll('.swiper-slide');
    const totalSlides = slides.length;


    // Simple Swiper initialization to show all images in grid
    this.swiper = new Swiper(galleryContainer, {
      modules: [Navigation, Pagination, Grid],
      slidesPerView: 2,
      grid: {
        rows: 3,
        fill: 'row'
      },
      spaceBetween: 16,
      pagination: {
        el: '.swiper-pagination-custom',
        type: 'custom',
        renderCustom: (swiper, current, total) => `${current}/${total}`,
      },
      navigation: {
        nextEl: '.swiper-btn-next-pagination',
        prevEl: '.swiper-btn-prev-pagination',
      },
      breakpoints: {
        320: {
          slidesPerView: 1,
          grid: { rows: 2 },
          spaceBetween: 10
        },
        640: {
          slidesPerView: 1.5,
          grid: { rows: 2 },
          spaceBetween: 10
        },
        768: {
          slidesPerView: 2,
          grid: { rows: 3 },
          spaceBetween: 20
        },
        1024: {
          slidesPerView: 3,
          grid: { rows: 2 },
          spaceBetween: 24
        }
      }
    });

    // Add event listeners for navigation visibility
    this.swiper.on('init', () => this.updateNavigationVisibility());
    this.swiper.on('resize', () => this.updateNavigationVisibility());
    this.swiper.on('update', () => this.updateNavigationVisibility());

    // Initial navigation visibility check
    this.updateNavigationVisibility();

  }

  updateNavigationVisibility() {
    if (!this.swiper) return;
    
    const slides = this.swiper.slides.length;
    const slidesPerView = this.swiper.slidesPerViewDynamic ? this.swiper.slidesPerViewDynamic() : this.swiper.params.slidesPerView;
    const rows = this.swiper.params.grid?.rows || 1;
    const visibleSlides = Math.ceil(slidesPerView * rows);
    
    // Navigation is needed if total slides exceed what's visible on screen
    const needsNavigation = slides > visibleSlides;
    
    // Find navigation containers (both possible locations)
    const navigationContainers = this.element.querySelectorAll('.mt-3.flex.justify-center');
    
    navigationContainers.forEach(container => {
      container.style.display = needsNavigation ? 'flex' : 'none';
    });
    
    // Also update touch/swipe behavior
    if (this.swiper.allowTouchMove !== undefined) {
      this.swiper.allowTouchMove = needsNavigation;
    }
  }

  destroy() {
    if (this.swiper) {
      this.swiper.destroy(true, true);
      this.swiper = null;
    }
  }
}