import { gsap } from 'gsap';

export default class DistributionMap {
  constructor(element) {
    this.element = element;
    this.tabs = element.querySelectorAll('.distribution-tab');
    this.locationContainers = element.querySelectorAll('.locations-container');
    this.currentTab = 'international';
    
    // Drag scroll properties
    this.isDragging = false;
    this.startX = 0;
    this.scrollLeft = 0;
    this.dragWrapper = null;
    
    this.init();
  }

  init() {
    this.bindEvents();
    this.initAnimations();
    this.initDragScroll();
    this.setInitialState();
    this.centerLegendIfNeeded();
  }

  bindEvents() {
    this.tabs.forEach(tab => {
      tab.addEventListener('click', (e) => this.handleTabSwitch(e));
    });

    // Add hover effects to pins (desktop)
    this.element.addEventListener('mouseenter', this.handlePinHover.bind(this), true);
    this.element.addEventListener('mouseleave', this.handlePinLeave.bind(this), true);
    
    // Add touch events for mobile pin labels
    this.element.addEventListener('touchstart', this.handlePinTouch.bind(this), true);
    this.element.addEventListener('click', this.handlePinClick.bind(this), true);
    
    // Hide labels when touching elsewhere
    document.addEventListener('touchstart', this.handleDocumentTouch.bind(this));
  }

  handleTabSwitch(e) {
    const clickedTab = e.target;
    const targetTab = clickedTab.getAttribute('data-tab');
    
    if (targetTab === this.currentTab) return;

    // Update tab states
    this.tabs.forEach(tab => tab.classList.remove('active'));
    clickedTab.classList.add('active');

    // Switch location containers with animation
    this.switchLocationView(targetTab);
    
    this.currentTab = targetTab;
  }

  switchLocationView(targetTab) {
    const currentContainer = this.element.querySelector('.locations-container.active');
    const mapBackground = this.element.querySelector('.map-background');
    let targetContainer;

    // Determine which container to show based on tab selection
    if (targetTab === 'international') {
      // Show only international locations (red distributors)
      targetContainer = this.element.querySelector('.international-locations');
    } else if (targetTab === 'india') {
      // Show only India locations (blue sales team)
      targetContainer = this.element.querySelector('.india-locations');
    }

    if (!targetContainer || currentContainer === targetContainer) return;

    // Get theme directory URI (passed from PHP)
    const themeUri = window.resplastTheme?.themeUri || '';

    // Animate transition
    const tl = gsap.timeline();

    // Fade out current pins
    tl.to(currentContainer.querySelectorAll('.location-pin'), {
      scale: 0,
      opacity: 0,
      duration: 0.3,
      stagger: 0.05,
      ease: 'back.in(1.7)'
    });

    // Switch containers and background image
    tl.call(() => {
      currentContainer.classList.remove('active');
      targetContainer.classList.add('active');
      
      // Add/remove class on distribution block for CSS targeting
      if (targetTab === 'india') {
        this.element.classList.add('india-active');
      } else {
        this.element.classList.remove('india-active');
      }
      
      // Show/hide Sales Team legend based on tab
      const salesTeamLegend = this.element.querySelector('.sales-team-legend');
      if (salesTeamLegend) {
        if (targetTab === 'international') {
          gsap.to(salesTeamLegend, { opacity: 0, duration: 0.3, ease: 'power2.out', pointerEvents: 'none' });
          salesTeamLegend.classList.add('hidden');
        } else {
          salesTeamLegend.classList.remove('hidden');
          gsap.to(salesTeamLegend, { opacity: 1, duration: 0.3, ease: 'power2.out', pointerEvents: 'auto' });
        }
      }
      
      // Center distribution legend if sales team legend is hidden
      this.centerLegendIfNeeded();
      
      // Change background image based on selected tab
      if (mapBackground) {
        if (targetTab === 'international') {
          mapBackground.style.backgroundImage = `url('${themeUri}/assets/images/about/International.png')`;
        } else if (targetTab === 'india') {
          mapBackground.style.backgroundImage = `url('${themeUri}/assets/images/about/india.png')`;
        }
      }
      
      // Force horizontal scrolling to be re-enabled after map switch
      if (window.innerWidth <= 768) {
        const mapWrapper = this.element.querySelector('.distribution-map-wrapper');
        if (mapWrapper) {
          // Reset scroll position to start
          mapWrapper.scrollLeft = 0;
          
          // Force body/html overflow
          document.documentElement.style.overflowX = 'auto';
          document.body.style.overflowX = 'auto';
        }
      }
    });

    // Animate in new pins
    tl.fromTo(targetContainer.querySelectorAll('.location-pin'), 
      { 
        scale: 0, 
        opacity: 0
      }, 
      {
        scale: 1,
        opacity: 1,
        duration: 0.4,
        stagger: 0.08,
        ease: 'back.out(1.7)'
      }
    );
  }

