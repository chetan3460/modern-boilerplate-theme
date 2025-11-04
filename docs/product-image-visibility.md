# Product Image Visibility Control

## Overview
You can now control whether product images are displayed in product listings on a per-product basis or globally.

## Per-Product Control

### In WordPress Admin:
1. Edit any product
2. Scroll to the "Custom Product Attributes" section  
3. Find the "Show Product Image" toggle switch
4. Toggle **ON** (Show) or **OFF** (Hide)
5. Save the product

### Default Behavior:
- **Default**: Images are shown by default
- **New products**: Will show images unless specifically turned off
- **Existing products**: Will show images (backward compatible)

## Template Usage

### Basic Usage:
The templates automatically respect the image visibility setting.

### Advanced Usage:
You can override the setting programmatically:

```php
// Force hide all images
$show_product_image = should_show_product_image($product_id, false);

// Force show all images  
$show_product_image = should_show_product_image($product_id, true);

// Use individual product setting (default behavior)
$show_product_image = should_show_product_image($product_id);
```

## Layout Behavior

### List View:
- **With Image**: Image takes 1/3 width, content takes 2/3 width
- **Without Image**: Content takes full width

### Grid View:
- **With Image**: Image shows at top of card
- **Without Image**: Content starts immediately (no image section)

## Use Cases

1. **Products without images**: Hide image area for cleaner look
2. **Text-only products**: Focus attention on specifications  
3. **Placeholder images**: Hide generic placeholders
4. **Custom layouts**: Control image display per design needs

## Technical Details

- **ACF Field**: `show_product_image` (True/False)
- **Helper Function**: `should_show_product_image()`
- **Templates**: Both `card-list.php` and `card-grid.php` support this feature
- **Performance**: No impact when images are hidden (they're not loaded)