export default class AccordionBlock {
  constructor(element) {
    this.element = element || document;
    this.init();
  }

  init() {
    this.setDomMap();
    // Use setTimeout to ensure DOM is fully ready
    if (document.readyState === 'loading') {
      document.addEventListener('DOMContentLoaded', () => this.bindEvents());
    } else {
      this.bindEvents();
    }
  }

  setDomMap() { }

  bindEvents() {
    try {
      // Handle new accordion structure with .accordion_items-grid
      this.initNewAccordions();

      // Keep existing data-attribute accordion functionality
      this.initDataAccordions();
    } catch (error) {
      console.error('AccordionBlock error:', error);
    }
  }

  initNewAccordions() {
    // Handle accordion_items-grid accordions
    const accordionContainers = this.element.querySelectorAll('.accordion_items-grid');

    accordionContainers.forEach((accordionContainer) => {
      const triggers = accordionContainer.querySelectorAll('.accordion-trigger');

      // Find the image container within the same accordion block
      const accordionBlock = accordionContainer.closest('.accordion_block');
      const imageContainer = accordionBlock ? accordionBlock.querySelector('[data-accordion-image-container]') : null;

      // Initialize with clip animation for the first/default image
      if (imageContainer) {
        this.initializeImageAnimation(imageContainer);
      }

      triggers.forEach(btn => {
        const handleToggle = () => {
          const expanded = btn.getAttribute('aria-expanded') === 'true';
          const accordionIndex = btn.getAttribute('data-accordion-index');

          // If clicking already open accordion, don't allow it to close
          if (expanded) {
            return;
          }

          // Close all accordions in this container
          triggers.forEach(trigger => {
            trigger.setAttribute('aria-expanded', 'false');
            const iconWrap = trigger.querySelector('.icon-wrap');
            if (iconWrap) {
              const plusIcon = iconWrap.querySelector('.plus');
              const minusIcon = iconWrap.querySelector('.minus');
              if (plusIcon) plusIcon.classList.remove('hidden');
              if (minusIcon) minusIcon.classList.add('hidden');
            }
            const panelId = trigger.getAttribute('aria-controls');
            const panel = accordionContainer.querySelector(`#${panelId}`);
            if (panel) {
              panel.style.cssText = 'display: none !important;';
              panel.classList.add('hidden');
            }
          });

          // Open clicked accordion
          btn.setAttribute('aria-expanded', 'true');
          const iconWrap = btn.querySelector('.icon-wrap');
          if (iconWrap) {
            const plusIcon = iconWrap.querySelector('.plus');
            const minusIcon = iconWrap.querySelector('.minus');
            if (plusIcon) plusIcon.classList.add('hidden');
            if (minusIcon) minusIcon.classList.remove('hidden');
          }
          const panelId = btn.getAttribute('aria-controls');
          const panel = accordionContainer.querySelector(`#${panelId}`);
          if (panel) {
            panel.classList.remove('hidden');
            panel.style.cssText = 'display: block !important;';
          }

          // Handle image switching
          this.switchAccordionImage(imageContainer, accordionIndex);
        };

        // Click event
        btn.addEventListener('click', () => {
          handleToggle();
        });
        
        // Keyboard support
        btn.addEventListener('keydown', (e) => {
          if (e.key === 'Enter' || e.key === ' ') {
            e.preventDefault();
            handleToggle();
          }
        });
      });
    });
  }

  initializeImageAnimation(imageContainer) {
    const animation = {
      start: 'inset(0 100% 0 0)',
      end: 'inset(0 0% 0 0)',
      transition: 'clip-path 0.5s cubic-bezier(0.25, 0.46, 0.45, 0.94)'
    };

    const allImages = imageContainer.querySelectorAll('img');
    
    // Find the visible image (default or first accordion image)
    let visibleImage = imageContainer.querySelector('[data-image-id="default"]');
    if (!visibleImage) {
      visibleImage = imageContainer.querySelector('[data-image-id="accordion-1"]');
    }

    if (visibleImage) {
      // Set all images to hidden state initially
      allImages.forEach(img => {
        img.style.opacity = '0';
        img.style.clipPath = animation.start;
      });

      // Animate the visible image in
      setTimeout(() => {
        visibleImage.style.opacity = '1';
        visibleImage.style.clipPath = animation.start;
        visibleImage.style.transition = `${animation.transition}, opacity 0.3s ease-in-out`;

        requestAnimationFrame(() => {
          visibleImage.style.clipPath = animation.end;
        });
      }, 100);
    }
  }

