# Automated Deployment Guide

## 🎯 How It Works

The service worker version is **automatically synced** from `package.json`. You only update the version in ONE place!

---

## 🚀 Quick Commands

### For Development

```bash
# Regular build (auto-generates service worker)
npm run build

# Development server (no service worker)
npm run dev

# Generate service worker only
npm run sw:generate
```

### For Production Deployment

```bash
# Automatic version bump + build (EASIEST)
npm run build:production

# This automatically:
# 1. Bumps version (1.0.1 → 1.0.2)
# 2. Builds assets
# 3. Generates service worker with new version
# 4. Copies to theme root
```

Then commit and push:
```bash
git add .
git commit -m "Deploy v$(node -p 'require(\"./package.json\").version')"
git push
```

---

## 📋 Complete Workflows

### Workflow 1: Small Changes (Same Version)

For CSS/JS tweaks during development:

```bash
npm run build
```

✅ Service worker regenerated with current version  
✅ No cache invalidation (faster for testing)

### Workflow 2: Production Deploy (New Version)

For releases to production:

```bash
npm run build:production
git add .
git commit -m "Release v1.0.2"
git push
```

✅ Version auto-bumped  
✅ Service worker regenerated  
✅ Old caches auto-deleted on user's next visit

### Workflow 3: Manual Version Control

If you want to manually set version:

```bash
# Edit package.json: "version": "1.0.5"
npm run build
```

✅ Service worker uses version 1.0.5  
✅ No manual updates needed

---

## 🎨 Version Bumping Options

### Patch (1.0.1 → 1.0.2)
```bash
npm version patch
npm run build
```
Use for: Bug fixes, small changes

### Minor (1.0.1 → 1.1.0)
```bash
npm version minor
npm run build
```
Use for: New features, significant updates

### Major (1.0.1 → 2.0.0)
```bash
npm version major
npm run build
```
Use for: Breaking changes, redesigns

---

## 🔍 How to Verify

After building, check the console output:
```
🚀 Generating service worker with version 1.0.2...
✅ Generated assets/js/service-worker.js
✅ Copied to service-worker.js
🎉 Service worker v1.0.2 generated successfully!
```

Check the generated file:
```bash
head -n 10 service-worker.js
```

Should show:
```javascript
/**
 * Service Worker for Resplast Theme
 * Version: 1.0.2 (auto-generated from package.json)
 */
const CACHE_VERSION = '1.0.2';
```

---

## 🧹 Cache Management

### During Development

Clear cache in browser console:
```javascript
window.swManager.clearCaches()
```

Or hard refresh: `Cmd+Shift+R` (Mac) / `Ctrl+Shift+R` (Windows)

### For Production

Just bump version - old caches auto-delete:
```bash
npm run build:production
```

---

## 📊 What Changed

**Before (Manual):**
1. Edit package.json → Change version
2. Edit service-worker.js → Change version
3. npm run build
4. cp assets/js/service-worker.js service-worker.js

**Now (Automated):**
1. `npm run build:production`

That's it! ✨

---

## 🔧 Configuration

All automation is configured in:
- `package.json` → Build scripts
- `scripts/generate-sw.js` → Auto-generation logic

To customize service worker behavior, edit the template in `scripts/generate-sw.js`.

---

## 🚨 Important Notes

### ✅ DO:
- Use `npm run build:production` for deployments
- Commit after version bumps
- Test locally before pushing

### ❌ DON'T:
- Manually edit `service-worker.js` (it's auto-generated)
- Manually edit `assets/js/service-worker.js` (also auto-generated)
- Forget to push after version bump

### 💡 TIP:
Edit the template in `scripts/generate-sw.js` to customize service worker behavior permanently.

---

## 📖 Quick Reference

| Command | When to Use |
|---------|-------------|
| `npm run dev` | Local development (no SW) |
| `npm run build` | Test build (same version) |
| `npm run build:production` | **Production deploy** |
| `npm run sw:generate` | Regenerate SW only |
| `npm version patch` | Bump patch version |
| `npm version minor` | Bump minor version |
| `npm version major` | Bump major version |

---

## 🎉 Summary

**You now have:**
- ✅ Fully automated service worker generation
- ✅ Auto-synced versions from package.json
- ✅ One-command production deploys
- ✅ Zero manual file editing

**To deploy:** Just run `npm run build:production` 🚀
