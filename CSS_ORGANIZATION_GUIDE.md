# Manual CSS Organization Guide for Resplast Theme

## 🎯 **Goal**
Organize your current 604-line `style.css` into smaller, manageable files for better maintenance and performance.

## 📁 **Suggested Structure**

```
assets/css/
├── style.css                    # Main import file (keep minimal)
├── config/
│   ├── variables.css           # All CSS custom properties
│   └── theme-config.css        # Tailwind @theme configuration
├── base/
│   ├── global.css              # Body, containers, utilities
│   └── utilities.css           # Color utility classes (.text-black, etc.)
├── components/
│   ├── scroll-to-top.css       # ScrollToTop component
│   ├── about-page.css          # About page specific styles
│   └── cards.css               # Card components
├── pages/
│   ├── home.css                # Homepage styles (already separate)
│   ├── news-detail.css         # News detail page (already separate)  
│   └── milestones-timeline.css # Timeline page (already separate)
└── custom/                     # Your existing structure (keep as-is)
    └── [all existing files...]
```

## 📝 **Step-by-Step Manual Process**

### **Step 1: Create Directories**
```bash
mkdir -p assets/css/config assets/css/base assets/css/components assets/css/pages
```

### **Step 2: Extract Variables (Optional but Recommended)**
**Create:** `assets/css/config/variables.css`

Extract lines 58-129 from your current `style.css`:
```css
/* All the CSS custom properties from @theme { } */
:root {
  --breakpoint-xs: 540px;
  --breakpoint-sm: 640px;
  /* ... all your color variables ... */
  --color-primary: #da000e;
  --color-brand-red: #da000e;
  /* ... rest of variables ... */
}
```

### **Step 3: Extract Tailwind Theme Config**
**Create:** `assets/css/config/theme-config.css`

Move the entire `@theme { }` block from your `style.css`:
```css
@theme {
  /* Custom Breakpoints */
  --breakpoint-xs: 540px;
  /* ... all @theme content ... */
}
```

### **Step 4: Extract Global Styles**
**Create:** `assets/css/base/global.css`

Extract lines 130-178 from your `style.css`:
```css
* {
  scrollbar-width: thin;
}

body {
  @apply font-instrument text-grey-2 overflow-x-hidden;
}

/* Container configurations */
@utility container {
  padding-inline: 12px;
  margin-inline: auto;
}

/* Form styles @layer components */
/* Section heading styles */
/* Breadcrumb styles */
```

### **Step 5: Extract Utility Classes**
**Create:** `assets/css/base/utilities.css`

Extract lines 179-350 from your `style.css` (all the color utility classes):
```css
.text-black {
  color: var(--color-black);
}

.bg-black {
  background-color: var(--color-black);
}

/* ... all color utilities ... */
.text-grey-1, .bg-grey-1, etc.
```

### **Step 6: Extract Components**
**Create:** `assets/css/components/scroll-to-top.css`

Extract lines 488-604 from your `style.css`:
```css
.scrollToTop {
  display: none !important;
  /* ... all scrollToTop styles and animations ... */
}

@keyframes wave-front { /* ... */ }
@keyframes wave-back { /* ... */ }
```

**Create:** `assets/css/components/about-page.css`

Extract about page specific styles (lines 426-486):
```css
.page-template-page-about {
  .stats-block {
    /* ... */
  }
  .timeline-ruler {
    /* ... */
  }
}
```

### **Step 7: Update Main style.css**
Replace your current `style.css` with organized imports:

```css
/* ==========================================================================
 * MAIN STYLESHEET - ORGANIZED
 * ========================================================================== */

/* Essential Swiper CSS */
@import 'swiper/css';
@import 'swiper/css/pagination';
@import 'swiper/css/autoplay';
@import 'swiper/css/navigation';
@import 'swiper/css/effect-fade';
@import 'swiper/css/grid';

/* Configuration */
@import './config/variables.css';       /* If you created this */
@import './config/theme-config.css';    /* If you created this */

/* Base Styles */
@import './base/global.css';            /* If you created this */
@import './base/utilities.css';         /* If you created this */

/* Components */
@import './components/scroll-to-top.css';  /* If you created this */
@import './components/about-page.css';     /* If you created this */

/* Your Existing Structure (Keep unchanged) */
@import './custom/_fonts.css';
@import './custom/_general.css';
@import './custom/_buttons.css';
@import './custom/structure/_topnav.css';
@import './custom/structure/_footer.css';
@import './custom/pages/_helper.css';
@import './custom/pages/_hero.css';
@import './custom/pages/_countdown.css';
@import './custom/pages/_contact.css';
@import './custom/blocks/homeTabBlock.css';
@import './custom/blocks/distribution-map.css';
@import './custom/blocks/accordion_block.css';
@import './team-members-block.css';
@import './custom/blocks/certificate_block.css';
@import './custom/plugins/_swiper-slider.css';
@import './custom/plugins/_datepicker.css';

/* Pages (already separate) */
@import './pages/news-detail.css';
@import './pages/milestones-timeline.css';

/* Tailwind */
@config "../../tailwind.config.js";
@import 'tailwindcss';
@custom-variant dark (&:where(.dark, .dark *));

/* Any remaining styles that don't fit above */
.swiper-btn-prev-pagination.swiper-button-disabled,
.swiper-btn-next-pagination.swiper-button-disabled {
  opacity: 0.35;
  cursor: not-allowed;
  pointer-events: none;
  transform: none !important;
}
```

## ⚠️ **Important Notes**

1. **Do this gradually** - Start with one file at a time
2. **Test after each step** - Run `npm run build` to ensure no errors
3. **Keep backups** - Copy your original `style.css` before starting
4. **Home.css stays separate** - Don't move it to pages/ folder (performance optimization)
5. **Watch for Tailwind classes** - Only use `@apply` in files imported by main `style.css`

## 🔄 **Migration Strategy**

### **Option A: Minimal (Start Here)**
1. Only create `config/variables.css` 
2. Extract just the color variables
3. Update `style.css` to import it

### **Option B: Moderate**
1. Create `config/` and `base/` folders
2. Extract variables and utilities
3. Keep everything else in main `style.css`

### **Option C: Complete**
1. Create all suggested folders
2. Extract everything as outlined above
3. Clean, organized structure

## 🧪 **Testing Each Step**

After each file creation:
```bash
npm run build
# Check for errors
# Test your website
```

## 📊 **Benefits You'll Get**

1. **🔍 Easy to find**: "Where are my color variables?" → `config/variables.css`
2. **🚀 Faster development**: Smaller files load faster in editor
3. **🔧 Easy maintenance**: Change colors in one place
4. **👥 Team friendly**: Clear structure for other developers
5. **📈 Scalable**: Easy to add new components

## 🎯 **Start Small**

I recommend starting with just extracting the variables file first. It's the biggest win with minimal risk.

Would you like me to help you with any specific step?