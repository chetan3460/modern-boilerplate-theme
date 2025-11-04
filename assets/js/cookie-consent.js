/**
 * Cookie Consent Banner
 * Handles cookie consent with Accept/Reject functionality
 */

export class CookieConsent {
  constructor() {
    this.cookieName = 'resplast_cookie_consent';
    this.cookieExpiry = 365; // days
    this.init();
  }

  init() {
    // Prevent duplicate banners
    if (document.getElementById('cookie-consent-banner')) {
      return;
    }

    // Check if user has already made a choice
    if (!this.hasConsent() && !this.hasRejected()) {
      this.showBanner();
    } else if (this.hasConsent()) {
      this.loadTrackingScripts();
    }
  }

  hasConsent() {
    return this.getCookie(this.cookieName) === 'accepted';
  }

  hasRejected() {
    return this.getCookie(this.cookieName) === 'rejected';
  }

  getCookie(name) {
    const value = `; ${document.cookie}`;
    const parts = value.split(`; ${name}=`);
    if (parts.length === 2) return parts.pop().split(';').shift();
    return null;
  }

  escapeHtml(text) {
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
  }

  showBanner() {
    const banner = document.createElement('div');
    banner.id = 'cookie-consent-banner';
    banner.className = 'cookie-consent-banner';
    banner.setAttribute('role', 'dialog');
    banner.setAttribute('aria-label', 'Cookie Consent');

    // Get localized text from WordPress (or use defaults)
    const text = window.cookieConsentText || {
      title: 'We use cookies to give you the best possible experience on our website.',
      description: 'We use cookies and similar technologies to enhance site performance, personalize your experience, and gather anonymous data about how you use our site. We also work with trusted partners to understand general trends like interests and online behavior. You can manage your preferences anytime.',
      policyText: 'Learn more in our',
      policyLink: 'Cookies Policy',
      policyUrl: '/resplast/legal-privacy-policy/',
      acceptButton: 'Accept All',
      rejectButton: 'Reject All',
      acceptLabel: 'Accept all cookies',
      rejectLabel: 'Reject all cookies'
    };

    banner.innerHTML = `
      <div class="cookie-consent-content">
        <div class="cookie-consent-text">
          <h3 class="cookie-consent-title">${this.escapeHtml(text.title)}</h3>
          <p class="cookie-consent-description">
            ${this.escapeHtml(text.description)} ${this.escapeHtml(text.policyText)} 
            <a href="${this.escapeHtml(text.policyUrl)}" class="cookie-policy-link">${this.escapeHtml(text.policyLink)}</a>.
          </p>
        </div>
        <div class="cookie-consent-actions">
          <button id="cookie-reject" class="cookie-btn cookie-btn-reject" aria-label="${this.escapeHtml(text.rejectLabel)}">
            ${this.escapeHtml(text.rejectButton)}
          </button>
          <button id="cookie-accept" class="cookie-btn cookie-btn-accept" aria-label="${this.escapeHtml(text.acceptLabel)}">
            ${this.escapeHtml(text.acceptButton)}
          </button>
        </div>
      </div>
    `;

    document.body.appendChild(banner);

    // Add event listeners
    document.getElementById('cookie-accept').addEventListener('click', () => {
      this.setConsent('accepted');
      this.removeBanner();
      this.loadTrackingScripts();
    });

    document.getElementById('cookie-reject').addEventListener('click', () => {
      this.setConsent('rejected');
      this.removeBanner();
    });

    // Animate banner in
    setTimeout(() => {
      banner.classList.add('cookie-consent-visible');
    }, 100);
  }

  setConsent(value) {
    const expiryDate = new Date();
    expiryDate.setDate(expiryDate.getDate() + this.cookieExpiry);
    document.cookie = `${this.cookieName}=${value}; expires=${expiryDate.toUTCString()}; path=/; SameSite=Lax`;

    // Trigger custom event for analytics
    const event = new CustomEvent('cookieConsentChange', { detail: { consent: value } });
    document.dispatchEvent(event);
  }

  removeBanner() {
    const banner = document.getElementById('cookie-consent-banner');
    if (banner) {
      banner.classList.remove('cookie-consent-visible');
      setTimeout(() => {
        banner.remove();
      }, 300);
    }
  }

  loadTrackingScripts() {
    // Dispatch event that tracking scripts can listen to
    const event = new CustomEvent('cookieConsentGranted');
    document.dispatchEvent(event);

    // If Google Analytics is present, update consent
    if (typeof gtag !== 'undefined') {
      gtag('consent', 'update', {
        'analytics_storage': 'granted',
        'ad_storage': 'granted'
      });
    }
  }
}

// Auto-initialize when DOM is ready (singleton pattern)
if (!window.cookieConsentInstance) {
  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', () => {
      window.cookieConsentInstance = new CookieConsent();
    });
  } else {
    window.cookieConsentInstance = new CookieConsent();
  }
}
