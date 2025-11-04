# Performance Testing Guide (2025 Standards)

## 🧪 Lab Testing (Development)

### 1. Chrome DevTools Lighthouse
```bash
# Open your site in Chrome
open "http://localhost/resplast"

# Then:
# 1. Open DevTools (F12 or Cmd+Opt+I)
# 2. Go to "Lighthouse" tab
# 3. Select "Performance" + "Desktop" or "Mobile"
# 4. Click "Analyze page load"
```

**Target Scores (2025):**
- ✅ Performance: ≥90
- ✅ LCP: ≤2.5s
- ✅ INP: ≤200ms  
- ✅ CLS: ≤0.1

### 2. WebPageTest (Advanced Analysis)
```bash
# Visit: https://www.webpagetest.org/
# Enter your URL: http://your-site.com
# Settings:
# - Location: Closest to your users
# - Browser: Chrome
# - Connection: 3G Fast (mobile testing)
```

### 3. Lighthouse CI (Automated)
Create `.lighthouserc.json` in your theme root:

```json
{
  "ci": {
    "assert": {
      "assertions": {
        "categories:performance": ["error", {"minScore": 0.9}],
        "resource-summary:total": ["error", {"maxNumericValue": 300000}],
        "script-treemap-data": ["warn", {"maxLength": 350000}],
        "largest-contentful-paint": ["error", {"maxNumericValue": 2500}],
        "interaction-to-next-paint": ["error", {"maxNumericValue": 200}],
        "cumulative-layout-shift": ["error", {"maxNumericValue": 0.1}]
      }
    }
  }
}
```

Install and run:
```bash
npm install -g @lhci/cli
lhci autorun --upload.target=filesystem --upload.outputDir=./lhci_reports
```

## 📱 Real User Monitoring (RUM)

### 1. Check Core Web Vitals Collection
Open browser console on your site and run:
```javascript
// Check if monitoring is active
console.log('Web Vitals monitoring active:', typeof onLCP !== 'undefined');

// Manual test
import('https://unpkg.com/web-vitals@4/dist/web-vitals.js').then(({onLCP, onINP, onCLS}) => {
  onLCP(console.log);
  onINP(console.log);  
  onCLS(console.log);
});
```

### 2. Network Tab Analysis
```bash
# In Chrome DevTools:
# 1. Network tab
# 2. Reload page
# 3. Check for:
#    - Hero image has fetchpriority="high" 
#    - CSS loads async (look for preload rel)
#    - Images have proper loading="lazy"
#    - AVIF/WebP formats being served
```

## 🔍 Specific Feature Testing

### Test Image Optimizations
```bash
# Check if AVIF/WebP is being served
curl -H "Accept: image/avif,image/webp,*/*" -I "http://localhost/resplast/wp-content/uploads/2024/01/hero-image.jpg"

# Should redirect to .avif or .webp version
```

### Test Critical CSS
```bash
# View page source - should see:
# <style id="critical-css">/* minified CSS */</style>
# <link rel="preload" href="style.css" as="style" onload="...">
```

### Test Prefetch Hints
```bash
# View page source on homepage - should see:
# <link rel="prefetch" href="/about" as="document">
# <link rel="prefetch" href="/contact" as="document">
```

### Test Font Loading  
```bash
# Check for font preloading in <head>:
# <link rel="preload" href="fonts/Inter-Regular.woff2" as="font" type="font/woff2" crossorigin>
```

## 📊 Performance Budgets

### Bundle Size Limits (2025 Standards)
```bash
# Check total CSS size
find ./assets/css -name "*.css" -exec wc -c {} + | tail -1
# Target: <300KB total

# Check JavaScript size  
find ./assets/js -name "*.js" -exec wc -c {} + | tail -1
# Target: <350KB total

# Check critical CSS size
# Should be <14KB for above-the-fold content
```

## 🚀 Testing Checklist

### Before Release
- [ ] Lighthouse Performance Score ≥90
- [ ] LCP ≤2.5s on mobile 3G
- [ ] INP ≤200ms during interactions
- [ ] CLS ≤0.1 (no layout jumps)
- [ ] TTFB ≤800ms
- [ ] Hero images have `fetchpriority="high"`
- [ ] Off-screen images lazy load
- [ ] AVIF/WebP formats served to supported browsers
- [ ] Critical CSS inlined (<14KB)
- [ ] Non-critical CSS loads async
- [ ] Fonts preloaded with `font-display: swap`
- [ ] Prefetch hints for likely next pages
- [ ] Core Web Vitals monitoring active

### Production Testing
- [ ] Test on actual mobile devices
- [ ] Test on slow 3G connection
- [ ] Test with Chrome UX Report data
- [ ] Set up PageSpeed Insights monitoring
- [ ] Configure Search Console Core Web Vitals

## 🔧 Debug Common Issues

### If LCP is slow:
1. Check hero image has `fetchpriority="high"`
2. Verify critical CSS is inlined
3. Check for render-blocking resources
4. Test image format (AVIF > WebP > JPEG)

### If INP is high:
1. Check for long JavaScript tasks
2. Verify background work is scheduled
3. Test interaction responsiveness
4. Check for heavy event listeners

### If CLS is high:
1. Verify images have width/height attributes
2. Check font loading (use font-display: swap)
3. Look for dynamic content insertion
4. Test on different screen sizes

## 📈 Monitoring Tools

### Free Tools:
- **Lighthouse CI** (automated testing)
- **PageSpeed Insights** (Google's analysis)
- **Search Console** (Core Web Vitals report)
- **Chrome UX Report** (real user data)

### Premium Tools:
- **WebPageTest** (detailed analysis)
- **GTmetrix** (performance monitoring)
- **Pingdom** (uptime + performance)
- **New Relic** (comprehensive monitoring)

## 🎯 Target Metrics Summary

| Metric | Target | Good | Needs Improvement |
|--------|--------|------|-------------------|
| **LCP** | ≤2.5s | ≤2.5s | 2.5s-4.0s |
| **INP** | ≤200ms | ≤200ms | 200ms-500ms |
| **CLS** | ≤0.1 | ≤0.1 | 0.1-0.25 |
| **TTFB** | ≤800ms | ≤800ms | 800ms-1800ms |
| **Performance Score** | ≥90 | 90-100 | 50-89 |

Test regularly and iterate on the biggest performance bottlenecks first!