  handlePinHover(e) {
    if (e.target.closest('.location-pin')) {
      const pin = e.target.closest('.location-pin');
      const label = pin.querySelector('.pin-label');
      
      gsap.to(pin.querySelector('.pin-marker'), {
        scale: 1.2,
        duration: 0.2,
        ease: 'power2.out'
      });

      if (label) {
        gsap.to(label, {
          opacity: 1,
          y: -5,
          duration: 0.2,
          ease: 'power2.out'
        });
      }
    }
  }

  handlePinLeave(e) {
    if (e.target.closest('.location-pin')) {
      const pin = e.target.closest('.location-pin');
      const label = pin.querySelector('.pin-label');
      
      gsap.to(pin.querySelector('.pin-marker'), {
        scale: 1,
        duration: 0.2,
        ease: 'power2.out'
      });

      if (label) {
        gsap.to(label, {
          opacity: 0,
          y: 0,
          duration: 0.2,
          ease: 'power2.out'
        });
      }
    }
  }

  handlePinTouch(e) {
    // Only handle on mobile
    if (window.innerWidth > 768) return;
    
    const pin = e.target.closest('.location-pin');
    if (pin && !this.isDragging) {
      e.preventDefault();
      e.stopPropagation();
      this.showPinLabel(pin);
    }
  }

  handlePinClick(e) {
    // Only handle on mobile
    if (window.innerWidth > 768) return;
    
    const pin = e.target.closest('.location-pin');
    if (pin && !this.isDragging) {
      e.preventDefault();
      e.stopPropagation();
      this.togglePinLabel(pin);
    }
  }

  handleDocumentTouch(e) {
    // Only handle on mobile
    if (window.innerWidth > 768) return;
    
    // If touch is outside any pin, hide all labels
    if (!e.target.closest('.location-pin')) {
      this.hideAllPinLabels();
    }
  }

  showPinLabel(pin) {
    // Hide other labels first
    this.hideAllPinLabels();
    
    const label = pin.querySelector('.pin-label');
    const marker = pin.querySelector('.pin-marker');
    
    if (marker) {
      gsap.to(marker, {
        scale: 1.2,
        duration: 0.2,
        ease: 'power2.out'
      });
    }
    
    if (label) {
      gsap.to(label, {
        opacity: 1,
        y: -5,
        duration: 0.3,
        ease: 'power2.out'
      });
    }
    
    // Mark this pin as active
    pin.classList.add('active-pin');
  }

  togglePinLabel(pin) {
    const label = pin.querySelector('.pin-label');
    const isVisible = label && gsap.getProperty(label, 'opacity') > 0;
    
    if (isVisible) {
      this.hidePinLabel(pin);
    } else {
      this.showPinLabel(pin);
    }
  }

  hidePinLabel(pin) {
    const label = pin.querySelector('.pin-label');
    const marker = pin.querySelector('.pin-marker');
    
    if (marker) {
      gsap.to(marker, {
        scale: 1,
        duration: 0.2,
        ease: 'power2.out'
      });
    }
    
    if (label) {
      gsap.to(label, {
        opacity: 0,
        y: 0,
        duration: 0.2,
        ease: 'power2.out'
      });
    }
    
    pin.classList.remove('active-pin');
  }

  hideAllPinLabels() {
    const activePins = this.element.querySelectorAll('.location-pin.active-pin');
    activePins.forEach(pin => {
      this.hidePinLabel(pin);
    });
  }

  initAnimations() {
    // Set initial animation states
    gsap.set(this.element.querySelectorAll('.location-pin'), {
      scale: 0,
      opacity: 0
    });

    gsap.set(this.element.querySelectorAll('.pin-label'), {
      opacity: 0,
      y: 5
    });
  }

  initDragScroll() {
    if (window.innerWidth <= 768) {
      this.dragWrapper = this.element.querySelector('.distribution-map-wrapper');
      if (!this.dragWrapper) return;

      // Mouse events
      this.dragWrapper.addEventListener('mousedown', this.handleDragStart.bind(this));
      this.dragWrapper.addEventListener('mousemove', this.handleDragMove.bind(this));
      this.dragWrapper.addEventListener('mouseup', this.handleDragEnd.bind(this));
      this.dragWrapper.addEventListener('mouseleave', this.handleDragEnd.bind(this));

      // Touch events
      this.dragWrapper.addEventListener('touchstart', this.handleTouchStart.bind(this), { passive: false });
      this.dragWrapper.addEventListener('touchmove', this.handleTouchMove.bind(this), { passive: false });
      this.dragWrapper.addEventListener('touchend', this.handleTouchEnd.bind(this));

      // Prevent default scrolling
      this.dragWrapper.addEventListener('scroll', (e) => {
        e.preventDefault();
      });

      // Update cursor styles
      this.dragWrapper.style.cursor = 'grab';
      this.dragWrapper.style.userSelect = 'none';
    }
  }

  handleDragStart(e) {
    this.isDragging = true;
    this.startX = e.pageX - this.dragWrapper.offsetLeft;
    this.scrollLeft = this.dragWrapper.scrollLeft;
    this.dragWrapper.style.cursor = 'grabbing';
  }

