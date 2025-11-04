import { gsap } from 'gsap';
import { ScrollTrigger } from "gsap/all";
import SplitText from 'gsap/SplitText';
export default class GSAPAnimations {
  constructor() {
    // Prevent double initialization
    if (window.gsapAnimationsInitialized) {
      return;
    }
    
    // Register GSAP plugins
    gsap.registerPlugin(ScrollTrigger, SplitText);
    this.init();
    window.gsapAnimationsInitialized = true;
  }

  init() {
    // Initialize all animations
    this.initFadeTextAnimation();
    this.initfadeInAnimation();

    this.initFadeInAnimations();
    this.initSlideInAnimations();
    // this.initStaggerAnimations();
    this.initCounterAnimations();
    this.initHeroAnimations();
    this.initLeafBounce();
    this.initAnimateCard();
    // this.initScrollDriftFrame();
    this.fadeUpStagger();
    this.animateCards();

  }

  initFadeTextAnimation() {
    const animatedTextElements = document.querySelectorAll(".fade-text");

    if (animatedTextElements.length > 0) {
      const staggerAmount = 0.03;
      const translateXValue = 20;
      const delayValue = 0.1;
      const easeType = "power2.out";

      animatedTextElements.forEach((element) => {
        // Split text into characters and words
        const animationSplitText = new SplitText(element, { type: "chars, words" });

        // Animate characters
        gsap.from(animationSplitText.chars, {
          duration: 1,
          delay: delayValue,
          x: translateXValue,
          autoAlpha: 0,
          stagger: staggerAmount,
          ease: easeType,
          scrollTrigger: {
            trigger: element,
            start: "top 85%",
          },
        });
      });
    }




  }

  // fade In
  initfadeInAnimation = () => {
    const fadeIn = document.querySelectorAll(".fade-in");
    if (fadeIn.length) {
      fadeIn.forEach((container) => {
        let fadeInTimeline = gsap.timeline({
          scrollTrigger: {
            trigger: container,
            start: "50px bottom",
          },
        });
        let delay = container.getAttribute("data-delay");
        fadeInTimeline.to(
          container,
          {
            opacity: 1,
            duration: 1,
            onComplete: () => {
              ScrollTrigger.refresh();
            },
          },
          delay
        );
      });
    }
  };


  // Fade in animations with ScrollTrigger (certificates excluded)
  initFadeInAnimations() {
    // Handle animate-fade-in elements (excluding certificate items)
    const fadeInElements = gsap.utils.toArray('.animate-fade-in:not(.certificate_items-item)');
    fadeInElements.forEach((element) => {
      gsap.fromTo(
        element,
        {
          opacity: 0,
          y: 30,
        },
        {
          opacity: 1,
          y: 0,
          duration: 1,
          ease: 'power2.out',
          scrollTrigger: {
            trigger: element,
            start: 'top 85%',
            end: 'top 50%',
            toggleActions: 'play none none reverse',
          },
        }
      );
    });
  }

  // Fade in up animations (existing Tailwind class support)
  initSlideInAnimations() {
    gsap.utils.toArray('.animate-fade-in-up').forEach((element) => {
      gsap.fromTo(
        element,
        {
          opacity: 0,
          y: 50,
        },
        {
          opacity: 1,
          y: 0,
          duration: 0.8,
          ease: 'power2.out',
          scrollTrigger: {
            trigger: element,
            start: 'top 90%',
            end: 'top 60%',
            toggleActions: 'play none none reverse',
          },
        }
      );
    });
  }


  // Enhanced counter animations
  initCounterAnimations() {
    gsap.utils.toArray('[data-count]').forEach((counter) => {
      const target = parseInt(counter.getAttribute('data-count'));

      gsap.fromTo(
        counter,
        {
          textContent: 0,
        },
        {
          textContent: target,
          duration: 2,
          ease: 'power2.out',
          snap: { textContent: 1 },
          scrollTrigger: {
            trigger: counter,
            start: 'top 80%',
            end: 'top 50%',
            toggleActions: 'play none none reverse',
          },
          onUpdate: function () {
            counter.textContent = Math.ceil(counter.textContent).toLocaleString();
          },
        }
      );
    });
  }

