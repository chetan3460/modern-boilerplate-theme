# Careers Page Blocks Troubleshooting Guide

## Issue: Blocks not rendering on careers page

Follow these steps to diagnose and fix the issue:

## Step 1: Check ACF Field Group

1. Go to WordPress Admin → **Custom Fields → Field Groups**
2. Look for "**Careers Panels**" field group
3. **If missing**: Import the `acf-json/careers_panels.json` file
4. **If exists**: Make sure it's **Active** and the location rule shows "Page Template is equal to page-careers.php"

## Step 2: Check Page Template

1. Go to **Pages → Careers** (or your careers page)
2. In the **Page Attributes** box, make sure **Template** is set to "**Careers**"
3. If you don't see this option, make sure the page-careers.php file exists in your theme

## Step 3: Add Content to Careers Panels

1. Edit your careers page in WordPress admin
2. Scroll down to find "**Careers Panels**" section
3. Click "**+ Add Block**" to add your first block
4. Choose from:
   - Accordion Block
   - Gallery Block  
   - Get In Touch Block
   - Testimonials Block (Hear from our people)
5. Fill in the required fields for each block
6. **Save/Update** the page

## Step 4: Check Debug Output

The page now includes debug information:
- **Yellow box**: "No careers_panels data found" = You need to add blocks
- **Green box**: "Found X career panel(s)" = Blocks exist, should render

## Step 5: Enable WordPress Debug (Optional)

Add to your `wp-config.php`:
```php
define('WP_DEBUG', true);
define('WP_DEBUG_LOG', true);
define('WP_DEBUG_DISPLAY', true);
```

This will show additional debug info in HTML comments and error messages.

## Step 6: Verify Template Files

All these files should exist:
- ✅ `templates/blocks/accordion_block.php`
- ✅ `templates/blocks/gallery_block.php` 
- ✅ `templates/blocks/testimonials_block.php`
- ✅ `templates/blocks/global/get_in_touch_block.php`

## Step 7: Common Solutions

### Solution 1: Re-import ACF Field Group
1. Delete existing "Careers Panels" field group
2. Re-import `acf-json/careers_panels.json`
3. Refresh careers page

### Solution 2: Check Page Template Assignment
1. Make sure you're editing the right page
2. Verify page template is set to "Careers"
3. Save the page again

### Solution 3: Add Test Content
1. Add at least one block to careers_panels
2. Fill in required fields
3. Save and view page

### Solution 4: Clear Cache
1. Clear any caching plugins
2. Hard refresh browser (Cmd+Shift+R)
3. Check page again

## Expected Results

After fixing, you should see:
- Green debug message showing number of blocks found
- Each block rendering with its content
- No error messages or missing template warnings

## Still Not Working?

1. Check browser console for JavaScript errors
2. Check WordPress error logs
3. Verify ACF Pro plugin is active
4. Make sure all required fields are filled in each block