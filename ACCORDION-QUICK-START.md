# Accordion Block - Quick Start Guide

## 5-Minute Setup

### Step 1: Add to Flexible Content (WordPress Admin)

1. Go to **Custom Fields → Field Groups**
2. Find your page's flexible content field (e.g., "Home Panels", "Legal Page Panels")
3. Click **Add Layout** inside the flexible content field
4. Configure:
   - **Name**: `accordion_block`
   - **Label**: `Accordion Block`
   - **Display**: `Block`
5. Click **Save Field Group**

### Step 2: Add Accordion Content (Page Editor)

1. Edit your page
2. Find the section with your flexible content field
3. Click **+ Add row** and select **Accordion Block**
4. Fill in:
   - **Title**: Your section title
   - **Subtitle**: Optional description
   - **Accordion Items**: Add items with title and content
   - **Settings**: Choose options below

### Step 3: Configure Settings

In the **Accordion Settings** section:

- **Allow Multiple Open**: Toggle if multiple items can be open simultaneously
- **Animated**: Toggle for smooth opening/closing animations
- **Background Color**: Choose from Light Blue, White, or Grey

### Step 4: Save

Click **Publish** or **Update** to save your page

## What You Get

✅ Fully functional accordion with Alpine.js
✅ Smooth animations (optional)
✅ Single or multiple open items
✅ Accessible (ARIA compliant)
✅ Mobile responsive
✅ 3 background color options

## Example: Legal & Privacy Page

```
Title: Legal & Privacy Policy

Accordion Items:
1. Disclaimer
   Content: Lorem ipsum...
   
2. Cookies
   Content: Lorem ipsum...
   
3. No Liability
   Content: Lorem ipsum...
   
4. Copyright and Trademark
   Content: Lorem ipsum...
   
5. Jurisdiction
   Content: Lorem ipsum...

Settings:
- Allow Multiple Open: OFF (only one item open at a time)
- Animated: ON
- Background Color: Light Blue
```

## Example: FAQ Section

```
Title: Frequently Asked Questions

Accordion Items:
1. What is your product?
   Content: Answer here...
   
2. How do I use it?
   Content: Answer here...
   
3. What's the pricing?
   Content: Answer here...

Settings:
- Allow Multiple Open: ON (users can compare multiple Q&As)
- Animated: ON
- Background Color: White
```

## Styling Notes

The accordion automatically uses your theme colors:
- **Light Blue**: `bg-light-blue` (default)
- **White**: White background with grey border
- **Grey**: `bg-grey-1`

Text colors are inherited from Tailwind prose classes:
- Headings: `text-grey-7` (dark grey)
- Body text: `text-grey-7` with hover states
- List markers: `text-primary` (red)

## Tips & Tricks

### Open First Item by Default
Currently, all items start closed. To change this, you'd need to modify the template (advanced).

### Custom CSS
Add custom styles to override accordion appearance:

```css
/* In your theme CSS */
.accordion-item {
    /* Your custom styles */
}

.accordion-item button:hover {
    /* Button hover state */
}
```

### WYSIWYG Editor
The accordion content uses a WYSIWYG editor, so you can:
- Format text (bold, italic, underline)
- Add links
- Create lists (bullets, numbered)
- Add blockquotes
- Embed basic HTML

## Browser Compatibility

Works on:
- ✅ Chrome/Edge 79+
- ✅ Firefox 75+
- ✅ Safari 12+
- ✅ Mobile browsers

## Troubleshooting

**Accordion not showing?**
- Make sure "Hide Block" is unchecked
- Ensure you have at least one accordion item added

**Animation not working?**
- Check that "Animated" is toggled ON
- Clear browser cache (Ctrl+Shift+Delete)

**Colors look wrong?**
- Check your theme's color configuration
- Verify Tailwind CSS is properly loaded

**Text won't format?**
- Use the WYSIWYG toolbar in the content editor
- Make sure you're in the content field (not title field)

## Next Steps

- Review full documentation: `ACCORDION-BLOCK-SETUP.md`
- Check template code: `templates/blocks/accordion_block.php`
- Explore Alpine.js: https://alpinejs.dev

---

**Setup Complete!** Your accordion is ready to use. Just add it to any page with flexible content.
