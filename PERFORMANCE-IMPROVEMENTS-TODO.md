# Performance Improvements TODO

This document outlines actionable performance improvements prioritized by impact and implementation effort.

---

## Performance Improvements (Prioritized by Impact)

### 1. **Service Worker for Asset Caching** ⭐ High Impact
**Description:**
- Cache Vite assets (CSS, JS) for instant repeat visits
- Offline support for critical pages
- Cache invalidation on asset updates

**Impact:** Near-instant repeat page loads (~80% faster)  
**Risk:** Medium (needs proper cache invalidation)  
**Effort:** 2-3 hours  
**Priority:** High

**Implementation:**
```javascript
// Create: assets/js/service-worker.js
const CACHE_NAME = 'resplast-v1';
const urlsToCache = [
  '/wp-content/themes/resplast-theme/dist/css/home.css',
  '/wp-content/themes/resplast-theme/dist/js/main.js',
  // Add other critical assets
];

self.addEventListener('install', (event) => {
  event.waitUntil(
    caches.open(CACHE_NAME)
      .then((cache) => cache.addAll(urlsToCache))
  );
});
```

---

### 2. **Database Query Optimization** ⭐ High Impact
**Description:**
- Cache ACF flexible content queries
- Optimize post meta queries
- Reduce database calls in `render_blocks()`
- Implement query result caching

**Impact:** 30-50% faster TTFB  
**Risk:** Low  
**Effort:** 1-2 hours  
**Priority:** High

**Implementation:**
```php
// Modify: inc/block-helpers.php
function render_blocks($field_name) {
    // Check transient cache first
    $cache_key = 'blocks_' . get_the_ID() . '_' . $field_name;
    $cached = get_transient($cache_key);
    
    if ($cached !== false) {
        echo $cached;
        return;
    }
    
    // Generate blocks
    ob_start();
    // ... existing block rendering code ...
    $output = ob_get_clean();
    
    // Cache for 12 hours
    set_transient($cache_key, $output, 12 * HOUR_IN_SECONDS);
    
    echo $output;
}
```

---

### 3. **Implement Resource Hints** ⭐ Quick Win
**Description:**
- Add `preconnect` for external domains (fonts, CDNs)
- Add `dns-prefetch` for third-party resources
- Currently missing from header.php

**Impact:** 200-500ms faster external resource loading  
**Risk:** Very Low  
**Effort:** 30 minutes  
**Priority:** High (Quick Win)

**Implementation:**
```php
// Modify: header.php or functions.php
function resplast_resource_hints() {
    ?>
    <!-- Preconnect to external domains -->
    <link rel="preconnect" href="https://fonts.googleapis.com" crossorigin>
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="dns-prefetch" href="//fonts.googleapis.com">
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link rel="preconnect" href="https://unpkg.com" crossorigin>
    <link rel="dns-prefetch" href="//unpkg.com">
    <?php
}
add_action('wp_head', 'resplast_resource_hints', 1);
```

---

### 4. **HTTP/2 Server Push** 🔥 Medium Impact
**Description:**
- Push critical CSS and JS files
- Requires server configuration (Apache/Nginx)
- Works well with existing Vite setup

**Impact:** 20-30% faster first paint  
**Risk:** Medium (needs proper configuration)  
**Effort:** 1-2 hours (including server config)  
**Priority:** Medium

**Implementation:**
```php
// Modify: functions.php
function resplast_http2_push() {
    if (!resplast_perf_enabled('http2_push')) return;
    
    $manifest = get_vite_manifest();
    if (isset($manifest['js/main.js'])) {
        header('Link: </wp-content/themes/resplast-theme/dist/' . $manifest['js/main.js']['file'] . '>; rel=preload; as=script', false);
    }
}
add_action('send_headers', 'resplast_http2_push');
```

---

### 5. **Component-Based Code Splitting** 🔥 Medium Impact
**Description:**
- Split vendor bundles (Alpine, Swiper, GSAP) further
- Load components only when needed
- Use dynamic imports for heavy blocks

**Impact:** 40-60KB smaller initial bundle  
**Risk:** Low  
**Effort:** 2-4 hours  
**Priority:** Medium

