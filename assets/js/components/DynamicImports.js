import { componentMap } from '../componentList';
import { max1200 } from '../utils';

// Dynamically import all components except those that are statically imported elsewhere
// Exclude GSAPAnimations (used in main.js and HeroSlider), NewsListingDropdowns (auto-init), and DynamicImports itself
const components = import.meta.glob(['./*.js', '!./GSAPAnimations.js', '!./NewsListingDropdowns.js', '!./DynamicImports.js']);

export default class DynamicImports {
  constructor() {
    // Use the 'load' event to ensure the browser's layout and scroll restoration is complete.
    window.addEventListener('load', this.init);
  }

  init = () => {
    this.setupObserver();
    this.processComponents();
  };

  setupObserver = () => {
    this.observer = new IntersectionObserver((entries, observer) => {
      entries.forEach((entry) => {
        if (entry.isIntersecting) {
          const el = entry.target;
          this.loadComponent(el);
          observer.unobserve(el);
        }
      });
    });
  };

  processComponents = () => {
    const elements = document.querySelectorAll('[data-component]:not(.init)');
    elements.forEach((el) => {
      // Eagerly load components that are marked as non-lazy.
      if (el.dataset.load === 'eager') {
        this.loadComponent(el);
        return; // Move to the next element.
      }

      // For lazy components, check if they are already visible.
      const rect = el.getBoundingClientRect();
      if (rect.top < window.innerHeight && rect.bottom > 0) {
        this.loadComponent(el);
      } else {
        // Otherwise, let the observer handle them.
        this.observer.observe(el);
      }
    });
  };

  loadComponent = async (el) => {
    if (el.classList.contains('init')) return;
    el.classList.add('init');

    const className = el.dataset.component;
    if (!className) return;

    const componentConfig = componentMap[className];
    if (!componentConfig) {
      return;
    }

    const { mobile, config } = componentConfig;
    if (!mobile && max1200.matches) return;

    const path = `./${className}.js`;
    if (components[path]) {
      try {
        const module = await components[path]();
        new module.default(el, config);
      } catch (error) {
      }
    } else {
    }
  };
}
