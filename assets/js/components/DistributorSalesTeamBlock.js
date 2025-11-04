

export default class DistributorSalesTeamBlock {
  constructor(element) {
    this.el = element;
    this.searchInput = null;
    this.salesGrid = null;
    this.locationCards = [];
    this.noResultsMessage = null;
    this.loadingSpinner = null;
    this.currentlyVisible = 3;
    this.isLoading = false;
    this.isMobile = window.innerWidth < 768;
    this.searchDebounceTimeout = null;

    // Bind methods to preserve 'this' context
    this.handleResize = this.handleResize.bind(this);
    this.handleInfiniteScroll = this.handleInfiniteScroll.bind(this);
    this.performSearch = this.performSearch.bind(this);

    // Initialize - try multiple times if elements aren't ready
    this.attemptInit();
  }

  attemptInit(retries = 5) {
    this.setDomMap();

    // If elements aren't found and we have retries left, try again
    if ((!this.searchInput || !this.salesGrid) && retries > 0) {
      setTimeout(() => {
        this.attemptInit(retries - 1);
      }, 100);
      return;
    }

    // Only proceed if we have the essential elements
    if (!this.searchInput || !this.salesGrid) {
      return; // Exit if essential elements don't exist after retries
    }

    this.initializeMobileState();
    this.bindEvents();
  }

  setDomMap() {
    // Try to find elements within the component element first, then fallback to document
    this.searchInput = this.el.querySelector('#location-search') || document.getElementById('location-search');
    this.salesGrid = this.el.querySelector('#sales-team-grid') || document.getElementById('sales-team-grid');
    this.noResultsMessage = this.el.querySelector('#no-results-message') || document.getElementById('no-results-message');
    this.loadingSpinner = this.el.querySelector('#loading-spinner') || document.getElementById('loading-spinner');
    this.locationCards = this.salesGrid ? Array.from(this.salesGrid.querySelectorAll('.location-card')) : [];
  }

  initializeMobileState() {
    // Initialize mobile hidden state for cards beyond the first 3
    if (this.isMobile && this.locationCards.length > 3) {
      this.locationCards.forEach((card, index) => {
        if (index >= this.currentlyVisible) {
          card.classList.add('mobile-hidden');
          card.classList.add('hidden');
          card.style.display = 'none';
        }
      });
    }
  }

  bindEvents() {
    // Bind search input event - this should always work if searchInput exists
    if (this.searchInput) {
      // Remove any existing listeners to avoid duplicates
      this.searchInput.removeEventListener('input', this.performSearch);
      // Debounce input for better UX and compatibility
      this.searchInput.addEventListener('input', () => {
        if (this.searchDebounceTimeout) clearTimeout(this.searchDebounceTimeout);
        this.searchDebounceTimeout = setTimeout(() => {
          this.performSearch();
        }, 200);
      });

      // Run once to normalize initial state
      this.performSearch();
    }

    // Always set up resize listener to handle mobile/desktop switching
    window.addEventListener('resize', this.handleResize);

    // Set up infinite scroll only on mobile when there are more than 3 cards
    if (this.isMobile && this.locationCards.length > 3) {
      window.addEventListener('scroll', this.handleInfiniteScroll, { passive: true });
    }

    // Expose clearSearch globally
    window.clearSearch = () => {
      if (this.searchInput) {
        this.searchInput.value = '';
        this.performSearch();
        this.searchInput.focus();
      }
    };
  }

  handleResize() {
    const newIsMobile = window.innerWidth < 768;
    if (newIsMobile !== this.isMobile) {
      this.isMobile = newIsMobile;

      if (!this.isMobile) {
        // Desktop: show all cards
        this.locationCards.forEach(card => {
          card.classList.remove('mobile-hidden');
          card.style.display = 'block';
        });
        // Remove scroll listener
        window.removeEventListener('scroll', this.handleInfiniteScroll);
      } else {
        // Mobile: hide cards beyond currentlyVisible
        if (this.locationCards.length > 3) {
          this.locationCards.forEach((card, index) => {
            if (index >= this.currentlyVisible) {
              card.classList.add('mobile-hidden');
              card.style.display = 'none';
            }
          });
          // Add scroll listener
          window.addEventListener('scroll', this.handleInfiniteScroll, { passive: true });
        }
      }
    }
  }

  handleInfiniteScroll() {
    if (this.isLoading || !this.isMobile) return;
    const scrollTop = window.pageYOffset || document.documentElement.scrollTop;
    const windowHeight = window.innerHeight;
    const documentHeight = document.documentElement.scrollHeight;
    if (scrollTop + windowHeight >= documentHeight * 0.8) {
      this.loadMoreCards();
    }
  }

  loadMoreCards() {
    if (this.isLoading || this.currentlyVisible >= this.locationCards.length) return;
    this.isLoading = true;
    if (this.loadingSpinner) this.loadingSpinner.style.display = 'block';
    setTimeout(() => {
      const cardsToShow = Math.min(3, this.locationCards.length - this.currentlyVisible);
      for (let i = 0; i < cardsToShow; i++) {
        const cardIndex = this.currentlyVisible + i;
        if (this.locationCards[cardIndex]) {
          this.locationCards[cardIndex].classList.remove('mobile-hidden');
          this.locationCards[cardIndex].classList.remove('hidden');
          this.locationCards[cardIndex].style.display = 'block';
        }
      }
      this.currentlyVisible += cardsToShow;
      this.isLoading = false;
      if (this.loadingSpinner) this.loadingSpinner.style.display = 'none';
    }, 800);
  }

  performSearch() {
    if (!this.searchInput || !this.locationCards.length) return;

    const searchTerm = this.searchInput.value.toLowerCase().trim();
    let visibleCount = 0;
    this.locationCards.forEach((card, index) => {
      const location = card.dataset.location || '';
      const memberNames = Array.from(card.querySelectorAll('.member-info')).map(member =>
        member.textContent.toLowerCase()
      ).join(' ');
      const matchesLocation = location.includes(searchTerm);
      const matchesMembers = memberNames.includes(searchTerm);
      if (matchesLocation || matchesMembers || searchTerm === '') {
        if (searchTerm !== '' || !this.isMobile || index < this.currentlyVisible) {
          card.classList.remove('mobile-hidden');
          card.classList.remove('hidden');
          card.style.display = 'block';
          visibleCount++;
        } else {
          card.classList.add('mobile-hidden');
          card.classList.add('hidden');
          card.style.display = 'none';
        }
      } else {
        card.classList.add('hidden');
        card.style.display = 'none';
      }
    });
    if (this.noResultsMessage) {
      if (visibleCount === 0 && searchTerm !== '') {
        this.noResultsMessage.classList.remove('hidden');
      } else {
        this.noResultsMessage.classList.add('hidden');
      }
    }
    if (searchTerm === '' && this.isMobile) {
      this.currentlyVisible = Math.min(3, this.locationCards.length);
    }
  }
}