  handleDragMove(e) {
    if (!this.isDragging) return;
    e.preventDefault();
    const x = e.pageX - this.dragWrapper.offsetLeft;
    const walk = (x - this.startX) * 2; // Multiply for faster scrolling
    this.dragWrapper.scrollLeft = this.scrollLeft - walk;
  }

  handleDragEnd() {
    this.isDragging = false;
    this.dragWrapper.style.cursor = 'grab';
  }

  handleTouchStart(e) {
    if (e.touches.length > 1) return; // Ignore multi-touch
    
    // Don't start drag if touching a pin
    if (e.target.closest('.location-pin')) return;
    
    e.preventDefault();
    this.isDragging = true;
    this.startX = e.touches[0].pageX - this.dragWrapper.offsetLeft;
    this.scrollLeft = this.dragWrapper.scrollLeft;
  }

  handleTouchMove(e) {
    if (!this.isDragging || e.touches.length > 1) return;
    e.preventDefault();
    const x = e.touches[0].pageX - this.dragWrapper.offsetLeft;
    const walk = (x - this.startX) * 2;
    this.dragWrapper.scrollLeft = this.scrollLeft - walk;
  }

  handleTouchEnd(e) {
    e.preventDefault();
    this.isDragging = false;
  }

  setInitialState() {
    // Ensure international tab is active initially
    const internationalTab = this.element.querySelector('[data-tab="international"]');
    const internationalContainer = this.element.querySelector('.international-locations');
    const mapBackground = this.element.querySelector('.map-background');
    const salesTeamLegend = this.element.querySelector('.sales-team-legend');
    
    if (internationalTab) internationalTab.classList.add('active');
    if (internationalContainer) internationalContainer.classList.add('active');
    
    // Hide Sales Team legend initially since international is active
    if (salesTeamLegend) {
      gsap.set(salesTeamLegend, { opacity: 0 });
    }

    // Set initial background image to International
    const themeUri = window.resplastTheme?.themeUri || '';
    if (mapBackground && themeUri) {
      mapBackground.style.backgroundImage = `url('${themeUri}/assets/images/about/International.png')`;
    }

    // Force initial scroll setup on mobile
    if (window.innerWidth <= 768) {
      document.documentElement.style.overflowX = 'auto';
      document.body.style.overflowX = 'auto';
    }

    // Animate initial pins in
    setTimeout(() => {
      const activePins = this.element.querySelectorAll('.locations-container.active .location-pin');
      gsap.to(activePins, {
        scale: 1,
        opacity: 1,
        duration: 0.4,
        stagger: 0.1,
        ease: 'back.out(1.7)'
      });
    }, 500);
  }

  centerLegendIfNeeded() {
    const distributionLegend = this.element.querySelector('.distribution-legend');
    const salesTeamLegend = this.element.querySelector('.sales-team-legend');
    
    if (!distributionLegend) return;
    
    // Check if sales team legend is hidden or not present
    const isSalesTeamHidden = !salesTeamLegend || gsap.getProperty(salesTeamLegend, 'opacity') === 0;
    
    if (isSalesTeamHidden) {
      // Center the distribution legend
      distributionLegend.style.margin = '0 auto';
      distributionLegend.style.justifyContent = 'center';
    } else {
      // Reset to default layout
      distributionLegend.style.margin = '';
      distributionLegend.style.justifyContent = '';
    }
  }

  // Static method to handle automatic initialization
  static initAll() {
    const distributionMaps = document.querySelectorAll('[data-component="DistributionMap"]');
    distributionMaps.forEach(element => {
      if (!element._distributionMapInstance) {
        element._distributionMapInstance = new DistributionMap(element);
      }
    });
  }

  // Clean up method
  destroy() {
    this.tabs.forEach(tab => {
      tab.removeEventListener('click', this.handleTabSwitch);
    });
    this.element.removeEventListener('mouseenter', this.handlePinHover, true);
    this.element.removeEventListener('mouseleave', this.handlePinLeave, true);
    
    // Clean up mobile pin events
    this.element.removeEventListener('touchstart', this.handlePinTouch, true);
    this.element.removeEventListener('click', this.handlePinClick, true);
    document.removeEventListener('touchstart', this.handleDocumentTouch);
    
    // Clean up drag events
    if (this.dragWrapper) {
      this.dragWrapper.removeEventListener('mousedown', this.handleDragStart.bind(this));
      this.dragWrapper.removeEventListener('mousemove', this.handleDragMove.bind(this));
      this.dragWrapper.removeEventListener('mouseup', this.handleDragEnd.bind(this));
      this.dragWrapper.removeEventListener('mouseleave', this.handleDragEnd.bind(this));
      this.dragWrapper.removeEventListener('touchstart', this.handleTouchStart.bind(this));
      this.dragWrapper.removeEventListener('touchmove', this.handleTouchMove.bind(this));
      this.dragWrapper.removeEventListener('touchend', this.handleTouchEnd.bind(this));
    }
    
    if (this.element._distributionMapInstance) {
      delete this.element._distributionMapInstance;
    }
  }
}