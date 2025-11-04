# Quick Extraction Reference

## 📍 **Exact Line Numbers in Your Current style.css**

### **Variables & Theme Config**
- **@theme block**: Lines 58-129 
- **All CSS custom properties**: Inside @theme block

### **Global Styles**  
- **Global reset & body**: Lines 130-178
- **Container utilities**: Lines 150-157
- **Form styles**: Lines 163-177

### **Color Utilities**
- **Color utility classes**: Lines 187-350
  - `.text-black` starts at line 187
  - `.text-charcoal` starts around line 200
  - All `.text-grey-*`, `.bg-grey-*` classes

### **Component Styles**
- **About page styles**: Lines 426-486
  - `.page-template-page-about` and all nested styles
- **ScrollToTop component**: Lines 490-604
  - `.scrollToTop` and all animations
  - `@keyframes wave-front` and `@keyframes wave-back`

## 🎯 **Easiest First Step**

Start with extracting just the **@theme block (lines 58-129)**:

1. Create `assets/css/config/theme-config.css`
2. Copy lines 58-129 from `style.css`
3. Delete those lines from `style.css`
4. Add `@import './config/theme-config.css';` to the top of `style.css`
5. Test with `npm run build`

This single change will:
- ✅ Move 72 lines out of main file
- ✅ Organize all your color variables
- ✅ Make colors easy to find and update
- ✅ Keep everything working exactly the same

## 📝 **Copy-Paste Commands**

### **View specific lines:**
```bash
# See the @theme block
sed -n '58,129p' assets/css/style.css

# See color utilities  
sed -n '187,350p' assets/css/style.css

# See scrollToTop styles
sed -n '490,604p' assets/css/style.css
```

### **Quick backup:**
```bash
cp assets/css/style.css assets/css/style.css.backup
```

This way you can manually extract exactly what you want, when you want, and test each step! 🚀