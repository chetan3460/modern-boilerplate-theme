# Staging Server Testing Guide

## ✅ Deployment Complete

**Version:** 1.0.3  
**Commit:** bf895f7  
**Pushed to:** main branch

---

## 🧪 How to Test on Staging

### 1. Pull Changes on Staging Server

SSH into staging and run:

```bash
cd /path/to/staging/wp-content/themes/resplast-theme
git pull origin main
```

### 2. Verify Files

Check that service worker exists:

```bash
ls -lh service-worker.js
head -10 service-worker.js
```

Should show:
```
Version: 1.0.3 (auto-generated from package.json)
const CACHE_VERSION = '1.0.3';
```

---

## 📊 Console Logging - What You'll See

### On First Page Load:

```
[SW Manager] Service Worker registered successfully
[SW] Installing service worker v1.0.3...
[SW] Service worker installed - assets will be cached on-demand
[SW] Activating service worker v1.0.3...
```

### When Assets Load from Network (First Time):

```
📥 [SW Fetching] main.DaW9xCMU.js
💾 [SW Cached] main.DaW9xCMU.js
📥 [SW Fetching] home.B87Qwl8D.css
💾 [SW Cached] home.B87Qwl8D.css
📥 [SW Fetching] vendor-alpine-chunk.B22izVms.js
💾 [SW Cached] vendor-alpine-chunk.B22izVms.js
```

### When Assets Load from Cache (Second Visit):

```
✅ [SW Cache Hit] main.DaW9xCMU.js (~5ms)
✅ [SW Cache Hit] home.B87Qwl8D.css (~5ms)
✅ [SW Cache Hit] vendor-alpine-chunk.B22izVms.js (~5ms)
```

---

## 🔍 Testing Checklist

### Step 1: Open DevTools
- Press `F12` or `Cmd+Option+I`
- Go to **Console** tab

### Step 2: First Visit
- Navigate to homepage
- Watch console logs
- Should see `📥 [SW Fetching]` messages
- Then `💾 [SW Cached]` messages

### Step 3: Check Cache
- Go to **Application** → **Cache Storage**
- Should see: `resplast-v1.0.3-assets`
- Click it to see cached files

### Step 4: Second Visit
- Reload the page (`Cmd+R`)
- Watch console logs
- Should see `✅ [SW Cache Hit]` messages
- Assets load in ~5ms

### Step 5: Check Network Tab
- Go to **Network** tab
- Reload page
- Look for assets with:
  - **Size:** `(disk cache)` or `(ServiceWorker)`
  - **Time:** < 10ms

---

## 🎯 Expected Results

| Test | Expected Result | Status |
|------|----------------|---------|
| Service Worker Registers | ✅ Console shows registration | ⬜ |
| Assets Cached on First Load | 📥💾 Console logs | ⬜ |
| Cache Storage Created | `resplast-v1.0.3-assets` visible | ⬜ |
| Second Load from Cache | ✅ Console logs | ⬜ |
| Network Tab Shows Cache | `(disk cache)` in size column | ⬜ |
| Load Time < 10ms | Assets load instantly | ⬜ |

---

## 🐛 Troubleshooting

### No Console Logs Appear

**Problem:** Service worker not registering

**Check:**
```javascript
// In console
navigator.serviceWorker.getRegistration()
```

**Solution:**
- Ensure HTTPS is enabled on staging
- Check `service-worker.js` exists in theme root
- Hard refresh: `Cmd+Shift+R`

---

### Still See Old Version (v1.0.2)

**Problem:** Old service worker cached

**Solution:**
```javascript
// In console
window.swManager.unregister()
window.swManager.clearCaches()
location.reload()
```

---

### Assets Not Caching

**Problem:** Service worker scope issue

**Check:**
```javascript
// In console
navigator.serviceWorker.controller
```

If `null`, service worker isn't controlling the page.

**Solution:**
- Reload page once more
- Service worker needs one page load to activate

---

## 📸 Screenshot Checklist

Take screenshots of:

1. ✅ Console logs showing cache hit messages
2. ✅ Application → Cache Storage showing v1.0.3
3. ✅ Network tab showing `(disk cache)`
4. ✅ Performance comparison (first vs second load)

---

## 📊 Performance Metrics to Note

### First Visit (Baseline):
- CSS load time: ~100-300ms
- JS load time: ~150-400ms
- Total asset load: ~500-1000ms

### Second Visit (Cached):
- CSS load time: ~5-10ms
- JS load time: ~5-10ms
- Total asset load: ~20-50ms

**Improvement:** ~80-95% faster! 🚀

---

## 🎬 Quick Test Script

Paste this in console to see full status:

\`\`\`javascript
(async () => {
  console.log('🔍 Service Worker Test Report\\n');
  
  // Registration
  const reg = await navigator.serviceWorker.getRegistration();
  console.log('✅ Status:', reg?.active?.state || 'Not registered');
  
  // Version
  const sw = await fetch('/wp-content/themes/resplast-theme/service-worker.js');
  const text = await sw.text();
  const version = text.match(/Version: ([\\d.]+)/)?.[1];
  console.log('📌 Version:', version);
  
  // Caches
  const caches = await caches.keys();
  console.log('\\n📦 Active Caches:');
  caches.forEach(c => console.log('  -', c));
  
  // Cache contents
  if (caches.length > 0) {
    const cache = await caches.open(caches[0]);
    const keys = await cache.keys();
    console.log(\`\\n📄 Cached Files: \${keys.length}\`);
  }
  
  console.log('\\n✅ Test complete!');
})();
\`\`\`

---

## ✨ Success Criteria

Service worker is working if you see:

✅ Version 1.0.3 in service-worker.js  
✅ Console shows emoji logs (📥, 💾, ✅)  
✅ Cache storage contains v1.0.3  
✅ Assets load in < 10ms on second visit  
✅ Network tab shows `(disk cache)`

---

## 📞 Report Back

After testing, report:

1. ✅ Console logs visible?
2. ✅ Cached files in DevTools?
3. ✅ Performance improvement noticed?
4. ❌ Any errors in console?

---

**Ready to test on staging!** 🚀
