# Cookie Consent Banner Implementation

## Overview

A custom GDPR-compliant cookie consent banner has been implemented matching the Resplast design with a light blue background and red action buttons.

## Files Added

### JavaScript
- **`assets/js/cookie-consent.js`** - Main cookie consent logic
  - Automatic banner display on first visit
  - Accept/Reject functionality
  - Cookie storage (365 days expiry)
  - Event dispatching for tracking scripts

### CSS
- **`assets/css/cookie-consent.css`** - Styled banner matching design
  - Light blue background (#BDE0E7)
  - Red buttons (#DC2626)
  - Responsive design (mobile, tablet, desktop)
  - Smooth animations

### PHP
- **`inc/cookie-helpers.php`** - Server-side helper functions
  - `resplast_has_cookie_consent()` - Check if cookies accepted
  - `resplast_has_rejected_cookies()` - Check if cookies rejected
  - `resplast_enqueue_tracking_scripts()` - Conditionally load tracking

## How It Works

### 1. Banner Display
- Automatically shows on first visit
- Positioned at bottom of page with slide-up animation
- Does not block content (non-intrusive)

### 2. User Actions
**Accept All:**
- Sets cookie: `resplast_cookie_consent=accepted`
- Dispatches `cookieConsentGranted` event
- Loads tracking scripts (Google Analytics, Facebook Pixel, etc.)

**Reject All:**
- Sets cookie: `resplast_cookie_consent=rejected`
- No tracking scripts loaded
- User preference remembered for 365 days

### 3. Cookie Storage
```javascript
Cookie Name: resplast_cookie_consent
Values: 'accepted' | 'rejected'
Expiry: 365 days
Path: /
SameSite: Lax
```

## Adding Tracking Scripts

### Method 1: PHP (Server-side)
Edit `inc/cookie-helpers.php` and uncomment/add your tracking code:

```php
function resplast_enqueue_tracking_scripts() {
    if (!resplast_has_cookie_consent()) {
        return;
    }

    // Google Analytics
    ?>
    <script async src="https://www.googletagmanager.com/gtag/js?id=GA_MEASUREMENT_ID"></script>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());
        gtag('config', 'GA_MEASUREMENT_ID');
    </script>
    <?php
}
```

### Method 2: JavaScript (Client-side)
Listen for the consent event:

```javascript
document.addEventListener('cookieConsentGranted', function() {
    // Load Google Analytics
    // Load Facebook Pixel
    // Load other tracking scripts
});
```

### Method 3: Google Consent Mode V2
Uncomment in `inc/cookie-helpers.php`:

```php
add_action('wp_head', 'resplast_add_gtag_consent_mode', 1);
```

This sets default consent to denied and updates when user accepts.

## Customization

### Text Content
Edit `assets/js/cookie-consent.js` line 47-52:

```javascript
banner.innerHTML = `
    <div class="cookie-consent-content">
        <div class="cookie-consent-text">
            <h3 class="cookie-consent-title">Your custom title</h3>
            <p class="cookie-consent-description">Your custom description...</p>
        </div>
        ...
    </div>
`;
```

### Colors
Edit `assets/css/cookie-consent.css`:

```css
/* Background color */
.cookie-consent-banner {
    background: linear-gradient(to bottom, rgba(189, 224, 231, 0.98), rgba(189, 224, 231, 1));
}

/* Button colors */
.cookie-btn-accept {
    background: #dc2626; /* Red */
}
```

### Cookie Policy Link
Update the link in the banner HTML (line 52 of cookie-consent.js):

```html
<a href="/cookie-policy" class="cookie-policy-link">Cookies Policy</a>
```

Then create a WordPress page at `/cookie-policy` with your policy content.

## Testing

### 1. Clear Existing Cookies
- Open DevTools (F12)
- Application → Cookies → Delete `resplast_cookie_consent`

### 2. Reload Page
- Banner should appear at bottom
- Test both Accept and Reject buttons

### 3. Verify Cookie Storage
- DevTools → Application → Cookies
- Check `resplast_cookie_consent` value

### 4. Test Persistence
- Reload page - banner should not appear
- Cookie should persist for 365 days

## GDPR Compliance Checklist

- ✅ **Explicit Consent**: Users must click to accept
- ✅ **Opt-out Option**: Clear "Reject All" button provided
- ✅ **Clear Information**: Banner explains cookie usage
- ✅ **Easy Access**: Link to detailed cookie policy
- ✅ **No Pre-ticking**: No cookies loaded until acceptance
- ✅ **Granular Control**: Accept/Reject all cookies
- ⚠️ **Cookie Categories**: Currently all-or-nothing (can extend)

## Performance Impact

- **CSS**: ~5KB (minified)
- **JavaScript**: ~3KB (minified)
- **No external dependencies**
- **Lazy animation**: Uses CSS transforms for smooth 60fps
- **Z-index**: 9999 (won't interfere with site content)

## Browser Support

- Chrome/Edge (last 2 versions)
- Firefox (last 2 versions)
- Safari (last 2 versions)
- Mobile browsers (iOS Safari, Chrome Mobile)

## Accessibility

- ✅ ARIA labels on buttons
- ✅ Keyboard navigation support
- ✅ Focus indicators
- ✅ Screen reader compatible
- ✅ High contrast text

## Future Enhancements (Optional)

1. **Granular Cookie Categories**
   - Essential cookies (always on)
   - Analytics cookies (optional)
   - Marketing cookies (optional)
   - Preference cookies (optional)

2. **Settings Modal**
   - Allow users to manage preferences after initial choice
   - Add "Cookie Settings" link in footer

3. **Multi-language Support**
   - Detect language from WordPress
   - Load appropriate translations

4. **Analytics Dashboard**
   - Track acceptance/rejection rates
   - Monitor compliance

## Support

For issues or questions:
1. Check browser console for JavaScript errors
2. Verify files are loaded in Network tab
3. Ensure Vite build completed successfully: `npm run build`
4. Clear WordPress cache if using caching plugins

## Build Commands

```bash
# Development (with hot reload)
npm run dev

# Production build
npm run build

# Clean build artifacts
npm run clean
```
