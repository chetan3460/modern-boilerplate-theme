import { gsap } from 'gsap';
import { ScrollTrigger, ScrollSmoother } from "gsap/all";
import SplitText from 'gsap/SplitText';

export default class GSAPAnimations {
  constructor() {
    // Register GSAP plugins
    gsap.registerPlugin(ScrollTrigger, SplitText);
    // Temporarily disabled: ScrollSmoother
    this.init();
  }

  init() {
    // Initialize all animations
    this.initFadeTextAnimation();
    this.initfadeInAnimation();
    this.initFadeInAnimations();
    this.initSlideInAnimations();
    this.initCounterAnimations();
    this.initHeroAnimations();
    this.initLeafBounce();
    this.initAnimateCard();
  }

  // ... (keeping all other existing methods the same)

  // Static method to animate hero slide content when slides change
  static animateHeroSlideContent({ heroTitle, heroSubtitle, heroDescription, ctaBlock }) {
    const tl = gsap.timeline({ delay: 0.2 });

    // Animate hero title with SplitText
    if (heroTitle) {
      try {
        // Store original text
        const originalText = heroTitle.getAttribute('data-original-text') || 
                           heroTitle.textContent || '';

        // Clean up existing SplitText
        if (heroTitle.splitText) {
          heroTitle.splitText.revert();
          delete heroTitle.splitText;
        }

        // Ensure clean text content
        if (originalText.trim()) {
          heroTitle.textContent = originalText.trim();
        }

        // Remove CSS transforms that interfere
        heroTitle.classList.remove('!capitalize', 'capitalize');
        heroTitle.style.setProperty('text-transform', 'none', 'important');

        // Create SplitText
        const split = new SplitText(heroTitle, { 
          type: "chars",
          charsClass: "hero-char"
        });
        
        heroTitle.splitText = split;
        const chars = split.chars;

        if (chars && chars.length > 0) {
          // Apply title case to each character
          const titleCaseText = originalText.trim()
            .toLowerCase()
            .replace(/\b\w+/g, (word) => word.charAt(0).toUpperCase() + word.slice(1));
          
          // Update character content
          for (let i = 0; i < Math.min(titleCaseText.length, chars.length); i++) {
            if (chars[i] && titleCaseText[i]) {
              chars[i].textContent = titleCaseText[i];
              chars[i].style.setProperty('text-transform', 'none', 'important');
            }
          }

          // Set initial state
          gsap.set(chars, {
            opacity: 0,
            y: 30,
            rotationX: 15
          });

          // Animate
          tl.to(chars, {
            opacity: 1,
            y: 0,
            rotationX: 0,
            duration: 0.6,
            ease: 'power2.out',
            stagger: {
              amount: 0.4,
              from: "start"
            }
          });
        } else {
          throw new Error('No characters created');
        }
      } catch (error) {
        // Fallback animation
        
        // Clean up
        if (heroTitle.splitText) {
          heroTitle.splitText.revert();
          delete heroTitle.splitText;
        }

        // Simple animation
        const originalText = heroTitle.getAttribute('data-original-text') || 
                           heroTitle.textContent || '';
        
        if (originalText.trim()) {
          const titleCaseText = originalText.trim()
            .toLowerCase()
            .replace(/\b\w+/g, (word) => word.charAt(0).toUpperCase() + word.slice(1));
          heroTitle.textContent = titleCaseText;
        }

        heroTitle.style.setProperty('text-transform', 'none', 'important');

        tl.fromTo(heroTitle,
          { opacity: 0, y: 40 },
          { opacity: 1, y: 0, duration: 0.8, ease: 'power2.out' }
        );
      }
    }

    // Animate other elements
    if (heroSubtitle) {
      tl.fromTo(heroSubtitle,
        { opacity: 0, y: 40 },
        { opacity: 1, y: 0, duration: 0.8, ease: 'power2.out' },
        '-=0.6'
      );
    }

    if (heroDescription) {
      tl.fromTo(heroDescription,
        { opacity: 0, y: 30 },
        { opacity: 1, y: 0, duration: 0.7, ease: 'power2.out' },
        '-=0.5'
      );
    }

    if (ctaBlock) {
      tl.fromTo(ctaBlock,
        { opacity: 0, y: 30 },
        { opacity: 1, y: 0, duration: 0.7, ease: 'power2.out' },
        '-=0.4'
      );
    }
  }
}