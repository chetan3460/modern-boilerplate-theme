# Modern Boilerplate WordPress Theme

A modern, performance-optimized WordPress theme built with **Vite**, **Tailwind CSS v4**, and **ACF (Advanced Custom Fields)**.

## ✨ Features

- ⚡ **Vite 7.x** - Lightning-fast development with HMR (Hot Module Replacement)
- 🎨 **Tailwind CSS 4.x** - Utility-first CSS framework with JIT compilation
- 🎬 **GSAP 3.x** - Professional-grade animations with lazy-loaded ScrollSmoother
- 📦 **ACF Integration** - Flexible content blocks with auto-generated templates
- 🚀 **Performance First** - Optimized for Core Web Vitals (LCP, INP, CLS)
- 📱 **Responsive** - Mobile-first design approach
- 🔧 **Modern Tooling** - ESLint, Prettier, Husky for code quality

## 🛠️ Tech Stack

- **Build Tool**: Vite 7.x
- **CSS**: Tailwind CSS 4.x + PostCSS
- **JavaScript**: ES6+ with GSAP, jQuery, Swiper
- **PHP**: WordPress 6.0+ compatible
- **Package Manager**: npm

## 📋 Requirements

- Node.js 18+ and npm
- PHP 7.4+
- WordPress 6.0+
- Advanced Custom Fields (ACF) Pro plugin

## 🚀 Quick Start

### Installation

```bash
# Clone the repository
git clone https://github.com/chetan3460/modern-boilerplate-theme.git

# Navigate to theme directory
cd modern-boilerplate-theme

# Install dependencies
npm install

# Start development server
npm run dev
```

### Development Commands

```bash
# Start dev server with HMR (localhost:3000)
npm run dev

# Build for production (with version bump)
npm run build:production

# Regular build (no version bump)
npm run build

# Format code (PHP, JS, CSS, HTML)
npm run format

# Check formatting
npm run format:check

# Clean build artifacts
npm run clean
```

## 📁 Project Structure

```
/
├── assets/              # Source files (Vite compiles these)
│   ├── css/            # Stylesheets (Tailwind + custom)
│   └── js/             # JavaScript (components, utilities)
├── inc/                # PHP functionality
│   ├── core/           # Core theme files (auto-loaded)
│   ├── features/       # Feature modules (ACF, filters, etc.)
│   ├── ajax/           # AJAX handlers
│   └── post-types/     # Custom post types
├── templates/          # Template parts
│   ├── blocks/         # ACF flexible content blocks
│   ├── components/     # Reusable components
│   └── parts/          # Header, footer, etc.
├── acf-json/           # ACF field definitions (version controlled)
├── dist/               # Production build output (generated)
└── vite.config.js      # Vite configuration
```

## 🎯 Key Features

### ACF Block Auto-Generation
The theme automatically generates PHP templates when you create ACF flexible content layouts. No manual file creation needed!

### Performance Optimizations
- Critical CSS injection
- Lazy loading images
- Modern image formats (WebP/AVIF)
- Service Worker caching
- Core Web Vitals monitoring
- Optimized bundle splitting

### Vite Integration
- ⚡ Instant HMR in development
- 📦 Optimized production builds
- 🔄 Automatic asset versioning
- 📊 Bundle size analysis

## 📖 Documentation

Detailed documentation is available in the repository:
- [ACF Block Generator](ACF-BLOCK-GENERATOR.md)
- [Deployment Guide](DEPLOYMENT-GUIDE.md)
- [Performance Status](PERFORMANCE-STATUS.md)
- [Service Worker Guide](SERVICE-WORKER-GUIDE.md)

## 🔧 Configuration

### Performance Settings
Edit `performance-config.php` to enable/disable performance features:
```php
define('RESPLAST_CRITICAL_CSS', true);
define('RESPLAST_LAZY_LOADING', true);
define('RESPLAST_MODERN_IMAGES', true);
```

### Tailwind Configuration
Customize `tailwind.config.js` for your design system.

### Vite Configuration
Modify `vite.config.js` for build customization.

## 🚀 Deployment

```bash
# One-command production deploy
npm run build:production

# Commit and push
git add .
git commit -m "Deploy v$(node -p 'require(\"./package.json\").version')"
git push
```

## 📊 Performance Budgets

- **CSS**: 300KB target (current: ~47KB ✅)
- **JS**: 350KB target (current: ~70KB ✅)
- **LCP**: <2.5s target
- **INP**: <200ms target
- **CLS**: <0.1 target

## 🤝 Contributing

1. Fork the repository
2. Create your feature branch (`git checkout -b feature/amazing-feature`)
3. Commit your changes (`git commit -m 'Add amazing feature'`)
4. Push to the branch (`git push origin feature/amazing-feature`)
5. Open a Pull Request

## 📝 License

This project is licensed under the ISC License.

## 👤 Author

**Chetan Dhargalkar**
- GitHub: [@chetan3460](https://github.com/chetan3460)

## 🙏 Acknowledgments

- Built with modern web standards (2025)
- Optimized for Core Web Vitals
- Follows WordPress coding standards
