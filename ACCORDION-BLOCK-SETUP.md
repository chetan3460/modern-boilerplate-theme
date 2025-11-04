# Accordion Block Setup with Alpine.js

This document provides guidance for using the Accordion Block with Alpine.js in the Resplast theme.

## Overview

The Accordion Block is a flexible, reusable component that displays content in an expandable/collapsible accordion format. It uses Alpine.js for smooth, reactive state management without requiring additional JavaScript coding.

## Setup Completed

✅ **ACF Field Group Created**: `group_accordion_block.json`
✅ **Block Template Created**: `templates/blocks/accordion_block.php`
✅ **Alpine.js Integration**: Pre-configured in `assets/js/main.js`

## Features

- **Single/Multiple Mode**: Choose between allowing one or multiple items open at a time
- **Smooth Animations**: Optional smooth transitions with Alpine.js x-transition directives
- **Customizable Styling**: Multiple background color options (light-blue, white, grey)
- **Accessible**: Full ARIA support with proper role attributes
- **Responsive**: Mobile-friendly design with Tailwind CSS

## ACF Field Configuration

### Field Structure

The ACF field group includes:

```
Accordion Block (Field Group)
├── Hide Block (true_false)
├── Title (text)
├── Subtitle (textarea)
├── Accordion Items (repeater)
│   ├── Item Title (text)
│   └── Item Content (wysiwyg)
└── Accordion Settings (group)
    ├── Allow Multiple Open (true_false)
    ├── Animated (true_false)
    └── Background Color (select)
```

### Settings Guide

**Allow Multiple Open**
- `OFF` (Default): Only one accordion item can be open at a time
- `ON`: Multiple items can be open simultaneously

**Animated**
- `ON` (Default): Smooth transitions when opening/closing
- `OFF`: Instant show/hide without animation

**Background Color**
- `light-blue` (Default): Light blue background
- `white`: White background with border
- `grey`: Grey background

## Template Usage

### File Location
```
templates/blocks/accordion_block.php
```

### Rendering in Page Templates

To render accordion blocks within a page, use the standard ACF helper:

```php
<?php
// If accordion_items is part of a flexible content field
render_blocks('your_field_name');
?>
```

Or directly with the flexible content loop:

```php
<?php if (have_rows('your_flexible_field')): ?>
    <?php while (have_rows('your_flexible_field')): the_row();
        $layout = get_row_layout();
        if ($layout === 'accordion_block') {
            get_template_part('templates/blocks/accordion_block');
        }
    endwhile; ?>
<?php endif; ?>
```

## Alpine.js Implementation

### Component Structure

The accordion uses Alpine.js's `x-data` attribute to initialize the accordion state:

```html
<div 
    x-data="accordion({
        allowMultiple: true/false,
        animated: true/false
    })"
>
```

### State Management

**Component Data**

```javascript
{
    openItems: [],           // Array of open item indices
    allowMultiple: false,    // Allow multiple items open
    animated: true,          // Enable animations
}
```

### Methods

**toggleItem(index)**
```javascript
// Toggle specific accordion item
toggleItem(0);  // Toggles first item
```

Behavior:
- If `allowMultiple` is `true`: Adds/removes index from `openItems` array
- If `allowMultiple` is `false`: Closes all other items and toggles the clicked item

**closeAll()**
```javascript
// Close all accordion items
closeAll();
```

**openAll()**
```javascript
// Open all accordion items (only if allowMultiple is true)
openAll();
```

### Directives Used

- **`x-data`**: Initializes Alpine component with accordion state
- **`x-show`**: Shows/hides accordion panels based on `openItems` state
- **`x-transition`**: Smooth animations during transitions
- **`@click`**: Handles accordion button clicks
- **`:aria-expanded`**: Reactive ARIA attribute for accessibility
- **`:class`**: Dynamic class binding for icon rotation and button states

## Styling

### CSS Classes

The template uses Tailwind CSS for all styling:

**Container**
```css
.accordion-wrapper      /* Main accordion container */
.accordion-item        /* Individual accordion item */
```

**Button States**
```css
.bg-opacity-100        /* Fully opaque when open */
.bg-opacity-90         /* Slightly transparent when closed */
.hover:bg-opacity-95   /* Hover state */
```

**Transitions**
```css
.transition-transform  /* Icon rotation transition */
.duration-300         /* 300ms animation duration */
.rotate-180           /* Icon rotation */
```

**Prose Styling**
```css
.prose prose-p:text-grey-7        /* Paragraph color */
.prose-ul:text-grey-7             /* List color */
.prose-li:marker:text-primary     /* List marker color */
```

