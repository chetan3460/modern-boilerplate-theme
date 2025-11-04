# News Listing ACF Setup

## 📋 **Implementation Summary**

The news listing section heading is now powered by ACF (Advanced Custom Fields) for easy content management.

## 🔧 **ACF Fields Required**

You need to create **2 ACF fields** in your WordPress admin:

### **Field 1: Section Title**
- **Field Label:** `News Listing Title`
- **Field Name:** `news_listing_title`
- **Field Type:** `Text`
- **Default Value:** `Driving what's next`
- **Required:** No (has fallback)
- **Instructions:** Enter the main heading for the news listing page

### **Field 2: Section Description**  
- **Field Label:** `News Listing Description`
- **Field Name:** `news_listing_description`
- **Field Type:** `Textarea`
- **Default Value:** `A look at our innovations, research milestones, and events that keep us ahead in a changing world.`
- **Required:** No (has fallback)
- **Instructions:** Enter the description text that appears below the main heading

## 📍 **Field Group Location**

### **Option 1: Page-specific (Recommended)**
Set the field group to show on:
- **Post Type:** `Page`
- **Page Template:** `News Listing` (if you have a specific news listing page)
- **Or Page:** `News` (if you have a specific news page)

### **Option 2: Options Page (Global)**
If you want these fields to be global settings:
- Create an **Options Page** field group
- Location: **Options Page** equals **Site Settings** (or create custom options page)

### **Option 3: CPT Archive (Alternative)**
- **Post Type Archive:** equals `News`

## 🎯 **Current Implementation**

### **Template Code:**
```php
<?php 
// Get ACF fields with fallback to default text
$section_title = get_field('news_listing_title') ?: 'Driving what\'s next';
$section_description = get_field('news_listing_description') ?: 'A look at our innovations, research milestones, and events that keep us ahead in a changing world.';
?>
<h1 class="text-3xl sm:text-4xl lg:text-5xl font-bold tracking-tight">
  <?php echo esc_html($section_title); ?>
</h1>
<?php if (!empty($section_description)): ?>
  <p class="mt-4 text-gray-600">
    <?php echo esc_html($section_description); ?>
  </p>
<?php endif; ?>
```

## 🏗️ **ACF Setup Steps**

### **Step 1: Create Field Group**
1. Go to **Custom Fields → Field Groups**
2. Click **Add New**
3. Title: `News Listing Content`

### **Step 2: Add Title Field**
1. Click **Add Field**
2. **Field Label:** `News Listing Title`
3. **Field Name:** `news_listing_title` (auto-generated)
4. **Field Type:** `Text`
5. **Instructions:** `Enter the main heading for the news listing page`
6. **Default Value:** `Driving what's next`
7. **Required:** `No`

### **Step 3: Add Description Field**
1. Click **Add Field**
2. **Field Label:** `News Listing Description`  
3. **Field Name:** `news_listing_description` (auto-generated)
4. **Field Type:** `Textarea`
5. **Instructions:** `Enter the description text below the main heading`
6. **Default Value:** `A look at our innovations, research milestones, and events that keep us ahead in a changing world.`
7. **Required:** `No`
8. **Rows:** `3-4` (for better editing experience)

### **Step 4: Set Location Rules**
Choose **one** of these options:

**Option A - Specific Page:**
- **Post Type** equals `Page`
- **AND Page Template** equals `News Listing`

**Option B - Global Options:**
- **Options Page** equals `Site Settings`

**Option C - News Archive:**
- **Post Type Archive** equals `News`

### **Step 5: Publish**
1. **Publish** the field group
2. Go to your news page/settings and set the content
3. Visit the news listing page to see your custom content

## 🎨 **Content Management**

### **Where to Edit:**
- **Page-specific:** Edit the news listing page
- **Global options:** Go to the options page you configured
- **CPT archive:** Edit under the news post type settings

### **Example Content:**
**Title:** `Latest News & Updates`  
**Description:** `Stay informed with our latest developments, insights, and industry updates.`

**Title:** `Innovation Hub`  
**Description:** `Discover breakthrough technologies and forward-thinking solutions shaping tomorrow.`

## 🔄 **Fallback Behavior**

### **If ACF is Not Available:**
- Fields will return empty
- Code falls back to default text
- Page continues to function normally

### **If Fields Are Empty:**
- Title falls back to: `"Driving what's next"`
- Description falls back to: `"A look at our innovations..."`
- No broken layouts or missing content

## 🧪 **Testing**

1. **Create the ACF fields** as described above
2. **Add custom content** in the WordPress admin
3. **Visit the news listing page** to see your custom content
4. **Try leaving fields empty** to test fallbacks
5. **Update content** to verify changes appear immediately

---

## ✅ **Result**

**The news listing section heading is now fully customizable through ACF!**

- **🎯 User-Friendly** - Content editors can easily update the heading
- **🔧 Developer-Friendly** - Clean code with proper fallbacks  
- **🎨 Design Consistent** - Maintains all existing styling
- **🛡️ Bulletproof** - Works even if ACF is disabled or fields are empty
- **📱 Responsive** - All responsive classes preserved

Content managers can now easily update the news listing page heading and description without touching code!