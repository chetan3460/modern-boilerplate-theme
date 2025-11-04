// Simple Team Tabs with Swiper Grid for Desktop and Regular Swiper for Mobile
import Swiper from 'swiper';
import { Navigation, Pagination, Grid } from 'swiper/modules';
import 'swiper/css';
import 'swiper/css/navigation';
import 'swiper/css/pagination';
import 'swiper/css/grid';

export default class SimpleTeamTabs {
  constructor(root = document) {
    this.root = root.querySelector('[data-component="SimpleTeamTabs"]') || root;
    if (!this.root) return;

    // Support sliding tabs for teams and galleries, toggle tabs, and legacy tabs
    this.tabs = this.root.querySelectorAll('.team-slide-tab, .gallery-slide-tab, .team-toggle-tab, .tab');
    this.wrappers = this.root.querySelectorAll('.team-category-wrapper, .gallery-category-wrapper');
    this.swipers = [];
    
    // Get the sliding background element (works for both team and gallery tabs)
    this.tabSlider = this.root.querySelector('.team-tab-slider, .gallery-tab-slider');

    this.init();
  }

  init() {
    // Set up tab click handlers
    this.tabs.forEach((tab, index) => {
      tab.style.cursor = 'pointer';
      tab.style.touchAction = 'manipulation';

      tab.addEventListener('click', (e) => {
        e.preventDefault();
        this.switchTab(index);
      });
    });

    // Initialize all swipers
    this.initAllSwipers();

    // Set first tab active
    if (this.tabs.length > 0) {
      this.switchTab(0);
    }
  }

  switchTab(activeIndex) {
    // Update tab states
    this.tabs.forEach((tab, index) => {
      const isSlideTab = tab.classList.contains('team-slide-tab') || tab.classList.contains('gallery-slide-tab');
      const isToggleTab = tab.classList.contains('team-toggle-tab');
      
      if (index === activeIndex) {
        tab.classList.add('active');
        if (isSlideTab) {
          // For slide tabs, CSS handles text color automatically
          tab.classList.remove('text-primary');
          tab.classList.add('text-white');
          // Force font weight with direct style
          tab.style.fontWeight = 'bold';
          tab.style.color = 'white';
        } else if (isToggleTab) {
          // For toggle tabs, CSS handles the styling automatically
          tab.classList.remove('bg-white', 'text-primary');
          tab.classList.add('bg-primary', 'border-primary', 'text-white');
        } else {
          // Legacy tab styling
          tab.classList.add('bg-primary', 'text-white');
          tab.classList.remove('text-gray-600');
        }
      } else {
        tab.classList.remove('active');
        if (isSlideTab) {
          // For slide tabs, CSS handles text color automatically
          tab.classList.remove('text-white');
          tab.classList.add('text-primary');
          // Force font weight with direct style
          tab.style.fontWeight = 'normal';
          tab.style.color = '#DA000E';
        } else if (isToggleTab) {
          // For toggle tabs, CSS handles the styling automatically
          tab.classList.remove('bg-primary', 'text-white');
          tab.classList.add('bg-white', 'border-primary', 'text-primary');
        } else {
          // Legacy tab styling
          tab.classList.remove('bg-primary', 'text-white');
          tab.classList.add('text-gray-600');
        }
      }
    });

    // Animate the sliding background for slide tabs (both team and gallery)
    if (this.tabSlider && this.tabs.length > 0 && (this.tabs[0].classList.contains('team-slide-tab') || this.tabs[0].classList.contains('gallery-slide-tab'))) {
      const totalTabs = this.tabs.length;
      const tabWidth = 100 / totalTabs;
      const leftPosition = `calc(${tabWidth * activeIndex}% + 4px)`;
      this.tabSlider.style.left = leftPosition;
    }

    // Update wrapper visibility
    this.wrappers.forEach((wrapper, index) => {
      if (index === activeIndex) {
        wrapper.classList.add('active');
        wrapper.style.display = 'block';
      } else {
        wrapper.classList.remove('active');
        wrapper.style.display = 'none';
      }
    });

    // Update swipers if needed
    if (this.swipers[activeIndex]) {
      setTimeout(() => {
        this.swipers[activeIndex].update();
      }, 100);
    }
  }