  // Leaf bounce animation for decorative shapes
  initLeafBounce() {
    const leaves = document.querySelectorAll('[data-leaf-bounce]');
    if (!leaves.length) return;

    leaves.forEach((leaf) => {
      gsap.set(leaf, { y: -120, opacity: 0 });
      const tl = gsap.timeline({
        scrollTrigger: {
          trigger: leaf,
          start: 'top 85%', // when leaf is near viewport
          toggleActions: 'play none none none',
          once: true,
        }
      });

      // Smooth, slow drop-in
      tl.to(leaf, { y: 0, opacity: 1, duration: 1.6, ease: 'expo.out' })
        // Gentle Bounce 1
        .to(leaf, { y: -24, duration: 0.75, ease: 'sine.out' })
        .to(leaf, { y: 0, duration: 0.95, ease: 'sine.inOut' })
        // Gentle Bounce 2 (smaller)
        .to(leaf, { y: -12, duration: 0.65, ease: 'sine.out' })
        .to(leaf, { y: 0, duration: 0.85, ease: 'sine.inOut' });
    });
  }





  // Hero section animations
  initHeroAnimations() {
    const heroTitle = document.querySelector('.hero-title');
    const heroSubtitle = document.querySelector('.hero-subtitle');
    const heroDescription = document.querySelector('.hero-description');
    const heroCTA = document.querySelector('.cta-block .btn');
    const ctaBlock = document.querySelector('.cta-block');

    // Only proceed if any hero elements exist
    if (heroTitle || heroSubtitle || heroDescription || heroCTA || ctaBlock) {
      // Immediately hide all elements to prevent flicker
      gsap.set([heroTitle, heroSubtitle, heroDescription, ctaBlock], { opacity: 0 });

      const tl = gsap.timeline({ delay: 0.5 });

      // Animate hero title with SplitText if available
      if (heroTitle) {
        try {
          gsap.registerPlugin(SplitText);

          // Split into characters
          const split = new SplitText(heroTitle, { type: "chars" });
          const chars = split.chars;

          gsap.set(chars, { opacity: 0, y: 50 });

          tl.set(heroTitle, { opacity: 1 });
          tl.to(chars, {
            opacity: 1,
            y: 0,
            duration: 0.8,
            ease: 'power2.out',
            stagger: { amount: 0.6, from: "start" }
          });
        } catch (error) {
          // Fallback animation if SplitText fails
          tl.fromTo(heroTitle,
            { opacity: 0, y: 60 },
            { opacity: 1, y: 0, duration: 1.2, ease: 'power3.out' }
          );
        }
      }

      // Animate subtitle
      if (heroSubtitle) {
        gsap.set(heroSubtitle, { y: 40 }); // Set initial position
        tl.to(heroSubtitle,
          { opacity: 1, y: 0, duration: 0.8, ease: 'power2.out' },
          '-=0.6'
        );
      }

      // Animate description
      if (heroDescription) {
        gsap.set(heroDescription, { y: 30 }); // Set initial position
        tl.to(heroDescription,
          { opacity: 1, y: 0, duration: 0.7, ease: 'power2.out' },
          '-=0.5'
        );
      }

      // Animate CTA block or individual button
      if (ctaBlock) {
        gsap.set(ctaBlock, { y: 30 }); // Set initial position
        tl.to(ctaBlock,
          { opacity: 1, y: 0, duration: 0.7, ease: 'power2.out' },
          '-=0.4'
        );
      } else if (heroCTA) {
        gsap.set(heroCTA, { y: 30 }); // Set initial position
        tl.to(heroCTA,
          { opacity: 1, y: 0, duration: 0.7, ease: 'power2.out' },
          '-=0.4'
        );
      }
    }
  }




  // Utility method to create custom animations
  static createScrollAnimation(element, fromProps, toProps, options = {}) {
    const defaultOptions = {
      duration: 1,
      ease: 'power2.out',
      scrollTrigger: {
        trigger: element,
        start: 'top 85%',
        end: 'top 50%',
        toggleActions: 'play none none reverse',
      },
    };

    const mergedOptions = { ...defaultOptions, ...options, ...toProps };

    return gsap.fromTo(element, fromProps, mergedOptions);
  }

  // Method to refresh ScrollTrigger (useful for dynamic content)
  static refresh() {
    ScrollTrigger.refresh();
  }

  // Method to kill all ScrollTrigger instances
  static cleanup() {
    ScrollTrigger.killAll();
  }

  // Utility method to clean up SplitText instances
  static cleanupSplitText(element) {
    if (element && element.splitText) {
      try {
        element.splitText.revert();
        delete element.splitText;
      } catch (error) {
      }
    }
  }