  switchAccordionImage(imageContainer, targetIndex) {
    if (!imageContainer) return;

    // Find target image first
    let targetImage;

    if (targetIndex === 'default') {
      targetImage = imageContainer.querySelector('[data-image-id="default"]');
    } else {
      targetImage = imageContainer.querySelector(`[data-image-id="accordion-${targetIndex}"]`);
      // Fallback to default if accordion image doesn't exist
      if (!targetImage) {
        targetImage = imageContainer.querySelector('[data-image-id="default"]');
      }
    }

    if (!targetImage) return;

    // Use left-to-right animation for all items
    const animation = {
      start: 'inset(0 100% 0 0)',
      end: 'inset(0 0% 0 0)',
      transition: 'clip-path 0.5s cubic-bezier(0.25, 0.46, 0.45, 0.94)'
    };

    // Hide all images first
    const allImages = imageContainer.querySelectorAll('img');
    allImages.forEach(img => {
      img.style.opacity = '0';
      img.style.clipPath = animation.start;
    });

    // Show target image with clip-path animation
    setTimeout(() => {
      targetImage.style.opacity = '1';
      targetImage.style.clipPath = animation.start;
      targetImage.style.transition = `${animation.transition}, opacity 0.3s ease-in-out`;

      // Trigger the clip-path animation
      requestAnimationFrame(() => {
        targetImage.style.clipPath = animation.end;
      });
    }, 50); // Small delay for smooth transition
  }

  initDataAccordions() {
    const Default = {
      alwaysOpen: false,
      activeClasses: 'bg-gray-50 dark:bg-slate-800 text-indigo-600',
      inactiveClasses: 'text-dark dark:text-white',
      onOpen: () => { },
      onClose: () => { },
      onToggle: () => { },
    };

    class Accordion {
      constructor(items = [], options = {}) {
        this._items = items;
        this._options = { ...Default, ...options };
        this._init();
      }

      _init() {
        if (this._items.length) {
          // show accordion item based on click
          this._items.map((item) => {
            if (item.active) {
              this.open(item.id);
            }

            item.triggerEl.addEventListener('click', () => {
              this.toggle(item.id);
            });
          });
        }
      }

      getItem(id) {
        return this._items.filter((item) => item.id === id)[0];
      }

      open(id) {
        const item = this.getItem(id);

        // don't hide other accordions if always open
        if (!this._options.alwaysOpen) {
          this._items.map((i) => {
            if (i !== item) {
              i.triggerEl.classList.remove(...this._options.activeClasses.split(' '));
              i.triggerEl.classList.add(...this._options.inactiveClasses.split(' '));
              i.targetEl.classList.add('hidden');
              i.triggerEl.setAttribute('aria-expanded', false);
              i.active = false;

              // rotate icon if set
              if (i.iconEl) {
                i.iconEl.classList.remove('rotate-180');
              }
            }
          });
        }

        // show active item
        item.triggerEl.classList.add(...this._options.activeClasses.split(' '));
        item.triggerEl.classList.remove(...this._options.inactiveClasses.split(' '));
        item.triggerEl.setAttribute('aria-expanded', true);
        item.targetEl.classList.remove('hidden');
        item.active = true;

        // rotate icon if set
        if (item.iconEl) {
          item.iconEl.classList.add('rotate-180');
        }

        // callback function
        this._options.onOpen(this, item);
      }

      toggle(id) {
        const item = this.getItem(id);

        if (item.active) {
          this.close(id);
        } else {
          this.open(id);
        }

        // callback function
        this._options.onToggle(this, item);
      }

      close(id) {
        const item = this.getItem(id);

        item.triggerEl.classList.remove(...this._options.activeClasses.split(' '));
        item.triggerEl.classList.add(...this._options.inactiveClasses.split(' '));
        item.targetEl.classList.add('hidden');
        item.triggerEl.setAttribute('aria-expanded', false);
        item.active = false;

        // rotate icon if set
        if (item.iconEl) {
          item.iconEl.classList.remove('rotate-180');
        }

        // callback function
        this._options.onClose(this, item);
      }
    }

    window.Accordion = Accordion;

    this.element.querySelectorAll('[data-accordion]').forEach((accordionEl) => {
      const alwaysOpen = accordionEl.getAttribute('data-accordion');
      const activeClasses = accordionEl.getAttribute('data-active-classes');
      const inactiveClasses = accordionEl.getAttribute('data-inactive-classes');

      const items = [];
      accordionEl.querySelectorAll('[data-accordion-target]').forEach((el) => {
        const item = {
          id: el.getAttribute('data-accordion-target'),
          triggerEl: el,
          targetEl: document.querySelector(el.getAttribute('data-accordion-target')),
          iconEl: el.querySelector('[data-accordion-icon]'),
          active: el.getAttribute('aria-expanded') === 'true' ? true : false,
        };
        items.push(item);
      });

      new Accordion(items, {
        alwaysOpen: alwaysOpen === 'open' ? true : false,
        activeClasses: activeClasses ? activeClasses : Default.activeClasses,
        inactiveClasses: inactiveClasses ? inactiveClasses : Default.inactiveClasses,
      });
    });
  }
}
