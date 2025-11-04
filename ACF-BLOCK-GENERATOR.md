# ACF Block Template Generator

This feature automatically generates PHP template files when you create new ACF flexible content layouts.

## How It Works

### 1. Create ACF Layout
When you create a new layout in your ACF flexible content field (like "Home Panels" or "About Panels"), the system will automatically generate a PHP template file.

### 2. Automatic File Generation
- **Files are created** when you save the ACF field group
- **No overwriting** - existing files are preserved
- **Default location** - all blocks created in `templates/blocks/`

### 3. Smart Global Detection
Blocks are automatically placed in the correct location:

**Global blocks** (`templates/blocks/global/`) - for blocks matching these patterns:
- `global_*` - e.g. `global_cta`
- `cta_*` - e.g. `cta_banner` 
- `contact_*` - e.g. `contact_form`
- `newsletter_*` - e.g. `newsletter_signup`
- `testimonial*` - e.g. `testimonial_block`
- `get_in_touch*` - e.g. `get_in_touch_block`
- `certificate_block`
- `footer_*`, `header_*`, `banner_*`
- `call_to_action`, `social_*`, `form_*`
- `subscribe*`, `download_*`, `popup_*`, `modal_*`

**Regular blocks** (`templates/blocks/`) - all other blocks

## Example Usage

### Step 1: Create ACF Layout
1. Go to **Custom Fields → Field Groups**
2. Edit your flexible content field (e.g., "Home Panels")
3. **Add New Layout**:
   - **Name**: `certificate_block`
   - **Label**: `Certificate Block`

### Step 2: Add Fields
Add these sub-fields to your layout:
- `hide_block` (True/False)
- `title` (Text)
- `description` (WYSIWYG) 
- `certificate_image` (Image)
- `certificates_list` (Repeater)
  - `certificate_name` (Text)
  - `certificate_file` (File)
  - `certificate_date` (Date Picker)

### Step 3: Save Field Group
When you click **Update**, the system will automatically create:
`templates/blocks/global/certificate_block.php` (automatically detected as global)

### Step 4: Use Your Block
The generated template includes:
- ✅ All your ACF fields as PHP variables
- ✅ Proper escaping and security
- ✅ Hide block functionality
- ✅ Responsive Tailwind CSS classes
- ✅ Image optimization integration
- ✅ Documentation comments

## Generated Template Features

### Field Type Support
- **Text/Textarea**: Automatically detects titles vs content
- **WYSIWYG**: Uses `wp_kses_post()` for safe HTML
- **Images**: Integrates with your `resplast_optimized_image()` function
- **Links**: Generates proper button markup
- **Repeaters**: Creates grid layouts with sub-fields
- **All others**: Safe fallback with proper escaping

### Smart Field Detection
- Fields with "title" or "heading" in name → `<h2>` tags
- Image fields → Optimized image output
- Link fields → Button styling
- Repeater fields → Grid layouts

### Security & Best Practices
- All output is properly escaped
- Uses `wp_kses_post()` for rich content
- Includes hide block functionality
- Responsive design with Tailwind CSS

## Customization

### Add Custom Global Patterns
Edit `inc/features/acf-block-generator.php` and add patterns to the `$global_patterns` array:

```php
$global_patterns = [
    'global_',
    'cta_',
    'your_custom_pattern_', // Add your patterns here
    'special_block_name',   // Or specific block names
];
```

### Modify Generated Code
The generator creates a starting template. You can:
1. **Edit the generated file** to customize styling and functionality
2. **The generator won't overwrite** existing files
3. **Use it as a base** and build upon it

## Notes

- ✅ **Safe**: Never overwrites existing files
- ✅ **Fast**: Templates ready immediately after saving ACF
- ✅ **Smart**: Automatically determines global vs page-specific
- ✅ **Secure**: All output properly escaped
- ✅ **Responsive**: Uses Tailwind CSS classes
- ✅ **Integrated**: Works with your existing theme functions

## Troubleshooting

### Template Not Generated?
1. Check if file already exists (won't overwrite)
2. Verify ACF layout has `sub_fields`
3. Check error logs for any issues

### Want Different Global Detection?
Edit the `$global_patterns` array in `inc/features/acf-block-generator.php` to add your own patterns

### Block in Wrong Location?
Blocks are auto-detected based on naming patterns. Either rename your block or modify the pattern list

### Need Different Styling?
The generated template is just a starting point - edit it to match your design!