**Implementation:**
```javascript
// Modify: assets/js/main.js
// BEFORE: Import everything upfront
import Alpine from 'alpinejs';
import Swiper from 'swiper';

// AFTER: Dynamic imports
async function initHeroSlider() {
    if (document.querySelector('.hero-slider')) {
        const { Swiper } = await import('swiper');
        // Initialize slider
    }
}

async function initAnimations() {
    if (document.querySelector('[data-animate]')) {
        const { gsap } = await import('gsap');
        // Initialize animations
    }
}
```

**Vite Config:**
```javascript
// Modify: vite.config.js
build: {
  rollupOptions: {
    output: {
      manualChunks: {
        'vendor-alpine': ['alpinejs'],
        'vendor-swiper': ['swiper'],
        'vendor-gsap': ['gsap'],
      }
    }
  }
}
```

---

### 6. **Image Placeholder Strategy** 🎨 UX Impact
**Description:**
- Add LQIP (Low Quality Image Placeholders)
- BlurHash or base64 thumbnails
- Better perceived performance

**Impact:** Smoother loading experience  
**Risk:** Low  
**Effort:** 2-3 hours  
**Priority:** Medium

**Implementation:**
```php
// Modify: inc/performance-helpers.php
function resplast_optimized_image($image_id, $size = 'full', $args = []) {
    // Generate tiny blurred placeholder
    $placeholder = wp_get_attachment_image_src($image_id, 'thumbnail');
    $placeholder_base64 = 'data:image/svg+xml;base64,' . base64_encode(
        '<svg xmlns="http://www.w3.org/2000/svg" width="40" height="40"><filter id="b"><feGaussianBlur stdDeviation="12"/></filter><image filter="url(#b)" width="100%" height="100%" href="' . $placeholder[0] . '"/></svg>'
    );
    
    // Add placeholder to image
    $args['style'] = 'background-image: url(' . $placeholder_base64 . '); background-size: cover;';
    
    // ... rest of existing function
}
```

---

### 7. **WordPress Object Caching** 🚀 Backend Impact
**Description:**
- Implement Redis or Memcached
- Cache transients, queries, ACF data
- Persistent object caching

**Impact:** 50-70% faster backend processing  
**Risk:** Medium (requires server setup)  
**Effort:** 2-4 hours (including server config)  
**Priority:** Medium

**Implementation:**
```bash
# Install Redis (macOS with Homebrew)
brew install redis
brew services start redis

# Install PHP Redis extension
pecl install redis

# Install WordPress Redis plugin
wp plugin install redis-cache --activate
wp redis enable
```

```php
// Add to: wp-config.php
define('WP_REDIS_HOST', '127.0.0.1');
define('WP_REDIS_PORT', 6379);
define('WP_CACHE', true);
```

---

### 8. **Intersection Observer for Lazy Loading** 🔧 Better Control
**Description:**
- Replace native lazy loading with Intersection Observer
- Better control over load timing
- Progressive image loading

**Impact:** 15-25% better scroll performance  
**Risk:** Low  
**Effort:** 1-2 hours  
**Priority:** Low

**Implementation:**
```javascript
// Create: assets/js/lazy-load.js
export class LazyLoader {
  constructor() {
    this.observer = new IntersectionObserver(
      (entries) => this.handleIntersection(entries),
      { rootMargin: '50px' }
    );
    
    this.init();
  }
  
  init() {
    const lazyImages = document.querySelectorAll('img[loading="lazy"]');
    lazyImages.forEach((img) => this.observer.observe(img));
  }
  
  handleIntersection(entries) {
    entries.forEach((entry) => {
      if (entry.isIntersecting) {
        const img = entry.target;
        if (img.dataset.src) {
          img.src = img.dataset.src;
        }
        this.observer.unobserve(img);
      }
    });
  }
}
```

---

### 9. **Critical Path CSS Extraction** 📦 Advanced
**Description:**
- Extract actual critical CSS per page type
- Use tools like Critical or Critters
- Currently using minimal safe CSS

**Impact:** 30-40% faster first render  
**Risk:** High (needs testing per page)  
**Effort:** 4-6 hours  
**Priority:** Low (Advanced)

**Implementation:**
```bash
# Install critical CSS extraction tool
npm install --save-dev critical

# Add to package.json scripts
"extract-critical": "critical path/to/page.html --base dist --inline > critical.css"
```

```javascript
// Modify: vite.config.js
import { critical } from 'critical';

export default {
  plugins: [
    {
      name: 'critical-css',
      closeBundle: async () => {
        await critical.generate({
          base: 'dist/',
          src: 'index.html',
          target: 'critical.css',
          width: 1300,
          height: 900
        });
      }
    }
  ]
}
```

