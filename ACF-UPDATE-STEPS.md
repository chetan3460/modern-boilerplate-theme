# ACF Fields Update - Step by Step Guide

## 🎯 Goal
Add tab functionality to your existing Gallery Block without breaking existing galleries.

---

## 📋 Step-by-Step Instructions

### **Step 1: Access ACF Field Groups**
1. Go to WordPress Admin: `http://localhost/resplast/wp-admin`
2. Navigate to **Custom Fields** → **Field Groups**
3. Find the field group containing your gallery block (probably called "Page Blocks" or similar)
4. Click **Edit** on that field group

### **Step 2: Locate Gallery Block Layout**
1. Look for your "Gallery Block" layout in the flexible content
2. It should have these existing fields:
   - Hide Block (true_false)
   - Title (text)
   - Description (wysiwyg)  
   - Gallery Items (repeater)

### **Step 3: Add New Fields to Gallery Block**

**🔸 Add "Show Tabs" Field:**
1. Click **"Add Field"** in your Gallery Block layout
2. Configure as follows:
   ```
   Field Label: Show Tabs
   Field Name: show_tabs
   Field Type: True / False
   Instructions: Enable tabbed gallery with categories
   Default Value: 0 (Off)
   UI: Yes
   UI On Text: Enable Tabs
   UI Off Text: Single Gallery
   ```

**🔸 Add "Gallery Categories" Repeater:**
1. Click **"Add Field"** again
2. Configure the main repeater:
   ```
   Field Label: Gallery Categories
   Field Name: gallery_categories
   Field Type: Repeater
   Instructions: Add categories/tabs for your gallery (e.g., ESG, CSR, Innovation)
   Button Label: Add Category
   Min: 0
   Max: (leave empty)
   ```

### **Step 4: Add Sub-fields to Gallery Categories**

Now add 3 sub-fields to the "Gallery Categories" repeater:

**🔸 Sub-field 1 - Category Name:**
1. Click **"Add Sub Field"** in Gallery Categories
2. Configure:
   ```
   Field Label: Category Name
   Field Name: category_name
   Field Type: Text
   Instructions: Enter the tab name (e.g., ESG, CSR, Innovation, Community)
   Placeholder: e.g., ESG
   Required: Yes
   ```

**🔸 Sub-field 2 - Category Slug:**
1. Click **"Add Sub Field"** again
2. Configure:
   ```
   Field Label: Category Slug  
   Field Name: category_slug
   Field Type: Text
   Instructions: Auto-generated URL-friendly version (leave empty to auto-generate)
   Placeholder: e.g., esg
   ```

**🔸 Sub-field 3 - Gallery Items (nested repeater):**
1. Click **"Add Sub Field"** one more time
2. Configure:
   ```
   Field Label: Gallery Items
   Field Name: gallery_items
   Field Type: Repeater
   Instructions: Add images for this category
   Button Label: Add Image
   Min: 0
   Max: (leave empty)
   ```

### **Step 5: Add Sub-fields to Nested Gallery Items**

Now add 3 sub-fields to the nested "Gallery Items" repeater:

**🔸 Gallery Image:**
1. Click **"Add Sub Field"** in the nested Gallery Items
2. Configure:
   ```
   Field Label: Gallery Image
   Field Name: gallery_image  
   Field Type: Image
   Instructions: Upload gallery image
   Return Format: Array
   Preview Size: Medium
   Library: All
   Required: Yes
   ```

**🔸 Image Title:**
1. Click **"Add Sub Field"** again
2. Configure:
   ```
   Field Label: Title
   Field Name: title
   Field Type: Text
   Instructions: Image caption/title
   Placeholder: e.g., Green Manufacturing
   ```

**🔸 Image Year:**
1. Click **"Add Sub Field"** one final time  
2. Configure:
   ```
   Field Label: Year
   Field Name: year
   Field Type: Text
   Instructions: Year photo was taken
   Placeholder: e.g., 2024
   ```

---

## 🎯 Final Field Structure

Your Gallery Block should now have this structure:

```
Gallery Block Layout:
├── Hide Block (true_false) [existing]
├── Title (text) [existing]
├── Description (wysiwyg) [existing]
├── Gallery Items (repeater) [existing - keep for backward compatibility]
├── Show Tabs (true_false) [NEW]
└── Gallery Categories (repeater) [NEW]
    ├── Category Name (text)
    ├── Category Slug (text)  
    └── Gallery Items (repeater)
        ├── Gallery Image (image)
        ├── Title (text)
        └── Year (text)
```

---

## 💾 Step 6: Save & Test

1. **Click "Update"** to save your field group
2. **Go to any page** with a gallery block to test
3. **Edit the page** and look for the new "Show Tabs" toggle
4. **Enable tabs** and add categories like "ESG", "CSR", "Innovation"
5. **Add images** to each category

---

## 🎉 You're Done!

The template is already updated and ready to use these new fields. When "Show Tabs" is enabled, you'll get the beautiful sliding tab interface. When disabled, it works exactly like before!

## 🆘 Need Help?

If you get stuck on any step, the field names must match exactly:
- `show_tabs` 
- `gallery_categories`
- `category_name`
- `category_slug`
- `gallery_items` (nested)
- `gallery_image`
- `title`
- `year`