import StatsCounter from './StatsCounter.js';

export default class SustainabilityImpactBlock {
  constructor(element) {
    this.element = element || document;
    this.init();
  }

  init() {
    this.setDomMap();
    this.bindEvents();
    this.setupIntersectionObserver();
  }

  setDomMap() {
    // Tab elements
    this.tabButtons = this.element.querySelectorAll('.impact-tab-btn');
    this.tabContents = this.element.querySelectorAll('.impact-tab-content');
    this.toggleSlider = this.element.querySelector('.impact-tabs .absolute');

    // Counter elements
    this.counterNumbers = this.element.querySelectorAll('.counter-number');
  }

  bindEvents() {
    // Tab switching functionality
    this.tabButtons.forEach(button => {
      button.addEventListener('click', this.handleTabClick.bind(this));
    });
  }

  handleTabClick(event) {
    const clickedButton = event.currentTarget;
    const targetTab = clickedButton.dataset.tab;
    const targetIndex = parseInt(clickedButton.dataset.index);

    // Update tab buttons text colors
    this.tabButtons.forEach(btn => {
      btn.classList.remove('text-white');
      btn.classList.add('text-primary');
    });

    // Activate clicked button text
    clickedButton.classList.add('text-white');
    clickedButton.classList.remove('text-primary');

    // Animate the sliding toggle background
    if (this.toggleSlider) {
      const leftPosition = targetIndex === 0 ? '4px' : '50%';
      this.toggleSlider.style.left = leftPosition;
    }

    // Update tab content with smooth transition
    this.tabContents.forEach((content, index) => {
      if (content.dataset.tab === targetTab) {
        // Show active content
        content.classList.remove('hidden', 'opacity-0');
        content.classList.add('active', 'opacity-100');

        // Reset animation state and animate counters for the active tab
        setTimeout(() => {
          this.animateCountersInTab(content);
        }, 200);
      } else {
        // Hide inactive content
        content.classList.add('hidden', 'opacity-0');
        content.classList.remove('active', 'opacity-100');
      }
    });
  }

  initCounters() {
    // Initialize counters for the default active tab
    const activeTab = this.element.querySelector('.impact-tab-content.active');
    if (activeTab) {
      this.animateCountersInTab(activeTab);
    }
  }

  animateCountersInTab(tabElement) {
    const counters = tabElement.querySelectorAll('.counter-number');
    const textStats = tabElement.querySelectorAll('.stat-text');

    // Add stats-counter class to numeric counters for StatsCounter.js to pick up
    counters.forEach((counter, index) => {
      counter.classList.add('stats-counter');

      // Store original text to preserve symbols like %, +, ₹, L
      const originalText = counter.textContent;
      counter.setAttribute('data-original-text', originalText);

      // Set duration and decimals attributes for StatsCounter
      counter.setAttribute('data-duration', '2000');
      counter.setAttribute('data-decimals', originalText.includes('.') ? '1' : '0');

      // Reset animation state for tab switching
      counter.classList.remove('animated');
    });

    // Initialize StatsCounter for this tab - it will handle scroll-triggered animation
    if (counters.length > 0) {
      const statsCounter = new StatsCounter(tabElement);
    }

    // Text stats (like LCA) appear immediately without animation
    textStats.forEach((textStat, index) => {
      // Keep text as-is, no animation
    });
  }


  // Intersection Observer for counter animation when block comes into view
  setupIntersectionObserver() {
    if (!window.IntersectionObserver) return;

    this.hasAnimated = false;

    const observer = new IntersectionObserver((entries) => {
      entries.forEach(entry => {
        if (entry.isIntersecting && !this.hasAnimated) {
          this.initCounters();
          this.hasAnimated = true;
          observer.unobserve(entry.target);
        }
      });
    }, {
      threshold: 0.3,
      rootMargin: '0px 0px -50px 0px'
    });

    observer.observe(this.element);
  }

  destroy() {
    // Clean up event listeners
    this.tabButtons.forEach(button => {
      button.removeEventListener('click', this.handleTabClick.bind(this));
    });
  }
}