### Background Color Options

```php
// In template
$bg_color_class = [
    'light-blue' => 'bg-light-blue',
    'white' => 'bg-white border border-grey-2',
    'grey' => 'bg-grey-1'
];
```

## Adding to Flexible Content

To add the accordion block as a flexible content layout:

1. **In WordPress Admin**:
   - Go to Custom Fields → Field Groups
   - Edit the flexible content field (e.g., "Home Panels", "Page Panels")
   - Click "Add Layout"
   - Name: `accordion_block`
   - Label: "Accordion Block"
   - Save field group

2. **ACF JSON Sync** (Automatic):
   - ACF will automatically create the JSON mapping
   - The field group `group_accordion_block.json` will be associated

3. **Verify Template**:
   - Ensure `templates/blocks/accordion_block.php` exists
   - Template is auto-detected by ACF when layout name matches

## JavaScript Events

The template includes inline Alpine.js initialization:

```javascript
document.addEventListener('alpine:init', () => {
    Alpine.data('accordion', (config = {}) => ({
        // Component definition
    }));
});
```

This ensures Alpine components are registered before any markup is processed.

## Accessibility

The accordion includes full ARIA support:

```html
<!-- Button -->
<button
    :aria-expanded="openItems.includes(index)"
    aria-controls="panel-id"
>

<!-- Panel -->
<div
    id="panel-id"
    role="region"
    :aria-labelledby="'button-id'"
>
```

**Screen Reader Support**
- Buttons announce expanded/collapsed state
- Panels are marked as regions with proper labels
- Icons have `aria-hidden="true"` to prevent redundant announcements

## Performance Considerations

- **Bundle Size**: Alpine.js adds ~14KB (minified)
- **Runtime**: All state managed reactively in memory
- **DOM Updates**: Only affected elements re-render
- **Animations**: GPU-accelerated via CSS transitions

## Browser Support

- Chrome/Edge: ✅ Full support
- Firefox: ✅ Full support
- Safari: ✅ Full support
- IE11: ⚠️ Not supported (Alpine.js requires ES2015+)

## Common Use Cases

### Legal/Privacy Pages
```
Title: "Legal & Privacy Policy"
Items:
- Disclaimer
- Cookies
- No Liability
- Copyright and Trademark
- Jurisdiction
```

### FAQ Section
```
Title: "Frequently Asked Questions"
Settings: Allow Multiple = ON
Items: Multiple Q&A pairs
```

### Product Features
```
Title: "Product Features"
Items: Feature descriptions
```

### Service Details
```
Title: "Service Overview"
Items: Service categories and descriptions
```

## Troubleshooting

### Accordion not working
1. Verify Alpine.js is loaded: Check browser console for `Alpine` object
2. Check ACF JSON file is in `acf-json/` directory
3. Ensure flexible content layout name is exactly `accordion_block`
4. Verify template file exists at `templates/blocks/accordion_block.php`

### Animations not smooth
1. Check if "Animated" setting is enabled
2. Verify CSS transitions are not being overridden
3. Check browser for CSS conflicts in DevTools

### Styling issues
1. Verify Tailwind CSS is properly configured
2. Check color tokens (grey-1, grey-7, primary, etc.) exist in `tailwind.config.js`
3. Check for CSS class conflicts in theme

### ARIA not working
1. Verify `aria-expanded` is bound correctly with `:aria-expanded`
2. Check panel IDs are unique
3. Verify `aria-labelledby` points to correct button ID

## Development Tips

### Debugging Alpine State
```javascript
// In browser console
document.querySelector('[data-component="AccordionBlock"]').__x.$data
```

### Custom Styling
Extend or override classes in `assets/css/components/accordion.css`:

```css
.accordion-item {
    @apply mb-3 last:mb-0;
    /* Custom styles */
}
```

### Adding Custom Methods
Extend the Alpine component in your theme's JavaScript:

```javascript
Alpine.data('accordion', (config) => ({
    // ... existing methods
    customMethod() {
        // Your code
    }
}));
```

## Related Files

- `acf-json/group_accordion_block.json` - ACF field group definition
- `templates/blocks/accordion_block.php` - Component template
- `assets/js/main.js` - Alpine.js initialization
- `tailwind.config.js` - Tailwind CSS color configuration

## Support

For issues or feature requests related to the accordion block:
1. Check this documentation
2. Review the template code and comments
3. Check Alpine.js documentation: https://alpinejs.dev
4. Review ACF documentation: https://www.advancedcustomfields.com/resources/
