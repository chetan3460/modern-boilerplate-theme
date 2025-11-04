import Swiper from 'swiper';
import { Navigation, Pagination, Autoplay } from 'swiper/modules';

/**
 * ESGSlider - Handles the slider functionality for ESG/CSR block
 * Supports both quote and certification badge layouts
 */
export default class ESGSlider {
  constructor(element) {
    console.log('ESGSlider initialized', element);
    this.el = element;
    // Find the slider container - could be the element itself or a child
    this.container = this.el.classList.contains('esg-slider-container') 
      ? this.el 
      : this.el.querySelector('.esg-slider-container');
    
    if (!this.container) {
      console.error('ESGSlider: slider container not found');
      return;
    }
    
    this.slider = this.container.querySelector('.esg-slider');
    console.log('Slider element:', this.slider);

    if (!this.slider) {
      console.error('ESGSlider: .esg-slider not found');
      return;
    }

    // Navigation elements
    this.prevEl = this.container.querySelector('.swiper-btn-prev');
    this.nextEl = this.container.querySelector('.swiper-btn-next');
    
    // Pagination
    this.paginationEl = this.container.querySelector('.swiper-pagination-custom');
    
    // Check if slider has enough slides
    this.slidesCount = this.slider.querySelectorAll('.swiper-slide').length;
    console.log('Slides count:', this.slidesCount);
    console.log('Navigation buttons:', { prev: this.prevEl, next: this.nextEl });
    
    if (this.slidesCount === 0) {
      console.error('ESGSlider: No slides found');
      return;
    }
    
    this.init();
  }

  init() {
    const swiperConfig = {
      modules: [Navigation, Pagination, Autoplay],
      loop: this.slidesCount > 1,
      speed: 600,
      autoplay: this.slidesCount > 1 ? {
        delay: 5000,
        disableOnInteraction: true,
        pauseOnMouseEnter: true,
      } : false,
      slidesPerView: 1,
      spaceBetween: 24,
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
      
      // Update navigation visibility based on slides count
      this.updateNavigationVisibility();
      
      this.swiper.on('resize', () => this.updateNavigationVisibility());
      this.swiper.on('update', () => this.updateNavigationVisibility());
      
      this.handleResize();
    } catch (error) {
      console.error('ESGSlider initialization failed:', error);
    }
  }

  updateNavigationVisibility() {
    if (!this.swiper) return;
    
    const needsNavigation = this.slidesCount > 1;
    
    // Show/hide navigation controls
    const navigationContainer = this.container.querySelector('.esg-navigation');
    if (navigationContainer) {
      navigationContainer.style.display = needsNavigation ? 'flex' : 'none';
    }
  }

  handleResize() {
    let timer;
    window.addEventListener('resize', () => {
      clearTimeout(timer);
      timer = setTimeout(() => {
        if (this.swiper) {
          this.swiper.update();
        }
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
