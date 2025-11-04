import Swiper from 'swiper';
import { Navigation, Pagination, Keyboard, A11y } from 'swiper/modules';

export default class TeamMembersBlock {
  constructor(element) {
    this.element = element || document;
    this.swiperInstance = null;
    this.resizeTimeout = null;
    this.currentCategory = null;
    this.init();
  }

  init() {
    this.setDomMap();
    this.bindEvents();
    this.initializeFirstTab();

    // Initialize swiper for mobile
    if (window.innerWidth < 992) {
      setTimeout(() => {
        this.initializeSingleSwiper();
      }, 100);
    }
  }

  setDomMap() {
    // Category tabs and content
    this.categoryTabs = this.element.querySelectorAll('.team-category-tab');
    this.categoryContents = this.element.querySelectorAll('.team-category-content');
  }


  bindEvents() {
    // Handle window resize with debouncing
    window.addEventListener('resize', this.handleResize.bind(this));

    // Category tabs functionality
    this.categoryTabs.forEach(tab => {
      tab.addEventListener('click', this.handleCategoryClick.bind(this));
    });
  }

  initializeFirstTab() {
    // Ensure the first tab is active and its content is visible
    if (this.categoryTabs.length > 0 && this.categoryContents.length > 0) {
      // Make sure first tab is visually active
      const firstTab = this.categoryTabs[0];
      firstTab.classList.add('bg-primary', 'text-white');
      firstTab.classList.remove('text-gray-600');

      // Make sure first content is visible
      const firstContent = this.categoryContents[0];
      firstContent.classList.remove('hidden');
      firstContent.style.display = 'block';
      this.currentCategory = firstContent.dataset.category;

      // Hide all other content
      for (let i = 1; i < this.categoryContents.length; i++) {
        const content = this.categoryContents[i];
        content.classList.add('hidden');
        content.style.display = 'none';
      }
    }
  }

  initializeSingleSwiper() {
    // Find the currently visible content
    const visibleContent = this.element.querySelector('.team-category-content:not(.hidden)');
    if (!visibleContent) {
      return;
    }

    const swiperEl = visibleContent.querySelector('.team-members-swiper');
    if (!swiperEl) {
      return;
    }

    if (swiperEl.swiper) {
      return; // Already initialized
    }

    const swiperConfig = {
      modules: [Navigation, Pagination, Keyboard, A11y],
      slidesPerView: 1.5,
      spaceBetween: 20,
      loop: false,
      grabCursor: true,

      navigation: {
        nextEl: swiperEl.querySelector('.swiper-button-next'),
        prevEl: swiperEl.querySelector('.swiper-button-prev'),
      },

      pagination: {
        el: swiperEl.querySelector('.swiper-pagination'),
        clickable: true,
      },
    };

    try {
      this.swiperInstance = new Swiper(swiperEl, swiperConfig);
    } catch (error) {
    }
  }

  destroySingleSwiper() {
    if (this.swiperInstance && this.swiperInstance.destroy) {
      this.swiperInstance.destroy(true, true);
      this.swiperInstance = null;
    }
  }

  handleResize() {
    clearTimeout(this.resizeTimeout);
    this.resizeTimeout = setTimeout(() => {
      if (window.innerWidth < 992) {
        // Mobile - ensure swiper is initialized
        if (!this.swiperInstance) {
          this.initializeSingleSwiper();
        }
      } else {
        // Desktop - destroy swiper if exists
        this.destroySingleSwiper();
      }
    }, 100);
  }

  handleCategoryClick(event) {
    const category = event.currentTarget.dataset.category;

    // Don't do anything if we're already on this category
    if (this.currentCategory === category) return;

    // Update tabs
    this.categoryTabs.forEach(tab => {
      tab.classList.remove('bg-primary', 'text-white');
      tab.classList.add('text-gray-600');
    });
    event.currentTarget.classList.remove('text-gray-600');
    event.currentTarget.classList.add('bg-primary', 'text-white');

    // Update content visibility
    this.categoryContents.forEach((content, index) => {
      const contentCategory = content.dataset.category;
      const shouldShow = contentCategory === category;

      if (shouldShow) {
        content.classList.remove('hidden');
        content.style.display = 'block';
        content.style.visibility = 'visible';
        content.style.position = 'static';
        content.style.left = 'auto';
      } else {
        content.classList.add('hidden');
        content.style.display = 'none';
      }
    });

    this.currentCategory = category;

    // Reinitialize swiper on mobile
    if (window.innerWidth < 992) {
      this.destroySingleSwiper();
      setTimeout(() => {
        this.initializeSingleSwiper();
      }, 100);
    }
  }


  destroy() {
    // Clean up event listeners
    window.removeEventListener('resize', this.handleResize.bind(this));

    this.categoryTabs.forEach(tab => {
      tab.removeEventListener('click', this.handleCategoryClick.bind(this));
    });

    // Destroy swiper instance
    this.destroySingleSwiper();

    // Clear timeouts
    if (this.resizeTimeout) {
      clearTimeout(this.resizeTimeout);
    }
  }
}