  // Utility method to preserve and restore text content
  static preserveTextContent(element) {
    if (!element) return '';

    let originalText = element.getAttribute('data-original-text');
    if (!originalText) {
      originalText = element.textContent || element.innerText || '';
      if (originalText.trim()) {
        element.setAttribute('data-original-text', originalText.trim());
      }
    }
    return originalText.trim();
  }




  // Static method to animate hero slide content when slides change
  static animateHeroSlideContent({ heroTitle, heroSubtitle, heroDescription, ctaBlock }) {
    const tl = gsap.timeline({ delay: 0.2 }); // Reduced delay for better manual swipe experience

    // Animate hero title with improved text preservation
    if (heroTitle) {
      try {
        // Register SplitText plugin
        gsap.registerPlugin(SplitText);

        // Store original text content FIRST to prevent any loss
        const originalTextContent = GSAPAnimations.preserveTextContent(heroTitle);

        // Check if this element already has SplitText applied and revert first
        GSAPAnimations.cleanupSplitText(heroTitle);

        // Remove problematic CSS classes that could interfere
        heroTitle.classList.remove('!capitalize', 'capitalize');

        // Ensure the original text is preserved
        if (originalTextContent.trim()) {
          heroTitle.textContent = originalTextContent.trim();
        }

        // Create SplitText instance with better error handling
        const split = new SplitText(heroTitle, {
          type: "chars",
          charsClass: "hero-char"
        });

        // Store reference for cleanup
        heroTitle.splitText = split;
        const chars = split.chars;

        if (chars && chars.length > 0) {
          // Verify text integrity - SplitText often removes spaces, so normalize both
          const splitTextContent = chars.map(char => char.textContent).join('');

          // Remove ALL whitespace for comparison since SplitText removes spaces
          const normalizedOriginal = originalTextContent.trim().replace(/\s+/g, '');
          const normalizedSplit = splitTextContent.replace(/\s+/g, '');

          // Continue with animation even if there's a minor mismatch
          if (normalizedSplit !== normalizedOriginal) {
          }

          // Apply title case transformation - but remove spaces to match SplitText behavior
          const convertToTitleCase = (text) => {
            return text.toLowerCase().replace(/\b\w+/g, (word) =>
              word.charAt(0).toUpperCase() + word.slice(1)
            );
          };

          const titleCaseText = convertToTitleCase(originalTextContent.trim());
          // Remove spaces to match what SplitText created
          const titleCaseNoSpaces = titleCaseText.replace(/\s+/g, '');

          // Update each character element - use the space-removed version
          const charactersToUpdate = Math.min(titleCaseNoSpaces.length, chars.length);
          for (let i = 0; i < charactersToUpdate; i++) {
            if (chars[i] && titleCaseNoSpaces[i]) {
              chars[i].textContent = titleCaseNoSpaces[i];
              // Ensure no CSS transformations interfere
              chars[i].style.setProperty('text-transform', 'none', 'important');
            }
          }

          // Override parent text-transform
          heroTitle.style.setProperty('text-transform', 'none', 'important');

          // Set initial animation state
          gsap.set(chars, {
            opacity: 0,
            y: 30, // Reduced movement for smoother animation
            rotationX: 15 // Subtle 3D effect
          });

          // Animate characters with optimized stagger
          tl.to(chars, {
            opacity: 1,
            y: 0,
            rotationX: 0,
            duration: 0.6, // Faster for better manual swipe experience
            ease: 'power2.out',
            stagger: {
              amount: 0.4, // Reduced stagger time
              from: "start"
            }
          });
        } else {
          // No characters created - use fallback
          throw new Error('No characters created - using fallback animation');
        }
      } catch (error) {

        // Clean up any partial SplitText
        if (heroTitle.splitText) {
          heroTitle.splitText.revert();
          delete heroTitle.splitText;
        }

        // Preserve and restore original text
        const originalText = heroTitle.getAttribute('data-original-text') ||
          heroTitle.textContent ||
          heroTitle.innerText || '';

        if (originalText.trim()) {
          // Apply title case manually for fallback
          const titleCaseText = originalText.trim()
            .toLowerCase()
            .replace(/\b\w+/g, (word) => word.charAt(0).toUpperCase() + word.slice(1));

          heroTitle.textContent = titleCaseText;
        }

        // Remove problematic classes and override CSS
        heroTitle.classList.remove('!capitalize', 'capitalize');
        heroTitle.style.setProperty('text-transform', 'none', 'important');

        // Fallback animation - simple and reliable
        tl.fromTo(
          heroTitle,
          {
            opacity: 0,
            y: 40,
          },
          {
            opacity: 1,
            y: 0,
            duration: 0.8, // Faster for manual swipe
            ease: 'power2.out',
          }
        );
      }
    }

    // Animate hero subtitle
    if (heroSubtitle) {
      tl.fromTo(
        heroSubtitle,
        {
          opacity: 0,
          y: 40,
        },
        {
          opacity: 1,
          y: 0,
          duration: 0.8,
          ease: 'power2.out',
        },
        '-=0.6'
      );
    }

    // Animate hero description
    if (heroDescription) {
      tl.fromTo(
        heroDescription,
        {
          opacity: 0,
          y: 30,
        },
        {
          opacity: 1,
          y: 0,
          duration: 0.7,
          ease: 'power2.out',
        },
        '-=0.5'
      );
    }

    // Animate CTA block
    if (ctaBlock) {
      tl.fromTo(
        ctaBlock,
        {
          opacity: 0,
          y: 30,
        },
        {
          opacity: 1,
          y: 0,
          duration: 0.7,
          ease: 'power2.out',
        },
        '-=0.4'
      );
    }
  }

