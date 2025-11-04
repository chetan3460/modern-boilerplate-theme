import Swiper from 'swiper';
import { Navigation, Pagination, Grid } from 'swiper/modules';

export default class GalleryBlock {
  constructor(element) {
    this.element = element || document;
    this.swiper = null;
    this.init();
  }

  init() {
    this.setDomMap();
    this.initSwiper();
  }

  setDomMap() {
    this.swiperContainer = this.element.querySelector('.gallery_items-grid');
  }

  initSwiper() {
    if (!this.swiperContainer) {
      return;
    }

    // Check if we have multiple slides for loop
    const slides = this.swiperContainer.querySelectorAll('.swiper-slide');
    const totalSlides = slides.length;


    // If we have 6 or fewer slides, show them all without pagination
    if (totalSlides <= 6) {
      // Initialize Swiper to show all slides in grid
      this.swiper = new Swiper(this.swiperContainer, {
        modules: [Grid],
        
        // Show all slides in grid format
        slidesPerView: 2,
        spaceBetween: 16,
        grid: {
          rows: Math.ceil(totalSlides / 2), // Dynamic rows based on slide count
          fill: 'row',
        },
        allowTouchMove: false, // Disable swiping when showing all slides
        
        // Responsive breakpoints
        breakpoints: {
          // Mobile: 2 columns
          480: {
            slidesPerView: 2,
            spaceBetween: 16,
            grid: {
              rows: Math.ceil(totalSlides / 2),
              fill: 'row',
            },
          },
          // Desktop: 3 columns
          1024: {
            slidesPerView: 3,
            spaceBetween: 24,
            grid: {
              rows: Math.ceil(totalSlides / 3),
              fill: 'row',
            },
          },
        },
        
        speed: 600,
        effect: 'slide',
        observer: true,
        observeParents: true,
      });
    } else {
      // More than 6 slides - use pagination
      this.swiper = new Swiper(this.swiperContainer, {
        modules: [Navigation, Pagination, Grid],

        // Show 6 slides per page in grid
        slidesPerView: 2,
        slidesPerGroup: 6,
        spaceBetween: 16,
        grid: {
          rows: 3,
          fill: 'row',
        },
        loop: false,
        
        // Responsive breakpoints
        breakpoints: {
          // Mobile: 2 columns x 3 rows = 6 slides
          480: {
            slidesPerView: 2,
            slidesPerGroup: 6,
            spaceBetween: 16,
            grid: {
              rows: 3,
              fill: 'row',
            },
          },
          // Desktop: 3 columns x 2 rows = 6 slides
          1024: {
            slidesPerView: 3,
            slidesPerGroup: 6,
            spaceBetween: 24,
            grid: {
              rows: 2,
              fill: 'row',
            },
          },
        },
        
        // Navigation arrows
        navigation: {
          nextEl: '.swiper-btn-next',
          prevEl: '.swiper-btn-prev',
        },

        // Pagination dots
        pagination: {
          el: '.swiper-pagination',
          clickable: true,
        },
        
        speed: 600,
        effect: 'slide',
        a11y: { enabled: true },
        keyboard: { enabled: true, onlyInViewport: true },
        grabCursor: true,
        observer: true,
        observeParents: true,
      });
    }

    
    // Handle window resize
    window.addEventListener('resize', this.handleResize.bind(this));
  }

  handleResize() {
    if (this.swiper) {
      this.swiper.update();
    }
  }

  destroy() {
    if (this.swiper) {
      this.swiper.destroy(true, true);
      this.swiper = null;
    }

    window.removeEventListener('resize', this.handleResize.bind(this));
  }
}