  initAllSwipers() {
    this.wrappers.forEach((wrapper, index) => {
      const swiperEl = wrapper.querySelector('.swiper');
      if (!swiperEl) return;

      const slides = swiperEl.querySelectorAll('.swiper-slide');
      if (slides.length === 0) return;

      const isMobile = window.innerWidth <= 1023;
      const nextEl = wrapper.querySelector('.swiper-btn-next');
      const prevEl = wrapper.querySelector('.swiper-btn-prev');
      const paginationEl = wrapper.querySelector('.swiper-pagination');
      const paginationCustom = wrapper.querySelector('.swiper-pagination-custom');

      try {
        const swiperConfig = {
          modules: [Navigation, Pagination, Grid],
          loop: false,
          watchOverflow: true,
          observer: true,
          observeParents: true,
          navigation: {
            nextEl: nextEl,
            prevEl: prevEl,
          },
          pagination: paginationCustom
            ? {
              el: paginationCustom,
              type: 'custom',
              renderCustom: (swiper, current, total) => `${current}/${total}`,
            }
            : paginationEl
              ? {
                el: paginationEl,
                clickable: true,
              }
              : false

        };

        // Detect if this is a gallery block (vs team members)
        const isGalleryBlock = this.root.classList.contains('gallery_block');
        
        if (isGalleryBlock) {
          // Gallery-specific Swiper configuration
          if (isMobile) {
            Object.assign(swiperConfig, {
              slidesPerView: 1,
              spaceBetween: 10,
              breakpoints: {
                640: { slidesPerView: 1.5, spaceBetween: 12 },
                768: { slidesPerView: 2, spaceBetween: 16 },
              }
            });
          } else {
            // Desktop: Gallery - always 3 slides per view
            const slideCount = slides.length;
            Object.assign(swiperConfig, {
              slidesPerView: 3,
              spaceBetween: 24,
              slidesPerGroup: 3,
              loop: false,
              centeredSlides: false,
              breakpoints: {
                1024: {
                  slidesPerView: 3,
                  spaceBetween: 20,
                  slidesPerGroup: 3
                },
                1280: {
                  slidesPerView: 3,
                  spaceBetween: 24,
                  slidesPerGroup: 3
                }
              }
            });
          }
        } else {
          // Team members configuration
          if (isMobile) {
            // Mobile: Match NewsSlider configuration
            Object.assign(swiperConfig, {
              slidesPerView: 1,
              spaceBetween: 10,
              breakpoints: {
                640: { slidesPerView: 2, spaceBetween: 12 },
                768: { slidesPerView: 2, spaceBetween: 12 },
              }
            });
          } else {
            // Desktop: Grid layout showing 6 items (3x2 grid)
            const slideCount = slides.length;
            const rows = slideCount <= 3 ? 1 : 2; // 1 row for ≤3 items, 2 rows for more
            const cols = 3; // Always 3 columns on desktop

            Object.assign(swiperConfig, {
              slidesPerView: cols,
              spaceBetween: 32,
              slidesPerGroup: slideCount <= 6 ? slideCount : 6, // Show all items if ≤6, otherwise group by 6
              grid: {
                rows: rows,
                fill: 'row'
              },
              breakpoints: {
                1024: {
                  slidesPerView: cols,
                  spaceBetween: 12,
                  slidesPerGroup: slideCount <= 6 ? slideCount : 6,
                  grid: {
                    rows: rows,
                    fill: 'row'
                  }
                },
                1280: {
                  slidesPerView: cols,
                  spaceBetween: 24,
                  slidesPerGroup: slideCount <= 6 ? slideCount : 6,
                  grid: {
                    rows: rows,
                    fill: 'row'
                  }
                }
              }
            });
          }
        }

        const swiperInstance = new Swiper(swiperEl, swiperConfig);
        this.swipers[index] = swiperInstance;

      } catch (error) {
        // Fallback: Try creating a swiper without Grid module
        try {
          const basicConfig = {
            modules: [Navigation, Pagination],
            slidesPerView: isMobile ? 1 : Math.min(3, slides.length),
            slidesPerGroup: isMobile ? 1 : Math.min(3, slides.length),
            spaceBetween: isMobile ? 10 : 32,
            loop: false,
            watchOverflow: true,
            observer: true,
            observeParents: true,
            navigation: {
              nextEl: nextEl,
              prevEl: prevEl,
            },
            pagination: paginationCustom
              ? {
                el: paginationCustom,
                type: 'custom',
                renderCustom: (swiper, current, total) => `${current}/${total}`,
              }
              : paginationEl
                ? {
                  el: paginationEl,
                  clickable: true,
                }
                : false,
            breakpoints: {
              640: { slidesPerView: Math.min(2, slides.length), spaceBetween: 12 },
              768: { slidesPerView: Math.min(2, slides.length), spaceBetween: 12 },
              1024: {
                slidesPerView: 3,
                slidesPerGroup: Math.min(6, slides.length),
                spaceBetween: 32,
              },
              1280: {
                slidesPerView: 3,
                slidesPerGroup: Math.min(6, slides.length),
                spaceBetween: 40,
              }
            }
          };

          const fallbackSwiper = new Swiper(swiperEl, basicConfig);
          this.swipers[index] = fallbackSwiper;

        } catch (fallbackError) {
          // Silently fail - swiper not critical for functionality
        }
      }
    });
  }

  destroyAllSwipers() {
    this.swipers.forEach((swiper) => {
      if (swiper && swiper.destroy) {
        try {
          swiper.destroy(true, true);
        } catch (error) {
          // Silently handle destroy errors
        }
      }
    });
    this.swipers = [];
  }
}