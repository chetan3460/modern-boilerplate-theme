# ESG/CSR Block Slider Implementation Guide

## Overview
The ESG/CSR block now supports a slider with two distinct layout types:
1. **Quote Layout** - Features a testimonial/quote with author info and badge image
2. **Certification Badge Layout** - Features certification badge with description and call-to-action

## ACF Field Structure

You need to update the `esg_csr_block` flexible content layout with the following field structure:

### Field Group: ESG CSR Block

**Location Rule:** Show this field group for flexible content layouts

#### Fields:

1. **hide_block** (True/False)
   - Field Name: `hide_block`
   - Field Type: True / False
   - Default: No

2. **title** (Text)
   - Field Name: `title`
   - Field Type: Text
   - Instructions: Main section heading

3. **description** (WYSIWYG Editor)
   - Field Name: `description`
   - Field Type: WYSIWYG Editor
   - Instructions: Section description

4. **esg_items** (Repeater)
   - Field Name: `esg_items`
   - Field Type: Repeater
   - Button Label: Add ESG Item
   - Min: 0
   - Max: Leave empty for unlimited
   - Layout: Block
   
   **Sub Fields:**
   
   a. **layout_type** (Select)
      - Field Name: `layout_type`
      - Field Type: Select
      - Choices:
        ```
        quote : Quote Layout
        certification : Certification Badge Layout
        ```
      - Default Value: `quote`
      - Allow Null: No
      - Required: Yes
   
   b. **quote_content** (WYSIWYG Editor)
      - Field Name: `quote_content`
      - Field Type: WYSIWYG Editor
      - Instructions: The quote text
      - Conditional Logic: Show if layout_type == quote
   
   c. **quote_name** (Text)
      - Field Name: `quote_name`
      - Field Type: Text
      - Instructions: Author name
      - Conditional Logic: Show if layout_type == quote
   
   d. **quote_designation** (Text)
      - Field Name: `quote_designation`
      - Field Type: Text
      - Instructions: Author designation/role
      - Conditional Logic: Show if layout_type == quote
   
   e. **badge_title** (Text)
      - Field Name: `badge_title`
      - Field Type: Text
      - Instructions: Title for certification badge
      - Conditional Logic: Show if layout_type == certification
   
   f. **badge_description** (WYSIWYG Editor)
      - Field Name: `badge_description`
      - Field Type: WYSIWYG Editor
      - Instructions: Description for certification badge
      - Conditional Logic: Show if layout_type == certification
   
   g. **badge_image** (Image)
      - Field Name: `badge_image`
      - Field Type: Image
      - Instructions: Badge/certification image (for both layouts)
      - Return Format: Array
      - Preview Size: Medium
      - Required: No
   
   h. **quote_link** (Link)
      - Field Name: `quote_link`
      - Field Type: Link
      - Instructions: Download link for report/document (e.g., PDF)
      - Return Format: Array
      - Conditional Logic: Show if layout_type == quote
   
   i. **badge_link** (Link)
      - Field Name: `badge_link`
      - Field Type: Link
      - Instructions: Call-to-action link (e.g., "Read More")
      - Return Format: Array
      - Conditional Logic: Show if layout_type == certification

## Implementation Steps

### Step 1: Update ACF Field Group

1. Go to **Custom Fields** in WordPress admin
2. Find the field group that contains `esg_csr_block` layout
3. Edit the `esg_csr_block` layout
4. Remove old fields: `quote_content`, `quote_name`, `quote_designation`, `quote_image`
5. Add new repeater field `esg_items` with all sub-fields as described above
6. Save the field group

### Step 2: Build Assets

After updating the template files, build the assets:

```bash
cd wp-content/themes/resplast-theme
npm run build
```

### Step 3: Test the Slider

1. Edit a page that uses the ESG/CSR block
2. Add multiple items to the `esg_items` repeater
3. Try both layout types
4. Preview the page to see the slider in action

## Usage Examples

### Example 1: Quote Layout

```
Layout Type: Quote Layout
Quote Content: "Lorem ipsum dolor sit amet, consectetur adipiscing elit..."
Quote Name: Rupen Choksi
Quote Designation: Managing Director
Badge Image: [Upload EcoVadis certification badge]
Quote Link:
  - Link Text: RPL GRI Report
  - URL: /wp-content/uploads/2024/report.pdf
  - Open in new tab: No (will download)
```

### Example 2: Certification Badge Layout

```
Layout Type: Certification Badge Layout
Badge Title: We are proudly part of the UN Global Compact Network, India
Badge Description: Through this partnership, we align our goals with the UN's Sustainable Development Goals (SDGs), reinforcing our vision for a more inclusive and resilient future.
Badge Image: [Upload UN Global Compact badge]
Badge Link: 
  - Link Text: Read More
  - URL: https://example.com/un-global-compact
  - Open in new tab: Yes
```

## Slider Features

- **Auto-play**: Enabled when multiple slides exist (5-second delay)
- **Navigation**: Previous/Next buttons with pagination (X/Y format)
- **Responsive**: Fully responsive on all devices
- **Lazy Loading**: Images are lazy-loaded for performance
- **Hover Pause**: Auto-play pauses on mouse hover
- **Interaction Stop**: Auto-play stops after user interaction

## Styling Notes

- Background color: `#EEF7E7` (light green)
- Brand red: `#DA000E`
- Rounded corners: 32px
- Navigation buttons: Circular with red border, hover fills with red
- Typography follows existing theme styles

## Browser Support

The slider uses Swiper.js which is already integrated in the theme and supports:
- Modern browsers (Chrome, Firefox, Safari, Edge)
- Mobile browsers (iOS Safari, Chrome Mobile)
- IE11+ (with polyfills)

## Troubleshooting

### Slider not appearing
- Check if `esg_items` repeater has at least one item
- Verify ACF field names match exactly
- Rebuild assets with `npm run build`
- Clear WordPress cache

### Navigation not showing
- Navigation automatically hides if there's only 1 slide
- Check browser console for JavaScript errors

### Images not loading
- Verify image IDs are valid
- Check if `resplast_optimized_image()` function exists
- Test with standard `<img>` tag as fallback
