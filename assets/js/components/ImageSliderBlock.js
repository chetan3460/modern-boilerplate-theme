import Swiper from 'swiper';
import { Navigation, Pagination, Autoplay, EffectFade } from 'swiper/modules';
import { gsap } from 'gsap';
import SplitText from 'gsap/SplitText';

// Enhanced ImageSliderBlock with effects and text animations
export default class ImageSliderBlock {
  constructor(element) {
    this.el = element;
    this.slider = this.el.querySelector('.image-slider');
    this.prevBtn = this.el.querySelector('.swiper-btn-prev');
    this.nextBtn = this.el.querySelector('.swiper-btn-next');
    this.pagination = this.el.querySelector('.swiper-pagination');
    
    // Animation state
    this.currentAnimations = [];
    this.splitTextInstances = [];
    this.isFirstSlide = true;

    if (!this.slider) return;
    this.init();
  }

  init() {
    const slideCount = this.slider.querySelectorAll('.swiper-slide').length;
    if (slideCount === 0) return;

    const swiperConfig = {
      modules: [Navigation, Pagination, Autoplay, EffectFade],
      loop: slideCount > 1,
      speed: 800, // Smooth fade transition
      
      // Add fade effect
      effect: 'fade',
      fadeEffect: {
        crossFade: true
      },

      // Autoplay with longer delay for content reading
      autoplay: slideCount > 1 ? {
        delay: 6000,
        disableOnInteraction: false,
        pauseOnMouseEnter: true,
      } : false,

      slidesPerView: 1,
      spaceBetween: 0,

      // Navigation
      navigation: (this.prevBtn && this.nextBtn && slideCount > 1) ? {
        nextEl: this.nextBtn,
        prevEl: this.prevBtn,
      } : false,

      // Pagination
      pagination: (this.pagination && slideCount > 1) ? {
        el: this.pagination,
        clickable: true,
        type: 'bullets',
      } : false,
      
      // Slide change events for animations
      on: {
        slideChangeTransitionStart: () => {
          // Start new slide animation immediately, no delay
          this.hideCurrentSlideContent();
          this.isFirstSlide = false;
          // Start immediately to prevent any white flash
          this.animateCurrentSlideContent();
        },
        init: () => {
          // Animate first slide on init with minimal delay
          setTimeout(() => this.animateCurrentSlideContent(), 100);
        }
      }
    };

    try {
      this.swiper = new Swiper(this.slider, swiperConfig);
    } catch (error) {
      // Error initializing swiper
    }
  }

  hideCurrentSlideContent() {
    // Kill current animations
    this.currentAnimations.forEach(anim => anim.kill());
    this.currentAnimations = [];
    
    // Hide all slide content for transition
    const slides = this.slider.querySelectorAll('.swiper-slide');
    slides.forEach(slide => {
      const content = slide.querySelector('.slide-content');
      
      if (content) {
        gsap.set(content, { opacity: 0 });
      }
    });
  }
  
  animateCurrentSlideContent() {
    if (!this.swiper) return;
    
    const activeSlide = this.swiper.slides[this.swiper.activeIndex];
    if (!activeSlide) return;
    
    const content = activeSlide.querySelector('.slide-content');
    const title = activeSlide.querySelector('.slide-title');
    const description = activeSlide.querySelector('.slide-description');
    
    if (!activeSlide) return;
    
    // Create timeline for this slide's animations
    const tl = gsap.timeline();
    this.currentAnimations.push(tl);
    
    // Fade in the content container (if exists)
    if (content) {
      tl.fromTo(content, 
        { opacity: 0 }, 
        { opacity: 1, duration: 0.4, ease: 'power2.out' },
        0
      );
    }
    
    // Different timing for first slide vs slide changes
    const baseDelay = 0.2; // Base delay after content starts
    const titleDelay = this.isFirstSlide ? baseDelay : baseDelay;
    const descriptionDelay = this.isFirstSlide ? (baseDelay + 0.6) : baseDelay;
    
    // Animate title with split text if available
    if (title) {
      try {
        const splitTitle = new SplitText(title, { type: 'chars,words' });
        this.splitTextInstances.push(splitTitle);
        
        tl.fromTo(splitTitle.chars,
          {
            opacity: 0,
            y: 20,
            rotationX: -45
          },
          {
            opacity: 1,
            y: 0,
            rotationX: 0,
            duration: 0.5,
            stagger: 0.01,
            ease: 'power2.out'
          },
          titleDelay
        );
      } catch (e) {
        // Fallback if SplitText fails
        tl.fromTo(title,
          { opacity: 0, y: 20 },
          { opacity: 1, y: 0, duration: 0.5, ease: 'power2.out' },
          titleDelay
        );
      }
    }
    
    // Animate description with conditional delay
    if (description) {
      tl.fromTo(description,
        {
          opacity: 0,
          y: 20
        },
        {
          opacity: 1,
          y: 0,
          duration: 0.6,
          ease: 'power2.out'
        },
        descriptionDelay
      );
    }
  }
  
  cleanupSplitText() {
    // Revert all split text instances
    this.splitTextInstances.forEach(split => {
      if (split && split.revert) {
        split.revert();
      }
    });
    this.splitTextInstances = [];
  }

  destroy() {
    // Kill all animations
    this.currentAnimations.forEach(anim => anim.kill());
    this.currentAnimations = [];
    
    // Cleanup split text
    this.cleanupSplitText();
    
    // Destroy swiper
    if (this.swiper) {
      this.swiper.destroy(true, true);
      this.swiper = null;
    }
  }
}
