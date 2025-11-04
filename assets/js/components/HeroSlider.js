// Import only what you need
import Swiper from "swiper";
import { Navigation, Pagination, Autoplay, EffectFade } from "swiper/modules";
import GSAPAnimations from './GSAPAnimations.js';

export default class HeroSlider {
  constructor() {
    // Cache selectors
    this.heroEl = document.querySelector(".hero-slider");
    this.spotEl = document.querySelector(".spotlight-slider");
    
    // Animation debounce tracking
    this.animationTimeout = null;
    this.isAnimating = false;

    this.initHero();
    this.initSpotlight();
  }


  initHero() {
    if (!this.heroEl) return;

    const heroNext = document.querySelector(".swiper-button-next");
    const heroPrev = document.querySelector(".swiper-button-prev");
    const heroPag = document.querySelector(".swiper-pagination");

    this.heroSwiper = new Swiper(this.heroEl, {
      modules: [Autoplay, Navigation, Pagination, EffectFade],
      loop: true,
      // rewind: true,
      // initialSlide: 0,
      speed: 800,
      effect: "fade",
      slidesPerView: 1,
      fadeEffect: { crossFade: true },
      autoplay: { delay: 5000, disableOnInteraction: false },
      pagination: heroPag
        ? { el: heroPag, clickable: true, type: "bullets" }
        : false,
      navigation: heroNext && heroPrev
        ? { nextEl: heroNext, prevEl: heroPrev }
        : false,
      on: {
        slideChangeTransitionStart: (swiper) => {
          // Clear any existing animation timeout
          if (this.animationTimeout) {
            clearTimeout(this.animationTimeout);
          }
          
          // Debounce animations for better manual swipe handling
          this.animationTimeout = setTimeout(() => {
            this.animateSlideContent(swiper.realIndex);
          }, 100); // Small delay to ensure slide transition has started
        },
        touchStart: () => {
          // Flag that user is interacting
          this.isAnimating = false;
        }
      },
    });
  }

  animateSlideContent(activeIndex) {
    // Prevent multiple simultaneous animations
    if (this.isAnimating) return;
    this.isAnimating = true;
    
    // Get the active slide
    const activeSlide = this.heroEl.querySelector(`.swiper-slide[data-swiper-slide-index="${activeIndex}"]`);
    if (!activeSlide) {
      this.isAnimating = false;
      return;
    }

    // Find elements within the active slide
    const heroTitle = activeSlide.querySelector('.hero-title');
    const heroSubtitle = activeSlide.querySelector('.hero-subtitle');
    const heroDescription = activeSlide.querySelector('.hero-description');
    const ctaBlock = activeSlide.querySelector('.cta-block');
    
    // Store original text content in data attributes if not already stored
    if (heroTitle && !heroTitle.hasAttribute('data-original-text')) {
      const originalText = heroTitle.textContent || heroTitle.innerText || '';
      if (originalText.trim()) {
        heroTitle.setAttribute('data-original-text', originalText.trim());
      }
    }

    // Call the static method from GSAPAnimations to animate these elements
    GSAPAnimations.animateHeroSlideContent({
      heroTitle,
      heroSubtitle,
      heroDescription,
      ctaBlock
    });
    
    // Reset animation flag after animation completes
    setTimeout(() => {
      this.isAnimating = false;
    }, 1000); // Adjust based on your longest animation duration
  }

  initSpotlight() {
    if (!this.spotEl) return;

    // Only init if more than one slide
    if (this.spotEl.querySelectorAll(".swiper-slide").length <= 1) return;

    const spotPag = document.querySelector(".spotlight-pagination");

    new Swiper(this.spotEl, {
      modules: [Pagination, Autoplay],
      loop: true,
      speed: 800,
      slidesPerView: 1,
      autoplay: { delay: 4000, disableOnInteraction: true },
      pagination: spotPag
        ? { el: spotPag, clickable: true }
        : undefined,
    });
  }
}
