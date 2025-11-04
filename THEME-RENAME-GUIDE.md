# Theme Rename Script Guide

## 🎯 Purpose

This script automates the process of renaming the theme for reuse in other projects. It handles all the find-and-replace operations needed to rebrand the theme.

---

## 🚀 How to Use

### 1. Copy Theme Folder

First, copy the entire theme to a new location:

```bash
cp -r resplast-theme ../new-project-theme
cd ../new-project-theme
```

### 2. Run the Script

```bash
./rename-theme.sh
```

### 3. Follow the Prompts

The script will ask you:

**Example:**
```
Enter new theme name (lowercase, no spaces, e.g., 'acme'): acme
```

**It will show:**
```
New theme name will be:
  Lowercase: acme
  Capitalized: Acme
  Uppercase: ACME

Enter new text domain prefix (or press Enter to use 'acme'): 
```

**Then confirm:**
```
⚠️  This will replace text in ALL files in this directory.
Continue? (y/n): y
```

---

## 📋 What Gets Changed

### Automatically Replaced:

✅ **Function prefixes**
- `resplast_` → `acme_`
- `resplast_optimized_image()` → `acme_optimized_image()`

✅ **Text domains**
- `'resplast'` → `'acme'`
- Used for translations

✅ **Constants**
- `RESPLAST_` → `ACME_`
- `RESPLAST_CSS_BUDGET` → `ACME_CSS_BUDGET`

✅ **Theme names**
- `Resplast Theme` → `Acme Theme`
- In style.css header

✅ **Package name**
- `package.json` name field
- Version reset to 1.0.0

✅ **Service worker cache names**
- `resplast-v1.0.3` → `acme-v1.0.3`

✅ **PWA manifest**
- App name and short name updated

---

## 🔍 Files Modified

The script updates these file types:
- **PHP files** (`.php`) - Functions, constants, text domains
- **JavaScript** (`.js`) - Service worker, cache names
- **CSS files** (`.css`) - Comments, theme info
- **Markdown** (`.md`) - Documentation
- **JSON files** (`package.json`, `manifest.json`)

---

## 🛡️ Safety Features

### 1. Automatic Backup

Before making changes, creates backup:
```
../resplast-theme-backup-20251028-103000/
```

### 2. Confirmation Prompts

- Confirms before starting
- Asks about clearing ACF fields
- Shows summary before proceeding

### 3. Excludes Important Folders

Skips:
- `node_modules/` (dependencies)
- `vendor/` (PHP dependencies)
- `dist/` (built assets)

---

## 📊 What to Do After

### 1. Review Changes

Check the backup folder if needed:
```bash
ls ../resplast-theme-backup-*
```

### 2. Install Dependencies

```bash
npm install
```

### 3. Build Assets

```bash
npm run build
```

### 4. Rename Folder

```bash
cd ..
mv new-project-theme acme-theme
```

### 5. Activate in WordPress

- Upload to `wp-content/themes/`
- Go to Appearance → Themes
- Activate the new theme

---

## 🎯 Example Usage

```bash
# Copy theme
cp -r resplast-theme ../acme-theme
cd ../acme-theme

# Run script
./rename-theme.sh

# Follow prompts:
# Enter new theme name: acme
# Enter prefix: acme
# Continue? y
# Clear ACF? n

# After completion:
npm install
npm run build

# Test theme
```

---

## ⚠️ Optional: Clear ACF Fields

The script asks if you want to clear ACF JSON:

**Choose "y" if:**
- Starting a completely new project
- Don't need existing field definitions

**Choose "n" if:**
- Keeping same field structure
- Want to preserve ACF fields

---

## 🔧 What Gets Replaced (Technical)

| Old | New | Where |
|-----|-----|-------|
| `resplast_` | `acme_` | PHP function prefixes |
| `'resplast'` | `'acme'` | Text domains |
| `RESPLAST_` | `ACME_` | PHP constants |
| `Resplast` | `Acme` | Theme names |
| `resplast-theme` | `acme-theme` | Package name |
| `replast` | `acme` | T_PREFIX constant |

---

## 🐛 Troubleshooting

### Script Won't Run

**Error:** `Permission denied`

**Fix:**
```bash
chmod +x rename-theme.sh
```

---

### Sed Command Errors

**Error:** `sed: command not found`

**Fix:** The script uses macOS `sed`. On Linux, change:
```bash
sed -i '' "s/pattern/replacement/g"
# to
sed -i "s/pattern/replacement/g"
```

---

### Backup Failed

**Error:** `Cannot create backup`

**Fix:** Ensure you have write permissions in parent directory

---

## 📝 Manual Changes (If Needed)

After running the script, you might want to manually update:

1. **style.css** - Theme description, author, URL
2. **README.md** - Project-specific documentation
3. **Screenshots** - Replace theme screenshot
4. **Icons** - Update favicon/app icons

---

## 💾 Restore from Backup

If something goes wrong:

```bash
# Find backup
ls ../resplast-theme-backup-*

# Restore
cd ..
rm -rf new-project-theme
cp -r resplast-theme-backup-20251028-103000 new-project-theme
```

---

## ✅ Verification Checklist

After renaming, verify:

- [ ] `style.css` shows new theme name
- [ ] `package.json` has new name
- [ ] Functions use new prefix (`acme_`)
- [ ] Constants use new prefix (`ACME_`)
- [ ] `npm run build` completes successfully
- [ ] Theme activates in WordPress
- [ ] No PHP errors in debug log

---

## 🎉 Success!

If all checks pass, your theme is successfully renamed and ready to use!

**Next:** Customize the theme for your specific project needs.

---

**Script Location:** `rename-theme.sh`  
**Last Updated:** 2025-10-28