---

### 10. **Reduce Third-Party Scripts** 🎯 Quick Win
**Description:**
- Audit and defer non-critical scripts
- Check for unused WordPress plugins
- Lazy load tracking scripts after interaction

**Impact:** 20-40% smaller JS bundle  
**Risk:** Low  
**Effort:** 1-2 hours  
**Priority:** Medium

**Implementation:**
```php
// Add to: functions.php
function resplast_defer_scripts($tag, $handle) {
    $defer_scripts = [
        'wp-embed',
        'comment-reply',
        // Add other non-critical scripts
    ];
    
    if (in_array($handle, $defer_scripts)) {
        return str_replace(' src', ' defer src', $tag);
    }
    
    return $tag;
}
add_filter('script_loader_tag', 'resplast_defer_scripts', 10, 2);

// Lazy load analytics
function resplast_lazy_analytics() {
    ?>
    <script>
    // Load analytics after user interaction
    let analyticsLoaded = false;
    const loadAnalytics = () => {
        if (analyticsLoaded) return;
        analyticsLoaded = true;
        
        // Load Google Analytics or other tracking
        const script = document.createElement('script');
        script.src = 'https://www.googletagmanager.com/gtag/js?id=GA_ID';
        document.head.appendChild(script);
    };
    
    // Load on first interaction
    ['mousedown', 'touchstart', 'keydown', 'scroll'].forEach(event => {
        document.addEventListener(event, loadAnalytics, { once: true });
    });
    
    // Or after 5 seconds
    setTimeout(loadAnalytics, 5000);
    </script>
    <?php
}
add_action('wp_footer', 'resplast_lazy_analytics');
```

---

## Implementation Priority Order (Recommended)

### Phase 1: Quick Wins (Week 1)
1. ✅ **Resource Hints** (30 mins, low risk)
2. ✅ **Reduce Third-Party Scripts** (1-2 hours, low risk)

### Phase 2: High Impact (Week 2-3)
3. 🚀 **Service Worker** (2-3 hours, medium risk)
4. 🚀 **Database Query Optimization** (1-2 hours, low risk)

### Phase 3: Advanced Optimizations (Week 4+)
5. 🔥 **Component Code Splitting** (2-4 hours, low risk)
6. 🔥 **WordPress Object Caching** (requires hosting setup)
7. 🎨 **Image Placeholder Strategy** (2-3 hours, low risk)

### Phase 4: Fine-Tuning (Future)
8. 🔧 **HTTP/2 Server Push** (1-2 hours, medium risk)
9. 🔧 **Intersection Observer** (1-2 hours, low risk)
10. 📦 **Critical Path CSS** (4-6 hours, high risk)

---

## Testing Checklist

Before implementing any feature:
- [ ] Test on homepage
- [ ] Test on inner pages
- [ ] Test on mobile devices
- [ ] Run Lighthouse audit
- [ ] Verify layout is not broken
- [ ] Check console for errors
- [ ] Test on 3G throttling
- [ ] Verify Core Web Vitals

---

## Current Performance Status

**Current Scores:**
- CSS Bundle: 47KB ✅
- JS Bundle: 70KB ✅
- LCP: ~1.5-2.5s ✅
- INP: <150ms ✅
- CLS: <0.1 ✅

**Expected After All Improvements:**
- CSS Bundle: 40KB (-15%)
- JS Bundle: 50KB (-29%)
- LCP: ~1.0-1.8s (-40%)
- INP: <100ms (-33%)
- CLS: <0.05 (-50%)
- **Lighthouse Score: 95-100**

---

## Configuration Updates Needed

Add to `performance-config.php`:

```php
// Service Worker
define('RESPLAST_SERVICE_WORKER', false);

// Database Caching
define('RESPLAST_DB_CACHE', false);

// HTTP/2 Push
define('RESPLAST_HTTP2_PUSH', false);

// Code Splitting
define('RESPLAST_CODE_SPLITTING', true);

// Image Placeholders
define('RESPLAST_IMAGE_PLACEHOLDERS', false);
```

---

## Notes

- Start with low-risk, high-impact improvements first
- Test each improvement individually before moving to the next
- Document any issues encountered during implementation
- Monitor Core Web Vitals after each change
- Keep performance budgets in mind (CSS: 300KB, JS: 350KB)

---

**Last Updated:** 2025-10-27  
**Next Review:** After Phase 1 completion
