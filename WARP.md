# WARP.md

This file provides guidance to WARP (warp.dev) when working with code in this repository.

## Development Commands

### Build & Development
```bash
# Development server with hot module reload (HMR)
npm run dev

# Production build (automatic version bump + build)
npm run build:production

# Regular build without version bump
npm run build

# Clean build artifacts
npm run clean
```

### Code Quality
```bash
# Format all files (PHP, JS, CSS, HTML)
npm run format

# Check formatting without making changes
npm run format:check
```

### Service Worker
```bash
# Regenerate service worker only
npm run sw:generate
```

### Version Bumping
```bash
# Patch version (1.0.1 → 1.0.2) - for bug fixes
npm version patch && npm run build

# Minor version (1.0.1 → 1.1.0) - for new features
npm version minor && npm run build

# Major version (1.0.1 → 2.0.0) - for breaking changes
npm version major && npm run build
```

## Architecture Overview

### Modern WordPress Theme with Vite + Tailwind CSS
This is a **modular WordPress theme** built with modern tooling:
- **Vite** for fast development and optimized production builds
- **Tailwind CSS v4** for utility-first styling
- **GSAP** for animations (with lazy-loaded ScrollSmoother)
- **ACF (Advanced Custom Fields)** for flexible content
- **Performance-first architecture** with 2025 web standards

### Core Technology Stack
- **Build Tool**: Vite 7.x with live reload
- **CSS Framework**: Tailwind CSS 4.x + PostCSS
- **JS Libraries**: GSAP 3.x, jQuery 3.7, Swiper 11.x
- **PHP Version**: WordPress-compatible (7.4+)
- **Environment Detection**: Automatic dev/production mode via `WP_ENV`

### Project Structure

```
/
├── assets/                 # Source files (compiled by Vite)
│   ├── css/               # CSS entry points
│   │   ├── style.css      # Main stylesheet
│   │   ├── home.css       # Homepage-specific styles
│   │   └── components/    # Component-specific CSS
│   └── js/
│       ├── main.js        # Main entry point
│       ├── components/    # JS components (GSAP, DynamicImports)
│       ├── helpers/       # Utility functions
│       └── utils/         # Shared utilities
│
├── inc/                   # PHP functionality (modular)
│   ├── core/             # Core theme initialization
│   │   ├── init.php      # Auto-loads all core PHP files
│   │   ├── vite.php      # Vite dev/prod asset handling
│   │   ├── enqueue.php   # Script/style enqueuing
│   │   ├── setup.php     # Theme setup & supports
│   │   └── helpers.php   # Helper functions
│   ├── features/         # Feature modules
│   │   ├── acf-block-generator.php    # Auto-generates ACF block templates
│   │   ├── filters.php                 # WordPress filters
│   │   ├── image-convert.php          # WebP/AVIF conversion
│   │   └── custom-posts.php           # Custom post type registration
│   ├── ajax/             # AJAX handlers
│   │   └── product-filter.php
│   └── post-types/       # Custom post type definitions
│       └── reports.php   # Reports CPT (investor reports, etc.)
│
├── templates/            # Template parts (organized by feature)
│   ├── blocks/          # ACF flexible content blocks
│   │   ├── global/      # Reusable blocks (CTAs, forms, etc.)
│   │   └── [feature]/   # Feature-specific blocks
│   ├── components/      # Reusable components
│   ├── parts/          # Template parts (header, footer, etc.)
│   ├── products/       # Product-related templates
│   └── news/           # News/blog templates
│
├── acf-json/           # ACF field definitions (version controlled)
├── dist/               # Production build output (generated)
├── public/             # Static assets
├── scripts/            # Build scripts
│   └── generate-sw.js  # Service worker generator
│
├── vite.config.js      # Vite configuration
├── tailwind.config.js  # Tailwind CSS configuration
├── postcss.config.cjs  # PostCSS configuration
└── performance-config.php  # Performance feature toggles
```

## Key Architectural Patterns

### 1. Modular PHP Architecture
- **Auto-loading**: `inc/core/init.php` automatically loads all PHP files in `/inc/core/`
- **Feature modules**: Optional features in `/inc/features/` are loaded conditionally
- **Namespace convention**: Use prefix `T_PREFIX` (defined as `wpmodernbp` in functions.php)

### 2. Vite Integration
- **Dev mode**: Vite runs on `localhost:3000` with HMR (Hot Module Replacement)
- **Production**: Assets compiled to `/dist/` with content hashing for cache busting
- **Manifest-based loading**: PHP reads `dist/.vite/manifest.json` to enqueue correct hashed filenames
- **Environment detection**: Theme automatically detects dev server at `localhost:3000`

**Key file**: `inc/core/vite.php` handles all dev/prod asset loading logic

### 3. ACF Block Auto-Generation
When you create a new ACF flexible content layout, the theme automatically generates a PHP template:
- **Location detection**: Blocks with patterns like `global_*`, `cta_*`, `contact_*` → `templates/blocks/global/`
- **Regular blocks**: All others → `templates/blocks/`
- **Never overwrites**: Existing files are preserved
- **Smart field rendering**: Automatically generates appropriate HTML based on field types

**Key file**: `inc/features/acf-block-generator.php`