  initAnimateCard = () => {
    const triggerSlices = [...document.querySelectorAll('.section-triger')];

    triggerSlices.forEach((section) => {
      const slices = section.querySelectorAll(".uncover_slice");
      const image = section.querySelector(".myimg");

      const tl = gsap.timeline({
        scrollTrigger: {
          trigger: section,
          start: "50% bottom",
          markers: false,
          once: true,
        }
      });

      tl.to(slices, {
        height: 0,
        ease: 'power6.inOut',
        duration: 0.6,
        stagger: { each: 0.3 }
      }, 'start')
        .to(image, {
          // scale: 1.3,
          duration: 1.5,
          ease: 'power6.inOut'
        }, 'start');
    });

  }
  // fade Up Stagger
  fadeUpStagger = () => {
    const fadeUpWrapper = gsap.utils.toArray(".fade-up-stagger-wrap");

    if (fadeUpWrapper.length) {
      fadeUpWrapper.forEach((fadeUpWrap) => {
        const fadeUp = fadeUpWrap.querySelectorAll(".fade-up-stagger");
        let delay = fadeUpWrap.getAttribute("data-delay");
        gsap.to(fadeUp, {
          scrollTrigger: {
            trigger: fadeUpWrap,
            start: "5% 100%",
          },
          opacity: 1,
          y: 0,
          duration: 1,
          delay: delay,
          stagger: 0.2,
        });
      });
    }


    const animateInUp = document.querySelectorAll(".anim-uni-in-up");
    animateInUp.forEach((e => {
      gsap.fromTo(e, {
        opacity: 0,
        y: 50,
        ease: "sine"
      }, {
        y: 0,
        opacity: 1,
        scrollTrigger: {
          trigger: e,
          toggleActions: "play none none reverse"
        }
      })
    }
    ));
  };

  animateCards = () => {
    // Ensure GSAP and ScrollTrigger are loaded
    // gsap.registerPlugin(ScrollTrigger);

    const cards = document.querySelectorAll(".animate-card-3");
    if (!cards.length) return; // stop if no elements found

    // Set initial state
    gsap.set(cards, {
      y: 50,
      opacity: 0
    });

    // Batch animation
    ScrollTrigger.batch(cards, {
      interval: 0.1,
      batchMax: Infinity,
      onEnter: (batch) =>
        gsap.to(batch, {
          opacity: 1,
          y: 0,
          ease: "sine.out",
          stagger: {
            each: 0.15,
            grid: [1, 3]
          },
          overwrite: true
        }),

      onLeave: (batch) =>
        gsap.set(batch, {
          opacity: 1,
          y: 0,
          overwrite: true
        }),

      onEnterBack: (batch) =>
        gsap.to(batch, {
          opacity: 1,
          y: 0,
          stagger: 0.15,
          overwrite: true
        }),

      onLeaveBack: (batch) =>
        gsap.set(batch, {
          opacity: 0,
          y: 50,
          overwrite: true
        }),
    });

    // Ensure proper reset on refresh (resize, etc.)
    ScrollTrigger.addEventListener("refreshInit", () =>
      gsap.set(cards, { y: 0, opacity: 1 })
    );
  }
}
