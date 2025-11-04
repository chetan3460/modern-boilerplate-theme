import { ScrollSmoother } from 'gsap/ScrollSmoother';

export default class CertificateModal {
  constructor(el) {
    this.el = el;
    this.init();
  }

  init() {
    this.el.addEventListener('click', (e) => {
      const button = e.target.closest('.view-certificate-btn');
      if (button) {
        e.preventDefault();
        this.show(
          button.getAttribute('data-certificate-image'),
          button.getAttribute('data-certificate-alt')
        );
      }
    });
  }

  getSmoother() {
    try {
      return window.app?.smoother || ScrollSmoother.get?.();
    } catch (e) {
      return null;
    }
  }

  show(imageUrl, imageAlt) {
    document.getElementById('certificate-modal')?.remove();

    const modal = document.createElement('div');
    modal.id = 'certificate-modal';
    modal.className = 'certificate-modal';
    modal.innerHTML = `
      <div class="certificate-modal-close" aria-label="Close modal"><span>&times;</span></div>
      <div class="certificate-modal-backdrop">
        <div class="certificate-modal-container">
          <div class="certificate-modal-image-wrapper">
            <img class="certificate-modal-image" src="${imageUrl}" alt="${imageAlt || 'Certificate'}" />
          </div>
        </div>
      </div>
    `;

    document.body.appendChild(modal);

    const smoother = this.getSmoother();
    let scrollY = window.scrollY;
    const scrollX = window.scrollX;

    // Get scroll position safely
    try {
      if (smoother?.scrollTop) {
        scrollY = smoother.scrollTop();
      }
    } catch (e) {
      // Use window.scrollY
    }

    // Pause scroll safely
    try {
      if (smoother?.paused) {
        smoother.paused(true);
      }
    } catch (e) {
      // Scroll pause failed, continue
    }

    // Prevent scrolling
    document.body.classList.add('overflow-hidden');
    document.body.setAttribute('data-scroll-y', scrollY);
    document.body.setAttribute('data-scroll-x', scrollX);
    document.body.setAttribute('data-has-smoother', smoother ? '1' : '0');
    
    // Prevent scroll on touch devices
    const preventScroll = (e) => {
      e.preventDefault();
    };
    
    document.body.addEventListener('touchmove', preventScroll, { passive: false });
    document.body.addEventListener('wheel', preventScroll, { passive: false });

    // Check if CSS styles are applied, if not use fallback inline styles
    setTimeout(() => {
      const computedStyle = window.getComputedStyle(modal);

      // If CSS styles aren't applied, add fallback inline styles
      if (computedStyle.position === 'static') {

        // Modal container styles
        Object.assign(modal.style, {
          position: 'fixed',
          top: '0',
          left: '0',
          width: '100vw',
          height: '100vh',
          zIndex: '9999',
          display: 'flex',
          alignItems: 'center',
          justifyContent: 'center',
          opacity: '0',
          transition: 'opacity 0.3s ease-out'
        });

        // Backdrop styles
        const backdrop = modal.querySelector('.certificate-modal-backdrop');
        if (backdrop) {
          Object.assign(backdrop.style, {
            position: 'fixed',
            top: '0',
            left: '0',
            width: '100vw',
            height: '100vh',
            backgroundColor: 'rgba(0, 0, 0, 0.75)',
            display: 'flex',
            alignItems: 'center',
            justifyContent: 'center',
            padding: '16px',
            cursor: 'pointer',
            zIndex: '10000',
            opacity: '0',
            transition: 'opacity 0.3s ease-out'
          });
        }

        // Container styles
        const container = modal.querySelector('.certificate-modal-container');
        if (container) {
          Object.assign(container.style, {
            position: 'relative',
            display: 'flex',
            alignItems: 'center',
            justifyContent: 'center',
            maxWidth: '80rem',
            maxHeight: '100vh',
            cursor: 'default',
            transform: 'translateY(60px) scale(0.95)',
            opacity: '0',
            transition: 'all 0.5s cubic-bezier(0.34, 1.56, 0.64, 1)'
          });
        }

        // Close button styles
        const closeBtn = modal.querySelector('.certificate-modal-close');
        if (closeBtn) {
          Object.assign(closeBtn.style, {
            position: 'absolute',
            top: '16px',
            right: '16px',
            width: '48px',
            height: '48px',
            backgroundColor: 'white',
            color: '#111827',
            borderRadius: '50%',
            fontSize: '20px',
            fontWeight: 'bold',
            cursor: 'pointer',
            zIndex: '10002',
            display: 'flex',
            alignItems: 'center',
            justifyContent: 'center',
            boxShadow: '0 25px 50px -12px rgba(0, 0, 0, 0.25)',
            transform: 'scale(0.8) rotate(-90deg)',
            opacity: '0',
            transition: 'all 0.4s ease-out'
          });
        }

        // Image styles
        const image = modal.querySelector('.certificate-modal-image');
        if (image) {
          Object.assign(image.style, {
            opacity: '1'
          });
        }

        // Trigger animations with staggered timing
        setTimeout(() => {
          // Fade in modal and backdrop
          modal.style.opacity = '1';
          if (backdrop) backdrop.style.opacity = '1';
        }, 50);

        setTimeout(() => {
          // Slide up container
          if (container) {
            container.style.transform = 'translateY(0) scale(1)';
            container.style.opacity = '1';
          }
        }, 150);

        setTimeout(() => {
          // Animate close button
          if (closeBtn) {
            closeBtn.style.transform = 'scale(1) rotate(0deg)';
            closeBtn.style.opacity = '1';
          }
        }, 350);

      }
    }, 100);

    // Setup close handlers
    this.setupCloseHandlers(modal, scrollY, preventScroll);
  }

  setupCloseHandlers(modal, scrollY, preventScroll) {
    const closeBtn = modal.querySelector('.certificate-modal-close');
    const backdrop = modal.querySelector('.certificate-modal-backdrop');
    const container = modal.querySelector('.certificate-modal-container');

    const closeModal = () => {
      modal.classList.add('closing');
      setTimeout(() => {
        modal.remove();

        const hasSmoother = document.body.getAttribute('data-has-smoother') === '1';

        document.body.classList.remove('overflow-hidden');
        document.body.removeAttribute('data-scroll-y');
        document.body.removeAttribute('data-scroll-x');
        document.body.removeAttribute('data-has-smoother');
        document.body.removeEventListener('touchmove', preventScroll);
        document.body.removeEventListener('wheel', preventScroll);

        // Resume smoother
        if (hasSmoother) {
          try {
            const smoother = this.getSmoother();
            if (smoother?.paused) {
              smoother.paused(false);
            }
          } catch (e) {
            // Resume failed, continue
          }
        }
      }, 250);
    };

    // Close button click
    if (closeBtn) {
      closeBtn.addEventListener('click', (e) => {
        e.stopPropagation();
        closeModal();
      });
    }

    // Backdrop click
    if (backdrop) {
      backdrop.addEventListener('click', closeModal);
    }

    // Prevent container close
    if (container) {
      container.addEventListener('click', (e) => e.stopPropagation());
    }

    // Escape key
    const handleEscape = (e) => {
      if (e.key === 'Escape') {
        closeModal();
        document.removeEventListener('keydown', handleEscape);
      }
    };
    document.addEventListener('keydown', handleEscape);
  }
}