### 4. Performance Optimizations
Performance features are **toggleable** via `performance-config.php`:
- **Critical CSS**: Minimal above-the-fold styles (2KB)
- **Lazy loading**: Images load on scroll (native `loading="lazy"`)
- **Modern image formats**: Automatic WebP/AVIF conversion with fallbacks
- **Hero image priority**: `fetchpriority="high"` for LCP optimization
- **Smart prefetching**: Preloads likely next pages
- **Core Web Vitals monitoring**: Real-time performance tracking via `web-vitals` library

**Safe mode is default** - aggressive optimizations like async CSS are disabled to prevent layout breaks.

### 5. CSS Architecture
- **Tailwind CSS 4.x**: Utility-first with JIT compilation
- **Component CSS**: Organized in `assets/css/components/`
- **Page-specific CSS**: `home.css` for homepage (code-split)
- **CSS code splitting**: Vite automatically splits CSS per entry point

### 6. JavaScript Architecture
- **Main entry**: `assets/js/main.js` initializes the app
- **Component-based**: Classes in `assets/js/components/` (e.g., `GSAPAnimations`, `DynamicImports`)
- **Lazy loading**: Heavy libraries like `ScrollSmoother` load on idle
- **Dynamic imports**: Route-based component loading for better performance

## Important Conventions

### ACF (Advanced Custom Fields)
- **JSON sync**: All field definitions stored in `/acf-json/` (version controlled)
- **Block templates**: Never manually create in `templates/blocks/` - let ACF Block Generator handle it
- **Global blocks**: Use naming patterns: `global_*`, `cta_*`, `contact_*`, `newsletter_*`, `testimonial*`, `footer_*`, `header_*`, `banner_*`
- **Hide block field**: Always include `hide_block` (True/False) field in layouts

### Image Optimization
- **Function**: Use `resplast_optimized_image($image_id, $size, $options)` for all images
- **Hero images**: Use `resplast_hero_image($image_id, $alt, $class)` for above-the-fold images
- **Options**: `priority` (bool), `lazy` (bool), `avif_support` (bool), `class`, `alt`

### Custom Post Types
- **Reports CPT**: Includes taxonomies `report_category`, `financial_year`, `quarter`
- **Registration**: Add new CPTs in `inc/post-types/`
- **Naming**: Use `resplast_` prefix for all custom functions

### Styling
- **Tailwind-first**: Prefer Tailwind utilities over custom CSS
- **Custom CSS**: Only for complex animations or one-off styles
- **Responsive**: Mobile-first approach (Tailwind defaults)
- **Dark mode**: Not implemented yet

## Development Workflows

### Adding a New ACF Block
1. Create layout in ACF field group (WordPress Admin → Custom Fields)
2. Save field group → template auto-generates in `templates/blocks/`
3. Customize the generated template as needed
4. Template won't be overwritten on subsequent saves

### Modifying Build Process
- **Vite config**: `vite.config.js` (entry points, build options, plugins)
- **Tailwind config**: `tailwind.config.js` (content paths, theme customization)
- **PostCSS**: `postcss.config.cjs` (CSS processing pipeline)

### Adding New Dependencies
```bash
# Install and save to package.json
npm install <package-name>

# Development dependencies
npm install -D <package-name>
```

### Performance Testing
1. Enable/disable features in `performance-config.php`
2. Test one feature at a time
3. Run Lighthouse in Chrome DevTools (Performance + Mobile)
4. Check Core Web Vitals in browser console:
   ```js
   import('https://unpkg.com/web-vitals@4/dist/web-vitals.js')
     .then(({onLCP, onINP, onCLS}) => {
       onLCP(console.log);
       onINP(console.log);
       onCLS(console.log);
     });
   ```

### Deployment
```bash
# Recommended: One-command deploy
npm run build:production

# Then commit and push
git add .
git commit -m "Deploy v$(node -p 'require("./package.json").version')"
git push
```

## Performance Budgets
- **CSS**: 300KB (current: ~47KB ✅)
- **JS**: 350KB (current: ~70KB ✅)
- **LCP**: <2.5s target
- **INP**: <200ms target
- **CLS**: <0.1 target

## Files to Never Edit Manually
- `service-worker.js` (auto-generated from `scripts/generate-sw.js`)
- `assets/js/service-worker.js` (auto-generated)
- `dist/` directory (Vite build output)
- `node_modules/` (npm packages)

## WordPress Core Optimizations
The theme includes aggressive WordPress optimizations:
- Gutenberg editor disabled (`use_block_editor_for_post` → false)
- Emoji scripts removed
- Heartbeat slowed to 60s
- Post revisions limited to 3
- Query strings removed from static resources
- Unnecessary head tags removed (RSD, WLW manifest, shortlinks, etc.)

## Service Worker
- **Version sync**: Automatically synced from `package.json` version
- **Cache strategy**: Precaches CSS/JS, network-first for HTML
- **Manual control**: `window.swManager.clearCaches()` in browser console

## Debugging
- **Vite dev badge**: Appears bottom-right in dev mode (links to `localhost:3000`)
- **HMR**: Changes to PHP, CSS, JS trigger auto-reload
- **Error overlay**: Vite shows errors as overlay in dev mode
- **ACF Block Generator logs**: Check `error_log` for generation status

## Common Pitfalls
- **Don't disable Vite dev server** while working - HMR won't work
- **Always run `npm run build`** before pushing to production
- **Don't edit files in `dist/`** - they get overwritten on build
- **Use `wp_kses_post()` for WYSIWYG content** - never raw `echo`
- **Escape all output** - use `esc_html()`, `esc_url()`, `esc_attr()`
- **Never commit `.env`** if it contains secrets
